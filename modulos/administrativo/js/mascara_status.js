	function validar() {
	
		var TaxaMudancaStatus = document.formulario.TaxaMudancaStatus.value.split(",");
		var PercentualDesconto = document.formulario.PercentualDesconto.value.split(",");
	
		if(document.formulario.IdServico.value == '') {
			mensagens(1);
			document.formulario.IdServico.focus();
			return false;
		}
		
		if(document.formulario.IdStatus.value == '') {
			mensagens(1);
			document.formulario.IdStatus.focus();
			return false;
		}
		
		if(document.formulario.PercentualDesconto.value == '') {
			mensagens(1);
			document.formulario.PercentualDesconto.focus();
			return false;
		}else{
			if(PercentualDesconto[0] == 0 && PercentualDesconto[1] == 0){
				if(TaxaMudancaStatus[0] > 0 || TaxaMudancaStatus[1] > 0){
					return true;
				}else{
					mensagens(92);
					document.formulario.PercentualDesconto.focus();
					return false;
				}
			}
		}
		
		if(document.formulario.TaxaMudancaStatus.value == '') {
			mensagens(1);
			document.formulario.TaxaMudancaStatus.focus();
			return false;
		}else{
			if(TaxaMudancaStatus[0] == 0 && TaxaMudancaStatus[1] == 0){
				if(PercentualDesconto[0] > 0 || PercentualDesconto[1] > 0){
					return true;
				}else{
					mensagens(92);
					document.formulario.TaxaMudancaStatus.focus();
					return false;
				}
			}
		}
		
		return true;
	}
	
	function inicia() {
		document.formulario.IdServico.focus();
	}
	
	function listar_mascara_status(IdServico) {
		if(IdServico == undefined || IdServico=='') {
			IdServico = 0;
		}
		
	    while(document.getElementById("tabelaMascaraStatus").rows.length > 2) {
			document.getElementById("tabelaMascaraStatus").deleteRow(1);
		}
		
	   	var url = "./xml/mascara_status.php?IdServico=" + IdServico;
		
		call_ajax(url, function (xmlhttp) {
			if(xmlhttp.responseText == 'false') {
				document.getElementById('tabelaStatusTotal').innerHTML		= "Total: 0";						
				document.getElementById('tabelaStatusDesconto').innerHTML	= "0,00";						
				document.getElementById('tabelaStatusTaxa').innerHTML		= "0,00";
			} else {
				var tam, linha, c0, c1, c2, c3, TotalDesc = 0, TotalTaxa = 0, nameNode, nameTextNode, IdStatus, Status, PercentualDesconto, TaxaMudancaStatus;
				
				for(var i = 0; i < xmlhttp.responseXML.getElementsByTagName("IdStatus").length; i++) {
					nameNode = xmlhttp.responseXML.getElementsByTagName("IdStatus")[i]; 
					nameTextNode = nameNode.childNodes[0];
					IdStatus = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("Status")[i]; 
					nameTextNode = nameNode.childNodes[0];
					Status = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("PercentualDesconto")[i]; 
					nameTextNode = nameNode.childNodes[0];
					PercentualDesconto = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("TaxaMudancaStatus")[i]; 
					nameTextNode = nameNode.childNodes[0];
					TaxaMudancaStatus = nameTextNode.nodeValue;
					
					TotalDesc = TotalDesc + parseFloat(PercentualDesconto);
					TotalTaxa = TotalTaxa + parseFloat(TaxaMudancaStatus);
					tam = document.getElementById('tabelaMascaraStatus').rows.length;
					linha = document.getElementById('tabelaMascaraStatus').insertRow(tam - 1);
					
					if(tam % 2 != 0) {
						linha.style.backgroundColor = "#E2E7ED";
					}
					
					linha.accessKey = IdStatus; 
					
					c0	= linha.insertCell(0);
					c1	= linha.insertCell(1);
					c2	= linha.insertCell(2);
					c3	= linha.insertCell(3);
					
					PercentualDesconto	= formata_float(Arredonda(PercentualDesconto,2),2).replace(/\./, ',');
					TaxaMudancaStatus	= formata_float(Arredonda(TaxaMudancaStatus,2),2).replace(/\./, ',');
					
					var linkIni = "<a href='./cadastro_mascara_status.php?IdServico=" + IdServico + "&IdStatus=" + IdStatus + "'>";
					var linkFim = "</a>";
					
					c0.innerHTML = linkIni + Status + linkFim;
					c0.style.cursor  = "pointer";
					c0.style.padding =	"0 0 0 5px";
					
					c1.innerHTML = linkIni + "<dec>" + PercentualDesconto + "</dec>" + linkFim + "&nbsp;&nbsp;";
					c1.style.cursor = "pointer";
					c1.style.textAlign = "right";
					
					c2.innerHTML = linkIni + "<sta>" + TaxaMudancaStatus + "</sta>" + linkFim + "&nbsp;&nbsp;" ;
					c2.style.cursor = "pointer";
					c2.style.textAlign = "right";
					
					c3.style.cursor = "pointer";
					c3.innerHTML    = "<img src='../../img/estrutura_sistema/ico_del.gif' alt='Excluir?' onClick=\"excluir(" + IdServico + ", " + IdStatus + ", 'listar');\">";
				}
				
				document.getElementById('tabelaStatusTotal').innerHTML	= "Total: " + i;
				document.getElementById('tabelaStatusDesconto').innerHTML	= formata_float(Arredonda(TotalDesc, 2), 2).replace(/\./, ',');
				document.getElementById('tabelaStatusTaxa').innerHTML		= formata_float(Arredonda(TotalTaxa, 2), 2).replace(/\./, ',');
			}
			
			if(window.janela != undefined) {
				window.janela.close();
			}
			
		});
	}
	
	function excluir(IdServico, IdStatus, listar) {
		if(excluir_registro()) {
			if(document.formulario != undefined) {
				if(document.formulario.Acao.value == 'inserir' && listar != 'listar') {
					return false;
				}
			}
    		
   			var url = "./files/excluir/excluir_mascara_status.php?IdServico=" + IdServico + "&IdStatus=" + IdStatus;
			
   			call_ajax(url, function (xmlhttp) {
				var numMsg = parseInt(xmlhttp.responseText);
				
				if(listar == undefined || listar == '') {
					if(document.formulario != undefined) {
						document.formulario.Erro.value = numMsg;
						
						if(document.formulario.Erro.value == 7) {
							document.formulario.Acao.value = "inserir";
							url = "cadastro_mascara_status.php?Erro=" + document.formulario.Erro.value + "&IdServico=" + IdServico;
							
							window.location.replace(url);
						} else {
							verificaErro();
						}
					}
				} else {
					mensagens(numMsg);
					
					if(numMsg == 7) {
						for(var i = 0; i < document.getElementById("tabelaMascaraStatus").rows.length; i++) {
							if(document.getElementById("tabelaMascaraStatus").rows[i].accessKey == IdStatus) {
								var PercentualDesconto = parseFloat(((document.getElementById("tabelaMascaraStatus").rows[i].innerHTML.replace(/[\w\W]*<dec>/, '')).replace(/<\/dec>[\w\W]*/, '')).replace(/\,/, '.'));
								PercentualDesconto = parseFloat(document.getElementById('tabelaStatusDesconto').innerHTML.replace(/\,/, '.')) - PercentualDesconto;
								document.getElementById('tabelaStatusDesconto').innerHTML = formata_float(Arredonda(PercentualDesconto, 2), 2).replace(/\./, ',');
								var TaxaMudancaStatus = parseFloat(((document.getElementById("tabelaMascaraStatus").rows[i].innerHTML.replace(/[\w\W]*<sta>/, '')).replace(/<\/sta>[\w\W]*/, '')).replace(/\,/, '.'));
								TaxaMudancaStatus = parseFloat(document.getElementById('tabelaStatusTaxa').innerHTML.replace(/\,/, '.')) - TaxaMudancaStatus;
								document.getElementById('tabelaStatusTaxa').innerHTML = formata_float(Arredonda(TaxaMudancaStatus, 2), 2).replace(/\./, ',');
								
								document.getElementById('tabelaMascaraStatus').deleteRow(i);
								tableMultColor('tabelaMascaraStatus', document.filtro.corRegRand.value);
								
								document.getElementById('tabelaStatusTotal').innerHTML	= "Total: " + (document.getElementById("tabelaMascaraStatus").rows.length - 2);
								break;
							}
						}
					}
				}
			});
		}
	} 
	
	function verificaAcao() {
		if(document.formulario != undefined) {
			if(document.formulario.Acao.value=='inserir') {	
				if(document.formulario.IdTipoServico.value == 2 || document.formulario.IdTipoServico.value == 3) {
					document.formulario.bt_inserir.disabled = true;
				} else {
					document.formulario.bt_inserir.disabled = false;
				}
				
				document.formulario.bt_alterar.disabled = true;
				document.formulario.bt_excluir.disabled = true;
			}
			
			if(document.formulario.Acao.value=='alterar') {	
				document.formulario.bt_inserir.disabled	= true;
				document.formulario.bt_alterar.disabled = false;
				document.formulario.bt_excluir.disabled = false;
			}
		}	
	}