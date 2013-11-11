function janela_busca_arquivo_remessa_tipo(){
	janelas('../administrativo/busca_arquivo_remessa_tipo.php',360,283,250,100,'');
}
function busca_arquivo_remessa_tipo(IdArquivoRemessaTipo,Erro,Local,IdContaReceber,IdLocalCobranca){
	if(Local == '' || Local == undefined){
		Local = document.formulario.Local.value;
	}
	if(IdContaReceber == undefined){
		IdContaReceber = '';
	}
	if(IdLocalCobranca == undefined){
		IdLocalCobranca = '';
	}
	if(IdArquivoRemessaTipo == undefined){
		IdArquivoRemessaTipo = '';
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
    
   	url = "xml/arquivo_remessa_tipo.php?IdArquivoRemessaTipo="+IdArquivoRemessaTipo+"&IdContaReceber="+IdContaReceber+"&IdLocalCobranca="+IdLocalCobranca;
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
						case 'LocalCobranca':
							document.formulario.IdArquivoRemessaTipo.value			=	"";
							document.formulario.DescricaoArquivoRemessaTipo.value	=	"";
							document.formulario.IdArquivoRemessaTipo.focus();
							break;
						case 'ArquivoRemessa':
							document.formulario.IdArquivoRemessaTipo.value			=	"";
							document.formulario.DescricaoArquivoRemessaTipo.value	=	"";
							break;
					}
															
					// Fim de Carregando
					carregando(false);
					verificaAcao();
				}else{
				
					nameNode = xmlhttp.responseXML.getElementsByTagName("IdArquivoRemessaTipo")[0]; 
					nameTextNode = nameNode.childNodes[0];
					IdArquivoRemessaTipo = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoArquivoRemessaTipo")[0]; 
					nameTextNode = nameNode.childNodes[0];
					var DescricaoArquivoRemessaTipo = nameTextNode.nodeValue;						
			
					switch(Local){
						case 'LocalCobranca':
							document.formulario.IdArquivoRemessaTipo.value 			= IdArquivoRemessaTipo;
							document.formulario.DescricaoArquivoRemessaTipo.value 	= DescricaoArquivoRemessaTipo;
							break;
						case 'ArquivoRemessa':
							document.formulario.IdArquivoRemessaTipo.value 			= IdArquivoRemessaTipo;
							document.formulario.DescricaoArquivoRemessaTipo.value 	= DescricaoArquivoRemessaTipo;
							break;
						case 'ContaReceber':
							nameNode = xmlhttp.responseXML.getElementsByTagName("OcorrenciaRemessa")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var OcorrenciaRemessa = nameTextNode.nodeValue;
					
							nameNode = xmlhttp.responseXML.getElementsByTagName("OcorrenciaRetorno")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var OcorrenciaRetorno = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("StatusRetorno")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var StatusRetorno = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("IdOcorrenciaRemessa")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var IdOcorrenciaRemessa = nameTextNode.nodeValue;
					
							nameNode = xmlhttp.responseXML.getElementsByTagName("IdOcorrenciaRetorno")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var IdOcorrenciaRetorno = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("IdStatusRetorno")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var IdStatusRetorno = nameTextNode.nodeValue;
							
							var tempPosicoes	=	OcorrenciaRemessa.split("\n");
							var tempRetornos	=	OcorrenciaRetorno.split("\n");
							var tempMotivos		=	StatusRetorno.split("\n");

							addOption(document.formulario.IdOcorrenciaRemessa,"","");
							addOption(document.formulario.IdOcorrenciaRetorno,"","");
							addOption(document.formulario.IdStatusRetorno,"","");

							var cont = 0;
							while(tempPosicoes[cont]){
							
								var temp	=	tempPosicoes[cont].split("^");
								
								addOption(document.formulario.IdOcorrenciaRemessa,temp[1],temp[0]);
								
								if(IdOcorrenciaRemessa == temp[0]){
									document.formulario.IdOcorrenciaRemessa[cont].selected = true;
								}
								
								cont++;
							}
							
							var cont = 0;
							while(tempRetornos[cont]){
							
								var temp	=	tempRetornos[cont].split("^");
								
								addOption(document.formulario.IdOcorrenciaRetorno,temp[1],temp[0]);
								
								if(IdOcorrenciaRetorno == temp[0]){
									document.formulario.IdOcorrenciaRetorno[cont].selected = true;
								}
								
								cont++;
							}
							
							var cont = 0;
							while(tempMotivos[cont]){
							
								var temp	=	tempMotivos[cont].split("^");
								
								addOption(document.formulario.IdStatusRetorno,temp[1],temp[0]);
								
								if(IdStatusRetorno == temp[0]){
									document.formulario.IdStatusRetorno[cont].selected = true;
								}
								
								cont++;
							}
							
							if(IdOcorrenciaRetorno !=''){
								for(ii=0;ii<document.formulario.IdOcorrenciaRetorno.length;ii++){
									if(IdOcorrenciaRetorno == document.formulario.IdOcorrenciaRetorno[ii].value){
										document.formulario.IdOcorrenciaRetorno[ii].selected = true;	
										ii	=	document.formulario.IdOcorrenciaRetorno.length;
									}
								}
							}else{
								document.formulario.IdOcorrenciaRetorno[0].selected = true;	
							}
							
							if(IdOcorrenciaRemessa != ''){
								for(ii=0;ii<document.formulario.IdOcorrenciaRemessa.length;ii++){
									if(IdOcorrenciaRemessa == document.formulario.IdOcorrenciaRemessa[ii].value){
										document.formulario.IdOcorrenciaRemessa[ii].selected = true;	
										ii	=	document.formulario.IdOcorrenciaRemessa.length;
									}
								}
							}else{
								document.formulario.IdOcorrenciaRemessa[0].selected = true;	
							}
							
							if(IdStatusRetorno !=''){
								for(ii=0;ii<document.formulario.IdStatusRetorno.length;ii++){
									if(IdStatusRetorno == document.formulario.IdStatusRetorno[ii].value){
										document.formulario.IdStatusRetorno[ii].selected = true;	
										ii	=	document.formulario.IdStatusRetorno.length;
									}
								}
							}else{
								document.formulario.IdStatusRetorno[0].selected = true;	
							}
							
							break;
					}		
						
				}
				if(document.getElementById("quadroBuscaArquivoRemessaTipo") != null){
					if(document.getElementById("quadroBuscaArquivoRemessaTipo").style.display == "block"){
						document.getElementById("quadroBuscaArquivoRemessaTipo").style.display =	"none";
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
