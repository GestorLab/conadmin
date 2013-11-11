function janela_busca_agente(){
	janelas('busca_agente.php',530,350,250,100,'');
}
function busca_agente(IdAgenteAutorizado,Erro,Local,ListarCampo){
	if(IdAgenteAutorizado == '' || IdAgenteAutorizado == undefined){
		IdAgenteAutorizado = 0;
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

   	url = "xml/agente.php?IdAgenteAutorizado="+IdAgenteAutorizado;
   	
	if(Local == 'Contrato' || Local == 'ContratoServico'){
		url	+=	'&IdStatus=1';
	}   
	   
	xmlhttp.open("GET", url,true);

   	var IdAgente = IdAgenteAutorizado;

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
						case 'AgenteAutorizado':
							
							document.formulario.IdAgenteAutorizado.value	=	"";	
							document.formulario.Nome.value					=	"";
							document.formulario.IdStatus.value				= 	"";
							document.formulario.IdGrupoPessoa.value			=	"";
							document.formulario.DescricaoGrupoPessoa.value	= 	"";
							document.formulario.IdLocalCobranca.value		= 	"";
							document.formulario.DataCriacao.value 			= 	"";
							document.formulario.LoginCriacao.value 			= 	"";
							document.formulario.DataAlteracao.value 		= 	"";
							document.formulario.LoginAlteracao.value		= 	"";
							document.formulario.Acao.value 					= 	'inserir';
							
							addParmUrl("marAgenteAutorizado","IdAgenteAutorizado",'');
							addParmUrl("marAgenteAutorizadoComissionamento","IdAgenteAutorizado",'');
							addParmUrl("marAgenteAutorizadoComissionamentoNovo","IdAgenteAutorizado",'');
							addParmUrl("marCarteira","IdAgenteAutorizado",'');
							addParmUrl("marCarteiraNovo","IdAgenteAutorizado",'');
							
							busca_pessoa(IdAgente,false,Local);
							status_inicial();
							verificaAcao();
							break;
						case 'Carteira':
							addParmUrl("marAgenteAutorizado","IdAgenteAutorizado",'');
							addParmUrl("marAgenteAutorizadoComissionamento","IdAgenteAutorizado",'');
							addParmUrl("marAgenteAutorizadoComissionamentoNovo","IdAgenteAutorizado",'');
							addParmUrl("marCarteira","IdAgenteAutorizado",'');
							addParmUrl("marCarteiraNovo","IdAgenteAutorizado",'');
							
							document.formulario.IdAgenteAutorizado.value	=	"";	
							document.formulario.NomeAgenteAutorizado.value	=	"";
							document.formulario.IdCarteira.value			=	"";	
							document.formulario.Nome.value					=	"";						
							document.formulario.DataCriacao.value 			= 	'';
							document.formulario.LoginCriacao.value 			= 	'';
							document.formulario.DataAlteracao.value 		= 	'';
							document.formulario.LoginAlteracao.value		= 	'';
							document.formulario.Acao.value 					= 	'inserir';
							
							document.formulario.IdAgenteAutorizado.focus();
							
							verificaAcao();
							break;
						case 'OrdemServico':
							document.formulario.IdAgenteAutorizado.value	=	"";
							document.formulario.NomeAgenteAutorizado.value	=	"";
							document.formulario.IdCarteira.disabled			= 	false;
							
							while(document.formulario.IdCarteira.options.length > 0){
								document.formulario.IdCarteira.options[0] = null;
							}
							
							document.formulario.IdAgenteAutorizado.focus();
							break;
						case 'ContratoServico':
							document.formulario.IdAgenteAutorizado.value	=	"";
							document.formulario.NomeAgenteAutorizado.value	=	"";
							
							while(document.formulario.IdCarteira.options.length > 0){
								document.formulario.IdCarteira.options[0] = null;
							}
							
							document.formulario.IdAgenteAutorizado.focus();
							break;
						case 'LoteRepasse':
							document.formulario.IdAgenteAutorizado.value	=	"";
							document.formulario.NomeAgenteAutorizado.value	=	"";
							
							listar_carteira(0);
							document.formulario.IdAgenteAutorizado.focus();
							break;
						case 'ProcessoFinanceiro':
							if(RazaoSocial == ''){
								RazaoSocial = Nome;
							}							
							document.formulario.IdAgenteAutorizado.value	=	"";
							document.formulario.NomeAgenteAutorizado.value	=	"";
							break;	
						case "AdicionarProcessoFinanceiro":
							document.formulario.IdAgenteAutorizado.value 	= "";
							document.formulario.NomeAgenteAutorizado.value 	= "";							
							document.formulario.IdAgenteAutorizado.focus();
							break;	
						default: //AgenteAutorizadoComissao - CarteiraComissao
							document.formulario.IdAgenteAutorizado.value	=	"";
							document.formulario.Nome.value					=	"";							
							document.formulario.IdAgenteAutorizado.focus();
					}
					
					
					// Fim de Carregando
					carregando(false);
				}else{
					nameNode = xmlhttp.responseXML.getElementsByTagName("IdAgenteAutorizado")[0]; 
					nameTextNode = nameNode.childNodes[0];
					var IdAgenteAutorizado = nameTextNode.nodeValue;	
				
					nameNode = xmlhttp.responseXML.getElementsByTagName("RazaoSocial")[0]; 
					nameTextNode = nameNode.childNodes[0];
					var RazaoSocial = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("Nome")[0]; 
					nameTextNode = nameNode.childNodes[0];
					var Nome = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("IdStatus")[0]; 
					nameTextNode = nameNode.childNodes[0];
					var IdStatus = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("IdGrupoPessoa")[0]; 
					nameTextNode = nameNode.childNodes[0];
					var IdGrupoPessoa = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoGrupoPessoa")[0]; 
					nameTextNode = nameNode.childNodes[0];
					var DescricaoGrupoPessoa = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("Restringir")[0]; 
					nameTextNode = nameNode.childNodes[0];
					var Restringir = nameTextNode.nodeValue;
					
					switch(Local){
						case 'AgenteAutorizado':
							
							addParmUrl("marAgenteAutorizado","IdAgenteAutorizado",IdAgenteAutorizado);
							addParmUrl("marAgenteAutorizadoComissionamento","IdAgenteAutorizado",IdAgenteAutorizado);
							addParmUrl("marAgenteAutorizadoComissionamentoNovo","IdAgenteAutorizado",IdAgenteAutorizado);
							addParmUrl("marCarteira","IdAgenteAutorizado",IdAgenteAutorizado);
							addParmUrl("marCarteiraNovo","IdAgenteAutorizado",IdAgenteAutorizado);
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("IdLocalCobranca")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var IdLocalCobranca = nameTextNode.nodeValue;	
													
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
							
							document.formulario.IdAgenteAutorizado.value	=	IdAgenteAutorizado;	
							document.formulario.Nome.value					=	Nome;			
							document.formulario.IdStatus.value				=	IdStatus;		
							document.formulario.IdGrupoPessoa.value			=	IdGrupoPessoa;			
							document.formulario.DescricaoGrupoPessoa.value	=	DescricaoGrupoPessoa;
							document.formulario.IdLocalCobranca.value		= 	IdLocalCobranca;	
							document.formulario.Restringir.value			= 	Restringir;								
							document.formulario.DataCriacao.value 			= 	dateFormat(DataCriacao);
							document.formulario.LoginCriacao.value 			= 	LoginCriacao;
							document.formulario.DataAlteracao.value 		= 	dateFormat(DataAlteracao);
							document.formulario.LoginAlteracao.value		= 	LoginAlteracao;
							document.formulario.Acao.value 					= 	'alterar';
							verificaAcao();
							break;
						case 'Carteira':
							
							addParmUrl("marAgenteAutorizado","IdAgenteAutorizado",IdAgenteAutorizado);
							addParmUrl("marAgenteAutorizadoComissionamento","IdAgenteAutorizado",IdAgenteAutorizado);
							addParmUrl("marAgenteAutorizadoComissionamentoNovo","IdAgenteAutorizado",IdAgenteAutorizado);
							addParmUrl("marCarteira","IdAgenteAutorizado",IdAgenteAutorizado);
							addParmUrl("marCarteiraNovo","IdAgenteAutorizado",IdAgenteAutorizado);
							
							if(RazaoSocial == ""){
								RazaoSocial = Nome;
							} 
													
							document.formulario.IdAgenteAutorizado.value	=	IdAgenteAutorizado;	
							document.formulario.NomeAgenteAutorizado.value	=	RazaoSocial;								
							document.formulario.IdCarteira.value 			= 	'';
							document.formulario.Nome.value 					= 	'';							
							document.formulario.DataCriacao.value 			= 	'';
							document.formulario.LoginCriacao.value 			= 	'';
							document.formulario.DataAlteracao.value 		= 	'';
							document.formulario.LoginAlteracao.value		= 	'';
							document.formulario.Acao.value 					= 	'inserir';
							
							document.formulario.IdCarteira.focus();
							verificaAcao();
							break;
						case 'OrdemServico':
							if(RazaoSocial == ''){
								RazaoSocial = Nome;
							}
						
							document.formulario.IdAgenteAutorizado.value	=	IdAgenteAutorizado;
							document.formulario.NomeAgenteAutorizado.value	=	RazaoSocial;

							listar_carteira(IdAgenteAutorizado);
							break;
						case 'ContratoServico':
							if(RazaoSocial == ''){
								RazaoSocial = Nome;
							}
							
							document.formulario.IdAgenteAutorizado.value	=	IdAgenteAutorizado;
							document.formulario.NomeAgenteAutorizado.value	=	RazaoSocial;
							
							listar_carteira(IdAgenteAutorizado);
							break;
						case 'LoteRepasse':
							if(RazaoSocial == ''){
								RazaoSocial = Nome;
							}
							
							document.formulario.IdAgenteAutorizado.value	=	IdAgenteAutorizado;
							document.formulario.NomeAgenteAutorizado.value	=	RazaoSocial;
							
							listar_carteira(IdAgenteAutorizado);
							break;
						case 'ProcessoFinanceiro':
							if(RazaoSocial == ''){
								RazaoSocial = Nome;
							}							
							document.formulario.IdAgenteAutorizado.value	=	IdAgenteAutorizado;
							document.formulario.NomeAgenteAutorizado.value	=	RazaoSocial;
							break;	
							
						case 'AdicionarProcessoFinanceiro': // add
		
							nameNode = xmlhttp.responseXML.getElementsByTagName("Status")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var Status = nameTextNode.nodeValue;
																	
							var cont = 0; ii='';
							if(RazaoSocial == ''){
								RazaoSocial = Nome;
							}	
							if(ListarCampo == '' || ListarCampo == undefined){							
								if(document.formulario.Filtro_IdAgenteAutorizado.value == ''){
									document.formulario.Filtro_IdAgenteAutorizado.value = IdAgenteAutorizado;
									ii = 0;
								}else{
									var tempFiltro	=	document.formulario.Filtro_IdAgenteAutorizado.value.split(',');
										
									ii=0; 
									while(tempFiltro[ii] != undefined){
										if(tempFiltro[ii] != IdAgenteAutorizado){
											cont++;		
										}
										ii++;
									}
									if(ii == cont){
										document.formulario.Filtro_IdAgenteAutorizado.value = document.formulario.Filtro_IdAgenteAutorizado.value + "," + IdAgenteAutorizado;
									}
								}
							}else{
								ii=0;
							}
							if(ii == cont){
								var tam, linha, c0, c1, c2, c3;
								
								tam 	= document.getElementById('tabelaAgente').rows.length;
								linha	= document.getElementById('tabelaAgente').insertRow(tam-1);
								
								if(tam%2 != 0){
									linha.style.backgroundColor = "#E2E7ED";
								}
								
								linha.accessKey 	= IdAgenteAutorizado; 
								
								c0	= linha.insertCell(0);	
								c1	= linha.insertCell(1);	
								c2	= linha.insertCell(2);	
								c3	= linha.insertCell(3);
								
								var linkIni = "<a href='cadastro_agente_autorizado.php?IdAgenteAutorizado="+IdAgenteAutorizado+"'>";
								var linkFim = "</a>";
								
								c0.innerHTML = linkIni + IdAgenteAutorizado + linkFim;
								c0.style.padding =	"0 0 0 5px";
								
								c1.innerHTML = linkIni + RazaoSocial.substr(0,30); + linkFim;
								
								c2.innerHTML = linkIni + Status.substr(0,30) + linkFim;
																
								if(document.formulario.IdStatus.value == 1){
									c3.innerHTML = "<img src='../../img/estrutura_sistema/ico_del.gif' alt='Excluir?' onClick=\"remover_filtro_agente("+IdAgenteAutorizado+")\"></tr>";
								}else{
									c3.innerHTML = "<img src='../../img/estrutura_sistema/ico_del_c.gif' alt='Excluir?'\">";
								}
								c3.style.textAlign = "center";
								c3.style.cursor = "pointer";
								
								if(document.formulario.IdProcessoFinanceiro.value == ''){
									document.getElementById('totaltabelaAgente').innerHTML	=	'Total: '+(ii+1);
								}else{
									if(document.formulario.Erro.value != ''){
										scrollWindow('bottom');
									}
								}
							}
							document.formulario.IdAgenteAutorizado.value 			= "";
							document.formulario.NomeAgenteAutorizado.value 			= "";
							break;
						default:
							if(RazaoSocial == ""){
								RazaoSocial = Nome;
							}
						
							document.formulario.IdAgenteAutorizado.value	=	IdAgenteAutorizado;
							document.formulario.Nome.value					=	RazaoSocial;
					}
				}	
				if(document.getElementById("quadroBuscaAgente") != null){
					if(document.getElementById("quadroBuscaAgente").style.display == "block"){
						document.getElementById("quadroBuscaAgente").style.display =	"none";
					}
				}
				if(document.getElementById("quadroBuscaPessoa") != null){
					if(document.getElementById("quadroBuscaPessoa").style.display == "block"){
						document.getElementById("quadroBuscaPessoa").style.display =	"none";
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