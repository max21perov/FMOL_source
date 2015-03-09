<?php


//----------------------------------------------------------------------------	
// functions
//----------------------------------------------------------------------------


/* 竖柱形图 */
function imageColumnS($img_width, $img_height, $item_arr, $border)
{     

	Header( "Content-type: image/gif");  
	//建立画布大小  
	$image = ImageCreate($img_width, $img_height); 
	$background=ImageColorAllocate($image, 200, 200, 200);  
	//ImageFilledRectangle($image,0,0,$img_width,$img_height,$background);  
	Imagecolortransparent($image,$background); 
	
	$xx = 0; //$border*2;  
	//画柱形图  
	$num = count($item_arr);  
	for ($i=0; $i<$num; ++$i){ 
		
		$percent = $item_arr[$i]["percent"];
		$bg_color_str = $item_arr[$i]["bg_color"];
		$font_color_str = $item_arr[$i]["font_color"];
		
		$bg_color = ImageColorAllocate($image, hexdec(substr($bg_color_str, 1, 2)), hexdec(substr($bg_color_str, 3, 2)), hexdec(substr($bg_color_str, 5, 2)));
		$font_color = ImageColorAllocate($image, hexdec(substr($font_color_str, 1, 2)), hexdec(substr($font_color_str, 3, 2)), hexdec(substr($font_color_str, 5, 2)));
		//柱形高度  
		$height=($img_height - 15)-($img_height - 15)*($percent/100);  
		ImageFilledRectangle($image, $xx, $height, $xx+$border, $img_height - 15, $bg_color);  
		ImageString($image, 3, $xx + $border / 4, $img_height - 12, $percent."%", $font_color);  
		
		//用于间隔  
		$xx=$xx + 10 + $border;  
	}  
	
	
	ImageGIF($image);  
	ImageDestroy($image); 
	
	
	
}

?>