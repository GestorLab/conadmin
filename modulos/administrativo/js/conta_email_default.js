	function busca_conta_email(IdContaEmail, Erro, Local,Login){
		if(IdContaEmail == '' || IdContaEmail == undefined){
			IdContaEmail = 0;
		}
		if(Local == '' || Local == undefined){
			Local = document.formulario.Local.value;
		}
		
		var url = "./xml/conta_email.php?IdContaEmail="+IdContaEmail+"&Login="+Login;
		call_ajax(url, function (xmlhttp){
			if(Erro != false){
				document.formulario.Erro.value = 0;
				verificaErro();
			}
			
			if(xmlhttp.responseText == "false"){
				switch(Local){
					case "ContaEmail":
						document.formulario.IdContaEmail.value			= '';
						document.formulario.DescricaoContaEmail.value	= '';
						document.formulario.NomeRemetente.value			= '';
						document.formulario.EmailRemetente.value		= '';
						document.formulario.NomeResposta.value			= '';
						document.formulario.EmailResposta.value			= '';
						document.formulario.Usuario.value				= '';
						document.formulario.Senha.value					= '';
						document.formulario.ServidorSMTP.value			= '';
						document.formulario.Porta.value					= '';
						document.formulario.RequerAutenticacao.value	= '';
						document.formulario.SMTPSeguro.value			= '';
						document.formulario.IntervaloEnvio.value		= '';
						document.formulario.QtdTentativaEnvio.value		= '';
						document.formulario.LoginCriacao.value			= '';
						document.formulario.DataCriacao.value			= '';
						document.formulario.LoginAlteracao.value		= '';
						document.formulario.DataAlteracao.value			= '';
						document.formulario.LimiteEnvioDiario.value		= '';
						document.formulario.Acao.value					= "inserir";
						
						verificaAcao();
						
						document.formulario.IdContaEmail.focus();
						break;
				}
			} else{
				var nameNode = xmlhttp.responseXML.getElementsByTagName("IdContaEmail")[0]; 
				var nameTextNode = nameNode.childNodes[0];
				IdContaEmail = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoContaEmail")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var DescricaoContaEmail = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("NomeRemetente")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var NomeRemetente = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("EmailRemetente")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var EmailRemetente = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("NomeResposta")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var NomeResposta = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("EmailResposta")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var EmailResposta = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("Usuario")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var Usuario = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("Senha")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var Senha = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("ServidorSMTP")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var ServidorSMTP = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("Porta")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var Porta = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("RequerAutenticacao")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var RequerAutenticacao = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("SMTPSeguro")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var SMTPSeguro = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("IntervaloEnvio")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var IntervaloEnvio = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("QtdTentativaEnvio")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var QtdTentativaEnvio = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("LoginCriacao")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var LoginCriacao = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("DataCriacao")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var DataCriacao = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("LoginAlteracao")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var LoginAlteracao = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("DataAlteracao")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var DataAlteracao = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("LimiteEnvioDiario")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var LimiteEnvioDiario = nameTextNode.nodeValue;
				
				switch(Local){
					case "ContaEmail":
						document.formulario.IdContaEmail.value			= IdContaEmail;
						document.formulario.DescricaoContaEmail.value	= DescricaoContaEmail;
						document.formulario.NomeRemetente.value			= NomeRemetente;
						document.formulario.EmailRemetente.value		= EmailRemetente;
						document.formulario.NomeResposta.value			= NomeResposta;
						document.formulario.EmailResposta.value			= EmailResposta;
						document.formulario.Usuario.value				= Usuario;
						document.formulario.Senha.value					= Senha;
						document.formulario.ServidorSMTP.value			= ServidorSMTP;
						document.formulario.Porta.value					= Porta;
						document.formulario.RequerAutenticacao.value	= RequerAutenticacao;
						document.formulario.SMTPSeguro.value			= SMTPSeguro;
						document.formulario.IntervaloEnvio.value		= IntervaloEnvio;
						document.formulario.QtdTentativaEnvio.value		= QtdTentativaEnvio;
						document.formulario.LoginCriacao.value			= LoginCriacao;
						document.formulario.DataCriacao.value			= dateFormat(DataCriacao);
						document.formulario.LoginAlteracao.value		= LoginAlteracao;
						document.formulario.DataAlteracao.value			= dateFormat(DataAlteracao);
						document.formulario.LimiteEnvioDiario.value		= LimiteEnvioDiario;
						document.formulario.Acao.value					= "alterar";
						
						verificaAcao();
						
						document.formulario.IdContaEmail.focus();
						break;
				}
			}
		});
	}