function janela_busca_cfop(){
	janelas('busca_cfop.php',530,350,250,100,'');
}
function busca_cfop(CFOP,Erro,Local){
	if(CFOP == '' || CFOP == undefined){
		CFOP = 0;
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

   	url = "xml/cfop.php?CFOP="+CFOP;
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
					document.formulario.CFOP.value					=	"";
					document.formulario.NaturezaOperacao.value		=	"";
					
					document.formulario.CFOP.focus();
										
					// Fim de Carregando
					carregando(false);
				}else{
					nameNode = xmlhttp.responseXML.getElementsByTagName("CFOP")[0]; 
					nameTextNode = nameNode.childNodes[0];
					CFOP = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("NaturezaOperacao")[0]; 
					nameTextNode = nameNode.childNodes[0];
					var NaturezaOperacao = nameTextNode.nodeValue;
					
					document.formulario.CFOP.value					=	CFOP;
					document.formulario.NaturezaOperacao.value		=	NaturezaOperacao;
				}
				if(document.getElementById("quadroBuscaCFOP") != null){
					if(document.getElementById("quadroBuscaCFOP").style.display	==	"block"){
						document.getElementById("quadroBuscaCFOP").style.display = "none";
					}
				}
			}// fim do else
			// Fim de Carregando
			carregando(false);
		}//fim do if status
		return true;
	}
	xmlhttp.send(null);
}


