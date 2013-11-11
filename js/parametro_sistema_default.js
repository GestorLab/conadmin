function janela_busca_parametro_sistema(IdGrupoParametroSistema){
	if(IdGrupoParametroSistema != ''){
		janelas('busca_parametro_sistema.php?IdGrupoParametroSistema='+IdGrupoParametroSistema,360,283,250,100,'');
	}
}
function busca_parametro_sistema(IdGrupoParametroSistema,IdParametroSistema,Erro,Local){
	if(IdGrupoParametroSistema == ''){
		IdGrupoParametroSistema = -1;
	}
	if(IdParametroSistema == ''){
		IdParametroSistema = 0;
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
    
   	url = "xml/parametro_sistema.php?IdGrupoParametroSistema="+IdGrupoParametroSistema+"&IdParametroSistema="+IdParametroSistema;
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
					limpa_form_parametro_sistema();
					document.formulario.IdParametroSistema.focus();
					
							
					document.formulario.Acao.value 						= 'inserir';
					
					// Fim de Carregando
					carregando(false);
				}else{
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("IdGrupoParametroSistema")[0]; 
					nameTextNode = nameNode.childNodes[0];
					var IdGrupoParametroSistema = nameTextNode.nodeValue;	
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoGrupoParametroSistema")[0]; 
					nameTextNode = nameNode.childNodes[0];
					var DescricaoGrupoParametroSistema = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("IdParametroSistema")[0]; 
					nameTextNode = nameNode.childNodes[0];
					IdParametroSistema = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("Editavel")[0]; 
					nameTextNode = nameNode.childNodes[0];
					var Editavel = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoParametroSistema")[0]; 
					nameTextNode = nameNode.childNodes[0];
					var DescricaoParametroSistema= nameTextNode.nodeValue;	
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("ValorParametroSistema")[0]; 
					nameTextNode = nameNode.childNodes[0];
					var ValorParametroSistema = nameTextNode.nodeValue;						
					
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
						
					
					document.formulario.IdGrupoParametroSistema.value 			= IdGrupoParametroSistema;
					document.formulario.DescricaoGrupoParametroSistema.value	= DescricaoGrupoParametroSistema;
					document.formulario.IdParametroSistema.value 				= IdParametroSistema;
					document.formulario.DescricaoParametroSistema.value 		= DescricaoParametroSistema;
					document.formulario.ValorParametroSistema.value 			= ValorParametroSistema;					
					document.formulario.Acao.value 							= 'alterar';
					
					if(Editavel == 'N'){
						document.formulario.DescricaoParametroSistema.readOnly = true;	
						document.formulario.ValorParametroSistema.readOnly 	= true;	
						document.formulario.Acao.value 						= 'inativo';
					}else{
						document.formulario.DescricaoParametroSistema.readOnly = false;	
						document.formulario.ValorParametroSistema.readOnly 	= false;	
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
