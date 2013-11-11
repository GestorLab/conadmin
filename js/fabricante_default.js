	function janela_busca_fabricante(){
		janelas('busca_fabricante.php',360,283,250,100,'');
	}
	function busca_fabricante(IdFabricante, Erro, Local){
		if(IdFabricante == ''){
			IdFabricante = 0;
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
		url = "xml/fabricante.php?IdFabricante="+IdFabricante;
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
						
						document.formulario.IdFabricante.value 			= '';
						document.formulario.DescricaoFabricante.value 	= '';
						
						addParmUrl("marFabricante","IdFabricante",'');
						addParmUrl("marProduto","IdFabricante",'');
						addParmUrl("marProdutoNovo","IdFabricante",'');
						
						switch (Local){
							case "Fabricante":
								document.formulario.DataCriacao.value 			= '';
								document.formulario.LoginCriacao.value 			= '';
								document.formulario.DataAlteracao.value 		= '';
								document.formulario.LoginAlteracao.value		= '';
								document.formulario.Acao.value 					= 'inserir';
								break;
						}
						
						document.formulario.IdFabricante.focus();
						
						// Fim de Carregando
						carregando(false);
					}else{
						nameNode = xmlhttp.responseXML.getElementsByTagName("IdFabricante")[0]; 
						nameTextNode = nameNode.childNodes[0];
						IdFabricante = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoFabricante")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var DescricaoFabricante = nameTextNode.nodeValue;
												
						document.formulario.IdFabricante.value		  = IdFabricante;
						document.formulario.DescricaoFabricante.value = DescricaoFabricante;
						
						addParmUrl("marFabricante","IdFabricante",IdFabricante);
						addParmUrl("marProduto","IdFabricante",IdFabricante);
						addParmUrl("marProdutoNovo","IdFabricante",IdFabricante);
						
						switch (Local){		
							case "Fabricante":
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
					if(document.getElementById("quadroBuscaFabricante") != null){
						if(document.getElementById("quadroBuscaFabricante").style.display == "block"){
							document.getElementById("quadroBuscaFabricante").style.display =	"none";
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
	
