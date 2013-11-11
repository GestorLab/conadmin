function busca_usuario_grupo_usuario(Login, IdGrupoUsuario, Erro, Local){
	if(Login == ''){
		Login = '¬';
	}
	if(IdGrupoUsuario == ''){
		IdGrupoUsuario = 0;
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
    
   	url = "../administrativo/xml/usuario_grupo_usuario.php?IdGrupoUsuario="+IdGrupoUsuario+"&Login="+Login;
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
					document.formulario.DataCriacao.value 		= "";
					document.formulario.LoginCriacao.value 		= "";
					document.formulario.Acao.value 				= 'inserir';
					
					// Fim de Carregando
					carregando(false);
				}else{
					nameNode = xmlhttp.responseXML.getElementsByTagName("IdGrupoUsuario")[0]; 
					nameTextNode = nameNode.childNodes[0];
					IdGrupoUsuario = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoGrupoUsuario")[0]; 
					nameTextNode = nameNode.childNodes[0];
					var DescricaoGrupoUsuario = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("Login")[0]; 
					nameTextNode = nameNode.childNodes[0];
					Login = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("NomeUsuario")[0]; 
					nameTextNode = nameNode.childNodes[0];
					var NomeUsuario = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("DataCriacao")[0]; 
					nameTextNode = nameNode.childNodes[0];
					var DataCriacao = nameTextNode.nodeValue;
			
					nameNode = xmlhttp.responseXML.getElementsByTagName("LoginCriacao")[0]; 
					nameTextNode = nameNode.childNodes[0];
					var LoginCriacao = nameTextNode.nodeValue;		
					
					document.formulario.Login.value 				= Login;
					document.formulario.NomeUsuario.value 			= NomeUsuario;
					document.formulario.IdGrupoUsuario.value 		= IdGrupoUsuario;
					document.formulario.DescricaoGrupoUsuario.value	= DescricaoGrupoUsuario;
					document.formulario.DataCriacao.value 			= dateFormat(DataCriacao);
					document.formulario.LoginCriacao.value 			= LoginCriacao;
					document.formulario.Acao.value 					= 'alterar';
				}
				if(document.getElementById("quadroBuscaUsuario") != null){
					if(document.getElementById("quadroBuscaUsuario").style.display == "block"){
						document.getElementById("quadroBuscaUsuario").style.display =	"none";
					}
				}
				verificaAcao();
			}
			// Fim de Carregando
			carregando(false);
		} 
		return true;
	}
	xmlhttp.send(null);
}
