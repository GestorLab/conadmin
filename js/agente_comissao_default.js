function busca_agente_comissao(IdAgenteAutorizado,IdServico,Parcela,Erro,Local){
	if(IdAgenteAutorizado == '' || IdAgenteAutorizado == undefined){
		IdAgenteAutorizado = 0;
	}
	if(IdServico == '' || IdServico == undefined){
		IdServico = 0;
	}
	if(Parcela == '' || Parcela == undefined){
		Parcela = 0;
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

   	url = "xml/agente_comissao.php?IdAgenteAutorizado="+IdAgenteAutorizado+"&IdServico="+IdServico+"&Parcela="+Parcela;
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
					document.formulario.Percentual.value 			= 	"";
					document.formulario.DataCriacao.value 			= 	"";
					document.formulario.LoginCriacao.value 			= 	"";
					document.formulario.DataAlteracao.value 		= 	"";
					document.formulario.LoginAlteracao.value		= 	"";
					document.formulario.Acao.value 					= 	'inserir';
					
					status_inicial();
					verificaAcao();
							
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
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("IdServico")[0]; 
					nameTextNode = nameNode.childNodes[0];
					var IdServico = nameTextNode.nodeValue;	
				
					nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoServico")[0]; 
					nameTextNode = nameNode.childNodes[0];
					var DescricaoServico = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("Parcela")[0]; 
					nameTextNode = nameNode.childNodes[0];
					var Parcela = nameTextNode.nodeValue;	
				
					nameNode = xmlhttp.responseXML.getElementsByTagName("Percentual")[0]; 
					nameTextNode = nameNode.childNodes[0];
					var Percentual = nameTextNode.nodeValue;
					
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
					
					addParmUrl("marAgenteAutorizado","IdAgenteAutorizado",IdAgenteAutorizado);
					addParmUrl("marAgenteAutorizadoComissionamento","IdAgenteAutorizado",IdAgenteAutorizado);
					addParmUrl("marAgenteAutorizadoComissionamentoNovo","IdAgenteAutorizado",IdAgenteAutorizado);
					addParmUrl("marCarteira","IdAgenteAutorizado",IdAgenteAutorizado);
					addParmUrl("marCarteiraNovo","IdAgenteAutorizado",IdAgenteAutorizado);
					addParmUrl("marCarteiraComissionamento","IdAgenteAutorizado",IdAgenteAutorizado);
					addParmUrl("marCarteiraComissionamentoNovo","IdAgenteAutorizado",IdAgenteAutorizado);
					
					if(RazaoSocial == ""){
						Nome	=	Nome;
					}
					
					document.formulario.IdAgenteAutorizado.value	=	IdAgenteAutorizado;	
					document.formulario.Nome.value					=	RazaoSocial;
					document.formulario.IdServico.value				=	IdServico;	
					document.formulario.DescricaoServico.value		=	DescricaoServico;	
					document.formulario.Parcela.value				=	Parcela;	
					document.formulario.Percentual.value			=	formata_float(Arredonda(Percentual,2),2).replace('.',',');;									
					document.formulario.DataCriacao.value 			= 	dateFormat(DataCriacao);
					document.formulario.LoginCriacao.value 			= 	LoginCriacao;
					document.formulario.DataAlteracao.value 		= 	dateFormat(DataAlteracao);
					document.formulario.LoginAlteracao.value		= 	LoginAlteracao;
					document.formulario.Acao.value 					= 	'alterar';
					
					verificaAcao();
					
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

