	function busca_protocolo(IdProtocolo, Erro, Local){
		if(IdProtocolo == '' || IdProtocolo == undefined){
			IdProtocolo = 0;
		}
		
		if(Local == '' || Local == undefined){
			Local = document.formulario.Local.value;
		}
		
		var url = "./xml/protocolo.php?IdProtocolo="+IdProtocolo;
		
		call_ajax(url, function (xmlhttp){
			if(Erro){
				document.formulario.Erro.value = 0;
				verificaErro();
			}
			
			if(xmlhttp.responseText == "false"){
				switch(Local){
					case "Protocolo":
						document.formulario.IdProtocolo.value				= '';
						document.formulario.LocalAbertura.value				= '';
						document.formulario.Assunto.value					= '';
						document.formulario.IdGrupoUsuarioAtendimento.value	= '';
						document.formulario.Data.value						= '';
						document.formulario.Hora.value						= '';
						document.formulario.LoginCriacao.value				= '';
						document.formulario.DataCriacao.value				= '';
						document.formulario.LoginAlteracao.value			= '';
						document.formulario.DataAlteracao.value				= '';
						document.formulario.LoginConclusao.value			= '';
						document.formulario.DataConclusao.value				= '';
						document.formulario.PessoaTodosDados.value			= 1;
						document.formulario.Concluir.value					= 0;
						document.formulario.Acao.value						= "inserir";
						
						document.getElementById("cpStatusProtocolo").style.color = '';
						document.getElementById("cpStatusProtocolo").innerHTML = '';
						document.getElementById("titData").style.color = "#000";
						document.getElementById("titData").style.backgroundColor='#fff';
						document.getElementById('titHora').style.color = "#000";
						document.getElementById('titHora').style.backgroundColor='#fff';
						
						buscar_protocolo_tipo();
						busca_pessoa(0,false,Local);
						busca_contrato(0,false,Local);
						busca_conta_eventual(0,false,Local);
						busca_conta_receber(0,false,Local);
						busca_ordem_servico(0);
						busca_login_usuario(document.formulario.IdGrupoUsuarioAtendimento.value,document.formulario.LoginAtendimento);
						protocolo_visualizar_historico(false,false);
						verificaAcao();
						
						document.formulario.IdProtocolo.focus();
						break;
				}
			} else{
				var nameNode = xmlhttp.responseXML.getElementsByTagName("IdProtocolo")[0]; 
				var nameTextNode = nameNode.childNodes[0];
				IdProtocolo = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("LocalAbertura")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var LocalAbertura = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("IdProtocoloTipo")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var IdProtocoloTipo = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("IdPessoa")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var IdPessoa = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("Nome")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var Nome = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("CPF_CNPJ")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var CPF_CNPJ = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("Email")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var Email = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("Telefone1")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var Telefone1 = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("Telefone2")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var Telefone2 = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("Telefone3")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var Telefone3 = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("Celular")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var Celular = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("IdContrato")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var IdContrato = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("IdContaEventual")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var IdContaEventual = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("IdContaReceber")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var IdContaReceber = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("IdOrdemServico")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var IdOrdemServico = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("Assunto")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var Assunto = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("IdGrupoUsuario")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var IdGrupoUsuario = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("LoginResponsavel")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var LoginResponsavel = nameTextNode.nodeValue;
				
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
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("LoginConclusao")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var LoginConclusao = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("DataConclusao")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var DataConclusao = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("TipoPessoa")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var TipoPessoa = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("IdStatus")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var IdStatus = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("Status")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var Status = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("CorStatus")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var CorStatus = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("Data")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var Data = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("Hora")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var Hora = nameTextNode.nodeValue;
				
				switch(Local){
					case "Protocolo":
						document.formulario.IdProtocolo.value				= IdProtocolo;
						document.formulario.LocalAbertura.value				= LocalAbertura;
						document.formulario.Telefone1.value 				= Telefone1;
						document.formulario.Telefone2.value 				= Telefone2;
						document.formulario.Telefone3.value 				= Telefone3;
						document.formulario.Celular.value 					= Celular;
						document.formulario.Assunto.value					= Assunto;
						document.formulario.IdGrupoUsuarioAtendimento.value	= IdGrupoUsuario;
						document.formulario.Data.value						= Data;
						document.formulario.Hora.value						= Hora;
						document.formulario.LoginCriacao.value				= LoginCriacao;
						document.formulario.DataCriacao.value				= dateFormat(DataCriacao);
						document.formulario.LoginAlteracao.value			= LoginAlteracao;
						document.formulario.DataAlteracao.value				= dateFormat(DataAlteracao);
						document.formulario.LoginConclusao.value			= LoginConclusao;
						document.formulario.DataConclusao.value				= dateFormat(DataConclusao);
						document.formulario.Concluir.value					= 0;
						document.formulario.Acao.value						= "alterar";
						
						if(IdProtocoloTipo != ''){
							document.formulario.PessoaTodosDados.value = 1;
						} else{
							document.formulario.PessoaTodosDados.value = 0;
						}
						
						document.getElementById("cpStatusProtocolo").style.color = CorStatus;
						document.getElementById("cpStatusProtocolo").innerHTML = Status;
						
						if(TipoPessoa == 2){
							document.formulario.NomeF.value	= Nome;
							document.formulario.CPF.value	= CPF_CNPJ;
							document.formulario.Email.value	= Email;
						} else{
							document.formulario.Nome.value			= Nome;
							document.formulario.CNPJ.value			= CPF_CNPJ;
							document.formulario.EmailJuridica.value	= Email;
						}
						
						if(Hora != ''){
							document.getElementById("titData").style.color = "#c10000";
							document.getElementById("titData").style.backgroundColor='#fff';
							document.getElementById('titHora').style.color = "#c10000";
							document.getElementById('titHora').style.backgroundColor='#fff';
						} else {
							document.getElementById("titData").style.color = "#000";
							document.getElementById("titData").style.backgroundColor='#fff';
							document.getElementById('titHora').style.color = "#000";
							document.getElementById('titHora').style.backgroundColor='#fff';
						}
						
						buscar_protocolo_tipo(IdProtocoloTipo);
						busca_pessoa(IdPessoa,false,Local);
						busca_contrato(IdContrato,false,Local);
						busca_conta_eventual(IdContaEventual,false,Local);
						busca_ordem_servico(IdOrdemServico,false,Local);
						if(IdContaReceber != ""){
							busca_conta_receber(IdContaReceber,false,Local);
						}
						busca_login_usuario(IdGrupoUsuario,document.formulario.LoginAtendimento,LoginResponsavel);
						protocolo_visualizar_historico(true,false);
						verificaAcao();
						
						document.formulario.IdProtocolo.focus();
						break;
				}
			}
		});
	}