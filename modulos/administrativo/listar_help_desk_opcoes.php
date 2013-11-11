<?
	$localModulo		=	1;
	$localOperacao		=	65;
	$localSuboperacao	=	"R";
	
	//array( Ticket, Login e Data de Criação, Conteúdo Histórico, Usuário Atendimento, Pendente Atualização - Reincidente, Ano, Usuário Atendimento Mês, Previsão
	$array_operacao = array(  "65", "124", "139", "146", "148", "149", "150", "161") ;	
	
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
	echo	"<Operacao><![CDATA[Ticket/Login e Data de Criação]]></Operacao>";
	echo	"<Link><![CDATA[menu_help_desk_login_data_criacao.php]]></Link>";
	echo	"<Tipo><![CDATA[Relatório]]></Tipo>";
	echo "</reg>";
	
	echo "<reg>";	
	echo	"<IdOperacao>3</IdOperacao>";		
	echo	"<Operacao><![CDATA[Ticket/Conteúdo Histórico]]></Operacao>";
	echo	"<Link><![CDATA[menu_help_desk_conteudo_historico.php]]></Link>";
	echo	"<Tipo><![CDATA[Relatório]]></Tipo>";
	echo "</reg>";
	
	echo "<reg>";	
	echo	"<IdOperacao>4</IdOperacao>";		
	echo	"<Operacao><![CDATA[Ticket/Usuário Atendimento]]></Operacao>";
	echo	"<Link><![CDATA[menu_help_desk_usuario_atendimento.php]]></Link>";
	echo	"<Tipo><![CDATA[Relatório]]></Tipo>";
	echo "</reg>";
	
	echo "<reg>";	
	echo	"<IdOperacao>5</IdOperacao>";		
	echo	"<Operacao><![CDATA[Ticket/Pendente Atualização - Reincidente]]></Operacao>";
	echo	"<Link><![CDATA[menu_help_desk_pendente_atualizacao_reincidente.php]]></Link>";
	echo	"<Tipo><![CDATA[Relatório]]></Tipo>";
	echo "</reg>";
	
	echo "<reg>";	
	echo	"<IdOperacao>6</IdOperacao>";		
	echo	"<Operacao><![CDATA[Ticket/Ano]]></Operacao>";
	echo	"<Link><![CDATA[menu_help_desk_ano.php]]></Link>";
	echo	"<Tipo><![CDATA[Gráfico]]></Tipo>";
	echo "</reg>";
	
	echo "<reg>";	
	echo	"<IdOperacao>7</IdOperacao>";		
	echo	"<Operacao><![CDATA[Ticket/Mês]]></Operacao>";
	echo	"<Link><![CDATA[menu_help_desk_mes.php]]></Link>";
	echo	"<Tipo><![CDATA[Gráfico]]></Tipo>";
	echo "</reg>";
	
	echo "<reg>";	
	echo	"<IdOperacao>8</IdOperacao>";		
	echo	"<Operacao><![CDATA[Ticket/Previsão]]></Operacao>";
	echo	"<Link><![CDATA[menu_help_desk_previsao.php]]></Link>";
	echo	"<Tipo><![CDATA[Relatório]]></Tipo>";
	echo "</reg>";

	echo "<reg>";	
	echo	"<IdOperacao>9</IdOperacao>";		
	echo	"<Operacao><![CDATA[Ticket/Para Impressão]]></Operacao>";
	echo	"<Link><![CDATA[menu_help_desk_impressao.php]]></Link>";
	echo	"<Tipo><![CDATA[Relatório]]></Tipo>";
	echo "</reg>";
	
	echo "<reg>";	
	echo	"<IdOperacao>10</IdOperacao>";		
	echo	"<Operacao><![CDATA[Ticket/Finalização]]></Operacao>";
	echo	"<Link><![CDATA[menu_help_desk_finalizacao.php]]></Link>";
	echo	"<Tipo><![CDATA[Relatório]]></Tipo>";
	echo "</reg>";
	
	echo "</db>";
?>