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
	 
   		if(Local == 'UsuarioGrupoUsuario'){
		   	url += "&IdStatus="+2; 
  	 	}
	   //	if(Local == '')
	   	
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
						
								document.formulario.IdPessoa.value 				= '';
								document.formulario.IdPessoaF.value 			= '';
								document.formulario.Nome.value 					= '';
								document.formulario.NomeF.value 				= '';
								document.formulario.RazaoSocial.value 			= '';
								document.formulario.CPF.value 					= '';
								document.formulario.CNPJ.value 					= '';
								document.formulario.Confirmacao.value			= '';
								document.formulario.IpAcesso.value				= '';
								document.formulario.DataExpiraSenha.value		= '';
								document.formulario.IdAcessoSimultaneo.value	= '';
								document.formulario.ForcaAlterarSenha.value		= '';
								document.formulario.DataCriacao.value 			= '';
								document.formulario.LoginCriacao.value 			= '';
								document.formulario.DataAlteracao.value 		= '';
								document.formulario.LoginAlteracao.value		= '';
								document.formulario.Password.value	 			= '';
								document.formulario.IdStatus.value	 			= '';
								document.formulario.LimiteVisualizacao.value	= '';
								
								document.formulario.Login.focus();
								document.formulario.Password.disabled 			= false;
								document.formulario.Confirmacao.disabled		= false;
								
								if(Login != 'NULL'){
									document.formulario.Login.value		 	= Login;
									document.formulario.IdPessoa.focus();
								}else{
									document.formulario.Login.value		 	= '';
								}					
								

								status_inicial();
								grupo_usuario();
								grupo_permissao();//renomear para grupo permissao
								document.getElementById("statusSenha").style.display = "none";
								document.formulario.ForcaAlterarSenha.value = document.formulario.ForcaAlterarSenhaDefault.value;
								document.formulario.Acao.value 				= 'inserir';
								
								break;
							case 'UsuarioAlterarSenha':
								document.getElementById('cp_juridica').style.display	= 'block';
								document.getElementById('cp_fisica').style.display		= 'none';
						
								document.formulario.IdPessoa.value 			= '';
								document.formulario.Nome.value 				= '';
								document.formulario.RazaoSocial.value 		= '';
								document.formulario.CNPJ.value 				= '';
								document.formulario.CPF.value 				= '';
								document.formulario.NomeF.value				= '';
								document.formulario.IdPessoaF.value			= '';
								
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
							case 'UsuarioGrupoUsuario':
								document.formulario.Login.value		 		= '';
								document.formulario.NomeUsuario.value	 	= '';
								
								while(document.formulario.IdGrupoUsuarioPermissao.options.length > 0){
									document.formulario.IdGrupoUsuarioPermissao.options[0] = null;
								}
								while(document.formulario.IdGrupoUsuarioOpcao.options.length > 0){
									document.formulario.IdGrupoUsuarioOpcao.options[0] = null;
								}
								
								document.formulario.Login.focus();
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
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("IdAcessoSimultaneo")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var IdAcessoSimultaneo = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("IdStatus")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var IdStatus = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("LimiteVisualizacao")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var LimiteVisualizacao = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("IpAcesso")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var IpAcesso = nameTextNode.nodeValue;		
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("DataExpiraSenha")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var DataExpiraSenha = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("ForcaAlterarSenha")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var ForcaAlterarSenha = nameTextNode.nodeValue;						
						
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
								
								document.formulario.Login.value					= Login;
								document.formulario.IpAcesso.value				= IpAcesso;
								document.formulario.DataCriacao.value 			= dateFormat(DataCriacao);
								document.formulario.LoginCriacao.value 			= LoginCriacao;
								document.formulario.DataAlteracao.value		 	= dateFormat(DataAlteracao);
								document.formulario.LoginAlteracao.value		= LoginAlteracao;
								document.formulario.IdAcessoSimultaneo.value	= IdAcessoSimultaneo;	
								document.formulario.IdStatus.value				= IdStatus;	
								document.formulario.DataExpiraSenha.value		= dateFormat(DataExpiraSenha);
								document.formulario.ForcaAlterarSenha.value		= ForcaAlterarSenha;
								document.formulario.LimiteVisualizacao.value	= LimiteVisualizacao;	
								document.formulario.Password.value	 			= '';
								document.formulario.Confirmacao.value			= '';
								document.formulario.Acao.value 					= 'alterar';
								
								if(document.formulario.Login.value == "root"){
									document.formulario.Password.disabled 			= true;
									document.formulario.Confirmacao.disabled		= true;
									document.formulario.bt_alterar.disabled			= true;
									document.formulario.bt_excluir.disabled			= true;
									document.formulario.bt_inserir.disabled			= true;
									document.formulario.Acao.value 					= '';
								}else{
									document.formulario.Password.disabled 			= false;
									document.formulario.Confirmacao.disabled		= false;
									document.formulario.bt_alterar.disabled			= false;
								}
								if(document.formulario.LocalLogin.value == Login){
									document.formulario.Password.disabled 			= true;
									document.formulario.Confirmacao.disabled		= true;
								}
								document.getElementById("statusSenha").style.display = "none";
								busca_pessoa(IdPessoa, false, Local);															
								grupo_usuario();//gera lista de grupo se login != ""
								grupo_permissao();//gera lista de grupo se login != ""
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
							case "UsuarioGrupoUsuario":
								document.formulario.Login.value				= Login;
								document.formulario.NomeUsuario.value 		= NomeUsuario;
								
								atualiza_usuario_grupo_usuario(Login);
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
