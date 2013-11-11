	function excluir(IdLocalCobranca){
		if(IdLocalCobranca == ''){
			IdLocalCobranca = document.formulario.IdLocalCobranca.value;
		}
		if(excluir_registro() == true){
			if(document.formulario != undefined){
				if(document.formulario.Acao.value == 'inserir'){
					return false;
				}
			}
			var xmlhttp   = false;
			if (window.XMLHttpRequest) { // Mozilla, Safari,...
    			xmlhttp = new XMLHttpRequest();
		        if(xmlhttp.overrideMimeType){
		    //    	xmlhttp.overrideMimeType('text/xml');
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
    
   			url = "files/excluir/excluir_local_cobranca.php?IdLocalCobranca="+IdLocalCobranca;
			xmlhttp.open("GET", url,true);
	
			xmlhttp.onreadystatechange = function(){ 

				// Carregando...
				carregando(true);

				if(xmlhttp.readyState == 4){ 
					if(xmlhttp.status == 200){
						if(document.formulario != undefined){
							document.formulario.Erro.value = xmlhttp.responseText;
							if(parseInt(xmlhttp.responseText) == 7){
								document.formulario.Acao.value 	= 'inserir';
								url = 'cadastro_local_cobranca.php?Erro='+document.formulario.Erro.value;
								window.location.replace(url);
							}else{
								verificaErro();
							}
						}else{
							var numMsg = parseInt(xmlhttp.responseText);
							mensagens(numMsg);
							if(numMsg == 7){
								var aux = 0, valor=0;
								for(var i=0; i<document.getElementById('tableListar').rows.length; i++){
									if(IdLocalCobranca == document.getElementById('tableListar').rows[i].accessKey){
										document.getElementById('tableListar').deleteRow(i);
										tableMultColor('tableListar');
										break;
									}
								}								
								if(aux=1){
									for(var i=1; i<(document.getElementById('tableListar').rows.length-1); i++){
										temp	=	document.getElementById('tableListar').rows[i].cells[6].innerHTML.split(">");
										temp1	=	temp[1].split("<");
										valor	+=	parseFloat(temp1[0].replace(',','.'));
									}
									document.getElementById('tableListarValor').innerHTML	=	formata_float(Arredonda(valor,2),2).replace('.',',');	
									document.getElementById("tableListarTotal").innerHTML	=	"Total: "+(document.getElementById('tableListar').rows.length-2);
								}	
							}
						}
					}
				}
				// Fim de Carregando
				carregando(false);
				return true;
			}
			xmlhttp.send(null);
		}
	} 
	function validar(){
		if(document.formulario.IdTipoLocalCobranca.value==''){
			mensagens(1);
			document.formulario.IdTipoLocalCobranca.focus();
			return false;
		}
		if(document.formulario.DescricaoLocalCobranca.value==''){
			mensagens(1);
			document.formulario.DescricaoLocalCobranca.focus();
			return false;
		}
		if(document.formulario.AbreviacaoNomeLocalCobranca.value==''){
			mensagens(1);
			document.formulario.AbreviacaoNomeLocalCobranca.focus();
			return false;
		}
		/*
		if(document.formulario.AvisoRegressivo.value==''){
			mensagens(1);
			document.formulario.AvisoRegressivo.focus();
			return false;
		} else{
			if(document.formulario.AvisoRegressivo.value>0){
				mensagens(1);
				document.formulario.AvisoRegressivo.focus();
				return false;
			}
		}*/
		
		if(document.formulario.ValorDespesaLocalCobranca.value==''){
			mensagens(1);
			document.formulario.ValorDespesaLocalCobranca.focus();
			return false;
		}
		if(document.formulario.PercentualJurosDiarios.value==''){
			mensagens(1);
			document.formulario.PercentualJurosDiarios.focus();
			return false;
		}
		if(document.formulario.PercentualMulta.value==''){
			mensagens(1);
			document.formulario.PercentualMulta.focus();
			return false;
		}
		if(document.formulario.ValorCobrancaMinima.value==''){
			mensagens(1);
			document.formulario.ValorCobrancaMinima.focus();
			return false;
		}
		if(document.formulario.ValorTaxaReImpressaoBoleto.value==''){
			mensagens(1);
			document.formulario.ValorTaxaReImpressaoBoleto.focus();
			return false;
		}		
		if(document.formulario.DiasCompensacao.value==''){
			mensagens(1);
			document.formulario.DiasCompensacao.focus();
			return false;
		}
		if(document.formulario.IdStatus.value==''){
			mensagens(1);
			document.formulario.IdStatus.focus();
			return false;
		}
/*		if(document.formulario.IdTipoLocalCobranca.value > 2 && document.formulario.IdTipoLocalCobranca.value < 5){
			if(document.formulario.IdArquivoRemessaTipo.value == ''){
				mensagens(1);
				document.formulario.IdArquivoRemessaTipo.focus();
				return false;
			}
		} */
		if(document.formulario.IdLocalCobrancaUnificada.options.disabled == false){		
			if(document.formulario.IdLocalCobrancaUnificada.options[0].selected == true || document.formulario.IdLocalCobrancaUnificada[0].selected == true){
			
				mensagens(1);
				document.formulario.IdLocalCobrancaUnificada.options.focus();
				return false;
			}
		}
		if(document.formulario.fakeupload.value != ""){
			var ExtImagem = document.formulario.fakeupload.value.split(".");		
			if(ExtImagem[ExtImagem.length-1] != "jpg" && ExtImagem[ExtImagem.length-1] != "gif" && ExtImagem[ExtImagem.length-1] != "png"){
				mensagens(192);
				document.formulario.fakeupload.focus();
				return false;
			}	
		}
		if(document.formulario.AvisoFaturaAtraso.value == '' && (document.formulario.IdTipoLocalCobranca.value == 2 || document.formulario.IdTipoLocalCobranca.value == 4)){
			mensagens(1);
			document.formulario.AvisoFaturaAtraso.focus();
			return false;
		}
		
		return true;
	}
	function inicia(){
		status_inicial();
		document.formulario.IdLocalCobranca.focus();
	}
	function status_inicial(){
		if(document.formulario.ValorDespesaLocalCobranca.value==''){
			document.formulario.ValorDespesaLocalCobranca.value	=	'0,00';
		}
		if(document.formulario.PercentualJurosDiarios.value==''){
			document.formulario.PercentualJurosDiarios.value	=	'0,000';
		}
		if(document.formulario.PercentualMulta.value==''){
			document.formulario.PercentualMulta.value	=	'0,000';
		}
		if(document.formulario.ValorCobrancaMinima.value==''){
			document.formulario.ValorCobrancaMinima.value	=	'0,00';
		}
		if(document.formulario.ValorTaxaReImpressaoBoleto.value==''){
			document.formulario.ValorTaxaReImpressaoBoleto.value	=	'0,00';
		}
		
		if(document.formulario.IdTipoLocalCobranca.value != '3'){
			document.formulario.IdAtualizarVencimentoViaCDA.value = document.formulario.IdAtualizarVencimentoViaCDADefault.value;
		}
		
		document.formulario.IdContraApresentacao.value = document.formulario.IdContraApresentacaoDefault.value;
		document.formulario.IdCobrarMultaJurosProximaFatura.value = document.formulario.IdCobrarMultaJurosProximaFaturaDefault.value;	
		
	}
	function busca_local_cobranca_unificada(IdLojaUnificada, IdLocalCobrancaUnificada){
		if(IdLojaUnificada == ''){
			IdLojaUnificada = 0;
			document.getElementById('cp_localCobranca').style.color = '#000';
			document.formulario.IdLocalCobrancaUnificada.disabled = true;
		}else{
			document.getElementById('cp_localCobranca').style.color = '#CC0000';
			document.formulario.IdLocalCobrancaUnificada.disabled = false;
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
	    
	   	url = "../administrativo/xml/local_cobranca_unificada.php?IdLojaUnificada="+IdLojaUnificada+"&IdLocalCobranca="+document.formulario.IdLocalCobranca.value;
		xmlhttp.open("GET", url,true);
		
		xmlhttp.onreadystatechange = function(){ 
	
			// Carregando...
			carregando(true);
	
			if(xmlhttp.readyState == 4){ 
				if(xmlhttp.status == 200){			
					if(xmlhttp.responseText == 'false'){
						while(document.formulario.IdLocalCobrancaUnificada.options.length > 0){
							document.formulario.IdLocalCobrancaUnificada.options[0] = null;
						}	
						carregando(false);	
					}else{	
						while(document.formulario.IdLocalCobrancaUnificada.options.length > 0){
							document.formulario.IdLocalCobrancaUnificada.options[0] = null;
						}							
						addOption(document.formulario.IdLocalCobrancaUnificada,"","");	
						for(i=0; i<xmlhttp.responseXML.getElementsByTagName("IdLocalCobranca").length; i++){							
							nameNode = xmlhttp.responseXML.getElementsByTagName("IdLocalCobranca")[i]; 
							nameTextNode = nameNode.childNodes[0];
							var IdLocalCobranca = nameTextNode.nodeValue;
													
							nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoLocalCobranca")[i]; 
							nameTextNode = nameNode.childNodes[0];
							var DescricaoLocalCobranca = nameTextNode.nodeValue;
							
							addOption(document.formulario.IdLocalCobrancaUnificada,DescricaoLocalCobranca,IdLocalCobranca);
						}
						document.formulario.IdLocalCobrancaUnificada.options[0].selected = true;						
						if(IdLocalCobrancaUnificada != ""){
							for(var i=0; i<document.formulario.IdLocalCobrancaUnificada.options.length; i++){
								if(document.formulario.IdLocalCobrancaUnificada.options[i].value == IdLocalCobrancaUnificada){
									document.formulario.IdLocalCobrancaUnificada.options[i].selected = true;
									i = document.formulario.IdLocalCobrancaUnificada.options.length;
								}							
							}
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
	function verificaImagem(){	
		document.getElementById("VerImagem").style.backgroundImage  = "url("+document.formulario.fakeupload.value+")";	
		document.getElementById("VerImagem").style.border 			= "1px #A4A4A4 solid";
	}	
	function valida_marcara_negativa(campo){
		if(parseInt(campo.value)){
			campo.value = parseInt(campo.value);
		}
		
		if(campo.value>0 && campo.value!="-0"){
			campo.value = campo.value/-1;
		}
	}
	function habilitar_arquivo_remessa_tipo(valor){
		
		if(valor == '' || valor == undefined){
			valor = 0;
		}
		
		switch(parseInt(valor)){
			case 0: //
				document.getElementById('titIdAtualizarRemessaViaCDA').style.display			= "none";
				document.getElementById('titIdAtualizarRemessaViaContaReceber').style.display	= "none";
				document.formulario.IdAtualizarRemessaViaCDA.style.display						= "none";
				document.formulario.IdAtualizarRemessaViaContaReceber.style.display				= "none";
				
				document.formulario.IdContraApresentacao.style.width 				= '210px';
				document.formulario.IdCobrarMultaJurosProximaFatura.style.width 	= '216px';
				document.formulario.IdAtualizarVencimentoViaCDA.style.width 		= '181px';
				document.getElementById('titStatus').style.marginLeft				= '5px';
				document.formulario.IdStatus.style.marginLeft						= '5px';
				document.formulario.IdStatus.style.width 							= '182px';
				
				document.getElementById('titArquivoRetornoTipo').style.display		= "block";
				document.getElementById('cpLupaArquivoRetornoTipo').style.display	= "block";
				document.getElementById('cpArquivoRetornoTipo').style.display		= "block";
				
				document.getElementById('titArquivoRemessaTipo').style.display		= "none";
				document.getElementById('cpLupaArquivoRemessaTipo').style.display	= "none";
				document.getElementById('cpArquivoRemessaTipo').style.display		= "none";
				
				document.getElementById('tbAvisoFaturaAtraso').style.display		= "block";
                document.getElementById('titAvisoFaturaAtraso').style.display		= "block";
				document.getElementById('cpAvisoFaturaAtraso').style.display		= "block";
			
				document.formulario.IdArquivoRemessaTipo.value						= "";
				document.formulario.DescricaoArquivoRemessaTipo.value				= "";
				
				document.formulario.IdAtualizarVencimentoViaCDA.disabled = false;
				break;				
			case 1: // Recebimento Manual
				document.getElementById('titIdAtualizarRemessaViaCDA').style.display			= "none";
				document.getElementById('titIdAtualizarRemessaViaContaReceber').style.display	= "none";
				document.formulario.IdAtualizarRemessaViaCDA.style.display						= "none";
				document.formulario.IdAtualizarRemessaViaContaReceber.style.display				= "none";
				
				document.formulario.IdContraApresentacao.style.width 				= '210px';
				document.formulario.IdCobrarMultaJurosProximaFatura.style.width 	= '216px';
				document.formulario.IdAtualizarVencimentoViaCDA.style.width 		= '181px';
				document.getElementById('titStatus').style.marginLeft				= '5px';
				document.formulario.IdStatus.style.marginLeft						= '5px';
				document.formulario.IdStatus.style.width 							= '182px';
				
				document.getElementById('titArquivoRemessaTipo').style.display		= "none";
				document.getElementById('cpLupaArquivoRemessaTipo').style.display	= "none";
				document.getElementById('cpArquivoRemessaTipo').style.display		= "none";
				
				document.getElementById('titArquivoRetornoTipo').style.display		= "none";
				document.getElementById('cpLupaArquivoRetornoTipo').style.display	= "none";
				document.getElementById('cpArquivoRetornoTipo').style.display		= "none";
				
				document.getElementById('tbAvisoFaturaAtraso').style.display		= "none";
                document.getElementById('titAvisoFaturaAtraso').style.display		= "none";
				document.getElementById('cpAvisoFaturaAtraso').style.display		= "none";
				
				document.formulario.AvisoFaturaAtraso.value							= "";
				document.formulario.IdArquivoRetornoTipo.value						= "";
				document.formulario.DescricaoArquivoRetornoTipo.value				= "";			
				document.formulario.IdArquivoRemessaTipo.value						= "";
				document.formulario.DescricaoArquivoRemessaTipo.value				= "";
				
				document.formulario.IdAtualizarVencimentoViaCDA.disabled = false;
				break;
			case 2: // Boleto sem Registro
				document.getElementById('titIdAtualizarRemessaViaCDA').style.display			= "none";
				document.getElementById('titIdAtualizarRemessaViaContaReceber').style.display	= "none";
				document.formulario.IdAtualizarRemessaViaCDA.style.display						= "none";
				document.formulario.IdAtualizarRemessaViaContaReceber.style.display				= "none";
				
				document.formulario.IdContraApresentacao.style.width 				= '210px';
				document.formulario.IdCobrarMultaJurosProximaFatura.style.width 	= '216px';
				document.formulario.IdAtualizarVencimentoViaCDA.style.width 		= '181px';
				document.getElementById('titStatus').style.marginLeft				= '5px';
				document.formulario.IdStatus.style.marginLeft						= '5px';
				document.formulario.IdStatus.style.width 							= '182px';
				
				document.getElementById('titArquivoRetornoTipo').style.display		= "block";
				document.getElementById('cpLupaArquivoRetornoTipo').style.display	= "block";
				document.getElementById('cpArquivoRetornoTipo').style.display		= "block";
				
				document.getElementById('titArquivoRemessaTipo').style.display		= "none";
				document.getElementById('cpLupaArquivoRemessaTipo').style.display	= "none";
				document.getElementById('cpArquivoRemessaTipo').style.display		= "none";
				
				document.getElementById('tbAvisoFaturaAtraso').style.display		= "block";
                document.getElementById('titAvisoFaturaAtraso').style.display		= "block";
				document.getElementById('cpAvisoFaturaAtraso').style.display		= "block";
			
				document.formulario.IdArquivoRemessaTipo.value						= "";
				document.formulario.DescricaoArquivoRemessaTipo.value				= "";
				
				document.formulario.IdAtualizarVencimentoViaCDA.disabled = false;
				break;				
			case 3: // Débito em Conta
				document.getElementById('titIdAtualizarRemessaViaCDA').style.display			= "block";
				document.getElementById('titIdAtualizarRemessaViaContaReceber').style.display	= "block";
				document.formulario.IdAtualizarRemessaViaCDA.style.display						= "block";
				document.formulario.IdAtualizarRemessaViaContaReceber.style.display				= "block";
				
				document.formulario.IdContraApresentacao.style.width 				='129px';
				document.formulario.IdCobrarMultaJurosProximaFatura.style.width 	='155px';
				document.formulario.IdAtualizarVencimentoViaCDA.style.width			='122px';
				document.formulario.IdAtualizarRemessaViaCDA.style.width 			='108px';
				document.formulario.IdAtualizarRemessaViaContaReceber.style.width 	='143px';
				document.getElementById('titStatus').style.marginLeft				= '9px';
				document.formulario.IdStatus.style.marginLeft						= '9px';
				document.formulario.IdStatus.style.width 							='110px';
				
				document.getElementById('titArquivoRetornoTipo').style.display		= "block";
				document.getElementById('cpLupaArquivoRetornoTipo').style.display	= "block";
				document.getElementById('cpArquivoRetornoTipo').style.display		= "block";
				
				document.getElementById('titArquivoRemessaTipo').style.display		= "block";
				document.getElementById('cpLupaArquivoRemessaTipo').style.display	= "block";
				document.getElementById('cpArquivoRemessaTipo').style.display		= "block";
				
				document.getElementById('tbAvisoFaturaAtraso').style.display		= "block";
				document.getElementById('titAvisoFaturaAtraso').style.display		= "none";
				document.getElementById('cpAvisoFaturaAtraso').style.display		= "none";
				
				document.formulario.AvisoFaturaAtraso.value							= "";
				
				document.formulario.IdAtualizarVencimentoViaCDA.value = 2;
				document.formulario.IdAtualizarVencimentoViaCDA.disabled = true;
				document.formulario.IdAtualizarRemessaViaCDA.value	= document.formulario.IdAtualizarRemessaViaCDADefault.value;
				document.formulario.IdAtualizarRemessaViaContaReceber.value	= document.formulario.IdAtualizarRemessaViaContaReceberDefault.value;
				break;		
			case 4: // Boleto com Remessa
				document.getElementById('titIdAtualizarRemessaViaCDA').style.display			= "block";
				document.getElementById('titIdAtualizarRemessaViaContaReceber').style.display	= "block";
				document.formulario.IdAtualizarRemessaViaCDA.style.display						= "block";
				document.formulario.IdAtualizarRemessaViaContaReceber.style.display				= "block";
				
				document.formulario.IdContraApresentacao.style.width				='129px';
				document.formulario.IdCobrarMultaJurosProximaFatura.style.width 	='155px';
				document.formulario.IdAtualizarVencimentoViaCDA.style.width 		='122px';
				document.formulario.IdAtualizarRemessaViaCDA.style.width 			='108px';
				document.formulario.IdAtualizarRemessaViaContaReceber.style.width 	='143px';
				document.getElementById('titStatus').style.marginLeft				= '9px';
				document.formulario.IdStatus.style.marginLeft						= '9px';
				document.formulario.IdStatus.style.width 							='110px';
				
				document.getElementById('titArquivoRemessaTipo').style.display		= "block";
				document.getElementById('cpLupaArquivoRemessaTipo').style.display	= "block";
				document.getElementById('cpArquivoRemessaTipo').style.display		= "block";
				
				document.getElementById('titArquivoRetornoTipo').style.display		= "block";
				document.getElementById('cpLupaArquivoRetornoTipo').style.display	= "block";
				document.getElementById('cpArquivoRetornoTipo').style.display		= "block";
				
				document.getElementById('tbAvisoFaturaAtraso').style.display		= "block";
                document.getElementById('titAvisoFaturaAtraso').style.display		= "block";
				document.getElementById('cpAvisoFaturaAtraso').style.display		= "block";
				
				document.formulario.IdAtualizarVencimentoViaCDA.disabled = false;
				document.formulario.IdAtualizarRemessaViaCDA.value	= document.formulario.IdAtualizarRemessaViaCDADefault.value;
				document.formulario.IdAtualizarRemessaViaContaReceber.value	= document.formulario.IdAtualizarRemessaViaContaReceberDefault.value;
				break;
			case 5: // Pagamento On-Line
				document.getElementById('titIdAtualizarRemessaViaCDA').style.display			= "none";
				document.getElementById('titIdAtualizarRemessaViaContaReceber').style.display	= "none";
				document.formulario.IdAtualizarRemessaViaCDA.style.display						= "none";
				document.formulario.IdAtualizarRemessaViaContaReceber.style.display				= "none";
				
				document.formulario.IdContraApresentacao.style.width 				= '210px';
				document.formulario.IdCobrarMultaJurosProximaFatura.style.width 	= '216px';
				document.formulario.IdAtualizarVencimentoViaCDA.style.width 		= '181px';
				document.getElementById('titStatus').style.marginLeft				= '5px';
				document.formulario.IdStatus.style.marginLeft						= '5px';
				document.formulario.IdStatus.style.width 							= '182px';
				
				document.getElementById('titArquivoRemessaTipo').style.display		= "none";
				document.getElementById('cpLupaArquivoRemessaTipo').style.display	= "none";
				document.getElementById('cpArquivoRemessaTipo').style.display		= "none";
				
				document.getElementById('titArquivoRetornoTipo').style.display		= "none";
				document.getElementById('cpLupaArquivoRetornoTipo').style.display	= "none";
				document.getElementById('cpArquivoRetornoTipo').style.display		= "none";
				
				document.getElementById('tbAvisoFaturaAtraso').style.display		= "none";
                document.getElementById('titAvisoFaturaAtraso').style.display		= "none";
				document.getElementById('cpAvisoFaturaAtraso').style.display		= "none";
				
				document.formulario.AvisoFaturaAtraso.value							= "";
				document.formulario.IdArquivoRetornoTipo.value						= "";
				document.formulario.DescricaoArquivoRetornoTipo.value				= "";			
				document.formulario.IdArquivoRemessaTipo.value						= "";
				document.formulario.DescricaoArquivoRemessaTipo.value				= "";
				
				document.formulario.IdAtualizarVencimentoViaCDA.disabled = false;
				break;	
			case 6: // Cartão de Crédito
				document.getElementById('titIdAtualizarRemessaViaCDA').style.display			= "block";
				document.getElementById('titIdAtualizarRemessaViaContaReceber').style.display	= "block";
				document.formulario.IdAtualizarRemessaViaCDA.style.display						= "block";
				document.formulario.IdAtualizarRemessaViaContaReceber.style.display				= "block";
				
				document.formulario.IdContraApresentacao.style.width 				='129px';
				document.formulario.IdCobrarMultaJurosProximaFatura.style.width 	='155px';
				document.formulario.IdAtualizarVencimentoViaCDA.style.width			='122px';
				document.formulario.IdAtualizarRemessaViaCDA.style.width 			='108px';
				document.formulario.IdAtualizarRemessaViaContaReceber.style.width 	='143px';
				document.getElementById('titStatus').style.marginLeft				= '9px';
				document.formulario.IdStatus.style.marginLeft						= '9px';
				document.formulario.IdStatus.style.width 							='110px';
				
				document.getElementById('titArquivoRetornoTipo').style.display		= "block";
				document.getElementById('cpLupaArquivoRetornoTipo').style.display	= "block";
				document.getElementById('cpArquivoRetornoTipo').style.display		= "block";
				
				document.getElementById('titArquivoRemessaTipo').style.display		= "block";
				document.getElementById('cpLupaArquivoRemessaTipo').style.display	= "block";
				document.getElementById('cpArquivoRemessaTipo').style.display		= "block";
				
				document.getElementById('tbAvisoFaturaAtraso').style.display		= "block";
				document.getElementById('titAvisoFaturaAtraso').style.display		= "none";
				document.getElementById('cpAvisoFaturaAtraso').style.display		= "none";
				
				document.formulario.AvisoFaturaAtraso.value							= "";
				
				document.formulario.IdAtualizarVencimentoViaCDA.value = 2;
				document.formulario.IdAtualizarVencimentoViaCDA.disabled = true;
				document.formulario.IdAtualizarRemessaViaCDA.value	= document.formulario.IdAtualizarRemessaViaCDADefault.value;
				document.formulario.IdAtualizarRemessaViaContaReceber.value	= document.formulario.IdAtualizarRemessaViaContaReceberDefault.value;
				break;		
			default:
			
		}		
	}
	function verificar_obrigatoriedade(campo,extensoes){
		if(campo.value != ''){
			var temp = campo.value.split('.');
			var ext = temp[temp.length-1].toLowerCase();
			document.formulario.Erro.value = 0;
			
			if(!extensoes.in_array(ext) && ext != ''){
				for(i = 0; i<document.formulario.length; i++){
					if(document.formulario[i].name != undefined){
						if(document.formulario[i].name == campo.name){
							document.formulario.Erro.value = 192;
							
							document.formulario[i-1].focus();
						}
					}
				}
			} else{
				for(i = 0; i<document.formulario.length; i++){
					if(document.formulario[i].name != undefined){
						if(document.formulario[i].name.substring(0,11) == 'EndArquivo_'){
							var temp = document.formulario[i].value.split('.');
							var ext = temp[temp.length-1].toLowerCase();
							
							if(!extensoes.in_array(ext) && ext != ''){
								mensagens(192);
								document.formulario[i-1].focus();
							}
						}
					}
				}
			}
			
			verificaErro();
		}
	}