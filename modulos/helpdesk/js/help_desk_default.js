	function busca_help_desk(IdTicket,Erro,Local){
		if(IdTicket == '' || IdTicket == undefined){
			IdTicket = 0;
		}
		if(Local == '' || Local == undefined){
			Local	=	document.formulario.Local.value;
		}
		
		var IdPessoa = document.formulario.IdPessoa.value;
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
	    if(IdPessoa != ""){
			url = "xml/help_desk.php?IdTicket="+IdTicket+"&IdPessoa="+IdPessoa;
		}else{
			url = "xml/help_desk.php?IdTicket="+IdTicket;
		}

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
								document.formulario.IdTicket.value				= "";
								document.formulario.Assunto.value 				= "";
								document.formulario.IdTipoTicket.value 			= "";
								document.formulario.IdTipoTicket2.value 		= "";
								document.formulario.IdSubTipoTicket.value 		= "";
								document.formulario.IdSubTipoTicket2.value 		= "";
								document.formulario.Mensagem.value 				= "";
								document.formulario.Mensagem2.value 			= "";
								document.formulario.DataCriacao.value			= "";
								document.formulario.LoginCriacao.value			= "";
								document.formulario.DataCriacao2.value			= "";
								document.formulario.LoginCriacao2.value			= "";
								document.formulario.Acao.value					= 'inserir';
								
								document.getElementById("tit_cpMensagem").innerHTML = "Mensagem";
								document.getElementById("tit_cpMensagem2").innerHTML = "Mensagem";
								document.getElementById("txtAdvertencia").innerHTML = "<B>Favor abrir um ticket para cada solicitação.<br />Tickets com mais de uma solicitação serão devolvidos.</B>";
								document.getElementById("cpEquipeResponsavel").style.display = "none";
								
								document.formulario.bt_aceitar.disabled			= true;
								document.formulario.bt_aceitar2.disabled		= true;
								document.formulario.bt_reabrir.disabled			= true;
								document.formulario.bt_reabrir2.disabled		= true;
								document.formulario.IdTipoTicket.disabled 		= false;
								document.formulario.IdTipoTicket2.disabled 		= false;
								document.formulario.IdSubTipoTicket.disabled 	= false;
								document.formulario.IdSubTipoTicket2.disabled 	= false;
								document.formulario.Assunto.readOnly 			= false;
								document.formulario.Assunto2.readOnly 			= false;
								
								while(document.getElementById('tabelaArquivos').rows.length > 2){
									document.getElementById('tabelaArquivos').deleteRow(1);
								}
								
								if(document.formulario.DescricaoArquivo_1 != undefined){
									document.formulario.DescricaoArquivo_1.value = '';
								}
								
								if(document.formulario.fakeupload_1 != undefined){
									document.formulario.fakeupload_1.value = '';
								}
								
								busca_subtipo_help_desk('');
								busca_subtipo_help_desk('','',document.formulario.IdSubTipoTicket2);
								listarHistorico('');
								busca_status('');
								
								document.getElementById("cp_duplicado").style.display = "none";
								document.formulario.IdTicket.focus();
								break;
						}
						// Fim de Carregando
						carregando(false);
					}else{
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("IdPessoa")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var IdPessoa = nameTextNode.nodeValue;
							
						nameNode = xmlhttp.responseXML.getElementsByTagName("IdTicket")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var IdTicket = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("Assunto")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var Assunto = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("IdTipoHelpDesk")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var IdTipoHelpDesk = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("IdSubTipoHelpDesk")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var IdSubTipoHelpDesk = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("IdGrupoUsuario")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var IdGrupoUsuario = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("Data")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var Data = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("Hora")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var Hora = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("LoginResponsavel")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var LoginResponsavel = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("Obs")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var Obs = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("IdStatus")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var IdStatus = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("DataCriacao")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var DataCriacao = nameTextNode.nodeValue;
					
						nameNode = xmlhttp.responseXML.getElementsByTagName("LoginCriacao")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var LoginCriacao = nameTextNode.nodeValue;
						
						addParmUrl("marHelpDesk","IdTicket",IdTicket);
						document.getElementById("cp_duplicado").style.display = "block";
						
						busca_subtipo_help_desk(IdTipoHelpDesk,IdSubTipoHelpDesk);
						busca_subtipo_help_desk(IdTipoHelpDesk,IdSubTipoHelpDesk,document.formulario.IdSubTipoTicket2);
						busca_status(IdStatus);
						
						if(IdStatus>599 && IdStatus<700){
							document.formulario.bt_aceitar.disabled		= false;
							document.formulario.bt_aceitar2.disabled	= false;
						} else{
							document.formulario.bt_aceitar.disabled		= true;
							document.formulario.bt_aceitar2.disabled	= true;
						}
						
						if((IdStatus>599 && IdStatus<700) || (IdStatus>399 && IdStatus<500)){
							document.formulario.bt_reabrir.disabled		= false;
							document.formulario.bt_reabrir2.disabled	= false;
						} else{
							document.formulario.bt_reabrir.disabled		= true;
							document.formulario.bt_reabrir2.disabled	= true;
						}
						
						if(!(IdStatus>399 && IdStatus<500) && !(IdStatus>599 && IdStatus<700)){
							document.formulario.bt_encaminhar.disabled = false;
							document.formulario.bt_encaminhar2.disabled = false;
						} else{
							document.formulario.bt_encaminhar.disabled = true;
							document.formulario.bt_encaminhar2.disabled = true;
						}
						
						document.formulario.IdPessoa.value						=	IdPessoa;
						document.formulario.IdTicket.value						=	IdTicket;
						document.formulario.IdTipoTicket.value 					=	IdTipoHelpDesk;
						document.formulario.IdTipoTicket2.value 				=	IdTipoHelpDesk;
						document.formulario.IdStatus.value						=	IdStatus;
						document.formulario.Assunto.value 						=	Assunto;
						document.formulario.Mensagem.value 						=	"";
						document.formulario.Assunto2.value 						=	Assunto;
						document.formulario.Mensagem2.value 					=	"";
						document.formulario.DataCriacao.value					=	dateFormat(DataCriacao);
						document.formulario.LoginCriacao.value					=	LoginCriacao;
						document.formulario.DataCriacao2.value					=	dateFormat(DataCriacao);
						document.formulario.LoginCriacao2.value					=	LoginCriacao;
						document.formulario.Acao.value							=	'alterar';
						
						while(document.getElementById('tabelaArquivos').rows.length > 2){
							document.getElementById('tabelaArquivos').deleteRow(1);
						}
						
						if(document.formulario.DescricaoArquivo_1 != undefined){
							document.formulario.DescricaoArquivo_1.value = '';
						}
						
						if(document.formulario.fakeupload_1 != undefined){
							document.formulario.fakeupload_1.value = '';
						}
						
						document.getElementById("cpEquipeResponsavel").style.display = "none";
						
						if(IdGrupoUsuario!=""){
							document.getElementById("cpEquipeResponsavel").style.display		= "block";
							document.getElementById("tit_sepGrupoAtendimento").style.display	= "block";
							document.getElementById("titGrupoAtendimento").style.display		= "block";
							document.getElementById("cap_sepGrupoAtendimento").style.display	= "block";
							document.formulario.IdGrupoUsuarioAtendimento.style.display			= "block";
							document.formulario.IdGrupoUsuarioAtendimento.value					= IdGrupoUsuario;
							
							if(LoginResponsavel!=""){
								document.getElementById("tit_sepUsuarioAtendimento").style.display	= "block";
								document.getElementById("titUsuarioAtendimento").style.display		= "block";
								document.getElementById("cap_sepUsuarioAtendimento").style.display	= "block";
								document.formulario.LoginAtendimento.style.display					= "block";
								busca_login_usuario(IdGrupoUsuario,document.formulario.LoginAtendimento,LoginResponsavel);
							} else{
								document.getElementById("tit_sepUsuarioAtendimento").style.display	= "none";
								document.getElementById("titUsuarioAtendimento").style.display		= "none";
								document.getElementById("cap_sepUsuarioAtendimento").style.display	= "none";
								document.formulario.LoginAtendimento.style.display					= "none";
							}
						} else{
							document.getElementById("tit_sepGrupoAtendimento").style.display	= "none";
							document.getElementById("titGrupoAtendimento").style.display		= "none";
							document.getElementById("cap_sepGrupoAtendimento").style.display	= "none";
							document.formulario.IdGrupoUsuarioAtendimento.style.display			= "none";
							
							document.getElementById("tit_sepUsuarioAtendimento").style.display	= "none";
							document.getElementById("titUsuarioAtendimento").style.display		= "none";
							document.getElementById("cap_sepUsuarioAtendimento").style.display	= "none";
							document.formulario.LoginAtendimento.style.display					= "none";
						}
						
						if(Data!=""){
							document.getElementById("cpEquipeResponsavel").style.display	= "block";
							document.getElementById("titData").style.display				= "block";
							document.formulario.Data.style.display							= "block";
							document.formulario.Data.value									= dateFormat(Data);
							
							if(Hora!=""){
								document.getElementById("titHora").style.display	= "block";
								document.formulario.Hora.style.display				= "block";
								document.formulario.Hora.value						= Hora;
							} else{
								document.getElementById("titHora").style.display	= "none";
								document.formulario.Hora.style.display				= "none";
							}
						} else{
							document.getElementById("titData").style.display	= "none";
							document.formulario.Data.style.display				= "none";
							document.getElementById("titHora").style.display	= "none";
							document.formulario.Hora.style.display				= "none";
						}
						
						document.getElementById("tit_cpMensagem").innerHTML = "Nova Observação";
						document.getElementById("tit_cpMensagem2").innerHTML = "Nova Observação";
						document.getElementById("txtAdvertencia").innerHTML = "<B>Os prazos informados são previsões referentes a cada etapa específica e não do tempo total de conclusão do Ticket.</B>";
						
						document.formulario.IdTipoTicket.disabled 		= true;
						document.formulario.IdTipoTicket2.disabled 		= true;
						document.formulario.IdSubTipoTicket.disabled 	= true;
						document.formulario.IdSubTipoTicket2.disabled 	= true;
						document.formulario.Assunto.readOnly 			= true;
						document.formulario.Assunto2.readOnly 			= true;
						listarHistorico(IdTicket);
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
	function busca_pessoa(){
		
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
	    
	   	url = "xml/pessoa_loja.php";

		xmlhttp.open("GET", url,true);
		xmlhttp.onreadystatechange = function(){ 
	
			// Carregando...
			carregando(true);
	
			if(xmlhttp.readyState == 4){ 
				if(xmlhttp.status == 200){
					if(xmlhttp.responseText != 'false'){
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("IdPessoa")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var IdPessoa = nameTextNode.nodeValue;
												
						document.formulario.IdPessoa.value						=	IdPessoa;
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