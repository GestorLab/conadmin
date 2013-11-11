	function inicia(){
		status_inicial();
		document.formulario.IdContrato.focus();
	}
	function listarParametro(IdServico,Erro,IdContrato){
		while(document.getElementById('tabelaParametro').rows.length > 0){
			document.getElementById('tabelaParametro').deleteRow(0);
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
	    url = "xml/contrato_parametro.php?IdServico="+IdServico+"&IdContrato="+IdContrato+"&IdStatus=1";
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
					if(xmlhttp.responseText == "false"){		
						document.getElementById('cp_parametrosServico').style.display	=	'none';
						// Fim de Carregando
						carregando(false);
					}else{
						var obsTemp = new Array(), cont=0,invisivel="";
						for(var i=0; i<xmlhttp.responseXML.getElementsByTagName("IdParametroServico").length; i++){
						
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoParametroServico")[i]; 
							nameTextNode = nameNode.childNodes[0];
							var DescricaoParametroServico = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("Obrigatorio")[i]; 
							nameTextNode = nameNode.childNodes[0];
							var Obrigatorio = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("Editavel")[i]; 
							nameTextNode = nameNode.childNodes[0];
							var Editavel = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("Valor")[i]; 
							nameTextNode = nameNode.childNodes[0];
							var Valor = nameTextNode.nodeValue;	
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("ValorDefault")[i]; 
							nameTextNode = nameNode.childNodes[0];
							var ValorDefault = nameTextNode.nodeValue;	
														
							nameNode = xmlhttp.responseXML.getElementsByTagName("IdParametroServico")[i]; 
							nameTextNode = nameNode.childNodes[0];
							var IdParametroServico = nameTextNode.nodeValue;
						
							nameNode = xmlhttp.responseXML.getElementsByTagName("Obs")[i]; 
							nameTextNode = nameNode.childNodes[0];
							var Obs = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("IdTipoParametro")[i]; 
							nameTextNode = nameNode.childNodes[0];
							var IdTipoParametro = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("IdMascaraCampo")[i]; 
							nameTextNode = nameNode.childNodes[0];
							var IdMascaraCampo = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("OpcaoValor")[i]; 
							nameTextNode = nameNode.childNodes[0];
							var OpcaoValor = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("Visivel")[i]; 
							nameTextNode = nameNode.childNodes[0];
							var Visivel = nameTextNode.nodeValue;
							
							if(Valor == '' && ValorDefault != ''){
								Valor	=	ValorDefault;
							}
							
							if(Visivel == '1'){
								obsTemp[cont]	=	Obs;
								
								tam 	 = document.getElementById('tabelaParametro').rows.length;
	
								if(cont%2 == 0){
									linha	 = document.getElementById('tabelaParametro').insertRow(tam);
									tabindex = 20 + cont + 1;
									pos		 = 0;
									padding	 = 22;
								}else{	
									padding	 = 10;
									tabindex = 20 + cont;
									pos		 = 1;
									if(obsTemp[(cont-1)]!= undefined && obsTemp[(cont-1)]!= ''){
										if(Obs	==	'')	Obs	=	'<BR>';
									}
								}
								
								if((cont+1) == xmlhttp.responseXML.getElementsByTagName("IdParametroServico").length && cont%2 == 0){
									padding	 = 22;	
								}
								
								if(Obrigatorio == 1){
									color = "#C10000";
								}else{
									color = "#000000";
								}
								
								if(Editavel == 2){
									disabled	=	"readOnly";
								}else{
									disabled	=	"";
								}
								
								linha.accessKey = IdParametroServico; 
	
								c0	= linha.insertCell(pos);
								
								
								if(IdTipoParametro == 1){
									switch(IdMascaraCampo){
										case '1': //Data
											c0.innerHTML = "<p style='margin:0; padding-bottom:1px; padding-left:"+padding+"px'><B style='color:"+color+";'>"+DescricaoParametroServico+"</B></p><p style='padding-bottom:6px; padding-left:"+padding+"px; margin:0;'><input type='text' name='Valor_"+IdParametroServico+"' value='"+Valor+"' style='width:399px;' autocomplete='off' onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out');\" "+disabled+" onkeypress=\"mascara(this,event,'date')\" maxlength='10' tabindex="+tabindex+"><input type='hidden' name='Obrigatorio_"+IdParametroServico+"' value='"+Obrigatorio+"'><input type='hidden' name='Visivel_"+IdParametroServico+"' value='"+Visivel+"'><BR>"+Obs+"</p>";
											break;
										case '2': //Inteiro
											c0.innerHTML = "<p style='margin:0; padding-bottom:1px; padding-left:"+padding+"px'><B style='color:"+color+";'>"+DescricaoParametroServico+"</B></p><p style='padding-bottom:6px; padding-left:"+padding+"px; margin:0;'><input type='text' name='Valor_"+IdParametroServico+"' value='"+Valor+"' style='width:399px;' autocomplete='off' onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out');\" "+disabled+" onkeypress=\"mascara(this,event,'int')\" tabindex="+tabindex+"><input type='hidden' name='Obrigatorio_"+IdParametroServico+"' value='"+Obrigatorio+"'><input type='hidden' name='Visivel_"+IdParametroServico+"' value='"+Visivel+"'><BR>"+Obs+"</p>";
											break;
										case '3': //Real
											c0.innerHTML = "<p style='margin:0; padding-bottom:1px; padding-left:"+padding+"px'><B style='color:"+color+";'>"+DescricaoParametroServico+"</B></p><p style='padding-bottom:6px; padding-left:"+padding+"px; margin:0;'><input type='text' name='Valor_"+IdParametroServico+"' value='"+Valor+"' style='width:399px;' autocomplete='off' onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out');\" "+disabled+" onkeypress=\"mascara(this,event,'float')\" maxlength='12' tabindex="+tabindex+"><input type='hidden' name='Obrigatorio_"+IdParametroServico+"' value='"+Obrigatorio+"'><input type='hidden' name='Visivel_"+IdParametroServico+"' value='"+Visivel+"'><BR>"+Obs+"</p>";
											break;
										case '5': //MAC
											c0.innerHTML = "<p style='margin:0; padding-bottom:1px; padding-left:"+padding+"px'><B style='color:"+color+";'>"+DescricaoParametroServico+"</B></p><p style='padding-bottom:6px; padding-left:"+padding+"px; margin:0;'><input type='text' name='Valor_"+IdParametroServico+"' value='"+Valor+"' style='width:399px;' autocomplete='off' onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out');\" "+disabled+" onkeypress=\"mascara(this,event,'mac')\" maxlength='17' tabindex="+tabindex+"><input type='hidden' name='Obrigatorio_"+IdParametroServico+"' value='"+Obrigatorio+"'><input type='hidden' name='Visivel_"+IdParametroServico+"' value='"+Visivel+"'><BR>"+Obs+"</p>";
											break;
										default:
											c0.innerHTML = "<p style='margin:0; padding-bottom:1px; padding-left:"+padding+"px'><B style='color:"+color+";'>"+DescricaoParametroServico+"</B></p><p style='padding-bottom:6px; padding-left:"+padding+"px; margin:0;'><input type='text' name='Valor_"+IdParametroServico+"' value='"+Valor+"' style='width:399px;' autocomplete='off' maxlength='255' onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out');\" "+disabled+" tabindex="+tabindex+"><input type='hidden' name='Obrigatorio_"+IdParametroServico+"' value='"+Obrigatorio+"'><input type='hidden' name='Visivel_"+IdParametroServico+"' value='"+Visivel+"'><BR>"+Obs+"</p>";
											break;
									}
								}else{
									campo  = "<p style='margin:0; padding-bottom:1px; padding-left:"+padding+"px'>";
									campo +=	"<B style='color:"+color+";'>"+DescricaoParametroServico+"</B></p>";
									campo += "<p style='padding-bottom:6px; padding-left:"+padding+"px; margin:0;'>";
									
									if(Editavel == 2){
										disabled	=	"disabled";
									}else{
										disabled	=	"";
									}
									
									campo +=	"<select name='Valor_"+IdParametroServico+"'  style='width:406px;' onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out');\" "+disabled+" tabindex="+tabindex+">";
									campo += "<option value=''></option>";
												
									valor	=	OpcaoValor.split("\n");
									for(var ii=0; ii<valor.length; ii++){
										selecionado = "";
										if(Valor == valor[ii]){
											selecionado	=	"selected=true";
										}
										campo +=	"<option value='"+valor[ii]+"' "+selecionado+">"+valor[ii]+"</option>";
									}
									campo += "</select>";
									campo +=	"<input type='hidden' name='Obrigatorio_"+IdParametroServico+"' value='"+Obrigatorio+"'><input type='hidden' name='Visivel_"+IdParametroServico+"' value='"+Visivel+"'>";
									campo +=	"<BR>"+Obs+"</p>";
									
									c0.innerHTML	=	campo;
								}
								
								cont++;
							}else{
								invisivel	+=	"<div style='display:none'>";
								if(IdTipoParametro == 1){
									invisivel += "<input type='text' name='Valor_"+IdParametroServico+"' value='"+Valor+"' style='width:399px;' autocomplete='off'><input type='hidden' name='Obrigatorio_"+IdParametroServico+"' value='"+Obrigatorio+"'><input type='hidden' name='Visivel_"+IdParametroServico+"' value='"+Visivel+"'></p>";
								}else{
									campo += "<p>";
									campo +=	"<select name='Valor_"+IdParametroServico+"'  style='width:406px;'>";
									campo += "<option value=''></option>";
												
									valor	=	OpcaoValor.split("\n");
									for(var ii=0; ii<valor.length; ii++){
										selecionado = "";
										if(ValorDefault == valor[ii]){
											selecionado	=	"selected=true";
										}
										campo +=	"<option value='"+valor[ii]+"' "+selecionado+">"+valor[ii]+"</option>";
									}
									campo += "</select>";
									campo +=	"<input type='hidden' name='Obrigatorio_"+IdParametroServico+"' value='"+Obrigatorio+"'><input type='hidden' name='Visivel_"+IdParametroServico+"' value='"+Visivel+"'>";
									
									invisivel	+=	campo;
								}
							}
						}
						
						if(cont > 0){	
							document.getElementById('cp_parametrosServico').style.display	=	'block';
						}
						
						if(invisivel !=""){
							tam 	 = document.getElementById('tabelaParametro').rows.length;
							linha	 = document.getElementById('tabelaParametro').insertRow(tam);
							
							linha.accessKey = IdParametroServico; 
							
							c0	= linha.insertCell(0);
							c0.innerHTML	=	invisivel;
						}
						
						if(document.formulario.Erro.value != '' && document.formulario.Erro.value != false){
							scrollWindow('bottom');
						}
					}	
					
//					for(i=50;i<document.formulario.length;i++){
//						alert(document.formulario[i].name+" -> "+document.formulario[i].value);
//					}
					
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
	function excluir(IdContrato,IdStatus){
		if(IdStatus  == 1){
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
	    
	   			url = "files/excluir/excluir_contrato.php?IdContrato="+IdContrato;
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
									url = 'cadastro_contrato.php?Erro='+document.formulario.Erro.value;
									window.location.replace(url);
								}else{
									verificaErro();
								}
							}else{
//							alert(xmlhttp.responseText);
								var temp   = xmlhttp.responseText.split("_");
								if(temp.length>0){
									var numMsg = parseInt(temp[0]);
								}else{
									var numMsg = parseInt(xmlhttp.responseText);
								}
								mensagens(numMsg);
								if(numMsg == 7){
									var aux = 0, valor=0, desc=0, total=0;
									for(ii=1;ii<temp.length;ii++){
										IdContrato	=	temp[ii];
										for(var i=0; i<document.getElementById('tableListar').rows.length; i++){
											if(IdContrato == document.getElementById('tableListar').rows[i].accessKey){
												document.getElementById('tableListar').deleteRow(i);
												tableMultColor('tableListar',document.filtro.corRegRand.value);
												aux=1;
												break;
											}
										}	
									}
									if(aux=1){
										for(var i=1; i<(document.getElementById('tableListar').rows.length-1); i++){
											temp	=	document.getElementById('tableListar').rows[i].cells[8].innerHTML.split(">");
											temp1	=	temp[1].split("<");
											temp2	=	temp1[0].replace(',','.');
											if(temp2=='') temp2 = 0;
											valor	+=	parseFloat(temp2);
												
											temp	=	document.getElementById('tableListar').rows[i].cells[9].innerHTML.split(">");
											temp1	=	temp[1].split("<");
											temp2	=	temp1[0].replace(',','.');
											if(temp2=='') temp2 = 0;
											desc	+=	parseFloat(temp2);
												
											temp	=	document.getElementById('tableListar').rows[i].cells[10].innerHTML.split(">");
											temp1	=	temp[1].split("<");
											temp2	=	temp1[0].replace(',','.');
											if(temp2=='') temp2 = 0;
											total	+=	parseFloat(temp2);
										}
										document.getElementById('tableListarValor').innerHTML		=	formata_float(Arredonda(valor,2),2).replace('.',',');	
										document.getElementById('tableListarDesconto').innerHTML	=	formata_float(Arredonda(desc,2),2).replace('.',',');	
										document.getElementById('tableListarFinal').innerHTML		=	formata_float(Arredonda(total,2),2).replace('.',',');
										document.getElementById("tableListarTotal").innerHTML		=	"Total: "+(document.getElementById('tableListar').rows.length-2);
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
		}else{
			return false;
		}
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
	function mudar_servico(){
		if(document.formulario.IdContrato.value == '' || document.formulario.IdStatus.value == 1){
			return false;
		}
		window.location.replace("cadastro_contrato_servico.php?IdContrato="+document.formulario.IdContrato.value);
	}

	function cadastrar(acao){
		document.formulario.Acao.value	=	acao;
		if(acao == 'inserir' || acao == 'alterar' || acao == 'receber'){
			if(validar(acao)==true){
				document.formulario.submit();
			}
		}else{
			document.formulario.submit();
		}
	}
	function validar_Data(id,campo){
		if(campo.value == ''){
			document.getElementById(id).style.backgroundColor='#FFFFFF';
			if(id=='DataInicio' || id=='DataPrimeiraCobranca'){
				document.getElementById(id).style.color='#C10000';
			}else{
				document.getElementById(id).style.color='#000';
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
			if(id=='DataInicio' || id=='DataPrimeiraCobranca'){
				document.getElementById(id).style.color='#C10000';
			}else{
				document.getElementById(id).style.color='#000';
			}
			if(id == 'DataTermino'){
				if(document.formulario.DataUltimaCobranca.value == ''){
					document.formulario.DataUltimaCobranca.value = campo.value;
				}
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
	function busca_status(IdStatus,VarStatus){
		if(IdStatus == undefined){
			IdStatus = 0;
		}
		if(VarStatus == undefined){
			VarStatus = '';
		}
		var xmlhttp = false;
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
	    
	    url = "xml/parametro_sistema.php?IdGrupoParametroSistema=69&IdParametroSistema="+IdStatus;
		
		if(IdStatus == '201'){
			url	+=	"&VarStatus="+VarStatus;
		}
		
		xmlhttp.open("GET", url,true);
	    
		// Carregando...
		carregando(true);
		
		xmlhttp.onreadystatechange = function(){ 
			if(xmlhttp.readyState == 4){ 
				if(xmlhttp.status == 200){
					if(xmlhttp.responseText != 'false'){		
						var nameNode, nameTextNode, ValorParametroSistema, cor;					
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("ValorParametroSistema")[0]; 
						nameTextNode = nameNode.childNodes[0];
						ValorParametroSistema = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("Cor")[0]; 
						nameTextNode = nameNode.childNodes[0];
						Cor = nameTextNode.nodeValue;
						
						document.getElementById('cpStatusContrato').style.display	=	'block';	
						document.getElementById('cpStatusContrato').innerHTML 		= 	"<a style='color:"+Cor+"' href='cadastro_contrato_status.php?IdContrato="+document.formulario.IdContrato.value+"'>"+ValorParametroSistema+"</a>";
					}else{
						document.getElementById('cpStatusContrato').style.display	=	'none';	
					}
					// Fim de Carregando
					carregando(false);
					
				}
			}
		}
		xmlhttp.send(null);	
	}
	function mudar_status(){
		if(document.formulario.IdContrato.value == ''){
			return false;
		}
		window.location.replace("cadastro_contrato_status.php?IdContrato="+document.formulario.IdContrato.value);
	}
	function verificaAcao(){
		if(document.formulario != undefined){
			if(document.formulario.Acao.value=='inserir'){			
				document.formulario.bt_inserir.disabled 	= false;
				document.formulario.bt_alterar.disabled 	= true;
				document.formulario.bt_excluir.disabled 	= true;
			}
			if(document.formulario.Acao.value=='alterar'){			
				document.formulario.bt_inserir.disabled 	= true;
				
				//Cancelado
				if(document.formulario.IdStatus.value == 1){
					document.formulario.bt_alterar.disabled 	= true;
					document.formulario.bt_excluir.disabled 	= false;
				}else{
					document.formulario.bt_alterar.disabled 	= false;
					document.formulario.bt_excluir.disabled 	= true;
				}
			}
		}	
	}
	function listarParametroLocalCobranca(IdLocalCobranca,Erro,IdContrato){
		while(document.getElementById('tabelaParametroLocalCobranca').rows.length > 0){
			document.getElementById('tabelaParametroLocalCobranca').deleteRow(0);
		}		
		
		var tam, linha, c0, c1, c2, c3, c4, c5;
		
		if(IdLocalCobranca == ''){
			IdLocalCobranca = 0;
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
	    url = "xml/contrato_parametro_local_cobranca.php?IdLocalCobranca="+IdLocalCobranca+"&IdContrato="+IdContrato+"&IdStatus=1&Visivel=1";

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
					if(xmlhttp.responseText == "false"){		
						document.getElementById('cp_parametrosLocalCobranca').style.display	=	'none';
						// Fim de Carregando
						carregando(false);
					}else{
						var obsTemp = new Array(), invisivel="",cont=0;
						for(var i=0; i<xmlhttp.responseXML.getElementsByTagName("IdLocalCobrancaParametroContrato").length; i++){
							
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("IdLocalCobrancaParametroContrato")[i]; 
							nameTextNode = nameNode.childNodes[0];
							var IdLocalCobrancaParametroContrato = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoParametroContrato")[i]; 
							nameTextNode = nameNode.childNodes[0];
							var DescricaoParametroContrato = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("Obrigatorio")[i]; 
							nameTextNode = nameNode.childNodes[0];
							var Obrigatorio = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("Editavel")[i]; 
							nameTextNode = nameNode.childNodes[0];
							var Editavel = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("Valor")[i]; 
							nameTextNode = nameNode.childNodes[0];
							var Valor = nameTextNode.nodeValue;	
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("ValorDefault")[i]; 
							nameTextNode = nameNode.childNodes[0];
							var ValorDefault = nameTextNode.nodeValue;	
						
							nameNode = xmlhttp.responseXML.getElementsByTagName("Obs")[i]; 
							nameTextNode = nameNode.childNodes[0];
							var Obs = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("IdTipoParametro")[i]; 
							nameTextNode = nameNode.childNodes[0];
							var IdTipoParametro = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("IdMascaraCampo")[i]; 
							nameTextNode = nameNode.childNodes[0];
							var IdMascaraCampo = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("OpcaoValor")[i]; 
							nameTextNode = nameNode.childNodes[0];
							var OpcaoValor = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("Visivel")[i]; 
							nameTextNode = nameNode.childNodes[0];
							var Visivel = nameTextNode.nodeValue;
							
							if(Valor == '' && ValorDefault != ''){
								Valor	=	ValorDefault;
							}
							
							if(Visivel == '1'){
								obsTemp[i]	=	Obs;
								
								tam 	 = document.getElementById('tabelaParametroLocalCobranca').rows.length;
	
								if(cont%2 == 0){
									linha	 = document.getElementById('tabelaParametroLocalCobranca').insertRow(tam);
									tabindex = 100 + cont + 1;
									pos		 = 0;
									padding	 = 22;
								}else{	
									padding	 = 10;
									tabindex = 100 + cont;
									pos		 = 1;
									if(obsTemp[(cont-1)]!= undefined && obsTemp[(cont-1)]!= ''){
										if(Obs	==	'')	Obs	=	'<BR>';
									}
								}
								
								if((cont+1) == xmlhttp.responseXML.getElementsByTagName("IdLocalCobrancaParametroContrato").length && cont%2 == 0){
									padding	 = 22;	
								}
								
								if(Obrigatorio == 1){
									color = "#C10000";
								}else{
									color = "#000000";
								}
								
								if(Editavel == 2){
									disabled	=	"readOnly";
								}else{
									disabled	=	"";
								}
								
								linha.accessKey = IdLocalCobrancaParametroContrato; 
	
								c0	= linha.insertCell(pos);
								
								
								if(IdTipoParametro == 1){
									switch(IdMascaraCampo){
										case '1': //Data
											c0.innerHTML = "<p style='margin:0; padding-bottom:1px; padding-left:"+padding+"px'><B style='color:"+color+";'>"+DescricaoParametroContrato+"</B></p><p style='padding-bottom:6px; padding-left:"+padding+"px; margin:0;'><input type='text' name='LocalCobranca_Valor_"+IdLocalCobrancaParametroContrato+"' value='"+Valor+"' style='width:399px;' autocomplete='off' onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out');\" "+disabled+" onkeypress=\"mascara(this,event,'date')\" maxlength='10' tabindex="+tabindex+"><input type='hidden' name='LocalCobranca_Obrigatorio_"+IdLocalCobrancaParametroContrato+"' value='"+Obrigatorio+"'><input type='hidden' name='LocalCobranca_Visivel_"+IdLocalCobrancaParametroContrato+"' value='"+Visivel+"'><BR>"+Obs+"</p>";
											break;
										case '2': //Inteiro
											c0.innerHTML = "<p style='margin:0; padding-bottom:1px; padding-left:"+padding+"px'><B style='color:"+color+";'>"+DescricaoParametroContrato+"</B></p><p style='padding-bottom:6px; padding-left:"+padding+"px; margin:0;'><input type='text' name='LocalCobranca_Valor_"+IdLocalCobrancaParametroContrato+"' value='"+Valor+"' style='width:399px;' autocomplete='off' onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out');\" "+disabled+" onkeypress=\"mascara(this,event,'int')\" tabindex="+tabindex+"><input type='hidden' name='LocalCobranca_Obrigatorio_"+IdLocalCobrancaParametroContrato+"' value='"+Obrigatorio+"'><input type='hidden' name='LocalCobranca_Visivel_"+IdLocalCobrancaParametroContrato+"' value='"+Visivel+"'><BR>"+Obs+"</p>";
											break;
										case '3': //Real
											c0.innerHTML = "<p style='margin:0; padding-bottom:1px; padding-left:"+padding+"px'><B style='color:"+color+";'>"+DescricaoParametroContrato+"</B></p><p style='padding-bottom:6px; padding-left:"+padding+"px; margin:0;'><input type='text' name='LocalCobranca_Valor_"+IdLocalCobrancaParametroContrato+"' value='"+Valor+"' style='width:399px;' autocomplete='off' onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out');\" "+disabled+" onkeypress=\"mascara(this,event,'float')\" maxlength='12' tabindex="+tabindex+"><input type='hidden' name='LocalCobranca_Obrigatorio_"+IdLocalCobrancaParametroContrato+"' value='"+Obrigatorio+"'><input type='hidden' name='LocalCobranca_Visivel_"+IdLocalCobrancaParametroContrato+"' value='"+Visivel+"'><BR>"+Obs+"</p>";
											break;
										case '5': //MAC
											c0.innerHTML = "<p style='margin:0; padding-bottom:1px; padding-left:"+padding+"px'><B style='color:"+color+";'>"+DescricaoParametroContrato+"</B></p><p style='padding-bottom:6px; padding-left:"+padding+"px; margin:0;'><input type='text' name='LocalCobranca_Valor_"+IdLocalCobrancaParametroContrato+"' value='"+Valor+"' style='width:399px;' autocomplete='off' onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out');\" "+disabled+" onkeypress=\"mascara(this,event,'mac')\" maxlength='17' tabindex="+tabindex+"><input type='hidden' name='LocalCobranca_Obrigatorio_"+IdLocalCobrancaParametroContrato+"' value='"+Obrigatorio+"'><input type='hidden' name='LocalCobranca_Visivel_"+IdLocalCobrancaParametroContrato+"' value='"+Visivel+"'><BR>"+Obs+"</p>";
											break;
										default:
											c0.innerHTML = "<p style='margin:0; padding-bottom:1px; padding-left:"+padding+"px'><B style='color:"+color+";'>"+DescricaoParametroContrato+"</B></p><p style='padding-bottom:6px; padding-left:"+padding+"px; margin:0;'><input type='text' name='LocalCobranca_Valor_"+IdLocalCobrancaParametroContrato+"' value='"+Valor+"' style='width:399px;' autocomplete='off' maxlength='255' onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out');\" "+disabled+" tabindex="+tabindex+"><input type='hidden' name='LocalCobranca_Obrigatorio_"+IdLocalCobrancaParametroContrato+"' value='"+Obrigatorio+"'><input type='hidden' name='LocalCobranca_Visivel_"+IdLocalCobrancaParametroContrato+"' value='"+Visivel+"'><BR>"+Obs+"</p>";
									}
								}else{
									campo  = "<p style='margin:0; padding-bottom:1px; padding-left:"+padding+"px'>";
									campo +=	"<B style='color:"+color+";'>"+DescricaoParametroContrato+"</B></p>";
									campo += "<p style='padding-bottom:6px; padding-left:"+padding+"px; margin:0;'>";
									
									if(Editavel == 2){
										disabled	=	"disabled";
									}else{
										disabled	=	"";
									}
									
									campo +=	"<select name='LocalCobranca_Valor_"+IdLocalCobrancaParametroContrato+"'  style='width:406px;' onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out');\" "+disabled+" tabindex="+tabindex+">";
									campo += "<option value=''></option>";
												
									valor	=	OpcaoValor.split("\n");
									for(var ii=0; ii<valor.length; ii++){
										selecionado = "";
										if(Valor == valor[ii]){
											selecionado	=	"selected=true";
										}
										campo +=	"<option value='"+valor[ii]+"' "+selecionado+">"+valor[ii]+"</option>";
									}
									campo += "</select>";
									campo +=	"<input type='hidden' name='LocalCobranca_Obrigatorio_"+IdLocalCobrancaParametroContrato+"' value='"+Obrigatorio+"'><input type='hidden' name='LocalCobranca_Visivel_"+IdLocalCobrancaParametroContrato+"' value='"+Visivel+"'>";
									campo +=	"<BR>"+Obs+"</p>";
									
									c0.innerHTML	=	campo;
								}								
								cont++;
							}else{
								invisivel	+=	"<div style='display:none'>";
								if(IdTipoParametro == 1){
									invisivel += "<input type='text' name='LocalCobranca_Valor_"+IdLocalCobrancaParametroContrato+"' value='"+ValorDefault+"' style='width:399px;' autocomplete='off'><input type='hidden' name='LocalCobranca_Obrigatorio_"+IdLocalCobrancaParametroContrato+"' value='"+Obrigatorio+"'><input type='hidden' name='LocalCobranca_Visivel_"+IdLocalCobrancaParametroContrato+"' value='"+Visivel+"'>";
								}else{
									campo += "<p>";
									campo +=	"<select name='LocalCobranca_Valor_"+IdLocalCobrancaParametroContrato+"'  style='width:406px;'>";
									campo += "<option value=''></option>";
												
									valor	=	OpcaoValor.split("\n");
									for(var ii=0; ii<valor.length; ii++){
										selecionado = "";
										if(ValorDefault == valor[ii]){
											selecionado	=	"selected=true";
										}
										campo +=	"<option value='"+valor[ii]+"' "+selecionado+">"+valor[ii]+"</option>";
									}
									campo += "</select>";
									campo +=	"<input type='hidden' name='LocalCobranca_Obrigatorio_"+IdLocalCobrancaParametroContrato+"' value='"+Obrigatorio+"'><input type='hidden' name='LocalCobranca_Visivel_"+IdLocalCobrancaParametroContrato+"' value='"+Visivel+"'>";
									
									invisivel	+=	campo;
								}
							
							
								invisivel	+=	"</div>";
							}
						}
						if(cont > 0){
							document.getElementById('cp_parametrosLocalCobranca').style.display	=	'block';
						}
						if(invisivel !=""){
							tam 	 = document.getElementById('tabelaParametroLocalCobranca').rows.length;
							linha	 = document.getElementById('tabelaParametroLocalCobranca').insertRow(tam);
								
							linha.accessKey = IdLocalCobrancaParametroContrato; 
								
							c0	= linha.insertCell(0);
							c0.innerHTML	=	invisivel;
						}
						
						if(document.formulario.Erro.value != '' && document.formulario.Erro.value != false){
							scrollWindow('bottom');
						}
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
	function busca_dia_cobranca(IdPessoa,DiaCobrancaDefault){
		if(IdPessoa == ""){
			IdPessoa = 0;
		}
		if(DiaCobrancaDefault == undefined){
			DiaCobrancaDefault = 0;
		}
		var xmlhttp = false;
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
	    
	    url = "xml/dia_cobranca.php?IdPessoa="+IdPessoa;
		
		xmlhttp.open("GET", url,true);
	    
		// Carregando...
		carregando(true);
		
		xmlhttp.onreadystatechange = function(){ 
			if(xmlhttp.readyState == 4){ 
				if(xmlhttp.status == 200){
					if(xmlhttp.responseText != 'false'){		
						while(document.formulario.DiaCobranca.options.length > 0){
							document.formulario.DiaCobranca.options[0] = null;
						}
						
						var nameNode, nameTextNode, DiaCobranca;					
						
						addOption(document.formulario.DiaCobranca,"","0");
						
						for(var i=0; i<xmlhttp.responseXML.getElementsByTagName("ValorCodigoInterno").length; i++){
							nameNode = xmlhttp.responseXML.getElementsByTagName("DiaCobranca")[i]; 
							nameTextNode = nameNode.childNodes[0];
							DiaCobranca = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("ValorCodigoInterno")[i]; 
							nameTextNode = nameNode.childNodes[0];
							ValorCodigoInterno = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoCodigoInterno")[i]; 
							nameTextNode = nameNode.childNodes[0];
							DescricaoCodigoInterno = nameTextNode.nodeValue;
						
							addOption(document.formulario.DiaCobranca,DescricaoCodigoInterno,ValorCodigoInterno);
							
							
						}
						if(DiaCobranca==""){
							DiaCobranca	=	DiaCobrancaDefault;		
						}
						
						
						for(i=0;i<document.formulario.DiaCobranca.length;i++){
							if(document.formulario.DiaCobranca[i].value == DiaCobranca){
								document.formulario.DiaCobranca[i].selected	=	true;
								break;
							}
						}
					}else{
						while(document.formulario.DiaCobranca.options.length > 0){
							document.formulario.DiaCobranca.options[0] = null;
						}
					}
					// Fim de Carregando
					carregando(false);
				}
			}
		}
		xmlhttp.send(null);	
	}
	function atualizarHistorico(IdContrato){
		if(IdContrato == undefined){
			IdContrato = 0;
		}
		var xmlhttp = false;
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
	    
	    url = "xml/contrato.php?IdContrato="+IdContrato;
		
		xmlhttp.open("GET", url,true);
	    
		// Carregando...
		carregando(true);
		
		xmlhttp.onreadystatechange = function(){ 
			if(xmlhttp.readyState == 4){ 
				if(xmlhttp.status == 200){
					if(xmlhttp.responseText != 'false'){		
						var nameNode, nameTextNode, Obs, IdStatus, VarStatus;					
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("Obs")[0]; 
						nameTextNode = nameNode.childNodes[0];
						Obs = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("IdStatus")[0]; 
						nameTextNode = nameNode.childNodes[0];
						IdStatus = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("VarStatus")[0]; 
						nameTextNode = nameNode.childNodes[0];
						VarStatus = nameTextNode.nodeValue;
						
						document.formulario.HistoricoObs.value				=	Obs;
						busca_status(IdStatus,VarStatus);
					}
					// Fim de Carregando
					carregando(false);
				}
			}
		}
		xmlhttp.send(null);	
	}
	function selecionaVencimento(IdContratoAgrupador){
		if(IdContratoAgrupador == undefined || IdContratoAgrupador == ""){
			IdContratoAgrupador = 0;
			
			document.formulario.DiaCobranca.disabled	=	false;
		}else{
			var xmlhttp = false;
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
		    
		    url = "xml/contrato.php?IdContrato="+IdContratoAgrupador;
			
			xmlhttp.open("GET", url,true);
		    
			// Carregando...
			carregando(true);
			
			xmlhttp.onreadystatechange = function(){ 
				if(xmlhttp.readyState == 4){ 
					if(xmlhttp.status == 200){
						if(xmlhttp.responseText != 'false'){		
							var nameNode, nameTextNode, DiaCobranca;					
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("DiaCobranca")[0]; 
							nameTextNode = nameNode.childNodes[0];
							DiaCobranca = nameTextNode.nodeValue;
							
							for(i=0;i<document.formulario.DiaCobranca.length;i++){
								if(document.formulario.DiaCobranca[i].value == DiaCobranca){
									document.formulario.DiaCobranca[i].selected	=	true;
									document.formulario.DiaCobrancaTemp.value	=	DiaCobranca;
									break;
								}
							}
							
							document.formulario.DiaCobranca.disabled	=	true;
						}
						// Fim de Carregando
						carregando(false);
					}
				}
			}
			xmlhttp.send(null);	
		}
	}
