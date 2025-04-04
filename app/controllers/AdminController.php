<?php

namespace App\Controllers;

use App\Models\User;
use App\Models\BookRequest;
use App\Services\ResponseService;
use App\Services\JWTHelper;
use App\Models\Book;

class AdminController extends Controller
{
    private User $userModel;
    private BookRequest $requestModel;
    private Book $bookModel;

    public function __construct()
    {
        $this->userModel = new User();
        $this->requestModel = new BookRequest();
        $this->bookModel = new Book();
    }

    private function authorizeAdmin()
    {
        $headers = getallheaders();
        $token = str_replace('Bearer ', '', $headers['Authorization'] ?? '');
        $payload = JWTHelper::verifyToken($token);

        if (!$payload || ($payload['role'] ?? '') !== 'admin') {
            ResponseService::Error("Unauthorized", 401);
        }
    }

    public function getAllUsers()
    {
        $this->authorizeAdmin();
        $users = $this->userModel->getAll();
        ResponseService::Send($users);
    }

    public function getAllBookRequests()
    {
        $this->authorizeAdmin();
        $requests = $this->requestModel->getAll();
        ResponseService::Send($requests);
    }

    public function updateBookRequestStatus($id)
    {
        $authHeader = $_SERVER['HTTP_AUTHORIZATION'] ?? '';
        $token = str_replace('Bearer ', '', $authHeader);
        $payload = JWTHelper::verifyToken($token);

        if (!$payload || $payload['role'] !== 'admin') {
            return ResponseService::Error("Unauthorized", 403);
        }

        $data = json_decode(file_get_contents('php://input'), true);
        if (!isset($data['status'])) {
            return ResponseService::Error("Missing status", 400);
        }

        $model = new BookRequest();
        $success = $model->updateStatus($id, $data['status']);

        return $success
            ? ResponseService::Send(["message" => "Updated"])
            : ResponseService::Error("Update failed", 500);
    }

    public function createBook()
    {
        $authHeader = $_SERVER['HTTP_AUTHORIZATION'] ?? '';
        $token = str_replace('Bearer ', '', $authHeader);
        $payload = JWTHelper::verifyToken($token);

        if (!$payload || ($payload['role'] ?? null) !== 'admin') {
            return ResponseService::Error("Unauthorized", 401);
        }

        $data = json_decode(file_get_contents('php://input'), true);

        if (!isset($data['title'], $data['author'])) {
            return ResponseService::Error("Missing required fields", 400);
        }

        $stmt = $this->requestModel::$pdo->prepare(
            "INSERT INTO books (title, author, description) VALUES (:title, :author, :description)"
        );

        $success = $stmt->execute([
            'title' => $data['title'],
            'author' => $data['author'],
            'description' => $data['description'] ?? ''
        ]);

        return $success
            ? ResponseService::Send(["message" => "Book added"])
            : ResponseService::Error("Failed to add book", 500);
    }

}
