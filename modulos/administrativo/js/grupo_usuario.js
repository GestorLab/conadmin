	function validar(){
		if(document.formulario.DescricaoGrupoUsuario.value==''){
			mensagens(1);
			document.formulario.DescricaoGrupoUsuario.focus();
			return false;
		}
		if(document.formulario.OrdemServico.value==''){
			mensagens(1);
			document.formulario.OrdemServico.focus();
			return false;
		}
		return true;
	}
	
	function inicia(){
		document.formulario.IdGrupoUsuario.focus();
	}
	
	function excluir(IdGrupoUsuario){
		if(IdGrupoUsuario == ''){
			IdGrupoUsuario = document.formulario.IdGrupoUsuario.value;
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
    
   			url = "files/excluir/excluir_grupo_usuario.php?IdGrupoUsuario="+IdGrupoUsuario;
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
								url = 'cadastro_grupo_usuario.php?Erro='+document.formulario.Erro.value;
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
									if(IdGrupoUsuario == document.getElementById('tableListar').rows[i].accessKey){
										document.getElementById('tableListar').deleteRow(i);
										//tableMultColor('tableListar',document.filtro.corRegRand.value);
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
	function busca_usuario_grupo_usuario(IdGrupoUsuario,Erro){
		if(IdGrupoUsuario == undefined || IdGrupoUsuario==''){
			IdGrupoUsuario = 0;
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
	    
	   	url = "../administrativo/xml/lista_usuario_grupo_usuario.php?IdGrupoUsuario="+IdGrupoUsuario;
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
						while(document.getElementById('tabelaUsuarioGrupoUsuario').rows.length > 2){
							document.getElementById('tabelaUsuarioGrupoUsuario').deleteRow(1);
						}
						
						document.getElementById('cp_usuario_grupo_usuario').style.display	=	'none';
						
						// Fim de Carregando
						carregando(false);
					}else{
						while(document.getElementById('tabelaUsuarioGrupoUsuario').rows.length > 2){
							document.getElementById('tabelaUsuarioGrupoUsuario').deleteRow(1);
						}
						
						document.getElementById('cp_usuario_grupo_usuario').style.display	=	'block';
						var tam, linha, c0, c1, c2, c3, c4;
						
						for(var i=0; i<xmlhttp.responseXML.getElementsByTagName("IdGrupoUsuario").length; i++){
							
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
							
							tam 	= document.getElementById('tabelaUsuarioGrupoUsuario').rows.length;
							linha	= document.getElementById('tabelaUsuarioGrupoUsuario').insertRow(tam-1);
							
							if(tam%2 != 0){
								linha.style.backgroundColor = "#E2E7ED";
							}
							
							linha.style.backgroundColor = Color;
							
							linha.accessKey = Login; 
							
							c0	= linha.insertCell(0);
							c1	= linha.insertCell(1);	
							c2	= linha.insertCell(2);	
							c3	= linha.insertCell(3);
							c4	= linha.insertCell(4);
							c5	= linha.insertCell(5);
							
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
							
							c4.innerHTML		= linkIni + Status + linkFim;
							c4.style.cursor		= "default";
							c4.style.padding	= "0 6px 0 0";
							c4.style.width		= "88px";
							
//							c5.innerHTML		= "<img src='../../img/estrutura_sistema/ico_del.gif' alt='Excluir?' title='Excluir?' onClick=\"excluir("+IdServico+","+QtdMes+", 'lista');\">";
							c5.innerHTML		= "<img src='../../img/estrutura_sistema/ico_del.gif' alt='Excluir?' title='Excluir?' onClick=\"excluir_usuario("+IdGrupoUsuario+",'"+Login+"');\">";
							c5.style.textAlign	= "center";
							c5.style.cursor		= "pointer";
						}
						document.getElementById('tabelaUsuarioGrupoUsuarioTotal').innerHTML = "Total: "+i;
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
	function excluir_usuario(IdGrupoUsuario, Login){
		if(IdGrupoUsuario == '' || IdGrupoUsuario){
			IdGrupoUsuario = document.formulario.IdGrupoUsuario.value;
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
    		
   			url = "files/excluir/excluir_usuario_grupo.php?IdGrupoUsuario="+IdGrupoUsuario+"&Login="+Login;
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
							for(var i=0; i<document.getElementById("tabelaUsuarioGrupoUsuario").rows.length; i++){
								if(Login == document.getElementById("tabelaUsuarioGrupoUsuario").rows[i].accessKey){
									document.getElementById("tabelaUsuarioGrupoUsuario").deleteRow(i);
									//tableMultColor("tabelaUsuarioGrupoUsuario",document.filtro.corRegRand.value);
									aux=1;
									break;
								}
							}
							
							var tam = (document.getElementById('tabelaUsuarioGrupoUsuario').rows.length-2);
							
							if(tam < 1){
								document.getElementById("cp_usuario_grupo_usuario").style.display = "none";
							}
							
							if(aux=1){
								document.getElementById("tabelaUsuarioGrupoUsuarioTotal").innerHTML	= "Total: "+tam;
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
	function busca_login_supervisor(IdGrupoUsuario,campo){
		url = "xml/usuario_grupo_usuario.php?IdGrupoUsuario="+IdGrupoUsuario;
				
		call_ajax(url, function(xmlhttp){
			if(xmlhttp.responseText == 'false'){
				while(campo.options.length > 0){
					campo.options[0] = null;
				}
			}else{
				while(campo.options.length > 0){
					campo.options[0] = null;
				}
				if(document.filtro.filtro_usuario != undefined){
					addOption(campo,"Todos","");
				}else{
					addOption(campo,"","");
				}
					
				for(var i=0; i<xmlhttp.responseXML.getElementsByTagName("Login").length; i++){
					var nameNode = xmlhttp.responseXML.getElementsByTagName("Login")[i]; 
					var nameTextNode = nameNode.childNodes[0];
					var Login = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("NomeUsuario")[i]; 
					nameTextNode = nameNode.childNodes[0];
					var NomeUsuario = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("UltimaAtendimento")[i]; 
					nameTextNode = nameNode.childNodes[0];
					var UltimaAtendimento = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("LoginSupervisor")[i]; 
					nameTextNode = nameNode.childNodes[0];
					var LoginSupervisor = nameTextNode.nodeValue;

					var Descricao = NomeUsuario.substr(0,50);
					
					Descricao += UltimaAtendimento;
					
					addOption(campo,Descricao,Login);
				}
				if(LoginSupervisor != ''){
					for(ii=0;ii<campo.length;ii++){
						if(campo[ii].value == LoginSupervisor){
							campo[ii].selected = true;
							break;
						}
					}
				}else{
					campo[0].selected = true;
				}						
			}
		});
	}