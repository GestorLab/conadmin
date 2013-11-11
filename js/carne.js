	function cancelar(IdCarne,IdStatus){
		if(IdCarne=='' || IdStatus == 0){
			return false;
		}
		url = 'cadastro_carne.php?IdCarne='+IdCarne;
		window.location.replace(url);
	} 
	function verificaAcao(){
		if(document.formulario != undefined){
			if(document.formulario.Acao.value=='inserir'){			
				document.formulario.bt_cancelar.disabled 	= true;
			}
			if(document.formulario.Acao.value=='cancelar'){			
				document.formulario.bt_cancelar.disabled 	= false;
			}
		}	
	}
	function inicia(){
		document.formulario.IdCarne.focus();
	}
	function busca_carne(IdCarne,Erro,Local){
		if(IdCarne == '' || IdCarne == undefined){
			IdCarne = 0;
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
			}else if (window.ActiveXObject){ // IE
				try{
					xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
				}catch(e){
					try{
						xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
					} catch (e) {}
				}
			}

   			url = "xml/carne.php?IdCarne="+IdCarne;
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
						document.formulario.IdCarne.value				=	"";
						document.getElementById('cp_juridica').style.display			= 'block';
						document.getElementById('cp_fisica').style.display				= 'none';
						
						document.formulario.IdPessoa.value 					= '';
						document.formulario.Nome.value 						= '';
						document.formulario.RazaoSocial.value 				= '';
						document.formulario.Cidade.value 					= '';
						document.formulario.CPF_CNPJ.value 					= '';
						document.formulario.Email.value 					= '';
						document.formulario.Telefone1.value					= '';
						document.formulario.SiglaEstado.value				= '';
						document.formulario.ObsCancelamento.value	 		= '';
						document.formulario.Voltar.value					= '0';
						document.formulario.VoltarDataBase.value			= '';
						document.formulario.VoltarDataBase.disabled			= false;
							
						document.getElementById('cp_CPF_CNPJ_Titulo').innerHTML 		=	"CNPJ";
						document.formulario.Acao.value 									= 	'inserir';
						document.getElementById('cpVoltarDataBase').style.display		=	'none';			
						document.getElementById('tabelaTotalValor').innerHTML			=	"0,00";	
						document.getElementById('tabelaTotalReceb').innerHTML			=	"0,00";	
						document.getElementById('tabelaTotal').innerHTML				=	"Total: 0";	
						
						addParmUrl("marCarne","IdCarne",'');

						status_inicial();
						
						while(document.getElementById('tabelaContaReceber').rows.length > 2){
							document.getElementById('tabelaContaReceber').deleteRow(1);
						}

						verificaAcao();
						
						// Fim de Carregando
						carregando(false);
					}else{
						nameNode = xmlhttp.responseXML.getElementsByTagName("IdCarne")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var IdCarne = nameTextNode.nodeValue;
					
						addParmUrl("marCarne","IdCarne",IdCarne);

						nameNode = xmlhttp.responseXML.getElementsByTagName("IdPessoa")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var IdPessoa = nameTextNode.nodeValue;
						
					
						busca_pessoa(IdPessoa,false,document.formulario.Local.value);
						
						while(document.getElementById('tabelaContaReceber').rows.length > 2){
							document.getElementById('tabelaContaReceber').deleteRow(1);
						}
						
						document.getElementById('cpVoltarDataBase').style.display	=	'none';	

						listar_conta_receber(IdCarne);

						document.formulario.IdCarne.value				=	IdCarne;
						document.formulario.IdPessoa.value				=	IdPessoa;
						document.formulario.Acao.value 					= 	'cancelar';

						verificaAcao();
					}
					if(window.janela != undefined){
						window.janela.close();
					}
				}// fim do else
				// Fim de Carregando
				carregando(false);
			}//fim do if status
			return true;
		}
		xmlhttp.send(null);
	}

	function listar_conta_receber(IdCarne){
		if(IdCarne == undefined || IdCarne==''){
			IdCarne = 0;
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
	    
	   	url = "xml/conta_receber.php?IdCarne="+IdCarne;
		xmlhttp.open("GET", url,true);

		xmlhttp.onreadystatechange = function(){ 
	
			if(xmlhttp.readyState == 4){ 
				if(xmlhttp.status == 200){
					if(xmlhttp.responseText == 'false'){
						while(document.getElementById('tabelaContaReceber').rows.length > 2){
							document.getElementById('tabelaContaReceber').deleteRow(1);
						}
						
						document.getElementById('tabelaTotalValor').innerHTML	=	"0,00";	
						document.getElementById('tabelaTotalReceb').innerHTML	=	"0,00";	
						document.getElementById('tabelaTotal').innerHTML		=	"Total: 0";	
						
					}else{
						while(document.getElementById('tabelaContaReceber').rows.length > 2){
							document.getElementById('tabelaContaReceber').deleteRow(1);
						}
						
						
						var tam, linha, c0, c1, c2, c3, c4;
						var IdContaReceber,NumeroDocumento,NumeroNF,AbreviacaoNomeLocalCobranca,DataLancamento,Valor,DataVencimento,ValorRecebido,DataRecebimento,DescricaoLocalRecebimento,TotalValor=0,TotalReceb=0;
						for(var i=0; i<xmlhttp.responseXML.getElementsByTagName("IdContaReceber").length; i++){	
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("IdContaReceber")[i]; 
							nameTextNode = nameNode.childNodes[0];
							IdContaReceber = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("NumeroDocumento")[i]; 
							nameTextNode = nameNode.childNodes[0];
							NumeroDocumento = nameTextNode.nodeValue;	
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("NumeroNF")[i]; 
							nameTextNode = nameNode.childNodes[0];
							NumeroNF = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("AbreviacaoNomeLocalCobranca")[i]; 
							nameTextNode = nameNode.childNodes[0];
							AbreviacaoNomeLocalCobranca = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("DataLancamento")[i]; 
							nameTextNode = nameNode.childNodes[0];
							DataLancamento = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("ValorReceber")[i]; 
							nameTextNode = nameNode.childNodes[0];
							Valor = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("DataVencimento")[i]; 
							nameTextNode = nameNode.childNodes[0];
							DataVencimento = nameTextNode.nodeValue;

							nameNode = xmlhttp.responseXML.getElementsByTagName("ValorRecebido")[i]; 
							nameTextNode = nameNode.childNodes[0];
							ValorRecebido = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("DataRecebimento")[i]; 
							nameTextNode = nameNode.childNodes[0];
							DataRecebimento = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoLocalRecebimento")[i]; 
							nameTextNode = nameNode.childNodes[0];
							DescricaoLocalRecebimento = nameTextNode.nodeValue;

							nameNode = xmlhttp.responseXML.getElementsByTagName("Tipo")[i]; 
							nameTextNode = nameNode.childNodes[0];
							Tipo = nameTextNode.nodeValue;

							nameNode = xmlhttp.responseXML.getElementsByTagName("Voltar")[i]; 
							nameTextNode = nameNode.childNodes[0];
							Voltar = nameTextNode.nodeValue;
							
							tam 	= document.getElementById('tabelaContaReceber').rows.length;
							linha	= document.getElementById('tabelaContaReceber').insertRow(tam-1);
							
							if(tam%2 != 0){
								linha.style.backgroundColor = "#E2E7ED";
							}
							
							linha.accessKey = IdContaReceber; 
							
							if(ValorRecebido==''){	ValorRecebido = 0;	}

							TotalValor	=	parseFloat(TotalValor) + parseFloat(Valor);
							TotalReceb	=	parseFloat(TotalReceb) + parseFloat(ValorRecebido);
							
							c0	= linha.insertCell(0);	
							c1	= linha.insertCell(1);	
							c2	= linha.insertCell(2);	
							c3	= linha.insertCell(3);
							c4	= linha.insertCell(4);
							c5	= linha.insertCell(5);
							c6	= linha.insertCell(6);
							c7	= linha.insertCell(7);
							c8	= linha.insertCell(8);
							c9	= linha.insertCell(9);

							linkIni = "<a href='cadastro_conta_receber.php?IdContaReceber="+IdContaReceber+"'>"
							linkFim	=	"</a>";
							
							c0.innerHTML = linkIni + IdContaReceber + linkFim;
							c0.style.padding  =	"0 0 0 5px";
							c0.style.cursor = "pointer";
							
							c1.innerHTML = linkIni + NumeroDocumento + linkFim;
							c1.style.cursor = "pointer";

							c2.innerHTML = linkIni + NumeroNF + linkFim;
							c2.style.cursor = "pointer";

							c3.innerHTML = linkIni + AbreviacaoNomeLocalCobranca + linkFim;
							c3.style.cursor = "pointer";

							c4.innerHTML = linkIni + dateFormat(DataLancamento) + linkFim;
							c4.style.cursor = "pointer";
							
							c5.innerHTML =  linkIni + formata_float(Arredonda(Valor,2),2).replace('.',',') + linkFim;
							c5.style.textAlign = "right";
							c5.style.cursor = "pointer";
							c5.style.padding  =	"0 8px 0 0";

							c6.innerHTML = linkIni + dateFormat(DataVencimento) + linkFim;
							c6.style.cursor = "pointer";

							c7.innerHTML =  linkIni + formata_float(Arredonda(ValorRecebido,2),2).replace('.',',') + linkFim;
							c7.style.textAlign = "right";
							c7.style.cursor = "pointer";
							c7.style.padding  =	"0 8px 0 0";

							c8.innerHTML = linkIni + dateFormat(DataRecebimento) + linkFim;
							c8.style.cursor = "pointer";

							c9.innerHTML = linkIni + DescricaoLocalRecebimento + linkFim;
							c9.style.cursor = "pointer";


							if(i == (parseInt(xmlhttp.responseXML.getElementsByTagName("IdContaReceber").length)-1)){
								if(Tipo == 'EV'){
									document.getElementById('cpVoltarDataBase').style.display	=	'none';	
								}else{
									document.getElementById('cpVoltarDataBase').style.display	=	'block';	
								}
							
								if(Voltar == 'false'){
									document.formulario.VoltarDataBase.value	=	2;
									document.formulario.VoltarDataBase.disabled	=	true;
								}else{
									document.formulario.VoltarDataBase.disabled	=	false;
								}

							}
						}
						document.getElementById('tabelaTotalValor').innerHTML	=	formata_float(Arredonda(TotalValor,2),2).replace('.',',');	
						document.getElementById('tabelaTotalReceb').innerHTML	=	formata_float(Arredonda(TotalReceb,2),2).replace('.',',');	
						document.getElementById('tabelaTotal').innerHTML		=	"Total: "+i;	
					}
					if(window.janela != undefined){
						window.janela.close();
					}
				}
			} 
			return true;
		}
		xmlhttp.send(null);
	}
	function validar(){
		if(document.formulario.ObsCancelamento.value == ''){
			mensagens(1);
			document.formulario.ObsCancelamento.focus();
			return false;
		}
		if(document.formulario.Voltar.value=='true'){
			if(document.formulario.VoltarDataBase.value=='0'){
				mensagens(1);
				document.formulario.VoltarDataBase.focus();
				return false;
			}
		}
	}

