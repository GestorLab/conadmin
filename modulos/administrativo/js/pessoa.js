	function inicia(){	
		scrollWindow('top');
		if(document.formulario.IdPessoa.value == ''){
			while(document.getElementById('tableEndereco').rows.length > 0){
				document.getElementById('tableEndereco').deleteRow(0);
			}
			
			document.formulario.QtdEndereco.value					=	0;
			document.formulario.QtdEnderecoAux.value				=	0;
			document.formulario.IdEnderecoDefault.value				=	1;
			
			formulario_endereco();
			
			statusInicial();
			localidadeDefault();
		}
		
		document.formulario.IdPessoa.focus();
		addArquivo(null);
		
		if(document.formulario.CPF_CNPJ_Obrigatorio.value == 1){
			document.getElementById('cp_CPF_CNPJ_Titulo').style.color	=	'#C10000';
		}else{
			document.getElementById('cp_CPF_CNPJ_Titulo').style.color	=	'#000000';
		}
		
		if(document.formulario.ObrigatoriedadeInscricaoEstadual.value == 1){		
			document.getElementById('tit_InscricaoEstadual').style.color	=	'#C10000';
		}else{
			document.getElementById('tit_InscricaoEstadual').style.color	=	'#000000';
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
		
		if(document.formulario.CPF_CNPJ_Obrigatorio.value == 1){
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
			
			// Aparece o Pessoa F?ca			
			document.getElementById('cp_Fisica').style.display = 'block';
			
			// Habilita Tipo Usuario 
			document.formulario.TipoUsuario.disabled = false;
			
		}else{ //Pessoa Jur?ca
			
			// T?lo do campo CPF_CNPJ
			document.getElementById('cp_CPF_CNPJ_Titulo').innerHTML = "CNPJ";
			document.formulario.CPF_CNPJ.maxLength					= 18; 
			
			//Aparece a Pessoa Jur?ca
			document.getElementById('cp_Juridica').style.display = 'block';
			
			// Some o Pessoa F?ca			
			document.getElementById('cp_Fisica').style.display = 'none';
		}
		
		ativar_cadastro_resumido(document.formulario.CadastroResumido);
	}
	function validar_CPF_CNPJ(valor){		
		valor = valor.replace(/[\.\/-]/g, '');
		
		if(valor == ''){
			return false;
		}
		
		if(document.formulario.TipoPessoa.value == 2){
			inserir_mascara(valor,'cpf');

			if(isCPF(valor) == false || valor.length != 11){
				document.getElementById('cp_CPF_CNPJ_Titulo').innerHTML = "CPF - Inv?do";
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
			inserir_mascara(valor,'cnpj');
			
			if(isCNPJ(valor) == false || valor.length != 14){
				document.getElementById('cp_CPF_CNPJ_Titulo').innerHTML = "CNPJ - Inv?do";
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
	function verificar_CPF_CNPJ(CPF_CNPJ){
		var CPF_CNPJ_valido = validar_CPF_CNPJ(CPF_CNPJ,'PessoaBusca');
		CPF_CNPJ = document.formulario.CPF_CNPJ.value;
		
		if(document.formulario.CPF_CNPJ_Duplicado.value == 1){
			if(CPF_CNPJ != '' && CPF_CNPJ_valido){
				var url = "./xml/pessoa.php?CPF_CNPJ="+CPF_CNPJ;
				
				call_ajax(url,function (xmlhttp){
					if(xmlhttp.responseText != 'false'){
						var nameNode = xmlhttp.responseXML.getElementsByTagName("IdPessoa")[0]; 
						var nameTextNode = nameNode.childNodes[0];
						var msg, IdPessoa = nameTextNode.nodeValue;
						
						if(document.formulario.TipoPessoa.value == 2){
							msg = "ATEN!O!\n\nCPF j?tilizado.\nDeseja continuar?";
						} else{
							msg = "ATEN!O!\n\nCNPJ j?tilizado.\nDeseja continuar?";
						}
						
						if(IdPessoa != ''){
							if(!confirm(msg)){
								busca_pessoa(document.formulario.IdPessoa.value, 'false', document.formulario.Local.value, CPF_CNPJ);
							}
						}
					}
				});
			}
		} else if(CPF_CNPJ != '' && (document.formulario.CPF_CNPJ_Obrigatorio.value == 1 || document.formulario.CPF_CNPJ_Duplicado.value == 2)){
			busca_pessoa(document.formulario.IdPessoa.value, 'false', document.formulario.Local.value, CPF_CNPJ);
		}
	}
	function inserir_mascara(valor,tipo){
		valor = valor.replace(/[\.\/-]/g, '');
		
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
		var color = "#000";
		
		if(campo == "cp_DataNascimento_Titulo") {
			color = "#000";
			
			if(document.formulario.DataNascimento_Obrigatorio.value == 1){
				color = "#c10000";
			}
		}
		
		if(!isData(valor) && valor != '') {
			document.getElementById(campo).style.backgroundColor = '#C10000';
			document.getElementById(campo).style.color = '#FFF';
			mensagens(27);
			return false;
		} else {
			document.getElementById(campo).style.backgroundColor='#FFFFFF';
			document.getElementById(campo).style.color = color;
			mensagens(0);
			return true;
		}	
	}
	function validar_Email(valor,id){
		if(valor == ''){
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
	function validar(){
		if(document.formulario.AtivarCadastroResumido.value == 2){
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
				if(document.formulario.ObrigatoriedadeSexo.value == 1 && document.formulario.Sexo.value == ""){
					mensagens(1);
					document.formulario.Sexo.focus();
					return false;
				}
				if(document.formulario.DataNascimento.value == '' && validar_Data(document.formulario.DataNascimento.value,'cp_DataNascimento_Titulo') && document.formulario.DataNascimento_Obrigatorio.value == 1) {
					mensagens(1);
					document.formulario.DataNascimento.focus();
					return false;
				}
				if(document.formulario.ObrigatoriedadeEstadoCivil.value == 1 && document.formulario.EstadoCivil.value == ""){
					mensagens(1);
					document.formulario.EstadoCivil.focus();
					return false;
				}
				if(document.formulario.ObrigatoriedadeRG.value == 1 && document.formulario.RG_IE.value == ""){
					mensagens(1);
					document.formulario.RG_IE.focus();
					return false;
				}
				if(document.formulario.RG_IE.value != "" && document.formulario.OrgaoExpedidor.value == ""){
					mensagens(1);
					document.formulario.OrgaoExpedidor.focus();
					return false;
				}
				
				if(document.formulario.ObrigatoriedadeNomePai.value == 1 && document.formulario.NomePai.value == ""){
					mensagens(1);
					document.formulario.NomePai.focus();
					return false;
				}
				if(document.formulario.ObrigatoriedadeNomeMae.value == 1 && document.formulario.NomeMae.value == ""){
					mensagens(1);
					document.formulario.NomeMae.focus();
					return false;
				}
				if(document.formulario.ObrigatoriedadeConjugue.value == 1 && document.formulario.NomeConjugue.value == ""){
					if(document.formulario.VisivelConjugueDisplay.value == 1){
						mensagens(1);
						document.formulario.NomeConjugue.focus();
						return false;
					}
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
				if(document.formulario.ObrigatoriedadeInscricaoEstadual.value == 1 && document.formulario.InscricaoEstadual.value == ''){ //Verifica se o codigo interno permite e se o campo est?azio.
					mensagens(1);
					document.formulario.InscricaoEstadual.focus();
					return false;		
				}
				//Convertendo Estado para sigla e demais variaveis
					var numEnderecoDefault	= document.formulario.IdEnderecoDefault.value;
					var IE					= document.formulario.InscricaoEstadual.value;
					if(numEnderecoDefault > 1){
						eval("var Estado 	= document.formulario.Estado_"+numEnderecoDefault+".value;");
					}else{
						eval("var Estado 	= document.formulario.Estado_1.value;");
					}
					switch(Estado){
						case 'Acre':
							document.formulario.SiglaEstadoIE.value = 'AC';
							break;
						case 'Alagoas':
							document.formulario.SiglaEstadoIE.value = 'AL';
							break;
						case 'Amapá':
							document.formulario.SiglaEstadoIE.value = 'AP';
							break;
						case 'Amazonas':
							document.formulario.SiglaEstadoIE.value = 'AM';
							break;
						case 'Bahia':
							document.formulario.SiglaEstadoIE.value = 'BA';
							break;
						case 'Ceará':
							document.formulario.SiglaEstadoIE.value = 'CE';
							break;
						case 'Distrito Federal':
							document.formulario.SiglaEstadoIE.value = 'DF';
							break;
						case 'Espirito Santo':
							document.formulario.SiglaEstadoIE.value = 'ES';
							break;
						case 'Goiás':
							document.formulario.SiglaEstadoIE.value = 'GO'
							break;
						case 'Maranhão':
							document.formulario.SiglaEstadoIE.value = 'MA';
							break;
						case 'Mato Grosso':
							document.formulario.SiglaEstadoIE.value = 'MT';
							break;
						case 'Mato Grosso do Sul':
							document.formulario.SiglaEstadoIE.value = 'MS';
							break;
						case 'Minas Gerais':
							document.formulario.SiglaEstadoIE.value = 'MG';
							break;
						case 'Pará':
							document.formulario.SiglaEstadoIE.value = 'PA';
							break;
						case 'Paraíba':
							document.formulario.SiglaEstadoIE.value = 'PB';
							break;
						case 'Paraná':
							document.formulario.SiglaEstadoIE.value = 'PR';
							break;
						case 'Pernambuco':
							document.formulario.SiglaEstadoIE.value = 'PE';
							break;
						case 'Piaui':
							document.formulario.SiglaEstadoIE.value = 'PI';
							break;
						case 'Roraima':
							document.formulario.SiglaEstadoIE.value = 'RR';
							break;
						case 'Rondonia':
							document.formulario.SiglaEstadoIE.value = 'RO';
							break;
						case 'Rio de Janeiro':
							document.formulario.SiglaEstadoIE.value = 'RJ';
							break;
						case 'Rio Grande do Norte':
							document.formulario.SiglaEstadoIE.value = 'RN';
							break;
						case 'Rio Grande do Sul':
							document.formulario.SiglaEstadoIE.value = 'RS';
							break;
						case 'Santa Catarina':
							document.formulario.SiglaEstadoIE.value = 'SC';
							break;
						case 'São Paulo':
							document.formulario.SiglaEstadoIE.value = 'SP';
							break;
						case 'Sergipe':
							document.formulario.SiglaEstadoIE.value = 'SE';
							break;
						case 'Tocantins':
							document.formulario.SiglaEstadoIE.value = 'TO';
							break;
					}	
					//fim conversao
				if(document.formulario.InscricaoEstadual.value != '' && document.formulario.InscricaoEstadual.value != '' && document.formulario.SiglaEstadoIE.value != '' && !isIE(document.formulario.InscricaoEstadual.value , document.formulario.SiglaEstadoIE.value)){
					document.getElementById("tit_InscricaoEstadual").style.backgroundColor = "#c10000";
					document.getElementById("tit_InscricaoEstadual").style.color = "#FFF";
					mensagens(146);
					document.formulario.InscricaoEstadual.focus();
					return false;					
				}else{
					document.getElementById("tit_InscricaoEstadual").style.color = "#000";
					document.getElementById("tit_InscricaoEstadual").style.backgroundColor = "#fff";
				}
				
			}
			if(document.formulario.TelefoneObrigatorio.value == 1){
				telefone	=	false;
				
				for(i=0;i<document.formulario.length;i++){
					if(document.formulario[i].name.substr(0,9) == 'Telefone_'){
						if(document.formulario[i].value != ""){
							telefone = true; 
							break;
						}
					}
					if(document.formulario[i].name.substr(0,8) == 'Celular_'){
						if(document.formulario[i].value != ""){
							telefone = true; 
							break;
						}
					}
					if(document.formulario[i].name.substr(0,4) == 'Fax_'){
						if(document.formulario[i].value != ""){
							telefone = true; 
							break;
						}
					}
				}
				if(telefone == false && document.formulario.Telefone1.value == '' && document.formulario.Telefone2.value== '' && document.formulario.Telefone3.value == '' && document.formulario.Celular.value == '' && document.formulario.Fax.value == ''){
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
			if(document.formulario.IdGrupoPessoa.value==""){
				mensagens(1);
				document.formulario.IdGrupoPessoa.focus();
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
			if(document.formulario.TipoCliente.checked==false && document.formulario.TipoUsuario.checked==false && document.formulario.TipoAgenteAutorizado.checked==false && document.formulario.TipoFornecedor.checked==false && document.formulario.TipoVendedor.checked==false){
				mensagens(1);
				document.formulario.TipoUsuario.focus();
				return false;
			}
			if(document.formulario.Cob_FormaCorreio.checked == false && document.formulario.Cob_FormaEmail.checked==false && document.formulario.Cob_FormaOutro.checked==false){
				mensagens(1);
				document.formulario.Cob_FormaCorreio.focus();
				return false;
			}
			if(document.formulario.IdMonitorFinanceiro.value==''){
				mensagens(1);
				document.formulario.IdMonitorFinanceiro.focus();
				return false;
			}
			if(document.formulario.ForcarAtualizar.value==''){
				mensagens(1);
				document.formulario.ForcarAtualizar.focus();
				return false;
			}
			if(document.formulario.Cob_FormaEmail.checked==true){
				var email	=	false;
				for(i=0;i<document.formulario.length;i++){
					if(document.formulario[i].name.substr(0,14) == 'EmailEndereco_'){
						if(document.formulario[i].value != ""){
							email = true;
							break;
						}
					}
				}
				if(email == false && document.formulario.Email.value == ''){
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
			
			if(document.formulario.Acao.value == 'inserir'){
				for(i=0;i<document.formulario.length;i++){
					aux	=	document.formulario[i].name.split('_');
					if(aux[1] >= 1){
						if(aux[0] == 'DescricaoEndereco' || aux[0] == 'CEP' || aux[0] == 'Endereco' || (document.formulario.Numero_Obrigatorio.value == 1 && aux[0] == 'Numero') || aux[0] == 'Bairro' || aux[0] == 'IdPais' || aux[0] == 'IdEstado' || aux[0] == 'IdCidade'){
							if(document.formulario[i].value.replace(/ /gi, "") == ""){
								mensagens(1);
								document.formulario[i].focus();
								return false;
							}
						}		
					}else{
						if(aux[0] == 'IdPessoaEndereco'){
							if(document.formulario[i+2].value!="" || document.formulario[i+3].value!="" || document.formulario[i+4].value!="" || document.formulario[i+5].value!="" || document.formulario[i+6].value!="" || document.formulario[i+7].value!="" ||  document.formulario[i+8].value!="" || document.formulario[i+10].value!="" || document.formulario[i+12].value!="" ||  document.formulario[i+14].value!="" || document.formulario[i+15].value!="" ||  document.formulario[i+16].value!="" || document.formulario[i+17].value!="" || document.formulario[i+18].value!=""){
								if(document.formulario[i+1].value == ""){
									mensagens(1);
									document.formulario[i+1].focus();
									return false;
								}
								if(document.formulario[i+3].value == ""){
									mensagens(1);
									document.formulario[i+3].focus();
									return false;
								}
								if(document.formulario[i+4].value == ""){
									mensagens(1);
									document.formulario[i+4].focus();
									return false;
								}
								if(document.formulario[i+7].value == ""){
									mensagens(1);
									document.formulario[i+7].focus();
									return false;
								}
								if(document.formulario[i+8].value == ""){
									mensagens(1);
									document.formulario[i+8].focus();
									return false;
								}
								if(document.formulario[i+10].value == ""){
									mensagens(1);
									document.formulario[i+10].focus();
									return false;
								}
								if(document.formulario[i+12].value == ""){
									mensagens(1);
									document.formulario[i+12].focus();
									return false;
								}
							}else{
								i	=	i+19;
							}
						}
					}
				}
			}else{
				for(i=0;i<document.formulario.length;i++){
					aux	= document.formulario[i].name.split('_');
					
					if(!isNaN(Number(aux[1])) && (aux[0] == 'DescricaoEndereco' || aux[0] == 'CEP' || aux[0] == 'Endereco' || (document.formulario.Numero_Obrigatorio.value == 1 && aux[0] == 'Numero') || aux[0] == 'Bairro' || aux[0] == 'IdPais' || aux[0] == 'IdEstado' || aux[0] == 'IdCidade')){
						if(document.formulario[i].value.replace(/ /gi, "") == ""){
							mensagens(1);
							document.formulario[i].focus();
							return false;
						}
					}
				}
			}
			
			for(i = 0; i<document.formulario.length; i++){
				if(document.formulario[i].name != undefined){
					if(document.formulario[i].name.substring(0,11) == 'EndArquivo_'){
						var temp = document.formulario[i].value.split('.');
						var ext = temp[temp.length-1].toLowerCase();
						
						if(!document.formulario.ExtensaoAnexo.value.split(',').in_array(ext) && ext != ''){
							mensagens(10);
							document.formulario[i-1].focus();
							return false;
						} else{
							if(document.formulario[i].value != '' && document.formulario[i+1].value == ''){
								mensagens(1);
								document.formulario[i+1].focus();
								return false;
							}
						}
					}
				}
			}
		}else{
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
				if(document.formulario.Nome_Resumido.value==''){
					mensagens(1);
					document.formulario.Nome_Resumido.focus();
					return false;
				}
				if(document.formulario.Email_Resumido.value==''){
					mensagens(1);
					document.formulario.Email_Resumido.focus();
					return false;
				}
				if(document.formulario.IdGrupoPessoa_Resumido.value==''){
					mensagens(1);
					document.formulario.IdGrupoPessoa_Resumido.focus();
					return false;
				}
				
				if(document.formulario.Telefone1_Resumido.value == '' && document.formulario.Telefone2_Resumido.value== '' && document.formulario.Telefone3_Resumido.value == '' && document.formulario.Celular_Resumido.value == '' && document.formulario.Fax_Resumido.value == ''){
					mensagens(42);
					document.formulario.Telefone1_Resumido.focus();
					return false;
				}
			}else{
				if(document.formulario.RazaoSocial_Resumido.value==''){
					mensagens(1);
					document.formulario.RazaoSocial_Resumido.focus();
					return false;
				}
				
				if(document.formulario.NomeFantasia_Resumido.value==''){
					mensagens(1);
					document.formulario.NomeFantasia_Resumido.focus();
					return false;
				}
				
				if(document.formulario.Email_Resumido2.value==''){
					mensagens(1);
					document.formulario.Email_Resumido2.focus();
					return false;
				}
				if(document.formulario.IdGrupoPessoa_Resumido2.value==''){
					mensagens(1);
					document.formulario.IdGrupoPessoa_Resumido2.focus();
					return false;
				}
				
				if(document.formulario.Telefone1_Resumido.value == '' && document.formulario.Telefone2_Resumido.value== '' && document.formulario.Telefone3_Resumido.value == '' && document.formulario.Celular_Resumido.value == '' && document.formulario.Fax_Resumido.value == ''){
					mensagens(42);
					document.formulario.Telefone1_Resumido.focus();
					return false;
				}
			}
			
			if(document.formulario.IdPais_Resumido.value == ""){
				mensagens(1);
				document.formulario.IdPais_Resumido.focus();
				return false;
			}
			
			if(document.formulario.IdEstado_Resumido.value == ""){
				mensagens(1);
				document.formulario.IdEstado_Resumido.focus();
				return false;
			}
			
			if(document.formulario.IdCidade_Resumido.value == ""){
				mensagens(1);
				document.formulario.IdCidade_Resumido.focus();
				return false;
			}
		}
		mensagens(0);
		return true;
	}
	function cadastrar(acao){	
		document.formulario.Acao.value	=	acao;
		switch(acao){
			case "inserir":
				if(validar(acao)==true){
					document.formulario.submit();
				}
				break;
			case "alterar":
				if(validar(acao)==true){
					document.formulario.submit();
				}
				break;
			case "Relatorio":
				window.open("relatorio_pessoa.php?IdPessoa="+document.formulario.IdPessoa.value);
				break;
			case "contaDebito":
				window.location.href = "cadastro_pessoa_conta_debito.php?IdPessoa="+document.formulario.IdPessoa.value;
				break;
			case "cartaoCredito":
				window.location.href = "cadastro_pessoa_cartao_credito.php?IdPessoa="+document.formulario.IdPessoa.value;
				break;			
		}
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
	function verificaAcao(){
		if(document.formulario != undefined){
			if(document.formulario.Acao.value=='inserir'){
				document.formulario.bt_inserir.disabled 		= false;
				document.formulario.bt_alterar.disabled 		= true;
				document.formulario.bt_excluir.disabled 		= true;
				document.formulario.bt_aviso.disabled			= true;
				document.formulario.bt_cda.disabled				= true;
				document.formulario.bt_relatorio.disabled		= true;
				document.formulario.bt_contaDebito.disabled		= true;
				document.formulario.bt_cartaoCredito.disabled	= true;
			}
			if(document.formulario.Acao.value=='alterar'){		
				document.formulario.bt_inserir.disabled 	= true;
				document.formulario.bt_alterar.disabled 	= false;
				document.formulario.bt_excluir.disabled 	= false;
				document.formulario.bt_aviso.disabled		= false;
				document.formulario.bt_cda.disabled			= false;
			}
		}	
	}
	
	function formulario_endereco(){
		var endereco = "",pos,descricao="",tabindex=43,CEP="",acao;
		var max = tam_endereco();
		
		document.formulario.QtdEndereco.value		=	parseInt(document.formulario.QtdEndereco.value) + 1;
		document.formulario.QtdEnderecoAux.value	=	parseInt(document.formulario.QtdEnderecoAux.value) + 1;
		
		pos		=	document.formulario.QtdEndereco.value;
		acao	=	document.formulario.Acao.value;
		
		if(pos == 1 && acao == "inserir" && document.formulario.CEPDefault.value !=""){
			CEP	=	document.formulario.CEPDefault.value;
		}
		
		if(pos == 1)	descricao = document.formulario.DescricaoEndereco1.value;
		if(pos == 2)	descricao = document.formulario.DescricaoEndereco2.value;
		
		if(pos > 1)		tabindex	=	tabindex + (14*(pos-1));	
		
		
		var tam, linha, c0;
		
		tam 	= document.getElementById('tableEndereco').rows.length;
		linha	= document.getElementById('tableEndereco').insertRow(tam);
		
		linha.accessKey = pos; 
							
		c0	= linha.insertCell(0);	
		
		endereco	+="<div id='formEndereco_"+pos+"' style='margin-top:10px; padding: 0;'>";
		
		if(pos == 1){
			endereco	+="<div class='cp_tit'><table cellspacing='0' cellpading='0' style='width:840px'><tr><td style='width:40%; text-align:left'>Endere&#231;o "+pos+": <font id='titEndereco_"+pos+"'>"+descricao+"</font> [<a style='cursor:pointer' onClick='formulario_endereco()'>+</a>]</td><td style='text-align:center'><a style='cursor:pointer;' title='Como Chegar?' onClick=\"como_chegar_pessoa(document.formulario.Endereco_"+pos+".value+', '+document.formulario.Numero_"+pos+".value+', '+document.formulario.Cidade_"+pos+".value+','+document.formulario.Estado_"+pos+".value);\">Como Chegar</a></td><td width='40%'>&nbsp;</td></tr></table></div>";
		}else{
			endereco	+="<div class='cp_tit'><table cellspacing='0' cellpading='0' style='width:840px'><tr><td style='width:40%; text-align:left'>Endere&#231;o "+pos+": <font id='titEndereco_"+pos+"'>"+descricao+"</font> [<a style='cursor:pointer' onClick='formulario_endereco()'>+</a>]</td><td style='text-align:center'><a style='cursor:pointer;' title='Como Chegar?' onClick=\"como_chegar_pessoa(document.formulario.Endereco_"+pos+".value+', '+document.formulario.Numero_"+pos+".value+', '+document.formulario.Cidade_"+pos+".value+','+document.formulario.Estado_"+pos+".value);\">Como Chegar</a></td><td style='width:40%; text-align:right;'>[<a style='cursor:pointer;' onClick='excluir_endereco("+pos+")'>x</a>]</td></tr></table></div>";		
		}
		
		endereco	+="<table>";
		endereco	+="	<tr>";
		endereco	+="		<td class='find'>&nbsp;</td>";
		
		if(pos >= 1 || acao=='alterar')	endereco	+="	<td class='descCampo'><B>Descri&#231;&#227;o Endere&#231;o</B></td>";
		else							endereco	+="	<td class='descCampo'>Descri&#231;&#227;o Endere&#231;o</td>";
			
		endereco	+="		<td class='separador'>&nbsp;</td>";
		endereco	+="		<td class='descCampo'>Nome Respons&#225;vel</td>";
		endereco	+="	</tr>";
		endereco	+="	<tr>";
		endereco	+="		<td class='find'>&nbsp;</td>";
		endereco	+="		<td class='campo'>";
		endereco	+="			<input type='hidden' name='IdPessoaEndereco_"+pos+"' value=''><input type='text' name='DescricaoEndereco_"+pos+"' value='"+descricao+"' style='width:399px' maxlength='100' onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out')\" onChange=\"atualiza_opcoes_endereco("+pos+",this.value)\" tabindex='"+tabindex+"'>";
		endereco	+="		</td>";
		endereco	+="		<td class='separador'>&nbsp;</td>";
		endereco	+="		<td class='campo'>";
		endereco	+="			<input type='text' name='NomeResponsavelEndereco_"+pos+"' value='' style='width:400px' maxlength='100' onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out')\" tabindex='"+(tabindex+1)+"'>";
		endereco	+="		</td>";
		endereco	+="	</tr>";
		endereco	+="</table>";
		endereco	+="<table>";
		endereco	+="	<tr>";
		endereco	+="		<td class='find'>&nbsp;</td>";
		
		if(pos >= 1 || acao=='alterar')	endereco	+="		<td class='descCampo'><B>CEP</B></td>";
		else			endereco	+="		<td class='descCampo'>CEP</td>";
		
		endereco	+="		<td class='separador'>&nbsp;</td>";
		
		if(pos >= 1 || acao=='alterar')	endereco	+="		<td class='descCampo'><B>Endere&#231;o</B></td>";
		else			endereco	+="		<td class='descCampo'>Endereço</td>";
		
		endereco	+="		<td class='separador'>&nbsp;</td>";
		
		if(pos >= 1 || acao=='alterar'){
			if(document.formulario.Numero_Obrigatorio.value == 1){
				endereco	+="		<td class='descCampo'><B>N&#186;</B></td>";
			}else{
				endereco	+="		<td class='descCampo'>N&#186;</td>";
			}
		}
		/*
		endereco	+="		<td class='descCampo'><B>Nº</B></td>";
		*/
		
		endereco	+="		<td class='separador'>&nbsp;</td>";					
		endereco	+="		<td class='descCampo'>Complemento</td>";	
		endereco	+="		<td class='separador'>&nbsp;</td>";
		
		if(pos >= 1 || acao=='alterar')	endereco	+="		<td class='descCampo'><B>Bairro</B></td>";
		else			endereco	+="		<td class='descCampo'>Bairro</td>";
		
		endereco	+="	</tr>";
		endereco	+="	<tr>";
		endereco	+="		<td class='find'><img style='cursor:pointer' src='../../img/estrutura_sistema/ico_lupa.gif' onClick=\"vi_id('quadroBuscaCep', true, event, null, 572); document.BuscaCep.UF.focus();\"></td>";
		endereco	+="		<td class='campo'>";
		endereco	+="			<input type='text' name='CEP_"+pos+"' value='"+CEP+"' style='width:70px' maxlength='9' onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out')\" onkeypress=\"mascara(this,event,'cep')\" onChange=\"busca_pessoa_cep(document.formulario.CEP_"+pos+".value,false,"+pos+")\" tabindex='"+(tabindex+2)+"'>";
		endereco	+="		</td>";
		endereco	+="		<td class='separador'>&nbsp;</td>";
		endereco	+="		<td class='campo'>";
		endereco	+="			<input type='text' name='Endereco_"+pos+"' value='' style='width:268px' maxlength='"+max+"' onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out')\" tabindex='"+(tabindex+3)+"'>";
		endereco	+="		</td>";
		endereco	+="		<td class='separador'>&nbsp;</td>";
		endereco	+="		<td class='campo'>";
		endereco	+="			<input type='text' name='Numero_"+pos+"' value='' style='width:55px' maxlength='10' onkeypress=\"mascara(this,event,'numeroEndereco')\" onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out')\" tabindex='"+(tabindex+4)+"'>";
		endereco	+="		</td>";
		endereco	+="		<td class='separador'>&nbsp;</td>";
		endereco	+="		<td class='campo'>";
		endereco	+="			<input type='text' name='Complemento_"+pos+"' value='' style='width:161px' maxlength='100' onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out')\" tabindex='"+(tabindex+5)+"'>";
		endereco	+="		</td>";
		endereco	+="		<td class='separador'>&nbsp;</td>";
		endereco	+="		<td class='campo'>";
		endereco	+="			<input type='text' name='Bairro_"+pos+"' value='' style='width:194px' maxlength='30' onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out')\" tabindex='"+(tabindex+6)+"'>";
		endereco	+="		</td>";
		endereco	+="	</tr>";
		endereco	+="</table>";
		endereco	+="<table>";
		endereco	+="	<tr>";
		endereco	+="		<td class='find'>&nbsp;</td>";
		
		if(pos >= 1 || acao=='alterar')	endereco	+="		<td class='descCampo'><B style='margin-right:54px;'>Pa&#237;s</B>Nome Pa&#237;s</td>";
		else			endereco	+="		<td class='descCampo'><B style='margin-right:54px; color:#000'>País</B>Nome País</td>";
		
		endereco	+="		<td class='separador'>&nbsp;</td>";
		endereco	+="		<td class='find'>&nbsp;</td>";
		
		if(pos >=1 || acao=='alterar')		endereco	+="		<td class='descCampo'><B style='margin-right:38px;'>Estado</b>Nome Estado</td>";
		else			endereco	+="		<td class='descCampo'><B style='margin-right:38px; color:#000'>Estado</b>Nome Estado</td>";
		
		endereco	+="		<td class='separador'>&nbsp;</td>";
		endereco	+="		<td class='find'>&nbsp;</td>";
		
		if(pos >=1 || acao=='alterar')		endereco	+="		<td class='descCampo'><B style='margin-right:38px;'>Cidade</B>Nome Cidade</td>";
		else			endereco	+="		<td class='descCampo'><B style='margin-right:38px; color:#000'>Cidade</B>Nome Cidade</td>";
		
		endereco	+="	</tr>";
		endereco	+="	<tr>";
		endereco	+="		<td class='find'><img src='../../img/estrutura_sistema/ico_lupa.gif' alt='Buscar' onClick=\"vi_id('quadroBuscaPais', true, event, null, 572, "+pos+");\"></td>";
		endereco	+="		<td class='campo'>";
		endereco	+="			<input type='text' name='IdPais_"+pos+"' value='' style='width:70px' maxlength='11' onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out')\" tabindex='"+(tabindex+7)+"' onChange='busca_pessoa_pais(this.value,false,document.formulario.Local.value, "+pos+")' onkeypress=\"mascara(this,event,'int')\"><input  class='agrupador' type='text' name='Pais_"+pos+"' value='' style='width:140px' maxlength='100' readOnly>";
		endereco	+="		</td>";
		endereco	+="		<td class='separador'>&nbsp;</td>";
		endereco	+="		<td class='find'><img src='../../img/estrutura_sistema/ico_lupa.gif' alt='Buscar' onClick=\"vi_id('quadroBuscaEstado', true, event, null, 572, "+pos+");\"></td>";
		endereco	+="		<td class='campo'>";
		endereco	+="			<input type='text' name='IdEstado_"+pos+"' value='' style='width:70px' maxlength='11' onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out')\" tabindex='"+(tabindex+8)+"' onChange='busca_pessoa_estado(document.formulario.IdPais_"+pos+".value,this.value,false,document.formulario.Local.value,"+pos+")' onkeypress=\"mascara(this,event,'int')\"><input class='agrupador' type='text' name='Estado_"+pos+"' value='' style='width:140px' maxlength='100' readOnly>";
		endereco	+="		</td>";
		endereco	+="		<td class='separador'>&nbsp;</td>";
		endereco	+="		<td class='find'><img src='../../img/estrutura_sistema/ico_lupa.gif' alt='Buscar' onClick=\"vi_id('quadroBuscaCidade', true, event, null, 572, "+pos+");\"></td>";
		endereco	+="		<td class='campo'>";
		endereco	+="			<input type='text' name='IdCidade_"+pos+"' value='' style='width:70px' maxlength='11' onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out')\" tabindex='"+(tabindex+9)+"' onChange='busca_pessoa_cidade(document.formulario.IdPais_"+pos+".value,document.formulario.IdEstado_"+pos+".value,this.value,false,document.formulario.Local.value,"+pos+")' onkeypress=\"mascara(this,event,'int')\"><input class='agrupador' type='text' name='Cidade_"+pos+"' value='' style='width:233px' maxlength='100' readOnly>";
		endereco	+="		</td>";
		endereco	+="	</tr>";
		endereco	+="</table>";
		endereco	+="<table>";
		endereco	+="	<tr>";
		endereco	+="		<td class='find'>&nbsp;</td>";
		endereco	+="		<td class='descCampo'>Fone</td>";	
		endereco	+="		<td class='separador'>&nbsp;</td>";	
		endereco	+="		<td class='descCampo'>Celular</td>";	
		endereco	+="		<td class='separador'>&nbsp;</td>";	
		endereco	+="		<td class='descCampo'>Fax</td>";	
		endereco	+="		<td class='separador'>&nbsp;</td>";							
		endereco	+="		<td class='descCampo'>Complemento Fone</td>";
		endereco	+="		<td class='separador'>&nbsp;</td>";
		endereco	+="		<td class='descCampo'><B style='color:#000' id='Email_"+pos+"'>E-mail</B></td>";
		endereco	+="		<td class='find'>&nbsp;</td>";
		endereco	+="	</tr>";
		endereco	+="	<tr>";
		endereco	+="		<td class='find'>&nbsp;</td>";
		endereco	+="		<td class='campo'>";
		endereco	+="			<input type='text' name='Telefone_"+pos+"' value='' style='width:122px' maxlength='18' onkeypress=\"mascara_fone(this,event)\" onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out')\" tabindex='"+(tabindex+10)+"'><div><a href='http://intranet.cntsistemas.com.br/aplicacoes/operadora' target='_blank'>Operadora</a></div>";
		endereco	+="		</td>";
		endereco	+="		<td class='separador'>&nbsp;</td>";
		endereco	+="		<td class='campo'>";
		endereco	+="			<input type='text' name='Celular_"+pos+"' value='' style='width:122px' maxlength='18' onkeypress=\"mascara_fone(this,event)\" onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out')\" tabindex='"+(tabindex+11)+"'><div><a href='http://intranet.cntsistemas.com.br/aplicacoes/operadora' target='_blank'>Operadora</a></div>";
		endereco	+="		</td>";
		endereco	+="		<td class='separador'>&nbsp;</td>";
		endereco	+="		<td class='campo'>";
		endereco	+="			<input type='text' name='Fax_"+pos+"' value='' style='width:122px' maxlength='18' onkeypress=\"mascara_fone(this,event)\" onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out')\" tabindex='"+(tabindex+12)+"'><div><a href='http://intranet.cntsistemas.com.br/aplicacoes/operadora' target='_blank'>Operadora</a></div>";
		endereco	+="		</td>";
		endereco	+="		<td class='separador'>&nbsp;</td>";
		endereco	+="		<td class='campo'>";
		endereco	+="			<input type='text' name='ComplementoTelefone_"+pos+"' value='' style='width:122px' maxlength='30' onkeypress=\"mascara_fone(this,event)\" onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out')\" tabindex='"+(tabindex+13)+"'><div><a href='http://intranet.cntsistemas.com.br/aplicacoes/operadora' target='_blank'>Operadora</a></div>";
		endereco	+="		</td>";
		endereco	+="		<td class='separador'>&nbsp;</td>";
		endereco	+="		<td class='campo' style='vertical-align:top;'>";
		endereco	+="			<input type='text' name='EmailEndereco_"+pos+"' value='' onkeypress=\"return mascara(this,event,'filtroCaractereEmail','','')\" style='width:240px' maxlength='255' onChange=\"validar_Email(this.value,'Email');verifica_email_cobranca();\" autocomplete='off' onFocus=\"Foco(this,'in',true)\"  onBlur=\"Foco(this,'out'); validar_Email(this.value,'Email_"+pos+"')\" tabindex='"+(tabindex+14)+"'>";
		endereco	+="		</td>";
		endereco	+="		<td class='find' style='vertical-align:top;' onClick='JsMail(document.formulario.EmailEndereco_"+pos+".value)'><img style='margin-top:3px;' src='../../img/estrutura_sistema/ico_outlook.gif' alt='Enviar E-mail'></td>";
		endereco	+="	</tr>";
		endereco	+="</table>";
		endereco	+="</div>";
		
		c0.innerHTML = endereco;
	}
	function busca_pessoa_pais(IdPais,Erro,Local,Endereco){
		if(IdPais == ''){
			IdPais = 0;
		}
		if(Local == '' || Local == undefined){
			Local	=	document.formulario.Local.value;
		}
		if(Endereco=='' || Endereco == undefined){
			return false;
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
						document.formulario.Erro.value = 0;
						verificaErro();
					}
					if(xmlhttp.responseText == 'false'){
						if(document.formulario.CadastroResumido.value == 1){
							document.formulario.IdPais_Resumido.value 	= '';
							document.formulario.Pais_Resumido.value 	= '';
							document.formulario.IdPais_Resumido.focus();
						}
						for(i=0;i<document.formulario.length;i++){
							if(document.formulario[i].name.substr(0,7) == 'IdPais_'){
								var temp	=	document.formulario[i].name.split("_");
								if(temp[1] == Endereco){
									document.formulario[i].value 		= '';	//IdPais
									document.formulario[i+1].value		= '';	//Pais
									
									document.formulario[i+2].value 		= '';	//IdEstado
									document.formulario[i+3].value		= '';	//Estado
									
									document.formulario[i+4].value 		= '';	//IdCidade
									document.formulario[i+5].value		= '';	//Cidade
						
									document.formulario[i].focus();
									break;
								}
							}
						}
											
						// Fim de Carregando
						carregando(false);
					}else{
						nameNode = xmlhttp.responseXML.getElementsByTagName("IdPais")[0]; 
						nameTextNode = nameNode.childNodes[0];
						IdPais = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("NomePais")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var NomePais = nameTextNode.nodeValue;					
						
						if(document.formulario.CadastroResumido.value == 1){
							document.formulario.IdPais_Resumido.value = IdPais;
							document.formulario.Pais_Resumido.value = NomePais;
						}
						
						for(i=0;i<document.formulario.length;i++){
							if(document.formulario[i].name.substr(0,7) == 'IdPais_'){
								var temp	=	document.formulario[i].name.split("_");
								if(temp[1] == Endereco){
									document.formulario[i].value 		= IdPais;	//IdPais
									document.formulario[i+1].value		= NomePais;	//Pais
									
									document.formulario[i+2].value 		= "";	//IdEstado
									document.formulario[i+3].value		= "";	//Estado
									
									document.formulario[i+4].value 		= "";	//IdCidade
									document.formulario[i+5].value		= "";	//Cidade
									break;
								}
							}
						}
					}
					
					if(document.getElementById("quadroBuscaPais").style.display	==	"block"){
						vi_id('quadroBuscaPais', false);
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
	function busca_pessoa_estado(IdPais,IdEstado,Erro,Local,Endereco){
		if(Local == '' || Local == undefined){
			Local	=	document.formulario.Local.value;
		}
		if(IdPais == ''){
			IdPais = 0;
		}
		if(IdEstado == ''){
			IdEstado = 0;
		}
		if(Endereco=='' || Endereco == undefined){
			return false;
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
						document.formulario.Erro.value = 0;
						verificaErro();
					}
					if(xmlhttp.responseText == 'false'){
						if(document.formulario.CadastroResumido.value == 1){
							document.formulario.IdEstado_Resumido.value = '';
							document.formulario.Estado_Resumido.value = '';
							document.formulario.IdEstado_Resumido.focus();
							if(document.formulario.IdPais_Resumido.value == ""){
								document.formulario.IdPais_Resumido.focus();
							}
							
						}
						for(i=0;i<document.formulario.length;i++){
							if(document.formulario[i].name.substr(0,9) == 'IdEstado_'){
								var temp	=	document.formulario[i].name.split("_");
								if(temp[1] == Endereco){
									document.formulario[i].value 		= '';	//IdEstado
									document.formulario[i+1].value		= '';	//Estado
									
									document.formulario[i+2].value 		= '';	//IdCidade
									document.formulario[i+3].value		= '';	//Cidade
									
									if(document.formulario[i-2].value == ""){
										document.formulario[i-2].focus();
									}else{
										document.formulario[i].focus();
									}
									break;
								}
							}
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
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("SiglaEstado")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var SiglaEstado = nameTextNode.nodeValue;
						
						if(Endereco == 1){
							document.formulario.SiglaEstadoIE.value = SiglaEstado;
						}
						
						if(document.formulario.CadastroResumido.value == 1){
							if(document.formulario.IdPais_Resumido.value == ""){
								document.formulario.IdPais_Resumido.focus();
								return false;
							}
							document.formulario.IdEstado_Resumido.value = IdEstado;
							document.formulario.Estado_Resumido.value = NomeEstado;
						}
						
						for(i=0;i<document.formulario.length;i++){
							if(document.formulario[i].name.substr(0,9) == 'IdEstado_'){
								var temp	=	document.formulario[i].name.split("_");
								if(temp[1] == Endereco){
									document.formulario[i].value 		= IdEstado;		//IdEstado
									document.formulario[i+1].value		= NomeEstado;	//Estado
									
									document.formulario[i+2].value 		= '';		//IdCidade
									document.formulario[i+3].value		= '';		//Cidade
									
									break;
								}
							}
						}
					}
					if(document.getElementById("quadroBuscaEstado").style.display	==	"block"){
						document.getElementById("quadroBuscaEstado").style.display	=	"none";
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
	function busca_pessoa_cidade(IdPais,IdEstado,IdCidade,Erro,Local,Endereco){
		if(IdPais == '')	IdPais = 0;
		if(IdEstado == '')	IdEstado = 0;
		if(IdCidade == '')	IdCidade = 0;
		
		if(Local == '' || Local == undefined){
			Local = document.formulario.Local.value;
		}
		if(Endereco=='' || Endereco == undefined){
			return false;
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
						document.formulario.Erro.value = 0;
						verificaErro();
					}
					if(xmlhttp.responseText == 'false'){
						if(document.formulario.CadastroResumido.value == 1){
							document.formulario.IdCidade_Resumido.value 	= '';
							document.formulario.Cidade_Resumido.value 		= '';
							document.formulario.Cidade_Resumido.focus();
							
							if(document.formulario.IdEstado_Resumido.value == ""){
								document.formulario.IdEstado_Resumido.focus();
							}
							
							if(document.formulario.IdPais_Resumido.value == ""){
								document.formulario.IdPais_Resumido.focus();
							}
							
						}
						for(i=0;i<document.formulario.length;i++){
							if(document.formulario[i].name.substr(0,9) == 'IdCidade_'){
								var temp	=	document.formulario[i].name.split("_");
								if(temp[1] == Endereco){
									document.formulario[i].value 		= '';	//IdEstado
									document.formulario[i+1].value		= '';	//Estado
									
									if(document.formulario.IdPessoa.value != ''){																		
										if(document.formulario[i-4].value == ""){
											document.formulario[i-4].focus();
										}else if(document.formulario[i-2].value == ""){
											document.formulario[i-2].focus();
										}else{
											document.formulario[i].focus();
										}
									}
									
									if(document.formulario.IdEnderecoDefault.value == Endereco){
										document.formulario.SiglaEstado.value	=	'';
									}
									break;
								}
							}
						}				
						// Fim de Carregando
						carregando(false);
					}else{
						nameNode = xmlhttp.responseXML.getElementsByTagName("IdCidade")[0]; 
						nameTextNode = nameNode.childNodes[0];
						IdCidade = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("NomeCidade")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var NomeCidade = nameTextNode.nodeValue;					
							
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
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("SiglaEstado")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var SiglaEstado = nameTextNode.nodeValue;
						
						if(document.formulario.CadastroResumido.value == 1){
							if(document.formulario.IdPais_Resumido.value == ""){
								document.formulario.IdPais_Resumido.focus();
								return false;
							}
							if(document.formulario.IdEstado_Resumido.value == ""){
								document.formulario.IdEstado_Resumido.focus();
								return false;
							}
							
							document.formulario.IdCidade_Resumido.value = IdCidade;
							document.formulario.Cidade_Resumido.value = NomeCidade;
						}
						
						for(i=0;i<document.formulario.length;i++){
							if(document.formulario[i].name.substr(0,7) == 'IdPais_'){
								var temp	=	document.formulario[i].name.split("_");
								if(temp[1] == Endereco){
									document.formulario[i].value 		= IdPais;		//IdPais
									document.formulario[i+1].value		= NomePais;		//Pais
								
									document.formulario[i+2].value 		= IdEstado;		//IdEstado
									document.formulario[i+3].value		= NomeEstado;	//Estado
									
									document.formulario[i+4].value 		= IdCidade;		//IdCidade
									document.formulario[i+5].value		= NomeCidade;	//Cidade
									
									if(document.formulario.IdEnderecoDefault.value == Endereco){
										document.formulario.SiglaEstado.value	=	SiglaEstado;
									}
									break;
								}
							}
						}
					}
					if(document.getElementById("quadroBuscaCidade").style.display	==	"block"){
						vi_id('quadroBuscaCidade', false);
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
	function busca_pessoa_cep(CEP, Erro, cpEndereco){
		if(CEP == ''){
			return false;
		}
		var temp = true;
		for(i=0;i<document.formulario.length;i++){
			if(document.formulario[i].name.substr(0,9) == 'Endereco_'){
				aux	=	document.formulario[i].name.split('_');
				if(aux[1] == cpEndereco){
					if(document.formulario[i].value != ""){
						temp	=	atualizar();
						break;
					}
				}
			}
		}
		
		if(temp == true){
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
	
		   	url = "../administrativo/xml/cep.php?CEP="+CEP;
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
						if(xmlhttp.responseText != 'false'){
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("IdPais")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var IdPais = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("IdEstado")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var IdEstado = nameTextNode.nodeValue;
						
							nameNode = xmlhttp.responseXML.getElementsByTagName("SiglaEstado")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var SiglaEstado = nameTextNode.nodeValue;
					
							nameNode = xmlhttp.responseXML.getElementsByTagName("IdCidade")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var IdCidade = nameTextNode.nodeValue;					
						
							nameNode = xmlhttp.responseXML.getElementsByTagName("Endereco")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var Endereco = nameTextNode.nodeValue;
						
							nameNode = xmlhttp.responseXML.getElementsByTagName("Bairro")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var Bairro = nameTextNode.nodeValue;
							
							if(cpEndereco == 1){
								document.formulario.SiglaEstadoIE.value = SiglaEstado;
							}
							
							for(i=0;i<document.formulario.length;i++){
								if(document.formulario[i].name.substr(0,9) == 'Endereco_'){
									aux	=	document.formulario[i].name.split('_');
									if(aux[1] == cpEndereco){
										document.formulario[i].value	=	Endereco;	//Endereco
										document.formulario[i+3].value	=	Bairro;		//Bairro
										break;
									}
								}
							}						
							busca_pessoa_cidade(IdPais,IdEstado,IdCidade,false,document.formulario.Local.value,cpEndereco);		
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
	function atualizar(){
		return confirm("ATENCAO!\n\nDeseja atualizar endere?","SIM","NAO");
	}
	function excluir_endereco(pos){
		if(document.formulario.QtdEnderecoAux.value > 1){
			if(confirm("ATENCAO!\n\nVoce esta prestes a excluir um endereco.\nDeseja continuar?","SIM","NAO") == true){
				var valor = 0;
				for(i=0;i<document.formulario.length;i++){
					if(document.formulario[i].name.substr(0,17) == 'IdPessoaEndereco_'){
						aux	=	document.formulario[i].name.split('_');
						if(aux[1] == pos){
							valor	=	document.formulario[i].value;
							
							if(document.formulario.Acao.value == 'alterar'){
								excluir_pessoa_endereco(document.formulario.IdPessoa.value,valor,pos);
							}else{
								for(ii=0;ii<document.formulario.IdEnderecoDefault.length;ii++){
									if(document.formulario.IdEnderecoDefault[ii].value == document.formulario[i].value){
										document.formulario.IdEnderecoDefault.options[ii] = null;
										ii = document.formulario.IdEnderecoDefault.length;;
									}
								}
								
								document.formulario.QtdEnderecoAux.value	=	parseInt(document.formulario.QtdEnderecoAux.value) - 1;
		
								document.getElementById("formEndereco_"+pos).innerHTML		=	"";
								document.getElementById("formEndereco_"+pos).style.height	=	"0";
								
								for(var i=0; i<document.getElementById('tableEndereco').rows.length; i++){
									if(pos == document.getElementById('tableEndereco').rows[i].accessKey){
										document.getElementById('tableEndereco').deleteRow(i);
										break;
									}
								}
							}
						}
					}
				}
			}	
		}else{
			alert("ATENCAO!\n\n?obrigat?rio pelo menos um endere?");
		}
	}
	function atualiza_opcoes_endereco(pos,valor){
		var temp = 0, aux;
		var end1 = document.formulario.IdEnderecoDefault.value;
		
		if(document.formulario.Acao.value == 'alterar'){
			for(i=0;i<document.formulario.length;i++){
				if(document.formulario[i].name.substr(0,17) == 'IdPessoaEndereco_'){
					aux	=	document.formulario[i].name.split('_');
					if(aux[1] == pos){
						for(ii=0;ii<document.formulario.IdEnderecoDefault.length;ii++){
							if(document.formulario.IdEnderecoDefault[ii].value == document.formulario[i].value){
								document.formulario.IdEnderecoDefault.options[ii] = null;
								addOption(document.formulario.IdEnderecoDefault,valor,document.formulario[i].value);
								temp = 1;
								ii = document.formulario.IdEnderecoDefault.length;;
							}
						}
						
						if(temp == 0){
							addOption(document.formulario.IdEnderecoDefault,valor,pos);
						}
						for(ii=0;ii<document.formulario.IdEnderecoDefault.length;ii++){
							if(document.formulario.IdEnderecoDefault[ii].value == end1){
								document.formulario.IdEnderecoDefault[ii].selected = true;
								ii = document.formulario.IdEnderecoDefault.length;;
							}
						}
						
						document.getElementById('titEndereco_'+pos).innerHTML	=	valor;	
						break;
					}
				}
			}
		}
	}
	function busca_pessoa_endereco(IdPessoa,IdEnderecoDefault){
		if(IdPessoa == '' || IdPessoa == undefined){
			IdPessoa = 0;
		}
		if(IdEnderecoDefault == undefined){
			IdEnderecoDefault = '';
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
	    
	   	url = "../administrativo/xml/pessoa_endereco.php?IdPessoa="+IdPessoa;
	   	
	   	xmlhttp.open("GET", url,true);
		
		xmlhttp.onreadystatechange = function(){ 
	
			// Carregando...
			carregando(true);
	
			if(xmlhttp.readyState == 4){ 
				if(xmlhttp.status == 200){
					if(xmlhttp.responseText != 'false'){
						var IdPessoaEndereco,CEP,Endereco,Complemento,Numero,Bairro,IdPais,NomePais,IdEstado,NomeEstado;
						var SiglaEstado,IdCidade,NomeCidade;
						var aux	=	1;
						
						while(document.formulario.IdEnderecoDefault.options.length > 0){
							document.formulario.IdEnderecoDefault.options[0] = null;
						}
						
						for(var i=0; i<xmlhttp.responseXML.getElementsByTagName("IdPessoaEndereco").length; i++){
							nameNode = xmlhttp.responseXML.getElementsByTagName("IdPessoaEndereco")[i]; 
							nameTextNode = nameNode.childNodes[0];
							IdPessoaEndereco = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("CEP")[i]; 
							nameTextNode = nameNode.childNodes[0];
							CEP = nameTextNode.nodeValue;	
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("Endereco")[i]; 
							nameTextNode = nameNode.childNodes[0];
							Endereco = nameTextNode.nodeValue;	
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("Complemento")[i]; 
							nameTextNode = nameNode.childNodes[0];
							Complemento = nameTextNode.nodeValue;	
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("Numero")[i]; 
							nameTextNode = nameNode.childNodes[0];
							Numero = nameTextNode.nodeValue;	
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("Bairro")[i]; 
							nameTextNode = nameNode.childNodes[0];
							Bairro = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("IdPais")[i]; 
							nameTextNode = nameNode.childNodes[0];
							IdPais = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("NomePais")[i]; 
							nameTextNode = nameNode.childNodes[0];
							NomePais = nameTextNode.nodeValue;	
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("IdEstado")[i]; 
							nameTextNode = nameNode.childNodes[0];
							IdEstado = nameTextNode.nodeValue;	
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("NomeEstado")[i]; 
							nameTextNode = nameNode.childNodes[0];
							NomeEstado = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("SiglaEstado")[i]; 
							nameTextNode = nameNode.childNodes[0];
							SiglaEstado = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("IdCidade")[i]; 
							nameTextNode = nameNode.childNodes[0];
							IdCidade = nameTextNode.nodeValue;	
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("NomeCidade")[i]; 
							nameTextNode = nameNode.childNodes[0];
							NomeCidade = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("NomeResponsavelEndereco")[i]; 
							nameTextNode = nameNode.childNodes[0];
							NomeResponsavelEndereco = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoEndereco")[i]; 
							nameTextNode = nameNode.childNodes[0];
							DescricaoEndereco = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("EmailEndereco")[i]; 
							nameTextNode = nameNode.childNodes[0];
							EmailEndereco = nameTextNode.nodeValue;	
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("Telefone")[i]; 
							nameTextNode = nameNode.childNodes[0];
							Telefone = nameTextNode.nodeValue;	
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("Celular")[i]; 
							nameTextNode = nameNode.childNodes[0];
							Celular = nameTextNode.nodeValue;	
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("ComplementoTelefone")[i]; 
							nameTextNode = nameNode.childNodes[0];
							ComplementoTelefone = nameTextNode.nodeValue;	
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("Fax")[i]; 
							nameTextNode = nameNode.childNodes[0];
							Fax = nameTextNode.nodeValue;	
							
							
							if(IdPessoaEndereco == IdEnderecoDefault){
								document.formulario.SiglaEstadoIE.value = document.formulario.SiglaEstado.value = SiglaEstado;
							}
							
							for(ii=0;ii<document.formulario.length;ii++){
								if(document.formulario[ii].name.substr(0,17) == 'IdPessoaEndereco_'){
									var temp	=	document.formulario[ii].name.split("_");
									if(temp[1] == aux){
										document.getElementById("titEndereco_"+aux).innerHTML	= DescricaoEndereco;
										
										document.formulario[ii].value 			= IdPessoaEndereco;		
										document.formulario[ii+1].value 		= DescricaoEndereco;		
										document.formulario[ii+2].value 		= NomeResponsavelEndereco;		
										document.formulario[ii+3].value 		= CEP;		
										document.formulario[ii+4].value 		= Endereco;		
										document.formulario[ii+5].value 		= Numero;		
										document.formulario[ii+6].value 		= Complemento;		
										document.formulario[ii+7].value 		= Bairro;		
										document.formulario[ii+8].value 		= IdPais;		
										document.formulario[ii+9].value 		= NomePais;	
										document.formulario[ii+10].value		= IdEstado;		
										document.formulario[ii+11].value		= NomeEstado;		
										document.formulario[ii+12].value		= IdCidade;		
										document.formulario[ii+13].value		= NomeCidade;		
										document.formulario[ii+14].value		= Telefone;			
										document.formulario[ii+15].value		= Celular;			
										document.formulario[ii+16].value		= Fax;			
										document.formulario[ii+17].value		= ComplementoTelefone;	
										document.formulario[ii+18].value		= EmailEndereco;	
										break;
									}
								}
							}
							
							addOption(document.formulario.IdEnderecoDefault,DescricaoEndereco,IdPessoaEndereco);
							
							aux++;
						}
						
						if(IdEnderecoDefault!=""){
							for(i=0;i<document.formulario.IdEnderecoDefault.length;i++){
								if(document.formulario.IdEnderecoDefault[i].value == IdEnderecoDefault){
									document.formulario.IdEnderecoDefault[i].selected	=	true;
									break;
								}
							}
						}else{
							document.formulario.IdEnderecoDefault[0].selected	=	true;
						}
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
	function como_chegar_pessoa(Destino){
		if(Destino == undefined || Destino == ', , ,'){
			Destino = '';
		}
		
		var vetTemp = Destino.split(', ');
		
		if(vetTemp.length < 3){
			return false;
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
		
		url = "xml/loja.php";
		xmlhttp.open("GET", url,true);
		xmlhttp.onreadystatechange = function(){
			if(xmlhttp.readyState == 4){ 
				if(xmlhttp.status == 200){
					if(xmlhttp.responseText != 'false'){
						nameNode = xmlhttp.responseXML.getElementsByTagName("Endereco")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var Endereco = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("Numero")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var Numero = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("CEP")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var CEP = nameTextNode.nodeValue;	
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("Bairro")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var Bairro = nameTextNode.nodeValue;	
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("Complemento")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var Complemento = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("NomeCidade")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var NomeCidade = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("SiglaEstado")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var SiglaEstado = nameTextNode.nodeValue;
						
						if(Destino == ''){
							for(ii=0;ii<document.formulario.length;ii++){
								if(document.formulario[ii].name.substr(0,17) == 'IdPessoaEndereco_'){
									var temp	=	document.formulario[ii].name.split("_");
									if(temp[1] == document.formulario.IdEnderecoDefault.value){
										Destino 	= document.formulario[ii+4].value+", "+document.formulario[ii+5].value+", "+document.formulario[ii+13].value+", "+document.formulario.SiglaEstado.value;
										break;
									}
								}
							}
						}
						
						var Origem	= Endereco+", "+Numero+", "+NomeCidade+", "+SiglaEstado;
						
						Origem	= removeAcento(Origem);
						Destino	= removeAcento(Destino);
						
						como_chegar_direciona(Origem,Destino);
					}
				}
			}
			return true;
		}
		xmlhttp.send(null);
	}
	function excluir_pessoa_endereco(IdPessoa,IdPessoaEndereco,pos){
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

		url = "files/excluir/excluir_pessoa_endereco.php?IdPessoa="+IdPessoa+"&IdPessoaEndereco="+IdPessoaEndereco;
		xmlhttp.open("GET", url,true);
		
		xmlhttp.onreadystatechange = function(){ 
			// Carregando...
			carregando(true);
			if(xmlhttp.readyState == 4){ 
				if(xmlhttp.status == 200){
					var erro = parseInt(xmlhttp.responseText);
					if(erro == 7){
						for(ii=0;ii<document.formulario.IdEnderecoDefault.length;ii++){
							if(document.formulario.IdEnderecoDefault[ii].value == IdPessoaEndereco){
								document.formulario.IdEnderecoDefault.options[ii] = null;
								ii = document.formulario.IdEnderecoDefault.length;;
							}
						}
								
						document.formulario.QtdEnderecoAux.value	=	parseInt(document.formulario.QtdEnderecoAux.value) - 1;
		
						document.getElementById("formEndereco_"+pos).innerHTML		=	"";
						document.getElementById("formEndereco_"+pos).style.height	=	"0";
						
						for(var i=0; i<document.getElementById('tableEndereco').rows.length; i++){
							if(pos == document.getElementById('tableEndereco').rows[i].accessKey){
								document.getElementById('tableEndereco').deleteRow(i);
								break;
							}
						}
						
						mensagens(erro);
					}else{
						mensagens(erro);
					}
				}
				// Fim de Carregando
				carregando(false);
			}
			return true;
		}
		xmlhttp.send(null);
	}
	
	function tela_aviso(){
		window.open("../../aplicacoes/aviso/index.php?IdLoja="+document.formulario.IdLoja.value+"&IdPessoa="+document.formulario.IdPessoa.value);
	}
	
	function cda(){
		window.open("../cda/rotinas/autentica.php?CPF_CNPJ="+document.formulario.CPF_CNPJ.value+"&Senha="+document.formulario.Senha.value);
	}
	
	function gerar_senha(){	
		document.formulario.Senha.value = Math.random() * 100000;
		document.formulario.Senha.value = Math.round(parseInt(Math.floor(document.formulario.Senha.value)));
	}
	
	function busca_atualizacao_cadastro(IdPessoa){
		if(IdPessoa == undefined || IdPessoa==''){
			IdPessoa = 0;
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
	    
	   	url = "./xml/pessoa_atualizacao_cadastro.php?IdPessoa="+IdPessoa;
		xmlhttp.open("GET", url,true);
		xmlhttp.onreadystatechange = function(){ 
			// Carregando...
			carregando(true);
	
			if(xmlhttp.readyState == 4){ 
				if(xmlhttp.status == 200){
					if(xmlhttp.responseText == 'false'){
						document.getElementById('cp_atualizacao_cadastro').style.display = "none";
						// Fim de Carregando
						carregando(false);
					}else{
						document.getElementById('cp_atualizacao_cadastro').style.display = "block";
						
						while(document.getElementById('tabelaAtualizacaoCadastro').rows.length > 2){
							document.getElementById('tabelaAtualizacaoCadastro').deleteRow(1);
						}
						
						var cabecalho, tam, linha, c0, c1, c2, c3, c4, c5, c6, c7, c8;
						
						for(var i=0; i<xmlhttp.responseXML.getElementsByTagName("IdPessoa").length; i++){
							nameNode = xmlhttp.responseXML.getElementsByTagName("IdPessoa")[i]; 
							nameTextNode = nameNode.childNodes[0];
							var IdPessoa = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("IdPessoaSolicitacao")[i]; 
							nameTextNode = nameNode.childNodes[0];
							var IdPessoaSolicitacao = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("Nome")[i]; 
							nameTextNode = nameNode.childNodes[0];
							var Nome = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("DataCriacao")[i]; 
							nameTextNode = nameNode.childNodes[0];
							var DataCriacao = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("IP")[i]; 
							nameTextNode = nameNode.childNodes[0];
							var IP = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("LoginAprovacao")[i]; 
							nameTextNode = nameNode.childNodes[0];
							var LoginAprovacao = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("DataAprovacao")[i]; 
							nameTextNode = nameNode.childNodes[0];
							var DataAprovacao = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("Navegador")[i]; 
							nameTextNode = nameNode.childNodes[0];
							var Navegador = nameTextNode.nodeValue;
														
							nameNode = xmlhttp.responseXML.getElementsByTagName("Cor")[i]; 
							nameTextNode = nameNode.childNodes[0];
							var Cor = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("Status")[i]; 
							nameTextNode = nameNode.childNodes[0];
							var Status = nameTextNode.nodeValue;
							
							tam 	= document.getElementById('tabelaAtualizacaoCadastro').rows.length;
							linha	= document.getElementById('tabelaAtualizacaoCadastro').insertRow(tam-1);
							
							if(tam%2 == 0){
								linha.style.backgroundColor = Cor;
							}
							
							var linkIni = "<a href='cadastro_pessoa_solicitacao.php?IdPessoaSolicitacao=" + IdPessoaSolicitacao + "' target='_blank'>";
							var linkFim = "</a>";
							
							linha.accessKey = IdPessoa + "_" + IdPessoaSolicitacao; 
							
							c0					= linha.insertCell(0);
							c1					= linha.insertCell(1);
							c2					= linha.insertCell(2);
							c3					= linha.insertCell(3);
							c4					= linha.insertCell(4);
							c5					= linha.insertCell(5);
							c6					= linha.insertCell(6);
							c7					= linha.insertCell(7);
							c8					= linha.insertCell(8);
							
							c0.innerHTML		= linkIni + IdPessoaSolicitacao + linkFim;
							c0.style.padding	= "0 2px 0 5px";
							
							c1.innerHTML		= linkIni + Nome + linkFim;
							c1.style.padding	= "0 2px 0 2px";
							
                            c2.innerHTML		= linkIni + dateFormat(DataCriacao) + linkFim;
							c2.style.padding	= "0 2px 0 2px";
                            
							c3.innerHTML		= linkIni + IP + linkFim;
							c3.style.padding	= "0 2px 0 2px";
                            
							c4.innerHTML		= linkIni + LoginAprovacao + linkFim;
							c4.style.padding	= "0 2px 0 2px";
							
							c5.innerHTML		= linkIni + dateFormat(DataAprovacao) + linkFim;
							c5.style.padding	= "0 2px 0 2px";
																					
							c6.innerHTML		= linkIni + Navegador + linkFim;
							c6.style.padding	= "0 2px 0 2px";
							
							c7.innerHTML		= linkIni + Status + linkFim;
							c7.style.padding	= "0 2px 0 2px";
							
							c8.innerHTML		= "&nbsp;";

						}
						
						document.getElementById("tabelaAtualizacaoCadastroTotal").innerHTML = "Total: " + i;
					}
				}
				// Fim de Carregando
				carregando(false);
			} 
			return true;
		}
		xmlhttp.send(null);
	}
	function addArquivo(IdArquivo){
		document.formulario.CountArquivo.value = parseInt(document.formulario.CountArquivo.value) + parseInt(1);
		
		if(IdArquivo=='' || IdArquivo == undefined){
			IdArquivo = '';	
		}
		
		var CountArquivo = parseInt(document.formulario.CountArquivo.value);
		var tam, linha, c0, c1, c2, c3, c4;
		var tabindex = CountArquivo+200;
		
		if(CountArquivo > document.formulario.MaxUploads.value && document.formulario.MaxUploads.value != ''){
			return false;
		}
		
		tam 	= document.getElementById('tabelaArquivos').rows.length;
		linha	= document.getElementById('tabelaArquivos').insertRow(tam);
		linha.accessKey = tam;
		
		c0	= linha.insertCell(0);
		c1	= linha.insertCell(1);
		c2	= linha.insertCell(2);
		c3	= linha.insertCell(3);
		c4	= linha.insertCell(4);
		
		c0.className	= "find";		
		c0.innerHTML	= "&nbsp;";
		c1.className	= "descCampo";
		c1.innerHTML	= "Anexar Arquivo";
		c2.className	= "separador";
		c2.innerHTML	= "&nbsp;";
		c3.className	= "descCampo";
		c3.innerHTML	= "<B id='titDescricaoArquivo_"+CountArquivo+"' style='color:#000;'>Descri&#231;&#227;o do Arquivo</B>";
		c4.className	= "find";
		c4.innerHTML	= "&nbsp;";
		
		tam		= document.getElementById('tabelaArquivos').rows.length;
		linha	= document.getElementById('tabelaArquivos').insertRow(tam);
		linha.accessKey = tam;
		
		c0	= linha.insertCell(0);
		c1	= linha.insertCell(1);
		c2	= linha.insertCell(2);
		c3	= linha.insertCell(3);
		c4	= linha.insertCell(4);
		
		c0.className			= "find";
		c0.innerHTML			= "&nbsp;";
		c1.className			= "campo";
		c1.style.width			= "450px";
		if(verificar_PermissaoInserirAnexo() == false){
			c1.innerHTML			= "<input type='text' disabled name='fakeupload_"+CountArquivo+"' onclick=\"verificar_PermissaoInserirAnexo();verificaErro();\" value='' autocomplete='off' style='width:356px; margin:0px;' onchange='verificar_obrigatoriedade(this, document.formulario.DescricaoArquivo_"+CountArquivo+", "+CountArquivo+");' onFocus=\"Foco(this,'in','auto');\" onBlur=\"Foco(this,'out');\" tabindex='"+tabindex+"'><div id='bt_procurar' onclick=\"verificar_PermissaoInserirAnexo();verificaErro()\" style='margin:-22px 0px 0px 360px;' tabindex='"+tabindex+"'></div><input type='text'  id='realupload' name='EndArquivo_"+CountArquivo+"' size='1' class='realupload' onchange='verificar_obrigatoriedade(this, document.formulario.DescricaoArquivo_"+CountArquivo+", "+CountArquivo+"); document.formulario.fakeupload_"+CountArquivo+".value = this.value;' /><div style='margin:-1px 0px 4px 0px;'>Aten&#231;&#227;o, tamanho m&#225;ximo do arquivo &#233; "+document.formulario.MaxSize.value+". <span title='"+document.formulario.ExtensaoAnexo.value.replace(/,/g, ', ')+".'>Tipos de arquivo a anexar.<span></div>";						
		}else{
			c1.innerHTML			= "<input type='text' name='fakeupload_"+CountArquivo+"' value='' autocomplete='off' style='width:356px; margin:0px;' onchange='verificar_obrigatoriedade(this, document.formulario.DescricaoArquivo_"+CountArquivo+", "+CountArquivo+");' onFocus=\"Foco(this,'in','auto');\" onBlur=\"Foco(this,'out');\" tabindex='"+tabindex+"'><div id='bt_procurar' style='margin:-22px 0px 0px 360px;' tabindex='"+tabindex+"'></div><input type='file' id='realupload' name='EndArquivo_"+CountArquivo+"' size='1' class='realupload' onchange='verificar_obrigatoriedade(this, document.formulario.DescricaoArquivo_"+CountArquivo+", "+CountArquivo+"); document.formulario.fakeupload_"+CountArquivo+".value = this.value;' /><div style='margin:-1px 0px 4px 0px;'>Aten&#231;&#227;o, tamanho m&#225;ximo do arquivo &#233; "+document.formulario.MaxSize.value+". <span title='"+document.formulario.ExtensaoAnexo.value.replace(/,/g, ', ')+".'>Tipos de arquivo a anexar.<span></div>";			
		}
		c2.className			= "separador";
		c2.innerHTML			= "&nbsp;";
		c3.className			= "campo";
		c3.style.verticalAlign	= "top";
		c3.innerHTML			= "<input type='text' name='DescricaoArquivo_"+CountArquivo+"' value='' style='width:338px; margin-top:0px;' tabindex='"+tabindex+"' maxlength='100' onFocus=\"Foco(this,'in');\"  onBlur=\"Foco(this,'out');\" readOnly>";
		c4.className			= "find";
		c4.style.verticalAlign	= "top";
		c4.innerHTML			= "<img src='../../img/estrutura_sistema/ico_del.gif' style='margin-top:6px;' alt='Excluir title='Excluir' onClick=\"excluir_arquivo('"+CountArquivo+"','"+IdArquivo+"');\">";
	}
	
	function verificar_PermissaoInserirAnexo(){		
		if(document.formulario.PermissaoInserirAnexo.value == ''){			
			document.formulario.Erro.value = 2;
			return false;
		}
	}
	
	function verificar_obrigatoriedade(campo1, campo2, CountArquivo){
		if(campo1.value != ''){
			var temp = campo1.value.split('.');
			var ext = temp[temp.length-1].toLowerCase();
			document.formulario.Erro.value = 0;
			
			if(!document.formulario.ExtensaoAnexo.value.split(',').in_array(ext) && ext != ''){
				for(i = 0; i<document.formulario.length; i++){
					if(document.formulario[i].name != undefined){
						if(document.formulario[i].name == campo1.name){
							document.formulario.Erro.value = 192;
							
							document.formulario[i-1].focus();
						}
					}
				}
			} else{
				for(i = 0; i<document.formulario.length; i++){
					if(document.formulario[i].name != undefined){
						if(document.formulario[i].name.substring(0,11) == 'EndArquivo_'){
							var temp = document.formulario[i].value.split('.');
							var ext = temp[temp.length-1].toLowerCase();
							
							if(!document.formulario.ExtensaoAnexo.value.split(',').in_array(ext) && ext != ''){
								mensagens(192);
								document.formulario[i-1].focus();
							}
						}
					}
				}
			}
			
			document.getElementById("titDescricaoArquivo_"+CountArquivo).style.color = "#C10000";
			campo2.readOnly = false;
			verificaErro();
		} else{
			document.getElementById("titDescricaoArquivo_"+CountArquivo).style.color = "#000000";
			campo2.readOnly = true;
			campo2.value = '';
		}
	}
	function excluir_arquivo(pos, IdArquivo){
		if(document.formulario.PermissaoExcluirAnexo.value == ''){
			document.formulario.Erro.value = 2;
			verificaErro();
			return false;
		}
		if(IdArquivo == ''){
			if((document.formulario.bt_alterar.disabled == true) && (document.formulario.bt_inserir.disabled == true) && parseInt(document.formulario.CountArquivo.value) > 0){
				return false;
			}
			
			if(confirm("ATENÇÃO\n\nVocê está prestes a excluir este arquivo.\nDeseja continuar?") == false){
				return false;
			}
			
			var tam = document.getElementById('tabelaArquivos').rows.length;
			var cont = 1;
			
			for(var i=1; i<=tam; i++){
				if(document.getElementById('tabelaArquivos').rows[i].accessKey == (pos*2)-1){
					document.getElementById('tabelaArquivos').deleteRow(i);
					document.getElementById('tabelaArquivos').deleteRow(i-1);
					i=tam;
					document.formulario.CountArquivo.value	= parseInt(document.formulario.CountArquivo.value) - 1;
					break;
				}
			}
			
			if(parseInt(document.formulario.CountArquivo.value) == 0){
				addArquivo();
			}
		} else{
			if(confirm("ATENÇÃO\n\nA exclusão será efetuada após a alteração da pessoa.\nDeseja continuar?") == false){
				return false;
			}
			
			var tam = document.getElementById('tabelaArquivosAnexados').rows.length-2;
			
			for(var i = 1; i <= tam; i++){
				if(document.getElementById('tabelaArquivosAnexados').rows[i].accessKey == IdArquivo){
					document.getElementById('tabelaArquivosAnexados').deleteRow(i);
					
					document.formulario.EcluirAnexos.value += ',' + IdArquivo;
					break;
				}
			}
			
			tam = (document.getElementById('tabelaArquivosAnexados').rows.length-2);
			
			if(tam > 0){
				document.getElementById("cpArquivosAnexados").style.display = "block";
				document.getElementById('tabelaValorTotal').innerHTML = "Total: " + tam;
			} else {
				document.getElementById("cpArquivosAnexados").style.display = "none";
			}
		}
	}
	function buscar_arquivo(IdPessoa){
		if(IdPessoa == '' || IdPessoa == undefined){
			IdPessoa = 0;
		}
		
		var xmlhttp = false;
		
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
		
		url = "./xml/pessoa_anexo.php?IdPessoa="+IdPessoa;
		xmlhttp.open("GET", url,true);
		xmlhttp.onreadystatechange = function(){ 
			// Carregando...
			carregando(true);
			if(xmlhttp.readyState == 4){ 
				if(xmlhttp.status == 200){
					if(xmlhttp.responseText == 'false'){
						document.getElementById("cpArquivosAnexados").style.display = "none";
						
						while(document.getElementById('tabelaArquivosAnexados').rows.length > 2){
							document.getElementById('tabelaArquivosAnexados').deleteRow(1);
						}
					}else{
						document.getElementById("cpArquivosAnexados").style.display = "block";
						
						while(document.getElementById('tabelaArquivosAnexados').rows.length > 2){
							document.getElementById('tabelaArquivosAnexados').deleteRow(1);
						}
						
						var  tam, linha, c0, c1, c2, c3, c4, c5, linkIni, linkFin;
						var nameNode, nameTextNode, EndArquivo, IdPessoa, IdAnexo, Anexo, DescricaoAnexo, NomeOriginal, MD5;
						
						for(var i=0; i<xmlhttp.responseXML.getElementsByTagName("IdPessoa").length; i++){
							nameNode = xmlhttp.responseXML.getElementsByTagName("IdPessoa")[i]; 
							nameTextNode = nameNode.childNodes[0];
							IdPessoa = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("IdAnexo")[i]; 
							nameTextNode = nameNode.childNodes[0];
							IdAnexo = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("IMG")[i]; 
							nameTextNode = nameNode.childNodes[0];
							IMG = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("Tamanho")[i]; 
							nameTextNode = nameNode.childNodes[0];
							Tamanho = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoAnexo")[i]; 
							nameTextNode = nameNode.childNodes[0];
							DescricaoAnexo = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("NomeOriginal")[i]; 
							nameTextNode = nameNode.childNodes[0];
							NomeOriginal = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("MD5")[i]; 
							nameTextNode = nameNode.childNodes[0];
							MD5 = nameTextNode.nodeValue;
							
							tam 	= document.getElementById('tabelaArquivosAnexados').rows.length;
							linha	= document.getElementById('tabelaArquivosAnexados').insertRow(tam-1);
							
							if(i%2 != 0){
								linha.style.backgroundColor = "#E2E7ED";
							}
							
							linha.accessKey = IdAnexo; 
							
							c0	= linha.insertCell(0);
							c1	= linha.insertCell(1);
							c2	= linha.insertCell(2);
							c3	= linha.insertCell(3);
							c4	= linha.insertCell(4);
							c5	= linha.insertCell(5);
							
							linkIni	= "<a href='./download_anexo_pessoa.php?Anexo=" + MD5 + "'>";
							linkFin	= "</a>";
							
							c0.innerHTML		= linkIni + IMG + linkFin;
							c0.style.padding	= "2px 0 2px 5px";
							
							c1.innerHTML		= linkIni + NomeOriginal + linkFin;
							c1.style.padding	= "2px 2px 2px 0px";
							
							c2.innerHTML		= linkIni + DescricaoAnexo + linkFin;
							c2.style.padding	= "2px 2px 2px 0px";
							
							c3.innerHTML		= linkIni + Tamanho + linkFin;
							c3.style.width		= "88px";
							c3.style.padding	= "2px 2px 2px 0px";
							
							c4.innerHTML		= linkIni + "Clique aqui" + linkFin;
							c4.style.width		= "72px";
							c4.style.padding	= "2px 2px 2px 0px";
							
							if(document.formulario.bt_alterar.disabled){
								c5.innerHTML	= "<img src='../../img/estrutura_sistema/ico_del_c.gif' alt='Excluir'>";
							} else{
								c5.innerHTML	= "<img src='../../img/estrutura_sistema/ico_del.gif' alt='Excluir' title='Excluir' onClick=\"excluir_arquivo('" + tam + "','" + IdAnexo + "');\">";
							}
							c5.style.cursor		= "pointer";
							c5.style.padding	= "2px 2px 2px 0px";
						}
						
						document.getElementById('tabelaValorTotal').innerHTML = "Total: " + i;
					}
				}
				// Fim de Carregando
				carregando(false);
			}
			return true;
		}
		xmlhttp.send(null);
	}
	function verifica_email_cobranca(){
		var email_style = document.getElementById("Email").getAttribute("style");
		if(email_style != null){
			var email = email_style.split(";");
			var email2 = email[1].split(": ");
			
			if(document.formulario.Cob_FormaEmail.checked){
				if(email2[1]=='rgb(193, 0, 0)'){
					document.getElementById("Email").style.color = '#FFFFFF';
				}else{
					document.getElementById("Email").style.color = '#C10000';
				}
			}else{
				if(email2[1]=='rgb(193, 0, 0)'){
					document.getElementById("Email").style.color = '#FFFFFF';
				}else{
					document.getElementById("Email").style.color = '#000000';
				}
			}
		}else{
			if(document.formulario.Cob_FormaEmail.checked){
				document.getElementById("Email").style.color = '#C10000';
			}else{
				document.getElementById("Email").style.color = '#000000';
			}
		}
	}
	function tam_endereco(){
		var max = 60, tam = document.formulario.Endereco_Length.value;
		
		if(tam == '' || tam > max){
			tam = max;
		}
		
		return tam;
	}
	function dados_adicionais(IdPessoa){
		if(IdPessoa == "" || IdPessoa == undefined) IdPessoa = 0;
		var url = "xml/pessoa_dados_adicionais.php?IdPessoa="+IdPessoa;
		var tam, linha, c0;
		
		var table = document.getElementById('tabelaDadosAdicionais');
		document.getElementById('tdPrimeiroContrato').innerHTML = "";
		document.getElementById('tdContratoAtivo').innerHTML = "";
		document.getElementById('tdContratoBloqueado').innerHTML = "";
		document.getElementById('tdContratoCancelado').innerHTML = "";
		document.getElementById('tdContratoMigrado').innerHTML = "";
		document.getElementById('tdCRValorMax').innerHTML = "";
		document.getElementById('tdCRValorMin').innerHTML = "";
		document.getElementById('tdMediaContaReceberMensal').innerHTML = "";
		document.getElementById('tdMediaContaReceberTrimestral').innerHTML = "";
		document.getElementById('tdMediaContaReceberSemestral').innerHTML = "";
		document.getElementById('tdMediaContaReceberAnual').innerHTML = "";
		document.getElementById('tdValorTotalQuitado').innerHTML = "";
		document.getElementById('tdValorTotalAberto').innerHTML = "";
		document.getElementById('tdValorTotalContaReceberVencidos').innerHTML = "";	
		document.getElementById('tdQuantidadeMesalOrdemServico').innerHTML = "";
		document.getElementById('tdQuantidadeTrimestralOrdemServico').innerHTML = "";
		document.getElementById('tdQuantidadeSemestralOrdemServico').innerHTML = "";
		document.getElementById('tdQuantidadeAnualOrdemServico').innerHTML = "";
		document.getElementById('tdQuantidadeMensalContaEventual').innerHTML = "";
		document.getElementById('tdQuantidadeTrimestralContaEventual').innerHTML = "";
		document.getElementById('tdQuantidadeSemestralContaEventual').innerHTML = "";
		document.getElementById('tdQuantidadeAnualContaEventual').innerHTML = "";
		document.getElementById('tdMediaMesalOrdemServico').innerHTML = "";
		document.getElementById('tdValorTotalMesalOrdemServico').innerHTML = "";
		document.getElementById('tdMediaTrimestralOrdemServico').innerHTML = "";
		document.getElementById('tdValorTotalTrimestralOrdemServico').innerHTML = "";
		document.getElementById('tdMediaSemestralOrdemServico').innerHTML = "";
		document.getElementById('tdValorTotalSemestralOrdemServico').innerHTML = "";
		document.getElementById('tdMediaAnualOrdemServico').innerHTML = "";
		document.getElementById('tdMediaTrimestralContaEventual').innerHTML = "";
		document.getElementById('tdValorTotalAnualOrdemServico').innerHTML = "";
		document.getElementById('tdMediaMensalContaEventual').innerHTML = "";
		document.getElementById('tdValorTotalMensalContaEventual').innerHTML = "";
		document.getElementById('tdMediaSemestralContaEventual').innerHTML = "";
		document.getElementById('tdValorTotalSemestralContaEventual').innerHTML = "";
		document.getElementById('tdMediaAnualContaEventual').innerHTML = "";
		document.getElementById('tdValorTotalAnualContaEventual').innerHTML = "";
		document.getElementById('tdValorContaReceberTotal').innerHTML = "";
		document.getElementById('tdValorMedioContrato').innerHTML = "";
		document.getElementById('tdValorTotalContrato').innerHTML = "";
		document.getElementById('tdValorTotalTrimestralContaEventual').innerHTML = "";
		document.getElementById('tdValorMediaContaReceberVencidos').innerHTML = "";
		
		call_ajax(url,function(xmlhttp){
			if(xmlhttp.responseText != "false"){
				var nameNode = xmlhttp.responseXML.getElementsByTagName("Ativo")[0]; 
				var nameTextNode = nameNode.childNodes[0];
				var Ativo = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("Bloqueado")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var Bloqueado = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("Cancelado")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var Cancelado = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("Migrado")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var Migrado = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("PrimeiroContrato")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var PrimeiroContrato = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("ValorMax")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var ValorMax = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("ValorMin")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var ValorMin = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("MediaContaReceberMensal")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var MediaContaReceberMensal = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("MediaContaReceberTrimestral")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var MediaContaReceberTrimestral = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("MediaContaReceberSemestral")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var MediaContaReceberSemestral = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("MediaContaReceberAnual")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var MediaContaReceberAnual = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("ValorTotalQuitado")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var ValorTotalQuitado = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("ValorTotalContaReceberAberto")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var ValorTotalContaReceberAberto = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("ValorTotalContaReceberVencidos")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var ValorTotalContaReceberVencidos = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("QuantidadeMesalOrdemServico")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var QuantidadeMesalOrdemServico = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("QuantidadeTrimestralOrdemServico")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var QuantidadeTrimestralOrdemServico = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("QuantidadeSemestralOrdemServico")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var QuantidadeSemestralOrdemServico = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("QuantidadeAnualOrdemServico")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var QuantidadeAnualOrdemServico = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("QuantidadeMensalContaEventual")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var QuantidadeMensalContaEventual = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("QuantidadeTrimestralContaEventual")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var QuantidadeTrimestralContaEventual = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("QuantidadeSemestralContaEventual")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var QuantidadeSemestralContaEventual = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("QuantidadeAnualContaEventual")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var QuantidadeAnualContaEventual = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("MediaMesalOrdemServico")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var MediaMesalOrdemServico = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("ValorTotalMesalOrdemServico")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var ValorTotalMesalOrdemServico = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("MediaTrimestralOrdemServico")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var MediaTrimestralOrdemServico = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("ValorTotalTrimestralOrdemServico")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var ValorTotalTrimestralOrdemServico = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("MediaSemestralOrdemServico")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var MediaSemestralOrdemServico = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("ValorTotalSemestralOrdemServico")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var ValorTotalSemestralOrdemServico = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("MediaAnualOrdemServico")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var MediaAnualOrdemServico = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("ValorTotalAnualOrdemServico")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var ValorTotalAnualOrdemServico = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("MediaMensalContaEventual")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var MediaMensalContaEventual = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("ValorTotalMensalContaEventual")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var ValorTotalMensalContaEventual = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("MediaTrimestralContaEventual")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var MediaTrimestralContaEventual = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("ValorTotalTrimestralContaEventual")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var ValorTotalTrimestralContaEventual = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("MediaTrimestralContaEventual")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var MediaTrimestralContaEventual = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("ValorTotalTrimestralContaEventual")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var ValorTotalTrimestralContaEventual = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("MediaSemestralContaEventual")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var MediaSemestralContaEventual = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("ValorTotalSemestralContaEventual")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var ValorTotalSemestralContaEventual = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("MediaAnualContaEventual")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var MediaAnualContaEventual = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("ValorTotalAnualContaEventual")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var ValorTotalAnualContaEventual = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("ValorTotalContaReceber")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var ValorTotalContaReceber = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("ValorMedioContrato")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var ValorMedioContrato = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("ValorTotalContrato")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var ValorTotalContrato = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("ValorMediaContaReceberVencidos")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var ValorMediaContaReceberVencidos = nameTextNode.nodeValue;
				
				document.getElementById('tdPrimeiroContrato').innerHTML += PrimeiroContrato;
				document.getElementById('tdContratoAtivo').innerHTML += Ativo;
				document.getElementById('tdContratoBloqueado').innerHTML += Bloqueado;
				document.getElementById('tdContratoCancelado').innerHTML += Cancelado;
				document.getElementById('tdContratoMigrado').innerHTML += Migrado;
				document.getElementById('tdCRValorMax').innerHTML += ValorMax;
				document.getElementById('tdCRValorMin').innerHTML += ValorMin;
				document.getElementById('tdMediaContaReceberMensal').innerHTML += MediaContaReceberMensal;
				document.getElementById('tdMediaContaReceberTrimestral').innerHTML += MediaContaReceberTrimestral;
				document.getElementById('tdMediaContaReceberSemestral').innerHTML += MediaContaReceberSemestral;
				document.getElementById('tdMediaContaReceberAnual').innerHTML += MediaContaReceberAnual;
				document.getElementById('tdValorTotalQuitado').innerHTML += ValorTotalQuitado;
				document.getElementById('tdValorTotalAberto').innerHTML += ValorTotalContaReceberAberto;
				document.getElementById('tdValorTotalContaReceberVencidos').innerHTML += ValorTotalContaReceberVencidos;
				document.getElementById('tdQuantidadeMesalOrdemServico').innerHTML += QuantidadeMesalOrdemServico;
				document.getElementById('tdQuantidadeTrimestralOrdemServico').innerHTML += QuantidadeTrimestralOrdemServico;
				document.getElementById('tdQuantidadeSemestralOrdemServico').innerHTML += QuantidadeSemestralOrdemServico;
				document.getElementById('tdQuantidadeAnualOrdemServico').innerHTML += QuantidadeAnualOrdemServico;
				document.getElementById('tdQuantidadeMensalContaEventual').innerHTML += QuantidadeMensalContaEventual;
				document.getElementById('tdQuantidadeTrimestralContaEventual').innerHTML += QuantidadeTrimestralContaEventual;
				document.getElementById('tdQuantidadeSemestralContaEventual').innerHTML += QuantidadeSemestralContaEventual;
				document.getElementById('tdQuantidadeAnualContaEventual').innerHTML += QuantidadeAnualContaEventual;
				document.getElementById('tdMediaMesalOrdemServico').innerHTML += MediaMesalOrdemServico;
				document.getElementById('tdValorTotalMesalOrdemServico').innerHTML += ValorTotalMesalOrdemServico;
				document.getElementById('tdMediaTrimestralOrdemServico').innerHTML += MediaTrimestralOrdemServico;
				document.getElementById('tdValorTotalTrimestralOrdemServico').innerHTML += ValorTotalTrimestralOrdemServico;
				document.getElementById('tdMediaSemestralOrdemServico').innerHTML += MediaSemestralOrdemServico;
				document.getElementById('tdValorTotalSemestralOrdemServico').innerHTML += ValorTotalSemestralOrdemServico;
				document.getElementById('tdMediaAnualOrdemServico').innerHTML += MediaAnualOrdemServico;
				document.getElementById('tdValorTotalAnualOrdemServico').innerHTML += ValorTotalAnualOrdemServico;
				document.getElementById('tdMediaMensalContaEventual').innerHTML += MediaMensalContaEventual;
				document.getElementById('tdValorTotalMensalContaEventual').innerHTML += ValorTotalMensalContaEventual;
				document.getElementById('tdMediaTrimestralContaEventual').innerHTML += MediaTrimestralContaEventual;
				document.getElementById('tdValorTotalTrimestralContaEventual').innerHTML += ValorTotalTrimestralContaEventual;
				document.getElementById('tdMediaSemestralContaEventual').innerHTML += MediaSemestralContaEventual;
				document.getElementById('tdValorTotalSemestralContaEventual').innerHTML += ValorTotalSemestralContaEventual;
				document.getElementById('tdMediaAnualContaEventual').innerHTML += MediaAnualContaEventual;
				document.getElementById('tdValorTotalAnualContaEventual').innerHTML += ValorTotalAnualContaEventual;
				document.getElementById('tdValorContaReceberTotal').innerHTML += ValorTotalContaReceber;
				document.getElementById('tdValorMedioContrato').innerHTML += ValorMedioContrato;
				document.getElementById('tdValorTotalContrato').innerHTML += ValorTotalContrato;
				document.getElementById('tdValorMediaContaReceberVencidos').innerHTML += ValorMediaContaReceberVencidos;
			}
		});
	}
	
	function ocultarQuadroConexao(Campo, Id){
		ocultarQuadro(Campo, Id);
		scrollWindow('bottom');
	}
	
	function visualizarConjugue(EstadoCivil){
		if(EstadoCivil == 2){
			document.getElementById('labelNomeConjugue').style.display = 'block';
			document.getElementById('campoNomeConjugue').style.display = 'block';
			document.formulario.NomePai.style.width = "250px";
			document.formulario.NomeMae.style.width = "250px";
			document.formulario.VisivelConjugueDisplay.value = 1;
		}else{
			document.getElementById('labelNomeConjugue').style.display = 'none';
			document.getElementById('campoNomeConjugue').style.display = 'none';
			document.formulario.NomePai.style.width = "399px";
			document.formulario.NomeMae.style.width = "400px";
			document.formulario.VisivelConjugueDisplay.value = 2;
		}
	}
	
	function obrigatoriedadeOrgaoExpedidor(){
		if(document.formulario.RG_IE.value != ""){
			document.getElementById('labelOrgaoExpedidor').style.color = "#c10000";
		}else{
			document.getElementById('labelOrgaoExpedidor').style.color = "#000";
		}
	}
	
	function mascara_fone(Campo, Event){
		if(document.formulario.HabilitarMascaraFone.value == "1"){
			mascara(Campo, Event, "fone");
		}
	}
	
	function ativar_cadastro_resumido(campo){
		if(campo == undefined && campo == ""){
			document.getElementById('cp_dadosCadastraisResumido').style.display = 'none';
			document.getElementById('cp_dadosCadastrais').style.display = 'block';
			document.getElementById('cp_Endereco_Outros').style.display = 'block';
			document.formulario.AtivarCadastroResumido.value = 2;
			//return false;
		}
		if(campo.value == 1){
			document.getElementById('cp_dadosCadastraisResumido').style.display = 'block';
			document.getElementById('cp_Endereco_Outros').style.display = 'none';
			document.formulario.AtivarCadastroResumido.value = 1;
			
			if(document.formulario.TipoPessoa.value == 1){
				document.getElementById('cp_dadosCadastrais').style.display = 'none';
				document.getElementById('cp_Fisica_Resumido').style.display = 'none';
				document.getElementById('cp_Juridica_Resumido').style.display = 'block';
			}else{
				document.getElementById('cp_dadosCadastrais').style.display = 'none';	
				document.getElementById('cp_Juridica_Resumido').style.display = 'none';
				document.getElementById('cp_Fisica_Resumido').style.display = 'block';			
			}
		}else{
			document.getElementById('cp_dadosCadastraisResumido').style.display = 'none';
			document.getElementById('cp_dadosCadastrais').style.display = 'block';
			document.getElementById('cp_Endereco_Outros').style.display = 'block';
			document.formulario.AtivarCadastroResumido.value = 2;
		}
	}