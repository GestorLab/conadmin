function janela_busca_agente(){
	janelas('busca_agente.php',530,350,250,100,'');
}
function busca_agente(IdAgenteAutorizado,Erro,Local){
	if(IdAgenteAutorizado == '' || IdAgenteAutorizado == undefined){
		IdAgenteAutorizado = 0;
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

   	url = "xml/agente.php?IdAgenteAutorizado="+IdAgenteAutorizado;
   	
	if(Local == 'Contrato' || Local == 'ContratoServico'){
		url	+=	'&IdStatus=1';
	}   
	   
	xmlhttp.open("GET", url,true);

   	var IdAgente = IdAgenteAutorizado;

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
						case 'AgenteAutorizado':
							
							document.formulario.IdAgenteAutorizado.value	=	"";	
							document.formulario.Nome.value					=	"";
							document.formulario.IdStatus.value				= 	"";
							document.formulario.DataCriacao.value 			= 	"";
							document.formulario.LoginCriacao.value 			= 	"";
							document.formulario.DataAlteracao.value 		= 	"";
							document.formulario.LoginAlteracao.value		= 	"";
							document.formulario.Acao.value 					= 	'inserir';
							
							addParmUrl("marAgenteAutorizado","IdAgenteAutorizado",'');
							addParmUrl("marAgenteAutorizadoComissionamento","IdAgenteAutorizado",'');
							addParmUrl("marAgenteAutorizadoComissionamentoNovo","IdAgenteAutorizado",'');
							addParmUrl("marCarteira","IdAgenteAutorizado",'');
							addParmUrl("marCarteiraNovo","IdAgenteAutorizado",'');
							
							busca_pessoa(IdAgente,false,Local);
							status_inicial();
							verificaAcao();
							break;
						case 'Carteira':
							addParmUrl("marAgenteAutorizado","IdAgenteAutorizado",'');
							addParmUrl("marAgenteAutorizadoComissionamento","IdAgenteAutorizado",'');
							addParmUrl("marAgenteAutorizadoComissionamentoNovo","IdAgenteAutorizado",'');
							addParmUrl("marCarteira","IdAgenteAutorizado",'');
							addParmUrl("marCarteiraNovo","IdAgenteAutorizado",'');
							
							document.formulario.IdAgenteAutorizado.value	=	"";	
							document.formulario.NomeAgenteAutorizado.value	=	"";
							document.formulario.IdCarteira.value			=	"";	
							document.formulario.Nome.value					=	"";						
							document.formulario.DataCriacao.value 			= 	'';
							document.formulario.LoginCriacao.value 			= 	'';
							document.formulario.DataAlteracao.value 		= 	'';
							document.formulario.LoginAlteracao.value		= 	'';
							document.formulario.Acao.value 					= 	'inserir';
							
							document.formulario.IdAgenteAutorizado.focus();
							
							verificaAcao();
							break;
						case 'Contrato':
							document.formulario.IdAgenteAutorizado.value	=	"";
							document.formulario.NomeAgenteAutorizado.value	=	"";
							
							while(document.formulario.IdCarteira.options.length > 0){
								document.formulario.IdCarteira.options[0] = null;
							}
							
							document.formulario.IdAgenteAutorizado.focus();
							break;
						case 'ContratoServico':
							document.formulario.IdAgenteAutorizado.value	=	"";
							document.formulario.NomeAgenteAutorizado.value	=	"";
							
							while(document.formulario.IdCarteira.options.length > 0){
								document.formulario.IdCarteira.options[0] = null;
							}
							
							document.formulario.IdAgenteAutorizado.focus();
							break;
						default: //AgenteAutorizadoComissao - CarteiraComissao
							document.formulario.IdAgenteAutorizado.value	=	"";
							document.formulario.Nome.value					=	"";							
							document.formulario.IdAgenteAutorizado.focus();
					}
					
					
					// Fim de Carregando
					carregando(false);
				}else{
					nameNode = xmlhttp.responseXML.getElementsByTagName("IdAgenteAutorizado")[0]; 
					nameTextNode = nameNode.childNodes[0];
					var IdAgenteAutorizado = nameTextNode.nodeValue;	
				
					nameNode = xmlhttp.responseXML.getElementsByTagName("RazaoSocial")[0]; 
					nameTextNode = nameNode.childNodes[0];
					var RazaoSocial = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("Nome")[0]; 
					nameTextNode = nameNode.childNodes[0];
					var Nome = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("IdStatus")[0]; 
					nameTextNode = nameNode.childNodes[0];
					var IdStatus = nameTextNode.nodeValue;
					
					switch(Local){
						case 'AgenteAutorizado':
							
							addParmUrl("marAgenteAutorizado","IdAgenteAutorizado",IdAgenteAutorizado);
							addParmUrl("marAgenteAutorizadoComissionamento","IdAgenteAutorizado",IdAgenteAutorizado);
							addParmUrl("marAgenteAutorizadoComissionamentoNovo","IdAgenteAutorizado",IdAgenteAutorizado);
							addParmUrl("marCarteira","IdAgenteAutorizado",IdAgenteAutorizado);
							addParmUrl("marCarteiraNovo","IdAgenteAutorizado",IdAgenteAutorizado);
													
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
							document.formulario.Nome.value					=	Nome;			
							document.formulario.IdStatus.value				=	IdStatus;										
							document.formulario.DataCriacao.value 			= 	dateFormat(DataCriacao);
							document.formulario.LoginCriacao.value 			= 	LoginCriacao;
							document.formulario.DataAlteracao.value 		= 	dateFormat(DataAlteracao);
							document.formulario.LoginAlteracao.value		= 	LoginAlteracao;
							document.formulario.Acao.value 					= 	'alterar';
							verificaAcao();
							break;
						case 'Carteira':
							
							addParmUrl("marAgenteAutorizado","IdAgenteAutorizado",IdAgenteAutorizado);
							addParmUrl("marAgenteAutorizadoComissionamento","IdAgenteAutorizado",IdAgenteAutorizado);
							addParmUrl("marAgenteAutorizadoComissionamentoNovo","IdAgenteAutorizado",IdAgenteAutorizado);
							addParmUrl("marCarteira","IdAgenteAutorizado",IdAgenteAutorizado);
							addParmUrl("marCarteiraNovo","IdAgenteAutorizado",IdAgenteAutorizado);
							
							if(RazaoSocial == ""){
								RazaoSocial = Nome;
							} 
													
							document.formulario.IdAgenteAutorizado.value	=	IdAgenteAutorizado;	
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
							if(RazaoSocial == ''){
								RazaoSocial = Nome;
							}
						
							document.formulario.IdAgenteAutorizado.value	=	IdAgenteAutorizado;
							document.formulario.NomeAgenteAutorizado.value	=	RazaoSocial;
							
							listar_carteira(IdAgenteAutorizado);
							break;
						case 'ContratoServico':
							if(RazaoSocial == ''){
								RazaoSocial = Nome;
							}
							
							document.formulario.IdAgenteAutorizado.value	=	IdAgenteAutorizado;
							document.formulario.NomeAgenteAutorizado.value	=	RazaoSocial;
							
							listar_carteira(IdAgenteAutorizado);
							break;
						default:
							if(RazaoSocial == ""){
								RazaoSocial = Nome;
							}
						
							document.formulario.IdAgenteAutorizado.value	=	IdAgenteAutorizado;
							document.formulario.Nome.value					=	RazaoSocial;
					}
				}	
				if(document.getElementById("quadroBuscaAgente") != null){
					if(document.getElementById("quadroBuscaAgente").style.display == "block"){
						document.getElementById("quadroBuscaAgente").style.display =	"none";
					}
				}
				if(document.getElementById("quadroBuscaPessoa") != null){
					if(document.getElementById("quadroBuscaPessoa").style.display == "block"){
						document.getElementById("quadroBuscaPessoa").style.display =	"none";
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

