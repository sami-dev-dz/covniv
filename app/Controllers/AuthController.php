<?php
namespace App\Controllers;
use PDO;

class AuthController extends Controller {
    
    public function showLoginForm() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $data = [
            'errors' => $_SESSION['form_errors'] ?? [],
            'formData' => $_SESSION['form_data'] ?? [],
            'csrf_token' => \App\Services\AuthSecurity::generateCsrfToken()
        ];
        unset($_SESSION['form_errors']);
        unset($_SESSION['form_data']);

        $this->view('auth/login', $data);
    }

    public function login() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $errors = [];
        $fieldErrors = ["num_carte" => "", "password" => ""];

        // CSRF Verification
        $csrfToken = filter_input(INPUT_POST, 'csrf_token', FILTER_SANITIZE_SPECIAL_CHARS);
        if (!\App\Services\AuthSecurity::validateCsrfToken($csrfToken)) {
            $errors[] = "Erreur de sécurité : session expirée ou requête invalide.";
        }

        // Rate Limiting Check
        if (\App\Services\AuthSecurity::isRateLimited('login', 5, 15)) {
            $errors[] = "Trop de tentatives échouées. Veuillez réessayer dans 15 minutes.";
        }

        // Proceed if no early errors
        if (empty($errors)) {
            $num_carte = filter_input(INPUT_POST, 'num_carte', FILTER_SANITIZE_NUMBER_INT);
            $password = $_POST["password"] ?? '';
            $rememberMe = isset($_POST["rememberMe"]);

            if (empty($num_carte)) {
                $fieldErrors["num_carte"] = "Le numéro de carte est requis.";
            }
            if (empty($password)) {
                $fieldErrors["password"] = "Le mot de passe est requis.";
            }

            if (empty($fieldErrors["num_carte"]) && empty($fieldErrors["password"])) {
                $conn = \Database::getConnection();
                $stmt = $conn->prepare("SELECT * FROM users WHERE num_carte = :num_carte");
                $stmt->execute(['num_carte' => $num_carte]);
                $user = $stmt->fetch();

                if ($user) {
                    if (password_verify($password, $user["password"])) {
                        // Prevent Session Fixation
                        session_regenerate_id(true);

                        $_SESSION['user_id'] = $user["user_id"];
                        
                        $stmt_info = $conn->prepare("SELECT prenom FROM infos WHERE user_info_id = :user_id");
                        $stmt_info->execute(['user_id' => $user["user_id"]]);
                        $user_info = $stmt_info->fetch();
                        
                        if ($user_info) {
                            $_SESSION['prenom'] = $user_info["prenom"];
                            
                            if ($rememberMe) {
                                $cookie_expiry = time() + (30 * 24 * 60 * 60);
                                $token = \App\Services\AuthSecurity::createRememberToken($user["user_id"]);
                                setcookie("remember_token", $token, $cookie_expiry, "/", "", true, true);
                            }
                            
                            \App\Services\AuthSecurity::clearAttempts('login');
                            \App\Services\AuthSecurity::logAuthEvent($user["user_id"], $num_carte, 'login_success');
                            
                            header("Location: /principal");
                            exit();
                        } else {
                            $errors[] = "Profil utilisateur incomplet.";
                            \App\Services\AuthSecurity::logAuthEvent($user["user_id"], $num_carte, 'login_failed_profile_incomplete');
                        }
                    } else {
                        $errors[] = "Mot de passe incorrect.";
                        \App\Services\AuthSecurity::recordAttempt('login');
                        \App\Services\AuthSecurity::logAuthEvent($user["user_id"] ?? null, $num_carte, 'login_failed_password');
                    }
                } else {
                    $errors[] = "Aucun compte trouvé avec ce numéro de carte.";
                    \App\Services\AuthSecurity::recordAttempt('login');
                    \App\Services\AuthSecurity::logAuthEvent(null, $num_carte, 'login_failed_user_not_found');
                }
            }
        }

        $_SESSION['form_errors'] = array_merge($errors, array_filter($fieldErrors));
        $_SESSION['form_data'] = ['num_carte' => $num_carte ?? '', 'rememberMe' => $rememberMe ?? false];
        
        header("Location: /login");
        exit();
    }

    public function showSignUpForm() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $data = [
            'errors' => $_SESSION['form_errors'] ?? [],
            'formData' => $_SESSION['form_data'] ?? [],
            'csrf_token' => \App\Services\AuthSecurity::generateCsrfToken()
        ];
        unset($_SESSION['form_errors']);
        unset($_SESSION['form_data']);

        $this->view('auth/sign-up', $data);
    }

    public function register() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $errors = [];

        // CSRF Verification
        $csrfToken = filter_input(INPUT_POST, 'csrf_token', FILTER_SANITIZE_SPECIAL_CHARS);
        if (!\App\Services\AuthSecurity::validateCsrfToken($csrfToken)) {
            $errors[] = "Erreur de sécurité : session expirée ou requête invalide.";
        }

        // Rate Limiting Check
        if (\App\Services\AuthSecurity::isRateLimited('register', 3, 30)) {
            $errors[] = "Trop de tentatives d'inscription. Veuillez réessayer plus tard.";
        }

        if (empty($errors)) {
            $nom = filter_input(INPUT_POST, 'nom', FILTER_SANITIZE_SPECIAL_CHARS);
            $prenom = filter_input(INPUT_POST, 'prenom', FILTER_SANITIZE_SPECIAL_CHARS);
            $num_carte = filter_input(INPUT_POST, 'num_carte', FILTER_SANITIZE_NUMBER_INT);
            $telephone = filter_input(INPUT_POST, 'telephone', FILTER_SANITIZE_NUMBER_INT);
            $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
            $sexe = filter_input(INPUT_POST, 'sexe', FILTER_SANITIZE_SPECIAL_CHARS) ?? '';
            $password = $_POST["password"] ?? '';
            $passwordRepeat = $_POST["repeat_password"] ?? '';

            if (empty($nom) || empty($prenom) || empty($telephone) || empty($num_carte) || empty($email) || empty($sexe) || empty($password) || empty($passwordRepeat)) {
                $errors[] = "Tous les champs sont requis.";
            }

            if (!ctype_digit($num_carte)) {
                $errors[] = "Le numéro de carte doit contenir uniquement des chiffres.";
            }

            if (strlen((string)$num_carte) != 8) {
                $errors[] = "Le numéro de carte étudiant doit contenir exactement 8 chiffres.";
            }

            if (!ctype_digit((string)$telephone)) {
                $errors[] = "Le numéro de téléphone doit contenir uniquement des chiffres.";
            } elseif (strlen((string)$telephone) != 10) {
                $errors[] = "Le numéro de téléphone doit contenir exactement 10 chiffres.";
            }

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors[] = "L'adresse email n'est pas valide.";
            }

            if ($password !== $passwordRepeat) {
                $errors[] = "Les mots de passe ne correspondent pas.";
            }

            if (strlen($password) < 8) {
                $errors[] = "Le mot de passe doit contenir au moins 8 caractères.";
            }

            $conn = \Database::getConnection();

            if (empty($errors)) {
                $stmt = $conn->prepare("SELECT num_carte FROM users WHERE num_carte = :num");
                $stmt->execute(['num' => $num_carte]);
                if ($stmt->fetch()) {
                    $errors[] = "Un compte avec ce numéro de carte existe déjà.";
                }

                $stmt_email = $conn->prepare("SELECT email FROM infos WHERE email = :email");
                $stmt_email->execute(['email' => $email]);
                if ($stmt_email->fetch()) {
                    $errors[] = "Un compte avec cette adresse email existe déjà.";
                }
            }

            if (empty($errors)) {
                $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
                try {
                    $conn->beginTransaction();

                    $stmt1 = $conn->prepare("INSERT INTO users (num_carte, password) VALUES (:num, :pass)");
                    $stmt1->execute(['num' => $num_carte, 'pass' => $hashedPassword]);
                    $user_id = $conn->lastInsertId();

                    $stmt2 = $conn->prepare("INSERT INTO infos (user_info_id, num_carte, prenom, nom, email, telephone, sexe, password, date_inscription, est_actif) VALUES (:id, :num, :prenom, :nom, :email, :tel, :sexe, :pass, NOW(), 1)");
                    $stmt2->execute([
                        'id' => $user_id, 'num' => $num_carte, 'prenom' => $prenom, 'nom' => $nom, 
                        'email' => $email, 'tel' => $telephone, 'sexe' => $sexe, 'pass' => $hashedPassword
                    ]);

                    $conn->commit();

                    session_regenerate_id(true);
                    $_SESSION['user_id'] = $user_id;
                    $_SESSION['num_carte'] = $num_carte;
                    $_SESSION['prenom'] = $prenom;

                    \App\Services\AuthSecurity::clearAttempts('register');
                    \App\Services\AuthSecurity::logAuthEvent($user_id, $num_carte, 'registration');

                    header("Location: /principal");
                    exit();
                } catch (\Exception $e) {
                    $conn->rollBack();
                    error_log("Registration Error (User $num_carte): " . $e->getMessage(), 3, dirname(__DIR__, 2) . '/logs/error.log');
                    $errors[] = "Une erreur est survenue lors de l'inscription.";
                }
            } else {
                \App\Services\AuthSecurity::recordAttempt('register', 3, 30);
            }
        }

        $_SESSION['form_errors'] = $errors;
        $_SESSION['form_data'] = $_POST;
        header("Location: /sign-up");
        exit();
    }

    public function logout() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (isset($_SESSION['user_id'])) {
            \App\Services\AuthSecurity::logAuthEvent($_SESSION['user_id'], $_SESSION['num_carte'] ?? null, 'logout');
        }

        $_SESSION = [];
        session_destroy();

        setcookie('remember_token', '', time() - 3600, '/', '', true, true);
        setcookie('user_id', '', time() - 3600, '/', '', true, true);
        setcookie('prenom', '', time() - 3600, '/', '', true, true);

        header("Location: /login");
        exit();
    }
}
