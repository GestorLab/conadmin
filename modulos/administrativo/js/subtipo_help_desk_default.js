	function busca_subtipo_help_desk(IdSubTipo,IdTipo,Erro,Local){
		if(IdSubTipo == '' || IdSubTipo == undefined){
			IdSubTipo = 0;
		}
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
	    
	   	url = "xml/subtipo_help_desk.php?IdTipo="+IdTipo+"&IdSubTipo="+IdSubTipo;

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
							case 'SubTipoHelpDesk':
								addParmUrl("marTipoHelpDesk","IdTipo","");
								
								document.formulario.IdSubTipo.value					= '';
								document.formulario.DescricaoSubTipo.value			= '';
								document.formulario.IdStatusSubTipo.value			= '';
								document.formulario.DataCriacao.value				= '';
								document.formulario.LoginCriacao.value				= '';
								document.formulario.DataAlteracao.value				= '';
								document.formulario.LoginAlteracao.value			= '';
								document.formulario.Acao.value						= 'inserir';
								
								document.formulario.IdSubTipo.focus();
								break;
						}
						// Fim de Carregando
						carregando(false);
					}else{
						nameNode = xmlhttp.responseXML.getElementsByTagName("IdTipo")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var IdTipo = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("IdSubTipo")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var IdSubTipo = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoSubTipo")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var DescricaoSubTipo = nameTextNode.nodeValue;
						
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
							case 'SubTipoHelpDesk':
								addParmUrl("marHelpDesk","IdSubTipo",IdSubTipo);
								addParmUrl("marTipoHelpDesk","IdTipo",IdTipo);
								addParmUrl("marSubTipoHelpDesk","IdTipo",IdTipo);
								
								document.formulario.IdSubTipo.value					= 	IdSubTipo;
								document.formulario.DescricaoSubTipo.value			=	DescricaoSubTipo;
								document.formulario.IdStatusSubTipo.value			=	IdStatus;
								document.formulario.DataCriacao.value				=	dateFormat(DataCriacao);
								document.formulario.LoginCriacao.value				=	LoginCriacao;
								document.formulario.DataAlteracao.value				=	dateFormat(DataAlteracao);
								document.formulario.LoginAlteracao.value			=	LoginAlteracao;
								document.formulario.Acao.value						=	'alterar';
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
