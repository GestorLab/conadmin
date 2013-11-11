function janela_busca_local_cobranca_layout(){
	janelas('../administrativo/busca_local_cobranca_layout.php',360,283,250,100,'');
}

function busca_local_cobranca_layout(IdLocalCobrancaLayout, Erro, Local){
	if(IdLocalCobrancaLayout == ''){
		IdLocalCobrancaLayout = 0;
	}
	if(Local == '' || Local == undefined){
		Local	=	document.formulario.Local.value;
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
    
   	url = "../administrativo/xml/local_cobranca_layout.php?IdLocalCobrancaLayout="+IdLocalCobrancaLayout;
	
	if(Local == "LocalCobranca"){
		url += "&IdStatus=1";
	}
	
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
							document.formulario.IdLocalCobrancaLayout.value 			= '';
							document.formulario.DescricaoLocalCobrancaLayout.value 		= '';							
							document.formulario.IdLocalCobrancaLayout.focus();
														
							document.getElementById("tbAvisoFaturaAtraso").style.display 	= "block";
							document.formulario.AvisoFaturaAtraso.value						= 1;								
							break;
						default:
							document.formulario.IdLocalCobrancaLayout.value 			= '';
							document.formulario.DescricaoLocalCobrancaLayout.value 		= '';
							document.formulario.IdLocalCobrancaLayout.focus();
							break
					}		
					
					// Fim de Carregando
					carregando(false);
				}else{
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("IdLocalCobrancaLayout")[0]; 
					nameTextNode = nameNode.childNodes[0];
					IdLocalCobrancaLayout = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoLocalCobrancaLayout")[0]; 
					nameTextNode = nameNode.childNodes[0];
					var DescricaoLocalCobrancaLayout = nameTextNode.nodeValue;
					
					switch(Local){
						case 'LocalCobranca':
							document.formulario.IdLocalCobrancaLayout.value				= IdLocalCobrancaLayout;
							document.formulario.DescricaoLocalCobrancaLayout.value 		= DescricaoLocalCobrancaLayout;
				
							document.getElementById("tbAvisoFaturaAtraso").style.display 	= "block";
							//document.formulario.AvisoFaturaAtraso.value						= "";		
				
							break;
						default:
							document.formulario.IdLocalCobrancaLayout.value				= IdLocalCobrancaLayout;
							document.formulario.DescricaoLocalCobrancaLayout.value 		= DescricaoLocalCobrancaLayout;				
							break;					
					}								
				}
				if(document.getElementById("quadroBuscaLocalCobrancaLayout") != null){
					if(document.getElementById("quadroBuscaLocalCobrancaLayout").style.display == "block"){
						document.getElementById("quadroBuscaLocalCobrancaLayout").style.display =	"none";
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
