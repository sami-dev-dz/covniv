<?php
namespace App\Controllers;


use PDO;

class ProfileController extends Controller {
    
    public function index() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        if (!isset($_SESSION['user_id'])) {
            header("Location: /login");
            exit();
        }
        
        $db = \Database::getConnection();
        $user_id = $_SESSION['user_id'];
        
        // Récupérer les informations de l'utilisateur
        $sql = "SELECT * FROM infos WHERE user_info_id = ?";
        $stmt = $db->prepare($sql);
        $stmt->execute([$user_id]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        $uploadError = '';
        
        // Gestion de l'upload de photo
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['photo_profil']) && $_FILES['photo_profil']['error'] == UPLOAD_ERR_OK) {
            $csrfToken = filter_input(INPUT_POST, 'csrf_token', FILTER_SANITIZE_SPECIAL_CHARS);
            if (!\App\Services\AuthSecurity::validateCsrfToken($csrfToken)) {
                $uploadError = "Erreur de sécurité : session expirée ou requête invalide.";
            } else {
                $uploadError = $this->handlePhotoUpload($db, $user_id, $user);
                if (empty($uploadError)) {
                    header("Location: /profil");
                    exit();
                }
            }
        }
        
        // Gestion de la suppression de photo
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_photo']) && $_POST['delete_photo'] === 'true') {
            $csrfToken = filter_input(INPUT_POST, 'csrf_token', FILTER_SANITIZE_SPECIAL_CHARS);
            if (\App\Services\AuthSecurity::validateCsrfToken($csrfToken)) {
                $this->deletePhoto($db, $user_id, $user);
            }
            header("Location: /profil");
            exit();
        }
        
        $profilePicturePath = $user['photo_profil'] ?? '';
        
        $data = [
            'user' => $user,
            'profilePicturePath' => $profilePicturePath,
            'uploadError' => $uploadError
        ];
        
        $this->view('profile/index', $data);
    }
    
    private function handlePhotoUpload($db, $user_id, &$user) {
        $uploadDir = 'uploads/profile_pictures/';
        
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }
        
        $file = $_FILES['photo_profil'];
        
        // Use finfo to get real MIME type
        $finfo = new \finfo(FILEINFO_MIME_TYPE);
        $mimeType = $finfo->file($file['tmp_name']);
        
        $allowedMimes = [
            'image/jpeg' => 'jpg',
            'image/png' => 'png',
            'image/gif' => 'gif',
            'image/webp' => 'webp'
        ];
        
        if (!array_key_exists($mimeType, $allowedMimes)) {
            return "Type de fichier invalide. Seuls JPG, PNG, GIF, et WEBP sont autorisés.";
        }
        
        $fileExtension = $allowedMimes[$mimeType];
        
        // Generate a random, unguessable filename to prevent path traversal and overwriting
        try {
            $fileName = bin2hex(random_bytes(16)) . '.' . $fileExtension;
        } catch (\Exception $e) {
            $fileName = uniqid('img_', true) . '.' . $fileExtension;
        }
        
        $targetPath = $uploadDir . $fileName;
        
        if ($file['size'] > 5 * 1024 * 1024) {
            return "Image trop volumineuse. Max 5MB.";
        }
        
        list($width, $height) = getimagesize($file['tmp_name']);
        if (!$width || !$height) {
             return "Le fichier n'est pas une image valide.";
        }
        
        $maxWidth = 800;
        $maxHeight = 800;
        
        if ($width > $maxWidth || $height > $maxHeight) {
            $ratio = min($maxWidth / $width, $maxHeight / $height);
            $newWidth = (int)round($width * $ratio);
            $newHeight = (int)round($height * $ratio);
            
            $srcImage = null;
            $dstImage = imagecreatetruecolor($newWidth, $newHeight);
            
            switch ($mimeType) {
                case 'image/jpeg':
                    $srcImage = imagecreatefromjpeg($file['tmp_name']);
                    break;
                case 'image/png':
                    $srcImage = imagecreatefrompng($file['tmp_name']);
                    imagealphablending($dstImage, false);
                    imagesavealpha($dstImage, true);
                    break;
                case 'image/gif':
                    $srcImage = imagecreatefromgif($file['tmp_name']);
                    break;
                case 'image/webp':
                    $srcImage = imagecreatefromwebp($file['tmp_name']);
                    break;
            }
            
            if (!$srcImage) {
                return "Erreur lors du traitement de l'image.";
            }
            
            imagecopyresampled($dstImage, $srcImage, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
            
            if (!empty($user['photo_profil']) && file_exists($user['photo_profil'])) {
                unlink($user['photo_profil']);
            }
            
            switch ($mimeType) {
                case 'image/jpeg':
                    imagejpeg($dstImage, $targetPath, 85);
                    break;
                case 'image/png':
                    imagepng($dstImage, $targetPath, 8);
                    break;
                case 'image/gif':
                    imagegif($dstImage, $targetPath);
                    break;
                case 'image/webp':
                    imagewebp($dstImage, $targetPath, 85);
                    break;
            }
            
            imagedestroy($srcImage);
            imagedestroy($dstImage);
        } else {
            if (!empty($user['photo_profil']) && file_exists($user['photo_profil'])) {
                unlink($user['photo_profil']);
            }
            
            if (!move_uploaded_file($file['tmp_name'], $targetPath)) {
                return "Erreur lors de la sauvegarde du fichier.";
            }
        }
        
        $updateSql = "UPDATE infos SET photo_profil = ? WHERE user_info_id = ?";
        $updateStmt = $db->prepare($updateSql);
        $updateStmt->execute([$targetPath, $user_id]);
        
        $_SESSION['photo_profil'] = $targetPath;
        
        return '';
    }
    
    
    private function deletePhoto($db, $user_id, &$user) {
        if (!empty($user['photo_profil']) && file_exists($user['photo_profil'])) {
            unlink($user['photo_profil']);
        }
        
        $updateSql = "UPDATE infos SET photo_profil = NULL WHERE user_info_id = ?";
        $updateStmt = $db->prepare($updateSql);
        $updateStmt->execute([$user_id]);
        
        $_SESSION['photo_profil'] = null;
    }

    public function showAddVehicle() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        if (!isset($_SESSION['user_id'])) {
            header("Location: /login");
            exit();
        }
        
        $data = [
            'errors' => $_SESSION['form_errors'] ?? [],
            'formData' => $_SESSION['form_data'] ?? []
        ];
        
        unset($_SESSION['form_errors']);
        unset($_SESSION['form_data']);
        
        $this->view('profile/add-vehicle', $data);
    }
    
    public function addVehicle() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        if (!isset($_SESSION['user_id'])) {
            header("Location: /login");
            exit();
        }
        
        $db = \Database::getConnection();
        $errors = [];
        
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $csrfToken = filter_input(INPUT_POST, 'csrf_token', FILTER_SANITIZE_SPECIAL_CHARS);
            if (!\App\Services\AuthSecurity::validateCsrfToken($csrfToken)) {
                $errors[] = "Erreur de sécurité : session expirée ou requête invalide.";
            }

            $modele = filter_input(INPUT_POST, 'modele', FILTER_SANITIZE_SPECIAL_CHARS);
            $marque = filter_input(INPUT_POST, 'marque', FILTER_SANITIZE_SPECIAL_CHARS);
            $annee = filter_input(INPUT_POST, 'annee', FILTER_SANITIZE_NUMBER_INT);
            $couleur = filter_input(INPUT_POST, 'couleur', FILTER_SANITIZE_SPECIAL_CHARS);
            $plaque_immatriculation = filter_input(INPUT_POST, 'plaque_immatriculation', FILTER_SANITIZE_SPECIAL_CHARS);
            $nombre_places = filter_input(INPUT_POST, 'nombre_places', FILTER_SANITIZE_NUMBER_INT);
            $proprietaire_id = $_SESSION['user_id'];

            if (empty($modele)) $errors[] = "Le modèle est requis.";
            if (empty($marque)) $errors[] = "La marque est requise.";
            if (empty($annee)) $errors[] = "L'année est requise.";
            if (empty($plaque_immatriculation)) $errors[] = "La plaque d'immatriculation est requise.";
            if (empty($nombre_places)) $errors[] = "Le nombre de places est requis.";

            if (empty($errors)) {
                $sql = "SELECT plaque_immatriculation FROM voitures WHERE plaque_immatriculation = ?";
                $stmt = $db->prepare($sql);
                $stmt->execute([$plaque_immatriculation]);
                
                if ($stmt->fetch()) {
                    $errors[] = "Cette plaque d'immatriculation est déjà enregistrée.";
                }
            }

            if (empty($errors)) {
                $sql = "INSERT INTO voitures (proprietaire_id, modele, marque, annee, couleur, plaque_immatriculation, nombre_places) 
                        VALUES (?, ?, ?, ?, ?, ?, ?)";
                $stmt = $db->prepare($sql);
                
                if ($stmt->execute([
                    $proprietaire_id, $modele, $marque, $annee, 
                    $couleur, $plaque_immatriculation, $nombre_places
                ])) {
                    $_SESSION['success_message_voiture'] = "Véhicule ajouté avec succès!";
                    header("Location: /publier-trajet");
                    exit();
                } else {
                    $errors[] = "Erreur lors de l'ajout du véhicule.";
                }
            }
        }

        if (!empty($errors)) {
            $_SESSION['form_errors'] = $errors;
            $_SESSION['form_data'] = $_POST;
            header("Location: /ajouter-vehicule");
            exit();
        }
    }
}
