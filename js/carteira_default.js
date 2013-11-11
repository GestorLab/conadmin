function janela_busca_carteira(){
	janelas('busca_carteira.php',530,350,250,100,'');
}
function busca_carteira(IdAgenteAutorizado,IdCarteira,Erro,Local){
	if(IdAgenteAutorizado == '' || IdAgenteAutorizado == undefined){
		IdAgenteAutorizado = 0;
	}
	if(IdCarteira == '' || IdCarteira == undefined){
		IdCarteira = 0;
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

   	url = "xml/carteira.php?IdAgenteAutorizado="+IdAgenteAutorizado+"&IdCarteira="+IdCarteira;
	xmlhttp.open("GET", url,true);
	
	if(Local == 'Contrato' || Local == 'ContratoServico'){
		url	+=	'&IdStatus=1';
	}  
	
	var IdCarteiraTemp 		   = 	IdCarteira;
	var IdAgenteAutorizadoTemp = 	IdAgenteAutorizado;
	
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
						case 'Carteira':
							addParmUrl("marCarteiraComissionamento","IdCarteira",'');
							addParmUrl("marCarteiraComissionamentoNovo","IdCarteira",'');
							addParmUrl("marCarteira","IdCarteira",'');
							addParmUrl("marAgenteAutorizado","IdAgenteAutorizado",IdAgenteAutorizadoTemp);
							addParmUrl("marAgenteAutorizadoComissionamento","IdAgenteAutorizado",IdAgenteAutorizadoTemp);
							addParmUrl("marAgenteAutorizadoComissionamentoNovo","IdAgenteAutorizado",IdAgenteAutorizadoTemp);
							addParmUrl("marCarteira","IdAgenteAutorizado",IdAgenteAutorizadoTemp);
							addParmUrl("marCarteiraNovo","IdAgenteAutorizado",IdAgenteAutorizadoTemp);
							
							document.formulario.IdCarteira.value			=	"";	
							document.formulario.Nome.value					=	"";	
							document.formulario.IdStatus.value				= 	"";
							document.formulario.DataCriacao.value 			= 	"";
							document.formulario.LoginCriacao.value 			= 	"";
							document.formulario.DataAlteracao.value 		= 	"";
							document.formulario.LoginAlteracao.value		= 	"";
							document.formulario.Acao.value 					= 	'inserir';
							
							busca_pessoa(IdCarteiraTemp,false,Local);
							document.formulario.IdCarteira.focus();
							status_inicial();
							verificaAcao();
							break;
						default: //Contrato - CarteiraComissao
							document.formulario.IdCarteira.value			=	"";
							document.formulario.NomeCarteira.value			=	"";	
							document.formulario.IdCarteira.focus();
					
					}
					
					// Fim de Carregando
					carregando(false);
				}else{
					nameNode = xmlhttp.responseXML.getElementsByTagName("IdCarteira")[0]; 
					nameTextNode = nameNode.childNodes[0];
					var IdCarteira = nameTextNode.nodeValue;	
				
					nameNode = xmlhttp.responseXML.getElementsByTagName("Nome")[0]; 
					nameTextNode = nameNode.childNodes[0];
					var Nome = nameTextNode.nodeValue;
					
					switch(Local){
						case 'Carteira':
							nameNode = xmlhttp.responseXML.getElementsByTagName("IdAgenteAutorizado")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var IdAgenteAutorizado = nameTextNode.nodeValue;	
							
							addParmUrl("marCarteiraComissionamento","IdCarteira",IdCarteira);
							addParmUrl("marCarteiraComissionamentoNovo","IdCarteira",IdCarteira);
							addParmUrl("marCarteira","IdCarteira",IdCarteira);
							addParmUrl("marAgenteAutorizado","IdAgenteAutorizado",IdAgenteAutorizado);
							addParmUrl("marAgenteAutorizadoComissionamento","IdAgenteAutorizado",IdAgenteAutorizado);
							addParmUrl("marAgenteAutorizadoComissionamentoNovo","IdAgenteAutorizado",IdAgenteAutorizado);
							addParmUrl("marCarteira","IdAgenteAutorizado",IdAgenteAutorizado);
							addParmUrl("marCarteiraNovo","IdAgenteAutorizado",IdAgenteAutorizado);
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("IdStatus")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var IdStatus = nameTextNode.nodeValue;
													
							nameNode = xmlhttp.responseXML.getElementsByTagName("NomeAgenteAutorizado")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var NomeAgenteAutorizado = nameTextNode.nodeValue;
						
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
							
							document.formulario.IdAgenteAutorizado.value	=	IdAgenteAutorizado;
							document.formulario.NomeAgenteAutorizado.value	=	NomeAgenteAutorizado;
							document.formulario.IdCarteira.value			=	IdCarteira;	
							document.formulario.Nome.value					=	Nome;
							document.formulario.IdStatus.value				= 	IdStatus;
							document.formulario.DataCriacao.value 			= 	dateFormat(DataCriacao);
							document.formulario.LoginCriacao.value 			= 	LoginCriacao;
							document.formulario.DataAlteracao.value 		= 	dateFormat(DataAlteracao);
							document.formulario.LoginAlteracao.value		= 	LoginAlteracao;	
							document.formulario.Acao.value 					= 	'alterar';
							verificaAcao();
							break;
						default:
							document.formulario.IdCarteira.value			=	IdCarteira;
							document.formulario.NomeCarteira.value			=	Nome;	
					
					}
				}	
				if(document.getElementById("quadroBuscaCarteira") != null){
					if(document.getElementById("quadroBuscaCarteira").style.display == "block"){
						document.getElementById("quadroBuscaCarteira").style.display =	"none";
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

