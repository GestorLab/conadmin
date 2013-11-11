<?
	$localModulo		=	1;
	$localOperacao		=	75;
	$localSuboperacao	=	"R";
	
	include ('../../files/conecta.php');
	include ('../../files/funcoes.php');
	include ('../../rotinas/verifica.php');
	
	$IdLoja					= $_SESSION['IdLoja'];	
	$Login					= $_SESSION['Login'];	
	$filtro					= $_POST['filtro'];
	$filtro_ordem			= $_POST['filtro_ordem'];
	$filtro_ordem_direcao	= $_POST['filtro_ordem_direcao'];
	$filtro_localTipoDado	= $_POST['filtro_tipoDado'];
	$filtro_nome			= url_string_xsl($_POST['filtro_nome'],'url',false);
	$filtro_data			= url_string_xsl($_POST['filtro_data'],'url',false);
	$filtro_navegador		= $_POST['filtro_navegador'];
	$filtro_limit			= $_POST['filtro_limit'];
	
	$filtro_url	= "";
	$filtro_sql = "";
	
	LimitVisualizacao("listar");
	
	if($filtro != "")
		$filtro_url		.= "&Filtro=$filtro";
		
	if($filtro_ordem != "")
		$filtro_url		.= "&Ordem=$filtro_ordem";
		
	if($filtro_ordem_direcao != "")
		$filtro_url .= "&OrdemDirecao=$filtro_ordem_direcao";
	
	if($filtro_localTipoDado != "")
		$filtro_url .= "&TipoDado=$filtro_localTipoDado";
		
	if($filtro_nome!=''){
		$filtro_url .= "&Nome=$filtro_nome";
		$filtro_nome = str_replace("'", "\'", $filtro_nome);
		$filtro_sql .=	" and (Pessoa.Nome like '%$filtro_nome%' or Pessoa.RazaoSocial like '%$filtro_nome%')";
	}
		
	if($filtro_data!=""){
		$filtro_url .= "&Data=".$filtro_data;
		$filtro_data = dataConv($filtro_data,'d/m/Y','Y-m-d');
		$filtro_sql .= " and LogAcessoCDA.DataHora like '$filtro_data%'";
	}
	
	if($filtro_navegador!=""){
		$filtro_url .= "&Navegador=".$filtro_navegador;
		$filtro_sql .= " and LogAcessoCDA.IdNavegador = '$filtro_navegador'";
	}
	
	if($filtro_limit!="")
		$filtro_url .= "&Limit=".$filtro_limit;
		
	if($filtro_url != ""){
		$filtro_url	= "?f=t".$filtro_url;
		$filtro_url	= url_string_xsl($filtro_url,'convert',false);
	}

	header ("content-type: text/xml");
	
	echo "<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
	echo "<?xml-stylesheet type=\"text/xsl\" href=\"listar_log_acesso_cda_xsl.php$filtro_url\"?>";
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
	
	$sql	=	"select 
					LogAcessoCDA.IdLogAcesso,
					Pessoa.TipoPessoa,
					substr(Pessoa.Nome,1,35) Nome, 
					substr(Pessoa.RazaoSocial,1,35) RazaoSocial,
					LogAcessoCDA.IP,
					Navegador.Navegador,
					LogAcessoCDA.DataHora
				 from 
				 	LogAcessoCDA,
					Pessoa,
					(
						select 
							IdParametroSistema IdNavegador, 
							ValorParametroSistema Navegador 
						from 
							ParametroSistema 
						where 
							IdGrupoParametroSistema = 89
					) Navegador
				where
					LogAcessoCDA.IdPessoa = Pessoa.IdPessoa and
					Navegador.IdNavegador = LogAcessoCDA.IdNavegador
				    $filtro_sql	
				order by
					LogAcessoCDA.IdLogAcesso desc,
					DataHora desc 
				$Limit";
	$res	=	mysql_query($sql,$con);
	while($lin	=	mysql_fetch_array($res)){
		$lin[DataHoraTemp]	=	dataConv($lin[DataHora],'Y-m-d H:i:s','d/m/Y H:i:s');
		$lin[DataHora]		=	dataConv($lin[DataHora],'Y-m-d H:i:s','YmdHis');
		
		if($lin[TipoPessoa]=='1'){
			$lin[Nome]	=	$lin[trim(getCodigoInterno(3,24))];	
		}
		
		echo "<reg>";	
		echo 	"<IdLogAcesso><![CDATA[$lin[IdLogAcesso]]]></IdLogAcesso>";
		echo 	"<Nome><![CDATA[$lin[Nome]]]></Nome>";	
		echo 	"<IP><![CDATA[$lin[IP]]]></IP>";
		echo 	"<Navegador><![CDATA[$lin[Navegador]]]></Navegador>";	
		echo 	"<DataHora><![CDATA[$lin[DataHora]]]></DataHora>";	
		echo 	"<DataHoraTemp><![CDATA[$lin[DataHoraTemp]]]></DataHoraTemp>";
		echo "</reg>";	
	}
	
	echo "</db>";
?>

