	function inicia(){
		status_inicial();
		document.formulario.IdContrato.focus();
	}
	function validar_Data(id,campo){
		if(campo.value == ''){
			document.getElementById(id).style.backgroundColor='#FFFFFF';
			if(id == 'DataInicioVigencia'){
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
			if(id == 'DataInicioVigencia'){
				document.getElementById(id).style.color='#C10000';
			}else{
				document.getElementById(id).style.color='#000000';	
			}
			mensagens(0);
			return true;
		}	
	}
	function verificaDataFinal(campo,DataInicio,DataFim){
		if(DataInicio != '' && DataFim != ''){
			var dataI = formatDate(DataInicio);
			var dataF = formatDate(DataFim);
			if(dataF < dataI){
				colorTemp = document.getElementById(campo).style.backgroundColor;
				document.getElementById(campo).style.backgroundColor = '#C10000';
				document.getElementById(campo).style.color='#FFFFFF';
				mensagens(39);
				return false;
			}else{
				colorTemp = document.getElementById(campo).style.backgroundColor;
				document.getElementById(campo).style.backgroundColor = '#FFFFFF';
				document.getElementById(campo).style.color='#C10000';
				mensagens(0);
			}
			return true;
		}
	}
	
	function busca_vigencia(IdContrato,Erro,Local,DataInicioVigenciaCond){
		if(IdContrato == ''){
			IdContrato = 0;
		}
		if(DataInicioVigenciaCond == '' || DataInicioVigenciaCond == undefined){
			DataInicioVigenciaCond = 0;
		}else{
			var tam		=	document.getElementById('tabelaVigencia').rows.length;	
			var i;
			for(i=0; i<tam; i++){
				if(document.getElementById('tabelaVigencia').rows[i].accessKey == DataInicioVigenciaCond){
					document.getElementById('tabelaVigencia').rows[i].style.backgroundColor = "#A0C4EA";
				}
				else{
					if(i%2 == 0 && i!=0 && i!=(tam-1)){
						document.getElementById('tabelaVigencia').rows[i].style.backgroundColor = "#E2E7ED";
					}else if(i%2 != 0 && i!=0 && i!=(tam-1)){
						document.getElementById('tabelaVigencia').rows[i].style.backgroundColor = "#FFFFFF";
					}
				}
			}
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
	    url = "xml/contrato_vigencia.php?IdContrato="+IdContrato+"&DataInicioVigencia="+DataInicioVigenciaCond;
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
						if(DataInicioVigenciaCond != 0){
							document.formulario.DataInicioVigencia.value 			= dateFormat(DataInicioVigenciaCond);		
						}else{
							document.formulario.DataInicioVigencia.value 			= "";
							document.formulario.DataInicioVigencia.focus();
						}
						document.formulario.DataTerminoVigencia.value 			= '';
						document.formulario.ValorDesconto.value 				= '';
						document.formulario.Valor.value							= '';
						document.formulario.ValorRepasseTerceiro.value			= '';
						document.formulario.ValorFinal.value					= '';
						document.formulario.DiaLimiteDesconto.value				= '';
						document.formulario.DataLimiteDesconto.value			= '';
						document.formulario.IdTipoDesconto.value				= '';
						document.formulario.IdContratoTipoVigencia.value		= '';
						document.formulario.DescricaoContratoTipoVigencia.value	= '';
						document.formulario.Acao.value							= 'inserir';
						
						status_inicial();
						
						busca_contrato(IdContrato,false);
						
						// Fim de Carregando
						carregando(false);
					}else{
						var DataInicioVigencia, DataTerminoVigencia, Valor, ValorDesconto, DescricaoContratoTipoVigencia, IdContratoTipoVigencia, DataLimiteDesconto, ValorTotal;
						for(var i=0; i<xmlhttp.responseXML.getElementsByTagName("DataInicioVigencia").length; i++){
					
							nameNode = xmlhttp.responseXML.getElementsByTagName("DataInicioVigencia")[i]; 
							nameTextNode = nameNode.childNodes[0];
							DataInicioVigencia = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("DataTerminoVigencia")[i]; 
							nameTextNode = nameNode.childNodes[0];
							DataTerminoVigencia = nameTextNode.nodeValue;	
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("IdContratoTipoVigencia")[i]; 
							nameTextNode = nameNode.childNodes[0];
							IdContratoTipoVigencia = nameTextNode.nodeValue;					
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoContratoTipoVigencia")[i]; 
							nameTextNode = nameNode.childNodes[0];
							DescricaoContratoTipoVigencia = nameTextNode.nodeValue;					
					
							nameNode = xmlhttp.responseXML.getElementsByTagName("IdTipoDesconto")[i]; 
							nameTextNode = nameNode.childNodes[0];
							IdTipoDesconto = nameTextNode.nodeValue;	
					
							nameNode = xmlhttp.responseXML.getElementsByTagName("ValorDesconto")[i]; 
							nameTextNode = nameNode.childNodes[0];
							ValorDesconto = nameTextNode.nodeValue;					
					
							nameNode = xmlhttp.responseXML.getElementsByTagName("Valor")[i]; 
							nameTextNode = nameNode.childNodes[0];
							Valor = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("ValorRepasseTerceiro")[i]; 
							nameTextNode = nameNode.childNodes[0];
							ValorRepasseTerceiro = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("IdTipoDesconto")[i]; 
							nameTextNode = nameNode.childNodes[0];
							IdTipoDesconto = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("LimiteDesconto")[i]; 
							nameTextNode = nameNode.childNodes[0];
							LimiteDesconto = nameTextNode.nodeValue;	
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("ValorTotal")[i]; 
							nameTextNode = nameNode.childNodes[0];
							ValorTotal = nameTextNode.nodeValue;
						
							nameNode = xmlhttp.responseXML.getElementsByTagName("DataCriacao")[i]; 
							nameTextNode = nameNode.childNodes[0];
							var DataCriacao = nameTextNode.nodeValue;
						
							nameNode = xmlhttp.responseXML.getElementsByTagName("LoginCriacao")[i]; 
							nameTextNode = nameNode.childNodes[0];
							var LoginCriacao = nameTextNode.nodeValue;					
						
							nameNode = xmlhttp.responseXML.getElementsByTagName("DataAlteracao")[i]; 
							nameTextNode = nameNode.childNodes[0];
							var DataAlteracao = nameTextNode.nodeValue;
						
							nameNode = xmlhttp.responseXML.getElementsByTagName("LoginAlteracao")[i]; 
							nameTextNode = nameNode.childNodes[0];
							var LoginAlteracao = nameTextNode.nodeValue;

							document.formulario.DataInicioVigencia.value			= dateFormat(DataInicioVigencia);
							document.formulario.DataTerminoVigencia.value 			= dateFormat(DataTerminoVigencia);
							document.formulario.ValorDesconto.value 				= formata_float(Arredonda(ValorDesconto,2),2).replace(".",",");
							document.formulario.Valor.value							= formata_float(Arredonda(Valor,2),2).replace(".",",");
							document.formulario.ValorRepasseTerceiro.value			= formata_float(Arredonda(ValorRepasseTerceiro,2),2).replace(".",",");
							document.formulario.ValorFinal.value					= formata_float(Arredonda(ValorTotal,2),2).replace(".",",");
							document.formulario.IdTipoDesconto.value				= IdTipoDesconto;
							document.formulario.IdContratoTipoVigencia.value		= IdContratoTipoVigencia;
							document.formulario.DescricaoContratoTipoVigencia.value	= DescricaoContratoTipoVigencia;
							document.formulario.DataCriacao.value					= dateFormat(DataCriacao);
							document.formulario.LoginCriacao.value					= LoginCriacao;
							document.formulario.DataAlteracao.value					= dateFormat(DataAlteracao);
							document.formulario.LoginAlteracao.value				= LoginAlteracao;
							
							addParmUrl("marContrato","IdContrato",IdContrato);
							addParmUrl("marVigenciaNovo","IdContrato",IdContrato);
							addParmUrl("marContasReceber","IdContrato",IdContrato);
							addParmUrl("marLancamentoFinanceiro","IdContrato",IdContrato);
							
							calculaValorFinal(document.formulario.Valor.value,document.formulario.ValorDesconto.value,'',document.formulario.ValorDesconto);
							
							document.formulario.Acao.value					= 'alterar';
							document.formulario.DataInicioVigencia.focus();
							
							verificaLimiteDesconto(IdTipoDesconto);
							
							switch(IdTipoDesconto){
								case '1':
									document.formulario.DataLimiteDesconto.value	=	dateFormat(LimiteDesconto);
									document.formulario.DiaLimiteDesconto.value		=	"";
									break;
								case '2':
									document.formulario.DataLimiteDesconto.value	=	"";
									document.formulario.DiaLimiteDesconto.value		=	LimiteDesconto;
									break;
							}
						}
					}	
					
					if(window.janela != undefined){
						window.janela.close();
					}
					verificaAcao();
				}
				// Fim de Carregando
				carregando(false);
			} 
			return true;
		}
		xmlhttp.send(null);
	}
	function listarVigencia(IdContrato,Erro){
		while(document.getElementById('tabelaVigencia').rows.length > 2){
			document.getElementById('tabelaVigencia').deleteRow(1);
		}		
		
		var tam, linha, c0, c1, c2, c3, c4, c5, c6, c7;
		
		if(IdContrato == ''){
			IdContrato = 0;
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
	    
	   	url = "xml/contrato_vigencia.php?IdContrato="+IdContrato;
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
						document.getElementById('tabelaRepasse').innerHTML					=	"0,00";
						document.getElementById('tabelaVigenciaTotal').innerHTML			=	"Total: 0";
												
						// Fim de Carregando
						carregando(false);
					}else{
						var IdContratoTipoVigencia, DataInicioVigencia, DataTerminoVigencia, Valor, ValorDesconto, DataLimiteDesconto, DescricaoContratoTipoDesconto;
						var TotalValor=0,TotalDesc=0,TotalFinal=0,TotalRepasse=0;
						for(var i=0; i<xmlhttp.responseXML.getElementsByTagName("DataInicioVigencia").length; i++){
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoContratoTipoVigencia")[i]; 
							nameTextNode = nameNode.childNodes[0];
							DescricaoContratoTipoVigencia = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("DataInicioVigencia")[i]; 
							nameTextNode = nameNode.childNodes[0];
							DataInicioVigencia = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("DataTerminoVigencia")[i]; 
							nameTextNode = nameNode.childNodes[0];
							DataTerminoVigencia = nameTextNode.nodeValue;	
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("Valor")[i]; 
							nameTextNode = nameNode.childNodes[0];
							Valor = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("ValorRepasseTerceiro")[i]; 
							nameTextNode = nameNode.childNodes[0];
							ValorRepasseTerceiro = nameTextNode.nodeValue;	
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("ValorDesconto")[i]; 
							nameTextNode = nameNode.childNodes[0];
							ValorDesconto = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("ValorTotal")[i]; 
							nameTextNode = nameNode.childNodes[0];
							ValorTotal = nameTextNode.nodeValue;
												
							tam 	= document.getElementById('tabelaVigencia').rows.length;
							linha	= document.getElementById('tabelaVigencia').insertRow(tam-1);
							
							if(i%2 != 0){
								linha.style.backgroundColor = "#E2E7ED";
							}
							
							linha.accessKey = DataInicioVigencia; 
							
							c0	= linha.insertCell(0);	
							c1	= linha.insertCell(1);	
							c2	= linha.insertCell(2);	
							c3	= linha.insertCell(3);
							c4	= linha.insertCell(4);
							c5	= linha.insertCell(5);
							c6	= linha.insertCell(6);
							c7	= linha.insertCell(7);
							
							if(Valor == '')					Valor  		  			= 0;
							if(ValorDesconto == '')			ValorDesconto 			= 0;
							if(ValorTotal == '')			ValorTotal   			= 0;
							if(ValorRepasseTerceiro == '')	ValorRepasseTerceiro    = 0;
							
							TotalValor		=	TotalValor +	parseFloat(Valor);
							TotalDesc		=	TotalDesc +	parseFloat(ValorDesconto);
							TotalFinal		=	TotalFinal + parseFloat(ValorTotal);
							TotalRepasse	=	TotalRepasse + parseFloat(ValorRepasseTerceiro);
							
							var linkIni = "<a href='#' onClick=\"busca_vigencia("+IdContrato+",false,'"+document.formulario.Local.value+"','"+DataInicioVigencia+"')\">";
							var linkFim = "</a>";
							
							c0.innerHTML = linkIni + dateFormat(DataInicioVigencia) + linkFim;
							c0.style.padding	 =	"0 0 0 5px";
							
							c1.innerHTML = linkIni + dateFormat(DataTerminoVigencia) + linkFim;
							
							c2.innerHTML = linkIni + formata_float(Valor,2).replace(".",",") + linkFim;
							c2.style.textAlign = "right";
							c2.style.padding   =	"0 8px 0 0";
							
							c3.innerHTML = linkIni + formata_float(ValorDesconto,2).replace(".",",") + linkFim;
							c3.style.textAlign = "right";
							c3.style.padding   =	"0 8px 0 0";
							
							c4.innerHTML = linkIni + formata_float(ValorTotal,2).replace(".",",") + linkFim;
							c4.style.textAlign = "right";
							c4.style.padding   =	"0 8px 0 0";
							
							c5.innerHTML = linkIni + DescricaoContratoTipoVigencia + linkFim;
							
							c6.innerHTML = linkIni + formata_float(ValorRepasseTerceiro,2).replace(".",",") + linkFim;
							c6.style.textAlign = "right";
							c6.style.padding   =	"0 8px 0 0";
							
							c7.innerHTML = "<img src='../../img/estrutura_sistema/ico_del.gif' alt='Excluir?' onClick=\"excluir("+IdContrato+",'"+DataInicioVigencia+"','listar')\">";
							c7.style.textAlign = "center";
							c7.style.cursor = "pointer";
						}
						document.getElementById('tabelaVigenciaValor').innerHTML			=	formata_float(Arredonda(TotalValor,2),2).replace('.',',');	
						document.getElementById('tabelaVigenciaValorDesconto').innerHTML	=	formata_float(Arredonda(TotalDesc,2),2).replace('.',',');
						document.getElementById('tabelaVigenciaValorFinal').innerHTML		=	formata_float(Arredonda(TotalFinal,2),2).replace('.',',');
						document.getElementById('tabelaRepasse').innerHTML					=	formata_float(Arredonda(TotalRepasse,2),2).replace('.',',');
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
			
			document.formulario.ValorPercentual.value	=	tempPerc;
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
	function excluir(IdContrato,DataInicioVigencia,listar){
		if(IdContrato== '' || IdContrato == undefined){
			IdContrato = document.formulario.IdContrato.value;
		}
		if(IdContrato == ''){
			return false;
		}
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
    
   			url = "files/excluir/excluir_contrato_vigencia.php?IdContrato="+IdContrato+"&DataInicioVigencia="+DataInicioVigencia;
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
									url = 'cadastro_vigencia.php?Erro='+document.formulario.Erro.value+"&IdContrato="+IdContrato;
									window.location.replace(url);
								}else{
									verificaErro();
								}
							}else{
								var numMsg = parseInt(xmlhttp.responseText);
								mensagens(numMsg);
								if(numMsg == 7){
									for(var i=0; i<document.getElementById('tableListar').rows.length; i++){
										if(IdContrato+"_"+DataInicioVigencia == document.getElementById('tableListar').rows[i].accessKey){
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
								if(formatDate(document.formulario.DataInicioVigencia.value) == DataInicioVigencia){
									document.formulario.DataInicioVigencia.value 			= '';		
									document.formulario.DataTerminoVigencia.value 			= '';
									document.formulario.ValorDesconto.value 				= '';
									document.formulario.Valor.value							= '';
									document.formulario.ValorFinal.value					= '';
									document.formulario.DataLimiteDesconto.value			= '';
									document.formulario.IdContratoTipoVigencia.value		= '';
									document.formulario.DescricaoContratoTipoVigencia.value	= '';
									document.formulario.Acao.value							= 'inserir';
							
									status_inicial();
									verificaAcao();
									
									document.formulario.DataInicioVigencia.focus();
								}
								var valor=0, desc=0, total=0, repasse=0, aux=0, cont=0;
								for(var i=0; i<document.getElementById('tabelaVigencia').rows.length; i++){
									if(DataInicioVigencia == document.getElementById('tabelaVigencia').rows[i].accessKey){
										document.getElementById('tabelaVigencia').deleteRow(i);
										tableMultColor('tabelaVigencia',document.filtro.corRegRand.value);
										aux = 1;
										break;
									}
								}
								if(aux == 1){
									for(var i=1; i<(document.getElementById('tabelaVigencia').rows.length-1); i++){
										temp	 =	document.getElementById('tabelaVigencia').rows[i].cells[2].innerHTML.split(">");
										temp1	 =	temp[1].split("<");
										valor	+=	parseFloat(temp1[0].replace(',','.'));
										
										temp	 =	document.getElementById('tabelaVigencia').rows[i].cells[3].innerHTML.split(">");
										temp1	 =	temp[1].split("<");
										desc	+=	parseFloat(temp1[0].replace(',','.'));
										
										temp	 =	document.getElementById('tabelaVigencia').rows[i].cells[4].innerHTML.split(">");
										temp1	 =	temp[1].split("<");
										total	+=	parseFloat(temp1[0].replace(',','.'));
										
										temp	 =	document.getElementById('tabelaVigencia').rows[i].cells[6].innerHTML.split(">");
										temp1	 =	temp[1].split("<");
										repasse	+=	parseFloat(temp1[0].replace(',','.'));
										
										cont++;
									}
									document.getElementById('tabelaVigenciaValor').innerHTML			=	formata_float(Arredonda(valor,2),2).replace('.',',');	
									document.getElementById('tabelaVigenciaValorDesconto').innerHTML	=	formata_float(Arredonda(desc,2),2).replace('.',',');		
									document.getElementById('tabelaVigenciaValorFinal').innerHTML		=	formata_float(Arredonda(total,2),2).replace('.',',');		
									document.getElementById('tabelaRepasse').innerHTML					=	formata_float(Arredonda(repasse,2),2).replace('.',',');	
									document.getElementById('tabelaVigenciaTotal').innerHTML			=	"Total: "+cont;
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
		if(document.formulario.DataInicioVigencia.value==''){
			mensagens(1);
			document.formulario.DataInicioVigencia.focus();
			return false;
		}else{
			if(isData(document.formulario.DataInicioVigencia.value) == false){		
				document.getElementById('DataInicioVigencia').style.backgroundColor = '#C10000';
				document.getElementById('DataInicioVigencia').style.color='#FFFFFF';
				mensagens(27);
				return false;
			}
			else{
				document.getElementById('DataInicioVigencia').style.backgroundColor='#FFFFFF';
				document.getElementById('DataInicioVigencia').style.color='#C10000';
				mensagens(0);
			}
		}
		if(document.formulario.DataTerminoVigencia.value!=""){
			if(isData(document.formulario.DataTerminoVigencia.value) == false){		
				document.getElementById('DataTerminoVigencia').style.backgroundColor = '#C10000';
				document.getElementById('DataTerminoVigencia').style.color='#FFFFFF';
				mensagens(27);
				return false;
			}else{
				document.getElementById('DataBaseCalculo').style.backgroundColor='#FFFFFF';
				document.getElementById('DataBaseCalculo').style.color='#000000';
				mensagens(0);
			}
		}
		if(document.formulario.DataInicioVigencia.value != '' && document.formulario.DataTerminoVigencia.value != ''){
			var dataI = formatDate(document.formulario.DataInicioVigencia.value);
			var dataF = formatDate(document.formulario.DataTerminoVigencia.value);
			if(dataF < dataI){
				document.getElementById('DataInicioVigencia').style.backgroundColor = '#C10000';
				document.getElementById('DataInicioVigencia').style.color='#FFFFFF';
				mensagens(39);
				return false;
			}else{
				document.getElementById('DataInicioVigencia').style.backgroundColor = '#FFFFFF';
				document.getElementById('DataInicioVigencia').style.color='#C10000';
				mensagens(0);
			}
		}
		if(document.formulario.IdContratoTipoVigencia.value==0){
			mensagens(1);
			document.formulario.IdContratoTipoVigencia.focus();
			return false;
		}
		if(document.formulario.Valor.value==''){
			mensagens(1);
			document.formulario.Valor.focus();
			return false;
		}
		if(document.formulario.IdTipoDesconto.value==''){
			mensagens(1);
			document.formulario.IdTipoDesconto.focus();
			return false;
		}
		if(document.formulario.ValorDesconto.value==''){
			mensagens(1);
			document.formulario.ValorDesconto.focus();
			return false;
		}
		if(document.formulario.ValorPercentual.value==''){
			mensagens(1);
			document.formulario.ValorPercentual.focus();
			return false;
		}
		if(document.formulario.ValorRepasseTerceiro.value==''){
			mensagens(1);
			document.formulario.ValorRepasseTerceiro.focus();
			return false;
		}
		mensagens(0);
		return true;
	}
	function verificaLimiteDesconto(valor){
		switch(valor){
			case '1':
				document.getElementById('titLimiteDesconto').innerHTML				=	'Data Limite Desc.';
				document.getElementById('titLimiteDesconto').style.display			=	'block';
				document.getElementById('cpDiaLimiteDesconto').style.display		=	'none';
				document.getElementById('cpDataLimiteDesconto').style.display		=	'block';
				document.getElementById('cpDataLimiteDescontoIco').style.display	=	'block';
				break;
			case '2':
				document.getElementById('titLimiteDesconto').innerHTML				=	'Dia Limite Desconto';
				document.getElementById('titLimiteDesconto').style.display			=	'block';
				document.getElementById('cpDiaLimiteDesconto').style.display		=	'block';
				document.getElementById('cpDataLimiteDesconto').style.display		=	'none';
				document.getElementById('cpDataLimiteDescontoIco').style.display	=	'none';
				break;
			default:
				document.getElementById('titLimiteDesconto').style.display			=	'none';
				document.getElementById('cpDiaLimiteDesconto').style.display		=	'none';
				document.getElementById('cpDataLimiteDesconto').style.display		=	'none';
				document.getElementById('cpDataLimiteDescontoIco').style.display	=	'none';
		}
	}
 
	
	
	
