function busca_radius(id,Tipo,IdServidor,Erro,Local){
	if(id == '' || id == undefined){
		id = 0;
	}
	if(Tipo == '' || Tipo == undefined){
		Tipo = 0;
	}
	if(IdServidor == '' || IdServidor == undefined){
		IdServidor = 0;
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

   	url = "xml/radius.php?id="+id+"&Tipo="+Tipo+"&IdServidor="+IdServidor;
   	
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
					document.formulario.id.value					=	"";	
					document.formulario.IdServidor.value			=	"";	
					document.formulario.Tipo.value					=	"";
					document.formulario.IdGrupo.value 				= 	"";
					document.formulario.NovoGrupo.value 			= 	"";
					document.formulario.Atributo.value 				= 	"";
					document.formulario.Operador.value				= 	"";
					document.formulario.Valor.value					= 	"";
					document.formulario.Acao.value 					= 	'inserir';
					
					while(document.formulario.IdGrupo.options.length > 0){
						document.formulario.IdGrupo.options[0] = null;
					}
					while(document.getElementById('tabelaRadius').rows.length > 2){
						document.getElementById('tabelaRadius').deleteRow(1);
					}
					
					addOption(document.formulario.IdGrupo,"","0");
					addOption(document.formulario.IdGrupo,"Novo Grupo","-1");
					document.formulario.IdGrupo[0].selected	=	true;
							
				/*	addParmUrl("marRadius","id",'');
					addParmUrl("marRadius","IdServidor",'');
					addParmUrl("marRadius","Tipo",'');
				*/	
					
					document.getElementById("cp_radius").style.display	=	"none";
					document.formulario.IdServidor.focus();
					verificaAcao();
					
					// Fim de Carregando
					carregando(false);
				}else{
					nameNode = xmlhttp.responseXML.getElementsByTagName("id")[0]; 
					nameTextNode = nameNode.childNodes[0];
					var id = nameTextNode.nodeValue;	
				
					nameNode = xmlhttp.responseXML.getElementsByTagName("GroupName")[0]; 
					nameTextNode = nameNode.childNodes[0];
					var GroupName = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("op")[0]; 
					nameTextNode = nameNode.childNodes[0];
					var op = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("Attribute")[0]; 
					nameTextNode = nameNode.childNodes[0];
					var Attribute = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("Value")[0]; 
					nameTextNode = nameNode.childNodes[0];
					var Value = nameTextNode.nodeValue;					
				/*	
					addParmUrl("marRadius","id",id);
					addParmUrl("marRadius","IdServidor",IdServidor);
					addParmUrl("marRadius","Tipo",Tipo);
				*/	
					document.getElementById("titNovoGrupo").style.display	=	"none";
					document.getElementById("cpNovoGrupo").style.display	=	"none";
					document.getElementById("cp_radius").style.display		=	"block";
					
					while(document.getElementById('tabelaRadius').rows.length > 2){
						document.getElementById('tabelaRadius').deleteRow(1);
					}
										
					buscaGrupo(IdServidor,GroupName);
					lista_atributos(IdServidor,GroupName);
					
					document.formulario.id.value					=	id;	
					document.formulario.Tipo.value					=	Tipo;	
					document.formulario.IdServidor.value			=	IdServidor;						
					document.formulario.Operador.value 				= 	op;
					document.formulario.Atributo.value 				= 	Attribute;
					document.formulario.Valor.value 				= 	Value;
					document.formulario.NovoGrupo.value				= 	"";
					document.formulario.Acao.value 					= 	'alterar';
					
					
					verificaAcao();
				}	
				
			}
			// Fim de Carregando
			carregando(false);
		} 
		return true;
	}
	xmlhttp.send(null);
}

