	function inicia(){		
		document.formulario.bt_alterar.disabled = true;
		document.formulario.IdTipoMensagem.focus();
	}	

	function validar(){
		return true;
	}

	function busca_tipo_mensagem_parametro_layout(IdTipoMensagem,Erro){	
		if(IdTipoMensagem == ''){
			IdTipoMensagem = 0;
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
	    
	   	url = "../administrativo/xml/tipo_mensagem_parametro.php?IdTipoMensagem="+IdTipoMensagem;
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
						
						for(i=0; i<xmlhttp.responseXML.getElementsByTagName("IdTipoMensagem").length; i++){	
							
							tam 	= document.getElementById('tableParametro').rows.length;	
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("IdTipoMensagem")[i]; 
							nameTextNode = nameNode.childNodes[0];
							var IdTipoMensagem = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("IdTipoMensagemParametro")[i]; 
							nameTextNode = nameNode.childNodes[0];
							var IdTipoMensagemParametro = nameTextNode.nodeValue;							
										
							nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoTipoMensagemParametro")[i]; 
							nameTextNode = nameNode.childNodes[0];
							var DescricaoTipoMensagemParametro = nameTextNode.nodeValue;
						
							nameNode = xmlhttp.responseXML.getElementsByTagName("ValorTipoMensagemParametro")[i]; 
							nameTextNode = nameNode.childNodes[0];
							var ValorTipoMensagemParametroDefault = nameTextNode.nodeValue;
												
							linha	= document.getElementById('tableParametro').insertRow(tam);							
							
							c0	= linha.insertCell(0);
							c1	= linha.insertCell(1);
							c2	= linha.insertCell(2);
							c3	= linha.insertCell(3);	
							
							c0.innerHTML = '&nbsp;';
							
							c1.style.verticalAlign = 'top';
							c1.innerHTML = "<input type='hidden' name='IdTipoMensagem_"+IdTipoMensagem+'_'+IdTipoMensagemParametro+"' value='"+IdTipoMensagem+'_'+IdTipoMensagemParametro+"' readOnly><input  type='text' style='width:350px' name='DescricaoTipoMensagemParametro_"+IdTipoMensagem+'_'+IdTipoMensagemParametro+"' value='"+DescricaoTipoMensagemParametro+"' tabindex='"+(tabindex+1)+"' readOnly><BR />&nbsp;"; 
								
							c2.innerHTML = '&nbsp;';					

							c3.innerHTML = "<input type='text' name='ValorTipoMensagemParametro_"+IdTipoMensagem+'_'+IdTipoMensagemParametro+"' value='' style='width:301px' onFocus=\"Foco(this,'in')\" onBlur=\"Foco(this,'out')\" tabindex='"+(tabindex+2)+"'><BR />&nbsp"; 							
							
							linha2	= document.getElementById('tableParametro').insertRow(tam);

							c0	= linha2.insertCell(0);
							c1	= linha2.insertCell(1);
							c2	= linha2.insertCell(2);
							c3	= linha2.insertCell(3);				
							
							c0.innerHTML = '&nbsp;';

							c1.innerHTML = '<b style="color:#000">Descrição<b>';
							
							c2.innerHTML = '&nbsp;';
							
							c3.innerHTML = "<b id=cpValor_"+IdTipoMensagem+'_'+IdTipoMensagemParametro+" style='"+cor+"'>Valor</b>";																							
							
							tabindex++;					
						}
						document.formulario.bt_alterar.disabled = false;
						document.getElementById('cpDadosParametros').style.display = 'block';
							
						busca_tipo_mensagem_parametro(document.formulario.IdTipoMensagem.value,Erro,false);
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