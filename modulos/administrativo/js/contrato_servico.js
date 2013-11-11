jQuery(document).ready(function(){
	
	$j = jQuery.noConflict();
	
	$j("#form").on("submit", function(e){
		var cont = 0;
		var className = "";
		if(validar()){
			mensagens(0);
			$j(".obrig").each(function(index){
				if($j(this).is("input:checkbox")){
					if($j(this).is(":checked") && cont == 0){
						cont++;
						className = "";
					}else if(!$j(this).is(":checked") && cont == 0){
						if(index == 0)
							className = "." + $j(this).attr("class") + ":eq("+index+")";
					}
				}else if($j(this).is("select")){
					if($j(this).attr("class").indexOf("obrig") != -1){
						if($j(this).attr("disabled") != "disabled"){
							if($j(this).find("option:first:selected").val() == 0){
								$j(this).focus();
								mensagens(1);
								e.preventDefault();
								return false;
							}
						}
					}
				}
				
			});
			
			if(className != "" && cont == 0){
				$j(className).focus();
				mensagens(1);
				e.preventDefault();
				return false;
			}
		}else{
			e.preventDefault();
		}
		
	});
});

	var ContExecucao = 0;
	function inicia(){
		status_inicial();
		document.formulario.IdServico.focus();
	}
	function listarParametroAnteriorContrato(IdServico,Erro,IdContrato){
		while(document.getElementById('tabelaParametro').rows.length > 0){
			document.getElementById('tabelaParametro').deleteRow(0);
		}		
	
		var tam, linha, c0;
		
		if(IdServico == ''){
			IdServico = 0;
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
	    url = "xml/servico_parametro.php?IdServico="+IdServico+"&IdStatus=1";

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
						// Fim de Carregando
						document.getElementById('cp_parametrosServico').style.display	=	'none';
						carregando(false);
					}else{
						var espaco, visivel, DescricaoParametroServico, Obrigatorio, ValorDefault, Valor, IdParametroServico, color, salvar;
						
						var obsTemp = new Array(), cont=0;
						for(var i=0; i<xmlhttp.responseXML.getElementsByTagName("IdParametroServico").length; i++){
							nameNode = xmlhttp.responseXML.getElementsByTagName("IdParametroServico")[i]; 
							nameTextNode = nameNode.childNodes[0];
							var IdParametroServico = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoParametroServico")[i]; 
							nameTextNode = nameNode.childNodes[0];
							var DescricaoParametroServico = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("Editavel")[i]; 
							nameTextNode = nameNode.childNodes[0];
							var Editavel = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("Visivel")[i]; 
							nameTextNode = nameNode.childNodes[0];
							var Visivel = nameTextNode.nodeValue;
							
							if(Visivel == 1){
								cont++;
								
								tam 	 = document.getElementById('tabelaParametro').rows.length;
								linha	 = document.getElementById('tabelaParametro').insertRow(tam);
	
								linha.accessKey = IdParametroServico; 
								
								c0	= linha.insertCell(0);
								listarServicoParametroNovo(IdServico,IdParametroServico,linha,c0,IdParametroServico);
								c0.style.padding = "0 0 6px 22px";
								
								c0	= linha.insertCell(1);
								
								if(Editavel == 1){
									c0.innerHTML = "<img id='importValor_"+i+"' src='../../img/estrutura_sistema/ico_seta_left.gif' alt='Importar Valor' style='cursor:pointer;' onClick='importaValor("+IdParametroServico+",this)'>";
								}else{
									c0.innerHTML = "<img id='importValor_"+i+"' src='../../img/estrutura_sistema/ico_seta_left_c.gif' alt='Importar Valor'>";
								}
								c0.style.padding = "0 0 6px 22px";
								
								c0	= linha.insertCell(2);
								c0.valign	=	"top";
								c0.style.padding = "0 0 6px 22px";
								
								inserirParametroAnterior(linha,c0,IdParametroServico,i,DescricaoParametroServico,Editavel);
							}else{
								listarServicoParametroNovo(IdServico,IdParametroServico,linha,c0,IdParametroServico);
							}
						}
						if(cont > 0 ){
							document.getElementById('cp_parametrosServico').style.display	=	'block';
						}
						
						document.formulario.QuantParametros.value = i;
					}
				}	
				// Fim de Carregando
				carregando(false);
				
				if(document.formulario.Local.value == "ContratoServico" || document.formulario.Local.value == "ContratoStatus"){
					scrollWindow('bottom');
				}
			} 
			return true;
		}
		xmlhttp.send(null);
	}
	
	var ArrayValor 	= new  Array();
	ArrayValor[0]	= '';
	
	function inserirParametroAnterior(linha,c0,IdParametroServico,ii,DescricaoParametroServico,Editavel){
		var TabelaAnterior="",Temp,i=0,opc,selecionado;
		TabelaAnterior += "<B>Parâmetro Serviço Anterior</B><BR>";
		TabelaAnterior += "<select id='IdParametroAnterior' name='ParametroAntigo_"+IdParametroServico+"' style='width:346px' onChange=\"atribuiValor("+IdParametroServico+",this.value,document.getElementById('importValor_"+ii+"'),"+Editavel+")\"><option value=''></option>";
		
		desc="";
		if(document.formulario.ParametrosAnterior.value != ''){
			Temp	=	document.formulario.ParametrosAnterior.value.split("§");
			
			while(i < Temp.length){
				opc	=	Temp[i].split("¬");	
				
				if(trim(formata_string(DescricaoParametroServico)) == trim(formata_string(opc[1]))){
					selecionado 	= 'selected=true';
					
					if(opc[2] != ''){
						if(Editavel == 1){
							document.getElementById("importValor_"+ii).src = "../../img/estrutura_sistema/ico_seta_left.gif";
							document.getElementById("importValor_"+ii).style.cursor = "pointer";
						} else{
							document.getElementById("importValor_"+ii).src = "../../img/estrutura_sistema/ico_seta_left_c.gif";
							document.getElementById("importValor_"+ii).style.cursor = "default";
						}
						
						desc = "Valor: "+opc[2];
					} else{
						document.getElementById("importValor_"+ii).src = "../../img/estrutura_sistema/ico_seta_left_c.gif";
						document.getElementById("importValor_"+ii).style.cursor = "default";
						desc = '&nbsp;';
					}
				} else{
					selecionado = '';
				}
				
				TabelaAnterior +=	"<option value='"+opc[2]+"' "+selecionado+">"+opc[1]+"</option>";
				i++;
			}
		}
		
		TabelaAnterior += "</select><BR><b style='font-weight:normal' id='Obs_"+IdParametroServico+"'>"+desc+"</b>";
		
		if(desc == '' || desc == '&nbsp;'){
			document.getElementById("importValor_"+ii).src = "../../img/estrutura_sistema/ico_seta_left_c.gif";
			document.getElementById("importValor_"+ii).style.cursor = "default";
		}
		
		linha.accessKey = IdParametroServico;
		c0.innerHTML =	TabelaAnterior;
					
	}
	function listarServicoParametroNovo(IdServico,IdParametroServico,linha,c0,IdParametroServico){
		if(IdServico == ''){
			IdServico = 0;
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
		
	    url = "xml/servico_parametro.php?IdServico="+IdServico+"&IdParametroServico="+IdParametroServico+"&IdContrato="+document.formulario.IdContrato.value+"&IdStatus=1";

		xmlhttp.open("GET", url,true);
 		xmlhttp.onreadystatechange = function(){ 
	
			// Carregando...
			carregando(true);

			if(xmlhttp.readyState == 4){ 
				if(xmlhttp.status == 200){
					//alert('listarServicoParametroNovo'+xmlhttp.responseText)
					if(xmlhttp.responseText == 'false'){		
						// Fim de Carregando
						document.getElementById('cp_parametrosServico').style.display	=	'none';
						carregando(false);
					}else{
						var visivel, DescricaoParametroServico, Obrigatorio, ValorDefault, Valor, IdParametroServico, color, salvar;
						
						var obsTemp = new Array(), invisivel="",cont=0, temp = 0, tipo="", tamMax="";
						for(var i=0; i<xmlhttp.responseXML.getElementsByTagName("IdParametroServico").length; i++){
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoParametroServico")[i]; 
							nameTextNode = nameNode.childNodes[0];
							var DescricaoParametroServico = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("Obrigatorio")[i]; 
							nameTextNode = nameNode.childNodes[0];
							var Obrigatorio = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("ValorDefault")[i]; 
							nameTextNode = nameNode.childNodes[0];
							var ValorDefault = nameTextNode.nodeValue;	
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("IdParametroServico")[i]; 
							nameTextNode = nameNode.childNodes[0];
							var IdParametroServico = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("Editavel")[i]; 
							nameTextNode = nameNode.childNodes[0];
							var Editavel = nameTextNode.nodeValue;
							
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
							
							//alert(DescricaoParametroServico+" -> "+ValorDefault);
							
							if(Visivel == '1'){
								temp++;
								
								tabindex = 15 + cont + 1;
								
								if(Obrigatorio == 1){
									color = "#C10000";
								}else{
									color = "#000000";
								}
								
								if(Editavel == 1){
									visivel	=	'';
								}else{
									visivel	=	'readOnly';
								}
								
								linha.accessKey = IdParametroServico; 
								
								if(Obs==''){ 
									Obs="<BR>";
								}else{
									Obs="Obs: "+Obs;
								}
								
								
								c0.innerHTML = "<B style='color:"+color+";'>"+DescricaoParametroServico+"</B><BR>";
								
								
								if(TamMaximo!="" && Editavel==1){
									tamMax	=	"maxlength='"+TamMaximo+"'";	
								}else{
									tamMax	=	"";
								}
								
								if(IdTipoParametro == 1){
									switch(IdTipoTexto){
										case '1':
											switch(IdMascaraCampo){
												case '1': //Data
													c0.innerHTML += "<input type='text' name='Valor_"+(IdParametroServico)+"' value='"+ValorDefault+"' style='width:399px;' autocomplete='off' onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out');\" "+visivel+" onkeypress=\"mascara(this,event,'date')\" maxlength='10' tabindex='"+tabindex+"'><input type='hidden' name='Obrigatorio_"+IdParametroServico+"' value='"+Obrigatorio+"'><input type='hidden' name='Visivel_"+IdParametroServico+"' value='"+Visivel+"'><input type='hidden' name='TamMinimo_"+IdParametroServico+"' value='"+TamMinimo+"'><BR>"+Obs;
													break;
												case '2': //Inteiro
													c0.innerHTML += "<input type='text' name='Valor_"+(IdParametroServico)+"' value='"+ValorDefault+"' style='width:399px;' autocomplete='off' onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out');\" "+visivel+" onkeypress=\"mascara(this,event,'int')\" "+tamMax+" tabindex='"+tabindex+"'><input type='hidden' name='Obrigatorio_"+IdParametroServico+"' value='"+Obrigatorio+"'><input type='hidden' name='Visivel_"+IdParametroServico+"' value='"+Visivel+"'><input type='hidden' name='TamMinimo_"+IdParametroServico+"' value='"+TamMinimo+"'><BR>"+Obs;
													break;
												case '3': //Real
													if(Editavel == 1){
														c0.innerHTML += "<input type='text' name='Valor_"+(IdParametroServico)+"' value='"+ValorDefault+"' style='width:399px;' autocomplete='off' onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out');\" "+visivel+" onkeypress=\"mascara(this,event,'float')\" "+tamMax+" tabindex='"+tabindex+"'><input type='hidden' name='Obrigatorio_"+IdParametroServico+"' value='"+Obrigatorio+"'><input type='hidden' name='Visivel_"+IdParametroServico+"' value='"+Visivel+"'><input type='hidden' name='TamMinimo_"+IdParametroServico+"' value='"+TamMinimo+"'><BR>"+Obs;
													}else{
														c0.innerHTML += "<input type='text' name='Valor_"+(IdParametroServico)+"' value='"+ValorDefault+"' style='width:399px;' autocomplete='off' onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out');\" "+visivel+" tabindex='"+tabindex+"'><input type='hidden' name='Obrigatorio_"+IdParametroServico+"' value='"+Obrigatorio+"'><input type='hidden' name='Visivel_"+IdParametroServico+"' value='"+Visivel+"'><input type='hidden' name='TamMinimo_"+IdParametroServico+"' value='"+TamMinimo+"'><BR>"+Obs;
													}
													break;
												case '4': //Usuário
													c0.innerHTML += "<input type='text' name='Valor_"+(IdParametroServico)+"' value='"+ValorDefault+"' style='width:399px;' autocomplete='off' onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out');\" "+visivel+" onkeypress=\"mascara(this,event,'usuario')\" "+tamMax+" tabindex='"+tabindex+"'><input type='hidden' name='Obrigatorio_"+IdParametroServico+"' value='"+Obrigatorio+"'><input type='hidden' name='Visivel_"+IdParametroServico+"' value='"+Visivel+"'><input type='hidden' name='TamMinimo_"+IdParametroServico+"' value='"+TamMinimo+"'><BR>"+Obs;
													break;
												case '5': //MAC
													c0.innerHTML += "<input type='text' name='Valor_"+(IdParametroServico)+"' value='"+ValorDefault+"' style='width:399px;' autocomplete='off' onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out');\" "+visivel+" onkeypress=\"mascara(this,event,'mac')\" maxlength='17' tabindex='"+tabindex+"'><input type='hidden' name='Obrigatorio_"+IdParametroServico+"' value='"+Obrigatorio+"'><input type='hidden' name='Visivel_"+IdParametroServico+"' value='"+Visivel+"'><input type='hidden' name='TamMinimo_"+IdParametroServico+"' value='"+TamMinimo+"'><BR>"+Obs;
													break;
												default:
													c0.innerHTML += invisivel+"<input type='text' name='Valor_"+(IdParametroServico)+"' value='"+ValorDefault+"' style='width:399px;' autocomplete='off' onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out');\" "+visivel+" "+tamMax+" tabindex='"+tabindex+"'><input type='hidden' name='Obrigatorio_"+IdParametroServico+"' value='"+Obrigatorio+"'><input type='hidden' name='Visivel_"+IdParametroServico+"' value='"+Visivel+"'><input type='hidden' name='TamMinimo_"+IdParametroServico+"' value='"+TamMinimo+"'><BR>"+Obs;
											}
											break;
										case '2':
											if(ExibirSenha == 1){
												tipo	=	'text';
											}else{
												tipo	=	'password';
											}	
											c0.innerHTML += invisivel+"<input type='"+tipo+"' name='Valor_"+(IdParametroServico)+"' value='"+ValorDefault+"' style='width:399px;' autocomplete='off' onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out');\" "+visivel+" "+tamMax+" tabindex='"+tabindex+"'><input type='hidden' name='Obrigatorio_"+IdParametroServico+"' value='"+Obrigatorio+"'><input type='hidden' name='Visivel_"+IdParametroServico+"' value='"+Visivel+"'><input type='hidden' name='TamMinimo_"+IdParametroServico+"' value='"+TamMinimo+"'><BR>"+Obs;
											break;
										case '3':
											c0.innerHTML += invisivel+"<input type='text' name='Valor_"+(IdParametroServico)+"' value='"+ValorDefault+"' style='width:399px;' autocomplete='off' onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out');\" "+visivel+" "+tamMax+" tabindex='"+tabindex+"'><input type='hidden' name='Obrigatorio_"+IdParametroServico+"' value='"+Obrigatorio+"'><input type='hidden' name='Visivel_"+IdParametroServico+"' value='"+Visivel+"'><input type='hidden' name='TamMinimo_"+IdParametroServico+"' value='"+TamMinimo+"'><BR>"+Obs;
											break;
										case '4':
											c0.innerHTML += invisivel+"<input type='text' name='Valor_"+(IdParametroServico)+"' value='"+ValorDefault+"' style='width:399px;' autocomplete='off' onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out');\" "+visivel+" "+tamMax+" tabindex='"+tabindex+"'><input type='hidden' name='Obrigatorio_"+IdParametroServico+"' value='"+Obrigatorio+"'><input type='hidden' name='Visivel_"+IdParametroServico+"' value='"+Visivel+"'><input type='hidden' name='TamMinimo_"+IdParametroServico+"' value='"+TamMinimo+"'><BR>"+Obs;
											break;
										case '5':
											c0.innerHTML += invisivel+"<input type='text' name='Valor_"+(IdParametroServico)+"' value='"+ValorDefault+"' style='width:399px;' autocomplete='off' onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out');\" "+visivel+" "+tamMax+" tabindex='"+tabindex+"'><input type='hidden' name='Obrigatorio_"+IdParametroServico+"' value='"+Obrigatorio+"'><input type='hidden' name='Visivel_"+IdParametroServico+"' value='"+Visivel+"'><input type='hidden' name='TamMinimo_"+IdParametroServico+"' value='"+TamMinimo+"'><BR>"+Obs;
											break;
										case '6': //Asterisk
											c0.innerHTML += invisivel+"<input type='text' name='Valor_"+(IdParametroServico)+"' value='"+ValorDefault+"' style='width:399px;' autocomplete='off' onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out');\" "+visivel+" "+tamMax+" tabindex='"+tabindex+"'><input type='hidden' name='Obrigatorio_"+IdParametroServico+"' value='"+Obrigatorio+"'><input type='hidden' name='Visivel_"+IdParametroServico+"' value='"+Visivel+"'><input type='hidden' name='TamMinimo_"+IdParametroServico+"' value='"+TamMinimo+"'><BR>"+Obs;
											break;
									}
								}else{
									campo = "<select name='Valor_"+(IdParametroServico)+"'  style='width:406px;' onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out');\" "+visivel+" tabindex='"+tabindex+"'>";
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
									campo +=	"<input type='hidden' name='Obrigatorio_"+(IdParametroServico)+"' value='"+Obrigatorio+"'><input type='hidden' name='Visivel_"+IdParametroServico+"' value='"+Visivel+"'><input type='hidden' name='TamMinimo_"+IdParametroServico+"' value='"+TamMinimo+"'>";
									campo +=	"<BR>"+Obs;
									
									c0.innerHTML	+=	campo;
								}
								
								cont++;							
							}else{
								invisivel	+=	"<div style='display:none'>";
								if(IdTipoParametro == 1){
									invisivel += "<input type='text' name='Valor_"+IdParametroServico+"' value='"+ValorDefault+"' style='width:399px;' autocomplete='off'><input type='hidden' name='Obrigatorio_"+IdParametroServico+"' value='"+Obrigatorio+"'><input type='hidden' name='Visivel_"+IdParametroServico+"' value='"+Visivel+"'><input type='hidden' name='TamMinimo_"+IdParametroServico+"' value='"+TamMinimo+"'></p>";
								}else{
									campo  = "";
									campo +=	"<select name='Valor_"+IdParametroServico+"'  style='width:406px;'>";
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
									campo +=	"<input type='hidden' name='Obrigatorio_"+IdParametroServico+"' value='"+Obrigatorio+"'><input type='hidden' name='Visivel_"+IdParametroServico+"' value='"+Visivel+"'><input type='hidden' name='TamMinimo_"+IdParametroServico+"' value='"+TamMinimo+"'>";
									
									invisivel	+=	campo;
								}
								
								invisivel	+=	"</div>";
							}
						}
						if(invisivel !=""){
							tam 	 = document.getElementById('tabelaParametro').rows.length;
							linha	 = document.getElementById('tabelaParametro').insertRow(tam);
								
							linha.accessKey = IdParametroServico; 
								
							c0	= linha.insertCell(0);
							c0.innerHTML	=	invisivel;
						}
						
						if(temp > 0){
							document.getElementById('cp_parametrosServico').style.display	=	'block';
						}
					}
				}	
				// Fim de Carregando
				carregando(false);
				
				if(document.formulario.Local.value == "ContratoServico" || document.formulario.Local.value == "ContratoStatus"){
					scrollWindow('bottom');
				}
			} 
			return true;
		}
		xmlhttp.send(null);
	}
	function listarParametroAnterior(IdServico,IdContrato){
		if(IdServico == ''){
			IdServico = 0;
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
	    url = "xml/contrato_automatico_servico_parametro.php?IdServico="+IdServico+"&IdContrato="+IdContrato+"&Visivel=1";

		xmlhttp.open("GET", url,true);
 		xmlhttp.onreadystatechange = function(){ 
	
			// Carregando...
			carregando(true);

			if(xmlhttp.readyState == 4){ 
				if(xmlhttp.status == 200){
					//alert(xmlhttp.responseText);
					if(xmlhttp.responseText == 'false'){		
						// Fim de Carregando
						//document.getElementById('cp_parametrosServico').style.display	=	'none';
						carregando(false);
					}else{
						var DescricaoParametroServico, ValorDefault, Valor, IdParametroServico;
						
						//document.getElementById('cp_parametrosServico').style.display	=	'block';
						
						for(var i=0; i<xmlhttp.responseXML.getElementsByTagName("IdParametroServico").length; i++){
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoParametroServico")[i]; 
							nameTextNode = nameNode.childNodes[0];
							var DescricaoParametroServico = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("IdParametroServico")[i]; 
							nameTextNode = nameNode.childNodes[0];
							var IdParametroServico = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("ValorDefault")[i]; 
							nameTextNode = nameNode.childNodes[0];
							var ValorDefault = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("Valor")[i]; 
							nameTextNode = nameNode.childNodes[0];
							var Valor = nameTextNode.nodeValue;
							
							if(Valor == '' && ValorDefault != ''){
								Valor	=	ValorDefault;
							}
							
							if(document.formulario.ParametrosAnterior.value != ''){
								document.formulario.ParametrosAnterior.value	+= '§';
							}
							document.formulario.ParametrosAnterior.value	+=	IdParametroServico+'¬'+DescricaoParametroServico+'¬'+Valor;	
							
							ArrayValor[i+1]	=	Valor;
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
	function atribuiValor(campo,valor,campoId,Editavel){
		if(valor == '' || valor == 0){
			campoId.src = '../../img/estrutura_sistema/ico_seta_left_c.gif';
			campoId.style.cursor = "default";
		} else{
			if(Editavel == 1){
				campoId.src = '../../img/estrutura_sistema/ico_seta_left.gif';
				campoId.style.cursor = "pointer";
			} else{
				campoId.src = '../../img/estrutura_sistema/ico_seta_left_c.gif';
				campoId.style.cursor = "default";
			}
		}
		
		var posInicial=0,posFinal=0,temp='';
		for(i = 0; i<document.formulario.length; i++){
			if(document.formulario[i].name != undefined){
				if(document.formulario[i].name.substring(0,6) == 'Valor_'){
					if(posInicial == 0){
						posInicial = i;
					}
					posFinal	=	i;
				}
			}
		}
		ValorCampo	=	"";
		
		if(posInicial != 0){
			for(i = posInicial; i<=posFinal; i=i+5){
				if(document.formulario[i].name.substring(0,6) == 'Valor_'){
					temp	=	document.formulario[i].name.split("_");
					if(temp[1] == campo){
						if(valor!=0){
							document.getElementById('Obs_'+campo).innerHTML = 'Valor: '+valor;
						}else{
							document.getElementById('Obs_'+campo).innerHTML = '&nbsp;';
						}
						break;
					}
				}
			}
		}
	}
	function importaValor(IdParametroServico,campo){
		var exit = campo.src.split("/");
		if(exit[exit.length-1] == "ico_seta_left_c.gif"){
			return false;
		}
		
		var posInicial=0,posFinal=0,temp='';
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
		var cont = 0, valor = "";
		if(posInicial != 0){
			for(i = posInicial; i<=posFinal; i=i+5){
				temp	=	document.formulario[i].name.split("_");
				if(temp[1] == IdParametroServico){	
					for(ii=0;ii<document.formulario[i+4].options.length;ii++){
						if(document.formulario[i+4][ii].selected == true){
							valor	=	document.formulario[i+4][ii].value;
							if(document.formulario[i].type != 'select-one'){
								if(valor!=0){
									document.formulario[i].value = valor;
								}else{
									document.formulario[i].value = '';
								}
							}else{
								var aux=0; 
								for(j=0;j<document.formulario[i].length;j++){
									document.formulario[i][j].value = document.formulario[i][j].value.replace("\n", "");
									if(document.formulario[i][j].value == valor){
										document.formulario[i][j].selected = true;
										aux = 	1;
										j	=	document.formulario[i].length;
									}
								}
								if(aux == 0){
									document.formulario[i][0].selected = true; 
								}
							}
						}
					}
					break;
				}
			}
		}
	}
	function calculaPeriodicidadeServico(IdPeriodicidade,valor,campo){
		if(valor != ''){
			if(valor.indexOf(",") != -1){	
				valor = valor.replace('.','');
				valor = valor.replace('.','');
				valor = valor.replace(',','.');
			}
			valor 		  = parseFloat(valor);
			
			var Meses = 1;
			
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
		    
		   	url = "xml/periodicidade.php?IdPeriodicidade="+IdPeriodicidade;
		
			xmlhttp.open("GET", url,true);
			xmlhttp.onreadystatechange = function(){ 
		
				if(xmlhttp.readyState == 4){ 
					if(xmlhttp.status == 200){;
						if(xmlhttp.responseText != 'false'){	
							nameNode = xmlhttp.responseXML.getElementsByTagName("Fator")[0]; 
							nameTextNode = nameNode.childNodes[0];
							Fator = nameTextNode.nodeValue;
						
							campo.value = valor*parseInt(Fator);
							campo.value = formata_float(Arredonda(campo.value,2),2).replace(".",",");
						}
					}
				}
			}
			xmlhttp.send(null);
		}else{
			campo.value = '';
		}
	}
	function verificaAcao(){
		if(document.formulario.IdContrato.value == ''){
			document.formulario.bt_alterar.disabled 	= true;
		}else{
			document.formulario.bt_alterar.disabled 	= false;
		}
	}
	function validar_Data(id,campo){
		if(campo.value == ''){
			document.getElementById(id).style.backgroundColor='#FFFFFF';
			document.getElementById(id).style.color='#C10000';
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
			document.getElementById(id).style.color='#C10000';
			mensagens(0);
			return true;
		}	
	}
	function verificaCredito(IdServico,DataBaseCalculo,DataCancelamento){
		limpaFormCredito();
		if(DataBaseCalculo != '' && DataCancelamento != '' && IdServico!=""){
			var dataBase = formatDate(DataBaseCalculo);
			var dataCanc = formatDate(DataCancelamento);
			if(dataBase > dataCanc){
				while(document.getElementById('tabelaCredito').rows.length > 1){
					document.getElementById('tabelaCredito').deleteRow(1);
				}
				
				addFormCredito(document.formulario.IdContrato.value);
				//listar_conta_receber_aberto(document.formulario.IdContrato.value);
				
				var ServicoAutomatico	=	document.formulario.ServicoAutomaticoAnterior.value;
			
				if(ServicoAutomatico!=""){
					temp	=	ServicoAutomatico.split("#");
					for(i=0;i<temp.length;i++){
						
						if(temp[i].indexOf("¬")!='-1'){
							aux			=	temp[i].split("¬");
							IdContrato	=	aux[1];
						
							addFormCredito(IdContrato,i);
							//listar_conta_receber_aberto(IdContrato);
						}
					}
				}
			}else{
				limpaFormCredito();
			}
		}else{
			limpaFormCredito();
		}
	}
	function addFormCredito(IdContrato,tab){
		var DataCancelamento	=	document.formulario.DataCancelamento.value;
		
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
	    
		url = "xml/contrato_autorizar_credito.php?IdContrato="+IdContrato+"&DataCancelamento="+DataCancelamento;
		xmlhttp.open("GET", url,true);
		xmlhttp.onreadystatechange = function(){ 
	
			// Carregando...
			carregando(true);
	
			if(xmlhttp.readyState == 4){ 
				if(xmlhttp.status == 200){
					if(xmlhttp.responseText == 'false'){
						document.getElementById("cp_credito").style.display 	=	"none";	
					}else{
						var tam, linha, c0, c1, c2, c3, c4, c5, c6, c7, c8, c9, c10, c11, c12, c13, c14, c15, c16, c17;
						
						for(var i=0; i<xmlhttp.responseXML.getElementsByTagName("IdContaReceber").length; i++){				
						
							nameNode = xmlhttp.responseXML.getElementsByTagName("IdServico")[i]; 
							nameTextNode = nameNode.childNodes[0];
							var IdServico = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoServico")[i]; 
							nameTextNode = nameNode.childNodes[0];
							var DescricaoServico = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("Valor")[i]; 
							nameTextNode = nameNode.childNodes[0];
							var Valor = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("DataReferenciaInicial")[i]; 
							nameTextNode = nameNode.childNodes[0];
							var DataReferenciaInicial = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("DataReferenciaFinal")[i]; 
							nameTextNode = nameNode.childNodes[0];
							var DataReferenciaFinal = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("IdContaReceber")[i]; 
							nameTextNode = nameNode.childNodes[0];
							var IdContaReceber = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("IdLancamentoFinanceiro")[i]; 
							nameTextNode = nameNode.childNodes[0];
							var IdLancamentoFinanceiro = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("QtdDias")[i]; 
							nameTextNode = nameNode.childNodes[0];
							var QtdDias = nameTextNode.nodeValue;
							
							if(Valor > 0){

								Valor	=	Arredonda(Valor,2);
								Valor	=	formata_float(Valor,2);										
								Valor	=	Valor.replace('.',',');
													
								var tabindex	=	300 + tab;
								
								document.getElementById("cp_credito").style.display 	=	"block";
																
								tam 	 = document.getElementById('tabelaCredito').rows.length;
								linha	 = document.getElementById('tabelaCredito').insertRow(tam);
								
								linha.accessKey = IdLancamentoFinanceiro; 
														
								c0	= linha.insertCell(0);
								c1	= linha.insertCell(1);
								c2	= linha.insertCell(2);
								c3	= linha.insertCell(3);
								c4	= linha.insertCell(4);
								c5	= linha.insertCell(5);
								c6	= linha.insertCell(6);
								c7	= linha.insertCell(7);
								c8	= linha.insertCell(8);
								c9	= linha.insertCell(9);
								c10	= linha.insertCell(10);
								c11	= linha.insertCell(11);
								c12	= linha.insertCell(12);
								c13	= linha.insertCell(13);
								c14	= linha.insertCell(14);
								c15	= linha.insertCell(15);							
								c16	= linha.insertCell(16);	
								c17	= linha.insertCell(17);	
								
								c0.innerHTML  = "&nbsp;";	
								c1.innerHTML  = "<input type='text' name='IdContaReceber_"+IdLancamentoFinanceiro+"' value='"+IdContaReceber+"' style='width:70px' readOnly>";	
								c2.innerHTML  = "&nbsp;";
								c3.innerHTML  = "<input type='text' name='IdLancamentoFinanceiro_"+IdLancamentoFinanceiro+"' value='"+IdLancamentoFinanceiro+"' style='width:65px' readOnly>";	
								c4.innerHTML  = "&nbsp;";
								c5.innerHTML  = "<input type='text' name='IdContratoCredito_"+IdLancamentoFinanceiro+"' value='"+IdContrato+"' style='width:45px' readOnly>";	
								c6.innerHTML  = "&nbsp;";
								c7.innerHTML  =	"<input type='text' name='IdServicoCredito_"+IdLancamentoFinanceiro+"' value='"+IdServico+"'  style='width:55px' maxlength='11' readOnly><input type='text' class='agrupador' name='DescricaoServicoCredito_"+IdContrato+"' value='"+DescricaoServico+"' style='width:120px' maxlength='100' readOnly>";								
								c8.innerHTML  = "&nbsp;";
								c9.innerHTML  = "<input type='text' name='DataInicioCredito_"+IdLancamentoFinanceiro+"' value='"+DataReferenciaInicial+"' style='width:80px' readOnly>";	
								c10.innerHTML  = "&nbsp;";	
								c11.innerHTML  = "<input type='text' name='DataTerminoCredito_"+IdLancamentoFinanceiro+"' value='"+DataReferenciaFinal+"' style='width:80px' readOnly>";	
								c12.innerHTML  = "&nbsp;";	
								c13.innerHTML  = "<input type='text' name='QuantidadeDias_"+IdLancamentoFinanceiro+"' value='"+QtdDias+"' style='width:50px' readOnly>";	
								c14.innerHTML = "&nbsp;";	
								c15.innerHTML = "<input type='text' name='ValorCredito_"+IdLancamentoFinanceiro+"' value='"+Valor+"' style='width:60px' readOnly>";	
								c16.innerHTML = "&nbsp;";							
								c17.innerHTML = "<select name='Autorizar_"+IdLancamentoFinanceiro+"' onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out')\"  style='width:58px' tabindex='"+tabindex+"'><option value='' selected></option></select>";	
								
								addOptionAutorizar(IdLancamentoFinanceiro);
							}
						}//Fim do for
					}
				}
				// Fim de Carregando
				carregando(false);
			}
			return true; 
		}
		xmlhttp.send(null);
	}
	function limpaFormCredito(){
		document.getElementById("cp_credito").style.display 								=	"none";
//		document.getElementById("cp_contasReceber").style.display 							=	"none";
		document.getElementById("cpLancamentoFinanceiroAguardandoCobranca").style.display 	=	"none";
		
		while(document.getElementById('tabelaCredito').rows.length > 1){
			document.getElementById('tabelaCredito').deleteRow(1);
		}
/*		
		while(document.getElementById('tabelaContasReceber').rows.length > 2){
			document.getElementById('tabelaContasReceber').deleteRow(1);		
		}*/
		
		while(document.getElementById('tabelaLancamentoFinanceiro').rows.length > 2){
			document.getElementById('tabelaLancamentoFinanceiro').deleteRow(1);		
		}
		document.getElementById('cpValorTotal').innerHTML		=	"0,00";		
		document.getElementById('tabelaTotal').innerHTML		=	"Total: 0";	
	}
	function addOptionAutorizar(IdContrato){
		var campo = "";
		for(i=0;i<document.formulario.length;i++){
			if(document.formulario[i].name == 'Autorizar_'+IdContrato){
				campo	=	document.formulario[i];
			}
		}
		if(campo != ""){
			while(campo.options.length > 0){
				campo.options[0] = null;
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
		    
		    url = "xml/parametro_sistema.php?IdGrupoParametroSistema=82";
			
			xmlhttp.open("GET", url,true);
		    
			// Carregando...
			carregando(true);
			
			xmlhttp.onreadystatechange = function(){ 
				if(xmlhttp.readyState == 4){ 
					if(xmlhttp.status == 200){
						if(xmlhttp.responseText != 'false'){		
							addOption(campo,"","0");
							
							var nameNode, nameTextNode, ValorParametroSistema, IdParametroSistema;					
							for(var i=0; i<xmlhttp.responseXML.getElementsByTagName("ValorParametroSistema").length; i++){
								nameNode = xmlhttp.responseXML.getElementsByTagName("IdParametroSistema")[i]; 
								nameTextNode = nameNode.childNodes[0];
								IdParametroSistema = nameTextNode.nodeValue;
								
								nameNode = xmlhttp.responseXML.getElementsByTagName("ValorParametroSistema")[i]; 
								nameTextNode = nameNode.childNodes[0];
								ValorParametroSistema = nameTextNode.nodeValue;
								
								addOption(campo,ValorParametroSistema,IdParametroSistema);
								
							}
							
							campo[0].selected	=	true;			
						}
						// Fim de Carregando
						carregando(false);
						
					}
				}
			}
			xmlhttp.send(null);	
		}
	}
	
	function listar_conta_receber_aberto(IdContrato){
		if(IdContrato == undefined || IdContrato==''){
			IdContrato = 0;
		}
		
		var nameNode, nameTextNode, url, Condicao;
	    
	   	url = "xml/conta_receber.php?Local=ContratoServico&IdContrato="+IdContrato+"&IdStatusAberto=1";
		
		call_ajax(url,function (xmlhttp){
			if(xmlhttp.responseText == 'false'){
				while(document.getElementById('tabelaContaReceber').rows.length > 2){
					document.getElementById('tabelaContaReceber').deleteRow(1);
				}
				
				document.getElementById('cpValorTotal').innerHTML		=	"0,00";		
				document.getElementById('tabelaTotal').innerHTML		=	"Total: 0";	
				
				// Fim de Carregando
				carregando(false);
			}else{
				
				while(document.getElementById('tabelaContaReceber').rows.length > 2){
					document.getElementById('tabelaContaReceber').deleteRow(1);
				}
						
				document.getElementById('cp_contaReceber').style.display = 'block';
				
				var tam, linha, c0, c1, c2, c3, c4, c5, c6, c7, c8;
				var IdLoja, IdContaReceber,Nome,RazaoSocial,NumeroDocumento,NumeroNF,DataLancamento,Valor,DataVencimento;
				var valorParc=0,contador = 0; tabindex = document.formulario.TabIndex.value;
				for(var i=0; i<xmlhttp.responseXML.getElementsByTagName("IdContaReceber").length; i++){	
					nameNode = xmlhttp.responseXML.getElementsByTagName("IdContaReceber")[i]; 
					nameTextNode = nameNode.childNodes[0];
					IdContaReceber = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("Nome")[i]; 
					nameTextNode = nameNode.childNodes[0];
					Nome = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("RazaoSocial")[i]; 
					nameTextNode = nameNode.childNodes[0];
					RazaoSocial = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("NumeroDocumento")[i]; 
					nameTextNode = nameNode.childNodes[0];
					NumeroDocumento = nameTextNode.nodeValue;	
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("NumeroNF")[i]; 
					nameTextNode = nameNode.childNodes[0];
					NumeroNF = nameTextNode.nodeValue;	
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("AbreviacaoNomeLocalCobranca")[i]; 
					nameTextNode = nameNode.childNodes[0];
					AbreviacaoNomeLocalCobranca = nameTextNode.nodeValue;	
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("DataLancamento")[i]; 
					nameTextNode = nameNode.childNodes[0];
					DataLancamento = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("Valor")[i]; 
					nameTextNode = nameNode.childNodes[0];
					Valor = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("DataVencimento")[i]; 
					nameTextNode = nameNode.childNodes[0];
					DataVencimento = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("DataRecebimento")[i]; 
					nameTextNode = nameNode.childNodes[0];
					DataRecebimento = nameTextNode.nodeValue;
					
					if(RazaoSocial != ""){
						Nome	=	RazaoSocial;
					}
					
					tam 	= document.getElementById('tabelaContaReceber').rows.length;
					linha	= document.getElementById('tabelaContaReceber').insertRow(tam-1);
					
					if(tam%2 != 0){
						linha.style.backgroundColor = "#E2E7ED";
					}
					
					linha.accessKey = IdContaReceber; 
					
					c0	= linha.insertCell(0);	
					c1	= linha.insertCell(1);	
					c2	= linha.insertCell(2);	
					c3	= linha.insertCell(3);
					c4	= linha.insertCell(4);
					c5	= linha.insertCell(5);
					c6	= linha.insertCell(6);
					c7	= linha.insertCell(7);
					c8	= linha.insertCell(8);
					c9	= linha.insertCell(9);
					
					valorParc	=	parseFloat(valorParc) + parseFloat(Valor);
					
					linkIni	= "<a href='cadastro_conta_receber.php?IdContaReceber="+IdContaReceber+"' target='_blank'>";	
					linkFim	= "</a>";
					
					c0.innerHTML = "<input style='border:0' class='obrig' type='checkbox' name='cr_"+IdContaReceber+"' onClick='selecionar(this)' onFocus='Foco(this, \"in\")' onBlur='Foco(this,\"out\")' tabindex='"+tabindex+"'>";
					c0.className = "tableListarEspaco";
					
					c1.innerHTML = linkIni + IdContaReceber + linkFim;
					c1.style.padding =	"0 0 0 5px";
					
					c2.innerHTML = linkIni + NumeroDocumento + linkFim;
					c2.style.padding =	"0 0 0 5px";
					
					c3.innerHTML = linkIni + NumeroNF + linkFim;
					
					c4.innerHTML = linkIni + AbreviacaoNomeLocalCobranca + linkFim;
					
					c5.innerHTML = linkIni + dateFormat(DataLancamento) + linkFim;
					
					c6.innerHTML = linkIni + formata_float(Arredonda(Valor,2),2).replace('.',',') + linkFim;
					c6.className = "valor";
					
					c7.innerHTML = linkIni + dateFormat(DataVencimento)+ linkFim ;
					c7.style.textAlign = "center";
					c7.style.padding =	"0 8px 0 0";
					
					c8.innerHTML = linkIni + dateFormat(DataRecebimento) + linkFim;
					
					contador++;
					tabindex++;
				}
					document.getElementById('tabelaTotalValor').innerHTML				=	formata_float(Arredonda(valorParc,2),2).replace('.',',');		
					document.getElementById('tabelaTotal2').innerHTML					=	"Total: "+contador;	
					
			}
			if(document.formulario.Local.value == "ContratoServico" || document.formulario.Local.value == "ContratoStatus"){
				scrollWindow('bottom');
			}
		});
	}
	function listar_lancamento_financeiro_aguardando_cobranca(IdContrato){
		if(IdContrato == undefined || IdContrato==''){
			IdContrato = 0;
		}
		
		var nameNode, nameTextNode, url, Condicao;
		var xmlhttp   = false;
		if (window.XMLHttpRequest) { // Mozilla, Safari,...
	    	xmlhttp = new XMLHttpRequest();
	        if(xmlhttp.overrideMimeType){
	        	xmlhttp.overrideMimeType("text/xml");
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
	    
	   	url = "xml/contrato_status_lancamento_financeiro.php?IdContrato="+IdContrato+"&IdStatus=2,3";
		xmlhttp.open("GET", url,true);

		xmlhttp.onreadystatechange = function(){ 
			// Carregando...
			carregando(true);
			if(xmlhttp.readyState == 4){ 
				if(xmlhttp.status == 200){
					if(xmlhttp.responseText == "false"){
						document.getElementById("cpLancamentoFinanceiroAguardandoCobranca").style.display = "none";
						
						while(document.getElementById("tabelaLancamentoFinanceiro").rows.length > 2){
							document.getElementById("tabelaLancamentoFinanceiro").deleteRow(1);
						}
						
						document.getElementById("cpValorTotalLancamentoFinanceiro").innerHTML	= "0,00";
						document.getElementById("cpDescTotalLancamentoFinanceiro").innerHTML	= "0,00";		
						document.getElementById("tabelaTotalLancamentoFinanceiro").innerHTML	= "Total: 0";	
						
						// Fim de Carregando
						carregando(false);
					}else{
						document.getElementById("cpLancamentoFinanceiroAguardandoCobranca").style.display = "block";
						
						while(document.getElementById("tabelaLancamentoFinanceiro").rows.length > 2){
							document.getElementById("tabelaLancamentoFinanceiro").deleteRow(1);
						}
						
						var tam, linha, c0, c1, c2, c3, c4, c5, c6, c7, c8, c9 ,c10;
						var IdLoja, IdLancamentoFinanceiro, Tipo, Nome, RazaoSocial, IdProcessoFinanceiro, IdContaReceber, Descricao, Codigo, Referencia, Valor, ValorDescontoAConceber, Status;
						var valorParc=0, valorDesc=0;
						
						for(var i=0; i<xmlhttp.responseXML.getElementsByTagName("IdLancamentoFinanceiro").length; i++){	
							nameNode = xmlhttp.responseXML.getElementsByTagName("IdLancamentoFinanceiro")[i]; 
							nameTextNode = nameNode.childNodes[0];
							IdLancamentoFinanceiro = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("Tipo")[i]; 
							nameTextNode = nameNode.childNodes[0];
							Tipo = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("Nome")[i]; 
							nameTextNode = nameNode.childNodes[0];
							Nome = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("RazaoSocial")[i]; 
							nameTextNode = nameNode.childNodes[0];
							RazaoSocial = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("IdProcessoFinanceiro")[i]; 
							nameTextNode = nameNode.childNodes[0];
							IdProcessoFinanceiro = nameTextNode.nodeValue;	
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("IdContaReceber")[i]; 
							nameTextNode = nameNode.childNodes[0];
							IdContaReceber = nameTextNode.nodeValue;	
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("Descricao")[i]; 
							nameTextNode = nameNode.childNodes[0];
							Descricao = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("Codigo")[i]; 
							nameTextNode = nameNode.childNodes[0];
							Codigo = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("Referencia")[i]; 
							nameTextNode = nameNode.childNodes[0];
							Referencia = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("Valor")[i]; 
							nameTextNode = nameNode.childNodes[0];
							Valor = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("ValorDescontoAConceber")[i]; 
							nameTextNode = nameNode.childNodes[0];
							ValorDescontoAConceber = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("Status")[i]; 
							nameTextNode = nameNode.childNodes[0];
							Status = nameTextNode.nodeValue;
							
							if(RazaoSocial != ''){
								Nome	=	RazaoSocial;
							}
							
							tam 	= document.getElementById("tabelaLancamentoFinanceiro").rows.length;
							linha	= document.getElementById("tabelaLancamentoFinanceiro").insertRow(tam-1);
							
							if(tam%2 != 0){
								linha.style.backgroundColor = "#E2E7ED";
							}
							
							linha.accessKey = IdLancamentoFinanceiro; 
							
							c0	= linha.insertCell(0);	
							c1	= linha.insertCell(1);	
							c2	= linha.insertCell(2);	
							c3	= linha.insertCell(3);
							c4	= linha.insertCell(4);
							c5	= linha.insertCell(5);
							c6	= linha.insertCell(6);
							c7	= linha.insertCell(7);
							c8	= linha.insertCell(8);
							c9	= linha.insertCell(9);
							c10	= linha.insertCell(10);
							
							valorParc	=	parseFloat(valorParc) + parseFloat(Valor);
							valorDesc	=	parseFloat(valorDesc) + parseFloat(ValorDescontoAConceber);
							
							linkIni	= "<a href='cadastro_lancamento_financeiro.php?IdLancamentoFinanceiro="+IdLancamentoFinanceiro+"' target='_blank'>";	
							linkFim	= "</a>";
							
							c0.innerHTML = linkIni + IdLancamentoFinanceiro + linkFim;
							c0.style.padding = "0 4px 0 5px";
							
							c1.innerHTML = linkIni + Tipo + linkFim;
							c1.style.padding = "0 4px 0 0";
							
							c2.innerHTML = linkIni + Codigo + linkFim;
							c2.style.padding = "0 4px 0 0";
							
							c3.innerHTML = linkIni + Nome + linkFim;
							c3.style.padding = "0 4px 0 0";
							
							c4.innerHTML = linkIni + Descricao + linkFim;
							c4.style.padding = "0 4px 0 0";
							
							c5.innerHTML = linkIni + IdProcessoFinanceiro + linkFim;
							c5.style.padding = "0 4px 0 0";
							
							c6.innerHTML = linkIni + Referencia + linkFim;
							c6.style.padding = "0 4px 0 0";
							
							c7.innerHTML = linkIni + formata_float(Arredonda(Valor,2),2).replace('.',',')+ linkFim;
							c7.style.textAlign = "right";
							c7.style.padding = "0 8px 0 0";
							
							c8.innerHTML = linkIni + formata_float(Arredonda(ValorDescontoAConceber,2),2).replace('.',',')+ linkFim ;
							c8.style.textAlign = "right";
							c8.style.padding = "0 8px 0 0";
							
							c9.innerHTML = linkIni + Status + linkFim;
							
							c10.innerHTML = "<img src='../../img/estrutura_sistema/ico_del_c.gif' alt='Excluir?'\">";
						}
						
						document.getElementById("cpValorTotalLancamentoFinanceiro").innerHTML	= formata_float(Arredonda(valorParc,2),2).replace('.',',');
						document.getElementById("cpDescTotalLancamentoFinanceiro").innerHTML	= formata_float(Arredonda(valorDesc,2),2).replace('.',',');
						document.getElementById("tabelaTotalLancamentoFinanceiro").innerHTML	= "Total: "+i;
					}
				}
				// Fim de Carregando
				carregando(false);
				
				if(document.formulario.Local.value == "ContratoServico" || document.formulario.Local.value == "ContratoStatus"){
					scrollWindow('bottom');
				}
			} 
			return true;
		}
		xmlhttp.send(null);
	}
	function listar_carteira_anterior(IdAgenteAutorizado,IdCarteiraTemp){
		if(IdAgenteAutorizado == ''){
			while(document.formulario.IdCarteiraAnterior.options.length > 0){
				document.formulario.IdCarteiraAnterior.options[0] = null;
			}
			return false;
		}
		if(IdCarteiraTemp == undefined){
			IdCarteiraTemp = '';
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

		url = "xml/carteira.php?IdAgenteAutorizado="+IdAgenteAutorizado;
		xmlhttp.open("GET", url,true);

		xmlhttp.onreadystatechange = function(){ 

			// Carregando...
			carregando(true);

			if(xmlhttp.readyState == 4){ 
				if(xmlhttp.status == 200){
					if(xmlhttp.responseText == 'false'){
						while(document.formulario.IdCarteiraAnterior.options.length > 0){
							document.formulario.IdCarteiraAnterior.options[0] = null;
						}
					}else{
						while(document.formulario.IdCarteiraAnterior.options.length > 0){
							document.formulario.IdCarteiraAnterior.options[0] = null;
						}
						addOption(document.formulario.IdCarteiraAnterior,"","0");
						
						for(var i=0; i<xmlhttp.responseXML.getElementsByTagName("IdCarteira").length; i++){
							nameNode = xmlhttp.responseXML.getElementsByTagName("IdCarteira")[i]; 
							nameTextNode = nameNode.childNodes[0];
							IdCarteira = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("Nome")[i]; 
							nameTextNode = nameNode.childNodes[0];
							Nome = nameTextNode.nodeValue;
							
							addOption(document.formulario.IdCarteiraAnterior,Nome,IdCarteira);
						}
						if(IdCarteiraTemp!=''){
							for(ii=0;ii<document.formulario.IdCarteiraAnterior.length;ii++){
								if(document.formulario.IdCarteiraAnterior[ii].value == IdCarteiraTemp){
									document.formulario.IdCarteiraAnterior[ii].selected = true;
									break;
								}
							}
						}else{
							document.formulario.IdCarteiraAnterior[0].selected = true;
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
	
	function listarServicoParametroAnteriorAutomatico(IdServico,tabindex,IdContrato){
		while(document.getElementById("tabelaParametro_"+IdServico+"").rows.length > 0){
			document.getElementById("tabelaParametro_"+IdServico+"").deleteRow(0);
		}		
	
		var tam, linha, c0;
		
		if(IdServico == ''){
			IdServico = 0;
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
	    url = "xml/servico_parametro.php?IdServico="+IdServico+"&IdStatus=1";

		xmlhttp.open("GET", url,true);
 		xmlhttp.onreadystatechange = function(){ 
	
			// Carregando...
			carregando(true);

			if(xmlhttp.readyState == 4){ 
				if(xmlhttp.status == 200){
	
					if(xmlhttp.responseText == 'false'){		
						// Fim de Carregando
						document.getElementById("tabelaParametro_"+IdServico+"").style.display	=	'none';
						carregando(false);
					}else{
						var espaco, visivel, DescricaoParametroServico, Obrigatorio, ValorDefault, Valor, IdParametroServico, color, salvar;
						
						var obsTemp = new Array(), cont=0;
						document.getElementById("tabelaParametro_"+IdServico+"").style.display	=	'block';
						for(var i=0; i<xmlhttp.responseXML.getElementsByTagName("IdParametroServico").length; i++){
							nameNode = xmlhttp.responseXML.getElementsByTagName("IdParametroServico")[i]; 
							nameTextNode = nameNode.childNodes[0];
							var IdParametroServico = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoParametroServico")[i]; 
							nameTextNode = nameNode.childNodes[0];
							var DescricaoParametroServico = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("Editavel")[i]; 
							nameTextNode = nameNode.childNodes[0];
							var Editavel = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("Visivel")[i]; 
							nameTextNode = nameNode.childNodes[0];
							var Visivel = nameTextNode.nodeValue;
							
							if(Visivel == 1){
								cont++;
								
								tam 	 = document.getElementById("tabelaParametro_"+IdServico+"").rows.length;
								linha	 = document.getElementById("tabelaParametro_"+IdServico+"").insertRow(tam);
	
								linha.accessKey = IdParametroServico; 
								
								c0	= linha.insertCell(0);
								listarServicoParametroAutomaticoNovo(IdServico,IdParametroServico,linha,c0,IdParametroServico);
								c0.style.padding = "0 0 6px 22px";
								
								c0	= linha.insertCell(1);
								
								if(Editavel == 1){
									c0.innerHTML = "<img id='importValorAutomatico_"+i+"' src='../../img/estrutura_sistema/ico_seta_left.gif' alt='Importar Valor' style='cursor:pointer;' onClick='importaValorAutomatico("+IdServico+","+IdParametroServico+")'>";
								}else{
									c0.innerHTML = "<img id='importValorAutomatico_"+i+"' src='../../img/estrutura_sistema/ico_seta_left_c.gif' alt='Importar Valor'>";
								}
								c0.style.padding = "0 0 6px 22px";
								
								c0	= linha.insertCell(2);
								c0.vAlign	=	"top";
								c0.style.padding = "0 0 6px 22px";
								
								inserirParametroAutomaticoAnterior(linha,c0,IdParametroServico,i,DescricaoParametroServico,IdServico,Editavel);
							}else{
								listarServicoParametroAutomaticoNovo(IdServico,IdParametroServico,linha,c0,IdParametroServico);
							}
						}
						if(cont > 0 ){
							document.getElementById("tabelaParametro_"+IdServico+"").style.display	=	'block';
						}
					}
				}	
				// Fim de Carregando
				carregando(false);
				
				if(document.formulario.Local.value == "ContratoServico" || document.formulario.Local.value == "ContratoStatus"){
					scrollWindow('bottom');
				}
			} 
			return true;
		}
		xmlhttp.send(null);
	}
	function listarServicoParametroAutomaticoNovo(IdServico,IdParametroServico,linha,c0,IdParametroServico){
		if(IdServico == ''){
			IdServico = 0;
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
	    url = "xml/servico_parametro.php?IdServico="+IdServico+"&IdParametroServico="+IdParametroServico+"&IdStatus=1";

		xmlhttp.open("GET", url,true);
 		xmlhttp.onreadystatechange = function(){ 
	
			// Carregando...
			carregando(true);

			if(xmlhttp.readyState == 4){ 
				if(xmlhttp.status == 200){
					if(xmlhttp.responseText == 'false'){		
						// Fim de Carregando
						document.getElementById("tabelaParametro_"+IdServico+"").style.display	=	'none';
						carregando(false);
					}else{
						var visivel, DescricaoParametroServico, Obrigatorio, ValorDefault, Valor, IdParametroServico, color, salvar, tamMax, tipo;
						
						var obsTemp = new Array(), invisivel="",cont=0, temp = 0;
						var tabindex=30;
						for(var i=0; i<xmlhttp.responseXML.getElementsByTagName("IdParametroServico").length; i++){
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoParametroServico")[i]; 
							nameTextNode = nameNode.childNodes[0];
							var DescricaoParametroServico = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("Obrigatorio")[i]; 
							nameTextNode = nameNode.childNodes[0];
							var Obrigatorio = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("ValorDefault")[i]; 
							nameTextNode = nameNode.childNodes[0];
							var ValorDefault = nameTextNode.nodeValue;	
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("IdParametroServico")[i]; 
							nameTextNode = nameNode.childNodes[0];
							var IdParametroServico = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("Editavel")[i]; 
							nameTextNode = nameNode.childNodes[0];
							var Editavel = nameTextNode.nodeValue;
							
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
							
							if(Visivel == '1'){
								temp++;
								
								tabindex = 200 + cont + 1;
								
								if(Obrigatorio == 1){
									color = "#C10000";
								}else{
									color = "#000000";
								}
								
								if(Editavel == 1){
									visivel	=	'';
								}else{
									visivel	=	'readOnly';
								}
								
								linha.accessKey = IdParametroServico; 
								
								if(Obs==''){ 
									Obs="<BR>";
								}else{
									Obs="Obs: "+Obs;
								}
								
								if(TamMaximo!="" && Editavel==1){
									tamMax	=	"maxlength='"+TamMaximo+"'";
								}else{
									tamMax	=	"";
								}
								
								c0.innerHTML = "<B style='color:"+color+";'>"+DescricaoParametroServico+"</B><BR>";
								
								if(IdTipoParametro == 1){
									switch(IdTipoTexto){
										case '1':
											switch(IdMascaraCampo){
												case '1': //Data
													c0.innerHTML += "<input type='text' name='ValorAutomatico_"+IdServico+"_"+(IdParametroServico)+"' value='"+ValorDefault+"' style='width:399px;' autocomplete='off' onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out');\" "+visivel+" onkeypress=\"mascara(this,event,'date')\" maxlength='10' tabindex='"+tabindex+"'><input type='hidden' name='ObrigatorioAutomatico_"+IdServico+"_"+IdParametroServico+"' value='"+Obrigatorio+"'><input type='hidden' name='VisivelAutomatico_"+IdServico+"_"+IdParametroServico+"' value='"+Visivel+"'><input type='hidden' name='TamMinimoAutomatico_"+IdServico+"_"+IdParametroServico+"' value='"+TamMinimo+"'><BR>"+Obs;
													break;
												case '2': //Inteiro
													c0.innerHTML += "<input type='text' name='ValorAutomatico_"+IdServico+"_"+(IdParametroServico)+"' value='"+ValorDefault+"' style='width:399px;' autocomplete='off' onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out');\" "+visivel+" onkeypress=\"mascara(this,event,'int')\" "+tamMax+" tabindex='"+tabindex+"'><input type='hidden' name='ObrigatorioAutomatico_"+IdServico+"_"+IdParametroServico+"' value='"+Obrigatorio+"'><input type='hidden' name='VisivelAutomatico_"+IdServico+"_"+IdParametroServico+"' value='"+Visivel+"'><input type='hidden' name='TamMinimoAutomatico_"+IdServico+"_"+IdParametroServico+"' value='"+TamMinimo+"'><BR>"+Obs;
													break;
												case '3': //Real
													if(Editavel == 1){
														c0.innerHTML += "<input type='text' name='ValorAutomatico_"+IdServico+"_"+(IdParametroServico)+"' value='"+ValorDefault+"' style='width:399px;' autocomplete='off' onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out');\" "+visivel+" onkeypress=\"mascara(this,event,'float')\" "+tamMax+" tabindex='"+tabindex+"'><input type='hidden' name='ObrigatorioAutomatico_"+IdServico+"_"+IdParametroServico+"' value='"+Obrigatorio+"'><input type='hidden' name='VisivelAutomatico_"+IdServico+"_"+IdParametroServico+"' value='"+Visivel+"'><input type='hidden' name='TamMinimoAutomatico_"+IdServico+"_"+IdParametroServico+"' value='"+TamMinimo+"'><BR>"+Obs;
													}else{
														c0.innerHTML += "<input type='text' name='ValorAutomatico_"+IdServico+"_"+(IdParametroServico)+"' value='"+ValorDefault+"' style='width:399px;' autocomplete='off' onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out');\" "+visivel+" tabindex='"+tabindex+"'><input type='hidden' name='ObrigatorioAutomatico_"+IdServico+"_"+IdParametroServico+"' value='"+Obrigatorio+"'><input type='hidden' name='VisivelAutomatico_"+IdServico+"_"+IdParametroServico+"' value='"+Visivel+"'><input type='hidden' name='TamMinimoAutomatico_"+IdServico+"_"+IdParametroServico+"' value='"+TamMinimo+"'><BR>"+Obs;
													}
													break;
												case '4': //Usuário
													c0.innerHTML += "<input type='text' name='ValorAutomatico_"+IdServico+"_"+(IdParametroServico)+"' value='"+ValorDefault+"' style='width:399px;' autocomplete='off' onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out');\" "+visivel+" onkeypress=\"mascara(this,event,'usuario')\" "+tamMax+" tabindex="+tabindex+"><input type='hidden' name='ObrigatorioAutomatico_"+IdServico+"_"+IdParametroServico+"' value='"+Obrigatorio+"'><input type='hidden' name='VisivelAutomatico_"+IdServico+"_"+IdParametroServico+"' value='"+Visivel+"'><input type='hidden' name='TamMinimoAutomatico_"+IdServico+"_"+IdParametroServico+"' value='"+TamMinimo+"'><BR>"+Obs;
													break;
												case '5': //MAC
													c0.innerHTML += "<input type='text' name='ValorAutomatico_"+IdServico+"_"+(IdParametroServico)+"' value='"+ValorDefault+"' style='width:399px;' autocomplete='off' onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out');\" "+visivel+" onkeypress=\"mascara(this,event,'mac')\" maxlength='17' tabindex="+tabindex+"><input type='hidden' name='ObrigatorioAutomatico_"+IdServico+"_"+IdParametroServico+"' value='"+Obrigatorio+"'><input type='hidden' name='VisivelAutomatico_"+IdServico+"_"+IdParametroServico+"' value='"+Visivel+"'><input type='hidden' name='TamMinimoAutomatico_"+IdServico+"_"+IdParametroServico+"' value='"+TamMinimo+"'><BR>"+Obs;
													break;
												default:
													c0.innerHTML += invisivel+"<input type='text' name='ValorAutomatico_"+IdServico+"_"+(IdParametroServico)+"' value='"+ValorDefault+"' style='width:399px;' autocomplete='off' onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out');\" "+visivel+" "+tamMax+" tabindex="+tabindex+"><input type='hidden' name='ObrigatorioAutomatico_"+IdServico+"_"+IdParametroServico+"' value='"+Obrigatorio+"'><input type='hidden' name='VisivelAutomatico_"+IdServico+"_"+IdParametroServico+"' value='"+Visivel+"'><input type='hidden' name='TamMinimoAutomatico_"+IdServico+"_"+IdParametroServico+"' value='"+TamMinimo+"'><BR>"+Obs;
											}
											break;
										case '2':
											if(ExibirSenha == 2){
												tipo	=	"password";
											}else{
												tipo	=	"text";
											}
											
											c0.innerHTML += invisivel+"<input type='"+tipo+"' name='ValorAutomatico_"+IdServico+"_"+(IdParametroServico)+"' value='"+ValorDefault+"' style='width:399px;' autocomplete='off' onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out');\" "+visivel+" "+tamMax+" tabindex='"+tabindex+"'><input type='hidden' name='ObrigatorioAutomatico_"+IdServico+"_"+IdParametroServico+"' value='"+Obrigatorio+"'><input type='hidden' name='VisivelAutomatico_"+IdServico+"_"+IdParametroServico+"' value='"+Visivel+"'><input type='hidden' name='TamMinimoAutomatico_"+IdServico+"_"+IdParametroServico+"' value='"+TamMinimo+"'><BR>"+Obs;
											break;
										case '3': //GRUPO RADIUS
											c0.innerHTML += invisivel+"<input type='text' name='ValorAutomatico_"+IdServico+"_"+(IdParametroServico)+"' value='"+ValorDefault+"' style='width:399px;' autocomplete='off' onFocus=\"Foco(this,'in');\" onBlur=\"Foco(this,'out');\" "+visivel+" "+tamMax+" tabindex='"+tabindex+"'><input type='hidden' name='ObrigatorioAutomatico_"+IdServico+"_"+IdParametroServico+"' value='"+Obrigatorio+"'><input type='hidden' name='VisivelAutomatico_"+IdServico+"_"+IdParametroServico+"' value='"+Visivel+"'><input type='hidden' name='TamMinimoAutomatico_"+IdServico+"_"+IdParametroServico+"' value='"+TamMinimo+"'><BR>"+Obs;
											break;
										case '4': //IPv4
											c0.innerHTML += invisivel+"<input type='text' name='ValorAutomatico_"+IdServico+"_"+(IdParametroServico)+"' value='"+ValorDefault+"' style='width:399px;' autocomplete='off' onFocus=\"Foco(this,'in');\" onBlur=\"Foco(this,'out');\" "+visivel+" "+tamMax+" tabindex='"+tabindex+"'><input type='hidden' name='ObrigatorioAutomatico_"+IdServico+"_"+IdParametroServico+"' value='"+Obrigatorio+"'><input type='hidden' name='VisivelAutomatico_"+IdServico+"_"+IdParametroServico+"' value='"+Visivel+"'><input type='hidden' name='TamMinimoAutomatico_"+IdServico+"_"+IdParametroServico+"' value='"+TamMinimo+"'><BR>"+Obs;
											break;
										case '5': //IPv6
											c0.innerHTML += invisivel+"<input type='text' name='ValorAutomatico_"+IdServico+"_"+(IdParametroServico)+"' value='"+ValorDefault+"' style='width:399px;' autocomplete='off' onFocus=\"Foco(this,'in');\" onBlur=\"Foco(this,'out');\" "+visivel+" "+tamMax+" tabindex='"+tabindex+"'><input type='hidden' name='ObrigatorioAutomatico_"+IdServico+"_"+IdParametroServico+"' value='"+Obrigatorio+"'><input type='hidden' name='VisivelAutomatico_"+IdServico+"_"+IdParametroServico+"' value='"+Visivel+"'><input type='hidden' name='TamMinimoAutomatico_"+IdServico+"_"+IdParametroServico+"' value='"+TamMinimo+"'><BR>"+Obs;
											break;
										case '6': //Asterisk
											c0.innerHTML += invisivel+"<input type='text' name='ValorAutomatico_"+IdServico+"_"+(IdParametroServico)+"' value='"+ValorDefault+"' style='width:399px;' autocomplete='off' onFocus=\"Foco(this,'in');\" onBlur=\"Foco(this,'out');\" "+visivel+" "+tamMax+" tabindex='"+tabindex+"'><input type='hidden' name='ObrigatorioAutomatico_"+IdServico+"_"+IdParametroServico+"' value='"+Obrigatorio+"'><input type='hidden' name='VisivelAutomatico_"+IdServico+"_"+IdParametroServico+"' value='"+Visivel+"'><input type='hidden' name='TamMinimoAutomatico_"+IdServico+"_"+IdParametroServico+"' value='"+TamMinimo+"'><BR>"+Obs;
											break;
									}
								}else{
									campo = "<select name='ValorAutomatico_"+IdServico+"_"+(IdParametroServico)+"'  style='width:406px;' onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out');\" "+visivel+" tabindex='"+tabindex+"'>";
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
									campo +=	"<input type='hidden' name='ObrigatorioAutomatico_"+IdServico+"_"+(IdParametroServico)+"' value='"+Obrigatorio+"'><input type='hidden' name='VisivelAutomatico_"+IdServico+"_"+IdParametroServico+"' value='"+Visivel+"'><input type='hidden' name='TamMinimoAutomatico_"+IdServico+"_"+IdParametroServico+"' value='"+TamMinimo+"'>";
									campo +=	"<BR>"+Obs;
									
									c0.innerHTML	+=	campo;
								}
								
								cont++;							
							}else{
								invisivel	+=	"<div style='display:none'>";
								if(IdTipoParametro == 1){
									invisivel += "<input type='text' name='ValorAutomatico_"+IdServico+"_"+IdParametroServico+"' value='"+ValorDefault+"' style='width:399px;' autocomplete='off'><input type='hidden' name='ObrigatorioAutomatico_"+IdServico+"_"+IdParametroServico+"' value='"+Obrigatorio+"'><input type='hidden' name='VisivelAutomatico_"+IdServico+"_"+IdParametroServico+"' value='"+Visivel+"'><input type='hidden' name='TamMinimoAutomatico_"+IdServico+"_"+IdParametroServico+"' value='"+TamMinimo+"'></p>";
								}else{
									campo  = "";
									campo +=	"<select name='ValorAutomatico_"+IdServico+"_"+IdParametroServico+"'  style='width:406px;'>";
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
									campo +=	"<input type='hidden' name='ObrigatorioAutomatico_"+IdServico+"_"+IdParametroServico+"' value='"+Obrigatorio+"'><input type='hidden' name='VisivelAutomatico_"+IdServico+"_"+IdParametroServico+"' value='"+Visivel+"'><input type='hidden' name='TamMinimoAutomatico_"+IdServico+"_"+IdParametroServico+"' value='"+TamMinimo+"'>";
									
									invisivel	+=	campo;
								}
								
								invisivel	+=	"</div>";
							}
						}
						if(invisivel !=""){
							tam 	 = document.getElementById("tabelaParametro_"+IdServico+"").rows.length;
							linha	 = document.getElementById("tabelaParametro_"+IdServico+"").insertRow(tam);
								
							linha.accessKey = IdParametroServico; 
								
							c0	= linha.insertCell(0);
							c0.innerHTML	=	invisivel;
						}
						
						if(temp > 0){
							document.getElementById("tabelaParametro_"+IdServico+"").style.display	=	'block';
						}
					}
				}	
				// Fim de Carregando
				carregando(false);
				
				if(document.formulario.Local.value == "ContratoServico" || document.formulario.Local.value == "ContratoStatus"){
					scrollWindow('bottom');
				}
			} 
			return true;
		}
		xmlhttp.send(null);
	}
	function inserirParametroAutomaticoAnterior(linha,c0,IdParametroServico,ii,DescricaoParametroServico,IdServico,Editavel){
		var TabelaAnterior="",Temp,i=0,opc,selecionado;
		TabelaAnterior += "<B>Parâmetro Serviço Anterior</B><BR>";
		TabelaAnterior += "<select id='IdParametroAnterior' name='ParametroAntigoAutomatico_"+IdServico+"_"+IdParametroServico+"' style='width:345px' onChange='atribuiValorAutomatico("+IdServico+","+IdParametroServico+",this.value)'><option value=''></option>";
		
		desc="";
		if(document.formulario.ParametrosAnterior.value != ''){
			Temp	=	document.formulario.ParametrosAnterior.value.split("§");
			
			while(i < Temp.length){
				opc	=	Temp[i].split("¬");	
				
				if(trim(formata_string(DescricaoParametroServico)) == trim(formata_string(opc[1]))){
					selecionado 	= 'selected=true';
					
					if(opc[2] != ''){
						if(Editavel == 1){
							document.getElementById("importValorAutomatico_"+ii).src = "../../img/estrutura_sistema/ico_seta_left.gif";
							document.getElementById("importValorAutomatico_"+ii).style.cursor = "pointer";
						} else{
							document.getElementById("importValorAutomatico_"+ii).src = "../../img/estrutura_sistema/ico_seta_left_c.gif";
							document.getElementById("importValorAutomatico_"+ii).style.cursor = "default";
						}
						
						desc = "Valor: "+opc[2];
					} else{
						document.getElementById("importValorAutomatico_"+ii).src = "../../img/estrutura_sistema/ico_seta_left_c.gif";
						document.getElementById("importValorAutomatico_"+ii).style.cursor = "default";
						desc = '&nbsp;';
					}
				}else{
					selecionado 	= '';
				}
				
				TabelaAnterior +=	"<option value='"+opc[2]+"' "+selecionado+">"+opc[1]+"</option>";
				i++;
			}
		}
		
		TabelaAnterior += "</select><BR><b style='font-weight:normal' id='ObsAutomatico_"+IdServico+"_"+IdParametroServico+"'>"+desc+"</b>";
		
		linha.accessKey = IdParametroServico; 
		
		c0.innerHTML =	TabelaAnterior;
					
	}
	function atribuiValorAutomatico(IdServico,campo,valor){
		var posInicial=0,posFinal=0,temp='';
		for(i = 0; i<document.formulario.length; i++){
			if(document.formulario[i].name != undefined){
				if(document.formulario[i].name.substring(0,16) == 'ValorAutomatico_'){
					if(posInicial == 0){
						posInicial = i;
					}
					posFinal	=	i;
				}
			}
		}
		ValorCampo	=	"";
		
		if(posInicial != 0){
			for(i = posInicial; i<=posFinal; i=i+5){
				//alert(document.formulario[i].name+" = "+document.formulario[i+1].name+" = "+document.formulario[i+2].name+" = "+document.formulario[i+3].name);
				if(document.formulario[i].name.substring(0,16) == 'ValorAutomatico_'){
					temp	=	document.formulario[i].name.split("_");
					if(temp[1] == IdServico && temp[2] == campo){
						if(valor!=0){
							document.getElementById('ObsAutomatico_'+IdServico+"_"+campo).innerHTML = 'Valor: '+valor;
						}else{
							document.getElementById('ObsAutomatico_'+IdServico+"_"+campo).innerHTML = '';
						}
						break;
					}
				}
			}
		}
	}
	function importaValorAutomatico(IdServico,IdParametroServico){
		var posInicial=0,posFinal=0,temp='';
		for(i = 0; i<document.formulario.length; i++){
			if(document.formulario[i].name != undefined){
				if(document.formulario[i].name.substring(0,16) == 'ValorAutomatico_'){
					if(posInicial == 0){
						posInicial = i;
					}
					posFinal = i;
				}
			}
		}
		var cont = 0, valor = "";
		if(posInicial != 0){
			for(i = posInicial; i<=posFinal; i=i+5){
				//alert(document.formulario[i].name+" = "+document.formulario[i+1].name+" = "+document.formulario[i+2].name+" = "+document.formulario[i+3].name);
				temp	=	document.formulario[i].name.split("_");
				if(temp[1] == IdServico && temp[2] == IdParametroServico){	
					for(ii=0;ii<document.formulario[i+4].options.length;ii++){
						//alert(document.formulario[i+4][ii].value);
						if(document.formulario[i+4][ii].selected == true){
							valor	=	document.formulario[i+4][ii].value;
							if(document.formulario[i].type != 'select-one'){
								if(valor!=0){
									document.formulario[i].value = valor;
								}else{
									document.formulario[i].value = '';
								}
							}else{
								var aux=0; 
								for(j=0;j<document.formulario[i].length;j++){
									if(document.formulario[i][j].value == valor){
										document.formulario[i][j].selected = true;
										aux = 	1;
										j	=	document.formulario[i].length;
									}
								}
								if(aux == 0){
									document.formulario[i][0].selected = true; 
								}
							}
						}
					}
					break;
				}
			}
		}
	}
	function calculaPeriodicidadeTerceiroServico(IdPeriodicidade,valor,campo){
		if(valor != ''){
			if(valor.indexOf(",") != -1){	
				valor = valor.replace('.','');
				valor = valor.replace('.','');
				valor = valor.replace(',','.');
			}
			valor 		  = parseFloat(valor);
			
			var Meses = 1;
			
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
		    
		   	url = "xml/periodicidade.php?IdPeriodicidade="+IdPeriodicidade;
		
			xmlhttp.open("GET", url,true);
			xmlhttp.onreadystatechange = function(){ 
		
				if(xmlhttp.readyState == 4){ 
					if(xmlhttp.status == 200){;
						if(xmlhttp.responseText != 'false'){	
							nameNode = xmlhttp.responseXML.getElementsByTagName("Fator")[0]; 
							nameTextNode = nameNode.childNodes[0];
							Fator = nameTextNode.nodeValue;
						
							campo.value = valor*parseInt(Fator);
							campo.value = formata_float(Arredonda(campo.value,2),2).replace(".",",");
						}
					}
				}
			}
			xmlhttp.send(null);
		}else{
			campo.value = '';
		}
	}
	function busca_dia_cobranca(IdPessoa, DiaCobrancaDefault){
		if(IdPessoa == ""){
			IdPessoa = 0;
		}
		
		if(DiaCobrancaDefault == undefined){
			DiaCobrancaDefault = 0;
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
	    
	    url = "xml/dia_cobranca.php?IdPessoa="+IdPessoa;
		
		xmlhttp.open("GET", url,true);
	    
		// Carregando...
		carregando(true);
		
		xmlhttp.onreadystatechange = function(){ 
			if(xmlhttp.readyState == 4){ 
				if(xmlhttp.status == 200){
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
					}else{
						while(document.formulario.DiaCobranca.options.length > 0){
							document.formulario.DiaCobranca.options[0] = null;
						}
					}
					// Fim de Carregando
					carregando(false);
				}
			}
		}
		xmlhttp.send(null);	
	}
	function busca_cfop_servico(IdServico, CFOPDefault){
		if(IdServico == ""){
			IdServico = 0;
		}
		
		if(CFOPDefault == undefined){
			CFOPDefault = 0;
		}
		
		var xmlhttp = false;
		
		if(window.XMLHttpRequest) { // Mozilla, Safari,...
	    	xmlhttp = new XMLHttpRequest();
			
	        if(xmlhttp.overrideMimeType){
	        	xmlhttp.overrideMimeType('text/xml');
			}
		} else if(window.ActiveXObject){ // IE
			try{
				xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
			} catch(e){
				try{
					xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
	            } catch(e){}
	        }
	    }
	    
	    url = "xml/cfop_servico.php?IdServico="+IdServico;
		xmlhttp.open("GET", url,true);
		// Carregando...
		carregando(true);
		xmlhttp.onreadystatechange = function(){ 
			if(xmlhttp.readyState == 4){ 
				if(xmlhttp.status == 200){
					if(xmlhttp.responseText != 'false'){		
						while(document.formulario.CFOPServico.options.length > 0){
							document.formulario.CFOPServico.options[0] = null;
						}
						
						if(document.getElementById("cpNotaFiscalCDA").style.display == "none"){
							document.getElementById("spServicoCFOP").className = "find";
							document.formulario.CFOPServico.style.width = "822px";
						} else{
							document.getElementById("spServicoCFOP").className = "separador";
							document.formulario.CFOPServico.style.width = "605px";
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
						
						var cont = document.formulario.CFOPServico.length;
						for(i = 0; i < document.formulario.CFOPServico.length; i++){
							if(document.formulario.Acao.value == 'inserir' && document.formulario.SelecionarCamposUmaOpcao.value == 1 && cont == 2){
								document.formulario.CFOPServico[1].selected	= true;						
							}else{
								if(document.formulario.CFOPServico[i].value == CFOPDefault){
									document.formulario.CFOPServico[i].selected	= true;
									break;
								}
							}
						}
					} else{
						while(document.formulario.CFOPServico.options.length > 0){
							document.formulario.CFOPServico.options[0] = null;
						}
						
						document.getElementById("cpServicoCFOP").style.display = "none";
					}
					// Fim de Carregando
					carregando(false);
				}
			}
		}
		
		xmlhttp.send(null);	
	}
	
	(function($j){
		$j.teste = function(value, id, nameClass){
			
			nameClass = nameClass.split(" ");
			nameClass = nameClass[0];
			id = id.split("_");
			nameId = id[0];
			id = parseInt(id[1]);
			if($j("."+nameClass + ":eq("+id+")").find("option:selected").val() == 0){
				$j("."+nameClass).each(function(index){
					if(index == id){
						return false;
					}
					$j(this).attr("disabled", "disabled");
					
				});
				$j("."+nameClass + ":eq("+id+")").focus();
				return false;
			}
			
			if(nameClass == "co"){
				if(value == 1){
					$j(".co").each(function(index){
						if(index < (id - 1)){
							$j("#VoltarDataBase_"+index).attr("disabled", "disabled");
							$j("#VoltarDataBase_"+index+" option:last").removeAttr("selected", false);
							$j("#VoltarDataBase_"+index).append("<option value='0' selected='selected'></option>"+
									"<option value='1'>Sim</option>"+
									"<option value='2'>N\u00e3o</option>");
						}
						else{
							index = id - 1
							$j("#VoltarDataBase_"+index).removeAttr("disabled");
							$j("#VoltarDataBase_"+index+" option").remove();
							$j("#VoltarDataBase_"+index).append("<option value='0' selected='selected'></option>"+
																"<option value='1'>Sim</option>"+
																"<option value='2'>N\u00e3o</option>");
						}
					});
				}else if(value == 2){
					$j(".co").each(function(index){
						if(index <= (id - 1)){
							$j("#VoltarDataBase_"+index).attr("disabled", true);
							$j("#VoltarDataBase_"+index+" option").remove();
							$j("#VoltarDataBase_"+index).append("<option value='0'></option>"+
													"<option value='1'>Sim</option>"+
													"<option value='2' selected='selected'>N\u00e3o</option>");
						
						}
					});
					
				}
			}else{
				$j("."+nameClass).each(function(index){
					if(index < (id - 1)){
						$j("#"+nameId+"_"+index).attr("disabled", "disabled");
						$j("#"+nameId+"_"+index+" option:last").removeAttr("selected", false);
						$j("#"+nameId+"_"+index).append("<option value='0' selected='selected'></option>"+
								"<option value='1'>Sim</option>"+
								"<option value='2'>N\u00e3o</option>");
					}
					else{
						index = id - 1
						$j("#"+nameId+"_"+index).removeAttr("disabled");
						$j("#"+nameId+"_"+index+" option").remove();
						$j("#"+nameId+"_"+index).append("<option value='0' selected='selected'></option>"+
															"<option value='1'>Sim</option>"+
															"<option value='2'>N\u00e3o</option>");
					}
				});
			}
			
			foco = $j("."+nameClass + ":eq("+id+")").parent().parent().index("tr") - 2;
			//alert(nameClass);
			if(foco != -1){
				//$j("."+nameClass+":eq("+foco+")").focus();
				//alert($j("tr:eq("+foco+")").find("td:last").text());
				$j("tr:eq("+foco+")").find("td:last").children().focus();
			}
		}
	})(jQuery);
	
	function busca_lancamentos_data_base(IdContaReceber, NumDoc){
		if(IdContaReceber == undefined || IdContaReceber == ''){
			IdContaReceber = 0;
		}
		
		$j(document).ajaxStart(function(){
			carregando(true);
		});
		
		$j(document).ajaxStop(function(){
			carregando(false);
		});
		
		$j.ajax({
			type:"GET",
			dataType:"html",
			url:"xml/demonstrativo.php",
			data:{IdContaReceber: IdContaReceber, NumDoc: NumDoc},
			success:function(data){
				//alert(data);
				if(data == "false" || data == ""){
					document.getElementById("cpVoltarDataBase").innerHTML = "";
				}else{
					$j("#cpVoltarDataBase table").remove();
					document.getElementById("cpVoltarDataBase").innerHTML = data;
					/*if(document.getElementById("cpVoltarDataBase").innerHTML == ""){
						document.getElementById("cpVoltarDataBase").innerHTML = data;
					}else{
						document.getElementById("cpVoltarDataBase").innerHTML += data;
					}*/
					
					$j.each($j(".co"), function(index, value){
						if(index == ($j(".co").length - 1))
							$j(this).removeAttr("disabled");
						else
							$j(this).attr("disabled", "disabled");
					});
					$j.each($j(".ev"), function(index, value){
						if(index == ($j(".ev").length - 1))
							$j(this).removeAttr("disabled");
						else
							$j(this).attr("disabled", "disabled");
					});
					$j.each($j(".os"), function(index, value){
						if(index == ($j(".os").length - 1))
							$j(this).removeAttr("disabled");
						else
							$j(this).attr("disabled", "disabled");
					});
					$j.each($j(".ef"), function(index, value){
						if(index == ($j(".ef").length - 1))
							$j(this).removeAttr("disabled");
						else
							$j(this).attr("disabled", "disabled");
					});
					
				}
				
			}
		});
		
		/*var url = "xml/demonstrativo.php?IdContaReceber="+IdContaReceber+"&NumDoc="+NumDoc;
		
		call_ajax(url,function (xmlhttp){
			alert(xmlhttp.responseText);
			if(xmlhttp.responseText == "false" || xmlhttp.responseText == ""){
				document.getElementById("cpVoltarDataBase").innerHTML = "";
			}else{
				if(document.getElementById("cpVoltarDataBase").innerHTML == ""){
					document.getElementById("cpVoltarDataBase").innerHTML = xmlhttp.responseText;
				}else{
					document.getElementById("cpVoltarDataBase").innerHTML += xmlhttp.responseText;
				}
				
			}*/
			/*alert(xmlhttp.responseText);
			if(xmlhttp.responseText == "false"){
				document.getElementById("cpVoltarDataBase").innerHTML = "";	
			} else{
				document.getElementById("cpVoltarDataBase").innerHTML = "";	
				
				var dados = "", dados_neg = "", tabindex = Number(document.formulario.TabIndex.value);
				
				for(var i = 0; i < xmlhttp.responseXML.getElementsByTagName("IdLancamentoFinanceiro").length; i++){
					var nameNode = xmlhttp.responseXML.getElementsByTagName("IdLancamentoFinanceiro")[i]; 
					var nameTextNode = nameNode.childNodes[0];
					var IdLancamentoFinanceiro = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("Tipo")[i]; 
					nameTextNode = nameNode.childNodes[0];
					var Tipo = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("IdContaReceber")[i]; 
					nameTextNode = nameNode.childNodes[0];
					IdContaReceber = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("Codigo")[i]; 
					nameTextNode = nameNode.childNodes[0];
					var Codigo = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("Descricao")[i]; 
					nameTextNode = nameNode.childNodes[0];
					var Descricao = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("Referencia")[i]; 
					nameTextNode = nameNode.childNodes[0];
					var Referencia = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("Moeda")[i]; 
					nameTextNode = nameNode.childNodes[0];
					var Moeda = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("Valor")[i]; 
					nameTextNode = nameNode.childNodes[0];
					var Valor = nameTextNode.nodeValue;	
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("Voltar")[i]; 
					nameTextNode = nameNode.childNodes[0];
					var Voltar = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("IdContratoAutomatico")[i]; 
					nameTextNode = nameNode.childNodes[0];
					var IdContratoAutomatico = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("IdLancamentoFinanceiroAutomatico")[i]; 
					nameTextNode = nameNode.childNodes[0];
					var IdLancamentoFinanceiroAutomatico = nameTextNode.nodeValue;
					
					if(Voltar == "true" && !(new RegExp(","+IdLancamentoFinanceiro+",$")).test(","+document.formulario.LancamentoFinanceiroTipoContrato.value+",")){
						Voltar = "false";
					}
					
					if(Valor == ''){
						Valor = 0;
					}
					
					if(Valor < 0){
						Valor = formata_float(Arredonda(Valor,2),2).replace(/\./,',');
						
						dados_neg	+=	"<table>";
						dados_neg	+=	"	<tr>";
						dados_neg	+=	"		<td class='find'>&nbsp;</td>";
						dados_neg	+=	"		<td class='descCampo'>Contas R.</td>";
						dados_neg	+=	"		<td class='separador'>&nbsp;</td>";
						dados_neg	+=	"		<td class='descCampo'>Tipo</td>";
						dados_neg	+=	"		<td class='separador'>&nbsp;</td>";
						dados_neg	+=	"		<td class='descCampo'>Código</td>";
						dados_neg	+=	"		<td class='separador'>&nbsp;</td>";
						dados_neg	+=	"		<td class='descCampo'>Descrição</td>";
						dados_neg	+=	"		<td class='separador'>&nbsp;</td>";
						dados_neg	+=	"		<td class='descCampo'>Referência</td>";
						dados_neg	+=	"		<td class='separador'>&nbsp;</td>";
						dados_neg	+=	"		<td class='descCampo'>Valor ("+Moeda+")</td>";
						dados_neg	+=	"		<td class='separador'>&nbsp;</td>";
						dados_neg	+=	"		<td class='descCampo'><B>Reaproveitar Crédito?</B></td>";	
						dados_neg	+=	"	</tr>";
						dados_neg	+=	"	<tr>";
						dados_neg	+=	"		<td class='find'>&nbsp;</td>";
						dados_neg	+=	"		<td class='campo'>";
						dados_neg	+=	"			<input type='text' name='ContaReceber_"+IdLancamentoFinanceiro+"' value='"+IdContaReceber+"' style='width:60px' readonly='readonly' />";
						dados_neg	+=	"		</td>";
						dados_neg	+=	"		<td class='separador'>&nbsp;</td>";
						dados_neg	+=	"		<td class='campo'>";
						dados_neg	+=	"			<select name='Tipo_"+IdLancamentoFinanceiro+"' style='width:50px'  disabled>";
						dados_neg	+=	"				<option value='1'>"+Tipo+"</option>";
						dados_neg	+=	"			</select>";
						dados_neg	+=	"		</td>";
						dados_neg	+=	"		<td class='separador'>&nbsp;</td>";
						dados_neg	+=	"		<td class='campo'>";
						dados_neg	+=	"			<input type='text' name='Codigo_"+IdLancamentoFinanceiro+"' value='"+Codigo+"' style='width:60px' readonly='readonly' />";
						dados_neg	+=	"		</td>";
						dados_neg	+=	"		<td class='separador'>&nbsp;</td>";
						dados_neg	+=	"		<td class='campo'>";
						dados_neg	+=	"			<input type='text' name='Descricao_"+IdLancamentoFinanceiro+"' value='"+Descricao+"' style='width:156px' readonly='readonly' />";
						dados_neg	+=	"		</td>";
						dados_neg	+=	"		<td class='separador'>&nbsp;</td>";
						dados_neg	+=	"		<td class='campo'>";
						dados_neg	+=	"			<input type='text' name='Referencia_"+IdLancamentoFinanceiro+"' value='"+Referencia+"' style='width:146px' readonly='readonly' />";
						dados_neg	+=	"		</td>";
						dados_neg	+=	"		<td class='separador'>&nbsp;</td>";
						dados_neg	+=	"		<td class='campo'>";
						dados_neg	+=	"			<input type='text' name='ValorLancamento_"+IdLancamentoFinanceiro+"' value='"+Valor+"' style='width:84px' readonly='readonly' />";
						dados_neg	+=	"		</td>";
						dados_neg	+=	"		<td class='separador'>&nbsp;</td>";
						dados_neg	+=	"		<td class='campo'>";
						dados_neg	+=	"			<select name='ReaproveitarCredito_"+IdLancamentoFinanceiro+"' style='width:170px'  onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out')\" tabindex='"+(tabindex+i)+"'\">";
						dados_neg	+=	"				<option value='0' selected></option>";
						dados_neg	+=	"			</select>";
						dados_neg	+=	"			<input type='hidden' name='ReaproveitarCreditoDefault_"+IdLancamentoFinanceiro+"' value='"+Voltar+"' />";
						dados_neg	+=	"		</td>";	
						dados_neg	+=	"	</tr>";
						dados_neg	+=	"</table>";
					} else{
						
						Valor	=	formata_float(Arredonda(Valor,2),2).replace(/\./,',');
						
						dados	+=	"<table>";
						dados	+=	"	<tr>";
						dados	+=	"		<td class='find'>&nbsp;</td>";
						dados	+=	"		<td class='descCampo'>Contas R.</td>";
						dados	+=	"		<td class='separador'>&nbsp;</td>";
						dados	+=	"		<td class='descCampo'>Tipo</td>";
						dados	+=	"		<td class='separador'>&nbsp;</td>";
						dados	+=	"		<td class='descCampo'>Código</td>";
						dados	+=	"		<td class='separador'>&nbsp;</td>";
						dados	+=	"		<td class='descCampo'>Descrição</td>";
						dados	+=	"		<td class='separador'>&nbsp;</td>";
						dados	+=	"		<td class='descCampo'>Referência</td>";
						dados	+=	"		<td class='separador'>&nbsp;</td>";
						dados	+=	"		<td class='descCampo'>Valor ("+Moeda+")</td>";
						dados	+=	"		<td class='separador'>&nbsp;</td>";
						
						switch(Tipo){
							case 'CO':
								dados	+=	"	<td class='descCampo'><B>Voltar data base de cálculo?</B></td>";
								break
							case 'EV':
								dados	+=	"	<td class='descCampo'><B>Cancelar Lanç. Financeiro?</B></td>";
								break;
							case 'OS':
								dados	+=	"	<td class='descCampo'><B>Cancelar Lanç. Financeiro?</B></td>";
								break;
							case 'EF':
								dados	+=	"	<td class='descCampo'><B>Cancelar Lanç. Financeiro?</B></td>";
								break;
						}								
						
						dados	+=	"	</tr>";
						dados	+=	"	<tr>";
						dados	+=	"		<td class='find'>&nbsp;</td>";
						dados	+=	"		<td class='campo'>";
						dados	+=	"			<input type='text' name='ContaReceber_"+IdLancamentoFinanceiro+"' value='"+IdContaReceber+"' style='width:60px' readOnly>";
						dados	+=	"		</td>";
						dados	+=	"		<td class='separador'>&nbsp;</td>";
						dados	+=	"		<td class='campo'>";
						dados	+=	"			<select name='Tipo_"+IdLancamentoFinanceiro+"' style='width:50px'>";
						dados	+=	"				<option value='1'>"+Tipo+"</option>";
						dados	+=	"			</select>";
						dados	+=	"		</td>";
						dados	+=	"		<td class='separador'>&nbsp;</td>";
						dados	+=	"		<td class='campo'>";
						dados	+=	"			<input type='text' name='Codigo_"+IdLancamentoFinanceiro+"' value='"+Codigo+"' style='width:60px' readOnly>";
						dados	+=	"		</td>";
						dados	+=	"		<td class='separador'>&nbsp;</td>";
						dados	+=	"		<td class='campo'>";
						dados	+=	"			<input type='text' name='Descricao_"+IdLancamentoFinanceiro+"' value='"+Descricao+"' style='width:156px' readOnly>";
						dados	+=	"		</td>";
						dados	+=	"		<td class='separador'>&nbsp;</td>";
						dados	+=	"		<td class='campo'>";
						dados	+=	"			<input type='text' name='Referencia_"+IdLancamentoFinanceiro+"' value='"+Referencia+"' style='width:146px' readOnly>";
						dados	+=	"		</td>";
						dados	+=	"		<td class='separador'>&nbsp;</td>";
						dados	+=	"		<td class='campo'>";
						dados	+=	"			<input type='text' name='ValorLancamento_"+IdLancamentoFinanceiro+"' value='"+Valor+"' style='width:84px' readOnly>";
						dados	+=	"		</td>";
						dados	+=	"		<td class='separador'>&nbsp;</td>";
						dados	+=	"		<td class='campo'>";
						
						switch(Tipo){
							case 'CO':
								eval("var InputLancamentoFinanceiroAutomatico = document.formulario.IdLancamentoFinanceiroAutomatico_"+IdLancamentoFinanceiro+";");
								
								if(InputLancamentoFinanceiroAutomatico == undefined) {
									InputLancamentoFinanceiroAutomatico = document.createElement("input");
									InputLancamentoFinanceiroAutomatico.setAttribute("type", "hidden");
									InputLancamentoFinanceiroAutomatico.setAttribute("name", "IdLancamentoFinanceiroAutomatico_"+IdLancamentoFinanceiro);
									InputLancamentoFinanceiroAutomatico.setAttribute("value", IdLancamentoFinanceiroAutomatico);
									document.formulario.appendChild(InputLancamentoFinanceiroAutomatico);
								} else {
									InputLancamentoFinanceiroAutomatico.value = IdLancamentoFinanceiroAutomatico;
								}
								
								dados	+=	"		<select name='VoltarDataBase_"+IdLancamentoFinanceiro+"' style='width:170px'  onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out')\" tabindex='"+(tabindex+i)+"' onChange=\"verificaMudarDataBase("+Codigo+","+IdLancamentoFinanceiro+",this.value);\">";
								dados	+=	"			<option value='0' selected></option>";
								break;
							case 'EV':		
								dados	+=	"		<select name='CancelarContaEventual_"+IdLancamentoFinanceiro+"' style='width:170px'  onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out')\" tabindex='"+(tabindex+i)+"'>";
								dados	+=	"			<option value='0' selected></option>";
								break;
							case 'OS':		
								dados	+=	"		<select name='CancelarOrdemServico_"+IdLancamentoFinanceiro+"' style='width:170px'  onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out')\" tabindex='"+(tabindex+i)+"'>";
								dados	+=	"			<option value='0' selected></option>";
								break;
							case 'EF':
								dados	+=	"		<select name='CancelarEncargoFinanceiro_"+IdLancamentoFinanceiro+"' style='width:170px'  onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out')\" tabindex='"+(tabindex+i)+"'>";
								break;
						}							
						
						dados	+=	"			</select>";
						dados	+=	"			<input type='hidden' name='VoltarDataBaseDefault_"+IdLancamentoFinanceiro+"' value='"+Voltar+"' />";
						dados	+=	"		</td>";	
						dados	+=	"	</tr>";
						dados	+=	"</table>";
					}
				}
				
				document.getElementById('cpVoltarDataBase').innerHTML = dados_neg+dados;
			}
			
			var posInicial = 0, posFinal = 0, campo = "";
			
			for(i = 0; i < document.formulario.length; i++){
				if(document.formulario[i].name != undefined){
					if(document.formulario[i].name.substring(0,16) == 'ValorLancamento_'){
						if(posInicial == 0){
							posInicial = i;
						}
						
						posFinal = i;
					}
				}
			}
			
			var IdCampo	= 0, aux = 0;
			
			if(posFinal > 0){
				var posFinalTemp = 0;
				
				for(i = posInicial; i <= posFinal; i += 8){
				
					var temp = document.formulario[i+1].name.split('_');
					IdCampo	= document.formulario[i-3].value;
					
					switch(temp[0]){
						case 'CancelarContaEventual':
							IdGrupoParametroSistema = 67;
							break;
						case 'CancelarEncargoFinanceiro':
							IdGrupoParametroSistema = 67;
							break;
						case 'VoltarDataBase':
							IdGrupoParametroSistema = 39;
							
							if(aux != trim(IdCampo)){
								document.formulario[i+1].disabled = false;
								aux	=	IdCampo;
							} else{
								document.formulario[i-6].disabled = true;
								document.formulario[i+1].disabled = false;
							}
							
							if(document.formulario[i+2].value == 'false'){
								document.formulario[i+1].disabled = true;
							}
							break;
						case 'ReaproveitarCredito':
							IdGrupoParametroSistema = 110;
							break;
						case 'CancelarOrdemServico':
							IdGrupoParametroSistema = 67;
							break;
					}
					
					addSelect(document.formulario[i+1],IdGrupoParametroSistema,'',true);
					
					if(document.formulario[i-4].options[document.formulario[i-4].selectedIndex].text == "CO"){
						posFinalTemp = i;
					}		
				}
				
				verificar_select_lancamentos_data_base(posInicial,posFinalTemp);
			}*/
		//});
	}  
	function verificar_select_lancamentos_data_base(posInicial,posFinal){
		
		if(ContExecucao > 0){
			setTimeout(function () { verificar_select_lancamentos_data_base(posInicial,posFinal); },100);
		} else{
			var selecionar = 2;
			var IdLancamentoFinanceiroAutomaticoTemp = "";
			
			if(document.formulario[posFinal+1].disabled){
				for(var i = 0; i < document.formulario.length; i++){
					if(document.formulario[i].name.substring(0, 33) == "IdLancamentoFinanceiroAutomatico_" && document.formulario[i].value != ""){
						if(IdLancamentoFinanceiroAutomaticoTemp != "")
							IdLancamentoFinanceiroAutomaticoTemp += ",";
						
						IdLancamentoFinanceiroAutomaticoTemp += document.formulario[i].value;
					}
				}
				
				selecionar = 1;
				
				if(IdLancamentoFinanceiroAutomaticoTemp != "") {
					if((new RegExp(document.formulario[posFinal+1].name.replace(/([^_]*_)/i, "(,")+",$)")).test(","+IdLancamentoFinanceiroAutomaticoTemp+",")){
						selecionar = 0;
					}
				}
			}
			
			for(var i = posFinal; i >= posInicial; i -= 8){
				if(document.formulario[i-4].options[document.formulario[i-4].selectedIndex].text == "CO"){
					if(selecionar == 1){
						document.formulario[i+1][1].selected = selecionar;
					} else{
						var temp = document.formulario[i+1].name.split('_');
						var IdLancamentoFinanceiroTipoContrato = temp[1];
						var LancamentoFinanceiroTipoContrato = ","+IdLancamentoFinanceiroTipoContrato+",";
						temp = document.formulario[i-7].name.split('_');
						
						if(temp[0] == "VoltarDataBase"){
							LancamentoFinanceiroTipoContrato = ","+temp[1]+LancamentoFinanceiroTipoContrato;
						}
						
						if(!(new RegExp(LancamentoFinanceiroTipoContrato)).test(","+document.formulario.LancamentoFinanceiroTipoContrato.value+",") && !(new RegExp("(,"+IdLancamentoFinanceiroTipoContrato+",)")).test(","+IdLancamentoFinanceiroAutomaticoTemp+",")){
							selecionar = 0;
						}
					}
				}
			}
		}
	}
	function addSelect(campo,IdGrupoParametroSistema,IdParametroSistemaTemp,selecionar){
		if(IdParametroSistemaTemp == undefined){
			IdParametroSistemaTemp = "";
		}
		
		if(selecionar == undefined){
			selecionar = false;
		}
	    
		var url = "xml/parametro_sistema.php?IdGrupoParametroSistema="+IdGrupoParametroSistema;
		
		if(!selecionar){
			url += "&IdParametroSistema="+IdParametroSistemaTemp;
		}
		
		ContExecucao++;
		
		call_ajax(url,function (xmlhttp){
			if(xmlhttp.responseText != 'false'){
				var nameNode,nameTextNode,IdParametroSistema,ValorParametroSistema;
				
				for(var ii = 0; ii < xmlhttp.responseXML.getElementsByTagName("IdParametroSistema").length; ii++){
					nameNode = xmlhttp.responseXML.getElementsByTagName("IdParametroSistema")[ii]; 
					nameTextNode = nameNode.childNodes[0];
					IdParametroSistema = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("ValorParametroSistema")[ii]; 
					nameTextNode = nameNode.childNodes[0];
					ValorParametroSistema = nameTextNode.nodeValue;
					
					addOption(campo,ValorParametroSistema,IdParametroSistema);
				}
				if(IdParametroSistemaTemp == '' || selecionar){
					campo.options[Number(IdParametroSistemaTemp)].selected = true;
				} else{
					campo.options[1].selected = true;
				}
			}
			
			ContExecucao--;
		});
	}
	function verificaMudarDataBase(Codigo,IdLancamentoFinanceiro,valor){
		var posInicial = 0, posFinal = 0, campo = "";
		
		for(var i = 0; i < document.formulario.length; i++){
			if(document.formulario[i].name != undefined){
				if(document.formulario[i].name.substring(0,7) == "Codigo_"){
					if(posInicial == 0){
						posInicial = i;
					}
					
					posFinal = i;
				}
			}
		}
		
		var cont = 0, aux = 0;
		
		for(i = posInicial; i <= posFinal; i += 8){
			if(document.formulario[i].value == Codigo){
				cont++;
			}
		}
		
		var posTemp	= 0;
		
		if(cont > 1){
			for(i = posInicial; i <= posFinal; i += 8){
				if(document.formulario[i].value == Codigo){
					var temp = document.formulario[i].name.split('_');
					
					if(temp[1] == IdLancamentoFinanceiro){
						posTemp = i;
						aux		= 1;
						break;
					}
				}
			}
			
			if(aux == 1 && posTemp >= posInicial){
				var verificador = true;
				
				for(i = posTemp; i >= posInicial; i -= 8){
					if(document.formulario[i].value == Codigo){
						var temp2 = document.formulario[i+4].name.split('_');
						
						if(temp2[0] != 'ReaproveitarCredito'){
							var CampoFocus = '';
							
							if(valor == 2){	//nao
								if(aux == 1){
									document.formulario[i+4].disabled = false;
									aux = 0;
								} else{
									document.formulario[i+4].disabled = true;
									document.formulario[i+4][1].selected = true;
								}
							} else if(valor == 1){ //sim
								if(aux == 1){
									if(document.formulario[i-4].name.substring(0,15) == 'VoltarDataBase_'){
										document.formulario[i-4].disabled = false;
										document.formulario[i-4][0].selected = true;
										
									}
									
									aux = 0;
									CampoFocus = document.formulario[i-4];
								} else{
									if(document.formulario[i-4] != undefined){
										if(document.formulario[i-8].value == Codigo){
											document.formulario[i-4].disabled = true;
											document.formulario[i-4][0].selected = true;
										}
									}
								}
							} else{
								if(aux == 1){
									document.formulario[i+4].disabled = false;
									document.formulario[i+4][0].selected = true;
									aux = 0;
								} else{
									document.formulario[i+4].disabled = true;
									document.formulario[i+4][0].selected = true;
								}
							}
							
							if(document.formulario[i-1].options[document.formulario[i-1].selectedIndex].text == "CO"){
								if(verificador){
									var LancamentoFinanceiroTipoContrato = ","+temp2[1]+",";
									//var LancamentoFinanceiroTipoContratoA = ","+temp2[1];
									temp2[0] = document.formulario[i-4].name.split('_');
									
									if(temp2[0][0] == "VoltarDataBase"){
										LancamentoFinanceiroTipoContrato = ","+temp2[0][1]+LancamentoFinanceiroTipoContrato;
									}
									
									verificador = (new RegExp(LancamentoFinanceiroTipoContrato)).test(","+document.formulario.LancamentoFinanceiroTipoContrato.value+",");
									
									if(!verificador){
										for(var ii = i-8; ii >= posInicial; ii -= 8){
											document.formulario[ii+4].disabled = true;
											
											if(document.formulario[ii+4][1] != null){
												document.formulario[ii+4][1].selected = true;
												CampoFocus = document.formulario[ii+12];
											}
										}
									}
								} else{
									document.formulario[i+4].disabled = !verificador;
									
									if(document.formulario[i+4][1]){
										document.formulario[i+4][1].selected = !verificador;
									}
								}
							}
							
							if(CampoFocus != ''){
								CampoFocus.focus();
							}
							
							if(document.formulario[i+4].name.substring(0,15) == 'VoltarDataBase_'){
								var IdLancamentoFinanceiroTemp = document.formulario[i+4].name.replace(/([^_]*)/i, "");
								
								eval("var InputLancamentoFinanceiroAutomatico = document.formulario.IdLancamentoFinanceiroAutomatico"+IdLancamentoFinanceiroTemp+";");
								
								if(InputLancamentoFinanceiroAutomatico != undefined){
									var IdLancamentoFinanceiroAutomatico = InputLancamentoFinanceiroAutomatico.value.split(",");
									
									for(var ii = 0; ii < IdLancamentoFinanceiroAutomatico.length; ii++) {
										eval("document.formulario.VoltarDataBase_"+IdLancamentoFinanceiroAutomatico[ii]+".value = "+document.formulario[i+4].value+";");
									}
								}
							}
						}
					}
				}
			}
		} else{
			for(i = posInicial; i <= posFinal; i += 8){
				if(document.formulario[i].value == Codigo){
					var temp = document.formulario[i].name.split('_');
					
					if(temp[1] == IdLancamentoFinanceiro){
						posTemp = i;
						aux		= 1;
						break;
					}
				}
			}
			
			if(aux == 1 && posTemp >= posInicial){				
				for(i = posTemp; i >= posInicial; i -= 8){
					if(document.formulario[i].value == Codigo){
						var temp2 = document.formulario[i+4].name.split('_');
						
						if(temp2[0] != 'ReaproveitarCredito'){
							if(document.formulario[i+4].name.substring(0,15) == 'VoltarDataBase_'){
								var IdLancamentoFinanceiroTemp = document.formulario[i+4].name.replace(/([^_]*)/i, "");
								
								eval("var InputLancamentoFinanceiroAutomatico = document.formulario.IdLancamentoFinanceiroAutomatico"+IdLancamentoFinanceiroTemp+";");
								
								if(InputLancamentoFinanceiroAutomatico != undefined){
									var IdLancamentoFinanceiroAutomatico = InputLancamentoFinanceiroAutomatico.value.split(",");
									
									for(var ii = 0; ii < IdLancamentoFinanceiroAutomatico.length; ii++) {
										eval("document.formulario.VoltarDataBase_"+IdLancamentoFinanceiroAutomatico[ii]+".value = "+document.formulario[i+4].value+";");
									}
								}
							}
						}
					}
				}
			}
		}
	}
	function selecionar(campo,buscar){
		var table = document.getElementById('tabelaContaReceber');
		
		if(buscar == undefined){
			buscar = true;
		}
		
		if(campo.name == "todos_cr"){
			var Checked = campo.checked;
			
			for(var i = 0; i < table.rows.length; i++){
				var AccessKey = table.rows[i].accessKey;
				if(AccessKey != '' && AccessKey != undefined){
					eval("var campo = document.formulario.cr_"+AccessKey+", valor_checked = "+Checked+"; if(campo.checked != valor_checked) { campo.checked = valor_checked; selecionar(campo,false); }");
				}
			}
		} else{
			if(campo.checked){
				document.formulario.CancelarContaReceber.value += campo.name.replace(/^cr_/i,',');
			} else{
				var ContaReceber = campo.name.replace(/^cr_/i,'');
				
				Exp = new RegExp("^"+ContaReceber+",|,"+ContaReceber+",|,"+ContaReceber+"$","i");
				document.formulario.CancelarContaReceber.value = (document.formulario.CancelarContaReceber.value+",").replace(Exp,',');
			}
			
			document.formulario.CancelarContaReceber.value = document.formulario.CancelarContaReceber.value.replace(/^,|,,|,$/g,'');
			var tratamento = "document.formulario.todos_cr.checked = (";
			
			for(var i = 0; i < table.rows.length; i++){
				var AccessKey = table.rows[i].accessKey;
				if(AccessKey != '' && AccessKey != undefined){
					tratamento += "document.formulario.cr_"+AccessKey+".checked && ";
				}
			}
			
			tratamento = tratamento.replace(/ && $/i, '')+");";
			
			eval(tratamento);
		}
		if(buscar){
			var CancelarContaReceber = document.formulario.CancelarContaReceber.value;
			
			if(CancelarContaReceber == ''){
				CancelarContaReceber = 0;
			}
			
			busca_lancamentos_data_base(CancelarContaReceber);
		}
	}
	function listar_conta_receber(IdContrato){
	   	var url = "xml/conta_receber.php?IdContrato="+IdContrato;
		
		call_ajax(url,function (xmlhttp){
			if(xmlhttp.responseText == 'false'){
				while(document.getElementById('tabelaContaReceber').rows.length > 2){
					document.getElementById('tabelaContaReceber').deleteRow(1);
				}
				
				document.getElementById('tabelaTotalValor').innerHTML	= "0,00";	
				document.getElementById('tabelaTotalReceb').innerHTML	= "0,00";	
				document.getElementById('tabelaTotal').innerHTML		= "Total: 0";	
				
			}else{
				while(document.getElementById('tabelaContaReceber').rows.length > 2){
					document.getElementById('tabelaContaReceber').deleteRow(1);
				}
				var tam, linha, c0, c1, c2, c3, c4, c5, c6, c7, c8, c9, c10, cont, tabindex = Number(document.formulario.TabIndex.value);
				var nameNode,nameTextNode,IdContaReceber,NumeroDocumento,NumeroNF,AbreviacaoNomeLocalCobranca,DataLancamento,Valor,DataVencimento,ValorRecebido,DataRecebimento,DescricaoLocalRecebimento,TotalValor=0,TotalReceb=0;cont=0;
				
				for(var i = 0; i < xmlhttp.responseXML.getElementsByTagName("IdContaReceber").length; i++){	
					nameNode = xmlhttp.responseXML.getElementsByTagName("IdContaReceber")[i]; 
					nameTextNode = nameNode.childNodes[0];
					IdContaReceber = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("NumeroDocumento")[i]; 
					nameTextNode = nameNode.childNodes[0];
					NumeroDocumento = nameTextNode.nodeValue;	
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("NumeroNF")[i]; 
					nameTextNode = nameNode.childNodes[0];
					NumeroNF = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("AbreviacaoNomeLocalCobranca")[i]; 
					nameTextNode = nameNode.childNodes[0];
					AbreviacaoNomeLocalCobranca = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("DataLancamento")[i]; 
					nameTextNode = nameNode.childNodes[0];
					DataLancamento = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("ValorReceber")[i]; 
					nameTextNode = nameNode.childNodes[0];
					Valor = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("DataVencimento")[i]; 
					nameTextNode = nameNode.childNodes[0];
					DataVencimento = nameTextNode.nodeValue;

					nameNode = xmlhttp.responseXML.getElementsByTagName("ValorRecebido")[i]; 
					nameTextNode = nameNode.childNodes[0];
					ValorRecebido = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("DataRecebimento")[i]; 
					nameTextNode = nameNode.childNodes[0];
					DataRecebimento = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoLocalRecebimento")[i]; 
					nameTextNode = nameNode.childNodes[0];
					DescricaoLocalRecebimento = nameTextNode.nodeValue;

					nameNode = xmlhttp.responseXML.getElementsByTagName("Tipo")[i]; 
					nameTextNode = nameNode.childNodes[0];
					Tipo = nameTextNode.nodeValue;

					nameNode = xmlhttp.responseXML.getElementsByTagName("Voltar")[i]; 
					nameTextNode = nameNode.childNodes[0];
					Voltar = nameTextNode.nodeValue;

					nameNode = xmlhttp.responseXML.getElementsByTagName("IdStatusRecebimento")[i]; 
					nameTextNode = nameNode.childNodes[0];
					var IdStatusRecebimento = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("IdStatus")[i]; 
					nameTextNode = nameNode.childNodes[0];
					var IdStatus = nameTextNode.nodeValue;
				
					document.formulario.todos_cr.disabled = true;
				
					if(IdStatus != 0 && IdStatus != "" && IdStatus != 2){
						
						document.formulario.bt_cancelar.disabled= false;
						document.formulario.todos_cr.disabled = false;
						document.formulario.ObsCancelamento.disabled = false;
						
						if(IdStatusRecebimento != 1){
						//	ValorRecebido = DataRecebimento = DescricaoLocalRecebimento = '';
						}
						
						tam 	= document.getElementById('tabelaContaReceber').rows.length;
						linha	= document.getElementById('tabelaContaReceber').insertRow(tam-1);
						
						if(tam%2 != 0){
							linha.style.backgroundColor = "#E2E7ED";
						}
						
						linha.accessKey = IdContaReceber; 
						
						if(ValorRecebido==''){
							ValorRecebido = 0;
						}

						TotalValor	= parseFloat(TotalValor) + parseFloat(Valor);
						TotalReceb	= parseFloat(TotalReceb) + parseFloat(ValorRecebido);
						
						c0	= linha.insertCell(0);	
						c1	= linha.insertCell(1);	
						c2	= linha.insertCell(2);	
						c3	= linha.insertCell(3);
						c4	= linha.insertCell(4);
						c5	= linha.insertCell(5);
						c6	= linha.insertCell(6);
						c7	= linha.insertCell(7);
						c8	= linha.insertCell(8);
						c9	= linha.insertCell(9);
						c10	= linha.insertCell(10);

						linkIni = "<a href='cadastro_conta_receber.php?IdContaReceber="+IdContaReceber+"'>"
						linkFim	=	"</a>";
						
						c0.innerHTML = "<input style='border:0' type='checkbox' name='cr_"+IdContaReceber+"' onClick='selecionar(this)' tabindex='"+(tabindex+i)+"'>";
						c0.className = "tableListarEspaco";
						
						c1.innerHTML = linkIni + IdContaReceber + linkFim;
						c1.style.padding  =	"0 0 0 5px";
						c1.style.cursor = "pointer";
						
						c2.innerHTML = linkIni + NumeroDocumento + linkFim;
						c2.style.cursor = "pointer";

						c3.innerHTML = linkIni + NumeroNF + linkFim;
						c3.style.cursor = "pointer";

						c4.innerHTML = linkIni + AbreviacaoNomeLocalCobranca + linkFim;
						c4.style.cursor = "pointer";

						c5.innerHTML = linkIni + dateFormat(DataLancamento) + linkFim;
						c5.style.cursor = "pointer";
						
						c6.innerHTML =  linkIni + formata_float(Arredonda(Valor,2),2).replace('.',',') + linkFim;
						c6.style.textAlign = "right";
						c6.style.cursor = "pointer";
						c6.style.padding  =	"0 8px 0 0";

						c7.innerHTML = linkIni + dateFormat(DataVencimento) + linkFim;
						c7.style.cursor = "pointer";

						c8.innerHTML =  linkIni + formata_float(Arredonda(ValorRecebido,2),2).replace('.',',') + linkFim;
						c8.style.textAlign = "right";
						c8.style.cursor = "pointer";
						c8.style.padding  =	"0 8px 0 0";

						c9.innerHTML = linkIni + dateFormat(DataRecebimento) + linkFim;
						c9.style.cursor = "pointer";

						c10.innerHTML = linkIni + DescricaoLocalRecebimento + linkFim;
						c10.style.cursor = "pointer";
						cont++;
					}
				}
				document.formulario.TabIndex.value						= (tabindex+i);
				document.getElementById('tabelaTotalValor').innerHTML	= formata_float(Arredonda(TotalValor,2),2).replace('.',',');	
				document.getElementById('tabelaTotal').innerHTML		= "Total: "+cont;
			}
			if(window.janela != undefined){
				window.janela.close();
			}
		});
	}
	
	function verificar_local_cobranca(IdLocalCobranca,IdCartao,IdContaDebito,Status){
		if(IdLocalCobranca == undefined || IdLocalCobranca == ""){
			IdLocalCobranca = 0;
		}
		var url = "xml/local_cobranca.php?IdLocalCobranca="+IdLocalCobranca;
		
		call_ajax(url,function (xmlhttp){
			if(xmlhttp.responseText != "false"){
				//document.getElementById('espacamentoNotaFiscal').removeAttribute('class');
				nameNode = xmlhttp.responseXML.getElementsByTagName("IdTipoLocalCobranca")[0]; 
				nameTextNode = nameNode.childNodes[0];
				IdTipoLocalCobranca = nameTextNode.nodeValue;
				if(Status == "Anterior"){
					switch(IdTipoLocalCobranca){
						case '3':
							document.getElementById('label_IdContaDebitoCartao'+Status).style.display = "block";
							document.getElementById('label_IdContaDebitoCartao'+Status).style.display = "block";
							document.getElementById('label_IdContaDebitoCartao'+Status).innerHTML = "Conta Débito Automático";
							document.formulario.IdContaDebitoCartaoAnterior.style.display = "block";
							buscar_conta_debito_atomatico(document.formulario.IdPessoa.value,IdContaDebito,Status);
							
							break;
						case '6':
							document.getElementById('label_IdContaDebitoCartao'+Status).style.display = "block";
							document.getElementById('label_IdContaDebitoCartao'+Status).style.display = "block";
							document.getElementById('label_IdContaDebitoCartao'+Status).innerHTML = "Cartão de Crédito";
							document.formulario.IdContaDebitoCartaoAnterior.style.display = "block";
							busca_cartao_credito(document.formulario.IdPessoa.value,IdCartao,Status);
							break;
						default:
							document.formulario.IdContaDebitoCartaoAnterior.style.display = "none";
							document.formulario.ObrigatoriedadeContaCartao.value = "";
							document.getElementById('espacamentoNotaFiscal').setAttribute('class','find');
							document.formulario.CFOPServico.style.width = '605px';
							break;
					}
				}else{
					switch(IdTipoLocalCobranca){
						case '3':
							document.getElementById('label_IdContaDebitoCartao').innerHTML = "Conta Débito Automático";
							document.getElementById('cpIdContaDebitoCartao').style.display = "block";
							buscar_conta_debito_atomatico(document.formulario.IdPessoa.value,IdContaDebito);
							document.formulario.ObrigatoriedadeContaCartao.value = 1;
							document.getElementById('espacamentoNotaFiscal').setAttribute('class','separador');
							document.formulario.CFOPServico.style.width = '416px';
							break;
						case '6':
							document.getElementById('label_IdContaDebitoCartao').innerHTML = "Cartão de Crédito";
							document.getElementById('cpIdContaDebitoCartao').style.display = "block";
							busca_cartao_credito(document.formulario.IdPessoa.value,IdCartao);
							document.formulario.ObrigatoriedadeContaCartao.value = 1;
							document.getElementById('espacamentoNotaFiscal').setAttribute('class','separador');
							document.formulario.CFOPServico.style.width = '416px';
							break;
						default:
							document.getElementById('cpIdContaDebitoCartao').style.display = "none";
							document.formulario.ObrigatoriedadeContaCartao.value = "";
							document.getElementById('espacamentoNotaFiscal').setAttribute('class','find');
							document.formulario.CFOPServico.style.width = '605px';
							break;
					}
				}
			}else{
				document.getElementById('cpIdContaDebitoCartao').style.display = "none";
				document.formulario.ObrigatoriedadeContaCartao.value = "";
				document.getElementById('espacamentoNotaFiscal').setAttribute('class','find');
				document.formulario.CFOPServico.style.width = '605px';
			}
		});
	}
	
	function buscar_conta_debito_atomatico(IdPessoa,IdNumeroContaDebito,Status){
		if(Status == "Anterior"){
			if(IdPessoa == undefined || IdPessoa == 0){
				IdPessoa = 0;
			}
			
			if(IdNumeroContaDebito == undefined || IdNumeroContaDebito == 0 || IdNumeroContaDebito == "NULL"){
				IdNumeroContaDebito = "";
			}
			
			while(document.formulario.IdContaDebitoCartaoAnterior.options[0] != null){
				document.formulario.IdContaDebitoCartaoAnterior.remove(0);
			}
			addOption(document.formulario.IdContaDebitoCartaoAnterior,"","");
			
			var url = "xml/pessoa_conta_debito.php?IdPessoa="+IdPessoa;
			call_ajax(url,function (xmlhttp){
				if(xmlhttp.responseText != "false"){
					for(var i=0;i<xmlhttp.responseXML.getElementsByTagName("IdContaDebito").length;i++){
						nameNode = xmlhttp.responseXML.getElementsByTagName("IdContaDebito")[0]; 
						nameTextNode = nameNode.childNodes[0];
						IdContaDebito = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("NumeroAgencia")[0]; 
						nameTextNode = nameNode.childNodes[0];
						NumeroAgencia = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("DigitoAgencia")[0]; 
						nameTextNode = nameNode.childNodes[0];
						DigitoAgencia = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("NumeroConta")[0]; 
						nameTextNode = nameNode.childNodes[0];
						NumeroConta = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("DigitoConta")[0]; 
						nameTextNode = nameNode.childNodes[0];
						DigitoConta = nameTextNode.nodeValue;
						
						var NumeroContaCompleto = NumeroAgencia+"-"+DigitoAgencia+" "+NumeroConta+"-"+DigitoConta;
						var SelectIdContaCartao = document.formulario.IdContaDebitoCartaoAnterior;
						addOption(document.formulario.IdContaDebitoCartaoAnterior,NumeroContaCompleto,IdContaDebito);
						
						if(IdNumeroContaDebito != ""){
							document.formulario.IdContaDebitoCartaoAnterior.value = IdNumeroContaDebito;
						}else{
							document.formulario.IdContaDebitoCartaoAnterior.options[0].selected = true;
						}
					}
					document.formulario.SeletorContaCartao.value = 'IdContaDebito';
				}else{
					while(document.formulario.IdContaDebitoCartaoAnterior.options[0] != null){
						document.formulario.IdContaDebitoCartaoAnterior.remove(0);
					}
					addOption(document.formulario.IdContaDebitoCartaoAnterior,"","");
				}	
			});
		}else{
			if(IdPessoa == undefined || IdPessoa == 0){
				IdPessoa = 0;
			}
			
			if(IdNumeroContaDebito == undefined || IdNumeroContaDebito == 0 || IdNumeroContaDebito == "NULL"){
				IdNumeroContaDebito = "";
			}
			
			while(document.formulario.IdContaDebitoCartao.options[0] != null){
				document.formulario.IdContaDebitoCartao.remove(0);
			}
			addOption(document.formulario.IdContaDebitoCartao,"","");
			
			var url = "xml/pessoa_conta_debito.php?IdPessoa="+IdPessoa;
			call_ajax(url,function (xmlhttp){
				if(xmlhttp.responseText != "false"){
					for(var i=0;i<xmlhttp.responseXML.getElementsByTagName("IdContaDebito").length;i++){
						nameNode = xmlhttp.responseXML.getElementsByTagName("IdContaDebito")[0]; 
						nameTextNode = nameNode.childNodes[0];
						IdContaDebito = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("NumeroAgencia")[0]; 
						nameTextNode = nameNode.childNodes[0];
						NumeroAgencia = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("DigitoAgencia")[0]; 
						nameTextNode = nameNode.childNodes[0];
						DigitoAgencia = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("NumeroConta")[0]; 
						nameTextNode = nameNode.childNodes[0];
						NumeroConta = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("DigitoConta")[0]; 
						nameTextNode = nameNode.childNodes[0];
						DigitoConta = nameTextNode.nodeValue;
						
						var NumeroContaCompleto = NumeroAgencia+"-"+DigitoAgencia+" "+NumeroConta+"-"+DigitoConta;
						var SelectIdContaCartao = document.formulario.IdContaDebitoCartao;
						addOption(document.formulario.IdContaDebitoCartao,NumeroContaCompleto,IdContaDebito);
						
						if(IdNumeroContaDebito != ""){
							document.formulario.IdContaDebitoCartao.value = IdNumeroContaDebito;
						}else{
							document.formulario.IdContaDebitoCartao.options[0].selected = true;
						}
					}
					document.formulario.SeletorContaCartao.value = 'IdContaDebito';
				}else{
					while(document.formulario.IdContaDebitoCartao.options[0] != null){
						document.formulario.IdContaDebitoCartao.remove(0);
					}
					addOption(document.formulario.IdContaDebitoCartao,"","");
				}	
			});
		}
	}
	function busca_cartao_credito(IdPessoa,IdNumeroCartao,Status)
	{
		if(IdPessoa == "" || IdPessoa == "NULL") IdPessoa = 0;
		if(IdNumeroCartao == "" || IdNumeroCartao == "NULL" || IdNumeroCartao == undefined) IdNumeroCartao = "";
		var url = "xml/pessoa_cartao_credito.php?IdPessoa="+IdPessoa;
		
		if(Status == "anterior"){
			while(document.formulario.IdContaDebitoCartaoAnterior.options[0] != null){
				document.formulario.IdContaDebitoCartaoAnterior.remove(0);
			}
			addOption(document.formulario.IdContaDebitoCartaoAnterior,"","");
			
			call_ajax(url, function(xmlhttp){ 
				var nameNode, nameTextNode;
				if(xmlhttp.responseText != 'false'){		
					var nameNode, nameTextNode, IdPessoaEndereco,DescricaoEndereco;							
					for(i=0;i<xmlhttp.responseXML.getElementsByTagName("IdCartao").length;i++){
						var nameNode = xmlhttp.responseXML.getElementsByTagName("IdCartao")[i]; 
						var nameTextNode = nameNode.childNodes[0];
						var IdCartao = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("NumeroCartaoMascarado")[i]; 
						nameTextNode = nameNode.childNodes[0];
						var NumeroCartaoMascarado = nameTextNode.nodeValue;
						
						addOption(document.formulario.IdContaDebitoCartaoAnterior,NumeroCartaoMascarado,IdCartao);
						
					}
					if(IdNumeroCartao == ""){
						document.formulario.IdContaDebitoCartaoAnterior.options[0].selected=true;
					}
					else{
						document.formulario.IdContaDebitoCartaoAnterior.value = IdNumeroCartao;
					}
					
					document.formulario.SeletorContaCartao.value = 'IdCartao';
				}
			});
		}else{
			while(document.formulario.IdContaDebitoCartao.options[0] != null){
				document.formulario.IdContaDebitoCartao.remove(0);
			}
			addOption(document.formulario.IdContaDebitoCartao,"","");
			
			call_ajax(url, function(xmlhttp){ 
				var nameNode, nameTextNode;
				if(xmlhttp.responseText != 'false'){		
					var nameNode, nameTextNode, IdPessoaEndereco,DescricaoEndereco;							
					for(i=0;i<xmlhttp.responseXML.getElementsByTagName("IdCartao").length;i++){
						var nameNode = xmlhttp.responseXML.getElementsByTagName("IdCartao")[i]; 
						var nameTextNode = nameNode.childNodes[0];
						var IdCartao = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("NumeroCartaoMascarado")[i]; 
						nameTextNode = nameNode.childNodes[0];
						var NumeroCartaoMascarado = nameTextNode.nodeValue;
						
						addOption(document.formulario.IdContaDebitoCartao,NumeroCartaoMascarado,IdCartao);
						
					}
					if(IdNumeroCartao == ""){
						document.formulario.IdContaDebitoCartao.options[0].selected=true;
					}
					else{
						document.formulario.IdContaDebitoCartao.value = IdNumeroCartao;
					}
					
					document.formulario.SeletorContaCartao.value = 'IdCartao';
				}
			});
		}
	}
	function selecionaCampos(){
		var cont = document.formulario.IdAgenteAutorizado.options.length;
		if(cont > 1 && document.formulario.Acao.value == 'inserir' && document.formulario.SelecionarCamposUmaOpcao.value == 1){
			document.formulario.IdAgenteAutorizado[1].selected	= true;
			listar_carteira(document.formulario.IdAgenteAutorizado[1].value);			
		}		
	}