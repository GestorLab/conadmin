	function inicia(){
		status_inicial();
		document.formulario.IdContrato.focus();
	}
	function listarParametro(IdServico,Erro,IdContrato){
		while(document.getElementById('tabelaParametro').rows.length > 0){
			document.getElementById('tabelaParametro').deleteRow(0);
		}		
		
		var tam, linha, c0, c1, c2, c3, c4, c5;
		
		if(IdServico == ''){
			IdServico = 0;
		}
		
	    var url = "xml/contrato_parametro.php?IdServico="+IdServico+"&IdContrato="+IdContrato+"&IdStatus=1";
		
		call_ajax(url,function (xmlhttp){
			if(Erro != false){
				document.formulario.Erro.value = 0;
				verificaErro();
			}
			
			if(xmlhttp.responseText == "false"){		
				document.getElementById('cp_parametrosServico').style.display	=	'none';
			} else{
				var obsTemp = new Array(), cont=0,invisivel="",campo="",tipo="", tamMax="";
				
				for(var i=0; i<xmlhttp.responseXML.getElementsByTagName("IdParametroServico").length; i++){
					var nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoParametroServico")[i]; 
					var nameTextNode = nameNode.childNodes[0];
					var DescricaoParametroServico = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("Obrigatorio")[i]; 
					nameTextNode = nameNode.childNodes[0];
					var Obrigatorio = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("Editavel")[i]; 
					nameTextNode = nameNode.childNodes[0];
					var Editavel = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("Valor")[i]; 
					nameTextNode = nameNode.childNodes[0];
					var Valor = nameTextNode.nodeValue;	
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("ValorDefault")[i]; 
					nameTextNode = nameNode.childNodes[0];
					var ValorDefault = nameTextNode.nodeValue;	
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("RestringirGrupoUsuario")[i]; 
					nameTextNode = nameNode.childNodes[0];
					var RestringirGrupoUsuario = nameTextNode.nodeValue;	
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("IdParametroServico")[i]; 
					nameTextNode = nameNode.childNodes[0];
					var IdParametroServico = nameTextNode.nodeValue;
				
					nameNode = xmlhttp.responseXML.getElementsByTagName("Obs")[i]; 
					nameTextNode = nameNode.childNodes[0];
					var Obs = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("IdTipoParametro")[i]; 
					nameTextNode = nameNode.childNodes[0];
					var IdTipoParametro = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("IdMascaraCampo")[i]; 
					nameTextNode = nameNode.childNodes[0];
					var IdMascaraCampo = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("OpcaoValor")[i]; 
					nameTextNode = nameNode.childNodes[0];
					var OpcaoValor = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("Visivel")[i]; 
					nameTextNode = nameNode.childNodes[0];
					var Visivel = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("IdTipoTexto")[i]; 
					nameTextNode = nameNode.childNodes[0];
					var IdTipoTexto = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("TamMinimo")[i]; 
					nameTextNode = nameNode.childNodes[0];
					var TamMinimo = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("TamMaximo")[i]; 
					nameTextNode = nameNode.childNodes[0];
					var TamMaximo = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("ExibirSenha")[i]; 
					nameTextNode = nameNode.childNodes[0];
					var ExibirSenha = nameTextNode.nodeValue;
					
					if(Valor == '' && ValorDefault != '' && document.formulario.Acao.value == 'inserir'){
						Valor	=	ValorDefault;
					}
					
					if(!(Visivel == '1' && RestringirGrupoUsuario == '1')) {
						Visivel = '2';
					}
					
					if(Visivel == '1'){
						obsTemp[cont]	=	Obs;
						
						tam 	 = document.getElementById('tabelaParametro').rows.length;

						if(cont%2 == 0){
							linha	 = document.getElementById('tabelaParametro').insertRow(tam);
							tabindex = 24 + cont + 1;
							pos		 = 0;
							padding	 = 23;
						}else{	
							padding	 = 10;
							tabindex = 24 + cont;
							pos		 = 1;
							if(obsTemp[(cont-1)]!= undefined && obsTemp[(cont-1)]!= ''){
								if(Obs	==	'')	Obs	=	'<BR>';
							}
						}
						
						if((cont+1) == xmlhttp.responseXML.getElementsByTagName("IdParametroServico").length && cont%2 == 0){
							padding	 = 22;	
						}
						
						if(Obrigatorio == 1){
							color = "#C10000";
						}else{
							color = "#000000";
						}
						
						if(Editavel == 2){
							if(IdTipoParametro == 1){
								disabled	=	"readOnly=true";
							}
							else{
								disabled	=	"disabled";
							}
						}else{
							disabled	=	"";
						}
						
						linha.accessKey = IdParametroServico; 

						c0	= linha.insertCell(pos);
						
						if(TamMaximo!="" && Editavel == 1){
							tamMax	=	"maxlength='"+TamMaximo+"'";
						}else{
							tamMax	=	"";
						}
						
						if(IdTipoParametro == 1){
							switch(IdTipoTexto){
								case '1':	//Texto
									switch(IdMascaraCampo){
										case '1': //Data
											c0.innerHTML = "<p style='margin:0; padding-bottom:1px; padding-left:"+padding+"px'><B style='color:"+color+";'>"+DescricaoParametroServico+"</B></p><p style='padding-bottom:6px; padding-left:"+padding+"px; margin:0;'><input type='text' name='Valor_"+IdParametroServico+"' value='"+Valor+"' style='width:399px;' autocomplete='off' onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out');\" "+disabled+" onkeypress=\"mascara(this,event,'date')\" maxlength='10' tabindex="+tabindex+"><input type='hidden' name='Obrigatorio_"+IdParametroServico+"' value='"+Obrigatorio+"'><input type='hidden' name='Visivel_"+IdParametroServico+"' value='"+Visivel+"'><input type='hidden' name='TamMinimo_"+IdParametroServico+"' value='"+TamMinimo+"'><BR>"+Obs+"</p>";
											break;
										case '2': //Inteiro
											c0.innerHTML = "<p style='margin:0; padding-bottom:1px; padding-left:"+padding+"px'><B style='color:"+color+";'>"+DescricaoParametroServico+"</B></p><p style='padding-bottom:6px; padding-left:"+padding+"px; margin:0;'><input type='text' name='Valor_"+IdParametroServico+"' value='"+Valor+"' style='width:399px;' autocomplete='off' "+tamMax+" onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out');\" "+disabled+" onkeypress=\"mascara(this,event,'int')\" tabindex="+tabindex+"><input type='hidden' name='Obrigatorio_"+IdParametroServico+"' value='"+Obrigatorio+"'><input type='hidden' name='Visivel_"+IdParametroServico+"' value='"+Visivel+"'><input type='hidden' name='TamMinimo_"+IdParametroServico+"' value='"+TamMinimo+"'><BR>"+Obs+"</p>";
											break;
										case '3': //Real
											if(Editavel == 1){
												c0.innerHTML = "<p style='margin:0; padding-bottom:1px; padding-left:"+padding+"px'><B style='color:"+color+";'>"+DescricaoParametroServico+"</B></p><p style='padding-bottom:6px; padding-left:"+padding+"px; margin:0;'><input type='text' name='Valor_"+IdParametroServico+"' value='"+Valor+"' style='width:399px;' autocomplete='off' onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out');\" "+disabled+" onkeypress=\"mascara(this,event,'float')\" "+tamMax+" tabindex="+tabindex+"><input type='hidden' name='Obrigatorio_"+IdParametroServico+"' value='"+Obrigatorio+"'><input type='hidden' name='Visivel_"+IdParametroServico+"' value='"+Visivel+"'><input type='hidden' name='TamMinimo_"+IdParametroServico+"' value='"+TamMinimo+"'><BR>"+Obs+"</p>";
											}else{
												c0.innerHTML = "<p style='margin:0; padding-bottom:1px; padding-left:"+padding+"px'><B style='color:"+color+";'>"+DescricaoParametroServico+"</B></p><p style='padding-bottom:6px; padding-left:"+padding+"px; margin:0;'><input type='text' name='Valor_"+IdParametroServico+"' value='"+Valor+"' style='width:399px;' autocomplete='off' onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out');\" tabindex="+tabindex+"><input type='hidden' name='Obrigatorio_"+IdParametroServico+"' value='"+Obrigatorio+"'><input type='hidden' name='Visivel_"+IdParametroServico+"' value='"+Visivel+"'><input type='hidden' name='TamMinimo_"+IdParametroServico+"' value='"+TamMinimo+"'><BR>"+Obs+"</p>";
											}
											break;
										case '4': //Usu�rio
											c0.innerHTML = "<p style='margin:0; padding-bottom:1px; padding-left:"+padding+"px'><B style='color:"+color+";'>"+DescricaoParametroServico+"</B></p><p style='padding-bottom:6px; padding-left:"+padding+"px; margin:0;'><input type='text' name='Valor_"+IdParametroServico+"' value='"+Valor+"' style='width:399px;' autocomplete='off' onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out');\" "+disabled+" onkeypress=\"mascara(this,event,'usuario')\" maxlength='255' tabindex="+tabindex+"><input type='hidden' name='Obrigatorio_"+IdParametroServico+"' value='"+Obrigatorio+"'><input type='hidden' name='Visivel_"+IdParametroServico+"' value='"+Visivel+"'><input type='hidden' name='TamMinimo_"+IdParametroServico+"' value=''><BR>"+Obs+"</p>";
											break;
										case '5': //MAC
											c0.innerHTML = "<p style='margin:0; padding-bottom:1px; padding-left:"+padding+"px'><B style='color:"+color+";'>"+DescricaoParametroServico+"</B></p><p style='padding-bottom:6px; padding-left:"+padding+"px; margin:0;'><input type='text' name='Valor_"+IdParametroServico+"' value='"+Valor+"' style='width:399px;' autocomplete='off' onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out');\" "+disabled+" onkeypress=\"mascara(this,event,'mac')\" maxlength='17' tabindex="+tabindex+"><input type='hidden' name='Obrigatorio_"+IdParametroServico+"' value='"+Obrigatorio+"'><input type='hidden' name='Visivel_"+IdParametroServico+"' value='"+Visivel+"'><input type='hidden' name='TamMinimo_"+IdParametroServico+"' value='"+TamMinimo+"'><BR>"+Obs+"</p>";
											break;
										default:
											c0.innerHTML = "<p style='margin:0; padding-bottom:1px; padding-left:"+padding+"px'><B style='color:"+color+";'>"+DescricaoParametroServico+"</B></p><p style='padding-bottom:6px; padding-left:"+padding+"px; margin:0;'><input type='text' name='Valor_"+IdParametroServico+"' value='"+Valor+"' style='width:399px;' autocomplete='off' "+tamMax+" onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out');\" "+disabled+" tabindex="+tabindex+"><input type='hidden' name='Obrigatorio_"+IdParametroServico+"' value='"+Obrigatorio+"'><input type='hidden' name='Visivel_"+IdParametroServico+"' value='"+Visivel+"'><input type='hidden' name='TamMinimo_"+IdParametroServico+"' value='"+TamMinimo+"'><BR>"+Obs+"</p>";
											break;
									}
									break;
								case '2': //Senha
									if(ExibirSenha == 2){
										tipo	=	"password";
									}else{
										tipo	=	"text";
									}
									
									c0.innerHTML = "<p style='margin:0; padding-bottom:1px; padding-left:"+padding+"px'><B style='color:"+color+";'>"+DescricaoParametroServico+"</B></p><p style='padding-bottom:6px; padding-left:"+padding+"px; margin:0;'><input type='"+tipo+"' name='Valor_"+IdParametroServico+"' value='"+Valor+"' style='width:399px;' autocomplete='off' "+tamMax+" onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out');\" "+disabled+"  "+tamMax+" tabindex="+tabindex+"><input type='hidden' name='Obrigatorio_"+IdParametroServico+"' value='"+Obrigatorio+"'><input type='hidden' name='Visivel_"+IdParametroServico+"' value='"+Visivel+"'><input type='hidden' name='TamMinimo_"+IdParametroServico+"' value='"+TamMinimo+"'><BR>"+Obs+"</p>";
									break;
								case '3': //GRUPO RADIUS
									if(Valor == ""){
										Valor = ValorDefault;
									}
									c0.innerHTML = "<p style='margin:0; padding-bottom:1px; padding-left:"+padding+"px'><B style='color:"+color+";'>"+DescricaoParametroServico+"</B></p><p style='padding-bottom:6px; padding-left:"+padding+"px; margin:0;'><input type='text' name='Valor_"+IdParametroServico+"' value='"+Valor+"' style='width:399px;' autocomplete='off' "+tamMax+" onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out');\" tabindex="+tabindex+" readOnly><input type='hidden' name='Obrigatorio_"+IdParametroServico+"' value='"+Obrigatorio+"'><input type='hidden' name='Visivel_"+IdParametroServico+"' value='"+Visivel+"'><input type='hidden' name='TamMinimo_"+IdParametroServico+"' value='"+TamMinimo+"'><BR>"+Obs+"</p>";
									break;
								case '4': //IPv4
									c0.innerHTML = "<p style='margin:0; padding-bottom:1px; padding-left:"+padding+"px'><B style='color:"+color+";'>"+DescricaoParametroServico+"</B></p><p style='padding-bottom:6px; padding-left:"+padding+"px; margin:0;'><input type='text' name='Valor_"+IdParametroServico+"' value='"+Valor+"' style='width:399px;' autocomplete='off' "+tamMax+" onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out');\" "+disabled+" tabindex="+tabindex+"><input type='hidden' name='Obrigatorio_"+IdParametroServico+"' value='"+Obrigatorio+"'><input type='hidden' name='Visivel_"+IdParametroServico+"' value='"+Visivel+"'><input type='hidden' name='TamMinimo_"+IdParametroServico+"' value='"+TamMinimo+"'><BR>"+Obs+"</p>";
									break;
								case '5': //IPv6	
									c0.innerHTML = "<p style='margin:0; padding-bottom:1px; padding-left:"+padding+"px'><B style='color:"+color+";'>"+DescricaoParametroServico+"</B></p><p style='padding-bottom:6px; padding-left:"+padding+"px; margin:0;'><input type='text' name='Valor_"+IdParametroServico+"' value='"+Valor+"' style='width:399px;' autocomplete='off' "+tamMax+" onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out');\" "+disabled+" tabindex="+tabindex+"><input type='hidden' name='Obrigatorio_"+IdParametroServico+"' value='"+Obrigatorio+"'><input type='hidden' name='Visivel_"+IdParametroServico+"' value='"+Visivel+"'><input type='hidden' name='TamMinimo_"+IdParametroServico+"' value='"+TamMinimo+"'><BR>"+Obs+"</p>";
									break;
								case '6': //Asterisk	
									c0.innerHTML = "<p style='margin:0; padding-bottom:1px; padding-left:"+padding+"px'><B style='color:"+color+";'>"+DescricaoParametroServico+"</B></p><p style='padding-bottom:6px; padding-left:"+padding+"px; margin:0;'><input type='text' name='Valor_"+IdParametroServico+"' value='"+Valor+"' style='width:399px;' autocomplete='off' "+tamMax+" onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out');\" "+disabled+" tabindex="+tabindex+"><input type='hidden' name='Obrigatorio_"+IdParametroServico+"' value='"+Obrigatorio+"'><input type='hidden' name='Visivel_"+IdParametroServico+"' value='"+Visivel+"'><input type='hidden' name='TamMinimo_"+IdParametroServico+"' value='"+TamMinimo+"'><BR>"+Obs+"</p>";
									break;
							}
						}else{
							campo  = "<p style='margin:0; padding-bottom:1px; padding-left:"+padding+"px'>";
							campo +=	"<B style='color:"+color+";'>"+DescricaoParametroServico+"</B></p>";
							campo += "<p style='padding-bottom:6px; padding-left:"+padding+"px; margin:0;'>";
							
							if(Editavel == 2){
								disabled	=	"disabled";
							}else{
								disabled	=	"";
							}
							
							campo +=	"<select name='Valor_"+IdParametroServico+"'  style='width:406px;' onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out');\" "+disabled+" tabindex="+tabindex+">";
							campo += "<option value=''></option>";
										
							valor	=	OpcaoValor.split("\n");
							
							for(var ii=0; ii<valor.length; ii++){
								selecionado = "";
								Valor = Valor.replace("\n","");	
								if(trim(Valor) == trim(valor[ii])){
									selecionado	=	"selected='true'";
								}
								campo +=	"<option value='"+valor[ii]+"' "+selecionado+">"+valor[ii]+"</option>";
							}
							campo += "</select>";
							campo +=	"<input type='hidden' name='Obrigatorio_"+IdParametroServico+"' value='"+Obrigatorio+"'><input type='hidden' name='Visivel_"+IdParametroServico+"' value='"+Visivel+"'><input type='hidden' name='TamMinimo_"+IdParametroServico+"' value='"+TamMinimo+"'>";
							campo +=	"<BR>"+Obs+"</p>";
							
							c0.innerHTML	=	campo;
						}
						
						cont++;
					}else{
						invisivel	+=	"<div style='display:none'>";
						if(IdTipoParametro == 1){
							invisivel += "<input type='text' name='Valor_"+IdParametroServico+"' value='"+Valor+"' style='width:399px;' autocomplete='off'><input type='hidden' name='Obrigatorio_"+IdParametroServico+"' value='"+Obrigatorio+"'><input type='hidden' name='Visivel_"+IdParametroServico+"' value='"+Visivel+"'><input type='hidden' name='TamMinimo_"+IdParametroServico+"' value='"+TamMinimo+"'></p>";
						}else{
							campo += "<p>";
							campo +=	"<select name='Valor_"+IdParametroServico+"'  style='width:406px;'>";
							campo += 	"<option value=''></option>";
										
							valor	=	OpcaoValor.split("\n");
							for(var ii=0; ii<valor.length; ii++){
								selecionado = "";
								if(trim(ValorDefault) == trim(valor[ii])){
									selecionado	=	"selected=true";
								}
								campo +=	"<option value='"+valor[ii]+"' "+selecionado+">"+valor[ii]+"</option>";
							}
							campo += "</select>";
							campo +=	"<input type='hidden' name='Obrigatorio_"+IdParametroServico+"' value='"+Obrigatorio+"'><input type='hidden' name='Visivel_"+IdParametroServico+"' value='"+Visivel+"'><input type='hidden' name='TamMinimo_"+IdParametroServico+"' value='"+TamMinimo+"'>";
							
							invisivel	+=	campo;
						}
					}
				}
				
				if(cont > 0){	
					document.getElementById('cp_parametrosServico').style.display	=	'block';
				}
				
				if(invisivel !=""){
					tam 	 = document.getElementById('tabelaParametro').rows.length;
					linha	 = document.getElementById('tabelaParametro').insertRow(tam);
					
					linha.accessKey = IdParametroServico; 
					
					c0	= linha.insertCell(0);
					c0.innerHTML	=	invisivel;
				}
				
				if(document.formulario.Erro.value != '' && document.formulario.Erro.value != false){
					scrollWindow('bottom');
				}
			}
			
			if(window.janela != undefined){
				window.janela.close();
			}
		});
	}
	function excluir(IdContrato,IdStatus){
		if(IdStatus  == 1){
			if(excluir_registro() == true){
				if(document.formulario != undefined){
					if(document.formulario.Acao.value == 'inserir'){
						return false;
					}
				}
				
	   			var url = "files/excluir/excluir_contrato.php?IdContrato="+IdContrato;
				
				call_ajax(url,function (xmlhttp){
					if(document.formulario != undefined){
						document.formulario.Erro.value = xmlhttp.responseText;
						
						if(parseInt(xmlhttp.responseText) == 7){
							document.formulario.Acao.value 	= 'inserir';
							url = 'cadastro_contrato.php?Erro='+document.formulario.Erro.value;
							window.location.replace(url);
						} else{
							verificaErro();
						}
					} else{
						var temp   = xmlhttp.responseText.split("_");
						
						if(temp.length>0){
							var numMsg = parseInt(temp[0]);
						} else{
							var numMsg = parseInt(xmlhttp.responseText);
						}
						
						mensagens(numMsg);
						
						if(numMsg == 7){
							var aux = 0, valor=0, desc=0, total=0;
							
							if(temp.length > 1){
								for(ii=1;ii<temp.length;ii++){
									IdContrato	=	temp[ii];
									
									for(var i=0; i<document.getElementById('tableListar').rows.length; i++){
										if(IdContrato == document.getElementById('tableListar').rows[i].accessKey){
											document.getElementById('tableListar').deleteRow(i);
											tableMultColor('tableListar',document.filtro.corRegRand.value);
											aux=1;
											break;
										}
									}	
								}
							} else{
								for(var i=0; i<document.getElementById('tableListar').rows.length; i++){
									if(IdContrato == document.getElementById('tableListar').rows[i].accessKey){
										document.getElementById('tableListar').deleteRow(i);
										tableMultColor('tableListar',document.filtro.corRegRand.value);
										aux=1;
										break;
									}
								}
							}
							
							if(aux==1){
								for(var i=1; i<(document.getElementById('tableListar').rows.length-1); i++){
									temp	=	document.getElementById('tableListar').rows[i].cells[8].innerHTML.split(">");
									temp1	=	temp[1].split("<");
									temp2	=	temp1[0].replace(',','.');
									if(temp2=='') temp2 = 0;
									valor	+=	parseFloat(temp2);
										
									temp	=	document.getElementById('tableListar').rows[i].cells[9].innerHTML.split(">");
									temp1	=	temp[1].split("<");
									temp2	=	temp1[0].replace(',','.');
									if(temp2=='') temp2 = 0;
									desc	+=	parseFloat(temp2);
										
									temp	=	document.getElementById('tableListar').rows[i].cells[10].innerHTML.split(">");
									temp1	=	temp[1].split("<");
									temp2	=	temp1[0].replace(',','.');
									if(temp2=='') temp2 = 0;
									total	+=	parseFloat(temp2);
								}
								
								document.getElementById('tableListarValor').innerHTML		=	formata_float(Arredonda(valor,2),2).replace('.',',');	
								document.getElementById('tableListarDesconto').innerHTML	=	formata_float(Arredonda(desc,2),2).replace('.',',');	
								document.getElementById('tableListarFinal').innerHTML		=	formata_float(Arredonda(total,2),2).replace('.',',');
								document.getElementById("tableListarTotal").innerHTML		=	"Total: "+(document.getElementById('tableListar').rows.length-2);
							}							
						}
					}
				});
			}
		} else{
			return false;
		}
	}
	function help2(msg,prioridade){
		if(msg!=''){
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
	function mudar_servico(){
		if(document.formulario.IdContrato.value == '' || document.formulario.IdStatus.value == 1){
			return false;
		}
		
		window.location.replace("cadastro_contrato_servico.php?IdContrato="+document.formulario.IdContrato.value);		
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
			case "receber":
				if(validar(acao)==true){
					document.formulario.submit();
				}
				break;
			case "Relatorio":
				window.open("relatorio_contrato.php?IdContrato="+document.formulario.IdContrato.value);
				break;
			case "imprimirContrato":
				window.open("imprimir_contrato.php?IdContrato="+document.formulario.IdContrato.value);
				break;
			case "imprimirDistrato":
				window.open("imprimir_distrato.php?IdContrato="+document.formulario.IdContrato.value);
				break;
			default:
				document.formulario.submit();
				break;
		}
	}
	function validar_Data(id,campo){
		if(campo.value == ''){
			document.getElementById(id).style.backgroundColor='#FFFFFF';
			
			if(id=='DataInicio' || id=='DataPrimeiraCobranca'){
				document.getElementById(id).style.color='#C10000';
			}else{
				document.getElementById(id).style.color='#000';
			}
			
			mensagens(0);
			return false;
		}
		
		if(isData(campo.value) == false){		
			document.getElementById(id).style.backgroundColor = '#C10000';
			document.getElementById(id).style.color='#FFFFFF';
			mensagens(27);
			return false;
		}else{
			document.getElementById(id).style.backgroundColor='#FFFFFF';
			
			if(id=='DataInicio' || id=='DataPrimeiraCobranca'){
				document.getElementById(id).style.color='#C10000';
			}else{
				document.getElementById(id).style.color='#000';
			}
			
			if(id == 'DataTermino'){
				if(document.formulario.DataUltimaCobranca.value == ''){
					document.formulario.DataUltimaCobranca.value = campo.value;
				}
			}
		}	
		
		mensagens(0);
		return true;
	}
	function verificaDataFinal(campo,DataInicio,DataFim){
		if(DataInicio != '' && DataFim != ''){
			var dataI = formatDate(DataInicio);
			var dataF = formatDate(DataFim);
			if(dataF < dataI){
				document.getElementById(campo).style.backgroundColor = '#C10000';
				document.getElementById(campo).style.color='#FFFFFF';
				mensagens(39);
				return false;
			}else{
				colorTemp = document.getElementById(campo).style.backgroundColor;
				document.getElementById(campo).style.backgroundColor = '#FFFFFF';
				document.getElementById(campo).style.color='#C10000';
				mensagens(0);
			}
			return true;
		}
	}
	function busca_status(IdStatus,VarStatus){
		if(IdStatus == undefined){
			IdStatus = 0;
		}
		
		if(VarStatus == undefined){
			VarStatus = '';
		}
		
		var url = "xml/parametro_sistema.php?IdGrupoParametroSistema=69&IdParametroSistema="+IdStatus;
		
		if(IdStatus == '201'){
			url += "&VarStatus="+VarStatus;
		}
		
		url += "&"+Math.random();
		
		call_ajax(url,function (xmlhttp){
			if(xmlhttp.responseText != 'false'){
				var nameNode = xmlhttp.responseXML.getElementsByTagName("ValorParametroSistema")[0]; 
				var nameTextNode = nameNode.childNodes[0];
				var ValorParametroSistema = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("Cor")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var Cor = nameTextNode.nodeValue;
				
				document.getElementById("cpStatusContrato").style.display	= "block";
				document.getElementById("cpStatusContrato").innerHTML 		= "<a style='color:"+Cor+"' href='cadastro_contrato_status.php?IdContrato="+document.formulario.IdContrato.value+"&IdStatus="+document.formulario.IdStatus.value+"'>"+ValorParametroSistema+" "+document.formulario.StatusTempoAlteracao.value+"</a>";
			} else{
				document.getElementById("cpStatusContrato").style.display	= "none";
			}
		});
	}
	function mudar_status(){
		if(document.formulario.IdContrato.value == ''){
			return false;
		}
		window.location.replace("cadastro_contrato_status.php?IdContrato="+document.formulario.IdContrato.value);
	}
	function verificaAcao(){
		if(document.formulario != undefined){
			if(document.formulario.Acao.value=='inserir'){			
				document.formulario.bt_inserir.disabled 			= false;
				document.formulario.bt_alterar.disabled		 		= true;
				document.formulario.bt_excluir.disabled			 	= true;
				document.formulario.bt_chegar.disabled 				= true;
				document.formulario.bt_fatura.disabled 				= true;
				document.formulario.bt_imprimir_contrato.disabled 	= true;
				document.formulario.bt_imprimir_distrato.disabled	= true;
				document.formulario.bt_relatorio.disabled			= true;
			}
			if(document.formulario.Acao.value=='alterar'){			
				document.formulario.bt_inserir.disabled 	= true;
				document.formulario.bt_chegar.disabled 		= false;
				
				//Cancelado
				if(document.formulario.IdStatus.value == 1){
					document.formulario.bt_alterar.disabled 	= true;
					document.formulario.bt_excluir.disabled 	= false;
				}else{
					document.formulario.bt_alterar.disabled 	= false;
					document.formulario.bt_excluir.disabled 	= true;
				}
			}
		}	
	}
	function listarParametroLocalCobranca(IdLocalCobranca,Erro,IdContrato){
		while(document.getElementById('tabelaParametroLocalCobranca').rows.length > 0){
			document.getElementById('tabelaParametroLocalCobranca').deleteRow(0);
		}		
		
		var tam, linha, c0, c1, c2, c3, c4, c5;
		
		if(IdLocalCobranca == ''){
			IdLocalCobranca = 0;
		}
		
		var url = "xml/contrato_parametro_local_cobranca.php?IdLocalCobranca="+IdLocalCobranca+"&IdContrato="+IdContrato+"&IdStatus=1&Visivel=1";
		
		call_ajax(url,function (xmlhttp){
			if(Erro != false){
				document.formulario.Erro.value = 0;
				verificaErro();
			}
			
			if(xmlhttp.responseText == "false"){		
				document.getElementById('cp_parametrosLocalCobranca').style.display	=	'none';
			} else{
				var obsTemp = new Array(), invisivel = "",cont = 0;
				
				for(var i=0; i<xmlhttp.responseXML.getElementsByTagName("IdLocalCobrancaParametroContrato").length; i++){
					var nameNode = xmlhttp.responseXML.getElementsByTagName("IdLocalCobrancaParametroContrato")[i]; 
					var nameTextNode = nameNode.childNodes[0];
					var IdLocalCobrancaParametroContrato = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoParametroContrato")[i]; 
					nameTextNode = nameNode.childNodes[0];
					var DescricaoParametroContrato = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("Obrigatorio")[i]; 
					nameTextNode = nameNode.childNodes[0];
					var Obrigatorio = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("Editavel")[i]; 
					nameTextNode = nameNode.childNodes[0];
					var Editavel = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("Valor")[i]; 
					nameTextNode = nameNode.childNodes[0];
					var Valor = nameTextNode.nodeValue;	
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("ValorDefault")[i]; 
					nameTextNode = nameNode.childNodes[0];
					var ValorDefault = nameTextNode.nodeValue;	
				
					nameNode = xmlhttp.responseXML.getElementsByTagName("Obs")[i]; 
					nameTextNode = nameNode.childNodes[0];
					var Obs = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("IdTipoParametro")[i]; 
					nameTextNode = nameNode.childNodes[0];
					var IdTipoParametro = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("IdMascaraCampo")[i]; 
					nameTextNode = nameNode.childNodes[0];
					var IdMascaraCampo = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("OpcaoValor")[i]; 
					nameTextNode = nameNode.childNodes[0];
					var OpcaoValor = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("Visivel")[i]; 
					nameTextNode = nameNode.childNodes[0];
					var Visivel = nameTextNode.nodeValue;
					
					if(Valor == '' && ValorDefault != '' && document.formulario.Acao.value == 'inserir'){
						Valor	=	ValorDefault;
					}
					
					if(Visivel == '1'){
						obsTemp[i]	=	Obs;
						
						tam 	 = document.getElementById('tabelaParametroLocalCobranca').rows.length;

						if(cont%2 == 0){
							linha	 = document.getElementById('tabelaParametroLocalCobranca').insertRow(tam);
							tabindex = 200 + cont + 1;
							pos		 = 0;
							padding	 = 22;
						}else{	
							padding	 = 10;
							tabindex = 200 + cont;
							pos		 = 1;
							if(obsTemp[(cont-1)]!= undefined && obsTemp[(cont-1)]!= ''){
								if(Obs	==	'')	Obs	=	'<BR>';
							}
						}
						
						if((cont+1) == xmlhttp.responseXML.getElementsByTagName("IdLocalCobrancaParametroContrato").length && cont%2 == 0){
							padding	 = 22;	
						}
						
						if(Obrigatorio == 1){
							color = "#C10000";
						}else{
							color = "#000000";
						}
						
						if(Editavel == 2){
							disabled	=	"readOnly";
						}else{
							disabled	=	"";
						}
						
						linha.accessKey = IdLocalCobrancaParametroContrato; 

						c0	= linha.insertCell(pos);
						
						
						if(IdTipoParametro == 1){
							switch(IdMascaraCampo){
								case '1': //Data
									c0.innerHTML = "<p style='margin:0; padding-bottom:1px; padding-left:"+padding+"px'><B style='color:"+color+";'>"+DescricaoParametroContrato+"</B></p><p style='padding-bottom:6px; padding-left:"+padding+"px; margin:0;'><input type='text' name='LocalCobranca_Valor_"+IdLocalCobrancaParametroContrato+"' value='"+Valor+"' style='width:399px;' autocomplete='off' onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out');\" "+disabled+" onkeypress=\"mascara(this,event,'date')\" maxlength='10' tabindex="+tabindex+"><input type='hidden' name='LocalCobranca_Obrigatorio_"+IdLocalCobrancaParametroContrato+"' value='"+Obrigatorio+"'><input type='hidden' name='LocalCobranca_Visivel_"+IdLocalCobrancaParametroContrato+"' value='"+Visivel+"'><BR>"+Obs+"</p>";
									break;
								case '2': //Inteiro
									c0.innerHTML = "<p style='margin:0; padding-bottom:1px; padding-left:"+padding+"px'><B style='color:"+color+";'>"+DescricaoParametroContrato+"</B></p><p style='padding-bottom:6px; padding-left:"+padding+"px; margin:0;'><input type='text' name='LocalCobranca_Valor_"+IdLocalCobrancaParametroContrato+"' value='"+Valor+"' style='width:399px;' autocomplete='off' onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out');\" "+disabled+" onkeypress=\"mascara(this,event,'int')\" tabindex="+tabindex+"><input type='hidden' name='LocalCobranca_Obrigatorio_"+IdLocalCobrancaParametroContrato+"' value='"+Obrigatorio+"'><input type='hidden' name='LocalCobranca_Visivel_"+IdLocalCobrancaParametroContrato+"' value='"+Visivel+"'><BR>"+Obs+"</p>";
									break;
								case '3': //Real
									if(Editavel == 1){
										c0.innerHTML = "<p style='margin:0; padding-bottom:1px; padding-left:"+padding+"px'><B style='color:"+color+";'>"+DescricaoParametroContrato+"</B></p><p style='padding-bottom:6px; padding-left:"+padding+"px; margin:0;'><input type='text' name='LocalCobranca_Valor_"+IdLocalCobrancaParametroContrato+"' value='"+Valor+"' style='width:399px;' autocomplete='off' onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out');\" "+disabled+" onkeypress=\"mascara(this,event,'float')\" maxlength='12' tabindex="+tabindex+"><input type='hidden' name='LocalCobranca_Obrigatorio_"+IdLocalCobrancaParametroContrato+"' value='"+Obrigatorio+"'><input type='hidden' name='LocalCobranca_Visivel_"+IdLocalCobrancaParametroContrato+"' value='"+Visivel+"'><BR>"+Obs+"</p>";
									}else{
										c0.innerHTML = "<p style='margin:0; padding-bottom:1px; padding-left:"+padding+"px'><B style='color:"+color+";'>"+DescricaoParametroContrato+"</B></p><p style='padding-bottom:6px; padding-left:"+padding+"px; margin:0;'><input type='text' name='LocalCobranca_Valor_"+IdLocalCobrancaParametroContrato+"' value='"+Valor+"' style='width:399px;' autocomplete='off' onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out');\" "+disabled+" maxlength='12' tabindex="+tabindex+"><input type='hidden' name='LocalCobranca_Obrigatorio_"+IdLocalCobrancaParametroContrato+"' value='"+Obrigatorio+"'><input type='hidden' name='LocalCobranca_Visivel_"+IdLocalCobrancaParametroContrato+"' value='"+Visivel+"'><BR>"+Obs+"</p>";
									}
									break;
								case '4': //Usu�rio
									c0.innerHTML = "<p style='margin:0; padding-bottom:1px; padding-left:"+padding+"px'><B style='color:"+color+";'>"+DescricaoParametroContrato+"</B></p><p style='padding-bottom:6px; padding-left:"+padding+"px; margin:0;'><input type='text' name='LocalCobranca_Valor_"+IdLocalCobrancaParametroContrato+"' value='"+Valor+"' style='width:399px;' autocomplete='off' onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out');\" "+disabled+" onkeypress=\"mascara(this,event,'usuario')\" maxlength='255' tabindex="+tabindex+"><input type='hidden' name='LocalCobranca_Obrigatorio_"+IdLocalCobrancaParametroContrato+"' value='"+Obrigatorio+"'><input type='hidden' name='LocalCobranca_Visivel_"+IdLocalCobrancaParametroContrato+"' value='"+Visivel+"'><BR>"+Obs+"</p>";
									break;
								case '5': //MAC
									c0.innerHTML = "<p style='margin:0; padding-bottom:1px; padding-left:"+padding+"px'><B style='color:"+color+";'>"+DescricaoParametroContrato+"</B></p><p style='padding-bottom:6px; padding-left:"+padding+"px; margin:0;'><input type='text' name='LocalCobranca_Valor_"+IdLocalCobrancaParametroContrato+"' value='"+Valor+"' style='width:399px;' autocomplete='off' onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out');\" "+disabled+" onkeypress=\"mascara(this,event,'mac')\" maxlength='18' tabindex="+tabindex+"><input type='hidden' name='LocalCobranca_Obrigatorio_"+IdLocalCobrancaParametroContrato+"' value='"+Obrigatorio+"'><input type='hidden' name='LocalCobranca_Visivel_"+IdLocalCobrancaParametroContrato+"' value='"+Visivel+"'><BR>"+Obs+"</p>";
									break;
								default:
									c0.innerHTML = "<p style='margin:0; padding-bottom:1px; padding-left:"+padding+"px'><B style='color:"+color+";'>"+DescricaoParametroContrato+"</B></p><p style='padding-bottom:6px; padding-left:"+padding+"px; margin:0;'><input type='text' name='LocalCobranca_Valor_"+IdLocalCobrancaParametroContrato+"' value='"+Valor+"' style='width:399px;' autocomplete='off' maxlength='255' onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out');\" "+disabled+" tabindex="+tabindex+"><input type='hidden' name='LocalCobranca_Obrigatorio_"+IdLocalCobrancaParametroContrato+"' value='"+Obrigatorio+"'><input type='hidden' name='LocalCobranca_Visivel_"+IdLocalCobrancaParametroContrato+"' value='"+Visivel+"'><BR>"+Obs+"</p>";
							}
						}else{
							campo  = "<p style='margin:0; padding-bottom:1px; padding-left:"+padding+"px'>";
							campo +=	"<B style='color:"+color+";'>"+DescricaoParametroContrato+"</B></p>";
							campo += "<p style='padding-bottom:6px; padding-left:"+padding+"px; margin:0;'>";
							
							if(Editavel == 2){
								disabled	=	"disabled";
							}else{
								disabled	=	"";
							}
							
							campo +=	"<select name='LocalCobranca_Valor_"+IdLocalCobrancaParametroContrato+"'  style='width:406px;' onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out');\" "+disabled+" tabindex="+tabindex+">";
							campo += "<option value=''></option>";
										
							valor	=	OpcaoValor.split("\n");
							for(var ii=0; ii<valor.length; ii++){
								selecionado = "";
								if(trim(Valor) == trim(valor[ii])){
									selecionado	=	"selected=true";
								}
								campo +=	"<option value='"+valor[ii]+"' "+selecionado+">"+valor[ii]+"</option>";
							}
							campo += "</select>";
							campo +=	"<input type='hidden' name='LocalCobranca_Obrigatorio_"+IdLocalCobrancaParametroContrato+"' value='"+Obrigatorio+"'><input type='hidden' name='LocalCobranca_Visivel_"+IdLocalCobrancaParametroContrato+"' value='"+Visivel+"'>";
							campo +=	"<BR>"+Obs+"</p>";
							
							c0.innerHTML	=	campo;
						}								
						cont++;
					}else{
						invisivel	+=	"<div style='display:none'>";
						if(IdTipoParametro == 1){
							invisivel += "<input type='text' name='LocalCobranca_Valor_"+IdLocalCobrancaParametroContrato+"' value='"+ValorDefault+"' style='width:399px;' autocomplete='off'><input type='hidden' name='LocalCobranca_Obrigatorio_"+IdLocalCobrancaParametroContrato+"' value='"+Obrigatorio+"'><input type='hidden' name='LocalCobranca_Visivel_"+IdLocalCobrancaParametroContrato+"' value='"+Visivel+"'>";
						}else{
							campo += "<p>";
							campo +=	"<select name='LocalCobranca_Valor_"+IdLocalCobrancaParametroContrato+"'  style='width:406px;'>";
							campo += "<option value=''></option>";
										
							valor	=	OpcaoValor.split("\n");
							for(var ii=0; ii<valor.length; ii++){
								selecionado = "";
								if(trim(ValorDefault) == trim(valor[ii])){
									selecionado	=	"selected=true";
								}
								campo +=	"<option value='"+valor[ii]+"' "+selecionado+">"+valor[ii]+"</option>";
							}
							campo += "</select>";
							campo +=	"<input type='hidden' name='LocalCobranca_Obrigatorio_"+IdLocalCobrancaParametroContrato+"' value='"+Obrigatorio+"'><input type='hidden' name='LocalCobranca_Visivel_"+IdLocalCobrancaParametroContrato+"' value='"+Visivel+"'>";
							
							invisivel	+=	campo;
						}
					
					
						invisivel	+=	"</div>";
					}
				}
				if(cont > 0){
					document.getElementById('cp_parametrosLocalCobranca').style.display	=	'block';
				}
				if(invisivel !=""){
					tam 	 = document.getElementById('tabelaParametroLocalCobranca').rows.length;
					linha	 = document.getElementById('tabelaParametroLocalCobranca').insertRow(tam);
						
					linha.accessKey = IdLocalCobrancaParametroContrato; 
						
					c0	= linha.insertCell(0);
					c0.innerHTML	=	invisivel;
				}
				
				if(document.formulario.Erro.value != '' && document.formulario.Erro.value != false){
					scrollWindow('bottom');
				}
			}	
			
			if(window.janela != undefined){
				window.janela.close();
			}
		});
	}
	function busca_dia_cobranca(IdPessoa,DiaCobrancaDefault){
		if(IdPessoa == ""){
			IdPessoa = 0;
		}
		if(DiaCobrancaDefault == undefined){
			DiaCobrancaDefault = 0;
		}
		
	    var url = "xml/dia_cobranca.php?IdPessoa="+IdPessoa;
		
		call_ajax(url,function (xmlhttp){
			if(xmlhttp.responseText != 'false'){		
				while(document.formulario.DiaCobranca.options.length > 0){
					document.formulario.DiaCobranca.options[0] = null;
				}
				
				var nameNode, nameTextNode, DiaCobranca;					
				
				addOption(document.formulario.DiaCobranca,"","0");
				
				for(var i=0; i<xmlhttp.responseXML.getElementsByTagName("ValorCodigoInterno").length; i++){
					nameNode = xmlhttp.responseXML.getElementsByTagName("DiaCobranca")[i]; 
					nameTextNode = nameNode.childNodes[0];
					DiaCobranca = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("ValorCodigoInterno")[i]; 
					nameTextNode = nameNode.childNodes[0];
					ValorCodigoInterno = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoCodigoInterno")[i]; 
					nameTextNode = nameNode.childNodes[0];
					DescricaoCodigoInterno = nameTextNode.nodeValue;
				
					addOption(document.formulario.DiaCobranca,DescricaoCodigoInterno,ValorCodigoInterno);
				}
				
				if(DiaCobranca==""){
					DiaCobranca	=	DiaCobrancaDefault;		
				}
				
				for(i=0;i<document.formulario.DiaCobranca.length;i++){
					if(document.formulario.DiaCobranca[i].value == DiaCobranca){
						document.formulario.DiaCobranca[i].selected	=	true;
						document.formulario.DiaCobrancaTemp.value	=	DiaCobranca;
						break;
					}
				}
			} else{
				while(document.formulario.DiaCobranca.options.length > 0){
					document.formulario.DiaCobranca.options[0] = null;
				}
			}
		});	
	}
	function atualizarHistorico(IdContrato){
		if(IdContrato == undefined){
			IdContrato = 0;
		}
		
	    var url = "xml/contrato.php?IdContrato="+IdContrato;
		
		call_ajax(url,function (xmlhttp){
			if(xmlhttp.responseText != 'false'){		
				var nameNode, nameTextNode, Obs, IdStatus, VarStatus;					
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("Obs")[0]; 
				nameTextNode = nameNode.childNodes[0];
				Obs = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("IdStatus")[0]; 
				nameTextNode = nameNode.childNodes[0];
				IdStatus = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("VarStatus")[0]; 
				nameTextNode = nameNode.childNodes[0];
				VarStatus = nameTextNode.nodeValue;
				
				document.formulario.HistoricoObs.value = Obs;
				busca_status(IdStatus,VarStatus);
			}
		});	
	}
	function selecionaVencimento(IdContratoAgrupador){
		if(IdContratoAgrupador == undefined || IdContratoAgrupador == "" || IdContratoAgrupador == 0){
			IdContratoAgrupador = 0;
			
			document.formulario.DiaCobranca.disabled	=	false;
		} else{
			var url = "xml/contrato.php?IdContrato="+IdContratoAgrupador;
			
			call_ajax(url,function (xmlhttp){
				if(xmlhttp.responseText != 'false'){		
					var nameNode, nameTextNode, DiaCobranca;					
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("DiaCobranca")[0]; 
					nameTextNode = nameNode.childNodes[0];
					DiaCobranca = nameTextNode.nodeValue;
					
					for(i=0;i<document.formulario.DiaCobranca.length;i++){
						if(trim(document.formulario.DiaCobranca[i].value) == trim(DiaCobranca)){
							document.formulario.DiaCobranca[i].selected	=	true;
							document.formulario.DiaCobrancaTemp.value	=	DiaCobranca;
							break;
						}
					}
					
					document.formulario.DiaCobranca.disabled	=	true;
				}
			});	
		}
	}
	function busca_opcoes_pessoa_endereco(IdPessoa,IdPessoaEnderecoTemp,IdPessoaEnderecoCobrancaTemp){
		if(IdPessoaEnderecoTemp == undefined){
			IdPessoaEnderecoTemp = "";
		}
		
		if(IdPessoaEnderecoCobrancaTemp == undefined){
			IdPessoaEnderecoCobrancaTemp = "";
		}
		
		while(document.formulario.IdPessoaEndereco.options.length > 0){
			document.formulario.IdPessoaEndereco.options[0] = null;
		}
		
		while(document.formulario.IdPessoaEnderecoCobranca.options.length > 0){
			document.formulario.IdPessoaEnderecoCobranca.options[0] = null;
		}
		
		if(IdPessoa != ""){
		    var url = "xml/pessoa_endereco.php?IdPessoa="+IdPessoa;
			
			call_ajax(url,function (xmlhttp){
				if(xmlhttp.responseText != 'false'){		
					var nameNode, nameTextNode, IdPessoaEndereco,DescricaoEndereco;					
					
					addOption(document.formulario.IdPessoaEndereco,"","0");
					addOption(document.formulario.IdPessoaEnderecoCobranca,"","0");
					
					for(i=0;i<xmlhttp.responseXML.getElementsByTagName("IdPessoaEndereco").length;i++){
						nameNode = xmlhttp.responseXML.getElementsByTagName("IdPessoaEndereco")[i]; 
						nameTextNode = nameNode.childNodes[0];
						IdPessoaEndereco = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoEndereco")[i]; 
						nameTextNode = nameNode.childNodes[0];
						DescricaoEndereco = nameTextNode.nodeValue;
						
						addOption(document.formulario.IdPessoaEndereco,DescricaoEndereco,IdPessoaEndereco);
						addOption(document.formulario.IdPessoaEnderecoCobranca,DescricaoEndereco,IdPessoaEndereco);
					}
					
					document.formulario.IdPessoaEndereco[0].selected				= true;
					document.formulario.IdPessoaEnderecoCobranca[0].selected	= true;
					
					var iTemp = i;
					
					if(document.formulario.Acao.value == 'inserir'){
						if(IdPessoaEnderecoTemp != "" && document.formulario.PreenchimentoAutomaticoEndereco.value == 1 && iTemp < 2){
							for(i=0;i<document.formulario.IdPessoaEndereco.options.length;i++){
								if(document.formulario.IdPessoaEndereco[i].value == IdPessoaEnderecoTemp){
									document.formulario.IdPessoaEndereco[i].selected = true;
									i = document.formulario.IdPessoaEndereco.options.length;
									
									busca_pessoa_endereco(IdPessoa,IdPessoaEnderecoTemp);
								}
							}
						} else{
							busca_pessoa_endereco();
						}
						
						if(IdPessoaEnderecoCobrancaTemp != "" && document.formulario.PreenchimentoAutomaticoEndereco.value == 1 && iTemp < 2){
							for(i=0;i<document.formulario.IdPessoaEnderecoCobranca.options.length;i++){
								if(document.formulario.IdPessoaEnderecoCobranca[i].value == IdPessoaEnderecoCobrancaTemp){
									document.formulario.IdPessoaEnderecoCobranca[i].selected = true;
									i = document.formulario.IdPessoaEnderecoCobranca.options.length;
									
									busca_pessoa_endereco_cobranca(IdPessoa,IdPessoaEnderecoCobrancaTemp);
								}
							}
						} else{
							busca_pessoa_endereco_cobranca();
						}
					} else{
						if(IdPessoaEnderecoTemp != ""){
							for(i=0;i<document.formulario.IdPessoaEndereco.options.length;i++){
								if(document.formulario.IdPessoaEndereco[i].value == IdPessoaEnderecoTemp){
									document.formulario.IdPessoaEndereco[i].selected = true;
									i = document.formulario.IdPessoaEndereco.options.length;
									
									busca_pessoa_endereco(IdPessoa,IdPessoaEnderecoTemp);
								}
							}
						} else{
							busca_pessoa_endereco();
						}
						
						if(IdPessoaEnderecoCobrancaTemp != ""){
							for(i=0;i<document.formulario.IdPessoaEnderecoCobranca.options.length;i++){
								if(document.formulario.IdPessoaEnderecoCobranca[i].value == IdPessoaEnderecoCobrancaTemp){
									document.formulario.IdPessoaEnderecoCobranca[i].selected = true;
									i = document.formulario.IdPessoaEnderecoCobranca.options.length;
									
									busca_pessoa_endereco_cobranca(IdPessoa,IdPessoaEnderecoCobrancaTemp);
								}
							}
						} else{
							busca_pessoa_endereco_cobranca();
						}
					}
				}
			});	
		}
	}
	function busca_pessoa_endereco(IdPessoa,IdPessoaEndereco){
		if(IdPessoa==''){
			IdPessoa = 0;
		}
		
		if(IdPessoaEndereco=='' || IdPessoaEndereco==undefined){
			IdPessoaEndereco = 0;
		}
		
		var url = "xml/pessoa_endereco.php?IdPessoa="+IdPessoa+"&IdPessoaEndereco="+IdPessoaEndereco;
		
		call_ajax(url,function (xmlhttp){
			if(xmlhttp.responseText != 'false'){		
				var nameNode, nameTextNode;					
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("IdPessoaEndereco")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var IdPessoaEndereco = nameTextNode.nodeValue;
					
				nameNode = xmlhttp.responseXML.getElementsByTagName("NomeResponsavelEndereco")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var NomeResponsavelEndereco = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("CEP")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var CEP = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("Endereco")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var Endereco = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("Numero")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var Numero = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("Complemento")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var Complemento = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("Bairro")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var Bairro = nameTextNode.nodeValue;
				
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
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("IdCidade")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var IdCidade = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("NomeCidade")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var NomeCidade = nameTextNode.nodeValue;
				
				document.formulario.NomeResponsavelEndereco.value			=	NomeResponsavelEndereco;
				document.formulario.CEP.value								=	CEP;
				document.formulario.Endereco.value							=	Endereco;
				document.formulario.Numero.value							=	Numero;
				document.formulario.Complemento.value						=	Complemento;
				document.formulario.Bairro.value							=	Bairro;
				document.formulario.IdPais.value							=	IdPais;
				document.formulario.Pais.value								=	NomePais;
				document.formulario.IdEstado.value							=	IdEstado;
				document.formulario.Estado.value							=	NomeEstado;
				document.formulario.SiglaEstado.value						=	SiglaEstado;
				document.formulario.IdCidade.value							=	IdCidade;
				document.formulario.Cidade.value							=	NomeCidade;
			}else{
				document.formulario.NomeResponsavelEndereco.value			=	"";
				document.formulario.CEP.value								=	"";
				document.formulario.Endereco.value							=	"";
				document.formulario.Numero.value							=	"";
				document.formulario.Complemento.value						=	"";
				document.formulario.Bairro.value							=	"";
				document.formulario.IdPais.value							=	"";
				document.formulario.Pais.value								=	"";
				document.formulario.IdEstado.value							=	"";
				document.formulario.Estado.value							=	"";
				document.formulario.SiglaEstado.value						=	"";
				document.formulario.IdCidade.value							=	"";
				document.formulario.Cidade.value							=	"";
			}
		});	
	}	
	function busca_pessoa_endereco_cobranca(IdPessoa,IdPessoaEndereco){
		if(IdPessoa==''){
			IdPessoa = 0;
		}
		
		if(IdPessoaEndereco=='' || IdPessoaEndereco==undefined){
			IdPessoaEndereco = 0;
		}
		
		var url = "xml/pessoa_endereco.php?IdPessoa="+IdPessoa+"&IdPessoaEndereco="+IdPessoaEndereco;
		
		call_ajax(url,function (xmlhttp){
			if(xmlhttp.responseText != 'false'){		
				var nameNode, nameTextNode;					
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("IdPessoaEndereco")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var IdPessoaEndereco = nameTextNode.nodeValue;
					
				nameNode = xmlhttp.responseXML.getElementsByTagName("NomeResponsavelEndereco")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var NomeResponsavelEndereco = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("CEP")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var CEP = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("Endereco")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var Endereco = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("Numero")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var Numero = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("Complemento")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var Complemento = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("Bairro")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var Bairro = nameTextNode.nodeValue;
				
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
				var IdCidade = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("NomeCidade")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var NomeCidade = nameTextNode.nodeValue;
				
				document.formulario.NomeResponsavelEnderecoCobranca.value	=	NomeResponsavelEndereco;
				document.formulario.CEPCobranca.value						=	CEP;
				document.formulario.EnderecoCobranca.value					=	Endereco;
				document.formulario.NumeroCobranca.value					=	Numero;
				document.formulario.ComplementoCobranca.value				=	Complemento;
				document.formulario.BairroCobranca.value					=	Bairro;
				document.formulario.IdPaisCobranca.value					=	IdPais;
				document.formulario.PaisCobranca.value						=	NomePais;
				document.formulario.IdEstadoCobranca.value					=	IdEstado;
				document.formulario.EstadoCobranca.value					=	NomeEstado;
				document.formulario.IdCidadeCobranca.value					=	IdCidade;
				document.formulario.CidadeCobranca.value					=	NomeCidade;
			}else{
				document.formulario.NomeResponsavelEnderecoCobranca.value	=	"";
				document.formulario.CEPCobranca.value						=	"";
				document.formulario.EnderecoCobranca.value					=	"";
				document.formulario.NumeroCobranca.value					=	"";
				document.formulario.ComplementoCobranca.value				=	"";
				document.formulario.BairroCobranca.value					=	"";
				document.formulario.IdPaisCobranca.value					=	"";
				document.formulario.PaisCobranca.value						=	"";
				document.formulario.IdEstadoCobranca.value					=	"";
				document.formulario.EstadoCobranca.value					=	"";
				document.formulario.IdCidadeCobranca.value					=	"";
				document.formulario.CidadeCobranca.value					=	"";
			}
		});	
	}	
	function busca_filtro_cidade(IdEstado,IdCidadeTemp){
		if(IdEstado == undefined || IdEstado==''){
			IdEstado = 0;			
		}
		if(IdCidadeTemp == undefined){
			IdCidadeTemp = '';
		}
		var xmlhttp = false;
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
	    
	    url = "xml/cidade.php?IdPais="+1+"&IdEstado="+IdEstado;

		xmlhttp.open("GET", url,true);
	    	
		xmlhttp.onreadystatechange = function(){ 
			if(xmlhttp.readyState == 4){ 
				if(xmlhttp.status == 200){
					if(xmlhttp.responseText != 'false'){		
						while(document.filtro.filtro_cidade.options.length > 0){
							document.filtro.filtro_cidade.options[0] = null;
						}
						
						var nameNode, nameTextNode, NomeCidade;	
						addOption(document.filtro.filtro_cidade,"","");				
											
						for(var i=0; i<xmlhttp.responseXML.getElementsByTagName("IdCidade").length; i++){
							nameNode = xmlhttp.responseXML.getElementsByTagName("IdCidade")[i]; 
							nameTextNode = nameNode.childNodes[0];
							IdCidade = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("NomeCidade")[i]; 
							nameTextNode = nameNode.childNodes[0];
							NomeCidade = nameTextNode.nodeValue;
						
							addOption(document.filtro.filtro_cidade,NomeCidade,IdCidade);
						}					
						
						if(IdCidadeTemp!=""){
							for(i=0;i<document.filtro.filtro_cidade.length;i++){
								if(document.filtro.filtro_cidade[i].value == IdCidadeTemp){
									document.filtro.filtro_cidade[i].selected	=	true;
									break;
								}
							}
						}else{
							document.filtro.filtro_cidade[0].selected	=	true;
						}						
					}else{
						while(document.filtro.filtro_cidade.options.length > 0){
							document.filtro.filtro_cidade.options[0] = null;
						}						
					}
					
				}		
			}
			return true;
		}
		xmlhttp.send(null);	
	}
	function filtroConsultarPor(valor){
		if(valor != ""){
			document.getElementById("titConsultarPor").style.display	= 'block';
			document.getElementById("cpConsultarPor").style.display 	= 'block';
		}else{
			document.getElementById("titConsultarPor").style.display 	= 'none';
			document.getElementById("cpConsultarPor").style.display 	= 'none';
			document.filtro.cpConsultarPor.value 						= '';
		}
	}
	function busca_cfop_servico(IdServico, CFOPDefault){
		if(IdServico == ""){
			IdServico = 0;
		}
		
		if(CFOPDefault == undefined){
			CFOPDefault = 0;
		}
		
		var url = "xml/cfop_servico.php?IdServico="+IdServico;
		
		call_ajax(url,function (xmlhttp){
			if(xmlhttp.responseText != 'false'){		
				while(document.formulario.CFOPServico.options.length > 0){
					document.formulario.CFOPServico.options[0] = null;
				}
				
				if(document.getElementById("cpNotaFiscalCDA").style.display == "none" && document.getElementById("cpAdequarLeis").style.display == "none"){
					document.getElementById("spServicoCFOP").className = "find";
					document.formulario.CFOPServico.style.width = "822px";
				} else{
					document.getElementById("spServicoCFOP").className = "separador";
					
					if(document.getElementById("cpNotaFiscalCDA").style.display == "block" && document.getElementById("cpAdequarLeis").style.display == "none") {
						document.formulario.CFOPServico.style.width = "604px";
					} else if(document.getElementById("cpNotaFiscalCDA").style.display == "none" && document.getElementById("cpAdequarLeis").style.display == "block"){
						document.formulario.CFOPServico.style.width = "612px";
					} else{
						document.formulario.CFOPServico.style.width = "394px";
					}
				}
				
				document.getElementById("cpServicoCFOP").style.display = "block";
				var nameNode, nameTextNode, CFOP, NaturezaOperacao;
				addOption(document.formulario.CFOPServico,"","");
				
				for(var i=0; i<xmlhttp.responseXML.getElementsByTagName("CFOP").length; i++){
					nameNode = xmlhttp.responseXML.getElementsByTagName("CFOP")[i]; 
					nameTextNode = nameNode.childNodes[0];
					CFOP = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("NaturezaOperacao")[i]; 
					nameTextNode = nameNode.childNodes[0];
					NaturezaOperacao = nameTextNode.nodeValue;
					
					addOption(document.formulario.CFOPServico, CFOP + " - " + NaturezaOperacao, CFOP);
				}
				
				for(i = 0; i < document.formulario.CFOPServico.length; i++){
					if(document.formulario.CFOPServico[i].value == CFOPDefault){
						document.formulario.CFOPServico[i].selected	= true;
						break;
					}
				}
			} else{
				while(document.formulario.CFOPServico.options.length > 0){
					document.formulario.CFOPServico.options[0] = null;
				}
				
				document.getElementById("cpServicoCFOP").style.display = "none";
			}
		});	
	}
	function busca_monitor(IdContrato){
		if(IdContrato == undefined || IdContrato == ''){
			IdContrato = document.formulario.IdContrato.value;
		}
		
		if(IdContrato != ""){
			document.getElementById("cp_monitor").style.display = "block"; 
			document.getElementById("cp_MonitorSinal").style.display = "block"; 
			atualizar_grafico();
		} else{
			document.getElementById("cp_monitor").style.display = "none"; 
			document.getElementById("cp_MonitorSinal").style.display = "none"; 
		}
	}
	function atualizar_grafico(){
		//var url_trafego = "./graficos/trafego_conexao.php?IdContrato="+document.formulario.IdContrato.value+"&IdGrafico="+document.formulario.IdTipoGrafico.value+"&random="+Math.floor(Math.random()*11);
		var url_sinal = "./graficos/sinal_conexao.php?IdContrato="+document.formulario.IdContrato.value+"&IdLoja="+document.formulario.IdLoja.value;
		//if(document.formulario.AtualizacaoAutomatica.checked){
			//document.images["img_MonitorTrafego"].src = url_trafego;
			document.images["img_MonitorSinal"].src = url_sinal;
			setTimeout("atualizar_grafico();", 15000);
			//atualizaSessao('filtro_MonitorAtualizacaoAutomatica', 1);
		/*} else{
			document.images["img_MonitorTrafego"].src = url_trafego;
			document.images["img_MonitorSinal"].src = url_sinal;
			atualizaSessao('filtro_MonitorAtualizacaoAutomatica', 2);
		}*/
		
		
	}
	function mensagens2(n){
		var msg='';
		var prioridade='';
		
		if(n == 0){
			return;
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
					
					if(msg != ''){
						scrollWindow("bottom");
					}
					
					document.getElementById('helpText').style.display = "block";
					
					switch (prioridade){
						case 'atencao':
							document.getElementById('helpText').innerHTML += "<div style='color:#C10000;'>"+msg+"</div>";
							return true;
						default:
							document.getElementById('helpText').innerHTML += "<div style='color:#004975;'>"+msg+"</div>";
							return true;
					}
				}
			}
		}
		xmlhttp.send(null);
	}
	function filtro_buscar_pessoa(IdPessoa){
		var xmlhttp = false;
		if(IdPessoa == '' ||  IdPessoa == undefined){
			IdPessoa = 0;
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
	    
	    url = "xml/pessoa.php?IdPessoa="+IdPessoa+"&IdStatus=1&IdTipoServico=1";
		xmlhttp.open("GET", url,true);
		// Carregando...
		carregando(true);
		xmlhttp.onreadystatechange = function(){ 
			if(xmlhttp.readyState == 4){ 
				if(xmlhttp.status == 200){
					if(xmlhttp.responseText == 'false'){
						document.filtro.filtro_id_pessoa.value				= '';
						document.filtro.filtro_descricao_id_pessoa.value	= '';
					} else {
						nameNode = xmlhttp.responseXML.getElementsByTagName("IdPessoa")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var IdPessoa = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("Nome")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var Nome = nameTextNode.nodeValue;
						
						document.filtro.filtro_id_pessoa.value				= IdPessoa;
						document.filtro.filtro_descricao_id_pessoa.value	= Nome;
						document.filtro.IdPessoa.value = "";
					}
					// Fim de Carregando
					carregando(false);
				}
			}
		}
		xmlhttp.send(null);	
	}
	
	function filtro_buscar_servico(IdServico){
		if(IdServico == '' || IdServico == undefined){
			IdServico = 0;
		}
		
	    //var url = "xml/servico.php?IdServico="+IdServico+"&IdStatus=1&IdTipoServico=1";
	    var url = "xml/servico.php?IdServico="+IdServico+"&IdTipoServico=1";
		
		call_ajax(url, function (xmlhttp) {
			if(xmlhttp.responseText == 'false'){
				document.filtro.filtro_id_servico.value				= '';
				document.filtro.filtro_descricao_id_servico.value	= '';
			} else {
				var nameNode = xmlhttp.responseXML.getElementsByTagName("IdServico")[0]; 
				var nameTextNode = nameNode.childNodes[0];
				var IdServico = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoServico")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var DescricaoServico = nameTextNode.nodeValue;
				
				document.filtro.filtro_id_servico.value				= IdServico;
				document.filtro.filtro_descricao_id_servico.value	= DescricaoServico;
				
				if(document.filtro.IdServico != undefined) {
					document.filtro.IdServico.value = "";
				}
			}
		});
	}
	function verificar_data_primeira_cobranca(IdContrato, DataInicioCobranca, Acao){
		if(DataInicioCobranca == '' || DataInicioCobranca == undefined){
			DataInicioCobranca = 0;
		}
		if ( Acao == "alterar"){
			var url = "xml/contrato_vigencia.php?IdContrato="+IdContrato;
		
			call_ajax(url, function (xmlhttp) {
				if(xmlhttp.responseText != 'false'){
					nameNode = xmlhttp.responseXML.getElementsByTagName("DataInicioVigencia")[0]; 
					nameTextNode = nameNode.childNodes[0];
					var DataInicioVigencia = nameTextNode.nodeValue;
					
					var Vigencia = DataInicioVigencia.split("-")[2].toString()+"/"+DataInicioVigencia.split("-")[1].toString()+"/"+DataInicioVigencia.split("-")[0].toString();
					
					var res1 = parseInt(Vigencia.split("/")[2].toString() + Vigencia.split("/")[1].toString() + Vigencia.split("/")[0].toString());
					var res2 = parseInt(DataInicioCobranca.split("/")[2].toString() + DataInicioCobranca.split("/")[1].toString() + DataInicioCobranca.split("/")[0].toString());
					
					document.formulario.AlterarVigencia.value = "0";
					
					if (res2 < res1){
						var alterar_data = confirm( "ATEN��O!\n\n A Data In�cio de Cobran�a do contrato � menor que a Data Inicio da Vig�ncia atual.\nDeseja continuar?","SIM","NAO");
					    if(alterar_data){
							document.formulario.AlterarVigencia.value = "1";	
						}
					}
				}
			});
		}	
	}
	function busca_resumo_conexao(IdContrato){
		if(IdContrato == undefined){
			IdContrato = '';
		}
		
		if(document.formulario.AtivarResumoConexao.value == 1){
			document.getElementById("tbResumoUltimaConexaoAtiva").style.display = "block";
			document.getElementById("tbResumoUltimaConexaoEncerrada").style.display = "block";
			document.getElementById("tbResumoInformacaoGeral").style.display = "block";
			
			ocultar_resumo_conexao();
			busca_ultima_conexao_ativa(IdContrato);
			busca_ultima_conexao_encerrada(IdContrato);
			busca_informacao_geral(IdContrato);
		} else{
			document.getElementById("cp_resumoConexao").style.display = "none";
		}
	}
	function ocultar_resumo_conexao(Ocultar){
		if(Ocultar == undefined){
			Ocultar = false;
		}
		
		resumo_conexao_id = new Array(
			"tbResumoUltimaConexaoAtiva",
			"tbResumoUltimaConexaoEncerrada",
			"tbResumoInformacaoGeral"
		);
		
		if(Ocultar){
			for(var i = 0; i < resumo_conexao_id.length; i++){
				document.getElementById(resumo_conexao_id[i]).style.display = "none";
			}
		} else{
			for(var i = 0; i < resumo_conexao_id.length; i++){
				if(document.getElementById(resumo_conexao_id[i]).style.display == "block"){
					document.getElementById("cp_resumoConexao").style.display = "block";
					return;
				}
			}
		}
		
		document.getElementById("cp_resumoConexao").style.display = "none";
	}
	function busca_ultima_conexao_ativa(IdContrato){
		if(IdContrato == undefined){
			IdContrato = '';
		}
		
		var url = "xml/contrato_ultima_conexao.php?IdContrato="+IdContrato+"&ConexaoAtiva=1";
		
		call_ajax(url,function (xmlhttp){
			if(xmlhttp.responseText != "false"){
				document.getElementById("cp_resumoConexao").style.display = "block";
				document.getElementById("tbResumoUltimaConexaoAtiva").style.display = "block";
				
				while(document.getElementById("tabelaResumoUltimaConexaoAtiva").rows.length > 0){
					document.getElementById("tabelaResumoUltimaConexaoAtiva").deleteRow(0);
				}
				
				var nameNode = xmlhttp.responseXML.getElementsByTagName("DataInicio")[0]; 
				var nameTextNode = nameNode.childNodes[0];
				var DataInicio = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("Upload")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var Upload = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("Download")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var Download = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("NAS")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var NAS = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("IP")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var IP = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("MAC")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var MAC = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("TipoConexao")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var TipoConexao = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("Ativa")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var Ativa = nameTextNode.nodeValue;
				
				if(Ativa == 0){
					document.formulario.bt_derrubar_ultima_conexao_ativa.disabled = true;
					document.getElementById("tabelaResumoUltimaConexaoAtiva").style.width = "100%";
					document.getElementById("tabelaResumoUltimaConexaoAtiva").style.height = "111px";
					tam = document.getElementById("tabelaResumoUltimaConexaoAtiva").rows.length;
					linha = document.getElementById("tabelaResumoUltimaConexaoAtiva").insertRow(tam-1);
					c0 = linha.insertCell(0);
					c0.innerHTML = "<strong>OFF-LINE</strong>";
					c0.style.textAlign = "center";
				} else{
					document.getElementById("tabelaResumoUltimaConexaoAtiva").style.height = "0";
					document.formulario.bt_derrubar_ultima_conexao_ativa.disabled = false;
					var ResumoUltimaConexao = new Array(
						"<strong>NAS: </strong>"+NAS,
						"<strong>Tipo Conex�o: </strong>"+TipoConexao,
						"<strong>IP: </strong>"+IP,
						"<strong>MAC: </strong>"+MAC,
						"<strong>Data In�cio: </strong>"+dateFormat(DataInicio)
					);
					
					for(var i = 0; i < ResumoUltimaConexao.length; i++){
						tam = document.getElementById("tabelaResumoUltimaConexaoAtiva").rows.length;
						linha = document.getElementById("tabelaResumoUltimaConexaoAtiva").insertRow(tam-1);
						c0 = linha.insertCell(0);
						c0.innerHTML = ResumoUltimaConexao[i];
					}
				}
			} else{
				document.getElementById("tbResumoUltimaConexaoAtiva").style.display = "none";
			}
			
			ocultar_resumo_conexao();
		},{id: "carregando_ResumoUltimaConexaoAtiva", id_y: 142});
	}
	function busca_ultima_conexao_encerrada(IdContrato){
		if(IdContrato == undefined){
			IdContrato = '';
		}
		
		var url = "xml/contrato_ultima_conexao.php?IdContrato="+IdContrato+"&ConexaoAtiva=2";
		
		call_ajax(url,function (xmlhttp){
			if(xmlhttp.responseText != "false"){
				document.getElementById("cp_resumoConexao").style.display = "block";
				document.getElementById("tbResumoUltimaConexaoEncerrada").style.display = "block";
				
				while(document.getElementById("tabelaResumoUltimaConexaoEncerrada").rows.length > 0){
					document.getElementById("tabelaResumoUltimaConexaoEncerrada").deleteRow(0);
				}
				
				var nameNode = xmlhttp.responseXML.getElementsByTagName("DataInicio")[0]; 
				var nameTextNode = nameNode.childNodes[0];
				var DataInicio = nameTextNode.nodeValue;
				
				var nameNode = xmlhttp.responseXML.getElementsByTagName("DataTermino")[0]; 
				var nameTextNode = nameNode.childNodes[0];
				var DataTermino = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("Upload")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var Upload = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("Download")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var Download = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("NAS")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var NAS = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("IP")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var IP = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("MAC")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var MAC = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("TipoConexao")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var TipoConexao = nameTextNode.nodeValue;
				
				var ResumoUltimaConexao = new Array(
					"<strong>NAS: </strong>"+NAS,
					"<strong>Tipo Conex�o: </strong>"+TipoConexao,
					"<strong>IP: </strong>"+IP,
					"<strong>MAC: </strong>"+MAC,
					"<strong>Data In�cio: </strong>"+dateFormat(DataInicio),
					"<strong>Data T�rmino: </strong>"+dateFormat(DataTermino),
					"<strong>Down.: </strong>"+Download+"<strong> / Up.: </strong>"+Upload
				);
				
				for(var i = 0; i < ResumoUltimaConexao.length; i++){
					tam = document.getElementById("tabelaResumoUltimaConexaoEncerrada").rows.length;
					linha = document.getElementById("tabelaResumoUltimaConexaoEncerrada").insertRow(tam-1);
					c0 = linha.insertCell(0);
					c0.innerHTML = ResumoUltimaConexao[i];
				}
			} else{
				document.getElementById("tbResumoUltimaConexaoEncerrada").style.display = "none";
			}
			
			ocultar_resumo_conexao();
		},{id: "carregando_ResumoUltimaConexaoEncerrada", id_y: 142});
	}
	function busca_informacao_geral(IdContrato){
		if(IdContrato == undefined){
			IdContrato = '';
		}
		
		var url = "xml/contrato_informacao_conexao.php?IdContrato="+IdContrato+"&Limit=1";
		
		call_ajax(url,function (xmlhttp){
			if(xmlhttp.responseText != "false"){
				document.getElementById("cp_resumoConexao").style.display = "block";
				document.getElementById("tbResumoInformacaoGeral").style.display = "block";
				
				while(document.getElementById("tabelaResumoInformacaoGeral").rows.length > 0){
					document.getElementById("tabelaResumoInformacaoGeral").deleteRow(0);
				}
				
				var nameNode = xmlhttp.responseXML.getElementsByTagName("MesAtual")[0]; 
				var nameTextNode = nameNode.childNodes[0];
				var MesAtual = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("MesAtualUpload")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var MesAtualUpload = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("MesAtualDownload")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var MesAtualDownload = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("MesAnterior")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var MesAnterior = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("MesAnteriorUpload")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var MesAnteriorUpload = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("MesAnteriorDownload")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var MesAnteriorDownload = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("Ultimos6Meses")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var Ultimos6Meses = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("Ultimos6MesesUpload")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var Ultimos6MesesUpload = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("Ultimos6MesesDownload")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var Ultimos6MesesDownload = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("Ultimos12Meses")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var Ultimos12Meses = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("Ultimos12MesesUpload")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var Ultimos12MesesUpload = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("Ultimos12MesesDownload")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var Ultimos12MesesDownload = nameTextNode.nodeValue;
				
				var ResumoUltimaConexao = new Array(
					new Array(
						"<div style='text-align:center;'><strong>Descri��o</strong></div>",
						"<div style='text-align:right;'><strong>Download</strong></div>",
						"<div style='text-align:right;'><strong>Upload</strong></div>"
					),
					new Array(
						"<div style='text-align:center;'>"+MesAtual+"<br />(m�s atual)</div>",
						"<div style='text-align:right;'>"+MesAtualDownload+"</div>",
						"<div style='text-align:right;'>"+MesAtualUpload+"</div>"
					),
					new Array(
						"<div style='text-align:center;'>"+MesAnterior+"<br />(m�s anterior)</div>",
						"<div style='text-align:right;'>"+MesAnteriorDownload+"</div>",
						"<div style='text-align:right;'>"+MesAnteriorUpload+"</div>"
					),
					new Array(
						"<div style='text-align:center;'>"+Ultimos6Meses+"<br />(ult. 6 meses)</div>",
						"<div style='text-align:right;'>"+Ultimos6MesesDownload+"</div>",
						"<div style='text-align:right;'>"+Ultimos6MesesUpload+"</div>"
					),
					new Array(
						"<div style='text-align:center;'>"+Ultimos12Meses+"<br />(ult. 12 meses)</div>",
						"<div style='text-align:right;'>"+Ultimos12MesesDownload+"</div>",
						"<div style='text-align:right;'>"+Ultimos12MesesUpload+"</div>"
					)
				);
				
				var tam = document.getElementById("tabelaResumoInformacaoGeral").rows.length;
				var linha = document.getElementById("tabelaResumoInformacaoGeral").insertRow(tam-1);
				
				for(var y = 0; y < ResumoUltimaConexao.length; y++){
					tam = document.getElementById("tabelaResumoInformacaoGeral").rows.length;
					linha = document.getElementById("tabelaResumoInformacaoGeral").insertRow(tam-1);
					
					tam = ResumoUltimaConexao[y].length; 
					
					for(var x = 0; x < ResumoUltimaConexao[y].length; x++){
						var c = linha.insertCell(x);
						c.innerHTML = ResumoUltimaConexao[y][x];
						c.style.padding = "2px 2px 2px";
						
						if(y != 0){
							c.style.borderTop = "1px solid #a4a4a4";
							
							if(x != (tam-1))
								c.style.borderRight = "1px solid #a4a4a4";
						}
					}
				}
			} else{
				document.getElementById("tbResumoInformacaoGeral").style.display = "none";
			}
			
			ocultar_resumo_conexao();
		},{id: "carregando_ResumoInformacaoGeral", id_y: 142});
	}
	function derrubar_conexao(IdContrato,NomeFuncao,id){
		var url = "xml/contrato_derrubar_conexao.php?IdContrato="+IdContrato;
		
		call_ajax(url,function (xmlhttp){
			setTimeout(NomeFuncao+"("+IdContrato+");",60000);
		},{id: id,sleep: 60000});
	}
	function ocultarQuadroConexao(Campo, Id){
		ocultarQuadro(Campo, Id); 
		busca_resumo_conexao(document.formulario.IdContrato.value);
		scrollWindow('bottom');
	}
	function ocultarQuadroMonitor(Campo, Id){
		ocultarQuadro(Campo, Id); 
		busca_monitor(document.formulario.IdContrato.value);
		scrollWindow('bottom');
	}
