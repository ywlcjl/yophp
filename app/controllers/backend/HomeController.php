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
        
        $tests = array();
        $result = $testModel->getResult(array('sex'=>1, 'age >'=>18), 10, 0);
        if($result) {
            $tests = BaseLib::getInstance()->getKeyToName($result);
        }
        $data['tests'] = $tests;
        
        view()->render('backend/home/index', $data);
    }
    
}

