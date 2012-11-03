<?php

abstract class Dm_Image_Filter_Abstract
{
	
	public function __construct()
	{
		
	}
	
	/**
	 * 
	 * @param DmImage
	 * @return bool
	 */
	abstract function execute(Dm_Image $image);
	
}
