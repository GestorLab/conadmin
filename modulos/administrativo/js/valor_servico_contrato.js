	function habilitar_campo(campo, tudo, automatico){
		if(!tudo){
			if(campo.checked){
				document.formulario.IdContrato.value += '|' + campo.value + '|';
				
				eval("document.formulario.Desconto_" + campo.value + "[0].disabled = false; document.formulario.Desconto_" + campo.value + "[1].disabled = false;");
				
				for(var i = 0; i < document.formulario.length; i++){
					if(document.formulario[i].name.substring(0, 9) == "Contrato_" && !document.formulario[i].checked){
						break;
					}	
				}
				
				if(!automatico){
					if(i == document.formulario.length){
						document.formulario.Contrato.checked = true;
					} else{
						document.formulario.Contrato.checked = false;
					}
				}
			} else{
				document.formulario.IdContrato.value = document.formulario.IdContrato.value.replace('|' + campo.value + '|', '');
				
				eval("document.formulario.Desconto_" + campo.value + "[0].checked = false; document.formulario.Desconto_" + campo.value + "[1].checked = false; document.formulario.Desconto_" + campo.value + "[0].disabled = true; document.formulario.Desconto_" + campo.value + "[1].disabled = true;");
				eval("document.formulario.ValorDesconto_" + campo.value + ".value = '';");
				
				var temp_desc = parseFloat(document.getElementById("valor-desc").innerHTML.replace(/,/, '.')) - parseFloat(document.getElementById("valor-desconto_" + campo.value).innerHTML.replace(/,/, '.'));
				var temp_final = parseFloat(document.getElementById("valor-final").innerHTML.replace(/,/, '.')) - parseFloat(document.getElementById("valor-final_" + campo.value).innerHTML.replace(/,/, '.'));
				document.getElementById("valor-desc").innerHTML = formata_float(Arredonda((temp_desc), 2), 2).replace(/\./, ',');
				document.getElementById("valor-final").innerHTML = formata_float(Arredonda((temp_final), 2), 2).replace(/\./, ',');
				document.getElementById("valor-desconto_" + campo.value).innerHTML = "0,00";
				document.getElementById("valor-final_" + campo.value).innerHTML = "0,00";
				
				if(!automatico){
					if(i == document.formulario.length){
						document.formulario.Contrato.checked = true;
					} else{
						document.formulario.Contrato.checked = false;
					}
				}
			}
		} else{
			for(var i = 0; i < document.formulario.length; i++){
				if(document.formulario[i].name.substring(0, 9) == "Contrato_"){
					document.formulario[i].checked = campo.checked;
					habilitar_campo(document.formulario[i], false, true);
				}	
			}
		}
	}
	
	function calcula(campo, IdContrato, valor_ant, valor_nov, valor_des){
		if(campo.value == 1){ // Manter Desconto
			var _temp_desc = parseFloat(document.getElementById("valor-desc").innerHTML.replace(/,/, '.')) - parseFloat(document.getElementById("valor-desconto_" + IdContrato).innerHTML.replace(/,/, '.'));
			var temp_final = parseFloat(document.getElementById("valor-final").innerHTML.replace(/,/, '.')) - parseFloat(document.getElementById("valor-final_" + IdContrato).innerHTML.replace(/,/, '.'));
			valor_des = parseFloat(valor_des);
			
			eval("if(document.formulario.ValorDesconto_" + IdContrato + ".value != '') { document.getElementById('valor-desc').innerHTML = formata_float(Arredonda((" + (isNaN(_temp_desc) ? 0 : _temp_desc) + "), 2), 2).replace(/\\./, ','); document.getElementById('valor-final').innerHTML = formata_float(Arredonda((" + (isNaN(temp_final) ? 0 : temp_final) + "), 2), 2).replace(/\\./, ','); document.formulario.ValorDesconto_" + IdContrato + ".value = " + valor_des + "; } else { document.formulario.ValorDesconto_" + IdContrato + ".value = " + valor_des + "; }");
			
			valor_nov = parseFloat(valor_nov) - valor_des;
			document.getElementById("valor-desconto_" + IdContrato).innerHTML = formata_float(Arredonda(valor_des, 2), 2).replace(/\./, ',');
			document.getElementById("valor-final_" + IdContrato).innerHTML = formata_float(Arredonda(valor_nov, 2), 2).replace(/\./, ',');
			temp_desc = parseFloat(document.getElementById("valor-desc").innerHTML.replace(/,/, '.'));
			temp_final = parseFloat(document.getElementById("valor-final").innerHTML.replace(/,/, '.'));
			document.getElementById("valor-desc").innerHTML = formata_float(Arredonda((temp_desc + valor_des), 2), 2).replace(/\./, ',');
			document.getElementById("valor-final").innerHTML = formata_float(Arredonda((temp_final + valor_nov), 2), 2).replace(/\./, ',');
		} else if(campo.value == 2){ // Manter Desconto Proporcional
			var _temp_desc = parseFloat(document.getElementById("valor-desc").innerHTML.replace(/,/, '.')) - parseFloat(document.getElementById("valor-desconto_" + IdContrato).innerHTML.replace(/,/, '.'));
			var temp_final = parseFloat(document.getElementById("valor-final").innerHTML.replace(/,/, '.')) - parseFloat(document.getElementById("valor-final_" + IdContrato).innerHTML.replace(/,/, '.'));
			valor_des = ((parseFloat(valor_des) * (((100 * parseFloat(valor_nov)) / parseFloat(valor_ant)) - 100)) / 100) + parseFloat(valor_des);
			
			eval("if(document.formulario.ValorDesconto_" + IdContrato + ".value != '') { document.getElementById('valor-desc').innerHTML = formata_float(Arredonda((" + (isNaN(_temp_desc) ? 0 : _temp_desc) + "), 2), 2).replace(/\\./, ','); document.getElementById('valor-final').innerHTML = formata_float(Arredonda((" + (isNaN(temp_final) ? 0 : temp_final) + "), 2), 2).replace(/\\./, ','); document.formulario.ValorDesconto_" + IdContrato + ".value = " + valor_des + "; } else { document.formulario.ValorDesconto_" + IdContrato + ".value = " + valor_des + "; }");
			
			valor_nov = parseFloat(valor_nov) - valor_des;
			document.getElementById("valor-desconto_" + IdContrato).innerHTML = formata_float(Arredonda(valor_des, 2), 2).replace(/\./, ",");
			document.getElementById("valor-final_" + IdContrato).innerHTML = formata_float(Arredonda(valor_nov, 2), 2).replace(/\./, ',');
			temp_desc = parseFloat(document.getElementById("valor-desc").innerHTML.replace(/,/, '.'));
			temp_final = parseFloat(document.getElementById("valor-final").innerHTML.replace(/,/, '.'));
			document.getElementById("valor-desc").innerHTML = formata_float(Arredonda((temp_desc + valor_des), 2), 2).replace(/\./, ',');
			document.getElementById("valor-final").innerHTML = formata_float(Arredonda((temp_final + valor_nov), 2), 2).replace(/\./, ',');
		}
	}
	
	function submit_formulario(){
		document.formulario.IdContrato.value = document.formulario.IdContrato.value.replace(/\|\|/g, ',');
		document.formulario.IdContrato.value = document.formulario.IdContrato.value.replace(/\|/g, '');
		var enviar = true;
		
		for(var i = 0; i < document.formulario.length; i++){
			if(document.formulario[i].name.substring(0, 9) == "Contrato_" && document.formulario[i].checked){
				eval("if(!document.formulario.Desconto_" + document.formulario[i].name.substring(9) + "[0].checked && !document.formulario.Desconto_" + document.formulario[i].name.substring(9) + "[1].checked){ mensagens(1, 'rotinas'); enviar = false; document.formulario.Desconto_" + document.formulario[i].name.substring(9) + "[0].focus(); }");
				
				if(!enviar){
					break;
				}
			}	
		}
		
		if(enviar){
			document.formulario.submit();
		}
	}