	function validar(){
		if(document.formulario.IdLojaDestino.value==''){
			mensagens(1);
			document.formulario.IdLojaDestino.focus();
			return false;
		}
		return true;
	}
	
	function inicia(){
		document.formulario.IdLojaDestino.focus();
	}
	
