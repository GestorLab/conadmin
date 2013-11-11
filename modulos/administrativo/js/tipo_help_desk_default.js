	function busca_tipo_help_desk(IdTipo,Erro,Local){
		if(IdTipo == '' || IdTipo == undefined){
			IdTipo = 0;
		}
		if(Local == '' || Local == undefined){
			Local	=	document.formulario.Local.value;
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
	    
	   	url = "xml/tipo_help_desk.php?IdTipo="+IdTipo;

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
						switch(Local){
							case 'TipoHelpDesk':
								addParmUrl("marTipoHelpDesk","IdTipo","");
								
								document.formulario.IdTipo.value					= '';
								document.formulario.DescricaoTipo.value				= '';
								document.formulario.IdStatus.value					= '';
								document.formulario.DataCriacao.value				= '';
								document.formulario.LoginCriacao.value				= '';
								document.formulario.DataAlteracao.value				= '';
								document.formulario.LoginAlteracao.value			= '';
								document.formulario.Acao.value						= 'inserir';
								
								document.formulario.IdTipo.focus();
								break;
							case 'SubTipoHelpDesk':
								addParmUrl("marTipoHelpDesk","IdTipo","");
								
								document.formulario.IdTipo.value					= '';
								document.formulario.DescricaoTipo.value				= '';
								document.formulario.IdStatus.value					= '';
								document.formulario.IdSubTipo.value					= '';
								document.formulario.DescricaoSubTipo.value			= '';
								document.formulario.IdStatusSubTipo.value			= '';
								document.formulario.DataCriacao.value				= '';
								document.formulario.LoginCriacao.value				= '';
								document.formulario.DataAlteracao.value				= '';
								document.formulario.LoginAlteracao.value			= '';
								break;
						}
						// Fim de Carregando
						carregando(false);
					}else{
						nameNode = xmlhttp.responseXML.getElementsByTagName("IdTipo")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var IdTipo = nameTextNode.nodeValue;
								
						nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoTipo")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var DescricaoTipo = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("IdStatus")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var IdStatus = nameTextNode.nodeValue;
						
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
						
						switch(Local){
							case 'TipoHelpDesk':
								addParmUrl("marHelpDesk","IdTipo",IdTipo);
								addParmUrl("marTipoHelpDesk","IdTipo",IdTipo);
								addParmUrl("marSubTipoHelpDesk","IdTipo",IdTipo);
								addParmUrl("marSubTipoHelpDeskNovo","IdTipo",IdTipo);
								
								document.formulario.IdTipo.value					= 	IdTipo;
								document.formulario.DescricaoTipo.value				=	DescricaoTipo;
								document.formulario.IdStatus.value					=	IdStatus;
								document.formulario.DataCriacao.value				=	dateFormat(DataCriacao);
								document.formulario.LoginCriacao.value				=	LoginCriacao;
								document.formulario.DataAlteracao.value				=	dateFormat(DataAlteracao);
								document.formulario.LoginAlteracao.value			=	LoginAlteracao;
								document.formulario.Acao.value						=	'alterar';
								break;
							case 'SubTipoHelpDesk':
								addParmUrl("marHelpDesk","IdTipo",IdTipo);
								addParmUrl("marTipoHelpDesk","IdTipo",IdTipo);
								addParmUrl("marTipoHelpDeskNovo","IdTipo",IdTipo);
								addParmUrl("marSubTipoHelpDesk","IdTipo",IdTipo);
								
								document.formulario.IdTipo.value					= 	IdTipo;
								document.formulario.DescricaoTipo.value				=	DescricaoTipo;
								document.formulario.IdStatus.value					=	IdStatus;
								document.formulario.IdSubTipo.value					= '';
								document.formulario.DescricaoSubTipo.value			= '';
								document.formulario.IdStatusSubTipo.value			= '';
								document.formulario.DataCriacao.value				= '';
								document.formulario.LoginCriacao.value				= '';
								document.formulario.DataAlteracao.value				= '';
								document.formulario.LoginAlteracao.value			= '';
								break;
						}
					}
					if(window.janela != undefined){
						window.janela.close();
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
