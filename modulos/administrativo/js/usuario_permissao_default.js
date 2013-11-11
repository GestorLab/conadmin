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
   	
	if(Local == 'UsuarioPermissao'){
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
					document.formulario.Login.value		= '';
					document.formulario.NomeUsuario.value 	= '';
					
					document.formulario.Login.focus();
					
					listar_usuario_permissao(document.formulario.IdLoja.value,Login);
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
					
					document.formulario.Login.value				= Login;
					document.formulario.NomeUsuario.value 		= NomeUsuario;
					
					document.formulario.Acao.value 				= 'alterar';
					
					listar_usuario_permissao(document.formulario.IdLoja.value,Login);
				}
				
				busca_modulo();
				
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

function busca_modulo(){
	var nameNode, nameTextNode, url;
	
	var Login		= document.formulario.Login.value;
	var LoginEditor = document.formulario.LoginEditor.value;
	var IdLoja 		= document.formulario.IdLoja.value;	
	
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
    
   	url = "xml/usuario_modulo.php?Login="+LoginEditor+"&IdLoja="+IdLoja;
	xmlhttp.open("GET", url,true);
	
	xmlhttp.onreadystatechange = function(){ 

		// Carregando...
		carregando(true);

		if(xmlhttp.readyState == 4){ 
			if(xmlhttp.status == 200){
				if(xmlhttp.responseText == 'false' || Login == ''){
					while(document.formulario.IdModulo.options.length > 0){
						document.formulario.IdModulo.options[0] = null;
					}
					// Fim de Carregando
					carregando(false);
				}else{
					while(document.formulario.IdModulo.options.length > 0){
						document.formulario.IdModulo.options[0] = null;
					}

					for(i=0; i<xmlhttp.responseXML.getElementsByTagName("IdModulo").length; i++){
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("IdModulo")[i]; 
						nameTextNode = nameNode.childNodes[0];
						IdModulo = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoModulo")[i]; 
						nameTextNode = nameNode.childNodes[0];
						DescricaoModulo = nameTextNode.nodeValue;
						
						addOption(document.formulario.IdModulo,DescricaoModulo,IdModulo);
					}
					document.formulario.IdModulo.options[0].selected = true;
				}
				busca_operacao();
			}
			// Fim de Carregando
			carregando(false);
		} 
		return true;
	}
	xmlhttp.send(null);
}

function busca_operacao(){
	var nameNode, nameTextNode, url;
	
	var Login 		= document.formulario.LoginEditor.value;
	var IdLoja 		= document.formulario.IdLoja.value;
	var IdModulo	= document.formulario.IdModulo.value;
	
	if(IdModulo == 2){
		document.getElementById("titLoja").style.color = "#000000";
		document.formulario.IdLoja.value = 1;
		document.formulario.IdLoja.disabled = true;
	} else{
		document.getElementById("titLoja").style.color = "#C10000";
		document.formulario.IdLoja.disabled = false;
	}
	
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
    
   	url = "xml/operacao.php?IdLoja="+IdLoja+"&IdModulo="+IdModulo;
	xmlhttp.open("GET", url,true);
	
	xmlhttp.onreadystatechange = function(){ 

		// Carregando...
		carregando(true);

		if(xmlhttp.readyState == 4){ 
			if(xmlhttp.status == 200){
				if(xmlhttp.responseText == 'false'){
					
					while(document.formulario.IdOperacao.options.length > 0){
						document.formulario.IdOperacao.options[0] = null;
					}
					
					// Fim de Carregando
					carregando(false);
				}else{
					while(document.formulario.IdOperacao.options.length > 0){
						document.formulario.IdOperacao.options[0] = null;
					}

					for(i=0; i<xmlhttp.responseXML.getElementsByTagName("IdOperacao").length; i++){
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("IdOperacao")[i]; 
						nameTextNode = nameNode.childNodes[0];
						IdOperacao = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("NomeOperacao")[i]; 
						nameTextNode = nameNode.childNodes[0];
						NomeOperacao = nameTextNode.nodeValue;
						
						addOption(document.formulario.IdOperacao,NomeOperacao,IdOperacao);
					}					
					document.formulario.IdOperacao.options[0].selected = true;					
				}
				atualiza_sub_operacao();
			}
			// Fim de Carregando
			carregando(false);
		} 
		return true;
	}
	xmlhttp.send(null);
}

function atualiza_sub_operacao(){
	busca_sub_operacao_opcoes();
	busca_sub_operacao_permissoes();
}

function busca_sub_operacao_opcoes(){
	var nameNode, nameTextNode, url;
	
	var Login		= document.formulario.Login.value;
	var LoginEditor	= document.formulario.LoginEditor.value;
	var IdLoja 		= document.formulario.IdLoja.value;
	var IdModulo	= document.formulario.IdModulo.value;
	var IdOperacao	= document.formulario.IdOperacao.value;
	
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
    
   	url = "xml/usuario_sub_operacao_opcoes.php?Login="+Login+"&IdLoja="+IdLoja+"&IdModulo="+IdModulo+"&IdOperacao="+IdOperacao;

	xmlhttp.open("GET", url,true);
	
	xmlhttp.onreadystatechange = function(){ 

		// Carregando...
		carregando(true);

		if(xmlhttp.readyState == 4){ 
			if(xmlhttp.status == 200){
				//alert(xmlhttp.responseText);
				if(xmlhttp.responseText == 'false'){
					
					while(document.formulario.IdOperacaoOpcao.options.length > 0){
						document.formulario.IdOperacaoOpcao.options[0] = null;
					}
					
					// Fim de Carregando
					carregando(false);
				}else{
					while(document.formulario.IdOperacaoOpcao.options.length > 0){
						document.formulario.IdOperacaoOpcao.options[0] = null;
					}

					for(i=0; i<xmlhttp.responseXML.getElementsByTagName("IdSubOperacao").length; i++){
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("IdSubOperacao")[i]; 
						nameTextNode = nameNode.childNodes[0];
						IdSubOperacao = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoSubOperacao")[i]; 
						nameTextNode = nameNode.childNodes[0];
						DescricaoSubOperacao = nameTextNode.nodeValue;
						
						addOption(document.formulario.IdOperacaoOpcao,DescricaoSubOperacao,IdSubOperacao);
					}
				}
				while(document.formulario.IdOperacaoPermissao.options.length > 0){
					document.formulario.IdOperacaoPermissao.options[0] = null;
				}
			}
			// Fim de Carregando
			carregando(false);
		} 
		return true;
	}
	xmlhttp.send(null);
}

function busca_sub_operacao_permissoes(){
	var nameNode, nameTextNode, url;
	
	var Login		= document.formulario.Login.value;
	var IdLoja 		= document.formulario.IdLoja.value;
	var IdModulo	= document.formulario.IdModulo.value;
	var IdOperacao	= document.formulario.IdOperacao.value;
	
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
    
   	url = "xml/usuario_sub_operacao.php?Login="+Login+"&IdLoja="+IdLoja+"&IdModulo="+IdModulo+"&IdOperacao="+IdOperacao;
	xmlhttp.open("GET", url,true);
	
	xmlhttp.onreadystatechange = function(){ 

		// Carregando...
		carregando(true);

		if(xmlhttp.readyState == 4){ 
			if(xmlhttp.status == 200){
				while(document.formulario.IdOperacaoPermissao.options.length > 0){
					document.formulario.IdOperacaoPermissao.options[0] = null;
				}
				//alert(xmlhttp.responseText);
				if(xmlhttp.responseText == 'false'){
					// Fim de Carregando
					carregando(false);
				}else{
					for(i=0; i<xmlhttp.responseXML.getElementsByTagName("IdSubOperacao").length; i++){
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("IdSubOperacao")[i]; 
						nameTextNode = nameNode.childNodes[0];
						IdSubOperacao = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoSubOperacao")[i]; 
						nameTextNode = nameNode.childNodes[0];
						DescricaoSubOperacao = nameTextNode.nodeValue;
						
						addOption(document.formulario.IdOperacaoPermissao,DescricaoSubOperacao,IdSubOperacao);
					}
					
					document.formulario.LoginCriacao.value 	= "";
					document.formulario.DataCriacao.value	= "";
					
					busca_sub_operacao_permissoes_dados();
				}
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
	
	for(i=0; i<document.formulario.IdOperacaoPermissao.length; i++){
		if(document.formulario.IdOperacaoPermissao[i].selected == true){
			selecionado++;
		}
	}
	if(selecionado != 1){
		return false;
	}
	
	var nameNode, nameTextNode, url;
	
	var Login			= document.formulario.Login.value;
	var IdLoja 			= document.formulario.IdLoja.value;
	var IdModulo		= document.formulario.IdModulo.value;
	var IdOperacao		= document.formulario.IdOperacao.value;
	var IdSubOperacao	= document.formulario.IdOperacaoPermissao.value;
	
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
    
   	url = "xml/usuario_sub_operacao.php?Login="+Login+"&IdLoja="+IdLoja+"&IdModulo="+IdModulo+"&IdOperacao="+IdOperacao+"&IdSubOperacao="+IdSubOperacao;
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
					document.formulario.DataCriacao.value	= DataCriacao;
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
	var i, count=0, IdSubOperacao, DescricaoSubOperacao, Msg;
	
	var Login			= document.formulario.Login.value;
	var IdLoja 			= document.formulario.IdLoja.value;
	var IdModulo		= document.formulario.IdModulo.value;
	var IdOperacao		= document.formulario.IdOperacao.value;
	
	if(Login == ""){			return false;	}
	
	var Login		= document.formulario.Login.value;
	var LoginEditor	= document.formulario.LoginEditor.value;
	var IdLoja 		= document.formulario.IdLoja.value;
	var IdModulo	= document.formulario.IdModulo.value;
	var IdOperacao	= document.formulario.IdOperacao.value;
	
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
				
				if(xmlhttp.responseText == 'false'){
				//	alert('entrou');
					switch(Acao){
						case 'add':
							Msg = "ATENÇÃO!\nDeseja realmente atribuir esta permissão a este usuário?";
							for(i=0; i<document.formulario.IdOperacaoOpcao.length; i++){
								if(document.formulario.IdOperacaoOpcao[i].selected == true){
									count++;
								}
							}
							
							if(count>0 && confirm(Msg) == false){	return false; 	}
							
							while(count > 0){
								for(i=0; i<document.formulario.IdOperacaoOpcao.length; i++){
									if(document.formulario.IdOperacaoOpcao[i].selected == true){
										IdSubOperacao	= document.formulario.IdOperacaoOpcao[i].value;
										DescricaoSubOperacao	= document.formulario.IdOperacaoOpcao[i].text;
										addOption(document.formulario.IdOperacaoPermissao,DescricaoSubOperacao,IdSubOperacao);
										document.formulario.IdOperacaoOpcao[i] = null;
					
										seta_permissao(Login, IdLoja, IdModulo, IdOperacao, IdSubOperacao, Acao);
										count--;						
										break;
									}			
								}
							}
							break;
						case 'rem':			
							Msg = "ATENÇÃO!\nDeseja realmente remover esta permissão deste usuário?";
							for(i=0; i<document.formulario.IdOperacaoPermissao.length; i++){
								if(document.formulario.IdOperacaoPermissao[i].selected == true){
									count++;
								}
							}
							
							if(count>0 && confirm(Msg) == false){	return false; 	}
							
							while(count > 0){
								for(i=0; i<document.formulario.IdOperacaoPermissao.length; i++){
									if(document.formulario.IdOperacaoPermissao[i].selected == true){
										IdSubOperacao	= document.formulario.IdOperacaoPermissao[i].value;
										DescricaoSubOperacao	= document.formulario.IdOperacaoPermissao[i].text;
										addOption(document.formulario.IdOperacaoOpcao,DescricaoSubOperacao,IdSubOperacao);
										document.formulario.IdOperacaoPermissao[i] = null;
										
										seta_permissao(Login, IdLoja, IdModulo, IdOperacao, IdSubOperacao, Acao);
										count--;
					//						busca_sub_operacao_opcoes();
										break;
									}			
								}
							}
							break;
					}
					busca_sub_operacao_permissoes_dados();
					listar_usuario_permissao(document.formulario.IdLoja.value,Login);
				}else{
					mensagens(85);
					carregando(false);
					return false;
				}
			}
			// Fim de Carregando
			carregando(false);
		} 
		return true;
	}
	xmlhttp.send(null);	
}

function seta_permissao(Login, IdLoja, IdModulo, IdOperacao, IdSubOperacao, Acao){
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
    
   	url = "files/editar/editar_usuario_permissao.php?Login="+Login+"&IdLoja="+IdLoja+"&IdModulo="+IdModulo+"&IdOperacao="+IdOperacao+"&IdSubOperacao="+IdSubOperacao+"&Acao="+Acao;
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
