	function busca_usuario(Login, Erro, Local){
		if(Login == ''){
			Login = 'NULL';
		}
		if(Local=='' || Local==undefined){
			Local	=	document.formulario.Local.value;
		}
	    
	   	var url = "xml/simular_acesso_usuario.php?Login="+Login;
		
		call_ajax(url, function (xmlhttp) { 				
			if(Erro != false){
				document.formulario.Erro.value = 0;
				verificaErro();
			}	
			if(xmlhttp.responseText == 'false'){						
				switch(Local){
					case 'SimularAcessoUsuario':
						document.getElementById('cp_juridica').style.display	= 'block';
						document.getElementById('cp_fisica').style.display		= 'none';
				
						document.formulario.IdPessoa.value 				= '';
						document.formulario.IdPessoaF.value 			= '';
						document.formulario.Nome.value 					= '';
						document.formulario.NomeF.value 				= '';
						document.formulario.RazaoSocial.value 			= '';
						document.formulario.CPF.value 					= '';
						document.formulario.CNPJ.value 					= '';
						document.formulario.IpAcesso.value				= '';
						document.formulario.DataExpiraSenha.value		= '';
						document.formulario.DataCriacao.value 			= '';
						document.formulario.LoginCriacao.value 			= '';
						document.formulario.DataAlteracao.value 		= '';
						document.formulario.LoginAlteracao.value		= '';						
						document.formulario.IdStatus.value	 			= '';
						document.formulario.LimiteVisualizacao.value	= '';
						
						document.formulario.Login.focus();
						
						if(Login != 'NULL'){
							document.formulario.Login.value		 	= Login;
							document.formulario.IdPessoa.focus();
						}else{
							document.formulario.Login.value		 	= '';
						}
						
						status_inicial();
						
						document.formulario.Acao.value 				= 'simular';
						
						break;												
				}
			}else{						
				var nameNode = xmlhttp.responseXML.getElementsByTagName("Login")[0]; 
				var nameTextNode = nameNode.childNodes[0];
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
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("LimiteVisualizacao")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var LimiteVisualizacao = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("IpAcesso")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var IpAcesso = nameTextNode.nodeValue;		
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("DataExpiraSenha")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var DataExpiraSenha = nameTextNode.nodeValue;			
				
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
				
				switch(Local){
					case 'SimularAcessoUsuario':
						document.formulario.Login.value					= Login+'_'+IdPessoa;
						document.formulario.IpAcesso.value				= IpAcesso;
						document.formulario.DataCriacao.value 			= dateFormat(DataCriacao);
						document.formulario.LoginCriacao.value 			= LoginCriacao;
						document.formulario.DataAlteracao.value		 	= dateFormat(DataAlteracao);
						document.formulario.LoginAlteracao.value		= LoginAlteracao;
						document.formulario.IdStatus.value				= IdStatus;	
						document.formulario.DataExpiraSenha.value		= dateFormat(DataExpiraSenha);
						document.formulario.LimiteVisualizacao.value	= LimiteVisualizacao;
						document.formulario.Acao.value 					= 'simular';
						
						busca_pessoa(IdPessoa,false,Local);
						break;						
				}
			
			}
			if(document.getElementById("quadroBuscaUsuario") != null){
				if(document.getElementById("quadroBuscaUsuario").style.display == "block"){
					document.getElementById("quadroBuscaUsuario").style.display =	"none";
				}
			}
		});
	}