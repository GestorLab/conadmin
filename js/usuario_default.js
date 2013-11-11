	function janela_busca_usuario(){
		janelas('../administrativo/busca_usuario.php',360,283,250,100,'');
	}
	
	function busca_usuario(Login, Erro, Local){
		if(Login == ''){
			Login = 'NULL';
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
	    
	   	url = "../administrativo/xml/usuario.php?Login="+Login;
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
						switch(Local){
							case 'Usuario':
								document.getElementById('cp_juridica').style.display	= 'block';
								document.getElementById('cp_fisica').style.display		= 'none';
						
								document.formulario.IdPessoa.value 			= '';
								document.formulario.Nome.value 				= '';
								document.formulario.RazaoSocial.value 		= '';
								document.formulario.Cidade.value 			= '';
								document.formulario.CPF_CNPJ.value 			= '';
								document.formulario.Email.value 			= '';
								document.formulario.Telefone1.value			= '';
								document.formulario.SiglaEstado.value		= '';
								
								document.getElementById('cp_CPF_CNPJ_Titulo').innerHTML 		= "CNPJ";
								document.formulario.Confirmacao.value		= '';
								
								document.formulario.DataCriacao.value 		= '';
								document.formulario.LoginCriacao.value 		= '';
								document.formulario.DataAlteracao.value 	= '';
								document.formulario.LoginAlteracao.value	= '';
								
								document.formulario.Login.focus();
								
								if(Login != 'NULL'){
									document.formulario.Login.value		 	= Login;
									document.formulario.IdPessoa.focus();
								}else{
									document.formulario.Login.value		 	= '';
								}
								
								status_inicial();
								
								document.formulario.Password.value	 		= '';
								document.formulario.IdStatus.value	 		= '';
								document.formulario.Acao.value 				= 'inserir';
								
								break;
							case 'UsuarioAlterarSenha':
								document.getElementById('cp_juridica').style.display	= 'block';
								document.getElementById('cp_fisica').style.display		= 'none';
						
								document.formulario.IdPessoa.value 			= '';
								document.formulario.Nome.value 				= '';
								document.formulario.RazaoSocial.value 		= '';
								document.formulario.Cidade.value 			= '';
								document.formulario.CPF_CNPJ.value 			= '';
								document.formulario.Email.value 			= '';
								document.formulario.Telefone1.value			= '';
								document.formulario.SiglaEstado.value		= '';
								
								document.getElementById('cp_CPF_CNPJ_Titulo').innerHTML 		= "CNPJ";
								document.formulario.Confirmacao.value		= '';
								
								document.formulario.DataCriacao.value 		= '';
								document.formulario.LoginCriacao.value 		= '';
								document.formulario.DataAlteracao.value 	= '';
								document.formulario.LoginAlteracao.value	= '';
								
								document.formulario.Login.focus();
								
								document.formulario.SenhaAntiga.value		= '';
								document.formulario.NovaSenha.value	 		= '';
								
								document.formulario.Acao.value 				= 'alterar';
								break;
							default:
								document.formulario.Login.value		 		= '';
								document.formulario.NomeUsuario.value	 	= '';
						}
						
						// Fim de Carregando
						carregando(false);
					}else{						
						nameNode = xmlhttp.responseXML.getElementsByTagName("Login")[0]; 
						nameTextNode = nameNode.childNodes[0];
						Login = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("NomeUsuario")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var NomeUsuario = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("IdPessoa")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var IdPessoa = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("IdStatus")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var IdStatus = nameTextNode.nodeValue;
						
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
						
						addParmUrl("marUsuario","Login",Login);
						addParmUrl("marUsuarioPermissao","Login",Login);
						addParmUrl("marUsuarioGrupoPermissao","Login",Login);
						addParmUrl("marUsuarioGrupoUsuario","Login",Login);
						addParmUrl("marUsuarioGrupoUsuarioNovo","Login",Login);
						addParmUrl("marLogAcesso","Login",Login);
								
						switch(Local){
							case 'Usuario':
								document.formulario.Login.value				= Login;
								document.formulario.DataCriacao.value 		= dateFormat(DataCriacao);
								document.formulario.LoginCriacao.value 		= LoginCriacao;
								document.formulario.DataAlteracao.value 	= dateFormat(DataAlteracao);
								document.formulario.LoginAlteracao.value	= LoginAlteracao;
								document.formulario.IdStatus.value			= IdStatus;	
								document.formulario.Password.value	 		= '';
								document.formulario.Confirmacao.value		= '';
								document.formulario.Acao.value 				= 'alterar';
								
								busca_pessoa(IdPessoa,false,Local);
								break;
							case 'UsuarioAlterarSenha':
								busca_pessoa(IdPessoa,false,Local);
								
								document.formulario.Login.value				= Login;
								document.formulario.DataCriacao.value 		= dateFormat(DataCriacao);
								document.formulario.LoginCriacao.value 		= LoginCriacao;
								document.formulario.DataAlteracao.value 	= dateFormat(DataAlteracao);
								document.formulario.LoginAlteracao.value	= LoginAlteracao;
								document.formulario.SenhaAntiga.value		= '';
								document.formulario.NovaSenha.value	 		= '';
								document.formulario.Confirmacao.value		= '';
								document.formulario.Acao.value 				= 'alterar';
								break;
							default:
								document.formulario.Login.value				= Login;
								document.formulario.NomeUsuario.value 		= NomeUsuario;
						}
					
					}
					if(document.getElementById("quadroBuscaUsuario") != null){
						if(document.getElementById("quadroBuscaUsuario").style.display == "block"){
							document.getElementById("quadroBuscaUsuario").style.display =	"none";
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
