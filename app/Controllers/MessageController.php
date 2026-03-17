<?php
namespace App\Controllers;
use PDO;

class MessageController extends Controller {
    
    public function index() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        if (!isset($_SESSION['user_id'])) {
            header("Location: /login");
            exit();
        }
        
        $db = \Database::getConnection();
        $mon_id = $_SESSION['user_id'];
        
        // Fetch contacts
        $utilisateurs_sql = "
            SELECT DISTINCT u.user_id, i.nom, i.prenom, i.photo_profil
            FROM users u
            JOIN infos i ON u.num_carte = i.num_carte
            WHERE u.user_id IN (
                SELECT t.conducteur_id
                FROM reservations r
                JOIN trajets t ON r.trajet_id = t.trajet_id
                WHERE r.passager_id = ?
                AND t.conducteur_id != ?
                AND r.statut_reservation IN ('confirmee', 'en_attente')

                UNION

                SELECT r.passager_id
                FROM reservations r
                JOIN trajets t ON r.trajet_id = t.trajet_id
                WHERE t.conducteur_id = ?
                AND r.passager_id != ?
                AND r.statut_reservation IN ('confirmee', 'en_attente')
            )
            ORDER BY i.nom, i.prenom
        ";
        $stmt_u = $db->prepare($utilisateurs_sql);
        $stmt_u->execute([$mon_id, $mon_id, $mon_id, $mon_id]);
        $utilisateurs = $stmt_u->fetchAll(PDO::FETCH_ASSOC);
        
        $dest_id = isset($_GET['destinataire_id']) ? (int)$_GET['destinataire_id'] : null;
        $trajet_id = isset($_GET['trajet_id']) ? (int)$_GET['trajet_id'] : null;
        
        $messages = [];
        $current_conversation_name = "";
        $current_avatar = "";
        
        if ($dest_id) {
            $user_info_sql = "
                SELECT i.nom, i.prenom, i.photo_profil 
                FROM users u
                JOIN infos i ON u.num_carte = i.num_carte
                WHERE u.user_id = ?
            ";
            $stmt_info = $db->prepare($user_info_sql);
            $stmt_info->execute([$dest_id]);
            $user_info = $stmt_info->fetch(PDO::FETCH_ASSOC);
            
            if ($user_info) {
                $current_conversation_name = $user_info['prenom'] . ' ' . $user_info['nom'];
                $current_avatar = $user_info['photo_profil'];
            }
            
            // Mark as read
            $sql_update = "UPDATE messages SET est_lu = 1 WHERE destinataire_id = ? AND expediteur_id = ?";
            $update_params = [$mon_id, $dest_id];
            if ($trajet_id) {
                $sql_update .= " AND trajet_id = ?";
                $update_params[] = $trajet_id;
            }
            $db->prepare($sql_update)->execute($update_params);

            // Fetch messages
            $sql_msg = "
                SELECT * FROM messages
                WHERE (
                    (expediteur_id = ? AND destinataire_id = ?)
                    OR (expediteur_id = ? AND destinataire_id = ?)
                )
            ";
            $msg_params = [$mon_id, $dest_id, $dest_id, $mon_id];
            if ($trajet_id) {
                $sql_msg .= " AND trajet_id = ?";
                $msg_params[] = $trajet_id;
            }
            $sql_msg .= " ORDER BY date_envoi ASC";
            $stmt_msg = $db->prepare($sql_msg);
            $stmt_msg->execute($msg_params);
            $messages = $stmt_msg->fetchAll(PDO::FETCH_ASSOC);
        }
        
        // Fetch inbox (last messages from each conversation)
        $inbox_sql = "
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
        $stmt_inbox = $db->prepare($inbox_sql);
        $stmt_inbox->execute([$mon_id, $mon_id]);
        $boite_reception = $stmt_inbox->fetchAll(PDO::FETCH_ASSOC);
        
        $data = [
            'utilisateurs' => $utilisateurs,
            'dest_id' => $dest_id,
            'trajet_id' => $trajet_id,
            'messages' => $messages,
            'current_conversation_name' => $current_conversation_name,
            'current_avatar' => $current_avatar,
            'boite_reception' => $boite_reception,
            'mon_id' => $mon_id
        ];
        
        $this->view('messaging/index', $data);
    }
    
    public function send() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        if (!isset($_SESSION['user_id'])) {
            header("Location: /login");
            exit();
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['contenu_message']) && !empty($_POST['destinataire_id'])) {
            $csrfToken = filter_input(INPUT_POST, 'csrf_token', FILTER_SANITIZE_SPECIAL_CHARS);
            if (!\App\Services\AuthSecurity::validateCsrfToken($csrfToken)) {
                // Return to messages if CSRF fails
                header("Location: /messagerie");
                exit();
            }

            $db = \Database::getConnection();
            $mon_id = $_SESSION['user_id'];
            $contenu = filter_input(INPUT_POST, 'contenu_message', FILTER_SANITIZE_SPECIAL_CHARS); // Add basic sanitization here too
            $dest_id = (int)$_POST['destinataire_id'];
            $trajet_id = !empty($_POST['trajet_id']) ? (int)$_POST['trajet_id'] : null;
            
            $stmt = $db->prepare("
                INSERT INTO messages (expediteur_id, destinataire_id, trajet_id, contenu_message)
                VALUES (?, ?, ?, ?)
            ");
            $stmt->execute([$mon_id, $dest_id, $trajet_id, $contenu]);
            
            header("Location: /messagerie?destinataire_id=$dest_id" . ($trajet_id ? "&trajet_id=$trajet_id" : ""));
            exit();
        }
        
        header("Location: /messagerie");
        exit();
    }
}
