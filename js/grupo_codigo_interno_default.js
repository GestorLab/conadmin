	function janela_busca_grupo_codigo_interno(){
		janelas('busca_grupo_codigo_interno.php',360,283,250,100,'');
	}

	function busca_grupo_codigo_interno(IdGrupoCodigoInterno, Erro, Local){
		if(IdGrupoCodigoInterno == ''){
			IdGrupoCodigoInterno = '-1';
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
	    
	   	url = "xml/grupo_codigo_interno.php?IdGrupoCodigoInterno="+IdGrupoCodigoInterno;
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
						
						document.formulario.IdGrupoCodigoInterno.value 				= '';
						document.formulario.DescricaoGrupoCodigoInterno.value 		= '';
						document.formulario.IdGrupoCodigoInterno.focus();
						
						document.formulario.Acao.value 									= 'inserir';
						
						limpa_form_codigo_interno();
						
						// Fim de Carregando
						carregando(false);
					}else{
						nameNode = xmlhttp.responseXML.getElementsByTagName("IdGrupoCodigoInterno")[0]; 
						nameTextNode = nameNode.childNodes[0];
						IdGrupoCodigoInterno = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoGrupoCodigoInterno")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var DescricaoGrupoCodigoInterno = nameTextNode.nodeValue;
						
						document.formulario.IdGrupoCodigoInterno.value		= IdGrupoCodigoInterno;
						document.formulario.DescricaoGrupoCodigoInterno.value = DescricaoGrupoCodigoInterno;
						
						limpa_form_codigo_interno();
					}
					if(document.getElementById("quadroBuscaGrupoCodigo") != null){	
						if(document.getElementById("quadroBuscaGrupoCodigo").style.display	==	"block"){
							document.getElementById("quadroBuscaGrupoCodigo").style.display = "none";
						}
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
