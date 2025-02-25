<?php

namespace App\TollBox;

class Helper
{
    public static function dda(mixed $data)
    {
        http_response_code(200);
        header('Content-Type: application/json');
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Headers: Content-Type, Authorization');
        echo json_encode($data);
        die();
    }
}
