<?
	$localModulo		=	1;
	$localOperacao		=	27;
	$localSuboperacao	=	"R";
	
	include ('../../files/conecta.php');
	include ('../../files/funcoes.php');
	include ('../../rotinas/verifica.php');
	 
		
	$localFiltro				= $_GET['Filtro'];
	$localOrdem					= $_GET['Ordem'];
	$localOrdemDirecao			= $_GET['OrdemDirecao'];
	$localTipoDado				= $_GET['TipoDado'];
	$localNome					= url_string_xsl($_GET['Nome'],'url',false);
	$localEmail					= url_string_xsl($_GET['Email'],'url',false);
	$localCelular				= url_string_xsl($_GET['Celular'],'url',false);
	$localCampo					= url_string_xsl($_GET['Campo'],'');
	$localValor					= url_string_xsl($_GET['Valor'],'url',false);
	$localIdTipoMensagem		= $_GET['IdTipoMensagem'];
	$localIdStatus				= $_GET['IdStatus'];
	$localIdHistoricoMensagem	= $_GET['IdHistoricoMensagem'];
	$localIdPessoa				= $_GET['IdPessoa'];
	$localErro					= $_GET['Erro'];
	$localLimit					= $_GET['Limit'];

	if($localOrdem == ''){							$localOrdem = "DataCriacao";		}
	if($localOrdemDirecao == ''){					
		if(getCodigoInterno(7,6) == "ascending"){
			$localOrdemDirecao = "descending";	
		}else{
			$localOrdemDirecao = getCodigoInterno(7,6);	
		}
	}	
	if($localTipoDado == ''){						$localTipoDado = 'number';	}
	
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
		<script type = 'text/javascript' src = 'js/reenvio_mensagem.js'></script>
		<script type = 'text/javascript' src = 'js/reenvio_mensagem_default.js'></script>
	</head>
	<body onLoad="ativaNome('<?=dicionario(58)?>')">
		<? include('filtro_reenvio_mensagem.php'); ?>
		<div id='carregando'><?=dicionario(17)?></div>
		<div id='conteudo'>
			<table class='tableListar' cellspacing='0' Id='tableListar'>
				<tr class='tableListarTitle'>
					<td class='id_listar'>
						<a href="javascript:filtro_ordenar('IdHistoricoMensagem','number')"><?=dicionario(141)?><?=compara($localOrdem,"IdHistoricoMensagem",$ImgOrdernarASC,'')?></a>
					</td>
					<td>
						<a href="javascript:filtro_ordenar('Nome')"><?=visualizarBuscaPessoa(getCodigoInterno(3,24))?> <?=compara($localOrdem,"Nome",$ImgOrdernarASC,'')?></a>
					</td>
					<?
						if($localIdTipoMensagem == 29 || $localIdTipoMensagem == 32){
							echo "	<td>
										<a href=\"javascript:filtro_ordenar('Celular','number')\">".dicionario(890)." ".compara($localOrdem,"Celular",$ImgOrdernarASC,'')."</a>
									</td>";
						}else{
							echo "	<td>
										<a href=\"javascript:filtro_ordenar('Email','text')\">".dicionario(890)." ".compara($localOrdem,"Email",$ImgOrdernarASC,'')."</a>
									</td>";
						}
					?>
					<td>
						<a href="javascript:filtro_ordenar('Titulo,'text')"><?=dicionario(718)?> <?=compara($localOrdem,"Titulo",$ImgOrdernarASC,'')?></a>
					</td>
					<td>
						<a href="javascript:filtro_ordenar('DataCriacao','number')"><?=dicionario(133)?> <?=compara($localOrdem,"DataCriacao",$ImgOrdernarASC,'')?></a>
					</td>
					<td>
						<a href="javascript:filtro_ordenar('DataEnvio','number')"><?=dicionario(730)?> <?=compara($localOrdem,"DataEnvio",$ImgOrdernarASC,'')?></a>
					</td>
					<td>
						<a href="javascript:filtro_ordenar('IdStatus','number')"><?=dicionario(140)?> <?=compara($localOrdem,"IdStatus",$ImgOrdernarASC,'')?></a>
					</td>
					<td class='bt_lista' />
					<td class='bt_lista' />
				</tr>
				<xsl:for-each select="db/reg">
				<xsl:sort order="<?=$localOrdemDirecao?>" select="<?=$localOrdem?>" data-type="<?=$localTipoDado?>" />
				<xsl:element name="tr">
					<xsl:attribute name="class">tableListarDados</xsl:attribute>
					<xsl:attribute name="onmouseover">destacaRegistro(this,true)</xsl:attribute>
					<xsl:attribute name="onmouseout">destacaRegistro(this,false)</xsl:attribute>
					<xsl:attribute name="accessKey"><xsl:value-of select="IdHistoricoMensagem"/></xsl:attribute>
					
					<xsl:element name="td">
						<xsl:attribute name="bgcolor"><xsl:value-of select="Color"/></xsl:attribute>
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_reenvio_mensagem.php?IdHistoricoMensagem=<xsl:value-of select="IdHistoricoMensagem"/></xsl:attribute>
							<xsl:value-of select="IdHistoricoMensagem"/>
						</xsl:element>
					</xsl:element>
					
					<xsl:element name="td">
						<xsl:attribute name="bgcolor"><xsl:value-of select="Color"/></xsl:attribute>
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_reenvio_mensagem.php?IdHistoricoMensagem=<xsl:value-of select="IdHistoricoMensagem"/></xsl:attribute>
							<xsl:value-of select="Nome"/>
						</xsl:element>
					</xsl:element>
					<?
						if($localIdTipoMensagem == 29 || $localIdTipoMensagem == 32){
							echo"<xsl:element name='td'>
									<xsl:attribute name='bgcolor'><xsl:value-of select='Color'/></xsl:attribute>
									<xsl:element name='a'>
										<xsl:attribute name='href'>cadastro_reenvio_mensagem.php?IdHistoricoMensagem=<xsl:value-of select='IdHistoricoMensagem'/></xsl:attribute>
										<xsl:value-of select='Celular'/>
									</xsl:element>
								</xsl:element>";
						}else{
							echo"<xsl:element name='td'>
									<xsl:attribute name='bgcolor'><xsl:value-of select='Color'/></xsl:attribute>
									<xsl:element name='a'>
										<xsl:attribute name='href'>cadastro_reenvio_mensagem.php?IdHistoricoMensagem=<xsl:value-of select='IdHistoricoMensagem'/></xsl:attribute>
										<xsl:value-of select='Email'/>
									</xsl:element>
								</xsl:element>";
						}
					?>
					<xsl:element name="td">
						<xsl:attribute name="bgcolor"><xsl:value-of select="Color"/></xsl:attribute>
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_reenvio_mensagem.php?IdHistoricoMensagem=<xsl:value-of select="IdHistoricoMensagem"/></xsl:attribute>
							<xsl:value-of select="Titulo"/>
						</xsl:element>
					</xsl:element>
					
					<xsl:element name="td">
						<xsl:attribute name="bgcolor"><xsl:value-of select="Color"/></xsl:attribute>
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_reenvio_mensagem.php?IdHistoricoMensagem=<xsl:value-of select="IdHistoricoMensagem"/></xsl:attribute>
							<xsl:value-of select="DataCriacaoTemp"/>
						</xsl:element>
					</xsl:element>
					
					<xsl:element name="td">
						<xsl:attribute name="bgcolor"><xsl:value-of select="Color"/></xsl:attribute>
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_reenvio_mensagem.php?IdHistoricoMensagem=<xsl:value-of select="IdHistoricoMensagem"/></xsl:attribute>
							<xsl:value-of select="DataEnvioTemp"/>
						</xsl:element>
					</xsl:element>
					
					<xsl:element name="td">
						<xsl:attribute name="bgcolor"><xsl:value-of select="Color"/></xsl:attribute>
						<xsl:element name="a">
							<xsl:attribute name="href">cadastro_reenvio_mensagem.php?IdHistoricoMensagem=<xsl:value-of select="IdHistoricoMensagem"/></xsl:attribute>
							<xsl:value-of select="Status"/>
						</xsl:element>
					</xsl:element>
					
					<xsl:element name="td">
						<xsl:attribute name="bgcolor"><xsl:value-of select="Color"/></xsl:attribute>
						<xsl:element name="img">
							<xsl:attribute name="src">../../img/estrutura_sistema/ico_outlook.gif</xsl:attribute>
							<xsl:attribute name="alt">Re-Enviar E-mail?</xsl:attribute>
							<xsl:attribute name="onClick">reenviar(<xsl:value-of select="IdHistoricoMensagem"/>)</xsl:attribute>
						</xsl:element>
					</xsl:element>					
					
					<xsl:element name="td">
						<xsl:attribute name="bgcolor"><xsl:value-of select="Color"/></xsl:attribute>
						<xsl:element name="img">
							<xsl:attribute name="src"><xsl:value-of select="NaoEnviarEmailIco"/></xsl:attribute>
							<xsl:attribute name="onClick"><xsl:value-of select="NaoEnviarEmail"/></xsl:attribute>
						</xsl:element>
					</xsl:element>
					
				</xsl:element>
				</xsl:for-each>				
				<tr class='tableListarTitle'>
					<td colspan='10'><?=dicionario(140)?>: <xsl:value-of select="count(db/reg)" /></td>
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
	
	addParmUrl("marReenvioMensagem","IdHistoricoMensagem",'<?=$localIdHistoricoMensagem?>');
	addParmUrl("marPessoa","IdPessoa",'<?=$localIdPessoa?>');
	addParmUrl("marContasReceber","IdPessoa",'<?=$localIdPessoa?>');
	addParmUrl("marPessoa","IdPessoa",'<?=$localIdPessoa?>');
	
	<?
		if($localErro != ''){
			echo "mensagens($localErro);";
		}
	?>
	
	function reenviar(IdHistoricoMensagem){
		window.location = "cadastro_enviar_mensagem.php?IdHistoricoMensagem="+IdHistoricoMensagem;
/*		{
			var xmlhttp   = false;
			if (window.XMLHttpRequest) { // Mozilla, Safari,...
    			xmlhttp = new XMLHttpRequest();
		        if(xmlhttp.overrideMimeType){
		    //    	xmlhttp.overrideMimeType('text/xml');
				}
			}else if (window.ActiveXObject){ // IE
				try{
					xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
				}catch(e){
					try{
						xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
		            } catch (e) {}
     		   }
    		}
    
   			url = "rotinas/reenviar_email.php?IdEmail="+IdEmail;
			xmlhttp.open("GET", url,true);
	
			xmlhttp.onreadystatechange = function(){ 

				// Carregando...
				carregando(true);

				if(xmlhttp.readyState == 4){ 
					if(xmlhttp.status == 200){
						var numMsg = parseInt(xmlhttp.responseText);
						mensagens(numMsg);
					}
				}
				// Fim de Carregando
				carregando(false);
				return true;
			}
			xmlhttp.send(null);
		}*/
	}
	
	tableMultColor('tableListar',document.filtro.corRegRand.value);
	menu_form = false;
</script>
</xsl:template>
</xsl:stylesheet>
