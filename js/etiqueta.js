	function validar(){
		if(document.formulario.Filtro_IdPessoa.value=='' && document.formulario.Filtro_IdContaReceber.value=='' && document.formulario.IdProcessoFinanceiro.value=='' && document.formulario.IdLocalCobranca.value==''){
			mensagens(66);
			return false;
		}
		if(document.formulario.EnderecoCobranca.value == ''){
			document.formulario.EnderecoCobranca.focus();
			mensagens(1);
			return false;
		}
		return true;
	}
	function inicia(){
		document.formulario.IdProcessoFinanceiro.focus();
		status_inicial();
	}

