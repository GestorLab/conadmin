<?	
	function codigoMesDia(){
		switch(date("m")){
			case 1:
				return "1".date("d");				
			case 2:
				return "2".date("d");
			case 3:
				return "3".date("d");
			case 4:
				return "4".date("d");
			case 5:
				return "5".date("d");
			case 6:
				return "6".date("d");
			case 7:
				return "7".date("d");
			case 8:
				return "8".date("d");
			case 9:
				return "9".date("d");
			case 10:
				return "O".date("d");
			case 11:
				return "N".date("d");
			case 12:
				return "D".date("d");
		}
	}
	function removeCaracters($string){
		$string = strtr($string, "áàãâéêíóôõúüçÁÀÃÂÉÊÍÓÔÕÚÜÇ", "aaaaeeiooouucAAAAEEIOOOUUC");
		$string = preg_replace("/[^\w\.,:;?@\[\]_\-\{\}<>=!#$%&\(\)*\/\\\+ ]/", '', $string);
		return $string;
	}
?>
