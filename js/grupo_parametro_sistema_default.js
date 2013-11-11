	function janela_busca_grupo_parametro_sistema(){
		janelas('busca_grupo_parametro_sistema.php',360,283,250,100,'');
	}
	
	function busca_grupo_parametro_sistema(IdGrupoParametroSistema, Erro, Local){
		if(IdGrupoParametroSistema == ''){
			IdGrupoParametroSistema = '-1';
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
	    
	   	url = "xml/grupo_parametro_sistema.php?IdGrupoParametroSistema="+IdGrupoParametroSistema;
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
						
						document.formulario.IdGrupoParametroSistema.value 				= '';
						document.formulario.DescricaoGrupoParametroSistema.value 		= '';
						
						limpa_form_parametro_sistema();
						
						
						document.formulario.IdGrupoParametroSistema.focus();
						
						// Fim de Carregando
						carregando(false);
					}else{
						nameNode = xmlhttp.responseXML.getElementsByTagName("IdGrupoParametroSistema")[0]; 
						nameTextNode = nameNode.childNodes[0];
						IdGrupoParametroSistema = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoGrupoParametroSistema")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var DescricaoGrupoParametroSistema = nameTextNode.nodeValue;
						
						document.formulario.IdGrupoParametroSistema.value		= IdGrupoParametroSistema;
						document.formulario.DescricaoGrupoParametroSistema.value = DescricaoGrupoParametroSistema;
						
						limpa_form_parametro_sistema();
					}
					if(document.getElementById("quadroBuscaGrupoParametro") != null){
						if(document.getElementById("quadroBuscaGrupoParametro").style.display	==	"block"){
							document.getElementById("quadroBuscaGrupoParametro").style.display = "none";
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
