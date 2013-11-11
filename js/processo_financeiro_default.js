	function janela_busca_processo_financeiro(){
		janelas('../administrativo/busca_processo_financeiro.php',360,283,250,100,'');
	}
	function busca_processo_financeiro(IdProcessoFinanceiro,Erro,Local){
		if(IdProcessoFinanceiro == ''){
			IdProcessoFinanceiro = 0;
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
	    
	    url = "xml/processo_financeiro.php?IdProcessoFinanceiro="+IdProcessoFinanceiro;
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
							case 'ProcessoFinanceiro':
								document.formulario.IdProcessoFinanceiro.focus();
							
								
							
								document.formulario.IdProcessoFinanceiro.value			= '';
								document.formulario.MesReferencia.value 				= '';
								document.formulario.MesVencimento.value 				= '';
								document.formulario.MenorVencimento.value 				= '';
								document.formulario.IdStatus.value 						= '';
								document.formulario.Filtro_IdPessoa.value	 			= '';
								document.formulario.Filtro_IdLocalCobranca.value 		= '';
								document.formulario.Filtro_FormaAvisoCobranca[0].selected 	= true;
								document.formulario.Filtro_IdStatusContrato[0].selected 	= true;
								document.formulario.EmailEnviado.value 					= '';
								document.formulario.Filtro_TipoLancamento.value 		= '';
								document.formulario.Filtro_TipoLancamento.value 		= '';
								document.formulario.Filtro_IdContrato.value 			= '';
								document.formulario.Filtro_IdPaisEstadoCidade.value 	= '';
								document.formulario.Filtro_IdServico.value 				= '';
								document.formulario.IdTipoLocalCobranca.value 			= '';
								document.formulario.DataCriacao.value 					= '';
								document.formulario.LoginCriacao.value 					= '';
								document.formulario.DataAlteracao.value 				= '';
								document.formulario.LoginAlteracao.value				= '';	
								document.formulario.DataProcessamento.value 			= '';
								document.formulario.LoginProcessamento.value 			= '';
								document.formulario.DataConfirmacao.value 				= '';
								document.formulario.LoginConfirmacao.value				= '';
								document.formulario.LogProcessamento.value 				= '';
								document.formulario.Acao.value 							= 'inserir';
								
								addParmUrl("marProcessoFinanceiro","IdProcessoFinanceiro","");
								addParmUrl("marLancamentoFinanceiro","IdProcessoFinanceiro","");
								addParmUrl("marContasReceber","IdProcessoFinanceiro","");
								addParmUrl("marPessoaFormaCobranca","IdProcessoFinanceiro","");
								
								while(document.getElementById('tabelaPessoa').rows.length > 2){
									document.getElementById('tabelaPessoa').deleteRow(1);
								}
								while(document.getElementById('tabelaContrato').rows.length > 2){
									document.getElementById('tabelaContrato').deleteRow(1);
								}
								while(document.getElementById('tabelaCidade').rows.length > 2){
									document.getElementById('tabelaCidade').deleteRow(1);
								}
								while(document.getElementById('tabelaLancFinanceiro').rows.length > 2){
									document.getElementById('tabelaLancFinanceiro').deleteRow(1);
								}
								while(document.getElementById('tabelaServico').rows.length > 2){
									document.getElementById('tabelaServico').deleteRow(1);
								}
								while(document.formulario.MenorVencimento.options.length > 0){
									document.formulario.MenorVencimento.options[0] = null;
								}
			
								addOption(document.formulario.MenorVencimento,"","");
								
								document.getElementById('cp_Status').style.display			= 'none';
								
								document.getElementById('Filtros').style.display			= 'block';
								document.getElementById('tabelaPessoa').style.display		= 'block';
								document.getElementById('titTabelaPessoa').style.display	= 'block';								
								document.getElementById('totaltabelaPessoa').innerHTML		= "Total: 0";
								
								document.getElementById('tabelaContrato').style.display		= 'block';
								document.getElementById('titTabelaContrato').style.display	= 'block';								
								document.getElementById('totaltabelaContrato').innerHTML	= "Total: 0";
								
								document.getElementById('tabelaCidade').style.display		= 'block';
								document.getElementById('titTabelaCidade').style.display	= 'block';	
								document.getElementById('totaltabelaCidade').innerHTML		= "Total: 0";
								
								document.getElementById('tabelaServico').style.display		= 'block';
								document.getElementById('titTabelaServico').style.display	= 'block';	
								document.getElementById('totaltabelaServico').innerHTML		= "Total: 0";
								
								document.getElementById('tabelaLancFinanceiroTotalValor').innerHTML	=	"0,00";	
								document.getElementById('tabelaLancFinanceiroTotal').innerHTML		=	"Total: 0";
								
								statusInicial();
								verificaAcao();
								break;
							case 'Etiqueta':
								document.formulario.IdProcessoFinanceiro.focus();
							
								document.formulario.IdProcessoFinanceiro.value			= '';
								document.formulario.MesReferencia.value 				= '';
								document.formulario.MesVencimento.value 				= '';
								document.formulario.MenorVencimento.value 				= '';
								document.formulario.Status.value 						= '';
								document.formulario.DescFiltro_IdLocalCobranca.value 	= '';
								break;
							case 'LancamentoFinanceiro':
								document.formulario.IdProcessoFinanceiro.value			= '';
								document.formulario.MesReferencia.value 				= '';
								document.formulario.MesVencimento.value 				= '';
								document.formulario.MenorVencimento.value 				= '';
								document.formulario.Filtro_TipoLancamento.value 		= '';
								document.formulario.Filtro_IdLocalCobranca.value 		= '';
								
								document.getElementById('cpProcessoFinanceiro').style.display	= 'none';
								break;								
						}
						
						// Fim de Carregando
						carregando(false);
					}else{
						nameNode = xmlhttp.responseXML.getElementsByTagName("IdProcessoFinanceiro")[0]; 
						nameTextNode = nameNode.childNodes[0];
						IdProcessoFinanceiro = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("MesVencimento")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var MesVencimento = nameTextNode.nodeValue;						
				
						nameNode = xmlhttp.responseXML.getElementsByTagName("MesReferencia")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var MesReferencia = nameTextNode.nodeValue;		
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("MenorVencimento")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var MenorVencimento = nameTextNode.nodeValue;		
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("IdStatus")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var IdStatus = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("Status")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var Status = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("Filtro_IdPessoa")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var Filtro_IdPessoa = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("Filtro_IdLocalCobranca")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var Filtro_IdLocalCobranca = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("Filtro_TipoLancamento")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var Filtro_TipoLancamento = nameTextNode.nodeValue;
						
						switch(Local){
							case 'ProcessoFinanceiro':
								nameNode = xmlhttp.responseXML.getElementsByTagName("IdTipoLocalCobranca")[0]; 
								nameTextNode = nameNode.childNodes[0];
								var IdTipoLocalCobranca = nameTextNode.nodeValue;
								
								nameNode = xmlhttp.responseXML.getElementsByTagName("Filtro_IdPaisEstadoCidade")[0]; 
								nameTextNode = nameNode.childNodes[0];
								var Filtro_IdPaisEstadoCidade = nameTextNode.nodeValue;
						
								nameNode = xmlhttp.responseXML.getElementsByTagName("LogProcessamento")[0]; 
								nameTextNode = nameNode.childNodes[0];
								var LogProcessamento = nameTextNode.nodeValue;
								
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
								
								nameNode = xmlhttp.responseXML.getElementsByTagName("DataProcessamento")[0]; 
								nameTextNode = nameNode.childNodes[0];
								var DataProcessamento = nameTextNode.nodeValue;
								
								nameNode = xmlhttp.responseXML.getElementsByTagName("LoginProcessamento")[0]; 
								nameTextNode = nameNode.childNodes[0];
								var LoginProcessamento = nameTextNode.nodeValue;
								
								nameNode = xmlhttp.responseXML.getElementsByTagName("DataConfirmacao")[0]; 
								nameTextNode = nameNode.childNodes[0];
								var DataConfirmacao = nameTextNode.nodeValue;
								
								nameNode = xmlhttp.responseXML.getElementsByTagName("LoginConfirmacao")[0]; 
								nameTextNode = nameNode.childNodes[0];
								var LoginConfirmacao = nameTextNode.nodeValue;
								
								nameNode = xmlhttp.responseXML.getElementsByTagName("EmailEnviado")[0]; 
								nameTextNode = nameNode.childNodes[0];
								var EmailEnviado = nameTextNode.nodeValue;	
								
								nameNode = xmlhttp.responseXML.getElementsByTagName("Filtro_IdContrato")[0]; 
								nameTextNode = nameNode.childNodes[0];
								var Filtro_IdContrato = nameTextNode.nodeValue;	
								
								nameNode = xmlhttp.responseXML.getElementsByTagName("Filtro_IdStatusContrato")[0]; 
								nameTextNode = nameNode.childNodes[0];
								var Filtro_IdStatusContrato = nameTextNode.nodeValue;	
								
								nameNode = xmlhttp.responseXML.getElementsByTagName("Filtro_FormaAvisoCobranca")[0]; 
								nameTextNode = nameNode.childNodes[0];
								var Filtro_FormaAvisoCobranca = nameTextNode.nodeValue;	
								
								nameNode = xmlhttp.responseXML.getElementsByTagName("Filtro_IdServico")[0]; 
								nameTextNode = nameNode.childNodes[0];
								var Filtro_IdServico = nameTextNode.nodeValue;	
								
								nameNode = xmlhttp.responseXML.getElementsByTagName("Cor")[0]; 
								nameTextNode = nameNode.childNodes[0];
								var Cor = nameTextNode.nodeValue;	
								
								while(document.getElementById('tabelaPessoa').rows.length > 2){
									document.getElementById('tabelaPessoa').deleteRow(1);
								}
								while(document.getElementById('tabelaContrato').rows.length > 2){
									document.getElementById('tabelaContrato').deleteRow(1);
								}
								while(document.getElementById('tabelaCidade').rows.length > 2){
									document.getElementById('tabelaCidade').deleteRow(1);
								}
								while(document.getElementById('tabelaServico').rows.length > 2){
									document.getElementById('tabelaServico').deleteRow(1);
								}							
								while(document.getElementById('tabelaLancFinanceiro').rows.length > 2){
									document.getElementById('tabelaLancFinanceiro').deleteRow(1);
								}
								
								document.formulario.IdProcessoFinanceiro.value			= IdProcessoFinanceiro;
								document.formulario.MesReferencia.value 				= MesReferencia;
								document.formulario.MesVencimento.value 				= MesVencimento;
								document.formulario.MenorVencimento.value 				= MenorVencimento;
								document.formulario.IdStatus.value 						= IdStatus;
								document.formulario.Filtro_IdLocalCobranca.value		= Filtro_IdLocalCobranca;
								document.formulario.Filtro_IdPessoa.value				= Filtro_IdPessoa;
								document.formulario.Filtro_TipoLancamento.value			= Filtro_TipoLancamento;								
								document.formulario.DataCriacao.value 					= dateFormat(DataCriacao);
								document.formulario.LoginCriacao.value 					= LoginCriacao;
								document.formulario.DataAlteracao.value 				= dateFormat(DataAlteracao);
								document.formulario.LoginAlteracao.value				= LoginAlteracao;
								document.formulario.DataProcessamento.value 			= dateFormat(DataProcessamento);
								document.formulario.LoginProcessamento.value 			= LoginProcessamento;
								document.formulario.DataConfirmacao.value 				= dateFormat(DataConfirmacao);
								document.formulario.LoginConfirmacao.value				= LoginConfirmacao;
								document.formulario.LogProcessamento.value 				= LogProcessamento;
								document.formulario.EmailEnviado.value 					= EmailEnviado;
								document.formulario.Filtro_IdContrato.value 			= Filtro_IdContrato;
								document.formulario.Filtro_IdStatusContrato.value 		= Filtro_IdStatusContrato;
								document.formulario.Filtro_FormaAvisoCobranca.value 	= Filtro_FormaAvisoCobranca;
								document.formulario.Filtro_IdPaisEstadoCidade.value 	= Filtro_IdPaisEstadoCidade;
								document.formulario.Filtro_IdServico.value 				= Filtro_IdServico;
								document.formulario.IdTipoLocalCobranca.value 			= IdTipoLocalCobranca;
								
								buscaVencimento(MesReferencia,MenorVencimento);
								
								addParmUrl("marProcessoFinanceiro","IdProcessoFinanceiro",IdProcessoFinanceiro);
								addParmUrl("marLancamentoFinanceiro","IdProcessoFinanceiro",IdProcessoFinanceiro);
								addParmUrl("marContasReceber","IdProcessoFinanceiro",IdProcessoFinanceiro);
								addParmUrl("marPessoaFormaCobranca","IdProcessoFinanceiro",IdProcessoFinanceiro);
								
								document.getElementById('tabelaPessoa').style.display		= 'none';
								document.getElementById('titTabelaPessoa').style.display	= 'none';
								document.getElementById('tabelaContrato').style.display		= 'none';
								document.getElementById('titTabelaContrato').style.display	= 'none';
								document.getElementById('tabelaCidade').style.display		= 'none';
								document.getElementById('titTabelaCidade').style.display	= 'none';
								document.getElementById('tabelaServico').style.display		= 'none';
								document.getElementById('titTabelaServico').style.display	= 'none';
								
								//############# Filtro_IdPessoa ###########################
								if(Filtro_IdPessoa != ""){
									var temp = Filtro_IdPessoa;
									
									var tempFiltro	=	temp.split(',');
									var ii=0;
									if(Filtro_IdPessoa != ''){
										while(tempFiltro[ii] != undefined){
											busca_pessoa(tempFiltro[ii],false,'ProcessoFinanceiro','',listar);
											ii++;
										}
									}
									document.getElementById('totaltabelaPessoa').innerHTML	=	"Total: "+ii;
									
									document.getElementById('tabelaPessoa').style.display		= 'block';
									document.getElementById('titTabelaPessoa').style.display	= 'block';
								}
								
								//############# Filtro_IdContrato ###########################
								if(Filtro_IdContrato!=""){
									var temp2 = Filtro_IdContrato;
									
									var tempFiltro2	=	temp2.split(',');
									var ii2=0;
									if(Filtro_IdContrato != ''){
										while(tempFiltro2[ii2] != undefined){
											busca_contrato(tempFiltro2[ii2],false,'ProcessoFinanceiro','',listar);
											ii2++;
										}
									}
									document.getElementById('totaltabelaContrato').innerHTML	=	"Total: "+ii2;
									
									document.getElementById('tabelaContrato').style.display		= 'block';
									document.getElementById('titTabelaContrato').style.display	= 'block';
								}
								
								//############# Filtro_IdPaisEstadoCidade ###########################
								if(Filtro_IdPaisEstadoCidade != ""){
									var temp3 = Filtro_IdPaisEstadoCidade;
									
									var tempFiltro3	=	temp3.split('^');
									var ii3=0;
									if(Filtro_IdPaisEstadoCidade != ''){
										while(tempFiltro3[ii3] != undefined){
											tempFiltro3[ii3]	=	tempFiltro3[ii3].split(',')
											adicionar_cidade(tempFiltro3[ii3][0],tempFiltro3[ii3][1],tempFiltro3[ii3][2],listar);
											ii3++;
										}
									}
									document.getElementById('totaltabelaCidade').innerHTML	=	"Total: "+ii3;
									
									document.getElementById('tabelaCidade').style.display		= 'block';
									document.getElementById('titTabelaCidade').style.display	= 'block';
								}
								
								//############# Filtro_IdServico ###########################
								if(Filtro_IdServico!=""){
									var temp2 = Filtro_IdServico;
									
									var tempFiltro2	=	temp2.split(',');
									var ii2=0;
									if(Filtro_IdServico != ''){
										while(tempFiltro2[ii2] != undefined){
											busca_servico(tempFiltro2[ii2],false,'ProcessoFinanceiro','',listar);
											ii2++;
										}
									}
									document.getElementById('totaltabelaServico').innerHTML	=	"Total: "+ii2;
									
									document.getElementById('tabelaServico').style.display		= 'block';
									document.getElementById('titTabelaServico').style.display	= 'block';
								}
								
								document.formulario.bt_imprimir.value 		= 'Gerar Boletos';
								document.formulario.bt_imprimir.style.width	= '100px';
								
								switch(IdStatus){
									case '1':
										document.formulario.Acao.value 		= 'alterar';
										
										document.getElementById('Filtros').style.display			= 'block';	
										document.getElementById('tabelaPessoa').style.display		= 'block';
										document.getElementById('titTabelaPessoa').style.display	= 'block';
										document.getElementById('tabelaContrato').style.display		= 'block';
										document.getElementById('titTabelaContrato').style.display	= 'block';
										document.getElementById('tabelaCidade').style.display		= 'block';
										document.getElementById('titTabelaCidade').style.display	= 'block';
										document.getElementById('tabelaServico').style.display		= 'block';
										document.getElementById('titTabelaServico').style.display	= 'block';
										break;
									case '2':
										if(Filtro_IdPessoa == '' && Filtro_IdContrato == '' && Filtro_IdPaisEstadoCidade == '' && Filtro_IdServico == ''){
											document.getElementById('Filtros').style.display			= 'none';	
										}else{
											document.getElementById('Filtros').style.display			= 'block';	
										}
										
										document.formulario.Acao.value 		= 'confirmar';
										break;
									case '3': //Confirmado
										if(Filtro_IdPessoa == '' && Filtro_IdContrato == '' && Filtro_IdPaisEstadoCidade == '' && Filtro_IdServico == ''){
											document.getElementById('Filtros').style.display			= 'none';	
										}else{
											document.getElementById('Filtros').style.display			= 'block';	
										}
										
										switch(IdTipoLocalCobranca){
											case '1': //Recebimento Manual
												document.formulario.bt_imprimir.value 		= 'Gerar Demonstrativo';
												document.formulario.bt_imprimir.style.width	= '140px';
												break;
											case '2': //Boleto sem Registro
												document.formulario.bt_imprimir.value 		= 'Gerar Boletos';
												document.formulario.bt_imprimir.style.width	= '100px';
												break;
											case '3': //Debito em Conta
												document.formulario.bt_imprimir.value 		= 'Gerar Arquivo Remessa';
												document.formulario.bt_imprimir.style.width	= '160px';
												break;
										}
										
										document.formulario.Acao.value 		= 'gerado';
										break;
								}
								
								document.getElementById('cp_Status').innerHTML			= Status;
								document.getElementById('cp_Status').style.display		= 'block';
								document.getElementById('cp_Status').style.color		= Cor;
								
								document.formulario.Visualizar.value = '';
								document.formulario.bt_visualizar.value = 'Visualizar';
			
								document.getElementById('cp_lancamentos_financeiros').style.display	=	'none';						
								document.getElementById('tabelaLancFinanceiroTotalValor').innerHTML	=	"0,00";	
								document.getElementById('tabelaLancFinanceiroTotal').innerHTML		=	"Total: 0";
								
								verificaAcao();
								break;
							case 'Etiqueta':
								nameNode = xmlhttp.responseXML.getElementsByTagName("DescFiltro_IdLocalCobranca")[0]; 
								nameTextNode = nameNode.childNodes[0];
								var DescFiltro_IdLocalCobranca = nameTextNode.nodeValue;
								
								document.formulario.IdProcessoFinanceiro.value			= IdProcessoFinanceiro;
								document.formulario.MesReferencia.value 				= MesReferencia;
								document.formulario.MesVencimento.value 				= MesVencimento;
								document.formulario.MenorVencimento.value 				= MenorVencimento;
								document.formulario.Status.value 						= Status;
								document.formulario.DescFiltro_IdLocalCobranca.value	= DescFiltro_IdLocalCobranca;
								document.formulario.Filtro_IdPessoa.value				= Filtro_IdPessoa;
								break;
							case 'LancamentoFinanceiro':
								document.formulario.IdProcessoFinanceiro.value			= IdProcessoFinanceiro;
								document.formulario.MesReferencia.value 				= MesReferencia;
								document.formulario.MesVencimento.value 				= MesVencimento;
								document.formulario.MenorVencimento.value 				= MenorVencimento;
								document.formulario.Filtro_IdLocalCobranca.value		= Filtro_IdLocalCobranca;
								document.formulario.Filtro_TipoLancamento.value			= Filtro_TipoLancamento;
								break;
						}
					}
					if(document.getElementById("quadroBuscaProcessoFinanceiro") != null){
						if(document.getElementById("quadroBuscaProcessoFinanceiro").style.display	==	"block"){
							document.getElementById("quadroBuscaProcessoFinanceiro").style.display = "none";
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
	function remover_filtro_contrato(IdContrato){
		for(var i=0; i<document.getElementById('tabelaContrato').rows.length; i++){
			if(IdContrato == document.getElementById('tabelaContrato').rows[i].accessKey){
				document.getElementById('tabelaContrato').deleteRow(i);
				tableMultColor('tabelaContrato');
				break;
			}
		}	
		var tempFiltro	=	document.formulario.Filtro_IdContrato.value.split(',');
		var novoFiltro  = '';
		
		var ii = 0;
		while(tempFiltro[ii] != undefined){
			if(tempFiltro[ii] != IdContrato){
				if(novoFiltro == ''){
					novoFiltro = tempFiltro[ii];
				}else{
					novoFiltro = novoFiltro + "," + tempFiltro[ii];
				}
			}
			ii=ii+1;
		}
		
		document.formulario.Filtro_IdContrato.value = novoFiltro;
		document.getElementById('totaltabelaContrato').innerHTML	=	'Total: '+(ii-1);
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
		document.getElementById('totaltabelaCidade').innerHTML	=	'Total: '+(ii-1);
	}
