	function validar(){
		document.formulario.Lancamentos.value	=	"";
		var posIni=0,posFim=0;
		for(i=0;i<document.formulario.length;i++){
			if(document.formulario[i].name.substr(0,22) == 'IdLancamentoFinanceiro'){
				if(posIni==0){
					posIni = i;
				}
				posFim	= i;	
			}
		}
		for(i=posIni;i<=posFim;i++){
			if(document.formulario[i].checked	==	true){
				if(document.formulario.Lancamentos.value != ''){
					document.formulario.Lancamentos.value	+=	',';
				}
				document.formulario.Lancamentos.value	+=	document.formulario[i].value;
			}	
		}
		if(document.formulario.Lancamentos.value == ''){
			mensagens(99);
			return false;
		}
		if(document.formulario.ObsLancamentoFinanceiro.value == ''){
			document.formulario.ObsLancamentoFinanceiro.focus();
			mensagens(1);
			return false;
		}
		return true;
	}
	function selecionar(campo){
		if(campo.checked == true){
			checar	=	true;	
		}else{
			checar	=	false;	
		}
		var posIni=0,posFim=0;
		for(i=0;i<document.formulario.length;i++){
			if(document.formulario[i].name.substr(0,22) == 'IdLancamentoFinanceiro'){
				if(posIni==0){
					posIni = i;
				}
				posFim	= i;	
			}
		}	
		for(i=posIni;i<=posFim;i++){
			if(document.formulario[i].disabled == false){
				document.formulario[i].checked	=	checar;
			}	
		}
	}
	function checarTodos(campo){
		var posIni=0,posFim=0,contador=0,total=0;
		for(i=0;i<document.formulario.length;i++){
			if(document.formulario[i].name.substr(0,22) == 'IdLancamentoFinanceiro'){
				if(posIni==0){
					posIni = i;
				}
				posFim	= i;	
			}
		}
		for(i=posIni;i<=posFim;i++){
			if(document.formulario[i].disabled == false){
				if(document.formulario[i].checked == true){
					contador++;
				}
				total++;
			}	
		}	
		if(contador != total){
			document.formulario.todos.checked = false;
		}else{
			document.formulario.todos.checked = true;
		}
	}
	function habilitarCheck(valor){
		var posIni=0,posFim=0,contador=0,total=0;
		for(i=0;i<document.formulario.length;i++){
			if(document.formulario[i].name.substr(0,22) == 'IdLancamentoFinanceiro'){
				if(posIni==0){
					posIni = i;
				}
				posFim	= i;	
			}
		}
		for(i=posIni;i<=posFim;i++){
			temp	=	document.formulario[i].name.split("_");
			if(temp[1] == valor){
				document.formulario[i].disabled =	false;
				break;
			}	
		}	
	}
