	function cancelar(IdNotaFiscal, IdContaReceber, IdStatus){
		if(cancelar_registro() == true){
			if(IdStatus == 0){
				return false;
			}
			
			var url = "xml/nota_fiscal_cancelar.php?IdContaReceber="+IdContaReceber+"&IdNotaFiscal="+IdNotaFiscal;
			
			call_ajax(url,function (xmlhttp){
				var numMsg = parseInt(xmlhttp.responseText);
				
				if(numMsg == 67){ // registro cancelado com sucesso
					for(var i = 0; i < document.getElementById('tableListar').rows.length; i++){
						if(IdNotaFiscal == document.getElementById('tableListar').rows[i].cells[0].innerHTML){
							document.getElementById('tableListar').rows[i].cells[10].innerHTML = document.getElementById('tableListar').rows[i].cells[10].innerHTML.replace(/ico_del.gif/g, "ico_del_c.gif");
							document.getElementById('tableListar').rows[i].cells[9].innerHTML = 'Cancelado';
							tableMultColor('tableListar',document.filtro.corRegRand.value);
							break;
						}
					}
					
					mensagens(numMsg);
				} else{
					if(numMsg == 68){ // erro ao cancelar registro
						mensagens(numMsg);
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
/*	function excluir(IdNotaFiscal){
		return false;
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
    
   			url = "files/excluir/excluir_nota_fiscal.php?IdNotaFiscal="+IdNotaFiscal;
			xmlhttp.open("GET", url,true);
	
			xmlhttp.onreadystatechange = function(){ 

				// Carregando...
				carregando(true);

				if(xmlhttp.readyState == 4){ 
					if(xmlhttp.status == 200){					
						var numMsg = parseInt(xmlhttp.responseText);
						mensagens(numMsg);
						if(numMsg == 7){
							var aux = 0, valor=0;
							for(var i=0; i<document.getElementById('tableListar').rows.length; i++){
								if(IdNotaFiscal == document.getElementById('tableListar').rows[i].accessKey){
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
					// Fim de Carregando
					carregando(false);
				}
				return true;
			}
			xmlhttp.send(null);
		}
	}
	
*/ 
	function exportar_notas_fiscais(modelo,periodo,formato){
		if(periodo.value == ""){
			periodo.focus();
			mensagens(1);
			return false;
		}
		if(formato == ""){
			formato.focus();
			mensagens(1);
			return false;
		}
		window.location = "rotinas/exportar_nota_fiscal.php?IdLayoutNotaFiscal="+modelo.value+"&Periodo="+periodo.value+"&FormatoExportacao="+formato;
	}

	function filtro_buscar_servico(IdServico){
		if(IdServico == '' || IdServico == undefined){
			IdServico = 0;
		}
	    
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
