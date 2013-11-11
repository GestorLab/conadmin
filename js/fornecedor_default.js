	function janela_busca_fornecedor(){
		janelas('busca_fornecedor.php',530,350,250,100,'');
	}
	function busca_fornecedor(IdPessoa, Erro, Local){
		if(IdPessoa == ''){
			IdPessoa = 0;
		}
		if(Local=='' || Local==undefined){
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
		url = "xml/fornecedor.php?IdPessoa="+IdPessoa;
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
								document.formulario.Nome.value 				= '';
								document.formulario.DataCriacao.value 		= '';
								document.formulario.LoginCriacao.value 		= '';
								document.formulario.DataAlteracao.value 	= '';
								document.formulario.LoginAlteracao.value	= '';
								document.formulario.Acao.value 				= 'inserir';
								
								busca_pessoa(IdPessoa,'false',Local);
								verificaAcao();
								break;
							case "NotaFiscal":
								document.formulario.IdPessoa.value 			= '';
								document.formulario.Nome.value 				= '';
								document.formulario.RazaoSocial.value 		= '';
								document.formulario.Cidade.value 			= '';
								document.formulario.CPF_CNPJ.value 			= '';
								document.formulario.Email.value 			= '';
								document.formulario.Telefone1.value			= '';
								document.formulario.SiglaEstado.value		= '';
								document.formulario.Endereco.value			= '';
							
								document.formulario.IdPessoa.focus();
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
							case 'NotaFiscal':
								busca_pessoa(IdPessoa,'false',Local);
								break;
							case 'Fornecedor':
								addParmUrl("marFornecedor","IdPessoa",IdPessoa);
								
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
								
								
								document.formulario.DataCriacao.value 			= dateFormat(DataCriacao);
								document.formulario.LoginCriacao.value 			= LoginCriacao;
								document.formulario.DataAlteracao.value 		= dateFormat(DataAlteracao);
								document.formulario.LoginAlteracao.value		= LoginAlteracao;
								document.formulario.Acao.value 					= 'alterar';
								
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
	
