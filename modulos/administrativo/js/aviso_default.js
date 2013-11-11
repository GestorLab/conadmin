function janela_busca_aviso(){
	janelas('busca_agenda.php',530,350,250,100,'');
}
function busca_aviso(IdAviso,Erro,Local){
	if(IdAviso == '' || IdAviso == undefined){
		IdAviso = 0;
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

   	url = "xml/aviso.php?IdAviso="+IdAviso;
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
					if(Local == 'Aviso'){
						document.formulario.IdAviso.value				=	"";
						document.formulario.Data.value					=	"";
						document.formulario.Hora.value					=	"";
						document.formulario.IdAvisoForma.value			=	"";
						document.formulario.Aviso.value					=	"";
						document.formulario.TituloAviso.value			= 	"";
						document.formulario.ResumoAviso.value			=	"";
						document.formulario.IdGrupoPessoa.value			=	"";
						document.formulario.DescricaoGrupoPessoa.value	=	"";
						document.formulario.IdServico.value				=	"";
						document.formulario.ParametroContrato.value		=	"";
						document.formulario.DescricaoServico.value		=	"";
						document.formulario.IdTipoServico.value			=	"";
						
						document.getElementById('cp_juridica').style.display			= 'block';
						document.getElementById('cp_fisica').style.display				= 'none';
						
						document.formulario.IdPessoa.value 			= '';
						document.formulario.IdPessoaF.value 		= '';
						document.formulario.Nome.value 				= '';
						document.formulario.NomeF.value 			= '';
						document.formulario.RazaoSocial.value 		= '';
						document.formulario.CPF.value 				= '';
						document.formulario.CNPJ.value 				= '';
						document.formulario.Email.value 			= '';
						document.formulario.IdGrupoUsuario.value	= '';
						document.formulario.Usuario.value			= '';
						document.formulario.DataCriacao.value		=	"";
						document.formulario.LoginCriacao.value		=	"";
						document.formulario.DataAlteracao.value		=	"";
						document.formulario.LoginAlteracao.value	=	"";
						document.formulario.Acao.value 				= 	'inserir';

						document.getElementById('LabelData').style.color = '#000';
						document.getElementById('LabelHora').style.color = '#000';
						
						busca_login_usuario('',document.formulario.Usuario);
						filtro_informativo_interno('');
						
						addParmUrl("marAviso","IdAviso",'');
						
						document.formulario.IdAviso.focus();
						verificaAcao();
					}
					
					// Fim de Carregando
					carregando(false);
				}else{
					nameNode = xmlhttp.responseXML.getElementsByTagName("IdAviso")[0]; 
					nameTextNode = nameNode.childNodes[0];
					var IdAviso = nameTextNode.nodeValue;
					
					if(Local == 'Aviso'){
							
						addParmUrl("marAviso","IdAviso",IdAviso);

						nameNode = xmlhttp.responseXML.getElementsByTagName("DataExpiracao")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var DataExpiracao = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("Aviso")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var Aviso = nameTextNode.nodeValue;

						nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoGrupoPessoa")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var DescricaoGrupoPessoa = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("IdGrupoPessoa")[0];
						nameTextNode = nameNode.childNodes[0];
						var IdGrupoPessoa = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("TituloAviso")[0];
						nameTextNode = nameNode.childNodes[0];
						var TituloAviso = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("ResumoAviso")[0];
						nameTextNode = nameNode.childNodes[0];
						var ResumoAviso = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("IdAvisoForma")[0];
						nameTextNode = nameNode.childNodes[0];
						var IdAvisoForma = nameTextNode.nodeValue;
						
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
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("IdPessoa")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var IdPessoa = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("Nome")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var Nome = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("RazaoSocial")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var RazaoSocial = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("TipoPessoa")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var TipoPessoa = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("CPF_CNPJ")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var CPF_CNPJ = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("Email")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var Email = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("IdServico")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var IdServico = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("ParametroContrato")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var ParametroContrato = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoServico")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var DescricaoServico = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("IdTipoServico")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var IdTipoServico = nameTextNode.nodeValue;

						nameNode = xmlhttp.responseXML.getElementsByTagName("IdGrupoUsuario")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var IdGrupoUsuario = nameTextNode.nodeValue;

						nameNode = xmlhttp.responseXML.getElementsByTagName("Usuario")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var Usuario = nameTextNode.nodeValue;
						
						document.formulario.IdPessoa.value 			= IdPessoa;
						document.formulario.CPF.value 				= CPF_CNPJ;
						document.formulario.CNPJ.value 				= CPF_CNPJ;
						document.formulario.Email.value 			= Email;
						
						if(TipoPessoa == 2){
							document.formulario.IdPessoaF.value 	= IdPessoa;
							document.formulario.NomeF.value 		= Nome;
						
							document.getElementById('cp_fisica').style.display		= 'block';
							document.getElementById('cp_juridica').style.display	= 'none';
						}else{
							document.formulario.RazaoSocial.value 	= RazaoSocial;
							document.formulario.Nome.value 			= Nome;
							document.formulario.IdPessoa.value 		= IdPessoa;
						
							document.getElementById('cp_juridica').style.display	= 'block';
							document.getElementById('cp_fisica').style.display		= 'none';
						}	

						document.getElementById('LabelData').style.color = '#000';
						document.getElementById('LabelHora').style.color = '#000';
						
						Data	=	DataExpiracao.substr(0,10);
						Hora	=	DataExpiracao.substr(11,5);	
						
						document.formulario.IdAviso.value				=	IdAviso;
						
						if(Data != "0000-00-00"){
							document.formulario.Data.value					=	dateFormat(Data);
						}

						document.formulario.Hora.value					=	Hora.substr(0,5);
						
						document.formulario.Aviso.value					=	Aviso;
						document.formulario.TituloAviso.value			= 	TituloAviso;
						document.formulario.ResumoAviso.value			= 	ResumoAviso;
						document.formulario.IdAvisoForma.value			= 	IdAvisoForma;
						document.formulario.IdGrupoPessoa.value			= 	IdGrupoPessoa;
						document.formulario.DescricaoGrupoPessoa.value	= 	DescricaoGrupoPessoa;
						document.formulario.IdServico.value				= 	IdServico;
						document.formulario.ParametroContrato.value		=	ParametroContrato;
						document.formulario.DescricaoServico.value		= 	DescricaoServico;
						document.formulario.IdTipoServico.value			= 	IdTipoServico;
						document.formulario.IdGrupoUsuario.value		= 	IdGrupoUsuario;
						document.formulario.Usuario.value				=	Usuario;
						document.formulario.DataCriacao.value 			= 	dateFormat(DataCriacao);
						document.formulario.LoginCriacao.value 			= 	LoginCriacao;
						document.formulario.DataAlteracao.value 		= 	dateFormat(DataAlteracao);
						document.formulario.LoginAlteracao.value		= 	LoginAlteracao;
						document.formulario.Acao.value 					= 	'alterar';
						
						busca_login_usuario(IdGrupoUsuario,document.formulario.Usuario,Usuario);
						filtro_informativo_interno(IdAvisoForma);

						verificaAcao();
					}	
				}
				if(window.janela != undefined){
					window.janela.close();
				}
			}// fim do else
			// Fim de Carregando
			carregando(false);
		}//fim do if status
		return true;
	}
	xmlhttp.send(null);
}