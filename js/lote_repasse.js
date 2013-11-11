	function verifica_mes(id,campo){
		if(campo.value == ''){
			document.getElementById(id).style.backgroundColor='#FFF';
			document.getElementById(id).style.color='#000';
			mensagens(0);
			return false;
		}
		if(isMes(campo.value) == false){		
			document.getElementById(id).style.backgroundColor = '#C10000';
			document.getElementById(id).style.color='#FFF';
			mensagens(45);
			return false;
		}else{
			document.getElementById(id).style.backgroundColor='#FFF';
			document.getElementById(id).style.color='#000';
			mensagens(0);
			return true;
		}	
	}
	function excluir(IdLoteRepasse,IdStatus){
		if(IdStatus == 3){
			return false;
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
    
   			url = "files/excluir/excluir_lote_repasse.php?IdLoteRepasse="+IdLoteRepasse;
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
								url = 'cadastro_lote_repasse.php?Erro='+document.formulario.Erro.value;
								window.location.replace(url);
							}else{
								verificaErro();
							}
						}else{
							var numMsg = parseInt(xmlhttp.responseText);
							mensagens(numMsg);
							if(numMsg == 7){
								var aux = 0, valor=0, repasse=0;
								for(var i=0; i<document.getElementById('tableListar').rows.length; i++){
									if(IdLoteRepasse == document.getElementById('tableListar').rows[i].accessKey){
										document.getElementById('tableListar').deleteRow(i);
										tableMultColor('tableListar',document.filtro.corRegRand.value);
										aux=1;
										break;
									}
								}
								if(aux=1){
									for(var i=1; i<(document.getElementById('tableListar').rows.length-1); i++){
										temp	=	document.getElementById('tableListar').rows[i].cells[2].innerHTML.split(">");
										temp1	=	temp[1].split("<");
										valor	+=	parseFloat(temp1[0].replace(',','.'));
											
										temp	=	document.getElementById('tableListar').rows[i].cells[4].innerHTML.split(">");
										temp1	=	temp[1].split("<");
										repasse	+=	parseFloat(temp1[0].replace(',','.'));
									}
									document.getElementById('tableListarValor').innerHTML	=	formata_float(Arredonda(valor,2),2).replace('.',',');	
									document.getElementById('tableListarRepasse').innerHTML	=	formata_float(Arredonda(repasse,2),2).replace('.',',');
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
		if(document.formulario.IdPessoa.value==''){
			mensagens(1);
			document.formulario.IdPessoa.focus();
			return false;
		}
		if(document.formulario.Filtro_MesReferencia.value==''){
			mensagens(1);
			document.formulario.Filtro_MesReferencia.focus();
			return false;
		}else{
			if(isMes(document.formulario.Filtro_MesReferencia.value) == false){
				document.formulario.Filtro_MesReferencia.focus();
				mensagens(45);			
				return false;
			}
		}
		if(document.formulario.ObsRepasse.value==''){
			mensagens(1);
			document.formulario.ObsRepasse.focus();
			return false;
		}
		return true;
	}
	function inicia(){
		document.formulario.IdLoteRepasse.focus();
	}
	function verificaAcao(){
		if(document.formulario != undefined){
			switch(document.formulario.Acao.value){
				case 'inserir':
					document.formulario.bt_inserir.disabled 	= false;
					document.formulario.bt_alterar.disabled 	= true;
					document.formulario.bt_excluir.disabled 	= true;

					document.formulario.bt_processar.disabled 	= true;
					document.formulario.bt_confirmar.disabled 	= true;
					document.formulario.bt_cancelar.disabled 	= true;
				
					document.formulario.bt_imprimir.disabled 	= true;
					break;
				case 'alterar':
					document.formulario.bt_inserir.disabled 	= true;
					document.formulario.bt_alterar.disabled 	= false;
					document.formulario.bt_excluir.disabled 	= false;

					document.formulario.bt_processar.disabled 	= false;
					document.formulario.bt_confirmar.disabled 	= true;
					document.formulario.bt_cancelar.disabled 	= true;
				
					document.formulario.bt_imprimir.disabled 	= true;
					break;
				case 'processar':
					document.formulario.bt_inserir.disabled 	= true;
					document.formulario.bt_alterar.disabled 	= true;
					document.formulario.bt_excluir.disabled 	= false;

					document.formulario.bt_processar.disabled 	= true;
					document.formulario.bt_confirmar.disabled 	= false;
					document.formulario.bt_cancelar.disabled 	= false;
				
					document.formulario.bt_imprimir.disabled 	= true;
					break;
				case 'confirmar':
					document.formulario.bt_inserir.disabled 	= true;
					document.formulario.bt_alterar.disabled 	= true;
					document.formulario.bt_excluir.disabled 	= true;

					document.formulario.bt_processar.disabled 	= true;
					document.formulario.bt_confirmar.disabled 	= true;
					
					if(document.formulario.UltimoLote.value == '1'){
						document.formulario.bt_cancelar.disabled 	= false;
					}else{ 
						document.formulario.bt_cancelar.disabled 	= true;
					}
				
					document.formulario.bt_imprimir.disabled 	= false;
					break;
			}
		}	
	}
	function cancelar_processo(){
	//	if(confirm('ATENÇÃO\n\nVocê está prestes a cancelar todos os lancamentos deste lote de repasse.\nDeseja realmente confirmar este cancelamento?')){
			document.formulario.Acao.value = 'cancelar';
			document.formulario.submit();
	//	}	
	}
	function confirmar_processo(){
		//if(confirm('ATENÇÃO\n\nVocê está prestes a confirmar um lote de repasse.\nDeseja realmente confirmar este processo?\n\n\nObs: Processo Irreversível.')){
			document.formulario.Acao.value = 'confirmar';
			document.formulario.submit();
		//}		
	}
	function cadastrar(Acao){
		if(validar()==true){
			if(Acao != ''){
				document.formulario.Acao.value	=	Acao;
			}
			document.formulario.submit();
		}
	}
	function imprimirEspelho(IdLoteRepasse){
		if(IdLoteRepasse != ''){
			window.location.replace("imprimir_lote_repasse.php?IdLoteRepasse="+IdLoteRepasse);
		}
	}
	function listar_lancamento_financeiro(IdLoteRepasse){
		if(IdLoteRepasse == undefined || IdLoteRepasse==''){
			IdLoteRepasse = 0;
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
	    
	   	url = "xml/lote_repasse_item.php?IdLoteRepasse="+IdLoteRepasse;
		xmlhttp.open("GET", url,true);

		xmlhttp.onreadystatechange = function(){ 
			
			// Carregando...
			carregando(true);
			
			if(xmlhttp.readyState == 4){ 
				if(xmlhttp.status == 200){
					if(xmlhttp.responseText == 'false'){
						document.getElementById('tabelaTotalItem').innerHTML	=	"0,00";	
						document.getElementById('tabelaTotalRepasse').innerHTML	=	"0,00";	
						document.getElementById('tabelaTotal').innerHTML		=	"Total: 0";	
						
					}else{
						var tam, linha, c0, c1, c2, c3, c4, c5, c6, c7, c8;
						var IdLancamentoFinanceiro,Tipo,Valor,Codigo,Descricao,Referencia,TotalValor=0,TotalRepasse=0,IdPessoa;
						var IdStatus = document.formulario.IdStatus.value;
						for(var i=0; i<xmlhttp.responseXML.getElementsByTagName("IdLancamentoFinanceiro").length; i++){	
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("IdLancamentoFinanceiro")[i]; 
							nameTextNode = nameNode.childNodes[0];
							IdLancamentoFinanceiro = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("Nome")[i]; 
							nameTextNode = nameNode.childNodes[0];
							Nome = nameTextNode.nodeValue;	
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("IdPessoa")[i]; 
							nameTextNode = nameNode.childNodes[0];
							IdPessoa = nameTextNode.nodeValue;	
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("DataVencimento")[i]; 
							nameTextNode = nameNode.childNodes[0];
							DataVencimento = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("DataRecebimento")[i]; 
							nameTextNode = nameNode.childNodes[0];
							DataRecebimento = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("Referencia")[i]; 
							nameTextNode = nameNode.childNodes[0];
							Referencia = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("Valor")[i]; 
							nameTextNode = nameNode.childNodes[0];
							Valor = nameTextNode.nodeValue;

							nameNode = xmlhttp.responseXML.getElementsByTagName("ValorItemRepasse")[i]; 
							nameTextNode = nameNode.childNodes[0];
							ValorItemRepasse = nameTextNode.nodeValue;

							tam 	= document.getElementById('tabelaLancFinanceiro').rows.length;
							linha	= document.getElementById('tabelaLancFinanceiro').insertRow(tam-1);
							
							if(tam%2 != 0){
								linha.style.backgroundColor = "#E2E7ED";
							}
							
							linha.accessKey = IdLancamentoFinanceiro; 
							
							TotalValor		=	parseFloat(TotalValor) + parseFloat(Valor);
							TotalRepasse	=	parseFloat(TotalRepasse) + parseFloat(ValorItemRepasse);
							
							c0	= linha.insertCell(0);	
							c1	= linha.insertCell(1);	
							c2	= linha.insertCell(2);	
							c3	= linha.insertCell(3);
							c4	= linha.insertCell(4);
							c5	= linha.insertCell(5);
							c6	= linha.insertCell(6);
							c7	= linha.insertCell(7);
							
							c0.innerHTML = IdLancamentoFinanceiro;
							c0.style.padding  =	"0 0 0 5px";
							
							c1.innerHTML = "<a href='cadastro_pessoa.php?IdPessoa="+IdPessoa+"'>" + Nome + "</a>";
							
							c2.innerHTML = Referencia;

							c3.innerHTML = dateFormat(DataVencimento);

							c4.innerHTML = dateFormat(DataRecebimento);
							
							c5.innerHTML =  formata_float(Arredonda(Valor,2),2).replace('.',',') ;
							c5.style.textAlign = "right";
							c5.style.padding  =	"0 8px 0 0";

							c6.innerHTML =  formata_float(Arredonda(ValorItemRepasse,2),2).replace('.',',') ;
							c6.style.textAlign = "right";
							c6.style.padding  =	"0 8px 0 0";
							
							if(IdStatus < 3){
								c7.innerHTML 	= "<img src='../../img/estrutura_sistema/ico_del.gif' alt='Excluir?' onClick=\"excluir_lancamento("+IdLoteRepasse+","+IdLancamentoFinanceiro+")\">";
								c7.style.cursor = "pointer";
							}else{
								c7.innerHTML 	= "<img src='../../img/estrutura_sistema/ico_del_c.gif' alt='Excluir?'>";
							}
						}
						document.getElementById('tabelaTotalItem').innerHTML	=	formata_float(Arredonda(TotalValor,2),2).replace('.',',');	
						document.getElementById('tabelaTotalRepasse').innerHTML	=	formata_float(Arredonda(TotalRepasse,2),2).replace('.',',');	
						document.getElementById('tabelaTotal').innerHTML		=	"Total: "+i;	
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
	function excluir_lancamento(IdLoteRepasse,IdLancamentoFinanceiro){
		if(IdLoteRepasse== '' || IdLoteRepasse == undefined){
			IdLoteRepasse = document.formulario.IdLoteRepasse.value;
		}
		if(IdLancamentoFinanceiro== '' || IdLancamentoFinanceiro == undefined){
			return false;
		}
		if(document.getElementById('tabelaLancFinanceiro').rows.length < 4){
			alert("ATENÇÃO\n\nÉ obrigatório ter no mínimo 1 (um) lançamento financeiro.");
			return false;
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
    
   			url = "files/excluir/excluir_lote_repasse_item.php?IdLoteRepasse="+IdLoteRepasse+"&IdLancamentoFinanceiro="+IdLancamentoFinanceiro;
			xmlhttp.open("GET", url,true);
		
			xmlhttp.onreadystatechange = function(){ 

				// Carregando...
				carregando(true);

				if(xmlhttp.readyState == 4){ 
					if(xmlhttp.status == 200){
						var numMsg = parseInt(xmlhttp.responseText);
						if(numMsg == 7){
							var valor=0, repasse=0, aux=0, cont=0;
							for(var i=0; i<document.getElementById('tabelaLancFinanceiro').rows.length; i++){
								if(IdLancamentoFinanceiro == document.getElementById('tabelaLancFinanceiro').rows[i].accessKey){
									document.getElementById('tabelaLancFinanceiro').deleteRow(i);
									tableMultColor('tabelaLancFinanceiro',document.filtro.corRegRand.value);
									aux = 1
									break;
								}
							}
							
							if(aux == 1){
								for(var i=1; i<(document.getElementById('tabelaLancFinanceiro').rows.length-1); i++){
									valor	+=	document.getElementById('tabelaLancFinanceiro').rows[i].cells[6].innerHTML;
									repasse	+=	document.getElementById('tabelaLancFinanceiro').rows[i].cells[7].innerHTML;
									
									cont++;
								}
								document.getElementById('tabelaTotalItem').innerHTML	=	formata_float(Arredonda(valor,2),2).replace('.',',');	
								document.getElementById('tabelaTotalRepasse').innerHTML	=	formata_float(Arredonda(repasse,2),2).replace('.',',');	
								document.getElementById('tabelaTotal').innerHTML		=	"Total: "+cont;
							}
							
						}else{
							mensagens(6);
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
	function janela_servico(IdStatus){
		if(IdStatus!='' && IdStatus>1){	
			return false;
		}else{
			janelas('busca_servico.php',530,283,250,100,'');
		}
	}
	function remover_filtro_servico(IdServico){
		for(var i=0; i<document.getElementById('tabelaServico').rows.length; i++){
			if(IdServico == document.getElementById('tabelaServico').rows[i].accessKey){
				document.getElementById('tabelaServico').deleteRow(i);
				tableMultColor('tabelaServico');
				break;
			}
		}	
		var tempFiltro	=	document.formulario.Filtro_IdServico.value.split(',');
		var novoFiltro  = '';
		
		var ii = 0;
		while(tempFiltro[ii] != undefined){
			if(tempFiltro[ii] != IdServico){
				if(novoFiltro == ''){
					novoFiltro = tempFiltro[ii];
				}else{
					novoFiltro = novoFiltro + "," + tempFiltro[ii];
				}
			}
			ii=ii+1;
		}
		
		document.formulario.Filtro_IdServico.value = novoFiltro;
		document.getElementById('totaltabelaServico').innerHTML	=	'Total: '+(ii-1);
	}	
	function imprimir_lancamento_financeiro(IdLoteRepasse){
		if(IdLoteRepasse == undefined || IdLoteRepasse==''){
			IdLoteRepasse = 0;
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
	    
	   	url = "xml/lote_repasse_item.php?IdLoteRepasse="+IdLoteRepasse;
		xmlhttp.open("GET", url,true);

		xmlhttp.onreadystatechange = function(){ 
		
			// Carregando...
			carregando(true);
	
			if(xmlhttp.readyState == 4){ 
				if(xmlhttp.status == 200){
					if(xmlhttp.responseText == 'false'){
						document.getElementById('tabelaTotalItem').innerHTML	=	"0,00";	
						document.getElementById('tabelaTotalRepasse').innerHTML	=	"0,00";	
						document.getElementById('tabelaTotal').innerHTML		=	"Total: 0";	
						
					}else{
						var tam, linha, c0, c1, c2, c3, c4, c5, c6, c7, c8;
						var IdLancamentoFinanceiro,Tipo,Valor,Codigo,Descricao,Referencia,TotalValor=0,TotalRepasse=0,IdPessoa;
						for(var i=0; i<xmlhttp.responseXML.getElementsByTagName("IdLancamentoFinanceiro").length; i++){	
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("IdLancamentoFinanceiro")[i]; 
							nameTextNode = nameNode.childNodes[0];
							IdLancamentoFinanceiro = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("Nome")[i]; 
							nameTextNode = nameNode.childNodes[0];
							Nome = nameTextNode.nodeValue;	
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("Descricao")[i]; 
							nameTextNode = nameNode.childNodes[0];
							Descricao = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("DataVencimento")[i]; 
							nameTextNode = nameNode.childNodes[0];
							DataVencimento = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("DataRecebimento")[i]; 
							nameTextNode = nameNode.childNodes[0];
							DataRecebimento = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("Referencia")[i]; 
							nameTextNode = nameNode.childNodes[0];
							Referencia = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("Valor")[i]; 
							nameTextNode = nameNode.childNodes[0];
							Valor = nameTextNode.nodeValue;

							nameNode = xmlhttp.responseXML.getElementsByTagName("ValorItemRepasse")[i]; 
							nameTextNode = nameNode.childNodes[0];
							ValorItemRepasse = nameTextNode.nodeValue;

							tam 	= document.getElementById('tabelaLancFinanceiro').rows.length;
							linha	= document.getElementById('tabelaLancFinanceiro').insertRow(tam-1);
							
							if(tam%2 != 0){
								linha.style.backgroundColor = "#E2E7ED";
							}
							
							linha.accessKey = IdLancamentoFinanceiro; 
							
							TotalValor		=	parseFloat(TotalValor) + parseFloat(Valor);
							TotalRepasse	=	parseFloat(TotalRepasse) + parseFloat(ValorItemRepasse);
							
							c0	= linha.insertCell(0);	
							c1	= linha.insertCell(1);	
							c2	= linha.insertCell(2);	
							c3	= linha.insertCell(3);
							c4	= linha.insertCell(4);
							c5	= linha.insertCell(5);
							c6	= linha.insertCell(6);
							c7	= linha.insertCell(7);
							
							Nome	=	Nome.substr(0,15);
							
							c0.innerHTML = IdLancamentoFinanceiro;
							c0.style.padding  =	"0 0 0 5px";
							
							c1.innerHTML = Nome;
							
							c2.innerHTML = Descricao;
							
							c3.innerHTML = Referencia;
							c3.style.textAlign = "center";

							c4.innerHTML = dateFormat(DataVencimento);

							c5.innerHTML = dateFormat(DataRecebimento);
							
							c6.innerHTML =  formata_float(Arredonda(Valor,2),2).replace('.',',');
							c6.style.textAlign = "right";
							c6.style.padding  =	"0 8px 0 0";

							c7.innerHTML =  formata_float(Arredonda(ValorItemRepasse,2),2).replace('.',',');
							c7.style.textAlign = "right";
							c7.style.padding  =	"0 8px 0 0";
						}
						document.getElementById('tabelaTotalItem').innerHTML	=	formata_float(Arredonda(TotalValor,2),2).replace('.',',');	
						document.getElementById('tabelaTotalRepasse').innerHTML	=	formata_float(Arredonda(TotalRepasse,2),2).replace('.',',');	
						document.getElementById('tabelaTotal').innerHTML		=	"Total: "+i;	
					}
					if(window.janela != undefined){
						window.janela.close();
					}
				}
			} 
			
			// Fim de Carregando
			carregando(false);
			
			return true;
		}
		xmlhttp.send(null);
	}
	function remover_filtro_pessoa(IdPessoa){
		for(var i=0; i<document.getElementById('tabelaPessoa').rows.length; i++){
			if(IdPessoa == document.getElementById('tabelaPessoa').rows[i].accessKey){
				document.getElementById('tabelaPessoa').deleteRow(i);
				tableMultColor('tabelaPessoa');
				break;
			}
		}	
		var tempFiltro	=	document.formulario.Filtro_IdPessoa.value.split(',');
		var novoFiltro  = '';
		
		var ii = 0;
		while(tempFiltro[ii] != undefined){
			if(tempFiltro[ii] != IdPessoa){
				if(novoFiltro == ''){
					novoFiltro = tempFiltro[ii];
				}else{
					novoFiltro = novoFiltro + "," + tempFiltro[ii];
				}
			}
			ii=ii+1;
		}
		
		document.formulario.Filtro_IdPessoa.value = novoFiltro;
		document.getElementById('totaltabelaPessoa').innerHTML	=	'Total: '+(ii-1);
	}	
	

