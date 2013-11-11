	function busca_lote_repasse(IdLoteRepasse, Erro, Local) {
		if(IdLoteRepasse == '') {
			IdLoteRepasse = 0;
			document.formulario.IdLoteRepasse.focus();
		}
		
		if(Local == '' || Local == undefined) {
			Local	=	document.formulario.Local.value;
		}
		
		var url = "xml/lote_repasse.php?IdLoteRepasse="+IdLoteRepasse;
		
		call_ajax(url, function (xmlhttp) {
			if(Erro != false) {
				document.formulario.Erro.value = 0;
				verificaErro();
			}
			
			if(xmlhttp.responseText == 'false') {
				addParmUrl("marTerceiro","IdPessoa","");
				addParmUrl("marLoteRepasse","IdLoteRepasse","");
				
				document.formulario.IdLoteRepasse.value 					= '';
				document.formulario.IdPessoa.value 							= '';
				document.formulario.Nome.value 								= '';
				document.formulario.ObsRepasse.value 						= '';
				document.formulario.IdStatus.value 							= '';
				document.formulario.DataCriacao.value 						= '';
				document.formulario.LoginCriacao.value 						= '';
				document.formulario.DataAlteracao.value 					= '';
				document.formulario.LoginAlteracao.value					= '';
				document.formulario.DataProcessamento.value 				= '';
				document.formulario.LoginProcessamento.value 				= '';
				document.formulario.DataConfirmacao.value 					= '';
				document.formulario.LoginConfirmacao.value					= '';
				document.formulario.Filtro_MesReferencia.value				= '';
				document.formulario.Filtro_MenorVencimento.value			= '';
				document.formulario.Filtro_MesReferencia.value				= '';
				document.formulario.TotalRepasse.value						= '';
				document.formulario.TotalValor.value						= '';
				document.formulario.UltimoLote.value						= '';	
				document.formulario.ObsRepasse.readOnly 					= false;
				document.formulario.IdPessoa.readOnly						= false;
				document.formulario.Filtro_MesReferencia.readOnly			= false;
				document.formulario.Filtro_MenorVencimento.readOnly			= false;
				document.formulario.Acao.value 								= 'inserir';
				
				while(document.getElementById('tabelaLancFinanceiro').rows.length > 2){
					document.getElementById('tabelaLancFinanceiro').deleteRow(1);
				}
				
				while(document.getElementById('tabelaServico').rows.length > 2){
					document.getElementById('tabelaServico').deleteRow(1);
				}
				
				while(document.getElementById('tabelaPessoa').rows.length > 2){
					document.getElementById('tabelaPessoa').deleteRow(1);
				}
				
				while(document.getElementById('tabelaCidade').rows.length > 2){
					document.getElementById('tabelaCidade').deleteRow(1);
				}
				
				while(document.getElementById('tabelaLocalRecebimento').rows.length > 2){
					document.getElementById('tabelaLocalRecebimento').deleteRow(1);
				}
				
				listar_pessoa();
				//listar_cidade();
				listar_servico();
				listar_local_recebimento();
				listar_agente_autorizado_carteira();
				
				document.getElementById('cp_Status').style.display					= 'none';
				document.getElementById('cpFiltro').style.display					= 'block';		
				document.getElementById('cp_LancFinanceiro').style.display			= 'none';
				document.getElementById('totaltabelaServico').innerHTML				= 'Total: 0';
				document.getElementById('cptotalRepasse').innerHTML					= '0,00';
				document.getElementById('cptotalValor').innerHTML					= '0,00';							
				document.getElementById('tabelaTotal').innerHTML					= 'Total: 0';
				document.getElementById('tabelaTotalItem').innerHTML				= '0,00';
				document.getElementById('tabelaTotalRepasse').innerHTML				= '0,00';							
				document.getElementById('totaltabelaPessoa').innerHTML				= "Total: 0";
				document.getElementById('totaltabelaLocalRecebimento').innerHTML	= "Total: 0";
				
				document.formulario.IdLoteRepasse.focus();
				verificaAcao();
			} else {
				var nameNode = xmlhttp.responseXML.getElementsByTagName("IdLoteRepasse")[0]; 
				var nameTextNode = nameNode.childNodes[0];
				var IdLoteRepasse = nameTextNode.nodeValue;

				nameNode = xmlhttp.responseXML.getElementsByTagName("IdPessoa")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var IdPessoa = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("Nome")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var Nome = nameTextNode.nodeValue;

				nameNode = xmlhttp.responseXML.getElementsByTagName("ObsRepasse")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var ObsRepasse = nameTextNode.nodeValue;

				nameNode = xmlhttp.responseXML.getElementsByTagName("IdStatus")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var IdStatus = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("Filtro_MenorVencimento")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var Filtro_MenorVencimento = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("Filtro_MesReferencia")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var Filtro_MesReferencia = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("Filtro_IdServico")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var Filtro_IdServico = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("Filtro_IdPessoa")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var Filtro_IdPessoa = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("Filtro_IdPaisEstadoCidade")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var Filtro_IdPaisEstadoCidade = nameTextNode.nodeValue;
						
				nameNode = xmlhttp.responseXML.getElementsByTagName("Filtro_IdLocalRecebimento")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var Filtro_IdLocalRecebimento = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("Filtro_IdAgenteAutorizado")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var Filtro_IdAgenteAutorizado = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("Filtro_IdCarteira")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var Filtro_IdCarteira = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("UltimoLote")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var UltimoLote = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("Status")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var Status = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("Cor")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var Cor = nameTextNode.nodeValue;
				
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

				nameNode = xmlhttp.responseXML.getElementsByTagName("DataProcessamento")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var DataProcessamento = nameTextNode.nodeValue;
	
				nameNode = xmlhttp.responseXML.getElementsByTagName("LoginProcessamento")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var LoginProcessamento = nameTextNode.nodeValue;					
	
				nameNode = xmlhttp.responseXML.getElementsByTagName("DataConfirmacao")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var DataConfirmacao = nameTextNode.nodeValue;
	
				nameNode = xmlhttp.responseXML.getElementsByTagName("LoginConfirmacao")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var LoginConfirmacao = nameTextNode.nodeValue;

				addParmUrl("marTerceiro","IdPessoa",IdPessoa);
				addParmUrl("marLoteRepasse","IdLoteRepasse",IdLoteRepasse);
				
				document.formulario.IdLoteRepasse.value 				= IdLoteRepasse;		
				document.formulario.IdPessoa.value 						= IdPessoa;
				document.formulario.Nome.value 							= Nome;
				document.formulario.IdStatus.value 						= IdStatus;
				document.formulario.ObsRepasse.value 					= ObsRepasse;
				document.formulario.DataCriacao.value 					= dateFormat(DataCriacao);
				document.formulario.LoginCriacao.value 					= LoginCriacao;
				document.formulario.DataAlteracao.value 				= dateFormat(DataAlteracao);
				document.formulario.LoginAlteracao.value				= LoginAlteracao;
				document.formulario.DataProcessamento.value 			= dateFormat(DataProcessamento);
				document.formulario.LoginProcessamento.value			= LoginProcessamento;
				document.formulario.DataConfirmacao.value 				= dateFormat(DataConfirmacao);
				document.formulario.LoginConfirmacao.value				= LoginConfirmacao;
				document.formulario.UltimoLote.value					= UltimoLote;
				document.formulario.Filtro_MesReferencia.value			= Filtro_MesReferencia;
				document.formulario.Filtro_MenorVencimento.value		= dateFormat(Filtro_MenorVencimento);
				
				while(document.getElementById('tabelaLancFinanceiro').rows.length > 2){
					document.getElementById('tabelaLancFinanceiro').deleteRow(1);
				}
				
				while(document.getElementById('tabelaServico').rows.length > 2){
					document.getElementById('tabelaServico').deleteRow(1);
				}
				
				while(document.getElementById('tabelaPessoa').rows.length > 2){
					document.getElementById('tabelaPessoa').deleteRow(1);
				}
				
				while(document.getElementById('tabelaLocalRecebimento').rows.length > 2){
					document.getElementById('tabelaLocalRecebimento').deleteRow(1);
				}
				
				document.getElementById('cp_LancFinanceiro').style.display	= 'none';
				document.getElementById('cpFiltro').style.display			= 'none';	

				switch(IdStatus) {
					case '1':
						document.formulario.Acao.value = 'alterar';
						break;
					case '2':
						document.formulario.Acao.value = 'processar';
						break;
					case '3':
						document.formulario.Acao.value = 'confirmar';
				}	
				
				listar_pessoa(Filtro_IdPessoa);
				listar_cidade(Filtro_IdPaisEstadoCidade);
				listar_servico(Filtro_IdServico);
				listar_local_recebimento(Filtro_IdLocalRecebimento);
				listar_agente_autorizado_carteira(Filtro_IdAgenteAutorizado,Filtro_IdCarteira);
				
				if(IdStatus > 1) {
					document.getElementById('cp_LancFinanceiro').style.display	= 'block';
					document.formulario.ObsRepasse.readOnly 					= true;
					document.formulario.IdPessoa.readOnly						= true;
					document.formulario.Filtro_MesReferencia.readOnly			= true;
					document.formulario.Filtro_MenorVencimento.readOnly			= true;
					
					listar_lancamento_financeiro(IdLoteRepasse);
				} else {
					document.getElementById('cp_LancFinanceiro').style.display	= 'none';
					document.formulario.ObsRepasse.readOnly 					= false;
					document.formulario.IdPessoa.readOnly						= false;
					document.formulario.Filtro_MesReferencia.readOnly			= false;
					document.formulario.Filtro_MenorVencimento.readOnly			= false;
				}
				
				document.getElementById('cp_Status').innerHTML		= Status;
				document.getElementById('cp_Status').style.display	= 'block';
				document.getElementById('cp_Status').style.color	= Cor;
				
				verificaAcao();
			}
			
			if(window.janela != undefined){
				window.janela.close();
			}
		});
	}