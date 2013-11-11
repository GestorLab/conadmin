	function janela_busca_grupo_permissao(){
		janelas('busca_grupo_permissao.php',360,283,250,100,'');
	}
	function busca_grupo_permissao(IdGrupoPermissao, Erro, Local){
	
		if(IdGrupoPermissao == ''){
			IdGrupoPermissao = 0;
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
	    
	   	url = "xml/grupo_permissao.php?IdGrupoPermissao="+IdGrupoPermissao;
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
						
						document.formulario.IdGrupoPermissao.value 			= '';
						document.formulario.DescricaoGrupoPermissao.value 	= '';
						
						if(Local == undefined){
							document.formulario.LimiteVisualizacao.value	= '';
							document.formulario.IpAcesso.value 				= '';
							document.formulario.DataCriacao.value 			= '';
							document.formulario.LoginCriacao.value 			= '';
							document.formulario.DataAlteracao.value 		= '';
							document.formulario.LoginAlteracao.value		= '';
							document.formulario.Acao.value 					= 'inserir';
							
							busca_usuario_grupo_permissao("",false);
						}
						document.formulario.IdGrupoPermissao.focus();
						
						// Fim de Carregando
						carregando(false);
					}else{
						nameNode = xmlhttp.responseXML.getElementsByTagName("IdGrupoPermissao")[0]; 
						nameTextNode = nameNode.childNodes[0];
						IdGrupoPermissao = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoGrupoPermissao")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var DescricaoGrupoPermissao = nameTextNode.nodeValue;
						
						document.formulario.IdGrupoPermissao.value		  = IdGrupoPermissao;
						document.formulario.DescricaoGrupoPermissao.value = DescricaoGrupoPermissao;
						
						if(Local == undefined){
							busca_usuario_grupo_permissao(IdGrupoPermissao,false);			
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("LimiteVisualizacao")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var LimiteVisualizacao = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("IpAcesso")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var IpAcesso = nameTextNode.nodeValue;
							
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
							
							addParmUrl("marGrupoPermissao","IdGrupoPermissao",IdGrupoPermissao);
							addParmUrl("marGrupoPermissaoPermissao","IdGrupoPermissao",IdGrupoPermissao);
							
							document.formulario.LimiteVisualizacao.value 	= LimiteVisualizacao;
							document.formulario.IpAcesso.value 				= IpAcesso;
							document.formulario.DataCriacao.value 			= dateFormat(DataCriacao);
							document.formulario.LoginCriacao.value 			= LoginCriacao;
							document.formulario.DataAlteracao.value 		= dateFormat(DataAlteracao);
							document.formulario.LoginAlteracao.value		= LoginAlteracao;
							document.formulario.Acao.value 					= 'alterar';
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
