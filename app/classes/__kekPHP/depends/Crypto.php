<?php

/**
 * @Class Crypto
 *
 * @Description Cryptographic Utility Wrapper
 *
 * @Author: Robert Mariano Schwindaman (Git: schwindy | get.schwindy@gmail.com)
 * @Version: 0.1
 * @Note: This class is subject to change.
 */
class Crypto
{
    public static $version = '1';

    public static function decrypt($data, $key, $nonce)
    {
        try {
            if (empty($key)) {
                return new Response(0, "Invalid key (empty)!", false);
            }

            if (empty($nonce)) {
                return new Response(0, "Invalid nonce (empty)!", false);
            }

            $data = sodium_crypto_secretbox_open($data, $nonce, $key);

            if ($data === false) {
                return new Response(0, "Invalid decrypted data (=== false)!", false);
            }

            return new Response(1, "Successfully decrypted your data!", $data);
        } catch (Exception $e) {
            return new Response(-1, "Fatal: Unable to decrypt", $e);
        }
    }

    public static function encrypt($data, $key, $nonce)
    {
        try {
            if (empty($key)) {
                return new Response(0, "Invalid key (empty)!", false);
            }

            if (empty($nonce)) {
                return new Response(0, "Invalid nonce (empty)!", false);
            }

            $data = sodium_crypto_secretbox($data, $nonce, $key);

            if ($data === false) {
                return new Response(0, "Invalid encrypted data (=== false)!", false);
            }

            return new Response(1, "Successfully encrypted your data!", $data);
        } catch (Exception $e) {
            return new Response(-1, "Fatal: Unable to encrypt", $e);
        }
    }

    public static function getKey()
    {
        return random_bytes(SODIUM_CRYPTO_SECRETBOX_KEYBYTES);
    }

    public static function getKeyPair()
    {
        return sodium_crypto_box_keypair();
    }

    public static function getKeyPairFromSeed($seed)
    {
        return sodium_crypto_secretbox_seed_keypair($seed);
    }

    public static function getNonce()
    {
        return random_bytes(SODIUM_CRYPTO_SECRETBOX_NONCEBYTES);
    }

    public static function getPublicKey($kp)
    {
        return sodium_crypto_box_publickey($kp);
    }

    public static function getSecret()
    {
        return constant("KEK_PEPPER_" . Crypto::$version);
    }

    public static function getSecretKey($kp)
    {
        return sodium_crypto_box_secretkey($kp);
    }

    public static function getSeed()
    {
        return random_bytes(SODIUM_CRYPTO_SECRETBOX_SEEDBYTES);
    }

    public static function hash($data = '', $oregano = '', $salt = '')
    {
        return sha1(empty($salt) ? PJSalt : $salt . $data . $oregano);
    }

    public static function signKeyPair()
    {
        return sodium_crypto_box_keypair();
    }
}