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
		<div id='tit'>Busca Grupo Serviço</div>
		<div id='filtro_busca'>
			<form name='formulario2' method='post'>
				<table>
					<tr>
						<td class='descCampo'>Nome Grupo Serviço</td>
					</tr>
					<tr>
						<td class='campo'>
							<input type='text' name='DescricaoServicoGrupo' value='<?=$local_DescricaoServicoGrupo?>' autocomplete="off" style='width:330px' maxlength='50' onkeyup="busca_servico_grupo_lista()" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='2'>
						</td>
					</tr>
				</table>
			</form>
		</div>
		<div id='carregando'>carregando...</div>
		<div class='listaDados' style='height: 180px;'>
			<div id='listaDadosQuadro'>&nbsp;</div>
		</div>
		<form name='formulario' method='post' action='busca_servico_grupo.php' onSubmit='return validar()'>
			<input type='hidden' name='IdServicoGrupo' value=''>
			<table>
				<tr>
					<td>
						<input type='submit' value='Ok' class='botao'>
						<input type='button' value='Cancelar' class='botao' onClick='window.close()'>
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
			window.opener.busca_servico_grupo(valorCampo);
		}
		return false;
	}
	function busca_servico_grupo_lista(){
		var DescricaoServicoGrupo = document.formulario2.DescricaoServicoGrupo.value;
		var Limit	 			  = <?=getCodigoInterno(7,4)?>;
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
	    if(DescricaoServicoGrupo == ''){
	    	url = "xml/servico_grupo.php?Limit="+Limit;
		}else{
			url = "xml/servico_grupo.php?DescricaoServicoGrupo="+DescricaoServicoGrupo;
		}
		xmlhttp.open("GET", url,true);
		
		xmlhttp.onreadystatechange = function(){ 

			// Carregando...
			carregando(true);

			if(xmlhttp.readyState == 4){ 
				if(xmlhttp.status == 200){
					var dados = "<table id='listaDados' style='width: 343px'>\n<tr>\n<td class='listaDados_titulo' style='width: 80px'>Grupo Serv.</td>\n<td class='listaDados_titulo'>Nome Grupo Serviço</td>\n</tr>";
					for(var i=0; i<xmlhttp.responseXML.getElementsByTagName("IdServicoGrupo").length; i++){
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("IdServicoGrupo")[i]; 
						nameTextNode = nameNode.childNodes[0];
						IdServicoGrupo = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoServicoGrupo")[i]; 
						nameTextNode = nameNode.childNodes[0];
						DescricaoServicoGrupo = verifica_dado(nameTextNode.nodeValue);
						
						dados += "\n<tr id='listaDados_td_"+IdServicoGrupo+"' onClick='aciona(this,"+IdServicoGrupo+")'>";
						dados += 	"\n<td>"+IdServicoGrupo+"</td>\n<td>"+DescricaoServicoGrupo.substr(0,30)+"</td>";
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
			if(window.opener.document.formulario.Local != undefined){
				window.opener.busca_servico_grupo(valor,false,window.opener.document.formulario.Local.value);
			}else{
				window.opener.busca_servico_grupo(valor);
			}
		}
		valorCampo = valor;
		document.formulario.IdServicoGrupo.value = valorCampo;
		campo.style.backgroundColor = '<?=getParametroSistema(15,4)?>';
	}
	function inicia(){
		document.formulario2.DescricaoServicoGrupo.focus();
	}
	inicia();
	busca_servico_grupo_lista();
</script>
