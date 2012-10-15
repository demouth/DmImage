<?php

abstract class DmImageFilter
{
	
	public function __construct()
	{
		
	}
	
	/**
	 * 
	 * @param DmImage
	 * @return bool
	 */
	abstract function execute(DmImage $image);
	
}
