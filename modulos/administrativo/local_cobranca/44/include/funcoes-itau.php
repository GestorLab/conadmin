<?php 

class boleto { 
	function banco_itau(&$V0842f867){ 
		 $V4ab10179 = "341"; 
		 $V92f52e6e = "9"; 
		 $V077effb5 = "000"; 
		 $V540e4d39 = $this->F540e4d39($V0842f867["data_vencimento"]); 
		 $V01773a8a = $this->F6266027b($V0842f867["valor"],10,"0","v"); 
		 $V9f808afd = $this->F6266027b($V0842f867["agencia"],4,"0"); 
		 $V0842f867["agencia"] = $V9f808afd; 
		 $Vef0ad7ba = $this->F6266027b($V0842f867["conta"],5,"0"); 
		 $V0842f867["conta"] = $Vef0ad7ba ; 
		 $V5b3b7abe = $this->F6266027b($V0842f867["nosso_numero"],8,"0"); 
		 $V7c3c1e38 = $V0842f867["carteira"];
		 $V1c90f9c3 = $this->Fd1ea9d43("$V9f808afd$Vef0ad7ba$V7c3c1e38$V5b3b7abe"); 
		 $Va5a7044f = $this->Fd1ea9d43("$V9f808afd$Vef0ad7ba");
		 $Vc21a9e1d = "$V4ab10179$V92f52e6e$V540e4d39$V01773a8a$V7c3c1e38$V5b3b7abe$V1c90f9c3$V9f808afd$Vef0ad7ba$Va5a7044f$V077effb5"; 
		 $V28dfab58 = $this->F80457cf3($Vc21a9e1d); 
		 $Vc21a9e1d = "$V4ab10179$V92f52e6e$V28dfab58$V540e4d39$V01773a8a$V7c3c1e38$V5b3b7abe$V1c90f9c3$V9f808afd$Vef0ad7ba$Va5a7044f$V077effb5"; 
		 $Vaf2c4191 = $V9f808afd ."/". $Vef0ad7ba . "-" . $V0842f867["digito_conta"]; 
		 $V5b3b7abe = $V7c3c1e38 ."/". $V5b3b7abe ."-". $V1c90f9c3; 
		 $V0842f867["codigo_barras"] = "$Vc21a9e1d"; 
		 $V0842f867["linha_digitavel"] = $this->F5aef63b6($Vc21a9e1d); 
		 $V0842f867["agencia_codigo"] = $Vaf2c4191 ; 
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
	function F11efdac1($V0fc3cfbc, $V593616de=9, $V4b43b0ae=0) { 
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
		$V9c6350b0 = $V15a00ab3 % 10; $V05fbaf7e = 10 - $V9c6350b0; 
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
		$V0db9137c = substr($V41ef8940, 4, 1);  
		 
		 
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
			//$V0842f867 = number_format($V0842f867,2,",","."); 
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
 
function fbarcode($valor){

$fino = 1 ;
$largo = 3 ;
$altura = 50 ;

  $barcodes[0] = "00110" ;
  $barcodes[1] = "10001" ;
  $barcodes[2] = "01001" ;
  $barcodes[3] = "11000" ;
  $barcodes[4] = "00101" ;
  $barcodes[5] = "10100" ;
  $barcodes[6] = "01100" ;
  $barcodes[7] = "00011" ;
  $barcodes[8] = "10010" ;
  $barcodes[9] = "01010" ;
  for($f1=9;$f1>=0;$f1--){ 
    for($f2=9;$f2>=0;$f2--){  
      $f = ($f1 * 10) + $f2 ;
      $texto = "" ;
      for($i=1;$i<6;$i++){ 
        $texto .=  substr($barcodes[$f1],($i-1),1) . substr($barcodes[$f2],($i-1),1);
      }
      $barcodes[$f] = $texto;
    }
  }


//Desenho da barra
//Guarda inicial

?> 
<img src=imagens/p.png width=<?=$fino?> height=<?=$altura?> border=0><img 
src=imagens/b.png width=<?=$fino?> height=<?=$altura?> border=0><img 
src=imagens/p.png width=<?=$fino?> height=<?=$altura?> border=0><img 
src=imagens/b.png width=<?=$fino?> height=<?=$altura?> border=0><img 
<?
$texto = $valor ;
if((strlen($texto) % 2) <> 0){
	$texto = "0" . $texto;
}

// Draw dos dados
while (strlen($texto) > 0) {
  $i = round(esquerda($texto,2));
  $texto = direita($texto,strlen($texto)-2);
  $f = $barcodes[$i];
  for($i=1;$i<11;$i+=2){
    if (substr($f,($i-1),1) == "0") {
      $f1 = $fino ;
    }else{
      $f1 = $largo ;
    }
?>
    src=imagens/p.png width=<?=$f1?> height=<?=$altura?> border=0><img 
<?
    if (substr($f,$i,1) == "0") {
      $f2 = $fino ;
    }else{
      $f2 = $largo ;
    }
?>
    src=imagens/b.png width=<?=$f2?> height=<?=$altura?> border=0><img 
<?
  }
}

// Draw guarda final
?>
src=imagens/p.png width=<?=$largo?> height=<?=$altura?> border=0><img 
src=imagens/b.png width=<?=$fino?> height=<?=$altura?> border=0><img 
src=imagens/p.png width=<?=1?> height=<?=$altura?> border=0><?
} //Fim da função

function esquerda($entra,$comp){
	return substr($entra,0,$comp);
}

function direita($entra,$comp){
	return substr($entra,strlen($entra)-$comp,$comp);
}

 
?>