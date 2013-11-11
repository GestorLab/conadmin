	function chama_mascara(campo,event){
		switch(document.filtro.filtro_campo.value){
			case 'DataPrevisao':
				campo.maxLength	=	10;
				return mascara(campo,event,'date');
				break;
			case 'MesPrevisao':
				campo.maxLength	=	7;
				return mascara(campo,event,'mes');
				break;
			default:
				campo.maxLength	=	100;
		}
	}
	function atualizar_filtro_subtipo_help_desk(IdTipoTicket,IdSubTipoTicketTemp){
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
	function atualizar_filtro_usuario_responsavel(IdGrupoUsuario,IdUsuarioTemp){
	
		if(IdGrupoUsuario == undefined){
			IdGrupoUsuario = '';
		}
		
		if(IdUsuarioTemp == undefined){
			IdUsuarioTemp = '';
		}
		
		
		url = "xml/usuario_grupo_usuario.php?IdGrupoUsuario="+IdGrupoUsuario;
		
		call_ajax(url, function(xmlhttp){ 
			var nameNode, nameTextNode;
			
			if(xmlhttp.responseText == 'false'){
				while(document.filtro.filtro_usuario_responsavel.options.length > 0){
					document.filtro.filtro_usuario_responsavel.options[0] = null;
				}
				
				addOption(document.filtro.filtro_usuario_responsavel,"Todos","");
			}else{
				while(document.filtro.filtro_usuario_responsavel.options.length > 0){
					document.filtro.filtro_usuario_responsavel.options[0] = null;
				}
				
				addOption(document.filtro.filtro_usuario_responsavel,"Todos","");
				
				for(var i=0; i<xmlhttp.responseXML.getElementsByTagName("Login").length; i++){
					nameNode = xmlhttp.responseXML.getElementsByTagName("Login")[i]; 
					nameTextNode = nameNode.childNodes[0];
					var Login = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("NomeUsuario")[i];
					nameTextNode = nameNode.childNodes[0];
					var NomeUsuario = nameTextNode.nodeValue;
					var Descricao = NomeUsuario.substr(0,50);
					
					addOption(document.filtro.filtro_usuario_responsavel,Descricao,Login);
				}
				
				if(IdUsuarioTemp!=''){
					for(ii=0;ii<document.filtro.filtro_usuario_responsavel.length;ii++){
						if(document.filtro.filtro_usuario_responsavel[ii].value == IdUsuarioTemp){
							document.filtro.filtro_usuario_responsavel[ii].selected = true;
							break;
						}
					}
				} else{
					document.filtro.filtro_usuario_responsavel[0].selected = true;
				}
			}
		});
	}
	function busca_pessoa_aproximada(campo,event){
		var url = "xml/pessoa_nome.php?Nome="+campo.value;
		
		call_ajax(url,function (xmlhttp){
			var NomeDefault = new Array(), nameNode, nameTextNode;
			
			if(campo.value != '' && xmlhttp.responseText != "false"){
				for(var i = 0; i < xmlhttp.responseXML.getElementsByTagName("NomeDefault").length; i++){
					nameNode = xmlhttp.responseXML.getElementsByTagName("NomeDefault")[i]; 
					nameTextNode = nameNode.childNodes[0];
					NomeDefault[i] = nameTextNode.nodeValue;
				}
			}
			
			busca_aproximada('filtro',campo,event,NomeDefault,22,5);
		},false);
	}