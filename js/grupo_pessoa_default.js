function janela_busca_grupo_pessoa(){
	janelas('../administrativo/busca_grupo_pessoa.php',360,283,250,100,'');
}

function busca_grupo_pessoa(IdGrupoPessoa, Erro, Local){
	if(IdGrupoPessoa == ''){
		IdGrupoPessoa = 0;
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
    
   	url = "../administrativo/xml/grupo_pessoa.php?IdGrupoPessoa="+IdGrupoPessoa;
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
					
					document.formulario.IdGrupoPessoa.value 			= '';
					document.formulario.DescricaoGrupoPessoa.value 		= '';
					
					if(Local == 'GrupoPessoa'){
						addParmUrl("marGrupoPessoa","IdGrupoPessoa",'');
						
						document.formulario.DataCriacao.value 		= "";
						document.formulario.LoginCriacao.value 		= "";
						document.formulario.DataAlteracao.value 	= "";
						document.formulario.LoginAlteracao.value	= "";
						document.formulario.Acao.value 				= 'inserir';
					}
					
					document.formulario.IdGrupoPessoa.focus();
					
					// Fim de Carregando
					carregando(false);
				}else{
					nameNode = xmlhttp.responseXML.getElementsByTagName("IdGrupoPessoa")[0]; 
					nameTextNode = nameNode.childNodes[0];
					IdGrupoPessoa = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoGrupoPessoa")[0]; 
					nameTextNode = nameNode.childNodes[0];
					var DescricaoGrupoPessoa = nameTextNode.nodeValue;
					
					document.formulario.IdGrupoPessoa.value				= IdGrupoPessoa;
					document.formulario.DescricaoGrupoPessoa.value 		= DescricaoGrupoPessoa;
					
					if(Local == 'GrupoPessoa'){
						addParmUrl("marGrupoPessoa","IdGrupoPessoa",IdGrupoPessoa);
						
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
						
						document.formulario.DataCriacao.value 		= dateFormat(DataCriacao);
						document.formulario.LoginCriacao.value 		= LoginCriacao;
						document.formulario.DataAlteracao.value 	= dateFormat(DataAlteracao);
						document.formulario.LoginAlteracao.value	= LoginAlteracao;
						document.formulario.Acao.value 				= 'alterar';
					}
				}
				if(document.getElementById('quadroBuscaGrupoPessoa') != null){
					if(document.getElementById('quadroBuscaGrupoPessoa').style.display	==	"block"){
						document.getElementById('quadroBuscaGrupoPessoa').style.display	= 	"none";
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
