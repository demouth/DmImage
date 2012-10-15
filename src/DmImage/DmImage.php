<?php

require_once realpath(dirname(__FILE__).'/../DmColor/').'/DmColor.php';
require_once dirname(__FILE__).'/Graphics/DmGraphics.php';
require_once dirname(__FILE__).'/Graphics/DmTextGraphics.php';

/**
 * DmImage
 * 画像を表すクラス。
 * 
 * @example
 * 
 * @version 1.1.0
 * @author demouth.net
 */
class DmImage
{
	
	/**
	 * @var DmGraphics
	 */
	public $graphics;
	/**
	 * @var DmTextGraphics
	 */
	public $textGraphics;
	
	/**
	 * @var resource
	 */
	protected $_imageResource;
	
	/**
	 * @var int
	 */
	protected $_width;
	
	/**
	 * @var int
	 */
	protected $_height;
	
	/**
	 * 
	 * @var string
	 */
	protected $_tempDirPath = "";
	
	/**
	 * コンストラクタ。
	 * @param int 画像幅（px）
	 * @param int 画像高さ（px）
	 * @param int 背景画像色 例:0x0099FF
	 */
	public function __construct($width , $height , $backgroundColor=0)
	{
		
		$imageResource = imagecreatetruecolor($width, $height);
		
		$this->graphics = new DmGraphics($imageResource , $width , $height);
		$this->textGraphics = new DmTextGraphics($imageResource , $width , $height);
		$this->_imageResource = $imageResource;
		$this->_width = $width;
		$this->_height = $height;
		
		//背景色設定(透過を有効化)
		imagesavealpha($imageResource,true);
//		imagealphablending($imageResource, false);
		$colorId = DmColor::argb($backgroundColor)->imagecolorallocatealpha($imageResource);
//		imagefilledrectangle($imageResource, 0, 0, $width-1, $height-1, $colorId);
		imagefill($imageResource, 0, 0, $colorId);
	}
	
	/**
	 * @return resource
	 */
	public function getImageResource()
	{
		return $this->_imageResource;
	}
	
	public function setImageResource($resource)
	{
		$this->_imageResource = $resource;
		$this->_width = imagesx($resource);
		$this->_height = imagesy($resource);
		return $this;
	}
	
	/**
	 * Return the width of the image.
	 * @return int 
	 */
	public function getWidth()
	{
		return $this->_width;
	}
	
	/**
	 * Return the height of the image.
	 * @return int
	 */
	public function getHeight()
	{
		return $this->_height;
	}
	
	
	/**
	 * Output a PNG image to either the browser.
	 * The raw image stream will be outputted directly.
	 * @return void
	 */
	public function display()
	{
		header('Content-type: image/png');
		imagepng($this->_imageResource , null , 5 );
	}
	
	/**
	 * Copy and merge part of an image
	 * @param DmImage
	 * @param int x-coordinate of source point.
	 * @param int y-coordinate of source point.
	 * @param int Source width.
	 * @param int Source height.
	 * @return bool Returns TRUE on success or FALSE on failure.
	 */
	public function draw(DmImage $image,$x=0,$y=0,$width=null,$height=null)
	{
		$srcImageResource = $image->getImageResource();
		if (is_null($width))  $width = $image->getWidth();
		if (is_null($height)) $height = $image->getHeight();
		return imagecopy(
			$this->getImageResource(),
			$srcImageResource,
			$x,
			$y,
			0,
			0,
			$width,
			$height
		);
	}
	
	/**
	 * The path to save the file to.
	 * @param string
	 * @param string filetype 'png' 'jpg' 'jpeg' 'gif'
	 * @return bool
	 */
	public function saveTo($path , $type='png', $quality=null)
	{
		if (!$path) return false;
		switch ($type) {
			case 'jpg':
			case 'jpeg':
				if (is_null($quality)) $quality = 75;
				imagejpeg($this->_imageResource , $path , $quality );
				break;
			case 'gif':
				imagegif($this->_imageResource , $path );
				break;
			case 'png':
			default:
				if (is_null($quality)) $quality = 5;
				imagepng($this->_imageResource , $path , $quality );
				break;
		}
		return true;
	}
	
	/**
	 * Destroy an image.
	 * @return void
	 */
	public function destroy()
	{
		imagedestroy($this->_imageResource);
		$this->graphics->destory();
		$this->graphics = null;
	}
	
	/**
	 * Return data scheme URI.
	 * 
	 * @example
	 *   data:image/png;base64,iVBORw0KGgoAAAANSUhEU...
	 * @return string 
	 */
	public function toDataSchemeURI()
	{
		$md5 = md5(microtime(1).rand(10000, 99999));
		$filePath = $this->tempDirPath() . DIRECTORY_SEPARATOR . "temp".$md5.".png";
		$this->saveTo($filePath);
		
		$uri = 'data:' . mime_content_type($filePath) . ';base64,';
		$uri .= base64_encode(file_get_contents($filePath));
		
		unlink($filePath);
		
		return $uri;
	}
	
	protected function tempDirPath()
	{
		if($this->_tempDirPath===""){
			return dirname(__FILE__).DIRECTORY_SEPARATOR.'temp';
		}else{
			return $this->_tempDirPath;
		}
	}
	
	/**
	 * TEMP画像の保存先を指定する。
	 * このメソッドを呼ばない場合、このライブラリ配置先と同階層に作成されます。
	 * 
	 * @param string TEMP画像保存先のフルパス
	 * @return void
	 */
	public function setTempDirPath($path)
	{
		$this->_tempDirPath = $path;
	}
	
	public function applyFilter($filter)
	{
		$filteredResouce = $filter->execute($this);
		$this->_imageResource = $filteredResouce;
		$this->_width  = imagesx($filteredResouce);
		$this->_height = imagesy($filteredResouce);
		
		return $this;
	}
	
	public function applyFilters(array $filters)
	{
		foreach ($filters as $filter) {
			$this->applyFilter($filter);
		}
		return $this;
	}
	
}