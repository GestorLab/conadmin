	function excluir(IdLocalRecebimento,IdArquivoRetorno){
		if(IdLocalRecebimento == '' || IdLocalRecebimento == undefined){
			IdLocalRecebimento = document.formulario.IdLocalRecebimento.value;
		}
		
		if(IdArquivoRetorno == '' || IdArquivoRetorno == undefined){
			IdArquivoRetorno = document.formulario.IdArquivoRetorno.value;
		}
		
		if(excluir_registro() == true){
			if(document.formulario != undefined){
				if(document.formulario.IdStatus.value != '1'){
					return false;
				}
			}
			
			var url = "files/excluir/excluir_arquivo_retorno.php?IdLocalRecebimento="+IdLocalRecebimento+"&IdArquivoRetorno="+IdArquivoRetorno;
			
			call_ajax(url, function (xmlhttp){
				if(document.formulario != undefined){
					document.formulario.Erro.value = xmlhttp.responseText;
					
					if(parseInt(xmlhttp.responseText) == 7){
						document.formulario.Acao.value 	= 'inserir';
						url = 'cadastro_arquivo_retorno.php?Erro='+document.formulario.Erro.value;
						window.location.replace(url);
					} else{
						verificaErro();
					}
				} else{
					var numMsg = parseInt(xmlhttp.responseText);
					mensagens(numMsg);
					
					if(numMsg == 7){
						var aux = 0, valor=0;
						
						for(var i=0; i<document.getElementById('tableListar').rows.length; i++){
							if(IdLocalRecebimento+"_"+IdArquivoRetorno == document.getElementById('tableListar').rows[i].accessKey){
								document.getElementById('tableListar').deleteRow(i);
								tableMultColor('tableListar');
								aux=1;
								break;
							}
						}
						
						if(aux=1){
							for(var i=1; i<(document.getElementById('tableListar').rows.length-1); i++){
								temp	=	document.getElementById('tableListar').rows[i].cells[7].innerHTML.split(">");
								temp1	=	temp[1].split("<");
								valor	+=	parseFloat(temp1[0].replace(',','.'));
							}
							
							document.getElementById('tableListarValor').innerHTML	=	formata_float(Arredonda(valor,2),2).replace('.',',');	
							document.getElementById("tableListarTotal").innerHTML	=	"Total: "+(document.getElementById('tableListar').rows.length-2);
						}								
					}
				}
			});
		}
	} 
	function validar(){
		if(document.formulario.IdLocalRecebimento.value==''){
			mensagens(1);
			document.formulario.IdLocalRecebimento.focus();
			return false;
		}
		
		var global = false;
		
		if(document.formulario.Acao.value == 'inserir'){
			if(document.formulario.fakeupload.value == ''){
				mensagens(1);
				document.formulario.fakeupload.focus();
				return false;
			}else{
				if(document.formulario.Arquivo.value == 'true'){
					return confirm("ATENCAO!\n\nArquivo já cadastrado.\nDeseja continuar?","SIM","NAO");
				}
			}
		}
		
		mensagens(0);
		return true;
	}
	function verifica_arquivo_retorno(IdLocalRecebimento,EndArquivo){
		var url = "xml/arquivo_retorno.php?IdLocalRecebimento="+IdLocalRecebimento+"&EndArquivo="+EndArquivo;
		
		call_ajax(url, function (xmlhttp){ 
			if(xmlhttp.responseText != 'false'){
				document.formulario.Arquivo.value = 'true';
			} else{
				document.formulario.Arquivo.value = 'false';
			}
		});
	}
	function inicia(){
		document.formulario.IdLocalRecebimento.focus();
		document.formulario.tempEndArquivo.value	=	"";
		document.formulario.EndArquivo.value		=	"";
		document.formulario.EnderecoArquivo.value	=	"";
	}
	function verificaAcao(){
		if(document.formulario != undefined){
			switch(document.formulario.Acao.value){
				case 'inserir':
					if(document.formulario.IdStatus.value == '1'){
						document.formulario.bt_inserir.disabled 	= true;
						document.formulario.bt_processar.disabled 	= false;
						document.formulario.bt_excluir.disabled 	= false;
					} else{
						document.formulario.bt_inserir.disabled 	= false;
						document.formulario.bt_processar.disabled 	= true;
						document.formulario.bt_excluir.disabled 	= true;
					}
					
					document.formulario.bt_visualizar.disabled 	= true;
					document.formulario.bt_confirmar.disabled 	= true;
					break;
				case 'processar':
					document.formulario.bt_inserir.disabled 	= true;
					document.formulario.bt_processar.disabled	= true;
					document.formulario.bt_confirmar.disabled 	= false;
					document.formulario.bt_excluir.disabled		= true;
					document.formulario.bt_visualizar.disabled 	= true;
					break;
				case 'confirmar':					
					document.formulario.bt_inserir.disabled 	= true;
					document.formulario.bt_processar.disabled	= true;
					document.formulario.bt_confirmar.disabled 	= true;
					document.formulario.bt_excluir.disabled		= true;
					document.formulario.bt_visualizar.disabled 	= false;					
					break;
			}
		}	
	}
	function listarContaReceberRecebimento(IdArquivoRetorno,IdLocalCobranca,Erro){
		if(IdArquivoRetorno == undefined || IdArquivoRetorno==''){
			IdArquivoRetorno = 0;
		}
		
		if(parseInt(document.formulario.QtdRegistro.value) > parseInt(document.formulario.LimiteContaReceber.value)){
			window.location.replace('listar_conta_receber.php?IdArquivoRetorno='+IdArquivoRetorno+'&IdLocalCobranca='+IdLocalCobranca+"&filtro_limit="+document.formulario.QtdRegistro.value);
			return false;
		} else{
			while(document.getElementById('tabelaTitulosRecebidos').rows.length > 2){
				document.getElementById('tabelaTitulosRecebidos').deleteRow(1);
			}
		    
			var url = "xml/conta_receber_arquivo_retorno.php?IdLocalCobranca="+IdLocalCobranca+"&IdArquivoRetorno="+IdArquivoRetorno;
			
			call_ajax(url, function (xmlhttp){
				if(xmlhttp.responseText == 'false'){
					document.getElementById('cp_titulos_recebidos').style.display	=	'none';
					document.getElementById('ValorDescTotal').innerHTML				=	"0,00";
					document.getElementById('cpValorTotal').innerHTML				=	"0,00";
					document.getElementById('ValorMoraMultaTotal').innerHTML		=	"0,00";
					document.getElementById('ValorRecebidoTotal').innerHTML			=	"0,00";
					document.getElementById('ValorOutrasDespesasTotal').innerHTML	=	"0,00";
					document.getElementById('tabelaTotal').innerHTML				=	"Total: 0";
					document.getElementById('cpPosicaoCobranca').style.display		=	'block';
				} else{
					document.getElementById('cp_titulos_recebidos').style.display	=	'block';
					
					var tam, linha, linkInicio, linkFim, c0, c1, c2, c3, c4, c5, c6, c7, c8, c9, c10, c11, c12, c13;
					var IdLoja, IdContaReceberRecebimento,DataRecebimento,DataVencimentoDiaUtil,DataVencimento,ValorDesconto,MD5,IdRecibo,ValorMoraMulta,ValorOutrasDespesas,ValorRecebido,DataLimiteDesconto,MargemMoraMulta;
					var valorDesc = 0, valorMora = 0, valorOutro=0, valorReceb=0, valorParc=0;
					
					if(document.formulario.IdTipoLocalCobranca.value == 3){
						document.getElementById('cpPosicaoCobranca').style.display = 'block';
					}
					
					for(var i = 0; i < xmlhttp.responseXML.getElementsByTagName("IdContaReceberRecebimento").length; i++){
						var nameNode = xmlhttp.responseXML.getElementsByTagName("IdLoja")[i]; 
						var nameTextNode = nameNode.childNodes[0];
						IdLoja = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("IdContaReceberRecebimento")[i]; 
						nameTextNode = nameNode.childNodes[0];
						IdContaReceberRecebimento = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("DataRecebimento")[i]; 
						nameTextNode = nameNode.childNodes[0];
						DataRecebimento = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("IdContaReceber")[i]; 
						nameTextNode = nameNode.childNodes[0];
						IdContaReceber = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("DataVencimentoDiaUtil")[i]; 
						nameTextNode = nameNode.childNodes[0];
						DataVencimentoDiaUtil = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("DataVencimento")[i]; 
						nameTextNode = nameNode.childNodes[0];
						DataVencimento = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("ValorDescontoRecebimento")[i]; 
						nameTextNode = nameNode.childNodes[0];
						ValorDescontoRecebimento = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("ValorContaReceber")[i]; 
						nameTextNode = nameNode.childNodes[0];
						ValorContaReceber = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("ValorRecebido")[i]; 
						nameTextNode = nameNode.childNodes[0];
						ValorRecebido = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("ValorMoraMulta")[i]; 
						nameTextNode = nameNode.childNodes[0];
						ValorMoraMulta = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("ValorOutrasDespesas")[i]; 
						nameTextNode = nameNode.childNodes[0];
						ValorOutrasDespesas = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("IdLoja")[i]; 
						nameTextNode = nameNode.childNodes[0];
						IdLoja = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("MD5")[i]; 
						nameTextNode = nameNode.childNodes[0];
						MD5 = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("IdRecibo")[i]; 
						nameTextNode = nameNode.childNodes[0];
						IdRecibo = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("NumeroDocumento")[i]; 
						nameTextNode = nameNode.childNodes[0];
						NumeroDocumento = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("ValorParcela")[i]; 
						nameTextNode = nameNode.childNodes[0];
						ValorParcela = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("Nome")[i]; 
						nameTextNode = nameNode.childNodes[0];
						Nome = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("IdStatusContaReceber")[i]; 
						nameTextNode = nameNode.childNodes[0];
						IdStatusContaReceber = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("IdStatusRecebimento")[i]; 
						nameTextNode = nameNode.childNodes[0];
						IdStatusRecebimento = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("DataLancamento")[i]; 
						nameTextNode = nameNode.childNodes[0];
						DataLancamento = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("DataLimiteDesconto")[i]; 
						nameTextNode = nameNode.childNodes[0];
						DataLimiteDesconto = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("MargemMoraMulta")[i]; 
						nameTextNode = nameNode.childNodes[0];
						MargemMoraMulta = nameTextNode.nodeValue;
						
						tam 	= document.getElementById('tabelaTitulosRecebidos').rows.length;
						linha	= document.getElementById('tabelaTitulosRecebidos').insertRow(tam-1);
						
						if(tam%2 != 0){
							linha.style.backgroundColor = "#E2E7ED";
						}
						
						linha.accessKey = IdContaReceberRecebimento; 
						
						if(IdRecibo > 0){
							var ValorFinal = parseFloat(ValorParcela) - parseFloat(MargemMoraMulta);
							
							if(isNaN(ValorFinal)){
								ValorFinal = 0.00;
							}
							
							linha.style.backgroundColor = document.formulario.CorRecebido.value;
							
							if((DataRecebimento.replace(/(-)/g, "") > DataVencimentoDiaUtil.replace(/(-)/g, "") && ValorMoraMulta == 0.00) || (DataRecebimento.replace(/(-)/g, "") > DataLimiteDesconto.replace(/(-)/g, "") && ValorFinal < ValorRecebido)){
								linha.style.backgroundColor = "#F9F900";
							}
							
							if(IdStatusContaReceber == 0 || IdStatusRecebimento == 0){
								linha.style.backgroundColor = document.formulario.CorCancelado.value;
							}
						}
						
						
						/*if(IdRecibo > 1){
							linha.style.backgroundColor = document.formulario.CorRecebido.value;
						}
						
						if(ValorContaReceber > ValorRecebido){
							linha.style.backgroundColor = document.formulario.CorRecebidoDesc.value;
						}
						
						if(DataRecebimento.replace(/-/g, "") > DataLimiteDesconto.replace(/-/g, "") || ValorParcela > ValorRecebido){
							linha.style.backgroundColor = "#F9F900";
						}
						
						if(IdStatusContaReceber == 0 || IdStatusRecebimento == 0){
							linha.style.backgroundColor = document.formulario.CorCancelado.value;
						}*/
						
						c0	= linha.insertCell(0);
						c1	= linha.insertCell(1);
						c2	= linha.insertCell(2);
						c3	= linha.insertCell(3);
						c4	= linha.insertCell(4);
						c5	= linha.insertCell(5);
						c6	= linha.insertCell(6);
						c7	= linha.insertCell(7);
						c8	= linha.insertCell(8);
						c9	= linha.insertCell(9);
						c10	= linha.insertCell(10);
						c11	= linha.insertCell(11);
						c12	= linha.insertCell(12);
						c13	= linha.insertCell(13);
						
						valorParc	=	parseFloat(valorParc) + parseFloat(ValorParcela);
						valorReceb	=	parseFloat(valorReceb) + parseFloat(ValorRecebido);
						valorDesc	=	parseFloat(valorDesc) + parseFloat(ValorDescontoRecebimento);
						valorMora	=	parseFloat(valorMora) + parseFloat(ValorMoraMulta);
						valorOutro	=	parseFloat(valorOutro) + parseFloat(ValorOutrasDespesas);
						
						if(IdLoja == document.formulario.IdLoja.value ){
							linkInicio	= "<a href='cadastro_conta_receber.php?IdContaReceber="+IdContaReceber+"' target='_blank'>";
							linkFim		= "</a>"
						} else{
							linkInicio	= '';
							linkFim		= '';
						}
						
						c0.innerHTML = linkInicio+IdLoja+linkFim;
						c0.style.padding =	"0 5px 0 5px";
						
						c1.innerHTML = linkInicio+NumeroDocumento+linkFim;
						c1.style.padding =	"0 5px 0 0";
						
						c2.innerHTML = linkInicio+IdContaReceberRecebimento+linkFim;
						
						c3.innerHTML = linkInicio+Nome.substr(0,20)+linkFim;
						
						c4.innerHTML = linkInicio+dateFormat(DataLancamento)+linkFim;
						
						c5.innerHTML = linkInicio+dateFormat(DataVencimento)+linkFim;
						
						c6.innerHTML = linkInicio+formata_float(Arredonda(ValorParcela,2),2).replace('.',',')+linkFim;
						c6.style.textAlign = "right";
						c6.style.padding =	"0 8px 0 0";
						
						c7.innerHTML = linkInicio+dateFormat(DataRecebimento)+linkFim;
						
						c8.innerHTML = linkInicio+formata_float(Arredonda(ValorDescontoRecebimento,2),2).replace('.',',')+linkFim;
						c8.style.textAlign = "right";
						c8.style.padding =	"0 8px 0 0";
						
						c9.innerHTML = linkInicio+formata_float(Arredonda(ValorMoraMulta,2),2).replace('.',',')+linkFim;
						c9.style.textAlign = "right";
						c9.style.padding =	"0 8px 0 0";
						
						c10.innerHTML = linkInicio+formata_float(Arredonda(ValorRecebido,2),2).replace('.',',')+linkFim;
						c10.style.textAlign = "right";
						c10.style.padding =	"0 8px 0 0";
						
						c11.innerHTML = linkInicio+formata_float(Arredonda(ValorOutrasDespesas,2),2).replace('.',',')+linkFim;
						c11.style.textAlign = "right";
						c11.style.padding =	"0 8px 0 0";
						
						c12.innerHTML = "<a href='recibo.php?Recibo="+MD5+"' target='_blank'>"+IdRecibo+"</a>";
						c12.style.cursor = "pointer";
						
						if(IdStatusContaReceber != 0 && (IdStatusRecebimento != 0 && IdStatusRecebimento != 6)){
							c13.innerHTML = "<img id='ImgLancamento"+IdContaReceber+"' src='../../img/estrutura_sistema/ico_del.gif' alt='Cancelar?' title='Cancelar?' onClick=\"cancelarContaReceberRecebimento("+IdContaReceber+","+IdContaReceberRecebimento+","+IdStatusRecebimento+")\">";
							c13.style.cursor = "pointer";
						} else{
							c13.innerHTML = "<img src='../../img/estrutura_sistema/ico_del_c.gif' alt='Cancelar?' title='Cancelar?'\">";
							c13.style.cursor = "default";
						}
						
						c13.style.textAlign = "center";
					}
					
					document.getElementById('cpValorTotal').innerHTML				=	formata_float(Arredonda(valorParc,2),2).replace('.',',');
					document.getElementById('ValorDescTotal').innerHTML				=	formata_float(Arredonda(valorDesc,2),2).replace('.',',');
					document.getElementById('ValorMoraMultaTotal').innerHTML		=	formata_float(Arredonda(valorMora,2),2).replace('.',',');
					document.getElementById('ValorRecebidoTotal').innerHTML			=	formata_float(Arredonda(valorReceb,2),2).replace('.',',');
					document.getElementById('ValorOutrasDespesasTotal').innerHTML	=	formata_float(Arredonda(valorOutro,2),2).replace('.',',');
					document.getElementById('tabelaTotal').innerHTML				=	"Total: "+i;
				}
			});
		}
	}
	function buscaVisualizar(){
		document.formulario.bt_cancelar_recebimento.disabled = true;
		
		if(document.formulario.Visualizar.value == ''){
			if(document.formulario.IdArquivoRetorno.value != ''){
				listarContaReceberRecebimento(document.formulario.IdArquivoRetorno.value,document.formulario.IdLocalRecebimento.value,false);
				conta_receber_ocorrencia();
				
				document.formulario.Visualizar.value = true;
				document.formulario.bt_visualizar.value = 'Ocultar';
			}
		} else{
			document.formulario.Visualizar.value = '';
			document.formulario.bt_visualizar.value = 'Visualizar';
			
			while(document.getElementById('tabelaTitulosRecebidos').rows.length > 2){
				document.getElementById('tabelaTitulosRecebidos').deleteRow(1);
			}
			
			document.getElementById('cp_titulos_recebidos').style.display	= "none";
			document.getElementById('tabelaTotal').innerHTML				= "Total: 0";
			document.getElementById('cpValorTotal').innerHTML				= "0,00";
			document.getElementById('ValorDescTotal').innerHTML				= "0,00";
			document.getElementById('ValorMoraMultaTotal').innerHTML		= "0,00";
			document.getElementById('ValorRecebidoTotal').innerHTML			= "0,00";
			document.getElementById('ValorOutrasDespesasTotal').innerHTML	= "0,00";
			
			while(document.getElementById("tabelaContaReceberOcorrencia").rows.length > 2){
				document.getElementById("tabelaContaReceberOcorrencia").deleteRow(1);
			}
			
			document.getElementById("cp_conta_receber_ocorrencia").style.display					= "none";
			document.getElementById("tabelaTotalContaReceberOcorrencia").innerHTML					= "Total: 0";
			document.getElementById("tabelaValorTotalContaReceberOcorrencia").innerHTML				= "0,00";
			document.getElementById("tabelaValorDescTotalContaReceberOcorrencia").innerHTML			= "0,00";
			document.getElementById("tabelaValorMoraMultaTotalContaReceberOcorrencia").innerHTML	= "0,00";
			document.getElementById("tabelaValorRecebidoTotalContaReceberOcorrencia").innerHTML		= "0,00";
		}
	}
	function cancelarContaReceberRecebimento(IdContaReceber,IdContaReceberRecebimento,IdStatus){
		if(IdStatus != 0 && IdStatus != 6){
			var url = "cadastro_cancelar_conta_receber_recebimento.php?IdContaReceber="+IdContaReceber+"&IdContaReceberRecebimento="+IdContaReceberRecebimento;
			window.location.replace(url);
		}
	}
	function listaLocalRecebimento(IdArquivoRetorno, IdLocalRecebimento){
		var IdLocalRecebimentoTemp;
		
		if(IdLocalRecebimentoTemp == undefined){
			IdLocalRecebimentoTemp = '';
		}
		
		var url = "xml/arquivo_retorno_local_cobranca.php?IdArquivoRetorno="+IdArquivoRetorno;
		
		call_ajax(url, function (xmlhttp){ 
			if(xmlhttp.responseText != 'false'){
				while(document.formulario.IdLocalRecebimento.options.length > 0){
					document.formulario.IdLocalRecebimento.options[0] = null;
				}
				
				addOption(document.formulario.IdLocalRecebimento," ","");
				
				for(var i = 0; i<xmlhttp.responseXML.getElementsByTagName("IdLocalCobranca").length; i++){
					var nameNode = xmlhttp.responseXML.getElementsByTagName("IdLocalCobranca")[i]; 
					var nameTextNode = nameNode.childNodes[0];
					var IdLocalCobranca = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoLocalCobranca")[i]; 
					nameTextNode = nameNode.childNodes[0];
					var DescricaoLocalCobranca = nameTextNode.nodeValue;
					
					addOption(document.formulario.IdLocalRecebimento,DescricaoLocalCobranca,IdLocalCobranca);
				}
				
				if(IdLocalRecebimentoTemp != ""){
					for(i = 0; i < document.formulario.IdLocalRecebimento.length; i++){
						if(document.formulario.IdLocalRecebimento[i].value == IdLocalRecebimentoTemp){
							document.formulario.IdLocalRecebimento[i].selected = true;
							break;
						}
					}
				} else{
					document.formulario.IdLocalRecebimento[0].selected = true;
				}
				
				if(IdArquivoRetorno != ""){
					busca_arquivo_retorno(IdLocalRecebimento,IdArquivoRetorno, false);
				}
			} else{
				while(document.formulario.IdLocalRecebimento.options.length > 0){
					document.formulario.IdLocalRecebimento.options[0] = null;
				}
				
				addOption(document.formulario.IdLocalRecebimento, " ", "");
			}
		});
	}
	function verificaLocalRecebimento(IdLocalRecebimento){
		if(IdLocalRecebimento == undefined){
			IdLocalRecebimento = '';
		}
		
		document.formulario.Acao.value = 'inserir';
		
		limpaFormArquivo();
		verificaAcao();
		
		if(IdLocalRecebimento == '' || IdLocalRecebimento == 0){
			return true;
		}
		
		document.formulario.bt_inserir.disabled						= false;
		document.formulario.EndArquivo.readOnly						= false;
		document.getElementById('cp_Arquivo').style.color			= '#C10000';
		document.getElementById('EndArquivo').style.display			= 'block';
		document.getElementById('bt_procurar').style.display		= 'block';				
		document.getElementById('EnderecoArquivo').style.display	= 'none';
		document.getElementById('cpPosicaoCobranca').style.display	= 'block';
						
		busca_arquivo_retorno_tipo(IdLocalRecebimento,'',false);
		busca_local_cobranca(IdLocalRecebimento,'',false);
		//buscaStatusLocalRecebimento(IdLocalRecebimento);
	}
	function limpaFormArquivo(){
		document.formulario.IdArquivoRetorno.value 				= '';
		document.formulario.IdArquivoRetorno2.value 			= '';
		document.formulario.EndArquivo.value					= '';
		document.formulario.fakeupload.value					= '';
		document.formulario.EnderecoArquivo.value				= '';
		document.formulario.NomeArquivo.value		 			= '';
		document.formulario.ValorTotal.value		 			= '';
		document.formulario.IdStatus.value		 				= '';
		document.formulario.DataRetorno.value		 			= '';
		document.formulario.QtdRegistro.value					= '';
		document.formulario.FileSize.value						= '';
		document.formulario.DataCriacao.value 					= '';
		document.formulario.LoginCriacao.value 					= '';
		document.formulario.DataProcessamento.value				= '';
		document.formulario.LoginProcessamento.value			= '';
		document.formulario.NumSeqArquivo.value					= '';
		document.formulario.LogRetorno.value					= '';
		document.formulario.IdArquivoRetornoTipo.value			= '';
		document.formulario.DescricaoArquivoRetornoTipo.value	= '';
		document.formulario.IdTipoLocalCobranca.value			= '';
		document.formulario.ValorTotalTaxa.value				= '';
		document.formulario.ValorLiquido.value					= '';
		
		document.formulario.EndArquivo.readOnly						= false;
		document.getElementById('cp_Arquivo').style.color			= '#C10000';
		document.getElementById('EndArquivo').style.display			= 'block';
		document.getElementById('bt_procurar').style.display		= 'block';				
		document.getElementById('EnderecoArquivo').style.display	= 'none';
		document.getElementById('cpPosicaoCobranca').style.display	= 'block';
		
		document.formulario.Visualizar.value = '';
		document.formulario.bt_visualizar.value = 'Visualizar';
		
		while(document.getElementById('tabelaTitulosRecebidos').rows.length > 2){
			document.getElementById('tabelaTitulosRecebidos').deleteRow(1);
		}
		
		document.getElementById('cp_titulos_recebidos').style.display	= "none";
		document.getElementById('ValorDescTotal').innerHTML				= "0,00";	
		document.getElementById('ValorOutrasDespesasTotal').innerHTML	= "0,00";	
		document.getElementById('ValorMoraMultaTotal').innerHTML		= "0,00";	
		document.getElementById('cpValorTotal').innerHTML				= "0,00";	
		document.getElementById('tabelaTotal').innerHTML				= "Total: 0";		
		
		while(document.getElementById("tabelaContaReceberOcorrencia").rows.length > 2){
			document.getElementById("tabelaContaReceberOcorrencia").deleteRow(1);
		}
		
		document.getElementById("cp_conta_receber_ocorrencia").style.display					= "none";
		document.getElementById("tabelaTotalContaReceberOcorrencia").innerHTML					= "Total: 0";
		document.getElementById("tabelaValorTotalContaReceberOcorrencia").innerHTML				= "0,00";
		document.getElementById("tabelaValorDescTotalContaReceberOcorrencia").innerHTML			= "0,00";
		document.getElementById("tabelaValorMoraMultaTotalContaReceberOcorrencia").innerHTML	= "0,00";
		document.getElementById("tabelaValorRecebidoTotalContaReceberOcorrencia").innerHTML		= "0,00";
		
		document.formulario.IdTipoLocalCobranca.value					= '';
	}
	function cadastrar(Acao){
		document.formulario.Acao.value = Acao;
		
		switch(Acao){
			case "processar":
				if(validar() == true){
					verificar_arquivo_retorno(document.formulario.IdLocalRecebimento.value, document.formulario.IdArquivoRetorno.value);
				}
				
				break;
			case "cancelar_recebimento":
				document.formulario.submit();
				break;
			case "confirmar":
				if(document.formulario.Login.value == 'root'){
					if(confirm("ATENCAO!\n\nDeseja enviar o e-mail contendo a confirmação de pagamentos para os clientes?\nDeseja continuar?","SIM","NAO")){
						document.formulario.EnviarEmailConfirmacaoPagamento.value = 1;
					}else{
						document.formulario.EnviarEmailConfirmacaoPagamento.value = 2;	
					}
				}
				if(validar() == true){
					document.formulario.submit();
				}
				break;
			default:
				if(validar() == true){
					document.formulario.submit();
				}
		}
	}
	function verificar_arquivo_retorno(IdLocalRecebimento, IdArquivoRetorno){
		var url = "rotinas/verificar_arquivo_retorno.php?IdLocalRecebimento="+IdLocalRecebimento+"&IdArquivoRetorno="+IdArquivoRetorno;
		
		call_ajax(url, function (xmlhttp){
			if(Number(xmlhttp.responseText) == 1){
				if(confirm("ATENÇÃO!\n\nEste Arquivo de Retorno não esta na ordem!\nDeseja continuar?")){
					document.formulario.submit();
				}
			} else{
				document.formulario.submit();
			}
		});
	}
	function conta_receber_ocorrencia(){
		var url = "xml/conta_receber_ocorrencia.php?IdLocalRecebimento="+document.formulario.IdLocalRecebimento.value+"&IdArquivoRetorno="+document.formulario.IdArquivoRetorno.value;
		
		while(document.getElementById("tabelaContaReceberOcorrencia").rows.length > 2){
			document.getElementById("tabelaContaReceberOcorrencia").deleteRow(1);
		}
		
		call_ajax(url, function (xmlhttp){
				if(xmlhttp.responseText == "false"){
					document.getElementById("cp_conta_receber_ocorrencia").style.display					= "none";
					document.getElementById("tabelaTotalContaReceberOcorrencia").innerHTML					= "Total: 0";
					document.getElementById("tabelaValorTotalContaReceberOcorrencia").innerHTML				= "0,00";
					document.getElementById("tabelaValorDescTotalContaReceberOcorrencia").innerHTML			= "0,00";
					document.getElementById("tabelaValorMoraMultaTotalContaReceberOcorrencia").innerHTML	= "0,00";
					document.getElementById("tabelaValorRecebidoTotalContaReceberOcorrencia").innerHTML		= "0,00";
				} else{
					document.getElementById("cp_conta_receber_ocorrencia").style.display = "block";
					var ValorTotal = 0.00, ValorDescTotal = 0.00, ValorMoraMultaTotal = 0.00, ValorRecebidoTotal = 0.00;
					
					for(var i = 0; i < xmlhttp.responseXML.getElementsByTagName("IdContaReceberRecebimento").length; i++){
						var nameNode = xmlhttp.responseXML.getElementsByTagName("IdLoja")[i]; 
						var nameTextNode = nameNode.childNodes[0];
						var IdLoja = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("IdContaReceberRecebimento")[i]; 
						nameTextNode = nameNode.childNodes[0];
						var IdContaReceberRecebimento = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("DataRecebimento")[i]; 
						nameTextNode = nameNode.childNodes[0];
						var DataRecebimento = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("IdContaReceber")[i]; 
						nameTextNode = nameNode.childNodes[0];
						var IdContaReceber = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("DataVencimento")[i]; 
						nameTextNode = nameNode.childNodes[0];
						var DataVencimento = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("DataVencimentoDiaUtil")[i]; 
						nameTextNode = nameNode.childNodes[0];
						var DataVencimentoDiaUtil = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("ValorDescontoRecebimento")[i]; 
						nameTextNode = nameNode.childNodes[0];
						var ValorDescontoRecebimento = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("ValorContaReceber")[i]; 
						nameTextNode = nameNode.childNodes[0];
						var ValorContaReceber = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("ValorRecebido")[i]; 
						nameTextNode = nameNode.childNodes[0];
						var ValorRecebido = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("ValorMoraMulta")[i]; 
						nameTextNode = nameNode.childNodes[0];
						var ValorMoraMulta = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("ValorOutrasDespesas")[i]; 
						nameTextNode = nameNode.childNodes[0];
						var ValorOutrasDespesas = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("IdLoja")[i]; 
						nameTextNode = nameNode.childNodes[0];
						var IdLoja = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("MD5")[i]; 
						nameTextNode = nameNode.childNodes[0];
						var MD5 = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("IdRecibo")[i]; 
						nameTextNode = nameNode.childNodes[0];
						var IdRecibo = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("NumeroDocumento")[i]; 
						nameTextNode = nameNode.childNodes[0];
						var NumeroDocumento = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("ValorParcela")[i]; 
						nameTextNode = nameNode.childNodes[0];
						var ValorParcela = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("MargemMoraMulta")[i]; 
						nameTextNode = nameNode.childNodes[0];
						var MargemMoraMulta = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("Nome")[i]; 
						nameTextNode = nameNode.childNodes[0];
						var Nome = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("IdStatusContaReceber")[i]; 
						nameTextNode = nameNode.childNodes[0];
						var IdStatusContaReceber = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("IdStatusRecebimento")[i]; 
						nameTextNode = nameNode.childNodes[0];
						var IdStatusRecebimento = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("DataLancamento")[i]; 
						nameTextNode = nameNode.childNodes[0];
						var DataLancamento = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("DataLimiteDesconto")[i]; 
						nameTextNode = nameNode.childNodes[0];
						var DataLimiteDesconto = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("Status")[i]; 
						nameTextNode = nameNode.childNodes[0];
						var Status = nameTextNode.nodeValue;
						
						var tam 	= document.getElementById("tabelaContaReceberOcorrencia").rows.length;
						var linha	= document.getElementById("tabelaContaReceberOcorrencia").insertRow(tam-1);
						
						if((tam % 2) != 0){
							linha.style.backgroundColor = "#E2E7ED";
						}
						
						linha.accessKey = IdContaReceberRecebimento; 
						
						if(IdRecibo > 0){
							var ValorFinal = parseFloat(ValorParcela) - parseFloat(MargemMoraMulta);
							
							if(isNaN(ValorFinal)){
								ValorFinal = 0.00;
							}
							
							linha.style.backgroundColor = document.formulario.CorRecebido.value;
							
							if((DataRecebimento.replace(/(-)/g, "") > DataVencimentoDiaUtil.replace(/(-)/g, "") && ValorMoraMulta == 0.00) || (DataRecebimento.replace(/(-)/g, "") > DataLimiteDesconto.replace(/(-)/g, "") && ValorFinal < ValorRecebido)){
								linha.style.backgroundColor = "#F9F900";
							}
							
							if(IdStatusContaReceber == 0 || IdStatusRecebimento == 0){
								linha.style.backgroundColor = document.formulario.CorCancelado.value;
							}
						}
						
						/*if(IdRecibo > 1){
							linha.style.backgroundColor = document.formulario.CorRecebido.value;
						}
						
						if(ValorContaReceber > ValorRecebido){
							linha.style.backgroundColor = document.formulario.CorRecebidoDesc.value;
						}
						
						if(DataRecebimento.replace(/-/g, "") > DataLimiteDesconto.replace(/-/g, "") || ValorParcela > ValorRecebido){
							linha.style.backgroundColor = "#F9F900";
						}
						
						if(IdStatusContaReceber == 0 || IdStatusRecebimento == 0){
							linha.style.backgroundColor = document.formulario.CorCancelado.value;
						}*/
						
						var c0 = linha.insertCell(0);
						var c1 = linha.insertCell(1);
						var c2 = linha.insertCell(2);
						var c3 = linha.insertCell(3);
						var c4 = linha.insertCell(4);
						var c5 = linha.insertCell(5);
						var c6 = linha.insertCell(6);
						var c7 = linha.insertCell(7);
						var c8 = linha.insertCell(8);
						var c9 = linha.insertCell(9);
						var c10 = linha.insertCell(10);
						var c11 = linha.insertCell(11);
						var c12 = linha.insertCell(12);
						
						ValorTotal += parseFloat(ValorParcela);
						ValorDescTotal += parseFloat(ValorDescontoRecebimento);
						ValorMoraMultaTotal += parseFloat(ValorMoraMulta);
						ValorRecebidoTotal += parseFloat(ValorRecebido);
						
						var linkInicio = '';
						var linkFim = '';
						
						if(IdLoja == document.formulario.IdLoja.value ){
							linkInicio = "<a href='cadastro_conta_receber.php?IdContaReceber="+IdContaReceber+"' target='_blank'>";
							linkFim = "</a>"
						}
						
						c0.innerHTML = linkInicio+IdLoja+linkFim;
						c0.className = "tableListarEspaco";
						
						c1.innerHTML = linkInicio+NumeroDocumento+linkFim;
						c1.style.padding =	"0 5px 0 0";
						
						c2.innerHTML = linkInicio+IdContaReceberRecebimento+linkFim;
						
						c3.innerHTML = linkInicio+Nome.substr(0,20)+linkFim;
						
						c4.innerHTML = linkInicio+dateFormat(DataLancamento)+linkFim;
						
						c5.innerHTML = linkInicio+dateFormat(DataVencimento)+linkFim;
						
						c6.innerHTML = linkInicio+formata_float(Arredonda(ValorParcela,2),2).replace('.',',')+linkFim;
						c6.className = "valor";
						
						c7.innerHTML = linkInicio+dateFormat(DataRecebimento)+linkFim;
						
						c8.innerHTML = linkInicio+formata_float(Arredonda(ValorDescontoRecebimento,2),2).replace('.',',')+linkFim;
						c8.className = "valor";
						
						c9.innerHTML = linkInicio+formata_float(Arredonda(ValorMoraMulta,2),2).replace('.',',')+linkFim;
						c9.className = "valor";
						
						c10.innerHTML = linkInicio+formata_float(Arredonda(ValorRecebido,2),2).replace('.',',')+linkFim;
						c10.className = "valor";
						
						c11.innerHTML = linkInicio+Status+linkFim;
						
						c12.innerHTML = "<input type='checkbox' name='ContaReceberOcorrencia_"+IdContaReceber+"_"+IdContaReceberRecebimento+"' value='"+IdContaReceber+"' onClick='habilitar_campo(this, true);' />";
						c12.className = "tableColEnd";
					}
					
					document.getElementById("tabelaTotalContaReceberOcorrencia").innerHTML = "Total: "+i;
					document.getElementById("tabelaValorTotalContaReceberOcorrencia").innerHTML = formata_float(Arredonda(ValorTotal,2),2).replace('.',',');
					document.getElementById("tabelaValorDescTotalContaReceberOcorrencia").innerHTML = formata_float(Arredonda(ValorDescTotal,2),2).replace('.',',');
					document.getElementById("tabelaValorMoraMultaTotalContaReceberOcorrencia").innerHTML = formata_float(Arredonda(ValorMoraMultaTotal,2),2).replace('.',',');
					document.getElementById("tabelaValorRecebidoTotalContaReceberOcorrencia").innerHTML = formata_float(Arredonda(ValorRecebidoTotal,2),2).replace('.',',');
				}
		});
	}
	function habilitar_campo(Campo, Habilitar){
		var TabelaContaReceberOcorrencia = document.getElementById("tabelaContaReceberOcorrencia").rows, Habilitar = true, ContaReceberOcorrencia = "";
		document.formulario.bt_cancelar_recebimento.disabled = true;
		
		if(Campo.name === TabelaContaReceberOcorrencia[0].getElementsByTagName("input")[0].name){
			Habilitar = TabelaContaReceberOcorrencia[0].getElementsByTagName("input")[0].checked;
			
			for(var i = 1; i < (TabelaContaReceberOcorrencia.length - 1); i++){
				TabelaContaReceberOcorrencia[i].getElementsByTagName("input")[0].checked = Habilitar;
				
				if(TabelaContaReceberOcorrencia[i].getElementsByTagName("input")[0].checked){
					ContaReceberOcorrencia += "," + TabelaContaReceberOcorrencia[i].getElementsByTagName("input")[0].name.replace(/([^\d]*)/i, "");
					document.formulario.bt_cancelar_recebimento.disabled = false;
				}
			}
		} else{
			for(var i = 1; i < (TabelaContaReceberOcorrencia.length - 1); i++){
				Habilitar = (TabelaContaReceberOcorrencia[i].getElementsByTagName("input")[0].checked && Habilitar);
				
				if(TabelaContaReceberOcorrencia[i].getElementsByTagName("input")[0].checked){
					ContaReceberOcorrencia += "," + TabelaContaReceberOcorrencia[i].getElementsByTagName("input")[0].name.replace(/([^\d]*)/i, "");
					document.formulario.bt_cancelar_recebimento.disabled = false;
				}
			}
			
			TabelaContaReceberOcorrencia[0].getElementsByTagName("input")[0].checked = Habilitar;
		}
		
		document.formulario.ContaReceberOcorrencia.value = ContaReceberOcorrencia.replace(/,/i, "");
	}
	function verificar_obrigatoriedade(campo,extensoes){
		if(campo.value != ''){
			var temp = campo.value.split('.');
			var ext = temp[temp.length-1].toLowerCase();
			document.formulario.Erro.value = 0;
			
			if(!extensoes.in_array(ext) && ext != ''){
				for(i = 0; i<document.formulario.length; i++){
					if(document.formulario[i].name != undefined){
						if(document.formulario[i].name == campo.name){
							document.formulario.Erro.value = 192;
							
							document.formulario[i-1].focus();
						}
					}
				}
			} else{
				for(i = 0; i<document.formulario.length; i++){
					if(document.formulario[i].name != undefined){
						if(document.formulario[i].name.substring(0,11) == 'EndArquivo_'){
							var temp = document.formulario[i].value.split('.');
							var ext = temp[temp.length-1].toLowerCase();
							
							if(!extensoes.in_array(ext) && ext != ''){
								mensagens(192);
								document.formulario[i-1].focus();
							}
						}
					}
				}
			}
			
			verificaErro();
		}
	}