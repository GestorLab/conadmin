function validar(){
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
function inicia(){
	document.formulario.Login.focus();
}
function iniciaConfirma(){
	document.formulario.KeyCode.select();
}