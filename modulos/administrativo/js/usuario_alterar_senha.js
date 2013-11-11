	function validar(){
			if(document.formulario.SenhaAntiga.value==''){
				document.formulario.SenhaAntiga.focus();
				mensagens(1);
				return false;
			}
			if(document.formulario.NovaSenha.value==''){
				document.formulario.NovaSenha.focus();
				mensagens(1);
				return false;
			}
			if(document.formulario.Confirmacao.value==''){
				document.formulario.Confirmacao.focus();
				mensagens(1);
				return false;
			}
			if(document.formulario.NovaSenha.value.length < 8){
				document.formulario.NovaSenha.focus();
				mensagens(193);
				return false;
			}
			if(document.formulario.NivelSenha.value == 1){
				document.formulario.NovaSenha.focus();
				mensagens(194);
				return false;
			}
			if(document.formulario.NovaSenha.value != document.formulario.Confirmacao.value){
				mensagens(11);
				document.formulario.SenhaAntiga.focus();
				document.formulario.SenhaAntiga.value = "";
				document.formulario.NovaSenha.value = "";
				document.formulario.Confirmacao.value = "";
				document.getElementById("statusSenha").style.display = "none";
				return false;
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