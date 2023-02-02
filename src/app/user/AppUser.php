<?php
namespace src\app\user;

class AppUser {
	
	public $id;
	public $username;
	public $password;
	public $privilege_id;

	public function __get($property){
		return 'get' . ucFirst($property);
	}
	
	public function getId(){
		return $this->id;
	}
	
	public function getPrivilege_id(){
		return $this->privilege_id;
	}
	
	public function getUsername(){
		return self::$username;
	}
	
	public function getPassword(){
		return $this->password;
	}
}

