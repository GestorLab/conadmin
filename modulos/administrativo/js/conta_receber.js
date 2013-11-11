	function excluir(IdContaReceber,IdStatus){
		if(IdStatus == 1 || IdStatus == 3 || IdStatus == 6){
			var url = 'cadastro_cancelar_conta_receber.php?IdContaReceber='+IdContaReceber;
			window.location.replace(url);
		}
	}
	function cadastrar(acao){
		document.formulario.Acao.value	=	acao;
		validar(acao);
		switch(acao){
			case 'alterar':
				if(validar(acao)==true){
					document.formulario.submit();
				}
				break;
			case 'receber':				
				if(document.formulario.CaixaAtivado.value != '1'){
					if(validar(acao)==true){
						document.formulario.submit();
					}
				} else {
					document.formulario.action = "cadastro_caixa_movimentacao.php";					
					document.formulario.submit();
				}
				break;
			case 'imprimirPdf':
				visualizar_boleto(document.formulario.IdContaReceber.value);
				break;
			case 'duplicataPdf':
				visualizar_duplicata(document.formulario.IdContaReceber.value);
				break;
			case 'notaFiscal':
				window.open("menu_conta_receber_nota_fiscal.php?IdContaReceber="+document.formulario.IdContaReceber.value);
				break;
			default:
				document.formulario.submit();
				break;
		}
	}
	function validar(acao){		
		if(document.formulario.NumeroNF.value!=''){
			if(document.formulario.DataNF.value==''){
				mensagens(1);
				document.formulario.DataNF.focus();
				return false;
			}else{
				if(isData(document.formulario.DataNF.value) == false){
					mensagens(27);
					document.formulario.DataNF.focus();
					return false;
				}	
			}
			
			if(document.formulario.ModeloNF.value == '' && acao != 'receber' && acao != 'alterar'){
				mensagens(1);
				document.formulario.ModeloNFTemp.focus();
				return false;
			}
		}
		if(document.formulario.IdPessoaEndereco.value=='' || document.formulario.IdPessoaEndereco.value=='0'){	
			mensagens(1);
			document.formulario.IdPessoaEndereco.focus();
			return false;
		}
		if(acao == 'receber'){
			if(document.formulario.IdLocalRecebimento.value == ''){
				mensagens(1);
				document.formulario.IdLocalRecebimento.focus();
				return false;
			}
			if(document.formulario.DataRecebimento.value==''){
				mensagens(1);
				document.formulario.DataRecebimento.focus();
				return false;
			}else{
				if(isData(document.formulario.DataRecebimento.value) == false){
					mensagens(27);
					document.formulario.DataRecebimento.focus();
					return false;
				}
			}
			if(document.formulario.CalcularMulta.value==''){
				mensagens(1);
				document.formulario.CalcularMulta.focus();
				return false;
			}
			if(document.formulario.ValorDescontoRecebimento.value == ''){
				mensagens(1);
				document.formulario.ValorDescontoRecebimento.focus();
				return false;
			}
			if(document.formulario.ValorMoraMulta.value == ''){
				mensagens(1);
				document.formulario.ValorMoraMulta.focus();
				return false;
			}
			if(document.formulario.ValorOutrasDespesas.value == ''){
				mensagens(1);
				document.formulario.ValorOutrasDespesas.focus();
				return false;
			}
			var posInicial=0,posFinal=0,temp=0;
			if(document.formulario.IdLocalRecebimento.value!=''){
				for(i = 0; i<document.formulario.length; i++){
					if(document.formulario[i].name != undefined){
						if(temp == 0 && document.formulario[i].name.substring(0,6) == 'Valor^'){
							posInicial = i;
							temp = 1;
						}
						if(document.formulario[i].name.substring(0,3) == 'Obs'){
							posFinal = i;
						}
					}
				}
				if(posInicial != 0){
					for(i = posInicial; i<posFinal; i=i+2){
						if(document.formulario[i+1].value == '1' && document.formulario[i].value == ''){
							mensagens(1);
							document.formulario[i].focus();
							return false;
						}
					}
				}
			}
		}
		
		if(document.formulario.IdContaReceberAgrupador.value != '' && !(confirm("ATENÇÃO!\n\nContas a receber utilizado no agrupamento Contas a receber n° "+document.formulario.IdContaReceberAgrupador.value+".\nDeseja continuar?"))){
			return false;
		}
		
		if(document.formulario.ObrigatoriedadeNumeroCartao.value == 1 && document.formulario.NumeroCartaoCredito.value == "0"){
			mensagens(1);
			document.formulario.NumeroCartaoCredito.focus();
			return false;
		}
		
		if(document.formulario.ObrigatoriedadeNumeroContaDebito.value == 1 && (document.formulario.IdContaDebito.value == "" || document.formulario.IdLocalCobranca.value == "")){
			if(document.formulario.IdLocalCobranca.value == ""){
				mensagens(1);
				document.formulario.IdLocalCobranca.focus();
				return false;
			}
			
			mensagens(1);
			document.formulario.IdContaDebito.focus();
			return false;
		}		
		
		mensagens(0);
		return true;
	}
	
	function validar_Data(id,campo){
		if(campo.value == ''){
			document.getElementById(id).style.backgroundColor='#FFF';
			if(id == 'DataNF' && document.formulario.NumeroNF.value == ''){
				document.getElementById(id).style.color='#000';
			}else{
				document.getElementById(id).style.color='#C10000';
			}
			mensagens(0);
			return false;
		}
		if(isData(campo.value) == false){		
			document.getElementById(id).style.backgroundColor = '#C10000';
			document.getElementById(id).style.color='#FFF';
			mensagens(27);
			return false;
		}else{
			document.getElementById(id).style.backgroundColor='#FFF';
			if(id == 'DataNF' && document.formulario.NumeroNF.value == ''){
				document.getElementById(id).style.color='#000';
			}else{
				document.getElementById(id).style.color='#C10000';
			}
			mensagens(0);
			return true;
		}	
	}
	
	function verificaAcao(){
		verificaDuplicata();
		
		document.formulario.ValorMoraMulta.readOnly 			= true;
		document.formulario.ValorDescontoRecebimento.readOnly	= true;
		document.formulario.ValorOutrasDespesas.readOnly		= true;
		document.formulario.bt_chegar.disabled					= false;
		
		//Alteração
		document.formulario.IdPessoaEndereco.disabled			= false;
		document.formulario.IdLocalRecebimento.disabled			= false;
		document.formulario.ObsNotaFiscal.disabled				= false;
		document.formulario.DataRecebimento.disabled			= false;
		document.formulario.CalcularMulta.disabled				= false;
		
		if(document.formulario.IdStatus.value == '0'){
			document.formulario.bt_alterar.disabled		= false;
			document.formulario.bt_imprimir1.disabled	= true;
			document.formulario.bt_receber.disabled		= true;
			document.formulario.bt_cancelar.disabled	= true;
		} else{
			document.formulario.Acao.value				= 'alterar';
			document.formulario.bt_alterar.disabled		= false;
			document.formulario.bt_imprimir1.disabled	= false;
			document.formulario.bt_receber.disabled		= true;
		}
		
		var imgDataRecebimentoTemp = '../../img/estrutura_sistema/ico_date.gif';
		
		if(Number(document.formulario.EditarDataRecebimento.value) == 2 || document.formulario.IdStatus.value == '0' || document.formulario.IdStatus.value == '7'){
			imgDataRecebimentoTemp = '../../img/estrutura_sistema/ico_date_c.gif';
		}
		
		switch(document.formulario.IdStatus.value){
			case '0': // Cancelado
				document.getElementById('imgDataRecebimento').src		= imgDataRecebimentoTemp;
				document.formulario.NumeroNF.readOnly 					= true;
				document.formulario.NumeroNF.onfocus					= function (){
				};
				document.formulario.DataNF.readOnly    					= true;	
				document.formulario.DataNF.onfocus						= function (){
				};
				document.formulario.ModeloNFTemp.disabled    			= true;	
				document.formulario.ModeloNFTemp.onfocus				= function (){
				};
				document.formulario.bt_enviar.disabled					= true;
				document.formulario.bt_alterar.disabled					= false;
				document.formulario.bt_imprimir1.disabled				= true;
				document.formulario.bt_receber.disabled					= true;
				document.formulario.bt_cancelar.disabled				= true;
				document.formulario.bt_pagamento.disabled				= true;
				document.formulario.bt_chegar.disabled					= true;
				document.formulario.bt_nota_fiscal.disabled				= true;
				break;
			case '1': // Aguardando Pagamento
				//verificar_numero_nf(document.formulario.NumeroNF.value);
				
				document.getElementById('imgDataRecebimento').src		= imgDataRecebimentoTemp;
				/*
				document.formulario.NumeroNF.readOnly 					= false;
				document.formulario.NumeroNF.onfocus					= function (){
					Foco(this, 'in');
				};
				document.formulario.DataNF.readOnly    					= false;
				document.formulario.DataNF.onfocus						= function (){
					Foco(this, 'in', true);
				};
				document.formulario.ModeloNFTemp.disabled    			= false;
				document.formulario.ModeloNFTemp.onfocus				= function (){
					Foco(this, 'in');
				};*/
				
				document.formulario.ValorMoraMulta.readOnly 			= false;
				document.formulario.ValorDescontoRecebimento.readOnly	= false;
				document.formulario.ValorOutrasDespesas.readOnly		= false;
				document.formulario.Acao.value							= 'receber';
				document.formulario.bt_enviar.disabled					= false;
				document.formulario.bt_alterar.disabled					= false;
				document.formulario.bt_imprimir1.disabled				= false;
				document.formulario.bt_cancelar.disabled				= false;
				document.formulario.bt_receber.disabled					= false;
				
				if(document.formulario.IdStatusConfirmacaoPagamento.value == '1') {
					document.formulario.bt_pagamento.disabled = true;
				} else{
					document.formulario.bt_pagamento.disabled = false;
				}
				break;
			case '2': // Quitado
				//verificar_numero_nf(document.formulario.NumeroNF.value);
				
				document.getElementById('imgDataRecebimento').src		= imgDataRecebimentoTemp;
				/*
				document.formulario.NumeroNF.readOnly 					= false;
				document.formulario.NumeroNF.onfocus					= function (){
					Foco(this, 'in');
				};
				document.formulario.DataNF.readOnly    					= false;
				document.formulario.DataNF.onfocus						= function (){
					Foco(this, 'in', true);
				};
				document.formulario.ModeloNFTemp.disabled	 			= false;
				document.formulario.ModeloNFTemp.onfocus				= function (){
					Foco(this, 'in');
				};*/
				
				
				document.formulario.Acao.value							= 'imprimir';
				document.formulario.bt_enviar.disabled					= false;
				document.formulario.bt_alterar.disabled					= false;
				document.formulario.bt_imprimir1.disabled				= false;
				document.formulario.bt_receber.disabled					= true;
				document.formulario.bt_cancelar.disabled				= true;
				document.formulario.bt_pagamento.disabled				= true;
				break;
			case '7': // Excluido
				document.getElementById('imgDataRecebimento').src		= imgDataRecebimentoTemp;
				document.formulario.NumeroNF.readOnly 					= true;
				document.formulario.NumeroNF.onfocus					= function (){
				};
				document.formulario.DataNF.readOnly    					= true;	
				document.formulario.DataNF.onfocus						= function (){
				};
				document.formulario.ModeloNFTemp.disabled    			= true;	
				document.formulario.ModeloNFTemp.onfocus				= function (){
				};
				document.formulario.bt_enviar.disabled					= true;
				document.formulario.bt_alterar.disabled					= false;
				document.formulario.bt_imprimir1.disabled				= true;
				document.formulario.bt_receber.disabled					= true;
				document.formulario.bt_pagamento.disabled				= true;
				document.formulario.bt_cancelar.disabled				= true;
				document.formulario.bt_chegar.disabled					= true;
				document.formulario.bt_nota_fiscal.disabled				= true;
				
				//Alteração
				document.formulario.IdPessoaEndereco.disabled			= true;
				document.formulario.IdLocalRecebimento.disabled			= true;
				document.formulario.ObsNotaFiscal.disabled				= true;
				document.formulario.DataRecebimento.disabled			= true;
				document.formulario.CalcularMulta.disabled				= true;
				
				break;
			case '8': // Agrupado
				//verificar_numero_nf(document.formulario.NumeroNF.value);
				
				document.getElementById('imgDataRecebimento').src		= imgDataRecebimentoTemp;
				/*
				document.formulario.NumeroNF.readOnly 					= false;
				document.formulario.NumeroNF.onfocus					= function (){
					Foco(this, 'in');
				};
				document.formulario.DataNF.readOnly    					= false;
				document.formulario.DataNF.onfocus						= function (){
					Foco(this, 'in', true);
				};
				document.formulario.ModeloNFTemp.disabled    			= false;
				document.formulario.ModeloNFTemp.onfocus				= function (){
					Foco(this, 'in');
				};*/
				
				document.formulario.Acao.value							= 'receber';
				document.formulario.bt_enviar.disabled					= false;
				document.formulario.bt_alterar.disabled					= false;
				document.formulario.bt_imprimir1.disabled				= false;
				document.formulario.bt_cancelar.disabled				= true;
				document.formulario.bt_receber.disabled					= true;
				
				break;
			default:
				//verificar_numero_nf(document.formulario.NumeroNF.value);
				
				document.getElementById('imgDataRecebimento').src		= imgDataRecebimentoTemp;
				/*
				document.formulario.NumeroNF.readOnly 					= false;
				document.formulario.NumeroNF.onfocus					= function (){
					Foco(this, 'in');
				};
				document.formulario.DataNF.readOnly    					= false;
				document.formulario.DataNF.onfocus						= function (){
					Foco(this, 'in', true);
				};
				document.formulario.ModeloNFTemp.disabled    			= false;
				document.formulario.ModeloNFTemp.onfocus				= function (){
					Foco(this, 'in');
				};*/
				
				document.formulario.Acao.value							= 'receber';
				document.formulario.bt_enviar.disabled					= false;
				document.formulario.bt_alterar.disabled					= false;
				document.formulario.bt_imprimir1.disabled				= false;
				document.formulario.bt_cancelar.disabled				= false;
				document.formulario.bt_receber.disabled					= false;
		}
		
		document.getElementById('cpValorMoraMulta').style.color = '#000';
		
		if(document.formulario.IdStatus.value == 0 || document.formulario.IdStatus.value == 2 || document.formulario.IdStatus.value == 7){
			document.formulario.IdLocalRecebimento.disabled				= true;
			document.formulario.CalcularMulta.disabled					= true;
			document.formulario.ValorMoraMulta.readOnly					= true;
			document.formulario.ValorOutrasDespesas.readOnly			= true;
			
			document.formulario.ValorDescontoRecebimento.setAttribute("onblur","");
			document.formulario.ValorDescontoRecebimento.setAttribute("onfocus","");
			document.formulario.ValorMoraMulta.setAttribute("onblur","");
			document.formulario.ValorMoraMulta.setAttribute("onfocus","");
			document.formulario.ValorOutrasDespesas.setAttribute("onblur","");
			document.formulario.ValorOutrasDespesas.setAttribute("onfocus","");
		}else{
			document.formulario.ValorDescontoRecebimento.setAttribute("onblur","Foco(this,'out'); calculaValor('ContaRecebimento')");
			document.formulario.ValorDescontoRecebimento.setAttribute("onfocus","Foco(this,'in')");
			document.formulario.ValorMoraMulta.setAttribute("onblur","Foco(this,'out'); calculaValor('ContaRecebimento')");
			document.formulario.ValorMoraMulta.setAttribute("onfocus","Foco(this,'in')");
			document.formulario.ValorOutrasDespesas.setAttribute("onblur","Foco(this,'out'); calculaValor('ContaRecebimento')");
			document.formulario.ValorOutrasDespesas.setAttribute("onfocus","Foco(this,'in')");
			
			document.formulario.IdLocalRecebimento.disabled				= false;
			document.formulario.CalcularMulta.disabled					= false;
			document.formulario.ValorMoraMulta.readOnly					= false;
		}
		
		if(document.formulario.IdLocalRecebimento.value == '' && document.formulario.DataRecebimento.value == ''){
			document.getElementById('DataRecebimento').style.color			= '#000';
			document.getElementById('cpLocalRecebimento').style.color		= '#000';
			document.getElementById('cpValorDesconto').style.color			= '#000';
			document.getElementById('cpValorOutrasDespesas').style.color	= '#000';
			document.getElementById('cpCalcularMulta').style.color			= '#000';
		} else{
			if((document.formulario.Acao.value == 'receber' || document.formulario.Acao.value == 'imprimir') && (document.formulario.IdLocalRecebimento.value != '' || document.formulario.DataRecebimento.value != '')){
				if(Number(document.formulario.EditarDataRecebimento.value) != 2){
					document.getElementById('DataRecebimento').style.color = '#C10000';
				}
				if(document.formulario.IdStatus.value == 0 || document.formulario.IdStatus.value == 2 || document.formulario.IdStatus.value == 7){
					document.getElementById("cpLocalRecebimento").style.color	= "#000";
					document.getElementById("cpCalcularMulta").style.color		= "#000";
					document.getElementById("cpValorMoraMulta").style.color		= "#000";
				}else{
					document.getElementById('cpLocalRecebimento').style.color		=	'#C10000';
					document.getElementById('cpValorMoraMulta').style.color			=	'#C10000';
					document.getElementById('cpValorDesconto').style.color			=	'#C10000';
					document.getElementById('cpValorOutrasDespesas').style.color	=	'#C10000';
					document.getElementById('cpCalcularMulta').style.color			=	'#C10000';
				}
				calcular_multa(document.formulario.CalcularMulta.value);
			}
		}
		
		if(document.formulario.IdStatus.value != 7){
			
		}
	}
	function calcular_multa(valor){
		document.formulario.ValorDescontoRecebimento.value	=	'0,00';
		document.formulario.ValorMoraMulta.value			=	'0,00';
		document.formulario.ValorOutrasDespesas.value		=	'0,00';
		var IdStatus = document.formulario.IdStatus.value;
		
		calculaValor('ContaRecebimento');
		
		if((document.formulario.Acao.value == 'receber' ||  document.formulario.Acao.value == 'imprimir') && (document.formulario.IdLocalRecebimento.value != '' || document.formulario.DataRecebimento.value != '')){
			if(document.formulario.CalcularMulta.value == 1){
				document.getElementById('cpValorMoraMulta').style.color		=	'#000';
				document.formulario.ValorMoraMulta.readOnly					=	true;
			}else{
				if(IdStatus == 0 || IdStatus == 2 || IdStatus == 7){
					document.getElementById('cpValorMoraMulta').style.color		=	'#000';
					document.formulario.ValorMoraMulta.readOnly					=	true;
				}else{
					document.getElementById('cpValorMoraMulta').style.color		=	'#C10000';
					document.formulario.ValorMoraMulta.readOnly					=	false;
				}
			}
		}else{
			document.getElementById('cpValorMoraMulta').style.color		=	'#000';
			document.formulario.ValorMoraMulta.readOnly					=	false;
		}
		calculaValor('ContaReceberRecebimento');
	}
	function inicia(){
		document.formulario.IdContaReceber.focus();
		statusInicial();
	}
	function calculaValor(conta,nome){
		if(conta == 'ContaReceber'){
			var desc, desp, valor, receb, mora, moraC;
	
			if(document.formulario.ValorDesconto.value == '')				document.formulario.ValorDesconto.value = 0;
			if(document.formulario.ValorDespesas.value == '')				document.formulario.ValorDespesas.value = 0;
			if(document.formulario.ValorContaReceber.value == '')			document.formulario.ValorContaReceber.value = 0;
			if(document.formulario.ValorDescontoRecebimento.value == '')	document.formulario.ValorDescontoRecebimento.value = 0;
			if(document.formulario.ValorOutrasDespesas.value == '')			document.formulario.ValorOutrasDespesas.value = 0;
			if(document.formulario.ValorMoraMulta.value == '')				document.formulario.ValorMoraMulta.value = 0;
	
			desc	=	document.formulario.ValorDesconto.value;
			desc	=	new String(desc);
			desc	=	desc.replace('.','');
			desc	=	desc.replace('.','');
			desc	=	desc.replace(',','.');
			
			desp	=	document.formulario.ValorDespesas.value;
			desp	=	new String(desp);;
			desp	=	desp.replace('.','');
			desp	=	desp.replace('.','');
			desp	=	desp.replace(',','.');
			
			valor	=	document.formulario.ValorContaReceber.value;
			valor	=	new String(valor);;
			valor	=	valor.replace('.','');
			valor	=	valor.replace('.','');
			valor	=	valor.replace(',','.');
	
			descR	=	document.formulario.ValorDescontoRecebimento.value;
			descR	=	new String(descR);
			descR	=	descR.replace('.','');
			descR	=	descR.replace('.','');
			descR	=	descR.replace(',','.');
			
			despO	=	document.formulario.ValorOutrasDespesas.value;
			despO	=	new String(despO);
			despO	=	despO.replace('.','');
			despO	=	despO.replace('.','');
			despO	=	despO.replace(',','.');
			
			mora	=	document.formulario.ValorMoraMulta.value;
			mora	=	new String(mora);
			mora	=	mora.replace('.','');
			mora	=	mora.replace('.','');
			mora	=	mora.replace(',','.');
			
			valor	=	parseFloat(valor) + parseFloat(desp) - parseFloat(desc);
			receb	=	parseFloat(valor);
			
			if(descR > 0){
				receb	=	parseFloat(receb) - parseFloat(descR);
			}
			
			if(despO > 0){
				receb	=	parseFloat(receb) + parseFloat(despO);
			}
			
			if(mora > 0){
				receb	=	parseFloat(receb) + parseFloat(mora);
			}
			
			valor	=	Arredonda(valor,2);
			valor	=	formata_float(valor,2);
			
			receb	=	Arredonda(receb,2);
			receb	=	formata_float(receb,2);
			
			document.formulario.ValorFinal.value		=	valor.replace('.',',');
			document.formulario.ValorRecebimento.value	=	valor.replace('.',',');
			document.formulario.ValorReceber.value		=	receb.replace('.',',');
		}else{
			var desc, desp, valor, valorFinal, moraC, mora;
			
			if(document.formulario.ValorDescontoRecebimento.value == '')	document.formulario.ValorDescontoRecebimento.value = 0;
			if(document.formulario.ValorOutrasDespesas.value == '')			document.formulario.ValorOutrasDespesas.value = 0;
			if(document.formulario.ValorMoraMulta.value == '')				document.formulario.ValorMoraMulta.value = 0;
				
			desc	=	document.formulario.ValorDescontoRecebimento.value;
			desc	=	new String(desc);
			desc	=	desc.replace('.','');
			desc	=	desc.replace('.','');
			desc	=	desc.replace(',','.');
			
			desp	=	document.formulario.ValorOutrasDespesas.value;
			desp	=	new String(desp);;
			desp	=	desp.replace('.','');
			desp	=	desp.replace('.','');
			desp	=	desp.replace(',','.');
			
			mora	=	document.formulario.ValorMoraMulta.value;
			mora	=	new String(mora);;
			mora	=	mora.replace('.','');
			mora	=	mora.replace('.','');
			mora	=	mora.replace(',','.');
			
			valor	=	document.formulario.ValorRecebimento.value;
			valor	=	new String(valor);;
			valor	=	valor.replace('.','');
			valor	=	valor.replace('.','');
			valor	=	valor.replace(',','.');
			
			if(document.formulario.CalcularMulta.value == 1){
				var DataRecebimentoTemp = document.formulario.DataRecebimento.value.split('/');
				var DataRecebimento = (DataRecebimentoTemp[2]+DataRecebimentoTemp[1]+DataRecebimentoTemp[0]).replace(/NaN/gi, '');
				
				var DataVencimentoTemp = document.formulario.DataVencimento.value.split('/');
				var DataVencimento = (DataVencimentoTemp[2]+DataVencimentoTemp[1]+DataVencimentoTemp[0]).replace(/NaN/gi, '');
				
				if(DataVencimento < DataRecebimento){
					moraC	= 	(parseFloat(valor) * parseFloat(document.formulario.PercentualMulta.value) / 100) + (parseFloat(valor) * parseFloat(document.formulario.PercentualJurosDiarios.value) / 100 * parseFloat(document.formulario.BaseVencimento.value));
					valor	=	parseFloat(valor) +	parseFloat(moraC);
					mora	=	0;
					
					document.formulario.ValorMoraMulta.value	=	formata_float(Arredonda(moraC,2),2).replace(".",",");
					document.formulario.ValorMoraMulta.readOnly	=	true;
				}
			}else{
				document.formulario.ValorMoraMulta.readOnly	=	false;	
			}
			
			valor	=	parseFloat(valor) + parseFloat(desp) + parseFloat(mora) - parseFloat(desc);
			valor	=	Arredonda(valor,2);
			valor	=	formata_float(valor,2);
			
			document.formulario.ValorReceber.value	=	valor.replace('.',',');
		}
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
	function listar_filtro(e){
		var e = e || event;
		var k = e.keyCode || e.which;
		if (k==13){
			if(validar_filtro() == true){
			 	document.filtro.submit();
			}
		}
	}
	function validar_filtro(){
		if(document.filtro.chNome.checked  == true)		document.filtro.chNome.value  = 1; 		else	document.filtro.chNome.value	=	0;
		if(document.filtro.chRazao.checked == true) 	document.filtro.chRazao.value = 1; 		else  	document.filtro.chRazao.value	=	0;
		if(document.filtro.chFone1.checked == true) 	document.filtro.chFone1.value = 1; 		else  	document.filtro.chFone1.value	=	0;
		if(document.filtro.chFone2.checked == true) 	document.filtro.chFone2.value = 1; 		else  	document.filtro.chFone2.value	=	0;
		if(document.filtro.chFone3.checked == true) 	document.filtro.chFone3.value = 1; 		else  	document.filtro.chFone3.value	=	0;
		if(document.filtro.chCel.checked   == true)		document.filtro.chCel.value   = 1; 		else  	document.filtro.chCel.value		=	0;
		if(document.filtro.chFax.checked   == true)	 	document.filtro.chFax.value   = 1; 		else  	document.filtro.chFax.value		=	0;
		if(document.filtro.chCompF.checked == true) 	document.filtro.chCompF.value = 1; 		else  	document.filtro.chCompF.value	=	0;
		if(document.filtro.chEmail.checked == true)		document.filtro.chEmail.value = 1; 		else  	document.filtro.chEmail.value	=	0;
		if(document.filtro.chNumD.checked  == true)	 	document.filtro.chNumD.value  = 1; 		else  	document.filtro.chNumD.value	=	0;
		if(document.filtro.chNumNF.checked == true) 	document.filtro.chNumNF.value = 1; 		else  	document.filtro.chNumNF.value	=	0;
		if(document.filtro.chDataF.checked == true) 	document.filtro.chDataF.value = 1; 		else  	document.filtro.chDataF.value	=	0;
		if(document.filtro.chLCob.checked  == true) 	document.filtro.chLCob.value  = 1; 		else  	document.filtro.chLCob.value	=	0;
		if(document.filtro.chDataL.checked == true)		document.filtro.chDataL.value = 1; 		else  	document.filtro.chDataL.value	=	0;
		if(document.filtro.chDataV.checked == true)	 	document.filtro.chDataV.value = 1;		else  	document.filtro.chDataV.value	=	0;
		if(document.filtro.chDataP.checked == true) 	document.filtro.chDataP.value = 1; 		else  	document.filtro.chDataP.value	=	0;
		if(document.filtro.chValor.checked == true) 	document.filtro.chValor.value = 1;		else  	document.filtro.chValor.value	=	0;
		if(document.filtro.chValDp.checked == true)		document.filtro.chValDp.value = 1; 		else  	document.filtro.chValDp.value	=	0;
		if(document.filtro.chValDe.checked == true)	 	document.filtro.chValDe.value = 1;		else  	document.filtro.chValDe.value	=	0;
		if(document.filtro.chValF.checked  == true)	 	document.filtro.chValF.value  = 1; 		else  	document.filtro.chValF.value	=	0;
		if(document.filtro.chLRec.checked  == true)		document.filtro.chLRec.value  = 1; 		else  	document.filtro.chLRec.value	=	0;
		if(document.filtro.chStat.checked  == true)	 	document.filtro.chStat.value  = 1;		else  	document.filtro.chStat.value	=	0;
		if(document.filtro.chObs.checked   == true)	 	document.filtro.chObs.value   = 1; 		else  	document.filtro.chObs.value		=	0;
		
		ativaNome('Contas a Receber');
		
		return true;
	}
	function filtro_ordenar_filtro(valor,typeDate){
		if(typeDate == undefined){
			typeDate = 'text';
		}
		
		if(document.filtro.filtro_tipoDado != undefined){
			document.filtro.filtro_tipoDado.value = typeDate;
		}
	
		if(document.filtro.filtro_ordem.value == valor){
			if(document.filtro.filtro_ordem_direcao.value == "ascending"){
				document.filtro.filtro_ordem_direcao.value = "descending";
			}else{
				document.filtro.filtro_ordem_direcao.value = "ascending";
			}
		}else{
			document.filtro.filtro_ordem.value = valor;
		}
		if(validar_filtro() == true){
			document.filtro.submit();
		}
	}
	function selecionaTodos(campo){
		for (i=0;i<document.filtro.length;i++){
			if(document.filtro[i].name.substr(0,2) == 'ch' && document.filtro[i].name != 'chTodos'){
				if(campo.checked == true){
					if(document.filtro[i].checked == false){
						document.filtro[i].checked = true;	
					}
				}else{
					if(document.filtro[i].checked == true){
						document.filtro[i].checked = false;	
					}
				}
			}
		}
	}
	
	function listarRecebimento(IdContaReceber,Erro,IdContaReceberRecebimentoTemp){
		if(IdContaReceber == undefined || IdContaReceber==''){
			IdContaReceber = 0;
		}
		
		if(IdContaReceberRecebimentoTemp == undefined){
			IdContaReceberRecebimentoTemp = '';
		}
		
	   	var url = "xml/conta_receber_recebimento.php?IdContaReceber="+IdContaReceber;
		
		call_ajax(url, function(xmlhttp){ 
			var nameNode, nameTextNode;
			
			if(xmlhttp.responseText == 'false'){
				document.getElementById('totalValorDesconto').innerHTML				=	"0,00";						
				document.getElementById('totalValorRecebido').innerHTML				=	"0,00";	
				document.getElementById('totalRecebimentos').innerHTML				=	"Total: 0";	
			}else{
				while(document.getElementById('tabelaRecebimentos').rows.length > 2){
					document.getElementById('tabelaRecebimentos').deleteRow(1);
				}
				
				var tam, linha, c0, c1, c2, c3, c4;
				var RecebimentoDuplicado, IdContaReceberRecebimento,DataRecebimento,ValorDesconto,ValorRecebido,Valor,DescricaoLocalRecebimento,TotalDesc=0,MD5,IdRecibo,IdCaixa,TotalReceb=0,ValorContaReceber,IdStatusContaReceber;
				
				for(var i=0; i<xmlhttp.responseXML.getElementsByTagName("IdContaReceberRecebimento").length; i++){	
					nameNode = xmlhttp.responseXML.getElementsByTagName("IdContaReceberRecebimento")[i]; 
					nameTextNode = nameNode.childNodes[0];
					IdContaReceberRecebimento = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("DataRecebimento")[i]; 
					nameTextNode = nameNode.childNodes[0];
					DataRecebimento = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("DataLancamento")[i]; 
					nameTextNode = nameNode.childNodes[0];
					DataLancamento = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("ValorDescontoRecebimento")[i]; 
					nameTextNode = nameNode.childNodes[0];
					ValorDescontoRecebimento = nameTextNode.nodeValue;	
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("ValorRecebido")[i]; 
					nameTextNode = nameNode.childNodes[0];
					ValorRecebido = nameTextNode.nodeValue;	
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("ValorContaReceber")[i]; 
					nameTextNode = nameNode.childNodes[0];
					ValorContaReceber = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoLocalRecebimento")[i]; 
					nameTextNode = nameNode.childNodes[0];
					DescricaoLocalRecebimento = nameTextNode.nodeValue;	
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("MD5")[i]; 
					nameTextNode = nameNode.childNodes[0];
					MD5 = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("IdCaixa")[i]; 
					nameTextNode = nameNode.childNodes[0];
					IdCaixa = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("IdRecibo")[i]; 
					nameTextNode = nameNode.childNodes[0];
					IdRecibo = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("IdStatus")[i]; 
					nameTextNode = nameNode.childNodes[0];
					IdStatus = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("IdLoja")[i]; 
					nameTextNode = nameNode.childNodes[0];
					IdLoja = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("Obs")[i]; 
					nameTextNode = nameNode.childNodes[0];
					Obs = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("IdContratoEstorno")[i]; 
					nameTextNode = nameNode.childNodes[0];
					IdContratoEstorno  = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("IdStatusContaReceber")[i]; 
					nameTextNode = nameNode.childNodes[0];
					IdStatusContaReceber = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("DataCriacao")[i]; 
					nameTextNode = nameNode.childNodes[0];
					DataCriacao = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("RecebimentoDuplicado")[i]; 
					nameTextNode = nameNode.childNodes[0];
					RecebimentoDuplicado = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("linkImpresso")[i]; 
					nameTextNode = nameNode.childNodes[0];
					linkImpresso = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("IdCaixaMovimentacao")[i]; 
					nameTextNode = nameNode.childNodes[0];
					IdCaixaMovimentacao = nameTextNode.nodeValue;

					if(ValorDescontoRecebimento == '')  ValorDescontoRecebimento = '0.00';
					if(ValorRecebido == '')				ValorRecebido = '0.00';
					
					TotalDesc	=	TotalDesc +	parseFloat(ValorDescontoRecebimento);
					TotalReceb	=	TotalReceb + parseFloat(ValorRecebido);
					
					tam 	= document.getElementById('tabelaRecebimentos').rows.length;
					linha	= document.getElementById('tabelaRecebimentos').insertRow(tam-1);
					
					if(tam%2 != 0){
						linha.style.backgroundColor = "#E2E7ED";
					}
					
					if(IdContaReceberRecebimentoTemp == IdContaReceberRecebimento){
						linha.style.backgroundColor = "#A0C4EA";
					}
					
					if(parseFloat(ValorContaReceber) > parseFloat(ValorRecebido) || dataConv(DataRecebimento, 'Y-m-d', 'Ymd') < dataConv(DataLancamento, 'Y-m-d', 'Ymd') || RecebimentoDuplicado == 1){
						linha.style.backgroundColor = document.formulario.CorRecebidoDesc.value;
					}
					
					if(IdStatus == 0 || IdStatus == 3){ //Cancelado || Estorno
						linha.style.backgroundColor = "#FFD2D2";
					}
					
					linha.accessKey = IdContaReceberRecebimento; 
					
					c0	= linha.insertCell(0);	
					c1	= linha.insertCell(1);	
					c2	= linha.insertCell(2);	
					c3	= linha.insertCell(3);
					c4	= linha.insertCell(4);
					c5	= linha.insertCell(5);
					c6	= linha.insertCell(6);
					
					var linkIni = "<a href='cadastro_conta_receber_recebimento.php?IdContaReceber="+IdContaReceber+"&IdContaReceberRecebimento="+IdContaReceberRecebimento+"'>";
					var linkFim = "</a>";
					
					c0.innerHTML = linkIni + IdContaReceberRecebimento + linkFim;
					c0.style.cursor  = "pointer";
					c0.style.padding =	"0 0 0 5px";
					
					c1.innerHTML = linkIni + dateFormat(DataRecebimento) + linkFim;
					c1.style.cursor = "pointer";
					
					c2.innerHTML = linkIni + ValorDescontoRecebimento.replace('.',',')+ linkFim + "&nbsp;&nbsp;" ;
					c2.style.cursor = "pointer";
					c2.style.textAlign = "right";
					
					c3.innerHTML = linkIni + ValorRecebido.replace('.',',') + linkFim + "&nbsp;&nbsp;";
					c3.style.cursor = "pointer";
					c3.style.textAlign = "right";
					
					c4.innerHTML = linkIni + DescricaoLocalRecebimento + linkFim;
					c4.style.cursor = "pointer";

					if(IdRecibo == 0){
						IdRecibo = "";
					}
					
					c5.style.width = "120px";
					switch(IdStatus){
						case '0':
							c5.innerHTML = "Canc.";
							break;
						case '3':
							c5.innerHTML = "<a href='estorno.php?"+linkImpresso+"' target='_blank'>"+IdCaixa+""+IdCaixaMovimentacao+" - Estorno</a>";
							break;
						default:
							c5.innerHTML = "<a href='recibo.php?Recibo="+MD5+"' target='_blank'>"+IdRecibo+"</a>";
					}
					c5.style.cursor = "pointer";
					
					if(IdStatus == 1 && IdStatusContaReceber != 7 && IdStatusContaReceber != 0 && document.formulario.PermissaoCancelarRecebimento.value == 1 && (document.formulario.CaixaAtivado.value != "1" || IdCaixa == "")){
						c6.innerHTML    = "<a href='cadastro_cancelar_conta_receber_recebimento.php?IdContaReceber="+IdContaReceber+"&IdContaReceberRecebimento="+IdContaReceberRecebimento+"'><img src='../../img/estrutura_sistema/ico_del.gif' alt='Cancelar?'></a>";								
					}else{
						c6.innerHTML    = "<img src='../../img/estrutura_sistema/ico_del_c.gif' alt='Cancelar?'>";
					}
					c6.style.cursor = "pointer";
                    
                    if(document.formulario.PermissaoCancelarRecebimento.value == 1 && (document.formulario.IdStatus.value == 1 || document.formulario.IdStatus.value == 3 || document.formulario.IdStatus.value == 6)){
                        document.formulario.bt_cancelar.disabled = false;
                    }else{
                        document.formulario.bt_cancelar.disabled = true;
                    }
				}
				
				document.getElementById('totalValorDesconto').innerHTML		=	formata_float(Arredonda(TotalDesc,2),2).replace('.',',');	
				document.getElementById('totalValorRecebido').innerHTML		=	formata_float(Arredonda(TotalReceb,2),2).replace('.',',');	
				document.getElementById('totalRecebimentos').innerHTML		=	"Total: "+i;	
				
			}
			
			if(window.janela != undefined){
				window.janela.close();
			}
		});
	} 	
	function chama_mascara(campo,event){
		switch(document.filtro.filtro_campo.value){
			case 'DataLancamento':
				campo.maxLength	=	10;
				return mascara(campo,event,'date');
				break;
			case 'DataCancelamento':
				campo.maxLength	=	10;
				return mascara(campo,event,'date');
				break;
			case 'DataExclusao':
				campo.maxLength	=	10;
				return mascara(campo,event,'date');
				break;
			case 'DataVencimentoOriginal':
				campo.maxLength	=	10;
				return mascara(campo,event,'date');
				break;
			case 'DataVencimento':
				campo.maxLength	=	10;
				return mascara(campo,event,'date');
				break;
			case 'DataVencimentoAtual':
				campo.maxLength	=	10;
				return mascara(campo,event,'date');
				break;
			case 'MesLancamento':
				campo.maxLength	=	7;
				return mascara(campo,event,'mes');
				break;
			case 'MesExclusao':
				campo.maxLength	=	7;
				return mascara(campo,event,'mes');
				break;
			case 'MesCancelamento':
				campo.maxLength	=	7;
				return mascara(campo,event,'mes');
				break;
			case 'MesVencimentoAtual':
				campo.maxLength	=	7;
				return mascara(campo,event,'mes');
				break;
			case 'MesVencimentoOriginal':
				campo.maxLength	=	7;
				return mascara(campo,event,'mes');
				break;
			case 'MesVencimento':
				campo.maxLength	=	7;
				return mascara(campo,event,'mes');
				break;	
			case 'IdProcessoFinanceiro':
				campo.maxLength	=	11;
				return mascara(campo,event,'int');
				break;
			case 'NumeroDocumento':
				campo.maxLength	=	11;
				return mascara(campo,event,'int');
				break;
			case 'NumeroNF':
				campo.maxLength	=	11;
				return mascara(campo,event,'int');
				break;
			case 'IdReceibo':
				campo.maxLength	=	11;
				return mascara(campo,event,'int');
				break;
			case 'IdContaReceber':
				campo.maxLength	=	11;
				return mascara(campo,event,'int');
				break;
			case 'IdLancamentoFinanceiro':
				campo.maxLength	=	11;
				return mascara(campo,event,'int');
				break;
			case 'IdArquivoRetorno':
				campo.maxLength	=	11;
				return mascara(campo,event,'int');
				break;
			case 'IdArquivoRemessa':
				campo.maxLength	=	11;
				return mascara(campo,event,'int');
				break;
			default:
				campo.maxLength	=	100;
		}
	}
	function cadastro_vencimento(){
		if(document.formulario.IdContaReceber.value!="" && document.formulario.IdStatus.value != "0" && document.formulario.IdStatus.value != "7"){
			location.replace('cadastro_conta_receber_vencimento.php?IdContaReceber='+document.formulario.IdContaReceber.value);
		}
	}
	function busca_opcoes_pessoa_endereco(IdPessoa,IdPessoaEnderecoTemp){
		if(IdPessoaEnderecoTemp == undefined){
			IdPessoaEnderecoTemp = "";
		}
		
		while(document.formulario.IdPessoaEndereco.options.length > 0){
			document.formulario.IdPessoaEndereco.options[0] = null;
		}
		
		if(IdPessoa != ""){
		    var url = "xml/pessoa_endereco.php?IdPessoa="+IdPessoa;
			
			call_ajax(url, function(xmlhttp){ 
				var nameNode, nameTextNode;
				
				if(xmlhttp.responseText != 'false'){		
					var nameNode, nameTextNode, IdPessoaEndereco,DescricaoEndereco;					
					
					addOption(document.formulario.IdPessoaEndereco,"","0");
					
					for(i=0;i<xmlhttp.responseXML.getElementsByTagName("IdPessoaEndereco").length;i++){
						nameNode = xmlhttp.responseXML.getElementsByTagName("IdPessoaEndereco")[i]; 
						nameTextNode = nameNode.childNodes[0];
						IdPessoaEndereco = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoEndereco")[i]; 
						nameTextNode = nameNode.childNodes[0];
						DescricaoEndereco = nameTextNode.nodeValue;
						
						addOption(document.formulario.IdPessoaEndereco,DescricaoEndereco,IdPessoaEndereco);
					}
					
					document.formulario.IdPessoaEndereco[0].selected 		 = true;
					
					if(IdPessoaEnderecoTemp!=""){
						for(i=0;i<document.formulario.IdPessoaEndereco.options.length;i++){
							if(document.formulario.IdPessoaEndereco[i].value == IdPessoaEnderecoTemp){
								document.formulario.IdPessoaEndereco[i].selected	=	true;
								i	=	document.formulario.IdPessoaEndereco.options.length;
							}
						}
					}
				}
			});
		}
	}
	function busca_pessoa_endereco(IdPessoa,IdPessoaEndereco){
		if(IdPessoa==''){
			IdPessoa = 0;
		}
		
		if(IdPessoaEndereco=='' || IdPessoaEndereco==undefined){
			IdPessoaEndereco = 0;
		}
		
		var url = "xml/pessoa_endereco.php?IdPessoa="+IdPessoa+"&IdPessoaEndereco="+IdPessoaEndereco;
		
		call_ajax(url, function(xmlhttp){
			if(xmlhttp.responseText != 'false'){		
				var nameNode, nameTextNode;					
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("IdPessoaEndereco")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var IdPessoaEndereco = nameTextNode.nodeValue;
					
				nameNode = xmlhttp.responseXML.getElementsByTagName("NomeResponsavelEndereco")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var NomeResponsavelEndereco = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("CEP")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var CEP = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("Endereco")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var Endereco = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("Numero")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var Numero = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("Complemento")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var Complemento = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("Bairro")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var Bairro = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("IdPais")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var IdPais = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("NomePais")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var NomePais = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("IdEstado")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var IdEstado = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("NomeEstado")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var NomeEstado = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("SiglaEstado")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var SiglaEstado = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("IdCidade")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var IdCidade = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("NomeCidade")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var NomeCidade = nameTextNode.nodeValue;
				
				document.formulario.NomeResponsavelEndereco.value			=	NomeResponsavelEndereco;
				document.formulario.CEP.value								=	CEP;
				document.formulario.Endereco.value							=	Endereco;
				document.formulario.Numero.value							=	Numero;
				document.formulario.Complemento.value						=	Complemento;
				document.formulario.Bairro.value							=	Bairro;
				document.formulario.IdPais.value							=	IdPais;
				document.formulario.Pais.value								=	NomePais;
				document.formulario.IdEstado.value							=	IdEstado;
				document.formulario.Estado.value							=	NomeEstado;
				document.formulario.SiglaEstado.value						=	SiglaEstado;
				document.formulario.IdCidade.value							=	IdCidade;
				document.formulario.Cidade.value							=	NomeCidade;
			}else{
				document.formulario.NomeResponsavelEndereco.value			=	"";
				document.formulario.CEP.value								=	"";
				document.formulario.Endereco.value							=	"";
				document.formulario.Numero.value							=	"";
				document.formulario.Complemento.value						=	"";
				document.formulario.Bairro.value							=	"";
				document.formulario.IdPais.value							=	"";
				document.formulario.Pais.value								=	"";
				document.formulario.IdEstado.value							=	"";
				document.formulario.Estado.value							=	"";
				document.formulario.SiglaEstado.value						=	"";
				document.formulario.IdCidade.value							=	"";
				document.formulario.Cidade.value							=	"";
			}
		});
	}
	
	function busca_filtro_cidade(IdEstado,IdCidadeTemp){
		if(IdEstado == undefined || IdEstado==''){
			IdEstado = 0;			
		}
		
		if(IdCidadeTemp == undefined){
			IdCidadeTemp = '';
		}
		
		var url = "xml/cidade.php?IdPais="+1+"&IdEstado="+IdEstado;
		
		call_ajax(url, function(xmlhttp){
			var nameNode, nameTextNode;
			
			if(xmlhttp.responseText != 'false'){		
				while(document.filtro.filtro_cidade.options.length > 0){
					document.filtro.filtro_cidade.options[0] = null;
				}
				
				var nameNode, nameTextNode, NomeCidade;					
				
				for(var i=0; i<xmlhttp.responseXML.getElementsByTagName("IdCidade").length; i++){
					nameNode = xmlhttp.responseXML.getElementsByTagName("IdCidade")[i]; 
					nameTextNode = nameNode.childNodes[0];
					IdCidade = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("NomeCidade")[i]; 
					nameTextNode = nameNode.childNodes[0];
					NomeCidade = nameTextNode.nodeValue;
				
					addOption(document.filtro.filtro_cidade,NomeCidade,IdCidade);
				}					
				
				if(IdCidadeTemp!=""){
					for(i=0;i<document.filtro.filtro_cidade.length;i++){
						if(document.filtro.filtro_cidade[i].value == IdCidadeTemp){
							document.filtro.filtro_cidade[i].selected	=	true;
							break;
						}
					}
				}else{
					document.filtro.filtro_cidade[0].selected	=	true;
				}						
			}else{
				while(document.filtro.filtro_cidade.options.length > 0){
					document.filtro.filtro_cidade.options[0] = null;
				}						
			}
		});
	}	
	
	function visualizar_boleto(IdContaReceber){
		if(IdContaReceber == undefined || IdContaReceber==''){
			IdContaReceber = 0;			
		}
		
		var url = "xml/conta_receber_visualizar_boleto.php?IdContaReceber="+IdContaReceber;
		
		call_ajax(url, function(xmlhttp){
			var nameNode, nameTextNode;
			
			if(xmlhttp.responseText != 'false'){
				nameNode = xmlhttp.responseXML.getElementsByTagName("Url")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var Url = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("Erro")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var Erro = nameTextNode.nodeValue;
				
				if(Erro == 0){
					window.open(Url);
				} else{
					document.formulario.Erro.value = Erro;
					verificaErro();
				}
			}
		});
   	}
	function filtro_buscar_pessoa(IdPessoa){
		if(IdPessoa == '' ||  IdPessoa == undefined){
			IdPessoa = 0;
		}
		
		var url = "xml/pessoa.php?IdPessoa="+IdPessoa+"&IdStatus=1&IdTipoServico=1";
		
		call_ajax(url,function (xmlhttp){
			if(xmlhttp.responseText == 'false'){
				document.filtro.filtro_id_pessoa.value				= '';
				document.filtro.filtro_descricao_id_pessoa.value	= '';
			} else{
				var nameNode = xmlhttp.responseXML.getElementsByTagName("IdPessoa")[0]; 
				var nameTextNode = nameNode.childNodes[0];
				var IdPessoa = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("Nome")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var Nome = nameTextNode.nodeValue;
				
				document.filtro.filtro_id_pessoa.value				= IdPessoa;
				document.filtro.filtro_descricao_id_pessoa.value	= Nome;
				//document.filtro.IdPessoa.value = "";
			}
		});	
	}
	function filtro_buscar_servico(IdServico){
		if(IdServico == '' || IdServico == undefined){
			IdServico = 0;
		}
		
	    var url = "xml/servico.php?IdServico="+IdServico;
		
		call_ajax(url, function (xmlhttp) {
			if(xmlhttp.responseText == 'false'){
				document.filtro.filtro_id_servico.value				= '';
				document.filtro.filtro_descricao_id_servico.value	= '';
			} else {
				var nameNode = xmlhttp.responseXML.getElementsByTagName("IdServico")[0]; 
				var nameTextNode = nameNode.childNodes[0];
				var IdServico = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoServico")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var DescricaoServico = nameTextNode.nodeValue;
				
				document.filtro.filtro_id_servico.value				= IdServico;
				document.filtro.filtro_descricao_id_servico.value	= DescricaoServico;
				
				if(document.filtro.IdServico != undefined) {
					document.filtro.IdServico.value = "";
				}
			}
		});
	}
	function busca_obs_nota_fiscal(IdContaReceber){
		if(IdContaReceber == '' ||  IdContaReceber == undefined){
			IdContaReceber = 0;
		}
		var nameNode, nameTextNode;
		var url = "xml/nota_fiscal.php?IdContaReceber="+IdContaReceber;
		
		call_ajax(url, function(xmlhttp){
			
			if(xmlhttp.responseText == 'false'){
				document.formulario.ObsNotaFiscal.value = '';
			} else {
				nameNode = xmlhttp.responseXML.getElementsByTagName("ObsNotaFiscal")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var ObsNotaFiscal = nameTextNode.nodeValue;
				
				document.formulario.ObsNotaFiscal.value = ObsNotaFiscal;
			}
		});
	}

	function busca_posicao_cobranca(IdStatus,IdPosicaoCobrancaTemp, IdContaReceber, IdTipoLocalCobranca,DataVencimento,DataAtual){
		var cont = 1,selecionado=0;
		if(IdPosicaoCobrancaTemp == undefined) 			IdPosicaoCobrancaTemp 		 = "";

		if(IdContaReceber == undefined || IdContaReceber == '') IdContaReceber 		 = 0;
		
		while(document.formulario.IdPosicaoCobranca.options.length > 0){
			document.formulario.IdPosicaoCobranca.options[0] = null;
		}
		
		if(IdStatus != ""){
			var url = "xml/conta_receber_posicao_cobranca.php?IdGrupoParametroSistema=81&IdContaReceber="+IdContaReceber;
			
			call_ajax(url, function(xmlhttp){
				if(xmlhttp.responseText != 'false'){
					var nameNode, nameTextNode, IdParametroSistema,ValorParametroSistema, PosicaoCobranca, opcao2=0, opcao3;					
					
					addOption(document.formulario.IdPosicaoCobranca,"","0");

					nameNode = xmlhttp.responseXML.getElementsByTagName("PosicaoCobranca")[0]; 
					nameTextNode = nameNode.childNodes[0];
					var PosicaoCobranca = nameTextNode.nodeValue;
					
					if(PosicaoCobranca != ""){
						if(PosicaoCobranca.substr(PosicaoCobranca.length - 1,PosicaoCobranca.length) != 0){
							PosicaoCobranca = PosicaoCobranca.substr(PosicaoCobranca.length - 1,PosicaoCobranca.length);
						}else{
							PosicaoCobranca = PosicaoCobranca.substr(PosicaoCobranca.length - 2,PosicaoCobranca.length);
						}
					}
					nameNode = xmlhttp.responseXML.getElementsByTagName("DataRemessa")[0]; 
					nameTextNode = nameNode.childNodes[0];
					DataRemessa = nameTextNode.nodeValue;

					if(IdStatus == 7){
						//Não Enviar Definitivamente
						addOption(document.formulario.IdPosicaoCobranca,"Não Enviar Definitivamente",10);
						if(PosicaoCobranca == 10){
							document.formulario.IdPosicaoCobranca.disabled = true;
						}
					}else{
						if(IdStatus != 6 && IdStatus != 2){ //Evita do duplicamento de campos, para novos 
							var DesPosicao = ["","Aguardando Remessa","Protestar Título","Sustar Protesto","Pedido de Baixa","Cancelar Título",
												  "Alterar Vencimento","Sustar protesto e baixar Título","Não Enviar","Alteração de outros dados","Não Enviar Definitivamente"];
							if(PosicaoCobranca != '' && PosicaoCobranca != undefined)
								addOption(document.formulario.IdPosicaoCobranca,DesPosicao[PosicaoCobranca],PosicaoCobranca);	
						}
						for(i=0;i<xmlhttp.responseXML.getElementsByTagName("IdParametroSistema").length;i++){
							nameNode = xmlhttp.responseXML.getElementsByTagName("IdParametroSistema")[i]; 
							nameTextNode = nameNode.childNodes[0];
							IdParametroSistema = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("ValorParametroSistema")[i]; 
							nameTextNode = nameNode.childNodes[0];
							ValorParametroSistema = nameTextNode.nodeValue;
							
							if(IdTipoLocalCobranca == 3){
								DataAtual 		= DataAtual.replace(/-/g,'');
								DataVencimento	= DataVencimento.replace(/-/g,'');
								if(DataRemessa == '0000-00-00'){
									if(DataVencimento > (DataAtual+1)){
										switch(PosicaoCobranca){
											case '1': 
												if(IdParametroSistema == 8){ //Não Enviar
													addOption(document.formulario.IdPosicaoCobranca,ValorParametroSistema,IdParametroSistema);
												}
												break;
											case '5':
												if(IdParametroSistema == 8){ //Não Enviar
													addOption(document.formulario.IdPosicaoCobranca,ValorParametroSistema,IdParametroSistema);
												}
												break;
										}
										
									} else{
										if(IdParametroSistema == 8){ //Não Enviar
											document.formulario.IdPosicaoCobranca.options[1] = null;
											addOption(document.formulario.IdPosicaoCobranca,ValorParametroSistema,IdParametroSistema);
										}
									}
								}else{
									if(PosicaoCobranca != ""){ //Só entrará aqui se houver pelo mesmo uma posição de cobrança para o conta  a receber.									
										if(DataVencimento > (DataAtual+1)){
											switch(PosicaoCobranca){
												case '1':
													if(IdParametroSistema == 5){ //Aguardando Remessa
														document.formulario.IdPosicaoCobranca.options[1] = null;
														addOption(document.formulario.IdPosicaoCobranca,ValorParametroSistema,IdParametroSistema);
													}
													if(IdParametroSistema == 8){ //Não Enviar
														addOption(document.formulario.IdPosicaoCobranca,ValorParametroSistema,IdParametroSistema);
													}
													break;
												case '5':
													if(IdParametroSistema == 1){ //Aguardando Remessa
														document.formulario.IdPosicaoCobranca.options[1] = null;
														addOption(document.formulario.IdPosicaoCobranca,ValorParametroSistema,IdParametroSistema);
													}
													if(IdParametroSistema == 8){ //Não Enviar
														addOption(document.formulario.IdPosicaoCobranca,ValorParametroSistema,IdParametroSistema);
													}
													break;
											}
										}else{
											if(IdParametroSistema == 5 && PosicaoCobranca != ""){ //Cancelar Título
												addOption(document.formulario.IdPosicaoCobranca,ValorParametroSistema,IdParametroSistema);
											}
										}
									}else{ // Caso não aja nenhuma posição de cobrança entrará aqui 
										if(DataVencimento > (DataAtual+1)){	// Verificar se o titulo não esta vencido
											if(IdParametroSistema == 1){ //Aguardando Remessa
												addOption(document.formulario.IdPosicaoCobranca,ValorParametroSistema,IdParametroSistema);
											}
											if(IdParametroSistema == 8){ //Aguardando Remessa
												addOption(document.formulario.IdPosicaoCobranca,ValorParametroSistema,IdParametroSistema);
											}
										} else{
											if(IdParametroSistema == 8){ //Não Enviar
												addOption(document.formulario.IdPosicaoCobranca,ValorParametroSistema,IdParametroSistema);
											}
										}
									}
								}
							} else{
								if(PosicaoCobranca == 10){
									document.formulario.IdPosicaoCobranca.disabled = true;
								}else{
									document.formulario.IdPosicaoCobranca.disabled = false;
									if(IdStatus == 6){
										if(IdParametroSistema == 1){ //Aguardando Remessa
											addOption(document.formulario.IdPosicaoCobranca,ValorParametroSistema,IdParametroSistema);
										}
										if(IdParametroSistema == 10){ //Não Enviar Definitivamente
											addOption(document.formulario.IdPosicaoCobranca,ValorParametroSistema,IdParametroSistema);
										}
									}
									if(IdStatus == 2){
										if(IdParametroSistema == 4){ //Aguardando Remessa
											addOption(document.formulario.IdPosicaoCobranca,ValorParametroSistema,IdParametroSistema);
										}
										if(IdParametroSistema == 8){ //Não Enviar Definitivamente
											addOption(document.formulario.IdPosicaoCobranca,ValorParametroSistema,IdParametroSistema);
										}
									}
									if(IdStatus != 6 && IdStatus != 2){
										switch(PosicaoCobranca){
											case '1': // Aguardando Remessa
												if(DataRemessa == '0000-00-00'){
													if(IdParametroSistema == 8){ //Não Enviar
														addOption(document.formulario.IdPosicaoCobranca,ValorParametroSistema,IdParametroSistema);
													}
												}else{
													if(IdParametroSistema == 2){ // Protestar Título
														addOption(document.formulario.IdPosicaoCobranca,ValorParametroSistema,IdParametroSistema);
													}
													if(IdParametroSistema == 5){ //Pedido Cancelamento
														addOption(document.formulario.IdPosicaoCobranca,ValorParametroSistema,IdParametroSistema);
													}
												}
												break;
											case '2': // Protestar Título
												if(DataRemessa == '0000-00-00'){
													if(IdParametroSistema == 5){ //Cancelar Titulo
														addOption(document.formulario.IdPosicaoCobranca,ValorParametroSistema,IdParametroSistema);
													}
													if(IdParametroSistema == 8){ //Não Enviar
														addOption(document.formulario.IdPosicaoCobranca,ValorParametroSistema,IdParametroSistema);
													}
												} else{
													if(IdParametroSistema == 3){ //Sustar Titulo
														addOption(document.formulario.IdPosicaoCobranca,ValorParametroSistema,IdParametroSistema);
													}
													if(IdParametroSistema == 5){ //Cancelar Titulo
														addOption(document.formulario.IdPosicaoCobranca,ValorParametroSistema,IdParametroSistema);
													}
													if(IdParametroSistema == 8){ //Não Enviar
														addOption(document.formulario.IdPosicaoCobranca,ValorParametroSistema,IdParametroSistema);
													}
												}
												break;
											case '3': //Sustar Protesto																				
												if(DataRemessa == '0000-00-00'){
													if(IdParametroSistema == 5){ //Cancelar Titulo
														addOption(document.formulario.IdPosicaoCobranca,ValorParametroSistema,IdParametroSistema);
													}
													if(IdParametroSistema == 8){ // Não enviar
														addOption(document.formulario.IdPosicaoCobranca,ValorParametroSistema,IdParametroSistema);
													}
												} else{
													if(IdParametroSistema == 5){ //Cancelar Titulo
														addOption(document.formulario.IdPosicaoCobranca,ValorParametroSistema,IdParametroSistema);
													}
												}
												break;
											case '4': //Pedido de Baixa																			
												if(DataRemessa == '0000-00-00'){
													if(IdParametroSistema == 8){ // Não enviar
														addOption(document.formulario.IdPosicaoCobranca,ValorParametroSistema,IdParametroSistema);
													}
												}
												document.getElementById('cp_Status').disabled = true;
												break;
											case '5': //Pedido Cancelamento																				
												if(DataRemessa == '0000-00-00'){
													if(IdParametroSistema == 8){ // Não enviar
														addOption(document.formulario.IdPosicaoCobranca,ValorParametroSistema,IdParametroSistema);
													}
												}
												document.getElementById('cp_Status').disabled = true;
												break;
											case '6': //Alteração de vencimento 
												if(DataRemessa == '0000-00-00'){
													if(IdParametroSistema == 2){ // Protestar Título
														addOption(document.formulario.IdPosicaoCobranca,ValorParametroSistema,IdParametroSistema);
													}
													if(IdParametroSistema == 4){ //Pedido de Baixa
														addOption(document.formulario.IdPosicaoCobranca,ValorParametroSistema,IdParametroSistema);
													}
													if(IdParametroSistema == 5){ //Cancelar Titulo
														addOption(document.formulario.IdPosicaoCobranca,ValorParametroSistema,IdParametroSistema);
													}
													if(IdParametroSistema == 8){ // Não enviar
														addOption(document.formulario.IdPosicaoCobranca,ValorParametroSistema,IdParametroSistema);
													}
												} else{
													if(IdParametroSistema == 2){ // Protestar Título
														addOption(document.formulario.IdPosicaoCobranca,ValorParametroSistema,IdParametroSistema);
													}
													if(IdParametroSistema == 4){ //Pedido de Baixa
														addOption(document.formulario.IdPosicaoCobranca,ValorParametroSistema,IdParametroSistema);
													}
													if(IdParametroSistema == 5){ //Cancelar Titulo
														addOption(document.formulario.IdPosicaoCobranca,ValorParametroSistema,IdParametroSistema);
													}
												}
												break;
											case '7': //Sustar protesto e baixar Título
												if(DataRemessa == '0000-00-00'){
													if(IdParametroSistema == 8){ // Não enviar
														addOption(document.formulario.IdPosicaoCobranca,ValorParametroSistema,IdParametroSistema);
													}
												}
												break;
											case '9': //Alteração de outros dados
												if(DataRemessa != '0000-00-00'){
													if(IdParametroSistema == 2){
														addOption(document.formulario.IdPosicaoCobranca,ValorParametroSistema,IdParametroSistema);
													}
													if(IdParametroSistema == 5){
														addOption(document.formulario.IdPosicaoCobranca,ValorParametroSistema,IdParametroSistema);
													}
													if(IdParametroSistema == 8){ // Não enviar
														addOption(document.formulario.IdPosicaoCobranca,ValorParametroSistema,IdParametroSistema);
													}
												} else{
													if(IdParametroSistema == 8){
														addOption(document.formulario.IdPosicaoCobranca,ValorParametroSistema,IdParametroSistema);
													}
												}
												
												break;
											default:
												if(IdParametroSistema == 1){ //Aguardando Remessa
													addOption(document.formulario.IdPosicaoCobranca,ValorParametroSistema,IdParametroSistema);
												}
												if(IdParametroSistema == 8){ //Não Enviar
													addOption(document.formulario.IdPosicaoCobranca,ValorParametroSistema,IdParametroSistema);
												}
												break;
										}
									}
								}
								if(PosicaoCobranca > 0 && PosicaoCobranca != "" && document.formulario.IdPosicaoCobranca.options[1] != undefined){
									document.formulario.IdPosicaoCobranca.options[1].selected = true;
								}
							}
						}
					}	
					if(IdPosicaoCobrancaTemp!=""){
						for(i=0;i<document.formulario.IdPosicaoCobranca.options.length;i++){
							if(document.formulario.IdPosicaoCobranca[i].value == IdPosicaoCobrancaTemp){
								document.formulario.IdPosicaoCobranca[i].selected	=	true;
								i	=	document.formulario.IdPosicaoCobranca.options.length;
								break;
							}
						}
					}
					if(PosicaoCobranca.length > 1){ 	
						for(j=0; j<PosicaoCobranca.length; j++){								
							for(i=1; i<document.formulario.IdPosicaoCobranca.options.length; i++){									
								if(document.formulario.IdPosicaoCobranca[i].value == PosicaoCobranca[j] && PosicaoCobranca[j] != '_'){									
									document.formulario.IdPosicaoCobranca.options[i] = null;		
								}
								if(PosicaoCobranca.length == 1 && PosicaoCobranca[j] == 1 && document.formulario.IdPosicaoCobranca[i].value == '3'){									
									document.formulario.IdPosicaoCobranca.options[i] = null;
								}									
							}
						}
					}
				}
			});
		}
	}
	function visualizar_duplicata(IdContaReceber){
		if(IdContaReceber == undefined || IdContaReceber==''){
			IdContaReceber = 0;			
		}
		var url = "xml/conta_receber_visualizar_duplicata.php?IdContaReceber="+IdContaReceber;
		
		call_ajax(url, function(xmlhttp){
			var nameNode, nameTextNode;
			
			if(xmlhttp.responseText != 'false'){
				nameNode = xmlhttp.responseXML.getElementsByTagName("Url")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var Url = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("Erro")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var Erro = nameTextNode.nodeValue;
				
				if(Erro == 0){
					window.open(Url);
				} else{
					document.formulario.Erro.value = Erro;
					verificaErro();
				}
			}
		});
   	}
    function verificaDuplicata(){
        
		var IdContaReceber = document.formulario.IdContaReceber.value;
		var url = "xml/conta_receber_visualizar_duplicata.php?IdContaReceber="+IdContaReceber;
		
		call_ajax(url, function(xmlhttp){
			var nameNode, nameTextNode;
			
			if(xmlhttp.responseText != 'false'){
							
				nameNode = xmlhttp.responseXML.getElementsByTagName("IdDuplicataLayout")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var IdDuplicataLayout = nameTextNode.nodeValue;
			      
				if(IdContaReceber != ''){
                    if(IdDuplicataLayout == '1'){
                        if(document.formulario.IdStatus.value == '0' || document.formulario.IdStatus.value == '2' || document.formulario.IdStatus.value == '7'){
                            document.formulario.bt_duplicata.disabled   =   true;
                        }else{
    				        document.formulario.bt_duplicata.disabled = false;
    				    }
                    } else{
    				    document.formulario.bt_duplicata.disabled = true;
    				}
                } else{
				    document.formulario.bt_duplicata.disabled = true;
				}
			}
		});
   	}
	function abrir_boleto(IdContaReceber){
		if(IdContaReceber == undefined || IdContaReceber==''){
			IdContaReceber = 0;			
		}
		
		var url = "xml/conta_receber_abrir_boleto.php?IdContaReceber="+IdContaReceber;
		
		call_ajax(url, function(xmlhttp){
			var nameNode, nameTextNode;
			
			if(xmlhttp.responseText != 'false'){
				nameNode = xmlhttp.responseXML.getElementsByTagName("Url")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var Url = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("Erro")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var Erro = nameTextNode.nodeValue;
				
				if(Erro == 0){
					if(Url != ""){
						window.open(Url,'_parent');
					}else{
						document.formulario.Erro.value = Erro;
						verificaErro();
					}
				} else{
					document.formulario.Erro.value = Erro;
					verificaErro();
				}
			}
		});
   	}
	function imprimir_duplicata_ao_imprimir_boleto(){
		var IdGrupoCodigoInterno = 3;
		var IdCodigoInterno = 188;
		var url = "xml/codigo_interno.php?IdGrupoCodigoInterno="+IdGrupoCodigoInterno+"&IdCodigoInterno="+IdCodigoInterno;
		
		call_ajax(url, function(xmlhttp){
			var nameNode, nameTextNode;
			
			if(xmlhttp.responseText != 'false'){
				nameNode = xmlhttp.responseXML.getElementsByTagName("ValorCodigoInterno")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var ValorCodigoInterno = nameTextNode.nodeValue;
								
				if(ValorCodigoInterno != ""){
					return ValorCodigoInterno;
				}
			}
		});
   	}
	function verifica_nota_fiscal(SerieNF){
		if(SerieNF != ""){
			document.getElementById("DataNF").style.color = "#000";
			document.getElementById("ModeloNF").style.color = "#000";
			
			document.getElementById("SerieNF").style.display = 'block';//titulos
			document.getElementById("BaseNF").style.display = 'block';
			document.getElementById("StatusNF").style.display = 'block';
			
			document.formulario.SerieNF.style.display = 'block';//campos
			document.formulario.BaseNF.style.display = 'block';
			document.formulario.StatusNF.style.display = 'block';
			
			document.formulario.NumeroNF.readOnly = true;
			document.formulario.DataNF.readOnly = true;
			document.formulario.ModeloNFTemp.disabled = true;
			document.formulario.SerieNF.readOnly = true;
			document.formulario.BaseNF.readOnly = true;
			document.formulario.StatusNF.readOnly = true;
			
			document.formulario.NumeroNF.setAttribute('onfocus','');
			document.formulario.NumeroNF.setAttribute('onblur','');
			document.formulario.DataNF.setAttribute('onfocus','');
			document.formulario.DataNF.setAttribute('onblur','');
			document.getElementById('imgDataNF').setAttribute('src','../../img/estrutura_sistema/ico_date_c.gif');
		}else{
			
			document.formulario.NumeroNF.readOnly = false;
			document.formulario.DataNF.readOnly = false;
			document.formulario.ModeloNFTemp.disabled = false;
			
			document.formulario.NumeroNF.setAttribute('onfocus','Foco(this,"in")');
			document.formulario.NumeroNF.setAttribute('onblur','Foco(this,"out")');
			document.formulario.DataNF.setAttribute('onfocus','Foco(this,"in")');
			document.formulario.DataNF.setAttribute('onblur','Foco(this,"out")');
			document.getElementById('imgDataNF').setAttribute('src','../../img/estrutura_sistema/ico_date.gif');
		}
	}
	function obrigatorio_nf(campo){
		if(campo.value == ""){
			document.getElementById("DataNF").style.color = "#000";
			document.getElementById("ModeloNF").style.color = "#000";
		}
		if(campo.value != ""){
			document.getElementById("DataNF").style.color = "#c10000";
			document.getElementById("ModeloNF").style.color = "#c10000";
		}
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
					
					if((keyCode >= 48 && keyCode <= 57)){
						var digitos = numero.value.replace(/( )/g,"");
						if((digitos.length > 0 && digitos.length % 4 == 0 && keyCode != 46 && keyCode != 8) && (keyCode >=48 && keyCode <= 57)){
							numero.value = numero.value + " ";
						}
					}else{
						if(keyCode != 8){
							if(event.preventDefault){ //standart browsers
								event.preventDefault();
							} else{ // internet explorer
								event.returnValue = false;
							}
						}
					}
					break;
			}
		}
		contadordigito++;
	}
	
	function busca_cartao(IdPessoa,IdCartaoAux,IdStatus)
	{
		if(IdPessoa == "" || IdPessoa == "NULL") IdPessoa = 0;
		var url = "xml/pessoa_cartao_credito.php?IdPessoa="+IdPessoa;
		
		elSel = document.formulario.NumeroCartaoCredito;
		for (var i = elSel.length - 1; i>=0; i--) {
			elSel.remove(0);
		}
		
		call_ajax(url, function(xmlhttp){ 
			var nameNode, nameTextNode;
			if(xmlhttp.responseText != 'false'){		
				var nameNode, nameTextNode, IdPessoaEndereco,DescricaoEndereco;					
				
				addOption(document.formulario.NumeroCartaoCredito,"","0");
				
				for(i=0;i<xmlhttp.responseXML.getElementsByTagName("IdCartao").length;i++){
					var nameNode = xmlhttp.responseXML.getElementsByTagName("IdCartao")[i]; 
					var nameTextNode = nameNode.childNodes[0];
					var IdCartao = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("NumeroCartaoMascarado")[i]; 
					nameTextNode = nameNode.childNodes[0];
					var NumeroCartaoMascarado = nameTextNode.nodeValue;
					
					addOption(document.formulario.NumeroCartaoCredito,NumeroCartaoMascarado,IdCartao);
				}
				
				document.formulario.IdContaDebito.value = "";
				
				if(IdStatus == 2 || IdStatus == 0){
					document.formulario.NumeroCartaoCredito.disabled = true;
				}
				
				if(IdCartaoAux == ""){
					document.formulario.NumeroCartaoCredito.options[0].selected=true;
					if(IdStatus == 2 || IdStatus == 0){
						document.formulario.NumeroCartaoCredito.disabled = true;
					}
				}
				else{
					for(var i = 0; i < document.formulario.NumeroCartaoCredito.length; i++){
						if(document.formulario.NumeroCartaoCredito.options[i].value == IdCartaoAux){
							document.formulario.NumeroCartaoCredito.value = IdCartaoAux;
							break;
						}
					}
				}
			}else{
				elSel = document.formulario.NumeroCartaoCredito;
				for (var i = elSel.length - 1; i>=0; i--) {
					elSel.remove(0);
				}
			
				if(document.formulario.NumeroCartaoCredito.value == ""){
					document.getElementById("titNumeroCartaoCredito").style.display = "none";
					document.getElementById("cpNumeroCartaoCredito").style.display = "none";
					document.formulario.ObrigatoriedadeNumeroCartao.value = "";
				}
			}
			
			if(document.formulario.NumeroCartaoCredito.length > 0){
				document.formulario.NumeroCartaoCreditoAux.value = document.formulario.NumeroCartaoCredito.options[document.formulario.NumeroCartaoCredito.selectedIndex].text;
			}
		});
	}
	
	function busca_conta_debito(IdPessoa,IdContaDebitoAux,IdStatus)
	{
		if(IdPessoa == "" || IdPessoa == "NULL") IdPessoa = 0;
		var url = "xml/pessoa_conta_debito.php?IdPessoa="+IdPessoa+"&IdLocalCobranca="+document.formulario.IdLocalCobranca.value;
		
		elSel = document.formulario.IdContaDebito;
		for (var i = elSel.length - 1; i>=0; i--) {
			elSel.remove(0);
		}
		
		call_ajax(url, function(xmlhttp){ 
			var nameNode, nameTextNode;
			
			if(xmlhttp.responseText != 'false'){		
				var nameNode, nameTextNode, IdPessoaEndereco,DescricaoEndereco;		
				addOption(document.formulario.IdContaDebito,"","");
				
				document.formulario.NumeroCartaoCredito.style.display = "block";
				document.getElementById("titNumeroContaDebito").style.display = "block";
				document.getElementById("cpNumeroContaDebito").style.display = "block";
				document.formulario.ObrigatoriedadeNumeroContaDebito.value = 1;
				
				for(i=0;i<xmlhttp.responseXML.getElementsByTagName("IdContaDebito").length;i++){
				
					var nameNode = xmlhttp.responseXML.getElementsByTagName("IdContaDebito")[i]; 
					var nameTextNode = nameNode.childNodes[0];
					var IdContaDebito = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("NumeroAgencia")[i]; 
					nameTextNode = nameNode.childNodes[0];
					var NumeroAgencia = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("DigitoAgencia")[i]; 
					nameTextNode = nameNode.childNodes[0];
					var DigitoAgencia = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("NumeroConta")[i]; 
					nameTextNode = nameNode.childNodes[0];
					var NumeroConta = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("DigitoConta")[i]; 
					nameTextNode = nameNode.childNodes[0];
					var DigitoConta = nameTextNode.nodeValue;
					
					var NumeroContaFinal = "";
					
					if(NumeroAgencia  != "" && DigitoAgencia != ""){
						NumeroContaFinal = NumeroAgencia+"-"+DigitoAgencia+" ";
					}else{
						NumeroContaFinal = NumeroAgencia+" ";
					}
					
					if(NumeroConta != "" && DigitoConta != ""){
						NumeroContaFinal += NumeroConta+"-"+DigitoConta;
					}else{
						NumeroContaFinal += NumeroConta;
					}
					
					addOption(document.formulario.IdContaDebito,NumeroContaFinal,IdContaDebito);
				}
				
				document.formulario.IdContaDebito.value = "";
				if(IdStatus == 2 || IdStatus == 0){
					document.formulario.IdContaDebito.disabled = true;
				}
				
				if(IdContaDebitoAux == ""){
					document.formulario.IdContaDebito.options[0].selected=true;
					if(IdStatus == 2 || IdStatus == 0){
						document.formulario.IdContaDebito.disabled = true;
					}
				}
				else{
					for(var i = 0; i < document.formulario.IdContaDebito.length; i++){
						if(document.formulario.IdContaDebito.options[i].value == IdContaDebitoAux){
							document.formulario.IdContaDebito.value = IdContaDebitoAux;
							break;
						}
					}
				}
			}else{
				elSel = document.formulario.IdContaDebito;
				for (var i = elSel.length - 1; i>=0; i--) {
					elSel.remove(0);
				}
				document.getElementById("titNumeroContaDebito").style.display = "none";
				document.getElementById("cpNumeroContaDebito").style.display = "none";
				document.formulario.ObrigatoriedadeNumeroContaDebito.value = "";
			}
			if(document.formulario.IdContaDebito.length > 0){
				document.formulario.NumeroContaDebito.value = document.formulario.IdContaDebito.options[document.formulario.IdContaDebito.selectedIndex].text;
			}
		});
	}
	
	function atualizar_NumeroContaDebito(){
		document.formulario.NumeroContaDebito.value = document.formulario.IdContaDebito.options[document.formulario.IdContaDebito.selectedIndex].text;
	}
	
	function atualizar_NumeroCartaCredito(){
		document.formulario.NumeroCartaoCreditoAux.value = document.formulario.NumeroCartaoCredito.options[document.formulario.NumeroCartaoCredito.selectedIndex].text;
	}
	
	function listarPosicaoCobranca(IdContaReceber,Erro){
		if(IdContaReceber == undefined || IdContaReceber==''){
			IdContaReceber = 0;
		}
		
		var TipoLocalCobraca = document.formulario.IdTipoLocalCobranca.value;
		
		if(TipoLocalCobraca == 3 || TipoLocalCobraca == 4 || TipoLocalCobraca == 6){
			if(document.formulario.PermissaoListarPosicoesCobranca.value != 1){
				return false;
			}
			
			var url = "xml/conta_receber_posicao_cobranca_default.php?IdContaReceber="+IdContaReceber;

			call_ajax(url, function(xmlhttp){ 
				var nameNode, nameTextNode;
				
				if(xmlhttp.responseText == 'false'){
					while(document.getElementById('tabelaPosicaoCobranca').rows.length > 1){
						document.getElementById('tabelaPosicaoCobranca').deleteRow(1);
					}
					
					document.getElementById('quantPosicaoCobranca').innerHTML = 0;
					document.getElementById('cp_listar_posicao_cobraca').style.display = "none";
				}else{
					while(document.getElementById('tabelaPosicaoCobranca').rows.length > 1){
						document.getElementById('tabelaPosicaoCobranca').deleteRow(1);
					}
					
					var tam, linha, c0, c1, c2,c3,cont=0;
					var RecebimentoDuplicado, IdContaReceberRecebimento,DataRecebimento,ValorDesconto,ValorRecebido,Valor,DescricaoLocalRecebimento,TotalDesc=0,MD5,IdRecibo,IdCaixa,TotalReceb=0,ValorContaReceber,IdStatusContaReceber;
					
					for(var i=0; i<xmlhttp.responseXML.getElementsByTagName("IdPosicaoCobranca").length; i++){	
						
						var nameNode = xmlhttp.responseXML.getElementsByTagName("IdLoja")[i]; 
						var nameTextNode = nameNode.childNodes[0];
						var IdLoja = nameTextNode.nodeValue;
						
						var nameNode = xmlhttp.responseXML.getElementsByTagName("PosicaoCobranca")[i]; 
						var nameTextNode = nameNode.childNodes[0];
						var PosicaoCobranca = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("IdArquivoRemessa")[i]; 
						nameTextNode = nameNode.childNodes[0];
						var IdArquivoRemessa = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("DataRemessa")[i]; 
						nameTextNode = nameNode.childNodes[0];
						var DataRemessa = nameTextNode.nodeValue;
												
						tam 	= document.getElementById('tabelaPosicaoCobranca').rows.length;
						linha	= document.getElementById('tabelaPosicaoCobranca').insertRow(tam);
						
						if(DataRemessa == "00/00/0000"){
							DataRemessa = "";
						}
						
						if(tam%2 != 0){
							linha.style.backgroundColor = "#E2E7ED";
						}
						
						linha.accessKey = IdContaReceberRecebimento; 
						
						c0	= linha.insertCell(0);	
						c1	= linha.insertCell(1);	
						c2	= linha.insertCell(2);	
						c3	= linha.insertCell(3);	
						
						var linkIni = "<a href='cadastro_conta_receber_recebimento.php?IdContaReceber="+IdContaReceber+"&IdContaReceberRecebimento="+IdContaReceberRecebimento+"'>";
						var linkFim = "</a>";
						
						c0.innerHTML = IdLoja + linkFim;
						c0.style.cursor  = "pointer";
						c0.style.padding =	"0 0 0 5px";
						
						c1.innerHTML = IdArquivoRemessa + linkFim;
						c1.style.cursor = "pointer";
						
						c2.innerHTML = PosicaoCobranca + "&nbsp;&nbsp;" ;
						c2.style.cursor = "pointer";
						c2.style.textAlign = "right";
						
						c3.innerHTML = DataRemessa + linkFim + "&nbsp;&nbsp;" ;
						c3.style.cursor = "pointer";
						c3.style.textAlign = "center";
						
						cont++;
					}
					
					document.getElementById('quantPosicaoCobranca').innerHTML = cont;
					document.getElementById('cp_listar_posicao_cobraca').style.display = "block";
				}
				
				if(window.janela != undefined){
					window.janela.close();
				}
			});
		}
	}
	function verificaNotaFiscal(IdContaReceber){
	
		var url = "xml/conta_receber_nota_fiscal.php?IdContaReceber="+IdContaReceber;
		
		call_ajax(url,function (xmlhttp){
			if(xmlhttp.responseText != 'false'){
				var nameNode = xmlhttp.responseXML.getElementsByTagName("IdContaReceber")[0]; 
				var nameTextNode = nameNode.childNodes[0];
				IdContaReceber = nameTextNode.nodeValue;

				nameNode = xmlhttp.responseXML.getElementsByTagName("Alerta")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var Alerta = nameTextNode.nodeValue;
				
				var confirmaNotaFiscal = confirm(Alerta);
				
				if(confirmaNotaFiscal == false){				
					cadastrar('alterar');
				}else{
					var url = "xml/conta_receber_nota_fiscal.php?IdContaReceber="+IdContaReceber+"&CancelarNotaFiscal=1";
					call_ajax(url,function (xmlhttp){});
					window.open("menu_conta_receber_nota_fiscal.php?IdContaReceber="+IdContaReceber+"&GeraNF=1");							
				}				
			}else{
				cadastrar('alterar');
			}
		});		
	}