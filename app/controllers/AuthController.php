<?php

namespace App\Controllers;

use App\Models\User;
use App\Services\JWTHelper;
use App\Services\ResponseService;
require_once __DIR__ . '/../services/JWTHelper.php';

class AuthController extends Controller
{
    private User $userModel;

    public function __construct()
    {
        $this->userModel = new User();
    }

    public function register()
    {
        $data = $this->decodePostData();

        if (empty($data['name']) || empty($data['email']) || empty($data['password'])) {
            return ResponseService::Error("Missing fields", 400);
        }

        $existingUser = $this->userModel->findByEmail($data['email']);
        if ($existingUser) {
            return ResponseService::Error("Email already registered", 409);
        }

        $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        $this->userModel->create($data);

        return ResponseService::Send(["message" => "User registered successfully"]);
    }

    public function login()
    {
        $data = $this->decodePostData();

        if (empty($data['email']) || empty($data['password'])) {
            return ResponseService::Error("Missing credentials", 400);
        }

        $user = $this->userModel->findByEmail($data['email']);
        if (!$user || !password_verify($data['password'], $user['password'])) {
            return ResponseService::Error("Invalid credentials", 401);
        }

        $token = JWTHelper::generateToken([
            "id" => $user['id'],
            "email" => $user['email'],
            "role" => $user['role']
        ]);

        return ResponseService::Send(["token" => $token]);
    }

    public function me()
    {
        $headers = getallheaders();
        if (!isset($headers['Authorization'])) {
            return ResponseService::Error("Unauthorized", 401);
        }

        $token = str_replace("Bearer ", "", $headers['Authorization']);
        $payload = \App\Services\JWTHelper::verifyToken($token);

        if (!$payload || !isset($payload['id'])) {
            return ResponseService::Error("Invalid token", 401);
        }

        $user = $this->userModel->findById($payload['id']);
        if (!$user) {
            return ResponseService::Error("User not found", 404);
        }

        return ResponseService::Send($user);
    }
}
