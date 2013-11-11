<?
	$localModulo		=	1;
	$localOperacao		=	27;
	$localSuboperacao	=	"E";
		
	include ('../../../files/conecta.php');
	include ('../../../files/funcoes.php');
	include ('../../../rotinas/verifica.php');
	
	$Path = "../../../";

	include ('../../../classes/envia_mensagem/envia_mensagem.php');
	
	if($_GET['IdHistoricoMensagem']!= ''){ 
		$local_IdHistoricoMensagem	= $_GET['IdHistoricoMensagem'];
	}
	else if($_POST['IdHistoricoMensagem']!= ''){
		$local_IdHistoricoMensagem	= $_POST['IdHistoricoMensagem'];
	}
	
	$local_Login	= $_SESSION["Login"];
	$local_IdLoja 	= $_SESSION["IdLoja"];
	$local_Login	= $_SESSION["Login"];

	if(permissaoSubOperacao($localModulo,$localOperacao,"E") == true){
		$sqlHistoricoMensagem = 
							"select
								IdTipoMensagem,
								Assunto,
								Titulo,
								Conteudo,
								IdPessoa,
								IdContaReceber,
								IdProcessoFinanceiro,
								IdReEnvio
							from
								HistoricoMensagem
							where
								IdLoja = $local_IdLoja and
								IdHistoricoMensagem = $local_IdHistoricoMensagem";
		$resHistoricoMensagem = mysql_query($sqlHistoricoMensagem,$con);
		$linHistoricoMensagem = mysql_fetch_array($resHistoricoMensagem);
		
		
		$linHistoricoMensagem[Email]	=	$_POST['Email'];
		
		$sql = "select
					(max(IdHistoricoMensagem)+1) IdHistoricoMensagem
				from
					HistoricoMensagem
				where
					IdLoja = $local_IdLoja";
		$resIdHistoricoMensagem = mysql_query($sql,$con);
		$linIdHistoricoMensagem = mysql_fetch_array($resIdHistoricoMensagem);
		
		if($linHistoricoMensagem[IdProcessoFinanceiro] == ''){  $linHistoricoMensagem[IdProcessoFinanceiro] = 'NULL'; }
		if($linHistoricoMensagem[IdMalaDireta] == ''){  $linHistoricoMensagem[IdMalaDireta] = 'NULL'; }		
		
		$linHistoricoMensagem[Conteudo] = str_replace("'","\'",$linHistoricoMensagem[Conteudo]);//Leonardo-15-01-13/Tratamento de aspas simples para as TAG's
		
		$sql = "insert into HistoricoMensagem set
				IdLoja					= $local_IdLoja,
				IdHistoricoMensagem		= $linIdHistoricoMensagem[IdHistoricoMensagem],
				IdTipoMensagem			= $linHistoricoMensagem[IdTipoMensagem],
				Email					= '$linHistoricoMensagem[Email]',
				Assunto					= '$linHistoricoMensagem[Assunto]',
				Titulo					= '$linHistoricoMensagem[Titulo]',
				Conteudo				= '$linHistoricoMensagem[Conteudo]',
				IdPessoa				= '$linHistoricoMensagem[IdPessoa]',
				IdContaReceber			= '$linHistoricoMensagem[IdContaReceber]',
				IdProcessoFinanceiro	= '$linHistoricoMensagem[IdProcessoFinanceiro]',
				IdMalaDireta			= '$linHistoricoMensagem[IdMalaDireta]',
				IdReEnvio				= '$local_IdHistoricoMensagem',
				IdStatus				= 1,
				MD5						= md5(concat($local_IdLoja,$linIdHistoricoMensagem[IdHistoricoMensagem])),
				LoginCriacao			= '$local_Login',
				DataCriacao				= (concat(curdate(),' ',curtime()))";			

		mysql_query($sql,$con);
		
		enviaMensagem($local_IdLoja, $linIdHistoricoMensagem[IdHistoricoMensagem]);
		
		header("Location: ../listar_reenvio_mensagem.php?IdHistoricoMensagem=$linIdHistoricoMensagem[IdHistoricoMensagem]&Erro=64");		
	}		
?>