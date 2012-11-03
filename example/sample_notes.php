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
$image = new Dm_Image(400,400, 0x002033FF);
$image
	->graphics
	->lineStyle(5,0x30FF0000)
	->fillStyle(0x6033FF00)
	->moveTo(20, 40)
	->beginLineFill()
	->lineTo(100, 110)
	->lineTo(150, 160)
	->lineTo(180, 120)
	->endLineFill()
	->drawCircle(100,100,150)
	->drawPie(150,60,150,100,0,360)
	->drawEllipse(20,50,50,80) //半透明色の場合色が濃くなってしまう
	->drawRect(10,50,100,120)
	->beginLineFill()
	->lineTo(150, 110)
	->lineTo(200, 160)
	->lineTo(230, 120)
	->endLineFill()
;

$image->display();
exit;


?><!DOCTYPE html>
<head>
	<meta charset="UTF-8" />
</head>
<body>
	
	<span style="color:#<?=$color->rgb?>">hdfiajsdkfajdsklfjadlfj</span>
	
	<span style="color:#<?=$color2->rgb?>">hdfiajsdkfajdsklfjadlfj</span>
	
	
</body>
</html>