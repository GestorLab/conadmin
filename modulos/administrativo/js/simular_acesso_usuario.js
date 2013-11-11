	function inicia(){
		status_inicial();		
		document.formulario.Login.focus();
	}
	function validar(){
		if(document.formulario.Login[0].selected == true){
			mensagens(1);
			document.formulario.Login.focus();
			return false;
		}
		return true;
	}
	
