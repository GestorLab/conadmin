<?
	$localModulo		=	1;
	$localOperacao		=	1;
	$localSuboperacao	=	"R";		
	
	//array( Pessoa , Pessoa Data Nasc, Pessoa Forma Cob, Pessoa telefone, Pessoa/Data de Cadastro, Pessoa/Dados Cadastrais, Pessoa/Com Contrato, Pessoa/Sem Contrato
	$array_operacao = array(  "1", "85", "29", "86", "103","119", "153", "154", "174") ;	

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
	echo "<?xml-stylesheet type=\"text/xsl\" href=\"listar_pessoa_opcoes_xsl.php$filtro_url\"?>";
	echo "<db>";
	
	echo "<reg>";			
	echo	"<IdOperacao>1</IdOperacao>";
	echo	"<Operacao><![CDATA[Pessoa]]></Operacao>";
	echo	"<Link><![CDATA[menu_pessoa.php]]></Link>";
	echo	"<Tipo><![CDATA[Relatório]]></Tipo>";
	echo "</reg>";
	
	echo "<reg>";			
	echo	"<IdOperacao>2</IdOperacao>";
	echo	"<Operacao><![CDATA[Pessoa/Data Nascimento]]></Operacao>";
	echo	"<Link><![CDATA[menu_pessoa_data_nascimento.php]]></Link>";
	echo	"<Tipo><![CDATA[Relatório]]></Tipo>";
	echo "</reg>";	
	
	echo "<reg>";		
	echo	"<IdOperacao>3</IdOperacao>";	
	echo	"<Operacao><![CDATA[Pessoa/Telefones]]></Operacao>";
	echo	"<Link><![CDATA[menu_pessoa_telefone.php]]></Link>";
	echo	"<Tipo><![CDATA[Relatório]]></Tipo>";
	echo "</reg>";	
	
	echo "<reg>";	
	echo	"<IdOperacao>4</IdOperacao>";		
	echo	"<Operacao><![CDATA[Pessoa/Data Cadastro]]></Operacao>";
	echo	"<Link><![CDATA[menu_pessoa_data_cadastro.php]]></Link>";
	echo	"<Tipo><![CDATA[Relatório]]></Tipo>";
	echo "</reg>";
	
	echo "<reg>";	
	echo	"<IdOperacao>5</IdOperacao>";		
	echo	"<Operacao><![CDATA[Pessoa/Dados Cadastrais]]></Operacao>";
	echo	"<Link><![CDATA[menu_pessoa_dados_cadastrais.php]]></Link>";
	echo	"<Tipo><![CDATA[Relatório]]></Tipo>";
	echo "</reg>";
	
	echo "<reg>";			
	echo	"<IdOperacao>6</IdOperacao>";
	echo	"<Operacao><![CDATA[Pessoa/Com Contrato]]></Operacao>";
	echo	"<Link><![CDATA[menu_pessoa_com_contrato.php]]></Link>";
	echo	"<Tipo><![CDATA[Relatório]]></Tipo>";
	echo "</reg>";
	
	echo "<reg>";			
	echo	"<IdOperacao>7</IdOperacao>";
	echo	"<Operacao><![CDATA[Pessoa/Sem Contrato]]></Operacao>";
	echo	"<Link><![CDATA[menu_pessoa_sem_contrato.php]]></Link>";
	echo	"<Tipo><![CDATA[Relatório]]></Tipo>";
	echo "</reg>";
	
	echo "<reg>";			
	echo	"<IdOperacao>8</IdOperacao>";
	echo	"<Operacao><![CDATA[Pessoa/Forma de Cobrança]]></Operacao>";
	echo	"<Link><![CDATA[menu_pessoa_forma_cobranca.php]]></Link>";
	echo	"<Tipo><![CDATA[Relatório]]></Tipo>";
	echo "</reg>";
	
	echo "<reg>";			
	echo	"<IdOperacao>9</IdOperacao>";
	echo	"<Operacao><![CDATA[Pessoa/E-mail]]></Operacao>";
	echo	"<Link><![CDATA[menu_pessoa_email.php]]></Link>";
	echo	"<Tipo><![CDATA[Relatório]]></Tipo>";
	echo "</reg>";
	
	echo "<reg>";			
	echo	"<IdOperacao>10</IdOperacao>";
	echo	"<Operacao><![CDATA[Pessoa/Cadastro Resumido]]></Operacao>";
	echo	"<Link><![CDATA[menu_pessoa_cadastro_resumido.php]]></Link>";
	echo	"<Tipo><![CDATA[Relatório]]></Tipo>";
	echo "</reg>";
	
	echo "<reg>";			
	echo	"<IdOperacao>11</IdOperacao>";
	echo	"<Operacao><![CDATA[Pessoa/Forma Aviso Cobrança]]></Operacao>";
	echo	"<Link><![CDATA[menu_pessoa_aviso_cobraca.php]]></Link>";
	echo	"<Tipo><![CDATA[Relatório]]></Tipo>";
	echo "</reg>";
	
	echo "<reg>";			
	echo	"<IdOperacao>12</IdOperacao>";
	echo	"<Operacao><![CDATA[Pessoa/Endereço]]></Operacao>";
	echo	"<Link><![CDATA[menu_pessoa_endereco.php]]></Link>";
	echo	"<Tipo><![CDATA[Relatório]]></Tipo>";
	echo "</reg>";
		
	echo "</db>";
?>