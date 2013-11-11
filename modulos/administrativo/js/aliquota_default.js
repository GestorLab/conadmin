function busca_aliquota(IdServico,Erro,Local){
	if(IdServico == ''){
		IdServico = 0;
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
    url = "xml/aliquota.php?IdServico="+IdServico;
	
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
					document.formulario.IdAliquotaTipo.value	= '';
					
					for(j=0; j<document.formulario.length; j++){
						if(document.formulario[j].name.substr(0,12) == "AliquotaICMS"){
							document.formulario[j].value = "0,0000";
						}
						
						if(document.formulario[j].name.substr(0,28) == "FatorBaseCalculoAliquotaICMS"){
							document.formulario[j].value = "1,0000";
						}
						
						if(document.formulario[j].name.substr(0,13) == 'AliquotaICMS_'){
							document.formulario[j].disabled = false;
						}
					}
					
					// Fim de Carregando
					carregando(false);
				}else{
					nameNode = xmlhttp.responseXML.getElementsByTagName("IdAliquotaTipo")[0]; 
					nameTextNode = nameNode.childNodes[0];
					var IdAliquotaTipo = nameTextNode.nodeValue;
					
					document.formulario.IdAliquotaTipo.value	= IdAliquotaTipo;
					
					for(var i=0; i<xmlhttp.responseXML.getElementsByTagName("IdPais").length; i++){
						nameNode = xmlhttp.responseXML.getElementsByTagName("IdPais")[i]; 
						nameTextNode = nameNode.childNodes[0];
						var IdPais = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("IdEstado")[i]; 
						nameTextNode = nameNode.childNodes[0];
						var IdEstado = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("AliquotaICMS")[i]; 
						nameTextNode = nameNode.childNodes[0];
						var AliquotaICMS = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("FatorBaseCalculoAliquotaICMS")[i]; 
						nameTextNode = nameNode.childNodes[0];
						var FatorBaseCalculoAliquotaICMS = nameTextNode.nodeValue;
						
						for(j=0; j<document.formulario.length; j++){
							if(document.formulario[j].name.substr(0,8) == "IdEstado"){
								if(document.formulario[j].value == IdEstado){
									if(AliquotaICMS != ''){
										document.formulario[j+2].value = formata_float(AliquotaICMS).replace('.',',');
									} else{
										document.formulario[j+2].value = '';
									}
									
									document.formulario[j+3].value = formata_float(FatorBaseCalculoAliquotaICMS).replace('.',',');
								}
							} else{
								if(document.formulario[j].name.substr(0,13) == 'AliquotaICMS_'){
									if(document.formulario.IdCategoriaTributaria.value == 1 && IdAliquotaTipo == 1){
										document.formulario[j].value = '';
										document.formulario[j].readOnly = true;
									} else{
										document.formulario[j].readOnly = false;
									}
								}
							}
						}
						
						document.formulario.Acao.value	= 'alterar';
					}
				}	
				
				if(window.janela != undefined){
					window.janela.close();
				}
				//verificaAcao();
			}
			// Fim de Carregando
			carregando(false);
		} 
		return true;
	}
	xmlhttp.send(null);
}