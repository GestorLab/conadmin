	function validar(){
		if(document.formulario.IdContaReceber.value==''){
			mensagens(1);
			document.formulario.IdContaReceber.focus();
			return false;
		}
		if(document.formulario.IdContaReceberRecebimento.value==''){
			mensagens(1);
			document.formulario.IdContaReceberRecebimento.focus();
			return false;
		}
		mensagens(0);
		return true;
	}
	function verificaAcao(){
		if(document.formulario != undefined){
			if(document.formulario.Acao.value=='inserir'){
				document.formulario.bt_cancelar.disabled		= true;
			}
			if(document.formulario.Acao.value=='alterar'){
				if(document.formulario.IdStatusRecebimento.value=='0'){
					document.formulario.bt_cancelar.disabled	= true;
				} else{
					if(/*document.formulario.CaixaAtivado.value == "1" && */document.formulario.IdCaixa.value != ""){
						document.formulario.bt_cancelar.disabled	= true;
					} else {
						document.formulario.bt_cancelar.disabled	= false;
					}
				}
			}
		}	
	}	
	function cadastrar(){
		if(validar()==true){
			window.location.replace("cadastro_cancelar_conta_receber_recebimento.php?IdContaReceber="+document.formulario.IdContaReceber.value+"&IdContaReceberRecebimento="+document.formulario.IdContaReceberRecebimento.value+"");
		}
	}
	function inicia(){
		document.formulario.IdContaReceberRecebimento.focus();
	}	
	function voltar(){
		window.location.replace("cadastro_conta_receber.php?IdContaReceber="+document.formulario.IdContaReceber.value);
	}	
	function listarRecebimento(IdContaReceber,Erro,IdContaReceberRecebimentoTemp){
		if(IdContaReceber == undefined || IdContaReceber==''){
			IdContaReceber = 0;
		}
		if(IdContaReceberRecebimentoTemp == undefined){
			IdContaReceberRecebimentoTemp = '';
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
	    
	   	url = "xml/conta_receber_recebimento.php?IdContaReceber="+IdContaReceber;
		xmlhttp.open("GET", url,true);

		xmlhttp.onreadystatechange = function(){ 
	
			// Carregando...
			carregando(true);
	
			if(xmlhttp.readyState == 4){ 
				if(xmlhttp.status == 200){
					if(xmlhttp.responseText == 'false'){
						document.getElementById('totalValorDesconto').innerHTML				=	"0,00";						
						document.getElementById('totalValorRecebido').innerHTML				=	"0,00";	
						document.getElementById('totalRecebimentos').innerHTML				=	"Total: 0";	
						
						// Fim de Carregando
						carregando(false);
					}else{
						while(document.getElementById('tabelaRecebimentos').rows.length > 2){
							document.getElementById('tabelaRecebimentos').deleteRow(1);
						}
						
						var tam, linha, c0, c1, c2, c3, c4;
						var RecebimentoDuplicado,IdContaReceberRecebimento,DataRecebimento,ValorDesconto,ValorRecebido,Valor,DescricaoLocalRecebimento,TotalDesc=0,IdRecibo,IdCaixa,TotalReceb=0;
						for(var i=0; i<xmlhttp.responseXML.getElementsByTagName("IdContaReceberRecebimento").length; i++){	
							nameNode = xmlhttp.responseXML.getElementsByTagName("IdContaReceberRecebimento")[i]; 
							nameTextNode = nameNode.childNodes[0];
							IdContaReceberRecebimento = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("DataRecebimento")[i]; 
							nameTextNode = nameNode.childNodes[0];
							DataRecebimento = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("DataLancamento")[i]; 
							nameTextNode = nameNode.childNodes[0];
							DataLancamento = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("ValorDescontoRecebimento")[i]; 
							nameTextNode = nameNode.childNodes[0];
							ValorDescontoRecebimento = nameTextNode.nodeValue;	
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("ValorRecebido")[i]; 
							nameTextNode = nameNode.childNodes[0];
							ValorRecebido = nameTextNode.nodeValue;	
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("ValorContaReceber")[i]; 
							nameTextNode = nameNode.childNodes[0];
							ValorContaReceber = nameTextNode.nodeValue;	
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoLocalRecebimento")[i]; 
							nameTextNode = nameNode.childNodes[0];
							DescricaoLocalRecebimento = nameTextNode.nodeValue;	
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("IdCaixa")[i]; 
							nameTextNode = nameNode.childNodes[0];
							IdCaixa = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("IdCaixaMovimentacao")[i]; 
							nameTextNode = nameNode.childNodes[0];
							IdCaixaMovimentacao = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("IdRecibo")[i]; 
							nameTextNode = nameNode.childNodes[0];
							IdRecibo = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("IdStatus")[i]; 
							nameTextNode = nameNode.childNodes[0];
							IdStatus = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("IdLoja")[i]; 
							nameTextNode = nameNode.childNodes[0];
							IdLoja = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("MD5")[i]; 
							nameTextNode = nameNode.childNodes[0];
							MD5 = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("Obs")[i]; 
							nameTextNode = nameNode.childNodes[0];
							Obs = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("RecebimentoDuplicado")[i]; 
							nameTextNode = nameNode.childNodes[0];
							RecebimentoDuplicado = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("linkImpresso")[i]; 
							nameTextNode = nameNode.childNodes[0];
							linkImpresso = nameTextNode.nodeValue;
														
							if(ValorDescontoRecebimento == '')  ValorDescontoRecebimento = '0.00';
							if(ValorRecebido == '')				ValorRecebido = '0.00';
							
							TotalDesc	=	TotalDesc +	parseFloat(ValorDescontoRecebimento);
							TotalReceb	=	TotalReceb + parseFloat(ValorRecebido);
							
							tam 	= document.getElementById('tabelaRecebimentos').rows.length;
							linha	= document.getElementById('tabelaRecebimentos').insertRow(tam-1);
							
							if(tam%2 != 0){
								linha.style.backgroundColor = "#E2E7ED";
							}
							
							if(IdContaReceberRecebimentoTemp == IdContaReceberRecebimento){
								linha.style.backgroundColor = "#A0C4EA";
							}
							
							if(parseFloat(ValorContaReceber) > parseFloat(ValorRecebido) || dataConv(DataRecebimento, 'Y-m-d', 'Ymd') < dataConv(DataLancamento, 'Y-m-d', 'Ymd') || RecebimentoDuplicado == 1){
								linha.style.backgroundColor = document.formulario.CorRecebidoDesc.value;
							}
							
							if(IdStatus == 0 || IdStatus == 3){ /*Cancelado || Estorno*/
								linha.style.backgroundColor = "#FFD2D2";
							}
							
							linha.accessKey = IdContaReceberRecebimento; 
							
							c0	= linha.insertCell(0);	
							c1	= linha.insertCell(1);	
							c2	= linha.insertCell(2);	
							c3	= linha.insertCell(3);
							c4	= linha.insertCell(4);
							c5	= linha.insertCell(5);
							c6	= linha.insertCell(6);
							
							var linkIni = "<a href='#' onClick=\"busca_conta_receber_recebimento("+IdContaReceber+","+IdContaReceberRecebimento+",false,'"+document.formulario.Local.value+"'); listarRecebimento("+IdContaReceber+",false,"+IdContaReceberRecebimento+")\">";
							var linkFim = "</a>";
							
							c0.innerHTML = linkIni + IdContaReceberRecebimento + linkFim;
							c0.style.cursor  = "pointer";
							c0.style.padding =	"0 0 0 5px";
							
							c1.innerHTML = linkIni + dateFormat(DataRecebimento) + linkFim;
							c1.style.cursor = "pointer";
							
							c2.innerHTML = linkIni + ValorDescontoRecebimento.replace('.',',')+ linkFim + "&nbsp;&nbsp;" ;
							c2.style.cursor = "pointer";
							c2.style.textAlign = "right";
							
							c3.innerHTML = linkIni + ValorRecebido.replace('.',',') + linkFim + "&nbsp;&nbsp;";
							c3.style.cursor = "pointer";
							c3.style.textAlign = "right";
							
							c4.innerHTML = linkIni + DescricaoLocalRecebimento + linkFim;
							c4.style.cursor = "pointer";
							
							c5.style.width = "120px";
							if(IdStatus == 1){
								c5.innerHTML = "<a href='recibo.php?Recibo="+MD5+"' target='_blank'>"+IdRecibo+"</a>";
							}else{
								if(IdStatus == 3){
									c5.innerHTML = "<a href='estorno.php?"+linkImpresso+"' target='_blank'>"+IdCaixa+""+IdCaixaMovimentacao+" - Estorno</a>";
								}else{
									c5.innerHTML = "Canc.";
								}
							}
							c5.style.cursor = "pointer";
							
							if(IdStatus == 1 && document.formulario.PermissaoCancelarRecebimento.value == 1 && (document.formulario.CaixaAtivado.value != "1" || IdCaixa == "")){
								c6.innerHTML    = "<a href='cadastro_cancelar_conta_receber_recebimento.php?IdContaReceber="+IdContaReceber+"&IdContaReceberRecebimento="+IdContaReceberRecebimento+"'><img src='../../img/estrutura_sistema/ico_del.gif' alt='Cancelar?'></a>";
							}else{
								c6.innerHTML    = "<img src='../../img/estrutura_sistema/ico_del_c.gif' alt='Cancelar?'>";
							}
							c6.style.cursor = "pointer";
                            
							if(document.formulario.PermissaoCancelarRecebimento.value == 1 && (document.formulario.CaixaAtivado.value != "1" || IdCaixa == "")){
                                document.formulario.bt_cancelar.disabled = false;
                            }else{
                                document.formulario.bt_cancelar.disabled = true;
                            }
						}
						document.getElementById('totalValorDesconto').innerHTML		=	formata_float(Arredonda(TotalDesc,2),2).replace('.',',');	
						document.getElementById('totalValorRecebido').innerHTML		=	formata_float(Arredonda(TotalReceb,2),2).replace('.',',');	
						document.getElementById('totalRecebimentos').innerHTML		=	"Total: "+i;	
					}
					if(window.janela != undefined){
						window.janela.close();
					}
				}
				// Fim de Carregando
				carregando(false);
			} 
			return true;
		}
		xmlhttp.send(null);
	} 
	function cadastro_vencimento(){
		if(document.formulario.IdContaReceber.value!=""){
			location.replace('cadastro_conta_receber_vencimento.php?IdContaReceber='+document.formulario.IdContaReceber.value);
		}
	}
	function filtro_buscar_servico(IdServico){
		if(IdServico == '' || IdServico == undefined){
			IdServico = 0;
		}
		
	    var url = "xml/servico.php?IdServico="+IdServico+"&IdStatus=1&IdTipoServico=1";
		
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
