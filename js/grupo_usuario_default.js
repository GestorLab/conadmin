	function janela_busca_grupo_usuario(){
		janelas('busca_grupo_usuario.php',360,283,250,100,'');
	}
	function busca_grupo_usuario(IdGrupoUsuario, Erro, Local){
		if(IdGrupoUsuario == ''){
			IdGrupoUsuario = 0;
		}
		if(Local == '' || Local== undefined){
			Local = document.formulario.Local.value;
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
	    
	   	url = "xml/grupo_usuario.php?IdGrupoUsuario="+IdGrupoUsuario;
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
						
						document.formulario.IdGrupoUsuario.value 			= '';
						document.formulario.DescricaoGrupoUsuario.value 	= '';
						
						switch(Local){
							case 'GrupoUsuario':
								addParmUrl("marGrupoUsuario","IdGrupoUsuario","");
								addParmUrl("marGrupoUsuarioQuadroAviso","IdGrupoUsuario","");
								
								document.formulario.DataCriacao.value 		= '';
								document.formulario.LoginCriacao.value 		= '';
								document.formulario.DataAlteracao.value 	= '';
								document.formulario.LoginAlteracao.value	= '';
								document.formulario.Acao.value 				= 'inserir';
								break;
							case 'GrupoUsuarioQuadroAviso':
								addParmUrl("marGrupoUsuario","IdGrupoUsuario","");
								addParmUrl("marGrupoUsuarioQuadroAviso","IdGrupoUsuario","");
								
								while(document.formulario.IdGrupoUsuarioOpcao.options.length > 0){
									document.formulario.IdGrupoUsuarioOpcao.options[0] = null;
								}
								while(document.formulario.IdGrupoUsuarioPermissao.options.length > 0){
									document.formulario.IdGrupoUsuarioPermissao.options[0] = null;
								}
								break;
						}
						document.formulario.IdGrupoUsuario.focus();
						
						// Fim de Carregando
						carregando(false);
					}else{
						nameNode = xmlhttp.responseXML.getElementsByTagName("IdGrupoUsuario")[0]; 
						nameTextNode = nameNode.childNodes[0];
						IdGrupoUsuario = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoGrupoUsuario")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var DescricaoGrupoUsuario = nameTextNode.nodeValue;
						
						document.formulario.IdGrupoUsuario.value		  = IdGrupoUsuario;
						document.formulario.DescricaoGrupoUsuario.value = DescricaoGrupoUsuario;
						
						switch(Local){
							case 'GrupoUsuario':
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
								
								addParmUrl("marGrupoUsuario","IdGrupoUsuario",IdGrupoUsuario);
								addParmUrl("marGrupoUsuarioQuadroAviso","IdGrupoUsuario",IdGrupoUsuario);
								
								document.formulario.DataCriacao.value 		= dateFormat(DataCriacao);
								document.formulario.LoginCriacao.value 		= LoginCriacao;
								document.formulario.DataAlteracao.value 	= dateFormat(DataAlteracao);
								document.formulario.LoginAlteracao.value	= LoginAlteracao;
								document.formulario.Acao.value 				= 'alterar';
								break;
							case 'GrupoUsuarioQuadroAviso':	
								addParmUrl("marGrupoUsuario","IdGrupoUsuario",IdGrupoUsuario);
								addParmUrl("marGrupoUsuarioQuadroAviso","IdGrupoUsuario",IdGrupoUsuario);
							
								atualiza_grupo_usuario(IdGrupoUsuario);
								break;
						}
					}
					if(document.getElementById("quadroBuscaGrupoUsuario") != null){
						if(document.getElementById("quadroBuscaGrupoUsuario").style.display == "block"){
							document.getElementById("quadroBuscaGrupoUsuario").style.display =	"none";
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
