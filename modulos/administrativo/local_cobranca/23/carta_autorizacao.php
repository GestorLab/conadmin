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
							Autoriza��o de D�bito Programado</th>
					</tr>
				</table>
			</div>
			<div class='quadro' style='text-align: justify;'>
				<ul>
					<li>Autorizo o <?$DescricaoBanco?> a debitar em minha conta corrente o valor correspondente � quita��o dos compromissos abaixo relacionados, oriundos de lan�amentos gerados pelo aplicativo D�bito Autom�tico.</li>
					<li>Comprometo-me, desde j�, a manter saldo suficiente para o referido d�bito, ficando o <?=$DescricaoBanco?> isento de qualquer responsabilidade decorrente da n�o liquida��o do compromisso por insufici�ncia de saldo na data do vencimento.</li>
					<li>Estou ciente de que, caso n�o conste na conta de consumo a express�o 'D�bito em conta-n�o receber no caixa', esta poder� ser quitada em qualquer terminal de auto-atendimento do <?=$DescricaoBanco?>. Nesse caso, procure sua ag�ncia para esclarecimentos.</li>
					<li>Em caso de d�vida ou reclama��o sobre datas de vencimento e/ou valores, devo solicitar esclarecimentos diretamente � empresa credora.</li>
					<li>O <?=$DescricaoBanco?> se reserva o direito de, a qualquer tempo, cancelar a presente presta��o de servi�o, mediante comunica��o por escrito.</li>
				</ul>
			</div>
			<br>
			<div>
				<table width='100%' style='font-weight: bold;'>
					<tr>
						<td>Conta para D�bito Programado</td>
					</tr>
				</table>
			</div>
			<div class='quadro'>
				<table style='width: 100%';>
					<tr>
						<td>
							Nome: <?= $lin[Nome] ?><br>
							Conv�nio: <?= $ParametroLocalCobranca[Convenio]?><BR>
							Descri��o/Finalidade: <?= $ParametroLocalCobranca[DescricaoFinalidade]?><BR>
							Banco: <?=$CodigoBanco?><BR>
							Ag�ncia: <?= $lin[Agencia] ?><BR>
							Conta: <?= $lin[Conta] ?><BR>
							Identifica��o para D�bito: <?= $lin[NumeroIdentificacao] ?></td>
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