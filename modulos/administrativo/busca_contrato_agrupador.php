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
		<div id='tit'>Busca Contrato Agrupador</div>
		<div id='filtro_busca'>
			<form name='formulario2' method='post'>
				<table>
					<tr>
						<td class='descCampo'>Nome Contrato Agrupador</td>
					</tr>
					<tr>
						<td class='campo'>
							<input type='text' name='Nome' autocomplete="off" value='<?=$local_Nome?>' style='width:474px' maxlength='100' onkeyup="busca_contrato_agrupador_lista()" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='1'>
						</td>
					</tr>
				</table>
			</form>
		</div>
		<div id='carregando'>carregando...</div>
		<div class='listaDados' style='height: 180px;'>
			<div id='listaDadosQuadro'>&nbsp;</div>
		</div>
		<form name='formulario' method='post' action='busca_contrato_agrupador.php' onSubmit='return validar()'>
			<input type='hidden' name='IdContratoAgrupador' value=''>
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
		var IdPessoa	= 	window.opener.document.formulario.IdPessoa.value;
		if(valorCampo !=''){
			window.opener.busca_contrato_agrupador(IdPessoa,true,window.opener.document.formulario.Local.value,valorCampo);
		}
		return false;
	}
	function busca_contrato_agrupador_lista(){
		var Nome				 	= document.formulario2.Nome.value;
		var Local				 	= window.opener.document.formulario.Local.value;
		var IdPessoa			 	= window.opener.document.formulario.IdPessoa.value;
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
	    
	    if(Nome == ''){
	    	url = "xml/contrato_agrupador.php?Limit="+Limit+"&Local="+Local+"&IdPessoa="+IdPessoa;
		}else{
			url = "xml/contrato_agrupador.php?Nome="+Nome+"&Local="+Local+"&IdPessoa="+IdPessoa;
		}

		xmlhttp.open("GET", url,true);
		
		xmlhttp.onreadystatechange = function(){ 

			// Carregando...
			carregando(true);

			if(xmlhttp.readyState == 4){ 
				if(xmlhttp.status == 200){
					var dados = "<table id='listaDados' style='width: 488px'>\n<tr>\n<td class='listaDados_titulo' style='width: 60px'>Contrato</td>\n<td class='listaDados_titulo'>Nome Contrato Agrupador</td>\n<td class='listaDados_titulo' style='width:80px'>Data Início</td>\n</tr>";
					for(var i=0; i<xmlhttp.responseXML.getElementsByTagName("IdContratoAgrupador").length; i++){
							
						nameNode = xmlhttp.responseXML.getElementsByTagName("IdContratoAgrupador")[i]; 
						nameTextNode = nameNode.childNodes[0];
						IdContratoAgrupador = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoContratoAgrupador")[i]; 
						nameTextNode = nameNode.childNodes[0];
						DescricaoContratoAgrupador = verifica_dado(nameTextNode.nodeValue);
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("DataInicio")[i]; 
						nameTextNode = nameNode.childNodes[0];
						DataInicio = nameTextNode.nodeValue;
						
						dados += "\n<tr id='listaDados_td_"+IdContratoAgrupador+"' onClick='aciona(this,"+IdContratoAgrupador+")'>";
						dados += 	"\n<td>"+IdContratoAgrupador+"</td><td>"+DescricaoContratoAgrupador+"</td><td>"+dateFormat(DataInicio)+"</td>";
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
		var IdPessoa	=	window.opener.document.formulario.IdPessoa.value;
		if(valorCampo!=''){
			document.getElementById('listaDados_td_'+valorCampo).style.backgroundColor = "#FFFFFF";
		}
		if(valorCampo == valor){
			window.opener.busca_contrato_agrupador(IdPessoa,false,window.opener.document.formulario.Local.value,valor);
		}
		valorCampo = valor;
		document.formulario.IdContratoAgrupador.value = valorCampo;
		campo.style.backgroundColor = '<?=getParametroSistema(15,4)?>';
	}
	function inicia(){
		document.formulario2.Nome.focus();
	}
	inicia();
	busca_contrato_agrupador_lista();
</script>
