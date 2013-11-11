<?
	include ('../../files/conecta.php');
	include ('../../rotinas/verifica.php');
	include ('../../files/funcoes.php');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
	<head>
		<title><?=getParametroSistema(4,1)?></title>
		<link rel = 'stylesheet' type = 'text/css' href = '../../css/conteudo.css' />
		<link rel = 'stylesheet' type = 'text/css' href = '../../css/default.css' />
		<script type = 'text/javascript' src = '../../js/funcoes.js'></script>
		<script type = 'text/javascript' src = '../../js/incremental_search.js'></script>
		<script type = 'text/javascript' src = '../../js/mensagens.js'></script>
	</head>
	<body>
		<div id='tit'>Busca Processo Financeiro</div>
		<div id='filtro_busca'>
			<form name='formulario2' method='post'>
				<table>
					<tr>
						<td class='descCampo'>Mês Referência</td>
						<td class='descCampo'>Menor Vencimento</td>
						<td class='descCampo'>Status</td>
					</tr>
					<tr>
						<td class='campo'>
							<input type='text' name='MesReferencia' autocomplete="off" value='' style='width:90px' maxlength='7' onkeyup="busca_processo_financeiro_lista()" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onkeypress="mascara(this,event,'mes')" tabindex='1'>
						</tr>
						<td class='campo'>	
							<input type='text' name='MenorVencimento' autocomplete="off" value='' style='width:105px' maxlength='7' onkeyup="busca_processo_financeiro_lista()" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onkeypress="mascara(this,event,'mes')" tabindex='2'>
						</td>
						<td  class='campo'>
						<select name='IdStatus' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" style='width:125px' onChange="busca_processo_financeiro_lista()">
							<option value=''>Todos</option>
							<?
								$sql = "select IdParametroSistema, ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema=29";
								$res = mysql_query($sql,$con);
								while($lin = mysql_fetch_array($res)){
									echo "<option value='$lin[IdParametroSistema]' ".compara($localIdStatus,$lin[IdParametroSistema],"selected='selected'","").">$lin[ValorParametroSistema]</option>";
								}
							?>
						</select>
					</td>
					</tr>
				</table>
			</form>
		</div>
		<div id='carregando'>carregando...</div>
		<div class='listaDados' style='height: 180px;'>
			<div id='listaDadosQuadro'>&nbsp;</div>
		</div>
		<form name='formulario' method='post' action='busca_processo_financeiro.php' onSubmit='return validar()'>
			<input type='hidden' name='IdProcessoFinanceiro' value=''>
			<table>
				<tr>
					<td>
						<input type='submit' value='Ok' class='botao'>
						<input type='button' value='Cancelar' onClick='window.close()' class='botao'>
					</td>
				</tr>
			</table>
		</form>
	</body>
</html>
<script>
	var valorCampo = '';
	function validar(){
		if(valorCampo !=''){
			window.opener.busca_processo_financeiro(valorCampo);	
		}
		return false;
	}
	function busca_processo_financeiro_lista(){
		var MesReferencia	= document.formulario2.MesReferencia.value;
		var MenorVencimento	= document.formulario2.MenorVencimento.value;
		var IdStatus		= document.formulario2.IdStatus.value;
		var Limit	  		= <?=getCodigoInterno(7,4)?>;
		var nameNode, nameTextNode, url;
		
		var xmlhttp   = false;
		if (window.XMLHttpRequest) { // Mozilla, Safari,...
	    	xmlhttp = new XMLHttpRequest();
	        if(xmlhttp.overrideMimeType){
	        	xmlhttp.overrideMimeType('text/xml');
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
	    
	    if(MesReferencia == '' && MenorVencimento=='' && IdStatus==''){
	    	url = "xml/processo_financeiro.php?Limit="+Limit;
		}else{
			url = "xml/processo_financeiro.php?MesReferencia="+MesReferencia+"&MenorVencimento="+MenorVencimento+"&IdStatus="+IdStatus;
		}
		xmlhttp.open("GET", url,true);
		
		xmlhttp.onreadystatechange = function(){ 

			// Carregando...
			carregando(true);

			if(xmlhttp.readyState == 4){ 
				if(xmlhttp.status == 200){
					var dados = "<table id='listaDados' style='width: 343px'>\n<tr>\n<td class='listaDados_titulo' style='width: 60px'>Processo</td>\n<td class='listaDados_titulo'>Mês Ref.</td>\n<td class='listaDados_titulo'>Menor Venc.</td>\n<td class='listaDados_titulo'>Local Cobrança</td>\n</tr>";
					for(var i=0; i<xmlhttp.responseXML.getElementsByTagName("IdProcessoFinanceiro").length; i++){
							
						nameNode = xmlhttp.responseXML.getElementsByTagName("IdProcessoFinanceiro")[i]; 
						nameTextNode = nameNode.childNodes[0];
						IdProcessoFinanceiro = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("MesReferencia")[i]; 
						nameTextNode = nameNode.childNodes[0];
						MesReferencia = verifica_dado(nameTextNode.nodeValue);
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("MenorVencimento")[i]; 
						nameTextNode = nameNode.childNodes[0];
						MenorVencimento = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("DescFiltro_IdLocalCobranca")[i]; 
						nameTextNode = nameNode.childNodes[0];
						DescFiltro_IdLocalCobranca = nameTextNode.nodeValue;
						
						DescFiltro_IdLocalCobranca = DescFiltro_IdLocalCobranca.substr(0,20);
						
						dados += "\n<tr id='listaDados_td_"+IdProcessoFinanceiro+"' onClick='aciona(this,"+IdProcessoFinanceiro+")'>";
						dados += 	"\n<td>"+IdProcessoFinanceiro+"</td><td>"+MesReferencia+"</td><td>"+MenorVencimento+"</td><td>"+DescFiltro_IdLocalCobranca+"</td>";
						dados += "\n</tr>";
					}
					dados += "\n</table>";
					document.getElementById('listaDadosQuadro').innerHTML = dados;
				}
			} 
			// Fim de Carregando
			carregando(false);
		}
		xmlhttp.send(null);
	}
	function aciona(campo,valor){
		if(valorCampo!=''){
			document.getElementById('listaDados_td_'+valorCampo).style.backgroundColor = "#FFFFFF";
		}
		if(valorCampo == valor){
			window.opener.busca_processo_financeiro(valor,true);
		}
		valorCampo = valor;
		document.formulario.IdProcessoFinanceiro.value = valorCampo;
		campo.style.backgroundColor = '<?=getParametroSistema(15,4)?>';
	}
	function inicia(){
		document.formulario2.MesReferencia.focus();
	}
	inicia();
	busca_processo_financeiro_lista();
</script>
