	function validar(){
		if(document.formulario.IdGrupoPermissao.value==''){
			mensagens(1);
			document.formulario.IdGrupoPermissao.focus();
			return false;
		}
		if(document.formulario.IdLojaDestino.value==''){
			mensagens(1);
			document.formulario.IdLojaDestino.focus();
			return false;
		}
		return true;
	}
	
	function inicia(){
		document.formulario.IdGrupoPermissao.focus();
	}
	
	
	function listar_grupo_permissao(IdGrupoPermissao){
		
		while(document.getElementById('tabelaPermissao').rows.length > 2){
			document.getElementById('tabelaPermissao').deleteRow(1);
		}
		
		if(IdGrupoPermissao == ''){
			IdGrupoPermissao = 0;
		}
		
		var nameNode, nameTextNode, url, Condicao;
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
	    
	   	url = "xml/grupo_permissao_permissao.php?IdGrupoPermissao="+IdGrupoPermissao;
		xmlhttp.open("GET", url,true);

		xmlhttp.onreadystatechange = function(){ 
	
			// Carregando...
			carregando(true);
	
			if(xmlhttp.readyState == 4){ 
				if(xmlhttp.status == 200){
					
					if(xmlhttp.responseText == 'false'){
						document.getElementById('totaltabelaPermissao').innerHTML			=	"Total: 0";	
						
						// Fim de Carregando
						carregando(false);
					}else{
						var tam, linha, c0, c1, c2, c3, c4, c5, c6;
						var DescricaoLoja,DescricaoModulo,NomeOperacao,DescricaoSubOperacao;
						for(var i=0; i<xmlhttp.responseXML.getElementsByTagName("IdGrupoPermissao").length; i++){	
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoLoja")[i]; 
							nameTextNode = nameNode.childNodes[0];
							DescricaoLoja = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoModulo")[i]; 
							nameTextNode = nameNode.childNodes[0];
							DescricaoModulo = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("NomeOperacao")[i]; 
							nameTextNode = nameNode.childNodes[0];
							NomeOperacao = nameTextNode.nodeValue;	
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoSubOperacao")[i]; 
							nameTextNode = nameNode.childNodes[0];
							DescricaoSubOperacao = nameTextNode.nodeValue;	
							
							tam 	= document.getElementById('tabelaPermissao').rows.length;
							linha	= document.getElementById('tabelaPermissao').insertRow(tam-1);
							
							if(tam%2 != 0){
								linha.style.backgroundColor = "#E2E7ED";
							}
							
							linha.accessKey = i; 
							
							c0	= linha.insertCell(0);
							c1	= linha.insertCell(1);	
							c2	= linha.insertCell(2);	
							c3	= linha.insertCell(3);
							
							c0.innerHTML = DescricaoLoja;
							c0.style.padding =	"0 0 0 5px";
							
							c1.innerHTML = DescricaoModulo;
							
							c2.innerHTML = NomeOperacao;
							
							c3.innerHTML = DescricaoSubOperacao;
						}
						document.getElementById('totaltabelaPermissao').innerHTML		=	"Total: "+i;
						scrollWindow('bottom');
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
	
