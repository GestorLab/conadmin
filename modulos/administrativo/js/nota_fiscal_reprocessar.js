	function validar(){
		if(document.formulario.IdNotaFiscalLayout.value==''){
			mensagens(1);
			document.formulario.IdNotaFiscalLayout.focus();
			return false;
		}
		if(document.formulario.PeriodoApuracao.value==''){
			mensagens(1);
			document.formulario.PeriodoApuracao.focus();
			return false;
		}
		if(document.formulario.IdNotaFiscal.value==''){
			mensagens(1);
			document.formulario.IdNotaFiscal.focus();
			return false;
		}
		return true;
	}
	function inicia(){
		document.formulario.IdNotaFiscalLayout.focus();
	}
	function verificaAcao(){
		if(document.formulario != undefined){
			if(document.formulario.Acao.value=='reprocessar'){			
				document.formulario.bt_reprocessar.disabled 	= true;				
			}		
		}	
	}
	function busca_Periodo_Apuracao(IdNotaFiscalLayout,PeriodoApuracaoTemp){
		if(IdNotaFiscalLayout == undefined || IdNotaFiscalLayout==''){
			IdNotaFiscalLayout = 0;
		}
		if(PeriodoApuracaoTemp == undefined){
			PeriodoApuracaoTemp = '';
		}
		var xmlhttp = false;
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
	    
	    url = "xml/nota_fiscal_reprocessar_periodo_apuracao.php?IdNotaFiscalLayout="+IdNotaFiscalLayout;
		xmlhttp.open("GET", url,true);
	    
		// Carregando...
		carregando(true);
		
		xmlhttp.onreadystatechange = function(){ 
			if(xmlhttp.readyState == 4){ 
				if(xmlhttp.status == 200){
					if(xmlhttp.responseText != 'false'){		
						while(document.formulario.PeriodoApuracao.options.length > 0){
							document.formulario.PeriodoApuracao.options[0] = null;
						}
						
						var nameNode, nameTextNode, PeriodoApuracao;
						addOption(document.formulario.PeriodoApuracao,"","");
						
						for(var i=0; i<xmlhttp.responseXML.getElementsByTagName("PeriodoApuracao").length; i++){
							nameNode = xmlhttp.responseXML.getElementsByTagName("PeriodoApuracao")[i]; 
							nameTextNode = nameNode.childNodes[0];
							var PeriodoApuracao = nameTextNode.nodeValue;
													
							addOption(document.formulario.PeriodoApuracao,PeriodoApuracao,PeriodoApuracao);
						}
						
						if(PeriodoApuracaoTemp!=""){
							for(i=0;i<document.formulario.PeriodoApuracao.length;i++){
								if(document.formulario.PeriodoApuracao[i].value == PeriodoApuracaoTemp){
									document.formulario.PeriodoApuracao[i].selected	=	true;
									break;
								}
							}
						}else{
							document.formulario.PeriodoApuracao[0].selected	=	true;
						}						
					}else{
						while(document.formulario.PeriodoApuracao.options.length > 0){
							document.formulario.PeriodoApuracao.options[0] = null;
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
	function buscaNotaFiscalPorPeriodoApuracao(){
		busca_nota_fiscal_reprocessar("", "", "", false, document.formulario.Local.value);
	}
	function busca_pessoa_aproximada(campo,event){
		var url = "xml/pessoa_nome.php?Nome="+campo.value;
		
		call_ajax(url,function (xmlhttp){
			var NomeDefault = new Array(), nameNode, nameTextNode;
			
			if(campo.value != '' && xmlhttp.responseText != "false"){
				for(var i = 0; i < xmlhttp.responseXML.getElementsByTagName("NomeDefault").length; i++){
					nameNode = xmlhttp.responseXML.getElementsByTagName("NomeDefault")[i]; 
					nameTextNode = nameNode.childNodes[0];
					NomeDefault[i] = nameTextNode.nodeValue;
				}
			}
			
			busca_aproximada('filtro',campo,event,NomeDefault,22,5);
		},false);
	}

	
