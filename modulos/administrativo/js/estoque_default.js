	function janela_busca_estoque(){
		janelas('busca_estoque.php',360,283,250,100,'');
	}
	function busca_estoque(IdEstoque, Erro, Local){
		if(IdEstoque == ''){
			IdEstoque = 0;
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
		url = "xml/estoque.php?IdEstoque="+IdEstoque;
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
						
						document.formulario.IdEstoque.value 			= '';
						document.formulario.DescricaoEstoque.value 	= '';
						
						addParmUrl("marEstoque","IdEstoque",'');
						
						switch (Local){
							case "Estoque":
								document.formulario.DataCriacao.value 			= '';
								document.formulario.LoginCriacao.value 			= '';
								document.formulario.DataAlteracao.value 		= '';
								document.formulario.LoginAlteracao.value		= '';
								document.formulario.Acao.value 					= 'inserir';
						}
						
						document.formulario.IdEstoque.focus();
						
						// Fim de Carregando
						carregando(false);
					}else{
						nameNode = xmlhttp.responseXML.getElementsByTagName("IdEstoque")[0]; 
						nameTextNode = nameNode.childNodes[0];
						IdEstoque = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoEstoque")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var DescricaoEstoque = nameTextNode.nodeValue;
						
						document.formulario.IdEstoque.value		  = IdEstoque;
						document.formulario.DescricaoEstoque.value = DescricaoEstoque;
						
						addParmUrl("marEstoque","IdEstoque",IdEstoque);
						
						switch (Local){		
							case "Estoque":
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
	
