<?
	include ('../../files/conecta.php');
	include ('../../rotinas/verifica.php');
	include ('../../files/funcoes.php');
	 
	$local_IdLoja	=	$_SESSION["IdLoja"];	
	$local_SubLocal	=	$_GET["SubLocal"];	
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
		<div id='tit'>Busca Centro de Custo</div>
		<div id='filtro_busca'>
			<form name='formulario2' method='post'>
				<table>
					<tr>
						<td class='descCampo'>Nome Centro de Custo</td>
					</tr>
					<tr>
						<td class='campo'>
							<input type='text' name='DescricaoCentroCusto' autocomplete="off" value='<?=$local_Nome?>' style='width:330px' maxlength='100' onkeyup="busca_centro_custo_lista()" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='2'>
						</td>
					</tr>
				</table>
			</form>
		</div>
		<div id='carregando'>carregando...</div>
		<div class='listaDados' style='height: 180px;'>
			<div id='listaDadosQuadro'>&nbsp;</div>
		</div>
		<form name='formulario' method='post' action='busca_centro_custo.php' onSubmit='return validar()'>
			<input type='hidden' name='IdCentroCusto' value=''>
			<input type='hidden' name='SubLocal' value='<?=$local_SubLocal?>'>
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
			if(document.formulario.SubLocal.value == 'Conta'){
				window.opener.busca_conta_pagar_centro_custo(window.opener.document.formulario.IdContaPagar.value,true,window.opener.document.formulario.Local.value,valorCampo);
			}else if(document.formulario.SubLocal.value == 'Mantenedor'){
				window.opener.busca_centro_custo_rateio(window.opener.document.formulario.IdCentroCusto.value,true,window.opener.document.formulario.Local.value,valorCampo);
			}else{
				window.opener.busca_centro_custo(valorCampo,true,window.opener.document.formulario.Local.value,document.formulario.SubLocal.value);
			}
		}
		return false;
	}
	function busca_centro_custo_lista(){
		var DescricaoCentroCusto 	= document.formulario2.DescricaoCentroCusto.value;
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
	    
	    if(DescricaoCentroCusto == ''){
	    	url = "xml/centro_custo.php?Limit="+Limit;
		}else{
			url = "xml/centro_custo.php?DescricaoCentroCusto="+DescricaoCentroCusto;
		}
		xmlhttp.open("GET", url,true);
		
		xmlhttp.onreadystatechange = function(){ 

			// Carregando...
			carregando(true);

			if(xmlhttp.readyState == 4){ 
				if(xmlhttp.status == 200){
					var dados = "<table id='listaDados' style='width: 343px'>\n<tr>\n<td class='listaDados_titulo' style='width: 80px'>Cent. de Cust.</td>\n<td class='listaDados_titulo'>Nome Centro de Custo</td>\n</tr>";
					for(var i=0; i<xmlhttp.responseXML.getElementsByTagName("IdCentroCusto").length; i++){
							
						nameNode = xmlhttp.responseXML.getElementsByTagName("IdCentroCusto")[i]; 
						nameTextNode = nameNode.childNodes[0];
						IdCentroCusto = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoCentroCusto")[i]; 
						nameTextNode = nameNode.childNodes[0];
						DescricaoCentroCusto = verifica_dado(nameTextNode.nodeValue);
						
						dados += "\n<tr id='listaDados_td_"+IdCentroCusto+"' onClick='aciona(this,"+IdCentroCusto+")'>";
						dados += 	"\n<td>"+IdCentroCusto+"</td><td>"+DescricaoCentroCusto+"</td>";
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
			if(document.formulario.SubLocal.value == 'Conta'){
				window.opener.busca_conta_pagar_centro_custo(window.opener.document.formulario.IdContaPagar.value,true,window.opener.document.formulario.Local.value,valor);
			}if(document.formulario.SubLocal.value == 'Mantenedor'){
				window.opener.busca_centro_custo_rateio(window.opener.document.formulario.IdCentroCusto.value,true,window.opener.document.formulario.Local.value,valor);
			}else{
				window.opener.busca_centro_custo(valor,true,window.opener.document.formulario.Local.value,document.formulario.SubLocal.value);
			}
		}
		valorCampo = valor;
		document.formulario.IdCentroCusto.value = valorCampo;
		campo.style.backgroundColor = '<?=getParametroSistema(15,4)?>';
	}
	function inicia(){
		document.formulario2.DescricaoCentroCusto.focus();
	}
	inicia();
	busca_centro_custo_lista();
</script>
