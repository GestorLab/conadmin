<?
	$localModulo		=	1;
	$localOperacao		=	70;
	$localSuboperacao	=	"R";
		
	include ('../../files/conecta.php');
	include ('../../files/funcoes.php');
	include ('../../rotinas/verifica.php');
	 
	$localFiltro			= $_GET['Filtro'];
	$localOrdem				= $_GET['Ordem'];
	$localOrdemDirecao		= $_GET['OrdemDirecao'];
	$localTipoDado			= $_GET['TipoDado'];
	$localCampo				= url_string_xsl($_GET['Campo'],'');
	$localValor				= url_string_xsl($_GET['Valor'],'url',false);
	$localTipoPessoa		= $_GET['TipoPessoa'];
	$localNome				= url_string_xsl($_GET['Nome'],'url',false);
	$localAprovada			= $_GET['Aprovada'];
	$localLimit				= $_GET['Limit'];
	$localIdPessoa			= $_GET['IdPessoa'];
	$localDataInicio_solicitacao		= url_string_xsl($_GET['DataInicio_solicitacao'],'');
	$localDataTermino_solicitacao		= url_string_xsl($_GET['DataTermino_solicitacao'],'');
	$localDataInicio_alteracao			= url_string_xsl($_GET['DataInicio_alteracao'],'');
	$localDataTermino_alteracao			= url_string_xsl($_GET['DataTermino_alteracao'],'');
	
	if($localOrdem == ''){							$localOrdem = "Nome";		}
	if($localOrdemDirecao == ''){					$localOrdemDirecao = getCodigoInterno(7,6);	}	
	if($localTipoDado == ''){						$localTipoDado = 'text';	}
	if($localNome != ""){
		$localNome = str_replace("\\","",$localNome);
	}
	LimitVisualizacao('xsl');
	
	$ImgOrdernarASC = "<img src='../../img/estrutura_sistema/seta_$localOrdemDirecao.gif' alt='Ordenado por' />";
	
	header ("content-type: text/xsl");
	echo "<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<xsl:decimal-format name="euro" decimal-separator="," grouping-separator="."/>
<xsl:template match="/">
<html>	
	<head>
		<link rel = 'stylesheet' type = 'text/css' href = '../../css/conteudo.css' />
		<link rel = 'stylesheet' type = 'text/css' href = '../../css/default.css' />
		<link rel = 'stylesheet' type = 'text/css' href = '../../css/impress.css' media='print' />
		<script type = 'text/javascript' src = '../../js/funcoes.js'></script>
		<script type = 'text/javascript' src = '../../js/incremental_search.js'></script>
		<script type = 'text/javascript' src = '../../js/mensagens.js'></script>
		<script type = 'text/javascript' src = '../../js/event.js'></script>
		<script type = 'text/javascript' src = 'js/pessoa_solicitacao.js'></script>
	</head>
	<body onLoad="javascript:ativaNome('<?=dicionario(218)?>');"> 
		<? include("filtro_pessoa_solicitacao.php"); ?>
		<div id='carregando'><?=dicionario(17)?></div>
		<div id='conteudo'>	
			<table class='tableListar' id='tableListar' cellspacing='0'>
				<tr class='tableListarTitle'>
					<td class='id_listar'>
						<a href="javascript:filtro_ordenar('IdPessoaSolicitacao','number');"><?=dicionario(141)?> <?=compara($localOrdem,"IdPessoaSolicitacao",$ImgOrdernarASC,'');?></a>
					</td>
					<?
						$nome	=	"<a href=\"javascript:filtro_ordenar('Nome');\">".dicionario(85)."".compara($localOrdem,'Nome',$ImgOrdernarASC,'')."</a>";
						$razao	=	"<a href=\"javascript:filtro_ordenar('RazaoSocial');\">".dicionario(172)."".compara($localOrdem,'RazaoSocial',$ImgOrdernarASC,'')."</a>";
					
						switch(getCodigoInterno(3,24)){
								case 'Nome';
									$primeiro = $razao;
									$segundo  = $nome;	
									break;
								default:
									$primeiro = $nome;
									$segundo  = $razao;
							}
					
						echo"
						  <td>$primeiro</td>
						  <td>$segundo</td>
						 "; 
					?>
					<td>
						<a href="javascript:filtro_ordenar('Telefone1');"><?=dicionario(213)?> <?=compara($localOrdem,"Telefone1",$ImgOrdernarASC,'');?></a>
					</td>
					<td style='width: 130px'>
						<a href="javascript:filtro_ordenar('CPF_CNPJ');"><?=dicionario(194)?><?=compara($localOrdem,"CPF_CNPJ",$ImgOrdernarASC,'');?></a>
					</td>
					<td>
						<a href="javascript:filtro_ordenar('NomeCidade');"><?=dicionario(186)?><?=compara($localOrdem,"NomeCidade",$ImgOrdernarASC,'');?></a>
					</td>
					<td class='sequencial'>
						<a href="javascript:filtro_ordenar('SiglaEstado');"><?=dicionario(139)?><?=compara($localOrdem,"SiglaEstado",$ImgOrdernarASC,'');?></a>
					</td>
					<td class='sequencial'>
						<a href="javascript:filtro_ordenar('Aprovada');"><?=dicionario(140)?><?=compara($localOrdem,"Aprovada",$ImgOrdernarASC,'');?></a>
					</td>
				</tr>
				<xsl:for-each select="db/reg">
				<xsl:sort order="<?=$localOrdemDirecao?>" select="<?=$localOrdem?>"  data-type="<?=$localTipoDado?>" />						
				<xsl:element name="tr">
					<xsl:attribute name="class">tableListarDados</xsl:attribute>
					<xsl:attribute name="onmouseover">destacaRegistro(this,true)</xsl:attribute>
					<xsl:attribute name="onmouseout">destacaRegistro(this,false)</xsl:attribute>
					<xsl:attribute name="accessKey"><xsl:value-of select="IdPessoa"/></xsl:attribute>
					<td class='sequencial'>
						<xsl:attribute name="bgcolor"><xsl:value-of select="Color"/></xsl:attribute>
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_pessoa_solicitacao.php?IdPessoaSolicitacao=<xsl:value-of select="IdPessoaSolicitacao"/></xsl:attribute>
							<xsl:value-of select="IdPessoaSolicitacao"/>
						</xsl:element>
					</td>
					<?	
						$Nome	=	"<xsl:attribute name='bgcolor'><xsl:value-of select='Color'/></xsl:attribute>
									<xsl:element name='a'>
										<xsl:attribute name='href'>cadastro_pessoa_solicitacao.php?IdPessoaSolicitacao=<xsl:value-of select='IdPessoaSolicitacao'/></xsl:attribute>
										<xsl:value-of select='Nome'/>
									</xsl:element>";
						
						$Razao	=	"<xsl:attribute name='bgcolor'><xsl:value-of select='Color'/></xsl:attribute>
										<xsl:element name='a'>
										<xsl:attribute name='href'>cadastro_pessoa_solicitacao.php?IdPessoaSolicitacao=<xsl:value-of select='IdPessoaSolicitacao'/></xsl:attribute>
										<xsl:value-of select='RazaoSocial'/>
									</xsl:element>";
						
						switch(getCodigoInterno(3,24)){
								case 'Nome';
									$Primeiro = $Razao;
									$Segundo  = $Nome;	
									break;
								default:
									$Primeiro = $Nome;
									$Segundo  = $Razao;
							}
					
						echo"
						  <td>$Primeiro</td>
						  <td>$Segundo</td>
						 ";  
					?>
					<td>
						<xsl:attribute name="bgcolor"><xsl:value-of select="Color"/></xsl:attribute>
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_pessoa_solicitacao.php?IdPessoaSolicitacao=<xsl:value-of select="IdPessoaSolicitacao"/></xsl:attribute>
							<xsl:value-of select="Telefone1"/>
						</xsl:element>
					</td>
					<td>
						<xsl:attribute name="bgcolor"><xsl:value-of select="Color"/></xsl:attribute>
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_pessoa_solicitacao.php?IdPessoaSolicitacao=<xsl:value-of select="IdPessoaSolicitacao"/></xsl:attribute>
							<xsl:value-of select="CPF_CNPJ"/>
						</xsl:element>
					</td>
					<td>
						<xsl:attribute name="bgcolor"><xsl:value-of select="Color"/></xsl:attribute>
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_pessoa_solicitacao.php?IdPessoaSolicitacao=<xsl:value-of select="IdPessoaSolicitacao"/></xsl:attribute>
							<xsl:value-of select="NomeCidade"/> 
						</xsl:element>
					</td>
					<td>
						<xsl:attribute name="bgcolor"><xsl:value-of select="Color"/></xsl:attribute>
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_pessoa_solicitacao.php?IdPessoaSolicitacao=<xsl:value-of select="IdPessoaSolicitacao"/></xsl:attribute>
							<xsl:value-of select="SiglaEstado"/> 
						</xsl:element>
					</td>
					<td>
						<xsl:attribute name="bgcolor"><xsl:value-of select="Color"/></xsl:attribute>
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_pessoa_solicitacao.php?IdPessoaSolicitacao=<xsl:value-of select="IdPessoaSolicitacao"/></xsl:attribute>
							<xsl:value-of select="Aprovada"/> 
						</xsl:element>
					</td>
				</xsl:element>
				</xsl:for-each>
				<tr class='tableListarTitle'>
					<td colspan='8' id='tableListarTotal'><?=dicionario(128)?>: <xsl:value-of select="count(db/reg)" /></td>
				</tr>
			</table>
			<table>
				<tr>
					<td class='find' />
					<td><h1 id='helpText' name='helpText' /></td>
				</tr>
			</table>
		</div>
		<div id="versaoDeImpressao"><?=getParametroSistema(4,1)?></div>
	</body>	
</html>
<script>
	
	addParmUrl("marPessoa","IdPessoa",'<?=$localIdPessoa?>');
	addParmUrl("marContrato","IdPessoa",'<?=$localIdPessoa?>');
	addParmUrl("marContratoNovo","IdPessoa",'<?=$localIdPessoa?>');
	addParmUrl("marLancamentoFinanceiro","IdPessoa",'<?=$localIdPessoa?>');
	addParmUrl("marContasReceber","IdPessoa",'<?=$localIdPessoa?>');
	addParmUrl("marProcessoFinanceiro","IdPessoa",'<?=$localIdPessoa?>');
	addParmUrl("marProcessoFinanceiroNovo","IdPessoa",'<?=$localIdPessoa?>');
	addParmUrl("marOrdemServico","IdPessoa",'<?=$localIdPessoa?>');
	addParmUrl("marOrdemServicoNovo","IdPessoa",'<?=$localIdPessoa?>');

	tableMultColor('tableListar',document.filtro.corRegRand.value);
	menu_form = false;
</script>
</xsl:template>
</xsl:stylesheet>
