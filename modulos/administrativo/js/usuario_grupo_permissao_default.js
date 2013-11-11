function busca_usuario(Login, Erro, Local){
	
	if(Login == ''){
		Login = 'NULL';
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
    
   	url = "xml/usuario.php?Login="+Login;

   	if(Local == 'UsuarioGrupoPermissao'){
	   	url += "&IdStatus="+2; 
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
					
					document.formulario.Login.value			= '';
					document.formulario.NomeUsuario.value 	= '';
					
					document.formulario.Login.focus();
					
					// Fim de Carregando
					carregando(false);
				}else{
					nameNode = xmlhttp.responseXML.getElementsByTagName("Login")[0]; 
					nameTextNode = nameNode.childNodes[0];
					Login = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("NomeUsuario")[0]; 
					nameTextNode = nameNode.childNodes[0];
					var NomeUsuario = nameTextNode.nodeValue;
					
					addParmUrl("marUsuario","Login",Login);
					addParmUrl("marUsuarioPermissao","Login",Login);
					addParmUrl("marUsuarioGrupoPermissao","Login",Login);
					addParmUrl("marUsuarioGrupoUsuario","Login",Login);
					addParmUrl("marUsuarioGrupoUsuarioNovo","Login",Login);
					addParmUrl("marLogAcesso","Login",Login);
					
					document.formulario.Login.value				= Login;
					document.formulario.NomeUsuario.value 		= NomeUsuario;
					
					document.formulario.Acao.value 				= 'alterar';
					
				}
				
				atualiza_grupo_permissao(Login);
				
				if(document.getElementById("quadroBuscaUsuario") != null){
					if(document.getElementById("quadroBuscaUsuario").style.display == "block"){
						document.getElementById("quadroBuscaUsuario").style.display =	"none";
					}
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

function atualiza_grupo_permissao(Login){
	if(Login == ''){
		while(document.formulario.IdGrupoPermissaoOpcao.options.length > 0){
			document.formulario.IdGrupoPermissaoOpcao.options[0] = null;
		}
		while(document.formulario.IdGrupoPermissaoPermissao.options.length > 0){
			document.formulario.IdGrupoPermissaoPermissao.options[0] = null;
		}
		document.formulario.LoginCriacao.value 	= '';
		document.formulario.DataCriacao.value	= '';
	}else{
		busca_grupo_permissao_opcoes();
		busca_grupo_permissao_permissoes();
	}
	
}

function busca_grupo_permissao_opcoes(){
	var nameNode, nameTextNode, url;
	
	var Login		= document.formulario.Login.value;
	var LoginEditor	= document.formulario.LoginEditor.value;
	
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
    
   	url = "xml/usuario_grupo_permissao_opcoes.php?Login="+Login+"&LoginEditor="+LoginEditor;
	xmlhttp.open("GET", url,true);
	
	xmlhttp.onreadystatechange = function(){ 

		// Carregando...
		carregando(true);

		if(xmlhttp.readyState == 4){ 
			if(xmlhttp.status == 200){
				while(document.formulario.IdGrupoPermissaoOpcao.options.length > 0){
					document.formulario.IdGrupoPermissaoOpcao.options[0] = null;
				}
				if(Login != ''){
					if(xmlhttp.responseText == 'false'){					
						// Fim de Carregando
						carregando(false);
					}else{
						for(i=0; i<xmlhttp.responseXML.getElementsByTagName("IdGrupoPermissao").length; i++){
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("IdGrupoPermissao")[i]; 
							nameTextNode = nameNode.childNodes[0];
							IdGrupoPermissao = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoGrupoPermissao")[i]; 
							nameTextNode = nameNode.childNodes[0];
							DescricaoGrupoPermissao = nameTextNode.nodeValue;
							
							addOption(document.formulario.IdGrupoPermissaoOpcao,DescricaoGrupoPermissao,IdGrupoPermissao);
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

function busca_grupo_permissao_permissoes(){
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
    
   	url = "xml/usuario_grupo_permissao.php?Login="+Login;
	xmlhttp.open("GET", url,true);
	
	xmlhttp.onreadystatechange = function(){ 

		// Carregando...
		carregando(true);

		if(xmlhttp.readyState == 4){ 
			if(xmlhttp.status == 200){
				while(document.formulario.IdGrupoPermissaoPermissao.options.length > 0){
					document.formulario.IdGrupoPermissaoPermissao.options[0] = null;
				}
				if(xmlhttp.responseText == 'false'){					
					// Fim de Carregando
					carregando(false);
				}else{
					for(i=0; i<xmlhttp.responseXML.getElementsByTagName("IdGrupoPermissao").length; i++){
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("IdGrupoPermissao")[i]; 
						nameTextNode = nameNode.childNodes[0];
						IdGrupoPermissao = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoGrupoPermissao")[i]; 
						nameTextNode = nameNode.childNodes[0];
						DescricaoGrupoPermissao = nameTextNode.nodeValue;
						
						addOption(document.formulario.IdGrupoPermissaoPermissao,DescricaoGrupoPermissao,IdGrupoPermissao);
					}
				}
				busca_grupo_permissao_permissoes_dados();
			}
			// Fim de Carregando
			carregando(false);
		} 
		return true;
	}
	xmlhttp.send(null);
}

function busca_grupo_permissao_permissoes_dados(){
	var i=0, selecionado=0;
	
	document.formulario.LoginCriacao.value 	= "";
	document.formulario.DataCriacao.value	= "";
	
	for(i=0; i<document.formulario.IdGrupoPermissaoPermissao.length; i++){
		if(document.formulario.IdGrupoPermissaoPermissao[i].selected == true){
			selecionado++;
		}
	}
	if(selecionado != 1){
		return false;
	}
	
	var nameNode, nameTextNode, url;
	
	var Login				= document.formulario.Login.value;
	var IdGrupoPermissao	= document.formulario.IdGrupoPermissaoPermissao.value;
	
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
    
   	url = "xml/usuario_grupo_permissao.php?Login="+Login+"&IdGrupoPermissao="+IdGrupoPermissao;
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
	
	var Login				= document.formulario.Login.value;	
	if(Login == ""){	return false;	}

	switch(Acao){
		case 'add':
			Msg = "ATENÇÃO!\nDeseja realmente atribuir esta permissão a este usuário?";
			for(i=0; i<document.formulario.IdGrupoPermissaoOpcao.length; i++){
				if(document.formulario.IdGrupoPermissaoOpcao[i].selected == true){
					count++;
				}
			}
			
			if(count>0 && confirm(Msg) == false){	return false; 	}
			
			while(count > 0){
				for(i=0; i<document.formulario.IdGrupoPermissaoOpcao.length; i++){
					if(document.formulario.IdGrupoPermissaoOpcao[i].selected == true){
						IdGrupoPermissao	= document.formulario.IdGrupoPermissaoOpcao[i].value;
						DescricaoGrupoPermissao	= document.formulario.IdGrupoPermissaoOpcao[i].text;
						
						addOption(document.formulario.IdGrupoPermissaoPermissao,DescricaoGrupoPermissao,IdGrupoPermissao);
						document.formulario.IdGrupoPermissaoOpcao[i] = null;
						
						seta_permissao(Login, IdGrupoPermissao, Acao)
						count--;						
						break;
					}			
				}
			}
			break;
		case 'rem':			
			Msg = "ATENÇÃO!\nDeseja realmente remover esta permissão deste usuário?";
			for(i=0; i<document.formulario.IdGrupoPermissaoPermissao.length; i++){
				if(document.formulario.IdGrupoPermissaoPermissao[i].selected == true){
					count++;
				}
			}
			
			if(count>0 && confirm(Msg) == false){	return false; 	}
			
			while(count > 0){
				for(i=0; i<document.formulario.IdGrupoPermissaoPermissao.length; i++){
					if(document.formulario.IdGrupoPermissaoPermissao[i].selected == true){
						IdGrupoPermissao	= document.formulario.IdGrupoPermissaoPermissao[i].value;
						DescricaoGrupoPermissao	= document.formulario.IdGrupoPermissaoPermissao[i].text;

						addOption(document.formulario.IdGrupoPermissaoOpcao,DescricaoGrupoPermissao,IdGrupoPermissao);
						
						document.formulario.IdGrupoPermissaoPermissao[i] = null;
						
						seta_permissao(Login, IdGrupoPermissao, Acao);
						count--;
						//busca_grupo_permissao_opcoes();

						break;
					}			
				}
			}
			break;
	}
	busca_grupo_permissao_permissoes_dados();
}

function seta_permissao(Login, IdGrupoPermissao, Acao){
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
    
   	url = "files/editar/editar_usuario_grupo_permissao.php?Login="+Login+"&IdGrupoPermissao="+IdGrupoPermissao+"&Acao="+Acao;
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
