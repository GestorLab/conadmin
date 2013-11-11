	function busca_loja(IdLoja){
		if(IdLoja == ''){
			IdLoja = 0;
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
		
		xmlhttp.open("GET", "xml/loja_free.php?IdLoja="+IdLoja); 
		
		xmlhttp.onreadystatechange = function(){ 
		
			// Carregando...
			carregando(true);
		
			if(xmlhttp.readyState == 4){ 
				if(xmlhttp.status == 200){
					if(xmlhttp.responseText == 'false'){
						document.formulario.IdLoja.value 		= "";
						document.formulario.DescricaoLoja.value = "";
						document.formulario.IdLoja.focus();
						// Fim de Carregando
						carregando(false);
						return false;
					}else{
						var nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoLoja")[0]; 
						var nameTextNode = nameNode.childNodes[0];
						
						document.formulario.DescricaoLoja.value = nameTextNode.nodeValue;
					}
					if(document.formulario.Login.value != '' && document.formulario.Senha.value !=''){
						busca_usuario(document.formulario.Login.value, false);
					}
					// Fim de Carregando
					carregando(false);
				}
			} 
			return true;
		}
		xmlhttp.send(null);
	}
	function validar(){		
		if(document.formulario.IdLoja.value==''){
			document.formulario.IdLoja.focus();
			mensagens(1,document.formulario.Local.value);
			return false;
		}
		if(document.formulario.Login.value==''){
			document.formulario.Login.focus();
			mensagens(1,document.formulario.Local.value);
			return false;
		}
		if(document.formulario.Senha.value==''){
			document.formulario.Senha.focus();
			mensagens(1,document.formulario.Local.value);
			return false;
		}
		if(document.formulario.AntiSpamSTR.value == '' && document.formulario.autorizaAntispam.value == 1){
			document.formulario.AntiSpamSTR.focus();
			mensagens(1,document.formulario.Local.value);
			return false;
		}
		return true;
	}
	function inicia(){
		document.formulario.IdLoja.focus();
	}
	function cadastrar(event){
		var nTecla;
		
		if(document.all) { // Internet Explorer
			nTecla = event.keyCode;
		} else if(document.layers) { // Nestcape
			nTecla = event.which;
		} else {
			nTecla = event.which;
		}
		
		if(nTecla=='13'){ 
			document.formulario.submit();
		}
	}

	/***** CDA ********/


	function validaCampo(CPF_CNPJ){
		var temp = "", tipo = "", cpf="";
		CPF_CNPJ = retiraCaracter(retiraCaracter(retiraCaracter(CPF_CNPJ, '.'), '-'),'/');
		
		switch(CPF_CNPJ.length){
			case 11:
				tipo = "cpf";
				if(isCPF(CPF_CNPJ) == false){
					mensagens(110,document.formulario.Local.value);
				}
				break;
			case 14:
				tipo = "cnpj";
				if(isCNPJ(CPF_CNPJ) == false){
					mensagens(110,document.formulario.Local.value);
				}
				break;
			default:
				mensagens(111,document.formulario.Local.value);
		}
		document.formulario.CPF_CNPJMascara.value	=	inserir_mascara(CPF_CNPJ,tipo);
		mensagens(0);
	}

	function inserir_mascara(valor,tipo){
		if(valor == ''){
			return false;
		}
		if(tipo == 'cpf'){
			var retorno = valor.substr(0,3) + '.' + valor.substr(3,3) + '.' + valor.substr(6,3) + '-' + valor.substr(9,2);
		}else{
			var retorno = valor.substr(0,2) + '.' + valor.substr(2,3) + '.' + valor.substr(5,3) + '/' + valor.substr(8,4) + '-' + valor.substr(12,2);
		}
		return retorno;
	}

	function retiraCaracter(string, caracter) {
		var i = 0;
		var final = '';
		while (i < string.length) {
			if (string.charAt(i) == caracter) {
				final += string.substr(0, i);
				string = string.substr(i+1, string.length - (i+1));
				i = 0;
			}
			else {
				i++;
			}
		}
		return final + string;
	}


	function busca_email(CPF_CNPJ){
		if(CPF_CNPJ == ''){
			CPF_CNPJ = 0;
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
		url = "xml/pessoa.php?CPF_CNPJ="+CPF_CNPJ;
		
		xmlhttp.open("GET", url,true);
		xmlhttp.onreadystatechange = function(){ 
		
			// Carregando...
			carregando(true);
		
			if(xmlhttp.readyState == 4){ 
				if(xmlhttp.status == 200){
					if(xmlhttp.responseText == 'false'){
						document.formulario.Email.value 		= "";
						document.formulario.IdPessoa.value  	= "";
						document.formulario.CPF_CNPJ.focus();
						
						if(CPF_CNPJ != 0){
							mensagens(110,document.formulario.Local.value);
						}else{
							mensagens(0);
						}
						// Fim de Carregando
						carregando(false);
						return false;
					}else{
						mensagens(0);
					
						var nameNode = xmlhttp.responseXML.getElementsByTagName("Email")[0]; 
						var nameTextNode = nameNode.childNodes[0];
						var Email	=	nameTextNode.nodeValue;
						
						var nameNode = xmlhttp.responseXML.getElementsByTagName("IdPessoa")[0]; 
						var nameTextNode = nameNode.childNodes[0];
						var IdPessoa	=	nameTextNode.nodeValue;
						
						document.formulario.Email.value 	= Email;
						document.formulario.IdPessoa.value  = IdPessoa;
					}
				}
				// Fim de Carregando
				carregando(false);
			} 
			return true;
		}
		xmlhttp.send(null);
	}	
	
	function busca_usuario(Login, Erro){
		// Carregando...
		carregando(true);
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
	    
	   	url = "modulos/administrativo/xml/usuario.php?Login="+Login; 
	   	
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
						document.getElementById("AntiSpam").style.display = "none";
						document.formulario.autorizaAntispam.value = 2;
						document.formulario.bt_logar.disabled = true;
						document.formulario.Senha.value = "";
						carregando(false);
					}else{
						nameNode = xmlhttp.responseXML.getElementsByTagName("QtdLoginInvalido")[0]; 
						nameTextNode = nameNode.childNodes[0];
						QtdLoginInvalido = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("Login")[0]; 
						nameTextNode = nameNode.childNodes[0];
						Login = nameTextNode.nodeValue;

						if(QtdLoginInvalido >= 3){
							document.getElementById("AntiSpam").style.display = "block";
							document.formulario.autorizaAntispam.value = 1;
						}else{
							document.formulario.autorizaAntispam.value = 2;
							document.getElementById("AntiSpam").style.display = "none";
						}
						document.formulario.bt_logar.disabled = false;
					}
				}
				// Fim de Carregando
				carregando(false);
			} 
			return true;
		}
		xmlhttp.send(null);
	}