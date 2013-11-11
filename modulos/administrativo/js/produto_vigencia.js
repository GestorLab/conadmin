	function inicia(){
		status_inicial();
		document.formulario.IdProduto.focus();
	}
	function validar_Data(id,campo){
		if(campo.value == ''){
			document.getElementById(id).style.backgroundColor='#FFFFFF';
			if(id == 'DataInicio'){
				document.getElementById(id).style.color='#C10000';
			}else{
				document.getElementById(id).style.color='#000000';	
			}
			mensagens(0);
			return false;
		}
		if(isData(campo.value) == false){		
			document.getElementById(id).style.backgroundColor = '#C10000';
			document.getElementById(id).style.color='#FFFFFF';
			mensagens(27);
			return false;
		}else{
			document.getElementById(id).style.backgroundColor='#FFFFFF';
			if(id == 'DataInicio'){
				document.getElementById(id).style.color='#C10000';
			}else{
				document.getElementById(id).style.color='#000000';	
			}
			mensagens(0);
			return true;
		}	
	}
	function verificaDataFinal(DataInicio,DataFim){
		if(DataInicio != '' && DataFim != ''){
			var dataI = formatDate(DataInicio);
			var dataF = formatDate(DataFim);
			if(dataF < dataI){
				colorTemp = document.getElementById('DataTermino').style.backgroundColor;
				document.getElementById('DataTermino').style.backgroundColor = '#C10000';
				document.getElementById('DataTermino').style.color='#FFFFFF';
				mensagens(39);
				return false;
			}else{
				colorTemp = document.getElementById('DataInicio').style.backgroundColor;
				document.getElementById('DataTermino').style.backgroundColor = '#FFFFFF';
				document.getElementById('DataTermino').style.color='#000000';
				mensagens(0);
			}
			return true;
		}
	}
	function listarVigencia(IdProduto,Erro){
		while(document.getElementById('tabelaVigencia').rows.length > 2){
			document.getElementById('tabelaVigencia').deleteRow(1);
		}		
		
		var tam, linha, c0, c1, c2, c3, c4, c5, c6, c7;
		
		if(IdProduto == ''){
			IdProduto = 0;
		}
		var nameNode, nameTextNode, url;
		
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
	    
	   	url = "xml/produto_vigencia.php?IdProduto="+IdProduto;
		xmlhttp.open("GET", url,true);
		xmlhttp.onreadystatechange = function(){ 
	
			// Carregando...
			carregando(true);
	
			if(xmlhttp.readyState == 4){ 
				if(xmlhttp.status == 200){
	
					if(Erro != false){
						document.formulario.Erro.value = 0;
						verificaErro();
					}
					if(xmlhttp.responseText == 'false'){		
						
						document.getElementById('tabelaVigenciaValor').innerHTML			=	"0,00";	
						document.getElementById('tabelaVigenciaValorDesconto').innerHTML	=	"0,00";	
						document.getElementById('tabelaVigenciaValorFinal').innerHTML		=	"0,00";	
						document.getElementById('tabelaVigenciaTotal').innerHTML			=	"Total: 0";
												
						// Fim de Carregando
						carregando(false);
					}else{
						
						var IdProdutoTipoVigencia, DataInicio, DataTermino, Valor, ValorDesconto, DataLimiteDesconto, DescricaoProdutoTipoDesconto;
						var TotalValor=0,TotalDesc=0,TotalFinal=0;
						for(var i=0; i<xmlhttp.responseXML.getElementsByTagName("DataInicio").length; i++){
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoProdutoTipoVigencia")[i]; 
							nameTextNode = nameNode.childNodes[0];
							DescricaoProdutoTipoVigencia = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("DataInicio")[i]; 
							nameTextNode = nameNode.childNodes[0];
							DataInicio = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("DataTermino")[i]; 
							nameTextNode = nameNode.childNodes[0];
							DataTermino = nameTextNode.nodeValue;	
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("Valor")[i]; 
							nameTextNode = nameNode.childNodes[0];
							Valor = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("ValorDesconto")[i]; 
							nameTextNode = nameNode.childNodes[0];
							ValorDesconto = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("DataLimiteDesconto")[i]; 
							nameTextNode = nameNode.childNodes[0];
							DataLimiteDesconto = nameTextNode.nodeValue;
												
							tam 	= document.getElementById('tabelaVigencia').rows.length;
							linha	= document.getElementById('tabelaVigencia').insertRow(tam-1);
							
							if(i%2 != 0){
								linha.style.backgroundColor = "#E2E7ED";
							}
							
							if(dateFormat(DataInicio) == document.formulario.DataInicio.value){
								linha.style.backgroundColor = "#A0C4EA";
							}
							
							linha.accessKey = DataInicio; 
							
							c0	= linha.insertCell(0);	
							c1	= linha.insertCell(1);	
							c2	= linha.insertCell(2);	
							c3	= linha.insertCell(3);
							c4	= linha.insertCell(4);
							c5	= linha.insertCell(5);
							c6	= linha.insertCell(6);
							c7	= linha.insertCell(7);
							
							if(Valor == '')			Valor  		  = 0;
							if(ValorDesconto == '')	ValorDesconto = 0;
							
							
							ValorTotal	=	Valor-ValorDesconto; 
							
							TotalValor	=	TotalValor +	parseFloat(Valor);
							TotalDesc	=	TotalDesc +	parseFloat(ValorDesconto);
							TotalFinal	=	TotalFinal + parseFloat(ValorTotal);
							
							var linkIni = "<a href='#' onClick=\"busca_produto_vigencia("+IdProduto+",'"+DataInicio+"',false,'"+document.formulario.Local.value+"')\">";
							var linkFim = "</a>";
							
							c0.innerHTML = linkIni + dateFormat(DataInicio) + linkFim;
							c0.style.padding	 =	"0 0 0 5px";
							
							c1.innerHTML = linkIni + dateFormat(DataTermino) + linkFim;
							
							c2.innerHTML = linkIni + formata_float(Valor,2).replace(".",",") + linkFim;
							c2.style.textAlign = "right";
							c2.style.padding	 =	"0 8px 0 0";
							
							c3.innerHTML = linkIni + formata_float(ValorDesconto,2).replace(".",",") + linkFim;
							c3.style.textAlign = "right";
							c3.style.padding	 =	"0 8px 0 0";
							
							c4.innerHTML = linkIni + formata_float(ValorTotal,2).replace(".",",") + linkFim;
							c4.style.textAlign = "right";
							c4.style.padding	 =	"0 8px 0 0";
							
							c5.innerHTML = linkIni + DescricaoProdutoTipoVigencia + linkFim;
							
							c6.innerHTML = linkIni + DataLimiteDesconto + linkFim;
							
							c7.innerHTML = "<img src='../../img/estrutura_sistema/ico_del.gif' alt='Excluir?' onClick=\"excluir("+IdProduto+",'"+DataInicio+"','listar')\">";
							c7.style.textAlign = "center";
							c7.style.cursor = "pointer";
						}
						document.getElementById('tabelaVigenciaValor').innerHTML			=	formata_float(Arredonda(TotalValor,2),2).replace('.',',');	
						document.getElementById('tabelaVigenciaValorDesconto').innerHTML	=	formata_float(Arredonda(TotalDesc,2),2).replace('.',',');
						document.getElementById('tabelaVigenciaValorFinal').innerHTML		=	formata_float(Arredonda(TotalFinal,2),2).replace('.',',');
						document.getElementById('tabelaVigenciaTotal').innerHTML			=	"Total: "+i;
						
					}	
					
					if(window.janela != undefined){
						window.janela.close();
					}
				}
				// Fim de Carregando
				carregando(false);
			} 
			return true;
		}
		xmlhttp.send(null);
	}
	function calculaValorFinal(valor,desc,perc,campo){
		if(valor=='' || desc == '' || perc==''){
			if(valor=='')	valor = '0,00';
			if(desc=='')	desc  = '0,00';
			if(perc=='')	perc  = '0,00';
		}
		var tempValor	=	valor.replace(".","");
		tempValor		=	tempValor.replace(".","");
		tempValor		=	tempValor.replace(",",".");
		
		var tempDesc	=	desc.replace("."," ");
		tempDesc		=	tempDesc.replace("."," ");
		tempDesc		=	tempDesc.replace(",",".");
		
		var valFinal	=	tempValor - tempDesc;
		
		if(campo.name == 'ValorDesconto'){
			tempPerc = (parseFloat(tempDesc)*100)/parseFloat(tempValor);
			
			tempPerc		= 	formata_float(Arredonda(tempPerc,2),2);
			tempPerc		=	tempPerc.replace('.',',');
			
			document.formulario.DescontoPerc.value	=	tempPerc;
		}else if(campo.name == 'ValorPercentual'){
			var tempPerc	=	perc.replace("."," ");
			tempPerc		=	tempPerc.replace("."," ");
			tempPerc		=	tempPerc.replace(",",".");
		
			
			tempDesc	=	(parseFloat(tempPerc)*parseFloat(tempValor))/100;
			
			
			valFinal	=	tempValor -	tempDesc;
			
			tempDesc		= 	formata_float(Arredonda(tempDesc,2),2);
			tempDesc		=	tempDesc.replace('.',',');
			
			document.formulario.ValorDesconto.value	=	tempDesc;
		}
		
		valFinal		= 	formata_float(Arredonda(valFinal,2),2);
		valFinal		=	valFinal.replace('.',',');
		
		document.formulario.ValorFinal.value	=	valFinal;
	}
	function excluir(IdProduto,DataInicio,listar){
		if(excluir_registro() == true){
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
    
   			url = "files/excluir/excluir_produto_vigencia.php?IdProduto="+IdProduto+"&DataInicio="+DataInicio;
			xmlhttp.open("GET", url,true);
		
			xmlhttp.onreadystatechange = function(){ 

				// Carregando...
				carregando(true);

				if(xmlhttp.readyState == 4){ 
					if(xmlhttp.status == 200){
						
						if(listar == undefined || listar == ''){
							if(document.formulario != undefined){
								document.formulario.Erro.value = xmlhttp.responseText;
								if(parseInt(xmlhttp.responseText) == 7){
									document.formulario.Acao.value 	= 'inserir';
									url = 'cadastro_produto_vigencia.php?Erro='+document.formulario.Erro.value+"&IdProduto="+IdProduto;
									window.location.replace(url);
								}else{
									verificaErro();
								}
							}else{
								var numMsg = parseInt(xmlhttp.responseText);
								mensagens(numMsg);
								if(numMsg == 7){
									for(var i=0; i<document.getElementById('tableListar').rows.length; i++){
										if(IdProduto+"_"+DataInicio == document.getElementById('tableListar').rows[i].accessKey){
											document.getElementById('tableListar').deleteRow(i);
											tableMultColor('tableListar',document.filtro.corRegRand.value);
											break;
										}
									}								
								}
							}
						}else{
							var numMsg = parseInt(xmlhttp.responseText);
							mensagens(numMsg);
							if(numMsg == 7){
								if(formatDate(document.formulario.DataInicio.value) == DataInicio){
									document.formulario.DataInicio.value 					= '';		
									document.formulario.DataTermino.value 					= '';
									document.formulario.ValorDesconto.value 				= '';
									document.formulario.DescontoPerc.value 					= '';
									document.formulario.Valor.value							= '';
									document.formulario.ValorFinal.value					= '';
									document.formulario.DataLimiteDesconto.value			= '';
									document.formulario.IdProdutoTipoVigencia.value			= '';
									document.formulario.DescricaoProdutoTipoVigencia.value	= '';
									document.formulario.Acao.value							= 'inserir';
							
									status_inicial();
									verificaAcao();
									
									document.formulario.DataInicio.focus();
								}
								for(var i=0; i<document.getElementById('tabelaVigencia').rows.length; i++){
									if(DataInicio == document.getElementById('tabelaVigencia').rows[i].accessKey){
										document.getElementById('tabelaVigencia').deleteRow(i);
										tableMultColor('tabelaVigencia',document.filtro.corRegRand.value);
										break;
									}
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
		if(document.formulario.IdProduto.value==''){
			mensagens(1);
			document.formulario.IdProduto.focus();
			return false;
		}
		if(document.formulario.DataInicio.value==''){
			mensagens(1);
			document.formulario.DataInicio.focus();
			return false;
		}else{
			if(isData(document.formulario.DataInicio.value) == false){		
				mensagens(27);
				document.formulario.DataInicio.focus();
				return false;
			}
		}
		if(document.formulario.DataTermino.value!=""){
			if(isData(document.formulario.DataTermino.value) == false){		
				mensagens(27);
				document.formulario.DataTermino.focus();
				return false;
			}
		}
		if(document.formulario.DataInicio.value != '' && document.formulario.DataTermino.value != ''){
			var dataI = formatDate(document.formulario.DataInicio.value);
			var dataF = formatDate(document.formulario.DataTermino.value);
			if(dataF < dataI){
				mensagens(39);
				document.formulario.DataTermino.focus();
				return false;
			}
		}
		if(document.formulario.Valor.value==''){
			mensagens(1);
			document.formulario.Valor.focus();
			return false;
		}
		if(document.formulario.ValorDesconto.value==''){
			mensagens(1);
			document.formulario.ValorDesconto.focus();
			return false;
		}
		if(document.formulario.IdProdutoTipoVigencia.value==''){
			mensagens(1);
			document.formulario.IdProdutoTipoVigencia.focus();
			return false;
		}
		mensagens(0);
		return true;
	}
	function calculaDesconto(campo){
		var valorDesc,valor,valorFinal,desconto;
		
		// ----------- Campo Valor ------------------ //
		valor = document.formulario.Valor.value;
		
		if(valor == '')	valor = 0;
		
		valor = new String(valor);
		valor = valor.replace('.','');		
		valor = valor.replace('.','');
		valor = valor.replace('.','');
		valor = valor.replace(',','.');
		valor = parseFloat(valor);	
		
		if(campo.name == 'Valor' || campo.name == 'ValorDesconto'){
			// ------------ Campo Desconto --------------- //
			valorDesc = document.formulario.ValorDesconto.value;
		
			if(valorDesc == '')	valorDesc = 0;
			
			valorDesc = new String(valorDesc);
			valorDesc = valorDesc.replace('.','');		
			valorDesc = valorDesc.replace('.','');
			valorDesc = valorDesc.replace('.','');
			valorDesc = valorDesc.replace(',','.');
			valorDesc = parseFloat(valorDesc);
			
			ValorFinal	=	valor - valorDesc;
			
			if(valorDesc != 0){
				desconto = ((valorDesc*100)/valor);
			}else{
				desconto = 0;
			}
			document.formulario.Valor.value  		= formata_float(Arredonda(valor,2),2).replace('.',',');
			document.formulario.ValorDesconto.value = formata_float(Arredonda(valorDesc,2),2).replace('.',',');
			document.formulario.DescontoPerc.value  = formata_float(Arredonda(desconto,2),2).replace('.',',');
			document.formulario.ValorFinal.value 	= formata_float(Arredonda(ValorFinal,2),2).replace('.',',');
		}else{
			// ------------ Campo Percentual Desconto --------------- //
			desconto = document.formulario.DescontoPerc.value;
		
			if(desconto == '')	desconto = 0;
			
			desconto = new String(desconto);
			desconto = desconto.replace('.','');		
			desconto = desconto.replace('.','');
			desconto = desconto.replace('.','');
			desconto = desconto.replace(',','.');
			desconto = parseFloat(desconto);	
			
			valorDesc	=	(valor * desconto)/100;
			ValorFinal	=	valor - valorDesc;
			
			document.formulario.Valor.value  		= formata_float(Arredonda(valor,2),2).replace('.',',');
			document.formulario.ValorDesconto.value	= formata_float(Arredonda(valorDesc,2),2).replace('.',',');
			document.formulario.DescontoPerc.value  = formata_float(Arredonda(desconto,2),2).replace('.',',');
			document.formulario.ValorFinal.value	= formata_float(Arredonda(ValorFinal,2),2).replace('.',',');			
		}
	}
 
