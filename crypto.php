<?php
class Crypto {
	static $encryption_method = 'aes-256-cbc';
	static $block_size        = 16;
	static $key_size          = 32; // in bytes - so 256 bit for aes-256
	static $iterations        = 2048;


	public static function encrypt($plaintext, $secret, $sign = false) {
		// Generate Intialization vector
		$iv = openssl_random_pseudo_bytes(self::$block_size);
	
		// Generate Salt
		$salt = openssl_random_pseudo_bytes(self::$block_size);
	
		// Generate salted Key
		$key = hash_pbkdf2('sha1', $secret, $salt, self::$iterations, self::$key_size, true);
	
		// Encrypt
		$ciphertext = openssl_encrypt($plaintext, self::$encryption_method, $key, OPENSSL_RAW_DATA, $iv);
	
		// Encode
		$ciphertext64 = self::base64_url_encode($iv . $salt . $ciphertext);
	
		// Sign
		if ($sign) {
			$ciphertext64 = $ciphertext64 . ":" . self::sign($ciphertext64, $key);
		}
	
		return $ciphertext64;
	}
}
?>