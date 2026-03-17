<?php
// routes/web.php

class Route {
    private static $routes = [];
    private static $currentGroupMiddleware = [];

    public static function get($uri, $controllerMethod, $middleware = []) {
        self::addRoute('GET', $uri, $controllerMethod, $middleware);
    }

    public static function post($uri, $controllerMethod, $middleware = []) {
        self::addRoute('POST', $uri, $controllerMethod, $middleware);
    }

    private static function addRoute($method, $uri, $controllerMethod, $middleware) {
        $uri = trim($uri, '/');
        if ($uri === '') $uri = '/';
        
        $mergedMiddleware = array_merge(self::$currentGroupMiddleware, (array)$middleware);
        self::$routes[$method][$uri] = [
            'action' => $controllerMethod,
            'middleware' => $mergedMiddleware
        ];
    }

    public static function group($middleware, $callback) {
        $previousMiddleware = self::$currentGroupMiddleware;
        self::$currentGroupMiddleware = array_merge(self::$currentGroupMiddleware, (array)$middleware);
        $callback();
        self::$currentGroupMiddleware = $previousMiddleware;
    }

    public static function dispatch($uri) {
        $method = $_SERVER['REQUEST_METHOD'];
        $uri = trim($uri, '/');
        if ($uri === '') $uri = '/';

        if (isset(self::$routes[$method][$uri])) {
            $route = self::$routes[$method][$uri];
            $action = $route['action'];
            $middlewares = $route['middleware'];

            // Handle Middleware
            foreach ($middlewares as $middleware) {
                if (!self::runMiddleware($middleware)) {
                    return; // Middleware handled the response
                }
            }
            
            // Split Controller@method
            list($controller, $methodName) = explode('@', $action);

            // Support nested sub-namespaces like Api\RideController
            $controllerClass = "App\\Controllers\\" . str_replace('/', '\\', $controller);
            $controllerFile = BASE_PATH . "app/Controllers/" . str_replace('\\', '/', $controller) . ".php";
            
            if (file_exists($controllerFile)) {
                require_once $controllerFile;
                if (class_exists($controllerClass)) {
                    $instance = new $controllerClass();
                    if (method_exists($instance, $methodName)) {
                        $instance->$methodName();
                        return;
                    }
                }
            }
        }
        
        http_response_code(404);
        echo "404 Not Found";
    }

    private static function runMiddleware($middleware) {
        switch ($middleware) {
            case 'auth':
                if (session_status() === PHP_SESSION_NONE) session_start();
                if (!isset($_SESSION['user_id'])) {
                    header("Location: /login");
                    return false;
                }
                break;
            case 'api.auth':
                return \App\Services\AuthSecurity::handleApiAuth();
        }
        return true;
    }
}

// Define routes here mapping clean URLs to controllers
Route::get('/', 'HomeController@index');
Route::get('accueil', 'HomeController@index');
Route::get('login', 'AuthController@showLoginForm');
Route::post('login', 'AuthController@login');
Route::get('sign-up', 'AuthController@showSignUpForm');
Route::post('sign-up', 'AuthController@register');
Route::get('logout', 'AuthController@logout');

// Home/Principal/Dashboard
Route::get('principal', 'HomeController@principal');
Route::get('about', 'HomeController@about');

// Profile & Vehicles
Route::get('profil', 'ProfileController@index');
Route::post('profil', 'ProfileController@index');
Route::get('ajouter-vehicule', 'ProfileController@showAddVehicle');
Route::post('ajouter-vehicule', 'ProfileController@addVehicle');

// Rides
Route::get('recherche-trajet', 'RideController@search');
Route::get('trajets-disponibles', 'RideController@results');
Route::get('publier-trajet', 'RideController@showPublishForm');
Route::post('publier-trajet', 'RideController@publish');

// Bookings & History
Route::group(['auth'], function() {
    Route::get('reserver', 'BookingController@book');
    Route::get('historique', 'BookingController@history');
    Route::get('demandes-recues', 'BookingController@requests');
    Route::post('update-demande', 'BookingController@updateStatus');
    Route::post('annuler-reservation', 'BookingController@cancel');
    Route::post('terminer-reservation', 'BookingController@terminate');
});

// Messaging
Route::group(['auth'], function() {
    Route::get('messagerie', 'MessageController@index');
    Route::post('envoyer-message', 'MessageController@send');
});

// API Routes
Route::group(['api.auth'], function() {
    Route::get('api/trajets/recherche', 'Api\RideController@search');
});

// ... More routes will be dynamically added as we refactor
