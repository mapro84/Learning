<?php
namespace src\app\Controller;

use src\Core\DB\Entity;
use src\Core\Utils\Debug;
use src\app\Controller\UserController;

class BOController extends AppController{
	
	public function show(array $messages=[], bool $authentication = false) {
        if($authentication === true){
            $skills = Entity::getAll('skill');
            $items = Entity::getAll('item');
            $entities = array('skills' => $skills, 'items' => $items, 'messages' => $messages);
            $this->render('bo',$entities);
        }else{
            $userController = new UserController();
            $userController->login();
        }

        /*
        if (getenv('admin') !== 'true' && $authent === false){

        }else{
            $skills = Entity::getAll('skill');
            $items = Entity::getAll('item');
            $entities = array('skills' => $skills, 'items' => $items, 'messages' => $messages);
            $this->render('bo',$entities);
        }
        */
	}

}