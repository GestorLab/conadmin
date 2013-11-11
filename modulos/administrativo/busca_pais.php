<?
	include ('../../files/conecta.php');
	include ('../../rotinas/verifica.php');
	include ('../../files/funcoes.php');
	 
	$Cobranca	=	$_GET['Cobranca'];

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
		<div id='tit'>Busca País</div>
		<div id='filtro_busca'>
			<form name='BuscaPais' method='post'>
				<table>
					<tr>
						<td class='descCampo'>Nome País</td>
					</tr>
					<tr>
						<td class='campo'>
							<input type='text' name='NomePais' autocomplete="off" value='' style='width:330px' maxlength='100' onkeyup="busca_pais_lista()" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='1'>
						</td>
					</tr>
				</table>
			</form>
		</div>
		<div id='carregando'>carregando...</div>
		<div class='listaDados' style='height: 180px;'>
			<div id='listaDadosQuadro'>&nbsp;</div>
		</div>
		<form name='formulario' method='post' action='busca_pais.php' onSubmit='return validar()'>
			<input type='hidden' name='IdPais' value=''>
			<input type='hidden' name='Cobranca' value='<?=$Cobranca?>'>
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
			if(document.formulario.Cobranca.value == 'cob_pais'){
				window.opener.busca_cob_pais(valorCampo);
			}else{
				window.opener.busca_pais(valorCampo);	
			}
		}
		return false;
	}
	function busca_pais_lista(){
		var NomePais	= document.formulario2.NomePais.value;
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
	    
	    if(NomePais == ''){
	    	url = "xml/pais.php?Limit="+Limit;
		}else{
			url = "xml/pais.php?NomePais="+NomePais;
		}
		xmlhttp.open("GET", url,true);
		
		xmlhttp.onreadystatechange = function(){ 

			// Carregando...
			carregando(true);

			if(xmlhttp.readyState == 4){ 
				if(xmlhttp.status == 200){
					var dados = "<table id='listaDados' style='width: 343px'>\n<tr>\n<td class='listaDados_titulo' style='width: 60px'>País</td>\n<td class='listaDados_titulo'>Nome País</td>\n</tr>";
					for(var i=0; i<xmlhttp.responseXML.getElementsByTagName("IdPais").length; i++){
							
						nameNode = xmlhttp.responseXML.getElementsByTagName("IdPais")[i]; 
						nameTextNode = nameNode.childNodes[0];
						IdPais = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("NomePais")[i]; 
						nameTextNode = nameNode.childNodes[0];
						NomePais = verifica_dado(nameTextNode.nodeValue);
						
						dados += "\n<tr id='listaDados_td_"+IdPais+"' onClick='aciona(this,"+IdPais+")'>";
						dados += 	"\n<td>"+IdPais+"</td><td>"+NomePais+"</td>";
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
		if(document.formulario.Cobranca.value == 'cob_pais'){
			if(valorCampo == valor){
				window.opener.busca_cob_pais(valor,true,'PessoaBusca');
			}
		}else{
			if(valorCampo == valor){
				window.opener.busca_pais(valor,true,'PessoaBusca');
			}
		}
		valorCampo = valor;
		document.formulario.IdPais.value = valorCampo;
		campo.style.backgroundColor = '<?=getParametroSistema(15,4)?>';
	}
	function inicia(){
		document.formulario2.NomePais.focus();
	}
	inicia();
	busca_pais_lista();
</script>
