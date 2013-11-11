function filtro_buscar_servico(IdServico){
	if(IdServico == '' || IdServico == undefined){
		IdServico = 0;
	}
	var IdStatus = '';
	
	//var url = "xml/servico.php?IdServico="+IdServico+"&IdStatus=1&IdTipoServico=1";
	var url = "xml/servico.php?IdServico="+IdServico+"&IdTipoServico=1,4";
	
	call_ajax(url, function (xmlhttp) {
		if(xmlhttp.responseText == 'false'){
			document.formulario.IdServico.value			= '';
			document.formulario.DescricaoServico.value	= '';
		} else {
			var nameNode = xmlhttp.responseXML.getElementsByTagName("IdServico")[0]; 
			var nameTextNode = nameNode.childNodes[0];
			var IdServico = nameTextNode.nodeValue;
			
			nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoServico")[0]; 
			nameTextNode = nameNode.childNodes[0];
			var DescricaoServico = nameTextNode.nodeValue;
			
			nameNode = xmlhttp.responseXML.getElementsByTagName("IdTipoServico")[0]; 
			nameTextNode = nameNode.childNodes[0];
			var IdTipoServico = nameTextNode.nodeValue;
			
			document.formulario.IdServico.value				= IdServico;
			document.formulario.DescricaoServico.value		= DescricaoServico;
			document.formulario.IdTipoServico.value			= IdTipoServico;
			
			if(document.formulario.IdServico != undefined) {
				document.formulario.IdServico.value = "";
			}
		}
	});
}
function busca_servico(IdServico,Erro,Local,ListarCampo){
	if(IdServico == "" || IdServico == undefined){
		IdServico = 0;
	}
	
	url = "xml/servico.php?IdServico="+IdServico;
	call_ajax(url,function(xmlhttp){
		if(xmlhttp.responseText != 'false'){
			nameNode = xmlhttp.responseXML.getElementsByTagName("IdServico")[0]; 
			nameTextNode = nameNode.childNodes[0];
			var IdServico = nameTextNode.nodeValue;
			
			nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoServico")[0]; 
			nameTextNode = nameNode.childNodes[0];
			var DescricaoServico = nameTextNode.nodeValue;
			
			nameNode = xmlhttp.responseXML.getElementsByTagName("DescTipoServico")[0]; 
			nameTextNode = nameNode.childNodes[0];
			var IdTipoServico = nameTextNode.nodeValue;
			
			document.formulario.IdServico.value 		= IdServico;
			document.formulario.DescricaoServico.value 	= DescricaoServico;
			document.formulario.IdTipoServico.value 	= IdTipoServico;
			
			addServico(IdServico,'');
		}else{
			document.formulario.IdServico.value 		= "";
			document.formulario.DescricaoServico.value 	= "";
			document.formulario.IdTipoServico.value 	= "";
		}
	});
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
								
								if(document.formulario.IdStatus.value == 1){
									c2.innerHTML = "<img src='../../img/estrutura_sistema/ico_del.gif' alt='Excluir?' onClick=\"remover_filtro_status("+IdStatusContrato+")\"></tr>";
								}else{
									c2.innerHTML = "<img src='../../img/estrutura_sistema/ico_del_c.gif' alt='Excluir?'\">";
								}
								c2.style.textAlign = "center";
								c2.style.cursor = "pointer";
								
								if(document.formulario.IdProcessoFinanceiro.value == ''){
									document.getElementById('totaltabelaStatus').innerHTML	=	'Total: '+(ii+1);
								}else{
									if(document.formulario.Erro.value != ''){
										scrollWindow('bottom');
									}
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

function addServico(IdServico,ListarCampo){
	if(IdServico != "" && IdServico!=undefined){
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
		
		url = "xml/servico.php?IdServico="+IdServico;
		xmlhttp.open("GET", url,true);

		xmlhttp.onreadystatechange = function(){ 
	
			// Carregando...
			carregando(true);
	
			if(xmlhttp.readyState == 4){ 
				if(xmlhttp.status == 200){
					if(xmlhttp.responseText == 'false'){		
						document.formulario.IdServico.value			= '';
						
						// Fim de Carregando
						carregando(false);
					}else{
						var cont = 0; ii='';
						if(ListarCampo == '' || ListarCampo == undefined){
							if(document.formulario.IdServico.value == ''){
								document.formulario.IdServico.value = IdServico;
								ii = 0;
							}else{
								var tempFiltro	=	document.formulario.IdServico.value.split(',');
									
								ii=0; 
								while(tempFiltro[ii] != undefined){
									if(tempFiltro[ii] != IdServico){
										cont++;		
									}
									ii++;
								}
								if(ii == cont){
									document.formulario.IdServico.value = document.formulario.IdServico.value + "," + IdServico;
								}
							}
						}else{
							ii=0;
						}
						if(ii == cont){
						
							nameNode = xmlhttp.responseXML.getElementsByTagName("IdServico")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var IdServico = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoServico")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var DescricaoServico = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("DescTipoServico")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var DescTipoServico = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("Valor")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var Valor = nameTextNode.nodeValue;
							
							var tam, linha, c0, c1, c2, c3, c4, c5, c6, c7, c8, c9, c10, c11;
							
							tam 	= document.getElementById('tabelaServico').rows.length;
							linha	= document.getElementById('tabelaServico').insertRow(tam-1);
							
							if(tam%2 != 0){
								linha.style.backgroundColor = "#E2E7ED";
							}
							
							linha.accessKey 			= IdServico; 
							
							c0	= linha.insertCell(0);	
							c1	= linha.insertCell(1);	
							c2	= linha.insertCell(2);
							c3	= linha.insertCell(3);
							c4	= linha.insertCell(4);
							
							c0.innerHTML = IdServico;
							c0.style.padding =	"0 0 0 5px";
							
							c1.innerHTML = DescricaoServico;
							c2.innerHTML = DescTipoServico;
							c3.innerHTML = Valor;
							c3.setAttribute("class","valor");
							if(IdServico >= 1){
								c4.innerHTML = "<img src='../../img/estrutura_sistema/ico_del.gif' alt='Excluir?' onClick=\"remover_filtro_servico("+IdServico+")\"></tr>";
							}else{
								c4.innerHTML = "<img src='../../img/estrutura_sistema/ico_del_c.gif' alt='Excluir?'\">";
							}
							c4.style.textAlign = "center";
							c4.style.cursor = "pointer";
							
							if(document.formulario.IdServico.value == ''){
								document.getElementById('totaltabelaServico').innerHTML	=	'Total: '+(ii+1);
							}else{
								if(document.formulario.Erro.value != ''){
									scrollWindow('bottom');
								}
							}
						}
						
						document.formulario.IdServico.value			=	"";
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

function remover_filtro_servico(IdServico){
	for(var i=0; i<document.getElementById('tabelaServico').rows.length; i++){
		if(IdServico == document.getElementById('tabelaServico').rows[i].accessKey){
			document.getElementById('tabelaServico').deleteRow(i);
			tableMultColor('tabelaServico');
			break;
		}
	}	
	var tempFiltro	=	document.formulario.Filtro_IdServico.value.split(',');
	var novoFiltro  = '';
	
	var ii = 0;
	while(tempFiltro[ii] != undefined){
		if(tempFiltro[ii] != IdServico){
			if(novoFiltro == ''){
				novoFiltro = tempFiltro[ii];
			}else{
				novoFiltro = novoFiltro + "," + tempFiltro[ii];
			}
		}
		ii=ii+1;
	}
	
	document.formulario.Filtro_IdServico.value = novoFiltro;
	document.getElementById('totaltabelaServico').innerHTML	=	'Total: '+(ii-1);
}