<?
	include ('../../files/conecta.php');
	include ('../../rotinas/verifica.php');
	include ('../../files/funcoes.php');
	
	$local_Local	=	$_GET['Local'];
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
		<div id='tit'>Busca Serviço</div>
		<div id='filtro_busca'>
			<form name='formulario2' method='post'>
				<table>
					<tr>
						<td class='descCampo'>Nome Serviço</td>
					</tr>
					<tr>
						<td class='campo'>
							<input type='text' name='DescricaoServico' autocomplete="off" value='<?=$local_Nome?>' style='width:391px' maxlength='100' onkeyup="busca_servico_lista()" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='1'>
						</td>
					</tr>
				</table>
			</form>
		</div>
		<div id='carregando'>carregando...</div>
		<div class='listaDados' style='height: 180px;'>
			<div id='listaDadosQuadro'>&nbsp;</div>
		</div>
		<form name='formulario' method='post' action='busca_servico.php' onSubmit='return validar()'>
			<input type='hidden' name='IdServico' value=''>
			<input type='hidden' name='Local' value='<?=$local_Local?>'>
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
		if(document.formulario.Local.value != ''){
			var Local	= document.formulario.Local.value;
		}else{
			var Local	= window.opener.document.formulario.Local.value;
		}
		if(valorCampo !=''){
			window.opener.busca_servico(valorCampo,true,Local);
		}
		return false;
	}
	function busca_servico_lista(){
		var DescricaoServico	 	= document.formulario2.DescricaoServico.value;
		var Local					= window.opener.document.formulario.Local.value;
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
	    
	    if(DescricaoServico == ''){
	    	url = "xml/servico.php?Limit="+Limit+"&Local="+Local;
		}else{
			url = "xml/servico.php?DescricaoServico="+DescricaoServico+"&Local="+Local;
		}
		
		if(Local == 'ContratoServico'){
			url	+=	"&IdServicoAlterar="+window.opener.document.formulario.IdServicoAnterior.value;
		}
		if((Local == 'Contrato' && window.opener.document.formulario.Acao.value == 'inserir') || Local == 'ContratoServico'){
			url	+=	"&IdPessoaF="+window.opener.document.formulario.IdPessoaF.value+"&IdPessoa="+window.opener.document.formulario.IdPessoa.value+"&IdTipoServico=1";
		}
		if(Local == 'Servico' && (window.opener.document.formulario.IdTipoServico.value == '3' || window.opener.document.formulario.IdTipoServico.value == '4')){
			url	+=	"&IdTipoServico=1";
		}
		xmlhttp.open("GET", url,true);
		xmlhttp.onreadystatechange = function(){ 

			// Carregando...
			carregando(true);

			if(xmlhttp.readyState == 4){ 
				if(xmlhttp.status == 200){
					var dados = "<table id='listaDados' style='width: 511px'>\n<tr>\n<td class='listaDados_titulo' style='width: 70px'>Serviço</td>\n<td class='listaDados_titulo'>Nome Serviço</td>\n<td class='listaDados_titulo'>Tipo</td>\n<td class='listaDados_titulo'>Valor (<?=getParametroSistema(5,1)?>)</td>";
					for(var i=0; i<xmlhttp.responseXML.getElementsByTagName("IdServico").length; i++){
							
						nameNode = xmlhttp.responseXML.getElementsByTagName("IdServico")[i]; 
						nameTextNode = nameNode.childNodes[0];
						IdServico = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoServico")[i]; 
						nameTextNode = nameNode.childNodes[0];
						DescricaoServico = verifica_dado(nameTextNode.nodeValue);
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("DescTipoServico")[i]; 
						nameTextNode = nameNode.childNodes[0];
						DescTipoServico = verifica_dado(nameTextNode.nodeValue);
						
						DescricaoServico	=	DescricaoServico.substr(0,35);
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("Valor")[i]; 
						nameTextNode = nameNode.childNodes[0];
						Valor = nameTextNode.nodeValue;
						
						if(Valor == '')	Valor = 0;
						Valor	=	formata_float(Valor).replace(".",",");
						
						dados += "\n<tr id='listaDados_td_"+IdServico+"' onClick='aciona(this,"+IdServico+")'>";
						dados += 	"\n<td>"+IdServico+"</td><td>"+DescricaoServico+"</td><td>"+DescTipoServico+"</td><td class='valor'>"+Valor+"</td>";
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
		if(document.formulario.Local.value != ''){
			var Local	= document.formulario.Local.value;
		}else{
			var Local	= window.opener.document.formulario.Local.value;
		}
		if(valorCampo == valor){
			window.opener.busca_servico(valor,true,Local);
		}
		valorCampo = valor;
		document.formulario.IdServico.value = valorCampo;
		campo.style.backgroundColor = '<?=getParametroSistema(15,4)?>';
	}
	function inicia(){
		document.formulario2.DescricaoServico.focus();
	}
	inicia();
	busca_servico_lista();
</script>
