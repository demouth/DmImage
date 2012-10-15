<?php

require_once dirname(__FILE__).'/DmImageInstagramNormalFilter.php';

class DmImageInstagramLoFiFilter extends DmImageInstagramNormalFilter
{
	public function effect($resource)
	{
		imagegammacorrect($resource,1,0.5);
		imagefilter($resource, IMG_FILTER_CONTRAST,-10);
		imagefilter($resource, IMG_FILTER_COLORIZE, 20,3,2,0);
	}
	
}
