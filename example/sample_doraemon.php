<?php

//------------------------------------
// DmImageライブラリを読み込む
//------------------------------------
require_once 'SplClassLoader.php';

// SplClassLoaderを使用する場合
$DmDirPath = '../src/';
$classLoader = new SplClassLoader(null, $DmDirPath);
$classLoader->register();

/*
// SplClassLoaderを使用せず、1ファイル毎読み込む場合
require_once $DmDirPath.'Dm/Color.php';
require_once $DmDirPath.'Dm/Image.php';
require_once $DmDirPath.'Dm/Image/Graphic/Interface.php';
require_once $DmDirPath.'Dm/Image/Graphic/Text.php';
require_once $DmDirPath.'Dm/Image/Graphic/Shape.php';
require_once $DmDirPath.'Dm/Image/File.php';
require_once $DmDirPath.'Dm/Image/Filter/Abstract.php';
require_once $DmDirPath.'Dm/Image/Filter/Fit.php';
require_once $DmDirPath.'Dm/Image/Filter/Crop.php';
require_once $DmDirPath.'Dm/Image/Filter/InstagramNormal.php';
require_once $DmDirPath.'Dm/Image/Filter/InstagramLoFi.php';
require_once $DmDirPath.'Dm/Image/Filter/InstagramWalden.php';
require_once $DmDirPath.'Dm/Image/Filter/InstagramToaster.php';
*/


//------------------------------------
// エラー出力（ここは必要に応じて）
//------------------------------------
ini_set('display_errors', 'on');
error_reporting(-1);
set_error_handler('onError');
function onError($errno, $errstr, $errfile, $errline){
	var_dump($errno, $errstr, $errfile, $errline);
	exit;
}


//------------------------------------
// サンプル
//------------------------------------
$colorBody = 0xFF0099FF;
$colorLine = 0xFF003399;
$colorWhite = 0xFFFFFFFF;
$colorEye  = 0xFF000000;
$colorRed  = 0xFFFF0000;
$colorYellow = 0xFFFFFF00;
$thickness = 3;
$image = new Dm_Image(500,600,0xFF66CCFF);
$image->graphics
	
	//体
	->fillStyle($colorBody)
	->lineStyle($thickness,$colorLine)
	->beginLineFill()
	->moveTo(260, 440)
	->lineTo(260, 450)
	->lineTo(360, 450)
	->lineTo(360, 370)
	->lineTo(400, 410)
	->lineTo(440, 370)
	->lineTo(360, 290)
	->lineTo(140, 290)
	->lineTo(60 , 370)
	->lineTo(100, 410)
	->lineTo(140, 370)
	->lineTo(140, 450)
	->lineTo(240, 450)
	->lineTo(240, 440)
	->endLineFill()
	
	//手
	->fillStyle($colorWhite)
	->drawCircle(410, 380, 35)
	->drawCircle(90 , 380, 35)
	
	//ポケット
	->drawCircle(250, 330, 85)
	->drawPie(250, 350, 120, 100, 0, 180)
	->moveTo(190, 350)
	->lineTo(310, 350)
	
	//足
	->drawRect(260, 450, 100, 29)
	->drawPie(359, 465, 20, 30, -90, 90)
	->drawPie(262, 465, 20, 30, 90, -90)
	->drawRect(140, 450, 100, 29)
	->drawPie(239, 465, 20, 30, -90, 90)
	->drawPie(142, 465, 20, 30, 90, -90)
	
	//頭
	->fillStyle($colorBody)
	->drawEllipse(250, 150, 350, 280)
	->fillStyle($colorWhite)
	->drawEllipse(250, 190, 280, 190)
	
	//目
	->drawEllipse(290, 100, 80, 90)
	->drawEllipse(210, 100, 80, 90)
	->fillStyle($colorEye)
	->drawCircle(270, 110, 10)
	->drawCircle(230, 110, 10)
	
	//鼻・口・ひげ
	->fillStyle($colorRed)
	->drawCircle(250, 140, 15)
	->lineStyle(0)
	->fillStyle($colorWhite)
	->drawCircle(255, 135, 5)
	->lineStyle($thickness,$colorLine)
	->moveTo(250, 153)
	->lineTo(250, 250)
	->fillStyle(0)
	->drawPie(250, 200, 160, 100, 15, 165)
	->moveTo(300, 170)
	->lineTo(350, 150)
	->moveTo(300, 180)
	->lineTo(350, 180)
	->moveTo(300, 190)
	->lineTo(350, 210)
	->moveTo(200, 170)
	->lineTo(150, 150)
	->moveTo(200, 180)
	->lineTo(150, 180)
	->moveTo(200, 190)
	->lineTo(150, 210)
	
	//首輪
	->fillStyle($colorRed)
	->drawRect(141, 260, 222, 30)
	->drawPie(360, 275, 20, 30, -90,  90)
	->drawPie(142, 275, 20, 30,  90, -90)
	
	//鈴
	->fillStyle($colorYellow)
	->drawCircle(250, 290, 20)
	->moveTo(232, 280)
	->lineTo(268, 280)
	->moveTo(230, 285)
	->lineTo(270, 285)
	->moveTo(250, 300)
	->lineTo(250, 310)
	->drawCircle(250, 298, 5)
;
$image->textGraphics
	->setFontSize(70)
//	->setFontFile('font/doraemoji/doraemoji.ttf') //フォント
	->setColor($colorLine)
	->textTo(20, 580, 'ドラえもん')
;

$image->display();
exit;

