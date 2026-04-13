<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!function_exists('debug')) {
    function debug($data)
    {
        echo '<pre>';
        print_r($data);
        die;
    }
}

if (!function_exists('upload_file')) {
    function upload_file($folder, $file)
    {
        // Loại bỏ dấu / ở đầu nếu có
        $fileName = time() . '-' . $file["name"];
        $targetFile = ($folder ? rtrim($folder, '/'). '/' : '') . $fileName;

        if (move_uploaded_file($file["tmp_name"], PATH_ASSETS_UPLOADS . $targetFile)) {
          
            return ltrim($targetFile, '/');
        }

        throw new Exception('Upload file không thành công!');
    }
}