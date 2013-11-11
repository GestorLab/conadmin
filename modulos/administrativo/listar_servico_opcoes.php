<?
	$localModulo		=	1;
	$localOperacao		=	2;
	$localSuboperacao	=	"R";		
	
	//array( Contrato , Contrato Local Cob., Contrato Cidade, Contrato Desconto, Contrato sem Ccbrancça, Contrato Tipo/Venc/Assinat, Contrato Tipo Vigencia, COntrato Novo, Contrato Parametro, Contrato Pessoa, Contrato/Histórico (Status/Data)/Contrato Sem Cobrança Aberto, Contrato/Tipo Pessoa, Contrato sem Vigência, Contrato/Datas, Contrato/Serviço/CFOP, Contrato/Migração, Contrato/Loja, Contrato/Serviço, Contrato/Status/Período, Contrato/Primeiro Contrato, Contrato/Períodos Pagos, Contrato/Mapeamento de cliente
	$array_operacao = array(  "2" , "87", "88", "73", "67", "89", "40", "93", "97", "98", "100", "101", "102", "104", "105", "106","118","122", "125", "127", "129", "130", "133", "144", "168", "170", "175", "188","193", "194", "195");
	
	include ('../../files/conecta.php');
	include ('../../files/funcoes.php');
	include ('../../rotinas/verifica_menu.php');
	 
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
	echo "<?xml-stylesheet type=\"text/xsl\" href=\"listar_servico_opcoes_xsl.php$filtro_url\"?>";
	echo "<db>";
	
	echo "<reg>";			
	echo	"<IdOperacao>1</IdOperacao>";
	echo	"<Operacao><![CDATA[Serviço]]></Operacao>";
	echo	"<Link><![CDATA[menu_servico.php]]></Link>";
	echo	"<Tipo><![CDATA[Relatório]]></Tipo>";
	echo "</reg>";	
	
	echo "<reg>";		
	echo	"<IdOperacao>2</IdOperacao>";	
	echo	"<Operacao><![CDATA[Serviço/Ticket Médio]]></Operacao>";
	echo	"<Link><![CDATA[menu_servico_ticket_medio.php]]></Link>";
	echo	"<Tipo><![CDATA[Relatório]]></Tipo>";
	echo "</reg>";
	
	echo "</db>";
?>
