<?
	$localModulo		= 1;
	$localOperacao		= 167;
	$localSuboperacao	= "R";
	
	include('../../files/conecta.php');
	include('../../files/funcoes.php');
	include('../../rotinas/verifica.php');
	
	$local_IdLoja					= $_SESSION['IdLoja'];
	$filtro							= $_POST['filtro'];
	$filtro_ordem					= $_POST['filtro_ordem'];
	$filtro_ordem_direcao			= $_POST['filtro_ordem_direcao'];
	$filtro_tipo_dado				= $_POST['filtro_tipoDado'];
	$filtro_titulo					= url_string_xsl($_POST['filtro_titulo'],'url',false);
	$filtro_assunto					= url_string_xsl($_POST['filtro_assunto'],'url',false);
	$filtro_id_status				= $_POST['filtro_id_status'];
	$filtro_limit					= $_POST['filtro_limit'];
	
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
		
	if($filtro_titulo != "") {
		$filtro_url .= "&Titulo=".$filtro_titulo;
		$filtro_sql .= " and TipoMensagem.Titulo like '%$filtro_titulo%'";
	}
	
	if($filtro_assunto != "") {
		$filtro_url .= "&Assunto=".$filtro_assunto;
		$filtro_sql .= " and TipoMensagem.Assunto like '%$filtro_assunto%'";
	}
	
	if($filtro_id_status != "") {
		$filtro_url .= "&IdStatus=".$filtro_id_status;
		$filtro_sql .= " and TipoMensagem.IdStatus = '$filtro_id_status'";
	}
	
	if($filtro_limit != "")
		$filtro_url .= "&Limit=".$filtro_limit;
		
	if($filtro_url != ""){
		$filtro_url	= "?f=t".$filtro_url;
		$filtro_url	= url_string_xsl($filtro_url,'convert',false);
	}
	
	header("content-type: text/xml");
	
	echo "<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
	echo "<?xml-stylesheet type=\"text/xsl\" href=\"listar_tipo_mensagem_xsl.php$filtro_url\"?>";
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
	$sql = "select
				TipoMensagem.IdTipoMensagem,
				TipoMensagem.IdTemplate,
				TipoMensagem.IdContaEmail,
				substring(TipoMensagem.Titulo,1,22) Titulo,
				substring(TipoMensagem.Assunto,1,44) Assunto,
				IF(ContaEmail.IdContaEmail != '', concat(ContaEmail.DescricaoContaEmail,' (',ContaEmail.Usuario,')'), ContaSMS.DescricaoContaSMS) DescricaoConta,
				TipoMensagem.IdStatus,
				TipoMensagem.IdContaSMS
			from 
				TipoMensagem
					left join ContaEmail on (TipoMensagem.IdLoja = ContaEmail.IdLoja and TipoMensagem.IdContaEmail = ContaEmail.IdContaEmail)
					left join ContaSMS on (TipoMensagem.IdLoja = ContaSMS.IdLoja and TipoMensagem.IdContaSMS = ContaSMS.IdContaSMS)
			where
				TipoMensagem.IdLoja = $local_IdLoja
				$filtro_sql
			order by
				TipoMensagem.IdTipoMensagem desc
			$Limit";
	$lin[DescricaoConta] = "";
	$res = mysql_query($sql,$con);
	while($lin = @mysql_fetch_array($res)) {
		$lin[Status] = getParametroSistema(227,$lin[IdStatus]);
		
		$sqlTipo =  "Select
					ContaSMS.IdContaSMS,
					ContaSMS.DescricaoContaSMS,
					ContaSMS.IdOperadora,
					ContaSMS.IdStatus
				From
					ContaSMS
				where
					IdLoja = $local_IdLoja and 
					ContaSMS.IdContaSMS = $lin[IdContaEmail]";
		$resTipo = mysql_query($sqlTipo, $con);
		$linTipo = mysql_fetch_array($resTipo);
		if($lin[IdTemplate] == 4){
			$lin[DescricaoConta] = $linTipo[DescricaoContaSMS];
		}

		echo "<reg>";			
		echo 	"<IdTipoMensagem>$lin[IdTipoMensagem]</IdTipoMensagem>";
		echo 	"<Titulo><![CDATA[$lin[Titulo]]]></Titulo>";
		echo 	"<Assunto><![CDATA[$lin[Assunto]]]></Assunto>";
		echo 	"<IdStatus><![CDATA[$lin[IdStatus]]]></IdStatus>";
		echo 	"<Status><![CDATA[$lin[Status]]]></Status>";
		echo 	"<DescricaoConta><![CDATA[$lin[DescricaoConta]]]></DescricaoConta>";
		echo "</reg>";	
	}
	
	echo "</db>";
?>