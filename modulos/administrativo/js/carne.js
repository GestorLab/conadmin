$j(document).ready(function(){
	$j("input[name=bt_cancelar]").on("click", function(){
		var cont = 0;
		var className = "";
		mensagens(0);
		$j(".obrig").each(function(index){
			if($j(this).attr("type") == "checkbox"){
				if($j(this).is(":checked")){
					cont++;
				}else{
					className = "." + $j(this).attr("class") + ":eq("+index+")";
				}
			}else if($j(this).attr("name") == "ObsCancelamento"){
				if($j(this).val() == "" && cont >  0){
					className = "." + $j(this).attr("class") + ":eq("+index+")";
					$j(className).focus();
					mensagens(1);
					return false;
				}else if($j(this).val() != "" && cont > 0){
					className = "." + $j(this).attr("class") + ":eq("+index+")";
				}
			}else if($j(".obrig:eq("+index+")").attr("disabled") != "disabled" && cont >  0){
				if($j(".obrig:eq("+index+") option:selected").val() == 0){
					className = ".obrig:eq("+index+")";
					$j(className).focus();
					mensagens(1);
					return false;
				}
				
			}
		});
		if($j(className).attr("type") == "checkbox"){
			if(cont == 0){
				$j(className).focus();
				mensagens(1);
				return false;
			}else if(cont >  0){
				mensagens(0);
			}
		}
		
		if($j(className).attr("name") == "ObsCancelamento" && cont > 0){
			if($j(className).val() != ""){
				$j("#form").submit();
			}
		}
		
	});
});	
var ContExecucao = 0;
	
	function cancelar(IdCarne,IdStatus){
		if(IdCarne=='' || IdStatus == 0){
			return false;
		}
		url = 'cadastro_carne.php?IdCarne='+IdCarne;
		window.location.replace(url);
	} 
	function verificaAcao(){
		if(document.formulario != undefined){
			if(document.formulario.Acao.value=='inserir'){			
				document.formulario.bt_cancelar.disabled = true;
			}
			if(document.formulario.Acao.value=='cancelar'){			
				document.formulario.bt_cancelar.disabled = false;
			}
		}	
	}
	function inicia(){
		document.formulario.IdCarne.focus();
	}
	function busca_carne(IdCarne,Erro,Local){
		if(IdCarne == '' || IdCarne == undefined){
			IdCarne = 0;
		}
		
		if(Local == '' || Local == undefined){
			Local = document.formulario.Local.value;
		}
		
		document.formulario.todos_cr.checked = false;
		var url = "xml/carne.php?IdCarne="+IdCarne;
		
		selecionar(document.formulario.todos_cr);
		call_ajax(url,function (xmlhttp){
			if(Erro != false){
				document.formulario.Erro.value = 0;
				verificaErro();
			}
			
			if(xmlhttp.responseText == 'false'){
				document.formulario.IdCarne.value							= "";
				document.formulario.IdPessoa.value 							= '';
				document.formulario.IdPessoaF.value 						= '';
				document.formulario.Nome.value 								= '';
				document.formulario.NomeF.value 							= '';
				document.formulario.RazaoSocial.value 						= '';
				document.formulario.CPF.value 								= '';
				document.formulario.CNPJ.value 								= '';
				document.formulario.Email.value 							= '';
				document.formulario.ObsCancelamento.value					= '';
				document.formulario.LancamentoFinanceiroTipoContrato.value	= '';
				document.formulario.Acao.value 								= 'inserir';		
				
				document.getElementById('cp_juridica').style.display	= 'block';
				document.getElementById('cp_fisica').style.display		= 'none';
				document.getElementById('tabelaTotalValor').innerHTML	= "0,00";	
				document.getElementById('tabelaTotalReceb').innerHTML	= "0,00";	
				document.getElementById('tabelaTotal').innerHTML		= "Total: 0";	
				document.getElementById('cpVoltarDataBase').innerHTML	= '';	
				
				addParmUrl("marCarne","IdCarne",'');
				status_inicial();
				
				while(document.getElementById('tabelaContaReceber').rows.length > 2){
					document.getElementById('tabelaContaReceber').deleteRow(1);
				}

				verificaAcao();
			} else{
				var nameNode = xmlhttp.responseXML.getElementsByTagName("IdCarne")[0]; 
				var nameTextNode = nameNode.childNodes[0];
				var IdCarne = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("IdPessoa")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var IdPessoa = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("IdContaReceber")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var IdContaReceber = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("LancamentoFinanceiroTipoContrato")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var LancamentoFinanceiroTipoContrato = nameTextNode.nodeValue;
				
				addParmUrl("marCarne","IdCarne",IdCarne);
				busca_pessoa(IdPessoa,false,document.formulario.Local.value);
				
				while(document.getElementById('tabelaContaReceber').rows.length > 2){
					document.getElementById('tabelaContaReceber').deleteRow(1);
				}
				
				listar_conta_receber(IdCarne);

				document.formulario.IdCarne.value							= IdCarne;
				document.formulario.IdPessoa.value							= IdPessoa;
				document.formulario.LancamentoFinanceiroTipoContrato.value	= LancamentoFinanceiroTipoContrato;
				document.formulario.Acao.value 								= 'cancelar';

				verificaAcao();
			}
			
			if(window.janela != undefined){
				window.janela.close();
			}
		});
	}
	function listar_conta_receber(IdCarne){
		if(IdCarne == undefined || IdCarne==''){
			IdCarne = 0;
		}
		
	   	var url = "xml/conta_receber.php?Local=Carne&IdStatusValido=1&IdCarne="+IdCarne;
		
		call_ajax(url,function (xmlhttp){
			//alert(xmlhttp.responseText);
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
				
				var tam, linha, c0, c1, c2, c3, c4, c5, c6, c7, c8, c9, c10, tabindex = Number(document.formulario.TabIndex.value);
				var nameNode,nameTextNode,IdContaReceber,NumeroDocumento,NumeroNF,AbreviacaoNomeLocalCobranca,DataLancamento,Valor,DataVencimento,ValorRecebido,DataRecebimento,DescricaoLocalRecebimento,TotalValor=0,TotalReceb=0;
				
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
					IdStatusRecebimento = nameTextNode.nodeValue;
					
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
					
					c0.innerHTML = "<input class='obrig' style='border:0' type='checkbox' name='cr_"+IdContaReceber+"' value="+NumeroDocumento+" onClick='selecionar(this)' tabindex='"+(tabindex+i)+"' />";
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
				}
				
				document.formulario.TabIndex.value						= (tabindex+i);
				document.getElementById('tabelaTotalValor').innerHTML	= formata_float(Arredonda(TotalValor,2),2).replace('.',',');	
				document.getElementById('tabelaTotalReceb').innerHTML	= formata_float(Arredonda(TotalReceb,2),2).replace('.',',');	
				document.getElementById('tabelaTotal').innerHTML		= "Total: "+i;	
			}
			
			if(window.janela != undefined){
				window.janela.close();
			}
		});
	}
	function validar(){
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
		
		for(i = posInicial; i <= posFinal; i += 8){
			alert(document.formulario[i+1].value+" "+document.formulario[i+1].name);
			if((document.formulario[i+1].value == "" || document.formulario[i+1].value == "0") && document.formulario[i+1].disabled == false){
				mensagens(1);
				document.formulario[i+1].focus();
				return false;
			}
		}
		
		if(document.formulario.ObsCancelamento.value == ''){
			mensagens(1);
			document.formulario.ObsCancelamento.focus();
			return false;
		}
		
		return true;
	}
	
	(function($j){
		$j.teste = function(value, id, nameClass){
			//alert(nameClass);
			nameClass = nameClass.split(" ");
			nameClass = nameClass[0];
			id = id.split("_");
			nameId = id[0];
			id = parseInt(id[1]);
			//alert(id);
			//alert($j("."+nameClass + ":eq("+id+")").parent().parent().index("tr"));
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
		var NumDoc = new Array;
		var j = 0;
		
		if(buscar == undefined){
			buscar = true;
		}
		
		if(campo.name == "todos_cr"){
			var Checked = campo.checked;
			
			
			for(var i = 0; i < table.rows.length; i++){
				var AccessKey = table.rows[i].accessKey;
				//alert(campo.value);
				if(AccessKey != '' && AccessKey != undefined){
					//alert(campo.checked);
					eval("var campo = document.formulario.cr_"+AccessKey+", valor_checked = "+Checked+"; if(campo.checked != valor_checked) { campo.checked = valor_checked; selecionar(campo,false); }");
					if(campo.checked){
						NumDoc[j] = campo.value;
						j++;
					}
					
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
			NumDoc = campo.value;
		}
		if(buscar){
			var CancelarContaReceber = document.formulario.CancelarContaReceber.value;
			
			if(CancelarContaReceber == ''){
				CancelarContaReceber = 0;
			}
			
			busca_lancamentos_data_base(CancelarContaReceber, NumDoc);
		}
	}