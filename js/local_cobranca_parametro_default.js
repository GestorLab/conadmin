function busca_local_cobranca_parametro(IdLocalCobranca,Erro,Local,IdLocalCobrancaParametroCond){
	if(IdLocalCobranca == '' || IdLocalCobranca == undefined){
		IdLocalCobranca = 0;
	}
	if(IdLocalCobrancaParametroCond == '' || IdLocalCobrancaParametroCond == undefined){
		IdLocalCobrancaParametroCond = 0;
	}else{
		var tam		=	document.getElementById('tabelaParametro').rows.length;	
		var i;
		for(i=0; i<tam; i++){
			if(document.getElementById('tabelaParametro').rows[i].accessKey == IdLocalCobrancaParametroCond){
				document.getElementById('tabelaParametro').rows[i].style.backgroundColor = "#A0C4EA";
			}
			else{
				if(i%2 == 0 && i!=0 && i!=(tam-1)){
					document.getElementById('tabelaParametro').rows[i].style.backgroundColor = "#E2E7ED";
				}else if(i%2 != 0 && i!=0 && i!=(tam-1)){
					document.getElementById('tabelaParametro').rows[i].style.backgroundColor = "#FFFFFF";
				}
			}
		}
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
    mensagens(0);
    url = "xml/local_cobranca_parametro.php?IdLocalCobranca="+IdLocalCobranca+"&IdLocalCobrancaParametro="+IdLocalCobrancaParametroCond;
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
					
					document.formulario.DescricaoLocalCobrancaParametro.value	=	"";
					document.formulario.Obrigatorio[0].selected 				= 	true;
					document.formulario.ObsLocalCobrancaParametro.value			=	"";
					document.formulario.ValorLocalCobrancaParametro.value		=	"";
					
					addParmUrl("marLocalCobrancaParametroNovo","IdLocalCobrancaParametro","");
					addParmUrl("marLocalCobrancaParametroNovo","IdLocalCobranca",IdLocalCobranca);
					addParmUrl("marLocalCobrancaParametroContrato","IdLocalCobranca",IdLocalCobranca);
					addParmUrl("marLocalCobrancaParametroContratoNovo","IdLocalCobranca",IdLocalCobranca);
						
					status_inicial();
					document.getElementById('tabelahelpText2').style.display	=	'none';
					
					document.formulario.Acao.value						= 	'inserir';
					
					var tam		=	document.getElementById('tabelaParametro').rows.length;	
					var i;
					for(i=0; i<tam; i++){
						if(i%2 == 0 && i!=0 && i!=(tam-1)){
							document.getElementById('tabelaParametro').rows[i].style.backgroundColor = "#E2E7ED";
						}else if(i%2 != 0 && i!=0 && i!=(tam-1)){
							document.getElementById('tabelaParametro').rows[i].style.backgroundColor = "#FFFFFF";
						}
					}
					if(IdLocalCobrancaParametroCond == 0)	document.formulario.IdLocalCobrancaParametro.focus();
					
					// Fim de Carregando
					carregando(false);
				}else{
					var IdLocalCobrancaParametro, DescricaoLocalCobrancaParametro, Obrigatorio, ValorLocalCobrancaParametro, ObsLocalCobrancaParametro;
					for(var i=0; i<xmlhttp.responseXML.getElementsByTagName("IdLocalCobrancaParametro").length; i++){
			
						nameNode = xmlhttp.responseXML.getElementsByTagName("IdLocalCobrancaParametro")[i]; 
						nameTextNode = nameNode.childNodes[0];
						IdLocalCobrancaParametro = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoLocalCobrancaParametro")[i]; 
						nameTextNode = nameNode.childNodes[0];
						DescricaoLocalCobrancaParametro = nameTextNode.nodeValue;	
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("Obrigatorio")[i]; 
						nameTextNode = nameNode.childNodes[0];
						Obrigatorio = nameTextNode.nodeValue;					
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("ValorLocalCobrancaParametro")[i]; 
						nameTextNode = nameNode.childNodes[0];
						ValorLocalCobrancaParametro = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("ObsLocalCobrancaParametro")[i]; 
						nameTextNode = nameNode.childNodes[0];
						ObsLocalCobrancaParametro = nameTextNode.nodeValue;
						
						if(IdLocalCobrancaParametroCond != ''){
							addParmUrl("marLocalCobrancaParametro","IdLocalCobrancaParametro",IdLocalCobrancaParametro);
							addParmUrl("marLocalCobrancaParametroNovo","IdLocalCobranca",IdLocalCobranca);
							addParmUrl("marLocalCobrancaParametroContrato","IdLocalCobranca",IdLocalCobranca);
							addParmUrl("marLocalCobrancaParametroContratoNovo","IdLocalCobranca",IdLocalCobranca);
							
							
							document.formulario.IdLocalCobrancaParametro.value			=	IdLocalCobrancaParametro;
							document.formulario.DescricaoLocalCobrancaParametro.value	=	DescricaoLocalCobrancaParametro;
							document.formulario.Obrigatorio.value						=	Obrigatorio;
							document.formulario.ObsLocalCobrancaParametro.value			=	ObsLocalCobrancaParametro;
							document.formulario.ValorLocalCobrancaParametro.value		=	ValorLocalCobrancaParametro;
							
							for(var i=0; i<document.formulario.Obrigatorio.length; i++){
								if(document.formulario.Obrigatorio[i].value == Obrigatorio){
									document.formulario.Obrigatorio[i].selected = true;
									i = document.formulario.Obrigatorio.length;
								}							
							}
							
							document.formulario.Acao.value							= 	'alterar';
						}
						
					}
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

