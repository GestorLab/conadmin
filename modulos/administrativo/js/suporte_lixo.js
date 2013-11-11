	function validar_Email(valor,id){
		var temp = valor.split(';');
		var i = 0;
		while(temp[i]!= '' && temp[i]!= undefined){
			temp[i]	= ignoreSpaces(temp[i]);
			if(isEmail(temp[i]) == false){				
				document.getElementById(id).style.backgroundColor = '#C10000';
				document.getElementById(id).style.color='#FFFFFF';
				mensagens(12);
				return false;
				break;
			}
			i++;	
		}
		document.getElementById(id).style.backgroundColor='#FFFFFF';
		document.getElementById(id).style.color='#000000';
				
		mensagens(0);
		return true;
	}
	function validar(){
		if(document.formulario.Assunto.value == '0'){				
			mensagens(1);
			document.formulario.Assunto.focus();
			return false;
		}
		if(document.formulario.Email.value != ''){
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
		}else{
			if(document.formulario.Email.value == ''){				
				mensagens(1);
				document.formulario.Email.focus();
				return false;
			}
		}
		if(document.formulario.Mensagem.value == ''){				
			mensagens(1);
			document.formulario.Mensagem.focus();
			return false;
		}
		mesganes(0);
		return true;
	}
	function inicia(){
		document.formulario.IdSuporteChamado.focus();
	}
	function cadastrar(){
		if(validar()==true){
			document.formulario.Acao.value = 'inserir'; 
			document.formulario.submit();
		}
	}
