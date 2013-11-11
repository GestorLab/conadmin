	function janela_busca_processo_financeiro(){
		janelas('../administrativo/busca_processo_financeiro.php',360,283,250,100,'');
	}
	function busca_processo_financeiro(IdProcessoFinanceiro,Erro,Local,ListarCampo){
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
		
		if(Local == "NotaFiscalSaida" || Local == "AdicionarMalaDireta" || Local == "MalaDireta"){
			url += "&IdStatus="+3;
		}
		
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
								
								document.formulario.IdProcessoFinanceiro.value				= '';
								document.formulario.MesReferencia.value 					= '';
								document.formulario.MesVencimento.value 					= '';
								document.formulario.MenorVencimento.value 					= '';
								document.formulario.DataNotaFiscal.value 					= '';
								document.formulario.DataNotaFiscalTemp.value 				= '';
								document.formulario.QdtLancamentos.value 					= '';
								document.formulario.QtdContaReceber.value 					= '';
								document.formulario.IdStatus.value							= '';
								document.formulario.Filtro_IdPessoa.value	 				= '';
								document.formulario.Filtro_IdLocalCobranca.value 			= '';
								document.formulario.Filtro_IdLocalCobrancaTemp.value		= '';
								document.formulario.Filtro_FormaAvisoCobranca[0].selected	= true;
								document.formulario.IdStatusContrato[0].selected			= true;
								document.formulario.VencimentoContrato[0].selected			= true;
								document.formulario.EmailEnviado.value						= '';
								document.formulario.Filtro_TipoLancamento.value				= '';
								document.formulario.Filtro_IdTipoPessoa.value				= '';
								document.formulario.Filtro_IdContrato.value					= '';
								document.formulario.Filtro_TipoContrato.value				= '';
								document.formulario.Filtro_IdPaisEstadoCidade.value			= '';
								document.formulario.Filtro_IdServico.value					= '';
								document.formulario.Filtro_IdStatusContrato.value			= '';
								document.formulario.Filtro_TipoCobranca[0].selected			= true;
								document.formulario.Filtro_IdGrupoPessoa.value				= '';
								document.formulario.Filtro_VencimentoContrato.value			= '';
								document.formulario.IdTipoLocalCobranca.value				= '';
								document.formulario.DataCriacao.value						= '';
								document.formulario.LoginCriacao.value						= '';
								document.formulario.DataAlteracao.value						= '';
								document.formulario.LoginAlteracao.value					= '';	
								document.formulario.DataProcessamento.value					= '';
								document.formulario.LoginProcessamento.value				= '';
								document.formulario.DataConfirmacao.value					= '';
								document.formulario.LoginConfirmacao.value					= '';
								document.formulario.LogProcessamento.value					= '';
								document.formulario.DataEmissao.value						= '';
								document.formulario.Acao.value								= 'inserir';
								
								document.formulario.MesReferencia.readOnly					= false;
								document.formulario.MesVencimento.readOnly					= false;
								document.formulario.MenorVencimento.disabled				= false;
								document.formulario.Filtro_IdLocalCobranca.disabled			= false;
								document.formulario.Filtro_TipoLancamento.disabled			= false;
								document.formulario.Filtro_TipoContrato.disabled			= false;
								document.formulario.Filtro_IdTipoPessoa.disabled			= false;
								document.formulario.Filtro_FormaAvisoCobranca.disabled		= false;
								document.formulario.VencimentoContrato.disabled				= false;
								document.formulario.IdStatusContrato.disabled				= false;
								document.formulario.IdPessoa.readOnly						= false;
								document.formulario.IdPais.readOnly							= false;
								document.formulario.IdEstado.readOnly						= false;
								document.formulario.IdCidade.readOnly						= false;
								document.formulario.IdServico.readOnly						= false;
								document.formulario.IdContrato.readOnly						= false;
								document.formulario.IdAgenteAutorizado.readOnly				= false;
								document.formulario.IdGrupoPessoa.readOnly					= false;
								document.formulario.bt_add_contrato.disabled				= false;
								document.formulario.bt_add_pessoa.disabled					= false;
								document.formulario.bt_add_cidade.disabled					= false;
								document.formulario.bt_add_servico.disabled					= false;
								document.formulario.bt_add_agente.disabled					= false;
								document.formulario.bt_add_status.disabled					= false;
								document.formulario.bt_add_vencimento_contrato.disabled		= false;
								document.formulario.bt_add_grupo_pessoa.disabled			= false;
								
								addParmUrl("marProcessoFinanceiro","IdProcessoFinanceiro","");
								addParmUrl("marLancamentoFinanceiro","IdProcessoFinanceiro","");
								addParmUrl("marContasReceber","IdProcessoFinanceiro","");
								addParmUrl("marPessoaFormaCobranca","IdProcessoFinanceiro","");
								addParmUrl("marPessoaFormaCobranca","filtro_limit","");
								addParmUrl("marReenvioMensagem","filtro_limit","");
								
								while(document.getElementById('tabelaVencimentoContrato').rows.length > 2){
									document.getElementById('tabelaVencimentoContrato').deleteRow(1);
								}
								
								while(document.getElementById('tabelaGrupoPessoa').rows.length > 2){
									document.getElementById('tabelaGrupoPessoa').deleteRow(1);
								}
								
								while(document.getElementById('tabelaStatus').rows.length > 2){
									document.getElementById('tabelaStatus').deleteRow(1);
								}
								
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
								
								while(document.getElementById('tabelaAgente').rows.length > 2){
									document.getElementById('tabelaAgente').deleteRow(1);
								}
								
								while(document.formulario.MenorVencimento.options.length > 0){
									document.formulario.MenorVencimento.options[0] = null;
								}
								
								addOption(document.formulario.MenorVencimento,"","");
								
								document.formulario.DataNotaFiscal.value								= '';
								document.getElementById('DataNotaFiscal').style.display					= 'none';
								document.getElementById('cpDataNotaFiscal').style.display				= 'none';
								document.getElementById('cpDataNotaFiscalIco').style.display			= 'none';
								document.getElementById('Filtros').style.display						= 'block';
								document.getElementById('cp_filtro_vencimento_contrato').style.display	= 'block';
								document.getElementById('cp_filtro_grupo_pessoa').style.display			= 'block';
								document.getElementById('cp_filtro_status').style.display				= 'block';
								document.getElementById('cp_filtro_pessoa').style.display				= 'block';
								document.getElementById('cp_filtro_contrato').style.display				= 'block';
								document.getElementById('cp_filtro_cidade').style.display				= 'block';
								document.getElementById('cp_filtro_servico').style.display				= 'block';
								document.getElementById('cp_filtro_agente').style.display				= 'block';
								document.getElementById('cp_Status').style.display						= 'none';
								document.getElementById('totaltabelaVencimentoContrato').innerHTML		= "Total: 0";
								document.getElementById('totaltabelaGrupoPessoa').innerHTML				= "Total: 0";
								document.getElementById('totaltabelaPessoa').innerHTML					= "Total: 0";
								document.getElementById('totaltabelaStatus').innerHTML					= "Total: 0";
								document.getElementById('totaltabelaContrato').innerHTML				= "Total: 0";
								document.getElementById('totaltabelaCidade').innerHTML					= "Total: 0";
								document.getElementById('totaltabelaServico').innerHTML					= "Total: 0";
								document.getElementById('totaltabelaAgente').innerHTML					= "Total: 0";
								document.getElementById('tabelaLancFinanceiroTotalValor').innerHTML		= "0,00";
								document.getElementById('tabelaLancFinanceiroTotal').innerHTML			= "Total: 0";
								
								listaLocalCobranca();
								statusInicial();
								verificaAcao();
								break;
							case 'Etiqueta':
								document.formulario.IdProcessoFinanceiro.focus();
								
								document.formulario.IdProcessoFinanceiro.value			= '';
								document.formulario.MesReferencia.value					= '';
								document.formulario.MesVencimento.value					= '';
								document.formulario.MenorVencimento.value				= '';
								document.formulario.Status.value						= '';
								document.formulario.DescFiltro_IdLocalCobranca.value	= '';
								document.formulario.Filtro_IdPessoa_Processo.value		= '';
								break;
							case 'LancamentoFinanceiro':
								document.formulario.IdProcessoFinanceiro.value			= '';
								document.formulario.MesReferencia.value 				= '';
								document.formulario.MesVencimento.value 				= '';
								document.formulario.MenorVencimento.value 				= '';
								document.formulario.Filtro_TipoLancamento.value 		= '';
								document.formulario.Filtro_IdLocalCobranca.value 		= '';
								
								if(document.getElementById('cpProcessoFinanceiro') != undefined){
									document.getElementById('cpProcessoFinanceiro').style.display	= 'none';
								}
								break;							
							case 'MalaDireta':
								document.formulario.IdProcessoFinanceiro.value		= '';
								document.formulario.MesReferencia.value				= '';
								document.formulario.MenorVencimento.value			= '';
								document.formulario.IdLocalCobranca.value			= '';
								break;							
							case 'AdicionarProcessoFinanceiro':
								document.formulario.IdProcessoFinanceiro.value			= '';
								document.formulario.MesReferenciaProcesso.value			= '';
								document.formulario.MesVencimentoProcesso.value			= '';
								document.formulario.LocalCobrancaProcesso.value			= '';
								document.formulario.QtdLancamentoProcesso.value			= '';
								document.formulario.ValorTotalProcesso.value			= '0.00';
								break;
							case 'AdicionarMalaDireta':
								document.formulario.IdProcessoFinanceiro.value		= '';
								document.formulario.MesReferencia.value				= '';
								document.formulario.MenorVencimento.value			= '';
								document.formulario.IdLocalCobranca.value			= '';
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
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("Filtro_TipoPessoa")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var Filtro_TipoPessoa = nameTextNode.nodeValue;						
						
						
						switch(Local){
							case 'ProcessoFinanceiro':
								
								nameNode = xmlhttp.responseXML.getElementsByTagName("DataNotaFiscal")[0]; 
								nameTextNode = nameNode.childNodes[0];
								var DataNotaFiscal = nameTextNode.nodeValue;
								
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
								
								nameNode = xmlhttp.responseXML.getElementsByTagName("Filtro_IdTipoContrato")[0]; 
								nameTextNode = nameNode.childNodes[0];
								var Filtro_IdTipoContrato = nameTextNode.nodeValue;	
								
								nameNode = xmlhttp.responseXML.getElementsByTagName("Filtro_IdStatusContrato")[0]; 
								nameTextNode = nameNode.childNodes[0];
								var Filtro_IdStatusContrato = nameTextNode.nodeValue;		
								
								nameNode = xmlhttp.responseXML.getElementsByTagName("Filtro_FormaAvisoCobranca")[0]; 
								nameTextNode = nameNode.childNodes[0];
								var Filtro_FormaAvisoCobranca = nameTextNode.nodeValue;	
								
								nameNode = xmlhttp.responseXML.getElementsByTagName("Filtro_IdServico")[0]; 
								nameTextNode = nameNode.childNodes[0];
								var Filtro_IdServico = nameTextNode.nodeValue;
								
								nameNode = xmlhttp.responseXML.getElementsByTagName("Filtro_IdAgenteAutorizado")[0]; 
								nameTextNode = nameNode.childNodes[0];
								var Filtro_IdAgenteAutorizado = nameTextNode.nodeValue; 
								
								nameNode = xmlhttp.responseXML.getElementsByTagName("Filtro_TipoCobranca")[0]; 
								nameTextNode = nameNode.childNodes[0];
								var Filtro_TipoCobranca = nameTextNode.nodeValue; 	
								
								nameNode = xmlhttp.responseXML.getElementsByTagName("Filtro_VencimentoContrato")[0]; 
								nameTextNode = nameNode.childNodes[0];
								var Filtro_VencimentoContrato = nameTextNode.nodeValue; 
								
								nameNode = xmlhttp.responseXML.getElementsByTagName("Filtro_IdGrupoPessoa")[0]; 
								nameTextNode = nameNode.childNodes[0];
								var Filtro_IdGrupoPessoa = nameTextNode.nodeValue; 
						
								nameNode = xmlhttp.responseXML.getElementsByTagName("QdtLancamentos")[0]; 								
								nameTextNode = nameNode.childNodes[0];
								var QdtLancamentos = nameTextNode.nodeValue;
								
								nameNode = xmlhttp.responseXML.getElementsByTagName("QdtContasReceber")[0]; 
								nameTextNode = nameNode.childNodes[0];
								var QdtContasReceber = nameTextNode.nodeValue;
								
								nameNode = xmlhttp.responseXML.getElementsByTagName("IdNotaFiscalTipo")[0]; 
								nameTextNode = nameNode.childNodes[0];
								var IdNotaFiscalTipo = nameTextNode.nodeValue;
								
								nameNode = xmlhttp.responseXML.getElementsByTagName("Cor")[0]; 
								nameTextNode = nameNode.childNodes[0];
								var Cor = nameTextNode.nodeValue;
								
								nameNode = xmlhttp.responseXML.getElementsByTagName("QTDProcessoFinanceiroEnviado")[0]; 
								nameTextNode = nameNode.childNodes[0];
								var QTDProcessoFinanceiroEnviado = nameTextNode.nodeValue;								
								
								
								while(document.getElementById('tabelaVencimentoContrato').rows.length > 2){
									document.getElementById('tabelaVencimentoContrato').deleteRow(1);
								}															
								while(document.getElementById('tabelaStatus').rows.length > 2){
									document.getElementById('tabelaStatus').deleteRow(1);
								}
								while(document.getElementById('tabelaGrupoPessoa').rows.length > 2){
									document.getElementById('tabelaGrupoPessoa').deleteRow(1);
								}
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
								while(document.getElementById('tabelaAgente').rows.length > 2){
									document.getElementById('tabelaAgente').deleteRow(1);
								}
								
								document.getElementById('totaltabelaVencimentoContrato').innerHTML		= "Total: 0";							
								document.getElementById('totaltabelaGrupoPessoa').innerHTML				= "Total: 0";																				
								document.getElementById('totaltabelaPessoa').innerHTML					= "Total: 0";							
								document.getElementById('totaltabelaStatus').innerHTML					= "Total: 0";							
								document.getElementById('totaltabelaContrato').innerHTML				= "Total: 0";
								document.getElementById('totaltabelaCidade').innerHTML					= "Total: 0";
								document.getElementById('totaltabelaServico').innerHTML					= "Total: 0";
								document.getElementById('totaltabelaAgente').innerHTML					= "Total: 0";
								document.getElementById("tabelaLancFinanceiroTotalValor").innerHTML		= "0,00";
								document.getElementById('tabelaLancFinanceiroTotal').innerHTML			= "Total: 0";
												
								document.formulario.IdProcessoFinanceiro.value			= IdProcessoFinanceiro;
								document.formulario.MesReferencia.value 				= MesReferencia;
								document.formulario.MesVencimento.value 				= MesVencimento;
								document.formulario.MenorVencimento.value 				= MenorVencimento;
								document.formulario.DataNotaFiscal.value 				= dateFormat(DataNotaFiscal);
								document.formulario.DataNotaFiscalTemp.value 			= dateFormat(DataNotaFiscal);
								document.formulario.IdStatus.value 						= IdStatus;
								document.formulario.Filtro_IdLocalCobranca.value		= Filtro_IdLocalCobranca;
								document.formulario.Filtro_IdPessoa.value				= Filtro_IdPessoa;
								document.formulario.Filtro_IdContrato.value				= Filtro_IdContrato;
								document.formulario.Filtro_TipoLancamento.value			= Filtro_TipoLancamento;
								document.formulario.Filtro_IdTipoPessoa.value			= Filtro_TipoPessoa;
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
								document.formulario.Filtro_TipoContrato.value 			= Filtro_IdTipoContrato;
								document.formulario.Filtro_FormaAvisoCobranca.value 	= Filtro_FormaAvisoCobranca;
								document.formulario.Filtro_IdPaisEstadoCidade.value 	= Filtro_IdPaisEstadoCidade;
								document.formulario.Filtro_IdServico.value 				= Filtro_IdServico;
								document.formulario.Filtro_IdStatusContrato.value 		= Filtro_IdStatusContrato;
								document.formulario.Filtro_IdAgenteAutorizado.value 	= Filtro_IdAgenteAutorizado;
								document.formulario.Filtro_TipoCobranca.value 			= Filtro_TipoCobranca;
								document.formulario.Filtro_VencimentoContrato.value 	= Filtro_VencimentoContrato;
								document.formulario.Filtro_IdGrupoPessoa.value 			= Filtro_IdGrupoPessoa;
								document.formulario.QdtLancamentos.value 				= QdtLancamentos;
								document.formulario.QtdContaReceber.value 				= QdtContasReceber;
								document.formulario.IdStatusContrato[0].selected		= true;
								document.formulario.IdTipoLocalCobranca.value 			= IdTipoLocalCobranca;							
								
								if(IdNotaFiscalTipo != ''){																															
									document.getElementById('DataNotaFiscal').style.display 		= 'block';																								
									document.getElementById('cpDataNotaFiscal').style.display 		= 'block';
									document.getElementById('cpDataNotaFiscalIco').style.display 	= 'block';																	
								}else{
									document.formulario.DataNotaFiscal.value 						= ''; 								
								
									document.getElementById('DataNotaFiscal').style.display 		= 'none';
									document.getElementById('cpDataNotaFiscal').style.display 		= 'none';					
									document.getElementById('cpDataNotaFiscalIco').style.display 	= 'none';
								}
								
								buscaVencimento(MesReferencia,MenorVencimento);
								Temporizador();
								
								addParmUrl("marProcessoFinanceiro","IdProcessoFinanceiro",IdProcessoFinanceiro);
								addParmUrl("marLancamentoFinanceiro","IdProcessoFinanceiro",IdProcessoFinanceiro);
								addParmUrl("marContasReceber","IdProcessoFinanceiro",IdProcessoFinanceiro);
								addParmUrl("marPessoaFormaCobranca","IdProcessoFinanceiro",IdProcessoFinanceiro);
								addParmUrl("marPessoaFormaCobranca","filtro_limit",QdtContasReceber);
								addParmUrl("marContasReceber","filtro_limit",QdtLancamentos);
								addParmUrl("marReenvioMensagem","IdProcessoFinanceiro",IdProcessoFinanceiro);
								addParmUrl("marReenvioMensagem","filtro_limit",QTDProcessoFinanceiroEnviado);
								
								document.getElementById('cp_filtro_vencimento_contrato').style.display	= 'none';
								document.getElementById('cp_filtro_grupo_pessoa').style.display			= 'none';
								document.getElementById('cp_filtro_status').style.display				= 'none';
								document.getElementById('cp_filtro_pessoa').style.display				= 'none';
								document.getElementById('cp_filtro_contrato').style.display				= 'none';
								document.getElementById('cp_filtro_cidade').style.display				= 'none';
								document.getElementById('cp_filtro_servico').style.display				= 'none';
								document.getElementById('cp_filtro_agente').style.display				= 'none';
								
								//############# Filtro_IdStatusContrato ###########################
								if(Filtro_IdStatusContrato != ""){
									var temp = Filtro_IdStatusContrato;
									
									var tempFiltro	=	temp.split(',');
									var ii=0;
									
									while(tempFiltro[ii] != undefined){
										addStatus(tempFiltro[ii],'listar');
										ii++;
									}
									
									document.getElementById('totaltabelaStatus').innerHTML	=	"Total: "+ii;								
									document.getElementById('cp_filtro_status').style.display		= 'block';									
								}
								
								//############# Filtro_VencimentoContrato ###########################
								if(Filtro_VencimentoContrato != ""){
									var temp = Filtro_VencimentoContrato;
									
									var tempFiltro	=	temp.split(',');
									var ii=0;
									
									while(tempFiltro[ii] != undefined){
										addVencimentoContrato(tempFiltro[ii],'listar');
										ii++;
									}
									
									document.getElementById('totaltabelaVencimentoContrato').innerHTML			= "Total: "+ii;																	
									document.getElementById('cp_filtro_vencimento_contrato').style.display	= 'block';
								
								}
								
								//############# Filtro_IdGrupoPessoa ###########################
								if(Filtro_IdGrupoPessoa != ""){
									
									var temp = Filtro_IdGrupoPessoa;
									
									var tempFiltro	=	temp.split(',');
									var ii=0;
									
									while(tempFiltro[ii] != undefined){
										busca_grupo_pessoa(tempFiltro[ii],false,'AdicionarProcessoFinanceiro',listar);
										ii++;
									}
									
									document.getElementById('totaltabelaGrupoPessoa').innerHTML			= "Total: "+ii;									
									document.getElementById('cp_filtro_grupo_pessoa').style.display		= 'block';
								}
								
								//############# Filtro_IdPessoa ###########################
								if(Filtro_IdPessoa != ""){
									
									var temp = Filtro_IdPessoa;
									
									var tempFiltro	=	temp.split(',');
									var ii=0;
									
									while(tempFiltro[ii] != undefined){
										busca_pessoa(tempFiltro[ii],false,'AdicionarProcessoFinanceiro','',listar);
										ii++;
									}
									
									document.getElementById('totaltabelaPessoa').innerHTML	=	"Total: "+ii;
									
									document.getElementById('cp_filtro_pessoa').style.display		= 'block';
								}
								
								//############# Filtro_IdContrato ###########################
								if(Filtro_IdContrato!=""){
									var temp2 = Filtro_IdContrato;
									
									var tempFiltro2	=	temp2.split(',');
									var ii2=0;
								
									while(tempFiltro2[ii2] != undefined){
										busca_contrato(tempFiltro2[ii2],false,'AdicionarContrato','',listar);
										ii2++;
									}
								
									document.getElementById('totaltabelaContrato').innerHTML	=	"Total: "+ii2;
									
									document.getElementById('cp_filtro_contrato').style.display		= 'block';
								}
								
								//############# Filtro_IdPaisEstadoCidade ###########################
								if(Filtro_IdPaisEstadoCidade != ""){
									var temp3 = Filtro_IdPaisEstadoCidade;
									
									var tempFiltro3	=	temp3.split('^');
									var ii3=0;
									
									while(tempFiltro3[ii3] != undefined){
										tempFiltro3[ii3]	=	tempFiltro3[ii3].split(',')
										adicionar_cidade(tempFiltro3[ii3][0],tempFiltro3[ii3][1],tempFiltro3[ii3][2],listar);
										ii3++;
									}
									
									document.getElementById('totaltabelaCidade').innerHTML	=	"Total: "+ii3;
									
									document.getElementById('cp_filtro_cidade').style.display		= 'block';
								}
								
								//############# Filtro_IdServico ###########################
								if(Filtro_IdServico!=""){
									var temp2 = Filtro_IdServico;
									
									var tempFiltro2	=	temp2.split(',');
									var ii2=0;

									while(tempFiltro2[ii2] != undefined){
										busca_servico(tempFiltro2[ii2],false,'AdicionarServico','',listar);
										ii2++;
									}
									
									document.getElementById('totaltabelaServico').innerHTML	=	"Total: "+ii2;
									
									document.getElementById('cp_filtro_servico').style.display		= 'block';
								}
								
								//############# Filtro_IdAgenteAutorizado ###########################
								if(Filtro_IdAgenteAutorizado != ""){									
									var temp4 = Filtro_IdAgenteAutorizado;
																		
									var tempFiltro4	=	temp4.split(',');
									
									var ii=0;																	
									while(tempFiltro4[ii] != undefined){										
										busca_agente(tempFiltro4[ii],false,'AdicionarProcessoFinanceiro',listar);
										ii++;
									}
									
									document.getElementById('totaltabelaAgente').innerHTML	=	"Total: "+ii;
									
									document.getElementById('cp_filtro_agente').style.display		= 'block';
								}
								
								document.formulario.bt_imprimir.value 		= 'Imprimir Boletos';
								document.formulario.bt_imprimir.style.width	= '120px';
								
								switch(IdStatus){
									case '1':
										document.formulario.Acao.value 		= 'alterar';
										
										document.getElementById('Filtros').style.display				= 'block';
										
										if(Filtro_TipoLancamento == 1 || Filtro_TipoLancamento == ''){
											document.getElementById('cp_filtro_vencimento_contrato').style.display	= 'block';
											document.getElementById('cp_filtro_status').style.display				= 'block';
										}	
										document.getElementById('cp_filtro_grupo_pessoa').style.display	= 'block';
										document.getElementById('cp_filtro_pessoa').style.display		= 'block';
										document.getElementById('cp_filtro_contrato').style.display		= 'block';
										document.getElementById('cp_filtro_cidade').style.display		= 'block';
										document.getElementById('cp_filtro_servico').style.display		= 'block';
										document.getElementById('cp_filtro_agente').style.display		= 'block';
										break;
									case '2':
										if(Filtro_IdStatusContrato == '' && Filtro_VencimentoContrato == '' && Filtro_IdGrupoPessoa == '' && Filtro_IdPessoa == '' && Filtro_IdContrato == '' && Filtro_IdPaisEstadoCidade == '' && Filtro_IdServico == '' && Filtro_IdAgenteAutorizado == ''){
											document.getElementById('Filtros').style.display			= 'none';	
										}else{
											document.getElementById('Filtros').style.display			= 'block';	
										}
										
										document.formulario.MesReferencia.readOnly	 	       	= true;
										document.formulario.MesVencimento.readOnly		       	= true;
										document.formulario.MenorVencimento.disabled	       	= true;
										document.formulario.Filtro_IdLocalCobranca.disabled    	= true;
										document.formulario.Filtro_TipoLancamento.disabled     	= true;
										document.formulario.Filtro_TipoContrato.disabled     	= true;
										document.formulario.Filtro_TipoCobranca.disabled     	= true;
										document.formulario.Filtro_IdTipoPessoa.disabled       	= true;
										document.formulario.Filtro_FormaAvisoCobranca.disabled 	= true;										
										document.formulario.VencimentoContrato.disabled		   	= true;	
										
										document.formulario.IdStatusContrato.disabled 	 	   = true;										
										document.formulario.IdPessoa.readOnly		 	 	   = true;									
										document.formulario.IdPais.readOnly		     	       = true;
										document.formulario.IdEstado.readOnly		 	 	   = true;
										document.formulario.IdCidade.readOnly		 	       = true;
										document.formulario.IdServico.readOnly		  		   = true;
										document.formulario.IdContrato.readOnly		 	       = true;
										document.formulario.IdAgenteAutorizado.readOnly	       = true;
										document.formulario.IdGrupoPessoa.readOnly	       	   = true;
																												
										
										document.formulario.bt_add_contrato.disabled     	   	= true;
										document.formulario.bt_add_pessoa.disabled 	     	   	= true;
										document.formulario.bt_add_cidade.disabled 	     	   	= true;
										document.formulario.bt_add_servico.disabled      	   	= true;
										document.formulario.bt_add_agente.disabled 	      	   	= true;
										document.formulario.bt_add_status.disabled 	     	   	= true;
										document.formulario.bt_add_vencimento_contrato.disabled	= true;
										document.formulario.bt_add_grupo_pessoa.disabled	   	= true;
										
										document.formulario.Acao.value 		= 'confirmar';
										break;
									case '3': //Confirmado
										if(Filtro_IdStatusContrato == '' && Filtro_VencimentoContrato == '' && Filtro_IdGrupoPessoa == '' && Filtro_IdPessoa == '' && Filtro_IdContrato == '' && Filtro_IdPaisEstadoCidade == '' && Filtro_IdServico == ''){
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
												document.formulario.bt_imprimir.value 		= 'Imprimir Boletos';
												document.formulario.bt_imprimir.style.width	= '120px';
												break;
											case '3': //Debito em Conta
												document.formulario.bt_imprimir.value 		= 'Gerar Arquivo Remessa';
												document.formulario.bt_imprimir.style.width	= '160px';
												break;
										}
										
										document.formulario.MesReferencia.readOnly	 	       	= true;
										document.formulario.MesVencimento.readOnly		       	= true;
										document.formulario.MenorVencimento.disabled	       	= true;
										document.formulario.Filtro_IdLocalCobranca.disabled    	= true;
										document.formulario.Filtro_TipoContrato.disabled		= true;
										document.formulario.Filtro_TipoCobranca.disabled		= true;
										document.formulario.Filtro_TipoLancamento.disabled     	= true;
										document.formulario.Filtro_IdTipoPessoa.disabled       	= true;
										document.formulario.Filtro_FormaAvisoCobranca.disabled 	= true;										
										document.formulario.VencimentoContrato.disabled		   	= true;	
										
										document.formulario.IdStatusContrato.disabled 	 	   = true;										
										document.formulario.IdPessoa.readOnly		 	 	   = true;									
										document.formulario.IdPais.readOnly		     	       = true;
										document.formulario.IdEstado.readOnly		 	 	   = true;
										document.formulario.IdCidade.readOnly		 	       = true;
										document.formulario.IdServico.readOnly		  		   = true;
										document.formulario.IdContrato.readOnly		 	       = true;
										document.formulario.IdAgenteAutorizado.readOnly	       = true;
										document.formulario.IdGrupoPessoa.readOnly	       	   = true;
																												
										
										document.formulario.bt_add_contrato.disabled     	   = true;
										document.formulario.bt_add_pessoa.disabled 	     	   = true;
										document.formulario.bt_add_cidade.disabled 	     	   = true;
										document.formulario.bt_add_servico.disabled      	   = true;
										document.formulario.bt_add_agente.disabled 	      	   = true;
										document.formulario.bt_add_status.disabled 	     	   = true;
										document.formulario.bt_add_vencimento_contrato.disabled= true;
										document.formulario.bt_add_grupo_pessoa.disabled	   = true;
										
										document.formulario.Acao.value 		= 'gerado';
										break;
									case '4':
										document.formulario.Acao.value 		= 'emprocessamento';	
										setTimeout(function (){ busca_processo_financeiro(IdProcessoFinanceiro,Erro,Local,ListarCampo);},30000);
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
								
								document.getElementById('cp_contas_receber').style.display	=	'none';						
								document.getElementById('tabelaContaReceberTotalValor').innerHTML	=	"0,00";	
								document.getElementById('tabelaContaReceberTotal').innerHTML		=	"Total: 0";

								listaLocalCobranca(Filtro_IdLocalCobranca, IdStatus);
								busca_nota_fiscal_data_emissao(Filtro_IdLocalCobranca,false);
								
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
								document.formulario.Filtro_IdPessoa_Processo.value		= Filtro_IdPessoa;
								break;
							case 'LancamentoFinanceiro':
								document.formulario.IdProcessoFinanceiro.value			= IdProcessoFinanceiro;
								document.formulario.MesReferencia.value 				= MesReferencia;
								document.formulario.MesVencimento.value 				= MesVencimento;
								document.formulario.MenorVencimento.value 				= MenorVencimento;
								document.formulario.Filtro_IdLocalCobranca.value		= Filtro_IdLocalCobranca;
								document.formulario.Filtro_TipoLancamento.value			= Filtro_TipoLancamento;
							case 'MalaDireta':
								nameNode = xmlhttp.responseXML.getElementsByTagName("DescFiltro_IdLocalCobranca")[0]; 
								nameTextNode = nameNode.childNodes[0];
								var DescFiltro_IdLocalCobranca = nameTextNode.nodeValue;
								
								document.formulario.IdProcessoFinanceiro.value	= IdProcessoFinanceiro;
								document.formulario.MesReferencia.value 		= MesReferencia;
								document.formulario.MenorVencimento.value 		= MenorVencimento;
								document.formulario.IdLocalCobranca.value		= DescFiltro_IdLocalCobranca;
								break;						
							case 'AdicionarProcessoFinanceiro':								
								nameNode = xmlhttp.responseXML.getElementsByTagName("ValorTotal")[0]; 
								nameTextNode = nameNode.childNodes[0];
								var ValorTotal = nameTextNode.nodeValue;
								
								nameNode = xmlhttp.responseXML.getElementsByTagName("QdtLancamentos")[0]; 								
								nameTextNode = nameNode.childNodes[0];
								var QdtLancamentos = nameTextNode.nodeValue;
								
								nameNode = xmlhttp.responseXML.getElementsByTagName("AbreviacaoNomeLocalCobranca")[0]; 
								nameTextNode = nameNode.childNodes[0];
								var AbreviacaoNomeLocalCobranca = nameTextNode.nodeValue;				
							
								var cont = 0; ii='';
								
								if(ListarCampo == '' || ListarCampo == undefined){
							
									if(document.formulario.Filtro_IdProcessoFinanceiro.value == ''){
										document.formulario.Filtro_IdProcessoFinanceiro.value = IdProcessoFinanceiro;
									
										ii = 0;
									}else{
										var tempFiltro	=	document.formulario.Filtro_IdProcessoFinanceiro.value.split(',');
											
										ii=0; 
										while(tempFiltro[ii] != undefined){											
											if(tempFiltro[ii] != IdProcessoFinanceiro){
												cont++;		
											}
											ii++;
										}
										if(ii == cont){
											document.formulario.Filtro_IdProcessoFinanceiro.value = document.formulario.Filtro_IdProcessoFinanceiro.value + "," + IdProcessoFinanceiro;
										}
									}
								}else{
									ii=0;
								}							
								if(ii == cont){
																										
									var tam, linha, c0, c1, c2, c3, c4, c5, c6, c7;
									
									tam 	= document.getElementById('tabelaProcessoFinanceiro').rows.length;
									linha	= document.getElementById('tabelaProcessoFinanceiro').insertRow(tam-1);
									
									if(tam%2 != 0){
										linha.style.backgroundColor = "#E2E7ED";
									}
									
									linha.accessKey 			= IdProcessoFinanceiro; 
									
									c0	= linha.insertCell(0);	
									c1	= linha.insertCell(1);	
									c2	= linha.insertCell(2);	
									c3	= linha.insertCell(3);
									c4	= linha.insertCell(4);
									c5	= linha.insertCell(5);
									c6	= linha.insertCell(6);
									c7	= linha.insertCell(7);							
									
									var linkIni = "<a href='cadastro_processo_financeiro.php?IdProcessoFinanceiro="+IdProcessoFinanceiro+"'>";
									var linkFim = "</a>";
								
									c0.innerHTML = linkIni + IdProcessoFinanceiro + linkFim;
									c0.style.padding =	"0 0 0 5px";
									
									c1.innerHTML = linkIni + MesReferencia + linkFim;
									
									c2.innerHTML = linkIni + MesVencimento + linkFim;
									
									c3.innerHTML = linkIni + AbreviacaoNomeLocalCobranca + linkFim;
									
									c4.innerHTML = linkIni + QdtLancamentos + linkFim;
									
									c5.innerHTML = linkIni + ValorTotal + linkFim;
									c5.style.textAlign = "right";
									c5.style.padding =	"0 8px 0 0";																	
									
									if(document.formulario.IdStatus.value == 1){
										c6.innerHTML = "<img src='../../img/estrutura_sistema/ico_del.gif' alt='Excluir?' onClick=\"remover_filtro_processo_financeiro("+IdProcessoFinanceiro+")\"></tr>";
									}else{
										c6.innerHTML = "<img src='../../img/estrutura_sistema/ico_del_c.gif' alt='Excluir?'\">";
									}
									c6.style.textAlign = "center";
									c6.style.cursor = "pointer";
							
									if(document.formulario.IdProcessoNotaFiscal.value == ''){
										document.getElementById('totaltabelaProcessoFinanceiro').innerHTML	=	'Total: '+(ii+1);
									}else{
										if(document.formulario.Erro.value != ''){
											scrollWindow('bottom');
										}
									}
								}
								
								document.formulario.IdProcessoFinanceiro.value			= "";
								document.formulario.MesReferenciaProcesso.value			= "";
								document.formulario.MesVencimentoProcesso.value			= "";
								document.formulario.LocalCobrancaProcesso.value			= "";
								document.formulario.QtdLancamentoProcesso.value			= "";
								document.formulario.ValorTotalProcesso.value			= "";	
								break;							
							case 'AdicionarMalaDireta':	
								nameNode = xmlhttp.responseXML.getElementsByTagName("DescFiltro_IdLocalCobranca")[0]; 
								nameTextNode = nameNode.childNodes[0];
								var DescFiltro_IdLocalCobranca = nameTextNode.nodeValue;		
							
								var cont = 0; ii='';
								
								if(ListarCampo == '' || ListarCampo == undefined){
									if(document.formulario.Filtro_IdProcessoFinanceiro.value == ''){
										document.formulario.Filtro_IdProcessoFinanceiro.value = IdProcessoFinanceiro;
										ii = 0;
									}else{
										var tempFiltro	=	document.formulario.Filtro_IdProcessoFinanceiro.value.split(',');
										ii=0;
										
										while(tempFiltro[ii] != undefined){
											if(tempFiltro[ii] != IdProcessoFinanceiro){
												cont++;		
											}
											ii++;
										}
										
										if(ii == cont){
											document.formulario.Filtro_IdProcessoFinanceiro.value = document.formulario.Filtro_IdProcessoFinanceiro.value + "," + IdProcessoFinanceiro;
										}
									}
								}else{
									ii=0;
								}
								
								if(ii == cont){
									var tam, linha, c0, c1, c2, c3, c4, c5, c6, c7;
									tam 	= document.getElementById('tabelaProcessoFinanceiro').rows.length;
									linha	= document.getElementById('tabelaProcessoFinanceiro').insertRow(tam-1);
									
									if(tam%2 != 0){
										linha.style.backgroundColor = "#E2E7ED";
									}
									
									linha.accessKey 			= IdProcessoFinanceiro; 
									
									c0	= linha.insertCell(0);	
									c1	= linha.insertCell(1);	
									c2	= linha.insertCell(2);	
									c3	= linha.insertCell(3);
									c4	= linha.insertCell(4);							
									
									var linkIni = "<a href='cadastro_processo_financeiro.php?IdProcessoFinanceiro="+IdProcessoFinanceiro+"'>";
									var linkFim = "</a>";
								
									c0.innerHTML = linkIni + IdProcessoFinanceiro + linkFim;
									c0.style.padding =	"0 0 0 5px";
									
									c1.innerHTML = linkIni + MesReferencia + linkFim;
									
									c2.innerHTML = linkIni + MenorVencimento + linkFim;
									
									c3.innerHTML = linkIni + DescFiltro_IdLocalCobranca + linkFim;
									
									if(document.formulario.IdStatus.value == 1){
										c4.innerHTML = "<img src='../../img/estrutura_sistema/ico_del.gif' alt='Excluir?' onClick=\"remover_filtro_processo_financeiro("+IdProcessoFinanceiro+")\"></tr>";
									}else{
										c4.innerHTML = "<img src='../../img/estrutura_sistema/ico_del_c.gif' alt='Excluir?'\">";
									}
									c4.style.textAlign = "center";
									c4.style.cursor = "pointer";
									
									document.getElementById('totaltabelaProcessoFinanceiro').innerHTML	=	'Total: '+(ii+1);
								}
								
								document.formulario.IdProcessoFinanceiro.value		= "";
								document.formulario.MesReferencia.value				= "";
								document.formulario.MenorVencimento.value			= "";
								document.formulario.IdLocalCobranca.value			= "";
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
	function remover_filtro_agente(IdAgente){
		for(var i=0; i<document.getElementById('tabelaAgente').rows.length; i++){
			if(IdAgente == document.getElementById('tabelaAgente').rows[i].accessKey){
				document.getElementById('tabelaAgente').deleteRow(i);
				tableMultColor('tabelaAgente');
				break;
			}
		}	
		var tempFiltro	=	document.formulario.Filtro_IdAgenteAutorizado.value.split(',');
		var novoFiltro  = '';
		
		var ii = 0;
		while(tempFiltro[ii] != undefined){
			if(tempFiltro[ii] != IdAgente){
				if(novoFiltro == ''){
					novoFiltro = tempFiltro[ii];
				}else{
					novoFiltro = novoFiltro + "," + tempFiltro[ii];
				}
			}
			ii=ii+1;
		}
		
		document.formulario.Filtro_IdAgenteAutorizado.value 	= 	novoFiltro;
		document.getElementById('totaltabelaAgente').innerHTML	=	'Total: '+(ii-1);
	}	
	
