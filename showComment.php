<?php




/* ���ȴ���һ�����ţ��������ɫ */

$square = new SWFShape();

$sqfill = $square->addFill(0, 0, 0xff);

$square->setRightFill($sqfill); 

$square->movePenTo(-250,-250);

$square->drawLineTo(250,-250);

$square->drawLineTo(250,250);

$square->drawLineTo(-250,250);

$square->drawLineTo(-250,-250); 



/* �ڶ���������ʹ������ķ��ţ������һЩ�ű� */

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



/* ���洴����ť */



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

$b->addAction(new SWFAction("setTarget(��/box��); gotoandplay(2);"), SWFBUTTON_MOUSEDOWN);



/* ���洴�����������������ķ��źͰ�ť */



$m = new SWFMovie();

$m->setDimension(4000,3000);



$i = $m->add($sqclip);

$i->setDepth(3);

$i->moveTo(1650, 400);

$i->setName("box");



$i = $m->add($b);

$i->setDepth(2);

$i->moveTo(1400,900);



/* ������ǰ������������� */



header(��Content-type: application/x-shockwave-flash��);

$m->output();
  
?>

