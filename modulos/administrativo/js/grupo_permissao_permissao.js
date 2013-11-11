	function inicia(){
		document.formulario.IdGrupoPermissao.focus();
	}
	function verificaAcao(){
		if(document.formulario.IdGrupoPermissao.value != ''){
			document.formulario.bt_conexao.disabled 	= false;
		} else{
			document.formulario.bt_conexao.disabled 	= true;
		}
		
		document.formulario.bt_inserir.disabled 	= true;
		document.formulario.bt_alterar.disabled 	= true;
		document.formulario.bt_excluir.disabled 	= true;
	}
	function listar_grupo_permissao(IdLoja,IdGrupoPermissao){
		
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
	    
	   	url = "xml/grupo_permissao_permissao.php?IdLoja="+IdLoja+"&IdGrupoPermissao="+IdGrupoPermissao;
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
						
						document.getElementById('totaltabelaPermissao').innerHTML = "Total: "+i;
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
	function derrubar_conexao(IdGrupoPermissao){
		var nameNode, nameTextNode, url, xmlhttp = false;
		
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
	    
	   	url = "xml/derrubar_conexao.php?IdGrupoPermissao="+IdGrupoPermissao;
		xmlhttp.open("GET", url,true);
		xmlhttp.onreadystatechange = function(){ 
			// Carregando...
			carregando(true);
			if(xmlhttp.readyState == 4){ 
				if(xmlhttp.status == 200){
					nameNode = xmlhttp.responseXML.getElementsByTagName("Atualizar")[0]; 
					nameTextNode = nameNode.childNodes[0];
					var Atualizar = nameTextNode.nodeValue;
					
					if(Atualizar > 0){
						sair();
					}
				}
				// Fim de Carregando
				carregando(false);
			} 
			return true;
		}
		xmlhttp.send(null);
	}
	function help(msg,prioridade){
		document.getElementById('helpText').innerHTML = msg;
		document.getElementById('helpText').style.display = "block";
		
		switch (prioridade){
			case 'atencao':
				document.getElementById('helpText').style.color = "#C10000";
				return true;
			default:
				document.getElementById('helpText').style.color = "#004975";
				return true;
		}
	}