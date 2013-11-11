<?
	include ('../../files/conecta.php');
	include ('../../rotinas/verifica.php');
	include ('../../files/funcoes.php');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
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
		<div id='tit'>Busca Local de Cobrança</div>
		<div id='filtro_busca'>
			<form name='formulario2' method='post'>
				<table>
					<tr>
						<td class='descCampo'>Nome Local de Cobrança</td>
						<td class='descCampo'>Abreviação</td>
					</tr>
					<tr>
						<td class='campo'>
							<input type='text' name='Nome' autocomplete="off" value='<?=$local_Nome?>' style='width:202px' maxlength='100' onkeyup="busca_local_cobranca_lista()" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')">
						</td>
						<td class='campo'>
							<input type='text' name='Abreviacao' autocomplete="off" value='<?=$local_Abreviacao?>' style='width:120px' maxlength='6' onkeyup="busca_local_cobranca_lista()" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')">
						</td>
					</tr>
				</table>
			</form>
		</div>
		<div id='carregando'>carregando...</div>
		<div class='listaDados' style='height: 180px;'>
			<div id='listaDadosQuadro'>&nbsp;</div>
		</div>
		<form name='formulario' method='post' action='busca_local_cobranca.php' onSubmit='return validar()'>
			<input type='hidden' name='IdLocalCobranca' value=''>
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
			window.opener.busca_local_cobranca(valorCampo);
		}
		return false;
	}
	function busca_local_cobranca_lista(){
		var Nome 		= document.formulario2.Nome.value;
		var Abreviacao	= document.formulario2.Abreviacao.value;
		var Limit	  	= <?=getCodigoInterno(7,4)?>;
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
	    
	    if(Nome == '' && Abreviacao == ''){
	    	url = "../administrativo/xml/local_cobranca.php?Limit="+Limit;
		}else{
			url = "../administrativo/xml/local_cobranca.php?Nome="+Nome+"&Abreviacao="+Abreviacao;
		}
		
		xmlhttp.open("GET", url,true);
		
		xmlhttp.onreadystatechange = function(){ 

			// Carregando...
			carregando(true);

			if(xmlhttp.readyState == 4){ 
				if(xmlhttp.status == 200){
					var dados = "<table id='listaDados' style='width: 343px'>\n<tr>\n<td class='listaDados_titulo' style='width: 70px'>Local Cob.</td>\n<td class='listaDados_titulo'>Nome Local Cobrança</td>\n<td class='listaDados_titulo'>Abreviação</td>\n</tr>";
					for(var i=0; i<xmlhttp.responseXML.getElementsByTagName("IdLocalCobranca").length; i++){
							
						nameNode = xmlhttp.responseXML.getElementsByTagName("IdLocalCobranca")[i]; 
						nameTextNode = nameNode.childNodes[0];
						var IdLocalCobranca = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoLocalCobranca")[i]; 
						nameTextNode = nameNode.childNodes[0];
						var DescricaoLocalCobranca = nameTextNode.nodeValue;
						
						DescricaoLocalCobranca = DescricaoLocalCobranca.substr(0,50);
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("AbreviacaoNomeLocalCobranca")[i]; 
						nameTextNode = nameNode.childNodes[0];
						var AbreviacaoNomeLocalCobranca = nameTextNode.nodeValue;
						
						dados += "\n<tr id='listaDados_td_"+IdLocalCobranca+"' onClick='aciona(this,"+IdLocalCobranca+")'>";
						dados += 	"\n<td>"+IdLocalCobranca+"</td><td>"+DescricaoLocalCobranca+"</td><td>"+AbreviacaoNomeLocalCobranca+"</td>";
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
			window.opener.busca_local_cobranca(valor);
		}
		valorCampo = valor;
		document.formulario.IdLocalCobranca.value = valorCampo;
		campo.style.backgroundColor = '<?=getParametroSistema(15,4)?>';
	}
	function inicia(){
		document.formulario2.Nome.focus();
	}
	inicia();
	busca_local_cobranca_lista();
</script>
