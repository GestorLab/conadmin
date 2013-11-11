	function busca_filtro_cidade(IdEstado,IdCidadeTemp){
		if(IdEstado == undefined || IdEstado==''){
			IdEstado = 0;			
		}
		
		if(IdCidadeTemp == undefined){
			IdCidadeTemp = '';
		}
		var xmlhttp = false;
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
	    
	    url = "xml/cidade.php?IdPais="+1+"&IdEstado="+IdEstado;

		xmlhttp.open("GET", url,true);
	    	
		xmlhttp.onreadystatechange = function(){ 
			if(xmlhttp.readyState == 4){ 
				if(xmlhttp.status == 200){
					if(xmlhttp.responseText != 'false'){		
						while(document.filtro.filtro_cidade.options.length > 0){
							document.filtro.filtro_cidade.options[0] = null;
						}
						
						var nameNode, nameTextNode, NomeCidade;	
						addOption(document.filtro.filtro_cidade,"","");				
											
						for(var i=0; i<xmlhttp.responseXML.getElementsByTagName("IdCidade").length; i++){
							nameNode = xmlhttp.responseXML.getElementsByTagName("IdCidade")[i]; 
							nameTextNode = nameNode.childNodes[0];
							IdCidade = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("NomeCidade")[i]; 
							nameTextNode = nameNode.childNodes[0];
							NomeCidade = nameTextNode.nodeValue;
						
							addOption(document.filtro.filtro_cidade,NomeCidade,IdCidade);
						}					
						
						if(IdCidadeTemp!=""){
							for(i=0;i<document.filtro.filtro_cidade.length;i++){
								if(document.filtro.filtro_cidade[i].value == IdCidadeTemp){
									document.filtro.filtro_cidade[i].selected	=	true;
									break;
								}
							}
						}else{
							document.filtro.filtro_cidade[0].selected	=	true;
						}						
					}else{
						while(document.filtro.filtro_cidade.options.length > 0){
							document.filtro.filtro_cidade.options[0] = null;
						}						
					}
					
				}		
			}
			return true;
		}
		xmlhttp.send(null);	
	}
	
	function filtro_buscar_servico(IdServico){
		if(IdServico == '' || IdServico == undefined){
			IdServico = 0;
		}
		var IdStatus = '';
	    
		//var url = "xml/servico.php?IdServico="+IdServico+"&IdStatus=1&IdTipoServico=1";
		var url = "xml/servico.php?IdServico="+IdServico+"&IdTipoServico=1,4";
		
		call_ajax(url, function (xmlhttp) {
			if(xmlhttp.responseText == 'false'){
				document.filtro.filtro_id_servico.value				= '';
				document.filtro.filtro_descricao_id_servico.value	= '';
			} else {
				var nameNode = xmlhttp.responseXML.getElementsByTagName("IdServico")[0]; 
				var nameTextNode = nameNode.childNodes[0];
				var IdServico = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoServico")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var DescricaoServico = nameTextNode.nodeValue;
				
				document.filtro.filtro_id_servico.value				= IdServico;
				document.filtro.filtro_descricao_id_servico.value	= DescricaoServico;
				
				if(document.filtro.IdServico != undefined) {
					document.filtro.IdServico.value = "";
				}
			}
		});
	}
	
	function filtro_buscar_pessoa(IdPessoa){
		var xmlhttp = false;
		if(IdPessoa == '' ||  IdPessoa == undefined){
			IdPessoa = 0;
		}
		
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
	    
	    url = "xml/pessoa.php?IdPessoa="+IdPessoa+"&IdStatus=1&IdTipoServico=1";
		xmlhttp.open("GET", url,true);
		// Carregando...
		carregando(true);
		xmlhttp.onreadystatechange = function(){ 
			if(xmlhttp.readyState == 4){ 
				if(xmlhttp.status == 200){
					if(xmlhttp.responseText == 'false'){
						document.filtro.filtro_id_pessoa.value				= '';
						document.filtro.filtro_descricao_id_pessoa.value	= '';
					} else {
						nameNode = xmlhttp.responseXML.getElementsByTagName("IdPessoa")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var IdPessoa = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("Nome")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var Nome = nameTextNode.nodeValue;
						
						document.filtro.filtro_id_pessoa.value				= IdPessoa;
						document.filtro.filtro_descricao_id_pessoa.value	= Nome;
						document.filtro.IdPessoa.value = "";
					}
					// Fim de Carregando
					carregando(false);
				}
			}
		}
		xmlhttp.send(null);	
	}