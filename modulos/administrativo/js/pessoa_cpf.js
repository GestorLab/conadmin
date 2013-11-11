	function verificaAcao(){
		if(document.formulario != undefined){
			if(document.formulario.Acao.value=='inserir'){			
				document.formulario.bt_alterar.disabled 	= true;
			}
			if(document.formulario.Acao.value=='alterar'){			
				document.formulario.bt_alterar.disabled 	= false;
			}
		}	
	}
	function validar(){
		if(document.formulario.TipoPessoa.value==''){
			mensagens(1);
			document.formulario.TipoPessoa.focus();
			return false;
		}
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
		mensagens(0);
		return true;
	}
	function inicia(){
		if(document.formulario.CPF_CNPJ_Obrigatorio.value == 1){
			document.getElementById('cp_CPF_CNPJ_Titulo').style.color = '#C10000';
		}else{
			document.getElementById('cp_CPF_CNPJ_Titulo').style.color = '#000000';
		}
		
		document.formulario.IdPessoa.focus();
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
	function ativaPessoa(pessoa){
		// Seleciona o Tipo da pessoa
		for(var i=0; i<document.formulario.TipoPessoa.length; i++){
			if(document.formulario.TipoPessoa[i].value == pessoa){
				document.formulario.TipoPessoa[i].selected = true;
			}
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
			
		}else{ //Pessoa Jurídica
			
			// Título do campo CPF_CNPJ
			document.getElementById('cp_CPF_CNPJ_Titulo').innerHTML = "CNPJ";
			document.formulario.CPF_CNPJ.maxLength					= 18; 
			
			//Aparece a Pessoa Jurídica
			document.getElementById('cp_Juridica').style.display = 'block';
			
			// Some o Pessoa Física			
			document.getElementById('cp_Fisica').style.display = 'none';
			
		}
		if(document.formulario.CPF_CNPJ.value!=''){
			// Valida o CPF_CNPJ referente a pessoa
			validar_CPF_CNPJ(document.formulario.CPF_CNPJ.value);
		}
	}
	function verificar_CPF_CNPJ(CPF_CNPJ){
		var CPF_CNPJ_valido = validar_CPF_CNPJ(CPF_CNPJ,'PessoaBusca');
		CPF_CNPJ = document.formulario.CPF_CNPJ.value;
		
		if(CPF_CNPJ != '' && CPF_CNPJ_valido){
			var url = "./xml/pessoa.php?CPF_CNPJ="+CPF_CNPJ;
			
			call_ajax(url,function (xmlhttp){
				if(xmlhttp.responseText != 'false'){
					var nameNode = xmlhttp.responseXML.getElementsByTagName("IdPessoa")[0]; 
					var nameTextNode = nameNode.childNodes[0];
					var msg, IdPessoa = nameTextNode.nodeValue;
					
					if(document.formulario.CPF_CNPJ_Duplicado.value == 1){
						if(document.formulario.TipoPessoa.value == 2){
							msg = "ATENÇÃO!\n\nCPF já utilizado.\nDeseja continuar?";
						} else{
							msg = "ATENÇÃO!\n\nCNPJ já utilizado.\nDeseja continuar?";
						}
						
						if(IdPessoa != ''){
							if(!confirm(msg)){
								busca_pessoa(document.formulario.IdPessoa.value, 'false', document.formulario.Local.value, CPF_CNPJ);
							}
						}
					} else{
						busca_pessoa(document.formulario.IdPessoa.value, 'false', document.formulario.Local.value, CPF_CNPJ);
					}
				}
			});
		}
	}
	function busca_pessoa_aproximada(campo,event){
		var url = "xml/pessoa_nome.php?Nome="+campo.value;
		
		call_ajax(url,function (xmlhttp){
			var NomeDefault = new Array(), nameNode, nameTextNode;
			
			if(campo.value != '' && xmlhttp.responseText != "false"){
				for(var i = 0; i < xmlhttp.responseXML.getElementsByTagName("NomeDefault").length; i++){
					nameNode = xmlhttp.responseXML.getElementsByTagName("NomeDefault")[i]; 
					nameTextNode = nameNode.childNodes[0];
					NomeDefault[i] = nameTextNode.nodeValue;
				}
			}
			
			busca_aproximada('filtro',campo,event,NomeDefault,22,5);
		},false);
	}