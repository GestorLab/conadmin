function busca_local_cobranca_parametro_contrato(IdLocalCobranca,Erro,Local,IdLocalCobrancaParametroContratoCond){
	if(IdLocalCobranca == '' || IdLocalCobranca == undefined){
		IdLocalCobranca = 0;
	}
	if(IdLocalCobrancaParametroContratoCond == '' || IdLocalCobrancaParametroContratoCond == undefined){
		IdLocalCobrancaParametroContratoCond = 0;
	}else{
		var tam		=	document.getElementById('tabelaParametro').rows.length;	
		var i;
		for(i=0; i<tam; i++){
			if(document.getElementById('tabelaParametro').rows[i].accessKey == IdLocalCobrancaParametroContratoCond){
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
    url = "xml/local_cobranca_parametro_contrato.php?IdLocalCobranca="+IdLocalCobranca+"&IdLocalCobrancaParametroContrato="+IdLocalCobrancaParametroContratoCond;
	
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
					document.formulario.IdLocalCobrancaParametroContrato.value	=	"";
					document.formulario.DescricaoParametroContrato.value		=	"";
					document.formulario.Obrigatorio[0].selected 				= 	true;
					document.formulario.ObrigatorioStatus.value 				= 	"";
					document.formulario.Calculavel[0].selected 					= 	true;
					document.formulario.Visivel[0].selected 					= 	true;
					document.formulario.VisivelOS[0].selected 					= 	true;
					document.formulario.ParametroDemonstrativo[0].selected 		= 	true;
					document.formulario.Obs.value								=	"";
					document.formulario.IdStatusParametro[0].selected 			= 	true;
					document.formulario.IdMascaraCampo[0].selected 				= 	true;
					document.formulario.ValorDefaultInput.value					=	"";
					document.formulario.RotinaCalculo.value						=	"";	
					document.formulario.OpcaoValor.value						=	"";	
					document.formulario.IdMascaraCampo.value					=	"";	
					document.formulario.IdTipoParametro.value					=	"";	
					document.formulario.RotinaCalculo.value						=	"";				
					document.formulario.DataCriacao.value						=	"";
					document.formulario.LoginCriacao.value						=	"";
					document.formulario.DataAlteracao.value						=	"";
					document.formulario.LoginAlteracao.value					=	"";
					document.formulario.Obrigatorio.disabled					=	false;
					document.formulario.IdMascaraCampo.disabled					=	false;
					document.formulario.Acao.value								= 	"inserir";
					
					addParmUrl("marLocalCobrancaParametroContratoNovo","IdLocalCobrancaParametroContrato","");
					
					document.formulario.IdLocalCobrancaParametroContrato.focus();
					
					while(document.formulario.ValorDefaultSelect.options.length > 0){
						document.formulario.ValorDefaultSelect.options[0] = null;
					}
					
					verificaTipoParametro();
					status_inicial();
					
					document.getElementById('tabelahelpText2').style.display	=	'none';
					document.getElementById('cpRotinaCalculo').style.display	=	'none';
					document.getElementById('titMascaraCor').style.color		=	'#C10000';
					
					
					// Fim de Carregando
					carregando(false);
				}else{
					var IdLocalCobrancaParametroContrato, DescricaoParametroContrato, Obrigatorio, ValorDefault, Obs, IdStatusParametro, DataCriacao, LoginCriacao, DataAlteracao, LoginAlteracao;
					for(var i=0; i<xmlhttp.responseXML.getElementsByTagName("IdLocalCobrancaParametroContrato").length; i++){
			
						nameNode = xmlhttp.responseXML.getElementsByTagName("IdLocalCobrancaParametroContrato")[i]; 
						nameTextNode = nameNode.childNodes[0];
						IdLocalCobrancaParametroContrato = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoParametroContrato")[i]; 
						nameTextNode = nameNode.childNodes[0];
						DescricaoParametroContrato = nameTextNode.nodeValue;	
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("Obrigatorio")[i]; 
						nameTextNode = nameNode.childNodes[0];
						Obrigatorio = nameTextNode.nodeValue;					
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("Editavel")[i]; 
						nameTextNode = nameNode.childNodes[0];
						Editavel = nameTextNode.nodeValue;					
				
						nameNode = xmlhttp.responseXML.getElementsByTagName("ValorDefault")[i]; 
						nameTextNode = nameNode.childNodes[0];
						ValorDefault = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("Obs")[i]; 
						nameTextNode = nameNode.childNodes[0];
						Obs = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("Calculavel")[i]; 
						nameTextNode = nameNode.childNodes[0];
						Calculavel = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("RotinaCalculo")[i]; 
						nameTextNode = nameNode.childNodes[0];
						RotinaCalculo = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("IdStatusParametro")[i]; 
						nameTextNode = nameNode.childNodes[0];
						IdStatusParametro = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("ParametroDemonstrativo")[i]; 
						nameTextNode = nameNode.childNodes[0];
						ParametroDemonstrativo = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("Visivel")[i]; 
						nameTextNode = nameNode.childNodes[0];
						Visivel = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("VisivelOS")[i]; 
						nameTextNode = nameNode.childNodes[0];
						VisivelOS = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("IdTipoParametro")[i]; 
						nameTextNode = nameNode.childNodes[0];
						IdTipoParametro = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("IdMascaraCampo")[i]; 
						nameTextNode = nameNode.childNodes[0];
						IdMascaraCampo = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("OpcaoValor")[i]; 
						nameTextNode = nameNode.childNodes[0];
						OpcaoValor = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("DataCriacao")[i]; 
						nameTextNode = nameNode.childNodes[0];
						DataCriacao = nameTextNode.nodeValue;
					
						nameNode = xmlhttp.responseXML.getElementsByTagName("LoginCriacao")[i]; 
						nameTextNode = nameNode.childNodes[0];
						LoginCriacao = nameTextNode.nodeValue;					
					
						nameNode = xmlhttp.responseXML.getElementsByTagName("DataAlteracao")[i]; 
						nameTextNode = nameNode.childNodes[0];
						DataAlteracao = nameTextNode.nodeValue;
					
						nameNode = xmlhttp.responseXML.getElementsByTagName("LoginAlteracao")[i]; 
						nameTextNode = nameNode.childNodes[0];
						LoginAlteracao = nameTextNode.nodeValue;
						
						if(IdLocalCobrancaParametroContratoCond != ''){
							addParmUrl("marLocalCobrancaParametroContratoNovo","IdLocalCobranca",IdLocalCobranca);
							addParmUrl("marLocalCobrancaParametroContrato","IdLocalCobranca",IdLocalCobranca);
							addParmUrl("marLocalCobrancaParametro","IdLocalCobranca",IdLocalCobranca);
							addParmUrl("marLocalCobrancaParametroNovo","IdLocalCobranca",IdLocalCobranca);
							addParmUrl("marLocalCobranca","IdLocalCobranca",IdLocalCobranca);
							
							
							document.formulario.IdLocalCobrancaParametroContrato.value		=	IdLocalCobrancaParametroContrato;
							document.formulario.DescricaoParametroContrato.value	=	DescricaoParametroContrato;
							document.formulario.Obrigatorio.value					=	Obrigatorio;
							document.formulario.ObrigatorioStatus.value				=	Obrigatorio;
							document.formulario.Editavel.value						=	Editavel;
							document.formulario.Obs.value							=	Obs;
							document.formulario.Visivel.value						=	Visivel;
							document.formulario.VisivelOS.value						=	VisivelOS;
							document.formulario.ParametroDemonstrativo.value		=	ParametroDemonstrativo;
							document.formulario.Calculavel.value					=	Calculavel;
							document.formulario.IdStatusParametro.value				=	IdStatusParametro;
							document.formulario.IdTipoParametro.value				=	IdTipoParametro;
							document.formulario.IdMascaraCampo.value				=	IdMascaraCampo;
							document.formulario.DataCriacao.value					=	dateFormat(DataCriacao);
							document.formulario.LoginCriacao.value					=	LoginCriacao;
							document.formulario.DataAlteracao.value					=	dateFormat(DataAlteracao);
							document.formulario.LoginAlteracao.value				=	LoginAlteracao;
							
							
							document.getElementById('titMascaraCor').style.color	=	'#C10000';
							
							if(Calculavel == 1){
								document.getElementById('cpRotinaCalculo').style.display	=	'block';
								document.formulario.RotinaCalculo.value						=	RotinaCalculo;
							}else{
								document.getElementById('cpRotinaCalculo').style.display	=	'none';
								document.formulario.RotinaCalculo.value						=	'';
							}
							
							if(Editavel == 2){
								document.formulario.Obrigatorio.disabled	=	true;
							}else{
								document.formulario.Obrigatorio.disabled	=	false;
							}
							
							// Caixa de Seleção
							if(IdTipoParametro == 2){
								document.formulario.ValorDefaultInput.value			=	"";
								document.formulario.OpcaoValor.value				=	OpcaoValor;
								
								busca_opcao_valor(OpcaoValor,ValorDefault);
							}else{
								document.formulario.ValorDefaultInput.value			=	ValorDefault;
								document.formulario.OpcaoValor.value				=	"";
							}
							
							verificaEditavel(Editavel);
							verificaTipoParametro(IdTipoParametro);
							
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

