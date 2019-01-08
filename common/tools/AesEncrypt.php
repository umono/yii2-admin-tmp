<?php

namespace common\tools;
/**
 * Created by PhpStorm.
 * User: moment
 * Date: 2018/6/14
 * Time: 上午12:11
 */
class AesEncrypt
{
    const AES_KEY = "qq3217834abcdefg"; //16位
    const AES_IV  = "1234567890123456"; //16位

    /**
     * 加密
     * @param $str
     * @return string
     */
    public static function aes_decrypt($str)
    {
        $decrypted = openssl_decrypt(base64_decode($str), 'aes-128-cbc', self::AES_KEY, OPENSSL_RAW_DATA, self::AES_IV);

        return $decrypted;
    }

    /**
     * 解密
     * @param $plain_text
     * @return string
     */
    public static function aes_encrypt($plain_text)
    {
        $encrypted_data = openssl_encrypt($plain_text, 'aes-128-cbc', self::AES_KEY, OPENSSL_RAW_DATA, self::AES_IV);

        return base64_encode($encrypted_data);
    }
}