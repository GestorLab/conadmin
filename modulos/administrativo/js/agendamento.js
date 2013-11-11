	function excluir(IdOrdemServico,Data,Hora){
		if(Data== '' || undefined){
			Data = document.formulario.Data.value;
		}
		if(Hora== '' || undefined){
			Hora = document.formulario.Hora.value;
		}
		if(IdOrdemServico== '' || undefined){
			IdOrdemServico = document.formulario.IdOrdemServico.value;
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
    		
    		var DataHoraAgendamento	=	formatDate(Data)+" "+Hora+":00";
    
   			url = "files/excluir/excluir_agendamento.php?DataHoraAgendamento="+DataHoraAgendamento+"&IdOrdemServico="+IdOrdemServico;
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
								url = 'cadastro_agendamento.php?Erro='+document.formulario.Erro.value;
								window.location.replace(url);
							}else{
								verificaErro();
							}
						}else{
							var numMsg = parseInt(xmlhttp.responseText);
							mensagens(numMsg);
							if(numMsg == 7){
								for(var i=0; i<document.getElementById('tableListar').rows.length; i++){
									if(IdOrdemServico+"_"+DataHoraAgendamento == document.getElementById('tableListar').rows[i].accessKey){
										document.getElementById('tableListar').deleteRow(i);
										tableMultColor('tableListar',document.filtro.corRegRand.value);
										break;
									}
								}								
							}
						}
					}
				}
				// Fim de Carregando
				carregando(false);
				return true;
			}
			xmlhttp.send(null);
		}
	} 
	function validar(){
		if(document.formulario.IdOrdemServico.value==""){
			mensagens(1);
			document.formulario.IdOrdemServico.focus();
			return false;
		}
		if(document.formulario.Data.value==""){
			mensagens(1);
			document.formulario.Data.focus();
			return false;
		}else{
			if(isData(document.formulario.Data.value) == false){
				mensagens(27);
				document.formulario.Data.focus();
				return false;
			}
		}
		if(document.formulario.Hora.value==''){
			mensagens(1);
			document.formulario.Hora.focus();
			return false;
		}else{
			if(isTime(document.formulario.Hora.value)==false){
				mensagens(1);
				document.formulario.Hora.focus();
				return false;
			}
		}
		return true;
	}
	
	function inicia(){
		status_inicial();
		document.formulario.IdOrdemServico.focus();
	}
	
	function status_inicial(){
		if(document.formulario.Data.value == ''){
			document.formulario.Data.value	=	data();
		}	
		if(document.formulario.Hora.value == ''){
			document.formulario.Hora.value	=	hora();
		}
	}
	
	function validar_Data(id,campo){
		if(campo.value == ''){
			document.getElementById(id).style.backgroundColor='#FFF';
			document.getElementById(id).style.color='#C10000';
			mensagens(0);
			return false;
		}
		if(isData(campo.value) == false){		
			colorTemp = document.getElementById(campo.name).style.backgroundColor;
			document.getElementById(id).style.backgroundColor = '#C10000';
			document.getElementById(id).style.color='#FFF';
			mensagens(27);
			return false;
		}else{
			document.getElementById(id).style.backgroundColor='#FFF';
			document.getElementById(id).style.color='#C10000';
			mensagens(0);
			return true;
		}	
	}
	function validar_Time(id,campo){
		if(campo.value == ''){
			document.getElementById(id).style.backgroundColor='#FFF';
			document.getElementById(id).style.color='#C10000';
			mensagens(0);
			return false;
		}
		if(isTime(campo.value) == false){		
			colorTemp = document.getElementById(campo.name).style.backgroundColor;
			document.getElementById(id).style.backgroundColor = '#C10000';
			document.getElementById(id).style.color='#FFF';
			mensagens(27);
			return false;
		}else{
			document.getElementById(id).style.backgroundColor='#FFF';
			document.getElementById(id).style.color='#C10000';
			mensagens(0);
			return true;
		}	
	}
	function verificaAcao(){
		if(document.formulario != undefined){
			if(document.formulario.Acao.value=='inserir'){			
				document.formulario.bt_inserir.disabled 	= false;
				document.formulario.bt_excluir.disabled 	= true;
			}
			if(document.formulario.Acao.value=='alterar'){			
				document.formulario.bt_inserir.disabled 	= true;
				document.formulario.bt_excluir.disabled 	= false;
			}
		}	
	}

