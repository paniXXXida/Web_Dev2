<?php

require_once __DIR__ . '/../vendor/autoload.php';

use App\Controllers\BookController;
use App\Controllers\BookRequestController;
use App\Controllers\BookCommentController;
use App\Controllers\AuthController;
use App\Controllers\AdminController;
use App\Services\EnvService;
use App\Services\ErrorReportingService;
use App\Services\ResponseService;
use Steampixel\Route;

EnvService::Init();
ErrorReportingService::Init();
ResponseService::SetCorsHeaders();

try {
    // Auth
    Route::add('/auth/register', fn() => (new AuthController())->register(), ['post']);
    Route::add('/auth/login', fn() => (new AuthController())->login(), ['post']);
    Route::add('/auth/me', fn() => (new AuthController())->me(), ['get']);


    // Books
    Route::add('/books', fn() => (new BookController())->getAll());
    Route::add('/books/([0-9]*)', fn($id) => (new BookController())->get($id));

    // Book Requests
    Route::add('/book-requests', fn() => (new BookRequestController())->createRequest(), ['post']);
    Route::add('/book-requests/user', fn() => (new BookRequestController())->getUserRequests(), ['get']);
    Route::add('/book-requests/([0-9]*)/cancel', fn($id) => (new BookRequestController())->cancelRequest($id), ['put']);

    // Admin
    Route::add('/admin/users', fn() => (new AdminController())->getAllUsers(), ['get']);
    Route::add('/admin/book-requests', fn() => (new AdminController())->getAllBookRequests(), ['get']);
    Route::add('/admin/book-requests/([0-9]*)', fn($id) => (new AdminController())->updateBookRequestStatus($id), ['put']);
    Route::add('/admin/books', fn() => (new AdminController())->createBook(), ['post']);


    // Comments
    Route::add('/comments/book/([0-9]*)', fn($bookId) => (new BookCommentController())->getByBookId($bookId), ['get']);
    Route::add('/comments', fn() => (new BookCommentController())->create(), ['post']); // ✅ Этот маршрут отсутствовал
    Route::add('/comments/([0-9]*)', fn($id) => (new BookCommentController())->delete($id), ['delete']);

    // 404 fallback
    Route::pathNotFound(fn() => ResponseService::Error("route is not defined", 404));

} catch (\Throwable $error) {
    if ($_ENV["environment"] === "LOCAL") {
        var_dump($error);
    } else {
        error_log($error);
    }
    ResponseService::Error("A server error occurred");
}

Route::run();
