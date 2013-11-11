function busca_servico_parametro_nota_fiscal(IdServico,IdNotaFiscalLayout,Erro,Local){
	if(IdServico == '' || IdServico == undefined){
		IdServico = 0;
	}
	
	if(IdNotaFiscalLayout == '' || IdNotaFiscalLayout == undefined){
		IdNotaFiscalLayout = 0;
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

    url = "xml/servico_parametro_nota_fiscal.php?IdServico="+IdServico+"&IdNotaFiscalLayout="+IdNotaFiscalLayout;
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
					// Fim de Carregando
					carregando(false);
				}else{					
					var aux = "";
											
					for(var i=0; i<xmlhttp.responseXML.getElementsByTagName("IdNotaFiscalLayoutParametro").length; i++){					
										
						nameNode = xmlhttp.responseXML.getElementsByTagName("IdNotaFiscalLayoutParametro")[i]; 
						nameTextNode = nameNode.childNodes[0];
						var IdNotaFiscalLayoutParametro = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("IdNotaFiscalLayout")[i]; 
						nameTextNode = nameNode.childNodes[0];
						var IdNotaFiscalLayout = nameTextNode.nodeValue;	
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("Valor")[i]; 
						nameTextNode = nameNode.childNodes[0];
						var Valor = nameTextNode.nodeValue;											

						aux = IdNotaFiscalLayout+"_"+IdNotaFiscalLayoutParametro;						
											
						for(ii=0; ii<document.formulario.length; ii++){
							if(document.formulario[ii].name.substr(0,28) == "IdNotaFiscalLayoutParametro_"){							
								if(document.formulario[ii].value == aux){
									document.formulario[ii+2].value = Valor;
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

