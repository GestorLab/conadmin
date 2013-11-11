function validar(){
	if(document.formulario.Login.value==''){
		document.formulario.Login.focus();
		mensagens(1,document.formulario.Local.value);
		return false;
	} 
	if(document.formulario.SenhaAtual.value==''){
		document.formulario.SenhaAtual.focus();
		mensagens(1,document.formulario.Local.value);
		return false;
	} 
	if(document.formulario.NovaSenha.value==''){
		document.formulario.NovaSenha.focus();
		mensagens(1,document.formulario.Local.value);
		return false;
	} 
	if(document.formulario.ConfirmaSenha.value==''){
		document.formulario.ConfirmaSenha.focus();	
		mensagens(1,document.formulario.Local.value);
		return false;
	}
		
	if(document.formulario.NovaSenha.value != document.formulario.ConfirmaSenha.value){
		mensagens(11,document.formulario.Local.value);
		
		document.formulario.NovaSenha.focus();
		document.formulario.NovaSenha.value = "";
		document.formulario.ConfirmaSenha.value = "";
		document.getElementById("statusSenha").style.display = "none";
		return false;
	}
	if(document.formulario.NovaSenha.value.length < 8){
		document.formulario.NovaSenha.focus();
		mensagens(193,document.formulario.Local.value);
		return false;
	}
	if(document.formulario.NivelSenha.value == 1){
		document.formulario.NovaSenha.focus();
		mensagens(194,document.formulario.Local.value);
		return false;
	}
	return true;
}
function inicia(){
	document.formulario.Login.focus();
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
	
	if(nTecla=='13' && (window.ActiveXObject)){
		if(validar()){
			document.formulario.submit();
		}
	}
}
function voltar(){
	window.location.href = "index.php";
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
