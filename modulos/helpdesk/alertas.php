<?
	$alerta = '';
	$i		= 0;

	if(!$conCNT){
		$alerta[$i] = "N�o foi poss�vel conectar no servidor de Help Desk. Por favor, tente novamente mais tarde.";
		$i++;
	}
		
	##### Exibe os alertas
	for($ii=0; $ii<$i; $ii++){
		if($alerta[$ii] != ''){
			echo "<div class='alerta'>$alerta[$ii]</div>";
		}
	}
?>
