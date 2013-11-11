	function verificaAcao(){
		if(document.formulario != undefined){
			switch(document.formulario.Acao.value){
				case "inserir":
					document.formulario.bt_exportar.disabled	= true;
					document.formulario.bt_visualizar.disabled	= true;
					document.formulario.bt_inserir.disabled		= false;
					document.formulario.bt_excluir.disabled		= true;
					document.formulario.bt_processar.disabled	= true;
					document.formulario.bt_confirmar.disabled	= true;
					document.formulario.bt_entrega.disabled		= true;
					document.formulario.bt_cancelar.disabled	= true;
					break;
				case "processar":
					document.formulario.bt_exportar.disabled	= true;
					document.formulario.bt_visualizar.disabled	= true;
					document.formulario.bt_inserir.disabled		= true;
					document.formulario.bt_excluir.disabled		= false;
					document.formulario.bt_processar.disabled	= false;
					document.formulario.bt_confirmar.disabled	= true;
					document.formulario.bt_entrega.disabled		= true;
					document.formulario.bt_cancelar.disabled	= true;
					break;
				case "confirmar":
					document.formulario.bt_exportar.disabled	= true;
					document.formulario.bt_visualizar.disabled	= false;
					document.formulario.bt_inserir.disabled		= true;
					document.formulario.bt_excluir.disabled		= true;
					document.formulario.bt_processar.disabled	= true;
					document.formulario.bt_confirmar.disabled	= false;
					document.formulario.bt_entrega.disabled		= true;
					document.formulario.bt_cancelar.disabled	= false;
					break;
				case "entregar":
					document.formulario.bt_exportar.disabled	= false;
					document.formulario.bt_visualizar.disabled	= false;
					document.formulario.bt_inserir.disabled		= true;
					document.formulario.bt_excluir.disabled		= true;
					document.formulario.bt_processar.disabled	= true;
					document.formulario.bt_confirmar.disabled	= true;
					document.formulario.bt_entrega.disabled		= false;
					document.formulario.bt_cancelar.disabled	= false;
					break;
				default:
					document.formulario.bt_exportar.disabled	= false;
					document.formulario.bt_visualizar.disabled	= false;
					document.formulario.bt_inserir.disabled		= true;
					document.formulario.bt_excluir.disabled		= true;
					document.formulario.bt_processar.disabled	= true;
					document.formulario.bt_confirmar.disabled	= true;
					document.formulario.bt_entrega.disabled		= true;
					document.formulario.bt_cancelar.disabled	= true;
			}
		}
		
		verificar_obrigatoriedade();
	}
	function verificaErro(){
		var nerro = parseInt(document.formulario.Erro.value);
		
		mensagens(nerro,document.formulario.Local.value);
	}
	function cadastrar(acao){
		if(acao == "cancelar"){
			document.formulario.Acao.value = acao;
			document.formulario.submit();
		} else {
			if(validar()){
				var Campo = mount_form_temp();
				
				if(Campo != undefined){
					Campo.Acao.value = acao;
					Campo.submit();
				}
			}
		}
	}
	function mount_form_temp(){
		if(document.formulario_temp != undefined){
			document.getElementsByTagName("body")[0].removeChild(document.formulario_temp);
		}
		
		var DivLN = document.createElement("form");
		DivLN.setAttribute("name", "formulario_temp");
		DivLN.setAttribute("method", document.formulario.method);
		DivLN.setAttribute("action", document.formulario.action);
		document.getElementsByTagName("body")[0].insertBefore(DivLN, null);
		
		for(var i = 0; i < document.formulario.elements.length; i++){
			if((new Array(
				"Peri", 
				"IdNF", 
				"Nume", 
				"IAU1", 
				"IPL1", 
				"IPL2", 
				"IPL5", 
				"IEM1", 
				"IEM2", 
				"IEM3",
				"IEM4",
				"IEM5",
				"IEM7",
				"IEM8"
			)).in_array(document.formulario.elements[i].name.substr(0,4)) || document.formulario.elements[i].type == "hidden"){
				DivLN = document.createElement("input");
				DivLN.setAttribute("type", "hidden");
				DivLN.setAttribute("name", document.formulario.elements[i].name);
				DivLN.setAttribute("value", document.formulario.elements[i].value);
				document.formulario_temp.insertBefore(DivLN, null);
			}
		}
		
		return document.formulario_temp;
	}
	function validar(){	
		var Temp_0 = document.formulario.MesAtual.value.split("/");
		var Temp_1 = document.formulario.PeriodoApuracao.value.split("/");
		
		if(!validar_data(document.formulario.PeriodoApuracao.value != "" ? "01/"+document.formulario.PeriodoApuracao.value : "")){
			document.formulario.PeriodoApuracao.focus();
			return false;
		}
		
		if(parseInt(Temp_0[1]+Temp_0[0]) <= parseInt(Temp_1[1]+Temp_1[0])){
			document.formulario.PeriodoApuracao.focus();
			mensagens(167);
			return false;
		}
		
		if(document.formulario.PeriodoApuracao.value == ""){
			document.formulario.PeriodoApuracao.focus();
			mensagens(1);
			return false;
		}

		if(document.formulario.IdNF.value == ""){
			document.formulario.IdNF.focus();
			mensagens(1);
			return false;
		}
		
		if(parseInt(document.formulario.IdStatus.value) > 1){
			if(document.formulario.NumeroFistel.value == ""){
				document.formulario.NumeroFistel.focus();
				mensagens(1);
				return false;
			}
			
			var elements = document.getElementById("bl_IndicadoresSICIUFMunicipio").getElementsByTagName("input");
			
			for(var i = 0; i < elements.length; i++){
				if(elements[i].type == "text" && elements[i].value == '' && !elements[i].readOnly && !elements[i].disabled){
					elements[i].focus();
					mensagens(1);
					return false;
				}
			}
			
			if(Number(document.formulario.IdTipoApuracao.value) == 3){
				if(document.formulario.IAU1NumeroCAT.value == ""){
					document.formulario.IAU1NumeroCAT.focus();
					mensagens(1);
					return false;
				}
				
				if(document.formulario.IPL1TotalKMCaboPrestadora.value == ""){
					document.formulario.IPL1TotalKMCaboPrestadora.focus();
					mensagens(1);
					return false;
				}
				
				if(document.formulario.IPL1CrescimentoPrevistoKMCaboPrestadora.value == ""){
					document.formulario.IPL1CrescimentoPrevistoKMCaboPrestadora.focus();
					mensagens(1);
					return false;
				}
				
				if(document.formulario.IPL1TotalKMCaboTerceiro.value == ""){
					document.formulario.IPL1TotalKMCaboTerceiro.focus();
					mensagens(1);
					return false;
				}
				
				if(document.formulario.IPL1CrescimentoPrevistoKMCaboTerceiro.value == ""){
					document.formulario.IPL1CrescimentoPrevistoKMCaboTerceiro.focus();
					mensagens(1);
					return false;
				}
				
				if(document.formulario.IPL2TotalKMFibraPrestadora.value == ""){
					document.formulario.IPL2TotalKMFibraPrestadora.focus();
					mensagens(1);
					return false;
				}
				
				if(document.formulario.IPL2CrescimentoPrevistoKMFibraPrestadora.value == ""){
					document.formulario.IPL2CrescimentoPrevistoKMFibraPrestadora.focus();
					mensagens(1);
					return false;
				}
				
				if(document.formulario.IPL2TotalKMFibraTerceiro.value == ""){
					document.formulario.IPL2TotalKMFibraTerceiro.focus();
					mensagens(1);
					return false;
				}
				
				if(document.formulario.IPL2CrescimentoPrevistoKMFibraTerceiro.value == ""){
					document.formulario.IPL2CrescimentoPrevistoKMFibraTerceiro.focus();
					mensagens(1);
					return false;
				}
				
				if(document.formulario.IEM1Indicador.value == ""){
					document.formulario.IEM1Indicador.focus();
					mensagens(1);
					return false;
				}
				
				if(document.formulario.IEM1ValorTotalAplicadoMarketing.value == ""){
					document.formulario.IEM1ValorTotalAplicadoMarketing.focus();
					mensagens(1);
					return false;
				}
				
				if(document.formulario.IEM1ValorTotalAplicadoEquipamento.value == ""){
					document.formulario.IEM1ValorTotalAplicadoEquipamento.focus();
					mensagens(1);
					return false;
				}
				
				if(document.formulario.IEM1ValorTotalAplicadoSoftware.value == ""){
					document.formulario.IEM1ValorTotalAplicadoSoftware.focus();
					mensagens(1);
					return false;
				}
				
				if(document.formulario.IEM1ValorTotalAplicadoPesquisaDesenvolvimento.value == ""){
					document.formulario.IEM1ValorTotalAplicadoPesquisaDesenvolvimento.focus();
					mensagens(1);
					return false;
				}
				
				if(document.formulario.IEM1ValorTotalAplicadoServico.value == ""){
					document.formulario.IEM1ValorTotalAplicadoServico.focus();
					mensagens(1);
					return false;
				}
				
				if(document.formulario.IEM1ValorTotalAplicadoCentralAtendimento.value == ""){
					document.formulario.IEM1ValorTotalAplicadoCentralAtendimento.focus();
					mensagens(1);
					return false;
				}
				
				if(document.formulario.IEM2ValorFaturamentoServico.value == ""){
					document.formulario.IEM2ValorFaturamentoServico.focus();
					mensagens(1);
					return false;
				}
				
				if(document.formulario.IEM2ValorFaturamentoIndustrizalizacaoServico.value == ""){
					document.formulario.IEM2ValorFaturamentoIndustrizalizacaoServico.focus();
					mensagens(1);
					return false;
				}
				
				if(document.formulario.IEM2ValorFaturamentoServicoAdicional.value == ""){
					document.formulario.IEM2ValorFaturamentoServicoAdicional.focus();
					mensagens(1);
					return false;
				}
			}
			
			if((Number(document.formulario.IdTipoApuracao.value) == 2 || Number(document.formulario.IdTipoApuracao.value) == 3) && (document.formulario.IEM3ValorInvestimentoRealizado.value == "")){
				document.formulario.IEM3ValorInvestimentoRealizado.focus();
				mensagens(1);
				return false;
			}
			
			if(document.formulario.IEM6TotalBruto.value == ""){
				document.formulario.IEM6TotalBruto.focus();
				mensagens(1);
				return false;
			}
			
			if(document.formulario.IEM7TotalLiquido.value == ""){
				document.formulario.IEM7TotalLiquido.focus();
				mensagens(1);
				return false;
			}
			
			if(document.formulario.IEM8ValorDespesaOperacaoManutencao.value == ""){
				document.formulario.IEM8ValorDespesaOperacaoManutencao.focus();
				mensagens(1);
				return false;
			}
			
			if(document.formulario.IEM8ValorDespesaPublicidade.value == ""){
				document.formulario.IEM8ValorDespesaPublicidade.focus();
				mensagens(1);
				return false;
			}
			
			if(document.formulario.IEM8ValorDespesaVenda.value == ""){
				document.formulario.IEM8ValorDespesaVenda.focus();
				mensagens(1);
				return false;
			}
			
			if(document.formulario.IEM8ValorDespesaInterconexao.value == ""){
				document.formulario.IEM8ValorDespesaInterconexao.focus();
				mensagens(1);
				return false;
			}
		}
		
		return true;
	}
	function inicia() {
		verificar_campo_sici();
		document.formulario.PeriodoApuracao.focus();
	}
	function verificar_campo_sici(PeriodoApuracao){
		var IdTipoApuracao = '', Mes = 0;
		
		if(!validar_data(PeriodoApuracao != "" ? "01/"+PeriodoApuracao : "")){
			PeriodoApuracao = "";
		} else if(PeriodoApuracao != undefined){
			Mes = Number(PeriodoApuracao.replace(/\/[\d]*/i, ""));
		}
		
		if(Mes > 0){
			document.getElementById("IPL1").style.display = "none";
			document.getElementById("IPL2").style.display = "none";
			document.getElementById("IEM1").style.display = "none";
			document.getElementById("IEM2").style.display = "none";
			document.getElementById("IEM3").style.display = "none";
			document.getElementById("IEM6").style.display = "block";
			document.getElementById("IEM7").style.display = "block";
			document.getElementById("IEM8").style.display = "block";
			document.getElementById("IAU1").style.display = "none";
			document.getElementById("bl_IndicadoresSICI").style.display = "block";
			
			if(Mes == 12){ // ANUAL
				IdTipoApuracao = 3;
				document.getElementById("IPL1").style.display = "block";
				document.getElementById("IPL2").style.display = "block";
				document.getElementById("IEM1").style.display = "block";
				document.getElementById("IEM2").style.display = "block";
				document.getElementById("IEM3").style.display = "block";
				document.getElementById("IAU1").style.display = "block";
			} else if(Mes == 6){ // SEMESTRAL
				document.getElementById("IEM3").style.display = "block";
				IdTipoApuracao = 2;
			} else{ // MENSAL
				IdTipoApuracao = 1;
			}
		} else{
			document.getElementById("IPL1").style.display = "none";
			document.getElementById("IPL2").style.display = "none";
			document.getElementById("IEM1").style.display = "none";
			document.getElementById("IEM2").style.display = "none";
			document.getElementById("IEM3").style.display = "none";
			document.getElementById("IEM6").style.display = "none";
			document.getElementById("IEM7").style.display = "none";
			document.getElementById("IEM8").style.display = "none";
			document.getElementById("IAU1").style.display = "none";
			document.getElementById("bl_IndicadoresSICI").style.display = "none";
		}
		
		document.formulario.IdTipoApuracao.value = IdTipoApuracao;
		
		return IdTipoApuracao;
	}
	function validar_data(Valor){
		if(!isData(Valor) && Valor != ""){
			document.getElementById('tit_PeriodoApuracao').style.backgroundColor = '#c10000';
			document.getElementById('tit_PeriodoApuracao').style.color='#ffffff';
			
			mensagens(45);
			return false;
		}
		
		document.getElementById('tit_PeriodoApuracao').style.color = "#c10000";
		document.getElementById('tit_PeriodoApuracao').style.backgroundColor='#ffffff';
		
		return true;
	}
	function excluir(PeriodoApuracao){
		if(excluir_registro() == true){
			if(document.formulario != undefined){
				if(document.formulario.Acao.value == "inserir"){
					return;
				}
			}
			
			var url = "files/excluir/excluir_sici.php?PeriodoApuracao="+PeriodoApuracao;
			
			call_ajax(url,function (xmlhttp){
				if(document.formulario != undefined){
					document.formulario.Erro.value = xmlhttp.responseText;
					
					if(parseInt(xmlhttp.responseText) == 7){
						document.formulario.Acao.value = "inserir";
						url = "cadastro_sici.php?Erro="+document.formulario.Erro.value;
						window.location.replace(url);
					} else{
						verificaErro();
					}
				} else{
					var numMsg = parseInt(xmlhttp.responseText);
					
					mensagens(numMsg);
					
					if(numMsg == 7){
						var aux = 0, valor=0, desc=0, total=0;
						
						for(var i = 0; i < document.getElementById("tableListar").rows.length; i++){
							if(PeriodoApuracao == document.getElementById("tableListar").rows[i].accessKey){
								document.getElementById("tableListar").deleteRow(i);
								tableMultColor("tableListar", document.filtro.corRegRand.value);
								aux = 1;
								break;
							}
						}
						
						if(aux == 1){
							document.getElementById("tableListarTotal").innerHTML = "Total: "+(document.getElementById("tableListar").rows.length-2);
						}							
					}
				}
			});
		}
	}
	function atualizar_tabindex(){
		var tab_index = 1;
		
		for(var i = 0; i < document.formulario.elements.length; i++){
			if((["button", "submit"]).in_array(document.formulario.elements[i].type) || ((document.formulario.elements[i].type == "text" || document.formulario.elements[i].type == "select-one")&& (!document.formulario.elements[i].readOnly) && (!document.formulario.elements[i].disabled))){
				document.formulario.elements[i].tabIndex = (tab_index++);
			} else{
				document.formulario.elements[i].removeAttribute('tabindex');
				document.formulario.elements[i].removeAttribute('onfocus');
				document.formulario.elements[i].removeAttribute('onblur');
				document.formulario.IdNF.setAttribute('style','width:360px;');
			}
		}
	}
	function calcula_ipl6(Complemento){
		var Total = 0.00;
		
		for(var i = 1; ; i++){
			eval("var Campo = document.formulario.IPL5_"+Complemento+"_"+i+";");
			
			if(!Campo){
				break;
			}
			
			var Valor = parseFloat(Campo.value.replace(/,/g, "."));
			
			if(isNaN(Valor)){
				Valor = 0;
			}
			
			Total += Valor;
		}
		
		Total = formata_float(Arredonda(Total, 2), 2).replace(/\./g, ",");
		
		eval("document.formulario.IPL6_"+Complemento+".value = '"+Total+"';");
	}
	function calcula_iem7(){
		calcula_iem8();
		
		var IEM6TotalBruto = parseFloat(document.formulario.IEM6TotalBruto.value.replace(/,/, "."));
		
		if(isNaN(IEM6TotalBruto)){
			IEM6TotalBruto = 0.00;
		}
		
		var IEM8ValorTotalCusto = parseFloat(document.formulario.IEM8ValorTotalCusto.value.replace(/,/, "."));
		
		if(isNaN(IEM8ValorTotalCusto)){
			IEM8ValorTotalCusto = 0.00;
		}
		
		var IEM7TotalLiquido = IEM6TotalBruto - IEM8ValorTotalCusto;
		
		document.formulario.IEM7TotalLiquido.value = formata_float(Arredonda(IEM7TotalLiquido, 2), 2).replace(/\./g, ",");
	}
	function calcula_iem8(){
		var IEM8ValorTotalCusto = 0.00, IEM8 = document.getElementById("IEM8");
		
		for(var i = 0; i < (IEM8.getElementsByTagName("input").length - 1); i++){
			var Valor = parseFloat(IEM8.getElementsByTagName("input")[i].value.replace(/,/, "."));
			
			if(isNaN(Valor)){
				Valor = 0.00;
			}
			
			IEM8ValorTotalCusto += Valor;
		}
		
		document.formulario.IEM8ValorTotalCusto.value = formata_float(Arredonda(IEM8ValorTotalCusto, 2), 2).replace(/\./g, ",");
	}
	function verificar_obrigatoriedade(){
		var BLIndicadoresSICI = document.getElementById("bl_IndicadoresSICI");
		
		if(parseInt(document.formulario.IdStatus.value) > 2){
			var BLIndicadoresSICIUFMunicipio = document.getElementById("bl_IndicadoresSICIUFMunicipio");
			// RETIRANDO A ODOBRIGATORIEDADE DE MODO DINÂMICO
			for(var i = 0; i < BLIndicadoresSICIUFMunicipio.getElementsByTagName("input").length; i++){
				if(BLIndicadoresSICIUFMunicipio.getElementsByTagName("input")[i].type == "text"){
					BLIndicadoresSICIUFMunicipio.getElementsByTagName("input")[i].readOnly = true;
					BLIndicadoresSICIUFMunicipio.getElementsByTagName("input")[i].onfocus = function (){
					};
				}
			}
			
			for(i = 0; i < BLIndicadoresSICIUFMunicipio.getElementsByTagName("b").length; i++){
				BLIndicadoresSICIUFMunicipio.getElementsByTagName("b")[i].style.color = "#000";
			}
			
			document.getElementById("tit_Fistel").style.color = "#000";
			document.formulario.NumeroFistel.readOnly = true;
			document.formulario.NumeroFistel.onfocus = function (){
			};
			
			for(i = 0; i < BLIndicadoresSICI.getElementsByTagName("input").length; i++){
				if(BLIndicadoresSICI.getElementsByTagName("input")[i].type == "text" && !(new Array("IEM6", "IEM7")).in_array(BLIndicadoresSICI.getElementsByTagName("input")[i].name.substr(0,4)) && BLIndicadoresSICI.getElementsByTagName("input")[i].name != "IEM8ValorTotalCusto"){
					BLIndicadoresSICI.getElementsByTagName("input")[i].readOnly = true;
					BLIndicadoresSICI.getElementsByTagName("input")[i].onfocus = function (){
					};
				}
			}
			
			for(i = 0; i < BLIndicadoresSICI.getElementsByTagName("b").length; i++){
				BLIndicadoresSICI.getElementsByTagName("b")[i].style.color = "#000";
			}
		} else{
			document.getElementById("tit_Fistel").style.color = "#c10000";
			document.formulario.NumeroFistel.readOnly = false;
			document.formulario.NumeroFistel.onfocus = function (){
				Foco(this,'in');
			};
			// ATRIBUINDO A ODOBRIGATORIEDADE DE MODO DINÂMICO
			for(i = 0; i < BLIndicadoresSICI.getElementsByTagName("input").length; i++){
				if(BLIndicadoresSICI.getElementsByTagName("input")[i].type == "text" && !(new Array("IEM6", "IEM7")).in_array(BLIndicadoresSICI.getElementsByTagName("input")[i].name.substr(0,4)) && BLIndicadoresSICI.getElementsByTagName("input")[i].name != "IEM8ValorTotalCusto"){
					BLIndicadoresSICI.getElementsByTagName("input")[i].readOnly = false;
					BLIndicadoresSICI.getElementsByTagName("input")[i].onfocus = function (){
						Foco(this,'in');
					};
				}
			}
			
			for(i = 0; i < BLIndicadoresSICI.getElementsByTagName("b").length; i++){
				BLIndicadoresSICI.getElementsByTagName("b")[i].style.color = "#c10000";
			}
		}
		
		atualizar_tabindex();
	}
	function verificar_visualizar(){
		if(document.OpenJS.PeriodoApuracao.value == "" && document.OpenJS.filtro_limit.value == ""){
			setTimeout(function () { verificar_visualizar(); }, 66);
		} else{
			document.OpenJS.submit();
			
			document.OpenJS.PeriodoApuracao.value	= "";
			document.OpenJS.filtro_limit.value		= "";
		}
	}
	function visualizar(PeriodoApuracao, Ocultar){
		if(Ocultar == undefined){
			Ocultar = false;
		}
		
		if(document.getElementById("tb_Visualizar").style.display != "block" && !Ocultar){
			if(Number(document.formulario.QtdLancamento.value) > Number(document.formulario.VisualizacaoLimit.value)){
				window.open("listar_sici_lancamento.php?PeriodoApuracao="+PeriodoApuracao+"&filtro_limit="+document.formulario.QtdLancamento.value, "_blank");
			} else{
				var url = "xml/sici_visualizar.php?PeriodoApuracao="+PeriodoApuracao;
				
				call_ajax(url,function (xmlhttp){
					document.formulario.bt_visualizar.value = "Ocultar";
					document.getElementById("tb_Visualizar").style.display = "block";
					document.getElementById("bt_imprimir").style.display = "block";
					var table = document.getElementById("tabelaVisualizar"), TotalValor = 0, TotalValorDesconto = 0, TotalValorFinal = 0;
					
					while(table.rows.length > 2){
						table.deleteRow(1);
					}
					
					if(xmlhttp.responseText != "false"){
						for(var i = 0; i < xmlhttp.responseXML.getElementsByTagName("IdContrato").length; i++){
							var nameNode = xmlhttp.responseXML.getElementsByTagName("IdContrato")[i]; 
							var nameTextNode = nameNode.childNodes[0];
							var IdContrato = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("IdLoja")[i]; 
							nameTextNode = nameNode.childNodes[0];
							var IdLoja = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("IdContaReceber")[i]; 
							nameTextNode = nameNode.childNodes[0];
							var IdContaReceber = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("IdLancamentoFinanceiro")[i]; 
							nameTextNode = nameNode.childNodes[0];
							var IdLancamentoFinanceiro = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoServico")[i]; 
							nameTextNode = nameNode.childNodes[0];
							var DescricaoServico = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("NumeroNF")[i]; 
							nameTextNode = nameNode.childNodes[0];
							var NumeroNF = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("ModeloNF")[i]; 
							nameTextNode = nameNode.childNodes[0];
							var ModeloNF = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("NumeroModeloNF")[i]; 
							nameTextNode = nameNode.childNodes[0];
							var NumeroModeloNF = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("DataNF")[i]; 
							nameTextNode = nameNode.childNodes[0];
							var DataNF = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("Valor")[i]; 
							nameTextNode = nameNode.childNodes[0];
							var Valor = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("ValorDescontoAConceber")[i]; 
							nameTextNode = nameNode.childNodes[0];
							var ValorDescontoAConceber = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("ValorFinal")[i]; 
							nameTextNode = nameNode.childNodes[0];
							var ValorFinal = nameTextNode.nodeValue;
							
							TotalValor += Number(Valor);
							TotalValorDesconto += Number(ValorDescontoAConceber);
							TotalValorFinal += Number(ValorFinal);
							
							var linha = table.insertRow((table.rows.length)-1);
							var c0 = linha.insertCell(0);
							var c1 = linha.insertCell(1);
							var c2 = linha.insertCell(2);
							var c3 = linha.insertCell(3);
							var c4 = linha.insertCell(4);
							var c5 = linha.insertCell(5);
							var c6 = linha.insertCell(6);
							var c7 = linha.insertCell(7);
							var c8 = linha.insertCell(8);
							var c9 = linha.insertCell(9);
							
							var linkContrato = "<a href='cadastro_contrato.php?IdContrato="+IdContrato+"'>";
							var linkContaReceber = "<a href='cadastro_conta_receber.php?IdContaReceber="+IdContaReceber+"'>";
							var linkLancamentoFinanceiro = "<a href='cadastro_lancamento_financeiro.php?IdLancamentoFinanceiro="+IdLancamentoFinanceiro+"'>";
							var linkFim = "</a>";
							
							c0.innerHTML = IdLoja;
							c0.className = "tableListarEspaco";
							c1.innerHTML = linkContrato + IdContrato + linkFim;
							c2.innerHTML = linkContaReceber + IdContaReceber + linkFim;
							c3.innerHTML = linkLancamentoFinanceiro + IdLancamentoFinanceiro + linkFim;
							c4.innerHTML = DescricaoServico;
							c5.innerHTML = NumeroModeloNF;
							c6.innerHTML = dateFormat(DataNF);
							c7.className = "valor";
							c7.innerHTML = formata_float(Arredonda(Valor, 2), 2).replace(/\./, ',');
							c8.className = "valor";
							c8.innerHTML = formata_float(Arredonda(ValorDescontoAConceber, 2), 2).replace(/\./, ',');
							c9.className = "valor";
							c9.innerHTML = formata_float(Arredonda(ValorFinal, 2), 2).replace(/\./, ',');
						}
						
						document.getElementById("tabelaTotalVisualizar").innerHTML = "Total: "+i; 
						document.getElementById("tabelaTotalValor").innerHTML = formata_float(Arredonda(TotalValor, 2), 2).replace(/\./, ',');
						document.getElementById("tabelaTotalValorDesconto").innerHTML = formata_float(Arredonda(TotalValorDesconto, 2), 2).replace(/\./, ',');
						document.getElementById("tabelaTotalValorFinal").innerHTML = formata_float(Arredonda(TotalValorFinal, 2), 2).replace(/\./, ',');
					} else{
						document.getElementById("tabelaTotalVisualizar").innerHTML = "Total: 0"; 
						document.getElementById("tabelaTotalValor").innerHTML = "0,00";
						document.getElementById("tabelaTotalValorDesconto").innerHTML = "0,00";
						document.getElementById("tabelaTotalValorFinal").innerHTML = "0,00";
					}
					
					scrollWindow("bottom");
				});
			}
		} else{
			document.formulario.bt_visualizar.value = "Visualizar";
			document.getElementById("tb_Visualizar").style.display = "none";
			document.getElementById("bt_imprimir").style.display = "none";
		}
	}