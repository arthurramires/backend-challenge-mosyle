<?php
    namespace App\Utils;

    class AuthToken{
        public static function generateToken($userId){
            $header = json_encode(['typ' => 'JWT', 'alg' => 'HS256']);

            $payload = json_encode(['user_id' => $userId]);

            $base64UrlHeader = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($header));

            $base64UrlPayload = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($payload));

            $signature = hash_hmac('sha256', $base64UrlHeader . "." . $base64UrlPayload, 'mosyle123!', true);

            $base64UrlSignature = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($signature));

            $jwt = $base64UrlHeader . "." . $base64UrlPayload . "." . $base64UrlSignature;

            return $jwt;
        }
    }
