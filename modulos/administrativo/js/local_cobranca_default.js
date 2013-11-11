	function janela_busca_local_cobranca(){
		janelas('../administrativo/busca_local_cobranca.php',360,283,250,100,'');
	}

	function busca_local_cobranca(IdLocalCobranca, Erro, Local, ListarCampo){
		if(IdLocalCobranca == ''){
			IdLocalCobranca = 0;
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
		
		if(Local == 'AdicionarLoteRepasse' && (ListarCampo == '' || ListarCampo == undefined)){
			if(document.formulario.IdStatus.value > 1){
				return false;
			}
		}
		
		
		url = "../administrativo/xml/local_cobranca.php?IdLocalCobranca="+IdLocalCobranca;
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
						
						switch(Local){
							case 'LocalCobrancaParametro':
								document.formulario.IdLocalCobranca.value 				= '';							
								document.formulario.DescricaoLocalCobranca.value 		= '';
								document.formulario.AbreviacaoNomeLocalCobranca.value 	= "";
								document.formulario.IdLocalCobrancaLayout.value 		= '';							
								busca_local_cobraca_layout_parametro("",false);						
								document.formulario.IdLocalCobranca.focus();
								break;
							case 'LocalCobrancaParametroContrato':
								document.formulario.IdLocalCobranca.value 					= '';
								document.formulario.DescricaoLocalCobranca.value 			= '';
								document.formulario.AbreviacaoNomeLocalCobranca.value 		= "";
								document.formulario.IdLocalCobrancaParametroContrato.value	=	"";
								document.formulario.DescricaoParametroContrato.value		=	"";
								document.formulario.Obrigatorio[0].selected 				= 	true;
								document.formulario.ObrigatorioStatus.value 				= 	"";
								document.formulario.Calculavel[0].selected 					= 	true;
								document.formulario.Editavel[0].selected					= 	true;
								document.formulario.Visivel[0].selected 					= 	true;
								document.formulario.VisivelOS[0].selected 					= 	true;
								document.formulario.ParametroDemonstrativo[0].selected 		= 	true;
								document.formulario.Obs.value								=	"";
								document.formulario.IdStatusParametro[0].selected 			= 	true;
								document.formulario.IdMascaraCampo[0].selected 				= 	true;
								document.formulario.ValorDefaultInput.value					=	"";
								document.formulario.RotinaCalculo.value						=	"";	
								document.formulario.OpcaoValor.value						=	"";	
								document.formulario.IdMascaraCampo.value					=	"";	
								document.formulario.IdTipoParametro.value					=	"";	
								document.formulario.RotinaCalculo.value						=	"";				
								document.formulario.DataCriacao.value						=	"";
								document.formulario.LoginCriacao.value						=	"";
								document.formulario.DataAlteracao.value						=	"";
								document.formulario.LoginAlteracao.value					=	"";
								document.formulario.Obrigatorio.disabled					=	false;
								document.formulario.IdMascaraCampo.disabled					=	false;
								
								while(document.getElementById('tabelaParametro').rows.length > 2){
									document.getElementById('tabelaParametro').deleteRow(1);
								}
								status_inicial();
								document.getElementById('tabelaParametroTotal').innerHTML	=	"Total: 0";
								document.formulario.IdLocalCobranca.focus();
								break;
							case 'ContaEventual':
								document.formulario.ValorDespesaLocalCobranca.value 	= "0,00";
								document.formulario.ValorCobrancaMinima.value 			= "";
								document.formulario.IdTipoLocalCobranca.value 			= "";
								break;
							case 'OrdemServico':
								document.formulario.ValorDespesaLocalCobranca.value 	= "0,00";
								document.formulario.ValorCobrancaMinima.value 			= "";
								break;
							case 'ProcessoFinanceiro':
								document.formulario.IdTipoLocalCobranca.value 			= "";
								document.formulario.DataNotaFiscal.value 				= ''; 							
							
								document.getElementById('DataNotaFiscal').style.display			= 'none';							
								document.getElementById('cpDataNotaFiscal').style.display 		= 'none';
								document.getElementById('cpDataNotaFiscalIco').style.display 	= 'none';
								break;
							case 'AdicionarLoteRepasse':
								document.formulario.IdLocalRecebimento.value 			= "";
								break;
							case 'LocalCobranca':
								document.formulario.IdPessoaF.value 						= "";
								document.formulario.IdPessoa.value							= "";							
								document.formulario.IdLocalCobranca.value 					= "";
								document.formulario.IdTipoLocalCobranca.value 				= "";
								document.formulario.IdLojaCobrancaUnificada.value			= "";
								document.formulario.IdLocalCobrancaUnificada.value			= "";	
								document.formulario.IdNotaFiscalTipo.value					= "";
								document.formulario.AvisoRegressivo.value					= "";
								document.formulario.DiasCompensacao.value					= "";
								document.formulario.DescricaoLocalCobranca.value 			= "";
								document.formulario.AbreviacaoNomeLocalCobranca.value 		= "";
								document.formulario.ValorDespesaLocalCobranca.value 		= "";
								document.formulario.IdLocalCobrancaLayout.value 			= "";
								document.formulario.DescricaoLocalCobrancaLayout.value		= "";
								document.formulario.PercentualJurosDiarios.value 			= "";
								document.formulario.PercentualMulta.value			 		= "";
								document.formulario.ValorCobrancaMinima.value 				= "";
								document.formulario.ValorTaxaReImpressaoBoleto.value 		= "";
								document.formulario.IdArquivoRetornoTipo.value				= "";
								document.formulario.IdDuplicataLayout.value					= "";
								document.formulario.DescricaoDuplicata.value				= "";
								document.formulario.DescricaoArquivoRetornoTipo.value		= "";
								document.formulario.IdArquivoRemessaTipo.value				= "";
								document.formulario.DescricaoArquivoRemessaTipo.value		= "";
								document.formulario.ExtLogo.value							= "";
								document.formulario.AvisoFaturaAtraso.value					= "";
								document.formulario.tempEndArquivo.value					= "";
								document.formulario.InicioNossoNumero.value					= "";
								document.formulario.DataCriacao.value 						= "";
								document.formulario.LoginCriacao.value 						= "";
								document.formulario.DataAlteracao.value 					= "";
								document.formulario.LoginAlteracao.value					= "";
								document.formulario.IdContraApresentacao.value				= "";
								document.formulario.IdCobrarMultaJurosProximaFatura.value	= "";
								document.formulario.IdStatus.value							= "";
								document.formulario.MsgDemonstrativo.value					= "";
								document.formulario.IdAtualizarVencimentoViaCDA.value		= document.formulario.IdAtualizarVencimentoViaCDADefault.value;
								document.formulario.IdAtualizarRemessaViaCDA.value			= document.formulario.IdAtualizarRemessaViaCDADefault.value;
								document.formulario.IdAtualizarRemessaViaContaReceber.value	= document.formulario.IdAtualizarRemessaViaContaReceberDefault.value;
								
								document.formulario.Acao.value 								= 'inserir';
								
								habilitar_arquivo_remessa_tipo();
								
								document.getElementById('cp_juridica').style.display			= 'block';
								document.getElementById('cp_fisica').style.display				= 'none';
								
								document.getElementById("EndArquivo").style.display 		= "block";
								document.getElementById("cp_OpcaoImagem").style.display 	= "none";
								document.getElementById("VerImagem").style.backgroundImage  = "";
								document.getElementById("VerImagem").style.border			= "0";	
								
								document.getElementById("tbAvisoFaturaAtraso").style.display 	= "none";							
								
								document.formulario.Nome.value 				= '';
								document.formulario.NomeF.value				= '';
								document.formulario.RazaoSocial.value 		= '';
								document.formulario.CPF.value 				= '';
								document.formulario.CNPJ.value 				= '';
								document.formulario.Email.value 			= '';
								
								addParmUrl("marLocalCobrancaParametro","IdLocalCobranca","");
								addParmUrl("marLocalCobrancaParametroNovo","IdLocalCobranca","");
								addParmUrl("marLocalCobrancaParametroContrato","IdLocalCobranca","");
								addParmUrl("marLocalCobrancaParametroContratoNovo","IdLocalCobranca","");
								
								document.formulario.IdLocalCobranca.focus();			
								verificaAcao();
								status_inicial();
								break;
							case "AgruparContaReceber":
								document.formulario.ValorDespesaLocalCobranca.value		= '0,00';
								document.formulario.ValorTaxaReImpressaoBoleto.value	= '0,00';
								document.formulario.PercentualJurosDiarios.value		= 0.00;
								document.formulario.PercentualMulta.value				= 0.00;
								document.getElementById("titMultaJuros").innerHTML		= "Multa 0,000%. Juros 0,000%";
								
								calcula_valor();
								break;
							default:
								document.formulario.IdPessoaF.value 					= "";
								document.formulario.IdPessoa.value						= "";							
								document.formulario.IdLocalCobranca.value 				= "";
								document.formulario.IdTipoLocalCobranca.value 			= "";
								document.formulario.IdLojaCobrancaUnificada.value		= "";
								document.formulario.IdDuplicataLayout.value				= "";
								document.formulario.DescricaoDuplicata.value			= "";
								document.formulario.IdLocalCobrancaUnificada.value		= "";	
								document.formulario.IdNotaFiscalTipo.value				= "";
								document.formulario.AvisoRegressivo.value				= "";
								document.formulario.DiasCompensacao.value				= "";
								document.formulario.DescricaoLocalCobranca.value 		= "";
								document.formulario.AbreviacaoNomeLocalCobranca.value 	= "";
								document.formulario.ValorDespesaLocalCobranca.value 	= "";
								document.formulario.IdLocalCobrancaLayout.value 		= "";
								document.formulario.DescricaoLocalCobrancaLayout.value	= "";
								document.formulario.PercentualJurosDiarios.value 		= "";
								document.formulario.PercentualMulta.value			 	= "";
								document.formulario.ValorCobrancaMinima.value 			= "";
								document.formulario.ValorTaxaReImpressaoBoleto.value 	= "";
								document.formulario.IdArquivoRetornoTipo.value			= "";
								document.formulario.DescricaoArquivoRetornoTipo.value	= "";
								document.formulario.IdArquivoRemessaTipo.value			= "";
								document.formulario.DescricaoArquivoRemessaTipo.value	= "";
								document.formulario.ExtLogo.value						= "";
								document.formulario.AvisoFaturaAtraso.value				= "";
								document.formulario.tempEndArquivo.value				= "";
								document.formulario.DataCriacao.value 					= "";
								document.formulario.LoginCriacao.value 					= "";
								document.formulario.DataAlteracao.value 				= "";
								document.formulario.LoginAlteracao.value				= "";
								document.formulario.Acao.value 							= 'inserir';
								
								habilitar_arquivo_remessa_tipo();
								
								document.getElementById('cp_juridica').style.display			= 'block';
								document.getElementById('cp_fisica').style.display				= 'none';
								
								document.getElementById("EndArquivo").style.display 		= "block";
								document.getElementById("cp_OpcaoImagem").style.display 	= "none";
								document.getElementById("VerImagem").style.backgroundImage  = "";
								document.getElementById("VerImagem").style.border			= "0";	
								
								document.getElementById("tbAvisoFaturaAtraso").style.display 	= "none";							
								
								document.formulario.Nome.value 				= '';
								document.formulario.NomeF.value				= '';
								document.formulario.RazaoSocial.value 		= '';
								document.formulario.CPF.value 				= '';
								document.formulario.CNPJ.value 				= '';
								document.formulario.Email.value 			= '';
								
								addParmUrl("marLocalCobrancaParametro","IdLocalCobranca","");
								addParmUrl("marLocalCobrancaParametroNovo","IdLocalCobranca","");
								addParmUrl("marLocalCobrancaParametroContrato","IdLocalCobranca","");
								addParmUrl("marLocalCobrancaParametroContratoNovo","IdLocalCobranca","");
								
								document.formulario.IdLocalCobranca.focus();			
								verificaAcao();
								status_inicial();
								break;
						}
					}else{
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("IdLocalCobranca")[0]; 
						nameTextNode = nameNode.childNodes[0];
						IdLocalCobranca = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("IdTipoLocalCobranca")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var IdTipoLocalCobranca = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoLocalCobranca")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var DescricaoLocalCobranca = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("AbreviacaoNomeLocalCobranca")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var AbreviacaoNomeLocalCobranca = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("ValorDespesaLocalCobranca")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var ValorDespesaLocalCobranca = nameTextNode.nodeValue;					
						
						switch (Local){
							case 'LocalCobrancaParametro':
								
								nameNode = xmlhttp.responseXML.getElementsByTagName("IdLocalCobrancaLayout")[0]; 
								nameTextNode = nameNode.childNodes[0];
								var IdLocalCobrancaLayout = nameTextNode.nodeValue;
							
								document.formulario.IdLocalCobranca.value				= IdLocalCobranca;
								document.formulario.DescricaoLocalCobranca.value 		= DescricaoLocalCobranca;
								document.formulario.AbreviacaoNomeLocalCobranca.value 	= AbreviacaoNomeLocalCobranca;
								document.formulario.IdLocalCobrancaLayout.value			= IdLocalCobrancaLayout;
								busca_local_cobraca_layout_parametro(IdLocalCobrancaLayout,false);													
								break;
							case 'LocalCobrancaParametroContrato':
								document.formulario.IdLocalCobranca.value				= IdLocalCobranca;
								document.formulario.DescricaoLocalCobranca.value 		= DescricaoLocalCobranca;
								document.formulario.AbreviacaoNomeLocalCobranca.value 	= AbreviacaoNomeLocalCobranca;							

								busca_local_cobranca_parametro_contrato(IdLocalCobranca,false,document.formulario.Local.value,document.formulario.IdLocalCobrancaParametroContratoTemp.value);
								listarParametro(IdLocalCobranca,false);
								break;
							case 'ContaEventual':
								document.formulario.ValorDespesaLocalCobranca.value 	= formata_float(Arredonda(ValorDespesaLocalCobranca,2),2).replace(".",",");
								document.formulario.IdTipoLocalCobranca.value 			= IdTipoLocalCobranca;
								busca_valor_minimo(IdLocalCobranca);
								break;
							case 'OrdemServico':
								document.formulario.ValorDespesaLocalCobranca.value 	= formata_float(Arredonda(ValorDespesaLocalCobranca,2),2).replace(".",",");
								busca_valor_minimo(IdLocalCobranca);
								break;
							case 'ProcessoFinanceiro':							
									
								nameNode = xmlhttp.responseXML.getElementsByTagName("IdNotaFiscalTipo")[0]; 
								nameTextNode = nameNode.childNodes[0];
								var IdNotaFiscalTipo = nameTextNode.nodeValue;
								
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
								break;
							case 'ArquivoRetorno':
								document.formulario.IdTipoLocalCobranca.value 			= IdTipoLocalCobranca;
								break;
							case 'CancelarArquivoRetorno':
								document.formulario.IdTipoLocalCobranca.value 			= IdTipoLocalCobranca;
								break;
							case 'AdicionarLoteRepasse':
								var cont = 0; ii='';
								if(ListarCampo == '' || ListarCampo == undefined){
									if(document.formulario.Filtro_IdLocalRecebimento.value == ''){
										document.formulario.Filtro_IdLocalRecebimento.value = IdLocalCobranca;
										ii = 0;
									}else{
										var tempFiltro	=	document.formulario.Filtro_IdLocalRecebimento.value.split(',');
											
										ii=0; 
										while(tempFiltro[ii] != undefined){
											if(tempFiltro[ii] != IdLocalCobranca){
												cont++;		
											}
											ii++;
										}
										if(ii == cont){
											document.formulario.Filtro_IdLocalRecebimento.value = document.formulario.Filtro_IdLocalRecebimento.value + "," + IdLocalCobranca;
										}
									}
								}else{
									ii=0;
								}
								if(ii == cont){
								
									var tam, linha, c0, c1, c2;
									
									tam 	= document.getElementById('tabelaLocalRecebimento').rows.length;
									linha	= document.getElementById('tabelaLocalRecebimento').insertRow(tam-1);
									
									if(tam%2 != 0){
										linha.style.backgroundColor = "#E2E7ED";
									}
									
									linha.accessKey 	= IdLocalCobranca; 
									
									c0	= linha.insertCell(0);	
									c1	= linha.insertCell(1);	
									c2	= linha.insertCell(2);
									
									var linkIni = "<a href='cadastro_local_cobranca.php?IdLocalCobranca="+IdLocalCobranca+"'>";
									var linkFim = "</a>";
									
									c0.innerHTML = linkIni + IdLocalCobranca + linkFim;
									c0.style.padding =	"0 0 0 5px";
									
									c1.innerHTML = linkIni + DescricaoLocalCobranca + linkFim;
									
									if(document.formulario.IdStatus.value == 3 || document.formulario.IdStatus.value == 2){
										c2.innerHTML = "<img src='../../img/estrutura_sistema/ico_del_c.gif' alt='Excluir?'\">";
									}else{
										c2.innerHTML = "<img src='../../img/estrutura_sistema/ico_del.gif' alt='Excluir?' onClick=\"remover_filtro_local_recebimento("+IdLocalCobranca+")\"></tr>";
									}
									c2.style.textAlign = "center";
									c2.style.cursor = "pointer";
									
									if(document.formulario.IdLoteRepasse.value == ''){
										document.getElementById('totaltabelaLocalRecebimento').innerHTML	=	'Total: '+(ii+1);
									}else{
										if(document.formulario.Erro.value != ''){
											scrollWindow('bottom');
										}
									}
								}
								document.formulario.IdLocalRecebimento.value 	= "";
								break;
							case "AgruparContaReceber":
								nameNode = xmlhttp.responseXML.getElementsByTagName("PercentualMulta")[0]; 
								nameTextNode = nameNode.childNodes[0];
								var PercentualMulta = nameTextNode.nodeValue;
								
								nameNode = xmlhttp.responseXML.getElementsByTagName("PercentualJurosDiarios")[0]; 
								nameTextNode = nameNode.childNodes[0];
								var PercentualJurosDiarios = nameTextNode.nodeValue;
								
								nameNode = xmlhttp.responseXML.getElementsByTagName("ValorTaxaReImpressaoBoleto")[0]; 
								nameTextNode = nameNode.childNodes[0];
								var ValorTaxaReImpressaoBoleto = nameTextNode.nodeValue;
								
								document.formulario.PercentualJurosDiarios.value		= PercentualJurosDiarios;
								document.formulario.PercentualMulta.value				= PercentualMulta;
								document.getElementById("titMultaJuros").innerHTML		= "Multa "+PercentualMulta.replace(/\./i, ",")+"%. Juros "+PercentualJurosDiarios.replace(/\./i, ",")+"%";
								document.formulario.ValorDespesaLocalCobranca.value		= formata_float(Arredonda(ValorDespesaLocalCobranca, 2), 2).replace(".",",");
								document.formulario.ValorTaxaReImpressaoBoleto.value	= formata_float(Arredonda(ValorTaxaReImpressaoBoleto, 2), 2).replace(".",",");
								
								calcula_valor();
								break;
							default:
								nameNode = xmlhttp.responseXML.getElementsByTagName("IdLoja")[0]; 
								nameTextNode = nameNode.childNodes[0];
								var IdLoja = nameTextNode.nodeValue;
								
								nameNode = xmlhttp.responseXML.getElementsByTagName("IdPessoa")[0]; 
								nameTextNode = nameNode.childNodes[0];
								var IdPessoa = nameTextNode.nodeValue;
								
								nameNode = xmlhttp.responseXML.getElementsByTagName("IdLocalCobrancaLayout")[0]; 
								nameTextNode = nameNode.childNodes[0];
								var IdLocalCobrancaLayout = nameTextNode.nodeValue;
								
								nameNode = xmlhttp.responseXML.getElementsByTagName("IdLojaCobrancaUnificada")[0]; 
								nameTextNode = nameNode.childNodes[0];
								var IdLojaCobrancaUnificada = nameTextNode.nodeValue;
								
								nameNode = xmlhttp.responseXML.getElementsByTagName("IdNotaFiscalTipo")[0]; 
								nameTextNode = nameNode.childNodes[0];
								var IdNotaFiscalTipo = nameTextNode.nodeValue;
								
								nameNode = xmlhttp.responseXML.getElementsByTagName("DiasCompensacao")[0]; 
								nameTextNode = nameNode.childNodes[0];
								var DiasCompensacao = nameTextNode.nodeValue;
								
								nameNode = xmlhttp.responseXML.getElementsByTagName("DiasAvisoRegressivo")[0]; 
								nameTextNode = nameNode.childNodes[0];
								var DiasAvisoRegressivo = nameTextNode.nodeValue;
								
								nameNode = xmlhttp.responseXML.getElementsByTagName("IdLocalCobrancaUnificada")[0]; 
								nameTextNode = nameNode.childNodes[0];
								var IdLocalCobrancaUnificada = nameTextNode.nodeValue;
							
								nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoLocalCobrancaLayout")[0]; 
								nameTextNode = nameNode.childNodes[0];
								var DescricaoLocalCobrancaLayout = nameTextNode.nodeValue;
								
								nameNode = xmlhttp.responseXML.getElementsByTagName("PercentualJurosDiarios")[0]; 
								nameTextNode = nameNode.childNodes[0];
								var PercentualJurosDiarios = nameTextNode.nodeValue;
							
								nameNode = xmlhttp.responseXML.getElementsByTagName("PercentualMulta")[0]; 
								nameTextNode = nameNode.childNodes[0];
								var PercentualMulta = nameTextNode.nodeValue;	
							
								nameNode = xmlhttp.responseXML.getElementsByTagName("ContraApresentacao")[0]; 
								nameTextNode = nameNode.childNodes[0];
								var ContraApresentacao = nameTextNode.nodeValue;	
							
								nameNode = xmlhttp.responseXML.getElementsByTagName("CobrarMultaJurosProximaFatura")[0]; 
								nameTextNode = nameNode.childNodes[0];
								var CobrarMultaJurosProximaFatura = nameTextNode.nodeValue;					
							
								nameNode = xmlhttp.responseXML.getElementsByTagName("ValorCobrancaMinima")[0]; 
								nameTextNode = nameNode.childNodes[0];
								var ValorCobrancaMinima = nameTextNode.nodeValue;
							
								nameNode = xmlhttp.responseXML.getElementsByTagName("IdArquivoRetornoTipo")[0]; 
								nameTextNode = nameNode.childNodes[0];
								var IdArquivoRetornoTipo = nameTextNode.nodeValue;
								
								nameNode = xmlhttp.responseXML.getElementsByTagName("IdDuplicataLayout")[0]; 
								nameTextNode = nameNode.childNodes[0];
								var IdDuplicataLayout = nameTextNode.nodeValue;
								
								nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoDuplicata")[0]; 
								nameTextNode = nameNode.childNodes[0];
								var DescricaoDuplicata = nameTextNode.nodeValue;
								
								nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoArquivoRetornoTipo")[0]; 
								nameTextNode = nameNode.childNodes[0];
								var DescricaoArquivoRetornoTipo = nameTextNode.nodeValue;
								
								nameNode = xmlhttp.responseXML.getElementsByTagName("IdArquivoRemessaTipo")[0]; 
								nameTextNode = nameNode.childNodes[0];
								var IdArquivoRemessaTipo = nameTextNode.nodeValue;

								nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoArquivoRemessaTipo")[0]; 
								nameTextNode = nameNode.childNodes[0];
								var DescricaoArquivoRemessaTipo = nameTextNode.nodeValue;
								
								nameNode = xmlhttp.responseXML.getElementsByTagName("ValorTaxaReImpressaoBoleto")[0]; 
								nameTextNode = nameNode.childNodes[0];
								var ValorTaxaReImpressaoBoleto = nameTextNode.nodeValue;
								
								nameNode = xmlhttp.responseXML.getElementsByTagName("TipoPessoa")[0]; 
								nameTextNode = nameNode.childNodes[0];
								var TipoPessoa = nameTextNode.nodeValue;
								
								nameNode = xmlhttp.responseXML.getElementsByTagName("Email")[0]; 
								nameTextNode = nameNode.childNodes[0];
								var Email = nameTextNode.nodeValue;
								
								nameNode = xmlhttp.responseXML.getElementsByTagName("CPF_CNPJ")[0]; 
								nameTextNode = nameNode.childNodes[0];
								var CPF_CNPJ = nameTextNode.nodeValue;
								
								nameNode = xmlhttp.responseXML.getElementsByTagName("RazaoSocial")[0]; 
								nameTextNode = nameNode.childNodes[0];
								var RazaoSocial = nameTextNode.nodeValue;
								
								nameNode = xmlhttp.responseXML.getElementsByTagName("Nome")[0]; 
								nameTextNode = nameNode.childNodes[0];
								var Nome = nameTextNode.nodeValue;
								
								nameNode = xmlhttp.responseXML.getElementsByTagName("ExtLogo")[0]; 
								nameTextNode = nameNode.childNodes[0];
								var ExtLogo = nameTextNode.nodeValue;
								
								nameNode = xmlhttp.responseXML.getElementsByTagName("AvisoFaturaAtraso")[0]; 
								nameTextNode = nameNode.childNodes[0];
								var AvisoFaturaAtraso = nameTextNode.nodeValue;
								
								nameNode = xmlhttp.responseXML.getElementsByTagName("IdStatus")[0]; 
								nameTextNode = nameNode.childNodes[0];
								var IdStatus = nameTextNode.nodeValue;
								
								nameNode = xmlhttp.responseXML.getElementsByTagName("InicioNossoNumero")[0]; 
								nameTextNode = nameNode.childNodes[0];
								var InicioNossoNumero = nameTextNode.nodeValue;
								
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
								
								nameNode = xmlhttp.responseXML.getElementsByTagName("AtualizarVencimentoViaCDA")[0]; 
								nameTextNode = nameNode.childNodes[0];
								var AtualizarVencimentoViaCDA = nameTextNode.nodeValue;
								
								nameNode = xmlhttp.responseXML.getElementsByTagName("AtualizarRemessaViaCDA")[0]; 
								nameTextNode = nameNode.childNodes[0];
								var AtualizarRemessaViaCDA = nameTextNode.nodeValue;
								
								nameNode = xmlhttp.responseXML.getElementsByTagName("AtualizarRemessaViaContaReceber")[0]; 
								nameTextNode = nameNode.childNodes[0];
								var AtualizarRemessaViaContaReceber = nameTextNode.nodeValue;
								
								nameNode = xmlhttp.responseXML.getElementsByTagName("MsgDemonstrativo")[0]; 
								nameTextNode = nameNode.childNodes[0];
								var MsgDemonstrativo = nameTextNode.nodeValue;
								
								document.formulario.IdPessoa.value							= IdPessoa;
								document.formulario.IdLocalCobranca.value					= IdLocalCobranca;
								document.formulario.IdTipoLocalCobranca.value				= IdTipoLocalCobranca;
								document.formulario.IdLojaCobrancaUnificada.value			= IdLojaCobrancaUnificada;	
								document.formulario.IdNotaFiscalTipo.value					= IdNotaFiscalTipo;
								document.formulario.DiasCompensacao.value					= DiasCompensacao;		
								document.formulario.AvisoRegressivo.value					= DiasAvisoRegressivo;
								document.formulario.DescricaoLocalCobranca.value 			= DescricaoLocalCobranca;
								document.formulario.AbreviacaoNomeLocalCobranca.value 		= AbreviacaoNomeLocalCobranca;
								document.formulario.ValorDespesaLocalCobranca.value 		= formata_float(Arredonda(ValorDespesaLocalCobranca,2),2).replace(".",",");
								document.formulario.IdLocalCobrancaLayout.value 			= IdLocalCobrancaLayout;
								document.formulario.DescricaoLocalCobrancaLayout.value		= DescricaoLocalCobrancaLayout;
								document.formulario.PercentualJurosDiarios.value 			= formata_float(Arredonda(PercentualJurosDiarios,3),3).replace(".",",");
								document.formulario.PercentualMulta.value 					= formata_float(Arredonda(PercentualMulta,3),3).replace(".",",");
								document.formulario.ValorCobrancaMinima.value 				= formata_float(Arredonda(ValorCobrancaMinima,2),2).replace(".",",");
								document.formulario.ValorTaxaReImpressaoBoleto.value 		= formata_float(Arredonda(ValorTaxaReImpressaoBoleto,2),2).replace(".",",");
								document.formulario.IdArquivoRetornoTipo.value				= IdArquivoRetornoTipo;
								document.formulario.IdDuplicataLayout.value					= IdDuplicataLayout;
								document.formulario.DescricaoDuplicata.value				= DescricaoDuplicata;
								document.formulario.DescricaoArquivoRetornoTipo.value		= DescricaoArquivoRetornoTipo;
								document.formulario.IdArquivoRemessaTipo.value				= IdArquivoRemessaTipo;
								document.formulario.DescricaoArquivoRemessaTipo.value		= DescricaoArquivoRemessaTipo;
								document.formulario.ExtLogo.value							= ExtLogo;
								document.formulario.AvisoFaturaAtraso.value					= AvisoFaturaAtraso;
								document.formulario.InicioNossoNumero.value					= InicioNossoNumero;
								document.formulario.IdContraApresentacao.value				= ContraApresentacao;
								document.formulario.IdCobrarMultaJurosProximaFatura.value	= CobrarMultaJurosProximaFatura;
								document.formulario.IdStatus.value							= IdStatus;
								document.formulario.DataCriacao.value 						= dateFormat(DataCriacao);
								document.formulario.LoginCriacao.value 						= LoginCriacao;
								document.formulario.DataAlteracao.value 					= dateFormat(DataAlteracao);
								document.formulario.LoginAlteracao.value					= LoginAlteracao;
								document.formulario.MsgDemonstrativo.value					= MsgDemonstrativo;
								document.formulario.Acao.value 								= 'alterar';								
								
								busca_local_cobranca_unificada(IdLojaCobrancaUnificada,IdLocalCobrancaUnificada);
								
								document.formulario.tempEndArquivo.value = "local_cobranca/personalizacao/"+IdLoja+"/"+IdLocalCobranca+"."+ExtLogo;
								
								if(ExtLogo != ""){								
									document.getElementById("EndArquivo").style.display 		= "none";
									document.getElementById("cp_OpcaoImagem").style.display 	= "block";
									document.formulario.OpcaoImagem[1].selected 				= true;
									document.getElementById("VerImagem").style.backgroundImage  = "url(local_cobranca/personalizacao/"+IdLoja+"/"+IdLocalCobranca+"."+ExtLogo+")"; 	
									document.getElementById("VerImagem").style.border 			= "1px #A4A4A4 solid";
								}else{								
									document.getElementById("EndArquivo").style.display 		= "block";
									document.getElementById("cp_OpcaoImagem").style.display 	= "none";
									document.getElementById("VerImagem").style.backgroundImage  = "";
									document.getElementById("VerImagem").style.border			= "0";								
								}							
								document.formulario.IdPessoa.value 			= IdPessoa;
								document.formulario.CPF.value 				= CPF_CNPJ;
								document.formulario.CNPJ.value 				= CPF_CNPJ;
								document.formulario.Email.value 			= Email;
								
								if(TipoPessoa == 2){
									document.formulario.IdPessoaF.value 	= IdPessoa;
									document.formulario.NomeF.value 		= Nome;
								
									document.getElementById('cp_fisica').style.display		= 'block';
									document.getElementById('cp_juridica').style.display	= 'none';
								}else{
									document.formulario.RazaoSocial.value 	= RazaoSocial;
									document.formulario.Nome.value 			= Nome;
									document.formulario.IdPessoa.value 		= IdPessoa;
								
									document.getElementById('cp_juridica').style.display	= 'block';
									document.getElementById('cp_fisica').style.display		= 'none';
								}
								if(IdLocalCobrancaLayout != ''){
									document.getElementById("tbAvisoFaturaAtraso").style.display 	= "block";
								}else{
									document.getElementById("tbAvisoFaturaAtraso").style.display 	= "none";
								}
								
								habilitar_arquivo_remessa_tipo(IdTipoLocalCobranca);
								
								if(document.formulario.IdAtualizarVencimentoViaCDA != undefined){
									document.formulario.IdAtualizarVencimentoViaCDA.value	= AtualizarVencimentoViaCDA;
								}
								
								if(document.formulario.IdAtualizarRemessaViaCDA != undefined){
									document.formulario.IdAtualizarRemessaViaCDA.value	= AtualizarRemessaViaCDA;
								}
								
								if(document.formulario.IdAtualizarRemessaViaContaReceber != undefined){
									document.formulario.IdAtualizarRemessaViaContaReceber.value		= AtualizarRemessaViaContaReceber;
								}
								
								addParmUrl("marLocalCobrancaParametro","IdLocalCobranca",IdLocalCobranca);
								addParmUrl("marLocalCobrancaParametroNovo","IdLocalCobranca",IdLocalCobranca);
								addParmUrl("marLocalCobrancaParametroContrato","IdLocalCobranca",IdLocalCobranca);
								addParmUrl("marLocalCobrancaParametroContratoNovo","IdLocalCobranca",IdLocalCobranca);
								verificaAcao();
								break;
						}
					}
					if(document.getElementById("quadroBuscaLocalCobranca") != null){
						if(document.getElementById("quadroBuscaLocalCobranca").style.display	==	"block"){
							document.getElementById("quadroBuscaLocalCobranca").style.display = "none";
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

	function busca_local_cobranca_geracao(IdLocalCobranca, Erro, Local, ListarCampo){

		if(IdLocalCobranca == ''){
			IdLocalCobranca = 0;
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
		
		if(Local == 'AdicionarLoteRepasse' && (ListarCampo == '' || ListarCampo == undefined)){
			if(document.formulario.IdStatus.value > 1){
				return false;
			}
		}
		
		
		url = "../administrativo/xml/local_cobranca_geracao.php?IdLocalCobranca="+IdLocalCobranca;
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
						
						switch(Local){
							case 'LocalCobrancaParametro':
								document.formulario.IdLocalCobranca.value 				= '';							
								document.formulario.DescricaoLocalCobranca.value 		= '';
								document.formulario.AbreviacaoNomeLocalCobranca.value 	= "";
								document.formulario.IdLocalCobrancaLayout.value 		= '';							
								busca_local_cobraca_layout_parametro("",false);						
								document.formulario.IdLocalCobranca.focus();
								break;
							case 'LocalCobrancaParametroContrato':
								document.formulario.IdLocalCobranca.value 				= '';
								document.formulario.DescricaoLocalCobranca.value 		= '';
								document.formulario.AbreviacaoNomeLocalCobranca.value 	= "";
								
								while(document.getElementById('tabelaParametro').rows.length > 2){
									document.getElementById('tabelaParametro').deleteRow(1);
								}
								document.getElementById('tabelaParametroTotal').innerHTML	=	"Total: 0";
								document.formulario.IdLocalCobranca.focus();
								break;
							case 'ContaEventual':
								document.formulario.ValorDespesaLocalCobranca.value 	= "0,00";
								document.formulario.ValorCobrancaMinima.value 			= "";
								break;
							case 'OrdemServico':
								document.formulario.ValorDespesaLocalCobranca.value 	= "0,00";
								document.formulario.ValorCobrancaMinima.value 			= "";
								break;
							case 'ProcessoFinanceiro':
								document.formulario.IdTipoLocalCobranca.value 			= "";
								document.formulario.DataNotaFiscal.value 				= ''; 							
							
								document.getElementById('DataNotaFiscal').style.display			= 'none';							
								document.getElementById('cpDataNotaFiscal').style.display 		= 'none';
								document.getElementById('cpDataNotaFiscalIco').style.display 	= 'none';
								break;
						}
					}else{
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("IdLocalCobranca")[0]; 
						nameTextNode = nameNode.childNodes[0];
						IdLocalCobranca = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("IdTipoLocalCobranca")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var IdTipoLocalCobranca = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoLocalCobranca")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var DescricaoLocalCobranca = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("AbreviacaoNomeLocalCobranca")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var AbreviacaoNomeLocalCobranca = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("ValorDespesaLocalCobranca")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var ValorDespesaLocalCobranca = nameTextNode.nodeValue;					
							
						switch (Local){
							case 'LocalCobrancaParametro':
								
								nameNode = xmlhttp.responseXML.getElementsByTagName("IdLocalCobrancaLayout")[0]; 
								nameTextNode = nameNode.childNodes[0];
								var IdLocalCobrancaLayout = nameTextNode.nodeValue;
							
								document.formulario.IdLocalCobranca.value				= IdLocalCobranca;
								document.formulario.DescricaoLocalCobranca.value 		= DescricaoLocalCobranca;
								document.formulario.AbreviacaoNomeLocalCobranca.value 	= AbreviacaoNomeLocalCobranca;
								document.formulario.IdLocalCobrancaLayout.value			= IdLocalCobrancaLayout;
								busca_local_cobraca_layout_parametro(IdLocalCobrancaLayout,false);													
								break;
							case 'LocalCobrancaParametroContrato':
								document.formulario.IdLocalCobranca.value				= IdLocalCobranca;
								document.formulario.DescricaoLocalCobranca.value 		= DescricaoLocalCobranca;
								document.formulario.AbreviacaoNomeLocalCobranca.value 	= AbreviacaoNomeLocalCobranca;
								listarParametro(IdLocalCobranca,false);
								break;
							case 'ContaEventual':
								document.formulario.ValorDespesaLocalCobranca.value 	= formata_float(Arredonda(ValorDespesaLocalCobranca,2),2).replace(".",",");
								busca_valor_minimo(IdLocalCobranca);
								break;
							case 'OrdemServico':
								document.formulario.ValorDespesaLocalCobranca.value 	= formata_float(Arredonda(ValorDespesaLocalCobranca,2),2).replace(".",",");
								busca_valor_minimo(IdLocalCobranca);
								break;
							case 'ProcessoFinanceiro':							
									
								nameNode = xmlhttp.responseXML.getElementsByTagName("IdNotaFiscalTipo")[0]; 
								nameTextNode = nameNode.childNodes[0];
								var IdNotaFiscalTipo = nameTextNode.nodeValue;
								
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
								break;
						}
					}
					if(document.getElementById("quadroBuscaLocalCobranca") != null){
						if(document.getElementById("quadroBuscaLocalCobranca").style.display	==	"block"){
							document.getElementById("quadroBuscaLocalCobranca").style.display = "none";
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
