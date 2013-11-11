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
	function atualizar_filtro_usuario_atendimento(IdGrupoUsuario,IdUsuarioTemp){
		if(IdGrupoUsuario == undefined){
			IdGrupoUsuario = '';
		}
		
		if(IdUsuarioTemp == undefined){
			IdUsuarioTemp = '';
		}
		
		var url = "xml/usuario_grupo_usuario.php?IdGrupoUsuario="+IdGrupoUsuario;
		
		call_ajax(url, function(xmlhttp){ 
			var nameNode, nameTextNode;
			
			if(xmlhttp.responseText == 'false'){
				while(document.filtro.filtro_login_responsavel.options.length > 0){
					document.filtro.filtro_login_responsavel.options[0] = null;
				}
				
				addOption(document.filtro.filtro_login_responsavel,"Todos","");
			}else{
				while(document.filtro.filtro_login_responsavel.options.length > 0){
					document.filtro.filtro_login_responsavel.options[0] = null;
				}
				
				addOption(document.filtro.filtro_login_responsavel,"Todos","");
				
				for(var i=0; i<xmlhttp.responseXML.getElementsByTagName("Login").length; i++){
					nameNode = xmlhttp.responseXML.getElementsByTagName("Login")[i]; 
					nameTextNode = nameNode.childNodes[0];
					var Login = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("NomeUsuario")[i];
					nameTextNode = nameNode.childNodes[0];
					var NomeUsuario = nameTextNode.nodeValue;
					var Descricao = NomeUsuario.substr(0,50);
					
					addOption(document.filtro.filtro_login_responsavel,Descricao,Login);
				}
				
				if(IdUsuarioTemp!=''){
					for(ii=0;ii<document.filtro.filtro_login_responsavel.length;ii++){
						if(document.filtro.filtro_login_responsavel[ii].value == IdUsuarioTemp){
							document.filtro.filtro_login_responsavel[ii].selected = true;
							break;
						}
					}
				} else{
					document.filtro.filtro_login_responsavel[0].selected = true;
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
				while(document.filtro.filtro_login_alteracao.options.length > 0){
					document.filtro.filtro_login_alteracao.options[0] = null;
				}
				
				addOption(document.filtro.filtro_login_alteracao,"Todos","");
			}else{
				while(document.filtro.filtro_login_alteracao.options.length > 0){
					document.filtro.filtro_login_alteracao.options[0] = null;
				}
				
				addOption(document.filtro.filtro_login_alteracao,"Todos","");
				
				for(var i=0; i<xmlhttp.responseXML.getElementsByTagName("Login").length; i++){
					nameNode = xmlhttp.responseXML.getElementsByTagName("Login")[i];
					nameTextNode = nameNode.childNodes[0];
					var Login = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("NomeUsuario")[i];
					nameTextNode = nameNode.childNodes[0];
					var NomeUsuario = nameTextNode.nodeValue;
					var Descricao = NomeUsuario.substr(0,50);
					
					addOption(document.filtro.filtro_login_alteracao,Descricao,Login);
				}
				
				if(IdUsuarioTemp!=''){
					for(ii=0;ii<document.filtro.filtro_login_alteracao.length;ii++){
						if(document.filtro.filtro_login_alteracao[ii].value == IdUsuarioTemp){
							document.filtro.filtro_login_alteracao[ii].selected = true;
							break;
						}
					}
				} else{
					document.filtro.filtro_login_alteracao[0].selected = true;
				}
			}
		});
	}