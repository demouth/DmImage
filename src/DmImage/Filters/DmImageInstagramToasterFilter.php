<?php

require_once dirname(__FILE__).'/DmImageFilter.php';
require_once dirname(__FILE__).'/DmImageFitFilter.php';
require_once dirname(__FILE__).'/DmImageCropFilter.php';

class DmImageInstagramToasterFilter extends DmImageFilter
{
	
	public $width = 0;
	
	public $height = 0;
	
	public function __construct($size)
	{
		$this->width  = $size;
		$this->height = $size;
	}
	
	public function execute(DmImage $image)
	{
		$w = $this->width;
		$h = $this->height;
		$fit = new DmImageFitFilter($w,$h,true);
		$crop = new DmImageCropFilter($w,$h);
		$filters = array($fit,$crop);
		$image->applyFilters($filters);
		
		
		
		$effectImage = new DmImageFile( dirname(__FILE__).'/img/effect_02.png');
		$effectImage->applyFilters($filters);
		$image->draw($effectImage,0,0,$effectImage->getWidth(),$effectImage->getHeight());
		
		$filteredResource = $image->getImageResource();
/*
		imagegammacorrect($filteredResource,1,1.2);
		imagefilter($filteredResource, IMG_FILTER_CONTRAST,-1);
		imagefilter($filteredResource, IMG_FILTER_COLORIZE, 60,20,1,0);
*/

		imagegammacorrect($filteredResource,1,0.8);
		imagefilter($filteredResource, IMG_FILTER_CONTRAST,-1);
		imagefilter($filteredResource, IMG_FILTER_COLORIZE, 10,3,2,0);

		$frameImage = new DmImageFile( dirname(__FILE__).'/img/frame_01.png');
		$frameImage->applyFilters($filters);
		$image->draw($frameImage,0,0,$frameImage->getWidth(),$frameImage->getHeight());

		$image->setImageResource($filteredResource);
		
		return $filteredResource;
		
	}
	
}
