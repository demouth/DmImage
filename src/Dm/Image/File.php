<?php
/**
 * Dm_Image_File
 * 画像を表すクラス。
 * 
 * @example
 * $image = new Dm_Image_File('/path/to/image/image.jpg');
 * $filter = new Dm_Image_Filter_InstagramLoFi(300,1);
 + $image->applyFilter($filter);
 * $image->display();
 * 
 * @author demouth.net
 */
class Dm_Image_File extends Dm_Image
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
		$this->graphics = new Dm_Image_Graphic_Shape($imageResource , $width , $height);
		$this->textGraphics = new Dm_Image_Graphic_Text($imageResource , $width , $height);
		$this->_imageResource = $imageResource;
		$this->_width = $width;
		$this->_height = $height;
		
		//背景色設定(透過を有効化)
		imagesavealpha($imageResource,true);
		
	}
	
}