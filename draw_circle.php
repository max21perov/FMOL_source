<?php
//這是一個調用的例子 
/*
require_once("bimage.php"); 

$chartdiameter=250; 
$chartfont=5; 
$d1=10;$d2=20;$d3=30;$d4=40;$d5=50; 
$chartdata=array($d1,$d2,$d3,$d4,$d5); 
$chartlabel=array("D1","D2","D3","D4","D5"); 

$colorbody=array(0xff,0xff,0xff); 
$colorborder=array(0x00,0x00,0x00); 
$colortext=array(0xff,0xff,0xff); 

$color1=array(0xff,0x00,0x00); 
$color2=array(0x00,0xff,0x00); 
$color3=array(0x00,0x00,0xff); 
$color4=array(0xff,0xff,0x00); 
$color5=array(0xff,0x00,0xff); 
$colorslice=array($color1 ,$color2,$color3,$color4,$color5); 
$file="tj.gif"; 
bimage($chartdata, 
$chartfont, 
$chartdiameter , 
$chartlabel , 
$colorslice, 
$colorbody , 
$colorborder, 
$colortext , 
$file );
*/

class Draw { 
var $im; 
var $x=200; //=>圓心x 軸 
var $y=200; //=>圓心y 軸 
var $x1=200;  //在圓上的一點的x 軸起始 
var $y1=0; //在圓上的一點的y 軸起始 
var $len=200; //=>半徑 
var $sc=0; //=>起始角 
var $ec=0; //=>結束角 
var $times; //記錄畫線條的次數 
var $white; //白色(背景色) 
var $black; //黑色(線條顏色) 
//var $rec_point; //記錄上一次的角度 
function Draw(){ //初始化物件 
$this->im = ImageCreate(400,400);  //建立400*400像素的圖形 
ImageColorAllocate ($this->im, 255, 255, 255); // 白色背景色 
$this->black = ImageColorAllocate ($this->im, 0, 0, 0); // 黑色 
imagearc ($this->im, $this->x,$this->y,2*$this->len,2*$this->len,0,360,$this->black);  //畫出圓形 
//imageline($this->im,$this->x,$this->y,$this->x+$this->x1,$this->y+$this->y1,$this->black); //line 1 
$this->times++; 
//$this->rec_point=0; //在零角度 
} 

function DrawLine($cornor){ //畫出直線 並填色$sc =>起始角，$ec =>結束角 
//$cornor=$ec-$sc; //計算角度 
//判斷線段會落在那一個象限 一、二、三、四個象限   
if($cornor < 90){//第一個象限 
$x1=cos($cornor)*$this->len; 
$y1=sin($cornor)*$this->len; 
imageline($this->im,$this->x,$this->y,$x1+$this->x,$y1+$this->y,$this->black); 
}elseif(($cornor > 90) && ($cornor < 180)){ ////第二個象限 @ 大於 90，小於 180 
$x1=sin($cornor-90)*$this->len; 
$y1=cos($cornor-90)*$this->len; 
imageline($this->im,$this->x,$this->y,$this->x-$x1,$y1+$this->y,$this->black); 
}elseif(($cornor > 180) && ($cornor < 270)){////第三個象限 @ 大於 180，小於 270 
$x1=cos($cornor-180)*$this->len; 
$y1=sin($cornor-180)*$this->len; 
imageline($this->im,$this->x,$this->y,$this->x-$x1,$this->y-$y1,$this->black); 
}elseif(($cornor > 270) && ($cornor < 360)){////第四個象限 @ 大於 270，小於 360 
$x1=sin($cornor-270)*$this->len; 
$y1=cos($cornor-270)*$this->len; 
imageline($this->im,$this->x,$this->y,$this->x+$x1,$this->y-$y1,$this->black); 
} 
$this->times++; 
//$this->rec_point=$ec; //記錄這一次的角度 
} 

function printDraw(){  //把圖形送到螢幕上 
imagejpeg($this->im); 
imagedestroy ($this->im);//釋放任何和圖形 im關聯的記憶體 
} 
function saveDraw($filename){  //把圖形存成檔案 
$id=fopen($filename,wb); //開啟檔案 
imagejpeg($this->im,$filename);  //寫入圖形 
fclose($id); //關檔 
//imagedestroy ($this->$im);//釋放任何和圖形 im關聯的記憶體 
} 
}

$obj = & new Draw();
$obj->printDraw();
?>
