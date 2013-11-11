function busca_local_cobranca_parametro(IdLocalCobranca,Erro,Local){
	if(IdLocalCobranca == '' || IdLocalCobranca == undefined){
		IdLocalCobranca = 0;
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

    url = "xml/local_cobranca_parametro.php?IdLocalCobranca="+IdLocalCobranca;
	xmlhttp.open("GET", url,true);
	xmlhttp.onreadystatechange = function(){ 

		// Carregando...
		carregando(true);

		if(xmlhttp.readyState == 4){ 
			if(xmlhttp.status == 200){	
				//alert(xmlhttp.responseText);		
				if(Erro != false){
					document.formulario.Erro.value = 0;
					verificaErro();
				}
				if(xmlhttp.responseText == 'false'){					
					addParmUrl("marLocalCobrancaParametroNovo","IdLocalCobrancaParametro","");
					addParmUrl("marLocalCobrancaParametroNovo","IdLocalCobranca","");
					addParmUrl("marLocalCobrancaParametroContrato","IdLocalCobranca","");
					addParmUrl("marLocalCobrancaParametroContratoNovo","IdLocalCobranca","");			
					
					// Fim de Carregando
					carregando(false);
				}else{					
					var aux = "";

					addParmUrl("marLocalCobrancaParametro","IdLocalCobrancaParametro",IdLocalCobrancaParametro);
					addParmUrl("marLocalCobrancaParametroNovo","IdLocalCobranca",IdLocalCobranca);
					addParmUrl("marLocalCobrancaParametroContrato","IdLocalCobranca",IdLocalCobranca);
					addParmUrl("marLocalCobrancaParametroContratoNovo","IdLocalCobranca",IdLocalCobranca);	
											
					for(var i=0; i<xmlhttp.responseXML.getElementsByTagName("IdLocalCobrancaParametro").length; i++){
			
						nameNode = xmlhttp.responseXML.getElementsByTagName("IdLocalCobrancaLayout")[i]; 
						nameTextNode = nameNode.childNodes[0];
						var IdLocalCobrancaLayout = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("IdLocalCobrancaParametro")[i]; 
						nameTextNode = nameNode.childNodes[0];
						var IdLocalCobrancaParametro = nameTextNode.nodeValue;	
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("ValorLocalCobrancaParametro")[i]; 
						nameTextNode = nameNode.childNodes[0];
						var ValorLocalCobrancaParametro = nameTextNode.nodeValue;											

						aux = IdLocalCobrancaLayout+"_"+IdLocalCobrancaParametro;						
						
						for(ii=0; ii<document.formulario.length; ii++){
							if(document.formulario[ii].name.substr(0,31) == "IdLocalCobrancaLayoutParametro_"){
								if(document.formulario[ii].value == aux){
									document.formulario[ii+2].value = ValorLocalCobrancaParametro;
									break;
								}
							}	
						}											
					}
					document.getElementById('cpDadosParametros').style.display = 'block';
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

