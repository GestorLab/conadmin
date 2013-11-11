	function verificaLocalRecebimento(IdLocalRecebimento){
		if(IdLocalRecebimento == '' || IdLocalRecebimento == 0){
			limpaFormArquivo();
			while(document.getElementById('tabelaTitulosRecebidos').rows.length > 2){
				document.getElementById('tabelaTitulosRecebidos').deleteRow(1);
			}
			document.getElementById('ValorDescTotal').innerHTML				=	"0,00";	
			document.getElementById('ValorOutrasDespesasTotal').innerHTML	=	"0,00";	
			document.getElementById('ValorMoraMultaTotal').innerHTML		=	"0,00";	
			document.getElementById('cpValorTotal').innerHTML				=	"0,00";	
			document.getElementById('tabelaTotal').innerHTML				=	"Total: 0";		
			
			document.formulario.IdTipoLocalCobranca.value	=	'';
			document.formulario.Acao.value					=	'inserir';
			verificaAcao();
		}else{
			busca_arquivo_retorno_tipo(IdLocalRecebimento,'',false);
			busca_local_cobranca(IdLocalRecebimento,'',false);
		}
	}
	
	function excluir(IdLocalRecebimento,IdArquivoRetorno){
		if(IdLocalRecebimento == '' || IdLocalRecebimento == undefined){
			IdLocalRecebimento = document.formulario.IdLocalRecebimento.value;
		}
		if(IdArquivoRetorno == '' || IdArquivoRetorno == undefined){
			IdArquivoRetorno = document.formulario.IdArquivoRetorno.value;
		}
		if(excluir_registro() == true){
			if(document.formulario != undefined){
				if(document.formulario.Acao.value == 'inserir'){
					return false;
				}
			}
			var xmlhttp   = false;
			if (window.XMLHttpRequest) { // Mozilla, Safari,...
    			xmlhttp = new XMLHttpRequest();
		        if(xmlhttp.overrideMimeType){
		    //    	xmlhttp.overrideMimeType('text/xml');
				}
			}else if (window.ActiveXObject){ // IE
				try{
					xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
				}catch(e){
					try{
						xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
		            } catch (e) {}
     		   }
    		}
    
   			url = "files/excluir/excluir_arquivo_retorno.php?IdLocalRecebimento="+IdLocalRecebimento+"&IdArquivoRetorno="+IdArquivoRetorno;
			xmlhttp.open("GET", url,true);
	
			xmlhttp.onreadystatechange = function(){ 

				// Carregando...
				carregando(true);

				if(xmlhttp.readyState == 4){ 
					if(xmlhttp.status == 200){
						if(document.formulario != undefined){
							document.formulario.Erro.value = xmlhttp.responseText;
							if(parseInt(xmlhttp.responseText) == 7){
								document.formulario.Acao.value 	= 'inserir';
								url = 'cadastro_arquivo_retorno.php?Erro='+document.formulario.Erro.value;
								window.location.replace(url);
							}else{
								verificaErro();
							}
						}else{
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
					}
					// Fim de Carregando
					carregando(false);
				}
				return true;
			}
			xmlhttp.send(null);
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
			if(document.formulario.EndArquivo.value==''){
				mensagens(1);
				document.formulario.EndArquivo.focus();
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
	function verifica_arquivo_retorno(EndArquivo){
		var xmlhttp   = false;
		if (window.XMLHttpRequest) { // Mozilla, Safari,...
	    	xmlhttp = new XMLHttpRequest();
	        if(xmlhttp.overrideMimeType){
	        	xmlhttp.overrideMimeType('text/xml');
			}
		}else if (window.ActiveXObject){ // IE
			try{
				xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
			}catch(e){
				try{
					xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
	            } catch (e) {}
	        }
	    }
		url = "xml/arquivo_retorno.php?EndArquivo="+EndArquivo;
		xmlhttp.open("GET", url,true);
		xmlhttp.onreadystatechange = function(){ 

			// Carregando...
			carregando(true);

			if(xmlhttp.readyState == 4){ 
				if(xmlhttp.status == 200){
					if(xmlhttp.responseText != 'false'){
						document.formulario.Arquivo.value = 'true';
					}else{
						document.formulario.Arquivo.value = 'false';
					}
				}
				// Fim de Carregando
				carregando(false);
			} 
			return true;
		}
		xmlhttp.send(null);
	}
	function inicia(){
		document.formulario.IdLocalRecebimento.focus();
		document.formulario.tempEndArquivo.value	=	"";
		document.formulario.EndArquivo.value		=	"";
		document.formulario.EnderecoArquivo.value	=	"";
	}
	function verificaAcao(){
		if(document.formulario != undefined){
			if(document.formulario.Acao.value=='inserir'){			
				document.formulario.bt_inserir.disabled 	= false;
				document.formulario.bt_processar.disabled 	= true;
				document.formulario.bt_excluir.disabled 	= true;
			}
			else if(document.formulario.Acao.value=='processar'){			
				document.formulario.bt_inserir.disabled 	= true;
				document.formulario.bt_excluir.disabled 	= false;
				document.formulario.bt_processar.disabled 	= false;
			}
		}	
	}
	function listarContaReceberRecebimento(IdArquivoRetorno,IdLocalCobranca,Erro){
		if(IdArquivoRetorno == undefined || IdArquivoRetorno==''){
			IdArquivoRetorno = 0;
		}
		
		while(document.getElementById('tabelaTitulosRecebidos').rows.length > 2){
			document.getElementById('tabelaTitulosRecebidos').deleteRow(1);
		}
		
		var nameNode, nameTextNode, url, Condicao;
		var xmlhttp   = false;
		if (window.XMLHttpRequest) { // Mozilla, Safari,...
	    	xmlhttp = new XMLHttpRequest();
	        if(xmlhttp.overrideMimeType){
	        	xmlhttp.overrideMimeType('text/xml');
			}
		}else if (window.ActiveXObject){ // IE
			try{
				xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
			}catch(e){
				try{
					xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
	            } catch (e) {}
	        }
	    }
	    
	   	url = "xml/conta_receber_recebimento.php?IdLocalCobranca="+IdLocalCobranca+"&IdArquivoRetorno="+IdArquivoRetorno;
		xmlhttp.open("GET", url,true);

		xmlhttp.onreadystatechange = function(){ 
	
			// Carregando...
			carregando(true);
	
			if(xmlhttp.readyState == 4){ 
				if(xmlhttp.status == 200){
					if(xmlhttp.responseText == 'false'){
						document.getElementById('ValorDescTotal').innerHTML				=	"0,00";	
						document.getElementById('cpValorTotal').innerHTML				=	"0,00";		
						document.getElementById('ValorMoraMultaTotal').innerHTML		=	"0,00";		
						document.getElementById('ValorRecebidoTotal').innerHTML			=	"0,00";		
						document.getElementById('ValorOutrasDespesasTotal').innerHTML	=	"0,00";	
						document.getElementById('tabelaTotal').innerHTML				=	"Total: 0";	
						document.getElementById('cpPosicaoCobranca').style.display		=	'block';
						
						// Fim de Carregando
						carregando(false);
					}else{
						var tam, linha, c0, c1, c2, c3, c4, c5, c6, c7, c8, c9, c10, c11, c12;
						var IdLoja, IdContaReceberRecebimento,DataRecebimento,DataVencimento,ValorDesconto,IdRecibo,ValorMoraMulta,ValorOutrasDespesas,ValorRecebido;
						var valorDesc = 0, valorMora = 0, valorOutro=0, valorReceb=0, valorParc=0;
						
						if(document.formulario.IdTipoLocalCobranca.value == 3){
							document.getElementById('cpPosicaoCobranca').style.display	=	'block';
						}
						
						for(var i=0; i<xmlhttp.responseXML.getElementsByTagName("IdContaReceberRecebimento").length; i++){	

							nameNode = xmlhttp.responseXML.getElementsByTagName("IdLoja")[i]; 
							nameTextNode = nameNode.childNodes[0];
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
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("IdRecibo")[i]; 
							nameTextNode = nameNode.childNodes[0];
							IdRecibo = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("ValorParcela")[i]; 
							nameTextNode = nameNode.childNodes[0];
							ValorParcela = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("Nome")[i]; 
							nameTextNode = nameNode.childNodes[0];
							Nome = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("DataVencimento")[i]; 
							nameTextNode = nameNode.childNodes[0];
							DataVencimento = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("IdStatus")[i]; 
							nameTextNode = nameNode.childNodes[0];
							IdStatus = nameTextNode.nodeValue;
							
							/*
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("PosicaoCobranca")[i]; 
							nameTextNode = nameNode.childNodes[0];
							PosicaoCobranca = nameTextNode.nodeValue;
							
							*/
							
							tam 	= document.getElementById('tabelaTitulosRecebidos').rows.length;
							linha	= document.getElementById('tabelaTitulosRecebidos').insertRow(tam-1);
							
							if(tam%2 != 0){
								linha.style.backgroundColor = "#E2E7ED";
							}
							
							linha.accessKey = IdContaReceberRecebimento; 
							
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
							
							if(document.formulario.IdTipoLocalCobranca.value == 3){
								c12	= linha.insertCell(12);
							}							
							
							valorParc	=	parseFloat(valorParc) + parseFloat(ValorParcela);
							valorReceb	=	parseFloat(valorReceb) + parseFloat(ValorRecebido);
							valorDesc	=	parseFloat(valorDesc) + parseFloat(ValorDescontoRecebimento);
							valorMora	=	parseFloat(valorMora) + parseFloat(ValorMoraMulta);
							valorOutro	=	parseFloat(valorOutro) + parseFloat(ValorOutrasDespesas);
							
							if(IdRecibo > 1){
								linha.style.backgroundColor = document.formulario.CorRecebido.value;
							}
							
							if(ValorContaReceber > ValorRecebido){
								linha.style.backgroundColor = document.formulario.CorRecebidoDesc.value;
							}
							
							if(IdStatus == 0){
								linha.style.backgroundColor = document.formulario.CorCancelado.value;								
							}
							
							c0.innerHTML = IdLoja;
							c0.style.padding =	"0 0 0 5px";
							
							c1.innerHTML = IdContaReceber;
							c1.style.padding =	"0 0 0 5px";
							
							c2.innerHTML = IdContaReceberRecebimento;
							
							c3.innerHTML = Nome;
							
							c4.innerHTML = dateFormat(DataVencimento);
							
							c5.innerHTML = formata_float(Arredonda(ValorParcela,2),2).replace('.',',');
							c5.style.textAlign = "right";
							c5.style.padding =	"0 8px 0 0";
							
							c6.innerHTML = dateFormat(DataRecebimento);
							
							c7.innerHTML = formata_float(Arredonda(ValorDescontoRecebimento,2),2).replace('.',',');
							c7.style.textAlign = "right";
							c7.style.padding =	"0 8px 0 0";
							
							c8.innerHTML = formata_float(Arredonda(ValorMoraMulta,2),2).replace('.',',');
							c8.style.textAlign = "right";
							c8.style.padding =	"0 8px 0 0";
							
							c9.innerHTML = formata_float(Arredonda(ValorRecebido,2),2).replace('.',',');
							c9.style.textAlign = "right";
							c9.style.padding =	"0 8px 0 0";
							
							c10.innerHTML = formata_float(Arredonda(ValorOutrasDespesas,2),2).replace('.',',');
							c10.style.textAlign = "right";
							c10.style.padding =	"0 8px 0 0";
							
							c11.innerHTML = "<a href='recibo.php?IdLoja="+IdLoja+"&IdRecibo="+IdRecibo+"' target='_blank'>"+IdRecibo+"</a>";
							c11.style.cursor = "pointer";
						}
						
						document.getElementById('cpValorTotal').innerHTML				=	formata_float(Arredonda(valorParc,2),2).replace('.',',');		
						document.getElementById('ValorDescTotal').innerHTML				=	formata_float(Arredonda(valorDesc,2),2).replace('.',',');		
						document.getElementById('ValorMoraMultaTotal').innerHTML		=	formata_float(Arredonda(valorMora,2),2).replace('.',',');		
						document.getElementById('ValorRecebidoTotal').innerHTML			=	formata_float(Arredonda(valorReceb,2),2).replace('.',',');		
						document.getElementById('ValorOutrasDespesasTotal').innerHTML	=	formata_float(Arredonda(valorOutro,2),2).replace('.',',');		
						document.getElementById('tabelaTotal').innerHTML				=	"Total: "+i;	
					}
				}
				// Fim de Carregando
				carregando(false);
			} 
			return true;
		}
		xmlhttp.send(null);
	}
