<?php
namespace Admin\Controller;
use Think\Controller;
class ValiteController extends BaseController{
	private $imgPath; //图片的路径
	private $keys; //存放特征码库
	private $data = array(); //图片二值化数据
	private $result = array();
	function __construct() {
		$this->keys = array(
				'2'=>'001111000011111100111000110110000110000000110000001100000011100000111000001110000011000000111111110111111110',
				'b'=>'110000000110000000110000000110111000111111100111001110110000110110000110110000110111001110111111100110111000',
				'v'=>'000000000000000000000000000110001100110001100110001100011011000001011000011011000001110000001110000001110000',
				'n'=>'000000000000000000000000000110111100111111110111000110110000110110000110110000110110000110110000110110000110',
				'z'=>'000000000000000000000000000111111100111111100000011000000111000001110000011100000011000000111111100111111100',
				'1'=>'000110000001110000011110000110110000100110000000110000000110000000110000000110000000110000000110000000110000',
				'x'=>'000000000000000000000000000110001100111011100011001000001110000001110000001110000011011000111011100110001100',
				'3'=>'001111100011111110110000110000000110000111100000111100000001110000000110110000110111001110011111100001111000',
				'm'=>'000000000000000000000000000110111001111111111111001110110001100110001100110001100110001100110001100110001100',
				'c'=>'000000000000000000000000000001111000011111100111001100110000000110000000110000000111001100011111000001111000',
		);
	}
	//设置图片   路径   或   url
	public function setImage($path) {
		$this->imgPath = $path;
	}
	//获得结果
	public function getResult() {
		$this->getBitArray();
		$this->match();
		return implode($this->result);
	}
	//得到图片二值化序列
	public function getBitArray() {
		$res = imagecreatefromjpeg($this->imgPath);
		$size = getimagesize($this->imgPath);

		for ($i=0; $i<$size[1]; $i++) {
			for ($j=0; $j<$size[0]; $j++) {
				//size[1]为图片高度
				$rgb = imagecolorat($res, $j, $i);
				$rgbArray = imagecolorsforindex($res, $rgb);
				if(($rgbArray['red']+$rgbArray['green']+$rgbArray['blue'])<300)	{
					$this->data[$i][$j] = 1;
					//echo 1;
				}
				else {
					$this->data[$i][$j] = 0;
					//echo 0;
				}
			}
			//echo '<br>';
		}
		//var_dump($this->data);
		//处理完后建立图形
		//imagejpeg($res, 'pic/new.jpg');
		//释放内存
		//imagedestroy($res);
		//echo $size[2];
	
	}
	//获取单个字符的二值化序列
	public function getSingleChar($x, $y, $xInc, $yInc) {
		$endX = $x+$xInc;
		$endY = $y+$yInc;
		$temp = $x;
		$bits = '';
		for (; $y<$endY; $y++) {
			for ($x=$temp; $x<$endX; $x++) {
				$bits.=$this->data[$y][$x];
				//echo $this->data[$y][$x];
			}
			//echo '<br>';
		}
		return $bits;
	}
	public function match() {
		//$this->data;
		//$charArray = array('v', 'b', 'z', '1');
		$percent = 0;
		for ($i=0; $i<4; $i++) {
			$data = $this->getSingleChar($i*10+3, 4, 9, 12); //$data为一个字符提取的二值化序列
			//echo $charArray[$i].'<br>';
			$percentArray = array();
			foreach ($this->keys as $index=>$key) {
	
				similar_text($data, $key, $percent);
				//echo $percent.'<br>';
				$percentArray[$percent] = $index;
			}
			ksort($percentArray);
			$this->result[] = end($percentArray);
			//var_dump($percentArray);
		}
		//var_dump($this->result);
	}	
	//建立特征码库
	static function createCodeLib($imgPath, $x, $y, $xInc, $yInc) {
		$res = imagecreatefromjpeg($imgPath);
		$endX = $x+$xInc;
		$endY = $y+$yInc;
		$temp = $x;
		for (; $y<$endY; $y++) {
			for ($x=$temp; $x<$endX; $x++) {
				//size[1]为图片高度
				$rgb = imagecolorat($res, $x, $y);
				$rgbArray = imagecolorsforindex($res, $rgb);
				if(($rgbArray['red']+$rgbArray['green']+$rgbArray['blue'])<300)
					echo 1;
				//echo $rgbArray['red']+$rgbArray['green']+$rgbArray['blue'],'\n';
				else
					echo 0;
				//echo $rgbArray['red']+$rgbArray['green']+$rgbArray['blue'],'\n';
			}
			//echo 'a';
			//echo '<br>';
		}
	}
}