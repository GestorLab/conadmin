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
		<div id='tit'>Busca Grupo Parāmetros do Sistema</div>
		<div id='filtro_busca'>
			<form name='formulario2'method='post'>
				<table>
					<tr>
						<td class='descCampo'>Nome Grupo Parāmetros do Sistema</td>
					</tr>
					<tr>
						<td class='campo'>
							<input type='text' name='Nome' autocomplete="off" value='<?=$local_Nome?>' style='width:330px' maxlength='100' onkeyup="busca_grupo_parametro_sistema_lista()" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='2'>
						</td>
					</tr>
				</table>
			</form>
		</div>
		<div id='carregando'>carregando...</div>
		<div class='listaDados' style='height: 180px;'>
			<div id='listaDadosQuadro'>&nbsp;</div>
		</div>
		<form name='formulario' method='post' action='busca_grupo_parametro_sistema.php' onSubmit='return validar()'>
			<input type='hidden' name='IdGrupoParametroSistema' value=''>
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
			window.opener.busca_grupo_parametro_sistema(valorCampo);
		}
		return false;
	}
	function busca_grupo_parametro_sistema_lista(){
		var Nome 		= document.formulario2.Nome.value;
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
	    
	    if(Nome == ''){
	    	url = "xml/grupo_parametro_sistema.php?Limit="+Limit;
		}else{
			url = "xml/grupo_parametro_sistema.php?Nome="+Nome;
		}
		xmlhttp.open("GET", url,true);
		
		xmlhttp.onreadystatechange = function(){ 

			// Carregando...
			carregando(true);

			if(xmlhttp.readyState == 4){ 
				if(xmlhttp.status == 200){
					var dados = "<table id='listaDados' style='width: 343px'>\n<tr>\n<td class='listaDados_titulo' style='width: 90px'>Grup. Par. Sist.</td>\n<td class='listaDados_titulo'>Nome</td>\n</tr>";
					for(var i=0; i<xmlhttp.responseXML.getElementsByTagName("IdGrupoParametroSistema").length; i++){
							
						nameNode = xmlhttp.responseXML.getElementsByTagName("IdGrupoParametroSistema")[i]; 
						nameTextNode = nameNode.childNodes[0];
						IdGrupoParametroSistema = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoGrupoParametroSistema")[i]; 
						nameTextNode = nameNode.childNodes[0];
						DescricaoGrupoParametroSistema = verifica_dado(nameTextNode.nodeValue);
						
						dados += "\n<tr id='listaDados_td_"+IdGrupoParametroSistema+"' onClick='aciona(this,"+IdGrupoParametroSistema+")'>";
						dados += 	"\n<td>"+IdGrupoParametroSistema+"</td><td>"+DescricaoGrupoParametroSistema+"</td>";
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
			window.opener.busca_grupo_parametro_sistema(valor);
		}
		valorCampo = valor;
		document.formulario.IdGrupoParametroSistema.value = valorCampo;
		campo.style.backgroundColor = '<?=getParametroSistema(15,4)?>';
	}
	function inicia(){
		document.formulario2.Nome.focus();
	}
	inicia();
	busca_grupo_parametro_sistema_lista();
</script>
