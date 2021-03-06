<?php 
	class boleto { 
		function banco_bradesco(&$V0842f867){ 
			$V4ab10179 = "037";																					// C�digo do banco
			$V92f52e6e = "9";																					// C�digo da Moeda
			$V077effb5 = "0"; 
			$V540e4d39 = $this->F540e4d39($V0842f867["data_vencimento"]);										// Data do vencimento
			$V01773a8a = $this->F6266027b($V0842f867["valor_boleto"],10,"0","v");								// Valor do Boleto
			$V9f808afd = $this->F6266027b($V0842f867["agencia"],4,"0");											// Ag�ncia
			$V0842f867["agencia"] = $V9f808afd;																	// Ag�ncia 
			$ContaSemDigito = str_pad($V0842f867["conta"], 7, 0, STR_PAD_LEFT);
			$Vef0ad7ba = $this->F6266027b($V0842f867["conta"].$V0842f867["digito_conta"],8,"0");				// C�digo da Conta
			$Vef0ad7ba2 = str_pad($V0842f867["conta"], 8, 0, STR_PAD_LEFT);
			$V0842f867["conta"] = $Vef0ad7ba;																	// C�digo da Conta 
			$V5b3b7abe = $this->F6266027b($V0842f867["numero_documento"],8,"0");								// N�mero do documento

			$V7c3c1e38 = $V0842f867["carteira"];																// C�digo da Carteira		
						
			$V1c90f9c3 = $this->F11efdac1($V9f808afd.$ContaSemDigito.$V7c3c1e38.$V5b3b7abe);					// Digito do DAC [Ag�ncia /Conta (sem digito verificador) /Carteira/Nosso N�mero]
					
			$Vc21a9e1d = "$V4ab10179$V92f52e6e$V540e4d39$V01773a8a$V9f808afd$V7c3c1e38$V5b3b7abe$Vef0ad7ba$V077effb5"; 
			$V28dfab58 = $this->F80457cf3($Vc21a9e1d); 

			$Vc21a9e1d = "$V4ab10179$V92f52e6e$V28dfab58$V540e4d39$V01773a8a$V9f808afd$V7c3c1e38$V5b3b7abe$V1c90f9c3$Vef0ad7ba"; // Informa��es para gerar a linha digitavel.
			$Vaf2c4191 = $V9f808afd ."/". $Vef0ad7ba . "-" . $V0842f867["digito_conta"]; 
			$V5b3b7abe = $V7c3c1e38 ."/". $V5b3b7abe ."-". $V1c90f9c3; 
						
			$V0842f867["codigo_barras"] = "$Vc21a9e1d"; 
			$V0842f867["linha_digitavel"] = $this->F5aef63b6($Vc21a9e1d); 
			$V0842f867["agencia_codigo"] = $Vaf2c4191; 
			$V0842f867["nosso_numero"] = $V5b3b7abe; 
		}
		
		function F80457cf3($V0842f867){ 
			$V0842f867 = $this->F11efdac1($V0842f867); 
			if($V0842f867==0 || $V0842f867 >9) $V0842f867 = 1; 
			return $V0842f867; 
		}

		function F540e4d39($V0842f867){ 
			$V0842f867 = str_replace("/","-",$V0842f867); 
			$V465b1f70 = explode("-",$V0842f867); 
			return $this->F1b261b5c($V465b1f70[2], $V465b1f70[1], $V465b1f70[0]); 
		}

		function F1b261b5c($Vbde9dee6, $Vd2db8a61, $V465b1f70) { 
			return(abs(($this->F5a66daf8("1997","10","07")) - ($this->F5a66daf8($Vbde9dee6, $Vd2db8a61, $V465b1f70)))); 
		}
		
		function F5a66daf8($V84cdc76c,$V7436f942,$V628b7db0) { 
			$V151aa009 = substr($V84cdc76c, 0, 2); 
			$V84cdc76c = substr($V84cdc76c, 2, 2); 
			if ($V7436f942 > 2) { 
				$V7436f942 -= 3; 
			} else { 
				$V7436f942 += 9; 
				if ($V84cdc76c) {
					$V84cdc76c--; 
				} else { 
					$V84cdc76c = 99; 
					$V151aa009 --; 
				} 
			}
			return ( floor((146097 * $V151aa009)/4 ) + floor(( 1461 * $V84cdc76c)/4 ) + floor(( 153 * $V7436f942 +2) /5 ) + $V628b7db0 +1721119); 
		}

		function F11efdac1($num){
			
		//	$num = str_pad($num, 15, 0, STR_PAD_LEFT);
			$numtotal10 = 0;
			$fator = 2;
			$pos = 0;

			// Separacao dos numeros
			for ($i = strlen($num); $i > 0; $i--) {
				// pega cada numero isoladamente
				$numeros[$i] = substr($num,$i-1,1);
				
				// Efetua multiplicacao do numero pelo (falor 10)
				$numeros[$i]." * ".$fator;
				$temp = $numeros[$i] * $fator;
				
				if($temp < 10){
					$numtotaltemp[$pos] = $temp;
					$pos++;
				}else{
					for($i_pos = 0; $i_pos < strlen($temp); $i_pos++){
						$numtotaltemp[$pos] = substr($temp,$i_pos,1);
						$pos++;
					}
				}
				
				if ($fator == 2) {
					$fator = 1;
				} else {
					$fator = 2; // intercala fator de multiplicacao (modulo 10)
				}
			}			
		
			$numtotal10 = array_sum($numtotaltemp);
			
			// v�rias linhas removidas, vide fun��o original
			// Calculo do modulo 10
			$resto = $numtotal10 % 10;
			$digito = 10 - $resto;
			if ($resto == 0) {
				$digito = 0;
			}
			
			return $digito;			
		}
		
		function F11efdac11($V0fc3cfbc, $V593616de=9, $V4b43b0ae=0) { 
			$V15a00ab3 = 0; 
			$V44f7e37e = 2;
			for ($V865c0c0b = strlen($V0fc3cfbc); $V865c0c0b > 0; $V865c0c0b--) { 

				$V5e8b750e[$V865c0c0b] = substr($V0fc3cfbc,$V865c0c0b-1,1); 
				
				$Vb040904b[$V865c0c0b] = $V5e8b750e[$V865c0c0b] * $V44f7e37e; 
				$V15a00ab3 += $Vb040904b[$V865c0c0b]; 
				if ($V44f7e37e == $V593616de) { 
					$V44f7e37e = 1; 
				} 
				$V44f7e37e++; 
			}
			if ($V4b43b0ae == 0) { 
				$V15a00ab3 *= 10; 
				$V05fbaf7e = $V15a00ab3 % 11; 
				if ($V05fbaf7e == 10) { 
					$V05fbaf7e = 0; 
				} 
				return $V05fbaf7e; 
			} elseif ($V4b43b0ae == 1){ 
				$V9c6350b0 = $V15a00ab3 % 11; 
				return $V9c6350b0; 
			} 
		}

		function modulo_11($num, $base=9, $r=0)  {
			/**
			 *   Autor:
			 *           Pablo Costa <pablo@users.sourceforge.net>
			 *
			 *   Fun��o:
			 *    Calculo do Modulo 11 para geracao do digito verificador 
			 *    de boletos bancarios conforme documentos obtidos 
			 *    da Febraban - www.febraban.org.br 
			 *
			 *   Entrada:
			 *     $num: string num�rica para a qual se deseja calcularo digito verificador;
			 *     $base: valor maximo de multiplicacao [2-$base]
			 *     $r: quando especificado um devolve somente o resto
			 *
			 *   Sa�da:
			 *     Retorna o Digito verificador.
			 *
			 *   Observa��es:
			 *     - Script desenvolvido sem nenhum reaproveitamento de c�digo pr� existente.
			 *     - Assume-se que a verifica��o do formato das vari�veis de entrada � feita antes da execu��o deste script.
			 */                                        

			$soma = 0;
			$fator = 2;

			/* Separacao dos numeros */
			for ($i = strlen($num); $i > 0; $i--) {
				// pega cada numero isoladamente
				if($i != 5){
					$numeros[$i] = substr($num,$i-1,1);
					// Efetua multiplicacao do numero pelo falor
					$parcial[$i] = $numeros[$i] * $fator;
					$parcial[$i]." = ".$numeros[$i]." * ".$fator;
					// Soma dos digitos
					$soma += $parcial[$i];

					if ($fator == $base) {
						// restaura fator de multiplicacao para 2 
						$fator = 1;
					}
					$fator++;
				}
			}

			/* Calculo do modulo 11 */
			if ($r == 0) {
				//$soma *= 10;
				$digito = $soma % 11;				
				
				if ($digito == 10 || $digito == 0 || $digito == 1) {				
					return 1;
				}

				$digito = 11 - $digito;
				
				if ($digito == 10 || $digito == 0 || $digito == 1) {
					$digito = 1;
				}
				return $digito;
			} elseif ($r == 1){
				$resto = $soma % 11;
				return $resto;
			}
		}
		
		function Fd1ea9d43($V0fc3cfbc) {
	
			$V4ec61c61 = 0; 
			$V44f7e37e = 2;  
			for ($V865c0c0b = strlen($V0fc3cfbc); $V865c0c0b > 0; $V865c0c0b--) { 
				$V5e8b750e[$V865c0c0b] = substr($V0fc3cfbc,$V865c0c0b-1,1); 
				$Vee487e79[$V865c0c0b] = $V5e8b750e[$V865c0c0b] * $V44f7e37e; 
				$V4ec61c61 .= $Vee487e79[$V865c0c0b]; 
				if ($V44f7e37e == 2) { 
					$V44f7e37e = 1; 
				} else { 
					$V44f7e37e = 2; 
				} 
			}
			$V15a00ab3 = 0; 
			for ($V865c0c0b = strlen($V4ec61c61); $V865c0c0b > 0; $V865c0c0b--) { 
				$V5e8b750e[$V865c0c0b] = substr($V4ec61c61,$V865c0c0b-1,1); 
				$V15a00ab3 += $V5e8b750e[$V865c0c0b]; 
			}
			$V9c6350b0 = $V15a00ab3 % 10; 
			$V05fbaf7e = 10 - $V9c6350b0; 
			if ($V9c6350b0 == 0) { 
				$V05fbaf7e = 0; 
			}
			return $V05fbaf7e; 
		}
		
		function F5aef63b6($V41ef8940) { 
			$Vec6ef230 = substr($V41ef8940, 0, 4); 			
			$V1d665b9b = substr($V41ef8940, 19, 5); 
			$V7bc3ca68 = $this->Fd1ea9d43("$Vec6ef230$V1d665b9b"); 			
			$V13207e3d = "$Vec6ef230$V1d665b9b$V7bc3ca68"; 
			$Ved92eff8 = substr($V13207e3d, 0, 5); 
			$Vc6c27fc9 = substr($V13207e3d, 5); 
			$V8a690a8f = "$Ved92eff8.$Vc6c27fc9";   
			$Vec6ef230 = substr($V41ef8940, 24, 10); 
			$V1d665b9b = $this->Fd1ea9d43($Vec6ef230); 
			$V7bc3ca68 = "$Vec6ef230$V1d665b9b";
			$V13207e3d = substr($V7bc3ca68, 0, 5); 
			$Ved92eff8 = substr($V7bc3ca68, 5); 
			$V4499f7f9 = "$V13207e3d.$Ved92eff8";   
			$Vec6ef230 = substr($V41ef8940, 34, 10); 
			$V1d665b9b = $this->Fd1ea9d43($Vec6ef230); 
			$V7bc3ca68 = "$Vec6ef230$V1d665b9b"; 
			$V13207e3d = substr($V7bc3ca68, 0, 5); 
			$Ved92eff8 = substr($V7bc3ca68, 5); 
			$V9e911857 = "$V13207e3d.$Ved92eff8";			
 			#$V0db9137c = substr($V41ef8940, 4, 1); 
			//echo $V0db9137c = substr($V41ef8940, 0, 44); 

			$V0db9137c = $this->modulo_11($V41ef8940);    			

			$Va7ad67b2 = substr($V41ef8940, 5, 14);
						
			return "$V8a690a8f $V4499f7f9 $V9e911857 $V0db9137c $Va7ad67b2";
		}
		
		function F294e91c9($V4d5128a0) { 
			$Ve2b64fe0 = substr($V4d5128a0, 0, 3); 
			$V284e2ffa = $this->F11efdac1($Ve2b64fe0);
			return $Ve2b64fe0 . "-" . $V284e2ffa; 
		}
		
		function F6266027b($V0842f867, $Vce2db5d6, $V0152807c, $V401281b0 = "e"){ 
			if($V401281b0=="v"){ 
				$V0842f867 = str_replace(".","",$V0842f867); 
				$V0842f867 = str_replace(",",".",$V0842f867); 
				$V0842f867 = number_format($V0842f867,2,"",""); 
				$V0842f867 = str_replace(".","",$V0842f867); 
				$V401281b0 = "e"; 
			} 
			while(strlen($V0842f867)<$Vce2db5d6){ 
				if($V401281b0=="e"){ 
					$V0842f867 = $V0152807c . $V0842f867; 
				}else{ 
					$V0842f867 = $V0842f867 . $V0152807c; 
				} 
			} 
			return $V0842f867; 
		}
	} 

function fbarcode($V01773a8a){
$V77e77c6a = 1 ;
$V5f44b105 = 3 ;
$V2c9890f4 = 50 ;
 $Ve5200a9e[0] = "00110" ;
 $Ve5200a9e[1] = "10001" ;
 $Ve5200a9e[2] = "01001" ;
 $Ve5200a9e[3] = "11000" ;
 $Ve5200a9e[4] = "00101" ;
 $Ve5200a9e[5] = "10100" ;
 $Ve5200a9e[6] = "01100" ;
 $Ve5200a9e[7] = "00011" ;
 $Ve5200a9e[8] = "10010" ;
 $Ve5200a9e[9] = "01010" ;
 for($Vbd19836d=9;$Vbd19836d>=0;$Vbd19836d--){ 
 for($V3667f6a0=9;$V3667f6a0>=0;$V3667f6a0--){ 
 $V8fa14cdd = ($Vbd19836d * 10) + $V3667f6a0 ;
 $V62059a74 = "" ;
 for($V865c0c0b=1;$V865c0c0b<6;$V865c0c0b++){ 
 $V62059a74 .= substr($Ve5200a9e[$Vbd19836d],($V865c0c0b-1),1) . substr($Ve5200a9e[$V3667f6a0],($V865c0c0b-1),1);
 }
 $Ve5200a9e[$V8fa14cdd] = $V62059a74;
 }
 }
 
 
?><img src=imagens/p.gif width=<?=$V77e77c6a?> height=<?=$V2c9890f4?> border=0><img 
src=imagens/b.gif width=<?=$V77e77c6a?> height=<?=$V2c9890f4?> border=0><img 
src=imagens/p.gif width=<?=$V77e77c6a?> height=<?=$V2c9890f4?> border=0><img 
src=imagens/b.gif width=<?=$V77e77c6a?> height=<?=$V2c9890f4?> border=0><img 
<?
$V62059a74 = $V01773a8a ;
if((strlen($V62059a74) % 2) <> 0){
	$V62059a74 = "0" . $V62059a74;
}
 
while (strlen($V62059a74) > 0) {
 $V865c0c0b = round(Ff2317ae6($V62059a74,2));
 $V62059a74 = F0835e508($V62059a74,strlen($V62059a74)-2);
 $V8fa14cdd = $Ve5200a9e[$V865c0c0b];
 for($V865c0c0b=1;$V865c0c0b<11;$V865c0c0b+=2){
 if (substr($V8fa14cdd,($V865c0c0b-1),1) == "0") {
 $Vbd19836d = $V77e77c6a ;
 }else{
 $Vbd19836d = $V5f44b105 ;
 }
?>
 src=imagens/p.gif width=<?=$Vbd19836d?> height=<?=$V2c9890f4?> border=0><img 
<?
 if (substr($V8fa14cdd,$V865c0c0b,1) == "0") {
 $V3667f6a0 = $V77e77c6a ;
 }else{
 $V3667f6a0 = $V5f44b105 ;
 }
?>
 src=imagens/b.gif width=<?=$V3667f6a0?> height=<?=$V2c9890f4?> border=0><img 
<?
 }
}
 
?>
src=imagens/p.gif width=<?=$V5f44b105?> height=<?=$V2c9890f4?> border=0><img 
src=imagens/b.gif width=<?=$V77e77c6a?> height=<?=$V2c9890f4?> border=0><img 
src=imagens/p.gif width=<?=1?> height=<?=$V2c9890f4?> border=0><?
} 
function Ff2317ae6($V0842f867,$V005480c8){
	return substr($V0842f867,0,$V005480c8);
}
function F0835e508($V0842f867,$V005480c8){
	return substr($V0842f867,strlen($V0842f867)-$V005480c8,$V005480c8);
} 
 
 ?>
