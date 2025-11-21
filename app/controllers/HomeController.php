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
        
        $data['users'] = $userModel->getResult(array("id >="=>1), 10, 0, 'id DESC');
        $data['sexNames'] = $userModel->_sexNames;
        $data['var'] = clean($_GET['var']);
        
        view()->render('home/index', $data);
    }
}