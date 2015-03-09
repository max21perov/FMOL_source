<?php
	/**
	 * 对 post 和 get 传递的url进行判断，检查是否有非法字符 
	 *
	 * @param [ArrFiltrate]				非法字符的
	 *
	 * @return  *****还没有验证这个模块是否正确*****
	 */	

//要过滤的非法字符
$ArrFiltrate=array("'",";","union");
//出错后要跳转的url,不填则默认前一页
$StrGoUrl="";
//是否存在数组中的值
function FunStringExist($StrFiltrate,$ArrFiltrate){
	foreach ($ArrFiltrate as $key=>$value){
		if (eregi($value,$StrFiltrate)){
			return true;
		}
	}
	return false;
}

//合并$_POST 和 $_GET
if(function_exists(array_merge)){
	//$ArrPostAndGet=array_merge($HTTP_POST_VARS,$HTTP_GET_VARS);
	$ArrPostAndGet=array_merge($_POST, $_GET);
}else{
	//foreach($HTTP_POST_VARS as $key=>$value){
	foreach($_POST as $key=>$value){
		$ArrPostAndGet[]=$value;
	}
	//foreach($HTTP_GET_VARS as $key=>$value){
	foreach($_GET as $key=>$value){
		$ArrPostAndGet[]=$value;
	}
}

//验证开始
foreach($ArrPostAndGet as $key=>$value){
	if (FunStringExist($value,$ArrFiltrate)){
		echo "<script language=\"javascript\">alert(\"非法字符\");</script>";
		if (empty($StrGoUrl)){
			echo "<script language=\"javascript\">history.go(-1);</script>";
		}else{
			echo "<script language=\"javascript\">window.location=\"".$StrGoUrl."\";</script>";
		}
		exit;
	}
}

?>
