function busca_forma_aviso_cobranca(IdFormaAvisoCobranca,Erro,Local){
	if(IdFormaAvisoCobranca == '' || IdFormaAvisoCobranca == undefined){
		IdFormaAvisoCobranca = 0;
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

   	url = "xml/forma_aviso_cobranca.php?IdFormaAvisoCobranca="+IdFormaAvisoCobranca;
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
				//alert(xmlhttp.responseText);
				if(xmlhttp.responseText == 'false'){
					if(Local == 'FormaAvisoCobranca'){
						document.formulario.IdFormaAvisoCobranca.value			=	"";
						document.formulario.DescricaoFormaAvisoCobranca.value	=	"";
						document.formulario.IdGrupoUsuarioMonitor.value			=	"";
						document.formulario.ViaEmail.checked					=	false;
						document.formulario.ViaImpressa.checked					=	false;
						document.formulario.MarcadorEstrela.checked				= 	false;										
						document.formulario.MarcadorQuadrado.checked			= 	false;
						document.formulario.MarcadorCirculo.checked				= 	false;
						document.formulario.MarcadorPositivo.checked			= 	false;
						document.formulario.DataCriacao.value					=	"";
						document.formulario.LoginCriacao.value					=	"";
						document.formulario.DataAlteracao.value					=	"";
						document.formulario.LoginAlteracao.value				=	"";
						document.formulario.Acao.value 							= 	'inserir';
											
						document.formulario.IdFormaAvisoCobranca.focus();
						verificaAcao();
					}
					
					// Fim de Carregando
					carregando(false);
				}else{
					nameNode = xmlhttp.responseXML.getElementsByTagName("IdFormaAvisoCobranca")[0]; 
					nameTextNode = nameNode.childNodes[0];
					var IdFormaAvisoCobranca = nameTextNode.nodeValue;
					
					if(Local == 'FormaAvisoCobranca'){
							
						addParmUrl("marFormaAvisoCobranca","IdFormaAvisoCobranca",IdFormaAvisoCobranca);

						nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoFormaAvisoCobranca")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var DescricaoFormaAvisoCobranca = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("IdGrupoUsuarioMonitor")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var IdGrupoUsuarioMonitor = nameTextNode.nodeValue;

						nameNode = xmlhttp.responseXML.getElementsByTagName("ViaEmail")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var ViaEmail = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("ViaImpressa")[0];
						nameTextNode = nameNode.childNodes[0];
						var ViaImpressa = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("MarcadorEstrela")[0];
						nameTextNode = nameNode.childNodes[0];
						var MarcadorEstrela = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("MarcadorQuadrado")[0];
						nameTextNode = nameNode.childNodes[0];
						var MarcadorQuadrado = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("MarcadorCirculo")[0];
						nameTextNode = nameNode.childNodes[0];
						var MarcadorCirculo = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("MarcadorPositivo")[0];
						nameTextNode = nameNode.childNodes[0];
						var MarcadorPositivo = nameTextNode.nodeValue;
												
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
						
											
						document.formulario.IdFormaAvisoCobranca.value				=	IdFormaAvisoCobranca;
						document.formulario.DescricaoFormaAvisoCobranca.value		= 	DescricaoFormaAvisoCobranca;
						document.formulario.IdGrupoUsuarioMonitor.value				=	IdGrupoUsuarioMonitor;
						document.formulario.DataCriacao.value 						= 	dateFormat(DataCriacao);
						document.formulario.LoginCriacao.value 						= 	LoginCriacao;
						document.formulario.DataAlteracao.value 					= 	dateFormat(DataAlteracao);
						document.formulario.LoginAlteracao.value					= 	LoginAlteracao;
						document.formulario.Acao.value 								= 	'alterar';
						
						if(ViaEmail == '1'){				document.formulario.ViaEmail.checked				=	true;	}else{		document.formulario.ViaEmail.checked				=	false;		}					
						if(ViaImpressa == '1'){				document.formulario.ViaImpressa.checked				=	true;	}else{		document.formulario.ViaImpressa.checked				=	false;		}					
						if(MarcadorEstrela == '1'){			document.formulario.MarcadorEstrela.checked			=	true;	}else{		document.formulario.MarcadorEstrela.checked			=	false;		}					
						if(MarcadorQuadrado == '1'){		document.formulario.MarcadorQuadrado.checked		=	true;	}else{		document.formulario.MarcadorQuadrado.checked		=	false;		}					
						if(MarcadorCirculo == '1'){			document.formulario.MarcadorCirculo.checked			=	true;	}else{		document.formulario.MarcadorCirculo.checked			=	false;		}					
						if(MarcadorPositivo == '1'){		document.formulario.MarcadorPositivo.checked		=	true;	}else{		document.formulario.MarcadorPositivo.checked		=	false;		}					

						verificaAcao();
					}	
				}
				if(window.janela != undefined){
					window.janela.close();
				}
			}// fim do else
			// Fim de Carregando
			carregando(false);
		}//fim do if status
		return true;
	}
	xmlhttp.send(null);
}

