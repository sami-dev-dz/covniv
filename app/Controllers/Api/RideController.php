<?php
namespace App\Controllers\Api;

use App\Controllers\ApiController;

class RideController extends ApiController {

    public function search() {
        $this->safeRun(function () {
            // Input retrieval
            $departure = filter_input(INPUT_GET, 'departure', FILTER_SANITIZE_SPECIAL_CHARS);
            $arrival   = filter_input(INPUT_GET, 'arrival',   FILTER_SANITIZE_SPECIAL_CHARS);

            // Required field validation
            if (empty($departure) || empty($arrival)) {
                return $this->errorResponse("Les paramètres 'departure' et 'arrival' sont requis.", 400);
            }

            // Length validation (prevent abuse)
            if (strlen($departure) > 100 || strlen($arrival) > 100) {
                return $this->errorResponse("Les paramètres de recherche sont trop longs.", 400);
            }

            $conn = \Database::getConnection();
            $stmt = $conn->prepare("
                SELECT r.ride_id, r.departure_city, r.arrival_city,
                       r.departure_time, r.available_seats, r.price
                FROM rides r
                WHERE r.departure_city LIKE :dep
                  AND r.arrival_city   LIKE :arr
                  AND r.departure_time  > NOW()
                  AND r.available_seats > 0
                ORDER BY r.departure_time ASC
                LIMIT 50
            ");
            $stmt->execute([
                'dep' => '%' . $departure . '%',
                'arr' => '%' . $arrival   . '%',
            ]);
            $rides = $stmt->fetchAll();

            // Proper 200 with envelope
            $this->jsonResponse([
                'status'  => 'success',
                'count'   => count($rides),
                'results' => $rides,
            ], 200);
        });
    }
}
