function janela_busca_pessoa(IdStatus){
	if(document.formulario.Local.value == 'Contrato'){
		if(document.formulario.IdContrato.value != ""){
			return false
		}else{
			janelas('../administrativo/busca_pessoa.php',530,350,250,100,'');
		}
	}else{
		if(IdStatus == undefined || IdStatus == ''){
			janelas('../administrativo/busca_pessoa.php',530,350,250,100,'');
		}else{
			if(IdStatus != 2 && IdStatus != 3){
				janelas('../administrativo/busca_pessoa.php',530,350,250,100,'');
			}
		}
	}
}
function busca_pessoa(IdPessoa,Erro,Local,CPF_CNPJ,ListarCampo){
	if(CPF_CNPJ == undefined){
		CPF_CNPJ = '';
	}
	
	if(CPF_CNPJ.length != 14 && CPF_CNPJ.length != 18 && CPF_CNPJ.length != 0){
		return false;
	}
	
	if(IdPessoa == '' && CPF_CNPJ == ''){
		IdPessoa = 0;
	}
	
	if(Local == '' || Local == undefined){
		Local = document.formulario.Local.value;
	}
	
	var nameNode, nameTextNode, url, Condicao;

	if(document.formulario.Condicao != undefined){
		Condicao = document.formulario.Condicao.value;
	}
	
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
    
	if(CPF_CNPJ == ''){
   		url = "../administrativo/xml/pessoa.php?IdPessoa="+IdPessoa;
    } else{
    	url = "../administrativo/xml/pessoa.php?CPF_CNPJ="+CPF_CNPJ;
	}
    
	switch(Local){
		case 'Usuario':
			url	+=	'&TipoUsuario=1';
			break;
		case 'AgenteAutorizado':
			url	+=	'&TipoAgenteAutorizado=1';	
			break;
		case 'Fornecedor':
			url	+=	'&TipoFornecedor=1';
			break;
		case 'NotaFiscalEntrada':
			url	+=	'&IdFornecedor='+IdPessoa;
			break;
		case 'DeclaracaoPagamento':
			url	+=	'&AnoDeclaracaoPagamento='+document.formulario.AnoReferencia.value;
			break;
	}
	
	xmlhttp.open("GET", url,true);
	var CPF	=	CPF_CNPJ;
	var validaCNPJ;
	xmlhttp.onreadystatechange = function(){
		// Carregando...
		carregando(true);
		if(xmlhttp.readyState == 4){ 
			if(xmlhttp.status == 200){
				if(Erro != false && Local != "OrdemServico"){
					document.formulario.Erro.value = 0;
					verificaErro();
				}
				
				if(xmlhttp.responseText == 'false'){
					switch(Local){
						case 'Pessoa':
							if(CPF == '' || (IdPessoa != '' && CPF != '')){
								while(document.formulario.IdEnderecoDefault.options.length > 0){
									document.formulario.IdEnderecoDefault.options[0] = null;
								}
								
								if(CPF == ''){
									document.formulario.TipoPessoa.disabled = false;
									ativaPessoa(document.formulario.TipoPessoaDefault.value);
								}
								
								document.formulario.IdPessoa.value 					= '';
								document.formulario.Nome.value 						= '';
								document.formulario.NomePai.value 					= '';
								document.formulario.NomeMae.value 					= '';
								document.formulario.NomeConjugue.value 				= '';
								document.formulario.NomeFantasia.value 				= '';
								document.formulario.NomeRepresentante.value 		= '';
								document.formulario.RazaoSocial.value 				= '';
								document.formulario.DataNascimento.value 			= '';
								document.formulario.DataFundacao.value 				= '';
								document.formulario.Sexo[0].selected 				= true;
								document.formulario.CPF_CNPJ.value 					= CPF;
								document.formulario.RG_IE.value 					= '';
								document.formulario.InscricaoEstadual.value 		= '';
								document.formulario.EstadoCivil[0].selected 		= true;
								document.formulario.InscricaoMunicipal.value		= '';
								document.formulario.Telefone1.value					= '';
								document.formulario.Telefone2.value					= '';
								document.formulario.Telefone3.value					= '';
								document.formulario.Celular.value 				= '';
								document.formulario.Fax.value 					= '';
								document.formulario.ComplementoTelefone.value	= '';
								document.formulario.Email.value 				= '';
								document.formulario.Site.value 					= 'http://';
								document.formulario.Obs.value 					= '';
								document.formulario.HistoricoObs.value 			= '';
								document.formulario.OrgaoExpedidor.value		= '';
								document.formulario.Cob_FormaEmail.checked		= false;
								document.formulario.Cob_FormaOutro.checked		= false;
								document.formulario.Cob_FormaCorreio.checked 	= false;
								document.formulario.IdMonitorFinanceiro.value	= '';
								//document.formulario.ForcarAtualizar.value		= '';
								document.formulario.AgruparContratos[0].selected = true;
								document.formulario.IdGrupoPessoa.value			= '';
								document.formulario.DescricaoGrupoPessoa.value	= '';
								document.formulario.TipoUsuario.checked			= false;
								document.formulario.TipoAgenteAutorizado.checked= false;
								document.formulario.TipoFornecedor.checked 		= false;
								document.formulario.TipoVendedor.checked 		= false;
								document.formulario.Senha.value					= '';
								document.formulario.QtdEndereco.value			= 0;
								document.formulario.QtdEnderecoAux.value		= 0;
								document.formulario.SiglaEstado.value			= '';
								document.formulario.IdEnderecoDefault.value		= 1;
								document.formulario.IdEnderecoDefault.disabled	=	true;
								document.formulario.IdGrupoPessoa_Resumido.value 			= "";
								document.formulario.DescricaoGrupoPessoa_Resumido.value 	= "";
								document.formulario.IdGrupoPessoa_Resumido2.value 			= "";
								document.formulario.DescricaoGrupoPessoa_Resumido2.value 	= "";
								
								obrigatoriedadeOrgaoExpedidor();
								
								while(document.getElementById('tableEndereco').rows.length > 0){
									document.getElementById('tableEndereco').deleteRow(0);
								}
								
								if(document.formulario.CampoExtra1 != undefined)
									document.formulario.CampoExtra1.value				= '';
								if(document.formulario.CampoExtra2 != undefined)
									document.formulario.CampoExtra2.value				= '';
								if(document.formulario.CampoExtra3 != undefined)
									document.formulario.CampoExtra3.value				= '';
								if(document.formulario.CampoExtra4 != undefined)
									document.formulario.CampoExtra4.value				= '';
								
								formulario_endereco();
								localidadeDefault();
							}
							
							busca_atualizacao_cadastro(0);
							
							document.formulario.DataCriacao.value 			= '';
							document.formulario.LoginCriacao.value 			= '';
							document.formulario.DataAlteracao.value 		= '';
							document.formulario.LoginAlteracao.value		= '';
							document.formulario.Acao.value 					= 'inserir';
							statusInicial();
							
							document.formulario.bt_relatorio.disabled	=	true;
							
							addParmUrl("marContrato","IdPessoa","");
							addParmUrl("marContratoNovo","IdPessoa","");
							addParmUrl("marContasReceber","IdPessoa","");
							addParmUrl("marLancamentoFinanceiro","IdPessoa","");
							addParmUrl("marProcessoFinanceiro","IdPessoa","");
							addParmUrl("marProcessoFinanceiroNovo","IdPessoa","");
							addParmUrl("marReenvioMensagem","IdPessoa","");
							addParmUrl("marPessoa","IdPessoa","");
							addParmUrl("marContaEventualNovo","IdPessoa","");
							addParmUrl("marContaEventual","IdPessoa","");
							addParmUrl("marProcessoFinanceiroNovo","IdPessoa","");
							addParmUrl("marProcessoFinanceiro","IdPessoa","");
							addParmUrl("marOrdemServico","IdPessoa","");
							addParmUrl("marOrdemServicoNovo","IdPessoa","");
							
							document.getElementById('cp_DataNascimento_Titulo').style.color = '#000';
							
							if(document.formulario.DataNascimento_Obrigatorio.value == 1){
								document.getElementById('cp_DataNascimento_Titulo').style.color = '#c10000';
							}
							
							if(document.formulario.ObrigatoriedadeInscricaoEstadual.value == 1){		
								document.getElementById('tit_InscricaoEstadual').style.color	=	'#C10000';
							}else{
								document.getElementById('tit_InscricaoEstadual').style.color	=	'#000000';
							}
							document.getElementById('cp_DataNascimento_Titulo').style.backgroundColor	= '#FFFFFF';
							document.getElementById('cp_DataFundacao_Titulo').style.backgroundColor		= '#FFFFFF';
							document.getElementById('cp_DataFundacao_Titulo').style.color				= '#000000';
							document.getElementById('titEnderecoDefault').style.color 					= '#000';
							document.getElementById('cpHistorico').style.display 						= 'none';
							document.getElementById('cp_Status').innerHTML								= '&nbsp;';
							
							if(document.formulario.CPF_CNPJ_Obrigatorio.value == 1){
								document.getElementById('cp_CPF_CNPJ_Titulo').style.backgroundColor			= '#FFF';
								document.getElementById('cp_CPF_CNPJ_Titulo').style.color					= '#c10000';
							} else{
								document.getElementById('cp_CPF_CNPJ_Titulo').style.backgroundColor			= '#FFF';
								document.getElementById('cp_CPF_CNPJ_Titulo').style.color					= '#000';
							}
							
							verificaAcao();
							
							if(document.formulario.CPF_CNPJ.value!=""){								
								validaCNPJ = validar_CPF_CNPJ(document.formulario.CPF_CNPJ.value);									
							}
							if(CPF == '' && (IdPessoa != '' || IdPessoa == 0)){
								document.formulario.IdPessoa.focus();
							}
							
							while(document.getElementById('tabelaArquivos').rows.length > 0){
								document.getElementById('tabelaArquivos').deleteRow(0);
							}
							
							document.getElementById('cp_dados_adicionais').style.display = "none";
							
							document.formulario.CountArquivo.value = 0;
							verifica_email_cobranca();
							buscar_arquivo();
							addArquivo();
							break;
						case 'PessoaCPF':
							document.formulario.IdPessoa.value 				= '';
							document.formulario.Nome.value 					= '';
							document.formulario.NomeFantasia.value 			= '';
							document.formulario.NomeRepresentante.value 	= '';
							document.formulario.RazaoSocial.value 			= '';
							document.formulario.DataNascimento.value 		= '';
							document.formulario.DataFundacao.value 			= '';
							document.formulario.Sexo[0].selected 			= true;
							document.formulario.CPF_CNPJ.value 				= CPF;
							document.formulario.RG_IE.value 				= '';
							document.formulario.InscricaoEstadual.value 	= '';
							document.formulario.EstadoCivil[0].selected 	= true;
							document.formulario.InscricaoMunicipal.value	= '';
							document.formulario.Telefone1.value				= '';
							document.formulario.Telefone2.value				= '';
							document.formulario.Telefone3.value				= '';
							document.formulario.Celular.value 				= '';
							document.formulario.Fax.value 					= '';
							document.formulario.ComplementoTelefone.value	= '';
							document.formulario.Email.value 				= '';
							document.formulario.Site.value 					= 'http://';
							document.formulario.Acao.value 					= 'inserir';
							
							addParmUrl("marContrato","IdPessoa","");
							addParmUrl("marContratoNovo","IdPessoa","");
							addParmUrl("marContasReceber","IdPessoa","");
							addParmUrl("marLancamentoFinanceiro","IdPessoa","");
							addParmUrl("marProcessoFinanceiro","IdPessoa","");
							addParmUrl("marProcessoFinanceiroNovo","IdPessoa","");
							addParmUrl("marReenvioMensagem","IdPessoa","");
							addParmUrl("marPessoa","IdPessoa","");
							addParmUrl("marContaEventualNovo","IdPessoa","");
							addParmUrl("marContaEventual","IdPessoa","");
							addParmUrl("marProcessoFinanceiroNovo","IdPessoa","");
							addParmUrl("marProcessoFinanceiro","IdPessoa","");
							addParmUrl("marOrdemServico","IdPessoa","");
							addParmUrl("marOrdemServicoNovo","IdPessoa","");
							
							document.getElementById('cp_CPF_CNPJ_Titulo').style.backgroundColor = '#FFFFFF';
							
							if(document.formulario.CPF_CNPJ_Obrigatorio.value == 1){
								document.getElementById('cp_CPF_CNPJ_Titulo').style.color = '#C10000';
							}else{
								document.getElementById('cp_CPF_CNPJ_Titulo').style.color = '#000';
							}
							
							
							verificaAcao();
							
							document.formulario.IdPessoa.focus();
							
							validar_CPF_CNPJ(document.formulario.CPF_CNPJ.value);
							break;
						case "ProcessoFinanceiro":
							document.formulario.IdPessoa.value 			= "";
							document.formulario.Nome.value 				= "";
							
							document.formulario.IdPessoa.focus();
							break;
						case "MalaDireta":
							document.formulario.IdPessoa.value 			= "";
							document.formulario.Nome.value 				= "";
							
							document.formulario.IdPessoa.focus();
							break;
						case "AdicionarProcessoFinanceiro":
							document.formulario.IdPessoa.value 			= "";
							document.formulario.Nome.value 				= "";
							
							document.formulario.IdPessoa.focus();
							break;
						case "AdicionarMalaDireta":
							document.formulario.IdPessoa.value 			= "";
							document.formulario.Nome.value 				= "";
							
							document.formulario.IdPessoa.focus();
							break;
						case "LoteRepasse":
							document.formulario.IdPessoaFiltro.value 	= "";
							document.formulario.NomeFiltro.value 		= "";
							
							//document.formulario.IdPessoaFiltro.focus();
							break;
						case "AdicionarLoteRepasse":
							document.formulario.IdPessoaFiltro.value 	= "";
							document.formulario.NomeFiltro.value 		= "";
							
							document.formulario.IdPessoaFiltro.focus();
							break;
						case "Etiqueta":
							document.formulario.IdPessoaFiltro.value 	= "";
							document.formulario.NomeFiltro.value 		= "";
							
							document.formulario.IdPessoaFiltro.focus();
							break;
						case "DeclaracaoPagamento":
							document.formulario.IdPessoaFiltro.value 	= "";
							document.formulario.NomeFiltro.value 		= "";
							
							document.formulario.IdPessoaFiltro.focus();
							break;
						case "AdicionarEtiqueta":
							document.formulario.IdPessoaFiltro.value 	= "";
							document.formulario.NomeFiltro.value 		= "";
							
							document.formulario.IdPessoaFiltro.focus();
							break;
						case "AgenteAutorizado":
							document.formulario.IdAgenteAutorizado.value 	= "";
							document.formulario.Nome.value 					= "";
							
							document.formulario.IdAgenteAutorizado.focus();
							break;
						case "Carteira":
							document.formulario.IdCarteira.value 		= "";
							document.formulario.Nome.value 				= "";
							document.formulario.Acao.value 				= "inserir";
							verificaAcao();
							break;
						case "Terceiro":
							document.formulario.IdPessoa.value 			= "";
							document.formulario.Nome.value 				= "";
							
							document.formulario.IdPessoa.focus();
							break;
						case "Fornecedor":
							document.formulario.IdPessoa.value 			= "";
							document.formulario.Nome.value 				= "";
							
							document.formulario.IdPessoa.focus();
							break;	
						case 'NotaFiscalEntrada':
							document.formulario.IdPessoa.value 				= '';
							document.formulario.Nome.value 					= '';
							document.formulario.RazaoSocial.value 			= '';
							document.formulario.CPF_CNPJ.value 				= '';
							document.formulario.CPF_CNPJFornecedor.value	= '';
							
							document.formulario.IdPessoa.focus();
							break;
						case 'PessoaSolicitacao':
							document.formulario.NomeAnterior.value 					= '';
							document.formulario.NomeFantasiaAnterior.value 			= '';
							document.formulario.NomeRepresentanteAnterior.value 	= '';
							document.formulario.RazaoSocialAnterior.value 			= '';
							document.formulario.DataNascimentoAnterior.value 		= '';
							document.formulario.DataFundacaoAnterior.value 			= '';
							document.formulario.SexoAnterior[0].selected 			= true;
							document.formulario.RG_IEAnterior.value 				= '';
							document.formulario.InscricaoEstadualAnterior.value 	= '';
							document.formulario.EstadoCivilAnterior[0].selected 	= true;
							document.formulario.InscricaoMunicipalAnterior.value	= '';
							document.formulario.Telefone1Anterior.value				= '';
							document.formulario.Telefone2Anterior.value				= '';
							document.formulario.Telefone3Anterior.value				= '';
							document.formulario.CelularAnterior.value 				= '';
							document.formulario.FaxAnterior.value 					= '';
							document.formulario.ComplementoTelefoneAnterior.value	= '';
							document.formulario.EmailAnterior.value 				= '';
							document.formulario.SiteAnterior.value 					= '';
							document.formulario.IdEnderecoDefault.value 			= '';
							document.formulario.IdEnderecoCobrancaDefault.value 	= '';
							
							if(document.formulario.CampoExtra1Anterior != undefined)
								document.formulario.CampoExtra1Anterior.value				= '';
							if(document.formulario.CampoExtra2Anterior != undefined)
								document.formulario.CampoExtra2Anterior.value				= '';
							if(document.formulario.CampoExtra3Anterior != undefined)
								document.formulario.CampoExtra3Anterior.value				= '';
							if(document.formulario.CampoExtra4Anterior != undefined)
								document.formulario.CampoExtra4Anterior.value				= '';	
					
							break;
						case 'ContaEventual':
							document.getElementById('cp_juridica').style.display			= 'block';
							document.getElementById('cp_fisica').style.display				= 'none';
							
							while(document.formulario.IdPessoaEnderecoCobranca.options.length > 0){
								document.formulario.IdPessoaEnderecoCobranca.options[0] = null;
							}
							
							addOption(document.formulario.IdPessoaEnderecoCobranca,"","0");
							
							document.formulario.IdPessoa.value							=	"";
							document.formulario.RazaoSocial.value						=	"";
							document.formulario.Nome.value								=	"";
							document.formulario.CNPJ.value								=	"";
							document.formulario.IdPessoaEnderecoCobranca.value			=	"";
							document.formulario.NomeResponsavelEnderecoCobranca.value	=	"";
							document.formulario.CEPCobranca.value						=	"";
							document.formulario.EnderecoCobranca.value					=	"";
							document.formulario.NumeroCobranca.value					=	"";
							document.formulario.ComplementoCobranca.value				=	"";
							document.formulario.BairroCobranca.value					=	"";
							document.formulario.IdPaisCobranca.value					=	"";
							document.formulario.PaisCobranca.value						=	"";
							document.formulario.IdEstadoCobranca.value					=	"";
							document.formulario.EstadoCobranca.value					=	"";
							document.formulario.IdCidadeCobranca.value					=	"";
							document.formulario.CidadeCobranca.value					=	"";	
							
							document.formulario.NomeRepresentante.value 				=	"";
							document.formulario.InscricaoEstadual.value 				= 	"";
							document.formulario.DataNascimento.value 					=	"";	
							document.formulario.RG.value 								=	"";
							document.formulario.Telefone1.value 						=	"";
							document.formulario.Telefone2.value 						= 	"";
							document.formulario.Telefone3.value 						= 	"";
							document.formulario.Celular.value 							= 	"";	
							document.formulario.Fax.value 								= 	"";
							document.formulario.ComplementoTelefone.value 				= 	"";
							document.formulario.EmailJuridica.value 					= 	"";
							
							document.formulario.DescricaoContaEventual.value 			= 	"";
							document.formulario.FormaCobranca[0].selected				= 	true;
							document.formulario.OcultarReferencia.value 				= 	"";
							document.formulario.ValorTotal.value 						= 	"0,00";
							document.formulario.QtdParcela.value 						= 	"";
							document.formulario.OcultarReferencia.value					= 	2;
							document.formulario.ObsContaEventual.value					= 	"";
							
							busca_forma_cobranca();
							
							document.formulario.IdPessoa.focus();
							break;
						case 'OrdemServico':
							document.getElementById('cp_juridica').style.display		= 'block';
							document.getElementById('cp_fisica').style.display			= 'none';
						
							document.formulario.IdPessoa.value 			= '';
							document.formulario.IdPessoaF.value 		= '';
							document.formulario.Nome.value 				= '';
							document.formulario.NomeF.value 			= '';
							document.formulario.RazaoSocial.value 		= '';
							document.formulario.CPF.value 				= '';
							document.formulario.CNPJ.value 				= '';
							document.formulario.Email.value 			= '';
							
							addParmUrl("marContrato","IdPessoa",'');
							addParmUrl("marContratoNovo","IdPessoa",'');
							//addParmUrl("marReenvioMensagem","IdPessoa",'');
							addParmUrl("marContaEventual","IdPessoa",'');
							addParmUrl("marContaEventualNovo","IdPessoa",'');
							addParmUrl("marPessoa","IdPessoa",'');
							addParmUrl("marOrdemServico","IdPessoa",'');
							addParmUrl("marOrdemServicoNovo","IdPessoa",'');
							addParmUrl("marVigenciaNovo","IdPessoa",'');
							
							if(IdPessoa != 0){
								document.formulario.IdPessoa.focus();
							}
							
							if(document.formulario.IdTipoOrdemServico.value != 2){
								document.formulario.IdPessoa.focus();
							}
							
							document.getElementById("cp_parametrosContrato").style.display	= "none";
							document.getElementById("cp_parametrosServico").style.display	= "none";
							document.getElementById("cp_automatico").style.display			= "none";
							
							document.formulario.IdContrato.value					= '';
							document.formulario.IdServicoContrato.value				= '';
							document.formulario.DescricaoServicoContrato.value		= '';
							document.formulario.DescPeriodicidadeContrato.value		= '';
							document.formulario.QtdParcelaContrato.value			= '';
							document.formulario.DataInicio.value					= '';
							document.formulario.DataTermino.value					= '';
							document.formulario.DataBaseCalculo.value				= '';
							document.formulario.DataUltimaCobranca.value			= '';
							document.formulario.AssinaturaContrato.value			= '';
							document.formulario.TipoContrato.value					= '';
							
							document.formulario.IdServico.value						= '';
							document.formulario.DescricaoServico.value				= '';
							document.formulario.IdTipoServico.value					= '';
							document.formulario.DetalheServico.value				= '';
							document.formulario.IdOrdemServicoLayout.value			= '';
							document.formulario.ValorOutros.value					= "0,00";
							document.formulario.Justificativa.value					= '';
							document.formulario.DescricaoOS.value					= '';
							document.formulario.Valor.value							= "0,00";
							
							document.formulario.Obs.value							= '';
							document.formulario.IdStatusNovo[0].selected							= true;
							
							listarOrdemServicoCliente('');
							visualiza_campo('');
							calcula_total();
							break;
						case 'Protocolo':
							document.getElementById('cp_juridica').style.display		= 'block';
							document.getElementById('cp_fisica').style.display			= 'none';
							document.getElementById("cp_NomeFantasia").style.color		= "#000";
							document.getElementById("cp_CNPJ").style.color				= "#000";
							document.getElementById("cp_NomePessoa").style.color		= "#000";
							document.getElementById("cp_CPF").style.color				= "#000";
							
							document.formulario.NomeF.onFocus = document.formulario.CPF.onFocus = document.formulario.Email.onFocus = document.formulario.Nome.onFocus = document.formulario.CNPJ.onFocus = document.formulario.EmailJuridica.onFocus = document.formulario.Telefone1.onFocus = document.formulario.Telefone2.onFocus = document.formulario.Telefone3.onFocus = document.formulario.Celular.onFocus = undefined;
							document.formulario.NomeF.onBlur = document.formulario.CPF.onBlur = document.formulario.Email.onBlur = 	document.formulario.Nome.onBlur = document.formulario.CNPJ.onBlur = document.formulario.EmailJuridica.onBlur = document.formulario.Telefone1.onBlur = document.formulario.Telefone2.onBlur = document.formulario.Telefone3.onBlur = document.formulario.Celular.onBlur = undefined;
							
							document.getElementById("cp_CPF").style.backgroundColor = document.getElementById("cp_CNPJ").style.backgroundColor = "#fff";
							document.getElementById("cp_CPF").innerHTML = "CPF";
							document.getElementById("cp_CNPJ").innerHTML = "CNPJ";
							if(document.formulario.IdPessoa.value != ""){
								document.formulario.Telefone1.readOnly 				= true;
								document.formulario.Telefone2.readOnly 				= true;
								document.formulario.Telefone3.readOnly 				= true;
								document.formulario.Celular.readOnly 				= true;
								document.formulario.NomeF.readOnly					= true;
								document.formulario.CPF.readOnly					= true;
								document.formulario.Email.readOnly 					= true;
								document.formulario.Nome.readOnly					= true;
								document.formulario.CNPJ.readOnly					= true;
								document.formulario.EmailJuridica.readOnly			= true;
							}else{
								document.formulario.Telefone1.readOnly 				= false;
								document.formulario.Telefone2.readOnly 				= false;
								document.formulario.Telefone3.readOnly 				= false;
								document.formulario.Celular.readOnly 				= false;
								document.formulario.NomeF.readOnly					= false;
								document.formulario.CPF.readOnly					= false;
								document.formulario.Email.readOnly 					= false;
								document.formulario.Nome.readOnly					= false;
								document.formulario.CNPJ.readOnly					= false;
								document.formulario.EmailJuridica.readOnly			= false;
							}
							document.formulario.IdPessoa.value 					= '';
							document.formulario.IdPessoaF.value 				= '';
							document.formulario.IdTipoPessoa.value 				= '';
							//document.formulario.IdTipoPessoaTemp.value 			= '';
							document.formulario.Nome.value 						= '';
							document.formulario.NomeF.value 					= '';
							document.formulario.RazaoSocial.value 				= '';
							document.formulario.CPF.value 						= '';
							document.formulario.CNPJ.value 						= '';
							document.formulario.Email.value 					= '';
							document.formulario.NomeRepresentante.value 		= '';
							document.formulario.InscricaoEstadual.value 		= '';
							document.formulario.DataNascimento.value 			= '';	
							document.formulario.RG.value 						= '';
							document.formulario.Telefone1.value 				= '';
							document.formulario.Telefone2.value 				= '';
							document.formulario.Telefone3.value 				= '';
							document.formulario.Celular.value 					= '';	
							document.formulario.Fax.value 						= '';
							document.formulario.ComplementoTelefone.value 		= '';
							document.formulario.EmailJuridica.value 			= '';
						/*  
							busca_contrato('',false,document.formulario.Local.value);
							busca_conta_eventual('',false,document.formulario.Local.value);
							busca_conta_receber('',false,document.formulario.Local.value);
							busca_ordem_servico('',false,document.formulario.Local.value);
						*/
							break;
						default:
							document.getElementById('cp_juridica').style.display			= 'block';
							document.getElementById('cp_fisica').style.display				= 'none';
						
							document.formulario.IdPessoa.value 			= '';
							document.formulario.IdPessoaF.value 		= '';
							document.formulario.Nome.value 				= '';
							document.formulario.NomeF.value 			= '';
							document.formulario.RazaoSocial.value 		= '';
							document.formulario.CPF.value 				= '';
							document.formulario.CNPJ.value 				= '';
							document.formulario.Email.value 			= '';
							
							if(IdPessoa != 0 && Local!='OrdemServico'){
								document.formulario.IdPessoa.focus();
							}
							if(Local == 'OrdemServico' && document.formulario.IdTipoOrdemServico.value != 2){
								document.formulario.IdPessoa.focus();
							}
							break;
					}
					
					if(Local == 'Contrato'){
	
						while(document.formulario.IdContratoAgrupador.options.length > 0){
							document.formulario.IdContratoAgrupador.options[0] = null;
						}
						while(document.formulario.IdPessoaEndereco.options.length > 0){
							document.formulario.IdPessoaEndereco.options[0] = null;
						}
						while(document.formulario.IdPessoaEnderecoCobranca.options.length > 0){
							document.formulario.IdPessoaEnderecoCobranca.options[0] = null;
						}
						
						document.formulario.IdPessoaEndereco.value					=	"";
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
						document.formulario.IdCidade.value							=	"";
						document.formulario.Cidade.value							=	"";
						
						document.formulario.IdPessoaEnderecoCobranca.value			=	"";
						document.formulario.NomeResponsavelEnderecoCobranca.value	=	"";
						document.formulario.CEPCobranca.value						=	"";
						document.formulario.EnderecoCobranca.value					=	"";
						document.formulario.NumeroCobranca.value					=	"";
						document.formulario.ComplementoCobranca.value				=	"";
						document.formulario.BairroCobranca.value					=	"";
						document.formulario.IdPaisCobranca.value					=	"";
						document.formulario.PaisCobranca.value						=	"";
						document.formulario.IdEstadoCobranca.value					=	"";
						document.formulario.EstadoCobranca.value					=	"";
						document.formulario.IdCidadeCobranca.value					=	"";
						document.formulario.CidadeCobranca.value					=	"";						
						document.formulario.SiglaEstado.value						=	"";
						
						document.formulario.NomeRepresentante.value 				=	"";
						document.formulario.InscricaoEstadual.value 				= 	"";
						document.formulario.DataNascimento.value 					=	"";	
						document.formulario.RG.value 								=	"";
						document.formulario.Telefone1.value 						=	"";
						document.formulario.Telefone2.value 						= 	"";
						document.formulario.Telefone3.value 						= 	"";
						document.formulario.Celular.value 							= 	"";	
						document.formulario.Fax.value 								= 	"";
						document.formulario.ComplementoTelefone.value 				= 	"";
						document.formulario.EmailJuridica.value 					= 	"";
						document.formulario.IdEnderecoDefault.value 				= 	"";
						
						if(document.formulario.Acao.value == 'inserir'){
							if(document.formulario.IdServico.value != ""){
								document.formulario.IdServico.value = "";
								busca_servico('');
								
								document.formulario.IdPessoa.focus();
							}
						}
					}
					if(Local == 'ContaDebito'){
						document.formulario.NomeRepresentante.value 		= '';
						document.formulario.InscricaoEstadual.value 		= '';
						document.formulario.DataNascimento.value 			= '';	
						document.formulario.RG.value 						= '';
						document.formulario.Telefone1.value 				= '';
						document.formulario.Telefone2.value 				= '';
						document.formulario.Telefone3.value 				= '';
						document.formulario.Celular.value 					= '';	
						document.formulario.Fax.value 						= '';
						document.formulario.ComplementoTelefone.value 		= '';
						document.formulario.EmailJuridica.value 			= '';
						document.formulario.Acao.value						= 'inserir';
						
						addParmUrl("marPessoa", "IdPessoa", '');
						addParmUrl("marPessoaNovo", "IdPessoa", '');
						addParmUrl("marContrato", "IdPessoa", '');
						addParmUrl("marContratoNovo", "IdPessoa", '');
						addParmUrl("marLancamentoFinanceiro", "IdPessoa", '');
						addParmUrl("marReenvioMensagem", "IdPessoa", '');
						addParmUrl("marContasReceber", "IdPessoa", '');
						addParmUrl("marContaEventual", "IdPessoa", '');
						addParmUrl("marContaEventualNovo", "IdPessoa", '');
						addParmUrl("marOrdemServico", "IdPessoa", '');
						addParmUrl("marOrdemServicoNovo", "IdPessoa", '');
						limpar_formulario_conta_debito();
						listar_conta_debito(0, false, Local);
						buscar_local_cobranca();
						
						document.formulario.IdPessoa.focus();
					}
					
					if(Local == 'CartaoCredito'){
						document.formulario.NomeRepresentante.value 		= '';
						document.formulario.InscricaoEstadual.value 		= '';
						document.formulario.DataNascimento.value 			= '';	
						document.formulario.RG.value 						= '';
						document.formulario.Telefone1.value 				= '';
						document.formulario.Telefone2.value 				= '';
						document.formulario.Telefone3.value 				= '';
						document.formulario.Celular.value 					= '';	
						document.formulario.Fax.value 						= '';
						document.formulario.ComplementoTelefone.value 		= '';
						document.formulario.EmailJuridica.value 			= '';
						document.formulario.Acao.value						= 'inserir';
						
						addParmUrl("marPessoa", "IdPessoa", '');
						addParmUrl("marPessoaNovo", "IdPessoa", '');
						addParmUrl("marContrato", "IdPessoa", '');
						addParmUrl("marContratoNovo", "IdPessoa", '');
						addParmUrl("marLancamentoFinanceiro", "IdPessoa", '');
						addParmUrl("marReenvioMensagem", "IdPessoa", '');
						addParmUrl("marContasReceber", "IdPessoa", '');
						addParmUrl("marContaEventual", "IdPessoa", '');
						addParmUrl("marContaEventualNovo", "IdPessoa", '');
						addParmUrl("marOrdemServico", "IdPessoa", '');
						addParmUrl("marOrdemServicoNovo", "IdPessoa", '');
						
						document.formulario.IdPessoa.focus();
						busca_pessoa_cartao_credito(0, 0, Erro, Local,ListarCampo)
						listar_cartao_credito("", false, Local);
					}
					
					if(Local == 'AgruparContaReceber'){
						document.formulario.NomeRepresentante.value			= "";
						document.formulario.InscricaoEstadual.value			= "";
						document.formulario.DataNascimento.value			= "";
						document.formulario.RG.value						= "";
						document.formulario.Telefone1.value					= "";
						document.formulario.Telefone2.value					= "";
						document.formulario.Telefone3.value					= "";
						document.formulario.Celular.value					= "";
						document.formulario.Fax.value						= "";
						document.formulario.ComplementoTelefone.value		= "";
						document.formulario.EmailJuridica.value				= "";
						
						busca_opcoes_pessoa_endereco(0, 0);
						limparNovoVencimento();
						
						while(document.getElementById('tabelaContaReceber').rows.length > 2){
							document.getElementById('tabelaContaReceber').deleteRow(1);
						}
						
						document.getElementById('totaltabelaContaReceber').innerHTML = "Total: 0";
						document.getElementById('totalValorTabelaContaReceber').innerHTML = "0,00";
						
						document.formulario.IdContaReceberAgrupados.value	= '';
						document.formulario.IdContaReceber.value			= '';
						document.formulario.NomePessoa.value				= '';
						document.formulario.IdStatus.value					= '';
						document.formulario.bt_add_conta_receber.disabled	= true;
					}
					
					if(Local == 'OrdemServico'){
						while(document.formulario.IdPessoaEndereco.options.length > 0){
							document.formulario.IdPessoaEndereco.options[0] = null;
						}
						
						document.formulario.IdPessoaEndereco.value					=	"";
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
						document.formulario.IdCidade.value							=	"";
						document.formulario.Cidade.value							=	"";						
						document.formulario.SiglaEstado.value						=	"";
						
						document.formulario.NomeRepresentante.value 				=	"";
						document.formulario.InscricaoEstadual.value 				= 	"";
						document.formulario.DataNascimento.value 					=	"";	
						document.formulario.RG.value 								=	"";
						document.formulario.Telefone1.value 						=	"";
						document.formulario.Telefone2.value 						= 	"";
						document.formulario.Telefone3.value 						= 	"";
						document.formulario.Celular.value 							= 	"";	
						document.formulario.Fax.value 								= 	"";
						document.formulario.ComplementoTelefone.value 				= 	"";
						document.formulario.EmailJuridica.value 					= 	"";
					}
					if(Local == 'HelpDesk'){
						document.formulario.NomeRepresentante.value 				=	"";
						document.formulario.InscricaoEstadual.value 				= 	"";
						document.formulario.DataNascimento.value 					=	"";	
						document.formulario.RG.value 								=	"";
						document.formulario.Telefone1.value 						=	"";
						document.formulario.Telefone2.value 						= 	"";
						document.formulario.Telefone3.value 						= 	"";
						document.formulario.Celular.value 							= 	"";	
						document.formulario.Fax.value 								= 	"";
						document.formulario.ComplementoTelefone.value 				= 	"";
						document.formulario.EmailJuridica.value 					= 	"";
						document.formulario.IdPais.value							= 	"";
						document.formulario.Pais.value								= 	"";
						document.formulario.IdEstado.value							= 	"";
						document.formulario.Estado.value							= 	"";
						document.formulario.IdCidade.value							= 	"";
						document.formulario.Cidade.value							= 	"";
					}
					
					// Fim de Carregando
					carregando(false);
				}else{
					nameNode = xmlhttp.responseXML.getElementsByTagName("IdPessoa")[0]; 
					nameTextNode = nameNode.childNodes[0];
					IdPessoa = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("Nome")[0]; 
					nameTextNode = nameNode.childNodes[0];
					var Nome = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("RazaoSocial")[0]; 
					nameTextNode = nameNode.childNodes[0];
					var RazaoSocial = nameTextNode.nodeValue;	
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("CPF_CNPJ")[0]; 
					nameTextNode = nameNode.childNodes[0];
					var CPF_CNPJ = nameTextNode.nodeValue;	
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("TipoPessoa")[0]; 
					nameTextNode = nameNode.childNodes[0];
					var TipoPessoa = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("Telefone1")[0]; 
					nameTextNode = nameNode.childNodes[0];
					var Telefone1 = nameTextNode.nodeValue;	
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("Telefone2")[0]; 
					nameTextNode = nameNode.childNodes[0];
					var Telefone2 = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("Telefone3")[0]; 
					nameTextNode = nameNode.childNodes[0];
					var Telefone3 = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("Celular")[0]; 
					nameTextNode = nameNode.childNodes[0];
					var Celular = nameTextNode.nodeValue;	
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("Fax")[0]; 
					nameTextNode = nameNode.childNodes[0];
					var Fax = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("NomeCidade")[0]; 
					nameTextNode = nameNode.childNodes[0];
					var NomeCidade = nameTextNode.nodeValue;
					
					switch(Condicao){
						case "Loja":
							nameNode = xmlhttp.responseXML.getElementsByTagName("Qtd_Loja")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var Qtd_Loja = nameTextNode.nodeValue;
							
							if(Qtd_Loja >= 1){
								IdPessoa 	= '';
								Nome		= '';
								mensagens(19);
								document.formulario.IdPessoa.focus();
							}
							break;
					}
					
					switch(Local){
						case 'Pessoa':
							nameNode = xmlhttp.responseXML.getElementsByTagName("NomeRepresentante")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var NomeRepresentante = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("DataNascimento")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var DataNascimento = nameTextNode.nodeValue;	
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("Sexo")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var Sexo = nameTextNode.nodeValue;	
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("RG_IE")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var RG_IE = nameTextNode.nodeValue;	
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("EstadoCivil")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var EstadoCivil = nameTextNode.nodeValue;	
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("InscricaoMunicipal")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var InscricaoMunicipal = nameTextNode.nodeValue;	
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("ComplementoTelefone")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var ComplementoTelefone = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("Email")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var Email = nameTextNode.nodeValue;	
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("Site")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var Site = nameTextNode.nodeValue;	
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("Obs")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var Obs = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("AgruparContratos")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var AgruparContratos = nameTextNode.nodeValue;	
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("Cob_CobrarDespesaBoleto")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var Cob_CobrarDespesaBoleto = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("IdGrupoPessoa")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var IdGrupoPessoa = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoGrupoPessoa")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var DescricaoGrupoPessoa = nameTextNode.nodeValue;
													
							nameNode = xmlhttp.responseXML.getElementsByTagName("Cob_FormaCorreio")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var Cob_FormaCorreio = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("Cob_FormaEmail")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var Cob_FormaEmail = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("Cob_FormaOutro")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var Cob_FormaOutro = nameTextNode.nodeValue;
													
							nameNode = xmlhttp.responseXML.getElementsByTagName("TipoUsuario")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var TipoUsuario = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("TipoAgenteAutorizado")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var TipoAgenteAutorizado = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("TipoFornecedor")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var TipoFornecedor = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("TipoVendedor")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var TipoVendedor = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("Senha")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var Senha = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("CampoExtra1")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var CampoExtra1 = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("CampoExtra2")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var CampoExtra2 = nameTextNode.nodeValue;					
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("CampoExtra3")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var CampoExtra3 = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("CampoExtra4")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var CampoExtra4 = nameTextNode.nodeValue;
													
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
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("QtdEndereco")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var QtdEndereco = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("IdEnderecoDefault")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var IdEnderecoDefault = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("IdOrdemServico")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var IdOrdemServico = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("ContaDebito")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var ContaDebito = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("CartaoCredito")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var CartaoCredito = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("NomePai")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var NomePai = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("NomeMae")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var NomeMae = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("NomeConjugue")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var NomeConjugue = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("CorStatus")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var CorStatus = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("Status")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var Status = nameTextNode.nodeValue;

							nameNode = xmlhttp.responseXML.getElementsByTagName("MonitorFinanceiro")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var MonitorFinanceiro = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("OrgaoExpedidor")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var OrgaoExpedidor = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("ForcarAtualizarDadosCDA")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var ForcarAtualizarDadosCDA = nameTextNode.nodeValue;
							/*
							nameNode = xmlhttp.responseXML.getElementsByTagName("Disabled")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var Disabled = nameTextNode.nodeValue;
							*/
							ativaPessoa(TipoPessoa);
							
							if(EstadoCivil == 2){
								visualizarConjugue(EstadoCivil);
							}
							
							addParmUrl("marContrato","IdPessoa",IdPessoa);
							addParmUrl("marContratoNovo","IdPessoa",IdPessoa);
							addParmUrl("marContasReceber","IdPessoa",IdPessoa);
							addParmUrl("marLancamentoFinanceiro","IdPessoa",IdPessoa);
							addParmUrl("marProcessoFinanceiro","IdPessoa",IdPessoa);
							addParmUrl("marContaEventualNovo","IdPessoa",IdPessoa);
							addParmUrl("marContaEventual","IdPessoa",IdPessoa);
							addParmUrl("marProcessoFinanceiroNovo","IdPessoa",IdPessoa);
							addParmUrl("marReenvioMensagem","IdPessoa",IdPessoa);
							addParmUrl("marPessoa","IdPessoa",IdPessoa);
							addParmUrl("marProcessoFinanceiroNovo","IdPessoa",IdPessoa);
							addParmUrl("marProcessoFinanceiro","IdPessoa",IdPessoa);
							addParmUrl("marOrdemServicoNovo","IdPessoa",IdPessoa);
							addParmUrl("marOrdemServico","IdPessoa",IdPessoa);
							
							for(var i=0; i<document.formulario.Sexo.length; i++){
								if(document.formulario.Sexo[i].value == Sexo){
									document.formulario.Sexo[i].selected = true;
									i = document.formulario.Sexo.length;
								}							
							}							
							
							for(var i=0; i<document.formulario.EstadoCivil.length; i++){
								if(document.formulario.EstadoCivil[i].value == EstadoCivil){
									document.formulario.EstadoCivil[i].selected = true;
									i = document.formulario.EstadoCivil.length;
								}							
							}
							
							if(Site == ""){
								Site	=	"http://";
							}
							
							while(document.formulario.IdEnderecoDefault.options.length > 0){
								document.formulario.IdEnderecoDefault.options[0] = null;
							}
							
							document.formulario.IdPessoa.value 					= IdPessoa;
							document.formulario.Nome.value 						= Nome;		
							document.formulario.NomePai.value 					= NomePai;		
							document.formulario.NomeMae.value 					= NomeMae;		
							document.formulario.TipoPessoa.disabled				= true;			
							document.formulario.TipoPessoa.value				= TipoPessoa;
							document.formulario.IdTipoPessoaTemp.value			= TipoPessoa;
							document.formulario.CPF_CNPJ.value 					= CPF_CNPJ;
							document.formulario.NomeFantasia.value 				= Nome;
							document.formulario.NomeRepresentante.value 		= NomeRepresentante;
							document.formulario.RazaoSocial.value 				= RazaoSocial;
							document.formulario.DataNascimento.value 			= dateFormat(DataNascimento);
							document.formulario.DataFundacao.value 				= dateFormat(DataNascimento);
							document.formulario.RG_IE.value 					= RG_IE;
							document.formulario.InscricaoEstadual.value			= RG_IE;
							document.formulario.InscricaoMunicipal.value		= InscricaoMunicipal;
							document.formulario.Telefone1.value 				= Telefone1;
							document.formulario.Telefone2.value 				= Telefone2;
							document.formulario.Telefone3.value 				= Telefone3;
							document.formulario.Celular.value 					= Celular;
							document.formulario.NomeConjugue.value 				= NomeConjugue;
							document.formulario.OrgaoExpedidor.value 			= OrgaoExpedidor;
							document.formulario.Fax.value 						= Fax;
							document.formulario.ComplementoTelefone.value		= ComplementoTelefone;
							document.formulario.Email.value 					= Email;
							document.formulario.Site.value 						= Site;
							document.formulario.Obs.value 						= "";
							document.formulario.SiglaEstado.value 				= "";
							document.formulario.AgruparContratos.value			= AgruparContratos;
							document.formulario.Cob_CobrarDespesaBoleto.value 	= Cob_CobrarDespesaBoleto;
							document.formulario.IdGrupoPessoa.value				= IdGrupoPessoa;
							document.formulario.DescricaoGrupoPessoa.value		= DescricaoGrupoPessoa;
							document.formulario.Senha.value						= Senha;
							document.formulario.IdMonitorFinanceiro.value		= MonitorFinanceiro;
							document.formulario.ForcarAtualizar.value			= ForcarAtualizarDadosCDA;
							document.formulario.DataCriacao.value 				= dateFormat(DataCriacao);
							document.formulario.LoginCriacao.value 				= LoginCriacao;
							document.formulario.DataAlteracao.value 			= dateFormat(DataAlteracao);
							document.formulario.LoginAlteracao.value			= LoginAlteracao;
							document.formulario.QtdEndereco.value				= 0;
							document.formulario.QtdEnderecoAux.value			= 0;
							document.formulario.IdEnderecoDefault.disabled		=	false;
							
							
							
							obrigatoriedadeOrgaoExpedidor();
							if(Cob_FormaCorreio == 'S'){
								document.formulario.Cob_FormaCorreio.checked	=	true;
							}else{
								document.formulario.Cob_FormaCorreio.checked	=	false;
							}					
							
							if(Cob_FormaEmail == 'S'){
								document.formulario.Cob_FormaEmail.checked		=	true;	
							}else{
								document.formulario.Cob_FormaEmail.checked		=	false;
							}
							
							if(Cob_FormaOutro == 'S'){
								document.formulario.Cob_FormaOutro.checked		=	true;
							}else{
								document.formulario.Cob_FormaOutro.checked		=	false;
							}
							
							if(TipoUsuario == '1'){
								document.formulario.TipoUsuario.checked			=	true;
							}else{
								document.formulario.TipoUsuario.checked			=	false;
							}
							/*
							if(Disabled == "true"){
								document.formulario.TipoUsuario.disabled	=	true;
								document.formulario.TipoUsuario.checked		=	true;
							}else{
								document.formulario.TipoUsuario.disabled			= false;
							}
							*/
							if(TipoAgenteAutorizado == '1'){
								document.formulario.TipoAgenteAutorizado.checked	=	true;
							}else{
								document.formulario.TipoAgenteAutorizado.checked	=	false;
							}
							
							if(TipoFornecedor == '1'){
								document.formulario.TipoFornecedor.checked			=	true;
							}else{
								document.formulario.TipoFornecedor.checked			=	false;
							}
							
							if(TipoVendedor == '1'){
								document.formulario.TipoVendedor.checked			=	true;
							}else{
								document.formulario.TipoVendedor.checked			=	false;
							}
						
							if(Obs!=""){
								document.getElementById('cpHistorico').style.display = 'block';
								document.formulario.HistoricoObs.value 				 = Obs;
							}else{
								document.getElementById('cpHistorico').style.display = 'none';
								document.formulario.HistoricoObs.value 				 = "";
							}
						
							if(document.formulario.CampoExtra1 != undefined)
								document.formulario.CampoExtra1.value				= CampoExtra1;
							if(document.formulario.CampoExtra2 != undefined)	
								document.formulario.CampoExtra2.value 				= CampoExtra2;
							if(document.formulario.CampoExtra3 != undefined)
								document.formulario.CampoExtra3.value				= CampoExtra3;
							if(document.formulario.CampoExtra4 != undefined)
								document.formulario.CampoExtra4.value				= CampoExtra4;
								
							document.getElementById('cp_DataNascimento_Titulo').style.color = '#000';
							
							
							if(document.formulario.DataNascimento_Obrigatorio.value == 1) {
								document.getElementById('cp_DataNascimento_Titulo').style.color = '#c10000';
							}
							
							if(document.formulario.ObrigatoriedadeInscricaoEstadual.value == 1){		
								document.getElementById('tit_InscricaoEstadual').style.color	=	'#C10000';
							}else{
								document.getElementById('tit_InscricaoEstadual').style.color	=	'#000000';
							}
							document.getElementById('cp_DataNascimento_Titulo').style.backgroundColor	= '#FFFFFF';
							document.getElementById('cp_DataFundacao_Titulo').style.backgroundColor 	= '#FFFFFF';
							document.getElementById('cp_DataFundacao_Titulo').style.color 				= '#000000';
							document.getElementById('titEnderecoDefault').style.color					= '#C10000';
							document.getElementById('cp_Status').style.color							= CorStatus;
							document.getElementById('cp_Status').innerHTML								= Status;
							document.formulario.Acao.value 												= 'alterar';
							
							if(IdOrdemServico != ""){
								document.formulario.bt_relatorio.disabled	=	false;
							}else{
								document.formulario.bt_relatorio.disabled	=	true;
							}
							
							if(ContaDebito > 0){
								document.formulario.bt_contaDebito.disabled	=	false;
							}else{
								document.formulario.bt_contaDebito.disabled	=	true;
							}

							if(CartaoCredito > 0){
								document.formulario.bt_cartaoCredito.disabled	=	false;
							}else{
								document.formulario.bt_cartaoCredito.disabled	=	true;
							}
							
							while(document.getElementById('tableEndereco').rows.length > 0){
								document.getElementById('tableEndereco').deleteRow(0);
							}
							
							for(i=1;i<=QtdEndereco;i++){
								formulario_endereco();
							}
							
							document.getElementById('cp_dados_adicionais').style.display = "block";
							buscar_arquivo(IdPessoa);
							busca_atualizacao_cadastro(IdPessoa);
							busca_pessoa_endereco(IdPessoa,IdEnderecoDefault);
							verifica_email_cobranca();
							verificaAcao();
							dados_adicionais(IdPessoa);
							document.formulario.IdPessoa.focus();
							document.getElementById('cp_dadosCadastraisResumido').style.display = 'none';
							document.getElementById('cp_dadosCadastrais').style.display = 'block';
							document.getElementById('cp_Endereco_Outros').style.display = 'block';
							document.formulario.AtivarCadastroResumido.value = 2;
							document.formulario.CadastroResumido.style.display = 'none';
							document.getElementById('lb_CadasdroResumido').style.display = 'none';
							document.getElementById('descricao').style.width = '420px';
							
							break;
						case 'PessoaCPF':
							nameNode = xmlhttp.responseXML.getElementsByTagName("NomeRepresentante")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var NomeRepresentante = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("DataNascimento")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var DataNascimento = nameTextNode.nodeValue;	
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("Sexo")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var Sexo = nameTextNode.nodeValue;	
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("RG_IE")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var RG_IE = nameTextNode.nodeValue;	
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("EstadoCivil")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var EstadoCivil = nameTextNode.nodeValue;	
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("InscricaoMunicipal")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var InscricaoMunicipal = nameTextNode.nodeValue;	
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("ComplementoTelefone")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var ComplementoTelefone = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("Email")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var Email = nameTextNode.nodeValue;	
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("Site")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var Site = nameTextNode.nodeValue;	
													
							ativaPessoa(TipoPessoa);
							
							addParmUrl("marContrato","IdPessoa",IdPessoa);
							addParmUrl("marContratoNovo","IdPessoa",IdPessoa);
							addParmUrl("marContasReceber","IdPessoa",IdPessoa);
							addParmUrl("marLancamentoFinanceiro","IdPessoa",IdPessoa);
							addParmUrl("marProcessoFinanceiro","IdPessoa",IdPessoa);
							addParmUrl("marContaEventualNovo","IdPessoa",IdPessoa);
							addParmUrl("marContaEventual","IdPessoa",IdPessoa);
							addParmUrl("marProcessoFinanceiroNovo","IdPessoa",IdPessoa);
							addParmUrl("marReenvioMensagem","IdPessoa",IdPessoa);
							addParmUrl("marPessoa","IdPessoa",IdPessoa);
							addParmUrl("marProcessoFinanceiroNovo","IdPessoa",IdPessoa);
							addParmUrl("marProcessoFinanceiro","IdPessoa",IdPessoa);
							addParmUrl("marOrdemServicoNovo","IdPessoa",IdPessoa);
							addParmUrl("marOrdemServico","IdPessoa",IdPessoa);
							
							for(var i=0; i<document.formulario.Sexo.length; i++){
								if(document.formulario.Sexo[i].value == Sexo){
									document.formulario.Sexo[i].selected = true;
									i = document.formulario.Sexo.length;
								}							
							}							
							
							for(var i=0; i<document.formulario.EstadoCivil.length; i++){
								if(document.formulario.EstadoCivil[i].value == EstadoCivil){
									document.formulario.EstadoCivil[i].selected = true;
									i = document.formulario.EstadoCivil.length;
								}							
							}
							
							document.formulario.IdPessoa.value 				= IdPessoa;
							document.formulario.Nome.value 					= Nome;					
							document.formulario.TipoPessoa.value			= TipoPessoa;
							document.formulario.CPF_CNPJ.value 				= CPF_CNPJ;
							document.formulario.NomeFantasia.value 			= Nome;
							document.formulario.NomeRepresentante.value 	= NomeRepresentante;
							document.formulario.RazaoSocial.value 			= RazaoSocial;
							document.formulario.DataNascimento.value 		= dateFormat(DataNascimento);
							document.formulario.DataFundacao.value 			= dateFormat(DataNascimento);
							document.formulario.RG_IE.value 				= RG_IE;
							document.formulario.InscricaoEstadual.value		= RG_IE;
							document.formulario.Sexo.value					= Sexo;
							document.formulario.InscricaoMunicipal.value	= InscricaoMunicipal;
							document.formulario.Telefone1.value 			= Telefone1;
							document.formulario.Telefone2.value 			= Telefone2;
							document.formulario.Telefone3.value 			= Telefone3;
							document.formulario.Celular.value 				= Celular;
							document.formulario.Fax.value 					= Fax;
							document.formulario.ComplementoTelefone.value	= ComplementoTelefone;
							document.formulario.Email.value 				= Email;
							
							if(Site == ""){
								Site	=	"http://";
							}
							
							document.formulario.Site.value 					= Site;
							
							if(document.formulario.CPF_CNPJ_Obrigatorio.value == 1){
								document.getElementById('cp_CPF_CNPJ_Titulo').style.color = '#C10000';
							}else{
								document.getElementById('cp_CPF_CNPJ_Titulo').style.color = '#000';
							}
							
							document.getElementById('cp_CPF_CNPJ_Titulo').style.backgroundColor = '#FFF';
							
							validar_CPF_CNPJ(CPF_CNPJ);
							
							document.formulario.Acao.value 					= 'alterar';
							
							verificaAcao();
							
							break;
						case 'PessoaSolicitacao':
							nameNode = xmlhttp.responseXML.getElementsByTagName("NomeRepresentante")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var NomeRepresentante = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("DataNascimento")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var DataNascimento = nameTextNode.nodeValue;	
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("Sexo")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var Sexo = nameTextNode.nodeValue;	
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("RG_IE")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var RG_IE = nameTextNode.nodeValue;	
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("EstadoCivil")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var EstadoCivil = nameTextNode.nodeValue;	
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("InscricaoMunicipal")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var InscricaoMunicipal = nameTextNode.nodeValue;	
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("ComplementoTelefone")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var ComplementoTelefone = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("Email")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var Email = nameTextNode.nodeValue;	
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("Site")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var Site = nameTextNode.nodeValue;	
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("CampoExtra1")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var CampoExtra1 = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("CampoExtra2")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var CampoExtra2 = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("CampoExtra3")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var CampoExtra3 = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("CampoExtra4")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var CampoExtra4 = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("IdEnderecoDefault")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var IdEnderecoDefault = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("QtdEndereco")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var QtdEndereco = nameTextNode.nodeValue;
							
							ativaPessoaAnterior(TipoPessoa);
							
							addParmUrl("marContrato","IdPessoa",IdPessoa);
							addParmUrl("marContratoNovo","IdPessoa",IdPessoa);
							addParmUrl("marContasReceber","IdPessoa",IdPessoa);
							addParmUrl("marLancamentoFinanceiro","IdPessoa",IdPessoa);
							addParmUrl("marProcessoFinanceiro","IdPessoa",IdPessoa);
							addParmUrl("marContaEventualNovo","IdPessoa",IdPessoa);
							addParmUrl("marContaEventual","IdPessoa",IdPessoa);
							addParmUrl("marProcessoFinanceiroNovo","IdPessoa",IdPessoa);
							addParmUrl("marReenvioMensagem","IdPessoa",IdPessoa);
							addParmUrl("marPessoa","IdPessoa",IdPessoa);
							addParmUrl("marProcessoFinanceiroNovo","IdPessoa",IdPessoa);
							addParmUrl("marProcessoFinanceiro","IdPessoa",IdPessoa);
							addParmUrl("marOrdemServicoNovo","IdPessoa",IdPessoa);
							addParmUrl("marOrdemServico","IdPessoa",IdPessoa);
							
							while(document.formulario.IdEnderecoDefaultAnterior.options.length > 0){
								document.formulario.IdEnderecoDefaultAnterior.options[0] = null;
							}
							
							for(var i=0; i<document.formulario.SexoAnterior.length; i++){
								if(document.formulario.SexoAnterior[i].value == Sexo){
									document.formulario.SexoAnterior[i].selected = true;
									i = document.formulario.SexoAnterior.length;
								}							
							}							
							
							for(var i=0; i<document.formulario.EstadoCivilAnterior.length; i++){
								if(document.formulario.EstadoCivilAnterior[i].value == EstadoCivil){
									document.formulario.EstadoCivilAnterior[i].selected = true;
									i = document.formulario.EstadoCivilAnterior.length;
								}							
							}
							
							document.formulario.NomeAnterior.value 					= Nome;					
							document.formulario.NomeFantasiaAnterior.value 			= Nome;
							document.formulario.NomeRepresentanteAnterior.value 	= NomeRepresentante;
							document.formulario.RazaoSocialAnterior.value 			= RazaoSocial;
							document.formulario.DataNascimentoAnterior.value 		= dateFormat(DataNascimento);
							document.formulario.DataFundacaoAnterior.value 			= dateFormat(DataNascimento);
							document.formulario.RG_IEAnterior.value 				= RG_IE;
							document.formulario.InscricaoEstadualAnterior.value		= RG_IE;
							document.formulario.SexoAnterior.value 					= Sexo;
							document.formulario.InscricaoMunicipalAnterior.value	= InscricaoMunicipal;
							document.formulario.Telefone1Anterior.value 			= Telefone1;
							document.formulario.Telefone2Anterior.value 			= Telefone2;
							document.formulario.Telefone3Anterior.value 			= Telefone3;
							document.formulario.CelularAnterior.value 				= Celular;
							document.formulario.FaxAnterior.value 					= Fax;
							document.formulario.ComplementoTelefoneAnterior.value	= ComplementoTelefone;
							document.formulario.EmailAnterior.value 				= Email;
							document.formulario.QtdEnderecoAnterior.value			= 0;
							document.formulario.QtdEnderecoAuxAnterior.value		= 0;
							
							if(Site == ""){
								Site	=	"http://";
							}
							
							document.formulario.SiteAnterior.value 					= Site;
							
							while(document.getElementById('tableEnderecoAnterior').rows.length > 0){
								document.getElementById('tableEnderecoAnterior').deleteRow(0);
							}
							
							for(i=1;i<=QtdEndereco;i++){
								formulario_endereco_anterior();
							}
							
							busca_pessoa_endereco_anterior(IdPessoa,IdEnderecoDefault);
							
							if(document.formulario.CampoExtra1Anterior != undefined){
								document.formulario.CampoExtra1Anterior.value				= CampoExtra1;
								if(document.formulario.CampoExtra1.value != document.formulario.CampoExtra1Anterior.value){
									document.formulario.CampoExtra1.style.border			= '1px #C10000 solid';
									document.formulario.CampoExtra1.style.backgroundColor	= '#FFEAEA';
								}
							}
							if(document.formulario.CampoExtra2Anterior != undefined){	
								document.formulario.CampoExtra2Anterior.value 				= CampoExtra2;
								if(document.formulario.CampoExtra2.value != document.formulario.CampoExtra2Anterior.value){
									document.formulario.CampoExtra2.style.border			= '1px #C10000 solid';
									document.formulario.CampoExtra2.style.backgroundColor	= '#FFEAEA';
								}
							}
							if(document.formulario.CampoExtra3Anterior != undefined){
								document.formulario.CampoExtra3Anterior.value				= CampoExtra3;
								if(document.formulario.CampoExtra3.value != document.formulario.CampoExtra3Anterior.value){
									document.formulario.CampoExtra3.style.border			= '1px #C10000 solid';
									document.formulario.CampoExtra3.style.backgroundColor	= '#FFEAEA';
								}
							}
							if(document.formulario.CampoExtra4Anterior != undefined){
								document.formulario.CampoExtra4Anterior.value				= CampoExtra4;
								if(document.formulario.CampoExtra4.value != document.formulario.CampoExtra4Anterior.value){
									document.formulario.CampoExtra4.style.border			= '1px #C10000 solid';
									document.formulario.CampoExtra4.style.backgroundColor	= '#FFEAEA';
								}
							}
							
							if(document.formulario.Nome.value != document.formulario.NomeAnterior.value){
								document.formulario.Nome.style.border					= '1px #C10000 solid';
								document.formulario.Nome.style.backgroundColor			= '#FFEAEA';
							}
							if(document.formulario.NomeFantasia.value != document.formulario.NomeFantasiaAnterior.value){
								document.formulario.NomeFantasia.style.border 			= '1px #C10000 solid';
								document.formulario.NomeFantasia.style.backgroundColor			= '#FFEAEA';
							}
							if(document.formulario.NomeRepresentante.value != document.formulario.NomeRepresentanteAnterior.value){ 
								document.formulario.NomeRepresentante.style.border			= '1px #C10000 solid';
								document.formulario.NomeRepresentante.style.backgroundColor	= '#FFEAEA';
							}
							if(document.formulario.RazaoSocial.value != document.formulario.RazaoSocialAnterior.value){
								document.formulario.RazaoSocial.style.border 			= '1px #C10000 solid';
								document.formulario.RazaoSocial.style.backgroundColor	= '#FFEAEA';
							}
							if(document.formulario.DataNascimento.value != document.formulario.DataNascimentoAnterior.value){
								document.formulario.DataNascimento.style.border 			= '1px #C10000 solid';
								document.formulario.DataNascimento.style.backgroundColor	= '#FFEAEA';
							}
							if(document.formulario.DataFundacao.value != document.formulario.DataFundacaoAnterior.value){
								document.formulario.DataFundacao.style.border 			= '1px #C10000 solid';
								document.formulario.DataFundacao.style.backgroundColor	= '#FFEAEA';
							}
							if(document.formulario.Sexo.value != document.formulario.SexoAnterior.value){
								document.formulario.Sexo.style.border 					= '1px #C10000 solid';
								document.formulario.Sexo.style.backgroundColor			= '#FFEAEA';
							}
							if(document.formulario.RG_IE.value != document.formulario.RG_IEAnterior.value){
								document.formulario.RG_IE.style.border 					= '1px #C10000 solid';
								document.formulario.RG_IE.style.backgroundColor			= '#FFEAEA';
							}
							if(document.formulario.InscricaoEstadual.value != document.formulario.InscricaoEstadualAnterior.value){
								document.formulario.InscricaoEstadual.style.border 			= '1px #C10000 solid';
								document.formulario.InscricaoEstadual.style.backgroundColor	= '#FFEAEA';
							}
							if(document.formulario.EstadoCivil.value != document.formulario.EstadoCivilAnterior.value){
								document.formulario.EstadoCivil.style.border 			= '1px #C10000 solid';
								document.formulario.EstadoCivil.style.backgroundColor	= '#FFEAEA';
							}
							if(document.formulario.InscricaoMunicipal.value != document.formulario.InscricaoMunicipalAnterior.value){
								document.formulario.InscricaoMunicipal.style.border				= '1px #C10000 solid';
								document.formulario.InscricaoMunicipal.style.backgroundColor	= '#FFEAEA';
							}
							if(document.formulario.Telefone1.value != document.formulario.Telefone1Anterior.value){
								document.formulario.Telefone1.style.border				= '1px #C10000 solid';
								document.formulario.Telefone1.style.backgroundColor		= '#FFEAEA';
							}
							if(document.formulario.Telefone2.value != document.formulario.Telefone2Anterior.value){
								document.formulario.Telefone2.style.border				= '1px #C10000 solid';
								document.formulario.Telefone2.style.backgroundColor		= '#FFEAEA';
							}
							if(document.formulario.Telefone3.value != document.formulario.Telefone3Anterior.value){
								document.formulario.Telefone3.style.border				= '1px #C10000 solid';
								document.formulario.Telefone3.style.backgroundColor		= '#FFEAEA';
							}
							if(document.formulario.Celular.value != document.formulario.CelularAnterior.value){
								document.formulario.Celular.style.border				= '1px #C10000 solid';
								document.formulario.Celular.style.backgroundColor		= '#FFEAEA';
							}
							if(document.formulario.Fax.value != document.formulario.FaxAnterior.value){
								document.formulario.Fax.style.border 					= '1px #C10000 solid';
								document.formulario.Fax.style.backgroundColor			= '#FFEAEA';
							}
							if(document.formulario.ComplementoTelefone.value != document.formulario.ComplementoTelefoneAnterior.value){
								document.formulario.ComplementoTelefone.style.border			= '1px #C10000 solid';
								document.formulario.ComplementoTelefone.style.backgroundColor	= '#FFEAEA';
							}
							if(document.formulario.Email.value != document.formulario.EmailAnterior.value){
								document.formulario.Email.style.border 					= '1px #C10000 solid';
								document.formulario.Email.style.backgroundColor			= '#FFEAEA';
							}
							if(document.formulario.Site.value != document.formulario.SiteAnterior.value){
								document.formulario.Site.style.border 					= '1px #C10000 solid';
								document.formulario.Site.style.backgroundColor			= '#FFEAEA';
							}
							if(document.formulario.IdEnderecoDefaultTemp.value != IdEnderecoDefault){
								document.formulario.IdEnderecoDefault.style.border 					= '1px #C10000 solid';
								document.formulario.IdEnderecoDefault.style.backgroundColor			= '#FFEAEA';
							}
							break;
						case 'AdicionarProcessoFinanceiro':
							var cont = 0; ii='';
							if(ListarCampo == '' || ListarCampo == undefined){
								if(document.formulario.Filtro_IdPessoa.value == ''){
									document.formulario.Filtro_IdPessoa.value = IdPessoa;
									ii = 0;
								}else{
									var tempFiltro	=	document.formulario.Filtro_IdPessoa.value.split(',');
										
									ii=0; 
									while(tempFiltro[ii] != undefined){
										if(tempFiltro[ii] != IdPessoa){
											cont++;		
										}
										ii++;
									}
									if(ii == cont){
										document.formulario.Filtro_IdPessoa.value = document.formulario.Filtro_IdPessoa.value + "," + IdPessoa;
									}
								}
							}else{
								ii=0;
							}
							if(ii == cont){
							
								nameNode = xmlhttp.responseXML.getElementsByTagName("SiglaEstado")[0]; 
								nameTextNode = nameNode.childNodes[0];
								var SiglaEstado = nameTextNode.nodeValue;
														
								var tam, linha, c0, c1, c2, c3, c4, c5, c6, c7;
								
								tam 	= document.getElementById('tabelaPessoa').rows.length;
								linha	= document.getElementById('tabelaPessoa').insertRow(tam-1);
								
								if(tam%2 != 0){
									linha.style.backgroundColor = "#E2E7ED";
								}
								
								linha.accessKey 			= IdPessoa; 
								
								c0	= linha.insertCell(0);	
								c1	= linha.insertCell(1);	
								c2	= linha.insertCell(2);	
								c3	= linha.insertCell(3);
								c4	= linha.insertCell(4);
								c5	= linha.insertCell(5);
								c6	= linha.insertCell(6);
								c7	= linha.insertCell(7);
								
								if(Telefone1 == ''){
									if(Telefone2 == ''){
										if(Telefone3 == ''){
											if(Celular == ''){
												if(Fax != ''){
													Telefone1	=	Fax;
												}
											}else{
												Telefone1	=	Celular;
											}
										}else{
											Telefone1	=	Telefone3;
										}
									}else{
										Telefone1	=	Telefone2;
									}
								}
								
								var linkIni = "<a href='cadastro_pessoa.php?IdPessoa="+IdPessoa+"'>";
								var linkFim = "</a>";
								
								c0.innerHTML = linkIni + IdPessoa + linkFim;
								c0.style.padding =	"0 0 0 5px";
								
								c1.innerHTML = linkIni + RazaoSocial.substr(0,30); + linkFim;
								
								c2.innerHTML = linkIni + Nome.substr(0,30) + linkFim;
								
								c3.innerHTML = linkIni + Telefone1 + linkFim;
								
								c4.innerHTML = linkIni + CPF_CNPJ + linkFim;
								
								c5.innerHTML = linkIni + NomeCidade + linkFim;
								
								c6.innerHTML = linkIni + SiglaEstado + linkFim;
								
								if(document.formulario.IdStatus.value == 1){
									c7.innerHTML = "<img src='../../img/estrutura_sistema/ico_del.gif' alt='Excluir?' onClick=\"remover_filtro_pessoa("+IdPessoa+")\"></tr>";
								}else{
									c7.innerHTML = "<img src='../../img/estrutura_sistema/ico_del_c.gif' alt='Excluir?'\">";
								}
								c7.style.textAlign = "center";
								c7.style.cursor = "pointer";
								
								if(document.formulario.IdProcessoFinanceiro.value == ''){
									document.getElementById('totaltabelaPessoa').innerHTML	=	'Total: '+(ii+1);
								}else{
									if(document.formulario.Erro.value != ''){
										scrollWindow('bottom');
									}
								}
							}
							document.formulario.IdPessoa.value 			= "";
							document.formulario.Nome.value 				= "";
							break;
						case 'AdicionarMalaDireta':
							var cont = 0; ii='';
							if(ListarCampo == '' || ListarCampo == undefined){
								if(document.formulario.Filtro_IdPessoa.value == ''){
									document.formulario.Filtro_IdPessoa.value = IdPessoa;
									ii = 0;
								}else{
									var tempFiltro	=	document.formulario.Filtro_IdPessoa.value.split(',');
										
									ii=0; 
									while(tempFiltro[ii] != undefined){
										if(tempFiltro[ii] != IdPessoa){
											cont++;		
										}
										ii++;
									}
									if(ii == cont){
										document.formulario.Filtro_IdPessoa.value = document.formulario.Filtro_IdPessoa.value + "," + IdPessoa;
									}
								}
							}else{
								ii=0;
							}
							if(ii == cont){
							
								nameNode = xmlhttp.responseXML.getElementsByTagName("SiglaEstado")[0]; 
								nameTextNode = nameNode.childNodes[0];
								var SiglaEstado = nameTextNode.nodeValue;
														
								var tam, linha, c0, c1, c2, c3, c4, c5, c6, c7;
								
								tam 	= document.getElementById('tabelaPessoa').rows.length;
								linha	= document.getElementById('tabelaPessoa').insertRow(tam-1);
								
								if(tam%2 != 0){
									linha.style.backgroundColor = "#E2E7ED";
								}
								
								linha.accessKey 			= IdPessoa; 
								
								c0	= linha.insertCell(0);	
								c1	= linha.insertCell(1);	
								c2	= linha.insertCell(2);	
								c3	= linha.insertCell(3);
								c4	= linha.insertCell(4);
								c5	= linha.insertCell(5);
								c6	= linha.insertCell(6);
								c7	= linha.insertCell(7);
								
								if(Telefone1 == ''){
									if(Telefone2 == ''){
										if(Telefone3 == ''){
											if(Celular == ''){
												if(Fax != ''){
													Telefone1	=	Fax;
												}
											}else{
												Telefone1	=	Celular;
											}
										}else{
											Telefone1	=	Telefone3;
										}
									}else{
										Telefone1	=	Telefone2;
									}
								}
								
								var linkIni = "<a href='cadastro_pessoa.php?IdPessoa="+IdPessoa+"'>";
								var linkFim = "</a>";
								
								c0.innerHTML = linkIni + IdPessoa + linkFim;
								c0.style.padding =	"0 0 0 5px";
								
								c1.innerHTML = linkIni + RazaoSocial.substr(0,30); + linkFim;
								
								c2.innerHTML = linkIni + Nome.substr(0,30) + linkFim;
								
								c3.innerHTML = linkIni + Telefone1 + linkFim;
								
								c4.innerHTML = linkIni + CPF_CNPJ + linkFim;
								
								c5.innerHTML = linkIni + NomeCidade + linkFim;
								
								c6.innerHTML = linkIni + SiglaEstado + linkFim;
								
								if(document.formulario.IdStatus.value == 1){
									c7.innerHTML = "<img src='../../img/estrutura_sistema/ico_del.gif' alt='Excluir?' onClick=\"remover_filtro_pessoa("+IdPessoa+")\"></tr>";
								}else{
									c7.innerHTML = "<img src='../../img/estrutura_sistema/ico_del_c.gif' alt='Excluir?'\">";
								}
								c7.style.textAlign = "center";
								c7.style.cursor = "pointer";
								
								document.getElementById('totaltabelaPessoa').innerHTML	=	'Total: '+(ii+1);
							}
							document.formulario.IdPessoa.value 			= "";
							document.formulario.Nome.value 				= "";
							break;
						case 'AdicionarLoteRepasse':
							var cont = 0; ii='';
							if(ListarCampo == '' || ListarCampo == undefined){
								if(document.formulario.Filtro_IdPessoa.value == ''){
									document.formulario.Filtro_IdPessoa.value = IdPessoa;
									ii = 0;
								}else{
									var tempFiltro	=	document.formulario.Filtro_IdPessoa.value.split(',');
										
									ii=0; 
									while(tempFiltro[ii] != undefined){
										if(tempFiltro[ii] != IdPessoa){
											cont++;		
										}
										ii++;
									}
									if(ii == cont){
										document.formulario.Filtro_IdPessoa.value = document.formulario.Filtro_IdPessoa.value + "," + IdPessoa;
									}
								}
							}else{
								ii=0;
							}
							if(ii == cont){
							
								nameNode = xmlhttp.responseXML.getElementsByTagName("SiglaEstado")[0]; 
								nameTextNode = nameNode.childNodes[0];
								var SiglaEstado = nameTextNode.nodeValue;
														
								var tam, linha, c0, c1, c2, c3, c4, c5, c6, c7;
								
								tam 	= document.getElementById('tabelaPessoa').rows.length;
								linha	= document.getElementById('tabelaPessoa').insertRow(tam-1);
								
								if(tam%2 != 0){
									linha.style.backgroundColor = "#E2E7ED";
								}
								
								linha.accessKey 			= IdPessoa; 
								
								c0	= linha.insertCell(0);	
								c1	= linha.insertCell(1);	
								c2	= linha.insertCell(2);	
								c3	= linha.insertCell(3);
								c4	= linha.insertCell(4);
								c5	= linha.insertCell(5);
								c6	= linha.insertCell(6);
								c7	= linha.insertCell(7);
								
								if(Telefone1 == ''){
									if(Telefone2 == ''){
										if(Telefone3 == ''){
											if(Celular == ''){
												if(Fax != ''){
													Telefone1	=	Fax;
												}
											}else{
												Telefone1	=	Celular;
											}
										}else{
											Telefone1	=	Telefone3;
										}
									}else{
										Telefone1	=	Telefone2;
									}
								}
								
								var linkIni = "<a href='cadastro_pessoa.php?IdPessoa="+IdPessoa+"'>";
								var linkFim = "</a>";
								
								c0.innerHTML = linkIni + IdPessoa + linkFim;
								c0.style.padding =	"0 0 0 5px";
								
								c1.innerHTML = linkIni + RazaoSocial.substr(0,30); + linkFim;
								
								c2.innerHTML = linkIni + Nome.substr(0,30) + linkFim;
								
								c3.innerHTML = linkIni + Telefone1 + linkFim;
								
								c4.innerHTML = linkIni + CPF_CNPJ + linkFim;
								
								c5.innerHTML = linkIni + NomeCidade + linkFim;
								
								c6.innerHTML = linkIni + SiglaEstado + linkFim;
								
								if(document.formulario.IdStatus.value == 3 || document.formulario.IdStatus.value == 2){
									c7.innerHTML = "<img src='../../img/estrutura_sistema/ico_del_c.gif' alt='Excluir?'\">";
								}else{
									c7.innerHTML = "<img src='../../img/estrutura_sistema/ico_del.gif' alt='Excluir?' onClick=\"remover_filtro_pessoa("+IdPessoa+")\"></tr>";
								}
								c7.style.textAlign = "center";
								c7.style.cursor = "pointer";
								
								if(document.formulario.IdLoteRepasse.value == ''){
									document.getElementById('totaltabelaPessoa').innerHTML	=	'Total: '+(ii+1);
								}else{
									if(document.formulario.Erro.value != ''){
										scrollWindow('bottom');
									}
								}
							}
							document.formulario.IdPessoaFiltro.value 			= "";
							document.formulario.NomeFiltro.value 				= "";
							break;
						case 'AdicionarEtiqueta':
							var cont = 0; ii='';
							if(ListarCampo == '' || ListarCampo == undefined){
								if(document.formulario.Filtro_IdPessoa.value == ''){
									document.formulario.Filtro_IdPessoa.value = IdPessoa;
									ii = 0;
								}else{
									var tempFiltro	=	document.formulario.Filtro_IdPessoa.value.split(',');
										
									ii=0; 
									while(tempFiltro[ii] != undefined){
										if(tempFiltro[ii] != IdPessoa){
											cont++;		
										}
										ii++;
									}
									if(ii == cont){
										document.formulario.Filtro_IdPessoa.value = document.formulario.Filtro_IdPessoa.value + "," + IdPessoa;
									}
								}
							}else{
								ii=0;
							}
							if(ii == cont){
							
								nameNode = xmlhttp.responseXML.getElementsByTagName("SiglaEstado")[0]; 
								nameTextNode = nameNode.childNodes[0];
								var SiglaEstado = nameTextNode.nodeValue;
														
								var tam, linha, c0, c1, c2, c3, c4;
								
								tam 	= document.getElementById('tabelaPessoa').rows.length;
								linha	= document.getElementById('tabelaPessoa').insertRow(tam-1);
								
								if(tam%2 != 0){
									linha.style.backgroundColor = "#E2E7ED";
								}
								
								linha.accessKey 			= IdPessoa; 
								
								c0	= linha.insertCell(0);	
								c1	= linha.insertCell(1);	
								c2	= linha.insertCell(2);	
								c3	= linha.insertCell(3);
								c4	= linha.insertCell(4);
								c5	= linha.insertCell(5);
								c6	= linha.insertCell(6);
								c7	= linha.insertCell(7);
								
								if(Telefone1 == ''){
									if(Telefone2 == ''){
										if(Telefone3 == ''){
											if(Celular == ''){
												if(Fax != ''){
													Telefone1	=	Fax;
												}
											}else{
												Telefone1	=	Celular;
											}
										}else{
											Telefone1	=	Telefone3;
										}
									}else{
										Telefone1	=	Telefone2;
									}
								}
								
								var linkIni = "<a href='cadastro_pessoa.php?IdPessoa="+IdPessoa+"'>";
								var linkFim = "</a>";
								
								c0.innerHTML = linkIni + IdPessoa + linkFim;
								c0.style.padding =	"0 0 0 5px";
								

								c1.innerHTML = linkIni + RazaoSocial.substr(0,30); + linkFim;
								
								c2.innerHTML = linkIni + Nome.substr(0,30) + linkFim;
								
								c3.innerHTML = linkIni + Telefone1 + linkFim;
								
								c4.innerHTML = linkIni + CPF_CNPJ + linkFim;
								
								c5.innerHTML = linkIni + NomeCidade + linkFim;
								
								c6.innerHTML = linkIni + SiglaEstado + linkFim;
								
								c7.innerHTML = "<img src='../../img/estrutura_sistema/ico_del.gif' alt='Excluir?' onClick=\"remover_filtro_pessoa("+IdPessoa+")\"></tr>";
								c7.style.textAlign = "center";
								c7.style.cursor = "pointer";
								
								document.getElementById('totaltabelaPessoa').innerHTML	=	'Total: '+(ii+1);
							}
							document.formulario.IdPessoaFiltro.value 			= "";
							document.formulario.NomeFiltro.value 				= "";
							break;
						case 'AdicionarAgenteAutorizado':
							var cont = 0; ii='';
							if(ListarCampo == '' || ListarCampo == undefined){
								if(document.formulario.Filtro_IdAgenteAutorizado.value == ''){
									document.formulario.Filtro_IdAgenteAutorizado.value = IdPessoa;
									ii = 0;
								}else{
									var tempFiltro	=	document.formulario.Filtro_IdAgenteAutorizado.value.split(',');
										
									ii=0; 
									while(tempFiltro[ii] != undefined){
										if(tempFiltro[ii] != IdPessoa){
											cont++;		
										}
										ii++;
									}
									if(ii == cont){
										document.formulario.Filtro_IdAgenteAutorizado.value = document.formulario.Filtro_IdAgenteAutorizado.value + "," + IdPessoa;
									}
								}
							}else{
								ii=0;
							}
							if(ii == cont){
							
								nameNode = xmlhttp.responseXML.getElementsByTagName("SiglaEstado")[0]; 
								nameTextNode = nameNode.childNodes[0];
								var SiglaEstado = nameTextNode.nodeValue;
														
								var tam, linha, c0, c1, c2, c3, c4, c5, c6, c7;
								
								tam 	= document.getElementById('tabelaAgente').rows.length;
								linha	= document.getElementById('tabelaAgente').insertRow(tam-1);
								
								if(tam%2 != 0){
									linha.style.backgroundColor = "#E2E7ED";
								}
								
								linha.accessKey 			= IdPessoa; 
								
								c0	= linha.insertCell(0);	
								c1	= linha.insertCell(1);	
								c2	= linha.insertCell(2);	
								c3	= linha.insertCell(3);
								c4	= linha.insertCell(4);
								c5	= linha.insertCell(5);
								c6	= linha.insertCell(6);
								c7	= linha.insertCell(7);
								
								if(Telefone1 == ''){
									if(Telefone2 == ''){
										if(Telefone3 == ''){
											if(Celular == ''){
												if(Fax != ''){
													Telefone1	=	Fax;
												}
											}else{
												Telefone1	=	Celular;
											}
										}else{
											Telefone1	=	Telefone3;
										}
									}else{
										Telefone1	=	Telefone2;
									}
								}
								
								var linkIni = "<a href='cadastro_agente.php?IdPessoa="+IdPessoa+"'>";
								var linkFim = "</a>";
								
								c0.innerHTML = linkIni + IdPessoa + linkFim;
								c0.style.padding =	"0 0 0 5px";
								
								c1.innerHTML = linkIni + RazaoSocial.substr(0,30); + linkFim;
								
								c2.innerHTML = linkIni + Nome.substr(0,30) + linkFim;
								
								c3.innerHTML = linkIni + Telefone1 + linkFim;
								
								c4.innerHTML = linkIni + CPF_CNPJ + linkFim;
								
								c5.innerHTML = linkIni + NomeCidade + linkFim;
								
								c6.innerHTML = linkIni + SiglaEstado + linkFim;
								
								if(document.formulario.IdStatus.value == 1 || document.formulario.IdStatus.value == ''){
									c7.innerHTML = "<img src='../../img/estrutura_sistema/ico_del.gif' alt='Excluir?' onClick=\"remover_filtro_agente("+IdPessoa+")\"></tr>";
								}else{
									c7.innerHTML = "<img src='../../img/estrutura_sistema/ico_del_c.gif' alt='Excluir?'\">";
								}
								c7.style.textAlign = "center";
								c7.style.cursor = "pointer";
								
								if(document.formulario.IdLoteRepasse.value == ''){
									document.getElementById('totaltabelaAgente').innerHTML	=	'Total: '+(ii+1);
								}else{
									if(document.formulario.Erro.value != ''){
										scrollWindow('bottom');
									}
								}
							}
							document.formulario.IdAgenteAutorizado.value 			= "";
							document.formulario.NomeAgenteAutorizado.value			= "";
							break;
						case "AgenteAutorizado":
							document.formulario.IdAgenteAutorizado.value	= IdPessoa;
							document.formulario.Nome.value 					= Nome;
							break;
						case "Carteira":
							document.formulario.IdCarteira.value		= IdPessoa;
							document.formulario.Nome.value 				= Nome;
							document.formulario.Acao.value				= 'inserir';
							
							verificaAcao();
							break;
						case "Terceiro":
							document.formulario.IdPessoa.value 			= IdPessoa;
							document.formulario.Nome.value 				= Nome;
							
							document.formulario.IdPessoa.focus();
							break;
						case "Fornecedor":
							document.formulario.IdPessoa.value 			= IdPessoa;
							document.formulario.Nome.value 				= Nome;
							document.formulario.CPF_CNPJ.value 			= CPF_CNPJ;
							
							document.formulario.IdPessoa.focus();
							break;
						case "ProcessoFinanceiro":
							document.formulario.IdPessoa.value 			= IdPessoa;
							document.formulario.Nome.value 				= Nome;
							break;
						case "MalaDireta":
							document.formulario.IdPessoa.value 			= IdPessoa;
							document.formulario.Nome.value 				= Nome;
							break;
						case "LoteRepasse":
							document.formulario.IdPessoaFiltro.value 	= IdPessoa;
							document.formulario.NomeFiltro.value 		= Nome;
							break;
						case "Etiqueta":
							document.formulario.IdPessoaFiltro.value 	= IdPessoa;
							document.formulario.NomeFiltro.value 		= Nome;
							break;
						case "DeclaracaoPagamento":
							document.formulario.IdPessoaFiltro.value 	= IdPessoa;
							document.formulario.NomeFiltro.value 		= Nome;
							break;
						case "NotaFiscalEntrada":
							document.formulario.IdPessoa.value 				= IdPessoa;
							document.formulario.RazaoSocial.value 			= RazaoSocial;
							document.formulario.Nome.value 					= Nome;
							document.formulario.CPF_CNPJ.value 				= CPF_CNPJ;
							document.formulario.CPF_CNPJFornecedor.value	= CPF_CNPJ;
							break;
						default:

							nameNode = xmlhttp.responseXML.getElementsByTagName("NomeCidade")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var NomeCidade = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("Email")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var Email = nameTextNode.nodeValue;
							
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
					}
					
					switch(Local){
						case 'Contrato':
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("IdEnderecoDefault")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var IdEnderecoDefault = nameTextNode.nodeValue;	
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("DataNascimento")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var DataNascimento = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("RG_IE")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var RG_IE = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("Telefone1")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var Telefone1 = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("Telefone2")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var Telefone2 = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("Telefone3")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var Telefone3 = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("Celular")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var Celular = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("Fax")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var Fax = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("ComplementoTelefone")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var ComplementoTelefone = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("NomeRepresentante")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var NomeRepresentante = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("Email")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var EmailJuridica = nameTextNode.nodeValue;
							
							document.formulario.NomeRepresentante.value 			= NomeRepresentante;
							document.formulario.InscricaoEstadual.value 			= RG_IE;
							document.formulario.DataNascimento.value 				= dateFormat(DataNascimento);	
							document.formulario.RG.value 							= RG_IE;
							document.formulario.Telefone1.value 					= Telefone1;
							document.formulario.Telefone2.value 					= Telefone2;
							document.formulario.Telefone3.value 					= Telefone3;
							document.formulario.Celular.value 						= Celular;	
							document.formulario.Fax.value 							= Fax;
							document.formulario.ComplementoTelefone.value 			= ComplementoTelefone;
							document.formulario.EmailJuridica.value 				= EmailJuridica;
							document.formulario.IdEnderecoDefault.value 			= IdEnderecoDefault;
							
							addParmUrl("marPessoa","IdPessoa",IdPessoa);
							addParmUrl("marContrato","IdPessoa",IdPessoa);
							addParmUrl("marContratoNovo","IdPessoa",IdPessoa);
							addParmUrl("marLancamentoFinanceiro","IdPessoa",IdPessoa);
							//addParmUrl("marReenvioMensagem","IdPessoa",IdPessoa); Leonardo - Se estou no Contrato nao necessito desta linha.
							addParmUrl("marContasReceber","IdPessoa",IdPessoa);
							addParmUrl("marContaEventual","IdPessoa",IdPessoa);
							addParmUrl("marContaEventualNovo","IdPessoa",IdPessoa);
							
							if(document.formulario.Acao.value == 'inserir'){
								listar_contrato_agrupador(IdPessoa);
							
								if(document.formulario.IdServico.value != ""){
									busca_servico('');
								}
								
								busca_dia_cobranca(IdPessoa,document.formulario.DiaCobrancaDefault.value);
							
								nameNode = xmlhttp.responseXML.getElementsByTagName("IdContrato")[0]; 
								nameTextNode = nameNode.childNodes[0];
								var IdContrato = nameTextNode.nodeValue;
								
								if(document.formulario.AvisoContrato.value == '1'){
									if(confirm("ATENCAO!\n\nEste cliente já possui um contrato.\nDeseja continuar?","SIM","NAO") == false){
										busca_pessoa(0);
										document.formulario.IdPessoa.focus();
									}
								}
								
								busca_opcoes_pessoa_endereco(IdPessoa,IdEnderecoDefault,IdEnderecoDefault);
							}
							break;
						case 'ContaDebito':
							nameNode = xmlhttp.responseXML.getElementsByTagName("IdEnderecoDefault")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var IdEnderecoDefault = nameTextNode.nodeValue;	
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("DataNascimento")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var DataNascimento = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("RG_IE")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var RG_IE = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("Telefone1")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var Telefone1 = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("Telefone2")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var Telefone2 = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("Telefone3")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var Telefone3 = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("Celular")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var Celular = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("Fax")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var Fax = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("ComplementoTelefone")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var ComplementoTelefone = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("NomeRepresentante")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var NomeRepresentante = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("Email")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var EmailJuridica = nameTextNode.nodeValue;
							
							document.formulario.NomeRepresentante.value 		= NomeRepresentante;
							document.formulario.InscricaoEstadual.value 		= RG_IE;
							document.formulario.DataNascimento.value 			= dateFormat(DataNascimento);	
							document.formulario.RG.value 						= RG_IE;
							document.formulario.Telefone1.value 				= Telefone1;
							document.formulario.Telefone2.value 				= Telefone2;
							document.formulario.Telefone3.value 				= Telefone3;
							document.formulario.Celular.value 					= Celular;	
							document.formulario.Fax.value 						= Fax;
							document.formulario.ComplementoTelefone.value 		= ComplementoTelefone;
							document.formulario.EmailJuridica.value 			= EmailJuridica;
							
							if(document.formulario.IdPessoaTemp.value != IdPessoa){
								limpar_formulario_conta_debito();
								buscar_local_cobranca();
							}
							
							addParmUrl("marPessoa","IdPessoa",IdPessoa);
							addParmUrl("marPessoaNovo", "IdPessoa", IdPessoa);
							addParmUrl("marContrato","IdPessoa",IdPessoa);
							addParmUrl("marContratoNovo","IdPessoa",IdPessoa);
							addParmUrl("marLancamentoFinanceiro","IdPessoa",IdPessoa);
							addParmUrl("marReenvioMensagem","IdPessoa",IdPessoa);
							addParmUrl("marContasReceber","IdPessoa",IdPessoa);
							addParmUrl("marContaEventual","IdPessoa",IdPessoa);
							addParmUrl("marContaEventualNovo","IdPessoa",IdPessoa);
							addParmUrl("marOrdemServico","IdPessoa",IdPessoa);
							addParmUrl("marOrdemServicoNovo","IdPessoa",IdPessoa);
							listar_conta_debito(IdPessoa, false, Local);
							break;
						case 'CartaoCredito':
							nameNode = xmlhttp.responseXML.getElementsByTagName("IdEnderecoDefault")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var IdEnderecoDefault = nameTextNode.nodeValue;	
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("DataNascimento")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var DataNascimento = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("RG_IE")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var RG_IE = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("Telefone1")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var Telefone1 = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("Telefone2")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var Telefone2 = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("Telefone3")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var Telefone3 = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("Celular")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var Celular = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("Fax")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var Fax = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("ComplementoTelefone")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var ComplementoTelefone = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("NomeRepresentante")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var NomeRepresentante = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("Email")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var EmailJuridica = nameTextNode.nodeValue;
							
							document.formulario.NomeRepresentante.value 		= NomeRepresentante;
							document.formulario.InscricaoEstadual.value 		= RG_IE;
							document.formulario.DataNascimento.value 			= dateFormat(DataNascimento);	
							document.formulario.RG.value 						= RG_IE;
							document.formulario.Telefone1.value 				= Telefone1;
							document.formulario.Telefone2.value 				= Telefone2;
							document.formulario.Telefone3.value 				= Telefone3;
							document.formulario.Celular.value 					= Celular;	
							document.formulario.Fax.value 						= Fax;
							document.formulario.ComplementoTelefone.value 		= ComplementoTelefone;
							document.formulario.EmailJuridica.value 			= EmailJuridica;
							
							addParmUrl("marPessoa","IdPessoa",IdPessoa);
							addParmUrl("marPessoaNovo", "IdPessoa", IdPessoa);
							addParmUrl("marContrato","IdPessoa",IdPessoa);
							addParmUrl("marContratoNovo","IdPessoa",IdPessoa);
							addParmUrl("marLancamentoFinanceiro","IdPessoa",IdPessoa);
							addParmUrl("marReenvioMensagem","IdPessoa",IdPessoa);
							addParmUrl("marContasReceber","IdPessoa",IdPessoa);
							addParmUrl("marContaEventual","IdPessoa",IdPessoa);
							addParmUrl("marContaEventualNovo","IdPessoa",IdPessoa);
							addParmUrl("marOrdemServico","IdPessoa",IdPessoa);
							addParmUrl("marOrdemServicoNovo","IdPessoa",IdPessoa);
							listar_cartao_credito(IdPessoa, false, Local);
							break;
						case 'AgruparContaReceber':
							nameNode = xmlhttp.responseXML.getElementsByTagName("IdEnderecoDefault")[0];
							nameTextNode = nameNode.childNodes[0];
							var IdEnderecoDefault = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("DataNascimento")[0];
							nameTextNode = nameNode.childNodes[0];
							var DataNascimento = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("RG_IE")[0];
							nameTextNode = nameNode.childNodes[0];
							var RG_IE = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("Telefone1")[0];
							nameTextNode = nameNode.childNodes[0];
							var Telefone1 = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("Telefone2")[0];
							nameTextNode = nameNode.childNodes[0];
							var Telefone2 = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("Telefone3")[0];
							nameTextNode = nameNode.childNodes[0];
							var Telefone3 = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("Celular")[0];
							nameTextNode = nameNode.childNodes[0];
							var Celular = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("Fax")[0];
							nameTextNode = nameNode.childNodes[0];
							var Fax = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("ComplementoTelefone")[0];
							nameTextNode = nameNode.childNodes[0];
							var ComplementoTelefone = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("NomeRepresentante")[0];
							nameTextNode = nameNode.childNodes[0];
							var NomeRepresentante = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("Email")[0];
							nameTextNode = nameNode.childNodes[0];
							var EmailJuridica = nameTextNode.nodeValue;
							
							document.formulario.NomeRepresentante.value			= NomeRepresentante;
							document.formulario.InscricaoEstadual.value			= RG_IE;
							document.formulario.DataNascimento.value			= dateFormat(DataNascimento);
							document.formulario.RG.value						= RG_IE;
							document.formulario.Telefone1.value					= Telefone1;
							document.formulario.Telefone2.value					= Telefone2;
							document.formulario.Telefone3.value					= Telefone3;
							document.formulario.Celular.value					= Celular;
							document.formulario.Fax.value						= Fax;
							document.formulario.ComplementoTelefone.value		= ComplementoTelefone;
							document.formulario.EmailJuridica.value				= EmailJuridica;
							
							if(Number(document.formulario.Buca.value) == 0){
								busca_opcoes_pessoa_endereco(IdPessoa,IdEnderecoDefault);
								limparNovoVencimento();
							}
							
							while(document.getElementById('tabelaContaReceber').rows.length > 2){
								document.getElementById('tabelaContaReceber').deleteRow(1);
							}
							
							document.getElementById('totaltabelaContaReceber').innerHTML = "Total: 0";
							document.getElementById('totalValorTabelaContaReceber').innerHTML = "0,00";
							
							document.formulario.IdContaReceberAgrupados.value	= '';
							document.formulario.IdContaReceber.value			= '';
							document.formulario.NomePessoa.value				= '';
							document.formulario.IdStatus.value					= '';
							document.formulario.bt_add_conta_receber.disabled	= true;
							break;
						case 'ContratoServiço':
							addParmUrl("marPessoa","IdPessoa",IdPessoa);
							addParmUrl("marContrato","IdPessoa",IdPessoa);
							addParmUrl("marContratoNovo","IdPessoa",IdPessoa);
							addParmUrl("marLancamentoFinanceiro","IdPessoa",IdPessoa);
							addParmUrl("marReenvioMensagem","IdPessoa",IdPessoa);
							addParmUrl("marContasReceber","IdPessoa",IdPessoa);
							addParmUrl("marContaEventual","IdPessoa",IdPessoa);
							addParmUrl("marContaEventualNovo","IdPessoa",IdPessoa);
							
							if(document.formulario.Acao.value == 'inserir'){
								if(document.formulario.IdServico.value != ""){
									busca_servico('');
								}
							}
							break;
						case 'ContaEventual':
							nameNode = xmlhttp.responseXML.getElementsByTagName("IdEnderecoDefault")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var IdEnderecoDefault = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("DataNascimento")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var DataNascimento = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("RG_IE")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var RG_IE = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("Telefone1")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var Telefone1 = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("Telefone2")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var Telefone2 = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("Telefone3")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var Telefone3 = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("Celular")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var Celular = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("Fax")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var Fax = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("ComplementoTelefone")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var ComplementoTelefone = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("NomeRepresentante")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var NomeRepresentante = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("Email")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var EmailJuridica = nameTextNode.nodeValue;
							
							document.formulario.NomeRepresentante.value 		= NomeRepresentante;
							document.formulario.InscricaoEstadual.value 		= RG_IE;
							document.formulario.DataNascimento.value 			= dateFormat(DataNascimento);	
							document.formulario.RG.value 						= RG_IE;
							document.formulario.Telefone1.value 				= Telefone1;
							document.formulario.Telefone2.value 				= Telefone2;
							document.formulario.Telefone3.value 				= Telefone3;
							document.formulario.Celular.value 					= Celular;	
							document.formulario.Fax.value 						= Fax;
							document.formulario.ComplementoTelefone.value 		= ComplementoTelefone;
							document.formulario.EmailJuridica.value 			= EmailJuridica;
							
							addParmUrl("marPessoa","IdPessoa",IdPessoa);
							addParmUrl("marContrato","IdPessoa",IdPessoa);
							addParmUrl("marContratoNovo","IdPessoa",IdPessoa);
							addParmUrl("marLancamentoFinanceiro","IdPessoa",IdPessoa);
							//addParmUrl("marReenvioMensagem","IdPessoa",IdPessoa);
							addParmUrl("marContasReceber","IdPessoa",IdPessoa);
							addParmUrl("marContaEventual","IdPessoa",IdPessoa);
							addParmUrl("marContaEventualNovo","IdPessoa",IdPessoa);
							
							if(document.formulario.Acao.value == 'inserir'){
								if(document.formulario.FormaCobranca.value==1){
									listar_contrato(IdPessoa);
								}else{
									listar_contrato_individual(IdPessoa);
								}
								
								busca_opcoes_pessoa_endereco(IdPessoa,IdEnderecoDefault);
								busca_pessoa_endereco_cobranca(IdPessoa,IdEnderecoDefault);
								
								document.formulario.DescricaoContaEventual.value 			= 	"";
								document.formulario.FormaCobranca[0].selected				= 	true;
								document.formulario.OcultarReferencia.value 				= 	"";
								document.formulario.ValorTotal.value 						= 	"0,00";
								document.formulario.QtdParcela.value 						= 	"";
								document.formulario.OcultarReferencia.value					= 	2;
								document.formulario.ObsContaEventual.value					= 	"";
								
								busca_forma_cobranca();
							} else{
								listar_parcela_conta(document.formulario.IdContaEventual.value);
							}
							break;
						case 'OrdemServico':
							nameNode = xmlhttp.responseXML.getElementsByTagName("IdEnderecoDefault")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var IdEnderecoDefault = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("DataNascimento")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var DataNascimento = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("RG_IE")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var RG_IE = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("Telefone1")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var Telefone1 = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("Telefone2")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var Telefone2 = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("Telefone3")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var Telefone3 = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("Celular")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var Celular = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("Fax")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var Fax = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("ComplementoTelefone")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var ComplementoTelefone = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("NomeRepresentante")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var NomeRepresentante = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("Email")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var EmailJuridica = nameTextNode.nodeValue;	
							
							document.formulario.NomeRepresentante.value 		= NomeRepresentante;
							document.formulario.InscricaoEstadual.value 		= RG_IE;
							document.formulario.DataNascimento.value 			= dateFormat(DataNascimento);	
							document.formulario.RG.value 						= RG_IE;
							document.formulario.Telefone1.value 				= Telefone1;
							document.formulario.Telefone2.value 				= Telefone2;
							document.formulario.Telefone3.value 				= Telefone3;
							document.formulario.Celular.value 					= Celular;	
							document.formulario.Fax.value 						= Fax;
							document.formulario.ComplementoTelefone.value 		= ComplementoTelefone;
							document.formulario.EmailJuridica.value 			= EmailJuridica;
							
							addParmUrl("marContrato","IdPessoa",IdPessoa);
							addParmUrl("marContratoNovo","IdPessoa",IdPessoa);
							//addParmUrl("marReenvioMensagem","IdPessoa",IdPessoa);
							addParmUrl("marContaEventual","IdPessoa",IdPessoa);
							addParmUrl("marContaEventualNovo","IdPessoa",IdPessoa);
							addParmUrl("marPessoa","IdPessoa",IdPessoa);
							addParmUrl("marOrdemServico","IdPessoa",IdPessoa);
							addParmUrl("marOrdemServicoNovo","IdPessoa",IdPessoa);
							addParmUrl("marVigenciaNovo","IdPessoa",IdPessoa);
							
							listarOrdemServicoCliente(IdPessoa);
							
							if(document.formulario.Acao.value == 'inserir'){
								if(document.formulario.IdServico.value != ""){
									busca_servico('');
								}
								if(document.formulario.IdContrato.value != "" && document.formulario.IdContratoTemp.value == ""){
									busca_contrato('');
								}
								
								document.formulario.IdContratoTemp.value = "";
								
								busca_opcoes_pessoa_endereco(IdPessoa,IdEnderecoDefault);
								busca_pessoa_endereco(IdPessoa,IdEnderecoDefault);
							}
							break;
						case 'Protocolo':
							nameNode = xmlhttp.responseXML.getElementsByTagName("IdEnderecoDefault")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var IdEnderecoDefault = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("DataNascimento")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var DataNascimento = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("RG_IE")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var RG_IE = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("Telefone1")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var Telefone1 = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("Telefone2")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var Telefone2 = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("Telefone3")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var Telefone3 = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("Celular")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var Celular = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("Fax")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var Fax = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("ComplementoTelefone")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var ComplementoTelefone = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("NomeRepresentante")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var NomeRepresentante = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("Email")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var Email = nameTextNode.nodeValue;	
							
							document.formulario.IdTipoPessoa.value 				= TipoPessoa;
							//document.formulario.IdTipoPessoaTemp.value 			= TipoPessoa;
							document.formulario.NomeRepresentante.value 		= NomeRepresentante;
							document.formulario.InscricaoEstadual.value 		= RG_IE;
							document.formulario.DataNascimento.value 			= dateFormat(DataNascimento);	
							document.formulario.RG.value 						= RG_IE;
							document.formulario.Fax.value 						= Fax;
							document.formulario.ComplementoTelefone.value 		= ComplementoTelefone;
							document.formulario.Telefone1.value 				= Telefone1;
							document.formulario.Telefone2.value 				= Telefone2;
							document.formulario.Telefone3.value 				= Telefone3;
							document.formulario.Celular.value 					= Celular;	
							document.formulario.Telefone1.readOnly 				= true;
							document.formulario.Telefone2.readOnly 				= true;
							document.formulario.Telefone3.readOnly 				= true;
							document.formulario.Celular.readOnly 				= true;	

							document.formulario.Telefone1.onFocus = document.formulario.Telefone2.onFocus = document.formulario.Telefone3.onFocus = document.formulario.Celular.onFocus = function () {
								return Foco(this,"in");
							};
							document.formulario.Telefone1.onBlur = document.formulario.Telefone2.onBlur = document.formulario.Telefone3.onBlur = document.formulario.Celular.onBlur = function () {
								return Foco(this,"out");
							};
							
							document.getElementById("cp_CPF").style.backgroundColor = document.getElementById("cp_CNPJ").style.backgroundColor = "#fff";
							document.getElementById("cp_CPF").innerHTML = "CPF";
							document.getElementById("cp_CNPJ").innerHTML = "CNPJ";
							
							if(TipoPessoa == 2){
								document.getElementById("cp_fisica").style.display		= "block";
								document.getElementById("cp_juridica").style.display	= "none";
								
								document.formulario.IdPessoaF.value 		= IdPessoa;
								if(document.formulario.IdPessoaF.value != ""){
									document.formulario.NomeF.readOnly			= true;
									document.formulario.CPF.readOnly			= true;
									document.formulario.Email.readOnly 			= true;
									document.formulario.Nome.readOnly			= true;
									document.formulario.CNPJ.readOnly			= true;
									document.formulario.EmailJuridica.readOnly	= true;
								}else{
									document.formulario.NomeF.readOnly			= false;
									document.formulario.CPF.readOnly			= false;
									document.formulario.Email.readOnly 			= false;
									document.formulario.Nome.readOnly			= false;
									document.formulario.CNPJ.readOnly			= false;
									document.formulario.EmailJuridica.readOnly	= false;
								}
								document.getElementById("cp_NomeFantasia").style.color = "#000";
								document.getElementById("cp_CNPJ").style.color = "#000";
								document.getElementById("cp_NomePessoa").style.color = "#000";
								
								if(Number(document.formulario.CPF_CNPJ_Obrigatorio.value) == 1) {
									document.getElementById("cp_CPF").style.color = "#000";
								} else {
									document.getElementById("cp_CPF").style.color = "#000";
								}
								
								document.formulario.NomeF.onFocus = document.formulario.CPF.onFocus = document.formulario.Email.onFocus = document.formulario.Telefone1.onFocus;
								document.formulario.NomeF.onBlur = document.formulario.CPF.onBlur = document.formulario.Email.onBlur = document.formulario.Telefone1.onBlur;
								document.formulario.Nome.onFocus = document.formulario.CNPJ.onFocus = document.formulario.EmailJuridica.onFocus = undefined;
								document.formulario.Nome.onBlur = document.formulario.CNPJ.onBlur = document.formulario.EmailJuridica.onBlur = undefined;
							} else{
								document.getElementById("cp_fisica").style.display		= "none";
								document.getElementById("cp_juridica").style.display	= "block";
								
								document.formulario.IdPessoa.value 			= IdPessoa;
								document.formulario.RazaoSocial.value 		= RazaoSocial;
								document.formulario.Telefone1.value 		= Telefone1;
								document.formulario.Telefone2.value 		= Telefone2;
								document.formulario.Telefone3.value 		= Telefone3;
								document.formulario.Celular.value 			= Celular;
								if(document.formulario.IdPessoaF.value != ""){
									document.formulario.NomeF.readOnly			= true;
									document.formulario.CPF.readOnly			= true;
									document.formulario.Email.readOnly 			= true;
									document.formulario.Nome.readOnly			= true;
									document.formulario.CNPJ.readOnly			= true;
									document.formulario.EmailJuridica.readOnly	= true;
								}else{
									document.formulario.NomeF.readOnly			= false;
									document.formulario.CPF.readOnly			= false;
									document.formulario.Email.readOnly 			= false;
									document.formulario.Nome.readOnly			= false;
									document.formulario.CNPJ.readOnly			= false;
									document.formulario.EmailJuridica.readOnly	= false;
								}
								
								document.getElementById("cp_NomeFantasia").style.color = "#000";
								document.getElementById("cp_NomePessoa").style.color = "#000";
								document.getElementById("cp_CPF").style.color = "#000";
								
								if(Number(document.formulario.CPF_CNPJ_Obrigatorio.value) == 1) {
									document.getElementById("cp_CNPJ").style.color = "000";
								} else {
									document.getElementById("cp_CNPJ").style.color = "#000";
								}
								
								document.formulario.NomeF.onFocus = document.formulario.CPF.onFocus = document.formulario.Email.onFocus = undefined;
								document.formulario.NomeF.onBlur = document.formulario.CPF.onBlur = document.formulario.Email.onBlur = undefined;
								document.formulario.Nome.onFocus = document.formulario.CNPJ.onFocus = document.formulario.EmailJuridica.onFocus = document.formulario.Telefone1.onFocus;
								document.formulario.Nome.onBlur = document.formulario.CNPJ.onBlur = document.formulario.EmailJuridica.onBlur = document.formulario.Telefone1.onBlur;
							}
							
								
							if(document.formulario.PessoaTodosDados.value == 1){
								if(TipoPessoa == 2){
									document.formulario.CPF.value 			= CPF_CNPJ;
									document.formulario.NomeF.value 		= Nome;
									document.formulario.Email.value 		= Email;
								} else{
									document.formulario.CNPJ.value 			= CPF_CNPJ;
									document.formulario.Nome.value 			= Nome;
									document.formulario.EmailJuridica.value = Email;
								}
								
								/*if(document.formulario.IdContrato.value != ""){
									busca_contrato(IdContrato,false,document.formulario.Local.value);
								}
								if(document.formulario.IdContaEventual.value != ""){
									busca_conta_eventual('',false,document.formulario.Local.value);
								}
								if(document.formulario.IdContaReceber.value != ""){
									busca_conta_receber('',false,document.formulario.Local.value);
								}
								if(document.formulario.IdOrdemServico.value != ""){
									busca_ordem_servico('',false,document.formulario.Local.value);
								}*/
							} else{
								document.formulario.PessoaTodosDados.value = 1;
							}
							break;
						case 'OrdemServicoFatura':
							nameNode = xmlhttp.responseXML.getElementsByTagName("IdEnderecoDefault")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var IdEnderecoDefault = nameTextNode.nodeValue;
							
							addParmUrl("marOrdemServico","IdPessoa",IdPessoa);
							addParmUrl("marOrdemServicoNovo","IdPessoa",IdPessoa);
							addParmUrl("marPessoa","IdPessoa",IdPessoa);
							
							if(document.formulario.Acao.value == 'inserir'){
								if(document.formulario.IdServico.value != ""){
									busca_servico('');
								}
								if(document.formulario.IdContrato.value != ""){
									busca_contrato('');
								}
							}
							
							if(document.formulario.IdPessoaEnderecoCobranca.value == ""){
								busca_opcoes_pessoa_endereco(IdPessoa,IdEnderecoDefault);
								busca_pessoa_endereco_cobranca(IdPessoa,IdEnderecoDefault);
							}
							break;
						case 'HelpDesk':
							nameNode = xmlhttp.responseXML.getElementsByTagName("IdEnderecoDefault")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var IdEnderecoDefault = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("DataNascimento")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var DataNascimento = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("RG_IE")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var RG_IE = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("Telefone1")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var Telefone1 = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("Telefone2")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var Telefone2 = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("Telefone3")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var Telefone3 = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("Celular")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var Celular = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("Fax")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var Fax = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("ComplementoTelefone")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var ComplementoTelefone = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("NomeRepresentante")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var NomeRepresentante = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("Email")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var EmailJuridica = nameTextNode.nodeValue;	
							
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
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("IdCidade")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var IdCidade = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("NomeCidade")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var NomeCidade = nameTextNode.nodeValue;
							
							document.formulario.NomeRepresentante.value 		= NomeRepresentante;
							document.formulario.InscricaoEstadual.value 		= RG_IE;
							document.formulario.DataNascimento.value 			= dateFormat(DataNascimento);	
							document.formulario.RG.value 						= RG_IE;
							document.formulario.Telefone1.value 				= Telefone1;
							document.formulario.Telefone2.value 				= Telefone2;
							document.formulario.Telefone3.value 				= Telefone3;
							document.formulario.Celular.value 					= Celular;	
							document.formulario.Fax.value 						= Fax;
							document.formulario.ComplementoTelefone.value 		= ComplementoTelefone;
							document.formulario.EmailJuridica.value 			= EmailJuridica;
							document.formulario.IdPais.value					= IdPais;
							document.formulario.Pais.value						= NomePais;
							document.formulario.IdEstado.value					= IdEstado;
							document.formulario.Estado.value					= NomeEstado;
							document.formulario.IdCidade.value					= IdCidade;
							document.formulario.Cidade.value					= NomeCidade;
							
							break;
					}
					
				}
				
				if(document.getElementById("quadroBuscaPessoa") != null){
					if(document.getElementById("quadroBuscaPessoa").style.display == "block"){
						document.getElementById("quadroBuscaPessoa").style.display =	"none";
					}
				}
			}
			if(Local!='OrdemServicoFatura'){
				// Fim de Carregando
				carregando(false);
			}
		} 
		return true;
	}
	xmlhttp.send(null);
}