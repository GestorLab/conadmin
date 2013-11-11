<?
	$sql = "SELECT
				IdVersao,
				IdVersaoOld
			FROM
				Atualizacao
			WHERE
				IdAtualizacao = $IdAtualizacao";
	$res = mysql_query($sql,$con);
	$lin = mysql_fetch_array($res);

	$sql = "update Atualizacao set LogUpdateMySQL = '#".date("Y-m-d H:i:s")." -> Inicia Execução.' where IdAtualizacao = $IdAtualizacao";
	mysql_query($sql,$con);

	$sql = "SELECT
				IdAtualizacao,
				IdTipoAtualizacao,
				IdTipoSQL,
				Atualizacao
			FROM
				cntsistemas_sisbuild.Atualizacao
			WHERE
				IdSistema = 1 AND
				IdAtualizacao > $lin[IdVersaoOld] AND
				IdAtualizacao <= $lin[IdVersao]
			ORDER BY
				IdAtualizacao";
	$res = mysql_query($sql,$conCNT);
	while($lin = mysql_fetch_array($res)){

		switch($lin[IdTipoAtualizacao]){
			case 1:
				//SQL
				switch($lin[IdTipoSQL]){
					case 1:
						//SQL Diversas
						$lin[Atualizacao] = explode(";",$lin[Atualizacao]);
						for($i=0; $i<count($lin[Atualizacao]); $i++){

							$lin[Atualizacao][$i] = trim($lin[Atualizacao][$i]);

							if($lin[Atualizacao][$i] != ''){
								// Executar a SQL
								executaQueryAtualizacao($IdAtualizacao, $lin[IdAtualizacao], $lin[Atualizacao][$i]);
							}
						}
						break;
					
					case 2:
						// Trigger
						executaQueryAtualizacao($IdAtualizacao, $lin[IdAtualizacao], $lin[Atualizacao]);
						break;
					
					case 3:
						// View
						executaQueryAtualizacao($IdAtualizacao, $lin[IdAtualizacao], $lin[Atualizacao]);						
						break;
					
					case 4:
						// Function
						executaQueryAtualizacao($IdAtualizacao, $lin[IdAtualizacao], $lin[Atualizacao]);
						break;

					case 5:
						// SQL Única
						executaQueryAtualizacao($IdAtualizacao, $lin[IdAtualizacao], $lin[Atualizacao]);
						break;
				}
				break;
			
			case 2:
				//ROTINA
				// Executar a rotina		
				executaRotinaAtualizacao($IdAtualizacao, $lin[IdAtualizacao], $lin[Atualizacao]);
				break;
		}
	}

	@mysql_close($con);
	include("../../../files/conecta.php");
	
	$sql = "update Atualizacao set DataUpdateMySQL = concat(curdate(),' ',curtime()), LogUpdateMySQL = concat(LogUpdateMySQL,'\n','#".date("Y-m-d H:i:s")." -> Conclui Execução.') where IdAtualizacao = $IdAtualizacao";
	mysql_query($sql,$con);

	function executaQueryAtualizacao($IdAtualizacao, $IdSisbuild, $sql){

		include("../../../files/conecta.php");

		$mysqli = new mysqli($con_bd[server], $con_bd[login], $con_bd[senha], $con_bd[banco]);
		$mysqli->query($sql);

		$ErroOff = array(0,1050,1060,1062);

		if(!in_array($mysqli->errno,$ErroOff)){
			$Log = "#$IdSisbuild -> ERRO[".$mysqli->errno."]: ".$mysqli->error;
			$Log = str_replace("'","\'",$Log).".";
		
			// Salva a execução
			$sql = "update Atualizacao set LogUpdateMySQL = concat(LogUpdateMySQL,'\n','$Log') where IdAtualizacao = $IdAtualizacao";
			mysql_query($sql,$con);
			
		}

		mysql_close($con);
		$mysqli->close();
	}

	function executaRotinaAtualizacao($IdAtualizacao, $IdSisbuild, $rotina){

		include("../../../files/conecta.php");

		$sql = "SELECT 
					ValorParametroSistema UrlSistema
				FROM 
					ParametroSistema 
				WHERE 
					IdGrupoParametroSistema = 6 AND 
					IdParametroSistema = 3";
		$res = mysql_query($sql,$con);
		$lin = mysql_fetch_array($res);
		
		$file = file("$lin[UrlSistema]/scripts/$rotina");

		$Log = "#$IdSisbuild -> ERRO[rotina]: ";
		$Log = str_replace("'","\'",$Log);

		// Salva a execução
		$sql = "update Atualizacao set LogUpdateMySQL = concat(LogUpdateMySQL,'\n','$Log') where IdAtualizacao = $IdAtualizacao";
		mysql_query($sql,$con);

		for($i=0; $i<count($file); $i++){
			$Log = str_replace("'","\'",$file[$i]);

			// Salva a execução
			$sql = "update Atualizacao set LogUpdateMySQL = concat(LogUpdateMySQL,'$Log') where IdAtualizacao = $IdAtualizacao";
			mysql_query($sql,$con);
		}

		// Salva a execução
		$sql = "update Atualizacao set LogUpdateMySQL = concat(LogUpdateMySQL,'.') where IdAtualizacao = $IdAtualizacao";
		mysql_query($sql,$con);

		mysql_close($con);
	}

	@mysql_close($con);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>	
	<body onLoad="proximaEtapa()">&nbsp;</body>
</html>
<script>
	function proximaEtapa(){
		parent.superior.location.replace('../cadastro_rotina_atualizacao_etapas.php?Etapa=6');
		parent.inferior.location.replace("rotina_atualizacao.php?Etapa=6&IdAtualizacao=<?=$IdAtualizacao?>");
	}
</script>