	function janela_busca_nota_fiscal(){
		janelas('busca_nota_fiscal.php',360,283,250,100,'');
	}
	function busca_nota_fiscal(NumeroNF, IdFornecedor, SerieNF, IdMovimentacaoProduto, Erro, Local){
		if(IdMovimentacaoProduto== undefined){ 
			IdMovimentacaoProduto = '';
		}
		if(IdMovimentacaoProduto ==''){
			if(NumeroNF == '' || NumeroNF == undefined){
				NumeroNF = 0;
			}
			if(IdFornecedor == '' || IdFornecedor == undefined){
				IdFornecedor = 0;
			}
			if(SerieNF == '' || SerieNF == undefined){
				SerieNF = 0;
			}
		}else{
			NumeroNF = '';
			CPF_CNPJ = '';
			SerieNF  = '';
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
	    
	    IdMovimentacaoProduto = parseInt(IdMovimentacaoProduto);
	    
		url = "xml/nota_fiscal.php?NumeroNF="+NumeroNF+"&IdFornecedor="+IdFornecedor+"&SerieNF="+SerieNF+"&IdMovimentacaoProduto="+IdMovimentacaoProduto;
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
					//	if(IdMovimentacaoProduto != ''){
							document.formulario.IdMovimentacaoProduto.value 		= '';
							document.formulario.TipoMovimentacao[0].selected		= true;
							document.formulario.IdEstoque[0].selected				= true;
							document.formulario.CFOP.value 							= '';
							document.formulario.NaturezaOperacao.value 				= '';
							document.formulario.DataNF.value 						= '';
							document.formulario.ValorNF.value 						= '';
							document.formulario.Produtos.value 						= '';
							document.formulario.ValorTotalICMSTemp.value 			= '';
							document.formulario.ValorTotalNFTemp.value 				= '';
							document.formulario.QtdProduto.value 					= '';
							document.formulario.ValorBaseCalculoICMS.value 			= '';
							document.formulario.ValorTotalICMS.value 				= '';
							document.formulario.ValorTotalProduto.value 			= '';
							document.formulario.ValorFrete.value 					= '';
							document.formulario.ValorSeguro.value 					= '';
							document.formulario.ValorOutrasDespesas.value 			= '';
							document.formulario.ValorTotalIPI.value 				= '';
							document.formulario.ValorTotalNF.value 					= '';
							document.formulario.Obs.value 							= '';
							document.formulario.DataCriacao.value 					= '';
							document.formulario.LoginCriacao.value 					= '';
							document.formulario.DataAlteracao.value 				= '';
							document.formulario.LoginAlteracao.value				= '';
							document.formulario.NumeroSerieTemp.value				= '';
							document.formulario.QtdProduto.value 					= '';
						
							while(document.getElementById('tabelaProduto').rows.length > 1){
								document.getElementById('tabelaProduto').deleteRow(1);
							}
						
							addProduto();
						//}
						
						document.formulario.Acao.value 							= 'inserir';
						
						addParmUrl("marNotaFiscal","IdMovimentacaoProduto",'');
						
						if(IdMovimentacaoProduto == ''){
							document.formulario.NumeroNF.focus();
						}
						
						// Fim de Carregando
						carregando(false);
					}else{
						nameNode = xmlhttp.responseXML.getElementsByTagName("IdMovimentacaoProduto")[0]; 
						nameTextNode = nameNode.childNodes[0];
						IdMovimentacaoProduto = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("NumeroNF")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var NumeroNF = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("IdFornecedor")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var IdFornecedor = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("DataNF")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var DataNF = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("SerieNF")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var SerieNF = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("IdEstoque")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var IdEstoque = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("TipoMovimentacao")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var TipoMovimentacao = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("CFOP")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var CFOP = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("NaturezaOperacao")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var NaturezaOperacao = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("ValorNF")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var ValorNF = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("ValorTotalProduto")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var ValorTotalProduto = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("ValorFrete")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var ValorFrete = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("ValorSeguro")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var ValorSeguro = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("ValorBaseCalculoICMS")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var ValorBaseCalculoICMS = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("ValorICMS")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var ValorICMS = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("ValorTotalIPI")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var ValorTotalIPI = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("ValorOutrasDespesas")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var ValorOutrasDespesas = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("Obs")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var Obs = nameTextNode.nodeValue;
							
						nameNode = xmlhttp.responseXML.getElementsByTagName("DataCriacao")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var DataCriacao = nameTextNode.nodeValue;
					
						nameNode = xmlhttp.responseXML.getElementsByTagName("LoginCriacao")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var LoginCriacao = nameTextNode.nodeValue;					
					
						nameNode = xmlhttp.responseXML.getElementsByTagName("DataAlteracao")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var DataAlteracao = nameTextNode.nodeValue;
					
						nameNode = xmlhttp.responseXML.getElementsByTagName("LoginAlteracao")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var LoginAlteracao = nameTextNode.nodeValue;
						
						while(document.getElementById('tabelaProduto').rows.length > 1){
							document.getElementById('tabelaProduto').deleteRow(1);
						}
						
						document.formulario.IdMovimentacaoProduto.value			= IdMovimentacaoProduto;
						document.formulario.NumeroNF.value 						= NumeroNF;						
						document.formulario.DataNF.value 						= dateFormat(DataNF);
						document.formulario.SerieNF.value 						= SerieNF;
						document.formulario.TipoMovimentacao.value 				= TipoMovimentacao;
						document.formulario.CFOP.value 							= CFOP;
						document.formulario.NaturezaOperacao.value 				= NaturezaOperacao;
						document.formulario.IdEstoque.value 					= IdEstoque;
						document.formulario.ValorNF.value 						= formata_float(Arredonda(ValorNF,2),2).replace('.',',');
						//document.formulario.ValorTotalNF.value 					= formata_float(Arredonda(ValorNF,2),2).replace('.',',');
						//document.formulario.ValorTotalNFTemp.value 				= formata_float(Arredonda(ValorNF,2),2).replace('.',',');
						//document.formulario.ValorTotalProduto.value 			= formata_float(Arredonda(ValorTotalProduto,2),2).replace('.',',');
						document.formulario.ValorFrete.value 					= formata_float(Arredonda(ValorFrete,2),2).replace('.',',');
						document.formulario.ValorSeguro.value 					= formata_float(Arredonda(ValorSeguro,2),2).replace('.',',');
						//document.formulario.ValorBaseCalculoICMS.value 			= formata_float(Arredonda(ValorBaseCalculoICMS,2),2).replace('.',',');
						document.formulario.ValorTotalICMS.value 				= formata_float(Arredonda(ValorICMS,2),2).replace('.',',');
						//document.formulario.ValorTotalICMSTemp.value 			= formata_float(Arredonda(ValorICMS,2),2).replace('.',',');
						//document.formulario.ValorTotalIPI.value 				= formata_float(Arredonda(ValorTotalIPI,2),2).replace('.',',');
						document.formulario.Obs.value 							= Obs;
						document.formulario.Produtos.value						= '';
						document.formulario.QtdProduto.value					= '';
						document.formulario.ValorOutrasDespesas.value 			= formata_float(Arredonda(ValorOutrasDespesas,2),2).replace('.',',');
						document.formulario.IdPessoa.value 						= IdFornecedor;
						document.formulario.DataCriacao.value 					= dateFormat(DataCriacao);
						document.formulario.LoginCriacao.value 					= LoginCriacao;
						document.formulario.DataAlteracao.value 				= dateFormat(DataAlteracao);
						document.formulario.LoginAlteracao.value				= LoginAlteracao;
						document.formulario.Acao.value 							= 'alterar';
						
						document.formulario.ValorTotalNF.value 					= '';
						document.formulario.ValorTotalNFTemp.value 				= '';
						document.formulario.ValorTotalProduto.value 			= '';
						document.formulario.ValorBaseCalculoICMS.value 			= '';
						//document.formulario.ValorTotalICMS.value 				= '';
						document.formulario.ValorTotalICMSTemp.value 			= '';
						document.formulario.ValorTotalIPI.value 				= '';
						document.formulario.NumeroSerieTemp.value				= '';
						
						busca_pessoa(IdFornecedor,false,Local);
						busca_nota_fiscal_produto(IdMovimentacaoProduto,false,Local);
						
						addParmUrl("marNotaFiscal","IdMovimentacaoProduto",IdMovimentacaoProduto);
					}
					if(window.janela != undefined){
						window.janela.close();
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
	function busca_nota_fiscal_produto(IdMovimentacaoProduto, Erro, Local){
		if(IdMovimentacaoProduto == ''){
			IdMovimentacaoProduto = 0;
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
	    
	   	url = "xml/nota_fiscal_produto.php?IdMovimentacaoProduto="+IdMovimentacaoProduto;
	   	xmlhttp.open("GET", url,true);
	
		xmlhttp.onreadystatechange = function(){ 
			// Carregando...
			carregando(true);
	
			if(xmlhttp.readyState == 4){ 
				if(xmlhttp.status == 200){
					if(xmlhttp.responseText != 'false'){		
						var IdProduto,Unidade,Quantidade,DescricaoReduzidaProduto,ValorUnitario,AliquotaIPI,AliquotaICMS,ValorIPI,posIni,posFim,ValorTotal;
						var posIni=0,posFim=0;
						
						for(var i=0; i<xmlhttp.responseXML.getElementsByTagName("IdMovimentacaoProduto").length; i++){
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("IdMovimentacaoProduto")[i]; 
							nameTextNode = nameNode.childNodes[0];
							IdMovimentacaoProduto = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("IdProduto")[i]; 
							nameTextNode = nameNode.childNodes[0];
							IdProduto = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoReduzidaProduto")[i]; 
							nameTextNode = nameNode.childNodes[0];
							DescricaoReduzidaProduto = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("Unidade")[i]; 
							nameTextNode = nameNode.childNodes[0];
							Unidade = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("IdProduto")[i]; 
							nameTextNode = nameNode.childNodes[0];
							IdProduto = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("Quantidade")[i]; 
							nameTextNode = nameNode.childNodes[0];
							Quantidade = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("ValorUnitario")[i]; 
							nameTextNode = nameNode.childNodes[0];
							ValorUnitario = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("AliquotaIPI")[i]; 
							nameTextNode = nameNode.childNodes[0];
							AliquotaIPI = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("AliquotaICMS")[i]; 
							nameTextNode = nameNode.childNodes[0];
							AliquotaICMS = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("ValorIPI")[i]; 
							nameTextNode = nameNode.childNodes[0];
							ValorIPI = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("NSerie")[i]; 
							nameTextNode = nameNode.childNodes[0];
							NSerie = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("NumeroSerieObrigatorio")[i]; 
							nameTextNode = nameNode.childNodes[0];
							NumeroSerieObrigatorio = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("NumeroSerie")[i]; 
							nameTextNode = nameNode.childNodes[0];
							NumeroSerie = nameTextNode.nodeValue;
							
							addProduto(IdProduto);
							
							if(document.formulario.Produtos.value !=''){
								document.formulario.Produtos.value	+=	'#';
							}
							document.formulario.Produtos.value	+=	IdProduto;	
							
							ValorTotal	=	Quantidade * ValorUnitario;
							
							posIni=0;
							posFim=0;
							
							for(ii=0;ii<document.formulario.length;ii++){
								if(document.formulario[ii].name.substr(0,10) == 'IdProduto_'){
									if(posIni==0){
										posIni = ii;
										posFim = ii;
									}else{
										posFim = ii;
									}
								}
							}	
							
							for(ii=posIni;ii<=posFim;ii=ii+9){	
								if(document.formulario[ii].name.substr(0,10) == 'IdProduto_'){
									id	=	document.formulario[ii].name.split('_');
									if(id[1] == document.formulario.QtdProduto.value){
										document.formulario[ii].value		=	IdProduto;
										document.formulario[ii].readOnly	=	true;
										document.formulario[ii+1].value		=	DescricaoReduzidaProduto;
										document.formulario[ii+2].value		=	Unidade;
										document.formulario[ii+3].value		=	formata_float(Arredonda(Quantidade,2),2).replace('.',',');
										document.formulario[ii+4].value		=	formata_float(Arredonda(ValorUnitario,5),5).replace('.',',');
										document.formulario[ii+5].value		=	formata_float(Arredonda(ValorTotal,2),2).replace('.',',');
										document.formulario[ii+6].value		=	formata_float(Arredonda(AliquotaICMS,2),2).replace('.',',');
										document.formulario[ii+7].value		=	formata_float(Arredonda(AliquotaIPI,2),2).replace('.',',');
										document.formulario[ii+8].value		=	formata_float(Arredonda(ValorIPI,2),2).replace('.',',');
										
										ii = posFim;
										
										calcula_somatorio_produto();
										calcula_total_nf();
									}
								}
							}
							
							posIni=0;
							posFim=0;
							
							for(ii=0;ii<document.formulario.length;ii++){
								if(document.formulario[ii].name.substr(0,7) == 'NSerie_'){
									if(posIni==0){
										posIni = ii;
										posFim = ii;
									}else{
										posFim = ii;
									}
								}
							}	
							
							for(ii=posIni;ii<=posFim;ii=ii+3){	
								if(document.formulario[ii].name.substr(0,7) == 'NSerie_'){
									id	=	document.formulario[ii].name.split('_');
									if(id[1] == document.formulario.QtdProduto.value){
										document.formulario[ii].value		=	NSerie;
										document.formulario[ii+1].value		=	NumeroSerieObrigatorio;
										document.formulario[ii+2].value		=	NumeroSerie;
										
										
										if(NSerie == 2){
											document.getElementById('icoNSerie'+document.formulario.QtdProduto.value).src	=	'../../img/estrutura_sistema/ico_serie_c.gif';
										}else{
											document.getElementById('icoNSerie'+document.formulario.QtdProduto.value).src	=	'../../img/estrutura_sistema/ico_serie.gif';
										}
										
										ii = posFim;
									}
								}
							}
								
						}
						document.formulario.NumeroNF.focus();	
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

