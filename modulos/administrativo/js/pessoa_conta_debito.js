	function inicia(){
		document.formulario.IdContaDebito.focus();
		status_inicial();
		scrollWindow('top');
	}
	function verificaAcao(){
	
		if(document.formulario != undefined){
			if(document.formulario.Acao.value == 'inserir'){
				document.formulario.bt_inserir.disabled 			= false;
				document.formulario.bt_alterar.disabled 			= true;
				document.formulario.bt_excluir.disabled 			= true;
				document.formulario.bt_imprimirAutorizacao.disabled = true;
			}
			
			if(document.formulario.Acao.value == 'alterar'){		
				document.formulario.bt_inserir.disabled 			= true;
				document.formulario.bt_alterar.disabled 			= false;
				document.formulario.bt_excluir.disabled 			= false;
				
				if(document.formulario.IdStatus.value == 2 || document.formulario.LocalCobranca.value == 0){
					document.formulario.bt_imprimirAutorizacao.disabled = true;
				} else{
					document.formulario.bt_imprimirAutorizacao.disabled = false;
				}
			}
		}	
	}
	function validar(){
	
		if(document.formulario.IdPessoa.value == ''){
			mensagens(1);
			document.formulario.IdPessoa.focus();
			return false;
		}
		
		if(document.formulario.DescricaoContaDebito.value == ''){
			mensagens(1);
			document.formulario.DescricaoContaDebito.focus();
			return false;
		}
		
		if(document.formulario.IdLocalCobranca.value == ''){
			mensagens(1);
			document.formulario.IdLocalCobranca.focus();
			return false;
		}
		
		if(document.formulario.NumeroAgencia.value == ''){
			mensagens(1);
			document.formulario.NumeroAgencia.focus();
			return false;
		}
		
		if(document.formulario.DigitoAgencia.value == '' && document.formulario.DigitoAgencia.style.display == 'block'){
			mensagens(1);
			document.formulario.DigitoAgencia.focus();
			return false;
		}
		
		if(document.formulario.NumeroConta.value == ''){
			mensagens(1);
			document.formulario.NumeroConta.focus();
			return false;
		}
		
		if(document.formulario.DigitoConta.value == ''){
			mensagens(1);
			document.formulario.DigitoConta.focus();
			return false;
		}
		
		if(document.formulario.IdStatus.value == ''){
			mensagens(1);
			document.formulario.IdStatus.focus();
			return false;
		}
		
		mensagens(0);
		return true;
	}
	function cadastrar(acao){	
		document.formulario.Acao.value = acao;
		
		switch(acao){
			case "inserir":
				if(validar(acao) == true){
					document.formulario.submit();
				}
				break;
			case "alterar":
				if(validar(acao) == true){
					document.formulario.submit();
				}
				break;
			case "imprimirAutorizacao":
				window.open("local_cobranca/"+document.formulario.IdLocalCobrancaLayout.value+"/carta_autorizacao.php?IdPessoa="+document.formulario.IdPessoa.value+"&IdContaDebito="+document.formulario.IdContaDebito.value);
				break;
		}
	}
	function excluir(IdPessoa, IdContaDebito, listar){
		if(excluir_registro() == true){
			var xmlhttp = false;
			
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
    
   			url = "files/excluir/excluir_pessoa_conta_debito.php?IdPessoa="+IdPessoa+"&IdContaDebito="+IdContaDebito;
			xmlhttp.open("GET", url,true);
			xmlhttp.onreadystatechange = function(){ 
				// Carregando...
				carregando(true);

				if(xmlhttp.readyState == 4){ 
					if(xmlhttp.status == 200){
						if(listar != "listar"){
							if(document.formulario != undefined){
								document.formulario.Erro.value = xmlhttp.responseText;
								if(parseInt(xmlhttp.responseText) == 7){
									document.formulario.Acao.value 	= 'inserir';
									url = 'cadastro_pessoa_conta_debito.php?Erro=' + document.formulario.Erro.value + "&IdPessoa=" + IdPessoa;
									window.location.replace(url);
								}else{
									verificaErro();
								}
							}
						} else{
							var numMsg = parseInt(xmlhttp.responseText);
							mensagens2(numMsg);
							
							if(numMsg == 7){
								var aux = 0;
								var tam = document.getElementById('tabelaContaDebito').rows.length-2;
								
								for(var i = 1; i <= tam; i++){
									if(document.getElementById('tabelaContaDebito').rows[i].accessKey == IdContaDebito){
										document.getElementById('tabelaContaDebito').deleteRow(i);
										tableMultColor('tabelaContaDebito', document.filtro.corRegRand.value);
										break;
									}
								}
								
								if(document.formulario.IdContaDebito.value == IdContaDebito){
									limpar_formulario_conta_debito();
								}
								
								tam = (document.getElementById('tabelaContaDebito').rows.length-2);
								document.getElementById('tabelaContaDebitoTotal').innerHTML = "Total: " + tam;
								
								if(tam > 0){
									document.getElementById("cp_conta_debito_cadastrada").style.display = "block";
								} else{
									document.getElementById("cp_conta_debito_cadastrada").style.display = "none";
									mensagens(numMsg);
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
	function mensagens2(n){
		var msg='';
		var prioridade='';
		var xmlhttp   = false;
		
		if(n == 0){
			document.getElementById('helpText2').style.display = "none";
		}
		
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
					} else{
						msg = '';
					}
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("pri"+n)[0]; 
					
					if(nameNode != null){
						nameTextNode = nameNode.childNodes[0];
						prioridade = nameTextNode.nodeValue;
					} else{
						prioridade = '';
					}
					
					if(msg!=''){
						scrollWindow("bottom");
					}
					
					document.getElementById('helpText2').innerHTML = msg;
					document.getElementById('helpText2').style.display = "block";
					document.getElementById('helpText').innerHTML = "&nbsp;";
					//document.getElementById('helpText').style.display = "none";
					
					switch (prioridade){
						case 'atencao':
							document.getElementById('helpText2').style.color = "#C10000";
							break;
						default:
							document.getElementById('helpText2').style.color = "#004975";
					}
				}
			}
			return true;
		}
		xmlhttp.send(null);
	}
	function listar_conta_debito(IdPessoa,Erro,Local){
		if(IdPessoa == undefined || IdPessoa == ''){
			IdPessoa = 0;
		}

		if(Local == ''){
			Local = document.formulario.Local.value;
		}
		
		var nameNode, nameTextNode, url, Condicao;
		var xmlhttp = false;
		
		if (window.XMLHttpRequest) { // Mozilla, Safari,...
	    	xmlhttp = new XMLHttpRequest();
			
	        if(xmlhttp.overrideMimeType){
	        	xmlhttp.overrideMimeType('text/xml');
			}
		} else if (window.ActiveXObject){ // IE
			try{
				xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
			}catch(e){
				try{
					xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
	            } catch (e) {}
	        }
	    }
	    
	   	url = "xml/pessoa_conta_debito.php?IdPessoa="+IdPessoa;
		xmlhttp.open("GET", url,true);
		xmlhttp.onreadystatechange = function(){
			// Carregando...
			carregando(true);
	
			if(xmlhttp.readyState == 4){ 
				if(xmlhttp.status == 200){
			
					if(xmlhttp.responseText == 'false'){
						while(document.getElementById('tabelaContaDebito').rows.length > 2){
							document.getElementById('tabelaContaDebito').deleteRow(1);
						}
						
						document.getElementById("cp_conta_debito_cadastrada").style.display = "none";
						document.getElementById('tabelaContaDebitoTotal').innerHTML = "Total: 0";	
						// Fim de Carregando
						carregando(false);
					}else{
						var tam, linha, c0, c1, c2, c3, c4, c5, c6;
						var IdContaDebito, AbreviacaoNomeLocalCobranca, DescricaoContaDebito, NumeroAgencia, DigitoAgencia, NumeroConta, DigitoConta, Status;
						document.getElementById("cp_conta_debito_cadastrada").style.display = "block";
						
						while(document.getElementById('tabelaContaDebito').rows.length > 2){
							document.getElementById('tabelaContaDebito').deleteRow(1);
						}
						
						for(var i=0; i<xmlhttp.responseXML.getElementsByTagName("IdContaDebito").length; i++){	
							nameNode = xmlhttp.responseXML.getElementsByTagName("IdContaDebito")[i]; 
							nameTextNode = nameNode.childNodes[0];
							IdContaDebito = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("AbreviacaoNomeLocalCobranca")[i]; 
							nameTextNode = nameNode.childNodes[0];
							AbreviacaoNomeLocalCobranca = nameTextNode.nodeValue;

							nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoContaDebito")[i]; 
							nameTextNode = nameNode.childNodes[0];
							DescricaoContaDebito = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("NumeroAgencia")[i]; 
							nameTextNode = nameNode.childNodes[0];
							NumeroAgencia = nameTextNode.nodeValue;	
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("DigitoAgencia")[i]; 
							nameTextNode = nameNode.childNodes[0];
							DigitoAgencia = nameTextNode.nodeValue;	
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("NumeroConta")[i]; 
							nameTextNode = nameNode.childNodes[0];
							NumeroConta = nameTextNode.nodeValue;	
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("DigitoConta")[i]; 
							nameTextNode = nameNode.childNodes[0];
							DigitoConta = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("Status")[i]; 
							nameTextNode = nameNode.childNodes[0];
							Status = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("ValorLocalCobrancaParametroDefault")[i]; 
							nameTextNode = nameNode.childNodes[0];
							var ValorLocalCobrancaParametroDefault = nameTextNode.nodeValue;
						
											
							tam 	= document.getElementById('tabelaContaDebito').rows.length;
							linha	= document.getElementById('tabelaContaDebito').insertRow(tam-1);
							
							if(tam%2 != 0){
								linha.style.backgroundColor = "#E2E7ED";
							}
							
							linha.accessKey = IdContaDebito; 
							
							c0	= linha.insertCell(0);	
							c1	= linha.insertCell(1);	
							c2	= linha.insertCell(2);	
							c3	= linha.insertCell(3);
							c4	= linha.insertCell(4);
							c5	= linha.insertCell(5);
							c6	= linha.insertCell(6);
							
							var linkIni = "<a href=\"javaScript: busca_pessoa_conta_debito("+IdPessoa+", "+IdContaDebito+", true, '"+Local+"');\">";
							var linkFim = "</a>";
							
							c0.innerHTML = linkIni + IdContaDebito + linkFim;
							c0.style.cursor  = "pointer";
							c0.style.padding =	"0 0 0 5px";
							
							c1.innerHTML = linkIni + AbreviacaoNomeLocalCobranca + linkFim;
							c1.style.cursor = "pointer";
							c1.style.marginRight = "4px";
							
							c2.innerHTML = linkIni + DescricaoContaDebito + linkFim;
							c2.style.cursor = "pointer";
							c2.style.marginRight = "4px";
							
							if (ValorLocalCobrancaParametroDefault == 1){
								c3.innerHTML = linkIni + NumeroAgencia + " - " + DigitoAgencia + linkFim;
							}else{
								c3.innerHTML = linkIni + NumeroAgencia + linkFim;
							}	
							c3.style.cursor = "pointer";
							c3.style.marginRight = "4px";
							
							c4.innerHTML = linkIni + NumeroConta + " - " + DigitoConta + linkFim;
							c4.style.cursor = "pointer";
							c4.style.marginRight = "4px";
							
							c5.innerHTML = linkIni + Status + linkFim;
							c5.style.cursor = "pointer";
							c5.style.marginRight = "4px";
							
							c6.innerHTML    = "<img src='../../img/estrutura_sistema/ico_del.gif' alt='Excluir?' onClick=\"excluir("+IdPessoa+", "+IdContaDebito+", 'listar')\">";
							c6.style.cursor = "pointer";
						}
						
						document.getElementById('tabelaContaDebitoTotal').innerHTML			=	"Total: "+i;	
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
	function limpar_formulario_conta_debito(){
		document.formulario.IdContaDebito.value			= '';
		document.formulario.DescricaoContaDebito.value	= '';
		document.formulario.IdLocalCobranca.value		= '';
		document.formulario.NumeroAgencia.value			= '';
		document.formulario.DigitoAgencia.value			= '';
		document.formulario.NumeroConta.value			= '';
		document.formulario.DigitoConta.value			= '';
		document.formulario.LoginCriacao.value			= '';
		document.formulario.DataCriacao.value			= '';
		document.formulario.LoginAlteracao.value		= '';
		document.formulario.DataAlteracao.value			= '';
		document.formulario.Acao.value					= 'inserir';
		
		document.formulario.IdContaDebito.focus();
		verificaAcao();
	}
	function buscar_local_cobranca(IdLocalCobrancaTemp, litarTodos){
	
		if(IdLocalCobrancaTemp == undefined){
			IdLocalCobrancaTemp = '';
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
	    
	    url = "xml/local_cobranca.php?IdTipo=3";
		
		if(!litarTodos){
			url += "&IdStatus=1";
		}
		
		xmlhttp.open("GET", url,true);
		// Carregando...
		carregando(true);
		
		xmlhttp.onreadystatechange = function(){ 
			if(xmlhttp.readyState == 4){ 
				if(xmlhttp.status == 200){
				
					if(xmlhttp.responseText != 'false'){
						while(document.formulario.IdLocalCobranca.options.length > 0){
							document.formulario.IdLocalCobranca.options[0] = null;
						}
						
						addOption(document.formulario.IdLocalCobranca,"","");
						
						var nameNode, nameTextNode, IdLocalCobranca;
						document.formulario.LocalCobranca.value = 1;
						
						for(var i=0; i<xmlhttp.responseXML.getElementsByTagName("IdLocalCobranca").length; i++){
							nameNode = xmlhttp.responseXML.getElementsByTagName("IdLocalCobranca")[i]; 
							nameTextNode = nameNode.childNodes[0];
							var IdLocalCobranca = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoLocalCobranca")[i]; 
							nameTextNode = nameNode.childNodes[0];
							var DescricaoLocalCobranca = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("IdStatus")[i]; 
							nameTextNode = nameNode.childNodes[0];
							var IdStatus = nameTextNode.nodeValue;
							
							if(IdLocalCobrancaTemp == IdLocalCobranca || IdStatus == 1){
								addOption(document.formulario.IdLocalCobranca, DescricaoLocalCobranca, IdLocalCobranca);
								
								if(document.formulario.LocalCobranca.value != 0){
									document.formulario.LocalCobranca.value = IdStatus;
								}
							}
						}
						
						if(IdLocalCobrancaTemp != ''){
							for(i = 0; i < document.formulario.IdLocalCobranca.length; i++){
								if(document.formulario.IdLocalCobranca[i].value == IdLocalCobrancaTemp){
									document.formulario.IdLocalCobranca[i].selected	=	true;
									break;
								}
							}
						} else{
							document.formulario.IdLocalCobranca[0].selected	=	true;
						}						
					} else{
						while(document.formulario.IdLocalCobranca.options.length > 0){
							document.formulario.IdLocalCobranca.options[0] = null;
						}
						
						addOption(document.formulario.IdLocalCobranca, '', '');
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
	function verifica_layout_local_cobranca(IdContaDebito, IdPessoa, IdLocalCobranca, Erro, Local){
		if(IdLocalCobranca == '' || IdLocalCobranca == undefined){
			IdLocalCobranca = 0;
		}
			
		if(Local == '' || Local == undefined){
			Local	=	document.formulario.Local.value;
		}
		
		var nameNode, nameTextNode, url;
		var xmlhttp   = false;
		
		if (window.XMLHttpRequest) { // Mozilla, Safari,...
	    	xmlhttp = new XMLHttpRequest();
			
	        if(xmlhttp.overrideMimeType){
	        	xmlhttp.overrideMimeType('text/xml');
			}
		} else if (window.ActiveXObject){ // IE
			try{
				xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
			}catch(e){
				try{
					xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
	            } catch (e) {}
	        }
	    }
		url = "xml/local_cobranca_layout_parametro_default.php?IdLocalCobranca="+IdLocalCobranca;
		xmlhttp.open("GET", url,true);
		xmlhttp.onreadystatechange = function(){ 
			//Carregando...
			carregando(true);
	
			if(xmlhttp.readyState == 4){ 
				if(xmlhttp.status == 200){
					if(Erro != false){
						document.formulario.Erro.value = 0;
					}
					
					if(xmlhttp.responseText == 'false'){
					
						document.formulario.DescricaoContaDebito.value	= '';
						document.formulario.IdLocalCobrancaLayout.value	= '';
						document.formulario.NumeroAgencia.value			= '';
						document.formulario.DigitoAgencia.value			= '';
						document.formulario.NumeroConta.value			= '';
						document.formulario.DigitoConta.value			= '';
						document.formulario.HistoricoObs.value			= '';
						document.formulario.IdStatus.value				= '';
						document.formulario.LoginCriacao.value			= '';
						document.formulario.DataCriacao.value			= '';
						document.formulario.LoginAlteracao.value		= '';
						document.formulario.DataAlteracao.value			= '';
						document.formulario.Acao.value					= 'inserir';
						document.getElementById('titDigitoAgencia').style.display	= 'block';
						document.formulario.DigitoAgencia.style.display		        = 'block';
						document.getElementById('titHifem').style.display			= 'block';
						document.formulario.NumeroAgencia.style.width				= '237px';
						document.formulario.DigitoAgencia.style.width				= '85px';
						document.formulario.NumeroConta.style.width					= '237px';
						document.formulario.DigitoConta.style.width					= '85px';
						document.formulario.IdStatus.style.width					= '109px';
						document.getElementById('titNumeroConta').style.marginLeft	= '9px';
						document.formulario.NumeroConta.style.marginLeft			= '9px';
						document.formulario.IdContaDebito.focus();
						
						// Fim de Carregando
						carregando(false);
					}else{
						nameNode = xmlhttp.responseXML.getElementsByTagName("ValorLocalCobrancaParametroDefault")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var ValorLocalCobrancaParametroDefault = nameTextNode.nodeValue;
						
						if (ValorLocalCobrancaParametroDefault == 2){
							document.getElementById('titDigitoAgencia').style.display	= 'none';
							document.getElementById('td_titDigitoAgencia').style.width 	= '0px';
							document.formulario.DigitoAgencia.style.display		        = 'none';
							document.formulario.DigitoAgencia.value						= '';
							document.getElementById('td_cpDigitoAgencia').style.width 	= '0px';
							document.getElementById('titHifem').style.display			= 'none';
							document.formulario.NumeroAgencia.style.width				= '287px';
							document.formulario.NumeroConta.style.width					= '287px';
							document.formulario.DigitoConta.style.width					= '85px';
							document.formulario.IdStatus.style.width					= '110px';
							document.getElementById('titNumeroConta').style.marginLeft	= '0px';
							document.formulario.NumeroConta.style.marginLeft			= '0px';
						}else{
							document.getElementById('titDigitoAgencia').style.display	= 'block';
							document.getElementById('td_titDigitoAgencia').style.width 	= '85px';
							document.formulario.DigitoAgencia.style.display		        = 'block';
							document.getElementById('titHifem').style.display			= 'block';
							document.formulario.NumeroAgencia.style.width				= '237px';
							document.getElementById('td_cpDigitoAgencia').style.width 	= '85px';
							document.formulario.DigitoAgencia.style.width				= '85px';
							document.formulario.NumeroConta.style.width					= '237px';
							document.formulario.DigitoConta.style.width					= '85px';
							document.formulario.IdStatus.style.width					= '109px';
							document.getElementById('titNumeroConta').style.marginLeft	= '9px';
							document.formulario.NumeroConta.style.marginLeft			= '9px';
						}
								
					}
					
					if(window.janela != undefined){
						window.janela.close();
					}
					
				//	verificaAcao();
				}
				// Fim de Carregando
				carregando(false);
			} 
			return true;
		}
		xmlhttp.send(null);
	}