<?
	$localModulo		= 1;
	$localOperacao		= 184;
	$localSuboperacao	= "R";
	
	# array( CDA, Pessoa Solicitação, CDA/Acessos ao CDA )
	$array_operacao = array( "184", "70", "185" );
	
	include('../../files/conecta.php');
	include('../../files/funcoes.php');
	include('../../rotinas/verifica_menu.php');
	
	$filtro					= $_POST['filtro'];
	$filtro_ordem			= $_POST['filtro_ordem'];
	$filtro_ordem_direcao	= $_POST['filtro_ordem_direcao'];
	
	$filtro_url = "";
	$filtro_sql = "";
	
	if($filtro != "")
		$filtro_url .= "&Filtro=$filtro";
		
	if($filtro_ordem != "")
		$filtro_url .= "&Ordem=$filtro_ordem";
		
	if($filtro_ordem_direcao != "")
		$filtro_url .= "&OrdemDirecao=$filtro_ordem_direcao";
	
	if($filtro_localTipoDado != "")
		$filtro_url .= "&TipoDado=$filtro_localTipoDado";
		
	if($filtro_url != ""){
		$filtro_url = "?f=t".$filtro_url;
		$filtro_url = url_string_xsl($filtro_url,'convert');
	}
	
	header("content-type: text/xml");
	
	echo "<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
	echo "<?xml-stylesheet type=\"text/xsl\" href=\"listar_cda_opcoes_xsl.php$filtro_url\"?>";
	echo "<db>";
	
	echo "<reg>";
	echo	"<IdOperacao>1</IdOperacao>";
	echo	"<Operacao><![CDATA[Solicitações de atualização de dados]]></Operacao>";
	echo	"<Link><![CDATA[menu_pessoa_solicitacao.php]]></Link>";
	echo	"<Tipo><![CDATA[Relatório]]></Tipo>";
	echo "</reg>";
	
	echo "<reg>";
	echo	"<IdOperacao>2</IdOperacao>";
	echo	"<Operacao><![CDATA[Acessos ao CDA]]></Operacao>";
	echo	"<Link><![CDATA[menu_cda_acessos.php]]></Link>";
	echo	"<Tipo><![CDATA[Gráfico]]></Tipo>";
	echo "</reg>";
	
	echo "</db>";
?>