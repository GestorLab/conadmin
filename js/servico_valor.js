	function excluir(IdServico,DataInicio,listar){
		if(IdServico== '' || IdServico==undefined){
			IdServico = document.formulario.IdServico.value;
		}
		if(DataInicio== '' || DataInicio==undefined){
			DataInicio = document.formulario.DataInicio.value;
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
    
   			url = "files/excluir/excluir_servico_valor.php?IdServico="+IdServico+"&DataInicio="+DataInicio;
			xmlhttp.open("GET", url,true);

			xmlhttp.onreadystatechange = function(){ 

				// Carregando...
				carregando(true);

				if(xmlhttp.readyState == 4){ 
					if(xmlhttp.status == 200){
						if(document.formulario != undefined){
							document.formulario.Erro.value = xmlhttp.responseText;
							if(listar == 'listar'){
								if(parseInt(xmlhttp.responseText) == 7){
									if(formatDate(document.formulario.DataInicio.value) == DataInicio){
										document.formulario.DataInicio.value 			= '';		
										document.formulario.DataTermino.value 			= '';
										document.formulario.ValorRepasseTerceiro.value 	= '';
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
									
									var valor=0, repasse=0, multa=0, aux=0, cont=0;
									for(var i=1; i<(document.getElementById('tabelaValor').rows.length-1); i++){
										if(DataInicio == document.getElementById('tabelaValor').rows[i].accessKey){
											document.getElementById('tabelaValor').deleteRow(i);
											tableMultColor('tabelaValor',document.filtro.corRegRand.value);
											aux = 1
											break;
										}
									}
									if(aux == 1){
										for(var i=1; i<(document.getElementById('tabelaValor').rows.length-1); i++){
											temp	=	document.getElementById('tabelaValor').rows[i].cells[3].innerHTML.split(">");
											temp1	=	temp[1].split("<");
											valor	+=	parseFloat(temp1[0].replace(',','.'));
											
											temp	=	document.getElementById('tabelaValor').rows[i].cells[4].innerHTML.split(">");
											temp1	=	temp[1].split("<");
											repasse	+=	parseFloat(temp1[0].replace(',','.'));
											
											temp	=	document.getElementById('tabelaValor').rows[i].cells[5].innerHTML.split(">");
											temp1	=	temp[1].split("<");
											multa	+=	parseFloat(temp1[0].replace(',','.'));
											cont++;
										}
										document.getElementById('tabelaValorValor').innerHTML			=	formata_float(Arredonda(valor,2),2).replace('.',',');	
										document.getElementById('tabelaValorRep').innerHTML				=	formata_float(Arredonda(repasse,2),2).replace('.',',');	
										document.getElementById('tabelaValorMulta').innerHTML			=	formata_float(Arredonda(multa,2),2).replace('.',',');	
										document.getElementById('tabelaValorTotal').innerHTML			=	"Total: "+cont;
									}
											
									document.getElementById('tabelahelpText2').style.display	=	'block';
									verificaErro2();
								}else{
									document.getElementById('tabelahelpText2').style.display	=	'block';
									verificaErro2();
								}
							}else{
								if(parseInt(xmlhttp.responseText) == 7){
									document.formulario.Acao.value 	= 'inserir';
									url = 'cadastro_servico_valor.php?Erro='+document.formulario.Erro.value+'&IdServico='+IdServico;
									window.location.replace(url);
								}else{
									verificaErro();
								}
							}
						}else{
							var numMsg = parseInt(xmlhttp.responseText);
							mensagens(numMsg);
							if(numMsg == 7){
								var aux = 0, valor=0, repasse=0, multa=0;
								for(var i=0; i<document.getElementById('tableListar').rows.length; i++){
									if(IdServico+"_"+DataInicio == document.getElementById('tableListar').rows[i].accessKey){
										document.getElementById('tableListar').deleteRow(i);
										tableMultColor('tableListar',document.filtro.corRegRand.value);
										aux=1;
										break;
									}
								}	
								if(aux=1){
									for(var i=1; i<(document.getElementById('tableListar').rows.length-1); i++){
										temp	=	document.getElementById('tableListar').rows[i].cells[4].innerHTML.split(">");
										temp1	=	temp[1].split("<");
										valor	+=	parseFloat(temp1[0].replace(',','.'));
											
										temp	=	document.getElementById('tableListar').rows[i].cells[5].innerHTML.split(">");
										temp1	=	temp[1].split("<");
										repasse	+=	parseFloat(temp1[0].replace(',','.'));
											
										temp	=	document.getElementById('tableListar').rows[i].cells[6].innerHTML.split(">");
										temp1	=	temp[1].split("<");
										multa	+=	parseFloat(temp1[0].replace(',','.'));
									}
									document.getElementById('tableListarValor').innerHTML	=	formata_float(Arredonda(valor,2),2).replace('.',',');	
									document.getElementById('tableListarRepasse').innerHTML	=	formata_float(Arredonda(repasse,2),2).replace('.',',');	
									document.getElementById('tableListarMulta').innerHTML	=	formata_float(Arredonda(multa,2),2).replace('.',',');	
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
		if(document.formulario.DataInicio.value==""){
			mensagens(1);
			document.formulario.DataInicio.focus();
			return false;
		}else{
			if(isData(document.formulario.DataInicio.value) == false){		
				document.getElementById('DataInicio').style.backgroundColor = '#C10000';
				document.getElementById('DataInicio').style.color='#FFFFFF';
				mensagens(27);
				return false;
			}
			else{
				document.getElementById('DataInicio').style.backgroundColor='#FFFFFF';
				document.getElementById('DataInicio').style.color='#C10000';
				mensagens(0);
			}
		}
		if(document.formulario.DataTermino.value != ""){
			if(isData(document.formulario.DataTermino.value) == false){		
				document.getElementById('DataTermino').style.backgroundColor = '#C10000';
				document.getElementById('DataTermino').style.color='#FFFFFF';
				document.formulario.DataTermino.focus();
				mensagens(27);
				return false;
			}
			else{
				if(verificaDataFinal('DataInicio',document.formulario.DataInicio.value,document.formulario.DataTermino.value)== false){
					document.formulario.DataInicio.focus();
					mensagens(39);
					return false;	
				}
				document.getElementById('DataTermino').style.backgroundColor='#FFFFFF';
				document.getElementById('DataTermino').style.color='#000000';
				mensagens(0);
			}
		}
		if(document.formulario.Valor.value==""){
			mensagens(1);
			document.formulario.Valor.focus();
			return false;
		}
		if(document.formulario.ValorRepasseTerceiro.value==""){
			mensagens(1);
			document.formulario.ValorRepasseTerceiro.focus();
			return false;
		}
		if(document.formulario.maxQtdMesesFidelidade.value > 0){
			if(document.formulario.MultaFidelidade.value=="" || document.formulario.MultaFidelidade.value=="0,00" || document.formulario.MultaFidelidade.value=="0"){
				mensagens(1);
				document.formulario.MultaFidelidade.focus();
				return false;
			}
		}
		return true;
	}
	function verificaDataFinal(campo,DataInicio,DataFim){
		if(DataInicio != '' && DataFim != ''){
			var dataI = formatDate(DataInicio);
			var dataF = formatDate(DataFim);
			if(dataF < dataI){
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
	function status_inicial(){
		if(document.formulario.ValorRepasseTerceiro.value == ''){
			document.formulario.ValorRepasseTerceiro.value	=	'0,00';
		}
		if(document.formulario.MultaFidelidade.value == ''){
			document.formulario.MultaFidelidade.value	=	'0,00';
		}
	}
	function validar_Data(id,campo){
		if(campo.value == ''){
			document.getElementById(id).style.backgroundColor='#FFFFFF';
			if(id == 'DataInicio'){
				document.getElementById(id).style.color='#C10000';
			}else if(id == 'DataTermino'){
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
			}else if(id == 'DataTermino'){
				document.getElementById(id).style.color='#000000';	
			}
			mensagens(0);
			return true;
		}	
	}
	function inicia(){
		status_inicial();
		document.formulario.IdServico.focus();
	}
	function listarServicoValor(IdServico,Erro){
		while(document.getElementById('tabelaValor').rows.length > 2){
			document.getElementById('tabelaValor').deleteRow(1);
		}		
		
		var tam, linha, c0, c1, c2, c3, c4, c5;
		
		if(IdServico == ''){
			IdServico = 0;
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
	    
	   	url = "xml/servico_valor.php?IdServico="+IdServico;
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
						
						document.getElementById('tabelaValorValor').innerHTML			=	"0,00";	
						document.getElementById('tabelaValorRep').innerHTML				=	"0,00";	
						document.getElementById('tabelaValorMulta').innerHTML			=	"0,00";	
						document.getElementById('tabelaValorTotal').innerHTML			=	"Total: 0";
						
						// Fim de Carregando
						//carregando(false);
					}else{
						var DadosValor, DataInicio, DataTermino, Valor, DescricaoServicoValor, ValorRep=0,ValorTotal=0,ValorRepasseTerceiro,MultaFidelidade,ValorMulta=0;
						for(var i=0; i<xmlhttp.responseXML.getElementsByTagName("DataInicio").length; i++){
				
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
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("ValorRepasseTerceiro")[i]; 
							nameTextNode = nameNode.childNodes[0];
							ValorRepasseTerceiro = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("MultaFidelidade")[i]; 
							nameTextNode = nameNode.childNodes[0];
							MultaFidelidade = nameTextNode.nodeValue;
							
							tam 	= document.getElementById('tabelaValor').rows.length;
							linha	= document.getElementById('tabelaValor').insertRow(tam-1);
						
							if(i%2 != 0){
								linha.style.backgroundColor = "#E2E7ED";
							}
							
							if(Valor == '')						Valor	 = 0;
							if(ValorRepasseTerceiro == '')		ValorRepasseTerceiro	 = 0;
							if(MultaFidelidade == '')			MultaFidelidade	 = 0;
							
							ValorTotal		=	ValorTotal +	parseFloat(Valor);
							ValorRep		=	ValorRep +	parseFloat(ValorRepasseTerceiro);
							ValorMulta		=	ValorMulta +	parseFloat(MultaFidelidade);
							
							linha.accessKey = DataInicio; 
							
							c0	= linha.insertCell(0);	
							c1	= linha.insertCell(1);	
							c2	= linha.insertCell(2);	
							c3	= linha.insertCell(3);
							c4	= linha.insertCell(4);
							c5	= linha.insertCell(5);
							c6	= linha.insertCell(6);
							
							var linkIni = "<a onClick=\"busca_servico_valor("+IdServico+",false,'"+document.formulario.Local.value+"','"+DataInicio+"')\">";
							var linkFim = "</a>";
							
							c0.innerHTML = linkIni + dateFormat(DataInicio) + linkFim;
							c0.style.cursor  = "pointer";
							c0.style.padding =	"0 0 0 5px";
							
							c1.innerHTML = linkIni + dateFormat(DataTermino) + linkFim;
							c1.style.cursor = "pointer";
							
							c2.innerHTML = linkIni + DescricaoServicoValor + linkFim;
							c2.style.cursor = "pointer";
							
							c3.innerHTML = linkIni + formata_float(Valor).replace('.',',') + linkFim;
							c3.style.textAlign = "right";
							c3.style.cursor = "pointer";
							c3.style.padding =	"0 8px 0 0";
							
							c4.innerHTML = linkIni + formata_float(ValorRepasseTerceiro).replace('.',',') + linkFim;
							c4.style.textAlign = "right";
							c4.style.cursor = "pointer";
							c4.style.padding =	"0 8px 0 0";
							
							c5.innerHTML = linkIni + formata_float(MultaFidelidade).replace('.',',') + linkFim;
							c5.style.textAlign = "right";
							c5.style.cursor = "pointer";
							c5.style.padding =	"0 8px 0 0";
							
							c6.innerHTML = "<img src='../../img/estrutura_sistema/ico_del.gif' alt='Excluir?' onClick=\"excluir("+IdServico+",'"+DataInicio+"','listar')\">";
							c6.style.textAlign = "center";
							c6.style.cursor = "pointer";
						}
						document.getElementById('tabelaValorValor').innerHTML			=	formata_float(Arredonda(ValorTotal,2)).replace('.',',');	
						document.getElementById('tabelaValorRep').innerHTML				=	formata_float(Arredonda(ValorRep,2)).replace('.',',');		
						document.getElementById('tabelaValorMulta').innerHTML			=	formata_float(Arredonda(ValorMulta,2)).replace('.',',');	
						document.getElementById('tabelaValorTotal').innerHTML			=	"Total: "+i;
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
	function mensagens2(n,Local){
		var msg='';
		var prioridade='';
		
		if(Local == '' || Local == undefined){
			Local = '';
		}
		if(n == 0){
			return help(msg,prioridade);
		}
		
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
		url = "../../xml/mensagens.xml";
   		xmlhttp.open("GET", url,true);
   		xmlhttp.onreadystatechange = function(){ 
   			if(xmlhttp.readyState == 4){ 
				if(xmlhttp.status == 200){
					nameNode = xmlhttp.responseXML.getElementsByTagName("msg"+n)[0]; 
					if(nameNode != null){
						nameTextNode = nameNode.childNodes[0];
						msg = nameTextNode.nodeValue;
					}else{
						msg = '';
					}
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("pri"+n)[0]; 
					if(nameNode != null){
						nameTextNode = nameNode.childNodes[0];
						prioridade = nameTextNode.nodeValue;
					}else{
						prioridade = '';
					}
					
					return help2(msg,prioridade);
				}
			}
		}
		xmlhttp.send(null);
	}
	function verificaErro2(){
		var nerro = parseInt(document.formulario.Erro.value);
		mensagens2(nerro,document.formulario.Local.value);
	}
	function help2(msg,prioridade){
		if(msg!=''){
			scrollWindow("bottom");
		}
		document.getElementById('helpText2').innerHTML = msg;
		document.getElementById('helpText2').style.display = "block";
		switch (prioridade){
			case 'atencao':
				document.getElementById('helpText2').style.color = "#C10000";
				return true;
			default:
				document.getElementById('helpText2').style.color = "#004975";
				return true;
		}
	}
	function alterarContrato(){
		return confirm("ATENCAO!\n\nVocê alterou o valor do serviço. Deseja alterar automaticamente o valor de todos\n os contratos deste serviço?","SIM","NAO");
	}
	
	function verificaErro(){
		var nerro = parseInt(document.formulario.Erro.value);
		mensagens(nerro,document.formulario.Local.value);
		
		if(nerro == 103){
			 if(alterarContrato()==true){
			 	window.location.replace("rotinas/editar_valor_servico_contrato.php?IdServico="+document.formulario.IdServico.value+"&DataInicio="+document.formulario.DataInicio.value);
			}
		}
	}

