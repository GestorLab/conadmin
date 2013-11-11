function janela_busca_local_cobranca(){
	janelas('../administrativo/busca_local_cobranca.php',360,283,250,100,'');
}

function busca_local_cobranca(IdLocalCobranca, Erro, Local){
	if(IdLocalCobranca == ''){
		IdLocalCobranca = 0;
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
    
   	url = "../administrativo/xml/local_cobranca.php?IdLocalCobranca="+IdLocalCobranca;
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
					
					switch(Local){
						case 'LocalCobrancaParametro':
							document.formulario.IdLocalCobranca.value 				= '';
							document.formulario.DescricaoLocalCobranca.value 		= '';
							document.formulario.AbreviacaoNomeLocalCobranca.value 	= "";
							
							while(document.getElementById('tabelaParametro').rows.length > 2){
								document.getElementById('tabelaParametro').deleteRow(1);
							}
							document.getElementById('tabelaParametroTotal').innerHTML	=	"Total: 0";
							document.formulario.IdLocalCobranca.focus();
							break;
						case 'LocalCobrancaParametroContrato':
							document.formulario.IdLocalCobranca.value 				= '';
							document.formulario.DescricaoLocalCobranca.value 		= '';
							document.formulario.AbreviacaoNomeLocalCobranca.value 	= "";
							
							while(document.getElementById('tabelaParametro').rows.length > 2){
								document.getElementById('tabelaParametro').deleteRow(1);
							}
							document.getElementById('tabelaParametroTotal').innerHTML	=	"Total: 0";
							document.formulario.IdLocalCobranca.focus();
							break;
						case 'ContaEventual':
							document.formulario.ValorDespesaLocalCobranca.value 	= "0,00";
							document.formulario.ValorCobrancaMinima.value 			= "";
							break;
						case 'OrdemServico':
							document.formulario.ValorDespesaLocalCobranca.value 	= "0,00";
							document.formulario.ValorCobrancaMinima.value 			= "";
							break;
						case 'ProcessoFinanceiro':
							document.formulario.IdTipoLocalCobranca.value 			= "";
							break;
						default:
							document.formulario.IdLocalCobranca.value 				= '';
							document.formulario.IdTipoLocalCobranca.value 			= '';
							document.formulario.DescricaoLocalCobranca.value 		= '';
							document.formulario.AbreviacaoNomeLocalCobranca.value 	= "";
							document.formulario.ValorDespesaLocalCobranca.value 	= "";
							document.formulario.IdLocalCobrancaLayout.value 		= "";
							document.formulario.DescricaoLocalCobrancaLayout.value	= "";
							document.formulario.PercentualJurosDiarios.value 		= "";
							document.formulario.PercentualMulta.value			 	= "";
							document.formulario.ValorCobrancaMinima.value 			= "";
							document.formulario.IdArquivoRetornoTipo.value			= "";
							document.formulario.DescricaoArquivoRetornoTipo.value	= "";
							document.formulario.IdArquivoRemessaTipo.value			= "";
							document.formulario.DescricaoArquivoRemessaTipo.value	= "";
							document.formulario.DataCriacao.value 					= "";
							document.formulario.LoginCriacao.value 					= "";
							document.formulario.DataAlteracao.value 				= "";
							document.formulario.LoginAlteracao.value				= "";
							document.formulario.Acao.value 							= 'inserir';
							
							addParmUrl("marLocalCobrancaParametro","IdLocalCobranca","");
							addParmUrl("marLocalCobrancaParametroNovo","IdLocalCobranca","");
							addParmUrl("marLocalCobrancaParametroContrato","IdLocalCobranca","");
							addParmUrl("marLocalCobrancaParametroContratoNovo","IdLocalCobranca","");
							
							document.formulario.IdLocalCobranca.focus();			
							verificaAcao();
							status_inicial();
							break;
					}
				}else{
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("IdLocalCobranca")[0]; 
					nameTextNode = nameNode.childNodes[0];
					IdLocalCobranca = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("IdTipoLocalCobranca")[0]; 
					nameTextNode = nameNode.childNodes[0];
					var IdTipoLocalCobranca = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoLocalCobranca")[0]; 
					nameTextNode = nameNode.childNodes[0];
					var DescricaoLocalCobranca = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("AbreviacaoNomeLocalCobranca")[0]; 
					nameTextNode = nameNode.childNodes[0];
					var AbreviacaoNomeLocalCobranca = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("ValorDespesaLocalCobranca")[0]; 
					nameTextNode = nameNode.childNodes[0];
					var ValorDespesaLocalCobranca = nameTextNode.nodeValue;					
						
					switch (Local){
						case 'LocalCobrancaParametro':
							document.formulario.IdLocalCobranca.value				= IdLocalCobranca;
							document.formulario.DescricaoLocalCobranca.value 		= DescricaoLocalCobranca;
							document.formulario.AbreviacaoNomeLocalCobranca.value 	= AbreviacaoNomeLocalCobranca;
							listarParametro(IdLocalCobranca,false);
							break;
						case 'LocalCobrancaParametroContrato':
							document.formulario.IdLocalCobranca.value				= IdLocalCobranca;
							document.formulario.DescricaoLocalCobranca.value 		= DescricaoLocalCobranca;
							document.formulario.AbreviacaoNomeLocalCobranca.value 	= AbreviacaoNomeLocalCobranca;
							listarParametro(IdLocalCobranca,false);
							break;
						case 'ContaEventual':
							document.formulario.ValorDespesaLocalCobranca.value 	= formata_float(Arredonda(ValorDespesaLocalCobranca,2),2).replace(".",",");
							busca_valor_minimo(IdLocalCobranca);
							break;
						case 'OrdemServico':
							document.formulario.ValorDespesaLocalCobranca.value 	= formata_float(Arredonda(ValorDespesaLocalCobranca,2),2).replace(".",",");
							busca_valor_minimo(IdLocalCobranca);
							break;
						case 'ProcessoFinanceiro':
							document.formulario.IdTipoLocalCobranca.value 			= IdTipoLocalCobranca;
							break;
						case 'ArquivoRetorno':
							document.formulario.IdTipoLocalCobranca.value 			= IdTipoLocalCobranca;
							break;
						default:
							nameNode = xmlhttp.responseXML.getElementsByTagName("IdLocalCobrancaLayout")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var IdLocalCobrancaLayout = nameTextNode.nodeValue;
						
							nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoLocalCobrancaLayout")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var DescricaoLocalCobrancaLayout = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("PercentualJurosDiarios")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var PercentualJurosDiarios = nameTextNode.nodeValue;
						
							nameNode = xmlhttp.responseXML.getElementsByTagName("PercentualMulta")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var PercentualMulta = nameTextNode.nodeValue;					
						
							nameNode = xmlhttp.responseXML.getElementsByTagName("ValorCobrancaMinima")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var ValorCobrancaMinima = nameTextNode.nodeValue;
						
							nameNode = xmlhttp.responseXML.getElementsByTagName("IdArquivoRetornoTipo")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var IdArquivoRetornoTipo = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoArquivoRetornoTipo")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var DescricaoArquivoRetornoTipo = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("IdArquivoRemessaTipo")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var IdArquivoRemessaTipo = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoArquivoRemessaTipo")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var DescricaoArquivoRemessaTipo = nameTextNode.nodeValue;
							
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
							
							document.formulario.IdLocalCobranca.value				= IdLocalCobranca;
							document.formulario.IdTipoLocalCobranca.value			= IdTipoLocalCobranca;
							document.formulario.DescricaoLocalCobranca.value 		= DescricaoLocalCobranca;
							document.formulario.AbreviacaoNomeLocalCobranca.value 	= AbreviacaoNomeLocalCobranca;
							document.formulario.ValorDespesaLocalCobranca.value 	= formata_float(Arredonda(ValorDespesaLocalCobranca,2),2).replace(".",",");
							document.formulario.IdLocalCobrancaLayout.value 		= IdLocalCobrancaLayout;
							document.formulario.DescricaoLocalCobrancaLayout.value	= DescricaoLocalCobrancaLayout;
							document.formulario.PercentualJurosDiarios.value 		= formata_float(Arredonda(PercentualJurosDiarios,3),3).replace(".",",");
							document.formulario.PercentualMulta.value 				= formata_float(Arredonda(PercentualMulta,3),3).replace(".",",");
							document.formulario.ValorCobrancaMinima.value 			= formata_float(Arredonda(ValorCobrancaMinima,2),2).replace(".",",");
							document.formulario.IdArquivoRetornoTipo.value			= IdArquivoRetornoTipo;
							document.formulario.DescricaoArquivoRetornoTipo.value	= DescricaoArquivoRetornoTipo;
							document.formulario.IdArquivoRemessaTipo.value			= IdArquivoRemessaTipo;
							document.formulario.DescricaoArquivoRemessaTipo.value	= DescricaoArquivoRemessaTipo;
							document.formulario.DataCriacao.value 					= dateFormat(DataCriacao);
							document.formulario.LoginCriacao.value 					= LoginCriacao;
							document.formulario.DataAlteracao.value 				= dateFormat(DataAlteracao);
							document.formulario.LoginAlteracao.value				= LoginAlteracao;
							document.formulario.Acao.value 							= 'alterar';
							
							addParmUrl("marLocalCobrancaParametro","IdLocalCobranca",IdLocalCobranca);
							addParmUrl("marLocalCobrancaParametroNovo","IdLocalCobranca",IdLocalCobranca);
							addParmUrl("marLocalCobrancaParametroContrato","IdLocalCobranca",IdLocalCobranca);
							addParmUrl("marLocalCobrancaParametroContratoNovo","IdLocalCobranca",IdLocalCobranca);
							verificaAcao();
							break;
					}
				}
				if(document.getElementById("quadroBuscaLocalCobranca") != null){
					if(document.getElementById("quadroBuscaLocalCobranca").style.display	==	"block"){
						document.getElementById("quadroBuscaLocalCobranca").style.display = "none";
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
