function busca_solicitacao(IdPessoaSolicitacao,Erro,Local){
	if(IdPessoaSolicitacao == ""){
		IdPessoaSolicitacao	=	0;
	}
	if(Local == '' || Local == undefined){
		Local = document.formulario.Local.value;
	}
	var nameNode, nameTextNode, url, Condicao;

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
    
    url = "../administrativo/xml/pessoa_solicitacao.php?IdPessoaSolicitacao="+IdPessoaSolicitacao;

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
					document.formulario.IdPessoaSolicitacao.value 			= '';
					document.formulario.IdPessoa.value 						= '';
					document.formulario.Nome.value 							= '';
					document.formulario.NomeFantasia.value 					= '';
					document.formulario.NomeRepresentante.value 			= '';
					document.formulario.RazaoSocial.value 					= '';
					document.formulario.DataNascimento.value 				= '';
					document.formulario.DataFundacao.value 					= '';
					document.formulario.Sexo[0].selected 					= true;
					document.formulario.CPF_CNPJ.value 						= '';
					document.formulario.RG_IE.value 						= '';
					document.formulario.InscricaoEstadual.value 			= '';
					document.formulario.EstadoCivil[0].selected 			= true;
					document.formulario.InscricaoMunicipal.value			= '';
					document.formulario.Telefone1.value						= '';
					document.formulario.Telefone2.value						= '';
					document.formulario.Telefone3.value						= '';
					document.formulario.Celular.value 						= '';
					document.formulario.Fax.value 							= '';
					document.formulario.ComplementoTelefone.value			= '';
					document.formulario.Email.value 						= '';
					document.formulario.Site.value 							= 'http://';
					document.formulario.DataCriacao.value 					= '';
					document.formulario.LoginCriacao.value 					= '';
					document.formulario.DataAprovacao.value 				= '';
					document.formulario.LoginAprovacao.value				= '';
					document.formulario.IdStatus.value						= '';
					document.formulario.IdStatus.value						= '';
					document.formulario.IdEnderecoDefault.value				= '';
					document.formulario.QtdEndereco.value					= 0;
					document.formulario.QtdEnderecoAux.value				= 0;
					document.formulario.IP.value							= "";					
					
					document.formulario.Nome.style.border						= '1px #A4A4A4 solid';
					document.formulario.NomeFantasia.style.border 				= '1px #A4A4A4 solid';
					document.formulario.NomeRepresentante.style.border			= '1px #A4A4A4 solid';
					document.formulario.RazaoSocial.style.border 				= '1px #A4A4A4 solid';
					document.formulario.DataNascimento.style.border 			= '1px #A4A4A4 solid';
					document.formulario.DataFundacao.style.border 				= '1px #A4A4A4 solid';
					document.formulario.Sexo.style.border 						= '1px #A4A4A4 solid';
					document.formulario.RG_IE.style.border 						= '1px #A4A4A4 solid';
					document.formulario.InscricaoEstadual.style.border 			= '1px #A4A4A4 solid';
					document.formulario.EstadoCivil.style.border 				= '1px #A4A4A4 solid';
					document.formulario.InscricaoMunicipal.style.border			= '1px #A4A4A4 solid';
					document.formulario.Telefone1.style.border					= '1px #A4A4A4 solid';
					document.formulario.Telefone2.style.border					= '1px #A4A4A4 solid';
					document.formulario.Telefone3.style.border					= '1px #A4A4A4 solid';
					document.formulario.Celular.style.border 					= '1px #A4A4A4 solid';
					document.formulario.Fax.style.border 						= '1px #A4A4A4 solid';
					document.formulario.ComplementoTelefone.style.border		= '1px #A4A4A4 solid';
					document.formulario.Email.style.border 						= '1px #A4A4A4 solid';
					document.formulario.Site.style.border 						= '1px #A4A4A4 solid';
					document.formulario.IdEnderecoDefault.style.border			= '1px #A4A4A4 solid';
					
					document.formulario.Nome.style.backgroundColor						= '#FFF';
					document.formulario.NomeFantasia.style.backgroundColor 				= '#FFF';
					document.formulario.NomeRepresentante.style.backgroundColor			= '#FFF';
					document.formulario.RazaoSocial.style.backgroundColor 				= '#FFF';
					document.formulario.DataNascimento.style.backgroundColor 			= '#FFF';
					document.formulario.DataFundacao.style.backgroundColor 				= '#FFF';
					document.formulario.Sexo.style.backgroundColor 						= '#FFF';
					document.formulario.RG_IE.style.backgroundColor 					= '#FFF';
					document.formulario.InscricaoEstadual.style.backgroundColor 		= '#FFF';
					document.formulario.EstadoCivil.style.backgroundColor 				= '#FFF';
					document.formulario.InscricaoMunicipal.style.backgroundColor		= '#FFF';
					document.formulario.Telefone1.style.backgroundColor					= '#FFF';
					document.formulario.Telefone2.style.backgroundColor					= '#FFF';
					document.formulario.Telefone3.style.backgroundColor					= '#FFF';
					document.formulario.Celular.style.backgroundColor 					= '#FFF';
					document.formulario.Fax.style.backgroundColor 						= '#FFF';
					document.formulario.ComplementoTelefone.style.backgroundColor		= '#FFF';
					document.formulario.Email.style.backgroundColor 					= '#FFF';
					document.formulario.Site.style.backgroundColor 						= '#FFF';
					document.formulario.IdEnderecoDefault.style.backgroundColor			= '#FFF';
					
					document.formulario.IdEnderecoDefaultTemp.value				= "";
					
					document.formulario.NomeAnterior.value 						= '';
					document.formulario.NomeFantasiaAnterior.value 				= '';
					document.formulario.NomeRepresentanteAnterior.value 		= '';
					document.formulario.RazaoSocialAnterior.value 				= '';
					document.formulario.DataNascimentoAnterior.value 			= '';
					document.formulario.DataFundacaoAnterior.value 				= '';
					document.formulario.SexoAnterior[0].selected 				= true;
					document.formulario.RG_IEAnterior.value 					= '';
					document.formulario.InscricaoEstadualAnterior.value 		= '';
					document.formulario.EstadoCivilAnterior[0].selected 		= true;
					document.formulario.InscricaoMunicipalAnterior.value		= '';
					document.formulario.Telefone1Anterior.value					= '';
					document.formulario.Telefone2Anterior.value					= '';
					document.formulario.Telefone3Anterior.value					= '';
					document.formulario.CelularAnterior.value 					= '';
					document.formulario.FaxAnterior.value 						= '';
					document.formulario.ComplementoTelefoneAnterior.value		= '';
					document.formulario.EmailAnterior.value 					= '';
					document.formulario.ComplementoTelefoneAnterior.value		= '';
					document.formulario.IdEnderecoDefaultAnterior.value 		= '';
					document.formulario.QtdEnderecoAnterior.value				= 0;
					document.formulario.QtdEnderecoAuxAnterior.value			= 0;
//					document.formulario.IdEnderecoCobrancaDefaultAnterior.value = 'http://';
					document.formulario.Acao.value 								= 'inserir';
					
					while(document.getElementById('tableEndereco').rows.length > 0){
						document.getElementById('tableEndereco').deleteRow(0);
					}
					while(document.getElementById('tableEnderecoAnterior').rows.length > 0){
						document.getElementById('tableEnderecoAnterior').deleteRow(0);
					}
					
					if(document.formulario.CampoExtra1 != undefined){
						document.formulario.CampoExtra1.value					= '';
						document.formulario.CampoExtra1.style.border			= '1px #A4A4A4 solid';
						document.formulario.CampoExtra1.style.backgroundColor	= '#FFF';
					}
					if(document.formulario.CampoExtra2 != undefined){
						document.formulario.CampoExtra2.value					= '';
						document.formulario.CampoExtra2.style.border			= '1px #A4A4A4 solid';
						document.formulario.CampoExtra2.style.backgroundColor	= '#FFF';
					}
					if(document.formulario.CampoExtra3 != undefined){
						document.formulario.CampoExtra3.value					= '';
						document.formulario.CampoExtra1.style.border			= '1px #A4A4A4 solid';
						document.formulario.CampoExtra3.style.backgroundColor	= '#FFF';
					}
					if(document.formulario.CampoExtra4 != undefined){
						document.formulario.CampoExtra4.value					= '';
						document.formulario.CampoExtra1.style.border			= '1px #A4A4A4 solid';	
						document.formulario.CampoExtra4.style.backgroundColor	= '#FFF';
					}
					
					if(document.formulario.CampoExtra1Anterior != undefined){
						document.formulario.CampoExtra1Anterior .value			= '';
					}
					if(document.formulario.CampoExtra2Anterior  != undefined){
						document.formulario.CampoExtra2Anterior .value			= '';
					}
					if(document.formulario.CampoExtra3Anterior  != undefined){
						document.formulario.CampoExtra3Anterior .value			= '';
					}
					if(document.formulario.CampoExtra4Anterior  != undefined){
						document.formulario.CampoExtra4Anterior .value			= '';
					}
					
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
					
					document.getElementById('cp_DataNascimento_Titulo').style.backgroundColor = '#FFFFFF';
					document.getElementById('cp_DataNascimento_Titulo').style.color 		  = '#000000';
					
					document.getElementById('cp_DataFundacao_Titulo').style.backgroundColor   = '#FFFFFF';
					document.getElementById('cp_DataFundacao_Titulo').style.color 			  = '#000000';
					
					document.getElementById('cpStatus').innerHTML 	= '';
					
					verificaAcao();
					
					document.formulario.IdPessoaSolicitacao.focus();
					
					// Fim de Carregando
					carregando(false);
				}else{
					nameNode = xmlhttp.responseXML.getElementsByTagName("IdPessoaSolicitacao")[0]; 
					nameTextNode = nameNode.childNodes[0];
					IdPessoaSolicitacao = nameTextNode.nodeValue;
				
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
											
					nameNode = xmlhttp.responseXML.getElementsByTagName("DataCriacao")[0]; 
					nameTextNode = nameNode.childNodes[0];
					var DataCriacao = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("LoginCriacao")[0]; 
					nameTextNode = nameNode.childNodes[0];
					var LoginCriacao = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("DataAprovacao")[0]; 
					nameTextNode = nameNode.childNodes[0];
					var DataAprovacao = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("LoginAprovacao")[0]; 
					nameTextNode = nameNode.childNodes[0];
					var LoginAprovacao = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("IdStatus")[0]; 
					nameTextNode = nameNode.childNodes[0];
					var IdStatus = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoStatus")[0]; 
					nameTextNode = nameNode.childNodes[0];
					var DescricaoStatus = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("Cor")[0]; 
					nameTextNode = nameNode.childNodes[0];
					var Cor = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("IdEnderecoDefault")[0]; 
					nameTextNode = nameNode.childNodes[0];
					var IdEnderecoDefault = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("QtdEndereco")[0]; 
					nameTextNode = nameNode.childNodes[0];
					var QtdEndereco = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("IP")[0]; 
					nameTextNode = nameNode.childNodes[0];
					var IP = nameTextNode.nodeValue;
							
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
					
					while(document.formulario.IdEnderecoDefault.options.length > 0){
						document.formulario.IdEnderecoDefault.options[0] = null;
					}
					
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
					
					document.formulario.IdPessoaSolicitacao.value 	= IdPessoaSolicitacao;
					document.formulario.IdPessoa.value 				= IdPessoa;
					document.formulario.Nome.value 					= Nome;					
					document.formulario.TipoPessoa.value			= TipoPessoa;
					document.formulario.CPF_CNPJ.value 				= CPF_CNPJ;
					document.formulario.NomeFantasia.value 			= Nome;
					document.formulario.NomeRepresentante.value 	= NomeRepresentante;
					document.formulario.RazaoSocial.value 			= RazaoSocial;
					document.formulario.DataNascimento.value 		= dateFormat(DataNascimento);
					document.formulario.DataFundacao.value 			= dateFormat(DataNascimento);
					document.formulario.Sexo.value 					= Sexo;
					document.formulario.EstadoCivil.value 			= EstadoCivil;
					document.formulario.RG_IE.value 				= RG_IE;
					document.formulario.InscricaoEstadual.value		= RG_IE;
					document.formulario.InscricaoMunicipal.value	= InscricaoMunicipal;
					document.formulario.Telefone1.value 			= Telefone1;
					document.formulario.Telefone2.value 			= Telefone2;
					document.formulario.Telefone3.value 			= Telefone3;
					document.formulario.Celular.value 				= Celular;
					document.formulario.Fax.value 					= Fax;
					document.formulario.ComplementoTelefone.value	= ComplementoTelefone;
					document.formulario.Email.value 				= Email;
					document.formulario.IdStatus.value 				= IdStatus;
					document.formulario.DataCriacao.value 			= dateFormat(DataCriacao);
					document.formulario.LoginCriacao.value			= LoginCriacao;
					document.formulario.DataAprovacao.value 		= dateFormat(DataAprovacao);
					document.formulario.LoginAprovacao.value		= LoginAprovacao;
					document.formulario.QtdEndereco.value			= 0;
					document.formulario.QtdEnderecoAux.value		= 0;
					document.formulario.IdEnderecoDefaultTemp.value	= IdEnderecoDefault;
					document.formulario.IP.value					= IP;
					document.formulario.Acao.value 					= 'alterar';
					
					document.formulario.Nome.style.border						= '1px #A4A4A4 solid';
					document.formulario.NomeFantasia.style.border 				= '1px #A4A4A4 solid';
					document.formulario.NomeRepresentante.style.border			= '1px #A4A4A4 solid';
					document.formulario.RazaoSocial.style.border 				= '1px #A4A4A4 solid';
					document.formulario.DataNascimento.style.border 			= '1px #A4A4A4 solid';
					document.formulario.DataFundacao.style.border 				= '1px #A4A4A4 solid';
					document.formulario.Sexo.style.border 						= '1px #A4A4A4 solid';
					document.formulario.RG_IE.style.border 						= '1px #A4A4A4 solid';
					document.formulario.InscricaoEstadual.style.border 			= '1px #A4A4A4 solid';
					document.formulario.EstadoCivil.style.border 				= '1px #A4A4A4 solid';
					document.formulario.InscricaoMunicipal.style.border			= '1px #A4A4A4 solid';
					document.formulario.Telefone1.style.border					= '1px #A4A4A4 solid';
					document.formulario.Telefone2.style.border					= '1px #A4A4A4 solid';
					document.formulario.Telefone3.style.border					= '1px #A4A4A4 solid';
					document.formulario.Celular.style.border 					= '1px #A4A4A4 solid';
					document.formulario.Fax.style.border 						= '1px #A4A4A4 solid';
					document.formulario.ComplementoTelefone.style.border		= '1px #A4A4A4 solid';
					document.formulario.Email.style.border 						= '1px #A4A4A4 solid';
					document.formulario.Site.style.border 						= '1px #A4A4A4 solid';
					document.formulario.IdEnderecoDefault.style.border 			= '1px #A4A4A4 solid';
					
					document.formulario.Nome.style.backgroundColor					= '#FFF';
					document.formulario.NomeFantasia.style.backgroundColor 			= '#FFF';
					document.formulario.NomeRepresentante.style.backgroundColor		= '#FFF';
					document.formulario.RazaoSocial.style.backgroundColor 			= '#FFF';
					document.formulario.DataNascimento.style.backgroundColor 		= '#FFF';
					document.formulario.DataFundacao.style.backgroundColor 			= '#FFF';
					document.formulario.Sexo.style.backgroundColor 					= '#FFF';
					document.formulario.RG_IE.style.backgroundColor 				= '#FFF';
					document.formulario.InscricaoEstadual.style.backgroundColor 	= '#FFF';
					document.formulario.EstadoCivil.style.backgroundColor 			= '#FFF';
					document.formulario.InscricaoMunicipal.style.backgroundColor	= '#FFF';
					document.formulario.Telefone1.style.backgroundColor				= '#FFF';
					document.formulario.Telefone2.style.backgroundColor				= '#FFF';
					document.formulario.Telefone3.style.backgroundColor				= '#FFF';
					document.formulario.Celular.style.backgroundColor 				= '#FFF';
					document.formulario.Fax.style.backgroundColor 					= '#FFF';
					document.formulario.ComplementoTelefone.style.backgroundColor	= '#FFF';
					document.formulario.Email.style.backgroundColor 				= '#FFF';
					document.formulario.Site.style.backgroundColor 					= '#FFF';
					document.formulario.IdEnderecoDefault.style.backgroundColor 	= '#FFF';
					
					if(Site == ""){
						Site	=	"http://";
					}
					
					document.formulario.Site.value 					= Site;
					
					while(document.getElementById('tableEndereco').rows.length > 0){
						document.getElementById('tableEndereco').deleteRow(0);
					}
					while(document.getElementById('tableEnderecoAnterior').rows.length > 0){
						document.getElementById('tableEnderecoAnterior').deleteRow(0);
					}
					
					for(i=1;i<=QtdEndereco;i++){
						formulario_endereco();
					}
					
					busca_pessoa_endereco(IdPessoa,IdEnderecoDefault,'',IdPessoaSolicitacao);
					
					if(document.formulario.CampoExtra1 != undefined){
						document.formulario.CampoExtra1.value					= CampoExtra1;
						document.formulario.CampoExtra1.style.border			= '1px #A4A4A4 solid';
						document.formulario.CampoExtra1.style.backgroundColor	= '#FFF';
					}
					if(document.formulario.CampoExtra2 != undefined){
						document.formulario.CampoExtra2.value 					= CampoExtra2;
						document.formulario.CampoExtra2.style.border			= '1px #A4A4A4 solid';
						document.formulario.CampoExtra2.style.backgroundColor	= '#FFF';
					}
					if(document.formulario.CampoExtra3 != undefined){
						document.formulario.CampoExtra3.value					= CampoExtra3;
						document.formulario.CampoExtra3.style.border			= '1px #A4A4A4 solid';
						document.formulario.CampoExtra3.style.backgroundColor	= '#FFF';
					}
					if(document.formulario.CampoExtra4 != undefined){
						document.formulario.CampoExtra4.value					= CampoExtra4;
						document.formulario.CampoExtra4.style.border			= '1px #A4A4A4 solid';
						document.formulario.CampoExtra4.style.backgroundColor	= '#FFF';
					}
					document.getElementById('cp_DataNascimento_Titulo').style.backgroundColor = '#FFFFFF';
					document.getElementById('cp_DataNascimento_Titulo').style.color = '#000000';
					
					document.getElementById('cp_DataFundacao_Titulo').style.backgroundColor = '#FFFFFF';
					document.getElementById('cp_DataFundacao_Titulo').style.color = '#000000';
					
					document.getElementById('cpStatus').innerHTML = DescricaoStatus;
					document.getElementById('cpStatus').style.color = Cor;
					
					busca_pessoa(IdPessoa,false,Local);
					verificaAcao();
				}
			}
			// Fim de Carregando
			carregando(false);
		} 
		return true;
	}
	xmlhttp.send(null);
}
