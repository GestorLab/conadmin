	function janela_busca_conta_receber(IdStatus){
		janelas('../administrativo/busca_conta_receber.php',530,350,250,100,'');
	}
	function busca_conta_receber(IdContaReceber,Erro,Local,ListarCampo){
		if(IdContaReceber == ''){
			IdContaReceber = 0;
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
	    
	   	url = "xml/conta_receber.php?IdContaReceber="+IdContaReceber;
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
						switch (Local){
							case 'ContaReceberRecebimento':	
								document.formulario.IdContaReceber.value 			= '';
								document.formulario.NumeroDocumento.value 			= '';
								document.formulario.DataLancamento.value 			= '';
								document.formulario.DataVencimento.value 			= '';
								document.formulario.DataRecebimento.value 			= '';
								document.formulario.ValorContaReceber.value	 		= '';
								document.formulario.ValorDespesas.value				= '';
								document.formulario.ValorDesconto.value 			= '';
								document.formulario.ValorFinal.value 				= '';
								document.formulario.ValorReceber.value 				= '';
								document.formulario.ValorRecebimento.value 			= '';
								document.formulario.ValorMoraMulta.value 			= '';
								document.formulario.ValorOutrasDespesas.value 		= '';
								document.formulario.IdStatus.value					= '';
								document.formulario.IdLocalCobranca.value 			= '';
								document.formulario.IdArquivoRetorno.value			= '';
								document.formulario.IdLocalRecebimento.value 		= '';
								document.formulario.ValorDescontoRecebimento.value 	= '';
								document.formulario.IdRecibo.value			 		= '';
								document.formulario.Obs.value	 					= '';
								document.formulario.NumeroNF.value 					= '';
								document.formulario.DataNF.value 					= '';
								document.formulario.DataCriacao.value 				= '';
								document.formulario.LoginCriacao.value 				= '';
								document.formulario.DataAlteracao.value 			= '';
								document.formulario.LoginAlteracao.value			= '';
								document.formulario.Acao.value 						= '';
								document.formulario.IdPessoa.value 					= '';
								document.formulario.HistoricoObs.value 				= '';
								document.formulario.IdStatusRecebimento.value		= "";	
								document.formulario.IdTipoLocalCobranca.value		= "";
								document.formulario.IdContaReceberRecebimento.value	= "";		
								
								verificaAcao();
								
								while(document.getElementById('tabelaLancFinanceiro').rows.length > 2){
									document.getElementById('tabelaLancFinanceiro').deleteRow(1);
								}
								while(document.getElementById('tabelaRecebimentos').rows.length > 2){
									document.getElementById('tabelaRecebimentos').deleteRow(1);
								}
								while(document.getElementById('tabelaParametro').rows.length > 0){
									document.getElementById('tabelaParametro').deleteRow(0);
								}
								
								document.getElementById('cpHistorico').style.display				=	'none';						
								document.getElementById('cp_parametros').style.display				=	'none';						
								
								document.getElementById('cp_lancamentos_financeiros').style.display	=	'none';						
								document.getElementById('tabelaLancFinanceiroTotalValor').innerHTML	=	"0,00";	
								document.getElementById('tabelaLancFinanceiroTotal').innerHTML		=	"Total: 0";
								
								document.getElementById('totalValorDesconto').innerHTML				=	"0,00";						
								document.getElementById('totalValorRecebido').innerHTML				=	"0,00";	
								document.getElementById('totalRecebimentos').innerHTML				=	"Total: 0";	
								
								document.getElementById('cp_Status').style.display			= 'none';	
								document.getElementById('cp_Status').innerHTML				= "";
								
								busca_pessoa('','false',document.formulario.Local.value);
								
								addParmUrl("marPessoa","IdContaReceber","");
								addParmUrl("marPessoa","IdPessoa",IdPessoa);
								addParmUrl("marPessoaNovo","IdContaReceber","");
								addParmUrl("marContasReceber","IdPessoa",IdPessoa);
								addParmUrl("marContasReceber","IdContaReceber","");
								addParmUrl("marLancamentoFinanceiro","IdContaReceber","");
								addParmUrl("marReenvioEmail","IdContaReceber","");
								addParmUrl("marReenvioEmail","IdPessoa","");
								
								document.formulario.bt_cancelar.disabled			=	true;
								document.formulario.IdContaReceber.focus();
								break;	
							default:	
								document.formulario.IdContaReceber.value 			= '';
								document.formulario.NumeroDocumento.value 			= '';
								document.formulario.DataLancamento.value 			= '';
								document.formulario.DataVencimento.value 			= '';
								document.formulario.DataRecebimento.value 			= '';
								document.formulario.ValorContaReceber.value	 		= '';
								document.formulario.ValorDespesas.value				= '';
								document.formulario.ValorDesconto.value 			= '';
								document.formulario.ValorFinal.value 				= '';
								document.formulario.ValorReceber.value 				= '';
								document.formulario.ValorRecebimento.value 			= '';
								document.formulario.ValorMoraMulta.value 			= '';
								document.formulario.ValorOutrasDespesas.value 		= '';
								document.formulario.IdStatus.value					= '';
								document.formulario.IdLocalCobranca.value 			= '';
								document.formulario.IdArquivoRetorno.value			= '';
								document.formulario.IdLocalRecebimento.value 		= '';
								document.formulario.ValorDescontoRecebimento.value 	= '';
								document.formulario.IdRecibo.value			 		= '';
								document.formulario.Obs.value	 					= '';
								document.formulario.NumeroNF.value 					= '';
								document.formulario.DataNF.value 					= '';
								document.formulario.DataCriacao.value 				= '';
								document.formulario.LoginCriacao.value 				= '';
								document.formulario.DataAlteracao.value 			= '';
								document.formulario.LoginAlteracao.value			= '';
								document.formulario.Acao.value 						= '';
								document.formulario.IdPessoa.value 					= '';
								document.formulario.HistoricoObs.value 				= '';
								document.formulario.IdStatusRecebimento.value		= "";	
								document.formulario.IdTipoLocalCobranca.value		= "";	
								
								verificaAcao();
								statusInicial();
								
								while(document.getElementById('tabelaLancFinanceiro').rows.length > 2){
									document.getElementById('tabelaLancFinanceiro').deleteRow(1);
								}
								while(document.getElementById('tabelaRecebimentos').rows.length > 2){
									document.getElementById('tabelaRecebimentos').deleteRow(1);
								}
								while(document.getElementById('tabelaParametro').rows.length > 0){
									document.getElementById('tabelaParametro').deleteRow(0);
								}
								
								document.getElementById('cpHistorico').style.display				=	'none';						
								document.getElementById('cp_parametros').style.display				=	'none';						
								
								document.getElementById('cp_lancamentos_financeiros').style.display	=	'none';						
								document.getElementById('tabelaLancFinanceiroTotalValor').innerHTML	=	"0,00";	
								document.getElementById('tabelaLancFinanceiroTotal').innerHTML		=	"Total: 0";
								
								document.getElementById('totalValorDesconto').innerHTML				=	"0,00";						
								document.getElementById('totalValorRecebido').innerHTML				=	"0,00";	
								document.getElementById('totalRecebimentos').innerHTML				=	"Total: 0";	
								
								document.getElementById('cp_Status').style.display			= 'none';	
								document.getElementById('cp_Status').innerHTML				= "";
								
								busca_pessoa('','false',document.formulario.Local.value);
								
								addParmUrl("marPessoa","IdContaReceber","");
								addParmUrl("marPessoa","IdPessoa",IdPessoa);
								addParmUrl("marPessoaNovo","IdContaReceber","");
								addParmUrl("marContasReceber","IdPessoa",IdPessoa);
								addParmUrl("marContasReceber","IdContaReceber","");
								addParmUrl("marLancamentoFinanceiro","IdContaReceber","");
								addParmUrl("marReenvioEmail","IdContaReceber","");
								addParmUrl("marReenvioEmail","IdPessoa","");
								
								document.formulario.ValorDesconto.readOnly  			= false;
								document.formulario.ValorDespesas.readOnly 				= false;
								document.formulario.IdLocalRecebimento.disabled  		= false;
								document.formulario.DataRecebimento.readOnly 			= false;
								document.getElementById('imgDataRecebimento').src		= '../../img/estrutura_sistema/ico_date.gif';
								document.formulario.ValorDescontoRecebimento.readOnly 	= false;
								document.formulario.ValorMoraMulta.readOnly 			= false;
								document.formulario.ValorOutrasDespesas.readOnly    	= false;
								document.formulario.Obs.readOnly 						= false;
								
								document.formulario.bt_enviar.disabled		=	true;
								document.formulario.bt_alterar.disabled		=	true;
								document.formulario.bt_imprimir1.disabled	=	true;
								document.formulario.bt_imprimir2.disabled	=	true;
								document.formulario.bt_receber.disabled		=	true;
								document.formulario.bt_cancelar.disabled	=	true;
								document.formulario.IdContaReceber.focus();
								break;	
						}			
						// Fim de Carregando
						carregando(false);
					}else{
						mensagens(0);
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("IdLoja")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var IdLoja = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("IdPessoa")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var IdPessoa = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("IdContaReceber")[0]; 
						nameTextNode = nameNode.childNodes[0];
						IdContaReceber = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("NumeroDocumento")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var NumeroDocumento = nameTextNode.nodeValue;						
				
						nameNode = xmlhttp.responseXML.getElementsByTagName("DataLancamento")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var DataLancamento = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("DataVencimento")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var DataVencimento = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("ValorContaReceber")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var ValorContaReceber = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("DataRecebimento")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var DataRecebimento = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("ValorDescontoRecebimento")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var ValorDescontoRecebimento = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("ValorMoraMulta")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var ValorMoraMulta = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("ValorOutrasDespesas")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var ValorOutrasDespesas = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("ValorDespesas")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var ValorDespesas = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("ValorDesconto")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var ValorDesconto = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("IdArquivoRetorno")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var IdArquivoRetorno = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("IdRecibo")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var IdRecibo = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("IdStatus")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var IdStatus = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("IdLocalCobranca")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var IdLocalCobranca = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("IdArquivoRetorno")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var IdArquivoRetorno = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("IdLocalRecebimento")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var IdLocalRecebimento = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("Obs")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var Obs = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("NumeroNF")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var NumeroNF = nameTextNode.nodeValue;
				
						nameNode = xmlhttp.responseXML.getElementsByTagName("DataNF")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var DataNF = nameTextNode.nodeValue;		
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("ValorFinal")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var ValorFinal = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("ValorReceber")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var ValorReceber = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("IdProcessoFinanceiro")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var IdProcessoFinanceiro = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("IdContaReceberRecebimento")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var IdContaReceberRecebimento = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("IdArquivoRemessaTipo")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var IdArquivoRemessaTipo = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("IdTipoLocalCobranca")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var IdTipoLocalCobranca = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("Status")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var Status = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("Cor")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var Cor = nameTextNode.nodeValue;
						
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
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("Boleto")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var Boleto = nameTextNode.nodeValue;
						
						switch (Local){
							case 'Etiqueta':
								var cont = 0; ii='';
								if(ListarCampo == '' || ListarCampo == undefined){
									if(document.formulario.Filtro_IdContaReceber.value == ''){
										document.formulario.Filtro_IdContaReceber.value = IdContaReceber;
										ii = 0;
									}else{
										var tempFiltro	=	document.formulario.Filtro_IdContaReceber.value.split(',');
											
										ii=0; 
										while(tempFiltro[ii] != undefined){
											if(tempFiltro[ii] != IdContaReceber){
												cont++;		
											}
											ii++;
										}
										if(ii == cont){
											document.formulario.Filtro_IdContaReceber.value = document.formulario.Filtro_IdContaReceber.value + "," + IdContaReceber;
										}
									}
								}else{
									ii=0;
								}
								if(ii == cont){
								
									nameNode = xmlhttp.responseXML.getElementsByTagName("Nome")[0]; 
									nameTextNode = nameNode.childNodes[0];
									var Nome = nameTextNode.nodeValue;
									
									nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoLocalCobranca")[0]; 
									nameTextNode = nameNode.childNodes[0];
									var DescricaoLocalCobranca = nameTextNode.nodeValue;
									
									nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoLocalRecebimento")[0]; 
									nameTextNode = nameNode.childNodes[0];
									var DescricaoLocalRecebimento = nameTextNode.nodeValue;
															
									var tam, linha, c0, c1, c2, c3, c4, c5, c6, c7, c8, c9;
									
									tam 	= document.getElementById('tabelaContaReceber').rows.length;
									linha	= document.getElementById('tabelaContaReceber').insertRow(tam-1);
									
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
									c8	= linha.insertCell(8);
									c9	= linha.insertCell(9);
									
									var linkIni = "<a href='cadastro_conta_receber.php?IdContaReceber="+IdContaReceber+"'>";
									var linkFim = "</a>";
									
									Nome = Nome.substr(0,20);
									DescricaoLocalCobranca = DescricaoLocalCobranca.substr(0,15);
									DescricaoLocalRecebimento = DescricaoLocalRecebimento.substr(0,15);
									
									c0.innerHTML = linkIni + IdContaReceber + linkFim;
									c0.style.padding =	"0 0 0 5px";
									
									c1.innerHTML = linkIni + Nome + linkFim;
									
									c2.innerHTML = linkIni + NumeroDocumento + linkFim;
									
									c3.innerHTML = linkIni + DescricaoLocalCobranca + linkFim;
									
									c4.innerHTML = linkIni + dateFormat(DataLancamento) + linkFim;
									
									c5.innerHTML = linkIni + formata_float(Arredonda(ValorFinal,2),2).replace(".",",") + linkFim;
									c5.style.padding =	"0 8px 0 0";
									c5.style.textAlign =	"right";
									
									c6.innerHTML = linkIni + dateFormat(DataVencimento) + linkFim;
									
									c7.innerHTML = linkIni + dateFormat(DataRecebimento) + linkFim;
									
									c8.innerHTML = linkIni + DescricaoLocalRecebimento + linkFim;
									
									c9.innerHTML = "<img src='../../img/estrutura_sistema/ico_del.gif' alt='Excluir?' onClick=\"remover_filtro_conta_receber("+IdContaReceber+")\"></tr>";
									c9.style.textAlign = "center";
									c9.style.cursor = "pointer";
									
									
									document.getElementById('totaltabelaContaReceber').innerHTML	=	'Total: '+(ii+1);
								}
								break;
							case 'ContaReceberRecebimento':
								while(document.getElementById('tabelaLancFinanceiro').rows.length > 2){
									document.getElementById('tabelaLancFinanceiro').deleteRow(1);
								}
								while(document.getElementById('tabelaParametro').rows.length > 0){
									document.getElementById('tabelaParametro').deleteRow(0);
								}
								while(document.getElementById('tabelaRecebimentos').rows.length > 2){
									document.getElementById('tabelaRecebimentos').deleteRow(1);
								}
								document.formulario.IdPessoa.value 					= IdPessoa;								
								document.formulario.IdContaReceber.value 			= IdContaReceber;
								document.formulario.NumeroDocumento.value 			= NumeroDocumento;
								document.formulario.ValorContaReceber.value			= formata_float(Arredonda(ValorContaReceber,2),2).replace(".",",");
								document.formulario.DataLancamento.value 			= dateFormat(DataLancamento);
								document.formulario.DataVencimento.value 			= dateFormat(DataVencimento);
								document.formulario.DataRecebimento.value 			= "";
								
								if((ValorDesconto != '0.00' || ValorDesconto != '0') && (ValorDescontoRecebimento == '0.00' || ValorDescontoRecebimento == '0')){
									ValorDescontoRecebimento	=	ValorDesconto;
								}
								if((ValorDespesas != '0.00' || ValorDespesas != '0') && (ValorOutrasDespesas == '0.00' || ValorOutrasDespesas == '0')){
									ValorOutrasDespesas	=	ValorDespesas;
								}
								
								document.formulario.ValorDespesas.value				= formata_float(Arredonda(ValorDespesas,2),2).replace(".",",");
								document.formulario.ValorDesconto.value 			= formata_float(Arredonda(ValorDesconto,2),2).replace(".",",");
								document.formulario.ValorMoraMulta.value 			= "0,00";
								document.formulario.ValorDescontoRecebimento.value	= "0,00";
								document.formulario.ValorOutrasDespesas.value		= "0,00";
								document.formulario.ValorRecebimento.value			= formata_float(Arredonda(ValorFinal,2),2).replace(".",",");
								document.formulario.ValorFinal.value				= formata_float(Arredonda(ValorFinal,2),2).replace(".",",");
								document.formulario.ValorReceber.value				= formata_float(Arredonda(ValorFinal,2),2).replace(".",",");
								document.formulario.IdStatus.value					= IdStatus;
								document.formulario.IdLocalCobranca.value 			= IdLocalCobranca;
								document.formulario.IdArquivoRetorno.value			= "";
								document.formulario.IdLocalRecebimento[0].selected	= true;
								document.formulario.IdRecibo.value 					= "";
								document.formulario.Obs.value 						= "";
								document.formulario.IdStatusRecebimento.value		= "";
								document.formulario.IdContaReceberRecebimento.value	= "";	
								document.formulario.HistoricoObs.value 				= Obs;
								document.formulario.NumeroNF.value 					= NumeroNF;
								document.formulario.IdTipoLocalCobranca.value 		= IdTipoLocalCobranca;
								document.formulario.DataNF.value 					= dateFormat(DataNF);
								document.formulario.DataCriacao.value 				= "";
								document.formulario.LoginCriacao.value 				= "";
								document.formulario.DataAlteracao.value 			= "";
								document.formulario.LoginAlteracao.value			= "";								
								document.formulario.Acao.value 						= 'inserir';
								
								for(var i=0; i<document.formulario.IdLocalCobranca.length; i++){
									if(document.formulario.IdLocalCobranca[i].value == IdLocalCobranca){
										document.formulario.IdLocalCobranca[i].selected = true;
										i = document.formulario.IdLocalCobranca.length;
									}							
								}
								
								addParmUrl("marPessoa","IdContaReceber",IdContaReceber);
								addParmUrl("marPessoa","IdPessoa",IdPessoa);
								addParmUrl("marPessoaNovo","IdContaReceber",IdContaReceber);
								addParmUrl("marContrato","IdPessoa",IdPessoa);
								addParmUrl("marContratoNovo","IdPessoa",IdPessoa);
								addParmUrl("marContrato","IdContaReceber",IdContaReceber);
								addParmUrl("marContratoNovo","IdContaReceber",IdContaReceber);
								addParmUrl("marContasReceber","IdPessoa",IdPessoa);
								//addParmUrl("marContasReceber","IdContaReceber",IdContaReceber);
								addParmUrl("marLancamentoFinanceiro","IdContaReceber",IdContaReceber);
								addParmUrl("marLancamentoFinanceiro","IdPessoa",IdPessoa);
								addParmUrl("marProcessoFinanceiro","IdContaReceber",IdContaReceber);
								addParmUrl("marProcessoFinanceiro","IdPessoa",IdPessoa);
								addParmUrl("marContaEventual","IdPessoa",IdPessoa);
								addParmUrl("marContaEventualNovo","IdPessoa",IdPessoa);
								addParmUrl("marReenvioEmail","IdContaReceber",IdContaReceber);
								addParmUrl("marReenvioEmail","IdPessoa",IdPessoa);

								//Recebido 
								busca_lancamento_financeiro(IdContaReceber,false);
								listarParametro(IdLocalRecebimento,false);
								busca_pessoa(IdPessoa,false,document.formulario.Local.value);
								listarContaReceberParametroRecebimento(IdContaReceber,IdLocalRecebimento,false);
								listarRecebimento(IdContaReceber,false,ListarCampo);
								scrollWindow('top');
								
								if(IdTipoLocalCobranca == 3){
									document.getElementById('cpPosicaoCobranca').style.display		= 'block';
									document.getElementById('titPosicaoCobranca').style.display		= 'block';
								}else{
									document.getElementById('cpPosicaoCobranca').style.display		= 'none';
									document.getElementById('titPosicaoCobranca').style.display		= 'none';
								}
								
								document.getElementById('cp_Status').innerHTML			= Status;
								document.getElementById('cp_Status').style.display		= 'block';
								document.getElementById('cp_Status').style.color		= Cor;
								
								document.formulario.bt_cancelar.disabled	=	true;
								break;
							default:
								while(document.getElementById('tabelaLancFinanceiro').rows.length > 2){
									document.getElementById('tabelaLancFinanceiro').deleteRow(1);
								}
								while(document.getElementById('tabelaParametro').rows.length > 0){
									document.getElementById('tabelaParametro').deleteRow(0);
								}
								while(document.getElementById('tabelaRecebimentos').rows.length > 2){
									document.getElementById('tabelaRecebimentos').deleteRow(1);
								}
								document.formulario.IdPessoa.value 					= IdPessoa;
								
								document.formulario.IdContaReceber.value 			= IdContaReceber;
								document.formulario.NumeroDocumento.value 			= NumeroDocumento;
								document.formulario.ValorContaReceber.value			= formata_float(Arredonda(ValorContaReceber,2),2).replace(".",",");
								document.formulario.DataLancamento.value 			= dateFormat(DataLancamento);
								document.formulario.DataVencimento.value 			= dateFormat(DataVencimento);
								document.formulario.DataRecebimento.value 			= "";
								
								if((ValorDesconto != '0.00' || ValorDesconto != '0') && (ValorDescontoRecebimento == '0.00' || ValorDescontoRecebimento == '0')){
									ValorDescontoRecebimento	=	ValorDesconto;
								}
								if((ValorDespesas != '0.00' || ValorDespesas != '0') && (ValorOutrasDespesas == '0.00' || ValorOutrasDespesas == '0')){
									ValorOutrasDespesas	=	ValorDespesas;
								}
								
								document.formulario.ValorDespesas.value				= formata_float(Arredonda(ValorDespesas,2),2).replace(".",",");
								document.formulario.ValorDesconto.value 			= formata_float(Arredonda(ValorDesconto,2),2).replace(".",",");
								document.formulario.ValorMoraMulta.value 			= "0,00";
								document.formulario.ValorDescontoRecebimento.value	= "0,00";
								document.formulario.ValorOutrasDespesas.value		= "0,00";
								document.formulario.ValorRecebimento.value			= formata_float(Arredonda(ValorFinal,2),2).replace(".",",");
								document.formulario.ValorFinal.value				= formata_float(Arredonda(ValorFinal,2),2).replace(".",",");
								document.formulario.ValorReceber.value				= formata_float(Arredonda(ValorFinal,2),2).replace(".",",");
								document.formulario.IdStatus.value					= IdStatus;
								document.formulario.IdLocalCobranca.value 			= IdLocalCobranca;
								document.formulario.IdArquivoRetorno.value			= "";
								document.formulario.IdLocalRecebimento[0].selected	= true;
								document.formulario.IdRecibo.value 					= "";
								document.formulario.Obs.value 						= "";
								document.formulario.IdStatusRecebimento.value		= "";	
								document.formulario.HistoricoObs.value 				= Obs;
								document.formulario.NumeroNF.value 					= NumeroNF;
								document.formulario.IdTipoLocalCobranca.value 		= IdTipoLocalCobranca;
								document.formulario.DataNF.value 					= dateFormat(DataNF);
								document.formulario.DataCriacao.value 				= dateFormat(DataCriacao);
								document.formulario.LoginCriacao.value 				= LoginCriacao;
								document.formulario.DataAlteracao.value 			= dateFormat(DataAlteracao);
								document.formulario.LoginAlteracao.value			= LoginAlteracao;
								document.formulario.ValorMoraMulta.readOnly 			= true;
								document.formulario.ValorDescontoRecebimento.readOnly	= true;
								document.formulario.ValorOutrasDespesas.readOnly		= true;	
								document.formulario.IdLocalRecebimento.disabled			= false;								
								document.formulario.Acao.value 						= 'alterar';
								
								for(var i=0; i<document.formulario.IdLocalCobranca.length; i++){
									if(document.formulario.IdLocalCobranca[i].value == IdLocalCobranca){
										document.formulario.IdLocalCobranca[i].selected = true;
										i = document.formulario.IdLocalCobranca.length;
									}							
								}
								
								if(Obs!=""){
									document.getElementById('cpHistorico').style.display		=	'block';		
								}else{
									document.getElementById('cpHistorico').style.display		=	'none';		
								}
								
								addParmUrl("marPessoa","IdContaReceber",IdContaReceber);
								addParmUrl("marPessoa","IdPessoa",IdPessoa);
								addParmUrl("marPessoaNovo","IdContaReceber",IdContaReceber);
								addParmUrl("marContrato","IdPessoa",IdPessoa);
								addParmUrl("marContratoNovo","IdPessoa",IdPessoa);
								addParmUrl("marContrato","IdContaReceber",IdContaReceber);
								addParmUrl("marContratoNovo","IdContaReceber",IdContaReceber);
								addParmUrl("marContasReceber","IdPessoa",IdPessoa);
								//addParmUrl("marContasReceber","IdContaReceber",IdContaReceber);
								addParmUrl("marLancamentoFinanceiro","IdContaReceber",IdContaReceber);
								addParmUrl("marLancamentoFinanceiro","IdPessoa",IdPessoa);
								addParmUrl("marProcessoFinanceiro","IdContaReceber",IdContaReceber);
								addParmUrl("marProcessoFinanceiro","IdPessoa",IdPessoa);
								addParmUrl("marContaEventual","IdPessoa",IdPessoa);
								addParmUrl("marContaEventualNovo","IdPessoa",IdPessoa);
								addParmUrl("marReenvioEmail","IdContaReceber",IdContaReceber);
								addParmUrl("marReenvioEmail","IdPessoa",IdPessoa);

								//Recebido 
								busca_lancamento_financeiro(IdContaReceber,false);
								listarParametro(IdLocalRecebimento,false);
								busca_pessoa(IdPessoa,false,document.formulario.Local.value);
								listarContaReceberParametroRecebimento(IdContaReceber,IdLocalRecebimento,false);
								listarRecebimento(IdContaReceber,false);
								statusInicial();
								scrollWindow('top');
								
								switch(document.formulario.IdStatus.value){
									case '0':
										document.formulario.ValorDesconto.readOnly  			= true;
										document.formulario.ValorDespesas.readOnly 				= true;
										document.formulario.IdLocalRecebimento.disabled  		= true;
										document.formulario.DataRecebimento.readOnly 			= true;
										document.getElementById('imgDataRecebimento').src		= '../../img/estrutura_sistema/ico_date_c.gif';
										document.formulario.ValorDescontoRecebimento.readOnly 	= true;
										document.formulario.ValorMoraMulta.readOnly 			= true;
										document.formulario.ValorOutrasDespesas.readOnly    	= true;
										document.formulario.NumeroNF.readOnly 					= true;
										document.formulario.DataNF.readOnly    					= true;
										
										document.formulario.bt_enviar.disabled		=	true;
										document.formulario.bt_alterar.disabled		=	false;
										document.formulario.bt_imprimir1.disabled	=	true;
										document.formulario.bt_imprimir2.disabled	=	true;
										document.formulario.bt_receber.disabled		=	true;
										document.formulario.bt_cancelar.disabled	=	true;
										break;
									case '2':
										document.formulario.ValorDesconto.readOnly  			= false;
										document.formulario.ValorDespesas.readOnly 				= false;
										document.formulario.IdLocalRecebimento.disabled  		= false;
										document.formulario.DataRecebimento.readOnly 			= false;
										document.getElementById('imgDataRecebimento').src		= '../../img/estrutura_sistema/ico_date.gif';
										document.formulario.ValorDescontoRecebimento.readOnly 	= false;
										document.formulario.ValorMoraMulta.readOnly 			= false;
										document.formulario.ValorOutrasDespesas.readOnly    	= false;
										document.formulario.NumeroNF.readOnly 					= false;
										document.formulario.DataNF.readOnly    					= false;
										
										document.formulario.Acao.value				=	'imprimir';
										document.formulario.bt_enviar.disabled		=	false;
										document.formulario.bt_alterar.disabled		=	false;
										document.formulario.bt_imprimir1.disabled	=	false;
										document.formulario.bt_imprimir2.disabled	=	false;
										document.formulario.bt_receber.disabled		=	false;
										document.formulario.bt_cancelar.disabled	=	false;
										break;
									default:
										document.formulario.ValorDesconto.readOnly  			= false;
										document.formulario.ValorDespesas.readOnly 				= false;
										document.formulario.IdLocalRecebimento.disabled  		= false;
										document.formulario.DataRecebimento.readOnly 			= false;
										document.getElementById('imgDataRecebimento').src		= '../../img/estrutura_sistema/ico_date.gif';
										document.formulario.ValorDescontoRecebimento.readOnly 	= false;
										document.formulario.ValorMoraMulta.readOnly 			= false;
										document.formulario.ValorOutrasDespesas.readOnly    	= false;
										document.formulario.NumeroNF.readOnly 					= false;
										document.formulario.DataNF.readOnly    					= false;
										
										document.formulario.Acao.value				=	'receber';
										document.formulario.bt_enviar.disabled		=	false;
										document.formulario.bt_alterar.disabled		=	false;
										document.formulario.bt_imprimir1.disabled	=	false;
										document.formulario.bt_imprimir2.disabled	=	false;
										document.formulario.bt_receber.disabled		=	false;
										document.formulario.bt_cancelar.disabled	=	false;
										break;
								}
								
								
								if(IdTipoLocalCobranca == 3){
									document.getElementById('cpPosicaoCobranca').style.display		= 'block';
									document.getElementById('titPosicaoCobranca').style.display		= 'block';
								}else{
									document.getElementById('cpPosicaoCobranca').style.display		= 'none';
									document.getElementById('titPosicaoCobranca').style.display		= 'none';
								}
								
								document.getElementById('cp_Status').innerHTML			= Status;
								document.getElementById('cp_Status').style.display		= 'block';
								document.getElementById('cp_Status').style.color		= Cor;
								
								if(Boleto == 'true'){
									document.formulario.bt_imprimir1.disabled	=	false;
									document.formulario.bt_imprimir2.disabled	=	false;
								}else{
									document.formulario.bt_imprimir1.disabled	=	true;
									document.formulario.bt_imprimir2.disabled	=	true;
								}
								
								
								verificaAcao();
								break;
						}
							
					}					
					if(document.getElementById("quadroBuscaContaReceber") != null){
						if(document.getElementById("quadroBuscaContaReceber").style.display	==	"block"){
							document.getElementById("quadroBuscaContaReceber").style.display = "none";
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
	function remover_filtro_conta_receber(IdContaReceber){
		for(var i=0; i<document.getElementById('tabelaContaReceber').rows.length; i++){
			if(IdContaReceber == document.getElementById('tabelaContaReceber').rows[i].accessKey){
				document.getElementById('tabelaContaReceber').deleteRow(i);
				tableMultColor('tabelaContaReceber');
				break;
			}
		}	
		var tempFiltro	=	document.formulario.Filtro_IdContaReceber.value.split(',');
		var novoFiltro  = '';
		
		var ii = 0;
		while(tempFiltro[ii] != undefined){
			if(tempFiltro[ii] != IdContaReceber){
				if(novoFiltro == ''){
					novoFiltro = tempFiltro[ii];
				}else{
					novoFiltro = novoFiltro + "," + tempFiltro[ii];
				}
			}
			ii=ii+1;
		}
		
		document.formulario.Filtro_IdContaReceber.value = novoFiltro;
		document.getElementById('totaltabelaContaReceber').innerHTML	=	'Total: '+(ii-1);
	}	
	
	function busca_lancamento_financeiro(IdContaReceber,Erro,IdStatus){
		if(IdContaReceber == undefined || IdContaReceber==''){
			IdContaReceber = 0;
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
	    
	   	url = "xml/demonstrativo.php?IdContaReceber="+IdContaReceber;
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
						document.getElementById('cp_lancamentos_financeiros').style.display	=	'none';						
						document.getElementById('tabelaLancFinanceiroTotalValor').innerHTML	=	"0,00";	
						document.getElementById('tabelaLancFinanceiroTotal').innerHTML		=	"Total: 0";	
						
						// Fim de Carregando
						carregando(false);
					}else{
						document.getElementById('cp_lancamentos_financeiros').style.display	=	'block';	
						var tam, linha, c0, c1, c2, c3, c4;
						var IdLancamentoFinanceiro,Tipo,Valor,Codigo,Descricao,Referencia,TotalValor=0,IdPessoa;
						for(var i=0; i<xmlhttp.responseXML.getElementsByTagName("IdLancamentoFinanceiro").length; i++){	
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("IdLancamentoFinanceiro")[i]; 
							nameTextNode = nameNode.childNodes[0];
							IdLancamentoFinanceiro = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("Valor")[i]; 
							nameTextNode = nameNode.childNodes[0];
							Valor = nameTextNode.nodeValue;	
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("Tipo")[i]; 
							nameTextNode = nameNode.childNodes[0];
							Tipo = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("Codigo")[i]; 
							nameTextNode = nameNode.childNodes[0];
							Codigo = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("Descricao")[i]; 
							nameTextNode = nameNode.childNodes[0];
							Descricao = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("Referencia")[i]; 
							nameTextNode = nameNode.childNodes[0];
							Referencia = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("IdStatus")[i]; 
							nameTextNode = nameNode.childNodes[0];
							IdStatus = nameTextNode.nodeValue;
							
							tam 	= document.getElementById('tabelaLancFinanceiro').rows.length;
							linha	= document.getElementById('tabelaLancFinanceiro').insertRow(tam-1);
							
							if(tam%2 != 0){
								linha.style.backgroundColor = "#E2E7ED";
							}
							
							switch(IdStatus){
								case '0':
									linha.style.backgroundColor = "#FFD2D2";
									break;
								case '2':
									linha.style.backgroundColor = "#FFFFCA";
									break;
							}
							
							if(Valor == '') Valor = 0;
							
							linha.accessKey = IdLancamentoFinanceiro; 
							
							TotalValor	=	parseFloat(TotalValor) + parseFloat(Valor);
							
							c0	= linha.insertCell(0);	
							c1	= linha.insertCell(1);	
							c2	= linha.insertCell(2);	
							c3	= linha.insertCell(3);
							c4	= linha.insertCell(4);
							
							switch(Tipo){
								case 'CO':
									linkIni	= "<a href='cadastro_contrato.php?IdContrato="+Codigo+"'>";	
									break;
								case 'EV':
									linkIni	= "<a href='cadastro_conta_eventual.php?IdContaEventual="+Codigo+"'>";	
									break;
								case 'OS':
									linkIni	= "<a href='cadastro_ordem_servico.php?IdOrdemServico="+Codigo+"'>";	
									break;
							}
							
							linkFim	=	"</a>";
							
							c0.innerHTML = linkIni + Tipo + linkFim;
							c0.style.padding  =	"0 0 0 5px";
							
							c1.innerHTML = linkIni + Codigo + linkFim;
							c1.style.cursor = "pointer";
							
							c2.innerHTML = Descricao;
							
							c3.innerHTML = Referencia;
							
							c4.innerHTML =  formata_float(Arredonda(Valor,2),2).replace('.',',');
							c4.style.textAlign = "right";
							c4.style.padding  =	"0 8px 0 0";
						}
						document.getElementById('tabelaLancFinanceiroTotalValor').innerHTML	=	formata_float(Arredonda(TotalValor,2),2).replace('.',',');	
						document.getElementById('tabelaLancFinanceiroTotal').innerHTML		=	"Total: "+i;	
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
	
	function listarParametro(IdLocalRecebimento,Erro){
		if(IdLocalRecebimento == '' || IdLocalRecebimento == undefined){
			IdLocalRecebimento = 0;
		}
		while(document.getElementById('tabelaParametro').rows.length > 0){
			document.getElementById('tabelaParametro').deleteRow(0);
		}		
		
		var tam, linha, c0, c1, c2, c3, c4, c5;
		
		if(IdLocalRecebimento == ''){
			IdLocalRecebimento = 0;
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
	    url = "xml/parametro_recebimento.php?IdLocalRecebimento="+IdLocalRecebimento;
 		xmlhttp.open("GET", url,true);
		xmlhttp.onreadystatechange = function(){ 

			if(xmlhttp.readyState == 4){ 
				if(xmlhttp.status == 200){
					if(xmlhttp.responseText == 'false'){		
						// Fim de Carregando
						document.getElementById('tabelaParametro').style.display	=	'none';
					}else{
						document.getElementById('tabelaParametro').style.display	=	'block';
						var DescricaoParametroRecebimento, Obrigatorio, IdParametroRecebimento, color,cont=0,tab=15,tabindex=0,temp=4;
						for(var i=0; i<xmlhttp.responseXML.getElementsByTagName("IdParametroRecebimento").length; i++){
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoParametroRecebimento")[i]; 
							nameTextNode = nameNode.childNodes[0];
							DescricaoParametroRecebimento = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("Obrigatorio")[i]; 
							nameTextNode = nameNode.childNodes[0];
							Obrigatorio = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("IdParametroRecebimento")[i]; 
							nameTextNode = nameNode.childNodes[0];
							IdParametroRecebimento = nameTextNode.nodeValue;
							
							tam 	 = document.getElementById('tabelaParametro').rows.length;
							
							padding  = "";
							
							if(i%4 == 0){
								linha	 = document.getElementById('tabelaParametro').insertRow(tam);
								if(tabindex == 0){
									tabindex = tab + 4;
								}else{
									tabindex = tab + 4 + i;
								}
							}else{
								tabindex = tabindex - 1;
							}
							
							if(cont > 1) cont = 0;
							
							if(calculate(i) == true){
								if(cont == 0){
									padding	=	'padding-left:24px';
								}else{
									padding	=	'padding-left:10px';
								}
								cont++;
							}else{
								padding	=	'padding-left:10px';
							}
							
							if((i+1)==xmlhttp.responseXML.getElementsByTagName("IdParametroRecebimento").length && calculate(i) == false) padding	=	'padding-left:24px';
							
							if(Obrigatorio == 1){
								color = "#C10000";
							}else{
								color = "#000000";
							}
							
							if(document.formulario.IdStatus.value == 2){
								readOnly	=	"readOnly";
							}else{
								readOnly	=	"";
							}
							
							linha.accessKey = IdParametroRecebimento; 

							c0	= linha.insertCell(0);
							c0.innerHTML = "<p style='margin:0; padding-bottom:1px; "+padding+"'><B style='color:"+color+";'>"+DescricaoParametroRecebimento+"</B></p><p style='padding-bottom:4px; margin:0; "+padding+"'><input type='text' name='Valor^"+IdParametroRecebimento+"' value='' style='width:190px' maxlength='255' onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out')\" tabindex="+tabindex+" "+readOnly+"><input type='hidden' name='Obrigatorio^"+IdParametroRecebimento+"' value='"+Obrigatorio+"'></p>";
						}
						document.formulario.QuantParametros.value = i;
						if(document.formulario.Erro.value != ''){
							scrollWindow('bottom');
						}
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
	function calculate(number) {
		var num=parseInt(number);
		for (var i=2;i<num;i++) {
			if (num % i == 0) {
				var prime="yes";
				return false;
				break;
			}
			if (num % i != 0) var prime="no";
		}
		if (prime == "no") return true;
	}
	function listarContaReceberParametroRecebimento(IdContaReceber,IdLocalRecebimento,Erro){
		if(IdContaReceber == '' || IdContaReceber == undefined){
			IdContaReceber = 0;
		}
		if(IdLocalRecebimento == '' || IdLocalRecebimento == undefined){
			IdLocalRecebimento = 0;
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
	    
		var posInicial,posFinal;
		for(i=0;i<document.formulario.length;i++){
			if(document.formulario[i].name != undefined){
				if(document.formulario[i].name == 'ValorReceber'){
					posInicial = i;
				}
			}
		}

		
		url = "xml/conta_receber_recebimento_parametro.php?IdContaReceber="+IdContaReceber+"&IdLocalRecebimento="+IdLocalRecebimento;
 		xmlhttp.open("GET", url,true);
		xmlhttp.onreadystatechange = function(){ 

			if(xmlhttp.readyState == 4){ 
				if(xmlhttp.status == 200){		
					if(xmlhttp.responseText == 'false'){
						document.getElementById('cp_parametros').style.display				=	'none';	
						
						for(ii=posInicial;ii<posFinal;ii++){
							if(document.formulario[ii].name != undefined){
								document.formulario[ii].value	=	"";
							}
						}
					}else{
						document.getElementById('cp_parametros').style.display				=	'block';	
						
						for(var i=0; i<xmlhttp.responseXML.getElementsByTagName("IdParametroRecebimento").length; i++){
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("IdParametroRecebimento")[i]; 
							nameTextNode = nameNode.childNodes[0];
							IdParametroRecebimento = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("ValorParametro")[i]; 
							nameTextNode = nameNode.childNodes[0];
							ValorParametro = nameTextNode.nodeValue;
							
							for(ii=posInicial;ii<document.formulario.length;ii++){
								if(document.formulario[ii].name != undefined){
									if(document.formulario[ii].name == 'Valor^'+IdParametroRecebimento){
										document.formulario[ii].value	=	ValorParametro;
										break;
									}
								}
							}	
						}
					}	
					
				}	
			} 
			return true;
		}
		xmlhttp.send(null);
	}

