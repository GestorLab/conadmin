	function busca_filtro_cidade_estado(IdEstado,IdCidadeTemp){
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
						while(document.formulario.IdCidade.options.length > 0){
							document.formulario.IdCidade.options[0] = null;
						}
						
						var nameNode, nameTextNode, NomeCidade;					
						
						addOption(document.formulario.IdCidade,"","");	
						for(var i=0; i<xmlhttp.responseXML.getElementsByTagName("IdCidade").length; i++){
							nameNode = xmlhttp.responseXML.getElementsByTagName("IdCidade")[i]; 
							nameTextNode = nameNode.childNodes[0];
							IdCidade = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("NomeCidade")[i]; 
							nameTextNode = nameNode.childNodes[0];
							NomeCidade = nameTextNode.nodeValue;
				
							addOption(document.formulario.IdCidade,NomeCidade,IdCidade);
						}					
						
						if(IdCidadeTemp!=""){
							for(i=0;i<document.formulario.IdCidade.length;i++){
								if(document.formulario.IdCidade[i].value == IdCidadeTemp){
									document.formulario.IdCidade[i].selected	=	true;
									break;
								}
							}
						}else{
							document.formulario.IdCidade[0].selected	=	true;
						}						
					}else{
						while(document.formulario.IdCidade.options.length > 0){
							document.formulario.IdCidade.options[0] = null;
						}						
					}
				});
			}
	function validar(){
		if(document.formulario.MesVencimento.value == ''){
			document.formulario.MesVencimento.focus();
			mensagens(1);
			return false;
		}			
		return true;
	}
	function inicia(){
		document.formulario.MesVencimento.focus();		
	}
	function verifica_mes(nome,campo){
		if(campo.value != ''){
			if(val_Mes(campo.value) == false){
				document.getElementById(nome).style.backgroundColor = '#C10000';
				document.getElementById(nome).style.color='#FFF';
				mensagens(45);			
			}else{
				document.getElementById(nome).style.backgroundColor = '#FFF';
				document.getElementById(nome).style.color='#C10000';
				mensagens(0);		
			}
		}else{	
			document.getElementById(nome).style.backgroundColor = '#FFF';
			document.getElementById(nome).style.color='#C10000';
			mensagens(0);
		}
	}
	
	function cadastrar(Acao){
		if(validar()==true){
			if(Acao != ''){
				document.formulario.Acao.value	=	Acao;
			}			
			if(Acao == 'gerar'){
				if(confirm('Você está prestes a emitir nota fiscal para os contas à receber relacionados na tela anterior.\n\nDeseja continuar?')){
					document.formulario.submit();
				}else{
					return false;
				}
			}
			document.formulario.submit();
		}
	}
	
	function verificaAcao(){
		if(document.formulario != undefined){
			switch(document.formulario.Acao.value){										
				default:					
					document.formulario.bt_gerar.disabled 			= true;				
					break
			}
		}	
	}
	
	function listarContaReceber(MesVencimento,IdContrato,IdGrupoPessoa,IdTipoPessoa,IdFormaAvisoCobranca,IdStatusContrato,IdStatusContaReceber,Erro,IdServico,IdEstado,IdCidade){
		if(IdContrato == undefined || IdContrato==''){
			IdContrato = 0;
		}
		if(IdContrato != ""){
			var novo	=	"";
			var valor	=	IdContrato.split("\n");
			for(var i=0; i<valor.length; i++){
				valor[i]	=	trim(valor[i]);
				if(valor[i] != ""){					
					if(novo != ""){
						novo +=	",";
					}
					novo +=	valor[i];
				}
			}
			IdContrato = novo;
		}
	
		while(document.getElementById('tabelaTitulos').rows.length > 2){
			document.getElementById('tabelaTitulos').deleteRow(1);
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
	    
	    url = "xml/gerar_nota_fiscal_em_massa_conta_receber.php?MesVencimento="+MesVencimento;
	    
	    if(IdContrato != 0){
	    	url += "&IdContrato="+IdContrato;
	    }
	    
	    if(IdGrupoPessoa != ""){
	    	url += "&IdGrupoPessoa="+IdGrupoPessoa;
	    }

		if(IdTipoPessoa != ""){
	    	url += "&IdTipoPessoa="+IdTipoPessoa;
	    }
	    
	    if(IdFormaAvisoCobranca != ""){
	    	url += "&IdFormaAvisoCobranca="+IdFormaAvisoCobranca;
	    } 	
	    
	    if(IdStatusContrato != ""){
	    	url += "&IdStatusContrato="+IdStatusContrato;
	    }
	    
	    if(IdStatusContaReceber != ""){
	    	url += "&IdStatusContaReceber="+IdStatusContaReceber;
	    }
		
		if(IdServico != ""){
	    	url += "&IdServico="+IdServico;
	    }
		
		if(IdEstado != ""){
			url += "&IdEstado="+IdEstado;
		}
		
		if(IdCidade != ""){
			url += "&IdCidade="+IdCidade;
		}
	  	
		url += "&Atualizacao="+Math.random();
		
		xmlhttp.open("GET", url,true);

		xmlhttp.onreadystatechange = function(){ 
	
			// Carregando...
			carregando(true);
	
			if(xmlhttp.readyState == 4){ 
				if(xmlhttp.status == 200){				
					if(xmlhttp.responseText == 'false'){				
						document.getElementById('cp_titulos').style.display	=	'block';
						document.getElementById('ValorTotal').innerHTML		=	"0,00";								
						document.getElementById('tabelaTotal').innerHTML	=	"Total: 0";	
						document.formulario.bt_gerar.disabled 				= true;										
						// Fim de Carregando
						carregando(false);
					}else{
										
						document.getElementById('cp_titulos').style.display	= 'block';
						
						var tam, linha, c0, c1, c2, c3, c4, c5;
						var IdContaReceber, DataLancamento, DataVencimento, Valor, Status, Nome;							
						var Valor = 0, ValorTotal=0;
						var QtdContaReceber;
														
						try{						
							QtdContaReceber = xmlhttp.responseXML.getElementsByTagName("IdContaReceber").length; // no Chrome a função length nao retorna zero quando nao encontra elemento no xml
						}catch(e){
							QtdContaReceber = 0;
						}
						
						for(var i=0; i<QtdContaReceber; i++){							
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("IdContaReceber")[i]; 
							nameTextNode = nameNode.childNodes[0];
							IdContaReceber = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("Nome")[i]; 
							nameTextNode = nameNode.childNodes[0];
							Nome = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("DataLancamento")[i]; 
							nameTextNode = nameNode.childNodes[0];
							DataLancamento = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("DataVencimento")[i]; 
							nameTextNode = nameNode.childNodes[0];
							DataVencimento = nameTextNode.nodeValue;	
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("Valor")[i]; 
							nameTextNode = nameNode.childNodes[0];
							Valor = nameTextNode.nodeValue;	
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("Status")[i]; 
							nameTextNode = nameNode.childNodes[0];
							Status = nameTextNode.nodeValue;
													
							tam 	= document.getElementById('tabelaTitulos').rows.length;
							linha	= document.getElementById('tabelaTitulos').insertRow(tam-1);
							
							if(tam%2 != 0){
								linha.style.backgroundColor = "#E2E7ED";
							}
							
							linha.accessKey = IdContaReceber; 
							
							c0	= linha.insertCell(0);	
							c1	= linha.insertCell(1);	
							c2	= linha.insertCell(2);	
							c3	= linha.insertCell(3);
							c4	= linha.insertCell(4);
							c5	= linha.insertCell(5);															
							c6	= linha.insertCell(6);															
							
							c0.innerHTML = "<input type='checkbox' name='check_"+IdContaReceber+"' width='6px' checked='true'/>";
																															
							c1.innerHTML = IdContaReceber;
							c1.style.padding =	"0 0 0 5px";
							
							c2.innerHTML = Nome.substr(0,20);
							
							c3.innerHTML = dateFormat(DataLancamento);
							
							c4.innerHTML = dateFormat(DataVencimento);
							
							c5.innerHTML = formata_float(Arredonda(Valor,2),2).replace('.',',');
							c5.style.textAlign = "right";
							c5.style.padding =	"0 8px 0 0";	
							
							c6.innerHTML = Status;		
							
							ValorTotal = parseFloat(ValorTotal) + parseFloat(Valor);				
							document.formulario.bt_gerar.disabled 				= false;
						}																
						document.getElementById('ValorTotal').innerHTML		=	formata_float(Arredonda(ValorTotal,2),2).replace('.',',');		
						document.getElementById('tabelaTotal').innerHTML	=	"Total: "+i;	
					}					
				}
				// Fim de Carregando
				carregando(false);
			} 
			return true;
		}
		xmlhttp.send(null);
	}
	
	function buscaAnalizar(){
		// Carregando...	
		if(validar()){
			carregando(true);
			if(document.formulario.Visualizar.value == ''){
				if(document.formulario.MesVencimento.value != ''){				
					listarContaReceber(document.formulario.MesVencimento.value,document.formulario.IdContrato.value,document.formulario.IdGrupoPessoa.value,document.formulario.IdTipoPessoa.value,document.formulario.IdFormaAvisoCobranca.value,document.formulario.IdStatusContrato.value,document.formulario.IdStatusContaReceber.value,false,document.formulario.IdServico.value,document.formulario.IdEstado.value,document.formulario.IdCidade.value);
					document.formulario.Visualizar.value 	= true;				
					document.formulario.bt_analizar.value 	= 'Ocutar';				
				}			
			}else{
				document.formulario.IdContaReceber.value 			= '';
				document.getElementById('cp_titulos').style.display = 'none';
				document.formulario.bt_analizar.value 				= 'Analizar';
				document.formulario.bt_gerar.disabled 				= true;
				document.formulario.Visualizar.value 				= '';			
				
				while(document.getElementById('tabelaTitulos').rows.length > 2){
					document.getElementById('tabelaTitulos').deleteRow(1);
				}
								
				document.getElementById('ValorTotal').innerHTML		=	"0,00";
				document.getElementById('tabelaTotal').innerHTML	=	"Total: 0";
				
				// Carregando...
				carregando(false);
			}
		}
	}
	
	function mutarPacoteNotaFiscal(){
		if(document.formulario != undefined) campos = document.formulario;
		var primeiro = 0;
		
		for(i = 0; i < campos.length; i++){
			if(campos[i].name.substring(0,6) == "check_" && campos[i].checked == true){
				var IdContaReceber = campos[i].name.replace("check_","");
				if(primeiro > 0){							
					document.formulario.IdContaReceber.value += ","+IdContaReceber;	
				}else{
					document.formulario.IdContaReceber.value = IdContaReceber;
				}
				
				primeiro++;
			}			
		}
		
		cadastrar('gerar');
	}
	
	function MarcaDesMarcaContaReceber(status){
		if(document.formulario != undefined) campos = document.formulario;
		
		for(i = 0; i < campos.length; i++){
			if(campos[i].name.substring(0,6) == "check_"){
				campos[i].checked = status;
			}			
		}
	}