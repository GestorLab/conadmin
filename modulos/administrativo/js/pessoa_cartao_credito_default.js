	function busca_pessoa_cartao_credito(IdPessoa, IdCartao, Erro, Local,ListarCampo){		if(IdPessoa == '' || IdPessoa == undefined){			IdPessoa = 0;		}		if(IdCartao == '' || IdCartao == undefined){			IdCartao = 0;		}		if(Local == '' || Local == undefined){			Local	=	document.formulario.Local.value;		}				var nameNode, nameTextNode, url;			   	url = "xml/pessoa_cartao_credito.php?IdCartaoCredito="+IdCartao+"&IdPessoa="+IdPessoa+"&Cadastro=true";		call_ajax(url,function(xmlhttp){			if(xmlhttp.responseText == 'false'){				switch(Local){					case 'CartaoCredito':						document.formulario.IdCartao.value				= '';						document.formulario.IdBandeiraCartao.value		= '';						document.formulario.NomeTitular.value			= '';						document.formulario.NumeroCartao.value			= '';						document.formulario.MesValidade.value			= '';						document.formulario.AnoValidade.value			= '';						document.formulario.CodigoSeguranca.value		= '';						document.formulario.DiaVencimentoFatura.value	= '';						document.formulario.IdStatus.value				= '';						document.formulario.LoginCriacao.value			= '';						document.formulario.DataCriacao.value			= '';						document.formulario.LoginAlteracao.value		= '';						document.formulario.DataAlteracao.value			= '';						document.formulario.IdBandeiraCartao.disabled		= false;						document.formulario.NomeTitular.readOnly			= false;						document.formulario.NumeroCartao.readOnly			= false;						document.formulario.MesValidade.disabled			= false;						document.formulario.AnoValidade.disabled			= false;						document.formulario.CodigoSeguranca.readOnly		= false;						document.formulario.IdStatus.readOnly				= false;						document.formulario.DiaVencimentoFatura.readOnly	= false;						document.formulario.Acao.value					= 'inserir';						document.formulario.IdCartao.focus();						listar_cartao_credito(document.formulario.IdPessoa.value,Erro,Local);						break;				}			} else{				nameNode = xmlhttp.responseXML.getElementsByTagName("IdCartao")[0]; 				nameTextNode = nameNode.childNodes[0];				var IdCartao = nameTextNode.nodeValue;								nameNode = xmlhttp.responseXML.getElementsByTagName("IdPessoa")[0]; 				nameTextNode = nameNode.childNodes[0];				var IdPessoa = nameTextNode.nodeValue;								nameNode = xmlhttp.responseXML.getElementsByTagName("IdBandeira")[0]; 				nameTextNode = nameNode.childNodes[0];				var IdBandeira = nameTextNode.nodeValue;								nameNode = xmlhttp.responseXML.getElementsByTagName("NomeTitular")[0]; 				nameTextNode = nameNode.childNodes[0];				var NomeTitular = nameTextNode.nodeValue;								nameNode = xmlhttp.responseXML.getElementsByTagName("NumeroCartao")[0]; 				nameTextNode = nameNode.childNodes[0];				var NumeroCartao = nameTextNode.nodeValue;								nameNode = xmlhttp.responseXML.getElementsByTagName("Validade")[0]; 				nameTextNode = nameNode.childNodes[0];				var Validade = nameTextNode.nodeValue;								nameNode = xmlhttp.responseXML.getElementsByTagName("IdStatus")[0]; 				nameTextNode = nameNode.childNodes[0];				var IdStatus = nameTextNode.nodeValue;								nameNode = xmlhttp.responseXML.getElementsByTagName("Status")[0]; 				nameTextNode = nameNode.childNodes[0];				var Status = nameTextNode.nodeValue;								nameNode = xmlhttp.responseXML.getElementsByTagName("CodigoSeguranca")[0]; 				nameTextNode = nameNode.childNodes[0];				var CodigoSeguranca = nameTextNode.nodeValue;								nameNode = xmlhttp.responseXML.getElementsByTagName("DiaVencimentoFatura")[0]; 				nameTextNode = nameNode.childNodes[0];				var DiaVencimentoFatura = nameTextNode.nodeValue;								nameNode = xmlhttp.responseXML.getElementsByTagName("LoginCriacao")[0]; 				nameTextNode = nameNode.childNodes[0];				var LoginCriacao = nameTextNode.nodeValue;								nameNode = xmlhttp.responseXML.getElementsByTagName("DataCriacao")[0]; 				nameTextNode = nameNode.childNodes[0];				var DataCriacao = nameTextNode.nodeValue;								nameNode = xmlhttp.responseXML.getElementsByTagName("LoginAlteracao")[0]; 				nameTextNode = nameNode.childNodes[0];				var LoginAlteracao = nameTextNode.nodeValue;								nameNode = xmlhttp.responseXML.getElementsByTagName("DataAlteracao")[0]; 				nameTextNode = nameNode.childNodes[0];				var DataAlteracao = nameTextNode.nodeValue;								nameNode = xmlhttp.responseXML.getElementsByTagName("NumeroTitulo")[0]; 				nameTextNode = nameNode.childNodes[0];				var NumeroTitulo = nameTextNode.nodeValue;				switch(Local){					case 'CartaoCredito':						addParmUrl("marPessoa", "IdPessoa", IdPessoa);						addParmUrl("marPessoaNovo", "IdPessoa", IdPessoa);						addParmUrl("marContrato", "IdPessoa", IdPessoa);						addParmUrl("marContratoNovo", "IdPessoa", IdPessoa);						addParmUrl("marContaEventual", "IdPessoa", IdPessoa);						addParmUrl("marContaEventualNovo", "IdPessoa", IdPessoa);						addParmUrl("marOrdemServico","IdPessoa",IdPessoa);						addParmUrl("marOrdemServicoNovo","IdPessoa",IdPessoa);												var mes_ano = Validade.split("/");												document.formulario.IdCartao.value				= IdCartao;						document.formulario.IdBandeiraCartao.value		= IdBandeira;						document.formulario.IdBandeiraCartaoTemp.value	= IdBandeira;						document.formulario.NomeTitular.value			= NomeTitular;						document.formulario.NumeroCartao.value			= NumeroCartao;						document.formulario.MesValidade.value			= mes_ano[0];						document.formulario.MesValidadeTemp.value		= mes_ano[0];						document.formulario.AnoValidade.value			= mes_ano[1];						document.formulario.AnoValidadeTemp.value		= mes_ano[1];						document.formulario.CodigoSeguranca.value		= CodigoSeguranca;						document.formulario.IdStatus.value				= IdStatus;						document.formulario.DiaVencimentoFatura.value	= DiaVencimentoFatura;						document.formulario.LoginCriacao.value			= LoginCriacao;						document.formulario.DataCriacao.value			= dateFormat(DataCriacao);						document.formulario.LoginAlteracao.value		= LoginAlteracao;						document.formulario.DataAlteracao.value			= dateFormat(DataAlteracao);						document.formulario.Acao.value					=  'alterar';												if(NumeroTitulo > 0){							document.formulario.IdBandeiraCartao.disabled		= true;							document.formulario.NomeTitular.readOnly			= true;							document.formulario.NumeroCartao.readOnly			= true;							document.formulario.MesValidade.disabled			= true;							document.formulario.AnoValidade.disabled			= true;							document.formulario.CodigoSeguranca.readOnly		= true;							document.formulario.IdStatus.readOnly				= false;							document.formulario.DiaVencimentoFatura.readOnly	= true;													}else{							document.formulario.IdBandeiraCartao.disabled		= false;							document.formulario.NomeTitular.readOnly			= false;							document.formulario.NumeroCartao.readOnly			= false;							document.formulario.MesValidade.disabled			= false;							document.formulario.AnoValidade.disabled			= false;							document.formulario.CodigoSeguranca.readOnly		= false;							document.formulario.IdStatus.readOnly				= false;							document.formulario.DiaVencimentoFatura.readOnly	= false;													}												listar_cartao_credito(IdPessoa,Erro,Local);						break;										}				}							if(window.janela != undefined){				window.janela.close();			}						verificaAcao();		});	} 