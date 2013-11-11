<?php
	class AntiSpam
	{
		static $fonts = array(
			"fonts/wavy_.ttf",
			"fonts/alanden_.ttf",
			"fonts/elecha_.ttf",
			"fonts/luggerbu_.ttf"
		);
		static $img = null;
		
		public $str = null;
		
		function AntiSpam($x = 204, $y = 58) {
			self::$img = imagecreate($x, $y);
		}
		
		function rand() {
			$str = "";
			$sort = "asdfghjklqwertyuiopzxcvbnm1234567890";
			
			for($i = 0; $i < 5; $i++) {
				$str .= $sort[rand(0, (strlen($sort) - 1))];
			}
			
			$this->setText($str);
		}
		
		function setText($str) {
			$this->str = $str;
			imagecolorallocate(self::$img, 255, 255, 255);
			$x = 20;
			
			for($i = 0; $i < strlen($this->str); $i++) {
				$r = rand(-33, 33);
				$this->raffleFont($this->raffleColor(), 28, $r, $x, 44, $this->str[$i]);
				$x += 36;
			}
		}
		
		function raffleFont($color, $size, $r, $x, $y, $char) {
			imagettftext(self::$img, $size, $r, $x, $y, $color,self::$fonts[rand(0, (count(self::$fonts) - 1))], $char);
		}
		
		function raffleColor($color = array(255, 255, 255)) {
			do {
				$r = rand(0, 100);
				$g = rand(0, 100);
				$b = rand(0, 100);
			} while($r.$g.$b == implode("", $color));
			
			return imagecolorallocate(self::$img, $r, $g, $b);
		}
		
		function show() {
			header("Content-type: image/png");
			imagepng(self::$img);
		}
	}
?>