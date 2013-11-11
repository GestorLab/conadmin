	function inicia(){		
		document.formulario.bt_alterar.disabled = true;
		document.formulario.IdLocalCobranca.focus();
	}	
	function validar(){		
		for(i=0; i<document.formulario.length; i++){
			if(document.formulario[i].name.substr(0,12) == "Obrigatorio_"){
				if(document.formulario[i].value == 1 && document.formulario[i-1].value == ""){
					mensagens(1);
					document.formulario[i-1].focus();
					return false;			
				}
			}	
		}				
		return true;
	}
	function busca_local_cobraca_layout_parametro(IdLocalCobrancaLayout,Erro){	
		if(IdLocalCobrancaLayout == ''){
			IdLocalCobrancaLayout = 0;
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
	    
	   	url = "../administrativo/xml/local_cobranca_layout_parametro.php?IdLocalCobrancaLayout="+IdLocalCobrancaLayout;
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
						
						for(i=0; i<xmlhttp.responseXML.getElementsByTagName("IdLocalCobrancaLayout").length; i++){	
							
							tam 	= document.getElementById('tableParametro').rows.length;	
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("IdLocalCobrancaParametro")[i]; 
							nameTextNode = nameNode.childNodes[0];
							var IdLocalCobrancaParametro = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("IdLocalCobrancaLayout")[i]; 
							nameTextNode = nameNode.childNodes[0];
							var IdLocalCobrancaLayout = nameTextNode.nodeValue;							
										
							nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoLocalCobrancaParametro")[i]; 
							nameTextNode = nameNode.childNodes[0];
							var DescricaoLocalCobrancaParametro = nameTextNode.nodeValue;
						
							nameNode = xmlhttp.responseXML.getElementsByTagName("ValorLocalCobrancaParametroDefault")[i]; 
							nameTextNode = nameNode.childNodes[0];
							var ValorLocalCobrancaParametroDefault = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("ObsLocalCobrancaParametro")[i]; 
							nameTextNode = nameNode.childNodes[0];
							var ObsLocalCobrancaParametro = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("Obrigatorio")[i]; 
							nameTextNode = nameNode.childNodes[0];
							var Obrigatorio = nameTextNode.nodeValue;							

							nameNode = xmlhttp.responseXML.getElementsByTagName("verificaLog")[i]; 
							nameTextNode = nameNode.childNodes[0];
							var verificaLog = nameTextNode.nodeValue;
							
							if(verificaLog != 2){
								nameNode = xmlhttp.responseXML.getElementsByTagName("LogParametro")[i]; 
								nameTextNode = nameNode.childNodes[0];
								var LogParametro = nameTextNode.nodeValue;								
								document.formulario.log.value = LogParametro;
							}
							var j = 0;							
							var aux = ObsLocalCobrancaParametro.split("\n");
							
							ObsLocalCobrancaParametro = "";
							while(aux[j] != "" && aux[j] != undefined){
								ObsLocalCobrancaParametro += aux[j]+"<br>"; 	
								j++;	
							}								
							
							if(Obrigatorio == 1){
								cor = "color: #C10000";
							}else{
								cor = "color: #000";	
							}
							
							linha	= document.getElementById('tableParametro').insertRow(tam);							
												
							if(ObsLocalCobrancaParametro == "") ObsLocalCobrancaParametro = "&nbsp;";
							
							c0	= linha.insertCell(0);
							c1	= linha.insertCell(1);
							c2	= linha.insertCell(2);
							c3	= linha.insertCell(3);	
							
							c0.innerHTML = '&nbsp;';
							
							c1.style.verticalAlign = 'top';
							c1.innerHTML = "<input type='hidden' name='IdLocalCobrancaLayoutParametro_"+IdLocalCobrancaLayout+'_'+IdLocalCobrancaParametro+"' value='"+IdLocalCobrancaLayout+'_'+IdLocalCobrancaParametro+"' readOnly><input  type='text' style='width:350px' name='DescricaoLocalCobrancaParametro_"+IdLocalCobrancaLayout+'_'+IdLocalCobrancaParametro+"' value='"+DescricaoLocalCobrancaParametro+"' tabindex='"+(tabindex+1)+"' readOnly><BR />&nbsp;"; 
								
							c2.innerHTML = '&nbsp;';					

							c3.innerHTML = "<input type='text' name='ValorLocalCobrancaParametro_"+IdLocalCobrancaLayout+'_'+IdLocalCobrancaParametro+"' value='"+ValorLocalCobrancaParametroDefault+"' style='width:301px' onFocus=\"Foco(this,'in')\" onBlur=\"Foco(this,'out')\" tabindex='"+(tabindex+2)+"'> <img src='../../img/estrutura_sistema/historicoLocaldeCobrancaParametro.png' style='position:relative; top:2px;' onmousemove=\"quadro_alt(event,this,'"+document.formulario.log.value+"')\" width='16' height='16'><input type='hidden' name='Obrigatorio_"+IdLocalCobrancaLayout+'_'+IdLocalCobrancaParametro+"' value='"+Obrigatorio+"' readOnly><BR />"+ObsLocalCobrancaParametro+""; 							
							
							linha2	= document.getElementById('tableParametro').insertRow(tam);

							c0	= linha2.insertCell(0);
							c1	= linha2.insertCell(1);
							c2	= linha2.insertCell(2);
							c3	= linha2.insertCell(3);				
							
							c0.innerHTML = '&nbsp;';

							c1.innerHTML = '<b style="color:#000">Descrição<b>';
							
							c2.innerHTML = '&nbsp;';
							
							c3.innerHTML = "<b id=cpValor_"+IdLocalCobrancaLayout+'_'+IdLocalCobrancaParametro+" style='"+cor+"'>Valor</b>";																							
						
							tabindex++;					
						}
						document.formulario.bt_alterar.disabled = false;
						document.getElementById('cpDadosParametros').style.display = 'block';
							
						busca_local_cobranca_parametro(document.formulario.IdLocalCobranca.value,Erro,false);
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
