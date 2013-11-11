	function cadastrar(Acao){
		if(validar()){
			document.formulario.Acao.value = Acao;
			document.formulario.submit();
		}
	}
	function validar(){
		if(document.formulario.IdTemplate.value == ''){
			mensagens(1);
			document.formulario.IdTemplate.focus();
			return false;
		}
		
		if(document.formulario.IdContaEmail.value == ''){
			mensagens(1);
			document.formulario.IdContaEmail.focus();
			return false;
		}
		
		if(document.formulario.IdStatus.value == ''){
			mensagens(1);
			document.formulario.IdStatus.focus();
			return false;
		}
		
		if(document.formulario.Titulo.value == ''){
			mensagens(1);
			document.formulario.Titulo.focus();
			return false;
		}
		
		if(document.formulario.Conteudo.value == ''){
			mensagens(1);
			document.formulario.Conteudo.focus();
			return false;
		}
		if(document.formulario.camposObrigatoriedade.value != 1){
			if(document.formulario.Assunto.value == ''){
				mensagens(1);
				document.formulario.Assunto.focus();
				return false;
			}
			
			if(document.formulario.Assinatura.value == ''){
				mensagens(1);
				document.formulario.Assinatura.focus();
				return false;
			}
		}
		
		return true;
	}
	function inicia(){
		status_inicial();
		
		document.formulario.IdTipoMensagem.focus();
	}
	function verificaAcao(){
		if(document.formulario != undefined){
			if(document.formulario.Acao.value=='inserir'){
				document.formulario.bt_alterar.disabled 	= true;
			}
			if(document.formulario.Acao.value=='alterar'){
				document.formulario.bt_alterar.disabled 	= false;
			}
		}	
	}
	function atualizar_formulario_IdContaEmail(IdTemplate,Selecionar){
		url = "xml/conta_mensagens.php?IdTemplate="+IdTemplate;
			call_ajax(url, function(xmlhttp){
			var nameNode, nameTextNode;

			if(xmlhttp.responseText == 'false'){
				while(document.formulario.IdContaEmail.options.length > 0){
					document.formulario.IdContaEmail.options[0] = null;
				}				
				addOption(document.formulario.IdContaEmail,"","");
			}else{
				while(document.formulario.IdContaEmail.options.length > 0){
					document.formulario.IdContaEmail.options[0] = null;
				}				
				addOption(document.formulario.IdContaEmail,"","");
				for(i=0; i<xmlhttp.responseXML.getElementsByTagName("IdConta").length; i++){
				
					nameNode = xmlhttp.responseXML.getElementsByTagName("IdConta")[i];
					nameTextNode = nameNode.childNodes[0];
					var IdConta = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("Descricao")[i];
					nameTextNode = nameNode.childNodes[0];
					var Descricao = nameTextNode.nodeValue;
					var Descricao = Descricao.substr(0,50);

					addOption(document.formulario.IdContaEmail,Descricao,IdConta);
				}			
				if(document.formulario.IdTipoMensagem.value == ''){
					document.formulario.IdContaEmail[0].selected = true;
				}
				if(Selecionar != undefined){
					document.formulario.IdContaEmail.value = Selecionar;
				}
				
			}
		})
	}
	function displayCampo(Selecao){
		if(Selecao == 4){
			if(document.getElementById("Assunto") != undefined){
				document.getElementById("Assunto").style.display = 'none';
				document.getElementById("TitAssunto").style.display = 'none';
			}
			if(document.getElementById("Assinatura") != undefined){
				document.getElementById("Assinatura").style.display = 'none';
			}
			document.formulario.camposObrigatoriedade.value = 1;
			document.getElementById("Conta").innerHTML = "Conta SMS";
		}else{
			if(document.getElementById("Assunto") != undefined){
				document.getElementById("Assunto").style.display = 'block';
				document.getElementById("TitAssunto").style.display = 'block';
			}
			if(document.getElementById("Assinatura") != undefined){
				document.getElementById("Assinatura").style.display = 'block';
			}
			document.getElementById("Conta").innerHTML = "Conta Email";
		}
		if(Selecao == ''){
			document.getElementById("Conta").innerHTML = "Conta E-mail/SMS";
		}
	}