	function validar(){
		if(document.formulario.DescricaoTipoOrdemServico.value==''){
			mensagens(1);
			document.formulario.DescricaoTipoOrdemServico.focus();
			return false;
		}
		if(document.formulario.Cor.value!='' && document.formulario.Cor.value.length < 7){
			mensagens(125);
			document.formulario.Cor.focus();
			return false;
		}
		return true;
	}
	function inicia(){
		document.formulario.IdTipoOrdemServico.focus();
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
	function cadastrar(){
		if(validar()==true){
			document.formulario.submit();
		}
	}
