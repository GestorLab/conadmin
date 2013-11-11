	function janela_busca_contrato(){
		janelas('../administrativo/busca_contrato.php',530,350,250,100,'');
	}
	function janela_busca_contrato2(){
		janelas('../administrativo/busca_contrato2.php',530,350,250,100,'');
	}
	function busca_contrato(IdContrato,Erro,Local,IdPessoa,ListarCampo){
		if(IdContrato == '' || IdContrato == undefined){
			IdContrato = 0;
		}
		
		if(Local == '' || Local == undefined){
			Local = document.formulario.Local.value;
		}
		
		if(Local == 'OrdemServico' && document.formulario.IdTipoOrdemServico.value != 2){
			if(document.formulario.IdPessoa != ''){
				IdPessoa = document.formulario.IdPessoa.value;
			}else{
				IdPessoa = document.formulario.IdPessoaF.value;
			}
			if(document.formulario.IdPessoa.value == '' && document.formulario.IdPessoaF.value == ''){
				document.formulario.IdPessoa.focus();
				mensagens(104);
				return false;
			}
		}
		
		if(IdPessoa == '' || IdPessoa == undefined){
			IdPessoa = '';
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
	    if(IdPessoa != '' && IdPessoa != undefined){
		   	url = "xml/contrato.php?IdContrato="+IdContrato+"&IdPessoa="+IdPessoa;
		}else{
		   	url = "xml/contrato.php?IdContrato="+IdContrato;
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
					//alert(xmlhttp.responseText);
					if(xmlhttp.responseText == 'false'){		
						
						if(Local!='Agendamento' && Local!= 'ProcessoFinanceiro' && Local != 'LancamentoFinanceiro'){
							addParmUrl("marContrato","IdContrato","");
							addParmUrl("marContasReceber","IdContrato","");
							addParmUrl("marLancamentoFinanceiro","IdContrato","");
							addParmUrl("marProcessoFinanceiro","IdContrato","");
							addParmUrl("marProcessoFinanceiroNovo","IdContrato","");
							addParmUrl("marOrdemServicoNovo","IdContrato","");
							addParmUrl("marOrdemServico","IdContrato","");
							addParmUrl("marVigencia","IdContrato","");
							addParmUrl("marVigenciaNovo","IdContrato","");
						}
						
						switch(Local){
							case 'ContratoAtivar':
								document.formulario.IdContrato.value				=	"";
								document.formulario.IdServico.value					=	"";
								document.formulario.DescricaoServico.value			=	"";
								document.formulario.DescPeriodicidade.value			=	"";
								document.formulario.QtdParcela.value				=	"";
								document.formulario.DataInicio.value				=	"";
								document.formulario.DataTermino.value				=	"";
								document.formulario.DataBaseCalculo.value			=	"";
								document.formulario.DataUltimaCobranca.value		=	"";
								document.formulario.DataPrimeiraCobranca.value		=	"";
								document.formulario.AssinaturaContrato.value		=	"";
								document.formulario.TipoContrato[0].selected		=	true;
								document.formulario.DataAtivacaoInicio.value		=	"";
								document.formulario.DataAtivacaoFim.value			=	"";
								document.formulario.AgruparContrato.value			=	"";
								document.formulario.DataPrimeiroVenc.value			=	"";

								document.getElementById('cp_juridica').style.display			= 'block';
								document.getElementById('cp_fisica').style.display				= 'none';								
								document.getElementById('cp_CPF_CNPJ_Titulo').innerHTML 		= "CNPJ";

								document.formulario.IdPessoa.value 			= '';
								document.formulario.IdPessoaF.value 		= '';
								document.formulario.Nome.value 				= '';
								document.formulario.RazaoSocial.value 		= '';
								document.formulario.Cidade.value 			= '';
								document.formulario.CPF_CNPJ.value 			= '';
								document.formulario.Email.value 			= '';
								document.formulario.Telefone1.value			= '';
								document.formulario.SiglaEstado.value		= '';
								document.formulario.Acao.value 				= 'inserir';
								
								verificaAcao();
								
								break;
							case 'ContratoDataBase':
								document.formulario.IdContrato.value				=	"";
								document.formulario.IdContrato.value 				= '';
								document.formulario.IdServico.value					=	"";
								document.formulario.DescricaoServico.value			=	"";
								document.formulario.DescPeriodicidade.value			=	"";
								document.formulario.QtdParcela.value				=	"";
								document.formulario.DataInicio.value				=	"";
								document.formulario.DataTermino.value				=	"";
								document.formulario.DataBaseCalculo.value			=	"";
								document.formulario.DataUltimaCobranca.value		=	"";
								document.formulario.DataPrimeiraCobranca.value		=	"";
								document.formulario.AssinaturaContrato.value		=	"";
								document.formulario.TipoContrato[0].selected		=	true;
								
								document.getElementById('cp_juridica').style.display			= 'block';
								document.getElementById('cp_fisica').style.display				= 'none';								
								document.getElementById('cp_CPF_CNPJ_Titulo').innerHTML 		= "CNPJ";

								document.formulario.IdPessoa.value 			= '';
								document.formulario.IdPessoaF.value 		= '';
								document.formulario.Nome.value 				= '';
								document.formulario.RazaoSocial.value 		= '';
								document.formulario.Cidade.value 			= '';
								document.formulario.CPF_CNPJ.value 			= '';
								document.formulario.Email.value 			= '';
								document.formulario.Telefone1.value			= '';
								document.formulario.SiglaEstado.value		= '';
								document.formulario.Acao.value 				= 'inserir';
								
								verificaAcao();
								document.formulario.IdContrato.focus();
								break;
							case 'OrdemServico':
								document.formulario.IdContrato.value				=	"";
								document.formulario.IdContrato.value				=	"";
								document.formulario.IdServicoContrato.value			=	"";
								document.formulario.DescricaoServicoContrato.value	=	"";
								document.formulario.DescPeriodicidadeContrato.value	=	"";
								document.formulario.QtdParcelaContrato.value		=	"";
								document.formulario.DataInicio.value				=	"";
								document.formulario.DataTermino.value				=	"";
								document.formulario.DataBaseCalculo.value			=	"";
								document.formulario.DataUltimaCobranca.value		=	"";
								document.formulario.AssinaturaContrato.value		=	"";
								document.formulario.TipoContrato.value				=	"";
								
								if(document.formulario.IdOrdemServico.value == ""){
									document.formulario.IdLocalCobranca.value			=	"";
									document.formulario.IdLocalCobrancaTemp.value		=	"";
								}
								
								for(var i=0; i<document.formulario.TipoContrato.length; i++){
									if(document.formulario.TipoContrato[i].value == ''){
										document.formulario.TipoContrato[i].selected = true;
										i = document.formulario.TipoContrato.length;
									}							
								}
								if(document.formulario.IdOrdemServico.value == "" && document.formulario.IdTipoOrdemServico.value != 2){
									document.formulario.IdContrato.focus();
								}
								if(document.formulario.Local.value == 'OrdemServico'){
									document.getElementById('cp_parametrosContrato').style.display		=	'none';
								}
								
								busca_servico(document.formulario.IdServico.value);
								
								break;
							case 'Agendamento':
								document.formulario.IdContrato.value				=	"";
								document.formulario.IdContrato.value				=	"";
								document.formulario.IdServicoContrato.value			=	"";
								document.formulario.DescricaoServicoContrato.value	=	"";
								document.formulario.DescPeriodicidadeContrato.value	=	"";
								document.formulario.DataInicio.value				=	"";
								document.formulario.DataTermino.value				=	"";
								document.formulario.DataBaseCalculo.value			=	"";
								document.formulario.DataUltimaCobranca.value		=	"";
								document.formulario.AssinaturaContrato.value		=	"";
								document.formulario.TipoContrato.value				=	"";
								break;
							case 'Contrato':
								document.formulario.IdContrato.value				=	"";	
								document.formulario.IdPessoa.value					=	"";
								document.formulario.IdPessoaF.value 				= 	'';
								document.formulario.Nome.value						=	"";
								document.formulario.IdServico.readOnly				=	false;
								document.formulario.IdPessoa.readOnly				=	false;
								document.formulario.IdPessoaF.readOnly				=	false;
								document.formulario.IdServico.value					=	"";
								document.formulario.DescricaoServico.value			=	"";
								document.formulario.IdPeriodicidade.value			=	"";
								document.formulario.Periodicidade.value				=	"";
								document.formulario.QtdParcela.value				=	"";
								document.formulario.QuantParcela.value				=	"";
								document.formulario.DescricaoServico.value			=	"";
								document.formulario.DataInicio.value				=	"";
								document.formulario.DataTermino.value				=	"";
								document.formulario.DataBaseCalculo.value			=	"";
								document.formulario.DataPrimeiraCobranca.value		=	"";
								document.formulario.DataUltimaCobranca.value		=	"";
								document.formulario.AssinaturaContrato.value		=	"";
								document.formulario.IdLocalCobranca.value			=	"";
								document.formulario.IdLocalCobrancaTemp.value	 	= 	"";
								document.formulario.IdStatus.value 					= 	"";
								document.formulario.IdCarteira.disabled				=	true;
								document.formulario.DataTermino.readOnly			=	false;
								document.formulario.DataUltimaCobranca.readOnly		=	false;
								document.formulario.IdLocalCobranca.value			=	"";
								document.formulario.IdCarteira.value				=	"";
								document.formulario.IdAgenteAutorizado.value		=	"";
								document.formulario.NomeAgenteAutorizado.value		=	"";
								document.formulario.IdContratoAgrupador.value		=	"";
								document.formulario.IdContratoAgrupador.disabled	=	false;
								document.formulario.AdequarLeisOrgaoPublico.value	=	"";
								document.formulario.ValorServico.value				=	"";
								document.formulario.ValorRepasseTerceiro.value		=	"";
								document.formulario.ValorPeriodicidade.value		=	"";
								document.formulario.DataCriacao.value				=	"";
								document.formulario.LoginCriacao.value				=	"";
								document.formulario.DataAlteracao.value				=	"";
								document.formulario.LoginAlteracao.value			=	"";
								document.formulario.TipoContrato.value				=	"";
								document.formulario.TipoContratoTemp.value			=	"";
								document.formulario.Obs.value						=	"";
								document.formulario.UrlContratoImpresso.value		=	"";
								document.formulario.UrlDistratoImpresso.value		=	"";
								document.formulario.Redirecionar.value				=	"";
								document.formulario.MesFechado.value 				= 	"";
								document.formulario.MesFechadoTemp.value			=	"";
								document.formulario.ValorServico.readOnly			= 	false;
								document.formulario.ValorRepasseTerceiro.readOnly	= 	false;
								document.formulario.QtdMesesFidelidade.value		=	"";
								document.formulario.QtdMesesFidelidadeTemp.value	=	"";
								document.formulario.ServicoAutomatico.value			=	"";
								document.formulario.HistoricoObs.value				=	"";
								document.formulario.MultaFidelidade.value			=	"";
								document.formulario.ValorMultaFidelidade.value		=	"";
								document.formulario.IdTipoLocalCobranca.value		=	"";
								document.formulario.DiaCobranca.value				=	"";
								document.formulario.DiaCobrancaTemp.value			=	"";
								document.formulario.DiaCobranca.disabled			=	false;
								
								while(document.formulario.DiaCobranca.options.length > 0){
									document.formulario.DiaCobranca.options[0] = null;
								}
								
								document.getElementById('cp_automatico').innerHTML			=	"";
								document.getElementById('cp_automatico').style.display		=	'none';
								document.getElementById('cpStatusContrato').style.display	=	'none';
								document.getElementById('cpHistorico').style.display		=	'none';
								document.getElementById('cp_parametrosServico').style.display		=	'none';
								document.getElementById('cp_parametrosLocalCobranca').style.display	=	'none';
								
								status_inicial();
								
								document.getElementById('cp_juridica').style.display		= 'block';
								document.getElementById('cp_fisica').style.display			= 'none';
								document.getElementById('helpText2').innerHTML 				= '';
								document.getElementById('helpText2').style.display 			= "none";
								document.getElementById('titMudarServico').style.display 	= "none";
								document.formulario.bt_carta.style.display					= 'none';
							
								document.formulario.Nome.value 				= '';
								document.formulario.RazaoSocial.value 		= '';
								document.formulario.Cidade.value 			= '';
								document.formulario.CPF_CNPJ.value 			= '';
								document.formulario.Email.value 			= '';
								document.formulario.Telefone1.value			= '';
								document.formulario.SiglaEstado.value		= '';
								
								while(document.getElementById('tabelaParametro').rows.length > 0){
									document.getElementById('tabelaParametro').deleteRow(0);
								}
								while(document.getElementById('tabelaParametroLocalCobranca').rows.length > 0){
									document.getElementById('tabelaParametroLocalCobranca').deleteRow(0);
								}
								
								document.getElementById('cp_CPF_CNPJ_Titulo').innerHTML 		= "CNPJ";
								document.getElementById('cp_parametrosServico').style.display	= 'none';
								
								document.getElementById('DataInicio').style.backgroundColor = '#FFFFFF';
								document.getElementById('DataInicio').style.color = '#C10000';
										
								document.getElementById('DataUltimaCobranca').style.backgroundColor = '#FFFFFF';
								document.getElementById('DataUltimaCobranca').style.color = '#000000';
								
								document.getElementById('BtImprimir').style.display	=	'none';
								
								document.getElementById('imgDataTermino').style.display				=	'block';
								document.getElementById('imgDataUltimaCobranca').style.display		=	'block';
								
								document.getElementById('imgDataTerminoDisab').style.display			=	'none';
								document.getElementById('imgDataUltimaCobrancaDisab').style.display		=	'none';
								
								document.formulario.IdContrato.focus();
								document.formulario.Acao.value 				= 'inserir';

								busca_dia_cobranca('',document.formulario.DiaCobrancaDefault.value)
								verificaAcao();
								break;
							case 'ContratoServico':
								document.formulario.IdContrato.value					=	"";
								document.formulario.IdContratoAnterior.value			=	'';
								document.formulario.IdPessoa.value						=	'';
								document.formulario.IdPessoaF.value 					= '';
								document.formulario.IdServicoAnterior.value				=	'';
								document.formulario.DescricaoServicoAnterior.value		=	'';
								document.formulario.DescPeriodicidadeAnterior.value		=	'';
								document.formulario.QtdParcelaAnterior.value			=	'';
								document.formulario.ValorServicoAnterior.value			=	'';
								document.formulario.TipoContratoAnterior.value			=	'';
								document.formulario.LocalCobrancaAnterior.value			=	'';
								document.formulario.MesFechadoAnterior.value			=	'';
								document.formulario.ValorPeriodicidadeAnterior.value	=	'';
								document.formulario.ValorRepasseTerceiroAnterior.value	=	'';
								document.formulario.QtdMesesFidelidadeAnterior.value	=	'';
								document.formulario.ValorMultaFidelidadeAnterior.value	=	'';
								document.formulario.DataInicio.value					=	"";
								document.formulario.DataPrimeiraCobranca.value			=	"";
								document.formulario.DataBaseCalculo.value				=	"";
								document.formulario.DataTermino.value					=	"";
								document.formulario.DataUltimaCobranca.value			=	"";
								document.formulario.AssinaturaContrato.value			=	"";
								document.formulario.QuantParametros.value				=	"";
								document.formulario.QuantParametrosLocalCobranca.value	=	"";
								document.formulario.Periodicidade.value					=	"";
								document.formulario.ParametrosAnterior.value			=	"";								
								document.formulario.QuantParcela.value					=	"";
								document.formulario.ServicoAutomatico.value				=	"";
								document.formulario.MultaFidelidade.value				=	"";
								document.formulario.IdStatus.value						=	"";
								document.formulario.TipoContratoTemp.value				=	"";
								document.formulario.MesFechadoTemp.value				=	"";								
								document.formulario.IdLocalCobrancaTemp.value			=	"";							
								document.formulario.IdTipoServico.value					=	"";
								document.formulario.IdAgenteAutorizadoAnterior.value	=	"";								
								document.formulario.NomeAgenteAutorizadoAnterior.value	=	"";							
								document.formulario.IdCarteiraAnterior.value			=	"";
								document.formulario.IdCarteira.disabled					=	false;
								
								document.getElementById('cp_juridica').style.display			= 'block';
								document.getElementById('cp_fisica').style.display				= 'none';
								document.getElementById('helpText2').innerHTML 					=  '';
								document.getElementById('helpText2').style.display 				= "none";
								document.getElementById('titMudarServico').style.display 		= "none";
								document.getElementById('cp_CPF_CNPJ_Titulo').innerHTML 		= "CNPJ";
								document.getElementById('cp_parametrosServico').style.display	= 'none';
							
								document.formulario.Nome.value 				= '';
								document.formulario.RazaoSocial.value 		= '';
								document.formulario.Cidade.value 			= '';
								document.formulario.CPF_CNPJ.value 			= '';
								document.formulario.Email.value 			= '';
								document.formulario.Telefone1.value			= '';
								document.formulario.SiglaEstado.value		= '';
								document.formulario.Acao.value 				= 'inserir';
								
								limpaFormCredito();
								
								document.formulario.IdContrato.focus();
								document.formulario.Acao.value 				= 'alterar';
								
								scrollWindow('top');							
								break;
							case 'ContratoStatus':
								document.formulario.IdContrato.value				=	'';
								document.formulario.IdPessoa.value					=	'';
								document.formulario.IdServico.value					=	'';
								document.formulario.DescricaoServico.value			=	'';
								document.formulario.DescPeriodicidade.value			=	'';
								document.formulario.QtdParcela.value				=	'';
								document.formulario.ValorServico.value				=	'';
								document.formulario.IdStatusAnterior.value			=	'';
								document.formulario.IdStatus.value					=	'';
								document.formulario.VarStatus.value					=	'';
								document.formulario.VarStatusAnterior.value			=	'';
								
								document.getElementById('cp_juridica').style.display			= 'block';
								document.getElementById('cp_fisica').style.display				= 'none';
								document.getElementById('helpText2').innerHTML 					=  '';
								document.getElementById('helpText2').style.display 				= "none";
								document.getElementById('cp_CPF_CNPJ_Titulo').innerHTML 		= "CNPJ";
							
								document.formulario.IdPessoaF.value 		= '';
								document.formulario.Nome.value 				= '';
								document.formulario.RazaoSocial.value 		= '';
								document.formulario.Cidade.value 			= '';
								document.formulario.CPF_CNPJ.value 			= '';
								document.formulario.Email.value 			= '';
								document.formulario.Telefone1.value			= '';
								document.formulario.SiglaEstado.value		= '';
								
								document.formulario.IdContrato.focus();
								document.formulario.Acao.value 				= 'alterar';
								
								scrollWindow('top');
								verificaAcao();							
								break;
							case "Vigencia":
								document.formulario.IdContrato.value			=	"";
								document.formulario.IdServico.value				=	"";
								document.formulario.DescricaoServico.value		=	"";
								document.formulario.DescPeriodicidade.value		=	"";
								document.formulario.QtdParcela.value			=	"";
								document.formulario.DataInicio.value			=	"";
								document.formulario.DataTermino.value			=	"";
								document.formulario.DataBaseCalculo.value		=	"";
								document.formulario.DataUltimaCobranca.value	=	"";
								document.formulario.AssinaturaContrato.value	=	"";
								document.formulario.TipoContrato.value			=	"";
								document.formulario.Valor.value					=	"";
								
								busca_pessoa(document.formulario.IdPessoa.value,false);
								listarVigencia(document.formulario.IdContrato.value,false);
								break;
							case "LancamentoFinanceiro":
								document.formulario.IdContrato.value			=	"";
								document.formulario.IdServico.value				=	"";
								document.formulario.DescricaoServico.value		=	"";
								document.formulario.DescPeriodicidade.value		=	"";
								document.formulario.QtdParcela.value			=	"";
								document.formulario.DataInicio.value			=	"";
								document.formulario.DataTermino.value			=	"";
								document.formulario.DataBaseCalculo.value		=	"";
								document.formulario.DataUltimaCobranca.value	=	"";
								document.formulario.AssinaturaContrato.value	=	"";
								document.formulario.TipoContrato.value			=	"";
								
								
//								document.getElementById('cpLancamentoFinanceiro').style.display				= 'none';
								break;
							case 'CancelarContrato':	
								document.formulario.IdContrato.value				=	"";
								document.formulario.IdPessoa.value					=	"";
								document.formulario.IdPessoaF.value 				= '';
								document.formulario.Nome.value						=	"";
								document.formulario.RazaoSocial.value 				= '';
								document.formulario.Cidade.value 					= '';
								document.formulario.CPF_CNPJ.value 					= '';
								document.formulario.Email.value 					= '';
								document.formulario.Telefone1.value					= '';
								document.formulario.SiglaEstado.value				= '';
								document.formulario.IdServico.value					=	"";
								document.formulario.DescricaoServico.value			=	"";
								document.formulario.DescPeriodicidade.value			=	"";
								document.formulario.QtdParcela.value				=	"";
								document.formulario.DataInicio.value				=	"";
								document.formulario.DataTermino.value				=	"";
								document.formulario.DataBaseCalculo.value			=	"";
								document.formulario.DataPrimeiraCobranca.value		=	"";
								document.formulario.DataUltimaCobranca.value		=	"";
								document.formulario.AssinaturaContrato.value		=	"";
								document.formulario.IdLocalCobranca[0].selected 	= 	true;
								document.formulario.IdLocalCobranca.value			=	"";
								document.formulario.IdCarteira.value				=	"";
								document.formulario.IdAgenteAutorizado.value		=	"";
								document.formulario.NomeAgenteAutorizado.value		=	"";
								document.formulario.IdContratoAgrupador.value		=	"";
								document.formulario.IdContratoAgrupador.disabled	=	false;
								document.formulario.AdequarLeisOrgaoPublico.value	=	"";
								document.formulario.ValorServico.value				=	"";
								document.formulario.ValorPeriodicidade.value		=	"";
								document.formulario.DataCriacao.value				=	"";
								document.formulario.LoginCriacao.value				=	"";
								document.formulario.DataAlteracao.value				=	"";
								document.formulario.LoginAlteracao.value			=	"";
								document.formulario.TipoContrato.value				=	"";
								document.formulario.Obs.value						=	"";
								
								document.getElementById('cp_juridica').style.display			= 'block';
								document.getElementById('cp_fisica').style.display				= 'none';
								
								document.getElementById('cp_CPF_CNPJ_Titulo').innerHTML 		= "CNPJ";
								
								document.formulario.IdContrato.focus();
								document.formulario.bt_confirmar.disabled 	= true;
								
								break;
						}
						
						// Fim de Carregando
						carregando(false);
					}else{
						nameNode = xmlhttp.responseXML.getElementsByTagName("IdContrato")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var IdContrato = nameTextNode.nodeValue;		
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("IdPessoa")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var IdPessoa = nameTextNode.nodeValue;	
					
						nameNode = xmlhttp.responseXML.getElementsByTagName("IdServico")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var IdServico = nameTextNode.nodeValue;	
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoServico")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var DescricaoServico = nameTextNode.nodeValue;	
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("IdPeriodicidade")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var IdPeriodicidade = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("DescPeriodicidade")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var DescPeriodicidade = nameTextNode.nodeValue;	
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("QtdParcela")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var QtdParcela = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("DataInicio")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var DataInicio = nameTextNode.nodeValue;	
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("DataTermino")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var DataTermino = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("DataBaseCalculo")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var DataBaseCalculo = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("DataUltimaCobranca")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var DataUltimaCobranca = nameTextNode.nodeValue;					
				
						nameNode = xmlhttp.responseXML.getElementsByTagName("DataPrimeiraCobranca")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var DataPrimeiraCobranca = nameTextNode.nodeValue;					
				
						nameNode = xmlhttp.responseXML.getElementsByTagName("AssinaturaContrato")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var AssinaturaContrato = nameTextNode.nodeValue;
					
						nameNode = xmlhttp.responseXML.getElementsByTagName("TipoContrato")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var TipoContrato = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("MesFechado")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var MesFechado = nameTextNode.nodeValue;
							
						nameNode = xmlhttp.responseXML.getElementsByTagName("IdAgenteAutorizado")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var IdAgenteAutorizado = nameTextNode.nodeValue;	
					
						nameNode = xmlhttp.responseXML.getElementsByTagName("NomeAgenteAutorizado")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var NomeAgenteAutorizado = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("IdCarteira")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var IdCarteira = nameTextNode.nodeValue;	
					
						nameNode = xmlhttp.responseXML.getElementsByTagName("IdContratoAgrupador")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var IdContratoAgrupador = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("IdLocalCobranca")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var IdLocalCobranca = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("IdTipoLocalCobranca")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var IdTipoLocalCobranca = nameTextNode.nodeValue;	
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("AdequarLeisOrgaoPublico")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var AdequarLeisOrgaoPublico = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("Obs")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var Obs = nameTextNode.nodeValue;
						
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
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("UrlContratoImpresso")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var UrlContratoImpresso = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("UrlDistratoImpresso")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var UrlDistratoImpresso = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("Valor")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var Valor = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("IdStatus")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var IdStatus = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("VarStatus")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var VarStatus = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("ValorRepasseTerceiro")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var ValorRepasseTerceiro = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("DadosAtivacao")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var DadosAtivacao = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("DataReferenciaFinal")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var DataReferenciaFinal = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("QtdMesesFidelidade")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var QtdMesesFidelidade = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("MultaFidelidade")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var MultaFidelidade = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("DiaCobranca")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var DiaCobranca = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("IdContratoPai")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var IdContratoPai = nameTextNode.nodeValue;
						
						
						if(IdContratoPai != ""){
							busca_contrato(IdContratoPai);
						}else{
							
							if(Local!='Agendamento' && Local!= 'ProcessoFinanceiro' && Local != 'LancamentoFinanceiro'){
								//addParmUrl("marContrato","IdContrato",IdContrato);
								addParmUrl("marContrato","IdPessoa",IdPessoa);
								addParmUrl("marContratoNovo","IdPessoa",IdPessoa);
								addParmUrl("marContasReceber","IdContrato",IdContrato);
								addParmUrl("marLancamentoFinanceiro","IdContrato",IdContrato);
								addParmUrl("marProcessoFinanceiro","IdContrato",IdContrato);
								addParmUrl("marProcessoFinanceiroNovo","IdContrato",IdContrato);
								addParmUrl("marReenvioEmail","IdPessoa",IdPessoa);
								addParmUrl("marContaEventual","IdPessoa",IdPessoa);
								addParmUrl("marContaEventualNovo","IdPessoa",IdPessoa);
								addParmUrl("marPessoa","IdPessoa",IdPessoa);
								addParmUrl("marOrdemServico","IdPessoa",IdPessoa);
								addParmUrl("marOrdemServicoNovo","IdContrato",IdContrato);
								addParmUrl("marOrdemServicoNovo","IdPessoa",IdPessoa);
								addParmUrl("marOrdemServico","IdContrato",IdContrato);
								addParmUrl("marVigencia","IdContrato",IdContrato);
								addParmUrl("marVigenciaNovo","IdContrato",IdContrato);
								addParmUrl("marVigenciaNovo","IdPessoa",IdPessoa);
							}
							
							switch(Local){
								case 'ContratoAtivar':
									nameNode = xmlhttp.responseXML.getElementsByTagName("DescPeriodicidade")[0]; 
									nameTextNode = nameNode.childNodes[0];
									var DescPeriodicidade = nameTextNode.nodeValue;
									
									document.formulario.IdContrato.value				=	IdContrato;
									document.formulario.IdServico.value					=	IdServico;
									document.formulario.DescricaoServico.value			=	DescricaoServico;
									document.formulario.DescPeriodicidade.value			=	DescPeriodicidade;
									document.formulario.QtdParcela.value				=	QtdParcela;
									document.formulario.DataInicio.value				=	dateFormat(DataInicio);
									document.formulario.DataTermino.value				=	dateFormat(DataTermino);
									document.formulario.DataBaseCalculo.value			=	dateFormat(DataBaseCalculo);
									document.formulario.DataUltimaCobranca.value		=	dateFormat(DataUltimaCobranca);
									document.formulario.AssinaturaContrato.value		=	AssinaturaContrato;
									document.formulario.TipoContrato.value				=	TipoContrato;
									document.formulario.DataPrimeiraCobranca.value		=	dateFormat(DataPrimeiraCobranca);
									document.formulario.DataAtivacaoInicio.value		=	dateFormat(DataPrimeiraCobranca);
									document.formulario.DataAtivacaoFim.value			=	dateFormat(DataReferenciaFinal);
									document.formulario.DataPrimeiroVenc.value			=	"";
									document.formulario.Acao.value						=	'confirmar';
	
									busca_pessoa(IdPessoa,false,document.formulario.Local.value);
									status_inicial();
	
									verificaAcao();
									break;
								case 'ContratoDataBase':
									nameNode = xmlhttp.responseXML.getElementsByTagName("DescPeriodicidade")[0]; 
									nameTextNode = nameNode.childNodes[0];
									var DescPeriodicidade = nameTextNode.nodeValue;
									
									document.formulario.IdContrato.value				=	IdContrato;
									document.formulario.IdServico.value					=	IdServico;
									document.formulario.DescricaoServico.value			=	DescricaoServico;
									document.formulario.DescPeriodicidade.value			=	DescPeriodicidade;
									document.formulario.QtdParcela.value				=	QtdParcela;
									document.formulario.DataInicio.value				=	dateFormat(DataInicio);
									document.formulario.DataTermino.value				=	dateFormat(DataTermino);
									document.formulario.DataBaseCalculo.value			=	dateFormat(DataBaseCalculo);
									document.formulario.DataUltimaCobranca.value		=	dateFormat(DataUltimaCobranca);
									document.formulario.AssinaturaContrato.value		=	AssinaturaContrato;
									document.formulario.TipoContrato.value				=	TipoContrato;
									document.formulario.DataPrimeiraCobranca.value		=	dateFormat(DataPrimeiraCobranca);
									document.formulario.Acao.value						=	'alterar';
	
									busca_pessoa(IdPessoa,false,document.formulario.Local.value);
	
									verificaAcao();
									
									break;
								case 'OrdemServico':
									nameNode = xmlhttp.responseXML.getElementsByTagName("DescPeriodicidade")[0]; 
									nameTextNode = nameNode.childNodes[0];
									var DescPeriodicidade = nameTextNode.nodeValue;
									
									document.formulario.IdContrato.value				=	IdContrato;
									document.formulario.IdServicoContrato.value			=	IdServico;
									document.formulario.DescricaoServicoContrato.value	=	DescricaoServico;
									document.formulario.DescPeriodicidadeContrato.value	=	DescPeriodicidade;
									document.formulario.QtdParcelaContrato.value		=	QtdParcela;
									document.formulario.DataInicio.value				=	dateFormat(DataInicio);
									document.formulario.DataTermino.value				=	dateFormat(DataTermino);
									document.formulario.DataBaseCalculo.value			=	dateFormat(DataBaseCalculo);
									document.formulario.DataUltimaCobranca.value		=	dateFormat(DataUltimaCobranca);
									document.formulario.AssinaturaContrato.value		=	AssinaturaContrato;
									document.formulario.TipoContrato.value				=	TipoContrato;
									
									if(document.formulario.IdLocalCobranca.value == ""){
										seleciona_local_cobranca(document.formulario.IdPessoa.value,IdLocalCobranca);
										document.formulario.IdLocalCobrancaTemp.value		=	IdLocalCobranca;
									}
									if(document.formulario.Local.value == 'OrdemServico'){
										listarParametroContrato(IdServico,false,IdContrato);
									}
									
									busca_servico(document.formulario.IdServico.value);
									
									break;
								case 'Agendamento':
									document.formulario.IdContrato.value				=	IdContrato;
									document.formulario.IdServicoContrato.value			=	IdServico;
									document.formulario.DescricaoServicoContrato.value	=	DescricaoServico;
									document.formulario.DescPeriodicidadeContrato.value	=	DescPeriodicidade;
									document.formulario.DataInicio.value				=	dateFormat(DataInicio);
									document.formulario.DataTermino.value				=	dateFormat(DataTermino);
									document.formulario.DataBaseCalculo.value			=	dateFormat(DataBaseCalculo);
									document.formulario.DataUltimaCobranca.value		=	dateFormat(DataUltimaCobranca);
									document.formulario.AssinaturaContrato.value		=	AssinaturaContrato;
									document.formulario.TipoContrato.value				=	TipoContrato;
									break;
								case 'Contrato':			
									
									nameNode = xmlhttp.responseXML.getElementsByTagName("QtdLancamentos")[0]; 
									nameTextNode = nameNode.childNodes[0];
									var QtdLancamentos = nameTextNode.nodeValue;
									
									nameNode = xmlhttp.responseXML.getElementsByTagName("ServicoAutomatico")[0]; 
									nameTextNode = nameNode.childNodes[0];
									var ServicoAutomatico = nameTextNode.nodeValue;
									
									nameNode = xmlhttp.responseXML.getElementsByTagName("IdContratoPai")[0]; 
									nameTextNode = nameNode.childNodes[0];
									var IdContratoPai = nameTextNode.nodeValue;
									
									if(DadosAtivacao == 1 && document.formulario.Redirecionar.value !='N'){
										window.location.replace('cadastro_contrato_ativar.php?IdContrato='+IdContrato);
									}
									
									while(document.formulario.IdPeriodicidade.options.length > 0){
										document.formulario.IdPeriodicidade.options[0] = null;
									}
									while(document.formulario.QtdParcela.options.length > 0){
										document.formulario.QtdParcela.options[0] = null;
									}
									while(document.formulario.TipoContrato.options.length > 0){
										document.formulario.TipoContrato.options[0] = null;
									}
									while(document.formulario.IdLocalCobranca.options.length > 0){
										document.formulario.IdLocalCobranca.options[0] = null;
									}
									while(document.formulario.MesFechado.options.length > 0){
										document.formulario.MesFechado.options[0] = null;
									}	
									document.formulario.QtdMesesFidelidade.value		=	"";	
									
									document.formulario.IdContrato.value				=	IdContrato;
									document.formulario.IdPessoa.value					=	IdPessoa;
									document.formulario.IdServico.value					=	IdServico;
									document.formulario.DescricaoServico.value			=	DescricaoServico;
									document.formulario.IdPeriodicidade.value			=	IdPeriodicidade;
									document.formulario.IdServico.readOnly				=	true;
									document.formulario.IdPessoa.readOnly				=	true;
									document.formulario.IdPessoaF.readOnly				=	true;
									document.formulario.DataInicio.value				=	dateFormat(DataInicio);
									document.formulario.DataTermino.value				=	dateFormat(DataTermino);
									document.formulario.DataBaseCalculo.value			=	dateFormat(DataBaseCalculo);
									document.formulario.DataPrimeiraCobranca.value		=	dateFormat(DataPrimeiraCobranca);
									document.formulario.DataUltimaCobranca.value		=	dateFormat(DataUltimaCobranca);
									document.formulario.AssinaturaContrato.value		=	AssinaturaContrato;
									document.formulario.IdAgenteAutorizado.value		=	IdAgenteAutorizado;
									document.formulario.NomeAgenteAutorizado.value		=	NomeAgenteAutorizado;
									document.formulario.IdContratoAgrupador.value		=	IdContratoAgrupador;
									document.formulario.IdContratoAgrupador.disabled	=	false;
									document.formulario.AdequarLeisOrgaoPublico.value	=	AdequarLeisOrgaoPublico;
									document.formulario.IdLocalCobrancaTemp.value		=	IdLocalCobranca;
									document.formulario.IdTipoLocalCobranca.value		=	IdTipoLocalCobranca;
									document.formulario.TipoContratoTemp.value			=	TipoContrato;
									document.formulario.MesFechadoTemp.value			=	MesFechado;
									document.formulario.QtdMesesFidelidadeTemp.value	=	QtdMesesFidelidade;
									document.formulario.DiaCobranca.value				=	DiaCobranca;
									document.formulario.DiaCobrancaTemp.value			=	DiaCobranca;
									document.formulario.DiaCobranca.disabled			=	false;
									document.formulario.Obs.value						=	"";
									document.formulario.HistoricoObs.value				=	Obs;
									document.formulario.ValorServico.value				=	formata_float(Arredonda(Valor,2),2).replace('.',',');
									document.formulario.ValorRepasseTerceiro.value		=	formata_float(Arredonda(ValorRepasseTerceiro,2),2).replace('.',',');
									document.formulario.ValorServico.readOnly			= 	true;
									document.formulario.ValorRepasseTerceiro.readOnly	= 	true;
									document.formulario.DataCriacao.value				=	dateFormat(DataCriacao);
									document.formulario.LoginCriacao.value				=	LoginCriacao;
									document.formulario.DataAlteracao.value				=	dateFormat(DataAlteracao);
									document.formulario.LoginAlteracao.value			=	LoginAlteracao;
									document.formulario.UrlContratoImpresso.value		=	UrlContratoImpresso;
									document.formulario.UrlDistratoImpresso.value		=	UrlDistratoImpresso;
									document.formulario.IdStatus.value					=	IdStatus;
									document.formulario.QuantParcela.value				=	QtdParcela;
									document.formulario.Periodicidade.value				=	IdPeriodicidade;
									document.formulario.ServicoAutomatico.value			=	ServicoAutomatico;
									document.formulario.Acao.value 						= 	'alterar';
									
									if(document.formulario.IdAgenteAutorizado.value != ''){
										document.formulario.IdCarteira.disabled				=	false;
									}else{
										document.formulario.IdCarteira.disabled				=	true;
									}
									
									if(ServicoAutomatico != ''){
										document.getElementById('cp_automatico').innerHTML			=	"";
										busca_automatico(document.formulario.ServicoAutomatico.value);
									}else{
										document.getElementById('cp_automatico').innerHTML			=	"";
										document.getElementById('cp_automatico').style.display		=	'none';
									}
									
									while(document.formulario.IdPeriodicidade.options.length > 1){
										document.formulario.IdPeriodicidade.options[1] = null;
									}
									while(document.formulario.QtdParcela.options.length > 1){
										document.formulario.QtdParcela.options[1] = null;
									}
									while(document.formulario.TipoContrato.options.length > 1){
										document.formulario.TipoContrato.options[1] = null;
									}
									while(document.formulario.IdLocalCobranca.options.length > 1){
										document.formulario.IdLocalCobranca.options[1] = null;
									}
									while(document.formulario.MesFechado.options.length > 1){
										document.formulario.MesFechado.options[1] = null;
									}
									
									if(Obs != ""){
										document.getElementById('cpHistorico').style.display		=	'block';
									}else{
										document.getElementById('cpHistorico').style.display		=	'none';
									}
									
									document.getElementById('cpStatusContrato').style.display			=	'none';								
									document.getElementById('cp_parametrosServico').style.display		=	'none';								
									document.getElementById('cp_parametrosLocalCobranca').style.display	=	'none';	
									
									listarParametro(document.formulario.IdServico.value,false,document.formulario.IdContrato.value);
									listarParametroLocalCobranca(IdLocalCobranca,false,document.formulario.IdContrato.value);
									busca_pessoa(IdPessoa,false,document.formulario.Local.value);
									periodicidade(IdServico);
									listar_carteira(IdAgenteAutorizado,IdCarteira);
									listar_contrato_agrupador(IdPessoa,IdContratoAgrupador,IdContrato);
									busca_status(IdStatus,VarStatus);
									calculaPeriodicidade(IdPeriodicidade,document.formulario.ValorServico.value);
									
									document.formulario.IdCarteira.value				=	IdCarteira;
									document.formulario.QtdMesesFidelidade.value		=	QtdMesesFidelidade;
									document.formulario.MultaFidelidade.value			=	MultaFidelidade;
									document.formulario.ValorMultaFidelidade.value		=	formata_float(Arredonda(MultaFidelidade,2),2).replace('.',',');
									
									document.getElementById('DataInicio').style.backgroundColor = '#FFFFFF';
									document.getElementById('DataInicio').style.color = '#C10000';
												
									document.getElementById('DataPrimeiraCobranca').style.backgroundColor = '#FFFFFF';
									document.getElementById('DataPrimeiraCobranca').style.color = '#C10000';
									
									document.getElementById('DataTermino').style.backgroundColor = '#FFFFFF';
									document.getElementById('DataTermino').style.color = '#000000';
									
									document.getElementById('titMudarServico').style.display 	= "block";
									document.getElementById('BtImprimir').style.display 		= "block";
				
									document.getElementById('DataUltimaCobranca').style.backgroundColor = '#FFFFFF';
									document.getElementById('DataUltimaCobranca').style.color = '#000000';
												
									if(UrlContratoImpresso !=''){
										document.formulario.bt_imprimir.disabled	=	false;
									}else{
										document.formulario.bt_imprimir.disabled	=	true;
									}
									
									// Status == Cancelado
									if(IdStatus == 1){
										if(UrlDistratoImpresso != ""){
											document.formulario.bt_imprimir.disabled	=	false;
										}else{
											document.formulario.bt_imprimir.disabled	=	true;
										}
									}
									
									if(IdContratoAgrupador  != ""){
										document.formulario.DiaCobranca.disabled	=	true;
									}
									
									var hoje = new Date();
	
									var dia = hoje.getDate();
									var mes = (hoje.getMonth())+1; 
									var ano = hoje.getFullYear();
			
									if(dia < 10) dia = "0" + dia;	
									if(mes < 10) mes = "0" + mes;
			
									hoje = ano+"-"+mes+"-"+dia;
									
									if(QtdLancamentos == 0 || QtdLancamentos == ''){
										document.formulario.bt_fatura.disabled		=	false;
									}else{
										document.formulario.bt_fatura.disabled		=	true;
									}
									
									if(document.formulario.IdTipoLocalCobranca.value == 3){
										document.formulario.bt_carta.style.display		=	'block';
									}else{
										document.formulario.bt_carta.style.display		=	'none';
									}
									
									document.formulario.IdContrato.focus();
									
									verificaAcao();
									scrollWindow('top');
																
									break;
								case 'ContratoServico':
									nameNode = xmlhttp.responseXML.getElementsByTagName("Valor")[0]; 
									nameTextNode = nameNode.childNodes[0];
									var Valor = nameTextNode.nodeValue;
									
									nameNode = xmlhttp.responseXML.getElementsByTagName("DescPeriodicidade")[0]; 
									nameTextNode = nameNode.childNodes[0];
									var DescPeriodicidade = nameTextNode.nodeValue;
									
									nameNode = xmlhttp.responseXML.getElementsByTagName("DescTipoContrato")[0]; 
									nameTextNode = nameNode.childNodes[0];
									var DescTipoContrato = nameTextNode.nodeValue;
									
									nameNode = xmlhttp.responseXML.getElementsByTagName("DescMesFechado")[0]; 
									nameTextNode = nameNode.childNodes[0];
									var DescMesFechado = nameTextNode.nodeValue;
									
									nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoLocalCobranca")[0]; 
									nameTextNode = nameNode.childNodes[0];
									var DescricaoLocalCobranca = nameTextNode.nodeValue;
									
									if(Valor == '')	Valor = 0;
									
									document.formulario.ParametrosAnterior.value			=	"";
									document.formulario.IdContrato.value					=	IdContrato;
									document.formulario.IdContratoAnterior.value			=	IdContrato;
									document.formulario.IdPessoa.value						=	IdPessoa;
									document.formulario.IdServicoAnterior.value				=	IdServico;
									document.formulario.DescricaoServicoAnterior.value		=	DescricaoServico;
									document.formulario.DescPeriodicidadeAnterior.value		=	DescPeriodicidade;
									document.formulario.QtdParcelaAnterior.value			=	QtdParcela;
									document.formulario.ValorServicoAnterior.value			=	formata_float(Arredonda(Valor,2),2).replace('.',',');
									document.formulario.TipoContratoAnterior.value			=	DescTipoContrato;
									document.formulario.MesFechadoAnterior.value			=	DescMesFechado;
									document.formulario.LocalCobrancaAnterior.value			=	DescricaoLocalCobranca;
									document.formulario.ValorRepasseTerceiroAnterior.value	=	formata_float(Arredonda(ValorRepasseTerceiro,2),2).replace('.',',');
									document.formulario.QtdMesesFidelidadeAnterior.value	=	QtdMesesFidelidade;
									document.formulario.ValorMultaFidelidadeAnterior.value	=	formata_float(Arredonda(MultaFidelidade,2),2).replace('.',',');
									document.formulario.DataInicio.value					=	dateFormat(DataInicio);
									document.formulario.DataPrimeiraCobranca.value			=	dateFormat(DataPrimeiraCobranca);
									document.formulario.DataBaseCalculo.value				=	dateFormat(DataBaseCalculo);
									document.formulario.DataTermino.value					=	dateFormat(DataTermino);
									document.formulario.DataUltimaCobranca.value			=	dateFormat(DataUltimaCobranca);
									document.formulario.AssinaturaContrato.value			=	AssinaturaContrato;
									document.formulario.IdAgenteAutorizadoAnterior.value	=	IdAgenteAutorizado;
									document.formulario.NomeAgenteAutorizadoAnterior.value	=	NomeAgenteAutorizado;
									document.formulario.Acao.value							=	'inserir';
									
									if(document.formulario.IdAgenteAutorizado.value != ''){
										document.formulario.IdCarteira.disabled				=	false;
									}else{
										document.formulario.IdCarteira.disabled				=	true;
									}
									
									listar_carteira_anterior(IdAgenteAutorizado,IdCarteira);
									listarParametroAnterior(IdServico,IdContrato);
									calculaPeriodicidadeServico(IdPeriodicidade,document.formulario.ValorServicoAnterior.value,document.formulario.ValorPeriodicidadeAnterior);
									busca_pessoa(IdPessoa,false);
									limpaFormCredito();
									
									document.getElementById('cp_parametrosServico').style.display	=	'none';
									
									verificaAcao();
									scrollWindow('top');							
									break;
								case 'ContratoStatus':
									nameNode = xmlhttp.responseXML.getElementsByTagName("Valor")[0]; 
									nameTextNode = nameNode.childNodes[0];
									var Valor = nameTextNode.nodeValue;
									
									nameNode = xmlhttp.responseXML.getElementsByTagName("DescPeriodicidade")[0]; 
									nameTextNode = nameNode.childNodes[0];
									var DescPeriodicidade = nameTextNode.nodeValue;
									
									if(Valor == '')	Valor = 0;
									
									document.formulario.IdContrato.value			=	IdContrato;
									document.formulario.IdPessoa.value				=	IdPessoa;
									document.formulario.IdServico.value				=	IdServico;
									document.formulario.DescricaoServico.value		=	DescricaoServico;
									document.formulario.DescPeriodicidade.value		=	DescPeriodicidade;
									document.formulario.QtdParcela.value			=	QtdParcela;
									document.formulario.VarStatusAnterior.value		=	VarStatus;
									document.formulario.ValorServico.value			=	formata_float(Arredonda(Valor,2),2).replace('.',',');
									
									busca_status(document.formulario.IdStatusAnterior,IdStatus,VarStatus);
									calculaPeriodicidadeServico(IdPeriodicidade,document.formulario.ValorServico.value,document.formulario.ValorPeriodicidade);
									busca_pessoa(IdPessoa,false,'Contrato');
									
									scrollWindow('top');
									verificaAcao();							
									break;
								case "Vigencia":
									nameNode = xmlhttp.responseXML.getElementsByTagName("Valor")[0]; 
									nameTextNode = nameNode.childNodes[0];
									var Valor = nameTextNode.nodeValue;
									
									document.formulario.IdContrato.value			=	IdContrato;
									document.formulario.IdServico.value				=	IdServico;
									document.formulario.DescricaoServico.value		=	DescricaoServico;
									document.formulario.DescPeriodicidade.value		=	DescPeriodicidade;
									document.formulario.QtdParcela.value			=	QtdParcela;
									document.formulario.DataInicio.value			=	dateFormat(DataInicio);
									document.formulario.DataTermino.value			=	dateFormat(DataTermino);
									document.formulario.DataBaseCalculo.value		=	dateFormat(DataBaseCalculo);
									document.formulario.DataUltimaCobranca.value	=	dateFormat(DataUltimaCobranca);
									document.formulario.AssinaturaContrato.value	=	AssinaturaContrato;
									document.formulario.TipoContrato.value			=	TipoContrato;
									document.formulario.Valor.value					=	formata_float(Arredonda(Valor,2),2).replace('.',',');
									
									for(var i=0; i<document.formulario.AssinaturaContrato.length; i++){
										if(document.formulario.AssinaturaContrato[i].value == AssinaturaContrato){
											document.formulario.AssinaturaContrato[i].selected = true;
											i = document.formulario.AssinaturaContrato.length;
										}							
									}
									
									for(var i=0; i<document.formulario.TipoContrato.length; i++){
										if(document.formulario.TipoContrato[i].value == TipoContrato){
											document.formulario.TipoContrato[i].selected = true;
											i = document.formulario.TipoContrato.length;
										}							
									}
									
									calculaValorFinal(document.formulario.Valor.value,document.formulario.ValorDesconto.value,'',document.formulario.ValorDesconto);
									
									busca_pessoa(IdPessoa,false,'Vigencia');
									listarVigencia(IdContrato,false);
									break;
								case "LancamentoFinanceiro":
									document.formulario.IdContrato.value			=	IdContrato;
									document.formulario.IdServico.value				=	IdServico;
									document.formulario.DescricaoServico.value		=	DescricaoServico;
									document.formulario.DescPeriodicidade.value		=	DescPeriodicidade;
									document.formulario.QtdParcela.value			=	QtdParcela;
									document.formulario.DataInicio.value			=	dateFormat(DataInicio);
									document.formulario.DataTermino.value			=	dateFormat(DataTermino);
									document.formulario.DataBaseCalculo.value		=	dateFormat(DataBaseCalculo);
									document.formulario.DataUltimaCobranca.value	=	dateFormat(DataUltimaCobranca);
									document.formulario.AssinaturaContrato.value	=	AssinaturaContrato;
									document.formulario.TipoContrato.value			=	TipoContrato;
									break;
								case 'CancelarContrato':
									document.formulario.IdContrato.value				=	IdContrato;
									document.formulario.IdPessoa.value					=	IdPessoa;
									document.formulario.IdServico.value					=	IdServico;
									document.formulario.DescricaoServico.value			=	DescricaoServico;
									document.formulario.DescPeriodicidade.value			=	DescPeriodicidade;
									document.formulario.QtdParcela.value				=	QtdParcela;
									document.formulario.DataInicio.value				=	dateFormat(DataInicio);
									document.formulario.DataTermino.value				=	dateFormat(DataTermino);
									document.formulario.DataBaseCalculo.value			=	dateFormat(DataBaseCalculo);
									document.formulario.DataPrimeiraCobranca.value		=	dateFormat(DataPrimeiraCobranca);
									document.formulario.DataUltimaCobranca.value		=	dateFormat(DataUltimaCobranca);
									document.formulario.AssinaturaContrato.value		=	AssinaturaContrato;
									document.formulario.IdAgenteAutorizado.value		=	IdAgenteAutorizado;
									document.formulario.NomeAgenteAutorizado.value		=	NomeAgenteAutorizado;
									document.formulario.IdContratoAgrupador.value		=	IdContratoAgrupador;
									document.formulario.IdContratoAgrupador.disabled	=	false;
									document.formulario.AdequarLeisOrgaoPublico.value	=	AdequarLeisOrgaoPublico;
									document.formulario.IdLocalCobranca.value			=	IdLocalCobranca;
									document.formulario.TipoContrato.value				=	TipoContrato;
									document.formulario.HistoricoObs.value				=	Obs;
									document.formulario.Obs.value						=	"";
									document.formulario.ValorServico.value				=	Valor;
									document.formulario.DataCriacao.value				=	dateFormat(DataCriacao);
									document.formulario.LoginCriacao.value				=	LoginCriacao;
									document.formulario.DataAlteracao.value				=	dateFormat(DataAlteracao);
									document.formulario.LoginAlteracao.value			=	LoginAlteracao;
									document.formulario.bt_confirmar.disabled			= 	false;
									
									for(var i=0; i<document.formulario.AssinaturaContrato.length; i++){
										if(document.formulario.AssinaturaContrato[i].value == AssinaturaContrato){
											document.formulario.AssinaturaContrato[i].selected = true;
											i = document.formulario.AssinaturaContrato.length;
										}							
									}
									
									for(var i=0; i<document.formulario.TipoContrato.length; i++){
										if(document.formulario.TipoContrato[i].value == TipoContrato){
											document.formulario.TipoContrato[i].selected = true;
											i = document.formulario.TipoContrato.length;
										}							
									}
									
									busca_pessoa(IdPessoa,false,document.formulario.Local.value);
									calculaPeriodicidade(IdPeriodicidade,Valor);
									listar_carteira(IdAgenteAutorizado,IdCarteira);
									listar_contrato_agrupador(IdPessoa,IdContratoAgrupador,IdContrato);
									
									document.formulario.IdCarteira.value	=	IdCarteira;
									document.formulario.IdCarteira.disabled	=	true;
									
									scrollWindow('top');							
									break;
								case 'ProcessoFinanceiro':
									var cont = 0; ii='';
									if(ListarCampo == '' || ListarCampo == undefined){
										if(document.formulario.Filtro_IdContrato.value == ''){
											document.formulario.Filtro_IdContrato.value = IdContrato;
											ii = 0;
										}else{
											var tempFiltro	=	document.formulario.Filtro_IdContrato.value.split(',');
												
											ii=0; 
											while(tempFiltro[ii] != undefined){
												if(tempFiltro[ii] != IdContrato){
													cont++;		
												}
												ii++;
											}
											if(ii == cont){
												document.formulario.Filtro_IdContrato.value = document.formulario.Filtro_IdContrato.value + "," + IdContrato;
											}
										}
									}else{
										ii=0;
									}
									if(ii == cont){
									
										nameNode = xmlhttp.responseXML.getElementsByTagName("NomePessoa")[0]; 
										nameTextNode = nameNode.childNodes[0];
										var NomePessoa = nameTextNode.nodeValue;
										
										nameNode = xmlhttp.responseXML.getElementsByTagName("DescTipoContrato")[0]; 
										nameTextNode = nameNode.childNodes[0];
										var DescTipoContrato = nameTextNode.nodeValue;
										
										nameNode = xmlhttp.responseXML.getElementsByTagName("AbreviacaoNomeLocalCobranca")[0]; 
										nameTextNode = nameNode.childNodes[0];
										var AbreviacaoNomeLocalCobranca = nameTextNode.nodeValue;
										
										nameNode = xmlhttp.responseXML.getElementsByTagName("ValorDesconto")[0]; 
										nameTextNode = nameNode.childNodes[0];
										var ValorDesconto = nameTextNode.nodeValue;
										
										nameNode = xmlhttp.responseXML.getElementsByTagName("Status")[0]; 
										nameTextNode = nameNode.childNodes[0];
										var Status = nameTextNode.nodeValue;
										
										var tam, linha, c0, c1, c2, c3, c4, c5, c6, c7, c8, c9, c10, c11;
										
										tam 	= document.getElementById('tabelaContrato').rows.length;
										linha	= document.getElementById('tabelaContrato').insertRow(tam-1);
										
										if(tam%2 != 0){
											linha.style.backgroundColor = "#E2E7ED";
										}
										
										linha.accessKey 			= IdContrato; 
										
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
										c11	= linha.insertCell(11);
										
										var linkIni = "<a href='cadastro_contrato.php?IdContrato="+IdContrato+"'>";
										var linkFim = "</a>";
										
										c0.innerHTML = linkIni + IdContrato + linkFim;
										c0.style.padding =	"0 0 0 5px";
										
										c1.innerHTML = linkIni + NomePessoa.substr(0,30) + linkFim;
										
										c2.innerHTML = linkIni + DescricaoServico.substr(0,30) + linkFim;
										
										c3.innerHTML = linkIni + DescTipoContrato.substr(0,3) + linkFim;
										
										c4.innerHTML = linkIni + dateFormat(DataInicio) + linkFim;
										
										c5.innerHTML = linkIni + dateFormat(DataTermino) + linkFim;
										
										c6.innerHTML = linkIni + AbreviacaoNomeLocalCobranca + linkFim;
										
										c7.innerHTML = linkIni + formata_float(Arredonda(Valor,2),2).replace('.',',') + linkFim ;
										c7.style.textAlign = "right";
										c7.style.padding =	"0 8px 0 0";
										
										c8.innerHTML = linkIni + formata_float(Arredonda(ValorDesconto,2),2).replace('.',',') + linkFim ;
										c8.style.textAlign = "right";
										c8.style.padding =	"0 8px 0 0";
										
										c9.innerHTML = linkIni + formata_float(Arredonda((Valor-ValorDesconto),2),2).replace('.',',') + linkFim ;
										c9.style.textAlign = "right";
										c9.style.padding =	"0 8px 0 0";
										
										c10.innerHTML = linkIni + Status + linkFim;
										
										if(document.formulario.IdStatus.value == 1){
											c11.innerHTML = "<img src='../../img/estrutura_sistema/ico_del.gif' alt='Excluir?' onClick=\"remover_filtro_contrato("+IdContrato+")\"></tr>";
										}else{
											c11.innerHTML = "<img src='../../img/estrutura_sistema/ico_del_c.gif' alt='Excluir?'\">";
										}
										c11.style.textAlign = "center";
										c11.style.cursor = "pointer";
										
										if(document.formulario.IdProcessoFinanceiro.value == ''){
											document.getElementById('totaltabelaContrato').innerHTML	=	'Total: '+(ii+1);
										}else{
											if(document.formulario.Erro.value != ''){
												scrollWindow('bottom');
											}
										}
									}
									break;
							}
						}
						
					}	
					if(document.getElementById("quadroBuscaContrato") != null){
						if(document.getElementById("quadroBuscaContrato").style.display == "block"){
							document.getElementById("quadroBuscaContrato").style.display =	"none";
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
	function listar_carteira(IdAgenteAutorizado,IdCarteiraTemp){
		if(IdAgenteAutorizado == ''){
			while(document.formulario.IdCarteira.options.length > 0){
				document.formulario.IdCarteira.options[0] = null;
			}
			return false;
		}
		if(IdCarteiraTemp == undefined){
			IdCarteiraTemp = '';
		}
		var xmlhttp   = false;
		if (window.XMLHttpRequest) { // Mozilla, Safari,...
			xmlhttp = new XMLHttpRequest();
	        if(xmlhttp.overrideMimeType){
	    //    	xmlhttp.overrideMimeType('text/xml');
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

		url = "xml/carteira.php?IdAgenteAutorizado="+IdAgenteAutorizado+"&IdStatus=1";
		xmlhttp.open("GET", url,true);

		xmlhttp.onreadystatechange = function(){ 

			// Carregando...
			carregando(true);

			if(xmlhttp.readyState == 4){ 
				if(xmlhttp.status == 200){
					if(xmlhttp.responseText == 'false'){
						while(document.formulario.IdCarteira.options.length > 0){
							document.formulario.IdCarteira.options[0] = null;
						}
					}else{
						while(document.formulario.IdCarteira.options.length > 0){
							document.formulario.IdCarteira.options[0] = null;
						}
						addOption(document.formulario.IdCarteira,"","0");
						
						if(document.formulario.Local.value == 'Contrato' || document.formulario.Local.value == 'ContratoServico'){
							document.formulario.IdCarteira.disabled	=	false;
							if(document.formulario.IdContrato.value == ''){
								document.formulario.IdCarteira.focus();	
							}
						}
							
						for(var i=0; i<xmlhttp.responseXML.getElementsByTagName("IdCarteira").length; i++){
							nameNode = xmlhttp.responseXML.getElementsByTagName("IdCarteira")[i]; 
							nameTextNode = nameNode.childNodes[0];
							IdCarteira = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("Nome")[i]; 
							nameTextNode = nameNode.childNodes[0];
							Nome = nameTextNode.nodeValue;
							
							addOption(document.formulario.IdCarteira,Nome,IdCarteira);
						}
						if(IdCarteiraTemp!=''){
							for(ii=0;ii<document.formulario.IdCarteira.length;ii++){
								if(document.formulario.IdCarteira[ii].value == IdCarteiraTemp){
									document.formulario.IdCarteira[ii].selected = true;
									break;
								}
							}
						}else{
							document.formulario.IdCarteira[0].selected = true;
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
	function listar_contrato_agrupador(IdPessoa,IdContratoAgrupadorTemp,IdContrato){
		if(IdPessoa == '' && document.formulario.Local.value != 'OrdemServicoFatura'){
			while(document.formulario.IdContratoAgrupador.options.length > 0){
				document.formulario.IdContratoAgrupador.options[0] = null;
			}
			return false;
		}
		if(IdContratoAgrupadorTemp == undefined){
			IdContratoAgrupadorTemp = '';
		}
		if(IdContrato == undefined){
			if(document.formulario.Local.value == 'ContaEventual'){
				IdContrato = '';
			}else{
				IdContrato = 0;
			}
		}
		var xmlhttp   = false;
		if (window.XMLHttpRequest) { // Mozilla, Safari,...
			xmlhttp = new XMLHttpRequest();
	        if(xmlhttp.overrideMimeType){
	    //    	xmlhttp.overrideMimeType('text/xml');
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
		
		if((document.formulario.Local.value == 'OrdemServicoFatura' || document.formulario.Local.value == 'LancamentoFinanceiro') && IdContratoAgrupadorTemp != ""){
			url = "xml/contrato.php?IdContrato="+IdContratoAgrupadorTemp;
		}else{
			url = "xml/contrato_agrupador.php?IdPessoa="+IdPessoa+"&IdContrato="+IdContrato;
		}
		xmlhttp.open("GET", url,true);
		xmlhttp.onreadystatechange = function(){ 

			// Carregando...
			carregando(true);

			if(xmlhttp.readyState == 4){ 
				if(xmlhttp.status == 200){
					if(xmlhttp.responseText == 'false'){
						while(document.formulario.IdContratoAgrupador.options.length > 0){
							document.formulario.IdContratoAgrupador.options[0] = null;
						}
						
						document.formulario.IdContratoAgrupador.disabled	=	true;
					}else{
						while(document.formulario.IdContratoAgrupador.options.length > 0){
							document.formulario.IdContratoAgrupador.options[0] = null;
						}
						
						if(document.formulario.Local.value != "LancamentoFinanceiro"){
							document.formulario.IdContratoAgrupador.disabled	=	false;
						}
						
						addOption(document.formulario.IdContratoAgrupador,"","0");
						
						if(document.formulario.Local.value == 'Contrato'){
							document.formulario.IdCarteira.disabled		=	false;
						}
							
						for(var i=0; i<xmlhttp.responseXML.getElementsByTagName("IdContrato").length; i++){
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("IdContrato")[i]; 
							nameTextNode = nameNode.childNodes[0];
							var IdContrato = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoContratoAgrupador")[i]; 
							nameTextNode = nameNode.childNodes[0];
							DescricaoContratoAgrupador = nameTextNode.nodeValue;
							
							var Descricao	=	"("+IdContrato+") "+DescricaoContratoAgrupador;
							
							addOption(document.formulario.IdContratoAgrupador,Descricao,IdContrato);
						}
						if(IdContratoAgrupadorTemp!=''){
							for(ii=0;ii<document.formulario.IdContratoAgrupador.length;ii++){
								if(document.formulario.IdContratoAgrupador[ii].value == IdContratoAgrupadorTemp){
									document.formulario.IdContratoAgrupador[ii].selected = true;
									break;
								}
							}
						}else{
							document.formulario.IdContratoAgrupador[0].selected = true;
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
	
	function busca_automatico(ServicoAutomatico){
		document.getElementById('cp_automatico').style.display	=	'block';
		
		var temp,aux,IdContrato,IdServico,table = "";
		
		temp	=	ServicoAutomatico.split("#");
		
		for(i=0;i<temp.length;i++){
			
			if(temp[i].indexOf("¬")!='-1'){
				aux			=	temp[i].split("¬");
				IdServico	=	aux[0];
				IdContrato	=	aux[1];
				
				table		+=	"<div id='cp_tit'>Serviço Automático ("+(i+1)+")</div>";
				table		+=		"<table>";
				table		+=				"<tr>";
				table		+=					"<td class='find'>&nbsp;</td>";
				table		+=					"<td class='descCampo'>Contrato</td>";
				table		+=					"<td class='separador'>&nbsp;</td>";
				table		+=					"<td class='descCampo'><B  style='margin-right:35px; color:#000' id='IdServico'>Serviço</B>Nome Serviço</td>";
				table		+=					"<td class='separador'>&nbsp;</td>";
				table		+=					"<td class='descCampo'>Valor Mensal do Serviço("+document.formulario.MoedaAtual.value+")</td>";
				table		+=					"<td class='separador'>&nbsp;</td>";
				table		+=					"<td class='descCampo'>Valor Periodicidade("+document.formulario.MoedaAtual.value+")</td>";
				table		+=				"</tr>";
				table		+=				"<tr>";
				table		+=					"<td class='find'>&nbsp;</td>";
				table		+=					"<td class='campo'>";
				table		+=						"<input type='text' name='IdContratoAutomatico_"+IdServico+"' value='"+IdContrato+"' style='width:70px'>";
				table		+=					"</td>";
				table		+=					"<td class='separador'>&nbsp;</td>";
				table		+=					"<td class='campo'>";
				table		+=						"<input type='text' name='IdServico_"+IdServico+"' value='"+IdServico+"'  style='width:70px' maxlength='11' readOnly><input type='text' class='agrupador' name='DescricaoServico_"+IdServico+"' style='width:300px' maxlength='100' readOnly>";
				table		+=					"</td>";
				table		+=					"<td class='separador'>&nbsp;</td>";
				table		+=					"<td class='campo'>";
				table		+=						"<input type='text' name='ValorInicial_"+IdServico+"' value='' style='width:160px' readOnly>";
				table		+=					"</td>";
				table		+=					"<td class='separador'>&nbsp;</td>";
				table		+=					"<td class='campo'>";
				table		+=						"<input type='text' name='ValorPeriodicidade_"+IdServico+"' value='' style='width:160px' maxlength='12' readOnly>";
				table		+=					"</td>";
				table		+=				"</tr>";
				table		+=			"</table>";
				table		+=			"<table id='tabelaParametro_"+IdServico+"' style='display:none'>";
				table		+=			"</table>";
			}else{
				IdServico	=	temp[i];
				IdContrato	=	"";
				
				table		+=	"<div id='cp_tit'>Serviço Automático ("+(i+1)+")</div>";
				table		+=		"<table>";
				table		+=				"<tr>";
				table		+=					"<td class='find'>&nbsp;</td>";
				table		+=					"<td class='descCampo'><B  style='margin-right:35px; color:#000' id='IdServico'>Serviço</B>Nome Serviço</td>";
				table		+=					"<td class='separador'>&nbsp;</td>";
				table		+=					"<td class='descCampo'>Valor Mensal do Serviço("+document.formulario.MoedaAtual.value+")</td>";
				table		+=					"<td class='separador'>&nbsp;</td>";
				table		+=					"<td class='descCampo'>Valor Periodicidade("+document.formulario.MoedaAtual.value+")</td>";
				table		+=				"</tr>";
				table		+=				"<tr>";
				table		+=					"<td class='find'>&nbsp;</td>";
				table		+=					"<td class='campo'>";
				table		+=						"<input type='hidden' name='IdContratoAutomatico_"+IdServico+"' value='"+IdContrato+"'><input type='text' name='IdServico_"+IdServico+"' value='"+IdServico+"'  style='width:70px' maxlength='11' readOnly><input type='text' class='agrupador' name='DescricaoServico_"+IdServico+"' style='width:388px' maxlength='100' readOnly>";
				table		+=					"</td>";
				table		+=					"<td class='separador'>&nbsp;</td>";
				table		+=					"<td class='campo'>";
				table		+=						"<input type='text' name='ValorInicial_"+IdServico+"' value='' style='width:160px' readOnly>";
				table		+=					"</td>";
				table		+=					"<td class='separador'>&nbsp;</td>";
				table		+=					"<td class='campo'>";
				table		+=						"<input type='text' name='ValorPeriodicidade_"+IdServico+"' value='' style='width:160px' maxlength='12' readOnly>";
				table		+=					"</td>";
				table		+=				"</tr>";
				table		+=			"</table>";
				table		+=			"<table id='tabelaParametro_"+IdServico+"' style='display:none'>";
				table		+=			"</table>";
			}
			
			busca_servico_automatico(IdServico,IdContrato);
		}
		
		document.getElementById('cp_automatico').innerHTML	=	table;	
		
		tabindex	=	0;
		for(i=0;i<temp.length;i++){
			if(temp[i].indexOf("¬")!= "-1"){
				aux			=	temp[i].split("¬");
				IdServico	=	aux[0];
				IdContrato	=	aux[1];
			}else{
				IdServico	=	temp[i];
				IdContrato	=	"";
			}
			
			if(tabindex == 0){
				tabindex = 100;
			}
			
			tabindex	=	tabindex + 100;
			
			if(document.formulario.Local.value == 'ContratoServico'){
				listarServicoParametroAnteriorAutomatico(IdServico,tabindex,IdContrato);	
			}else{
				listarServicoParametroAutomatico(IdServico,tabindex,IdContrato);	
			}
		}
		
	}
	function busca_servico_automatico(IdServico,IdContrato){
		if(IdServico == '' || IdServico == undefined){
			IdServico = 0;
		}
		if(IdContrato == undefined){
			IdContrato = '';
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
	    
	    if(IdContrato != ""){
	   		url = "xml/contrato.php?IdContrato="+IdContrato;
	   	}else{
	   		url = "xml/servico.php?IdServico="+IdServico+"&Local=Servico";
		}
		
		xmlhttp.open("GET", url,true);
		xmlhttp.onreadystatechange = function(){ 
	
			// Carregando...
			carregando(true);
	
			if(xmlhttp.readyState == 4){ 
				if(xmlhttp.status == 200){
	
					if(xmlhttp.responseText != 'false'){		
						nameNode = xmlhttp.responseXML.getElementsByTagName("IdServico")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var IdServico = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoServico")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var DescricaoServico = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("Valor")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var Valor = nameTextNode.nodeValue;
						
						if(Valor == ''){				Valor = '0';	}
						
						var posIni = 0, posFim = 0;
						for(ii=0;ii<document.formulario.length;ii++){
							if(document.formulario[ii].name.substring(0,10)	==	"IdServico_"){
								if(posIni == 0){
									posIni	=	ii;
								}
								posFim	=	ii;
							}
						}
						
						var Periodicidade;
						
						for(ii=posIni;ii<=posFim;ii++){
							if(document.formulario[ii].name.substring(0,10)	==	"IdServico_"){
								temp	=	document.formulario[ii].name.split("_");
								if(temp[1] == IdServico){
									document.formulario[ii+1].value	=	DescricaoServico;
									document.formulario[ii+2].value	=	formata_float(Arredonda(Valor,2),2).replace('.',',');
									
									
									if(IdContrato != ""){
										Periodicidade	=	document.formulario.Periodicidade.value;
									}else{
										Periodicidade	=	document.formulario.IdPeriodicidade.value;
									}
									
									if(Periodicidade != ''){
										calculaPeriodicidade(Periodicidade,Valor,document.formulario[ii+3]);
									}
									break;
								}
							}
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
	function calculaServicoAutomatico(IdPeriodicidade){	
		if(document.formulario.Local.value == 'Contrato' && document.formulario.ServicoAutomatico.value != ""){
			var posIni = 0, posFim = 0;
			for(ii=0;ii<document.formulario.length;ii++){
				if(document.formulario[ii].name.substring(0,10)	==	"IdServico_"){
					if(posIni == 0){
						posIni	=	ii;
					}
					posFim	=	ii;
				}
			}
			for(ii=posIni;ii<=posFim;ii++){
				if(document.formulario[ii].name.substring(0,10)	==	"IdServico_"){
					calculaPeriodicidade(document.formulario.IdPeriodicidade.value,document.formulario[ii+2].value,document.formulario[ii+3]);
				}
			}
		}
	}	
	function listarServicoParametroAutomatico(IdServico,tabindex,IdContrato){
		while(document.getElementById("tabelaParametro_"+IdServico+"").rows.length > 0){
			document.getElementById("tabelaParametro_"+IdServico+"").deleteRow(0);
		}		
	
		var tam, linha, c0, c1, c2, c3, c4, c5;
		
		if(IdServico == ''){
			IdServico = 0;
		}
		if(IdContrato == undefined){
			IdContrato = '';
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
	    if(IdContrato!=""){
			url = "xml/contrato_parametro.php?IdServico="+IdServico+"&IdStatus=1&IdContrato="+IdContrato;
		}else{
			url = "xml/servico_parametro.php?IdServico="+IdServico+"&IdStatus=1";
		}

		xmlhttp.open("GET", url,true);
 		xmlhttp.onreadystatechange = function(){ 
	
			// Carregando...
			carregando(true);

			if(xmlhttp.readyState == 4){ 
				if(xmlhttp.status == 200){
					if(xmlhttp.responseText == 'false'){		
						// Fim de Carregando
						document.getElementById("tabelaParametro_"+IdServico+"").style.display	=	'none';
						carregando(false);
					}else{
						var padding, visivel,DescricaoParametroServico, Obrigatorio, ValorDefault, Valor, IdParametroServico, color, salvar;
						
						document.getElementById("tabelaParametro_"+IdServico+"").style.display	=	'block';
						var obsTemp = new Array(), invisivel="",cont=0;
						for(var i=0; i<xmlhttp.responseXML.getElementsByTagName("IdParametroServico").length; i++){
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoParametroServico")[i]; 
							nameTextNode = nameNode.childNodes[0];
							var DescricaoParametroServico = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("Obrigatorio")[i]; 
							nameTextNode = nameNode.childNodes[0];
							var Obrigatorio = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("Valor")[i]; 
							nameTextNode = nameNode.childNodes[0];
							var Valor = nameTextNode.nodeValue;	
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("ValorDefault")[i]; 
							nameTextNode = nameNode.childNodes[0];
							var ValorDefault = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("IdParametroServico")[i]; 
							nameTextNode = nameNode.childNodes[0];
							var IdParametroServico = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("Editavel")[i]; 
							nameTextNode = nameNode.childNodes[0];
							var Editavel = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("Obs")[i]; 
							nameTextNode = nameNode.childNodes[0];
							var Obs = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("IdTipoParametro")[i]; 
							nameTextNode = nameNode.childNodes[0];
							var IdTipoParametro = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("IdMascaraCampo")[i]; 
							nameTextNode = nameNode.childNodes[0];
							var IdMascaraCampo = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("OpcaoValor")[i]; 
							nameTextNode = nameNode.childNodes[0];
							var OpcaoValor = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("Visivel")[i]; 
							nameTextNode = nameNode.childNodes[0];
							var Visivel = nameTextNode.nodeValue;
							
							if(IdContrato == ""){
								Valor	=	ValorDefault;
							}
							
							
							if(Visivel == '1'){
								tam 	 = document.getElementById("tabelaParametro_"+IdServico+"").rows.length;
								
								obsTemp[cont]	=	Obs;
								
								if(cont%2 == 0){
									linha	 = document.getElementById("tabelaParametro_"+IdServico+"").insertRow(tam);
									tabindex = tabindex + cont + 1;
									padding	 = 22;	
									pos		 = 0;
								}else{
									padding	 = 10;	
									tabindex = tabindex + cont;
									pos		 = 1;
								}
								
								if(obsTemp[(cont-1)]== undefined || obsTemp[(cont-1)]== ''){
									if(Obs	==	'')	Obs	=	'<BR>';
								}
								
								if((cont+1) == xmlhttp.responseXML.getElementsByTagName("tabelaParametro_"+IdServico+"").length && cont%2 == 0){
									padding	 = 22;	
								}
									
								if(Obrigatorio == 1){
									color = "#C10000";
								}else{
									color = "#000000";
								}
								
								if(Editavel == 1){
									visivel	=	'';
								}else{
									visivel	=	'readOnly';
								}
								
								linha.accessKey = IdParametroServico; 
								
								c0	= linha.insertCell(pos);
								
								if(IdTipoParametro == 1){
									switch(IdMascaraCampo){
										case '1':
											c0.innerHTML = "<p style='margin:0; padding-bottom:1px; padding-left:"+padding+"px'><B style='color:"+color+";'>"+DescricaoParametroServico+"</B></p><p style='padding-bottom:6px; padding-left:"+padding+"px; margin:0;'><input type='text' name='ValorAutomatico_"+IdServico+"_"+IdParametroServico+"' value='"+Valor+"' style='width:399px;' autocomplete='off' maxlength='255' onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out');\" "+visivel+" onkeypress=\"mascara(this,event,'date')\" maxlength='10' tabindex="+tabindex+"><input type='hidden' name='ObrigatorioAutomatico_"+IdServico+"_"+IdParametroServico+"' value='"+Obrigatorio+"'><input type='hidden' name='VisivelAutomatico_"+IdServico+"_"+IdParametroServico+"' value='"+Visivel+"'><BR>"+Obs+"</p>";
											break;
										case '2':
											c0.innerHTML=  "<p style='margin:0; padding-bottom:1px; padding-left:"+padding+"px'><B style='color:"+color+";'>"+DescricaoParametroServico+"</B></p><p style='padding-bottom:6px; padding-left:"+padding+"px; margin:0;'><input type='text' name='ValorAutomatico_"+IdServico+"_"+IdParametroServico+"' value='"+Valor+"' style='width:399px;' autocomplete='off' maxlength='255' onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out');\" "+visivel+" onkeypress=\"mascara(this,event,'int')\" tabindex="+tabindex+"><input type='hidden' name='ObrigatorioAutomatico_"+IdServico+"_"+IdParametroServico+"' value='"+Obrigatorio+"'><input type='hidden' name='VisivelAutomatico_"+IdServico+"_"+IdParametroServico+"' value='"+Visivel+"'><BR>"+Obs+"</p>";
											break;
										case '3':
											c0.innerHTML = "<p style='margin:0; padding-bottom:1px; padding-left:"+padding+"px'><B style='color:"+color+";'>"+DescricaoParametroServico+"</B></p><p style='padding-bottom:6px; padding-left:"+padding+"px; margin:0;'><input type='text' name='ValorAutomatico_"+IdServico+"_"+IdParametroServico+"' value='"+Valor+"' style='width:399px;' autocomplete='off' maxlength='255' onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out');\" "+visivel+" onkeypress=\"mascara(this,event,'float')\" maxlength='12' tabindex="+tabindex+"><input type='hidden' name='ObrigatorioAutomatico_"+IdServico+"_"+IdParametroServico+"' value='"+Obrigatorio+"'><input type='hidden' name='VisivelAutomatico_"+IdServico+"_"+IdParametroServico+"' value='"+Visivel+"'><BR>"+Obs+"</p>";
											break;
										case '5':
											c0.innerHTML = "<p style='margin:0; padding-bottom:1px; padding-left:"+padding+"px'><B style='color:"+color+";'>"+DescricaoParametroServico+"</B></p><p style='padding-bottom:6px; padding-left:"+padding+"px; margin:0;'><input type='text' name='ValorAutomatico_"+IdServico+"_"+IdParametroServico+"' value='"+Valor+"' style='width:399px;' autocomplete='off' onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out');\" "+visivel+"  onkeypress=\"mascara(this,event,'mac')\" maxlength='17' tabindex="+tabindex+"><input type='hidden' name='ObrigatorioAutomatico_"+IdServico+"_"+IdParametroServico+"' value='"+Obrigatorio+"'><input type='hidden' name='VisivelAutomatico_"+IdServico+"_"+IdParametroServico+"' value='"+Visivel+"'><BR>"+Obs+"</p>";
											break;
										default:
											c0.innerHTML = "<p style='margin:0; padding-bottom:1px; padding-left:"+padding+"px'><B style='color:"+color+";'>"+DescricaoParametroServico+"</B></p><p style='padding-bottom:6px; padding-left:"+padding+"px; margin:0;'><input type='text' name='ValorAutomatico_"+IdServico+"_"+IdParametroServico+"' value='"+Valor+"' style='width:399px;' autocomplete='off' maxlength='255' onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out');\" "+visivel+" tabindex="+tabindex+"><input type='hidden' name='ObrigatorioAutomatico_"+IdServico+"_"+IdParametroServico+"' value='"+Obrigatorio+"'><input type='hidden' name='VisivelAutomatico_"+IdServico+"_"+IdParametroServico+"' value='"+Visivel+"'><BR>"+Obs+"</p>";
									}
								}else{
									campo  = "<p style='margin:0; padding-bottom:1px; padding-left:"+padding+"px'>";
									campo +=	"<B style='color:"+color+";'>"+DescricaoParametroServico+"</B></p>";
									campo += "<p style='padding-bottom:6px; padding-left:"+padding+"px; margin:0;'>";
									campo +=	"<select name='ValorAutomatico_"+IdServico+"_"+IdParametroServico+"'  style='width:399px;' onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out');\" "+visivel+" tabindex="+tabindex+">";
									campo += "<option value='"+Valor+"'></option>";
												
									valor	=	OpcaoValor.split("\n");
									for(var ii=0; ii<valor.length; ii++){
										selecionado = "";
										if(Valor == valor[ii]){
											selecionado	=	"selected=true";
										}
										campo +=	"<option value='"+valor[ii]+"' "+selecionado+">"+valor[ii]+"</option>";
									}
									campo += "</select>";
									campo +=	"<input type='hidden' name='ObrigatorioAutomatico_"+IdServico+"_"+IdParametroServico+"' value='"+Obrigatorio+"'><input type='hidden' name='VisivelAutomatico_"+IdServico+"_"+IdParametroServico+"' value='"+Visivel+"'>";
									campo +=	"<BR>"+Obs+"</p>";
									
									c0.innerHTML	=	campo;
								}
								
								cont++;
							}else{
								invisivel	+=	"<div style='display:none'>";
								if(IdTipoParametro == 1){
									invisivel += "<input type='text' name='ValorAutomatico_"+IdServico+"_"+IdParametroServico+"' value='"+Valor+"' style='width:399px;' autocomplete='off'><input type='hidden' name='ObrigatorioAutomatico_"+IdServico+"_"+IdParametroServico+"' value='"+Obrigatorio+"'><input type='hidden' name='VisivelAutomatico_"+IdServico+"_"+IdParametroServico+"' value='"+Visivel+"'>";
								}else{
									campo  = "";
									campo +=	"<select name='ValorAutomatico_"+IdServico+"_"+IdParametroServico+"'  style='width:406px;'>";
									campo += "<option value=''></option>";
												
									valor	=	OpcaoValor.split("\n");
									for(var ii=0; ii<valor.length; ii++){
										selecionado = "";
										if(Valor == valor[ii]){
											selecionado	=	"selected=true";
										}
										campo +=	"<option value='"+valor[ii]+"' "+selecionado+">"+valor[ii]+"</option>";
									}
									campo += "</select>";
									campo +=	"<input type='hidden' name='ObrigatorioAutomatico_"+IdServico+"_"+IdParametroServico+"' value='"+Obrigatorio+"'><input type='hidden' name='VisivelAutomatico_"+IdServico+"_"+IdParametroServico+"' value='"+Visivel+"'>";
									
									invisivel	+=	campo;
								}
							
							
								invisivel	+=	"</div>";
							}
						}
						
						if(invisivel !=""){
							tam 	 = document.getElementById("tabelaParametro_"+IdServico+"").rows.length;
							linha	 = document.getElementById("tabelaParametro_"+IdServico+"").insertRow(tam);
							
							linha.accessKey = IdParametroServico; 
							
							c0	= linha.insertCell(0);
							c0.innerHTML	=	invisivel;
						}
						
						if(document.formulario.Erro.value != '0' && document.formulario.Erro.value!=''){
							scrollWindow('bottom');	
						}else{
							scrollWindow('top');	
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
	
	function periodicidade(IdServico,Temp){
		if(Temp == undefined) Temp = "";
		
		while(document.formulario.IdPeriodicidade.options.length > 0){
			document.formulario.IdPeriodicidade.options[0] = null;
		}
		while(document.formulario.QtdParcela.options.length > 0){
			document.formulario.QtdParcela.options[0] = null;
		}
		while(document.formulario.TipoContrato.options.length > 0){
			document.formulario.TipoContrato.options[0] = null;
		}
		while(document.formulario.IdLocalCobranca.options.length > 0){
			document.formulario.IdLocalCobranca.options[0] = null;
		}
		while(document.formulario.MesFechado.options.length > 0){
			document.formulario.MesFechado.options[0] = null;
		}	
		
		document.formulario.QtdMesesFidelidade.value		=	"";	
		
		if(IdServico!=""){
			var xmlhttp = false;
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

			url = "xml/servico_periodicidade.php?IdServico="+IdServico+"&Local="+document.formulario.Local.value;
			
			// Carregando...
			carregando(true);
			
			xmlhttp.open("GET", url,true);
			xmlhttp.onreadystatechange = function(){ 
				if(xmlhttp.readyState == 4){ 
					if(xmlhttp.status == 200){
						if(xmlhttp.responseText != 'false'){		
							var nameNode, nameTextNode, IdPeriodicidade;
							addOption(document.formulario.IdPeriodicidade,"","");
							
							for(var i=0; i<xmlhttp.responseXML.getElementsByTagName("IdPeriodicidade").length; i++){
								
								nameNode = xmlhttp.responseXML.getElementsByTagName("IdPeriodicidade")[i]; 
								nameTextNode = nameNode.childNodes[0];
								IdPeriodicidade = nameTextNode.nodeValue;
								
								nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoPeriodicidade")[i]; 
								nameTextNode = nameNode.childNodes[0];
								DescricaoPeriodicidade = nameTextNode.nodeValue;
								
								addOption(document.formulario.IdPeriodicidade,DescricaoPeriodicidade,IdPeriodicidade);
							}
							if(i==1){
								document.formulario.IdPeriodicidade[1].selected = true;
								IdPeriodicidade	=	document.formulario.IdPeriodicidade[1].value;
								
								servico_periodicidade_parcelas(IdServico,IdPeriodicidade,Temp);
								calculaPeriodicidade(IdPeriodicidade,document.formulario.ValorServico.value);
								
								if(Temp == "" && document.formulario.Acao.value == 'inserir'){
									contrato_periodicidade(document.formulario.Periodicidade.value);
								}
							}else{
								if(document.formulario.Acao.value == 'inserir'){
									document.formulario.IdPeriodicidade[0].selected = true;
								}else{
									if(Temp == ""){
										contrato_periodicidade(document.formulario.Periodicidade.value);
										servico_periodicidade_parcelas(IdServico,document.formulario.Periodicidade.value)
									}else{
										document.formulario.IdPeriodicidade[0].selected = true;
									}
								}
							}
						}else{
							contrato_periodicidade(document.formulario.Periodicidade.value);
						}
					}
					// Fim de Carregando
					carregando(false);
				}
				return true;
			}
			xmlhttp.send(null);	
			
		}
	}
	function servico_periodicidade_parcelas(IdServico,IdPeriodicidade,Temp){
		if(Temp == undefined) Temp = "";
		
		while(document.formulario.QtdParcela.options.length > 0){
			document.formulario.QtdParcela.options[0] = null;
		}
		while(document.formulario.TipoContrato.options.length > 0){
			document.formulario.TipoContrato.options[0] = null;
		}
		while(document.formulario.IdLocalCobranca.options.length > 0){
			document.formulario.IdLocalCobranca.options[0] = null;
		}
		while(document.formulario.MesFechado.options.length > 0){
			document.formulario.MesFechado.options[0] = null;
		}
		document.formulario.QtdMesesFidelidade.value		=	"";	
		
		if(IdPeriodicidade!=""){
			var xmlhttp = false;
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
		   	url = "xml/servico_periodicidade.php?IdServico="+IdServico+"&IdPeriodicidade="+IdPeriodicidade+"&Local="+document.formulario.Local.value;
			
			xmlhttp.open("GET", url,true);
			xmlhttp.onreadystatechange = function(){ 
				// Carregando...
				carregando(true);
				
				if(xmlhttp.readyState == 4){ 
					if(xmlhttp.status == 200){ 
						if(xmlhttp.responseText != 'false'){		
							var nameNode, nameTextNode;					
							addOption(document.formulario.QtdParcela,"","");
							
							for(var i=0; i<xmlhttp.responseXML.getElementsByTagName("IdPeriodicidade").length; i++){
								
								nameNode = xmlhttp.responseXML.getElementsByTagName("QtdParcela")[i]; 
								nameTextNode = nameNode.childNodes[0];
								QtdParcela = nameTextNode.nodeValue;
								
								addOption(document.formulario.QtdParcela,QtdParcela,QtdParcela);
							}
							if(i==1){
								document.formulario.QtdParcela[1].selected = true;
								QtdParcela	=	document.formulario.QtdParcela[1].value;
								
								busca_servico_tipo_contrato(IdServico,IdPeriodicidade,QtdParcela,Temp);
								
								if(Temp == "" && document.formulario.Acao.value == 'alterar'){
									contrato_periodicidade_parcela(document.formulario.QuantParcela.value);
								}
							}else{
								if(document.formulario.Acao.value == 'inserir'){
									document.formulario.QtdParcela[0].selected = true;
								}else{
									if(Temp == ""){
										contrato_periodicidade_parcela(document.formulario.QuantParcela.value);
										busca_servico_tipo_contrato(IdServico,document.formulario.Periodicidade.value,document.formulario.QuantParcela.value)
									}else{
										document.formulario.QtdParcela[0].selected = true;
									}
								}
							}
						}else{
							contrato_periodicidade_parcela(document.formulario.QuantParcela.value);
						}
					}
					// Fim de Carregando
					carregando(false);
				}
				return true;
			}
			xmlhttp.send(null);
		}else{
			while(document.getElementById('tabelaParametroLocalCobranca').rows.length > 0){
				document.getElementById('tabelaParametroLocalCobranca').deleteRow(0);
			}
			
			document.getElementById('cp_parametrosLocalCobranca').style.display	=	'none';
		}
	}
	function busca_servico_tipo_contrato(IdServico,IdPeriodicidade,QtdParcela,Temp){
		if(Temp == undefined) Temp = "";
		
		while(document.formulario.TipoContrato.options.length > 0){
			document.formulario.TipoContrato.options[0] = null;
		}
		while(document.formulario.IdLocalCobranca.options.length > 0){
			document.formulario.IdLocalCobranca.options[0] = null;
		}
		while(document.formulario.MesFechado.options.length > 0){
			document.formulario.MesFechado.options[0] = null;
		}
		document.formulario.QtdMesesFidelidade.value		=	"";	
		
		var nameNode, nameTextNode, url;
		
		if(QtdParcela != ''){
			var xmlhttp = false;
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
		    
		    url = "xml/servico_periodicidade.php?IdServico="+IdServico+"&IdPeriodicidade="+IdPeriodicidade+"&QtdParcela="+QtdParcela+"&Local=Contrato";
			xmlhttp.open("GET", url,true);
	
			xmlhttp.onreadystatechange = function(){ 
				// Carregando...
				carregando(true);
					
				if(xmlhttp.readyState == 4){ 
					if(xmlhttp.status == 200){
						if(xmlhttp.responseText != 'false'){
							var nameNode, nameTextNode, DescTipoContrato, TipoContrato;					
							addOption(document.formulario.TipoContrato,"","");
							
							for(var i=0; i<xmlhttp.responseXML.getElementsByTagName("TipoContrato").length; i++){
								
								nameNode = xmlhttp.responseXML.getElementsByTagName("TipoContrato")[i]; 
								nameTextNode = nameNode.childNodes[0];
								TipoContrato = nameTextNode.nodeValue;
	
								nameNode = xmlhttp.responseXML.getElementsByTagName("DescTipoContrato")[i]; 
								nameTextNode = nameNode.childNodes[0];
								DescTipoContrato = nameTextNode.nodeValue;
								
								addOption(document.formulario.TipoContrato,DescTipoContrato,TipoContrato);
							}
							if(i == 1){
								document.formulario.TipoContrato[1].selected		=	true;
								TipoContrato	=	document.formulario.TipoContrato[1].value;
								
								busca_servico_local_cobranca(IdServico,IdPeriodicidade,QtdParcela,TipoContrato,Temp);
								
								if(Temp == "" && document.formulario.Acao.value == 'alterar'){
									contrato_tipo_contrato(document.formulario.TipoContratoTemp.value);
								}
							}else{
								if(document.formulario.Acao.value == 'inserir'){
									document.formulario.TipoContrato[0].selected = true;
								}else{
									if(Temp == ""){
										contrato_tipo_contrato(document.formulario.TipoContratoTemp.value);
										busca_servico_local_cobranca(IdServico,document.formulario.Periodicidade.value,document.formulario.QuantParcela.value,document.formulario.TipoContratoTemp.value)
									}else{
										document.formulario.TipoContrato[0].selected = true;
									}
								}
							}					
						}else{
							contrato_tipo_contrato(document.formulario.TipoContratoTemp.value);
						}
					}
					// Fim de Carregando
					carregando(false);
				}
				return true;
			}
			xmlhttp.send(null);
		}
	}
	function busca_servico_local_cobranca(IdServico,IdPeriodicidade,QtdParcela,TipoContrato,Temp){
		if(Temp == undefined)	Temp = "";
	
		
		while(document.formulario.IdLocalCobranca.options.length > 0){
			document.formulario.IdLocalCobranca.options[0] = null;
		}
		while(document.formulario.MesFechado.options.length > 0){
			document.formulario.MesFechado.options[0] = null;
		}
		document.formulario.QtdMesesFidelidade.value		=	"";	
		
		if(TipoContrato!=""){
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
		    
			url = "xml/servico_periodicidade.php?IdServico="+IdServico+"&IdPeriodicidade="+IdPeriodicidade+"&QtdParcela="+QtdParcela+"&TipoContrato="+TipoContrato+"&Local=Contrato";
			xmlhttp.open("GET", url,true);
			
			// Carregando...
			carregando(true);
			
			xmlhttp.onreadystatechange = function(){ 
				if(xmlhttp.readyState == 4){ 
					if(xmlhttp.status == 200){
						if(xmlhttp.responseText != 'false'){		
							var nameNode, nameTextNode, IdLocalCobranca, DescricaoLocalCobranca, IdTipoLocalCobranca;					
							
							addOption(document.formulario.IdLocalCobranca,"","");
							
							for(var i=0; i<xmlhttp.responseXML.getElementsByTagName("IdLocalCobranca").length; i++){
								
								nameNode = xmlhttp.responseXML.getElementsByTagName("IdLocalCobranca")[i]; 
								nameTextNode = nameNode.childNodes[0];
								IdLocalCobranca = nameTextNode.nodeValue;
								
								nameNode = xmlhttp.responseXML.getElementsByTagName("IdTipoLocalCobranca")[i]; 
								nameTextNode = nameNode.childNodes[0];
								IdTipoLocalCobranca = nameTextNode.nodeValue;
		
								nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoLocalCobranca")[i]; 
								nameTextNode = nameNode.childNodes[0];
								DescricaoLocalCobranca = nameTextNode.nodeValue;
								
								addOption(document.formulario.IdLocalCobranca,DescricaoLocalCobranca,IdLocalCobranca,Temp);
							}
							if(i==1){
								document.formulario.IdLocalCobranca[1].selected		=	true;	
								IdLocalCobranca	=	document.formulario.IdLocalCobranca[1].value;

								if(document.formulario.Acao.value == 'inserir' || Temp != ""){
									listarLocalCobrancaParametro(IdLocalCobranca,true);
								}
								
								busca_servico_mes_fechado(IdServico,IdPeriodicidade,QtdParcela,TipoContrato,IdLocalCobranca);
								
								if(Temp == "" && document.formulario.Acao.value == 'alterar'){
									contrato_local_cobranca(document.formulario.IdLocalCobrancaTemp.value);
								}
							}else{
								if(document.formulario.Acao.value == 'inserir'){
									document.formulario.IdLocalCobranca[0].selected = true;
								}else{
									if(Temp == ""){
										contrato_local_cobranca(document.formulario.IdLocalCobrancaTemp.value);
										busca_servico_mes_fechado(IdServico,document.formulario.Periodicidade.value,document.formulario.QuantParcela.value,document.formulario.TipoContratoTemp.value,document.formulario.IdLocalCobrancaTemp.value);
									}else{
										document.formulario.IdLocalCobranca[0].selected = true;
									}
								}	
							}	
						}else{
							contrato_local_cobranca(document.formulario.IdLocalCobrancaTemp.value);
						}
					}
					// Fim de Carregando
					carregando(false);
				}
				return true;
			}
			xmlhttp.send(null);
		}
	}
	function busca_servico_mes_fechado(IdServico,IdPeriodicidade,QtdParcela,TipoContrato,IdLocalCobranca,Temp){
		if(Temp == undefined) Temp = "";
		
		while(document.formulario.MesFechado.options.length > 0){
			document.formulario.MesFechado.options[0] = null;
		}
		document.formulario.QtdMesesFidelidade.value		=	"";	
		
		if(IdLocalCobranca != ''){
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
		    
			url = "xml/servico_periodicidade.php?IdServico="+IdServico+"&IdPeriodicidade="+IdPeriodicidade+"&QtdParcela="+QtdParcela+"&TipoContrato="+TipoContrato+"&IdLocalCobranca="+IdLocalCobranca+"&Local=Contrato";
			xmlhttp.open("GET", url,true);
			
			// Carregando...
			carregando(true);
			
			xmlhttp.onreadystatechange = function(){ 
				if(xmlhttp.readyState == 4){ 
					if(xmlhttp.status == 200){
						if(xmlhttp.responseText != 'false'){		
							var nameNode, nameTextNode, MesFechado, DescricaoLocalCobranca, QtdMesesFidelidade;					
							
							addOption(document.formulario.MesFechado,"","");
							
							for(var i=0; i<xmlhttp.responseXML.getElementsByTagName("MesFechado").length; i++){
							
								nameNode = xmlhttp.responseXML.getElementsByTagName("MesFechado")[i]; 
								nameTextNode = nameNode.childNodes[0];
								MesFechado = nameTextNode.nodeValue;
		
								nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoMesFechado")[i]; 
								nameTextNode = nameNode.childNodes[0];
								DescricaoMesFechado = nameTextNode.nodeValue;
								
								nameNode = xmlhttp.responseXML.getElementsByTagName("QtdMesesFidelidade")[i]; 
								nameTextNode = nameNode.childNodes[0];
								QtdMesesFidelidade = nameTextNode.nodeValue;
								
								addOption(document.formulario.MesFechado,DescricaoMesFechado,MesFechado);
							}
							
							if(i==1){
								document.formulario.MesFechado[1].selected  	= true;							
								document.formulario.QtdMesesFidelidade.value	=	QtdMesesFidelidade;
								
								if(Temp == "" && document.formulario.Acao.value == 'alterar'){
									contrato_mes_fechado(document.formulario.MesFechadoTemp.value,document.formulario.QtdMesesFidelidade.value);
								}
							}else{
								if(document.formulario.Acao.value == 'inserir'){
									document.formulario.MesFechado[0].selected = true;
								}else{
									if(Temp == ""){
										contrato_mes_fechado(document.formulario.MesFechadoTemp.value,document.formulario.QtdMesesFidelidade.value);
									}else{
										document.formulario.MesFechado[0].selected = true;	
									}
								}
							}
						}else{
							contrato_mes_fechado(document.formulario.MesFechadoTemp.value,document.formulario.QtdMesesFidelidade.value);
						}
					}
					// Fim de Carregando
					carregando(false);
				}
				return true;
			}
			xmlhttp.send(null);
		}
	}
	function contrato_periodicidade(IdPeriodicidade){
		var temp	=	0;
		for(i=0;i<document.formulario.IdPeriodicidade.options.length;i++){
			if(document.formulario.IdPeriodicidade[i].value == IdPeriodicidade){
				document.formulario.IdPeriodicidade[i].selected	=	true;
				temp++;
				break;
			}
		}
		if(temp == 0){
			var xmlhttp = false;
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
			
			url = "xml/periodicidade.php?IdPeriodicidade="+IdPeriodicidade
			// Carregando...
			carregando(true);
			
			xmlhttp.open("GET", url,true);
			xmlhttp.onreadystatechange = function(){ 
				if(xmlhttp.readyState == 4){ 
					if(xmlhttp.status == 200){
						if(xmlhttp.responseText != 'false'){		
							var nameNode, nameTextNode, IdPeriodicidade;
							
							if(document.formulario.IdPeriodicidade.options.length == 0){
								addOption(document.formulario.IdPeriodicidade,"","");
							}
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("IdPeriodicidade")[0]; 
							nameTextNode = nameNode.childNodes[0];
							IdPeriodicidade = nameTextNode.nodeValue;
								
							nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoPeriodicidade")[0]; 
							nameTextNode = nameNode.childNodes[0];
							DescricaoPeriodicidade = nameTextNode.nodeValue;
								
							addOption(document.formulario.IdPeriodicidade,DescricaoPeriodicidade,IdPeriodicidade);
							
							contrato_periodicidade_parcela(document.formulario.QuantParcela.value);
						}
					}
					// Fim de Carregando
					carregando(false);
				}
				return true;
			}
			xmlhttp.send(null);
		}	
	}
	function contrato_periodicidade_parcela(QtdParcela){
		var temp	=	0;
		for(i=0;i<document.formulario.QtdParcela.options.length;i++){
			if(document.formulario.QtdParcela[i].value == QtdParcela){
				document.formulario.QtdParcela[i].selected	=	true;
				temp++;
				break;
			}
		}
		if(temp == 0){
			if(document.formulario.QtdParcela.options.length == 0){
				addOption(document.formulario.QtdParcela,"","");
			}
			
			addOption(document.formulario.QtdParcela,QtdParcela,QtdParcela);
			
			contrato_tipo_contrato(document.formulario.TipoContratoTemp.value);
		}
	}
	function contrato_tipo_contrato(TipoContrato){
		var temp	=	0;
		for(i=0;i<document.formulario.TipoContrato.options.length;i++){
			if(document.formulario.TipoContrato[i].value == TipoContrato){
				document.formulario.TipoContrato[i].selected	=	true;
				temp++;
				break;
			}
		}
		if(temp == 0){
			var xmlhttp = false;
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

			url = "xml/parametro_sistema.php?IdGrupoParametroSistema=28&IdParametroSistema="+TipoContrato;
	
			// Carregando...
			carregando(true);
			
			
			xmlhttp.open("GET", url,true);
			xmlhttp.onreadystatechange = function(){ 
				if(xmlhttp.readyState == 4){ 
					if(xmlhttp.status == 200){
						if(xmlhttp.responseText != 'false'){		
							var nameNode, nameTextNode, IdParametroSistema;
							
							if(document.formulario.TipoContrato.options.length == 0){
								addOption(document.formulario.TipoContrato,"","");
							}
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("IdParametroSistema")[0]; 
							nameTextNode = nameNode.childNodes[0];
							IdParametroSistema = nameTextNode.nodeValue;

							nameNode = xmlhttp.responseXML.getElementsByTagName("ValorParametroSistema")[0]; 
							nameTextNode = nameNode.childNodes[0];
							ValorParametroSistema = nameTextNode.nodeValue;

							addOption(document.formulario.TipoContrato,ValorParametroSistema,IdParametroSistema);
							
							contrato_local_cobranca(document.formulario.IdLocalCobrancaTemp.value);
						}
					}
					// Fim de Carregando
					carregando(false);
				}
				return true;
			}
			xmlhttp.send(null);
		}	
	}
	function contrato_local_cobranca(IdLocalCobranca){
		var temp	=	0;
		for(i=0;i<document.formulario.IdLocalCobranca.options.length;i++){
			if(document.formulario.IdLocalCobranca[i].value == IdLocalCobranca){
				document.formulario.IdLocalCobranca[i].selected	=	true;
				temp++;
				break;
			}
		}
		if(temp == 0){
			var xmlhttp = false;
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

		    
		    url = "xml/local_cobranca.php?IdLocalCobranca="+IdLocalCobranca;

			// Carregando...
			carregando(true);
			
			xmlhttp.open("GET", url,true);
			xmlhttp.onreadystatechange = function(){ 
				if(xmlhttp.readyState == 4){ 
					if(xmlhttp.status == 200){
						if(xmlhttp.responseText != 'false'){		
							var nameNode, nameTextNode, IdLocalCobranca;
							
							if(document.formulario.IdLocalCobranca.options.length == 0){
								addOption(document.formulario.IdLocalCobranca,"","");
							}
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("IdLocalCobranca")[0]; 
							nameTextNode = nameNode.childNodes[0];
							IdLocalCobranca = nameTextNode.nodeValue;

							nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoLocalCobranca")[0]; 
							nameTextNode = nameNode.childNodes[0];
							DescricaoLocalCobranca = nameTextNode.nodeValue;

							addOption(document.formulario.IdLocalCobranca,DescricaoLocalCobranca,IdLocalCobranca);
							
							contrato_mes_fechado(document.formulario.MesFechadoTemp.value,document.formulario.QtdMesesFidelidadeTemp.value)
						}
					}
					// Fim de Carregando
					carregando(false);
				}
				return true;
			}
			xmlhttp.send(null);
		}	
	}
	function contrato_mes_fechado(MesFechado,QtdMesesFidelidade){
		var temp	=	0;
		for(i=0;i<document.formulario.MesFechado.options.length;i++){
			if(document.formulario.MesFechado[i].value == MesFechado){
				document.formulario.MesFechado[i].selected	=	true;
				temp++;
				break;
			}
		}
		if(temp == 0){
			var xmlhttp = false;
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
			url = "xml/parametro_sistema.php?IdGrupoParametroSistema=70&IdParametroSistema="+MesFechado;
			
			// Carregando...
			carregando(true);
			
			xmlhttp.open("GET", url,true);
			xmlhttp.onreadystatechange = function(){ 
				if(xmlhttp.readyState == 4){ 
					if(xmlhttp.status == 200){
						if(xmlhttp.responseText != 'false'){		
							var nameNode, nameTextNode, IdPeriodicidade;
							
							if(document.formulario.MesFechado.options.length == 0){
								addOption(document.formulario.MesFechado,"","");
							}
								
							nameNode = xmlhttp.responseXML.getElementsByTagName("IdParametroSistema")[0]; 
							nameTextNode = nameNode.childNodes[0];
							IdParametroSistema = nameTextNode.nodeValue;

							nameNode = xmlhttp.responseXML.getElementsByTagName("ValorParametroSistema")[0]; 
							nameTextNode = nameNode.childNodes[0];
							ValorParametroSistema = nameTextNode.nodeValue;

							addOption(document.formulario.MesFechado,ValorParametroSistema,IdParametroSistema);
							
							document.formulario.QtdMesesFidelidade.value	=	QtdMesesFidelidade;
						}
					}
					// Fim de Carregando
					carregando(false);
				}
				return true;
			}
			xmlhttp.send(null);
		}	
	}
	
	function listarLocalCobrancaParametro(IdLocalCobranca,Erro,IdContrato){
		while(document.getElementById('tabelaParametroLocalCobranca').rows.length > 0){
			document.getElementById('tabelaParametroLocalCobranca').deleteRow(0);
		}
		var tam, linha, c0, c1, c2, c3, c4, c5;
		
		if(IdLocalCobranca == ''){
			IdLocalCobranca = 0;
		}
		if(IdContrato == undefined){
			IdContrato = '';
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
	    if(IdContrato != ""){
    		url = "xml/contrato_local_cobranca_parametro_contrato.php?IdLocalCobranca="+IdLocalCobranca+"&IdStatus=1&IdContrato="+IdContrato;
	    }else{
			url = "xml/local_cobranca_parametro_contrato.php?IdLocalCobranca="+IdLocalCobranca+"&IdStatus=1";
		}
		
		var Local	=	document.formulario.Local.value;
		
		if(Local == 'Contrato' || Local == 'ContratoServico'){
	//		url	+=	'&Visivel=1';	
		}
		if(Local == 'OrdemServico'){
	//		url	+=	'&VisivelOS=1';	
		}
		
		xmlhttp.open("GET", url,true);
 		xmlhttp.onreadystatechange = function(){ 
	
			// Carregando...
			carregando(true);

			if(xmlhttp.readyState == 4){ 
				if(xmlhttp.status == 200){
				
					if(xmlhttp.responseText == 'false'){		
						// Fim de Carregando
						document.getElementById('cp_parametrosLocalCobranca').style.display	=	'none';	
						
						carregando(false);
					}else{
						var padding, visivel,DescricaoParametroContrato, Obrigatorio, ValorDefault, Valor, IdLocalCobrancaParametroContrato, color, salvar;
						
						var obsTemp = new Array(),invisivel="",cont=0;
						for(var i=0; i<xmlhttp.responseXML.getElementsByTagName("IdLocalCobrancaParametroContrato").length; i++){
							nameNode = xmlhttp.responseXML.getElementsByTagName("IdLocalCobrancaParametroContrato")[i]; 
							nameTextNode = nameNode.childNodes[0];
							var IdLocalCobrancaParametroContrato = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoParametroContrato")[i]; 
							nameTextNode = nameNode.childNodes[0];
							var DescricaoParametroContrato = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("Obrigatorio")[i]; 
							nameTextNode = nameNode.childNodes[0];
							var Obrigatorio = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("ValorDefault")[i]; 
							nameTextNode = nameNode.childNodes[0];
							var ValorDefault = nameTextNode.nodeValue;	
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("Valor")[i]; 
							nameTextNode = nameNode.childNodes[0];
							var Valor = nameTextNode.nodeValue;	
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("Editavel")[i]; 
							nameTextNode = nameNode.childNodes[0];
							var Editavel = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("Obs")[i]; 
							nameTextNode = nameNode.childNodes[0];
							var Obs = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("IdTipoParametro")[i]; 
							nameTextNode = nameNode.childNodes[0];
							var IdTipoParametro = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("IdMascaraCampo")[i]; 
							nameTextNode = nameNode.childNodes[0];
							var IdMascaraCampo = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("OpcaoValor")[i]; 
							nameTextNode = nameNode.childNodes[0];
							var OpcaoValor = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("Visivel")[i]; 
							nameTextNode = nameNode.childNodes[0];
							var Visivel = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("VisivelOS")[i]; 
							nameTextNode = nameNode.childNodes[0];
							var VisivelOS = nameTextNode.nodeValue;
							
							
							if(Local == 'OrdemServico'){
								Visivel	=	VisivelOS;	
							}
							
							if(IdContrato!=""){
								ValorDefault	=	Valor;
							}
							
							if(Visivel == '1'){
								tam 	 = document.getElementById('tabelaParametroLocalCobranca').rows.length;
								
								obsTemp[cont]	=	Obs;
								
								if(cont%2 == 0){
									linha	 = document.getElementById('tabelaParametroLocalCobranca').insertRow(tam);
									tabindex = 100 + cont + 1;
									padding	 = 22;	
									pos		 = 0;
								}else{
									padding	 = 10;	
									tabindex = 100 + cont;
									pos		 = 1;
									if(obsTemp[(cont-1)]!= undefined && obsTemp[(cont-1)]!= ''){
										if(Obs	==	'')	Obs	=	'<BR>';
									}
								}
								
								if((cont+1) == xmlhttp.responseXML.getElementsByTagName("IdLocalCobrancaParametroContrato").length && cont%2 == 0){
									padding	 = 22;	
								}
									
								if(Obrigatorio == 1){
									color = "#C10000";
								}else{
									color = "#000000";
								}
								
								if(Editavel == 1){
									visivel	=	'';
								}else{
									visivel	=	'readOnly';
								}
								
								linha.accessKey = IdLocalCobrancaParametroContrato; 
								
								c0	= linha.insertCell(pos);
								
								if(IdTipoParametro == 1){
									switch(IdMascaraCampo){
										case '1':
											c0.innerHTML = "<p style='margin:0; padding-bottom:1px; padding-left:"+padding+"px'><B style='color:"+color+";'>"+DescricaoParametroContrato+"</B></p><p style='padding-bottom:6px; padding-left:"+padding+"px; margin:0;'><input type='text' name='LocalCobranca_Valor_"+IdLocalCobrancaParametroContrato+"' value='"+ValorDefault+"' style='width:399px;' autocomplete='off' onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out');\" "+visivel+" onkeypress=\"mascara(this,event,'date')\" maxlength='10' tabindex="+tabindex+"><input type='hidden' name='LocalCobranca_Obrigatorio_"+IdLocalCobrancaParametroContrato+"' value='"+Obrigatorio+"'><input type='hidden' name='LocalCobranca_Visivel_"+IdLocalCobrancaParametroContrato+"' value='"+Visivel+"'><BR>"+Obs+"</p>";
											break;
										case '2':
											c0.innerHTML = "<p style='margin:0; padding-bottom:1px; padding-left:"+padding+"px'><B style='color:"+color+";'>"+DescricaoParametroContrato+"</B></p><p style='padding-bottom:6px; padding-left:"+padding+"px; margin:0;'><input type='text' name='LocalCobranca_Valor_"+IdLocalCobrancaParametroContrato+"' value='"+ValorDefault+"' style='width:399px;' autocomplete='off' onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out');\" "+visivel+" onkeypress=\"mascara(this,event,'int')\" tabindex="+tabindex+"><input type='hidden' name='LocalCobranca_Obrigatorio_"+IdLocalCobrancaParametroContrato+"' value='"+Obrigatorio+"'><input type='hidden' name='LocalCobranca_Visivel_"+IdLocalCobrancaParametroContrato+"' value='"+Visivel+"'><BR>"+Obs+"</p>";
											break;
										case '3':
											c0.innerHTML = "<p style='margin:0; padding-bottom:1px; padding-left:"+padding+"px'><B style='color:"+color+";'>"+DescricaoParametroContrato+"</B></p><p style='padding-bottom:6px; padding-left:"+padding+"px; margin:0;'><input type='text' name='LocalCobranca_Valor_"+IdLocalCobrancaParametroContrato+"' value='"+ValorDefault+"' style='width:399px;' autocomplete='off' onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out');\" "+visivel+" onkeypress=\"mascara(this,event,'float')\" maxlength='12' tabindex="+tabindex+"><input type='hidden' name='LocalCobranca_Obrigatorio_"+IdLocalCobrancaParametroContrato+"' value='"+Obrigatorio+"'><input type='hidden' name='LocalCobranca_Visivel_"+IdLocalCobrancaParametroContrato+"' value='"+Visivel+"'><BR>"+Obs+"</p>";
											break;
										case '5':
											c0.innerHTML = "<p style='margin:0; padding-bottom:1px; padding-left:"+padding+"px'><B style='color:"+color+";'>"+DescricaoParametroContrato+"</B></p><p style='padding-bottom:6px; padding-left:"+padding+"px; margin:0;'><input type='text' name='LocalCobranca_Valor_"+IdLocalCobrancaParametroContrato+"' value='"+ValorDefault+"' style='width:399px;' autocomplete='off' onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out');\" "+visivel+" onkeypress=\"mascara(this,event,'mac')\" maxlength='17' tabindex="+tabindex+"><input type='hidden' name='LocalCobranca_Obrigatorio_"+IdLocalCobrancaParametroContrato+"' value='"+Obrigatorio+"'><input type='hidden' name='LocalCobranca_Visivel_"+IdLocalCobrancaParametroContrato+"' value='"+Visivel+"'><BR>"+Obs+"</p>";
											break;
										default:
											c0.innerHTML = "<p style='margin:0; padding-bottom:1px; padding-left:"+padding+"px'><B style='color:"+color+";'>"+DescricaoParametroContrato+"</B></p><p style='padding-bottom:6px; padding-left:"+padding+"px; margin:0;'><input type='text' name='LocalCobranca_Valor_"+IdLocalCobrancaParametroContrato+"' value='"+ValorDefault+"' style='width:399px;' autocomplete='off' onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out');\" "+visivel+" tabindex="+tabindex+"><input type='hidden' name='LocalCobranca_Obrigatorio_"+IdLocalCobrancaParametroContrato+"' value='"+Obrigatorio+"'><input type='hidden' name='LocalCobranca_Visivel_"+IdLocalCobrancaParametroContrato+"' value='"+Visivel+"'><BR>"+Obs+"</p>";
									}
								}else{
									campo  = "<p style='margin:0; padding-bottom:1px; padding-left:"+padding+"px'>";
									campo +=	"<B style='color:"+color+";'>"+DescricaoParametroContrato+"</B></p>";
									campo += "<p style='padding-bottom:6px; padding-left:"+padding+"px; margin:0;'>";
									
									if(Editavel == 2){
										disabled	=	"disabled";
									}else{
										disabled	=	"";
									}
									
									campo +=	"<select name='LocalCobranca_Valor_"+IdLocalCobrancaParametroContrato+"'  style='width:406px;' onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out');\" "+visivel+" tabindex="+tabindex+" "+disabled+">";
									campo += "<option value=''></option>";
												
									valor	=	OpcaoValor.split("\n");
									for(var ii=0; ii<valor.length; ii++){
										selecionado = "";
										if(ValorDefault == valor[ii]){
											selecionado	=	"selected=true";
										}
										campo +=	"<option value='"+valor[ii]+"' "+selecionado+">"+valor[ii]+"</option>";
									}
									campo += "</select>";
									campo +=	"<input type='hidden' name='LocalCobranca_Obrigatorio_"+IdLocalCobrancaParametroContrato+"' value='"+Obrigatorio+"'><input type='hidden' name='LocalCobranca_Visivel_"+IdLocalCobrancaParametroContrato+"' value='"+Visivel+"'>";
									campo +=	"<BR>"+Obs+"</p>";
									
									c0.innerHTML	=	campo;
								}
								
								cont++;
							}else{
								invisivel	+=	"<div style='display:none'>";
								if(IdTipoParametro == 1){
									invisivel += "<input type='text' name='LocalCobranca_Valor_"+IdLocalCobrancaParametroContrato+"' value='"+ValorDefault+"' style='width:399px;' autocomplete='off'><input type='hidden' name='LocalCobranca_Obrigatorio_"+IdLocalCobrancaParametroContrato+"' value='"+Obrigatorio+"'><input type='hidden' name='LocalCobranca_Visivel_"+IdLocalCobrancaParametroContrato+"' value='"+Visivel+"'>";
								}else{
									campo  = "";
									campo +=	"<select name='LocalCobranca_Valor_"+IdLocalCobrancaParametroContrato+"'  style='width:406px;'>";
									campo += "<option value=''></option>";
												
									valor	=	OpcaoValor.split("\n");
									for(var ii=0; ii<valor.length; ii++){
										selecionado = "";
										if(ValorDefault == valor[ii]){
											selecionado	=	"selected=true";
										}
										campo +=	"<option value='"+valor[ii]+"' "+selecionado+">"+valor[ii]+"</option>";
									}
									campo += "</select>";
									campo +=	"<input type='hidden' name='LocalCobranca_Obrigatorio_"+IdLocalCobrancaParametroContrato+"' value='"+Obrigatorio+"'><input type='hidden' name='LocalCobranca_Visivel_"+IdLocalCobrancaParametroContrato+"' value='"+Visivel+"'>";
									
									invisivel	+=	campo;
								}
							
							
								invisivel	+=	"</div>";
							}
						}
						
						if(cont > 0){
							document.getElementById('cp_parametrosLocalCobranca').style.display			=	'block';	
							document.getElementById('tabelaParametroLocalCobranca').style.display		=	'block';		
						}
						if(invisivel !=""){
							tam 	 = document.getElementById('tabelaParametroLocalCobranca').rows.length;
							linha	 = document.getElementById('tabelaParametroLocalCobranca').insertRow(tam);
								
							linha.accessKey = IdLocalCobrancaParametroContrato; 
								
							c0	= linha.insertCell(0);
							c0.innerHTML	=	invisivel;
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
	


	
