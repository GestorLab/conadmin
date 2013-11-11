	function busca_servico_parametro(IdServico,Erro,Local,IdParametroServicoCond){
		if(IdServico == '' || IdServico == undefined){
			IdServico = 0;
		}
		
		if(IdParametroServicoCond == '' || IdParametroServicoCond == undefined){
			IdParametroServicoCond = 0;
		} else{
			var tam = document.getElementById('tabelaParametro').rows.length;	
			
			for(var i = 0; i < tam; i++){
				if(document.getElementById('tabelaParametro').rows[i].accessKey == IdParametroServicoCond){
					document.getElementById('tabelaParametro').rows[i].style.backgroundColor = "#A0C4EA";
				} else{
					if((i%2) == 0 && i != 0 && i != (tam-1)){
						document.getElementById('tabelaParametro').rows[i].style.backgroundColor = "#E2E7ED";
					} else if((i%2) != 0 && i != 0 && i != (tam-1)){
						document.getElementById('tabelaParametro').rows[i].style.backgroundColor = "#FFFFFF";
					}
				}
			}
		}
		
		var url = "xml/servico_parametro.php?IdServico="+IdServico+"&IdParametroServico="+IdParametroServicoCond;
		
		call_ajax(url,function (xmlhttp) {
			if(Erro != false){
				document.formulario.Erro.value = 0;
				verificaErro();
			}
			
			if(xmlhttp.responseText == 'false'){
				document.formulario.IdParametroServico.value					= "";
				document.formulario.IdTipoAcesso.value							= "";
				document.formulario.SalvarHistorico.value						= "";
				document.formulario.DescricaoParametroServico.value				= "";
				document.formulario.Obrigatorio_Texto[0].selected				= true;
				document.formulario.ObrigatorioStatus_Texto.value				= "";
				document.formulario.Calculavel_Texto[0].selected 				= true;
				document.formulario.Obrigatorio_Selecao[0].selected				= true;
				document.formulario.BotaoAuxiliar_Texto.value					= "";
				document.formulario.ObrigatorioStatus_Selecao.value				= "";
				document.formulario.Calculavel_Selecao[0].selected 				= true;
				document.formulario.CalculavelOpcoes[0].selected 				= true;
				document.formulario.Visivel_Texto[0].selected 					= true;
				document.formulario.VisivelOS_Texto[0].selected 				= true;
				document.formulario.VisivelCDA_Texto[0].selected 				= true;
				document.formulario.AcessoCDA_Texto[0].selected 				= true;
				document.formulario.ParametroDemonstrativo_Texto[0].selected 	= true;
				document.formulario.Visivel_Selecao[0].selected 				= true;
				document.formulario.VisivelOS_Selecao[0].selected 				= true;
				document.formulario.VisivelCDA_Selecao[0].selected 				= true;
				document.formulario.AcessoCDA_Selecao[0].selected 				= true;
				document.formulario.ParametroDemonstrativo_Selecao[0].selected 	= true;
				document.formulario.IdTipoTexto[0].selected 					= true;
				document.formulario.Obs.value									= "";
				document.formulario.IdStatusParametro[0].selected 				= true;
				document.formulario.IdMascaraCampo[0].selected 					= true;
				document.formulario.ValorDefaultInput.value						= "";
				document.formulario.IdGrupoUsuarioInput.value					= "";
				document.formulario.IdGrupoUsuarioSelect.value					= "";
				document.formulario.DescricaoGrupoUsuarioInput.value			= "";
				document.formulario.DescricaoGrupoUsuarioSelect.value			= "";
				document.formulario.DescricaoParametroServicoCDA.value			= "";
				document.formulario.TamMinimo.value								= "";
				document.formulario.TamMaximo.value								= "";
				document.formulario.RotinaCalculo.value							= "";	
				document.formulario.RotinaOpcoes.value							= "";	
				document.formulario.RotinaOpcoesContrato.value					= "";
				document.formulario.OpcaoValor.value							= "";	
				document.formulario.IdTipoParametro.value						= "";	
				document.formulario.RotinaCalculo.value							= "";				
				document.formulario.DataCriacao.value							= "";
				document.formulario.LoginCriacao.value							= "";
				document.formulario.DataAlteracao.value							= "";
				document.formulario.LoginAlteracao.value						= "";
				document.formulario.Obrigatorio_Texto.disabled					= false;
				document.formulario.Obrigatorio_Selecao.disabled				= false;
				document.formulario.IdMascaraCampo.disabled						= false;
				document.formulario.Acao.value									= "inserir";
				
				while(document.formulario.ValorDefaultSelect.options.length > 0){
					document.formulario.ValorDefaultSelect.options[0] = null;
				}
				
				while(document.formulario.IdMascaraCampo.options.length > 0){
					document.formulario.IdMascaraCampo.options[0] = null;
				}
				
				addParmUrl("marServicoParametroNovo","IdParametroServico","");
				addOption(document.formulario.IdMascaraCampo,"","");
				verificaTipoParametro();
				status_inicial();
				verificaErro();
				busca_grupos_usuario();
				atualizaTipoTexto();
				
				document.getElementById('tabelahelpText2').style.display		= 'none';
				document.getElementById('cpRotinaCalculo').style.display		= 'none';
				document.getElementById('cpRotinaOpcoes').style.display			= 'none';
				document.getElementById('cpRotinaOpcoesContrato').style.display	= 'none';
				document.getElementById('cpDescricaoCDA').style.display			= 'none';
				document.getElementById('tableCampoTexto').style.display		= 'none';
				
				document.formulario.IdParametroServico.focus();
			} else{
				for(var i = 0; i<xmlhttp.responseXML.getElementsByTagName("IdParametroServico").length; i++){
					var nameNode = xmlhttp.responseXML.getElementsByTagName("IdParametroServico")[i]; 
					var nameTextNode = nameNode.childNodes[0];
					var IdParametroServico = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("IdTipoAcesso")[i]; 
					nameTextNode = nameNode.childNodes[0];
					var IdTipoAcesso = nameTextNode.nodeValue;	
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("SalvarHistorico")[i]; 
					nameTextNode = nameNode.childNodes[0];
					var SalvarHistorico = nameTextNode.nodeValue;	
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoParametroServico")[i]; 
					nameTextNode = nameNode.childNodes[0];
					var DescricaoParametroServico = nameTextNode.nodeValue;	
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("Obrigatorio")[i]; 
					nameTextNode = nameNode.childNodes[0];
					var Obrigatorio = nameTextNode.nodeValue;					
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("Editavel")[i]; 
					nameTextNode = nameNode.childNodes[0];
					var Editavel = nameTextNode.nodeValue;	
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("BotaoAuxiliar")[i]; 
					nameTextNode = nameNode.childNodes[0];
					var BotaoAuxiliar = nameTextNode.nodeValue;					
			
					nameNode = xmlhttp.responseXML.getElementsByTagName("ValorDefault")[i]; 
					nameTextNode = nameNode.childNodes[0];
					var ValorDefault = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("IdGrupoUsuario")[i]; 
					nameTextNode = nameNode.childNodes[0];
					var IdGrupoUsuario = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("Obs")[i]; 
					nameTextNode = nameNode.childNodes[0];
					var Obs = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("Calculavel")[i]; 
					nameTextNode = nameNode.childNodes[0];
					var Calculavel = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("CalculavelOpcoes")[i]; 
					nameTextNode = nameNode.childNodes[0];
					var CalculavelOpcoes = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("RotinaCalculo")[i]; 
					nameTextNode = nameNode.childNodes[0];
					var RotinaCalculo = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("RotinaOpcoes")[i]; 
					nameTextNode = nameNode.childNodes[0];
					var RotinaOpcoes = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("RotinaOpcoesContrato")[i]; 
					nameTextNode = nameNode.childNodes[0];
					var RotinaOpcoesContrato = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("IdStatusParametro")[i]; 
					nameTextNode = nameNode.childNodes[0];
					var IdStatusParametro = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("ParametroDemonstrativo")[i]; 
					nameTextNode = nameNode.childNodes[0];
					var ParametroDemonstrativo = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("Visivel")[i]; 
					nameTextNode = nameNode.childNodes[0];
					var Visivel = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("VisivelOS")[i]; 
					nameTextNode = nameNode.childNodes[0];
					var VisivelOS = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("VisivelCDA")[i]; 
					nameTextNode = nameNode.childNodes[0];
					var VisivelCDA = nameTextNode.nodeValue
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("AcessoCDA")[i]; 
					nameTextNode = nameNode.childNodes[0];
					var AcessoCDA = nameTextNode.nodeValue
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("Unico")[i]; 
					nameTextNode = nameNode.childNodes[0];
					var Unico = nameTextNode.nodeValue
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("IdTipoParametro")[i]; 
					nameTextNode = nameNode.childNodes[0];
					var IdTipoParametro = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("IdMascaraCampo")[i]; 
					nameTextNode = nameNode.childNodes[0];
					var IdMascaraCampo = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("OpcaoValor")[i]; 
					nameTextNode = nameNode.childNodes[0];
					var OpcaoValor = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("IdTipoTexto")[i]; 
					nameTextNode = nameNode.childNodes[0];
					var IdTipoTexto = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("ExibirSenha")[i]; 
					nameTextNode = nameNode.childNodes[0];
					var ExibirSenha = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("TamMinimo")[i]; 
					nameTextNode = nameNode.childNodes[0];
					var TamMinimo = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("TamMaximo")[i]; 
					nameTextNode = nameNode.childNodes[0];
					var TamMaximo = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoParametroServicoCDA")[i]; 
					nameTextNode = nameNode.childNodes[0];
					var DescricaoParametroServicoCDA = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("DataCriacao")[i]; 
					nameTextNode = nameNode.childNodes[0];
					var DataCriacao = nameTextNode.nodeValue;
				
					nameNode = xmlhttp.responseXML.getElementsByTagName("LoginCriacao")[i]; 
					nameTextNode = nameNode.childNodes[0];
					var LoginCriacao = nameTextNode.nodeValue;					
				
					nameNode = xmlhttp.responseXML.getElementsByTagName("DataAlteracao")[i]; 
					nameTextNode = nameNode.childNodes[0];
					var DataAlteracao = nameTextNode.nodeValue;
				
					nameNode = xmlhttp.responseXML.getElementsByTagName("LoginAlteracao")[i]; 
					nameTextNode = nameNode.childNodes[0];
					var LoginAlteracao = nameTextNode.nodeValue;
					
					if(IdParametroServicoCond != ''){
						addParmUrl("marServicoParametro","IdParametroServico",IdParametroServico);
						addParmUrl("marServicoParametroNovo","IdServico",IdServico);
						document.formulario.IdParametroServico.value				= IdParametroServico;
						document.formulario.IdTipoAcesso.value						= IdTipoAcesso;
						document.formulario.SalvarHistorico.value					= SalvarHistorico;
						document.formulario.DescricaoParametroServico.value			= DescricaoParametroServico;
						document.formulario.Obrigatorio_Texto.value					= Obrigatorio;
						document.formulario.ObrigatorioStatus_Texto.value			= Obrigatorio;
						document.formulario.Editavel_Texto.value					= Editavel;
						document.formulario.BotaoAuxiliar_Texto.value				= BotaoAuxiliar;
						document.formulario.Visivel_Texto.value						= Visivel;
						document.formulario.VisivelOS_Texto.value					= VisivelOS;
						document.formulario.VisivelCDA_Texto.value					= VisivelCDA;
						document.formulario.AcessoCDA_Texto.value					= AcessoCDA;
						document.formulario.Unico_Texto.value						= Unico;
						document.formulario.ParametroDemonstrativo_Texto.value		= ParametroDemonstrativo;
						document.formulario.Calculavel_Texto.value					= Calculavel;
						document.formulario.Obrigatorio_Selecao.value				= Obrigatorio;
						document.formulario.ObrigatorioStatus_Selecao.value			= Obrigatorio;
						document.formulario.Editavel_Selecao.value					= Editavel;
						document.formulario.Visivel_Selecao.value					= Visivel;
						document.formulario.VisivelOS_Selecao.value					= VisivelOS;
						document.formulario.VisivelCDA_Selecao.value				= VisivelCDA;
						document.formulario.AcessoCDA_Selecao.value					= AcessoCDA;
						document.formulario.Unico_Selecao.value						= Unico;
						document.formulario.ParametroDemonstrativo_Selecao.value	= ParametroDemonstrativo;
						document.formulario.Calculavel_Selecao.value				= Calculavel;
						document.formulario.Obs.value								= Obs;
						document.formulario.CalculavelOpcoes.value					= CalculavelOpcoes;
						document.formulario.IdStatusParametro.value					= IdStatusParametro;
						document.formulario.IdTipoParametro.value					= IdTipoParametro;
						document.formulario.IdTipoTexto.value						= IdTipoTexto;
						document.formulario.TamMinimo.value							= TamMinimo;
						document.formulario.TamMaximo.value							= TamMaximo;
						document.formulario.DescricaoParametroServicoCDA.value		= DescricaoParametroServicoCDA;
						document.formulario.DataCriacao.value						= dateFormat(DataCriacao);
						document.formulario.LoginCriacao.value						= LoginCriacao;
						document.formulario.DataAlteracao.value						= dateFormat(DataAlteracao);
						document.formulario.LoginAlteracao.value					= LoginAlteracao;
						
						while(document.formulario.ValorDefaultSelect.options.length > 0){
							document.formulario.ValorDefaultSelect.options[0] = null;
						}
						
						while(document.formulario.IdMascaraCampo.options.length > 0){
							document.formulario.IdMascaraCampo.options[0] = null;
						}
						
						addOption(document.formulario.IdMascaraCampo,"","");
						
						if(Calculavel == 1){
							document.getElementById('cpRotinaCalculo').style.display	= 'block';
							document.formulario.RotinaCalculo.value						= RotinaCalculo;
						} else{
							document.getElementById('cpRotinaCalculo').style.display	= 'none';
							document.formulario.RotinaCalculo.value						= '';
						}
						
						if(CalculavelOpcoes == 1){
							document.getElementById('cpOpcaoValor').style.display			= 'none';
							document.getElementById('cpRotinaOpcoes').style.display			= 'block';
							document.getElementById('cpRotinaOpcoesContrato').style.display	= 'block';
							document.formulario.RotinaOpcoes.value							= RotinaOpcoes;
							document.formulario.RotinaOpcoesContrato.value					= RotinaOpcoesContrato;
						} else{
							document.getElementById('cpOpcaoValor').style.display			= 'block';
							document.getElementById('cpRotinaOpcoes').style.display			= 'none';
							document.getElementById('cpRotinaOpcoesContrato').style.display	= 'none';
							document.formulario.RotinaOpcoes.value							= '';
							document.formulario.RotinaOpcoesContrato.value					= '';		
						}
						
						if(document.formulario.IdTipoParametro.value == 1){
							if(Editavel == 2){
								document.formulario.Obrigatorio_Texto.disabled = true;
							} else{
								document.formulario.Obrigatorio_Texto.disabled = false;
							}
							
							verificaEditavel(document.formulario.Editavel_Texto);
						} else if(document.formulario.IdTipoParametro.value == 2){
							if(Editavel == 2){
								document.formulario.Obrigatorio_Selecao.disabled = true;
							} else{
								document.formulario.Obrigatorio_Selecao.disabled = false;
							}
							
							verificaEditavel(document.formulario.Editavel_Selecao);
						}
						
						busca_grupos_usuario(IdGrupoUsuario);
						// Caixa de Seleção
						if(IdTipoParametro == 2){
							document.formulario.ValorDefaultInput.value	= "";
							document.formulario.OpcaoValor.value		= OpcaoValor;
							
							if(CalculavelOpcoes == 1){
								busca_rotina_opcoes(RotinaOpcoes,ValorDefault);
							} else{
								busca_opcao_valor(OpcaoValor,ValorDefault);
							}
						} else{
							document.formulario.ValorDefaultInput.value	= ValorDefault;
							document.formulario.OpcaoValor.value		= "";
						}
						
						if(IdTipoTexto == 2){
							IdMascaraCampo = ExibirSenha;
						}
						
						verificaTipoParametro(IdTipoParametro);
						verificaTipoParametroCDA(VisivelCDA);
						
						if(IdTipoTexto == 3){
							atualizaTipoTexto(IdTipoTexto,ValorDefault);
						} else{
							atualizaTipoTexto(IdTipoTexto,IdMascaraCampo);
						}
						//chama_mascara();
						document.formulario.Acao.value = 'alterar';
						verificaErro();
					}
					atualizaCampo(IdMascaraCampo);
				}
			}
			
			if(window.janela != undefined){
				window.janela.close();
			}
			
			verificaAcao();
		});
	}