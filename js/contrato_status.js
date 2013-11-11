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
	function inicia(){
		document.formulario.IdStatus.focus();
	}
	function verifica_status(valor){
		switch(valor){
			case '1':
				document.getElementById('titStatus').style.display		=	'block';
				document.getElementById('cpDataBloqueio').innerHTML		=	"Data Término Cont.";;
				document.getElementById('titOS').style.display			=	'none';
				document.getElementById('sepVarStatus').style.display	=	'block';
				document.getElementById('cp2VarStatus').style.display	=	'block';
				document.getElementById('imgVarStatus').style.display	=	'block';
				document.getElementById('cpOS').style.display			=	'none';
				document.getElementById('sep2DataFinal').style.display	=	'block';
				document.getElementById('sepDataFinal').style.display	=	'block';
				document.getElementById('titDataFinal').style.display	=	'block';
				document.getElementById('findDataFinal').style.display	=	'block';
				document.getElementById('findDataFinal').style.display	=	'block';
				document.getElementById('imgDataFinal').style.display	=	'block';
				document.getElementById('cpDataFinal').style.display	=	'block';
				document.getElementById('DataFinal').innerHTML			=	"Data Última Cob.";
				document.getElementById('DataFinal').style.color		=	'#C10000';
				document.getElementById('tableObs').style.display		=	'block';
				document.getElementById('cpObs').innerHTML				=	"Observações do Cancelamento";
				break;
			case '201':
				document.getElementById('titStatus').style.display		=	'block';
				document.getElementById('cpDataBloqueio').innerHTML		=	"Data Bloqueio";
				document.getElementById('sepVarStatus').style.display	=	'block';
				document.getElementById('cp2VarStatus').style.display	=	'block';
				document.getElementById('imgVarStatus').style.display	=	'block';
				document.getElementById('cpOS').style.display			=	'none';
				document.getElementById('titOS').style.display			=	'none';
				document.getElementById('sep2DataFinal').style.display	=	'none';
				document.getElementById('sepDataFinal').style.display	=	'none';
				document.getElementById('titDataFinal').style.display	=	'none';
				document.getElementById('findDataFinal').style.display	=	'none';
				document.getElementById('findDataFinal').style.display	=	'none';
				document.getElementById('imgDataFinal').style.display	=	'none';
				document.getElementById('cpDataFinal').style.display	=	'none';
				document.getElementById('tableObs').style.display		=	'none';
				break;
			case '305':
				document.getElementById('titStatus').style.display		=	'none';
				document.getElementById('titOS').style.display			=	'block';
				document.getElementById('sepVarStatus').style.display	=	'none';
				document.getElementById('cp2VarStatus').style.display	=	'none';
				document.getElementById('imgVarStatus').style.display	=	'none';
				document.getElementById('cpOS').style.display			=	'block';
				document.getElementById('sep2DataFinal').style.display	=	'none';
				document.getElementById('sepDataFinal').style.display	=	'none';
				document.getElementById('titDataFinal').style.display	=	'none';
				document.getElementById('findDataFinal').style.display	=	'none';
				document.getElementById('findDataFinal').style.display	=	'none';
				document.getElementById('imgDataFinal').style.display	=	'none';
				document.getElementById('cpDataFinal').style.display	=	'none';
				document.getElementById('tableObs').style.display		=	'none';
				break;
			case '306':
				document.getElementById('titStatus').style.display		=	'block';
				document.getElementById('cpDataBloqueio').innerHTML		=	"Data Inicial";
				document.getElementById('titOS').style.display			=	'none';
				document.getElementById('sepVarStatus').style.display	=	'block';
				document.getElementById('cp2VarStatus').style.display	=	'block';
				document.getElementById('imgVarStatus').style.display	=	'block';
				document.getElementById('cpOS').style.display			=	'none';
				document.getElementById('sep2DataFinal').style.display	=	'block';
				document.getElementById('sepDataFinal').style.display	=	'block';
				document.getElementById('titDataFinal').style.display	=	'block';
				document.getElementById('findDataFinal').style.display	=	'block';
				document.getElementById('findDataFinal').style.display	=	'block';
				document.getElementById('imgDataFinal').style.display	=	'block';
				document.getElementById('cpDataFinal').style.display	=	'block';
				document.getElementById('DataFinal').style.color		=	'#000';
				document.getElementById('tableObs').style.display		=	'none';
				break;
			default:
				document.getElementById('titStatus').style.display		=	'none';
				document.getElementById('sepVarStatus').style.display	=	'none';
				document.getElementById('cp2VarStatus').style.display	=	'none';
				document.getElementById('imgVarStatus').style.display	=	'none';
				document.getElementById('cpOS').style.display			=	'none';
				document.getElementById('titOS').style.display			=	'none';
				document.getElementById('sep2DataFinal').style.display	=	'none';
				document.getElementById('sepDataFinal').style.display	=	'none';
				document.getElementById('titDataFinal').style.display	=	'none';
				document.getElementById('findDataFinal').style.display	=	'none';
				document.getElementById('findDataFinal').style.display	=	'none';
				document.getElementById('imgDataFinal').style.display	=	'none';
				document.getElementById('cpDataFinal').style.display	=	'none';
				document.getElementById('tableObs').style.display		=	'none';
		}
	}
	function validar_Data(id,campo){
		if(campo.value == ''){
			document.getElementById(id).style.backgroundColor='#FFFFFF';
			document.getElementById(id).style.color='#C10000';
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
			document.getElementById(id).style.color='#C10000';
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
					if(document.formulario.VarStatus.value == ''){
						document.formulario.VarStatus.focus();
						mensagens(1);
						return false;
					}else{
						if(isData(document.formulario.VarStatus.value) == false){
							document.formulario.VarStatus.focus();
							mensagens(27);
							return false;	
						}
					}
					if(document.formulario.DataFinal.value == ''){
						document.formulario.DataFinal.focus();
						mensagens(1);
						return false;
					}else{
						if(isData(document.formulario.DataFinal.value) == false){
							document.formulario.DataFinal.focus();
							mensagens(27);
							return false;	
						}
					}
					if(document.formulario.Obs.value == ''){
						document.formulario.Obs.focus();
						mensagens(1);
						return false;
					}
					break;
				case '201':
					if(document.formulario.VarStatus.value == ''){
						document.formulario.VarStatus.focus();
						mensagens(1);
						return false;
					}else{
						if(isData(document.formulario.VarStatus.value) == false){
							document.formulario.VarStatus.focus();
							mensagens(27);
							return false;	
						}
					}
					break;
				case '306':
					if(document.formulario.VarStatus.value == ''){
						document.formulario.VarStatus.focus();
						mensagens(1);
						return false;
					}else{
						if(isData(document.formulario.VarStatus.value) == false){
							document.formulario.VarStatus.focus();
							mensagens(27);
							return false;	
						}
					}
					if(document.formulario.DataFinal.value == ''){
						document.formulario.DataFinal.focus();
						mensagens(1);
						return false;
					}else{
						if(isData(document.formulario.DataFinal.value) == false){
							document.formulario.DataFinal.focus();
							mensagens(27);
							return false;	
						}else{
							if(verificaDataFinal('DataBloqueio',document.formulario.VarStatus.value,document.formulario.DataFinal.value)== false){
								document.formulario.VarStatus.focus();
								mensagens(39);
								return false;	
							}
							document.getElementById('DataFinal').style.backgroundColor='#FFFFFF';
							document.getElementById('DataFinal').style.color='#000000';
							mensagens(0);
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
	}
	function voltar(){
		window.location.replace("cadastro_contrato.php?IdContrato="+document.formulario.IdContrato.value);
	}
