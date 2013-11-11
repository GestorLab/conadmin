
	function inicia(){
		document.formulario.Email.focus();
	}
	function validar(){
		if(document.formulario.Email.value == ""){
			mensagens(1);
			document.formulario.Email.focus();
			return false;
		}else{
			var temp = document.formulario.Email.value.split(';');
			var i = 0;
			while(temp[i]!= '' && temp[i]!= undefined){
				temp[i]	= ignoreSpaces(temp[i]);
				if(isEmail(temp[i]) == false){				
					mensagens(12);
					document.formulario.Email.focus();
					return false;
					break;
				}
				i++;	
			}
		}
		return true;
	}
	function validar_Email(valor){
		if(valor == ''){
			return false;
		}
		var temp = valor.split(';');
		var i = 0;
		while(temp[i]!= '' && temp[i]!= undefined){
			temp[i]	= ignoreSpaces(temp[i]);
			if(isEmail(temp[i]) == false){				
				mensagens(12);
				return false;
				break;
			}
			i++;	
		}
		mensagens(0);
		return true;
	}
