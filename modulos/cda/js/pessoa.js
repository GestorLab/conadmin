	function inicia(){
		if(document.getElementById("coluna1main").offsetWidth < 260){
			document.getElementById("coluna1main").style.width = 260;
		}
		
		status_inicial();
		document.formulario.Nome.focus();
	}
	function validar(){
		if(document.formulario.TipoPessoa.value == '1'){
			if(document.formulario.Nome.value == ''){
				mensagem(9);
				document.formulario.Nome.focus();
				return false;
			}
			
			if(document.formulario.RazaoSocial.value == ''){
				mensagem(10);
				document.formulario.RazaoSocial.focus();
				return false;
			}
			
			if(document.formulario.NomeRepresentante.value == ''){
				mensagem(11);
				document.formulario.NomeRepresentante.focus();
				return false;
			}
			
			if(document.formulario.RG_IE.value != '' && !isIE(document.formulario.RG_IE.value, document.formulario.SiglaEstado.value)){
				mensagem(66);
				document.formulario.RG_IE.focus();
				return false;
			}
			
			if(document.formulario.DataNascimento.value != ''){
				if(isData(document.formulario.DataNascimento.value) == false){
					mensagem(13);
					document.formulario.DataNascimento.focus();
					return false;
				}
			}
		}else{
			if(document.formulario.Nome.value == ''){
				mensagem(8);
				document.formulario.Nome.focus();
				return false;
			}

			if(document.formulario.Sexo.value == '' && document.formulario.Sexo_Obrigatorio.value == 1){
				mensagem(73);
				document.formulario.Sexo.focus();
				return false;
			}
			
			if(document.formulario.DataNascimento.value != ''){
				if(isData(document.formulario.DataNascimento.value) == false){
					mensagem(13);
					document.formulario.DataNascimento.focus();
					return false;
				}
			} else if(document.formulario.DataNascimento_Obrigatorio.value == 1){
				mensagem(62);
				document.formulario.DataNascimento.focus();
				return false;
			}

			if(document.formulario.EstadoCivil.value == '' && document.formulario.EstadoCivil_Obrigatorio.value == 1){
				mensagem(74);
				document.formulario.EstadoCivil.focus();
				return false;
			}
			
			if(document.formulario.RG_IE.value == '' && document.formulario.Rg_Obrigatorio.value == 1){
				mensagem(75);
				document.formulario.RG_IE.focus();
				return false;
			}

			if(document.formulario.RG_IE.value != '' && document.formulario.OrgaoExpedidor.value == ''){
				mensagem(76);
				document.formulario.OrgaoExpedidor.focus();
				return false;
			}

			if(document.formulario.NomeMae.value == '' && document.formulario.NomeMae_Obrigatorio.value == 1){
				mensagem(78);
				document.formulario.NomeMae.focus();
				return false;
			}

			if(document.formulario.NomePai.value == '' && document.formulario.NomePai_Obrigatorio.value == 1){
				mensagem(77);
				document.formulario.NomePai.focus();
				return false;
			}
			
			if(document.formulario.EstadoCivil.value == 2 && document.formulario.NomeConjugue_Obrigatorio.value == 1 && document.formulario.NomeConjugue.value == ""){
				mensagem(79);
				document.formulario.NomeConjugue.focus();
				return false;
			}
		}
		
		if(document.formulario.Telefone_Obrigatorio.value == '1'){
			if(document.formulario.Telefone1.value == '' && document.formulario.Telefone2.value == '' && document.formulario.Telefone3.value == '' && document.formulario.Celular.value == '' && document.formulario.Fax.value == ''){
				mensagem(20);
				document.formulario.Telefone1.focus();
				return false;
			}
		}
		
		if(document.formulario.Email.value != ''){
			var temp = document.formulario.Email.value.split(';');
			var i = 0;
			
			while(temp[i]!= '' && temp[i]!= undefined){
				temp[i]	= ignoreSpaces(temp[i]);
				if(validar_Email(temp[i], "Email") == false){
					return false;
				}
				i++;	
			}
		} else if(document.formulario.Email.value == ''){
			mensagem(81);
			document.formulario.Email.focus();
			return false;
		}
		
		if(document.formulario.CampoExtra1 != undefined){
			if(document.formulario.CampoExtra1.value == '' && document.formulario.CampoExtra1Obrigatorio.value == "S"){
				alert("Atenção!\r\n"+document.getElementById("titCampoExtra1").innerHTML+" - Campo obrigatorio.");
				document.formulario.CampoExtra1.focus();
				return false;
			}
		}
		
		if(document.formulario.CampoExtra2 != undefined){
			if(document.formulario.CampoExtra2.value == '' && document.formulario.CampoExtra2Obrigatorio.value == "S"){
				alert("Atenção!\r\n"+document.getElementById("titCampoExtra2").innerHTML+" - Campo obrigatorio.");
				document.formulario.CampoExtra2.focus();
				return false;
			}
		}
		
		if(document.formulario.CampoExtra3 != undefined){
			if(document.formulario.CampoExtra3.value == '' && document.formulario.CampoExtra3Obrigatorio.value == "S"){
				alert("Atenção!\r\n"+document.getElementById("titCampoExtra3").innerHTML+" - Campo obrigatorio.");
				document.formulario.CampoExtra3.focus();
				return false;
			}
		}
		
		if(document.formulario.CampoExtra4 != undefined){
			if(document.formulario.CampoExtra4.value == '' && document.formulario.CampoExtra4Obrigatorio.value == "S"){
				alert("Atenção!\r\n"+document.getElementById("titCampoExtra4").innerHTML+" - Campo obrigatorio.");
				document.formulario.CampoExtra4.focus();
				return false;
			}
		}
		
		if(document.formulario.CEP_EnderecoPrincipal.value==''){
			mensagem(14);
			document.formulario.CEP_EnderecoPrincipal.focus();
			return false;
		}
		
		if(document.formulario.Endereco_EnderecoPrincipal.value==''){
			mensagem(15);
			document.formulario.Endereco_EnderecoPrincipal.focus();
			return false;
		}
		
		if(document.formulario.Bairro_EnderecoPrincipal.value==''){
			mensagem(16);
			document.formulario.Bairro_EnderecoPrincipal.focus();
			return false;
		}
		
		if(document.formulario.Numero_EnderecoPrincipal.value=='' && document.formulario.Numero_Obrigatorio.value==1){
			mensagem(63);
			document.formulario.Numero_EnderecoPrincipal.focus();
			return false;
		}
		
		if(document.formulario.IdEstado_EnderecoPrincipal.value==''){
			mensagem(18);
			document.formulario.IdEstado_EnderecoPrincipal.focus();
			return false;
		}
		
		if(document.formulario.IdCidade_EnderecoPrincipal.value==''){
			mensagem(19);
			document.formulario.IdCidade_EnderecoPrincipal.focus();
			return false;
		}
		
		if(document.formulario.Senha_Obrigatorio.value==1){
			if(document.formulario.Senha.value==''){
				mensagem(25);
				document.formulario.Senha.focus();
				return false;
			} else if(document.formulario.Senha.value!=document.formulario.Confirmacao.value){
				mensagem(23);
				document.formulario.Confirmacao.focus();
				return false;
			}
		}
		
		return true;
	}
	function busca_estado(descricao, IdPais, IdEstadoTemp, IdCidadeTemp){
		if(IdPais == '')
			IdPais = 0;
		
		if(IdEstadoTemp == undefined)
			IdEstadoTemp = '';
		
		if(IdCidadeTemp == undefined)
			IdCidadeTemp = '';
		
		var xmlhttp = false;
		var nameNode, nameTextNode, url;
		var campoEstado = eval("document.formulario.IdEstado"+descricao);
		var campoCidade = eval("document.formulario.IdCidade"+descricao);
		
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
		
		url = "xml/estado.php?IdPais="+IdPais;
		xmlhttp.open("GET", url,true);
		xmlhttp.onreadystatechange = function(){ 
			// Carregando...
			carregando(true);
			
			if(xmlhttp.readyState == 4){ 
				if(xmlhttp.status == 200){
					if(xmlhttp.responseText == 'false'){
						while(campoEstado.options.length > 0){
							campoEstado.options[0] = null;
						}
						
						addOption(campoEstado, '', '');
						
						while(campoCidade.options.length > 0){
							campoCidade.options[0] = null;
						}
						
						addOption(campoCidade, '', '');
					}else{
						var IdEstado, NomeEstado;
						
						while(campoEstado.options.length > 0){
							campoEstado.options[0] = null;
						}
							
						addOption(campoEstado, '', '');
						
						for(var i = 0; i < xmlhttp.responseXML.getElementsByTagName("IdEstado").length; i++){
							nameNode = xmlhttp.responseXML.getElementsByTagName("IdEstado")[i]; 
							nameTextNode = nameNode.childNodes[0];
							IdEstado = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("NomeEstado")[i]; 
							nameTextNode = nameNode.childNodes[0];
							NomeEstado = nameTextNode.nodeValue;
							
							addOption(campoEstado, NomeEstado, IdEstado);
						}
						
						if(IdEstadoTemp == ""){
							campoEstado.options[0].selected = true;
						}else{
							for(var i = 0; i < campoEstado.length; i++){
								if(campoEstado.options[i].value == IdEstadoTemp){
									campoEstado.options[i].selected = true;
									i = campoEstado.length;
								}
							}
						}
						
						if(IdEstadoTemp == "" && IdCidadeTemp == ""){
							while(campoCidade.options.length > 0){
								campoCidade.options[0] = null;
							}
							
							addOption(campoCidade, '', '');
						}else{
							busca_cidade(descricao, IdPais, IdEstadoTemp, IdCidadeTemp)
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
	function busca_cidade(descricao, IdPais, IdEstado, IdCidadeTemp){
		if(IdPais == '')
			IdPais = 0;
		
		if(IdEstado == '' || IdEstado == undefined)
			IdEstado = 0;
		
		if(IdCidadeTemp == undefined)
			IdCidadeTemp = "";
		
		var xmlhttp = false;
		var nameNode, nameTextNode, url;
		var campoCidade = eval("document.formulario.IdCidade"+descricao);
		
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
		
		url = "xml/cidade.php?IdPais="+IdPais+"&IdEstado="+IdEstado;
		xmlhttp.open("GET", url,true);
		xmlhttp.onreadystatechange = function(){ 
			// Carregando...
			carregando(true);
			
			if(xmlhttp.readyState == 4){ 
				if(xmlhttp.status == 200){
					if(xmlhttp.responseText == 'false'){
						while(campoCidade.options.length > 0){
							campoCidade.options[0] = null;
						}
						
						addOption(campoCidade, '', '');
					}else{
						var SiglaEstado, IdCidade, NomeCidade;
						
						while(campoCidade.options.length > 0){
							campoCidade.options[0] = null;
						}
						
						addOption(campoCidade, '', '');
						
						for(var i=0; i<xmlhttp.responseXML.getElementsByTagName("IdCidade").length; i++){
							nameNode = xmlhttp.responseXML.getElementsByTagName("IdCidade")[i]; 
							nameTextNode = nameNode.childNodes[0];
							IdCidade = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("NomeCidade")[i]; 
							nameTextNode = nameNode.childNodes[0];
							NomeCidade = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("SiglaEstado")[i]; 
							nameTextNode = nameNode.childNodes[0];
							SiglaEstado = nameTextNode.nodeValue;
							
							addOption(campoCidade, NomeCidade, IdCidade);
						}
						
						if(campoCidade.name == "IdCidade_EnderecoPrincipal"){
							document.formulario.SiglaEstado.value = SiglaEstado;
						}
						
						if(IdCidadeTemp == ""){
							campoCidade.options[0].selected = true;
						}else{
							for(var i = 0; i < campoCidade.length; i++){
								if(campoCidade.options[i].value == IdCidadeTemp){
									campoCidade.options[i].selected = true;
									i = campoCidade.length;
								}
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
	function validar_Email(valor, id){
		if(valor == ''){
			document.getElementById(id).style.backgroundColor='#FFFFFF';
			document.getElementById(id).style.color = null;
			
			return false;
		}
		
		var temp = valor.split(';');
		var i = 0;
		
		while(temp[i] != '' && temp[i] != undefined){
			temp[i]	= ignoreSpaces(temp[i]);
			
			if(isEmail(temp[i]) == false){
				colorTemp = document.getElementById(id).style.backgroundColor;
				document.getElementById(id).style.backgroundColor = '#C10000';
				document.getElementById(id).style.color='#FFFFFF';
				
				mensagem(24);
				
				return false;
			}
			
			i++;
		}
		
		document.getElementById(id).style.backgroundColor='#FFFFFF';
		document.getElementById(id).style.color=null;
		
		return true;
	}
	function ocultar_campo(value){
		if(value){
			document.getElementById("cpEnderecoCobranca").style.display = "block";
		} else{
			document.getElementById("cpEnderecoCobranca").style.display = "none";
			document.formulario.CEP_EnderecoCobranca.value = '';
			document.formulario.Endereco_EnderecoCobranca.value = '';
			document.formulario.Numero_EnderecoCobranca.value = '';
			document.formulario.Complemento_EnderecoCobranca.value = '';
			document.formulario.Bairro_EnderecoCobranca.value = '';
			
			busca_estado('_EnderecoCobranca', document.formulario.IdPais_1.value);
		}
	}
	function busca_cep(descricao, CEP){
		if(CEP == ''){
			CEP = 0;
		}
		if(atualizar() == true){
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
		    
		   	url = "xml/cep.php?CEP="+CEP;
			xmlhttp.open("GET", url,true);
			
			xmlhttp.onreadystatechange = function(){ 
				// Carregando...
				carregando(true);
		
				if(xmlhttp.readyState == 4){ 
					if(xmlhttp.status == 200){
						if(xmlhttp.responseText != 'false'){
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("IdPais")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var IdPais = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("IdEstado")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var IdEstado = nameTextNode.nodeValue;
					
							nameNode = xmlhttp.responseXML.getElementsByTagName("IdCidade")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var IdCidade = nameTextNode.nodeValue;					
						
							nameNode = xmlhttp.responseXML.getElementsByTagName("Endereco")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var Endereco = nameTextNode.nodeValue;
						
							nameNode = xmlhttp.responseXML.getElementsByTagName("Bairro")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var Bairro = nameTextNode.nodeValue;
							
							eval("document.formulario.Endereco"+descricao+".value = Endereco; document.formulario.Bairro"+descricao+".value = Bairro;");
							
							busca_estado(descricao,IdPais,IdEstado,IdCidade);
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