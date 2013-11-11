
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

	function buscar(object, mouse, operacao){
		if(operacao){
			if(document.filtro.filtro_valor.value == ''){
				document.filtro.filtro_campo.value = "IdHistoricoMensagem";
				document.filtro.filtro_valor.value = document.filtro.IdHistoricoMensagem.value
			}
			
			object.href = "listar_reenvio_mensagem.php?filtro_nome="+document.filtro.filtro_nome.value+"&filtro_valor="+document.filtro.filtro_valor.value+"&filtro_campo="+document.filtro.filtro_campo.value+"&filtro_idstatus="+document.filtro.filtro_idstatus.value+"&filtro_tipo="+document.filtro.filtro_tipo.value+"&filtro_limit="+document.filtro.filtro_limit.value;
			
			if(document.filtro.IdPessoa.value == ''){
				object.href	+=	"&IdContaReceber="+document.filtro.IdContaReceber.value;
			}
		}
		
		if(document.filtro.keyCode.value == '' && mouse){
			parent.location.href = object.href;
		} else{
			if(navigator.userAgent.indexOf("Opera") != -1 && document.filtro.keyCode.value == 17){
				parent.location.href = object.href;
			}
			
			document.filtro.keyCode.value = null;
		}
	}
	function code(event){
		if(event != null){
			var key_code;
			
			if(event.keyCode){
				key_code = event.keyCode;
			} else if(event.which){
				key_code = event.which;
			} else{
				key_code = event.charCode;
			}
			
			if(key_code > 15 && key_code < 18){
				document.filtro.keyCode.value = key_code;
			}
		} else{
			document.filtro.keyCode.value = null;
		}
	}
	function cadastrar(){
		if(validar()==true){
			if(document.formulario.ExecutarViaAjax.value != ''){
				var dados_http = {
					method: "POST",
					url: document.formulario.ExecutarViaAjax.value
				};
				
				if((/\?/).test(document.formulario.ExecutarViaAjax.value)){
					dados_http.url += "&";
				} else{
					dados_http.url += "?";
				}
				
				dados_http.url += "Email="+document.formulario.Email.value;
				
				call_ajax(dados_http,function (xmlhttp){
					var temp = xmlhttp.responseText.split(/\n/);
					window.location.href = temp[temp.length-1];
				});
			} else{
				document.formulario.submit();
			}
		}
	}