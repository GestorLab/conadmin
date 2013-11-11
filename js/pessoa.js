	function inicia(){	
		scrollWindow('top');
		
		statusInicial();
		
		document.formulario.IdPessoa.focus();
		
		if(document.formulario.CPF_CNPJ_Obrigatorio.value == 1){
			document.getElementById('cp_CPF_CNPJ_Titulo').style.color	=	'#C10000';
		}else{
			document.getElementById('cp_CPF_CNPJ_Titulo').style.color	=	'#000000';
		}
	}
	function ativaPessoa(pessoa){
		// Seleciona o Tipo da pessoa
		for(var i=0; i<document.formulario.TipoPessoa.length; i++){
			if(document.formulario.TipoPessoa[i].value == pessoa){
				document.formulario.TipoPessoa[i].selected = true;
			}
		}
		document.getElementById('cp_CPF_CNPJ_Titulo').style.backgroundColor = '#FFF';
		
		if(document.getElementById('CPF_CNPJ_Obrigatorio').value == 1){
			document.getElementById('cp_CPF_CNPJ_Titulo').style.color='#C10000';	
		}else{
			document.getElementById('cp_CPF_CNPJ_Titulo').style.color='#000';	
		}
		// Dependendo do tipo da pessoa
		if(pessoa == 2){ //Pessoa Fisica
			// Campo CPF_CNPJ
			
			document.getElementById('cp_CPF_CNPJ_Titulo').innerHTML = "CPF";
			document.formulario.CPF_CNPJ.maxLength					= 14; 
			
			// Ocultar a Pessoa Juridica			
			document.getElementById('cp_Juridica').style.display = 'none';
			
			// Aparece o Pessoa Física			
			document.getElementById('cp_Fisica').style.display = 'block';
			
			// Habilita Tipo Usuario 
			document.formulario.TipoUsuario.disabled = false;
			
		}else{ //Pessoa Jurídica
			
			// Título do campo CPF_CNPJ
			document.getElementById('cp_CPF_CNPJ_Titulo').innerHTML = "CNPJ";
			document.formulario.CPF_CNPJ.maxLength					= 18; 
			
			//Aparece a Pessoa Jurídica
			document.getElementById('cp_Juridica').style.display = 'block';
			
			// Some o Pessoa Física			
			document.getElementById('cp_Fisica').style.display = 'none';
			
			// desabilita Tipo Usuario 
//			document.formulario.TipoUsuario.disabled = true;
		}
	}
	function validar_CPF_CNPJ(valor){
		if(valor == ''){
			return false;
		}
		var temp	=	valor.split('.');
		if(document.formulario.TipoPessoa.value == 2){
			if(temp[1] == undefined){
				inserir_mascara(valor,'cpf');
			}
			if(isCPF(valor) == false){
				document.getElementById('cp_CPF_CNPJ_Titulo').innerHTML = "CPF - Inválido";
				colorTemp = document.getElementById('cp_CPF_CNPJ_Titulo').style.backgroundColor;
				document.getElementById('cp_CPF_CNPJ_Titulo').style.backgroundColor = '#C10000';
				document.getElementById('cp_CPF_CNPJ_Titulo').style.color='#FFFFFF';
				document.formulario.CPF_CNPJ.focus();
				return false;
			}else{
				document.getElementById('cp_CPF_CNPJ_Titulo').innerHTML = "CPF";
				document.getElementById('cp_CPF_CNPJ_Titulo').style.backgroundColor='#FFFFFF';
				if(document.formulario.CPF_CNPJ_Obrigatorio.value == 1){
					document.getElementById('cp_CPF_CNPJ_Titulo').style.color	=	'#C10000';
				}else{
					document.getElementById('cp_CPF_CNPJ_Titulo').style.color	=	'#000000';
				}
				return true;
			}				
		}else{
			if(temp[1] == undefined){
				inserir_mascara(valor,'cnpj');
			}
			if(isCNPJ(valor) == false){
				document.getElementById('cp_CPF_CNPJ_Titulo').innerHTML = "CNPJ - Inválido";
				colorTemp = document.getElementById('cp_CPF_CNPJ_Titulo').style.backgroundColor;
				document.getElementById('cp_CPF_CNPJ_Titulo').style.backgroundColor = '#C10000';
				document.getElementById('cp_CPF_CNPJ_Titulo').style.color='#FFFFFF';
				document.formulario.CPF_CNPJ.focus();
				return false;
			}else{
				document.getElementById('cp_CPF_CNPJ_Titulo').innerHTML = "CNPJ";
				document.getElementById('cp_CPF_CNPJ_Titulo').style.backgroundColor='#FFFFFF';
				if(document.formulario.CPF_CNPJ_Obrigatorio.value == 1){
					document.getElementById('cp_CPF_CNPJ_Titulo').style.color	=	'#C10000';
				}else{
					document.getElementById('cp_CPF_CNPJ_Titulo').style.color	=	'#000000';
				}
				return true;
			}	
		}
	}
	function inserir_mascara(valor,tipo){
		if(valor == ''){
			return false;
		}
		if(tipo == 'cpf'){
			var retorno = valor.substr(0,3) + '.' + valor.substr(3,3) + '.' + valor.substr(6,3) + '-' + valor.substr(9,2);
		}else{
			var retorno = valor.substr(0,2) + '.' + valor.substr(2,3) + '.' + valor.substr(5,3) + '/' + valor.substr(8,4) + '-' + valor.substr(12,2);
		}
		document.formulario.CPF_CNPJ.value = retorno;
	}	
	function chama_mascara(campo,event){
		if(document.formulario.TipoPessoa.value == 2){
			return mascara(campo,event,'cpf');
		}else{
			return mascara(campo,event,'cnpj');
		}
	}
	function validar_Data(valor,campo){
		if(valor == ''){
			return false;
		}
		if(isData(valor) == false){				
			document.getElementById(campo).style.backgroundColor = '#C10000';
			document.getElementById(campo).style.color='#000';
			mensagens(27);
			return false;
		}else{
			document.getElementById(campo).style.backgroundColor='#FFFFFF';
			document.getElementById(campo).style.color='#000';
			mensagens(0);
			return true;
		}	
	}
	function validar_Email(valor,id){
		if(valor == ''){
			document.formulario.Enviar_Email.value			=	2;
			return false;
		}
		var temp = valor.split(';');
		var i = 0;
		while(temp[i]!= '' && temp[i]!= undefined){
			temp[i]	= ignoreSpaces(temp[i]);
			if(isEmail(temp[i]) == false){				
				colorTemp = document.getElementById(id).style.backgroundColor;
				document.getElementById(id).style.backgroundColor = '#C10000';
				document.getElementById(id).style.color='#FFFFFF';
				mensagens(12);
				return false;
				break;
			}
			i++;	
		}
		document.getElementById(id).style.backgroundColor='#FFFFFF';
		document.getElementById(id).style.color='#000000';
				
		mensagens(0);
		return true;
	}
	function busca_cob_pais(IdPais,Erro,Local){
		if(IdPais == ''){
			IdPais = 0;
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
	    
	   	url = "../administrativo/xml/pais.php?IdPais="+IdPais;
		xmlhttp.open("GET", url,true);
		
		xmlhttp.onreadystatechange = function(){ 

			// Carregando...
			carregando(true);

			if(xmlhttp.readyState == 4){ 
				if(xmlhttp.status == 200){

					if(Erro != false){
//						document.formulario.Erro.value = 0;
//						verificaErro();
					}
					if(xmlhttp.responseText == 'false'){
						
						document.formulario.Cob_IdPais.value 	= '';
						document.formulario.Cob_Pais.value		= '';
						
						document.formulario.Cob_IdPais.focus();

						// Fim de Carregando
						carregando(false);
					}else{
						nameNode = xmlhttp.responseXML.getElementsByTagName("IdPais")[0]; 
						nameTextNode = nameNode.childNodes[0];
						IdPais = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("NomePais")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var NomePais = nameTextNode.nodeValue;					
						
						document.formulario.Cob_IdPais.value	= IdPais;
						document.formulario.Cob_Pais.value 		= NomePais;
						
					}
					if(document.formulario.Cob_IdEstado != undefined){
						document.formulario.Cob_IdEstado.value 	= '';
						document.formulario.Cob_Estado.value	= '';
						
					}
					if(document.formulario.Cob_IdCidade != undefined){
						document.formulario.Cob_IdCidade.value 	= '';
						document.formulario.Cob_Cidade.value	= '';							
					}
					
					if(document.getElementById("quadroBuscaCobPais").style.display	==	"block"){
						vi_id('quadroBuscaCobPais', false);
					}
				}
				// Fim de Carregando
				carregando(false);
			} 
			return true;
		}
		xmlhttp.send(null);
		return true;
	}
	function busca_cob_estado(IdPais,IdEstado,Erro,Local){
		
		if(IdEstado == ''){
			IdEstado = 0;
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
	    
	   	url = "../administrativo/xml/estado.php?IdPais="+IdPais+"&IdEstado="+IdEstado;
		xmlhttp.open("GET", url,true);
		
		xmlhttp.onreadystatechange = function(){ 

			// Carregando...
			carregando(true);

			if(xmlhttp.readyState == 4){ 
				if(xmlhttp.status == 200){

					if(Erro != false){
//						document.formulario.Erro.value = 0;
//						verificaErro();
					}
					if(xmlhttp.responseText == 'false'){
						document.formulario.Cob_IdEstado.value	 	= '';
						document.formulario.Cob_Estado.value		= '';
						
						if(IdPais == ''){
							document.formulario.Cob_IdPais.focus();
						}else{
							document.formulario.Cob_IdEstado.focus();
						}
						// Fim de Carregando
						carregando(false);
					}else{
						nameNode = xmlhttp.responseXML.getElementsByTagName("IdEstado")[0]; 
						nameTextNode = nameNode.childNodes[0];
						IdEstado = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("NomeEstado")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var NomeEstado = nameTextNode.nodeValue;
											
						document.formulario.Cob_IdEstado.value	= IdEstado;
						document.formulario.Cob_Estado.value 	= NomeEstado;
						
					}
					if(document.formulario.Cob_IdCidade != undefined){
						document.formulario.Cob_IdCidade.value 	= '';
						document.formulario.Cob_Cidade.value	= '';							
					}
					if(document.getElementById("quadroBuscaCobEstado").style.display	==	"block"){
						vi_id('quadroBuscaCobEstado', false);
					}
				}
				// Fim de Carregando
				carregando(false);
			} 
			return true;
		}
		xmlhttp.send(null);
		return true;
	}
	function busca_cob_cidade(IdPais,IdEstado,IdCidade,Erro,Local){
		if(IdCidade == ''){
			IdCidade = 0;
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
	    
	   	url = "../administrativo/xml/cidade.php?IdPais="+IdPais+"&IdEstado="+IdEstado+"&IdCidade="+IdCidade;
		xmlhttp.open("GET", url,true);
		
		xmlhttp.onreadystatechange = function(){ 

			// Carregando...
			carregando(true);

			if(xmlhttp.readyState == 4){ 
				if(xmlhttp.status == 200){

					if(Erro != false){
//						document.formulario.Erro.value = 0;
//						verificaErro();
					}
					if(xmlhttp.responseText == 'false'){
						
						document.formulario.Cob_IdCidade.value 	= '';
						document.formulario.Cob_Cidade.value	= '';

						if(document.formulario.Cob_IdPais.value == ''){
							document.formulario.Cob_IdPais.focus();
						}else if(document.formulario.Cob_IdEstado.value == ''){
							document.formulario.Cob_IdEstado.focus();
						}					
						// Fim de Carregando
						carregando(false);
					}else{
						nameNode = xmlhttp.responseXML.getElementsByTagName("IdPais")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var IdPais = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("NomePais")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var NomePais = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("IdEstado")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var IdEstado = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("NomeEstado")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var NomeEstado = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("IdCidade")[0]; 
						nameTextNode = nameNode.childNodes[0];
						IdCidade = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("NomeCidade")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var NomeCidade = nameTextNode.nodeValue;					
						
						document.formulario.Cob_IdPais.value	= IdPais;
						document.formulario.Cob_Pais.value 		= NomePais;
						document.formulario.Cob_IdEstado.value	= IdEstado;
						document.formulario.Cob_Estado.value 	= NomeEstado;
						document.formulario.Cob_IdCidade.value	= IdCidade;
						document.formulario.Cob_Cidade.value 	= NomeCidade;
					}
					if(document.getElementById("quadroBuscaCobCidade").style.display	==	"block"){
						vi_id('quadroBuscaCobCidade', false);
					}
					verificaAcao();
				}
				// Fim de Carregando
				carregando(false);
			} 
			return true;
		}
		xmlhttp.send(null);
		return true;
	}
	function validar(){
		if(document.formulario.CPF_CNPJ_Obrigatorio.value == 1){
			if(document.formulario.CPF_CNPJ.value==''){
				mensagens(1);
				document.formulario.CPF_CNPJ.focus();
				return false;
			}		
		}
		if(document.formulario.CPF_CNPJ.value!=''){
			if(validar_CPF_CNPJ(document.formulario.CPF_CNPJ.value) == false){
				mensagens(18);
				document.formulario.CPF_CNPJ.focus();
				return false;
			}
		}
		if(document.formulario.TipoPessoa.value == 2){
			if(document.formulario.Nome.value==''){
				mensagens(1);
				document.formulario.Nome.focus();
				return false;
			}
		}else{
			if(document.formulario.NomeFantasia.value==''){
				mensagens(1);
				document.formulario.NomeFantasia.focus();
				return false;
			}
			if(document.formulario.RazaoSocial.value==''){
				mensagens(1);
				document.formulario.RazaoSocial.focus();
				return false;
			}
			if(document.formulario.NomeRepresentante.value==''){
				mensagens(1);
				document.formulario.NomeRepresentante.focus();
				return false;
			}
		}
		if(document.formulario.Endereco.value==''){
			mensagens(1);
			document.formulario.Endereco.focus();
			return false;
		}
		if(document.formulario.Numero.value==''){
			mensagens(1);
			document.formulario.Numero.focus();
			return false;
		}
		if(document.formulario.CEP.value==''){
			mensagens(1);
			document.formulario.CEP.focus();
			return false;
		}
		if(document.formulario.IdPais.value==''){
			mensagens(1);
			document.formulario.IdPais.focus();
			return false;
		}
		if(document.formulario.IdEstado.value==''){
			mensagens(1);
			document.formulario.IdEstado.focus();
			return false;
		}
		if(document.formulario.IdCidade.value==''){
			mensagens(1);
			document.formulario.IdCidade.focus();
			return false;
		}
		if(document.formulario.Telefone_Obrigatorio.value == 1){
			if(document.formulario.Telefone1.value == '' && document.formulario.Telefone2.value== '' && document.formulario.Telefone3.value == '' && document.formulario.Celular.value == '' && document.formulario.Fax.value == '' && document.formulario.Cob_Telefone1.value == ''){
				mensagens(42);
				document.formulario.Telefone1.focus();
				return false;
			}
		}
		if(document.formulario.Email.value != ''){
			var temp = document.formulario.Email.value.split(';');
			var i = 0;
			while(temp[i]!= '' && temp[i]!= undefined){
				temp[i]	= ignoreSpaces(temp[i]);
				if(isEmail(temp[i]) == false){				
					mensagens(12);
					document.formulario.Email.focus();
					return false;
					break;
				}
				i++;	
			}
		}
		if(document.formulario.Enviar_Email.value==0){
			mensagens(1);
			document.formulario.Enviar_Email.focus();
			return false;
		}else{
			if(document.formulario.Enviar_Email.value==1 && document.formulario.Email.value == '' && document.formulario.Cob_Email.value == ''){
				mensagens(1);
				document.formulario.Email.focus();
				return false;
			}
		}
		if(document.formulario.CampoExtra1Obrigatorio != undefined){
			if(document.formulario.CampoExtra1Obrigatorio.value=='S' && document.formulario.CampoExtra1.value==''){
				mensagens(1);
				document.formulario.CampoExtra1.focus();
				return false;
			}
		}
		if(document.formulario.CampoExtra2Obrigatorio != undefined){
			if(document.formulario.CampoExtra2Obrigatorio.value=='S' && document.formulario.CampoExtra2.value==''){
				mensagens(1);
				document.formulario.CampoExtra2.focus();
				return false;
			}
		}
		if(document.formulario.CampoExtra3Obrigatorio != undefined){
			if(document.formulario.CampoExtra3Obrigatorio.value=='S' && document.formulario.CampoExtra3.value==''){
				mensagens(1);
				document.formulario.CampoExtra3.focus();
				return false;
			}
		}
		if(document.formulario.CampoExtra4Obrigatorio != undefined){
			if(document.formulario.CampoExtra4Obrigatorio.value=='S' && document.formulario.CampoExtra4.value==''){
				mensagens(1);
				document.formulario.CampoExtra4.focus();
				return false;
			}
		}
		if(document.formulario.TipoCliente.checked==false && document.formulario.TipoUsuario.checked==false && document.formulario.TipoAgenteAutorizado.checked==false && document.formulario.TipoFornecedor.checked==false && document.formulario.TipoVendedor.checked==false){
			mensagens(1);
			document.formulario.TipoUsuario.focus();
			return false;
		}
		if(document.formulario.Cob_CobrarDespesaBoleto.value==0){
			mensagens(1);
			document.formulario.Cob_CobrarDespesaBoleto.focus();
			return false;
		}
		if(document.formulario.AgruparContratos.value==0){
			mensagens(1);
			document.formulario.AgruparContratos.focus();
			return false;
		}
		if(document.formulario.Cob_FormaEmail.checked==false && document.formulario.Cob_FormaCorreio.checked==false && document.formulario.Cob_FormaOutro.checked==false){
			mensagens(1);
			document.formulario.Cob_FormaCorreio.focus();
			return false;
		}
		if(document.formulario.Cob_FormaEmail.checked==true && document.formulario.Cob_Email.value == '' && document.formulario.Email.value == ''){
			mensagens(1);
			document.formulario.Cob_Email.focus();
			return false;
		}
		if(document.formulario.Cob_IdPais.value!=''){
			if(document.formulario.Cob_IdEstado.value==''){
				mensagens(1);
				document.formulario.Cob_IdEstado.focus();
				return false;
			}else{
				if(document.formulario.Cob_IdCidade.value==''){
					mensagens(1);
					document.formulario.Cob_IdCidade.focus();
					return false;
				}
			}
		}
		if(document.formulario.Cob_Email.value != ''){
			var temp = document.formulario.Cob_Email.value.split(';');
			var i = 0;
			while(temp[i]!= '' && temp[i]!= undefined){
				temp[i]	= ignoreSpaces(temp[i]);
				if(isEmail(temp[i]) == false){				
					mensagens(12);
					document.formulario.Cob_Email.focus();
					return false;
					break;
				}
				i++;	
			}
		}
		mensagens(0);
		return true;
	}
	function excluir(IdPessoa){
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
    
   			url = "files/excluir/excluir_pessoa.php?IdPessoa="+IdPessoa;
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
								url = 'cadastro_pessoa.php?Erro='+document.formulario.Erro.value;
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
									if(IdPessoa == document.getElementById('tableListar').rows[i].accessKey){
										document.getElementById('tableListar').deleteRow(i);										
										tableMultColor('tableListar',document.filtro.corRegRand.value);
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

	
