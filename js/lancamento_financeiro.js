	function excluir(IdLancamentoFinanceiro,IdStatus){
		if(IdLancamentoFinanceiro!='' && IdStatus == 2){
			window.location.replace('cadastro_cancelar_lancamento_financeiro.php?IdLancamentoFinanceiro='+IdLancamentoFinanceiro);
		}
	}
	function validar(){
		if(document.formulario.IdLancamentoFinanceiro.value==''){
			mensagens(1);
			document.formulario.IdLancamentoFinanceiro.focus();
			return false;
		}
		return true;
	}
	function inicia(){
		document.formulario.IdLancamentoFinanceiro.focus();
	}
	function verificaAcao(){
		if(document.formulario.IdStatus.value == 2){ //Aguardando Cobranca
			document.formulario.bt_cancelar.disabled = false;
		}else{
			document.formulario.bt_cancelar.disabled = true;
		}
	}
