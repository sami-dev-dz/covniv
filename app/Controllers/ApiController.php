<?php
namespace App\Controllers;

class ApiController extends Controller {

    /**
     * Send a JSON response and exit.
     */
    protected function jsonResponse($data, $status = 200) {
        if (ob_get_length()) ob_clean();
        http_response_code($status);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data);
        exit();
    }

    /**
     * Send a structured error response.
     */
    protected function errorResponse($message, $status = 400) {
        $this->jsonResponse(['error' => $message], $status);
    }

    /**
     * Wrap a callable in a try-catch to prevent raw PHP errors
     * from leaking to the API consumer.
     */
    protected function safeRun(callable $fn) {
        try {
            $fn();
        } catch (\Throwable $e) {
            error_log("API Error in " . get_class($this) . ": " . $e->getMessage(), 3, BASE_PATH . 'logs/error.log');
            $this->errorResponse("Une erreur interne s'est produite. Veuillez réessayer.", 500);
        }
    }
}
