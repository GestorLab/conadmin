var Anterior 	= '';
var IdAnterior	= '';	

function open_close(IdOpc,Atual){
	switch(document.getElementById(IdOpc).style.display){
		case 'block':
			document.getElementById(IdOpc).style.display = 'none';
			Atual.style.backgroundImage = "url(../../img/estrutura_sistema/mn_mais.gif)";
			break;
		default:
			document.getElementById(IdOpc).style.display = 'block';
			Atual.style.backgroundImage = "url(../../img/estrutura_sistema/mn_menos.gif)";
			break;
	}
	if(Anterior != '' && Anterior != Atual){
		document.getElementById(IdAnterior).style.display = 'none';	
		Anterior.style.backgroundImage = "url(../../img/estrutura_sistema/mn_mais.gif)";
	}
	IdAnterior	=	IdOpc;
	Anterior	=	Atual;
}
