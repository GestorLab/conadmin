	function excluir(IdArquivoRemessa,IdLocalCobranca){
		if(IdLocalCobranca == '' || IdLocalCobranca == undefined){
			IdLocalCobranca = document.formulario.IdLocalCobranca.value;
		}
		if(IdArquivoRemessa == '' || IdArquivoRemessa == undefined){
			IdArquivoRemessa = document.formulario.IdArquivoRemessa.value;
		}
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
    
   			url = "files/excluir/excluir_arquivo_remessa.php?IdLocalCobranca="+IdLocalCobranca+"&IdArquivoRemessa="+IdArquivoRemessa;
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
								url = 'cadastro_arquivo_remessa.php?Erro='+document.formulario.Erro.value;
								window.location.replace(url);
							}else{
								verificaErro();
							}
						}else{
							var numMsg = parseInt(xmlhttp.responseText);
							mensagens(numMsg);
							if(numMsg == 7){
								var aux = 0, valor=0;
								for(var i=0; i<document.getElementById('tableListar').rows.length; i++){
									if(IdArquivoRemessa+"_"+IdLocalCobranca== document.getElementById('tableListar').rows[i].accessKey){
										document.getElementById('tableListar').deleteRow(i);
										tableMultColor('tableListar');
										aux=1;
										break;
									}
								}	
								if(aux=1){
									for(var i=1; i<(document.getElementById('tableListar').rows.length-1); i++){
										temp	=	document.getElementById('tableListar').rows[i].cells[6].innerHTML.split(">");
										temp1	=	temp[1].split("<");
										valor	+=	parseFloat(temp1[0].replace(',','.'));
									}
									document.getElementById('tableListarValor').innerHTML	=	formata_float(Arredonda(valor,2),2).replace('.',',');	
									document.getElementById("tableListarTotal").innerHTML	=	"Total: "+(document.getElementById('tableListar').rows.length-2);
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
	function validar(){
		if(document.formulario.IdLocalCobranca.value==''){
			mensagens(1);
			document.formulario.IdLocalCobranca.focus();
			return false;
		}
		mensagens(0);
		return true;
	}
	function inicia(){
		document.formulario.IdArquivoRemessa.focus();
	}
	function verificaAcao(){
		if(document.formulario != undefined){
			switch(document.formulario.IdStatus.value){
				case '0': //Cancelado
					document.formulario.bt_inserir.disabled 	= true;
					document.formulario.bt_excluir.disabled 	= false;
					document.formulario.bt_processar.disabled 	= true;
					document.formulario.bt_confirmar.disabled 	= true;
					document.formulario.bt_cancelar.disabled 	= true;
					document.formulario.bt_gerar.disabled 		= true;
					document.formulario.bt_visualizar.disabled 	= true;
					break;
				case '1': //cadastrado
					document.formulario.bt_inserir.disabled 	= true;
					document.formulario.bt_excluir.disabled 	= false;
					document.formulario.bt_processar.disabled 	= false;
					document.formulario.bt_confirmar.disabled 	= true;
					document.formulario.bt_cancelar.disabled 	= true;
					document.formulario.bt_gerar.disabled 		= true;
					document.formulario.bt_visualizar.disabled 	= true;
					break;
				case '2': //processado
					document.formulario.bt_inserir.disabled 	= true;
					document.formulario.bt_excluir.disabled 	= false;
					document.formulario.bt_processar.disabled 	= true;
					document.formulario.bt_confirmar.disabled 	= false;
					document.formulario.bt_cancelar.disabled 	= true;
					document.formulario.bt_gerar.disabled 		= true;
					document.formulario.bt_visualizar.disabled 	= true;
					break;
				case '3': //confirmado
					document.formulario.bt_inserir.disabled 	= true;
					document.formulario.bt_excluir.disabled 	= false;
					document.formulario.bt_processar.disabled 	= true;
					document.formulario.bt_confirmar.disabled 	= true;
					document.formulario.bt_cancelar.disabled 	= false;
					document.formulario.bt_gerar.disabled 		= false;
					document.formulario.bt_visualizar.disabled 	= false;
					break;
				default:			
					document.formulario.bt_inserir.disabled 	= false;
					document.formulario.bt_processar.disabled 	= true;
					document.formulario.bt_excluir.disabled 	= true;
					document.formulario.bt_confirmar.disabled 	= true;
					document.formulario.bt_cancelar.disabled 	= true;
					document.formulario.bt_gerar.disabled 		= true;
					document.formulario.bt_visualizar.disabled 	= true;
					break;
			}
		}	
	}
	function listarContaReceber(IdArquivoRemessa,IdLocalCobranca,Erro){
		if(IdArquivoRemessa == undefined || IdArquivoRemessa==''){
			IdArquivoRemessa = 0;
		}
		if(IdLocalCobranca == undefined || IdLocalCobranca==''){
			IdLocalCobranca = 0;
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
	    
	   	url = "xml/conta_receber.php?IdLocalCobranca="+IdLocalCobranca+"&IdArquivoRemessa="+IdArquivoRemessa;
		xmlhttp.open("GET", url,true);

		xmlhttp.onreadystatechange = function(){ 
	
			// Carregando...
			carregando(true);
	
			if(xmlhttp.readyState == 4){ 
				if(xmlhttp.status == 200){
					if(xmlhttp.responseText == 'false'){
						document.getElementById('cp_titulos').style.display		=	"block";
					
						document.getElementById('cpValorTotal').innerHTML		=	"0,00";		
						document.getElementById('tabelaTotal').innerHTML		=	"Total: 0";	
						
						// Fim de Carregando
						carregando(false);
					}else{
						document.getElementById('cp_titulos').style.display		=	"block";
						
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
							
							c5.innerHTML = linkIni + formata_float(Arredonda(Valor,2),2).replace('.',',')+ linkFim + "&nbsp;&nbsp;";
							c5.style.textAlign = "right";
							
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
	function cadastrar(Acao){
		if(validar()==true){
			if(Acao != ''){
				document.formulario.Acao.value	=	Acao;
			}
			document.formulario.submit();
		}
	}
	function buscaVisualizar(){
		if(document.formulario.Visualizar.value == ''){
			if(document.formulario.IdArquivoRemessa.value != ''){
				listarContaReceber(document.formulario.IdArquivoRemessa.value,document.formulario.IdLocalCobranca.value,false);
				document.formulario.Visualizar.value = true;
				document.formulario.bt_visualizar.value = 'Ocultar';
			}
		}else{
			document.formulario.Visualizar.value = '';
			document.formulario.bt_visualizar.value = 'Visualizar';
			
			while(document.getElementById('tabelaContasReceber').rows.length > 2){
				document.getElementById('tabelaContasReceber').deleteRow(1);
			}
			
			document.getElementById('cp_titulos').style.display	=	'none';						
			document.getElementById('cpValorTotal').innerHTML	=	"0,00";	
			document.getElementById('tabelaTotal').innerHTML	=	"Total: 0";
		}
	}
