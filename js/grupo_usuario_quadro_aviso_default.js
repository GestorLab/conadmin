function busca_grupo_usuario_quadro_aviso(Login, Erro, Local){
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
    
   	url = "xml/grupo_usuario_quadro_aviso.php?IdGrupoUsuario="+IdGrupoUsuario;
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
					addParmUrl("GrupoUsuarioQuadroAviso","IdGrupoUsuario","");
					addParmUrl("marGrupoUsuarioQuadroAviso","IdGrupoUsuario","");
					
					document.formulario.Login.value		= '';
					document.formulario.NomeUsuario.value 	= '';
					
					document.formulario.Login.focus();
					
					// Fim de Carregando
					carregando(false);
				}else{
					addParmUrl("marGrupoUsuario","IdGrupoUsuario",IdGrupoUsuario);
					addParmUrl("marGrupoUsuarioQuadroAviso","IdGrupoUsuario",IdGrupoUsuario);
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("IdGrupoUsuario")[0]; 
					nameTextNode = nameNode.childNodes[0];
					IdGrupoUsuario = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoGrupoUsuario")[0]; 
					nameTextNode = nameNode.childNodes[0];
					var DescricaoGrupoUsuario = nameTextNode.nodeValue;
					
					addParmUrl("marGrupoUsuario","IdGrupoUsuario",IdGrupoUsuario);
					addParmUrl("marGrupoUsuarioQuadroAviso","IdGrupoUsuario",IdGrupoUsuario);
					
					document.formulario.IdGrupoUsuario.value				= IdGrupoUsuario;
					document.formulario.DescricaoGrupoUsuario.value 		= DescricaoGrupoUsuario;
					
					document.formulario.Acao.value 				= 'alterar';
					
				}
				
				if(window.janela != undefined){
					window.janela.close();
				}
				//verificaAcao();
			}
			// Fim de Carregando
			carregando(false);
		} 
		return true;
	}
	xmlhttp.send(null);
}

function atualiza_grupo_usuario(IdGrupoUsuario){
	if(IdGrupoUsuario == ''){
		while(document.formulario.IdGrupoUsuarioOpcao.options.length > 0){
			document.formulario.IdGrupoUsuarioOpcao.options[0] = null;
		}
		while(document.formulario.IdGrupoUsuarioPermissao.options.length > 0){
			document.formulario.IdGrupoUsuarioPermissao.options[0] = null;
		}
		document.formulario.LoginCriacao.value 	= '';
		document.formulario.DataCriacao.value	= '';
	}else{
		busca_grupo_usuario_opcoes();
		busca_grupo_usuario_permissoes();
	}
	
}

function busca_grupo_usuario_opcoes(){
	var nameNode, nameTextNode, url;
	
	var IdGrupoUsuario		= document.formulario.IdGrupoUsuario.value;
	
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
    
   	url = "xml/grupo_usuario_quadro_aviso_opcoes.php?IdGrupoUsuario="+IdGrupoUsuario;
	xmlhttp.open("GET", url,true);

	xmlhttp.onreadystatechange = function(){ 

		// Carregando...
		carregando(true);

		if(xmlhttp.readyState == 4){ 
			if(xmlhttp.status == 200){
				while(document.formulario.IdGrupoUsuarioOpcao.options.length > 0){
					document.formulario.IdGrupoUsuarioOpcao.options[0] = null;
				}
				if(IdGrupoUsuario != ''){
					if(xmlhttp.responseText == 'false'){					
						// Fim de Carregando
						carregando(false);
					}else{
						for(i=0; i<xmlhttp.responseXML.getElementsByTagName("IdQuadroAviso").length; i++){
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("IdQuadroAviso")[i]; 
							nameTextNode = nameNode.childNodes[0];
							IdQuadroAviso = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoQuadroAviso")[i]; 
							nameTextNode = nameNode.childNodes[0];
							DescricaoQuadroAviso = nameTextNode.nodeValue;
							
							addOption(document.formulario.IdGrupoUsuarioOpcao,DescricaoQuadroAviso,IdQuadroAviso);	
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

function busca_grupo_usuario_permissoes(){
	var nameNode, nameTextNode, url;
	
	var IdGrupoUsuario		= document.formulario.IdGrupoUsuario.value;
	
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
    
   	url = "xml/grupo_usuario_quadro_aviso_permissao.php?IdGrupoUsuario="+IdGrupoUsuario;
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
					for(i=0; i<xmlhttp.responseXML.getElementsByTagName("IdQuadroAviso").length; i++){
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("IdQuadroAviso")[i]; 
						nameTextNode = nameNode.childNodes[0];
						IdQuadroAviso = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoQuadroAviso")[i]; 
						nameTextNode = nameNode.childNodes[0];
						DescricaoQuadroAviso = nameTextNode.nodeValue;
						
						addOption(document.formulario.IdGrupoUsuarioPermissao,DescricaoQuadroAviso,IdQuadroAviso);
					}
				}
				busca_grupo_usuario_permissoes_dados();
			}
			// Fim de Carregando
			carregando(false);
		} 
		return true;
	}
	xmlhttp.send(null);
}

function busca_grupo_usuario_permissoes_dados(){
	var i=0, selecionado=0;
	
	document.formulario.LoginCriacao.value 	= "";
	document.formulario.DataCriacao.value	= "";
	
/*	for(i=0; i<document.formulario.IdGrupoUsuarioPermissao.length; i++){
		if(document.formulario.IdGrupoUsuarioPermissao[i].selected == true){
			selecionado++;
		}
	}
	if(selecionado != 1){
		return false;
	}*/
	var nameNode, nameTextNode, url;
	
	var IdGrupoUsuario		= document.formulario.IdGrupoUsuario.value;
	var IdGrupoPermissao	= 1;

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
    
   	url = "xml/grupo_usuario_quadro_aviso_permissao.php?IdGrupoUsuario="+IdGrupoUsuario+"&IdGrupoPermissao="+IdGrupoPermissao;
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

function quadro_aviso(Acao){
	var i, count=0, Msg;
	
	var IdGrupoUsuario			= document.formulario.IdGrupoUsuario.value;	
	if(IdGrupoUsuario == ""){	return false;	}

	switch(Acao){
		case 'add':
			Msg = "ATENÇÃO!\nDeseja realmente atribuir este quadro de aviso a este grupo de usuário?";
			for(i=0; i<document.formulario.IdGrupoUsuarioOpcao.length; i++){
				if(document.formulario.IdGrupoUsuarioOpcao[i].selected == true){
					count++;
				}
			}
			
			if(count>0 && confirm(Msg) == false){	return false; 	}
			
			while(count > 0){
				for(i=0; i<document.formulario.IdGrupoUsuarioOpcao.length; i++){
					if(document.formulario.IdGrupoUsuarioOpcao[i].selected == true){
						IdQuadroAviso		= document.formulario.IdGrupoUsuarioOpcao[i].value;
						DescricaoQuadroAviso	= document.formulario.IdGrupoUsuarioOpcao[i].text;
						
						addOption(document.formulario.IdGrupoUsuarioPermissao,DescricaoQuadroAviso,IdQuadroAviso);
						document.formulario.IdGrupoUsuarioOpcao[i] = null;
						
						seta_permissao(IdGrupoUsuario, IdQuadroAviso, Acao);
						count--;						
						break;
					}			
				}
			}
			break;
		case 'rem':			
			Msg = "ATENÇÃO!\nDeseja realmente remover este quadro de aviso deste grupo de usuário??";
			for(i=0; i<document.formulario.IdGrupoUsuarioPermissao.length; i++){
				if(document.formulario.IdGrupoUsuarioPermissao[i].selected == true){
					count++;
				}
			}
			
			if(count>0 && confirm(Msg) == false){	return false; 	}
			
			while(count > 0){
				for(i=0; i<document.formulario.IdGrupoUsuarioPermissao.length; i++){
					if(document.formulario.IdGrupoUsuarioPermissao[i].selected == true){
						IdQuadroAviso	= document.formulario.IdGrupoUsuarioPermissao[i].value;
						DescricaoQuadroAviso	= document.formulario.IdGrupoUsuarioPermissao[i].text;

						addOption(document.formulario.IdGrupoUsuarioOpcao,DescricaoQuadroAviso,IdQuadroAviso);
						
						document.formulario.IdGrupoUsuarioPermissao[i] = null;
						
						seta_permissao(IdGrupoUsuario, IdQuadroAviso, Acao);
						count--;
						//busca_grupo_permissao_opcoes();

						break;
					}			
				}
			}
			break;
	}
	busca_grupo_usuario_permissoes_dados();
}

function seta_permissao(IdGrupoUsuario, IdQuadroAviso, Acao){
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
    
   	url = "files/editar/editar_grupo_usuario_quadro_aviso.php?IdGrupoUsuario="+IdGrupoUsuario+"&IdQuadroAviso="+IdQuadroAviso+"&Acao="+Acao;
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
