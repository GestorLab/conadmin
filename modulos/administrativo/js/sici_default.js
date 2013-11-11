	function busca_sici(PeriodoApuracao, Erro, Local){
		if(PeriodoApuracao == undefined){
			PeriodoApuracao == '';
		}
		
		if(Local == '' || Local == undefined){
			Local = document.formulario.Local.value;
		}
		
		var url = "./xml/sici.php?PeriodoApuracao="+PeriodoApuracao;
		
		Erro = validar_data(PeriodoApuracao != "" ? "01/"+PeriodoApuracao : "");
		call_ajax(url,function (xmlhttp){
			if(Erro){
				document.formulario.Erro.value = 0;
				verificaErro();
			}
			
			if(xmlhttp.responseText == "false"){
				switch(Local){
					case "SICI":
						addParmUrl("marSICILancamento", "PeriodoApuracao", "");
						visualizar(undefined, true);
						
						document.formulario.IdNF.value											= "";
						document.formulario.IAU1NumeroCAT.value									= "";
						document.formulario.IPL1TotalKMCaboPrestadora.value						= "";
						document.formulario.IPL1TotalKMCaboTerceiro.value						= "";
						document.formulario.IPL1CrescimentoPrevistoKMCaboPrestadora.value		= "";
						document.formulario.IPL1CrescimentoPrevistoKMCaboTerceiro.value			= "";
						document.formulario.IPL2TotalKMFibraPrestadora.value					= "";
						document.formulario.IPL2TotalKMFibraTerceiro.value						= "";
						document.formulario.IPL2CrescimentoPrevistoKMFibraPrestadora.value		= "";
						document.formulario.IPL2CrescimentoPrevistoKMFibraTerceiro.value		= "";
						document.formulario.IEM1Indicador.value									= "";
						document.formulario.IEM1ValorTotalAplicadoEquipamento.value				= "";
						document.formulario.IEM1ValorTotalAplicadoPesquisaDesenvolvimento.value	= "";
						document.formulario.IEM1ValorTotalAplicadoMarketing.value				= "";
						document.formulario.IEM1ValorTotalAplicadoSoftware.value				= "";
						document.formulario.IEM1ValorTotalAplicadoServico.value					= "";
						document.formulario.IEM1ValorTotalAplicadoCentralAtendimento.value		= "";
						document.formulario.IEM2ValorFaturamentoServico.value					= "";
						document.formulario.IEM2ValorFaturamentoIndustrizalizacaoServico.value	= "";
						document.formulario.IEM2ValorFaturamentoServicoAdicional.value			= "";
						document.formulario.IEM3ValorInvestimentoRealizado.value				= "";
						document.formulario.IEM6TotalBruto.value								= "";
						document.formulario.IEM7TotalLiquido.value								= "";
						document.formulario.IEM8ValorTotalCusto.value							= "";
						document.formulario.IEM8ValorDespesaPublicidade.value					= "";
						document.formulario.IEM8ValorDespesaInterconexao.value					= "";
						document.formulario.IEM8ValorDespesaOperacaoManutencao.value			= "";
						document.formulario.IEM8ValorDespesaVenda.value							= "";
						document.formulario.IdStatus.value										= "";
						document.formulario.LoginCriacao.value									= "";
						document.formulario.DataCriacao.value									= "";
						document.formulario.LoginProcessamento.value							= "";
						document.formulario.DataProcessamento.value								= "";
						document.formulario.LoginConfirmacao.value								= "";
						document.formulario.DataConfirmacao.value								= "";
						document.formulario.LoginConfirmacaoEntrega.value						= "";
						document.formulario.DataConfirmacaoEntrega.value						= "";
						document.formulario.Acao.value											= "inserir";
						document.getElementById("cp_Status").innerHTML							= "";
						document.getElementById("cp_Status").style.color						= "";
						document.getElementById("tit_Fistel").style.display						= "none";
						document.formulario.NumeroFistel.style.display							= "none";
						document.getElementById("bl_IndicadoresSICI").style.display				= "none";
						document.getElementById("bl_IndicadoresSICIUFMunicipio").innerHTML		= "";
						document.getElementById("bl_IndicadoresSICIUFMunicipio").style.display	= "none";
						document.getElementById("cp_dc_Fistel").innerHTML						= "";
						
						document.getElementById("tit_NF").style.color = "#c10000";
						document.formulario.IdNF.disabled = false;
						document.formulario.IdNF.onfocus = function (){	Foco(this,'in');};
						
						verificaAcao();
				}
			} else{
				var nameNode = xmlhttp.responseXML.getElementsByTagName("PeriodoApuracao")[0]; 
				var nameTextNode = nameNode.childNodes[0];
				var PeriodoApuracao = nameTextNode.nodeValue;
				
				var nameNode = xmlhttp.responseXML.getElementsByTagName("IdNotaFiscalLayout")[0]; 
				var nameTextNode = nameNode.childNodes[0];
				var IdNotaFiscalLayout = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("IdTipoApuracao")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var IdTipoApuracao = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("IAU1NumeroCAT")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var IAU1NumeroCAT = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoIAU1NumeroCAT")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var DescricaoIAU1NumeroCAT = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("IPL1TotalKMCaboPrestadora")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var IPL1TotalKMCaboPrestadora = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoIPL1TotalKMCaboPrestadora")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var DescricaoIPL1TotalKMCaboPrestadora = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("IPL1TotalKMCaboTerceiro")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var IPL1TotalKMCaboTerceiro = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoIPL1TotalKMCaboTerceiro")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var DescricaoIPL1TotalKMCaboTerceiro = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("IPL1CrescimentoPrevistoKMCaboPrestadora")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var IPL1CrescimentoPrevistoKMCaboPrestadora = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoIPL1CrescimentoPrevistoKMCaboPrestadora")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var DescricaoIPL1CrescimentoPrevistoKMCaboPrestadora = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("IPL1CrescimentoPrevistoKMCaboTerceiro")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var IPL1CrescimentoPrevistoKMCaboTerceiro = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoIPL1CrescimentoPrevistoKMCaboTerceiro")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var DescricaoIPL1CrescimentoPrevistoKMCaboTerceiro = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("IPL2TotalKMFibraPrestadora")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var IPL2TotalKMFibraPrestadora = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoIPL2TotalKMFibraPrestadora")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var DescricaoIPL2TotalKMFibraPrestadora = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("IPL2TotalKMFibraTerceiro")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var IPL2TotalKMFibraTerceiro = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoIPL2TotalKMFibraTerceiro")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var DescricaoIPL2TotalKMFibraTerceiro = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("IPL2CrescimentoPrevistoKMFibraPrestadora")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var IPL2CrescimentoPrevistoKMFibraPrestadora = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoIPL2CrescimentoPrevistoKMFibraPrestadora")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var DescricaoIPL2CrescimentoPrevistoKMFibraPrestadora = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("IPL2CrescimentoPrevistoKMFibraTerceiro")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var IPL2CrescimentoPrevistoKMFibraTerceiro = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoIPL2CrescimentoPrevistoKMFibraTerceiro")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var DescricaoIPL2CrescimentoPrevistoKMFibraTerceiro = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("IEM1Indicador")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var IEM1Indicador = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoIEM1Indicador")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var DescricaoIEM1Indicador = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("IEM1ValorTotalAplicadoEquipamento")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var IEM1ValorTotalAplicadoEquipamento = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoIEM1ValorTotalAplicadoEquipamento")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var DescricaoIEM1ValorTotalAplicadoEquipamento = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("IEM1ValorTotalAplicadoPesquisaDesenvolvimento")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var IEM1ValorTotalAplicadoPesquisaDesenvolvimento = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoIEM1ValorTotalAplicadoPesquisaDesenvolvimento")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var DescricaoIEM1ValorTotalAplicadoPesquisaDesenvolvimento = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("IEM1ValorTotalAplicadoMarketing")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var IEM1ValorTotalAplicadoMarketing = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoIEM1ValorTotalAplicadoMarketing")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var DescricaoIEM1ValorTotalAplicadoMarketing = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("IEM1ValorTotalAplicadoSoftware")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var IEM1ValorTotalAplicadoSoftware = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoIEM1ValorTotalAplicadoSoftware")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var DescricaoIEM1ValorTotalAplicadoSoftware = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("IEM1ValorTotalAplicadoServico")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var IEM1ValorTotalAplicadoServico = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoIEM1ValorTotalAplicadoServico")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var DescricaoIEM1ValorTotalAplicadoServico = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("IEM1ValorTotalAplicadoCentralAtendimento")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var IEM1ValorTotalAplicadoCentralAtendimento = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoIEM1ValorTotalAplicadoCentralAtendimento")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var DescricaoIEM1ValorTotalAplicadoCentralAtendimento = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("IEM2ValorFaturamentoServico")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var IEM2ValorFaturamentoServico = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoIEM2ValorFaturamentoServico")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var DescricaoIEM2ValorFaturamentoServico = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("IEM2ValorFaturamentoIndustrizalizacaoServico")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var IEM2ValorFaturamentoIndustrizalizacaoServico = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoIEM2ValorFaturamentoIndustrizalizacaoServico")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var DescricaoIEM2ValorFaturamentoIndustrizalizacaoServico = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("IEM2ValorFaturamentoServicoAdicional")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var IEM2ValorFaturamentoServicoAdicional = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoIEM2ValorFaturamentoServicoAdicional")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var DescricaoIEM2ValorFaturamentoServicoAdicional = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("IEM3ValorInvestimentoRealizado")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var IEM3ValorInvestimentoRealizado = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoIEM3ValorInvestimentoRealizado")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var DescricaoIEM3ValorInvestimentoRealizado = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("IEM6TotalBruto")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var IEM6TotalBruto = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("IEM7TotalLiquido")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var IEM7TotalLiquido = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("IEM8ValorTotalCusto")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var IEM8ValorTotalCusto = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("IEM8ValorDespesaPublicidade")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var IEM8ValorDespesaPublicidade = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoIEM8ValorDespesaPublicidade")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var DescricaoIEM8ValorDespesaPublicidade = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("IEM8ValorDespesaInterconexao")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var IEM8ValorDespesaInterconexao = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoIEM8ValorDespesaInterconexao")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var DescricaoIEM8ValorDespesaInterconexao = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("IEM8ValorDespesaOperacaoManutencao")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var IEM8ValorDespesaOperacaoManutencao = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoIEM8ValorDespesaOperacaoManutencao")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var DescricaoIEM8ValorDespesaOperacaoManutencao = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("IEM8ValorDespesaVenda")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var IEM8ValorDespesaVenda = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoIEM8ValorDespesaVenda")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var DescricaoIEM8ValorDespesaVenda = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("Fistel")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var Fistel = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoFistel")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var DescricaoFistel = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("IdStatus")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var IdStatus = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("Status")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var Status = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("CorStatus")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var CorStatus = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("QtdLancamento")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var QtdLancamento = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("LoginCriacao")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var LoginCriacao = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("DataCriacao")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var DataCriacao = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("LoginProcessamento")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var LoginProcessamento = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("DataProcessamento")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var DataProcessamento = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("LoginConfirmacao")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var LoginConfirmacao = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("DataConfirmacao")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var DataConfirmacao = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("LoginConfirmacaoEntrega")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var LoginConfirmacaoEntrega = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("DataConfirmacaoEntrega")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var DataConfirmacaoEntrega = nameTextNode.nodeValue;
				
				switch(Local){
					case "SICI":
						addParmUrl("marSICILancamento", "PeriodoApuracao", dateFormat(PeriodoApuracao));
						visualizar(undefined, true);
						
						document.formulario.PeriodoApuracao.value									= dateFormat(PeriodoApuracao);
						document.formulario.IdNF.value												= IdNotaFiscalLayout;
						document.formulario.IdStatus.value											= IdStatus;
						document.formulario.QtdLancamento.value										= Number(QtdLancamento);
						document.formulario.LoginCriacao.value										= LoginCriacao;
						document.formulario.DataCriacao.value										= dateFormat(DataCriacao);
						document.formulario.LoginProcessamento.value								= LoginProcessamento;
						document.formulario.DataProcessamento.value									= dateFormat(DataProcessamento);
						document.formulario.LoginConfirmacao.value									= LoginConfirmacao;
						document.formulario.DataConfirmacao.value									= dateFormat(DataConfirmacao);
						document.formulario.LoginConfirmacaoEntrega.value							= LoginConfirmacaoEntrega;
						document.formulario.DataConfirmacaoEntrega.value							= dateFormat(DataConfirmacaoEntrega);
						document.getElementById("cp_Status").innerHTML								= Status;
						document.getElementById("cp_Status").style.color							= CorStatus;
						document.getElementById("status").style.width								= "245px";
						document.getElementById("bl_IndicadoresSICIUFMunicipio").style.innerHTML	= "";
						document.getElementById("bl_IndicadoresSICIUFMunicipio").style.display		= "none";
						document.getElementById("bl_IndicadoresSICI").style.display					= "none";
						document.getElementById("tb_fistel").style.display							= "none";
						document.getElementById("tit_Fistel").style.display							= "none";
						document.formulario.NumeroFistel.style.display								= "none";
						document.formulario.IAU1NumeroCAT.value										= "";
						document.formulario.IPL1TotalKMCaboPrestadora.value							= "";
						document.formulario.IPL1TotalKMCaboTerceiro.value							= "";
						document.formulario.IPL1CrescimentoPrevistoKMCaboPrestadora.value			= "";
						document.formulario.IPL1CrescimentoPrevistoKMCaboTerceiro.value				= "";
						document.formulario.IPL2TotalKMFibraPrestadora.value						= "";
						document.formulario.IPL2TotalKMFibraTerceiro.value							= "";
						document.formulario.IPL2CrescimentoPrevistoKMFibraPrestadora.value			= "";
						document.formulario.IPL2CrescimentoPrevistoKMFibraTerceiro.value			= "";
						document.formulario.IEM1Indicador.value										= "";
						document.formulario.IEM1ValorTotalAplicadoEquipamento.value					= "";
						document.formulario.IEM1ValorTotalAplicadoPesquisaDesenvolvimento.value		= "";
						document.formulario.IEM1ValorTotalAplicadoMarketing.value					= "";
						document.formulario.IEM1ValorTotalAplicadoSoftware.value					= "";
						document.formulario.IEM1ValorTotalAplicadoServico.value						= "";
						document.formulario.IEM1ValorTotalAplicadoCentralAtendimento.value			= "";
						document.formulario.IEM2ValorFaturamentoServico.value						= "";
						document.formulario.IEM2ValorFaturamentoIndustrizalizacaoServico.value		= "";
						document.formulario.IEM2ValorFaturamentoServicoAdicional.value				= "";
						document.formulario.IEM3ValorInvestimentoRealizado.value					= "";
						document.formulario.IEM6TotalBruto.value									= "";
						document.formulario.IEM7TotalLiquido.value									= "";
						document.formulario.IEM8ValorDespesaPublicidade.value						= "";
						document.formulario.IEM8ValorDespesaInterconexao.value						= "";
						document.formulario.IEM8ValorDespesaOperacaoManutencao.value				= "";
						document.formulario.IEM8ValorDespesaVenda.value								= "";
						document.formulario.IEM8ValorTotalCusto.value								= "";
						document.getElementById("cp_dc_Fistel").innerHTML							= "";
						
						switch(parseInt(document.formulario.IdStatus.value)){
							case 1:
								document.formulario.Acao.value = "processar";
								break;
							case 2:
								document.formulario.Acao.value = "confirmar";
								break;
							case 3:
								document.formulario.Acao.value = "entregar";
								break;
							default:
								document.formulario.Acao.value = null;
						}
						
						document.getElementById("tit_NF").style.color = "#000";
						document.formulario.IdNF.disabled = true;
						document.formulario.IdNF.onfocus = function (){};				
						if(parseInt(document.formulario.IdStatus.value) > 1){
							document.getElementById("status").style.width	= "245px"; // 548
							document.getElementById("tb_fistel").style.display	= "block";
							document.getElementById("tit_Fistel").style.display	= "block";
							document.formulario.NumeroFistel.style.display		= "block";
							
							if(parseInt(document.formulario.IdStatus.value) > 2){
								//document.getElementById("status").style.width	= "300px"; // 548
								IPL1TotalKMCaboPrestadora						= formata_float(Arredonda(IPL1TotalKMCaboPrestadora, 2), 2).replace(/\./g, ",");
								IPL1TotalKMCaboTerceiro							= formata_float(Arredonda(IPL1TotalKMCaboTerceiro, 2), 2).replace(/\./g, ",");
								IPL1CrescimentoPrevistoKMCaboPrestadora			= formata_float(Arredonda(IPL1CrescimentoPrevistoKMCaboPrestadora, 2), 2).replace(/\./g, ",");
								IPL1CrescimentoPrevistoKMCaboTerceiro			= formata_float(Arredonda(IPL1CrescimentoPrevistoKMCaboTerceiro, 2), 2).replace(/\./g, ",");
								IPL2TotalKMFibraPrestadora						= formata_float(Arredonda(IPL2TotalKMFibraPrestadora, 2), 2).replace(/\./g, ",");
								IPL2TotalKMFibraTerceiro						= formata_float(Arredonda(IPL2TotalKMFibraTerceiro, 2), 2).replace(/\./g, ",");
								IPL2CrescimentoPrevistoKMFibraPrestadora		= formata_float(Arredonda(IPL2CrescimentoPrevistoKMFibraPrestadora, 2), 2).replace(/\./g, ",");
								IPL2CrescimentoPrevistoKMFibraTerceiro			= formata_float(Arredonda(IPL2CrescimentoPrevistoKMFibraTerceiro, 2), 2).replace(/\./g, ",");
								IEM1Indicador									= formata_float(Arredonda(IEM1Indicador, 2), 2).replace(/\./g, ",");
								IEM1ValorTotalAplicadoEquipamento				= formata_float(Arredonda(IEM1ValorTotalAplicadoEquipamento, 2), 2).replace(/\./g, ",");
								IEM1ValorTotalAplicadoPesquisaDesenvolvimento	= formata_float(Arredonda(IEM1ValorTotalAplicadoPesquisaDesenvolvimento, 2), 2).replace(/\./g, ",");
								IEM1ValorTotalAplicadoMarketing					= formata_float(Arredonda(IEM1ValorTotalAplicadoMarketing, 2), 2).replace(/\./g, ",");
								IEM1ValorTotalAplicadoSoftware					= formata_float(Arredonda(IEM1ValorTotalAplicadoSoftware, 2), 2).replace(/\./g, ",");
								IEM1ValorTotalAplicadoServico					= formata_float(Arredonda(IEM1ValorTotalAplicadoServico, 2), 2).replace(/\./g, ",");
								IEM1ValorTotalAplicadoCentralAtendimento		= formata_float(Arredonda(IEM1ValorTotalAplicadoCentralAtendimento, 2), 2).replace(/\./g, ",");
								IEM2ValorFaturamentoServico						= formata_float(Arredonda(IEM2ValorFaturamentoServico, 2), 2).replace(/\./g, ",");
								IEM2ValorFaturamentoIndustrizalizacaoServico	= formata_float(Arredonda(IEM2ValorFaturamentoIndustrizalizacaoServico, 2), 2).replace(/\./g, ",");
								IEM2ValorFaturamentoServicoAdicional			= formata_float(Arredonda(IEM2ValorFaturamentoServicoAdicional, 2), 2).replace(/\./g, ",");
								IEM3ValorInvestimentoRealizado					= formata_float(Arredonda(IEM3ValorInvestimentoRealizado, 2), 2).replace(/\./g, ",");
								IEM8ValorDespesaPublicidade						= formata_float(Arredonda(IEM8ValorDespesaPublicidade, 2), 2).replace(/\./g, ",");
								IEM8ValorDespesaInterconexao					= formata_float(Arredonda(IEM8ValorDespesaInterconexao, 2), 2).replace(/\./g, ",");
								IEM8ValorDespesaOperacaoManutencao				= formata_float(Arredonda(IEM8ValorDespesaOperacaoManutencao, 2), 2).replace(/\./g, ",");
								IEM8ValorDespesaVenda							= formata_float(Arredonda(IEM8ValorDespesaVenda, 2), 2).replace(/\./g, ",");
							}
							
							IEM6TotalBruto		= formata_float(Arredonda(IEM6TotalBruto, 2), 2).replace(/\./g, ",");
							IEM7TotalLiquido	= formata_float(Arredonda(IEM7TotalLiquido, 2), 2).replace(/\./g, ",");
							IEM8ValorTotalCusto	= formata_float(Arredonda(IEM8ValorTotalCusto, 2), 2).replace(/\./g, ",");
							
							document.formulario.NumeroFistel.value									= Fistel;
							document.formulario.IAU1NumeroCAT.value									= IAU1NumeroCAT;
							document.formulario.IPL1TotalKMCaboPrestadora.value						= IPL1TotalKMCaboPrestadora;
							document.formulario.IPL1TotalKMCaboTerceiro.value						= IPL1TotalKMCaboTerceiro;
							document.formulario.IPL1CrescimentoPrevistoKMCaboPrestadora.value		= IPL1CrescimentoPrevistoKMCaboPrestadora;
							document.formulario.IPL1CrescimentoPrevistoKMCaboTerceiro.value			= IPL1CrescimentoPrevistoKMCaboTerceiro;
							document.formulario.IPL2TotalKMFibraPrestadora.value					= IPL2TotalKMFibraPrestadora;
							document.formulario.IPL2TotalKMFibraTerceiro.value						= IPL2TotalKMFibraTerceiro;
							document.formulario.IPL2CrescimentoPrevistoKMFibraPrestadora.value		= IPL2CrescimentoPrevistoKMFibraPrestadora;
							document.formulario.IPL2CrescimentoPrevistoKMFibraTerceiro.value		= IPL2CrescimentoPrevistoKMFibraTerceiro;
							document.formulario.IEM1Indicador.value									= IEM1Indicador;
							document.formulario.IEM1ValorTotalAplicadoEquipamento.value				= IEM1ValorTotalAplicadoEquipamento;
							document.formulario.IEM1ValorTotalAplicadoPesquisaDesenvolvimento.value	= IEM1ValorTotalAplicadoPesquisaDesenvolvimento;
							document.formulario.IEM1ValorTotalAplicadoMarketing.value				= IEM1ValorTotalAplicadoMarketing;
							document.formulario.IEM1ValorTotalAplicadoSoftware.value				= IEM1ValorTotalAplicadoSoftware;
							document.formulario.IEM1ValorTotalAplicadoServico.value					= IEM1ValorTotalAplicadoServico;
							document.formulario.IEM1ValorTotalAplicadoCentralAtendimento.value		= IEM1ValorTotalAplicadoCentralAtendimento;
							document.formulario.IEM2ValorFaturamentoServico.value					= IEM2ValorFaturamentoServico;
							document.formulario.IEM2ValorFaturamentoIndustrizalizacaoServico.value	= IEM2ValorFaturamentoIndustrizalizacaoServico;
							document.formulario.IEM2ValorFaturamentoServicoAdicional.value			= IEM2ValorFaturamentoServicoAdicional;
							document.formulario.IEM3ValorInvestimentoRealizado.value				= IEM3ValorInvestimentoRealizado;
							document.formulario.IEM6TotalBruto.value								= IEM6TotalBruto;
							document.formulario.IEM7TotalLiquido.value								= IEM7TotalLiquido;
							document.formulario.IEM8ValorTotalCusto.value							= IEM8ValorTotalCusto;
							document.formulario.IEM8ValorDespesaPublicidade.value					= IEM8ValorDespesaPublicidade;
							document.formulario.IEM8ValorDespesaInterconexao.value					= IEM8ValorDespesaInterconexao;
							document.formulario.IEM8ValorDespesaOperacaoManutencao.value			= IEM8ValorDespesaOperacaoManutencao;
							document.formulario.IEM8ValorDespesaVenda.value							= IEM8ValorDespesaVenda;
							
							document.getElementById("cp_dc_Fistel").innerHTML											= DescricaoFistel;
							document.getElementById("cp_dc_IAU1NumeroCAT").innerHTML									= DescricaoIAU1NumeroCAT;
							document.getElementById("cp_dc_IPL1TotalKMCaboPrestadora").innerHTML						= DescricaoIPL1TotalKMCaboPrestadora;
							document.getElementById("cp_dc_IPL1TotalKMCaboTerceiro").innerHTML							= DescricaoIPL1TotalKMCaboTerceiro;
							document.getElementById("cp_dc_IPL1CrescimentoPrevistoKMCaboPrestadora").innerHTML			= DescricaoIPL1CrescimentoPrevistoKMCaboPrestadora;
							document.getElementById("cp_dc_IPL1CrescimentoPrevistoKMCaboTerceiro").innerHTML			= DescricaoIPL1CrescimentoPrevistoKMCaboTerceiro;
							document.getElementById("cp_dc_IPL2TotalKMFibraPrestadora").innerHTML						= DescricaoIPL2TotalKMFibraPrestadora;
							document.getElementById("cp_dc_IPL2TotalKMFibraTerceiro").innerHTML							= DescricaoIPL2TotalKMFibraTerceiro;
							document.getElementById("cp_dc_IPL2CrescimentoPrevistoKMFibraPrestadora").innerHTML			= DescricaoIPL2CrescimentoPrevistoKMFibraPrestadora;
							document.getElementById("cp_dc_IPL2CrescimentoPrevistoKMFibraTerceiro").innerHTML			= DescricaoIPL2CrescimentoPrevistoKMFibraTerceiro;
							document.getElementById("cp_dc_IEM1Indicador").innerHTML									= DescricaoIEM1Indicador;
							document.getElementById("cp_dc_IEM1ValorTotalAplicadoEquipamento").innerHTML				= DescricaoIEM1ValorTotalAplicadoEquipamento;
							document.getElementById("cp_dc_IEM1ValorTotalAplicadoPesquisaDesenvolvimento").innerHTML	= DescricaoIEM1ValorTotalAplicadoPesquisaDesenvolvimento;
							document.getElementById("cp_dc_IEM1ValorTotalAplicadoMarketing").innerHTML					= DescricaoIEM1ValorTotalAplicadoMarketing;
							document.getElementById("cp_dc_IEM1ValorTotalAplicadoSoftware").innerHTML					= DescricaoIEM1ValorTotalAplicadoSoftware;
							document.getElementById("cp_dc_IEM1ValorTotalAplicadoServico").innerHTML					= DescricaoIEM1ValorTotalAplicadoServico;
							document.getElementById("cp_dc_IEM1ValorTotalAplicadoCentralAtendimento").innerHTML			= DescricaoIEM1ValorTotalAplicadoCentralAtendimento;
							document.getElementById("cp_dc_IEM2ValorFaturamentoServico").innerHTML						= DescricaoIEM2ValorFaturamentoServico;
							document.getElementById("cp_dc_IEM2ValorFaturamentoIndustrizalizacaoServico").innerHTML		= DescricaoIEM2ValorFaturamentoIndustrizalizacaoServico;
							document.getElementById("cp_dc_IEM2ValorFaturamentoServicoAdicional").innerHTML				= DescricaoIEM2ValorFaturamentoServicoAdicional;
							document.getElementById("cp_dc_IEM3ValorInvestimentoRealizado").innerHTML					= DescricaoIEM3ValorInvestimentoRealizado;
							document.getElementById("cp_dc_IEM8ValorDespesaPublicidade").innerHTML						= DescricaoIEM8ValorDespesaPublicidade;
							document.getElementById("cp_dc_IEM8ValorDespesaInterconexao").innerHTML						= DescricaoIEM8ValorDespesaInterconexao;
							document.getElementById("cp_dc_IEM8ValorDespesaOperacaoManutencao").innerHTML				= DescricaoIEM8ValorDespesaOperacaoManutencao;
							document.getElementById("cp_dc_IEM8ValorDespesaVenda").innerHTML							= DescricaoIEM8ValorDespesaVenda;
							
							verificar_campo_sici(document.formulario.PeriodoApuracao.value);
							
							var Mes = Number(PeriodoApuracao.replace(/\/[\d]*|[\d]*-/i, ""));
							var xmlhttpResponseXMLIndicadorEstado = xmlhttp.responseXML.getElementsByTagName("IndicadorEstado");
							var BLIndicadoresSICIUFMunicipio = document.getElementById("bl_IndicadoresSICIUFMunicipio");
							var DivLN = document.createElement("div");
							DivLN.setAttribute("id", "cp_tit");
							DivLN.marginTop = "200px";
							DivLN.innerHTML = "Indicadores por UF e Município";
							BLIndicadoresSICIUFMunicipio.innerHTML = "";
							BLIndicadoresSICIUFMunicipio.insertBefore(DivLN, null);
							
							for(var i = 0; i < xmlhttpResponseXMLIndicadorEstado.length; i++){

								nameNode = xmlhttpResponseXMLIndicadorEstado[i].getElementsByTagName("IdPais")[0]; 
								nameTextNode = nameNode.childNodes[0];
								var IdPais = nameTextNode.nodeValue;
								
								nameNode = xmlhttpResponseXMLIndicadorEstado[i].getElementsByTagName("IdEstado")[0]; 
								nameTextNode = nameNode.childNodes[0];
								var IdEstado = nameTextNode.nodeValue;
								
								nameNode = xmlhttpResponseXMLIndicadorEstado[i].getElementsByTagName("UF")[0]; 
								nameTextNode = nameNode.childNodes[0];
								var UF = nameTextNode.nodeValue;
								
								nameNode = xmlhttpResponseXMLIndicadorEstado[i].getElementsByTagName("IEM4")[0]; 
								nameTextNode = nameNode.childNodes[0];
								var IEM4 = nameTextNode.nodeValue;
								
								nameNode = xmlhttpResponseXMLIndicadorEstado[i].getElementsByTagName("DescricaoIEM4")[0]; 
								nameTextNode = nameNode.childNodes[0];
								var DescricaoIEM4 = nameTextNode.nodeValue;
								
								nameNode = xmlhttpResponseXMLIndicadorEstado[i].getElementsByTagName("IEM5")[0]; 
								nameTextNode = nameNode.childNodes[0];
								var IEM5 = nameTextNode.nodeValue;
								
								nameNode = xmlhttpResponseXMLIndicadorEstado[i].getElementsByTagName("DescricaoIEM5")[0]; 
								nameTextNode = nameNode.childNodes[0];
								var DescricaoIEM5 = nameTextNode.nodeValue;
								
								document.getElementById("bl_IndicadoresSICIUFMunicipio").style.display = "inline";
								
								var IdNome;
								DivLN = document.createElement("div");
								DivLN.setAttribute("id", "cp_sub_sub_tit");
								DivLN.setAttribute("style", "font-size:14px;");
								DivLN.innerHTML = "UF: "+UF;
								BLIndicadoresSICIUFMunicipio.insertBefore(DivLN, null);
								
								// IEM 4
								if(IdTipoApuracao == 2 || IdTipoApuracao == 3){
									DivLN = document.createElement("div");
									DivLN.setAttribute("id", "cp_sub_tit");
									DivLN.innerHTML = "<u>IEM 4</u> - Evolução do Número de Postos de Trabalho Diretos.<div style='float:right; text-align:right;'>"+UF+"</div>";
									BLIndicadoresSICIUFMunicipio.insertBefore(DivLN, null);
									
									IdNome = "IEM4_0_"+IdPais+"_"+IdEstado;
									DivLN = document.createElement("table");
									DivLN.setAttribute("id", IdNome);
									BLIndicadoresSICIUFMunicipio.insertBefore(DivLN, null);
									table = document.getElementById(IdNome);
									linha = table.insertRow((table.rows.length)-1);
									c0 = linha.insertCell(0);
									c1 = linha.insertCell(1);
									linha = table.insertRow((table.rows.length)-1);
									c0 = linha.insertCell(0);
									c1 = linha.insertCell(1);
									
									c0.className = "find";
									c0.innerHTML = "&nbsp;";
									c1.className = "descCampo";
									c1.innerHTML = "<b>Quantidade de empregados contratados diretamente:</b>";
									
									linha = table.insertRow((table.rows.length)-1);
									c0 = linha.insertCell(0);
									c1 = linha.insertCell(1);
									
									c0.className = "find";
									c0.innerHTML = "&nbsp;";
									c1.className = "campo";
									c1.innerHTML = "<input style='width:399px' type='text' name='IEM4_"+IdPais+"_"+IdEstado+"' value='"+IEM4+"' autocomplete='off' maxlength='11' onkeypress=\"mascara(this,event,'int')\" onFocus=\"Foco(this,'in')\" onBlur=\"Foco(this,'out')\" /><div id='cp_dc_IEM4'>"+DescricaoIEM4+"</div>";
									// IEM 5
									DivLN = document.createElement("div");
									DivLN.setAttribute("id", "cp_sub_tit");
									DivLN.innerHTML = "<u>IEM 5</u> - Evolução do Número de Postos de Trabalho Indiretos.<div style='float:right; text-align:right;'>"+UF+"</div>";
									BLIndicadoresSICIUFMunicipio.insertBefore(DivLN, null);
									
									IdNome = "IEM5_0_"+IdPais+"_"+IdEstado;
									DivLN = document.createElement("table");
									DivLN.setAttribute("id", IdNome);
									BLIndicadoresSICIUFMunicipio.insertBefore(DivLN, null);
									table = document.getElementById(IdNome);
									linha = table.insertRow((table.rows.length)-1);
									c0 = linha.insertCell(0);
									c1 = linha.insertCell(1);
									linha = table.insertRow((table.rows.length)-1);
									c0 = linha.insertCell(0);
									c1 = linha.insertCell(1);
									
									c0.className = "find";
									c0.innerHTML = "&nbsp;";
									c1.className = "descCampo";
									c1.innerHTML = "<b>Quantidade de empregados de empresas terceirizadas:</b>";
									
									linha = table.insertRow((table.rows.length)-1);
									c0 = linha.insertCell(0);
									c1 = linha.insertCell(1);
									
									c0.className = "find";
									c0.innerHTML = "&nbsp;";
									c1.className = "campo";
									c1.innerHTML = "<input style='width:399px' type='text' name='IEM5_"+IdPais+"_"+IdEstado+"' value='"+IEM5+"' autocomplete='off' maxlength='11' onkeypress=\"mascara(this,event,'int')\" onFocus=\"Foco(this,'in')\" onBlur=\"Foco(this,'out')\" /><div id='cp_dc_IEM5'>"+DescricaoIEM5+"</div>";
								}
								// IEM 9 - FÍSICA
								IdNome = "bl_IEM9_0_"+IdPais+"_"+IdEstado+"_2";
								DivLN = document.createElement("div");
								DivLN.setAttribute("id", IdNome);
								BLIndicadoresSICIUFMunicipio.insertBefore(DivLN, null);
								var BLIEM9_2 = document.getElementById(IdNome);
								
								DivLN = document.createElement("div");
								DivLN.setAttribute("id", "cp_sub_tit");
								DivLN.innerHTML = "<u>IEM 9</u> - Preço Médio (Pessoa Física).<div style='float:right; text-align:right;'>"+UF+"</div>";
								BLIEM9_2.insertBefore(DivLN, null);
								// IEM 9 - JURÍDICA
								IdNome = "bl_IEM9_0_"+IdPais+"_"+IdEstado+"_1";
								DivLN = document.createElement("div");
								DivLN.setAttribute("id", IdNome);
								BLIndicadoresSICIUFMunicipio.insertBefore(DivLN, null);
								var BLIEM9_1 = document.getElementById(IdNome);
								
								DivLN = document.createElement("div");
								DivLN.setAttribute("id", "cp_sub_tit");
								DivLN.innerHTML = "<u>IEM 9</u> - Preço Médio (Pessoa Jurídica).<div style='float:right; text-align:right;'>"+UF+"</div>";
								BLIEM9_1.insertBefore(DivLN, null);
								
								var TituloCampoIEM9 = [
									"Velocidade <= 512Kbps ("+document.formulario.Moeda.value+"):",
									"Velocidade entre 512Kbps e 2Mbps ("+document.formulario.Moeda.value+"):",
									"Velocidade entre 2Mbps e 12Mbps ("+document.formulario.Moeda.value+"):",
									"Velocidade entre 12Mbps e 34Mbps ("+document.formulario.Moeda.value+"):",
									"Velocidade > 34Mbps ("+document.formulario.Moeda.value+"):"
								];
								
								var xmlhttpResponseXMLGrupoVelocidade = xmlhttpResponseXMLIndicadorEstado[i].getElementsByTagName("GrupoVelocidade");
								
								for(var ii = 0; ii < xmlhttpResponseXMLGrupoVelocidade.length; ii++){
									nameNode = xmlhttpResponseXMLGrupoVelocidade[ii].getElementsByTagName("IdVelocidade")[0]; 
									nameTextNode = nameNode.childNodes[0];
									var IdVelocidade_0 = nameTextNode.nodeValue;
									
									nameNode = xmlhttpResponseXMLGrupoVelocidade[ii].getElementsByTagName("DescricaoVelocidade")[0]; 
									nameTextNode = nameNode.childNodes[0];
									var DescricaoVelocidade_0 = nameTextNode.nodeValue;
									
									nameNode = xmlhttpResponseXMLGrupoVelocidade[ii].getElementsByTagName("IEM9PessoaFisica")[0]; 
									nameTextNode = nameNode.childNodes[0];
									var IEM9PessoaFisica_0 = nameTextNode.nodeValue;
									
									nameNode = xmlhttpResponseXMLGrupoVelocidade[ii].getElementsByTagName("IEM9PessoaJuridica")[0]; 
									nameTextNode = nameNode.childNodes[0];
									var IEM9PessoaJuridica_0 = nameTextNode.nodeValue;
									
									IEM9PessoaFisica_0 = formata_float(Arredonda(IEM9PessoaFisica_0, 2), 2).replace(/\./g, ",");
									IEM9PessoaJuridica_0 = formata_float(Arredonda(IEM9PessoaJuridica_0, 2), 2).replace(/\./g, ",");
									// IEM 9 - FÍSICA
									IdNome = "IEM9_"+ii+"_"+IdPais+"_"+IdEstado+"_2_"+IdVelocidade_0;
									DivLN = document.createElement("table");
									DivLN.setAttribute("id", IdNome);
									BLIEM9_2.insertBefore(DivLN, null);
									table = document.getElementById(IdNome);
									
									linha = table.insertRow((table.rows.length)-1);
									c0 = linha.insertCell(0);
									c1 = linha.insertCell(1);
									linha = table.insertRow((table.rows.length)-1);
									c0 = linha.insertCell(0);
									c1 = linha.insertCell(1);
									
									c0.className = "find";
									c0.innerHTML = "&nbsp;";
									c1.className = "descCampo";
									c1.innerHTML = TituloCampoIEM9[IdVelocidade_0-1];
									
									ii++;
									
									if(xmlhttpResponseXMLGrupoVelocidade[ii]){
										nameNode = xmlhttpResponseXMLGrupoVelocidade[ii].getElementsByTagName("IdVelocidade")[0]; 
										nameTextNode = nameNode.childNodes[0];
										var IdVelocidade_1 = nameTextNode.nodeValue;
										
										nameNode = xmlhttpResponseXMLGrupoVelocidade[ii].getElementsByTagName("DescricaoVelocidade")[0]; 
										nameTextNode = nameNode.childNodes[0];
										var DescricaoVelocidade_1 = nameTextNode.nodeValue;
										
										nameNode = xmlhttpResponseXMLGrupoVelocidade[ii].getElementsByTagName("IEM9PessoaFisica")[0]; 
										nameTextNode = nameNode.childNodes[0];
										var IEM9PessoaFisica_1 = nameTextNode.nodeValue;
										
										nameNode = xmlhttpResponseXMLGrupoVelocidade[ii].getElementsByTagName("IEM9PessoaJuridica")[0]; 
										nameTextNode = nameNode.childNodes[0];
										var IEM9PessoaJuridica_1 = nameTextNode.nodeValue;
										
										IEM9PessoaFisica_1 = formata_float(Arredonda(IEM9PessoaFisica_1, 2), 2).replace(/\./g, ",");
										IEM9PessoaJuridica_1 = formata_float(Arredonda(IEM9PessoaJuridica_1, 2), 2).replace(/\./g, ",");
										
										var c2 = linha.insertCell(2);
										var c3 = linha.insertCell(3);
										
										c2.className = "separador";
										c2.innerHTML = "&nbsp;";
										c3.className = "descCampo";
										c3.innerHTML = TituloCampoIEM9[IdVelocidade_1-1];
									}
									
									linha = table.insertRow((table.rows.length)-1);
									c0 = linha.insertCell(0);
									c1 = linha.insertCell(1);
									
									c0.className = "find";
									c0.innerHTML = "&nbsp;";
									c1.className = "campo";
									c1.innerHTML = "<input style='width:399px' type='text' name='IEM9PessoaFisica_"+IdPais+"_"+IdEstado+"_"+IdVelocidade_0+"' value='"+IEM9PessoaFisica_0+"' maxlength='11' readonly='readonly' />";
									
									if(xmlhttpResponseXMLGrupoVelocidade[ii]){
										c2 = linha.insertCell(2);
										c3 = linha.insertCell(3);
										
										c2.className = "separador";
										c2.innerHTML = "&nbsp;";
										c3.className = "campo";
										c3.innerHTML = "<input style='width:399px' type='text' name='IEM9PessoaFisica_"+IdPais+"_"+IdEstado+"_"+IdVelocidade_1+"' value='"+IEM9PessoaFisica_1+"' maxlength='11' readonly='readonly' />";
									}
									// IEM 9 - JURÍDICA
									IdNome = "IEM9_"+(ii-1)+"_"+IdPais+"_"+IdEstado+"_1_"+IdVelocidade_0;
									DivLN = document.createElement("table");
									DivLN.setAttribute("id", IdNome);
									BLIEM9_1.insertBefore(DivLN, null);
									table = document.getElementById(IdNome);
									
									linha = table.insertRow((table.rows.length)-1);
									c0 = linha.insertCell(0);
									c1 = linha.insertCell(1);
									linha = table.insertRow((table.rows.length)-1);
									c0 = linha.insertCell(0);
									c1 = linha.insertCell(1);
									
									c0.className = "find";
									c0.innerHTML = "&nbsp;";
									c1.className = "descCampo";
									c1.innerHTML = TituloCampoIEM9[IdVelocidade_0-1];
									
									if(xmlhttpResponseXMLGrupoVelocidade[ii]){
										c2 = linha.insertCell(2);
										c3 = linha.insertCell(3);
										
										c2.className = "separador";
										c2.innerHTML = "&nbsp;";
										c3.className = "descCampo";
										c3.innerHTML = TituloCampoIEM9[IdVelocidade_1-1];
									}
									
									linha = table.insertRow((table.rows.length)-1);
									c0 = linha.insertCell(0);
									c1 = linha.insertCell(1);
									
									c0.className = "find";
									c0.innerHTML = "&nbsp;";
									c1.className = "campo";
									c1.innerHTML = "<input style='width:399px' type='text' name='IEM9PessoaJuridica_"+IdPais+"_"+IdEstado+"_"+IdVelocidade_0+"' value='"+IEM9PessoaJuridica_0+"' maxlength='11' readonly='readonly' />";
									
									if(xmlhttpResponseXMLGrupoVelocidade[ii]){
										c2 = linha.insertCell(2);
										c3 = linha.insertCell(3);
										
										c2.className = "separador";
										c2.innerHTML = "&nbsp;";
										c3.className = "campo";
										c3.innerHTML = "<input style='width:399px' type='text' name='IEM9PessoaJuridica_"+IdPais+"_"+IdEstado+"_"+IdVelocidade_1+"' value='"+IEM9PessoaJuridica_1+"' maxlength='11' readonly='readonly' />";
									}
								}
								
								nameNode = xmlhttpResponseXMLIndicadorEstado[i].getElementsByTagName("IEM10MenorPreco1MbpsNaoDedicadoPessoaFisica")[0]; 
								nameTextNode = nameNode.childNodes[0];
								var IEM10MenorPreco1MbpsNaoDedicadoPessoaFisica = nameTextNode.nodeValue;
								
								nameNode = xmlhttpResponseXMLIndicadorEstado[i].getElementsByTagName("IEM10MaiorPreco1MbpsNaoDedicadoPessoaFisica")[0]; 
								nameTextNode = nameNode.childNodes[0];
								var IEM10MaiorPreco1MbpsNaoDedicadoPessoaFisica = nameTextNode.nodeValue;
								
								nameNode = xmlhttpResponseXMLIndicadorEstado[i].getElementsByTagName("IEM10MenorPreco1MbpsDedicadoPessoaFisica")[0]; 
								nameTextNode = nameNode.childNodes[0];
								var IEM10MenorPreco1MbpsDedicadoPessoaFisica = nameTextNode.nodeValue;
								
								nameNode = xmlhttpResponseXMLIndicadorEstado[i].getElementsByTagName("IEM10MaiorPreco1MbpsDedicadoPessoaFisica")[0]; 
								nameTextNode = nameNode.childNodes[0];
								var IEM10MaiorPreco1MbpsDedicadoPessoaFisica = nameTextNode.nodeValue;
								
								IEM10MenorPreco1MbpsNaoDedicadoPessoaFisica = formata_float(Arredonda(IEM10MenorPreco1MbpsNaoDedicadoPessoaFisica, 2), 2).replace(/\./g, ",");
								IEM10MaiorPreco1MbpsNaoDedicadoPessoaFisica = formata_float(Arredonda(IEM10MaiorPreco1MbpsNaoDedicadoPessoaFisica, 2), 2).replace(/\./g, ",");
								IEM10MenorPreco1MbpsDedicadoPessoaFisica = formata_float(Arredonda(IEM10MenorPreco1MbpsDedicadoPessoaFisica, 2), 2).replace(/\./g, ",");
								IEM10MaiorPreco1MbpsDedicadoPessoaFisica = formata_float(Arredonda(IEM10MaiorPreco1MbpsDedicadoPessoaFisica, 2), 2).replace(/\./g, ",");
								// IEM 10 - FÍSICA
								DivLN = document.createElement("div");
								DivLN.setAttribute("id", "cp_sub_tit");
								DivLN.innerHTML = "<u>IEM 10</u> - Menor e maior preço por 1 Mbps (Pessoa Física).<div style='float:right; text-align:right;'>"+UF+"</div>";
								BLIndicadoresSICIUFMunicipio.insertBefore(DivLN, null);
								
								IdNome = "IEM10_0_"+IdPais+"_"+IdEstado+"_2";
								DivLN = document.createElement("table");
								DivLN.setAttribute("id", IdNome);
								BLIndicadoresSICIUFMunicipio.insertBefore(DivLN, null);
								table = document.getElementById(IdNome);
								
								linha = table.insertRow((table.rows.length)-1);
								c0 = linha.insertCell(0);
								c1 = linha.insertCell(1);
								linha = table.insertRow((table.rows.length)-1);
								c0 = linha.insertCell(0);
								c1 = linha.insertCell(1);
								c2 = linha.insertCell(2);
								c3 = linha.insertCell(3);
								
								c0.className = "find";
								c0.innerHTML = "&nbsp;";
								c1.className = "descCampo";
								c1.innerHTML = "Menor preço por 1Mbps (não dedicado) ("+document.formulario.Moeda.value+"):";
								c2.className = "separador";
								c2.innerHTML = "&nbsp;";
								c3.className = "descCampo";
								c3.innerHTML = "Menor preço por 1Mbps (dedicado) ("+document.formulario.Moeda.value+"):";
								
								linha = table.insertRow((table.rows.length)-1);
								c0 = linha.insertCell(0);
								c1 = linha.insertCell(1);
								c2 = linha.insertCell(2);
								c3 = linha.insertCell(3);
								
								c0.className = "find";
								c0.innerHTML = "&nbsp;";
								c1.className = "campo";
								c1.innerHTML = "<input style='width:399px' type='text' name='IEM10MenorPreco1MbpsNaoDedicadoPessoaFisica_"+IdPais+"_"+IdEstado+"' value='"+IEM10MenorPreco1MbpsNaoDedicadoPessoaFisica+"' maxlength='11' readonly='readonly' />";
								c2.className = "separador";
								c2.innerHTML = "&nbsp;";
								c3.className = "campo";
								c3.innerHTML = "<input style='width:399px' type='text' name='IEM10MenorPreco1MbpsDedicadoPessoaFisica_"+IdPais+"_"+IdEstado+"' value='"+IEM10MenorPreco1MbpsDedicadoPessoaFisica+"' maxlength='11' readonly='readonly' />";
								
								IdNome = "IEM10_1_"+IdPais+"_"+IdEstado+"_2";
								DivLN = document.createElement("table");
								DivLN.setAttribute("id", IdNome);
								BLIndicadoresSICIUFMunicipio.insertBefore(DivLN, null);
								table = document.getElementById(IdNome);
								
								linha = table.insertRow((table.rows.length)-1);
								c0 = linha.insertCell(0);
								c1 = linha.insertCell(1);
								linha = table.insertRow((table.rows.length)-1);
								c0 = linha.insertCell(0);
								c1 = linha.insertCell(1);
								c2 = linha.insertCell(2);
								c3 = linha.insertCell(3);
								
								c0.className = "find";
								c0.innerHTML = "&nbsp;";
								c1.className = "descCampo";
								c1.innerHTML = "Maior preço por 1Mbps (não dedicado) ("+document.formulario.Moeda.value+"):";
								c2.className = "separador";
								c2.innerHTML = "&nbsp;";
								c3.className = "descCampo";
								c3.innerHTML = "Maior preço por 1Mbps (dedicado) ("+document.formulario.Moeda.value+"):";
								
								linha = table.insertRow((table.rows.length)-1);
								c0 = linha.insertCell(0);
								c1 = linha.insertCell(1);
								c2 = linha.insertCell(2);
								c3 = linha.insertCell(3);
								
								c0.className = "find";
								c0.innerHTML = "&nbsp;";
								c1.className = "campo";
								c1.innerHTML = "<input style='width:399px' type='text' name='IEM10MaiorPreco1MbpsNaoDedicadoPessoaFisica_"+IdPais+"_"+IdEstado+"' value='"+IEM10MaiorPreco1MbpsNaoDedicadoPessoaFisica+"' maxlength='11' readonly='readonly' />";
								c2.className = "separador";
								c2.innerHTML = "&nbsp;";
								c3.className = "campo";
								c3.innerHTML = "<input style='width:399px' type='text' name='IEM10MaiorPreco1MbpsDedicadoPessoaFisica_"+IdPais+"_"+IdEstado+"' value='"+IEM10MaiorPreco1MbpsDedicadoPessoaFisica+"' maxlength='11' readonly='readonly' />";
								
								nameNode = xmlhttpResponseXMLIndicadorEstado[i].getElementsByTagName("IEM10MenorPreco1MbpsNaoDedicadoPessoaJuridica")[0]; 
								nameTextNode = nameNode.childNodes[0];
								var IEM10MenorPreco1MbpsNaoDedicadoPessoaJuridica = nameTextNode.nodeValue;
								
								nameNode = xmlhttpResponseXMLIndicadorEstado[i].getElementsByTagName("IEM10MaiorPreco1MbpsNaoDedicadoPessoaJuridica")[0]; 
								nameTextNode = nameNode.childNodes[0];
								var IEM10MaiorPreco1MbpsNaoDedicadoPessoaJuridica = nameTextNode.nodeValue;
								
								nameNode = xmlhttpResponseXMLIndicadorEstado[i].getElementsByTagName("IEM10MenorPreco1MbpsDedicadoPessoaJuridica")[0]; 
								nameTextNode = nameNode.childNodes[0];
								var IEM10MenorPreco1MbpsDedicadoPessoaJuridica = nameTextNode.nodeValue;
								
								nameNode = xmlhttpResponseXMLIndicadorEstado[i].getElementsByTagName("IEM10MaiorPreco1MbpsDedicadoPessoaJuridica")[0]; 
								nameTextNode = nameNode.childNodes[0];
								var IEM10MaiorPreco1MbpsDedicadoPessoaJuridica = nameTextNode.nodeValue;
								
								IEM10MenorPreco1MbpsNaoDedicadoPessoaJuridica = formata_float(Arredonda(IEM10MenorPreco1MbpsNaoDedicadoPessoaJuridica, 2), 2).replace(/\./g, ",");
								IEM10MaiorPreco1MbpsNaoDedicadoPessoaJuridica = formata_float(Arredonda(IEM10MaiorPreco1MbpsNaoDedicadoPessoaJuridica, 2), 2).replace(/\./g, ",");
								IEM10MenorPreco1MbpsDedicadoPessoaJuridica = formata_float(Arredonda(IEM10MenorPreco1MbpsDedicadoPessoaJuridica, 2), 2).replace(/\./g, ",");
								IEM10MaiorPreco1MbpsDedicadoPessoaJuridica = formata_float(Arredonda(IEM10MaiorPreco1MbpsDedicadoPessoaJuridica, 2), 2).replace(/\./g, ",");
								// IEM 10 - JURÍDICA
								DivLN = document.createElement("div");
								DivLN.setAttribute("id", "cp_sub_tit");
								DivLN.innerHTML = "<u>IEM 10</u> - Menor e maior preço por 1 Mbps (Pessoa Jurídica).<div style='float:right; text-align:right;'>"+UF+"</div>";
								BLIndicadoresSICIUFMunicipio.insertBefore(DivLN, null);
								
								IdNome = "IEM10_0_"+IdPais+"_"+IdEstado+"_1";
								DivLN = document.createElement("table");
								DivLN.setAttribute("id", IdNome);
								BLIndicadoresSICIUFMunicipio.insertBefore(DivLN, null);
								table = document.getElementById(IdNome);
								
								linha = table.insertRow((table.rows.length)-1);
								c0 = linha.insertCell(0);
								c1 = linha.insertCell(1);
								linha = table.insertRow((table.rows.length)-1);
								c0 = linha.insertCell(0);
								c1 = linha.insertCell(1);
								c2 = linha.insertCell(2);
								c3 = linha.insertCell(3);
								
								c0.className = "find";
								c0.innerHTML = "&nbsp;";
								c1.className = "descCampo";
								c1.innerHTML = "Menor preço por 1Mbps (não dedicado) ("+document.formulario.Moeda.value+"):";
								c2.className = "separador";
								c2.innerHTML = "&nbsp;";
								c3.className = "descCampo";
								c3.innerHTML = "Menor preço por 1Mbps (dedicado) ("+document.formulario.Moeda.value+"):";
								
								linha = table.insertRow((table.rows.length)-1);
								c0 = linha.insertCell(0);
								c1 = linha.insertCell(1);
								c2 = linha.insertCell(2);
								c3 = linha.insertCell(3);
								
								c0.className = "find";
								c0.innerHTML = "&nbsp;";
								c1.className = "campo";
								c1.innerHTML = "<input style='width:399px' type='text' name='IEM10MenorPreco1MbpsNaoDedicadoPessoaJuridica_"+IdPais+"_"+IdEstado+"' value='"+IEM10MenorPreco1MbpsNaoDedicadoPessoaJuridica+"' maxlength='11' readonly='readonly' />";
								c2.className = "separador";
								c2.innerHTML = "&nbsp;";
								c3.className = "campo";
								c3.innerHTML = "<input style='width:399px' type='text' name='IEM10MenorPreco1MbpsDedicadoPessoaJuridica_"+IdPais+"_"+IdEstado+"' value='"+IEM10MenorPreco1MbpsDedicadoPessoaJuridica+"' maxlength='11' readonly='readonly' />";
								
								IdNome = "IEM10_1_"+IdPais+"_"+IdEstado+"_1";
								DivLN = document.createElement("table");
								DivLN.setAttribute("id", IdNome);
								BLIndicadoresSICIUFMunicipio.insertBefore(DivLN, null);
								table = document.getElementById(IdNome);
								
								linha = table.insertRow((table.rows.length)-1);
								c0 = linha.insertCell(0);
								c1 = linha.insertCell(1);
								linha = table.insertRow((table.rows.length)-1);
								c0 = linha.insertCell(0);
								c1 = linha.insertCell(1);
								c2 = linha.insertCell(2);
								c3 = linha.insertCell(3);
								
								c0.className = "find";
								c0.innerHTML = "&nbsp;";
								c1.className = "descCampo";
								c1.innerHTML = "Maior preço por 1Mbps (não dedicado) ("+document.formulario.Moeda.value+"):";
								c2.className = "separador";
								c2.innerHTML = "&nbsp;";
								c3.className = "descCampo";
								c3.innerHTML = "Maior preço por 1Mbps (dedicado) ("+document.formulario.Moeda.value+"):";
								
								linha = table.insertRow((table.rows.length)-1);
								c0 = linha.insertCell(0);
								c1 = linha.insertCell(1);
								c2 = linha.insertCell(2);
								c3 = linha.insertCell(3);
								
								c0.className = "find";
								c0.innerHTML = "&nbsp;";
								c1.className = "campo";
								c1.innerHTML = "<input style='width:399px' type='text' name='IEM10MaiorPreco1MbpsNaoDedicadoPessoaJuridica_"+IdPais+"_"+IdEstado+"' value='"+IEM10MaiorPreco1MbpsNaoDedicadoPessoaJuridica+"' maxlength='11' readonly='readonly' />";
								c2.className = "separador";
								c2.innerHTML = "&nbsp;";
								c3.className = "campo";
								c3.innerHTML = "<input style='width:399px' type='text' name='IEM10MaiorPreco1MbpsDedicadoPessoaJuridica_"+IdPais+"_"+IdEstado+"' value='"+IEM10MaiorPreco1MbpsDedicadoPessoaJuridica+"' maxlength='11' readonly='readonly' />";
								
								var xmlhttpResponseXMLIndicadorCidade = xmlhttpResponseXMLIndicadorEstado[i].getElementsByTagName("IndicadorCidade");
								
								for(ii = 0; ii < xmlhttpResponseXMLIndicadorCidade.length; ii++){
									nameNode = xmlhttpResponseXMLIndicadorCidade[ii].getElementsByTagName("IdCidade")[0]; 
									nameTextNode = nameNode.childNodes[0];
									var IdCidade = nameTextNode.nodeValue;
									
									nameNode = xmlhttpResponseXMLIndicadorCidade[ii].getElementsByTagName("NomeCidade")[0]; 
									nameTextNode = nameNode.childNodes[0];
									var NomeCidade = nameTextNode.nodeValue;
									
									nameNode = xmlhttpResponseXMLIndicadorCidade[ii].getElementsByTagName("IPL3PessoaFisica")[0]; 
									nameTextNode = nameNode.childNodes[0];
									var IPL3PessoaFisica = nameTextNode.nodeValue;
									
									DivLN = document.createElement("div");
									DivLN.setAttribute("id", "cp_sub_sub_tit");
									DivLN.setAttribute("style", "font-size:11px;");
									DivLN.innerHTML = "Municipio: "+NomeCidade+" - "+UF;
									BLIndicadoresSICIUFMunicipio.insertBefore(DivLN, null);
									// IPL 3 - FÍSICA
									DivLN = document.createElement("div");
									DivLN.setAttribute("id", "cp_sub_tit");
									DivLN.innerHTML = "<u>IPL 3</u> - Distribuição do quantitativo total de acessos físicos em serviço por tipo de usuário (Pessoa Física).<div style='float:right; text-align:right;'>"+NomeCidade+" - "+UF+"</div>";
									BLIndicadoresSICIUFMunicipio.insertBefore(DivLN, null);
									
									IdNome = "IPL3_0_"+IdPais+"_"+IdEstado+"_"+IdCidade+"_2";
									DivLN = document.createElement("table");
									DivLN.setAttribute("id", IdNome);
									BLIndicadoresSICIUFMunicipio.insertBefore(DivLN, null);
									table = document.getElementById(IdNome);
									
									linha = table.insertRow((table.rows.length)-1);
									c0 = linha.insertCell(0);
									c1 = linha.insertCell(1);
									linha = table.insertRow((table.rows.length)-1);
									c0 = linha.insertCell(0);
									c1 = linha.insertCell(1);
									
									c0.className = "find";
									c0.innerHTML = "&nbsp;";
									c1.className = "descCampo";
									c1.innerHTML = "</b>Total de Acessos:</b>";
									
									linha = table.insertRow((table.rows.length)-1);
									c0 = linha.insertCell(0);
									c1 = linha.insertCell(1);
									
									c0.className = "find";
									c0.innerHTML = "&nbsp;";
									c1.className = "campo";
									c1.innerHTML = "<input style='width:399px' type='text' name='IPL3PessoaFisica_"+IdPais+"_"+IdEstado+"_"+IdCidade+"' value='"+IPL3PessoaFisica+"' maxlength='11' readonly='readonly' />";
									
									nameNode = xmlhttpResponseXMLIndicadorCidade[ii].getElementsByTagName("IPL3PessoaJuridica")[0]; 
									nameTextNode = nameNode.childNodes[0];
									var IPL3PessoaJuridica = nameTextNode.nodeValue;
									// IPL 3 - JURÍDICA
									DivLN = document.createElement("div");
									DivLN.setAttribute("id", "cp_sub_tit");
									DivLN.innerHTML = "<u>IPL 3</u> - Distribuição do quantitativo total de acessos físicos em serviço por tipo de usuário (Pessoa Jurídica).<div style='float:right; text-align:right;'>"+NomeCidade+" - "+UF+"</div>";
									BLIndicadoresSICIUFMunicipio.insertBefore(DivLN, null);
									
									IdNome = "IPL3_0_"+IdPais+"_"+IdEstado+"_"+IdCidade+"_1";
									DivLN = document.createElement("table");
									DivLN.setAttribute("id", IdNome);
									BLIndicadoresSICIUFMunicipio.insertBefore(DivLN, null);
									table = document.getElementById(IdNome);
									
									linha = table.insertRow((table.rows.length)-1);
									c0 = linha.insertCell(0);
									c1 = linha.insertCell(1);
									linha = table.insertRow((table.rows.length)-1);
									c0 = linha.insertCell(0);
									c1 = linha.insertCell(1);
									
									c0.className = "find";
									c0.innerHTML = "&nbsp;";
									c1.className = "descCampo";
									c1.innerHTML = "</b>Total de Acessos:</b>";
									
									linha = table.insertRow((table.rows.length)-1);
									c0 = linha.insertCell(0);
									c1 = linha.insertCell(1);
									
									c0.className = "find";
									c0.innerHTML = "&nbsp;";
									c1.className = "campo";
									c1.innerHTML = "<input style='width:399px' type='text' name='IPL3PessoaJuridica_"+IdPais+"_"+IdEstado+"_"+IdCidade+"' value='"+IPL3PessoaJuridica+"' maxlength='11' readonly='readonly' />";
									// IPL 4
									IdNome = "bl_IPL4_"+IdPais+"_"+IdEstado+"_"+IdCidade;
									DivLN = document.createElement("div");
									DivLN.setAttribute("id", IdNome);
									BLIndicadoresSICIUFMunicipio.insertBefore(DivLN, null);
									var BLIPL4 = document.getElementById(IdNome);
									// IPL 5
									if(IdTipoApuracao == 2 || IdTipoApuracao == 3){
										IdNome = "bl_IPL5_"+IdPais+"_"+IdEstado+"_"+IdCidade;
										DivLN = document.createElement("div");
										DivLN.setAttribute("id", IdNome);
										BLIndicadoresSICIUFMunicipio.insertBefore(DivLN, null);
										var BLIPL5 = document.getElementById(IdNome);
									}
									
									var xmlhttpResponseXMLTecnologia = xmlhttpResponseXMLIndicadorCidade[ii].getElementsByTagName("Tecnologia");
									
									for(var iii = 0; iii < xmlhttpResponseXMLTecnologia.length; iii++){
										nameNode = xmlhttpResponseXMLTecnologia[iii].getElementsByTagName("IdTecnologia")[0]; 
										nameTextNode = nameNode.childNodes[0];
										var IdTecnologia = nameTextNode.nodeValue;
										
										nameNode = xmlhttpResponseXMLTecnologia[iii].getElementsByTagName("DescricaoTecnologia")[0]; 
										nameTextNode = nameNode.childNodes[0];
										var DescricaoTecnologia = nameTextNode.nodeValue;
										
										nameNode = xmlhttpResponseXMLTecnologia[iii].getElementsByTagName("IPL4TotalAcessos")[0]; 
										nameTextNode = nameNode.childNodes[0];
										var IPL4TotalAcessos = nameTextNode.nodeValue;
										// IPL 4
										DivLN = document.createElement("div");
										DivLN.setAttribute("id", "cp_sub_tit");
										DivLN.innerHTML = "<u>IPL 4</u> - Distribuição do quantitativo de acessos físicos em serviço ("+DescricaoTecnologia+").<div style='float:right; text-align:right;'>"+NomeCidade+" - "+UF+"</div>";
										BLIPL4.insertBefore(DivLN, null);
										
										IdNome = "IPL4_0_"+IdPais+"_"+IdEstado+"_"+IdCidade+"_"+IdTecnologia;
										DivLN = document.createElement("table");
										DivLN.setAttribute("id", IdNome);
										BLIPL4.insertBefore(DivLN, null);
										table = document.getElementById(IdNome);
										
										linha = table.insertRow((table.rows.length)-1);
										c0 = linha.insertCell(0);
										c1 = linha.insertCell(1);
										var linha_tit = table.insertRow((table.rows.length)-1);
										c0 = linha_tit.insertCell(0);
										c1 = linha_tit.insertCell(1);
										
										c0.className = "find";
										c0.innerHTML = "&nbsp;";
										c1.className = "descCampo";
										c1.innerHTML = "Total de Acessos:";
										
										var linha_cap = table.insertRow((table.rows.length)-1);
										c0 = linha_cap.insertCell(0);
										c1 = linha_cap.insertCell(1);
										
										c0.className = "find";
										c0.innerHTML = "&nbsp;";
										c1.className = "campo";
										c1.innerHTML = "<input style='width:399px' type='text' name='IPL4TotalAcessos_"+IdPais+"_"+IdEstado+"_"+IdCidade+"_"+IdTecnologia+"' value='"+IPL4TotalAcessos+"' maxlength='11' readonly='readonly' />";
										
										var TitleVelocidade = [
											"0 Kbps a 512 Kbps",
											"512 Kbps a 2 Mbps",
											"2 Mbps a 12 Mbps",
											"12 Mbps a 34 Mbps",
											"> 34 Mbps"
										];
										
										var xmlhttpResponseXMLVelocidade = xmlhttpResponseXMLTecnologia[iii].getElementsByTagName("Velocidade");
										
										for(var iiii = 0; iiii < xmlhttpResponseXMLVelocidade.length; iiii++){
											nameNode = xmlhttpResponseXMLVelocidade[iiii].getElementsByTagName("IdVelocidade")[0]; 
											nameTextNode = nameNode.childNodes[0];
											var IdVelocidade = nameTextNode.nodeValue;
											
											nameNode = xmlhttpResponseXMLVelocidade[iiii].getElementsByTagName("DescricaoVelocidade")[0]; 
											nameTextNode = nameNode.childNodes[0];
											var DescricaoVelocidade = nameTextNode.nodeValue;
											
											nameNode = xmlhttpResponseXMLVelocidade[iiii].getElementsByTagName("IPL4")[0]; 
											nameTextNode = nameNode.childNodes[0];
											var IPL4 = nameTextNode.nodeValue;
											
											if(iiii % 2 != 0){
												IdNome = "IPL4_"+((iiii+1)/2)+"_"+IdPais+"_"+IdEstado+"_"+IdCidade+"_"+IdTecnologia;
												DivLN = document.createElement("table");
												DivLN.setAttribute("id", IdNome);
												BLIPL4.insertBefore(DivLN, null);
												table = document.getElementById(IdNome);
												
												linha = table.insertRow((table.rows.length)-1);
												c0 = linha.insertCell(0);
												c1 = linha.insertCell(1);
												linha_tit = table.insertRow((table.rows.length)-1);
												c0 = linha_tit.insertCell(0);
												c1 = linha_tit.insertCell(1);
												
												c0.className = "find";
												c0.innerHTML = "&nbsp;";
												c1.className = "descCampo";
												c1.innerHTML = TitleVelocidade[IdVelocidade-1];
												
												linha_cap = table.insertRow((table.rows.length)-1);
												c0 = linha_cap.insertCell(0);
												c1 = linha_cap.insertCell(1);
												
												c0.className = "find";
												c0.innerHTML = "&nbsp;";
												c1.className = "campo";
												c1.innerHTML = "<input style='width:399px' type='text' name='IPL4__"+IdPais+"_"+IdEstado+"_"+IdCidade+"_"+IdTecnologia+"_"+IdVelocidade+"' value='"+IPL4+"' maxlength='11' readonly='readonly' />";
											} else{
												c2 = linha_tit.insertCell(2);
												c3 = linha_tit.insertCell(3);
												
												c2.className = "separador";
												c2.innerHTML = "&nbsp;";
												c3.className = "descCampo";
												c3.innerHTML = TitleVelocidade[IdVelocidade-1];
												
												c2 = linha_cap.insertCell(2);
												c3 = linha_cap.insertCell(3);
												
												c2.className = "separador";
												c2.innerHTML = "&nbsp;";
												c3.className = "campo";
												c3.innerHTML = "<input style='width:399px' type='text' name='IPL4_"+IdPais+"_"+IdEstado+"_"+IdCidade+"_"+IdTecnologia+"_"+IdVelocidade+"' value='"+IPL4+"' maxlength='11' readonly='readonly' />";
											}
										}
										
										nameNode = xmlhttpResponseXMLTecnologia[iii].getElementsByTagName("IPL4TotalAcessos")[0]; 
										nameTextNode = nameNode.childNodes[0];
										var IPL4TotalAcessos = nameTextNode.nodeValue;
										
										if(IdTipoApuracao == 2 || IdTipoApuracao == 3){
											nameNode = xmlhttpResponseXMLTecnologia[iii].getElementsByTagName("IPL5")[0]; 
											nameTextNode = nameNode.childNodes[0];
											var IPL5 = nameTextNode.nodeValue;
											
											nameNode = xmlhttpResponseXMLTecnologia[iii].getElementsByTagName("DescricaoIPL5")[0]; 
											nameTextNode = nameNode.childNodes[0];
											var DescricaoIPL5 = nameTextNode.nodeValue;
											
											if(parseInt(IdStatus) > 2){
												IPL5 = formata_float(Arredonda(IPL5, 2), 2).replace(/\./g, ",");
											}
											
											DivLN = document.createElement("div");
											DivLN.setAttribute("id", "cp_sub_tit");
											DivLN.innerHTML = "<u>IPL 5</u> - Capacidade total do sistema implantada e em serviço (Mbps) ("+DescricaoTecnologia+").<div style='float:right; text-align:right;'>"+NomeCidade+" - "+UF+"</div>";
											BLIPL5.insertBefore(DivLN, null);
											
											IdNome = "IPL5_0_"+IdPais+"_"+IdEstado+"_"+IdCidade+"_"+IdTecnologia;
											DivLN = document.createElement("table");
											DivLN.setAttribute("id", IdNome);
											BLIPL5.insertBefore(DivLN, null);
											table = document.getElementById(IdNome);
											
											linha = table.insertRow((table.rows.length)-1);
											c0 = linha.insertCell(0);
											c1 = linha.insertCell(1);
											linha = table.insertRow((table.rows.length)-1);
											c0 = linha.insertCell(0);
											c1 = linha.insertCell(1);
											
											c0.className = "find";
											c0.innerHTML = "&nbsp;";
											c1.className = "descCampo";
											c1.innerHTML = "<b>Capacidade total do sistema implantada e em serviço em Mbps:</b>";
											
											linha = table.insertRow((table.rows.length)-1);
											c0 = linha.insertCell(0);
											c1 = linha.insertCell(1);
											
											c0.className = "find";
											c0.innerHTML = "&nbsp;";
											c1.className = "campo";
											c1.innerHTML = "<input style='width:399px' type='text' name='IPL5_"+IdPais+"_"+IdEstado+"_"+IdCidade+"_"+IdTecnologia+"' value='"+IPL5+"' autocomplete='off' maxlength='11' onChange=\"calcula_ipl6('"+IdPais+"_"+IdEstado+"_"+IdCidade+"');\" onkeypress=\"mascara(this,event,'float')\" onkeydown=\"backspace(this,event)\" onFocus=\"Foco(this,'in')\" onBlur=\"Foco(this,'out')\" /><div id='cp_dc_IPL5'>"+DescricaoIPL5+"</div>";
										}
									}
									
									if(IdTipoApuracao == 2 || IdTipoApuracao == 3){
										nameNode = xmlhttpResponseXMLIndicadorCidade[ii].getElementsByTagName("IPL6")[0]; 
										nameTextNode = nameNode.childNodes[0];
										var IPL6 = nameTextNode.nodeValue;
										
										IPL6 = formata_float(Arredonda(IPL6, 2), 2).replace(/\./g, ",");
										// IPL 6
										DivLN = document.createElement("div");
										DivLN.setAttribute("id", "cp_sub_tit");
										DivLN.innerHTML = "<u>IPL 6</u> - Capacidade total do sistema instalada em Mbps.<div style='float:right; text-align:right;'>"+NomeCidade+" - "+UF+"</div>";
										BLIndicadoresSICIUFMunicipio.insertBefore(DivLN, null);
										
										IdNome = "IPL6_"+IdPais+"_"+IdEstado+"_"+IdCidade;
										DivLN = document.createElement("table");
										DivLN.setAttribute("id", IdNome);
										BLIndicadoresSICIUFMunicipio.insertBefore(DivLN, null);
										table = document.getElementById(IdNome);
										
										linha = table.insertRow((table.rows.length)-1);
										c0 = linha.insertCell(0);
										c1 = linha.insertCell(1);
										linha = table.insertRow((table.rows.length)-1);
										c0 = linha.insertCell(0);
										c1 = linha.insertCell(1);
										
										c0.className = "find";
										c0.innerHTML = "&nbsp;";
										c1.className = "descCampo";
										c1.innerHTML = "Capacidade total do sistema implantada e em serviço:";
										
										linha = table.insertRow((table.rows.length)-1);
										c0 = linha.insertCell(0);
										c1 = linha.insertCell(1);
										
										c0.className = "find";
										c0.innerHTML = "&nbsp;";
										c1.className = "campo";
										c1.innerHTML = "<input style='width:399px' type='text' name='IPL6_"+IdPais+"_"+IdEstado+"_"+IdCidade+"' value='"+IPL6+"' maxlength='11' readonly='readonly' />";
									}
								}
							}
						}
						
						document.formulario.PeriodoApuracao.focus();
						verificaAcao();
				}
			}
		});
	}