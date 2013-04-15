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
require_once 'SimpleGifMerge.php';

define('DIR_PATH', dirname(__FILE__).DIRECTORY_SEPARATOR.'img'.DIRECTORY_SEPARATOR);
define('W', 640);
define('H', 360);
define('C', 200);
define('D', 50);
define('FRAME', 150);

$merge = new SimpleGifMerge(DIR_PATH);
$merge->clear();

$nodeList = array();
for ($i=0; $i < C; $i++) { 
	$nodeList[] = array(
		rand(-20,W+20),
		rand(-20,H+20),
		0,
		rand(-10,10)*0.5,
		rand(-10,10)*0.5
	);
}

for ($i=0; $i < FRAME; $i++) { 
	draw($nodeList, $i);
}

$merge->merge();

function draw(&$nodeList,$c){
	$image = new Dm_Image(W,H,0xFF000000);
	$graphics = $image->graphics;
	$graphics
		->lineStyle(0)
		->fillStyle(0x66FFFFFF);
	
	for ($i=0; $i < C; $i++) {
		$node = &$nodeList[$i];
		$node[2] = 0;
	}
	
	for ($i=0; $i < C; $i++) {
		$node = &$nodeList[$i];
		
		for ($j=$i; $j < C; $j++) {
			if($i==$j)continue;
			$nNode = &$nodeList[$j];
			$x = $node[0] - $nNode[0];
			$y = $node[1] - $nNode[1];
			$diff = sqrt($x*$x+$y*$y);
			if($diff<D){
				$node[2] += 1;
				$nNode[2] += 1;
				$graphics
					->lineStyle(
						min(10*(D-$diff)/D,4),
						Dm_Color::argb((D-$diff)/D*0.6+0.2,255,255,255)->toInt()
					)
					->moveTo($node[0], $node[1])
					->lineTo($nNode[0], $nNode[1])
				;
			}
		}
	}
	
	$graphics->lineStyle(0,0x11FFFFFF);
	for ($i=0; $i < C; $i++) {
		$node = $nodeList[$i];
		$graphics->drawCircle($node[0], $node[1], 2+$node[2]*$node[2]*0.2);
	}
	
	for ($i=0; $i < C; $i++) {
		$node = &$nodeList[$i];
		$node[0] += $node[3];
		$node[1] += $node[4];
	}
	
	$count = sprintf('%05d', $c);
	$image->saveTo(DIR_PATH.'img'.$count.'.gif','gif');
}
