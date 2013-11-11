	function inicia(){
		if(document.formulario.CPF_CNPJ != undefined){
			document.formulario.CPF_CNPJ.focus();
		} else{
			document.formulario.Pessoa.focus();
		}
	}
	function cadastrar(campo,event){
		var nTecla;
	
		if(document.all) { // Internet Explorer
		    nTecla = event.keyCode;
		} else if(document.layers) { // Nestcape
		    nTecla = event.which;
		} else {
		    nTecla = event.which;
		}
		if(nTecla==13){ 
			if(document.formulario.ExigirSenha.value == 1){
				if(campo.name == 'Senha'){
					if(validar() == true){
						document.formulario.submit();
					}
				}
			}else{
				if(validar() == true){
					document.formulario.submit();
				}
			}			
		}
	}
	function validar(){
		if(document.formulario.CPF_CNPJ == undefined){
			return true;
		}
		if(document.formulario.CPF_CNPJ.value==''){
			if(document.formulario.ValidarCPF_CNPJ.value==1){
				mensagem(1);
			} else{
				mensagem(60);
			}
			
			document.formulario.CPF_CNPJ.focus();
			return false;
		}
		
		
		if(document.formulario.ExigirSenha.value == 1 && document.formulario.Senha.value==''){
			mensagem(2);
			document.formulario.Senha.focus();
			return false;
		}
		if(document.formulario.CPF_CNPJ.value!='' && document.formulario.ValidarCPF_CNPJ.value==1){
			var tipo = "", CPF_CNPJ;
			CPF_CNPJ = document.formulario.CPF_CNPJ.value;
			CPF_CNPJ = retiraCaracter(retiraCaracter(retiraCaracter(CPF_CNPJ, '.'), '-'),'/');

			switch(CPF_CNPJ.length){
				case 11:
					tipo = "cpf";					
					if(isCPF(CPF_CNPJ) == false){
						mensagem(3);
						return false;						
					}
					break;
				case 14:
					tipo = "cnpj";
					if(isCNPJ(CPF_CNPJ) == false){
						mensagem(3);
						return false;
					}
					break;
				default:
					mensagem(3);
					return false;
			}
		}
		return true;
	}	
	function validaCampo(CPF_CNPJ){
		if(CPF_CNPJ == ''){
			return true;
		}
		
		var temp = "", tipo = "", cpf="";
	    
	    switch(retiraCaracter(retiraCaracter(retiraCaracter(CPF_CNPJ, '.'), '-'),'/').length){
			case 11:
				tipo = "cpf";
				
				if(isCPF(CPF_CNPJ) == false){
					mensagem(3);
					return false;
				}
				
				break;
			case 14:
				tipo = "cnpj";
				
				if(isCNPJ(CPF_CNPJ) == false){
					mensagem(3);
					return false;
				}
				
				break;
			default:
				mensagem(3);
				return false;
		}
		
		return true;
	}
	function verificar_CPF_CNPJ(CPF_CNPJ){
		var CPF_CNPJ_valido = validaCampo(CPF_CNPJ);
		CPF_CNPJ = document.formulario.CPF_CNPJ.value;
		
		if(CPF_CNPJ != '' && CPF_CNPJ_valido){
			CPF_CNPJ = CPF_CNPJ.replace(/[\.\/-]/g,'');
			
			if(CPF_CNPJ.length > 11){
				CPF_CNPJ = inserir_mascara(CPF_CNPJ,'cnpj')
			} else{
				CPF_CNPJ = inserir_mascara(CPF_CNPJ,'cpf')
			}
			
			var url = "./xml/pessoa.php?CPF_CNPJ="+CPF_CNPJ;
			
			call_ajax(url,function (xmlhttp){
				if(xmlhttp.responseText != 'false'){
					var nameNode = xmlhttp.responseXML.getElementsByTagName("IdPessoa")[0]; 
					var nameTextNode = nameNode.childNodes[0];
					var msg, IdPessoa = nameTextNode.nodeValue;
					
					if(IdPessoa != ''){
						mensagem(65);
						document.formulario.CPF_CNPJ.focus();
						document.formulario.CPF_CNPJ_duplicado.value = 1;
					} else{
						document.formulario.CPF_CNPJ_duplicado.value = 0;
					}
				} else{
					document.formulario.CPF_CNPJ_duplicado.value = 0;
				}
			});
		} else{
			document.formulario.CPF_CNPJ_duplicado.value = 0;
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
		return retorno;
	}
	
	function retiraCaracter(string, caracter) {
	    var i = 0;
	    var final = '';
	    while (i < string.length) {
	        if (string.charAt(i) == caracter) {
	            final += string.substr(0, i);
	            string = string.substr(i+1, string.length - (i+1));
	            i = 0;
	        }
	        else {
	            i++;
	        }
	    }
	    return final + string;
	}
	function validarSenha(){
		if(document.formulario.CPF_CNPJ.value==''){
			if(document.formulario.ValidarCPF_CNPJ.value==1){
				mensagem(1);
			} else{
				mensagem(60);
			}
			
			document.formulario.CPF_CNPJ.focus();
			return false;
		}
		if(document.formulario.CPF_CNPJ.value!='' && document.formulario.ValidarCPF_CNPJ.value==1){
			var tipo = "", CPF_CNPJ;
			CPF_CNPJ = document.formulario.CPF_CNPJ.value;
			CPF_CNPJ = retiraCaracter(retiraCaracter(retiraCaracter(CPF_CNPJ, '.'), '-'),'/');
		    
		    switch(CPF_CNPJ.length){
				case 11:
					tipo = "cpf";
					
					if(isCPF(document.formulario.CPF_CNPJ.value) == false){
						mensagem(3);
						document.formulario.CPF_CNPJ.focus();
						return false;
					}
					
					document.formulario.CPF_CNPJ.value = inserir_mascara(document.formulario.CPF_CNPJ.value.replace(/[\.\-\/]/g,''),"cpf");
					break;
				case 14:
					tipo = "cnpj";
					
					if(isCNPJ(document.formulario.CPF_CNPJ.value) == false){
						mensagem(3);
						document.formulario.CPF_CNPJ.focus();
						return false;
					}
					
					document.formulario.CPF_CNPJ.value = inserir_mascara(document.formulario.CPF_CNPJ.value.replace(/[\.\-\/]/g,''),"cnpj");
					break;
				default:
					mensagem(3);
					document.formulario.CPF_CNPJ.focus();
					return false;
			}
		}
		
		if(document.formulario.CPF_CNPJ_duplicado != undefined){
			if(document.formulario.CPF_CNPJ_duplicado.value == 1){
				mensagem(65);
				document.formulario.CPF_CNPJ.focus();
				return false;
			}
		}
		
		if(document.formulario.EnviaSenha != undefined){
			if(document.formulario.EnviaSenha.value == 2){
				if(document.formulario.Email.value == ''){
					mensagem(1);
					document.formulario.CPF_CNPJ.focus();
					return false;
				}
				if(document.formulario.Email.value != ''){
					var temp = document.formulario.Email.value.split(';');
					var i = 0;
					
					while(temp[i]!= '' && temp[i]!= undefined){
						temp[i]	= ignoreSpaces(temp[i]);
						if(isEmail(temp[i]) == false){				
							mensagem(24);
							document.formulario.Email.focus();
							return false;
						}
						i++;	
					}
				}
			}
		}
		
		return true;
	}
	function validaEmail(Email){
		if(Email != ''){
			var temp = Email.split(';');
			var i = 0;
			
			while(temp[i]!= '' && temp[i]!= undefined){
				temp[i]	= ignoreSpaces(temp[i]);
				if(isEmail(temp[i]) == false){				
					mensagem(24);
					return false;
				}
				i++;	
			}
		}
	}
	function validar_pessoa(){
		if(document.formulario.TipoPessoa.value=='1'){
			if(document.formulario.Nome.value==''){
				mensagem(9);
				document.formulario.Nome.focus();
				return false;
			}
			if(document.formulario.RazaoSocial.value==''){
				mensagem(10);
				document.formulario.RazaoSocial.focus();
				return false;
			}
			if(document.formulario.NomeRepresentante.value==''){
				mensagem(11);
				document.formulario.NomeRepresentante.focus();
				return false;
			}
			if(document.formulario.DataNascimento.value!=''){
				if(isData(document.formulario.DataNascimento.value) == false){
					mensagem(12);
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
			if(document.formulario.Sexo_Obrigatorio.value == 1){
				if(document.formulario.Sexo.value == ""){
					mensagem(73);
					document.formulario.Sexo.focus();
					return false
				}
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
			if(document.formulario.EstadoCivil_Obrigatorio.value == 1){
				if(document.formulario.EstadoCivil.value == ""){
					mensagem(74);
					document.formulario.EstadoCivil.focus();
					return false
				}
			}
			
			if(document.formulario.Rg_Obrigatorio.value == 1){
				if(document.formulario.RG_IE.value == ""){
					mensagem(75);
					document.formulario.RG_IE.focus();
					return false
				}
			}
			if(document.formulario.RG_IE.value != ""){
				if(document.formulario.OrgaoExpedidor.value == ""){
					mensagem(76);
					document.formulario.OrgaoExpedidor.focus();
					return false
				}
			}
			if(document.formulario.NomeMae_Obrigatorio.value == 1){
				if(document.formulario.NomeMae.value == ""){
					mensagem(78);
					document.formulario.NomeMae.focus();
					return false
				}
			}
			if(document.formulario.NomePai_Obrigatorio.value == 1){
				if(document.formulario.NomePai.value == ""){
					mensagem(77);
					document.formulario.NomePai.focus();
					return false
				}
			}
			if(document.formulario.NomeConjugue_Obrigatorio.value == 1){
				if(document.formulario.NomeConjugue.value == "" && document.formulario.VisivelNomeConjugue.value == 1){
					mensagem(79);
					document.formulario.NomeConjugue.focus();
					return false
				}
			}
		}
		if(document.formulario.Telefone_Obrigatorio.value == '1'){
			if(document.formulario.Telefone1.value == "" && document.formulario.Telefone2.value == "" && document.formulario.Telefone3.value == "" && document.formulario.Celular.value == "" && document.formulario.Fax.value == ""){
				mensagem(20);
				document.formulario.Telefone1.focus();
				return false;
			}
		}
		if(document.formulario.IdEnderecoDefault.value==''){
			mensagem(40);
			document.formulario.IdEnderecoDefault.focus();
			return false;
		}
		if(document.formulario.Email_Obrigatorio.value == 'S'){
			if(document.formulario.Email.value == ''){
				mensagem(81);
				document.formulario.Email.focus();
				return false;
			}
		}
		if(document.formulario.Email.value != ''){
			var temp = document.formulario.Email.value.split(';');
			var i = 0;
			
			while(temp[i]!= '' && temp[i]!= undefined){
				temp[i]	= ignoreSpaces(temp[i]);
				if(isEmail(temp[i]) == false){				
					mensagem(24);
					document.formulario.Email.focus();
					return false;
				}
				i++;	
			}
		}
		if(document.formulario.CampoExtra1Obrigatorio != undefined){
			if(document.formulario.CampoExtra1Obrigatorio.value=='S' && document.formulario.CampoExtra1.value==''){
				mensagem_especial(2,2);
				document.formulario.CampoExtra1.focus();
				return false;
			}
		}
		if(document.formulario.CampoExtra2Obrigatorio != undefined){
			if(document.formulario.CampoExtra2Obrigatorio.value=='S' && document.formulario.CampoExtra2.value==''){
				mensagem_especial(2,5);
				document.formulario.CampoExtra2.focus();
				return false;
			}
		}
		if(document.formulario.CampoExtra3Obrigatorio != undefined){
			if(document.formulario.CampoExtra3Obrigatorio.value=='S' && document.formulario.CampoExtra3.value==''){
				mensagem_especial(2,8);
				document.formulario.CampoExtra3.focus();
				return false;
			}
		}
		if(document.formulario.CampoExtra4Obrigatorio != undefined){
			if(document.formulario.CampoExtra4Obrigatorio.value=='S' && document.formulario.CampoExtra4.value==''){
				mensagem_especial(2,11);
				document.formulario.CampoExtra4.focus();
				return false;
			}
		}
		for(i=0;i<document.formulario.length;i++){
			aux	=	document.formulario[i].name.split('_');
			if(aux[0] == 'DescricaoEndereco' || aux[0] == 'CEP' || aux[0] == 'Endereco' || (document.formulario.Numero_Obrigatorio.value == 1 && aux[0] == 'Numero') || aux[0] == 'Bairro' || aux[0] == 'IdPais' || aux[0] == 'IdEstado' || aux[0] == 'IdCidade'){
				if(document.formulario[i].value == "" && aux[1] != "Length"){
					switch(aux[0]){
						case 'DescricaoEndereco':
							mensagem(44);
							break;
						case 'CEP':
							mensagem(14);
							break;
						case 'Endereco':
							mensagem(15);
							break;
						case 'Numero':
							mensagem(63);
							break;
						case 'Bairro':
							mensagem(16);
							break;
						case 'IdPais':
							mensagem(17);
							break;
						case 'IdEstado':
							mensagem(18);
							break;	
						case 'IdCidade':
							mensagem(19);
							break;
					}
					document.formulario[i].focus();
					return false;
				}
			}
		}
		 
		document.formulario.submit();
		
		if(document.formulario.ValidarSolicitacao.value == 'true'){							
			return confirm('ATENÇÃO\n\n'+document.formulario.MensagemSolicitacao.value+'\n\n\nObs: Processo Irreversível.');							
		}	
		return true;
	}
	function validar_protocolo(){
		if(document.formulario.IdProtocoloTipo.value==''){
			mensagem(67);
			document.formulario.IdProtocoloTipo.focus();
			return false;
		}
		
		if(document.formulario.Assunto.value==''){
			mensagem(68);
			document.formulario.Assunto.focus();
			return false;
		}
		
		if(document.formulario.Mensagem.value==''){
			mensagem(69);
			document.formulario.Mensagem.focus();
			return false;
		}
		
		return true;
	}
	function inicia_pessoa(){
		if(document.formulario.Erro.value != ""){
			mensagem(document.formulario.Erro.value);
		}
		
		var QTDEndereco					=	document.formulario.QtdEndereco.value;
		var IdPessoa					=	document.formulario.IdPessoa.value;
		var IdEnderecoDefault			=	document.formulario.IdEnderecoDefault.value;
		
		if(document.formulario.IdPessoa.value != ""){
			document.formulario.QtdEndereco.value		=	0;
			document.formulario.QtdEnderecoAux.value	=	0;
			for(i=1;i<=QTDEndereco;i++){
				formulario_endereco();
			}
			
			busca_pessoa_endereco(IdPessoa,IdEnderecoDefault);
		}
		document.formulario.Nome.focus();
	}
	
	function formulario_endereco(){
		var endereco = "",pos,descricao="",tabindex=42,CEP="",acao;
		var max = tam_endereco();
		
		document.formulario.QtdEndereco.value		=	parseInt(document.formulario.QtdEndereco.value) + 1;
		document.formulario.QtdEnderecoAux.value	=	parseInt(document.formulario.QtdEnderecoAux.value) + 1;
		
		pos		=	document.formulario.QtdEndereco.value;
		
		if(pos == 1){
			descricao = document.formulario.DescricaoEndereco1.value;
			
			while(document.getElementById('tableEndereco').rows.length > 0){
				document.getElementById('tableEndereco').deleteRow(0);
			}
		}
		
		if(pos == 2){
			descricao = document.formulario.DescricaoEndereco2.value;
		}
		
		if(pos > 1)		tabindex	=	tabindex * pos;	
		
		var tam, linha, c0;
		
		tam 	= document.getElementById('tableEndereco').rows.length;
		linha	= document.getElementById('tableEndereco').insertRow(tam);
		
		linha.accessKey = pos; 
							
		c0	= linha.insertCell(0);	

		endereco	+="<div id='formEndereco_"+pos+"' style='margin-top:10px'>";
		endereco	+="<div class='cp_tit'><table cellspacing='0' cellpading='0' style='width:597px'><tr><td style='width:40%; text-align:left'>Endereço "+pos+": <font id='titEndereco_"+pos+"'>"+descricao+"</font> <!--[<a style='cursor:pointer' onClick='formulario_endereco()'>+</a>]--></td><td style='text-align:right'><!--[<a style='cursor:pointer;' onClick='excluir_endereco("+pos+")'>x</a>]--></td></tr></table></div>";		
		endereco	+="<table>";
		endereco	+="	<tr>";
		endereco	+="	<td class='title'><B>Descrição Endereço</B></td>";
		endereco	+="		<td class='sep' />";
		endereco	+="		<td class='title'>Nome Responsável</td>";
		endereco	+="	</tr>";
		endereco	+="	<tr>";
		endereco	+="		<td>";
		endereco	+="			<input class='FormPadrao' type='hidden' name='IdPessoaEndereco_"+pos+"' value=''><input class='FormPadrao' type='text' name='DescricaoEndereco_"+pos+"' value='"+descricao+"' style='width:294px' maxlength='100' onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out')\" onChange=\"atualiza_opcoes_endereco("+pos+",this.value)\" tabindex='"+tabindex+"'>";
		endereco	+="		</td>";
		endereco	+="		<td class='sep' />";
		endereco	+="		<td>";
		endereco	+="			<input class='FormPadrao' type='text' name='NomeResponsavelEndereco_"+pos+"' value='' style='width:294px' maxlength='100' onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out')\" tabindex='"+(tabindex+1)+"'>";
		endereco	+="		</td>";
		endereco	+="	</tr>";
		endereco	+="</table>";
		endereco	+="<table>";
		endereco	+="	<tr>";
		endereco	+="		<td class='title'><B>CEP</B></td>";
		endereco	+="		<td class='sep' />";
		endereco	+="		<td class='title'><B>Endereço</B></td>";
		endereco	+="		<td class='sep' />";
		if(document.formulario.Numero_Obrigatorio.value == 1){
			endereco	+="		<td class='title'><B>Nº</B></td>";
		}else{
			endereco	+="		<td class='title'>Nº</td>";
		}
		endereco	+="		<td class='sep' />";					
		endereco	+="		<td class='title'>Complemento</td>";	
		endereco	+="		<td class='sep' />";
		endereco	+="		<td class='title'><B>Bairro</B></td>";
		endereco	+="	</tr>";
		endereco	+="	<tr>";
		endereco	+="		<td>";
		endereco	+="			<input class='FormPadrao' type='text' name='CEP_"+pos+"' value='"+CEP+"' style='width:75px' maxlength='9' onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out')\" onkeypress=\"mascara(this,event,'cep')\" onChange=\"busca_pessoa_cep(document.formulario.CEP_"+pos+".value,false,document.formulario.IdPais_"+pos+",document.formulario.IdEstado_"+pos+",document.formulario.IdCidade_"+pos+")\" tabindex='"+(tabindex+2)+"'>";
		endereco	+="		</td>";
		endereco	+="		<td class='sep' />";
		endereco	+="		<td>";
		endereco	+="			<input  class='FormPadrao' type='text' name='Endereco_"+pos+"' value='' style='width:195px' maxlength='"+max+"' onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out')\" tabindex='"+(tabindex+3)+"'>";
		endereco	+="		</td>";
		endereco	+="		<td class='sep' />";
		endereco	+="		<td>";
		endereco	+="			<input class='FormPadrao' type='text' name='Numero_"+pos+"' value='' style='width:55px' maxlength='5' onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out')\" tabindex='"+(tabindex+4)+"'>";
		endereco	+="		</td>";
		endereco	+="		<td class='sep' />";
		endereco	+="		<td>";
		endereco	+="			<input class='FormPadrao' type='text' name='Complemento_"+pos+"' value='' style='width:115px' maxlength='30' onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out')\" tabindex='"+(tabindex+5)+"'>";
		endereco	+="		</td>";
		endereco	+="		<td class='sep' />";
		endereco	+="		<td>";
		endereco	+="			<input class='FormPadrao' type='text' name='Bairro_"+pos+"' value='' style='width:120px' maxlength='30' onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out')\" tabindex='"+(tabindex+6)+"'>";
		endereco	+="		</td>";
		endereco	+="	</tr>";
		endereco	+="</table>";
		endereco	+="<table>";
		endereco	+="	<tr>";
		endereco	+="		<td class='title'><B style='margin-right:54px;'>País</td>";
		endereco	+="		<td class='sep' />";;
		endereco	+="		<td class='title'><B style='margin-right:38px;'>Estado</b></td>";
		endereco	+="		<td class='sep' />";
		endereco	+="		<td class='title'><B style='margin-right:38px;'>Cidade</B></td>";
		endereco	+="	</tr>";
		endereco	+="	<tr>";
		endereco	+="		<td>";
		endereco	+="			<select class='FormPadrao' name='IdPais_"+pos+"' style='width:100px' onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out')\" onChange=\"verifica_estado(this.value,'',document.formulario.IdEstado_"+pos+")\" tabindex='"+(tabindex+7)+"'><option value='' selected></option></select>";
		endereco	+="		</td>";
		endereco	+="		<td class='sep' />";
		endereco	+="		<td>";
		endereco	+="			<select class='FormPadrao' name='IdEstado_"+pos+"' style='width:158px' onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out')\" onChange=\"verifica_cidade(document.formulario.IdPais_"+pos+".value,this.value,'',document.formulario.IdCidade_"+pos+")\" tabindex='"+(tabindex+8)+"'><option value='' selected></option></select>";
		endereco	+="		</td>";
		endereco	+="		<td class='sep' />";
		endereco	+="		<td>";
		endereco	+="			<select class='FormPadrao' name='IdCidade_"+pos+"' style='width:321px' onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out')\" tabindex='"+(tabindex+9)+"'><option value='' selected></option></select>";
		endereco	+="		</td>";
		endereco	+="	</tr>";
		endereco	+="</table>";
		endereco	+="<table>";
		endereco	+="	<tr>";
		endereco	+="		<td class='title'>Fone</td>";	
		endereco	+="		<td class='sep' />";	
		endereco	+="		<td class='title'>Celular</td>";	
		endereco	+="		<td class='sep' />";	
		endereco	+="		<td class='title'>Fax</td>";	
		endereco	+="		<td class='sep' />";							
		endereco	+="		<td class='title'>Complemento Fone</td>";
		endereco	+="		<td class='sep' />";
		endereco	+="		<td class='title'><B style='color:#000' id='Email_"+pos+"'>E-mail</B></td>";
		endereco	+="	</tr>";
		endereco	+="	<tr>";
		endereco	+="		<td>";
		endereco	+="			<input class='FormPadrao' type='text' name='Telefone_"+pos+"' value='' style='width:100px' maxlength='18' onFocus=\"Foco(this,'in')\" onkeypress=\"mascara(this, event, 'fone');\" onBlur=\"Foco(this,'out')\" tabindex='"+(tabindex+10)+"'>";
		endereco	+="		</td>";
		endereco	+="		<td class='sep' />";
		endereco	+="		<td>";
		endereco	+="			<input class='FormPadrao' type='text' name='Celular_"+pos+"' value='' style='width:100px' maxlength='18' onFocus=\"Foco(this,'in')\" onkeypress=\"mascara(this, event, 'fone');\" onBlur=\"Foco(this,'out')\" tabindex='"+(tabindex+11)+"'>";
		endereco	+="		</td>";
		endereco	+="		<td class='sep' />";
		endereco	+="		<td>";
		endereco	+="			<input class='FormPadrao' type='text' name='Fax_"+pos+"' value='' style='width:100px' maxlength='18' onFocus=\"Foco(this,'in')\" onkeypress=\"mascara(this, event, 'fone');\" onBlur=\"Foco(this,'out')\" tabindex='"+(tabindex+12)+"'>";
		endereco	+="		</td>";
		endereco	+="		<td class='sep' />";
		endereco	+="		<td>";
		endereco	+="			<input class='FormPadrao' type='text' name='ComplementoTelefone_"+pos+"' value='' style='width:100px' maxlength='30' onFocus=\"Foco(this,'in')\" onkeypress=\"mascara(this, event, 'fone');\" onBlur=\"Foco(this,'out')\" tabindex='"+(tabindex+13)+"'>";
		endereco	+="		</td>";
		endereco	+="		<td class='sep' />";
		endereco	+="		<td>";
		endereco	+="			<input class='FormPadrao' type='text' name='EmailEndereco_"+pos+"' value='' style='width:160px' maxlength='255' autocomplete='off' onFocus=\"Foco(this,'in',true)\"  onBlur=\"Foco(this,'out'); validar_Email(this.value,'Email_"+pos+"')\" tabindex='"+(tabindex+14)+"'>";
		endereco	+="		</td>";
		endereco	+="	</tr>";
		endereco	+="</table>";
		endereco	+="</div>";
		
		c0.innerHTML = endereco;
		inicia_pais(pos);
	}
	function inicia_pais(pos){
		for(ii=0;ii<document.formulario.length;ii++){
			if(document.formulario[ii].name.substr(0,7) == 'IdPais_'){
				var temp	=	document.formulario[ii].name.split("_");
				if(temp[1] == pos){
					verifica_pais('',document.formulario[ii]);
					break;
				}
			}
		}	
	}
	function busca_pessoa_endereco(IdPessoa,IdEnderecoDefault,IdEnderecoCobrancaDefault){
		if(IdPessoa == '' || IdPessoa == undefined){
			IdPessoa = 0;
		}
		if(IdEnderecoDefault == undefined){
			IdEnderecoDefault = '';
		}
		if(IdEnderecoCobrancaDefault == undefined){
			IdEnderecoCobrancaDefault = 0;
		}
		
		var IdPessoaSolicitacao = document.formulario.IdPessoaSolicitacao.value;
		
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
	    
	    /*if(IdPessoaSolicitacao!=""){
	    	url = "xml/pessoa_endereco_solicitacao.php?IdPessoa="+IdPessoa+"&IdPessoaSolicitacao="+IdPessoaSolicitacao;
	    }else{*/
	   		url = "xml/pessoa_endereco.php?IdPessoa="+IdPessoa;
	   	//}

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
									//	document.formulario[ii+8].value 		= IdPais;		
									//	document.formulario[ii+9].value			= IdEstado;		
									//	document.formulario[ii+10].value		= IdCidade;		
										document.formulario[ii+11].value		= Telefone;			
										document.formulario[ii+12].value		= Celular;			
										document.formulario[ii+13].value		= Fax;				
										document.formulario[ii+14].value		= ComplementoTelefone;					
										document.formulario[ii+15].value		= EmailEndereco;					
										
										verifica_pais(IdPais,document.formulario[ii+8]);
										verifica_estado(IdPais,IdEstado,document.formulario[ii+9]);
										verifica_cidade(IdPais,IdEstado,IdCidade,document.formulario[ii+10]);
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
	function atualiza_opcoes_endereco(pos,valor){
		var temp = 0, aux;
		var end1 = document.formulario.IdEnderecoDefault.value;
		
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
	function verifica_pais(IdPaisTemp,campo){
		var xmlhttp = false;
		var nameNode, nameTextNode, url;	
		
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
		url = "xml/pais.php";
		
		xmlhttp.open("GET", url,true);
		xmlhttp.onreadystatechange = function(){ 
		
			// Carregando...
			carregando(true);
			
			if(xmlhttp.readyState == 4){ 
				if(xmlhttp.status == 200){
					if(xmlhttp.responseText == 'false'){
						while(campo.options.length > 0){
							campo.options[0] = null;
						}
					}else{
						var IdPais, NomePais;
						while(campo.options.length > 0){
							campo.options[0] = null;
						}
							
						addOption(campo,"","");
						
						for(var i=0; i<xmlhttp.responseXML.getElementsByTagName("IdPais").length; i++){
							nameNode = xmlhttp.responseXML.getElementsByTagName("IdPais")[i]; 
							nameTextNode = nameNode.childNodes[0];
							IdPais = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("NomePais")[i]; 
							nameTextNode = nameNode.childNodes[0];
							NomePais = nameTextNode.nodeValue;
							
							addOption(campo,NomePais,IdPais);
							
						}
						
						if(IdPaisTemp!=""){
							for(i=0;i<campo.options.length;i++){
								if(campo.options[i].value == IdPaisTemp){
									campo.options[i].selected	=	true;
								}
							}
						}else{
							campo.options[0].selected = true;
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
	function verifica_estado(IdPais,IdEstadoTemp,campo){
		var xmlhttp = false;
		var nameNode, nameTextNode, url;	
		
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
						while(campo.options.length > 0){
							campo.options[0] = null;
						}
					}else{
						var IdEstado, NomeEstado;
						while(campo.options.length > 0){
							campo.options[0] = null;
						}
							
						addOption(campo,"","");
						for(var i=0; i<xmlhttp.responseXML.getElementsByTagName("IdEstado").length; i++){
							nameNode = xmlhttp.responseXML.getElementsByTagName("IdEstado")[i]; 
							nameTextNode = nameNode.childNodes[0];
							IdEstado = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("NomeEstado")[i]; 
							nameTextNode = nameNode.childNodes[0];
							NomeEstado = nameTextNode.nodeValue;
							
							addOption(campo,NomeEstado,IdEstado);
							
						}
						
						if(IdEstadoTemp!=""){
							for(i=0;i<campo.options.length;i++){
								if(campo.options[i].value == IdEstadoTemp){
									campo.options[i].selected	=	true;
								}
							}
						}else{
							campo.options[0].selected = true;
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
	function verifica_cidade(IdPais,IdEstado,IdCidadeTemp,campo){
		var xmlhttp = false;
		var nameNode, nameTextNode, url;	
		
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
						while(campo.options.length > 0){
							campo.options[0] = null;
						}
					}else{
						var IdCidade, NomeCidade;
						while(campo.options.length > 0){
							campo.options[0] = null;
						}
							
						addOption(campo,"","");
						for(var i=0; i<xmlhttp.responseXML.getElementsByTagName("IdCidade").length; i++){
							nameNode = xmlhttp.responseXML.getElementsByTagName("IdCidade")[i]; 
							nameTextNode = nameNode.childNodes[0];
							IdCidade = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("NomeCidade")[i]; 
							nameTextNode = nameNode.childNodes[0];
							NomeCidade = nameTextNode.nodeValue;
							
							addOption(campo,NomeCidade,IdCidade);
						}
						
						if(IdCidadeTemp!=""){
							for(i=0;i<campo.options.length;i++){
								if(campo.options[i].value == IdCidadeTemp){
									campo.options[i].selected	=	true;
								}
							}
						}else{
							campo.options[0].selected = true;
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
	function excluir_endereco(pos){
		if(document.formulario.QtdEnderecoAux.value > 1){
			if(confirm("ATENCAO!\n\nVoce esta prestes a excluir um endereco.\nDeseja continuar?","SIM","NAO") == true){
				var valor = 0;
				for(i=0;i<document.formulario.length;i++){
					if(document.formulario[i].name.substr(0,17) == 'IdPessoaEndereco_'){
						aux	=	document.formulario[i].name.split('_');
						if(aux[1] == pos){
							valor	=	document.formulario[i].value;
							
							excluir_pessoa_endereco(document.formulario.IdPessoa.value,valor,pos);
						}	
					}
				}
			}	
		}else{
			alert("ATENCAO!\n\nÉ obrigatório pelo menos um endereço.");
		}
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
		
		var IdPessoaSolicitacao	=	document.formulario.IdPessoaSolicitacao.value;

		url = "files/excluir/excluir_pessoa_endereco_solicitacao.php?IdPessoa="+IdPessoa+"&IdPessoaEndereco="+IdPessoaEndereco+"&IdPessoaSolicitacao="+IdPessoaSolicitacao;
		xmlhttp.open("GET", url,true);
		
		xmlhttp.onreadystatechange = function(){ 

			// Carregando...
			carregando(true);

			if(xmlhttp.readyState == 4){ 
				if(xmlhttp.status == 200){
					var erro = parseInt(xmlhttp.responseText);
					if(erro == 42){
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
						
						mensagem(erro);
					}else{
						mensagem(erro);
					}
				}
				// Fim de Carregando
				carregando(false);
			}
			return true;
		}
		xmlhttp.send(null);
	} 
	function inicia_atualizar_vencimento(){
		if(document.formulario.Erro.value != ""){
			mensagem(document.formulario.Erro.value);
		}
		document.formulario.Vencimento.focus();
	}
	function validar_atualizar_vencimento(){
		
		if(document.formulario.Vencimento.value=='0'){	
			mensagem(33);
			document.formulario.Vencimento.focus();
			return false;
		}
		return true;
	}
	function goToURL(arq){ 
		if(arq == '' || arq == undefined) {
			arq = "index.php"; 
		}
		
		window.location.href = arq; 
	}
	function inicia_alterar_senha(){
		if(document.formulario.Erro.value != ""){
			mensagem(document.formulario.Erro.value);
		}
		document.formulario.SenhaAtual.focus();
	}
	function validar_alterar_senha(){
		if(document.formulario.SenhaAtual.value==''){
			mensagem(27);
			document.formulario.SenhaAtual.focus();
			return false;
		}
		if(document.formulario.NovaSenha.value==''){
			mensagem(28);
			document.formulario.NovaSenha.focus();
			return false;
		}
		if(document.formulario.Confirmacao.value==''){
			mensagem(26);
			document.formulario.Confirmacao.focus();
			return false;
		}
		if(document.formulario.NovaSenha.value!=document.formulario.Confirmacao.value){
			mensagem(29);
			document.formulario.Confirmacao.focus();
			return false;
		}
		return true;
	}
	function calculaValor(){
		var qtdDias, valor, valorFinal, multaC, multa, juros, taxa, perc, tempPerc, qtdDias, percM, percJ;	
		if(document.formulario.Vencimento.value == "0"){
			document.formulario.ValorMulta.value = formata_float(Arredonda(document.formulario.ValorMultaTemp.value,2),2).replace('.',',');	
			document.formulario.ValorJuros.value = formata_float(Arredonda(document.formulario.ValorJurosTemp.value,2),2).replace('.',',');			
			qtdDias = 	0;
		}else{
			//alert(document.formulario.Vencimento.value+" -- "+document.formulario.PrimeiroVencimento.value);			 		
			qtdDias	=	difDias(document.formulario.Vencimento.value,document.formulario.PrimeiroVencimento.value);
			//alert(qtdDias);
		}
		document.formulario.ValorFinal.value = document.formulario.ValorFinalTemp.value;		
		
		if(document.formulario.ValorMulta.value == ''){					mora  	= 0;	}else{		multa  	= document.formulario.ValorMulta.value;					}
		if(document.formulario.ValorJuros.value == ''){					juros 	= 0;	}else{		juros	= document.formulario.ValorJuros.value;					}
		if(document.formulario.ValorTaxaReImpressaoBoleto.value == ''){	taxa  	= 0;	}else{		taxa  	= document.formulario.ValorTaxaReImpressaoBoleto.value;	}	
		//if(document.formulario.ValorFinal.value == ''){					valor  	= 0;	}else{		valor  	= document.formulario.ValorFinal.value;					}
		if(document.formulario.ValorContaReceber.value == ''){			valor  	= 0;	}else{		valor  	= document.formulario.ValorContaReceber.value;			}
		if(document.formulario.PercentualMulta.value==''){				percM	= 0;	}else{		percM	= document.formulario.PercentualMulta.value;			}  
		if(document.formulario.PercentualJurosDiarios.value==''){		percJ	= 0;	}else{		percJ	= document.formulario.PercentualJurosDiarios.value;		}  
			
		multa	=	new String(multa);
		multa	=	multa.replace(',','.');
		
		juros	=	new String(juros);
		juros	=	juros.replace(',','.');
		
		taxa	=	new String(taxa);
		taxa	=	taxa.replace(',','.');
		
		valor	=	new String(valor);
		valor	=	valor.replace(',','.');
			
		if(document.formulario.Vencimento.value != "0"){		
			multa	= 	(parseFloat(valor) * parseFloat(percM)) / 100;
			juros	= 	(parseFloat(valor) * parseFloat(percJ) / 100) * parseInt(qtdDias);
			
			document.formulario.ValorMulta.value	=	formata_float(Arredonda(multa,2),2).replace('.',',');		
			document.formulario.ValorJuros.value	=	formata_float(Arredonda(juros,2),2).replace('.',',');	
			valorFinal	=	parseFloat(valor) + parseFloat(multa) + parseFloat(juros) +  parseFloat(taxa);
		}else{
			valorFinal = document.formulario.ValorFinalTemp.value;
		}	
		
		valorFinal	=	Arredonda(valorFinal,2);
		valorFinal	=	formata_float(valorFinal,2);
		
		document.formulario.ValorFinal.value	=	valorFinal.replace('.',',');
	}
	function validar_contrato(){
		var posInicial=0,posFinal=0,temp=0, qtd, qtdMin;
		for(i = 0; i<document.formulario.length; i++){
			if(document.formulario[i].name != undefined){
				if(document.formulario[i].name.substring(0,6) == 'Valor_'){
					if(posInicial == 0){
						posInicial = i;
					}
					posFinal = i;
				}
			}
		}	
		if(posInicial != 0){		
			for(i = posInicial; i<=posFinal; i=i+4){				
				if(document.formulario[i+3].value == '2'){										
					if(document.formulario[i].value != '' || (document.formulario[i+5].value != '' && document.formulario[i].value == '') || (document.formulario[i+9].value != '' && (document.formulario[i+5].value != '' || document.formulario[i+5].value == '') && document.formulario[i].value == '')){						
						if(document.formulario[i+2].value!="" && parseInt(document.formulario[i].value.length) < parseInt(document.formulario[i+2].value)){
							qtdMin		=	parseInt(document.formulario[i+2].value);
							qtd			=	conta(qtdMin);
							if(conta(qtd)!=""){
								qtdMin	=	qtdMin+' ('+qtd+')';
							}
							mensagem(36,qtdMin);
							document.formulario[i].focus();
							return false;
						}						
						if(document.formulario[i].value != document.formulario[i+4].value && (document.formulario[i+1].value == 1 || document.formulario[i].value != "")){																										
							mensagem(30);
							document.formulario[i].focus();
							return false;
						}else{
							if(document.formulario[i+5].value == ""){
								mensagem(35);								
								document.formulario[i+5].focus();
								return false;	
							}
							if(document.formulario[i+9].value == ""){
								mensagem(26);								
								document.formulario[i+9].focus();
								return false;	
							}
							if(document.formulario[i+9].value != document.formulario[i+5].value){
								mensagem(23);								
								document.formulario[i+5].focus();
								return false;		
							}else{
								if(document.formulario[i].value == "" && document.formulario[i].value != document.formulario[i+4].value){
									mensagem(27);
									document.formulario[i].focus();
									return false;
								}
							}																			
							i=i+9;						
						}															
					}else{						
						if(document.formulario[i].value == "" && document.formulario[i+1].value == 1){							
							mensagem(39);
							document.formulario[i].focus();
							return false;
						}else{
							i=i+9;						
						}
					}
				}else{
					if(document.formulario[i+1].value == '1'){
						if(document.formulario[i].type != 'select-one'){
						 	if(document.formulario[i].value == ''){
								mensagem(35);
								document.formulario[i].focus();
								return false;
							}else{
								if(document.formulario[i+2].value!="" && parseInt(document.formulario[i].value.length) < parseInt(document.formulario[i+2].value)){
									qtdMin		=	parseInt(document.formulario[i+2].value);
									qtd			=	conta(qtdMin);
									if(conta(qtd)!=""){
										qtdMin	=	qtdMin+' ('+qtd+')';
									}
									mensagem(36,qtdMin);
									document.formulario[i].focus();
									return false;
								}
							}					
						}else{
							var cont = 0;
							for(j=0;j<document.formulario[i].options.length;j++){
							 	if(document.formulario[i][j].selected == true && document.formulario[i][j].value != ""){
							 		cont++;
							 		break;
								}
							}
							if(cont == 0){
								mensagem(35);
								document.formulario[i].focus();
								return false;
							} 	
								
						}
					}else{
						if(document.formulario[i].type == 'text' && document.formulario[i].value != '' && document.formulario[i+2].value!="" && parseInt(document.formulario[i].value.length) < parseInt(document.formulario[i+2].value)){
							qtdMin		=	parseInt(document.formulario[i+2].value);
							qtd			=	conta(qtdMin);
							if(conta(qtd)!=""){
								qtdMin	=	qtdMin+' ('+qtd+')';
							}
							mensagem(36,qtdMin);
							document.formulario[i].focus();
							return false;
						}					
					}								
				}
			}
		}
		posInicial	= 0;
		posFinal	= 0;
		for(ii = 0; ii<document.formulario.length; ii++){
			if(document.formulario[ii].name != undefined){
				if(document.formulario[ii].name.substring(0,16) == 'ValorAutomatico_'){
					if(posInicial == 0){
						posInicial = ii;
					}
					posFinal = ii;
				}
			}
		}
		
		if(posInicial != 0){
			for(ii = posInicial; ii<=posFinal; ii=ii+4){				
				if(document.formulario[ii+3].value == '2'){					
					if(document.formulario[ii].value != '' || (document.formulario[ii+5].value != '' && document.formulario[ii].value == '') || (document.formulario[ii+9].value != '' && (document.formulario[ii+5].value != '' || document.formulario[ii+5].value == '') && document.formulario[ii].value == '')){	
						if(document.formulario[ii+2].value!="" && parseInt(document.formulario[ii].value.length) < parseInt(document.formulario[ii+2].value)){
							qtdMin		=	parseInt(document.formulario[ii+2].value);
							qtd			=	conta(qtdMin);
							if(conta(qtd)!=""){
								qtdMin	=	qtdMin+' ('+qtd+')';
							}
							mensagem(36,qtdMin);
							document.formulario[ii].focus();
							return false;
						}					
						if(document.formulario[ii].value != document.formulario[ii+4].value && (document.formulario[ii+1].value == 1 || document.formulario[ii].value != "")){						
							mensagem(30);						
							document.formulario[ii].focus();
							return false;
						}else{
							if(document.formulario[ii+5].value == ""){
								mensagem(35);
								document.formulario[ii+5].focus();
								return false;	
							}
							if(document.formulario[ii+9].value == ""){
								mensagem(26);
								document.formulario[ii+9].focus();
								return false;	
							}
							if(document.formulario[ii+9].value != document.formulario[ii+5].value){
								mensagem(23);								
								document.formulario[ii+5].focus();
								return false;		
							}else{
								if(document.formulario[ii].value == "" && document.formulario[ii].value != document.formulario[ii+4].value){
									mensagem(27);
									document.formulario[ii].focus();
									return false;
								}
							}	
							ii=ii+9;
						}
					}else{						
						if(document.formulario[ii].value == "" && document.formulario[ii+1].value == 1){							
							mensagem(39);
							document.formulario[ii].focus();
							return false;
						}else{
							ii=ii+9;	
						}
					}
				}else{
					if(document.formulario[ii+1].value == '1'){
						if(document.formulario[ii].type != 'select-one'){
						 	if(document.formulario[ii].value == ''){
								mensagem(35);
								document.formulario[ii].focus();
								return false;
							}else{
								if(document.formulario[ii+2].value!="" && parseInt(document.formulario[ii].value.length) < parseInt(document.formulario[ii+2].value)){
									qtdMin		=	parseInt(document.formulario[ii+2].value);
									qtd			=	conta(qtdMin);
									if(conta(qtd)!=""){
										qtdMin	=	qtdMin+' ('+qtd+')';
									}
									mensagem(36,qtdMin);
									document.formulario[ii].focus();
									return false;
								}
							}
						}else{
							var cont = 0;
							 for(j=0;j<document.formulario[ii].options.length;j++){
							 	if(document.formulario[ii][j].selected == true && document.formulario[ii][j].value != ""){
							 		cont++;
							 		break;
								}
							}
							if(cont == 0){
								mensagem(35);
								document.formulario[ii].focus();
								return false;
							} 	
						}
					}else{
						if(document.formulario[ii].type == 'text' && document.formulario[ii].value != '' && document.formulario[ii+2].value!="" && parseInt(document.formulario[ii].value.length) < parseInt(document.formulario[ii+2].value)){
							qtdMin		=	parseInt(document.formulario[ii+2].value);
							qtd			=	conta(qtdMin);
							if(conta(qtd)!=""){
								qtdMin	=	qtdMin+' ('+qtd+')';
							}
							mensagem(36,qtdMin);
							document.formulario[ii].focus();
							return false;
						}
					}
				}
			}
		}
		return true;	
	}
	function cadastrar_contrato(){
		if(validar_contrato() == true){
			document.formulario.submit();
		}
	}
	function busca_pessoa_cep(CEP, Erro, campoPais, campoEstado, campoCidade){
		if(CEP == ''){
			return false;
		}
		
		var cpEndereco	=	campoPais.name.split("_");
		cpEndereco 		= 	cpEndereco[1];
		
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
	
		   	url = "xml/cep.php?CEP="+CEP;
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
					
							nameNode = xmlhttp.responseXML.getElementsByTagName("IdCidade")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var IdCidade = nameTextNode.nodeValue;					
						
							nameNode = xmlhttp.responseXML.getElementsByTagName("Endereco")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var Endereco = nameTextNode.nodeValue;
						
							nameNode = xmlhttp.responseXML.getElementsByTagName("Bairro")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var Bairro = nameTextNode.nodeValue;
							
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
							
							verifica_pais(IdPais,campoPais);
							verifica_estado(IdPais,IdEstado,campoEstado);
							verifica_cidade(IdPais,IdEstado,IdCidade,campoCidade);
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
	function tam_endereco(){
		var max = 60, tam = document.formulario.Endereco_Length.value;
		
		if(tam == '' || tam > max){
			tam = max;
		}
		
		return tam;
	}
	function visualizarConjugue(EstadoCivil){
		if(EstadoCivil == 2){
			document.getElementById('labelNomeConjugue').style.display = 'block';
			document.formulario.NomeConjugue.style.display = 'block';
			document.formulario.NomePai.style.width = "190px";
			document.formulario.NomeMae.style.width = "190px";
			document.formulario.VisivelNomeConjugue.value = 1;
		}else{
			document.getElementById('labelNomeConjugue').style.display = 'none';
			document.formulario.NomeConjugue.style.display	= 'none';
			document.formulario.NomePai.style.width			= "295px";
			document.formulario.NomeMae.style.width			= "294px";
			document.formulario.VisivelNomeConjugue.value	= 2;
			document.formulario.NomeConjugue.value			= '';
		}
	}
	
	function obrigatoriedadeOrgaoExpedidor(){
		if(document.formulario.RG_IE.value != ""){
			document.getElementById('labelOrgaoExpedidor').style.color = "#c10000";
		}else{
			document.getElementById('labelOrgaoExpedidor').style.color = "#000";
		}
	}