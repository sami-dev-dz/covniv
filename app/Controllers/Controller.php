<?php
namespace App\Controllers;

class Controller {
    protected function view($viewPath, $data = []) {
        // Extract data to variables
        extract($data);
        
        // Include the view file
        $file = BASE_PATH . 'app/Views/' . $viewPath . '.php';
        if (file_exists($file)) {
            require_once $file;
        } else {
            die("View not found: " . $viewPath);
        }
    }
}
