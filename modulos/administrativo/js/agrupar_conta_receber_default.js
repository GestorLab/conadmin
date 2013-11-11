	function busca_agrupar_conta_receber(IdContaReceberAgrupador,Erro,Local) {
		if (IdContaReceberAgrupador == undefined) {
			IdContaReceberAgrupador = '';
		}
		
		var url = "./xml/agrupar_conta_receber.php?IdContaReceberAgrupador=" + IdContaReceberAgrupador;
		
		call_ajax(url,function (xmlhttp){
			if(Erro != false){
				document.formulario.Erro.value = 0;
				verificaErro();
			}
			
			if(xmlhttp.responseText == "false"){
				switch(Local){
					case "AgruparContaReceber":
						document.formulario.Buca.value							= 0;
						document.formulario.IdContaReceberAgrupador.value		= '';
						document.formulario.IdContaReceberAgrupados.value		= '';
						document.formulario.IdPessoaEndereco.value				= '';
						document.formulario.DataVencimento.value				= '';
						document.formulario.IdLocalCobranca.value				= '';
						document.formulario.QtdParcela.value					= '';
						document.formulario.OcultarReferencia.value				= '';
						document.formulario.LoginCriacao.value					= '';
						document.formulario.DataCriacao.value					= '';
						document.formulario.ValorMoraMulta.value				= '';
						document.formulario.ValorJurosVencimento.value			= '';
						document.formulario.ValorTaxaReImpressaoBoleto.value	= '';
						document.formulario.ValorDespesaLocalCobranca.value		= '';
						document.formulario.ValorOutrasDespesas.value			= '';
						document.formulario.ValorDescontoVencimento.value		= '';
						document.formulario.Acao.value 							= "inserir";
						
						document.formulario.IdPessoa.readOnly					= false;
						document.formulario.IdPessoaEndereco.disabled			= false;
						document.formulario.IdContaReceber.readOnly				= false;
						document.formulario.CalcularMulta.disabled				= false;
						document.formulario.OcultarReferencia.disabled			= false;
						document.formulario.IdLocalCobranca.disabled			= false;
						document.formulario.DataVencimento.readOnly				= false;
						document.formulario.QtdParcela.readOnly					= false;
						document.formulario.ValorDespesaLocalCobranca.readOnly	= false;
						document.formulario.ValorMoraMulta.readOnly				= false;
						document.formulario.ValorJurosVencimento.readOnly		= false;
						document.formulario.ValorTaxaReImpressaoBoleto.readOnly	= false;
						document.formulario.ValorOutrasDespesas.readOnly		= false;
						document.formulario.ValorDescontoVencimento.readOnly	= false;
						document.formulario.PercentualVencimento.readOnly		= false;
						document.formulario.bt_simular.disabled					= false;
						
						document.getElementById("titIdPessoaEndereco").style.color = "#c10000";
						
						document.formulario.IdContaReceber.onfocus = function () {
							Foco(this, "in"); 
						};
						
						document.getElementById("cpCalcularMulta").style.color = "#c10000";
						
						document.getElementById("titOcultarReferencia").style.color = "#c10000";
						
						document.getElementById("titIdLocalCobranca").style.color = "#c10000";
						
						document.getElementById("titDataVencimento").style.color = "#c10000";
						document.formulario.DataVencimento.onfocus = function () {
							Foco(this, "in", true); 
						};
						
						document.getElementById("titQTDParcelas").style.color = "#c10000";
						document.formulario.QtdParcela.onfocus = function () {
							Foco(this, "in"); 
						};
						
						document.formulario.ValorDespesaLocalCobranca.onfocus = function () {
							Foco(this, "in"); 
						};
						
						document.getElementById("titValorMoraMulta").style.color = "#c10000";
						document.formulario.ValorMoraMulta.onfocus = function () {
							Foco(this, "in"); 
						};
						
						document.getElementById("titValorJurosVencimento").style.color = "#c10000";
						document.formulario.ValorJurosVencimento.onfocus = function () {
							Foco(this, "in"); 
						};
						
						document.getElementById("titValorTaxaReImpressaoBoleto").style.color = "#c10000";
						document.formulario.ValorTaxaReImpressaoBoleto.onfocus = function () {
							Foco(this, "in"); 
						};
						
						document.getElementById("titValorOutrasDespesas").style.color = "#c10000";
						document.formulario.ValorOutrasDespesas.onfocus = function () {
							Foco(this, "in"); 
						};
						
						document.getElementById("titValorDescontoVencimento").style.color = "#c10000";
						document.formulario.ValorDescontoVencimento.onfocus = function () {
							Foco(this, "in"); 
						};
						
						document.getElementById("titPercentualVencimento").style.color = "#c10000";
						document.formulario.PercentualVencimento.onfocus = function () {
							Foco(this, "in"); 
						};
						
						busca_status();
						addParmUrl("marAgruparContaReceber","IdContaReceberAgrupador",'');
						addParmUrl("marContasReceber","IdContaReceberAgrupador",'');
						buscar_local_cobranca('', false)
						busca_pessoa(0, false, Local);
						simular_parcela(false);
						inicia();
						
						document.formulario.IdContaReceberAgrupador.focus();
						break;
				}
			} else{
				var nameNode = xmlhttp.responseXML.getElementsByTagName("IdContaReceberAgrupador")[0];
				var nameTextNode = nameNode.childNodes[0];
				var IdContaReceberAgrupador = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("IdPessoa")[0];
				nameTextNode = nameNode.childNodes[0];
				var IdPessoa = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("IdPessoaEndereco")[0];
				nameTextNode = nameNode.childNodes[0];
				var IdPessoaEndereco = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("IdContaReceberAgrupados")[0];
				nameTextNode = nameNode.childNodes[0];
				var IdContaReceberAgrupados = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("IdLocalCobranca")[0];
				nameTextNode = nameNode.childNodes[0];
				var IdLocalCobranca = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("QtdParcela")[0];
				nameTextNode = nameNode.childNodes[0];
				var QtdParcela = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("OcultarReferencia")[0];
				nameTextNode = nameNode.childNodes[0];
				var OcultarReferencia = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("ValorDespesaLocalCobranca")[0];
				nameTextNode = nameNode.childNodes[0];
				var ValorDespesaLocalCobranca = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("ValorDesconto")[0];
				nameTextNode = nameNode.childNodes[0];
				var ValorDesconto = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("ValorMulta")[0];
				nameTextNode = nameNode.childNodes[0];
				var ValorMulta = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("ValorJuros")[0];
				nameTextNode = nameNode.childNodes[0];
				var ValorJuros = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("ValorTaxaReImpressaoBoleto")[0];
				nameTextNode = nameNode.childNodes[0];
				var ValorTaxaReImpressaoBoleto = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("ValorOutrasDespesas")[0];
				nameTextNode = nameNode.childNodes[0];
				var ValorOutrasDespesas = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("LoginCriacao")[0];
				nameTextNode = nameNode.childNodes[0];
				var LoginCriacao = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("DataCriacao")[0];
				nameTextNode = nameNode.childNodes[0];
				var DataCriacao = nameTextNode.nodeValue;
				
				switch (Local) {
					case "AgruparContaReceber":
						var ContaReceberAgrupadoParcela = xmlhttp.responseXML.getElementsByTagName("ContaReceberAgrupadoParcela")[0];
						
						nameNode = ContaReceberAgrupadoParcela.getElementsByTagName("DataVencimento")[0];
						nameTextNode = nameNode.childNodes[0];
						var DataVencimento = nameTextNode.nodeValue;
						
						document.formulario.Buca.value							= 1;
						document.formulario.IdContaReceberAgrupador.value		= IdContaReceberAgrupador;
						document.formulario.IdContaReceberAgrupados.value		= IdContaReceberAgrupados;
						document.formulario.DataVencimento.value				= dateFormat(DataVencimento);
						document.formulario.QtdParcela.value					= QtdParcela;
						document.formulario.OcultarReferencia.value				= OcultarReferencia;
						document.formulario.ValorMoraMulta.value				= formata_float(Arredonda(ValorMulta,2),2).replace(/\./i, ',');
						document.formulario.ValorJurosVencimento.value			= formata_float(Arredonda(ValorJuros,2),2).replace(/\./i, ',');
						document.formulario.ValorTaxaReImpressaoBoleto.value	= formata_float(Arredonda(ValorTaxaReImpressaoBoleto,2),2).replace(/\./i, ',');
						document.formulario.ValorDespesaLocalCobranca.value		= formata_float(Arredonda(ValorDespesaLocalCobranca,2),2).replace(/\./i, ',');
						document.formulario.ValorOutrasDespesas.value			= formata_float(Arredonda(ValorOutrasDespesas,2),2).replace(/\./i, ',');
						document.formulario.ValorDescontoVencimento.value		= formata_float(Arredonda(ValorDesconto,2),2).replace(/\./i, ',');
						document.formulario.LoginCriacao.value					= LoginCriacao;
						document.formulario.DataCriacao.value					= dateFormat(DataCriacao);
						document.formulario.Acao.value 							= "alterar";
						
						busca_status();
						addParmUrl("marAgruparContaReceber","IdContaReceberAgrupador",IdContaReceberAgrupador);
						addParmUrl("marContasReceber","IdContaReceberAgrupador",IdContaReceberAgrupador);
						buscar_local_cobranca(IdLocalCobranca, true)
						busca_pessoa(IdPessoa, false, document.formulario.Local.value);
						busca_opcoes_pessoa_endereco(IdPessoa,IdPessoaEndereco);
						
						setTimeout(function (){
							listar_conta_receber_agrupados(IdContaReceberAgrupados);
						}, 200);
						
						document.formulario.IdPessoa.readOnly					= true;
						document.formulario.IdPessoaEndereco.disabled			= true;
						document.formulario.IdContaReceber.readOnly				= true;
						document.formulario.CalcularMulta.disabled				= true;
						document.formulario.OcultarReferencia.disabled			= true;
						document.formulario.IdLocalCobranca.disabled			= true;
						document.formulario.DataVencimento.readOnly				= true;
						document.formulario.QtdParcela.readOnly					= true;
						document.formulario.ValorDespesaLocalCobranca.readOnly	= true;
						document.formulario.ValorMoraMulta.readOnly				= true;
						document.formulario.ValorJurosVencimento.readOnly		= true;
						document.formulario.ValorTaxaReImpressaoBoleto.readOnly	= true;
						document.formulario.ValorOutrasDespesas.readOnly		= true;
						document.formulario.ValorDescontoVencimento.readOnly	= true;
						document.formulario.PercentualVencimento.readOnly		= true;
						document.formulario.bt_simular.disabled					= true;
						
						document.getElementById("titIdPessoaEndereco").style.color = "#000";
						
						document.formulario.IdContaReceber.onfocus = function () {};
						
						document.getElementById("cpCalcularMulta").style.color = "#000";
						
						document.getElementById("titOcultarReferencia").style.color = "#000";
						
						document.getElementById("titIdLocalCobranca").style.color = "#000";
						
						document.getElementById("titDataVencimento").style.color = "#000";
						document.formulario.DataVencimento.onfocus = function () {};
						
						document.getElementById("titQTDParcelas").style.color = "#000";
						document.formulario.QtdParcela.onfocus = function () {};
						
						document.formulario.ValorDespesaLocalCobranca.onfocus = function () {};
						
						document.getElementById("titValorMoraMulta").style.color = "#000";
						document.formulario.ValorMoraMulta.onfocus = function () {};
						
						document.getElementById("titValorJurosVencimento").style.color = "#000";
						document.formulario.ValorJurosVencimento.onfocus = function () {};
						
						document.getElementById("titValorTaxaReImpressaoBoleto").style.color = "#000";
						document.formulario.ValorTaxaReImpressaoBoleto.onfocus = function () {};
						
						document.getElementById("titValorOutrasDespesas").style.color = "#000";
						document.formulario.ValorOutrasDespesas.onfocus = function () {};
						
						document.getElementById("titValorDescontoVencimento").style.color = "#000";
						document.formulario.ValorDescontoVencimento.onfocus = function () {};
						
						document.getElementById("titPercentualVencimento").style.color = "#000";
						document.formulario.PercentualVencimento.onfocus = function () {};
						break;
				}
			}
			
			if (window.janela != undefined) {
				window.janela.close();
			}
			
			verificaAcao();
		});
	}