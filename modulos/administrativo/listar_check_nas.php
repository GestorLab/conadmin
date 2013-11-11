<?
	$localModulo		=	1;
	$localOperacao		=	58;
	$localSuboperacao	=	"R";

	set_time_limit(0);
	
	include ('../../files/conecta.php');
	include ('../../files/funcoes.php');
	include ('../../rotinas/verifica.php');
	
	$filtro					= $_POST['filtro'];
	$filtro_ordem			= $_POST['filtro_ordem'];
	$filtro_ordem_direcao	= $_POST['filtro_ordem_direcao'];
	$filtro_localTipoDado	= $_POST['filtro_tipoDado'];
	$filtro_nas				= url_string_xsl($_POST['filtro_nas'],'url',false);
	$filtro_nome			= $_POST['filtro_nome'];
	$filtro_acesso			= $_POST['filtro_acesso'];
	$filtro_ssh				= $_POST['filtro_ssh'];
	$filtro_snmp			= $_POST['filtro_snmp'];
	$filtro_limit			= $_POST['filtro_limit'];
	$filtro_url				= "";
	$filtro_sql 			= "";
	
	if($filtro_pessoa == '' && $_GET['IdPessoa'] != ''){
		$filtro_pessoa		= $_GET['IdPessoa'];
	}
	
	LimitVisualizacao("listar");
	
	if($filtro != "")
		$filtro_url	.= "&Filtro=$filtro";
	
	if($filtro_ordem != "")
		$filtro_url	.= "&Ordem=$filtro_ordem";
		
	if($filtro_ordem_direcao != "")
		$filtro_url .= "&OrdemDirecao=$filtro_ordem_direcao";
	
	if($filtro_localTipoDado != "")
		$filtro_url .= "&TipoDado=$filtro_localTipoDado";
	
	if($filtro_nas != ""){
		$filtro_url	.= "&Nas=$filtro_nas";
		$filtro_sql .=	" and nasipaddress = '$filtro_nas'";
	}
	
	if($filtro_limit!="")
		$filtro_url .= "&Limit=".$filtro_limit;
		
	if($filtro_url != ""){
		$filtro_url	= "?f=t".$filtro_url;	
		$filtro_url	= url_string_xsl($filtro_url,'convert',false);
	}
	
	header ("content-type: text/xml");
	
	echo "<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
	echo "<?xml-stylesheet type=\"text/xsl\" href=\"listar_check_nas_xsl.php$filtro_url\"?>";
	echo "<db>";
	
	if($filtro == "s"){
		if($filtro_limit != ""){
			$Limit	= " limit $filtro_limit";
		}
	}else{
		if($filtro_limit == ""){
			$Limit 	= " limit 0,".getCodigoInterno(7,5);
		}else{
			$Limit 	= " limit 0,".$filtro_limit;
		}
	}

	$DataCalcNas = incrementaData(date("Y-m-d"),-30);
	
	$sql = "SELECT
				DISTINCT
				nasipaddress Nas
			FROM
				((SELECT
					nasipaddress
				FROM
					radius.radacct
				WHERE
					acctstarttime >= '$DataCalcNas')
				UNION
					(SELECT
						nasipaddress
					FROM
						radius.radacctJornal
					WHERE
						acctstarttime >= '$DataCalcNas')) NAS
			WHERE
				1
				$filtro_sql
			ORDER BY
				Nas
			$Limit";
	$res = mysql_query($sql,$con);
	while($lin = @mysql_fetch_array($res)){
		echo "<reg>";			
		echo 	"<Nas><![CDATA[$lin[Nas]]]></Nas>";
		
		$IP = @explode(".", $lin[Nas]);
		
		for($j=0; $j<4; $j++){								
			$IP[$j] = str_pad(trim($IP[$j]), 3, "0", STR_PAD_LEFT);
		}
		
		$lin[NasTemp] = (int) $IP[0].$IP[1].$IP[2].$IP[3]; 
		
		echo 	"<NasTemp><![CDATA[$lin[NasTemp]]]></NasTemp>";

		// Testa o acesso
		$conectadoAcesso = @fsockopen($lin[Nas], getCodigoInterno(43,1), $numeroDoErro, $stringDoErro, 1);

	    if($conectadoAcesso){
			// UP
			$IdStatus = 1;
			$Status = "Ativado";
		}else{
			// DOWN
			$IdStatus = 0;
			$Status = "Desativado";
		}
		
		$StatusAcesso = $Status;

		echo 	"<Acesso><![CDATA[$IdStatus]]></Acesso>";
		echo 	"<AcessoTemp><![CDATA[$Status]]></AcessoTemp>";

		// Testa o acesso SSH
		$conectadoSSH = @fsockopen($lin[Nas], getCodigoInterno(43,2), $numeroDoErro, $stringDoErro, 1);

	    if($conectadoSSH){
			// UP
			$IdStatus = 1;
			$Status = "Ativado";
		}else{
			// DOWN
			$IdStatus = 0;
			$Status = "Desativado";
		}
		
		$StatusSSH = $Status;

		echo 	"<SSH><![CDATA[$IdStatus]]></SSH>";
		echo 	"<SSHTemp><![CDATA[$Status]]></SSHTemp>";

		// Testa se o SNMP esta ativo
		if($conectadoAcesso or $conectadoSSH){
			$conectadoSNMP = @snmpwalk($lin[Nas], getCodigoInterno(43,3), "system.sysName");
		}else{
			$conectadoSNMP = false;
		}

	    if($conectadoSNMP){
			// UP
			$IdStatus = 1;
			$Status = "Ativado";
		}else{
			// DOWN
			$IdStatus = 0;
			$Status = "Desativado";
		}
		
		$StatusSNMP = $Status;		

		echo 	"<SNMP><![CDATA[$IdStatus]]></SNMP>";
		echo 	"<SNMPTemp><![CDATA[$Status]]></SNMPTemp>";

		// Pega o Nome do Equipamento
		$Name = endArray(explode("STRING: ",$conectadoSNMP[0]));
		echo 	"<Name><![CDATA[$Name]]></Name>";
		
		$rconectado = @fsockopen($lin[Nas], getCodigoInterno(43,3), $numeroDoErro, $stringDoErro, 1);
		$latencia	= preg_replace("/^([^=]*[ =])/i", null, exec("ping -c 1 -W 1 $lin[Nas]"));
		$latencia	= explode("/",$latencia);
		$latencia	= ($latencia[0] + $latencia[1] + $latencia[2])/3;
		
		if($StatusAcesso == 'Desativado' && $StatusSSH == 'Desativado' && $StatusSNMP == 'Desativado'){
			$latencia = "";
		}
		echo 	"<Latencia><![CDATA[$latencia]]></Latencia>";

		echo "</reg>";	
	}
	echo "</db>";
?>
