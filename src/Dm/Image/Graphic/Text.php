<?php
/**
 * Dm_Image_Graphic_Text
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
class Dm_Image_Graphic_Text implements Dm_Image_Graphic_Interface
{
	
	/**
	 * 
	 * @var int
	 */
	protected $_font=1;
	
	protected $_fontPath;
	
	protected $_fontSize = 12;
	
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
		
		$this->_fontPath = dirname(__FILE__).'/font/ipag00303/ipag.ttf';
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
	 * テキストスタイルを決定する。
	 * @param int latin2 エンコーディングの組み込みのフォントの場合は 1, 2, 3, 4, 5 のいずれか (数字が大きなほうが、より大きいフォントに対応します)、 あるいは imageloadfont() で登録したフォントの識別子のいずれか。
	 * @param int 線色 例:0x00FF99
	 * @return Dm_Image_Graphic_Text
	 */
	public function setFontSize($size)
	{
		$this->_fontSize = $size;
		return $this;
	}
	
	/**
	 * 
	 * @return Dm_Image_Graphic_Text
	 */
	public function setColor($color=0xFF000000)
	{
		$this->_fontColor = $color;
		return $this;
	}
	
	/**
	 * 
	 * @return Dm_Image_Graphic_Text
	 */
	public function setFontFile($fontPath)
	{
		$this->_fontPath = $fontPath;
		return $this;
	}
	
	/**
	 * 文字列を描画する
	 * @param int X軸(px)
	 * @param int Y軸(px)
	 * @param string 文字列
	 * @return Dm_Image_Graphic_Text
	 */
	public function textTo($x,$y,$text,$angle=0)
	{
		imagettftext(
			$this->_imageResource ,
			$this->_fontSize,//fontsize
			$angle,//angle
			$x ,
			$y ,
			Dm_Color::argb($this->_fontColor)->imagecolorallocatealpha($this->_imageResource),
			$this->_fontPath,
			$text
		);
		return $this;
	}
	
	/**
	 * 
	 */
	public function destroy()
	{
		
		
	}
	
}