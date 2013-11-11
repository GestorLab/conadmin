<?
	$localModulo		=	1;
	$localOperacao		=	26;
	$localSuboperacao	=	"R";
	
	include ('../../files/conecta.php');
	include ('../../files/funcoes.php');
	include ('../../rotinas/verifica.php');

	$localFiltro					= $_GET['Filtro'];
	$localOrdem						= $_GET['Ordem'];
	$localOrdemDirecao				= $_GET['OrdemDirecao'];
	$localTipoDado					= $_GET['TipoDado'];
	$localNome						= url_string_xsl($_GET['Nome'],'URL',false);
	$localDescricaoServico			= url_string_xsl($_GET['DescricaoServico'],'URL',false);
	$localIdStatus					= $_GET['IdStatus'];
	$localFaturado					= $_GET['Faturado'];
	$local_IdPessoa					= $_GET['IdPessoa'];
	$local_IdContrato				= $_GET['IdContrato'];
	$local_IdOrdemServico			= $_GET['IdOrdemServico'];
	$local_IdContaReceber			= $_GET['IdContaReceber'];
	$local_IdContrato				= $_GET['IdContrato'];
	$local_IdContaEventual			= $_GET['IdContaEventual'];
	$local_IdProcessoFinanceiro		= $_GET['IdProcessoFinanceiro'];	
	$local_FormaCobranca			= $_GET['FormaCobranca'];	
	$local_TipoPessoa				= $_GET['TipoPessoa'];	
	$localCampo						= url_string_xsl($_GET['Campo'],'URL',false);
	$localValor						= url_string_xsl($_GET['Valor'],'URL',false);	
	$Limit							= $_GET['Limit'];

	if($localOrdem == ''){
		switch(getCodigoInterno(3,243)){
			case 'Id':
				$localOrdem = "IdOrdemServico";
				break;
			case 'Nome Pessoa';
				$localOrdem = "Nome";
				break;
			case 'Tipo OS';
				$localOrdem = "DescricaoTipoOrdemServico";
				break;
			case 'Responsável';
				$localOrdem = "LoginSupervisor";
				break;
			case 'Nome Serviço';
				$localOrdem = "DescricaoServico";
				break;
			case 'Descrição OS';
				$localOrdem = "DescricaoOS";
				break;
			case 'Valor';
				$localOrdem = "Valor";
				break;
			case 'Data Fatura';
				$localOrdem = "DataFaturamento";
				break;
			case 'Data Criação';
				$localOrdem = "DataCriacao";
				break;
			case 'Tempo Aber.';
				$localOrdem = "DiaAbertura";
				break;
			case 'Status';
				$localOrdem = "Status";
				break;
			default:
				$localOrdem = "DataCriacao";
		}		
	}
	if($localOrdemDirecao == ''){			$localOrdemDirecao = "descending";	}	
	if($localTipoDado == ''){				$localTipoDado = 'number';	}
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
		<script type = 'text/javascript' src = 'js/ordem_servico_busca_pessoa_aproximada.js'></script>
	</head>
	<body  onLoad="ativaNome('<?=dicionario(29)?>')">
		<? include('filtro_ordem_servico.php'); ?>
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
						<a href="javascript:filtro_ordenar('DescricaoTipoOrdemServico')"><?=dicionario(487)?><?=compara($localOrdem,"DescricaoTipoOrdemServico",$ImgOrdernarASC,'')?></a>
					</td>
					<td>
						<a href="javascript:filtro_ordenar('LoginSupervisor')"><?=dicionario(488)?><?=compara($localOrdem,"LoginSupervisor",$ImgOrdernarASC,'')?></a>
					</td>
					<td>
						<a href="javascript:filtro_ordenar('DescricaoServico')"><?=dicionario(223)?><?=compara($localOrdem,"DescricaoServico",$ImgOrdernarASC,'')?></a>
					</td>
					<td>
						<a href="javascript:filtro_ordenar('DescricaoOS')"><?=dicionario(456)?> <?=compara($localOrdem,"DescricaoOS",$ImgOrdernarASC,'')?></a>
					</td>
					<td class='valor'>
						<a href="javascript:filtro_ordenar('Valor','number')"><?=dicionario(204)?> (<?=getParametroSistema(5,1)?>) <?=compara($localOrdem,"Valor",$ImgOrdernarASC,'')?></a>
					</td>
					<td>
						<a href="javascript:filtro_ordenar('DataFaturamento','number')"><?=dicionario(489)?><?=compara($localOrdem,"DataFaturamento",$ImgOrdernarASC,'')?></a>
					</td>
					<td>
						<a href="javascript:filtro_ordenar('DataCriacao','number')" ><?=dicionario(490)?><?=compara($localOrdem,"DataCriacao",$ImgOrdernarASC,'')?></a>
					</td>
					<td>
						<a href="javascript:filtro_ordenar('DiaAbertura','number')"><?=dicionario(431)?>.<?=compara($localOrdem,"DiaAbertura",$ImgOrdernarASC,'')?></a>
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
							<xsl:value-of select="DescricaoTipoOrdemServico"/>
						</xsl:element>
					</td>
					<td>
						<xsl:attribute name="bgcolor"><xsl:value-of select="Color"/></xsl:attribute>
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_ordem_servico.php?IdOrdemServico=<xsl:value-of select="IdOrdemServico"/></xsl:attribute>
							<xsl:value-of select="LoginSupervisor"/>
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
							<xsl:value-of select="DescricaoOS"/>
						</xsl:element>
					</td>
					<td class='valor'>
						<xsl:attribute name="bgcolor"><xsl:value-of select="Color"/></xsl:attribute>
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_ordem_servico.php?IdOrdemServico=<xsl:value-of select="IdOrdemServico"/></xsl:attribute>
							<xsl:value-of select='format-number(Valor,"0,00","euro")'/>
						</xsl:element>
					</td>
					<td>
						<xsl:attribute name="bgcolor"><xsl:value-of select="Color"/></xsl:attribute>
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_ordem_servico.php?IdOrdemServico=<xsl:value-of select="IdOrdemServico"/></xsl:attribute>
							<xsl:value-of select="DataFaturamentoTemp"/>
						</xsl:element>
					</td>
					<td>
						<xsl:attribute name="bgcolor"><xsl:value-of select="Color"/></xsl:attribute>
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_ordem_servico.php?IdOrdemServico=<xsl:value-of select="IdOrdemServico"/></xsl:attribute>
							<xsl:value-of select="DataCriacaoTemp"/>
						</xsl:element>
					</td>
					<td>
						<xsl:attribute name="bgcolor"><xsl:value-of select="Color"/></xsl:attribute>
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_ordem_servico.php?IdOrdemServico=<xsl:value-of select="IdOrdemServico"/></xsl:attribute>
							<xsl:value-of select="TempoAbertura"/>
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
					<td />	
					<td class='valor' id='tableListarValor'><xsl:value-of select='format-number(sum(db/reg/Valor),"0,00","euro")' /></td>
					<td />
					<td />
					<td />
					<td />					
					<td />					
				</tr>
			</table>
			<table>
				<tr>
					<td class='find'/>
					<td><h1 id='helpText' name='helpText'/></td>
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
	addParmUrl("marReenvioMensagem","IdPessoa",'<?=$local_IdPessoa?>');
	addParmUrl("marContaEventual","IdPessoa",'<?=$local_IdPessoa?>');
	addParmUrl("marContaEventualNovo","IdPessoa",'<?=$local_IdPessoa?>');
	addParmUrl("marContrato","IdContrato",'<?=$local_IdContrato?>');
	
	tableMultColor('tableListar',document.filtro.corRegRand.value);
	menu_form = false;	
</script>
</xsl:template>
</xsl:stylesheet>
