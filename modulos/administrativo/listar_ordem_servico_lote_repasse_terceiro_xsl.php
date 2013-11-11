<?
	$localModulo		=	1;
	$localOperacao		=	132;
	$localSuboperacao	=	"R";
	
	include ('../../files/conecta.php');
	include ('../../files/funcoes.php');
	include ('../../rotinas/verifica.php');
	 
		
	$localFiltro					= $_GET['Filtro'];
	$localOrdem						= $_GET['Ordem'];
	$localOrdemDirecao				= $_GET['OrdemDirecao'];
	$localTipoDado					= $_GET['TipoDado'];
	$localNome						= url_string_xsl($_GET['Nome'],'url',false);
	$localDescricaoServico			= url_string_xsl($_GET['DescricaoServico'],'url',false);
	$localIdStatus					= $_GET['IdStatus'];
	$localFaturado					= $_GET['Faturado'];
	$local_IdPessoa					= $_GET['IdPessoa'];
	$local_IdContrato				= $_GET['IdContrato'];
	$local_IdOrdemServico			= $_GET['IdOrdemServico'];
	$local_IdContaReceber			= $_GET['IdContaReceber'];
	$local_IdContrato				= $_GET['IdContrato'];
	$local_IdContaEventual			= $_GET['IdContaEventual'];
	$local_IdProcessoFinanceiro		= $_GET['IdProcessoFinanceiro'];	
	$localCampo						= url_string_xsl($_GET['Campo'],'');
	$localValor						= url_string_xsl($_GET['Valor'],'url',false);	
	$Limit							= $_GET['Limit'];

	$localPercentualRepasseTerceiro			=	$_GET['PercentualRepasseTerceiro'];
	$localPercentualRepasseTerceiroOutros	=	$_GET['PercentualRepasseTerceiroOutros'];

	if($localOrdem == ''){					$localOrdem = "NomeTerceiro";		}
	if($localOrdemDirecao == ''){			$localOrdemDirecao = getCodigoInterno(7,6);	}	
	if($localTipoDado == ''){				$localTipoDado = 'text';	}
	
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
		<script type = 'text/javascript' src = 'js/ordem_servico.js'></script>
		<script type = 'text/javascript' src = 'js/ordem_servico_lote_repasse_terceiro.js'></script>
	</head>
	<body  onLoad="ativaNome('<?=dicionario(501)?>')">
		<? include('filtro_ordem_servico_lote_repasse_terceiro.php'); ?>
		<div id='carregando'><?=dicionario(17)?></div>
		<div id='conteudo'>
			<table class='tableListar' cellspacing='0' Id='tableListar'>
				<tr class='tableListarTitle'>
					<td class='id_listar'>
						<a href="javascript:filtro_ordenar('IdOrdemServico','number')"><?=dicionario(141)?><?=compara($localOrdem,"IdOrdemServico",$ImgOrdernarASC,'')?></a>
					</td>
					<td class='marcador' />
					<td>
						<a href="javascript:filtro_ordenar('Nome')"><?=visualizarBuscaPessoa(getCodigoInterno(3,24))?><?=compara($localOrdem,"Nome",$ImgOrdernarASC,'')?></a>
					</td>			
					<td>
						<a href="javascript:filtro_ordenar('DescricaoServico')"><?=dicionario(30)?><?=compara($localOrdem,"DescricaoServico",$ImgOrdernarASC,'')?></a>
					</td>
					<td>
						<a href="javascript:filtro_ordenar('NomeTerceiro')"><?=dicionario(33)?> <?=compara($localOrdem,"NomeTerceiro",$ImgOrdernarASC,'')?></a>
					</td>
					<td>
						<a href="javascript:filtro_ordenar('FormaCobranca')"><?=dicionario(406)?>. <?=compara($localOrdem,"FormaCobranca",$ImgOrdernarASC,'')?></a>
					</td>
					<?
						if($localPercentualRepasseTerceiro == 2){
					echo" 
					<td class='valor'>
						<a href=\"javascript:filtro_ordenar('PercentualRepasseTerceiro','number')\">".dicionario(502)." (%)".compara($localOrdem,"PercentualRepasseTerceiro",$ImgOrdernarASC,'')."</a>
					</td>";
						}
						if($localPercentualRepasseTerceiroOutros == 2){	
					echo"		
					<td class='valor'>
						<a href=\"javascript:filtro_ordenar('PercentualRepasseTerceiroOutros','number')\">".dicionario(503)."(%)".compara($localOrdem,"PercentualRepasseTerceiroOutros",$ImgOrdernarASC,'')."</a>
					</td>";						
						}
					?>
				
					<td class='valor'>
						<a href="javascript:filtro_ordenar('Valor','number')"><?=dicionario(204)?> (<?=getParametroSistema(5,1)?>) <?=compara($localOrdem,"Valor",$ImgOrdernarASC,'')?></a>
					</td>
					<td class='valor'>
						<a href="javascript:filtro_ordenar('ValorOutros','number')"><?=dicionario(504)?>(<?=getParametroSistema(5,1)?>) <?=compara($localOrdem,"ValorOutros",$ImgOrdernarASC,'')?></a>
					</td>
					<td class='valor'>
						<a href="javascript:filtro_ordenar('ValorTotal','number')"><?=dicionario(398)?> (<?=getParametroSistema(5,1)?>) <?=compara($localOrdem,"ValorTotal",$ImgOrdernarASC,'')?></a>
					</td>
					<td class='valor'>
						<a href="javascript:filtro_ordenar('ValorRepasseTerceiro','number')"><?=dicionario(473)?> (<?=getParametroSistema(5,1)?>) <?=compara($localOrdem,"ValorRepasseTerceiro",$ImgOrdernarASC,'')?></a>
					</td>					
					<td class='valor'>
						<a href="javascript:filtro_ordenar('ValorRepasseTerceiroOutros','number')"><?=dicionario(505)?> (<?=getParametroSistema(5,1)?>) <?=compara($localOrdem,"ValorRepasseTerceiroOutros",$ImgOrdernarASC,'')?></a>
					</td>				
					<td class='valor'>
						<a href="javascript:filtro_ordenar('ValorTotalRepasseTerceiro','number')"><?=dicionario(506)?>.(<?=getParametroSistema(5,1)?>) <?=compara($localOrdem,"ValorTotalRepasseTerceiro",$ImgOrdernarASC,'')?></a>
					</td>	
					<td>
						<a href="javascript:filtro_ordenar('Status')"><?=dicionario(140)?> <?=compara($localOrdem,"Status",$ImgOrdernarASC,'')?></a>
					</td>
					<td class='bt_lista' />
				</tr>
				<xsl:for-each select="db/reg">
				<xsl:sort order="<?=$localOrdemDirecao?>" select="<?=$localOrdem?>" data-type="<?=$localTipoDado?>" />
				<xsl:element name="tr">
					<xsl:attribute name="class">tableListarDados</xsl:attribute>
					<xsl:attribute name="onmouseover">destacaRegistro(this,true)</xsl:attribute>
					<xsl:attribute name="onmouseout">destacaRegistro(this,false)</xsl:attribute>
					<xsl:attribute name="accessKey"><xsl:value-of select="IdOrdemServico"/></xsl:attribute>
					<td>
						<xsl:attribute name="bgcolor"><xsl:value-of select="Color"/></xsl:attribute>
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_ordem_servico.php?IdOrdemServico=<xsl:value-of select="IdOrdemServico"/></xsl:attribute>
							<xsl:value-of select="IdOrdemServico"/>
						</xsl:element>
					</td>
					<td>
						<xsl:attribute name="bgcolor"><xsl:value-of select="Color"/></xsl:attribute>
						<xsl:element name="a">							
							<xsl:attribute name="href">cadastro_ordem_servico.php?IdOrdemServico=<xsl:value-of select="IdOrdemServico"/></xsl:attribute>
							<xsl:attribute name="style">color:<xsl:value-of select="CorMarcador"/></xsl:attribute>																
							<xsl:value-of select="Marcador"/>
						</xsl:element>
					</td>
					<td>
						<xsl:attribute name="bgcolor"><xsl:value-of select="Color"/></xsl:attribute>
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_ordem_servico.php?IdOrdemServico=<xsl:value-of select="IdOrdemServico"/></xsl:attribute>
							<xsl:value-of select="Nome"/>
						</xsl:element>
					</td>					
					<td>
						<xsl:attribute name="bgcolor"><xsl:value-of select="Color"/></xsl:attribute>
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_ordem_servico.php?IdOrdemServico=<xsl:value-of select="IdOrdemServico"/></xsl:attribute>
							<xsl:value-of select="DescricaoServico"/>
						</xsl:element>
					</td>
					<td>
						<xsl:attribute name="bgcolor"><xsl:value-of select="Color"/></xsl:attribute>
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_ordem_servico.php?IdOrdemServico=<xsl:value-of select="IdOrdemServico"/></xsl:attribute>
							<xsl:value-of select="NomeTerceiro"/>
						</xsl:element>
					</td>
					<td>
						<xsl:attribute name="bgcolor"><xsl:value-of select="Color"/></xsl:attribute>
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_ordem_servico.php?IdOrdemServico=<xsl:value-of select="IdOrdemServico"/></xsl:attribute>
							<xsl:value-of select="FormaCobranca"/>
						</xsl:element>
					</td>
					<?
						if($localPercentualRepasseTerceiro == 2){
					echo"	
					<td class='valor'>
						<xsl:attribute name='bgcolor'><xsl:value-of select='Color'/></xsl:attribute>
						<xsl:element name='a'>
							<xsl:attribute name='href'>cadastro_ordem_servico.php?IdOrdemServico=<xsl:value-of select='IdOrdemServico'/></xsl:attribute>
							<xsl:value-of select=\"format-number(PercentualRepasseTerceiro,'0,00','euro')\"/>
						</xsl:element>
					</td>";
						}
					
						if($localPercentualRepasseTerceiroOutros == 2){	
					echo"	
					<td class='valor'>
						<xsl:attribute name='bgcolor'><xsl:value-of select='Color'/></xsl:attribute>
						<xsl:element name='a'>
							<xsl:attribute name='href'>cadastro_ordem_servico.php?IdOrdemServico=<xsl:value-of select='IdOrdemServico'/></xsl:attribute>
							<xsl:value-of select=\"format-number(PercentualRepasseTerceiroOutros,'0,00','euro')\"/>
						</xsl:element>
					</td>";
						}
					?>
					<td class='valor'>
						<xsl:attribute name="bgcolor"><xsl:value-of select="Color"/></xsl:attribute>
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_ordem_servico.php?IdOrdemServico=<xsl:value-of select="IdOrdemServico"/></xsl:attribute>
							<xsl:value-of select='format-number(Valor,"0,00","euro")'/>
						</xsl:element>
					</td>	
					<td class='valor'>
						<xsl:attribute name="bgcolor"><xsl:value-of select="Color"/></xsl:attribute>
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_ordem_servico.php?IdOrdemServico=<xsl:value-of select="IdOrdemServico"/></xsl:attribute>
							<xsl:value-of select='format-number(ValorOutros,"0,00","euro")'/>
						</xsl:element>
					</td>
					<td class='valor'>
						<xsl:attribute name="bgcolor"><xsl:value-of select="Color"/></xsl:attribute>
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_ordem_servico.php?IdOrdemServico=<xsl:value-of select="IdOrdemServico"/></xsl:attribute>
							<xsl:value-of select='format-number(ValorTotal,"0,00","euro")'/>
						</xsl:element>
					</td>						
					<td class='valor'>
						<xsl:attribute name="bgcolor"><xsl:value-of select="Color"/></xsl:attribute>
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_ordem_servico.php?IdOrdemServico=<xsl:value-of select="IdOrdemServico"/></xsl:attribute>
							<xsl:value-of select='format-number(ValorRepasseTerceiro,"0,00","euro")'/>
						</xsl:element>
					</td>				
					<td class='valor'>
						<xsl:attribute name="bgcolor"><xsl:value-of select="Color"/></xsl:attribute>
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_ordem_servico.php?IdOrdemServico=<xsl:value-of select="IdOrdemServico"/></xsl:attribute>
							<xsl:value-of select='format-number(ValorRepasseTerceiroOutros,"0,00","euro")'/>
						</xsl:element>
					</td>
					<td class='valor'>
						<xsl:attribute name="bgcolor"><xsl:value-of select="Color"/></xsl:attribute>
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_ordem_servico.php?IdOrdemServico=<xsl:value-of select="IdOrdemServico"/></xsl:attribute>
							<xsl:value-of select='format-number(ValorTotalRepasseTerceiro,"0,00","euro")'/>
						</xsl:element>
					</td>
					<td>
						<xsl:attribute name="bgcolor"><xsl:value-of select="Color"/></xsl:attribute>
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_ordem_servico.php?IdOrdemServico=<xsl:value-of select="IdOrdemServico"/></xsl:attribute>
							<xsl:value-of select="Status"/>
						</xsl:element>
					</td>
					<td class='bt_lista'>
						<xsl:attribute name="bgcolor"><xsl:value-of select="Color"/></xsl:attribute>
						<xsl:element name="img">
							<xsl:attribute name="src"><xsl:value-of select="ImgExc"/></xsl:attribute>
							<xsl:attribute name="alt">Excluir?</xsl:attribute>
							<xsl:attribute name="onClick">excluir(<xsl:value-of select="IdOrdemServico"/>,<xsl:value-of select="IdStatus"/>)</xsl:attribute>
						</xsl:element>
					</td>
				</xsl:element>
				</xsl:for-each>
				<tr class='tableListarTitle'>
					<td colspan='3' id='tableListarTotal'><?=dicionario(128)?>: <xsl:value-of select="count(db/reg)" /></td>
					<td />
					<td />
					<td />
					<?
						if($localPercentualRepasseTerceiro == 2){							
					echo"	
					<td />
					";
						}
						if($localPercentualRepasseTerceiroOutros == 2){	
					echo"
					<td />
					";	
						}
					?>					
					<td class='valor' id='tableListarValor'><xsl:value-of select='format-number(sum(db/reg/Valor),"0,00","euro")' /></td>
					<td class='valor' id='tableListarValor'><xsl:value-of select='format-number(sum(db/reg/ValorOutros),"0,00","euro")' /></td>
					<td class='valor' id='tableListarValor'><xsl:value-of select='format-number(sum(db/reg/ValorTotal),"0,00","euro")' /></td>				
					<td class='valor' id='tableListarValor'><xsl:value-of select='format-number(sum(db/reg/ValorRepasseTerceiro),"0,00","euro")' /></td>					
					<td class='valor' id='tableListarValor'><xsl:value-of select='format-number(sum(db/reg/ValorRepasseTerceiroOutros),"0,00","euro")' /></td>
					<td class='valor' id='tableListarValor'><xsl:value-of select='format-number(sum(db/reg/ValorTotalRepasseTerceiro),"0,00","euro")' /></td>														
					<td />
					<td />
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
	verificaAcao();
	
	addParmUrl("marOrdemServico","IdOrdemServico",'<?=$local_IdOrdemServico?>');
	addParmUrl("marOrdemServicoNovo","IdPessoa",'<?=$local_IdPessoa?>');
	addParmUrl("marOrdemServico","IdPessoa",'<?=$local_IdPessoa?>');
	addParmUrl("marLancamentoFinanceiro","IdPessoa",'<?=$local_IdPessoa?>');
	addParmUrl("marProcessoFinanceiro","IdProcessoFinanceiro",'<?=$local_IdProcessoFinanceiro?>');
	addParmUrl("marContasReceber","IdOrdemServico",'<?=$local_IdOrdemServico?>');
	addParmUrl("marContasReceber","IdPessoa",'<?=$local_IdPessoa?>');
	addParmUrl("marPessoa","IdPessoa",'<?=$local_IdPessoa?>');
	addParmUrl("marContrato","IdPessoa",'<?=$local_IdPessoa?>');
	addParmUrl("marContratoNovo","IdPessoa",'<?=$local_IdPessoa?>');
	addParmUrl("marReenvioEmail","IdPessoa",'<?=$local_IdPessoa?>');
	addParmUrl("marContaEventual","IdPessoa",'<?=$local_IdPessoa?>');
	addParmUrl("marContaEventualNovo","IdPessoa",'<?=$local_IdPessoa?>');
	addParmUrl("marContrato","IdContrato",'<?=$local_IdContrato?>');
	
	tableMultColor('tableListar',document.filtro.corRegRand.value);
	menu_form = false;
</script>
</xsl:template>
</xsl:stylesheet>
