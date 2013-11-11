	function validar(){
		if(document.formulario.SenhaAntiga.value=='' || document.formulario.NovaSenha.value==''|| document.formulario.Confirmacao.value=='' ){
			mensagens(1);
			if(document.formulario.SenhaAntiga.value==''){
				document.formulario.SenhaAntiga.focus();
			}else if(document.formulario.NovaSenha.value==''){
				document.formulario.NovaSenha.focus();
			}else if(document.formulario.Confirmacao.value==''){
				document.formulario.Confirmacao.focus();	
			}
			return false;
		}else{
			if(document.formulario.NovaSenha.value != document.formulario.Confirmacao.value){
				mensagens(11);
				document.formulario.SenhaAntiga.focus();
				document.formulario.SenhaAntiga.value = "";
				document.formulario.NovaSenha.value = "";
				document.formulario.Confirmacao.value = "";
				return false;
			}
		}
		return true;
	}
	function inicia(){
		document.formulario.SenhaAntiga.focus();
	}	
	function verificaAcao(){
		if(document.formulario.Acao.value=='alterar'){			
			document.formulario.bt_inserir.disabled 	= true;
			document.formulario.bt_alterar.disabled 	= false;
			document.formulario.bt_excluir.disabled 	= true;
		}	
	}
