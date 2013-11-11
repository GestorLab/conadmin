
	function validar(){
		if(document.formulario.IdServico.value==''){
			mensagens(1);
			document.formulario.IdServico.focus();
			return false;
		}
		return true;
	}
	function inicia(){
		document.formulario.IdServico.focus();
	}
	function verificaAcao(){
		if(document.formulario.IdServico.value == ''){
			document.formulario.bt_alterar.disabled 	= true;
		}else{
			document.formulario.bt_alterar.disabled 	= false;
		}	
	}
