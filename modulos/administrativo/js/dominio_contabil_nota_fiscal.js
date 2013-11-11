	function validar(){
		if(document.formulario.CodigoEmpresa.value == ''){
			document.formulario.CodigoEmpresa.focus();
			mensagens(1);
			return false;
		}		
		if(document.formulario.CodigoAcumulador.value == ''){
			document.formulario.CodigoAcumulador.focus();
			mensagens(1);
			return false;
		}		
		if(document.formulario.IdNotaFiscalLayout.value == ''){
			document.formulario.IdNotaFiscalLayout.focus();
			mensagens(1);
			return false;
		}
		if(document.formulario.MesVencimento.value == ''){
			document.formulario.MesVencimento.focus();
			mensagens(1);
			return false;
		}			
		return true;
	}
	function inicia(){
		document.formulario.IdNotaFiscalLayout.focus();		
	}
	function verifica_mes(nome,campo){
		if(campo.value != ''){
			if(val_Mes(campo.value) == false){
				document.getElementById(nome).style.backgroundColor = '#C10000';
				document.getElementById(nome).style.color='#FFF';
				mensagens(45);			
			}else{
				document.getElementById(nome).style.backgroundColor = '#FFF';
				document.getElementById(nome).style.color='#C10000';
				mensagens(0);		
			}
		}else{	
			document.getElementById(nome).style.backgroundColor = '#FFF';
			document.getElementById(nome).style.color='#C10000';
			mensagens(0);
		}
	}
	
	function cadastrar(Acao){
		if(validar()==true){
			if(Acao != ''){
				document.formulario.Acao.value	=	Acao;
			}			
			document.formulario.submit();
		}
	}
