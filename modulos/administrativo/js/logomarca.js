	function cadastrar(Acao){
		if(validar()){
			document.formulario.Acao.value = Acao;
			document.formulario.submit();
		}
	}
	function validar(){
		if(document.formulario.Largura.value == ""){
			mensagens(1);
			document.formulario.Largura.focus();
			return false;
		}else{
			if(document.formulario.Largura.value <= 0){
				mensagens(1);
				document.formulario.Largura.focus();
				return false;
			}
		}
		if(document.formulario.Margem_Esquerda.value == ""){
			mensagens(1);
			document.formulario.Margem_Esquerda.focus();
			return false;
		}
		if(document.formulario.Margem_Superior.value == ""){
			mensagens(1);
			document.formulario.Margem_Superior.focus();
			return false;
		}
		if(document.formulario.fakeupload.value != ""){
			var ExtImagem	= document.formulario.fakeupload.value.split(".");
			
			if(ExtImagem[ExtImagem.length-1] != "gif"){
				mensagens(170);
				document.formulario.fakeupload.focus();
				return false;
			}
		}
		
		return true;
	}
	function verificaImagem(){
		document.getElementById("VerImagem").style.backgroundImage  = "url("+document.formulario.fakeupload.value+")";
	}
	function verificaAcao(){
		if(document.getElementById("VerImagem") != ""){
			document.formulario.bt_alterar.disabled = false;
		} else{
			document.formulario.bt_alterar.disabled = true;
		}
	}
	function atualiza_logo(){
		var logo_head = parent.cabecalho.document.getElementById('logo_head');
		logo_head.src = '../../img/personalizacao/logo_cab.gif?rand=' + Math.random();
		var myImageElement = document.getElementById('VerImagem');
		myImageElement.src = '../../img/personalizacao/logo_cab.gif?rand=' + Math.random();
	}
	function resetar_logomarca(){
		var url = "xml/logomarca_resetar_default.php";
		
		call_ajax(url, function(xmlhttp){
			var nameNode, nameTextNode;
			
			if(xmlhttp.responseText != 'false'){
				mensagens(xmlhttp.responseText);
				atualiza_logo();
			}
		});
	}
	function limpa_campos_posicionamento_logo(IdLoja){
		
		var url = "xml/logomarca_reset.php?IdLoja="+IdLoja;
		
		call_ajax(url, function(xmlhttp){
			var nameNode, nameTextNode;
			
			if(xmlhttp.responseText != 'false'){
				nameNode = xmlhttp.responseXML.getElementsByTagName("Largura")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var Largura = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("MargemEsquerda")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var MargemEsquerda = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("MargemSuperior")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var MargemSuperior = nameTextNode.nodeValue;
				
				document.formulario.Largura.value			= Largura;
				document.formulario.Margem_Esquerda.value	= MargemEsquerda;
				document.formulario.Margem_Superior.value	= MargemSuperior;
				
				resetar_logomarca();
			}
		});
	}