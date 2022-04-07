<?php

class AES
{

    public static string $cipher = "aes-256-cbc";

    public static function generateKey() : string {
        return openssl_random_pseudo_bytes(32);
    }

    public static function encrypt(string $key, string $textToEncrypt) : string {
        $ivSize = openssl_cipher_iv_length(self::$cipher);
        $iv = openssl_random_pseudo_bytes($ivSize);

        $encryptedText = openssl_encrypt($textToEncrypt, self::$cipher, $key, 0, $iv);

        return "$iv\n$encryptedText";
    }

    public static function decrypt(string $key, string $encryptedText) : string {
        $ivAndEncryptedText = explode("\n", $encryptedText);

        $iv = $ivAndEncryptedText[0];
        $encryptedText = $ivAndEncryptedText[1];

        return openssl_decrypt($encryptedText, self::$cipher, $key, 0, $iv);
    }
}