<?php




/* 首先创建一个符号，并填充颜色 */

$square = new SWFShape();

$sqfill = $square->addFill(0, 0, 0xff);

$square->setRightFill($sqfill); 

$square->movePenTo(-250,-250);

$square->drawLineTo(250,-250);

$square->drawLineTo(250,250);

$square->drawLineTo(-250,250);

$square->drawLineTo(-250,-250); 



/* 在动画剪辑里使用上面的符号，并添加一些脚本 */

$sqclip = new SWFSprite();

$i = $sqclip->add($square);

$i->setDepth(1);

$sqclip->setframes(25);

$sqclip->add(new SWFAction("stop();")); 

$sqclip->nextFrame();

$sqclip->add(new SWFAction("play();")); 

for($n=0; $n<24; $n++) {

$i->rotate(-15);

$sqclip->nextFrame();

}



/* 下面创建按钮 */



function rect($r, $g, $b) {

$s = new SWFShape();

$s->setRightFill($s->addFill($r, $g, $b));

$s->drawLine(500,0);

$s->drawLine(0,500);

$s->drawLine(-500,0);

$s->drawLine(0,-500);

return $s;

}



$b = new SWFButton();

$b->addShape(rect(0xff, 0, 0), SWFBUTTON_UP | SWFBUTTON_HIT);

$b->addShape(rect(0, 0xff, 0), SWFBUTTON_OVER);

$b->addShape(rect(0, 0, 0xff), SWFBUTTON_DOWN); 

$b->addAction(new SWFAction("setTarget(’/box’); gotoandplay(2);"), SWFBUTTON_MOUSEDOWN);



/* 下面创建动画，并添加上面的符号和按钮 */



$m = new SWFMovie();

$m->setDimension(4000,3000);



$i = $m->add($sqclip);

$i->setDepth(3);

$i->moveTo(1650, 400);

$i->setName("box");



$i = $m->add($b);

$i->setDepth(2);

$i->moveTo(1400,900);



/* 最后，我们把它输出到浏览器 */



header(’Content-type: application/x-shockwave-flash’);

$m->output();
  
?>

