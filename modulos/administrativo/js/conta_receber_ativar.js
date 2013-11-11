	function verificaAcao(){
		if(document.formulario.IdStatus.value == 0 || document.formulario.IdStatus.value == 7){
			document.formulario.bt_ativar.disabled		=	false;
		}else{
			document.formulario.bt_ativar.disabled		=	true;
		}
	}
	function cadastrar(){
		if(validar()==true){
			document.formulario.submit();
		}
	}
	function validar(){
		if(document.formulario.IdPosicaoCobranca.value == '-1' && (document.formulario.IdTipoLocalCobranca.value == '3' || document.formulario.IdTipoLocalCobranca.value == '4')){
			mensagens(1);
			document.formulario.IdPosicaoCobranca.focus();
			return false;
		}
		
		if(document.formulario.ObsAtivar.value==''){
			mensagens(1);
			document.formulario.ObsAtivar.focus();
			return false;
		}
		return true;
	}
	function inicia(){
		document.formulario.IdContaReceber.focus();
		status_inicial();
	}
	function busca_lancamento_financeiro(IdContaReceber,Erro,IdStatus){
		if(IdContaReceber == undefined || IdContaReceber==''){
			IdContaReceber = 0;
		}
		
		var url = "xml/demonstrativo.php?IdContaReceber="+IdContaReceber;
		
		call_ajax(url, function (xmlhttp) { 
			var nameNode, nameTextNode;
			
			if(xmlhttp.responseText == 'false'){
				document.getElementById('cp_lancamentos_financeiros').style.display	=	'none';						
				document.getElementById('tabelaLancFinanceiroTotalValor').innerHTML	=	"0,00";	
				document.getElementById('tabelaLancFinanceiroTotal').innerHTML		=	"Total: 0";	
			}else{
				document.getElementById('cp_lancamentos_financeiros').style.display	=	'block';	
				var cont=0,IdLancamentoFinanceiro,Tipo,Valor,IdContrato,IdContaEventual,NumParcelaEventual,QtdParcela,DescricaoContaEventual,DataReferenciaInicial,DataReferenciaFinal,DescricaoServico,TotalValor,IdPessoa,IdContrato,TotalValor=0;
				
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
					}
					
					if(Tipo == 'EV'){
						cont++;
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
		});
	}
	function listarRecebimento(IdContaReceber,Erro,IdContaReceberRecebimentoTemp){
		if(IdContaReceber == undefined || IdContaReceber==''){
			IdContaReceber = 0;
		}
		
		if(IdContaReceberRecebimentoTemp == undefined){
			IdContaReceberRecebimentoTemp = '';
		}
		
		var url = "xml/conta_receber_recebimento.php?IdContaReceber="+IdContaReceber;
		
		call_ajax(url, function (xmlhttp) { 
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
				var IdContaReceberRecebimento,DataRecebimento,ValorDesconto,ValorRecebido,Valor,DescricaoLocalRecebimento,TotalDesc=0,IdRecibo,TotalReceb=0;
				
				for(var i=0; i<xmlhttp.responseXML.getElementsByTagName("IdContaReceberRecebimento").length; i++){	
					nameNode = xmlhttp.responseXML.getElementsByTagName("IdContaReceberRecebimento")[i]; 
					nameTextNode = nameNode.childNodes[0];
					IdContaReceberRecebimento = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("DataRecebimento")[i]; 
					nameTextNode = nameNode.childNodes[0];
					DataRecebimento = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("ValorDescontoRecebimento")[i]; 
					nameTextNode = nameNode.childNodes[0];
					ValorDescontoRecebimento = nameTextNode.nodeValue;	
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("ValorRecebido")[i]; 
					nameTextNode = nameNode.childNodes[0];
					ValorRecebido = nameTextNode.nodeValue;	
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoLocalRecebimento")[i]; 
					nameTextNode = nameNode.childNodes[0];
					DescricaoLocalRecebimento = nameTextNode.nodeValue;	
					
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
					
					if(IdStatus == 0 || IdStatus == 3){ /*Cancelado || Estorno*/
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
					
					switch(IdStatus){
						case '0':
							c5.innerHTML = "Canc.";
							break;
						case '3':
							c5.innerHTML = "<a href='estorno.php?IdLoja="+IdLoja+"&IdContaReceber="+IdContaReceber+"&IdContaReceberRecebimento="+IdContaReceberRecebimento+"' target='_blank'>Estorno</a>";
							break;
						default:
							c5.innerHTML = "<a href='recibo.php?IdLoja="+IdLoja+"&IdRecibo="+IdRecibo+"' target='_blank'>"+IdRecibo+"</a>";
					}
					c5.style.cursor = "pointer";
					
					if(IdStatus == 1){
						c6.innerHTML    = "<a href='cadastro_cancelar_conta_receber_recebimento.php?IdContaReceber="+IdContaReceber+"&IdContaReceberRecebimento="+IdContaReceberRecebimento+"'><img src='../../img/estrutura_sistema/ico_del.gif' alt='Cancelar?'></a>";
					}else{
						c6.innerHTML    = "<img src='../../img/estrutura_sistema/ico_del_c.gif' alt='Cancelar?'>";
					}
					c6.style.cursor = "pointer";
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
	function listar_contrato(IdPessoa,IdContratoTemp){
		if(IdContratoTemp == undefined){
			IdContratoTemp = '';
		}
		
		url = "xml/contrato_agrupador.php?IdPessoa="+IdPessoa;
		
		call_ajax(url, function (xmlhttp) { 
			var nameNode, nameTextNode;
			
			if(xmlhttp.responseText == 'false'){
				while(document.formulario.IdContratoEstorno.options.length > 0){
					document.formulario.IdContratoEstorno.options[0] = null;
				}
			}else{
				while(document.formulario.IdContratoEstorno.options.length > 0){
					document.formulario.IdContratoEstorno.options[0] = null;
				}
				
				addOption(document.formulario.IdContratoEstorno,"","0");
				
				for(var i=0; i<xmlhttp.responseXML.getElementsByTagName("IdContrato").length; i++){
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("IdContrato")[i]; 
					nameTextNode = nameNode.childNodes[0];
					var IdContrato = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoContratoAgrupador")[i]; 
					nameTextNode = nameNode.childNodes[0];
					DescricaoContratoAgrupador = nameTextNode.nodeValue;
					
					var Descricao	=	"("+IdContrato+") "+DescricaoContratoAgrupador;
					
					addOption(document.formulario.IdContratoEstorno,Descricao,IdContrato);
				}
				if(IdContratoTemp!=''){
					for(ii=0;ii<document.formulario.IdContratoEstorno.length;ii++){
						if(document.formulario.IdContratoEstorno[ii].value == IdContratoTemp){
							document.formulario.IdContratoEstorno[ii].selected = true;
							break;
						}
					}
				}else{
					document.formulario.IdContratoEstorno[0].selected = true;
				}
			}
		});
	}
	function busca_pessoa_aproximada(campo,event){
		var url = "xml/pessoa_nome.php?Nome="+campo.value;
		
		call_ajax(url,function (xmlhttp){
			var NomeDefault = new Array(), nameNode, nameTextNode;
			
			if(campo.value != '' && xmlhttp.responseText != "false"){
				for(var i = 0; i < xmlhttp.responseXML.getElementsByTagName("NomeDefault").length; i++){
					nameNode = xmlhttp.responseXML.getElementsByTagName("NomeDefault")[i]; 
					nameTextNode = nameNode.childNodes[0];
					NomeDefault[i] = nameTextNode.nodeValue;
				}
			}
			
			busca_aproximada('filtro',campo,event,NomeDefault,22,5);
		},false);
	}
		
	function busca_posicao_cobranca(IdContaReceber, IdPosicaoCobrancaTemp){
		if(IdPosicaoCobrancaTemp == undefined){
			IdPosicaoCobrancaTemp = '';
		}
		
		var url = "xml/conta_receber_posicao_cobranca.php?IdGrupoParametroSistema=81&IdContaReceber="+IdContaReceber;
		
		call_ajax(url, function (xmlhttp) { 
			var nameNode, nameTextNode;
			
			if(xmlhttp.responseText == 'false'){
				while(document.formulario.IdPosicaoCobranca.options.length > 0){
					document.formulario.IdPosicaoCobranca.options[0] = null;
				}
			}else{
				while(document.formulario.IdPosicaoCobranca.options.length > 0){
					document.formulario.IdPosicaoCobranca.options[0] = null;
				}
				addOption(document.formulario.IdPosicaoCobranca,"","-1");
				addOption(document.formulario.IdPosicaoCobranca,"Excluir pedido de remessa de cancelamento","0");
				
				for(var i=0; i<xmlhttp.responseXML.getElementsByTagName("IdParametroSistema").length; i++){
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("IdParametroSistema")[i]; 
					nameTextNode = nameNode.childNodes[0];
					var IdParametroSistema = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("ValorParametroSistema")[i]; 
					nameTextNode = nameNode.childNodes[0];
					var ValorParametroSistema = nameTextNode.nodeValue;					

					if(IdParametroSistema == 10){	// Não enviar definitivamente							
						addOption(document.formulario.IdPosicaoCobranca,ValorParametroSistema,IdParametroSistema);						
					}
				}	
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("PosicaoCobrancaTemp")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var PosicaoCobrancaTemp = nameTextNode.nodeValue;

				nameNode = xmlhttp.responseXML.getElementsByTagName("DataRemessa")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var DataRemessa = nameTextNode.nodeValue;
				
				if(PosicaoCobrancaTemp == 5 && DataRemessa != '0000-00-00'){
					document.formulario.IdPosicaoCobranca.options[1] = null;
				}

				if(IdPosicaoCobrancaTemp!=''){
					for(ii=0;ii<document.formulario.IdPosicaoCobranca.length;ii++){
						if(document.formulario.IdPosicaoCobranca[ii].value == IdPosicaoCobrancaTemp){
							document.formulario.IdPosicaoCobranca[ii].selected = true;
							break;
						}
					}
				}else{
					document.formulario.IdPosicaoCobranca[0].selected = true;
				}
			}
		});
	}
