	function selecionaTodos(campo){
		for (i=0;i<document.formulario.length;i++){
			if(document.formulario[i].name.substr(0,7) == 'Status_'){
				if(campo.checked == true){
					if(document.formulario[i].checked == false){
						document.formulario[i].checked = true;	
					}
				}else{
					if(document.formulario[i].checked == true){
						document.formulario[i].checked = false;	
					}
				}
			}
		}
	}
	function validarCheck(){
		document.formulario.Contratos.value	=	"";
		
		for (i=0;i<document.formulario.length;i++){
			if(document.formulario[i].name.substr(0,7) == 'Status_'){
				if(document.formulario[i].checked == true){
					if(document.formulario.Contratos.value != ""){
						document.formulario.Contratos.value +=	',';
					}
					document.formulario.Contratos.value +=	document.formulario[i].value;
				}
			}
		}
		
		if(document.formulario.Contratos.value==""){
			mensagens(113);
			return false;
		}
		return true;
	}
	function iniciaListar(){
		if(document.formulario.Contratos.value != ""){
			contrato	=	document.formulario.Contratos.value.split(',');
			
			i	=	0;
			while(i< contrato.length){
				for (ii=0;ii<document.formulario.length;ii++){
					if(document.formulario[ii].name.substr(0,7) == 'Status_'){
						if(document.formulario[ii].value == contrato[i]){
							document.formulario[ii].checked = true;	
							break;
						}
					}
				}
				i++;
			}
		}
	}
	function verifica_status(valor){
	
		switch(valor){
			case '1':	//Cancelado
				document.getElementById('tableAtivoTemp').style.display		=	'none';
				document.getElementById('tableCancelado').style.display		=	'block';
				document.getElementById('avisoAtivo').style.display			=	'none';
				document.getElementById('tableObs').style.display			=	'block';
				//document.getElementById('cpObs').innerHTML					=	'Observações do Cancelamento';
				document.getElementById('bt_alterar').value					=	'Confirmar Cancelamento';
				break;
			case '200': //Ativo
				document.getElementById('tableAtivoTemp').style.display		=	'none';
				document.getElementById('tableCancelado').style.display		=	'none';
				document.getElementById('avisoAtivo').style.display			=	'none';
				document.getElementById('tableObs').style.display			=	'block';
				document.getElementById('cpObs').innerHTML					=	'Observações da Ativação';
				break;
			case '201':	//Ativo temporariamente
				document.getElementById('titDataBloqueio').innerHTML		=	'Data Bloqueio';
				document.getElementById('titDataBloqueio').style.color		=	'#C10000';
				document.getElementById('tableAtivoTemp').style.display		=	'block';
				document.getElementById('tableCancelado').style.display		=	'none';
				document.getElementById('avisoAtivo').style.display			=	'none';
				document.getElementById('tableObs').style.display			=	'block';
				document.getElementById('cpObs').innerHTML					=	'Observações da Ativação';
				break;
			case '202': //Ativo sem cobrança
				document.getElementById('tableAtivoTemp').style.display		=	'none';
				document.getElementById('tableCancelado').style.display		=	'none';
				document.getElementById('avisoAtivo').style.display			=	'none';
				document.getElementById('tableObs').style.display			=	'block';
				document.getElementById('cpObs').innerHTML					=	'Observações da Ativação';
				break;
			case '301': //Bloqueiado Técnico
				document.getElementById('tableAtivoTemp').style.display		=	'none';
				document.getElementById('tableCancelado').style.display		=	'none';
				document.getElementById('avisoAtivo').style.display			=	'none';
				document.getElementById('tableObs').style.display			=	'block';
				document.getElementById('cpObs').innerHTML					=	'Observações do Bloqueio';
				break;
			case '302': //Bloqueiado Administraivo
				document.getElementById('tableAtivoTemp').style.display		=	'none';
				document.getElementById('tableCancelado').style.display		=	'none';
				document.getElementById('avisoAtivo').style.display			=	'none';
				document.getElementById('tableObs').style.display			=	'block';
				document.getElementById('cpObs').innerHTML					=	'Observações do Bloqueio';
				break;
			case '303': //Bloqueiado Financeiro
				document.getElementById('tableAtivoTemp').style.display		=	'none';
				document.getElementById('tableCancelado').style.display		=	'none';
				document.getElementById('avisoAtivo').style.display			=	'none';
				document.getElementById('tableObs').style.display			=	'block';
				document.getElementById('cpObs').innerHTML					=	'Observações do Bloqueio';
				break;
			case '304': //Bloqueiado Aguardando Ativação
				document.getElementById('tableAtivoTemp').style.display		=	'none';
				document.getElementById('tableCancelado').style.display		=	'none';
				document.getElementById('avisoAtivo').style.display			=	'none';
				document.getElementById('tableObs').style.display			=	'block';
				document.getElementById('cpObs').innerHTML					=	'Observações do Bloqueio';
				break;
			case '305': //Bloqueado (Em Andamento)
				document.getElementById('tableAtivoTemp').style.display		=	'none';
				document.getElementById('tableCancelado').style.display		=	'none';
				document.getElementById('avisoAtivo').style.display			=	'none';
				document.getElementById('tableObs').style.display			=	'block';
				document.getElementById('cpObs').innerHTML					=	'Observações do Bloqueio';
				break;
			case '306': //Bloqueado (Em Agendado)
				document.getElementById('titDataBloqueio').innerHTML		=	'Data Agendamento';
				document.getElementById('titDataBloqueio').style.color		=	'#C10000';
				document.getElementById('tableAtivoTemp').style.display		=	'block';
				document.getElementById('tableCancelado').style.display		=	'none';
				document.getElementById('avisoAtivo').style.display			=	'none';
				document.getElementById('tableObs').style.display			=	'block';
				document.getElementById('cpObs').innerHTML					=	'Observações do Bloqueio';
				break;
			default:
				document.getElementById('tableAtivoTemp').style.display		=	'none';
				document.getElementById('tableCancelado').style.display		=	'none';
				document.getElementById('avisoAtivo').style.display			=	'none';
				document.getElementById('tableObs').style.display			=	'none';
				document.getElementById('cpObs').innerHTML					=	'Observações';
		}
	}
	function voltar(){
		window.location.replace("listar_contrato_mudar_status.php");
	}
	function verificaAcao(){
		document.formulario.bt_alterar.disabled 	= false;
	}
	function validar_Data(id,campo){
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
		if(document.formulario.IdStatus.value == 0){
			document.formulario.IdStatus.focus();
			mensagens(1);
			return false;
		}else{
			switch(document.formulario.IdStatus.value){
				case '1':
					if(document.formulario.DataUltimaCobrancaStatus.value == ''){
						document.formulario.DataUltimaCobrancaStatus.focus();
						mensagens(1);
						return false;
					}else{
						if(isData(document.formulario.DataUltimaCobrancaStatus.value) == false){
							document.formulario.DataUltimaCobrancaStatus.focus();
							mensagens(27);
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
			}
			if(document.formulario.Obs.value == ''){
				document.formulario.Obs.focus();
				mensagens(1);
				return false;
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
	function inicia(){
		document.formulario.IdStatus.focus();
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
	/*
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
								VarStatus	=	VarStatus.split('#');
							
								document.getElementById('tDataBloqueio').innerHTML			=	'Data Início';
								document.getElementById('tDataBloqueio').style.display		=	'block';
								document.getElementById('cmpDataBloqueio').style.display	=	'block';
								document.getElementById('tDataFinal').style.display			=	'block';
								document.getElementById('cmpDataFinal').style.display		=	'block';
								document.formulario.DataBloqueio.value						=	VarStatus[0];
								document.formulario.DataTermino.value						=	VarStatus[1];
								break;
							default:
								document.getElementById('tDataBloqueio').style.display		=	'none';
								document.getElementById('cmpDataBloqueio').style.display	=	'none';
								document.getElementById('tDataFinal').style.display			=	'none';
								document.getElementById('cmpDataFinal').style.display		=	'none';
								document.formulario.DataBloqueio.value						=	"";
								document.formulario.DataTermino.value						=	"";
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
	*/
