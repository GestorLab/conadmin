	function inicia(){
		status_inicial();
		document.formulario.IdLocalCobranca.focus();
	}
	function excluir(IdLocalCobranca,IdLocalCobrancaParametro,listar){
		if(IdLocalCobranca== '' || IdLocalCobranca==undefined){
			IdLocalCobranca = document.formulario.IdLocalCobranca.value;
		}
		if(IdLocalCobrancaParametro== '' || IdLocalCobrancaParametro==undefined){
			IdLocalCobrancaParametro = document.formulario.IdLocalCobrancaParametro.value;
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
    
   			url = "files/excluir/excluir_local_cobranca_parametro.php?IdLocalCobranca="+IdLocalCobranca+"&IdLocalCobrancaParametro="+IdLocalCobrancaParametro;
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
									if(document.formulario.IdLocalCobrancaParametro.value == IdLocalCobrancaParametro){
										document.formulario.IdLocalCobrancaParametro.value 		  = '';		
										document.formulario.DescricaoLocalCobrancaParametro.value = '';
										document.formulario.Obrigatorio.value					  = '';
										document.formulario.ValorLocalCobrancaParametro.value     = '';
										document.formulario.ObsLocalCobrancaParametro.value		  = '';
										document.formulario.Acao.value						= 'inserir';
								
										status_inicial();
										verificaAcao();
										
										document.formulario.IdLocalCobrancaParametro.focus();
									}
									
									for(var i=0; i<document.getElementById('tabelaParametro').rows.length; i++){
										if(IdLocalCobrancaParametro == document.getElementById('tabelaParametro').rows[i].accessKey){
											document.getElementById('tabelaParametro').deleteRow(i);
											tableMultColor('tabelaParametro',document.filtro.corRegRand.value);
											document.getElementById('tabelaParametroTotal').innerHTML			=	"Total: "+(document.getElementById('tabelaParametro').rows.length-2);
											break;
										}
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
									url = 'cadastro_local_cobranca_parametro.php?Erro='+document.formulario.Erro.value+'&IdLocalCobranca='+IdLocalCobranca;
									window.location.replace(url);
								}else{
									verificaErro();
								}
							}
						}else{
							var numMsg = parseInt(xmlhttp.responseText);
							mensagens(numMsg);
							if(numMsg == 7){
								var aux = 0;
								for(var i=0; i<document.getElementById('tableListar').rows.length; i++){
									if(IdLocalCobranca+"_"+IdLocalCobrancaParametro == document.getElementById('tableListar').rows[i].accessKey){
										document.getElementById('tableListar').deleteRow(i);
										tableMultColor('tableListar',document.filtro.corRegRand.value);
										aux=1;
										break;
									}
								}
								if(aux=1){
									document.getElementById("tableListarTotal").innerHTML	=	"Total: "+(document.getElementById('tableListar').rows.length-2);
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
	function validar(){
		if(document.formulario.IdLocalCobrancaParametro.value==""){
			mensagens(1);
			document.formulario.IdLocalCobrancaParametro.focus();
			return false;
		}
		if(document.formulario.DescricaoLocalCobrancaParametro.value==""){
			mensagens(1);
			document.formulario.DescricaoLocalCobrancaParametro.focus();
			return false;
		}
		if(document.formulario.Obrigatorio.value==""){
			mensagens(1);
			document.formulario.Obrigatorio.focus();
			return false;
		}
		return true;
	}
	function listarParametro(IdLocalCobranca,Erro){
		while(document.getElementById('tabelaParametro').rows.length > 2){
			document.getElementById('tabelaParametro').deleteRow(1);
		}		
		
		var tam, linha, c0, c1, c2, c3, c4, c5;
		
		if(IdLocalCobranca == '' || IdLocalCobranca == undefined){
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
	    
	   	url = "xml/local_cobranca_parametro.php?IdLocalCobranca="+IdLocalCobranca;
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
						document.getElementById('tabelaParametroTotal').innerHTML			=	"Total: 0";
					}else{
						var IdLocalCobrancaParametro,DescricaoLocalCobrancaParametro,ValorLocalCobrancaParamentro,DescObrigatorio;
						for(var i=0; i<xmlhttp.responseXML.getElementsByTagName("IdLocalCobrancaParametro").length; i++){
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("IdLocalCobrancaParametro")[i]; 
							nameTextNode = nameNode.childNodes[0];
							IdLocalCobrancaParametro = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoLocalCobrancaParametro")[i]; 
							nameTextNode = nameNode.childNodes[0];
							DescricaoLocalCobrancaParametro = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("ValorLocalCobrancaParametro")[i]; 
							nameTextNode = nameNode.childNodes[0];
							ValorLocalCobrancaParametro = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("DescObrigatorio")[i]; 
							nameTextNode = nameNode.childNodes[0];
							DescObrigatorio = nameTextNode.nodeValue;	
							
							tam 	= document.getElementById('tabelaParametro').rows.length;
							linha	= document.getElementById('tabelaParametro').insertRow(tam-1);
								
							if(i%2 != 0){
								linha.style.backgroundColor = "#E2E7ED";
							}
							
							linha.accessKey = IdLocalCobrancaParametro; 
							
							c0	= linha.insertCell(0);	
							c1	= linha.insertCell(1);	
							c2	= linha.insertCell(2);	
							c3	= linha.insertCell(3);	
							c4	= linha.insertCell(4);
							
							var linkIni = "<a href='#' onClick=\"busca_local_cobranca_parametro("+IdLocalCobranca+",false,'"+document.formulario.Local.value+"','"+IdLocalCobrancaParametro+"');\">";
							var linkFim = "</a>";
							
							c0.innerHTML = linkIni + IdLocalCobrancaParametro.substr(0,50) + linkFim;
							c0.style.cursor  = "pointer";
							c0.style.padding =	"0 0 0 5px";
							
							c1.innerHTML = linkIni + DescricaoLocalCobrancaParametro.substr(0,50) + linkFim;
							c1.style.cursor = "pointer";
							
							c2.innerHTML = linkIni + ValorLocalCobrancaParametro.substr(0,50) + linkFim;
							c2.style.cursor = "pointer";
							
							c3.innerHTML = linkIni + DescObrigatorio + linkFim;
							c3.style.cursor = "pointer";
										
							c4.innerHTML = "<img src='../../img/estrutura_sistema/ico_del.gif' alt='Excluir?' onClick=\"excluir("+IdLocalCobranca+",'"+IdLocalCobrancaParametro+"','listar')\">";
							c4.style.textAlign = "center";
							c4.style.cursor = "pointer";
						}
						document.getElementById('tabelaParametroTotal').innerHTML			=	"Total: "+i;
						
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
