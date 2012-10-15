<?php

require_once dirname(__FILE__).'/DmImageFilter.php';
require_once dirname(__FILE__).'/DmImageFitFilter.php';
require_once dirname(__FILE__).'/DmImageCropFilter.php';

class DmImageInstagramNormalFilter extends DmImageFilter
{
	
	public $width = 0;
	
	public $height = 0;
	
	public $fram;
	
	public function __construct($size, $frame=3)
	{
		$this->width  = $size;
		$this->height = $size;
		$this->fram   = $frame;
	}
	
	public function execute(DmImage $image)
	{
		$w = $this->width;
		$h = $this->height;
		$f = (int)$this->fram;
		$fit = new DmImageFitFilter($w,$h,true);
		$crop = new DmImageCropFilter($w,$h);
		$filters = array($fit,$crop);
		$image->applyFilters($filters);
		
		
		
		$effectImage = new DmImageFile( dirname(__FILE__).'/img/instagram_effect_01.png');
		$effectImage->applyFilters($filters);
		$image->draw($effectImage,0,0,$effectImage->getWidth(),$effectImage->getHeight());
		
		$filteredResource = $image->getImageResource();
		$this->effect($filteredResource);
		
		
		if($f===1 || $f===2 || $f===3){
			$frameImage = new DmImageFile( dirname(__FILE__).'/img/frame_'.str_pad($f,2,'0',STR_PAD_LEFT).'.png');
			$frameImage->applyFilters($filters);
			$image->draw($frameImage,0,0,$frameImage->getWidth(),$frameImage->getHeight());
		}
		$image->setImageResource($filteredResource);
		
		return $filteredResource;
		
	}
	
	public function effect($resource)
	{
		
	}
	
}
