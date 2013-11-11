<?
	$localModulo		=	1;
	$localOperacao		=	42;
	$localSuboperacao	=	"V";	
	
	include ('../../files/conecta.php');
	include ('../../files/funcoes.php');
	include ('../../rotinas/verifica.php');
	 
	$local_Login		= $_SESSION["Login"];
	$local_IdLoja		= $_SESSION["IdLoja"];
	
	if($_GET['IdLoteRepasse']!=''){
		$local_IdLoteRepasse	= $_GET['IdLoteRepasse'];	
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>	
	<head>
		<link rel = 'stylesheet' type = 'text/css' href = '../../css/conteudo.css' />
		<link rel = 'stylesheet' type = 'text/css' href = '../../css/default.css' />
		<link rel = 'stylesheet' type = 'text/css' href = '../../css/impress.css' media='print' />
		<script type = 'text/javascript' src = '../../js/funcoes.js'></script>
		<script type = 'text/javascript' src = '../../js/incremental_search.js'></script>
		<script type = 'text/javascript' src = '../../js/mensagens.js'></script>
		<script type = 'text/javascript' src = 'js/lote_repasse.js'></script>
		<style type="text/css">
			input[type=text]:readOnly  		{ background-color: #FFF; }
			input[type=datetime]:readOnly  	{ background-color: #FFF; }
			input[type=date]:readOnly  		{ background-color: #FFF; }
			textarea:readOnly  				{ background-color: #FFF; }
			select:disabled  { background-color: #FFF; }
			select:disabled  { color: #000; }
			@media print {
				.botao { display:none; }
				}
		</style>
	</head>
	<body  onLoad="ativaNome('Lote de Repasse')">
		<? include('filtro_lote_repasse.php'); ?>
		<div id='carregando'>carregando</div>
		<div id='conteudo'>
			<table style='padding-top:5px'>
				<tr>
					<td class='find'>&nbsp;</td>
					<td class='descCampo'style='<?=getParametroSistema(4,2)?>'><img src='../../img/personalizacao/logo_cab.gif'></td>
					<?
						$sql	=	"select
										Pessoa.Nome,
										Pessoa.RazaoSocial,
										Filtro_MesReferencia MesReferencia,
										Filtro_MenorVencimento MenorVencimento,
										DataConfirmacao,
										ObsRepasse
									from
										LoteRepasseTerceiro,
										Pessoa
									where
										LoteRepasseTerceiro.IdLoja = $local_IdLoja and
										LoteRepasseTerceiro.IdLoteRepasse = $local_IdLoteRepasse and
										Pessoa.IdPessoa = LoteRepasseTerceiro.IdTerceiro";
						$res	=	mysql_query($sql,$con);
						$lin	=	mysql_fetch_array($res);	
					?>
					<td class='separador'>&nbsp;</td>
					<td class='descCampo'>
						Lote Repasse: <B style='font-weight:normal; color:#000'><?=$local_IdLoteRepasse?></B><BR>
						Nome Terceiro: <B style='font-weight:normal; color:#000'><?=$lin[getCodigoInterno(3,24)]?></b><BR>
						Mês Referência: <B style='font-weight:normal; color:#000'><?=$lin[MesReferencia]?></B><BR>
						<?
							if($lin[MenorVencimento] != ''){
								echo "Menor Vencimento: <B style='font-weight:normal; color:#000'>".dataConv($lin[MenorVencimento],"Y-m-d","d/m/Y")."</B><BR>";
							}
						?>
						Data Confirmação: <B style='font-weight:normal; color:#000'><?=dataConv($lin[DataConfirmacao],'Y-m-d','d/m/Y')?></B><BR>
						Obs.: <B style='font-weight:normal; color:#000'><?=$lin[ObsRepasse]?></B></td>
				</tr>
			</table>
			<HR style='margin-top:-8px'>
			<!--table>
				<tr>
					<td class='find'>&nbsp;</td>
					<td class='descCampo'>Lançamentos Financeiros</td>				
				</tr>
			</table-->
			<table id='tabelaLancFinanceiro' class='tableListarCad' cellspacing='0'>
				<tr class='tableListarTitleCad'>
					<td class='tableListarEspaco'>Id</td>
					<td>Nome</td>
					<td>Serviço</td>
					<td style='text-align:center'>Referência</td>
					<td>Data Venc.</td>
					<td>Data Pag.</td>
					<td class='valor'>Valor(<?=getParametroSistema(5,1)?>)</td>
					<td class='valor'>Desconto(<?=getParametroSistema(5,1)?>)</td>
					<td class='valor'>Repasse(<?=getParametroSistema(5,1)?>)</td>
				</tr>
				<tr class='tableListarTitleCad'>
					<td class='tableListarEspaco' colspan='6' id='tabelaTotal'></td>
					<td id='tabelaTotalItem' class='valor'>0,00</td>
					<td id='tabelaTotalDescontoItem' class='valor'>0,00</td>
					<td id='tabelaTotalRepasse' class='valor'>0,00</td>
				</tr>
			</table>
			<br />
			<table style='width:848px;'>
				<tr>
					<td class='find'>&nbsp;</td>
					<td class='campo' style='text-align:right;'>
						<input type='button' name='bt_imprimir' value='Imprimir' class='botao' onClick='javascript:self.print()'>
					</td>
				</tr>
			</table>
		</div>
	</body>
</html>	
<script>
	addParmUrl("marLoteRepasse","IdLoteRepasse",<?=$local_IdLoteRepasse?>);
	imprimir_lancamento_financeiro(<?=$local_IdLoteRepasse?>);
</script>
