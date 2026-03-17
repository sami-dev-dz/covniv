<?php
namespace App\Services;

class AuthSecurity {
    
    // CSRF Protection
    public static function generateCsrfToken() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }
    
    public static function validateCsrfToken($token) {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $token)) {
            return false;
        }
        return true;
    }
    
    // Auth Logging
    public static function logAuthEvent($userId, $numCarte, $action) {
        $conn = \Database::getConnection();
        $ip = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
        
        $stmt = $conn->prepare("INSERT INTO auth_logs (user_id, num_carte, ip_address, action) VALUES (:user_id, :num_carte, :ip_address, :action)");
        $stmt->execute([
            'user_id' => $userId,
            'num_carte' => $numCarte,
            'ip_address' => $ip,
            'action' => $action
        ]);
    }
    
    // Rate Limiting & Brute Force Protection
    public static function isRateLimited($actionType, $maxAttempts = 5, $lockoutMinutes = 15) {
        $conn = \Database::getConnection();
        $ip = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
        
        // Clean up old lockouts
        $conn->query("UPDATE rate_limits SET attempts = 0, locked_until = NULL WHERE locked_until < NOW()");
        
        $stmt = $conn->prepare("SELECT * FROM rate_limits WHERE ip_address = :ip AND action_type = :action");
        $stmt->execute(['ip' => $ip, 'action' => $actionType]);
        $record = $stmt->fetch();
        
        if ($record) {
            if ($record['locked_until'] !== null && strtotime($record['locked_until']) > time()) {
                return true; // Still locked out
            }
        }
        return false;
    }
    
    public static function recordAttempt($actionType, $maxAttempts = 5, $lockoutMinutes = 15) {
        $conn = \Database::getConnection();
        $ip = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
        
        $stmt = $conn->prepare("SELECT * FROM rate_limits WHERE ip_address = :ip AND action_type = :action");
        $stmt->execute(['ip' => $ip, 'action' => $actionType]);
        $record = $stmt->fetch();
        
        if ($record) {
            $newAttempts = $record['attempts'] + 1;
            $lockedUntil = null;
            
            if ($newAttempts >= $maxAttempts) {
                // Set lockout
                $lockedUntil = date('Y-m-d H:i:s', time() + ($lockoutMinutes * 60));
            }
            
            $update = $conn->prepare("UPDATE rate_limits SET attempts = :attempts, locked_until = :locked, last_attempt_at = NOW() WHERE id = :id");
            $update->execute([
                'attempts' => $newAttempts,
                'locked' => $lockedUntil,
                'id' => $record['id']
            ]);
        } else {
            $insert = $conn->prepare("INSERT INTO rate_limits (ip_address, action_type, attempts) VALUES (:ip, :action, 1)");
            $insert->execute([
                'ip' => $ip,
                'action' => $actionType
            ]);
        }
    }
    
    public static function clearAttempts($actionType) {
        $conn = \Database::getConnection();
        $ip = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
        
        $stmt = $conn->prepare("DELETE FROM rate_limits WHERE ip_address = :ip AND action_type = :action");
        $stmt->execute(['ip' => $ip, 'action' => $actionType]);
    }
    
    // Secure Remember Me
    public static function createRememberToken($userId) {
        $conn = \Database::getConnection();
        $token = bin2hex(random_bytes(32));
        $tokenHash = hash('sha256', $token);
        // Expiry in 30 days
        $expires = date('Y-m-d H:i:s', time() + (30 * 24 * 60 * 60));
        
        // Remove old tokens for this user to keep it clean, or allow multiple devices
        // Let's clear old ones for simplicity
        $stmt = $conn->prepare("DELETE FROM remember_tokens WHERE user_id = :userId");
        $stmt->execute(['userId' => $userId]);
        
        $stmt = $conn->prepare("INSERT INTO remember_tokens (user_id, token_hash, expires_at) VALUES (:userId, :tokenHash, :expires)");
        $stmt->execute([
            'userId' => $userId,
            'tokenHash' => $tokenHash,
            'expires' => $expires
        ]);
        
        // Return clear token to send to browser
        return $userId . ':' . $token; 
    }
    
    public static function validateRememberToken($cookieValue) {
        if (empty($cookieValue) || strpos($cookieValue, ':') === false) {
            return false;
        }
        
        list($userId, $token) = explode(':', $cookieValue, 2);
        
        $conn = \Database::getConnection();
        $stmt = $conn->prepare("SELECT * FROM remember_tokens WHERE user_id = :userId AND expires_at > NOW()");
        $stmt->execute(['userId' => $userId]);
        $tokens = $stmt->fetchAll();
        
        foreach ($tokens as $record) {
            if (hash_equals($record['token_hash'], hash('sha256', $token))) {
                return $record['user_id'];
            }
        }
        
        return false;
    }
    
    // API Security & Authentication
    public static function handleApiAuth() {
        // CORS Headers
        self::setCorsHeaders();

        // Handle pre-flight OPTIONS request
        if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
            http_response_code(200);
            exit();
        }

        // API Rate Limiting (10 requests per minute per IP)
        if (self::isRateLimited('api', 10, 1)) {
            self::apiError("Too Many Requests. Please wait before retrying.", 429);
            return false;
        }
        self::recordAttempt('api', 10, 1);

        $headers = getallheaders();
        $authHeader = $headers['Authorization'] ?? $headers['authorization'] ?? '';

        if (empty($authHeader) || strpos($authHeader, 'Bearer ') !== 0) {
            self::apiError("Unauthorized: Missing or invalid token", 401);
            return false;
        }

        $token = substr($authHeader, 7);
        if (!self::validateBearerToken($token)) {
            self::apiError("Unauthorized: Invalid token", 401);
            return false;
        }

        return true;
    }


    private static function setCorsHeaders() {
        $origin = $_SERVER['HTTP_ORIGIN'] ?? '*';
        // In production, restrict this to allowed origins
        header("Access-Control-Allow-Origin: $origin");
        header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
        header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
        header("Access-Control-Allow-Credentials: true");
    }

    private static function validateBearerToken($token) {
        // Simplified for now: check against a hardcoded key or a database lookup in the future
        $apiKey = getenv('API_KEY') ?: 'dummy-dev-key';
        return hash_equals($apiKey, $token);
    }

    private static function apiError($message, $status = 401) {
        http_response_code($status);
        header('Content-Type: application/json');
        echo json_encode(['error' => $message]);
        exit();
    }

    // Input Sanitization helper
    public static function sanitize($input) {
        if (is_array($input)) {
            foreach($input as $key => $value) {
                $input[$key] = self::sanitize($value);
            }
            return $input;
        }
        return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
    }
}
