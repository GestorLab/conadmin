	function inicia(){
		document.formulario.DataTermino.focus();
		status_inicial();
	}
	function status_inicial(){
		if(document.formulario.Acao.value == 'Cancelar'){
			document.getElementById('DataTermino').style.color			='#C10000';	
			document.getElementById('DataUltimaCobranca').style.color	='#C10000';	
		}else{
			document.getElementById('DataTermino').style.color			='#000';	
			document.getElementById('DataUltimaCobranca').style.color	='#000';	
		}		
	}
	function validar_Data(id,campo){
		if(campo.value == ''){
			document.getElementById(id).style.backgroundColor='#FFFFFF';
			document.getElementById(id).style.color='#C10000';
			mensagens(0);
			status_inicial();
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
			if(id == 'DataTermino'){
				if(document.formulario.DataUltimaCobranca.value == ''){
					document.formulario.DataUltimaCobranca.value = campo.value;
				}
			}
			status_inicial();
			mensagens(0);
			return true;
		}	
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
				status_inicial();
				mensagens(0);
			}
			return true;
		}
	}
	function validar(){
		if(document.formulario.DataTermino.value != ""){
			if(isData(document.formulario.DataTermino.value) == false){		
				document.formulario.DataTermino.focus();
				mensagens(27);
				return false;
			}
			else{
				if(verificaDataFinal('DataTermino',document.formulario.DataInicio.value,document.formulario.DataTermino.value)== false){
					document.formulario.DataInicio.focus();
					mensagens(39);
					return false;	
				}
			}
		}else{
			if(document.formulario.Acao.value == 'Cancelar'){
				document.formulario.DataTermino.focus();
				mensagens(1);
				return false;
			}
		}
		if(document.formulario.DataTermino.value != "" && document.formulario.DataUltimaCobranca.value == ""){
			document.formulario.DataUltimaCobranca.focus();
			mensagens(1);
			return false;
		}
		if(document.formulario.DataUltimaCobranca.value != ""){
			if(isData(document.formulario.DataUltimaCobranca.value) == false){		
				document.formulario.DataUltimaCobranca.focus();
				mensagens(27);
				return false;
			}
		}else{
			if(document.formulario.Acao.value == 'Cancelar'){	
				document.formulario.DataUltimaCobranca.focus();
				mensagens(1);
				return false;
			}
		}
		if(document.formulario.Obs.value == ''){
			document.formulario.Obs.focus();
			mensagens(1);
			return false;
		}
		
		mensagens(0);
		return true;
	}
