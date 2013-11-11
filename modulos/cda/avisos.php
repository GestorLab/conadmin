<?
	$Avisos = avisos(1,$local_IdPessoa);

	if($Avisos){
		$IdAvisos = array_keys($Avisos);
		$MaxAvisos = count($IdAvisos);

		if(getParametroSistema(95,33) != ''){
			$MaxAvisos = getParametroSistema(95,33);
		}
		for($i=0; $i<$MaxAvisos; $i++){
			if(strlen($Avisos[$IdAvisos[$i]][ResumoAviso]) > 47){
				$avisoPersonalizado = substr($Avisos[$IdAvisos[$i]][ResumoAviso],0,47);
				$avisoPersonalizado .= "... ";
			}else{
				$avisoPersonalizado = $Avisos[$IdAvisos[$i]][ResumoAviso];
			}
			$Avisos[$IdAvisos[$i]][DataCriacao]	=	dataConv($Avisos[$IdAvisos[$i]][DataCriacao],'Y-m-d','d/m/Y');

			echo"<p><B style='font-weight:bold'>".$Avisos[$IdAvisos[$i]][DataCriacao]." - ".$Avisos[$IdAvisos[$i]][TituloAviso]."</B><BR />".$avisoPersonalizado."<BR /><a href='?ctt=aviso.php&IdAviso=".$IdAvisos[$i]."' style='color:#C60000;'><B>[Leia mais]</B></a></p>";
		}
	}else{
		echo"<BR><center>Não há avisos.</center>";
	}
?>