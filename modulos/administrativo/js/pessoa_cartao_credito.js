	var contadordigito = 0;
	
	function inicia(){
		document.formulario.IdCartao.focus();
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
			}
		}	
	}
	function validar(){
	
		if(document.formulario.IdPessoa.value == ''){
			mensagens(1);
			document.formulario.IdPessoa.focus();
			return false;
		}
		
		if(document.formulario.IdBandeiraCartao.value == ''){
			mensagens(1);
			document.formulario.IdBandeiraCartao.focus();
			return false;
		}
		
		if(document.formulario.NomeTitular.value == ''){
			mensagens(1);
			document.formulario.NomeTitular.focus();
			return false;
		}
		
		if(document.formulario.NumeroCartao.value == ''){
			mensagens(1);
			document.formulario.NumeroCartao.focus();
			return false;
		}
		
		if(document.formulario.MesValidade.value == ''){
			mensagens(1);
			document.formulario.MesValidade.focus();
			return false;
		}
		
		if(document.formulario.AnoValidade.value == ''){
			mensagens(1);
			document.formulario.AnoValidade.focus();
			return false;
		}
		
		if(document.formulario.CodigoSeguranca.value == ''){
			mensagens(1);
			document.formulario.CodigoSeguranca.focus();
			return false;
		}
		
		if(document.formulario.DiaVencimentoFatura.value == ''){
			mensagens(1);
			document.formulario.DiaVencimentoFatura.focus();
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
				window.open("local_cobranca/"+document.formulario.IdLocalCobrancaLayout.value+"/carta_autorizacao.php?IdPessoa="+document.formulario.IdPessoa.value+"&IdContaDebito="+document.formulario.IdCartaoCredito.value);
				break;
		}
	}
	function excluir(IdPessoa, IdCartao, listar){
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
    
   			url = "files/excluir/excluir_pessoa_cartao_credito.php?IdPessoa="+IdPessoa+"&IdCartao="+IdCartao;
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
									url = 'cadastro_pessoa_cartao_credito.php?Erro=' + document.formulario.Erro.value + "&IdPessoa=" + IdPessoa;
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
									if(document.getElementById('tabelaContaDebito').rows[i].accessKey == IdCartao){
										document.getElementById('tabelaContaDebito').deleteRow(i);
										tableMultColor('tabelaContaDebito', document.filtro.corRegRand.value);
										break;
									}
								}
								
								if(document.formulario.IdCartao.value == IdCartao){
									limpar_formulario_cartao_credito();
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
	
	function limpar_formulario_cartao_credito(){
		document.formulario.IdCartao.value				= '';
		document.formulario.IdBandeiraCartao.value		= '';
		document.formulario.NomeTitular.value			= '';
		document.formulario.NumeroCartao.value			= '';
		document.formulario.MesValidade.value			= '';
		document.formulario.AnoValidade.value			= '';
		document.formulario.CodigoSeguranca.value		= '';
		document.formulario.DiaVencimentoFatura.value	= '';
		document.formulario.IdStatus.value				= '';
		document.formulario.LoginCriacao.value			= '';
		document.formulario.DataCriacao.value			= '';
		document.formulario.LoginAlteracao.value		= '';
		document.formulario.DataAlteracao.value			= '';
		document.formulario.Acao.value					=  "inserir";
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
	function listar_cartao_credito(IdPessoa,Erro,Local){
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
	    
	   	url = "xml/pessoa_cartao_credito.php?IdPessoa="+IdPessoa+"&Cadastro=true";
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
						
						for(var i=0; i<xmlhttp.responseXML.getElementsByTagName("IdCartao").length; i++){	
							nameNode = xmlhttp.responseXML.getElementsByTagName("IdCartao")[i]; 
							nameTextNode = nameNode.childNodes[0];
							var IdCartao = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("IdPessoa")[i]; 
							nameTextNode = nameNode.childNodes[0];
							var IdPessoa = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("IdBandeira")[i]; 
							nameTextNode = nameNode.childNodes[0];
							var IdBandeira = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("NomeTitular")[i]; 
							nameTextNode = nameNode.childNodes[0];
							var NomeTitular = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("NumeroCartao")[i]; 
							nameTextNode = nameNode.childNodes[0];
							var NumeroCartao = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("Validade")[i]; 
							nameTextNode = nameNode.childNodes[0];
							var Validade = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("Status")[i]; 
							nameTextNode = nameNode.childNodes[0];
							var Status = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("CodigoSeguranca")[i]; 
							nameTextNode = nameNode.childNodes[0];
							var CodigoSeguranca = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("DiaVencimentoFatura")[i]; 
							nameTextNode = nameNode.childNodes[0];
							var DiaVencimentoFatura = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("LoginCriacao")[i]; 
							nameTextNode = nameNode.childNodes[0];
							var LoginCriacao = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("DataCriacao")[i]; 
							nameTextNode = nameNode.childNodes[0];
							var DataCriacao = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("LoginAlteracao")[i]; 
							nameTextNode = nameNode.childNodes[0];
							var LoginAlteracao = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("DataAlteracao")[i]; 
							nameTextNode = nameNode.childNodes[0];
							var DataAlteracao = nameTextNode.nodeValue;
											
							tam 	= document.getElementById('tabelaContaDebito').rows.length;
							linha	= document.getElementById('tabelaContaDebito').insertRow(tam-1);
							
							if(tam%2 != 0){
								linha.style.backgroundColor = "#E2E7ED";
							}
							
							linha.accessKey = IdCartao; 
							
							c0	= linha.insertCell(0);	
							c1	= linha.insertCell(1);	
							c2	= linha.insertCell(2);	
							c3	= linha.insertCell(3);
							c4	= linha.insertCell(4);
							c5	= linha.insertCell(5);
							c6	= linha.insertCell(6);
							
							var linkIni = "<a href=\"javaScript: busca_pessoa_cartao_credito("+IdPessoa+", "+IdCartao+", true, '"+Local+"');\">";
							var linkFim = "</a>";
							
							c0.innerHTML = linkIni + IdCartao + linkFim;
							c0.style.cursor  = "pointer";
							c0.style.padding =	"0 0 0 5px";
							
							c1.innerHTML = linkIni + NomeTitular + linkFim;
							c1.style.cursor = "pointer";
							c1.style.marginRight = "4px";
							
							c2.innerHTML = linkIni + NumeroCartao + linkFim;
							c2.style.cursor = "pointer";
							c2.style.marginRight = "4px";
							
							c3.innerHTML = linkIni + Validade + linkFim;
							c3.style.cursor = "pointer";
							c3.style.marginRight = "4px";
							
							c4.innerHTML = linkIni + DiaVencimentoFatura + linkFim;
							c4.style.cursor = "pointer";
							c4.style.marginRight = "4px";
							
							c5.innerHTML = linkIni + Status + linkFim;
							c5.style.cursor = "pointer";
							c5.style.marginRight = "4px";
							
							c6.innerHTML    = "<img src='../../img/estrutura_sistema/ico_del.gif' alt='Excluir?' onClick=\"excluir("+IdPessoa+", "+IdCartao+", 'listar')\">";
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
		
	function mascara_cartao(numero,tipo,event){
		intMin = 0;
		intMax = 9;
		
		if(numero == undefined) 
			return false;
			
		if(numero.value.length >= 19)
			return false;
		else{
			switch(tipo){
				case 1:
					keyCode = (event.keyCode ? event.keyCode : event.which ? event.which : event.charCode);
					var nTecla = event.keyCode;
					
					if((keyCode >= 48 && keyCode <= 57) || keyCode==8){
						var digitos = numero.value.replace(/( )/g,"");
						if((digitos.length > 0 && digitos.length % 4 == 0 && keyCode != 46 && keyCode != 8) && (keyCode >=48 && keyCode <= 57)){
							numero.value = numero.value + " ";
						}
					}else{
						if(event.preventDefault){ //standart browsers
							event.preventDefault();
						} else{ // internet explorer
							event.returnValue = false;
						}
					}
					break;
			}
		}
		contadordigito++;
	}
	
	function caixaAlta(valor){
		valor.value=valor.value.toUpperCase();
	}
	function controla_dia_vencimento(campo,event){
		keyCode = (event.keyCode ? event.keyCode : event.which ? event.which : event.charCode);
		if(campo.value.length == 0 && (keyCode >=51 && keyCode <= 57)){	
			if(event.preventDefault){ //standart browsers
				event.preventDefault();
			} else{ // internet explorer
				event.returnValue = false;
			}
		}
		if(campo.value.length == 1 && keyCode == 57){	
			if(event.preventDefault){ //standart browsers
				event.preventDefault();
			} else{ // internet explorer
				event.returnValue = false;
			}
		}
	}