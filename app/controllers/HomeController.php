<?php

//namespace Yophp\Application\Controllers;

class HomeController
{
    public function __construct()
    {
    }

    public function index() {
        $data = array();
        $data['title'] = 'Yophp';

        view()->render('home/index', $data);
    }

}