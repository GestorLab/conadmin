function busca_servico_valor(IdServico,Erro,Local,DataInicioCond){
	if(IdServico == ''){
		IdServico = 0;
	}
	if(DataInicioCond == '' || DataInicioCond == undefined){
		DataInicioCond = 0;
	}else{
		var tam		=	document.getElementById('tabelaValor').rows.length;	
		var i;
		for(i=0; i<tam; i++){
			if(document.getElementById('tabelaValor').rows[i].accessKey == DataInicioCond){
				document.getElementById('tabelaValor').rows[i].style.backgroundColor = "#A0C4EA";
			}
			else{
				if(i%2 == 0 && i!=0 && i!=(tam-1)){
					document.getElementById('tabelaValor').rows[i].style.backgroundColor = "#E2E7ED";
				}else if(i%2 != 0 && i!=0 && i!=(tam-1)){
					document.getElementById('tabelaValor').rows[i].style.backgroundColor = "#FFFFFF";
				}
			}
		}
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
    url = "xml/servico_valor.php?IdServico="+IdServico+"&DataInicio="+DataInicioCond;
	
	xmlhttp.open("GET", url,true);
	xmlhttp.onreadystatechange = function(){ 

		// Carregando...
		carregando(true);

		if(xmlhttp.readyState == 4){ 
			if(xmlhttp.status == 200){

				if(Erro != false){
					document.formulario.Erro.value = 0;
				//	verificaErro();
				}
				if(xmlhttp.responseText == 'false'){		
						document.formulario.DataTermino.value				=	"";
						document.formulario.DescricaoServicoValor.value		=	"";
						document.formulario.Valor.value						=	"";
						document.formulario.ValorRepasseTerceiro.value		=	"";
						document.formulario.MultaFidelidade.value			=	"";
						document.formulario.DataCriacao.value				= 	"";
						document.formulario.LoginCriacao.value				= 	"";
						document.formulario.DataAlteracao.value				= 	"";
						document.formulario.LoginAlteracao.value			= 	"";
						
						document.getElementById("cpValorMulta").style.color	=	"#000";
						
						
						document.formulario.Acao.value						= 'inserir';
						
						addParmUrl("marServicoValorNovo","DataInicio","");
						
						status_inicial();
						document.getElementById('tabelahelpText2').style.display	=	'none';
						document.formulario.DataInicio.focus();
					
					// Fim de Carregando
					carregando(false);
				}else{
					var DadosValor, DataInicio, DataTermino, Valor, DescricaoServicoValor,DataCriacao,LoginCriacao,DataAlteracao,LoginAlteracao;
					for(var i=0; i<xmlhttp.responseXML.getElementsByTagName("DataInicio").length; i++){
			
						nameNode = xmlhttp.responseXML.getElementsByTagName("DataInicio")[i]; 
						nameTextNode = nameNode.childNodes[0];
						DataInicio = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("DataTermino")[i]; 
						nameTextNode = nameNode.childNodes[0];
						DataTermino = nameTextNode.nodeValue;	
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoServicoValor")[i]; 
						nameTextNode = nameNode.childNodes[0];
						DescricaoServicoValor = nameTextNode.nodeValue;					
				
						nameNode = xmlhttp.responseXML.getElementsByTagName("Valor")[i]; 
						nameTextNode = nameNode.childNodes[0];
						Valor = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("ValorRepasseTerceiro")[i]; 
						nameTextNode = nameNode.childNodes[0];
						ValorRepasseTerceiro = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("MultaFidelidade")[i]; 
						nameTextNode = nameNode.childNodes[0];
						MultaFidelidade = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("DataCriacao")[i]; 
						nameTextNode = nameNode.childNodes[0];
						DataCriacao = nameTextNode.nodeValue;
					
						nameNode = xmlhttp.responseXML.getElementsByTagName("LoginCriacao")[i]; 
						nameTextNode = nameNode.childNodes[0];
						LoginCriacao = nameTextNode.nodeValue;					
					
						nameNode = xmlhttp.responseXML.getElementsByTagName("DataAlteracao")[i]; 
						nameTextNode = nameNode.childNodes[0];
						DataAlteracao = nameTextNode.nodeValue;
					
						nameNode = xmlhttp.responseXML.getElementsByTagName("LoginAlteracao")[i]; 
						nameTextNode = nameNode.childNodes[0];
						LoginAlteracao = nameTextNode.nodeValue;
						
						if(Valor	==	'')					Valor = 0;
						if(ValorRepasseTerceiro	==	'')		ValorRepasseTerceiro = 0;
						if(MultaFidelidade	==	'')			MultaFidelidade = 0;
						
						addParmUrl("marServicoValor","DataInicio",DataInicio);
												
						if(DataInicioCond != ''){
							document.formulario.DataInicio.value			= dateFormat(DataInicio);
							document.formulario.DataTermino.value 			= dateFormat(DataTermino);
							document.formulario.DescricaoServicoValor.value = DescricaoServicoValor;
							document.formulario.Valor.value					= formata_float(Valor).replace(".",",");
							document.formulario.ValorAnterior.value			= formata_float(Valor).replace(".",",");
							document.formulario.ValorRepasseTerceiro.value	= formata_float(ValorRepasseTerceiro).replace(".",",");
							document.formulario.MultaFidelidade.value		= formata_float(MultaFidelidade).replace(".",",");
							document.formulario.DataCriacao.value			= dateFormat(DataCriacao);
							document.formulario.LoginCriacao.value			= LoginCriacao;
							document.formulario.DataAlteracao.value			= dateFormat(DataAlteracao);
							document.formulario.LoginAlteracao.value		= LoginAlteracao;
							
							if(document.formulario.maxQtdMesesFidelidade.value <= 0){
								document.getElementById("cpValorMulta").style.color	=	"#000";
							}else{
								document.getElementById("cpValorMulta").style.color	=	"#C10000";
							}
							
							document.formulario.Acao.value					= 'alterar';
						}
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


