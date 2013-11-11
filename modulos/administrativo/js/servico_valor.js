	function excluir(IdServico,DataInicio,listar) {
		if(IdServico == '' || IdServico == undefined) {
			IdServico = document.formulario.IdServico.value;
		}
		
		if(DataInicio == '' || DataInicio == undefined) {
			DataInicio = document.formulario.DataInicio.value;
		}
		
		if(excluir_registro()){
   			var url = "files/excluir/excluir_servico_valor.php?IdServico="+IdServico+"&DataInicio="+DataInicio;
			
			call_ajax(url, function (xmlhttp) {
				if(document.formulario != undefined) {
					document.formulario.Erro.value = xmlhttp.responseText;
					
					if(listar == 'listar') {
						if(parseInt(xmlhttp.responseText) == 7) {
							if(formatDate(document.formulario.DataInicio.value) == DataInicio) {
								document.formulario.DataInicio.value 			= '';
								document.formulario.DataTermino.value 			= '';
								document.formulario.Valor.value					= '';
								document.formulario.MultaFidelidade.value		= '';
								document.formulario.DescricaoServicoValor.value	= '';
								document.formulario.DataCriacao.value			= '';
								document.formulario.LoginCriacao.value			= '';
								document.formulario.DataAlteracao.value			= '';
								document.formulario.LoginAlteracao.value		= '';
								document.formulario.Acao.value					= 'inserir';
						
								status_inicial();
								verificaAcao();
								document.formulario.DataInicio.focus();
							}
							
							var valor = 0, multa = 0, aux = 0, cont = 0;
							
							for(var i = 1; i < (document.getElementById('tabelaValor').rows.length-1); i++) {
								if(DataInicio == document.getElementById('tabelaValor').rows[i].accessKey) {
									document.getElementById('tabelaValor').deleteRow(i);
									tableMultColor('tabelaValor',document.filtro.corRegRand.value);
									aux = 1
									break;
								}
							}
							
							if(aux == 1) {
								for(i = 1; i < (document.getElementById('tabelaValor').rows.length-1); i++) {
									temp = document.getElementById('tabelaValor').rows[i].cells[3].innerHTML.split(">");
									
									if(temp[1] != undefined) {
										temp1 = temp[1].split("<");
										valor += parseFloat(temp1[0].replace(/,/i,'.'));
									}
									
									temp = document.getElementById('tabelaValor').rows[i].cells[4].innerHTML.split(">");
									
									if(temp[1] != undefined) {
										temp1 = temp[1].split("<");
										multa += parseFloat(temp1[0].replace(/,/i,'.'));
									}
									
									var tam = document.getElementById('tabelaValor').rows.length;
									var tamanho = (tam-tam)+1;
									
									if(i == tamanho) {
										document.getElementById('tabelaValor').rows[i].cells[5].innerHTML = "<img src='../../img/estrutura_sistema/ico_del.gif' alt='Excluir?' onClick=\"excluir("+IdServico+",'"+DataInicio+"','listar')\">";
									}
									cont++;
								}
								
								document.getElementById('tabelaValorValor').innerHTML	= formata_float(Arredonda(valor,2),2).replace(/\./i,',');	
								document.getElementById('tabelaValorMulta').innerHTML	= formata_float(Arredonda(multa,2),2).replace(/\./i,',');	
								document.getElementById('tabelaValorTotal').innerHTML	= "Total: "+cont;
							}
							
							document.formulario.Acao.value = 'inserir';
							url = 'cadastro_servico_valor.php?Erro='+document.formulario.Erro.value+'&IdServico='+IdServico;
							window.location.replace(url);
						} else {
							document.getElementById('tabelahelpText2').style.display = 'block';
							verificaErro2();
						}
					} else {
						if(parseInt(xmlhttp.responseText) == 7) {
							document.formulario.Acao.value = 'inserir';
							url = 'cadastro_servico_valor.php?Erro='+document.formulario.Erro.value+'&IdServico='+IdServico;
							window.location.replace(url);
						} else {
							verificaErro();
						}
					}
				} else {
					var numMsg = parseInt(xmlhttp.responseText);
					mensagens(numMsg);
					
					if(numMsg == 7) {
						var aux = 0, valor = 0, multa = 0;
						
						for(var i = 0; i < document.getElementById('tableListar').rows.length; i++) {
							if(IdServico+"_"+DataInicio == document.getElementById('tableListar').rows[i].accessKey) {
								document.getElementById('tableListar').deleteRow(i);
								tableMultColor('tableListar',document.filtro.corRegRand.value);
								aux=1;
								break;
							}
						}
						
						if(aux == 1) {
							for(i = 1; i < (document.getElementById('tableListar').rows.length-1); i++) {
								temp	= document.getElementById('tableListar').rows[i].cells[4].innerHTML.split(">");
								temp1	= temp[1].split("<");
								valor	+= parseFloat(temp1[0].replace(/,/i,'.'));
								temp	= document.getElementById('tableListar').rows[i].cells[5].innerHTML.split(">");
								temp1	= temp[1].split("<");
								multa	+= parseFloat(temp1[0].replace(/,/i,'.'));
							}
							
							document.getElementById('tableListarValor').innerHTML	= formata_float(Arredonda(valor,2),2).replace(/\./i,',');
							document.getElementById('tableListarMulta').innerHTML	= formata_float(Arredonda(multa,2),2).replace(/\./i,',');
							document.getElementById("tableListarTotal").innerHTML	= "Total: "+(document.getElementById('tableListar').rows.length-2);
						}		
					}
				}
				
			});
		}
	} 
	function validar() {
		if(document.formulario.DataInicio.value == "") {
			mensagens(1);
			document.formulario.DataInicio.focus();
			return false;
		} else {
			if(!isData(document.formulario.DataInicio.value)) {
				document.getElementById('DataInicio').style.backgroundColor = '#C10000';
				document.getElementById('DataInicio').style.color = '#FFF';
				mensagens(27);
				return false;
			} else {
				if(verificaDataFinal('DataInicio',"'"+document.formulario.DataInicio.value+"'","'"+document.formulario.DataTermino.value+"'")){
					mensagens(187);
					document.formulario.DataInicio.focus();
					return false;	
				}
				document.getElementById('DataInicio').style.backgroundColor = '#FFF';
				document.getElementById('DataInicio').style.color = '#C10000';
				mensagens(0);
			}
		}
		
		if(document.formulario.DataTermino.value != "") {
			if(!isData(document.formulario.DataTermino.value)) {
				document.getElementById('DataTermino').style.backgroundColor = '#C10000';
				document.getElementById('DataTermino').style.color = '#FFF';
				mensagens(27);
				document.formulario.DataTermino.focus();
				return false;
			} else {
				if(!verificaDataFinal('DataInicio',document.formulario.DataInicio.value,document.formulario.DataTermino.value)){
					mensagens(39);
					document.formulario.DataInicio.focus();
					return false;	
				}
				
				document.getElementById('DataTermino').style.backgroundColor = '#FFF';
				document.getElementById('DataTermino').style.color = '#000';
				mensagens(0);
			}
		}
		
		if(document.formulario.Valor.value == "") {
			mensagens(1);
			document.formulario.Valor.focus();
			return false;
		}
		
		if(document.formulario.maxQtdMesesFidelidade.value > 0 && (document.formulario.MultaFidelidade.value == "" || document.formulario.MultaFidelidade.value == "0,00" || document.formulario.MultaFidelidade.value == "0")) {
			mensagens(1);
			document.formulario.MultaFidelidade.focus();
			return false;
		}
		
		if(document.formulario.IdContratoTipoVigencia.value == "" || document.formulario.IdContratoTipoVigencia.value == "0") {
			mensagens(1);
			document.formulario.IdContratoTipoVigencia.focus();
			return false;
		}
		
		return true;
	}
	function verificaDataFinal(campo,DataInicio,DataFim) {
		
		if(DataInicio != "" && DataFim != "") {
			var dataI = formatDate(DataInicio);
			var dataF = formatDate(DataFim);
			
			if(dataF < dataI) {
				document.getElementById(campo).style.backgroundColor = '#C10000';
				document.getElementById(campo).style.color='#FFF';
				mensagens(39);
				return false;
			} else {
				colorTemp = document.getElementById(campo).style.backgroundColor;
				document.getElementById(campo).style.backgroundColor = '#FFF';
				document.getElementById(campo).style.color='#C10000';
				mensagens(0);
			}
			
			return true;
		}else{
			return false;
		}
	}
	function verificaDataInicio(DataInicio,DataInicioTemp) {
		if(DataInicio != '' && DataInicioTemp != '') {
			var dataI = formatDate(DataInicio);
			var dataIT = DataInicioTemp;
			
			if(dataI < dataIT) {
				document.getElementById('DataInicio').style.backgroundColor = '#C10000';
				document.getElementById('DataInicio').style.color='#FFF';
				mensagens(187);
				return false;
			} else {
				colorTemp = document.getElementById('DataInicio').style.backgroundColor;
				document.getElementById('DataInicio').style.backgroundColor = '#FFF';
				document.getElementById('DataInicio').style.color='#C10000';
				mensagens(0);
			}
			
			return true;
		}
	}
	function status_inicial() {
		if(document.formulario.MultaFidelidade.value == '') {
			document.formulario.MultaFidelidade.value = '0,00';
		}
	}
	function validar_Data(id,campo) {
		if(campo.value == '') {
			document.getElementById(id).style.backgroundColor = '#FFF';
			
			if(id == 'DataInicio') {
				document.getElementById(id).style.color = '#C10000';
			} else if(id == 'DataTermino') {
				document.getElementById(id).style.color = '#000';	
			}
			
			mensagens(0);
			return false;
		}
		
		if(!isData(campo.value)) {
			document.getElementById(id).style.backgroundColor = '#C10000';
			document.getElementById(id).style.color = '#FFF';
			mensagens(27);
			return false;
		} else {
			document.getElementById(id).style.backgroundColor='#FFF';
			
			if(id == 'DataInicio') {
				document.getElementById(id).style.color = '#C10000';
			} else if(id == 'DataTermino') {
				document.getElementById(id).style.color = '#000';	
			}
			
			mensagens(0);
			return true;
		}	
	}
	function inicia() {
		status_inicial();
		document.formulario.IdServico.focus();
	}
	function listarServicoValor(IdServico,Erro) {
		while(document.getElementById('tabelaValor').rows.length > 2) {
			document.getElementById('tabelaValor').deleteRow(1);
		}
		
		if(IdServico == '') {
			IdServico = 0;
		}
		
	   	var url = "xml/servico_valor.php?IdServico="+IdServico;
		
		call_ajax(url, function (xmlhttp) {
			if(Erro != false){
				document.formulario.Erro.value = 0;
				verificaErro();
			}
			
			if(xmlhttp.responseText == 'false') {
				document.getElementById('tabelaValorValor').innerHTML	= "0,00";	
				document.getElementById('tabelaValorMulta').innerHTML	= "0,00";	
				document.getElementById('tabelaValorTotal').innerHTML	= "Total: 0";
			} else {
				var nameNode, nameTextNode, DadosValor, DataInicio, DataTermino, Valor, DescricaoServicoValor, ValorRep=0, ValorPer=0, ValorPerOut=0, ValorTotal=0,ValorRepasseTerceiro,MultaFidelidade,ValorMulta=0;
				
				for(var i=0; i<xmlhttp.responseXML.getElementsByTagName("DataInicio").length; i++) {
					nameNode = xmlhttp.responseXML.getElementsByTagName("DataInicio")[i]; 
					nameTextNode = nameNode.childNodes[0];
					DataInicio = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("DataTermino")[i]; 
					nameTextNode = nameNode.childNodes[0];
					DataTermino = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoServicoValor")[i]; 
					nameTextNode = nameNode.childNodes[0];
					DescricaoServicoValor = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("Valor")[i]; 
					nameTextNode = nameNode.childNodes[0];
					Valor = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("MultaFidelidade")[i]; 
					nameTextNode = nameNode.childNodes[0];
					MultaFidelidade = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("DataInicioTemp")[i]; 
					nameTextNode = nameNode.childNodes[0];
					DataInicioTemp = nameTextNode.nodeValue;
					
					var tam 	= document.getElementById('tabelaValor').rows.length;
					var linha	= document.getElementById('tabelaValor').insertRow(tam-1);
					var tamanho = (tam-tam)+1;
					
					if(i%2 != 0){
						linha.style.backgroundColor = "#E2E7ED";
					}
					
					if(Valor == '') {
						Valor = 0;
					}
					
					if(MultaFidelidade == '') {
						MultaFidelidade = 0;
					}
					
					ValorTotal		= ValorTotal + parseFloat(Valor);
					ValorMulta		= ValorMulta + parseFloat(MultaFidelidade);
					linha.accessKey	= DataInicio;
					
					var c0 = linha.insertCell(0);	
					var c1 = linha.insertCell(1);	
					var c2 = linha.insertCell(2);	
					var c3 = linha.insertCell(3);
					var c4 = linha.insertCell(4);
					var c5 = linha.insertCell(5);
					var c6 = linha.insertCell(6);
					var c7 = linha.insertCell(7);
					var c8 = linha.insertCell(8);
					
					var linkIni = "<a onClick=\" busca_servico_valor("+IdServico+",false,'"+document.formulario.Local.value+"','"+DataInicio+"');servico_valor_bloquear('"+DataInicio+"','"+DataInicioTemp+"'); \">";
					var linkFim = "</a>";
					
					c0.innerHTML = linkIni + dateFormat(DataInicio) + linkFim;
					c0.style.cursor  = "pointer";
					c0.style.padding =	"0 0 0 5px";
					
					c1.innerHTML = linkIni + dateFormat(DataTermino) + linkFim;
					c1.style.cursor = "pointer";
					
					c2.innerHTML = linkIni + DescricaoServicoValor + linkFim;
					c2.style.cursor = "pointer";
					
					c3.innerHTML = linkIni + formata_float(Valor).replace(/\./i,',') + linkFim;
					c3.style.textAlign = "right";
					c3.style.cursor = "pointer";
					c3.style.padding =	"0 8px 0 0";
					
					c4.innerHTML = linkIni + formata_float(MultaFidelidade).replace(/\./i,',') + linkFim;
					c4.style.textAlign = "right";
					c4.style.cursor = "pointer";
					c4.style.padding =	"0 8px 0 0";
					
					if(i+1 > 1 && tamanho != i+1){
						c5.innerHTML = "<img src='../../img/estrutura_sistema/ico_del_c.gif' alt='Excluir?'>";
					}else{
						c5.innerHTML = "<img src='../../img/estrutura_sistema/ico_del.gif' alt='Excluir?' onClick=\"excluir("+IdServico+",'"+DataInicio+"','listar')\">";
					}
					c5.style.textAlign = "center";
					c5.style.cursor = "pointer";
				}
				
				document.getElementById('tabelaValorTotal').innerHTML = "Total: "+i;
				document.getElementById('tabelaValorValor').innerHTML = formata_float(Arredonda(ValorTotal,2)).replace(/\./i,',');
				document.getElementById('tabelaValorMulta').innerHTML = formata_float(Arredonda(ValorMulta,2)).replace(/\./i,',');
			}	
			
			if(window.janela != undefined) {
				window.janela.close();
			}
		});
	}
	function mensagens2(n,Local) {
		var msg = '', prioridade = '';
		
		if(Local == '' || Local == undefined) {
			Local = '';
		}
		
		if(n == 0) {
			return help(msg,prioridade);
		}
		
		var url = "../../xml/mensagens.xml";
		
		call_ajax(url, function (xmlhttp) {
			var nameNode = xmlhttp.responseXML.getElementsByTagName("msg"+n)[0], nameTextNode;
			
			if(nameNode != null) {
				nameTextNode = nameNode.childNodes[0];
				msg = nameTextNode.nodeValue;
			} else {
				msg = '';
			}
			
			nameNode = xmlhttp.responseXML.getElementsByTagName("pri"+n)[0]; 
			
			if(nameNode != null) {
				nameTextNode = nameNode.childNodes[0];
				prioridade = nameTextNode.nodeValue;
			} else {
				prioridade = '';
			}
			
			return help2(msg,prioridade);
		});
	}
	function verificaErro2() {
		var nerro = parseInt(document.formulario.Erro.value);
		mensagens2(nerro,document.formulario.Local.value);
	}
	function help2(msg,prioridade) {
		if(msg != '') {
			scrollWindow("bottom");
		}
		
		document.getElementById('helpText2').innerHTML = msg;
		document.getElementById('helpText2').style.display = "block";
		
		switch(prioridade) {
			case 'atencao':
				document.getElementById('helpText2').style.color = "#C10000";
				return true;
			default:
				document.getElementById('helpText2').style.color = "#004975";
				return true;
		}
	}
	function alterarContrato() {
		return confirm("ATENCAO!\n\nVocê alterou o valor do serviço. Deseja alterar automaticamente o valor de todos\n os contratos deste serviço?","SIM","NAO");
	}
	function verificaErro() {
		var nerro = parseInt(document.formulario.Erro.value);
		mensagens(nerro,document.formulario.Local.value);
		
		if(nerro == 103 && alterarContrato()) {
		 	window.location.replace("rotinas/editar_valor_servico_contrato.php?IdServico="+document.formulario.IdServico.value+"&DataInicio="+document.formulario.DataInicio.value);
		}
	}
	function calcula_percentual(campo) {
		var ValorInicial				= document.formulario.Valor.value.replace(/\./g,"");
		var ValorRepasseTerceiro		= document.formulario.ValorRepasseTerceiro.value.replace(/\./g,"");
		var PercentualRepasseTerceiro	= document.formulario.PercentualRepasseTerceiro.value.replace(/\./g,"");
		ValorInicial					= ValorInicial.replace(/,/i,".");
		ValorRepasseTerceiro			= ValorRepasseTerceiro.replace(/,/i,".");
		PercentualRepasseTerceiro		= PercentualRepasseTerceiro.replace(/,/i,".");
		
		if(document.formulario.IdTipoServico.value != 2 && document.formulario.IdTipoServico.value != 3) {
			if(ValorInicial == '' || ValorInicial == '0.00') {
				document.formulario.PercentualRepasseTerceiro.value			= '0,00';
				document.formulario.PercentualRepasseTerceiroOutros.value	= '0,00';
			} else {
				if(ValorRepasseTerceiro == "") {
					ValorRepasseTerceiro = 0;
				}
				
				if(PercentualRepasseTerceiro == "") {
					PercentualRepasseTerceiro = 0;
				}
				
				if(campo.name == 'ValorRepasseTerceiro') {
					PercentualRepasseTerceiro = (parseFloat(ValorRepasseTerceiro)*100)/parseFloat(ValorInicial);
					PercentualRepasseTerceiro = formata_float(Arredonda(PercentualRepasseTerceiro,2),2);
					PercentualRepasseTerceiro = PercentualRepasseTerceiro.replace(/\./i,',');
					
					if(ValorRepasseTerceiro == '0') {
						document.formulario.ValorRepasseTerceiro.value = '0,00';
					}
					
					document.formulario.PercentualRepasseTerceiro.value = PercentualRepasseTerceiro;
				} else if(campo.name == 'PercentualRepasseTerceiro') {
					ValorRepasseTerceiro = (parseFloat(PercentualRepasseTerceiro)*parseFloat(ValorInicial))/100;
					ValorRepasseTerceiro = formata_float(Arredonda(ValorRepasseTerceiro,2),2);
					ValorRepasseTerceiro = ValorRepasseTerceiro.replace(/\./i,',');
					document.formulario.ValorRepasseTerceiro.value = ValorRepasseTerceiro;
				}
			}
		}
		
		if(document.formulario.PercentualRepasseTerceiro.value == '') {
			document.formulario.PercentualRepasseTerceiro.value = '0,00';
		}
		
		if(document.formulario.PercentualRepasseTerceiroOutros.value == '') {
			document.formulario.PercentualRepasseTerceiroOutros.value = '0,00';
		}
	}
	function lista_terceiro(IdServico) {
		while(document.getElementById("tabelaTerceiro").rows.length > 2) {
			document.getElementById("tabelaTerceiro").deleteRow(1);
		}
		
		document.getElementById("totaltabelaTerceiro").innerHTML = "Total: 0";
		
		if(IdServico == undefined) {
			IdServico = '';
		}
		
		call_ajax("xml/servico_terceiro.php?IdServico="+IdServico, function (xmlhttp) {
			if(xmlhttp.responseText != "false") {
				var nameNode, nameTextNode;
				
				for(var i = 0; i < xmlhttp.responseXML.getElementsByTagName("IdTerceiro").length; i++) {
					nameNode = xmlhttp.responseXML.getElementsByTagName("IdTerceiro")[i]; 
					nameTextNode = nameNode.childNodes[0];
					var IdTerceiro = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("ValorRepasseTerceiro")[i]; 
					nameTextNode = nameNode.childNodes[0];
					var ValorRepasseTerceiro = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("PercentualRepasseTerceiro")[i]; 
					nameTextNode = nameNode.childNodes[0];
					var PercentualRepasseTerceiro = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("PercentualRepasseTerceiroOutros")[i]; 
					nameTextNode = nameNode.childNodes[0];
					var PercentualRepasseTerceiroOutros = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("IdServico")[i]; 
					nameTextNode = nameNode.childNodes[0];
					var IdServico = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("LoginCriacao")[i]; 
					nameTextNode = nameNode.childNodes[0];
					var LoginCriacao = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("DataCriacao")[i]; 
					nameTextNode = nameNode.childNodes[0];
					var DataCriacao = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("LoginAlteracao")[i]; 
					nameTextNode = nameNode.childNodes[0];
					var LoginAlteracao = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("DataAlteracao")[i]; 
					nameTextNode = nameNode.childNodes[0];
					var DataAlteracao = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("Nome")[i]; 
					nameTextNode = nameNode.childNodes[0];
					var Nome = nameTextNode.nodeValue;
					
					var tam = document.getElementById("tabelaTerceiro").rows.length;
					var linha = document.getElementById("tabelaTerceiro").insertRow(tam - 1);
					linha.accessKey = IdTerceiro;
					
					if((tam % 2) != 0) {
						linha.style.backgroundColor = "#E2E7ED";
					}
					
					var c0 = linha.insertCell(0);
					var c1 = linha.insertCell(1);
					var c2 = linha.insertCell(2);
					var c3 = linha.insertCell(3);
					var c4 = linha.insertCell(4);
					var c5 = linha.insertCell(5);
					
					c0.innerHTML = IdTerceiro;
					c0.style.paddingLeft = "5px";
					c1.innerHTML = Nome;
					c2.className = "valor";
					c2.innerHTML = formata_float(Arredonda(ValorRepasseTerceiro,2),2).replace(/\./i, ",");
					c3.className = "valor";
					c3.innerHTML = formata_float(Arredonda(PercentualRepasseTerceiro,2),2).replace(/\./i, ",");
					c4.className = "valor";
					c4.innerHTML = formata_float(Arredonda(PercentualRepasseTerceiroOutros,2),2).replace(/\./i, ",");
					c5.innerHTML = "<img src='../../img/estrutura_sistema/ico_del_c.gif' alt='Excluir?' />";
				}
				
				document.getElementById("totaltabelaTerceiro").innerHTML = "Total: "+(tam - 1);
			}
		});
	}