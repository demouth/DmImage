<?php

class Dm_Image_Filter_InstagramToaster extends Dm_Image_Filter_InstagramNormal
{
	
	public function effect($resource)
	{
		
		imagegammacorrect($resource,1,0.8);
		imagefilter($resource, IMG_FILTER_CONTRAST,-1);
		imagefilter($resource, IMG_FILTER_COLORIZE, 10,3,2,0);
		
	}
	
}
