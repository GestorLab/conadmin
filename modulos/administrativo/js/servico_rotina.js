
	function validar(){
		if(document.formulario.IdServico.value==''){
			mensagens(1);
			document.formulario.IdServico.focus();
			return false;
		}
		if(document.formulario.IdConsulta.value == 4){
			if(document.formulario.IdParametroServico.value == ""){
				mensagens(1);
				document.formulario.IdParametroServico.focus();
				return false;
			}
		}
		return true;
	}
	function inicia(){
		verificar_tipo_monitor();
		document.formulario.IdServico.focus();
	}
	function verificaAcao(){
		if(document.formulario.IdServico.value == ''){
			document.formulario.bt_alterar.disabled 	= true;
		}else{
			document.formulario.bt_alterar.disabled 	= false;
		}
		
		busca_email_lista_bloqueados();
	}
	function verificar_tipo_monitor(valor) {
	
		switch(Number(valor)){
			case 1:
				document.getElementById("titSMNP").style.display		= "block";
				document.formulario.IdSNMP.style.display				= "block";
				document.getElementById("titComandoSSH").style.display	= "none";
				document.formulario.ComandoSSH.style.display			= "none";
				document.getElementById("titCodigoSNMP").style.display	= "none";
				document.formulario.CodigoSNMP.style.display			= "none";
				document.getElementById("td_titHistorico").style.width	= "50px";
				document.getElementById("titHistorico").style.display	= "block";
				document.getElementById("td_cpHistorico").style.width	= "50px";
				document.formulario.Historico.style.display				= "block";
				document.getElementById("titEmdias").style.display  	= "block";
				document.getElementById("td_titEmdias").style.width		= "50px";
				document.formulario.bt_add.style.marginLeft				= "6px";
				document.formulario.bt_add.disabled						= false;
				document.getElementById("tb_CodigoSNMP").style.display	= "none";
				break;
			case 2:
				document.getElementById("titSMNP").style.display		= "none";
				document.formulario.IdSNMP.style.display				= "none";
				document.getElementById("titComandoSSH").style.display	= "block";
				document.formulario.ComandoSSH.style.display			= "block";
				document.getElementById("titCodigoSNMP").style.display	= "none";
				document.formulario.CodigoSNMP.style.display			= "none";
				document.getElementById("td_titHistorico").style.width	= "50px";
				document.getElementById("titHistorico").style.display	= "block";
				document.getElementById("td_cpHistorico").style.width	= "50px";
				document.formulario.Historico.style.display				= "block";
				document.getElementById("titEmdias").style.display  	= "block";
				document.getElementById("td_titEmdias").style.width		= "50px";
				document.formulario.Historico.disabled					= false;
				document.formulario.bt_add.style.marginLeft				= "6px";
				document.formulario.bt_add.disabled						= false;
				document.getElementById("tb_CodigoSNMP").style.display	= "none";
				break;
			default:
				document.getElementById("titSMNP").style.display			= "none";
				document.formulario.IdSNMP.style.display					= "none";
				document.getElementById("titComandoSSH").style.display		= "none";
				document.formulario.ComandoSSH.style.display				= "none";
				document.getElementById("titCodigoSNMP").style.display		= "none";
				document.formulario.CodigoSNMP.style.display				= "none";
				document.getElementById("td_titHistorico").style.width		= "0px";
				document.getElementById("titHistorico").style.display		= "none";
				document.formulario.Historico.style.display					= "none";
				document.getElementById("td_cpHistorico").style.width		= "0px";
				document.getElementById("titEmdias").style.display  		= "none";
				document.getElementById("td_titEmdias").style.width			= "0px";
				document.formulario.Historico.disabled						= false;
				document.formulario.bt_add.style.marginLeft					= "2px";
				document.formulario.bt_add.disabled							= true;
				document.getElementById("cpParametroServico").style.display	= "none";
				document.getElementById("tb_CodigoSNMP").style.display		= "none";
				
		}
		
		document.formulario.IdSNMP.value		= "";
		document.formulario.ComandoSSH.value	= "";
		document.formulario.CodigoSNMP.value	= "";
		document.formulario.Historico.value		= "0";
		//verificar_snmp();
	}
	function verificar_snmp(valor) {
	
		if(valor == ""){
			document.formulario.Historico.disabled	= false;
			document.formulario.Historico.value = "0";
			document.getElementById("titCodigoSNMP").style.display	= "none";
			document.formulario.CodigoSNMP.style.display			= "none";
			document.getElementById("tb_CodigoSNMP").style.display	= "none";
		}else{
			if(Number(valor) == 1){
				document.getElementById("tb_CodigoSNMP").style.display	= "block";
				document.getElementById("titCodigoSNMP").style.color 	= "#c10000";
				document.getElementById("titCodigoSNMP").style.display	= "block";
				document.formulario.CodigoSNMP.style.display			= "block";
				document.formulario.Historico.value						= "0";
				document.formulario.Historico.disabled					= false;
				document.getElementById("titEmdias").style.display  	= "block";
			} else {
				document.getElementById("tb_CodigoSNMP").style.display	= "none";
				document.getElementById("titCodigoSNMP").style.color	= "#000";
				document.getElementById("titCodigoSNMP").style.display	= "none";
				document.formulario.CodigoSNMP.style.display			= "none";
				document.formulario.Historico.value						= "0";
				document.formulario.Historico.disabled					= true;
				document.getElementById("titEmdias").style.display  	= "block";
			}
		}
	}
	function adicionar_monitor(IdServico) {
		if(validar() == false){
			return false;
		}
		if((IdServico != '' && IdServico != undefined) || IdServico == 0) {
			var url = "xml/servico_monitor.php?IdServico="+IdServico;
			
			call_ajax(url, function (xmlhttp) {
				while(document.getElementById("tabelaMonitor").rows.length > 2) {
					document.getElementById("tabelaMonitor").deleteRow(1);
				}
				document.getElementById("totaltabelaMonitor").innerHTML = "Total: 0";
				document.formulario.DadosMonitor.value = '';
				
				if(xmlhttp.responseText != "false") {
					for(var i = 0; i < xmlhttp.responseXML.getElementsByTagName("IdServicoMonitor").length; i++) {
						
						var nameNode = xmlhttp.responseXML.getElementsByTagName("IdServicoMonitor")[i]; 
						var nameTextNode = nameNode.childNodes[0];
						var IdServicoMonitor = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("IdTipoMonitor")[i]; 
						nameTextNode = nameNode.childNodes[0];
						var IdTipoMonitor = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("TipoMonitor")[i]; 
						nameTextNode = nameNode.childNodes[0];
						var TipoMonitor = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("IdConsulta")[i]; 
						nameTextNode = nameNode.childNodes[0];
						var IdConsulta = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("IdSNMP")[i]; 
						nameTextNode = nameNode.childNodes[0];
						var IdSNMP = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("SNMP")[i]; 
						nameTextNode = nameNode.childNodes[0];
						var SNMP = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("ComandoSSH")[i]; 
						nameTextNode = nameNode.childNodes[0];
						var ComandoSSH = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("Historico")[i]; 
						nameTextNode = nameNode.childNodes[0];
						var Historico = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("IdFormatoResultado")[i]; 
						nameTextNode = nameNode.childNodes[0];
						var IdFormatoResultado = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("IdParametroServico")[i]; 
						nameTextNode = nameNode.childNodes[0];
						var IdParametroServico = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("FiltroContratoParametro")[i]; 
						nameTextNode = nameNode.childNodes[0];
						var FiltroContratoParametro = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("CodigoSNMP")[i]; 
						nameTextNode = nameNode.childNodes[0];
						var CodigoSNMP = nameTextNode.nodeValue;
						
						var DadosMonitor = {
							AccessKey: IdServicoMonitor,
							IdServicoMonitor: IdServicoMonitor,
							IdTipoMonitor: IdTipoMonitor,
							TipoMonitor: TipoMonitor,
							Consulta: IdConsulta,
							IdSNMP: IdSNMP,
							SNMP: SNMP,
							ComandoSSH: ComandoSSH,
							Historico: Historico,
							Resultado: IdFormatoResultado,
							IdParametroServico: IdParametroServico,
							FiltroContratoParametro: FiltroContratoParametro,
							CodigoSNMP:	CodigoSNMP
						};

						adicionar_monitor_tabela(DadosMonitor);
					}
				} else {
				   	limpar_campo_monitor();
				}
			});
		} else {
			var IdTipoMonitor = document.formulario.IdTipoMonitor.value;
			var TipoMonitor = '';
		
			if(IdTipoMonitor == 1) {
				if(document.formulario.IdSNMP.value == '') {
					mensagens(1);
					document.formulario.IdSNMP.focus();
					return;
				}
				
				if(Number(document.formulario.IdSNMP.value) == 1 && document.formulario.CodigoSNMP.value == '') {
					mensagens(1);
					document.formulario.CodigoSNMP.focus();
					return;
				}
					
			} else if(IdTipoMonitor == 2) {
				if(document.formulario.ComandoSSH.value == '') {
					mensagens(1);
					document.formulario.ComandoSSH.focus();
					return;
				}
			}
			
			if(IdTipoMonitor != '') {
				for(var i = 0; i < document.formulario.IdTipoMonitor.length; i++){
					if(document.formulario.IdTipoMonitor.options[i].value == IdTipoMonitor){
						TipoMonitor = document.formulario.IdTipoMonitor.options[i].text;
						break;
					}
				}		
			}
			
			var IdSNMP = document.formulario.IdSNMP.value;
			var SNMP = '';
			for(var i = 0; i < document.formulario.IdSNMP.length; i++){
				if(document.formulario.IdSNMP.options[i].value == IdSNMP){
					SNMP = document.formulario.IdSNMP.options[i].text;
					break;
				}
			}
			
			var Historico = document.formulario.Historico.value;
			var DadosMonitor = {
				AccessKey: (Number(document.getElementById("tabelaMonitor").rows[(document.getElementById("tabelaMonitor").rows.length - 2)].accessKey) + 1),
				IdServicoMonitor: '',
				IdTipoMonitor: IdTipoMonitor,
				TipoMonitor: TipoMonitor,
				Consulta: document.formulario.IdConsulta.value,
				IdSNMP: IdSNMP,
				SNMP: SNMP,
				ComandoSSH: document.formulario.ComandoSSH.value,
				Historico: 	document.formulario.Historico.value,
				Resultado: 	document.formulario.IdFormatoResultado.value,
				IdParametroServico: document.formulario.IdParametroServico.value,
				FiltroContratoParametro: document.formulario.FiltroContratoParametro.value,
				CodigoSNMP: document.formulario.CodigoSNMP.value
			};
			adicionar_monitor_tabela(DadosMonitor);
		}
	}
	function adicionar_monitor_tabela(DadosMonitor) {
		var tam = document.getElementById("tabelaMonitor").rows.length;
		var linha = document.getElementById("tabelaMonitor").insertRow(tam - 1);
		
		linha.accessKey = DadosMonitor.AccessKey;
		
		if((tam % 2) != 0) {
			linha.style.backgroundColor = "#E2E7ED";
		}
		
		var c0 = linha.insertCell(0);
		var c1 = linha.insertCell(1);
		var c2 = linha.insertCell(2);
		var c3 = linha.insertCell(3);
		var c4 = linha.insertCell(4);
		var c5 = linha.insertCell(5);
		
		if(document.formulario.DadosMonitor.value != '') {
			document.formulario.DadosMonitor.value += "»"+DadosMonitor.AccessKey+"¬"+DadosMonitor.IdServicoMonitor+"¬"+DadosMonitor.IdTipoMonitor+"¬"+DadosMonitor.TipoMonitor+"¬"+DadosMonitor.Consulta+"¬"+DadosMonitor.IdSNMP+"¬"+DadosMonitor.SNMP+"¬"+DadosMonitor.ComandoSSH+"¬"+DadosMonitor.Historico+"¬"+DadosMonitor.Resultado+"¬"+DadosMonitor.IdParametroServico+"¬"+DadosMonitor.FiltroContratoParametro+"¬"+DadosMonitor.CodigoSNMP;
		} else {
			document.formulario.DadosMonitor.value = DadosMonitor.AccessKey+"¬"+DadosMonitor.IdServicoMonitor+"¬"+DadosMonitor.IdTipoMonitor+"¬"+DadosMonitor.TipoMonitor+"¬"+DadosMonitor.Consulta+"¬"+DadosMonitor.IdSNMP+"¬"+DadosMonitor.SNMP+"¬"+DadosMonitor.ComandoSSH+"¬"+DadosMonitor.Historico+"¬"+DadosMonitor.Resultado+"¬"+DadosMonitor.IdParametroServico+"¬"+DadosMonitor.FiltroContratoParametro+"¬"+DadosMonitor.CodigoSNMP;
		}
		
		if(DadosMonitor.Consulta == 1){
			DadosMonitor.Consulta = "NAS";
		}
		if(DadosMonitor.Resultado  == 1){
			DadosMonitor.Resultado = "Grafico";
		}
		if(DadosMonitor.Consulta == 2){ 
			DadosMonitor.Consulta = "Cliente";
		}
		if(DadosMonitor.Resultado  == 2){
			DadosMonitor.Resultado = "Texto";
		}
        if(DadosMonitor.Consulta == 3){ 
			DadosMonitor.Consulta = "Outro";
		}
		if(DadosMonitor.Consulta == 4){ 
			DadosMonitor.Consulta = "Parâmetro Serviço";
		}
				
		c0.innerHTML			= DadosMonitor.TipoMonitor;
		c0.style.paddingLeft	= "5px";
		c1.innerHTML			= DadosMonitor.Consulta;
		c1.style.paddingLeft	= "5px";
		c2.innerHTML			= DadosMonitor.SNMP;
		c2.style.paddingLeft	= "5px";
		c3.innerHTML			= DadosMonitor.Historico;
		c3.style.paddingLeft	= "5px";
		c4.innerHTML			= DadosMonitor.Resultado;
		c4.style.paddingLeft	= "5px";
		c5.innerHTML			= "<img src='../../img/estrutura_sistema/ico_del.gif' alt='Excluir?' onClick=\"remover_monitor_tabela('"+DadosMonitor.AccessKey+"');\">";
		c5.style.cursor			= "pointer";
		
		document.getElementById("totaltabelaMonitor").innerHTML = "Total: "+(tam - 1);
		limpar_campo_monitor();
	}
	function remover_monitor_tabela(AccessKey, IdServicoMonitor) {
		var tam = document.getElementById("tabelaMonitor").rows.length;
		
		for(var i = 0; i < tam; i++) {
			if(AccessKey == document.getElementById("tabelaMonitor").rows[i].accessKey) {
				document.getElementById('tabelaMonitor').deleteRow(i);
				tableMultColor('tabelaMonitor');
				
				var DadosMonitor = document.formulario.DadosMonitor.value.split("»"), exp = RegExp("^"+AccessKey+"[\w\W]*"), Temp = '';
				
				for(var i = 0; i < DadosMonitor.length; i++) {
					if(!(exp).test(DadosMonitor[i])) {
						Temp += "»"+DadosMonitor[i];
					}
				}
				
				document.formulario.DadosMonitor.value = Temp.replace(/^[»]/,'');
				tam--;
				break;
			}
		}
		
		document.getElementById("totaltabelaMonitor").innerHTML = "Total: "+(tam - 2);
	}
	function limpar_campo_monitor() {
		document.formulario.IdTipoMonitor.value				= '';
		document.formulario.IdConsulta.value				= '';
		document.formulario.IdSNMP.value					= '';
		document.formulario.ComandoSSH.value				= '';
		document.formulario.CodigoSNMP.value				= '';
		document.formulario.Historico.value					= '';
		document.formulario.IdFormatoResultado.value		= '';
		document.formulario.IdParametroServico.value		= '';
		document.formulario.FiltroContratoParametro.value	= '';
		
		verificar_tipo_monitor();
	}
	function verificar_historico(valor){
		if(valor == ''){
			document.formulario.Historico.value	= '0';
		}
	}
	function busca_email_lista_bloqueados(){
		var IdServico = document.formulario.IdServico.value;
		
		if((IdServico != '' && IdServico != undefined) || IdServico == 0) {
			var url = "xml/servico_email_lista_bloqueado.php?IdServico="+IdServico;
			
			call_ajax(url, function (xmlhttp) {
				if(xmlhttp.responseText != "false") {
					for(var i = 0; i < xmlhttp.responseXML.getElementsByTagName("IdServico").length; i++) {
						
						var nameNode = xmlhttp.responseXML.getElementsByTagName("IdServico")[i]; 
						var nameTextNode = nameNode.childNodes[0];
						var IdServico = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("EmailListaBloqueados")[i]; 
						nameTextNode = nameNode.childNodes[0];
						var EmailListaBloqueados = nameTextNode.nodeValue;
						
						document.formulario.Email_lista_bloqueados.value = EmailListaBloqueados;
					}
				} else {
				   	document.formulario.Email_lista_bloqueados.value = '';
				}
			});
		}
	}
	function verificar_consulta(IdConsulta){
		if(document.formulario.IdServico.value != "")
			IdServico = document.formulario.IdServico.value;
		else
			IdServico = 0;
	
		if(IdConsulta == 4){
			
			var url = "xml/servico_parametro.php?IdServico="+IdServico;
			call_ajax(url,function (xmlhttp){
				document.getElementById('cpParametroServico').style.display = "block";
				if(xmlhttp.responseText != 'false'){
					while(document.formulario.IdParametroServico.options.length > 0){
						document.formulario.IdParametroServico.options[0] = null;
					}
					
					var nameNode, nameTextNode, DescricaoParametroServico,IdParametroServico;	
					addOption(document.formulario.IdParametroServico,"","");				
										
					for(var i=0; i<xmlhttp.responseXML.getElementsByTagName("IdParametroServico").length; i++){
						nameNode = xmlhttp.responseXML.getElementsByTagName("IdParametroServico")[i]; 
						nameTextNode = nameNode.childNodes[0];
						IdParametroServico = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoParametroServico")[i]; 
						nameTextNode = nameNode.childNodes[0];
						DescricaoParametroServico = nameTextNode.nodeValue;
					
						addOption(document.formulario.IdParametroServico,DescricaoParametroServico,IdParametroServico);
					}					
						document.formulario.IdParametroServico.options[0].selected = true;					
				}else{
					while(document.formulario.IdParametroServico.options.length > 0){
						document.formulario.IdParametroServico.options[0] = null;
					}								
				}
			});	
		}else{
			document.getElementById('cpParametroServico').style.display = "none";
			document.formulario.IdParametroServico.value 		= "";
			document.formulario.FiltroContratoParametro.value 	= "";
		}
	}