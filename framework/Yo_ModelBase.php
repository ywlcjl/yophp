<?php
/*
 * 模型基类
 */
class Yo_ModelBase
{
    //存储所有子类单例的通用静态变量
    protected static $_instances = [];

    protected $tableName;
    
    protected $db;
    
    public function __construct()
    {
        //获取数据库连接
        $this->db = Yo_PdoMysql::getInstance()->getDb();
    }

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

            // 确保子类定义了 protected static $name
            if (property_exists($className, 'name') && static::$name) {
                self::$_instances[$className]->tableName = static::$name;
            }
        }

        return self::$_instances[$className];
    }

    public function getResult($param=array(), $limit='', $start=0, $orderBy='id DESC')
    {
        $result = array();
        
        $whereStr = $this->whereStr($param);
                
        $sql = "SELECT * FROM ". $this->tableName." $whereStr 
                ORDER BY $orderBy ";

        if($limit !== '') {
            $sql .= "LIMIT $start, $limit ";
        }

        $sth = $this->db->prepare($sql);
        $sth->execute();
        
        if ($sth) {
            $result = $sth->fetchAll(PDO::FETCH_ASSOC);
        }

        return $result;
    }
    
    public function getRow($param=array())
    {
        $row = array();
        $whereStr = $this->whereStr($param);
        
        $sql = "SELECT * FROM ". $this->tableName." $whereStr";

        $sth = $this->db->prepare($sql);
        $sth->execute();
        
        if($sth) {
            $row = $sth->fetch(PDO::FETCH_ASSOC);
        }
        
        return $row;
    }

    public function count($param=array())
    {
        $num = 0;
        $whereStr = $this->whereStr($param);
                
        $sql = "SELECT COUNT(*) as num FROM ". $this->tableName." $whereStr";
        
        $sth = $this->db->prepare($sql);
        $sth->execute();
        if($sth) {
            $row = $sth->fetch(PDO::FETCH_ASSOC);
            $num = $row['num'];
        }
        return $num;
    }
    
    public function query($sql, $fetchRow=false)
    {
        $result = array();
        $sth = $this->db->prepare($sql);
        $sth->execute();
        
        //sql错误提示
        $this->errorInfo($sth);
        
        if ($sth) {
            if($fetchRow) {
                $result = $sth->fetch(PDO::FETCH_ASSOC);
            } else {
                $result = $sth->fetchAll(PDO::FETCH_ASSOC);
            }
        }

        return $result;
    }
    
    public function insert($param)
    {
        $fieldStr = '';
        $valueStr = '';

        if ($param != NULL) {
            $i = 0;
            foreach ($param as $key => $value) {
                if($i > 0) {
                    $fieldStr .= ',';
                    $valueStr .= ',';
                }
                $fieldStr .= "`$key`";
                $valueStr .= "'$value'";
                $i++;
            }
        }

        $sql = "INSERT INTO " .$this->tableName. "($fieldStr) VALUES($valueStr)";

        $insert = $this->db->exec($sql);
        return $insert;
    }

    public function update($setParam, $whereParam)
    {
        $setStr = '';
        
        if ($setParam != NULL) {
            $i = 0;
            foreach ($setParam as $key => $value) {
                if($i > 0) {
                    $setStr .= ',';
                }
                
                $setStr .= "`$key`='$value'";
                $i++;
            }
        }
        $whereStr = $this->whereStr($whereParam);
        
        $sql = "UPDATE " . $this->tableName. " SET $setStr $whereStr";

        $update = $this->db->exec($sql);
        return $update;
    }

    public function delete($param)
    {
        $whereStr = $this->whereStr($param);
        
        $sql = "DELETE FROM " . $this->tableName." $whereStr";
        echo $sql;
        $delete = $this->db->exec($sql);
        return $delete;
    }
    
    public function getPage($param = array(), $orderBy = 'id DESC', $url='', $pagePer = 20, $suffix = '', $pageStyle='pageDefault') {
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
        $result = $this->getResult($param, $pagePer, $startRow, $orderBy);

        //自定义分页处理方法, 默认为pageDefault方法, 可以根据实际情况添加不同的样式方法
        $this->$pageStyle($url, $pageNum, $pagePer, $total, $suffix);  //创建分页链接
        
        return $result;
    }
    
    public function getPageSql($sql, $url, $pagePer=20, $suffix='', $pageStyle='pageDefault') {
        $result = array();

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
        $row = $this->query($queryCount, true);
        $total = isset($row['total']) ? $row['total'] : 0;

        //获取数据
        if ($total > 0) {
            $sql .= " LIMIT $startRow, $pagePer";
            $result = $this->query($sql);
        }
        
        //自定义分页处理方法, 默认为pageDefault方法, 可以根据实际情况添加不同的样式方法
        $this->$pageStyle($url, $pageNum, $pagePer, $total, $suffix);
        
        return $result;
    }

    protected function pageDefault($baseUrl, $pageNum, $perPage = 20, $totalRows = 200, $suffix = '') {
        $aClass = ' class="pageLink"';
        $nowPage = $pageNum;
        $totalPage = intval($totalRows / $perPage);
        if($totalRows % $perPage > 0) {
            $totalPage = $totalPage + 1;
        }

        $next = '';
        $nextPage = $nowPage+1;
        if($nowPage < $totalPage) {
            $next = "<a href=\"$baseUrl/$nextPage{$suffix}\"$aClass>Next</a> ";
        }
        $prev = '';
        $prevPage = $nowPage-1;
        if($nowPage > 1) {
            $prev = "<a href=\"$baseUrl/$prevPage{$suffix}\"$aClass>Prev</a> ";
        }
        
        $showPage = 7;
        $pageList = array();
        
        $startInt = 1;
        $endInt = $totalPage;
        
	//页数列表
        if ($totalPage - $showPage <= 0) {
            
        } else {
            if ($nowPage - intval($showPage / 2) <= 0) {
                $endInt = $showPage;
            } elseif ($nowPage + intval($showPage / 2) > $totalPage) {
                $startInt = $totalPage - $showPage + 1;
                $endInt = $totalPage;
            } else {
                $startInt = $nowPage - intval($showPage / 2);
                $endInt = $nowPage + intval($showPage / 2);
            }
        }
        for($i=$startInt; $i<=$endInt; $i++) {
            $pageList[] = $i;
        }
        
        $pageStr = '<div class="pageList">';
        
        $pageStr .= "<a href=\"$baseUrl/1{$suffix}\"$aClass>First</a> ";
        if($prev) {
            $pageStr .= $prev;
        }
        
        if($pageList) {
            foreach ($pageList as $key=>$value) {
                if($nowPage == $value) {
                    $pageStr .= "<b>$value</b> ";
                } else {
                    $pageStr .= "<a href=\"$baseUrl/$value{$suffix}\"$aClass>$value</a> ";
                }
            }
        }
        
        if($next) {
            $pageStr .= $next;
        }
        
        $pageStr .= "<a href=\"$baseUrl/$totalPage{$suffix}\"$aClass>Last</a> ";
        
        $pageStr .= '</div>';
        
        view()->setPageNav($pageStr);
    }

    public function insertId()
    {
        return $this->db->lastInsertId();
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
    private function whereStr($param) {
        $whereStr = '';
        if($param && is_array($param)) {
            $whereStr .= ' WHERE ';
            
            $i = 0;
            foreach($param as $key=>$value) {
                if($i > 0) {
                    $whereStr .= ' AND ';
                }
                
                //是否需要单引号
                $sy = is_numeric($value) ? '' : "'";
                
                $keyArray = explode(' ', $key);
                
                if(count($keyArray) > 1) {
                    //['name like']='test'
                    $keyName = $keyArray[0];
                    $keySymbol = $keyArray[1];
                    if($keySymbol == 'like') {
                        $whereStr .= "`$keyName` LIKE '%$value%'";
                    } elseif(in_array($keySymbol, array('>', '>=', '<', '<=', '!='))) {
                        $whereStr .= "`$keyName` $keySymbol $sy{$value}$sy";
                    } elseif($keySymbol == 'in') {
                        if($value && is_array($value)) {
                            $whereStr .= "$keyName IN (";
                            foreach ($value as $k=>$v) {
                                if($k > 0) {
                                    $whereStr .= ",";
                                }
                                $sys = is_numeric($v) ? '' : "'";
                                
                                $whereStr .= "$sys{$v}$sys";
                            }
                            $whereStr .= ")";
                        }
                    }
                } else {
                    
                    $whereStr .= "`$key`= $sy{$value}$sy";
                }
                $i++;
            }
        }
        
        return $whereStr;
    }
}
