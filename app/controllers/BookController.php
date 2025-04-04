<?php

namespace App\Controllers;

use App\Services\ResponseService;
use App\Models\Book;

class BookController extends Controller
{
    private $bookModel;

    function __construct()
    {
        $this->bookModel = new Book();
    }

    function getAll()
    {
        $page = (int)($_GET["page"] ?? 1);
        ResponseService::Send($this->bookModel->getAll($page));
    }

    function get($id)
    {
        ResponseService::Send($this->bookModel->get($id));
    }

    public function create()
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

        $success = $this->bookModel->create($data);

        return $success
            ? ResponseService::Send(["message" => "Book added"])
            : ResponseService::Error("Failed to add book", 500);
    }


    function update($id)
    {
        $data = $this->decodePostData();
        $this->validateInput(["title", "author"], $data);

        $updatedBook = $this->bookModel->update($id, $data);
        ResponseService::Send($updatedBook);
    }

    function delete($id)
    {
        $this->bookModel->delete($id);
        ResponseService::Send([], 204);
    }
}
