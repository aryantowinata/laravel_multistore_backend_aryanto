<?php

namespace App\Services\Aes128;

class AesCrypt
{
    private static $cipher = 'aes-128-cbc';

    public static function encrypt($data)
    {
        $key = env('ENCRYPTION_KEY');
        $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length(self::$cipher));
        $encrypted = openssl_encrypt($data, self::$cipher, $key, 0, $iv);
        return base64_encode($encrypted . '::' . $iv);
    }

    public static function decrypt($data)
    {
        $key = env('ENCRYPTION_KEY');
        list($encrypted_data, $iv) = explode('::', base64_decode($data), 2);
        return openssl_decrypt($encrypted_data, self::$cipher, $key, 0, $iv);
    }
}
