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
		<div id='tit'>Busca Terceiro</div>
		<div id='filtro_busca'>
			<form name='formulario2' method='post'>
				<table>
					<tr>
						<td class='descCampo'>Nome Pessoa/Razão Social</td>
						<td class='descCampo'>CPF/CNPJ</td>
					</tr>
					<tr>
						<td class='campo'>
							<input type='text' name='Nome' autocomplete="off" value='' style='width:365px' maxlength='100' onkeyup="busca_terceiro_lista()" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')">
						</td>
						<td class='campo'>
							<input type='text' name='CPF_CNPJ' value='' autocomplete="off" style='width:125px' maxlength='18' onFocus="Foco(this,'in',true)"  onBlur="Foco(this,'out')" onkeyup="busca_terceiro_lista()">
						</td>
					</tr>
				</table>
				<table>
					<tr>
						<td class='descCampo'>País</td>
						<td class='descCampo'>Estado</td>
						<td class='descCampo'>Nome Cidade</td>
					</tr>
					<tr>
						<td>
							<select name='IdPais'  style='width:113px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onChange="busca_terceiro_lista(); verifica_estado(this.value)">
								<option value=''>Todos</option>
								<?
									$sql = "select IdPais, NomePais from Pais order by NomePais";
									$res = mysql_query($sql,$con);
									while($lin = mysql_fetch_array($res)){
										echo "<option value='$lin[IdPais]'>$lin[NomePais]</option>";
									}
								?>
							</select>
						</td>
						<td>
							<select name='IdEstado' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" style='width:161px'  onChange="busca_terceiro_lista()">
								<option value=''>Todos</option>
								<?
									$sql	=	"select IdEstado, NomeEstado from Pais,Estado where Estado.IdPais = Pais.IdPais and Estado.IdPais=$localIdPais order by NomeEstado";
									$res	=	mysql_query($sql,$con);
									while($lin = mysql_fetch_array($res)){
										echo "<option value='$lin[IdEstado]'>$lin[NomeEstado]</option>\n";
									}
								?>
							</select>
						</td>
						<td class='campo'>
							<input type='text' name='NomeCidade' value='' style='width:220px' maxlength='100' onkeyup="busca_terceiro_lista()" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')">
						</td>
					</tr>
				</table>
			</form>
		</div>
		<div id='carregando'>carregando...</div>
		<div class='listaDados' style='height: 200px;'>
			<div id='listaDadosQuadro'>&nbsp;</div>
		</div>
		<form name='formulario' method='post' action='busca_terceiro.php' onSubmit='return validar()'>
			<input type='hidden' name='IdPessoa' value=''>
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
			window.opener.busca_terceiro(valorCampo,false);
		}
		return false;
	}
	function busca_terceiro_lista(){
		var Nome 		= document.formulario2.Nome.value;
		var IdPais 		= document.formulario2.IdPais.value;
		var IdEstado	= document.formulario2.IdEstado.value;
		var NomeCidade	= document.formulario2.NomeCidade.value;
		var CPF_CNPJ	= document.formulario2.CPF_CNPJ.value;
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
	    
	    if(Nome == '' && IdPais == '' && IdEstado == '' && NomeCidade == '' && CPF_CNPJ==''){
	    	url = "../administrativo/xml/terceiro.php?Limit="+Limit;
		}else{
			url = "../administrativo/xml/terceiro.php?Nome="+Nome+"&IdPais="+IdPais+"&IdEstado="+IdEstado+"&NomeCidade="+NomeCidade+"&CPF_CNPJ="+CPF_CNPJ;
		}
		xmlhttp.open("GET", url,true);
		
		xmlhttp.onreadystatechange = function(){ 

			// Carregando...
			carregando(true);

			if(xmlhttp.readyState == 4){ 
				if(xmlhttp.status == 200){
					var dados = "<table id='listaDados' style='width: 511px'>\n<tr>\n<td class='listaDados_titulo' style='width: 60px'>Pessoa</td>\n<td class='listaDados_titulo'>Nome Terceiro</td>\n<td class='listaDados_titulo'>Razão Social</td>\n</tr>";
					for(var i=0; i<xmlhttp.responseXML.getElementsByTagName("IdPessoa").length; i++){
							
						nameNode = xmlhttp.responseXML.getElementsByTagName("IdPessoa")[i]; 
						nameTextNode = nameNode.childNodes[0];
						IdPessoa = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("Nome")[i]; 
						nameTextNode = nameNode.childNodes[0];
						Nome = verifica_dado(nameTextNode.nodeValue);
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("RazaoSocial")[i]; 
						nameTextNode = nameNode.childNodes[0];
						RazaoSocial = verifica_dado(nameTextNode.nodeValue);
						
						Nome 		= Nome.substring(0,30);
						RazaoSocial = RazaoSocial.substring(0,30);
						
						dados += "\n<tr id='listaDados_td_"+IdPessoa+"' onClick='aciona(this,"+IdPessoa+")'>";
						dados += 	"\n<td>"+IdPessoa+"</td><td>"+Nome+"</td><td>"+RazaoSocial+"</td>";
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
			window.opener.busca_terceiro(valor);
		}
		valorCampo = valor;
		document.formulario.IdPessoa.value = valorCampo;
		campo.style.backgroundColor = '<?=getParametroSistema(15,4)?>';
	}
	function inicia(){
		document.formulario2.Nome.focus();
	}
	inicia();
	busca_terceiro_lista();
</script>
