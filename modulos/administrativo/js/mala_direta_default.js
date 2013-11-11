	function busca_mala_direta(IdMalaDireta, Erro, Local){
		if(IdMalaDireta == '' || IdMalaDireta == undefined){
			IdMalaDireta = 0;
		}
		
		if(Local == '' || Local == undefined){
			Local = document.formulario.Local.value;
		}
		
		var url = "./xml/mala_direta.php?IdMalaDireta="+IdMalaDireta;
		
		call_ajax(url, function (xmlhttp){
			var nameNode, nameTextNode;
			
			if(Erro != false){
				document.formulario.Erro.value = 0;
				verificaErro();
			}
			
			if(xmlhttp.responseText == 'false'){
				switch(Local){
					case 'MalaDireta':
						document.formulario.IdMalaDireta.value				= '';
						document.formulario.IdTipoMensagem.value			= '';
						document.formulario.IdTipoConteudo.value			= '';
						document.formulario.DescricaoMalaDireta.value		= '';
						document.formulario.ListaEmail.value				= '';
						document.formulario.AltAnexoArquivo.value			= '1';
						document.formulario.LogEnvio.value					= '';
						document.formulario.IdStatus.value					= '1';
						document.formulario.IdContaEmail.value				= '';
						document.formulario.DataCriacao.value				= '';
						document.formulario.LoginCriacao.value				= '';
						document.formulario.DataAlteracao.value				= '';
						document.formulario.LoginAlteracao.value			= '';
						document.formulario.LoginProcessamento.value		= '';
						document.formulario.DataProcessamento.value			= '';
						document.formulario.LoginEnvio.value				= '';
						document.formulario.DataEnvio.value					= '';
						document.formulario.Acao.value						= "inserir";
						
						document.getElementById('cp_Status').style.display = "none";
						document.getElementById("bcConteudoEnvio").style.display = "block";
						document.getElementById("cp_tit_filtro").style.display = "block";
						document.getElementById("titModeloMalaDiretaBusca").style.display = "block";
						document.getElementById("cpModeloMalaDiretaBusca").style.display = "block";
						document.getElementById("titTipoMensagem").style.display = "none";
						document.getElementById("cpTipoMensagem").style.display = "none";
						document.getElementById("titModeloMalaDireta").style.margin = "0 0 0 18px";
						
						listar_filtro_pessoa();
						listar_filtro_grupo_pessoa();
						listar_filtro_servico();
						listar_filtro_contrato();
						listar_filtro_status_contrato();
						listar_filtro_processo_financeiro();
						listar_filtro_cidade();
						listar_lista_email(true);
						habilitar_campo(0);
						visualizacao_html();
						verificaAcao();
						
						document.getElementById("titDescricaoMalaDireta").style.color = "#c10000";
						document.formulario.DescricaoMalaDireta.readOnly = false;
						document.formulario.ListaEmail.readOnly = false;
						document.getElementById("titIdTipoConteudo").style.color = "#c10000";
						document.formulario.IdTipoConteudo.disabled = false;
						document.formulario.bt_alterar_arquivo_anexo.disabled = false;
						document.formulario.bt_alterar_mala_direta.disabled = false;
						document.getElementById("titIdContaEmail").style.color = "#c10000";
						document.formulario.IdContaEmail.disabled = false;
						document.getElementById("titTextoAvulso").style.color = "#c10000";
						document.formulario.TextoAvulso.readOnly = false;
						
						document.formulario.DescricaoMalaDireta.onfocus = function (){
							return Foco(this,'in');
						};
						document.formulario.ListaEmail.onfocus = function (){
							return Foco(this,'in');
						};
						document.formulario.TextoAvulso.onfocus = function (){
							return Foco(this,'in');
						};
						
						document.formulario.IdMalaDireta.focus();
						break;
				}
			} else{
				nameNode = xmlhttp.responseXML.getElementsByTagName("IdMalaDireta")[0]; 
				nameTextNode = nameNode.childNodes[0];
				IdMalaDireta = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("IdTipoMensagem")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var IdTipoMensagem = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("IdTipoConteudo")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var IdTipoConteudo = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoMalaDireta")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var DescricaoMalaDireta = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("ListaEmail")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var ListaEmail = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("LogEnvio")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var LogEnvio = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("IdStatus")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var IdStatus = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("Status")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var Status = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("CorStatus")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var CorStatus = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("ExtModelo")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var ExtModelo = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("Filtro_IdPessoa")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var Filtro_IdPessoa = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("Filtro_IdGrupoPessoa")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var Filtro_IdGrupoPessoa = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("Filtro_IdServico")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var Filtro_IdServico = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("Filtro_IdContrato")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var Filtro_IdContrato = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("Filtro_IdStatusContrato")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var Filtro_IdStatusContrato = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("Filtro_IdProcessoFinanceiro")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var Filtro_IdProcessoFinanceiro = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("Filtro_IdPaisEstadoCidade")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var Filtro_IdPaisEstadoCidade = nameTextNode.nodeValue;
				
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
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("LoginProcessamento")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var LoginProcessamento = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("DataProcessamento")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var DataProcessamento = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("LoginEnvio")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var LoginEnvio = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("DataEnvio")[0];
				nameTextNode = nameNode.childNodes[0];
				var DataEnvio = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("IdContaEmail")[0];
				nameTextNode = nameNode.childNodes[0];
				var IdContaEmail = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("Conteudo")[0];
				nameTextNode = nameNode.childNodes[0];
				var Conteudo = nameTextNode.nodeValue;
				
				switch(Local){
					case 'MalaDireta':
						nameNode = xmlhttp.responseXML.getElementsByTagName("CodeHTML")[0];
						nameTextNode = nameNode.childNodes[0];
						var CodeHTML = nameTextNode.nodeValue;
						
						document.formulario.IdMalaDireta.value				= IdMalaDireta;
						document.formulario.IdTipoMensagem.value			= IdTipoMensagem;
						document.formulario.AltAnexoArquivo.value			= '0';
						document.formulario.DescricaoMalaDireta.value		= DescricaoMalaDireta;
						document.formulario.ListaEmail.value				= ListaEmail;
						document.formulario.LogEnvio.value					= LogEnvio;
						document.formulario.IdStatus.value					= IdStatus;
						document.formulario.IdTipoConteudo.value			= IdTipoConteudo;
						document.formulario.IdContaEmail.value				= IdContaEmail;
						document.formulario.LoginCriacao.value				= LoginCriacao;
						document.formulario.DataCriacao.value				= dateFormat(DataCriacao);
						document.formulario.LoginAlteracao.value			= LoginAlteracao;
						document.formulario.DataAlteracao.value				= dateFormat(DataAlteracao);
						document.formulario.LoginProcessamento.value		= LoginProcessamento;
						document.formulario.DataProcessamento.value			= dateFormat(DataProcessamento);
						document.formulario.LoginEnvio.value				= LoginEnvio;
						document.formulario.DataEnvio.value					= dateFormat(DataEnvio);
						document.formulario.Acao.value						= 'alterar';
						
						document.getElementById('cp_Status').innerHTML		= Status;
						document.getElementById('cp_Status').style.display	= 'block';
						document.getElementById('cp_Status').style.color	= CorStatus;
						
						listar_filtro_pessoa(Filtro_IdPessoa);
						listar_filtro_grupo_pessoa(Filtro_IdGrupoPessoa);
						listar_filtro_servico(Filtro_IdServico);
						listar_filtro_contrato(Filtro_IdContrato);
						listar_filtro_status_contrato(Filtro_IdStatusContrato);
						listar_filtro_processo_financeiro(Filtro_IdProcessoFinanceiro);
						listar_filtro_cidade(Filtro_IdPaisEstadoCidade);
						
						addParmUrl("marReenvioMensagem","IdMalaDireta",IdMalaDireta);
						
						if(Filtro_IdPessoa == '' && Filtro_IdGrupoPessoa == '' && Filtro_IdServico == '' && Filtro_IdContrato == '' && Filtro_IdStatusContrato == '' && Filtro_IdProcessoFinanceiro == '' && Filtro_IdPaisEstadoCidade == '' && IdStatus != 1){
							document.getElementById("cp_tit_filtro").style.display = "none";
						} else{
							document.getElementById("cp_tit_filtro").style.display = "block";
						}
						
						if(ExtModelo == ''){
							document.formulario.AltAnexoArquivo.value = '1';
						}
						
						listar_lista_email(true);
						habilitar_campo(IdTipoConteudo);
						visualizacao_html();
						
						switch(IdTipoConteudo){
							case '1':	// ANEXAR ARQUIVO
								document.formulario.bt_visualizar_arquivo_anexo.onclick = function (){
									visualizacao_html(this,CodeHTML);
								};
								document.formulario.bt_alterar_arquivo_anexo.onclick = function (){
									visualizacao_html(document.formulario.bt_visualizar_arquivo_anexo);
									
									document.formulario.AltAnexoArquivo.value = 1;
									document.getElementById("cpButtonArquivoAnexo").style.display = "none";
									document.getElementById("titArquivoAnexo").style.display = "block";
									document.getElementById("cpArquivoAnexo").style.display = "block";
								};
								
								document.getElementById("titModeloMalaDiretaBusca").style.display = "block";
								document.getElementById("cpModeloMalaDiretaBusca").style.display = "block";
								document.getElementById("titTipoMensagem").style.display = "none";
								document.getElementById("cpTipoMensagem").style.display = "none";
								document.getElementById("titModeloMalaDireta").style.margin = "0 0 0 18px";
								break;
							case '2':	// MODELO MALA DIRETA
								document.formulario.bt_visualizar_mala_direta.onclick = function (){
									visualizacao_html(this,CodeHTML);
								};
								
								document.formulario.AltAnexoArquivo.value = '1';
								document.formulario.IdTipoMensagemMalaDiretaEnviada.value = IdTipoMensagem;
								document.getElementById("titModeloMalaDiretaBusca").style.display = "none";
								document.getElementById("cpModeloMalaDiretaBusca").style.display = "none";
								document.getElementById("titTipoMensagem").style.display = "block";
								document.getElementById("cpTipoMensagem").style.display = "block";
								document.getElementById("titModeloMalaDireta").style.margin = "0 10px 0 0";
								break;
							case '3':	// TEXTO AVULSO
								document.formulario.TextoAvulso.value = Conteudo;
								visualizacao_texto_avulso(document.formulario.TextoAvulso.value);
								document.getElementById("titModeloMalaDiretaBusca").style.display = "block";
								document.getElementById("cpModeloMalaDiretaBusca").style.display = "block";
								document.getElementById("titTipoMensagem").style.display = "none";
								document.getElementById("cpTipoMensagem").style.display = "none";
								document.getElementById("titModeloMalaDireta").style.margin = "0 0 0 18px";
						} 
						
						if(IdStatus == 2){
							document.getElementById("titDescricaoMalaDireta").style.color = "#000";
							document.formulario.DescricaoMalaDireta.readOnly = true;
							document.formulario.ListaEmail.readOnly = true;
							document.getElementById("titIdTipoConteudo").style.color = "#000";
							document.formulario.IdTipoConteudo.disabled = true;
							document.formulario.bt_alterar_arquivo_anexo.disabled = true;
							document.formulario.bt_alterar_mala_direta.disabled = true;
							document.getElementById("titIdContaEmail").style.color = "#000";
							document.formulario.IdContaEmail.disabled = true;
							document.getElementById("titTextoAvulso").style.color = "#000";
							document.formulario.TextoAvulso.readOnly = true;
							
							document.formulario.DescricaoMalaDireta.onfocus = function (){};
							document.formulario.ListaEmail.onfocus = function (){};
							document.formulario.TextoAvulso.onfocus = function (){};
						} else{
							document.getElementById("titDescricaoMalaDireta").style.color = "#c10000";
							document.formulario.DescricaoMalaDireta.readOnly = false;
							document.formulario.ListaEmail.readOnly = false;
							document.getElementById("titIdTipoConteudo").style.color = "#c10000";
							document.formulario.IdTipoConteudo.disabled = false;
							document.formulario.bt_alterar_arquivo_anexo.disabled = false;
							document.formulario.bt_alterar_mala_direta.disabled = false;
							document.getElementById("titIdContaEmail").style.color = "#c10000";
							document.formulario.IdContaEmail.disabled = false;
							document.getElementById("titTextoAvulso").style.color = "#c10000";
							document.formulario.TextoAvulso.readOnly = false;
							
							document.formulario.DescricaoMalaDireta.onfocus = function (){
								return Foco(this,'in');
							};
							document.formulario.ListaEmail.onfocus = function (){
								return Foco(this,'in');
							};
							document.formulario.TextoAvulso.onfocus = function (){
								return Foco(this,'in');
							};
						}
						
						document.formulario.IdMalaDireta.focus();
						verificaAcao();
						break;
				}
			}
		});
	}
	function busca_mala_direta_enviada(IdMalaDireta, Erro, Local){
		if(IdMalaDireta == '' || IdMalaDireta == undefined){
			IdMalaDireta = 0;
		}
		
		if(Local == '' || Local == undefined){
			Local = document.formulario.Local.value;
		}
		
		var url = "./xml/mala_direta.php?IdMalaDireta="+IdMalaDireta;
		
		call_ajax(url, function (xmlhttp){
			var nameNode, nameTextNode;
			
			if(Erro != false){
				document.formulario.Erro.value = 0;
				verificaErro();
			}
			
			if(xmlhttp.responseText == 'false'){
				switch(Local){
					case "MalaDireta":
						document.formulario.IdModeloMalaDireta.value				= '';
						document.formulario.DescricaoModeloMalaDireta.value			= '';
						document.formulario.IdTipoMensagemMalaDiretaEnviada.value	= '';
						document.formulario.IdTipoMensagem.value					= '';
						
						document.formulario.IdTipoMensagemMalaDiretaEnviada.focus();
						break;
					default:
						document.formulario.IdModeloMalaDireta.value				= '';
						document.formulario.DescricaoModeloMalaDireta.value			= '';
						document.formulario.IdTipoMensagemMalaDiretaEnviada.value	= '';
						document.formulario.IdTipoMensagem.value					= '';
						
						document.formulario.IdTipoMensagemMalaDiretaEnviada.focus();
						break;
				}
			} else{
				nameNode = xmlhttp.responseXML.getElementsByTagName("IdMalaDireta")[0]; 
				nameTextNode = nameNode.childNodes[0];
				IdMalaDireta = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("IdTipoMensagem")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var IdTipoMensagem = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoMalaDireta")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var DescricaoMalaDireta = nameTextNode.nodeValue;
				
				switch(Local){
					case "MalaDireta":
						if(document.formulario.IdMalaDireta.value != IdMalaDireta){
							document.formulario.IdModeloMalaDireta.value				= IdMalaDireta;
							document.formulario.DescricaoModeloMalaDireta.value			= DescricaoMalaDireta;
							document.formulario.IdTipoMensagemMalaDiretaEnviada.value	= IdTipoMensagem;
							document.formulario.IdTipoMensagem.value					= IdTipoMensagem;
							
							document.formulario.IdTipoMensagemMalaDiretaEnviada.focus();
						} else{
							busca_mala_direta_enviada(0, Erro, Local);
						}
						break;
					default:
						document.formulario.IdModeloMalaDireta.value				= IdMalaDireta;
						document.formulario.DescricaoModeloMalaDireta.value			= DescricaoMalaDireta;
						document.formulario.IdTipoMensagemMalaDiretaEnviada.value	= IdTipoMensagem;
						document.formulario.IdTipoMensagem.value					= IdTipoMensagem;
						
						document.formulario.IdTipoMensagemMalaDiretaEnviada.focus();
						break;
				}
			}
			
			if(document.getElementById("quadroBuscaMalaDiretaEnviada") != null){
				if(document.getElementById("quadroBuscaMalaDiretaEnviada").style.display == "block"){
					document.getElementById("quadroBuscaMalaDiretaEnviada").style.display =	"none";
				}
			}
		});
	}