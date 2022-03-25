<?php
namespace Src\System;
use \Firebase\JWT\JWT;
use Firebase\JWT\Key;

class AuthValidation {

    public static function isValidJwt($data){
        try {
              $secret_key = "owt125";
              JWT::decode($data->jwt, new Key($secret_key,'HS512'));
              return true;   
        } catch (\Throwable $th) {
        return false;
        }

    }
    public static function decodedData($data){
        $secret_key = "owt125";
        $decoded_data = JWT::decode($data->jwt, new Key($secret_key,'HS512'));
        return $decoded_data;   
    }
}