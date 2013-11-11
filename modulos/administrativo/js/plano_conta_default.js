function janela_busca_plano_conta(Tipo,AcessoRapido){
	if(Tipo == undefined){
		var Tipo = '';
	}
	if(AcessoRapido == undefined){
		var AcessoRapido = '';
	}
	janelas('busca_plano_conta.php?Tipo='+Tipo+'&AcessoRapido='+AcessoRapido,500,300,250,100,'');
}
function busca_plano_conta(IdPlanoConta, Erro, Local, AcessoRapido, Auxiliar){
	if(IdPlanoConta == '' || IdPlanoConta == undefined){
		IdPlanoConta = 0;
	}
	if(AcessoRapido == '' || AcessoRapido == undefined){
		AcessoRapido  = '';
	}
	if(Auxiliar == '' || Auxiliar == undefined){
		Auxiliar  = '';
	}
	
	var nameNode, nameTextNode, url, fecha=true;
	
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
    url = "xml/plano_conta.php?IdPlanoConta="+IdPlanoConta+"&AcessoRapido="+AcessoRapido+"&Auxiliar="+Auxiliar;		
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
						case "PlanoConta":
							document.formulario.DescricaoPlanoConta.value 	= '';
							document.formulario.IdAcessoRapido.value 		= '';
							document.formulario.Tipo.value 					= '';
							document.formulario.AcaoContabil.value			= '';
							document.formulario.DataCriacao.value 			= '';
							document.formulario.LoginCriacao.value 			= '';
							document.formulario.DataAlteracao.value 		= '';
							document.formulario.LoginAlteracao.value		= '';
							document.formulario.Acao.value 					= 'inserir';
							break;
						default:
							document.formulario.IdPlanoConta.value 			= '';
							document.formulario.DescricaoPlanoConta.value 	= '';
							
							document.formulario.IdPlanoConta.focus();
							
					}
					
					// Fim de Carregando
					carregando(false);
				}else{					
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("IdPlanoConta")[0]; 
					nameTextNode = nameNode.childNodes[0];
					IdPlanoConta = nameTextNode.nodeValue;

					nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoPlanoConta")[0]; 
					nameTextNode = nameNode.childNodes[0];
					var DescricaoPlanoConta = nameTextNode.nodeValue;
										
					nameNode = xmlhttp.responseXML.getElementsByTagName("Tipo")[0]; 
					nameTextNode = nameNode.childNodes[0];
					var Tipo = nameTextNode.nodeValue;
					
					switch (Local){
						case "Servico":							
							/*if(TipoObrigatorio != undefined && TipoObrigatorio != ''){
								if(TipoObrigatorio != Tipo.substr(0,1)){
									IdPlanoConta = '';
									DescricaoPlanoConta = '';
									if(window.janela == undefined){
										document.formulario.IdPlanoContaAcRapido.focus();
									}
									fecha = false;								
								}
								if(AcessoRapido == 'S' && IdAcessoRapido !=''){
									document.formulario.IdPlanoContaAcRapido.value 		= IdAcessoRapido;
									document.formulario.PlanoContaAcessoRapido.checked  = true;
									document.formulario.PlanoContaAcessoRapido.value    = 'S';	
								}else{
									document.formulario.IdPlanoContaAcRapido.value 		= IdPlanoConta;
									document.formulario.PlanoContaAcessoRapido.checked  = false;
									document.formulario.PlanoContaAcessoRapido.value    = 'N';	
								}*/
								document.formulario.IdPlanoConta.value				= IdPlanoConta;
								document.formulario.DescricaoPlanoConta.value 		= DescricaoPlanoConta;
						//	}
							break;
						case "ContaPagar":							
							if(Tipo == 1){
								document.formulario.IdPlanoConta.value				= IdPlanoConta;
								document.formulario.DescricaoPlanoConta.value 		= DescricaoPlanoConta;
								
								document.formulario.Descricao.focus();
							}else{
								document.formulario.IdPlanoConta.value				= '';
								document.formulario.DescricaoPlanoConta.value 		= '';
								
								document.formulario.IdPlanoConta.focus();
							}
							break;
						default:
							document.formulario.IdPlanoConta.value			= IdPlanoConta;
							document.formulario.DescricaoPlanoConta.value 	= DescricaoPlanoConta;
							
							if(document.formulario.Tipo != undefined){
								
								nameNode = xmlhttp.responseXML.getElementsByTagName("IdAcessoRapido")[0]; 
								nameTextNode = nameNode.childNodes[0];
								var IdAcessoRapido = nameTextNode.nodeValue;
																
								nameNode = xmlhttp.responseXML.getElementsByTagName("AcaoContabil")[0]; 
								nameTextNode = nameNode.childNodes[0];
								var AcaoContabil = nameTextNode.nodeValue;
								
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
								
								document.formulario.IdAcessoRapido.value 	= IdAcessoRapido;
								document.formulario.Tipo.value 				= Tipo;
								document.formulario.AcaoContabil.value 		= AcaoContabil;
								document.formulario.DataCriacao.value 		= dateFormat(DataCriacao);
								document.formulario.LoginCriacao.value 		= LoginCriacao;
								document.formulario.DataAlteracao.value 	= dateFormat(DataAlteracao);
								document.formulario.LoginAlteracao.value	= LoginAlteracao;
								document.formulario.Acao.value				= 'alterar';
							}
					}
					
					// Fim de Carregando
					carregando(false);
				}
				if(document.getElementById("quadroBuscaPlanoConta") != null){
					if(document.getElementById("quadroBuscaPlanoConta").style.display == "block" && fecha == true){
						document.getElementById("quadroBuscaPlanoConta").style.display =	"none";
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
