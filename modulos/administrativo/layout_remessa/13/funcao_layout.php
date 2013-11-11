<?	
	function mod11($num, $base=4, $r=0) {
		/**
		* Autor:
		* Pablo Costa <pablo@users.sourceforge.net>
		*
		* Função:
		* Calculo do Modulo 11 para geracao do digito verificador 
		* de boletos bancarios conforme documentos obtidos 
		* da Febraban - www.febraban.org.br 
		*
		* Entrada:
		* $num: string numérica para a qual se deseja calcularo digito verificador;
		* $base: valor maximo de multiplicacao [2-$base]
		* $r: quando especificado um devolve somente o resto
		*
		* Saída:
		* Retorna o Digito verificador.
		*
		* Observações:
		* - Script desenvolvido sem nenhum reaproveitamento de código pré existente.
		* - Assume-se que a verificação do formato das variáveis de entrada é feita antes da execução deste script.
		*/ 
		$fi		  = 0;
		$soma	  = 0;
		$fator[0] = 3;
		$fator[1] = 7;
		$fator[2] = 9;
		$fator[3] = 1;
		
		/* Separacao dos numeros */
		for ($i = strlen($num); $i > 0; $i--) {
	
			// pega cada numero isoladamente
			$numeros[$i] = substr($num,$i-1,1);

			// Efetua multiplicacao do numero pelo falor
			$parcial[$i] = $numeros[$i] * $fator[$fi];

			// Soma dos digitos
			$soma += $parcial[$i];
			$fi++;
			if ($fi == $base) {
				$fi = 0;
			}			
		}

		/* Calculo do modulo 11 */
		if($r == 0){		
			$digito = $soma % 11;
			
			if($digito != 0 && $digito != 1){
				$digito = 11 - $digito; 
			}else{
				$digito = 0;
			}		
				
			return $digito;
		} elseif ($r == 1){
			$resto = $soma % 11;
			return $resto;
		}
	}
	
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
