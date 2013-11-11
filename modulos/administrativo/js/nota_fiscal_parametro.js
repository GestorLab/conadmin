	function inicia(){		
		document.formulario.bt_alterar.disabled = true;
	//	document.formulario.IdNotaFiscalTipo.focus();
	}	
	function validar(){		
		for(i=0; i<document.formulario.length; i++){
			if(document.formulario[i].name.substr(0,6) == "Valor_" || document.formulario[i].name.substr(0,11) == "OpcaoValor_"){
				if(document.formulario[i].value == ""){
					mensagens(1);
					document.formulario[i].focus();
					return false;			
				}
			}	
		}				
		return true;
	}
	function busca_nota_fiscal_layout_parametro(IdNotaFiscalTipo,Erro){	
		if(IdNotaFiscalTipo == ''){
			IdNotaFiscalTipo = 0;
		}	
		var nameNode, nameTextNode, url, qtdLinha = 0;
		
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
	    
	   	url = "../administrativo/xml/nota_fiscal_layout_parametro.php?IdNotaFiscalTipo="+IdNotaFiscalTipo;
		xmlhttp.open("GET", url,true);

		xmlhttp.onreadystatechange = function(){ 
	
			// Carregando...
			carregando(true);
	
			if(xmlhttp.readyState == 4){ 
				if(xmlhttp.status == 200){
					//alert(xmlhttp.responseText);
					if(Erro != false){
						document.formulario.Erro.value = 0;
						verificaErro();
					}
					
					if(xmlhttp.responseText == 'false'){																								
						for(i=document.getElementById('tableParametro').rows.length-1; i>0; i--){																						
							document.getElementById('tableParametro').deleteRow(i);
						}
						
						document.getElementById('cpDadosParametros').style.display = 'none';
						document.formulario.bt_alterar.disabled = true;
						
						// Fim de Carregando
						carregando(false);
					}else{
						var tam, linha, linha2, tabindex = 0;
						var c0, c1, c2, c3;					
						var cor;
						
						for(i=document.getElementById('tableParametro').rows.length-1; i>0; i--){																						
							document.getElementById('tableParametro').deleteRow(i);
						}
						
						for(i=0; i<xmlhttp.responseXML.getElementsByTagName("IdNotaFiscalLayout").length; i++){	
							
							tam 	= document.getElementById('tableParametro').rows.length;	
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("IdNotaFiscalTipo")[i]; 
							nameTextNode = nameNode.childNodes[0];
							var IdNotaFiscalTipo = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("IdNotaFiscalLayout")[i]; 
							nameTextNode = nameNode.childNodes[0];
							var IdNotaFiscalLayout = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("IdNotaFiscalLayoutParametro")[i]; 
							nameTextNode = nameNode.childNodes[0];
							var IdNotaFiscalLayoutParametro = nameTextNode.nodeValue;							
										
							nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoParametro")[i]; 
							nameTextNode = nameNode.childNodes[0];
							var DescricaoParametro = nameTextNode.nodeValue;
						
							nameNode = xmlhttp.responseXML.getElementsByTagName("Destino")[i]; 
							nameTextNode = nameNode.childNodes[0];
							var Destino = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("ValorDefault")[i]; 
							nameTextNode = nameNode.childNodes[0];
							var ValorDefault = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("OpcaoValor")[i]; 
							nameTextNode = nameNode.childNodes[0];
							var OpcaoValor = nameTextNode.nodeValue;
																			
							var aux, aux2, ii=0;

							if(Destino == ""){	
								linha	= document.getElementById('tableParametro').insertRow(tam);								
								
								c0	= linha.insertCell(0);
								c1	= linha.insertCell(1);
								c2	= linha.insertCell(2);
								c3	= linha.insertCell(3);										
															
								c0.innerHTML = '&nbsp;';
								
								c1.innerHTML = "<input type='hidden' name='IdNotaFiscalLayoutParametro_"+IdNotaFiscalLayout+'_'+IdNotaFiscalLayoutParametro+"' value='"+IdNotaFiscalLayout+'_'+IdNotaFiscalLayoutParametro+"' readOnly><input  type='text' style='width:350px' name='DescricaoParametro_"+IdNotaFiscalLayout+'_'+IdNotaFiscalLayoutParametro+"' value='"+DescricaoParametro+"' tabindex='"+(tabindex+1)+"' readOnly>"; 
									
								c2.innerHTML = '&nbsp;';
								
								if(OpcaoValor == ""){																						
									c3.innerHTML = "<input type='text' name='Valor_"+IdNotaFiscalLayout+'_'+IdNotaFiscalLayoutParametro+"' value='"+ValorDefault+"' style='width:301px' maxlength='255' onFocus=\"Foco(this,'in')\" onBlur=\"Foco(this,'out')\" tabindex='"+(tabindex+2)+"'>"; 																							
								}else{
									c3.innerHTML = "<select id='OpcaoValor_"+IdNotaFiscalLayout+'_'+IdNotaFiscalLayoutParametro+"' name='OpcaoValor_"+IdNotaFiscalLayout+'_'+IdNotaFiscalLayoutParametro+"' onFocus=\"Foco(this,'in')\"  style='width:309px' onBlur=\"Foco(this,'out');\" tabindex='"+(tabindex+2)+"'></select>";								

									addOption(document.getElementById('OpcaoValor_'+IdNotaFiscalLayout+'_'+IdNotaFiscalLayoutParametro),"","");										 																
								
									aux = OpcaoValor.split("\n");														
									while(aux[ii] != undefined){
										aux2 = aux[ii].split("^");										
										addOption(document.getElementById('OpcaoValor_'+IdNotaFiscalLayout+'_'+IdNotaFiscalLayoutParametro),aux2[1], aux2[0]);										
										ii++;
									}
									
									var nome = 'OpcaoValor_'+IdNotaFiscalLayout+'_'+IdNotaFiscalLayoutParametro;
									for(j=0; j<document.formulario.length; j++){										
										if(ValorDefault != ""){
											if(document.formulario[j].name == nome){
												document.formulario[j].value = ValorDefault;
											}
										}else{
											if(document.formulario[j].name == nome){
												document.formulario[j].value = "";	
											}
										}								
									}
								}
								linha2	= document.getElementById('tableParametro').insertRow(tam);
														
								c0	= linha2.insertCell(0);
								c1	= linha2.insertCell(1);
								c2	= linha2.insertCell(2);
								c3	= linha2.insertCell(3);				
								
								c0.innerHTML = '&nbsp;';
														
								c1.innerHTML = '<b style="color:#000">Descrição<b>';
								
								c2.innerHTML = '&nbsp;';
								
								c3.innerHTML = "<b style='color:#C10000'>Valor</b>";																							
							
								tabindex++;			
							}	
								
						}
						document.formulario.IdNotaFiscalTipo.value = IdNotaFiscalTipo;
						document.formulario.IdNotaFiscalLayout.value = IdNotaFiscalLayout;
						document.formulario.bt_alterar.disabled = false;
						document.getElementById('cpDadosParametros').style.display = 'block';
							
						busca_nota_fiscal_parametro(IdNotaFiscalTipo,IdNotaFiscalLayout,Erro,false);
						
					}					
				}
				// Fim de Carregando
				carregando(false);
			} 
			return true;
		}
		xmlhttp.send(null);
		return true;
	}
