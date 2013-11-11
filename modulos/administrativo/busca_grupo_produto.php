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
		<div id='tit'>Busca Grupo Produto</div>
		<div id='filtro_busca'>
			<form name='formulario2' method='post'>
				<table>
					<tr>
						<td class='descCampo'>Nome Grupo Produto</td>
					</tr>
					<tr>
						<td class='campo'>
							<input type='text' name='DescricaoGrupoProduto' autocomplete="off" value='' style='width:330px' maxlength='100' onkeyup="busca_grupo_produto_lista()" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='1'>
						</td>
					</tr>
				</table>
			</form>
		</div>
		<div id='carregando'>carregando...</div>
		<div class='listaDados' style='height: 180px;'>
			<div id='listaDadosQuadro'>&nbsp;</div>
		</div>
		<form name='formulario' method='post' action='busca_grupo_produto.php' onSubmit='return validar()'>
			<input type='hidden' name='IdGrupoProduto' value=''>
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
			window.opener.busca_grupo_produto(valorCampo);	
		}
		return false;
	}
	function busca_grupo_produto_lista(){
		var DescricaoGrupoProduto	= document.formulario2.DescricaoGrupoProduto.value;
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
	    
	    if(DescricaoGrupoProduto == ''){
	    	url = "xml/grupo_produto.php?Limit="+Limit;
		}else{
			url = "xml/grupo_produto.php?DescricaoGrupoProduto="+DescricaoGrupoProduto;
		}
		xmlhttp.open("GET", url,true);
		
		xmlhttp.onreadystatechange = function(){ 

			// Carregando...
			carregando(true);

			if(xmlhttp.readyState == 4){ 
				if(xmlhttp.status == 200){
					var dados = "<table id='listaDados' style='width: 343px'>\n<tr>\n<td class='listaDados_titulo' style='width: 70px'>Grupo Prod.</td>\n<td class='listaDados_titulo'>Nome Grupo Produto</td>\n</tr>";
					for(var i=0; i<xmlhttp.responseXML.getElementsByTagName("IdGrupoProduto").length; i++){
							
						nameNode = xmlhttp.responseXML.getElementsByTagName("IdGrupoProduto")[i]; 
						nameTextNode = nameNode.childNodes[0];
						IdGrupoProduto = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoGrupoProduto")[i]; 
						nameTextNode = nameNode.childNodes[0];
						DescricaoGrupoProduto = verifica_dado(nameTextNode.nodeValue);
						
						DescricaoGrupoProduto = DescricaoGrupoProduto.substr(0,30);
						
						dados += "\n<tr id='listaDados_td_"+IdGrupoProduto+"' onClick='aciona(this,"+IdGrupoProduto+")'>";
						dados += 	"\n<td>"+IdGrupoProduto+"</td><td>"+DescricaoGrupoProduto+"</td>";
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
			window.opener.busca_grupo_produto(valor,true);
		}
		valorCampo = valor;
		document.formulario.IdGrupoProduto.value = valorCampo;
		campo.style.backgroundColor = '<?=getParametroSistema(15,4)?>';
	}
	function inicia(){
		document.formulario2.DescricaoGrupoProduto.focus();
	}
	inicia();
	busca_grupo_produto_lista();
</script>
