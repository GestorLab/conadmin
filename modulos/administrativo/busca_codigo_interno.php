<?
	include ('../../files/conecta.php');
	include ('../../rotinas/verifica.php');
	include ('../../files/funcoes.php');
	 
	$local_IdGrupoCodigoInterno	=	$_GET['IdGrupoCodigoInterno'];	
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
		<div id='tit'>Busca Parâmetro do Sistema</div>
		<div id='filtro_busca'>
			<form name='formulario2' method='post'>
				<table>
					<tr>
						<td class='descCampo'>Nome</td>
					</tr>
					<tr>
						<td class='campo'>
							<input type='text' name='DescricaoCodigoInterno' autocomplete="off" value='<?=$local_Nome?>' style='width:330px' maxlength='100' onkeyup="busca_codigo_interno_lista()" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='2'>
						</td>
					</tr>
				</table>
			</form>
		</div>
		<div id='carregando'>carregando...</div>
		<div class='listaDados' style='height: 180px;'>
			<div id='listaDadosQuadro'>&nbsp;</div>
		</div>
		<form name='formulario' method='post' action='busca_codigo_interno.php' onSubmit='return validar()'>
			<input type='hidden' name='IdGrupoCodigoInterno' value='<?=$local_IdGrupoCodigoInterno?>'>
			<input type='hidden' name='IdCodigoInterno' value=''>
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
			var IdGrupoCodigoInterno		= document.formulario.IdGrupoCodigoInterno.value;
			window.opener.busca_codigo_interno(IdGrupoCodigoInterno,valorCampo,true,window.opener.document.formulario.Local.value);
		}
		return false;
	}
	function busca_codigo_interno_lista(){
		var DescricaoCodigoInterno 	= document.formulario2.DescricaoCodigoInterno.value;
		var IdGrupoCodigoInterno	= document.formulario.IdGrupoCodigoInterno.value;
		var Limit	  				= <?=getCodigoInterno(7,4)?>;
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
	    
	    if(DescricaoCodigoInterno == ''){
	    	url = "xml/codigo_interno.php?IdGrupoCodigoInterno="+IdGrupoCodigoInterno+"&Limit="+Limit;
		}else{
			url = "xml/codigo_interno.php?IdGrupoCodigoInterno="+IdGrupoCodigoInterno+"&DescricaoCodigoInterno="+DescricaoCodigoInterno;
		}
		xmlhttp.open("GET", url,true);
		
		xmlhttp.onreadystatechange = function(){ 

			// Carregando...
			carregando(true);

			if(xmlhttp.readyState == 4){ 
				if(xmlhttp.status == 200){
					var dados = "<table id='listaDados' style='width: 343px'>\n<tr>\n<td class='listaDados_titulo' style='width: 80px'>Cód. Interno</td>\n<td class='listaDados_titulo'>Nome</td>\n</tr>";
					for(var i=0; i<xmlhttp.responseXML.getElementsByTagName("IdCodigoInterno").length; i++){
							
						nameNode = xmlhttp.responseXML.getElementsByTagName("IdCodigoInterno")[i]; 
						nameTextNode = nameNode.childNodes[0];
						IdCodigoInterno = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoCodigoInterno")[i]; 
						nameTextNode = nameNode.childNodes[0];
						DescricaoCodigoInterno = verifica_dado(nameTextNode.nodeValue);
						
						dados += "\n<tr id='listaDados_td_"+IdCodigoInterno+"' onClick='aciona(this,"+IdCodigoInterno+")'>";
						dados += 	"\n<td>"+IdCodigoInterno+"</td><td>"+DescricaoCodigoInterno+"</td>";
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
		var IdGrupoCodigoInterno	= document.formulario.IdGrupoCodigoInterno.value;
		if(valorCampo!=''){
			document.getElementById('listaDados_td_'+valorCampo).style.backgroundColor = "#FFFFFF";
		}
		if(valorCampo == valor){
			window.opener.busca_codigo_interno(IdGrupoCodigoInterno,valor,true,window.opener.document.formulario.Local.value);
		}
		valorCampo = valor;
		document.formulario.IdCodigoInterno.value = valorCampo;
		campo.style.backgroundColor = '<?=getParametroSistema(15,4)?>';
	}
	function inicia(){
		document.formulario2.DescricaoCodigoInterno.focus();
	}
	inicia();
	busca_codigo_interno_lista();
</script>
