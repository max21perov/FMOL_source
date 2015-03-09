<?php
//
// +----------------------------------------------------------------------+
// | JS        javascript ��                                              |
// +----------------------------------------------------------------------+
// | Copyright (c) 2001 NetFish Software                                  |
// |                                                                      |
// | Author: whxbb(whxbb@21cn.com)                                        |
// +----------------------------------------------------------------------+
//
// $Id: js.class.php,v 0.1 2001/8/5 18:48:33 yf Exp $
//

// ��ֱֹ�ӷ��ʸ�ҳ��
if (basename($HTTP_SERVER_VARS['PHP_SELF']) == "js.class.php") {
    header("HTTP/1.0 404 Not Found");
}

/**
* Purpose
* ��װ��һЩ���õ�Javascript���룬�Ա���PHP�п��ٵ���
* @author  : whxbb(whxbb@21cn.com)
* @version : 0.1
* @date    :  2001/8/5
*/
class JS
{
    function JS(){}
    
    /**
     *��������ҳ
     * @param $step ���صĲ��� Ĭ��Ϊ1
     */
    function Back($step = -1)
    {
        $msg = "history.go(".$step.");";
        JS::_Write($msg);
        JS::FreeResource();
        exit;
    }

    /**
     * ��������Ĵ���
     * @param $msg ������Ϣ
     */
    function Alert($msg)
    {
        $msg = "alert("".$msg."");";
        JS::_Write($msg);
    }
    /**
     * дjs
     * @param $msg
     */
    function _Write($msg)
    {
        echo "<script language="javascript">n";
        echo $msg;
        echo "n</script>";
    }

    /**
     * ˢ�µ�ǰҳ
     */
    function Reload()
    {
        $msg = "location.reload();";
        JS::FreeResource();
        JS::_Write($msg);
        exit;
    }
    /**
     * ˢ�µ�����ҳ
     */
    function ReloadOpener()
    {
        $msg = "if (opener)    opener.location.reload();";
        JS::_Write($msg);
    }

    /**
     * ��ת��url
     * @param $url Ŀ��ҳ
     */
    function Goto($url)
    {
        $msg = "location.href = '$url';";
        JS::FreeResource();
        JS::_Write($msg);
        exit;
    }
    /**
     * �رմ���
     */
     function Close()
     {
         $msg = "window.close()";
        JS::FreeResource();
        JS::_Write($msg);
        exit;
        
     }
    /**
     * �ύ��
     * @param $frm ����
     */
    function Submit($frm)
    {
        $msg = $frm.".submit();";
        JS::_Write($msg);
    }
    /** 
     * �ر����ݿ�����
     */
    function FreeResource()
    {
        // ���ݿ����ӱ�־
        global $conn;
        if (is_resource($conn))
            @mysql_close($conn);
    }
}
?>
