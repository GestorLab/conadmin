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
		
		url = "xml/help_desk_subtipo.php?IdTipoTicket="+IdTipoTicket;
		
		call_ajax(url, function(xmlhttp){ 
			var nameNode, nameTextNode;
			
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
							campo[i].selected = true;
							break;
						}
					}
				}else{
					campo[0].selected = true;
				}
			}else{
				while(campo.options.length > 0){
					campo.options[0] = null;
				}
				addOption(campo,"","");
			}
		});
	}
	function atualizar_filtro_subtipo_help_desk(IdTipoTicket,IdSubTipoTicketTemp){
		if(IdTipoTicket == undefined || IdTipoTicket==''){
			IdTipoTicket = 0;
		}
		if(IdSubTipoTicketTemp == undefined){
			IdSubTipoTicketTemp = '';
		}
		
		url = "xml/help_desk_subtipo.php?IdTipoTicket="+IdTipoTicket;
		
		call_ajax(url, function(xmlhttp){ 
			var nameNode, nameTextNode;
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
							document.filtro.filtro_sub_tipo[i].selected = true;
							break;
						}
					}
				}else{
					document.filtro.filtro_sub_tipo[0].selected = true;
				}
			}else{
				while(document.filtro.filtro_sub_tipo.options.length > 0){
					document.filtro.filtro_sub_tipo.options[0] = null;
				}
				addOption(document.filtro.filtro_sub_tipo,"Todos","");
			}
		});
	}
	function alualizar_filtro_usuario_help_desk(Campo, Usuario){
		if(Usuario == undefined){
			Usuario = '';
		}
		
		url = "xml/help_desk_usuario.php?IdStatus=1";
		call_ajax(url, function(xmlhttp){ 
			var nameNode, nameTextNode;
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
							document.filtro.filtro_valor[i].selected = true;
							break;
						}
					}
				}else{
					document.filtro.filtro_valor[0].selected = true;
				}
			}else{
				while(document.filtro.filtro_valor.options.length > 0){
					document.filtro.filtro_valor.options[0] = null;
				}
				addOption(document.filtro.filtro_valor,"Todos","");
			}
		});
	}
	function atualizar_filtro_usuario_atendimento(IdGrupoUsuario,IdUsuarioTemp){
	
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
		});
	}
	function atualizar_filtro_usuario_alteracao(IdGrupoUsuario,IdUsuarioTemp){
	
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
				while(document.filtro.filtro_usuario_alteracao.options.length > 0){
					document.filtro.filtro_usuario_alteracao.options[0] = null;
				}
				
				addOption(document.filtro.filtro_usuario_alteracao,"Todos","");
			}else{
				while(document.filtro.filtro_usuario_alteracao.options.length > 0){
					document.filtro.filtro_usuario_alteracao.options[0] = null;
				}
				
				addOption(document.filtro.filtro_usuario_alteracao,"Todos","");
				
				for(var i=0; i<xmlhttp.responseXML.getElementsByTagName("Login").length; i++){
					nameNode = xmlhttp.responseXML.getElementsByTagName("Login")[i];
					nameTextNode = nameNode.childNodes[0];
					var Login = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("NomeUsuario")[i];
					nameTextNode = nameNode.childNodes[0];
					var NomeUsuario = nameTextNode.nodeValue;
					var Descricao = NomeUsuario.substr(0,50);
					
					addOption(document.filtro.filtro_usuario_alteracao,Descricao,Login);
				}
				
				if(IdUsuarioTemp!=''){
					for(ii=0;ii<document.filtro.filtro_usuario_alteracao.length;ii++){
						if(document.filtro.filtro_usuario_alteracao[ii].value == IdUsuarioTemp){
							document.filtro.filtro_usuario_alteracao[ii].selected = true;
							break;
						}
					}
				} else{
					document.filtro.filtro_usuario_alteracao[0].selected = true;
				}
			}
		});
	}
	function excluir_arquivo(pos, IdArquivo){
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
				document.formulario.CountArquivo.value = parseInt(document.formulario.CountArquivo.value) - 1;
				break;
			}
		}
		
		if(parseInt(document.formulario.CountArquivo.value) == 0){
			addArquivo(null);
		}
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