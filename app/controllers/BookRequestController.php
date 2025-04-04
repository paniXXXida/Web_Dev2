<?php

namespace App\Controllers;

use App\Models\BookRequest;
use App\Services\JWTHelper;
use App\Services\ResponseService;

class BookRequestController
{
    private BookRequest $requestModel;

    public function __construct()
    {
        $this->requestModel = new BookRequest();
    }

    public function getUserRequests()
    {
        $authHeader = $_SERVER['HTTP_AUTHORIZATION'] ?? '';
        $token = str_replace('Bearer ', '', $authHeader);
        $payload = JWTHelper::verifyToken($token);

        if (!$payload || !isset($payload['id'])) {
            return ResponseService::Error("Unauthorized", 401);
        }

        $userId = $payload['id'];
        $requests = $this->requestModel->findByUserIdWithBookTitles($userId);

        return ResponseService::Send($requests);
    }

    public function createRequest()
    {
        $authHeader = $_SERVER['HTTP_AUTHORIZATION'] ?? '';
        $token = str_replace('Bearer ', '', $authHeader);
        $payload = JWTHelper::verifyToken($token);

        if (!$payload || !isset($payload['id'])) {
            return ResponseService::Error("Unauthorized", 401);
        }

        $data = json_decode(file_get_contents('php://input'), true);

        if (!isset($data['book_id'])) {
            return ResponseService::Error("Missing book_id", 400);
        }

        $result = $this->requestModel->create([
            'user_id' => $payload['id'],
            'book_id' => $data['book_id'],
            'status'  => 'pending'
        ]);

        if ($result) {
            return ResponseService::Send(["message" => "Request submitted"]);
        } else {
            return ResponseService::Error("Failed to submit request", 500);
        }
    }

    public function cancelRequest($id)
    {
        $authHeader = $_SERVER['HTTP_AUTHORIZATION'] ?? '';
        $token = str_replace('Bearer ', '', $authHeader);
        $payload = JWTHelper::verifyToken($token);

        if (!$payload || !isset($payload['id'])) {
            return ResponseService::Error("Unauthorized", 401);
        }

        $userId = $payload['id'];
        $success = $this->requestModel->cancelRequest($id, $userId);

        if ($success) {
            return ResponseService::Send(["message" => "Request cancelled"]);
        } else {
            return ResponseService::Error("Failed to cancel request", 500);
        }
    }
}
