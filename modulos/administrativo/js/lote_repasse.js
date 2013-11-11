	function verifica_mes(id,campo){
		if(campo.value == ''){
			document.getElementById(id).style.backgroundColor='#FFF';
			document.getElementById(id).style.color='#CC0000';
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
			document.getElementById(id).style.color='#CC0000';
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
										temp	=	document.getElementById('tableListar').rows[i].cells[3].innerHTML.split(">");
										temp1	=	temp[1].split("<");
										valor	+=	parseFloat(temp1[0].replace(',','.'));
											
										temp	=	document.getElementById('tableListar').rows[i].cells[5].innerHTML.split(">");
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
						document.getElementById('tabelaTotalItem').innerHTML			=	"0,00";	
						document.getElementById('tabelaTotalDescontoItem').innerHTML	=	"0,00";
						document.getElementById('tabelaTotalRepasse').innerHTML			=	"0,00";	
						document.getElementById('tabelaTotal').innerHTML				=	"Total: 0";	
						
					}else{
						var tam, linha, c0, c1, c2, c3, c4, c5, c6, c7, c8, c9;
						var IdLancamentoFinanceiro,Tipo,Valor,Codigo,Descricao,Referencia,TotalDescontoItem=0,TotalValor=0,TotalValorRepasseTerceiro=0,TotalRepasse=0,IdPessoa;
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
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("ValorRepasseTerceiro")[i]; 
							nameTextNode = nameNode.childNodes[0];
							ValorRepasseTerceiro = nameTextNode.nodeValue;

							nameNode = xmlhttp.responseXML.getElementsByTagName("ValorItemRepasse")[i]; 
							nameTextNode = nameNode.childNodes[0];
							ValorItemRepasse = nameTextNode.nodeValue;

							nameNode = xmlhttp.responseXML.getElementsByTagName("ValorDescontoItemRepasse")[i]; 
							nameTextNode = nameNode.childNodes[0];
							ValorDescontoItemRepasse = nameTextNode.nodeValue;

							tam 	= document.getElementById('tabelaLancFinanceiro').rows.length;
							linha	= document.getElementById('tabelaLancFinanceiro').insertRow(tam-1);
							
							if(tam%2 != 0){
								linha.style.backgroundColor = "#E2E7ED";
							}
							
							linha.accessKey = IdLancamentoFinanceiro; 
							
							TotalValor					=	parseFloat(TotalValor) + parseFloat(Valor);
							TotalValorRepasseTerceiro	=	parseFloat(TotalValorRepasseTerceiro) + parseFloat(ValorRepasseTerceiro);
							TotalRepasse				=	parseFloat(TotalRepasse) + parseFloat(ValorItemRepasse);
							TotalDescontoItem			=	parseFloat(TotalDescontoItem) + parseFloat(ValorDescontoItemRepasse);
							
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
							
							c0.innerHTML = "<a href='cadastro_lancamento_financeiro.php?IdLancamentoFinanceiro="+IdLancamentoFinanceiro+"&IdPessoa="+IdPessoa+"'>"+IdLancamentoFinanceiro+"</a>";
							c0.style.padding  =	"0 0 0 5px";
							
							c1.innerHTML = "<a href='cadastro_pessoa.php?IdPessoa="+IdPessoa+"'>" + Nome + "</a>";
							
							c2.innerHTML = Referencia;

							c3.innerHTML = dateFormat(DataVencimento);

							c4.innerHTML = dateFormat(DataRecebimento);
							
							c5.innerHTML =  formata_float(Arredonda(Valor,2),2).replace('.',',') ;
							c5.style.textAlign = "right";
							c5.style.padding  =	"0 8px 0 0";
							
							c6.innerHTML =  formata_float(Arredonda(ValorRepasseTerceiro,2),2).replace('.',',') ;
							c6.style.textAlign = "right";
							c6.style.padding  =	"0 8px 0 0";

							c7.innerHTML =  formata_float(Arredonda(ValorDescontoItemRepasse,2),2).replace('.',',') ;
							c7.style.textAlign = "right";
							c7.style.padding  =	"0 8px 0 0";

							c8.innerHTML =  formata_float(Arredonda(ValorItemRepasse,2),2).replace('.',',') ;
							c8.style.textAlign = "right";
							c8.style.padding  =	"0 8px 0 0";
							
							if(IdStatus < 3){
								c9.innerHTML 	= "<img src='../../img/estrutura_sistema/ico_del.gif' alt='Excluir?' onClick=\"excluir_lancamento("+IdLoteRepasse+","+IdLancamentoFinanceiro+")\">";
								c9.style.cursor = "pointer";
							}else{
								c9.innerHTML 	= "<img src='../../img/estrutura_sistema/ico_del_c.gif' alt='Excluir?'>";
							}
						}
						document.getElementById('tabelaTotalItem').innerHTML					=	formata_float(Arredonda(TotalValor,2),2).replace('.',',');	
						document.getElementById('tabelaTotalValorRepasseTerceiro').innerHTML	=	formata_float(Arredonda(TotalValorRepasseTerceiro,2),2).replace('.',',');	
						document.getElementById('tabelaTotalDescontoItem').innerHTML			=	formata_float(Arredonda(TotalDescontoItem,2),2).replace('.',',');	
						document.getElementById('tabelaTotalRepasse').innerHTML					=	formata_float(Arredonda(TotalRepasse,2),2).replace('.',',');	
						document.getElementById('tabelaTotal').innerHTML						=	"Total: "+i;	
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
						document.getElementById('tabelaTotalItem').innerHTML			=	"0,00";	
						document.getElementById('tabelaTotalDescontoItem').innerHTML	=	"0,00";
						document.getElementById('tabelaTotalRepasse').innerHTML			=	"0,00";	
						document.getElementById('tabelaTotal').innerHTML				=	"Total: 0";	
					}else{
						var tam, linha, c0, c1, c2, c3, c4, c5, c6, c7, c8;
						var IdLancamentoFinanceiro,Tipo,Valor,Codigo,Descricao,Referencia,TotalValor=0,TotalDescontoItem=0,TotalRepasse=0,IdPessoa;
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

							nameNode = xmlhttp.responseXML.getElementsByTagName("ValorDescontoItemRepasse")[i]; 
							nameTextNode = nameNode.childNodes[0];
							ValorDescontoItemRepasse = nameTextNode.nodeValue;

							tam 	= document.getElementById('tabelaLancFinanceiro').rows.length;
							linha	= document.getElementById('tabelaLancFinanceiro').insertRow(tam-1);
							
							if(tam%2 != 0){
								linha.style.backgroundColor = "#E2E7ED";
							}
							
							linha.accessKey = IdLancamentoFinanceiro; 
							
							TotalValor		=	parseFloat(TotalValor) + parseFloat(Valor);
							TotalRepasse	=	parseFloat(TotalRepasse) + parseFloat(ValorItemRepasse);
							TotalDescontoItem	=	parseFloat(TotalDescontoItem) + parseFloat(ValorDescontoItemRepasse);
							
							c0	= linha.insertCell(0);	
							c1	= linha.insertCell(1);	
							c2	= linha.insertCell(2);	
							c3	= linha.insertCell(3);
							c4	= linha.insertCell(4);
							c5	= linha.insertCell(5);
							c6	= linha.insertCell(6);
							c7	= linha.insertCell(7);
							c8	= linha.insertCell(8);
							
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

							c7.innerHTML =  formata_float(Arredonda(ValorDescontoItemRepasse,2),2).replace('.',',') ;
							c7.style.textAlign = "right";
							c7.style.padding  =	"0 8px 0 0";

							c8.innerHTML =  formata_float(Arredonda(ValorItemRepasse,2),2).replace('.',',');
							c8.style.textAlign = "right";
							c8.style.padding  =	"0 8px 0 0";
						}
						document.getElementById('tabelaTotalItem').innerHTML	=	formata_float(Arredonda(TotalValor,2),2).replace('.',',');	
						document.getElementById('tabelaTotalDescontoItem').innerHTML	=	formata_float(Arredonda(TotalDescontoItem,2),2).replace('.',',');	
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
	function remover_filtro_local_recebimento(IdLocalRecebimento){
		for(var i=0; i<document.getElementById('tabelaLocalRecebimento').rows.length; i++){
			if(IdLocalRecebimento == document.getElementById('tabelaLocalRecebimento').rows[i].accessKey){
				document.getElementById('tabelaLocalRecebimento').deleteRow(i);
				tableMultColor('tabelaLocalRecebimento');
				break;
			}
		}	
		var tempFiltro	=	document.formulario.Filtro_IdLocalRecebimento.value.split(',');
		var novoFiltro  = '';
		
		var ii = 0;
		while(tempFiltro[ii] != undefined){
			if(tempFiltro[ii] != IdLocalRecebimento){
				if(novoFiltro == ''){
					novoFiltro = tempFiltro[ii];
				}else{
					novoFiltro = novoFiltro + "," + tempFiltro[ii];
				}
			}
			ii=ii+1;
		}
		
		document.formulario.Filtro_IdLocalRecebimento.value = novoFiltro;
		document.getElementById('totaltabelaLocalRecebimento').innerHTML	=	'Total: '+(ii-1);
	}
	function validar_Data(id,campo){
		if(campo.value == ''){
			document.getElementById(id).style.color = "#000";
			document.getElementById(id).style.backgroundColor='#FFF';
			
			mensagens(0);
			return false;
		}
		if(isData(campo.value) == false){	
			document.getElementById(id).style.backgroundColor = '#C10000';
			document.getElementById(id).style.color='#FFF';
			mensagens(27);
			return false;
		}else{
			document.getElementById(id).style.color = "#000";
			document.getElementById(id).style.backgroundColor='#FFF';
		}	
	}
	function listar_carteira(IdAgenteAutorizado,IdCarteiraTemp,AddAgenteCarteira){
		while(document.formulario.IdCarteira.options.length > 0){
			document.formulario.IdCarteira.options[0] = null;
		}
		
		if(IdAgenteAutorizado == ''){
			return;
		}
		
		if(AddAgenteCarteira == undefined){
			AddAgenteCarteira = false;
		}
		
		if(IdCarteiraTemp == undefined){
			IdCarteiraTemp = '';
		}
		
		var url = "xml/carteira.php?IdAgenteAutorizado="+IdAgenteAutorizado+"&IdStatus=1";
		
		call_ajax(url, function (xmlhttp) {
			if(xmlhttp.responseText != 'false'){
				addOption(document.formulario.IdCarteira,"","0");
				
				for(var i=0; i<xmlhttp.responseXML.getElementsByTagName("IdCarteira").length; i++){
					var nameNode = xmlhttp.responseXML.getElementsByTagName("IdCarteira")[i]; 
					var nameTextNode = nameNode.childNodes[0];
					var IdCarteira = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("Nome")[i]; 
					nameTextNode = nameNode.childNodes[0];
					var Nome = nameTextNode.nodeValue;
					
					addOption(document.formulario.IdCarteira,Nome,IdCarteira);
				}
				
				if(IdCarteiraTemp!=''){
					for(ii=0;ii<document.formulario.IdCarteira.length;ii++){
						//alert(ii+"\n"+document.formulario.IdCarteira[ii].value+" = "+IdCarteiraTemp);
						if(document.formulario.IdCarteira[ii].value == IdCarteiraTemp){
							document.formulario.IdCarteira[ii].selected = true;
							break;
						}
					}
				}else{
					document.formulario.IdCarteira[0].selected = true;
				}
			}
			
			if(AddAgenteCarteira){
				busca_pessoa(IdAgenteAutorizado,false,'AdicionarAgenteAutorizadoCarteira','','listar');
			}
		});
	}
	function listar_pessoa(Filtro_IdPessoa) {
		if(Filtro_IdPessoa == undefined) {
			Filtro_IdPessoa = '';
		}
		
		busca_pessoa(0,false,document.formulario.Local.value);
		
		document.formulario.Filtro_IdPessoa.value = '';
		document.getElementById("totaltabelaPessoa").innerHTML = "Total: 0";
		
		while(document.getElementById("tabelaPessoa").rows.length > 2) {
			document.getElementById("tabelaPessoa").deleteRow(1);
		}
		
		if(Filtro_IdPessoa != "" || document.formulario.IdStatus.value == 1 || document.formulario.Acao.value == "inserir") {
			document.getElementById("cp_filtro_pessoa").style.display = "block";
			document.getElementById("cpFiltro").style.display = "block";
			
			if(Filtro_IdPessoa != "") {
				Filtro_IdPessoa = Filtro_IdPessoa.split(",");
				
				for(var i = 0; i < Filtro_IdPessoa.length; i++) {
					busca_pessoa(Filtro_IdPessoa[i],false,'AdicionarLoteRepasse');
				}
			}
		} else {
			document.getElementById("cp_filtro_pessoa").style.display = "none";
		}
	}
	function listar_cidade(Filtro_IdPaisEstadoCidade) {
		if(Filtro_IdPaisEstadoCidade == undefined) {
			Filtro_IdPaisEstadoCidade = '';
		}
		
		busca_pais(0,false,document.formulario.Local.value)
		
		document.formulario.Filtro_IdPaisEstadoCidade.value = '';
		document.getElementById("totaltabelaCidade").innerHTML = "Total: 0";
		
		while(document.getElementById("tabelaCidade").rows.length > 2) {
			document.getElementById("tabelaCidade").deleteRow(1);
		}
		
		if(Filtro_IdPaisEstadoCidade != "" || document.formulario.IdStatus.value == 1 || document.formulario.Acao.value == "inserir") {
			document.getElementById("cp_filtro_cidade").style.display = "block";
			document.getElementById("cpFiltro").style.display = "block";
			
			if(Filtro_IdPaisEstadoCidade != "") {
				Filtro_IdPaisEstadoCidade = Filtro_IdPaisEstadoCidade.split("^");
				
				for(var i = 0; i < Filtro_IdPaisEstadoCidade.length; i++) {
					var IdPaisEstadoCidade = Filtro_IdPaisEstadoCidade[i].split(",");
					busca_cidade(IdPaisEstadoCidade[0],IdPaisEstadoCidade[1],IdPaisEstadoCidade[2],false,"AdicionarLoteRepasse")
				}
			}
		} else {
			document.getElementById("cp_filtro_cidade").style.display = "none";
		}
	}
	function remover_filtro_cidade(IdPais,IdEstado,IdCidade){
		for(var i=0; i<document.getElementById('tabelaCidade').rows.length; i++){
			if(IdPais+","+IdEstado+","+IdCidade == document.getElementById('tabelaCidade').rows[i].accessKey){
				document.getElementById('tabelaCidade').deleteRow(i);
				tableMultColor('tabelaCidade');
				break;
			}
		}	
		var tempFiltro	=	document.formulario.Filtro_IdPaisEstadoCidade.value.split('^');
		var novoFiltro  = '';
		
		var ii = 0;
		while(tempFiltro[ii] != undefined){
			if(tempFiltro[ii] != IdPais+","+IdEstado+","+IdCidade){
				if(novoFiltro == ''){
					novoFiltro = tempFiltro[ii];
				}else{
					novoFiltro = novoFiltro + "^" + tempFiltro[ii];
				}
			}
			ii=ii+1;
		}
		
		document.formulario.Filtro_IdPaisEstadoCidade.value = novoFiltro;
		document.getElementById('totaltabelaCidade').innerHTML	= 'Total: '+(ii-1);
	}
	function listar_servico(Filtro_IdServico) {
		if(Filtro_IdServico == undefined) {
			Filtro_IdServico = '';
		}
		
		busca_servico(0,false,document.formulario.Local.value);
		
		document.formulario.Filtro_IdServico.value = '';
		document.getElementById("totaltabelaServico").innerHTML = "Total: 0";
		
		while(document.getElementById("tabelaServico").rows.length > 2) {
			document.getElementById("tabelaServico").deleteRow(1);
		}
		
		if(Filtro_IdServico != "" || document.formulario.IdStatus.value == 1 || document.formulario.Acao.value == "inserir") {
			document.getElementById("cp_filtro_servico").style.display = "block";
			document.getElementById("cpFiltro").style.display = "block";
			
			if(Filtro_IdServico != "") {
				Filtro_IdServico = Filtro_IdServico.split(",");
				
				for(var i = 0; i < Filtro_IdServico.length; i++) {
					busca_servico(Filtro_IdServico[i],false,'AdicionarLoteRepasse','busca')
				}
			}
		} else {
			document.getElementById("cp_filtro_servico").style.display = "none";
		}
	}
	function listar_local_recebimento(Filtro_IdLocalRecebimento) {
		if(Filtro_IdLocalRecebimento == undefined) {
			Filtro_IdLocalRecebimento = '';
		}
		
		document.formulario.IdLocalRecebimento.value = '';
		document.formulario.Filtro_IdLocalRecebimento.value = '';
		document.getElementById("totaltabelaLocalRecebimento").innerHTML = "Total: 0";
		
		while(document.getElementById("tabelaLocalRecebimento").rows.length > 2) {
			document.getElementById("tabelaLocalRecebimento").deleteRow(1);
		}
		
		if(Filtro_IdLocalRecebimento != "" || document.formulario.IdStatus.value == 1 || document.formulario.Acao.value == "inserir") {
			document.getElementById("cp_filtro_local_recebimento").style.display = "block";
			document.getElementById("cpFiltro").style.display = "block";
			
			if(Filtro_IdLocalRecebimento != "") {
				Filtro_IdLocalRecebimento = Filtro_IdLocalRecebimento.split(",");
				
				for(var i = 0; i < Filtro_IdLocalRecebimento.length; i++) {
					busca_local_cobranca(Filtro_IdLocalRecebimento[i],false,'AdicionarLoteRepasse')
				}
			}
		} else {
			document.getElementById("cp_filtro_local_recebimento").style.display = "none";
		}
	}
	function adicionar_agente_autorizado_carteira(IdAgenteAutorizado,IdCarteira) {
		if(IdCarteira == undefined || IdCarteira == '') {
			IdCarteira = 0;
		}
		
		var AccessKey = IdAgenteAutorizado+"_"+IdCarteira;
		
		if(document.formulario.Filtro_IdAgenteAutorizadoCarteira.value.split(",").in_array(AccessKey) || IdAgenteAutorizado == '') {
			document.formulario.IdAgenteAutorizado.value = '';
			document.formulario.NomeAgenteAutorizado.value = '';
			
			listar_carteira(0);
			return;
		}
		
		var url = "xml/agente.php?IdAgenteAutorizado="+IdAgenteAutorizado+"&IdCarteira="+IdCarteira;
		
		call_ajax(url, function (xmlhttp) {
			if(xmlhttp.responseText != "false") {
				var nameNode = xmlhttp.responseXML.getElementsByTagName("NomeCarteira")[0];
				var nameTextNode = nameNode.childNodes[0];
				var NomeCarteira = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("Nome")[0];
				nameTextNode = nameNode.childNodes[0];
				var Nome = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("RazaoSocial")[0];
				nameTextNode = nameNode.childNodes[0];
				var RazaoSocial = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("CPF_CNPJ")[0];
				nameTextNode = nameNode.childNodes[0];
				var CPF_CNPJ = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("NomeCidade")[0];
				nameTextNode = nameNode.childNodes[0];
				var NomeCidade = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("SiglaEstado")[0];
				nameTextNode = nameNode.childNodes[0];
				var SiglaEstado = nameTextNode.nodeValue;
				
				if(RazaoSocial == 1) {
					Nome = RazaoSocial;
				}
				
				if(document.formulario.Filtro_IdAgenteAutorizadoCarteira.value != ''){
					document.formulario.Filtro_IdAgenteAutorizadoCarteira.value += ",";
				}
				
				document.formulario.Filtro_IdAgenteAutorizadoCarteira.value += AccessKey;
				
				tam = document.getElementById("tabelaAgenteAutorizadoCarteira").rows.length;
				linha = document.getElementById("tabelaAgenteAutorizadoCarteira").insertRow(tam-1);
				
				if((tam % 2) != 0) {
					linha.style.backgroundColor = "#E2E7ED";
				}
				
				linha.accessKey = AccessKey;
				
				var c0 = linha.insertCell(0);
				var c1 = linha.insertCell(1);
				var c2 = linha.insertCell(2);
				var c3 = linha.insertCell(3);
				var c4 = linha.insertCell(4);
				var c5 = linha.insertCell(5);
				var c6 = linha.insertCell(6);
				
				var linkIni = "<a href='cadastro_agente.php?IdPessoa="+IdAgenteAutorizado+"'>";
				var linkFim = "</a>";
				
				c0.innerHTML = linkIni + IdAgenteAutorizado + linkFim;
				c0.className = "tableListarEspaco";
				
				c1.innerHTML = linkIni + Nome.substr(0,25); + linkFim;
				
				c2.innerHTML = linkIni + CPF_CNPJ + linkFim;
				
				c3.innerHTML = linkIni + NomeCidade + linkFim;
				
				c4.innerHTML = linkIni + SiglaEstado + linkFim;
				
				c5.innerHTML = linkIni + NomeCarteira + linkFim;
				
				if(document.formulario.IdStatus.value == 1 || document.formulario.IdStatus.value == '') {
					c6.innerHTML = "<img src='../../img/estrutura_sistema/ico_del.gif' alt='Excluir?' onClick=\"remover_filtro_agente_autorizado_carteira('"+AccessKey+"')\"></tr>";
				} else {
					c6.innerHTML = "<img src='../../img/estrutura_sistema/ico_del_c.gif' alt='Excluir?'\">";
				}
				
				c6.style.textAlign = "right";
				c6.style.cursor = "pointer";
				
				document.getElementById("totaltabelaAgenteAutorizadoCarteira").innerHTML = "Total: "+(tam-1);
				document.formulario.IdAgenteAutorizado.value = '';
				document.formulario.NomeAgenteAutorizado.value = '';
				
				listar_carteira(0);
			}
		});
	}
	function listar_agente_autorizado_carteira(IdAgenteAutorizado,IdCarteira) {
		if(IdAgenteAutorizado == undefined) { 
			IdAgenteAutorizado = '';
		}
		
		if(IdCarteira == undefined) {
			IdCarteira = '';
		}
		
		document.getElementById("totaltabelaAgenteAutorizadoCarteira").innerHTML = "Total: 0";
		document.formulario.Filtro_IdAgenteAutorizadoCarteira.value = '';
		document.formulario.IdAgenteAutorizado.value = '';
		document.formulario.NomeAgenteAutorizado.value = '';
		
		listar_carteira(0);
		
		while(document.getElementById("tabelaAgenteAutorizadoCarteira").rows.length > 2) {
			document.getElementById("tabelaAgenteAutorizadoCarteira").deleteRow(1);
		}
		
		if((IdAgenteAutorizado != '' && IdCarteira != '') || document.formulario.IdStatus.value == 1 || document.formulario.Acao.value == "inserir") {
			document.getElementById("cp_filtro_agente_autorizado_carteira").style.display = "block";
			document.getElementById("cpFiltro").style.display = "block";
		
			if(IdAgenteAutorizado != '' && IdCarteira != '') {
				IdAgenteAutorizado = IdAgenteAutorizado.split(",");
				IdCarteira = IdCarteira.split(",");
				
				for(var i = 0; i < IdAgenteAutorizado.length; i++) {
					adicionar_agente_autorizado_carteira(IdAgenteAutorizado[i],IdCarteira[i]);
				}
			}
		} else {
			document.getElementById("cp_filtro_agente_autorizado_carteira").style.display = "none";
		}
	}
	function remover_filtro_agente_autorizado_carteira(AccessKey) {
		var tam = document.getElementById("tabelaAgenteAutorizadoCarteira").rows.length;
		
		for(var i = 0; i < tam; i++){
			if(document.getElementById("tabelaAgenteAutorizadoCarteira").rows[i].accessKey == AccessKey){
				document.getElementById("tabelaAgenteAutorizadoCarteira").deleteRow(i);
				tableMultColor("tabelaAgenteAutorizadoCarteira");
				tam--;
				break;
			}
		}
		
		var Filtro_IdAgenteAutorizadoCarteiraTemp = document.formulario.Filtro_IdAgenteAutorizadoCarteira.value.split(","), IdAgenteAutorizadoCarteira = '';
		
		for(var i = 0; i < Filtro_IdAgenteAutorizadoCarteiraTemp.length; i++) {
			if(Filtro_IdAgenteAutorizadoCarteiraTemp[i] != AccessKey) {
				if(IdAgenteAutorizadoCarteira != '') {
					IdAgenteAutorizadoCarteira += ",";
				}
				
				IdAgenteAutorizadoCarteira += Filtro_IdAgenteAutorizadoCarteiraTemp[i];
			}
		}
		
		document.formulario.Filtro_IdAgenteAutorizadoCarteira.value	= IdAgenteAutorizadoCarteira;
		document.getElementById("totaltabelaAgenteAutorizadoCarteira").innerHTML = "Total: "+(tam-2);
	}