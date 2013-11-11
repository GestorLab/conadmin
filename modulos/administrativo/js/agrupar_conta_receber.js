	function cadastrar(acao){
		document.formulario.Acao.value = acao;
		
		switch(acao){
			case 'inserir':
				if(validar(acao) == true){
					document.formulario.submit();
				}
				break;
			case 'abrirContaReceber':
				if(document.formulario.IdContaReceberAgrupador.value != ''){
					window.open("./listar_conta_receber.php?IdContaReceberAgrupador="+document.formulario.IdContaReceberAgrupador.value);
				}
				break;
			default:
				document.formulario.submit();
				break;
		}
	}
	function validar_parcial(){
		if(document.formulario.IdPessoa.value == ''){
			mensagens(1);
			document.formulario.IdPessoa.focus();
			return false;
		}
		
		if(document.formulario.IdPessoaEndereco.value == ''){
			mensagens(1);
			document.formulario.IdPessoaEndereco.focus();
			return false;
		}
		
		if((document.getElementById("tabelaContaReceber").rows.length-2) < 2){
			mensagens(140);
			document.formulario.IdContaReceber.focus();
			return false;
		}
		
		if(document.formulario.DataVencimento.value == ''){
			mensagens(1);
			document.formulario.DataVencimento.focus();
			return false;
		}
		
		if(document.formulario.IdLocalCobranca.value == ''){
			mensagens(1);
			document.formulario.IdLocalCobranca.focus();
			return false;
		}
		
		if(document.formulario.QtdParcela.value == ''){
			mensagens(1);
			document.formulario.QtdParcela.focus();
			return false;
		}
		
		if(document.formulario.CalcularMulta.value == ''){
			mensagens(1);
			document.formulario.CalcularMulta.focus();
			return false;
		}
		
		if(document.formulario.ValorMoraMulta.value == ''){
			mensagens(1);
			document.formulario.ValorMoraMulta.focus();
			return false;
		}
		
		if(document.formulario.ValorJurosVencimento.value == ''){
			mensagens(1);
			document.formulario.ValorJurosVencimento.focus();
			return false;
		}
		
		if(document.formulario.ValorTaxaReImpressaoBoleto.value == ''){
			mensagens(1);
			document.formulario.ValorTaxaReImpressaoBoleto.focus();
			return false;
		}
		
		if(document.formulario.ValorOutrasDespesas.value == ''){
			mensagens(1);
			document.formulario.ValorOutrasDespesas.focus();
			return false;
		}
		
		if(document.formulario.ValorDescontoVencimento.value == ''){
			mensagens(1);
			document.formulario.ValorDescontoVencimento.focus();
			return false;
		}
		
		if(document.formulario.PercentualVencimento.value == ''){
			mensagens(1);
			document.formulario.PercentualVencimento.focus();
			return false;
		}
		
		return true;
	}
	function validar(){
		if(!validar_parcial()){
			return false;
		}
		
		if(document.getElementById("cp_Vencimento").style.display == "none"){
			mensagens(72);
			document.formulario.bt_simular.focus();
			
			return false;
		}
		
		if(parseFloat(document.getElementById("totalValor").innerHTML.replace(/,/g, ".")) != parseFloat(document.formulario.ValorFinalVencimento.value.replace(/,/g, "."))){
			mensagens(166);
			document.formulario.parcValor_1.focus();
			
			return false;
		}
		
		var tabelaVencimentoInput = document.getElementById("tabelaVencimento").getElementsByTagName("input");
		
		for(var i in tabelaVencimentoInput){
			if(tabelaVencimentoInput[i].name != undefined){
				if(tabelaVencimentoInput[i].name.substring(0, 9) == "parcData_"){
					if(!isData(tabelaVencimentoInput[i].value)){
						mensagens(27);
						tabelaVencimentoInput[i].focus();
						
						return false;
					} else if(formatDate(tabelaVencimentoInput[i].value) < formatDate(tabelaVencimentoInput[i].value)){
						mensagens(70);
						tabelaVencimentoInput[i].focus();
						
						return false;
					}
				}
			}
		}
		
		mensagens(0);
		
		return true;
	}
	function validar_Data(id,campo){
		if(campo.value == ''){
			document.getElementById(id).style.backgroundColor='#FFF';
			if(id == 'DataNF'){
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
			if(id == 'DataNF'){
				document.getElementById(id).style.color='#000';
			}else{
				document.getElementById(id).style.color='#C10000';
			}
			mensagens(0);
			return true;
		}	
	}
	function verificaAcao(){
		if(document.formulario.IdContaReceberAgrupador.value == ''){
			document.formulario.bt_abrir_conta_receber.disabled	= true;
			document.formulario.bt_cadastrar.disabled			= false;
		}else{
			document.formulario.bt_abrir_conta_receber.disabled	= false;
			document.formulario.bt_cadastrar.disabled			= true;
		}
	}
	function inicia(){
		statusInicial();
		document.formulario.IdContaReceberAgrupador.focus();
	}
	function busca_conta_receber_agrupar(IdContaReceber){
		if(document.formulario.IdPessoa.value == ''){
			document.formulario.IdContaReceber.value = '';
			document.formulario.NomePessoa.value = '';
			document.formulario.IdStatus.value = '';
			document.formulario.bt_add_conta_receber.disabled = true;
			
			document.formulario.IdPessoa.focus();
			return;
		}
		
		busca_conta_receber(IdContaReceber, false, document.formulario.Local.value);
	}
	function listar_conta_receber_agrupados(IdContaReceberAgrupados){
		if(IdContaReceberAgrupados == undefined || IdContaReceberAgrupados == null){
			IdContaReceberAgrupados = document.formulario.IdContaReceberAgrupados.value;
		}
		
		var url = "./xml/conta_receber.php?IdContaReceber="+IdContaReceberAgrupados;
		
		call_ajax(url, function (xmlhttp){
			if(xmlhttp.responseText != "false"){
				for(var i = 0; i < xmlhttp.responseXML.getElementsByTagName("IdContaReceber").length; i++){
					var nameNode = xmlhttp.responseXML.getElementsByTagName("IdContaReceber")[i];
					var nameTextNode = nameNode.childNodes[0];
					var IdContaReceber = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("Nome")[i];
					nameTextNode = nameNode.childNodes[0];
					var Nome = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("ValorReceber")[i]; 
					nameTextNode = nameNode.childNodes[0];
					var ValorReceber = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("DataVencimento")[i]; 
					nameTextNode = nameNode.childNodes[0];
					var DataVencimento = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("DataLancamento")[i]; 
					nameTextNode = nameNode.childNodes[0];
					var DataLancamento = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("NumeroNF")[i]; 
					nameTextNode = nameNode.childNodes[0];
					var NumeroNF = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("NumeroDocumento")[i]; 
					nameTextNode = nameNode.childNodes[0];
					var NumeroDocumento = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("RazaoSocial")[i];
					nameTextNode = nameNode.childNodes[0];
					var RazaoSocial = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoLocalCobranca")[i];
					nameTextNode = nameNode.childNodes[0];
					var DescricaoLocalCobranca = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("AbreviacaoNomeLocalCobranca")[i];
					nameTextNode = nameNode.childNodes[0];
					var AbreviacaoNomeLocalCobranca = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("TipoLancamentoFinanceiro")[i];
					nameTextNode = nameNode.childNodes[0];
					var TipoLancamentoFinanceiro = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("Status")[i]; 
					nameTextNode = nameNode.childNodes[0];
					var Status = nameTextNode.nodeValue;
					
					if(Nome != ""){
						var NomePessoaContaReceber = Nome;
					}
					
					if(RazaoSocial != ""){
						var NomePessoaContaReceber = RazaoSocial;
					}
					
					var cont = 0; ii='0';
					
					if(document.formulario.IdContaReceberAgrupados.value != ""){
						document.formulario.IdContaReceberAgrupados.value += ",";
					}
					
					document.formulario.IdContaReceberAgrupados.value += IdContaReceber;
					var tam = document.getElementById('tabelaContaReceber').rows.length;
					var linha = document.getElementById('tabelaContaReceber').insertRow(tam-1);
					
					if((tam % 2) != 0){
						linha.style.backgroundColor = "#E2E7ED";
					}
					
					linha.accessKey = IdContaReceber;
					var c0	= linha.insertCell(0);
					var c1	= linha.insertCell(1);
					var c2	= linha.insertCell(2);
					var c3	= linha.insertCell(3);
					var c4	= linha.insertCell(4);
					var c5	= linha.insertCell(5);
					var c6	= linha.insertCell(6);
					var c7	= linha.insertCell(7);
					var c8	= linha.insertCell(8);
					var c9	= linha.insertCell(9);
					var c10	= linha.insertCell(10);
					var linkIni = "<a href='cadastro_conta_receber.php?IdContaReceber="+IdContaReceber+"' target='_blank'>";
					var linkFim = "</a>";
					
					c0.innerHTML = linkIni + IdContaReceber + linkFim;
					c0.className = "tableListarEspaco";
					
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
					
					document.getElementById('totaltabelaContaReceber').innerHTML		= 'Total: '+(tam-1);
					document.getElementById('totalValorTabelaContaReceber').innerHTML	= (formata_float(Arredonda(temp,2),2).replace(/\./i, ','));
					document.formulario.IdContaReceber.value							= '';
					document.formulario.NomePessoa.value								= '';
					document.formulario.IdStatus.value									= '';
					document.formulario.bt_add_conta_receber.disabled					= true;
					document.formulario.ValorVencimento.value							= (formata_float(Arredonda(temp,2),2).replace(/\./i, ','));
				}
				
				calcular_novo_vencimento();
				simular_parcela(true);
				document.formulario.IdContaReceber.focus();
			}
		});
	}
	function calcular_novo_vencimento(name){
		var Valor = parseFloat((document.formulario.ValorVencimento.value.replace(/\./g, '')).replace(/,/i, '.'));
		
		if(isNaN(Valor)) 
			Valor = 0.00;
		
		var Multa = parseFloat((document.formulario.ValorMoraMulta.value.replace(/\./g, '')).replace(/,/i, '.'));
		
		if(isNaN(Multa)) 
			Multa = 0.00;
		
		var Juros = parseFloat((document.formulario.ValorJurosVencimento.value.replace(/\./g, '')).replace(/,/i, '.'));
		
		if(isNaN(Juros)) 
			Juros = 0.00;
		
		var TaxaReImpressao = parseFloat((document.formulario.ValorTaxaReImpressaoBoleto.value.replace(/\./g, '')).replace(/,/i, '.'));
		
		if(isNaN(TaxaReImpressao)) 
			TaxaReImpressao = 0.00;
		
		var OutrasDespesas = parseFloat((document.formulario.ValorOutrasDespesas.value.replace(/\./g, '')).replace(/,/i, '.'));
		
		if(isNaN(OutrasDespesas)) 
			OutrasDespesas = 0.00;
		
		var Desconto = parseFloat((document.formulario.ValorDescontoVencimento.value.replace(/\./g, '')).replace(/,/i, '.'));
		
		if(isNaN(Desconto)) 
			Desconto = 0.00;
		
		var PercentualVencimento = parseFloat((document.formulario.PercentualVencimento.value.replace(/\./g, '')).replace(/,/i, '.'));
		
		if(isNaN(PercentualVencimento)) 
			PercentualVencimento = 0.00;
		
		var ValorFinal = (Valor + Multa + Juros + TaxaReImpressao + OutrasDespesas);
		
		if(name != "PercentualVencimento"){
			PercentualVencimento = parseFloat((Desconto * 100) / ValorFinal);
			
			if(isNaN(PercentualVencimento)){
				PercentualVencimento = 0.00;
			}
			
			document.formulario.PercentualVencimento.value = formata_float(Arredonda(PercentualVencimento, 2), 2).replace(".",",");
		} else{
			Desconto = parseFloat((ValorFinal * PercentualVencimento) / 100);
			
			if(isNaN(Desconto)){
				Desconto = 0.00;
			}
			
			document.formulario.ValorDescontoVencimento.value = formata_float(Arredonda(Desconto, 2), 2).replace(".",",");
		}
		
		ValorFinal = ValorFinal - Desconto;
		
		if(isNaN(ValorFinal)) 
			ValorFinal = 0.00;
		
		document.formulario.ValorFinalVencimento.value = formata_float(Arredonda(ValorFinal, 2), 2).replace(".",",");
	}
	function busca_opcoes_pessoa_endereco(IdPessoa,IdPessoaEnderecoTemp){
		if(IdPessoaEnderecoTemp == undefined){
			IdPessoaEnderecoTemp = "";
		}
		
		while(document.formulario.IdPessoaEndereco.options.length > 0){
			document.formulario.IdPessoaEndereco.options[0] = null;
		}
		
		addOption(document.formulario.IdPessoaEndereco,"","");
		
		if(Number(IdPessoa) == 0){
			busca_pessoa_endereco();
		}
		
		if(IdPessoa != ""){
		    var url = "xml/pessoa_endereco.php?IdPessoa="+IdPessoa;
			
			call_ajax(url,function (xmlhttp){
				if(xmlhttp.responseText != 'false'){
					var nameNode, nameTextNode, IdPessoaEndereco,DescricaoEndereco;
					
					for(var i = 0; i < xmlhttp.responseXML.getElementsByTagName("IdPessoaEndereco").length; i++){
						nameNode = xmlhttp.responseXML.getElementsByTagName("IdPessoaEndereco")[i]; 
						nameTextNode = nameNode.childNodes[0];
						IdPessoaEndereco = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoEndereco")[i]; 
						nameTextNode = nameNode.childNodes[0];
						DescricaoEndereco = nameTextNode.nodeValue;
						
						addOption(document.formulario.IdPessoaEndereco,DescricaoEndereco,IdPessoaEndereco);
					}
					
					document.formulario.IdPessoaEndereco[0].selected = true;
					
					if(IdPessoaEnderecoTemp != ""){
						for(i = 0; i < document.formulario.IdPessoaEndereco.options.length; i++){
							if(document.formulario.IdPessoaEndereco[i].value == IdPessoaEnderecoTemp){
								document.formulario.IdPessoaEndereco[i].selected = true;
								i = document.formulario.IdPessoaEndereco.options.length;
								
								busca_pessoa_endereco(IdPessoa,IdPessoaEnderecoTemp);
							}
						}
					} else{
						busca_pessoa_endereco();
					}
				} else{
					busca_pessoa_endereco();
				}
			});
		}
	}
	function busca_pessoa_endereco(IdPessoa,IdPessoaEndereco){
		if(IdPessoa == ''){
			IdPessoa = 0;
		}
		
		if(IdPessoaEndereco == '' || IdPessoaEndereco == undefined){
			IdPessoaEndereco = 0;
		}
		
		var url = "xml/pessoa_endereco.php?IdPessoa="+IdPessoa+"&IdPessoaEndereco="+IdPessoaEndereco;
		
		call_ajax(url,function (xmlhttp){
			if(xmlhttp.responseText != 'false'){
				var nameNode = xmlhttp.responseXML.getElementsByTagName("IdPessoaEndereco")[0]; 
				var nameTextNode = nameNode.childNodes[0];
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
				
				document.formulario.NomeResponsavelEndereco.value	= NomeResponsavelEndereco;
				document.formulario.CEP.value						= CEP;
				document.formulario.Endereco.value					= Endereco;
				document.formulario.Numero.value					= Numero;
				document.formulario.Complemento.value				= Complemento;
				document.formulario.Bairro.value					= Bairro;
				document.formulario.IdPais.value					= IdPais;
				document.formulario.Pais.value						= NomePais;
				document.formulario.IdEstado.value					= IdEstado;
				document.formulario.Estado.value					= NomeEstado;
				document.formulario.IdCidade.value					= IdCidade;
				document.formulario.Cidade.value					= NomeCidade;
			} else{
				document.formulario.NomeResponsavelEndereco.value	= "";
				document.formulario.CEP.value						= "";
				document.formulario.Endereco.value					= "";
				document.formulario.Numero.value					= "";
				document.formulario.Complemento.value				= "";
				document.formulario.Bairro.value					= "";
				document.formulario.IdPais.value					= "";
				document.formulario.Pais.value						= "";
				document.formulario.IdEstado.value					= "";
				document.formulario.Estado.value					= "";
				document.formulario.IdCidade.value					= "";
				document.formulario.Cidade.value					= "";
			}
		});
	}
	function limparNovoVencimento(){
		document.formulario.DataVencimento.value				= '';
		document.formulario.IdLocalCobranca.value				= '';
		document.formulario.ValorVencimento.value				= "0,00";
		document.formulario.ValorDespesaLocalCobranca			= "0,00";
		document.formulario.ValorMoraMulta.value				= "0,00";
		document.formulario.ValorJurosVencimento.value			= "0,00";
		document.formulario.ValorTaxaReImpressaoBoleto.value	= "0,00";
		document.formulario.ValorOutrasDespesas.value			= "0,00";
		document.formulario.ValorDescontoVencimento.value		= "0,00";
		document.formulario.ValorFinalVencimento.value			= "0,00";
	}
	function remover_conta_receber_agrupado(IdContaReceber){
		for(var i=0; i<document.getElementById('tabelaContaReceber').rows.length; i++){
			if(IdContaReceber == document.getElementById('tabelaContaReceber').rows[i].accessKey){
				var temp = document.getElementById('tabelaContaReceber').rows[i].innerHTML;
				temp = temp.split("<span>")[1];
				temp = temp.split("</span>")[0];
				temp = temp.replace(/\./g, '');
				temp = parseFloat(temp.replace(/,/i, '.'));
				var Valor = document.getElementById('totalValorTabelaContaReceber').innerHTML;
				Valor = Valor.replace(/\./g, '');
				Valor = parseFloat(Valor.replace(/,/i, '.'));
				
				if(temp > Valor){
					Valor = temp - Valor;
				} else{
					Valor = Valor - temp;
				}
				
				document.getElementById('tabelaContaReceber').deleteRow(i);
				tableMultColor('tabelaContaReceber');
				break;
			}
		}
		
		var tempIdContaReceberAgrupados	=	document.formulario.IdContaReceberAgrupados.value.split(',');
		var novoIdContaReceberAgrupados  = '';
		var ii = 0;
		
		while(tempIdContaReceberAgrupados[ii] != undefined){
			if(tempIdContaReceberAgrupados[ii] != IdContaReceber){
				if(novoIdContaReceberAgrupados == ''){
					novoIdContaReceberAgrupados = tempIdContaReceberAgrupados[ii];
				} else{
					novoIdContaReceberAgrupados = novoIdContaReceberAgrupados + "," + tempIdContaReceberAgrupados[ii];
				}
			}
			
			ii++;
		}
		
		document.formulario.ValorVencimento.value = (formata_float(Arredonda(Valor,2),2).replace(/\./i, ','));
		document.formulario.IdContaReceberAgrupados.value = novoIdContaReceberAgrupados;
		document.getElementById('totaltabelaContaReceber').innerHTML = 'Total: '+(ii-1);
		document.getElementById('totalValorTabelaContaReceber').innerHTML = document.formulario.ValorVencimento.value;
		
		calcula_valor();
		simular_parcela(false);
	}
	function buscar_local_cobranca(IdLocalCobrancaTemp, litarTodos){
		if(IdLocalCobrancaTemp == undefined){
			IdLocalCobrancaTemp = '';
		}
		
		var url = "xml/local_cobranca.php";
		
		if(!litarTodos){
			url += "?IdStatus=1";
		}
		
		call_ajax(url,function (xmlhttp){
			if(xmlhttp.responseText != 'false'){
				while(document.formulario.IdLocalCobranca.options.length > 0){
					document.formulario.IdLocalCobranca.options[0] = null;
				}
				
				addOption(document.formulario.IdLocalCobranca,"","");
				
				var nameNode, nameTextNode, IdLocalCobranca;
				
				for(var i = 0; i < xmlhttp.responseXML.getElementsByTagName("IdLocalCobranca").length; i++){
					nameNode = xmlhttp.responseXML.getElementsByTagName("IdLocalCobranca")[i]; 
					nameTextNode = nameNode.childNodes[0];
					var IdLocalCobranca = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoLocalCobranca")[i]; 
					nameTextNode = nameNode.childNodes[0];
					var DescricaoLocalCobranca = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("IdStatus")[i]; 
					nameTextNode = nameNode.childNodes[0];
					var IdStatus = nameTextNode.nodeValue;
					
					if(IdLocalCobrancaTemp == IdLocalCobranca || IdStatus == 1){
						addOption(document.formulario.IdLocalCobranca, DescricaoLocalCobranca, IdLocalCobranca);
					}
				}
				
				if(IdLocalCobrancaTemp != ''){
					for(i = 0; i < document.formulario.IdLocalCobranca.length; i++){
						if(document.formulario.IdLocalCobranca[i].value == IdLocalCobrancaTemp){
							document.formulario.IdLocalCobranca[i].selected	=	true;
							break;
						}
					}
				} else{
					document.formulario.IdLocalCobranca[0].selected	=	true;
				}						
			} else{
				while(document.formulario.IdLocalCobranca.options.length > 0){
					document.formulario.IdLocalCobranca.options[0] = null;
				}
				
				addOption(document.formulario.IdLocalCobranca, '', '');
			}
			
			verificaAcao();
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
	function simular_parcela(Simular){
		var TabelaVencimento = document.getElementById("tabelaVencimento");
		
		if(!Simular || Simular == undefined){
			while(TabelaVencimento.rows.length > 2){
				TabelaVencimento.deleteRow(1);
			}
			
			document.getElementById('cp_Vencimento').style.display	= "none";
		} else{
			if(document.formulario.Acao.value == "inserir"){
				if(validar_parcial()){
					document.getElementById("cp_Vencimento").style.display = 'block';
					
					while(TabelaVencimento.rows.length > 2){
						TabelaVencimento.deleteRow(1);
					}
					
					var Dia = parseInt(document.formulario.DataVencimento.value.substring(0, 2).replace(/^[0]*/i, ''));
					var Mes = parseInt(document.formulario.DataVencimento.value.substring(3, 5).replace(/^[0]*/i, ''));
					var Ano = parseInt(document.formulario.DataVencimento.value.substring(6));
					var QtdParcela = parseInt(document.formulario.QtdParcela.value);
					
					if(isNaN(QtdParcela)){
						QtdParcela = 0;
					}
					
					var ValorFinalVencimento = parseFloat(document.formulario.ValorFinalVencimento.value.replace(/,/g, '.'));
					
					if(isNaN(ValorFinalVencimento)){
						ValorFinalVencimento = 0.00;
					}
					
					var ValorFinalVencimentoTemp = ValorFinalVencimento;
					var ValorParcelaVencimento = parseFloat((ValorFinalVencimentoTemp/QtdParcela).toFixed(2));
					
					if(parseFloat((ValorParcelaVencimento*QtdParcela).toFixed(2)) < ValorFinalVencimentoTemp){
						ValorParcelaVencimento += 0.01;
					}
					
					var ValorPercentualVencimento = parseFloat(((100*ValorParcelaVencimento)/ValorFinalVencimento).toFixed(2));
					var ValorDespesaLocalCobranca = parseFloat(document.formulario.ValorDespesaLocalCobranca.value.replace(/,/g, '.'));
					
					if(isNaN(ValorDespesaLocalCobranca)){
						ValorDespesaLocalCobranca = 0.00;
					}
					
					var ValorParcelaTotalVencimento = 0.00;
					var ValorDespesaTotalVencimento = 0.00;
					var ValorTotalTotalVencimento = 0.00;
					var ValorPercentualTotalVencimento = 0.00;
					var cont = 0;
					var MesTemp = Mes;
					var Tam = (MesTemp + QtdParcela);
					
					for(var i = Mes; i < Tam; i++){
						if(i < 13){
							Mes = i;
						} else{
							if(cont == 0){
								Mes = 1;
								Ano++;
								cont = 1;
							} else{
								Mes++;
							}
						}
						
						if(Mes == 12){
							cont = 0;
						}
						
						QTDDiasMes = parseInt(numDiasMes(Ano, Mes));
						
						if(Dia > QTDDiasMes){
							dianovo = QTDDiasMes;
						} else{
							dianovo = Dia;
						}
						
						if(dianovo < 10){
							dianovo = "0"+dianovo;
						}
						
						if(Mes < 10){
							Mes = "0"+Mes;
						}
						
						var IdParcela = ((i-MesTemp)+1);
						var Data = mostraDataFim(QTDDiasMes, dianovo+"/"+Mes+"/"+Ano);
						var ValorTotalVencimento = ValorParcelaVencimento+ValorDespesaLocalCobranca;
						ValorFinalVencimentoTemp -= ValorParcelaVencimento;
						ValorParcelaTotalVencimento += ValorParcelaVencimento;
						ValorPercentualTotalVencimento += ValorPercentualVencimento;
						ValorDespesaTotalVencimento += ValorDespesaLocalCobranca;
						ValorTotalTotalVencimento += ValorTotalVencimento;
						/* INSERIR UMA ROW */
						var tam = TabelaVencimento.rows.length;
						var linha = TabelaVencimento.insertRow(tam-1);
						
						if((tam%2) != 0){
							linha.style.backgroundColor = "#e2e7ed";
						}
						
						var tabindex = 14 * (IdParcela+1);
						linha.accessKey = IdParcela; 
						
						var c0 = linha.insertCell(0);
						var c1 = linha.insertCell(1);
						var c2 = linha.insertCell(2);
						var c3 = linha.insertCell(3);
						var c4 = linha.insertCell(4);
						var c5 = linha.insertCell(5);
						
						c0.className = "tableListarEspaco";
						c0.style.textAlign = "center";
						c0.innerHTML = IdParcela;
						c1.className = "valor";
						c1.innerHTML = "<input class='valor' style='margin:0; width:150px;' name='parcValor_"+IdParcela+"' value='"+formata_float(Arredonda(ValorParcelaVencimento,2),2).replace(/\./g, ",")+"' maxlength='16' onFocus=\"Foco(this,'in')\" onBlur=\"Foco(this,'out');\" onChange=\"calcular_valor_parcela(this);\" onkeypress='reais(this,event)' onkeydown='backspace(this,event)' tabindex='"+(tabindex)+"' />";
						c2.className = "valor";
						c2.innerHTML = "<input class='valor' style='margin:0; width:60px' name='parcPerc_"+IdParcela+"' value='"+formata_float(Arredonda(ValorPercentualVencimento,2),2).replace(/\./g, ",")+"' maxlength='6' onFocus=\"Foco(this,'in')\" onBlur=\"Foco(this,'out')\" onkeypress=\"mascara(this,event,'float')\" onkeydown='backspace(this,event)' tabindex="+(tabindex+1)+" onChange=\"calcular_valor_parcela(this)\" />";
						c3.className = "valor";
						c3.innerHTML = "<input class='valor' style='margin:0; width:150px' name='parcDesp_"+IdParcela+"' value='"+formata_float(Arredonda(ValorDespesaLocalCobranca,2),2).replace(/\./g, ",")+"' maxlength='16' onkeypress='reais(this,event)' onkeydown='backspace(this,event)' onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out');\" onChange=\"calcular_valor_parcela(this)\" tabindex="+(tabindex+2)+" />";
						c4.className = "valor";
						c4.innerHTML = "<input class='valor' style='margin:0; width:150px' name='parcTotal_"+IdParcela+"' value='"+formata_float(Arredonda(ValorTotalVencimento,2),2).replace(/\./g, ",")+"' readonly='readonly' />";
						c5.innerHTML = "<input style='margin:0; width: 100px' name='parcData_"+IdParcela+"' value='"+Data+"' maxlength='10' onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out')\" onkeypress=\"mascara(this,event,'date')\" tabindex='"+(tabindex+3)+"' />";
						/* CALCULAR A DIFERENÇA QUE FALTA PARA FECHAR O VALOR */
						if((parseFloat((ValorFinalVencimentoTemp/(QtdParcela-IdParcela)).toFixed(2))*(QtdParcela-IdParcela)).toFixed(2) == ValorFinalVencimentoTemp.toFixed(2)){
							ValorParcelaVencimento = parseFloat((ValorFinalVencimentoTemp/(QtdParcela-IdParcela)).toFixed(2));
							ValorPercentualVencimento = parseFloat(((100*ValorParcelaVencimento)/ValorFinalVencimento).toFixed(2));
						}
					}
					
					document.getElementById("totalVencimentos").innerHTML = "Total: "+QtdParcela;
					document.getElementById("totalValor").innerHTML = formata_float(Arredonda(ValorParcelaTotalVencimento, 2), 2).replace(/\./g, ",");
					document.getElementById("totalPercentual").innerHTML = formata_float(Arredonda(ValorPercentualTotalVencimento, 2), 2).replace(/\./g, ",");
					document.getElementById("totalValorDespesa").innerHTML = formata_float(Arredonda(ValorDespesaTotalVencimento, 2), 2).replace(/\./g, ",");
					document.getElementById("totalValorTotal").innerHTML = formata_float(Arredonda(ValorTotalTotalVencimento, 2), 2).replace(/\./g, ",");
				}
			} else{
				var url = "./xml/agrupar_conta_receber.php?IdContaReceberAgrupador="+document.formulario.IdContaReceberAgrupador.value;
				
				call_ajax(url, function (xmlhttp) {
					if(xmlhttp.responseText != "false"){
						document.getElementById("cp_Vencimento").style.display = 'block';
						
						while(TabelaVencimento.rows.length > 2){
							TabelaVencimento.deleteRow(1);
						}
						
						var nameNode = xmlhttp.responseXML.getElementsByTagName("IdContaReceberAgrupador")[0];
						var nameTextNode = nameNode.childNodes[0];
						var IdContaReceberAgrupador = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("ValorDespesaLocalCobranca")[0];
						nameTextNode = nameNode.childNodes[0];
						var ValorDespesaLocalCobranca = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("ValorTotal")[0];
						nameTextNode = nameNode.childNodes[0];
						var ValorParcelaTotalVencimento = parseFloat(nameTextNode.nodeValue);
						var ContaReceberAgrupadoParcela = xmlhttp.responseXML.getElementsByTagName("ContaReceberAgrupadoParcela")[0];
						var ValorPercentualTotalVencimento = 0.00;
						var ValorDespesaTotalVencimento = 0.00;
						
						for(var i = 0; i < ContaReceberAgrupadoParcela.getElementsByTagName("IdContaReceber").length; i++){
							nameNode = ContaReceberAgrupadoParcela.getElementsByTagName("IdContaReceber")[i];
							nameTextNode = nameNode.childNodes[0];
							var IdContaReceber = nameTextNode.nodeValue;
							
							nameNode = ContaReceberAgrupadoParcela.getElementsByTagName("NumParcelaContaReceberAgrupado")[i];
							nameTextNode = nameNode.childNodes[0];
							var NumParcelaContaReceberAgrupado = nameTextNode.nodeValue;
							
							nameNode = ContaReceberAgrupadoParcela.getElementsByTagName("ValorParcela")[i];
							nameTextNode = nameNode.childNodes[0];
							var ValorParcela = nameTextNode.nodeValue;
							
							nameNode = ContaReceberAgrupadoParcela.getElementsByTagName("ValorDespesas")[i];
							nameTextNode = nameNode.childNodes[0];
							var ValorDespesas = nameTextNode.nodeValue;
							
							nameNode = ContaReceberAgrupadoParcela.getElementsByTagName("DataVencimento")[i];
							nameTextNode = nameNode.childNodes[0];
							var DataVencimento = nameTextNode.nodeValue;
							/* INSERIR UMA ROW */
							var tam = TabelaVencimento.rows.length;
							var linha = TabelaVencimento.insertRow(tam-1);
							
							if((tam%2) != 0){
								linha.style.backgroundColor = "#e2e7ed";
							}
							
							ValorParcela = parseFloat(ValorParcela);
							ValorDespesas = parseFloat(ValorDespesas);
							ValorDespesaTotalVencimento += ValorDespesas;
							var ValorPercentualVencimento = ((ValorParcela*100)/ValorParcelaTotalVencimento);
							ValorPercentualTotalVencimento += ValorPercentualVencimento;
							var ValorTotalVencimento = (ValorParcela+ValorDespesas);
							IdParcela = NumParcelaContaReceberAgrupado;
							var tabindex = 14 * (IdParcela+1);
							linha.accessKey = IdParcela; 
							
							var c0 = linha.insertCell(0);
							var c1 = linha.insertCell(1);
							var c2 = linha.insertCell(2);
							var c3 = linha.insertCell(3);
							var c4 = linha.insertCell(4);
							var c5 = linha.insertCell(5);
							
							c0.className = "tableListarEspaco";
							c0.style.textAlign = "center";
							c0.innerHTML = IdParcela;
							c1.className = "valor";
							c1.innerHTML = "<input class='valor' style='margin:0; width:150px;' name='parcValor_"+IdParcela+"' value='"+formata_float(Arredonda(ValorParcela,2),2).replace(/\./g, ",")+"' maxlength='16' readonly='readonly' />";
							c2.className = "valor";
							c2.innerHTML = "<input class='valor' style='margin:0; width:60px' name='parcPerc_"+IdParcela+"' value='"+formata_float(Arredonda(ValorPercentualVencimento,2),2).replace(/\./g, ",")+"' maxlength='6' readonly='readonly' />";
							c3.className = "valor";
							c3.innerHTML = "<input class='valor' style='margin:0; width:150px' name='parcDesp_"+IdParcela+"' value='"+formata_float(Arredonda(ValorDespesas,2),2).replace(/\./g, ",")+"' maxlength='16' readonly='readonly' />";
							c4.className = "valor";
							c4.innerHTML = "<input class='valor' style='margin:0; width:150px' name='parcTotal_"+IdParcela+"' value='"+formata_float(Arredonda(ValorTotalVencimento,2),2).replace(/\./g, ",")+"' readonly='readonly' />";
							c5.innerHTML = "<input style='margin:0; width: 100px' name='parcData_"+IdParcela+"' value='"+dateFormat(DataVencimento)+"' maxlength='10' readonly='readonly' />";
						}
						
						var ValorTotalTotalVencimento = ValorParcelaTotalVencimento+ValorDespesaTotalVencimento;
						document.getElementById("totalVencimentos").innerHTML = "Total: "+i;
						document.getElementById("totalValor").innerHTML = formata_float(Arredonda(ValorParcelaTotalVencimento, 2), 2).replace(/\./g, ",");
						document.getElementById("totalPercentual").innerHTML = formata_float(Arredonda(ValorPercentualTotalVencimento, 2), 2).replace(/\./g, ",");
						document.getElementById("totalValorDespesa").innerHTML = formata_float(Arredonda(ValorDespesaTotalVencimento, 2), 2).replace(/\./g, ",");
						document.getElementById("totalValorTotal").innerHTML = formata_float(Arredonda(ValorTotalTotalVencimento, 2), 2).replace(/\./g, ",");
					}
				});
			}
		}
	}
	function calcular_valor_parcela(Campo){
		var Temp = Campo.name.split(/_/);
		var ParcValorTotal = 0.00;
		var ParcPercTotal = 0.00;
		var ParcDespTotal = 0.00;
		var ParcTotalTotal = 0.00;
		var ValorFinalVencimento = parseFloat(document.formulario.ValorFinalVencimento.value.replace(/,/g, '.'));
		
		if(isNaN(ValorFinalVencimento)){
			ValorFinalVencimento = 0.00;
		}
		
		var ValorFinalVencimentoTemp = ValorFinalVencimento;
		var ParcValor = 0.00;
		var ParcPerc = 0.00;
		var ParcDesp = 0.00;
		var ParcTotal = 0.00;
		
		if(Temp[0] == "parcPerc"){
			ParcValor = parseFloat(((ValorFinalVencimento*parseFloat(Campo.value.replace(/,/g, ".")))/100).toFixed(2));
			
			if(isNaN(ParcValor)){
				ParcValor = 0.00;
			}
			
			eval("var CampoParcValor = document.formulario.parcValor_"+Temp[1]+";");
			
			CampoParcValor.value = formata_float(Arredonda(ParcValor, 2), 2).replace(/\./g, ",");
		}
		
		for(var i = 1; i <= document.formulario.QtdParcela.value; i++){
			eval("var CampoParcValor = document.formulario.parcValor_"+i+";");
			eval("var CampoParcPerc = document.formulario.parcPerc_"+i+";");
			eval("var CampoParcDesp = document.formulario.parcDesp_"+i+";");
			eval("var CampoParcTotal = document.formulario.parcTotal_"+i+";");
			
			if(parseInt(Temp[1]) < i && (Temp[0] == "parcValor" || Temp[0] == "parcPerc")){
				ParcValor = parseFloat((ValorFinalVencimentoTemp/((document.formulario.QtdParcela.value-i)+1)).toFixed(2));
				
				if(isNaN(ParcValor)){
					ParcValor = 0.00;
				}
				
				CampoParcValor.value = formata_float(Arredonda(ParcValor, 2), 2).replace(/\./g, ",");
			} else{
				ParcValor = parseFloat(CampoParcValor.value.replace(/,/g, "."));
				
				if(isNaN(ParcValor)){
					ParcValor = 0.00;
				}
			}
			
			ValorFinalVencimentoTemp -= ParcValor;
			ParcPerc = parseFloat(((100*ParcValor)/ValorFinalVencimento).toFixed(2));
			ParcDesp = parseFloat(CampoParcDesp.value.replace(/,/g, "."));
			
			if(isNaN(ParcDesp)){
				ParcDesp = 0.00;
			}
			
			ParcTotal = ParcValor+ParcDesp;
			ParcValorTotal += ParcValor;
			ParcPercTotal += ParcPerc;
			ParcDespTotal += ParcDesp;
			ParcTotalTotal += ParcTotal;
			
			CampoParcPerc.value = formata_float(Arredonda(ParcPerc, 2), 2).replace(/\./g, ",")
			CampoParcTotal.value = formata_float(Arredonda(ParcTotal, 2), 2).replace(/\./g, ",")
		}
		
		document.getElementById("totalValor").innerHTML = formata_float(Arredonda(ParcValorTotal, 2), 2).replace(/\./g, ",");
		document.getElementById("totalPercentual").innerHTML = formata_float(Arredonda(ParcPercTotal, 2), 2).replace(/\./g, ",");
		document.getElementById("totalValorDespesa").innerHTML = formata_float(Arredonda(ParcDespTotal, 2), 2).replace(/\./g, ",");
		document.getElementById("totalValorTotal").innerHTML = formata_float(Arredonda(ParcTotalTotal, 2), 2).replace(/\./g, ",");
	}
	function calcula_valor(campo){
		var tabelaContaReceber = document.getElementById("tabelaContaReceber"), ValorTotal = 0.00, MoraTotal = 0.00, JurosDiariosTotal = 0.00;
		var PercentualJurosDiarios = parseFloat(document.formulario.PercentualJurosDiarios.value);
		var PercentualMulta = parseFloat(document.formulario.PercentualMulta.value);
		
		for(var i = 1; i < (tabelaContaReceber.rows.length - 1); i++){
			var IdContrato = (tabelaContaReceber.rows[i].accessKey);
			
			eval("var Valor = parseFloat(document.formulario.Valor_"+IdContrato+".value); var DataVencimento = document.formulario.DataVencimento_"+IdContrato+".value;");
			
			ValorTotal += Valor;
			var QTDDias = difDias(document.formulario.DataVencimento.value, DataVencimento);
			
			if(QTDDias < 0) 
				QTDDias = 0;
			
			var Mora = ((Valor * PercentualMulta) / 100);
			
			if(isNaN(Mora)) 
				Mora = 0.00;
			
			MoraTotal += Mora;
			var JurosDiarios = (((Valor * PercentualJurosDiarios) / 100) * parseInt(QTDDias));
			
			if(isNaN(JurosDiarios)) 
				JurosDiarios = 0.00;
			
			JurosDiariosTotal += JurosDiarios;
		}
		
		if(document.formulario.CalcularMulta.value != "1")
			MoraTotal = JurosDiariosTotal = 0.00;
		
		document.formulario.ValorVencimento.value = formata_float(Arredonda(ValorTotal,2),2).replace(/\./g, ",");
		document.formulario.ValorMoraMulta.value = formata_float(Arredonda(MoraTotal,2),2).replace(/\./g, ",");
		document.formulario.ValorJurosVencimento.value = formata_float(Arredonda(JurosDiariosTotal,2),2).replace(/\./g, ",");
		
		calcular_novo_vencimento();
	}
	function busca_status(){
		document.getElementById("cp_Status").style.display	= "none";
		document.getElementById("cp_Status").innerHTML		= "";
		
		var url = "xml/agrupar_conta_receber_status.php?IdContaReceberAgrupador="+document.formulario.IdContaReceberAgrupador.value;
		
		call_ajax(url,function (xmlhttp){
			if(xmlhttp.responseText != 'false'){
				nameNode = xmlhttp.responseXML.getElementsByTagName("ValorParametroSistema")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var ValorParametroSistema = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("Parcela")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var Parcela = nameTextNode.nodeValue;
				
				if(Parcela != ''){
					ValorParametroSistema += "<br><span style='font-size:9px;'>" + Parcela + "</span>";
				}
				
				document.getElementById("cp_Status").style.display		= "block";
				document.getElementById("cp_Status").style.color		= "#55930C";
				document.getElementById("cp_Status").style.fontSize		= "15px";
				document.getElementById("cp_Status").style.lineHeight	= "11px";
				document.getElementById('cp_Status').innerHTML			= ValorParametroSistema;
			}
		});
	} 