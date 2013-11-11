	function validar(){
		if(document.formulario.IdServico.value == ''){
			mensagens(1);
			document.formulario.IdServico.focus();
			return false;
		}
		if(document.formulario.IdTipoDesconto.value == ''){
			mensagens(1);
			document.formulario.IdTipoDesconto.focus();
			return false;
		}
		if(document.formulario.IdTipoDesconto.value == '1' || document.formulario.IdTipoDesconto.value == '2'){
			if(document.formulario.ValorDesconto.value == '' || (document.formulario.ValorDesconto.value.replace(/\./g, '')).replace(/,/i, '.') <= 0.00){
				mensagens(1);
				document.formulario.ValorDesconto.focus();
				return false;
			}
		}
		if(document.formulario.IdContratoTipoVigencia.value == ''){
			mensagens(1);
			document.formulario.IdContratoTipoVigencia.focus();
			return false;
		}
		if(document.formulario.VigenciaDefinitiva.value == ''){
			mensagens(1);
			document.formulario.VigenciaDefinitiva.focus();
			return false;
		}
		if(document.formulario.IdTipoDesconto.value == '2' && document.formulario.LimiteDesconto.value.replace(/-/i, '') == ''){
			mensagens(1);
			document.formulario.LimiteDesconto.focus();
			return false;
		}
		if(Number(document.formulario.IdRepasse.value) == 1 && document.formulario.ValorRepasseTerceiro.value == ''){
			mensagens(1);
			document.formulario.ValorRepasseTerceiro.focus();
			return false;
		}
		if(Number(document.formulario.IdRepasse.value) == 2 && document.formulario.PercentualRepasseTerceiro.value == ''){
			mensagens(1);
			document.formulario.PercentualRepasseTerceiro.focus();
			return false;
		}
		
		return true;
	}
	function inicia(){
		document.formulario.IdServico.focus();
		status_inicial();
	}
	function calcula_fator(Desconto){
		if(Desconto == ''){
			Desconto = 0;
			document.formulario.ValorFinal.value = '';	
			document.formulario.Fator.value = 1;
		}else{
			var ValorServico = document.formulario.ValorServico.value;
			ValorServico = ValorServico.replace(/\./g, '');
			ValorServico = parseFloat(ValorServico.replace(/,/i, '.'));
			Desconto = Desconto.replace(/\./g, '');
			Desconto = parseFloat(Desconto.replace(/,/i, '.'));
			var ValorFinal = ValorServico - Desconto;
			var Fator = (ValorServico - Desconto)/ValorServico;
			
			if(isNaN(ValorFinal)){
				ValorFinal = 0.00;
			}
			
			document.formulario.ValorFinal.value = formata_float(Arredonda(ValorFinal,2),2).replace(/\./i, ',');	
			document.formulario.Fator.value = Fator;
		}
	}
	function verifica_limite_desconto(IdTipoDesconto){
		switch(IdTipoDesconto){
			case '1': //Concebido
				document.getElementById('titLimiteDesconto').style.display			=	'none';
				document.getElementById('cpLimiteDesconto').style.display			=	'none';
				document.getElementById('titDesconto').style.display				=	'block';
				document.getElementById('titPercentual').style.display				=	'block';
				document.getElementById('titValorFinal').style.display				=	'block';
				document.getElementById('cpDesconto').style.display					=	'block';
				document.getElementById('cpPercentual').style.display				=	'block';
				document.getElementById('cpValorFinal').style.display				=	'block';
				document.formulario.LimiteDesconto.maxLength						=	10;	
				
				if(document.formulario.LimiteDesconto.value == "0"){
					document.formulario.LimiteDesconto.value = "";
				}
				break;
			case '2': //A conceber
				document.getElementById('titLimiteDesconto').style.display			=	'block';
				document.getElementById('cpLimiteDesconto').style.display			=	'block';
				document.getElementById('titDesconto').style.display				=	'block';
				document.getElementById('titPercentual').style.display				=	'block';
				document.getElementById('titValorFinal').style.display				=	'block';
				document.getElementById('cpDesconto').style.display					=	'block';
				document.getElementById('cpPercentual').style.display				=	'block';
				document.getElementById('cpValorFinal').style.display				=	'block';
				document.formulario.LimiteDesconto.maxLength						=	3;	
				
				if(document.formulario.LimiteDesconto.value == ""){
					document.formulario.LimiteDesconto.value	= 0;
				}else{
					if(document.formulario.LimiteDesconto.value.indexOf("/") != -1){
						document.formulario.LimiteDesconto.value	= 0;
					}
				}
				break;
			default:
				document.getElementById('titLimiteDesconto').style.display		=	'none';
				document.getElementById('cpLimiteDesconto').style.display		=	'none';
				document.getElementById('titDesconto').style.display			=	'none';
				document.getElementById('titPercentual').style.display			=	'none';
				document.getElementById('titValorFinal').style.display			=	'none';
				document.getElementById('cpDesconto').style.display				=	'none';
				document.getElementById('cpPercentual').style.display			=	'none';
				document.getElementById('cpValorFinal').style.display			=	'none';
				document.formulario.LimiteDesconto.maxLength					=	10;	
				document.formulario.ValorDesconto.value							=	'0,00';
				document.formulario.ValorPercentual.value						=	'0,00';
				document.formulario.ValorFinal.value							=	'0,00';
		}
		
	}
	function listar_mascara_vigencia(IdServico,Erro,Local) {
		if(IdServico == undefined || IdServico=='') {
			IdServico = 0;
		}
		
	   	var url = "xml/mascara_vigencia.php?IdServico="+IdServico;
		
		call_ajax(url, function (xmlhttp) {
			if(xmlhttp.responseText == 'false') {
				document.getElementById('tabelaVigenciaTotal').innerHTML			= "Total: 0";
				document.getElementById('tabelaVigenciaValor').innerHTML			= "0,00";
				document.getElementById('tabelaVigenciaValorDesconto').innerHTML	= "0,00";
				document.getElementById('tabelaVigenciaValorFinal').innerHTML		= "0,00";
				document.getElementById('tabelaValorRepasse').innerHTML				= "0,00";
				document.getElementById('tabelaPercentualRepasse').innerHTML		= "0,00";
			} else {
				var TotalValor = 0, TotalDesc = 0, TotalFinal = 0, TotalValorTerc = 0, TotalPercentualTerc = 0;
				
				for(var i=0; i<xmlhttp.responseXML.getElementsByTagName("Mes").length; i++){	
					var nameNode = xmlhttp.responseXML.getElementsByTagName("Mes")[i]; 
					var nameTextNode = nameNode.childNodes[0];
					var Mes = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("Valor")[i]; 
					nameTextNode = nameNode.childNodes[0];
					var Valor = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("Fator")[i]; 
					nameTextNode = nameNode.childNodes[0];
					var Fator = nameTextNode.nodeValue;	
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("ValorRepasseTerceiro")[i]; 
					nameTextNode = nameNode.childNodes[0];
					var ValorRepasseTerceiro = nameTextNode.nodeValue;	
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("PercentualRepasseTerceiro")[i]; 
					nameTextNode = nameNode.childNodes[0];
					var PercentualRepasseTerceiro = nameTextNode.nodeValue;	
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoVigenciaDefinitiva")[i]; 
					nameTextNode = nameNode.childNodes[0];
					var DescricaoVigenciaDefinitiva = nameTextNode.nodeValue;	
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("IdTipoDesconto")[i]; 
					nameTextNode = nameNode.childNodes[0];
					var IdTipoDesconto = nameTextNode.nodeValue;
					
					if(Valor == '') {
						Valor = 0;
					}
					
					if(ValorRepasseTerceiro != '') {
						TotalValorTerc += parseFloat(ValorRepasseTerceiro);
						ValorRepasseTerceiro = formata_float(Arredonda(ValorRepasseTerceiro,2),2).replace(/\./i,',');
					}
					
					if(PercentualRepasseTerceiro != '') {
						TotalPercentualTerc += parseFloat(PercentualRepasseTerceiro);
						PercentualRepasseTerceiro = formata_float(Arredonda(PercentualRepasseTerceiro,2),2).replace(/\./i,',');
					}
					
					var ValorDesconto			=	Valor - (Fator * Valor);
					var ValorFinal				=	Valor - ValorDesconto;
					
					TotalValor	+= parseFloat(Valor);
					TotalDesc	+= parseFloat(ValorDesconto);
					TotalFinal	+= parseFloat(ValorFinal);
					
					var tam 	= document.getElementById('tabelaMascaraVigencia').rows.length;
					var linha	= document.getElementById('tabelaMascaraVigencia').insertRow(tam-1);
					
					if((tam % 2) != 0) {
						linha.style.backgroundColor = "#E2E7ED";
					}
					
					linha.accessKey = Mes; 
					
					var c0	= linha.insertCell(0);	
					var c1	= linha.insertCell(1);	
					var c2	= linha.insertCell(2);	
					var c3	= linha.insertCell(3);
					var c4	= linha.insertCell(4);
					var c5	= linha.insertCell(5);
					var c6	= linha.insertCell(6);
					var c7	= linha.insertCell(7);
					
					Valor			= formata_float(Arredonda(Valor,2),2).replace(/\./i,',');
					ValorDesconto	= formata_float(Arredonda(ValorDesconto,2),2).replace(/\./i,',');
					ValorFinal		= formata_float(Arredonda(ValorFinal,2),2).replace(/\./i,',');
					
					var linkIni = "<a href='cadastro_mascara_vigencia.php?IdServico="+IdServico+"&Mes="+Mes+"'>";
					var linkFim = "</a>";
					
					c0.innerHTML = linkIni + Mes+'º' + linkFim;
					c0.style.cursor  = "pointer";
					c0.style.padding =	"0 0 0 5px";
					
					c1.innerHTML = linkIni + Valor + linkFim + "&nbsp;&nbsp;";
					c1.style.cursor = "pointer";
					c1.style.textAlign = "right";
					
					c2.innerHTML = linkIni + ValorDesconto + linkFim + "&nbsp;&nbsp;" ;
					c2.style.cursor = "pointer";
					c2.style.textAlign = "right";
					
					c3.innerHTML = linkIni + ValorFinal + linkFim + "&nbsp;&nbsp;";
					c3.style.cursor = "pointer";
					c3.style.textAlign = "right";
					
					c4.innerHTML = linkIni + DescricaoVigenciaDefinitiva + linkFim + "&nbsp;&nbsp;";
					c4.style.cursor = "pointer";
					c4.style.textAlign = "right";
					
					c5.innerHTML = linkIni + ValorRepasseTerceiro + linkFim + "&nbsp;&nbsp;";
					c5.style.cursor = "pointer";
					c5.style.textAlign = "right";
					
					c6.innerHTML = linkIni + PercentualRepasseTerceiro + linkFim + "&nbsp;&nbsp;";
					c6.style.cursor = "pointer";
					c6.style.textAlign = "right";
					
					if(document.formulario.CountMes.value > 1 && Mes != document.formulario.CountMes.value) {
						c7.innerHTML = "<img src='../../img/estrutura_sistema/ico_del_c.gif' alt='Excluir?'>";
					} else {
						c7.innerHTML = "<img src='../../img/estrutura_sistema/ico_del.gif' alt='Excluir?' onClick=\"excluir("+IdServico+","+Mes+",'listar')\">";
					}
					
					c7.style.cursor = "pointer";
				}
				
				document.getElementById('tabelaVigenciaTotal').innerHTML			= "Total: "+i;
				document.getElementById('tabelaVigenciaValor').innerHTML			= formata_float(Arredonda(TotalValor,2),2).replace(/\./i,',');
				document.getElementById('tabelaVigenciaValorDesconto').innerHTML	= formata_float(Arredonda(TotalDesc,2),2).replace(/\./i,',');
				document.getElementById('tabelaVigenciaValorFinal').innerHTML		= formata_float(Arredonda(TotalFinal,2),2).replace(/\./i,',');
				document.getElementById('tabelaValorRepasse').innerHTML				= formata_float(Arredonda(TotalValorTerc,2),2).replace(/\./i,',');
				document.getElementById('tabelaPercentualRepasse').innerHTML		= formata_float(Arredonda(TotalPercentualTerc,2),2).replace(/\./i,',');
			}
			
			if(window.janela != undefined) {
				window.janela.close();
			}
		});
	} 	
	function excluir(IdServico,Mes,listar){
		if(excluir_registro()){
			if(document.formulario != undefined && document.formulario.Acao.value == 'inserir' && listar != 'listar') {
				return;
			}
			
    		var temp = new String(Mes);
    		
    		if(temp.split("º") != -1) {
    			temp = temp.split("º");
    			Mes = temp[0];
			}
    		
   			var url = "files/excluir/excluir_mascara_vigencia.php?IdServico="+IdServico+"&Mes="+Mes;
   			
			call_ajax(url, function (xmlhttp) {
				if(listar == undefined || listar == '') {
					if(document.formulario != undefined) {
						document.formulario.Erro.value = parseInt(xmlhttp.responseText);
						
						if(document.formulario.Erro.value == 7) {
							document.formulario.Acao.value = 'inserir';
							url = 'cadastro_mascara_vigencia.php?Erro='+document.formulario.Erro.value+"&IdServico="+IdServico;
							window.location.replace(url);
						} else {
							verificaErro();
						}
					}
				} else {
					var numMsg = parseInt(xmlhttp.responseText);
					mensagens(numMsg);
					
					if(numMsg == 7) {
						var tempMes = new String(document.formulario.Mes.value);
						
						if(tempMes.indexOf("º") != -1) {
							tempMes = tempMes.split("º");
							tempMes = tempMes[0];
						}
						
						if(tempMes == Mes) {
							document.formulario.ValorDesconto.value 			= '';
							document.formulario.IdTipoDesconto.value			= '';
							document.formulario.ValorFinal.value				= '';
							document.formulario.LimiteDesconto.value			= '';
							document.formulario.IdContratoTipoVigencia.value	= '';
							document.formulario.IdRepasse.value					= '';
							document.formulario.DataCriacao.value				= '';
							document.formulario.LoginCriacao.value				= '';
							document.formulario.DataAlteracao.value				= '';
							document.formulario.LoginAlteracao.value			= '';
							document.formulario.Acao.value						= 'inserir';
							
							verificarRepasse()
							verificaAcao();
							document.formulario.IdTipoDesconto.focus();
						}
						
						document.formulario.CountMes.value 	= document.formulario.CountMes.value - 1;
						document.formulario.Mes.value 		= (parseInt(document.formulario.CountMes.value)+1)+'º';	
						
						var valor = 0, desc = 0, total = 0, repasse_val = 0, repasse_pec = 0, aux = 0, cont = 0;
						
						for(var i = 0; i < document.getElementById('tabelaMascaraVigencia').rows.length; i++) {
							if(Mes == document.getElementById('tabelaMascaraVigencia').rows[i].accessKey) {
								document.getElementById('tabelaMascaraVigencia').deleteRow(i);
								tableMultColor('tabelaMascaraVigencia',document.filtro.corRegRand.value);
								aux = 1;
								break;
							}
						}
						
						if(aux == 1) {
							function get_valor(row,cell) {
								row = document.getElementById('tabelaMascaraVigencia').rows[row].cells[cell].innerHTML.split(">");
								row = parseFloat(row[1].split("<")[0].replace(/\./g,'').replace(/,/i,'.'));
								
								if(isNaN(row)) {
									row = 0;
								}
								
								return row;
							}
							
							for(var i = 1; i < (document.getElementById('tabelaMascaraVigencia').rows.length-1); i++) {
								valor += get_valor(i,1);
								desc += get_valor(i,2);
								total += get_valor(i,3);
								repasse_val += get_valor(i,5);
								repasse_pec += get_valor(i,6);
								
								if(i == (document.getElementById('tabelaMascaraVigencia').rows.length-2)) {
									document.getElementById('tabelaMascaraVigencia').rows[i].cells[7].innerHTML = "<img src='../../img/estrutura_sistema/ico_del.gif' alt='Excluir?' onClick=\"excluir("+IdServico+","+(Mes-1)+",'listar')\">";
								}
								
								cont++;
							}
							
							document.getElementById('tabelaVigenciaTotal').innerHTML			= "Total: "+cont;
							document.getElementById('tabelaVigenciaValor').innerHTML			= formata_float(Arredonda(valor,2),2).replace('.',',');	
							document.getElementById('tabelaVigenciaValorDesconto').innerHTML	= formata_float(Arredonda(desc,2),2).replace('.',',');
							document.getElementById('tabelaVigenciaValorFinal').innerHTML		= formata_float(Arredonda(total,2),2).replace('.',',');
							document.getElementById('tabelaValorRepasse').innerHTML				= formata_float(Arredonda(repasse_val,2),2).replace('.',',');
							document.getElementById('tabelaPercentualRepasse').innerHTML		= formata_float(Arredonda(repasse_pec,2),2).replace('.',',');
						}
					}
				}
			});
		}
	} 
	
	function verificaAcao(){
		if(document.formulario != undefined){
			if(document.formulario.Acao.value=='inserir'){			
				if(document.formulario.IdTipoServico.value == 2 || document.formulario.IdTipoServico.value == 3){
					document.formulario.bt_inserir.disabled = true;
				}else{
					document.formulario.bt_inserir.disabled = false;
				}
				document.formulario.bt_alterar.disabled 	= true;
				document.formulario.bt_excluir.disabled 	= true;
			}
			if(document.formulario.Acao.value=='alterar'){			
				document.formulario.bt_inserir.disabled 	= true;
				document.formulario.bt_alterar.disabled 	= false;
				document.formulario.bt_excluir.disabled 	= false;
				
				var tempMes	=	document.formulario.Mes.value;
				if(tempMes.indexOf("º") != -1){
    				tempMes	=	tempMes.split("º");
					tempMes	=	tempMes[0];
				}
				
				if(document.formulario.CountMes.value > 1 && tempMes < document.formulario.CountMes.value){
					document.formulario.bt_excluir.disabled 	= true;
				}
			}
		}	
	}
	function calculaValorFinal(valor, desc, perc, campo){
		if(valor == '' || desc == '' || perc == ''){
			if(valor==''){
				valor = "0,00";
			}
			
			if(desc==''){
				desc  = "0,00";
			}
			
			if(perc==''){
				perc  = "0,00";
			}
		}
		
		var tempValor	= valor.replace(/\./g, '');
		tempValor		= tempValor.replace(/,/i, '.');
		var tempDesc	= desc.replace(/\./g, '');
		tempDesc		= tempDesc.replace(/,/i, '.');
		var valFinal	= tempValor - tempDesc;
		
		if(campo.name == "ValorDesconto"){
			tempPerc = (parseFloat(tempDesc)*100)/parseFloat(tempValor);
			
			if(isNaN(tempPerc)){
				tempPerc = 0.00;
			}
			
			tempPerc = formata_float(Arredonda(tempPerc,2),2);
			tempPerc = tempPerc.replace(/\./i, ',');
			document.formulario.ValorPercentual.value = tempPerc;
		} else if(campo.name == "ValorPercentual"){
			var tempPerc = perc.replace(/\./g, '');
			tempPerc = tempPerc.replace(/,/i, '.');
			tempDesc = (parseFloat(tempPerc)*parseFloat(tempValor))/100;
			
			if(isNaN(tempDesc)){
				tempDesc = 0.00;
			}
			
			valFinal = tempValor - tempDesc;
			tempDesc = formata_float(Arredonda(tempDesc,2),2);
			tempDesc = tempDesc.replace(/\./i, ',');
			document.formulario.ValorDesconto.value	= tempDesc;
		}
		
		if(isNaN(valFinal)){
			valFinal = 0.00;
		}
		
		valFinal = formata_float(Arredonda(valFinal,2),2);
		valFinal = valFinal.replace(/\./i, ',');
		document.formulario.ValorFinal.value = valFinal;
		
		calcula_fator(document.formulario.ValorDesconto.value);
	}
	function limparDesconto(){
		if(parseFloat(document.formulario.ValorServico.value.replace(/\./g, '').replace(/,/i, '.')) < 0.01 || document.formulario.ValorServico.value == ''){
			document.getElementById('titDesconto').style.color = "#000";
			document.formulario.ValorDesconto.value = "0,00";
			document.formulario.ValorDesconto.readOnly = true;
			document.getElementById('titPercentual').style.color = "#000";
			document.formulario.ValorPercentual.value = "0,00";
			document.formulario.ValorPercentual.readOnly = true;
		} else{
			document.getElementById('titDesconto').style.color = "#c10000";
			document.formulario.ValorDesconto.readOnly = false;
			document.getElementById('titPercentual').style.color = "#c10000";
			document.formulario.ValorPercentual.readOnly = false;
		}
	}
	function verificarRepasse(IdRepasse) {
		document.formulario.ValorRepasseTerceiro.value = "0,00";
		document.formulario.PercentualRepasseTerceiro.value = "0,00";
		
		switch(Number(IdRepasse)) {
			case 1:
				document.getElementById("cpValorRepasseMensal").style.display = "block";
				document.formulario.ValorRepasseTerceiro.style.display = "block";
				document.getElementById("cpPercRepasseMensal").style.display = "none";
				document.formulario.PercentualRepasseTerceiro.style.display = "none";
				break;
			case 2:
				document.getElementById("cpValorRepasseMensal").style.display = "none";
				document.formulario.ValorRepasseTerceiro.style.display = "none";
				document.getElementById("cpPercRepasseMensal").style.display = "block";
				document.formulario.PercentualRepasseTerceiro.style.display = "block"
				break;
			default:
				document.getElementById("cpValorRepasseMensal").style.display = "none";
				document.formulario.ValorRepasseTerceiro.style.display = "none";
				document.getElementById("cpPercRepasseMensal").style.display = "none";
				document.formulario.PercentualRepasseTerceiro.style.display = "none";
		}
	}