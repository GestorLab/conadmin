<?
	$localModulo		=	1;
	$localOperacao		=	77;
	$localSuboperacao	=	"R";
		
	include ('../../files/conecta.php');
	include ('../../files/funcoes.php');
	include ('../../rotinas/verifica.php');
	 
	$localFiltro					= $_GET['Filtro'];
	$localOrdem						= $_GET['Ordem'];
	$localOrdemDirecao				= $_GET['OrdemDirecao'];
	$localTipoDado					= $_GET['TipoDado'];
	$localNome						= url_string_xsl($_GET['Nome'],'');
	$localRecebido					= $_GET['Recebido'];
	$localIdLocalCobranca			= $_GET['IdLocalCobranca'];
	$localValor						= url_string_xsl($_GET['Valor'],'');
	$localDataLancamentoIni			= url_string_xsl($_GET['DataLancamentoIni'],'');
	$localDataLancamentoFim			= url_string_xsl($_GET['DataLancamentoFim'],'');
	$localDataPagamentoIni			= url_string_xsl($_GET['DataPagamentoIni'],'');
	$localDataPagamentoFim			= url_string_xsl($_GET['DataPagamentoFim'],'');
	$localDataVencimentoIni			= url_string_xsl($_GET['DataVencimentoIni'],'');
	$localDataVencimentoFim			= url_string_xsl($_GET['DataVencimentoFim'],'');
	$localDescricaoServico			= url_string_xsl($_GET['DescricaoServico'],'');
	$localNumeroDocumento			= url_string_xsl($_GET['NumeroDocumento'],'');
	$localIdProcessoFinanceiro		= url_string_xsl($_GET['IdProcessoFinanceiro'],'');
	$localIdPais					= url_string_xsl($_GET['IdPais'],'');
	$localIdEstado					= url_string_xsl($_GET['IdEstado'],'');
	$localNomeCidade				= url_string_xsl($_GET['Cidade'],'');
	$localNumeroNF					= url_string_xsl($_GET['NumeroNF'],'');
		
	$localchNome					= $_GET['chNome'];	
	$localchRazao					= $_GET['chRazao'];
	$localchFone1					= $_GET['chFone1'];
	$localchFone2					= $_GET['chFone2'];
	$localchFone3					= $_GET['chFone3'];
	$localchCel						= $_GET['chCel'];
	$localchFax						= $_GET['chFax'];
	$localchCompF					= $_GET['chCompF'];
	$localchEmail					= $_GET['chEmail'];
	$localchNumD					= $_GET['chNumD'];
	$localchNumNF					= $_GET['chNumNF'];
	$localchDataF					= $_GET['chDataF'];
	$localchLCob					= $_GET['chLCob'];
	$localchDataL					= $_GET['chDataL'];
	$localchDataV					= $_GET['chDataV'];
	$localchDataP					= $_GET['chDataP'];
	$localchValor					= $_GET['chValor'];
	$localchValDe					= $_GET['chValDe'];
	$localchValDp					= $_GET['chValDp'];
	$localchValF					= $_GET['chValF'];	
	$localchLRec					= $_GET['chLRec'];
	$localchStat					= $_GET['chStat'];
	$localchObs						= $_GET['chObs'];	
	$localchLink					= $_GET['chLink'];

	$localqtdNome					= $_GET['qtdNome'];
	$localqtdRazao					= $_GET['qtdRazao'];
	$localqtdFone1					= $_GET['qtdFone1'];
	$localqtdFone2					= $_GET['qtdFone2'];
	$localqtdFone3					= $_GET['qtdFone3'];
	$localqtdCel					= $_GET['qtdCel'];
	$localqtdFax					= $_GET['qtdFax'];
	$localqtdCompF					= $_GET['qtdCompF'];
	$localqtdEmail					= $_GET['qtdEmail'];
	$localqtdNumD					= $_GET['qtdNumD'];
	$localqtdNumNF					= $_GET['qtdNumNF'];
	$localqtdDataF					= $_GET['qtdDataF'];
	$localqtdLCob					= $_GET['qtdLCob'];
	$localqtdDataL					= $_GET['qtdDataL'];
	$localqtdDataV					= $_GET['qtdDataV'];
	$localqtdDataP					= $_GET['qtdDataP'];
	$localqtdValor					= $_GET['qtdValor'];
	$localqtdValDe					= $_GET['qtdValDe'];
	$localqtdValDp					= $_GET['qtdValDp'];
	$localqtdValF					= $_GET['qtdValF'];	
	$localqtdLRec					= $_GET['qtdLRec'];
	$localqtdStat					= $_GET['qtdStat'];
	$localqtdObs					= $_GET['qtdObs'];	
	$localqtdLink					= $_GET['qtdLink'];
	$localtam						= $_GET['tam'];
	
	if($localOrdem == ''){							$localOrdem = "DataLancamento";		}
	if($localOrdemDirecao == ''){					$localOrdemDirecao = getCodigoInterno(7,6);	}
	if($localTipoDado == ''){						$localTipoDado = 'number';	}
	
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
		<script type = 'text/javascript' src = 'js/conta_receber.js'></script>
	</head>
	<body  onLoad="ativaNome('Contas a Receber/Excel')">
		<? include('filtro_conta_receber_avancado.php'); ?>
		<div id='carregando'>carregando</div>
		<div id='conteudo'>
			<table class='tableListar' cellspacing='0' Id='tableListar' style='width:<?=$localtam?>'>					
				<tr class='tableListarTitle'>
					<td class='id_listar'>
						<a href="javascript:filtro_ordenar_filtro('IdContaReceber','number')">Id <?=compara($localOrdem,"IdContaReceber",$ImgOrdernarASC,'')?></a>
					</td>
					<td id='titNome'>
						<a href="javascript:filtro_ordenar_filtro('Nome','text')">Nome Pessoa <?=$localtam?> <?=compara($localOrdem,"Nome",$ImgOrdernarASC,'')?></a>
					</td>
					<td id='titRazao'>
						<a href="javascript:filtro_ordenar_filtro('RazaoSocial','text')">Razão Social<?=compara($localOrdem,"RazaoSocial",$ImgOrdernarASC,'')?></a>
					</td>
					<td id='titFone1'>
						<a href="javascript:filtro_ordenar_filtro('Telefone1','text')">Fone Resid.<?=compara($localOrdem,"Telefone1",$ImgOrdernarASC,'')?></a>
					</td>
					<td id='titFone2'>
						<a href="javascript:filtro_ordenar_filtro('Telefone2','text')">Fone Comerc. (1)<?=compara($localOrdem,"Telefone2",$ImgOrdernarASC,'')?></a>
					</td>
					<td id='titFone3'>
						<a href="javascript:filtro_ordenar_filtro('Telefone3','text')">Fone Comerc. (2)<?=compara($localOrdem,"Telefone3",$ImgOrdernarASC,'')?></a>
					</td>
					<td id='titCel'>
						<a href="javascript:filtro_ordenar_filtro('Celular','text')">Celular<?=compara($localOrdem,"Celular",$ImgOrdernarASC,'')?></a>
					</td>
					<td id='titFax'>
						<a href="javascript:filtro_ordenar_filtro('Fax','text')">Fax<?=compara($localOrdem,"Fax",$ImgOrdernarASC,'')?></a>
					</td>
					<td id='titCompF'>
						<a href="javascript:filtro_ordenar_filtro('ComplementoTelefone','text')">Comp. Fone<?=compara($localOrdem,"ComplementoTelefone",$ImgOrdernarASC,'')?></a>
					</td>
					<td id='titEmail'>
						<a href="javascript:filtro_ordenar_filtro('Email','text')">E-mail<?=compara($localOrdem,"Email",$ImgOrdernarASC,'')?></a>
					</td>
					<td id='titNumD'>
						<a href="javascript:filtro_ordenar_filtro('NumeroDocumento','number')">Nº Doc. <?=compara($localOrdem,"NumeroDocumento",$ImgOrdernarASC,'')?></a>
					</td>
					<td id='titNumNF'>
						<a href="javascript:filtro_ordenar_filtro('NumeroNF','number')">Nº NF <?=compara($localOrdem,"NumeroNF",$ImgOrdernarASC,'')?></a>
					</td>
					<td id='titDataF'>
						<a href="javascript:filtro_ordenar_filtro('DataNF','number')">Data NF <?=compara($localOrdem,"DataNF",$ImgOrdernarASC,'')?></a>
					</td>
					<td id='titLCob'>
						<a href="javascript:filtro_ordenar_filtro('DescricaoLocalCobranca','text')">Local Cob. <?=compara($localOrdem,"DescricaoLocalCobranca",$ImgOrdernarASC,'')?></a>
					</td>
					<td id='titDataL'>
						<a href="javascript:filtro_ordenar_filtro('DataLancamento','number')">Data Lanç.<?=compara($localOrdem,"DataLancamento",$ImgOrdernarASC,'')?></a>
					</td>
					<td id='titDataV'>
						<a href="javascript:filtro_ordenar_filtro('DataVencimento','number')">Vencimento <?=compara($localOrdem,"DataVencimento",$ImgOrdernarASC,'')?></a>
					</td>
					<td id='titDataP'>
						<a href="javascript:filtro_ordenar_filtro('DataRecebimento','number')">Pagamento <?=compara($localOrdem,"DataRecebimento",$ImgOrdernarASC,'')?></a>
					</td>
					<td id='titValor' class='valor'>
						<a href="javascript:filtro_ordenar_filtro('ValorLancamento','number')">Valor (<?=getParametroSistema(5,1)?>)<?=compara($localOrdem,"ValorLancamento",$ImgOrdernarASC,'')?></a>
					</td>
					<td id='titValDp' class='valor'>
						<a href="javascript:filtro_ordenar_filtro('ValorDespesas','number')">Valor Desp. (<?=getParametroSistema(5,1)?>)<?=compara($localOrdem,"ValorDespesas",$ImgOrdernarASC,'')?></a>
					</td>
					<td id='titValDe' class='valor'>
						<a href="javascript:filtro_ordenar_filtro('ValorDesconto','number')">Valor Desc. (<?=getParametroSistema(5,1)?>)<?=compara($localOrdem,"ValorDesconto",$ImgOrdernarASC,'')?></a>
					</td>
					<td id='titValF' class='valor'>
						<a href="javascript:filtro_ordenar_filtro('Valor','number')">Valor Final (<?=getParametroSistema(5,1)?>)<?=compara($localOrdem,"Valor",$ImgOrdernarASC,'')?></a>
					</td>
					<td id='titLRec'>
						<a href="javascript:filtro_ordenar_filtro('DescricaoLocalRecebimento','text')">Local Rec. <?=compara($localOrdem,"DescricaoLocalRecebimento",$ImgOrdernarASC,'')?></a>
					</td>
					<td id='titStat'>
						<a href="javascript:filtro_ordenar_filtro('Status','text')">Status <?=compara($localOrdem,"Status",$ImgOrdernarASC,'')?></a>
					</td>
					<td id='titObs'>
						<a href="javascript:filtro_ordenar_filtro('Obs','text')">Obs <?=compara($localOrdem,"Obs",$ImgOrdernarASC,'')?></a>
					</td>
					<td id='titLink' />
					<td class='bt_lista' />
				</tr>
				<xsl:for-each select="db/reg">
				<xsl:sort order="<?=$localOrdemDirecao?>" select="<?=$localOrdem?>" data-type="<?=$localTipoDado?>" />
				<xsl:element name="tr">
					<xsl:attribute name="class">tableListarDados</xsl:attribute>
					<xsl:attribute name="width"><xsl:value-of select="tamNome"/></xsl:attribute>
					<xsl:attribute name="onmouseover">destacaRegistro(this,true)</xsl:attribute>
					<xsl:attribute name="onmouseout">destacaRegistro(this,false)</xsl:attribute>
					<xsl:attribute name="accessKey"><xsl:value-of select="IdContaReceber"/></xsl:attribute>
					<xsl:element name="td">
						<xsl:attribute name="bgcolor"><xsl:value-of select="Color"/></xsl:attribute>
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_conta_receber.php?IdContaReceber=<xsl:value-of select="IdContaReceber"/></xsl:attribute>
							<xsl:value-of select="IdContaReceber"/>
						</xsl:element>
					</xsl:element>
					<xsl:if test="chNome = 1"> 
						<xsl:element name="td">
							<xsl:attribute name="bgcolor"><xsl:value-of select="Color"/></xsl:attribute>
							<xsl:element name="a">
								<xsl:attribute name="href">cadastro_conta_receber.php?IdContaReceber=<xsl:value-of select="IdContaReceber"/></xsl:attribute>
								<xsl:value-of select="Nome"/><xsl:value-of select="tamNome"/>
							</xsl:element>
						</xsl:element>
					</xsl:if>
					<xsl:if test="chRazao = 1"> 
						<xsl:element name="td">
							<xsl:attribute name="bgcolor"><xsl:value-of select="Color"/></xsl:attribute>
							<xsl:element name="a">
								<xsl:attribute name="href">cadastro_conta_receber.php?IdContaReceber=<xsl:value-of select="IdContaReceber"/></xsl:attribute>
								<xsl:value-of select="RazaoSocial"/>
							</xsl:element>
						</xsl:element>
					</xsl:if>
					<xsl:if test="chFone1 = 1"> 
						<xsl:element name="td">
							<xsl:attribute name="bgcolor"><xsl:value-of select="Color"/></xsl:attribute>
							<xsl:element name="a">
								<xsl:attribute name="href">cadastro_conta_receber.php?IdContaReceber=<xsl:value-of select="IdContaReceber"/></xsl:attribute>
								<xsl:value-of select="Telefone1"/>
							</xsl:element>
						</xsl:element>
					</xsl:if>
					<xsl:if test="chFone2 = 1"> 
						<xsl:element name="td">
							<xsl:attribute name="bgcolor"><xsl:value-of select="Color"/></xsl:attribute>
							<xsl:element name="a">
								<xsl:attribute name="href">cadastro_conta_receber.php?IdContaReceber=<xsl:value-of select="IdContaReceber"/></xsl:attribute>
								<xsl:value-of select="Telefone2"/>
							</xsl:element>
						</xsl:element>
					</xsl:if>
					<xsl:if test="chFone3 = 1"> 
						<xsl:element name="td">
							<xsl:attribute name="bgcolor"><xsl:value-of select="Color"/></xsl:attribute>
							<xsl:element name="a">
								<xsl:attribute name="href">cadastro_conta_receber.php?IdContaReceber=<xsl:value-of select="IdContaReceber"/></xsl:attribute>
								<xsl:value-of select="Telefone3"/>
							</xsl:element>
						</xsl:element>
					</xsl:if>
					<xsl:if test="chCel = 1"> 
						<xsl:element name="td">
							<xsl:attribute name="bgcolor"><xsl:value-of select="Color"/></xsl:attribute>
							<xsl:element name="a">
								<xsl:attribute name="href">cadastro_conta_receber.php?IdContaReceber=<xsl:value-of select="IdContaReceber"/></xsl:attribute>
								<xsl:value-of select="Celular"/>
							</xsl:element>
						</xsl:element>
					</xsl:if>
					<xsl:if test="chFax = 1"> 
						<xsl:element name="td">
							<xsl:attribute name="bgcolor"><xsl:value-of select="Color"/></xsl:attribute>
							<xsl:element name="a">
								<xsl:attribute name="href">cadastro_conta_receber.php?IdContaReceber=<xsl:value-of select="IdContaReceber"/></xsl:attribute>
								<xsl:value-of select="Fax"/>
							</xsl:element>
						</xsl:element>
					</xsl:if>
					<xsl:if test="chCompF = 1"> 
						<xsl:element name="td">
							<xsl:attribute name="bgcolor"><xsl:value-of select="Color"/></xsl:attribute>
							<xsl:element name="a">
								<xsl:attribute name="href">cadastro_conta_receber.php?IdContaReceber=<xsl:value-of select="IdContaReceber"/></xsl:attribute>
								<xsl:value-of select="ComplementoTelefone"/>
							</xsl:element>
						</xsl:element>
					</xsl:if>
					<xsl:if test="chEmail = 1"> 
						<xsl:element name="td">
							<xsl:attribute name="bgcolor"><xsl:value-of select="Color"/></xsl:attribute>
							<xsl:element name="a">
								<xsl:attribute name="href">cadastro_conta_receber.php?IdContaReceber=<xsl:value-of select="IdContaReceber"/></xsl:attribute>
								<xsl:value-of select="Email"/>
							</xsl:element>
						</xsl:element>
					</xsl:if>
					<xsl:if test="chNumD = 1"> 
						<xsl:element name="td">
							<xsl:attribute name="bgcolor"><xsl:value-of select="Color"/></xsl:attribute>
							<xsl:element name="a">
								<xsl:attribute name="href">cadastro_conta_receber.php?IdContaReceber=<xsl:value-of select="IdContaReceber"/></xsl:attribute>
								<xsl:value-of select="NumeroDocumento"/>
							</xsl:element>
						</xsl:element>
					</xsl:if>
					<xsl:if test="chNumNF = 1"> 
						<xsl:element name="td">
							<xsl:attribute name="bgcolor"><xsl:value-of select="Color"/></xsl:attribute>
							<xsl:element name="a">
								<xsl:attribute name="href">cadastro_conta_receber.php?IdContaReceber=<xsl:value-of select="IdContaReceber"/></xsl:attribute>
								<xsl:value-of select="NumeroNF"/>
							</xsl:element>
						</xsl:element>
					</xsl:if>
					<xsl:if test="chDataF = 1"> 
						<xsl:element name="td">
							<xsl:attribute name="bgcolor"><xsl:value-of select="Color"/></xsl:attribute>
							<xsl:element name="a">
								<xsl:attribute name="href">cadastro_conta_receber.php?IdContaReceber=<xsl:value-of select="IdContaReceber"/></xsl:attribute>
								<xsl:value-of select="DataNFTemp"/>
							</xsl:element>
						</xsl:element>
					</xsl:if>
					<xsl:if test="chLCob = 1"> 
						<xsl:element name="td">
							<xsl:attribute name="bgcolor"><xsl:value-of select="Color"/></xsl:attribute>
							<xsl:element name="a">
								<xsl:attribute name="href">cadastro_conta_receber.php?IdContaReceber=<xsl:value-of select="IdContaReceber"/></xsl:attribute>
								<xsl:value-of select="DescricaoLocalCobranca"/>
							</xsl:element>
						</xsl:element>
					</xsl:if>
					<xsl:if test="chDataL = 1"> 
						<xsl:element name="td">
							<xsl:attribute name="bgcolor"><xsl:value-of select="Color"/></xsl:attribute>
							<xsl:element name="a">
								<xsl:attribute name="href">cadastro_conta_receber.php?IdContaReceber=<xsl:value-of select="IdContaReceber"/></xsl:attribute>
								<xsl:value-of select="DataLancamentoTemp"/>
							</xsl:element>
						</xsl:element>
					</xsl:if>
					<xsl:if test="chDataV = 1"> 
						<xsl:element name="td">
							<xsl:attribute name="bgcolor"><xsl:value-of select="Color"/></xsl:attribute>
							<xsl:element name="a">
								<xsl:attribute name="href">cadastro_conta_receber.php?IdContaReceber=<xsl:value-of select="IdContaReceber"/></xsl:attribute>
								<xsl:value-of select="DataVencimentoTemp"/>
							</xsl:element>
						</xsl:element>
					</xsl:if>
					<xsl:if test="chDataP = 1"> 
						<xsl:element name="td">
							<xsl:attribute name="bgcolor"><xsl:value-of select="Color"/></xsl:attribute>
							<xsl:element name="a">
								<xsl:attribute name="href">cadastro_conta_receber.php?IdContaReceber=<xsl:value-of select="IdContaReceber"/></xsl:attribute>
								<xsl:value-of select="DataRecebimentoTemp"/>
							</xsl:element>
						</xsl:element>
					</xsl:if>
					<xsl:if test="chValor = 1"> 
					<xsl:element name="td">
						<xsl:attribute name="bgcolor"><xsl:value-of select="Color"/></xsl:attribute>
							<xsl:attribute name="class">valor</xsl:attribute>
							<xsl:element name="a">
								<xsl:attribute name="href">cadastro_conta_receber.php?IdContaReceber=<xsl:value-of select="IdContaReceber"/></xsl:attribute>
								<xsl:value-of select='format-number(ValorLancamento,"0,00","euro")'/>
							</xsl:element>
						</xsl:element>
					</xsl:if>
					<xsl:if test="chValDp = 1"> 
					<xsl:element name="td">
						<xsl:attribute name="bgcolor"><xsl:value-of select="Color"/></xsl:attribute>
							<xsl:attribute name="class">valor</xsl:attribute>
							<xsl:element name="a">
								<xsl:attribute name="href">cadastro_conta_receber.php?IdContaReceber=<xsl:value-of select="IdContaReceber"/></xsl:attribute>
								<xsl:value-of select='format-number(ValorDespesas,"0,00","euro")'/>
							</xsl:element>
						</xsl:element>
					</xsl:if>
					<xsl:if test="chValDe = 1"> 
					<xsl:element name="td">
						<xsl:attribute name="bgcolor"><xsl:value-of select="Color"/></xsl:attribute>
							<xsl:attribute name="class">valor</xsl:attribute>
							<xsl:element name="a">
								<xsl:attribute name="href">cadastro_conta_receber.php?IdContaReceber=<xsl:value-of select="IdContaReceber"/></xsl:attribute>
								<xsl:value-of select='format-number(ValorDesconto,"0,00","euro")'/>
							</xsl:element>
						</xsl:element>
					</xsl:if>
					<xsl:if test="chValF = 1"> 
					<xsl:element name="td">
						<xsl:attribute name="bgcolor"><xsl:value-of select="Color"/></xsl:attribute>
							<xsl:attribute name="class">valor</xsl:attribute>
							<xsl:element name="a">
								<xsl:attribute name="href">cadastro_conta_receber.php?IdContaReceber=<xsl:value-of select="IdContaReceber"/></xsl:attribute>
								<xsl:value-of select='format-number(Valor,"0,00","euro")'/>
							</xsl:element>
						</xsl:element>
					</xsl:if>
					<xsl:if test="chLRec = 1"> 
						<xsl:element name="td">
							<xsl:attribute name="bgcolor"><xsl:value-of select="Color"/></xsl:attribute>
							<xsl:element name="a">
								<xsl:attribute name="href">cadastro_conta_receber.php?IdContaReceber=<xsl:value-of select="IdContaReceber"/></xsl:attribute>
								<xsl:value-of select="DescricaoLocalRecebimento"/>
							</xsl:element>
						</xsl:element>
					</xsl:if>
					<xsl:if test="chStat = 1"> 
						<xsl:element name="td">
							<xsl:attribute name="bgcolor"><xsl:value-of select="Color"/></xsl:attribute>
							<xsl:element name="a">
								<xsl:attribute name="href">cadastro_conta_receber.php?IdContaReceber=<xsl:value-of select="IdContaReceber"/></xsl:attribute>
								<xsl:value-of select="Status"/>
							</xsl:element>
						</xsl:element>
					</xsl:if>
					<xsl:if test="chObs = 1"> 
						<xsl:element name="td">
							<xsl:attribute name="bgcolor"><xsl:value-of select="Color"/></xsl:attribute>
							<xsl:element name="a">
								<xsl:attribute name="href">cadastro_conta_receber.php?IdContaReceber=<xsl:value-of select="IdContaReceber"/></xsl:attribute>
								<xsl:value-of select="Obs"/>
							</xsl:element>
						</xsl:element>
					</xsl:if>
					<xsl:if test="chLink = 1"> 
					<xsl:element name="td">
						<xsl:attribute name="bgcolor"><xsl:value-of select="Color"/></xsl:attribute>
							<xsl:element name="a">
								<xsl:attribute name="href"><xsl:value-of select="Link"/></xsl:attribute>
								<xsl:attribute name="target"><xsl:value-of select="Target"/></xsl:attribute>
								<xsl:value-of select="MsgLink"/>
							</xsl:element>
						</xsl:element>
					</xsl:if>
					<xsl:element name="td">
						<xsl:attribute name="bgcolor"><xsl:value-of select="Color"/></xsl:attribute>
						<xsl:attribute name="class">bt_lista</xsl:attribute>
						<xsl:element name="img">
							<xsl:attribute name="src"><xsl:value-of select="ImgExc"/></xsl:attribute>
							<xsl:attribute name="alt">Cancelar?</xsl:attribute>
							<xsl:attribute name="onClick">excluir(<xsl:value-of select="IdContaReceber"/>,<xsl:value-of select="IdStatus"/>)</xsl:attribute>
						</xsl:element>
					</xsl:element>
				</xsl:element>
				</xsl:for-each>
				<tr class='tableListarTitle'>
					<?
						if($localchNome == 1){
							if($localchRazao == 1){
								$colspan = 3;
							}else{
								$colspan = 2;
							}
						}else{
							$colspan = 1;	
						}
					?>
					<td colspan='<?=$colspan?>'>Total: <xsl:value-of select="count(db/reg)" /></td>
					<td id='sFone1'/>
					<td id='sFone2'/>
					<td id='sFone3'/>
					<td id='sCel'/>
					<td id='sFax'/>
					<td id='scompF'/>
					<td id='sEmail'/>
					<td id='sNumD'/>
					<td id='sNumNF'/>
					<td id='sDataF'/>
					<td id='sLCob'/>
					<td id='sDataL'/>
					<td id='sDataV'/>
					<td id='sDataP'/>
					<td id='sValor' class='valor'><xsl:value-of select='format-number(sum(db/reg/ValorLancamento),"0,00","euro")' /></td>
					<td id='sValDp' class='valor'><xsl:value-of select='format-number(sum(db/reg/ValorDespesas),"0,00","euro")' /></td>
					<td id='sValDe' class='valor'><xsl:value-of select='format-number(sum(db/reg/ValorDesconto),"0,00","euro")' /></td>
					<td id='sValF' class='valor'><xsl:value-of select='format-number(sum(db/reg/Valor),"0,00","euro")' /></td>
					<td id='sLRec'/>
					<td id='sStat'/>
					<td id='sObs'/>
					<td id='sLink'/>
					<td />
				</tr>
			</table>
		</div>
		<table>
			<tr>
				<td class='find' />
				<td><h1 id='helpText' name='helpText' /></td>
			</tr>
		</table>
	</body>	
</html>
<script>
	tableMultColor('tableListar',document.filtro.corRegRand.value);
	menu_form = false;
	
	function inicia(){
		if('<?=$localchNome?> '== 1)	document.filtro.chNome.checked	= true;		else	document.filtro.chNome.checked	= false;
		if('<?=$localchRazao?> '== 1)	document.filtro.chRazao.checked	= true;		else	document.filtro.chRazao.checked	= false;
		if('<?=$localchFone1?> '== 1)	document.filtro.chFone1.checked	= true;		else	document.filtro.chFone1.checked	= false;
		if('<?=$localchFone2?> '== 1)	document.filtro.chFone2.checked	= true;		else	document.filtro.chFone2.checked	= false;
		if('<?=$localchFone3?> '== 1)	document.filtro.chFone3.checked	= true;		else	document.filtro.chFone3.checked	= false;
		if('<?=$localchCel?> '== 1)		document.filtro.chCel.checked	= true;		else	document.filtro.chCel.checked	= false;
		if('<?=$localchFax?> '== 1)		document.filtro.chFax.checked	= true;		else	document.filtro.chFax.checked	= false;
		if('<?=$localchCompF?> '== 1)	document.filtro.chCompF.checked	= true;		else	document.filtro.chCompF.checked	= false;
		if('<?=$localchEmail?> '== 1)	document.filtro.chEmail.checked	= true;		else	document.filtro.chEmail.checked	= false;
		if('<?=$localchNumD?> '== 1)	document.filtro.chNumD.checked	= true;		else	document.filtro.chNumD.checked	= false;
		if('<?=$localchNumNF?> '== 1)	document.filtro.chNumNF.checked	= true;		else	document.filtro.chNumNF.checked	= false;
		if('<?=$localchDataF?> '== 1)	document.filtro.chDataF.checked	= true;		else	document.filtro.chDataF.checked	= false;
		if('<?=$localchLCob?> '== 1)	document.filtro.chLCob.checked	= true;		else	document.filtro.chLCob.checked	= false;
		if('<?=$localchDataL?> '== 1)	document.filtro.chDataL.checked	= true;		else	document.filtro.chDataL.checked	= false;
		if('<?=$localchDataV?> '== 1)	document.filtro.chDataV.checked	= true;		else	document.filtro.chDataV.checked	= false;
		if('<?=$localchDataP?> '== 1)	document.filtro.chDataP.checked	= true;		else	document.filtro.chDataP.checked	= false;
		if('<?=$localchValor?> '== 1)	document.filtro.chValor.checked	= true;		else	document.filtro.chValor.checked	= false;
		if('<?=$localchValDp?> '== 1)	document.filtro.chValDp.checked	= true;		else	document.filtro.chValDp.checked	= false;
		if('<?=$localchValDe?> '== 1)	document.filtro.chValDe.checked	= true;		else	document.filtro.chValDe.checked	= false;
		if('<?=$localchValF?> '== 1)	document.filtro.chValF.checked	= true;		else	document.filtro.chValF.checked	= false;
		if('<?=$localchLRec?> '== 1)	document.filtro.chLRec.checked	= true;		else	document.filtro.chLRec.checked	= false;
		if('<?=$localchStat?> '== 1)	document.filtro.chStat.checked	= true;		else	document.filtro.chStat.checked	= false;
		if('<?=$localchObs?> '== 1)		document.filtro.chObs.checked	= true;		else	document.filtro.chObs.checked	= false;
		if('<?=$localchLink?> '== 1)	document.filtro.chLink.checked	= true;		else	document.filtro.chLink.checked	= false;
		
		if(document.filtro.chNome.checked == true)		document.getElementById('titNome').style.display	=	'block';	else 	document.getElementById('titNome').style.display		=	'none';
		if(document.filtro.chRazao.checked == true)		document.getElementById('titRazao').style.display	=	'block';	else	document.getElementById('titRazao').style.display	=	'none';
		if(document.filtro.chFone1.checked == true)		document.getElementById('titFone1').style.display	=	'block';	else	document.getElementById('titFone1').style.display		=	'none';
		if(document.filtro.chFone2.checked == true)		document.getElementById('titFone2').style.display	=	'block';	else	document.getElementById('titFone2').style.display		=	'none';
		if(document.filtro.chFone3.checked == true)		document.getElementById('titFone3').style.display	=	'block';	else	document.getElementById('titFone3').style.display		=	'none';
		if(document.filtro.chCel.checked == true)		document.getElementById('titCel').style.display		=	'block';	else	document.getElementById('titCel').style.display			=	'none';
		if(document.filtro.chFax.checked == true)		document.getElementById('titFax').style.display		=	'block';	else	document.getElementById('titFax').style.display			=	'none';
		if(document.filtro.chCompF.checked == true)		document.getElementById('titCompF').style.display	=	'block';	else	document.getElementById('titCompF').style.display		=	'none';
		if(document.filtro.chEmail.checked == true)		document.getElementById('titEmail').style.display	=	'block';	else	document.getElementById('titEmail').style.display		=	'none';
		if(document.filtro.chNumD.checked == true)		document.getElementById('titNumD').style.display	=	'block';	else	document.getElementById('titNumD').style.display		=	'none';
		if(document.filtro.chNumNF.checked == true)		document.getElementById('titNumNF').style.display	=	'block';	else	document.getElementById('titNumNF').style.display		=	'none';
		if(document.filtro.chDataF.checked == true)		document.getElementById('titDataF').style.display	=	'block';	else	document.getElementById('titDataF').style.display		=	'none';
		if(document.filtro.chLCob.checked == true)		document.getElementById('titLCob').style.display	=	'block';	else	document.getElementById('titLCob').style.display		=	'none';
		if(document.filtro.chDataL.checked == true)		document.getElementById('titDataL').style.display	=	'block';	else	document.getElementById('titDataL').style.display		=	'none';
		if(document.filtro.chDataV.checked == true)		document.getElementById('titDataV').style.display	=	'block';	else	document.getElementById('titDataV').style.display		=	'none';
		if(document.filtro.chDataP.checked == true)		document.getElementById('titDataP').style.display	=	'block';	else	document.getElementById('titDataP').style.display		=	'none';
		if(document.filtro.chValor.checked == true)		document.getElementById('titValor').style.display	=	'block';	else	document.getElementById('titValor').style.display		=	'none';
		if(document.filtro.chValDp.checked == true)		document.getElementById('titValDp').style.display	=	'block';	else	document.getElementById('titValDp').style.display		=	'none';
		if(document.filtro.chValDe.checked == true)		document.getElementById('titValDe').style.display	=	'block';	else	document.getElementById('titValDe').style.display		=	'none';
		if(document.filtro.chValF.checked == true)		document.getElementById('titValF').style.display	=	'block';	else	document.getElementById('titValF').style.display		=	'none';
		if(document.filtro.chLRec.checked == true)		document.getElementById('titLRec').style.display	=	'block';	else	document.getElementById('titLRec').style.display		=	'none';
		if(document.filtro.chStat.checked == true)		document.getElementById('titStat').style.display	=	'block';	else	document.getElementById('titStat').style.display		=	'none';
		if(document.filtro.chObs.checked == true)		document.getElementById('titObs').style.display		=	'block';	else	document.getElementById('titObs').style.display			=	'none';
		if(document.filtro.chLink.checked == true)		document.getElementById('titLink').style.display	=	'block';	else	document.getElementById('titLink').style.display		=	'none';
	
		if(document.filtro.chFone1.checked == true)		document.getElementById('sFone1').style.display	=	'block';	else	document.getElementById('sFone1').style.display		=	'none';
		if(document.filtro.chFone2.checked == true)		document.getElementById('sFone2').style.display	=	'block';	else	document.getElementById('sFone2').style.display		=	'none';
		if(document.filtro.chFone3.checked == true)		document.getElementById('sFone3').style.display	=	'block';	else	document.getElementById('sFone3').style.display		=	'none';
		if(document.filtro.chCel.checked == true)		document.getElementById('sCel').style.display	=	'block';	else	document.getElementById('sCel').style.display		=	'none';
		if(document.filtro.chFax.checked == true)		document.getElementById('sFax').style.display	=	'block';	else	document.getElementById('sFax').style.display		=	'none';
		if(document.filtro.chCompF.checked == true)		document.getElementById('sCompF').style.display	=	'block';	else	document.getElementById('sCompF').style.display		=	'none';
		if(document.filtro.chEmail.checked == true)		document.getElementById('sEmail').style.display	=	'block';	else	document.getElementById('sEmail').style.display		=	'none';
		if(document.filtro.chNumD.checked == true)		document.getElementById('sNumD').style.display	=	'block';	else	document.getElementById('sNumD').style.display		=	'none';
		if(document.filtro.chNumNF.checked == true)		document.getElementById('sNumNF').style.display	=	'block';	else	document.getElementById('sNumNF').style.display		=	'none';
		if(document.filtro.chDataF.checked == true)		document.getElementById('sDataF').style.display	=	'block';	else	document.getElementById('sDataF').style.display		=	'none';
		if(document.filtro.chLCob.checked == true)		document.getElementById('sLCob').style.display	=	'block';	else	document.getElementById('sLCob').style.display		=	'none';
		if(document.filtro.chDataL.checked == true)		document.getElementById('sDataL').style.display	=	'block';	else	document.getElementById('sDataL').style.display		=	'none';
		if(document.filtro.chDataV.checked == true)		document.getElementById('sDataV').style.display	=	'block';	else	document.getElementById('sDataV').style.display		=	'none';
		if(document.filtro.chDataP.checked == true)		document.getElementById('sDataP').style.display	=	'block';	else	document.getElementById('sDataP').style.display		=	'none';
		if(document.filtro.chValor.checked == true)		document.getElementById('sValor').style.display	=	'block';	else	document.getElementById('sValor').style.display		=	'none';
		if(document.filtro.chValDp.checked == true)		document.getElementById('sValDp').style.display	=	'block';	else	document.getElementById('sValDp').style.display		=	'none';
		if(document.filtro.chValDe.checked == true)		document.getElementById('sValDe').style.display	=	'block';	else	document.getElementById('sValDe').style.display		=	'none';
		if(document.filtro.chValF.checked == true)		document.getElementById('sValF').style.display	=	'block';	else	document.getElementById('sValF').style.display		=	'none';
		if(document.filtro.chLRec.checked == true)		document.getElementById('sLRec').style.display	=	'block';	else	document.getElementById('sLRec').style.display		=	'none';
		if(document.filtro.chStat.checked == true)		document.getElementById('sStat').style.display	=	'block';	else	document.getElementById('sStat').style.display		=	'none';
		if(document.filtro.chObs.checked == true)		document.getElementById('sObs').style.display	=	'block';	else	document.getElementById('sObs').style.display		=	'none';
		if(document.filtro.chLink.checked == true)		document.getElementById('sLink').style.display	=	'block';	else	document.getElementById('sLink').style.display		=	'none';
	}
	
	inicia();
</script>
</xsl:template>
</xsl:stylesheet>

