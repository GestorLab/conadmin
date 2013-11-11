	function inicia(){
		document.formulario.IdAviso.focus();
	}
	function excluir(IdAviso){
		if(IdAviso == ''){
			var IdAviso = document.formulario.IdAviso.value;
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
    
   			url = "files/excluir/excluir_aviso.php?IdAviso="+IdAviso;
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
								url = 'cadastro_aviso.php?Erro='+document.formulario.Erro.value;
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
									if(IdAviso == document.getElementById('tableListar').rows[i].accessKey){
										document.getElementById('tableListar').deleteRow(i);
										tableMultColor('tableListar',document.filtro.corRegRand.value);
										aux=1;
										break;
									}//if
								}//for	
								if(aux=1){
									document.getElementById("tableListarTotal").innerHTML	=	"Total: "+(document.getElementById('tableListar').rows.length-2);
								}							
							}//if
						}//else
					}//if
					// Fim de Carregando
					carregando(false);
				}//if
				return true;
			}
			xmlhttp.send(null);
		}
	} 
	function validar(){
		var DataAtual = parseInt(document.formulario.DataAtualTemp.value);
		Data = document.formulario.Data.value;
		Data = Data.split('/');
		Data = parseInt(Data[2] + Data[1] + Data[0]);
		
		if(Data < DataAtual){
			mensagens(27);
			document.formulario.Data.focus();
			return false;
		}
		
		if(document.formulario.TituloAviso.value == ''){
			mensagens(1);
			document.formulario.TituloAviso.focus();
			return false;
		}
		if(document.formulario.ResumoAviso.value == ''){
			mensagens(1);
			document.formulario.ResumoAviso.focus();
			return false;
		}
		if(document.formulario.IdAvisoForma.value == ''){
			mensagens(1);
			document.formulario.IdAvisoForma.focus();
			return false;		
		}
		if(document.formulario.Hora.value != ""){
			if(document.formulario.Data.value == ''){	
				mensagens(1);
				document.getElementById('LabelData').style.color = '#C10000';
				document.formulario.Data.focus();
				return false;
			}else{
				if(isData(document.formulario.Data.value) == false){		
					mensagens(27);
					document.formulario.Data.focus();
					return false;
				}
			}
		}
		if(document.formulario.Data.value != ""){
			if(document.formulario.Hora.value == ''){				
				mensagens(1);
				document.getElementById('LabelHora').style.color = '#C10000';
				document.formulario.Hora.focus();
				return false;
			}else{
				if(isTime(document.formulario.Hora.value) == false){		
					mensagens(28);
					document.formulario.Hora.focus();
					return false;
				}
				document.formulario.Hora.style.color='#000000';
			}
		}
		if(document.formulario.Aviso.value == ''){
			mensagens(1);
			document.formulario.Aviso.focus();
			return false;
		}
		return true;
	}
	function validar_Data(id,campo){
		if(campo.value != ''){
			if(isData(campo.value) == false){		
				document.getElementById(id).style.backgroundColor	= '#C10000';
				document.getElementById(id).style.color				='#FFFFFF';
				mensagens(27);
				return false;
			}else{
				document.getElementById(id).style.backgroundColor	='#FFFFFF';
				document.getElementById(id).style.color				='#000';
				mensagens(0);
				if(document.formulario.Hora.value == ''){
					document.getElementById('LabelHora').style.color	='#C10000';
				}
				return true;
			}			
		}else{
			document.getElementById(id).style.backgroundColor	= '#FFFFFF';
			document.getElementById('LabelHora').style.color    ='#000';
		}

		if(campo.value == '' && document.formulario.Hora.value != ''){
			document.getElementById('LabelData').style.color		='#C10000';
		}else{
			document.getElementById('LabelData').style.color		='#000';
		}
	}
	function validar_Hora(id,campo){
		if(campo.value != ''){
			if(isTime(campo.value) == false){		
				document.getElementById(id).style.backgroundColor	= '#C10000';
				document.getElementById(id).style.color				='#FFFFFF';	
				mensagens(28);
				return false;
			}else{
				document.getElementById(id).style.backgroundColor	='#FFFFFF';
				document.getElementById(id).style.color				='#000';
				mensagens(0);
				if(document.formulario.Hora.value == ''){
					document.getElementById('LabelData').style.color	='#C10000';
				}
				return true;
			}				
		}else{
			document.getElementById(id).style.backgroundColor	= '#FFFFFF';
			document.getElementById('LabelData').style.color	='#000';
		}

		if(campo.value == '' && document.formulario.Data.value != ''){
			document.getElementById('LabelHora').style.color		='#C10000';
		}else{
			document.getElementById('LabelHora').style.color		='#000';
		}
	}
	function impedir_data_expiracao(Data){
		if(Data == '' || Data == undefined) {
			return;
		}
		var DataAtual = parseInt(document.formulario.DataAtualTemp.value);
		Data = Data.split('/');
		Data = parseInt(Data[2] + Data[1] + Data[0]);
		
		if(Data < DataAtual){
			mensagens(27);
			document.formulario.Data.focus();
			return false;
		}
	}
	function busca_login_usuario(IdGrupoUsuario,campo,IdUsuarioTemp){
		if(IdGrupoUsuario == ''){
			while(campo.options.length > 0){
				campo.options[0] = null;
			}
			
			if(document.filtro.filtro_usuario != undefined){
				addOption(campo,"Todos","");
			} else{
				addOption(campo,"","");
			}
			return false;
		}
		if(IdUsuarioTemp == undefined){
			IdUsuarioTemp = '';
		}
		
		url = "xml/usuario_grupo_usuario.php?IdGrupoUsuario="+IdGrupoUsuario;
		
		call_ajax(url,function (xmlhttp){
			if(xmlhttp.responseText == 'false'){
				while(campo.options.length > 0){
					campo.options[0] = null;
				}
				addOption(campo,"","");
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
					
					var Descricao	=	NomeUsuario.substr(0,50);	
					
					addOption(campo,Descricao,Login);
				}
				
				
				if(IdUsuarioTemp!=''){
					for(ii=0;ii<campo.length;ii++){
						if(campo[ii].value == IdUsuarioTemp){
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

	function filtro_informativo_interno(FormaAviso){
		// 3 = Informativo Interno
		if(FormaAviso == 3){
			document.getElementById('GrupoUsuarioBloco').style.display		= 'block';			
		}else{
			document.getElementById('GrupoUsuarioBloco').style.display		= 'none';
			document.formulario.IdGrupoUsuario.value						= '';
			document.formulario.Usuario.value									= '';
		}
	}