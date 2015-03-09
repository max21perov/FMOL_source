<?php
//
// +----------------------------------------------------------------------+
// | WHXBB          ����                                                  |
// +----------------------------------------------------------------------+
// | Copyright (c) 2001 NetFish Software                                  |
// |                                                                      |
// | Author: whxbb(whxbb@21cn.com)                                        |
// +----------------------------------------------------------------------+
//
// $Id: whxbb.class.php,v 0.1 2001/8/4 12:53:33 yf Exp $
//
// ��ֱֹ�ӷ��ʸ�ҳ��
if (basename($HTTP_SERVER_VARS['PHP_SELF']) == "whxbb.class.php") {
    header("HTTP/1.0 404 Not Found");
}

// ���Ա�־��Ϊ1ʱ��ϵͳ�����ڵ���״̬
define('WHXBB_DEBUG_FLAG', 0);

// ��������Ԥ����
// ���Դ���
define('WHXBB_ERROR_IGNORE', 1);
// ��ҳ����ʾ����
define('WHXBB_ERROR_ECHO'  , 2);
// �������󾯸沢��ʾ����
define('WHXBB_ERROR_ALERT' , 4);
// ֹͣ���������
define('WHXBB_ERROR_DIE'   , 8);
// ������ҳ
define('WHXBB_ERROR_RETURN', 16);
// ����ָ��ҳ
define('WHXBB_ERROR_GOTO', 32);

/**
* Purpose
*  ���࣬ �ڸ����з�װ��һЩ���õķ���
*
* @author  : whxbb(whxbb@21cn.com)
* @version : 0.1
* @date    :  2001/12/4
*/
class WHXBB
{
    /**
     * ���Ա�־
     * @access protected
     */
    protected $_debug;
    /**
     * ���ݿ����ӱ�־
     * @access protect
     */
    protected $_conn;

    function WHXBB()
    {
  // ���ݿ����ӱ�־
        global $_conn;
        if (!is_resource($conn))
            die("���ݿ����Ӵ���");
        $this->_conn = $conn;
        $this->_debug = WHXBB_DEBUG_FLAG;
    }

    /**
     * �����ַ���
     * @param $str Ҫ������ַ���
     * @param $act in ��'�滻�ɡ�out �ѡ��滻��'
  * @access public
     */
    function OperateString(&$str, $act)
    {
        if($act == 'in')
            $str = str_replace("'", "\��", $str);
        if($act == 'out')
            $str = str_replace("\��", "'", $str);
    }
    /**
     * �ж�һ�������Ƿ�Ϊ�������
     *
     * @param   $data   Ҫ�жϵı���
     * @access  public
     * @return  bool    �� true ���ǡ�false
     */
    function isError($data) {
        return (bool)(is_object($data) &&
                      (get_class($data) == "whxbb_error" ||
                       is_subclass_of($data, "whxbb_error")));
    }
    /**
     * �ж�һ�������Ƿ�Ϊ��ҳ����
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
* ������
*
* Purpose
*
*�����������
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
* ������(����������ʾ����)
*
* @author  : whxbb(whxbb@21cn.com)
* @version : 0.1
* @date    :  2001/8/4
*/
class WHXBB_Error extends WHXBB
{
    /**
     * ������Ϣ
     * @access protected
     */
    protected $_errNo;
    /**
     * �������
     * @access protected
     */
    protected $_errMsg;
    /** ����ʽ �μ�"��������Ԥ����" */
    protected $_reportMethod;

    /**
     * ����һ���������
     * @param $errMsg   ������Ϣ, ������ַ�����
     * @param $errNo    �������, ����Ĵ���
     * @param $reportMethod ����ʽ,�μ�"��������Ԥ����"
     * @param $param1 ����һ������ת��ָ��ҳ��ʱҳ���url
     * @param $param2 ������ ����
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
     * �õ��������Ĵ�����Ϣ
     */
    function GetMsg()
    {
        return $this->_errMsg;
    }
    /**
     * �õ��������Ĵ������
     */
    function GetNo()
    {
        return $this->_errNo;
    }
}
?>

 
