<?php
/**
 * Dm_Image
 * 画像を表すクラス。
 * 
 * @example
 * Example 1:
 * $image = new Dm_Image(400,300, 0xFF0099FF);
 * $image->display();
 * 
 * Example 2:
 * $image = new Dm_Image(400,300, 0xFF0099FF);
 * $image->graphics
 * 	->lineStyle(1,0xFFFFFFFF)
 * 	->fillStyle(0)
 * 	->drawRect(10, 10, 190, 140)
 * 	->drawCircle(100, 225, 50)
 * 	->drawEllipse(300, 75, 150, 100)
 * 	->drawPie(300, 225, 150, 100, 0, 135);
 * $image->display();
 * 
 * Example 2:
 * $image = new Dm_Image(400,300, 0xFF0099FF);
 * $image->textGraphics
 * 	->setColor(0xFFFFFFFF)
 * 	->setFontSize(30)
 * 	->textTo(100, 100, 'Hello world.');
 * $image->display();
 * 
 * Example 2:
 * $image = new Dm_Image_File('/path/to/image/image.jpg');
 * $filter = new Dm_Image_Filter_InstagramLoFi(300,1);
 + $image->applyFilter($filter);
 * $image->display();
 * 
 * @author demouth.net
 */
class Dm_Image
{
	
	/**
	 * @var Dm_Image_Graphic_Shape
	 */
	public $graphics;
	/**
	 * @var Dm_Image_Graphic_Text
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
		
		$this->graphics = new Dm_Image_Graphic_Shape($imageResource , $width , $height);
		$this->textGraphics = new Dm_Image_Graphic_Text($imageResource , $width , $height);
		$this->_imageResource = $imageResource;
		$this->_width = $width;
		$this->_height = $height;
		
		//背景色設定(透過を有効化)
		imagesavealpha($imageResource,true);
//		imagealphablending($imageResource, false);
		$colorId = Dm_Color::argb($backgroundColor)->imagecolorallocatealpha($imageResource);
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
	 * @return bool
	 */
	public function display($type='png', $quality=null)
	{
		return $this->outputTo(null, $type, $quality);
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
	public function draw(Dm_Image $image,$x=0,$y=0,$width=null,$height=null)
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
		return $this->outputTo($path, $type, $quality);
	}
	
	
	/**
	 * 
	 * 
	 * @return 
	 */
	public function startDownload($filename='image', $type='png', $quality=null)
	{
		header('Content-Type: application/octet-stream');
		header('Content-disposition: attachment; filename="'.$filename.'.'.$type.'"');
		return $this->outputTo(null, $type, $quality);
	}
	
	
	/**
	 * 画像をブラウザあるいはファイルに出力する。
	 * $pathがNULLならブラウザ出力する。
	 * @param string or null nullならブラウザ出力する
	 * @param string filetype 'png' 'jpg' 'jpeg' 'gif'
	 * @return bool
	 */
	protected function outputTo($path , $type='png', $quality=null)
	{
		switch ($type) {
			case 'jpg':
			case 'jpeg':
				if (is_null($quality)) $quality = 75;
				if (is_null($path)) header('Content-Type: image/jpeg');
				imagejpeg($this->_imageResource , $path , $quality );
				break;
			case 'gif':
				if (is_null($path)) header('Content-Type: image/gif');
				imagegif($this->_imageResource , $path );
				break;
			case 'png':
			default:
				if (is_null($quality)) $quality = 5;
				if (is_null($path)) header('Content-Type: image/png');
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
		$this->graphics->destroy();
		$this->graphics = null;
		$this->textGraphics->destroy();
		$this->textGraphics = null;
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
			return dirname(__FILE__).DIRECTORY_SEPARATOR.'Image'.DIRECTORY_SEPARATOR.'temp';
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