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
		<div id='tit'>Busca Datas Especiais</div>
		<div id='filtro_busca'>
			<form name='formulario2' method='post'>
				<table>
					<tr>
						<td class='descCampo'>Data</td>
						<td class='descCampo'>Descri��o Data</td>
					</tr>
					<tr>
						<td class='campo'>
							<input type='text' name='Data' autocomplete="off" value='<?=$local_Data?>' style='width:95px' maxlength='10' onKeyPress="mascara(this,event,'date')" onkeyup="busca_datas_especiais_lista()" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='1'>
						</td>
						<td class='campo'>
							<input type='text' name='Nome' autocomplete="off" value='<?=$local_DescricaoData?>' style='width:227px' maxlength='100' onkeyup="busca_datas_especiais_lista()" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='2'>
						</td>
					</tr>
				</table>
			</form>
		</div>
		<div id='carregando'>carregando...</div>
		<div class='listaDados' style='height: 180px;'>
			<div id='listaDadosQuadro'>&nbsp;</div>
		</div>
		<form name='formulario' method='post' action='busca_datas_especiais.php' onSubmit='return validar()'>
			<input type='hidden' name='Data' value=''>
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
			window.opener.busca_datas_especiais(dateFormat(valorCampo));
		}
		return false;
	}
	function busca_datas_especiais_lista(){
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
	    	url = "../administrativo/xml/datas_especiais.php?Limit="+Limit;
		}else{
			url = "../administrativo/xml/datas_especiais.php?DescricaoData="+Nome;
		}
		xmlhttp.open("GET", url,true);
		
		xmlhttp.onreadystatechange = function(){ 

			// Carregando...
			carregando(true);

			if(xmlhttp.readyState == 4){ 
				if(xmlhttp.status == 200){
					var dados = "<table id='listaDados' style='width: 343px'>\n<tr>\n<td class='listaDados_titulo' style='width: 60px'>Data</td>\n<td class='listaDados_titulo'>Descri��o Data</td>\n</tr>";
					//alert(xmlhttp.responseText);
					for(var i=0; i<xmlhttp.responseXML.getElementsByTagName("Data").length; i++){
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("Data")[i]; 
						nameTextNode = nameNode.childNodes[0];
						Data = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoData")[i]; 
						nameTextNode = nameNode.childNodes[0];
						DescricaoData = verifica_dado(nameTextNode.nodeValue);
						
						dados += "\n<tr id='listaDados_td_"+Data+"' onClick=\"aciona(this,'"+Data+"')\">";
						dados += 	"\n<td>"+dateFormat(Data)+"</td><td>"+DescricaoData+"</td>";
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
			window.opener.busca_datas_especiais(dateFormat(valor));
		}
		valorCampo = valor;

		document.formulario.Data.value = dateFormat(valorCampo);
		
		campo.style.backgroundColor = '<?=getParametroSistema(15,4)?>';
	}
	function inicia(){
		document.formulario2.Data.focus();
	}
	inicia();
	busca_datas_especiais_lista();
</script>
