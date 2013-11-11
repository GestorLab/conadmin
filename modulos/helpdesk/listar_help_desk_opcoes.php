<?
	$localModulo		=	2;
	$localOperacao		=	1;
	$localSuboperacao	=	"R";
	
	$array_operacao 	= array(  "1", "2", "3", "4");
	
	include ('../../files/conecta.php');
	include ('../../files/conecta_cntsistemas.php');
	include ('../../files/funcoes.php');
	include ('rotinas/verifica.php');
	 
	$filtro					= $_POST['filtro'];
	$filtro_ordem			= $_POST['filtro_ordem'];
	$filtro_ordem_direcao	= $_POST['filtro_ordem_direcao'];
	
	$filtro_url	= "";
	$filtro_sql = "";
	
	if($filtro != "")
		$filtro_url		.= "&Filtro=$filtro";
		
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
	echo "<?xml-stylesheet type=\"text/xsl\" href=\"listar_help_desk_opcoes_xsl.php$filtro_url\"?>";
	echo "<db>";
	
	echo "<reg>";			
	echo	"<IdOperacao>1</IdOperacao>";
	echo	"<Operacao><![CDATA[Ticket]]></Operacao>";
	echo	"<Link><![CDATA[menu_help_desk.php]]></Link>";
	echo	"<Tipo><![CDATA[Relatório]]></Tipo>";
	echo "</reg>";
	
	echo "<reg>";	
	echo	"<IdOperacao>2</IdOperacao>";		
	echo	"<Operacao><![CDATA[Ticket/Local de Abertura]]></Operacao>";
	echo	"<Link><![CDATA[menu_help_desk_local_abertura.php]]></Link>";
	echo	"<Tipo><![CDATA[Relatório]]></Tipo>";
	echo "</reg>";
	
	echo "<reg>";	
	echo	"<IdOperacao>3</IdOperacao>";		
	echo	"<Operacao><![CDATA[Ticket/Login e Data de Criação]]></Operacao>";
	echo	"<Link><![CDATA[menu_help_desk_login_data_criacao.php]]></Link>";
	echo	"<Tipo><![CDATA[Relatório]]></Tipo>";
	echo "</reg>";
	
	echo "<reg>";	
	echo	"<IdOperacao>4</IdOperacao>";		
	echo	"<Operacao><![CDATA[Ticket/Conteúdo Histórico]]></Operacao>";
	echo	"<Link><![CDATA[menu_help_desk_conteudo_historico.php]]></Link>";
	echo	"<Tipo><![CDATA[Relatório]]></Tipo>";
	echo "</reg>";
	
	echo "</db>";
?>