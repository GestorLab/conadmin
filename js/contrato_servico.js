	function inicia(){
		status_inicial();
		document.formulario.IdServico.focus();
	}
	function listarParametroAnteriorContrato(IdServico,Erro,IdContrato){
		while(document.getElementById('tabelaParametro').rows.length > 0){
			document.getElementById('tabelaParametro').deleteRow(0);
		}		
	
		var tam, linha, c0;
		
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
	    url = "xml/servico_parametro.php?IdServico="+IdServico+"&IdStatus=1";

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
						// Fim de Carregando
						document.getElementById('cp_parametrosServico').style.display	=	'none';
						carregando(false);
					}else{
						var espaco, visivel, DescricaoParametroServico, Obrigatorio, ValorDefault, Valor, IdParametroServico, color, salvar;
						
						var obsTemp = new Array(), cont=0;
						for(var i=0; i<xmlhttp.responseXML.getElementsByTagName("IdParametroServico").length; i++){
							nameNode = xmlhttp.responseXML.getElementsByTagName("IdParametroServico")[i]; 
							nameTextNode = nameNode.childNodes[0];
							var IdParametroServico = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoParametroServico")[i]; 
							nameTextNode = nameNode.childNodes[0];
							var DescricaoParametroServico = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("Editavel")[i]; 
							nameTextNode = nameNode.childNodes[0];
							var Editavel = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("Visivel")[i]; 
							nameTextNode = nameNode.childNodes[0];
							var Visivel = nameTextNode.nodeValue;
							
							if(Visivel == 1){
								cont++;
								
								tam 	 = document.getElementById('tabelaParametro').rows.length;
								linha	 = document.getElementById('tabelaParametro').insertRow(tam);
	
								linha.accessKey = IdParametroServico; 
								
								c0	= linha.insertCell(0);
								listarServicoParametroNovo(IdServico,IdParametroServico,linha,c0,IdParametroServico);
								c0.style.padding = "0 0 6px 22px";
								
								c0	= linha.insertCell(1);
								
								if(Editavel == 1){
									c0.innerHTML = "<img src='../../img/estrutura_sistema/ico_seta_left.gif' alt='Importar Valor' style='cursor:pointer;' onClick='importaValor("+IdParametroServico+")'>";
								}else{
									c0.innerHTML = "<img src='../../img/estrutura_sistema/ico_seta_left_c.gif' alt='Importar Valor'>";
								}
								c0.style.padding = "0 0 6px 22px";
								
								c0	= linha.insertCell(2);
								c0.vAlign	=	"top";
								c0.style.padding = "0 0 6px 22px";
								
								inserirParametroAnterior(linha,c0,IdParametroServico,i,DescricaoParametroServico);
							}else{
								listarServicoParametroNovo(IdServico,IdParametroServico,linha,c0,IdParametroServico);
							}
						}
						if(cont > 0 ){
							document.getElementById('cp_parametrosServico').style.display	=	'block';
						}
						
						document.formulario.QuantParametros.value = i;
					}
				}	
				// Fim de Carregando
				carregando(false);
			} 
			return true;
		}
		xmlhttp.send(null);
	}
	
	var ArrayValor 	= new  Array();
	ArrayValor[0]	= '';
	
	function inserirParametroAnterior(linha,c0,IdParametroServico,ii,DescricaoParametroServico){
		var TabelaAnterior="",Temp,i=0,opc,selecionado;
		TabelaAnterior += "<B>Parâmetro Serviço Anterior</B><BR>";
		TabelaAnterior += "<select id='IdParametroAnterior' name='ParametroAntigo_"+IdParametroServico+"' style='width:346px' onChange='atribuiValor("+IdParametroServico+",this.value)'><option value='0'></option>";
		
		desc="";
		if(document.formulario.ParametrosAnterior.value != ''){
			Temp	=	document.formulario.ParametrosAnterior.value.split("§");
			
			while(i < Temp.length){
				opc	=	Temp[i].split("¬");	
				
				if(formata_string(DescricaoParametroServico) == formata_string(opc[1])){
					selecionado 	= 'selected=true';
					desc			= "Valor: "+opc[2]; 	 
				}else{
					selecionado 	= '';	 
				}	
				
				TabelaAnterior +=	"<option value='"+opc[2]+"' "+selecionado+">"+opc[1]+"</option>";
				i++;
			}
			
		}
		
		TabelaAnterior += "</select><BR><b style='font-weight:normal' id='Obs_"+IdParametroServico+"'>"+desc+"</b>";
		
		linha.accessKey = IdParametroServico; 
		
		c0.innerHTML =	TabelaAnterior;
					
	}
	function listarServicoParametroNovo(IdServico,IdParametroServico,linha,c0,IdParametroServico){
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
	    url = "xml/servico_parametro.php?IdServico="+IdServico+"&IdParametroServico="+IdParametroServico+"&IdStatus=1";

		xmlhttp.open("GET", url,true);
 		xmlhttp.onreadystatechange = function(){ 
	
			// Carregando...
			carregando(true);

			if(xmlhttp.readyState == 4){ 
				if(xmlhttp.status == 200){
					//alert('listarServicoParametroNovo'+xmlhttp.responseText)
					if(xmlhttp.responseText == 'false'){		
						// Fim de Carregando
						document.getElementById('cp_parametrosServico').style.display	=	'none';
						carregando(false);
					}else{
						var visivel, DescricaoParametroServico, Obrigatorio, ValorDefault, Valor, IdParametroServico, color, salvar;
						
						var obsTemp = new Array(), invisivel="",cont=0, temp = 0;
						for(var i=0; i<xmlhttp.responseXML.getElementsByTagName("IdParametroServico").length; i++){
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoParametroServico")[i]; 
							nameTextNode = nameNode.childNodes[0];
							var DescricaoParametroServico = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("Obrigatorio")[i]; 
							nameTextNode = nameNode.childNodes[0];
							var Obrigatorio = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("ValorDefault")[i]; 
							nameTextNode = nameNode.childNodes[0];
							var ValorDefault = nameTextNode.nodeValue;	
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("IdParametroServico")[i]; 
							nameTextNode = nameNode.childNodes[0];
							var IdParametroServico = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("Editavel")[i]; 
							nameTextNode = nameNode.childNodes[0];
							var Editavel = nameTextNode.nodeValue;
							
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
							
							//alert(DescricaoParametroServico+" -> "+ValorDefault);
							
							if(Visivel == '1'){
								temp++;
								
								tabindex = 11 + cont + 1;
								
								if(Obrigatorio == 1){
									color = "#C10000";
								}else{
									color = "#000000";
								}
								
								if(Editavel == 1){
									visivel	=	'';
								}else{
									visivel	=	'readOnly';
								}
								
								linha.accessKey = IdParametroServico; 
								
								if(Obs==''){ 
									Obs="<BR>";
								}else{
									Obs="Obs: "+Obs;
								}
								
								
								c0.innerHTML = "<B style='color:"+color+";'>"+DescricaoParametroServico+"</B><BR>";
								
								if(IdTipoParametro == 1){
									switch(IdMascaraCampo){
										case '1': //Data
											c0.innerHTML += "<input type='text' name='Valor_"+(IdParametroServico)+"' value='"+ValorDefault+"' style='width:399px;' autocomplete='off' onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out');\" "+visivel+" onkeypress=\"mascara(this,event,'date')\" maxlength='10' tabindex="+tabindex+"><input type='hidden' name='Obrigatorio_"+IdParametroServico+"' value='"+Obrigatorio+"'><input type='hidden' name='Visivel_"+IdParametroServico+"' value='"+Visivel+"'><BR>"+Obs;
											break;
										case '2': //Inteiro
											c0.innerHTML += "<input type='text' name='Valor_"+(IdParametroServico)+"' value='"+ValorDefault+"' style='width:399px;' autocomplete='off' onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out');\" "+visivel+" onkeypress=\"mascara(this,event,'int')\" maxlength='11' tabindex="+tabindex+"><input type='hidden' name='Obrigatorio_"+IdParametroServico+"' value='"+Obrigatorio+"'><input type='hidden' name='Visivel_"+IdParametroServico+"' value='"+Visivel+"'><BR>"+Obs;
											break;
										case '3': //Real
											c0.innerHTML += "<input type='text' name='Valor_"+(IdParametroServico)+"' value='"+ValorDefault+"' style='width:399px;' autocomplete='off' onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out');\" "+visivel+" onkeypress=\"mascara(this,event,'float')\" maxlength='12' tabindex="+tabindex+"><input type='hidden' name='Obrigatorio_"+IdParametroServico+"' value='"+Obrigatorio+"'><input type='hidden' name='Visivel_"+IdParametroServico+"' value='"+Visivel+"'><BR>"+Obs;
											break;
										case '5': //MAC
											c0.innerHTML += "<input type='text' name='Valor_"+(IdParametroServico)+"' value='"+ValorDefault+"' style='width:399px;' autocomplete='off' onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out');\" "+visivel+" onkeypress=\"mascara(this,event,'mac')\" maxlength='17' tabindex="+tabindex+"><input type='hidden' name='Obrigatorio_"+IdParametroServico+"' value='"+Obrigatorio+"'><input type='hidden' name='Visivel_"+IdParametroServico+"' value='"+Visivel+"'><BR>"+Obs;
											break;
										default:
											c0.innerHTML += invisivel+"<input type='text' name='Valor_"+(IdParametroServico)+"' value='"+ValorDefault+"' style='width:399px;' autocomplete='off' onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out');\" "+visivel+" maxlength='255' tabindex="+tabindex+"><input type='hidden' name='Obrigatorio_"+IdParametroServico+"' value='"+Obrigatorio+"'><input type='hidden' name='Visivel_"+IdParametroServico+"' value='"+Visivel+"'><BR>"+Obs;
									}
								}else{
									campo = "<select name='Valor_"+(IdParametroServico)+"'  style='width:406px;' onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out');\" "+visivel+" tabindex="+tabindex+">";
									campo += "<option value='"+ValorDefault+"'></option>";
												
									valor	=	OpcaoValor.split("\n");
									for(var ii=0; ii<valor.length; ii++){
										selecionado = "";
										if(ValorDefault == valor[ii]){
											selecionado	=	"selected=true";
										}
										campo +=	"<option value='"+valor[ii]+"' "+selecionado+">"+valor[ii]+"</option>";
									}
									campo += "</select>";
									campo +=	"<input type='hidden' name='Obrigatorio_"+(IdParametroServico)+"' value='"+Obrigatorio+"'><input type='hidden' name='Visivel_"+IdParametroServico+"' value='"+Visivel+"'>";
									campo +=	"<BR>"+Obs;
									
									c0.innerHTML	+=	campo;
								}
								
								cont++;							
							}else{
								invisivel	+=	"<div style='display:none'>";
								if(IdTipoParametro == 1){
									invisivel += "<input type='text' name='Valor_"+IdParametroServico+"' value='"+ValorDefault+"' style='width:399px;' autocomplete='off'><input type='hidden' name='Obrigatorio_"+IdParametroServico+"' value='"+Obrigatorio+"'><input type='hidden' name='Visivel_"+IdParametroServico+"' value='"+Visivel+"'></p>";
								}else{
									campo  = "";
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
								
								invisivel	+=	"</div>";
							}
						}
						if(invisivel !=""){
							tam 	 = document.getElementById('tabelaParametro').rows.length;
							linha	 = document.getElementById('tabelaParametro').insertRow(tam);
								
							linha.accessKey = IdParametroServico; 
								
							c0	= linha.insertCell(0);
							c0.innerHTML	=	invisivel;
						}
						
						if(temp > 0){
							document.getElementById('cp_parametrosServico').style.display	=	'block';
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
	function listarParametroAnterior(IdServico,IdContrato){
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
	    url = "xml/contrato_automatico_servico_parametro.php?IdServico="+IdServico+"&IdContrato="+IdContrato+"&Visivel=1";

		xmlhttp.open("GET", url,true);
 		xmlhttp.onreadystatechange = function(){ 
	
			// Carregando...
			carregando(true);

			if(xmlhttp.readyState == 4){ 
				if(xmlhttp.status == 200){
					//alert(xmlhttp.responseText);
					if(xmlhttp.responseText == 'false'){		
						// Fim de Carregando
						//document.getElementById('cp_parametrosServico').style.display	=	'none';
						carregando(false);
					}else{
						var DescricaoParametroServico, ValorDefault, Valor, IdParametroServico;
						
						//document.getElementById('cp_parametrosServico').style.display	=	'block';
						
						for(var i=0; i<xmlhttp.responseXML.getElementsByTagName("IdParametroServico").length; i++){
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoParametroServico")[i]; 
							nameTextNode = nameNode.childNodes[0];
							var DescricaoParametroServico = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("IdParametroServico")[i]; 
							nameTextNode = nameNode.childNodes[0];
							var IdParametroServico = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("ValorDefault")[i]; 
							nameTextNode = nameNode.childNodes[0];
							var ValorDefault = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("Valor")[i]; 
							nameTextNode = nameNode.childNodes[0];
							var Valor = nameTextNode.nodeValue;
						
							if(Valor == ''){
								Valor	=	ValorDefault;
							}
							
							if(document.formulario.ParametrosAnterior.value != ''){
								document.formulario.ParametrosAnterior.value	+= '§';
							}
							document.formulario.ParametrosAnterior.value	+=	IdParametroServico+'¬'+DescricaoParametroServico+'¬'+Valor;	
							
							ArrayValor[i+1]	=	Valor;
						}
						//alert(document.formulario.ParametrosAnterior.value);
					}
				}	
				// Fim de Carregando
				carregando(false);
			} 
			return true;
		}
		xmlhttp.send(null);
	}
	function atribuiValor(campo,valor){
		var posInicial=0,posFinal=0,temp='';
		for(i = 0; i<document.formulario.length; i++){
			if(document.formulario[i].name != undefined){
				if(document.formulario[i].name.substring(0,6) == 'Valor_'){
					if(posInicial == 0){
						posInicial = i;
					}
					posFinal	=	i;
				}
			}
		}
		ValorCampo	=	"";
		
		if(posInicial != 0){
			for(i = posInicial; i<=posFinal; i=i+4){
				//alert(document.formulario[i].name+" = "+document.formulario[i+1].name+" = "+document.formulario[i+2].name+" = "+document.formulario[i+3].name);
				if(document.formulario[i].name.substring(0,6) == 'Valor_'){
					temp	=	document.formulario[i].name.split("_");
					if(temp[1] == campo){
						if(valor!=0){
							document.getElementById('Obs_'+campo).innerHTML = 'Valor: '+valor;
						}else{
							document.getElementById('Obs_'+campo).innerHTML = '';
						}
						break;
					}
				}
			}
		}
	}
	function importaValor(IdParametroServico){
		var posInicial=0,posFinal=0,temp='';
		for(i = 0; i<document.formulario.length; i++){
			if(document.formulario[i].name != undefined){
				if(document.formulario[i].name.substring(0,6) == 'Valor_'){
					if(posInicial == 0){
						posInicial = i;
					}
					posFinal = i;
				}
			}
		}
		var cont = 0, valor = "";
		if(posInicial != 0){
			for(i = posInicial; i<=posFinal; i=i+4){
				//alert(document.formulario[i].name+" = "+document.formulario[i+1].name+" = "+document.formulario[i+2].name+" = "+document.formulario[i+3].name);
				temp	=	document.formulario[i].name.split("_");
				if(temp[1] == IdParametroServico){	
					for(ii=0;ii<document.formulario[i+3].options.length;ii++){
						//alert(document.formulario[i+4][ii].value);
						if(document.formulario[i+3][ii].selected == true){
							valor	=	document.formulario[i+3][ii].value;
							if(valor!=0){
								document.formulario[i].value = valor;
							}else{
								document.formulario[i].value = '';
							}
						}
					}
					break;
					
					/*if(document.formulario[i+2].value != 0){
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
					    
					    var IdServico			=	document.formulario.IdServicoAnterior.value;
					    var IdContrato			=	document.formulario.IdContrato.value;
					    var IdParametroServico	=	document.formulario[i+2].value;
					    
					    url = "xml/contrato_parametro.php?IdServico="+IdServico+"&IdContrato="+IdContrato+"&IdParametroServico="+IdParametroServico;
				
						xmlhttp.open("GET", url,true);
				 		xmlhttp.onreadystatechange = function(){ 
					
							// Carregando...
							carregando(true);
				
							if(xmlhttp.readyState == 4){ 
								if(xmlhttp.status == 200){
									if(xmlhttp.responseText == 'false'){		
										// Fim de Carregando
										//document.getElementById('tabelaParametro').style.display	=	'none';
										//carregando(false);
									}else{
										nameNode = xmlhttp.responseXML.getElementsByTagName("Valor")[0]; 
										nameTextNode = nameNode.childNodes[0];
										var Valor = nameTextNode.nodeValue;
										
										nameNode = xmlhttp.responseXML.getElementsByTagName("ValorDefault")[0]; 
										nameTextNode = nameNode.childNodes[0];
										var ValorDefault = nameTextNode.nodeValue;
										
										if(Valor == ''){
											Valor	=	ValorDefault;
										}
										
										if(document.formulario[i].type == 'text'){
											document.formulario[i].value = Valor;
										}else{
											if(document.formulario[i].type == 'select-one'){
												for(j=0;j<document.formulario[i].length;j++){
													if(document.formulario[i][j].value == Valor){
														document.formulario[i][j].selected	=	true;	
														break;
													}
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
						break;
					}*/
				}
				//cont++;
			}
		}
	}
	function calculaPeriodicidadeServico(IdPeriodicidade,valor,campo){
		if(valor != ''){
			if(valor.indexOf(",") != -1){	
				valor = valor.replace('.','');
				valor = valor.replace('.','');
				valor = valor.replace(',','.');
			}
			valor 		  = parseFloat(valor);
			
			var Meses = 1;
			
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
		    
		   	url = "xml/periodicidade.php?IdPeriodicidade="+IdPeriodicidade;
		
			xmlhttp.open("GET", url,true);
			xmlhttp.onreadystatechange = function(){ 
		
				if(xmlhttp.readyState == 4){ 
					if(xmlhttp.status == 200){;
						if(xmlhttp.responseText != 'false'){	
							nameNode = xmlhttp.responseXML.getElementsByTagName("Fator")[0]; 
							nameTextNode = nameNode.childNodes[0];
							Fator = nameTextNode.nodeValue;
						
							campo.value = valor*parseInt(Fator);
							campo.value = formata_float(Arredonda(campo.value,2),2).replace(".",",");
						}
					}
				}
			}
			xmlhttp.send(null);
		}else{
			campo.value = '';
		}
	}
	function verificaAcao(){
		if(document.formulario.IdContrato.value == ''){
			document.formulario.bt_alterar.disabled 	= true;
		}else{
			document.formulario.bt_alterar.disabled 	= false;
		}
	}
	function validar_Data(id,campo){
		if(campo.value == ''){
			document.getElementById(id).style.backgroundColor='#FFFFFF';
			document.getElementById(id).style.color='#C10000';
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
			document.getElementById(id).style.color='#C10000';
			mensagens(0);
			return true;
		}	
	}
	function verificaCredito(IdServico,DataBaseCalculo,DataCancelamento){
		if(DataBaseCalculo != '' && DataCancelamento != '' && IdServico!=""){
			var dataBase = formatDate(DataBaseCalculo);
			var dataCanc = formatDate(DataCancelamento);
			if(dataBase > dataCanc){
				//contratos abertos
				document.getElementById("cp_credito").style.display 	=	"block";
				
				var anodataBase 	= dataBase.substring(0,4);
				var mesdataBase 	= (dataBase.substring(5,7) - 1);
				var diadataBase 	= dataBase.substring(8,10);
				
				var anodataCanc 	= dataCanc.substring(0,4);
				var mesdataCanc 	= (dataCanc.substring(5,7) - 1);
				var diadataCanc 	= dataCanc.substring(8,10);
				
				var qtdDias	=	diferencaDias(new Date(anodataBase, mesdataBase, diadataBase), new Date(anodataCanc,mesdataCanc, diadataCanc));
				
				var valorServico	=	document.formulario.ValorServicoAnterior.value
				valorServico = valorServico.replace('.','');
				valorServico = valorServico.replace('.','');
				valorServico = valorServico.replace(',','.');
				
				var qtdDiasDataBase	=	numDiasMes(anodataBase, mesdataCanc);
				
				var valor	=	(valorServico/qtdDiasDataBase)*qtdDias;
			
				
				document.formulario.DataInicioCredito.value		=	DataCancelamento;
				document.formulario.DataTerminoCredito.value	=	DataBaseCalculo;
				document.formulario.QuantidadeDias.value		=	qtdDias;
				document.formulario.ValorCredito.value			=	formata_float(Arredonda(valor,2),2).replace('.',',');
				
				listar_conta_receber_aberto(document.formulario.IdContrato.value);
			}else{
				limpaFormCredito();
			}
		}else{
			limpaFormCredito();
		}
	}
	function limpaFormCredito(){
		document.getElementById("cp_credito").style.display 	=	"none";
		
		while(document.getElementById('tabelaContasReceber').rows.length > 2){
			document.getElementById('tabelaContasReceber').deleteRow(1);
		}
	}
	function listar_conta_receber_aberto(IdContrato){
		if(IdContrato == undefined || IdContrato==''){
			IdContrato = 0;
		}
		
		while(document.getElementById('tabelaContasReceber').rows.length > 2){
			document.getElementById('tabelaContasReceber').deleteRow(1);
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
	    
	   	url = "xml/conta_receber.php?IdContrato="+IdContrato+"&IdStatusAberto=1";
		xmlhttp.open("GET", url,true);

		xmlhttp.onreadystatechange = function(){ 
	
			// Carregando...
			carregando(true);
	
			if(xmlhttp.readyState == 4){ 
				if(xmlhttp.status == 200){
					if(xmlhttp.responseText == 'false'){
						document.getElementById('cpValorTotal').innerHTML		=	"0,00";		
						document.getElementById('tabelaTotal').innerHTML		=	"Total: 0";	
						
						// Fim de Carregando
						carregando(false);
					}else{
						var tam, linha, c0, c1, c2, c3, c4, c5, c6, c7;
						var IdLoja, IdContaReceber,Nome,RazaoSocial,NumeroDocumento,NumeroNF,DataLancamento,Valor,DataVencimento;
						var valorParc=0;
						for(var i=0; i<xmlhttp.responseXML.getElementsByTagName("IdContaReceber").length; i++){	

							nameNode = xmlhttp.responseXML.getElementsByTagName("IdContaReceber")[i]; 
							nameTextNode = nameNode.childNodes[0];
							IdContaReceber = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("Nome")[i]; 
							nameTextNode = nameNode.childNodes[0];
							Nome = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("RazaoSocial")[i]; 
							nameTextNode = nameNode.childNodes[0];
							RazaoSocial = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("NumeroDocumento")[i]; 
							nameTextNode = nameNode.childNodes[0];
							NumeroDocumento = nameTextNode.nodeValue;	
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("NumeroNF")[i]; 
							nameTextNode = nameNode.childNodes[0];
							NumeroNF = nameTextNode.nodeValue;	
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("DataLancamento")[i]; 
							nameTextNode = nameNode.childNodes[0];
							DataLancamento = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("Valor")[i]; 
							nameTextNode = nameNode.childNodes[0];
							Valor = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("DataVencimento")[i]; 
							nameTextNode = nameNode.childNodes[0];
							DataVencimento = nameTextNode.nodeValue;
							
							if(RazaoSocial != ""){
								Nome	=	RazaoSocial;
							}
							
							tam 	= document.getElementById('tabelaContasReceber').rows.length;
							linha	= document.getElementById('tabelaContasReceber').insertRow(tam-1);
							
							if(tam%2 != 0){
								linha.style.backgroundColor = "#E2E7ED";
							}
							
							linha.accessKey = IdContaReceber; 
							
							c0	= linha.insertCell(0);	
							c1	= linha.insertCell(1);	
							c2	= linha.insertCell(2);	
							c3	= linha.insertCell(3);
							c4	= linha.insertCell(4);
							c5	= linha.insertCell(5);
							c6	= linha.insertCell(6);
							c7	= linha.insertCell(7);
							
							valorParc	=	parseFloat(valorParc) + parseFloat(Valor);
							
							linkIni	= "<a href='cadastro_conta_receber.php?IdContaReceber="+IdContaReceber+"'>";	
							linkFim	= "</a>";
							
							
							c0.innerHTML = linkIni + IdContaReceber + linkFim;
							c0.style.padding =	"0 0 0 5px";
							
							c1.innerHTML = linkIni + Nome + linkFim;
							
							c2.innerHTML = linkIni + NumeroDocumento + linkFim;
							
							c3.innerHTML = linkIni + NumeroNF + linkFim;
							
							c4.innerHTML = linkIni + dateFormat(DataLancamento) + linkFim;
							
							c5.innerHTML = linkIni + formata_float(Arredonda(Valor,2),2).replace('.',',')+ linkFim ;
							c5.style.textAlign = "right";
							c5.style.padding =	"0 8px 0 0";
							
							c6.innerHTML = linkIni + dateFormat(DataVencimento) + linkFim;
							
							c7.innerHTML = "<img src='../../img/estrutura_sistema/ico_del_c.gif' alt='Excluir?'\">";
						}
						
						document.getElementById('cpValorTotal').innerHTML				=	formata_float(Arredonda(valorParc,2),2).replace('.',',');		
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
	
	function listar_carteira_anterior(IdAgenteAutorizado,IdCarteiraTemp){
		if(IdAgenteAutorizado == ''){
			while(document.formulario.IdCarteiraAnterior.options.length > 0){
				document.formulario.IdCarteiraAnterior.options[0] = null;
			}
			return false;
		}
		if(IdCarteiraTemp == undefined){
			IdCarteiraTemp = '';
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

		url = "xml/carteira.php?IdAgenteAutorizado="+IdAgenteAutorizado;
		xmlhttp.open("GET", url,true);

		xmlhttp.onreadystatechange = function(){ 

			// Carregando...
			carregando(true);

			if(xmlhttp.readyState == 4){ 
				if(xmlhttp.status == 200){
					if(xmlhttp.responseText == 'false'){
						while(document.formulario.IdCarteiraAnterior.options.length > 0){
							document.formulario.IdCarteiraAnterior.options[0] = null;
						}
					}else{
						while(document.formulario.IdCarteiraAnterior.options.length > 0){
							document.formulario.IdCarteiraAnterior.options[0] = null;
						}
						addOption(document.formulario.IdCarteiraAnterior,"","0");
						
						for(var i=0; i<xmlhttp.responseXML.getElementsByTagName("IdCarteira").length; i++){
							nameNode = xmlhttp.responseXML.getElementsByTagName("IdCarteira")[i]; 
							nameTextNode = nameNode.childNodes[0];
							IdCarteira = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("Nome")[i]; 
							nameTextNode = nameNode.childNodes[0];
							Nome = nameTextNode.nodeValue;
							
							addOption(document.formulario.IdCarteiraAnterior,Nome,IdCarteira);
						}
						if(IdCarteiraTemp!=''){
							for(ii=0;ii<document.formulario.IdCarteiraAnterior.length;ii++){
								if(document.formulario.IdCarteiraAnterior[ii].value == IdCarteiraTemp){
									document.formulario.IdCarteiraAnterior[ii].selected = true;
									break;
								}
							}
						}else{
							document.formulario.IdCarteiraAnterior[0].selected = true;
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
	
	function listarServicoParametroAnteriorAutomatico(IdServico,tabindex,IdContrato){
		while(document.getElementById("tabelaParametro_"+IdServico+"").rows.length > 0){
			document.getElementById("tabelaParametro_"+IdServico+"").deleteRow(0);
		}		
	
		var tam, linha, c0;
		
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
	    url = "xml/servico_parametro.php?IdServico="+IdServico+"&IdStatus=1";

		xmlhttp.open("GET", url,true);
 		xmlhttp.onreadystatechange = function(){ 
	
			// Carregando...
			carregando(true);

			if(xmlhttp.readyState == 4){ 
				if(xmlhttp.status == 200){
	
					if(xmlhttp.responseText == 'false'){		
						// Fim de Carregando
						document.getElementById("tabelaParametro_"+IdServico+"").style.display	=	'none';
						carregando(false);
					}else{
						var espaco, visivel, DescricaoParametroServico, Obrigatorio, ValorDefault, Valor, IdParametroServico, color, salvar;
						
						var obsTemp = new Array(), cont=0;
						document.getElementById("tabelaParametro_"+IdServico+"").style.display	=	'block';
						for(var i=0; i<xmlhttp.responseXML.getElementsByTagName("IdParametroServico").length; i++){
							nameNode = xmlhttp.responseXML.getElementsByTagName("IdParametroServico")[i]; 
							nameTextNode = nameNode.childNodes[0];
							var IdParametroServico = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoParametroServico")[i]; 
							nameTextNode = nameNode.childNodes[0];
							var DescricaoParametroServico = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("Editavel")[i]; 
							nameTextNode = nameNode.childNodes[0];
							var Editavel = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("Visivel")[i]; 
							nameTextNode = nameNode.childNodes[0];
							var Visivel = nameTextNode.nodeValue;
							
							if(Visivel == 1){
								cont++;
								
								tam 	 = document.getElementById("tabelaParametro_"+IdServico+"").rows.length;
								linha	 = document.getElementById("tabelaParametro_"+IdServico+"").insertRow(tam);
	
								linha.accessKey = IdParametroServico; 
								
								c0	= linha.insertCell(0);
								listarServicoParametroAutomaticoNovo(IdServico,IdParametroServico,linha,c0,IdParametroServico);
								c0.style.padding = "0 0 6px 22px";
								
								c0	= linha.insertCell(1);
								
								if(Editavel == 1){
									c0.innerHTML = "<img src='../../img/estrutura_sistema/ico_seta_left.gif' alt='Importar Valor' style='cursor:pointer;' onClick='importaValorAutomatico("+IdServico+","+IdParametroServico+")'>";
								}else{
									c0.innerHTML = "<img src='../../img/estrutura_sistema/ico_seta_left_c.gif' alt='Importar Valor'>";
								}
								c0.style.padding = "0 0 6px 22px";
								
								c0	= linha.insertCell(2);
								c0.vAlign	=	"top";
								c0.style.padding = "0 0 6px 22px";
								
								inserirParametroAutomaticoAnterior(linha,c0,IdParametroServico,i,DescricaoParametroServico,IdServico);
							}else{
								listarServicoParametroAutomaticoNovo(IdServico,IdParametroServico,linha,c0,IdParametroServico);
							}
						}
						if(cont > 0 ){
							document.getElementById("tabelaParametro_"+IdServico+"").style.display	=	'block';
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
	function listarServicoParametroAutomaticoNovo(IdServico,IdParametroServico,linha,c0,IdParametroServico){
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
	    url = "xml/servico_parametro.php?IdServico="+IdServico+"&IdParametroServico="+IdParametroServico+"&IdStatus=1";

		xmlhttp.open("GET", url,true);
 		xmlhttp.onreadystatechange = function(){ 
	
			// Carregando...
			carregando(true);

			if(xmlhttp.readyState == 4){ 
				if(xmlhttp.status == 200){
					//alert('listarServicoParametroNovo'+xmlhttp.responseText)
					if(xmlhttp.responseText == 'false'){		
						// Fim de Carregando
						document.getElementById("tabelaParametro_"+IdServico+"").style.display	=	'none';
						carregando(false);
					}else{
						var visivel, DescricaoParametroServico, Obrigatorio, ValorDefault, Valor, IdParametroServico, color, salvar;
						
						var obsTemp = new Array(), invisivel="",cont=0, temp = 0;
						for(var i=0; i<xmlhttp.responseXML.getElementsByTagName("IdParametroServico").length; i++){
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoParametroServico")[i]; 
							nameTextNode = nameNode.childNodes[0];
							var DescricaoParametroServico = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("Obrigatorio")[i]; 
							nameTextNode = nameNode.childNodes[0];
							var Obrigatorio = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("ValorDefault")[i]; 
							nameTextNode = nameNode.childNodes[0];
							var ValorDefault = nameTextNode.nodeValue;	
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("IdParametroServico")[i]; 
							nameTextNode = nameNode.childNodes[0];
							var IdParametroServico = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("Editavel")[i]; 
							nameTextNode = nameNode.childNodes[0];
							var Editavel = nameTextNode.nodeValue;
							
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
							
							//alert(DescricaoParametroServico+" -> "+ValorDefault);
							
							if(Visivel == '1'){
								temp++;
								
								tabindex = 11 + cont + 1;
								
								if(Obrigatorio == 1){
									color = "#C10000";
								}else{
									color = "#000000";
								}
								
								if(Editavel == 1){
									visivel	=	'';
								}else{
									visivel	=	'readOnly';
								}
								
								linha.accessKey = IdParametroServico; 
								
								if(Obs==''){ 
									Obs="<BR>";
								}else{
									Obs="Obs: "+Obs;
								}
								
								
								c0.innerHTML = "<B style='color:"+color+";'>"+DescricaoParametroServico+"</B><BR>";
								
								if(IdTipoParametro == 1){
									switch(IdMascaraCampo){
										case '1': //Data
											c0.innerHTML += "<input type='text' name='ValorAutomatico_"+IdServico+"_"+(IdParametroServico)+"' value='"+ValorDefault+"' style='width:399px;' autocomplete='off' onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out');\" "+visivel+" onkeypress=\"mascara(this,event,'date')\" maxlength='10' tabindex="+tabindex+"><input type='hidden' name='ObrigatorioAutomatico_"+IdServico+"_"+IdParametroServico+"' value='"+Obrigatorio+"'><input type='hidden' name='VisivelAutomatico_"+IdServico+"_"+IdParametroServico+"' value='"+Visivel+"'><BR>"+Obs;
											break;
										case '2': //Inteiro
											c0.innerHTML += "<input type='text' name='ValorAutomatico_"+IdServico+"_"+(IdParametroServico)+"' value='"+ValorDefault+"' style='width:399px;' autocomplete='off' onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out');\" "+visivel+" onkeypress=\"mascara(this,event,'int')\" maxlength='11' tabindex="+tabindex+"><input type='hidden' name='ObrigatorioAutomatico_"+IdServico+"_"+IdParametroServico+"' value='"+Obrigatorio+"'><input type='hidden' name='VisivelAutomatico_"+IdServico+"_"+IdParametroServico+"' value='"+Visivel+"'><BR>"+Obs;
											break;
										case '3': //Real
											c0.innerHTML += "<input type='text' name='ValorAutomatico_"+IdServico+"_"+(IdParametroServico)+"' value='"+ValorDefault+"' style='width:399px;' autocomplete='off' onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out');\" "+visivel+" onkeypress=\"mascara(this,event,'float')\" maxlength='12' tabindex="+tabindex+"><input type='hidden' name='ObrigatorioAutomatico_"+IdServico+"_"+IdParametroServico+"' value='"+Obrigatorio+"'><input type='hidden' name='VisivelAutomatico_"+IdServico+"_"+IdParametroServico+"' value='"+Visivel+"'><BR>"+Obs;
											break;
										case '5': //MAC
											c0.innerHTML += "<input type='text' name='ValorAutomatico_"+IdServico+"_"+(IdParametroServico)+"' value='"+ValorDefault+"' style='width:399px;' autocomplete='off' onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out');\" "+visivel+" onkeypress=\"mascara(this,event,'mac')\" maxlength='17' tabindex="+tabindex+"><input type='hidden' name='ObrigatorioAutomatico_"+IdServico+"_"+IdParametroServico+"' value='"+Obrigatorio+"'><input type='hidden' name='VisivelAutomatico_"+IdServico+"_"+IdParametroServico+"' value='"+Visivel+"'><BR>"+Obs;
											break;
										default:
											c0.innerHTML += invisivel+"<input type='text' name='ValorAutomatico_"+IdServico+"_"+(IdParametroServico)+"' value='"+ValorDefault+"' style='width:399px;' autocomplete='off' onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out');\" "+visivel+" maxlength='255' tabindex="+tabindex+"><input type='hidden' name='ObrigatorioAutomatico_"+IdServico+"_"+IdParametroServico+"' value='"+Obrigatorio+"'><input type='hidden' name='VisivelAutomatico_"+IdServico+"_"+IdParametroServico+"' value='"+Visivel+"'><BR>"+Obs;
									}
								}else{
									campo = "<select name='ValorAutomatico_"+IdServico+"_"+(IdParametroServico)+"'  style='width:406px;' onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out');\" "+visivel+" tabindex="+tabindex+">";
									campo += "<option value='"+ValorDefault+"'></option>";
												
									valor	=	OpcaoValor.split("\n");
									for(var ii=0; ii<valor.length; ii++){
										selecionado = "";
										if(ValorDefault == valor[ii]){
											selecionado	=	"selected=true";
										}
										campo +=	"<option value='"+valor[ii]+"' "+selecionado+">"+valor[ii]+"</option>";
									}
									campo += "</select>";
									campo +=	"<input type='hidden' name='ObrigatorioAutomatico_"+IdServico+"_"+(IdParametroServico)+"' value='"+Obrigatorio+"'><input type='hidden' name='VisivelAutomatico_"+IdServico+"_"+IdParametroServico+"' value='"+Visivel+"'>";
									campo +=	"<BR>"+Obs;
									
									c0.innerHTML	+=	campo;
								}
								
								cont++;							
							}else{
								invisivel	+=	"<div style='display:none'>";
								if(IdTipoParametro == 1){
									invisivel += "<input type='text' name='ValorAutomatico_"+IdServico+"_"+IdParametroServico+"' value='"+ValorDefault+"' style='width:399px;' autocomplete='off'><input type='hidden' name='ObrigatorioAutomatico_"+IdServico+"_"+IdParametroServico+"' value='"+Obrigatorio+"'><input type='hidden' name='VisivelAutomatico_"+IdServico+"_"+IdParametroServico+"' value='"+Visivel+"'></p>";
								}else{
									campo  = "";
									campo +=	"<select name='ValorAutomatico_"+IdServico+"_"+IdParametroServico+"'  style='width:406px;'>";
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
									campo +=	"<input type='hidden' name='ObrigatorioAutomatico_"+IdServico+"_"+IdParametroServico+"' value='"+Obrigatorio+"'><input type='hidden' name='VisivelAutomatico_"+IdServico+"_"+IdParametroServico+"' value='"+Visivel+"'>";
									
									invisivel	+=	campo;
								}
								
								invisivel	+=	"</div>";
							}
						}
						if(invisivel !=""){
							tam 	 = document.getElementById("tabelaParametro_"+IdServico+"").rows.length;
							linha	 = document.getElementById("tabelaParametro_"+IdServico+"").insertRow(tam);
								
							linha.accessKey = IdParametroServico; 
								
							c0	= linha.insertCell(0);
							c0.innerHTML	=	invisivel;
						}
						
						if(temp > 0){
							document.getElementById("tabelaParametro_"+IdServico+"").style.display	=	'block';
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
	function inserirParametroAutomaticoAnterior(linha,c0,IdParametroServico,ii,DescricaoParametroServico,IdServico){
		var TabelaAnterior="",Temp,i=0,opc,selecionado;
		TabelaAnterior += "<B>Parâmetro Serviço Anterior</B><BR>";
		TabelaAnterior += "<select id='IdParametroAnterior' name='ParametroAntigoAutomatico_"+IdServico+"_"+IdParametroServico+"' style='width:346px' onChange='atribuiValorAutomatico("+IdServico+","+IdParametroServico+",this.value)'><option value='0'></option>";
		
		desc="";
		if(document.formulario.ParametrosAnterior.value != ''){
			Temp	=	document.formulario.ParametrosAnterior.value.split("§");
			
			while(i < Temp.length){
				opc	=	Temp[i].split("¬");	
				
				if(formata_string(DescricaoParametroServico) == formata_string(opc[1])){
					selecionado 	= 'selected=true';
					desc			= "Valor: "+opc[2]; 	 
				}else{
					selecionado 	= '';	 
				}	
				
				TabelaAnterior +=	"<option value='"+opc[2]+"' "+selecionado+">"+opc[1]+"</option>";
				i++;
			}
			
		}
		
		TabelaAnterior += "</select><BR><b style='font-weight:normal' id='ObsAutomatico_"+IdServico+"_"+IdParametroServico+"'>"+desc+"</b>";
		
		linha.accessKey = IdParametroServico; 
		
		c0.innerHTML =	TabelaAnterior;
					
	}
	function atribuiValorAutomatico(IdServico,campo,valor){
		var posInicial=0,posFinal=0,temp='';
		for(i = 0; i<document.formulario.length; i++){
			if(document.formulario[i].name != undefined){
				if(document.formulario[i].name.substring(0,16) == 'ValorAutomatico_'){
					if(posInicial == 0){
						posInicial = i;
					}
					posFinal	=	i;
				}
			}
		}
		ValorCampo	=	"";
		
		if(posInicial != 0){
			for(i = posInicial; i<=posFinal; i=i+4){
				//alert(document.formulario[i].name+" = "+document.formulario[i+1].name+" = "+document.formulario[i+2].name+" = "+document.formulario[i+3].name);
				if(document.formulario[i].name.substring(0,16) == 'ValorAutomatico_'){
					temp	=	document.formulario[i].name.split("_");
					if(temp[1] == IdServico && temp[2] == campo){
						if(valor!=0){
							document.getElementById('ObsAutomatico_'+IdServico+"_"+campo).innerHTML = 'Valor: '+valor;
						}else{
							document.getElementById('ObsAutomatico_'+IdServico+"_"+campo).innerHTML = '';
						}
						break;
					}
				}
			}
		}
	}
	function importaValorAutomatico(IdServico,IdParametroServico){
		var posInicial=0,posFinal=0,temp='';
		for(i = 0; i<document.formulario.length; i++){
			if(document.formulario[i].name != undefined){
				if(document.formulario[i].name.substring(0,16) == 'ValorAutomatico_'){
					if(posInicial == 0){
						posInicial = i;
					}
					posFinal = i;
				}
			}
		}
		var cont = 0, valor = "";
		if(posInicial != 0){
			for(i = posInicial; i<=posFinal; i=i+4){
				//alert(document.formulario[i].name+" = "+document.formulario[i+1].name+" = "+document.formulario[i+2].name+" = "+document.formulario[i+3].name);
				temp	=	document.formulario[i].name.split("_");
				if(temp[1] == IdServico && temp[2] == IdParametroServico){	
					for(ii=0;ii<document.formulario[i+3].options.length;ii++){
						//alert(document.formulario[i+4][ii].value);
						if(document.formulario[i+3][ii].selected == true){
							valor	=	document.formulario[i+3][ii].value;
							if(valor!=0){
								document.formulario[i].value = valor;
							}else{
								document.formulario[i].value = '';
							}
						}
					}
					break;
				}
			}
		}
	}