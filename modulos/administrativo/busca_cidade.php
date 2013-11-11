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
		<div id='tit'>Busca Cidade</div>
		<div id='filtro_busca'>
			<form name='formulario2' method='post'>
				<table>
					<tr>
						<td class='descCampo'>Nome Cidade</td>
					</tr>
					<tr>
						<td class='campo'>
							<input type='text' name='NomeCidade' value='<?=$local_NomeCidade?>' style='width:330px' maxlength='100' onkeyup="busca_cidade_lista()" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='2'>
						</td>
					</tr>
				</table>
			</form>
		</div>
		<div id='carregando'>carregando...</div>
		<div class='listaDados' style='height: 180px;'>
			<div id='listaDadosQuadro'>&nbsp;</div>
		</div>
		<form name='formulario' method='post' action='busca_cidade.php' onSubmit='return validar()'>
			<input type='hidden' name='IdCidade' value=''>
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
			if(document.formulario.Cobranca.value == 'cob_cidade'){
				var IdPais 	  	= window.opener.document.formulario.Cob_IdPais.value;
				var IdEstado   	= window.opener.document.formulario.Cob_IdEstado.value;
				window.opener.busca_cob_cidade(IdPais,IdEstado,valorCampo);
			}else{
				var IdPais 	  	= window.opener.document.formulario.IdPais.value;
				var IdEstado   	= window.opener.document.formulario.IdEstado.value;
				window.opener.busca_cidade(IdPais,IdEstado,valorCampo);
			}
		}
		return false;
	}
	function busca_cidade_lista(){
		if(document.formulario.Cobranca.value == 'cob_cidade'){
			var IdPais 	  	= window.opener.document.formulario.Cob_IdPais.value;
			var IdEstado   	= window.opener.document.formulario.Cob_IdEstado.value;
		}else{
			var IdPais 	  	= window.opener.document.formulario.IdPais.value;
			var IdEstado   	= window.opener.document.formulario.IdEstado.value;
		}
		var NomeCidade	= document.formulario2.NomeCidade.value;
		var Limit	  	= <?=getCodigoInterno(7,4)?>;
		var nameNode, nameTextNode, url;
		
		if(IdPais == '' || IdEstado == ''){
			window.close();
		}
		
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
	    
	    if(NomeCidade == ''){
	    	url = "xml/cidade.php?IdPais="+IdPais+"&IdEstado="+IdEstado+"&Limit="+Limit;
		}else{
			url = "xml/cidade.php?IdPais="+IdPais+"&IdEstado="+IdEstado+"&NomeCidade="+NomeCidade;
		}
		xmlhttp.open("GET", url,true);
		
		xmlhttp.onreadystatechange = function(){ 

			// Carregando...
			carregando(true);

			if(xmlhttp.readyState == 4){ 
				if(xmlhttp.status == 200){
					var dados = "<table id='listaDados' style='width: 343px'>\n<tr>\n<td class='listaDados_titulo' style='width: 60px'>Cidade</td>\n<td class='listaDados_titulo'>Nome Cidade</td>\n</tr>";
					for(var i=0; i<xmlhttp.responseXML.getElementsByTagName("IdPais").length; i++){
							
						nameNode = xmlhttp.responseXML.getElementsByTagName("IdCidade")[i]; 
						nameTextNode = nameNode.childNodes[0];
						IdCidade = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("NomeCidade")[i]; 
						nameTextNode = nameNode.childNodes[0];
						NomeCidade = verifica_dado(nameTextNode.nodeValue);
						
						dados += "\n<tr id='listaDados_td_"+IdCidade+"' onClick='aciona(this,"+IdCidade+")'>";
						dados += 	"\n<td>"+IdCidade+"</td><td>"+NomeCidade+"</td>";
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
		if(document.formulario.Cobranca.value == 'cob_cidade'){
			var IdPais 	  	= window.opener.document.formulario.Cob_IdPais.value;
			var IdEstado   	= window.opener.document.formulario.Cob_IdEstado.value;
		}else{
			var IdPais 	  	= window.opener.document.formulario.IdPais.value;
			var IdEstado   	= window.opener.document.formulario.IdEstado.value;
		}
		var Local	   	= window.opener.document.formulario.Local.value;
		if(valorCampo!=''){
			document.getElementById('listaDados_td_'+valorCampo).style.backgroundColor = "#FFFFFF";
		}
		if(Local != 'Cidade'){
			Local = 'PessoaBusca';
		}
		if(valorCampo == valor){
			if(document.formulario.Cobranca.value == 'cob_cidade'){
				window.opener.busca_cob_cidade(IdPais,IdEstado,valor,true,Local);
			}else{
				window.opener.busca_cidade(IdPais,IdEstado,valor,true,Local);
			}
		}
		valorCampo = valor;
		document.formulario.IdCidade.value = valorCampo;
		campo.style.backgroundColor = '<?=getParametroSistema(15,4)?>';
	}
	function inicia(){
		document.formulario2.NomeCidade.focus();
	}
	inicia();
	busca_cidade_lista();
</script>