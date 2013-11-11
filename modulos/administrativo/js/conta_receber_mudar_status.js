	function selecionaTodos(campo){
		for(var i = 0; i < document.formulario.length; i++){
			if(document.formulario[i].name.substr(0,7) == 'Status_'){
				if(campo.checked == true){
					if(document.formulario[i].checked == false){
						document.formulario[i].checked = true;	
					}
				} else{
					if(document.formulario[i].checked == true){
						document.formulario[i].checked = false;	
					}
				}
			}
		}
	}
	
	function validarCheck(){
		document.formulario.Contratos.value = "";
		
		for(var i = 0; i < document.formulario.length; i++){
			if(document.formulario[i].name.substr(0,7) == 'Status_'){
				if(document.formulario[i].checked == true){
					if(document.formulario.Contratos.value != ""){
						document.formulario.Contratos.value += ',';
					}
					
					document.formulario.Contratos.value += document.formulario[i].value;
				}
			}
		}
		
		if(document.formulario.Contratos.value == ""){
			mensagens(113);
			return false;
		}
		
		return true;
	}
	
	function iniciaListar(){
		if(document.formulario.Contratos.value != ""){
			contrato = document.formulario.Contratos.value.split(',');
			var i = 0;
			
			while(i < contrato.length){
				for(var ii = 0; ii < document.formulario.length; ii++){
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