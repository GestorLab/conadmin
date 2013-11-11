	function busca_help_desk(IdTicket,Erro,Local){			
		if(IdTicket == '' || IdTicket == undefined){
			IdTicket = 0;
		}
		if(Local == '' || Local == undefined){
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
	    
	   	url = "xml/help_desk.php?IdTicket="+IdTicket;

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
							case 'HelpDesk':
								document.formulario.IdTicket.value						= "";
								document.formulario.LocalAbertura.value					= "";
								document.formulario.IdLojaHelpDesk.value 				= "";
								document.formulario.IdLojaHelpDeskF.value 				= "";
								document.formulario.IdPessoa.value 						= "";
								document.formulario.IdPessoaF.value 					= "";
								document.formulario.Nome.value 							= "";
								document.formulario.NomeF.value 						= "";
								document.formulario.RazaoSocial.value 					= "";
								document.formulario.CPF.value 							= "";
								document.formulario.CNPJ.value 							= "";
								document.formulario.IdTipoTicket.value					= "";
								document.formulario.IdTipoTicketTemp.value				= "";
								document.formulario.IdTipoTicket2.value					= "";
								document.formulario.IdSubTipoTicketTemp.value			= "";
								document.formulario.Assunto.value						= "";
								document.formulario.AssuntoTemp.value					= "";
								document.formulario.Assunto2.value						= "";
								document.formulario.Mensagem.value						= "";
								document.formulario.Mensagem2.value						= "";
								document.formulario.ChangeLog.value						= "";
								document.formulario.IdGrupoUsuarioAtendimento.value		= "";
								document.formulario.IdGrupoUsuarioAtendimentoTemp.value	= "";
								document.formulario.LoginAtendimentoTemp.value			= "";
								document.formulario.Data.value							= "";
								document.formulario.Data2.value							= "";
								document.formulario.Hora.value							= "";
								document.formulario.Hora2.value							= "";
								document.formulario.IdStatus.value 						= 100;
								document.formulario.IdStatusNovoTemp.value				= 100;
								document.formulario.IdMarcador.value					= "";
								document.formulario.DataCriacao.value					= "";
								document.formulario.LoginCriacao.value					= "";
								document.formulario.DataCriacao2.value					= "";
								document.formulario.LoginCriacao2.value					= "";
								document.formulario.IdGrupoAtendimento.value			= "";
								document.formulario.TicketDia.value						= 0;
								document.formulario.TicketDia2.value					= 0;
								document.formulario.IdPais.value						= "";
								document.formulario.IdEstado.value						= "";
								document.formulario.IdCidade.value						= "";
								document.formulario.Pais.value							= "";
								document.formulario.Estado.value						= "";
								document.formulario.Cidade.value						= "";
								document.formulario.Acao.value							= 'inserir';
								
								addParmUrl("marHelpDesk","IdTicket",'');
								addParmUrl("marTipoHelpDesk","IdTipo",'');
								addParmUrl("marContrato","IdPessoa",'');
								addParmUrl("marContratoNovo","IdPessoa",'');
								addParmUrl("marTipoHelpDeskNovo","IdTipo",'');
								addParmUrl("marSubTipoHelpDesk","IdTipo",'');
								addParmUrl("marSubTipoHelpDeskNovo","IdTipo",'');
								addParmUrl("marSubTipoHelpDesk","IdSubTipo",'');
								addParmUrl("marSubTipoHelpDeskNovo","IdSubTipo",'');
								addParmUrl("marMensagemEnviada","IdTicket",'');
								
								//document.formulario.IdLojaHelpDesk.readOnly			= false;
								document.formulario.IdLojaHelpDeskF.readOnly		= false;
								document.formulario.IdPessoa.readOnly				= false;
								document.formulario.IdPessoaF.readOnly				= false;
								document.formulario.Assunto.readOnly 				= false;
								document.formulario.Assunto2.readOnly 				= false;
								
								document.getElementById("tit_cpMensagem").innerHTML		= "Mensagem";
								document.getElementById("tit_cpMensagem2").innerHTML	= "Mensagem";
								document.getElementById("titHora").style.bakcground		= "#ffffff";
								document.getElementById("titHora").style.color			= "#000000";
								document.getElementById("titData").style.background		= "#ffffff";
								document.getElementById("titData").style.color			= "#000000";
								document.getElementById("titPrioridade").style.display	= "none";
								document.getElementById("cpPrioridade").style.display	= "none";
								
								while(document.getElementById('tabelaArquivos').rows.length > 2){
									document.getElementById('tabelaArquivos').deleteRow(1);
								}
								
								busca_login_usuario('',document.formulario.LoginAtendimento);
								busca_subtipo_help_desk('');
								busca_subtipo_help_desk('','',document.formulario.IdSubTipoTicket2);
								busca_pessoa('',false,Local);
								verifica_change_log('');
								buscaVisualizar(false);
								
								document.getElementById('cp_juridica').style.display			= "block";
								document.getElementById('cp_fisica').style.display				= "none";
								document.getElementById('tableMarcador').style.display			= "none";
								document.getElementById('titTableMarcador').style.display		= "none";
								document.getElementById('cp_Status').style.display				= "none";
								document.getElementById("titPublica").style.display				= "none";
								document.getElementById("titPublica2").style.display			= "none";
								document.getElementById("cpPublica").style.display				= "none";
								document.getElementById("cpPublica2").style.display				= "none";
								document.getElementById("cp_ChangeLog").style.display			= "none";
								
								document.getElementById("cpAssunto").style.width 				= "815px";
								document.getElementById("cpAssunto2").style.width 				= "815px";
								
								document.formulario.Visualizar.value 							= "";
								document.formulario.bt_visualizar.value 						= "Visualizar Histórico";
								
								while(document.getElementById('tabelaHelpDeskHistorico').rows.length > 2){
									document.getElementById('tabelaHelpDeskHistorico').deleteRow(1);
								}
								
								if(document.formulario.DescricaoArquivo_1 != undefined){
									document.formulario.DescricaoArquivo_1.value = '';
								}
								
								if(document.formulario.fakeupload_1 != undefined){
									document.formulario.fakeupload_1.value = '';
								}
								
								document.getElementById('cp_help_desk_historico').style.display	= "none";
								document.getElementById("cp_duplicado").style.display = "none";
								document.formulario.bt_visualizar.disabled = true;
								document.formulario.IdTicket.focus();
								status_inicial();
								//ticket_dia(document.formulario.Data.value,document.formulario.IdGrupoUsuarioAtendimento.value,document.formulario.LoginAtendimento.value,event);
								//ticket_dia(document.formulario.Data2.value,document.formulario.IdGrupoUsuarioAtendimento.value,document.formulario.LoginAtendimento.value,event);
								break;
						}
						// Fim de Carregando
						carregando(false);
					}else{
						nameNode = xmlhttp.responseXML.getElementsByTagName("IdTicket")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var IdTicket = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("IdTicketHistorico")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var IdTicketHistorico = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("LocalAbertura")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var LocalAbertura = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("IdLojaHelpDesk")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var IdLojaHelpDesk = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("IdPessoa")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var IdPessoa = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("IdMarcador")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var IdMarcador = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("Assunto")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var Assunto = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("ChangeLog")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var ChangeLog = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("Mensagem")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var Mensagem = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("IdTipoHelpDesk")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var IdTipoHelpDesk = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("IdSubTipoHelpDesk")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var IdSubTipoHelpDesk = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("IdHistoricoMensagem")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var IdHistoricoMensagem = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("IdGrupoUsuario")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var IdGrupoUsuario = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("LoginResponsavel")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var LoginResponsavel = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("Data")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var Data = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("Hora")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var Hora = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("IdStatus")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var IdStatus = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("VisualizarHistorico")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var VisualizarHistorico = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("IdPrioridade")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var IdPrioridade = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoPrioridade")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var DescricaoPrioridade = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("CorMarcador1")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var CorMarcador1 = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("CorMarcador2")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var CorMarcador2 = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("CorMarcador3")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var CorMarcador3 = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("TicketDia")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var TicketDia = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("IdPais")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var IdPais = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("IdEstado")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var IdEstado = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("IdCidade")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var IdCidade = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("NomePais")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var NomePais = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("NomeEstado")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var NomeEstado = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("NomeCidade")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var NomeCidade = nameTextNode.nodeValue;
						
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
							case 'HelpDesk':
								addParmUrl("marHelpDesk","IdTicket",IdTicket);
								addParmUrl("marTipoHelpDesk","IdTipo",IdTipoHelpDesk);
								addParmUrl("marContrato","IdPessoa",IdPessoa);
								addParmUrl("marContratoNovo","IdPessoa",IdPessoa);
								addParmUrl("marTipoHelpDeskNovo","IdTipo",IdTipoHelpDesk);
								addParmUrl("marSubTipoHelpDesk","IdTipo",IdTipoHelpDesk);
								addParmUrl("marSubTipoHelpDeskNovo","IdTipo",IdTipoHelpDesk);
								addParmUrl("marSubTipoHelpDesk","IdSubTipo",IdSubTipoHelpDesk);
								addParmUrl("marSubTipoHelpDeskNovo","IdSubTipo",IdSubTipoHelpDesk);
								addParmUrl("marMensagemEnviada","IdTicket",IdTicket);
								busca_status(IdStatus);
								document.getElementById("cp_duplicado").style.display = "block";
								
								busca_subtipo_help_desk(IdTipoHelpDesk,IdSubTipoHelpDesk);
								busca_subtipo_help_desk(IdTipoHelpDesk,IdSubTipoHelpDesk,document.formulario.IdSubTipoTicket2);
								
								document.formulario.IdTicket.value						= 	IdTicket;
								document.formulario.LocalAbertura.value					=	LocalAbertura;
								document.formulario.IdLojaHelpDesk.value				=	IdLojaHelpDesk;
								document.formulario.IdLojaHelpDeskF.value				=	IdLojaHelpDesk;
								document.formulario.IdPessoa.value						=	IdPessoa;
								document.formulario.IdPessoaF.value						=	IdPessoa;
								document.formulario.Assunto.value						=	Assunto;
								document.formulario.AssuntoTemp.value					=	Assunto;
								document.formulario.Assunto2.value						=	Assunto;
								document.formulario.Mensagem.value						=	"";
								document.formulario.Mensagem2.value						=	"";
								document.formulario.ChangeLog.value						=	ChangeLog;
								document.formulario.IdStatus.value						=	500;
								document.formulario.IdStatusNovoTemp.value				=	500;
								document.formulario.IdTipoTicket.value					=	IdTipoHelpDesk;
								document.formulario.IdTipoTicketTemp.value				=	IdTipoHelpDesk;
								document.formulario.IdTipoTicket2.value					=	IdTipoHelpDesk;
								document.formulario.IdSubTipoTicketTemp.value			=	IdSubTipoHelpDesk;
								document.formulario.IdGrupoUsuarioAtendimento.value		=	IdGrupoUsuario;
								document.formulario.IdGrupoUsuarioAtendimentoTemp.value	=	IdGrupoUsuario;
								document.formulario.LoginAtendimentoTemp.value			=	LoginResponsavel;
								document.formulario.Data.value							=	dateFormat(Data);
								document.formulario.Data2.value							=	dateFormat(Data);
								document.formulario.DataCriacao.value					=	dateFormat(DataCriacao);
								document.formulario.LoginCriacao.value					=	LoginCriacao;
								document.formulario.DataCriacao2.value					=	dateFormat(DataCriacao);
								document.formulario.LoginCriacao2.value					=	LoginCriacao;
								document.formulario.Acao.value							=	'alterar';
								document.formulario.Publica.value						=	document.formulario.IdPublicaDefault.value;
								document.formulario.Publica2.value						=	document.formulario.IdPublicaDefault.value;
								document.formulario.IdGrupoAtendimento.value			=	IdGrupoUsuario;
								document.formulario.TicketDia.value						=	TicketDia;
								document.formulario.TicketDia2.value					= 	TicketDia;
								document.formulario.IdPais.value						=	IdPais;
								document.formulario.IdEstado.value						=	IdEstado;
								document.formulario.IdCidade.value						=	IdCidade;
								document.formulario.Pais.value							=	NomePais;
								document.formulario.Estado.value						=	NomeEstado;
								document.formulario.Cidade.value						=	NomeCidade;
								
								while(document.getElementById('tabelaArquivos').rows.length > 2){
									document.getElementById('tabelaArquivos').deleteRow(1);
								}
								
								if(document.formulario.DescricaoArquivo_1 != undefined){
									document.formulario.DescricaoArquivo_1.value = '';
								}
								
								if(document.formulario.fakeupload_1 != undefined){
									document.formulario.fakeupload_1.value = '';
								}
								
								document.getElementById("titHora").style.background	=	"#ffffff";
								document.getElementById("titData").style.background	=	"#ffffff";
								
								if(Hora!=""){
									document.getElementById("titHora").style.color	=	"#CC0000";
									document.getElementById("titHora2").style.color	=	"#CC0000";
									document.getElementById("titData").style.color	=	"#CC0000";
									document.getElementById("titData2").style.color	=	"#CC0000";
									document.formulario.Hora.value					=	Hora;
									document.formulario.Hora2.value					=	Hora;
								} else{
									document.getElementById("titHora").style.color	=	"#000000";
									document.getElementById("titHora2").style.color	=	"#000000";
									document.getElementById("titData").style.color	=	"#000000";
									document.getElementById("titData2").style.color	=	"#000000";
									document.formulario.Hora.value					=	"";
									document.formulario.Hora2.value					=	"";
								}

								document.formulario.IdPessoa.readOnly			=	true;
								document.formulario.IdPessoaF.readOnly			=	true;
								document.formulario.Assunto.readOnly 			=	true;
								document.formulario.Assunto2.readOnly 			=	true;
								
								document.getElementById('cp_Status').style.display				= "none";
								
								document.formulario.Visualizar.value 							= "";
								document.formulario.bt_visualizar.value 						= "Visualizar Histórico";
								
								while(document.getElementById('tabelaHelpDeskHistorico').rows.length > 2){
									document.getElementById('tabelaHelpDeskHistorico').deleteRow(1);
								}
								document.getElementById('cp_help_desk_historico').style.display	= "none";
								
								if(VisualizarHistorico==1){
									document.formulario.bt_visualizar.disabled = false;
								}
								
								busca_login_usuario(IdGrupoUsuario,document.formulario.LoginAtendimento,LoginResponsavel);
								busca_pessoa(IdPessoa,false,Local);
								buscaVisualizar();
								
								if(IdPrioridade != ''){
									document.getElementById("titPrioridade").style.display	= "block";
									document.getElementById("cpPrioridade").style.display	= "block";
									document.getElementById("nivelPrioridade").src			= "../../img/estrutura_sistema/prioridade"+IdPrioridade+".gif";
									document.getElementById("nivelPrioridade").title		= DescricaoPrioridade;
								}
								
								if(IdStatus > 399 && IdStatus < 500){
									document.formulario.bt_alterar.disabled = true;
									document.formulario.bt_alterar2.disabled = true;
								} else{
									document.formulario.bt_alterar.disabled = false;
									document.formulario.bt_alterar2.disabled = false;
								}
								
								if((IdStatus > 199 && IdStatus < 300) || (IdStatus > 399 && IdStatus < 500) || (IdStatus > 599 && IdStatus < 700)){
									document.formulario.bt_finalizar.disabled = true;
									document.formulario.bt_finalizar2.disabled = true;
								} else{
									document.formulario.bt_finalizar.disabled = false;
									document.formulario.bt_finalizar2.disabled = false;
								}
								
								if((IdStatus > 199 && IdStatus < 500) || (IdStatus > 599 && IdStatus < 700)){
									document.formulario.bt_pendente.disabled = true;
									document.formulario.bt_pendente2.disabled = true;
								} else{
									document.formulario.bt_pendente.disabled = false;
									document.formulario.bt_pendente2.disabled = false;
								}
								
								if((IdStatus > 99 && IdStatus < 200) || (IdStatus > 299 && IdStatus < 400) || (IdStatus > 499 && IdStatus < 600)){
									document.getElementById('titTableMarcador').style.display	=	'block';	
									document.getElementById('tableMarcador').style.display		=	'block';
									
									document.getElementById('mVermelho').style.backgroundColor	=	CorMarcador1;
									document.getElementById('mAmarelo').style.backgroundColor	=	CorMarcador2;
									document.getElementById('mVerde').style.backgroundColor		=	CorMarcador3;
									document.formulario.IdMarcador.value						=	IdMarcador;
									
									if(IdMarcador != '1' && IdMarcador != '2' && IdMarcador != '3'){
										document.formulario.IdMarcador.value	=	'';
									}
									
									document.getElementById("tit_cpMensagem").innerHTML		= "Nova Observação";
									document.getElementById("tit_cpMensagem2").innerHTML	= "Nova Observação";
								}else{
									document.getElementById('titTableMarcador').style.display	= "none";
									document.getElementById('tableMarcador').style.display		= "none";	
								}
								
								if(parseInt(IdStatus) == 400 || IdStatus == 600){
									document.getElementById("cp_ChangeLog").style.display = "block";
									verifica_change_log(ChangeLog);
								} else{
									document.getElementById("cp_ChangeLog").style.display = "none";
									verifica_change_log('');
								}
								
								if(IdStatus == 600)
								{
									addOption(document.formulario.IdStatus,"Aguard. Finalização Cliente",600);
									document.formulario.IdStatus.value = 500;
								}
								document.getElementById("cpAssunto").style.width 		= "700px";
								document.getElementById("cpAssunto2").style.width 		= "700px";
								document.getElementById("titPublica").style.display		= "block";
								document.getElementById("titPublica2").style.display	= "block";
								document.getElementById("cpPublica").style.display		= "block";
								document.getElementById("cpPublica2").style.display		= "block";	
								
								ticket_dia(dateFormat(Data),IdGrupoUsuario,LoginResponsavel,event="");
								break;
						}
					}
					if(window.janela != undefined){
						window.janela.close();
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