function busca_nota_fiscal_reprocessar(IdNotaFiscalLayout, PeriodoApuracao, IdNotaFiscal, Erro, Local){
	
	if(IdNotaFiscalLayout == ''){
		IdNotaFiscalLayout = 0;
	}	
	if(IdNotaFiscal == ''){
		IdNotaFiscal = 0;
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
    
    
   	url = "../administrativo/xml/nota_fiscal_reprocessar.php?IdNotaFiscalLayout="+IdNotaFiscalLayout+"&PeriodoApuracao="+PeriodoApuracao+"&IdNotaFiscal="+IdNotaFiscal;
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
						case 'NotaFiscalReprocessar':
							document.formulario.IdNotaFiscal.value				= "";
							document.formulario.DataEmissao.value				= "";
							document.formulario.ValorNotaFiscal.value			= "";						
							document.formulario.LoginCriacao.value				= "";
							document.formulario.DataCriacao.value				= "";					
							
							document.formulario.IdNotaFiscalLayout.focus();
							
							document.formulario.bt_reprocessar.disabled = true;
							
							document.formulario.Acao.value 		= "";
							verificaAcao();
							break;						
					}
				}else{
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("DataEmissao")[0]; 
					nameTextNode = nameNode.childNodes[0];
					var DataEmissao = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("ValorNotaFiscal")[0]; 
					nameTextNode = nameNode.childNodes[0];
					var ValorNotaFiscal = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("LoginCriacao")[0]; 
					nameTextNode = nameNode.childNodes[0];
					var LoginCriacao = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("DataCriacao")[0]; 
					nameTextNode = nameNode.childNodes[0];
					var DataCriacao = nameTextNode.nodeValue;
														
					switch (Local){
						case 'NotaFiscalReprocessar':
							
							document.formulario.DataEmissao.value			= dateFormat(DataEmissao);
							document.formulario.ValorNotaFiscal.value		= ValorNotaFiscal;						
							document.formulario.LoginCriacao.value			= LoginCriacao;
							document.formulario.DataCriacao.value			= dateFormat(DataCriacao);				

							document.formulario.IdNotaFiscal.focus();

							document.formulario.Acao.value 		= "reprocessar";

							document.formulario.bt_reprocessar.disabled = false;
							break;
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
