<?php
namespace src\app\Controller;

use src\app\user\AppUser;
use src\Core\Utils\Check;
use src\Core\Auth\DBAuth;
use src\Core\Config\Config;
use src\Core\Utils\Debug;

class UserController extends AppController {
	
	private $user = false;
	private $id;
	private $username;
	private $privilege_id;

	public function login(){

        $privilege = 'null';
		if(isset($_POST['username']) && isset($_POST['password'])){
			$username = addslashes($_POST['username']);
			$password = addslashes($_POST['password']);
            $privilege = $this->providePrivilege($username,$password);
            Debug::dump($this->user);
            if($privilege === 'admin') {
                //$this->providePrivilege($username,$password);
                $boController = new BOController($this->messages, true);
                $boController->show($this->messages, true);
            }elseif($privilege === 'invited') {
                $entities = ['messages' => $this->messages];
                $this->render('user/login',$entities);
            }elseif($privilege === 'null'){
                $this->messages['errors'][] = 'Login or password incorrect';
            }
		}

        if($privilege === 'null'){
            $entities = ['messages' => $this->messages];
            $this->render('user/login',$entities);
        }

	}
	
	private function providePrivilege($username,$password): string
    {
		$this->user = DBAuth::login($username,$password);
		if(!empty($this->user[0])){
			if($this->user[0]['privilege_id'] === 1){
				$this->logUser();
                $this->messages['infos'][] = "You are logged as Administrator";
                return 'admin';
			}else{
				$this->messages['infos'][] = "You are logged as Invited";
				$this->messages['infos'][] = "For more privileges ask your Administrator";
                return 'invited';
			}
		}
		return 'null';
	}
	
	private function logUser(): void
    {
		//Set the session cookie with SameSite=None
		$params = session_get_cookie_params();
		$params['samesite'] = 'None';
		session_set_cookie_params($params);
		
		//Create a session
		$cookie_lifetime = Config::getGenConfKey('cookie_lifetime');
		if(!isset($_SESSION)) { 
			session_start(['cookie_lifetime' => $cookie_lifetime]);
		}
		
		//Set the session variable
		$_SESSION['auth'] = $this->id;
		
		setcookie('user', $this->username, $cookie_lifetime);

    }

	public function disconnect(){
		if(isset($_SESSION)) { 
			unset($_SESSION['auth']);
			session_destroy(); 
		}
		$homeController = new HomeController();
		$homeController->show();
	}
	
	public static function islogged(){
		return $_SESSION['auth'] ?? NULL;
	}

}

