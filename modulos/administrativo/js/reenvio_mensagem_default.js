	function busca_reenvio_mensagem(IdHistoricoMensagem,Erro,Local){
		if(IdHistoricoMensagem == '' || IdHistoricoMensagem == undefined){
			IdHistoricoMensagem = 0;
		}
		if(Local == '' || Local == undefined){
			Local	=	document.formulario.Local.value;
		}
		var nameNode, nameTextNode, url;

	   	url = "xml/reenvio_mensagem.php?IdHistoricoMensagem="+IdHistoricoMensagem;
		
		call_ajax(url,function (xmlhttp){
			if(xmlhttp.responseText == 'false'){
				switch(Local){
					case 'EnviarMensagem':
						document.formulario.IdHistoricoMensagem.value			= 	"";
						document.formulario.Destinatario.value							= 	"";
						document.formulario.Destinatario.focus();
						break;
					default:
						document.formulario.IdPessoa.value						=	"";		
						document.filtro.IdPessoa.value							=	"";
						document.formulario.Nome.value							=	"";
						document.formulario.IdHistoricoMensagem.value			= 	"";
						document.filtro.IdHistoricoMensagem.value 				= 	"";
						document.formulario.IdContaReceber.value 				= 	"";
						document.filtro.IdContaReceber.value 					= 	"";
						document.formulario.IdTipoMensagem.value 				= 	"";
						document.formulario.Destinatario.value							= 	"";
						document.formulario.Obs.value							=	"";
						document.formulario.Assunto.value						= 	"";
						document.formulario.DataEnvio.value 					= 	"";
						document.formulario.DataCriacao.value 					= 	"";
						document.formulario.HoraEnvio.value 					= 	"";
						document.formulario.DataLeitura.value 					= 	"";
						document.formulario.HoraLeitura.value 					= 	"";
						document.formulario.IPLeitura.value 					= 	"";
						document.formulario.IdReEnvio.value						= 	"";
						
				
						document.getElementById("QtdTentativaEnvio1").style.display = "none";
						document.getElementById("QtdTentativaEnvio2").style.display = "none";
						document.getElementById("QtdMaxima").style.display = "none";
				
						
						document.getElementById('cp_Status').style.display		= "none";                                                                
						
						parent.location = "cadastro_reenvio_mensagem.php?IdHistoricoMensagem=";
						window.parent.mensagem.location.replace("../../visualizar_mensagem.php");
						document.formulario.IdHistoricoMensagem.focus();
						break;								
				}						
				
				// Fim de Carregando
				carregando(false);
			}else{
				nameNode = xmlhttp.responseXML.getElementsByTagName("IdPessoa")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var IdPessoa = nameTextNode.nodeValue;	
			
				nameNode = xmlhttp.responseXML.getElementsByTagName("Nome")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var Nome = nameTextNode.nodeValue;

				nameNode = xmlhttp.responseXML.getElementsByTagName("Email")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var Email = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("Celular")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var Celular = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("IdHistoricoMensagem")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var IdHistoricoMensagem = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("Mensagem")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var Mensagem = nameTextNode.nodeValue;
				
				switch(Local){
					case 'EnviarMensagem':
						document.formulario.IdHistoricoMensagem.value 	= 	IdHistoricoMensagem;
						document.formulario.Destinatario.value 				= 	Email;
						break;
					default:
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("IdContaReceber")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var IdContaReceber = nameTextNode.nodeValue;					
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("IdTipoMensagem")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var IdTipoMensagem = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("DataEnvio")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var DataHoraEnvio = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("LoginCriacao")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var LoginCriacao = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("DataCriacao")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var DataCriacao = nameTextNode.nodeValue;

						nameNode = xmlhttp.responseXML.getElementsByTagName("DataLeitura")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var DataHoraLeitura = nameTextNode.nodeValue;

						nameNode = xmlhttp.responseXML.getElementsByTagName("IPLeitura")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var IPLeitura = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("IdReEnvio")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var IdReEnvio = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("IdPessoa")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var IdPessoa = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("IdLoja")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var IdLoja = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("IdProcessoFinanceiro")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var IdProcessoFinanceiro = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("IdOrdemServico")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var IdOrdemServico = nameTextNode.nodeValue;

						nameNode = xmlhttp.responseXML.getElementsByTagName("Assunto")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var Assunto = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("Obs")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var Obs = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("QtdTentativaEnvio")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var QtdTentativaEnvio = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("LimiteEnvioDiario")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var LimiteEnvioDiario = nameTextNode.nodeValue;							
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("IdStatus")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var IdStatus = nameTextNode.nodeValue;

						addParmUrl("marPessoa","IdPessoa",IdPessoa);
						addParmUrl("marContrato","IdPessoa",IdPessoa);
						addParmUrl("marContrato","IdContaReceber",IdContaReceber);
						addParmUrl("marContratoNovo","IdPessoa",IdPessoa);
						addParmUrl("marContasReceber","IdPessoa",IdPessoa);
						addParmUrl("marContasReceber","IdContaReceber",IdContaReceber);
						addParmUrl("marLancamentoFinanceiro","IdContaReceber",IdContaReceber);
						addParmUrl("marLancamentoFinanceiro","IdPessoa",IdPessoa);
						addParmUrl("marProcessoFinanceiro","IdProcessoFinanceiro",IdProcessoFinanceiro);
						addParmUrl("marContaEventual","IdContaReceber",IdContaReceber);
						addParmUrl("marContaEventualNovo","IdPessoa",IdPessoa);
						addParmUrl("marOrdemServicoNovo","IdOrdemServico",IdOrdemServico);
						addParmUrl("marOrdemServico","IdOrdemServico",IdOrdemServico);
						
						document.filtro.IdPessoa.value							=	IdPessoa;	
						document.formulario.IdPessoa.value						=	IdPessoa;	
						if(IdPessoa != ''){
							document.formulario.Nome.value						=	Nome;	
						}else{
							document.formulario.Nome.value						=	'';	
						}
						document.formulario.IdHistoricoMensagem.value			= 	IdHistoricoMensagem;
						document.filtro.IdHistoricoMensagem.value 				= 	IdHistoricoMensagem;
						document.formulario.IdContaReceber.value				= 	IdContaReceber;
						document.filtro.IdContaReceber.value 					= 	IdContaReceber;
						document.formulario.IdTipoMensagem.value				= 	IdTipoMensagem;
						if(IdTipoMensagem == 32 || IdTipoMensagem == 29){
							document.formulario.Destinatario.value 						= 	Celular;
						}else{
							document.formulario.Destinatario.value 						= 	Email;
						}
						document.formulario.Obs.value							= 	Obs;
						document.formulario.Mensagem.value						= 	Mensagem;
						document.formulario.Assunto.value						= 	Assunto;
						document.formulario.IdReEnvio.value						= 	IdReEnvio;
						document.formulario.DataEnvio.value						= 	dateFormat(DataHoraEnvio.substring(0,10));
						document.formulario.LoginCriacao.value					= 	LoginCriacao;
						document.formulario.DataCriacao.value					= 	dateFormat(DataCriacao.substring(0,10));
						document.formulario.HoraEnvio.value						= 	DataHoraEnvio.substring(11,16);
						document.formulario.DataLeitura.value					= 	dateFormat(DataHoraLeitura.substring(0,10));
						document.formulario.HoraLeitura.value					= 	DataHoraLeitura.substring(11,16);
						document.formulario.IPLeitura.value						= 	IPLeitura;
						document.formulario.QtdTentativaEnvio.value				= 	QtdTentativaEnvio;
						document.getElementById("QtdMaxima").innerHTML			= 	"QTD. Máxima: "+LimiteEnvioDiario;						
						
						if(IdTipoMensagem == 32 || IdTipoMensagem == 29){
							document.getElementById('destinatario').innerHTML = "Celular";
							//oculta os demais campos desnecessarios para o tipo SMS
							document.getElementById('no_sms_view').style.display = "none";
							document.getElementById('hr').style.display = "none";
							document.getElementById('mensagem_sms').style.display = "block";
							
						}
						
						if(IdStatus == 1){
							document.getElementById("NaoEnviarEmail").style.display = "block";
						}
						if(IdStatus == 3){
							document.getElementById("QtdTentativaEnvio1").style.display = "block";
							document.getElementById("QtdTentativaEnvio2").style.display = "block";
							document.getElementById("QtdMaxima").style.display = "block";
						}
						busca_status(IdHistoricoMensagem); 
						if(IdTipoMensagem != 32 && IdTipoMensagem != 29){
							window.parent.mensagem.location.replace("../../visualizar_mensagem.php?IdLoja="+IdLoja+"&IdHistoricoMensagem="+IdHistoricoMensagem);
						}
						document.formulario.IdHistoricoMensagem.focus();
						break;
				}
			}
		});
	}
	function busca_mensagem_enviada(IdLoja,IdHistoricoMensagem){
		if(IdHistoricoMensagem == ''){
			return false;
		}
		busca_reenvio_mensagem(IdHistoricoMensagem,false);
		window.parent.mensagem.location.replace("../../visualizar_mensagem.php?IdLoja="+IdLoja+"&IdHistoricoMensagem="+IdHistoricoMensagem);
	}
	function reenviar_mensagem(IdHistoricoMensagem){
		parent.location = "cadastro_enviar_mensagem.php?IdHistoricoMensagem="+IdHistoricoMensagem;
	}
	function nao_enviar_email(IdHistoricoMensagem){
		if(IdHistoricoMensagem != ""){
			document.formulario.Acao.value = "alterar";
			document.formulario.submit();
		}
	}
	function cancelarEnvio(IdHistoricoMensagem,IdLoja,Erro){
		var url = "files/editar/editar_enviar_mensagem.php?IdHistoricoMensagem="+IdHistoricoMensagem+"&IdLoja="+IdLoja;				
		carregando(true);
		call_ajax(url, function (xmlhttp){
			if(Erro){
				window.location.reload(true);
			}else{
				document.filtro.submit();
			}
		});

	}