<?php

//namespace Yophp\Application\Controllers;

class HomeController
{
    public function __construct()
    {
    }

    public function index() {
        $data = array();
        $testModel = TestModel::getInstance();
        
        $data['test'] = $testModel->getResult(array('name like'=>'jacky'), 10, 0, 'id ASC');
        $data['var'] = clean($_GET['var']);
        
        view()->render('home/index', $data);
    }
}