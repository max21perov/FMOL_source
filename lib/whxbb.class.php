<?php
//
// +----------------------------------------------------------------------+
// | WHXBB          基类                                                  |
// +----------------------------------------------------------------------+
// | Copyright (c) 2001 NetFish Software                                  |
// |                                                                      |
// | Author: whxbb(whxbb@21cn.com)                                        |
// +----------------------------------------------------------------------+
//
// $Id: whxbb.class.php,v 0.1 2001/8/4 12:53:33 yf Exp $
//
// 禁止直接访问该页面
if (basename($HTTP_SERVER_VARS['PHP_SELF']) == "whxbb.class.php") {
    header("HTTP/1.0 404 Not Found");
}

// 调试标志，为1时，系统运行在调试状态
define('WHXBB_DEBUG_FLAG', 0);

// 出错代码的预定义
// 忽略错误
define('WHXBB_ERROR_IGNORE', 1);
// 在页面显示错误
define('WHXBB_ERROR_ECHO'  , 2);
// 弹出错误警告并显示错误
define('WHXBB_ERROR_ALERT' , 4);
// 停止程序的运行
define('WHXBB_ERROR_DIE'   , 8);
// 返回上页
define('WHXBB_ERROR_RETURN', 16);
// 跳到指定页
define('WHXBB_ERROR_GOTO', 32);

/**
* Purpose
*  基类， 在该类中封装了一些常用的方法
*
* @author  : whxbb(whxbb@21cn.com)
* @version : 0.1
* @date    :  2001/12/4
*/
class WHXBB
{
    /**
     * 调试标志
     * @access protected
     */
    protected $_debug;
    /**
     * 数据库连接标志
     * @access protect
     */
    protected $_conn;

    function WHXBB()
    {
  // 数据库连接标志
        global $_conn;
        if (!is_resource($conn))
            die("数据库连接错误");
        $this->_conn = $conn;
        $this->_debug = WHXBB_DEBUG_FLAG;
    }

    /**
     * 处理字符串
     * @param $str 要处理的字符串
     * @param $act in 将'替换成’out 把’替换成'
  * @access public
     */
    function OperateString(&$str, $act)
    {
        if($act == 'in')
            $str = str_replace("'", "\’", $str);
        if($act == 'out')
            $str = str_replace("\’", "'", $str);
    }
    /**
     * 判断一个变量是否为错误对象
     *
     * @param   $data   要判断的变量
     * @access  public
     * @return  bool    是 true 不是　false
     */
    function isError($data) {
        return (bool)(is_object($data) &&
                      (get_class($data) == "whxbb_error" ||
                       is_subclass_of($data, "whxbb_error")));
    }
    /**
     * 判断一个变量是否为分页对象
     *
     * @param   $data   the value to test
     * @access  public
     * @return  bool    true if $data is an Pager
     */
    function isPager($data) {
        return (bool)(is_object($data) &&
                      (get_class($data) == "pager" ||
                       is_subclass_of($data, "pager")));
    }
}

/**
* 调试类
*
* Purpose
*
*　程序调试用
*
* @author  : wxhbb(whxbb@21cn.com)
* @version : 0.1
* @date    :  2001/8/4
*/
class WHXBB_Debug Extends WHXBB
{
    function WHXBB_Debug($msg)
    {
        $this->WHXBB();
        if($this->_debug == 1)
        {
            echo "n<br>WHXBB Debug >>> $msg<br>n";
        }    
    }
}
/**
* Purpose
* 错误处理(触发错误，显示错误)
*
* @author  : whxbb(whxbb@21cn.com)
* @version : 0.1
* @date    :  2001/8/4
*/
class WHXBB_Error extends WHXBB
{
    /**
     * 错误信息
     * @access protected
     */
    protected $_errNo;
    /**
     * 错误代码
     * @access protected
     */
    protected $_errMsg;
    /** 报错方式 参见"出错代码的预定义" */
    protected $_reportMethod;

    /**
     * 构造一个错误对象
     * @param $errMsg   错误信息, 错误的字符描述
     * @param $errNo    错误代码, 错误的代码
     * @param $reportMethod 报错方式,参见"出错代码的预定义"
     * @param $param1 参数一，如跳转到指定页面时页面的url
     * @param $param2 参数二 保留
     * @access public
     */
    function WHXBB_Error($errMsg, $errNo, $reportMethod = WHXBB_ERROR_IGNORE, $param1 = '', $param2 = '')
    {
        $this->WHXBB();
        $this->_errMsg = $errMsg;
        $this->_errNo = $errNo;
        $this->_reportMethod = $reportMethod;
        switch($reportMethod)
        {
            case WHXBB_ERROR_IGNORE:
                break;
            case WHXBB_ERROR_ECHO:
                echo $errMsg;
                break;
            case WHXBB_ERROR_ALERT:
                JS::Alert($errMsg);
                break;
            case WHXBB_ERROR_DIE:
                $this->Close();
                exit;
                break;
            case WHXBB_ERROR_DIE + WHXBB_ERROR_ALERT:
                JS::Alert($errMsg);
                $this->Close();
                exit;
                break;
            case WHXBB_ERROR_DIE + WHXBB_ERROR_ECHO:
                echo $errMsg;
                $this->Close();
                exit;
                break;
            case WHXBB_ERROR_ALERT + WHXBB_ERROR_RETURN:
                JS::ALert($errMsg);
                JS::Back();
                break;
            case WHXBB_ERROR_RETURN:
                JS::Back();
                break;
            case WHXBB_ERROR_GOTO:
                JS::Goto($param1);
                break;
            case WHXBB_ERROR_GOTO + WHXBB_ERROR_ALERT:
                JS::ALert($errMsg);
                JS::Goto($param1);
                break;
        }
        new WHXBB_Debug($errNo.":".$errMsg);
    }
    /**
     * 得到错误对象的错误信息
     */
    function GetMsg()
    {
        return $this->_errMsg;
    }
    /**
     * 得到错误对象的错误代买
     */
    function GetNo()
    {
        return $this->_errNo;
    }
}
?>

 
