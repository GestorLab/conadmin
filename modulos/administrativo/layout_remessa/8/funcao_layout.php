<?	
	function codigoMesDia(){
		switch(date("m")){
			case 1:
				return date("d")."01";				
			case 2:
				return date("d")."02";
			case 3:
				return date("d")."03";
			case 4:
				return date("d")."04";
			case 5:
				return date("d")."05";
			case 6:
				return date("d")."06";
			case 7:
				return date("d")."07";
			case 8:
				return date("d")."08";
			case 9:
				return date("d")."09";
			case 10:
				return date("d")."O";
			case 11:
				return date("d")."N";
			case 12:
				return date("d")."D";
		}
	}
	function removeCaracters($string){
		$string = strtr($string, "АЮЦБИЙМСТУЗЭГаюцбиймстузэг", "aaaaeeiooouucAAAAEEIOOOUUC");
		$string = preg_replace("/[^\w\.,:;?@\[\]_\-\{\}<>=!#$%&\(\)*\/\\\+ ]/", '', $string);
		return $string;
	}
?>