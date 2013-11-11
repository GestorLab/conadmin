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
		<div id='tit'>Busca Tipo Vigência Produto</div>
		<div id='filtro_busca'>
			<form name='formulario2'method='post'>
				<table>
					<tr>
						<td class='descCampo'>Nome Tipo Vigência Produto</td>
					</tr>
					<tr>
						<td class='campo'>
							<input type='text' name='DescricaoProdutoTipoVigencia' value='' autocomplete="off" style='width:330px' maxlength='100' onkeyup="busca_produto_tipo_vigencia_lista()" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='2'>
						</td>
					</tr>
				</table>
			</form>
		</div>
		<div id='carregando'>carregando...</div>
		<div class='listaDados' style='height: 180px;'>
			<div id='listaDadosQuadro'>&nbsp;</div>
		</div>
		<form name='formulario' method='post' action='busca_produto_tipo_vigencia.php' onSubmit='return validar()'>
			<input type='hidden' name='IdProdutoTipoVigencia' value=''>
			<table>
				<tr>
					<td>
						<input type='submit' value='Ok' class='botao'>
						<input type='button' value='Cancelar' onClick='window.close()' class='botao'>
					</td>
				</tr>
			</table>
		</form>
		</div>		
	</body>
</html>
<script>
	var valorCampo = '';
	function validar(){
		if(valorCampo !=''){
			window.opener.busca_produto_tipo_vigencia(valorCampo);
		}
		return false;
	}
	function busca_produto_tipo_vigencia_lista(){
		var DescricaoProdutoTipoVigencia = document.formulario2.DescricaoProdutoTipoVigencia.value;
		var Limit						  = <?=getCodigoInterno(7,4)?>;
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
	    
	    if(DescricaoProdutoTipoVigencia == ''){
	    	url = "xml/produto_tipo_vigencia.php?Limit="+Limit;
		}else{
			url = "xml/produto_tipo_vigencia.php?DescricaoProdutoTipoVigencia="+DescricaoProdutoTipoVigencia;
		}
		xmlhttp.open("GET", url,true);
		
		xmlhttp.onreadystatechange = function(){ 

			// Carregando...
			carregando(true);

			if(xmlhttp.readyState == 4){ 
				if(xmlhttp.status == 200){
					var dados = "<table id='listaDados' style='width: 343px'>\n<tr>\n<td class='listaDados_titulo' style='width: 60px'>Tipo Vig.</td>\n<td class='listaDados_titulo'>Nome Tipo Vigência Produto</td>\n</tr>";
					for(var i=0; i<xmlhttp.responseXML.getElementsByTagName("IdProdutoTipoVigencia").length; i++){
							
						nameNode = xmlhttp.responseXML.getElementsByTagName("IdProdutoTipoVigencia")[i]; 
						nameTextNode = nameNode.childNodes[0];
						IdProdutoTipoVigencia = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoProdutoTipoVigencia")[i]; 
						nameTextNode = nameNode.childNodes[0];
						DescricaoProdutoTipoVigencia = verifica_dado(nameTextNode.nodeValue);
						
						DescricaoProdutoTipoVigencia = DescricaoProdutoTipoVigencia.substr(0,40);
						
						dados += "\n<tr id='listaDados_td_"+IdProdutoTipoVigencia+"' onClick='aciona(this,"+IdProdutoTipoVigencia+")'>";
						dados += 	"\n<td>"+IdProdutoTipoVigencia+"</td>\n<td>"+DescricaoProdutoTipoVigencia+"</td>";
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
			window.opener.busca_produto_tipo_vigencia(valor,false,window.opener.document.formulario.Local.value);
		}
		valorCampo = valor;
		document.formulario.IdProdutoTipoVigencia.value = valorCampo;
		campo.style.backgroundColor = '<?=getParametroSistema(15,4)?>';
	}
	function inicia(){
		document.formulario2.DescricaoProdutoTipoVigencia.focus();
	}
	inicia();
	busca_produto_tipo_vigencia_lista();
</script>
