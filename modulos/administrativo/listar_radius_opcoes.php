<?
	$localModulo		=	1;	
	
	include ('../../files/conecta.php');
	include ('../../files/funcoes.php');
	include ('../../rotinas/verifica.php');

	$local_IdLoja		= $_SESSION["IdLoja"];
	 
	$filtro					= $_POST['filtro'];
	$filtro_ordem			= $_POST['filtro_ordem'];
	$filtro_ordem_direcao	= $_POST['filtro_ordem_direcao'];
	
	$filtro_sql = "";
	
	if($filtro_ordem != "")
		$filtro_url		.= "&Ordem=$filtro_ordem";
		
	if($filtro_ordem_direcao != "")
		$filtro_url .= "&OrdemDirecao=$filtro_ordem_direcao";
	
	if($filtro_localTipoDado != "")
		$filtro_url .= "&TipoDado=$filtro_localTipoDado";
		
	if($filtro_url != ""){
		$filtro_url	= "?f=t".$filtro_url;
		$filtro_url	= url_string_xsl($filtro_url,'convert');
	}

		
	header ("content-type: text/xml");
	
	echo "<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
	echo "<?xml-stylesheet type=\"text/xsl\" href=\"listar_radius_opcoes_xsl.php$filtro_url\"?>";
	echo "<db>";
	
	
	echo "<reg>";			
	echo	"<Operacao><![CDATA[Gráfico de Utilização]]></Operacao>";
	echo	"<Link><![CDATA[menu_radius_tempo_conexao.php]]></Link>";
	echo	"<Tipo><![CDATA[Gráfico]]></Tipo>";
	echo "</reg>";	
	
	echo "<reg>";			
	echo	"<Operacao><![CDATA[Relatório de Utilização]]></Operacao>";
	echo	"<Link><![CDATA[menu_radius_conexao.php]]></Link>";
	echo	"<Tipo><![CDATA[Relatório]]></Tipo>";
	echo "</reg>";
	
	echo "<reg>";			
	echo	"<Operacao><![CDATA[Usuários/Período]]></Operacao>";
	echo	"<Link><![CDATA[menu_radius_usuario_periodo.php]]></Link>";
	echo	"<Tipo><![CDATA[Relatório]]></Tipo>";
	echo "</reg>";

	$sql = "select
				DescricaoCodigoInterno,
				ValorCodigoInterno
			from
				CodigoInterno
			where
				IdGrupoCodigoInterno = 10000 and
				IdLoja = $local_IdLoja and
				IdCodigoInterno >= 2000 and
				IdCodigoInterno <= 2999
			order by
				ValorCodigoInterno";
	$res = mysql_query($sql,$con);
	while($lin = mysql_fetch_array($res)){
		echo "<reg>";			
		echo	"<Operacao><![CDATA[$lin[DescricaoCodigoInterno]]]></Operacao>";
		echo	"<Link><![CDATA[menu_log_radius.php?Ano=$lin[ValorCodigoInterno]]]></Link>";
		echo	"<Tipo><![CDATA[Relatório]]></Tipo>";
		echo "</reg>";
	}

	echo "<reg>";			
	echo	"<Operacao><![CDATA[Log Radius (".date("Y").")]]></Operacao>";
	echo	"<Link><![CDATA[menu_log_radius.php?Ano=".date("Y")."]]></Link>";
	echo	"<Tipo><![CDATA[Relatório]]></Tipo>";
	echo "</reg>";

	echo "<reg>";			
	echo	"<Operacao><![CDATA[Conexão Ativa]]></Operacao>";
	echo	"<Link><![CDATA[menu_radius_conexao_ativa.php]]></Link>";
	echo	"<Tipo><![CDATA[Relatório]]></Tipo>";
	echo "</reg>";
	
	OpcoesExtrasRelatorio(getCodigoInterno(10003,1));
	
	echo "</db>";
?>