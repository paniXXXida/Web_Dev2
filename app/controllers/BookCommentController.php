<?php

namespace App\Controllers;

use App\Models\BookReview;
use App\Services\JWTHelper;
use App\Services\ResponseService;

class BookCommentController
{
    private BookReview $reviewModel;

    public function __construct()
    {
        $this->reviewModel = new BookReview();
    }

    public function getByBookId($bookId)
    {
        $comments = $this->reviewModel->getByBookId($bookId);
        return ResponseService::Send($comments);
    }

    public function create()
    {
        $authHeader = $_SERVER['HTTP_AUTHORIZATION'] ?? '';
        $token = str_replace('Bearer ', '', $authHeader);
        $payload = JWTHelper::verifyToken($token);

        if (!$payload || !isset($payload['id'])) {
            return ResponseService::Error("Unauthorized", 401);
        }

        $data = json_decode(file_get_contents("php://input"), true);

        if (!isset($data['book_id'], $data['comment']) || trim($data['comment']) === "") {
            return ResponseService::Error("Missing or empty comment", 400);
        }

        $success = $this->reviewModel->create([
            'book_id' => $data['book_id'],
            'user_id' => $payload['id'],
            'comment' => htmlspecialchars($data['comment']),
            'rating' => $data['rating'] ?? null
        ]);

        return $success
            ? ResponseService::Send(["message" => "Comment added"], 201)
            : ResponseService::Error("Failed to save comment", 500);
    }

    public function delete($id)
    {
        $authHeader = $_SERVER['HTTP_AUTHORIZATION'] ?? '';
        $token = str_replace('Bearer ', '', $authHeader);
        $payload = JWTHelper::verifyToken($token);

        if (!$payload || !isset($payload['id'])) {
            return ResponseService::Error("Unauthorized", 401);
        }

        $this->reviewModel->delete($id);
        return ResponseService::Send(["message" => "Comment deleted"]);
    }
}
