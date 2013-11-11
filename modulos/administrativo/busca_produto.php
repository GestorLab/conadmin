<?
	include ('../../files/conecta.php');
	include ('../../rotinas/verifica.php');
	include ('../../files/funcoes.php');
	
	$local_pos	=	$_GET['pos'];
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
		<div id='tit'>Busca Produto</div>
		<div id='filtro_busca'>
			<form name='formulario2' method='post'>
				<table>
					<tr>
						<td class='descCampo'>Nome Produto</td>
						<td class='descCampo'>Nome Fabricante</td>
					</tr>
					<tr>
						<td class='campo'>
							<input type='text' name='DescricaoProduto' autocomplete="off" value='' style='width:300px' maxlength='100' onkeyup="busca_produto_lista()" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')">
						</td>
						<td class='campo'>
							<input type='text' name='DescricaoFabricante' autocomplete="off" value='' style='width:190px' maxlength='100' onkeyup="busca_produto_lista()" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')">
						</td>
					</tr>
				</table>
				<table>
					<tr>
						<td class='descCampo'>Nome Grupo Produto</td>
						<td class='descCampo'>Nome SubGrupo Produto</td>
					</tr>
					<tr>
						<td class='campo'>
							<input type='text' name='DescricaoGrupoProduto' autocomplete="off" value='' style='width:245px' maxlength='100' onkeyup="busca_produto_lista()" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')">
						</td>
						<td class='campo'>
							<input type='text' name='DescricaoSubGrupoProduto' autocomplete="off" value='' style='width:245px' maxlength='100' onkeyup="busca_produto_lista()" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')">
						</td>
					</tr>
				</table>
			</form>
		</div>
		<div id='carregando'>carregando...</div>
		<div class='listaDados' style='height: 200px;'>
			<div id='listaDadosQuadro'>&nbsp;</div>
		</div>
		<form name='formulario' method='post' action='busca_produto.php' onSubmit='return validar()'>
			<input type='hidden' name='IdProduto' value=''>
			<input type='hidden' name='pos' value='<?=$local_pos?>'>
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
			window.opener.busca_produto(valorCampo,'false',window.opener.document.formulario.Local.value,document.formulario.pos.value);
		}
		return false;
	}
	function busca_produto_lista(){
		var DescricaoProduto 			= document.formulario2.DescricaoProduto.value;
		var DescricaoFabricante			= document.formulario2.DescricaoFabricante.value;
		var DescricaoGrupoProduto		= document.formulario2.DescricaoGrupoProduto.value;
		var DescricaoSubGrupoProduto	= document.formulario2.DescricaoSubGrupoProduto.value;
		var Local						= window.opener.document.formulario.Local.value;
		var Limit	  					= <?=getCodigoInterno(7,4)?>;
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
	    
	    if(DescricaoProduto == '' && DescricaoFabricante == '' && DescricaoGrupoProduto == '' && DescricaoSubGrupoProduto == ''){
	    	url = "../administrativo/xml/produto_busca.php?Limit="+Limit;
		}else{
			url = "../administrativo/xml/produto_busca.php?DescricaoProduto="+DescricaoProduto+"&DescricaoFabricante="+DescricaoFabricante+"&DescricaoGrupoProduto="+DescricaoGrupoProduto+"&DescricaoSubGrupoProduto="+DescricaoSubGrupoProduto;
		}
		
		xmlhttp.open("GET", url,true);
		
		xmlhttp.onreadystatechange = function(){ 

			// Carregando...
			carregando(true);

			if(xmlhttp.readyState == 4){ 
				if(xmlhttp.status == 200){
					var dados = "<table id='listaDados' style='width: 511px'>\n<tr>\n<td class='listaDados_titulo' style='width: 60px'>Produto</td>\n<td class='listaDados_titulo'>Nome Produto</td>\n<td class='listaDados_titulo'>Nome Fabricante</td>\n</tr>";
					for(var i=0; i<xmlhttp.responseXML.getElementsByTagName("IdProduto").length; i++){
							
						nameNode = xmlhttp.responseXML.getElementsByTagName("IdProduto")[i]; 
						nameTextNode = nameNode.childNodes[0];
						IdProduto = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoProduto")[i]; 
						nameTextNode = nameNode.childNodes[0];
						DescricaoProduto = verifica_dado(nameTextNode.nodeValue);
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoFabricante")[i]; 
						nameTextNode = nameNode.childNodes[0];
						DescricaoFabricante = verifica_dado(nameTextNode.nodeValue);
						
						DescricaoProduto 		= DescricaoProduto.substring(0,30);
						DescricaoFabricante 	= DescricaoFabricante.substring(0,30);
						
						dados += "\n<tr id='listaDados_td_"+IdProduto+"' onClick='aciona(this,"+IdProduto+")'>";
						dados += 	"\n<td>"+IdProduto+"</td><td>"+DescricaoProduto+"</td><td>"+DescricaoFabricante+"</td>";
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
			window.opener.busca_produto(valor,false,window.opener.document.formulario.Local.value,document.formulario.pos.value);
		}
		valorCampo = valor;
		document.formulario.IdProduto.value = valorCampo;
		campo.style.backgroundColor = '<?=getParametroSistema(15,4)?>';
	}
	function inicia(){
		document.formulario2.DescricaoProduto.focus();
	}
	inicia();
	busca_produto_lista();
</script>
