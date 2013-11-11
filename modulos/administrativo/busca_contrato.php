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
		<div id='tit'>Busca Contrato</div>
		<div id='filtro_busca'>
			<form name='formulario2' method='post'>
				<table>
					<tr>
						<td class='descCampo'>Nome Pessoa/Razão Social</td>
						<td class='descCampo'>CPF/CNPJ</td>
					</tr>
					<tr>
						<td class='campo'>
							<input type='text' name='Nome' autocomplete="off" value='<?=$local_Nome?>' style='width:365px' maxlength='100' onkeyup="busca_contrato_lista()" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')">
						</td>
						<td class='campo'>
							<input type='text' name='CPF_CNPJ' value='<?=$local_CPF_CNPJ?>' autocomplete="off" style='width:125px' maxlength='18' onFocus="Foco(this,'in',true)"  onBlur="Foco(this,'out')" onkeyup="busca_contrato_lista()">
						</td>
					</tr>
				</table>
				<table>
					<tr>
						<td class='descCampo'>Nome Serviço</td>
						<td class='separador'>&nbsp;</td>
						<td class='descCampo'>Periodicidade</td>
					</tr>
					<tr>
						<td class='campo'>
							<input type='text' name='DescricaoServico' autocomplete="off" value='<?=$local_Nome?>' style='width:354px' maxlength='100' onkeyup="busca_contrato_lista()" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')">
						</td>
						<td class='separador'>&nbsp;</td>
						<td class='campo'>
							<select name='Periodicidade' style='width:134px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onChange="busca_contrato_lista()">
								<option value=''>Todos</option>
								<?
									$sql = "select IdCodigoInterno, DescricaoCodigoInterno from CodigoInterno where IdGrupoCodigoInterno=8 order by ValorCodigoInterno";
									$res = @mysql_query($sql,$con);
									while($lin = @mysql_fetch_array($res)){
										echo"<option value='$lin[IdCodigoInterno]'>$lin[DescricaoCodigoInterno]</option>";
									}
								?>
							</select>
						</td>
					</tr>
				</table>
			</form>
		</div>
		<div id='carregando'>carregando...</div>
		<div class='listaDados' style='height: 200px;'>
			<div id='listaDadosQuadro'>&nbsp;</div>
		</div>
		<form name='formulario' method='post' action='busca_contrato.php' onSubmit='return validar()'>
			<input type='hidden' name='IdContrato' value=''>
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
			window.opener.busca_contrato(valorCampo,'false',window.opener.document.formulario.Local.value);
		}
		return false;
	}
	function busca_contrato_lista(){
		var Nome 				= document.formulario2.Nome.value;
		var CPF_CNPJ			= document.formulario2.CPF_CNPJ.value;
		var DescricaoServico	= document.formulario2.DescricaoServico.value;
		var Periodicidade		= document.formulario2.Periodicidade.value;
		var Local				= window.opener.document.formulario.Local.value;
		var Limit	  			= <?=getCodigoInterno(7,4)?>;
		var nameNode, nameTextNode, url;
		
		if(Local == 'OrdemServico'){
			if(window.opener.document.formulario.IdPessoa.value == '' && window.opener.document.formulario.IdPessoaF.value == ''){
				return false;
			}else{
				var IdPessoa			= window.opener.document.formulario.IdPessoa.value;
				
				if(IdPessoa == ""){
					IdPessoa	=	window.opener.document.formulario.IdPessoaF.value;
				}
			}
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
	    
	    if(Nome == '' && DescricaoServico == '' && Periodicidade == '' && CPF_CNPJ==''){
	    	url = "../administrativo/xml/contrato.php?Limit="+Limit;
		}else{
			url = "../administrativo/xml/contrato.php?Nome="+Nome+"&DescricaoServico="+DescricaoServico+"&Periodicidade="+Periodicidade+"&CPF_CNPJ="+CPF_CNPJ;
		}
		if(Local == 'OrdemServico'){
			url	+= "&IdPessoa="+IdPessoa;
		}
		
		xmlhttp.open("GET", url,true);
		
		xmlhttp.onreadystatechange = function(){ 

			// Carregando...
			carregando(true);

			if(xmlhttp.readyState == 4){ 
				if(xmlhttp.status == 200){
					var dados = "<table id='listaDados' style='width: 511px'>\n<tr>\n<td class='listaDados_titulo' style='width: 60px'>Contrato</td>\n<td class='listaDados_titulo'>Nome Pessoa/Razão Social</td>\n<td class='listaDados_titulo'>Nome Serviço</td>\n<td class='listaDados_titulo'>Data Início</td>\n</tr>";
					for(var i=0; i<xmlhttp.responseXML.getElementsByTagName("IdContrato").length; i++){
							
						nameNode = xmlhttp.responseXML.getElementsByTagName("IdContrato")[i]; 
						nameTextNode = nameNode.childNodes[0];
						IdContrato = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("Nome")[i]; 
						nameTextNode = nameNode.childNodes[0];
						Nome = verifica_dado(nameTextNode.nodeValue);
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("RazaoSocial")[i]; 
						nameTextNode = nameNode.childNodes[0];
						RazaoSocial = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoServico")[i]; 
						nameTextNode = nameNode.childNodes[0];
						DescricaoServico = verifica_dado(nameTextNode.nodeValue);
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("DataInicio")[i]; 
						nameTextNode = nameNode.childNodes[0];
						DataInicio = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("Cor")[i]; 
						nameTextNode = nameNode.childNodes[0];
						Cor = nameTextNode.nodeValue;
												
						Nome 		= Nome.substring(0,30);
						RazaoSocial = RazaoSocial.substring(0,30);
						DescricaoServico = DescricaoServico.substring(0,20);
						
						if(RazaoSocial != '') Nome = RazaoSocial;
						
						if(Cor=="")	Cor = "#FFF";
						
						dados += "\n<tr id='listaDados_td_"+IdContrato+"' onClick='aciona(this,"+IdContrato+")'>";
						dados += 	"\n<td style='background-color:"+Cor+"'>"+IdContrato+"</td><td style='background-color:"+Cor+"'>"+Nome+"</td><td style='background-color:"+Cor+"'>"+DescricaoServico+"</td><td style='background-color:"+Cor+"'>"+dateFormat(DataInicio)+"</td>";
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
			window.opener.busca_contrato(valor,false,window.opener.document.formulario.Local.value);
		}
		valorCampo = valor;
		document.formulario.IdContrato.value = valorCampo;
		campo.style.backgroundColor = '<?=getParametroSistema(15,4)?>';
	}
	function inicia(){
		document.formulario2.Nome.focus();
	}
	inicia();
	busca_contrato_lista();
</script>
