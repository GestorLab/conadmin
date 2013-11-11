	function verificaAcao(){
		if(document.formulario != undefined){
			if(document.formulario.Acao.value == 'inserir'){
				document.formulario.bt_inserir.disabled		= false;
				document.formulario.bt_alterar.disabled		= true;
				document.formulario.bt_excluir.disabled 	= true;
				document.formulario.bt_processar.disabled 	= true;
				document.formulario.bt_enviar.disabled 		= true;
				document.formulario.bt_cancelar.disabled 	= true;
				document.formulario.bt_lista_email.disabled	= true;
			} else{
				document.formulario.bt_inserir.disabled		= true;
				document.formulario.bt_lista_email.disabled	= false;
				
				switch(Number(document.formulario.IdStatus.value)){
					case 1:
						document.formulario.bt_alterar.disabled		= false;
						document.formulario.bt_excluir.disabled 	= false;
						document.formulario.bt_processar.disabled 	= false;
						document.formulario.bt_enviar.disabled 		= true;
						document.formulario.bt_cancelar.disabled 	= true;
						document.formulario.bt_lista_email.disabled = true;
						break;
					case 2:
						document.formulario.bt_alterar.disabled		= true;
						document.formulario.bt_excluir.disabled 	= true;
						document.formulario.bt_processar.disabled 	= true;
						document.formulario.bt_enviar.disabled 		= false;
						document.formulario.bt_cancelar.disabled 	= false;
				}
			}
		}
	}
	function cadastrar(Acao){
		if(validar()){
			document.formulario.Acao.value = Acao;
			document.formulario.submit();
		}
	}
	function validar(){
		if(document.formulario.DescricaoMalaDireta.value == ''){
			mensagens(1);
			document.formulario.DescricaoMalaDireta.focus();
			return false;
		}
		
		if(document.formulario.IdTipoConteudo.value == ''){
			mensagens(1);
			document.formulario.IdTipoConteudo.focus();
			return false;
		}
		
		switch(document.formulario.IdTipoConteudo.value){
			case'1':
				if(document.formulario.AltAnexoArquivo.value == "1"){
					if(document.formulario.EndArquivoAnexo.value == ''){
						mensagens(1);
						document.formulario.fakeupload_ArquivoAnexo.focus();
						return false;
					}
					
					var temp = document.formulario.EndArquivoAnexo.value.split('.');
					var ext = temp[temp.length-1].toLowerCase();
					
					if(!document.formulario.ExtAnexoArquivo.value.split(',').in_array(ext) && ext != ''){
						mensagens(10);
						document.formulario.fakeupload_ArquivoAnexo.focus();
						return false;
					}
				}
				break;
			case '2':
				if(document.formulario.IdTipoMensagemMalaDiretaEnviada.value == ''){
					mensagens(1);
					document.formulario.IdModeloMalaDireta.focus();
					return false;
				}
				break;
			case '3':
				if(document.formulario.TextoAvulso.value == ''){
					mensagens(1);
					document.formulario.TextoAvulso.focus();
					return false;
				}
				break;
		}
		
		if(document.formulario.IdContaEmail.value == ''){
			mensagens(1);
			document.formulario.IdContaEmail.focus();
			return false;
		}
		
		return true;
	}
	function verifica_extensao(){
		var temp = document.formulario.EndArquivoAnexo.value.split('.');
		var ext = temp[temp.length-1].toLowerCase();
		
		if(!document.formulario.ExtAnexoArquivo.value.split(',').in_array(ext)){
			mensagens(10);
			document.formulario.fakeupload_ArquivoAnexo.focus();
		} else{
			mensagens(0);
		}
	}
	function excluir(IdMalaDireta,IdStatus){
		if(IdMalaDireta == '' || IdMalaDireta == undefined){
			var IdMalaDireta = document.formulario.IdMalaDireta.value;
		}
		
		if(IdStatus != 1){
			return false;
		}
		
		if(excluir_registro() == true){
			if(document.formulario != undefined){
				if(document.formulario.Acao.value == "inserir"){
					return false;
				}
			}
			
			var url = "./files/excluir/excluir_mala_direta.php?IdMalaDireta=" + IdMalaDireta;
			
			call_ajax(url, function (xmlhttp){
				if(document.formulario != undefined){
					document.formulario.Erro.value = xmlhttp.responseText;
					
					if(parseInt(xmlhttp.responseText) == 7){
						document.formulario.Acao.value = "inserir";
						url = "cadastro_mala_direta.php?Erro=" + document.formulario.Erro.value;
						window.location.replace(url);
					} else{
						verificaErro();
					}
				} else{
					var numMsg = parseInt(xmlhttp.responseText);
					mensagens(numMsg);
					
					if(numMsg == 7){
						var aux = 0, valor = 0;
						
						for(var i = 0; i < document.getElementById("tableListar").rows.length; i++){
							if(IdMalaDireta == document.getElementById("tableListar").rows[i].accessKey){
								document.getElementById("tableListar").deleteRow(i);
								tableMultColor("tableListar", document.filtro.corRegRand.value);
								aux = 1;
								break;
							}
						}
						
						if(aux == 1){
							document.getElementById("tableListarTotal").innerHTML = "Total: "+(document.getElementById("tableListar").rows.length-2);
						}								
					}
				}
			});
		}
	}
	function atualizar_campo(campo){
		var IdMalaDireta = document.formulario.IdMalaDireta.value;
		
		if(IdMalaDireta != ''){
			var url = "./xml/mala_direta.php?IdMalaDireta=" + IdMalaDireta;
			
			call_ajax(url, function (xmlhttp){
				var nameNode, nameTextNode;
				
				if(xmlhttp.responseText != "false"){
					nameNode = xmlhttp.responseXML.getElementsByTagName("LogEnvio")[0]; 
					nameTextNode = nameNode.childNodes[0];
					var LogEnvio = nameTextNode.nodeValue;
					
					switch(campo.name){
						case "Descricao":
							campo.value = LogEnvio;
							break;
					}
				}
			});
		}
	}
	function remover_filtro_pessoa(IdPessoa){
		for(var i = 0; i < document.getElementById("tabelaPessoa").rows.length; i++){
			if(IdPessoa == document.getElementById("tabelaPessoa").rows[i].accessKey){
				document.getElementById("tabelaPessoa").deleteRow(i);
				tableMultColor("tabelaPessoa");
				break;
			}
		}
		
		var tempFiltro = document.formulario.Filtro_IdPessoa.value.split(',');
		var novoFiltro = '';
		var ii = 0;
		
		while(tempFiltro[ii] != undefined){
			if(tempFiltro[ii] != IdPessoa){
				if(novoFiltro == ''){
					novoFiltro = tempFiltro[ii];
				} else{
					novoFiltro = novoFiltro + "," + tempFiltro[ii];
				}
			}
			
			ii=ii+1;
		}
		
		document.formulario.Filtro_IdPessoa.value = novoFiltro;
		document.getElementById('totaltabelaPessoa').innerHTML = "Total: " + (ii-1);
	}
	function addStatus(IdStatusContrato,ListarCampo){
		if(IdStatusContrato != "" && IdStatusContrato != undefined){
		    var url = "xml/parametro_sistema.php?IdGrupoParametroSistema=69&IdParametroSistema="+IdStatusContrato;
			
			call_ajax(url, function (xmlhttp){
				var nameNode, nameTextNode;
				
				if(xmlhttp.responseText == 'false'){
					document.formulario.IdStatusContrato.value = '';
				} else{
					var cont = 0; ii='';
					
					if(ListarCampo == '' || ListarCampo == undefined){
						if(document.formulario.Filtro_IdStatusContrato.value == ''){
							document.formulario.Filtro_IdStatusContrato.value = IdStatusContrato;
							ii = 0;
						} else{
							var tempFiltro = document.formulario.Filtro_IdStatusContrato.value.split(',');
							ii=0; 
							
							while(tempFiltro[ii] != undefined){
								if(tempFiltro[ii] != IdStatusContrato){
									cont++;
								}
								
								ii++;
							}
							
							if(ii == cont){
								document.formulario.Filtro_IdStatusContrato.value = document.formulario.Filtro_IdStatusContrato.value + "," + IdStatusContrato;
							}
						}
					} else{
						ii=0;
					}
					
					if(ii == cont){
						nameNode = xmlhttp.responseXML.getElementsByTagName("IdParametroSistema")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var IdParametroSistema = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("ValorParametroSistema")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var ValorParametroSistema = nameTextNode.nodeValue;
						
						var tam, linha, c0, c1, c2, c3, c4, c5, c6, c7, c8, c9, c10, c11;
						
						tam 	= document.getElementById('tabelaStatus').rows.length;
						linha	= document.getElementById('tabelaStatus').insertRow(tam-1);
						
						if(tam%2 != 0){
							linha.style.backgroundColor = "#E2E7ED";
						}
						
						linha.accessKey = IdParametroSistema; 
						
						c0 = linha.insertCell(0);
						c1 = linha.insertCell(1);
						c2 = linha.insertCell(2);
						
						c0.innerHTML = IdParametroSistema;
						c0.style.padding = "0 0 0 5px";
						
						c1.innerHTML = ValorParametroSistema;
						
						if(document.formulario.IdStatus.value == 1){
							c2.innerHTML = "<img src='../../img/estrutura_sistema/ico_del.gif' alt='Excluir?' onClick=\"remover_filtro_status("+IdStatusContrato+")\"></tr>";
						} else{
							c2.innerHTML = "<img src='../../img/estrutura_sistema/ico_del_c.gif' alt='Excluir?'\">";
						}
						
						c2.style.textAlign = "center";
						c2.style.cursor = "pointer";
						
						document.getElementById('totaltabelaStatus').innerHTML = "Total: " + (ii+1);
					}
					
					document.formulario.IdStatusContrato.value = "";
				}
			});
		} else{
			document.formulario.IdStatusContrato.value = '';
			document.formulario.IdStatusContrato.focus();
		}
	}
	function remover_filtro_status(IdStatusContrato){
		for(var i = 0; i < document.getElementById('tabelaStatus').rows.length; i++){
			if(IdStatusContrato == document.getElementById('tabelaStatus').rows[i].accessKey){
				document.getElementById('tabelaStatus').deleteRow(i);
				tableMultColor('tabelaStatus');
				break;
			}
		}
		
		var tempFiltro = document.formulario.Filtro_IdStatusContrato.value.split(',');
		var novoFiltro = '';
		var ii = 0;
		
		while(tempFiltro[ii] != undefined){
			if(tempFiltro[ii] != IdStatusContrato){
				if(novoFiltro == ''){
					novoFiltro = tempFiltro[ii];
				} else{
					novoFiltro = novoFiltro + "," + tempFiltro[ii];
				}
			}
			
			ii=ii+1;
		}
		
		document.formulario.Filtro_IdStatusContrato.value = novoFiltro;
		document.getElementById('totaltabelaStatus').innerHTML = "Total: " + (ii-1);
	}
	function addContrato(){
		if(document.formulario.IdContrato.value != ""){
			busca_contrato(document.formulario.IdContrato.value, false, 'AdicionarMalaDireta')
		} else{
			document.formulario.IdContrato.value				= "";
			document.formulario.IdServicoContrato.value			= "";
			document.formulario.DescricaoServicoContrato.value	= "";
			document.formulario.DescPeriodicidadeContrato.value	= "";
			document.formulario.DescTipoContrato.value			= "";
			
			document.formulario.IdContrato.focus();
		}
	}
	function remover_filtro_contrato(IdContrato){
		for(var i = 0; i < document.getElementById("tabelaContrato").rows.length; i++){
			if(IdContrato == document.getElementById("tabelaContrato").rows[i].accessKey){
				document.getElementById("tabelaContrato").deleteRow(i);
				tableMultColor("tabelaContrato");
				break;
			}
		}
		
		var tempFiltro = document.formulario.Filtro_IdContrato.value.split(',');
		var novoFiltro = '';
		var ii = 0;
		
		while(tempFiltro[ii] != undefined){
			if(tempFiltro[ii] != IdContrato){
				if(novoFiltro == ''){
					novoFiltro = tempFiltro[ii];
				} else{
					novoFiltro = novoFiltro + "," + tempFiltro[ii];
				}
			}
			
			ii++;
		}
		
		document.formulario.Filtro_IdContrato.value = novoFiltro;
		document.getElementById('totaltabelaContrato').innerHTML = "Total: " + (ii-1);
	}
	function remover_filtro_pessoa(IdPessoa){
		for(var i = 0; i < document.getElementById("tabelaPessoa").rows.length; i++){
			if(IdPessoa == document.getElementById("tabelaPessoa").rows[i].accessKey){
				document.getElementById("tabelaPessoa").deleteRow(i);
				tableMultColor("tabelaPessoa");
				break;
			}
		}
		
		var tempFiltro = document.formulario.Filtro_IdPessoa.value.split(',');
		var novoFiltro = '';
		var ii = 0;
		
		while(tempFiltro[ii] != undefined){
			if(tempFiltro[ii] != IdPessoa){
				if(novoFiltro == ''){
					novoFiltro = tempFiltro[ii];
				} else{
					novoFiltro = novoFiltro + "," + tempFiltro[ii];
				}
			}
			
			ii++;
		}
		
		document.formulario.Filtro_IdPessoa.value = novoFiltro;
		document.getElementById('totaltabelaPessoa').innerHTML = "Total: " + (ii-1);
	}
	function remover_filtro_processo_financeiro(IdProcessoFinanceiro){
		for(var i = 0; i < document.getElementById("tabelaProcessoFinanceiro").rows.length; i++){
			if(IdProcessoFinanceiro == document.getElementById("tabelaProcessoFinanceiro").rows[i].accessKey){
				document.getElementById("tabelaProcessoFinanceiro").deleteRow(i);
				tableMultColor("tabelaProcessoFinanceiro");
				break;
			}
		}
		
		var tempFiltro = document.formulario.Filtro_IdProcessoFinanceiro.value.split(',');
		var novoFiltro = '';
		var ii = 0;
		
		while(tempFiltro[ii] != undefined){
			if(tempFiltro[ii] != IdProcessoFinanceiro){
				if(novoFiltro == ''){
					novoFiltro = tempFiltro[ii];
				} else{
					novoFiltro = novoFiltro + "," + tempFiltro[ii];
				}
			}
			
			ii++;
		}
		
		document.formulario.Filtro_IdProcessoFinanceiro.value = novoFiltro;
		document.getElementById('totaltabelaProcessoFinanceiro').innerHTML = "Total: " + (ii-1);
	}
	function remover_filtro_grupo_pessoa(IdGrupoPessoa){
		for(var i = 0; i < document.getElementById("tabelaGrupoPessoa").rows.length; i++){
			if(IdGrupoPessoa == document.getElementById("tabelaGrupoPessoa").rows[i].accessKey){
				document.getElementById("tabelaGrupoPessoa").deleteRow(i);
				tableMultColor("tabelaGrupoPessoa");
				break;
			}
		}
		
		var tempFiltro = document.formulario.Filtro_IdGrupoPessoa.value.split(',');
		var novoFiltro = '';
		var ii = 0;
		
		while(tempFiltro[ii] != undefined){
			if(tempFiltro[ii] != IdGrupoPessoa){
				if(novoFiltro == ''){
					novoFiltro = tempFiltro[ii];
				} else{
					novoFiltro = novoFiltro + "," + tempFiltro[ii];
				}
			}
			
			ii++;
		}
		
		document.formulario.Filtro_IdGrupoPessoa.value = novoFiltro;
		document.getElementById('totaltabelaGrupoPessoa').innerHTML = "Total: " + (ii-1);
	}
	function habilitar_campo(valor){
		document.formulario.fakeupload_ArquivoAnexo.value = '';
		document.formulario.EndArquivoAnexo.value = '';
		document.formulario.TextoAvulso.value = '';
		document.getElementById("cpButtonArquivoAnexo").style.display = "none";
		
		if(document.formulario.Acao.value != 'alterar'){
			busca_mala_direta_enviada(0,false);
		}
		
		switch(valor){
			case '1':	// ANEXAR ARQUIVO
				document.getElementById("titTextAvulso").style.display = "none";
				document.getElementById("titModeloMalaDireta").style.display = "none";
				document.getElementById("cpModeloMalaDireta").style.display = "none";
				
				if(document.formulario.AltAnexoArquivo.value == 1){
					visualizacao_html();
					
					document.getElementById("titArquivoAnexo").style.display = "block";
					document.getElementById("cpArquivoAnexo").style.display = "block";
					document.getElementById("cpButtonArquivoAnexo").style.display = "none";
				} else{
					document.getElementById("titArquivoAnexo").style.display = "none";
					document.getElementById("cpArquivoAnexo").style.display = "none";
					document.getElementById("cpButtonArquivoAnexo").style.display = "block";
				}
				break;
			case '2':	// MODELO MALA DIRETA
				visualizacao_html();
				
				document.getElementById("titArquivoAnexo").style.display = "none";
				document.getElementById("cpArquivoAnexo").style.display = "none";
				document.getElementById("titTextAvulso").style.display = "none";
				document.getElementById("titModeloMalaDireta").style.display = "block";
				document.getElementById("cpModeloMalaDireta").style.display = "block";
				break;
			case '3':	// TEXTO AVULSO
				visualizacao_html();
				visualizacao_texto_avulso('')
				
				document.getElementById("titArquivoAnexo").style.display = "none";
				document.getElementById("cpArquivoAnexo").style.display = "none";
				document.getElementById("titTextAvulso").style.display = "block";
				document.getElementById("titModeloMalaDireta").style.display = "none";
				document.getElementById("cpModeloMalaDireta").style.display = "none";
				break;
			default:
				visualizacao_html();
				
				document.getElementById("titArquivoAnexo").style.display = "none";
				document.getElementById("cpArquivoAnexo").style.display = "none";
				document.getElementById("titTextAvulso").style.display = "none";
				document.getElementById("titModeloMalaDireta").style.display = "none";
				document.getElementById("cpModeloMalaDireta").style.display = "none";
		}
	}
	function visualizacao_texto_avulso(valor){
		document.getElementById('VisualizacaoTextoAvulso').contentDocument.body.innerHTML = "<html><head><style type='text/css'>body { margin:0; padding:0 4px 0 4px; }</style></head><body>" + valor + "</body></html>";
	}
	function visualizacao_html(campo,CodeHTML){
		if(document.getElementById("cpVisualizarHTML").style.display == "none" && CodeHTML != '' && CodeHTML != undefined){
			document.getElementById("cpVisualizarHTML").style.display = "block";
			
			if(!(/<html>/).test(CodeHTML)){
				CodeHTML = "<html><head><style type='text/css'>body { margin:0; padding:0 4px 0 4px; }</style></head><body>" + CodeHTML + "</body></html>";	
			}
			
			document.getElementById("VisualizacaoHTML").contentDocument.body.innerHTML = CodeHTML;
			
			if(campo == undefined || campo == null){
				document.formulario.bt_visualizar_arquivo_anexo.value = "Ocultar";
				document.formulario.bt_visualizar_mala_direta.value = "Ocultar";
			} else{
				campo.value = "Ocultar";
			}
		} else{
			document.getElementById("cpVisualizarHTML").style.display = "none";
			document.getElementById("VisualizacaoHTML").contentDocument.body.innerHTML = "<html><head><style type='text/css'>body { margin:0; padding:0 4px 0 4px; }</style></head><body></body></html>";
			
			if(campo == undefined || campo == null){
				document.formulario.bt_visualizar_arquivo_anexo.value = "Visualizar";
				document.formulario.bt_visualizar_mala_direta.value = "Visualizar";
			} else{
				campo.value = "Visualizar";
			}
		}
	}
	function alterar_modelo_mala_direta(){
		visualizacao_html();
		
		document.getElementById("titModeloMalaDiretaBusca").style.display = "block";
		document.getElementById("cpModeloMalaDiretaBusca").style.display = "block";
		document.getElementById("titTipoMensagem").style.display = "none";
		document.getElementById("cpTipoMensagem").style.display = "none";
		document.getElementById("titModeloMalaDireta").style.margin = "0 0 0 18px";
	}
	function listar_filtro_pessoa(Filtro_IdPessoa){
		document.getElementById("cp_filtro_pessoa").style.display = "block";
		document.getElementById("totaltabelaPessoa").innerHTML = "Total: 0";
		document.formulario.Filtro_IdPessoa.value = '';
		
		if(Number(document.formulario.IdStatus.value) != 1){
			document.formulario.IdPessoa.readOnly = true;
			document.formulario.bt_add_pessoa.disabled = true;
			document.formulario.IdPessoa.onfocus = function (){};
		} else{
			document.formulario.IdPessoa.readOnly = false;
			document.formulario.bt_add_pessoa.disabled = false;
			document.formulario.IdPessoa.onfocus = function (){ Foco(this,'in'); };
		}
		
		while(document.getElementById('tabelaPessoa').rows.length > 2){
			document.getElementById('tabelaPessoa').deleteRow(1);
		}
		
		if(Filtro_IdPessoa != '' && Filtro_IdPessoa != undefined){
			Filtro_IdPessoa = Filtro_IdPessoa.split(',');
			
			for(var i = 0; i < Filtro_IdPessoa.length; i++){
				busca_pessoa(Filtro_IdPessoa[i], false, 'AdicionarMalaDireta');
			}
		} else{
			if(Number(document.formulario.IdStatus.value) != 1){
				document.getElementById("cp_filtro_pessoa").style.display = "none";
			}
		}
	}
	function listar_filtro_grupo_pessoa(Filtro_IdGrupoPessoa){
		document.getElementById("cp_filtro_grupo_pessoa").style.display = "block";
		document.getElementById("totaltabelaGrupoPessoa").innerHTML = "Total: 0";
		document.formulario.Filtro_IdGrupoPessoa.value = '';
		
		if(Number(document.formulario.IdStatus.value) != 1){
			document.formulario.IdGrupoPessoa.readOnly = true;
			document.formulario.bt_add_grupo_pessoa.disabled = true;
			document.formulario.IdGrupoPessoa.onfocus = function (){};
		} else{
			document.formulario.IdGrupoPessoa.readOnly = false;
			document.formulario.bt_add_grupo_pessoa.disabled = false;
			document.formulario.IdGrupoPessoa.onfocus = function (){ Foco(this,'in'); };
		}
		
		while(document.getElementById('tabelaGrupoPessoa').rows.length > 2){
			document.getElementById('tabelaGrupoPessoa').deleteRow(1);
		}
		
		if(Filtro_IdGrupoPessoa != '' && Filtro_IdGrupoPessoa != undefined){
			Filtro_IdGrupoPessoa = Filtro_IdGrupoPessoa.split(',');
			
			for(var i = 0; i < Filtro_IdGrupoPessoa.length; i++){
				busca_grupo_pessoa(Filtro_IdGrupoPessoa[i], false, 'AdicionarMalaDireta');
			}
		} else{
			if(Number(document.formulario.IdStatus.value) != 1){
				document.getElementById("cp_filtro_grupo_pessoa").style.display = "none";
			}
		}
	}
	function listar_filtro_servico(Filtro_IdServico){
		document.getElementById("cp_filtro_servico").style.display = "block";
		document.getElementById("totaltabelaServico").innerHTML = "Total: 0";
		document.formulario.Filtro_IdServico.value = '';
		
		if(Number(document.formulario.IdStatus.value) != 1){
			document.formulario.IdServico.readOnly = true;
			document.formulario.bt_add_servico.disabled = true;
			document.formulario.IdServico.onfocus = function (){};
		} else{
			document.formulario.IdServico.readOnly = false;
			document.formulario.bt_add_servico.disabled = false;
			document.formulario.IdServico.onfocus = function (){ Foco(this,'in'); };
		}
		
		while(document.getElementById('tabelaServico').rows.length > 2){
			document.getElementById('tabelaServico').deleteRow(1);
		}
		
		if(Filtro_IdServico != '' && Filtro_IdServico != undefined){
			Filtro_IdServico = Filtro_IdServico.split(',');
			
			for(var i = 0; i < Filtro_IdServico.length; i++){
				busca_servico(Filtro_IdServico[i], false, 'AdicionarMalaDireta', 'busca');
			}
		} else{
			if(Number(document.formulario.IdStatus.value) != 1){
				document.getElementById("cp_filtro_servico").style.display = "none";
			}
		}
	}
	function listar_filtro_contrato(Filtro_IdContrato){
		document.getElementById("cp_filtro_contrato").style.display = "block";
		document.getElementById("totaltabelaContrato").innerHTML = "Total: 0";
		document.formulario.Filtro_IdContrato.value = '';
		
		if(Number(document.formulario.IdStatus.value) != 1){
			document.formulario.IdContrato.readOnly = true;
			document.formulario.bt_add_contrato.disabled = true;
			document.formulario.IdContrato.onfocus = function (){};
		} else{
			document.formulario.IdContrato.readOnly = false;
			document.formulario.bt_add_contrato.disabled = false;
			document.formulario.IdContrato.onfocus = function (){ Foco(this,'in'); };
		}
		
		while(document.getElementById('tabelaContrato').rows.length > 2){
			document.getElementById('tabelaContrato').deleteRow(1);
		}
		
		if(Filtro_IdContrato != '' && Filtro_IdContrato != undefined){
			Filtro_IdContrato = Filtro_IdContrato.split(',');
			
			for(var i = 0; i < Filtro_IdContrato.length; i++){
				busca_contrato(Filtro_IdContrato[i], false, 'AdicionarMalaDireta');
			}
		} else{
			if(Number(document.formulario.IdStatus.value) != 1){
				document.getElementById("cp_filtro_contrato").style.display = "none";
			}
		}
	}
	function listar_filtro_status_contrato(Filtro_IdStatusContrato){
		document.getElementById("cp_filtro_status").style.display = "block";
		document.getElementById("totaltabelaStatus").innerHTML = "Total: 0";
		document.formulario.Filtro_IdStatusContrato.value = '';
		
		if(Number(document.formulario.IdStatus.value) != 1){
			document.formulario.IdStatusContrato.disabled = true;
			document.formulario.bt_add_status.disabled = true;
			document.formulario.IdStatusContrato.onfocus = function (){};
		} else{
			document.formulario.IdStatusContrato.disabled = false;
			document.formulario.bt_add_status.disabled = false;
			document.formulario.IdStatusContrato.onfocus = function (){ Foco(this,'in'); };
		}
		
		while(document.getElementById('tabelaStatus').rows.length > 2){
			document.getElementById('tabelaStatus').deleteRow(1);
		}
		
		if(Filtro_IdStatusContrato != '' && Filtro_IdStatusContrato != undefined){
			Filtro_IdStatusContrato = Filtro_IdStatusContrato.split(',');
			
			for(var i = 0; i < Filtro_IdStatusContrato.length; i++){
				addStatus(Filtro_IdStatusContrato[i], '');
			}
		} else{
			if(Number(document.formulario.IdStatus.value) != 1){
				document.getElementById("cp_filtro_status").style.display = "none";
			}
		}
	}
	function listar_filtro_processo_financeiro(Filtro_IdProcessoFinanceiro){
		document.getElementById("cp_filtro_processo_financeiro").style.display = "block";
		document.getElementById("totaltabelaProcessoFinanceiro").innerHTML = "Total: 0";
		document.formulario.Filtro_IdProcessoFinanceiro.value = '';
		
		if(Number(document.formulario.IdStatus.value) != 1){
			document.formulario.IdProcessoFinanceiro.readOnly = true;
			document.formulario.bt_add_processo_financeiro.disabled = true;
			document.formulario.IdProcessoFinanceiro.onfocus = function (){};
		} else{
			document.formulario.IdProcessoFinanceiro.readOnly = false;
			document.formulario.bt_add_processo_financeiro.disabled = false;
			document.formulario.IdProcessoFinanceiro.onfocus = function (){ Foco(this,'in'); };
		}
		
		while(document.getElementById('tabelaProcessoFinanceiro').rows.length > 2){
			document.getElementById('tabelaProcessoFinanceiro').deleteRow(1);
		}
		
		if(Filtro_IdProcessoFinanceiro != '' && Filtro_IdProcessoFinanceiro != undefined){
			Filtro_IdProcessoFinanceiro = Filtro_IdProcessoFinanceiro.split(',');
			
			for(var i = 0; i < Filtro_IdProcessoFinanceiro.length; i++){
				busca_processo_financeiro(Filtro_IdProcessoFinanceiro[i], false, 'AdicionarMalaDireta');
			}
		} else{
			if(Number(document.formulario.IdStatus.value) != 1){
				document.getElementById("cp_filtro_processo_financeiro").style.display = "none";
			}
		}
	}
	function listar_filtro_cidade(Filtro_IdPaisEstadoCidade){
		document.getElementById("cp_filtro_cidade").style.display = "block";
		document.getElementById("totaltabelaCidade").innerHTML = "Total: 0";
		document.formulario.Filtro_IdPaisEstadoCidade.value = '';
		
		if(Number(document.formulario.IdStatus.value) != 1){
			document.formulario.IdPais.readOnly = true;
			document.formulario.bt_add_cidade.disabled = true;
			document.formulario.IdPais.onfocus = function (){};
		} else{
			document.formulario.IdPais.readOnly = false;
			document.formulario.bt_add_cidade.disabled = false;
			document.formulario.IdPais.onfocus = function (){ Foco(this,'in'); };
		}
		
		while(document.getElementById('tabelaCidade').rows.length > 2){
			document.getElementById('tabelaCidade').deleteRow(1);
		}
		
		if(Filtro_IdPaisEstadoCidade != '' && Filtro_IdPaisEstadoCidade != undefined){
			Filtro_IdPaisEstadoCidade = Filtro_IdPaisEstadoCidade.split('^');
			
			for(var i = 0; i < Filtro_IdPaisEstadoCidade.length; i++){
				IdPaisEstadoCidadeTemp = Filtro_IdPaisEstadoCidade[i].split(',');
				
				busca_cidade(IdPaisEstadoCidadeTemp[0], IdPaisEstadoCidadeTemp[1], IdPaisEstadoCidadeTemp[2], false, 'AdicionarMalaDireta');
			}
		} else{
			if(Number(document.formulario.IdStatus.value) != 1){
				document.getElementById("cp_filtro_cidade").style.display = "none";
			}
		}
	}
	function listar_lista_email(Ocultar){
		document.getElementById("tabelaListaEmailTotal").innerHTML = "Total: 0";
		document.formulario.bt_lista_email.value = "Visualizar Lista de E-mail";
		
		while(document.getElementById('tabelaListaEmail').rows.length > 2){
			document.getElementById('tabelaListaEmail').deleteRow(1);
		}
		
		if(Ocultar == undefined){
			Ocultar = false
		}
		
		if(document.getElementById("cp_lista_email").style.display == "block" || Ocultar){
			document.getElementById("cp_lista_email").style.display = "none";
		} else{
			document.getElementById("cp_lista_email").style.display = "none";
			var url = "xml/mala_direta_email.php?IdMalaDireta="+document.formulario.IdMalaDireta.value;
			
			call_ajax(url,function (xmlhttp){
				if(xmlhttp.responseText != "false"){
					for(var i = 0; i < xmlhttp.responseXML.getElementsByTagName("IdMalaDireta").length; i++){
						var nameNode = xmlhttp.responseXML.getElementsByTagName("IdMalaDireta")[i]; 
						var nameTextNode = nameNode.childNodes[0];
						var IdMalaDireta = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("IdPessoa")[i]; 
						nameTextNode = nameNode.childNodes[0];
						var IdPessoa = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("Nome")[i]; 
						nameTextNode = nameNode.childNodes[0];
						var Nome = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("RazaoSocial")[i]; 
						nameTextNode = nameNode.childNodes[0];
						var RazaoSocial = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("Email")[i]; 
						nameTextNode = nameNode.childNodes[0];
						var Email = nameTextNode.nodeValue;
						
						if(RazaoSocial != ''){
							Nome = RazaoSocial;
						}
						
						var tam = document.getElementById('tabelaListaEmail').rows.length;
						var linha = document.getElementById('tabelaListaEmail').insertRow(tam-1);
						var LinkIni = "";
						var LinkFim = "";
						
						if(IdPessoa != ''){
							LinkIni = "<a href='cadastro_pessoa.php?IdPessoa="+IdPessoa+"' target='_blank'>";
							LinkFim = "</a>";
						}
						
						if((tam % 2) != 0){
							linha.style.backgroundColor = "#E2E7ED";
						}
						
						linha.accessKey = IdMalaDireta; 
						
						var c0 = linha.insertCell(0);
						var c1 = linha.insertCell(1);
						var c2 = linha.insertCell(2);
						
						c0.innerHTML = LinkIni+Nome+LinkFim;
						c0.className = "tableListarEspaco";
						
						c1.innerHTML = LinkIni+Email+LinkFim;
					}
					
					document.getElementById("tabelaListaEmailTotal").innerHTML = "Total: "+i;
					document.getElementById("cp_lista_email").style.display = "block";
					document.formulario.bt_lista_email.value = "Ocultar Lista de E-mail";
					
					scrollWindow('bottom');
				}
			});
		}
	}