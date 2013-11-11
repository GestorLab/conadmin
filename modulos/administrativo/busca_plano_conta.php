<?
	include ('../../files/conecta.php');
	include ('../../rotinas/verifica.php');
	include ('../../files/funcoes.php');
	 
	$local_Tipo			=	$_GET['Tipo'];
	$local_AcessoRapido	=	$_GET['AcessoRapido'];
?>
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
		<div id='tit'>Busca Plano de Conta</div>
		<div id='filtro_busca'>
			<form name='formulario2' method='post'>
				<table>
					<tr>
						<td class='descCampo'>Plano de Conta</td>
						<td class='descCampo'>Nome Plano de Conta</td>
						<td class='descCampo'>Ac. Rápido</td>
					</tr>
					<tr>
						<td class='campo'>
							<input type='text' name='IdPlanoConta' value='<?=$local_IdPlanoConta?>' size='9' style='width:110px' onkeyup="busca_plano_conta_lista()" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onkeypress="mascara(this,event,'PlanoConta','N')" tabindex='1'> 
						</td>
						<td class='campo'>
							<input type='text' name='DescricaoPlanoConta' value='<?=$local_Descricao?>' autocomplete="off" style='width:227px' maxlength='100' onkeyup="busca_plano_conta_lista()" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='2'>
						</td>
						<td class='campo'>
							<input type='text' name='IdAcessoRapido' value='<?=$local_IdAcessoRapido?>' autocomplete="off" style='width:134px' maxlength='30' onkeyup="busca_plano_conta_lista()" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='3'>
						</td>
					</tr>
				</table>
			</form>
		</div>
		<div id='carregando'>carregando...</div>
		<div class='listaDados' style='height: 200px;'>
			<div id='listaDadosQuadro'>&nbsp;</div>
		</div>
		<form name='formulario' method='post' action='busca_plano_conta.php' onSubmit='return validar()'>
			<input type='hidden' name='IdPlanoConta' value=''>
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
			window.opener.busca_plano_conta(valorCampo,'<?=$local_Tipo?>',true,window.opener.document.formulario.Local.value,'<?=$local_AcessoRapido?>','');
		}
		return false;
	}
	function busca_plano_conta_lista(){
		var IdPlanoConta 			= document.formulario2.IdPlanoConta.value;
		var DescricaoPlanoConta	 	= document.formulario2.DescricaoPlanoConta.value;
		var IdAcessoRapido		 	= document.formulario2.IdAcessoRapido.value;
		var Limit	  	 			= <?=getCodigoInterno(7,4)?>;
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
	    
	    if(IdPlanoConta == '' && DescricaoPlanoConta == '' && IdAcessoRapido == ''){
	    	url = "xml/plano_conta.php?Limit="+Limit;
		}else{
			url = "xml/plano_conta.php?IdPlanoConta="+IdPlanoConta+"&DescricaoPlanoConta="+DescricaoPlanoConta+"&IdAcessoRapido="+IdAcessoRapido;
		}
		xmlhttp.open("GET", url,true);
		
		xmlhttp.onreadystatechange = function(){ 

			// Carregando...
			carregando(true);

			if(xmlhttp.readyState == 4){ 
				if(xmlhttp.status == 200){
					var dados = "<table id='listaDados' style='width: 483px; font-size:10px'>\n<tr>\n<td class='listaDados_titulo' style='width: 100px'>Plano de Conta</td>\n<td class='listaDados_titulo'>Nome</td>\n<td class='listaDados_titulo' style='width:55px'>Tipo</td>\n<td class='listaDados_titulo'>Ac. Rápido</td>\n</tr>";
					
					for(var i=0; i<xmlhttp.responseXML.getElementsByTagName("IdPlanoConta").length; i++){
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("IdPlanoConta")[i]; 
						nameTextNode = nameNode.childNodes[0];
						IdPlanoConta = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoPlanoConta")[i]; 
						nameTextNode = nameNode.childNodes[0];
						DescricaoPlanoConta = verifica_dado(nameTextNode.nodeValue);

						nameNode = xmlhttp.responseXML.getElementsByTagName("IdAcessoRapido")[i]; 
						nameTextNode = nameNode.childNodes[0];
						IdAcessoRapido = verifica_dado(nameTextNode.nodeValue);
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoTipo")[i]; 
						nameTextNode = nameNode.childNodes[0];
						Tipo = verifica_dado(nameTextNode.nodeValue);
						
						dados += "\n<tr id='listaDados_td_"+IdPlanoConta+"' onClick=\"aciona(this,'"+IdPlanoConta+"','"+Tipo+"')\">";
						dados += "\n<td>"+IdPlanoConta+"</td>\n<td>"+DescricaoPlanoConta+"</td>\n<td>"+Tipo+"</td>\n<td>"+IdAcessoRapido+"</td>";
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
	function aciona(campo,valor,Tipo){
		if(valorCampo!=''){
			document.getElementById('listaDados_td_'+valorCampo).style.backgroundColor = "#FFFFFF";
		}
		var Local = window.opener.document.formulario.Local.value;
		if(Local == 'ContaPagar'){
			if(Tipo == 'Analítico'){
				if(valorCampo == valor){
						window.opener.busca_plano_conta(valor,'<?=$local_Tipo?>',true,window.opener.document.formulario.Local.value,'<?=$local_AcessoRapido?>','');
				}
				valorCampo = valor;
				document.formulario.IdPlanoConta.value = valorCampo;
				campo.style.backgroundColor = '<?=getParametroSistema(15,4)?>';	
			}
		}else{
			if(valorCampo == valor){
					window.opener.busca_plano_conta(valor,'<?=$local_Tipo?>',true,window.opener.document.formulario.Local.value,'<?=$local_AcessoRapido?>','');
			}
			valorCampo = valor;
			document.formulario.IdPlanoConta.value = valorCampo;
			campo.style.backgroundColor = '<?=getParametroSistema(15,4)?>';
		}
	}
	function inicia(){
		document.formulario2.IdPlanoConta.focus();
	}
	inicia();
	busca_plano_conta_lista();
</script>
