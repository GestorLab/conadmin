jQuery(document).ready(function(){
	
	$j = jQuery.noConflict();
	
	$j("#form").on("submit", function(e){
		var cont = 0;
		var className = "";
		if(validar()){
			mensagens(0);
			$j(".obrig").each(function(index){
				if($j(this).is("input:checkbox")){
					if($j(this).is(":checked") && cont == 0){
						cont++;
						className = "";
					}else if(!$j(this).is(":checked") && cont == 0){
						if(index == 0)
							className = "." + $j(this).attr("class") + ":eq("+index+")";
					}
				}else if($j(this).is("select")){
					if($j(this).attr("class").indexOf("obrig") != -1){
						if($j(this).attr("disabled") != "disabled"){
							if($j(this).find("option:first:selected").val() == 0){
								$j(this).focus();
								mensagens(1);
								e.preventDefault();
								return false;
							}
						}
					}
				}
				
			});
			
			if(className != "" && cont == 0){
				$j(className).focus();
				mensagens(1);
				e.preventDefault();
				return false;
			}
		}else{
			e.preventDefault();
		}
		
	});
});	

	var ContExecucao = 0;
	
	function busca_status_contrato(IdStatus,VarStatus){
		if(IdStatus == undefined){
			IdStatus = 0;
		}
	
		if(VarStatus == undefined){
			VarStatus = '';
		}
		
		var url = "xml/parametro_sistema.php?IdGrupoParametroSistema=69&IdParametroSistema="+IdStatus;
		
		if(IdStatus == '201'){
			url += "&VarStatus="+VarStatus;
		}
		
		url += "&"+Math.random();
		
		call_ajax(url,function(xmlhttp){
			if(xmlhttp.responseText != 'false'){
				var nameNode = xmlhttp.responseXML.getElementsByTagName("ValorParametroSistema")[0]; 
				var nameTextNode = nameNode.childNodes[0];
				var ValorParametroSistema = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("Cor")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var Cor = nameTextNode.nodeValue;
				
				document.getElementById("cpStatusContrato").style.display	= "block";
				if(VarStatus != "" && (IdStatus == 204 || IdStatus == 306)){
					document.getElementById("cpStatusContrato").innerHTML 		= "<span style='color:"+Cor+"'>"+ValorParametroSistema.replace(")"," p/ ")+""+VarStatus+")"+document.formulario.StatusTempoAlteracao.value+"</span>";
				} else{
					document.getElementById("cpStatusContrato").innerHTML 		= "<span style='color:"+Cor+"'>"+ValorParametroSistema+" "+document.formulario.StatusTempoAlteracao.value+"</span>";
				}
			} else{
				document.getElementById('cpStatusContrato').style.display	= "none";
			}
		});
	}
	function busca_status(campo,IdStatus,VarStatus){
		if(IdStatus == undefined){
			IdStatus = '';
		}
		if(VarStatus == undefined){
			VarStatus = '';
		}
		while(campo.options.length > 0){
			campo.options[0] = null;
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
		url = xmlhttp.open("GET", "xml/parametro_sistema.php?IdGrupoParametroSistema=69"); 
		// Carregando...
		carregando(true);
		
		xmlhttp.onreadystatechange = function(){ 
			if(xmlhttp.readyState == 4){ 
				if(xmlhttp.status == 200){
					if(xmlhttp.responseText != 'false'){		
						var nameNode, nameTextNode, IdParametroSistema, ValorParametroSistema;					
						
						addOption(campo,"","0");
						
						for(var i=0; i<xmlhttp.responseXML.getElementsByTagName("IdParametroSistema").length; i++){
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("IdParametroSistema")[i]; 
							nameTextNode = nameNode.childNodes[0];
							IdParametroSistema = nameTextNode.nodeValue;

							nameNode = xmlhttp.responseXML.getElementsByTagName("ValorParametroSistema")[i]; 
							nameTextNode = nameNode.childNodes[0];
							ValorParametroSistema = nameTextNode.nodeValue;
							
							if(IdParametroSistema == IdStatus && VarStatus != ''){
								switch(IdParametroSistema){
									case '201':							
										ValorParametroSistema	=	ValorParametroSistema.replace('Temporariamente','até '+VarStatus);
										break;
								}
							}

							addOption(campo,ValorParametroSistema,IdParametroSistema);
						}
						
						for(ii=0;ii<campo.length;ii++){
							if(campo[ii].value == IdStatus){
								campo[ii].selected = true;	
							}
						}
						
						switch(IdStatus){
							case '201':
								document.getElementById('tDataBloqueio').innerHTML			=	'Data Bloqueio';
								document.getElementById('tDataBloqueio').style.display		=	'block';
								document.getElementById('cmpDataBloqueio').style.display	=	'block';
								document.getElementById('tDataFinal').style.display			=	'none';
								document.getElementById('cmpDataFinal').style.display		=	'none';
								document.formulario.DataBloqueio.value						=	VarStatus;
								break;
							case '305':
								document.getElementById('tDataBloqueio').innerHTML			=	'Ordem Serviço';
								document.getElementById('tDataBloqueio').style.display		=	'block';
								document.getElementById('cmpDataBloqueio').style.display	=	'block';
								document.getElementById('tDataFinal').style.display			=	'none';
								document.getElementById('cmpDataFinal').style.display		=	'none';
								document.formulario.DataBloqueio.value						=	VarStatus;
								break;
							case '306':
								document.getElementById('tDataBloqueio').innerHTML			=	'Data Agendam.';
								document.getElementById('tDataBloqueio').style.display		=	'block';
								document.getElementById('cmpDataBloqueio').style.display	=	'block';
								document.getElementById('tDataFinal').style.display			=	'none';
								document.getElementById('cmpDataFinal').style.display		=	'none';
								document.formulario.DataBloqueio.value						=	VarStatus;
								break;
							default:
								document.getElementById('tDataBloqueio').style.display		=	'none';
								document.getElementById('cmpDataBloqueio').style.display	=	'none';
								document.getElementById('tDataFinal').style.display			=	'none';
								document.getElementById('cmpDataFinal').style.display		=	'none';
								document.formulario.DataBloqueio.value						=	"";
								document.formulario.DataFinal.value							=	"";
						}	
					}
					// Fim de Carregando
					carregando(false);
					
				}
			}
		}
		xmlhttp.send(null);	
	}
	function calculaPeriodicidadeServico(IdPeriodicidade,valor,campo){
		if(valor != ''){
			if(valor.indexOf(",") != -1){	
				valor = valor.replace('.','');
				valor = valor.replace('.','');
				valor = valor.replace(',','.');
			}
			valor 		  = parseFloat(valor);
			
			var Meses = 1;
			
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
		    
		   	url = "xml/periodicidade.php?IdPeriodicidade="+IdPeriodicidade;
		
			xmlhttp.open("GET", url,true);
			xmlhttp.onreadystatechange = function(){ 
		
				if(xmlhttp.readyState == 4){ 
					if(xmlhttp.status == 200){;
						if(xmlhttp.responseText != 'false'){	
							nameNode = xmlhttp.responseXML.getElementsByTagName("Fator")[0]; 
							nameTextNode = nameNode.childNodes[0];
							Fator = nameTextNode.nodeValue;
						
							campo.value = valor*parseInt(Fator);
							campo.value = formata_float(Arredonda(campo.value,2),2).replace(".",",");
						}
					}
				}
			}
			xmlhttp.send(null);
		}else{
			campo.value = '';
		}
	}
	function inicia(){
		document.formulario.IdStatus.focus();
	}
	function verifica_status(valor){
		document.getElementById('cpLancamentoFinanceiroAguardandoCobranca').style.display = 'none';
		
		document.formulario.DataBloqueioStatus.value					= '';
		document.formulario.DataUltimaCobrancaStatus.value				= '';
		document.formulario.DataTerminoStatus.value						= '';
		document.formulario.Obs.value									= '';
		
		switch(valor){
			case '1':	//Cancelado
				document.getElementById('tableAtivoTemp').style.display		= 'none';
				document.getElementById('tableCancelado').style.display		= 'block';
				document.getElementById('avisoAtivo').style.display			= 'none';
				document.getElementById('tableObs').style.display			= 'none';
				document.formulario.bt_alterar.style.display				= 'none';
				document.formulario.bt_cancelar.style.display				= 'inline';
				document.getElementById("cp_log").style.display				= "block";
				document.formulario.Acao.value 								= 'cancelar';
				
				listar_conta_receber_aberto(document.formulario.IdContrato.value);
				listar_lancamento_financeiro_aguardando_cobranca(document.formulario.IdContrato.value);
				break;
			case '200': //Ativo
				if(document.formulario.IdStatusAnterior.value == 1){
					document.getElementById('tableAtivoTemp').style.display		= 'block';
					document.getElementById('titDataBloqueio').innerHTML		= 'Data Reativação';
					document.getElementById('titDataBloqueio').style.color		= '#000000';
					document.getElementById('tableCancelado').style.display		= 'none';
					document.getElementById('avisoAtivo').style.display			= 'block';
					document.getElementById('tableObs').style.display			= 'block';
					document.getElementById('cp_conta_receber').style.display	= 'none';
					document.getElementById('cp_log').style.display				= 'none';
					document.formulario.bt_alterar.style.display				= 'inline';
					document.formulario.bt_cancelar.style.display				= 'none';
				}else{
					document.getElementById('tableAtivoTemp').style.display		= 'none';
					document.getElementById('tableCancelado').style.display		= 'none';
					document.getElementById('avisoAtivo').style.display			= 'none';
					document.getElementById('tableObs').style.display			= 'block';
					document.getElementById('cp_conta_receber').style.display	= 'none';
					document.getElementById('cp_log').style.display				= 'none';
					document.formulario.bt_alterar.style.display				= 'inline';
					document.formulario.bt_cancelar.style.display				= 'none';
				}
				
				//document.getElementById('cpContaReceberAberto').style.display	=	'none';
				document.getElementById('cpObs').innerHTML						=	'Observações da Ativação';
				break;
			case '201':	//Ativo temporariamente
				document.getElementById('titDataBloqueio').innerHTML			= 'Data Bloqueio';
				document.getElementById('titDataBloqueio').style.color			= '#C10000';
				document.getElementById('tableAtivoTemp').style.display			= 'block';
				document.getElementById('tableCancelado').style.display			= 'none';
				document.getElementById('avisoAtivo').style.display				= 'none';
				document.getElementById('tableObs').style.display				=  'block';
				document.getElementById('cpObs').innerHTML						= 'Observações da Ativação';
				document.getElementById('cp_conta_receber').style.display		= 'none';
				document.getElementById('cp_log').style.display					= 'none';
				document.formulario.bt_alterar.style.display					= 'inline';
				document.formulario.bt_cancelar.style.display					= 'none';
				break;
			case '202': //Ativo sem cobrança
				document.getElementById('tableAtivoTemp').style.display			= 'none';
				document.getElementById('tableCancelado').style.display			= 'none';
				document.getElementById('avisoAtivo').style.display				= 'none';
				document.getElementById('tableObs').style.display				= 'block';
				document.getElementById('cpObs').innerHTML						= 'Observações da Ativação';
				document.getElementById('cp_conta_receber').style.display		= 'none';
				document.getElementById('cp_log').style.display					= 'none';
				document.formulario.bt_alterar.style.display					= 'inline';
				document.formulario.bt_cancelar.style.display					= 'none';
				break;
			case '204': //Ativo (Agendado)
				document.getElementById('titDataBloqueio').innerHTML			=	'Data Ativação';
				document.getElementById('titDataBloqueio').style.color			=	'#C10000';
				document.getElementById('tableAtivoTemp').style.display			=	'block';
				document.getElementById('tableCancelado').style.display			=	'none';
				document.getElementById('avisoAtivo').style.display				=	'none';
				document.getElementById('tableObs').style.display				=	'block';
				document.getElementById('cpObs').innerHTML						=	'Observações da Ativação';
				document.getElementById('cp_conta_receber').style.display		=	'none';
				document.getElementById('cp_log').style.display					=	'none';
				document.formulario.bt_alterar.style.display					= 	'inline';
				document.formulario.bt_cancelar.style.display					= 	'none';
				break;	
			case '301': //Bloqueiado Técnico
				document.getElementById('tableAtivoTemp').style.display			=	'none';
				document.getElementById('tableCancelado').style.display			=	'none';
				document.getElementById('avisoAtivo').style.display				=	'none';
				document.getElementById('tableObs').style.display				=	'block';
				document.getElementById('cpObs').innerHTML						=	'Observações do Bloqueio';
				document.getElementById('cp_conta_receber').style.display		=	'none';
				document.getElementById('cp_log').style.display					=	'none';
				document.formulario.bt_alterar.style.display					= 	'inline';
				document.formulario.bt_cancelar.style.display					= 	'none';
				break;
			case '302': //Bloqueiado Administraivo
				document.getElementById('tableAtivoTemp').style.display			=	'none';
				document.getElementById('tableCancelado').style.display			=	'none';
				document.getElementById('avisoAtivo').style.display				=	'none';
				document.getElementById('tableObs').style.display				=	'block';
				document.getElementById('cpObs').innerHTML						=	'Observações do Bloqueio';
				document.getElementById('cp_conta_receber').style.display		=	'none';
				document.getElementById('cp_log').style.display					=	'none';
				document.formulario.bt_alterar.style.display					= 	'inline';
				document.formulario.bt_cancelar.style.display					= 	'none';
				break;
			case '303': //Bloqueiado Financeiro
				document.getElementById('tableAtivoTemp').style.display			=	'none';
				document.getElementById('tableCancelado').style.display			=	'none';
				document.getElementById('avisoAtivo').style.display				=	'none';
				document.getElementById('tableObs').style.display				=	'block';
				document.getElementById('cpObs').innerHTML						=	'Observações do Bloqueio';
				document.getElementById('cp_conta_receber').style.display		=	'none';
				document.getElementById('cp_log').style.display					=	'none';
				document.formulario.bt_alterar.style.display					= 	'inline';
				document.formulario.bt_cancelar.style.display					= 	'none';
				break;
			case '304': //Bloqueiado Aguardando Ativação
				document.getElementById('tableAtivoTemp').style.display			=	'none';
				document.getElementById('tableCancelado').style.display			=	'none';
				document.getElementById('avisoAtivo').style.display				=	'none';
				document.getElementById('tableObs').style.display				=	'block';
				document.getElementById('cpObs').innerHTML						=	'Observações do Bloqueio';
				document.getElementById('cp_conta_receber').style.display		=	'none';
				document.getElementById('cp_log').style.display					=	'none';
				document.formulario.bt_alterar.style.display					= 	'inline';
				document.formulario.bt_cancelar.style.display					= 	'none';
				break;
			case '305': //Bloqueado (Em Andamento)
				document.getElementById('tableAtivoTemp').style.display			=	'none';
				document.getElementById('tableCancelado').style.display			=	'none';
				document.getElementById('avisoAtivo').style.display				=	'none';
				document.getElementById('tableObs').style.display				=	'block';
				document.getElementById('cpObs').innerHTML						=	'Observações do Bloqueio';
				document.getElementById('cp_conta_receber').style.display		=	'none';
				document.getElementById('cp_log').style.display					=	'none';
				document.formulario.bt_alterar.style.display					= 	'inline';
				document.formulario.bt_cancelar.style.display					= 	'none';
				break;
			case '306': //Bloqueado (Em Agendado)
				document.getElementById('titDataBloqueio').innerHTML			=	'Data Bloqueio';
				document.getElementById('titDataBloqueio').style.color			=	'#C10000';
				document.getElementById('tableAtivoTemp').style.display			=	'block';
				document.getElementById('tableCancelado').style.display			=	'none';
				document.getElementById('avisoAtivo').style.display				=	'none';
				document.getElementById('tableObs').style.display				=	'block';
				document.getElementById('cpObs').innerHTML						=	'Observações do Bloqueio';
				document.getElementById('cp_conta_receber').style.display		=	'none';
				document.getElementById('cp_log').style.display					=	'none';
				document.formulario.bt_alterar.style.display					= 	'inline';
				document.formulario.bt_cancelar.style.display					= 	'none';
				break;
			default:
				document.getElementById('tableAtivoTemp').style.display			=	'none';
				document.getElementById('tableCancelado').style.display			=	'none';
				document.getElementById('avisoAtivo').style.display				=	'none';
				document.getElementById('tableObs').style.display				=	'block';
				document.getElementById('cpObs').innerHTML						=	'Observações';
				document.getElementById('cp_conta_receber').style.display		=	'none';
				document.getElementById('cp_log').style.display					=	'none';
				document.formulario.bt_alterar.style.display					= 	'inline';
				document.formulario.bt_cancelar.style.display					= 	'none';
		}
	}
	function validar_Data(id,campo){				
		if(document.formulario.IdStatus.value == 200){
			document.getElementById(id).style.backgroundColor = '#C10000';
			if(dataConv(campo.value, "d/m/Y", "Ymd") <= dataConv(document.formulario.DataTermino.value, "d/m/Y", "Ymd")){
				document.getElementById(id).style.color='#FFFFFF';
				mensagens(27);
				return false;
			}
		}
		if(campo.value == ''){
			document.getElementById(id).style.backgroundColor='#FFFFFF';
			if(document.formulario.IdStatus.value == '200'){
				document.getElementById(id).style.color='#000';
			}else{
				document.getElementById(id).style.color='#C10000';
			}
			mensagens(0);
			return false;
		}
		
		if(isData(campo.value) == false){		
			document.getElementById(id).style.backgroundColor = '#C10000';
			document.getElementById(id).style.color='#FFFFFF';
			mensagens(27);
			return false;
		}else{
			document.getElementById(id).style.backgroundColor='#FFFFFF';
			if(document.formulario.IdStatus.value == '200'){
				document.getElementById(id).style.color='#000';
			}else{
				document.getElementById(id).style.color='#C10000';
			}
			mensagens(0);
			return true;
		}	
	}
	function validar(){
		var posInicial = 0, posFinal = 0, campo = "";
		if(document.formulario.IdStatus.value == 0){
			document.formulario.IdStatus.focus();
			mensagens(1);
			return false;
		}else{
			switch(document.formulario.IdStatus.value){
				case '1':	//Cancelado
					if(document.formulario.DataUltimaCobrancaStatus.value == ''){
						document.formulario.DataUltimaCobrancaStatus.focus();
						mensagens(1);
						return false;
					}else{
						if(isData(document.formulario.DataUltimaCobrancaStatus.value) == false){
							document.formulario.DataUltimaCobrancaStatus.focus();
							mensagens(27);
							if (confirm("Tem certeza que deseja excluir essa categoria?")) {  
								//location.href="deletar_categoria.jsp?acao=deletar";  
							}  
							return false;	
						}
					}
					
					if(document.formulario.DataTerminoStatus.value == ''){
						document.formulario.DataTerminoStatus.focus();
						mensagens(1);
						return false;
					}else{
						if(isData(document.formulario.DataTerminoStatus.value) == false){
							document.formulario.DataTerminoStatus.focus();
							mensagens(27);
							return false;	
						}
					}
					
					for(i = 0; i < document.formulario.length; i++){
						if(document.formulario[i].name != undefined){
							if(document.formulario[i].name.substring(0,16) == 'ValorLancamento_'){
								if(posInicial == 0){
									posInicial = i;
								}
								posFinal = i;
							}
						}
					}
					
					for(i = posInicial; i <= posFinal; i += 8){
						if((document.formulario[i+1].value == "" || document.formulario[i+1].value == "0") && document.formulario[i+1].disabled == false && document.formulario[i+1].name != "Erro"){
							mensagens(1);
							document.formulario[i+1].focus();
							return false;
						}
					}
					
					if(document.formulario.ObsCancelamento.value == '' && document.formulario.IdStatus.value == 1){
						mensagens(1);
						document.formulario.ObsCancelamento.focus();
						return false;
					}
					
					if(document.formulario.Obs.value == '' && document.formulario.IdStatus.value != 1){
						mensagens(1);
						document.formulario.Obs.focus();
						return false;
					}
					
					break;
				case '201':
					if(document.formulario.DataBloqueioStatus.value == ''){
						document.formulario.DataBloqueioStatus.focus();
						mensagens(1);
						return false;
					}else{
						if(isData(document.formulario.DataBloqueioStatus.value) == false){
							document.formulario.DataBloqueioStatus.focus();
							mensagens(27);
							return false;	
						}
					}
					break;
				case '200':
					if(document.formulario.DataBloqueioStatus.value!=""){
						if(isData(document.formulario.DataBloqueioStatus.value) == false){
							document.formulario.DataBloqueioStatus.focus();
							mensagens(27);
							return false;	
						}
						if(dataConv(document.formulario.DataBloqueioStatus.value, "d/m/Y", "Ymd") <= dataConv(document.formulario.DataTermino.value, "d/m/Y", "Ymd")){
							document.formulario.DataBloqueioStatus.focus();							
							mensagens(27);
							return false;
						}	
					}									
					break;	
				case '306':
					if(document.formulario.DataBloqueioStatus.value == ''){
						document.formulario.DataBloqueioStatus.focus();
						mensagens(1);
						return false;
					}else{
						if(isData(document.formulario.DataBloqueioStatus.value) == false){
							document.formulario.DataBloqueioStatus.focus();
							mensagens(27);
							return false;	
						}
					}
					break;
				default:
					if(document.formulario.IdStatusAnterior.value == document.formulario.IdStatus.value){
						document.formulario.IdStatus.focus();
						mensagens(100);
						return false;
					}
			}
			if(document.formulario.Obs.value == '' && document.formulario.IdStatus.value != 1){
				document.formulario.Obs.focus();
				mensagens(1);
				return false;
			}
			if(document.getElementById("bl_Protocolo").style.display == "block"){
				if(document.formulario.ProtocoloAssunto.value == ''){
					document.formulario.ProtocoloAssunto.focus();
					mensagens(1);
					return false;
				}
				if(document.formulario.ProtocoloObservacao.value == ''){
					document.formulario.ProtocoloObservacao.focus();
					mensagens(1);
					return false;
				}
			}
		}
		return true;
	}
	function verificaDataFinal(campo,DataInicio,DataFim){
		if(DataInicio != '' && DataFim != ''){
			var dataI = formatDate(DataInicio);
			var dataF = formatDate(DataFim);
			if(dataF < dataI){
				document.getElementById(campo).style.backgroundColor = '#C10000';
				document.getElementById(campo).style.color='#FFFFFF';
				mensagens(39);
				return false;
			}else{
				colorTemp = document.getElementById(campo).style.backgroundColor;
				document.getElementById(campo).style.backgroundColor = '#FFFFFF';
				document.getElementById(campo).style.color='#C10000';
				mensagens(0);
			}
			return true;
		}
	}
	function verificaAcao(){
		if(document.formulario.IdContrato.value == ''){
			document.formulario.bt_alterar.disabled 	= true;
		}else{
			document.formulario.bt_alterar.disabled 	= false;
		}
		
		if(document.formulario.Acao.value =='cancelar'){			
			document.formulario.bt_alterar.style.display = 'none';
		}
	}
	function voltar(){
		window.location.replace("cadastro_contrato.php?IdContrato="+document.formulario.IdContrato.value);
	}
	
	function listar_conta_receber_aberto(IdContrato){
		if(IdContrato == undefined || IdContrato == ''){
			IdContrato = 0;
		}
		
	   	var url = "xml/conta_receber.php?Local=ContratoStatus&IdContrato="+IdContrato;
		
		call_ajax(url,function (xmlhttp){
			if(xmlhttp.responseText == "false"){
				while(document.getElementById("tabelaContaReceber").rows.length > 2){
					document.getElementById("tabelaContaReceber").deleteRow(1);
				}
				
				document.getElementById("cp_conta_receber").style.display =	"none";
				document.getElementById("tabelaTotalValor").innerHTML = "0,00";		
				document.getElementById("tabelaTotal").innerHTML = "Total: 0";	
			} else{
				while(document.getElementById("tabelaContaReceber").rows.length > 2){
					document.getElementById("tabelaContaReceber").deleteRow(1);
				}
				
				var tabindex = Number(document.formulario.TabIndex.value), TotalValor = 0, cont = 0;
				
				for(var i = 0; i < xmlhttp.responseXML.getElementsByTagName("IdContaReceber").length; i++){	
					var nameNode = xmlhttp.responseXML.getElementsByTagName("IdContaReceber")[i]; 
					var nameTextNode = nameNode.childNodes[0];
					var IdContaReceber = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("NumeroDocumento")[i]; 
					nameTextNode = nameNode.childNodes[0];
					var NumeroDocumento = nameTextNode.nodeValue;	
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("NumeroNF")[i]; 
					nameTextNode = nameNode.childNodes[0];
					var NumeroNF = nameTextNode.nodeValue;	
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("AbreviacaoNomeLocalCobranca")[i]; 
					nameTextNode = nameNode.childNodes[0];
					var AbreviacaoNomeLocalCobranca = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("DataLancamento")[i]; 
					nameTextNode = nameNode.childNodes[0];
					var DataLancamento = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("ValorReceber")[i]; 
					nameTextNode = nameNode.childNodes[0];
					var Valor = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("DataVencimento")[i]; 
					nameTextNode = nameNode.childNodes[0];
					var DataVencimento = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("DataRecebimento")[i]; 
					nameTextNode = nameNode.childNodes[0];
					var DataRecebimento = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("IdStatus")[i]; 
					nameTextNode = nameNode.childNodes[0];
					var IdStatus = nameTextNode.nodeValue;
					
					var tam = document.getElementById("tabelaContaReceber").rows.length;
					var linha = document.getElementById("tabelaContaReceber").insertRow(tam-1);
					
					if(IdStatus != 0 && IdStatus){
						if((tam % 2) != 0){
							linha.style.backgroundColor = "#E2E7ED";
						}
						
						linha.accessKey = IdContaReceber; 
						TotalValor = parseFloat(TotalValor) + parseFloat(Valor);
						
						var c0 = linha.insertCell(0);	
						var c1 = linha.insertCell(1);	
						var c2 = linha.insertCell(2);	
						var c3 = linha.insertCell(3);
						var c4 = linha.insertCell(4);
						var c5 = linha.insertCell(5);
						var c6 = linha.insertCell(6);
						var c7 = linha.insertCell(7);
						
						linkIni = "<a href='cadastro_conta_receber.php?IdContaReceber="+IdContaReceber+"'>"
						linkFim	= "</a>";
						
						c0.innerHTML = "<input style='border:0' class='obrig' type='checkbox' name='cr_"+IdContaReceber+"' onClick='selecionar(this);' onFocus='Foco(this, \"in\")' onBlur='Foco(this,\"out\")' tabindex='"+(tabindex+i)+"'>";
						c0.className = "tableListarEspaco";
						
						c1.innerHTML = linkIni + IdContaReceber + linkFim;
						c1.style.padding  =	"0 0 0 5px";
						c1.style.cursor = "pointer";
						
						c2.innerHTML = linkIni + NumeroDocumento + linkFim;
						c2.style.padding  =	"0 0 0 5px";
						c2.style.cursor = "pointer";

						c3.innerHTML = linkIni + NumeroNF + linkFim;
						c3.style.cursor = "pointer";

						c4.innerHTML = linkIni + AbreviacaoNomeLocalCobranca + linkFim;
						c4.style.cursor = "pointer";

						c5.innerHTML = linkIni + dateFormat(DataLancamento) + linkFim;
						c5.style.cursor = "pointer";
						
						c6.innerHTML =  linkIni + formata_float(Arredonda(Valor,2),2).replace('.',',') + linkFim;
						c6.className = "valor";
						c6.style.cursor = "pointer";
						c6.style.padding  =	"0 8px 0 0";

						c7.innerHTML = linkIni + dateFormat(DataVencimento) + linkFim;
						c7.style.cursor = "pointer";
						
						cont++;
					}
				}
				
				if(cont > 0){
					document.getElementById("cp_conta_receber").style.display =	"block";
				} else{
					document.getElementById("cp_conta_receber").style.display =	"none";
				}
				
				document.formulario.TabIndex.value = (tabindex+cont);
				document.getElementById('tabelaTotalValor').innerHTML = formata_float(Arredonda(TotalValor,2),2).replace(/\./, ",");	
				document.getElementById('tabelaTotal').innerHTML = "Total: "+cont;
			}
		});
		
		scrollWindow("bottom");
	}
	function calculaValorFinal(valor,desc,perc,campo){	
		if(valor=='' || desc == '' || perc==''){
			if(valor=='')	valor = '0,00';
			if(desc=='')	desc  = '0,00';
			if(perc=='')	perc  = '0,00';
		}
		var tempValor	=	valor.replace(".","");
		tempValor		=	tempValor.replace(".","");
		tempValor		=	tempValor.replace(",",".");
		
		var tempDesc	=	desc.replace("."," ");
		tempDesc		=	tempDesc.replace("."," ");
		tempDesc		=	tempDesc.replace(",",".");
		
		var valFinal	=	tempValor - tempDesc;	

		if(campo.name == 'ValorDesconto'){				
			if(parseFloat(desc) > 0){							
				tempPerc = (parseFloat(tempDesc)*100)/parseFloat(tempValor);					
				tempPerc		= 	formata_float(Arredonda(tempPerc,2),2);
				tempPerc		=	tempPerc.replace('.',',');				
				
			}else{						
				tempPerc		=	formata_float(Arredonda(tempDesc,2),2);
				tempPerc		=	tempPerc.replace('.',',');				
			}			
			document.formulario.ValorPercentual.value	=	tempPerc;
		}else if(campo.name == 'ValorPercentual'){			
			var tempPerc	=	perc.replace("."," ");
			tempPerc		=	tempPerc.replace("."," ");
			tempPerc		=	tempPerc.replace(",",".");
		
			tempDesc		=	(parseFloat(tempPerc)*parseFloat(tempValor))/100;
			valFinal		=	tempValor -	tempDesc;
			
			tempDesc		= 	formata_float(Arredonda(tempDesc,2),2);
			tempDesc		=	tempDesc.replace('.',',');
			
			document.formulario.ValorDesconto.value	=	tempDesc;
		}
		
		valFinal		= 	formata_float(Arredonda(valFinal,2),2);
		valFinal		=	valFinal.replace('.',',');
		
		document.formulario.ValorFinal.value	=	valFinal;
	}
	
	function verificaLimiteDesconto(valor){
		switch(valor){
			case '1':
				document.getElementById('tabDataDiaDesconto').style.display 		= 'block';
				document.getElementById('titLimiteDesconto').innerHTML				=	'Data Limite Desc.';
				document.getElementById('titLimiteDesconto').style.display			=	'block';
				document.getElementById('titDataLimiteDescontoIco').style.display	=	'block';
				document.getElementById('cpDiaLimiteDesconto').style.display		=	'none';
				document.getElementById('cpDataLimiteDesconto').style.display		=	'block';				
				break;
			case '2':
				document.getElementById('tabDataDiaDesconto').style.display 		= 'block';
				document.getElementById('titLimiteDesconto').innerHTML				=	'Dia Limite Desconto';			
				document.getElementById('titLimiteDesconto').style.display			=	'block';
				document.getElementById('cpDiaLimiteDesconto').style.display		=	'block';
				document.getElementById('titDataLimiteDescontoIco').style.display	=	'none';
				document.getElementById('cpDataLimiteDesconto').style.display		=	'none';				
				break;
			case '3':			
				document.getElementById('tabDataDiaDesconto').style.display 		= 'none';
				document.getElementById('titLimiteDesconto').style.display			= 'none';
				document.getElementById('cpDiaLimiteDesconto').style.display		= 'none';
				document.getElementById('titDataLimiteDescontoIco').style.display	= 'none';
				document.getElementById('cpDataLimiteDesconto').style.display		= 'none';				
				
				document.formulario.ValorDesconto.value 							= "0,00";
				document.formulario.ValorPercentual.value	 						= "0,00"; 
				document.formulario.ValorRepasseTerceiro.value	 					= "0,00"; 
				document.formulario.DiaLimiteDesconto.value 						= "0";
				document.formulario.DataLimiteDesconto.value	 					= "";				
				break;			
		}
	}
	
	function busca_vigencia(IdContrato,Erro){		
		if(IdContrato == ''){
			IdContrato = 0;
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
	    url = "xml/contrato_vigencia_ativa.php?IdContrato="+IdContrato;

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
						document.formulario.IdTipoDesconto[0].selected 			= true;
						document.formulario.ValorDesconto.value 				= '0,00';
						document.formulario.Valor.value							= '0,00';
						document.formulario.ValorRepasseTerceiro.value			= '0,00';
						document.formulario.ValorPercentual.value				= '0,00';
						document.formulario.ValorFinal.value					= '0,00';
						document.formulario.DiaLimiteDesconto.value				= '';
						document.formulario.DataLimiteDesconto.value			= '';
						document.formulario.DiaLimiteDesconto.readOnly			= false;
						document.formulario.DataLimiteDesconto.readOnly			= false;
						document.formulario.IdTipoDesconto.value				= '';
					
						// Fim de Carregando
						carregando(false);
					}else{						
						var Valor, ValorDesconto, DataLimiteDesconto, ValorRepasseTerceiro, ValorTotal, IdTipoDesconto;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("ValorDesconto")[0]; 
						nameTextNode = nameNode.childNodes[0];
						ValorDesconto = nameTextNode.nodeValue;					
				
						nameNode = xmlhttp.responseXML.getElementsByTagName("Valor")[0]; 
						nameTextNode = nameNode.childNodes[0];
						Valor = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("ValorRepasseTerceiro")[0]; 
						nameTextNode = nameNode.childNodes[0];
						ValorRepasseTerceiro = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("IdTipoDesconto")[0]; 
						nameTextNode = nameNode.childNodes[0];
						IdTipoDesconto = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("LimiteDesconto")[0]; 
						nameTextNode = nameNode.childNodes[0];
						LimiteDesconto = nameTextNode.nodeValue;	
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("ValorTotal")[0]; 
						nameTextNode = nameNode.childNodes[0];
						ValorTotal = nameTextNode.nodeValue;
						
						verificaLimiteDesconto(IdTipoDesconto);
						
						document.formulario.ValorDesconto.value 				= formata_float(Arredonda(ValorDesconto,2),2).replace(".",",");
						document.formulario.Valor.value							= formata_float(Arredonda(Valor,2),2).replace(".",",");
						document.formulario.ValorRepasseTerceiro.value			= formata_float(Arredonda(ValorRepasseTerceiro,2),2).replace(".",",");
						document.formulario.ValorFinal.value					= formata_float(Arredonda(ValorTotal,2),2).replace(".",",");
						document.formulario.IdTipoDesconto.value				= IdTipoDesconto;
						document.formulario.DiaLimiteDesconto.readOnly			= false;
						document.formulario.DataLimiteDesconto.readOnly			= false;
						
						calculaValorFinal(document.formulario.Valor.value,document.formulario.ValorDesconto.value,'',document.formulario.ValorDesconto);
						
						
						switch(IdTipoDesconto){
							case '1':
								document.formulario.DataLimiteDesconto.value	=	dateFormat(LimiteDesconto);
								document.formulario.DiaLimiteDesconto.value		=	"";
								break;
							case '2':
								document.formulario.DataLimiteDesconto.value	=	"";
								document.formulario.DiaLimiteDesconto.value		=	LimiteDesconto;
								break;
						}						
					}						
					if(window.janela != undefined){
						window.janela.close();
					}					
				}
				// Fim de Carregando
				carregando(false);
			} 
			return true;
		}
		xmlhttp.send(null);
	}
	function listar_lancamento_financeiro_aguardando_cobranca(IdContrato){
		if(IdContrato == undefined || IdContrato==''){
			IdContrato = 0;
		}
		
		var url = "xml/contrato_status_lancamento_financeiro.php?IdContrato="+IdContrato+"&IdStatus=2,3";
		
		call_ajax(url,function (xmlhttp){
			if(xmlhttp.responseText == "false"){
				document.getElementById("cpLancamentoFinanceiroAguardandoCobranca").style.display = "none";
				
				while(document.getElementById("tabelaLancamentoFinanceiro").rows.length > 2){
					document.getElementById("tabelaLancamentoFinanceiro").deleteRow(1);
				}
				
				document.getElementById("cpValorTotalLancamentoFinanceiro").innerHTML	= "0,00";
				document.getElementById("cpDescTotalLancamentoFinanceiro").innerHTML	= "0,00";
				document.getElementById("tabelaTotalLancamentoFinanceiro").innerHTML	= "Total: 0";
			} else{
				document.getElementById("cpLancamentoFinanceiroAguardandoCobranca").style.display = "block";
				
				while(document.getElementById("tabelaLancamentoFinanceiro").rows.length > 2){
					document.getElementById("tabelaLancamentoFinanceiro").deleteRow(1);
				}
				
				var tam, linha, c0, c1, c2, c3, c4, c5, c6, c7, c8, c9 ,c10;
				var IdLoja;
				var valorParc=0, valorDesc=0;
				
				for(var i=0; i<xmlhttp.responseXML.getElementsByTagName("IdLancamentoFinanceiro").length; i++){
					var nameNode = xmlhttp.responseXML.getElementsByTagName("IdLancamentoFinanceiro")[i]; 
					var nameTextNode = nameNode.childNodes[0];
					var IdLancamentoFinanceiro = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("Tipo")[i]; 
					nameTextNode = nameNode.childNodes[0];
					var Tipo = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("Nome")[i]; 
					nameTextNode = nameNode.childNodes[0];
					var Nome = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("RazaoSocial")[i]; 
					nameTextNode = nameNode.childNodes[0];
					var RazaoSocial = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("IdProcessoFinanceiro")[i]; 
					nameTextNode = nameNode.childNodes[0];
					var IdProcessoFinanceiro = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("IdContaReceber")[i]; 
					nameTextNode = nameNode.childNodes[0];
					var IdContaReceber = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("Descricao")[i]; 
					nameTextNode = nameNode.childNodes[0];
					var Descricao = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("Codigo")[i]; 
					nameTextNode = nameNode.childNodes[0];
					var Codigo = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("Referencia")[i]; 
					nameTextNode = nameNode.childNodes[0];
					var Referencia = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("Valor")[i]; 
					nameTextNode = nameNode.childNodes[0];
					var Valor = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("ValorDescontoAConceber")[i]; 
					nameTextNode = nameNode.childNodes[0];
					var ValorDescontoAConceber = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("Status")[i]; 
					nameTextNode = nameNode.childNodes[0];
					var Status = nameTextNode.nodeValue;
					
					if(RazaoSocial != ''){
						Nome = RazaoSocial;
					}
					
					tam 	= document.getElementById("tabelaLancamentoFinanceiro").rows.length;
					linha	= document.getElementById("tabelaLancamentoFinanceiro").insertRow(tam-1);
					
					if(tam%2 != 0){
						linha.style.backgroundColor = "#E2E7ED";
					}
					
					linha.accessKey = IdLancamentoFinanceiro; 
					
					c0	= linha.insertCell(0);	
					c1	= linha.insertCell(1);	
					c2	= linha.insertCell(2);	
					c3	= linha.insertCell(3);
					c4	= linha.insertCell(4);
					c5	= linha.insertCell(5);
					c6	= linha.insertCell(6);
					c7	= linha.insertCell(7);
					c8	= linha.insertCell(8);
					c9	= linha.insertCell(9);
					c10	= linha.insertCell(10);
					
					valorParc	= parseFloat(valorParc) + parseFloat(Valor);
					valorDesc	= parseFloat(valorDesc) + parseFloat(ValorDescontoAConceber);
					
					linkIni	= "<a href='cadastro_lancamento_financeiro.php?IdLancamentoFinanceiro="+IdLancamentoFinanceiro+"'>";
					linkFim	= "</a>";
					
					c0.innerHTML = linkIni + IdLancamentoFinanceiro + linkFim;
					c0.style.padding = "0 4px 0 5px";
					
					c1.innerHTML = linkIni + Tipo + linkFim;
					c1.style.padding = "0 4px 0 0";
					
					c2.innerHTML = linkIni + Codigo + linkFim;
					c2.style.padding = "0 4px 0 0";
					
					c3.innerHTML = linkIni + Nome + linkFim;
					c3.style.padding = "0 4px 0 0";
					
					c4.innerHTML = linkIni + Descricao + linkFim;
					c4.style.padding = "0 4px 0 0";
					
					c5.innerHTML = linkIni + IdProcessoFinanceiro + linkFim;
					c5.style.padding = "0 4px 0 0";
					
					c6.innerHTML = linkIni + Referencia + linkFim;
					c6.style.padding = "0 4px 0 0";
					
					c7.innerHTML = linkIni + formata_float(Arredonda(Valor,2),2).replace('.',',')+ linkFim ;
					c7.style.textAlign = "right";
					c7.style.padding = "0 8px 0 0";
					
					c8.innerHTML = linkIni + formata_float(Arredonda(ValorDescontoAConceber,2),2).replace('.',',')+ linkFim ;
					c8.style.textAlign = "right";
					c8.style.padding = "0 8px 0 0";
					
					c9.innerHTML = linkIni + Status + linkFim;
					
					c10.innerHTML = "<img src='../../img/estrutura_sistema/ico_del_c.gif' alt='Excluir?'\">";
				}
				
				document.getElementById("cpValorTotalLancamentoFinanceiro").innerHTML	= formata_float(Arredonda(valorParc,2),2).replace('.',',');	
				document.getElementById("cpDescTotalLancamentoFinanceiro").innerHTML	= formata_float(Arredonda(valorDesc,2),2).replace('.',',');	
				document.getElementById("tabelaTotalLancamentoFinanceiro").innerHTML	= "Total: "+i;
			}
			
			scrollWindow('bottom');
		});
	}
	function validar_Email(valor,id){
		if(valor == ''){
			return false;
		}
		
		var temp = valor.split(';');
		var i = 0;
		
		while(temp[i]!= '' && temp[i]!= undefined){
			temp[i]	= ignoreSpaces(temp[i]);
			
			if(isEmail(temp[i]) == false){				
				colorTemp = document.getElementById(id).style.backgroundColor;
				document.getElementById(id).style.backgroundColor = '#C10000';
				document.getElementById(id).style.color='#FFFFFF';
				mensagens(12);
				return false;
				break;
			}
			
			i++;	
		}
		
		document.getElementById(id).style.backgroundColor='#FFFFFF';
		document.getElementById(id).style.color='#000000';
		mensagens(0);
		return true;
	}
	function selecionar(campo,buscar){
		var table = document.getElementById("tabelaContaReceber");
		
		if(buscar == undefined){
			buscar = true;
		}
		
		if(campo.name == "todos_cr"){
			var Checked = campo.checked;
			
			for(var i = 0; i < table.rows.length; i++){
				var AccessKey = table.rows[i].accessKey;
				
				if(AccessKey != '' && AccessKey != undefined){
					eval("var campo = document.formulario.cr_"+AccessKey+", valor_checked = "+Checked+"; if(campo.checked != valor_checked) { campo.checked = valor_checked; selecionar(campo,false); }");
				}
			}
		} else{
			if(campo.checked){
				document.formulario.CancelarContaReceber.value += campo.name.replace(/^cr_/i,',');
			} else{
				var ContaReceber = campo.name.replace(/^cr_/i,'');
				Exp = new RegExp("^"+ContaReceber+",|,"+ContaReceber+",|,"+ContaReceber+"$","i");
				document.formulario.CancelarContaReceber.value = (document.formulario.CancelarContaReceber.value+",").replace(Exp,',');
			}
			
			document.formulario.CancelarContaReceber.value = document.formulario.CancelarContaReceber.value.replace(/^,|,,|,$/g,'');
			var tratamento = "document.formulario.todos_cr.checked = (";
			
			for(var i = 0; i < table.rows.length; i++){
				var AccessKey = table.rows[i].accessKey;
				
				if(AccessKey != '' && AccessKey != undefined){
					tratamento += "document.formulario.cr_"+AccessKey+".checked && ";
				}
			}
			
			tratamento = tratamento.replace(/ && $/i, '')+");";
			
			eval(tratamento);
		}
		
		if(buscar){
			var CancelarContaReceber = document.formulario.CancelarContaReceber.value;
			
			if(CancelarContaReceber == ''){
				CancelarContaReceber = 0;
			}
			
			busca_lancamentos_data_base(CancelarContaReceber);
		}
	}
	
	(function($j){
		$j.teste = function(value, id, nameClass){
			
			nameClass = nameClass.split(" ");
			nameClass = nameClass[0];
			id = id.split("_");
			nameId = id[0];
			id = parseInt(id[1]);
			if($j("."+nameClass + ":eq("+id+")").find("option:selected").val() == 0){
				$j("."+nameClass).each(function(index){
					if(index == id){
						return false;
					}
					$j(this).attr("disabled", "disabled");
					
				});
				$j("."+nameClass + ":eq("+id+")").focus();
				return false;
			}
			
			if(nameClass == "co"){
				if(value == 1){
					$j(".co").each(function(index){
						if(index < (id - 1)){
							$j("#VoltarDataBase_"+index).attr("disabled", "disabled");
							$j("#VoltarDataBase_"+index+" option:last").removeAttr("selected", false);
							$j("#VoltarDataBase_"+index).append("<option value='0' selected='selected'></option>"+
									"<option value='1'>Sim</option>"+
									"<option value='2'>N\u00e3o</option>");
						}
						else{
							index = id - 1
							$j("#VoltarDataBase_"+index).removeAttr("disabled");
							$j("#VoltarDataBase_"+index+" option").remove();
							$j("#VoltarDataBase_"+index).append("<option value='0' selected='selected'></option>"+
																"<option value='1'>Sim</option>"+
																"<option value='2'>N\u00e3o</option>");
						}
					});
				}else if(value == 2){
					$j(".co").each(function(index){
						if(index <= (id - 1)){
							$j("#VoltarDataBase_"+index).attr("disabled", true);
							$j("#VoltarDataBase_"+index+" option").remove();
							$j("#VoltarDataBase_"+index).append("<option value='0'></option>"+
													"<option value='1'>Sim</option>"+
													"<option value='2' selected='selected'>N\u00e3o</option>");
						
						}
					});
					
				}
			}else{
				$j("."+nameClass).each(function(index){
					if(index < (id - 1)){
						$j("#"+nameId+"_"+index).attr("disabled", "disabled");
						$j("#"+nameId+"_"+index+" option:last").removeAttr("selected", false);
						$j("#"+nameId+"_"+index).append("<option value='0' selected='selected'></option>"+
								"<option value='1'>Sim</option>"+
								"<option value='2'>N\u00e3o</option>");
					}
					else{
						index = id - 1
						$j("#"+nameId+"_"+index).removeAttr("disabled");
						$j("#"+nameId+"_"+index+" option").remove();
						$j("#"+nameId+"_"+index).append("<option value='0' selected='selected'></option>"+
															"<option value='1'>Sim</option>"+
															"<option value='2'>N\u00e3o</option>");
					}
				});
			}
			
			foco = $j("."+nameClass + ":eq("+id+")").parent().parent().index("tr") - 2;
			//alert(nameClass);
			if(foco != -1){
				//$j("."+nameClass+":eq("+foco+")").focus();
				//alert($j("tr:eq("+foco+")").find("td:last").text());
				$j("tr:eq("+foco+")").find("td:last").children().focus();
			}
		}
	})(jQuery);
	
	function busca_lancamentos_data_base(IdContaReceber, NumDoc){
		if(IdContaReceber == undefined || IdContaReceber == ''){
			IdContaReceber = 0;
		}
		
		$j(document).ajaxStart(function(){
			carregando(true);
		});
		
		$j(document).ajaxStop(function(){
			carregando(false);
		});
		
		$j.ajax({
			type:"GET",
			dataType:"html",
			url:"xml/demonstrativo.php",
			data:{IdContaReceber: IdContaReceber, NumDoc: NumDoc},
			success:function(data){
				//alert(data);
				if(data == "false" || data == ""){
					document.getElementById("cpVoltarDataBase").innerHTML = "";
				}else{
					$j("#cpVoltarDataBase table").remove();
					document.getElementById("cpVoltarDataBase").innerHTML = data;
					/*if(document.getElementById("cpVoltarDataBase").innerHTML == ""){
						document.getElementById("cpVoltarDataBase").innerHTML = data;
					}else{
						document.getElementById("cpVoltarDataBase").innerHTML += data;
					}*/
					
					$j.each($j(".co"), function(index, value){
						if(index == ($j(".co").length - 1))
							$j(this).removeAttr("disabled");
						else
							$j(this).attr("disabled", "disabled");
					});
					$j.each($j(".ev"), function(index, value){
						if(index == ($j(".ev").length - 1))
							$j(this).removeAttr("disabled");
						else
							$j(this).attr("disabled", "disabled");
					});
					$j.each($j(".os"), function(index, value){
						if(index == ($j(".os").length - 1))
							$j(this).removeAttr("disabled");
						else
							$j(this).attr("disabled", "disabled");
					});
					$j.each($j(".ef"), function(index, value){
						if(index == ($j(".ef").length - 1))
							$j(this).removeAttr("disabled");
						else
							$j(this).attr("disabled", "disabled");
					});
					
				}
				
			}
		});
		
		/*var url = "xml/demonstrativo.php?IdContaReceber="+IdContaReceber+"&NumDoc="+NumDoc;
		
		call_ajax(url,function (xmlhttp){
			alert(xmlhttp.responseText);
			if(xmlhttp.responseText == "false" || xmlhttp.responseText == ""){
				document.getElementById("cpVoltarDataBase").innerHTML = "";
			}else{
				if(document.getElementById("cpVoltarDataBase").innerHTML == ""){
					document.getElementById("cpVoltarDataBase").innerHTML = xmlhttp.responseText;
				}else{
					document.getElementById("cpVoltarDataBase").innerHTML += xmlhttp.responseText;
				}
				
			}*/
			/*alert(xmlhttp.responseText);
			if(xmlhttp.responseText == "false"){
				document.getElementById("cpVoltarDataBase").innerHTML = "";	
			} else{
				document.getElementById("cpVoltarDataBase").innerHTML = "";	
				
				var dados = "", dados_neg = "", tabindex = Number(document.formulario.TabIndex.value);
				
				for(var i = 0; i < xmlhttp.responseXML.getElementsByTagName("IdLancamentoFinanceiro").length; i++){
					var nameNode = xmlhttp.responseXML.getElementsByTagName("IdLancamentoFinanceiro")[i]; 
					var nameTextNode = nameNode.childNodes[0];
					var IdLancamentoFinanceiro = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("Tipo")[i]; 
					nameTextNode = nameNode.childNodes[0];
					var Tipo = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("IdContaReceber")[i]; 
					nameTextNode = nameNode.childNodes[0];
					IdContaReceber = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("Codigo")[i]; 
					nameTextNode = nameNode.childNodes[0];
					var Codigo = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("Descricao")[i]; 
					nameTextNode = nameNode.childNodes[0];
					var Descricao = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("Referencia")[i]; 
					nameTextNode = nameNode.childNodes[0];
					var Referencia = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("Moeda")[i]; 
					nameTextNode = nameNode.childNodes[0];
					var Moeda = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("Valor")[i]; 
					nameTextNode = nameNode.childNodes[0];
					var Valor = nameTextNode.nodeValue;	
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("Voltar")[i]; 
					nameTextNode = nameNode.childNodes[0];
					var Voltar = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("IdContratoAutomatico")[i]; 
					nameTextNode = nameNode.childNodes[0];
					var IdContratoAutomatico = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("IdLancamentoFinanceiroAutomatico")[i]; 
					nameTextNode = nameNode.childNodes[0];
					var IdLancamentoFinanceiroAutomatico = nameTextNode.nodeValue;
					
					if(Voltar == "true" && !(new RegExp(","+IdLancamentoFinanceiro+",$")).test(","+document.formulario.LancamentoFinanceiroTipoContrato.value+",")){
						Voltar = "false";
					}
					
					if(Valor == ''){
						Valor = 0;
					}
					
					if(Valor < 0){
						Valor = formata_float(Arredonda(Valor,2),2).replace(/\./,',');
						
						dados_neg	+=	"<table>";
						dados_neg	+=	"	<tr>";
						dados_neg	+=	"		<td class='find'>&nbsp;</td>";
						dados_neg	+=	"		<td class='descCampo'>Contas R.</td>";
						dados_neg	+=	"		<td class='separador'>&nbsp;</td>";
						dados_neg	+=	"		<td class='descCampo'>Tipo</td>";
						dados_neg	+=	"		<td class='separador'>&nbsp;</td>";
						dados_neg	+=	"		<td class='descCampo'>Código</td>";
						dados_neg	+=	"		<td class='separador'>&nbsp;</td>";
						dados_neg	+=	"		<td class='descCampo'>Descrição</td>";
						dados_neg	+=	"		<td class='separador'>&nbsp;</td>";
						dados_neg	+=	"		<td class='descCampo'>Referência</td>";
						dados_neg	+=	"		<td class='separador'>&nbsp;</td>";
						dados_neg	+=	"		<td class='descCampo'>Valor ("+Moeda+")</td>";
						dados_neg	+=	"		<td class='separador'>&nbsp;</td>";
						dados_neg	+=	"		<td class='descCampo'><B>Reaproveitar Crédito?</B></td>";	
						dados_neg	+=	"	</tr>";
						dados_neg	+=	"	<tr>";
						dados_neg	+=	"		<td class='find'>&nbsp;</td>";
						dados_neg	+=	"		<td class='campo'>";
						dados_neg	+=	"			<input type='text' name='ContaReceber_"+IdLancamentoFinanceiro+"' value='"+IdContaReceber+"' style='width:60px' readonly='readonly' />";
						dados_neg	+=	"		</td>";
						dados_neg	+=	"		<td class='separador'>&nbsp;</td>";
						dados_neg	+=	"		<td class='campo'>";
						dados_neg	+=	"			<select name='Tipo_"+IdLancamentoFinanceiro+"' style='width:50px'  disabled>";
						dados_neg	+=	"				<option value='1'>"+Tipo+"</option>";
						dados_neg	+=	"			</select>";
						dados_neg	+=	"		</td>";
						dados_neg	+=	"		<td class='separador'>&nbsp;</td>";
						dados_neg	+=	"		<td class='campo'>";
						dados_neg	+=	"			<input type='text' name='Codigo_"+IdLancamentoFinanceiro+"' value='"+Codigo+"' style='width:60px' readonly='readonly' />";
						dados_neg	+=	"		</td>";
						dados_neg	+=	"		<td class='separador'>&nbsp;</td>";
						dados_neg	+=	"		<td class='campo'>";
						dados_neg	+=	"			<input type='text' name='Descricao_"+IdLancamentoFinanceiro+"' value='"+Descricao+"' style='width:156px' readonly='readonly' />";
						dados_neg	+=	"		</td>";
						dados_neg	+=	"		<td class='separador'>&nbsp;</td>";
						dados_neg	+=	"		<td class='campo'>";
						dados_neg	+=	"			<input type='text' name='Referencia_"+IdLancamentoFinanceiro+"' value='"+Referencia+"' style='width:146px' readonly='readonly' />";
						dados_neg	+=	"		</td>";
						dados_neg	+=	"		<td class='separador'>&nbsp;</td>";
						dados_neg	+=	"		<td class='campo'>";
						dados_neg	+=	"			<input type='text' name='ValorLancamento_"+IdLancamentoFinanceiro+"' value='"+Valor+"' style='width:84px' readonly='readonly' />";
						dados_neg	+=	"		</td>";
						dados_neg	+=	"		<td class='separador'>&nbsp;</td>";
						dados_neg	+=	"		<td class='campo'>";
						dados_neg	+=	"			<select name='ReaproveitarCredito_"+IdLancamentoFinanceiro+"' style='width:170px'  onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out')\" tabindex='"+(tabindex+i)+"'\">";
						dados_neg	+=	"				<option value='0' selected></option>";
						dados_neg	+=	"			</select>";
						dados_neg	+=	"			<input type='hidden' name='ReaproveitarCreditoDefault_"+IdLancamentoFinanceiro+"' value='"+Voltar+"' />";
						dados_neg	+=	"		</td>";	
						dados_neg	+=	"	</tr>";
						dados_neg	+=	"</table>";
					} else{
						
						Valor	=	formata_float(Arredonda(Valor,2),2).replace(/\./,',');
						
						dados	+=	"<table>";
						dados	+=	"	<tr>";
						dados	+=	"		<td class='find'>&nbsp;</td>";
						dados	+=	"		<td class='descCampo'>Contas R.</td>";
						dados	+=	"		<td class='separador'>&nbsp;</td>";
						dados	+=	"		<td class='descCampo'>Tipo</td>";
						dados	+=	"		<td class='separador'>&nbsp;</td>";
						dados	+=	"		<td class='descCampo'>Código</td>";
						dados	+=	"		<td class='separador'>&nbsp;</td>";
						dados	+=	"		<td class='descCampo'>Descrição</td>";
						dados	+=	"		<td class='separador'>&nbsp;</td>";
						dados	+=	"		<td class='descCampo'>Referência</td>";
						dados	+=	"		<td class='separador'>&nbsp;</td>";
						dados	+=	"		<td class='descCampo'>Valor ("+Moeda+")</td>";
						dados	+=	"		<td class='separador'>&nbsp;</td>";
						
						switch(Tipo){
							case 'CO':
								dados	+=	"	<td class='descCampo'><B>Voltar data base de cálculo?</B></td>";
								break
							case 'EV':
								dados	+=	"	<td class='descCampo'><B>Cancelar Lanç. Financeiro?</B></td>";
								break;
							case 'OS':
								dados	+=	"	<td class='descCampo'><B>Cancelar Lanç. Financeiro?</B></td>";
								break;
							case 'EF':
								dados	+=	"	<td class='descCampo'><B>Cancelar Lanç. Financeiro?</B></td>";
								break;
						}								
						
						dados	+=	"	</tr>";
						dados	+=	"	<tr>";
						dados	+=	"		<td class='find'>&nbsp;</td>";
						dados	+=	"		<td class='campo'>";
						dados	+=	"			<input type='text' name='ContaReceber_"+IdLancamentoFinanceiro+"' value='"+IdContaReceber+"' style='width:60px' readOnly>";
						dados	+=	"		</td>";
						dados	+=	"		<td class='separador'>&nbsp;</td>";
						dados	+=	"		<td class='campo'>";
						dados	+=	"			<select name='Tipo_"+IdLancamentoFinanceiro+"' style='width:50px'>";
						dados	+=	"				<option value='1'>"+Tipo+"</option>";
						dados	+=	"			</select>";
						dados	+=	"		</td>";
						dados	+=	"		<td class='separador'>&nbsp;</td>";
						dados	+=	"		<td class='campo'>";
						dados	+=	"			<input type='text' name='Codigo_"+IdLancamentoFinanceiro+"' value='"+Codigo+"' style='width:60px' readOnly>";
						dados	+=	"		</td>";
						dados	+=	"		<td class='separador'>&nbsp;</td>";
						dados	+=	"		<td class='campo'>";
						dados	+=	"			<input type='text' name='Descricao_"+IdLancamentoFinanceiro+"' value='"+Descricao+"' style='width:156px' readOnly>";
						dados	+=	"		</td>";
						dados	+=	"		<td class='separador'>&nbsp;</td>";
						dados	+=	"		<td class='campo'>";
						dados	+=	"			<input type='text' name='Referencia_"+IdLancamentoFinanceiro+"' value='"+Referencia+"' style='width:146px' readOnly>";
						dados	+=	"		</td>";
						dados	+=	"		<td class='separador'>&nbsp;</td>";
						dados	+=	"		<td class='campo'>";
						dados	+=	"			<input type='text' name='ValorLancamento_"+IdLancamentoFinanceiro+"' value='"+Valor+"' style='width:84px' readOnly>";
						dados	+=	"		</td>";
						dados	+=	"		<td class='separador'>&nbsp;</td>";
						dados	+=	"		<td class='campo'>";
						
						switch(Tipo){
							case 'CO':
								eval("var InputLancamentoFinanceiroAutomatico = document.formulario.IdLancamentoFinanceiroAutomatico_"+IdLancamentoFinanceiro+";");
								
								if(InputLancamentoFinanceiroAutomatico == undefined) {
									InputLancamentoFinanceiroAutomatico = document.createElement("input");
									InputLancamentoFinanceiroAutomatico.setAttribute("type", "hidden");
									InputLancamentoFinanceiroAutomatico.setAttribute("name", "IdLancamentoFinanceiroAutomatico_"+IdLancamentoFinanceiro);
									InputLancamentoFinanceiroAutomatico.setAttribute("value", IdLancamentoFinanceiroAutomatico);
									document.formulario.appendChild(InputLancamentoFinanceiroAutomatico);
								} else {
									InputLancamentoFinanceiroAutomatico.value = IdLancamentoFinanceiroAutomatico;
								}
								
								dados	+=	"		<select name='VoltarDataBase_"+IdLancamentoFinanceiro+"' style='width:170px'  onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out')\" tabindex='"+(tabindex+i)+"' onChange=\"verificaMudarDataBase("+Codigo+","+IdLancamentoFinanceiro+",this.value);\">";
								dados	+=	"			<option value='0' selected></option>";
								break;
							case 'EV':		
								dados	+=	"		<select name='CancelarContaEventual_"+IdLancamentoFinanceiro+"' style='width:170px'  onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out')\" tabindex='"+(tabindex+i)+"'>";
								dados	+=	"			<option value='0' selected></option>";
								break;
							case 'OS':		
								dados	+=	"		<select name='CancelarOrdemServico_"+IdLancamentoFinanceiro+"' style='width:170px'  onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out')\" tabindex='"+(tabindex+i)+"'>";
								dados	+=	"			<option value='0' selected></option>";
								break;
							case 'EF':
								dados	+=	"		<select name='CancelarEncargoFinanceiro_"+IdLancamentoFinanceiro+"' style='width:170px'  onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out')\" tabindex='"+(tabindex+i)+"'>";
								break;
						}							
						
						dados	+=	"			</select>";
						dados	+=	"			<input type='hidden' name='VoltarDataBaseDefault_"+IdLancamentoFinanceiro+"' value='"+Voltar+"' />";
						dados	+=	"		</td>";	
						dados	+=	"	</tr>";
						dados	+=	"</table>";
					}
				}
				
				document.getElementById('cpVoltarDataBase').innerHTML = dados_neg+dados;
			}
			
			var posInicial = 0, posFinal = 0, campo = "";
			
			for(i = 0; i < document.formulario.length; i++){
				if(document.formulario[i].name != undefined){
					if(document.formulario[i].name.substring(0,16) == 'ValorLancamento_'){
						if(posInicial == 0){
							posInicial = i;
						}
						
						posFinal = i;
					}
				}
			}
			
			var IdCampo	= 0, aux = 0;
			
			if(posFinal > 0){
				var posFinalTemp = 0;
				
				for(i = posInicial; i <= posFinal; i += 8){
				
					var temp = document.formulario[i+1].name.split('_');
					IdCampo	= document.formulario[i-3].value;
					
					switch(temp[0]){
						case 'CancelarContaEventual':
							IdGrupoParametroSistema = 67;
							break;
						case 'CancelarEncargoFinanceiro':
							IdGrupoParametroSistema = 67;
							break;
						case 'VoltarDataBase':
							IdGrupoParametroSistema = 39;
							
							if(aux != trim(IdCampo)){
								document.formulario[i+1].disabled = false;
								aux	=	IdCampo;
							} else{
								document.formulario[i-6].disabled = true;
								document.formulario[i+1].disabled = false;
							}
							
							if(document.formulario[i+2].value == 'false'){
								document.formulario[i+1].disabled = true;
							}
							break;
						case 'ReaproveitarCredito':
							IdGrupoParametroSistema = 110;
							break;
						case 'CancelarOrdemServico':
							IdGrupoParametroSistema = 67;
							break;
					}
					
					addSelect(document.formulario[i+1],IdGrupoParametroSistema,'',true);
					
					if(document.formulario[i-4].options[document.formulario[i-4].selectedIndex].text == "CO"){
						posFinalTemp = i;
					}		
				}
				
				verificar_select_lancamentos_data_base(posInicial,posFinalTemp);
			}*/
		//});
	} 
	function verificar_select_lancamentos_data_base(posInicial,posFinal){
		if(ContExecucao > 0){
			setTimeout(function () { verificar_select_lancamentos_data_base(posInicial,posFinal); },100);
		} else{
			var selecionar = 2;
			var IdLancamentoFinanceiroAutomaticoTemp = "";
			
			if(document.formulario[posFinal+1].disabled){
				for(var i = 0; i < document.formulario.length; i++){
					if(document.formulario[i].name.substring(0, 33) == "IdLancamentoFinanceiroAutomatico_" && document.formulario[i].value != ""){
						if(IdLancamentoFinanceiroAutomaticoTemp != "")
							IdLancamentoFinanceiroAutomaticoTemp += ",";
						
						IdLancamentoFinanceiroAutomaticoTemp += document.formulario[i].value;
					}
				}
				
				selecionar = 1;
				
				if(IdLancamentoFinanceiroAutomaticoTemp != "") {
					if((new RegExp(document.formulario[posFinal+1].name.replace(/([^_]*_)/i, "(,")+",$)")).test(","+IdLancamentoFinanceiroAutomaticoTemp+",")){
						selecionar = 0;
					}
				}
			}
			
			for(var i = posFinal; i >= posInicial; i -= 8){
				if(document.formulario[i-4].options[document.formulario[i-4].selectedIndex].text == "CO"){
					if(selecionar == 1){
						document.formulario[i+1][1].selected = selecionar;
					} else{
						var temp = document.formulario[i+1].name.split('_');
						var IdLancamentoFinanceiroTipoContrato = temp[1];
						var LancamentoFinanceiroTipoContrato = ","+IdLancamentoFinanceiroTipoContrato+",";
						temp = document.formulario[i-7].name.split('_');
						
						if(temp[0] == "VoltarDataBase"){
							LancamentoFinanceiroTipoContrato = ","+temp[1]+LancamentoFinanceiroTipoContrato;
						}
						
						if(!(new RegExp(LancamentoFinanceiroTipoContrato)).test(","+document.formulario.LancamentoFinanceiroTipoContrato.value+",") && !(new RegExp("(,"+IdLancamentoFinanceiroTipoContrato+",)")).test(","+IdLancamentoFinanceiroAutomaticoTemp+",")){
							selecionar = 0;
						}
					}
				}
			}
		}
	}
	function addSelect(campo,IdGrupoParametroSistema,IdParametroSistemaTemp,selecionar){
		if(IdParametroSistemaTemp == undefined){
			IdParametroSistemaTemp = "";
		}
		
		if(selecionar == undefined){
			selecionar = false;
		}
	    
		var url = "xml/parametro_sistema.php?IdGrupoParametroSistema="+IdGrupoParametroSistema;
		
		if(!selecionar){
			url += "&IdParametroSistema="+IdParametroSistemaTemp;
		}
		
		ContExecucao++;
		
		call_ajax(url,function (xmlhttp){
			if(xmlhttp.responseText != 'false'){
				var nameNode,nameTextNode,IdParametroSistema,ValorParametroSistema;
				
				for(var ii = 0; ii < xmlhttp.responseXML.getElementsByTagName("IdParametroSistema").length; ii++){
					nameNode = xmlhttp.responseXML.getElementsByTagName("IdParametroSistema")[ii]; 
					nameTextNode = nameNode.childNodes[0];
					IdParametroSistema = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("ValorParametroSistema")[ii]; 
					nameTextNode = nameNode.childNodes[0];
					ValorParametroSistema = nameTextNode.nodeValue;
					
					addOption(campo,ValorParametroSistema,IdParametroSistema);
				}
				if(IdParametroSistemaTemp == '' || selecionar){
					campo.options[Number(IdParametroSistemaTemp)].selected = true;
				} else{
					campo.options[1].selected = true;
				}
			}
			
			ContExecucao--;
		});
	}
	function verificaMudarDataBase(Codigo,IdLancamentoFinanceiro,valor){
		var posInicial = 0, posFinal = 0, campo = "";
		
		for(var i = 0; i < document.formulario.length; i++){
			if(document.formulario[i].name != undefined){
				if(document.formulario[i].name.substring(0,7) == "Codigo_"){
					if(posInicial == 0){
						posInicial = i;
					}
					
					posFinal = i;
				}
			}
		}
		
		var cont = 0, aux = 0;
		
		for(i = posInicial; i <= posFinal; i += 8){
			if(document.formulario[i].value == Codigo){
				cont++;
			}
		}
		
		var posTemp	= 0;
		
		if(cont > 1){
			for(i = posInicial; i <= posFinal; i += 8){
				if(document.formulario[i].value == Codigo){
					var temp = document.formulario[i].name.split('_');
					
					if(temp[1] == IdLancamentoFinanceiro){
						posTemp = i;
						aux		= 1;
						break;
					}
				}
			}
			
			if(aux == 1 && posTemp >= posInicial){
				var verificador = true;
				
				for(i = posTemp; i >= posInicial; i -= 8){
					if(document.formulario[i].value == Codigo){
						var temp2 = document.formulario[i+4].name.split('_');
						
						if(temp2[0] != 'ReaproveitarCredito'){
							var CampoFocus = '';
							
							if(valor == 2){	//nao
								if(aux == 1){
									document.formulario[i+4].disabled = false;
									aux = 0;
								} else{
									document.formulario[i+4].disabled = true;
									document.formulario[i+4][1].selected = true;
								}
							} else if(valor == 1){ //sim
								if(aux == 1){
									if(document.formulario[i-4].name.substring(0,15) == 'VoltarDataBase_'){
										document.formulario[i-4].disabled = false;
										document.formulario[i-4][0].selected = true;
										
									}
									
									aux = 0;
									CampoFocus = document.formulario[i-4];
								} else{
									if(document.formulario[i-4] != undefined){
										if(document.formulario[i-8].value == Codigo){
											document.formulario[i-4].disabled = true;
											document.formulario[i-4][0].selected = true;
										}
									}
								}
							} else{
								if(aux == 1){
									document.formulario[i+4].disabled = false;
									document.formulario[i+4][0].selected = true;
									aux = 0;
								} else{
									document.formulario[i+4].disabled = true;
									document.formulario[i+4][0].selected = true;
								}
							}
							
							if(document.formulario[i-1].options[document.formulario[i-1].selectedIndex].text == "CO"){
								if(verificador){
									var LancamentoFinanceiroTipoContrato = ","+temp2[1]+",";
									//var LancamentoFinanceiroTipoContratoA = ","+temp2[1];
									temp2[0] = document.formulario[i-4].name.split('_');
									
									if(temp2[0][0] == "VoltarDataBase"){
										LancamentoFinanceiroTipoContrato = ","+temp2[0][1]+LancamentoFinanceiroTipoContrato;
									}
									
									verificador = (new RegExp(LancamentoFinanceiroTipoContrato)).test(","+document.formulario.LancamentoFinanceiroTipoContrato.value+",");
									
									if(!verificador){
										for(var ii = i-8; ii >= posInicial; ii -= 8){
											document.formulario[ii+4].disabled = true;
											
											if(document.formulario[ii+4][1] != null){
												document.formulario[ii+4][1].selected = true;
												CampoFocus = document.formulario[ii+12];
											}
										}
									}
								} else{
									document.formulario[i+4].disabled = !verificador;
									
									if(document.formulario[i+4][1]){
										document.formulario[i+4][1].selected = !verificador;
									}
								}
							}
							
							if(CampoFocus != ''){
								CampoFocus.focus();
							}
							
							if(document.formulario[i+4].name.substring(0,15) == 'VoltarDataBase_'){
								var IdLancamentoFinanceiroTemp = document.formulario[i+4].name.replace(/([^_]*)/i, "");
								
								eval("var InputLancamentoFinanceiroAutomatico = document.formulario.IdLancamentoFinanceiroAutomatico"+IdLancamentoFinanceiroTemp+";");
								
								if(InputLancamentoFinanceiroAutomatico != undefined){
									var IdLancamentoFinanceiroAutomatico = InputLancamentoFinanceiroAutomatico.value.split(",");
									
									for(var ii = 0; ii < IdLancamentoFinanceiroAutomatico.length; ii++) {
										eval("document.formulario.VoltarDataBase_"+IdLancamentoFinanceiroAutomatico[ii]+".value = "+document.formulario[i+4].value+";");
									}
								}
							}
						}
					}
				}
			}
		}else{
			for(i = posInicial; i <= posFinal; i += 8){
				if(document.formulario[i].value == Codigo){
					var temp = document.formulario[i].name.split('_');
					
					if(temp[1] == IdLancamentoFinanceiro){
						posTemp = i;
						aux		= 1;
						break;
					}
				}
			}
			
			if(aux == 1 && posTemp >= posInicial){
				for(i = posTemp; i >= posInicial; i -= 8){
					if(document.formulario[i].value == Codigo){
						var temp2 = document.formulario[i+4].name.split('_');
						
						if(temp2[0] != 'ReaproveitarCredito'){
							if(document.formulario[i+4].name.substring(0,15) == 'VoltarDataBase_'){
								var IdLancamentoFinanceiroTemp = document.formulario[i+4].name.replace(/([^_]*)/i, "");
								
								eval("var InputLancamentoFinanceiroAutomatico = document.formulario.IdLancamentoFinanceiroAutomatico"+IdLancamentoFinanceiroTemp+";");
								
								if(InputLancamentoFinanceiroAutomatico != undefined){
									var IdLancamentoFinanceiroAutomatico = InputLancamentoFinanceiroAutomatico.value.split(",");
									
									for(var ii = 0; ii < IdLancamentoFinanceiroAutomatico.length; ii++) {
										eval("document.formulario.VoltarDataBase_"+IdLancamentoFinanceiroAutomatico[ii]+".value = "+document.formulario[i+4].value+";");
									}
								}
							}
						}
					}
				}
			}
		}
	}
	function cancelar_conta_receber(ContaReceber){
		
//		document.formulario.action="files/editar/editar_cancelar_conta_receber.php?IdContaReceber="+ContaReceber;
		
	}
	function desativar_confirmar(Atual,Novo){
		if(Atual.value == Novo.value){
			document.formulario.bt_alterar.disabled = true;
			document.formulario.bt_protocolo.disabled = false;
			
			if(document.formulario.bt_alterar.style.display != "none"){
				abrir_protocolo(true);
				
				document.formulario.bt_protocolo.disabled = true;
			}
		} else{
			document.formulario.bt_alterar.disabled = false;
		}
	}
	function abrir_protocolo(Ocultar){
		if(Ocultar == undefined){
			Ocultar = false;
		}
		
		if(document.getElementById("bl_Protocolo").style.display != "none" || Ocultar){
			document.getElementById("bl_Protocolo").style.display = "none";
			document.formulario.bt_protocolo.value = "Gerar Protocolo";
			document.formulario.ProtocoloAssunto.value = "";
			document.formulario.ProtocoloObservacao.value = "";
		} else{
			document.getElementById("bl_Protocolo").style.display = "block";
			document.formulario.bt_protocolo.value = "Não Gerar Protocolo";
			var ProtocoloAssunto = "Mudança de status "+document.formulario.IdStatus.options[document.formulario.IdStatus.selectedIndex].text;
			var ProtocoloObservacao = document.formulario.Obs.value;
			
			if(Number(document.formulario.IdStatus.value) < 200){
				ProtocoloObservacao = document.formulario.ObsCancelamento.value;
			}
			
			document.formulario.ProtocoloAssunto.value = ProtocoloAssunto;
			document.formulario.ProtocoloObservacao.value = ProtocoloObservacao;
		}
	}