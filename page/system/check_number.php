<?php
session_start();

//��PHP��GD���������֤��Ч��

//�ȳ����������ٰ����ɵ���֤�����ȥ
$img_height = 100;    //�ȶ���ͼƬ�ĳ�����
$img_width = 30;

//if($_GET["act"]== "init"){

    //srand(microtime() * 100000);//PHP420��srand���Ǳ����
    for($Tmpa=0; $Tmpa<4; $Tmpa++){
        $nmsg .= dechex(rand(0,15)); 
    }//by sports98


    $_SESSION['login_check_number'] = $nmsg;  

    //$HTTP_SESSION_VARS[login_check_number] = strval(mt_rand("1111","9999"));    //����4λ�������������session��
    //˭�����²��䣬����ͬʱ������ĸ�����ְ�����----��sports98�����

    $aimg = imageCreate($img_height, $img_width);    //����ͼƬ
    ImageColorAllocate($aimg, 235, 235, 235);            //ͼƬ��ɫ��ImageColorAllocate��1�ζ�����ɫPHP����Ϊ�ǵ�ɫ��
    $black = ImageColorAllocate($aimg, 0, 0, 0);        //������Ҫ�ĺ�ɫ
    ImageRectangle($aimg, 0, 0, $img_height-1, $img_width-1, $black);//�ȳ�һ��ɫ�ľ��ΰ�ͼƬ��Χ

    //���������ѩ�������ˣ���ʵ������ͼƬ������һЩ����
    for ($i=1; $i<=100; $i++) {    //����100��������
        imageString($aimg, 1, mt_rand(1,$img_height-7), mt_rand(1,$img_width-7), "*", 
		    imageColorAllocate($aimg, mt_rand(200,255), mt_rand(200,255), mt_rand(200,255))
			);
        // ���������˰ɣ���ʵҲ����ѩ�����������ɣ��Ŷ��ѡ�Ϊ��ʹ���ǿ�����"�������¡�5��6ɫ"��
		// �͵���1��1���������ǵ�ʱ�������ǵ�λ�á���ɫ��������С�����������rand()��mt_rand��������ɡ�
    }

    //���������˱��������ھ͸ð��Ѿ����ɵ�������������ˡ�����������࣬
	// �����1��1���طţ�ͬʱ�����ǵĴ�С��λ�á���ɫ���ó������~~
    //Ϊ�������ڱ������������ɫ������200������Ĳ�С��200
    for ($i=0; $i<strlen($_SESSION['login_check_number']); $i++){
        imageString($aimg, mt_rand(30,50), $i*$img_height/4+mt_rand(1,10), 
		    mt_rand(1,$img_width/2), $_SESSION['login_check_number'][$i], 
			imageColorAllocate($aimg, mt_rand(0,100), mt_rand(0,150), mt_rand(0,200))
			);
    }
	
    Header("Content-type: image/png");    //����������������������ͼƬ������Ҫ��������ʾ
    ImagePng($aimg);                    //����png��ʽ�������ٺ�Ч��������µ������
    ImageDestroy($aimg);
//}

?>
