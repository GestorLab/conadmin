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
							limpaFormArquivo();
							
							document.formulario.Acao.value 		= 'inserir';
							
							while(document.getElementById('tabelaTitulosRecebidos').rows.length > 2){
								document.getElementById('tabelaTitulosRecebidos').deleteRow(1);
							}
							
							document.getElementById('ValorDescTotal').innerHTML				=	"0,00";	
							document.getElementById('ValorOutrasDespesasTotal').innerHTML	=	"0,00";		
								document.getElementById('ValorRecebidoTotal').innerHTML		=	"0,00";	
							document.getElementById('ValorMoraMultaTotal').innerHTML		=	"0,00";	
							document.getElementById('cpValorTotal').innerHTML				=	"0,00";	
							document.getElementById('tabelaTotal').innerHTML				=	"Total: 0";	
							document.getElementById('cpPosicaoCobranca').style.display		=	'none';
							
							busca_arquivo_retorno_tipo('','',false);
							
							document.formulario.IdLocalRecebimento.focus();
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
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("Processado")[0]; 
					nameTextNode = nameNode.childNodes[0];
					var Processado = nameTextNode.nodeValue;
					
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
					
					switch (Local){
						case "ArquivoRetorno":
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
							
							
							document.formulario.IdArquivoRetorno.value 		= IdArquivoRetorno;
							document.formulario.IdArquivoRetorno2.value 	= IdArquivoRetorno;
							document.formulario.IdLocalRecebimento.value 	= IdLocalRecebimento;
							document.formulario.EnderecoArquivo.value		= EndArquivo;
							document.formulario.tempEndArquivo.value		= EndArquivo;
							document.formulario.NomeArquivo.value			= NomeArquivo;
							document.formulario.ValorTotal.value			= ValorTotal;
							document.formulario.ValorTotalTaxa.value		= ValorTotalTaxa;
							document.formulario.ValorLiquido.value			= ValorLiquido;
							document.formulario.Processado.value			= Processado;
							document.formulario.DataRetorno.value			= DataRetorno;
							document.formulario.QtdRegistro.value			= QtdRegistro;
							document.formulario.FileSize.value				= FileSize;
							document.formulario.NumSeqArquivo.value			= NumSeqArquivo;
							document.formulario.IdTipoLocalCobranca.value	= IdTipoLocalCobranca;
							
							document.formulario.EndArquivo.readOnly						= true;
							document.getElementById('cp_Arquivo').style.color			= '#C10000';
							document.getElementById('EndArquivo').style.display			= 'none';
							document.getElementById('EnderecoArquivo').style.display	= 'block';
							document.getElementById('cpPosicaoCobranca').style.display	=	'none';
							
							document.formulario.LogRetorno.value			= LogRetorno;
							document.formulario.DataCriacao.value 			= dateFormat(DataCriacao);
							document.formulario.LoginCriacao.value 			= LoginCriacao;
							document.formulario.DataProcessamento.value 	= dateFormat(DataProcessamento);
							document.formulario.LoginProcessamento.value	= LoginProcessamento;
							document.formulario.Acao.value 					= 'processar';
							
							busca_arquivo_retorno_tipo(IdLocalRecebimento,'',false);
							verificaAcao();

							if(Processado == 1){
								document.formulario.bt_processar.disabled = true;
								document.formulario.bt_excluir.disabled   = true;
							}else{
								document.formulario.bt_processar.disabled = false;
								document.formulario.bt_excluir.disabled   = false;
							}
							
							listarContaReceberRecebimento(IdArquivoRetorno,IdLocalRecebimento,false);
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
function limpaFormArquivo(){
	document.formulario.IdArquivoRetorno.value 				= '';
	document.formulario.IdArquivoRetorno2.value 			= '';
	document.formulario.EndArquivo.value					= '';
	document.formulario.NomeArquivo.value		 			= '';
	document.formulario.ValorTotal.value		 			= '';
	document.formulario.Processado.value		 			= '';
	document.formulario.DataRetorno.value		 			= '';
	document.formulario.QtdRegistro.value					= '';
	document.formulario.FileSize.value						= '';
	document.formulario.DataCriacao.value 					= '';
	document.formulario.LoginCriacao.value 					= '';
	document.formulario.DataProcessamento.value				= '';
	document.formulario.LoginProcessamento.value			= '';
	document.formulario.NumSeqArquivo.value					= '';
	document.formulario.LogRetorno.value					= '';
	document.formulario.IdArquivoRetornoTipo.value			= '';
	document.formulario.DescricaoArquivoRetornoTipo.value	= '';
	document.formulario.IdTipoLocalCobranca.value			= '';
	
	document.formulario.EndArquivo.readOnly						= false;
	document.getElementById('cp_Arquivo').style.color			= '#C10000';
	document.getElementById('EndArquivo').style.display			= 'block';
	document.getElementById('EnderecoArquivo').style.display	= 'none';
}
