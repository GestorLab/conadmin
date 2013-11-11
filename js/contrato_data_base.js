	function validar_Data(id,campo){
		if(campo.value == ''){
			document.getElementById(id).style.backgroundColor='#FFFFFF';
			if(id=='DataInicio' || id=='DataPrimeiraCobranca'){
				document.getElementById(id).style.color='#C10000';
			}else{
				document.getElementById(id).style.color='#000';
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
			if(id=='DataInicio' || id=='DataPrimeiraCobranca'){
				document.getElementById(id).style.color='#C10000';
			}else{
				document.getElementById(id).style.color='#000';
			}
			if(id == 'DataTermino'){
				if(document.formulario.DataUltimaCobranca.value == ''){
					document.formulario.DataUltimaCobranca.value = campo.value;
				}
			}
			mensagens(0);
			return true;
		}	
	}
	function verificaAcao(){
		if(document.formulario != undefined){
			if(document.formulario.Acao.value=='inserir'){			
				document.formulario.bt_alterar.disabled 	= true;
			}
			if(document.formulario.Acao.value=='alterar'){			
				document.formulario.bt_alterar.disabled 	= false;
			}
		}	
	}
	function validar(){
		if(document.formulario.DataInicio.value==""){
			mensagens(1);
			document.formulario.DataInicio.focus();
			return false;
		}else{
			if(isData(document.formulario.DataInicio.value) == false){		
				document.getElementById('DataInicio').style.backgroundColor = '#C10000';
				document.getElementById('DataInicio').style.color='#FFFFFF';
				mensagens(27);
				return false;
			}
			else{
				document.getElementById('DataInicio').style.backgroundColor='#FFFFFF';
				document.getElementById('DataInicio').style.color='#C10000';
				mensagens(0);
			}
		}
		if(document.formulario.DataPrimeiraCobranca.value==""){
			mensagens(1);
			document.formulario.DataPrimeiraCobranca.focus();
			return false;
		}else{
			if(isData(document.formulario.DataPrimeiraCobranca.value) == false){		
				document.getElementById('DataPrimeiraCobranca').style.backgroundColor = '#C10000';
				document.getElementById('DataPrimeiraCobranca').style.color='#FFFFFF';
				mensagens(27);
				return false;
			}
			else{
				document.getElementById('DataPrimeiraCobranca').style.backgroundColor='#FFFFFF';
				document.getElementById('DataPrimeiraCobranca').style.color='#C10000';
				mensagens(0);
			}
		}
		if(document.formulario.DataBaseCalculo.value!=""){
			if(isData(document.formulario.DataBaseCalculo.value) == false){		
				document.formulario.DataBaseCalculo.focus();
				mensagens(27);
				return false;
			}
		}
		if(document.formulario.DataTermino.value != ""){
			if(isData(document.formulario.DataTermino.value) == false){		
				document.getElementById('DataTermino').style.backgroundColor = '#C10000';
				document.getElementById('DataTermino').style.color='#FFFFFF';
				document.formulario.DataTermino.focus();
				mensagens(27);
				return false;
			}
			else{
				if(verificaDataFinal('DataInicio',document.formulario.DataInicio.value,document.formulario.DataTermino.value)== false){
					document.formulario.DataInicio.focus();
					mensagens(39);
					return false;	
				}
				document.getElementById('DataInicio').style.backgroundColor='#FFFFFF';
				document.getElementById('DataInicio').style.color='#C10000';
				mensagens(0);
			}
		}
		if(document.formulario.DataUltimaCobranca.value != ""){
			if(isData(document.formulario.DataUltimaCobranca.value) == false){		
				document.getElementById('DataUltimaCobranca').style.backgroundColor = '#C10000';
				document.getElementById('DataUltimaCobranca').style.color='#FFFFFF';
				mensagens(27);
				return false;
			}
			else{
				document.getElementById('DataUltimaCobranca').style.backgroundColor='#FFFFFF';
				document.getElementById('DataUltimaCobranca').style.color='#000000';
				mensagens(0);
			}
		}
		mensagens(0);
		return true;
	}
	function inicia(){
		document.formulario.IdContrato.focus();
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
