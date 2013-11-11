function atualiza_usuario_grupo_usuario(Login){
	if(Login == ''){
		while(document.formulario.IdGrupoUsuarioOpcao.options.length > 0){
			document.formulario.IdGrupoUsuarioOpcao.options[0] = null;
		}
		while(document.formulario.IdGrupoUsuarioPermissao.options.length > 0){
			document.formulario.IdGrupoUsuarioPermissao.options[0] = null;
		}
		document.formulario.LoginCriacao.value 	= '';
		document.formulario.DataCriacao.value	= '';
	}else{
		busca_usuario_grupo_usuario_opcoes();
		busca_usuario_grupo_usuario_permissoes();
	}
	
}
function busca_usuario_grupo_usuario_opcoes(){
	var nameNode, nameTextNode, url;
	
	var Login		= document.formulario.Login.value;
	
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
    
   	url = "xml/usuario_grupo_usuario_opcoes.php?Login="+Login;
	xmlhttp.open("GET", url,true);

	xmlhttp.onreadystatechange = function(){ 

		// Carregando...
		carregando(true);

		if(xmlhttp.readyState == 4){ 
			if(xmlhttp.status == 200){
				while(document.formulario.IdGrupoUsuarioOpcao.options.length > 0){
					document.formulario.IdGrupoUsuarioOpcao.options[0] = null;
				}
				if(Login != ''){
					if(xmlhttp.responseText == 'false'){					
						// Fim de Carregando
						carregando(false);
					}else{
						for(i=0; i<xmlhttp.responseXML.getElementsByTagName("IdGrupoUsuario").length; i++){
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("IdGrupoUsuario")[i]; 
							nameTextNode = nameNode.childNodes[0];
							IdGrupoUsuario = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoGrupoUsuario")[i]; 
							nameTextNode = nameNode.childNodes[0];
							DescricaoGrupoUsuario = nameTextNode.nodeValue;
							
							addOption(document.formulario.IdGrupoUsuarioOpcao,DescricaoGrupoUsuario,IdGrupoUsuario);	
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

function busca_usuario_grupo_usuario_permissoes(){
	var nameNode, nameTextNode, url;
	
	var Login		= document.formulario.Login.value;
	
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
    
   	url = "xml/usuario_grupo_usuario_permissao.php?Login="+Login;
	xmlhttp.open("GET", url,true);
	
	xmlhttp.onreadystatechange = function(){ 

		// Carregando...
		carregando(true);

		if(xmlhttp.readyState == 4){ 
			if(xmlhttp.status == 200){
				while(document.formulario.IdGrupoUsuarioPermissao.options.length > 0){
					document.formulario.IdGrupoUsuarioPermissao.options[0] = null;
				}
				if(xmlhttp.responseText == 'false'){					
					// Fim de Carregando
					carregando(false);
				}else{
					for(i=0; i<xmlhttp.responseXML.getElementsByTagName("IdGrupoUsuario").length; i++){
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("IdGrupoUsuario")[i]; 
						nameTextNode = nameNode.childNodes[0];
						IdGrupoUsuario = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoGrupoUsuario")[i]; 
						nameTextNode = nameNode.childNodes[0];
						DescricaoGrupoUsuario = nameTextNode.nodeValue;
						
						addOption(document.formulario.IdGrupoUsuarioPermissao,DescricaoGrupoUsuario,IdGrupoUsuario);
					}
				}
				busca_sub_operacao_permissoes_dados();
			}
			// Fim de Carregando
			carregando(false);
		} 
		return true;
	}
	xmlhttp.send(null);
}

function busca_sub_operacao_permissoes_dados(){
	var i=0, selecionado=0;
	
	document.formulario.LoginCriacao.value 	= "";
	document.formulario.DataCriacao.value	= "";
	
	var nameNode, nameTextNode, url;
	
	var Login		= document.formulario.Login.value;
	var IdGrupoUsuario	 = 1;

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
    
   	url = "xml/usuario_grupo_usuario_permissao.php?Login="+Login+"&IdGrupoUsuario="+IdGrupoUsuario;
	xmlhttp.open("GET", url,true);

	xmlhttp.onreadystatechange = function(){ 

		// Carregando...
		carregando(true);

		if(xmlhttp.readyState == 4){ 
			if(xmlhttp.status == 200){
				if(xmlhttp.responseText != 'false'){
					nameNode = xmlhttp.responseXML.getElementsByTagName("DataCriacao")[0]; 
					nameTextNode = nameNode.childNodes[0];
					var DataCriacao = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("LoginCriacao")[0]; 
					nameTextNode = nameNode.childNodes[0];
					var LoginCriacao = nameTextNode.nodeValue;
				
					document.formulario.LoginCriacao.value 	= LoginCriacao;
					document.formulario.DataCriacao.value	= dateFormat(DataCriacao);
				}
			}
			// Fim de Carregando
			carregando(false);
		} 
		return true;
	}
	xmlhttp.send(null);	
}

function quadro_permissao(Acao){
	var i, count=0, Msg;
	
	var Login			= document.formulario.Login.value;	
	if(Login == ""){	return false;	}

	switch(Acao){
		case 'add':
			Msg = "ATENÇÃO!\nDeseja realmente atribuir este grupo usuário a este usuário?";
			for(i=0; i<document.formulario.IdGrupoUsuarioOpcao.length; i++){
				if(document.formulario.IdGrupoUsuarioOpcao[i].selected == true){
					count++;
				}
			}
			
			if(count>0 && confirm(Msg) == false){	return false; 	}
			
			while(count > 0){
				for(i=0; i<document.formulario.IdGrupoUsuarioOpcao.length; i++){
					if(document.formulario.IdGrupoUsuarioOpcao[i].selected == true){
						IdGrupoUsuario			= document.formulario.IdGrupoUsuarioOpcao[i].value;
						DescricaoGrupoUsuario	= document.formulario.IdGrupoUsuarioOpcao[i].text;
						
						addOption(document.formulario.IdGrupoUsuarioPermissao,DescricaoGrupoUsuario,IdGrupoUsuario);
						document.formulario.IdGrupoUsuarioOpcao[i] = null;
						
						seta_permissao(Login, IdGrupoUsuario, Acao);
						count--;						
						break;
					}			
				}
			}
			break;
		case 'rem':			
			Msg = "ATENÇÃO!\nDeseja realmente remover este grupo usuário deste usuário??";
			for(i=0; i<document.formulario.IdGrupoUsuarioPermissao.length; i++){
				if(document.formulario.IdGrupoUsuarioPermissao[i].selected == true){
					count++;
				}
			}
			
			if(count>0 && confirm(Msg) == false){	return false; 	}
			
			while(count > 0){
				for(i=0; i<document.formulario.IdGrupoUsuarioPermissao.length; i++){
					if(document.formulario.IdGrupoUsuarioPermissao[i].selected == true){
						IdGrupoUsuario			= document.formulario.IdGrupoUsuarioPermissao[i].value;
						DescricaoGrupoUsuario	= document.formulario.IdGrupoUsuarioPermissao[i].text;

						addOption(document.formulario.IdGrupoUsuarioOpcao,DescricaoGrupoUsuario,IdGrupoUsuario);
						
						document.formulario.IdGrupoUsuarioPermissao[i] = null;
						
						seta_permissao(Login, IdGrupoUsuario, Acao);
						count--;

						break;
					}			
				}
			}
			break;
	}
	busca_sub_operacao_permissoes_dados();
}

function seta_permissao(Login, IdGrupoUsuario, Acao){
	var xmlhttp   = false, retorno = true;
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
    
   	url = "files/editar/editar_usuario_grupo_usuario.php?IdGrupoUsuario="+IdGrupoUsuario+"&Login="+Login+"&Acao="+Acao;
	xmlhttp.open("GET", url,true);
	
	xmlhttp.onreadystatechange = function(){ 

		// Carregando...
		carregando(true);

		if(xmlhttp.readyState == 4){ 
			if(xmlhttp.status == 200){
				var res = xmlhttp.responseText;
				document.formulario.Erro.value = res;
				verificaErro();
			}
		}
		// Fim de Carregando
		carregando(false);
		return true;
	}
	xmlhttp.send(null);	
}
