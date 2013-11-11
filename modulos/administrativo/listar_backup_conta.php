<?
	$localModulo		= 1;
	$localOperacao		= 172;
	$localSuboperacao	= "R";
	
	include('../../files/conecta.php');
	include('../../files/funcoes.php');
	include('../../rotinas/verifica.php');
	
	$local_IdLoja				= $_SESSION['IdLoja'];
	$filtro						= $_POST['filtro'];
	$filtro_ordem				= $_POST['filtro_ordem'];
	$filtro_ordem_direcao		= $_POST['filtro_ordem_direcao'];
	$filtro_tipo_dado			= $_POST['filtro_tipoDado'];
	$filtro_servidor_endereco	= url_string_xsl($_POST['filtro_servidor_endereco'],'url',false);
	$filtro_servidor_usuario	= url_string_xsl($_POST['filtro_servidor_usuario'],'url',false);
	$filtro_limit				= $_POST['filtro_limit'];
	
	LimitVisualizacao("listar");
	
	$filtro_url	= "";
	$filtro_sql = "";
	
	if($filtro != "")
		$filtro_url		.= "&Filtro=$filtro";
		
	if($filtro_ordem != "")
		$filtro_url		.= "&Ordem=$filtro_ordem";
		
	if($filtro_ordem_direcao != "")
		$filtro_url .= "&OrdemDirecao=$filtro_ordem_direcao";
	
	if($filtro_tipo_dado != "")
		$filtro_url .= "&TipoDado=$filtro_tipo_dado";
	
	if($filtro_localTipoDado != "")
		$filtro_url .= "&TipoDado=$filtro_localTipoDado";
		
	if($filtro_servidor_endereco != "") {
		$filtro_url .= "&ServidorEndereco=".$filtro_servidor_endereco;
		$filtro_sql .= " and (ValorParametroSistema like '%$filtro_servidor_endereco%')";
	}
	
	if($filtro_servidor_usuario != "") {
		$filtro_url .= "&ServidorUsuario=".$filtro_servidor_usuario;
		$filtro_sql .= " and (ValorParametroSistema like '%$filtro_servidor_usuario%')";
	}
	
	if($filtro_limit != "")
		$filtro_url .= "&Limit=".$filtro_limit;
		
	if($filtro_url != ""){
		$filtro_url	= "?f=t".$filtro_url;
		$filtro_url	= url_string_xsl($filtro_url,'convert',false);
	}
	
	header("content-type: text/xml");
	
	echo "<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
	echo "<?xml-stylesheet type=\"text/xsl\" href=\"listar_backup_conta_xsl.php$filtro_url\"?>";
	echo "<db>";
	
	if($filtro == "s") {
		if($filtro_limit != "") {
			$Limit	= " limit $filtro_limit";
		}
	} else {
		if($filtro_limit == "") {
			$Limit 	= " limit 0,".getCodigoInterno(7,5);
		} else {
			$Limit 	= " limit 0,".$filtro_limit;
		}
	}
	
	$sql = "SELECT 
				(SUBSTRING(IdParametroSistema,1,CHAR_LENGTH(IdParametroSistema)-1)-1) IdBackupConta
			FROM
				ParametroSistema 
			WHERE 
				IdGrupoParametroSistema = 83 AND 
				IdParametroSistema > 19 
				$filtro_sql
			GROUP BY
				IdBackupConta
			$Limit;";
	$res = mysql_query($sql,$con);
	
	if(@mysql_num_rows($res) > 0){
		while($lin = @mysql_fetch_array($res)){
			$local_IdBackupContaTemp = (int)(($lin[IdBackupConta]+1)*10);
			$i = 0;
			$sql_Temp = "SELECT 
							ValorParametroSistema,
							LoginCriacao,
							DataCriacao,
							LoginAlteracao,
							DataAlteracao
						FROM 
							ParametroSistema
						WHERE 
							IdGrupoParametroSistema = '83' AND
							IdParametroSistema > 19 AND 
							IdParametroSistema >= $local_IdBackupContaTemp AND 
							IdParametroSistema <= ".($local_IdBackupContaTemp+9).";";
			$res_Temp = mysql_query($sql_Temp,$con);
			
			if(@mysql_num_rows($res_Temp) > 0){
				while($lin_Temp = @mysql_fetch_array($res_Temp)){
					$local_LoginCriacao			= $lin_Temp[LoginCriacao];
					$local_DataCriacao			= $lin_Temp[DataCriacao];
					$local_LoginAlteracao		= $lin_Temp[LoginAlteracao];
					$local_DataAlteracao		= $lin_Temp[DataAlteracao];
					$local_ParametroValor[$i]	= $lin_Temp[ValorParametroSistema];
					$i++;
				}
				
				echo "<reg>";			
				echo "<IdBackupConta><![CDATA[$lin[IdBackupConta]]]></IdBackupConta>";
				echo "<ServidorEndereco><![CDATA[$local_ParametroValor[0]]]></ServidorEndereco>";
				echo "<ServidorUsuario><![CDATA[$local_ParametroValor[1]]]></ServidorUsuario>";
				echo "<ServidorSenha><![CDATA[$local_ParametroValor[2]]]></ServidorSenha>";
				echo "<BackupCaminho><![CDATA[$local_ParametroValor[3]]]></BackupCaminho>";
				echo "<ServidorPorta><![CDATA[$local_ParametroValor[4]]]></ServidorPorta>";
				echo "<LoginCriacao><![CDATA[$local_LoginCriacao]]></LoginCriacao>";
				echo "<DataCriacao><![CDATA[$local_DataCriacao]]></DataCriacao>";
				echo "<LoginAlteracao><![CDATA[$local_LoginAlteracao]]></LoginAlteracao>";
				echo "<DataAlteracao><![CDATA[$local_DataAlteracao]]></DataAlteracao>";
				echo "</reg>";	
			}
		}
	}
	
	echo "</db>";
?>