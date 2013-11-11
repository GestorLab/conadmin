<?
	function mod10($num) {
		/**
		* Autor:
		* Weiner Soares de Lima
		*
		* Função:
		* Calculo do Modulo 10 para geracao do digito verificador 			
		*/ 

		$soma	= 0;
		$fator	= 2;
		$flag	= 0;

		/* Separacao dos numeros */
		for ($i = strlen($num); $i > 0; $i--) {
			if($flag == 1){
				$fator = 1;
				$flag = 0;
			}else{
				$fator = 2;
				$flag = 1;
			}
			$numeros[$i] = substr($num,$i-1,1);
			
			// Efetua multiplicacao do numero pelo falor			
			$parcial[$i] = $numeros[$i] * $fator;	
		}

		$j = 0;
		for($i=0; $i<=count($parcial); $i++){			
			if($parcial[$i] > 9){
				$aux = str_split($parcial[$i]);				
				$vetor[$j] = $aux[0]; 
				$j++;
				$vetor[$j] = $aux[1];
				$j++;
			}else{
				$vetor[$j] = $parcial[$i];
				$j++;
			}
		}		
		$soma = array_sum($vetor);

		/* Calculo do modulo 10 */
		
		$digito = $soma % 10;		
		$digito = 10 - $digito;		
				
		return $digito;
	
	}	
?>
