function busca_conta_eventual(IdContaEventual, Erro, Local){
	if(IdContaEventual == ''){
		IdContaEventual = 0;
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
    
   	url = "../administrativo/xml/conta_eventual.php?IdContaEventual="+IdContaEventual;
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
				if(xmlhttp.responseText == "false"){
					switch(Local){
						case 'ContaEventual':
							if(Erro != 80 || IdContaEventual == 0){	
								document.formulario.IdContaEventual.value 				= '';
								document.formulario.DescricaoContaEventual.value 		= '';
								
								document.getElementById('cp_juridica').style.display	= 'block';
								document.getElementById('cp_fisica').style.display		= 'none';
								document.getElementById('cp_CPF_CNPJ_Titulo').innerHTML = "CNPJ";
							
								document.formulario.IdPessoa.readOnly				= false;
								document.formulario.IdPessoaF.readOnly				= false;
								document.formulario.IdPessoa.value 					= '';
								document.formulario.Nome.value 						= '';
								document.formulario.RazaoSocial.value 				= '';
								document.formulario.Cidade.value 					= '';
								document.formulario.CPF_CNPJ.value 					= '';
								document.formulario.Email.value 					= '';
								document.formulario.Telefone1.value					= '';
								document.formulario.SiglaEstado.value				= '';
								document.formulario.IdLocalCobranca.value 			= '';
								document.formulario.DataPrimeiroVencimentoContrato.value	= '';
								document.formulario.DataPrimeiroVencimentoIndividual.value	= '';
								document.formulario.ValorTotal.value				= '';
								document.formulario.ValorTotalContrato.value		= '';
								document.formulario.ValorTotalIndividual.value		= '';
								document.formulario.ValorDespesaLocalCobranca.value	= '';
								document.formulario.ValorCobrancaMinima.value		= '';
								document.formulario.QtdParcela.value				= '';
								document.formulario.QtdParcelaContrato.value		= '';
								document.formulario.QtdParcelaIndividual.value		= '';
								document.formulario.IdStatus.value					= 0;
								document.formulario.Status.value					= 0;
								document.formulario.ObsContaEventual.value			= '';
								document.formulario.IdContratoAgrupador.value 		= '';
								document.formulario.FormaCobranca.value 			= 0;
								document.formulario.OcultarReferencia[0].selected	= true;
								
								while(document.getElementById('tabelaVencimento').rows.length > 2){
									document.getElementById('tabelaVencimento').deleteRow(1);
								}
								
								document.getElementById('cp_Vencimento').style.display	=	'none';
								
								document.formulario.DataCriacao.value 		= "";
								document.formulario.LoginCriacao.value 		= "";
								document.formulario.DataAlteracao.value 	= "";
								document.formulario.LoginAlteracao.value	= "";
								document.formulario.Acao.value 				= 'inserir';
								
								status_inicial();
								
								addParmUrl("marContasReceber","IdContaEventual","");
								addParmUrl("marLancamentoFinanceiro","IdContaEventual","");
								addParmUrl("marContaEventual","IdContaEventual","");
								
								document.getElementById('titFormaCobranca').style.display		=	'block';
								document.getElementById('titContrato').style.display			=	'none';
								document.getElementById('titIndividual').style.display			=	'none';
									
								document.formulario.IdContaEventual.focus();
							}else{
								document.formulario.ValorTotal.focus();
							}
							break;
						case 'LancamentoFinanceiro':
							document.formulario.IdContaEventual.value 					= '';
							document.formulario.DescricaoContaEventual.value 			= '';
							document.formulario.FormaCobranca[0].selected 				= true;
							document.formulario.IdContratoAgrupador[0].selected 		= true;
							document.formulario.ValorTotalContrato.value				= '';
							document.formulario.QtdParcelaContrato.value				= '';
							document.formulario.DataPrimeiroVencimentoContrato.value	= '';
							document.formulario.ValorTotalIndividual.value				= '';
							document.formulario.ValorDespesaLocalCobranca.value			= '';
							document.formulario.QtdParcelaIndividual.value				= '';
							document.formulario.DataPrimeiroVencimentoIndividual.value	= '';
							
							document.getElementById('cpContaEventual').style.display	=	'none';
							break;
					}
					// Fim de Carregando
					carregando(false);
				}else{
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("IdContaEventual")[0]; 
					nameTextNode = nameNode.childNodes[0];
					var IdContaEventual = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoContaEventual")[0]; 
					nameTextNode = nameNode.childNodes[0];
					var DescricaoContaEventual = nameTextNode.nodeValue;
					
					document.formulario.IdContaEventual.value				= IdContaEventual;
					document.formulario.DescricaoContaEventual.value 		= DescricaoContaEventual;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("IdPessoa")[0]; 
					nameTextNode = nameNode.childNodes[0];
					var IdPessoa = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("ValorTotal")[0]; 
					nameTextNode = nameNode.childNodes[0];
					var ValorTotal = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("ValorDespesaLocalCobranca")[0]; 
					nameTextNode = nameNode.childNodes[0];
					var ValorDespesaLocalCobranca = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("QtdParcela")[0]; 
					nameTextNode = nameNode.childNodes[0];
					var QtdParcela = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("IdContrato")[0]; 
					nameTextNode = nameNode.childNodes[0];
					var IdContratoAgrupador = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("DataPrimeiroVencimento")[0]; 
					nameTextNode = nameNode.childNodes[0];
					var DataPrimeiroVencimento = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("IdStatus")[0]; 
					nameTextNode = nameNode.childNodes[0];
					var IdStatus = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("IdLocalCobranca")[0]; 
					nameTextNode = nameNode.childNodes[0];
					var IdLocalCobranca = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("FormaCobranca")[0]; 
					nameTextNode = nameNode.childNodes[0];
					var FormaCobranca = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("ObsContaEventual")[0]; 
					nameTextNode = nameNode.childNodes[0];
					var ObsContaEventual = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("ValorCobrancaMinima")[0]; 
					nameTextNode = nameNode.childNodes[0];
					var ValorCobrancaMinima = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("OcultarReferencia")[0]; 
					nameTextNode = nameNode.childNodes[0];
					var OcultarReferencia = nameTextNode.nodeValue;
					
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
						case 'ContaEventual':
							listar_parcela_conta(IdContaEventual);	
							busca_pessoa(IdPessoa,false,Local);	
							listar_contrato(IdPessoa,IdContratoAgrupador);
							
							switch(FormaCobranca){
								case '1':
									document.formulario.ValorTotalContrato.value				= formata_float(Arredonda(ValorTotal,2),2).replace(".",",");
									document.formulario.DataPrimeiroVencimentoContrato.value	= DataPrimeiroVencimento;
									document.formulario.IdContratoAgrupador.value 				= IdContratoAgrupador;
									document.formulario.QtdParcelaContrato.value				= QtdParcela;
									
									document.getElementById('titFormaCobranca').style.display	=	'none';
									document.getElementById('titContrato').style.display		=	'block';
									document.getElementById('titIndividual').style.display		=	'none';	
									break;
								case '2':
									document.formulario.DataPrimeiroVencimentoIndividual.value	= dateFormat(DataPrimeiroVencimento);
									document.formulario.ValorTotalIndividual.value				= formata_float(Arredonda(ValorTotal,2),2).replace(".",",");
									document.formulario.ValorDespesaLocalCobranca.value			= formata_float(Arredonda(ValorDespesaLocalCobranca,2),2).replace(".",",");
									document.formulario.IdLocalCobranca.value 					= IdLocalCobranca;
									document.formulario.QtdParcelaIndividual.value				= QtdParcela;
									
									document.getElementById('titFormaCobranca').style.display	=	'none';
									document.getElementById('titContrato').style.display		=	'none';
									document.getElementById('titIndividual').style.display		=	'block';
									break;
							}					
							
							document.formulario.FormaCobranca.value 			= FormaCobranca;
							document.formulario.ValorTotal.value				= '';
							document.formulario.QtdParcela.value				= '';
							document.formulario.IdStatus.value					= IdStatus;
							document.formulario.Status.value					= IdStatus;
							document.formulario.ValorCobrancaMinima.value		= ValorCobrancaMinima;
							document.formulario.ObsContaEventual.value			= ObsContaEventual;
							document.formulario.OcultarReferencia.value			= OcultarReferencia;
							
							addParmUrl("marPessoa","IdPessoa",IdPessoa);
							addParmUrl("marContasReceber","IdContaEventual",IdContaEventual);
							addParmUrl("marLancamentoFinanceiro","IdContaEventual",IdContaEventual);
							addParmUrl("marContrato","IdPessoa",IdPessoa);
							addParmUrl("marContratoNovo","IdPessoa",IdPessoa);
							addParmUrl("marReenvioEmail","IdPessoa",IdPessoa);
							addParmUrl("marContaEventual","IdContaEventual",IdContaEventual);
							addParmUrl("marContaEventualNovo","IdPessoa",IdPessoa);
							
							if(IdStatus == 2 || IdStatus == 0){
								document.formulario.IdPessoa.readOnly				= true;
								document.formulario.IdPessoaF.readOnly				= true;
							}else{								
								document.formulario.IdPessoa.readOnly				= false;
								document.formulario.IdPessoaF.readOnly				= false;
							}
							
							
							document.formulario.DataCriacao.value 		= dateFormat(DataCriacao);
							document.formulario.LoginCriacao.value 		= LoginCriacao;
							document.formulario.DataAlteracao.value 	= dateFormat(DataAlteracao);
							document.formulario.LoginAlteracao.value	= LoginAlteracao;
							document.formulario.Acao.value 				= 'alterar';
							break;
						case 'LancamentoFinanceiro':
							document.formulario.FormaCobranca.value 			= FormaCobranca;
							
							switch(FormaCobranca){
								case '1': //Contrato
									document.getElementById('cpContaEventualContrato').style.display	=	'block';
									document.getElementById('cpContaEventualIndividual').style.display	=	'none';
									
									document.formulario.ValorTotalContrato.value				= formata_float(Arredonda(ValorTotal,2),2).replace(".",",");
									document.formulario.DataPrimeiroVencimentoContrato.value	= DataPrimeiroVencimento;
									document.formulario.IdContratoAgrupador.value 				= IdContratoAgrupador;
									document.formulario.QtdParcelaContrato.value				= QtdParcela;
									
									listar_contrato_agrupador(IdPessoa,IdContratoAgrupador);
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
					
					if(document.formulario.Erro.value != ''){
						scrollWindow('bottom');
					}else{
						scrollWindow('top');
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

