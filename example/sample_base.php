<?php

ini_set('display_errors', 'on');
error_reporting(-1);
set_error_handler('onError');

function onError($errno, $errstr, $errfile, $errline){
	var_dump($errno, $errstr, $errfile, $errline);exit;
}


/*
//http://nucleus.yaibeen.com/item30.html
    $buf_info = "";
    $arrInfo = gd_info();
    foreach ($arrInfo as $idx => $buf) {
        if (is_bool($buf)) {
            $buf_info .= "<p>" .htmlspecialchars("[$idx]  ==>> " .($buf ? "OK" : "No Support")) ."</p>";
        } else {
            $buf_info .= "<p>" .htmlspecialchars("[$idx]  ==>> $buf") ."</p>";
        }
    }
    echo $buf_info;
exit;
*/


/*


$size = 300;
$image=imagecreatetruecolor($size, $size);

imagesavealpha($image,true);

// 白い背景で黒いふちどりにします
$back = imagecolorallocatealpha($image, 255, 255, 255,50);
$border = imagecolorallocate($image, 0, 0, 0);
imagefilledrectangle($image, 0, 0, $size - 1, $size - 1, $back);
imagerectangle($image, 0, 0, $size - 1, $size - 1, $border);

$yellow_x = 100;
$yellow_y = 75;
$red_x    = 120;
$red_y    = 165;
$blue_x   = 187;
$blue_y   = 125;
$radius   = 150;

// alpha 値を指定して色を作成します
$yellow = imagecolorallocatealpha($image, 255, 255, 0, 75);
$red    = imagecolorallocatealpha($image, 255, 0, 0, 75);
$blue   = imagecolorallocatealpha($image, 0, 0, 255, 75);

// 3つの重なる円を描きます
imagefilledellipse($image, $yellow_x, $yellow_y, $radius, $radius, $yellow);
imagefilledellipse($image, $red_x, $red_y, $radius, $radius, $red);
imagefilledellipse($image, $blue_x, $blue_y, $radius, $radius, $blue);



$image2=imagecreatetruecolor($size, $size);
imagefilledrectangle($image2, 0, 0, $size - 1, $size - 1, $back);
imagefilledellipse($image2, $blue_x, $blue_y, $radius, $radius, $blue);


imagecopymerge($image, $image2, 10, 10, 0, 0, 200, 147, 75);


// 正しいヘッダを出力するのを忘れないように!
header('Content-Type: image/png');

// 最後に、結果を出力します
imagepng($image);
imagedestroy($image);


exit;
*/



/*
$size = 300;
$image=imagecreatetruecolor($size, $size);

// 白い背景で黒いふちどりにします
$back = imagecolorallocatealpha($image, 255, 10, 255 , 30);
$border = imagecolorallocatealpha($image, 0, 100, 200, 10);
imagefilledrectangle($image, 0, 0, 100, 150, $back);
imagerectangle($image, 0, 0, $size - 1, $size - 1, $border);


$yellow_x = 100;
$yellow_y = 75;
$red_x    = 120;
$red_y    = 165;
$blue_x   = 187;
$blue_y   = 125;
$radius   = 150;

// alpha 値を指定して色を作成します
$yellow = imagecolorallocatealpha($image, 255, 255, 0, 75);
$red    = imagecolorallocatealpha($image, 255, 0, 0, 75);
$blue   = imagecolorallocatealpha($image, 0, 0, 255, 75);

// 3つの重なる円を描きます
imagefilledellipse($image, $yellow_x, $yellow_y, $radius, $radius, $yellow);
imagefilledellipse($image, $red_x, $red_y, $radius, $radius, $red);
imagefilledellipse($image, $blue_x, $blue_y, $radius, $radius, $blue);



$values = array(
            40,  50,  // Point 1 (x, y)
            20,  240, // Point 2 (x, y)
            60,  60,  // Point 3 (x, y)
            240, 20,  // Point 4 (x, y)
            50,  40,  // Point 5 (x, y)
            10,  10   // Point 6 (x, y)
            );

// 多角形を描画します
imagesetthickness($image,10);
imagefilledpolygon($image, $values, 6, $blue);


imagesetthickness($image,10);
imageline($image, 10, 100, 50, 180, $border);



// 正しいヘッダを出力するのを忘れないように!
header('Content-Type: image/png');

// 最後に、結果を出力します
imagepng($image);
imagedestroy($image);
exit;

*/











require_once '../src/Dm/Color.php';
require_once '../src/Dm/Image.php';
require_once '../src/Dm/Image/Graphic/Interface.php';
require_once '../src/Dm/Image/Graphic/Text.php';
require_once '../src/Dm/Image/Graphic/Shape.php';
require_once '../src/Dm/Image/File.php';
require_once '../src/Dm/Image/Filter/Abstract.php';
require_once '../src/Dm/Image/Filter/Fit.php';
require_once '../src/Dm/Image/Filter/Crop.php';
require_once '../src/Dm/Image/Filter/InstagramNormal.php';
require_once '../src/Dm/Image/Filter/InstagramLoFi.php';
require_once '../src/Dm/Image/Filter/InstagramWalden.php';
require_once '../src/Dm/Image/Filter/InstagramToaster.php';

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
	->drawEllipse(20,50,50,80)
	->drawRect(10,50,100,120)
	->beginLineFill()
	->lineTo(150, 110)
	->lineTo(200, 160)
	->lineTo(230, 120)
	->endLineFill()
;

$image
	->textGraphics
	->setFontSize(20)
	->setColor(0x66FF0000)
	->textTo(50, 100, "abcあいう",90);





$image2 = new Dm_Image(200,200, 0x00FFFFFF);
$image2
	->graphics
	->lineStyle(5,0x30FF0000)
	->fillStyle(0x6033FF00)
	->moveTo(20, 40)
	->lineTo(150, 110)
	->lineTo(200, 160)
	->lineTo(230, 120)
;

$filter = new Dm_Image_Filter_InstagramLoFi(300,1);
$filter2 = new Dm_Image_Filter_InstagramWalden(300,2);
$filter3 = new Dm_Image_Filter_InstagramToaster(300);

$image = new Dm_Image_File('/home/www/demouth/www/docroot/git/dmpiechart/src/lib/DmImage/02.jpeg');
$image->applyFilter($filter);

?><img src="<?=$image->toDataSchemeURI()?>" /><?php

$image2 = new Dm_Image_File('/home/www/demouth/www/docroot/git/dmpiechart/src/lib/DmImage/02.jpeg');
$image2->applyFilter($filter2);

?><img src="<?=$image2->toDataSchemeURI()?>" /><?php



?><img src="../src/lib/DmImage/02.jpeg" style="width:300px;" /><?php

$filter = new Dm_Image_Filter_InstagramLoFi(300,3);
$image3 = new Dm_Image_File('/home/www/demouth/www/docroot/git/dmpiechart/src/lib/DmImage/lena_std.png');
$image3->applyFilter($filter);

?><br><img src="<?=$image3->toDataSchemeURI()?>" /><?php

$filter2 = new Dm_Image_Filter_InstagramWalden(300,0);
$image4 = new Dm_Image_File('/home/www/demouth/www/docroot/git/dmpiechart/src/lib/DmImage/lena_std.png');
$image4->applyFilter($filter2);

?><img src="<?=$image4->toDataSchemeURI()?>" /><?php



?><img src="./src/lib/DmImage/lena_std.png" style="width:300px;" /><?php

//$image->draw($image2,190,160);
//$image->display();


exit;



$color  = DmColor::rgb(155,200,60);
$color2 = DmColor::rgb(155,200,60);
$color2->v($color2->v * 0.8);

print $color2->hsv;

$color = new DmColor();
$color->r(255)->g(100)->b(50);
echo $color->rgb; // 100




?><!DOCTYPE html>
<head>
	<meta charset="UTF-8" />
</head>
<body>
	
	<span style="color:#<?=$color->rgb?>">hdfiajsdkfajdsklfjadlfj</span>
	
	<span style="color:#<?=$color2->rgb?>">hdfiajsdkfajdsklfjadlfj</span>
	
	
</body>
</html>