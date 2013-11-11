	function janela_busca_tipo_email(){
		janelas('busca_tipo_email.php',360,283,250,100,'');
	}
	function busca_tipo_email(IdTipoEmail, Erro, Local){
		if(IdTipoEmail == ''){
			IdTipoEmail = 0;
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
		url = "xml/tipo_email.php?IdTipoEmail="+IdTipoEmail;
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
						
						document.formulario.IdTipoEmail.value 			= '';
						document.formulario.DescricaoTipoEmail.value 	= '';
						
						switch (Local){
							case "TipoEmail":
								//addParmUrl("marTipoEmail","IdTipoEmail",'');
								
								document.formulario.DiasParaEnvio.value			= '';
								document.formulario.AssuntoEmail.value 			= '';
								document.formulario.EstruturaEmail.value 		= '';
								document.formulario.Acao.value 					= 'inserir';
								break;
						}
						
						document.formulario.IdTipoEmail.focus();
						
						// Fim de Carregando
						carregando(false);
					}else{
						nameNode = xmlhttp.responseXML.getElementsByTagName("IdLoja")[0]; 
						nameTextNode = nameNode.childNodes[0];
						IdLoja = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("IdTipoEmail")[0]; 
						nameTextNode = nameNode.childNodes[0];
						IdTipoEmail = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoTipoEmail")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var DescricaoTipoEmail = nameTextNode.nodeValue;
												
						document.formulario.IdTipoEmail.value		  = IdTipoEmail;
						document.formulario.DescricaoTipoEmail.value  = DescricaoTipoEmail;
						
						switch (Local){		
							case "TipoEmail":
								//addParmUrl("marTipoEmail","IdTipoEmail",IdTipoEmail);
								
								nameNode = xmlhttp.responseXML.getElementsByTagName("DiasParaEnvio")[0]; 
								nameTextNode = nameNode.childNodes[0];
								var DiasParaEnvio = nameTextNode.nodeValue;
							
								nameNode = xmlhttp.responseXML.getElementsByTagName("AssuntoEmail")[0]; 
								nameTextNode = nameNode.childNodes[0];
								var AssuntoEmail = nameTextNode.nodeValue;					
							
								nameNode = xmlhttp.responseXML.getElementsByTagName("EstruturaEmail")[0]; 
								nameTextNode = nameNode.childNodes[0];
								var EstruturaEmail = nameTextNode.nodeValue;
							
								document.formulario.DiasParaEnvio.value 				= DiasParaEnvio;
								document.formulario.AssuntoEmail.value 					= AssuntoEmail;
								document.formulario.EstruturaEmail.value 				= EstruturaEmail;
								document.formulario.Acao.value 							= 'alterar';
								
								window.parent.email.location.replace("../../visualizar_tipo_email.php?IdLoja="+IdLoja+"&IdTipoEmail="+IdTipoEmail);
								break;
						}
					}
					if(document.getElementById("quadroBuscaTipoEmail") != null){
						if(document.getElementById("quadroBuscaTipoEmail").style.display == "block"){
							document.getElementById("quadroBuscaTipoEmail").style.display =	"none";
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
	
