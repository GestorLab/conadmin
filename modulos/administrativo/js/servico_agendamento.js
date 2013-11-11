	function verificaAcao(){
		if(document.formulario != undefined){
			if(document.formulario.Acao.value=='inserir'){
				document.formulario.bt_inserir.disabled = false;
				document.formulario.bt_alterar.disabled = true;
				document.formulario.bt_excluir.disabled	= true;	
			}
			if(document.formulario.Acao.value=='alterar'){
				if(document.formulario.IdTipoServico.value == 1){
					document.formulario.bt_inserir.disabled = true;
					document.formulario.bt_alterar.disabled = false;
					document.formulario.bt_excluir.disabled	= false;
				} else{
					document.formulario.bt_inserir.disabled = true;
					document.formulario.bt_alterar.disabled = false;
					document.formulario.bt_excluir.disabled	= true;
				}
			}
		}	
	}
	function inicia(){
		document.formulario.IdServico.focus();
	}	
	function validar(){	
		document.getElementById("helpText2").innerHTML = "&nbsp;";
		
		if(document.formulario.IdServico.value == ""){
			mensagens(1);
			document.formulario.IdServico.focus();
			return false;		
		}
		
		if(document.formulario.IdTipoServico.value == 1){
			if(document.formulario.IdQtdMeses.value == "" || document.formulario.IdQtdMeses.value < 1){
				mensagens(1);
				document.formulario.IdQtdMeses.focus();
				return false;		
			}
			
			if(document.formulario.IdStatus.value == ""){
				mensagens(1);
				document.formulario.IdStatus.focus();
				return false;		
			}
			
			if(document.formulario.IdNovoStatus.value == ""){
				mensagens(1);
				document.formulario.IdNovoStatus.focus();
				return false;		
			}
			
			if((document.formulario.IdNovoStatus.value == 201 || document.formulario.IdNovoStatus.value == 306) && document.formulario.QtdDias.value == ""){
				mensagens(1);
				document.formulario.QtdDias.focus();
				return false;
			}
		} else{
			if((document.formulario.MudarStatusContratoConcluirOS.value == 201 || document.formulario.MudarStatusContratoConcluirOS.value == 306) && document.formulario.QtdDias.value == ""){
				mensagens(1);
				document.formulario.QtdDias.focus();
				return false;
			}
		}
		
		return true;
	}
	function busca_novo_status(IdStatusTemp, IdNovoStatusTemp, limpar){	
		while(document.formulario.IdNovoStatus.options.length > 0){
			document.formulario.IdNovoStatus.options[0] = null;
		}
		
		addOption(document.formulario.IdNovoStatus,"","");
		
		if(IdStatusTemp == 0 || IdStatusTemp == ''){
			document.formulario.IdNovoStatus.disabled = true;
			document.getElementById("titNovoStatus").style.color = "#000000";
			
			return false;
		}
		
		if(limpar){
			verificar_status();
		}
		
		if(IdNovoStatusTemp == undefined){
			IdNovoStatusTemp = '';
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

		url = "xml/parametro_sistema.php?IdGrupoParametroSistema=69";
		xmlhttp.open("GET", url,true);
		xmlhttp.onreadystatechange = function(){
			// Carregando...
			carregando(true);
			if(xmlhttp.readyState == 4){ 
				if(xmlhttp.status == 200){
					if(xmlhttp.responseText != 'false'){
						var nameNode, nameTextNode;
						
						for(var i=0; i<xmlhttp.responseXML.getElementsByTagName("IdParametroSistema").length; i++){
							nameNode = xmlhttp.responseXML.getElementsByTagName("IdParametroSistema")[i]; 
							nameTextNode = nameNode.childNodes[0];
							var IdParametroSistema = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("ValorParametroSistema")[i]; 
							nameTextNode = nameNode.childNodes[0];
							var ValorParametroSistema = nameTextNode.nodeValue;
						
							if(IdStatusTemp != IdParametroSistema){
								addOption(document.formulario.IdNovoStatus,ValorParametroSistema,IdParametroSistema);
							}
						}
						
						document.formulario.IdStatus.value = IdStatusTemp;
						
						if(IdNovoStatusTemp!=""){
							for(i=0;i<document.formulario.IdNovoStatus.length;i++){
								if(document.formulario.IdNovoStatus[i].value == IdNovoStatusTemp){
									document.formulario.IdNovoStatus[i].selected = true;
									break;
								}
							}
						}else{
							document.formulario.IdNovoStatus[0].selected = true;
						}
						
						document.formulario.IdNovoStatus.disabled = false;
						document.getElementById("titNovoStatus").style.color = "#C10000";
						document.formulario.IdNovoStatus.focus();
					}
				}
				// Fim de Carregando
				carregando(false);
			}
			return true;
		}
		xmlhttp.send(null);
	}
	function listar_servico_agendamento(IdServico){
		if(IdServico == undefined){
			IdServico = '';
		}
		
		document.formulario.QtdMesesTemp.value = '';
		document.getElementById("helpText2").innerHTML = "&nbsp;";
		
		while(document.getElementById('tabelaAgendamento').rows.length > 2){
			document.getElementById('tabelaAgendamento').deleteRow(1);
		}
		
		var tam, linha, c0, c1, c2, c3;
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
	    
	   	url = "xml/servico_agendamento.php?IdServico="+IdServico;
		xmlhttp.open("GET", url,true);
		xmlhttp.onreadystatechange = function(){ 
			// Carregando...
			carregando(true);
			if(xmlhttp.readyState == 4){ 
				if(xmlhttp.status == 200){
					if(xmlhttp.responseText == 'false'){
						if(document.getElementById('tabelaAgendamentoTotal') != undefined){
							document.getElementById('tabelaAgendamentoTotal').innerHTML = "Total: 0";
						}
						
						document.getElementById("cpAgendamentosCadastrados").style.display = "none";
						// Fim de Carregando
						carregando(false);
					}else{
						document.getElementById("cpAgendamentosCadastrados").style.display = "block";
						var IdServico, TipoServico, QtdMes, Status, NovoStatus;
						
						for(var i=0; i<xmlhttp.responseXML.getElementsByTagName("IdServico").length; i++){
							nameNode = xmlhttp.responseXML.getElementsByTagName("IdServico")[i]; 
							nameTextNode = nameNode.childNodes[0];
							IdServico = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("TipoServico")[i]; 
							nameTextNode = nameNode.childNodes[0];
							TipoServico = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("QtdMes")[i]; 
							nameTextNode = nameNode.childNodes[0];
							QtdMes = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("Status")[i]; 
							nameTextNode = nameNode.childNodes[0];
							Status = nameTextNode.nodeValue;
					
							nameNode = xmlhttp.responseXML.getElementsByTagName("NovoStatus")[i]; 
							nameTextNode = nameNode.childNodes[0];
							NovoStatus = nameTextNode.nodeValue;
							
							tam 	= document.getElementById('tabelaAgendamento').rows.length;
							linha	= document.getElementById('tabelaAgendamento').insertRow(tam-1);
						
							if(i%2 != 0){
								linha.style.backgroundColor = "#E2E7ED";
							}
							
							linha.accessKey = QtdMes; 
							
							c0	= linha.insertCell(0);	
							c1	= linha.insertCell(1);	
							c2	= linha.insertCell(2);	
							c3	= linha.insertCell(3);
							
							if(document.formulario.QtdMesesTemp.value != ''){
								document.formulario.QtdMesesTemp.value += ","+QtdMes;
							} else{
								document.formulario.QtdMesesTemp.value = QtdMes;
							}
							
							var linkIni = "<a onClick=\"busca_servico_agendamento("+IdServico+", "+QtdMes+", true);\">";
							var linkFim = "</a>";
							
							c0.innerHTML = linkIni + QtdMes + linkFim;
							c0.style.cursor = "pointer";
							c0.style.padding =	"0 0 0 5px";
							
							c1.innerHTML = linkIni + Status + linkFim;
							c1.style.cursor = "pointer";
							
							c2.innerHTML = linkIni + NovoStatus + linkFim;
							c2.style.cursor = "pointer";
							c2.style.padding =	"0 8px 0 0";
							
							c3.innerHTML = "<img src='../../img/estrutura_sistema/ico_del.gif' alt='Excluir?' title='Excluir?' onClick=\"excluir("+IdServico+","+QtdMes+", 'lista');\">";
							c3.style.textAlign = "center";
							c3.style.cursor = "pointer";
						}
						
						if(document.getElementById('tabelaAgendamentoTotal') != undefined){
							document.getElementById('tabelaAgendamentoTotal').innerHTML = "Total: "+i;
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
	function excluir(IdServico, QtdMes, tipo){
		if(IdServico == '' || undefined){
			IdServico = document.formulario.IdServico.value;
		}
		
		var IdTipoServico = document.formulario.IdTipoServico.value;
		
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
    		
   			url = "files/excluir/excluir_servico_agendamento.php?IdServico="+IdServico+"&QtdMes="+QtdMes;
			xmlhttp.open("GET", url,true);
			xmlhttp.onreadystatechange = function(){ 
				// Carregando...
				carregando(true);
				if(xmlhttp.readyState == 4){ 
					if(xmlhttp.status == 200){
						if(tipo != "lista"){
							document.formulario.Erro.value = xmlhttp.responseText;
							
							if(parseInt(xmlhttp.responseText) == 7){
								document.formulario.Acao.value 	= "inserir";
								url = "cadastro_servico_agendamento.php?Erro="+document.formulario.Erro.value+"&IdServico="+IdServico;
								window.location.replace(url);
							} else{
								mensagens2(0);
								verificaErro();
							}
						} else{
							var numMsg = parseInt(xmlhttp.responseText);
							mensagens2(numMsg);
							
							if(numMsg == 7){
								var aux = 0;
								for(var i=0; i<document.getElementById("tabelaAgendamento").rows.length; i++){
									if(QtdMes == document.getElementById("tabelaAgendamento").rows[i].accessKey){
										document.getElementById("tabelaAgendamento").deleteRow(i);
										tableMultColor("tabelaAgendamento",document.filtro.corRegRand.value);
										aux=1;
										break;
									}
								}
								
								if(aux=1){
									document.formulario.QtdMesesTemp.value = document.formulario.QtdMesesTemp.value.replace(QtdMes, '');
									document.formulario.QtdMesesTemp.value = document.formulario.QtdMesesTemp.value.replace(',,', ',');
									var tam = (document.getElementById('tabelaAgendamento').rows.length-2);
									document.getElementById("tabelaAgendamentoTotal").innerHTML	= "Total: "+tam;
									
									if(tam > 0){
										document.getElementById("cpAgendamentosCadastrados").style.display = "block";
									} else{
										document.getElementById("cpAgendamentosCadastrados").style.display = "none";
									}
									
									verificar_qtd_meses(document.formulario.IdQtdMeses.value);
								}
							}
						}
					}
				}
				// Fim de Carregando
				carregando(false);
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
					
					if(msg!=''){
						scrollWindow("bottom");
					}
					
					document.getElementById('helpText2').innerHTML = msg;
					document.getElementById('helpText2').style.display = "block";
					document.getElementById('helpText').style.display = "none";
					
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
	function verificar_qtd_meses(valor){
		valor = Math.round(valor);
		var valorTemp = document.formulario.QtdMesesTemp.value.split(',');
		
		for(var i in valorTemp){
			if(valorTemp[i] == valor || valor == 0){
				busca_servico_agendamento(document.formulario.IdServico.value, valor, true);
				return false;
			}
		}
		
		if(document.formulario.Acao.value == 'alterar'){
			document.formulario.IdStatus.value			= '';
			document.formulario.QtdDias.value			= '';
			document.formulario.LoginCriacao.value		= '';
			document.formulario.DataCriacao.value		= '';
			document.formulario.LoginAlteracao.value	= '';
			document.formulario.DataAlteracao.value		= '';
			document.formulario.Acao.value				= 'inserir';
			
			busca_novo_status(0);
			verificaAcao();
		}
	}
	function verificar_status(valor){
		if(valor == 201 || valor == 306){
			document.getElementById("cpQtdDias").style.display = "block";
		} else{
			document.formulario.QtdDias.value = '';
			document.getElementById("cpQtdDias").style.display = "none";
		}
	}