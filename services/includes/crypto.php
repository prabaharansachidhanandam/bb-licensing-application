<?php
class Encryption{

    /**
    * 
    * Retourne la chaîne de caracère encodéé en MIME base64
    * ----------------------------------------------------
    * @param string
    * @return string
    *
    **/
    public static function safe_b64encode($string='') {
        $data = base64_encode($string);
        $data = str_replace(['+','/','='],['-','_',''],$data);
        return $data;
    }

    /**
    * 
    * Retourne la chaîne de caracère MIME base64 décodée
    * -------------------------------------------------
    * @param string
    * @return string
    *
    **/
    public static function safe_b64decode($string='') {
        $data = str_replace(['-','_'],['+','/'],$string);
        $mod4 = strlen($data) % 4;
        if ($mod4) {
            $data .= substr('====', $mod4);
        }
        return base64_decode($data);
    }

    /**
    *
    * Crypte une chaîne de caractères avec un algorithme de cryptage aes-256 mode cbc
    * Le crypatage s'effectue avec une clé définie dans le fichier core.php
    * ------------------------------------------------------------------------------------------
    * @param string
    * @return string
    *
    **/
    public static function encode($value=false){ 
        if(!$value) return false;
        $iv_size = openssl_cipher_iv_length('aes-256-cbc');
        $iv = openssl_random_pseudo_bytes($iv_size);
        $crypttext = openssl_encrypt($value, 'aes-256-cbc', 'your security cipherSeed', OPENSSL_RAW_DATA, $iv);
        return self::safe_b64encode($iv.$crypttext); 
    }

    /**
    *
    * Decrypte une chaîne de caractères
    * ---------------------------------
    * @param string
    * @return string
    *
    **/
    public static function decode($value=false){
        if(!$value) return false;
        $crypttext = self::safe_b64decode($value);
        $iv_size = openssl_cipher_iv_length('aes-256-cbc');
        $iv = substr($crypttext, 0, $iv_size);
        $crypttext = substr($crypttext, $iv_size);
        if(!$crypttext) return false;
        $decrypttext = openssl_decrypt($crypttext, 'aes-256-cbc', 'your security cipherSeed', OPENSSL_RAW_DATA, $iv);
        return rtrim($decrypttext);
    }
}


$pass_get = 'prabha@bb.com';
$base64_crypt = Encryption::encode($pass_get); // get base64 of crypt data

echo $base64_crypt;
//echo Encryption::decode($base64_crypt);
/**/
?>