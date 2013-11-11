function busca_arquivo_remessa(IdArquivoRemessa,IdLocalCobranca,Erro,Local){
	if(IdArquivoRemessa=='' || IdArquivoRemessa == undefined){
		IdArquivoRemessa = 0;
	}
	if(IdLocalCobranca == '' || IdLocalCobranca == undefined){
		IdLocalCobranca = 0;
	}	
	if(Local == '' || Local == undefined){
		Local = document.formulario.Local.value;
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
    
   	url = "xml/arquivo_remessa.php?IdLocalCobranca="+IdLocalCobranca+"&IdArquivoRemessa="+IdArquivoRemessa;
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
					switch (Local){
						case "ArquivoRemessa":
							addParmUrl("marArquivoRemessa","IdArquivoRemessa","");
							
							listaLocalCobranca('', IdLocalCobranca);
							
							addParmUrl("marArquivoRemessa","IdArquivoRemessa",'');
							addParmUrl("marContasReceber","IdArquivoRemessa",'');
							addParmUrl("marContasReceber","IdLocalCobranca",'');
							
							document.formulario.IdArquivoRemessa.value 				= '';
							document.formulario.QtdLimitContaReceber.value     		= '';
							document.formulario.IdArquivoRemessaTipo.value			= '';
							document.formulario.DescricaoArquivoRemessaTipo.value	= '';
							document.formulario.EndArquivo.value		 			= '';
							document.formulario.NomeArquivo.value		 			= '';
							document.formulario.ValorTotal.value		 			= '';
							document.formulario.IdStatus.value		 				= '';
							document.formulario.DataRemessa.value		 			= '';
							document.formulario.QtdRegistro.value					= '';
							document.formulario.FileSize.value						= '';
							document.formulario.DataCriacao.value 					= '';
							document.formulario.LoginCriacao.value 					= '';
							document.formulario.DataProcessamento.value				= '';
							document.formulario.LoginProcessamento.value			= '';													
							document.formulario.DataAlteracao.value 				= '';							
							document.formulario.LoginAlteracao.value 				= '';
							document.formulario.DataConfirmacao.value				= '';
							document.formulario.LoginConfirmacao.value				= '';
							document.formulario.NumSeqArquivo.value					= '';
							document.formulario.LogRemessa.value					= '';
							document.formulario.Acao.value 							= 'inserir';
							
							document.formulario.Visualizar.value 	= '';
							document.formulario.bt_visualizar.value = 'Visualizar';
							
							while(document.getElementById('tabelaContasReceber').rows.length > 2){
								document.getElementById('tabelaContasReceber').deleteRow(1);
							}
							
							document.getElementById('cp_Status').style.display		= "none";
							document.getElementById('cp_Status').innerHTML			= "";	
							document.getElementById('cp_titulos').style.display		= "none";
							document.getElementById('cpValorTotal').innerHTML		= "0,00";	
							document.getElementById('tabelaTotal').innerHTML		= "Total: 0";
							
							document.formulario.bt_visualizar.disabled 				= true;
							break;
						case "CancelarArquivoRemessa":
							addParmUrl("marCancelarArquivoRemessa", "IdArquivoRemessa", '');
							
							listaLocalCobranca('', IdLocalCobranca);
							
							document.formulario.IdArquivoRemessa.value 				= '';
							document.formulario.QtdLimitContaReceber.value     		= '';
							document.formulario.IdArquivoRemessaTipo.value			= '';
							document.formulario.DescricaoArquivoRemessaTipo.value	= '';
							document.formulario.EndArquivo.value		 			= '';
							document.formulario.NomeArquivo.value		 			= '';
							document.formulario.ValorTotal.value		 			= '';
							document.formulario.IdStatus.value		 				= '';
							document.formulario.DataRemessa.value		 			= '';
							document.formulario.QtdRegistro.value					= '';
							document.formulario.FileSize.value						= '';
							document.formulario.DataCriacao.value 					= '';
							document.formulario.LoginCriacao.value 					= '';
							document.formulario.DataProcessamento.value				= '';
							document.formulario.LoginProcessamento.value			= '';
							document.formulario.DataAlteracao.value 				= '';							
							document.formulario.LoginAlteracao.value 				= '';
							document.formulario.DataConfirmacao.value				= '';
							document.formulario.LoginConfirmacao.value				= '';
							document.formulario.NumSeqArquivo.value					= '';
							document.formulario.LogRemessa.value					= '';
							document.formulario.Visualizar.value					= '';
							document.getElementById('cp_Status').style.display		= "none";
							document.getElementById('cp_Status').innerHTML			= '';
							document.formulario.Acao.value 							= "inserir";
							break;
					}
					
					// Fim de Carregando
					carregando(false);
					verificaAcao();
				}else{
					nameNode = xmlhttp.responseXML.getElementsByTagName("IdArquivoRemessa")[0]; 
					nameTextNode = nameNode.childNodes[0];
					IdArquivoRemessa = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("IdLocalCobranca")[0]; 
					nameTextNode = nameNode.childNodes[0];
					IdLocalCobranca = nameTextNode.nodeValue;						
			
					nameNode = xmlhttp.responseXML.getElementsByTagName("IdArquivoRemessaTipo")[0]; 
					nameTextNode = nameNode.childNodes[0];
					var IdArquivoRemessaTipo = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoArquivoRemessaTipo")[0]; 
					nameTextNode = nameNode.childNodes[0];
					var DescricaoArquivoRemessaTipo = nameTextNode.nodeValue;
			
					nameNode = xmlhttp.responseXML.getElementsByTagName("EndArquivo")[0]; 
					nameTextNode = nameNode.childNodes[0];
					var EndArquivo = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("NomeArquivo")[0]; 
					nameTextNode = nameNode.childNodes[0];
					var NomeArquivo = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("ValorTotal")[0]; 
					nameTextNode = nameNode.childNodes[0];
					var ValorTotal = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("IdStatus")[0]; 
					nameTextNode = nameNode.childNodes[0];
					var IdStatus = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("Status")[0]; 
					nameTextNode = nameNode.childNodes[0];
					var Status = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("Cor")[0]; 
					nameTextNode = nameNode.childNodes[0];
					var Cor = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("DataRemessa")[0]; 
					nameTextNode = nameNode.childNodes[0];
					var DataRemessa = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("QtdRegistro")[0]; 
					nameTextNode = nameNode.childNodes[0];
					var QtdRegistro = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("FileSize")[0]; 
					nameTextNode = nameNode.childNodes[0];
					var FileSize = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("DataCriacao")[0]; 
					nameTextNode = nameNode.childNodes[0];
					var DataCriacao = nameTextNode.nodeValue;
										
					nameNode = xmlhttp.responseXML.getElementsByTagName("LoginCriacao")[0]; 
					nameTextNode = nameNode.childNodes[0];
					var LoginCriacao = nameTextNode.nodeValue;					
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("DataProcessamento")[0]; 
					nameTextNode = nameNode.childNodes[0];
					var DataProcessamento = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("LoginProcessamento")[0]; 
					nameTextNode = nameNode.childNodes[0];
					var LoginProcessamento = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("NumSeqArquivo")[0]; 
					nameTextNode = nameNode.childNodes[0];
					var NumSeqArquivo = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("LogRemessa")[0]; 
					nameTextNode = nameNode.childNodes[0];
					var LogRemessa = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("QtdLimitContaReceber")[0]; 
					nameTextNode = nameNode.childNodes[0];
					var QtdLimitContaReceber = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("DataAlteracao")[0]; 
					nameTextNode = nameNode.childNodes[0];
					var DataAlteracao = nameTextNode.nodeValue;
										
					nameNode = xmlhttp.responseXML.getElementsByTagName("LoginAlteracao")[0]; 
					nameTextNode = nameNode.childNodes[0];
					var LoginAlteracao = nameTextNode.nodeValue;					
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("DataConfirmacao")[0]; 
					nameTextNode = nameNode.childNodes[0];
					var DataConfirmacao = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("LoginConfirmacao")[0]; 
					nameTextNode = nameNode.childNodes[0];
					var LoginConfirmacao = nameTextNode.nodeValue;

					if(ValorTotal!=""){
						ValorTotal	=	formata_float(Arredonda(ValorTotal,2),2).replace(".",",");
					}
				
					switch (Local){
						case "ArquivoRemessa":
							nameNode = xmlhttp.responseXML.getElementsByTagName("IdArquivoRemessaFila")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var IdArquivoRemessaFila = nameTextNode.nodeValue;
							
							addParmUrl("marArquivoRemessa","IdArquivoRemessa",IdArquivoRemessa);
							addParmUrl("marContasReceber","IdArquivoRemessa",IdArquivoRemessa);
							addParmUrl("marContasReceber","IdLocalCobranca",IdLocalCobranca);
							
							document.formulario.IdArquivoRemessa.value 				= IdArquivoRemessa;
							document.formulario.IdLocalCobranca.value 				= IdLocalCobranca;
							document.formulario.IdArquivoRemessaTipo.value			= IdArquivoRemessaTipo;
							document.formulario.DescricaoArquivoRemessaTipo.value	= DescricaoArquivoRemessaTipo;
							document.formulario.EndArquivo.value					= EndArquivo;
							document.formulario.NomeArquivo.value					= NomeArquivo;
							document.formulario.ValorTotal.value					= ValorTotal;
							document.formulario.DataRemessa.value					= dateFormat(DataRemessa);
							document.formulario.QtdRegistro.value					= QtdRegistro;
							document.formulario.FileSize.value						= FileSize;
							document.formulario.NumSeqArquivo.value					= NumSeqArquivo;
							document.formulario.LogRemessa.value					= LogRemessa;
							document.formulario.IdStatus.value						= IdStatus;
							document.formulario.DataCriacao.value 					= dateFormat(DataCriacao);
							document.formulario.LoginCriacao.value 					= LoginCriacao;
							document.formulario.QtdLimitContaReceber.value			= QtdLimitContaReceber;	
							document.formulario.DataProcessamento.value 			= dateFormat(DataProcessamento);
							document.formulario.LoginProcessamento.value			= LoginProcessamento;
							document.formulario.DataAlteracao.value 				= dateFormat(DataAlteracao);
							document.formulario.LoginAlteracao.value 				= LoginAlteracao;
							document.formulario.DataConfirmacao.value 				= dateFormat(DataConfirmacao);
							document.formulario.LoginConfirmacao.value				= LoginConfirmacao;
							document.formulario.Acao.value							= 'processar';
							
							document.formulario.Visualizar.value 	= '';
							document.formulario.bt_visualizar.value = 'Visualizar';
							
							document.getElementById('cp_titulos').style.display		= "none";
							document.getElementById('cpValorTotal').innerHTML		= "0,00";	
							document.getElementById('tabelaTotal').innerHTML		= "Total: 0";
							document.getElementById('cp_Status').innerHTML			= Status;
							document.getElementById('cp_Status').style.display		= 'block';
							document.getElementById('cp_Status').style.color		= Cor;			
							
							verificaAcao();
							
							if(IdStatus == 3){
								if(IdArquivoRemessaFila != IdArquivoRemessa){
									document.formulario.bt_cancelar.disabled = true;
								} else{
									document.formulario.bt_cancelar.disabled = false;
								}
							}
							break;
						case "CancelarArquivoRemessa":
							addParmUrl("marCancelarArquivoRemessa", "IdArquivoRemessa", IdArquivoRemessa);
							
							document.formulario.IdArquivoRemessa.value 				= IdArquivoRemessa;
							document.formulario.IdLocalCobranca.value 				= IdLocalCobranca;
							document.formulario.IdArquivoRemessaTipo.value			= IdArquivoRemessaTipo;
							document.formulario.DescricaoArquivoRemessaTipo.value	= DescricaoArquivoRemessaTipo;
							document.formulario.EndArquivo.value					= EndArquivo;
							document.formulario.NomeArquivo.value					= NomeArquivo;
							document.formulario.ValorTotal.value					= ValorTotal;
							document.formulario.DataRemessa.value					= dateFormat(DataRemessa);
							document.formulario.QtdRegistro.value					= QtdRegistro;
							document.formulario.FileSize.value						= FileSize;
							document.formulario.NumSeqArquivo.value					= NumSeqArquivo;
							document.formulario.LogRemessa.value					= LogRemessa;
							document.formulario.IdStatus.value						= IdStatus;
							document.formulario.DataCriacao.value 					= dateFormat(DataCriacao);
							document.formulario.LoginCriacao.value 					= LoginCriacao;
							document.formulario.QtdLimitContaReceber.value			= QtdLimitContaReceber;	
							document.formulario.DataProcessamento.value 			= dateFormat(DataProcessamento);
							document.formulario.LoginProcessamento.value			= LoginProcessamento;
							document.formulario.DataAlteracao.value 				= dateFormat(DataAlteracao);
							document.formulario.LoginAlteracao.value 				= LoginAlteracao;
							document.formulario.DataConfirmacao.value 				= dateFormat(DataConfirmacao);
							document.formulario.LoginConfirmacao.value				= LoginConfirmacao;
							document.formulario.Visualizar.value					= '';
							document.getElementById('cp_Status').innerHTML			= Status;
							document.getElementById('cp_Status').style.display		= "block";
							document.getElementById('cp_Status').style.color		= Cor;			
							document.formulario.Acao.value							= "processar";
							
							verificaAcao();
							break;
					}
						
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