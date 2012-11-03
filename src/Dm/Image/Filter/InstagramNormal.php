<?php

abstract class Dm_Image_Filter_InstagramNormal extends Dm_Image_Filter_Abstract
{
	
	public $width = 0;
	
	public $height = 0;
	
	public $fram;
	
	/**
	 * 
	 * @param int 画像サイズピクセル数
	 * @param int フレーム番号 0-3
	 */
	public function __construct($size, $frame=3)
	{
		$this->width  = $size;
		$this->height = $size;
		$this->fram   = $frame;
	}
	
	public function execute(Dm_Image $image)
	{
		$w = $this->width;
		$h = $this->height;
		$f = (int)$this->fram;
		$fit = new Dm_Image_Filter_Fit($w,$h,true);
		$crop = new Dm_Image_Filter_Crop($w,$h);
		$filters = array($fit,$crop);
		$image->applyFilters($filters);
		
		
		
		$effectImage = new Dm_Image_File( dirname(__FILE__).'/img/instagram_effect_01.png');
		$effectImage->applyFilters($filters);
		$image->draw($effectImage,0,0,$effectImage->getWidth(),$effectImage->getHeight());
		
		$filteredResource = $image->getImageResource();
		$this->effect($filteredResource);
		
		
		if($f===1 || $f===2 || $f===3){
			$frameImage = new Dm_Image_File( dirname(__FILE__).'/img/frame_'.str_pad($f,2,'0',STR_PAD_LEFT).'.png');
			$frameImage->applyFilters($filters);
			$image->draw($frameImage,0,0,$frameImage->getWidth(),$frameImage->getHeight());
		}
		$image->setImageResource($filteredResource);
		
		return $filteredResource;
		
	}
	
	abstract public function effect($resource);
}
