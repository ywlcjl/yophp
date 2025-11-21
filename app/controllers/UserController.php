<?php
//namespace Yophp\Application\Controllers;

class UserController
{
    public function __construct()
    {
    }

    public function index() {
        $data = array();
        $userModel = UserModel::getInstance();
        
        $data['users'] = $userModel->getResult(array('sex'=>2, 'id >='=> 5), 10, 0, 'id ASC');
        $data['var'] = clean($_GET['var']);
        
        view()->render('user/index', $data);
    }
    
    public function crud() {
        $data = array();
        
        $userModel = UserModel::getInstance();
        
        $data['user'] = $userModel->getRow(array('name'=>'jacky12'));
        
        $data['result'] = $userModel->getResult(array('sex in'=>array(1,2), 'name like'=>'jacky', 'age >'=>17), 10, 0, 'id DESC');
        
        $data['count'] = $userModel->count(array('sex'=>1));

        $sql = "SELECT * FROM user WHERE sex=2 ORDER BY id ASC LIMIT 1";
        $data['sql'] = $sql;
        $data['queryData'] = $userModel->query($sql);

        $userNameArray = array('jack', 'tom', 'mike', 'rose', 'bong', 'zero', 'rick', 'viter', 'saw', 'ray');
        $insertParam = array('name'=>$userNameArray[rand(0,9)].rand(100,1000), 'age'=>rand(16,30),'sex'=>rand(1,2), 'update_time'=>date('Y-m-d H:i:s'));
        $insert = $userModel->insert($insertParam);
        $data['insertId'] = $userModel->insertId();

        $updateParam = array('name'=>'lose'.rand(1000, 2000), 'update_time'=>date('Y-m-d H:i:s'));
        $data['update'] = $userModel->update($updateParam, array('id'=>3));

        //$deleteParam = array('name like'=>'jacky', 'id'=>28);
        //$delete = $userModel->delete($deleteParam);

        view()->render('user/crud', $data);
    }
    
    public function sql() {
        $data = array();
        $userModel = UserModel::getInstance();

        $sql = "SELECT * FROM user WHERE sex=2 ORDER BY id ASC LIMIT 5";
        $data['queryData'] = $userModel->query($sql);
        $data['sql'] = $sql;

        view()->render('user/sql', $data);
    }
    
    public function cache() {
        $data = array();
        $cache = Yo_Cache::getInstance('apc', 'file');
        
        $userModel = UserModel::getInstance();
        
        $users = $cache->get("user_cache");
        if(!$users) {
            $users = $userModel->getResult(array('sex'=>2), 10, 0);
            $cache->save("user_cache", $users, 3600);
        }
        $success = 0;
        $message = "";

        if($users) {
            $success = 1;
            $message = 'Cache Catched';
        } else {
            $message = 'no users';
        }

        $data['success'] = $success;
        $data['message'] = $message;
        $data['users'] = $users;

        header('Content-Type: application/json');
        echo json_encode($data);
    }
    
    public function cacheRedis() {
        $cache = Yo_Cache::getInstance('redis', 'apc');
        
        $userModel = UserModel::getInstance();
        
        $users = $cache->get("user_redis_cache");
        if(!$users) {
            $users = $userModel->getResult(array('sex'=>2), 10, 0);
            $cache->save("user_redis_cache", $users, 3600);
        }

        $success = 0;
        $message = "";

        if($users) {
            $success = 1;
            $message = 'Redis Cache Catched';
        } else {
            $message = 'no users';
        }

        $data['success'] = $success;
        $data['message'] = $message;
        $data['users'] = $users;

        header('Content-Type: application/json');
        echo json_encode($data);
    }
    
    public function page() {
        $data = array();
        $userModel = UserModel::getInstance();
        
        $users = $userModel->getPage(array(), 'id DESC', '/user/page', 10);
        $data['users'] = $users;
        
        view()->render('user/page', $data);
    }
    
    public function pagesql() {
        $data = array();
        $userModel = UserModel::getInstance();

        $sex = clean($_GET['sex']);
        $data['sex'] = $sex;

        if(array_key_exists($sex, $userModel->_sexNames)) {
            $whereSql = "AND sex=$sex";
        }

        $users = $userModel->getPageSql("SELECT * FROM user WHERE 1=1 $whereSql ORDER BY id ASC", '/user/pagesql', 5, '?sex='.$sex);
        $data['users'] = $users;
        
        view()->render('user/page', $data);
    }
}

