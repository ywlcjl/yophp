<?php

class YoView
{
    //protected $variables = array();
    
    private static $_instance;
    
    private $_pageNav;

    protected function __construct() {}
    private function __clone() {}
    public function __wakeup() {}
    
    //单例模式
    public static function getInstance()
    {
        if (!(self::$_instance instanceof self)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }
    
    /*
     * 渲染视图
     */
    public function render($view, $data=array()) {
        if($data) {
            extract($data);
        }

        $viewFile = VIEW_DIR.$view.'.php';
        
        if(file_exists($viewFile)) {
            include $viewFile;
        }
    }

    /**
     * 输出json
     * @param $response
     * @return void
     */
    public function json($response=[])
    {
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($response);
        exit;
    }
    
    public function getPageNav() {
        return $this->_pageNav;
    }
    
    public function setPageNav($str) {
        $this->_pageNav = $str;
    }

    public function pageDefault($baseUrl, $pageNum, $perPage = 20, $totalRows = 200, $suffix = '')
    {
        $this->createPageNav($baseUrl, $pageNum, $perPage, $totalRows, $suffix);
    }

    public function pageDefaultCn($baseUrl, $pageNum, $perPage = 20, $totalRows = 200, $suffix = '')
    {
        $customName=array('next'=>'下一页', 'prev'=>'上一页', 'first'=>'首页', 'last'=>'尾页');
        $this->createPageNav($baseUrl, $pageNum, $perPage, $totalRows, $suffix, $customName);
    }


    public function createPageNav($baseUrl, $pageNum, $perPage = 20, $totalRows = 200, $suffix = '',
                                  $customName=array('next'=>'Next', 'prev'=>'Prev', 'first'=>'First', 'last'=>'Last'),
                                  $customClass = array('divClass'=>'pageList', 'aClass'=>'pageLink')

    ) {
        $divClass = 'class="'.$customClass['divClass'].'"';
        $aClass = 'class="'.$customClass['aClass'].'"';
        $nowPage = $pageNum;
        $totalPage = intval($totalRows / $perPage);
        if($totalRows % $perPage > 0) {
            $totalPage = $totalPage + 1;
        }

        $next = '';
        $nextPage = $nowPage+1;
        if($nowPage < $totalPage) {
            $next = "<a href=\"$baseUrl/$nextPage{$suffix}\" $aClass>{$customName['next']}</a> ";
        }
        $prev = '';
        $prevPage = $nowPage-1;
        if($nowPage > 1) {
            $prev = "<a href=\"$baseUrl/$prevPage{$suffix}\" $aClass>{$customName['prev']}</a> ";
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

        $pageStr = "<div $divClass>";

        $pageStr .= "<a href=\"$baseUrl/1{$suffix}\" $aClass>{$customName['first']}</a> ";
        if($prev) {
            $pageStr .= $prev;
        }

        if($pageList) {
            foreach ($pageList as $key=>$value) {
                if($nowPage == $value) {
                    $pageStr .= "<b>$value</b> ";
                } else {
                    $pageStr .= "<a href=\"$baseUrl/$value{$suffix}\" $aClass>$value</a> ";
                }
            }
        }

        if($next) {
            $pageStr .= $next;
        }

        $pageStr .= "<a href=\"$baseUrl/$totalPage{$suffix}\" $aClass>{$customName['last']}</a> ";

        $pageStr .= '</div>';

        $this->setPageNav($pageStr);
    }

}
