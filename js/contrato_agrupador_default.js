	function janela_busca_contrato_agrupador(IdPessoa){
		if(IdPessoa != ''){
			janelas('busca_contrato_agrupador.php?IdPessoa='+IdPessoa,507,283,250,100,'');
		}
	}
	function busca_contrato_agrupador(IdPessoa,Erro,Local,IdContratoAgrupador){
		if(IdPessoa == ''){
			IdPessoa = 0;
		}
		if(IdContratoAgrupador == '' || IdContratoAgrupador == undefined){
			IdContratoAgrupador = '';
		}
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
	    url = "xml/contrato_agrupador.php?IdPessoa="+IdPessoa+"&IdContratoAgrupador="+IdContratoAgrupador;
	 	xmlhttp.open("GET", url,true);

		xmlhttp.onreadystatechange = function(){ 

			// Carregando...
			carregando(true);
			if(xmlhttp.readyState == 4){ 
				if(xmlhttp.status == 200){
	
					if(Erro != false){
						document.formulario.Erro.value = 0;
						verificaErro();
					}
					if(xmlhttp.responseText == 'false'){		
						document.formulario.IdContratoAgrupador.value			=	'';
						document.formulario.DescricaoContratoAgrupador.value	=	'';
						
						document.formulario.IdContratoAgrupador.focus();
						carregando(false);
					}else{
						var DescricaoContratoAgrupador, IdContratoAgrupador;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoContratoAgrupador")[0]; 
						nameTextNode = nameNode.childNodes[0];
						DescricaoContratoAgrupador = nameTextNode.nodeValue;					
							
						nameNode = xmlhttp.responseXML.getElementsByTagName("IdContratoAgrupador")[0]; 
						nameTextNode = nameNode.childNodes[0];
						IdContratoAgrupador = nameTextNode.nodeValue;					
							
						document.formulario.IdContratoAgrupador.value			=	IdContratoAgrupador;
						document.formulario.DescricaoContratoAgrupador.value	=	DescricaoContratoAgrupador;
					}	
					
					if(window.janela != undefined){
						window.janela.close();
					}
				}
				// Fim de Carregando
				carregando(false);
			} 
			return true;
		}
		xmlhttp.send(null);
	}
