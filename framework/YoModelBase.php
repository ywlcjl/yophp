<?php
/*
 * 模型基类
 * YoModelBase 使用Base命名是为了自动加载区分普通的Model类, 方便加载目录
 */
class YoModelBase
{
    //存储所有子类单例的通用静态变量
    protected static $_instances = [];

    protected $_tableName;
    
    protected $_db;

    protected function __construct()
    {
        //获取数据库连接
        $this->_db = YoPdoMysql::getInstance()->getDb();
    }

    private function __clone() {}
    public function __wakeup() {}

    /**
     * 单例模式: 使用后期静态绑定 (Late Static Binding) 实现子类独立单例
     * @return static 返回调用该方法的子类的实例
     */
    public static function getInstance()
    {
        //获取调用者的类名 (例如: ExampleModel)
        $className = static::class;

        if (!isset(self::$_instances[$className])) {
            //假如没有找到就单例模式初始化
            self::$_instances[$className] = new static();

            // 确保子类定义了 protected static $_name
            if (property_exists($className, '_name') && static::$_name) {
                self::$_instances[$className]->_tableName = static::$_name;
            }
        }

        return self::$_instances[$className];
    }

    public function getList($param=[], $limit='', $start=0, $orderBy='id DESC', $fields = '*')
    {
        $result = [];
        list($whereSql, $bindings) = $this->buildWhere($param);

        $sql = "SELECT $fields FROM `{$this->_tableName}` $whereSql ORDER BY $orderBy";

        if ($limit !== '') {
            $sql .= " LIMIT " . $start . ", " . $limit;
        }

        $sth = $this->_db->prepare($sql);
        $sth->execute($bindings);
        $fetch = $sth->fetchAll();

        if ($fetch) {
            $result = $fetch;
        }

        return $result;
    }
    
    public function getRow($param=[], $fields = '*')
    {
        $row = [];
        list($whereSql, $bindings) = $this->buildWhere($param);
        $sql = "SELECT $fields FROM `{$this->_tableName}` $whereSql LIMIT 1";

        $sth = $this->_db->prepare($sql);
        $sth->execute($bindings);
        $fetch = $sth->fetch();

        if ($fetch) {
            $row = $fetch;
        }

        return $row;
    }

    public function count($param=array())
    {
        $num = 0;
        list($whereSql, $bindings) = $this->buildWhere($param);
        $sql = "SELECT COUNT(*) as num FROM `{$this->_tableName}` $whereSql";

        $sth = $this->_db->prepare($sql);
        $sth->execute($bindings);
        $row = $sth->fetch();
        if ($row) {
            $num = $row['num'];
        }
        return $num;
    }
    
    public function query($sql, $bindings=[], $fetchRow=false)
    {
        $sth = $this->_db->prepare($sql);
        $sth->execute($bindings);

        $result = [];
        if($fetchRow) {
            $fetch = $sth->fetch();
            if ($fetch) {
                $result = $fetch;
            }
        } else {
            $fetch = $sth->fetchAll();
            if ($fetch) {
                $result = $fetch;
            }
        }

        return $result;
    }
    
    public function insert($param)
    {
        $insertId = 0;
        $fields = array_keys($param);
        $placeholders = implode(',', array_fill(0, count($fields), '?'));
        $sql = "INSERT INTO `{$this->_tableName}` (`" . implode("`,`", $fields) . "`) VALUES ($placeholders)";

        $sth = $this->_db->prepare($sql);
        $sth->execute(array_values($param));

        $lastInsertId = $this->_db->lastInsertId();

        if ($lastInsertId) {
            $insertId = $lastInsertId;
        }

        return $insertId;
    }

    public function update($setParam, $whereParam)
    {
        $setFields = [];
        $setValues = [];
        foreach ($setParam as $key => $value) {
            $setFields[] = "`$key` = ?";
            $setValues[] = $value;
        }
        $setStr = implode(', ', $setFields);

        list($whereSql, $whereValues) = $this->buildWhere($whereParam);
        $sql = "UPDATE `{$this->_tableName}` SET $setStr $whereSql";

        $sth = $this->_db->prepare($sql);
        return $sth->execute(array_merge($setValues, $whereValues));
    }

    public function delete($param)
    {
        list($whereSql, $bindings) = $this->buildWhere($param);
        $sql = "DELETE FROM `{$this->_tableName}` $whereSql";
        $sth = $this->_db->prepare($sql);
        return $sth->execute($bindings);
    }
    
    public function getPage($param=[], $orderBy = 'id DESC', $url='', $pagePer = 20, $suffix = '', $fields = '*', $customPageStyle='pageDefault') {
        $result = [];
        $urlArray = explode('/', $url);
        $pageUri = 1;
        foreach($urlArray as $key=>$value) {
            if ($value) {
                $pageUri++;
            }
        }

        //计算分页起始条目
        $nowPageUri = intval(uri($pageUri));
        $pageNum = $nowPageUri ? $nowPageUri : 1;
        $startRow = ($pageNum - 1) * $pagePer;

        $total = $this->count($param);
        //获取数据
        $list = $this->getList($param, $pagePer, $startRow, $orderBy, $fields);

        $result['list'] = $list;
        $result['total'] = $total;
        $result['current'] = $pageNum;
        $result['size'] = $pagePer;
        $result['url'] = $url;
        $result['suffix'] = $suffix;

        //创建自定义分页html, 默认为view类的pageDefault方法, 可以在view类添加自定义添加不同的样式方法, 留空则不创建html分页代码
        if ($customPageStyle) {
            view()->$customPageStyle($url, $pageNum, $pagePer, $total, $suffix);
        }

        return $result;
    }
    
    public function getPageSql($sql, $bindings = [], $url='', $pagePer=20, $suffix='', $customPageStyle='pageDefault') {
        $result = [];

        $urlArray = explode('/', $url);
        $pageUri = 1;
        foreach($urlArray as $key=>$value) {
            if ($value) {
                $pageUri++;
            }
        }
        
        //计算分页起始条目
        $nowPageUri = intval(uri($pageUri));
        $pageNum = $nowPageUri ? $nowPageUri : 1;
        $startRow = ($pageNum - 1) * $pagePer;
        
        //使用正则替换SQL, 生成count(*)统计查询分页总数
        $countSql = $sql;
        
        //如果是分组查询则需要做子查询
        if (stristr($countSql, 'GROUP BY') !== FALSE) {
            $pattern = '/^SELECT.*FROM/i';
            $queryCount = preg_replace($pattern,'SELECT COUNT(*) AS total FROM (SELECT COUNT(*) FROM', $countSql);
            $queryCount .= ") AS a";
        } else {
            $pattern = '/^SELECT.*FROM/i';
            $queryCount = preg_replace($pattern,'SELECT COUNT(*) AS total FROM', $countSql); 
        }

        //返回数组
        $row = $this->query($queryCount, $bindings,true);
        $total = isset($row['total']) ? $row['total'] : 0;

        //获取数据
        if ($total > 0) {
            $sql .= " LIMIT $startRow, $pagePer";
            $list = $this->query($sql, $bindings);
        }

        $result['list'] = $list;
        $result['total'] = $total;
        $result['current'] = $pageNum;
        $result['size'] = $pagePer;
        $result['url'] = $url;
        $result['suffix'] = $suffix;

        //创建自定义分页html, 默认为view类的pageDefault方法, 可以在view类添加自定义添加不同的样式方法, 留空则不创建html分页代码
        if ($customPageStyle) {
            view()->$customPageStyle($url, $pageNum, $pagePer, $total, $suffix);
        }
        
        return $result;
    }

    public function insertId()
    {
        return $this->_db->lastInsertId();
    }
    
    /*
     * 数据库sql错误提示
     */
    public function errorInfo($sth) {
        
        if(DEBUG_MODE != 'production') {

            $info = $sth->errorInfo();
            if($info[0] != '00000' && $info[2]!='') {
                echo $info[0].', '.$info[1].', '.$info[2];
            }
        }
    }
    
    /*
     * where条件语句拼接
     */
    protected function buildWhere($param)
    {
        if (empty($param)) return ['', []];

        $conditions = [];
        $bindings = [];

        foreach ($param as $key => $value) {
            $keyArray = explode(' ', trim($key));
            $field = $keyArray[0];
            $op = isset($keyArray[1]) ? strtolower($keyArray[1]) : '=';

            if ($op === 'like') {
                $conditions[] = "`$field` LIKE ?";
                $bindings[] = "%$value%";
            } elseif ($op === 'in' && is_array($value)) {
                $placeholders = implode(',', array_fill(0, count($value), '?'));
                $conditions[] = "`$field` IN ($placeholders)";
                $bindings = array_merge($bindings, array_values($value));
            } elseif (in_array($op, ['>', '>=', '<', '<=', '!='])) {
                $conditions[] = "`$field` $op ?";
                $bindings[] = $value;
            } else {
                $conditions[] = "`$field` = ?";
                $bindings[] = $value;
            }
        }

        return [' WHERE ' . implode(' AND ', $conditions), $bindings];
    }
}
