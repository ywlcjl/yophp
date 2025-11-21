<?php

//namespace Yophp\Application\Controllers;

class HomeController
{
    public function __construct()
    {
    }

    public function index() {
        $data = array();
        $userModel = UserModel::getInstance();
        
        $users = array();
        $result = $userModel->getResult(array('sex'=>1, 'age >'=>18), 10, 0);
        if($result) {
            $users = BaseLib::getInstance()->getKeyToName($result);
        }
        $data['users'] = $users;
        
        view()->render('backend/home/index', $data);
    }
    
}

