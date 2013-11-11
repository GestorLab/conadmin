	function busca_lote_repasse(IdLoteRepasse, Erro, Local){
		if(IdLoteRepasse == ''){
			IdLoteRepasse = 0;
		}
		if(Local=='' || Local==undefined){
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
		url = "xml/lote_repasse.php?IdLoteRepasse="+IdLoteRepasse;
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
							addParmUrl("marTerceiro","IdPessoa","");
							addParmUrl("marLoteRepasse","IdLoteRepasse","");
							
							document.formulario.IdLoteRepasse.value 			= '';
							document.formulario.IdPessoa.value 					= '';
							document.formulario.Nome.value 						= '';
							document.formulario.ObsRepasse.value 				= '';
							document.formulario.IdStatus.value 					= '';
							document.formulario.DataCriacao.value 				= '';
							document.formulario.LoginCriacao.value 				= '';
							document.formulario.DataAlteracao.value 			= '';
							document.formulario.LoginAlteracao.value			= '';
							document.formulario.DataProcessamento.value 		= '';
							document.formulario.LoginProcessamento.value 		= '';
							document.formulario.DataConfirmacao.value 			= '';
							document.formulario.LoginConfirmacao.value			= '';
							document.formulario.Filtro_MesReferencia.value		= '';
							document.formulario.Filtro_IdServico.value			= '';
							document.formulario.Filtro_IdPessoa.value			= '';
							document.formulario.TotalRepasse.value				= '';
							document.formulario.TotalValor.value				= '';
							document.formulario.UltimoLote.value				= '';
							document.formulario.ObsRepasse.readOnly 			= false;
							document.formulario.IdPessoa.readOnly				= false;
							document.formulario.Filtro_MesReferencia.readOnly	= false;
							document.formulario.Acao.value 						= 'inserir';
							
							while(document.getElementById('tabelaLancFinanceiro').rows.length > 2){
								document.getElementById('tabelaLancFinanceiro').deleteRow(1);
							}
							
							while(document.getElementById('tabelaServico').rows.length > 2){
								document.getElementById('tabelaServico').deleteRow(1);
							}
							while(document.getElementById('tabelaPessoa').rows.length > 2){
								document.getElementById('tabelaPessoa').deleteRow(1);
							}
							
							document.getElementById('cp_Status').style.display			= 'none';
							
							document.getElementById('totaltabelaServico').innerHTML	= 'Total: 0';
							document.getElementById('cptotalRepasse').innerHTML		= '0,00';
							document.getElementById('cptotalValor').innerHTML		= '0,00';
							
							document.getElementById('tabelaTotal').innerHTML			= 'Total: 0';
							document.getElementById('tabelaTotalItem').innerHTML		= '0,00';
							document.getElementById('tabelaTotalRepasse').innerHTML		= '0,00';
							document.getElementById('titTabelaServico').style.display	= 'block';	
							document.getElementById('tabelaServico').style.display		= 'block';
							
							document.getElementById('tabelaPessoa').style.display		= 'block';
							document.getElementById('titTabelaPessoa').style.display	= 'block';								
							document.getElementById('totaltabelaPessoa').innerHTML		= "Total: 0";
							
							document.getElementById('cp_LancFinanceiro').style.display	= 'none';
							document.getElementById('cpFiltro').style.display		= 'block';	
							
							document.formulario.IdLoteRepasse.focus();
							verificaAcao();
						// Fim de Carregando
						carregando(false);
					}else{
						nameNode = xmlhttp.responseXML.getElementsByTagName("IdLoteRepasse")[0]; 
						nameTextNode = nameNode.childNodes[0];
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
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("Filtro_MesReferencia")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var Filtro_MesReferencia = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("Filtro_IdServico")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var Filtro_IdServico = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("Filtro_IdPessoa")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var Filtro_IdPessoa = nameTextNode.nodeValue;
						
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
						
						document.formulario.IdLoteRepasse.value 		= IdLoteRepasse;		
						document.formulario.IdPessoa.value 				= IdPessoa;
						document.formulario.Nome.value 					= Nome;
						document.formulario.IdStatus.value 				= IdStatus;
						document.formulario.ObsRepasse.value 			= ObsRepasse;
						document.formulario.DataCriacao.value 			= dateFormat(DataCriacao);
						document.formulario.LoginCriacao.value 			= LoginCriacao;
						document.formulario.DataAlteracao.value 		= dateFormat(DataAlteracao);
						document.formulario.LoginAlteracao.value		= LoginAlteracao;
						document.formulario.DataProcessamento.value 	= dateFormat(DataProcessamento);
						document.formulario.LoginProcessamento.value	= LoginProcessamento;
						document.formulario.DataConfirmacao.value 		= dateFormat(DataConfirmacao);
						document.formulario.LoginConfirmacao.value		= LoginConfirmacao;
						document.formulario.UltimoLote.value			= UltimoLote;
						document.formulario.Filtro_MesReferencia.value	= Filtro_MesReferencia;
						document.formulario.Filtro_IdServico.value		= Filtro_IdServico;
						document.formulario.Filtro_IdPessoa.value		= Filtro_IdPessoa;
						
						while(document.getElementById('tabelaLancFinanceiro').rows.length > 2){
							document.getElementById('tabelaLancFinanceiro').deleteRow(1);
						}
						while(document.getElementById('tabelaServico').rows.length > 2){
							document.getElementById('tabelaServico').deleteRow(1);
						}
						while(document.getElementById('tabelaPessoa').rows.length > 2){
							document.getElementById('tabelaPessoa').deleteRow(1);
						}
						
						document.getElementById('tabelaPessoa').style.display		= 'none';
						document.getElementById('titTabelaPessoa').style.display	= 'none';
						
						document.getElementById('tabelaServico').style.display		= 'none';
						document.getElementById('titTabelaServico').style.display	= 'none';
						
						document.getElementById('cp_LancFinanceiro').style.display	= 'none';
						
						document.getElementById('cpFiltro').style.display			= 'none';		
						
						//############# Filtro_IdServico ###########################
						if(Filtro_IdServico != ""){
							var temp = Filtro_IdServico;
									
							var tempFiltro	=	temp.split(',');
							var novoFiltro  = '';
							var ii=0;
							if(Filtro_IdServico != ''){
								while(tempFiltro[ii] != undefined){
									busca_servico(tempFiltro[ii],false,'LoteRepasse','listar');
									ii++;
								}
							}
							
							document.getElementById('totaltabelaServico').innerHTML	=	"Total: "+ii;
									
							document.getElementById('tabelaServico').style.display		= 'block';
							document.getElementById('titTabelaServico').style.display	= 'block';
							document.getElementById('cpFiltro').style.display				= 'block';	
						
						}
						//############# Filtro_IdPessoa ###########################
						if(Filtro_IdPessoa != ""){
							var temp = Filtro_IdPessoa;
									
							var tempFiltro	=	temp.split(',');
							var ii=0;
							if(Filtro_IdPessoa != ''){
								while(tempFiltro[ii] != undefined){
									busca_pessoa(tempFiltro[ii],false,'LoteRepasse','','listar');
									ii++;
								}
							}
							document.getElementById('tabelaPessoa').style.display		= 'block';
							document.getElementById('titTabelaPessoa').style.display	= 'block';
							document.getElementById('cpFiltro').style.display			= 'block';	
						}
						
						if(IdStatus > 1){
							document.getElementById('cp_LancFinanceiro').style.display	= 'block';	

							document.formulario.ObsRepasse.readOnly 			= true;
							document.formulario.IdPessoa.readOnly				= true;
							document.formulario.Filtro_MesReferencia.readOnly	= true;


							listar_lancamento_financeiro(IdLoteRepasse);
						}else{
							document.getElementById('cp_LancFinanceiro').style.display	= 'none';	

							document.formulario.ObsRepasse.readOnly 			= false;
							document.formulario.IdPessoa.readOnly				= false;
							document.formulario.Filtro_MesReferencia.readOnly	= false;
						}
						
						document.getElementById('cp_Status').innerHTML			= Status;
						document.getElementById('cp_Status').style.display		= 'block';
						document.getElementById('cp_Status').style.color		= Cor;

						switch(IdStatus){
							case '1':
								document.formulario.Acao.value 		= 'alterar';
								
								document.getElementById('tabelaPessoa').style.display		= 'block';
								document.getElementById('titTabelaPessoa').style.display	= 'block';
						
								document.getElementById('tabelaServico').style.display		= 'block';
								document.getElementById('titTabelaServico').style.display	= 'block';
								
								document.getElementById('cpFiltro').style.display			= 'block';	
								break;
							case '2':
								document.formulario.Acao.value 		= 'processar';
								break;
							case '3':
								document.formulario.Acao.value 		= 'confirmar';
								break;
						}
						
						verificaAcao();
					}
					if(window.janela != undefined){
						window.janela.close();
					}
				}
				// Fim de Carregando
				carregando(false);
			} 
			return true;
		}
		xmlhttp.send(null);
	}	
	
