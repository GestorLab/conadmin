<?
	$localModulo		=	1;
	$localOperacao		=	58;
	$localSuboperacao	=	"R";		
	
	include ('../../files/conecta.php');
	include ('../../files/funcoes.php');
	include ('../../rotinas/verifica.php');

	$local_Login			= $_SESSION["Login"];	 
	$local_IdLoja			= $_SESSION['IdLoja']; 
	$filtro					= $_POST['filtro'];
	$filtro_ordem			= $_POST['filtro_ordem'];
	$filtro_ordem_direcao	= $_POST['filtro_ordem_direcao'];

	if($local_Login != 'root'){
		header("Location: sem_permissao.php");
	}
	
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
	echo "<?xml-stylesheet type=\"text/xsl\" href=\"listar_operacao_avancada_xsl.php$filtro_url\"?>";
	echo "<db>";

	echo "<reg>";			
	echo	"<Operacao><![CDATA[Simular Acesso de Usuário]]></Operacao>";
	echo	"<Link><![CDATA[cadastro_simular_acesso_usuario.php]]></Link>";
	echo "</reg>";
	
	echo "<reg>";			
	echo	"<Operacao><![CDATA[Derrubar Todos os Usuários logados]]></Operacao>";
	echo	"<Link><![CDATA[menu_derrubar_usuarios_logados.php]]></Link>";
	echo "</reg>";
	
	echo "<reg>";			
	echo	"<Operacao><![CDATA[Sistema em Modo de Atualização]]></Operacao>";
	echo	"<Link><![CDATA[menu_sistema_modo_atualizacao.php]]></Link>";
	echo "</reg>";

	echo "<reg>";			
	echo	"<Operacao><![CDATA[Teste de Email]]></Operacao>";
	echo	"<Link><![CDATA[cadastro_teste_email.php]]></Link>";
	echo "</reg>";

	echo "<reg>";			
	echo	"<Operacao><![CDATA[Log Processo Financeiro - Gerar Boletos]]></Operacao>";
	echo	"<Link><![CDATA[local_cobranca/gerar_boletos_background.log]]></Link>";
	echo "</reg>";

	echo "<reg>";			
	echo	"<Operacao><![CDATA[Log Processo Financeiro - Confirmar]]></Operacao>";
	echo	"<Link><![CDATA[rotinas/confirmar_processo_financeiro_background.log]]></Link>";
	echo "</reg>";

	echo "<reg>";			
	echo	"<Operacao><![CDATA[Log Processo Financeiro - Enviar Emails]]></Operacao>";
	echo	"<Link><![CDATA[rotinas/enviar_mensagem_processo_financeiro.log]]></Link>";
	echo "</reg>";

	echo "<reg>";			
	echo	"<Operacao><![CDATA[Log Cron]]></Operacao>";
	echo	"<Link><![CDATA[../../rotinas/cron.log]]></Link>";
	echo "</reg>";

	echo "<reg>";			
	echo	"<Operacao><![CDATA[Log Cron Minuto]]></Operacao>";
	echo	"<Link><![CDATA[../../rotinas/cron_minuto.log]]></Link>";
	echo "</reg>";

	echo "<reg>";			
	echo	"<Operacao><![CDATA[Log Cron Backup]]></Operacao>";
	echo	"<Link><![CDATA[../../rotinas/cron_backup.log]]></Link>";
	echo "</reg>";
	

	echo "<reg>";			
	echo	"<Operacao><![CDATA[Relatório Atualização Versão]]></Operacao>";
	echo	"<Link><![CDATA[listar_atualizacao_versao.php]]></Link>";
	echo "</reg>";
	
	
	echo "</db>";
?>