<?php

require_once dirname(__FILE__).'/DmImage.php';

/**
 * DmImageLoader
 * 画像を表すクラス。
 * 
 * @example
 * 
 * @version 1.0.0
 * @author demouth.net
 */
class DmImageFile extends DmImage
{
	
	/**
	 * コンストラクタ。
	 * @param int 画像幅（px）
	 * @param int 画像高さ（px）
	 * @param int 背景画像色 例:0x0099FF
	 */
	public function __construct($filePath)
	{
		
		$fileType = exif_imagetype($filePath);
		
		if($fileType === IMAGETYPE_GIF){
			$imageResource = imagecreatefromgif($filePath);
		}else if($fileType === IMAGETYPE_JPEG){
			$imageResource = imagecreatefromjpeg($filePath);
		}else if($fileType === IMAGETYPE_PNG){
			$imageResource = imagecreatefrompng($filePath);
		}else if($fileType === IMAGETYPE_WBMP){
			$imageResource = imagecreatefromwbmp($filePath);
		}else{
			throw new Exception('file type error. '.$filePath.' is not supported image file.');
		}
		
		$imageSize = getimagesize($filePath);
		$width = $imageSize[0];
		$height = $imageSize[1];
		$this->graphics = new DmGraphics($imageResource , $width , $height);
		$this->textGraphics = new DmTextGraphics($imageResource , $width , $height);
		$this->_imageResource = $imageResource;
		$this->_width = $width;
		$this->_height = $height;
		
		//背景色設定(透過を有効化)
		imagesavealpha($imageResource,true);
		
	}
	
}