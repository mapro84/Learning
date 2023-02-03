<?php
namespace src\app\Controller;

use src\app\user\AppUser;
use src\Core\DB\DB;
use src\Core\Utils\Check;
use src\Core\Auth\DBAuth;
use src\Core\Config\Config;
use src\Core\Utils\Debug;

class UserController extends AppController {
	
	private static $user;
	private $id;
	private $username;
	private $privilege_id;

    public static function getAdminConnection(): ?bool
    {
        if(getenv('admin') === 'false') {
            return false;
        }
        $query = "SELECT state from connection WHERE state = ? ;";
        $result = DB::prepare($query, [1]);
        return $result ? (bool)$result[0]['state'] : null;
    }

    private static function setAdminConnection($token = 0): void {
        $query = "UPDATE connection SET state = ? WHERE id = ? ";
        DB::prepare($query, [$token, 1]);
    }

	public function login(){
        $privilege = 'null';
		if(isset($_POST['username']) && isset($_POST['password'])){
			$username = addslashes($_POST['username']);
			$password = addslashes($_POST['password']);
            $privilege = $this->providePrivilege($username,$password);
            if($privilege === 'admin'){
                //$this->providePrivilege($username,$password);
                if(filter_var(getenv('admin'), FILTER_VALIDATE_BOOL) === false){
                    $this->messages['errors'][] = 'Administration interface not available';
                    $entities = ['messages' => $this->messages];
                    $this->render('user/login',$entities);
                }else{
                    self::setAdminConnection(1);
                    $this->messages['infos'][] = "You are logged as Administrator";
                    $boController = new BOController($this->messages, true);
                    $boController->show($this->messages, true);
                }
            }elseif($privilege === 'invited') {
                $this->messages['infos'][] = "You are logged as Invited";
                $this->messages['infos'][] = "For more privileges ask your Administrator";
                self::setAdminConnection();
                $entities = ['messages' => $this->messages];
                $this->render('user/login',$entities);
            }elseif($privilege === 'null'){
                self::setAdminConnection();
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
		self::$user = DBAuth::login($username,$password);
		if(!empty(self::$user[0])){
			if(self::$user[0]['privilege_id'] === 1){
				//$this->logUser();
                return 'admin';
			}else{
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
		
		setcookie('user', self::$username, $cookie_lifetime);

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

