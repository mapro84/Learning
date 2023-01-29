<?php
namespace src\Core\Auth;

use src\Core\DB\DB;
use src\Core\Utils\Debug;
use src\Core\Config\Config;

class DBAuth {

	private static $env;
	
	public function __construct(){
		self::$env = Config::getGenConfKey('ENV');
	}
	
	public static function login(String $username, String $password){
		$request = "SELECT * FROM appuser WHERE username= ? AND password= ?";
		$user = DB::prepare($request, [$username,sha1($password)]);
		return empty($user) ? false : $user;
	}
	
}

