<?php
namespace App\Controllers;


use PDO;

class HomeController extends Controller {
    
    public function index() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        // Si l'utilisateur est connecté, rediriger vers le tableau de bord
        if (isset($_SESSION['user_id'])) {
            header("Location: /principal");
            exit();
        }
        
        // Sinon afficher la page d'accueil publique
        $this->view('home/index');
    }
    
    public function principal() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        if (!isset($_SESSION['user_id'])) {
            header("Location: /login");
            exit();
        }
        
        $db = \Database::getConnection();
        $conducteur_id = $_SESSION['user_id'];
        
        // Gérer les actions sur les réservations (AJAX)
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && isset($_POST['reservation_id'])) {
            $this->handleReservationAction($db, $_POST['reservation_id'], $_POST['action']);
            exit;
        }
        
        // Récupérer les trajets du conducteur
        $trajets_sql = "SELECT trajet_id FROM trajets WHERE conducteur_id = ?";
        $stmt = $db->prepare($trajets_sql);
        $stmt->execute([$conducteur_id]);
        $trajet_ids = $stmt->fetchAll(PDO::FETCH_COLUMN);
        
        // Récupérer les demandes reçues en attente
        $demandesRecues = [];
        if (!empty($trajet_ids)) {
            $placeholders = implode(',', array_fill(0, count($trajet_ids), '?'));
            $sql = "
                SELECT r.* 
                FROM reservations r
                JOIN infos i ON r.passager_id = i.user_info_id
                JOIN trajets t ON r.trajet_id = t.trajet_id
                WHERE r.trajet_id IN ($placeholders) AND r.statut_reservation = 'en_attente'
            ";
            $stmt = $db->prepare($sql);
            $stmt->execute($trajet_ids);
            $demandesRecues = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        
        // Récupérer les messages non lus
        $sql = "
            SELECT m1.*, i.nom, i.prenom, i.photo_profil,
                   (SELECT COUNT(*) FROM messages WHERE expediteur_id = m1.expediteur_id AND destinataire_id = ? AND est_lu = 0) AS unread_count
            FROM messages m1
            JOIN users u ON m1.expediteur_id = u.user_id
            JOIN infos i ON u.num_carte = i.num_carte
            INNER JOIN (
                SELECT expediteur_id, MAX(date_envoi) AS last_date
                FROM messages
                WHERE destinataire_id = ?
                GROUP BY expediteur_id
            ) m2 ON m1.expediteur_id = m2.expediteur_id AND m1.date_envoi = m2.last_date
            ORDER BY m1.date_envoi DESC
        ";
        $stmt = $db->prepare($sql);
        $stmt->execute([$conducteur_id, $conducteur_id]);
        $messages = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $data = [
            'prenom' => $_SESSION['prenom'] ?? null,
            'success_message' => $_SESSION['success_message'] ?? null,
            'demandesRecues' => $demandesRecues,
            'messages' => $messages,
            'unreadCount' => !empty($messages) ? ($messages[0]['unread_count'] ?? 0) : 0
        ];
        
        // Nettoyer le message de succès après affichage
        if (isset($_SESSION['success_message'])) {
            unset($_SESSION['success_message']);
        }
        
        $this->view('home/principal', $data);
    }
    
    private function handleReservationAction($db, $reservation_id, $action) {
        if (!in_array($action, ['confirmee', 'annulee'])) {
            echo json_encode(['success' => false, 'message' => 'Action invalide']);
            return;
        }
        
        $conducteur_id = $_SESSION['user_id'];
        
        // Vérifier que l'utilisateur est bien le conducteur du trajet
        $sql_verify = "
            SELECT r.trajet_id, r.places_reservees
            FROM reservations r
            JOIN trajets t ON r.trajet_id = t.trajet_id
            WHERE r.reservation_id = ? AND t.conducteur_id = ?
        ";
        $stmt_verify = $db->prepare($sql_verify);
        $stmt_verify->execute([$reservation_id, $conducteur_id]);
        $row = $stmt_verify->fetch(PDO::FETCH_ASSOC);
        
        if (!$row) {
            echo json_encode(['success' => false, 'message' => 'Non autorisé']);
            return;
        }
        
        if ($action === 'annulee') {
            // Remettre les places disponibles
            $update_sql = "UPDATE trajets SET places_disponibles = places_disponibles + ? WHERE trajet_id = ?";
            $update_stmt = $db->prepare($update_sql);
            $update_stmt->execute([$row['places_reservees'], $row['trajet_id']]);
        }
        
        // Mettre à jour le statut de la réservation
        $sql = "UPDATE reservations SET statut_reservation = ? WHERE reservation_id = ?";
        $stmt = $db->prepare($sql);
        $stmt->execute([$action, $reservation_id]);
        
        echo json_encode(['success' => true]);
    }
    
    public function about() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $this->view('home/about');
    }
}
