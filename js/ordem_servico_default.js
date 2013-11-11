	function janela_busca_ordem_servico(){
		janelas('busca_ordem_servico.php',507,283,250,100,'');
	}
	function busca_ordem_servico(IdOrdemServico,Erro,Local){
		if(IdOrdemServico == '' || IdOrdemServico == undefined){
			IdOrdemServico = 0;
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
	    
	   	url = "xml/ordem_servico.php?IdOrdemServico="+IdOrdemServico;

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
							case 'OrdemServico':	
								document.formulario.IdOrdemServico.value				= "";
								document.formulario.IdTipoOrdemServico.value			= "";
								document.formulario.IdPessoa.value 						= '';
								document.formulario.IdPessoaF.value 					= '';
								document.formulario.Nome.value 							= '';
								document.formulario.NomeF.value 						= '';
								document.formulario.RazaoSocial.value 					= '';
								document.formulario.Cidade.value 						= '';
								document.formulario.CPF_CNPJ.value 						= '';
								document.formulario.Email.value 						= '';
								document.formulario.Telefone1.value						= '';
								document.formulario.SiglaEstado.value					= '';
								document.formulario.Sexo.value							= '';
								document.formulario.Telefone1F.value					= '';
								document.formulario.IdContrato.value					= "";
								document.formulario.IdServicoContrato.value				= "";
								document.formulario.DescricaoServicoContrato.value		= "";
								document.formulario.DescPeriodicidadeContrato.value		= "";
								document.formulario.QtdParcelaContrato.value			= "";
								document.formulario.DataInicio.value					= "";
								document.formulario.DataTermino.value					= "";
								document.formulario.DataBaseCalculo.value				= "";
								document.formulario.DataUltimaCobranca.value			= "";
								document.formulario.AssinaturaContrato.value			= "";
								//document.formulario.IdLocalCobranca.value				= "";
								//document.formulario.DataFaturamento.value				= "";
								document.formulario.TipoContrato.value					= "";
								document.formulario.IdServico.value						= "";
								document.formulario.DescricaoServico.value				= "";
								document.formulario.IdTipoServico.value					= "";
								document.formulario.Valor.value							= "";	
								document.formulario.IdStatus.value						= "";	
								document.formulario.DescricaoOS.value					= "";	
								document.formulario.DescricaoOSInterna.value			= "";	
								document.formulario.Obs.value							= "";
								document.formulario.DetalheServico.value				= "";
								document.formulario.Data.value							= "";
								document.formulario.Hora.value							= "";	
								document.formulario.IdStatusNovo.value					= "";
								document.formulario.LoginAtendimentoAtual.value				=	"";
								document.formulario.IdGrupoUsuarioAtendimentoAtual.value	=	"";
								document.formulario.DataAtual.value						= "";
								document.formulario.HoraAtual.value						= "";
								document.formulario.HistoricoObs.value					= "";
								document.formulario.LoginAtendimento.value				= "";
								document.formulario.IdGrupoUsuarioAtendimento.value		= "";
								document.formulario.DataCriacao.value					= "";
								document.formulario.LoginCriacao.value					= "";
								document.formulario.DataAlteracao.value					= "";
								document.formulario.LoginAlteracao.value				= "";
								document.formulario.Acao.value							=	'inserir';
								
								while(document.getElementById('tabelaParametro').rows.length > 0){
									document.getElementById('tabelaParametro').deleteRow(0);
								}
								while(document.getElementById('tabelaParametroContrato').rows.length > 0){
									document.getElementById('tabelaParametroContrato').deleteRow(0);
								}			
								
								while(document.formulario.LoginAtendimento.options.length > 0){
									document.formulario.LoginAtendimento.options[0] = null;
								}
								
								for(i=0; i<document.formulario.IdStatusNovo.options.length; i++){
									if(document.formulario.IdStatusNovo.options[i].value == '0'){
										document.formulario.IdStatusNovo.options[i] = null;
									}
								}
								
								while(document.getElementById('tabelaHistorico').rows.length > 2){
									document.getElementById('tabelaHistorico').deleteRow(1);
								}
			
								document.getElementById('cp_historico_os').style.display	=	'none';	
								document.formulario.IdGrupoUsuarioAtendimento.disabled		= 	false;
								document.formulario.LoginAtendimento.disabled				= 	false;
								document.formulario.IdStatus.disabled						= 	true;
								document.formulario.IdStatusNovo.disabled					= 	false;
								document.formulario.DescricaoOS.readOnly					= 	false;
								document.formulario.Valor.readOnly							= 	false;
								document.formulario.Data.readOnly							=	false;
								document.formulario.Hora.readOnly							=	false;
								document.formulario.Obs.readOnly							=	false;
								document.formulario.IdPessoa.readOnly						= 	false;
								document.formulario.IdServico.readOnly						= 	false;
								document.formulario.IdContrato.readOnly						= 	false;
								document.formulario.IdTipoOrdemServico.disabled				= 	false;
								
								document.getElementById('cp_juridica').style.display		= 'block';
								document.getElementById('cp_fisica').style.display			= 'none';
								document.getElementById('cp_CPF_CNPJ_Titulo').innerHTML		= "CNPJ";
								document.getElementById('cp_Status').style.display			= "none";
								document.getElementById('titStatus').style.color			= "#C10000";
								
								document.getElementById('cp_parametrosServico').style.display	= 'none';
								document.getElementById('cp_parametrosContrato').style.display	= 'none';
								document.getElementById("cpHistoricoAtual").style.display		= "none";
								document.getElementById("cpFaturamento").style.display			= "none";
								document.getElementById("cpDescricaoOSInterna").style.display	= "none";
								
								addParmUrl("marOrdemServico","IdOrdemServico",'');
								
								document.formulario.IdOrdemServico.focus();
								break;
							case 'OrdemServicoFatura':
								document.formulario.IdOrdemServico.value				= "";
								document.formulario.IdTipoOrdemServico.value			= "";
								document.formulario.IdPessoa.value 						= '';
								document.formulario.Nome.value 							= '';
								document.formulario.IdPessoaF.value 					= '';
								document.formulario.NomeF.value 						= '';
								document.formulario.RazaoSocial.value 					= '';
								document.formulario.Cidade.value 						= '';
								document.formulario.CPF_CNPJ.value 						= '';
								document.formulario.Email.value 						= '';
								document.formulario.Telefone1.value						= '';
								document.formulario.SiglaEstado.value					= '';
								document.formulario.Sexo.value							= '';
								document.formulario.Telefone1F.value					= '';
								document.formulario.IdContrato.value					= "";
								document.formulario.IdServicoContrato.value				= "";
								document.formulario.DescricaoServicoContrato.value		= "";
								document.formulario.DescPeriodicidadeContrato.value		= "";
								document.formulario.QtdParcelaContrato.value			= "";
								document.formulario.DataInicio.value					= "";
								document.formulario.DataTermino.value					= "";
								document.formulario.DataBaseCalculo.value				= "";
								document.formulario.DataUltimaCobranca.value			= "";
								document.formulario.AssinaturaContrato.value			= "";
								document.formulario.TipoContrato.value					= "";
								document.formulario.IdServico.value						= "";
								document.formulario.DescricaoServico.value				= "";
								document.formulario.IdTipoServico.value					= "";
								document.formulario.Valor.value							= "";	
								document.formulario.IdStatus.value						= "";	
								document.formulario.DescricaoOS.value					= "";	
								document.formulario.DetalheServico.value				= "";
								
								document.formulario.ValorTotal.value							= "";
								document.formulario.FormaCobranca.value							= "";
								document.formulario.FormaCobrancaTemp.value						= "";	
								document.formulario.QtdParcela.value							= "";
								document.formulario.DataPrimeiroVencimentoContrato.value		= "";
								document.formulario.IdLocalCobranca.value						= "";
								document.formulario.IdLocalCobrancaTemp.value					= "";
								document.formulario.ValorDespesaLocalCobranca.value				= "";
								document.formulario.DataPrimeiroVencimentoIndividual.value		= "";
								document.formulario.IdContratoAgrupador.value					= "";
								document.formulario.simulado.value								= "";
								document.formulario.Acao.value									= 'inserir';
								
								while(document.getElementById('tabelaVencimento').rows.length > 0){
									document.getElementById('tabelaVencimento').deleteRow(0);
								}	
								while(document.formulario.IdContratoAgrupador.options.length > 0){
									document.formulario.IdContratoAgrupador.options[0] = null;
								}		
								
								document.getElementById('cp_juridica').style.display		= 'block';
								document.getElementById('cp_fisica').style.display			= 'none';
								document.getElementById('cp_CPF_CNPJ_Titulo').innerHTML		= "CNPJ";
								document.getElementById('cp_Status').style.display			= "none";
								document.getElementById("cp_Vencimento").style.display		= "none";
								
								document.getElementById('titDataPrimeiroVencimentoContrato').style.display		= "none";
								document.getElementById("titLocalCobranca").style.display						= "none";
								document.getElementById("titContrato").style.display							= "none";
								document.getElementById('titValorDespesas').style.display						= "none";
								document.getElementById('cpDataPrimeiroVencimentoContrato').style.display		= "none";
								document.getElementById("cpValorDespesa").style.display							= "none";
								document.getElementById('cpDataPrimeiroVencimento').style.display				= "none";
								document.getElementById('titQtdParcela').style.display							= "none";
								document.getElementById('cpQtdParcela').style.display							= "none";
								document.getElementById('cpLocalCobranca').style.display						= "none";
								document.getElementById('cpContrato').style.display								= "none";
								
								document.formulario.QtdParcela.readOnly							=   false;
								document.formulario.IdLocalCobranca.disabled					=   false;
								document.formulario.ValorDespesaLocalCobranca.readOnly			=   false;
								document.formulario.bt_simular.disabled							=   false;
								document.formulario.DataPrimeiroVencimentoIndividual.readOnly	=   false;
								document.formulario.DataPrimeiroVencimentoContrato.readOnly		=   false;
								
								addParmUrl("marOrdemServico","IdOrdemServico",'');
								
								document.formulario.IdOrdemServico.focus();
								break;
							case 'LancamentoFinanceiro':
								document.formulario.IdOrdemServico.value 					= '';
								document.formulario.IdServicoOS.value 						= '';
								document.formulario.DescricaoServicoOS.value 				= '';
								document.formulario.FormaCobrancaOS[0].selected 			= true;
								document.formulario.IdContratoAgrupador[0].selected 		= true;
								document.formulario.ValorTotalContrato.value				= '';
								document.formulario.QtdParcelaContrato.value				= '';
								document.formulario.DataPrimeiroVencimentoContrato.value	= '';
								document.formulario.ValorTotalIndividual.value				= '';
								document.formulario.ValorDespesaLocalCobranca.value			= '';
								document.formulario.QtdParcelaIndividual.value				= '';
								document.formulario.DataPrimeiroVencimentoIndividual.value	= '';
							
								document.getElementById('cpContaEventual').style.display	=	'none';
						}
						// Fim de Carregando
						carregando(false);
					}else{
						nameNode = xmlhttp.responseXML.getElementsByTagName("IdOrdemServico")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var IdOrdemServico = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("IdTipoOrdemServico")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var IdTipoOrdemServico = nameTextNode.nodeValue;	
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("IdServico")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var IdServico = nameTextNode.nodeValue;

						nameNode = xmlhttp.responseXML.getElementsByTagName("IdServicoContrato")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var IdServicoContrato = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("IdPessoa")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var IdPessoa = nameTextNode.nodeValue;	
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("IdContrato")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var IdContrato = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("LoginAtendimento")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var LoginAtendimento = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("IdGrupoUsuarioAtendimento")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var IdGrupoUsuarioAtendimento = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("IdLocalCobranca")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var IdLocalCobranca = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("DataFaturamento")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var DataFaturamento = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("ValorTotal")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var ValorTotal = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("IdStatus")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var IdStatus = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoOS")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var DescricaoOS = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoServico")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var DescricaoServico = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("Obs")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var Obs = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("DataAgendamentoAtendimento")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var DataAgendamentoAtendimento = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("DescTipoOrdemServico")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var DescTipoOrdemServico = nameTextNode.nodeValue;
						
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
						
						switch(Local){
							case 'OrdemServico':
								Data	=	dateFormat(DataAgendamentoAtendimento.substr(0,10));
								Hora	=	DataAgendamentoAtendimento.substr(11,5);
								
								if(IdTipoOrdemServico == 4){
									addOption(document.formulario.IdTipoOrdemServico,DescTipoOrdemServico,IdTipoOrdemServico);
								}	
								
								if(Hora == '00:00'){
									Hora	=	"";
								}							
								
								document.formulario.IdGrupoUsuarioAtendimento.value		= 	IdGrupoUsuarioAtendimento;
								document.formulario.IdOrdemServico.value				=	IdOrdemServico;
								document.formulario.IdServicoContrato.value				=	IdServicoContrato;
								document.formulario.IdPessoa.value						=	IdPessoa;
								document.formulario.IdPessoaF.value						=	IdPessoa;
								document.formulario.IdTipoOrdemServico.value			=	IdTipoOrdemServico;
								document.formulario.Valor.value							=	formata_float(Arredonda(ValorTotal,2),2).replace(".",",");
								document.formulario.IdStatus.value						=	IdStatus;
								document.formulario.IdStatusNovo.value					=	"";
								document.formulario.DescricaoOS.value					=	DescricaoOS;
								document.formulario.DescricaoOSInterna.value			=	DescricaoOS;
								document.formulario.IdContrato.value					=	IdContrato;	
								document.formulario.Obs.value							= 	'';	
								document.formulario.DataCriacao.value					=	dateFormat(DataCriacao);
								document.formulario.LoginCriacao.value					=	LoginCriacao;
								document.formulario.DataAlteracao.value					=	dateFormat(DataAlteracao);
								document.formulario.LoginAlteracao.value				=	LoginAlteracao;
								document.formulario.LoginAtendimentoAtual.value			=	LoginAtendimento;
								document.formulario.IdGrupoUsuarioAtendimentoAtual.value	=	IdGrupoUsuarioAtendimento;
								document.formulario.DataAtual.value						=	Data;
								document.formulario.HoraAtual.value						=	Hora;
								document.formulario.HistoricoObs.value					=	Obs;
								document.formulario.LoginAtendimento.value				=	"";
								document.formulario.IdGrupoUsuarioAtendimento.value		=	"";
								document.formulario.Obs.value							=	"";
								document.formulario.Data.value							=	"";
								document.formulario.Hora.value							=	"";
								document.formulario.Acao.value 							=	'alterar';
								
								if(Obs!=""){
									document.getElementById("cpHistoricoAtual").style.display		= "block";
								}else{
									document.getElementById("cpHistoricoAtual").style.display		= "none";
								}
								document.getElementById('cp_parametrosServico').style.display	= 'none';
								document.getElementById('cp_parametrosContrato').style.display	= 'none';
								
								document.getElementById("cpFaturamento").style.display			= "none";
								document.getElementById('titStatus').style.color				= "#000";
								
								while(document.getElementById('tabelaParametro').rows.length > 0){
									document.getElementById('tabelaParametro').deleteRow(0);
								}
								while(document.getElementById('tabelaParametroContrato').rows.length > 0){
									document.getElementById('tabelaParametroContrato').deleteRow(0);
								}
								if(FormaCobranca == 1){	//Contrato
									seleciona_local_cobranca(IdPessoa,IdLocalCobranca);
								}else{ //Individual
									seleciona_local_cobranca('',IdLocalCobranca);
								}
								busca_pessoa(IdPessoa,false);
								busca_status(IdStatus);
								atualiza_tipo_servico(IdTipoOrdemServico);	
								busca_servico(IdServico,false);
								busca_login_usuario(IdGrupoUsuarioAtendimento,document.formulario.LoginAtendimentoAtual,LoginAtendimento);
								listarParametro(IdOrdemServico,IdServico,false);	
								
								if(IdContrato != ""){
									busca_contrato(IdContrato,false,'OrdemServico');
								}else{
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
								}
								
								addParmUrl("marPessoa","IdPessoa",IdPessoa);
								addParmUrl("marContrato","IdPessoa",IdPessoa);
								addParmUrl("marContratoNovo","IdPessoa",IdPessoa);
								addParmUrl("marContasReceber","IdPessoa",IdPessoa);
								addParmUrl("marLancamentoFinanceiro","IdPessoa",IdPessoa);
								addParmUrl("marLancamentoFinanceiro","IdOrdemServico",IdOrdemServico);
								addParmUrl("marContasReceber","IdOrdemServico",IdOrdemServico);
								addParmUrl("marProcessoFinanceiro","IdPessoa",IdPessoa);
								addParmUrl("marContaEventual","IdPessoa",IdPessoa);
								addParmUrl("marContaEventualNovo","IdPessoa",IdPessoa);
								addParmUrl("marOrdemServico","IdPessoa",IdPessoa);
								
								if(IdStatus == 5){
									addParmUrl("marOrdemServico","IdOrdemServico",IdOrdemServico);
								}else{
									addParmUrl("marOrdemServico","IdOrdemServico","");
								}						
								
								document.getElementById('titGrupoAtendimento').style.display	= 'none';
								document.getElementById('titUsuarioAtendimento').style.display	= 'none';
								document.getElementById('titDataAgendamento').style.display		= 'none';
								document.getElementById('titHoraAgendamento').style.display		= 'none';
								document.getElementById('cpGrupoAtendimento').style.display		= 'none';
								document.getElementById('cpUsuarioAtendimento').style.display	= 'none';
								document.getElementById('cpData').style.display					= 'none';
								document.getElementById('cpDataIco').style.display				= 'none';
								document.getElementById('cpHora').style.display					= 'none';
						
								document.getElementById('titObs').style.color					= '#000';
								
								document.formulario.IdPessoa.readOnly				= 	true;
								document.formulario.IdServico.readOnly				= 	true;
								document.formulario.IdContrato.readOnly				= 	true;
								document.formulario.IdTipoOrdemServico.disabled		= 	true;
								document.formulario.IdStatusNovo.disabled			=   false;
								
								if(document.formulario.Login.value == LoginAtendimento || document.formulario.Login.value == document.formulario.LoginCriacao.value){
									document.formulario.DescricaoOS.readOnly					= 	false;
									document.formulario.Valor.readOnly							= 	false;
									document.formulario.IdStatusNovo.disabled					=	false;
									document.formulario.Data.readOnly							=	false;
									document.formulario.Hora.readOnly							=	false;
									document.formulario.IdGrupoUsuarioAtendimento.disabled		=	false;
									document.formulario.LoginAtendimento.disabled				=	false;
									document.formulario.Obs.readOnly							=	false;
									temp = 1;
								}else{
									document.formulario.DescricaoOS.readOnly					= 	true;
									document.formulario.Valor.readOnly							= 	true;

									if(IdStatus == 3 && document.formulario.Login.value == LoginCriacao){
										document.formulario.IdStatusNovo.disabled					=	false;
										document.formulario.Data.readOnly							=	false;
										document.formulario.Hora.readOnly							=	false;
										document.formulario.IdGrupoUsuarioAtendimento.disabled		=	false;
										document.formulario.LoginAtendimento.disabled				=	false;
										document.formulario.Obs.readOnly							=	false;	
									}else{
										document.formulario.IdStatusNovo.disabled					=	true;
										document.formulario.Data.readOnly							=	true;
										document.formulario.Hora.readOnly							=	true;
										document.formulario.IdGrupoUsuarioAtendimento.disabled		=	true;
										document.formulario.LoginAtendimento.disabled				=	true;
										document.formulario.Obs.readOnly							=	false;
									}
								}
								if(IdStatus == 0 || IdStatus == 5 || IdStatus == 4){
									document.formulario.DescricaoOS.readOnly					= 	true;
									document.formulario.Valor.readOnly							=	true;
									document.formulario.Data.readOnly							=	true;
									document.formulario.Hora.readOnly							=	true;
									document.formulario.IdGrupoUsuarioAtendimento.disabled		=	true;
									document.formulario.LoginAtendimento.disabled				=	true;
									document.formulario.Obs.readOnly							=	false;
									
									if(IdStatus == 4){
										document.formulario.IdStatusNovo.disabled				=	false;
										
										addStatus('0');	
										
										document.formulario.IdStatusNovo[0].selected	=	true;									
									}else{
										document.formulario.IdStatusNovo.disabled		=	true;
										addStatus('');
									}
								}else{
									addStatus('');
								}
								if(IdTipoOrdemServico == 2){
									document.getElementById('cpDescricaoOSInterna').style.display	=	'block';
								}else{
									document.getElementById('cpDescricaoOSInterna').style.display	=	'none';
								}
								
								if(LoginAtendimento == '' && (IdStatus == 1 || IdStatus == 3)){
									verifica_permissao_update(IdGrupoUsuarioAtendimento);
								}
								document.formulario.bt_inserir.disabled 	= true;
								break;
							case 'OrdemServicoFatura':
								nameNode = xmlhttp.responseXML.getElementsByTagName("FormaCobranca")[0]; 
								nameTextNode = nameNode.childNodes[0];
								var FormaCobranca = nameTextNode.nodeValue;
								
								nameNode = xmlhttp.responseXML.getElementsByTagName("QtdParcela")[0]; 
								nameTextNode = nameNode.childNodes[0];
								var QtdParcela = nameTextNode.nodeValue;
								
								nameNode = xmlhttp.responseXML.getElementsByTagName("DataFaturamento")[0]; 
								nameTextNode = nameNode.childNodes[0];
								var DataFaturamento = nameTextNode.nodeValue;
								
								nameNode = xmlhttp.responseXML.getElementsByTagName("ValorDespesaLocalCobranca")[0]; 
								nameTextNode = nameNode.childNodes[0];
								var ValorDespesaLocalCobranca = nameTextNode.nodeValue;
								
								nameNode = xmlhttp.responseXML.getElementsByTagName("DataPrimeiroVencimento")[0]; 
								nameTextNode = nameNode.childNodes[0];
								var DataPrimeiroVencimento = nameTextNode.nodeValue;
								
								nameNode = xmlhttp.responseXML.getElementsByTagName("DescTipoOrdemServico")[0]; 
								nameTextNode = nameNode.childNodes[0];
								var DescTipoOrdemServico = nameTextNode.nodeValue;
								
								if(IdTipoOrdemServico == 4){
									addOption(document.formulario.IdTipoOrdemServico,DescTipoOrdemServico,IdTipoOrdemServico);
								}
								
								while(document.formulario.IdContratoAgrupador.options.length > 0){
									document.formulario.IdContratoAgrupador.options[0] = null;
								}
								
								document.formulario.IdPessoa.value						=	IdPessoa;
								document.formulario.IdPessoaF.value						=	IdPessoa;
								document.formulario.IdOrdemServico.value				=	IdOrdemServico;
								document.formulario.IdTipoOrdemServico.value			=	IdTipoOrdemServico;
								document.formulario.Valor.value							=	formata_float(Arredonda(ValorTotal,2),2).replace(".",",");
								document.formulario.ValorTotal.value					=	formata_float(Arredonda(ValorTotal,2),2).replace(".",",");
								document.formulario.IdStatus.value						=	IdStatus;
								document.formulario.DescricaoOS.value					=	DescricaoOS;
								document.formulario.IdContrato.value					=	IdContrato;	
								document.formulario.DataCriacao.value					=	dateFormat(DataCriacao);
								document.formulario.LoginCriacao.value					=	LoginCriacao;
								document.formulario.DataAlteracao.value					=	dateFormat(DataAlteracao);
								document.formulario.LoginAlteracao.value				=	LoginAlteracao;
								document.formulario.FormaCobranca.value					=   FormaCobranca;
								document.formulario.FormaCobrancaTemp.value				=   FormaCobranca;		
								document.formulario.QtdParcela.value					=   QtdParcela;
								document.formulario.IdLocalCobranca.value				=   IdLocalCobranca;
								document.formulario.IdLocalCobrancaTemp.value			=   IdLocalCobranca;
								document.formulario.ValorDespesaLocalCobranca.value		=   formata_float(Arredonda(ValorDespesaLocalCobranca,2),2).replace(".",",");;
								document.formulario.Acao.value 							= 	"alterar";
								document.formulario.IdContratoAgrupador.value			=   "";
								document.formulario.simulado.value						=	"";
								
								busca_status(IdStatus);
								atualiza_tipo_servico(IdTipoOrdemServico);	
								busca_servico(IdServico,false);
								busca_pessoa(IdPessoa,false,'OrdemServico');
								
								if(IdContrato != ""){
									busca_contrato(IdContrato,false,'OrdemServico');
								}else{
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
								}
								
								addParmUrl("marPessoa","IdPessoa",IdPessoa);
								addParmUrl("marContrato","IdPessoa",IdPessoa);
								addParmUrl("marContratoNovo","IdPessoa",IdPessoa);
								addParmUrl("marContasReceber","IdPessoa",IdPessoa);
								addParmUrl("marLancamentoFinanceiro","IdPessoa",IdPessoa);
								addParmUrl("marLancamentoFinanceiro","IdOrdemServico",IdOrdemServico);
								addParmUrl("marContasReceber","IdOrdemServico",IdOrdemServico);
								addParmUrl("marProcessoFinanceiro","IdPessoa",IdPessoa);
								addParmUrl("marContaEventual","IdPessoa",IdPessoa);
								addParmUrl("marContaEventualNovo","IdPessoa",IdPessoa);
								addParmUrl("marOrdemServico","IdPessoa",IdPessoa);
								addParmUrl("marOrdemServico","IdOrdemServico","");
								
								
								switch(FormaCobranca){
									case '1':
										document.formulario.DataPrimeiroVencimentoContrato.value	= DataPrimeiroVencimento;
										document.formulario.DataPrimeiroVencimentoIndividual.value	= "";
										break;
									case '2':
										document.formulario.DataPrimeiroVencimentoIndividual.value	= dateFormat(DataPrimeiroVencimento);
										document.formulario.DataPrimeiroVencimentoContrato.value	= "";
										break;
								}
								
								document.getElementById('titDataPrimeiroVencimentoContrato').style.display	= "none";
								document.getElementById("titLocalCobranca").style.display					= "none";
								document.getElementById("titContrato").style.display						= "none";
								document.getElementById('titValorDespesas').style.display					= "none";
								document.getElementById('titQtdParcela').style.display						= "none";
								document.getElementById('cpQtdParcela').style.display						= "none";
								document.getElementById('cpDataPrimeiroVencimentoContrato').style.display	= "none";
								document.getElementById("cpValorDespesa").style.display						= "none";
								document.getElementById('cpDataPrimeiroVencimento').style.display			= "none";
								document.getElementById('cpLocalCobranca').style.display					= "none";
								document.getElementById('cpContrato').style.display							= "none";
								
								if(ValorTotal > 0){	
									busca_forma_cobranca(FormaCobranca);
								}else{
									verificaValor(ValorTotal);
								}
								listar_ordem_servico_parcela(IdOrdemServico);
								
								if(IdStatus == 5 || IdStatus == 0){
									document.formulario.FormaCobranca.disabled						=   true;
									document.formulario.QtdParcela.readOnly							=   true;
									document.formulario.IdLocalCobranca.disabled					=   true;
									document.formulario.ValorDespesaLocalCobranca.readOnly			=   true;
									document.formulario.DataPrimeiroVencimentoIndividual.readOnly	=   true;
									document.formulario.DataPrimeiroVencimentoContrato.readOnly		=   true;
									document.formulario.bt_simular.disabled							=   true;
								}else{
									document.formulario.FormaCobranca.disabled						=   false;
									document.formulario.QtdParcela.readOnly							=   false;
									document.formulario.IdLocalCobranca.disabled					=   false;
									document.formulario.ValorDespesaLocalCobranca.readOnly			=   false;
									document.formulario.bt_simular.disabled							=   false;
									document.formulario.DataPrimeiroVencimentoIndividual.readOnly	=   false;
									document.formulario.DataPrimeiroVencimentoContrato.readOnly		=   false;
								}
								
								if(ValorTotal <= 0){
									document.formulario.bt_simular.disabled							=   true;
								}
								
								break;
							case 'LancamentoFinanceiro':
								nameNode = xmlhttp.responseXML.getElementsByTagName("FormaCobranca")[0]; 
								nameTextNode = nameNode.childNodes[0];
								var FormaCobranca = nameTextNode.nodeValue;
								
								nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoServico")[0]; 
								nameTextNode = nameNode.childNodes[0];
								var DescricaoServico = nameTextNode.nodeValue;
								
								nameNode = xmlhttp.responseXML.getElementsByTagName("ValorTotal")[0]; 
								nameTextNode = nameNode.childNodes[0];
								var ValorTotal = nameTextNode.nodeValue;
								
								nameNode = xmlhttp.responseXML.getElementsByTagName("DataPrimeiroVencimento")[0]; 
								nameTextNode = nameNode.childNodes[0];
								var DataPrimeiroVencimento = nameTextNode.nodeValue;
								
								nameNode = xmlhttp.responseXML.getElementsByTagName("IdContrato")[0]; 
								nameTextNode = nameNode.childNodes[0];
								var IdContrato = nameTextNode.nodeValue;
								
								nameNode = xmlhttp.responseXML.getElementsByTagName("QtdParcela")[0]; 
								nameTextNode = nameNode.childNodes[0];
								var QtdParcela = nameTextNode.nodeValue;
								
								nameNode = xmlhttp.responseXML.getElementsByTagName("ValorDespesaLocalCobranca")[0]; 
								nameTextNode = nameNode.childNodes[0];
								var ValorDespesaLocalCobranca = nameTextNode.nodeValue;
								
								nameNode = xmlhttp.responseXML.getElementsByTagName("IdLocalCobranca")[0]; 
								nameTextNode = nameNode.childNodes[0];
								var IdLocalCobranca = nameTextNode.nodeValue;
								
								document.formulario.IdOrdemServico.value 			= IdOrdemServico;
								document.formulario.IdServicoOS.value 				= IdServico;
								document.formulario.DescricaoServicoOS.value 		= DescricaoServico;
								document.formulario.FormaCobrancaOS.value 			= FormaCobranca;
								
								switch(FormaCobranca){
									case '1': //Contrato
										document.getElementById('cpContaEventualContrato').style.display	=	'block';
										document.getElementById('cpContaEventualIndividual').style.display	=	'none';
										
										document.formulario.ValorTotalContrato.value				= formata_float(Arredonda(ValorTotal,2),2).replace(".",",");
										document.formulario.DataPrimeiroVencimentoContrato.value	= DataPrimeiroVencimento;
										document.formulario.IdContratoAgrupador.value 				= IdContrato;
										document.formulario.QtdParcelaContrato.value				= QtdParcela;
										
										listar_contrato_agrupador(IdPessoa,IdContrato);
										break;
									case '2': //Individual
										document.getElementById('cpContaEventualContrato').style.display	=	'none';
										document.getElementById('cpContaEventualIndividual').style.display	=	'block';
										
										document.formulario.DataPrimeiroVencimentoIndividual.value	= dateFormat(DataPrimeiroVencimento);
										document.formulario.ValorTotalIndividual.value				= formata_float(Arredonda(ValorTotal,2),2).replace(".",",");
										document.formulario.ValorDespesaLocalCobranca.value			= formata_float(Arredonda(ValorDespesaLocalCobranca,2),2).replace(".",",");
										document.formulario.IdLocalCobranca.value 					= IdLocalCobranca;
										document.formulario.QtdParcelaIndividual.value				= QtdParcela;
										break;
								}
								
								break;
						}
					}
					if(window.janela != undefined){
						window.janela.close();
					}
					verificaAcao();
				}
				// Fim de Carregando
				carregando(false);
			} 
			return true;
		}
		xmlhttp.send(null);
	}
