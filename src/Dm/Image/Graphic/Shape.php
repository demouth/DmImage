<?php
/**
 * Dm_Image_Graphic_Shape
 * 画像への図形描画等を行う。
 * 
 * @example
 * $graphics
 * 		->moveTo($PL, $PT)
 * 		->lineTo($PL+$W , $PT)
 * 		->moveTo($PL, $PT+$H)
 * 		->lineTo($PL+$W , $PT+$H)
 * 		->moveTo($PL, $PT)
 * 		->lineTo($PL, $PT+$H)
 * 		->moveTo($PL+$W, $PT)
 * 		->lineTo($PL+$W, $PT+$H);
 * 
 * @author demouth.net
 */
class Dm_Image_Graphic_Shape implements Dm_Image_Graphic_Interface
{
	
	
	/**
	 * 
	 * @var int
	 */
	protected $_oldX = 0;
	
	/**
	 * 
	 * @var int
	 */
	protected $_oldY = 0;
	
	/**
	 * 
	 * @var array
	 */
	protected $_polygonPath = array();
	
	/**
	 * 
	 * @var bool
	 */
	protected $_beganFill = false;
	
	/**
	 * 
	 * @var int
	 */
	protected $_lineThickness = 1;
	
	/**
	 * 
	 * @var int
	 */
	protected $_lineColor = 0;
	
	/**
	 * 
	 * @var Dm_Color
	 */
	protected $_lineDmColor;
	
	/**
	 * 
	 * @var int
	 */
	protected $_fillColor;
	
	/**
	 * 
	 * @var Dm_Color
	 */
	protected $_fillDmColor;
	
	/**
	 * 
	 * @var int
	 */
	protected $_font=1;
	
	/**
	 * 
	 * @var int
	 */
	protected $_fontColor=0;
	
	/**
	 * 
	 * @var gd
	 */
	protected $_imageResource;
	
	/**
	 * 
	 * @var int
	 */
	protected $_width;
	
	/**
	 * 
	 * @var int
	 */
	protected $_height;
	
	/**
	 * コンストラクタ。
	 * @param resource 画像リソース
	 * @param int 画像幅
	 * @param int 画像高さ
	 */
	public function __construct($imageResource , $width , $height)
	{
		$this->_imageResource = $imageResource;
		$this->_width = $width;
		$this->_height = $height;
		
		$this->lineStyle(1,0xFF000000);
		$this->fillStyle(0xFF000000);
	}
	
	/**
	 * 画像幅取得。
	 * @return int
	 */
	public function getWidth()
	{
		return $this->_width;
	}
	
	/**
	 * 画像高さ取得。
	 * @return int
	 */
	public function getHeight()
	{
		return $this->_height;
	}
	
	/**
	 * 現在の描画位置を (x, y) に移動します。
	 * @param int X軸(px)
	 * @param int Y軸(px)
	 * @return Dm_Image_Graphic_Shape
	 */
	public function moveTo($x,$y)
	{
		$this->_oldX = $x;
		$this->_oldY = $y;
		$this->_polygonPath[] = $x;
		$this->_polygonPath[] = $y;
		return $this;
	}
	
	/**
	 * 現在の描画位置から (x, y) まで、現在の線のスタイルを使用して線を描画します。
	 * その後で、現在の描画位置は (x, y) に設定されます。
	 * @param int X軸(px)
	 * @param int Y軸(px)
	 * @return Dm_Image_Graphic_Shape
	 */
	public function lineTo($x,$y)
	{
		
		$imageResource = $this->_imageResource;
		
		$_x = (int)$x;
		$_y = (int)$y;
		if($this->_beganFill){
			$this->_polygonPath[] = $_x;
			$this->_polygonPath[] = $_y;
		}else{
			imageline(
				$imageResource,
				$this->_oldX,
				$this->_oldY,
				$_x,
				$_y,
				$this->_lineDmColor->imagecolorallocatealpha($imageResource)
			);
		}
		$this->_oldX = $_x;
		$this->_oldY = $_y;
		return $this;
	}
	
	/**
	 * 
	 * @param int 線幅(px)
	 * @param int 線色 例:0x00FF99
	 * @return Dm_Image_Graphic_Shape
	 */
	public function lineStyle($thickness , $color=0)
	{
		$this->_lineThickness = $thickness;
		$this->_lineColor = $color;
		$this->_lineDmColor = Dm_Color::argb($color);
		
		imagesetthickness(
			$this->_imageResource,
			$this->_lineThickness
		);
		
		return $this;
	}
	
	
	/**
	 * 
	 * @param int 
	 * @param int 
	 * @param int 
	 * @param int 
	 * @return Dm_Image_Graphic_Shape
	 */
	public function drawRect($x,$y,$width,$height)
	{
		$width  += $x;
		$height += $y;
		$fillColor = $this->_fillDmColor;
		$lineColor = $this->_lineDmColor;
		imagefilledrectangle(
			$this->_imageResource,
			$x,
			$y,
			$width,
			$height,
			$fillColor->imagecolorallocatealpha($this->_imageResource)
		);
		imagerectangle(
			$this->_imageResource,
			$x,
			$y,
			$width,
			$height,
			$lineColor->imagecolorallocatealpha($this->_imageResource)
		);
		return $this;
	}
	
	/**
	 * 
	 * NOTE: Transparent does not work.
	 * 
	 * @param int 
	 * @param int 
	 * @param int 
	 * @return Dm_Image_Graphic_Shape
	 */
	public function drawCircle($x,$y,$radius)
	{
		$radius *= 2;
		$this->drawEllipse($x,$y,$radius,$radius);
		return $this;
	}
	
	/**
	 * 
	 * NOTE: Transparent does not work.
	 * 
	 * @return Dm_Image_Graphic_Shape
	 */
	public function drawPie($x,$y,$width,$height,$startDegree,$endDegree)
	{
		if (abs($startDegree-$endDegree)==360){
			$startDegree = 0;
			$endDegree = 359.9;
		}
		$fillColor = $this->_fillDmColor;
		$lineColor = $this->_lineDmColor;
		imagefilledarc(
			$this->_imageResource,
			$x,
			$y,
			$width,
			$height,
			$startDegree,
			$endDegree,
			$fillColor->imagecolorallocatealpha($this->_imageResource),
			IMG_ARC_PIE
		);
		imagearc(
			$this->_imageResource,
			$x,
			$y,
			$width,
			$height,
			$startDegree,
			$endDegree,
			$lineColor->imagecolorallocatealpha($this->_imageResource)
		);
		return $this;
	}
	
	/**
	 * 
	 * NOTE: Transparent does not work.
	 * 
	 * @return Dm_Image_Graphic_Shape
	 */
	public function drawEllipse($x,$y,$width,$height)
	{
		$fillColor = $this->_fillDmColor;
		$lineColor = $this->_lineDmColor;
		imagefilledellipse(
			$this->_imageResource,
			$x,
			$y,
			$width,
			$height,
			$fillColor->imagecolorallocatealpha($this->_imageResource)
		);
		//imagesetthicknessが効かない
		//see http://www.php.net/manual/en/function.imagesetthickness.php#93726
		//TODO:半透明色の場合色が濃くなってしまう。
		imagearc(
			$this->_imageResource,
			$x,
			$y,
			$width,
			$height,
			0,
			359.9,
			$lineColor->imagecolorallocatealpha($this->_imageResource)
		);
		return $this;
	}
	
	/**
	 * 
	 * @param int 色 例:0x00FF99
	 * @return Dm_Image_Graphic_Shape
	 */
	public function fillStyle($color)
	{
		$this->_fillColor = $color;
		$this->_fillDmColor = Dm_Color::argb($color);
		return $this;
	}
	
	/**
	 * 
	 * @return Dm_Image_Graphic_Shape
	 */
	public function beginLineFill()
	{
		$this->_polygonPath = array();
		$this->_beganFill = true;
		return $this;
	}
	
	/**
	 * 
	 * @return Dm_Image_Graphic_Shape
	 */
	public function endLineFill()
	{
		
		imagesetthickness(
			$this->_imageResource,
			0
		);
		imagefilledpolygon(
			$this->_imageResource,
			$this->_polygonPath,
			count($this->_polygonPath)/2,
			$this->_fillDmColor->imagecolorallocatealpha($this->_imageResource)
		);
		imagesetthickness(
			$this->_imageResource,
			$this->_lineThickness
		);
		imagepolygon(
			$this->_imageResource,
			$this->_polygonPath,
			count($this->_polygonPath)/2,
			$this->_lineDmColor->imagecolorallocatealpha($this->_imageResource)
		);
		$this->_polygonPath = array();
		$this->_beganFill = false;
		return $this;
	}
	
	/**
	 * 
	 */
	public function destroy()
	{
		
		
	}
	
}