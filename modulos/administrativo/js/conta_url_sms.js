	function validar(){
		if(document.formulario.IdPessoa.value == ""){
			document.formulario.IdPessoa.focus();
			mensagens(1);
			return false
		}
		if(document.formulario.Celular.value == ""){
			document.formulario.Celular.focus();
			mensagens(1);
			return false
		}
		if(document.formulario.Mensagem.value == ""){
			document.formulario.Mensagem.focus();
			mensagens(1);
			return false
		}
		
		return true;
	}
	
	function busca_pessoa(IdPessoa){
		if(IdPessoa == "" || IdPessoa == undefined){
			IdPessoa = 0;
		}
	
		var url = "xml/pessoa.php?IdPessoa="+IdPessoa;
		
		call_ajax(url, function (xmlhttp) {
			if(xmlhttp.responseText != "false") {
				for(var i = 0; i < xmlhttp.responseXML.getElementsByTagName("IdPessoa").length; i++) {
					var nameNode = xmlhttp.responseXML.getElementsByTagName("IdPessoa")[i]; 
					var nameTextNode = nameNode.childNodes[0];
					var IdPessoa = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("Nome")[i]; 
					nameTextNode = nameNode.childNodes[0];
					var Nome = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("Celular")[i]; 
					nameTextNode = nameNode.childNodes[0];
					var Celular = nameTextNode.nodeValue;
					
					document.formulario.IdPessoa.value 	= IdPessoa;
					document.formulario.Nome.value		= Nome;	
					document.formulario.Celular.value	= Celular;	
				}
			} else{
				document.formulario.IdPessoa.value 	= "";
				document.formulario.Nome.value		= "";
				document.formulario.Celular.value	= "";
				document.formulario.Mensagem.value	= "";
				document.formulario.Link.value		= "";
				document.getElementById('tablelink').style.display = "none";
				verificarcampos();
			}
			
			if(document.getElementById("quadroBuscaPessoa") != null){
				if(document.getElementById("quadroBuscaPessoa").style.display == "block"){
					document.getElementById("quadroBuscaPessoa").style.display =	"none";
				}
			}
		});
	}
	
	function maxlegth(campo,max){
		if(campo == undefined){
			return false;
		}
		
		if(campo.value.length <= max){
			campo.readOnly = false;
		}else{
			campo.readOnly = true;
		}
	}
	
	function verificarcampos(IdPessoa,Celular,Mensagem){
		if(IdPessoa != undefined && Celular != undefined && Mensagem != undefined)
		{
			if(IdPessoa != "" && Celular != "" && Mensagem != ""){
				document.formulario.bt_GeraUrl.disabled = false;
			}else{
				document.formulario.bt_GeraUrl.disabled = true;
			}			
		}
	}