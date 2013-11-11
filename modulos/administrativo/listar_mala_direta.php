<?
	$localModulo		= 1;
	$localOperacao		= 155;
	$localSuboperacao	= "R";		
	
	include('../../files/conecta.php');
	include('../../files/funcoes.php');
	include('../../rotinas/verifica.php');
	
	$local_IdLoja				= $_SESSION['IdLoja'];
	$filtro						= $_POST['filtro'];
	$filtro_ordem				= $_POST['filtro_ordem'];
	$filtro_ordem_direcao		= $_POST['filtro_ordem_direcao'];
	$filtro_tipo_dado			= $_POST['filtro_tipoDado'];
	$filtro_descricao			= url_string_xsl($_POST['filtro_descricao'],'url',false);
	$filtro_id_tipo_conteudo	= $_POST['filtro_id_tipo_conteudo'];
	$filtro_id_tipo_mensagem	= $_POST['filtro_id_tipo_mensagem'];
	$filtro_id_status			= $_POST['filtro_id_status'];
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
		
	if($filtro_descricao!="") {
		$filtro_url .= "&Descricao=" . $filtro_descricao;
		$filtro_sql .= " AND (MalaDireta.DescricaoMalaDireta LIKE '%$filtro_descricao%')";
	}
	if($filtro_id_tipo_conteudo != '') {
		$filtro_url .= "&IdTipoConteudo=" . $filtro_id_tipo_conteudo;
		$filtro_sql .= " AND MalaDireta.IdTipoConteudo=" . $filtro_id_tipo_conteudo;
	}
	if($filtro_id_tipo_mensagem != '') {
		$filtro_url .= "&IdTipoMensagem=" . $filtro_id_tipo_mensagem;
		$filtro_sql .= " AND MalaDireta.IdTipoMensagem=" . $filtro_id_tipo_mensagem;
	}
	if($filtro_id_status != '') {
		$filtro_url .= "&IdStatus=" . $filtro_id_status;
		$filtro_sql .= " and MalaDireta.IdStatus=" . $filtro_id_status;
	}
	
	if($filtro_limit!="")
		$filtro_url .= "&Limit=" . $filtro_limit;
		
	if($filtro_url != ""){
		$filtro_url	= "?f=t".$filtro_url;
		$filtro_url	= url_string_xsl($filtro_url,'convert',false);
	}
	
	header("content-type: text/xml");
	
	echo "<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
	echo "<?xml-stylesheet type=\"text/xsl\" href=\"listar_mala_direta_xsl.php$filtro_url\"?>";
	echo "<db>";
	
	if($filtro == "s") {
		if($filtro_limit != "") {
			$Limit	= " limit $filtro_limit";
		}
	} else {
		if($filtro_limit == "") {
			$Limit 	= " limit 0," . getCodigoInterno(7,5);
		} else {
			$Limit 	= " limit 0," . $filtro_limit;
		}
	}
	
	$sql = "SELECT
				MalaDireta.IdMalaDireta,
				MalaDireta.DescricaoMalaDireta, 
				MalaDireta.IdTipoMensagem,
				MalaDireta.IdTipoConteudo,
				MalaDireta.IdStatus
			FROM 
				MalaDireta
			WHERE
				MalaDireta.IdLoja = $local_IdLoja
				$filtro_sql
			order by
				MalaDireta.IdMalaDireta desc
			$Limit;";
	$res = mysql_query($sql,$con);
	while($lin = @mysql_fetch_array($res)) {
		$lin[Status]		= getParametroSistema(201, $lin[IdStatus]);
		$lin[TipoConteudo]	= getParametroSistema(202, $lin[IdTipoConteudo]);
		
		echo "<reg>";			
		echo 	"<IdMalaDireta>$lin[IdMalaDireta]</IdMalaDireta>";
		echo 	"<DescricaoMalaDireta><![CDATA[$lin[DescricaoMalaDireta]]]></DescricaoMalaDireta>";
		echo	"<IdTipoMensagem><![CDATA[$lin[IdTipoMensagem]]]></IdTipoMensagem>";
		echo	"<IdTipoConteudo><![CDATA[$lin[IdTipoConteudo]]]></IdTipoConteudo>";
		echo	"<TipoConteudo><![CDATA[$lin[TipoConteudo]]]></TipoConteudo>";
		echo 	"<IdStatus><![CDATA[$lin[IdStatus]]]></IdStatus>";
		echo	"<Status><![CDATA[$lin[Status]]]></Status>";
		echo "</reg>";	
	}
	
	echo "</db>";
?>