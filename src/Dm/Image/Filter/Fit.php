<?php

class Dm_Image_Filter_Fit extends Dm_Image_Filter_Abstract
{
	
	public $width = 0;
	
	public $height = 0;
	
	/**
	 * 
	 * @var bool
	 */
	public $bounding;
	
	public function __construct($width,$height,$bounding=false)
	{
		$this->width  = $width;
		$this->height = $height;
		$this->bounding = $bounding;
	}
	
	public function execute(Dm_Image $image)
	{
		
		$width  = $this->width;
		$height = $this->height;
		$ratio  = 1;
		$wRatio = $width  / $image->getWidth();
		$hRatio = $height / $image->getHeight();
		if($wRatio > $hRatio){
			$ratio = $this->bounding ? $wRatio : $hRatio;
		}else{
			$ratio = $this->bounding ? $hRatio : $wRatio;
		}
		
		$w = $image->getWidth() * $ratio;
		$h = $image->getHeight() * $ratio;
		
		$resampledResource = imagecreatetruecolor($w, $h);
		//背景色設定(透過を有効化)
		imagesavealpha($resampledResource,true);
//		imagealphablending($imageResource, false);
		$colorId = Dm_Color::argb(0)->imagecolorallocatealpha($resampledResource);
//		imagefilledrectangle($imageResource, 0, 0, $width-1, $height-1, $colorId);
		imagefill($resampledResource, 0, 0, $colorId);
		
		imagecopyresampled(
			$resampledResource,
			$image->getImageResource(),
			0,0,0,0,$w,$h,
			$image->getWidth(),
			$image->getHeight()
		);
		
		return $resampledResource;
		
	}
	
}
