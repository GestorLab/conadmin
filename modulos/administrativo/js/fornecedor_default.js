	function janela_busca_fornecedor(){
		janelas('busca_fornecedor.php',530,350,250,100,'');
	}
	function busca_fornecedor(IdPessoa, Erro, Local, CPF_CNPJ){
		if(IdPessoa == '' && CPF_CNPJ == ''){
			IdPessoa = 0;
		}
		if(Local=='' || Local==undefined){
			Local	=	document.formulario.Local.value;
		}
		if(CPF_CNPJ== undefined){
			CPF_CNPJ =	"";
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
		url = "xml/fornecedor.php?IdPessoa="+IdPessoa+"&CPF_CNPJ="+CPF_CNPJ;
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
						switch (Local){
							case "Fornecedor":
								addParmUrl("marFornecedor","IdPessoa","");

								document.formulario.IdPessoa.value 			= '';
								document.formulario.CPF_CNPJ.value			= '';
								document.formulario.Nome.value 				= '';
								document.formulario.DataCriacao.value 		= '';
								document.formulario.LoginCriacao.value 		= '';
								document.formulario.DataAlteracao.value 	= '';
								document.formulario.LoginAlteracao.value	= '';
								document.formulario.Acao.value 				= 'inserir';
								
								busca_pessoa(IdPessoa,'false',Local);
								verificaAcao();
								break;
							case "NotaFiscalEntrada":
								document.formulario.IdPessoa.value 				= '';
								document.formulario.Nome.value 					= '';
								document.formulario.RazaoSocial.value 			= '';
								document.formulario.CPF_CNPJFornecedor.value 	= '';
								document.formulario.CPF_CNPJ.value 				= '';
								
								if(CPF_CNPJ!=""){
									document.formulario.CPF_CNPJ.focus();
								}else{
									document.formulario.IdPessoa.focus();
								}
								verificaAcao();
								break;
							default:
								document.formulario.IdPessoa.value 			= '';
								document.formulario.Nome.value 				= '';
								
								document.formulario.IdPessoa.focus();
						}
						
						// Fim de Carregando
						carregando(false);
					}else{
					
						nameNode = xmlhttp.responseXML.getElementsByTagName("IdPessoa")[0]; 
						nameTextNode = nameNode.childNodes[0];
						IdPessoa = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("Nome")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var Nome = nameTextNode.nodeValue;
						
						document.formulario.IdPessoa.value 				= IdPessoa;
						document.formulario.Nome.value 					= Nome;
						
						switch (Local){	
							case 'NotaFiscalEntrada':							
								nameNode = xmlhttp.responseXML.getElementsByTagName("RazaoSocial")[0]; 
								nameTextNode = nameNode.childNodes[0];
								var RazaoSocial = nameTextNode.nodeValue;
								
								nameNode = xmlhttp.responseXML.getElementsByTagName("TipoPessoa")[0]; 
								nameTextNode = nameNode.childNodes[0];
								var TipoPessoa = nameTextNode.nodeValue;
								
								nameNode = xmlhttp.responseXML.getElementsByTagName("CPF_CNPJ")[0]; 
								nameTextNode = nameNode.childNodes[0];
								var CPF_CNPJ = nameTextNode.nodeValue;
								
								if(TipoPessoa == 2){
									document.getElementById("cp_RazaoSocial_Titulo").innerHTML 	= "Nome";
									document.getElementById("cp_CNPJ").innerHTML 				= 'CPF';	
								}else{
									document.getElementById("cp_RazaoSocial_Titulo").innerHTML 	= "Nome Fantasia";
									document.getElementById("cp_CNPJ").innerHTML 				= 'CNPJ';	
								}
								
								document.formulario.RazaoSocial.value 			= RazaoSocial;
								document.formulario.CPF_CNPJ.value 				= CPF_CNPJ;
								document.formulario.CPF_CNPJFornecedor.value	= CPF_CNPJ;
								break;
							case 'Fornecedor':							
								addParmUrl("marFornecedor","IdPessoa",IdPessoa);
								
								nameNode = xmlhttp.responseXML.getElementsByTagName("CPF_CNPJ")[0]; 
								nameTextNode = nameNode.childNodes[0];
								var CPF_CNPJ = nameTextNode.nodeValue;
								
								nameNode = xmlhttp.responseXML.getElementsByTagName("TipoPessoa")[0]; 
								nameTextNode = nameNode.childNodes[0];
								var TipoPessoa = nameTextNode.nodeValue;
								
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
								
								document.formulario.CPF_CNPJ.value				= CPF_CNPJ;
								document.formulario.DataCriacao.value 			= dateFormat(DataCriacao);
								document.formulario.LoginCriacao.value 			= LoginCriacao;
								document.formulario.DataAlteracao.value 		= dateFormat(DataAlteracao);
								document.formulario.LoginAlteracao.value		= LoginAlteracao;
								document.formulario.Acao.value 					= 'alterar';
								
								if(TipoPessoa == 2){								
									document.getElementById("cp_CPF_CNPJ").innerHTML 	= 'CPF';	
								}else{
									document.getElementById("cp_CPF_CNPJ").innerHTML 	= 'CNPJ';	
								}
								
								verificaAcao();
								break;
						}
					}
					if(document.getElementById("quadroBuscaPessoa") != null){
						if(document.getElementById("quadroBuscaPessoa").style.display == "block"){
							document.getElementById("quadroBuscaPessoa").style.display =	"none";
						}
					}
					if(document.getElementById("quadroBuscaFornecedor") != null){
						if(document.getElementById("quadroBuscaFornecedor").style.display	==	"block"){
							document.getElementById("quadroBuscaFornecedor").style.display = "none";
						}
					}
				}
				// Fim de Carregando
				carregando(false);
			} 
			return true;
		}
		xmlhttp.send(null);
	}	
	
