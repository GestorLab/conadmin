	function inicia(){
		document.formulario.sms.focus();
	}
	function validar(){
		if(document.formulario.sms.value == ""){
			mensagens(1);
			document.formulario.sms.focus();
			return false;
		}
		return true;
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
				
				dados_http.url += "sms="+document.formulario.sms.value;
				
				call_ajax(dados_http,function (xmlhttp){
					var temp = xmlhttp.responseText.split(/\n/);
					window.location.href = temp[temp.length-1];
				});
			} else{
				document.formulario.submit();
			}
		}
	}
	function mascara_fone(Campo, Event){
		if(document.formulario.HabilitarMascaraFone.value == "1"){
			mascara(Campo, Event, "fone");
		}
	}