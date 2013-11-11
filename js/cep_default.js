function busca_cep(CEP, Erro, cpEndereco, cpBairro, cpIdPais, cpIdEstado, cpIdCidade){
	if(CEP == ''){
		CEP = 0;
	}
	var temp = true;
	if(cpEndereco.value!='' || cpBairro.value!='' || cpIdPais.value!='' || cpIdEstado.value!='' || cpIdCidade.value!=''){
		temp	=	atualizar();
	}
	if(temp == true){
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
	    
	   	url = "../administrativo/xml/cep.php?CEP="+CEP;
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
					if(xmlhttp.responseText != 'false'){
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("IdPais")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var IdPais = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("IdEstado")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var IdEstado = nameTextNode.nodeValue;
				
						nameNode = xmlhttp.responseXML.getElementsByTagName("IdCidade")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var IdCidade = nameTextNode.nodeValue;					
					
						nameNode = xmlhttp.responseXML.getElementsByTagName("Endereco")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var Endereco = nameTextNode.nodeValue;
					
						nameNode = xmlhttp.responseXML.getElementsByTagName("Bairro")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var Bairro = nameTextNode.nodeValue;
						
						cpEndereco.value 		= Endereco;
						cpBairro.value 			= Bairro;
						
						if(IdPais != cpIdPais.value || IdEstado != cpIdEstado.value || IdCidade != cpIdCidade.value){
							if(cpIdPais.name == 'Cob_IdPais'){
								busca_cob_cidade(IdPais,IdEstado,IdCidade);		
							}else{
								busca_cidade(IdPais,IdEstado,IdCidade);		
							}
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
}
function atualizar(){
	return confirm("ATENCAO!\n\nDeseja atualizar endereço?","SIM","NAO");
}
