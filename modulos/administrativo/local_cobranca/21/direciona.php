<?
	include("../../../../files/conecta.php");
	include("../../../../files/funcoes.php");

	$sql = "select
			Pessoa.CPF_CNPJ,
			Pessoa.Senha
		from
			ContaReceber,
			Pessoa
		where
			ContaReceber.IdLoja = $_POST[IdLoja] and
			ContaReceber.IdContaReceber = $_POST[IdContaReceber] and
			ContaReceber.IdPessoa = Pessoa.IdPessoa";
	$res = mysql_query($sql,$con);
	$lin = mysql_fetch_array($res);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>	
	<head>
		<title><?=getParametroSistema(4,1)?></title>
		<link rel = 'stylesheet' type = 'text/css' href = '../../../../css/conteudo.css' />
		<link rel = 'stylesheet' type = 'text/css' href = '../../../../css/default.css' />
		<script type = 'text/javascript' src = '../../js/funcoes.js'></script>
		<script type = 'text/javascript' src = '../../js/incremental_search.js'></script>
		<script type = 'text/javascript' src = '../../js/event.js'></script>
		<link REL="SHORTCUT ICON" HREF="../../../../img/estrutura_sistema/favicon.ico">
	</head>
	<body>
		<div id='sem_permissao'>
			<p id='p1'>Gateway de pagamentos F2B</p>
			<br />
			<center><img src='../../../../img/estrutura_sistema/bandeira_pgto/f2b.jpg' alt='F2B' style='border: 5px #FFF solid;' /></center>
			<p id='p2'>Para emitir o boleto deste cliente é necessário acessar o módulo de<br>pagamento on-line da F2B disponível na Central do Assinante.</p>
			<p id='p2'>Para continuar, clique em avançar.</p>
		</div>
		<table  style='width:100%; margin-top:30px; text-align:center'>
			<tr>
				<td class='campo'>
					<input type='button' name='bt_confirmar' value='Avançar' style='cursor:pointer; width: 80px; height: 25px' onClick="confirmar()">
				</td>
			</tr>
		</table>
	</body>
</html>
<script>
	function confirmar(){
		window.location.replace('../../../cda/rotinas/autentica.php?CPF_CNPJ=<?=$lin[CPF_CNPJ]?>&Senha=<?=$lin[Senha]?>');
	}
</script>