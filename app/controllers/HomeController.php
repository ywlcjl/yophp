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
    
    public function test() {
        echo 'test <br/>';
        
        $testModel = TestModel::getInstance();
        
        $test = $testModel->getRow(array('name'=>'jacky12'));
        echo 'getRow: <br>';
        print_r($test);
        
        $tests = $testModel->getResult(array('sex in'=>array(1,2), 'name like'=>'jacky', 'age >'=>17), '', 0, 'id DESC');
        echo '<br>getResult: <br>';
        print_r($tests);
        
        $count = $testModel->count(array('sex'=>1));
        print "<br>count: $count<br>";
        
        $queryData = $testModel->query("SELECT * FROM test WHERE sex=2 ORDER BY id ASC LIMIT 1");
        echo "query sql: SELECT * FROM test WHERE sex=2 ORDER BY id ASC LIMIT 1<br>";
        print_r($queryData);
        
        $insertParam = array('name'=>'jacky'.rand(1,100), 'age'=>rand(16,22),'sex'=>rand(1,2), 'update_time'=>date('Y-m-d H:i:s'));
        echo '<br>insert: <br>';
        //$insert = $testModel->insert($insertParam);
        $insertId = $testModel->insertId();
        echo "insertId: $insertId";
        
        $updateParam = array('name'=>'lose'.rand(100,200), 'update_time'=>date('Y-m-d H:i:s'));
        $update = $testModel->update($updateParam, array('id'=>5, 'sex'=>1));
        echo "<br> update row: $update";
        
        $deleteParam = array('name like'=>'jacky', 'id'=>28);
        //$delete = $testModel->delete($deleteParam);
        echo "<br> delete row: $delete";
        
    }
    
    public function test2() {
        $testModel = TestModel::getInstance();
        
        $queryData = $testModel->query("SELECT * FROM test WHERE sex=2 ORDER BY id ASC LIMIT 1");
        print_r($queryData);
    }
    
    public function redis(){
        $redis = new Redis();
        $redis->connect('127.0.0.1', 6379);
        $redis->setex("test", 3600, "Hello Redis");
        echo $redis->get("test");
        
        $testModel = TestModel::getInstance();
        
        $result = $redis->get("result");
        if(!$result) {
            $result = $testModel->getResult(array('sex'=>1), 10, 0);
            $redis->setex("result", 3600, serialize($result));
        } else {
            $result = unserialize($result);
        }

        
        echo '<br>';
        print_r($result);
    }
    
    public function redisex() {
        $redis = Yo_RedisEx::getInstance();
        $testModel = TestModel::getInstance();
        
        $tests = $redis->get("test", true);
        if(!$tests) {
            $tests = $testModel->getResult(array('sex'=>2), 10, 0);
            $redis->set("test", $tests, 3600);
        }
        
        print_r($tests);
    }
    
    public function page() {
        $data = array();
        $testModel = TestModel::getInstance();
        
        $tests = $testModel->getPage(array('sex'=>2), 'id DESC', '/home/page', 2);
        $data['tests'] = $tests;
        
        view()->render('home/page', $data);
    }
    
    public function pagesql() {
        $data = array();
        $testModel = TestModel::getInstance();
        
        $tests = $testModel->getPageSql("SELECT * FROM test WHERE sex=2 ORDER BY id ASC", '/home/pagesql', 5, '?cid=2');
        $data['tests'] = $tests;
        
        view()->render('home/page', $data);
    }
}

