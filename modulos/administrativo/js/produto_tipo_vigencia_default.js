	function janela_busca_produto_tipo_vigencia(){
		janelas('busca_produto_tipo_vigencia.php',360,283,250,100,'');
	}
	function busca_produto_tipo_vigencia(IdProdutoTipoVigencia, Erro, Local){
		if(IdProdutoTipoVigencia == ''){
			IdProdutoTipoVigencia = 0;
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
		url = "xml/produto_tipo_vigencia.php?IdProdutoTipoVigencia="+IdProdutoTipoVigencia;
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
						
						document.formulario.IdProdutoTipoVigencia.value 		= '';
						document.formulario.DescricaoProdutoTipoVigencia.value 	= '';
						
						switch (Local){
							case "ProdutoTipoVigencia":
								addParmUrl("marProdutoTipoVigencia","IdProdutoTipoVigencia","");
								
								document.formulario.DataCriacao.value 						= '';
								document.formulario.LoginCriacao.value 						= '';
								document.formulario.DataAlteracao.value 					= '';
								document.formulario.LoginAlteracao.value					= '';
								document.formulario.Acao.value 								= 'inserir';
						}
						document.formulario.IdProdutoTipoVigencia.focus();
						
						// Fim de Carregando
						carregando(false);
					}else{
						nameNode = xmlhttp.responseXML.getElementsByTagName("IdProdutoTipoVigencia")[0]; 
						nameTextNode = nameNode.childNodes[0];
						IdProdutoTipoVigencia = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoProdutoTipoVigencia")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var DescricaoProdutoTipoVigencia = nameTextNode.nodeValue;
						
						document.formulario.IdProdutoTipoVigencia.value		   = IdProdutoTipoVigencia;
						document.formulario.DescricaoProdutoTipoVigencia.value = DescricaoProdutoTipoVigencia;
						
						switch (Local){		
							case "ProdutoTipoVigencia":
								addParmUrl("marProdutoTipoVigencia","IdProdutoTipoVigencia",IdProdutoTipoVigencia);
								
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
	
