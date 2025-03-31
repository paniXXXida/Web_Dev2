<?php

/**
 * Setup
 */

// require autoload file to autoload vendor libraries
require_once __DIR__ . '/../vendor/autoload.php';

// require local classes
use App\Controllers\BookController;
use App\Controllers\BookRequestController;
use App\Controllers\AuthController;
use App\Services\EnvService;
use App\Services\ErrorReportingService;
use App\Services\ResponseService;

// require vendor libraries
use Steampixel\Route;

// initialize global environment variables
EnvService::Init();

// initialize error reporting (on in local env)
ErrorReportingService::Init();

// set CORS headers
ResponseService::SetCorsHeaders();

/**
 * Main application routes
 */
try {
    /**
     * Auth routes
     */
    Route::add('/auth/register', function () {
        $authController = new AuthController();
        $authController->register();
    }, ["post"]);

    Route::add('/auth/login', function () {
        $authController = new AuthController();
        $authController->login();
    }, ["post"]);

    Route::add('/auth/me', function () {
        $authController = new AuthController();
        $authController->me();
    }, ["get"]);

    Route::add('/auth/is-me/([0-9]*)', function ($id) {
        $authController = new AuthController();
        $authController->isMe($id);
    }, 'get');

    /**
     * Book routes
     */
    Route::add('/books', function () {
        $controller = new BookController();
        $controller->getAll();
    });

    Route::add('/books/([0-9]*)', function ($id) {
        $controller = new BookController();
        $controller->get($id);
    });

    /**
     * Book request route
     */
    Route::add('/book-requests', function () {
        $controller = new BookRequestController();
        $controller->createRequest();
    }, ['post']);

    /**
     * 404 handler
     */
    Route::pathNotFound(function () {
        ResponseService::Error("route is not defined", 404);
    });

} catch (\Throwable $error) {
    if ($_ENV["environment"] === "LOCAL") {
        var_dump($error);
    } else {
        error_log($error);
    }
    ResponseService::Error("A server error occurred");
}

Route::run();
