	function validar(){
		if(document.formulario.IdPeriodicidade.value == ''){
			mensagem(55);
			document.formulario.IdPeriodicidade.focus();
			return false;
		}
		
		if(document.formulario.QtdParcela.value == ''){
			mensagem(56);
			document.formulario.QtdParcela.focus();
			return false;
		}
		
		if(document.formulario.TipoContrato.value == ''){
			mensagem(57);
			document.formulario.TipoContrato.focus();
			return false;
		}
		
		if(document.formulario.DiaCobranca.value == '0'){
			mensagem(33);
			document.formulario.DiaCobranca.focus();
			return false;
		}
		
		if(document.formulario.IdLocalCobranca.value == ''){
			mensagem(59);
			document.formulario.IdLocalCobranca.focus();
			return false;
		}
		
		if(document.formulario.MesFechado.value == ''){
			mensagem(58);
			document.formulario.MesFechado.focus();
			return false;
		}
		
		var Titulo = Array();
		
		for(var i = 0, cont = -1; i < document.getElementsByTagName("td").length; i++){
			if(document.getElementsByTagName("td")[i].className == "title" && document.getElementsByTagName("td")[i].innerHTML.replace(/<b>|<\/b>/g,'') == "Mês Fechado"){
				cont++;
			} else if(document.getElementsByTagName("td")[i].className == "title" && cont > -1){
				Titulo[cont] = document.getElementsByTagName("td")[i].innerHTML.replace(/<b>|<\/b>/g,'');
				cont++;
			}
		}
		
		for(var i = cont = 0; i < document.formulario.length; i++){
			if(document.formulario[i].name.substr(0,12) == "Obrigatorio_" && document.formulario[i-1].name.substr(0,6) == "Valor_"){
				if(document.formulario[i].value == 1 && document.formulario[i-1].value == ''){
					alert("Atenção!\r\n"+Titulo[cont]+" - Campo Obrigatório.");
					document.formulario[i-1].focus();
					return false;
				} else if(document.formulario[i-1].type == "password" && document.formulario[i+4].type == "password" && document.formulario[i-1].value != document.formulario[i+4].value){
					alert("Atenção!\r\nA "+Titulo[cont]+" e "+Titulo[cont+1]+" não conferem.");
					document.formulario[i+4].focus();
					return false;
				}
				
				cont++;
			}
		}
		
		return true;
	}