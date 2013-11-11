<?
	$localModulo		=	1;
	$localOperacao		=	26;
	$localSuboperacao	=	"R";		
	
	//array(Ordem de Serviço, Ordem Serviço/Agendamento, Ordem de Serviço/Data Cadastro, Ordem Serviço/Lote Repasse Ter,Ordem Serviço/Lote Repasse Ter, Ordem Servico/Localidade, Ordem de Servico/Tipo Pessoa, Ordem de Serviço/Marcador, Ordem Serviço/Serviço);
	$array_operacao = array(  "26", "91", "123", "132", "134", "156","163", "191", "192") ;	

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
	echo "<?xml-stylesheet type=\"text/xsl\" href=\"listar_ordem_servico_opcoes_xsl.php$filtro_url\"?>";
	echo "<db>";
	
	echo "<reg>";			
	echo	"<IdOperacao>1</IdOperacao>";
	echo	"<Operacao><![CDATA[Ordem Servico]]></Operacao>";
	echo	"<Link><![CDATA[menu_ordem_servico.php]]></Link>";
	echo	"<Tipo><![CDATA[Relatório]]></Tipo>";
	echo "</reg>";
	
	echo "<reg>";	
	echo	"<IdOperacao>2</IdOperacao>";		
	echo	"<Operacao><![CDATA[Ordem Serviço/Agendamento]]></Operacao>";
	echo	"<Link><![CDATA[menu_ordem_servico_avancado.php]]></Link>";
	echo	"<Tipo><![CDATA[Relatório]]></Tipo>";
	echo "</reg>";
	
	echo "<reg>";	
	echo	"<IdOperacao>3</IdOperacao>";		
	echo	"<Operacao><![CDATA[Ordem de Serviço/Tipo]]></Operacao>";
	echo	"<Link><![CDATA[menu_ordem_servico_tipo.php]]></Link>";
	echo	"<Tipo><![CDATA[Relatório]]></Tipo>";
	echo "</reg>";
	
	echo "<reg>";	
	echo	"<IdOperacao>4</IdOperacao>";		
	echo	"<Operacao><![CDATA[Ordem de Serviço/Status]]></Operacao>";
	echo	"<Link><![CDATA[menu_ordem_servico_status.php]]></Link>";
	echo	"<Tipo><![CDATA[Gráfico]]></Tipo>";
	echo "</reg>";
	
	echo "<reg>";	
	echo	"<IdOperacao>5</IdOperacao>";		
	echo	"<Operacao><![CDATA[Ordem de Serviço/Data Cadastro]]></Operacao>";
	echo	"<Link><![CDATA[menu_ordem_servico_data_cadastro.php]]></Link>";
	echo	"<Tipo><![CDATA[Relatório]]></Tipo>";
	echo "</reg>";
	
	echo "<reg>";	
	echo	"<IdOperacao>6</IdOperacao>";		
	echo	"<Operacao><![CDATA[Ordem Serviço/Lote Repasse Terceiro]]></Operacao>";
	echo	"<Link><![CDATA[menu_ordem_servico_lote_repasse_terceiro.php]]></Link>";
	echo	"<Tipo><![CDATA[Relatório]]></Tipo>";
	echo "</reg>";
	
	echo "<reg>";	
	echo	"<IdOperacao>7</IdOperacao>";		
	echo	"<Operacao><![CDATA[Ordem Serviço/Pessoa/Período]]></Operacao>";
	echo	"<Link><![CDATA[menu_ordem_servico_pessoa_periodo.php]]></Link>";
	echo	"<Tipo><![CDATA[Relatório]]></Tipo>";
	echo "</reg>";
	
	echo "<reg>";	
	echo	"<IdOperacao>8</IdOperacao>";		
	echo	"<Operacao><![CDATA[Ordem Serviço/Localidade]]></Operacao>";
	echo	"<Link><![CDATA[menu_ordem_servico_localidade.php]]></Link>";
	echo	"<Tipo><![CDATA[Relatório]]></Tipo>";
	echo "</reg>";
	
	echo "<reg>";	
	echo	"<IdOperacao>9</IdOperacao>";		
	echo	"<Operacao><![CDATA[Ordem Serviço/Tipo Pessoa]]></Operacao>";
	echo	"<Link><![CDATA[menu_ordem_servico_tipo_pessoa.php]]></Link>";
	echo	"<Tipo><![CDATA[Relatório]]></Tipo>";
	echo "</reg>";
	
	echo "<reg>";	
	echo	"<IdOperacao>10</IdOperacao>";		
	echo	"<Operacao><![CDATA[Ordem Serviço/Marcador]]></Operacao>";
	echo	"<Link><![CDATA[menu_ordem_servico_marcador.php]]></Link>";
	echo	"<Tipo><![CDATA[Gráfico]]></Tipo>";
	echo "</reg>";
	
	echo "<reg>";	
	echo	"<IdOperacao>11</IdOperacao>";		
	echo	"<Operacao><![CDATA[Ordem de Serviço/Qtd. Abertos Mês]]></Operacao>";
	echo	"<Link><![CDATA[menu_ordem_servico_qtd_aberto_mes.php]]></Link>";
	echo	"<Tipo><![CDATA[Gráfico]]></Tipo>";
	echo "</reg>";
	
/*	
	Ainda não foi concluido o relatório
	echo "<reg>"; 	
	echo	"<IdOperacao>7</IdOperacao>";		
	echo	"<Operacao><![CDATA[Ordem Serviço/Lote Repasse Terceiro (Parcela Quitada)]]></Operacao>";
	echo	"<Link><![CDATA[menu_ordem_servico_lote_repasse_terceiro_parcela_quitada.php]]></Link>";
	echo	"<Tipo><![CDATA[Relatório]]></Tipo>";
	echo "</reg>";
*/			
	echo "</db>";
?>