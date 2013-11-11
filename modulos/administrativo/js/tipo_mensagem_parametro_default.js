function busca_tipo_mensagem_parametro(IdTipoMensagem,Erro,Local){	
	if(IdTipoMensagem == '' || IdTipoMensagem == undefined){
		IdTipoMensagem = 0;
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

    url = "xml/tipo_mensagem_parametro.php?IdTipoMensagem="+IdTipoMensagem;

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
					// Fim de Carregando
					carregando(false);
				}else{					
					var aux = "";
					
					for(var i=0; i<xmlhttp.responseXML.getElementsByTagName("IdTipoMensagem").length; i++){
			
						nameNode = xmlhttp.responseXML.getElementsByTagName("IdTipoMensagem")[i]; 
						nameTextNode = nameNode.childNodes[0];
						var IdTipoMensagem = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("IdTipoMensagemParametro")[i]; 
						nameTextNode = nameNode.childNodes[0];
						var IdTipoMensagemParametro = nameTextNode.nodeValue;	

						nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoTipoMensagemParametro")[i]; 
						nameTextNode = nameNode.childNodes[0];
						var DescricaoTipoMensagemParametro = nameTextNode.nodeValue;	
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("ValorTipoMensagemParametro")[i]; 
						nameTextNode = nameNode.childNodes[0];
						var ValorTipoMensagemParametro = nameTextNode.nodeValue;											

						aux = IdTipoMensagem+"_"+IdTipoMensagemParametro;						
						
						for(ii=0; ii<document.formulario.length; ii++){
							if(document.formulario[ii].name.substr(0,15) == "IdTipoMensagem_"){
								if(document.formulario[ii].value == aux){
									document.formulario[ii+2].value = ValorTipoMensagemParametro;
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