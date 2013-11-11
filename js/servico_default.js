	function janela_busca_servico(IdServico,Local){
		if(document.formulario.Local.value == 'Contrato'){	
			if(document.formulario.IdContrato.value != ''){
				return false;
			}else{
				if(document.formulario.IdPessoa.value == '' && document.formulario.IdPessoaF.value == ''){
					document.formulario.IdPessoa.focus();
					mensagens(104);	
					return false;
				}
				janelas('busca_servico.php?Local='+document.formulario.Local.value,530,283,250,100,'');
			}
		}else{
			if(IdServico == undefined)	IdServico = '';
			if(Local == undefined)		Local 	  = '';
			if(Local == 'OrdemServico'){
				if(document.formulario.IdPessoa.value == ''){
					return false;
				}else{
					janelas('busca_servico.php?Local='+Local,530,283,250,100,'');
				}
			}else{
				janelas('busca_servico.php?IdServico='+IdServico+'&Local='+Local,530,283,250,100,'');
			}
		}
	}
	function busca_servico(IdServico,Erro,Local,ListarCampo){
		if(IdServico == '' || IdServico == undefined){
			IdServico = 0;
		}
		if(Local == '' || Local == undefined){
			Local	=	document.formulario.Local.value;
		}
		if(ListarCampo == undefined) ListarCampo = "";
		
		if(document.formulario.Local.value == 'Contrato'){
			if(document.formulario.IdPessoa.value == '' && document.formulario.IdPessoaF.value == ''){
				document.formulario.IdServico.value = "";
				document.formulario.IdPessoa.focus();
				mensagens(104);
				return false;
			}
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
	    
	   	url = "xml/servico.php?IdServico="+IdServico+"&Local="+Local;
		
		if(document.formulario.IdServicoAnterior != undefined){
			url	+=	"&IdServicoAlterar="+document.formulario.IdServicoAnterior.value;
		}
		if(Local == 'ServicoAgrupador'){
			if(document.formulario.IdServico.value != ''){
				url	+=	"&IdServicoAgrupador="+document.formulario.IdServico.value;
			}
			if(document.formulario.IdTipoServico.value == 3 || document.formulario.IdTipoServico.value == 4){
				url	+=	"&IdTipoServico=1";
			}
		}
		if((document.formulario.Local.value == 'Contrato' && document.formulario.Acao.value == 'inserir') || document.formulario.Local.value == 'ContratoServico'){
			var IdPessoa	=	document.formulario.IdPessoa.value;
			
			if(IdPessoa == ""){
				IdPessoa	=	document.formulario.IdPessoaF.value;
			}
			
			
			url	+=	"&IdPessoa="+IdPessoa+"&IdTipoServico=1&IdStatus=1";
		}		
		if(Local == 'OrdemServico'){			
			if(document.formulario.IdContrato.value != ""){
				url	+=	"&IdTipoServico=3"; /*Agrupado*/
				url	+=	"&IdContrato="+document.formulario.IdContrato.value;	
			}else{
				url	+=	"&IdTipoServico=2";	/*Eventual*/
			}
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
						switch(Local){
							case "Contrato": 
								document.formulario.IdServico.value				=	"";
								document.formulario.DescricaoServico.value		=	"";
								document.formulario.ValorServico.value			=	'';
								document.formulario.ValorPeriodicidade.value	=	'';
								document.formulario.ValorRepasseTerceiro.value	=	'';
								document.formulario.MultaFidelidade.value		=	"";
								document.formulario.ValorMultaFidelidade.value	=	"";
								document.formulario.QtdMesesFidelidade.value	=	"";
								document.formulario.QtdMesesFidelidadeTemp.value=	"";
								document.formulario.ServicoAutomatico.value		=	"";
								document.formulario.Periodicidade.value			=	"";
								document.formulario.QuantParcela.value			=	"";
								document.formulario.TipoContratoTemp.value		=	"";
								document.formulario.IdLocalCobrancaTemp.value	=	"";
								document.formulario.MesFechadoTemp.value		=	"";
								document.formulario.UrlContratoImpresso.value	=	"";
								document.formulario.UrlDistratoImpresso.value	=	"";
								
								while(document.getElementById('tabelaParametro').rows.length > 0){
									document.getElementById('tabelaParametro').deleteRow(0);
								}
								while(document.getElementById('tabelaParametroLocalCobranca').rows.length > 0){
									document.getElementById('tabelaParametroLocalCobranca').deleteRow(0);
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
								document.getElementById('cpQtdParcela').style.color			=	'#C10000';
								document.getElementById('cpTipoContrato').style.color		=	'#C10000';								
								document.getElementById('cpLocalCobranca').style.color		=	'#C10000';								
								document.getElementById('cpMesFechado').style.color			=	'#C10000';
								document.getElementById('cp_automatico').style.display		=	'none';
								document.getElementById('cp_parametrosServico').style.display	=	'none';
								document.getElementById('cp_parametrosLocalCobranca').style.display	=	'none';
								document.getElementById('cp_automatico').innerHTML			=	"";
								
								if(document.formulario.Acao.value == 'inserir'){
									document.formulario.IdServico.focus();
								}
								break;
							case "ContratoServico": 	
								document.formulario.IdServico.value						=	"";
								document.formulario.DescricaoServico.value				=	"";
								document.formulario.ValorServico.value					=	'';
								document.formulario.ValorPeriodicidade.value			=	'';
								document.formulario.ValorRepasseTerceiro.value			=	'';
								document.formulario.MultaFidelidade.value				=	"";
								document.formulario.ValorMultaFidelidade.value			=	"";
								document.formulario.IdCarteira.value					=	"";
								document.formulario.IdAgenteAutorizado.value			=	"";
								document.formulario.NomeAgenteAutorizado.value			=	"";
								
								while(document.getElementById('tabelaParametro').rows.length > 0){
									document.getElementById('tabelaParametro').deleteRow(0);
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
								document.getElementById('cpQtdParcela').style.color			=	'#C10000';
								document.getElementById('cpTipoContrato').style.color		=	'#C10000';								
								document.getElementById('cpLocalCobranca').style.color		=	'#C10000';								
								document.getElementById('cpMesFechado').style.color			=	'#C10000';
								document.getElementById('cp_automatico').style.display		=	'none';
								document.getElementById('cp_automatico').innerHTML			=	"";
								document.getElementById('cp_parametrosServico').style.display	=	'none';
								
								document.getElementById("cp_credito").style.display 	=	"none";
		
								limpaFormCredito();
								
								document.formulario.IdServico.focus();
								verificaAcao();
								break;	
							case "Servico":
								addParmUrl("marServico","IdServico","");
								addParmUrl("marServicoValor","IdServico","");
								addParmUrl("marServicoValorNovo","IdServico","");
								addParmUrl("marServicoParametro","IdServico","");
								addParmUrl("marServicoParametroNovo","IdServico","");
								addParmUrl("marServicoRotina","IdServico","");
								
								document.formulario.IdServico.value						=	"";
								document.formulario.DescricaoServico.value				=	"";
								document.formulario.IdServicoGrupo.value				=	"";
								document.formulario.IdTipoServico[0].selected			=	true;
								document.formulario.DescricaoServicoGrupo.value			=	"";
								document.formulario.IdPlanoConta.value					=	"";
								document.formulario.DescricaoPlanoConta.value			=	"";
								document.formulario.IdCentroCusto.value					=	"";
								document.formulario.DescricaoCentroCusto.value			=	"";
								document.formulario.AgruparLancamentosFinanceiros.value	=	"";
								document.formulario.ExibirReferencia.value				=	"";
								document.formulario.Periodicidade.value					=	"";
								document.formulario.IdPeriodicidade.value				=	"";
								document.formulario.QtdParcelaMaximo.value				=	"";
								document.formulario.AtivacaoAutomatica.value			=	"";
								document.formulario.AtivacaoAutomaticaTemp.value		=	"";
								document.formulario.ContratoImpresso.value				=	"";
								document.formulario.IdPessoa.value						=	"";
								document.formulario.IdLocalCobranca[0].selected			=	true;
								document.formulario.TipoContrato[0].selected			=	true;
								document.formulario.MesFechado[0].selected				=	true;
								document.formulario.EmailCobranca[0].selected			=	true;
								document.formulario.ExecutarRotinas[0].selected			=	true;
								document.formulario.EmailCobrancaTemp.value				=	"";
								document.formulario.IdServicoAgrupador.value			=	"";
								document.formulario.DescricaoServicoAgrupador.value		=	"";
								document.formulario.DiasLimiteBloqueio.value			=	"";
								document.formulario.QtdMesesFidelidade.value			=	"";
								document.formulario.Nome.value							=	"";
								document.formulario.ValorRepasseTerceiro.value			=	"";
								document.formulario.DetalheServico.value				=	"";
								document.formulario.DataCriacao.value					=	"";
								document.formulario.LoginCriacao.value					=	"";
								document.formulario.DataAlteracao.value					=	"";
								document.formulario.LoginAlteracao.value				=	"";
								document.formulario.QuantPeriodicidade.value			=	"";
								document.formulario.ValorInicial.value					=	"";
								document.formulario.ServicoAgrupador.value				=	"";
								document.formulario.DiasAvisoAposVencimento.value		=	"";
								document.formulario.ValorInicial.readOnly				=	false;
								document.formulario.Filtro_IdPaisEstadoCidade.value 	= 	"";
								document.formulario.MsgAuxiliarCobranca.value			= 	"";
								
								status_inicial();
								
								while(document.getElementById('tabelaPeriodicidade').rows.length > 2){
									document.getElementById('tabelaPeriodicidade').deleteRow(1);
								}
								while(document.getElementById('tabelaCidade').rows.length > 2){
									document.getElementById('tabelaCidade').deleteRow(1);
								}
								while(document.formulario.IdPeriodicidade.options.length > 0){
									document.formulario.IdPeriodicidade.options[0] = null;
								}
								while(document.formulario.QtdParcelaMaximo.options.length > 0){
									document.formulario.QtdParcelaMaximo.options[0] = null;
								}
								
								document.getElementById('cpValorInicial').style.color				=	'#C10000';
								document.getElementById('cpValorInicialMoeda').style.color			=	'#C10000';
								document.getElementById('cpServicoImportar').style.display			=	'block';
								document.getElementById('totaltabelaPeriodicidade').innerHTML		=	'Total: 0';
								document.getElementById('totaltabelaServico').innerHTML				=	'Total: 0';
								document.getElementById('cpPeriodicidade').style.display			=	'none';
								document.getElementById('cpAgrupado').style.display					=	'none';
								document.getElementById('totaltabelaCidade').innerHTML				= "Total: 0";
								
								document.getElementById('cpValorInicial').innerHTML					=	'Valor Inicial Mensal';		
								
								document.formulario.IdServicoImportar.value				=	"";
								document.formulario.DescricaoServicoImportar.value		=	"";
								
								document.formulario.Acao.value 							= 'inserir';
								document.formulario.IdServico.focus();
								
								verificaAcao();
								break;
							case "ServicoValor": 
								addParmUrl("marServico","IdServico","");
								addParmUrl("marServicoValor","IdServico","");
								addParmUrl("marServicoValorNovo","IdServico","");
								addParmUrl("marServicoParametro","IdServico","");
								addParmUrl("marServicoParametroNovo","IdServico","");
								addParmUrl("marServicoRotina","IdServico","");
								
								document.getElementById('tabelaValorValor').innerHTML			=	"0,00";	
								document.getElementById('tabelaValorRep').innerHTML				=	"0,00";	
								document.getElementById('tabelaValorTotal').innerHTML			=	"Total: 0";
								
								while(document.getElementById('tabelaValor').rows.length > 2){
									document.getElementById('tabelaValor').deleteRow(1);
								}
								
								document.formulario.IdServico.value					=	"";
								document.formulario.DescricaoServico.value			=	"";
								document.formulario.IdTipoServico.value				=	"";
								document.formulario.DataTermino.value				=	"";
								document.formulario.DescricaoServicoValor.value		=	"";
								document.formulario.Valor.value						=	"";
								document.formulario.ValorRepasseTerceiro.value		=	"";
								document.formulario.DataCriacao.value				= 	"";
								document.formulario.LoginCriacao.value				= 	"";
								document.formulario.DataAlteracao.value				= 	"";
								document.formulario.LoginAlteracao.value			= 	"";
								document.formulario.maxQtdMesesFidelidade.value		= 	"";
								
								document.formulario.Acao.value						= 'inserir';
								
								addParmUrl("marServicoValorNovo","DataInicio","");
								
								status_inicial();
								document.getElementById('tabelahelpText2').style.display	=	'none';
								
								document.formulario.IdServico.focus();
								
								break;
							case "ServicoParametro": 
								addParmUrl("marServico","IdServico","");
								addParmUrl("marServicoValor","IdServico","");
								addParmUrl("marServicoValorNovo","IdServico","");
								addParmUrl("marServicoParametro","IdServico","");
								addParmUrl("marServicoParametroNovo","IdServico","");
								addParmUrl("marServicoParametroNovo","IdServico","");
								addParmUrl("marServicoRotina","IdServico","");
								
								document.getElementById('tabelaParametroTotal').innerHTML			=	"Total: 0";
								
								while(document.getElementById('tabelaParametro').rows.length > 2){
									document.getElementById('tabelaParametro').deleteRow(1);
								}
								
								document.formulario.IdServico.value						=	"";
								document.formulario.DescricaoServico.value				=	"";
								document.formulario.IdParametroServico.value			=	"";
								document.formulario.DescricaoParametroServico.value		=	"";
								document.formulario.Obrigatorio[0].selected 			= 	true;
								document.formulario.ObrigatorioStatus.value 			= 	"";
								document.formulario.Calculavel[0].selected 				= 	true;
								document.formulario.Obs.value							=	"";
								document.formulario.ValorDefaultInput.value				=	"";
								document.formulario.IdStatusParametro[0].selected 		= 	true;
								document.formulario.RotinaCalculo.value					=	"";	
								document.formulario.IdTipoParametro.value				=	"";		
								document.formulario.IdMascaraCampo.value				=	"";		
								document.formulario.OpcaoValor.value					=	"";					
								document.formulario.DataCriacao.value					=	"";
								document.formulario.LoginCriacao.value					=	"";
								document.formulario.DataAlteracao.value					=	"";
								document.formulario.LoginAlteracao.value				=	"";
								document.formulario.Obrigatorio.disabled				=	false;
								
								addParmUrl("marServicoParametroNovo","IdParametroServico","");
								
								while(document.formulario.ValorDefaultSelect.options.length > 0){
									document.formulario.ValorDefaultSelect.options[0] = null;
								}
					
								verificaTipoParametro();
								status_inicial();
								
								document.getElementById('tabelahelpText2').style.display	=	'none';
								document.getElementById('cpRotinaCalculo').style.display	=	'none';
								
								document.formulario.Acao.value						= 	'inserir';
								document.formulario.IdServico.focus();
								mensagens(0);
								break;	
							case "ServicoRotina": 
								addParmUrl("marServico","IdServico","");
								addParmUrl("marServicoValor","IdServico","");
								addParmUrl("marServicoValorNovo","IdServico","");
								addParmUrl("marServicoParametro","IdServico","");
								addParmUrl("marServicoParametroNovo","IdServico","");
								addParmUrl("marServicoRotina","IdServico","");
								
								document.formulario.IdServico.value				= "";
								document.formulario.DescricaoServico.value		= "";
								document.formulario.UrlRotinaBloqueio.value		= "";
								document.formulario.UrlContratoImpresso.value	= "";
								document.formulario.UrlRotinaDesbloqueio.value	= "";
								document.formulario.UrlRotinaCriacao.value		= "";
								document.formulario.UrlRotinaCancelamento.value	= "";	
								document.formulario.UrlRotinaAlteracao.value	= "";
								document.formulario.UrlDistratoImpresso.value	= "";	
								document.formulario.DataCriacao.value			= "";
								document.formulario.LoginCriacao.value			= "";
								document.formulario.DataAlteracao.value			= "";
								document.formulario.LoginAlteracao.value		= "";
								
								document.getElementById('LinkUrlContrato').innerHTML 	   = "<img src='../../img/estrutura_sistema/ico_ie.gif' alt='Visualizar'>";
								document.getElementById('LinkUrlDistratoImpresso').innerHTML = "<img src='../../img/estrutura_sistema/ico_ie.gif' alt='Visualizar'>";
								document.formulario.IdServico.focus();
								mensagens(0);
								verificaAcao();
								break;
							case "OrdemServico": 
								while(document.getElementById('tabelaParametro').rows.length > 1){
									document.getElementById('tabelaParametro').deleteRow(0);
								}
								
								if(document.getElementById('tabelaParametroContrato').style.display == 'none' || document.getElementById('tabelaParametroContrato').style.display == ''){
									document.getElementById('cp_parametrosServico').style.display	= 'none';
								}
								
								document.formulario.IdServico.value					=	"";
								document.formulario.DescricaoServico.value			=	"";
								document.formulario.IdTipoServico.value				= 	"";
								document.formulario.DetalheServico.value			=	"";

								if(document.formulario.IdTipoOrdemServico.value != 2 && document.formulario.Acao.value == 'inserir'){
									document.formulario.IdServico.focus();
								}
								break;
							case "OrdemServicoFatura": 
								document.formulario.IdServico.value					=	"";
								document.formulario.DescricaoServico.value			=	"";
								document.formulario.IdTipoServico.value				= 	"";
								document.formulario.DetalheServico.value			=	"";

								if(document.formulario.IdTipoOrdemServico.value != 2 && document.formulario.Acao.value == 'inserir'){
									document.formulario.IdServico.focus();
								}
								break;
							case "ServicoImportar":
								document.formulario.IdServicoImportar.value				=	"";
								document.formulario.DescricaoServicoImportar.value		=	"";
								
								document.formulario.IdServicoImportar.focus();
								break;
							case "ServicoAgrupador":
								document.formulario.IdServicoAgrupador.value			=	"";
								document.formulario.DescricaoServicoAgrupador.value		=	"";
								
								document.formulario.IdServicoAgrupador.focus();
								break;							
							case 'ProcessoFinanceiro':
								/*Naum fazer nda!*/
								break;
							default:
								document.formulario.IdServico.value				=	"";
								document.formulario.DescricaoServico.value		=	"";
								
								document.formulario.IdServico.focus();
								break;
						}
						// Fim de Carregando
						carregando(false);
					}else{
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("IdServico")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var IdServico = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("IdTipoServico")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var IdTipoServico = nameTextNode.nodeValue;	
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoServico")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var DescricaoServico = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("Valor")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var Valor = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("DetalheServico")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var DetalheServico = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("ValorRepasseTerceiro")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var ValorRepasseTerceiro = nameTextNode.nodeValue;
						
						switch(Local){
							case "Contrato":
								document.getElementById('cp_parametrosServico').style.display		=	'none';		
								
								nameNode = xmlhttp.responseXML.getElementsByTagName("MultaFidelidade")[0]; 
								nameTextNode = nameNode.childNodes[0];
								var MultaFidelidade = nameTextNode.nodeValue;
								
								nameNode = xmlhttp.responseXML.getElementsByTagName("ServicoAutomatico")[0]; 
								nameTextNode = nameNode.childNodes[0];
								var ServicoAutomatico = nameTextNode.nodeValue;
								
								nameNode = xmlhttp.responseXML.getElementsByTagName("UrlContratoImpresso")[0]; 
								nameTextNode = nameNode.childNodes[0];
								var UrlContratoImpresso = nameTextNode.nodeValue;
								
								nameNode = xmlhttp.responseXML.getElementsByTagName("UrlDistratoImpresso")[0]; 
								nameTextNode = nameNode.childNodes[0];
								var UrlDistratoImpresso = nameTextNode.nodeValue;
								
								if(Valor == ''){				Valor = '0';	}
								if(ValorRepasseTerceiro == ''){	ValorRepasseTerceiro = '0';	}
							
								document.formulario.ValorServico.value			=	formata_float(Arredonda(Valor,2),2).replace('.',',');
								document.formulario.ValorRepasseTerceiro.value	=	formata_float(Arredonda(ValorRepasseTerceiro,2),2).replace('.',',');
								document.formulario.ValorMultaFidelidade.value	=	formata_float(Arredonda(MultaFidelidade,2),2).replace('.',',');
								document.formulario.MultaFidelidade.value		=	MultaFidelidade;
								
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
								
								document.formulario.IdServico.value						=	IdServico;
								document.formulario.DescricaoServico.value				=	DescricaoServico;
								document.formulario.ServicoAutomatico.value				=	ServicoAutomatico;
								document.formulario.UrlContratoImpresso.value			=	UrlContratoImpresso;
								document.formulario.UrlDistratoImpresso.value			=	UrlDistratoImpresso;
								
								periodicidade(document.formulario.IdServico.value,ListarCampo);
								listarServicoParametro(document.formulario.IdServico.value,true);
								
								document.getElementById('cp_automatico').innerHTML		=	"";
								document.getElementById('cp_automatico').style.display	=	'none';
								
								if(ServicoAutomatico != ''){
									busca_automatico(document.formulario.ServicoAutomatico.value);
								}
								break;
							case "ServicoAutomatico":
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
								
								for(ii=posIni;ii<=posFim;i++){
									if(document.formulario[ii].name.substring(0,10)	==	"IdServico_"){
										temp	=	document.formulario[ii].name.split("_");
										if(temp[1] == IdServico){
											document.formulario[ii+1].value	=	DescricaoServico;
											document.formulario[ii+2].value	=	formata_float(Arredonda(Valor,2),2).replace('.',',');
											break;
										}
									}
								}
								
								break;
							case "ContratoServico":
								document.getElementById('cp_parametrosServico').style.display		=	'none';
								document.getElementById('cp_parametrosLocalCobranca').style.display	=	'none';
								
								nameNode = xmlhttp.responseXML.getElementsByTagName("MultaFidelidade")[0]; 
								nameTextNode = nameNode.childNodes[0];
								var MultaFidelidade = nameTextNode.nodeValue;
								
								nameNode = xmlhttp.responseXML.getElementsByTagName("ServicoAutomatico")[0]; 
								nameTextNode = nameNode.childNodes[0];
								var ServicoAutomatico = nameTextNode.nodeValue;
								
								//document.formulario.ParametrosAnterior.value	=	"";
								
								//listarParametroAnterior(document.formulario.IdServicoAnterior.value,document.formulario.IdContrato.value);
								listarParametroAnteriorContrato(IdServico,false,document.formulario.IdContrato.value);
								
								if(Valor == ''){				Valor = '0';	}
								if(ValorRepasseTerceiro == ''){	ValorRepasseTerceiro = '0';	}
							
								document.formulario.IdServico.value				=	IdServico;
								document.formulario.DescricaoServico.value		=	DescricaoServico;
								document.formulario.ServicoAutomatico.value		=	ServicoAutomatico;
								document.formulario.ValorServico.value			=	formata_float(Arredonda(Valor,2),2).replace('.',',');
								document.formulario.ValorRepasseTerceiro.value	=	formata_float(Arredonda(ValorRepasseTerceiro,2),2).replace('.',',');
								document.formulario.ValorMultaFidelidade.value	=	formata_float(Arredonda(MultaFidelidade,2),2).replace('.',',');
								document.formulario.MultaFidelidade.value		=	MultaFidelidade;
								
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
								
								periodicidade(document.formulario.IdServico.value,ListarCampo);
								
								document.getElementById('cp_automatico').innerHTML		=	"";
								document.getElementById('cp_automatico').style.display	=	'none';
								
								if(ServicoAutomatico != ''){
									busca_automatico(document.formulario.ServicoAutomatico.value);
								}
								
								
								verificaCredito(IdServico,document.formulario.DataBaseCalculo.value,document.formulario.DataCancelamento.value);
								break;	
							case "Servico":
								addParmUrl("marServico","IdServico",IdServico);
								addParmUrl("marServicoValor","IdServico",IdServico);
								addParmUrl("marServicoValorNovo","IdServico",IdServico);
								addParmUrl("marServicoParametro","IdServico",IdServico);
								addParmUrl("marServicoParametroNovo","IdServico",IdServico);
								addParmUrl("marServicoRotina","IdServico",IdServico);
							
								nameNode = xmlhttp.responseXML.getElementsByTagName("IdServicoGrupo")[0]; 
								nameTextNode = nameNode.childNodes[0];
								var IdServicoGrupo = nameTextNode.nodeValue;	
								
								nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoServicoGrupo")[0]; 
								nameTextNode = nameNode.childNodes[0];
								var DescricaoServicoGrupo = nameTextNode.nodeValue;
																
								nameNode = xmlhttp.responseXML.getElementsByTagName("IdCentroCusto")[0]; 
								nameTextNode = nameNode.childNodes[0];
								var IdCentroCusto = nameTextNode.nodeValue;
								
								nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoCentroCusto")[0]; 
								nameTextNode = nameNode.childNodes[0];
								var DescricaoCentroCusto = nameTextNode.nodeValue;
								
								nameNode = xmlhttp.responseXML.getElementsByTagName("IdPlanoConta")[0]; 
								nameTextNode = nameNode.childNodes[0];
								var IdPlanoConta = nameTextNode.nodeValue;
								
								nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoPlanoConta")[0]; 
								nameTextNode = nameNode.childNodes[0];
								var DescricaoPlanoConta = nameTextNode.nodeValue;
								
								nameNode = xmlhttp.responseXML.getElementsByTagName("ContratoImpresso")[0]; 
								nameTextNode = nameNode.childNodes[0];
								var ContratoImpresso = nameTextNode.nodeValue;					
						
								nameNode = xmlhttp.responseXML.getElementsByTagName("IdStatus")[0]; 
								nameTextNode = nameNode.childNodes[0];
								var IdStatus = nameTextNode.nodeValue;
								
								nameNode = xmlhttp.responseXML.getElementsByTagName("IdTerceiro")[0]; 
								nameTextNode = nameNode.childNodes[0];
								var IdTerceiro = nameTextNode.nodeValue;			
							
								nameNode = xmlhttp.responseXML.getElementsByTagName("Nome")[0]; 
								nameTextNode = nameNode.childNodes[0];
								var Nome = nameTextNode.nodeValue;
								
								nameNode = xmlhttp.responseXML.getElementsByTagName("AtivacaoAutomatica")[0]; 
								nameTextNode = nameNode.childNodes[0];
								var AtivacaoAutomatica = nameTextNode.nodeValue;
								
								nameNode = xmlhttp.responseXML.getElementsByTagName("ExibirReferencia")[0]; 
								nameTextNode = nameNode.childNodes[0];
								var ExibirReferencia = nameTextNode.nodeValue;
								
								nameNode = xmlhttp.responseXML.getElementsByTagName("AgruparLancamentosFinanceiros")[0]; 
								nameTextNode = nameNode.childNodes[0];
								var AgruparLancamentosFinanceiros = nameTextNode.nodeValue;
								
								nameNode = xmlhttp.responseXML.getElementsByTagName("DiasAvisoAposVencimento")[0]; 
								nameTextNode = nameNode.childNodes[0];
								var DiasAvisoAposVencimento = nameTextNode.nodeValue;
								
								nameNode = xmlhttp.responseXML.getElementsByTagName("DiasLimiteBloqueio")[0]; 
								nameTextNode = nameNode.childNodes[0];
								var DiasLimiteBloqueio = nameTextNode.nodeValue;
								
								nameNode = xmlhttp.responseXML.getElementsByTagName("Filtro_IdPaisEstadoCidade")[0]; 
								nameTextNode = nameNode.childNodes[0];
								var Filtro_IdPaisEstadoCidade = nameTextNode.nodeValue;
								
								nameNode = xmlhttp.responseXML.getElementsByTagName("MsgAuxiliarCobranca")[0]; 
								nameTextNode = nameNode.childNodes[0];
								var MsgAuxiliarCobranca = nameTextNode.nodeValue;
								
								nameNode = xmlhttp.responseXML.getElementsByTagName("EmailCobranca")[0]; 
								nameTextNode = nameNode.childNodes[0];
								var EmailCobranca = nameTextNode.nodeValue;
								
								nameNode = xmlhttp.responseXML.getElementsByTagName("ExecutarRotinas")[0]; 
								nameTextNode = nameNode.childNodes[0];
								var ExecutarRotinas = nameTextNode.nodeValue;
															
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
								
								document.formulario.IdServico.value						=	IdServico;
								document.formulario.DescricaoServico.value				=	DescricaoServico;
								document.formulario.IdTipoServico.value					=	IdTipoServico;
								document.formulario.IdServicoGrupo.value				=	IdServicoGrupo;
								document.formulario.IdPlanoConta.value					=	IdPlanoConta;
								document.formulario.DescricaoPlanoConta.value			=	DescricaoPlanoConta;
								document.formulario.IdCentroCusto.value					=	IdCentroCusto;
								document.formulario.DescricaoCentroCusto.value			=	DescricaoCentroCusto;
								document.formulario.DescricaoServicoGrupo.value			=	DescricaoServicoGrupo;
								document.formulario.ContratoImpresso.value				=	ContratoImpresso;
								document.formulario.IdPessoa.value						=	IdTerceiro;
								document.formulario.Nome.value							=	Nome;								
								document.formulario.ExibirReferencia.value				=	ExibirReferencia;
								document.formulario.ValorInicial.readOnly				=	true;
								document.formulario.ValorInicial.value					=	formata_float(Arredonda(Valor,2),2).replace('.',',');
								document.formulario.AtivacaoAutomatica.value			=	AtivacaoAutomatica;
								document.formulario.AtivacaoAutomaticaTemp.value		=	AtivacaoAutomatica;
								document.formulario.DetalheServico.value				=	DetalheServico;
								document.formulario.AgruparLancamentosFinanceiros.value	=	AgruparLancamentosFinanceiros;								
								document.formulario.DiasAvisoAposVencimento.value		=	DiasAvisoAposVencimento;
								document.formulario.Filtro_IdPaisEstadoCidade.value 	= 	Filtro_IdPaisEstadoCidade;
								document.formulario.MsgAuxiliarCobranca.value			=	MsgAuxiliarCobranca;
								document.formulario.DataCriacao.value					=	dateFormat(DataCriacao);
								document.formulario.LoginCriacao.value					=	LoginCriacao;
								document.formulario.DataAlteracao.value					=	dateFormat(DataAlteracao);
								document.formulario.LoginAlteracao.value				=	LoginAlteracao;
								document.formulario.EmailCobranca.value					=	EmailCobranca;
								document.formulario.EmailCobrancaTemp.value				=	EmailCobranca;
								document.formulario.ExecutarRotinas.value				=	ExecutarRotinas;
								document.formulario.DiasLimiteBloqueio.value			=	DiasLimiteBloqueio;
								document.formulario.Periodicidade.value					=	"";
								document.formulario.IdPeriodicidade.value				=	"";
								document.formulario.QtdParcelaMaximo.value				=	"";
								document.formulario.QuantPeriodicidade.value			=	"";
								document.formulario.IdLocalCobranca.value				=	"";
								document.formulario.TipoContrato.value					=	"";
								document.formulario.IdServicoAgrupador.value			=	"";
								document.formulario.DescricaoServicoAgrupador.value		=	"";
								document.formulario.ServicoAgrupador.value				=	"";
								document.formulario.ValorRepasseTerceiro.readOnly		=	true;
								
								if(IdTerceiro != ''){
									document.formulario.ValorRepasseTerceiro.value			=	formata_float(Arredonda(ValorRepasseTerceiro,2),2).replace('.',',');
								}else{
									document.formulario.ValorRepasseTerceiro.value			=	"";
								}
								
								document.getElementById('cpValorInicial').style.color				=	'#000';
								document.getElementById('cpValorInicialMoeda').style.color			=	'#000';
								document.getElementById('cpServicoImportar').style.display			=	'none';
								document.getElementById('totaltabelaPeriodicidade').innerHTML		=	'Total: 0';
								document.getElementById('totaltabelaServico').innerHTML				=	'Total: 0';
								
								document.getElementById('cpValorInicial').innerHTML					=	'Valor Mensal Atual';
								
								for(var i=0; i<document.formulario.ContratoImpresso.length; i++){
									if(document.formulario.ContratoImpresso[i].value == ContratoImpresso){
										document.formulario.ContratoImpresso[i].selected = true;
										i = document.formulario.ContratoImpresso.length;
									}							
								}
								
								for(var i=0; i<document.formulario.AtivacaoAutomatica.length; i++){
									if(document.formulario.AtivacaoAutomatica[i].value == AtivacaoAutomatica){
										document.formulario.AtivacaoAutomatica[i].selected = true;
										i = document.formulario.AtivacaoAutomatica.length;
									}							
								}
								
								for(var i=0; i<document.formulario.IdStatus.length; i++){
									if(document.formulario.IdStatus[i].value == IdStatus){
										document.formulario.IdStatus[i].selected = true;
										i = document.formulario.IdStatus.length;
									}							
								}
								
								while(document.getElementById('tabelaServico').rows.length > 2){
									document.getElementById('tabelaServico').deleteRow(1);
								}
								while(document.getElementById('tabelaCidade').rows.length > 2){
									document.getElementById('tabelaCidade').deleteRow(1);
								}
								while(document.getElementById('tabelaPeriodicidade').rows.length > 2){
									document.getElementById('tabelaPeriodicidade').deleteRow(1);
								}
								
								for(var i=0; i<document.formulario.AgruparLancamentosFinanceiros.length; i++){
									if(document.formulario.AgruparLancamentosFinanceiros[i].value == AgruparLancamentosFinanceiros){
										document.formulario.AgruparLancamentosFinanceiros[i].selected = true;
										i = document.formulario.AgruparLancamentosFinanceiros.length;
									}							
								}
								
								Valor	=	Valor.replace('.',',');
								
								verificaTipoServico(IdTipoServico);
								
								switch(IdTipoServico){
									case '1':
										busca_periodicidade(IdServico,Valor);
										break;
									case '3':
										busca_servico_agrupado(IdServico);
										break;
									case '4':
										busca_servico_agrupado(IdServico);
										break;
								}
								
								//############# Filtro_IdPaisEstadoCidade ###########################
								if(Filtro_IdPaisEstadoCidade != ""){
									var temp3 = Filtro_IdPaisEstadoCidade;
									
									var tempFiltro3	=	temp3.split('^');
									var ii3=0;
									if(Filtro_IdPaisEstadoCidade != ''){
										while(tempFiltro3[ii3] != undefined){
											tempFiltro3[ii3]	=	tempFiltro3[ii3].split(',')
											adicionar_cidade(tempFiltro3[ii3][0],tempFiltro3[ii3][1],tempFiltro3[ii3][2],listar);
											ii3++;
										}
									}
									document.getElementById('totaltabelaCidade').innerHTML	=	"Total: "+ii3;
								}
								
								document.formulario.Acao.value 		= 'alterar';
								
								if(document.formulario.Erro.value!='0'){
								//	scrollWindow("bottom");
								}
								verificaAcao();
								break;
							case "ServicoValor": 	
								nameNode = xmlhttp.responseXML.getElementsByTagName("maxQtdMesesFidelidade")[0]; 
								nameTextNode = nameNode.childNodes[0];
								var maxQtdMesesFidelidade = nameTextNode.nodeValue;
							
								addParmUrl("marServico","IdServico",IdServico);
								addParmUrl("marServicoValor","IdServico",IdServico);
								addParmUrl("marServicoValorNovo","IdServico",IdServico);
								addParmUrl("marServicoParametro","IdServico",IdServico);
								addParmUrl("marServicoParametroNovo","IdServico",IdServico);
								addParmUrl("marServicoRotina","IdServico",IdServico);
								
								listarServicoValor(IdServico,false);
								
								document.formulario.IdServico.value					=	IdServico;
								document.formulario.IdTipoServico.value				=	IdTipoServico;
								document.formulario.DescricaoServico.value			=	DescricaoServico;
								document.formulario.maxQtdMesesFidelidade.value		=	maxQtdMesesFidelidade;
								
								if(document.formulario.maxQtdMesesFidelidade.value <= 0){
									document.getElementById("cpValorMulta").style.color	=	"#000";
								}else{
									document.getElementById("cpValorMulta").style.color	=	"#C10000";
								}
								
								document.formulario.IdServico.focus();
								break;	
							case "ServicoParametro": 
								addParmUrl("marServico","IdServico",IdServico);
								addParmUrl("marServicoValor","IdServico",IdServico);
								addParmUrl("marServicoValorNovo","IdServico",IdServico);
								addParmUrl("marServicoParametro","IdServico",IdServico);
								addParmUrl("marServicoParametroNovo","IdServico",IdServico);
								addParmUrl("marServicoRotina","IdServico",IdServico);
								
								mensagens(0);
								listarParametro(IdServico,false);
								
								document.formulario.IdServico.value						=	IdServico;
								document.formulario.DescricaoServico.value				=	DescricaoServico;
								document.formulario.IdParametroServico.value			=	"";
								document.formulario.DescricaoParametroServico.value		=	"";
								document.formulario.Obrigatorio[0].selected 			= 	true;
								document.formulario.ObrigatorioStatus.value 			= 	"";
								document.formulario.Calculavel[0].selected 				= 	true;
								document.formulario.Obs.value							=	"";
								document.formulario.ValorDefaultInput.value				=	"";
								document.formulario.IdStatusParametro[0].selected 		= 	true;
								document.formulario.RotinaCalculo.value					=	"";		
								document.formulario.IdTipoParametro.value				=	"";		
								document.formulario.IdMascaraCampo.value				=	"";		
								document.formulario.OpcaoValor.value					=	"";				
								document.formulario.DataCriacao.value					=	"";
								document.formulario.LoginCriacao.value					=	"";
								document.formulario.DataAlteracao.value					=	"";
								document.formulario.LoginAlteracao.value				=	"";
								document.formulario.Obrigatorio.disabled				=	false;
								
								addParmUrl("marServicoParametroNovo","IdParametroServico","");
								
								while(document.formulario.ValorDefaultSelect.options.length > 0){
									document.formulario.ValorDefaultSelect.options[0] = null;
								}
						
								verificaTipoParametro();
								status_inicial();
								document.getElementById('tabelahelpText2').style.display	=	'none';
								document.getElementById('cpRotinaCalculo').style.display	=	'none';
								
								document.formulario.Acao.value						= 	'inserir';
								document.formulario.IdServico.focus();
								
								document.formulario.IdParametroServico.focus();
								
								verificaAcao();
								break;
							case "ServicoRotina":
								addParmUrl("marServico","IdServico",IdServico);
								addParmUrl("marServicoValor","IdServico",IdServico);
								addParmUrl("marServicoValorNovo","IdServico",IdServico);
								addParmUrl("marServicoParametro","IdServico",IdServico);
								addParmUrl("marServicoParametroNovo","IdServico",IdServico);
								addParmUrl("marServicoRotina","IdServico",IdServico);
							
								nameNode = xmlhttp.responseXML.getElementsByTagName("UrlContratoImpresso")[0]; 
								nameTextNode = nameNode.childNodes[0];
								var UrlContratoImpresso = nameTextNode.nodeValue;
								
								nameNode = xmlhttp.responseXML.getElementsByTagName("UrlRotinaBloqueio")[0]; 
								nameTextNode = nameNode.childNodes[0];
								var UrlRotinaBloqueio = nameTextNode.nodeValue;	
								
								nameNode = xmlhttp.responseXML.getElementsByTagName("UrlRotinaDesbloqueio")[0]; 
								nameTextNode = nameNode.childNodes[0];
								var UrlRotinaDesbloqueio = nameTextNode.nodeValue;	
								
								nameNode = xmlhttp.responseXML.getElementsByTagName("UrlRotinaCriacao")[0]; 
								nameTextNode = nameNode.childNodes[0];
								var UrlRotinaCriacao = nameTextNode.nodeValue;	
								
								nameNode = xmlhttp.responseXML.getElementsByTagName("UrlRotinaAlteracao")[0]; 
								nameTextNode = nameNode.childNodes[0];
								var UrlRotinaAlteracao = nameTextNode.nodeValue;	
								
								nameNode = xmlhttp.responseXML.getElementsByTagName("UrlRotinaCancelamento")[0]; 
								nameTextNode = nameNode.childNodes[0];
								var UrlRotinaCancelamento = nameTextNode.nodeValue;
								
								nameNode = xmlhttp.responseXML.getElementsByTagName("UrlDistratoImpresso")[0]; 
								nameTextNode = nameNode.childNodes[0];
								var UrlDistratoImpresso = nameTextNode.nodeValue;
								
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
								
								document.formulario.IdServico.value						=	IdServico;
								document.formulario.DescricaoServico.value				=	DescricaoServico;
								document.formulario.UrlRotinaBloqueio.value				=	UrlRotinaBloqueio;
								document.formulario.UrlContratoImpresso.value			=	UrlContratoImpresso;
								document.formulario.UrlRotinaDesbloqueio.value			=	UrlRotinaDesbloqueio;
								document.formulario.UrlRotinaCriacao.value				=	UrlRotinaCriacao;
								document.formulario.UrlRotinaAlteracao.value			=	UrlRotinaAlteracao;
								document.formulario.UrlRotinaCancelamento.value			=	UrlRotinaCancelamento;
								document.formulario.UrlDistratoImpresso.value			=	UrlDistratoImpresso;
								
								if(UrlContratoImpresso != ''){
									document.getElementById('LinkUrlContrato').innerHTML 	   = "<a href='contrato/"+document.formulario.UrlContratoImpresso.value+"' target='_blank'><img src='../../img/estrutura_sistema/ico_ie.gif' alt='Visualizar'></a>";
									document.getElementById('LinkUrlDistratoImpresso').innerHTML = "<a href='contrato/"+document.formulario.UrlDistratoImpresso.value+"' target='_blank'><img src='../../img/estrutura_sistema/ico_ie.gif' alt='Visualizar'></a>";
								}else{
									document.getElementById('LinkUrlContrato').innerHTML 	   = "<img src='../../img/estrutura_sistema/ico_ie.gif' alt='Visualizar'>";
									document.getElementById('LinkUrlDistratoImpresso').innerHTML = "<img src='../../img/estrutura_sistema/ico_ie.gif' alt='Visualizar'>";
								}
								
								document.formulario.DataCriacao.value					=	dateFormat(DataCriacao);
								document.formulario.LoginCriacao.value					=	LoginCriacao;
								document.formulario.DataAlteracao.value					=	dateFormat(DataAlteracao);
								document.formulario.LoginAlteracao.value				=	LoginAlteracao;
								
								document.formulario.Acao.value 		= 'alterar';
								verificaAcao();
								break;
							case "OrdemServico": 
								document.formulario.IdServico.value					=	IdServico;
								document.formulario.DescricaoServico.value			=	DescricaoServico;
								document.formulario.DetalheServico.value			=	DetalheServico;
								document.formulario.IdTipoServico.value				=	IdTipoServico;
								
								if(document.formulario.Acao.value == 'inserir'){
									listarServicoParametro(document.formulario.IdServico.value,true);
								}
								
								if(document.formulario.IdOrdemServico.value == '' && document.formulario.Acao.value == 'inserir'){
									document.formulario.Valor.value	= formata_float(Arredonda(Valor,2),2).replace('.',',');
								}
								break;
							case "OrdemServicoFatura": 
								document.formulario.IdServico.value					=	IdServico;
								document.formulario.DescricaoServico.value			=	DescricaoServico;
								document.formulario.DetalheServico.value			=	DetalheServico;
								document.formulario.IdTipoServico.value				=	IdTipoServico;
								break;		
							case "ServicoImportar":
								document.formulario.IdServicoImportar.value			=	IdServico;
								document.formulario.DescricaoServicoImportar.value	=	DescricaoServico;
								break;
							case 'LoteRepasse':
								var cont = 0; ii='';
								if(ListarCampo == '' || ListarCampo == undefined || ListarCampo == 'busca'){
									if(document.formulario.Filtro_IdServico.value == ''){
										document.formulario.Filtro_IdServico.value = IdServico;
										ii = 0;
									}else{
										var tempFiltro	=	document.formulario.Filtro_IdServico.value.split(',');
											
										ii=0; 
										while(tempFiltro[ii] != undefined){
											if(tempFiltro[ii] != IdServico){
												cont++;		
											}
											ii++;
										}
										if(ii == cont){
											document.formulario.Filtro_IdServico.value = document.formulario.Filtro_IdServico.value + "," + IdServico;
										}
									}
								}else{
									ii=0;
								}
								if(ii == cont){
								
									nameNode = xmlhttp.responseXML.getElementsByTagName("ValorRepasseTerceiro")[0]; 
									nameTextNode = nameNode.childNodes[0];
									var ValorRepasseTerceiro = nameTextNode.nodeValue;
															
									var tam, linha, c0, c1, c2, c3, c4;
									
									tam 	= document.getElementById('tabelaServico').rows.length;
									linha	= document.getElementById('tabelaServico').insertRow(tam-1);
									
									if(tam%2 != 0){
										linha.style.backgroundColor = "#E2E7ED";
									}
									
									if(ValorRepasseTerceiro == ''){
										ValorRepasseTerceiro = 0;
									}
									if(Valor == ''){
										Valor = 0;
									}
									
									linha.accessKey 			= IdServico; 
									
									c0	= linha.insertCell(0);	
									c1	= linha.insertCell(1);	
									c2	= linha.insertCell(2);	
									c3	= linha.insertCell(3);
									c4	= linha.insertCell(4);
									
									var linkIni = "<a href='cadastro_servico.php?IdServico="+IdServico+"'>";
									var linkFim = "</a>";
									
									c0.innerHTML = linkIni + IdServico + linkFim;
									c0.style.padding =	"0 0 0 5px";
									
									c1.innerHTML = linkIni + DescricaoServico + linkFim;
									
									c2.innerHTML =  formata_float(Arredonda(Valor,2),2).replace('.',',');
									c2.style.padding =	"0 8px 0 0";
									c2.style.textAlign = "right";
									
									c3.innerHTML =  formata_float(Arredonda(ValorRepasseTerceiro,2),2).replace('.',',');
									c3.style.textAlign = "right";
									c3.style.padding =	"0 8px 0 0";
									
									if(document.formulario.IdStatus.value == 1 || document.formulario.IdStatus.value == ''){
										c4.innerHTML = "<img src='../../img/estrutura_sistema/ico_del.gif' alt='Excluir?' onClick=\"remover_filtro_servico("+IdServico+")\"></tr>";
									}else{
										c4.innerHTML = "<img src='../../img/estrutura_sistema/ico_del_c.gif' alt='Excluir?'\">";
									}
									c4.style.textAlign = "center";
									c4.style.cursor = "pointer";
									
									//if(document.formulario.IdStatus.value == '' || document.formulario.IdStatus.value == 1){
										if(document.formulario.TotalValor.value == ''){
											document.formulario.TotalValor.value = 0;
										}
										
										v1	=	document.formulario.TotalValor.value;
										v1	=	new String(v1);;
										v1	=	v1.replace('.','');
										v1	=	v1.replace('.','');
										v1	=	v1.replace(',','.');
										
										totalv1	=	parseFloat(v1)	+	parseFloat(Valor);
										totalv1	=	formata_float(Arredonda(totalv1,2),2).replace('.',',');
										
										document.formulario.TotalValor.value	=	totalv1;
										
										if(document.formulario.TotalRepasse.value == ''){
											document.formulario.TotalRepasse.value = 0;
										}
										
										v2	=	document.formulario.TotalRepasse.value;
										v2	=	new String(v2);;
										v2	=	v2.replace('.','');
										v2	=	v2.replace('.','');
										v2	=	v2.replace(',','.');
										
										totalv2	=	parseFloat(v2)	+	parseFloat(ValorRepasseTerceiro);
										totalv2	=	formata_float(Arredonda(totalv2,2),2).replace('.',',');
										
										document.formulario.TotalRepasse.value	=	totalv2;
										
										document.getElementById('totaltabelaServico').innerHTML	=	'Total: '+(ii+1);
										document.getElementById('cptotalValor').innerHTML		=	totalv1;
										document.getElementById('cptotalRepasse').innerHTML		=	totalv2;
									/*}else{
										if(document.formulario.Erro.value != ''){
											scrollWindow('bottom');
										}
									}*/
								}
								break;
							case "ServicoAgrupador":
								document.formulario.IdServicoAgrupador.value			=	IdServico;
								document.formulario.DescricaoServicoAgrupador.value		=	DescricaoServico;
								break;
							case 'ProcessoFinanceiro':
								var cont = 0; ii='';
								if(ListarCampo == 'busca'){
									if(document.formulario.Filtro_IdServico.value == ''){
										document.formulario.Filtro_IdServico.value = IdServico;
										ii = 0;
									}else{
										var tempFiltro	=	document.formulario.Filtro_IdServico.value.split(',');
											
										ii=0; 
										while(tempFiltro[ii] != undefined){
											if(tempFiltro[ii] != IdServico){
												cont++;		
											}
											ii++;
										}
										if(ii == cont){
											document.formulario.Filtro_IdServico.value = document.formulario.Filtro_IdServico.value + "," + IdServico;
										}
									}
								}else{
									ii=0;
								}
								if(ii == cont){
								
									nameNode = xmlhttp.responseXML.getElementsByTagName("DescTipoServico")[0]; 
									nameTextNode = nameNode.childNodes[0];
									var DescTipoServico = nameTextNode.nodeValue;
									
									var tam, linha, c0, c1, c2, c3, c4;
									
									tam 	= document.getElementById('tabelaServico').rows.length;
									linha	= document.getElementById('tabelaServico').insertRow(tam-1);
									
									if(tam%2 != 0){
										linha.style.backgroundColor = "#E2E7ED";
									}
									
									linha.accessKey 			= IdServico; 
									
									c0	= linha.insertCell(0);	
									c1	= linha.insertCell(1);	
									c2	= linha.insertCell(2);	
									c3	= linha.insertCell(3);
									c4	= linha.insertCell(4);
									
									var linkIni = "<a href='cadastro_servico.php?IdServico="+IdServico+"'>";
									var linkFim = "</a>";
									
									c0.innerHTML = linkIni + IdServico + linkFim;
									c0.style.padding =	"0 0 0 5px";
									
									c1.innerHTML = linkIni + DescricaoServico.substr(0,30) + linkFim;
									
									c2.innerHTML = linkIni + DescTipoServico.substr(0,30) + linkFim;
									
									c3.innerHTML = linkIni + formata_float(Arredonda(Valor,2),2).replace('.',',') + linkFim ;
									c3.style.textAlign = "right";
									c3.style.padding =	"0 8px 0 0";
									
									if(document.formulario.IdStatus.value == 1){
										c4.innerHTML = "<img src='../../img/estrutura_sistema/ico_del.gif' alt='Excluir?' onClick=\"remover_filtro_servico("+IdServico+")\"></tr>";
									}else{
										c4.innerHTML = "<img src='../../img/estrutura_sistema/ico_del_c.gif' alt='Excluir?'\">";
									}
									c4.style.textAlign = "center";
									c4.style.cursor = "pointer";
									
									if(document.formulario.IdProcessoFinanceiro.value == ''){
										document.getElementById('totaltabelaServico').innerHTML	=	'Total: '+(ii+1);
									}else{
										if(document.formulario.Erro.value != ''){
											scrollWindow('bottom');
										}
									}
								}
								break;
							default:
								document.formulario.IdServico.value					=	IdServico;
								document.formulario.DescricaoServico.value			=	DescricaoServico;
								break;
						}
					}
					if(document.getElementById("quadroBuscaServico") != null){
						if(document.getElementById("quadroBuscaServico").style.display == "block"){
							document.getElementById("quadroBuscaServico").style.display =	"none";
						}
					}
					if(document.getElementById("quadroBuscaServicoAgrupador") != null){
						if(document.getElementById("quadroBuscaServicoAgrupador").style.display == "block"){
							document.getElementById("quadroBuscaServicoAgrupador").style.display =	"none";
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
	function calculaPeriodicidade(IdPeriodicidade,valor,campo){
		if(campo == '' || campo == undefined){
			campo	=	document.formulario.ValorPeriodicidade;
		}
		
		if(valor != ''){
			if(valor.indexOf(",") != -1){	
				valor = valor.replace('.','');
				valor = valor.replace('.','');
				valor = valor.replace(',','.');
			}
			valor 		  = parseFloat(valor);
			
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
		    
		   	url = "xml/periodicidade.php?IdPeriodicidade="+IdPeriodicidade;
		
			xmlhttp.open("GET", url,true);
			xmlhttp.onreadystatechange = function(){ 
		
				if(xmlhttp.readyState == 4){ 
					if(xmlhttp.status == 200){
						if(xmlhttp.responseText != 'false'){	
							nameNode = xmlhttp.responseXML.getElementsByTagName("Fator")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var Fator = nameTextNode.nodeValue;
							
							campo.value = valor*parseInt(Fator);
							campo.value = formata_float(Arredonda(campo.value,2),2).replace(".",",");
						}
					}
				}
			}
			xmlhttp.send(null);
		}else{
			if((document.formulario.Local.value == 'Contrato' || document.formulario.Local.value == 'ContratoServico')&& campo == ''){
				document.formulario.ValorServico.value 		 = '0,00';
				document.formulario.ValorPeriodicidade.value = '0,00';
			}
		}
	}
	function listarServicoParametro(IdServico,Erro,IdContrato){
		while(document.getElementById('tabelaParametro').rows.length > 0){
			document.getElementById('tabelaParametro').deleteRow(0);
		}
		var tam, linha, c0, c1, c2, c3, c4, c5;
		
		if(IdServico == ''){
			IdServico = 0;
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
	    url = "xml/servico_parametro.php?IdServico="+IdServico+"&IdStatus=1";
		
		var Local	=	document.formulario.Local.value;
		
		xmlhttp.open("GET", url,true);
 		xmlhttp.onreadystatechange = function(){ 
	
			// Carregando...
			carregando(true);

			if(xmlhttp.readyState == 4){ 
				if(xmlhttp.status == 200){
					if(xmlhttp.responseText == 'false'){		
						// Fim de Carregando
						if(document.formulario.Local.value == 'OrdemServico'){
							document.getElementById('cp_parametrosServico').style.display	=	'none';	
						}
						carregando(false);
					}else{
						var padding, visivel,DescricaoParametroServico, Obrigatorio, ValorDefault, Valor, IdParametroServico, color, salvar, invisivel="";
						
						var obsTemp = new Array(), cont=0;
						for(var i=0; i<xmlhttp.responseXML.getElementsByTagName("IdParametroServico").length; i++){
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoParametroServico")[i]; 
							nameTextNode = nameNode.childNodes[0];
							var DescricaoParametroServico = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("Obrigatorio")[i]; 
							nameTextNode = nameNode.childNodes[0];
							var Obrigatorio = nameTextNode.nodeValue;
							
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
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("VisivelOS")[i]; 
							nameTextNode = nameNode.childNodes[0];
							var VisivelOS = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("CalculavelOpcoes")[i]; 
							nameTextNode = nameNode.childNodes[0];
							var CalculavelOpcoes = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("RotinaOpcoesContrato")[i]; 
							nameTextNode = nameNode.childNodes[0];
							var RotinaOpcoesContrato = nameTextNode.nodeValue;
							
							//alert(DescricaoParametroServico+" -> "+ValorDefault);
							
							if(Local == 'OrdemServico'){
								Visivel	=	VisivelOS;	
							}
							
							if(Visivel == '1'){
								tam 	 = document.getElementById('tabelaParametro').rows.length;
								
								obsTemp[cont]	=	Obs;
								
								if(cont%2 == 0){
									linha	 = document.getElementById('tabelaParametro').insertRow(tam);
									tabindex = 19 + cont + 1;
									padding	 = 22;	
									pos		 = 0;
								}else{
									padding	 = 10;	
									tabindex = 19 + cont;
									pos		 = 1;
									if(obsTemp[(cont-1)]!= undefined && obsTemp[(cont-1)]!= ''){
										if(Obs	==	'')	Obs	=	'<BR>';
									}
								}
							
								if((cont+1) == xmlhttp.responseXML.getElementsByTagName("IdParametroServico").length && cont%2 == 0){
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
											c0.innerHTML = "<p style='margin:0; padding-bottom:1px; padding-left:"+padding+"px'><B style='color:"+color+";'>"+DescricaoParametroServico+"</B></p><p style='padding-bottom:6px; padding-left:"+padding+"px; margin:0;'><input type='text' name='Valor_"+IdParametroServico+"' value='"+ValorDefault+"' style='width:399px;' autocomplete='off' onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out');\" "+visivel+" onkeypress=\"mascara(this,event,'date')\" maxlength='10' tabindex="+tabindex+"><input type='hidden' name='Obrigatorio_"+IdParametroServico+"' value='"+Obrigatorio+"'><input type='hidden' name='Visivel_"+IdParametroServico+"' value='"+Visivel+"'><BR>"+Obs+"</p>";
											break;
										case '2':
											c0.innerHTML = "<p style='margin:0; padding-bottom:1px; padding-left:"+padding+"px'><B style='color:"+color+";'>"+DescricaoParametroServico+"</B></p><p style='padding-bottom:6px; padding-left:"+padding+"px; margin:0;'><input type='text' name='Valor_"+IdParametroServico+"' value='"+ValorDefault+"' style='width:399px;' autocomplete='off' onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out');\" "+visivel+" onkeypress=\"mascara(this,event,'int')\" tabindex="+tabindex+"><input type='hidden' name='Obrigatorio_"+IdParametroServico+"' value='"+Obrigatorio+"'><input type='hidden' name='Visivel_"+IdParametroServico+"' value='"+Visivel+"'><BR>"+Obs+"</p>";
											break;
										case '3':
											c0.innerHTML = "<p style='margin:0; padding-bottom:1px; padding-left:"+padding+"px'><B style='color:"+color+";'>"+DescricaoParametroServico+"</B></p><p style='padding-bottom:6px; padding-left:"+padding+"px; margin:0;'><input type='text' name='Valor_"+IdParametroServico+"' value='"+ValorDefault+"' style='width:399px;' autocomplete='off' onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out');\" "+visivel+" onkeypress=\"mascara(this,event,'float')\" maxlength='12' tabindex="+tabindex+"><input type='hidden' name='Obrigatorio_"+IdParametroServico+"' value='"+Obrigatorio+"'><input type='hidden' name='Visivel_"+IdParametroServico+"' value='"+Visivel+"'><BR>"+Obs+"</p>";
											break;
										case '5':
											c0.innerHTML = "<p style='margin:0; padding-bottom:1px; padding-left:"+padding+"px'><B style='color:"+color+";'>"+DescricaoParametroServico+"</B></p><p style='padding-bottom:6px; padding-left:"+padding+"px; margin:0;'><input type='text' name='Valor_"+IdParametroServico+"' value='"+ValorDefault+"' style='width:399px;' autocomplete='off' onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out');\" "+visivel+" onkeypress=\"mascara(this,event,'mac')\" maxlength='17' tabindex="+tabindex+"><input type='hidden' name='Obrigatorio_"+IdParametroServico+"' value='"+Obrigatorio+"'><input type='hidden' name='Visivel_"+IdParametroServico+"' value='"+Visivel+"'><BR>"+Obs+"</p>";
											break;
										default:
											c0.innerHTML = "<p style='margin:0; padding-bottom:1px; padding-left:"+padding+"px'><B style='color:"+color+";'>"+DescricaoParametroServico+"</B></p><p style='padding-bottom:6px; padding-left:"+padding+"px; margin:0;'><input type='text' name='Valor_"+IdParametroServico+"' value='"+ValorDefault+"' style='width:399px;' autocomplete='off' onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out');\" "+visivel+" tabindex="+tabindex+"><input type='hidden' name='Obrigatorio_"+IdParametroServico+"' value='"+Obrigatorio+"'><input type='hidden' name='Visivel_"+IdParametroServico+"' value='"+Visivel+"'><BR>"+Obs+"</p>";
									}
								}else{
									campo  = "<p style='margin:0; padding-bottom:1px; padding-left:"+padding+"px'>";
									campo +=	"<B style='color:"+color+";'>"+DescricaoParametroServico+"</B></p>";
									campo += "<p style='padding-bottom:6px; padding-left:"+padding+"px; margin:0;'>";
										
									if(Editavel == 2){
										disabled	=	"disabled";
									}else{
										disabled	=	"";
									}
										
									campo +=	"<select name='Valor_"+IdParametroServico+"'  style='width:406px;' onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out');\" "+visivel+" tabindex="+tabindex+" "+disabled+">";
									campo += 		"<option value=''></option>";
									
									valor	=	OpcaoValor.split("\n");
									for(var ii=0; ii<valor.length; ii++){
										selecionado = "";
										if(ValorDefault == valor[ii]){
											selecionado	=	"selected=true";
										}
										campo +=	"<option value='"+valor[ii]+"' "+selecionado+">"+valor[ii]+"</option>";
									}
									
									campo += "</select>";
									campo +=	"<input type='hidden' name='Obrigatorio_"+IdParametroServico+"' value='"+Obrigatorio+"'><input type='hidden' name='Visivel_"+IdParametroServico+"' value='"+Visivel+"'>";
									campo +=	"<BR>"+Obs+"</p>";
										
									c0.innerHTML	=	campo;
								}
								
								cont++;
							}else{
								invisivel	+=	"<div style='display:none'>";
								if(IdTipoParametro == 1){
									invisivel += "<input type='text' name='Valor_"+IdParametroServico+"' value='"+ValorDefault+"' style='width:399px;' autocomplete='off'><input type='hidden' name='Obrigatorio_"+IdParametroServico+"' value='"+Obrigatorio+"'><input type='hidden' name='Visivel_"+IdParametroServico+"' value='"+Visivel+"'>";
								}else{
									campo  =	"";
									campo +=	"<select name='Valor_"+IdParametroServico+"'  style='width:406px;'>";
									campo += 		"<option value=''></option>";
									
									valor	=	OpcaoValor.split("\n");
									for(var ii=0; ii<valor.length; ii++){
										selecionado = "";
										if(ValorDefault == valor[ii]){
											selecionado	=	"selected=true";
										}
										campo +=	"<option value='"+valor[ii]+"' "+selecionado+">"+valor[ii]+"</option>";
									}
									campo += "</select>";
									campo +=	"<input type='hidden' name='Obrigatorio_"+IdParametroServico+"' value='"+Obrigatorio+"'><input type='hidden' name='Visivel_"+IdParametroServico+"' value='"+Visivel+"'>";
									
									invisivel	+=	campo;
								}
							
							
								invisivel	+=	"</div>";
							}
						}
						
						if(cont > 0){
							document.getElementById('cp_parametrosServico').style.display	=	'block';	
							document.getElementById('tabelaParametro').style.display		=	'block';	
						}
						
						if(invisivel !=""){
							tam 	 = document.getElementById('tabelaParametro').rows.length;
							linha	 = document.getElementById('tabelaParametro').insertRow(tam);
							
							linha.accessKey = IdParametroServico; 
							
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

	

	
