	function inicia(){	
		scrollWindow('top');
		
		document.formulario.IdPessoaSolicitacao.focus();
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
	}
	function ativaPessoaAnterior(pessoa){
		// Dependendo do tipo da pessoa
		if(pessoa == 2){ //Pessoa Fisica
			// Ocultar a Pessoa Juridica			
			document.getElementById('cp_JuridicaAnterior').style.display = 'none';
			
			// Aparece o Pessoa Física			
			document.getElementById('cp_FisicaAnterior').style.display = 'block';
		}else{ //Pessoa Jurídica
			//Aparece a Pessoa Jurídica
			document.getElementById('cp_JuridicaAnterior').style.display = 'block';
			
			// Some o Pessoa Física			
			document.getElementById('cp_FisicaAnterior').style.display = 'none';
		}
	}
	function validar_Data(valor,campo){
		if(valor == ''){
			return false;
		}
		if(isData(valor) == false){				
			document.getElementById(campo).style.backgroundColor = '#C10000';
			document.getElementById(campo).style.color='#FFF';
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
	/*function validar(){
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
		if(document.formulario.Telefone_Obrigatorio.value == 1){
			if(document.formulario.Telefone1.value == '' && document.formulario.Telefone2.value== '' && document.formulario.Telefone3.value == '' && document.formulario.Celular.value == '' && document.formulario.Fax.value == ''){
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
		for(i=0;i<document.formulario.length;i++){
			aux	=	document.formulario[i].name.split('_');
			if(aux[0] == 'DescricaoEndereco' || aux[0] == 'CEP' || aux[0] == 'Endereco' || aux[0] == 'Bairro' || aux[0] == 'IdPais' || aux[0] == 'IdEstado' || aux[0] == 'IdCidade'){
				if(document.formulario[i].value == ""){
					mensagens(1);
					document.formulario[i].focus();
					return false;
				}
			}
		}
		mensagens(0);
		return true;
	}*/
	function verificaAcao(){
		if(document.formulario != undefined){
			if(document.formulario.Acao.value=='inserir'){			
				document.formulario.bt_confirmar.disabled 	= true;
				document.formulario.bt_recusar.disabled 	= true;
			}
			if(document.formulario.Acao.value=='alterar'){			
				switch(document.formulario.IdStatus.value){
					case '3':
						document.formulario.bt_confirmar.disabled 	= true;	
						document.formulario.bt_recusar.disabled 	= true;
						break;
					case '0':
						document.formulario.bt_confirmar.disabled 	= true;	
						document.formulario.bt_recusar.disabled 	= true;
						break;
					case '2':
						document.formulario.bt_confirmar.disabled 	= true;	
						document.formulario.bt_recusar.disabled 	= true;
						break;
					default:
						document.formulario.bt_confirmar.disabled 	= false;	
						document.formulario.bt_recusar.disabled 	= false;
						break;
				}		
			}
		}	
	}
	function cadastrar(aprovada){
		document.formulario.IdStatus.value = aprovada;
		if(aprovada == '3'){
			document.formulario.submit();
		}else{
			//if(validar()==true){
				document.formulario.submit();
			//}
		}
	}
	function formulario_endereco_anterior(){
		var endereco = "",pos,descricao="",tabindex=42,CEP="",acao;
		
		document.formulario.QtdEnderecoAnterior.value		=	parseInt(document.formulario.QtdEnderecoAnterior.value) + 1;
		document.formulario.QtdEnderecoAuxAnterior.value	=	parseInt(document.formulario.QtdEnderecoAuxAnterior.value) + 1;
		
		pos		=	document.formulario.QtdEnderecoAnterior.value;
		
		if(pos == 1)	descricao = document.formulario.DescricaoEndereco1.value;
		if(pos == 2)	descricao = document.formulario.DescricaoEndereco2.value;
		
		if(pos > 1)		tabindex	=	tabindex + (14*(pos-1));	
		
		
		var tam, linha, c0;
		
		tam 	= document.getElementById('tableEnderecoAnterior').rows.length;
		linha	= document.getElementById('tableEnderecoAnterior').insertRow(tam);
		
		linha.accessKey = pos; 
							
		c0	= linha.insertCell(0);	
		
		endereco	+="<div id='formEnderecoAnterior_"+pos+"' style='margin-top:10px'>";
		endereco	+="<div class='cp_tit' style='background-color:#E0DFE3; color:#000'>Endereço Anterior "+pos+": <font id='titEnderecoAnterior_"+pos+"'>"+descricao+"</font></div>";		
		endereco	+="<table>";
		endereco	+="	<tr>";
		endereco	+="		<td class='find'>&nbsp;</td>";
		endereco	+="		<td class='descCampo'>Descrição Endereço</td>";
		endereco	+="		<td class='separador'>&nbsp;</td>";
		endereco	+="		<td class='descCampo'>Nome Responsável</td>";
		endereco	+="	</tr>";
		endereco	+="	<tr>";
		endereco	+="		<td class='find'>&nbsp;</td>";
		endereco	+="		<td class='campo'>";
		endereco	+="			<input type='hidden' name='IdPessoaEnderecoAnterior_"+pos+"' value=''><input type='text' name='DescricaoEnderecoAnterior_"+pos+"' value='"+descricao+"' style='width:399px' maxlength='100' readOnly>";
		endereco	+="		</td>";
		endereco	+="		<td class='separador'>&nbsp;</td>";
		endereco	+="		<td class='campo'>";
		endereco	+="			<input type='text' name='NomeResponsavelEnderecoAnterior_"+pos+"' value='' style='width:400px' maxlength='100' readOnly>";
		endereco	+="		</td>";
		endereco	+="	</tr>";
		endereco	+="</table>";
		endereco	+="<table>";
		endereco	+="	<tr>";
		endereco	+="		<td class='find'>&nbsp;</td>";
		endereco	+="		<td class='descCampo'>CEP</td>";
		endereco	+="		<td class='separador'>&nbsp;</td>";
		endereco	+="		<td class='descCampo'>Endereço</td>";
		endereco	+="		<td class='separador'>&nbsp;</td>";
		endereco	+="		<td class='descCampo'>Nº</td>";
		endereco	+="		<td class='separador'>&nbsp;</td>";					
		endereco	+="		<td class='descCampo'>Complemento</td>";	
		endereco	+="		<td class='separador'>&nbsp;</td>";
		endereco	+="		<td class='descCampo'>Bairro</td>";
		endereco	+="	</tr>";
		endereco	+="	<tr>";
		endereco	+="		<td class='find'><img style='cursor:pointer' src='../../img/estrutura_sistema/ico_lupa_c.gif'></td>";
		endereco	+="		<td class='campo'>";
		endereco	+="			<input type='text' name='CEPAnterior_"+pos+"' value='"+CEP+"' style='width:70px' maxlength='9' readOnly>";
		endereco	+="		</td>";
		endereco	+="		<td class='separador'>&nbsp;</td>";
		endereco	+="		<td class='campo'>";
		endereco	+="			<input type='text' name='EnderecoAnterior_"+pos+"' value='' style='width:268px' maxlength='60' readOnly>";
		endereco	+="		</td>";
		endereco	+="		<td class='separador'>&nbsp;</td>";
		endereco	+="		<td class='campo'>";
		endereco	+="			<input type='text' name='NumeroAnterior_"+pos+"' value='' style='width:55px' maxlength='10' readOnly>";
		endereco	+="		</td>";
		endereco	+="		<td class='separador'>&nbsp;</td>";
		endereco	+="		<td class='campo'>";
		endereco	+="			<input type='text' name='ComplementoAnterior_"+pos+"' value='' style='width:161px' maxlength='30' readOnly>";
		endereco	+="		</td>";
		endereco	+="		<td class='separador'>&nbsp;</td>";
		endereco	+="		<td class='campo'>";
		endereco	+="			<input type='text' name='Bairro_"+pos+"' value='' style='width:194px' maxlength='30' readOnly>";
		endereco	+="		</td>";
		endereco	+="	</tr>";
		endereco	+="</table>";
		endereco	+="<table>";
		endereco	+="	<tr>";
		endereco	+="		<td class='find'>&nbsp;</td>";
		endereco	+="		<td class='descCampo'><B style='margin-right:54px; color:#000'>País</B>Nome País</td>";
		endereco	+="		<td class='separador'>&nbsp;</td>";
		endereco	+="		<td class='find'>&nbsp;</td>";
		endereco	+="		<td class='descCampo'><B style='margin-right:38px; color:#000'>Estado</b>Nome Estado</td>";
		endereco	+="		<td class='separador'>&nbsp;</td>";
		endereco	+="		<td class='find'>&nbsp;</td>";
		endereco	+="		<td class='descCampo'><B style='margin-right:38px; color:#000'>Cidade</B>Nome Cidade</td>";
		endereco	+="	</tr>";
		endereco	+="	<tr>";
		endereco	+="		<td class='find'><img src='../../img/estrutura_sistema/ico_lupa_c.gif' alt='Buscar'></td>";
		endereco	+="		<td class='campo'>";
		endereco	+="			<input type='text' name='IdPaisAnterior_"+pos+"' value='' style='width:70px' maxlength='11'  readOnly><input  class='agrupador' type='text' name='PaisAnterior_"+pos+"' value='' style='width:140px' maxlength='100' readOnly>";
		endereco	+="		</td>";
		endereco	+="		<td class='separador'>&nbsp;</td>";
		endereco	+="		<td class='find'><img src='../../img/estrutura_sistema/ico_lupa_c.gif' alt='Buscar'></td>";
		endereco	+="		<td class='campo'>";
		endereco	+="			<input type='text' name='IdEstadoAnterior_"+pos+"' value='' style='width:70px' maxlength='11'readOnly><input class='agrupador' type='text' name='EstadoAnterior_"+pos+"' value='' style='width:140px' maxlength='100' readOnly>";
		endereco	+="		</td>";
		endereco	+="		<td class='separador'>&nbsp;</td>";
		endereco	+="		<td class='find'><img src='../../img/estrutura_sistema/ico_lupa_c.gif' alt='Buscar'></td>";
		endereco	+="		<td class='campo'>";
		endereco	+="			<input type='text' name='IdCidadeAnterior_"+pos+"' value='' style='width:70px' maxlength='11' readOnly><input class='agrupador' type='text' name='Cidade_"+pos+"' value='' style='width:233px' maxlength='100' readOnly>";
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
		endereco	+="		<td class='descCampo'>E-mail</td>";
		endereco	+="		<td class='find'>&nbsp;</td>";
		endereco	+="	</tr>";
		endereco	+="	<tr>";
		endereco	+="		<td class='find'>&nbsp;</td>";
		endereco	+="		<td class='campo'>";
		endereco	+="			<input type='text' name='TelefoneAnterior_"+pos+"' value='' style='width:122px' maxlength='18' readOnly>";
		endereco	+="		</td>";
		endereco	+="		<td class='separador'>&nbsp;</td>";
		endereco	+="		<td class='campo'>";
		endereco	+="			<input type='text' name='CelularAnterior_"+pos+"' value='' style='width:122px' maxlength='18' readOnly>";
		endereco	+="		</td>";
		endereco	+="		<td class='separador'>&nbsp;</td>";
		endereco	+="		<td class='campo'>";
		endereco	+="			<input type='text' name='FaxAnterior_"+pos+"' value='' style='width:122px' maxlength='18' readOnly>";
		endereco	+="		</td>";
		endereco	+="		<td class='separador'>&nbsp;</td>";
		endereco	+="		<td class='campo'>";
		endereco	+="			<input type='text' name='ComplementoTelefoneAnterior_"+pos+"' value='' style='width:122px' maxlength='30' readOnly>";
		endereco	+="		</td>";
		endereco	+="		<td class='separador'>&nbsp;</td>";
		endereco	+="		<td class='campo'>";
		endereco	+="			<input type='text' name='EmailEnderecoAnterior_"+pos+"' value='' style='width:240px' maxlength='255' autocomplete='off' readOnly>";
		endereco	+="		</td>";
		endereco	+="		<td class='find' onClick='JsMail(document.formulario.EmailEnderecoAnterior_"+pos+".value)'><img src='../../img/estrutura_sistema/ico_outlook.gif' alt='Enviar E-mail'></td>";
		endereco	+="	</tr>";
		endereco	+="</table>";
		endereco	+="</div>";
		
		c0.innerHTML = endereco;
	}
	function busca_pessoa_endereco_anterior(IdPessoa,IdEnderecoDefault,IdEnderecoCobrancaDefault){
		if(IdPessoa == '' || IdPessoa == undefined){
			IdPessoa = 0;
		}
		if(IdEnderecoDefault == undefined){
			IdEnderecoDefault = '';
		}
		if(IdEnderecoCobrancaDefault == undefined){
			IdEnderecoCobrancaDefault = 0;
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
						
						while(document.formulario.IdEnderecoDefaultAnterior.options.length > 0){
							document.formulario.IdEnderecoDefaultAnterior.options[0] = null;
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
								if(document.formulario[ii].name.substr(0,25) == 'IdPessoaEnderecoAnterior_'){
									var temp	=	document.formulario[ii].name.split("_");
									if(temp[1] == aux){
										document.getElementById("titEnderecoAnterior_"+aux).innerHTML	= DescricaoEndereco;
										
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
										
										for(j=0;j<document.formulario.length;j++){
											if(document.formulario[j].name.substr(0,17) == 'IdPessoaEndereco_'){
												var temp2	=	document.formulario[j].name.split("_");
												if(temp2[1] == aux){
													if(document.formulario[ii+1].value != document.formulario[j+1].value){
														document.formulario[j+1].style.border				= '1px #C10000 solid';
														document.formulario[j+1].style.backgroundColor		= '#FFEAEA';	
													}
													if(document.formulario[ii+2].value != document.formulario[j+2].value){
														document.formulario[j+2].style.border				= '1px #C10000 solid';
														document.formulario[j+2].style.backgroundColor		= '#FFEAEA';	
													}
													if(document.formulario[ii+3].value != document.formulario[j+3].value){
														document.formulario[j+3].style.border				= '1px #C10000 solid';
														document.formulario[j+3].style.backgroundColor		= '#FFEAEA';	
													}
													if(document.formulario[ii+4].value != document.formulario[j+4].value){
														document.formulario[j+4].style.border				= '1px #C10000 solid';
														document.formulario[j+4].style.backgroundColor		= '#FFEAEA';	
													}
													if(document.formulario[ii+5].value != document.formulario[j+5].value){
														document.formulario[j+5].style.border				= '1px #C10000 solid';
														document.formulario[j+5].style.backgroundColor		= '#FFEAEA';	
													}
													if(document.formulario[ii+6].value != document.formulario[j+6].value){
														document.formulario[j+6].style.border				= '1px #C10000 solid';
														document.formulario[j+6].style.backgroundColor		= '#FFEAEA';	
													}
													if(document.formulario[ii+7].value != document.formulario[j+7].value){
														document.formulario[j+7].style.border				= '1px #C10000 solid';
														document.formulario[j+7].style.backgroundColor		= '#FFEAEA';	
													}
													if(document.formulario[ii+8].value != document.formulario[j+8].value){
														document.formulario[j+8].style.border				= '1px #C10000 solid';
														document.formulario[j+8].style.backgroundColor		= '#FFEAEA';	
													}
													if(document.formulario[ii+9].value != document.formulario[j+9].value){
														document.formulario[j+9].style.border				= '1px #C10000 solid';
														document.formulario[j+9].style.backgroundColor		= '#FFEAEA';	
													}
													if(document.formulario[ii+10].value != document.formulario[j+10].value){
														document.formulario[j+10].style.border				= '1px #C10000 solid';
														document.formulario[j+10].style.backgroundColor		= '#FFEAEA';	
													}
													if(document.formulario[ii+11].value != document.formulario[j+11].value){
														document.formulario[j+11].style.border				= '1px #C10000 solid';
														document.formulario[j+11].style.backgroundColor		= '#FFEAEA';	
													}
													if(document.formulario[ii+12].value != document.formulario[j+12].value){
														document.formulario[j+12].style.border				= '1px #C10000 solid';
														document.formulario[j+12].style.backgroundColor		= '#FFEAEA';	
													}
													if(document.formulario[ii+13].value != document.formulario[j+13].value){
														document.formulario[j+13].style.border				= '1px #C10000 solid';
														document.formulario[j+13].style.backgroundColor		= '#FFEAEA';	
													}
													if(document.formulario[ii+14].value != document.formulario[j+14].value){
														document.formulario[j+14].style.border				= '1px #C10000 solid';
														document.formulario[j+14].style.backgroundColor		= '#FFEAEA';	
													}
													if(document.formulario[ii+15].value != document.formulario[j+15].value){
														document.formulario[j+15].style.border				= '1px #C10000 solid';
														document.formulario[j+15].style.backgroundColor		= '#FFEAEA';	
													}
													if(document.formulario[ii+16].value != document.formulario[j+16].value){
														document.formulario[j+16].style.border				= '1px #C10000 solid';
														document.formulario[j+16].style.backgroundColor		= '#FFEAEA';	
													}
													if(document.formulario[ii+17].value != document.formulario[j+17].value){
														document.formulario[j+17].style.border				= '1px #C10000 solid';
														document.formulario[j+17].style.backgroundColor		= '#FFEAEA';	
													}
													if(document.formulario[ii+18].value != document.formulario[j+18].value){
														document.formulario[j+18].style.border				= '1px #C10000 solid';
														document.formulario[j+18].style.backgroundColor		= '#FFEAEA';	
													}
													break;
												}
											}
										}
										break;
									}
								}
							}
							
							addOption(document.formulario.IdEnderecoDefaultAnterior,DescricaoEndereco,IdPessoaEndereco);
							
							aux++;
						}
						
						if(IdEnderecoDefault!=""){
							for(i=0;i<document.formulario.IdEnderecoDefaultAnterior.length;i++){
								if(document.formulario.IdEnderecoDefaultAnterior[i].value == IdEnderecoDefault){
									document.formulario.IdEnderecoDefaultAnterior[i].selected	=	true;
									break;
								}
							}
						}else{
							document.formulario.IdEnderecoDefaultAnterior[0].selected	=	true;
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
	function formulario_endereco(){
		var endereco = "",pos,descricao="",tabindex=42,CEP="",acao;
		
		document.formulario.QtdEndereco.value		=	parseInt(document.formulario.QtdEndereco.value) + 1;
		document.formulario.QtdEnderecoAux.value	=	parseInt(document.formulario.QtdEnderecoAux.value) + 1;
		
		pos		=	document.formulario.QtdEndereco.value;
		acao	=	document.formulario.Acao.value;
		
		if(pos == 1)	descricao = document.formulario.DescricaoEndereco1.value;
		if(pos == 2)	descricao = document.formulario.DescricaoEndereco2.value;
		
		if(pos > 1)		tabindex	=	tabindex + (14*(pos-1));	
		
		var tam, linha, c0;
		
		tam 	= document.getElementById('tableEndereco').rows.length;
		linha	= document.getElementById('tableEndereco').insertRow(tam);
		
		linha.accessKey = pos; 
							
		c0	= linha.insertCell(0);	
		
		endereco	+="<div id='formEndereco_"+pos+"' style='margin-top:10px'>";
		endereco	+="<div class='cp_tit' style='background-color:#E0DFE3; color:#000'>Endereço "+pos+": <font id='titEndereco_"+pos+"'>"+descricao+"</font></div>";		
		endereco	+="<table>";
		endereco	+="	<tr>";
		endereco	+="		<td class='find'>&nbsp;</td>";
		endereco	+="	<td class='descCampo'>Descrição Endereço</td>";
		endereco	+="		<td class='separador'>&nbsp;</td>";
		endereco	+="		<td class='descCampo'>Nome Responsável</td>";
		endereco	+="	</tr>";
		endereco	+="	<tr>";
		endereco	+="		<td class='find'>&nbsp;</td>";
		endereco	+="		<td class='campo'>";
		endereco	+="			<input type='hidden' name='IdPessoaEndereco_"+pos+"' value=''><input type='text' name='DescricaoEndereco_"+pos+"' value='"+descricao+"' style='width:399px' maxlength='100' onChange=\"atualiza_opcoes_endereco("+pos+",this.value)\" readOnly>";
		endereco	+="		</td>";
		endereco	+="		<td class='separador'>&nbsp;</td>";
		endereco	+="		<td class='campo'>";
		endereco	+="			<input type='text' name='NomeResponsavelEndereco_"+pos+"' value='' style='width:400px' maxlength='100' readOnly>";
		endereco	+="		</td>";
		endereco	+="	</tr>";
		endereco	+="</table>";
		endereco	+="<table>";
		endereco	+="	<tr>";
		endereco	+="		<td class='find'>&nbsp;</td>";
		endereco	+="		<td class='descCampo'>CEP</td>";
		endereco	+="		<td class='separador'>&nbsp;</td>";
		endereco	+="		<td class='descCampo'>Endereço</td>";
		endereco	+="		<td class='separador'>&nbsp;</td>";
		endereco	+="		<td class='descCampo'>Nº</td>";
		endereco	+="		<td class='separador'>&nbsp;</td>";					
		endereco	+="		<td class='descCampo'>Complemento</td>";	
		endereco	+="		<td class='separador'>&nbsp;</td>";
		endereco	+="		<td class='descCampo'>Bairro</td>";
		endereco	+="	</tr>";
		endereco	+="	<tr>";
		endereco	+="		<td class='find'><img style='cursor:pointer' src='../../img/estrutura_sistema/ico_lupa_c.gif'></td>";
		endereco	+="		<td class='campo'>";
		endereco	+="			<input type='text' name='CEP_"+pos+"' value='"+CEP+"' style='width:70px' maxlength='9' onkeypress=\"mascara(this,event,'cep')\" onChange=\"busca_pessoa_cep(document.formulario.CEP_"+pos+".value,false,"+pos+")\" readOnly>";
		endereco	+="		</td>";
		endereco	+="		<td class='separador'>&nbsp;</td>";
		endereco	+="		<td class='campo'>";
		endereco	+="			<input type='text' name='Endereco_"+pos+"' value='' style='width:268px' maxlength='60' readOnly>";
		endereco	+="		</td>";
		endereco	+="		<td class='separador'>&nbsp;</td>";
		endereco	+="		<td class='campo'>";
		endereco	+="			<input type='text' name='Numero_"+pos+"' value='' style='width:55px' maxlength='10' readOnly>";
		endereco	+="		</td>";
		endereco	+="		<td class='separador'>&nbsp;</td>";
		endereco	+="		<td class='campo'>";
		endereco	+="			<input type='text' name='Complemento_"+pos+"' value='' style='width:161px' maxlength='30' readOnly>";
		endereco	+="		</td>";
		endereco	+="		<td class='separador'>&nbsp;</td>";
		endereco	+="		<td class='campo'>";
		endereco	+="			<input type='text' name='Bairro_"+pos+"' value='' style='width:194px' maxlength='30' readOnly>";
		endereco	+="		</td>";
		endereco	+="	</tr>";
		endereco	+="</table>";
		endereco	+="<table>";
		endereco	+="	<tr>";
		endereco	+="		<td class='find'>&nbsp;</td>";
		endereco	+="		<td class='descCampo'><span style='margin-right:54px;'>País</span>Nome País</td>";
		endereco	+="		<td class='separador'>&nbsp;</td>";
		endereco	+="		<td class='find'>&nbsp;</td>";
		endereco	+="		<td class='descCampo'><span style='margin-right:38px;'>Estado</span>Nome Estado</td>";
		endereco	+="		<td class='separador'>&nbsp;</td>";
		endereco	+="		<td class='find'>&nbsp;</td>";
		endereco	+="		<td class='descCampo'><span style='margin-right:38px;'>Cidade</span>Nome Cidade</td>";
		endereco	+="	</tr>";
		endereco	+="	<tr>";
		endereco	+="		<td class='find'><img style='cursor:pointer' src='../../img/estrutura_sistema/ico_lupa_c.gif'></td>";
		endereco	+="		<td class='campo'>";
		endereco	+="			<input type='text' name='IdPais_"+pos+"' value='' style='width:70px' maxlength='11' onChange='busca_pessoa_pais(this.value,false,document.formulario.Local.value, "+pos+")' onkeypress=\"mascara(this,event,'int')\" readOnly><input  class='agrupador' type='text' name='Pais_"+pos+"' value='' style='width:140px' maxlength='100' readOnly>";
		endereco	+="		</td>";
		endereco	+="		<td class='separador'>&nbsp;</td>";
		endereco	+="		<td class='find'><img style='cursor:pointer' src='../../img/estrutura_sistema/ico_lupa_c.gif'></td>";
		endereco	+="		<td class='campo'>";
		endereco	+="			<input type='text' name='IdEstado_"+pos+"' value='' style='width:70px' maxlength='11' onChange='busca_pessoa_estado(document.formulario.IdPais_"+pos+".value,this.value,false,document.formulario.Local.value,"+pos+")' onkeypress=\"mascara(this,event,'int')\" readOnly><input class='agrupador' type='text' name='Estado_"+pos+"' value='' style='width:140px' maxlength='100' readOnly>";
		endereco	+="		</td>";
		endereco	+="		<td class='separador'>&nbsp;</td>";
		endereco	+="		<td class='find'><img style='cursor:pointer' src='../../img/estrutura_sistema/ico_lupa_c.gif'></td>";
		endereco	+="		<td class='campo'>";
		endereco	+="			<input type='text' name='IdCidade_"+pos+"' value='' style='width:70px' maxlength='11' onChange='busca_pessoa_cidade(document.formulario.IdPais_"+pos+".value,document.formulario.IdEstado_"+pos+".value,this.value,false,document.formulario.Local.value,"+pos+")' onkeypress=\"mascara(this,event,'int')\" readOnly><input class='agrupador' type='text' name='Cidade_"+pos+"' value='' style='width:233px' maxlength='100' readOnly>";
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
		endereco	+="			<input type='text' name='Telefone_"+pos+"' value='' style='width:122px' maxlength='18' readOnly>";
		endereco	+="		</td>";
		endereco	+="		<td class='separador'>&nbsp;</td>";
		endereco	+="		<td class='campo'>";
		endereco	+="			<input type='text' name='Celular_"+pos+"' value='' style='width:122px' maxlength='18' readOnly>";
		endereco	+="		</td>";
		endereco	+="		<td class='separador'>&nbsp;</td>";
		endereco	+="		<td class='campo'>";
		endereco	+="			<input type='text' name='Fax_"+pos+"' value='' style='width:122px' maxlength='18' readOnly>";
		endereco	+="		</td>";
		endereco	+="		<td class='separador'>&nbsp;</td>";
		endereco	+="		<td class='campo'>";
		endereco	+="			<input type='text' name='ComplementoTelefone_"+pos+"' value='' style='width:122px' maxlength='30' readOnly>";
		endereco	+="		</td>";
		endereco	+="		<td class='separador'>&nbsp;</td>";
		endereco	+="		<td class='campo'>";
		endereco	+="			<input type='text' name='EmailEndereco_"+pos+"' value='' style='width:240px' maxlength='255' autocomplete='off' validar_Email(this.value,'Email_"+pos+"')\" readOnly>";
		endereco	+="		</td>";
		endereco	+="		<td class='find' onClick='JsMail(document.formulario.EmailEndereco_"+pos+".value)'><img src='../../img/estrutura_sistema/ico_outlook.gif' alt='Enviar E-mail'></td>";
		endereco	+="	</tr>";
		endereco	+="</table>";
		endereco	+="</div>";
		
		c0.innerHTML = endereco;
	}
	function busca_pessoa_endereco(IdPessoa,IdEnderecoDefault,IdEnderecoCobrancaDefault,IdPessoaSolicitacao){
		if(IdPessoa == '' || IdPessoa == undefined){
			IdPessoa = 0;
		}
		if(IdEnderecoDefault == undefined){
			IdEnderecoDefault = '';
		}
		if(IdEnderecoCobrancaDefault == undefined){
			IdEnderecoCobrancaDefault = 0;
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
	    
	   	url = "../administrativo/xml/pessoa_endereco_solicitacao.php?IdPessoa="+IdPessoa+"&IdPessoaSolicitacao="+IdPessoaSolicitacao;
	   	
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
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("Atualizacao")[i]; 
							nameTextNode = nameNode.childNodes[0];
							Atualizacao = nameTextNode.nodeValue;	
							
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
										
										if(Atualizacao == ''){
											document.formulario[ii].style.border	= '1px #C10000 solid';	
											document.formulario[ii+1].style.border	= '1px #C10000 solid';	
											document.formulario[ii+2].style.border	= '1px #C10000 solid';	
											document.formulario[ii+3].style.border	= '1px #C10000 solid';	
											document.formulario[ii+4].style.border	= '1px #C10000 solid';	
											document.formulario[ii+5].style.border	= '1px #C10000 solid';	
											document.formulario[ii+6].style.border	= '1px #C10000 solid';	
											document.formulario[ii+7].style.border	= '1px #C10000 solid';	
											document.formulario[ii+8].style.border	= '1px #C10000 solid';	
											document.formulario[ii+9].style.border	= '1px #C10000 solid';	
											document.formulario[ii+10].style.border	= '1px #C10000 solid';	
											document.formulario[ii+11].style.border	= '1px #C10000 solid';	
											document.formulario[ii+12].style.border	= '1px #C10000 solid';	
											document.formulario[ii+13].style.border	= '1px #C10000 solid';	
											document.formulario[ii+14].style.border	= '1px #C10000 solid';	
											document.formulario[ii+15].style.border	= '1px #C10000 solid';	
											document.formulario[ii+16].style.border	= '1px #C10000 solid';	
											document.formulario[ii+17].style.border	= '1px #C10000 solid';	
											document.formulario[ii+18].style.border	= '1px #C10000 solid';
											
											document.formulario[ii].style.backgroundColor		= '#FFEAEA';		
											document.formulario[ii+1].style.backgroundColor		= '#FFEAEA';		
											document.formulario[ii+2].style.backgroundColor		= '#FFEAEA';		
											document.formulario[ii+3].style.backgroundColor		= '#FFEAEA';		
											document.formulario[ii+4].style.backgroundColor		= '#FFEAEA';		
											document.formulario[ii+5].style.backgroundColor		= '#FFEAEA';		
											document.formulario[ii+6].style.backgroundColor		= '#FFEAEA';		
											document.formulario[ii+7].style.backgroundColor		= '#FFEAEA';		
											document.formulario[ii+8].style.backgroundColor		= '#FFEAEA';		
											document.formulario[ii+9].style.backgroundColor		= '#FFEAEA';	
											document.formulario[ii+10].style.backgroundColor	= '#FFEAEA';		
											document.formulario[ii+11].style.backgroundColor	= '#FFEAEA';		
											document.formulario[ii+12].style.backgroundColor	= '#FFEAEA';		
											document.formulario[ii+13].style.backgroundColor	= '#FFEAEA';		
											document.formulario[ii+14].style.backgroundColor	= '#FFEAEA';			
											document.formulario[ii+15].style.backgroundColor	= '#FFEAEA';			
											document.formulario[ii+16].style.backgroundColor	= '#FFEAEA';			
											document.formulario[ii+17].style.backgroundColor	= '#FFEAEA';	
											document.formulario[ii+18].style.backgroundColor	= '#FFEAEA';		
										}
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

		url = "files/excluir/excluir_pessoa_solicitacao_endereco.php?IdPessoa="+IdPessoa+"&IdPessoaEndereco="+IdPessoaEndereco;
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
						for(i=0;i<document.formulario.length;i++){
							if(document.formulario[i].name.substr(0,9) == 'IdCidade_'){
								var temp	=	document.formulario[i].name.split("_");
								if(temp[1] == Endereco){
									document.formulario[i].value 		= '';	//IdEstado
									document.formulario[i+1].value		= '';	//Estado
						
									if(document.formulario[i-4].value == ""){
										document.formulario[i-4].focus();
									}else if(document.formulario[i-2].value == ""){
										document.formulario[i-2].focus();
									}else{
										document.formulario[i].focus();
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