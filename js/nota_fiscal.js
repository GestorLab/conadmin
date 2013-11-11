	function excluir(IdMovimentacaoProduto){
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
    
   			url = "files/excluir/excluir_nota_fiscal.php?IdMovimentacaoProduto="+IdMovimentacaoProduto;
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
								url = 'cadastro_nota_fiscal.php?Erro='+document.formulario.Erro.value;
								window.location.replace(url);
							}else{
								verificaErro();
							}
						}else{
							var numMsg = parseInt(xmlhttp.responseText);
							mensagens(numMsg);
							if(numMsg == 7){
								var aux = 0, valor=0;
								for(var i=0; i<document.getElementById('tableListar').rows.length; i++){
									if(IdMovimentacaoProduto == document.getElementById('tableListar').rows[i].accessKey){
										document.getElementById('tableListar').deleteRow(i);
										tableMultColor('tableListar',document.filtro.corRegRand.value);
										aux=1;
										break;
									}
								}
								if(aux=1){
									for(var i=1; i<(document.getElementById('tableListar').rows.length-1); i++){
										temp	=	document.getElementById('tableListar').rows[i].cells[7].innerHTML.split(">");
										temp1	=	temp[1].split("<");
										valor	+=	parseFloat(temp1[0].replace(',','.'));
									}
									document.getElementById('tableListarValor').innerHTML	=	formata_float(Arredonda(valor,2),2).replace('.',',');	
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
	function validar(){
		if(document.formulario.NumeroNF.value==''){
			mensagens(1);
			document.formulario.NumeroNF.focus();
			return false;
		}
		if(document.formulario.CPF_CNPJ.value==''){
			mensagens(1);
			document.formulario.CPF_CNPJ.focus();
			return false;
		}
		if(document.formulario.SerieNF.value==''){
			mensagens(1);
			document.formulario.SerieNF.focus();
			return false;
		}
		if(document.formulario.TipoMovimentacao.value==''){
			mensagens(1);
			document.formulario.TipoMovimentacao.focus();
			return false;
		}
		if(document.formulario.CFOP.value==''){
			mensagens(1);
			document.formulario.CFOP.focus();
			return false;
		}
		if(document.formulario.DataNF.value==''){
			mensagens(1);
			document.formulario.DataNF.focus();
			return false;
		}else{
			if(isData(document.formulario.DataNF.value)==false){
				mensagens(27);
				document.formulario.DataNF.focus();
				return false;
			}
		}
		if(document.formulario.ValorNF.value==''){
			mensagens(1);
			document.formulario.ValorNF.focus();
			return false;
		}else{
			var valorNF,totalNF;
		
			valorNF		=	document.formulario.ValorNF.value;
			valorNF		=	new String(valorNF);
			valorNF		=	valorNF.replace('.','');
			valorNF		=	valorNF.replace('.','');
			valorNF		=	valorNF.replace('.','');
			valorNF		=	valorNF.replace('.','');
			valorNF		=	valorNF.replace(',','.');
			
			totalNF		=	document.formulario.ValorTotalNF.value;
			totalNF		=	new String(totalNF);
			totalNF		=	totalNF.replace('.','');
			totalNF		=	totalNF.replace('.','');
			totalNF		=	totalNF.replace('.','');
			totalNF		=	totalNF.replace('.','');
			totalNF		=	totalNF.replace(',','.');
			
			if(valorNF != totalNF){
				mensagens(94);
				document.formulario.ValorNF.focus();
				return false;
			}
		}
		if(document.formulario.IdEstoque.value==''){
			mensagens(1);
			document.formulario.IdEstoque.focus();
			return false;
		}
		if(document.formulario.IdPessoa.value==''){
			mensagens(1);
			document.formulario.IdPessoa.focus();
			return false;
		}
		var posIni=0,posFim=0;
		for(i=0;i<document.formulario.length;i++){
			if(document.formulario[i].name.substr(0,10) == 'IdProduto_'){
				if(posIni==0){
					posIni = i;
					posFim = i;
				}else{
					posFim = i;
				}
			}
		}
		
		qtd = 0;
		
		for(i=posIni;i<=posFim;i=i+9){
			temp		=	document.formulario[i].name.split('_');	
			CodProduto	=	temp[1];	
			IdProduto	=	document.formulario[i].value;
			if(document.formulario[i].value==''){
				mensagens(1);
				document.formulario[i].focus();
				return false;
				break;
			}
			if(document.formulario[i+3].value==''){
				mensagens(1);
				document.formulario[i+3].focus();
				return false;
				break;
			}else{
				qtd = parseInt(document.formulario[i+3].value);
			}
			if(document.formulario[i+4].value==''){
				mensagens(1);
				document.formulario[i+4].focus();
				return false;
				break;
			}
			if(document.formulario[i+6].value==''){
				mensagens(1);
				document.formulario[i+6].focus();
				return false;
				break;
			}
			if(document.formulario[i+7].value==''){
				mensagens(1);
				document.formulario[i+7].focus();
				return false;
				break;
			}
			
			var posIni2=0,posFim2=0,ii;
			for(ii=0;ii<document.formulario.length;ii++){
				if(document.formulario[ii].name.substr(0,7) == 'NSerie_'){
					if(posIni2==0){
						posIni2 = ii;
						posFim2 = ii;
					}else{
						posFim2 = ii;
					}
				}
			}
			
			if(posIni2!=0){
				for(ii=posIni2;ii<=posFim2;ii=ii+3){
					var temp2	=	document.formulario[ii].name.split('_');	
					if(temp2[1] == CodProduto){
						if(document.formulario[ii].value == 1){
							var tam = 0, serie, qtdTemp	=	0, cont, qtdTemp2;
							
							serie		=	document.formulario[ii+2].value.split('\n');
							tam			=	parseInt(serie.length);
							qtdTemp		=	0;
							qtdTemp2	=	0;
							cont		=	0;
							serieTemp	=	new Array();
							novoSerie	=	'';
							
							//Retira Espaços e Enter
							while(cont<tam){
								
								serie[cont] = trim(serie[cont]);
								
								if(serie[cont]!=''){
									serieTemp[qtdTemp]	=	serie[cont];
									
									qtdTemp++;				
								}
								
								cont++;
							}
							
							//Retira Numero Repetido
							novoSerie		=	removeDuplicado(serieTemp,true);
							qtdTemp2		=	novoSerie.length;
							
							if(qtdTemp != qtdTemp2){
								mensagens(97);
								document.formulario[i].focus();
								return false;
							}else if(qtd != qtdTemp || document.formulario[ii+2].value == 1 && qtdTemp == 0){
								mensagens(96);
								document.formulario[i].focus();
								return false;
							}else{
								IdMovimentacaoProduto	=	document.formulario.IdMovimentacaoProduto.value;
								NumeroSerie				=	novoSerie;
								 //validarExistenciaNumeroSere(IdMovimentacaoProduto,document.formulario[i].value,NumeroSerie);
								 
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
							    
							   	url = "xml/nota_fiscal_produto_serie.php?IdMovimentacaoProduto="+IdMovimentacaoProduto+"&IdProduto="+IdProduto+"&NumeroSerie="+NumeroSerie;
							   	xmlhttp.open("GET", url,true);
							
								xmlhttp.onreadystatechange = function(){ 
									if(xmlhttp.readyState == 4){ 
										if(xmlhttp.status == 200){
											if(xmlhttp.responseText == 'false'){
												document.formulario.validar.value = 'true';
											}else{
												document.formulario.validar.value = 'false';
											}
										}
									} 				
								}
								xmlhttp.send(null);
								
							}
						}
						
						ii	=	posFim2;
					}
				}
			} 	
		}
		if(document.formulario.ValorTotalICMS.value==''){
			mensagens(95);
			document.formulario.ValorTotalICMS.focus();
			return false;
		}else{
			var valorICMS,totalICMS;
			
			valorICMS		=	document.formulario.ValorTotalICMS.value;
			valorICMS		=	new String(valorICMS);
			valorICMS		=	valorICMS.replace('.','');
			valorICMS		=	valorICMS.replace('.','');
			valorICMS		=	valorICMS.replace('.','');
			valorICMS		=	valorICMS.replace('.','');
			valorICMS		=	valorICMS.replace(',','.');
			
			if(document.formulario.ValorTotalICMSTemp.value==""){
				document.formulario.ValorTotalICMSTemp.value = 0;
			}
			
			totalICMS		=	document.formulario.ValorTotalICMSTemp.value;
			totalICMS		=	new String(totalICMS);
			totalICMS		=	totalICMS.replace('.','');
			totalICMS		=	totalICMS.replace('.','');
			totalICMS		=	totalICMS.replace('.','');
			totalICMS		=	totalICMS.replace('.','');
			totalICMS		=	totalICMS.replace(',','.');
			
			if(valorICMS != totalICMS){
				mensagens(95);
				document.formulario.ValorTotalICMS.focus();
				return false;
			}
		}
		if(document.formulario.ValorFrete.value==''){
			mensagens(1);
			document.formulario.ValorFrete.focus();
			return false;
		}
		if(document.formulario.ValorSeguro.value==''){
			mensagens(1);
			document.formulario.ValorSeguro.focus();
			return false;
		}
		if(document.formulario.ValorOutrasDespesas.value==''){
			mensagens(1);
			document.formulario.ValorOutrasDespesas.focus();
			return false;
		}
		if(document.formulario.validar.value == 'false'){
			mensagens(98);
			return false;
		}
		return true;
	}
	function inicia(){
		document.formulario.NumeroNF.focus();
		
		if(document.formulario.IdMovimentacaoProduto.value == ''){
			addProduto('');
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
	function validar_CNPJ(valor){
		if(valor == ''){
			return false;
		}
		var temp	=	valor.split('.');
		if(temp[1] == undefined){
			inserir_mascara(valor);
		}
		if(isCNPJ(valor) == false){
			document.getElementById('cp_CPF_CNPJ_Titulo').innerHTML = "CNPJ Fornecedor - Inválido";
			colorTemp = document.getElementById('cp_CPF_CNPJ_Titulo').style.backgroundColor;
			document.getElementById('cp_CPF_CNPJ_Titulo').style.backgroundColor = '#C10000';
			document.getElementById('cp_CPF_CNPJ_Titulo').style.color='#FFFFFF';
			document.formulario.CPF_CNPJ.focus();
			return false;
		}else{
			document.getElementById('cp_CPF_CNPJ_Titulo').innerHTML = "CNPJ Fornecedor";
			document.getElementById('cp_CPF_CNPJ_Titulo').style.backgroundColor='#FFFFFF';
			document.getElementById('cp_CPF_CNPJ_Titulo').style.color	=	'#C10000';
			return true;
		}	
	}
	function inserir_mascara(valor){
		if(valor == ''){
			return false;
		}
		var retorno = valor.substr(0,2) + '.' + valor.substr(2,3) + '.' + valor.substr(5,3) + '/' + valor.substr(8,4) + '-' + valor.substr(12,2);
		document.formulario.CPF_CNPJ.value = retorno;
	}
	function verifica_addProduto(){
		var i=0,posIni=0,posFim=0,bloqueio=0;
		for(i=0;i<document.formulario.length;i++){
			if(document.formulario[i].name.substr(0,10) == 'IdProduto_'){
				if(posIni==0){
					posIni = i;
					posFim = i;
				}else{
					posFim = i;
				}
			}
		}
		
		for(i=posIni;i<=posFim;i=i+9){
			if(document.formulario[i].value==''){
				mensagens(1);
				document.formulario[i].focus();
				bloqueio = 1;
				break;
			}
			if(document.formulario[i+3].value==''){
				mensagens(1);
				document.formulario[i+3].focus();
				bloqueio = 1;
				break;
			}
			if(document.formulario[i+4].value==''){
				mensagens(1);
				document.formulario[i+4].focus();
				bloqueio = 1;
				break;
			}
			if(document.formulario[i+6].value==''){
				mensagens(1);
				document.formulario[i+6].focus();
				bloqueio = 1;
				break;
			}
			if(document.formulario[i+7].value==''){
				mensagens(1);
				document.formulario[i+7].focus();
				bloqueio = 1;
				break;
			}
		} 

		if(bloqueio == 0){
			addProduto('');
		}
	}
	function addProduto(IdProduto){
	
		if(IdProduto == undefined){
			IdProduto = 0;
		}
	
		var tam,linha,c0,c1,c2,c3,c4,c5,c6,c7,c8,c9,c0,c11,c12,c13,c14,c15,c16,c17,id=0;
		
		if(document.formulario.QtdProduto.value == ''){
			document.formulario.QtdProduto.value = 0;
		}
		
		id =  parseInt(document.formulario.QtdProduto.value) + 1;
		
		document.formulario.QtdProduto.value = id;
		
		
		tam 	= document.getElementById('tabelaProduto').rows.length;
		linha	= document.getElementById('tabelaProduto').insertRow(tam);
			
		linha.accessKey = id; 
		
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
	
		if(IdProduto != 0){
			c0.innerHTML = "<img src='../../img/estrutura_sistema/ico_lupa.gif' alt='Buscar'>";
		}else{
			c0.innerHTML = "<img src='../../img/estrutura_sistema/ico_lupa.gif' alt='Buscar' onClick=\"vi_id('quadroBuscaProduto', true, event, null, 405, "+id+");\">";
		}
		
		c0.style.padding =	"3px 0 3px 3px";
		c0.style.textAlign	=	'center';
		c0.style.cursor = "pointer";
		c0.style.width = "20px";
		
		tabindex = 9 + id; 
		
		c1.innerHTML = "<input type='text' name='IdProduto_"+id+"' style='width:60px' value='' maxlength='11' onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out')\"; onChange=\"busca_produto(this.value,false,document.formulario.Local.value,"+id+")\" tabindex='"+tabindex+"'><input class='agrupador' type='text' name='DescricaoReduzidaProduto' value='' style='width:152px' maxlength='100' readOnly>";
		c1.style.padding =	"3px 0 3px 0";
		
		c2.innerHTML = "&nbsp;";
		
		c3.innerHTML = "<input type='text' name='IdUnidade_"+id+"' style='width:30px' value='' readOnly>";
		c3.style.padding =	"3px 0 3px 0";
		
		c4.innerHTML = "&nbsp;";
		
		c5.innerHTML = "<input type='text' name='Quantidade_"+id+"' style='width:50px' maxlength='16' onkeypress=\"reais(this,event)\" onkeydown=\"backspace(this,event)\" onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out'); calcula_total_produto("+id+")\" tabindex='"+tabindex+"'>";
		c5.style.padding =	"3px 0 3px 0";
		
		c6.innerHTML = "&nbsp;";
		
		c7.innerHTML = "<input type='text' name='ValorUnitario_"+id+"' style='width:82px' maxlength='23' onkeypress=\"reais(this,event,5)\" onkeydown=\"backspace(this,event,5)\" onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out');  calcula_total_produto("+id+");\" tabindex='"+tabindex+"'>";
		c7.style.padding =	"3px 0 3px 0";
		
		c8.innerHTML = "&nbsp;";
		
		c9.innerHTML = "<input type='text' name='ValorTotal_"+id+"' style='width:90px' value='' readOnly>";
		c9.style.padding =	"3px 0 3px 0";
		
		c10.innerHTML = "&nbsp;";
		
		c11.innerHTML = "<input type='text' name='AliquotaICMS_"+id+"' style='width:78px;' maxlength='8' onkeypress=\"reais(this,event)\" onkeydown=\"backspace(this,event)\" onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out'); calcula_somatorio_produto()\" tabindex='"+tabindex+"'>";
		c11.style.padding =	"3px 0 3px 0";
		
		c12.innerHTML = "&nbsp;";
		
		c13.innerHTML = "<input type='text' name='AliquotaIPI_"+id+"' style='width:69px;' value='' maxlength='8' onkeypress=\"reais(this,event)\" onkeydown=\"backspace(this,event)\" onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out'); calcula_valor_ipi("+id+")\" tabindex='"+tabindex+"'>";
		c13.style.padding =	"3px 0 3px 0";
		
		c14.innerHTML = "&nbsp;";
			
		c15.innerHTML = "<input type='text' name='ValorIPI_"+id+"' value='' style='width:75px' maxlength='20' readOnly>";
		c15.style.padding =	"3px 0 3px 0";
		
		c16.innerHTML = "<img id='icoNSerie"+id+"' src='../../img/estrutura_sistema/ico_serie.gif' alt='Adicionar Nº Série?' onClick=\"verifica_numero_serie("+id+")\">";
		c16.style.cursor = "pointer";
		c16.style.padding =	"3px 0 3px 3px";
		c16.style.textAlign	=	'center';
		
		c17.innerHTML = "<img src='../../img/estrutura_sistema/ico_del.gif' alt='Excluir?' onClick=\"excluirProduto("+id+")\">";
		c17.style.cursor = "pointer";
		c17.style.padding =	"3px 0 3px 3px";
		c17.style.textAlign	=	'center';
		
		
		if(id > 1){
			document.getElementById('IdProduto_'+id).focus();
		}
		
		addNumeroSerie(id);
	}
	function verifica_numero_serie(id){
		var i=0,posIni=0,posFim=0;
		for(i=0;i<document.formulario.length;i++){
			if(document.formulario[i].name.substr(0,10) == 'IdProduto_'){
				if(posIni==0){
					posIni	=	i;
					posFim	=	i;
				}else{
					posFim	=	i;
				}
			}
		}
		document.formulario.Produtos.value	=	'';	
		
		for(i=posIni;i<=posFim;i=i+9){
			if(document.formulario[i].name.substr(0,10) == 'IdProduto_'){
				var temp	=	document.formulario[i].name.split('_');	
				if(temp[1] == id){
					if(document.formulario[i].value != '' && document.formulario[i+3].value != ''){
						
						var i2=0,posIni2=0,posFim2=0;
						for(i2=0;i2<document.formulario.length;i2++){
							if(document.formulario[i2].name.substr(0,7) == 'NSerie_'){
								if(posIni2==0){
									posIni2	=	i2;
									posFim2	=	i2;
								}else{
									posFim2	=	i2;
								}
							}
						}
						
						for(i2=posIni2;i2<=posFim2;i2=i2+3){
							if(document.formulario[i2].name.substr(0,7) == 'NSerie_'){
								var temp2	=	document.formulario[i2].name.split('_');	
								if(temp2[1] == id && document.formulario[i2].value == 1){
						
									vi_id('quadroBuscaNumeroSerie', true, event, 460, 400); 
									
									document.formulario.NumeroSerieTemp.value	=	id;
									document.getElementById("NumeroSerie_"+id).style.display = 'block';
									
									ocultarNumeroSerie(id);
									
									i2	=	posFim2;
								}
							}
						}
					}else{
						if(document.formulario[i].value == ''){
							document.formulario[i].focus();
						}else{
							document.formulario[i+3].focus();
						}
						mensagens(1);
					}
					i = posFim;
				}
			}
		}
	}
	function addNumeroSerie(id){
	
		var tam,linha,c0;
		
		tam 	= document.getElementById('tabelaNumeroSerie').rows.length;
		linha	= document.getElementById('tabelaNumeroSerie').insertRow(tam);
			
		linha.accessKey = id; 
		
		c0	= linha.insertCell(0);	

		c0.id = 'NumeroSerie_'+id;
		
		c0.innerHTML = "<input type='hidden' name='NSerie_"+id+"' value=''><input type='hidden' name='NSerieObrigatorio_"+id+"' value=''><textarea name='NumeroSerie_"+id+"' style='width: 327px;' rows=5 onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out');\"></textarea>";
		c0.style.display = 'none';
	}
	function ocultarNumeroSerie(id){
		var i=0,posIni=0,posFim=0;
		for(i=0;i<document.formulario.length;i++){
			if(document.formulario[i].name.substr(0,12) == 'NumeroSerie_'){
				if(posIni==0){
					posIni	=	i;
					posFim	=	i;
				}else{
					posFim	=	i;
				}
			}
		}
		for(i=posIni;i<=posFim;i=i+3){
			var temp	=	document.formulario[i].name.split('_');	
			if(temp[1] != id){
				if(document.getElementById("NumeroSerie_"+temp[1]).style.display == 'block'){
					document.getElementById("NumeroSerie_"+temp[1]).style.display = 'none';
				}
			}else{
				document.formulario[i].focus();
			}
		}
	}
	function cancelarNumeroSerie(){
		if(document.formulario.IdMovimentacaoProduto.value == ''){
			var i=0,posIni=0,posFim=0;
			for(i=0;i<document.formulario.length;i++){
				if(document.formulario[i].name.substr(0,12) == 'NumeroSerie_'){
					if(posIni==0){
						posIni	=	i;
						posFim	=	i;
					}else{
						posFim	=	i;
					}
				}
			}
			for(i=posIni;i<=posFim;i=i+3){
				var temp	=	document.formulario[i].name.split('_');	
				if(temp[1] == document.formulario.NumeroSerieTemp.value){
					document.formulario[i].value	=	'';
					i = posFim;
				}
			}
		}
	}
	function validarNumeroSerie(){
		var i=0,posIni=0,posFim=0;
		for(i=0;i<document.formulario.length;i++){
			if(document.formulario[i].name.substr(0,10) == 'IdProduto_'){
				if(posIni==0){
					posIni	=	i;
					posFim	=	i;
				}else{
					posFim	=	i;
				}
			}
		}
		var qtd,id;
		
		id	=	document.formulario.NumeroSerieTemp.value;
		
		for(i=posIni;i<=posFim;i=i+9){
			if(document.formulario[i].name.substr(0,10) == 'IdProduto_'){
				var temp	=	document.formulario[i].name.split('_');	
				if(temp[1] == id){
					qtd = parseInt(document.formulario[i+3].value);
				}
			}
		}
		
		
		posIni=0,posFim=0;
		for(i=0;i<document.formulario.length;i++){
			if(document.formulario[i].name.substr(0,12) == 'NumeroSerie_'){
				if(posIni==0){
					posIni	=	i;
					posFim	=	i;
				}else{
					posFim	=	i;
				}
			}
		}
		
		var tam = 0, serie, qtdTemp	=	0;
		
		for(i=posIni;i<=posFim;i=i+3){
			var temp	=	document.formulario[i].name.split('_');	
			if(temp[1] == id){
				
				serie		=	document.formulario[i].value.split('\n');
				tam			=	parseInt(serie.length);
				qtdTemp		=	0;
				qtdTemp2	=	0;
				cont		=	0;
				serieTemp	=	new Array();
				novoSerie	=	'';
				
				//Retira Espaços e Enter
				while(cont<tam){
					
					serie[cont] = trim(serie[cont]);
					
					if(serie[cont]!=''){
						serieTemp[qtdTemp]	=	serie[cont];
						
						qtdTemp++;				
					}
					
					cont++;
				}
				
				//Retira Numero Repetido
				novoSerie	=	removeDuplicado(serieTemp,true);
				qtdTemp2		=	novoSerie.length;
				
				if(qtdTemp != qtdTemp2){
					alert("Atenção! Existe número de séries repetido.");
					document.formulario[i].focus();
					break;
				}else if(qtd != qtdTemp){
					alert("Atenção! A quantidade de número de séries não confere.");
					document.formulario[i].focus();
					break;
				}else{
					vi_id('quadroBuscaNumeroSerie', false);
				}
				
				i = posFim;
			}
		}
	}
	function excluirProduto(id){
		var numMsg = '';
		if(document.getElementById('tabelaProduto').rows.length > 2){
			for(var i=0; i<document.getElementById('tabelaProduto').rows.length; i++){
				if(id == document.getElementById('tabelaProduto').rows[i].accessKey){
					document.getElementById('tabelaProduto').deleteRow(i);
					calcula_somatorio_produto();
					calcula_total_nf();
					
					i = document.getElementById('tabelaProduto').rows.length;
				}
			}
			for(var i=0; i<document.getElementById('tabelaNumeroSerie').rows.length; i++){
				if(id == document.getElementById('tabelaNumeroSerie').rows[i].accessKey){
					document.getElementById('tabelaNumeroSerie').deleteRow(i);
					
					i = document.getElementById('tabelaNumeroSerie').rows.length;
				}
			}
			var i=0,posIni=0,posFim=0;
			for(i=0;i<document.formulario.length;i++){
				if(document.formulario[i].name.substr(0,10) == 'IdProduto_'){
					if(posIni==0){
						posIni	=	i;
						posFim	=	i;
					}else{
						posFim	=	i;
					}
				}
			}
			document.formulario.Produtos.value	=	'';	
			
			for(i=posIni;i<=posFim;i=i+9){
				if(document.formulario[i].name.substr(0,10) == 'IdProduto_'){
					var temp	=	document.formulario[i].name.split('_');	
					if(temp[1] != id){
						if(document.formulario.Produtos.value != ''){
							if(document.formulario[i].value!=''){
								document.formulario.Produtos.value	+=	'#';	
							}
						}
						if(document.formulario[i].value!=''){
							document.formulario.Produtos.value	+=	document.formulario[i].value;
						}
					}
				}
			}
		}else{
			mensagens(93);
		}
	}

	function limpa_form_produto(pos){
		var i=0,posIni=0,posFim=0;
		for(i=0;i<document.formulario.length;i++){
			if(document.formulario[i].name.substr(0,10) == 'IdProduto_'){
				if(posIni==0){
					posIni = i;
					posFim = i;
				}else{
					posFim = i;
				}
			}
		}
		
		var id;
		
		for(i=posIni;i<=posFim;i=i+9){
			if(document.formulario[i].name.substr(0,10) == 'IdProduto_'){
				id	=	document.formulario[i].name.split('_');
				
				if(id[1] == pos){
					//alert(document.formulario[i].value);
					document.formulario[i].value	=	'';
					document.formulario[i+1].value	=	'';
					document.formulario[i+2].value	=	'';
					document.formulario[i+3].value	=	'';
					document.formulario[i+4].value	=	'';
					document.formulario[i+5].value	=	'';
					document.formulario[i+6].value	=	'';
					document.formulario[i+7].value	=	'';
					document.formulario[i+8].value	=	'';
					
					document.formulario[i].focus();
					
					calcula_somatorio_produto();
					
					i = posFim;
				}
			}
		} 
		
		posIni=0,posFim=0;
		for(i=0;i<document.formulario.length;i++){
			if(document.formulario[i].name.substr(0,7) == 'NSerie_'){
				if(posIni==0){
					posIni = i;
					posFim = i;
				}else{
					posFim = i;
				}
			}
		}
		
		for(i=posIni;i<=posFim;i=i+3){
			if(document.formulario[i].name.substr(0,7) == 'NSerie_'){
				id	=	document.formulario[i].name.split('_');
				
				if(id[1] == pos){
					document.formulario[i].value	=	'';
					document.formulario[i+1].value	=	'';
					document.formulario[i+2].value	=	'';
					
					document.getElementById('icoNSerie'+pos).src	=	'../../img/estrutura_sistema/ico_serie.gif';
					i = posFim;
				}
			}
		} 
	}
	
	function calcula_total_produto(pos){
		var i=0,posIni=0,posFim=0;
		for(i=0;i<document.formulario.length;i++){
			if(document.formulario[i].name.substr(0,10) == 'IdProduto_'){
				if(posIni==0){
					posIni = i;
					posFim = i;
				}else{
					posFim = i;
				}
			}
		}
		
		var id,qtd,valor,total;
		
		for(i=posIni;i<=posFim;i=i+9){
			if(document.formulario[i].name.substr(0,10) == 'IdProduto_'){
				id	=	document.formulario[i].name.split('_');
				
				if(id[1] == pos){
					if(document.formulario[i+3].value != '' && document.formulario[i+4].value != ''){
						qtd		=	document.formulario[i+3].value;
						qtd		=	new String(qtd);
						qtd		=	qtd.replace('.','');
						qtd		=	qtd.replace('.','');
						qtd		=	qtd.replace('.','');
						qtd		=	qtd.replace('.','');
						qtd		=	qtd.replace(',','.');
						
						valor	=	document.formulario[i+4].value;
						valor	=	new String(valor);
						valor	=	valor.replace('.','');
						valor	=	valor.replace('.','');
						valor	=	valor.replace('.','');
						valor	=	valor.replace('.','');
						valor	=	valor.replace(',','.');
						
						total	=	parseFloat(qtd)*parseFloat(valor);
						
						document.formulario[i+5].value	=	formata_float(Arredonda(total,2),2).replace('.',',');
					
						calcula_somatorio_produto();
						calcula_valor_ipi(pos);
					}else{
						if(document.formulario.ValorTotalProduto.value == ''){
							document.formulario[i+5].value					=	'';
							document.formulario.ValorTotalProduto.value		=	'';
							document.formulario.ValorBaseCalculoICMS.value	=	'';
						}
					}
					
					i = posFim;
				}
			}
		}
	}
	
	function calcula_valor_ipi(pos){
		var i=0,posIni=0,posFim=0;
		for(i=0;i<document.formulario.length;i++){
			if(document.formulario[i].name.substr(0,10) == 'IdProduto_'){
				if(posIni==0){
					posIni = i;
					posFim = i;
				}else{
					posFim = i;
				}
			}
		}
		
		var id,ipi,valor,total;
		
		for(i=posIni;i<=posFim;i=i+9){
			if(document.formulario[i].name.substr(0,10) == 'IdProduto_'){
				id	=	document.formulario[i].name.split('_');
				
				if(id[1] == pos){
					if(document.formulario[i+5].value != '' && document.formulario[i+7].value != ''){
						ipi		=	document.formulario[i+7].value;
						ipi		=	new String(ipi);
						ipi		=	ipi.replace('.','');
						ipi		=	ipi.replace('.','');
						ipi		=	ipi.replace('.','');
						ipi		=	ipi.replace('.','');
						ipi		=	ipi.replace(',','.');
						
						total	=	document.formulario[i+5].value;
						total	=	new String(total);
						total	=	total.replace('.','');
						total	=	total.replace('.','');
						total	=	total.replace('.','');
						total	=	total.replace('.','');
						total	=	total.replace(',','.');
						
						valor	=	(parseFloat(total)*parseFloat(ipi))/100;
						
						//Arrendodar Valor
						document.formulario[i+8].value	=	formata_float(Arredonda(valor,2),2).replace('.',',');
						
						//Sem Arrendodar Valor
						/*var tempValor	=	new String(valor);	
						var decimal 	=   tempValor.split('.');
						if(decimal[1]!=""){
							tempValor	=	decimal[0] +'.'+decimal[1].substr(0,2);
							valor		=	parseFloat(tempValor);
						}
						
						document.formulario[i+8].value	=	formata_float(valor,2).replace('.',',');
						*/	
						
						calcula_somatorio_produto();
					}else{
						document.formulario[i+8].value			=	'';
						document.formulario.ValorTotalIPI.value	=	'';
					}
					
					i = posFim;
				}
			}
		}
	}
	
	function calcula_somatorio_produto(){
		var i=0,posIni=0,posFim=0;
		for(i=0;i<document.formulario.length;i++){
			if(document.formulario[i].name.substr(0,10) == 'IdProduto_'){
				if(posIni==0){
					posIni = i;
					posFim = i;
				}else{
					posFim = i;
				}
			}
		}
		
		var valor,total=0, totalipi=0,totalicms=0,cont=0,contipi=0,ipi,valorNF=0,conticms=0,icms;
		
		for(i=posIni;i<=posFim;i=i+9){
			if(document.formulario[i].name.substr(0,10) == 'IdProduto_'){
				if(document.formulario[i+5].value != ''){
					valor	=	document.formulario[i+5].value;
					valor	=	new String(valor);
					valor	=	valor.replace('.','');
					valor	=	valor.replace('.','');
					valor	=	valor.replace('.','');
					valor	=	valor.replace('.','');
					valor	=	valor.replace(',','.');
							
					total	+=	parseFloat(valor);
					
					cont++;
				}
				if(document.formulario[i+6].value != ''){
					icms	=	document.formulario[i+6].value;
					icms	=	new String(icms);
					icms	=	icms.replace('.','');
					icms	=	icms.replace('.','');
					icms	=	icms.replace('.','');
					icms	=	icms.replace('.','');
					icms	=	icms.replace(',','.');
							
					totalicms	+=	(parseFloat(valor)*parseFloat(icms))/100;
					
					conticms++;
				}
				if(document.formulario[i+8].value != ''){
					ipi		=	document.formulario[i+8].value;
					ipi		=	new String(ipi);
					ipi		=	ipi.replace('.','');
					ipi		=	ipi.replace('.','');
					ipi		=	ipi.replace('.','');
					ipi		=	ipi.replace('.','');
					ipi		=	ipi.replace(',','.');
							
					totalipi	+=	parseFloat(ipi);
					
					contipi++;
				}
			}
		} 
		
		valorNF	=	total + totalipi;
		
		if(cont != 0){
			document.formulario.ValorTotalProduto.value		=	formata_float(Arredonda(total,2),2).replace('.',',');
			document.formulario.ValorBaseCalculoICMS.value	=	formata_float(Arredonda(total,2),2).replace('.',',');
		}
		if(contipi != 0){
			document.formulario.ValorTotalIPI.value			=	formata_float(Arredonda(totalipi,2),2).replace('.',',');
		}
		if(conticms != 0){
			document.formulario.ValorTotalICMSTemp.value	=	formata_float(Arredonda(totalicms,2),2).replace('.',',');
			//document.formulario.ValorTotalICMS.value		=	formata_float(Arredonda(totalicms,2),2).replace('.',',');			
		}
		if(cont != 0 || contipi != 0){
			document.formulario.ValorTotalNF.value			=	formata_float(Arredonda(valorNF,2),2).replace('.',',');
			document.formulario.ValorTotalNFTemp.value		=	formata_float(Arredonda(valorNF,2),2).replace('.',',');
		}
	}
	function calcula_total_nf(){
		var seguro=0,frete=0,despesas=0,valor=0,total=0;
		
		if(document.formulario.ValorFrete.value != ''){
			frete	=	document.formulario.ValorFrete.value;
		}
		frete	=	new String(frete);
		frete	=	frete.replace('.','');
		frete	=	frete.replace('.','');
		frete	=	frete.replace('.','');
		frete	=	frete.replace('.','');
		frete	=	frete.replace(',','.');
		
		if(document.formulario.ValorSeguro.value != ''){
			seguro	=	document.formulario.ValorSeguro.value;
		}
		seguro	=	new String(seguro);
		seguro	=	seguro.replace('.','');
		seguro	=	seguro.replace('.','');
		seguro	=	seguro.replace('.','');
		seguro	=	seguro.replace('.','');
		seguro	=	seguro.replace(',','.');
		
		if(document.formulario.ValorOutrasDespesas.value != ''){
			despesas	=	document.formulario.ValorOutrasDespesas.value;
		}
		despesas	=	new String(despesas);
		despesas	=	despesas.replace('.','');
		despesas	=	despesas.replace('.','');
		despesas	=	despesas.replace('.','');
		despesas	=	despesas.replace('.','');
		despesas	=	despesas.replace(',','.');
		
		if(document.formulario.ValorTotalNFTemp.value != ''){
			valor	=	document.formulario.ValorTotalNFTemp.value;
		}
		valor	=	new String(valor);
		valor	=	valor.replace('.','');
		valor	=	valor.replace('.','');
		valor	=	valor.replace('.','');
		valor	=	valor.replace('.','');
		valor	=	valor.replace(',','.');
		
		total	=	parseFloat(valor) + parseFloat(despesas) + parseFloat(seguro) + parseFloat(frete);
		
		if(valor==0 && despesas==0 && seguro == 0 && frete == 0 && total == 0){
			document.formulario.ValorTotalNF.value	=	"";
		}else{
			document.formulario.ValorTotalNF.value	=	formata_float(Arredonda(total,2),2).replace('.',',');
		}
	}
