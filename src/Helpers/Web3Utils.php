<?php
/**
 * Created by PhpStorm.
 * User: martijndebruijn
 * Date: 7/26/21
 * Time: 10:48 AM
 */

namespace  Appbakkers\Ethereum\Helpers;

class Web3Utils {

    /**
     * Pad a address hex string to a certain length
     * @param String $hexString
     * @param int $length
     * @return String
     */
    public static function padBytes(String $hexString, int $length) : String {

         //Remove 0x if already included
        if(substr($hexString,0,2) == "0x")
            $hexString = substr($hexString,2);

        $byteArray = self::hex2ByteArray($hexString);


        while (count($byteArray) < $length)
            array_unshift($byteArray, 0);

        $paddedString =  self::byteArray2Hex($byteArray);

        return '0x'.$paddedString;
    }

    /**
     * Convert bytes array to Hex String
     * @param $byteArray
     * @return string
     */
    private static function byteArray2Hex($byteArray) {
        $chars = array_map("chr", $byteArray);
        $bin = join($chars);
        return bin2hex($bin);
    }

    /**
     * Convert Hex String to bytes array
     * @param $hexString
     * @return array
     */
    private static function hex2ByteArray($hexString) {
        $string = hex2bin($hexString);
        return unpack('C*', $string);
    }

    /**
     * @param $string
     * @return string
     */
    public static function string2Hex($string) {
        return bin2hex($string);
    }

    /**
     * @param $hexString
     * @return bool|string
     */
    public static function hex2String($hexString) {
        return hex2bin($hexString);
    }
}