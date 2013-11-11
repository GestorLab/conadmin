<?
	include ('../../files/conecta.php');
	include ('../../rotinas/verifica.php');
	include ('../../files/funcoes.php');
	
	$local_IdLoja	=	$_SESSION["IdLoja"];

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
		<div id='tit'>Busca Contas a Receber</div>
		<div id='filtro_busca'>
			<form name='formulario2' method='post'>
				<table>
					<tr>
						<td class='descCampo'>Nome Pessoa/Razão Social</td>
						<td class='descCampo'>Nº Documento</td>
						<td class='descCampo'>Data Lançamento</td>
					</tr>
					<tr>
						<td class='campo'>
							<input type='text' name='Nome' autocomplete="off" value='<?=$local_Nome?>' style='width:284px' maxlength='100' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onkeyup='busca_conta_receber_lista()'>
						</td>
						<td class='campo'>
							<input type='text' name='NumeroDocumento' value='<?=$local_CPF_CNPJ?>' autocomplete="off" style='width:100px' maxlength='11' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onkeypress="mascara(this,event,'int')" onkeyup='busca_conta_receber_lista()'>
						</td>
						<td class='campo'>
							<input type='text' name='DataLancamento' value='<?=$local_DataLancamento?>' style='width:100px' maxlength='10' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')"  onkeypress="mascara(this,event,'date')" onChange='busca_conta_receber_lista()'> 
						</td>
					</tr>
				</table>
				<table>
					<tr>
						<td class='descCampo'>Local de Cobrança</td>
						<td class='descCampo'>Data Vencimento</td>
						<td class='descCampo'>Data Pagamento</td>
					</tr>
					<tr>
						<td class='campo'>
							<select name='IdLocalCobranca' onFocus="Foco(this,'in')"  style='width:288px' onBlur="Foco(this,'out');" tabindex='5'>
								<option value=''>Todos</option>
								<?
									$sql = "select IdLocalCobranca, DescricaoLocalCobranca from LocalCobranca where IdLoja=$local_IdLoja order by DescricaoLocalCobranca";
									$res = @mysql_query($sql,$con);
									while($lin = @mysql_fetch_array($res)){
										echo"<option value='$lin[IdLocalCobranca]' ".compara($local_IdLocalCobranca,$lin[IdLocalCobranca],"selected", "").">$lin[DescricaoLocalCobranca]</option>";
									}
								?>
							</select>
						</td>
						<td class='campo'>
							<input type='text' name='DataVencimento' value='<?=$local_DataVencimento?>' style='width:100px' maxlength='10'  onkeypress="mascara(this,event,'date')" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onChange='busca_conta_receber_lista()'>
						</td>
						<td class='campo'>
							<input type='text' name='DataPagamento' value='<?=$local_DataPagamento?>' style='width:100px' maxlength='10'  onkeypress="mascara(this,event,'date')" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onChange='busca_conta_receber_lista()'>
						</td>
					</tr>
				</table>
			</form>
		</div>
		<div id='carregando'>carregando...</div>
		<div class='listaDados' style='height: 200px;'>
			<div id='listaDadosQuadro'>&nbsp;</div>
		</div>
		<form name='formulario' method='post' action='busca_conta_receber.php' onSubmit='return validar()'>
			<input type='hidden' name='IdContaReceber' value=''>
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
			window.opener.busca_conta_receber(valorCampo,'false',window.opener.document.formulario.Local.value);
		}
		return false;
	}
	function busca_conta_receber_lista(){
		var Nome 					= document.formulario2.Nome.value;
		var NumeroDocumento 		= document.formulario2.NumeroDocumento.value;
		var DataVencimento			= document.formulario2.DataVencimento.value;
		var DataLancamento			= document.formulario2.DataLancamento.value;
		var IdLocalCobranca			= document.formulario2.IdLocalCobranca.value;
		var DataPagamento			= document.formulario2.DataPagamento.value;
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
	    
	    if(Nome == '' && NumeroDocumento == '' && DataVencimento == '' && DataLancamento == '' && IdLocalCobranca=='' && DataPagamento==''){
	    	url = "../administrativo/xml/conta_receber.php?Limit="+Limit;
		}else{
			url = "../administrativo/xml/conta_receber.php?Nome="+Nome+"&NumeroDocumento="+NumeroDocumento+"&DataVencimento="+DataVencimento+"&DataLancamento="+DataLancamento+"&IdLocalCobranca="+IdLocalCobranca+"&DataPagamento="+DataPagamento;
		}
		xmlhttp.open("GET", url,true);
		
		xmlhttp.onreadystatechange = function(){ 

			// Carregando...
			carregando(true);

			if(xmlhttp.readyState == 4){ 
				if(xmlhttp.status == 200){
					var dados = "<table id='listaDados' style='width: 511px'>\n<tr>\n<td class='listaDados_titulo' style='width: 70px'>Conta Rec.</td>\n<td class='listaDados_titulo'>Nome Pessoa</td>\n<td class='listaDados_titulo'>Nº Doc.</td>\n<td class='listaDados_titulo'>Data Lanc.</td>\n<td class='listaDados_titulo'>Data Venc.</td>\n</tr>";
					for(var i=0; i<xmlhttp.responseXML.getElementsByTagName("IdContaReceber").length; i++){
						nameNode = xmlhttp.responseXML.getElementsByTagName("IdContaReceber")[i]; 
						nameTextNode = nameNode.childNodes[0];
						IdContaReceber = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("Nome")[i]; 
						nameTextNode = nameNode.childNodes[0];
						var Nome = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("NumeroDocumento")[i]; 
						nameTextNode = nameNode.childNodes[0];
						var NumeroDocumento = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("DataLancamento")[i]; 
						nameTextNode = nameNode.childNodes[0];
						var DataLancamento = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("DataVencimento")[i]; 
						nameTextNode = nameNode.childNodes[0];
						var DataVencimento = nameTextNode.nodeValue;
						
						Nome 		= Nome.substring(0,30);
						
						dados += "\n<tr id='listaDados_td_"+IdContaReceber+"' onClick='aciona(this,"+IdContaReceber+")'>";
						dados += 	"\n<td>"+IdContaReceber+"</td><td>"+Nome+"</td><td>"+NumeroDocumento+"</td><td>"+dateFormat(DataLancamento)+"</td><td>"+dateFormat(DataVencimento)+"</td>";
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
			window.opener.busca_conta_receber(valor,false,window.opener.document.formulario.Local.value);
		}
		valorCampo = valor;
		document.formulario.IdContaReceber.value = valorCampo;
		campo.style.backgroundColor = '<?=getParametroSistema(15,4)?>';
	}
	function inicia(){
		document.formulario2.Nome.focus();
	}
	inicia();
	busca_conta_receber_lista();
</script>
