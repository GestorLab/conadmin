	function janela_busca_subgrupo_produto(){
		janelas('busca_subgrupo_produto.php',360,283,250,100,'');
	}
	function busca_subgrupo_produto(IdGrupoProduto, IdSubGrupoProduto, Erro, Local){
		if(IdGrupoProduto == ''){
			IdGrupoProduto = 0;
		}
		if(IdSubGrupoProduto == '' || IdSubGrupoProduto == undefined){
			IdSubGrupoProduto = 0;
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
		url = "xml/subgrupo_produto.php?IdGrupoProduto="+IdGrupoProduto+"&IdSubGrupoProduto="+IdSubGrupoProduto;

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
						document.formulario.IdSubGrupoProduto.value 			= '';
						document.formulario.DescricaoSubGrupoProduto.value 		= '';
						
						addParmUrl("marSubGrupoProduto","IdSubGrupoProduto",'');
						
						switch (Local){
							case "SubGrupoProduto":
								
								busca_grupo_produto(IdGrupoProduto,false,Local);
								
								document.formulario.DataCriacao.value 				= '';
								document.formulario.LoginCriacao.value 				= '';
								document.formulario.DataAlteracao.value 			= '';
								document.formulario.LoginAlteracao.value			= '';
								document.formulario.Acao.value 						= 'inserir';
						}
						document.formulario.IdSubGrupoProduto.focus();
						
						// Fim de Carregando
						carregando(false);
					}else{
						nameNode = xmlhttp.responseXML.getElementsByTagName("IdGrupoProduto")[0]; 
						nameTextNode = nameNode.childNodes[0];
						IdGrupoProduto = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoGrupoProduto")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var DescricaoGrupoProduto = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("IdSubGrupoProduto")[0]; 
						nameTextNode = nameNode.childNodes[0];
						IdSubGrupoProduto = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoSubGrupoProduto")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var DescricaoSubGrupoProduto = nameTextNode.nodeValue;
						
						addParmUrl("marSubGrupoProduto","IdSubGrupoProduto",IdSubGrupoProduto);
						addParmUrl("marSubGrupoProduto","IdGrupoProduto",IdGrupoProduto);
						
						document.formulario.IdGrupoProduto.value			= IdGrupoProduto;
						document.formulario.DescricaoGrupoProduto.value 	= DescricaoGrupoProduto;
						document.formulario.IdSubGrupoProduto.value			= IdSubGrupoProduto;
						document.formulario.DescricaoSubGrupoProduto.value 	= DescricaoSubGrupoProduto;
						
						switch (Local){		
							case "SubGrupoProduto":
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
						
								document.formulario.DataCriacao.value 					= dateFormat(DataCriacao);
								document.formulario.LoginCriacao.value 					= LoginCriacao;
								document.formulario.DataAlteracao.value 				= dateFormat(DataAlteracao);
								document.formulario.LoginAlteracao.value				= LoginAlteracao;
								
								document.formulario.Acao.value 							= 'alterar';
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
	
