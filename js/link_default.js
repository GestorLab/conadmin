function janela_busca_link(){
	janelas('busca_link.php',360,283,250,100,'');
}
function busca_link(IdLink,Erro,Local){
	if(IdLink == '' || IdLink == undefined){
		IdLink = 0;
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

   	url = "xml/link.php?IdLink="+IdLink;
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
					switch(Local){
						case 'Link':
							
							document.formulario.IdLink.value				=	"";	
							document.formulario.DescricaoLink.value			=	"";
							document.formulario.Site.value					= 	"";
							document.formulario.DataCriacao.value 			= 	"";
							document.formulario.LoginCriacao.value 			= 	"";
							document.formulario.DataAlteracao.value 		= 	"";
							document.formulario.LoginAlteracao.value		= 	"";
							document.formulario.Acao.value 					= 	'inserir';
							
							addParmUrl("marLink","IdLink",'');
							
							busca_link(IdLink,false,Local);
							verificaAcao();
							break;
						case 'Carteira':
							addParmUrl("marAgenteAutorizado","IdLink",'');
							addParmUrl("marAgenteAutorizadoComissionamento","IdLink",'');
							addParmUrl("marAgenteAutorizadoComissionamentoNovo","IdLink",'');
							addParmUrl("marCarteira","IdLink",'');
							addParmUrl("marCarteiraNovo","IdLink",'');
							
							document.formulario.IdLink.value	=	"";	
							document.formulario.NomeAgenteAutorizado.value	=	"";
							document.formulario.IdCarteira.value			=	"";	
							document.formulario.Nome.value					=	"";						
							document.formulario.DataCriacao.value 			= 	'';
							document.formulario.LoginCriacao.value 			= 	'';
							document.formulario.DataAlteracao.value 		= 	'';
							document.formulario.LoginAlteracao.value		= 	'';
							document.formulario.Acao.value 					= 	'inserir';
							
							document.formulario.IdLink.focus();
							
							verificaAcao();
							break;
						case 'Contrato':
							document.formulario.IdLink.value	=	"";
							document.formulario.NomeAgenteAutorizado.value	=	"";
							
							while(document.formulario.IdCarteira.options.length > 0){
								document.formulario.IdCarteira.options[0] = null;
							}
							
							document.formulario.IdLink.focus();
							break;
						default: //AgenteAutorizadoComissao - CarteiraComissao
							document.formulario.IdLink.value	=	"";
							document.formulario.Nome.value					=	"";							
							document.formulario.IdLink.focus();
					}
					
					
					// Fim de Carregando
					carregando(false);
				}else{
					nameNode = xmlhttp.responseXML.getElementsByTagName("IdLink")[0]; 
					nameTextNode = nameNode.childNodes[0];
					var IdLink = nameTextNode.nodeValue;	
				
					nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoLink")[0]; 
					nameTextNode = nameNode.childNodes[0];
					var DescricaoLink = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("Link")[0]; 
					nameTextNode = nameNode.childNodes[0];
					var Site = nameTextNode.nodeValue;
					
					switch(Local){
						case 'Link':
							
							addParmUrl("marLink","IdLink",IdLink);
													
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
							
							document.formulario.IdLink.value				=	IdLink;	
							document.formulario.DescricaoLink.value			=	DescricaoLink;
							document.formulario.Site.value					=   Site;
							document.formulario.DataCriacao.value 			= 	dateFormat(DataCriacao);
							document.formulario.LoginCriacao.value 			= 	LoginCriacao;
							document.formulario.DataAlteracao.value 		= 	dateFormat(DataAlteracao);
							document.formulario.LoginAlteracao.value		= 	LoginAlteracao;
							document.formulario.Acao.value 					= 	'alterar';
							verificaAcao();
							break;
						case 'Carteira':
							
							addParmUrl("marAgenteAutorizado","IdLink",IdLink);
							addParmUrl("marAgenteAutorizadoComissionamento","IdLink",IdLink);
							addParmUrl("marAgenteAutorizadoComissionamentoNovo","IdLink",IdLink);
							addParmUrl("marCarteira","IdLink",IdLink);
							addParmUrl("marCarteiraNovo","IdLink",IdLink);
													
							document.formulario.IdLink.value	=	IdLink;	
							document.formulario.NomeAgenteAutorizado.value	=	RazaoSocial;								
							document.formulario.IdCarteira.value 			= 	'';
							document.formulario.Nome.value 					= 	'';							
							document.formulario.DataCriacao.value 			= 	'';
							document.formulario.LoginCriacao.value 			= 	'';
							document.formulario.DataAlteracao.value 		= 	'';
							document.formulario.LoginAlteracao.value		= 	'';
							document.formulario.Acao.value 					= 	'inserir';
							
							document.formulario.IdCarteira.focus();
							verificaAcao();
							break;
						case 'Contrato':
							document.formulario.IdLink.value	=	IdLink;
							document.formulario.NomeAgenteAutorizado.value	=	RazaoSocial;
							
							listar_carteira(IdLink);
							break;
						default:
							document.formulario.IdLink.value				=	IdLink;
							document.formulario.Nome.value					=	RazaoSocial;
					}
				}	
				if(window.janela != undefined){
					window.janela.close();
				}
			}
			// Fim de Carregando
			carregando(false);
		} 
		return true;
	}
	xmlhttp.send(null);
}

