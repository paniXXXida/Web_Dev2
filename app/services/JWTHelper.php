<?php

namespace App\Services;
class JWTHelper
{
    private static string $secret = "mybooks_super_secret_key";
    private static string $algo = "HS256";

    public static function generateToken(array $payload, int $expireMinutes = 60): string
    {
        $header = json_encode(['typ' => 'JWT', 'alg' => self::$algo]);

        $payload['exp'] = time() + ($expireMinutes * 60);
        $payloadEncoded = json_encode($payload);

        $base64UrlHeader = rtrim(strtr(base64_encode($header), '+/', '-_'), '=');
        $base64UrlPayload = rtrim(strtr(base64_encode($payloadEncoded), '+/', '-_'), '=');

        $signature = hash_hmac('sha256', "$base64UrlHeader.$base64UrlPayload", self::$secret, true);
        $base64UrlSignature = rtrim(strtr(base64_encode($signature), '+/', '-_'), '=');

        return "$base64UrlHeader.$base64UrlPayload.$base64UrlSignature";
    }

    public static function verifyToken(string $token): ?array
    {
        $parts = explode('.', $token);
        if (count($parts) !== 3) return null;

        [$header, $payload, $signature] = $parts;

        $rawSignature = hash_hmac('sha256', "$header.$payload", self::$secret, true);
        $base64UrlRawSignature = rtrim(strtr(base64_encode($rawSignature), '+/', '-_'), '=');

        if (!hash_equals($signature, $base64UrlRawSignature)) return null;

        $decodedPayload = json_decode(base64_decode($payload), true);
        if ($decodedPayload['exp'] < time()) return null;

        return $decodedPayload;
    }
}
