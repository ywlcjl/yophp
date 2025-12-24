<?php

//namespace Yophp\Application\Controllers;

class ExampleController extends YoControllerBase
{
    public function __construct()
    {
        //加载自定义helper函数文件
        $this->loadHelper('MyHelper');
    }

    public function index()
    {
        $data = [];

        //controller的get过滤
        $status = $this->get('status', 1);

        $paramInput = array(
            'status' => $status,
        );

        $exampleModel = ExampleModel::getInstance();

        $data['examples'] = $exampleModel->getList($paramInput, 10, 0, 'id DESC');
        $data['status'] = $status;
        $data['statuss'] = $exampleModel->_statuss;
        $data['title'] = 'Yophp Example Index';

        view()->render('example/index', $data);
    }

    public function json()
    {
        $response = array('message' => 'hello message', 'code' => 200);
        view()->json($response);
    }

    /**
     * @param $id 该命名必须和route里面的{name}一致
     * @return void
     */
    public function detail($id = 0)
    {
        $data = [];

        $id = intval($id);
        if (!$id) {
            exit('not found id');
        }
        $detailId = $id;


        $exampleModel = ExampleModel::getInstance();
        $detail = [];
        if ($detailId > 0) {
            $row = $exampleModel->getRow(array('id' => $detailId));
            if ($row) {
                $detail = $row;
            }
        }

        $data['detail'] = $detail;
        $data['detailId'] = $detailId;
        $data['statuss'] = $exampleModel->_statuss;
        $data['title'] = 'Yophp Example Detail';
        view()->render('example/detail', $data);
    }

    /**
     * @param $id   该命名必须和route里面的{id}一致
     * @param $name 该命名必须和route里面的{name}一致
     * @return void
     */
    public function detailWithName($id = 0, $name = '')
    {
        $data = [];

        $id = intval($id);
        if (!$id) {
            exit('not found id');
        }
        $detailId = $id;


        $exampleModel = ExampleModel::getInstance();
        $detail = [];
        if ($detailId > 0) {
            $row = $exampleModel->getRow(array('id' => $detailId));
            if ($row) {
                $detail = $row;
            }
        }

        $data['detail'] = $detail;
        $data['detailId'] = $detailId;
        $data['statuss'] = $exampleModel->_statuss;
        $data['title'] = 'Yophp Example Detail with Name: ' . $name;
        view()->render('example/detail', $data);
    }

    public function edit()
    {
        $data = [];
        $exampleModel = ExampleModel::getInstance();

        $id = $this->get('id', '');
        $detail = [];

        if ($id > 0) {
            //编辑example
            $param = array('id' => $id);
            $row = $exampleModel->getRow($param);
            if ($row) {
                $detail = $row;

            }
        } else {
            //新增
        }

        $data['id'] = $id;
        $data['detail'] = $detail;
        $data['statuss'] = $exampleModel->_statuss;
        $titleSuffix = $id > 0 ? 'Edit' : 'Add';
        $data['title'] = "Yophp Example $titleSuffix";

        view()->render('example/edit', $data);
    }

    public function save()
    {
        $data = [];
        $success = 0;
        $message = '';

        if (isset($_POST) && !empty($_POST)) {
            $exampleModel = ExampleModel::getInstance();

            //验证器
            $validator = new YoValidator();
            $validator->rule('name', '名称')->required()->alphaDash();
            $validator->rule('status', '状态')->required()->numeric();
            $validator->rule('desc_txt', '描述')->maxLength(20);

            $id = intval($this->post('id'));
            $name = $this->post('name', '', false);
            $status = $this->post('status');
            $descTxt = $this->post('desc_txt');


            if (!$validator->run()) {
                //规则验证失败
                $message = $validator->getErrorInfo();
            } else {
                $inputParam = [];
                $inputParam['name'] = $name;
                $inputParam['desc_txt'] = $descTxt;
                $inputParam['status'] = $status;

                $affected = '';
                if ($id > 0) {
                    //编辑现有条目
                    $whereParam['id'] = $id;
                    $inputParam['update_time'] = date('Y-m-d H:i:s');
                    $affected = $exampleModel->update($inputParam, $whereParam);
                } else {
                    //新增条目
                    $affected = $exampleModel->insert($inputParam);
                }

                if ($affected) {
                    $success = 1;
                    if ($id > 0) {
                        $message = 'edit success';
                    } else {
                        $message = 'add success';
                    }
                } else {
                    $message = 'save fail or no affected';
                }
            }

            if ($success) {
                goUrl("/example/page/?success=$success&message=$message");
            } else {
                $detail = array(
                    'id' => $id,
                    'name' => $name,
                    'desc_txt' => $descTxt,
                    'status' => $status
                );

                $data['detail'] = $detail;
                $data['statuss'] = $exampleModel->_statuss;
                $titleSuffix = $id > 0 ? 'Edit' : 'Add';
                $data['title'] = "Yophp Example $titleSuffix";
                $data['success'] = $success;
                $data['message'] = $message;
                view()->render('example/edit', $data);
            }

        } else {
            die("Input error");
        }
    }

    public function delete()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            die('Illegal Request');
        }

        $data = [];
        $message = '';
        $success = 0;

        $id = intval($_POST['id']);

        $exampleModel = ExampleModel::getInstance();

        if ($id > 0) {
            $param = array('id' => $id);
            $delete = $exampleModel->delete($param);
            if ($delete) {
                $success = 1;
                $message = "delete ID $id success";
            }
        } else {
            $message = 'Input id incorrect';
        }

        goUrl("/example/page/?success=$success&message=$message");
    }

    public function sql()
    {
        $data = [];
        $exampleModel = ExampleModel::getInstance();

        $sql = "SELECT id, name, desc_txt, `status`, create_time 
                    FROM example 
                    WHERE status=? 
                    ORDER BY id ASC 
                    LIMIT 5";

        $data['examples'] = $exampleModel->query($sql, [1]);
        $data['statuss'] = $exampleModel->_statuss;
        $data['sql'] = $sql;
        $data['title'] = 'Yophp Example SQL';

        view()->render('example/sql', $data);
    }

    public function cache()
    {
        $data = [];

        $cache = YoCache::getInstance('apcu', 'file');

        $exampleModel = ExampleModel::getInstance();

        $exampleCacheKey = 'examples_cache';

        $message = "";
        $success = 0;

        $examples = $cache->get($exampleCacheKey);
        if (!$examples) {
            $examples = $exampleModel->getList(array('status' => 1), 10, 0, "id DESC");

            $cache->save($exampleCacheKey, $examples, 3600); //缓存时间单位是秒
            $message = 'create examples catch';
        } else {
            $message = 'found examples catch';
        }

        if ($examples) {
            $success = 1;
        }

        $data['code'] = 200;
        $data['current_driver'] = $cache->_currentDriver;
        $data['success'] = $success;
        $data['message'] = $message;
        $data['examples'] = $examples;

        view()->json($data);
    }

    public function cacheRedis()
    {
        $cache = YoCache::getInstance('redis', 'file');

        $exampleModel = ExampleModel::getInstance();

        $exampleCacheKey = 'examples_redis_cache';
        $message = "";

        $examples = $cache->get($exampleCacheKey);
        if (!$examples) {
            $examples = $exampleModel->getList(array('status' => 1), 5, 0, "id DESC");
            $cache->save($exampleCacheKey, $examples, 3600);
            $message = 'create redis cache';
        } else {
            $message = 'found redis Cache';
        }

        $success = 0;

        if ($examples) {
            $success = 1;
        }

        $data['code'] = 200;
        $data['current_driver'] = $cache->_currentDriver;
        $data['success'] = $success;
        $data['message'] = $message;
        $data['examples'] = $examples;

        view()->json($data);
    }

    public function page()
    {
        $data = [];
        $exampleModel = ExampleModel::getInstance();

        $paramInput = [];

        $status = '';
        if (isset($_GET['status']) && $_GET['status'] !== '') {
            $status = $this->get('status');
            $paramInput['status'] = $status;
        }

        $pageUrl = '/example/page';
        $suffix = "/?status=$status";

        $result = $exampleModel->getPage($paramInput, 'id DESC', $pageUrl, 10, $suffix);

        $data['examples'] = $result['list'];
        $data['status'] = $status;
        $data['statuss'] = $exampleModel->_statuss;
        $data['title'] = 'Yophp Example Page';
        $data['pageUrl'] = $pageUrl;
        $data['success'] = $_GET['success'] ?? '';
        $data['message'] = $_GET['message'] ?? '';

        view()->render('example/page', $data);
    }

    public function pagesql()
    {
        $data = [];
        $exampleModel = ExampleModel::getInstance();

        $bindings = [];
        $status = '';
        $whereSql = '';

        if (isset($_GET['status']) && $_GET['status'] !== '') {
            $status = sanitize($_GET['status']);
            $whereSql = "AND `status`=?";
            $bindings[] = $status;
        }

        $suffix = "/?status=$status";

        //注意SELECT id, name, desc_txt, `status`, create_time FROM example, FROM不要换行, 否则匹配不到正则表达字符替换
        $sql = "SELECT id, name, desc_txt, `status`, update_time, create_time FROM example 
                WHERE 1=1 $whereSql 
                ORDER BY id ASC";
        //echo $sql;
        $pageUrl = '/example/pagesql';

        $result = $exampleModel->getPageSql($sql, $bindings, $pageUrl, 5, $suffix, 'pageDefaultCn');
        $data['examples'] = $result['list'];
        $data['sql'] = $sql;
        $data['status'] = $status;
        $data['statuss'] = $exampleModel->_statuss;
        $data['title'] = 'Yophp Example Page SQL';
        $data['pageUrl'] = $pageUrl;

        view()->render('example/page', $data);
    }

    public function cookie()
    {
        $data = [];
        $data['title'] = 'Yophp Example Cookie';

        $cookieName = $this->getCookie('name');
        $data['cookieName'] = $cookieName;

        view()->render('example/cookie', $data);
    }

    public function addCookie()
    {
        $data = [];
        $this->setCookie('name', 'Hello Jacky!');
        $data['success'] = 1;
        $data['message'] = 'cookie added';
        view()->json($data);
    }

    public function delCookie()
    {
        $data = [];
        $this->deleteCookie('name');
        $data['success'] = 1;
        $data['message'] = 'cookie deleted';
        view()->json($data);
    }

    public function clearCookie()
    {
        $data = [];
        $this->clearAllCookies();
        $data['success'] = 1;
        $data['message'] = 'cookie clear';
        view()->json($data);
    }
    
    public function upload()
    {
        $data = [];
        $data['title'] = 'Yophp Example Upload File';
        $success = 0;
        $message = '';
        $uploadData = [];
        $thumbPath = '';

        if (isset($_POST) && !empty($_POST)) {
            $config = [
                'uploadPath'   => UPLOADS_DIR.'example',
                'allowedTypes' => 'png|jpg|jpeg',
                'maxSize'      => 10240,
                'encryptName'  => true,
                'fileNameSuffix' => 'yo_',
            ];

            $upload = new YoUpload($config);

            if ($upload->doUpload('file')) {
                $uploadData = $upload->getData();
                $success = 1;
                $message = 'upload success';

                //生成缩略图
                if (file_exists($uploadData['fullPath'])) {
                    $image = new YoImage($uploadData['fullPath']);
                    $result = $image->thumb(360, 240, 'thumb', 100, true);
                    if ($result) {
                        $thumbPath = getImgPath($uploadData['relativePath'], 'thumb');
                    }
                }
            } else {
                $message = 'upload fail: '. $upload->getError();
            }
        }

        $data['success'] = $success;
        $data['message'] = $message;
        $data['uploadData'] = $uploadData;
        $data['thumbPath'] = $thumbPath;

            view()->render('example/upload', $data);
    }
}

