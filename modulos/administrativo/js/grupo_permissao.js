	function validar(){
		if(document.formulario.DescricaoGrupoPermissao.value==''){
			mensagens(1);
			document.formulario.DescricaoGrupoPermissao.focus();
			return false;
		}
		return true;
	}
	function inicia(){
		document.formulario.IdGrupoPermissao.focus();
	}
	function verificaAcao(){
		if(document.formulario != undefined){
			if(document.formulario.Acao.value=='inserir'){
				document.formulario.bt_conexao.disabled 	= true;
				document.formulario.bt_inserir.disabled 	= false;
				document.formulario.bt_alterar.disabled 	= true;
				document.formulario.bt_excluir.disabled 	= true;
			}
			if(document.formulario.Acao.value=='alterar'){			
				document.formulario.bt_conexao.disabled 	= false;
				document.formulario.bt_inserir.disabled 	= true;
				document.formulario.bt_alterar.disabled 	= false;
				document.formulario.bt_excluir.disabled 	= false;
			}
		}	
	}
	function excluir(IdGrupoPermissao,Login){
		if(IdGrupoPermissao == ''){
			IdGrupoPermissao = document.formulario.IdGrupoPermissao.value;
		}
		if(excluir_registro() == true){
			if(document.formulario != undefined){
				if(document.formulario.Acao.value == 'inserir'){
					return false;
				}
			}
			var xmlhttp   = false;
			if (window.XMLHttpRequest) { // Mozilla, Safari,...
    			xmlhttp = new XMLHttpRequest();
		        if(xmlhttp.overrideMimeType){
		    //    	xmlhttp.overrideMimeType('text/xml');
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
    
   			url = "files/excluir/excluir_grupo_permissao.php?IdGrupoPermissao="+IdGrupoPermissao;
			xmlhttp.open("GET", url,true);
	
			xmlhttp.onreadystatechange = function(){ 

				// Carregando...
				carregando(true);

				if(xmlhttp.readyState == 4){ 
					if(xmlhttp.status == 200){
						if(document.formulario != undefined){
							document.formulario.Erro.value = xmlhttp.responseText;
							if(parseInt(xmlhttp.responseText) == 7){
								document.formulario.Acao.value 	= 'inserir';
								url = 'cadastro_grupo_permissao.php?Erro='+document.formulario.Erro.value;
								window.location.replace(url);
							}else{
								verificaErro();
							}
						}else{
							var numMsg = parseInt(xmlhttp.responseText);
							mensagens(numMsg);
							if(numMsg == 7){
								var aux = 0;
								for(var i=0; i<document.getElementById('tableListar').rows.length; i++){
									if(IdGrupoPermissao == document.getElementById('tableListar').rows[i].accessKey){
										document.getElementById('tableListar').deleteRow(i);
										aux=1;
										break;
									}
								}	
								if(aux=1){
									document.getElementById("tableListarTotal").innerHTML	=	"Total: "+(document.getElementById('tableListar').rows.length-2);
								}							
							}
						}
					}
					// Fim de Carregando
					carregando(false);
				}
				return true;
			}
			xmlhttp.send(null);
		}
	} 
	function busca_usuario_grupo_permissao(IdGrupoPermissao,Erro){
		if(IdGrupoPermissao == undefined || IdGrupoPermissao==''){
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
	    
	   	url = "../administrativo/xml/lista_usuario_grupo_permissao.php?IdGrupoPermissao="+IdGrupoPermissao;
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
						while(document.getElementById('tabelaUsuarioGrupoPermissao').rows.length > 2){
							document.getElementById('tabelaUsuarioGrupoPermissao').deleteRow(1);
						}
						
						document.getElementById('cp_usuario_grupo_permissao').style.display	=	'none';
						
						// Fim de Carregando
						carregando(false);
					}else{
						while(document.getElementById('tabelaUsuarioGrupoPermissao').rows.length > 2){
							document.getElementById('tabelaUsuarioGrupoPermissao').deleteRow(1);
						}
						
						document.getElementById('cp_usuario_grupo_permissao').style.display	=	'block';
						var tam, linha, c0, c1, c2, c3, c4, c5;
						
						for(var i=0; i<xmlhttp.responseXML.getElementsByTagName("IdGrupoPermissao").length; i++){
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("Login")[i]; 
							nameTextNode = nameNode.childNodes[0];
							var Login = nameTextNode.nodeValue;	
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("Nome")[i]; 
							nameTextNode = nameNode.childNodes[0];
							var Nome = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("Email")[i]; 
							nameTextNode = nameNode.childNodes[0];
							var Email = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("Status")[i]; 
							nameTextNode = nameNode.childNodes[0];
							var Status = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("Color")[i]; 
							nameTextNode = nameNode.childNodes[0];
							var Color = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("Data")[i]; 
							nameTextNode = nameNode.childNodes[0];
							var Data = nameTextNode.nodeValue;
							
							Data	= dateFormat(Data);
							
							tam 	= document.getElementById('tabelaUsuarioGrupoPermissao').rows.length;
							linha	= document.getElementById('tabelaUsuarioGrupoPermissao').insertRow(tam-1);
							
							if(tam%2 != 0){
								linha.style.backgroundColor = "#E2E7ED";
							}
							
							linha.style.backgroundColor = Color;
							
							linha.accessKey = Login;//define o login como chave de acesso 
							
							c0	= linha.insertCell(0);
							c1	= linha.insertCell(1);	
							c2	= linha.insertCell(2);	
							c3	= linha.insertCell(3);
							c4	= linha.insertCell(4);
							c5 	= linha.insertCell(5);
							
							var linkIni = "<a href='cadastro_usuario.php?Login="+Login+"'>";
							var linkFim = "</a>";
							
							c0.innerHTML		= linkIni + Login + linkFim;
							c0.style.cursor		= "default";
							c0.style.padding	= "0 6px 0 5px";
							c0.style.width		= "111px";
							
							c1.innerHTML		= linkIni + Nome + linkFim;
							c1.style.cursor		= "default";
							c1.style.padding	= "0 6px 0 0";
							
							c2.innerHTML		= linkIni + Email + linkFim;
							c2.style.cursor		= "default";
							c2.style.padding	= "0 6px 0 0";
							
							c3.innerHTML		= linkIni + Data + linkFim;
							c3.style.cursor  	= "default";
							c3.style.padding	= "0 6px 0 0";
							c3.style.width		= "141px";
							
							c4.innerHTML		= linkIni + Status +linkFim;
							c4.style.cursor		= "default";
							c4.style.padding	= "0 6px 0 0";
							c4.style.width		= "88px";
							
							c5.innerHTML		= "<img src='../../img/estrutura_sistema/ico_del.gif' alt='Excluir?' title='Excluir?' onClick=\"excluir_usuario("+IdGrupoPermissao+",'"+Login+"');\">";
							c5.style.cursor = "pointer";
							c5.style.textAlign	= "right";
						}
						document.getElementById('tabelaUsuarioGrupoPermissaoTotal').innerHTML = "Total: "+i;
						scrollWindow('bottom');
					}
				}
				// Fim de Carregando
				carregando(false);
			} 
			return true;
		}
		xmlhttp.send(null);
	}
	function excluir_usuario(IdGrupoPermissao, Login){
		if(IdGrupoPermissao == '' || IdGrupoPermissao){
			IdGrupoPermissao = document.formulario.IdGrupoPermissao.value;
		}
		
		if(excluir_registro() == true){
			var xmlhttp   = false;
			
			if (window.XMLHttpRequest) { // Mozilla, Safari,...
    			xmlhttp = new XMLHttpRequest();
		        if(xmlhttp.overrideMimeType){
		    //    	xmlhttp.overrideMimeType('text/xml');
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
    		
   			url = "files/excluir/excluir_usuario_grupo_permissao.php?IdGrupoPermissao="+IdGrupoPermissao+"&Login="+Login;
			xmlhttp.open("GET", url,true);
			xmlhttp.onreadystatechange = function(){ 
				// Carregando...
				carregando(true);
				if(xmlhttp.readyState == 4){ 
					if(xmlhttp.status == 200){
						var numMsg = parseInt(xmlhttp.responseText);
						mensagens2(numMsg);
						
						if(numMsg == 37){
							var aux = 0;
							for(var i=0; i<document.getElementById("tabelaUsuarioGrupoPermissao").rows.length; i++){
								if(Login == document.getElementById("tabelaUsuarioGrupoPermissao").rows[i].accessKey){
									document.getElementById("tabelaUsuarioGrupoPermissao").deleteRow(i);
									//tableMultColor("tabelaUsuarioGrupoPermissao",document.filtro.corRegRand.value);
									aux=1;
									break;
								}
							}
							
							var tam = (document.getElementById('tabelaUsuarioGrupoPermissao').rows.length-2);
							
							if(tam < 1){
								document.getElementById("cp_usuario_grupo_permissao").style.display = "none";
							}
							
							if(aux=1){
								document.getElementById("tabelaUsuarioGrupoPermissaoTotal").innerHTML	= "Total: "+tam;
							}
						}
					}
				}
				// Fim de Carregando
				carregando(false);
				return true;
			}
			xmlhttp.send(null);
		}
	}
	function mensagens2(n){
		var msg='';
		var prioridade='';
		var xmlhttp   = false;
		
		if(n == 0){
			document.getElementById('helpText2').style.display = "none";
		}
		
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
	    
		url = "../../xml/mensagens.xml";
   		xmlhttp.open("GET", url,true);
   		xmlhttp.onreadystatechange = function(){ 
   			if(xmlhttp.readyState == 4){ 
				if(xmlhttp.status == 200){
					nameNode = xmlhttp.responseXML.getElementsByTagName("msg"+n)[0]; 
					if(nameNode != null){
						nameTextNode = nameNode.childNodes[0];
						msg = nameTextNode.nodeValue;
					}else{
						msg = '';
					}
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("pri"+n)[0]; 
					if(nameNode != null){
						nameTextNode = nameNode.childNodes[0];
						prioridade = nameTextNode.nodeValue;
					}else{
						prioridade = '';
					}
					
					if(msg!=''){
						scrollWindow("bottom");
					}
					document.getElementById('helpText2').innerHTML = msg;
					document.getElementById('helpText2').style.display = "block";
					document.getElementById('helpText').style.display = "none";
					
					switch (prioridade){
						case 'atencao':
							document.getElementById('helpText2').style.color = "#C10000";
							break;
						default:
							document.getElementById('helpText2').style.color = "#004975";
					}
				}
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