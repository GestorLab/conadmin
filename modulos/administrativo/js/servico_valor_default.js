	function busca_servico_valor(IdServico, Erro, Local, DataInicioCond) {
		if(IdServico == '') {
			IdServico = 0;
		}
		
		if(DataInicioCond == '' || DataInicioCond == undefined) {
			DataInicioCond = 0;
		} else {
			var tam	= document.getElementById('tabelaValor').rows.length;
			
			for(var i = 0; i < tam; i++) {
				if(document.getElementById('tabelaValor').rows[i].accessKey == DataInicioCond) {
					document.getElementById('tabelaValor').rows[i].style.backgroundColor = "#A0C4EA";
				} else {
					if((i % 2) == 0 && i != 0 && i != (tam - 1)) {
						document.getElementById('tabelaValor').rows[i].style.backgroundColor = "#E2E7ED";
					} else if((i % 2) != 0 && i != 0 && i != (tam - 1)) {
						document.getElementById('tabelaValor').rows[i].style.backgroundColor = "#FFF";
					}
				}
			}
		}
		
		var url = "xml/servico_valor.php?IdServico="+IdServico+"&DataInicio="+DataInicioCond;
		
		call_ajax(url, function (xmlhttp) {
			if(Erro != false) {
				document.formulario.Erro.value = 0;
			}
			
			if(xmlhttp.responseText == 'false') {
				document.formulario.DataTermino.value						= "";
				document.formulario.DescricaoServicoValor.value				= "";
				document.formulario.Valor.value								= "";
				document.formulario.MultaFidelidade.value					= "";
				document.formulario.IdContratoTipoVigencia[0].selected		= true;
				document.formulario.DataCriacao.value						= "";
				document.formulario.LoginCriacao.value						= "";
				document.formulario.DataAlteracao.value						= "";
				document.formulario.LoginAlteracao.value					= "";
				document.getElementById("cpValorMulta").style.color			= "#000";
				document.getElementById('tabelahelpText2').style.display	= 'none';
				document.formulario.Acao.value								= 'inserir';
				
				addParmUrl("marServicoValorNovo","DataInicio","");
				status_inicial();
				document.formulario.DataInicio.focus();
			} else {
				var nameNode, nameTextNode, DadosValor, DataInicio, DataTermino, Valor, DescricaoServicoValor, DataCriacao, LoginCriacao, DataAlteracao, LoginAlteracao;
				
				for(var i = 0; i < xmlhttp.responseXML.getElementsByTagName("DataInicio").length; i++){
					nameNode = xmlhttp.responseXML.getElementsByTagName("DataInicio")[i]; 
					nameTextNode = nameNode.childNodes[0];
					DataInicio = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("DataTermino")[i]; 
					nameTextNode = nameNode.childNodes[0];
					DataTermino = nameTextNode.nodeValue;	
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoServicoValor")[i]; 
					nameTextNode = nameNode.childNodes[0];
					DescricaoServicoValor = nameTextNode.nodeValue;					
			
					nameNode = xmlhttp.responseXML.getElementsByTagName("Valor")[i]; 
					nameTextNode = nameNode.childNodes[0];
					Valor = nameTextNode.nodeValue;
					
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("IdTipoServico")[i]; 
					nameTextNode = nameNode.childNodes[0];
					IdTipoServico = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("MultaFidelidade")[i]; 
					nameTextNode = nameNode.childNodes[0];
					MultaFidelidade = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("IdContratoTipoVigencia")[i]; 
					nameTextNode = nameNode.childNodes[0];
					IdContratoTipoVigencia = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("DataCriacao")[i]; 
					nameTextNode = nameNode.childNodes[0];
					DataCriacao = nameTextNode.nodeValue;
				
					nameNode = xmlhttp.responseXML.getElementsByTagName("LoginCriacao")[i]; 
					nameTextNode = nameNode.childNodes[0];
					LoginCriacao = nameTextNode.nodeValue;					
				
					nameNode = xmlhttp.responseXML.getElementsByTagName("DataAlteracao")[i]; 
					nameTextNode = nameNode.childNodes[0];
					DataAlteracao = nameTextNode.nodeValue;
				
					nameNode = xmlhttp.responseXML.getElementsByTagName("LoginAlteracao")[i]; 
					nameTextNode = nameNode.childNodes[0];
					LoginAlteracao = nameTextNode.nodeValue;
					
					if(Valor == '') {
						Valor = 0;
					}
					
					if(MultaFidelidade	==	'') {
						MultaFidelidade = 0;
					}
					
					addParmUrl("marServicoValor","DataInicio",DataInicio);
					
					if(DataInicioCond != '') {
						document.formulario.DataInicio.value				= dateFormat(DataInicio);
						document.formulario.DataTermino.value 				= dateFormat(DataTermino);
						document.formulario.DescricaoServicoValor.value 	= DescricaoServicoValor;
						document.formulario.Valor.value						= formata_float(Valor).replace(/\./i,",");
						document.formulario.ValorAnterior.value				= formata_float(Valor).replace(/\./i,",");
						document.formulario.MultaFidelidade.value			= formata_float(MultaFidelidade).replace(/\./i,",");
						document.formulario.IdContratoTipoVigencia.value	= IdContratoTipoVigencia;
						document.formulario.DataCriacao.value				= dateFormat(DataCriacao);
						document.formulario.LoginCriacao.value				= LoginCriacao;
						document.formulario.DataAlteracao.value				= dateFormat(DataAlteracao);
						document.formulario.LoginAlteracao.value			= LoginAlteracao;
						document.formulario.Acao.value						= "alterar";
					}
				}
			}	
			
			if(window.janela != undefined) {
				window.janela.close();
			}
			
			verificaAcao();
		});
	}
	function busca_servico_valor_bloquear(IdServico, DataInicioCond) {
		if(IdServico == '') {
			IdServico = 0;
		}
		
		if(DataInicioCond == '' || DataInicioCond == undefined) {
			DataInicioCond = 0;
		}
		
		var url = "xml/servico_valor.php?IdServico="+IdServico+"&DataInicio="+DataInicioCond;
		
		call_ajax(url, function (xmlhttp) {
						
			if(xmlhttp.responseText != 'false') {
				var nameNode, nameTextNode, DataInicio, DataInicioTemp;
				
				for(var i = 0; i < xmlhttp.responseXML.getElementsByTagName("DataInicio").length; i++){
					nameNode = xmlhttp.responseXML.getElementsByTagName("DataInicio")[i]; 
					nameTextNode = nameNode.childNodes[0];
					DataInicio = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("DataInicioTemp")[i]; 
					nameTextNode = nameNode.childNodes[0];
					DataInicioTemp = nameTextNode.nodeValue;
					
					document.formulario.DataInicioTemp.value = DataInicioTemp;
					
					if(DataInicioTemp != ""){
						servico_valor_bloquear(DataInicioCond,DataInicioTemp);
					}
				}
			}
		});
	}
	function servico_valor_bloquear(mes,mesTemp){
		if(mes == mesTemp){
			document.getElementById('DataInicio').style.color = "#C10000";
			document.getElementById('Valor').style.color = "#C10000";
			document.getElementById('TipoVigencia').style.color = "#C10000";
			if(document.formulario.maxQtdMesesFidelidade.value > 0) {
				document.getElementById("cpValorMulta").style.color	= "#C10000";
			} else {
				document.getElementById("cpValorMulta").style.color	= "#000";
			}
			
			document.formulario.DataInicio.readOnly = false;
			document.formulario.DataTermino.readOnly = false;
			document.formulario.Valor.readOnly = false;
			document.formulario.MultaFidelidade.readOnly = false;
			document.formulario.DescricaoServicoValor.readOnly = false;
			
			document.formulario.IdContratoTipoVigencia.disabled = false;
			
			document.formulario.icoDataInicio.setAttribute("src","../../img/estrutura_sistema/ico_date.gif");
			document.formulario.icoDataTermino.setAttribute("src","../../img/estrutura_sistema/ico_date.gif");
			
			document.formulario.DataInicio.setAttribute("onfocus","Foco(this,'in')");
			document.formulario.DataInicio.setAttribute("onblur","Foco(this,'out')");
			document.formulario.DataTermino.setAttribute("onfocus","Foco(this,'in')");
			document.formulario.DataTermino.setAttribute("onblur","Foco(this,'out')");
			document.formulario.Valor.setAttribute("onfocus","Foco(this,'in')");
			document.formulario.Valor.setAttribute("onblur","Foco(this,'out')");
			document.formulario.MultaFidelidade.setAttribute("onfocus","Foco(this,'in')");
			document.formulario.MultaFidelidade.setAttribute("onblur","Foco(this,'out')");
			document.formulario.DescricaoServicoValor.setAttribute("onfocus","Foco(this,'in')");
			document.formulario.DescricaoServicoValor.setAttribute("onblur","Foco(this,'out')");
			
		}else{
			
			document.getElementById('DataInicio').style.color = "#000000";
			document.getElementById('Valor').style.color = "#000000";
			document.getElementById('TipoVigencia').style.color = "#000000";
			document.getElementById("cpValorMulta").style.color	= "#000000";
			
			document.formulario.DataInicio.readOnly = true;
			document.formulario.DataTermino.readOnly = true;
			document.formulario.Valor.readOnly = true;
			document.formulario.MultaFidelidade.readOnly = true;
			document.formulario.DescricaoServicoValor.readOnly = true;
			
			document.formulario.IdContratoTipoVigencia.disabled = true;
			
			document.formulario.icoDataInicio.setAttribute("src","../../img/estrutura_sistema/ico_date_c.gif");
			document.formulario.icoDataTermino.setAttribute("src","../../img/estrutura_sistema/ico_date_c.gif");
			
			document.formulario.DataInicio.setAttribute("onfocus","");
			document.formulario.DataInicio.setAttribute("onblur","");
			document.formulario.DataTermino.setAttribute("onfocus","");
			document.formulario.DataTermino.setAttribute("onblur","");
			document.formulario.Valor.setAttribute("onfocus","");
			document.formulario.Valor.setAttribute("onblur","");
			document.formulario.MultaFidelidade.setAttribute("onfocus","");
			document.formulario.MultaFidelidade.setAttribute("onblur","");
			document.formulario.DescricaoServicoValor.setAttribute("onfocus","");
			document.formulario.DescricaoServicoValor.setAttribute("onblur","");
		}
	}