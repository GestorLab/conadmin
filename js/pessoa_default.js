function janela_busca_pessoa(IdStatus){
	if(document.formulario.Local.value == 'Contrato'){
		if(document.formulario.IdContrato.value != ""){
			return false
		}else{
			janelas('../administrativo/busca_pessoa.php',530,350,250,100,'');
		}
	}
	else{
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
	if(IdPessoa == '' && CPF_CNPJ == ''){
		IdPessoa = 0;
	}
	if(Local == ''){
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
    
    url = "../administrativo/xml/pessoa.php?IdPessoa="+IdPessoa+"&CPF_CNPJ="+CPF_CNPJ;

	switch(Local){
		case 'Usuario':
			url	+=	'&TipoUsuario=1';
			break;
		case 'AgenteAutorizado':
			url	+=	'&TipoAgenteAutorizado=1';	
			break;
		case 'Fornecedor':
			url	+=	'&TipoFornecedor=1&TipoPessoa=1';
			break;
		case 'NotaFiscal':
			url	+=	'&IdFornecedor='+IdPessoa;
			break;
	}

	xmlhttp.open("GET", url,true);
	var CPF	=	CPF_CNPJ;	

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
						case 'Pessoa':
							if(CPF == '' || (IdPessoa != '' && CPF != '')){
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
								document.formulario.CEP.value 					= '';
								document.formulario.Endereco.value 				= '';
								document.formulario.Complemento.value 			= '';
								document.formulario.Numero.value				= '';
								document.formulario.Bairro.value 				= '';
								document.formulario.Telefone1.value				= '';
								document.formulario.Telefone2.value				= '';
								document.formulario.Telefone3.value				= '';
								document.formulario.Celular.value 				= '';
								document.formulario.Fax.value 					= '';
								document.formulario.ComplementoTelefone.value	= '';
								document.formulario.Email.value 				= '';
								document.formulario.Site.value 					= 'http://';
								document.formulario.Obs.value 					= '';
								document.formulario.HistoricoObs.value 			= '';
								document.formulario.Cob_FormaEmail.checked		= false;
								document.formulario.Cob_FormaOutro.checked		= false;
								document.formulario.Cob_FormaCorreio.checked 	= false;
								document.formulario.AgruparContratos[0].selected = true;
								document.formulario.Enviar_Email.value 			= '';
								document.formulario.IdGrupoPessoa.value			= '';
								document.formulario.DescricaoGrupoPessoa.value	= '';
								document.formulario.TipoUsuario.checked			= false;
								document.formulario.TipoAgenteAutorizado.checked= false;
								document.formulario.TipoFornecedor.checked 		= false;
								document.formulario.TipoVendedor.checked 		= false;
								document.formulario.Senha.value					= '';
								
								document.formulario.Cob_NomeResponsavel.value 		= '';
								document.formulario.Cob_CobrarDespesaBoleto.value 	= '';
								document.formulario.Cob_CEP.value 					= '';
								document.formulario.Cob_Endereco.value 				= '';
								document.formulario.Cob_Complemento.value 			= '';
								document.formulario.Cob_Numero.value				= '';
								document.formulario.Cob_Bairro.value 				= '';
								document.formulario.Cob_Telefone1.value				= '';
								document.formulario.Cob_ComplementoTelefone.value	= '';
								document.formulario.Cob_IdPais.value 				= '';
								document.formulario.Cob_Pais.value 					= '';
								document.formulario.Cob_IdEstado.value				= '';
								document.formulario.Cob_Estado.value				= '';
								document.formulario.Cob_IdCidade.value				= '';
								document.formulario.Cob_Cidade.value				= '';
								document.formulario.Cob_Email.value					= '';
								
								if(document.formulario.CampoExtra1 != undefined)
									document.formulario.CampoExtra1.value				= '';
								if(document.formulario.CampoExtra2 != undefined)
									document.formulario.CampoExtra2.value				= '';
								if(document.formulario.CampoExtra3 != undefined)
									document.formulario.CampoExtra3.value				= '';
								if(document.formulario.CampoExtra4 != undefined)
									document.formulario.CampoExtra4.value				= '';
								
								statusInicial();
								localidadeDefault();
							}
							document.formulario.DataCriacao.value 			= '';
							document.formulario.LoginCriacao.value 			= '';
							document.formulario.DataAlteracao.value 		= '';
							document.formulario.LoginAlteracao.value		= '';
							document.formulario.Acao.value 					= 'inserir';
							
							addParmUrl("marContrato","IdPessoa","");
							addParmUrl("marContratoNovo","IdPessoa","");
							addParmUrl("marContasReceber","IdPessoa","");
							addParmUrl("marLancamentoFinanceiro","IdPessoa","");
							addParmUrl("marProcessoFinanceiro","IdPessoa","");
							addParmUrl("marProcessoFinanceiroNovo","IdPessoa","");
							addParmUrl("marReenvioEmail","IdPessoa","");
							addParmUrl("marPessoa","IdPessoa","");
							addParmUrl("marContaEventualNovo","IdPessoa","");
							addParmUrl("marContaEventual","IdPessoa","");
							addParmUrl("marProcessoFinanceiroNovo","IdPessoa","");
							addParmUrl("marProcessoFinanceiro","IdPessoa","");
							addParmUrl("marOrdemServico","IdPessoa","");
							addParmUrl("marOrdemServicoNovo","IdPessoa","");
							
							document.getElementById('cp_DataNascimento_Titulo').style.backgroundColor = '#FFFFFF';
							document.getElementById('cp_DataNascimento_Titulo').style.color = '#000000';
							
							document.getElementById('cp_DataFundacao_Titulo').style.backgroundColor = '#FFFFFF';
							document.getElementById('cp_DataFundacao_Titulo').style.color = '#000000';
						
							document.getElementById('cp_CPF_CNPJ_Titulo').style.backgroundColor = '#FFFFFF';
							document.getElementById('cp_CPF_CNPJ_Titulo').style.color = '#C10000';
							
							document.getElementById('cpHistorico').style.display = 'none';
							
							
							verificaAcao();
							
							if(CPF == '' && IdPessoa != ''){
								document.formulario.IdPessoa.focus();
							}
							
							validar_CPF_CNPJ(document.formulario.CPF_CNPJ.value);
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
							document.formulario.CEP.value 					= '';
							document.formulario.Endereco.value 				= '';
							document.formulario.Complemento.value 			= '';
							document.formulario.Numero.value				= '';
							document.formulario.Bairro.value 				= '';
							document.formulario.Telefone1.value				= '';
							document.formulario.Telefone2.value				= '';
							document.formulario.Telefone3.value				= '';
							document.formulario.Celular.value 				= '';
							document.formulario.Fax.value 					= '';
							document.formulario.ComplementoTelefone.value	= '';
							document.formulario.Email.value 				= '';
							document.formulario.Site.value 					= 'http://';
							document.formulario.Enviar_Email.value 			= '';
							document.formulario.Acao.value 					= 'inserir';
							
							localidadeDefault();
							
							addParmUrl("marContrato","IdPessoa","");
							addParmUrl("marContratoNovo","IdPessoa","");
							addParmUrl("marContasReceber","IdPessoa","");
							addParmUrl("marLancamentoFinanceiro","IdPessoa","");
							addParmUrl("marProcessoFinanceiro","IdPessoa","");
							addParmUrl("marProcessoFinanceiroNovo","IdPessoa","");
							addParmUrl("marReenvioEmail","IdPessoa","");
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
						case 'NotaFiscal':
							document.formulario.IdPessoa.value 			= '';
							document.formulario.Nome.value 				= '';
							document.formulario.RazaoSocial.value 		= '';
							document.formulario.Cidade.value 			= '';
							document.formulario.CPF_CNPJ.value 			= '';
							document.formulario.Email.value 			= '';
							document.formulario.Telefone1.value			= '';
							document.formulario.SiglaEstado.value		= '';
							document.formulario.Endereco.value			= '';
							
							document.formulario.CPF_CNPJ.focus();
							break;
						default:
							document.getElementById('cp_juridica').style.display			= 'block';
							document.getElementById('cp_fisica').style.display				= 'none';
						
							document.formulario.IdPessoa.value 			= '';
							document.formulario.Nome.value 				= '';
							document.formulario.RazaoSocial.value 		= '';
							document.formulario.Cidade.value 			= '';
							document.formulario.CPF_CNPJ.value 			= '';
							document.formulario.Email.value 			= '';
							document.formulario.Telefone1.value			= '';
							document.formulario.SiglaEstado.value		= '';
							
							document.getElementById('cp_CPF_CNPJ_Titulo').innerHTML 		= "CNPJ";
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
						busca_dia_cobranca('',document.formulario.DiaCobrancaDefault.value)	
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
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("Cob_Telefone1")[0]; 
					nameTextNode = nameNode.childNodes[0];
					var Cob_Telefone1 = nameTextNode.nodeValue;
					
					switch (Condicao){
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
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("RG_IE")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var RG_IE = nameTextNode.nodeValue;	
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("EstadoCivil")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var EstadoCivil = nameTextNode.nodeValue;	
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("InscricaoMunicipal")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var InscricaoMunicipal = nameTextNode.nodeValue;	
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("CEP")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var CEP = nameTextNode.nodeValue;	
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("Endereco")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var Endereco = nameTextNode.nodeValue;	
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("Complemento")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var Complemento = nameTextNode.nodeValue;	
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("Numero")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var Numero = nameTextNode.nodeValue;	
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("Bairro")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var Bairro = nameTextNode.nodeValue;	
							
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
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("Cob_CEP")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var Cob_CEP = nameTextNode.nodeValue;	
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("Cob_Endereco")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var Cob_Endereco = nameTextNode.nodeValue;	
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("Cob_Complemento")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var Cob_Complemento = nameTextNode.nodeValue;	
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("Cob_Numero")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var Cob_Numero = nameTextNode.nodeValue;	
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("Cob_Bairro")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var Cob_Bairro = nameTextNode.nodeValue;	
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("Cob_ComplementoTelefone")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var Cob_ComplementoTelefone = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("Cob_NomeResponsavel")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var Cob_NomeResponsavel = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("Cob_CobrarDespesaBoleto")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var Cob_CobrarDespesaBoleto = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("Cob_IdPais")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var Cob_IdPais = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("Cob_NomePais")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var Cob_NomePais = nameTextNode.nodeValue;	
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("Cob_IdEstado")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var Cob_IdEstado = nameTextNode.nodeValue;	
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("Cob_NomeEstado")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var Cob_NomeEstado = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("Cob_IdCidade")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var Cob_IdCidade = nameTextNode.nodeValue;	
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("Cob_NomeCidade")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var Cob_NomeCidade = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("Cob_Email")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var Cob_Email = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("IdGrupoPessoa")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var IdGrupoPessoa = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoGrupoPessoa")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var DescricaoGrupoPessoa = nameTextNode.nodeValue;
													
							nameNode = xmlhttp.responseXML.getElementsByTagName("Enviar_Email")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var Enviar_Email = nameTextNode.nodeValue;	
							
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
							
							ativaPessoa(TipoPessoa);
							
							addParmUrl("marContrato","IdPessoa",IdPessoa);
							addParmUrl("marContratoNovo","IdPessoa",IdPessoa);
							addParmUrl("marContasReceber","IdPessoa",IdPessoa);
							addParmUrl("marLancamentoFinanceiro","IdPessoa",IdPessoa);
							addParmUrl("marProcessoFinanceiro","IdPessoa",IdPessoa);
							addParmUrl("marContaEventualNovo","IdPessoa",IdPessoa);
							addParmUrl("marContaEventual","IdPessoa",IdPessoa);
							addParmUrl("marProcessoFinanceiroNovo","IdPessoa",IdPessoa);
							addParmUrl("marReenvioEmail","IdPessoa",IdPessoa);
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
							document.formulario.InscricaoMunicipal.value	= InscricaoMunicipal;
							document.formulario.CEP.value 					= CEP;
							document.formulario.Endereco.value 				= Endereco;
							document.formulario.Complemento.value 			= Complemento;
							document.formulario.Numero.value 				= Numero;
							document.formulario.Bairro.value 				= Bairro;
							document.formulario.IdPais.value				= IdPais;
							document.formulario.Pais.value					= NomePais;
							document.formulario.IdEstado.value 				= IdEstado;
							document.formulario.Estado.value				= NomeEstado;
							document.formulario.IdCidade.value 				= IdCidade;
							document.formulario.Cidade.value				= NomeCidade;
							document.formulario.Telefone1.value 			= Telefone1;
							document.formulario.Telefone2.value 			= Telefone2;
							document.formulario.Telefone3.value 			= Telefone3;
							document.formulario.Celular.value 				= Celular;
							document.formulario.Fax.value 					= Fax;
							document.formulario.ComplementoTelefone.value	= ComplementoTelefone;
							document.formulario.Email.value 				= Email;
							
							if(Cob_FormaCorreio == 'S'){	document.formulario.Cob_FormaCorreio.checked	=	true;		}else{		document.formulario.Cob_FormaCorreio.checked	=	false;		}					
							if(Cob_FormaEmail == 'S'){		document.formulario.Cob_FormaEmail.checked		=	true;		}else{		document.formulario.Cob_FormaEmail.checked		=	false;		}
							if(Cob_FormaOutro == 'S'){		document.formulario.Cob_FormaOutro.checked		=	true;		}else{		document.formulario.Cob_FormaOutro.checked		=	false;		}
						
						
							if(TipoUsuario == '1'){				document.formulario.TipoUsuario.checked				=	true;	}else{		document.formulario.TipoUsuario.checked				=	false;		}					
							if(TipoAgenteAutorizado == '1'){	document.formulario.TipoAgenteAutorizado.checked	=	true;	}else{		document.formulario.TipoAgenteAutorizado.checked	=	false;		}
							if(TipoFornecedor == '1'){			document.formulario.TipoFornecedor.checked			=	true;	}else{		document.formulario.TipoFornecedor.checked			=	false;		}
							if(TipoVendedor == '1'){			document.formulario.TipoVendedor.checked			=	true;	}else{		document.formulario.TipoVendedor.checked			=	false;		}
						
							if(Site == ""){
								Site	=	"http://";
							}
							
							if(Obs!=""){
								document.getElementById('cpHistorico').style.display = 'block';
								document.formulario.HistoricoObs.value 					= Obs;
							}else{
								document.getElementById('cpHistorico').style.display = 'none';
								document.formulario.HistoricoObs.value 					= "";
							}
							
							document.formulario.Site.value 					= Site;
							document.formulario.Obs.value 					= "";
							document.formulario.AgruparContratos.value		= AgruparContratos;
							
							document.formulario.Cob_NomeResponsavel.value 		= Cob_NomeResponsavel;
							document.formulario.Cob_CobrarDespesaBoleto.value 	= Cob_CobrarDespesaBoleto;
							document.formulario.Cob_CEP.value 					= Cob_CEP;
							document.formulario.Cob_Endereco.value 				= Cob_Endereco;
							document.formulario.Cob_Complemento.value 			= Cob_Complemento;
							document.formulario.Cob_Numero.value				= Cob_Numero;
							document.formulario.Cob_Bairro.value 				= Cob_Bairro;
							document.formulario.Cob_Telefone1.value				= Cob_Telefone1;
							document.formulario.Cob_ComplementoTelefone.value	= Cob_ComplementoTelefone;
							document.formulario.Cob_IdPais.value 				= Cob_IdPais;
							document.formulario.Cob_Pais.value 					= Cob_NomePais;
							document.formulario.Cob_IdEstado.value				= Cob_IdEstado;
							document.formulario.Cob_Estado.value				= Cob_NomeEstado;
							document.formulario.Cob_IdCidade.value				= Cob_IdCidade;
							document.formulario.Cob_Cidade.value				= Cob_NomeCidade;
							document.formulario.Cob_Email.value					= Cob_Email;
							document.formulario.Enviar_Email.value 				= Enviar_Email;
							document.formulario.IdGrupoPessoa.value				= IdGrupoPessoa;
							document.formulario.DescricaoGrupoPessoa.value		= DescricaoGrupoPessoa;
							document.formulario.Senha.value						= Senha;
							
							if(document.formulario.CampoExtra1 != undefined)
								document.formulario.CampoExtra1.value				= CampoExtra1;
							if(document.formulario.CampoExtra2 != undefined)	
								document.formulario.CampoExtra2.value 				= CampoExtra2;
							if(document.formulario.CampoExtra3 != undefined)
								document.formulario.CampoExtra3.value				= CampoExtra3;
							if(document.formulario.CampoExtra4 != undefined)
							document.formulario.CampoExtra4.value				= CampoExtra4;
								
							document.formulario.DataCriacao.value 			= dateFormat(DataCriacao);
							document.formulario.LoginCriacao.value 			= LoginCriacao;
							document.formulario.DataAlteracao.value 		= dateFormat(DataAlteracao);
							document.formulario.LoginAlteracao.value		= LoginAlteracao;
							
							document.getElementById('cp_DataNascimento_Titulo').style.backgroundColor = '#FFFFFF';
							document.getElementById('cp_DataNascimento_Titulo').style.color = '#000000';
							
							document.getElementById('cp_DataFundacao_Titulo').style.backgroundColor = '#FFFFFF';
							document.getElementById('cp_DataFundacao_Titulo').style.color = '#000000';
							
							document.formulario.Acao.value 					= 'alterar';
							
							verificaAcao();
							
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
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("RG_IE")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var RG_IE = nameTextNode.nodeValue;	
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("EstadoCivil")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var EstadoCivil = nameTextNode.nodeValue;	
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("InscricaoMunicipal")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var InscricaoMunicipal = nameTextNode.nodeValue;	
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("CEP")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var CEP = nameTextNode.nodeValue;	
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("Endereco")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var Endereco = nameTextNode.nodeValue;	
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("Complemento")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var Complemento = nameTextNode.nodeValue;	
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("Numero")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var Numero = nameTextNode.nodeValue;	
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("Bairro")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var Bairro = nameTextNode.nodeValue;	
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("ComplementoTelefone")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var ComplementoTelefone = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("Email")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var Email = nameTextNode.nodeValue;	
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("Site")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var Site = nameTextNode.nodeValue;	
													
							nameNode = xmlhttp.responseXML.getElementsByTagName("Enviar_Email")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var Enviar_Email = nameTextNode.nodeValue;	
							
							ativaPessoa(TipoPessoa);
							
							addParmUrl("marContrato","IdPessoa",IdPessoa);
							addParmUrl("marContratoNovo","IdPessoa",IdPessoa);
							addParmUrl("marContasReceber","IdPessoa",IdPessoa);
							addParmUrl("marLancamentoFinanceiro","IdPessoa",IdPessoa);
							addParmUrl("marProcessoFinanceiro","IdPessoa",IdPessoa);
							addParmUrl("marContaEventualNovo","IdPessoa",IdPessoa);
							addParmUrl("marContaEventual","IdPessoa",IdPessoa);
							addParmUrl("marProcessoFinanceiroNovo","IdPessoa",IdPessoa);
							addParmUrl("marReenvioEmail","IdPessoa",IdPessoa);
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
							document.formulario.InscricaoMunicipal.value	= InscricaoMunicipal;
							document.formulario.CEP.value 					= CEP;
							document.formulario.Endereco.value 				= Endereco;
							document.formulario.Complemento.value 			= Complemento;
							document.formulario.Numero.value 				= Numero;
							document.formulario.Bairro.value 				= Bairro;
							document.formulario.IdPais.value				= IdPais;
							document.formulario.Pais.value					= NomePais;
							document.formulario.IdEstado.value 				= IdEstado;
							document.formulario.Estado.value				= NomeEstado;
							document.formulario.IdCidade.value 				= IdCidade;
							document.formulario.Cidade.value				= NomeCidade;
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
							document.formulario.Enviar_Email.value 			= Enviar_Email;
							
							if(document.formulario.CPF_CNPJ_Obrigatorio.value == 1){
								document.getElementById('cp_CPF_CNPJ_Titulo').style.color = '#C10000';
							}else{
								document.getElementById('cp_CPF_CNPJ_Titulo').style.color = '#000';
							}
							
							document.formulario.Acao.value 					= 'alterar';
							
							verificaAcao();
							
							break;
						case 'ProcessoFinanceiro':
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
												if(Fax == ''){
													if(Cob_Telefone1 == ''){
														Telefone1	=	'';	
													}else{
														Telefone1	=	Cob_Telefone1;		
													}	
												}else{
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
							break;
						case 'LoteRepasse':
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
												if(Fax == ''){
													if(Cob_Telefone1 == ''){
														Telefone1	=	'';	
													}else{
														Telefone1	=	Cob_Telefone1;		
													}	
												}else{
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
							break;
						case 'Etiqueta':
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
							
								nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoGrupoPessoa")[0]; 
								nameTextNode = nameNode.childNodes[0];
								var DescricaoGrupoPessoa = nameTextNode.nodeValue;
														
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
								
								var linkIni = "<a href='cadastro_pessoa.php?IdPessoa="+IdPessoa+"'>";
								var linkFim = "</a>";
								
								c0.innerHTML = linkIni + IdPessoa + linkFim;
								c0.style.padding =	"0 0 0 5px";
								
								c1.innerHTML = linkIni + Nome + linkFim;
								
								c2.innerHTML = linkIni + DescricaoGrupoPessoa + linkFim;
								
								c3.innerHTML = linkIni + CPF_CNPJ + linkFim;
								
								c4.innerHTML = "<img src='../../img/estrutura_sistema/ico_del.gif' alt='Excluir?' onClick=\"remover_filtro_pessoa("+IdPessoa+")\"></tr>";
								c4.style.textAlign = "center";
								c4.style.cursor = "pointer";
								
								document.getElementById('totaltabelaPessoa').innerHTML	=	'Total: '+(ii+1);
							}
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
							
							document.formulario.IdPessoa.focus();
							break;
						case "NotaFiscal":
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("NomeCidade")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var NomeCidade = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("Cob_Email")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var Cob_Email = nameTextNode.nodeValue;
						
							nameNode = xmlhttp.responseXML.getElementsByTagName("Email")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var Email = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("SiglaEstado")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var SiglaEstado = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("Endereco")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var Endereco = nameTextNode.nodeValue;	
								
							if(Email == '' && Cob_Email != ''){
								Email	=	Cob_Email;
							}
							
							if(Telefone1 == ''){
								if(Telefone2 == ''){
									if(Telefone3 == ''){
										if(Celular == ''){
											if(Fax == ''){
												if(Cob_Telefone1 == ''){
													Telefone1	=	'';	
												}else{
													Telefone1	=	Cob_Telefone1;		
												}	
											}else{
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
							
							document.getElementById('cp_CPF_CNPJ_Titulo').style.color			= '#C10000';
							document.getElementById('cp_CPF_CNPJ_Titulo').style.backgroundColor = '#FFF';
							
							if(TipoPessoa == 2){
								document.getElementById('cp_CPF_CNPJ_Titulo').innerHTML = "CPF";
							}else{
								document.getElementById('cp_CPF_CNPJ_Titulo').innerHTML = "CNPJ";
							}
							
							document.formulario.IdPessoa.value 			= IdPessoa;
							document.formulario.RazaoSocial.value 		= RazaoSocial;
							document.formulario.Nome.value 				= Nome;
							document.formulario.Cidade.value 			= NomeCidade;
							document.formulario.CPF_CNPJ.value 			= CPF_CNPJ;
							document.formulario.Email.value 			= Email;
							document.formulario.SiglaEstado.value		= SiglaEstado;
							document.formulario.Endereco.value			= Endereco;
							document.formulario.Telefone1.value			= Telefone1;
							
							break;
						default:
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("NomeCidade")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var NomeCidade = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("Cob_Email")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var Cob_Email = nameTextNode.nodeValue;
						
							nameNode = xmlhttp.responseXML.getElementsByTagName("Email")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var Email = nameTextNode.nodeValue;
							
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
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("Cob_Telefone1")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var Cob_Telefone1 = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("SiglaEstado")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var SiglaEstado = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("Sexo")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var Sexo = nameTextNode.nodeValue;	
								
							nameNode = xmlhttp.responseXML.getElementsByTagName("EstadoCivil")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var EstadoCivil = nameTextNode.nodeValue;	
							
							if(Email == '' && Cob_Email != ''){
								Email	=	Cob_Email;
							}
							
							if(Telefone1 == ''){
								if(Telefone2 == ''){
									if(Telefone3 == ''){
										if(Celular == ''){
											if(Fax == ''){
												if(Cob_Telefone1 == ''){
													Telefone1	=	'';	
												}else{
													Telefone1	=	Cob_Telefone1;		
												}	
											}else{
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
	
							document.formulario.IdPessoa.value 			= IdPessoa;
							document.formulario.Cidade.value 			= NomeCidade;
							document.formulario.CPF_CNPJ.value 			= CPF_CNPJ;
							document.formulario.Email.value 			= Email;
							document.formulario.SiglaEstado.value		= SiglaEstado;
							
							if(TipoPessoa == 2){
								
								document.formulario.NomeF.value 				= Nome;
								document.formulario.Telefone1F.value			= Telefone1;
								document.formulario.IdPessoaF.value 			= IdPessoa;
							
								
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
								
								document.getElementById('cp_CPF_CNPJ_Titulo').innerHTML 	= "CPF";
								document.getElementById('cp_fisica').style.display			= 'block';
								document.getElementById('cp_juridica').style.display		= 'none';
							}else{
								
								document.formulario.RazaoSocial.value 		= RazaoSocial;
								document.formulario.Nome.value 				= Nome;
								document.formulario.Telefone1.value			= Telefone1;
								document.formulario.IdPessoa.value 			= IdPessoa;
							
								document.getElementById('cp_CPF_CNPJ_Titulo').innerHTML 	= "CNPJ";
								document.getElementById('cp_juridica').style.display		= 'block';
								document.getElementById('cp_fisica').style.display			= 'none';
							}
							
							document.getElementById('cp_CPF_CNPJ_Titulo').style.backgroundColor = '#FFF';
							
							if(TipoPessoa == 2){
								document.getElementById('cp_CPF_CNPJ_Titulo').innerHTML = "CPF";
							}else{
								document.getElementById('cp_CPF_CNPJ_Titulo').innerHTML = "CNPJ";
							}
							
							if(Local == 'Contrato' && document.formulario.IdContrato.value == ''){
								listar_contrato_agrupador(IdPessoa);
							}
					}
					
					switch(Local){
						case 'Contrato':
							addParmUrl("marPessoa","IdPessoa",IdPessoa);
							addParmUrl("marContrato","IdPessoa",IdPessoa);
							addParmUrl("marContratoNovo","IdPessoa",IdPessoa);
							addParmUrl("marLancamentoFinanceiro","IdPessoa",IdPessoa);
							addParmUrl("marReenvioEmail","IdPessoa",IdPessoa);
							addParmUrl("marContasReceber","IdPessoa",IdPessoa);
							addParmUrl("marContaEventual","IdPessoa",IdPessoa);
							addParmUrl("marContaEventualNovo","IdPessoa",IdPessoa);
							
							if(document.formulario.Acao.value == 'inserir'){
								if(document.formulario.IdServico.value != ""){
									busca_servico('');
								}
								busca_dia_cobranca(IdPessoa,document.formulario.DiaCobrancaDefault.value);
							}
							break;
						case 'ContratoServiço':
							addParmUrl("marPessoa","IdPessoa",IdPessoa);
							addParmUrl("marContrato","IdPessoa",IdPessoa);
							addParmUrl("marContratoNovo","IdPessoa",IdPessoa);
							addParmUrl("marLancamentoFinanceiro","IdPessoa",IdPessoa);
							addParmUrl("marReenvioEmail","IdPessoa",IdPessoa);
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
							addParmUrl("marPessoa","IdPessoa",IdPessoa);
							addParmUrl("marContrato","IdPessoa",IdPessoa);
							addParmUrl("marContratoNovo","IdPessoa",IdPessoa);
							addParmUrl("marLancamentoFinanceiro","IdPessoa",IdPessoa);
							addParmUrl("marReenvioEmail","IdPessoa",IdPessoa);
							addParmUrl("marContasReceber","IdPessoa",IdPessoa);
							addParmUrl("marContaEventual","IdPessoa",IdPessoa);
							addParmUrl("marContaEventualNovo","IdPessoa",IdPessoa);
							
							if(document.formulario.FormaCobranca.value==1 && document.formulario.Acao.value == 'inserir'){
								listar_contrato_agrupador(IdPessoa);
							}
							break;
						case 'OrdemServico':
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
							break;
					}
					
				}
				if(document.getElementById("quadroBuscaPessoa") != null){
					if(document.getElementById("quadroBuscaPessoa").style.display == "block"){
						document.getElementById("quadroBuscaPessoa").style.display =	"none";
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
