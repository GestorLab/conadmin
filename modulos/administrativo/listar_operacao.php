<?
	$localModulo		=	1;
	$localOperacao		=	58;
	$localSuboperacao	=	"R";		
	
	include ('../../files/conecta.php');
	include ('../../files/funcoes.php');
	include ('../../rotinas/verifica.php');
	
	$local_IdLoja			= $_SESSION['IdLoja']; 
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
	echo "<?xml-stylesheet type=\"text/xsl\" href=\"listar_operacao_xsl.php$filtro_url\"?>";
	echo "<db>";
	
	echo "<reg>";			
	echo	"<Operacao><![CDATA[Pessoa - Alterar CPF/CNPJ]]></Operacao>";
	echo	"<Link><![CDATA[cadastro_pessoa_cpf.php]]></Link>";
	echo "</reg>";	
	
	echo "<reg>";			
	echo	"<Operacao><![CDATA[Contrato - Alterar datas do Contrato]]></Operacao>";
	echo	"<Link><![CDATA[cadastro_contrato_data_base.php]]></Link>";
	echo "</reg>";	
	
	echo "<reg>";			
	echo	"<Operacao><![CDATA[E-mail - Alterar tipo e-mail]]></Operacao>";
	echo	"<Link><![CDATA[listar_tipo_email.php]]></Link>";
	echo "</reg>";	
	
	echo "<reg>";			
	echo	"<Operacao><![CDATA[Contrato - Alterar Status]]></Operacao>";
	echo	"<Link><![CDATA[listar_contrato_mudar_status.php]]></Link>";
	echo "</reg>";	
	
	echo "<reg>";			
	echo	"<Operacao><![CDATA[Processo Financeiro - Cancelar]]></Operacao>";
	echo	"<Link><![CDATA[cadastro_processo_financeiro.php?Cancelar=true]]></Link>";
	echo "</reg>";		
	
	echo "<reg>";			
	echo	"<Operacao><![CDATA[Relatório - Lista de Processos]]></Operacao>";
	echo	"<Link><![CDATA[listar_processo.php]]></Link>";
	echo "</reg>";	
	
	echo "<reg>";			
	echo	"<Operacao><![CDATA[Executar rotinas diárias]]></Operacao>";
	echo	"<Link><![CDATA[cadastro_executar_rotina.php]]></Link>";
	echo "</reg>";
	
	if(getParametroSistema(111,1) == 1){
		echo "<reg>";			
		echo	"<Operacao><![CDATA[Importar de Registros]]></Operacao>";
		echo	"<Link><![CDATA[menu_importar_registros.php]]></Link>";
		echo "</reg>";
	}
	
	echo "<reg>";			
	echo	"<Operacao><![CDATA[Contas a Receber - Excluir Registros]]></Operacao>";
	echo	"<Link><![CDATA[listar_conta_receber_excluir.php]]></Link>";
	echo "</reg>";
	
	echo "<reg>";			
	echo	"<Operacao><![CDATA[Contas a Receber - Ativar]]></Operacao>";
	echo	"<Link><![CDATA[cadastro_conta_receber_ativar.php]]></Link>";
	echo "</reg>";
	
	echo "<reg>";			
	echo	"<Operacao><![CDATA[Arquivo de Retorno (Cancelar Processamento)]]></Operacao>";
	echo	"<Link><![CDATA[cadastro_cancelar_arquivo_retorno.php]]></Link>";
	echo "</reg>";
	
	echo "<reg>";			
	echo	"<Operacao><![CDATA[Relatório - UpTime (Tempo do servidor ligado)]]></Operacao>";
	echo	"<Link><![CDATA[aviso_uptime.php]]></Link>";
	echo "</reg>";	
	
	echo "<reg>";			
	echo	"<Operacao><![CDATA[Nota Fiscal - Reprocessar]]></Operacao>";
	echo	"<Link><![CDATA[cadastro_nota_fiscal_reprocessar.php]]></Link>";
	echo "</reg>";
	
	echo "<reg>";			
	echo	"<Operacao><![CDATA[Arquivo de Remessa (Cancelar Processamento)]]></Operacao>";
	echo	"<Link><![CDATA[cadastro_cancelar_arquivo_remessa.php]]></Link>";
	echo "</reg>";
	
	echo "<reg>";			
	echo	"<Operacao><![CDATA[Lançamento Financeiro - Alterar Referência]]></Operacao>";
	echo	"<Link><![CDATA[cadastro_lancamento_financeiro_operacoes_especiais.php?Edit=1]]></Link>";
	echo "</reg>";

	echo "<reg>";			
	echo	"<Operacao><![CDATA[Relatório - E-mail Enviado (E-mail's antigos)]]></Operacao>";
	echo	"<Link><![CDATA[menu_reenvio_email.php]]></Link>";
	echo "</reg>";

	echo "<reg>";			
	echo	"<Operacao><![CDATA[Relatório - Número de Documento - Tabela de Reserva]]></Operacao>";
	echo	"<Link><![CDATA[menu_numero_documento.php]]></Link>";
	echo "</reg>";

	echo "<reg>";			
	echo	"<Operacao><![CDATA[Grupo Permissão - Duplicação de permissões]]></Operacao>";
	echo	"<Link><![CDATA[cadastro_grupo_permissao_duplicar.php]]></Link>";
	echo "</reg>";

	echo "<reg>";			
	echo	"<Operacao><![CDATA[Relatório - Verificação DNS]]></Operacao>";
	echo	"<Link><![CDATA[aviso_dns.php]]></Link>";
	echo "</reg>";

	echo "<reg>";			
	echo	"<Operacao><![CDATA[Relatório - Check NAS]]></Operacao>";
	echo	"<Link><![CDATA[listar_check_nas.php]]></Link>";
	echo "</reg>";
	
	echo "<reg>";			
	echo	"<Operacao><![CDATA[Configurar - Logomarca]]></Operacao>";
	echo	"<Link><![CDATA[cadastro_logomarca.php]]></Link>";
	echo "</reg>";
	
	/*echo "<reg>";			
	echo	"<Operacao><![CDATA[Contrato - Derrubar Login em Massa]]></Operacao>";
	echo	"<Link><![CDATA[filtro_derroba_contrato_massa.php]]></Link>";
	echo "</reg>";*/
	
	
	if(getCodigoInterno(10000,1) != ''){
		echo "<reg>";			
		echo	"<Operacao><![CDATA[FreeRadius - Reiniciar FreeRadius]]></Operacao>";
		echo	"<Link><![CDATA[menu_reiniciar_free_radius.php]]></Link>";
		echo "</reg>";
	}
	
/*	EM DESENVOLVIMENTO
	echo "<reg>";			
	echo	"<Operacao><![CDATA[Contas a Receber - Alterar Status]]></Operacao>";
	echo	"<Link><![CDATA[cadastro_conta_receber_mudar_status.php]]></Link>";
	echo "</reg>";*/
		
	echo "</db>";
?>