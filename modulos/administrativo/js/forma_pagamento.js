	function excluir(IdFormaPagamento){
		if(excluir_registro() == true){
			if(document.formulario != undefined){
				if(document.formulario.Acao.value == 'inserir'){
					return false;
				}
			}
			
   			var url = "files/excluir/excluir_forma_pagamento.php?IdFormaPagamento="+IdFormaPagamento;
			
			call_ajax(url,function (xmlhttp){
				if(document.formulario != undefined){
					document.formulario.Erro.value = xmlhttp.responseText;
					
					if(parseInt(xmlhttp.responseText) == 7){
						document.formulario.Acao.value 	= 'inserir';
						url = 'cadastro_forma_pagamento.php?Erro='+document.formulario.Erro.value;
						window.location.replace(url);
					} else{
						verificaErro();
					}
				} else{
					var numMsg = parseInt(xmlhttp.responseText);
					mensagens(numMsg);
					
					if(numMsg == 7){
						var aux = 0;
						for(var i=0; i<document.getElementById('tableListar').rows.length; i++){
							if(IdFormaPagamento == document.getElementById('tableListar').rows[i].accessKey){
								document.getElementById('tableListar').deleteRow(i);
								tableMultColor('tableListar',document.filtro.corRegRand.value);
								aux=1;
								break;
							}
						}
						
						if(aux=1){
							document.getElementById("tableListarTotal").innerHTML	=	"Total: "+(document.getElementById('tableListar').rows.length-2);
						}							
					}
				}
			});
		}
	} 
	function validar(){
		if(document.formulario.DescricaoFormaPagamento.value==''){
			mensagens(1);
			document.formulario.DescricaoFormaPagamento.focus();
			return false;
		}
		
		for(var i = 1; i <= document.formulario.QTDParcelas.value; i++){
			if(document.getElementById("bl_Parcela_"+i)){
				eval("var CampoPercentualJurosMes = document.formulario.PercentualJurosMes_"+i+";");
				
				if(CampoPercentualJurosMes.value == "" || parseFloat(CampoPercentualJurosMes.value.replace(/,/, ".")) < 0.00){
					mensagens(1);
					CampoPercentualJurosMes.focus();
					return false;
				}
			}
		}
		
		return true;
	}
	function inicia(){
		add_parcela();
		document.formulario.IdFormaPagamento.focus();
	}
	function add_parcela(PercentualJurosMes, ExcluirParcela){
		var Parcela = Number(document.formulario.QTDParcelas.value);
		
		if(PercentualJurosMes == '' || PercentualJurosMes == undefined){
			PercentualJurosMes = '';
		} else{
			PercentualJurosMes = formata_float(Arredonda(PercentualJurosMes, 2), 2).replace(/\./g, ",");
			
			eval("var CampoPercentualJurosMes =  document.formulario.PercentualJurosMes_"+Parcela+";");
			
			if(Number(document.formulario.QTDParcelas.value) == 1 && CampoPercentualJurosMes.value == ""){
				CampoPercentualJurosMes.value = PercentualJurosMes;
				return;
			}
		}
		
		Parcela++;
		document.formulario.QTDParcelas.value = Parcela;
		
		var element = document.getElementById("bl_Parcelas");
		var IdNome = "bl_Parcela_"+Parcela;
		var DivLN = document.createElement("table");
		DivLN.setAttribute("id", IdNome);
		element.appendChild(DivLN, null);
		var table = document.getElementById(IdNome);
		
		var linha = table.insertRow((table.rows.length)-1);
		var c0 = linha.insertCell(0);
		var c1 = linha.insertCell(1);
		var c2 = linha.insertCell(2);
		var c3 = linha.insertCell(3);
		var c4 = linha.insertCell(4);
		
		c0.className = "find";
		c0.innerHTML = "&nbsp;";
		
		c1.className = "campo";
		c1.innerHTML = "<input type='text' name='Parcela_"+Parcela+"' value='"+Parcela+"' style='width:80px' readonly='readonly' />";
		
		c2.className = "separador";
		c2.innerHTML = "&nbsp;";
		
		c3.className = "campo";
		c3.innerHTML = "<input type='text' name='PercentualJurosMes_"+Parcela+"' value='"+PercentualJurosMes+"' style='width:110px'  onkeypress=\"mascara(this,event,'float');\" onkeydown=\"backspace(this,event);\" onFocus=\"Foco(this,'in');\" onBlur=\"Foco(this,'out');\" maxlength='14' tabindex='"+(document.formulario.TabIndex.value++)+"' />";
		
		c4.className = "find";
		c4.style.verticalAlign = "top";
		
		if(ExcluirParcela == '1'){
			c4.innerHTML = "<img onclick=\"del_parcela("+Parcela+");\" alt='Excluir?' title='Excluir?' style='margin-top:6px;' src='../../img/estrutura_sistema/ico_del.gif'>";
		} else {
			c4.innerHTML = "<img alt='Excluir?' title='Excluir?' style='margin-top:6px;' src='../../img/estrutura_sistema/ico_del_c.gif'>";
		}
		
		linha = table.insertRow((table.rows.length)-1);
		c0 = linha.insertCell(0);
		c1 = linha.insertCell(1);
		c2 = linha.insertCell(2);
		c3 = linha.insertCell(3);
		
		c0.className = "find";
		c0.innerHTML = "&nbsp;";
		
		c1.className = "descCampo";
		c1.innerHTML = "Parcela";
		
		c2.className = "separador";
		c2.innerHTML = "&nbsp;";
		
		c3.className = "descCampo";
		c3.innerHTML = "<b>Juros Mensal (%)</b>";
	}
	function del_parcela(Parcela){
		var Element = document.getElementById("bl_Parcelas");
		var CampoQTDParcelas = document.formulario.QTDParcelas;
		
		if(Parcela == '' || Parcela == undefined){
			for(var i = CampoQTDParcelas.value; i > 0; i--){
				del_parcela(i);
			}
		} else{
			if(CampoQTDParcelas.value == 1){
				eval("document.formulario.Parcela_"+Parcela+".value = '"+CampoQTDParcelas.value+"';");
				eval("document.formulario.PercentualJurosMes_"+Parcela+".value = '';");
			} else if(CampoQTDParcelas.value == Parcela){
				Element.removeChild(document.getElementById("bl_Parcela_"+Parcela));
				CampoQTDParcelas.value--;
				document.formulario.TabIndex.value--;
			}
		}
	}