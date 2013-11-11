function janela_busca_centro_custo(SubLocal){
	if(SubLocal == undefined){
		SubLocal	=	'';
	}
	janelas('busca_centro_custo.php?SubLocal='+SubLocal,360,283,250,100,'');
}
function busca_centro_custo(IdCentroCusto,Erro,Local,SubLocal){
	if(IdCentroCusto == ''){
		IdCentroCusto = 0;
	}
	if(SubLocal == undefined){
		SubLocal	=	'';
	}
	if(Local == undefined || Local == ''){
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
    
    url = "xml/centro_custo.php?IdCentroCusto="+IdCentroCusto;
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
						case "CentroCusto":
							limpa_form_centro_custo();
							statusInicial();
							break;
						case "CentroCustoRateio":
							if(SubLocal == 'Mantenedor'){
								document.formulario.IdCentroCustoMantenedor.disabled		= false;
								document.formulario.IdCentroCustoMantenedor.value 			= '';
								document.formulario.DescricaoCentroCustoMantenedor.value 	= '';
								
								document.formulario.IdCentroCustoMantenedor.focus();
							}else{
								document.formulario.IdCentroCusto.value 					= '';
								document.formulario.DescricaoCentroCusto.value 				= '';
								document.formulario.IdCentroCustoMantenedor.disabled		= false;
								document.formulario.IdCentroCustoMantenedor.value 			= '';
								document.formulario.DescricaoCentroCustoMantenedor.value 	= '';
								document.formulario.Percentual.value 						= '';
								
								while(document.getElementById('tabelaRateio').rows.length > 2){
									document.getElementById('tabelaRateio').deleteRow(1);
								}
								
								document.formulario.IdCentroCusto.focus();
							}
							break;
						case "ContaPagar":
							if(SubLocal=='Conta'){
								document.formulario.IdCentroCustoConta.value 			= '';
								document.formulario.DescricaoCentroCustoConta.value 	= '';
								
								document.formulario.IdCentroCustoConta.focus();
							}else{
								document.formulario.IdCentroCusto.value 		= '';
								document.formulario.DescricaoCentroCusto.value 	= '';
								
								document.formulario.IdCentroCusto.focus();
							}
							break;
						default:
							document.formulario.IdCentroCusto.value 		= '';
							document.formulario.DescricaoCentroCusto.value 	= '';
							
							document.formulario.IdCentroCusto.focus();
							break;
					}
										
					// Fim de Carregando
					carregando(false);
				}else{
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("IdCentroCusto")[0]; 
					nameTextNode = nameNode.childNodes[0];
					IdCentroCusto = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoCentroCusto")[0]; 
					nameTextNode = nameNode.childNodes[0];
					var DescricaoCentroCusto = nameTextNode.nodeValue;						
					
					switch (Local){
						case "ContaPagar":
							if(SubLocal=='Conta'){
								document.formulario.IdCentroCustoConta.value 			= IdCentroCusto;
								document.formulario.DescricaoCentroCustoConta.value 	= DescricaoCentroCusto;
							
								document.formulario.Percentual.focus();
							}else{
								document.formulario.IdCentroCusto.value 				= IdCentroCusto;
								document.formulario.DescricaoCentroCusto.value 			= DescricaoCentroCusto;
							
								document.formulario.OrigemLancamento.focus();
							}
							break;
						case "CentroCustoRateio":
							if(SubLocal=='Mantenedor'){
								document.formulario.IdCentroCustoMantenedor.disabled		= false;
								document.formulario.IdCentroCustoMantenedor.value 			= IdCentroCusto;
								document.formulario.DescricaoCentroCustoMantenedor.value 	= DescricaoCentroCusto;
							
								document.formulario.Percentual.focus();
							}else{
								document.formulario.IdCentroCusto.value 					= IdCentroCusto;
								document.formulario.DescricaoCentroCusto.value 				= DescricaoCentroCusto;
								document.formulario.IdCentroCustoMantenedor.disabled		= false;
								document.formulario.IdCentroCustoMantenedor.value 			= '';
								document.formulario.DescricaoCentroCustoMantenedor.value 	= '';
								document.formulario.Percentual.value 						= '';
								
								document.formulario.IdCentroCustoMantenedor.focus();
														
								listarCentroCustoRateio(IdCentroCusto,'true');
							}
							break;
						case "CentroCusto":
							nameNode = xmlhttp.responseXML.getElementsByTagName("DataCriacao")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var DataCriacao = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("IdStatus")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var IdStatus = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("LoginCriacao")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var LoginCriacao = nameTextNode.nodeValue;					
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("DataAlteracao")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var DataAlteracao = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("LoginAlteracao")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var LoginAlteracao = nameTextNode.nodeValue;
							
							for(var i=0; i<document.formulario.IdStatus.length; i++){
								if(document.formulario.IdStatus[i].value == IdStatus){
									document.formulario.IdStatus[i].selected = true;
									i = document.formulario.IdStatus.length;
								}							
							}	
							
							document.formulario.IdCentroCusto.value 		= IdCentroCusto;
							document.formulario.DescricaoCentroCusto.value 	= DescricaoCentroCusto;
							document.formulario.IdStatus.value				= IdStatus;
							document.formulario.DataCriacao.value 			= dateFormat(DataCriacao);
							document.formulario.LoginCriacao.value 			= LoginCriacao;
							document.formulario.DataAlteracao.value 		= dateFormat(DataAlteracao);
							document.formulario.LoginAlteracao.value		= LoginAlteracao;
							document.formulario.Acao.value 					= 'alterar';
							break
						default:
							document.formulario.IdCentroCusto.value 		= IdCentroCusto;
							document.formulario.DescricaoCentroCusto.value 	= DescricaoCentroCusto;
							break;
						
					}
						
				}
				if(document.getElementById("quadroBuscaCentroCusto") != null){
					if(document.getElementById("quadroBuscaCentroCusto").style.display == "block"){
						document.getElementById("quadroBuscaCentroCusto").style.display =	"none";
					}
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
function limpa_form_centro_custo(){
	document.formulario.IdCentroCusto.value			= '';
	document.formulario.DescricaoCentroCusto.value 	= '';
	document.formulario.DataCriacao.value 			= '';
	document.formulario.LoginCriacao.value 			= '';
	document.formulario.DataAlteracao.value 		= '';
	document.formulario.LoginAlteracao.value		= '';
	document.formulario.Acao.value 					= 'inserir';
}
