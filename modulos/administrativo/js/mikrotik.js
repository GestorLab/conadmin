function validar(){
	return true;
}
function inicia(){
	document.formulario.IdStatusContrato.focus();
}

function addStatus(IdStatusContrato,ListarCampo){
	if(IdStatusContrato != "" && IdStatusContrato!=undefined){
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
		
		url = "xml/parametro_sistema.php?IdGrupoParametroSistema=69&IdParametroSistema="+IdStatusContrato;
		xmlhttp.open("GET", url,true);

		xmlhttp.onreadystatechange = function(){ 
	
			// Carregando...
			carregando(true);
	
			if(xmlhttp.readyState == 4){ 
				if(xmlhttp.status == 200){
					if(xmlhttp.responseText == 'false'){		
						document.formulario.IdStatusContrato.value			= '';
						
						// Fim de Carregando
						carregando(false);
					}else{
						var cont = 0; ii='';
						if(ListarCampo == '' || ListarCampo == undefined){
							if(document.formulario.Filtro_IdStatusContrato.value == ''){
								document.formulario.Filtro_IdStatusContrato.value = IdStatusContrato;
								ii = 0;
							}else{
								var tempFiltro	=	document.formulario.Filtro_IdStatusContrato.value.split(',');
									
								ii=0; 
								while(tempFiltro[ii] != undefined){
									if(tempFiltro[ii] != IdStatusContrato){
										cont++;		
									}
									ii++;
								}
								if(ii == cont){
									document.formulario.Filtro_IdStatusContrato.value = document.formulario.Filtro_IdStatusContrato.value + "," + IdStatusContrato;
								}
							}
						}else{
							ii=0;
						}
						if(ii == cont){
						
							nameNode = xmlhttp.responseXML.getElementsByTagName("IdParametroSistema")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var IdParametroSistema = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("ValorParametroSistema")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var ValorParametroSistema = nameTextNode.nodeValue;
							
							var tam, linha, c0, c1, c2, c3, c4, c5, c6, c7, c8, c9, c10, c11;
							
							tam 	= document.getElementById('tabelaStatus').rows.length;
							linha	= document.getElementById('tabelaStatus').insertRow(tam-1);
							
							if(tam%2 != 0){
								linha.style.backgroundColor = "#E2E7ED";
							}
							
							linha.accessKey 			= IdParametroSistema; 
							
							c0	= linha.insertCell(0);	
							c1	= linha.insertCell(1);	
							c2	= linha.insertCell(2);
							
							c0.innerHTML = IdParametroSistema;
							c0.style.padding =	"0 0 0 5px";
							
							c1.innerHTML = ValorParametroSistema;														

							c2.innerHTML = "<img src='../../img/estrutura_sistema/ico_del.gif' alt='Excluir?' onClick=\"remover_filtro_status("+IdStatusContrato+")\"></tr>";							
							c2.style.textAlign = "center";
							c2.style.cursor = "pointer";
			
							document.getElementById('totaltabelaStatus').innerHTML	=	'Total: '+(ii+1);
			
							if(document.formulario.Erro.value != ''){
								scrollWindow('bottom');
							}				
						}							
						document.formulario.IdStatusContrato.value			=	"";
					}
				}
				// Fim de Carregando
				carregando(false);
			} 
			return true;
		}
		xmlhttp.send(null);
	}else{
		document.formulario.IdStatusContrato.value			= '';
		document.formulario.IdStatusContrato.focus();
	}
}

function remover_filtro_status(IdStatusContrato){
	for(var i=0; i<document.getElementById('tabelaStatus').rows.length; i++){
		if(IdStatusContrato == document.getElementById('tabelaStatus').rows[i].accessKey){
			document.getElementById('tabelaStatus').deleteRow(i);
			tableMultColor('tabelaStatus');
			break;
		}
	}	
	var tempFiltro	=	document.formulario.Filtro_IdStatusContrato.value.split(',');
	var novoFiltro  = '';
	
	var ii = 0;
	while(tempFiltro[ii] != undefined){
		if(tempFiltro[ii] != IdStatusContrato){
			if(novoFiltro == ''){
				novoFiltro = tempFiltro[ii];
			}else{
				novoFiltro = novoFiltro + "," + tempFiltro[ii];
			}
		}
		ii=ii+1;
	}
	
	document.formulario.Filtro_IdStatusContrato.value = novoFiltro;
	document.getElementById('totaltabelaStatus').innerHTML	=	'Total: '+(ii-1);
}