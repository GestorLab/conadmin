<?
	$localModulo		=	1;
	$localOperacao		=	142;
	$localSuboperacao	=	"V";
	$localCadComum		=	true;
	
	include ('../../../../files/conecta.php');
	include ('../../../../files/funcoes.php');
	include ('../../../../rotinas/verifica.php');
	
	$local_IdLoja			= $_SESSION["IdLoja"];
	$local_IdPessoa	 		= $_GET['IdPessoa'];	
	$local_IdContaDebito 	= $_GET['IdContaDebito'];
	
	include("../informacoes_default_carta_autorizacao.php");

	$DescricaoBanco = "Banco do Brasil S.A";
	$CodigoBanco	= "001";
?>
 <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
 "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
	<head>
		<TITLE><?=getParametroSistema(4,1)?></TITLE>
	</head>
	<link rel = 'stylesheet' type = 'text/css' href = '../../../../css/impress.css' media='print' />
	<style type="text/css">
		body{
			font-family: Verdade, Arial, Times;
			font-size: 12px;
			margin: 0;
		}
		#conteiner{
			margin: auto;
		}
		#conteiner .texto{
			margin:0;
		}
		#conteiner .quadro{
			border: solid 2px black;
			border-left: 0;
			border-right: 0;
			padding: 3px;
		}
		ul, ul li{
			margin: 0;
			padding: 0;
		}
		ul{
			list-style-type: decimal;
			padding-left: 17px;
		}
		ul li{

		}
	</style>
	<body>
		<div id='conteiner'>
			<div>
				<table width='100%'>
					<tr>
						<th align='left' style='width: 250px'><img src='../../../../img/personalizacao/logo_cab.jpg' /></th>
						<th>
							<?=$DescricaoBanco?><BR>
							<?=$DadosEmpresa[RazaoSocial]?><BR>
							Autorização de Débito Programado</th>
					</tr>
				</table>
			</div>
			<div class='quadro' style='text-align: justify;'>
				<ul>
					<li>Autorizo o <?$DescricaoBanco?> a debitar em minha conta corrente o valor correspondente à quitação dos compromissos abaixo relacionados, oriundos de lançamentos gerados pelo aplicativo Débito Automático.</li>
					<li>Comprometo-me, desde já, a manter saldo suficiente para o referido débito, ficando o <?=$DescricaoBanco?> isento de qualquer responsabilidade decorrente da não liquidação do compromisso por insuficiência de saldo na data do vencimento.</li>
					<li>Estou ciente de que, caso não conste na conta de consumo a expressão 'Débito em conta-não receber no caixa', esta poderá ser quitada em qualquer terminal de auto-atendimento do <?=$DescricaoBanco?>. Nesse caso, procure sua agência para esclarecimentos.</li>
					<li>Em caso de dúvida ou reclamação sobre datas de vencimento e/ou valores, devo solicitar esclarecimentos diretamente à empresa credora.</li>
					<li>O <?=$DescricaoBanco?> se reserva o direito de, a qualquer tempo, cancelar a presente prestação de serviço, mediante comunicação por escrito.</li>
				</ul>
			</div>
			<br>
			<div>
				<table width='100%' style='font-weight: bold;'>
					<tr>
						<td>Conta para Débito Programado</td>
					</tr>
				</table>
			</div>
			<div class='quadro'>
				<table style='width: 100%';>
					<tr>
						<td>
							Nome: <?= $lin[Nome] ?><br>
							Convênio: <?= $ParametroLocalCobranca[Convenio]?><BR>
							Descrição/Finalidade: <?= $ParametroLocalCobranca[DescricaoFinalidade]?><BR>
							Banco: <?=$CodigoBanco?><BR>
							Agência: <?= $lin[Agencia] ?><BR>
							Conta: <?= $lin[Conta] ?><BR>
							Identificação para Débito: <?= $lin[NumeroIdentificacao] ?></td>
						<td style='text-align:center'> <?= dataConv($lin[DataCriacao], 'Y-m-d H:i:s','d/m/Y') ?><BR><BR><BR><BR><BR>______________________________________________________<BR><?= $lin[CPF_CNPJ] ?></td>
					</tr>
				</table>
			</div>
		</div>
		<div class='impress'>
			<br /><br />
			<hr />
			<table style='width:100%'>
				<tr>
					<td style='text-align:center'>
						<input type='button' name='bt_imprimir' value='Imprimir' class='botao' onClick='javascript:self.print()'>
					</td>
				</tr>
			</table>
		</div>
	</body>
</html>