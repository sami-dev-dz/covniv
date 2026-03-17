<?php
namespace App\Controllers;
use PDO;

class RideController extends Controller {
    
    public function search() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        if (!isset($_SESSION['user_id'])) {
            header("Location: /login");
            exit();
        }
        
        $this->view('rides/search');
    }
    
    public function results() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        if (!isset($_SESSION['user_id'])) {
            header("Location: /login");
            exit();
        }
        
        $db = \Database::getConnection();
        
        $lieu_depart = $_GET['lieu_depart'] ?? '';
        $lieu_arrivee = $_GET['lieu_arrivee'] ?? '';
        $date_depart = $_GET['date_depart'] ?? date('Y-m-d');
        $heure_depart = $_GET['heure_depart'] ?? '';
        $prix_max = $_GET['prix'] ?? 1000;
        $places_min = $_GET['places'] ?? 1;
        
        $sql = "SELECT t.*, u.user_id, i.prenom, i.nom, i.email, i.telephone, i.photo_profil, i.sexe
                FROM trajets t
                JOIN users u ON t.conducteur_id = u.user_id
                JOIN infos i ON u.num_carte = i.num_carte
                WHERE 1=1";
        
        $params = [];
        
        if (!empty($lieu_depart)) {
            $sql .= " AND lieu_depart = ?";
            $params[] = $lieu_depart;
        }
        if (!empty($lieu_arrivee)) {
            $sql .= " AND lieu_arrivee = ?";
            $params[] = $lieu_arrivee;
        }
        if (!empty($date_depart)) {
            $sql .= " AND date_depart >= ?";
            $params[] = $date_depart;
        }
        if (!empty($heure_depart)) {
            $sql .= " AND heure_depart >= ?";
            $params[] = $heure_depart;
        }
        if (!empty($prix_max)) {
            $sql .= " AND prix <= ?";
            $params[] = $prix_max;
        }
        if (!empty($places_min)) {
            $sql .= " AND places_disponibles >= ?";
            $params[] = $places_min;
        }
        $sql .= " ORDER BY date_depart ASC";
        
        $stmt = $db->prepare($sql);
        $stmt->execute($params);
        $all_trips = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $available_trips = [];
        $user_id = (int)$_SESSION['user_id'];
        
        foreach ($all_trips as $trajet) {
            $sql_check = "SELECT 1 FROM reservations WHERE trajet_id = ? AND passager_id = ? LIMIT 1";
            $stmt_check = $db->prepare($sql_check);
            $stmt_check->execute([$trajet['trajet_id'], $user_id]);
            
            if (!$stmt_check->fetch()) {
                $available_trips[] = $trajet;
            }
        }
        
        $data = [
            'available_trips' => $available_trips,
            'available_count' => count($available_trips),
            'places_min' => $places_min
        ];
        
        $this->view('rides/results', $data);
    }
    
    public function showPublishForm() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        if (!isset($_SESSION['user_id'])) {
            header("Location: /login");
            exit();
        }
        
        $db = \Database::getConnection();
        $user_id = $_SESSION['user_id'];
        
        $sql = "SELECT * FROM voitures WHERE proprietaire_id = ?";
        $stmt = $db->prepare($sql);
        $stmt->execute([$user_id]);
        $user_voitures = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $data = [
            'user_voitures' => $user_voitures,
            'errors' => $_SESSION['trajet_errors'] ?? [],
            'success_message' => $_SESSION['success_message'] ?? null,
            'success_message_voiture' => $_SESSION['success_message_voiture'] ?? null
        ];
        
        unset($_SESSION['trajet_errors']);
        unset($_SESSION['success_message']);
        unset($_SESSION['success_message_voiture']);
        
        $this->view('rides/publish', $data);
    }
    
    public function publish() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        if (!isset($_SESSION['user_id'])) {
            header("Location: /login");
            exit();
        }
        
        $db = \Database::getConnection();
        $errors = [];
        
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $csrfToken = filter_input(INPUT_POST, 'csrf_token', FILTER_SANITIZE_SPECIAL_CHARS);
            if (!\App\Services\AuthSecurity::validateCsrfToken($csrfToken)) {
                $errors[] = "Erreur de sécurité : session expirée ou requête invalide.";
            }

            $required_fields = [
                "voiture" ,"date_depart", "heure_depart", 
                "lieu_depart", "adresse_depart", "lieu_arrivee", 
                "places_disponibles", "prix"
            ];
            
            foreach ($required_fields as $field) {
                if (empty($_POST[$field])) {
                    $errors[] = "Le champ " . str_replace('_', ' ', $field) . " est requis.";
                }
            }

            if (empty($errors)) {
                $date_heure_depart = $_POST["date_depart"] . ' ' . $_POST["heure_depart"];
                if (strtotime($date_heure_depart) < time()) {
                    $errors[] = "La date et l'heure de départ doivent être dans le futur.";
                }
            }

            if (empty($errors)) {
                $voiture_id = $_POST["voiture"];
                $date_depart = $_POST["date_depart"];
                $heure_depart = $_POST["heure_depart"];
                $lieu_depart = $_POST["lieu_depart"];
                $adresse_depart = $_POST["adresse_depart"];
                $lieu_arrivee = $_POST["lieu_arrivee"];
                $adresse_arrivee = $_POST["adresse_arrivee"];
                $places_disponibles = $_POST["places_disponibles"];
                $prix = $_POST["prix"];
                $notes = $_POST["notes"] ?? '';

                $sql = "INSERT INTO trajets (conducteur_id, voiture_id, date_depart, heure_depart, lieu_depart, adresse_depart, lieu_arrivee, adresse_arrivee, places_disponibles, prix, notes) 
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                $stmt = $db->prepare($sql);
                
                if ($stmt->execute([
                    $_SESSION['user_id'], $voiture_id, $date_depart, $heure_depart, 
                    $lieu_depart, $adresse_depart, $lieu_arrivee, $adresse_arrivee, 
                    $places_disponibles, $prix, $notes
                ])) {
                    $_SESSION["success_message"] = "Trajet publié avec succès!";
                    header("Location: /principal");
                    exit();
                } else {
                    $errors[] = "Erreur lors de la publication du trajet.";
                }
            }
        }
        
        $_SESSION['trajet_errors'] = $errors;
        header("Location: /publier-trajet");
        exit();
    }
}
