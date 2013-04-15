<?php

//------------------------------------
// DmImageライブラリを読み込む
//------------------------------------
require_once '../../SplClassLoader.php';

// SplClassLoaderを使用する場合
$DmDirPath = '../../../src/';
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

define('W', 640);
define('H', 360);
define('FONT_SIZE', 11);
define('FRAME', 200);

$STR = array('感','憂','位','中','上','医','受','柄','下','嫁','木','区','毛','弧');
$letters = array_fill(0,((int)W/FONT_SIZE),0);
$image = new Dm_Image(W,H,0xFF000000);
$graphics = $image->graphics;
$textGraphics = $image->textGraphics;
$graphics->fillStyle(0x22000000);
for($i=0;$i<FRAME;$i++) draw();
$image->display();

function draw()
{
	global $STR,$letters,$graphics,$textGraphics;
	
	$graphics->drawRect(0, 0, W, H);
	$l = count($letters);
	for($i=0;$i<$l;$i++){
		$str = $STR[rand(0,count($STR)-1)];
		$y = $letters[$i];
		$textGraphics
			->setColor(0xFF00FF00)
			->setFontSize(FONT_SIZE-3)
			->textTo($i*FONT_SIZE, $y, $str);
		$letters[$i] = ($y > H + rand(0,1e3)) ? 0 : $y+FONT_SIZE;
	}
}
