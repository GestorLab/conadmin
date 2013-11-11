	function busca_subtipo_ordem_servico(IdTipoOrdemServico, IdSubTipoOrdemServico, Erro, Local){
		if(IdTipoOrdemServico == ''){
			IdTipoOrdemServico = 0;
		}
		if(IdSubTipoOrdemServico == '' || IdSubTipoOrdemServico == undefined){
			IdSubTipoOrdemServico = 0;
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
		url = "xml/subtipo_ordem_servico.php?IdTipoOrdemServico="+IdTipoOrdemServico+"&IdSubTipoOrdemServico="+IdSubTipoOrdemServico;

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
						document.formulario.IdSubTipoOrdemServico.value 			= '';
						document.formulario.DescricaoSubTipoOrdemServico.value 		= '';
						
						addParmUrl("marSubTipoOrdemServico","IdSubTipoOrdemServico",'');
						
						switch (Local){
							case "SubTipoOrdemServico":
								busca_tipo_ordem_servico(IdTipoOrdemServico,false,Local);
								
								document.formulario.Cor.value 						= '';
								document.formulario.DataCriacao.value 				= '';
								document.formulario.LoginCriacao.value				= '';
								document.formulario.DataAlteracao.value 			= '';
								document.formulario.LoginAlteracao.value			= '';
								document.formulario.Acao.value 						= 'inserir';
						}
						document.formulario.IdSubTipoOrdemServico.focus();
						
						// Fim de Carregando
						carregando(false);
					}else{
						nameNode = xmlhttp.responseXML.getElementsByTagName("IdTipoOrdemServico")[0]; 
						nameTextNode = nameNode.childNodes[0];
						IdTipoOrdemServico = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoTipoOrdemServico")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var DescricaoTipoOrdemServico = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("IdSubTipoOrdemServico")[0]; 
						nameTextNode = nameNode.childNodes[0];
						IdSubTipoOrdemServico = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoSubTipoOrdemServico")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var DescricaoSubTipoOrdemServico = nameTextNode.nodeValue;
						
						addParmUrl("marSubTipoOrdemServico","IdSubTipoOrdemServico",IdSubTipoOrdemServico);
						addParmUrl("marSubTipoOrdemServico","IdTipoOrdemServico",IdTipoOrdemServico);
						addParmUrl("marSubTipoOrdemServicoNovo","IdTipoOrdemServico",IdTipoOrdemServico);
						addParmUrl("marTipoOrdemServico","IdTipoOrdemServico",IdTipoOrdemServico);
						
						document.formulario.IdTipoOrdemServico.value			= IdTipoOrdemServico;
						document.formulario.DescricaoTipoOrdemServico.value 	= DescricaoTipoOrdemServico;
						document.formulario.IdSubTipoOrdemServico.value			= IdSubTipoOrdemServico;
						document.formulario.DescricaoSubTipoOrdemServico.value 	= DescricaoSubTipoOrdemServico;
						
						switch (Local){		
							case "SubTipoOrdemServico":
								nameNode = xmlhttp.responseXML.getElementsByTagName("Cor")[0]; 
								nameTextNode = nameNode.childNodes[0];
								var Cor = nameTextNode.nodeValue;			
								
								nameNode = xmlhttp.responseXML.getElementsByTagName("DataCriacao")[0]; 
								nameTextNode = nameNode.childNodes[0];
								var DataCriacao = nameTextNode.nodeValue;
							
								nameNode = xmlhttp.responseXML.getElementsByTagName("LoginCriacao")[0]; 
								nameTextNode = nameNode.childNodes[0];
								var LoginCriacao = nameTextNode.nodeValue;
								
								nameNode = xmlhttp.responseXML.getElementsByTagName("DataAlteracao")[0]; 
								nameTextNode = nameNode.childNodes[0];
								var DataAlteracao = nameTextNode.nodeValue;
							
								nameNode = xmlhttp.responseXML.getElementsByTagName("LoginAlteracao")[0]; 
								nameTextNode = nameNode.childNodes[0];
								var LoginAlteracao = nameTextNode.nodeValue;
						
								document.formulario.Cor.value 						= Cor;
								document.formulario.DataCriacao.value 				= dateFormat(DataCriacao);
								document.formulario.LoginCriacao.value				= LoginCriacao;
								document.formulario.DataAlteracao.value 			= dateFormat(DataAlteracao);
								document.formulario.LoginAlteracao.value			= LoginAlteracao;
								document.formulario.Acao.value 						= 'alterar';
						}
					}
					if(window.janela != undefined){
						window.janela.close();
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
	
