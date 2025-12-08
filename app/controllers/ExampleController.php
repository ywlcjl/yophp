<?php

//namespace Yophp\Application\Controllers;

class ExampleController
{
    public function __construct()
    {
    }

    public function index() {
        $data = array();

        $status = 1;
        if(isset($_GET['status']) && $_GET['status'] !== '') {
            $status = clean($_GET['status']);
        }

        $paramInput = array(
            'status' => $status,
        );

        $exampleModel = ExampleModel::getInstance();

        $data['examples'] = $exampleModel->getResult($paramInput, 10, 0, 'id DESC');
        $data['status'] = $status;
        $data['statuss'] = $exampleModel->_statuss;
        $data['title'] = 'Yophp Example Index';
        
        view()->render('example/index', $data);
    }

    public function json() {
        $response = array('message' => 'hello message', 'code' => 200);
        view()->json($response);
    }

    public function detail()
    {
        $data = array();

        $detailId = uri(2);
        //先查路由映射
        if(!is_numeric($detailId)) {
            //查不到再查普通入口
            $detailId = uri(3);
            if(!is_numeric($detailId)) {
                $detailId = 0;
            }
        }

        $exampleModel = ExampleModel::getInstance();
        $detail = array();
        if ($detailId > 0) {
            $row = $exampleModel->getRow(array('id' => $detailId, 'status' => 1));
            if (count($row) > 0) {
                $detail = $row;
            }
        }

        $data['detail'] = $detail;
        $data['detailId'] = $detailId;
        $data['statuss'] = $exampleModel->_statuss;
        $data['title'] = 'Yophp Example Detail';
        view()->render('example/detail', $data);

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

        view()->render('example/crud', $data);
    }
    
    public function sql() {
        $data = array();
        $exampleModel = ExampleModel::getInstance();

        $sql = "SELECT id, name, desc_txt, `status`, create_time 
                    FROM example 
                    WHERE status=1 
                    ORDER BY id ASC 
                    LIMIT 5";

        $data['examples'] = $exampleModel->query($sql);
        $data['statuss'] = $exampleModel->_statuss;
        $data['sql'] = $sql;
        $data['title'] = 'Yophp Example SQL';

        view()->render('example/sql', $data);
    }
    
    public function cache() {
        $data = array();

        $cache = Yo_Cache::getInstance('apcu', 'file');
        
        $exampleModel = ExampleModel::getInstance();

        $exampleCacheKey = 'examples_cache';

        $message = "";
        $success = 0;

        $examples = $cache->get($exampleCacheKey);
        if(!$examples) {
            $examples = $exampleModel->getResult(array('status'=>1), 10, 0, "id DESC");

            $cache->save($exampleCacheKey, $examples, 3600); //缓存时间单位是秒
            $message = 'create examples catch';
        } else {
            $message = 'found examples catch';
        }

        if($examples) {
            $success = 1;
        }

        $data['code'] = 200;
        $data['current_driver'] = $cache->_currentDriver;
        $data['success'] = $success;
        $data['message'] = $message;
        $data['examples'] = $examples;

        view()->json($data);
    }
    
    public function cacheRedis() {
        $cache = Yo_Cache::getInstance('redis', 'file');
        
        $exampleModel = ExampleModel::getInstance();

        $exampleCacheKey = 'examples_redis_cache';
        $message = "";

        $examples = $cache->get($exampleCacheKey);
        if(!$examples) {
            $examples = $exampleModel->getResult(array('status'=>1), 5, 0, "id DESC");
            $cache->save($exampleCacheKey, $examples, 3600);
            $message = 'create redis cache';
        } else {
            $message = 'found redis Cache';
        }

        $success = 0;

        if($examples) {
            $success = 1;
        }

        $data['code'] = 200;
        $data['current_driver'] = $cache->_currentDriver;
        $data['success'] = $success;
        $data['message'] = $message;
        $data['examples'] = $examples;

        view()->json($data);
    }
    
    public function page() {
        $data = array();
        $exampleModel = ExampleModel::getInstance();

        $paramInput = array();

        $status = '';
        if(isset($_GET['status']) && $_GET['status'] !== '') {
            $status = clean($_GET['status']);
            $paramInput['status'] = $status;
        }

        $pageUrl = '/example/page';
        $suffix = "/?status=$status";

        $examples = $exampleModel->getPage($paramInput, 'id DESC', $pageUrl, 10, $suffix);
        $data['examples'] = $examples;
        $data['status'] = $status;
        $data['statuss'] = $exampleModel->_statuss;
        $data['title'] = 'Yophp Example Page';
        $data['pageUrl'] = $pageUrl;
        
        view()->render('example/page', $data);
    }
    
    public function pagesql() {
        $data = array();
        $exampleModel = ExampleModel::getInstance();

        $status = '';
        $whereSql = '';

        if(isset($_GET['status']) && $_GET['status'] !== '') {
            $status = clean($_GET['status']);
            $whereSql = "AND `status`=$status";
        }

        $suffix = "/?status=$status";

        //注意SELECT id, name, desc_txt, `status`, create_time FROM example, FROM不要换行, 否则匹配不到正则表达字符替换
        $sql = "SELECT id, name, desc_txt, `status`, create_time FROM example 
                WHERE 1=1 $whereSql 
                ORDER BY id ASC";
        //echo $sql;
        $pageUrl = '/example/pagesql';

        $examples = $exampleModel->getPageSql($sql,$pageUrl, 5, $suffix);
        $data['examples'] = $examples;
        $data['sql'] = $sql;
        $data['status'] = $status;
        $data['statuss'] = $exampleModel->_statuss;
        $data['title'] = 'Yophp Example Page SQL';
        $data['pageUrl'] = $pageUrl;

        view()->render('example/page', $data);
    }
}

