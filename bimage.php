
<?php


//bimage.php

/* 
函數說明 
$chartdata:資料，是陣列元素 
$chartfont:字型大小 
$chartdiameter:決定餅的大小（要看你餓不餓了，呵呵） 
$chartlabel:標題，也是陣列元素 
$colorslice:顏色陣列，例如$tmp=array255,255,255);$colorslic=array($tmp); 
$colorborder:邊框顏色，陣列 
$colortext :文本顏色,陣列 
$colorbody:背景顏色，陣列 
$file:輸出圖片檔案名*/ 
function bimage($chartdata, 
$chartfont, 
$chartdiameter , 
$chartlabel , 
$colorslice, 
$colorbody , 
$colorborder, 
$colortext , 
$file 
) 
{ 
$chartdiameter=150; 

$chartfontheight=imagefontheight($chartfont); 
$d1=10;$d2=20;$d3=30;$d4=40;$d5=50; 
$chartdata=array($d1,$d2,$d3,$d4,$d5); 
$chartlabel=array("D1","D2","D3","D4","D5"); 
$chartwidth=$chartdiameter+20; 
$chartheight=$chartdiameter+20+(($chartfontheight+2)*count($chartdata)); 
header("content-type:image/gif"); 
$image=imagecreate($chartwidth,$chartheight); 
$colorbody =imagecolorallocate ($image,$colorbody[0],$colorbody[1],$colorbody[2]); 
$colortext =imagecolorallocate ($image,$colortext[0],$colortext[1],$colortext[2]); 
$colorborder =imagecolorallocate ($image,$colorborder[0],$colorborder[1],$colorborder[2]); 
for ($i=0;$i<count($colorslice); ++$i)
{ 
$t=imagecolorallocate($image,$colorslice[$i][0],$colorslice[$i][1],$colorslice[$i][2]);
$colorslice[$i]=$t; 
} 
for($i=0;$i<count($chartdata); ++$i) 
{ 
$charttotal+=$chartdata[$i]; 
} 
$chartcenterx=$chartdiameter/2+10; 
$chartcentery=$chartdiameter/2+10; 
$degrees=0; 
for($i=0;$i<count($chartdata); ++$i) 
{ 
$startdegrees=round($degrees); 
$degrees+=(($chartdata[$i]/$charttotal)*360); 
$enddegrees=round($degrees); 
$currentcolor=$colorslice[$i%(count($colorslice))]; 
imagearc($image , 
$chartcenterx, 
$chartcentery, 
$chartdiameter, 
$chartdiameter, 
$startdegrees, 
$enddegrees, 
$currentcolor); 
list($arcx,$arcy)=circle_point($startdegrees,$chartdiameter); 
imageline($image, 
$chartcenterx, 
$chartcentery, 
floor($chartcenterx+$arcx), 
floor($chartcentery+$arcy), 
$currentcolor ); 
list($arcx,$arcy)=circle_point($enddegrees,$chartdiameter); 

imageline($image, 
$chartcenterx, 
$chartcentery, 
ceil($chartcenterx+$arcx), 
ceil($chartcentery +$arcy), 
$currentcolor); 

$midpoint=round((($enddegrees-$startdegrees)/2)+$startdegrees); 
list($arcx,$arcy)= circle_point ( $midpoint, $chartdiameter/2); 
imagefilltoborder($image, 
floor($chartcenterx+$arcx), 
floor($chartcentery+$arcy), 
$currentcolor, 
$currentcolor); 
} 
imagearc($image, 
$chartcenterx, 
$chartcentery, 
$chartdiameter, 
$chartdiameter, 
0,360, 
$colorborder); 
imagefilltoborder ($image, 
floor($chartcenterx +( $chartdiameter /2)+2), 
$chartcentery , 
$colorborder, 
$colorborder ); 
for ($i=0;$i<count($colorslice); ++$i)  
{ 
$currentcolor=$colorslice[$i%(count($colorslice))]; 
$liney=$chartdiameter+20+($i*($chartfontheight+2)); 
imagerectangle ($image, 
10, 
$liney, 
20+$chartfontheight, 
$liney+$chartfontheight, 
$colorbody); 
imagefilltoborder($image, 
12, 
$liney+2, 
$colorbody, 
$currentcolor); 
imagestring($image, 
$chartfont, 
40+$chartfontheight, 
$liney, 
"$chartlabel[$i]:$chartdata[$i]", 
$colortext); 

} 

imagegif ($image,$file); 

} 

function radians($degrees) 
{ 
return($degrees*(pi()/180.0)); 
} 
function circle_point($degrees,$diameter) 
{ 
$x=cos(radians($degrees))*($diameter/2); 
$y=sin(radians($degrees))*($diameter/2); 
return (array($x,$y)); 
} 

?>