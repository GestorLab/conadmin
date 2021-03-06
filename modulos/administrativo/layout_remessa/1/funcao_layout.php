<?
		function mod11($num, $base=9, $r=0) {
		/**
		* Autor:
		* Pablo Costa <pablo@users.sourceforge.net>
		*
		* Fun��o:
		* Calculo do Modulo 11 para geracao do digito verificador 
		* de boletos bancarios conforme documentos obtidos 
		* da Febraban - www.febraban.org.br 
		*
		* Entrada:
		* $num: string num�rica para a qual se deseja calcularo digito verificador;
		* $base: valor maximo de multiplicacao [2-$base]
		* $r: quando especificado um devolve somente o resto
		*
		* Sa�da:
		* Retorna o Digito verificador.
		*
		* Observa��es:
		* - Script desenvolvido sem nenhum reaproveitamento de c�digo pr� existente.
		* - Assume-se que a verifica��o do formato das vari�veis de entrada � feita antes da execu��o deste script.
		*/ 

		$soma = 0;
		$fator = 2;
		
		/* Separacao dos numeros */
		for ($i = strlen($num); $i > 0; $i--) {
	
			// pega cada numero isoladamente
			$numeros[$i] = substr($num,$i-1,1);

			// Efetua multiplicacao do numero pelo falor
			//echo $numeros[$i]." * ".$fator."<br>";
			$parcial[$i] = $numeros[$i] * $fator;

			// Soma dos digitos
			$soma += $parcial[$i];
			if ($fator == $base) {
				// restaura fator de multiplicacao para 2 
				$fator = 1;
			}
			$fator++;
		}

		/* Calculo do modulo 11 */
		if ($r == 0) {
		//	$soma *= 10;
			$digito = $soma % 11;
			
			if($digito != 0){
				$digito = 11 - $digito; 
			}
		
			if ($digito == 10) {
				$digito = "P";
			}		
			return $digito;
		} elseif ($r == 1){
			$resto = $soma % 11;
			return $resto;
		}
	}
?>
