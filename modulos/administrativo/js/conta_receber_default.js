	function janela_busca_conta_receber(IdStatus){
		janelas('../administrativo/busca_conta_receber.php',530,350,250,100,'');
	}
	function busca_conta_receber(IdContaReceber,Erro,Local,ListarCampo){
		if(IdContaReceber == ''){
			IdContaReceber = 0;
		}
		if(Local == 'ContaReceber'){
			var url = "xml/conta_receber_novo.php?IdContaReceber="+IdContaReceber;
		}else{
			var url = "xml/conta_receber.php?IdContaReceber="+IdContaReceber;
		}
		
		if(Local == 'ContaReceberAtivar'){
			url += '&IdStatusAtivacaoContaReceber=1';
		}
		
		if(Local == 'NotaFiscalSaida'){
			url += '&IdStatusValido=1';
		}
		
		if(Local == "AgruparContaReceber"){
			var url = "xml/conta_receber_agrupar.php?IdContaReceber="+IdContaReceber;
			
			url += "&IdPessoa="+document.formulario.IdPessoa.value+"&IdStatus=1&Local="+Local;
			var tempIdContaReceberAgrupados = document.formulario.IdContaReceberAgrupados.value.split(',');
			
			for(var i = 0; i < tempIdContaReceberAgrupados.length; i++){
				if(tempIdContaReceberAgrupados[i] == IdContaReceber){
					document.formulario.IdContaReceber.value = '';
					document.formulario.NomePessoa.value = '';
					document.formulario.IdStatus.value = '';
					document.formulario.bt_add_conta_receber.disabled = true;
					
					document.formulario.IdContaReceber.focus();
					return;
				}
			}
		}
		if(Local == "AdicionarAgruparContaReceber"){
			var url = "xml/conta_receber_agrupar.php?IdContaReceber="+IdContaReceber;
		}
		call_ajax(url, function(xmlhttp){ 
			var nameNode, nameTextNode;
			
			if(Erro != false){
				document.formulario.Erro.value = 0;
				verificaErro();
			}
			
			if(!isNaN(Number(xmlhttp.responseText))){
				switch (Local){
					case "AgruparContaReceber":
						document.formulario.IdContaReceber.value			= '';
						document.formulario.NomePessoa.value				= '';
						document.formulario.IdStatus.value					= '';
						document.formulario.bt_add_conta_receber.disabled	= true;
						
						alert("Contas a Receber já utilizado no agrupamento '"+Number(xmlhttp.responseText)+"', verifique.");
						break;
				}
			} else{
				if(xmlhttp.responseText == 'false'){
					switch (Local){
						case 'ContaReceberAtivar':
							document.formulario.IdContaReceber.value 			= '';
							document.formulario.NumeroDocumento.value 			= '';
							document.formulario.DataLancamento.value 			= '';
							document.formulario.DataVencimento.value 			= '';
							document.formulario.ValorContaReceber.value	 		= '';
							document.formulario.ValorDespesas.value				= '';
							document.formulario.ValorDesconto.value 			= '';
							document.formulario.ValorFinal.value 				= '';
							document.formulario.IdStatus.value					= '';
//								document.formulario.Status.value					= '';
							document.formulario.IdLocalCobranca.value 			= '';
							document.formulario.NumeroNF.value 					= '';
							document.formulario.ObsAtivar.value					= '';
							document.formulario.Obs.value						= '';
							document.formulario.DataCriacao.value 				= '';
							document.formulario.LoginCriacao.value 				= '';
							document.formulario.DataAlteracao.value 			= '';
							document.formulario.LoginAlteracao.value			= '';
							document.formulario.Acao.value 						= 'alterar';
							document.formulario.IdPessoa.value 					= '';
							document.formulario.IdTipoLocalCobranca.value		= '';
							document.formulario.IdPosicaoCobranca.value			= '';
							
							verificaAcao();
							status_inicial();
							
							while(document.getElementById('tabelaLancFinanceiro').rows.length > 2){
								document.getElementById('tabelaLancFinanceiro').deleteRow(1);
							}
							
							document.getElementById('cp_lancamentos_financeiros').style.display	=	'none';	
							document.getElementById('IdPosicaoCobranca').style.display			=	'none';
							document.getElementById('DescricaoPosicaoCobranca').style.display	=	'none';
							
							document.getElementById('tabelaLancFinanceiroTotalValor').innerHTML	=	"0,00";	
							document.getElementById('tabelaLancFinanceiroTotal').innerHTML		=	"Total: 0";
							
							document.getElementById('totalValorDesconto').innerHTML				=	"0,00";						
							document.getElementById('totalValorRecebido').innerHTML				=	"0,00";	
							document.getElementById('totalRecebimentos').innerHTML				=	"Total: 0";		
							
							document.getElementById('cp_Status').style.display					= 'none';	
							document.getElementById('cp_Status').innerHTML						= "";
							
							busca_posicao_cobranca('');
							busca_pessoa('','false',document.formulario.Local.value);
							
							addParmUrl("marPessoa","IdContaReceber","");
							addParmUrl("marPessoa","IdPessoa",IdPessoa);
							addParmUrl("marPessoaNovo","IdContaReceber","");
							addParmUrl("marContasReceber","IdPessoa",IdPessoa);
							addParmUrl("marContasReceber","IdContaReceber","");
							addParmUrl("marLancamentoFinanceiro","IdContaReceber","");
							
							document.formulario.IdContaReceber.focus();
							break;
						case 'ContaReceberRecebimento':	
							document.formulario.IdContaReceber.value 			= '';
							document.formulario.NumeroDocumento.value 			= '';
							document.formulario.DataLancamento.value 			= '';
							document.formulario.DataVencimento.value 			= '';
							document.formulario.DataRecebimento.value 			= '';
							document.formulario.ValorContaReceber.value	 		= '';
							document.formulario.ValorDespesas.value				= '';
							document.formulario.ValorDesconto.value 			= '';
							document.formulario.ValorMulta.value				= '';
							document.formulario.ValorJuros.value				= '';
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
						//	document.formulario.PercentualJurosDiarios.value	= "";	
							document.formulario.ValorDescontoAConceber.value	= '';
							document.formulario.PercentualDesconto.value		= '';
							document.formulario.DataLimiteDesconto.value		= '';
							document.formulario.LimiteDesconto.value			= '';								
							document.formulario.ValorTaxa.value 				= '';	
							document.formulario.ValorPercentual.value			= '';
							document.formulario.ValorOutrasDesp.value			= '';
							
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
							
							document.getElementById('cp_Status').style.display					= 'none';	
							document.getElementById('cp_Status').innerHTML				= "";
							
							document.getElementById('cpPosicaoCobranca').style.display			= 'none';
							document.getElementById("cpLimiteDesconto").style.display			=	"none";
							document.getElementById("titDataLimiteDesconto").style.display		=	"none";
							document.getElementById("cpDataLimiteDesconto").style.display		=	"none";
							document.getElementById("imgDataLimiteDesconto").style.display		=	"none";	
							document.getElementById("cpPosicaoCobranca").style.display			=	"none";	
							
							busca_pessoa('',false,document.formulario.Local.value);
							
							addParmUrl("marPessoa","IdContaReceber","");
							addParmUrl("marPessoa","IdPessoa",IdPessoa);
							addParmUrl("marPessoaNovo","IdContaReceber","");
							addParmUrl("marContasReceber","IdPessoa",IdPessoa);
							addParmUrl("marContasReceber","IdContaReceber","");
							addParmUrl("marLancamentoFinanceiro","IdContaReceber","");
							addParmUrl("marReenvioMensagem","IdContaReceber","");
							//addParmUrl("marReenvioMensagem","IdPessoa","");
							
															
							document.formulario.bt_cancelar.disabled			=	true;
							document.formulario.IdContaReceber.focus();
							break;
						
						case 'ContaReceberVencimento':															
							document.formulario.IdContaReceber.value 			= '';
							document.formulario.NumeroDocumento.value 			= '';
							document.formulario.DataLancamento.value 			= '';
							document.formulario.DataVencimento.value 			= '';
							document.formulario.DataVencimentoAntiga.value 		= '';
							document.formulario.IdLocalCobranca.value 			= '';
							document.formulario.ValorContaReceber.value	 		= '';
							document.formulario.ValorDespesas.value				= '';
							document.formulario.ValorOutrasDespesas.value		= '';
							document.formulario.ValorMulta.value				= '';
							document.formulario.ValorJuros.value				= '';
							document.formulario.ValorDesconto.value 			= '';
							document.formulario.ValorOutrasDesp.value 			= ''; 
							document.formulario.ValorPercentual.value 			= '';
							document.formulario.ValorTaxa.value		 			= '';  
							document.formulario.ValorFinal.value 				= '';
							document.formulario.NumeroNF.value 					= '';
							document.formulario.DataNF.value 					= '';
							document.formulario.IdStatus.value					= '';
							document.formulario.DataVencimento.value			= '';
							document.formulario.CalcularMulta.value				= '';
							document.formulario.TaxaReimpressao.value			= '';
							document.formulario.ValorVencimento.value 			= '';
							document.formulario.ValorMoraMulta.value 			= '';
							document.formulario.ValorDescontoVencimento.value 	= '';
							document.formulario.ValorJurosVencimento.value 		= '';
							document.formulario.ValorTaxaReImpressaoBoleto.value= '';
							document.formulario.ValorFinalVencimento.value 		= '';
							document.formulario.DataCriacao.value 				= '';
							document.formulario.LoginCriacao.value 				= '';
							document.formulario.IdPessoa.value 					= '';	
							document.formulario.IdTipoLocalCobranca.value		= '';
							document.formulario.IdPosicaoCobranca.value			= '';
							document.formulario.TaxaReimpressao.value 			= '';	
							document.formulario.PercentualMulta.value			= "";	
							document.formulario.PercentualJurosDiarios.value	= "";	
							document.formulario.QuantDias.value					= "";
							document.formulario.ValorDescontoAConceber.value	= '';
							document.formulario.PercentualDesconto.value		= '';
							document.formulario.DataLimiteDesconto.value		= '';
							document.formulario.LimiteDesconto.value			= '';
							document.formulario.DataPrimeiroVencimento.value	= '';
							document.formulario.ValorPrimeiroVencimento.value	= '';
							document.formulario.IdProcessoFinanceiro.value 		= '';
							document.formulario.Acao.value 						= 'inserir';
							document.formulario.ValorTaxaReImpressaoBoletoLocalCobranca.value	=	'';		
							
							verificaAcao();
							
							while(document.getElementById('tabelaLancFinanceiro').rows.length > 2){
								document.getElementById('tabelaLancFinanceiro').deleteRow(1);
							}							
							while(document.getElementById('tabelaVencimentos').rows.length > 2){
								document.getElementById('tabelaVencimentos').deleteRow(1);
							}
							
							document.getElementById('cp_lancamentos_financeiros').style.display	=	'none';						
							document.getElementById('tabelaLancFinanceiroTotalValor').innerHTML	=	"0,00";	
							document.getElementById('tabelaLancFinanceiroTotal').innerHTML		=	"Total: 0";
															
							document.getElementById('totalValorDesconto').innerHTML				=	"0,00";						
							document.getElementById('totalValorReceber').innerHTML				=	"0,00";	
							document.getElementById('totalValorMulta').innerHTML				=	"0,00";						
							document.getElementById('totalValorJuros').innerHTML				=	"0,00";	
							document.getElementById('totalValorTaxa').innerHTML					=	"0,00";														
							document.getElementById('totalValorFinal').innerHTML				=	"0,00";	
							document.getElementById('totalValorOutrasDespessas').innerHTML		=	"0,00";	
							document.getElementById('totalVencimentos').innerHTML				=	"Total: 0";
															
							document.getElementById('cp_Status').style.display			= 'none';	
							document.getElementById('cp_Status').innerHTML				= "";
							
							document.getElementById('cpPosicaoCobranca').style.display			= 	'none';
							document.getElementById("cpLimiteDesconto").style.display			=	"none";
							document.getElementById("titDataLimiteDesconto").style.display		=	"none";
							document.getElementById("cpDataLimiteDesconto").style.display		=	"none";
							document.getElementById("imgDataLimiteDesconto").style.display		=	"none";	
							document.getElementById("cpPosicaoCobranca").style.display			=	"none";	
							
							document.getElementById('titMultaJuros').innerHTML	=	'Multa:0,000%.  Juros:0,000%';
							document.getElementById('titTaxa').innerHTML	=	document.formulario.Moeda.value+':0,00';
							
							busca_pessoa('','false',document.formulario.Local.value);
							
							addParmUrl("marPessoa","IdContaReceber","");
							addParmUrl("marPessoa","IdPessoa",IdPessoa);
							addParmUrl("marPessoaNovo","IdContaReceber","");
							addParmUrl("marContasReceber","IdPessoa",IdPessoa);
							addParmUrl("marContasReceber","IdContaReceber","");
							addParmUrl("marLancamentoFinanceiro","IdContaReceber","");
							addParmUrl("marReenvioMensagem","IdContaReceber","");
							//addParmUrl("marReenvioMensagem","IdPessoa","");
							
							document.formulario.IdContaReceber.focus();
							
							statusInicial();
							verificaAcao();
							break;
						case 'Etiqueta':
							document.formulario.IdContaReceber.value 				= "";
							document.formulario.NomeContaReceber.value 				= "";
							document.formulario.NumeroDocumento.value 				= "";
							document.formulario.IdLocalCobrancaContaReceber.value 	= "";
							document.formulario.DataLancamento.value 				= "";
						
							document.formulario.IdContaReceber.focus();
							break;	
						case 'AdicionarEtiqueta':
							document.formulario.IdContaReceber.value 				= "";
							document.formulario.NomeContaReceber.value 				= "";
							document.formulario.NumeroDocumento.value 				= "";
							document.formulario.IdLocalCobrancaContaReceber.value 	= "";
							document.formulario.DataLancamento.value 				= "";
						
							document.formulario.IdContaReceber.focus();
							break;							
						case 'AdicionarContaReceber':
							document.formulario.IdContaReceber.value				= "";
							document.formulario.NomePessoaContaReceber.value		= "";
							document.formulario.DataVencimentoContaReceber.value	= "";
							document.formulario.NumeroDocumentoContaReceber.value	= "";																
							break;								
						case 'AgruparContaReceber':
							document.getElementById("tit_ContaReceberPessoa").innerHTML	= "Nome Pessoa";
							
							document.formulario.IdContaReceber.value			= '';
							document.formulario.NomePessoa.value				= '';
							document.formulario.IdStatus.value					= '';
/*								document.formulario.IdPessoaEndereco.value			= '';
							document.formulario.NomeResponsavelEndereco.value	= '';
							document.formulario.CEP.value						= '';
							document.formulario.Endereco.value					= '';
							document.formulario.Numero.value					= '';
							document.formulario.Complemento.value				= '';
							document.formulario.Bairro.value					= '';
							document.formulario.IdPais.value					= '';
							document.formulario.Pais.value						= '';
							document.formulario.IdEstado.value					= '';
							document.formulario.Estado.value					= '';
							document.formulario.IdCidade.value					= '';
							document.formulario.Cidade.value					= '';
*/								
							limparNovoVencimento();
							
/*								while(document.formulario.IdPessoaEndereco.options.length > 0){
								document.formulario.IdPessoaEndereco.options[0] = null;
							}
*/								
							document.formulario.bt_add_conta_receber.disabled	= true;
							document.formulario.IdContaReceber.focus();
							break;							
						case 'Protocolo':
							document.formulario.IdContaReceber.value			= '';
							document.formulario.NomePessoaContaReceber.value	= '';
							document.formulario.IdStatusContaReceber.value		= '';
							break;
						default:
							document.formulario.IdContaReceber.value 			= '';
							document.formulario.IdProcessoFinanceiro.value 		= '';
							document.formulario.NumeroDocumento.value 			= '';
							document.formulario.DataLancamento.value 			= '';
							document.formulario.DataVencimento.value 			= '';
							document.formulario.DataRecebimento.value 			= '';
							document.formulario.ValorContaReceber.value	 		= '';
							document.formulario.ValorDespesas.value				= '';
							document.formulario.ValorDesconto.value 			= '';
							document.formulario.ValorJuros.value 				= '';
							document.formulario.ValorMulta.value 				= '';
							document.formulario.ValorPercentual.value 			= '';
							document.formulario.ValorTaxa.value 				= '';
							document.formulario.ValorOutrasDesp.value 			= '';
							document.formulario.ValorFinal.value 				= '';
							document.formulario.ValorReceber.value 				= '';
							document.formulario.ValorRecebimento.value 			= '';
							document.formulario.ValorMoraMulta.value 			= '';
							document.formulario.ValorOutrasDespesas.value 		= '';
							document.formulario.ValorDescontoAConceber.value	= '';
							document.formulario.PercentualDesconto.value		= '';
							document.formulario.DataLimiteDesconto.value		= '';
							document.formulario.LimiteDesconto.value			= '';
							document.formulario.IdStatus.value					= '';
							document.formulario.IdLocalCobranca.value 			= '';
							document.formulario.IdLocalRecebimento.value 		= '';
							document.formulario.ValorDescontoRecebimento.value 	= '';
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
							document.formulario.BaseVencimento.value			= "";	
							document.formulario.CalcularMulta.value				= "";	
							document.formulario.PercentualMulta.value			= "";	
							document.formulario.PercentualJurosDiarios.value	= "";
							document.formulario.Endereco.value 					= '';
							document.formulario.Numero.value 					= '';
							document.formulario.CEP.value 						= '';
							document.formulario.SiglaEstado.value				= '';
							document.formulario.Cidade.value					= '';
							document.formulario.Bairro.value					= '';	
							
							document.formulario.IdPessoaEndereco.value			=	"";
							document.formulario.NomeResponsavelEndereco.value	=	"";
							document.formulario.CEP.value						=	"";
							document.formulario.Endereco.value					=	"";
							document.formulario.Numero.value					=	"";
							document.formulario.Complemento.value				=	"";
							document.formulario.Bairro.value					=	"";
							document.formulario.IdPais.value					=	"";
							document.formulario.Pais.value						=	"";
							document.formulario.IdEstado.value					=	"";
							document.formulario.Estado.value					=	"";
							document.formulario.IdCidade.value					=	"";
							document.formulario.Cidade.value					=	"";
							document.formulario.NumeroCartaoCredito.value		= 	"";
							document.formulario.IdLocalCobrancaTemp.value		= 	"";
							document.formulario.NossoNumero.value				=	"";
							
							busca_obs_nota_fiscal(0);
							verificaAcao();
							statusInicial();
							
							if(document.getElementById('titMultaJuros') != undefined){
								document.getElementById('titMultaJuros').innerHTML	=	'Multa:0,000%.  Juros:0,000%';
							}
							if(document.formulario.IdContaReceberAgrupador != undefined){
								document.formulario.IdContaReceberAgrupador.value = '';
							}
							
							while(document.formulario.IdPessoaEndereco.options.length > 0){
								document.formulario.IdPessoaEndereco.options[0] = null;
							}
							while(document.getElementById('tabelaLancFinanceiro').rows.length > 2){
								document.getElementById('tabelaLancFinanceiro').deleteRow(1);
							}
							while(document.getElementById('tabelaRecebimentos').rows.length > 2){
								document.getElementById('tabelaRecebimentos').deleteRow(1);
							}
							while(document.getElementById('tabelaParametro').rows.length > 0){
								document.getElementById('tabelaParametro').deleteRow(0);
							}
							
							if(document.formulario.CaixaAtivado.value != '1'){
								document.getElementById("cp_recebimento").className = null;
							} else {
								document.getElementById("cp_recebimento").className = "ocultar-bl";
							}
							
							document.getElementById('cpHistorico').style.display				=	'none';						
							document.getElementById('cp_parametros').style.display				=	'none';	
							document.getElementById("titLimiteDesconto").style.display			=	"none";
							document.getElementById("cpLimiteDesconto").style.display			=	"none";
							document.getElementById("titDataLimiteDesconto").style.display		=	"none";
							document.getElementById("cpDataLimiteDesconto").style.display		=	"none";
//									document.getElementById("imgDataLimiteDesconto").style.display		=	"none";	
							document.getElementById("titPosicaoCobranca").style.display			=	"none";				
							document.getElementById("cpPosicaoCobranca").style.display			=	"none";				
							
							document.getElementById('cp_lancamentos_financeiros').style.display	=	'none';
							document.getElementById('tabelaLancFinanceiroTotalValor').innerHTML	=	"0,00";
							document.getElementById('tabelaLancFinanceiroTotal').innerHTML		=	"Total: 0";
							
							document.getElementById('totalValorDesconto').innerHTML				=	"0,00";
							document.getElementById('totalValorRecebido').innerHTML				=	"0,00";
							document.getElementById('totalRecebimentos').innerHTML				=	"Total: 0";
							
							document.getElementById('cp_Status').style.display			= 'none';
							document.getElementById('cp_Status').innerHTML				= "";
							
							
							document.getElementById("cpDataNFIco").style.display 	= 'block';
							document.getElementById("spDataNFIco").style.display 	= 'block';
							document.getElementById("imgDataNF").style.display		= 'block';
							
							busca_pessoa('','false',document.formulario.Local.value);
							
							addParmUrl("marPessoa","IdContaReceber","");
							addParmUrl("marPessoa","IdPessoa",IdPessoa);
							addParmUrl("marPessoaNovo","IdContaReceber","");
							addParmUrl("marContasReceber","IdPessoa",IdPessoa);
							addParmUrl("marContasReceber","IdContaReceber","");
							addParmUrl("marLancamentoFinanceiro","IdContaReceber","");
							addParmUrl("marReenvioMensagem","IdContaReceber","");
							//addParmUrl("marReenvioMensagem","IdPessoa","");
							
							//document.formulario.ValorDesconto.readOnly  			= false;
							//document.formulario.ValorDespesas.readOnly 			= false;
							document.formulario.IdLocalRecebimento.disabled  		= false;
							document.formulario.DataRecebimento.readOnly 			= false;
							document.getElementById('imgDataRecebimento').src		= '../../img/estrutura_sistema/ico_date.gif';
							document.formulario.ValorDescontoRecebimento.readOnly 	= false;
							document.formulario.ValorMoraMulta.readOnly 			= false;
							document.formulario.ValorOutrasDespesas.readOnly    	= false;
							document.formulario.Obs.readOnly 						= false;
							document.formulario.ValorMoraMulta.readOnly				= false;
							
							document.formulario.bt_enviar.disabled		=	true;
							document.formulario.bt_alterar.disabled		=	true;
							document.formulario.bt_imprimir1.disabled	=	true;
							document.formulario.bt_receber.disabled		=	true;
							document.formulario.bt_cancelar.disabled	=	true;
							document.formulario.bt_chegar.disabled		=	true;
							document.formulario.bt_nota_fiscal.disabled = 	true;
							
							busca_cartao("","","");
							busca_conta_debito("","","");
							listarPosicaoCobranca("","");
							
							document.formulario.IdContaReceber.focus();
							break;	
					}
				} else{
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
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("ValorOutrasDespesasVencimento")[0]; 
					nameTextNode = nameNode.childNodes[0];
					var ValorOutrasDespesasVencimento = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("ValorDespesas")[0]; 
					nameTextNode = nameNode.childNodes[0];
					var ValorDespesas = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("ValorDesconto")[0]; 
					nameTextNode = nameNode.childNodes[0];
					var ValorDesconto = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("ValorJuros")[0]; 
					nameTextNode = nameNode.childNodes[0];
					var ValorJuros = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("ValorMulta")[0]; 
					nameTextNode = nameNode.childNodes[0];
					var ValorMulta = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("IdArquivoRetorno")[0]; 
					nameTextNode = nameNode.childNodes[0];
					var IdArquivoRetorno = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("IdRecibo")[0]; 
					nameTextNode = nameNode.childNodes[0];
					var IdRecibo = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("IdStatus")[0]; 
					nameTextNode = nameNode.childNodes[0];
					var IdStatus = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("StatusPagamento")[0]; 
					nameTextNode = nameNode.childNodes[0];
					var StatusPagamento = nameTextNode.nodeValue;
					
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
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("OcultarBlocoNotafiscal")[0]; 
					nameTextNode = nameNode.childNodes[0];
					var OcultarBlocoNotafiscal = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("NumeroNF")[0]; 
					nameTextNode = nameNode.childNodes[0];
					var NumeroNF = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("DataNF")[0]; 
					nameTextNode = nameNode.childNodes[0];
					var DataNF = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("ModeloNF")[0]; 
					nameTextNode = nameNode.childNodes[0];
					var ModeloNF = nameTextNode.nodeValue;
					
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
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("BaseVencimento")[0]; 
					nameTextNode = nameNode.childNodes[0];
					var BaseVencimento = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("PercentualMulta")[0]; 
					nameTextNode = nameNode.childNodes[0];
					var PercentualMulta = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("PercentualJurosDiarios")[0]; 
					nameTextNode = nameNode.childNodes[0];
					var PercentualJurosDiarios = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("IdPosicaoCobranca")[0]; 
					nameTextNode = nameNode.childNodes[0];
					var IdPosicaoCobranca = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("ValorDescontoAConceber")[0]; 
					nameTextNode = nameNode.childNodes[0];
					var ValorDescontoAConceber = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("LimiteDesconto")[0]; 
					nameTextNode = nameNode.childNodes[0];
					var LimiteDesconto = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("DataLimiteDesconto")[0]; 
					nameTextNode = nameNode.childNodes[0];
					var DataLimiteDesconto = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("IdContaReceberAgrupador")[0]; 
					nameTextNode = nameNode.childNodes[0];
					var IdContaReceberAgrupador = nameTextNode.nodeValue;
					
					var valor_cp_Status = "<div style='line-height:11px; font-size:15px;'><b style='color:" + Cor + ";'>" + Status + "<br><span style='font-size:9px;'>" + StatusPagamento + "</span><b></div>";
					
					switch(Local){
						case 'AdicionarEtiqueta':
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
							
							document.formulario.IdContaReceber.value 				= "";
							document.formulario.NomeContaReceber.value 				= "";
							document.formulario.NumeroDocumento.value 				= "";
							document.formulario.IdLocalCobrancaContaReceber.value 	= "";
							document.formulario.DataLancamento.value 				= "";
							
							break;
						case 'ContaReceberAtivar':
							while(document.getElementById('tabelaLancFinanceiro').rows.length > 2){
								document.getElementById('tabelaLancFinanceiro').deleteRow(1);
							}
							
							document.formulario.IdPessoa.value 					= IdPessoa;
							
							document.formulario.IdContaReceber.value 			= IdContaReceber;
							document.formulario.NumeroDocumento.value 			= NumeroDocumento;
							document.formulario.ValorContaReceber.value			= formata_float(Arredonda(ValorContaReceber,2),2).replace(".",",");
							document.formulario.DataLancamento.value 			= dateFormat(DataLancamento);
							document.formulario.DataVencimento.value 			= dateFormat(DataVencimento);
							
							document.formulario.ValorDespesas.value				= formata_float(Arredonda(ValorDespesas,2),2).replace(".",",");
							document.formulario.ValorDesconto.value 			= formata_float(Arredonda(ValorDesconto,2),2).replace(".",",");
							document.formulario.ValorFinal.value				= formata_float(Arredonda(ValorFinal,2),2).replace(".",",");
							document.formulario.IdStatus.value					= IdStatus;
//								document.formulario.Status.value					= IdStatus;
							document.formulario.IdLocalCobranca.value 			= IdLocalCobranca;
							document.formulario.Obs.value 						= Obs;
							document.formulario.NumeroNF.value 					= NumeroNF;
							document.formulario.DataCriacao.value 				= dateFormat(DataCriacao);
							document.formulario.LoginCriacao.value 				= LoginCriacao;
							document.formulario.DataAlteracao.value 			= dateFormat(DataAlteracao);
							document.formulario.LoginAlteracao.value			= LoginAlteracao;
							document.formulario.ObsAtivar.value					= "";								
							document.formulario.IdTipoLocalCobranca.value		= IdTipoLocalCobranca;
							document.formulario.IdPosicaoCobranca.value			= '';
							document.formulario.Acao.value 						= 'alterar';
							
							for(var i=0; i<document.formulario.IdLocalCobranca.length; i++){
								if(document.formulario.IdLocalCobranca[i].value == IdLocalCobranca){
									document.formulario.IdLocalCobranca[i].selected = true;
									i = document.formulario.IdLocalCobranca.length;
								}							
							}
							
							if(IdTipoLocalCobranca == 3 || IdTipoLocalCobranca == 4){
								document.getElementById('IdPosicaoCobranca').style.display			= 'block';
								document.getElementById('DescricaoPosicaoCobranca').style.display	= 'block';
							}else{
								document.getElementById('IdPosicaoCobranca').style.display			= 'none';
								document.getElementById('DescricaoPosicaoCobranca').style.display	= 'none';
							}
							
							document.getElementById('cp_Status').innerHTML			= valor_cp_Status;
							document.getElementById('cp_Status').style.display		= 'block';
							document.getElementById('cp_Status').style.color		= Cor;
							
							addParmUrl("marPessoa","IdContaReceber",IdContaReceber);
							addParmUrl("marPessoa","IdPessoa",IdPessoa);
							addParmUrl("marPessoaNovo","IdContaReceber",IdContaReceber);
							addParmUrl("marContasReceber","IdPessoa",IdPessoa);
							addParmUrl("marLancamentoFinanceiro","IdContaReceber",IdContaReceber);							

							//Recebido
							busca_posicao_cobranca(IdContaReceber); 
							listarRecebimento(IdContaReceber,false);
							busca_lancamento_financeiro(IdContaReceber,false);
							busca_pessoa(IdPessoa,false);
							status_inicial();
							verificaAcao();
							break;
						case 'ContaReceberRecebimento':
						
							nameNode = xmlhttp.responseXML.getElementsByTagName("ValorTaxaReImpressaoBoleto")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var ValorTaxaReImpressaoBoleto = nameTextNode.nodeValue;
							
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
//								document.formulario.DataRecebimento.value 			= "";
							document.formulario.ValorDespesas.value				= formata_float(Arredonda(ValorDespesas,2),2).replace(".",",");
							document.formulario.ValorDesconto.value 			= formata_float(Arredonda(ValorDesconto,2),2).replace(".",",");
							document.formulario.ValorJuros.value				= formata_float(Arredonda(ValorJuros,2),2).replace(".",",");
							document.formulario.ValorMulta.value 				= formata_float(Arredonda(ValorMulta,2),2).replace(".",",");
							
							document.formulario.ValorTaxa.value 				= formata_float(Arredonda(ValorTaxaReImpressaoBoleto,2),2).replace(".",",");
							document.formulario.ValorOutrasDesp.value 			= formata_float(Arredonda(ValorOutrasDespesasVencimento,2),2).replace(".",",");
							
//								document.formulario.ValorMoraMulta.value 			= "0,00";
//								document.formulario.ValorDescontoRecebimento.value	= "0,00";
//								document.formulario.ValorOutrasDespesas.value		= "0,00";
							document.formulario.ValorRecebimento.value			= formata_float(Arredonda(ValorFinal,2),2).replace(".",",");
							document.formulario.ValorFinal.value				= formata_float(Arredonda(ValorFinal,2),2).replace(".",",");
							document.formulario.ValorReceber.value				= formata_float(Arredonda(ValorFinal,2),2).replace(".",",");
							document.formulario.IdStatus.value					= IdStatus;
							document.formulario.IdLocalCobranca.value 			= IdLocalCobranca;
//								document.formulario.IdArquivoRetorno.value			= "";
//								document.formulario.IdLocalRecebimento[0].selected	= true;
//								document.formulario.IdRecibo.value 					= "";
//								document.formulario.Obs.value 						= "";
//								document.formulario.IdStatusRecebimento.value		= "";
//								document.formulario.IdContaReceberRecebimento.value	= "";	
							document.formulario.HistoricoObs.value 				= Obs;
							document.formulario.NumeroNF.value 					= NumeroNF;
							document.formulario.IdTipoLocalCobranca.value 		= IdTipoLocalCobranca;
							document.formulario.DataNF.value 					= dateFormat(DataNF);
							document.formulario.IdPosicaoCobranca.value			= IdPosicaoCobranca;
							document.formulario.DataCriacao.value 				= dateFormat(DataCriacao);
							document.formulario.LoginCriacao.value 				= LoginCriacao;
							document.formulario.DataAlteracao.value 			= dateFormat(DataAlteracao);
							document.formulario.LoginAlteracao.value			= LoginAlteracao;
							document.formulario.LimiteDesconto.value			= LimiteDesconto;								
							document.formulario.DataLimiteDesconto.value		= '';								
							document.formulario.Acao.value 						= 'inserir';
							
							if(ValorDesconto > 0){
								var PercDesconto	=	(parseFloat(ValorDesconto)*100)/parseFloat(ValorFinal);
							}else{
								var PercDesconto = 0;
							}	
							
							document.formulario.ValorPercentual.value = formata_float(Arredonda(PercDesconto,2),2).replace(".",","); 	
							
							if(ValorDescontoAConceber > 0){
								var perc	=	(parseFloat(ValorDescontoAConceber)*100)/parseFloat(ValorFinal);
								
								document.getElementById("titLimiteDesconto").style.display		=	"block";
								document.getElementById("cpLimiteDesconto").style.display		=	"block";
								document.getElementById("titDataLimiteDesconto").style.display	=	"block";
								document.getElementById("cpDataLimiteDesconto").style.display	=	"block";
								document.getElementById("imgDataLimiteDesconto").style.display	=	"block";
								
								if(LimiteDesconto != ''){
									document.formulario.DataLimiteDesconto.value	=	dateFormat(DataLimiteDesconto);
								}
							}else{
								var perc	=	0;
								
								document.getElementById("titLimiteDesconto").style.display		=	"none";
								document.getElementById("cpLimiteDesconto").style.display		=	"none";
								document.getElementById("titDataLimiteDesconto").style.display	=	"none";
								document.getElementById("cpDataLimiteDesconto").style.display	=	"none";
								document.getElementById("imgDataLimiteDesconto").style.display	=	"none";
							}
							
							
							document.formulario.ValorDescontoAConceber.value		= formata_float(Arredonda(ValorDescontoAConceber,2),2).replace(".",",");
							document.formulario.PercentualDesconto.value			= formata_float(Arredonda(perc,2),2).replace(".",",");
							
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
							addParmUrl("marReenvioMensagem","IdContaReceber",IdContaReceber);
							//addParmUrl("marReenvioMensagem","IdPessoa",IdPessoa);

							//Recebido 
							busca_lancamento_financeiro(IdContaReceber,false);
							listarParametro(IdLocalRecebimento,false);
							busca_pessoa(IdPessoa,false,document.formulario.Local.value);
							listarContaReceberParametroRecebimento(IdContaReceber,IdLocalRecebimento,false);
							listarRecebimento(IdContaReceber,false,ListarCampo);
							scrollWindow('top');
							
							if(IdTipoLocalCobranca == 3){
								document.getElementById('cpPosicaoCobranca').style.display		= 'block';
							}else{
								document.getElementById('cpPosicaoCobranca').style.display		= 'none';
							}
							
							document.getElementById('cp_Status').innerHTML			= valor_cp_Status;
							document.getElementById('cp_Status').style.display		= 'block';
							document.getElementById('cp_Status').style.color		= Cor;
							
							document.formulario.bt_cancelar.disabled	=	true;
							break;
						case 'ContaReceberVencimento':
														
							nameNode = xmlhttp.responseXML.getElementsByTagName("ValorTaxaReImpressaoBoleto")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var ValorTaxaReImpressaoBoleto = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("ValorTaxaReImpressaoBoletoLocalCobranca")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var ValorTaxaReImpressaoBoletoLocalCobranca = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("ValorLancamento")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var ValorLancamento = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("DataPrimeiroVencimento")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var DataPrimeiroVencimento = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("ValorPrimeiroVencimento")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var ValorPrimeiroVencimento = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("IdProcessoFinanceiro")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var IdProcessoFinanceiro = nameTextNode.nodeValue;

							while(document.getElementById('tabelaLancFinanceiro').rows.length > 2){
								document.getElementById('tabelaLancFinanceiro').deleteRow(1);
							}
							while(document.getElementById('tabelaVencimentos').rows.length > 2){
								document.getElementById('tabelaVencimentos').deleteRow(1);
							}							
							
							document.formulario.ValorTaxaReImpressaoBoletoLocalCobranca.value	=	formata_float(Arredonda(ValorTaxaReImpressaoBoletoLocalCobranca,2),2).replace(".",",");
							document.formulario.IdPessoa.value 							= IdPessoa;								
							document.formulario.IdContaReceber.value 					= IdContaReceber;
							document.formulario.NumeroDocumento.value 					= NumeroDocumento;
							document.formulario.DataLancamento.value 					= dateFormat(DataLancamento);
							document.formulario.DataVencimentoAntiga.value 				= dateFormat(DataVencimento);
							document.formulario.DataVencimento.value 					= "";								
							document.formulario.ValorContaReceber.value					= formata_float(Arredonda(ValorContaReceber,2),2).replace(".",",");
							document.formulario.ValorDespesas.value						= formata_float(Arredonda(ValorDespesas,2),2).replace(".",",");
							document.formulario.ValorMulta.value 						= formata_float(Arredonda(ValorMulta,2),2).replace(".",",");
							document.formulario.ValorDesconto.value 					= formata_float(Arredonda(ValorDesconto,2),2).replace(".",",");
							document.formulario.ValorJuros.value						= formata_float(Arredonda(ValorJuros,2),2).replace(".",",");
							document.formulario.ValorFinal.value						= formata_float(Arredonda(ValorFinal,2),2).replace(".",",");
							document.formulario.ValorVencimento.value					= formata_float(Arredonda(ValorPrimeiroVencimento,2),2).replace(".",",");
							document.formulario.ValorOutrasDesp.value					= formata_float(Arredonda(ValorOutrasDespesasVencimento,2),2).replace(".",","); 
							document.formulario.ValorTaxa.value							= formata_float(Arredonda(ValorTaxaReImpressaoBoleto,2),2).replace(".",","); 
							document.formulario.CalcularMulta.value 					= "";
							document.formulario.TaxaReimpressao.value 					= "";
							document.formulario.ValorMoraMulta.value 					= "";
							document.formulario.ValorOutrasDespesas.value 				= "";
							document.formulario.ValorDescontoVencimento.value			= "";
							document.formulario.PercentualVencimento.value				= "";
							document.formulario.ValorJurosVencimento.value				= "";
							document.formulario.QuantDias.value							= "";
							document.formulario.IdStatus.value							= IdStatus;
							document.formulario.IdLocalCobranca.value 					= IdLocalCobranca;
							document.formulario.NumeroNF.value 							= NumeroNF;
							document.formulario.IdTipoLocalCobranca.value 				= IdTipoLocalCobranca;
							document.formulario.IdPosicaoCobranca.value					= IdPosicaoCobranca;
							document.formulario.PercentualMulta.value					= PercentualMulta;
							document.formulario.PercentualJurosDiarios.value			= PercentualJurosDiarios;
							document.formulario.DataNF.value 							= dateFormat(DataNF);
							document.formulario.DataCriacao.value 						= "";
							document.formulario.LoginCriacao.value 						= "";
							document.formulario.LimiteDesconto.value					= LimiteDesconto;								
							document.formulario.DataLimiteDesconto.value				= '';
							document.formulario.DataPrimeiroVencimento.value			= dateFormat(DataPrimeiroVencimento);
							document.formulario.ValorPrimeiroVencimento.value			= formata_float(Arredonda(ValorPrimeiroVencimento,2),2).replace(".",",");
							document.formulario.IdProcessoFinanceiro.value 				= IdProcessoFinanceiro;
							document.formulario.Acao.value 								= 'alterar';
							
							var PercDesconto = 0;
							
							if(ValorDesconto > 0){
								var ValorTotal	=	parseFloat(ValorContaReceber) + parseFloat(ValorMulta) + parseFloat(ValorJuros) + parseFloat(ValorOutrasDespesasVencimento) + parseFloat(ValorTaxaReImpressaoBoleto);
								PercDesconto	=	(parseFloat(ValorDesconto)*100)/parseFloat(ValorTotal);
							}
							
							if(ValorDescontoAConceber > 0){
								var perc	=	(parseFloat(ValorDescontoAConceber)*100)/parseFloat(ValorFinal);
								
								document.getElementById("titLimiteDesconto").style.display			=	"block";
								document.getElementById("cpLimiteDesconto").style.display			=	"block";
								document.getElementById("titDataLimiteDesconto").style.display		=	"block";
								document.getElementById("cpDataLimiteDesconto").style.display		=	"block";
								document.getElementById("imgDataLimiteDesconto").style.display		=	"block";
								document.getElementById("titManterDescontoAConceber").style.display	=	"block";
								document.formulario.ManterDescontoAConceber.style.display			=	"block";
							
								if(LimiteDesconto != ''){
									document.formulario.DataLimiteDesconto.value	=	dateFormat(DataLimiteDesconto);
								}
							}else{
								var perc	=	0;
								
								document.getElementById("titLimiteDesconto").style.display			=	"none";
								document.getElementById("cpLimiteDesconto").style.display			=	"none";
								document.getElementById("titDataLimiteDesconto").style.display		=	"none";
								document.getElementById("cpDataLimiteDesconto").style.display		=	"none";
								document.getElementById("imgDataLimiteDesconto").style.display		=	"none";
								document.getElementById("titManterDescontoAConceber").style.display	=	"none";
								document.formulario.ManterDescontoAConceber.style.display			=	"none";
								document.formulario.ManterDescontoAConceber.value					=	"";
							}
							
							if(ValorTaxaReImpressaoBoletoLocalCobranca == 0 || ValorTaxaReImpressaoBoletoLocalCobranca==""){
								document.formulario.TaxaReimpressao.value 		=	2;
							}else{
								if(document.formulario.ValorTaxaReImpressaoDefault.value == 1){
									ValorFinal	=	parseFloat(ValorFinal)+parseFloat(ValorTaxaReImpressaoBoletoLocalCobranca);
								}
							}							
							
							document.formulario.ValorFinalVencimento.value			= formata_float(Arredonda(ValorPrimeiroVencimento,2),2).replace(".",",");
							document.formulario.ValorDescontoAConceber.value		= formata_float(Arredonda(ValorDescontoAConceber,2),2).replace(".",",");
							document.formulario.PercentualDesconto.value			= formata_float(Arredonda(perc,2),2).replace(".",",");
							document.formulario.ValorPercentual.value 				= formata_float(Arredonda(PercDesconto,2),2).replace(".",","); 
							
							if(document.formulario.TaxaReimpressao.value == 1){
								document.formulario.ValorTaxaReImpressaoBoleto.value	= formata_float(Arredonda(ValorTaxaReImpressaoBoletoLocalCobranca,2),2).replace(".",",");
							}
							
							for(var i=0; i<document.formulario.IdLocalCobranca.length; i++){
								if(document.formulario.IdLocalCobranca[i].value == IdLocalCobranca){
									document.formulario.IdLocalCobranca[i].selected = true;
									i = document.formulario.IdLocalCobranca.length;
								}							
							}
							
							var pMulta	=	formata_float(Arredonda(PercentualMulta,3),3).replace(".",",");
							var pJuros	=	formata_float(Arredonda(PercentualJurosDiarios,3),3).replace(".",",");
							
							document.getElementById('titMultaJuros').innerHTML	=	'Multa:'+pMulta+'%.  Juros:'+pJuros+'%';
							document.getElementById('titTaxa').innerHTML		=	document.formulario.Moeda.value+':'+document.formulario.ValorTaxaReImpressaoBoletoLocalCobranca.value;
							
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
							addParmUrl("marReenvioMensagem","IdContaReceber",IdContaReceber);
							//addParmUrl("marReenvioMensagem","IdPessoa",IdPessoa);

							//Recebido 
							busca_lancamento_financeiro(IdContaReceber,false);
							busca_pessoa(IdPessoa,false,document.formulario.Local.value);
							listarVencimento(IdContaReceber,false);
							scrollWindow('top');
							statusInicial();
							
							if(IdTipoLocalCobranca == 3){
								document.getElementById('cpPosicaoCobranca').style.display		= 'block';
							}else{
								document.getElementById('cpPosicaoCobranca').style.display		= 'none';
							}
							
							document.getElementById('cp_Status').innerHTML			= valor_cp_Status;
							document.getElementById('cp_Status').style.display		= 'block';
							document.getElementById('cp_Status').style.color		= Cor;
							break;
						case 'Etiqueta':
							nameNode = xmlhttp.responseXML.getElementsByTagName("Nome")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var Nome = nameTextNode.nodeValue;
							
							document.formulario.IdContaReceber.value 				= IdContaReceber;
							document.formulario.NomeContaReceber.value 				= Nome;
							document.formulario.NumeroDocumento.value 				= NumeroDocumento;
							document.formulario.IdLocalCobrancaContaReceber.value 	= IdLocalCobranca;
							document.formulario.DataLancamento.value 				= dateFormat(DataLancamento);
							break;						
						case 'AdicionarContaReceber':
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("Nome")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var Nome = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("RazaoSocial")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var RazaoSocial = nameTextNode.nodeValue;
							
							if(Nome != ""){
								var NomePessoaContaReceber = Nome;								
								document.getElementById("cp_ContaReceberPessoa").innerHTML = "Nome Pessoa";
							}
							if(RazaoSocial != ""){
								var NomePessoaContaReceber = RazaoSocial;
								document.getElementById("cp_ContaReceberPessoa").innerHTML = "Razão Social";								
							}	
							
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
						
								var linkIni = "<a href='cadastro_conta_receber.php?IdContaReceber="+IdContaReceber+"'>";
								var linkFim = "</a>";
																
								c0.innerHTML = linkIni + IdContaReceber + linkFim;
								c0.style.padding =	"0 0 0 5px";
								
								c1.innerHTML = linkIni + NomePessoaContaReceber.substr(0,20) + linkFim;
								
								c2.innerHTML = linkIni + dateFormat(DataVencimento) + linkFim;
								
								c3.innerHTML = linkIni + NumeroDocumento + linkFim;
																
								c4.innerHTML = "<img src='../../img/estrutura_sistema/ico_del.gif' alt='Excluir?' onClick=\"remover_filtro_conta_receber("+IdContaReceber+")\"></tr>";
								c4.style.textAlign = "center";
								c4.style.cursor = "pointer";
								
								if(document.formulario.IdProcessoNotaFiscal.value == ''){
									document.getElementById('totaltabelaContaReceber').innerHTML	=	'Total: '+(ii+1);
								}
							}
							
							document.formulario.IdContaReceber.value				= "";
							document.formulario.NomePessoaContaReceber.value		= "";
							document.formulario.DataVencimentoContaReceber.value	= "";
							document.formulario.NumeroDocumentoContaReceber.value	= "";
							
							break;		
						case 'AgruparContaReceber':
							nameNode = xmlhttp.responseXML.getElementsByTagName("Nome")[0];
							nameTextNode = nameNode.childNodes[0];
							var Nome = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("RazaoSocial")[0];
							nameTextNode = nameNode.childNodes[0];
							var RazaoSocial = nameTextNode.nodeValue;
							
							if(Nome != ""){
								var NomePessoaContaReceber = Nome;
								document.getElementById("tit_ContaReceberPessoa").innerHTML = "Nome Pessoa";
							}
							
							if(RazaoSocial != ""){
								var NomePessoaContaReceber = RazaoSocial;
								document.getElementById("tit_ContaReceberPessoa").innerHTML = "Razão Social";
							}
							
							document.formulario.IdContaReceber.value			= IdContaReceber;
							document.formulario.NomePessoa.value				= NomePessoaContaReceber;
							document.formulario.IdStatus.value					= IdStatus;
							document.formulario.bt_add_conta_receber.disabled	= false;
							
							//limparNovoVencimento();
							
							document.formulario.bt_add_conta_receber.focus();
							break;	
						case 'Protocolo':
							nameNode = xmlhttp.responseXML.getElementsByTagName("Nome")[0];
							nameTextNode = nameNode.childNodes[0];
							var Nome = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("RazaoSocial")[0];
							nameTextNode = nameNode.childNodes[0];
							var RazaoSocial = nameTextNode.nodeValue;
							
							if(Nome != ""){
								var NomePessoaContaReceber = Nome;
								document.getElementById("tit_ContaReceberPessoa").innerHTML = "Nome Pessoa";
							}
							
							if(RazaoSocial != ""){
								var NomePessoaContaReceber = RazaoSocial;
								document.getElementById("tit_ContaReceberPessoa").innerHTML = "Razão Social";
							}
							
							document.formulario.IdContaReceber.value			= IdContaReceber;
							document.formulario.NomePessoaContaReceber.value	= NomePessoaContaReceber;
							document.formulario.IdStatusContaReceber.value		= IdStatus;
							break;
						case 'AdicionarAgruparContaReceber':
							nameNode = xmlhttp.responseXML.getElementsByTagName("Nome")[0];
							nameTextNode = nameNode.childNodes[0];
							var Nome = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("RazaoSocial")[0];
							nameTextNode = nameNode.childNodes[0];
							var RazaoSocial = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoLocalCobranca")[0];
							nameTextNode = nameNode.childNodes[0];
							var DescricaoLocalCobranca = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("AbreviacaoNomeLocalCobranca")[0];
							nameTextNode = nameNode.childNodes[0];
							var AbreviacaoNomeLocalCobranca = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("TipoLancamentoFinanceiro")[0];
							nameTextNode = nameNode.childNodes[0];
							var TipoLancamentoFinanceiro = nameTextNode.nodeValue;
							
							if(Nome != ""){
								var NomePessoaContaReceber = Nome;
							}
							
							if(RazaoSocial != ""){
								var NomePessoaContaReceber = RazaoSocial;
							}
							
							var cont = 0; ii='';
							
							if(ListarCampo == '' || ListarCampo == undefined){
								if(document.formulario.IdContaReceberAgrupados.value == ''){
									document.formulario.IdContaReceberAgrupados.value = IdContaReceber;
									ii = 0;
								} else{
									var tempIdContaReceberAgrupados = document.formulario.IdContaReceberAgrupados.value.split(',');
									ii = 0;
									
									while(tempIdContaReceberAgrupados[ii] != undefined){
										if(tempIdContaReceberAgrupados[ii] != IdContaReceber){
											cont++;
										}
										
										ii++;
									}
									
									if(ii == cont){
										document.formulario.IdContaReceberAgrupados.value = document.formulario.IdContaReceberAgrupados.value + "," + IdContaReceber;
									}
								}
							} else{
								ii=0;
							}
							
							if(ii == cont){
								var tam, linha, c0, c1, c2, c3, c4, c5, c6, c7, c8, c9, c10;
								
								tam		= document.getElementById('tabelaContaReceber').rows.length;
								linha	= document.getElementById('tabelaContaReceber').insertRow(tam-1);
								
								if(tam%2 != 0){
									linha.style.backgroundColor = "#E2E7ED";
								}
								
								linha.accessKey = IdContaReceber;
								linha.innerHTML += "<input type='hidden' name='Valor_"+IdContaReceber+"' value='"+ValorReceber+"' />";
								linha.innerHTML += "<input type='hidden' name='DataVencimento_"+IdContaReceber+"' value='"+dateFormat(DataVencimento)+"' />";
								
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
								c10	= linha.insertCell(10);
								
								var linkIni = "<a href='cadastro_conta_receber.php?IdContaReceber="+IdContaReceber+"' target='_blank'>";
								var linkFim = "</a>";
								
								c0.innerHTML = linkIni + IdContaReceber + linkFim;
								c0.style.padding = "0 0 0 5px";
								
								c1.innerHTML = linkIni + NomePessoaContaReceber.substr(0,20) + linkFim;
								
								c2.innerHTML = linkIni + TipoLancamentoFinanceiro + linkFim;
								
								c3.innerHTML = linkIni + NumeroDocumento + linkFim;
								
								c4.innerHTML = linkIni + NumeroNF + linkFim;
								
								c5.innerHTML = linkIni + AbreviacaoNomeLocalCobranca + linkFim;
								
								c6.innerHTML = linkIni + dateFormat(DataLancamento) + linkFim;
								
								c7.innerHTML = linkIni + "<span>" + (formata_float(Arredonda(ValorReceber,2),2).replace(/\./, ',')) + "</span>" + linkFim;
								c7.className = "valor";
								
								c8.innerHTML = linkIni + dateFormat(DataVencimento) + linkFim;
								
								c9.innerHTML = linkIni + Status + linkFim;
								
								if(Number(document.formulario.Buca.value) == 0){
									c10.innerHTML = "<img src='../../img/estrutura_sistema/ico_del.gif' alt='Excluir?' onClick=\"remover_conta_receber_agrupado("+IdContaReceber+")\">";
									c10.style.cursor = "pointer";
								} else{
									c10.innerHTML = "<img src='../../img/estrutura_sistema/ico_del_c.gif' alt='Excluir?'>";
									c10.style.cursor = "default";
								}
								
								c10.style.textAlign = "center";
								
								var temp = parseFloat((document.getElementById('totalValorTabelaContaReceber').innerHTML.replace(/\./g, '')).replace(/,/i, '.'));
								temp = parseFloat(ValorReceber) + parseFloat(temp);
								
								if(isNaN(temp)){
									temp = 0.00;
								}
								
								document.getElementById('totaltabelaContaReceber').innerHTML = 'Total: '+(ii+1);
								document.getElementById('totalValorTabelaContaReceber').innerHTML = (formata_float(Arredonda(temp,2),2).replace(/\./i, ','));
							}
							
							document.formulario.IdContaReceber.value			= '';
							document.formulario.NomePessoa.value				= '';
							document.formulario.IdStatus.value					= '';
							document.formulario.bt_add_conta_receber.disabled	= true;
							document.formulario.ValorVencimento.value			= (formata_float(Arredonda(temp,2),2).replace(/\./i, ','));
							
							calcula_valor();
							document.formulario.IdContaReceber.focus();
							break;
						case "Movimentacao":
							nameNode = xmlhttp.responseXML.getElementsByTagName("Nome")[0];
							nameTextNode = nameNode.childNodes[0];
							var Nome = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("RazaoSocial")[0];
							nameTextNode = nameNode.childNodes[0];
							var RazaoSocial = nameTextNode.nodeValue;
							
							var Item = document.formulario.ManipulaItem.value;
							
							if(Nome != ""){
								var NomePessoa = Nome;
								document.getElementById("tit_Pessoa_"+Item).innerHTML = "Nome Pessoa";
							}
							
							if(RazaoSocial != ""){
								var NomePessoa = RazaoSocial;
								document.getElementById("tit_Pessoa_"+Item).innerHTML = "Razão Social";
							}
							
							ValorContaReceber = parseFloat(ValorContaReceber.replace(/,/g, "."));
							
							if(isNaN(ValorContaReceber)){
								ValorContaReceber = 0.00;
							}
							
							ValorMulta = parseFloat(ValorMulta.replace(/,/g, "."));
							
							if(isNaN(ValorMulta)){
								ValorMulta = 0.00;
							}
							
							ValorJuros = parseFloat(ValorJuros.replace(/,/g, "."));
							
							if(isNaN(ValorJuros)){
								ValorJuros = 0.00;
							}
							
							ValorDesconto = parseFloat(ValorDesconto.replace(/,/g, "."));
							
							if(isNaN(ValorDesconto)){
								ValorDesconto = 0.00;
							}
							
							ValorFinal = parseFloat(ValorFinal.replace(/,/g, "."));
							
							if(isNaN(ValorFinal)){
								ValorFinal = 0.00;
							}
							
							var TotalValor			= parseFloat(document.formulario.TotalValor.value.replace(/,/g, ".")) + ValorContaReceber;
							var TotalValorMulta		= parseFloat(document.formulario.TotalValorMulta.value.replace(/,/g, ".")) + ValorMulta;
							var TotalValorJuros		= parseFloat(document.formulario.TotalValorJuros.value.replace(/,/g, ".")) + ValorJuros;
							var TotalValorDesconto	= parseFloat(document.formulario.TotalValorDesconto.value.replace(/,/g, ".")) + ValorDesconto;
							var TotalValorFinal		= parseFloat(document.formulario.TotalValorFinal.value.replace(/,/g, ".")) + ValorFinal;
							
							eval("var ValorDescontoTemp = parseFloat(document.formulario.ValorDescontoItem_"+Item+".value.replace(/,/g, '.'));");
							
							if(isNaN(ValorDescontoTemp)){
								ValorDescontoTemp = 0.00;
							}
							
							TotalValorDesconto -= ValorDescontoTemp;
							
							document.formulario.TotalValor.value			= formata_float(Arredonda(TotalValor, 2), 2).replace(/\./g, ",");
							document.formulario.TotalValorMulta.value		= formata_float(Arredonda(TotalValorMulta, 2), 2).replace(/\./g, ",");
							document.formulario.TotalValorJuros.value		= formata_float(Arredonda(TotalValorJuros, 2), 2).replace(/\./g, ",");
							document.formulario.TotalValorDesconto.value	= formata_float(Arredonda(TotalValorDesconto, 2), 2).replace(/\./g, ",");
							document.formulario.TotalValorFinal.value		= formata_float(Arredonda(TotalValorFinal, 2), 2).replace(/\./g, ",");
							
							eval("document.formulario.IdContaReceberItem_"+Item+".value = '"+IdContaReceber+"';");
							eval("document.formulario.NumeroDocumentoItem_"+Item+".value = '"+NumeroDocumento+"';");
							eval("document.formulario.NomePessoaItem_"+Item+".value = '"+NomePessoa+"';");
							eval("document.formulario.DataVencimentoItem_"+Item+".value = '"+dateFormat(DataVencimento)+"';");
							eval("document.formulario.ValorItem_"+Item+".value = '"+formata_float(Arredonda(ValorContaReceber, 2), 2).replace(/\./g, ",")+"';");
							eval("document.formulario.ValorMultaItem_"+Item+".value = '"+formata_float(Arredonda(ValorMulta, 2), 2).replace(/\./g, ",")+"';");
							eval("document.formulario.ValorJurosItem_"+Item+".value = '"+formata_float(Arredonda(ValorJuros, 2), 2).replace(/\./g, ",")+"';");
							eval("document.formulario.ValorDescontoItem_"+Item+".value = '"+formata_float(Arredonda(ValorDesconto, 2), 2).replace(/\./g, ",")+"';");
							eval("document.formulario.ValorFinalItem_"+Item+".value = '"+formata_float(Arredonda(ValorFinal, 2), 2).replace(/\./g, ",")+"';");
							break;
						default:
							nameNode = xmlhttp.responseXML.getElementsByTagName("IdPessoaEndereco")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var IdPessoaEndereco = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("ValorTaxaReImpressaoBoleto")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var ValorTaxaReImpressaoBoleto = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("PosicaoCobranca")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var PosicaoCobranca = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("IdNotaFiscalTipo")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var IdNotaFiscalTipo = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("ValorLancamento")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var ValorLancamento = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("IdStatusConfirmacaoPagamento")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var IdStatusConfirmacaoPagamento = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("StatusPagamento")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var StatusPagamento = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("IdTipoLocalCobrancaRecebimento")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var IdTipoLocalCobrancaRecebimento = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("DataAtual")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var DataAtual = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("DataVencimento")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var DataVencimento = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("SerieNF")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var SerieNF = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("BaseCalculoNF")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var BaseCalculoNF = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("IdStatusNF")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var IdStatusNF = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("IdCartao")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var IdCartao = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("IdContaDebito")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var IdContaDebito = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("NossoNumero")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var NossoNumero = nameTextNode.nodeValue;
							
							while(document.getElementById('tabelaLancFinanceiro').rows.length > 2){
								document.getElementById('tabelaLancFinanceiro').deleteRow(1);
							}
							
							while(document.getElementById('tabelaParametro').rows.length > 0){
								document.getElementById('tabelaParametro').deleteRow(0);
							}
							
							while(document.getElementById('tabelaRecebimentos').rows.length > 2){
								document.getElementById('tabelaRecebimentos').deleteRow(1);
							}
			
							document.formulario.IdPessoa.value 						= IdPessoa;
							document.formulario.IdContaReceber.value 				= IdContaReceber;
							document.formulario.IdProcessoFinanceiro.value 			= IdProcessoFinanceiro;
							document.formulario.IdStatusConfirmacaoPagamento.value	= IdStatusConfirmacaoPagamento;
							document.formulario.NumeroDocumento.value 				= NumeroDocumento;
							document.formulario.DataLancamento.value 				= dateFormat(DataLancamento);
							document.formulario.DataVencimento.value 				= dateFormat(DataVencimento);
							document.formulario.LoginAlteracao.value				= LoginAlteracao;
							document.formulario.BaseVencimento.value				= BaseVencimento;
							document.formulario.PercentualMulta.value				= PercentualMulta;
							document.formulario.PercentualJurosDiarios.value		= PercentualJurosDiarios;
							document.formulario.IdStatus.value						= IdStatus;
							document.formulario.IdLocalCobranca.value 				= IdLocalCobranca;
							document.formulario.IdLocalCobrancaTemp.value 			= IdLocalCobranca;
							document.formulario.IdLocalRecebimento[0].selected		= true;
							document.formulario.IdLocalRecebimento.disabled			= false;
							document.formulario.Obs.value 							= "";
							document.formulario.IdStatusRecebimento.value			= "";
							document.formulario.HistoricoObs.value 					= Obs;
							document.formulario.NumeroNF.value 						= NumeroNF;
							document.formulario.SerieNF.value 						= SerieNF;
							document.formulario.BaseNF.value 						= BaseCalculoNF.replace(".",",");
							document.formulario.IdTipoLocalCobranca.value 			= IdTipoLocalCobranca;
							document.formulario.DataNF.value 						= dateFormat(DataNF);
							document.formulario.ModeloNF.value 						= ModeloNF;
							document.formulario.ModeloNFTemp.value 					= ModeloNF;
							document.formulario.StatusNF.value 						= IdStatusNF;
							document.formulario.DataCriacao.value 					= dateFormat(DataCriacao);
							document.formulario.LoginCriacao.value 					= LoginCriacao;
							document.formulario.DataAlteracao.value 				= dateFormat(DataAlteracao);
							document.formulario.IdPosicaoCobranca.value				= IdPosicaoCobranca;
							document.formulario.LimiteDesconto.value				= LimiteDesconto;
							document.formulario.DataLimiteDesconto.value			= '';
							document.formulario.ValorContaReceber.value				= formata_float(Arredonda(ValorContaReceber,2),2).replace(".",",");
							document.formulario.ValorDespesas.value					= formata_float(Arredonda(ValorDespesas,2),2).replace(".",",");
							document.formulario.ValorMulta.value					= formata_float(Arredonda(ValorMulta,2),2).replace(".",",");
							document.formulario.ValorJuros.value					= formata_float(Arredonda(ValorJuros,2),2).replace(".",",");
							document.formulario.ValorDesconto.value 				= formata_float(Arredonda(ValorDesconto,2),2).replace(".",",");
							document.formulario.ValorFinal.value					= formata_float(Arredonda(ValorFinal,2),2).replace(".",",");
							document.formulario.ValorOutrasDesp.value				= formata_float(Arredonda(ValorOutrasDespesasVencimento,2),2).replace(".",",");
							document.formulario.ValorTaxa.value						= formata_float(Arredonda(ValorTaxaReImpressaoBoleto,2),2).replace(".",",");
							document.formulario.DataAtual.value						= DataAtual;
							document.formulario.NumeroCartaoCredito.value			= IdCartao;
							document.formulario.DataVencimentoTemp.value			= DataVencimento;
							document.formulario.IdPessoaEnderecoTemp.value			= IdPessoaEndereco;
							document.formulario.NossoNumero.value					= NossoNumero;
							document.formulario.Acao.value;
							
							
							switch(IdTipoLocalCobranca){
								case '3':
									document.formulario.NumeroCartaoCredito.style.display = "block";
									document.getElementById("titNumeroContaDebito").style.display = "block";
									document.getElementById("cpNumeroContaDebito").style.display = "block";
									if(IdStatus != 2 && IdStatus != 0){
										document.formulario.IdLocalCobranca.disabled = false;
										document.getElementById("LocalCobracaLabel").style.color = "#C10000";
									}
									document.formulario.ObrigatoriedadeNumeroContaDebito.value = 1;
									busca_conta_debito(IdPessoa,IdContaDebito,IdStatus);
									document.getElementById('cpPosicaoCobranca').style.display = 'block';
									document.getElementById('titPosicaoCobranca').style.display = 'block';
									document.formulario.IdPosicaoCobranca.style.display = 'block';
									break;
								case '4':
									document.getElementById('cpPosicaoCobranca').style.display = 'block';
									document.getElementById('titPosicaoCobranca').style.display = 'block';
									document.formulario.IdPosicaoCobranca.style.display = 'block';
									break;
								case '6':
									document.formulario.IdContaDebito.style.display = "block";
									document.getElementById("titNumeroCartaoCredito").style.display = "block";
									document.getElementById("cpNumeroCartaoCredito").style.display = "block";
									if(IdStatus != 2 || IdStatus != 0){
										document.formulario.IdLocalCobranca.disabled = true;
										document.getElementById("LocalCobracaLabel").style.color = "#000";
									}
									document.formulario.ObrigatoriedadeNumeroCartao.value = 1;
									busca_cartao(IdPessoa,IdCartao,IdStatus);
									document.getElementById('cpPosicaoCobranca').style.display = 'block';
									document.getElementById('titPosicaoCobranca').style.display = 'block';
									document.formulario.IdPosicaoCobranca.style.display = 'block';
									break;
								default:
									document.formulario.NumeroCartaoCredito.style.display = "none";
									document.getElementById("titNumeroCartaoCredito").style.display = "none";
									document.getElementById("cpNumeroCartaoCredito").style.display = "none";
									document.getElementById("titNumeroContaDebito").style.display = "none";
									document.getElementById("cpNumeroContaDebito").style.display = "none";
									if(IdStatus != 2 || IdStatus != 0){
										document.formulario.IdLocalCobranca.disabled = true;
										document.getElementById("LocalCobracaLabel").style.color = "#000";
									}
									document.formulario.ObrigatoriedadeNumeroCartao.value = "";
									document.formulario.ObrigatoriedadeNumeroContaDebito.value = "";
									document.getElementById('titPosicaoCobranca').style.display = 'none';
									document.getElementById('cpPosicaoCobranca').style.display = 'none';
									document.formulario.IdPosicaoCobranca.style.display = 'none';
									break;
							
							}
							
							if(document.formulario.CaixaAtivado.value != '1'){
								document.getElementById("cp_recebimento").className = null;
							} else {
								document.getElementById("cp_recebimento").className = "ocultar-bl";
							}
							
							var pMulta = formata_float(Arredonda(PercentualMulta,3),3).replace(".",",");
							var pJuros = formata_float(Arredonda(PercentualJurosDiarios,3),3).replace(".",",");
							
							if(document.getElementById('titMultaJuros') != undefined){
								document.getElementById('titMultaJuros').innerHTML = 'Multa:'+pMulta+'%.  Juros:'+pJuros+'%';
							}
							
							if(document.formulario.IdContaReceberAgrupador != undefined){
								document.formulario.IdContaReceberAgrupador.value = IdContaReceberAgrupador;
							}
							if(IdNotaFiscalTipo != ""){
								document.formulario.bt_nota_fiscal.disabled = false;
							}
							
							var ValorTotal = parseFloat(ValorContaReceber) + parseFloat(ValorMulta) + parseFloat(ValorJuros) + parseFloat(ValorTaxaReImpressaoBoleto) + parseFloat(ValorOutrasDespesasVencimento);
							
							if(ValorDesconto > 0){
								var percdesconto = (parseFloat(ValorDesconto)*100)/parseFloat(ValorTotal);
							} else{
								var percdesconto = 0;
							}
							
							if(ValorDescontoAConceber > 0){
								var perc = (parseFloat(ValorDescontoAConceber)*100)/parseFloat(ValorLancamento);
								
								document.getElementById("titLimiteDesconto").style.display		= "block";
								document.getElementById("cpLimiteDesconto").style.display		= "block";
								document.getElementById("titDataLimiteDesconto").style.display	= "block";
								document.getElementById("cpDataLimiteDesconto").style.display	= "block";
								
								if(LimiteDesconto != ''){
									document.formulario.DataLimiteDesconto.value = dateFormat(DataLimiteDesconto);
								}
							} else{
								var perc = 0;
								
								document.getElementById("titLimiteDesconto").style.display		= "none";
								document.getElementById("cpLimiteDesconto").style.display		= "none";
								document.getElementById("titDataLimiteDesconto").style.display	= "none";
								document.getElementById("cpDataLimiteDesconto").style.display	= "none";
							}
							
							document.formulario.ValorMoraMulta.value 			= "0,00";
							document.formulario.ValorDescontoRecebimento.value	= "0,00";
							document.formulario.ValorOutrasDespesas.value		= "0,00";
							document.formulario.ValorRecebimento.value			= formata_float(Arredonda(ValorFinal,2),2).replace(".",",");
							document.formulario.ValorDescontoAConceber.value	= formata_float(Arredonda(ValorDescontoAConceber,2),2).replace(".",",");
							document.formulario.ValorPercentual.value			= formata_float(Arredonda(percdesconto,2),2).replace(".",",");
							document.formulario.PercentualDesconto.value		= formata_float(Arredonda(perc,2),2).replace(".",",");
							
							for(var i = 0; i<document.formulario.IdLocalCobranca.length; i++){
								if(document.formulario.IdLocalCobranca[i].value == IdLocalCobranca){
									document.formulario.IdLocalCobranca[i].selected = true;
									i = document.formulario.IdLocalCobranca.length;
								}
							}
							
							if(Obs!=""){
								document.getElementById('cpHistorico').style.display = 'block';
							} else{
								document.getElementById('cpHistorico').style.display = 'none';
							}
							
							addParmUrl("marProcessoFinanceiro","IdProcessoFinanceiro",IdProcessoFinanceiro);
							addParmUrl("marPessoa","IdContaReceber",IdContaReceber);
							addParmUrl("marPessoa","IdPessoa",IdPessoa);
							addParmUrl("marPessoaNovo","IdContaReceber",IdContaReceber);
							addParmUrl("marContrato","IdPessoa",IdPessoa);
							addParmUrl("marContratoNovo","IdPessoa",IdPessoa);
							addParmUrl("marContrato","IdContaReceber",IdContaReceber);
							addParmUrl("marContratoNovo","IdContaReceber",IdContaReceber);
							addParmUrl("marContasReceber","IdPessoa",IdPessoa);
							addParmUrl("marLancamentoFinanceiro","IdContaReceber",IdContaReceber);
							addParmUrl("marLancamentoFinanceiro","IdPessoa",IdPessoa);
							addParmUrl("marProcessoFinanceiro","IdContaReceber",IdContaReceber);
							addParmUrl("marProcessoFinanceiro","IdPessoa",IdPessoa);
							addParmUrl("marContaEventual","IdPessoa",IdPessoa);
							addParmUrl("marContaEventualNovo","IdPessoa",IdPessoa);
							addParmUrl("marReenvioMensagem","IdContaReceber",IdContaReceber);
							//addParmUrl("marReenvioMensagem","IdPessoa",IdPessoa);
							
							verifica_nota_fiscal(SerieNF);
							busca_obs_nota_fiscal(IdContaReceber);
							busca_lancamento_financeiro(IdContaReceber,false);
							listarParametro(IdLocalRecebimento,false);
							busca_pessoa(IdPessoa,false,document.formulario.Local.value);
							listarContaReceberParametroRecebimento(IdContaReceber,IdLocalRecebimento,false);
							listarRecebimento(IdContaReceber,false);
							listarPosicaoCobranca(IdContaReceber,Erro);
							busca_opcoes_pessoa_endereco(IdPessoa,IdPessoaEndereco);
							busca_pessoa_endereco(IdPessoa,IdPessoaEndereco);
							busca_posicao_cobranca(IdStatus,IdPosicaoCobranca,IdContaReceber, IdTipoLocalCobranca,DataVencimento,DataAtual);
							statusInicial();
							scrollWindow('top');
							calculaValor('ContaRecebimento');
							
							if(IdStatus != 2){
								document.formulario.NumeroCartaoCredito.readOnly = false;
							}else{
								document.formulario.NumeroCartaoCredito.readOnly = true;
							}
							
							/*if(OcultarBlocoNotafiscal != 0){
								document.getElementById("blNotaFiscal").style.display = "none";
							} else{*/
								document.getElementById("blNotaFiscal").style.display = "block";
							//}
							//-> Leonardo - 17-04-13 - Bagunça a visualização do  campo Nova Posição de Cobrança
							/*if(IdTipoLocalCobranca == 3 || IdTipoLocalCobranca == 4 || IdTipoLocalCobranca == 6 || (IdPosicaoCobranca == 4 && IdTipoLocalCobrancaRecebimento == 1)){
								document.getElementById('cpPosicaoCobranca').style.display	= 'block';
								document.getElementById('titPosicaoCobranca').style.display	= 'block';
							} else{
								document.getElementById('titPosicaoCobranca').style.display	= 'none';
								document.getElementById('cpPosicaoCobranca').style.display	= 'none';
							}*/
							
							document.getElementById('cp_Status').innerHTML			= valor_cp_Status;
							document.getElementById('cp_Status').style.display		= 'block';
							document.getElementById('cp_Status').style.color		= Cor;
							
							verificaAcao();
							
							if(Boleto == 'true'){
								document.formulario.bt_imprimir1.disabled = false;
							} else{
								document.formulario.bt_imprimir1.disabled = true;
							}
							break;
					}
				}
				
				if(document.getElementById("quadroBuscaContaReceber") != null){
					if(document.getElementById("quadroBuscaContaReceber").style.display == "block"){
						document.getElementById("quadroBuscaContaReceber").style.display = "none";
					}
				}
			}
		});
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
		
		var Local	=	document.formulario.Local.value;
		var url = "xml/demonstrativo_conta_receber.php?IdContaReceber="+IdContaReceber;
		
		call_ajax(url, function(xmlhttp){ 
			var nameNode, nameTextNode;
			
			if(Erro != false){
				document.formulario.Erro.value = 0;
				verificaErro();
			}
			
			if(xmlhttp.responseText == 'false'){
				document.getElementById('cp_lancamentos_financeiros').style.display	=	'none';						
				document.getElementById('tabelaLancFinanceiroTotalValor').innerHTML	=	"0,00";	
				document.getElementById('tabelaLancFinanceiroTotal').innerHTML		=	"Total: 0";	
			}else{
				document.getElementById('cp_lancamentos_financeiros').style.display	=	'block';	
				var tam, linha, c0, c1, c2, c3, c4;
				var IdLancamentoFinanceiro,Tipo,Valor,Codigo,Descricao,Referencia,TotalValor=0,IdPessoa, linkIni, linkFim;
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
							linkFim = "</a>";
							break;
						case 'EV':
							linkIni	= "<a href='cadastro_conta_eventual.php?IdContaEventual="+Codigo+"'>";
							linkFim = "</a>";
							break;
						case 'OS':
							linkIni	= "<a href='cadastro_ordem_servico.php?IdOrdemServico="+Codigo+"'>";
							linkFim = "</a>";
							break;
						case 'CR':
							Codigo = IdContaReceber;
							linkIni	= "<a href='cadastro_conta_receber.php?IdContaReceber="+Codigo+"'>";
							linkFim = "</a>";
							break;
						case 'EF':
							linkIni	= "<a href='cadastro_conta_receber.php?IdContaReceber="+Codigo+"'>";
							linkFim = "</a>";
							break;
					}
					
					c0.innerHTML = linkIni + Tipo + linkFim;
					c0.style.padding  =	"0 0 0 5px";
					
					c1.innerHTML = linkIni + Codigo + linkFim;
					c1.style.cursor = "pointer";
					
					c2.innerHTML = Descricao;
					
					c3.innerHTML = Referencia;
					
					c4.innerHTML =  formata_float(Arredonda(Valor,2),2).replace('.',',');
					c4.style.textAlign = "right";
					c4.style.padding  =	"0 8px 0 0";
					
					if(i == 0 && Local=='OrdemServico'){
						switch(Tipo){
							case 'CO':
								busca_pessoa_endereco(document.formulario.IdPessoa.value,Tipo,Codigo);
								break;
							case 'EV':
								busca_pessoa_endereco(document.formulario.IdPessoa.value,Tipo,Codigo);
								break;
							case 'OS':
								busca_pessoa_endereco(document.formulario.IdPessoa.value,Tipo,Codigo);
								break;
						}
					}
				}
				
				document.getElementById('tabelaLancFinanceiroTotalValor').innerHTML	=	formata_float(Arredonda(TotalValor,2),2).replace('.',',');	
				document.getElementById('tabelaLancFinanceiroTotal').innerHTML		=	"Total: "+i;	
			}
			
			if(window.janela != undefined){
				window.janela.close();
			}
		});
	}
	
	function listarParametro(IdLocalRecebimento,Erro){
		if(IdLocalRecebimento == '' || IdLocalRecebimento == undefined){
			IdLocalRecebimento = 0;
		}
		
		while(document.getElementById('tabelaParametro').rows.length > 0){
			document.getElementById('tabelaParametro').deleteRow(0);
		}		
		
		if(IdLocalRecebimento == ''){
			IdLocalRecebimento = 0;
		}
		
	    var url = "xml/parametro_recebimento.php?IdLocalRecebimento="+IdLocalRecebimento;
		
		call_ajax(url, function(xmlhttp){
			var nameNode, nameTextNode, tam, linha, c0, c1, c2, c3, c4, c5;
			
			if(xmlhttp.responseText == 'false'){
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
		});
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
		
		var posInicial,posFinal;
		
		for(i=0;i<document.formulario.length;i++){
			if(document.formulario[i].name != undefined){
				if(document.formulario[i].name == 'ValorReceber'){
					posInicial = i;
				}
			}
		}
		
		var url = "xml/conta_receber_recebimento_parametro.php?IdContaReceber="+IdContaReceber+"&IdLocalRecebimento="+IdLocalRecebimento;
		
		call_ajax(url, function(xmlhttp){ 
			var nameNode, nameTextNode;
			
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
		});
	}
	function buscar_conta_receber(IdRecibo,Erro,Local,ListarCampo){
		if(IdRecibo == '' || IdRecibo == undefined){
			IdRecibo = 0;
		}
		
		var url = "xml/conta_receber_recebimento.php?IdRecibo="+IdRecibo;
		
		call_ajax(url, function(xmlhttp){ 
			var nameNode, nameTextNode;
			
			if(xmlhttp.responseText != 'false'){
				nameNode = xmlhttp.responseXML.getElementsByTagName("IdContaReceber")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var IdContaReceber = nameTextNode.nodeValue;
				
				busca_conta_receber(IdContaReceber,false,document.formulario.Local.value);
				scrollWindow('bottom');
			}
		});
	}
