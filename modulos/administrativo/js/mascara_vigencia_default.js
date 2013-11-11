	function busca_mascara_vigencia(IdServico,Erro,Local,Mes) {
	
		if(IdServico == '' || IdServico == undefined) {
			IdServico = 0;
		}
		
		if(Mes=='' || Mes == undefined) {
			Mes = 0;
		}
		
		if(Local == '' || Local == undefined) {
			Local = document.formulario.Local.value;
		}
		
		var url = "xml/mascara_vigencia.php?IdServico="+IdServico+"&Mes="+Mes+"&IdTipoServico=1,4";
		
		call_ajax(url, function (xmlhttp) {
			if(Erro != false) {
				document.formulario.Erro.value = 0;
				verificaErro();
			}
		
			if(xmlhttp.responseText == 'false') {
				document.getElementById('titDesconto').style.color			= "#000";
				document.formulario.ValorDesconto.value						= "0,00";
				document.formulario.ValorDesconto.readOnly					= true;
				document.getElementById('titPercentual').style.color		= "#000";
				document.formulario.ValorPercentual.value					= "0,00";
				document.formulario.ValorPercentual.readOnly				= true;
				document.formulario.ValorDesconto.value 					= "0,00";
				document.formulario.ValorPercentual.value 					= "0,00";
				document.formulario.ValorFinal.value						= "0,00";
				document.formulario.IdTipoDesconto.value					= '';
				document.formulario.LimiteDesconto.value					= '';
				document.formulario.IdContratoTipoVigencia.value			= '';
				document.formulario.IdRepasse.value							= '';
				document.formulario.Fator.value								= '';
				document.formulario.VigenciaDefinitiva.value				= '';
				document.formulario.DataCriacao.value						= '';
				document.formulario.LoginCriacao.value						= '';
				document.formulario.DataAlteracao.value						= '';
				document.formulario.LoginAlteracao.value					= '';
				document.formulario.Acao.value								= 'inserir';
				document.getElementById('titLimiteDesconto').style.display	= 'none';
				document.getElementById('cpLimiteDesconto').style.display	= 'none';
				
				verificarRepasse();
				verificaAcao();
				document.formulario.IdTipoDesconto.focus();
			} else {
				var nameNode = xmlhttp.responseXML.getElementsByTagName("IdServico")[0]; 
				var nameTextNode = nameNode.childNodes[0];
				var IdServico = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoServico")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var DescricaoServico = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("IdTipoServico")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var IdTipoServico = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("Valor")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var Valor = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("ValorRepasseTerceiro")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var ValorRepasseTerceiro = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("PercentualRepasseTerceiro")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var PercentualRepasseTerceiro = nameTextNode.nodeValue;
			
				nameNode = xmlhttp.responseXML.getElementsByTagName("Fator")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var Fator = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("IdTipoDesconto")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var IdTipoDesconto = nameTextNode.nodeValue;
			
				nameNode = xmlhttp.responseXML.getElementsByTagName("IdContratoTipoVigencia")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var IdContratoTipoVigencia = nameTextNode.nodeValue;
			
				nameNode = xmlhttp.responseXML.getElementsByTagName("LimiteDesconto")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var LimiteDesconto = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("QtdMes")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var QtdMes = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("VigenciaDefinitiva")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var VigenciaDefinitiva = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("DataCriacao")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var DataCriacao = nameTextNode.nodeValue;
			
				nameNode = xmlhttp.responseXML.getElementsByTagName("LoginCriacao")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var LoginCriacao = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("DataAlteracao")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var DataAlteracao = nameTextNode.nodeValue;
			
				nameNode = xmlhttp.responseXML.getElementsByTagName("LoginAlteracao")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var LoginAlteracao = nameTextNode.nodeValue;
				var IdRepasse = '';
				
				if(ValorRepasseTerceiro != '' || PercentualRepasseTerceiro != '') {
					if(ValorRepasseTerceiro == '' && PercentualRepasseTerceiro != '') {
						PercentualRepasseTerceiro = formata_float(Arredonda(PercentualRepasseTerceiro,2),2).replace(/\./i,',');
						IdRepasse = 2;
					} else {
						ValorRepasseTerceiro = formata_float(Arredonda(ValorRepasseTerceiro,2),2).replace(/\./i,',');
						IdRepasse = 1;
					}
				}
				
				verificarRepasse(IdRepasse);
				
				if(Valor == '') {
					Valor = 0;
				}
				
				ValorDesconto	= Valor - (Fator*Valor);
				ValorFinal		= Valor - ValorDesconto;
				ValorPercentual	= (parseFloat(ValorDesconto)*100) / parseFloat(Valor);
				
				if(IdTipoDesconto == 1) {
					LimiteDesconto = dateFormat(LimiteDesconto);
				}
				
				addParmUrl("marServico","IdServico",IdServico);
				addParmUrl("marServicoValor","IdServico",IdServico);
				addParmUrl("marServicoValorNovo","IdServico",IdServico);
				addParmUrl("marServicoParametro","IdServico",IdServico);
				addParmUrl("marServicoParametroNovo","IdServico",IdServico);
				addParmUrl("marServicoRotina","IdServico",IdServico);
				addParmUrl("marMascaraVigencia","IdServico",IdServico);
				addParmUrl("marMascaraVigenciaNovo","IdServico",IdServico);
				
				document.getElementById('titDesconto').style.color			= "#c10000";
				document.formulario.ValorDesconto.readOnly					= false;
				document.getElementById('titPercentual').style.color		= "#c10000";
				document.formulario.ValorPercentual.readOnly				= false;
				document.formulario.IdServico.value							= IdServico;
				document.formulario.DescricaoServico.value					= DescricaoServico;
				document.formulario.ValorServico.value						= formata_float(Arredonda(Valor,2),2).replace(/\./i,',');
				document.formulario.IdTipoServico.value						= IdTipoServico;
				document.formulario.Mes.value								= Mes+'º';
				document.formulario.Fator.value								= Fator;
				document.formulario.ValorDesconto.value 					= formata_float(Arredonda(ValorDesconto,2),2).replace(/\./i,',');
				document.formulario.ValorPercentual.value 					= formata_float(Arredonda(ValorPercentual,2),2).replace(/\./i,',');
				document.formulario.IdTipoDesconto.value					= IdTipoDesconto;
				document.formulario.ValorFinal.value						= formata_float(Arredonda(ValorFinal,2),2).replace(/\./i,',');
				document.formulario.LimiteDesconto.value					= LimiteDesconto;
				document.formulario.IdRepasse.value							= IdRepasse;
				document.formulario.ValorRepasseTerceiro.value				= ValorRepasseTerceiro;
				document.formulario.PercentualRepasseTerceiro.value			= PercentualRepasseTerceiro;
				document.formulario.IdContratoTipoVigencia.value			= IdContratoTipoVigencia;
				document.formulario.CountMes.value							= QtdMes;
				document.formulario.VigenciaDefinitiva.value				= VigenciaDefinitiva;
				document.formulario.DataCriacao.value						= dateFormat(DataCriacao);
				document.formulario.LoginCriacao.value						= LoginCriacao;
				document.formulario.DataAlteracao.value						= dateFormat(DataAlteracao);
				document.formulario.LoginAlteracao.value					= LoginAlteracao;
				document.formulario.Acao.value								= 'alterar';
				document.getElementById('titLimiteDesconto').style.display	= 'block';
				document.getElementById('cpLimiteDesconto').style.display	= 'block';
				
				if(IdTipoServico == 2 || IdTipoServico == 3) { /* Eventual || Agrupado */
					document.formulario.bt_inserir.disabled = true;
				} else {
					listar_mascara_vigencia(IdServico,false,Local);
				}
				
				limparDesconto();
				verifica_limite_desconto(IdTipoDesconto);
				verificaAcao();
			}
			
			if(window.janela != undefined) {
				window.janela.close();
			}
		});
	}