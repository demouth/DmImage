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
$color = Dm_Color::argb(1, 255, 0, 0); //RGB指定
$color->v *= 0.5; //明度を半分に（HSV）
$image = new Dm_Image(400,300, $color->toInt());
$image->display();
exit;
