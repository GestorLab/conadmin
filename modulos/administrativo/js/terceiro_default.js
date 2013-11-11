	function janela_busca_terceiro(IdStatus){
		if(IdStatus == '' || IdStatus == undefined || IdStatus < 2){
			janelas('busca_terceiro.php',530,350,250,100,'');
		}
	}
	function busca_terceiro(IdPessoa, Erro, Local){
		if(IdPessoa == ''){
			IdPessoa = 0;
		}
		if(Local=='' || Local==undefined){
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
		url = "xml/terceiro.php?IdPessoa="+IdPessoa;
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
							case "Terceiro":
								addParmUrl("marTerceiro","IdPessoa","");
								addParmUrl("marLoteRepasse","IdPessoa","");
								addParmUrl("marLoteRepasseNovo","IdPessoa","");

								document.formulario.IdPessoa.value 			= '';
								document.formulario.Nome.value 				= '';
								document.formulario.DataCriacao.value 		= '';
								document.formulario.LoginCriacao.value 		= '';
								document.formulario.DataAlteracao.value 	= '';
								document.formulario.LoginAlteracao.value	= '';
								document.formulario.Acao.value 				= 'inserir';
								
								busca_pessoa(IdPessoa,'false',Local);
								verificaAcao();
								break;
							case 'Servico':
								document.formulario.IdTerceiro.value							= "";
								document.formulario.NomeTerceiro.value 							= "";
								document.formulario.PercentualRepasseTerceiro.value				= "0,00";
								document.formulario.PercentualRepasseTerceiroOutros.value		= "0,00";
								document.formulario.IdRepasse.value								= 0;
								document.formulario.IdRepasse.disabled							= true;
								document.formulario.ValorRepasseTerceiro.readOnly				= true;
								document.formulario.PercentualRepasseTerceiro.readOnly			= true;
								document.formulario.PercentualRepasseTerceiroOutros.readOnly	= true;
								
								verificarRepasse(document.formulario.IdRepasse.value);
								break;
							case 'OrdemServicoFatura':
								document.formulario.IdTerceiro.value 				= '';
								document.formulario.NomeTerceiro.value 				= '';
								break
							default:
								document.formulario.IdPessoa.value 			= '';
								document.formulario.Nome.value 				= '';
								
								document.formulario.IdPessoa.focus();
						}
						
						// Fim de Carregando
						carregando(false);
					}else{
						nameNode = xmlhttp.responseXML.getElementsByTagName("IdPessoa")[0]; 
						nameTextNode = nameNode.childNodes[0];
						IdPessoa = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("Nome")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var Nome = nameTextNode.nodeValue;
						
						switch (Local){	
							case 'Terceiro':
								addParmUrl("marTerceiro","IdPessoa",IdPessoa);
								addParmUrl("marLoteRepasse","IdPessoa",IdPessoa);
								addParmUrl("marLoteRepasseNovo","IdPessoa",IdPessoa);
							
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
								
								document.formulario.IdPessoa.value 				= IdPessoa;
								document.formulario.Nome.value 					= Nome;
								document.formulario.DataCriacao.value 			= dateFormat(DataCriacao);
								document.formulario.LoginCriacao.value 			= LoginCriacao;
								document.formulario.DataAlteracao.value 		= dateFormat(DataAlteracao);
								document.formulario.LoginAlteracao.value		= LoginAlteracao;
								document.formulario.Acao.value 					= 'alterar';
								
								verificaAcao();
								break;	
							case 'Servico':
								document.formulario.IdTerceiro.value							= IdPessoa;
								document.formulario.NomeTerceiro.value							= Nome;
								document.formulario.PercentualRepasseTerceiro.value				= "0,00";
								document.formulario.PercentualRepasseTerceiroOutros.value		= "0,00";
								document.formulario.IdRepasse.value								= "0";
								document.formulario.IdRepasse.disabled							= false;
								document.formulario.ValorRepasseTerceiro.readOnly				= false;
								document.formulario.PercentualRepasseTerceiro.readOnly			= false;
								document.formulario.PercentualRepasseTerceiroOutros.readOnly	= false;
								
								verificarRepasse(document.formulario.IdRepasse.value);
								break;
							case 'OrdemServicoFatura':
								document.formulario.IdTerceiro.value 				= IdPessoa;
								document.formulario.NomeTerceiro.value 				= Nome;
								break;
							default:
								document.formulario.IdPessoa.value 				= IdPessoa;
								document.formulario.Nome.value 					= Nome;
								break;
						}
					}
					if(document.getElementById("quadroBuscaTerceiro") != null){
						if(document.getElementById("quadroBuscaTerceiro").style.display == "block"){
							document.getElementById("quadroBuscaTerceiro").style.display =	"none";
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
	
