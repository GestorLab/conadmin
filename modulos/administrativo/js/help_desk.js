	function verificaAcao(){
		if(document.formulario != undefined){
			if(document.formulario.Acao.value=='inserir'){	
				document.formulario.bt_imprimir.disabled	= true;		
				document.formulario.bt_imprimir2.disabled	= true;		
				document.formulario.bt_inserir.disabled 	= false;
				document.formulario.bt_inserir2.disabled 	= false;
				document.formulario.bt_alterar.disabled 	= true;
				document.formulario.bt_alterar2.disabled 	= true;
			}
			if(document.formulario.Acao.value=='alterar'){
				document.formulario.bt_imprimir.disabled	= false;	
				document.formulario.bt_imprimir2.disabled	= false;	
				document.formulario.bt_inserir.disabled 	= true;
				document.formulario.bt_inserir2.disabled 	= true;
				document.formulario.bt_alterar.disabled 	= false;
				document.formulario.bt_alterar2.disabled 	= false;
				
				verifica_previsao_etapa();
			}
		}	
	}
	function inicia(){
		document.formulario.bt_visualizar.disabled = true;
		document.formulario.IdStatus.value = 100;
		addArquivo(null);
		status_inicial();
		document.formulario.IdTicket.focus();
	}
	function validar(){
		if(document.formulario.ChangeLog.value == '' || document.getElementById("cp_ChangeLog").style.display == 'none'){
			if(document.formulario.IdLojaHelpDesk.value == ''){
				mensagens(1);
				document.formulario.IdLojaHelpDesk.focus();
				return false;
			}
			if(document.formulario.IdPessoa.value == ''){
				mensagens(1);
				document.formulario.IdPessoa.focus();
				return false;
			}
			if(document.formulario.IdTipoTicket.value == ''){
				mensagens(1);
				document.formulario.IdTipoTicket.focus();
				return false;
			}
			if(document.formulario.IdSubTipoTicket.value == ''){
				mensagens(1);
				document.formulario.IdSubTipoTicket.focus();
				return false;
			}
			if(document.formulario.Assunto.value == ''){
				mensagens(1);
				document.formulario.Assunto.focus();
				return false;
			}
			if(document.formulario.Publica.value == '' && document.formulario.Publica.style.display == 'block'){				
				mensagens(1);
				document.formulario.Publica.focus();
				return false;
			}
			if(document.formulario.Mensagem.value == ''){
				mensagens(1);
				document.formulario.Mensagem.focus();
				return false;
			}
			for(i = 0; i<document.formulario.length; i++){
				if(document.formulario[i].name != undefined){
					if(document.formulario[i].name.substring(0,11) == 'EndArquivo_'){
						var temp = document.formulario[i].value.split('.');
						var ext = temp[temp.length-1].toLowerCase();
						
						if(!document.formulario.ExtensaoAnexo.value.split(',').in_array(ext) && ext != ''){
							mensagens(10);
							document.formulario[i-1].focus();
							return false;
						} else{
							if(document.formulario[i].value != '' && document.formulario[i+1].value == ''){
								mensagens(1);
								document.formulario[i+1].focus();
								return false;
							}
						}
					}
				}
			}
			if(document.formulario.Data.value == '' && document.formulario.Hora.value != ''){
				mensagens(1);
				document.formulario.Data.focus();
				return false;
			}
			if(document.formulario.Erro.value == "27"){
				if(!validar_Data('titData', document.formulario.Data)){
					document.formulario.Data.focus();
					mensagens(27);
					return false;
				}
			}
			if(document.formulario.Hora.value == '' && document.formulario.Data.value != ''){
				mensagens(1);
				document.formulario.Hora.focus();
				return false;
			}
			if(document.formulario.Erro.value == "28"){
				if(!validar_Time('titHora', document.formulario.Hora)){
					mensagens(28);
					document.formulario.Hora.focus();
					return false;
				}
			}
			if(document.formulario.IdStatus.value == ''){
				mensagens(1);
				document.formulario.IdStatus.focus();
				return false;
			}
			if(document.formulario.Acao.value == "alterar" && document.formulario.Publica.value == 1 && !confirm("ATENÇÃO!\n\nVocê está prestes a inserir uma mensagem pública.\nDeseja continuar?")){
				mensagens(0);
				return false;
			}
		}
		
		mensagens(0);
		return true;
	}
	function validar_Data(id,campo){
		verifica_previsao_etapa();
	
		if(campo.value == ''){
			if(document.formulario.Hora.value != ''){
				document.getElementById('titData').style.color = "#CC0000";
				document.getElementById('titData').style.backgroundColor='#FFF';
				document.getElementById('titData2').style.color = "#CC0000";
				document.getElementById('titData2').style.backgroundColor='#FFF';
			} else{
				document.getElementById('titData').style.color = "#000";
				document.getElementById('titData').style.backgroundColor='#FFF';
				document.getElementById('titData2').style.color = "#000";
				document.getElementById('titData2').style.backgroundColor='#FFF';
				document.getElementById("titHora").style.color = "#000";
				document.getElementById("titHora2").style.color = "#000";
				document.formulario.TicketDia.value = "0";
				document.formulario.TicketDia2.value = "0";
			}
			
			mensagens(0);
			return false;
		}
		
		if(isData(campo.value) == false){	
			document.getElementById('titData').style.backgroundColor = '#C10000';
			document.getElementById('titData').style.color='#FFF';
			document.getElementById('titData2').style.backgroundColor = '#C10000';
			document.getElementById('titData2').style.color='#FFF';
			
			mensagens(27);
			document.formulario.Erro.value = 27;
			return false;
		}else{
			document.getElementById('titData').style.color = "#CC0000";
			document.getElementById('titData').style.backgroundColor='#FFF';
			document.getElementById('titData2').style.color = "#CC0000";
			document.getElementById('titData2').style.backgroundColor='#FFF';
			
			if(isTime(document.formulario.Hora.value)){
				document.getElementById("titHora").style.color = "#CC0000";
				document.getElementById("titHora").style.backgroundColor='#FFF';
				document.getElementById("titHora2").style.color = "#CC0000";
				document.getElementById("titHora2").style.backgroundColor='#FFF';
			} else{
				document.formulario.Erro.value = 28;
				mensagens(28);
				return false;
			}
				
			if(validar_data_hora(campo.value, '', id)){
				return true;
			} else{
				return false;
			}
		}
	}
	function validar_Time(id,campo){
		verifica_previsao_etapa();
		
		if(campo.value == ''){
			if(document.formulario.Data.value != ''){
				document.getElementById('titHora').style.color = "#CC0000";
				document.getElementById('titHora').style.backgroundColor='#FFF';
				document.getElementById('titHora2').style.color = "#CC0000";
				document.getElementById('titHora2').style.backgroundColor='#FFF';
			} else{
				document.getElementById('titHora').style.color = "#000";
				document.getElementById('titHora').style.backgroundColor='#FFF';
				document.getElementById('titHora2').style.color = "#000";
				document.getElementById('titHora2').style.backgroundColor='#FFF';
				document.getElementById("titData").style.color = "#000";
				document.getElementById("titData2").style.color = "#000";
				document.formulario.TicketDia.value = "0";
				document.formulario.TicketDia2.value = "0";
			}
			
			mensagens(0);
			return false;
		}
		
		if(isTime(campo.value) == false){
			document.getElementById('titHora').style.backgroundColor = '#C10000';
			document.getElementById('titHora').style.color='#FFF';
			document.getElementById('titHora2').style.backgroundColor = '#C10000';
			document.getElementById('titHora2').style.color='#FFF';
			
			document.formulario.Erro.value = 28;
			mensagens(28);
			return false;
		}else{
			document.getElementById('titHora').style.color = "#CC0000";
			document.getElementById('titHora').style.backgroundColor='#FFF';
			document.getElementById('titHora2').style.color = "#CC0000";
			document.getElementById('titHora2').style.backgroundColor='#FFF';
			if(isData(document.formulario.Data.value)){ 
				document.getElementById("titData").style.color = "#CC0000";
				document.getElementById("titData").style.backgroundColor='#FFF';
				document.getElementById("titData2").style.color = "#CC0000";
				document.getElementById("titData2").style.backgroundColor='#FFF';
			} else{
				document.formulario.Erro.value = 27;
				mensagens(27);
				return false;
			}	
			if(validar_data_hora('', campo.value, id)){
				return true;
			} else{
				return false;
			}
		}	
	}
	function validar_data_hora(Data, Hora, Id){
			
		var IdAux;
		var IdGrupoUsuario = document.formulario.IdGrupoUsuarioAtendimento.value;
		var LoginResponsavel = document.formulario.LoginAtendimento.value;
		
			
		if(Id.substring(0,7) == "titData"){
			IdAux = "titData2";
		}
		if(Id.substring(0,7) == "titHora"){
			IdAux = "titHora2";
		}
		if(Data == ''){
			Data = document.formulario.Data.value;
		}
		
		if(Hora == ''){
			Hora = document.formulario.Hora.value;
		}
		
		var xmlhttp   = false;
		if (window.XMLHttpRequest) { // Mozilla, Safari,...
			xmlhttp = new XMLHttpRequest();
	        if(xmlhttp.overrideMimeType){
	    //    	xmlhttp.overrideMimeType('text/xml');
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
		
		url = "xml/validar_data_hora.php?Data="+Data+"&Hora="+Hora;
		xmlhttp.open("GET", url,true);

		xmlhttp.onreadystatechange = function(){ 

			// Carregando...
			carregando(true);

			if(xmlhttp.readyState == 4){ 
				if(xmlhttp.status == 200){
					if(xmlhttp.responseText == 'true'){
						mensagens(0);
						
						document.getElementById(Id).style.color = "#CC0000";
						document.getElementById(Id).style.backgroundColor = "#FFF";
						document.getElementById(IdAux).style.color = "#CC0000";
						document.getElementById(IdAux).style.backgroundColor = "#FFF";
						document.formulario.Erro.value = "";
						
					} else{
						nameNode = xmlhttp.responseXML.getElementsByTagName("Error")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var Error = nameTextNode.nodeValue;
						
						if(Error != 0){
							nameNode = xmlhttp.responseXML.getElementsByTagName("IdTemp")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var IdTemp = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("IdTemp2")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var IdTemp2 = nameTextNode.nodeValue;
							
							document.getElementById(IdTemp).style.backgroundColor = '#C10000';
							document.getElementById(IdTemp).style.color='#FFF';
							document.getElementById(IdTemp2).style.backgroundColor = '#C10000';
							document.getElementById(IdTemp2).style.color='#FFF';
					
							if(document.formulario.Hora.value != ''){
								document.getElementById('titHora').style.backgroundColor = '#C10000';
								document.getElementById('titHora').style.color='#FFF';
								document.getElementById('titHora2').style.backgroundColor = '#C10000';
								document.getElementById('titHora2').style.color='#FFF';
							}
						}
						
						document.formulario.Erro.value = Error;
						mensagens(Error);
					}
				}
				// Fim de Carregando
				carregando(false);
			}
			return true;
		}
		xmlhttp.send(null);
		
		if(document.formulario.Erro.value != "" && document.formulario.Erro.value != 0){
			return false;
		} else{
			return true;
		}
	}
	function cadastrar(acao){
		document.formulario.Acao.value = acao;
		switch(acao){
			case 'inserir':
				if(validar()){ 
					carregando(true);
					document.formulario.submit();
				}
				break;
			case 'alterar':
				if(validar()){ 
					carregando(true);
					document.formulario.submit();
				}
				break;
			case 'finalizar':
				if(validar()){ 
					carregando(true);
					document.formulario.submit();
				}
				break;
			case 'pendente':
				if(validar()){ 
					carregando(true);
					document.formulario.submit();
				}
				break;
			case 'imprimir':
				window.open("./ticket/layout/1/imprimir_help_desk.php?IdTicket="+document.formulario.IdTicket.value+"&Local=2");
				break;
			default:
				carregando(true);
				document.formulario.submit();
				break;
		}
	}
	function busca_status(IdStatusTemp){
		if(IdStatusTemp == undefined){
			IdStatusTemp = 0;
		}
		var xmlhttp   = false;
		if (window.XMLHttpRequest) { // Mozilla, Safari,...
			xmlhttp = new XMLHttpRequest();
	        if(xmlhttp.overrideMimeType){
	    //    	xmlhttp.overrideMimeType('text/xml');
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

		url = "xml/parametro_sistema.php?IdGrupoParametroSistema=128&IdParametroSistema="+IdStatusTemp;
		xmlhttp.open("GET", url,true);

		xmlhttp.onreadystatechange = function(){ 

			// Carregando...
			carregando(true);

			if(xmlhttp.readyState == 4){ 
				if(xmlhttp.status == 200){
					if(xmlhttp.responseText != 'false'){
						nameNode = xmlhttp.responseXML.getElementsByTagName("IdParametroSistema")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var IdParametroSistema = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("ValorParametroSistema")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var ValorParametroSistema = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("Cor")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var Cor = nameTextNode.nodeValue;
						
						document.formulario.IdStatusTemp.value				=	IdParametroSistema;
						document.getElementById('cp_Status').style.display	=	"block";		
						document.getElementById('cp_Status').style.color	=	Cor;		
						document.getElementById('cp_Status').innerHTML		=	ValorParametroSistema;
						
					}
				}
				// Fim de Carregando
				carregando(false);
			}
			return true;
		}
		xmlhttp.send(null);
	}
	function addMarcador(IdMarcador){
		if(document.formulario.bt_alterar.disabled != true){		
			if(document.formulario.Acao.value != 'inserir'){
				var xmlhttp = false;
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
			    
			    url = "files/editar/editar_help_desk_marcador.php?IdTicket="+document.formulario.IdTicket.value+"&IdMarcador="+IdMarcador;
				
				xmlhttp.open("GET", url,true);
			    
				// Carregando...
				carregando(true);
				
				xmlhttp.onreadystatechange = function(){ 
					if(xmlhttp.readyState == 4){ 
						if(xmlhttp.status == 200){
							if(xmlhttp.responseText != "false"){
								nameNode = xmlhttp.responseXML.getElementsByTagName("IdMarcadorAux")[0]; 
								nameTextNode = nameNode.childNodes[0];
								var IdMarcadorAux = nameTextNode.nodeValue;
								
								nameNode = xmlhttp.responseXML.getElementsByTagName("CorMarcador1")[0]; 
								nameTextNode = nameNode.childNodes[0];
								var CorMarcador1 = nameTextNode.nodeValue;
								
								nameNode = xmlhttp.responseXML.getElementsByTagName("CorMarcador2")[0]; 
								nameTextNode = nameNode.childNodes[0];
								var CorMarcador2 = nameTextNode.nodeValue;
								
								nameNode = xmlhttp.responseXML.getElementsByTagName("CorMarcador3")[0]; 
								nameTextNode = nameNode.childNodes[0];
								var CorMarcador3 = nameTextNode.nodeValue;
								
								if(IdMarcadorAux != 2){
									document.getElementById('mVermelho').style.backgroundColor	=	CorMarcador1;
									document.getElementById('mAmarelo').style.backgroundColor	=	CorMarcador2;
									document.getElementById('mVerde').style.backgroundColor		=	CorMarcador3;
									document.formulario.IdMarcador.value						=	IdMarcadorAux;
									
									if(IdMarcadorAux != '1' && IdMarcadorAux != '22' && IdMarcadorAux != '3'){
										document.formulario.IdMarcador.value	=	'';
									}
								}else{
									document.formulario.Erro.value	=	parseInt(IdMarcadorAux);
									
									mensagens(document.formulario.Erro.value);
								}
							}
						}
						// Fim de Carregando
						carregando(false);
					}
					return true;
				}
				xmlhttp.send(null);	
			}else{
				if(document.formulario.IdMarcador.value == IdMarcador){
					IdMarcador = 0;
				}
			
				switch(IdMarcador){
					case '2':
						document.getElementById('mVermelho').style.backgroundColor	=	'#FFD9D9';
						document.getElementById('mAmarelo').style.backgroundColor	=	'#F9F900';
						document.getElementById('mVerde').style.backgroundColor		=	'#D5FFD5';
						document.formulario.IdMarcador.value	=	IdMarcador;
						break;
					case '3':
						document.getElementById('mVermelho').style.backgroundColor	=	'#FFD9D9';
						document.getElementById('mAmarelo').style.backgroundColor	=	'#FFFFCE';
						document.getElementById('mVerde').style.backgroundColor		=	'#008000';
						document.formulario.IdMarcador.value	=	IdMarcador;
						break;
					case '1':
						document.getElementById('mVermelho').style.backgroundColor	=	'#FF0000';
						document.getElementById('mAmarelo').style.backgroundColor	=	'#FFFFCE';
						document.getElementById('mVerde').style.backgroundColor		=	'#D5FFD5';
						document.formulario.IdMarcador.value	=	IdMarcador;
						break;
					default:
						document.getElementById('mVermelho').style.backgroundColor	=	'#FFD9D9';
						document.getElementById('mAmarelo').style.backgroundColor	=	'#FFFFCE';
						document.getElementById('mVerde').style.backgroundColor		=	'#D5FFD5';
						document.formulario.IdMarcador.value	=	'';
						break;
				}
			}
		}
	}
	function busca_subtipo_help_desk(IdTipoTicket,IdSubTipoTicketTemp,campo){
		if(IdTipoTicket == undefined || IdTipoTicket==''){
			IdTipoTicket = 0;
		}
		if(IdSubTipoTicketTemp == undefined){
			IdSubTipoTicketTemp = '';
		}
		if(campo == undefined){
			campo = document.formulario.IdSubTipoTicket;
		}
		var xmlhttp = false;
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
	    
	    url = "xml/help_desk_subtipo.php?IdTipoTicket="+IdTipoTicket;
		
		xmlhttp.open("GET", url,true);
	    
		// Carregando...
		carregando(true);
		
		xmlhttp.onreadystatechange = function(){ 
			if(xmlhttp.readyState == 4){ 
				if(xmlhttp.status == 200){
					if(xmlhttp.responseText != 'false'){		
						while(campo.options.length > 0){
							campo.options[0] = null;
						}
						
						var nameNode, nameTextNode, IdSubTipoTicket;
						addOption(campo,"","");
						
						for(var i=0; i<xmlhttp.responseXML.getElementsByTagName("IdSubTipoHelpDesk").length; i++){
							nameNode = xmlhttp.responseXML.getElementsByTagName("IdSubTipoHelpDesk")[i]; 
							nameTextNode = nameNode.childNodes[0];
							var IdSubTipoHelpDesk = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoSubTipoHelpDesk")[i]; 
							nameTextNode = nameNode.childNodes[0];
							var DescricaoSubTipoHelpDesk = nameTextNode.nodeValue;
						
							addOption(campo,DescricaoSubTipoHelpDesk,IdSubTipoHelpDesk);
						}
						
						if(IdSubTipoTicketTemp!=""){
							for(i=0;i<campo.length;i++){
								if(campo[i].value == IdSubTipoTicketTemp){
									campo[i].selected	=	true;
									break;
								}
							}
						}else{
							campo[0].selected	=	true;
						}						
					}else{
						while(campo.options.length > 0){
							campo.options[0] = null;
						}
						addOption(campo,"","");
					}
				}
				// Fim de Carregando
				carregando(false);
			}
			return true;
		}
		xmlhttp.send(null);	
	}
	function alualizar_filtro_subtipo_help_desk(IdTipoTicket,IdSubTipoTicketTemp){
		if(IdTipoTicket == undefined || IdTipoTicket==''){
			IdTipoTicket = 0;
		}
		if(IdSubTipoTicketTemp == undefined){
			IdSubTipoTicketTemp = '';
		}
		var xmlhttp = false;
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
	    
	    url = "xml/help_desk_subtipo.php?IdTipoTicket="+IdTipoTicket;
		xmlhttp.open("GET", url,true);
		xmlhttp.onreadystatechange = function(){
			if(xmlhttp.readyState == 4){ 
				if(xmlhttp.status == 200){
					if(xmlhttp.responseText != 'false'){		
						while(document.filtro.filtro_sub_tipo.options.length > 0){
							document.filtro.filtro_sub_tipo.options[0] = null;
						}
						
						var nameNode, nameTextNode, IdSubTipoTicket;
						addOption(document.filtro.filtro_sub_tipo,"Todos","");
						
						for(var i=0; i<xmlhttp.responseXML.getElementsByTagName("IdSubTipoHelpDesk").length; i++){
							nameNode = xmlhttp.responseXML.getElementsByTagName("IdSubTipoHelpDesk")[i]; 
							nameTextNode = nameNode.childNodes[0];
							var IdSubTipoHelpDesk = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoSubTipoHelpDesk")[i]; 
							nameTextNode = nameNode.childNodes[0];
							var DescricaoSubTipoHelpDesk = nameTextNode.nodeValue;
							
							addOption(document.filtro.filtro_sub_tipo,DescricaoSubTipoHelpDesk,IdSubTipoHelpDesk);
						}
						
						if(IdSubTipoTicketTemp!=""){
							for(i=0;i<document.filtro.filtro_sub_tipo.length;i++){
								if(document.filtro.filtro_sub_tipo[i].value == IdSubTipoTicketTemp){
									document.filtro.filtro_sub_tipo[i].selected	=	true;
									break;
								}
							}
						}else{
							document.filtro.filtro_sub_tipo[0].selected	=	true;
						}						
					}else{
						while(document.filtro.filtro_sub_tipo.options.length > 0){
							document.filtro.filtro_sub_tipo.options[0] = null;
						}
						addOption(document.filtro.filtro_sub_tipo,"Todos","");
					}
					
				}
			}
			return true;
		}
		xmlhttp.send(null);	
	}
	function alualizar_filtro_usuario_help_desk(Campo, Usuario){
		if(Usuario == undefined){
			Usuario = '';
		}
		var xmlhttp = false;
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
	    
	    url = "xml/help_desk_usuario.php?IdStatus=1";
		xmlhttp.open("GET", url,true);
		xmlhttp.onreadystatechange = function(){
			if(xmlhttp.readyState == 4){ 
				if(xmlhttp.status == 200){
					if(xmlhttp.responseText != 'false'){		
						while(document.filtro.filtro_valor.options.length > 0){
							document.filtro.filtro_valor.options[0] = null;
						}
						
						var nameNode, nameTextNode, Login;
						addOption(document.filtro.filtro_valor,"Todos","");
						
						for(var i=0; i<xmlhttp.responseXML.getElementsByTagName("Login").length; i++){
							nameNode = xmlhttp.responseXML.getElementsByTagName("Login")[i]; 
							nameTextNode = nameNode.childNodes[0];
							Login = nameTextNode.nodeValue;
							
							if(Campo != ''){
								addOption(document.filtro.filtro_valor,Login,Login);
							}
						}
						
						if(Campo!="" && Usuario!=""){
							for(i=0;i<document.filtro.filtro_valor.length;i++){
								if(document.filtro.filtro_valor[i].value == Usuario){
									document.filtro.filtro_valor[i].selected	=	true;
									break;
								}
							}
						}else{
							document.filtro.filtro_valor[0].selected	=	true;
						}						
					}else{
						while(document.filtro.filtro_valor.options.length > 0){
							document.filtro.filtro_valor.options[0] = null;
						}
						addOption(document.filtro.filtro_valor,"Todos","");
					}
					
				}
			}
			return true;
		}
		xmlhttp.send(null);	
	}
	function busca_help_desk_historico(IdTicket,Erro,IdStatusTemp,direcScroll){
		if(IdTicket == undefined || IdTicket==''){
			IdTicket = 0;
		}
		
		var nameNode, nameTextNode, url, Condicao;
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
	    
	   	url = "../administrativo/xml/help_desk_historico.php?IdTicket="+IdTicket;
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
						document.getElementById('cp_help_desk_historico').style.display = "none";
						
						// Fim de Carregando
						carregando(false);
					}else{
						document.getElementById('cp_help_desk_historico').style.display = "block";	
						while(document.getElementById('tabelaHelpDeskHistorico').rows.length > 1){
							document.getElementById('tabelaHelpDeskHistorico').deleteRow(1);
						}
						
						var cabecalho, tam, linha, c0;
						for(var i=0; i<xmlhttp.responseXML.getElementsByTagName("IdTicketHistorico").length; i++){	
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("IdTicketHistorico")[i]; 
							nameTextNode = nameNode.childNodes[0];
							var IdTicketHistorico = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("Anexo")[i]; 
							nameTextNode = nameNode.childNodes[0];
							var Anexo = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("Status")[i]; 
							nameTextNode = nameNode.childNodes[0];
							var Status = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("Cor")[i]; 
							nameTextNode = nameNode.childNodes[0];
							var Cor = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("Assunto")[i]; 
							nameTextNode = nameNode.childNodes[0];
							var Assunto = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("Obs")[i]; 
							nameTextNode = nameNode.childNodes[0];
							var Obs = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("Publica")[i]; 
							nameTextNode = nameNode.childNodes[0];
							var Publica = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("Marcar")[i]; 
							nameTextNode = nameNode.childNodes[0];
							var Marcar = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("Aberta")[i]; 
							nameTextNode = nameNode.childNodes[0];
							var Aberta = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("DataCriacao")[i]; 
							nameTextNode = nameNode.childNodes[0];
							var DataCriacao = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("LoginCriacao")[i]; 
							nameTextNode = nameNode.childNodes[0];
							var LoginCriacao = nameTextNode.nodeValue;
							
							if(Publica == 1){
								UrlImagem 	= "../../img/estrutura_sistema/ico_cadedado_c.gif";
								Title 		= "Pública Sim";
							} else{
								UrlImagem 	= "../../img/estrutura_sistema/ico_cadedado.gif";
								Title 		= "Pública Não";
							}
							
							if(Marcar == 1){
								UrlIMGDes	= "../../img/estrutura_sistema/ico_estrela.gif";
								TitleIMGDes	= "Desmarcar";
								Anexo = Anexo.replace(/#000/i, "#fff");
								CorBackground = "#71B5FF";
								CorText = "#fff";
							} else{
								UrlIMGDes 	= "../../img/estrutura_sistema/ico_estrela_c.gif";
								TitleIMGDes	= "Marcar";
								CorBackground = Cor;
								CorText = "#000";
							}
							
							if(Aberta == 1){
								UrlIMGOct	= "../../img/estrutura_sistema/ico_seta_up.gif";
								TitleIMGOct	= "Minimizar";
								Aberta = "block";
							} else{
								UrlIMGOct 	= "../../img/estrutura_sistema/ico_seta_down.gif";
								TitleIMGOct	= "Maximizar";
								Aberta = "none";
							}
							
							tam 	= document.getElementById('tabelaHelpDeskHistorico').rows.length;
							linha	= document.getElementById('tabelaHelpDeskHistorico').insertRow(tam-1);
							
							linha.accessKey = IdTicketHistorico+"_0"; 
							
							c0					= linha.insertCell(0);
							c0.style.height		= "14px";
							
							tam 	= document.getElementById('tabelaHelpDeskHistorico').rows.length;
							linha	= document.getElementById('tabelaHelpDeskHistorico').insertRow(tam-1);
							
							linha.accessKey = IdTicketHistorico+"_1"; 
							linha.style.backgroundColor = CorBackground;
							linha.style.color = CorText;
							Obs = Obs.replace(/  /g,"&nbsp;&nbsp;");
							Obs = Obs.replace(/\n|\r\n/g,"<br />");
							
							var msg = "<div id='obs_"+IdTicketHistorico+"' style='display:"+Aberta+";'>"+Obs+"<div id='bloco_anexo_"+IdTicketHistorico+"_1'>"+Anexo+"</div>"+"</div>";
							var temp_title = msg.replace(/'/g,"&amp;#39;");
							
							temp_title = temp_title.replace(/.gif&amp;#39;/g,".gif\\'");
							temp_title = temp_title.replace(/<img src=&amp;#39;..\/..\/img\/estrutura_sistema\/ico_/g,"<img src=\\'../../img/estrutura_sistema/ico_");
							temp_title = temp_title.replace(/"/g,"&quot;");
							
							cabecalho = "<div style='height: 14px; margin-bottom: 2px;'><div style='float:left;' onmousemove=\"quadro_alt(event, this, '"+temp_title+"', 'document.getElementById(\\'obs_"+IdTicketHistorico+"\\').style.display == \\'none\\'');\"><b>Data:</b> "+dateFormat(DataCriacao)+"</div><div style='float:right; width:303px; vertical-align:top;'><div style='float:left;' onmousemove=\"quadro_alt(event, this, '"+temp_title+"', 'document.getElementById(\\'obs_"+IdTicketHistorico+"\\').style.display == \\'none\\'');\"><b>Status:</b> "+Status+"</div>";
							
							c0					= linha.insertCell(0);
							
							if(IdTicketHistorico > 1){
								cabecalho += "<div style='float:right; margin-bottom:-4px;'>&nbsp; &nbsp; <img src='"+UrlIMGDes+"' title='"+TitleIMGDes+"' alt='"+TitleIMGDes+"' id='marcar_"+IdTicketHistorico+"' onClick='marcar("+IdTicket+","+IdTicketHistorico+");' />&nbsp;<img id='publica_"+IdTicket+"_"+IdTicketHistorico+"' src='"+UrlImagem+"' title='"+Title+"' onclick='publica_help_desk_historico("+IdTicket+","+IdTicketHistorico+")' />&nbsp;<img src='"+UrlIMGOct+"' title='"+TitleIMGOct+"' alt='"+TitleIMGOct+"' style='margin-top:2px;' onClick='aberta("+IdTicket+","+IdTicketHistorico+",this,\"obs_"+IdTicketHistorico+"\");'/></div>";
							} else{
								cabecalho += "<div style='float:right; margin-bottom:-4px;'>&nbsp; &nbsp; <img src='"+UrlIMGDes+"' title='"+TitleIMGDes+"' alt='"+TitleIMGDes+"' id='marcar_"+IdTicketHistorico+"' onClick='marcar("+IdTicket+","+IdTicketHistorico+");' />&nbsp;<img src='"+UrlIMGOct+"' title='"+TitleIMGOct+"' alt='"+TitleIMGOct+"' style='margin-top:2px;' onClick='aberta("+IdTicket+","+IdTicketHistorico+",this,\"obs_"+IdTicketHistorico+"\");'/></div>";
							}
							
							cabecalho += "</div></div>";
							
							c0.innerHTML		= cabecalho+msg;
							
							c0.style.textAlign	= "justify";
							c0.style.padding	= "2px 5px 4px 5px";
						}
						if(direcScroll){
							scrollWindow('bottom');
						}
					}
				}
				// Fim de Carregando
				carregando(false);
			} 
			return true;
		}
		xmlhttp.send(null);
	}
	function marcar(IdTicket, IdTicketHistorico){
		var nameNode, nameTextNode, url;
		var xmlhttp = false;
		
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
		
		url = "../administrativo/files/editar/editar_help_desk_marcar.php?IdTicket="+IdTicket+"&IdTicketHistorico="+IdTicketHistorico;
		xmlhttp.open("GET", url,true);
		xmlhttp.onreadystatechange = function(){
			// Carregando...
			carregando(true);
			
			if(xmlhttp.readyState == 4){
				if(xmlhttp.status == 200){
					if(xmlhttp.responseText != 'false'){
						nameNode = xmlhttp.responseXML.getElementsByTagName("Marcar")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var Marcar = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("Cor")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var Cor = nameTextNode.nodeValue;
						
						for(var i=0; i < document.getElementById('tabelaHelpDeskHistorico').rows.length; i++){
							if(IdTicketHistorico+"_1" == document.getElementById('tabelaHelpDeskHistorico').rows[i].accessKey){
								var textHTML = document.getElementById("bloco_anexo_"+IdTicketHistorico+"_1").innerHTML;
								
								if(Marcar == 1){
									document.getElementById('tabelaHelpDeskHistorico').rows[i].style.backgroundColor = "#71B5FF";
									document.getElementById('tabelaHelpDeskHistorico').rows[i].style.color = "#fff";
									document.getElementById("marcar_"+IdTicketHistorico).src = "../../img/estrutura_sistema/ico_estrela.gif";
									document.getElementById("marcar_"+IdTicketHistorico).title = "Desmarcar";
									document.getElementById("marcar_"+IdTicketHistorico).alt = "Desmarcar";
									
									if(textHTML){
										var temp0 = textHTML.split("color:");
										var temp1 = temp0[1].split(";");
										textHTML = temp0[0]+"color:#fff;"+temp1[1];
									}
								} else{
									document.getElementById('tabelaHelpDeskHistorico').rows[i].style.backgroundColor = Cor;
									document.getElementById('tabelaHelpDeskHistorico').rows[i].style.color = "#000";
									document.getElementById("marcar_"+IdTicketHistorico).src = "../../img/estrutura_sistema/ico_estrela_c.gif";
									document.getElementById("marcar_"+IdTicketHistorico).title = "Marcar";
									document.getElementById("marcar_"+IdTicketHistorico).alt = "Marcar";
									
									if(textHTML){
										var temp0 = textHTML.split("color:");
										var temp1 = temp0[1].split(";");
										textHTML = temp0[0]+"color:#000;"+temp1[1];
									}
								}
								
								document.getElementById("bloco_anexo_"+IdTicketHistorico+"_1").innerHTML = textHTML;
								break;
							}
						}
					}
				}
				// Fim de Carregando
				carregando(false);
			}
			
			return true;
		}
		
		xmlhttp.send(null);
	}
	function aberta(IdTicket, IdTicketHistorico, botao, id){
		var nameNode, nameTextNode, url;
		var xmlhttp = false;
		
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
		
		url = "../administrativo/files/editar/editar_help_desk_aberta.php?IdTicket="+IdTicket+"&IdTicketHistorico="+IdTicketHistorico;
		xmlhttp.open("GET", url,true);
		xmlhttp.onreadystatechange = function(){
			// Carregando...
			carregando(true);
			
			if(xmlhttp.readyState == 4){ 
				if(xmlhttp.status == 200){
					if(xmlhttp.responseText != 'false'){
						nameNode = xmlhttp.responseXML.getElementsByTagName("Aberta")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var Aberta = nameTextNode.nodeValue;
						
						if(Aberta == 1){
							botao.src = "../../img/estrutura_sistema/ico_seta_up.gif";
							botao.title = "Minimizar";
							botao.alt = "Minimizar";
							document.getElementById(id).style.display = "block";
						} else{
							botao.src = "../../img/estrutura_sistema/ico_seta_down.gif";
							botao.title = "Maximizar";
							botao.alt = "Maximizar";
							document.getElementById(id).style.display = "none";
						}
					}
				}
				// Fim de Carregando
				carregando(false);
			}
			
			return true;
		}
		
		xmlhttp.send(null);
	}
	function buscaVisualizar(direcScroll){
		// Carregando...
		carregando(true);
		if(document.formulario.Visualizar.value == ''){
			if(document.formulario.IdTicket.value != ''){
				busca_help_desk_historico(document.formulario.IdTicket.value,false,document.formulario.IdStatus.value,direcScroll);
				document.formulario.Visualizar.value = true;
				document.formulario.bt_visualizar.value = 'Ocultar Histórico';
				document.getElementById("cp_duplicado").style.display = 'block';
			} else{
				carregando(false);
			}
		}else{
			document.formulario.Visualizar.value = '';
			document.formulario.bt_visualizar.value = 'Visualizar Histórico';
			
			while(document.getElementById('tabelaHelpDeskHistorico').rows.length > 2){
				document.getElementById('tabelaHelpDeskHistorico').deleteRow(1);
			}
			
			document.getElementById('cp_help_desk_historico').style.display	= 'none';
			document.getElementById("cp_duplicado").style.display = 'none';
			
			// Carregando...
			carregando(false);
		}
	}
	function busca_login_usuario(IdGrupoUsuario,campo,IdUsuarioTemp){
		if(IdGrupoUsuario == ''){
			while(campo.options.length > 0){
				campo.options[0] = null;
			}
			
			if(document.filtro.filtro_usuario != undefined){
				addOption(campo,"Todos","");
			} else{
				addOption(campo,"","");
			}
			return false;
		}
		if(IdUsuarioTemp == undefined){
			IdUsuarioTemp = '';
		}
		
		url = "xml/usuario_grupo_usuario.php?IdGrupoUsuario="+IdGrupoUsuario;
		
		call_ajax(url,function (xmlhttp){
			if(xmlhttp.responseText == 'false'){
				while(campo.options.length > 0){
					campo.options[0] = null;
				}
				addOption(campo,"","");
			}else{
				while(campo.options.length > 0){
					campo.options[0] = null;
				}
				
				if(document.filtro.filtro_usuario != undefined){
					addOption(campo,"Todos","");
				}else{
					addOption(campo,"","");
				}
				
				for(var i=0; i<xmlhttp.responseXML.getElementsByTagName("Login").length; i++){
						
					var nameNode = xmlhttp.responseXML.getElementsByTagName("Login")[i]; 
					var nameTextNode = nameNode.childNodes[0];
					var Login = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("NomeUsuario")[i]; 
					nameTextNode = nameNode.childNodes[0];
					var NomeUsuario = nameTextNode.nodeValue;
					
					var Descricao	=	NomeUsuario.substr(0,50);	
					
					addOption(campo,Descricao,Login);
				}
				
				
				if(IdUsuarioTemp!=''){
					for(ii=0;ii<campo.length;ii++){
						if(campo[ii].value == IdUsuarioTemp){
							campo[ii].selected = true;
							break;
						}
					}
				}else{					
					campo[0].selected = true;
				}
			}
		});
	}
	function addPrioridade(campo){
		if(document.formulario.bt_alterar.disabled != true){
			if(document.formulario.PermitirEditarPrioridade.value == "1"){
				if(document.formulario.Acao.value != 'inserir'){
					var xmlhttp = false;
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
					
					url = "files/editar/editar_help_desk_prioridade.php?IdTicket="+document.formulario.IdTicket.value;
					
					xmlhttp.open("GET", url,true);
					
					// Carregando...
					carregando(true);
					
					xmlhttp.onreadystatechange = function(){ 
						if(xmlhttp.readyState == 4){ 
							if(xmlhttp.status == 200){
								if(xmlhttp.responseText != "false"){
									nameNode = xmlhttp.responseXML.getElementsByTagName("IdPrioridade")[0]; 
									nameTextNode = nameNode.childNodes[0];
									var IdPrioridade = nameTextNode.nodeValue;
									
									nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoPrioridade")[0]; 
									nameTextNode = nameNode.childNodes[0];
									var DescricaoPrioridade = nameTextNode.nodeValue;
									
									document.getElementById("nivelPrioridade").src		= "../../img/estrutura_sistema/prioridade"+IdPrioridade+".gif";
									document.getElementById("nivelPrioridade").title	= DescricaoPrioridade;
								}
							}
							// Fim de Carregando
							carregando(false);
						}
						return true;
					}
					xmlhttp.send(null);	
				}
			}else{
				mensagens(2);
				return false;
			}
		}
	}
	function publica_help_desk_historico(IdTicket,IdTicketHistorico){
		if(confirm("ATENCAO!\n\nVoce esta prestes a alterar este registro.\nDeseja continuar?","SIM","NAO")){
			if(IdTicket == undefined || IdTicket==''){
				IdTicket = 0;
			}
			
			var nameNode, nameTextNode, url, Condicao;
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
		    
		   	url = "files/editar/editar_help_desk_publica.php?IdTicket="+IdTicket+"&IdTicketHistorico="+IdTicketHistorico;
			xmlhttp.open("GET", url,true);
	
			xmlhttp.onreadystatechange = function(){ 
		
				// Carregando...
				carregando(true);
		
				if(xmlhttp.readyState == 4){ 
					if(xmlhttp.status == 200){	
						if(xmlhttp.responseText != 'false'){
							nameNode = xmlhttp.responseXML.getElementsByTagName("Publica")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var Publica = nameTextNode.nodeValue;			
							
							if(Publica == 1){
								document.getElementById("publica_"+IdTicket+"_"+IdTicketHistorico).src = "../../img/estrutura_sistema/ico_cadedado_c.gif";
								document.getElementById("publica_"+IdTicket+"_"+IdTicketHistorico).title = "Pública Sim";
							}else{
								document.getElementById("publica_"+IdTicket+"_"+IdTicketHistorico).src = "../../img/estrutura_sistema/ico_cadedado.gif";
								document.getElementById("publica_"+IdTicket+"_"+IdTicketHistorico).title = "Pública Não";
							}
													
						}
					}
					// Fim de Carregando
					carregando(false);
				} 
				return true;
			}
			xmlhttp.send(null);
		}
	}
	function addArquivo(IdArquivo){
		document.formulario.CountArquivo.value = parseInt(document.formulario.CountArquivo.value) + parseInt(1);
		
		if(IdArquivo=='' || IdArquivo == undefined){
			IdArquivo = '';	
		}
		
		var CountArquivo = parseInt(document.formulario.CountArquivo.value);
		var tam, linha, c0, c1, c2, c3, c4;
		var tabindex = CountArquivo+7;
		
		if((CountArquivo > document.formulario.MaxUploads.value && document.formulario.MaxUploads.value != '') || document.formulario.AnexarArquivo.value == 0){
			return false;
		}
		
		tam 	= document.getElementById('tabelaArquivos').rows.length;
		linha	= document.getElementById('tabelaArquivos').insertRow(tam);
		linha.accessKey = tam;
		
		c0	= linha.insertCell(0);
		c1	= linha.insertCell(1);
		c2	= linha.insertCell(2);
		c3	= linha.insertCell(3);
		c4	= linha.insertCell(4);
		
		c0.className	= "find";		
		c0.innerHTML	= "&nbsp;";
		c1.className	= "descCampo";
		c1.innerHTML	= "Anexar Arquivo";
		c2.className	= "separador";
		c2.innerHTML	= "&nbsp;";
		c3.className	= "descCampo";
		c3.innerHTML	= "<B id='titDescricaoArquivo_"+CountArquivo+"' style='color:#000;'>Descrição do Arquivo</B>";
		c4.className	= "find";
		c4.innerHTML	= "&nbsp;";
		
		tam		= document.getElementById('tabelaArquivos').rows.length;
		linha	= document.getElementById('tabelaArquivos').insertRow(tam);
		linha.accessKey = tam;
		
		c0	= linha.insertCell(0);
		c1	= linha.insertCell(1);
		c2	= linha.insertCell(2);
		c3	= linha.insertCell(3);
		c4	= linha.insertCell(4);
		
		c0.className			= "find";
		c0.innerHTML			= "&nbsp;";
		c1.className			= "campo";
		c1.style.width			= "450px";
		c1.innerHTML			= "<input type='text' name='fakeupload_"+CountArquivo+"' value='' autocomplete='off' style='width:356px; margin:0px;' onchange='verificar_obrigatoriedade(this, document.formulario.DescricaoArquivo_"+CountArquivo+", "+CountArquivo+");' onFocus=\"Foco(this,'in','auto');\" onBlur=\"Foco(this,'out');\" tabindex='"+tabindex+"'><div id='bt_procurar' style='margin:-22px 0px 0px 360px;' tabindex='"+tabindex+"'></div><input type='file' id='realupload' name='EndArquivo_"+CountArquivo+"' size='1' class='realupload' onchange='verificar_obrigatoriedade(this, document.formulario.DescricaoArquivo_"+CountArquivo+", "+CountArquivo+"); document.formulario.fakeupload_"+CountArquivo+".value = this.value;' /><div style='margin:-1px 0px 4px 0px;'>Atenção, tamanho máximo do arquivo é "+document.formulario.MaxSize.value+". <span title='"+document.formulario.ExtensaoAnexo.value.replace(/,/g, ', ')+".'>Tipos de arquivo a anexar.<span></div>";
		c2.className			= "separador";
		c2.innerHTML			= "&nbsp;";
		c3.className			= "campo";
		c3.style.verticalAlign	= "top";
		c3.innerHTML			= "<input type='text' name='DescricaoArquivo_"+CountArquivo+"' value='' style='width:338px; margin-top:0px;' tabindex='"+tabindex+"' maxlength='100' onFocus=\"Foco(this,'in');\"  onBlur=\"Foco(this,'out');\" readOnly>";
		c4.className			= "find";
		c4.style.verticalAlign	= "top";
		c4.innerHTML			= "<img src='../../img/estrutura_sistema/ico_del.gif' style='margin-top:6px;' alt='Excluir title='Excluir' onClick=\"excluir_arquivo('"+CountArquivo+"','"+IdArquivo+"');\">";
	}
	function verificar_obrigatoriedade(campo1, campo2, CountArquivo){
		if(campo1.value != ''){
			var temp = campo1.value.split('.');
			var ext = temp[temp.length-1].toLowerCase();
			document.formulario.Erro.value = 0;
			
			if(!document.formulario.ExtensaoAnexo.value.split(',').in_array(ext) && ext != ''){
				for(i = 0; i<document.formulario.length; i++){
					if(document.formulario[i].name != undefined){
						if(document.formulario[i].name == campo1.name){
							document.formulario.Erro.value = 192;
							
							document.formulario[i-1].focus();
						}
					}
				}
			} else{
				for(i = 0; i<document.formulario.length; i++){
					if(document.formulario[i].name != undefined){
						if(document.formulario[i].name.substring(0,11) == 'EndArquivo_'){
							var temp = document.formulario[i].value.split('.');
							var ext = temp[temp.length-1].toLowerCase();
							
							if(!document.formulario.ExtensaoAnexo.value.split(',').in_array(ext) && ext != ''){
								mensagens(192);
								document.formulario[i-1].focus();
							}
						}
					}
				}
			}
			
			document.getElementById("titDescricaoArquivo_"+CountArquivo).style.color = "#C10000";
			campo2.readOnly = false;
			verificaErro();
		} else{
			document.getElementById("titDescricaoArquivo_"+CountArquivo).style.color = "#000000";
			campo2.readOnly = true;
			campo2.value = '';
		}
	}
	function excluir_arquivo(pos, IdArquivo){
		if(document.formulario.AnexarArquivo.value == 0){
			return;
		}
		
		if(confirm("ATENÇÃO\n\nVocê está prestes a excluir este arquivo.\nDeseja continuar?") == false){
			return false;
		}	
		
		var tam = document.getElementById('tabelaArquivos').rows.length;
		var cont = 1;
		
		for(var i=1; i<=tam; i++){
			if(document.getElementById('tabelaArquivos').rows[i].accessKey == (pos*2)-1){
				document.getElementById('tabelaArquivos').deleteRow(i);
				document.getElementById('tabelaArquivos').deleteRow(i-1);
				i=tam;
				document.formulario.CountArquivo.value	= parseInt(document.formulario.CountArquivo.value) - 1;
				break;
			}
		}
		
		if(parseInt(document.formulario.CountArquivo.value) == 0){
			addArquivo(null);
		}
	}
	function limpar_campo(valor){
		if(valor != document.formulario.LoginAtendimentoTemp.value){
			document.formulario.LoginAtendimentoTemp.value = valor;
			document.formulario.Data.value = '';
			document.formulario.Hora.value = '';
			document.formulario.Data2.value = '';
			document.formulario.Hora2.value = '';
			document.formulario.TicketDia.value = '0';
			document.formulario.TicketDia2.value = '0';
			
			validar_Data("titData", document.formulario.Data);
			validar_Time("titHora", document.formulario.Hora);
			validar_Data("titData2", document.formulario.Data);
			validar_Time("titHora2", document.formulario.Hora);
		}
	}
	function verificaErro(){
		var nerro = parseInt(document.formulario.Erro.value);
		mensagens(nerro,document.formulario.Local.value);
	}
	function help(msg,prioridade){
		if(msg != ''){
			scrollWindow("bottom");
		}

		document.getElementById('helpText').innerHTML = msg;
		document.getElementById('helpText2').innerHTML = msg;
		document.getElementById('helpText').style.display = "block";
		document.getElementById('helpText2').style.display = "block";
		switch (prioridade){
			case 'atencao':
				document.getElementById('helpText').style.color = "#C10000";
				document.getElementById('helpText2').style.color = "#C10000";
				return true;
			default:
				document.getElementById('helpText').style.color = "#004975";
				document.getElementById('helpText2').style.color = "#004975";
				return true;
		}
	}
	function verifica_change_log(value){
		if(value != ''){
			if(document.formulario.IdStatusTemp.value == ''){
				//setTimeout("verifica_change_log('"+value+"');",200);
				//return;
			}
			
			document.getElementById("tit_cpMensagem").style.color = "#000";
			document.getElementById("tit_cpMensagem2").style.color = "#000";
			document.formulario.Mensagem.value = '';
			document.formulario.Mensagem.readOnly = true;
			document.formulario.Mensagem2.value = '';
			document.formulario.Mensagem2.readOnly = true;
			busca_login_usuario(document.formulario.IdGrupoUsuarioAtendimentoTemp.value,document.formulario.LoginAtendimento);
			document.formulario.IdGrupoUsuarioAtendimento.value = document.formulario.IdGrupoUsuarioAtendimentoTemp.value;
			document.formulario.LoginAtendimento.value = document.formulario.LoginAtendimentoTemp.value;
			document.formulario.IdGrupoUsuarioAtendimento.disabled = true;
			document.formulario.LoginAtendimento.disabled = true;
			document.formulario.IdStatus.value = document.formulario.IdStatusTemp.value;
			document.getElementById("titStatusNovo").style.color = "#000";
			document.formulario.IdStatus.disabled = true;
			document.getElementById("titAssunto").style.color = "#000";
			document.getElementById("titAssunto2").style.color = "#000";
			document.getElementById("titPublica").style.color = "#000";
			document.getElementById("titPublica2").style.color = "#000";
			document.formulario.Assunto.value = document.formulario.AssuntoTemp.value;
			document.formulario.Assunto2.value = document.formulario.AssuntoTemp.value;
			document.formulario.Publica.value = document.formulario.IdPublicaDefault.value;
			document.formulario.Publica2.value = document.formulario.IdPublicaDefault.value;
			document.formulario.Assunto.readOnly = true;
			document.formulario.Assunto2.readOnly = true;
			document.formulario.Publica.disabled = true;
			document.formulario.Publica2.disabled = true;
			document.getElementById("titTipo").style.color = "#000";
			document.getElementById("titTipo2").style.color = "#000";
			document.getElementById("titSubTipo").style.color = "#000";
			document.getElementById("titSubTipo2").style.color = "#000";
			
			if(document.formulario.IdSubTipoTicket2.length > 1 && document.formulario.IdTipoTicket.value == document.formulario.IdTipoTicketTemp.value){
				document.formulario.IdSubTipoTicket.value = document.formulario.IdSubTipoTicketTemp.value;
				document.formulario.IdSubTipoTicket2.value = document.formulario.IdSubTipoTicketTemp.value;
			} else{
				busca_subtipo_help_desk(document.formulario.IdTipoTicketTemp.value,document.formulario.IdSubTipoTicketTemp);
				busca_subtipo_help_desk(document.formulario.IdTipoTicketTemp.value,document.formulario.IdSubTipoTicketTemp,document.formulario.IdSubTipoTicket2);
			}
			
			document.formulario.IdTipoTicket.value = document.formulario.IdTipoTicketTemp.value;
			document.formulario.IdTipoTicket2.value = document.formulario.IdTipoTicketTemp.value;
			document.formulario.IdTipoTicket.disabled = true;
			document.formulario.IdTipoTicket2.disabled = true;
			document.formulario.IdSubTipoTicket.disabled = true;
			document.formulario.IdSubTipoTicket2.disabled = true;
			document.getElementById("titData").style.color = "#000";
			document.getElementById("titData").style.backgroundColor = "#fff";
			document.getElementById("titData2").style.color = "#000";
			document.getElementById("titData2").style.backgroundColor = "#fff";
			document.getElementById("titHora").style.color = "#000";
			document.getElementById("titHora").style.backgroundColor = "#fff";
			document.getElementById("titHora2").style.color = "#000";
			document.getElementById("titHora2").style.backgroundColor = "#fff";
			document.formulario.Data.value = document.formulario.DataTemp.value;
			document.formulario.Data2.value = document.formulario.DataTemp.value;
			document.formulario.Hora.value = document.formulario.HoraTemp.value;
			document.formulario.Hora2.value = document.formulario.HoraTemp.value;
			document.formulario.Data.readOnly = true;
			document.formulario.Data2.readOnly = true;
			document.formulario.Hora.readOnly = true;
			document.formulario.Hora2.readOnly = true;
			document.getElementById("blAnexoArquivo").style.display = "none";
		} else{
			document.getElementById("tit_cpMensagem").style.color = "#c10000";
			document.getElementById("tit_cpMensagem2").style.color = "#c10000";
			document.formulario.Mensagem.readOnly = false;
			document.formulario.Mensagem2.readOnly = false;
			document.formulario.IdGrupoUsuarioAtendimento.disabled = false;
			document.formulario.LoginAtendimento.disabled = false;
			document.formulario.IdStatus.value = document.formulario.IdStatusNovoTemp.value;
			document.formulario.IdStatus.disabled = false;
			document.getElementById("titStatusNovo").style.color = "#c10000";
			document.getElementById("titAssunto").style.color = "#c10000";
			document.getElementById("titAssunto2").style.color = "#c10000";
			document.getElementById("titPublica").style.color = "#c10000";
			document.getElementById("titPublica2").style.color = "#c10000";
			document.formulario.Assunto.readOnly = false;
			document.formulario.Assunto2.readOnly = false;
			document.formulario.Publica.disabled = false;
			document.formulario.Publica2.disabled = false;
			document.getElementById("titTipo").style.color = "#c10000";
			document.getElementById("titTipo2").style.color = "#c10000";
			document.getElementById("titSubTipo").style.color = "#c10000";
			document.getElementById("titSubTipo2").style.color = "#c10000";
			document.formulario.IdTipoTicket.disabled = false;
			document.formulario.IdTipoTicket2.disabled = false;
			document.formulario.IdSubTipoTicket.disabled = false;
			document.formulario.IdSubTipoTicket2.disabled = false;
			document.formulario.Data.readOnly = false;
			document.formulario.Data2.readOnly = false;
			document.formulario.Hora.readOnly = false;
			document.formulario.Hora2.readOnly = false;
			document.getElementById("blAnexoArquivo").style.display = "block";
		}
	}
	function verifica_previsao_etapa(){
		var Data = document.formulario.Data.value;
		var Hora = document.formulario.Hora.value;
		var DataAtual = parseInt(document.formulario.DataAtualTemp.value);
		var DataPrevisao = Data.substring(6,10)+Data.substring(3,5)+Data.substring(0,2)+Hora.replace(/:/g,"");
		
		if((DataAtual > parseInt(DataPrevisao) && DataPrevisao.length == 12) || document.formulario.Acao.value == "inserir"){
			document.formulario.bt_alterar.disabled = true;
			document.formulario.bt_alterar2.disabled = true;
		} else{
			document.formulario.bt_alterar.disabled = false;
			document.formulario.bt_alterar2.disabled = false;
		}
	}
	function limpa_data_hora(IdStatus){
		if(IdStatus == '200'){
			document.formulario.Data.value  = '';
			document.formulario.Hora.value  = '';
			document.formulario.Data2.value = '';
			document.formulario.Hora2.value = '';
			document.formulario.TicketDia.value = '0';
			document.formulario.TicketDia2.value = '0';
			
			validar_Data("titData", document.formulario.Data);
			validar_Time("titHora", document.formulario.Hora);
			validar_Data("titData2", document.formulario.Data);
			validar_Time("titHora2", document.formulario.Hora);
		}
	}
	function ticket_dia(Data,IdGrupoUsuario,LoginResponsavel,event){
		var url = "xml/help_desk_ticket_dia.php?Data="+Data+"&IdGrupoUsuario="+IdGrupoUsuario+"&LoginResponsavel="+LoginResponsavel;
		event="";
		if(event == undefined){
			event = document.formulario.TicketDia.event;	
		}
		
		call_ajax(url, function (xmlhttp) {
			if(xmlhttp.responseText != 'false'){
				var nameNode = xmlhttp.responseXML.getElementsByTagName("TicketDia")[0]; 
				var nameTextNode = nameNode.childNodes[0];
				var TicketDia = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("TicketLista")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var TicketLista = nameTextNode.nodeValue;
				
				document.formulario.TicketDia.value = TicketDia;				
				document.formulario.TicketDia2.value = TicketDia;	
				document.formulario.TicketLista.value = TicketLista;
			}else{				
				document.formulario.TicketLista.value = '';
				document.formulario.TicketDia.value = 0;				
				document.formulario.TicketDia2.value = 0;			
			}
		});
	}

	function atualizar_filtro_usuario_atendimento(IdGrupoUsuario,IdUsuarioTemp){
		if(IdGrupoUsuario == undefined || IdGrupoUsuario == ''){
			IdGrupoUsuario = 0;
		}
		
		if(IdUsuarioTemp == undefined){
			IdUsuarioTemp = '';
		}
		
		var xmlhttp   = false;
		
		if (window.XMLHttpRequest) { // Mozilla, Safari,...
			xmlhttp = new XMLHttpRequest();
	        if(xmlhttp.overrideMimeType){
	    //    	xmlhttp.overrideMimeType('text/xml');
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

		url = "xml/usuario_grupo_usuario.php?IdGrupoUsuario="+IdGrupoUsuario;

		xmlhttp.open("GET", url,true);
		xmlhttp.onreadystatechange = function(){ 
			// Carregando...
			carregando(true);
			if(xmlhttp.readyState == 4){ 
				if(xmlhttp.status == 200){
				
					if(xmlhttp.responseText == 'false'){
						while(document.filtro.filtro_usuario_atendimento.options.length > 0){
							document.filtro.filtro_usuario_atendimento.options[0] = null;
						}
						
						addOption(document.filtro.filtro_usuario_atendimento,"Todos","");
					}else{
						while(document.filtro.filtro_usuario_atendimento.options.length > 0){
							document.filtro.filtro_usuario_atendimento.options[0] = null;
						}
						
						addOption(document.filtro.filtro_usuario_atendimento,"Todos","");
						
						for(var i=0; i<xmlhttp.responseXML.getElementsByTagName("Login").length; i++){
							nameNode = xmlhttp.responseXML.getElementsByTagName("Login")[i]; 
							nameTextNode = nameNode.childNodes[0];
							var Login = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("NomeUsuario")[i]; 
							nameTextNode = nameNode.childNodes[0];
							var NomeUsuario = nameTextNode.nodeValue;
							var Descricao = NomeUsuario.substr(0,50);	
							
							addOption(document.filtro.filtro_usuario_atendimento,Descricao,Login);
						}
						
						if(IdUsuarioTemp!=''){
							for(ii=0;ii<document.filtro.filtro_usuario_atendimento.length;ii++){
								if(document.filtro.filtro_usuario_atendimento[ii].value == IdUsuarioTemp){
									document.filtro.filtro_usuario_atendimento[ii].selected = true;
									break;
								}
							}
						} else{
							document.filtro.filtro_usuario_atendimento[0].selected = true;
						}						
					}
				}
				// Fim de Carregando
				carregando(false);
			}
			return true;
		}
		xmlhttp.send(null);
	}
	function verificaQuadroLista(campo,event,data){
		if(document.formulario.TicketLista.value != '' && data.value != ''){
			quadro_alt(event,campo,document.formulario.TicketLista.value);		
		}
	}