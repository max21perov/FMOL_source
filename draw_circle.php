<?php
//�@��һ���{�õ����� 
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
var $x=200; //=>�A��x �S 
var $y=200; //=>�A��y �S 
var $x1=200;  //�ڈA�ϵ�һ�c��x �S��ʼ 
var $y1=0; //�ڈA�ϵ�һ�c��y �S��ʼ 
var $len=200; //=>�돽 
var $sc=0; //=>��ʼ�� 
var $ec=0; //=>�Y���� 
var $times; //ӛ䛮����l�ĴΔ� 
var $white; //��ɫ(����ɫ) 
var $black; //��ɫ(���l�ɫ) 
//var $rec_point; //ӛ���һ�εĽǶ� 
function Draw(){ //��ʼ����� 
$this->im = ImageCreate(400,400);  //����400*400���صĈD�� 
ImageColorAllocate ($this->im, 255, 255, 255); // ��ɫ����ɫ 
$this->black = ImageColorAllocate ($this->im, 0, 0, 0); // ��ɫ 
imagearc ($this->im, $this->x,$this->y,2*$this->len,2*$this->len,0,360,$this->black);  //�����A�� 
//imageline($this->im,$this->x,$this->y,$this->x+$this->x1,$this->y+$this->y1,$this->black); //line 1 
$this->times++; 
//$this->rec_point=0; //����Ƕ� 
} 

function DrawLine($cornor){ //����ֱ�� �K��ɫ$sc =>��ʼ�ǣ�$ec =>�Y���� 
//$cornor=$ec-$sc; //Ӌ��Ƕ� 
//�Дྀ�Ε�������һ������ һ�����������Ă�����   
if($cornor < 90){//��һ������ 
$x1=cos($cornor)*$this->len; 
$y1=sin($cornor)*$this->len; 
imageline($this->im,$this->x,$this->y,$x1+$this->x,$y1+$this->y,$this->black); 
}elseif(($cornor > 90) && ($cornor < 180)){ ////�ڶ������� @ ��� 90��С� 180 
$x1=sin($cornor-90)*$this->len; 
$y1=cos($cornor-90)*$this->len; 
imageline($this->im,$this->x,$this->y,$this->x-$x1,$y1+$this->y,$this->black); 
}elseif(($cornor > 180) && ($cornor < 270)){////���������� @ ��� 180��С� 270 
$x1=cos($cornor-180)*$this->len; 
$y1=sin($cornor-180)*$this->len; 
imageline($this->im,$this->x,$this->y,$this->x-$x1,$this->y-$y1,$this->black); 
}elseif(($cornor > 270) && ($cornor < 360)){////���Ă����� @ ��� 270��С� 360 
$x1=sin($cornor-270)*$this->len; 
$y1=cos($cornor-270)*$this->len; 
imageline($this->im,$this->x,$this->y,$this->x+$x1,$this->y-$y1,$this->black); 
} 
$this->times++; 
//$this->rec_point=$ec; //ӛ��@һ�εĽǶ� 
} 

function printDraw(){  //�шD���͵�ΞĻ�� 
imagejpeg($this->im); 
imagedestroy ($this->im);//ጷ��κκ͈D�� im�P��ӛ���w 
} 
function saveDraw($filename){  //�шD�δ�ən�� 
$id=fopen($filename,wb); //�_���n�� 
imagejpeg($this->im,$filename);  //����D�� 
fclose($id); //�P�n 
//imagedestroy ($this->$im);//ጷ��κκ͈D�� im�P��ӛ���w 
} 
}

$obj = & new Draw();
$obj->printDraw();
?>
