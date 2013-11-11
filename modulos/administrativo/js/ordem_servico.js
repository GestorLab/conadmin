	function excluir(IdOrdemServico,IdStatus){
		if(IdOrdemServico== '' || undefined){
			IdOrdemServico = document.formulario.IdOrdemServico.value;
		}
		if(IdStatus > 99){ // IdStatus != 0
			return false;
		}
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
    
   			url = "files/excluir/excluir_ordem_servico.php?IdOrdemServico="+IdOrdemServico;
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
								url = 'cadastro_ordem_servico.php?Erro='+document.formulario.Erro.value;
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
									if(IdOrdemServico == document.getElementById('tableListar').rows[i].accessKey){
										document.getElementById('tableListar').deleteRow(i);
										tableMultColor('tableListar',document.filtro.corRegRand.value);
										aux=1;
										break;
									}
								}
								if(aux=1){
									for(var i=1; i<(document.getElementById('tableListar').rows.length-1); i++){
										temp	=	document.getElementById('tableListar').rows[i].cells[6].innerHTML.split(">");
										temp1	=	temp[1].split("<");
										valor	+=	parseFloat(temp1[0].replace(',','.'));;
									}
									document.getElementById('tableListarValor').innerHTML			=	formata_float(Arredonda(valor,2),2).replace('.',',');	
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
		if(document.formulario.IdTipoOrdemServico.value == ""){
			mensagens(1);
			document.formulario.IdTipoOrdemServico.focus();
			return false;
		}
		if(document.formulario.IdSubTipoOrdemServico.value == ""){
			mensagens(1);
			document.formulario.IdSubTipoOrdemServico.focus();
			return false;
		}
		if(document.formulario.IdTipoOrdemServico.value != '2'){
			if(document.formulario.IdPessoa.value==""){
				mensagens(1);
				document.formulario.IdPessoa.focus();
				return false;
			}
			if(document.formulario.IdServico.value==''){
				mensagens(1);
				document.formulario.IdServico.focus();
				return false;
			}
			if((document.formulario.Acao.value == 'inserir' || document.formulario.Acao.value == 'alterar') || document.formulario.Login.value == document.formulario.LoginCriacao.value){
				if(document.formulario.Valor.value==""){
					mensagens(1);
					document.formulario.Valor.focus();
					return false;
				}
				
				var ValorOutros = document.formulario.ValorOutros.value;
				ValorOutros		= ValorOutros.replace(/\./g,"");
				ValorOutros		= ValorOutros.replace(/,/i,".");
				
				if(parseFloat(ValorOutros) > 0){
					if(document.formulario.Justificativa.value==""){
						mensagens(1);
						document.formulario.Justificativa.focus();
						return false;
					}
				}
				if(document.formulario.DescricaoOS.value=="" && document.formulario.ObrigatoriedadeDescricaoOrdemServico.value == 1){
					mensagens(1);
					document.formulario.DescricaoOS.focus();
					return false;
				}
				
				var posInicial=0,posFinal=0,temp=0;
				
				if(document.formulario.IdServico.value != '' && document.getElementById("cp_parametrosServico").style.display != "none"){
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
					if(posInicial != 0){
						var decremento = 0;
						
						if(document.formulario.Acao.value == "alterar"){
							decremento++;
						}
						
						for(i = posInicial; i<=posFinal; i=i+(4-decremento)){
							if(document.formulario[i+1].value == 1){
								if(document.formulario[i].type == 'text'){
								 	if(document.formulario[i+2].value == '1' || decremento == 1){
										if(document.formulario[i].value == ''){
											mensagens(1);
											document.formulario[i].focus();
											return false;
										}else{
											if(document.formulario[i+(3-decremento)].value != '' && parseInt(document.formulario[i].value.length) < parseInt(document.formulario[i+(3-decremento)].value)){
												qtdMin = document.formulario[i+(3-decremento)].value+' ('+conta(document.formulario[i+(3-decremento)].value)+')';
												mensagens(130,'',qtdMin);
												document.formulario[i].focus();
												return false;
											}
										}
									}
								}
								else if(document.formulario[i].type == 'password'){
								 	if(document.formulario[i+2].value == '1' || decremento == 1){
										if(document.formulario[i].value == ''){
											mensagens(1);
											document.formulario[i].focus();
											return false;
										}else{
											if(document.formulario[i+(3-decremento)].value!="" && parseInt(document.formulario[i].value.length) < parseInt(document.formulario[i+(3-decremento)].value)){
												qtdMin = document.formulario[i+(3-decremento)].value+' ('+conta(document.formulario[i+(3-decremento)].value)+')';
												mensagens(130,'',qtdMin);
												document.formulario[i].focus();
												return false;
											}
										}
									}
								}
								else if(document.formulario[i].type == 'select-one'){
									var cont = 0;
									
									for(j=0;j<document.formulario[i].options.length;j++){
										if(document.formulario[i][j].selected == true && document.formulario[i][j].value != ""){
											cont++;
											j = document.formulario[i].options.length;
										}
									}
									
									if(cont == 0 && (document.formulario[i+2].value == '1' || decremento == 1)){
										mensagens(1);
										document.formulario[i].focus();
										return false;
									}
								}
							}
						}
					}
				}
				if(document.formulario.IdPessoaEndereco.value=="0"){
					mensagens(1);
					document.formulario.IdPessoaEndereco.focus();
					return false;
				}
			}else{
				if(document.formulario.DescricaoOS.value==""){
					mensagens(1);
					document.formulario.DescricaoOS.focus();
					return false;
				}
			}
		}else{
			if(document.formulario.DescricaoOSInterna.value==""){
				mensagens(1);
				document.formulario.DescricaoOSInterna.focus();
				return false;
			}
		}
		if(document.formulario.IdStatusNovo.value=="" && document.formulario.Acao.value == "inserir"){
			if(document.formulario.IdStatusNovo.value==""){
				mensagens(1);
				document.formulario.IdStatusNovo.focus();
				return false;
			}
		}
		if(document.formulario.IdStatusNovo.value!=""){
			if(document.formulario.IdStatusNovo.value >= 0 && document.formulario.IdStatusNovo.value <= 99){ //Cancelado 
				if(document.formulario.Obs.value==""){
					mensagens(1);
					document.formulario.Obs.focus();
					return false;
				}
			}
			if(document.formulario.IdStatusNovo.value >= 100 && document.formulario.IdStatusNovo.value <= 199){ //Em Aberto
				if(document.formulario.IdGrupoUsuarioAtendimento.value==""){
					mensagens(1);
					document.formulario.IdGrupoUsuarioAtendimento.focus();
					return false;
				}
			}		
			if(document.formulario.IdStatusNovo.value >= 200 && document.formulario.IdStatusNovo.value <= 299){	//Concluido
				
				if(document.formulario.NovaDescricaoOsCDA.value == 1){
					if(document.formulario.DescricaoCDA.value==""){
						mensagens(1);
						document.formulario.DescricaoCDA.focus();
						return false;
					}
				}
				
				if(document.formulario.IdTipoOrdemServico.value != 2 && document.formulario.Faturado.value != 1){
					valor	=	document.formulario.ValorTotal.value;
					valor	=	new String(valor);
					valor	=	valor.replace(/\./g,'');
					valor	=	valor.replace(/,/i,'.');
					
					if(Number(valor) != 0){
						valorF	=	document.formulario.ValorFinal.value;
						valorF	=	new String(valorF);
						valorF	=	valorF.replace(/\./g,'');
						valorF	=	valorF.replace(/,/i,'.');
						
						if(valor != valorF){
							mensagens(105);
							document.formulario.ValorFinal.focus();
							return false;
						}
						if(document.formulario.FormaCobranca.value==""){
							mensagens(1);
							document.formulario.FormaCobranca.focus();
							return false;
						}
					}
				}
				if(document.formulario.IdStatusNovo.value >= 300 && document.formulario.IdStatusNovo.value <= 399){ //Pendente
					if(document.formulario.Obs.value==""){
						mensagens(1);
						document.formulario.Obs.focus();
						return false;
					}
				}				
			}
			if(document.formulario.ObrigatoriedadeDataHora.value == 1){
				if(document.formulario.IdStatusNovo.value == 100){
					if(document.formulario.Data.value == ""){
						mensagens(1);
						document.formulario.Data.focus();
						return false;
					}
					if(document.formulario.Hora.value == ""){
						mensagens(1);
						document.formulario.Hora.focus();
						return false;
					}
				}
			}
			if(document.formulario.Data.value!="" && isData(document.formulario.Data.value) == false){
				mensagens(27);
				document.formulario.Data.focus();
				return false;
			}
			if(document.formulario.Hora.value!="" && isTime(document.formulario.Hora.value)==false){
				mensagens(28);
				document.formulario.Hora.focus();
				return false;
			}
			if(document.formulario.Data.value=="" && document.formulario.Hora.value!=""){
				mensagens(1);
				document.formulario.Data.focus();
				return false;
			}
		}
		for(i = 0; i<document.formulario.length; i++){
			if(document.formulario[i].name != undefined){
				if(document.formulario[i].name.substring(0,11) == 'EndArquivo_'){
					var temp = document.formulario[i].value.split('.');
					var ext = temp[temp.length-1].toLowerCase();
					
					if(!document.formulario.ExtensaoAnexo.value.split(',').in_array(ext) && ext != ''){
						mensagens(10);
						document.formulario[i-1].focus();
						return false;
					} else{
						if(document.formulario[i].value != '' && document.formulario[i+1].value == ''){
							mensagens(1);
							document.formulario[i+1].focus();
							return false;
						}
					}
				}
			}
		}
		
		mensagens(0);
		return true;
	}
	function atualiza_filtro_tipo_servico(IdTipoOrdemServico,IdSubTipoOrdemServico){
		busca_filtro_subtipo_ordem_servico(IdTipoOrdemServico,IdSubTipoOrdemServico);
	}
	function busca_filtro_subtipo_ordem_servico(IdTipoOrdemServico,IdSubTipoOrdemServicoTemp){
		if(IdTipoOrdemServico == undefined || IdTipoOrdemServico==''){
			IdTipoOrdemServico = 0;
			/*while(document.filtro.filtro_ordem_servico_sub_tipo.options.length > 0){
				document.filtro.filtro_ordem_servico_sub_tipo.options[0] = null;
			}
			
			addOption(document.filtro.filtro_ordem_servico_sub_tipo,"Todos","");
			return false;*/
			
		}
		if(IdSubTipoOrdemServicoTemp == undefined){
			IdSubTipoOrdemServicoTemp = '';
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
	    
	    url = "xml/subtipo_ordem_servico.php?IdTipoOrdemServico="+IdTipoOrdemServico;

		xmlhttp.open("GET", url,true);
	    	
		xmlhttp.onreadystatechange = function(){ 
			if(xmlhttp.readyState == 4){ 
				if(xmlhttp.status == 200){
					if(xmlhttp.responseText != 'false'){		
						while(document.filtro.filtro_ordem_servico_sub_tipo.options.length > 0){
							document.filtro.filtro_ordem_servico_sub_tipo.options[0] = null;
						}
						
						var nameNode, nameTextNode, IdSubTipoOrdemServico;					
						
						addOption(document.filtro.filtro_ordem_servico_sub_tipo,"Todos","");
						
						for(var i=0; i<xmlhttp.responseXML.getElementsByTagName("IdSubTipoOrdemServico").length; i++){
							nameNode = xmlhttp.responseXML.getElementsByTagName("IdSubTipoOrdemServico")[i]; 
							nameTextNode = nameNode.childNodes[0];
							IdSubTipoOrdemServico = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoSubTipoOrdemServico")[i]; 
							nameTextNode = nameNode.childNodes[0];
							DescricaoSubTipoOrdemServico = nameTextNode.nodeValue;
						
							addOption(document.filtro.filtro_ordem_servico_sub_tipo,DescricaoSubTipoOrdemServico,IdSubTipoOrdemServico);
						}					
						
						if(IdSubTipoOrdemServicoTemp!=""){
							for(i=0;i<document.filtro.filtro_ordem_servico_sub_tipo.length;i++){
								if(document.filtro.filtro_ordem_servico_sub_tipo[i].value == IdSubTipoOrdemServicoTemp){
									document.filtro.filtro_ordem_servico_sub_tipo[i].selected	=	true;
									break;
								}
							}
						}else{
							document.filtro.filtro_ordem_servico_sub_tipo[0].selected	=	true;
						}						
					}else{
						while(document.filtro.filtro_ordem_servico_sub_tipo.options.length > 0){
							document.filtro.filtro_ordem_servico_sub_tipo.options[0] = null;
						}
						addOption(document.filtro.filtro_ordem_servico_sub_tipo,"Todos","");
					}
					
				}		
			}
			return true;
		}
		xmlhttp.send(null);	
	}	
	function atualiza_tipo_servico(IdTipoOrdemServico,IdSubTipoOrdemServico){
		busca_status_novo(40,document.formulario.IdStatusNovo,"",IdTipoOrdemServico);
		
		switch(IdTipoOrdemServico){
			case '2': //Interna
				document.getElementById('cp_dadosCliente').style.display		=	'none';
				document.getElementById('cpDadosServico').style.display			=	'none';
				document.getElementById('cpAgenteAutorizado').style.display		=	'none';
				document.getElementById('cp_dadosContrato').style.display		=	'none';
				document.getElementById("cp_automatico").style.display			= 	'none';
				document.getElementById('cpDescricaoOSInterna').style.display	=	'block';
				document.getElementById('cp_parametrosServico').style.display	= 	'none';
				document.getElementById('cp_parametrosContrato').style.display	= 	'none';
				document.getElementById('cpEndereco').style.display				= 	'none';
				//document.formulario.DescricaoOSInterna.value					=	'';
				//document.formulario.DescricaoOS.value							=	'';
				document.formulario.Obs.value									=	'';
				
				addParmUrl("marContrato","IdContrato","");
				addParmUrl("marContrato","IdPessoa","");
				addParmUrl("marContratoNovo","IdPessoa","");
				addParmUrl("marContasReceber","IdContrato","");
				addParmUrl("marLancamentoFinanceiro","IdContrato","");
				addParmUrl("marProcessoFinanceiro","IdContrato","");
				addParmUrl("marProcessoFinanceiroNovo","IdContrato","");
				//addParmUrl("marReenvioMensagem","IdPessoa","");
				addParmUrl("marContaEventual","IdPessoa","");
				addParmUrl("marContaEventualNovo","IdPessoa","");
				addParmUrl("marPessoa","IdPessoa","");
				addParmUrl("marOrdemServico","IdPessoa","");
				addParmUrl("marOrdemServicoNovo","IdContrato","");
				addParmUrl("marOrdemServicoNovo","IdPessoa","");
				addParmUrl("marOrdemServico","IdContrato","");
				addParmUrl("marVigencia","IdContrato","");
				addParmUrl("marVigenciaNovo","IdContrato","");
				addParmUrl("marVigenciaNovo","IdPessoa","");
				
				busca_pessoa('','false',document.formulario.Local.value);
				visualiza_campo('');
				busca_login_usuario('',document.formulario.LoginAtendimento);
				break;
			default:
				document.getElementById('cp_dadosCliente').style.display		=	'block';
				document.getElementById('cpDadosServico').style.display			=	'block';
				document.getElementById('cpAgenteAutorizado').style.display		=	'block';
				document.getElementById('cp_dadosContrato').style.display		=	'block';
				document.getElementById('cpDescricaoOSInterna').style.display	=	'none';
				document.getElementById('cpEndereco').style.display				= 	'block';
				//document.formulario.DescricaoOSInterna.value					=	'';
				//document.formulario.DescricaoOS.value							=	'';
				document.formulario.Obs.value									=	'';
				
				visualiza_campo('');
				busca_login_usuario('',document.formulario.LoginAtendimento);
		}
		//busca_subtipo_ordem_servico(IdTipoOrdemServico,IdSubTipoOrdemServico);
	}
	function busca_subtipo_ordem_servico(IdTipoOrdemServico,IdSubTipoOrdemServicoTemp){
		if(IdTipoOrdemServico == undefined || IdTipoOrdemServico==''){
			IdTipoOrdemServico = 0;
		}
		if(IdSubTipoOrdemServicoTemp == undefined){
			IdSubTipoOrdemServicoTemp = '';
		}
		var xmlhttp = false;
	
	    url = "xml/subtipo_ordem_servico.php?IdTipoOrdemServico="+IdTipoOrdemServico;
		
		call_ajax(url, function(xmlhttp){
			if(xmlhttp.responseText != 'false'){		
				while(document.formulario.IdSubTipoOrdemServico.options.length > 0){
					document.formulario.IdSubTipoOrdemServico.options[0] = null;
				}
				
				var nameNode, nameTextNode, IdSubTipoOrdemServico;					
				
				addOption(document.formulario.IdSubTipoOrdemServico,"","");
				
				for(var i=0; i<xmlhttp.responseXML.getElementsByTagName("IdSubTipoOrdemServico").length; i++){
					nameNode = xmlhttp.responseXML.getElementsByTagName("IdSubTipoOrdemServico")[i]; 
					nameTextNode = nameNode.childNodes[0];
					IdSubTipoOrdemServico = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoSubTipoOrdemServico")[i]; 
					nameTextNode = nameNode.childNodes[0];
					DescricaoSubTipoOrdemServico = nameTextNode.nodeValue;
				
					addOption(document.formulario.IdSubTipoOrdemServico,DescricaoSubTipoOrdemServico,IdSubTipoOrdemServico);
				}
				
				if(IdSubTipoOrdemServicoTemp!=""){
					for(i=0;i<document.formulario.IdSubTipoOrdemServico.length;i++){
						if(document.formulario.IdSubTipoOrdemServico[i].value == IdSubTipoOrdemServicoTemp){
							document.formulario.IdSubTipoOrdemServico[i].selected	=	true;
							document.formulario.IdSubTipoOrdemServicoTemp.value	=	IdSubTipoOrdemServicoTemp;
						}
					}
				}else{
					document.formulario.IdSubTipoOrdemServicoTemp.value	=	'';
					document.formulario.IdSubTipoOrdemServico[0].selected	=	true;
				}						
			}else{
				while(document.formulario.IdSubTipoOrdemServico.options.length > 0){
					document.formulario.IdSubTipoOrdemServico.options[0] = null;
				}
				
				document.formulario.IdSubTipoOrdemServicoTemp.value = '';
			}
			
		});	
	}
	function validar_Data(id,campo){
		if(campo.value == ''){
			document.getElementById(id).style.backgroundColor='#FFF';
			document.getElementById(id).style.color='#000';
			mensagens(0);
			return false;
		}
		if(isData(campo.value) == false){		
			document.getElementById(id).style.backgroundColor = '#C10000';
			document.getElementById(id).style.color='#FFF';
			mensagens(27);
			return false;
		}else{
			document.getElementById(id).style.backgroundColor='#FFF';
			document.getElementById(id).style.color='#000';
			mensagens(0);
			return true;
		}	
	}
	function validar_Time(id,campo){
		if(campo.value == ''){
			document.getElementById(id).style.backgroundColor='#FFF';
			document.getElementById(id).style.color='#000';
			mensagens(0);
			return false;
		}
		if(isTime(campo.value) == false){		
			document.getElementById(id).style.backgroundColor = '#C10000';
			document.getElementById(id).style.color='#FFF';
			mensagens(27);
			return false;
		}else{
			document.getElementById(id).style.backgroundColor='#FFF';
			document.getElementById(id).style.color='#000';
			mensagens(0);
			return true;
		}	
	}
	function verificaAcao(){
		if(document.formulario != undefined){
			if(document.formulario.Acao.value=='inserir'){	
				document.formulario.bt_imprimir.disabled	= true;	
				document.formulario.bt_fatura.disabled		= true;			
				document.formulario.bt_inserir.disabled 	= false;
				document.formulario.bt_alterar.disabled 	= true;
				document.formulario.bt_excluir.disabled 	= true;
				document.formulario.bt_chegar.disabled 		= true;
			}else{			
				document.formulario.bt_inserir.disabled 	= true;
				document.formulario.bt_imprimir.disabled	= false;
				document.formulario.bt_excluir.disabled 	= true;
				document.formulario.bt_alterar.disabled 	= false;
				document.formulario.bt_chegar.disabled 		= false;
				
				var valor	=	document.formulario.ValorTotal.value;
				valor		=	new String(valor);
				valor		=	valor.replace('.','');
				valor		=	valor.replace('.','');
				valor		=	valor.replace(',','.');	
				
				if((document.formulario.IdStatus.value >= 0 && document.formulario.IdStatus.value <= 99) || (document.formulario.IdStatus.value >= 200 && document.formulario.IdStatus.value <= 299)){
					document.formulario.bt_alterar.disabled 	= true;
					document.formulario.IdStatusNovo.disabled	= true;
					
					if(document.formulario.IdStatus.value >= 200 && document.formulario.IdStatus.value <= 299){
						if(valor <= 0){
							document.formulario.bt_alterar.disabled 	= false;
							document.formulario.IdStatusNovo.disabled	= false;
							document.formulario.bt_fatura.disabled		= true;
						}
					}
				} else{
					document.formulario.bt_alterar.disabled 	= false;
					document.formulario.IdStatusNovo.disabled	= false;
					
					var valor	=	document.formulario.ValorTotal.value;
					valor		=	new String(valor);
					valor		=	valor.replace('.','');
					valor		=	valor.replace('.','');
					valor		=	valor.replace(',','.');	
					
					if(((document.formulario.IdStatus.value >= 100 && document.formulario.IdStatus.value <= 199) || (document.formulario.IdStatus.value >= 400 && document.formulario.IdStatus.value <= 499)) && valor > 0){
						document.formulario.bt_fatura.disabled		= false;	
					}else{
						document.formulario.bt_fatura.disabled		= true;						
					}
				}
				
				if(document.formulario.PermissaoFatura.value == true){				
					document.formulario.bt_alterar.disabled 	= false;
					document.formulario.IdStatusNovo.disabled	= false;
					
					if((document.formulario.IdStatus.value >= 0 && document.formulario.IdStatus.value <= 99) || (document.formulario.IdStatus.value >= 200 && document.formulario.IdStatus.value <= 299)){
						document.formulario.bt_alterar.disabled 	= true;
						document.formulario.IdStatusNovo.disabled	= true;
						
						if((document.formulario.IdStatus.value >= 200 && document.formulario.IdStatus.value <= 299) && valor <= 0){
							document.formulario.bt_alterar.disabled 	= false;
							document.formulario.IdStatusNovo.disabled	= false;
						}
						
						if(document.formulario.IdStatus.value >= 0 && document.formulario.IdStatus.value <= 99){
							document.formulario.bt_excluir.disabled 	= false;
							document.formulario.bt_fatura.disabled		= true;	
						}else{
							document.formulario.bt_excluir.disabled 	= true;
						}
					}else{					
						document.formulario.bt_alterar.disabled 	= false;
						document.formulario.IdStatusNovo.disabled	= false;
						document.formulario.bt_excluir.disabled 	= true;
					}
					
					if(document.formulario.IdStatus.value >= 400 && document.formulario.IdStatus.value <= 499){
						document.formulario.bt_excluir.disabled 	= true;	
						document.formulario.bt_imprimir.disabled 	= false;					
					}
				}
				
				if(document.formulario.IdTipoOrdemServico.value == 1){ // Ordem de serviço do tipo Atendimento				
					if(document.formulario.PermissaoGeralOsTipoAtendimento.value == 1){ // OS - Qualquer usuário tem permissao edição Tip. Atendimento	
						document.formulario.bt_alterar.disabled 	= false;
						document.formulario.IdStatusNovo.disabled	= false;
						
						if((document.formulario.IdStatus.value >= 0 && document.formulario.IdStatus.value <= 99) || (document.formulario.IdStatus.value >= 200 && document.formulario.IdStatus.value <= 299)){
							document.formulario.bt_alterar.disabled 	= true;
							document.formulario.IdStatusNovo.disabled	= true;
							
							if((document.formulario.IdStatus.value >= 200 && document.formulario.IdStatus.value <= 299) && valor <= 0){
								document.formulario.bt_alterar.disabled 	= false;
								document.formulario.IdStatusNovo.disabled	= false;
							}
							
							if(document.formulario.IdStatus.value >= 0 && document.formulario.IdStatus.value <= 99){
								document.formulario.bt_excluir.disabled 	= false;
								document.formulario.bt_fatura.disabled		= true;
							} else{
								document.formulario.bt_excluir.disabled 	= true;
							}
						} else{
							document.formulario.bt_alterar.disabled 	= false;
							document.formulario.IdStatusNovo.disabled	= false;
							document.formulario.bt_excluir.disabled 	= true;
						}
						
						if(document.formulario.IdStatus.value >= 400 && document.formulario.IdStatus.value <= 499){
							document.formulario.bt_excluir.disabled 	= true;
							document.formulario.bt_imprimir.disabled 	= false;
						}
					} else{
						if(document.formulario.Login.value == document.formulario.LoginCriacao.value || document.formulario.Login.value == document.formulario.LoginAtendente.value){
							document.formulario.bt_alterar.disabled 	= false;
							document.formulario.IdStatusNovo.disabled	= false;
							
							if(document.formulario.Login.value == document.formulario.LoginCriacao.value){
								if((document.formulario.IdStatus.value >= 0 && document.formulario.IdStatus.value <= 99) || (document.formulario.IdStatus.value >= 200 && document.formulario.IdStatus.value <= 299)){
									document.formulario.bt_alterar.disabled 	= true;
									document.formulario.IdStatusNovo.disabled	= true;
									
									if((document.formulario.IdStatus.value >= 200 && document.formulario.IdStatus.value <= 299) && valor <= 0){
										document.formulario.bt_alterar.disabled 	= false;
										document.formulario.IdStatusNovo.disabled	= false;
									}
									
									if(document.formulario.IdStatus.value >= 0 && document.formulario.IdStatus.value <= 99){
										document.formulario.bt_excluir.disabled 	= false;
										document.formulario.bt_fatura.disabled		= true;	
									} else{
										document.formulario.bt_excluir.disabled 	= true;
									}
								} else{
									document.formulario.bt_alterar.disabled 	= false;
									document.formulario.IdStatusNovo.disabled	= false;
									document.formulario.bt_excluir.disabled 	= true;
								}
								
								if(document.formulario.IdStatus.value >= 400 && document.formulario.IdStatus.value <= 499){
									document.formulario.bt_excluir.disabled 	= true;	
									document.formulario.bt_imprimir.disabled 	= false;					
								}
							} else{
								document.formulario.bt_excluir.disabled 	= true;					
							}	
						} else{ //verificar LoginAtendimento no GrupoUsuarioAtendimento
						
							if(document.formulario.LoginAtendente.value == ''){
								var IdGrupoUsuarioAtendimento = document.formulario.IdGrupoUsuarioAtendimentoAtual.value;
							
								var xmlhttp   = false;
								if(window.XMLHttpRequest){ // Mozilla, Safari,...
									xmlhttp = new XMLHttpRequest();
									if(xmlhttp.overrideMimeType){
								//    	xmlhttp.overrideMimeType('text/xml');
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
						
								url = "xml/usuario_grupo_usuario.php?IdGrupoUsuario="+IdGrupoUsuarioAtendimento;
							
								xmlhttp.open("GET", url,true);
								xmlhttp.onreadystatechange = function(){ 
									// Carregando...
									carregando(true);
									if(xmlhttp.readyState == 4){ 
										if(xmlhttp.status == 200){								
											
											if(xmlhttp.responseText == false){
												document.formulario.bt_alterar.disabled 	= true;
												document.formulario.IdStatusNovo.disabled	= true;
											} else{											
												var update	=	false;
												var Login	=	document.formulario.Login.value;
												
												for(var i=0; i<xmlhttp.responseXML.getElementsByTagName("Login").length; i++){
													nameNode = xmlhttp.responseXML.getElementsByTagName("Login")[i]; 
													nameTextNode = nameNode.childNodes[0];
													LoginAtendimento = nameTextNode.nodeValue;
													
													if(LoginAtendimento == Login){
														if((document.formulario.IdStatus.value >= 0 && document.formulario.IdStatus.value <= 99) || (document.formulario.IdStatus.value >= 200 && document.formulario.IdStatus.value <= 299)){
															document.formulario.bt_alterar.disabled 	= true;
															document.formulario.IdStatusNovo.disabled	= true;
															
															if((document.formulario.IdStatus.value >= 200 && document.formulario.IdStatus.value <= 299) && valor <= 0){
																document.formulario.bt_alterar.disabled 	= false;
																document.formulario.IdStatusNovo.disabled	= false;
															}
															
															if(document.formulario.IdStatus.value >= 200 && document.formulario.IdStatus.value <= 299){
																document.formulario.bt_excluir.disabled 	= true;
															} else{
																document.formulario.bt_excluir.disabled 	= false;
															}
														} else{
															document.formulario.bt_alterar.disabled 	= false;
															document.formulario.IdStatusNovo.disabled	= false;
															document.formulario.bt_excluir.disabled 	= true;
														}										
														update = true;
														break;
													}
												}
												if(update == false){
													document.formulario.bt_alterar.disabled 	= true;
													document.formulario.IdStatusNovo.disabled	= true;
													document.formulario.bt_excluir.disabled 	= true;
												}
											}
										}
										// Fim de Carregando
										carregando(false);
									}
									return true;
								}
								xmlhttp.send(null);
							} else{ // Verifica o caso quando a OS esta direcionada a a outra pessoa e verifica se o usuário atual possue permissão desse grupo				
														
								var IdGrupoUsuarioAtendimento = document.formulario.IdGrupoUsuarioAtendimentoAtual.value;
							
								var xmlhttp   = false;
								if(window.XMLHttpRequest){ // Mozilla, Safari,...
									xmlhttp = new XMLHttpRequest();
									if(xmlhttp.overrideMimeType){
								//    	xmlhttp.overrideMimeType('text/xml');
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
						
								url = "xml/usuario_grupo_usuario.php?IdGrupoUsuario="+IdGrupoUsuarioAtendimento;
							
								xmlhttp.open("GET", url,true);
								xmlhttp.onreadystatechange = function(){ 
									// Carregando...
									carregando(true);
									if(xmlhttp.readyState == 4){ 
										if(xmlhttp.status == 200){								
											
											if(xmlhttp.responseText == false){
												document.formulario.bt_alterar.disabled 	= true;
												document.formulario.IdStatusNovo.disabled	= true;
											} else{											
												var update	=	false;
												var Login	=	document.formulario.Login.value;
												
												for(var i=0; i<xmlhttp.responseXML.getElementsByTagName("Login").length; i++){
													nameNode = xmlhttp.responseXML.getElementsByTagName("Login")[i]; 
													nameTextNode = nameNode.childNodes[0];
													LoginAtendimento = nameTextNode.nodeValue;
													
													if(LoginAtendimento == Login){
														if((document.formulario.IdStatus.value >= 0 && document.formulario.IdStatus.value <= 99) || (document.formulario.IdStatus.value >= 200 && document.formulario.IdStatus.value <= 299)){
															document.formulario.bt_alterar.disabled 	= true;
															document.formulario.IdStatusNovo.disabled	= true;
															
															if((document.formulario.IdStatus.value >= 200 && document.formulario.IdStatus.value <= 299) && valor <= 0){
																document.formulario.bt_alterar.disabled 	= false;
																document.formulario.IdStatusNovo.disabled	= false;
															}
															
															if(document.formulario.IdStatus.value >= 200 && document.formulario.IdStatus.value <= 299){
																document.formulario.bt_excluir.disabled 	= true;
															} else{
																document.formulario.bt_excluir.disabled 	= false;
															}
														} else{
															document.formulario.bt_alterar.disabled 	= false;
															document.formulario.IdStatusNovo.disabled	= false;
															document.formulario.bt_excluir.disabled 	= true;
														}										
														update = true;
														break;
													}
												}
												if(update == false){
													document.formulario.bt_alterar.disabled 	= true;
													document.formulario.IdStatusNovo.disabled	= true;
													document.formulario.bt_excluir.disabled 	= true;
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
					}
				}
				
				if(document.formulario.IdTipoOrdemServico.value == 2){ // Ordem de serviço do Tipo Interna
					if(document.formulario.PermissaoGeralOsTipoInterno.value == 1){ // OS - Qualquer usuário tem permissao edição Tip. I
						document.formulario.bt_alterar.disabled 	= false;
						
						if((document.formulario.IdStatus.value >= 0 && document.formulario.IdStatus.value <= 99) || (document.formulario.IdStatus.value >= 200 && document.formulario.IdStatus.value <= 299)){							
							document.formulario.bt_alterar.disabled 	= true;
							
							if(document.formulario.IdStatus.value >= 0 && document.formulario.IdStatus.value <= 99){
								document.formulario.bt_excluir.disabled 	= false;
								document.formulario.bt_fatura.disabled		= true;
							} else{
								document.formulario.bt_excluir.disabled 	= true;
							}
						} else{
							document.formulario.bt_alterar.disabled 	= false;
							document.formulario.bt_excluir.disabled 	= true;
						}
						
						if(document.formulario.IdStatus.value >= 400 && document.formulario.IdStatus.value <= 499){
							document.formulario.bt_excluir.disabled 	= true;
							document.formulario.bt_imprimir.disabled 	= false;
						}
					} else{
						if(document.formulario.Login.value == document.formulario.LoginCriacao.value || document.formulario.Login.value == document.formulario.LoginAtendente.value){
							document.formulario.bt_alterar.disabled 	= false;
							
							if(document.formulario.Login.value == document.formulario.LoginCriacao.value){
								if((document.formulario.IdStatus.value >= 0 && document.formulario.IdStatus.value <= 99) || (document.formulario.IdStatus.value <= 200 && document.formulario.IdStatus.value <= 299)){
									document.formulario.bt_alterar.disabled 	= true;
									
									if(document.formulario.IdStatus.value >= 0 && document.formulario.IdStatus.value <= 99){
										document.formulario.bt_excluir.disabled 	= false;
										document.formulario.bt_fatura.disabled		= true;
									} else{
										document.formulario.bt_excluir.disabled 	= true;
									}
								} else{
									document.formulario.bt_alterar.disabled 	= false;
									document.formulario.bt_excluir.disabled 	= true;
								}
								
								if(document.formulario.IdStatus.value >= 400 && document.formulario.IdStatus.value <= 499){
									document.formulario.bt_excluir.disabled 	= true;
									document.formulario.bt_imprimir.disabled 	= false;
								}
							} else{
								document.formulario.bt_excluir.disabled 	= true;
							}
							
						} else{ //verificar LoginAtendimento no GrupoUsuarioAtendimento
							if(document.formulario.LoginAtendente.value == ''){
								var IdGrupoUsuarioAtendimento = document.formulario.IdGrupoUsuarioAtendimentoAtual.value;
							
								var xmlhttp   = false;
								if(window.XMLHttpRequest){ // Mozilla, Safari,...
									xmlhttp = new XMLHttpRequest();
									if(xmlhttp.overrideMimeType){
								//    	xmlhttp.overrideMimeType('text/xml');
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
								
								url = "xml/usuario_grupo_usuario.php?IdGrupoUsuario="+IdGrupoUsuarioAtendimento;
								xmlhttp.open("GET", url,true);
								xmlhttp.onreadystatechange = function(){
									// Carregando...
									carregando(true);
									if(xmlhttp.readyState == 4){
										if(xmlhttp.status == 200){
											if(xmlhttp.responseText == false){
												document.formulario.bt_alterar.disabled 	= true;
											} else{
												var update	=	false;
												var Login	=	document.formulario.Login.value;
												
												for(var i=0; i<xmlhttp.responseXML.getElementsByTagName("Login").length; i++){
													nameNode = xmlhttp.responseXML.getElementsByTagName("Login")[i]; 
													nameTextNode = nameNode.childNodes[0];
													LoginAtendimento = nameTextNode.nodeValue;
													
													if(LoginAtendimento == Login){
														if((document.formulario.IdStatus.value >= 0 && document.formulario.IdStatus.value <= 99) || (document.formulario.IdStatus.value >= 200 && document.formulario.IdStatus.value <= 299)){
															document.formulario.bt_alterar.disabled 	= true;
															
															if(document.formulario.IdStatus.value >= 200 && document.formulario.IdStatus.value <= 299){
																document.formulario.bt_excluir.disabled 	= true;
															} else{
																document.formulario.bt_excluir.disabled 	= false;
															}
														} else{
															document.formulario.bt_alterar.disabled 	= false;
															document.formulario.bt_excluir.disabled 	= true;
														}
														
														update = true;
														break;
													}
												}
												if(update == false){
													document.formulario.bt_alterar.disabled 	= true;
													document.formulario.bt_excluir.disabled 	= true;
												}
											}
										}
										// Fim de Carregando
										carregando(false);
									}
									return true;
								}
								xmlhttp.send(null);
							} else{
							//	document.formulario.bt_excluir.disabled 	= true;
								var IdGrupoUsuarioAtendimento = document.formulario.IdGrupoUsuarioAtendimentoAtual.value;
							
								var xmlhttp   = false;
								if(window.XMLHttpRequest){ // Mozilla, Safari,...
									xmlhttp = new XMLHttpRequest();
									if(xmlhttp.overrideMimeType){
								//    	xmlhttp.overrideMimeType('text/xml');
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
								
								url = "xml/usuario_grupo_usuario.php?IdGrupoUsuario="+IdGrupoUsuarioAtendimento;
								xmlhttp.open("GET", url,true);
								xmlhttp.onreadystatechange = function(){
									// Carregando...
									carregando(true);
									if(xmlhttp.readyState == 4){
										if(xmlhttp.status == 200){
											if(xmlhttp.responseText == false){
												document.formulario.bt_alterar.disabled 	= true;
											} else{
												var update	=	false;
												var Login	=	document.formulario.Login.value;
												
												for(var i=0; i<xmlhttp.responseXML.getElementsByTagName("Login").length; i++){
													nameNode = xmlhttp.responseXML.getElementsByTagName("Login")[i]; 
													nameTextNode = nameNode.childNodes[0];
													LoginAtendimento = nameTextNode.nodeValue;
													
													if(LoginAtendimento == Login){
														if((document.formulario.IdStatus.value >= 0 && document.formulario.IdStatus.value <= 99) || (document.formulario.IdStatus.value >= 200 && document.formulario.IdStatus.value <= 299)){
															document.formulario.bt_alterar.disabled 	= true;
															
															if(document.formulario.IdStatus.value >= 200 && document.formulario.IdStatus.value <= 299){
																document.formulario.bt_excluir.disabled 	= true;
															} else{
																document.formulario.bt_excluir.disabled 	= false;
															}
														} else{
															document.formulario.bt_alterar.disabled 	= false;
															document.formulario.bt_excluir.disabled 	= true;
														}
														
														update = true;
														break;
													}
												}
												if(update == false){
													document.formulario.bt_alterar.disabled 	= true;
													document.formulario.bt_excluir.disabled 	= true;
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
					}
				}
			}
		}
	}
	
	function busca_login_usuario(IdGrupoUsuario,campo,LoginTemp){
		if(IdGrupoUsuario == ''){
			while(campo.options.length > 0){
				campo.options[0] = null;
			}
			
			if(document.filtro.filtro_usuario != undefined){
				addOption(campo,"Todos","");
			}
			return false;
		}
		if(LoginTemp == undefined){
			LoginTemp = '';
		}
				
		url = "xml/usuario_grupo_usuario.php?IdGrupoUsuario="+IdGrupoUsuario;
		
		if(campo.name == "LoginAtendimento") {
			url += "&IdPessoa="+document.formulario.IdPessoa.value;
		}
		
		call_ajax(url, function(xmlhttp){
			if(xmlhttp.responseText == 'false'){
				while(campo.options.length > 0){
					campo.options[0] = null;
				}
			}else{
				while(campo.options.length > 0){
					campo.options[0] = null;
				}
				if(document.filtro.filtro_usuario != undefined){
					addOption(campo,"Todos","");
				}else{
					addOption(campo,"","");
				}
					
				for(var i=0; i<xmlhttp.responseXML.getElementsByTagName("Login").length; i++){
					var nameNode = xmlhttp.responseXML.getElementsByTagName("Login")[i]; 
					var nameTextNode = nameNode.childNodes[0];
					var Login = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("NomeUsuario")[i]; 
					nameTextNode = nameNode.childNodes[0];
					var NomeUsuario = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("UltimaAtendimento")[i]; 
					nameTextNode = nameNode.childNodes[0];
					var UltimaAtendimento = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("QTDAberto")[i]; 
					nameTextNode = nameNode.childNodes[0];
					var QTDAberto = nameTextNode.nodeValue;
					
					var Descricao = NomeUsuario.substr(0,50);
					
					if(campo.name == "LoginAtendimento") {
						Descricao += " ("+QTDAberto+")";
					}
					
					Descricao += UltimaAtendimento;
					
					addOption(campo,Descricao,Login);
				}
				if(LoginTemp!=''){
					for(ii=0;ii<campo.length;ii++){
						if(campo[ii].value == LoginTemp){
							campo[ii].selected = true;
							break;
						}
					}
				}else{
					campo[0].selected = true;
				}						
			}
		});
	} 
	function addStatus(IdStatus){
	
		url = "xml/parametro_sistema.php?IdGrupoParametroSistema=40&IdParametroSistemaFalse=400";
		
		if(IdStatus!="" && IdStatus!=undefined){
			url	+= "&IdParametroSistema="+IdStatus;
		}
		
		call_ajax(url, function(xmlhttp){
			if(xmlhttp.responseText == 'false'){
				while(document.formulario.IdStatusNovo.options.length > 0){
					document.formulario.IdStatusNovo.options[0] = null;
				}
			}else{
				while(document.formulario.IdStatusNovo.options.length > 0){
					document.formulario.IdStatusNovo.options[0] = null;
				}
				
				addOption(document.formulario.IdStatusNovo,"","");
				
				for(var i=0; i<xmlhttp.responseXML.getElementsByTagName("IdParametroSistema").length; i++){
					nameNode = xmlhttp.responseXML.getElementsByTagName("IdParametroSistema")[i]; 
					nameTextNode = nameNode.childNodes[0];
					IdParametroSistema = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("ValorParametroSistema")[i]; 
					nameTextNode = nameNode.childNodes[0];
					ValorParametroSistema = nameTextNode.nodeValue;
					
					if(((Number(document.formulario.PermisaoConcluirOS.value) == 1 && IdParametroSistema > 199 && IdParametroSistema < 300) || IdParametroSistema < 200 || IdParametroSistema > 299) && !(IdParametroSistema >= 200 && IdParametroSistema <= 299 && document.formulario.Acao.value != "inserir" && document.formulario.LoginAtendimentoAtual.value != document.formulario.Login.value && ((document.formulario.IdTipoOrdemServico.value == 1 && document.formulario.UsuarioADefault.value != 1 && document.formulario.ResponsavelADefault.value == 1) || (document.formulario.IdTipoOrdemServico.value == 2 && document.formulario.UsuarioIDefault.value != 1 && document.formulario.ResponsavelIDefault.value == 1)))){
						if(document.formulario.IdStatus.value >= 200 && document.formulario.IdStatus.value <= 299){
							if(IdParametroSistema == 100){
								addOption(document.formulario.IdStatusNovo,ValorParametroSistema,IdParametroSistema);
							}
						}else{
							if(IdParametroSistema == 0){
								if(document.formulario.PermissaoCancelarOS.value == true){
									addOption(document.formulario.IdStatusNovo,ValorParametroSistema,IdParametroSistema);
								}
							} else{
								addOption(document.formulario.IdStatusNovo,ValorParametroSistema,IdParametroSistema);
							}
						}
					}
				}
				document.formulario.IdStatusNovo[0].selected = true;
			}
		});
	} 
	function verifica_permissao_update(IdGrupoUsuarioAtendimento){
		
		if(IdGrupoUsuarioAtendimento != ''){
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
	
			url = "xml/usuario_grupo_usuario.php?IdGrupoUsuario="+IdGrupoUsuarioAtendimento;
			xmlhttp.open("GET", url,true);
	
			xmlhttp.onreadystatechange = function(){ 
	
				// Carregando...
				carregando(true);
	
				if(xmlhttp.readyState == 4){ 
					if(xmlhttp.status == 200){
					
						if(xmlhttp.responseText == 'false'){
							document.formulario.bt_alterar.disabled 	= true;								
							document.formulario.ValorOutros.readOnly 	= false;
							document.formulario.Valor.readOnly 			= false;
							document.formulario.ValorFinal.readOnly		= false;					
						}else{
							var Login	=	document.formulario.Login.value;
							for(var i=0; i<xmlhttp.responseXML.getElementsByTagName("Login").length; i++){
						
								nameNode = xmlhttp.responseXML.getElementsByTagName("Login")[i]; 
								nameTextNode = nameNode.childNodes[0];
								LoginAtendimento = nameTextNode.nodeValue;
								
								if(LoginAtendimento == Login || document.formulario.LoginCriacao.value == Login){								
									document.formulario.IdStatusNovo.disabled				=	false;
									document.formulario.Data.readOnly						=	false;
									document.formulario.Hora.readOnly						=	false;
									document.formulario.IdGrupoUsuarioAtendimento.disabled	=	false;
									document.formulario.LoginAtendimento.disabled			=	false;
									document.formulario.Obs.readOnly						=	false;
									document.formulario.DescricaoOS.readOnly				= 	false;									
									document.formulario.bt_alterar.disabled 				=	false;

									if(document.formulario.Faturado.value == 1){			
										document.formulario.ValorOutros.readOnly 	= true;
										document.formulario.Valor.readOnly 			= true;
										document.formulario.ValorFinal.readOnly		= true;
									}else{
										document.formulario.ValorOutros.readOnly 	= false;
										document.formulario.Valor.readOnly 			= false;
										document.formulario.ValorFinal.readOnly		= false;
									}		
									
									var valor	=	document.formulario.ValorTotal.value;
									valor		=	new String(valor);
									valor		=	valor.replace('.','');
									valor		=	valor.replace('.','');
									valor		=	valor.replace(',','.');

									if(valor > 0){																		
										document.formulario.bt_fatura.disabled 				=	false;
									}else{
									
										document.formulario.bt_fatura.disabled 				=	true;
									}
									break;
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
	function busca_status(IdStatusTemp){
		if(IdStatusTemp == undefined){
			IdStatusTemp = 0;
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

		url = "xml/ordem_servico_status.php?IdStatus="+IdStatusTemp+"&IdOrdemServico="+document.formulario.IdOrdemServico.value;
		xmlhttp.open("GET", url,true);

		xmlhttp.onreadystatechange = function(){ 

			// Carregando...
			carregando(true);

			if(xmlhttp.readyState == 4){ 
				if(xmlhttp.status == 200){
					
					if(xmlhttp.responseText != 'false'){
						nameNode = xmlhttp.responseXML.getElementsByTagName("ValorParametroSistema")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var ValorParametroSistema = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("Parcela")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var Parcela = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("Cor")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var Cor = nameTextNode.nodeValue;
						
						if(document.formulario.Faturado.value == 1 && Parcela != ''){
							ValorParametroSistema += "<br><span style='font-size:9px;'>" + Parcela + "</span>";
						}

						document.getElementById('cp_Status').style.display		=	"block";		
						document.getElementById('cp_Status').style.color		=	Cor;		
						document.getElementById('cp_Status').innerHTML			=	ValorParametroSistema;
						document.getElementById('cp_Status').style.fontSize		=	"15px";
						document.getElementById('cp_Status').style.lineHeight	=	"11px";
					}
				}
				// Fim de Carregando
				carregando(false);
			}
			return true;
		}
		xmlhttp.send(null);
	} 
	function cadastrar(acao){
		switch(acao){
			case "cancelar":
				document.formulario.Acao.value = acao;
				if(document.formulario.Login.value == document.formulario.LoginCriacao.value){
					document.formulario.submit();
				}else{
					mensagens(2);
				}
				break;
			case "imprimir":
				window.open("ordem_servico.php?OrdemServico="+document.formulario.MD5.value);
				break;
			default:
				if(validar()==true){
					document.formulario.submit();
				}
				break;
		}
	}
	function visualiza_campo(IdStatus){
		if(IdStatus == ''){		
			document.getElementById('titNovaObsCDA').style.display			= 'none';
			document.getElementById('titGrupoAtendimento').style.display	= 'none';
			document.getElementById('titUsuarioAtendimento').style.display	= 'none';
			document.getElementById('titDataAgendamento').style.display		= 'none';
			document.getElementById('titHoraAgendamento').style.display		= 'none';
			document.getElementById('cpNovaObsCDA').style.display			= 'none';
			document.getElementById('cpGrupoAtendimento').style.display		= 'none';
			document.getElementById('tblSupervisor').style.display			= 'none';
			document.getElementById('cpUsuarioAtendimento').style.display	= 'none';
			document.getElementById('cpData').style.display					= 'none';
			document.getElementById('cpDataIco').style.display				= 'none';
			document.getElementById('cpHora').style.display					= 'none';
			document.getElementById('cpFaturamento').style.display			= 'none';
			
			
//			document.getElementById('tableMarcador').style.display			= 'none';
//			document.getElementById('titTableMarcador').style.display		= 'none';
		
			document.getElementById('titObs').style.color					= '#000';
			document.formulario.cpGrupoAtendimentot.value					= '';
			document.formulario.Data.value									= '';
			document.formulario.Hora.value									= '';
		
			busca_login_usuario('', document.formulario.LoginAtendimento);
			
			return false;
		}
		if(IdStatus >= 0 && IdStatus <= 99){	//Cancelado
			document.getElementById('titNovaObsCDA').style.display			= 'none';
			document.getElementById('titGrupoAtendimento').style.display	= 'none';
			document.getElementById('titUsuarioAtendimento').style.display	= 'none';
			document.getElementById('titDataAgendamento').style.display		= 'none';
			document.getElementById('titHoraAgendamento').style.display		= 'none';
			document.getElementById('cpNovaObsCDA').style.display			= 'none';
			document.getElementById('cpGrupoAtendimento').style.display		= 'none';
			document.getElementById('tblSupervisor').style.display			= 'none';
			document.getElementById('cpUsuarioAtendimento').style.display	= 'none';
			document.getElementById('cpData').style.display					= 'none';
			document.getElementById('cpDataIco').style.display				= 'none';
			document.getElementById('cpHora').style.display					= 'none';
			document.getElementById('cpFaturamento').style.display			= 'none';
			
//			document.getElementById('tableMarcador').style.display			= 'none';	
//			document.getElementById('titTableMarcador').style.display		= 'none';
			
			document.getElementById('titObs').style.color					= '#C10000';
			document.formulario.cpGrupoAtendimentot.value					= '';
			document.formulario.Data.value									= '';
			document.formulario.Hora.value									= '';
			
			busca_login_usuario('', document.formulario.LoginAtendimento);
		}
		if(IdStatus >= 100 && IdStatus <= 199){	//Em Aberto
			document.getElementById('titNovaObsCDA').style.display			= 'none';
			document.getElementById('titGrupoAtendimento').style.display	= 'block';
			document.getElementById('titGrupoAtendimentot').style.width		= '180px';
			document.getElementById('titUsuarioAtendimento').style.display	= 'block';
			document.getElementById('titUsuarioAtendimento').style.width	= '297px';
			document.getElementById('titDataAgendamento').style.display		= 'block';
			document.getElementById('titDataAgendamento').style.width		= '116px';
			document.getElementById('titHoraAgendamento').style.display		= 'block';
			document.getElementById('cpNovaObsCDA').style.display			= 'none';
			document.getElementById('cpGrupoAtendimento').style.display		= 'block';
			document.getElementById('tblSupervisor').style.display			= 'block';
			document.formulario.IdGrupoUsuarioAtendimento.style.width		= '180px';	
			document.getElementById('cpUsuarioAtendimento').style.display	= 'block';
			document.getElementById('cpData').style.display					= 'block';
			document.getElementById('cpDataIco').style.display				= 'block';
			document.getElementById('cpHora').style.display					= 'block';
			document.getElementById('cpFaturamento').style.display			= 'none';
			
			document.getElementById('titGrupoAtendimento').style.color		= '#C10000';
			document.getElementById('titObs').style.color					= '#000';
			
//			document.getElementById('tableMarcador').style.display			= 'block';	
//			document.getElementById('titTableMarcador').style.display		= 'block';
			
			document.formulario.IdGrupoUsuarioAtendimento.disabled			= false;
			document.formulario.LoginAtendimento.disabled					= false;
			document.formulario.Data.readOnly								= false;
			document.formulario.Hora.readOnly								= false;
			
			if(document.formulario.Acao.value == 'inserir'){
				switch(document.formulario.IdMarcadorDefault.value){
					case '2':
						document.getElementById('mVermelho').style.backgroundColor	=	'#FFD9D9';
						document.getElementById('mAmarelo').style.backgroundColor	=	'#F9F900';
						document.getElementById('mVerde').style.backgroundColor		=	'#D5FFD5';
						document.formulario.IdMarcador.value	=	document.formulario.IdMarcadorDefault.value;
						break;
					case '3':
						document.getElementById('mVermelho').style.backgroundColor	=	'#FFD9D9';
						document.getElementById('mAmarelo').style.backgroundColor	=	'#FFFFCE';
						document.getElementById('mVerde').style.backgroundColor		=	'#008000';
						document.formulario.IdMarcador.value	=	document.formulario.IdMarcadorDefault.value;
						break;
					case '1':
						document.getElementById('mVermelho').style.backgroundColor	=	'#FF0000';
						document.getElementById('mAmarelo').style.backgroundColor	=	'#FFFFCE';
						document.getElementById('mVerde').style.backgroundColor		=	'#D5FFD5';
						document.formulario.IdMarcador.value	=	document.formulario.IdMarcadorDefault.value;
						break;
					default:
						document.getElementById('mVermelho').style.backgroundColor	=	'#FFD9D9';
						document.getElementById('mAmarelo').style.backgroundColor	=	'#FFFFCE';
						document.getElementById('mVerde').style.backgroundColor		=	'#D5FFD5';
						document.formulario.IdMarcador.value	=	'';
				}
			}
		}
		if(IdStatus >= 200 && IdStatus <= 299){ 	//Concluido
			document.getElementById('titNovaObsCDA').style.display			= 'block';
			document.getElementById('titGrupoAtendimento').style.display	= 'none';
			document.getElementById('titUsuarioAtendimento').style.display	= 'none';
			document.getElementById('titDataAgendamento').style.display		= 'none';
			document.getElementById('titHoraAgendamento').style.display		= 'none';
			document.getElementById('titNovaObsCDAt').style.width			= '150px';
			document.getElementById('cpNovaObsCDA').style.display			= 'block';
			document.getElementById('cpGrupoAtendimento').style.display		= 'none';
			document.getElementById('cpUsuarioAtendimento').style.display	= 'none';
			document.getElementById('cpData').style.display					= 'none';
			document.getElementById('cpDataIco').style.display				= 'none';
			document.getElementById('cpHora').style.display					= 'none';
			document.getElementById('cpObsCDA').style.display        		= 'none';
			document.getElementById('titObsCDA').style.display        		= 'none';
			document.getElementById('tblSupervisor').style.display			= 'none';
			
//			document.getElementById('tableMarcador').style.display			= 'none';	
//			document.getElementById('titTableMarcador').style.display		= 'none';
			
			document.getElementById('titObs').style.color					= '#000';
			document.formulario.cpGrupoAtendimentot.value					= '';
			document.formulario.Data.value									= '';
			document.formulario.Hora.value									= '';
			document.formulario.ValorFinal.value							= '';
			
			busca_login_usuario('', document.formulario.LoginAtendimento);
			
			ValorTotal	=	document.formulario.ValorTotal.value;
			ValorTotal	=	new String(ValorTotal);
			ValorTotal	=	ValorTotal.replace(/\./g, '');
			ValorTotal	=	ValorTotal.replace(/,/i, '.');
			
			if(Number(ValorTotal) > 0){
				document.getElementById('cpFaturamento').style.display			= 'block';
				
				if(document.formulario.ValorFinal.value == ""){
					document.formulario.ValorFinal.value						= "0,00";
				}
				
				if(document.formulario.IdContrato.value == ""){
					document.formulario.FormaCobranca.value						= 2; //Individual
					document.formulario.FormaCobrancaTemp.value					= 2; //Individual
				}else{
					document.formulario.FormaCobranca.value						= ""; 
					document.formulario.FormaCobrancaTemp.value					= ""; 
				}
			}else{
				document.getElementById('cpFaturamento').style.display			= 'none';
			}
		}
		if(IdStatus >= 300 && IdStatus <= 399){ //Pendente
			document.getElementById('titNovaObsCDA').style.display			= 'none';
			document.getElementById('titGrupoAtendimento').style.display	= 'none';
			document.getElementById('titUsuarioAtendimento').style.display	= 'none';
			document.getElementById('titDataAgendamento').style.display		= 'none';
			document.getElementById('titHoraAgendamento').style.display		= 'none';
			document.getElementById('cpNovaObsCDA').style.display			= 'none';
			document.getElementById('cpGrupoAtendimento').style.display		= 'none';
			document.getElementById('cpUsuarioAtendimento').style.display	= 'none';
			document.getElementById('cpData').style.display					= 'none';
			document.getElementById('cpDataIco').style.display				= 'none';
			document.getElementById('cpHora').style.display					= 'none';
			document.getElementById('cpFaturamento').style.display			= 'none';
			document.getElementById('tblSupervisor').style.display			= 'none';
			
//			document.getElementById('tableMarcador').style.display			= 'none';	
//			document.getElementById('titTableMarcador').style.display		= 'none';
			
			document.getElementById('titObs').style.color					= '#C10000';
			document.formulario.cpGrupoAtendimentot.value					= '';
			document.formulario.Data.value									= '';
			document.formulario.Hora.value									= '';
			
			busca_login_usuario('', document.formulario.LoginAtendimento);
		}
		if(IdStatus > 399){
			document.getElementById('titNovaObsCDA').style.display			= 'none';
			document.getElementById('titGrupoAtendimento').style.display	= 'none';
			document.getElementById('titUsuarioAtendimento').style.display	= 'none';
			document.getElementById('titDataAgendamento').style.display		= 'none';
			document.getElementById('titHoraAgendamento').style.display		= 'none';
			document.getElementById('cpNovaObsCDA').style.display			= 'none';
			document.getElementById('cpGrupoAtendimento').style.display		= 'none';
			document.getElementById('cpUsuarioAtendimento').style.display	= 'none';
			document.getElementById('cpData').style.display					= 'none';
			document.getElementById('cpDataIco').style.display				= 'none';
			document.getElementById('cpHora').style.display					= 'none';
			document.getElementById('cpFaturamento').style.display			= 'none';
			document.getElementById('tblSupervisor').style.display			= 'none';
			
//			document.getElementById('tableMarcador').style.display			= 'none';	
//			document.getElementById('titTableMarcador').style.display		= 'none';
			
			document.getElementById('titObs').style.color					= '#000';
			document.formulario.cpGrupoAtendimentot.value					= '';
			document.formulario.Data.value									= '';
			document.formulario.Hora.value									= '';
			
			busca_login_usuario('', document.formulario.LoginAtendimento);	
		}
		if(document.formulario.Faturado.value == 1){
			document.getElementById('cpFaturamento').style.display			= 'none';
		}
	}
	function listarParametro(IdOrdemServico,IdServico,Erro){
		while(document.getElementById('tabelaParametro').rows.length > 1){
			document.getElementById('tabelaParametro').deleteRow(0);
		}
		
		if(IdServico == ''){
			IdServico = 0;
		}
		
		var url = "xml/ordem_servico_parametro.php?IdServico="+IdServico+"&IdOrdemServico="+IdOrdemServico+"&IdStatus=1";
		
		call_ajax(url,function (xmlhttp){
			if(xmlhttp.responseText == 'false'){
				document.getElementById('cp_parametrosServico').style.display = 'none';
			} else{
				document.getElementById('cp_parametrosServico').style.display = 'none';
				
				var tam, linha, c0, obsTemp = new Array(), invisivel = "", cont = 0, readOnly, tipo = "";
				
				for(var i=0; i<xmlhttp.responseXML.getElementsByTagName("IdParametroServico").length; i++){
					var nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoOrdemServicoParametro")[i]; 
					var nameTextNode = nameNode.childNodes[0];
					var DescricaoOrdemServicoParametro = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("Obrigatorio")[i]; 
					nameTextNode = nameNode.childNodes[0];
					var Obrigatorio = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("Valor")[i]; 
					nameTextNode = nameNode.childNodes[0];
					var Valor = nameTextNode.nodeValue;	
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("ValorDefault")[i]; 
					nameTextNode = nameNode.childNodes[0];
					var ValorDefault = nameTextNode.nodeValue;	
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("IdParametroServico")[i]; 
					nameTextNode = nameNode.childNodes[0];
					var IdParametroServico = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("IdTipoParametro")[i]; 
					nameTextNode = nameNode.childNodes[0];
					var IdTipoParametro = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("Obs")[i]; 
					nameTextNode = nameNode.childNodes[0];
					var Obs = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("IdMascaraCampo")[i]; 
					nameTextNode = nameNode.childNodes[0];
					var IdMascaraCampo = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("OpcaoValor")[i]; 
					nameTextNode = nameNode.childNodes[0];
					var OpcaoValor = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("PermissaoInserir")[i]; 
					nameTextNode = nameNode.childNodes[0];
					var PermissaoInserir = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("PermissaoEditar")[i]; 
					nameTextNode = nameNode.childNodes[0];
					var PermissaoEditar = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("PermissaoVisualizar")[i]; 
					nameTextNode = nameNode.childNodes[0];
					var PermissaoVisualizar = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("VisivelOS")[i]; 
					nameTextNode = nameNode.childNodes[0];
					var VisivelOS = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("RestringirGrupoUsuario")[i]; 
					nameTextNode = nameNode.childNodes[0];
					var RestringirGrupoUsuario = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("IdTipoTexto")[i]; 
					nameTextNode = nameNode.childNodes[0];
					var IdTipoTexto = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("ExibirSenha")[i]; 
					nameTextNode = nameNode.childNodes[0];
					var ExibirSenha = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("TamMinimo")[i]; 
					nameTextNode = nameNode.childNodes[0];
					var TamMinimo = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("TamMaximo")[i]; 
					nameTextNode = nameNode.childNodes[0];
					var TamMaximo = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("Editavel")[i]; 
					nameTextNode = nameNode.childNodes[0];
					var Editavel = nameTextNode.nodeValue;
					
					if(Valor == '' && ValorDefault != ''){
						Valor = ValorDefault;
					}
					
					if(VisivelOS == '1' && RestringirGrupoUsuario == '1') {
						VisivelOS = '2';
					}
					
					if(PermissaoVisualizar != '1') {
						VisivelOS = '2';
						Obrigatorio = "2";
					}
					
					if(document.formulario.Acao.value != "inserir"){
						if(PermissaoEditar != '1'){
							Obrigatorio = "2";
							Editavel = "2";
						}
					} else if(PermissaoInserir != '1'){
						Obrigatorio = "2";
						Editavel = "2";
					}
					//alert(VisivelOS);
					if(VisivelOS == '1'){
						obsTemp[cont] = Obs;
						tam = document.getElementById('tabelaParametro').rows.length;
						
						if(cont%2 == 0){
							linha		= document.getElementById('tabelaParametro').insertRow(tam);
							tabindex	= 11 + cont + 1;
							pos			= 0;
							padding		= 22;
						} else{
							padding		= 10;
							tabindex	= 11 + cont;
							pos			= 1;
							
							if(obsTemp[(cont-1)]!= undefined && obsTemp[(cont-1)]!= ''){
								if(Obs == '')
									Obs = '<BR>';
							}
						}
						
						if((cont+1) == xmlhttp.responseXML.getElementsByTagName("IdParametroServico").length && cont%2 == 0){
							padding = 22;
						}
						
						if(Obrigatorio == 1){
							color = "#C10000";
						} else{
							color = "#000000";
						}
						
						if(document.formulario.Login.value == document.formulario.LoginCriacao.value && Editavel == 1){
							readOnly = '';
						} else{
							readOnly = "readOnly='true'";
						}
						
						linha.accessKey = IdParametroServico; 
						
						c0 = linha.insertCell(pos);
						c0.style.verticalAlign = "top";
						
						if(TamMaximo!="" && Editavel==1){
							tamMax = "maxlength='"+TamMaximo+"'";
						} else{
							tamMax = "";
						}
						
						if(IdTipoParametro == 1){
							switch(IdTipoTexto){
								case '1':
									switch(IdMascaraCampo){
										case '1':
											c0.innerHTML = "<p style='margin:0; padding-bottom:1px; padding-left:"+padding+"px'><B style='color:"+color+";'>"+DescricaoOrdemServicoParametro+"</B></p><p style='padding-bottom:6px; padding-left:"+padding+"px; margin:0;'><input type='text' name='Valor_"+IdParametroServico+"' value='"+Valor+"' style='width:399px;' autocomplete='off' onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out');\" "+readOnly+" onkeypress=\"mascara(this,event,'date')\" maxlength='10' tabindex="+tabindex+"><input type='hidden' name='Obrigatorio_"+IdParametroServico+"' value='"+Obrigatorio+"'><input type='hidden' name='TamMinimo_"+IdParametroServico+"' value='"+TamMinimo+"'><BR>"+Obs+"</p>";
											break;
										case '2':
											c0.innerHTML = "<p style='margin:0; padding-bottom:1px; padding-left:"+padding+"px'><B style='color:"+color+";'>"+DescricaoOrdemServicoParametro+"</B></p><p style='padding-bottom:6px; padding-left:"+padding+"px; margin:0;'><input type='text' name='Valor_"+IdParametroServico+"' value='"+Valor+"' style='width:399px;' autocomplete='off' onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out');\" "+readOnly+" onkeypress=\"mascara(this,event,'int')\" "+tamMax+" tabindex="+tabindex+"><input type='hidden' name='Obrigatorio_"+IdParametroServico+"' value='"+Obrigatorio+"'><input type='hidden' name='TamMinimo_"+IdParametroServico+"' value='"+TamMinimo+"'><BR>"+Obs+"</p>";
											break;
										case '3':
											if(Editavel == 1){
												c0.innerHTML = "<p style='margin:0; padding-bottom:1px; padding-left:"+padding+"px'><B style='color:"+color+";'>"+DescricaoOrdemServicoParametro+"</B></p><p style='padding-bottom:6px; padding-left:"+padding+"px; margin:0;'><input type='text' name='Valor_"+IdParametroServico+"' value='"+Valor+"' style='width:399px;' autocomplete='off' onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out');\" "+readOnly+" onkeypress=\"mascara(this,event,'float')\" "+tamMax+" tabindex="+tabindex+"><input type='hidden' name='Obrigatorio_"+IdParametroServico+"' value='"+Obrigatorio+"'><input type='hidden' name='TamMinimo_"+IdParametroServico+"' value='"+TamMinimo+"'><BR>"+Obs+"</p>";
											}else{
												c0.innerHTML = "<p style='margin:0; padding-bottom:1px; padding-left:"+padding+"px'><B style='color:"+color+";'>"+DescricaoOrdemServicoParametro+"</B></p><p style='padding-bottom:6px; padding-left:"+padding+"px; margin:0;'><input type='text' name='Valor_"+IdParametroServico+"' value='"+Valor+"' style='width:399px;' autocomplete='off' onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out');\" "+readOnly+" tabindex="+tabindex+"><input type='hidden' name='Obrigatorio_"+IdParametroServico+"' value='"+Obrigatorio+"'><input type='hidden' name='TamMinimo_"+IdParametroServico+"' value='"+TamMinimo+"'><BR>"+Obs+"</p>";
											}
											break;
										case '5':
											c0.innerHTML = "<p style='margin:0; padding-bottom:1px; padding-left:"+padding+"px'><B style='color:"+color+";'>"+DescricaoOrdemServicoParametro+"</B></p><p style='padding-bottom:6px; padding-left:"+padding+"px; margin:0;'><input type='text' name='Valor_"+IdParametroServico+"' value='"+Valor+"' style='width:399px;' autocomplete='off' onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out');\" "+readOnly+" onkeypress=\"mascara(this,event,'mac')\" maxlength='17' tabindex="+tabindex+"><input type='hidden' name='Obrigatorio_"+IdParametroServico+"' value='"+Obrigatorio+"'><input type='hidden' name='TamMinimo_"+IdParametroServico+"' value='"+TamMinimo+"'><BR>"+Obs+"</p>";
											break;
										default:
											c0.innerHTML = "<p style='margin:0; padding-bottom:1px; padding-left:"+padding+"px'><B style='color:"+color+";'>"+DescricaoOrdemServicoParametro+"</B></p><p style='padding-bottom:6px; padding-left:"+padding+"px; margin:0;'><input type='text' name='Valor_"+IdParametroServico+"' value='"+Valor+"' style='width:399px;' autocomplete='off' onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out');\" "+tamMax+" "+readOnly+" tabindex="+tabindex+"><input type='hidden' name='Obrigatorio_"+IdParametroServico+"' value='"+Obrigatorio+"'><input type='hidden' name='TamMinimo_"+IdParametroServico+"' value='"+TamMinimo+"'><BR>"+Obs+"</p>";
									}
									break;
								case '2':
									if(ExibirSenha == 1){
										tipo	=	'text';
									}else{
										tipo	=	'password';
									}
									c0.innerHTML = "<p style='margin:0; padding-bottom:1px; padding-left:"+padding+"px'><B style='color:"+color+";'>"+DescricaoOrdemServicoParametro+"</B></p><p style='padding-bottom:6px; padding-left:"+padding+"px; margin:0;'><input type='"+tipo+"' name='Valor_"+IdParametroServico+"' value='"+Valor+"' "+tamMax+" style='width:399px;' autocomplete='off' onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out');\" "+readOnly+" tabindex="+tabindex+"><input type='hidden' name='Obrigatorio_"+IdParametroServico+"' value='"+Obrigatorio+"'><input type='hidden' name='TamMinimo_"+IdParametroServico+"' value='"+TamMinimo+"'><BR>"+Obs+"</p>";
									break;
								case '3': //GRUPO RADIUS
									c0.innerHTML = "<p style='margin:0; padding-bottom:1px; padding-left:"+padding+"px'><B style='color:"+color+";'>"+DescricaoOrdemServicoParametro+"</B></p><p style='padding-bottom:6px; padding-left:"+padding+"px; margin:0;'><input type='text' name='Valor_"+IdParametroServico+"' value='"+Valor+"' style='width:399px;' autocomplete='off' onFocus=\"Foco(this,'in');\" onBlur=\"Foco(this,'out');\" "+tamMax+" readOnly tabindex="+tabindex+"><input type='hidden' name='Obrigatorio_"+IdParametroServico+"' value='"+Obrigatorio+"'><input type='hidden' name='TamMinimo_"+IdParametroServico+"' value='"+TamMinimo+"'><BR>"+Obs+"</p>";
									break;
								case '4': //IPv4
									c0.innerHTML = "<p style='margin:0; padding-bottom:1px; padding-left:"+padding+"px'><B style='color:"+color+";'>"+DescricaoOrdemServicoParametro+"</B></p><p style='padding-bottom:6px; padding-left:"+padding+"px; margin:0;'><input type='text' name='Valor_"+IdParametroServico+"' value='"+Valor+"' style='width:399px;' autocomplete='off' onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out');\" "+tamMax+" "+readOnly+" tabindex="+tabindex+"><input type='hidden' name='Obrigatorio_"+IdParametroServico+"' value='"+Obrigatorio+"'><input type='hidden' name='TamMinimo_"+IdParametroServico+"' value='"+TamMinimo+"'><BR>"+Obs+"</p>";
									break;
								case '5': //IPv6
									c0.innerHTML = "<p style='margin:0; padding-bottom:1px; padding-left:"+padding+"px'><B style='color:"+color+";'>"+DescricaoOrdemServicoParametro+"</B></p><p style='padding-bottom:6px; padding-left:"+padding+"px; margin:0;'><input type='text' name='Valor_"+IdParametroServico+"' value='"+Valor+"' style='width:399px;' autocomplete='off' onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out');\" "+tamMax+" "+readOnly+" tabindex="+tabindex+"><input type='hidden' name='Obrigatorio_"+IdParametroServico+"' value='"+Obrigatorio+"'><input type='hidden' name='TamMinimo_"+IdParametroServico+"' value='"+TamMinimo+"'><BR>"+Obs+"</p>";
									break;
								case '6': //Asterisk
									c0.innerHTML = "<p style='margin:0; padding-bottom:1px; padding-left:"+padding+"px'><B style='color:"+color+";'>"+DescricaoOrdemServicoParametro+"</B></p><p style='padding-bottom:6px; padding-left:"+padding+"px; margin:0;'><input type='text' name='Valor_"+IdParametroServico+"' value='"+Valor+"' style='width:399px;' autocomplete='off' onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out');\" "+tamMax+" "+readOnly+" tabindex="+tabindex+"><input type='hidden' name='Obrigatorio_"+IdParametroServico+"' value='"+Obrigatorio+"'><input type='hidden' name='TamMinimo_"+IdParametroServico+"' value='"+TamMinimo+"'><BR>"+Obs+"</p>";
									break;
							}
						} else{
							campo  = "<p style='margin:0; padding-bottom:1px; padding-left:"+padding+"px'>";
							campo += "<B style='color:"+color+";'>"+DescricaoOrdemServicoParametro+"</B></p>";
							campo += "<p style='padding-bottom:6px; padding-left:"+padding+"px; margin:0;'>";
							
							if(readOnly == "readOnly='true'"){
								readOnly = "disabled='true'";
							}
							
							campo += "<select type='select' name='Valor_"+IdParametroServico+"'  style='width:406px;' onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out');\" "+readOnly+" tabindex="+tabindex+">";
							campo += "<option value=''></option>";
							valor = OpcaoValor.split("\n");
							
							for(var ii=0; ii<valor.length; ii++){
								selecionado = "";
								Valor = Valor.replace("\n","");
								
								if(trim(Valor) == trim(valor[ii])){
									selecionado = "selected='true'";
								}
								
								campo += "<option value='"+trim(valor[ii])+"' "+selecionado+">"+trim(valor[ii])+"</option>";
							}
							
							campo += "</select>";
							campo += "<input type='hidden' name='Obrigatorio_"+IdParametroServico+"' value='"+Obrigatorio+"'><input type='hidden' name='TamMinimo_"+IdParametroServico+"' value='"+TamMinimo+"'>";
							campo += "<BR>"+Obs+"</p>";
							
							c0.innerHTML = campo;
						}
						
						cont++;
					}else{
						invisivel += "<div style='display:none'>";
						
						if(IdTipoParametro == 1){
							invisivel += "<input type='text' name='Valor_"+IdParametroServico+"' value='"+ValorDefault+"' style='width:399px;' autocomplete='off'><input type='hidden' name='Obrigatorio_"+IdParametroServico+"' value='"+Obrigatorio+"'><input type='hidden' name='TamMinimo_"+IdParametroServico+"' value='"+TamMinimo+"'></p>";
						} else{
							campo = "";
							campo += "<select type='select' name='Valor_"+IdParametroServico+"'  style='width:406px;'>";
							campo += "<option value=''></option>";
							
							valor = OpcaoValor.split("\n");
							
							for(var ii=0; ii<valor.length; ii++){
								selecionado = "";
								
								if(trim(ValorDefault) == trim(valor[ii])){
									selecionado = "selected='true'";
								}
								
								campo += "<option value='"+trim(valor[ii])+"' "+selecionado+">"+trim(valor[ii])+"</option>";
							}
							campo += "</select>";
							campo += "<input type='hidden' name='Obrigatorio_"+IdParametroServico+"' value='"+Obrigatorio+"'><input type='hidden' name='TamMinimo_"+IdParametroServico+"' value='"+TamMinimo+"'>";
							
							invisivel += campo;
						}
					}
				}
				
				if(cont > 0){
					document.getElementById('cp_parametrosServico').style.display = 'block';
				}
				
				if(invisivel !=""){
					tam 	= document.getElementById('tabelaParametro').rows.length;
					linha	= document.getElementById('tabelaParametro').insertRow(tam);
					
					linha.accessKey = IdParametroServico; 
					
					c0 = linha.insertCell(0);
					c0.innerHTML = invisivel;
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
	function faturamento(){
		if(document.formulario.IdOrdemServico.value!="" ){
			window.location.replace("cadastro_ordem_servico_fatura.php?IdOrdemServico="+document.formulario.IdOrdemServico.value);
		}
	}
	function listarParametroContrato(IdServico,Erro,IdContrato){
		while(document.getElementById('tabelaParametroContrato').rows.length > 0){
			document.getElementById('tabelaParametroContrato').deleteRow(0);
		}
		
		if(IdServico == ''){
			IdServico = 0;
		}
		
		var url = "xml/contrato_parametro.php?IdServico="+IdServico+"&IdContrato="+IdContrato+"&IdStatus=1&VisivelOS=1";
		
		call_ajax(url,function (xmlhttp){
			if(xmlhttp.responseText == 'false'){
				document.getElementById('cp_parametrosContrato').style.display		= 'none'; 
				document.getElementById('tabelaParametroContrato').style.display	= 'block';
			} else{
				var tam, linha, c0, padding, visivel, color, salvar, tipo = "", obsTemp = new Array();
				
				for(var i = 0; i < xmlhttp.responseXML.getElementsByTagName("IdParametroServico").length; i++){
					if(i == 0){
						document.getElementById('cp_parametrosContrato').style.display		= 'block';
						document.getElementById('tabelaParametroContrato').style.display	= 'block';
					}
					
					var nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoParametroServico")[i]; 
					var nameTextNode = nameNode.childNodes[0];
					var DescricaoParametroServico = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("Valor")[i]; 
					nameTextNode = nameNode.childNodes[0];
					var Valor = nameTextNode.nodeValue;
					
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
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("IdTipoTexto")[i]; 
					nameTextNode = nameNode.childNodes[0];
					var IdTipoTexto = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("ExibirSenha")[i]; 
					nameTextNode = nameNode.childNodes[0];
					var ExibirSenha = nameTextNode.nodeValue;
					
					tam = document.getElementById('tabelaParametroContrato').rows.length;
					
					obsTemp[i] = Obs;
					
					if(i%2 == 0){
						linha		= document.getElementById('tabelaParametroContrato').insertRow(tam);
						padding		= 22;
						pos			= 0;
					} else{
						padding		= 10;
						pos			= 1;
						
						if(obsTemp[(i-1)]!= undefined && obsTemp[(i-1)]!= ''){
							if(Obs == '')
								Obs = '<BR>';
						}
					}
					
					if((i+1) == xmlhttp.responseXML.getElementsByTagName("IdParametroServico").length && i%2 == 0){
						padding = 22;
					}
					
					linha.accessKey = IdParametroServico; 
					
					c0 = linha.insertCell(pos);
					c0.style.verticalAlign = "top";
					
					if(IdTipoParametro == 1){
						switch(IdTipoTexto){
							case '1':
								switch(IdMascaraCampo){
									case '1':
										c0.innerHTML = "<p style='margin:0; padding-bottom:1px; padding-left:"+padding+"px'><B style='color:#000;'>"+DescricaoParametroServico+"</B></p><p style='padding-bottom:6px; padding-left:"+padding+"px; margin:0;'><input type='text' name='ValorContrato_"+(i+1)+"' value='"+Valor+"' style='width:399px;' autocomplete='off' "+visivel+" readOnly maxlength='10'><BR>"+Obs+"</p>";
										break;
									case '2':
										c0.innerHTML = "<p style='margin:0; padding-bottom:1px; padding-left:"+padding+"px'><B style='color:#000;'>"+DescricaoParametroServico+"</B></p><p style='padding-bottom:6px; padding-left:"+padding+"px; margin:0;'><input type='text' name='ValorContrato_"+(i+1)+"' value='"+Valor+"' style='width:399px;' autocomplete='off' "+visivel+" readOnly><BR>"+Obs+"</p>";
										break;
									case '3':
										c0.innerHTML = "<p style='margin:0; padding-bottom:1px; padding-left:"+padding+"px'><B style='color:#000;'>"+DescricaoParametroServico+"</B></p><p style='padding-bottom:6px; padding-left:"+padding+"px; margin:0;'><input type='text' name='ValorContrato_"+(i+1)+"' value='"+Valor+"' style='width:399px;' autocomplete='off' "+visivel+" maxlength='12' readOnly><BR>"+Obs+"</p>";
										break;
									case '5':
										c0.innerHTML = "<p style='margin:0; padding-bottom:1px; padding-left:"+padding+"px'><B style='color:#000;'>"+DescricaoParametroServico+"</B></p><p style='padding-bottom:6px; padding-left:"+padding+"px; margin:0;'><input type='text' name='ValorContrato_"+(i+1)+"' value='"+Valor+"' style='width:399px;' autocomplete='off' "+visivel+" maxlength='17' readOnly><BR>"+Obs+"</p>";
										break;
									default:
										c0.innerHTML = "<p style='margin:0; padding-bottom:1px; padding-left:"+padding+"px'><B style='color:#000;'>"+DescricaoParametroServico+"</B></p><p style='padding-bottom:6px; padding-left:"+padding+"px; margin:0;'><input type='text' name='ValorContrato_"+(i+1)+"' value='"+Valor+"' style='width:399px;' "+visivel+" readOnly><BR>"+Obs+"</p>";
								}
								break;
							case '2':
								if(ExibirSenha == 1){
									tipo = 'text';
								} else{
									tipo = 'password';
								}
								
								c0.innerHTML = "<p style='margin:0; padding-bottom:1px; padding-left:"+padding+"px'><B style='color:#000;'>"+DescricaoParametroServico+"</B></p><p style='padding-bottom:6px; padding-left:"+padding+"px; margin:0;'><input type='"+tipo+"' name='ValorContrato_"+(i+1)+"' value='"+Valor+"' style='width:399px;' "+visivel+" readOnly><BR>"+Obs+"</p>";
								break;
							case '3': //GRUPO RADIUS
								c0.innerHTML = "<p style='margin:0; padding-bottom:1px; padding-left:"+padding+"px'><B style='color:#000;'>"+DescricaoParametroServico+"</B></p><p style='padding-bottom:6px; padding-left:"+padding+"px; margin:0;'><input type='text' name='ValorContrato_"+(i+1)+"' value='"+Valor+"' style='width:399px;' "+visivel+" readOnly><BR>"+Obs+"</p>";
								break;
							case '4': //IPv4
								c0.innerHTML = "<p style='margin:0; padding-bottom:1px; padding-left:"+padding+"px'><B style='color:#000;'>"+DescricaoParametroServico+"</B></p><p style='padding-bottom:6px; padding-left:"+padding+"px; margin:0;'><input type='text' name='ValorContrato_"+(i+1)+"' value='"+Valor+"' style='width:399px;' "+visivel+" readOnly><BR>"+Obs+"</p>";
								break;
							case '5': //IPv6
								c0.innerHTML = "<p style='margin:0; padding-bottom:1px; padding-left:"+padding+"px'><B style='color:#000;'>"+DescricaoParametroServico+"</B></p><p style='padding-bottom:6px; padding-left:"+padding+"px; margin:0;'><input type='text' name='ValorContrato_"+(i+1)+"' value='"+Valor+"' style='width:399px;' "+visivel+" readOnly><BR>"+Obs+"</p>";
								break;
							case '6': //Asterisk
								c0.innerHTML = "<p style='margin:0; padding-bottom:1px; padding-left:"+padding+"px'><B style='color:#000;'>"+DescricaoParametroServico+"</B></p><p style='padding-bottom:6px; padding-left:"+padding+"px; margin:0;'><input type='text' name='ValorContrato_"+(i+1)+"' value='"+Valor+"' style='width:399px;' "+visivel+" readOnly><BR>"+Obs+"</p>";
								break;
						}
					} else{
						campo = "<p style='margin:0; padding-bottom:1px; padding-left:"+padding+"px'>";
						campo += "<B style='color:"+color+";'>"+DescricaoParametroServico+"</B></p>";
						campo += "<p style='padding-bottom:6px; padding-left:"+padding+"px; margin:0;'>";
						campo += "<select name='ValorContrato_"+(i+1)+"'  style='width:406px;' "+visivel+" disabled>";
						campo += "<option value=''></option>";
						
						valor = OpcaoValor.split("\n");
						
						for(var ii = 0; ii < valor.length; ii++){
							selecionado = "";
							Valor = Valor.replace("\n","");	
							
							if(trim(Valor) == trim(valor[ii])){
								selecionado = "selected=true";
							}
							
							campo += "<option value='"+trim(valor[ii])+"' "+selecionado+">"+trim(valor[ii])+"</option>";
						}
						
						campo += "</select>";
						campo += "<BR>"+Obs+"</p>";
						
						c0.innerHTML = campo;
					}
				}
			}
		});
	}
	function chama_mascara(campo,event){
		switch(document.filtro.filtro_campo.value){
			case 'DataAgendamento':
				campo.maxLength	=	10;
				return mascara(campo,event,'date');
				break;
			case 'DataAlteracao':
				campo.maxLength	=	10;
				return mascara(campo,event,'date');
				break;
			case 'DataFaturamento':
				campo.maxLength	=	10;
				return mascara(campo,event,'date');
				break;
			case 'MesFaturamento':
				campo.maxLength	=	7;
				return mascara(campo,event,'mes');
				break;
			case 'MesAgendamento':
				campo.maxLength	=	7;
				return mascara(campo,event,'mes');
				break;	
			case 'MesAlteracao':
				campo.maxLength	=	7;
				return mascara(campo,event,'mes');
				break;
			case 'DataCadastro':
				campo.maxLength	=	10;
				return mascara(campo,event,'date');
				break;
			case 'DataVencimento':
				campo.maxLength	=	10;
				return mascara(campo,event,'date');
				break;
			case 'DataFatura':
				campo.maxLength	=	10;
				return mascara(campo,event,'date');
				break;
			case 'DataConclusao':
				campo.maxLength	=	10;
				return mascara(campo,event,'date');
				break;
			case 'DataFaturamento':
				campo.maxLength	=	10;
				return mascara(campo,event,'date');
				break;
			case 'MesFatura':
				campo.maxLength	=	7;
				return mascara(campo,event,'mes');
				break;
			case 'MesCadastro':
				campo.maxLength	=	7;
				return mascara(campo,event,'mes');
				break;
			case 'MesVencimento':
				campo.maxLength	=	7;
				return mascara(campo,event,'mes');
				break;
			case 'MesConclusao':
				campo.maxLength	=	7;
				return mascara(campo,event,'mes');
				break;
			case 'DataCancelamento':
				campo.maxLength	=	10;
				return mascara(campo,event,'date');
				break;
			case 'MesCancelamento':
				campo.maxLength	=	7;
				return mascara(campo,event,'mes');
				break;				
			default:
				campo.maxLength	=	100;
		}
	}
	function busca_opcoes_pessoa_endereco(IdPessoa,IdPessoaEnderecoTemp){
		if(IdPessoaEnderecoTemp == undefined)	IdPessoaEnderecoTemp	= "";
		
		if(IdPessoa != ""){
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
		    
		    url = "xml/pessoa_endereco.php?IdPessoa="+IdPessoa;
			
			xmlhttp.open("GET", url,true);
			xmlhttp.onreadystatechange = function(){
				// Carregando...
				carregando(true);
				
				if(xmlhttp.readyState == 4){ 
					if(xmlhttp.status == 200){
						if(xmlhttp.responseText != 'false'){
							while(document.formulario.IdPessoaEndereco.options.length > 0){
								document.formulario.IdPessoaEndereco.options[0] = null;
							}
							
							var nameNode, nameTextNode, IdPessoaEndereco,DescricaoEndereco;					
							
							addOption(document.formulario.IdPessoaEndereco,"","0");
							
							for(i=0;i<xmlhttp.responseXML.getElementsByTagName("IdPessoaEndereco").length;i++){
								nameNode = xmlhttp.responseXML.getElementsByTagName("IdPessoaEndereco")[i]; 
								nameTextNode = nameNode.childNodes[0];
								IdPessoaEndereco = nameTextNode.nodeValue;
								
								nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoEndereco")[i]; 
								nameTextNode = nameNode.childNodes[0];
								DescricaoEndereco = nameTextNode.nodeValue;
								
								addOption(document.formulario.IdPessoaEndereco,DescricaoEndereco,IdPessoaEndereco);
							}
							
							document.formulario.IdPessoaEndereco[0].selected 		 = true;
							document.formulario.IdPessoaEnderecoTemp.value			= '';
							
							if(IdPessoaEnderecoTemp!=""){
								for(i=0;i<document.formulario.IdPessoaEndereco.options.length;i++){
									if(document.formulario.IdPessoaEndereco[i].value == IdPessoaEnderecoTemp){
										document.formulario.IdPessoaEnderecoTemp.value		=	IdPessoaEnderecoTemp;
										document.formulario.IdPessoaEndereco[i].selected	=	true;
										i	=	document.formulario.IdPessoaEndereco.options.length;
									}
								}
							}
						} else{
							while(document.formulario.IdPessoaEndereco.options.length > 0){
								document.formulario.IdPessoaEndereco.options[0] = null;
							}
							
							document.formulario.IdPessoaEnderecoTemp.value = '';
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
	function busca_pessoa_endereco(IdPessoa,IdPessoaEndereco){
		if(IdPessoa==''){
			IdPessoa = 0;
		}
		if(IdPessoaEndereco=='' || IdPessoaEndereco==undefined){
			IdPessoaEndereco = 0;
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
	    
	    url = "xml/pessoa_endereco.php?IdPessoa="+IdPessoa+"&IdPessoaEndereco="+IdPessoaEndereco;
		
		xmlhttp.open("GET", url,true);
		xmlhttp.onreadystatechange = function(){ 
			// Carregando...
			carregando(true);
			
			if(xmlhttp.readyState == 4){ 
				if(xmlhttp.status == 200){
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
				}
				// Fim de Carregando
				carregando(false);
			}
			return true;
		}
		xmlhttp.send(null);	
	}
	function addMarcador(IdMarcador){
		if(document.formulario.bt_alterar.disabled != true){
			if(document.formulario.Acao.value != 'inserir'){
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
			    
			    url = "files/editar/editar_ordem_servico_marcador.php?IdOrdemServico="+document.formulario.IdOrdemServico.value+"&IdMarcador="+IdMarcador;
				xmlhttp.open("GET", url,true);
				xmlhttp.onreadystatechange = function(){ 
					// Carregando...
					carregando(true);
					
					if(xmlhttp.readyState == 4){ 
						if(xmlhttp.status == 200){
							if(xmlhttp.responseText != "false"){
								nameNode = xmlhttp.responseXML.getElementsByTagName("IdMarcadorAux")[0]; 
								nameTextNode = nameNode.childNodes[0];
								var IdMarcadorAux = nameTextNode.nodeValue;
								
								nameNode = xmlhttp.responseXML.getElementsByTagName("HistoricoObs")[0]; 
								nameTextNode = nameNode.childNodes[0];
								var HistoricoObs = nameTextNode.nodeValue;
								
								nameNode = xmlhttp.responseXML.getElementsByTagName("CorMarcador1")[0]; 
								nameTextNode = nameNode.childNodes[0];
								var CorMarcador1 = nameTextNode.nodeValue;
								
								nameNode = xmlhttp.responseXML.getElementsByTagName("CorMarcador2")[0]; 
								nameTextNode = nameNode.childNodes[0];
								var CorMarcador2 = nameTextNode.nodeValue;
								
								nameNode = xmlhttp.responseXML.getElementsByTagName("CorMarcador3")[0]; 
								nameTextNode = nameNode.childNodes[0];
								var CorMarcador3 = nameTextNode.nodeValue;
								
								if(IdMarcadorAux != 2){
									document.getElementById('mVermelho').style.backgroundColor	=	CorMarcador1;
									document.getElementById('mAmarelo').style.backgroundColor	=	CorMarcador2;
									document.getElementById('mVerde').style.backgroundColor		=	CorMarcador3;
									document.formulario.IdMarcador.value	=	IdMarcador;
									
									if(IdMarcadorAux != '1' && IdMarcadorAux != '22' && IdMarcadorAux != '3'){
										document.formulario.IdMarcador.value	=	'';
									}
									
									document.formulario.HistoricoObs.value = HistoricoObs;
								}else{
									document.formulario.Erro.value	=	parseInt(xmlhttp.responseText);
									
									mensagens(document.formulario.Erro.value);
								}
							}
						}
						// Fim de Carregando
						carregando(false);
					}
					return true;
				}
				xmlhttp.send(null);	
			}else{
				if(document.formulario.IdMarcador.value == IdMarcador){
					IdMarcador = 0;
				}
			
				switch(IdMarcador){
					case '2':
						document.getElementById('mVermelho').style.backgroundColor	=	'#FFD9D9';
						document.getElementById('mAmarelo').style.backgroundColor	=	'#F9F900';
						document.getElementById('mVerde').style.backgroundColor		=	'#D5FFD5';
						document.formulario.IdMarcador.value	=	IdMarcador;
						break;
					case '3':
						document.getElementById('mVermelho').style.backgroundColor	=	'#FFD9D9';
						document.getElementById('mAmarelo').style.backgroundColor	=	'#FFFFCE';
						document.getElementById('mVerde').style.backgroundColor		=	'#008000';
						document.formulario.IdMarcador.value	=	IdMarcador;
						break;
					case '1':
						document.getElementById('mVermelho').style.backgroundColor	=	'#FF0000';
						document.getElementById('mAmarelo').style.backgroundColor	=	'#FFFFCE';
						document.getElementById('mVerde').style.backgroundColor		=	'#D5FFD5';
						document.formulario.IdMarcador.value	=	IdMarcador;
						break;
					default:
						document.getElementById('mVermelho').style.backgroundColor	=	'#FFD9D9';
						document.getElementById('mAmarelo').style.backgroundColor	=	'#FFFFCE';
						document.getElementById('mVerde').style.backgroundColor		=	'#D5FFD5';
						document.formulario.IdMarcador.value	=	'';
						break;
				}
			}
		}
	}
	function busca_status_novo(IdGrupoParametroSistema, campo, IdStatus, IdTipoOrdemServico){
		
		if(IdGrupoParametroSistema == ''){
			IdGrupoParametroSistema = 0;
		}
		if(IdTipoOrdemServico == ''){
			IdTipoOrdemServico = 0;
		}
		var IdStatus = document.formulario.IdStatus.value;
		
		url = "xml/status_novo.php?IdGrupoParametroSistema="+IdGrupoParametroSistema+"&IdStatus="+IdStatus;
		
		call_ajax(url, function(xmlhttp){
			if(xmlhttp.responseText == 'false'){
				while(campo.options.length > 0){
					campo.options[0] = null;
				}
			}else{
				while(campo.options.length > 0){
					campo.options[0] = null;
				}
				if(document.filtro.IdStatusNovo != undefined){
					addOption(campo,"Todos","");
				}else{
					addOption(campo,"","");
				}
				
				for(var i=0; i<xmlhttp.responseXML.getElementsByTagName("IdStatusNovo").length; i++){
					nameNode = xmlhttp.responseXML.getElementsByTagName("IdStatusNovo")[i]; 
					nameTextNode = nameNode.childNodes[0];
					var IdStatusNovo = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("SelectStatusNovo")[i]; 
					nameTextNode = nameNode.childNodes[0];
					var SelectStatusNovo = nameTextNode.nodeValue;
					
					if((Number(document.formulario.PermisaoConcluirOS.value) == 1 && IdStatusNovo > 199 && IdStatusNovo < 300) || IdStatusNovo < 200 || IdStatusNovo > 299) {
						if(document.formulario.Acao.value == "inserir"){
							if(IdStatusNovo>99 && IdStatusNovo<299){
								addOption(campo,SelectStatusNovo,IdStatusNovo);
							}
						} else{
							if(IdTipoOrdemServico==2){
								if(IdStatusNovo>99 && IdStatusNovo<399){
									addOption(campo,SelectStatusNovo,IdStatusNovo);
								}
							}else{
								if(IdStatusNovo>99){
									addOption(campo,SelectStatusNovo,IdStatusNovo);
								}								
							}
						}	
					}						
				}
				campo.value = IdStatus;
			}
		});
	}
	function calcula_total(){
		var Valor		=	document.formulario.Valor.value;
		var ValorOutros	=	document.formulario.ValorOutros.value;
		
		if(Valor == ''){
			Valor = "0,00";
		}
		
		if(ValorOutros == ''){
			ValorOutros = "0,00";
		}
		
		Valor		=	Valor.replace(".","");
		Valor		=	Valor.replace(".","");
		Valor		=	Valor.replace(".","");
		Valor		=	Valor.replace(",",".");
		
		ValorOutros	=	ValorOutros.replace(".","");
		ValorOutros	=	ValorOutros.replace(".","");
		ValorOutros	=	ValorOutros.replace(".","");
		ValorOutros	=	ValorOutros.replace(",",".");
		
		Valor 		= parseFloat(Valor);
		ValorOutros	=	parseFloat(ValorOutros);
		
		var ValorTotal	= parseFloat(Valor+ValorOutros);
		
		ValorTotal		= 	formata_float(Arredonda(ValorTotal,2),2);
		
		if(ValorOutros > 0){
			document.formulario.Justificativa.readOnly	=	false;
			document.getElementById("titJustificativa").style.color = "#C10000";
			document.formulario.Justificativa.blur();
			document.formulario.Justificativa.focus();
		} else{
			document.getElementById("titJustificativa").style.color = "#000";
			document.formulario.Justificativa.readOnly	=	true;
			document.formulario.Justificativa.value		=	'';
		}
		
		ValorTotal		=	ValorTotal.replace('.',',');
		
		document.formulario.ValorTotal.value = ValorTotal;					
	}
	function listarOrdemServicoCliente(IdPessoa){
		if(IdPessoa == undefined || IdPessoa==''){
			IdPessoa = 0;
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
	    
	   	url = "xml/ordem_servico_cliente.php?IdPessoa="+IdPessoa+"&IdOrdemServico="+document.formulario.IdOrdemServico.value;
		
		xmlhttp.open("GET", url,true);
		
		xmlhttp.onreadystatechange = function(){
			// Carregando...
			carregando(true);
			if(xmlhttp.readyState == 4){
				if(xmlhttp.status == 200){
					if(xmlhttp.responseText == 'false'){
						document.getElementById('cp_ordemServicoCliente').style.display		= "none";
						document.getElementById('tabelaOrdemServicoClienteTotal').innerHTML	=	"Total: 0";
						
						// Fim de Carregando
						carregando(false);
					}else{
						document.getElementById('cp_ordemServicoCliente').style.display		= "block";
						
						while(document.getElementById('tabelaOrdemServicoCliente').rows.length > 2){
							document.getElementById('tabelaOrdemServicoCliente').deleteRow(1);
						}
						
						var cabecalho, tam, linha, c0, c1, c2, c3, c4, c5, c6, c7;
						var IdOrdemServico, Cliente, ClienteTitle, Descricao, DescricaoTitle, DescricaoSubTipo, Atendimento, DataAgendamentoAtendimento, TempoAbertura, Status, Cor, DataCriacao, LoginCriacao, linkIni, linkFim;
						for(var i=0; i<xmlhttp.responseXML.getElementsByTagName("IdOrdemServico").length; i++){	
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("IdOrdemServico")[i]; 
							nameTextNode = nameNode.childNodes[0];
							IdOrdemServico = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("Cliente")[i]; 
							nameTextNode = nameNode.childNodes[0];
							Cliente = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("ClienteTitle")[i]; 
							nameTextNode = nameNode.childNodes[0];
							ClienteTitle = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("Descricao")[i]; 
							nameTextNode = nameNode.childNodes[0];
							Descricao = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoTitle")[i]; 
							nameTextNode = nameNode.childNodes[0];
							DescricaoTitle = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoSubTipo")[i]; 
							nameTextNode = nameNode.childNodes[0];
							DescricaoSubTipo = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("Atendimento")[i]; 
							nameTextNode = nameNode.childNodes[0];
							Atendimento = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("DataAgendamentoAtendimento")[i]; 
							nameTextNode = nameNode.childNodes[0];
							DataAgendamentoAtendimento = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("TempoAbertura")[i]; 
							nameTextNode = nameNode.childNodes[0];
							TempoAbertura = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("Status")[i]; 
							nameTextNode = nameNode.childNodes[0];
							Status = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("Cor")[i]; 
							nameTextNode = nameNode.childNodes[0];
							Cor = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("DataCriacao")[i]; 
							nameTextNode = nameNode.childNodes[0];
							DataCriacao = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("LoginCriacao")[i]; 
							nameTextNode = nameNode.childNodes[0];
							LoginCriacao = nameTextNode.nodeValue;
							
							tam 	= document.getElementById('tabelaOrdemServicoCliente').rows.length;
							linha	= document.getElementById('tabelaOrdemServicoCliente').insertRow(tam-1);
							
							if(Cor != ''){
								linha.style.backgroundColor = Cor;
							} else{
								if((i % 2) != 0){
									linha.style.backgroundColor = "#E2E7ED";
								}
							}
							
							linha.accessKey = IdOrdemServico; 
							
							c0	= linha.insertCell(0);	
							c1	= linha.insertCell(1);	
							c2	= linha.insertCell(2);	
							c3	= linha.insertCell(3);	
							c4	= linha.insertCell(4);
							c5	= linha.insertCell(5);
							c6	= linha.insertCell(6);
							c7	= linha.insertCell(7);
							
							linkIni	= "<a href='cadastro_ordem_servico.php?IdOrdemServico=" + IdOrdemServico + "' target='_blank' ";
							linkFim	= "</a>";
							
							c0.innerHTML = linkIni + ">" + IdOrdemServico + linkFim;
							c0.style.padding  =	"0 0 0 5px";
							
							c1.innerHTML = linkIni + " onmousemove='quadro_alt(event, this, \"" + (new String(ClienteTitle.replace(/\n|\r\n/g,'<br />').replace(/\t/,''))) + "\");'>" + Cliente + linkFim;
							
							c2.innerHTML = linkIni + " onmousemove='quadro_alt(event, this, \"" + (new String(DescricaoTitle.replace(/\n|\r\n/g,'<br />').replace(/\t/,''))).replace(/['"]/g,'\\"') + "\");'>" + Descricao + linkFim;
							
							c3.innerHTML = linkIni + ">" + DescricaoSubTipo + linkFim;
							
							c4.innerHTML = linkIni + ">" + Atendimento + linkFim;
							
							c5.innerHTML = linkIni + ">" + DataAgendamentoAtendimento + linkFim;
							
							c6.innerHTML = linkIni + ">" + TempoAbertura + linkFim;
							
							c7.innerHTML = linkIni + ">" + Status + linkFim;
						}
						
						document.getElementById('tabelaOrdemServicoClienteTotal').innerHTML			=	"Total: "+i;
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
	function addArquivo(IdArquivo, EndArquivo, DescArquivo){
		if((document.formulario.bt_alterar.disabled == true) && (document.formulario.bt_inserir.disabled == true)){
			if(parseInt(document.formulario.CountArquivo.value) > 0){
				return false;
			}
			
			Disabled = "disabled";
			ReadOnly = "readOnly";
			var Img = "../../img/estrutura_sistema/ico_del_c.gif";
		} else{
			if(EndArquivo == '' || EndArquivo == undefined){
				EndArquivo = '';
				Disabled = '';
				ReadOnly = '';
				var Img = "../../img/estrutura_sistema/ico_del.gif";
			} else{
				Disabled = "disabled";
				ReadOnly = "readOnly";
				var Img = "../../img/estrutura_sistema/ico_del_c.gif";
			}
		}
		
		document.formulario.CountArquivo.value = parseInt(document.formulario.CountArquivo.value) + parseInt(1);
		
		if(IdArquivo == '' || IdArquivo == undefined){
			IdArquivo = '';	
		}
		
		if(EndArquivo == '' || EndArquivo == undefined){
			EndArquivo = '';
		}
		
		if(DescArquivo == '' || DescArquivo == undefined){
			DescArquivo = '';
		}
		
		var CountArquivo = parseInt(document.formulario.CountArquivo.value);
		var tam, linha, c0, c1, c2, c3, c4;
		var tabindex = CountArquivo+109;
		
		if(CountArquivo > document.formulario.MaxUploads.value && document.formulario.MaxUploads.value != ''){
			return false;
		}
		
		tam 	= document.getElementById('tabelaArquivos').rows.length;
		linha	= document.getElementById('tabelaArquivos').insertRow(tam);
		linha.accessKey = tam;
		
		c0	= linha.insertCell(0);
		c1	= linha.insertCell(1);
		c2	= linha.insertCell(2);
		c3	= linha.insertCell(3);
		c4	= linha.insertCell(4);
		
		c0.className	= "find";		
		c0.innerHTML	= "&nbsp;";
		c1.className	= "descCampo";
		c1.innerHTML	= "Anexar Arquivo";
		c2.className	= "separador";
		c2.innerHTML	= "&nbsp;";
		c3.className	= "descCampo";
		c3.innerHTML	= "<B id='titDescricaoArquivo_"+CountArquivo+"' style='color:#000;'>Descrição do Arquivo</B>";
		c4.className	= "find";
		c4.innerHTML	= "&nbsp;";
		
		tam		= document.getElementById('tabelaArquivos').rows.length;
		linha	= document.getElementById('tabelaArquivos').insertRow(tam);
		linha.accessKey = tam;
		
		c0	= linha.insertCell(0);
		c1	= linha.insertCell(1);
		c2	= linha.insertCell(2);
		c3	= linha.insertCell(3);
		c4	= linha.insertCell(4);
		
		c0.className			= "find";
		c0.innerHTML			= "&nbsp;";
		c1.className			= "campo";
		c1.innerHTML			= "<input type='text' name='fakeupload_"+CountArquivo+"' value='"+EndArquivo+"' autocomplete='off' style='width:356px; margin:0px;' onchange='verificar_obrigatoriedade(this, document.formulario.DescricaoArquivo_"+CountArquivo+", "+CountArquivo+");' onFocus=\"Foco(this,'in','auto');\" onBlur=\"Foco(this,'out');\" tabindex='"+tabindex+"' "+ReadOnly+"><div id='bt_procurar' style='margin:-22px 0px 0px 360px;' tabindex='"+tabindex+"'></div><input type='file' id='realupload' name='EndArquivo_"+CountArquivo+"' size='1' class='realupload' onchange='verificar_obrigatoriedade(this, document.formulario.DescricaoArquivo_"+CountArquivo+", "+CountArquivo+"); document.formulario.fakeupload_"+CountArquivo+".value = this.value;' "+Disabled+"/><div style='margin:-1px 0px 4px 0px;'>Atenção, tamanho máximo do arquivo é "+document.formulario.MaxSize.value+". <span title='"+document.formulario.ExtensaoAnexo.value.replace(/,/g, ', ')+".'>Tipos de arquivo a anexar.<span></div>";
		c2.className			= "separador";
		c2.innerHTML			= "&nbsp;";
		c3.className			= "campo";
		c3.style.verticalAlign	= "top";
		c3.innerHTML			= "<input type='text' name='DescricaoArquivo_"+CountArquivo+"' value='"+DescArquivo+"' style='width:338px; margin-top:0px;' tabindex='"+tabindex+"' maxlength='100' onFocus=\"Foco(this,'in');\"  onBlur=\"Foco(this,'out');\" readOnly>";
		c4.className			= "find";
		c4.style.verticalAlign	= "top";
		c4.innerHTML			= "<img src='" + Img + "' style='margin-top:6px;' alt='Excluir title='Excluir' onClick=\"excluir_arquivo('"+CountArquivo+"','"+IdArquivo+"');\">";
	}
	function verificar_obrigatoriedade(campo1, campo2, CountArquivo){
		if(campo1.value != ''){
			var temp = campo1.value.split('.');
			var ext = temp[temp.length-1].toLowerCase();
			document.formulario.Erro.value = 0;
			
			if(!document.formulario.ExtensaoAnexo.value.split(',').in_array(ext) && ext != ''){
				for(i = 0; i<document.formulario.length; i++){
					if(document.formulario[i].name != undefined){
						if(document.formulario[i].name == campo1.name){
							document.formulario.Erro.value = 192;
							
							document.formulario[i-1].focus();
						}
					}
				}
			} else{
				for(i = 0; i<document.formulario.length; i++){
					if(document.formulario[i].name != undefined){
						if(document.formulario[i].name.substring(0,11) == 'EndArquivo_'){
							var temp = document.formulario[i].value.split('.');
							var ext = temp[temp.length-1].toLowerCase();
							
							if(!document.formulario.ExtensaoAnexo.value.split(',').in_array(ext) && ext != ''){
								mensagens(192);
								document.formulario[i-1].focus();
							}
						}
					}
				}
			}
			
			document.getElementById("titDescricaoArquivo_"+CountArquivo).style.color = "#C10000";
			campo2.readOnly = false;
			verificaErro();
		} else{
			document.getElementById("titDescricaoArquivo_"+CountArquivo).style.color = "#000000";
			campo2.readOnly = true;
			campo2.value = '';
		}
	}
	function excluir_arquivo(pos, IdArquivo){
		if(IdArquivo == ''){
			if((document.formulario.bt_alterar.disabled == true) && (document.formulario.bt_inserir.disabled == true) && parseInt(document.formulario.CountArquivo.value) > 0){
				return false;
			}
			if(confirm("ATENÇÃO\n\nVocê está prestes a excluir este arquivo.\nDeseja continuar?") == false){
				return false;
			}
			
			var tam = document.getElementById('tabelaArquivos').rows.length;
			
			var cont = 1;
			for(var i=1; i<=tam; i++){
				if(document.getElementById('tabelaArquivos').rows[i].accessKey == (pos*2)-1){
					document.getElementById('tabelaArquivos').deleteRow(i);
					document.getElementById('tabelaArquivos').deleteRow(i-1);
					i=tam;
					document.formulario.CountArquivo.value	= parseInt(document.formulario.CountArquivo.value) - 1;
					break;
				}
			}
			
			if(parseInt(document.formulario.CountArquivo.value) == 0){
				addArquivo();
			}
		} else{
			if(confirm("ATENÇÃO\n\nA exclusão só será efetuada após a alteração da ordem de serviço.\nDeseja continuar?") == false){
				return false;
			}
			
			var tam = document.getElementById('tabelaArquivosAnexados').rows.length-2;
			
			for(var i = 1; i <= tam; i++){
				if(document.getElementById('tabelaArquivosAnexados').rows[i].accessKey == IdArquivo){
					document.getElementById('tabelaArquivosAnexados').deleteRow(i);
					
					document.formulario.EcluirAnexos.value += ',' + IdArquivo;
					break;
				}
			}
			
			tam = (document.getElementById('tabelaArquivosAnexados').rows.length-2);
			
			if(tam > 0){
				document.getElementById("cpArquivosAnexados").style.display = "block";
				document.getElementById('tabelaValorTotal').innerHTML = "Total: " + tam;
			} else {
				document.getElementById("cpArquivosAnexados").style.display = "none";
			}
		}
	}
	function buscar_arquivo(IdOrdemServico){
		if(IdOrdemServico == '' || IdOrdemServico == undefined){
			IdOrdemServico = 0;
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
		
		url = "./xml/ordem_servico_anexo.php?IdOrdemServico="+IdOrdemServico;
		xmlhttp.open("GET", url,true);
		xmlhttp.onreadystatechange = function(){ 
			// Carregando...
			carregando(true);
			if(xmlhttp.readyState == 4){ 
				if(xmlhttp.status == 200){
					if(xmlhttp.responseText == 'false'){
						document.getElementById("cpArquivosAnexados").style.display = "none";
						
						while(document.getElementById('tabelaArquivosAnexados').rows.length > 2){
							document.getElementById('tabelaArquivosAnexados').deleteRow(1);
						}
					}else{
						document.getElementById("cpArquivosAnexados").style.display = "block";
						
						while(document.getElementById('tabelaArquivosAnexados').rows.length > 2){
							document.getElementById('tabelaArquivosAnexados').deleteRow(1);
						}
						
						var  tam, linha, c0, c1, c2, c3, c4, c5, linkIni, linkFin;
						var nameNode, nameTextNode, EndArquivo, IdLoja, IdOrdemServico, IdAnexo, Anexo, DescricaoAnexo, NomeOriginal, MD5;
						
						for(var i=0; i<xmlhttp.responseXML.getElementsByTagName("IdLoja").length; i++){
							nameNode = xmlhttp.responseXML.getElementsByTagName("IdLoja")[i]; 
							nameTextNode = nameNode.childNodes[0];
							IdLoja = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("IdOrdemServico")[i]; 
							nameTextNode = nameNode.childNodes[0];
							IdOrdemServico = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("IdAnexo")[i]; 
							nameTextNode = nameNode.childNodes[0];
							IdAnexo = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("IMG")[i]; 
							nameTextNode = nameNode.childNodes[0];
							IMG = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("Tamanho")[i]; 
							nameTextNode = nameNode.childNodes[0];
							Tamanho = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoAnexo")[i]; 
							nameTextNode = nameNode.childNodes[0];
							DescricaoAnexo = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("NomeOriginal")[i]; 
							nameTextNode = nameNode.childNodes[0];
							NomeOriginal = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("MD5")[i]; 
							nameTextNode = nameNode.childNodes[0];
							MD5 = nameTextNode.nodeValue;
							
							tam 	= document.getElementById('tabelaArquivosAnexados').rows.length;
							linha	= document.getElementById('tabelaArquivosAnexados').insertRow(tam-1);
							
							if(i%2 != 0){
								linha.style.backgroundColor = "#E2E7ED";
							}
							
							linha.accessKey = IdAnexo; 
							
							c0	= linha.insertCell(0);
							c1	= linha.insertCell(1);
							c2	= linha.insertCell(2);
							c3	= linha.insertCell(3);
							c4	= linha.insertCell(4);
							c5	= linha.insertCell(5);
							
							linkIni	= "<a href='./download_anexo_ordem_servico.php?Anexo=" + MD5 + "'>";
							linkFin	= "</a>";
							
							c0.innerHTML		= linkIni + IMG + linkFin;
							c0.style.padding	= "2px 0 2px 5px";
							
							c1.innerHTML		= linkIni + NomeOriginal + linkFin;
							c1.style.padding	= "2px 2px 2px 0px";
							
							c2.innerHTML		= linkIni + DescricaoAnexo + linkFin;
							c2.style.padding	= "2px 2px 2px 0px";
							
							c3.innerHTML		= linkIni + Tamanho + linkFin;
							c3.style.width		= "88px";
							c3.style.padding	= "2px 2px 2px 0px";
							
							c4.innerHTML		= linkIni + "Clique aqui" + linkFin;
							c4.style.width		= "72px";
							c4.style.padding	= "2px 2px 2px 0px";
							
							if(document.formulario.bt_alterar.disabled){
								c5.innerHTML	= "<img src='../../img/estrutura_sistema/ico_del_c.gif' alt='Excluir'>";
							} else{
								c5.innerHTML	= "<img src='../../img/estrutura_sistema/ico_del.gif' alt='Excluir' title='Excluir' onClick=\"excluir_arquivo('" + tam + "','" + IdAnexo + "');\">";
							}
							c5.style.cursor		= "pointer";
							c5.style.padding	= "2px 2px 2px 0px";
						}
						
						document.getElementById('tabelaValorTotal').innerHTML = "Total: " + i;
					}
				}
				// Fim de Carregando
				carregando(false);
			}
			return true;
		}
		xmlhttp.send(null);
	}
	function busca_filtro_cidade(IdEstado,IdCidadeTemp){
		if(IdEstado == undefined || IdEstado==''){
			IdEstado = 0;			
		}
		
		if(IdCidadeTemp == undefined){
			IdCidadeTemp = '';
		}
		
		var url = "xml/cidade.php?IdPais="+1+"&IdEstado="+IdEstado;
		
		call_ajax(url, function(xmlhttp){
			var nameNode, nameTextNode;
			
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
		});
	}
	function verificaNovaObsCDA(opc){
        if(opc == 1){
			document.getElementById('titObsCDA').style.display       	= 'block';
			document.getElementById('cpObsCDA').style.display    	 	= 'block';
			document.getElementById('cpObsCDA').value			   	 	= '';
		}else{
			document.getElementById('titObsCDA').style.display       	= 'none';
			document.getElementById('cpObsCDA').style.display        	= 'none';
		}
    }
	function busca_login_supervisor(IdGrupoUsuario,campo){
		if(IdGrupoUsuario == ''){
			while(campo.options.length > 0){
				campo.options[0] = null;
			}
			
			if(document.filtro.filtro_usuario != undefined){
				addOption(campo,"Todos","");
			}
			return false;
		}
				
		url = "xml/usuario_grupo_usuario.php?IdGrupoUsuario="+IdGrupoUsuario+"&IdOrdemServico="+document.formulario.IdOrdemServico.value;
		
		if(campo.name == "LoginSupervisor") {
			url += "&IdPessoa="+document.formulario.IdPessoa.value;
		}
		
		call_ajax(url, function(xmlhttp){
			if(xmlhttp.responseText == 'false'){
				while(campo.options.length > 0){
					campo.options[0] = null;
				}
			}else{
				while(campo.options.length > 0){
					campo.options[0] = null;
				}
				if(document.filtro.filtro_usuario != undefined){
					addOption(campo,"Todos","");
				}else{
					addOption(campo,"","");
				}
					
				for(var i=0; i<xmlhttp.responseXML.getElementsByTagName("Login").length; i++){
					var nameNode = xmlhttp.responseXML.getElementsByTagName("Login")[i]; 
					var nameTextNode = nameNode.childNodes[0];
					var Login = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("NomeUsuario")[i]; 
					nameTextNode = nameNode.childNodes[0];
					var NomeUsuario = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("UltimaAtendimento")[i]; 
					nameTextNode = nameNode.childNodes[0];
					var UltimaAtendimento = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("QTDAberto")[i]; 
					nameTextNode = nameNode.childNodes[0];
					var QTDAberto = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("LoginSupervisor")[i]; 
					nameTextNode = nameNode.childNodes[0];
					var LoginSupervisor = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("LoginSupervisorAtual")[i]; 
					nameTextNode = nameNode.childNodes[0];
					var LoginSupervisorAtual = nameTextNode.nodeValue;
					
					var Descricao = NomeUsuario.substr(0,50);
					
					if(campo.name == "LoginSupervisor") {
						Descricao += " ("+QTDAberto+")";
					}
					
					Descricao += UltimaAtendimento;
					
					addOption(campo,Descricao,Login);
				}
				
				if(campo.name == 'LoginSupervisorAtual'){//Login Supervisor Atual
					if(LoginSupervisorAtual != ''){
						for(x=1;x<campo.length;x++){
							if(campo[x].value == LoginSupervisorAtual){
								campo.disabled = true;
								campo[x].selected = true;
								break;
							}
						}
					}else{
						campo.disabled = true;
						campo[0].selected = true;
					}
				}else{//Login Supervisor Novo Status
					if(LoginSupervisor != ''){//login supervisor grupo ususario
						for(ii=0;ii<campo.length;ii++){
							if(campo[ii].value == LoginSupervisor){
								campo.disabled = true;
								campo[ii].selected = true;
								break;
							}
						}
					}else{//login supervisor OS
						if(LoginSupervisorAtual != ""){
							for(ii=0;ii<campo.length;ii++){
								if(campo[ii].value == LoginSupervisorAtual){
									campo.disabled = false;
									campo[ii].selected = true;
									break;
								}
							}
						}else{
							campo[0].selected = true;
							campo.disabled = false;
						}
					}
				}
			}
		});
	}
	function emAtendimento(Valor){
		if(document.formulario.bt_alterar.disabled != true){			    
			if(document.formulario.Acao.value != 'inserir'){
			    var IdOrdemServico = document.formulario.IdOrdemServico.value;
				
				url = "files/editar/editar_ordem_servico_atendimento.php?IdOrdemServico="+IdOrdemServico+"&Valor="+Valor;
				
				call_ajax(url, function(xmlhttp){
					if(xmlhttp.responseText != "false"){
						var nameNode = xmlhttp.responseXML.getElementsByTagName("EmAtendimento")[0]; 
						var nameTextNode = nameNode.childNodes[0];
						var EmAtendimento = nameTextNode.nodeValue;
						
						var nameNode = xmlhttp.responseXML.getElementsByTagName("HistoricoObs")[0]; 
						var nameTextNode = nameNode.childNodes[0];
						var HistoricoObs = nameTextNode.nodeValue;						
						document.formulario.HistoricoObs.value = HistoricoObs;						
						
						if(EmAtendimento == '2'){
							document.getElementById('atendimento').setAttribute("src","../../img/estrutura_sistema/atendimento_c.gif");
							document.getElementById('atendimento').setAttribute("title","Aguardando Atendimento");
							document.formulario.EmAtendimentoStatus.value = 1;
						}else{
							document.getElementById('atendimento').setAttribute("src","../../img/estrutura_sistema/atendimento.gif");
							document.getElementById('atendimento').setAttribute("title","Em Atendimento");
							document.formulario.EmAtendimentoStatus.value = 2;
						}
					}
				});
			}
		}
	}
	function buscaAtendimento(IdOrdemServico){
		
		url = "xml/ordem_servico_atendimento.php?IdOrdemServico="+IdOrdemServico
				
		call_ajax(url, function(xmlhttp){
			if(xmlhttp.responseText != "false"){
				var nameNode = xmlhttp.responseXML.getElementsByTagName("EmAtendimento")[0]; 
				var nameTextNode = nameNode.childNodes[0];
				var EmAtendimento = nameTextNode.nodeValue;
				
				var nameNode = xmlhttp.responseXML.getElementsByTagName("IdStatus")[0]; 
				var nameTextNode = nameNode.childNodes[0];
				var IdStatus = nameTextNode.nodeValue;
				
				var img = document.getElementById('atendimento');
				
				if(IdStatus >= 100 && IdStatus <=199){
					
					document.getElementById('tableEmAndamento').style.display = 'block';
					if(EmAtendimento == '2'){
						img.setAttribute("src","../../img/estrutura_sistema/atendimento_c.gif");
						img.setAttribute("title","Aguardando Atendimento");
						document.formulario.EmAtendimentoStatus.value = 1;
					}else{
						img.setAttribute("src","../../img/estrutura_sistema/atendimento.gif");
						img.setAttribute("title","Em Atendimento");
						document.formulario.EmAtendimentoStatus.value = 2;
					}
				}else{
					document.getElementById('tableEmAndamento').style.display = 'none';
				}
			}
		});	
	}
	
	function limpa_campo(Status){
		var campo = document.formulario.LoginSupervisor;
		switch(Status){
			case '100':
				document.formulario.LoginSupervisor.selectedIndex = 0;
				break;
			default:
				document.formulario.LoginSupervisor.selectedIndex = 0;
				break;
		}
	}
	function novo_supervisor(){
		if(document.formulario.LoginSupervisor.value == ""){
			document.formulario.LoginSupervisor.value = document.formulario.LoginAtendimento.value;
		}
	}
	function permissaoQuadroDescricaoOs(){
		var V = document.formulario.PermissaoVisualizar.value;
		var I = document.formulario.PermissaoInserir.value;
		var U = document.formulario.PermissaoEditar.value;
		
		if(V == 1){
			document.getElementById("cpDescricaoOrdemServico").style.display = "block";
			document.formulario.DescricaoOS.readOnly = true;
			document.formulario.ObrigatoriedadeDescricaoOrdemServico.value = 0;
			document.getElementById("DescricaoOrdemServico").style.color = "#000";
		}
		if(I == 1 || U == 1){
			if(document.formulario.Acao.value == "inserir" && I == 1){
				document.getElementById("cpDescricaoOrdemServico").style.display = "block";
				document.formulario.DescricaoOS.readOnly = false;
				document.formulario.ObrigatoriedadeDescricaoOrdemServico.value = 1;
				document.getElementById("DescricaoOrdemServico").style.color = "#C10000";
			}else{
				document.formulario.DescricaoOS.readOnly = true;
			}
			if(document.formulario.Acao.value == "alterar" && U == 1){
				document.getElementById("cpDescricaoOrdemServico").style.display = "block";
				document.formulario.DescricaoOS.readOnly = false;
				document.formulario.ObrigatoriedadeDescricaoOrdemServico.value = 1;
				document.getElementById("DescricaoOrdemServico").style.color = "#C10000";
			}			
		}		
	}
	function subTipoOrdemServicoDefault(ValorCI,IdOrdemServico){
		var IdTipoOrdemServico = document.formulario.IdTipoOrdemServico.value;
		
		if(IdOrdemServico == "" || IdOrdemServico == 0){
			for(i=0;i<document.formulario.IdSubTipoOrdemServico.length;i++){
				if(document.formulario.IdSubTipoOrdemServico[i].value == ValorCI){
					document.formulario.IdSubTipoOrdemServico[i].selected	=	true;
					document.formulario.IdSubTipoOrdemServicoTemp.value	=	ValorCI;
				}
			}
		}else{
			if(IdTipoOrdemServico == undefined || IdTipoOrdemServico==''){
				IdTipoOrdemServico = 0;
			}
			
			var xmlhttp = false;
			
			url = "xml/subtipo_ordem_servico.php?IdTipoOrdemServico="+IdTipoOrdemServico+"&IdOrdemServico="+IdOrdemServico;
			
			call_ajax(url, function(xmlhttp){
				if(xmlhttp.responseText != 'false'){		
					var nameNode, nameTextNode, IdSubTipoOrdemServico;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("IdSubTipoOrdemServico")[0]; 
					nameTextNode = nameNode.childNodes[0];
					IdSubTipoOrdemServico = nameTextNode.nodeValue;
					
					
					for(i=0;i<document.formulario.IdSubTipoOrdemServico.length;i++){
						if(document.formulario.IdSubTipoOrdemServico[i].value == IdSubTipoOrdemServico){
							document.formulario.IdSubTipoOrdemServico[i].selected	=	true;
							document.formulario.IdSubTipoOrdemServicoTemp.value = IdSubTipoOrdemServico;
						}
					}
					
				}else{
					while(document.formulario.IdSubTipoOrdemServico.options.length > 0){
						document.formulario.IdSubTipoOrdemServico.options[0] = null;
					}
					
					document.formulario.IdSubTipoOrdemServicoTemp.value = '';
				}
				
			});
		}
	}