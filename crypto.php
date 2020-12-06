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
	
	public static function decrypt($msg, $secret) {
		if (!$msg) {
			return "";
		}

		// Separate payload from potential hmac
		$separated = explode(":", trim($msg));

		// Extract HMAC if signed
		$hmac = (isset($separated[1])) ? $separated[1] : null;

		// Convert data-string to array
		$data = self::base64_url_decode($separated[0]);

		// Extract IV
		$iv = substr($data, 0, self::$block_size);

		// Extract Salt
		$salt = substr($data, self::$block_size, self::$block_size);

		// Extract ciphertext
		$ciphertext = substr($data, self::$block_size * 2);

		// Generate Key
		$key = hash_pbkdf2('sha1', $secret, $salt, self::$iterations, self::$key_size, true);

		// Ensure integrity if signed
		if ($hmac && !hash_equals(self::sign($separated[0], $key), $hmac)) {
			return "";
		}

		// Decrypt
		return openssl_decrypt($ciphertext, self::$encryption_method, $key, OPENSSL_RAW_DATA, $iv);
	}
	
	private static function base64_url_encode($str) {
		return strtr(base64_encode($str), '+/', '-_');
	}

	public static function sign($data, $key) {
		return hash_hmac('sha256', $data, $key);
	}
	
	private static function base64_url_decode($str) {
		return base64_decode(strtr($str, '-_', '+/'));
	}
}
?>
