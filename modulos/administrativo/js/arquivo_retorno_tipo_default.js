function janela_busca_arquivo_retorno_tipo(){
	janelas('../administrativo/busca_arquivo_retorno_tipo.php',360,283,250,100,'');
}
function busca_arquivo_retorno_tipo(IdLocalRecebimento,IdArquivoRetornoTipo,Erro,Local){
	if(Local == '' || Local == undefined){
		Local = document.formulario.Local.value;
	}
	
	if(IdArquivoRetornoTipo == ''){
		IdArquivoRetornoTipo = 0;
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
    
   	url = "xml/arquivo_retorno_tipo.php?IdLocalRecebimento="+IdLocalRecebimento+"&IdArquivoRetornoTipo="+IdArquivoRetornoTipo;
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
					document.formulario.IdArquivoRetornoTipo.value			=	"";
					document.formulario.DescricaoArquivoRetornoTipo.value	=	"";
					
					if(Local == 'LocalCobranca'){
						document.formulario.IdArquivoRetornoTipo.focus();
					}
															
					// Fim de Carregando
					carregando(false);
					verificaAcao();
				}else{
				
					nameNode = xmlhttp.responseXML.getElementsByTagName("IdArquivoRetornoTipo")[0]; 
					nameTextNode = nameNode.childNodes[0];
					IdArquivoRetornoTipo = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoArquivoRetornoTipo")[0]; 
					nameTextNode = nameNode.childNodes[0];
					var DescricaoArquivoRetornoTipo = nameTextNode.nodeValue;						
			
					document.formulario.IdArquivoRetornoTipo.value 			= IdArquivoRetornoTipo;
					document.formulario.DescricaoArquivoRetornoTipo.value 	= DescricaoArquivoRetornoTipo;
						
				}
				if(document.getElementById("quadroBuscaArquivoRetornoTipo") != null){
					if(document.getElementById("quadroBuscaArquivoRetornoTipo").style.display == "block"){
						document.getElementById("quadroBuscaArquivoRetornoTipo").style.display =	"none";
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
