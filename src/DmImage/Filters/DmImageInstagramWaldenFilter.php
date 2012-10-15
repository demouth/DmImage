<?php

require_once dirname(__FILE__).'/DmImageInstagramNormalFilter.php';

class DmImageInstagramWaldenFilter extends DmImageInstagramNormalFilter
{
	
	
	public function effect($resource)
	{
		
		imagegammacorrect($resource,1,1.2);
		imagefilter($resource, IMG_FILTER_CONTRAST,-1);
		imagefilter($resource, IMG_FILTER_COLORIZE, 60,20,0,50);
		
	}
	
}
