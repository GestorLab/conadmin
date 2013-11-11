	function busca_tipo_ordem_servico(IdTipoOrdemServico, Erro, Local){
		if(IdTipoOrdemServico == ''){
			IdTipoOrdemServico = 0;
		}
		if(Local == '' || Local == undefined){
			Local	=	document.formulario.Local.value;
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
		url = "xml/tipo_ordem_servico.php?IdTipoOrdemServico="+IdTipoOrdemServico;
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
						
						document.formulario.IdTipoOrdemServico.value 			= '';
						document.formulario.DescricaoTipoOrdemServico.value 	= '';
						
						switch (Local){
							case "TipoOrdemServico":
								addParmUrl("marTipoOrdemServico","IdTipoOrdemServico",'');
								addParmUrl("marSubTipoOrdemServico","IdTipoOrdemServico",'');
								addParmUrl("marSubTipoOrdemServicoNovo","IdTipoOrdemServico",'');
								
								document.formulario.Cor.value					= '';
								document.formulario.DataAlteracao.value 		= '';
								document.formulario.LoginAlteracao.value 		= '';
								document.formulario.Acao.value 					= 'inserir';
								break;
						}
						
						document.formulario.IdTipoOrdemServico.focus();
						
						// Fim de Carregando
						carregando(false);
					}else{
						nameNode = xmlhttp.responseXML.getElementsByTagName("IdTipoOrdemServico")[0]; 
						nameTextNode = nameNode.childNodes[0];
						IdTipoOrdemServico = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoTipoOrdemServico")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var DescricaoTipoOrdemServico = nameTextNode.nodeValue;
						
						document.formulario.IdTipoOrdemServico.value		  = IdTipoOrdemServico;
						document.formulario.DescricaoTipoOrdemServico.value   = DescricaoTipoOrdemServico;
						
						switch (Local){		
							case "TipoOrdemServico":
								addParmUrl("marTipoOrdemServico","IdTipoOrdemServico",IdTipoOrdemServico);
								addParmUrl("marSubTipoOrdemServico","IdTipoOrdemServico",IdTipoOrdemServico);
								addParmUrl("marSubTipoOrdemServicoNovo","IdTipoOrdemServico",IdTipoOrdemServico);
								
								nameNode = xmlhttp.responseXML.getElementsByTagName("Cor")[0]; 
								nameTextNode = nameNode.childNodes[0];
								var Cor = nameTextNode.nodeValue;
							
								nameNode = xmlhttp.responseXML.getElementsByTagName("DataAlteracao")[0]; 
								nameTextNode = nameNode.childNodes[0];
								var DataAlteracao = nameTextNode.nodeValue;					
							
								nameNode = xmlhttp.responseXML.getElementsByTagName("LoginAlteracao")[0]; 
								nameTextNode = nameNode.childNodes[0];
								var LoginAlteracao = nameTextNode.nodeValue;
							
								document.formulario.Cor.value 				= Cor;
								document.formulario.DataAlteracao.value 	= dateFormat(DataAlteracao);
								document.formulario.LoginAlteracao.value 	= LoginAlteracao;
								document.formulario.Acao.value 				= 'alterar';
								
								break;
							case 'SubTipoOrdemServico':
								document.formulario.IdSubTipoOrdemServico.focus();
								break;
						}
					}
					if(document.getElementById("quadroBuscaTipoOrdemServico") != null){
						if(document.getElementById("quadroBuscaTipoOrdemServico").style.display == "block"){
							document.getElementById("quadroBuscaTipoOrdemServico").style.display =	"none";
						}
					}
					verificaAcao();
				}
				// Fim de Carregando
				carregando(false);
			} 
			return true;
		}
		xmlhttp.send(null);
	}	
	
