<?php
/**
 * Dm_Color
 * 色を表すクラス。
 * RGB・HSV変換できます。
 * 
 * @example
 * Example 1:
 * //RGBを10進数で個別に設定する。
 * $color = new Dm_Color();
 * $color->r(255)->g(100)->b(50);
 * echo $color->g; // 100
 * 
 * Example 2:
 * //RGBを16進数で設定する。
 * $color = Dm_Color::rgb(0xFF0066);
 * echo $color->r; // 255
 * 
 * Example 3:
 * //HSV色空間で色を設定する。
 * $color = Dm_Color::hsv(100, 0.5, 0.2);
 * echo $color->s; // 0.5
 * echo $color->hsv; // 'hsv(100, 0.5,0.2)'
 * 
 * Example4
 * //RGBを16進数で設定し、16進数で値を取得する。
 * $color = Dm_Color::rgb(0xFF0099);
 * echo $color->rgb; // 'ff0099'
 * 
 * Example5
 * //RGBを10進数で設定し、HSVで値を取得する。
 * $color = Dm_Color::rgb(255,10,60);
 * echo $color->rgb; // 'ff0a3c'
 * echo $color->hsv; // 'hsv(347.76, 0.96,1)'
 * 
 * Example6
 * //RGBを10進数で個別に設定し、明度を80%に下げた色を16進数で取得する。
 * $color = Dm_Color::rgb(155,200,60);
 * $color2->v = $color2->v * 0.8;
 * echo $color->rgb; // '7ba02f'
 * 
 * Example7
 * //Alphaを使う
 * $color = Dm_Color::argb(0.5, 155, 200, 60);
 * $color = Dm_Color::ahsv(0.5, 300.5, 200, 60);
 * echo $color->a; // 0.5
 * echo $color->argb; // '7fffc83c'
 * 
 * Example7
 * //16進数に変換する
 * $color = Dm_Color::argb(0.5, 155, 200, 60);
 * $color = $color->toInt();
 * 
 * @author demouth.net
 */
class Dm_Color
{
	
	/**
	 * R
	 * @var int 0-255
	 */
	protected $_r = 0;
	
	/**
	 * G
	 * @var int 0-255
	 */
	protected $_g = 0;
	
	/**
	 * B
	 * @var int 0-255
	 */
	protected $_b = 0;
	
	/**
	 * Alpha
	 * 0が透明、1が不透明
	 * @var float 0-1
	 */
	protected $_a = 0;
	
	/**
	 * 色相(Hue)
	 * @var float 0-360
	 */
	protected $_h = 0;
	
	/**
	 * 彩度(Saturation・Chroma)
	 * @var float 0-1
	 */
	protected $_s = 0;
	
	/**
	 * 明度(Brightness・Lightness・Value)
	 * @var float 0-1
	 */
	protected $_v = 0;
	
	/**
	 * コンストラクタ。
	 */
	public function __construct()
	{
		
	}
	
	/**
	 * マジックメソッド。
	 * r,g,b,h,s,vメソッドを呼び出した際に色を更新する。
	 * @param string メソッド名
	 * @param array arguments
	 * @return self
	 */
	public function __call($name, $arguments)
	{
		if(count($arguments)>0){
			return $this->_set($name, $arguments[0]);
		}else{
			return $this->_get($name);
		}
	}
	
	/**
	 * マジックメソッド。
	 * r,g,b,h,s,v,rgb,hsv に対応する。
	 * @see クラスコメント参照
	 * @param string 取得したい色
	 * @return mixed
	 */
	public function __get($name)
	{
		return $this->_get($name);
	}
	
	public function __set($name, $value)
	{
		return $this->_set($name, $value);
	}
	
	/**
	 * マジックメソッド。
	 * r,g,b,h,s,v,rgb,hsv に対応する。
	 * @see クラスコメント参照
	 * @param string 取得したい色
	 * @return mixed
	 */
	public function _get($name)
	{
		$colorFunctions = array('r','g','b','h','s','v','a');
		if (in_array($name, $colorFunctions)){
			$varName = '_'.$name;
			return $this->{$varName};
		}
		
		if ($name === 'rgb'){
			return dechex($this->_r << 16 | $this->_g << 8 | $this->_b);
		}else if ($name === 'argb'){
			return dechex( (int)($this->_a*0xFF) << 24 | $this->_r << 16 | $this->_g << 8 | $this->_b);
		}else if ($name === 'hsv'){
			return 'hsv('
				.round($this->_h,2).', '
				.round($this->_s,2).','
				.round($this->_v,2).')';
		}else if ($name === 'ahsv'){
			return 'hsva('
				.round($this->_a,2).')'
				.round($this->_h,2).', '
				.round($this->_s,2).','
				.round($this->_v,2).')';
		}
	}
	
	protected function _set($name, $value)
	{
		//RGB
		$colorFunctions = array('r','g','b');
		if (in_array($name, $colorFunctions)){
			$varName = '_'.$name;
			$this->{$varName} = $value;
			$this->updateHSV();
		}
		
		//HSV
		$colorFunctions = array('h','s','v');
		if (in_array($name, $colorFunctions)){
			$varName = '_'.$name;
			$this->{$varName} = $value;
			$this->updateRGB();
		}
		
		//alpha
		if ($name == 'a') {
			$this->_a = $value;
		}
		
		return $this;
	}
	
	/**
	 * RGBで初期化しインスタンスを取得する。
	 * 16進数の場合は引数が1つ。
	 * R,G,Bを個別で指定する場合は引数が3つ。
	 * @param int R 0-255(0x00-0xFF)
	 * @param int G 0-255(0x00-0xFF) or null
	 * @param int B 0-255(0x00-0xFF) or null
	 * @return self
	 */
	public static function rgb($v1,$v2=null,$v3=null)
	{
		if(is_null($v2)){
			$rgb = $v1;
			$r = ($rgb >> 16) & 0xFF;
			$g = ($rgb >> 8) & 0xFF;
			$b = $rgb & 0xFF;
			$self = new self();
			return $self->r($r)->g($g)->b($b);
		}else{
			$r = $v1 & 0xFF;
			$g = $v2 & 0xFF;
			$b = $v3 & 0xFF;
			$self = new self();
			return $self->r($r)->g($g)->b($b);
		}
	}
	
	/**
	 * ARGBで初期化しインスタンスを取得する。
	 * 16進数の場合は引数が1つ。
	 * A,R,G,Bを個別で指定する場合は引数が4つ。
	 * Alphaは第4引数に渡すか、0xFF001122とARGBの16進数を渡す(例だとFFの部分)。
	 * 
	 * @param float Alpha 0-1 or int
	 * @param int R 0-255(0x00-0xFF) or null
	 * @param int G 0-255(0x00-0xFF) or null
	 * @param int B 0-255(0x00-0xFF) or null
	 * @return self
	 */
	public static function argb($v1,$v2=null,$v3=null,$v4=null)
	{
		if(is_null($v2)){
			$argb = $v1;
			$a = ($argb >> 24)/0xFF;
			$self = self::rgb($argb);
			return $self->a($a);
		}else{
			$a = $v1 <= 1 ? $v1 : 1;
			$self = self::rgb($v2,$v3,$v4);
			return $self->a($a);
		}
	}
	
	/**
	 * HSVで初期化しインスタンスを取得する。
	 * @param float
	 * @param float
	 * @param float 
	 * @return self
	 */
	public static function hsv($h,$s,$v,$a=1)
	{
		$self = new self();
		return $self->h($h)->s($s)->v($v)->a($a);
	}
	
	/**
	 * AHSVで初期化しインスタンスを取得する。
	 * @param float Alpha 0-1
	 * @param float H
	 * @param float S
	 * @param float V
	 * @return self
	 */
	public static function ahsv($a,$h,$s,$v)
	{
		$self = self::hsv($h,$s,$v);
		return $self->a($a);
	}
	
	/**
	 * 
	 * @return int
	 */
	public function toInt()
	{
		$int = 
			((int)($this->_a*255) << 24) |
			$this->_r << 16 |
			$this->_g << 8  |
			$this->_b;
		return $int;
	}
	
	/**
	 * imagecolorallocateで色IDを取得する。
	 * @return int
	 */
	public function imagecolorallocatealpha($imageResource)
	{
		return imagecolorallocatealpha(
			$imageResource,
			$this->_r,
			$this->_g,
			$this->_b,
			((int)(127-$this->_a*127))
		);
	}
	
	/**
	 * imagecolorallocateで色IDを取得する。
	 * @return int
	 */
	public function imagecolorallocate($imageResource)
	{
		
		return imagecolorallocate(
			$imageResource,
			$this->_r,
			$this->_g,
			$this->_b
		);
		
	}
	
	/**
	 * RGBでHSVを更新する。
	 * @return void
	 */
	protected function updateHSV()
	{
		$hsv = array(0, 0, 0);
		$r = $this->_r / 255;
		$g = $this->_g / 255;
		$b = $this->_b / 255;
		$max = max($r,$g,$b);
		$min = min($r,$g,$b);
		if($max != 0){
			$hsv[1] = ($max - $min) / $max;
			if($max == $r){
				if( ($max-$min)==0 ){
					$hsv[0] = 0;
				}else{
					$hsv[0] = 60 * ($g - $b) / ($max-$min);
				}
			}else if($max == $g){
				$hsv[0] = 60 * ($b - $r) / ($max - $min) + 120;
			}else{
				$hsv[0] = 60 * ($r - $g) / ($max - $min) + 240;
			}
			while ($hsv[0] < 0){
				$hsv[0] += 360;
			}
		}
		$hsv[2] = $max;
		$this->_h = $hsv[0];
		$this->_s = $hsv[1];
		$this->_v = $hsv[2];
	}
	
	/**
	 * HSVでRGBを更新する。
	 * @return void
	 */
	protected function updateRGB()
	{
		$h = $this->_h;
		$s = $this->_s;
		$v = $this->_v;
		if($s == 0) {
			$this->_r = (int) $v * 255;
			$this->_g = (int) $v * 255;
			$this->_b = (int) $v * 255;
		}else{
			$hi = ($h/60)>>0;
			$f = ($h/60 - $hi);
			$p = $v*(1 - $s);
			$q = $v*(1 - $f*$s);
			$t = $v*(1-(1-$f)*$s);
			if($hi==0){
				$this->_r = (int)($v * 255);
				$this->_g = (int)($t * 255);
				$this->_b = (int)($p * 255);
			}else if($hi==1){
				$this->_r = (int)($q * 255);
				$this->_g = (int)($v * 255);
				$this->_b = (int)($p * 255);
			}else if($hi==2){
				$this->_r = (int)($p * 255);
				$this->_g = (int)($v * 255);
				$this->_b = (int)($t * 255);
			}else if($hi==3){
				$this->_r = (int)($p * 255);
				$this->_g = (int)($q * 255);
				$this->_b = (int)($v * 255);
			}else if($hi==4){
				$this->_r = (int)($t * 255);
				$this->_g = (int)($p * 255);
				$this->_b = (int)($v * 255);
			}else if($hi==5){
				$this->_r = (int)($v * 255);
				$this->_g = (int)($p * 255);
				$this->_b = (int)($q * 255);
			}
		}
	}
	
}