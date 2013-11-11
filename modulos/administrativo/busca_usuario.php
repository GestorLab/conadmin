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
		<script type = 'text/javascript' src = '../../js/event.js'></script>
	</head>
	<body>
		<div id='tit'>Busca Usuário</div>
		<div id='filtro_busca'>
			<form name='formulario2' method='post'>
				<table>
					<tr>
						<td class='descCampo'>Login</td>
						<td class='descCampo'>Nome Usuário</td>
					</tr>
					<tr>
						<td class='campo'>
							<input type='text' name='Login' autocomplete="off" value='' style='width:120px' maxlength='20' onkeyup="busca_usuario_lista()" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')">
						</td>
						<td class='campo'>
							<input type='text' name='NomeUsuario' autocomplete="off" value='' style='width:202px' maxlength='30' onkeyup="busca_usuario_lista()" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')">
						</td>
					</tr>
				</table>
			</form>
		</div>
		<div id='carregando'>carregando...</div>
		<div class='listaDados' style='height: 180px;'>
			<div id='listaDadosQuadro'>&nbsp;</div>
		</div>
		<form name='formulario' method='post' action='busca_usuario.php' onSubmit='return validar()'>
			<input type='hidden' name='Login' value=''>
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
			window.opener.busca_usuario(valorCampo);	
		}
		return false;
	}
	function busca_usuario_lista(){
		var Login		= document.formulario2.Login.value;
		var NomeUsuario	= document.formulario2.NomeUsuario.value;
		var Limit	  	= <?=getCodigoInterno(7,4)?>;
		var nameNode, nameTextNode, url;
		
		var Busca = 'Busca';
		
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
	    
	    if(Login == '' && NomeUsuario=='') {
	    	url = "xml/usuario.php?Limit="+Limit+"&Busca="+Busca;
		}else{
			url = "xml/usuario.php?Login="+Login+"&NomeUsuario="+NomeUsuario+"&Busca="+Busca;
		}
		xmlhttp.open("GET", url,true);
		
		xmlhttp.onreadystatechange = function(){ 

			// Carregando...
			carregando(true);

			if(xmlhttp.readyState == 4){ 
				if(xmlhttp.status == 200){
					var dados = "<table id='listaDados' style='width: 343px'>\n<tr>\n<td class='listaDados_titulo' style='width: 100px'>Login</td>\n<td class='listaDados_titulo'>Nome Usuário</td>\n</tr>";
					for(var i=0; i<xmlhttp.responseXML.getElementsByTagName("Login").length; i++){
							
						nameNode = xmlhttp.responseXML.getElementsByTagName("Login")[i]; 
						nameTextNode = nameNode.childNodes[0];
						Login = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("NomeUsuario")[i]; 
						nameTextNode = nameNode.childNodes[0];
						NomeUsuario = verifica_dado(nameTextNode.nodeValue);
						
						dados += "\n<tr id='listaDados_td_"+Login+"' onClick=\"aciona(this,'"+Login+"')\">";
						dados += 	"\n<td>"+Login+"</td><td>"+NomeUsuario+"</td>";
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
			window.opener.busca_usuario(valor,true);
		}
		valorCampo = valor;
		document.formulario.Login.value = valorCampo;
		campo.style.backgroundColor = '<?=getParametroSistema(15,4)?>';
	}
	function inicia(){
		document.formulario2.Login.focus();
	}
	inicia();
	busca_usuario_lista();
	enterAsTab(document.forms.formulario2);
</script>
