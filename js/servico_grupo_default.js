	function janela_busca_servico_grupo(){
		janelas('busca_servico_grupo.php',360,283,250,100,'');
	}
	function busca_servico_grupo(IdServicoGrupo, Erro, Local){
		if(IdServicoGrupo == ''){
			IdServicoGrupo = 0;
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
		url = "xml/servico_grupo.php?IdServicoGrupo="+IdServicoGrupo;
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
							case "Servico":
								document.formulario.IdServicoGrupo.value 			= '';
								document.formulario.DescricaoServicoGrupo.value 	= '';
								break;
							default:
								addParmUrl("marGrupoServico","IdServicoGrupo","");
							
								document.formulario.IdServicoGrupo.value 			= '';
								document.formulario.DescricaoServicoGrupo.value 	= '';
								document.formulario.DataCriacao.value 				= '';
								document.formulario.LoginCriacao.value 				= '';
								document.formulario.DataAlteracao.value 			= '';
								document.formulario.LoginAlteracao.value			= '';
								document.formulario.Acao.value 						= 'inserir';
						}
						document.formulario.IdServicoGrupo.focus();
						
						// Fim de Carregando
						carregando(false);
					}else{
						nameNode = xmlhttp.responseXML.getElementsByTagName("IdServicoGrupo")[0]; 
						nameTextNode = nameNode.childNodes[0];
						IdServicoGrupo = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoServicoGrupo")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var DescricaoServicoGrupo = nameTextNode.nodeValue;
						
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
						
						switch (Local){		
							case "Servico":
								document.formulario.IdServicoGrupo.value		= IdServicoGrupo;
								document.formulario.DescricaoServicoGrupo.value = DescricaoServicoGrupo;
								break;
							default:
								addParmUrl("marGrupoServico","IdServicoGrupo",IdServicoGrupo);
								
								document.formulario.IdServicoGrupo.value		= IdServicoGrupo;
								document.formulario.DescricaoServicoGrupo.value = DescricaoServicoGrupo;
								document.formulario.DataCriacao.value 			= dateFormat(DataCriacao);
								document.formulario.LoginCriacao.value 			= LoginCriacao;
								document.formulario.DataAlteracao.value 		= dateFormat(DataAlteracao);
								document.formulario.LoginAlteracao.value		= LoginAlteracao;
								
								document.formulario.Acao.value 					= 'alterar';
						}
					}
					if(document.getElementById("quadroBuscaServicoGrupo") != null){
						if(document.getElementById("quadroBuscaServicoGrupo").style.display == "block"){
							document.getElementById("quadroBuscaServicoGrupo").style.display =	"none";
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
	
