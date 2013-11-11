function janela_busca_grupo_pessoa(){
	janelas('../administrativo/busca_grupo_pessoa.php',360,283,250,100,'');
}

function busca_grupo_pessoa(IdGrupoPessoa, Erro, Local, ListarCampo){

	if(IdGrupoPessoa == ''){
		IdGrupoPessoa = 0;
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
    
   	url = "../administrativo/xml/grupo_pessoa.php?IdGrupoPessoa="+IdGrupoPessoa;
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
						case 'GrupoPessoa':
							addParmUrl("marGrupoPessoa","IdGrupoPessoa",'');
							
							document.formulario.DataCriacao.value 		= "";
							document.formulario.LoginCriacao.value 		= "";
							document.formulario.DataAlteracao.value 	= "";
							document.formulario.LoginAlteracao.value	= "";
							document.formulario.Acao.value 				= 'inserir';
							break;
						case 'AdicionarProcessoFinanceiro':
							document.formulario.IdGrupoPessoa.value 			= '';
							document.formulario.DescricaoGrupoPessoa.value 		= '';
							
							document.getElementById('totaltabelaGrupoPessoa').innerHTML	= "Total: 0";	
							break;
						case 'AdicionarMalaDireta':
							document.formulario.IdGrupoPessoa.value 			= '';
							document.formulario.DescricaoGrupoPessoa.value 		= '';
							
							document.getElementById('totaltabelaGrupoPessoa').innerHTML	= "Total: 0";	
							break;
						case 'Pessoa':
							document.formulario.IdGrupoPessoa_Resumido.value 			= "";
							document.formulario.DescricaoGrupoPessoa_Resumido.value 	= "";
							document.formulario.IdGrupoPessoa_Resumido2.value 			= "";
							document.formulario.DescricaoGrupoPessoa_Resumido2.value 	= "";
							document.formulario.IdGrupoPessoa.value						= "";
							document.formulario.DescricaoGrupoPessoa.value 				= "";
							break;
						default:
							document.formulario.IdGrupoPessoa.value 			= '';
							document.formulario.DescricaoGrupoPessoa.value 		= '';
							break;
					}
					
					document.formulario.IdGrupoPessoa.focus();
					
					// Fim de Carregando
					carregando(false);
				}else{
					nameNode = xmlhttp.responseXML.getElementsByTagName("IdGrupoPessoa")[0]; 
					nameTextNode = nameNode.childNodes[0];
					IdGrupoPessoa = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoGrupoPessoa")[0]; 
					nameTextNode = nameNode.childNodes[0];
					var DescricaoGrupoPessoa = nameTextNode.nodeValue;
									
					switch(Local){
						case 'GrupoPessoa':							
							addParmUrl("marGrupoPessoa","IdGrupoPessoa",IdGrupoPessoa);
							
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
							
							document.formulario.IdGrupoPessoa.value				= IdGrupoPessoa;
							document.formulario.DescricaoGrupoPessoa.value 		= DescricaoGrupoPessoa;		
							document.formulario.DataCriacao.value 				= dateFormat(DataCriacao);
							document.formulario.LoginCriacao.value 				= LoginCriacao;
							document.formulario.DataAlteracao.value 			= dateFormat(DataAlteracao);
							document.formulario.LoginAlteracao.value			= LoginAlteracao;
							document.formulario.Acao.value 						= 'alterar';
							break;
						case 'AdicionarProcessoFinanceiro':				
							
							var cont = 0; ii='';
							if(ListarCampo == '' || ListarCampo == undefined){
								if(document.formulario.Filtro_IdGrupoPessoa.value == ''){
									document.formulario.Filtro_IdGrupoPessoa.value = IdGrupoPessoa;
									ii = 0;
								}else{
									var tempFiltro	=	document.formulario.Filtro_IdGrupoPessoa.value.split(',');
										
									ii=0; 
									while(tempFiltro[ii] != undefined){
										if(tempFiltro[ii] != IdGrupoPessoa){
											cont++;													
										}
										ii++;
									}
									if(ii == cont){
										document.formulario.Filtro_IdGrupoPessoa.value = document.formulario.Filtro_IdGrupoPessoa.value + "," + IdGrupoPessoa;
									}
								}
							}else{
								ii=0;
							}
							if(ii == cont){
												
								var tam, linha, c0, c1, c2;
								
								tam 	= document.getElementById('tabelaGrupoPessoa').rows.length;
								linha	= document.getElementById('tabelaGrupoPessoa').insertRow(tam-1);
								
								if(tam%2 != 0){
									linha.style.backgroundColor = "#E2E7ED";
								}
								
								linha.accessKey 			= IdGrupoPessoa; 
								
								c0	= linha.insertCell(0);	
								c1	= linha.insertCell(1);	
								c2	= linha.insertCell(2);								
																						
								var linkIni = "<a href='cadastro_grupo_pessoa.php?IdGrupoPessoa="+IdGrupoPessoa+"'>";
								var linkFim = "</a>";
								
								c0.innerHTML = linkIni + IdGrupoPessoa + linkFim;
								c0.style.padding =	"0 0 0 5px";
								
								c1.innerHTML = linkIni + DescricaoGrupoPessoa.substr(0,30); + linkFim;						
								
								if(document.formulario.IdStatus.value == 1){
									c2.innerHTML = "<img src='../../img/estrutura_sistema/ico_del.gif' alt='Excluir?' onClick=\"remover_filtro_grupo_pessoa("+IdGrupoPessoa+")\"></tr>";
								}else{
									c2.innerHTML = "<img src='../../img/estrutura_sistema/ico_del_c.gif' alt='Excluir?'\">";
								}
								c2.style.textAlign = "center";
								c2.style.cursor = "pointer";
								
								if(document.formulario.IdProcessoFinanceiro.value == ''){
									document.getElementById('totaltabelaGrupoPessoa').innerHTML	=	'Total: '+(ii+1);
								}else{
									if(document.formulario.Erro.value != ''){
										scrollWindow('bottom');
									}
								}
							}
							document.formulario.IdGrupoPessoa.value				= "";
							document.formulario.DescricaoGrupoPessoa.value 		= "";	
							break;
						case 'AdicionarMalaDireta':	
							var cont = 0; ii='';
							
							if(ListarCampo == '' || ListarCampo == undefined){
								if(document.formulario.Filtro_IdGrupoPessoa.value == ''){
									document.formulario.Filtro_IdGrupoPessoa.value = IdGrupoPessoa;
									ii = 0;
								}else{
									var tempFiltro	=	document.formulario.Filtro_IdGrupoPessoa.value.split(',');
										
									ii=0; 
									while(tempFiltro[ii] != undefined){
										if(tempFiltro[ii] != IdGrupoPessoa){
											cont++;													
										}
										ii++;
									}
									if(ii == cont){
										document.formulario.Filtro_IdGrupoPessoa.value = document.formulario.Filtro_IdGrupoPessoa.value + "," + IdGrupoPessoa;
									}
								}
							}else{
								ii=0;
							}
							if(ii == cont){
												
								var tam, linha, c0, c1, c2;
								
								tam 	= document.getElementById('tabelaGrupoPessoa').rows.length;
								linha	= document.getElementById('tabelaGrupoPessoa').insertRow(tam-1);
								
								if(tam%2 != 0){
									linha.style.backgroundColor = "#E2E7ED";
								}
								
								linha.accessKey 			= IdGrupoPessoa; 
								
								c0	= linha.insertCell(0);	
								c1	= linha.insertCell(1);	
								c2	= linha.insertCell(2);								
																						
								var linkIni = "<a href='cadastro_grupo_pessoa.php?IdGrupoPessoa="+IdGrupoPessoa+"'>";
								var linkFim = "</a>";
								
								c0.innerHTML = linkIni + IdGrupoPessoa + linkFim;
								c0.style.padding =	"0 0 0 5px";
								
								c1.innerHTML = linkIni + DescricaoGrupoPessoa.substr(0,30); + linkFim;						
								
								if(document.formulario.IdStatus.value == 1){
									c2.innerHTML = "<img src='../../img/estrutura_sistema/ico_del.gif' alt='Excluir?' onClick=\"remover_filtro_grupo_pessoa("+IdGrupoPessoa+")\"></tr>";
								}else{
									c2.innerHTML = "<img src='../../img/estrutura_sistema/ico_del_c.gif' alt='Excluir?'\">";
								}
								c2.style.textAlign = "center";
								c2.style.cursor = "pointer";
								
								document.getElementById('totaltabelaGrupoPessoa').innerHTML	=	'Total: '+(ii+1);
							}
							document.formulario.IdGrupoPessoa.value				= "";
							document.formulario.DescricaoGrupoPessoa.value 		= "";	
							break;
						case 'Pessoa':
							if(document.formulario.CadastroResumido.value == 1){
								document.formulario.IdGrupoPessoa_Resumido.value 			= IdGrupoPessoa;
								document.formulario.DescricaoGrupoPessoa_Resumido.value 	= DescricaoGrupoPessoa;
								document.formulario.IdGrupoPessoa_Resumido2.value 			= IdGrupoPessoa;
								document.formulario.DescricaoGrupoPessoa_Resumido2.value 	= DescricaoGrupoPessoa;
							}else{
								document.formulario.IdGrupoPessoa_Resumido.value 			= "";
								document.formulario.DescricaoGrupoPessoa_Resumido.value 	= "";
								document.formulario.IdGrupoPessoa_Resumido2.value 			= "";
								document.formulario.DescricaoGrupoPessoa_Resumido2.value 	= "";
							}
							document.formulario.IdGrupoPessoa.value				= IdGrupoPessoa;
							document.formulario.DescricaoGrupoPessoa.value 		= DescricaoGrupoPessoa;
							break;
						default:
							document.formulario.IdGrupoPessoa.value				= IdGrupoPessoa;
							document.formulario.DescricaoGrupoPessoa.value 		= DescricaoGrupoPessoa;
							break;
					}
				}
				if(document.getElementById('quadroBuscaGrupoPessoa') != null){
					if(document.getElementById('quadroBuscaGrupoPessoa').style.display	==	"block"){
						document.getElementById('quadroBuscaGrupoPessoa').style.display	= 	"none";
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
