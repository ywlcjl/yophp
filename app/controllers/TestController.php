<?php
//namespace Yophp\Application\Controllers;

class TestController
{
    public function __construct()
    {
    }

    public function index() {
        $data = array();
        $testModel = TestModel::getInstance();
        
        $data['test'] = $testModel->getResult(array('name like'=>'jacky'), 10, 0, 'id ASC');
        $data['var'] = clean($_GET['var']);
        
        view()->render('test/index', $data);
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
    
    public function cache() {
        $cache = Yo_Cache::getInstance('apc', 'file');
        
        $testModel = TestModel::getInstance();
        
        $tests = $cache->get("test_cache");
        if(!$tests) {
            $tests = $testModel->getResult(array('sex'=>2), 10, 0);
            $cache->save("test_cache", $tests, 3600);
        }
        
        print_r($tests);
    }
    
    public function cacheredis() {
        $cache = Yo_Cache::getInstance('redis', 'file');
        
        $testModel = TestModel::getInstance();
        
        $tests = $cache->get("test_redis_cache");
        if(!$tests) {
            $tests = $testModel->getResult(array('sex'=>2), 10, 0);
            $cache->save("test_redis_cache", $tests, 3600);
        }
        
        print_r($tests);
    }
    
    public function page() {
        $data = array();
        $testModel = TestModel::getInstance();
        
        $tests = $testModel->getPage(array('sex'=>2), 'id DESC', '/test/page', 2);
        $data['tests'] = $tests;
        
        view()->render('test/page', $data);
    }
    
    public function pagesql() {
        $data = array();
        $testModel = TestModel::getInstance();
        
        $tests = $testModel->getPageSql("SELECT * FROM test WHERE sex=2 ORDER BY id ASC", '/home/pagesql', 5, '?cid=2');
        $data['tests'] = $tests;
        
        view()->render('test/page', $data);
    }
}

