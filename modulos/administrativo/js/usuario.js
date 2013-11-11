	function excluir(Login){
		if(Login == ''){
			Login = document.formulario.Login.value;
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
    
   			url = "files/excluir/excluir_usuario.php?Login="+Login;
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
								url = 'cadastro_usuario.php?Erro='+document.formulario.Erro.value;
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
									if(Login == document.getElementById('tableListar').rows[i].accessKey){
										document.getElementById('tableListar').deleteRow(i);
										tableMultColor('tableListar');
										aux = 1;
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
	function validar(){
		if(document.formulario.Login.value==''){
			mensagens(1);
			document.formulario.Login.focus();
			return false;
		}
		if(document.formulario.IdPessoa.value==''){
			mensagens(1);
			document.formulario.IdPessoa.focus();
			return false;
		}
		if(document.formulario.Acao.value=='alterar' && document.formulario.Password.value!=""){
			if(document.formulario.Password.value.length < 8){
				document.formulario.Password.focus();
				mensagens(193);
				return false;
			}			
			if(document.formulario.NivelSenha.value == 1){
				document.formulario.Password.focus();
				mensagens(194);
				return false;
			}
			if(document.formulario.Password.value != document.formulario.Confirmacao.value){
				mensagens(11);
				document.formulario.Password.focus();
				document.formulario.Password.value = "";
				document.formulario.Confirmacao.value = "";
				document.getElementById("statusSenha").style.display = "none";
				return false;
			}
		}
		if(document.formulario.Acao.value=='inserir'){
			if(document.formulario.Password.value==''){
				document.formulario.Password.focus();
				mensagens(1);
				return false;
			}
			if(document.formulario.NivelSenha.value == 1){
				document.formulario.Password.focus();
				mensagens(194);
				return false;
			}
			if(document.formulario.Password.value.length < 8){
				document.formulario.Password.focus();
				mensagens(193);
				return false;
			}
			if(document.formulario.Password.value != document.formulario.Confirmacao.value){
				mensagens(11);
				document.formulario.Password.focus();
				document.formulario.Password.value = "";
				document.formulario.Confirmacao.value = "";
				return false;
			}
		} 
		if(document.formulario.IdAcessoSimultaneo.value==''){
			mensagens(1);
			document.formulario.IdAcessoSimultaneo.focus();
			return false;
		}
		if(document.formulario.IdStatus.value==''){
			mensagens(1);
			document.formulario.IdStatus.focus();
			return false;
		}
		if(document.formulario.InteracaoMikrotik.value==1){
			if(document.formulario.IdGrupoAcesso.value==''){
				document.formulario.IdGrupoAcesso.focus();
				mensagens(1);
				return false;
			}
			
			var errorTemp = 1;
			if(document.formulario.Servidor){
				if(document.formulario.Servidor.length != undefined){
					for(var i=0; i<document.formulario.Servidor.length; i++){
						if(document.formulario.Servidor[i].checked == 1 && !document.formulario.Servidor[i].disabled){
							errorTemp = 0;
							break;
						}
					}
				} else{
					if(document.formulario.Servidor.checked == 1 && !document.formulario.Servidor.disabled){
						errorTemp = 0;
					}
				}
			}
			if(errorTemp == 1){
				if(document.formulario.Servidor.length != undefined){
					for(var i=0; i<document.formulario.Servidor.length; i++){
						if(!document.formulario.Servidor[i].disabled){
							document.formulario.Servidor[i].focus();
							break;
						}
					}
				} else{
					if(!document.formulario.Servidor.disabled){
						document.formulario.Servidor.focus();
					}
				}
				mensagens(errorTemp);
				return false;
			}
		}
		
		return true;
	}
	function inicia(){
		status_inicial();
		document.formulario.Login.focus();
	}
	function busca_grupo_acesso_radius(GroupNameTemp){
		var nameNode, nameTextNode, url;
		
		if(GroupNameTemp == undefined){
			GroupNameTemp = '';
		}
		
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
	    
	   	url = "xml/usuario_grupo_acesso_radius.php";  	
		xmlhttp.open("GET", url,true);
		//alert(url);
		xmlhttp.onreadystatechange = function(){ 
	
			// Carregando...
			carregando(true);
	
			if(xmlhttp.readyState == 4){ 
				if(xmlhttp.status == 200){
					//alert(xmlhttp.responseText);
					if(xmlhttp.responseText == "false"){
						while(document.formulario.IdGrupoAcesso.options.length > 0){
							document.formulario.IdGrupoAcesso.options[0] = null;
						}
						addOption(document.formulario.IdGrupoAcesso,"","");
					} else{
						while(document.formulario.IdGrupoAcesso.options.length > 0){
							document.formulario.IdGrupoAcesso.options[0] = null;
						}
						addOption(document.formulario.IdGrupoAcesso,"","");	
						
						for(var i=0; i<xmlhttp.responseXML.getElementsByTagName("Id").length; i++){
							nameNode = xmlhttp.responseXML.getElementsByTagName("GroupName")[i]; 
							nameTextNode = nameNode.childNodes[0];
							var GroupName = nameTextNode.nodeValue;
							
							addOption(document.formulario.IdGrupoAcesso, GroupName, GroupName);					
						}
						
						if(GroupNameTemp!=''){
							for(ii=0;ii<document.formulario.IdGrupoAcesso.length;ii++){
								if(document.formulario.IdGrupoAcesso[ii].value == GroupNameTemp){
									document.formulario.IdGrupoAcesso[ii].selected = true;
									break;
								}
							}
						} else{
							document.formulario.IdGrupoAcesso[0].selected = true;
						}
					}				
				// Fim de Carregando
				}
				carregando(false);
			} 
			return true;
		}
		xmlhttp.send(null);					
	}

	function MarcaServidorRadius(campo){
		if(campo.checked && document.formulario.ServidorRadius.value.search(campo.value) == -1){
			document.formulario.ServidorRadius.value += '_'+campo.value;
		} else{
			document.formulario.ServidorRadius.value = '';
			if(document.formulario.Servidor.length != undefined){
				for(var i=0; i<document.formulario.Servidor.length; i++){
					if(document.formulario.Servidor[i].checked){
						document.formulario.ServidorRadius.value += '_'+document.formulario.Servidor[i].value;
					}
				}
			} else{
				if(document.formulario.Servidor.checked){
					document.formulario.ServidorRadius.value += '_'+document.formulario.Servidor.value;
				}
			}
		}
	}
	function grupo_usuario(){
		document.getElementById("tabelaGrupoUsuarioTotal").innerHTML = "Total: 0";
		
		while(document.getElementById('tabelaGrupoUsuario').rows.length > 2){
			document.getElementById('tabelaGrupoUsuario').deleteRow(1);
		}
				
		if(document.formulario.Login.value != ""){
			var url = "xml/grupo_usuario.php?Login="+document.formulario.Login.value;
			
			call_ajax(url,function (xmlhttp){
				if(xmlhttp.responseText != "false"){
					for(var i = 0; i < xmlhttp.responseXML.getElementsByTagName("Login").length; i++){
						var nameNode = xmlhttp.responseXML.getElementsByTagName("Login")[i]; 
						var nameTextNode = nameNode.childNodes[0];
						var Login = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("IdGrupoUsuario")[i]; 
						nameTextNode = nameNode.childNodes[0];
						var IdGrupoUsuario = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoGrupoUsuario")[i]; 
						nameTextNode = nameNode.childNodes[0];
						var DescricaoGrupoUsuario = nameTextNode.nodeValue;

						var tam = document.getElementById('tabelaGrupoUsuario').rows.length;
						var linha = document.getElementById('tabelaGrupoUsuario').insertRow(tam-1);
						var LinkIni1 = "";
						var LinkFim1 = "";
						var LinkIni2 = "";
						var LinkFim2 = "";
						
						LinkIni1 = "<a href='cadastro_grupo_usuario.php?IdGrupoUsuario="+IdGrupoUsuario+"' target='_blank'>";
						LinkFim1 = "</a>";
						LinkIni2 = "<a href='cadastro_grupo_usuario.php?IdGrupoUsuario="+IdGrupoUsuario+"' target='_blank'>";
						LinkFim2 = "</a>";
						
						if((tam % 2) != 0){
							linha.style.backgroundColor = "#E2E7ED";
						}
						
						linha.accessKey = Login; 
						
						var c0 = linha.insertCell(0);
						var c1 = linha.insertCell(1);
						
						c0.innerHTML = LinkIni1+IdGrupoUsuario+LinkFim1;
						c0.style.width = "40px";
						c0.className = "tableListarEspaco";
						
						c1.innerHTML = LinkIni2+DescricaoGrupoUsuario+LinkFim2;
					}
					
					document.getElementById("tabelaGrupoUsuarioTotal").innerHTML = "Total: "+i;
					document.getElementById("cp_grupo_usuario").style.display = "block";
					
					scrollWindow('bottom');
				}
			});
		}
	}
	function grupo_permissao(){
		document.getElementById("tabelaGrupoPermissaoTotal").innerHTML = "Total: 0";
		
		while(document.getElementById('tabelaGrupoPermissao').rows.length > 2){
			document.getElementById('tabelaGrupoPermissao').deleteRow(1);
		}
				
		if(document.formulario.Login.value != ""){
			var url = "xml/grupo_permissao.php?Login="+document.formulario.Login.value;
			
			call_ajax(url,function (xmlhttp){
				if(xmlhttp.responseText != "false"){
					for(var i = 0; i < xmlhttp.responseXML.getElementsByTagName("Login").length; i++){
						var nameNode = xmlhttp.responseXML.getElementsByTagName("Login")[i]; 
						var nameTextNode = nameNode.childNodes[0];
						var Login = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("IdGrupoPermissao")[i]; 
						nameTextNode = nameNode.childNodes[0];
						var IdGrupoPermissao = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoGrupoPermissao")[i]; 
						nameTextNode = nameNode.childNodes[0];
						var DescricaoGrupoPermissao = nameTextNode.nodeValue;

						var tam = document.getElementById('tabelaGrupoPermissao').rows.length;
						var linha = document.getElementById('tabelaGrupoPermissao').insertRow(tam-1);
						var LinkIni1 = "";
						var LinkFim1 = "";
						var LinkIni2 = "";
						var LinkFim2 = "";
						
						LinkIni1 = "<a href='cadastro_grupo_permissao.php?IdGrupoPermissao="+IdGrupoPermissao+"' target='_blank'>";
						LinkFim1 = "</a>";
						LinkIni2 = "<a href='cadastro_grupo_permissao.php?IdGrupoPermissao="+IdGrupoPermissao+"' target='_blank'>";
						LinkFim2 = "</a>";
						
						if((tam % 2) != 0){
							linha.style.backgroundColor = "#E2E7ED";
						}
						
						linha.accessKey = Login; 
						
						var c0 = linha.insertCell(0);
						var c1 = linha.insertCell(1);
						
						c0.innerHTML = LinkIni1+IdGrupoPermissao+LinkFim1;
						c0.style.width = "40px";
						c0.className = "tableListarEspaco";
						
						c1.innerHTML = LinkIni2+DescricaoGrupoPermissao+LinkFim2;
					}
					
					document.getElementById("tabelaGrupoPermissaoTotal").innerHTML = "Total: "+i;
					document.getElementById("cp_grupo_permissao").style.display = "block";
					
					scrollWindow('bottom');
				}
			});
		}
	}