function janela_busca_codigo_interno(IdGrupoCodigoInterno){
	if(IdGrupoCodigoInterno != ''){
		janelas('busca_codigo_interno.php?IdGrupoCodigoInterno='+IdGrupoCodigoInterno,360,283,250,100,'');
	}
}
function busca_codigo_interno(IdGrupoCodigoInterno,IdCodigoInterno,Erro,Local){
	if(IdGrupoCodigoInterno == ''){
		IdGrupoCodigoInterno = -1;
	}
	if(IdCodigoInterno == ''){
		IdCodigoInterno = 0;
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
    
   	url = "xml/codigo_interno.php?IdGrupoCodigoInterno="+IdGrupoCodigoInterno+"&IdCodigoInterno="+IdCodigoInterno;
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
					limpa_form_codigo_interno();
					document.formulario.IdCodigoInterno.focus();
					document.formulario.Acao.value 						= 'inserir';
					
					// Fim de Carregando
					carregando(false);
				}else{
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("IdGrupoCodigoInterno")[0]; 
					nameTextNode = nameNode.childNodes[0];
					var IdGrupoCodigoInterno = nameTextNode.nodeValue;	
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoGrupoCodigoInterno")[0]; 
					nameTextNode = nameNode.childNodes[0];
					var DescricaoGrupoCodigoInterno = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("IdCodigoInterno")[0]; 
					nameTextNode = nameNode.childNodes[0];
					IdCodigoInterno = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("Editavel")[0]; 
					nameTextNode = nameNode.childNodes[0];
					var Editavel = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoCodigoInterno")[0]; 
					nameTextNode = nameNode.childNodes[0];
					var DescricaoCodigoInterno= nameTextNode.nodeValue;	
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("ValorCodigoInterno")[0]; 
					nameTextNode = nameNode.childNodes[0];
					var ValorCodigoInterno = nameTextNode.nodeValue;						
					
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
						
					
					document.formulario.IdGrupoCodigoInterno.value 			= IdGrupoCodigoInterno;
					document.formulario.DescricaoGrupoCodigoInterno.value	= DescricaoGrupoCodigoInterno;
					document.formulario.IdCodigoInterno.value 				= IdCodigoInterno;
					document.formulario.DescricaoCodigoInterno.value 		= DescricaoCodigoInterno;
					document.formulario.ValorCodigoInterno.value 			= ValorCodigoInterno;					
					document.formulario.Acao.value 							= 'alterar';
					
					if(Editavel == 'N'){
						document.formulario.DescricaoCodigoInterno.disabled = true;	
						document.formulario.ValorCodigoInterno.disabled 	= true;	
						document.formulario.Acao.value 						= 'inativo';
					}else{
						document.formulario.DescricaoCodigoInterno.disabled = false;	
						document.formulario.ValorCodigoInterno.disabled 	= false;	
					}
					
					
					document.formulario.DataCriacao.value 		= dateFormat(DataCriacao);
					document.formulario.LoginCriacao.value 		= LoginCriacao;
					document.formulario.DataAlteracao.value 	= dateFormat(DataAlteracao);
					document.formulario.LoginAlteracao.value	= LoginAlteracao;
					
				}
				if(window.janela != undefined){
					window.janela.close();
				}
				verificaAcao();
			}
			// Fim de Carregando
			carregando(false);
		} 
		return true;
	}
	xmlhttp.send(null);
}
function limpa_form_codigo_interno(){
	document.formulario.IdCodigoInterno.value			= '';
	document.formulario.DescricaoCodigoInterno.value 	= '';
	document.formulario.ValorCodigoInterno.value 		= '';
	document.formulario.DataCriacao.value 	  			= '';
	document.formulario.LoginCriacao.value 				= '';
	document.formulario.DataAlteracao.value 			= '';
	document.formulario.LoginAlteracao.value			= '';
}
