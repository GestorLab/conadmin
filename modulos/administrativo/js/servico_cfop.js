	function verificaAcao(){
		if(document.formulario != undefined){
			if(document.formulario.IdServico.value != ''){
				document.formulario.bt_inserir.disabled = false;
			} else{
				document.formulario.bt_inserir.disabled = true;
			}
		}	
	}
	function inicia(){
		busca_cfop_opcoes();
		document.formulario.IdServico.focus();
	}	
	function validar(){		
		if(document.formulario.IdServico.value == ""){
			mensagens(1);
			document.formulario.IdServico.focus();
			return false;		
		}
		
		return true;
	}
	function listar_cfop_servico(IdServico){
		if(IdServico == '' || IdServico == undefined){
			IdServico = document.formulario.IdServico.value;
		}
		
		var tam, linha, c0, c1, c2;
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
	    
	   	url = "xml/lista_servico_cfop.php?IdServico="+IdServico;
		xmlhttp.open("GET", url,true);
		xmlhttp.onreadystatechange = function(){ 
			// Carregando...
			carregando(true);
			if(xmlhttp.readyState == 4){ 
				if(xmlhttp.status == 200){
					while(document.getElementById('tabelaServicoCFOP').rows.length > 2){
						document.getElementById('tabelaServicoCFOP').deleteRow(1);
					}
					
					if(xmlhttp.responseText == 'false'){
						if(document.getElementById('tabelaServicoCFOPTotal') != undefined){
							document.getElementById('tabelaServicoCFOPTotal').innerHTML = "Total: 0";
						}
						
						document.getElementById("cpServicoCFOP").style.display = "none";
						// Fim de Carregando
						carregando(false);
					} else{
						document.getElementById("cpServicoCFOP").style.display = "block";
						var CFOP, NaturezaOperacao;
						
						for(var i=0; i<xmlhttp.responseXML.getElementsByTagName("CFOP").length; i++){
							nameNode = xmlhttp.responseXML.getElementsByTagName("CFOP")[i]; 
							nameTextNode = nameNode.childNodes[0];
							CFOP = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("NaturezaOperacao")[i]; 
							nameTextNode = nameNode.childNodes[0];
							NaturezaOperacao = nameTextNode.nodeValue;
							
							tam 	= document.getElementById('tabelaServicoCFOP').rows.length;
							linha	= document.getElementById('tabelaServicoCFOP').insertRow(tam-1);
						
							if(i%2 != 0){
								linha.style.backgroundColor = "#E2E7ED";
							}
							
							linha.accessKey = CFOP; 
							
							c0	= linha.insertCell(0);	
							c1	= linha.insertCell(1);	
							c2	= linha.insertCell(2);	
							c3	= linha.insertCell(3);
							
							c0.innerHTML = CFOP;
							c0.style.cursor = "pointer";
							c0.style.padding =	"0 0 0 5px";
							
							c1.innerHTML = NaturezaOperacao;
							c1.style.cursor = "pointer";
							
							c2.innerHTML = "<img src='../../img/estrutura_sistema/ico_del.gif' alt='Excluir?' title='Excluir?' onClick=\"excluir(" + IdServico + ",'" + CFOP + "');\">";
							c2.style.textAlign = "center";
							c2.style.cursor = "pointer";
						}
						
						if(document.getElementById('tabelaServicoCFOPTotal') != undefined){
							document.getElementById('tabelaServicoCFOPTotal').innerHTML = "Total: "+i;
						}
						
						if(document.getElementById('helpText2') != undefined){
							document.getElementById('helpText2').innerHTML = "&nbsp;";
						}
					}	
					
					if(window.janela != undefined){
						window.janela.close();
					}
				}
				// Fim de Carregando
				verificaAcao();
				carregando(false);
			} 
			return true;
		}
		xmlhttp.send(null);
	}
	function excluir(IdServico, CFOP){
		if(IdServico == '' || undefined){
			IdServico = document.formulario.IdServico.value;
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
    		
   			url = "files/excluir/excluir_servico_cfop.php?IdServico=" + IdServico + "&CFOP=" + CFOP;
			xmlhttp.open("GET", url,true);
			xmlhttp.onreadystatechange = function(){ 
				// Carregando...
				carregando(true);
				if(xmlhttp.readyState == 4){ 
					if(xmlhttp.status == 200){
						var numMsg = parseInt(xmlhttp.responseText);
						mensagens2(numMsg);
						
						if(numMsg == 7){
							var aux = 0;
							for(var i=0; i<document.getElementById("tabelaServicoCFOP").rows.length; i++){
								if(CFOP == document.getElementById("tabelaServicoCFOP").rows[i].accessKey){
									document.getElementById("tabelaServicoCFOP").deleteRow(i);
									tableMultColor("tabelaServicoCFOP",document.filtro.corRegRand.value);
									aux=1;
									break;
								}
							}
							
							if(aux=1){
								var total = (document.getElementById('tabelaServicoCFOP').rows.length-2);
								document.getElementById("tabelaServicoCFOPTotal").innerHTML	= "Total: " + total;
								busca_cfop_opcoes();
								
								if(total == 0){
									document.getElementById("cpServicoCFOP").style.display = "none";
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
	function busca_cfop_opcoes(IdServico){
		if(IdServico == '' || IdServico == undefined){
			IdServico = document.formulario.IdServico.value;
		}
		
		var xmlhttp = false;
		var nameNode, nameTextNode, url;
		
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
		
		url = "xml/servico_cfop_opcoes.php?IdServico="+IdServico;
		xmlhttp.open("GET", url,true);
		xmlhttp.onreadystatechange = function(){ 
			// Carregando...
			carregando(true);
			if(xmlhttp.readyState == 4){ 
				if(xmlhttp.status == 200){
					while(document.formulario.IdCFOPDisponiveis.options.length > 0){
						document.formulario.IdCFOPDisponiveis.options[0] = null;
					}
					
					if(xmlhttp.responseText != 'false'){
						var CFOP, NaturezaOperacao;
						
						for(i=0; i<xmlhttp.responseXML.getElementsByTagName("CFOP").length; i++){
							nameNode = xmlhttp.responseXML.getElementsByTagName("CFOP")[i]; 
							nameTextNode = nameNode.childNodes[0];
							CFOP = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("NaturezaOperacao")[i]; 
							nameTextNode = nameNode.childNodes[0];
							NaturezaOperacao = nameTextNode.nodeValue;
							
							addOption(document.formulario.IdCFOPDisponiveis,CFOP + " - " + NaturezaOperacao,CFOP);	
						}
						
					}
				}
				// Fim de Carregando
				verificaAcao();
				carregando(false);
			} 
			return true;
		}
		xmlhttp.send(null);
	}
	function servico_cfop(){
		var i, count = 0, CFOP = '0';
		var IdServico = document.formulario.IdServico.value;
		
		if(IdServico == ""){
			return false;
		}
		
		for(i=0; i<document.formulario.IdCFOPDisponiveis.length; i++){
			if(document.formulario.IdCFOPDisponiveis[i].selected){
				count++;
			}
		}
		
		while(count > 0){
			for(i=0; i<document.formulario.IdCFOPDisponiveis.length; i++){
				if(document.formulario.IdCFOPDisponiveis[i].selected){
					CFOP += ',' + document.formulario.IdCFOPDisponiveis[i].value;
					document.formulario.IdCFOPDisponiveis[i] = null;
					count--;
					break;
				}
			}
		}
		
		seta_servico_cfop(IdServico, CFOP);
	}
	function seta_servico_cfop(IdServico, CFOP){
		var xmlhttp = false, retorno = true;
		
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
		
		url = "files/inserir/inserir_servico_cfop.php?IdServico=" + IdServico + "&CFOP=" + CFOP;
		xmlhttp.open("GET", url,true);
		xmlhttp.onreadystatechange = function(){ 
			// Carregando...
			carregando(true);
			if(xmlhttp.readyState == 4){ 
				if(xmlhttp.status == 200){
					var res = xmlhttp.responseText;
					document.formulario.Erro.value = res;
					
					verificaErro();
					listar_cfop_servico(IdServico);
				}
			}
			// Fim de Carregando
			verificaAcao();
			carregando(false);
			return true;
		}
		xmlhttp.send(null);	
	}