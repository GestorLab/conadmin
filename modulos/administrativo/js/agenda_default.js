function janela_busca_agenda(){
	janelas('busca_agenda.php',530,350,250,100,'');
}
function busca_agenda(IdAgenda,Erro,Local){
	if(IdAgenda == '' || IdAgenda == undefined){
		IdAgenda = 0;
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

   	url = "xml/agenda.php?IdAgenda="+IdAgenda;
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
				//alert(xmlhttp.responseText);
				if(xmlhttp.responseText == 'false'){
					if(Local == 'Agenda'){
							
							document.formulario.IdAgenda.value				=	"";
							document.formulario.Data.value					=	"";
							document.formulario.Hora.value					=	"";
							document.formulario.IdStatus.value				=	"";
							document.formulario.IdStatus.disabled			= 	true;
							document.formulario.Descricao.value				=	"";
							document.formulario.IdPessoa.value				= 	"";
							document.formulario.IdPessoaF.value				=	"";
							document.formulario.Nome.value					=	"";
							document.formulario.NomeF.value					=	"";
							document.formulario.Telefone1.value				=	"";
							document.formulario.Sexo.value					=	"";
							document.formulario.EstadoCivil.value			=	"";
							document.formulario.Telefone1F.value			=	"";
							document.formulario.CPF_CNPJ.value				=	"";
							document.formulario.Cidade.value				=	"";
							document.formulario.SiglaEstado.value					=	"";
							document.formulario.Email.value					=	"";
							document.formulario.RazaoSocial.value			=   "";
							document.formulario.Acao.value 					= 	'inserir';
							
							addParmUrl("marAgenda","IdAgenda",'');
							
							verificaAcao();
					}
					
					// Fim de Carregando
					carregando(false);
				}else{
					nameNode = xmlhttp.responseXML.getElementsByTagName("IdAgenda")[0]; 
					nameTextNode = nameNode.childNodes[0];
					var IdAgenda = nameTextNode.nodeValue;
					
					if(Local == 'Agenda'){
							
						addParmUrl("marAgenda","IdAgenda",IdAgenda);

						nameNode = xmlhttp.responseXML.getElementsByTagName("Data")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var Data = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("Hora")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var Hora = nameTextNode.nodeValue;

						nameNode = xmlhttp.responseXML.getElementsByTagName("Descricao")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var Descricao = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("IdPessoa")[0];
						nameTextNode = nameNode.childNodes[0];
						var IdPessoa = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("IdStatus")[0];
						nameTextNode = nameNode.childNodes[0];
						var IdStatus = nameTextNode.nodeValue;
							
						
						busca_pessoa(IdPessoa,false,document.formulario.Local.value);

						document.formulario.IdAgenda.value				=	IdAgenda;
						document.formulario.Data.value					=	dateFormat(Data);
						document.formulario.Hora.value					=	Hora.substr(0,5);
						document.formulario.Descricao.value				=	Descricao;
						document.formulario.IdPessoa.value				= 	IdPessoa;
						document.formulario.IdStatus.value				= 	IdStatus;
						document.formulario.IdStatus.disabled			= 	false;
						document.formulario.Acao.value 					= 	'alterar';

						verificaAcao();
					}	
				}
				if(window.janela != undefined){
					window.janela.close();
				}
			}
			// Fim de Carregando
			carregando(false);
		}
		return true;
	}
	xmlhttp.send(null);
}

