	function janela_busca_tabela_preco(){
		janelas('busca_tabela_preco.php',360,283,250,100,'');
	}
	function busca_tabela_preco(IdTabelaPreco, Erro, Local){
		if(IdTabelaPreco == ''){
			IdTabelaPreco = 0;
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
		url = "xml/tabela_preco.php?IdTabelaPreco="+IdTabelaPreco;
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
						
						document.formulario.IdTabelaPreco.value 			= '';
						document.formulario.DescricaoTabelaPreco.value 	= '';
						
						addParmUrl("marTabelaPreco","IdTabelaPreco",'');
						
						switch (Local){
							case "TabelaPreco":
								document.formulario.DataCriacao.value 			= '';
								document.formulario.LoginCriacao.value 			= '';
								document.formulario.DataAlteracao.value 		= '';
								document.formulario.LoginAlteracao.value		= '';
								document.formulario.Acao.value 					= 'inserir';
								break;
						}
						
						document.formulario.IdTabelaPreco.focus();
						
						// Fim de Carregando
						carregando(false);
					}else{
						nameNode = xmlhttp.responseXML.getElementsByTagName("IdTabelaPreco")[0]; 
						nameTextNode = nameNode.childNodes[0];
						IdTabelaPreco = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoTabelaPreco")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var DescricaoTabelaPreco = nameTextNode.nodeValue;
												
						document.formulario.IdTabelaPreco.value		  = IdTabelaPreco;
						document.formulario.DescricaoTabelaPreco.value = DescricaoTabelaPreco;
						
						addParmUrl("marTabelaPreco","IdTabelaPreco",IdTabelaPreco);
						
						switch (Local){		
							case "TabelaPreco":
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
								break;
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
	
