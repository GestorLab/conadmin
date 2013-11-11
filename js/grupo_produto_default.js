	function janela_busca_grupo_produto(){
		janelas('busca_grupo_produto.php',360,283,250,100,'');
	}
	function busca_grupo_produto(IdGrupoProduto, Erro, Local){
		if(IdGrupoProduto == ''){
			IdGrupoProduto = 0;
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
		url = "xml/grupo_produto.php?IdGrupoProduto="+IdGrupoProduto;
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
						document.formulario.IdGrupoProduto.value 			= '';
						document.formulario.DescricaoGrupoProduto.value 	= '';
						
						addParmUrl("marGrupoProduto","IdGrupoProduto",'');
						addParmUrl("marSubGrupoProduto","IdGrupoProduto",'');
						addParmUrl("marSubGrupoProdutoNovo","IdGrupoProduto",'');
						
						switch (Local){
							case "GrupoProduto":
								document.formulario.IdGrupoProduto.value 			= '';
								document.formulario.DescricaoGrupoProduto.value 	= '';
								document.formulario.DataCriacao.value 				= '';
								document.formulario.LoginCriacao.value 				= '';
								document.formulario.DataAlteracao.value 			= '';
								document.formulario.LoginAlteracao.value			= '';
								document.formulario.Acao.value 						= 'inserir';
						}
						document.formulario.IdGrupoProduto.focus();
						
						// Fim de Carregando
						carregando(false);
					}else{
						nameNode = xmlhttp.responseXML.getElementsByTagName("IdGrupoProduto")[0]; 
						nameTextNode = nameNode.childNodes[0];
						IdGrupoProduto = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoGrupoProduto")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var DescricaoGrupoProduto = nameTextNode.nodeValue;
						
						addParmUrl("marGrupoProduto","IdGrupoProduto",IdGrupoProduto);
						addParmUrl("marSubGrupoProduto","IdGrupoProduto",IdGrupoProduto);
						addParmUrl("marSubGrupoProdutoNovo","IdGrupoProduto",IdGrupoProduto);
						
						document.formulario.IdGrupoProduto.value		= IdGrupoProduto;
						document.formulario.DescricaoGrupoProduto.value = DescricaoGrupoProduto;
						
						switch (Local){		
							case "GrupoProduto":
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
					if(document.getElementById("quadroBuscaGrupoProduto") != null){
						if(document.getElementById("quadroBuscaGrupoProduto").style.display == "block"){
							document.getElementById("quadroBuscaGrupoProduto").style.display =	"none";
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
	
