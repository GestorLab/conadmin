function janela_busca_arquivo_retorno(IdLocalRecebimento){
	if(IdLocalRecebimento != ''){
		janelas('busca_arquivo_retorno.php?IdLocalRecebimento='+IdLocalRecebimento,507,283,250,100,'');
	}
}
function busca_arquivo_retorno(IdLocalRecebimento,IdArquivoRetorno,Erro,Local){
	if(IdLocalRecebimento == ''){
		IdLocalRecebimento = 0;
	}
	
	if(IdArquivoRetorno == ''){
		IdArquivoRetorno = 0;
	}
	
	if(Local == 'CancelarArquivoRetorno' && IdLocalRecebimento == ''){
		document.formulario.IdArquivoRetorno.value = '';
		document.formulario.IdLocalRecebimento.focus();
		return false;
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
    
   	url = "xml/arquivo_retorno.php?IdLocalRecebimento="+IdLocalRecebimento+"&IdArquivoRetorno="+IdArquivoRetorno;
	
	if(Local == 'CancelarArquivoRetorno'){
		url	+=	'&IdStatus=2,3';
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
					switch (Local){
						case "ArquivoRetorno":
							addParmUrl("marArquivoRetorno","IdArquivoRetorno","");
							addParmUrl("marContasReceber","IdArquivoRetorno","");
							addParmUrl("marContasReceber","IdLocalCobranca","");
							
							limpaFormArquivo();
							
							while(document.getElementById('tabelaTitulosRecebidos').rows.length > 2){
								document.getElementById('tabelaTitulosRecebidos').deleteRow(1);
							}
							
							document.formulario.IdStatus.value 								= '';
							document.formulario.Acao.value 									= 'inserir';
							document.getElementById('cp_Arquivo').style.color				= '#C10000';
							document.getElementById('cp_titulos_recebidos').style.display	= "none";	
							document.getElementById('ValorDescTotal').innerHTML				= "0,00";	
							document.getElementById('ValorOutrasDespesasTotal').innerHTML	= "0,00";		
							document.getElementById('ValorRecebidoTotal').innerHTML			= "0,00";	
							document.getElementById('ValorMoraMultaTotal').innerHTML		= "0,00";	
							document.getElementById('cpValorTotal').innerHTML				= "0,00";	
							document.getElementById('tabelaTotal').innerHTML				= "Total: 0";	
							document.formulario.Visualizar.value 							= '';
							document.formulario.bt_visualizar.value 						= 'Visualizar';
							document.getElementById('bt_procurar').style.display 			= 'block';
							document.getElementById('cp_Status').innerHTML					= "&nbsp;";
							
							mensagens(0);
							busca_arquivo_retorno_tipo('','',false);
							listaLocalRecebimento('');
							document.formulario.IdLocalRecebimento.focus();
							break;
						case "CancelarArquivoRetorno":
							addParmUrl("marCancelarArquivoRetorno","IdArquivoRetorno","");
							addParmUrl("marContasReceber","IdArquivoRetorno","");
							addParmUrl("marContasReceber","IdLocalCobranca","");
							
							limpaFormArquivo();
							
							document.formulario.IdStatus.value 								= '';
							//document.formulario.Acao.value 								= 'inserir';
								
							//document.getElementById('cpPosicaoCobranca').style.display	=	'none';
							document.getElementById('bt_procurar').style.display = 'block';
							busca_arquivo_retorno_tipo('','',false);
						
							document.formulario.IdArquivoRetorno.focus(); 
							break;
					}
															
					// Fim de Carregando
					carregando(false);
					verificaAcao();
				}else{
					nameNode = xmlhttp.responseXML.getElementsByTagName("IdArquivoRetorno")[0]; 
					nameTextNode = nameNode.childNodes[0];
					IdArquivoRetorno = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("IdLocalRecebimento")[0]; 
					nameTextNode = nameNode.childNodes[0];
					var IdLocalRecebimento = nameTextNode.nodeValue;						
			
					nameNode = xmlhttp.responseXML.getElementsByTagName("EndArquivo")[0]; 
					nameTextNode = nameNode.childNodes[0];
					var EndArquivo = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("NomeArquivo")[0]; 
					nameTextNode = nameNode.childNodes[0];
					var NomeArquivo = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("ValorTotal")[0]; 
					nameTextNode = nameNode.childNodes[0];
					var ValorTotal = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("ValorTotalTaxa")[0]; 
					nameTextNode = nameNode.childNodes[0];
					var ValorTotalTaxa = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("ValorLiquido")[0]; 
					nameTextNode = nameNode.childNodes[0];
					var ValorLiquido = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("IdStatus")[0]; 
					nameTextNode = nameNode.childNodes[0];
					var IdStatus = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("Status")[0]; 
					nameTextNode = nameNode.childNodes[0];
					var Status = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("Cor")[0]; 
					nameTextNode = nameNode.childNodes[0];
					var Cor = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("DataRetorno")[0]; 
					nameTextNode = nameNode.childNodes[0];
					var DataRetorno = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("QtdRegistro")[0]; 
					nameTextNode = nameNode.childNodes[0];
					var QtdRegistro = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("FileSize")[0]; 
					nameTextNode = nameNode.childNodes[0];
					var FileSize = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("IdTipoLocalCobranca")[0]; 
					nameTextNode = nameNode.childNodes[0];
					var IdTipoLocalCobranca = nameTextNode.nodeValue;
					
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
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("LogRetorno")[0]; 
					nameTextNode = nameNode.childNodes[0];
					var LogRetorno = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("IdArquivoRetornoTipo")[0]; 
					nameTextNode = nameNode.childNodes[0];
					IdArquivoRetornoTipo = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoArquivoRetornoTipo")[0]; 
					nameTextNode = nameNode.childNodes[0];
					var DescricaoArquivoRetornoTipo = nameTextNode.nodeValue;
					
					switch (Local){
						case "ArquivoRetorno":
							addParmUrl("marArquivoRetorno","IdArquivoRetorno",IdArquivoRetorno);
							addParmUrl("marContasReceber","IdArquivoRetorno",IdArquivoRetorno);
							addParmUrl("marContasReceber","IdLocalCobranca",IdLocalRecebimento);
							
							limpaFormArquivo();
							
							if(DataRetorno != ''){
								DataRetorno	=	dateFormat(DataRetorno);
							}
							
							if(ValorTotal!=""){
								ValorTotal	=	formata_float(Arredonda(ValorTotal,2),2).replace(".",",");
							}
							
							if(ValorTotalTaxa!=""){
								ValorTotalTaxa	=	formata_float(Arredonda(ValorTotalTaxa,2),2).replace(".",",");
							}
							
							if(ValorLiquido!=""){
								ValorLiquido	=	formata_float(Arredonda(ValorLiquido,2),2).replace(".",",");
							}
							
							document.formulario.IdStatus.value 							= IdStatus;
							document.formulario.IdArquivoRetorno.value 					= IdArquivoRetorno;
							document.formulario.IdArquivoRetorno2.value 				= IdArquivoRetorno;
							document.formulario.IdLocalRecebimento.value 				= IdLocalRecebimento;
							document.formulario.EnderecoArquivo.value					= EndArquivo;
							document.formulario.fakeupload.value						= EndArquivo;
							document.formulario.tempEndArquivo.value					= EndArquivo;
							document.formulario.NomeArquivo.value						= NomeArquivo;
							document.formulario.ValorTotal.value						= ValorTotal;
							document.formulario.ValorTotalTaxa.value					= ValorTotalTaxa;
							document.formulario.ValorLiquido.value						= ValorLiquido;
							document.formulario.DataRetorno.value						= DataRetorno;
							document.formulario.QtdRegistro.value						= QtdRegistro;
							document.formulario.FileSize.value							= FileSize;
							document.formulario.NumSeqArquivo.value						= NumSeqArquivo;
							document.formulario.IdTipoLocalCobranca.value				= IdTipoLocalCobranca;
							document.formulario.IdArquivoRetornoTipo.value 				= IdArquivoRetornoTipo;
							document.formulario.DescricaoArquivoRetornoTipo.value 		= DescricaoArquivoRetornoTipo;
							document.formulario.EndArquivo.readOnly						= true;
							document.getElementById('cp_Arquivo').style.color			= '#000000';
							document.getElementById('EndArquivo').style.display			= 'none';
							document.getElementById('bt_procurar').style.display		= 'none';				
							document.getElementById('EnderecoArquivo').style.display	= 'block';
							document.getElementById('cpPosicaoCobranca').style.display	= 'none';
							document.formulario.Visualizar.value 						= '';
							document.formulario.bt_visualizar.value 					= 'Visualizar';
							document.getElementById('cp_Status').innerHTML				= Status;
							document.getElementById('cp_Status').style.color			= Cor;
							document.formulario.LogRetorno.value						= LogRetorno;
							document.formulario.DataCriacao.value 						= dateFormat(DataCriacao);
							document.formulario.LoginCriacao.value 						= LoginCriacao;
							document.formulario.DataProcessamento.value 				= dateFormat(DataProcessamento);
							document.formulario.LoginProcessamento.value				= LoginProcessamento;
							
							switch(document.formulario.IdStatus.value){
								case '2':
									document.formulario.Acao.value 						= 'processar';
									break;
								case '3': 
									document.formulario.Acao.value 						= 'confirmar';
									break;
								default:
									document.formulario.Acao.value 						= 'inserir';
							}
							
							while(document.getElementById('tabelaTitulosRecebidos').rows.length > 2){
								document.getElementById('tabelaTitulosRecebidos').deleteRow(1);
							}
							
							document.getElementById('cp_titulos_recebidos').style.display	=	"none";						
							document.getElementById('cpValorTotal').innerHTML				=	"0,00";						
							document.getElementById('ValorDescTotal').innerHTML				=	"0,00";						
							document.getElementById('ValorMoraMultaTotal').innerHTML		=	"0,00";						
							document.getElementById('ValorRecebidoTotal').innerHTML			=	"0,00";							
							document.getElementById('ValorOutrasDespesasTotal').innerHTML	=	"0,00";
							document.getElementById('tabelaTotal').innerHTML				=	"Total: 0";
							
							mensagens(document.formulario.Erro.value);
							verificaAcao();
							break;
						case "CancelarArquivoRetorno":
							addParmUrl("marCancelarArquivoRetorno","IdArquivoRetorno",IdArquivoRetorno);
							addParmUrl("marContasReceber","IdArquivoRetorno",IdArquivoRetorno);
							addParmUrl("marContasReceber","IdLocalCobranca",IdLocalRecebimento);
							addParmUrl("marContasReceber","filtro_limit",QtdRegistro);
							
							if(DataRetorno != ''){
								DataRetorno	=	dateFormat(DataRetorno);
							}
							
							if(ValorTotal!=""){
								ValorTotal	=	formata_float(Arredonda(ValorTotal,2),2).replace(".",",");
							}
							
							if(ValorTotalTaxa!=""){
								ValorTotalTaxa	=	formata_float(Arredonda(ValorTotalTaxa,2),2).replace(".",",");
							}
							
							if(ValorLiquido!=""){
								ValorLiquido	=	formata_float(Arredonda(ValorLiquido,2),2).replace(".",",");
							}
							
							document.formulario.IdStatus.value 							= IdStatus;
							document.formulario.IdArquivoRetorno.value 					= IdArquivoRetorno;
							document.formulario.IdArquivoRetorno2.value 				= IdArquivoRetorno;
							document.formulario.IdLocalRecebimento.value 				= IdLocalRecebimento;
							document.formulario.EnderecoArquivo.value					= EndArquivo;
							document.formulario.tempEndArquivo.value					= EndArquivo;
							document.formulario.NomeArquivo.value						= NomeArquivo;
							document.formulario.ValorTotal.value						= ValorTotal;
							document.formulario.ValorTotalTaxa.value					= ValorTotalTaxa;
							document.formulario.ValorLiquido.value						= ValorLiquido;
							document.formulario.DataRetorno.value						= DataRetorno;
							document.formulario.QtdRegistro.value						= QtdRegistro;
							document.formulario.FileSize.value							= FileSize;
							document.formulario.NumSeqArquivo.value						= NumSeqArquivo;
							document.formulario.IdTipoLocalCobranca.value				= IdTipoLocalCobranca;
							document.formulario.IdArquivoRetornoTipo.value 				= IdArquivoRetornoTipo;
							document.formulario.DescricaoArquivoRetornoTipo.value	 	= DescricaoArquivoRetornoTipo;
							document.getElementById('cp_Status').innerHTML				= Status;
							document.getElementById('cp_Status').style.color			= Cor;
							document.formulario.EndArquivo.readOnly						= true;
							document.getElementById('cp_Arquivo').style.color			= '#C10000';
							document.getElementById('EndArquivo').style.display			= 'none';
							document.getElementById('bt_procurar').style.display		= 'none';
							document.getElementById('EnderecoArquivo').style.display	= 'block';
							document.formulario.LogRetorno.value						= LogRetorno;
							document.formulario.DataCriacao.value 						= dateFormat(DataCriacao);
							document.formulario.LoginCriacao.value 						= LoginCriacao;
							document.formulario.DataProcessamento.value 				= dateFormat(DataProcessamento);
							document.formulario.LoginProcessamento.value				= LoginProcessamento;
							document.formulario.Acao.value 								= 'processar';

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