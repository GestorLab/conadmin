	function verificaGrupo(valor){
		if(valor == '-1'){
			document.getElementById("titNovoGrupo").style.display	=	"block";
			document.getElementById("cpNovoGrupo").style.display	=	"block";
		}else{
			document.getElementById("titNovoGrupo").style.display	=	"none";
			document.getElementById("cpNovoGrupo").style.display	=	"none";
		}
	}
	function verificaAtributo(valor){
		if(valor == '-1'){
			document.getElementById("titNovoAtributo").style.display	=	"block";
			document.formulario.NovoAtributo.style.display				=	"block";
			document.getElementById("titOperador").style.marginLeft		=	"9px";
			document.formulario.Operador.style.marginLeft				=	"9px";
			document.formulario.Valor.style.width						=	"257px";
		}else{
			document.getElementById("titNovoAtributo").style.display	=	"none";
			document.formulario.NovoAtributo.style.display				=	"none";
			document.getElementById("titOperador").style.marginLeft		=	"7px";
			document.formulario.Operador.style.marginLeft				=	"7px";
			document.formulario.Valor.style.width						=	"424px";
		}
	}
	function inicia(){
		document.formulario.IdServidor.focus();
	}
	function excluir(id,Tipo,IdServidor,listar){
		if(listar == undefined){
			listar = '';
		}
		if(excluir_registro() == true){
			
			/*if(document.formulario != undefined){
				if(document.formulario.Acao.value == 'inserir'){
					return false;
				}
			}*/
			
   			var url = "files/excluir/excluir_radius.php?id="+id+"&Tipo="+Tipo+"&IdServidor="+IdServidor;
			
			call_ajax(url,function (xmlhttp){
				if(document.formulario != undefined){
					document.formulario.Erro.value = xmlhttp.responseText;
					
					if(listar == ""){
						if(parseInt(xmlhttp.responseText) == 7){
							document.formulario.Acao.value = 'inserir';
							url = 'cadastro_radius.php?Erro='+document.formulario.Erro.value;
							window.location.replace(url);
						} else{
							verificaErro();
						}
					} else{
						var numMsg = parseInt(xmlhttp.responseText);
						mensagens2(numMsg);
						
						if(numMsg == 7){
							var aux = 0;
							
							for(var i = 0; i < document.getElementById('tabelaRadius').rows.length; i++){
								if(id+"_"+Tipo == document.getElementById('tabelaRadius').rows[i].accessKey){
									document.getElementById('tabelaRadius').deleteRow(i);
									tableMultColor('tabelaRadius',document.filtro.corRegRand.value);
									aux=1;
									break;
								}//if
							}//for	
							
							if(aux = 1){
								document.getElementById("tabelaRadiusTotal").innerHTML = "Total: "+(document.getElementById('tabelaRadius').rows.length-2);
							}	
							
							if(id == document.formulario.id.value && Tipo == document.formulario.Tipo.value){
								document.formulario.id.value	= "";	
								document.formulario.Valor.value	= "";
								if(document.formulario.Local.value == "Radius"){
									document.formulario.IdServidor[0].selected	= true;
									document.formulario.Tipo[0].selected		= true;
									document.formulario.IdGrupo[0].selected		= true;
									document.formulario.Atributo[0].selected	= true;
									document.formulario.Operador[0].selected	= true;
								}
								document.formulario.Acao.value 	= 'inserir';
								
								mensagens();
								verificaAcao();
							}					
						}
					}
				} else{
					var numMsg = parseInt(xmlhttp.responseText);
					mensagens(numMsg);
					
					if(numMsg == 7){
						var aux = 0;
						
						for(var i = 0; i < document.getElementById('tableListar').rows.length; i++){
							if(id+"_"+Tipo+"_"+IdServidor == document.getElementById('tableListar').rows[i].accessKey){
								document.getElementById('tableListar').deleteRow(i);
								tableMultColor('tableListar',document.filtro.corRegRand.value);
								aux = 1;
								break;
							}//if
						}//for	
						
						if(aux = 1){
							document.getElementById("tableListarTotal").innerHTML = "Total: "+(document.getElementById('tableListar').rows.length-2);
						}							
					}//if
				}//else
			});
		}
	} 
	function validar(){
		if(document.formulario.IdServidor.value == ''){
			mensagens(1);
			document.formulario.IdServidor.focus();
			return false;
		}
		if(document.formulario.Tipo.value == ''){
			mensagens(1);
			document.formulario.Tipo.focus();
			return false;
		}
		if(document.formulario.IdGrupo.value == '0'){
			mensagens(1);
			document.formulario.IdGrupo.focus();
			return false;
		}else{
			if(document.formulario.IdGrupo.value == '-1'){
				if(document.formulario.NovoGrupo.value == ''){
					mensagens(1);
					document.formulario.NovoGrupo.focus();
					return false;
				}
			}
		}
		if(document.formulario.Atributo.value == ''){
			mensagens(1);
			document.formulario.Atributo.focus();
			return false;
		}else{
			if(document.formulario.Atributo.value == '-1'){
				if(document.formulario.NovoAtributo.value == ''){
					mensagens(1);
					document.formulario.NovoAtributo.focus();
					return false;
				}
			}
		}
		if(document.formulario.Operador.value == ''){
			mensagens(1);
			document.formulario.Operador.focus();
			return false;
		}
		if(document.formulario.Valor.value == ''){
			mensagens(1);
			document.formulario.Valor.focus();
			return false;
		}
		return true;
	}
	function buscaGrupo(IdServidor,GroupNameTemp){
		if(IdServidor == ""){
			IdServidor = 0;
			
			while(document.formulario.IdGrupo.options.length > 0){
				document.formulario.IdGrupo.options[0] = null;
			}
			
			addOption(document.formulario.IdGrupo,"","0");
			addOption(document.formulario.IdGrupo,"Novo Grupo","-1");
			return false;
		}
		
		if(GroupNameTemp == undefined){
			GroupNameTemp = "";
		}
		
	    var url = "xml/group_name.php?IdServidor="+IdServidor;
		
		call_ajax(url,function (xmlhttp){
			if(xmlhttp.responseText != 'false'){		
				while(document.formulario.IdGrupo.options.length > 0){
					document.formulario.IdGrupo.options[0] = null;
				}
				
				var nameNode, nameTextNode, IdGrupo;					
				
				addOption(document.formulario.IdGrupo,"","0");
				
				for(var i = 0; i < xmlhttp.responseXML.getElementsByTagName("GroupName").length; i++){
					nameNode = xmlhttp.responseXML.getElementsByTagName("GroupName")[i]; 
					nameTextNode = nameNode.childNodes[0];
					GroupName = nameTextNode.nodeValue;
					
					addOption(document.formulario.IdGrupo,GroupName,GroupName);
				}
				
				addOption(document.formulario.IdGrupo,"Novo Grupo","-1");

				if(GroupNameTemp == ""){
					GroupNameTemp = 0;
				}
				
				for(i = 0; i < document.formulario.IdGrupo.length;i++){
					if(document.formulario.IdGrupo[i].value == GroupNameTemp){
						document.formulario.IdGrupo[i].selected	=	true;
						break;
					}
				}
			}
		});	
	}
	function buscaAtributo(){
	    var url = "xml/codigo_interno.php?IdGrupoCodigoInterno=10001&Ordem=ValorCodigoInterno";
		
		call_ajax(url,function (xmlhttp){
			if(xmlhttp.responseText != 'false'){		
				while(document.formulario.Atributo.options.length > 0){
					document.formulario.Atributo.options[0] = null;
				}
				
				var nameNode, nameTextNode, Atributo;					
				
				addOption(document.formulario.Atributo,"","0");
				
				for(var i = 0; i < xmlhttp.responseXML.getElementsByTagName("ValorCodigoInterno").length; i++){
					nameNode = xmlhttp.responseXML.getElementsByTagName("ValorCodigoInterno")[i]; 
					nameTextNode = nameNode.childNodes[0];
					ValorCodigoInterno = nameTextNode.nodeValue;
					
					addOption(document.formulario.Atributo,ValorCodigoInterno,ValorCodigoInterno);
				}
				
				addOption(document.formulario.Atributo,'Novo Atributo','-1');
				document.formulario.Atributo[0].selected = true;
			}
		});	
	}
	function addGrupo(IdServidor,GroupNameTemp){
		if(IdServidor == ""){
			IdServidor = 0;
			
			while(document.filtro.filtro_grupo.options.length > 0){
				document.filtro.filtro_grupo.options[0] = null;
			}
			
			addOption(document.filtro.filtro_grupo,"Todos","");
			return false;
		}
		
		if(GroupNameTemp == undefined){
			GroupNameTemp	=	"";
		}
		
	    var url = "xml/group_name.php?IdServidor="+IdServidor;
		
		call_ajax(url,function (xmlhttp){
			if(xmlhttp.responseText != 'false'){		
				while(document.filtro.filtro_grupo.options.length > 0){
					document.filtro.filtro_grupo.options[0] = null;
				}
				
				var nameNode, nameTextNode, IdGrupo;					
				
				addOption(document.filtro.filtro_grupo,"Todos","");
				
				for(var i = 0; i < xmlhttp.responseXML.getElementsByTagName("GroupName").length; i++){
					nameNode = xmlhttp.responseXML.getElementsByTagName("GroupName")[i]; 
					nameTextNode = nameNode.childNodes[0];
					GroupName = nameTextNode.nodeValue;
					
					addOption(document.filtro.filtro_grupo,GroupName,GroupName);
				}
				
				if(GroupNameTemp == "") GroupNameTemp = 0;
				
				for(i = 0; i < document.filtro.filtro_grupo.length;i++){
					if(document.filtro.filtro_grupo[i].value == GroupNameTemp){
						document.filtro.filtro_grupo[i].selected	=	true;
						break;
					}
				}
			}
		});	
	}
	function addAtribute(){
	    var url = "xml/codigo_interno.php?IdGrupoCodigoInterno=10001";
		
		call_ajax(url,function (xmlhttp){
			if(xmlhttp.responseText != 'false'){	
				while(document.filtro.filtro_atributo.options.length > 0){
					document.filtro.filtro_atributo.options[0] = null;
				}
				
				var nameNode, nameTextNode, ValorCodigoInterno;					
				
				addOption(document.filtro.filtro_atributo,"Todos","");
				
				for(var i=0; i<xmlhttp.responseXML.getElementsByTagName("ValorCodigoInterno").length; i++){
					nameNode = xmlhttp.responseXML.getElementsByTagName("ValorCodigoInterno")[i]; 
					nameTextNode = nameNode.childNodes[0];
					ValorCodigoInterno = nameTextNode.nodeValue;
					
					addOption(document.filtro.filtro_atributo,ValorCodigoInterno,ValorCodigoInterno);
				}
				
				document.filtro.filtro_atributo[0].selected	= true;
			}
		});
	}
	function inserirAtributo(IdServidor){
		if(IdServidor != ""){
			var  url = "xml/radius_atributte_inserir.php?IdServidor="+IdServidor;
			
			call_ajax(url);
		}
	}
	function lista_atributos(IdServidor,IdGrupo){
		while(document.getElementById('tabelaRadius').rows.length > 2){
			document.getElementById('tabelaRadius').deleteRow(1);
		}
		
		if(IdServidor == undefined || IdServidor==''){
			IdServidor = 0;
		}
		
		if(IdGrupo == undefined || IdGrupo==''){
			IdGrupo = 0;
		}
		
	   	var url = "xml/radius.php?IdServidor="+IdServidor+"&IdGrupo="+IdGrupo;
		
		call_ajax(url,function (xmlhttp){
			if(xmlhttp.responseText == 'false'){
				document.getElementById('tabelaRadiusTotal').innerHTML = "Total: 0";	
			} else{
				document.getElementById("cp_radius").style.display = "block";
				
				var tam, linha, c0, c1, c2, c3, c4, c5, c6;
				var nameNode, nameTextNode, id,GroupName,Attribute,Value,op,DescTipo,Tipo;
				
				for(var i = 0; i < xmlhttp.responseXML.getElementsByTagName("id").length; i++){	
					nameNode = xmlhttp.responseXML.getElementsByTagName("id")[i]; 
					nameTextNode = nameNode.childNodes[0];
					id = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("GroupName")[i]; 
					nameTextNode = nameNode.childNodes[0];
					GroupName = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("Attribute")[i]; 
					nameTextNode = nameNode.childNodes[0];
					Attribute = nameTextNode.nodeValue;	
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("Value")[i]; 
					nameTextNode = nameNode.childNodes[0];
					Value = nameTextNode.nodeValue;	
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("op")[i]; 
					nameTextNode = nameNode.childNodes[0];
					op = nameTextNode.nodeValue;	
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("DescTipo")[i]; 
					nameTextNode = nameNode.childNodes[0];
					DescTipo = nameTextNode.nodeValue;	
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("Tipo")[i]; 
					nameTextNode = nameNode.childNodes[0];
					Tipo = nameTextNode.nodeValue;
					
					tam 	= document.getElementById('tabelaRadius').rows.length;
					linha	= document.getElementById('tabelaRadius').insertRow(tam-1);
					
					if((tam % 2) != 0){
						linha.style.backgroundColor = "#E2E7ED";
					}
					
					if(id == document.formulario.id.value && Tipo == document.formulario.Tipo.value){
						document.getElementById('tabelaRadius').rows[i+1].style.backgroundColor = "#A0C4EA";
					}
					
					
					linha.accessKey = id+"_"+Tipo; 
					
					c0	= linha.insertCell(0);
					c1	= linha.insertCell(1);	
					c2	= linha.insertCell(2);	
					c3	= linha.insertCell(3);
					c4	= linha.insertCell(4);
					c5	= linha.insertCell(5);
					c6	= linha.insertCell(6);
					
					linkIni	= 	"<a href='cadastro_radius.php?id="+id+"&Tipo="+Tipo+"&IdServidor="+IdServidor+"'>";	
					linkFim	=	"</a>";
					
					c0.innerHTML = (i+1);
					c0.style.padding =	"0 0 0 5px";
					
					c1.innerHTML = linkIni + GroupName + linkFim;
					c1.style.cursor  = "pointer";
					
					c2.innerHTML = linkIni + DescTipo + linkFim;
					c2.style.cursor  = "pointer";
					
					c3.innerHTML = linkIni + Attribute + linkFim;
					c3.style.cursor  = "pointer";
					
					c4.innerHTML = linkIni + op + linkFim;
					c4.style.cursor  = "pointer";
					
					c5.innerHTML = linkIni + Value + linkFim;
					c5.style.cursor  = "pointer";
					
					c6.innerHTML = "<img src='../../img/estrutura_sistema/ico_del.gif' alt='Excluir?' onClick=\"excluir("+id+",'"+Tipo+"',"+IdServidor+",'listar')\">";
					c6.style.cursor = "pointer";
					c6.style.textAlign = "center";
				}
				
				document.getElementById('tabelaRadiusTotal').innerHTML = "Total: "+i;
			}
		});
	}
	function carregando2(acao){
		if(acao == true){
			document.getElementById("carregando2").style.display = 'block';
		} else{
			document.getElementById("carregando2").style.display = 'none';
		}
		
		return true;
	}
	function mensagens2(n,Local){
		var msg='';
		var prioridade='';
		
		if(Local == '' || Local == undefined){
			Local = '';
		}
		
		if(n == 0){
			return help2(msg,prioridade);
		}
		
		var url = "../../xml/mensagens.xml";
		
		call_ajax(url,function (xmlhttp){
			nameNode = xmlhttp.responseXML.getElementsByTagName("msg"+n)[0]; 
			
			if(nameNode != null){
				nameTextNode = nameNode.childNodes[0];
				msg = nameTextNode.nodeValue;
			} else{
				msg = '';
			}
			
			nameNode = xmlhttp.responseXML.getElementsByTagName("pri"+n)[0]; 
			
			if(nameNode != null){
				nameTextNode = nameNode.childNodes[0];
				prioridade = nameTextNode.nodeValue;
			} else{
				prioridade = '';
			}
			
			return help2(msg,prioridade);
		});
	}
	function help2(msg,prioridade){
		if(msg != ''){
			scrollWindow("bottom");
		}
		
		document.getElementById('helpText2').innerHTML = msg;
		document.getElementById('helpText2').style.display = "block";
		
		switch (prioridade){
			case 'atencao':
				document.getElementById('helpText2').style.color = "#C10000";
				return true;
			default:
				document.getElementById('helpText2').style.color = "#004975";
				return true;
		}
	}