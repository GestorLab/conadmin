	function busca_tipo_mensagem(IdTipoMensagem, Erro, Local){
		if(IdTipoMensagem == '' || IdTipoMensagem == undefined){
			IdTipoMensagem = 0;
		}
		
		if(Local == '' || Local == undefined){
			Local = document.formulario.Local.value;
		}
		
		var url = "./xml/tipo_mensagem.php?IdTipoMensagem="+IdTipoMensagem;
	
		call_ajax(url,function (xmlhttp){
			if(Erro != false){
				document.formulario.Erro.value = 0;
				//displayCampo('');			
				//verificaErro();
			}
			
			if(xmlhttp.responseText == "false"){
				switch(Local){
					case "TipoMensagem":
						document.formulario.IdTipoMensagem.value	= "";
						document.formulario.IdTemplate.value		= "";
						document.formulario.Delay.value				= "";
						document.formulario.IdContaEmail.value		= "";
						document.formulario.LimiteEnvioDiario.value	= "";
						document.formulario.Titulo.value			= "";
						document.formulario.Assunto.value			= "";
						document.formulario.Conteudo.value			= "";
						document.formulario.Assinatura.value		= "";
						document.formulario.IdStatus.value			= "";
						document.formulario.DataAlteracao.value		= "";
						document.formulario.LoginAlteracao.value	= "";
						document.formulario.Acao.value				= "inserir";
						
						verificaAcao();
						
						addParmUrl("marReenvioMensagem","IdTipoMensagem","");
						addParmUrl("marTipoMensagemParametro","IdTipoMensagem","");
						addParmUrl("marTipoMensagemParametroNovo","IdTipoMensagem","");
						
						atualizar_formulario_IdContaEmail(0);
						document.formulario.IdTipoMensagem.focus();
						break;
					case "TipoMensagemParametro":
						document.formulario.IdTipoMensagem.value 			= '';
						document.formulario.DescricaoTipoMensagem.value		= '';
						
						busca_tipo_mensagem_parametro_layout("",false);
						document.formulario.IdTipoMensagem.focus();
						break;
				}
			} else{
				nameNode = xmlhttp.responseXML.getElementsByTagName("IdTipoMensagem")[0]; 
				nameTextNode = nameNode.childNodes[0];
				IdTipoMensagem = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("IdTemplate")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var IdTemplate = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("LimiteEnvioDiario")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var LimiteEnvioDiario = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("IdContaEmail")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var IdContaEmail = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("IdContaSMS")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var IdContaSMS = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("Titulo")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var Titulo = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("Assunto")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var Assunto = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("Conteudo")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var Conteudo = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("Assinatura")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var Assinatura = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("IdStatus")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var IdStatus = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("DataAlteracao")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var DataAlteracao = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("DelayDisparo")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var DelayDisparo = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("LoginAlteracao")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var LoginAlteracao = nameTextNode.nodeValue;
				
				switch(Local){
					case "TipoMensagem":
						document.formulario.IdTipoMensagem.value	= IdTipoMensagem;
						document.formulario.IdTemplate.value		= IdTemplate;
						document.formulario.IdContaEmail.value		= IdContaEmail;
						document.formulario.LimiteEnvioDiario.value	= LimiteEnvioDiario;
						document.formulario.Titulo.value			= Titulo;
						document.formulario.Assunto.value			= Assunto;
						document.formulario.Conteudo.value			= Conteudo;
						document.formulario.Assinatura.value		= Assinatura;
						document.formulario.IdStatus.value			= IdStatus;
						document.formulario.Delay.value				= DelayDisparo;
						document.formulario.DataAlteracao.value		= dateFormat(DataAlteracao);
						document.formulario.LoginAlteracao.value	= LoginAlteracao;
						document.formulario.Acao.value				= "alterar";
						
						if(IdTemplate == 4){
							displayCampo(4);
						}else{
							displayCampo();
						}
						verificaAcao();
						addParmUrl("marReenvioMensagem","IdTipoMensagem",IdTipoMensagem);
						addParmUrl("marTipoMensagemParametro","IdTipoMensagem",IdTipoMensagem);
						addParmUrl("marTipoMensagemParametroNovo","IdTipoMensagem",IdTipoMensagem);
						
						atualizar_formulario_IdContaEmail(IdTemplate,IdContaEmail);
						document.formulario.IdTipoMensagem.focus();
						break;
					case 'TipoMensagemParametro':

						document.formulario.IdTipoMensagem.value				= IdTipoMensagem;
						document.formulario.DescricaoTipoMensagem.value	 		= Titulo;

						busca_tipo_mensagem_parametro_layout(IdTipoMensagem,false);
						break;
				}
				if(document.getElementById("quadroBuscaTipoMensagem") != null){
					if(document.getElementById("quadroBuscaTipoMensagem").style.display == "block"){
						document.getElementById("quadroBuscaTipoMensagem").style.display =	"none";
					}
				}
			}
		});
	}