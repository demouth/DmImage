<?php
require('GifMerge.class.php');

class SimpleGifMerge{
	public $path;
	public function __construct($path){
		$this->path = $path.'/';
	}
	public function merge(){
		if ($handle = opendir($this->path)) {
			$c = 0;
			$files = array();
			while (false !== ($file = readdir($handle))) {
				if(!is_file($this->path.$file))continue; 
				$files[] = realpath($this->path.$file);
				$c++;
			}
			sort($files);
			closedir($handle);
		}
		//http://d.hatena.ne.jp/shimooka/20060914/1158209427
		$d = array_fill(0, $c, 5);
		$x = array_fill(0, $c, 0);
		$y = array_fill(0, $c, 0);
		$anim = new GifMerge($files, 255, 255, 255, 0, $d, $x, $y, 'C_FILE');
		header('Content-type: image/gif');
		echo $anim->getAnimation();
	}
	public function clear(){
		if ($handle = opendir($this->path)) {
			while (false !== ($file = readdir($handle))) {
				if(is_file($this->path.$file)) unlink($this->path.$file);
			}
		}
	}
}