<?php
namespace App\Controllers;
use PDO;

class BookingController extends Controller {
    
    public function book() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        if (!isset($_SESSION['user_id'])) {
            header("Location: /login");
            exit();
        }
        
        $db = \Database::getConnection();
        $user_id = (int)$_SESSION['user_id'];
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Port logic from reservation.php POST part (reservation handling)
            // ... (similar to what was in reservation.php but using PDO and clean logic)
        }
        
        if (isset($_GET['id'])) {
            $trajet_id = $_GET['id'];
            $places_min = (int)($_GET['places_min'] ?? 1);
            
            // Check if existing reservation
            $sql_check = "SELECT * FROM reservations WHERE trajet_id = ? AND passager_id = ?";
            $stmt_check = $db->prepare($sql_check);
            $stmt_check->execute([$trajet_id, $user_id]);
            
            if ($stmt_check->fetch()) {
                $_SESSION['info_message'] = "Vous avez déjà réservé ce trajet.";
                header("Location: /trajets-disponibles");
                exit();
            }
            
            // Fetch ride details
            $sql = "SELECT t.*, i.prenom, i.nom, i.photo_profil, v.marque, v.modele, v.couleur, v.plaque_immatriculation
                    FROM trajets t
                    JOIN users u ON t.conducteur_id = u.user_id
                    JOIN infos i ON u.num_carte = i.num_carte
                    JOIN voitures v ON t.voiture_id = v.voiture_id
                    WHERE t.trajet_id = ?";
            $stmt = $db->prepare($sql);
            $stmt->execute([$trajet_id]);
            $trajet = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($trajet) {
                if ($trajet['conducteur_id'] == $user_id) {
                    $_SESSION['info_message'] = "Vous ne pouvez pas réserver votre propre trajet.";
                    header("Location: /trajets-disponibles");
                    exit();
                }
                
                // Perform reservation
                $db->beginTransaction();
                try {
                    $update_sql = "UPDATE trajets SET places_disponibles = places_disponibles - ? WHERE trajet_id = ? AND places_disponibles >= ?";
                    $update_stmt = $db->prepare($update_sql);
                    $update_stmt->execute([$places_min, $trajet_id, $places_min]);
                    
                    if ($update_stmt->rowCount() === 0) {
                        throw new \Exception("Pas assez de places disponibles.");
                    }
                    
                    $sql_res = "INSERT INTO reservations (trajet_id, passager_id, places_reservees, statut_reservation, date_creation) 
                                VALUES (?, ?, ?, 'en_attente', NOW())";
                    $stmt_res = $db->prepare($sql_res);
                    $stmt_res->execute([$trajet_id, $user_id, $places_min]);
                    
                    $db->commit();
                    
                    $data = [
                        'trajet' => $trajet,
                        'places_min' => $places_min
                    ];
                    $this->view('bookings/reserve', $data);
                } catch (\Exception $e) {
                    $db->rollBack();
                    $_SESSION['trajet_errors'] = [$e->getMessage()];
                    header("Location: /recherche-trajet");
                    exit();
                }
            } else {
                header("Location: /recherche-trajet");
                exit();
            }
        }
    }
    
    public function history() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        if (!isset($_SESSION['user_id'])) {
            header("Location: /login");
            exit();
        }
        
        $db = \Database::getConnection();
        $user_id = $_SESSION['user_id'];
        
        // Port logic from historique.php
        $sql_publies = "SELECT t.*, v.marque, v.modele 
                        FROM trajets t 
                        JOIN voitures v ON t.voiture_id = v.voiture_id 
                        WHERE t.conducteur_id = ? 
                        ORDER BY t.date_depart DESC";
        $stmt_publies = $db->prepare($sql_publies);
        $stmt_publies->execute([$user_id]);
        $trajets_publies = $stmt_publies->fetchAll(PDO::FETCH_ASSOC);
        
        $sql_reserves = "SELECT r.*, t.lieu_depart, t.lieu_arrivee, t.date_depart, t.heure_depart, t.prix, i.prenom, i.nom
                         FROM reservations r
                         JOIN trajets t ON r.trajet_id = t.trajet_id
                         JOIN users u ON t.conducteur_id = u.user_id
                         JOIN infos i ON u.num_carte = i.num_carte
                         WHERE r.passager_id = ?
                         ORDER BY t.date_depart DESC";
        $stmt_reserves = $db->prepare($sql_reserves);
        $stmt_reserves->execute([$user_id]);
        $trajets_reserves = $stmt_reserves->fetchAll(PDO::FETCH_ASSOC);
        
        $data = [
            'trajets_publies' => $trajets_publies,
            'trajets_reserves' => $trajets_reserves
        ];
        
        $this->view('bookings/history', $data);
    }
    
    public function requests() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        if (!isset($_SESSION['user_id'])) {
            header("Location: /login");
            exit();
        }
        
        $db = \Database::getConnection();
        $user_id = $_SESSION['user_id'];
        
        $sql = "SELECT r.*, t.lieu_depart, t.lieu_arrivee, t.date_depart, t.heure_depart, i.nom, i.prenom, i.photo_profil, i.telephone
                FROM reservations r
                JOIN trajets t ON r.trajet_id = t.trajet_id
                JOIN infos i ON r.passager_id = i.user_info_id
                WHERE t.conducteur_id = ? AND r.statut_reservation = 'en_attente'
                ORDER BY r.date_creation DESC";
        $stmt = $db->prepare($sql);
        $stmt->execute([$user_id]);
        $demandes = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $this->view('bookings/requests', ['demandes' => $demandes]);
    }

    public function updateStatus() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        if (!isset($_SESSION['user_id'])) {
            header("HTTP/1.1 401 Unauthorized");
            echo json_encode(['success' => false, 'message' => 'Unauthorized']);
            exit();
        }
        
        $db = \Database::getConnection();
        $csrfToken = filter_input(INPUT_POST, 'csrf_token', FILTER_SANITIZE_SPECIAL_CHARS);
        if (!\App\Services\AuthSecurity::validateCsrfToken($csrfToken)) {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Erreur CSRF']);
            exit();
        }

        $reservation_id = $_POST['reservation_id'] ?? null;
        $action = $_POST['action'] ?? null;
        
        if ($reservation_id && in_array($action, ['confirmee', 'annulee'])) {
            $db->beginTransaction();
            try {
                // Verify authorization
                $sql_verify = "SELECT t.conducteur_id, r.trajet_id, r.places_reservees, r.statut_reservation 
                               FROM reservations r 
                               JOIN trajets t ON r.trajet_id = t.trajet_id 
                               WHERE r.reservation_id = ?";
                $stmt_verify = $db->prepare($sql_verify);
                $stmt_verify->execute([$reservation_id]);
                $row = $stmt_verify->fetch(PDO::FETCH_ASSOC);

                if (!$row || $row['conducteur_id'] != $_SESSION['user_id']) {
                    $db->rollBack();
                    header('Content-Type: application/json');
                    echo json_encode(['success' => false, 'message' => 'Non autorisé']);
                    exit();
                }

                if ($action === 'annulee' && $row['statut_reservation'] !== 'annulee' && $row['statut_reservation'] !== 'refusee') {
                    $update_sql = "UPDATE trajets SET places_disponibles = places_disponibles + ? WHERE trajet_id = ?";
                    $update_stmt = $db->prepare($update_sql);
                    $update_stmt->execute([$row['places_reservees'], $row['trajet_id']]);
                }
                
                $sql = "UPDATE reservations SET statut_reservation = ? WHERE reservation_id = ?";
                $stmt = $db->prepare($sql);
                $stmt->execute([$action, $reservation_id]);
                
                $db->commit();
                header('Content-Type: application/json');
                echo json_encode(['success' => true]);
                exit();
            } catch (\Exception $e) {
                $db->rollBack();
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'message' => $e->getMessage()]);
                exit();
            }
        }
        
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'message' => 'Invalid data']);
        exit();
    }

    public function cancel() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        if (!isset($_SESSION['user_id'])) {
            header("Location: /login");
            exit();
        }
        
        $db = \Database::getConnection();
        $csrfToken = filter_input(INPUT_POST, 'csrf_token', FILTER_SANITIZE_SPECIAL_CHARS);
        if (!\App\Services\AuthSecurity::validateCsrfToken($csrfToken)) {
            $_SESSION['error_message'] = "Erreur de sécurité : session expirée ou requête invalide.";
            header("Location: /historique");
            exit();
        }

        $reservation_id = $_POST['reservation_id'] ?? null;
        $user_id = $_SESSION['user_id'];
        
        if ($reservation_id) {
            $db->beginTransaction();
            try {
                $sql_check = "SELECT * FROM reservations WHERE reservation_id = ? AND passager_id = ?";
                $stmt_check = $db->prepare($sql_check);
                $stmt_check->execute([$reservation_id, $user_id]);
                $reservation = $stmt_check->fetch(PDO::FETCH_ASSOC);
                
                if ($reservation && $reservation['statut_reservation'] !== 'annulee' && $reservation['statut_reservation'] !== 'refusee') {
                    $update_sql = "UPDATE reservations SET statut_reservation = 'annulee' WHERE reservation_id = ?";
                    $update_stmt = $db->prepare($update_sql);
                    $update_stmt->execute([$reservation_id]);
                    
                    $places_sql = "UPDATE trajets SET places_disponibles = places_disponibles + ? WHERE trajet_id = ?";
                    $places_stmt = $db->prepare($places_sql);
                    $places_stmt->execute([$reservation['places_reservees'], $reservation['trajet_id']]);
                    
                    $db->commit();
                    $_SESSION['success_message'] = "Réservation annulée avec succès";
                } else {
                    $db->rollBack();
                }
            } catch (\Exception $e) {
                $db->rollBack();
                $_SESSION['error_message'] = "Erreur lors de l'annulation";
            }
        }
        
        header("Location: /historique");
        exit();
    }

    public function terminate() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        if (!isset($_SESSION['user_id'])) {
            header("Location: /login");
            exit();
        }
        
        $db = \Database::getConnection();
        $csrfToken = filter_input(INPUT_POST, 'csrf_token', FILTER_SANITIZE_SPECIAL_CHARS);
        if (!\App\Services\AuthSecurity::validateCsrfToken($csrfToken)) {
            $_SESSION['error_message'] = "Erreur de sécurité : session expirée ou requête invalide.";
            header("Location: /historique");
            exit();
        }

        $reservation_id = $_POST['reservation_id'] ?? null;
        $user_id = $_SESSION['user_id'];
        
        if ($reservation_id) {
            $sql_update = "UPDATE reservations SET statut_reservation = 'terminee' WHERE reservation_id = ? AND passager_id = ?";
            $stmt = $db->prepare($sql_update);
            $stmt->execute([$reservation_id, $user_id]);
            $_SESSION['success_message'] = "Voyage terminé";
        }
        
        header("Location: /historique");
        exit();
    }
}
