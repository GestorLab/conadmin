<?
	$localModulo		=	1;
	$localOperacao		=	31;
	$localSuboperacao	=	"R";		
	
	//array( Pessoa , Contrato, Lanc. Financ., Contas a Receber, Email Enviados, Conta Eventual)
	$array_operacao 	= array(  "1", "2", "18", "17", "31", "27", "94") ;

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
	echo "<?xml-stylesheet type=\"text/xsl\" href=\"listar_conta_eventual_opcoes_xsl.php$filtro_url\"?>";
	echo "<db>";
	
	echo "<reg>";			
	echo	"<IdOperacao>1</IdOperacao>";
	echo	"<Operacao><![CDATA[Conta Eventual]]></Operacao>";
	echo	"<Link><![CDATA[menu_conta_eventual.php]]></Link>";
	echo	"<Tipo><![CDATA[Relatório]]></Tipo>";
	echo "</reg>";
	
	echo "<reg>";	
	echo	"<IdOperacao>2</IdOperacao>";		
	echo	"<Operacao><![CDATA[Conta Eventual/Contas a Receber]]></Operacao>";
	echo	"<Link><![CDATA[menu_conta_eventual_avancado.php]]></Link>";
	echo	"<Tipo><![CDATA[Relatório]]></Tipo>";
	echo "</reg>";
		
	echo "</db>";
?>
