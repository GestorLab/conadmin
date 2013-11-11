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
	return true;
}
function validarConfirma(){
	if(document.formulario.KeyCode.value==''){
		document.formulario.KeyCode.focus();
		mensagens(1,document.formulario.Local.value);
		return false;
	}
	if(document.formulario.KeyLicenca.value==''){
		document.formulario.KeyLicenca.focus();
		mensagens(1,document.formulario.Local.value);
		return false;
	}
	return true;
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
function inicia(){
	document.formulario.IdLoja.focus();
}
function iniciaConfirma(){
	document.formulario.KeyCode.select();
}
function busca_loja(IdLoja) {
	if(IdLoja == '') {
		IdLoja = 0;
	}
	
	call_ajax("xml/loja_free.php?IdLoja=" + IdLoja, function (xmlhttp) {
		if(xmlhttp.responseText == 'false') {
			document.formulario.IdLoja.value = "";
			document.formulario.DescricaoLoja.value = "";
			document.formulario.IdLoja.focus();
			return false;
		} else {
			var nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoLoja")[0]; 
			var nameTextNode = nameNode.childNodes[0];
			document.formulario.DescricaoLoja.value = nameTextNode.nodeValue;
		}
	});
}