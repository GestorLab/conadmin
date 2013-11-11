	var menu_form, menu_lista, teclas_atalho;
		
	function OnOff(Local,Emissor){
		switch (Emissor){
			case 'menu':
				alert(document.frames.length);
				//[0].document.getElementById('cabecalho').style.display = 'none';
				break;
			default:
				Emissor = document.getElementById(Emissor);
				if(document.getElementById(Local).style.display == '' || document.getElementById(Local).style.display == 'block'){
					document.getElementById(Local).style.display = 'none';
					Emissor.innerHTML = 'off';
					Emissor.style.color = '#C1002A';
				}else{
					document.getElementById(Local).style.display = 'block';
					Emissor.innerHTML = 'on'; 
					Emissor.style.color = '#00882B';
				}
				break;
		}
	}
	function cadastrar(){
		if(validar()==true){
			document.formulario.submit();
		}
	}
	function Foco(campo,acao,obrigatorio){
		if(acao == 'in'){
			switch (obrigatorio){
				case true:
					campo.style.backgroundColor = "#FFE784";
					break;
				case 'auto':
					campo.style.backgroundColor = "#E6EDFF";
					break;
				default:
					campo.style.backgroundColor = "#FFEAEA";
					break;
			}			
		}else{
			campo.style.backgroundColor = "#FFFFFF";
		}	
	}
	function mascara(campo,event,tipo,acesso){
		var nTecla;
		
		if(document.all) { // Internet Explorer
		    nTecla = event.keyCode;
		} else if(document.layers) { // Nestcape
		    nTecla = event.which;
		} else {
		    nTecla = event.which;
		}
		
		if(nTecla == 0)  return false;
		if(nTecla == 8)  return true; 
		
		if(tipo	==	"PlanoConta"){
			if(acesso == "N" || acesso == undefined){
				numMenor=0;
				numMaior=9;
				var string = campo.value;
				if(string.length-1 >= 0){
					if(string.substr(string.length-1,1) == "." && nTecla == 46){
						event.returnValue = false;				
					}
				}
				if ((nTecla < (48 + numMenor) && nTecla != 46) || nTecla > (48 + numMaior)){
					if(nTecla!=9){
						event.returnValue = false;
					}
				}
				//48 a 57 ascII - 0 a 9
			}else{
				return false;
			}
		}	
		if(tipo	==	"cfop"){
			var tamMax = campo.maxLength;
			var tam = campo.value.length;
			numMenor=0;
			numMaior=9;
			
			if(nTecla < (48 + numMenor) || nTecla > (48 + numMaior) || tamMax == tam){
				if(nTecla!=9){
					event.returnValue = false
				}
			}else{				
				 mascara_cfop(campo,'onkeypress');
			}
		}		
		if(tipo	==	"int"){
			numMenor=0;
			numMaior=9;
			if (nTecla < (48 + numMenor) || nTecla > (48 + numMaior)){
				if (event.preventDefault){ //standart browsers
					if(nTecla!=9){
						event.preventDefault();
					}
				}else{ // internet explorer
					event.returnValue = false;
				}
			}
			//48 a 57 ascII - 0 a 9
		}
		if(tipo	==	"numerico"){
			numMenor=0;
			numMaior=9;
			if(nTecla != 45){
				if (nTecla < (48 + numMenor) || nTecla > (48 + numMaior)){
					if (event.preventDefault){ //standart browsers
						event.preventDefault();
					}else{ // internet explorer
						event.returnValue = false;
					}
				}
			}else{
				if(campo.value.length > 0){
					temp	=	campo.value.indexOf('-'); 
					if(temp >= 0){
						if (event.preventDefault){ //standart browsers
							if(nTecla!=9){
								event.preventDefault();
							}
						}else{ // internet explorer
							event.returnValue = false;
						}
					}
				}
			}
			//48 a 57 ascII - 0 a 9
		}
		if(tipo	==	"double"){
			var tamMax = campo.maxLength;
			var tam = campo.value.length;
			numMenor=0;
			numMaior=9;
			
			if(nTecla < (48 + numMenor) || nTecla > (48 + numMaior) || tamMax == tam){
				if(nTecla!=9){
					event.returnValue = false
				}
			}else{				
				 mascara_float(campo,'onkeypress');
			}
		}
		if(tipo	==	"float"){
			var tamMax = campo.maxLength;
			var tam = campo.value.length;
			numMenor=0;
			numMaior=9;
			
			if(nTecla < (48 + numMenor) || nTecla > (48 + numMaior) || tamMax == tam){
				if(nTecla!=9){
					event.returnValue = false
				}
			}else{				
				 mascara_double(campo,'onkeypress');
			}
		}
		if(tipo	==	"date"){
			if(campo.value.length>=10){
				return false;
			}
			numMenor=0;
			numMaior=9;
			if (nTecla < (48 + numMenor) || nTecla > (48 + numMaior)){
				if(nTecla!=9){
					event.returnValue = false
				}
				//48 a 57 ascII - 0 a 9
			}
			if(campo.value.length==2 || campo.value.length==5){
				campo.value = campo.value + "/";
			}
		}
		if(tipo	==	"cnpj"){
			if(campo.value.length>=18){
				return false;
			}
			numMenor=0;
			numMaior=9;
			if (nTecla < (48 + numMenor) || nTecla > (48 + numMaior)){
				if(nTecla!=9){
					event.returnValue = false
					//48 a 57 ascII - 0 a 9
				}
			}
			if(campo.value.length==2 || campo.value.length==6){
				campo.value = campo.value + ".";
			}
			if(campo.value.length==10){
				campo.value = campo.value + "/";
			}
			if(campo.value.length==15){
				campo.value = campo.value + "-";
			}
		}
		if(tipo== "cpf"){
			if(campo.value.length>=14){
				return false;
			}
			numMenor=0;
			numMaior=9;
			if (nTecla < (48 + numMenor) || nTecla > (48 + numMaior)){
				if(nTecla!=9){
					event.returnValue = false
					//48 a 57 ascII - 0 a 9
				}
			}
			var mycpf = '';
	        mycpf = mycpf + campo;
	        if (campo.value.length == 3){
	              campo.value = campo.value + '.';
	        }
	        if (campo.value.length == 7){
	              campo.value = campo.value + '.';
	        }
	        if (campo.value.length == 11){
	              campo.value = campo.value + '-';
	        }
		}
		if(tipo== "cpf_cnpj"){
			numMenor=0;
			numMaior=9;
			if (nTecla < (45 + numMenor) || nTecla > (48 + numMaior)){
				if(nTecla!=9){
					event.returnValue = false
					//48 a 57 ascII - 0 a 9
				}
			}
		}
		if(tipo	==	"fone"){
			if(campo.value.length>=13){
				return false;
			}
			numMenor=0;
			numMaior=9;
			if (nTecla < (48 + numMenor) || nTecla > (48 + numMaior)){
				if(nTecla!=9){
					event.returnValue = false
					//48 a 57 ascII - 0 a 9
				}
			}
			if(campo.value.length==0){
				campo.value = campo.value + "(";
			}
			if(campo.value.length==3){
				campo.value = campo.value + ")";
			}
			if(campo.value.length==8){
				campo.value = campo.value + "-";
			}
		}
		if(tipo	==	"cep"){
			if(campo.value.length>=9){
				return false;
			}
			numMenor=0;
			numMaior=9;
			if (nTecla < (48 + numMenor) || nTecla > (48 + numMaior)){
				event.returnValue = false
				//48 a 57 ascII - 0 a 9
			}
			if(campo.value.length==5){
				campo.value = campo.value + "-";
			}
		}
		if(tipo	==	"mes"){
			if(campo.value.length>7){
				return false;
			}
			numMenor=0;
			numMaior=9;
			if (nTecla < (48 + numMenor) || nTecla > (48 + numMaior)){
				if(nTecla!=9){
					event.returnValue = false
					//48 a 57 ascII - 0 a 9
				}
			}
			if(campo.value.length==2){
				campo.value = campo.value + "/";
			}
		}
		if(tipo	==	"hora"){
			if(campo.value.length>7){
				return false;
			}
			numMenor=0;
			numMaior=9;
			if (nTecla < (48 + numMenor) || nTecla > (48 + numMaior)){
				if(nTecla!=9){
					event.returnValue = false
					//48 a 57 ascII - 0 a 9
				}
			}
			if(campo.value.length==2){
				campo.value = campo.value + ":";
			}
		}
		if(tipo	==	"mac"){
			if(campo.value.length>=17){
				return false;
			}
			if(campo.value.length==2 || campo.value.length==5 || campo.value.length==8 || campo.value.length==11 || campo.value.length==14){
				campo.value = campo.value + ":";
			}
		}
	}
	function mascara_double(campo,evento){
		var tamMax = campo.maxLength;
		var tam = campo.value.length;
		var str = campo.value;
		var pos = 0;
		
		if(evento == ''){
			pos = 1;
		}	
	
		if(str!=''){
			str = str.replace(",","");	// Tira as vírgulas
			str = str.replace(".","");	// Tira os pontos
			str = str*1;	// Converte para inteiro
			campo.value = str;
		}
		
		tam = campo.value.length;
		
		switch (tam){
			case 0:
				campo.value = "0,0" + campo.value;
				break;	
			case 1:
				campo.value = "0," + campo.value;
				break;
			default:
				var decimal 	= campo.value.substr(tam-1-pos,1+pos);
				var inteiro 	= campo.value.substr(0,tam-1-pos);
				var inteiroTam 	= inteiro.length;
				var inteiros 	= new Array();
				var i=0;
				var ii;
					
				while((inteiroTam%3) != 0){
					inteiro		= "0"+inteiro;
					inteiroTam 	= inteiro.length;					
				}
				
				while(inteiro != ''){
					inteiros[i] = inteiro.substr(0,3);
					inteiro 	= inteiro.substr(3,inteiroTam);
					
					inteiroTam 	= inteiro.length;
					i++;
				}
				
				if(inteiros[0] != ''){
					inteiros[0]	= Number(inteiros[0]);
				}
	
				for(ii=0;ii<i;ii++){
					if(inteiros[ii]!='' && inteiros[ii]!=undefined){
						if(inteiro!=''){
							inteiro = inteiro + '.';
						}
						inteiro = inteiro + inteiros[ii];
					}
				}
				
				str = inteiro + ',' + decimal;
				campo.value = str;
				break;					
		}
	}
	function mascara_float(campo,evento){
		var tamMax = campo.maxLength;
		var tam = campo.value.length;
		var str = campo.value;
		var pos = 0;
		
		if(evento == ''){
			pos = 1;
		}	
	
		if(str!=''){
			str = str.replace(",","");	// Tira as vírgulas
			str = str.replace(".","");	// Tira os pontos
			str = str*1;	// Converte para inteiro
			campo.value = str;
		}
		
		tam = campo.value.length;
		
		switch (tam){
			case 0:
				campo.value = "0,00" + campo.value;
				break;	
			case 1:
				campo.value = "0,0" + campo.value;
				break;
			case 2:
				campo.value = "0," + campo.value;
				break;
			default:
				var decimal 	= campo.value.substr(tam-2-pos,2+pos);
				var inteiro 	= campo.value.substr(0,tam-2-pos);
				var inteiroTam 	= inteiro.length;
				var inteiros 	= new Array();
				var i=0;
				var ii;
					
				while((inteiroTam%3) != 0){
					inteiro		= "0"+inteiro;
					inteiroTam 	= inteiro.length;					
				}
				
				while(inteiro != ''){
					inteiros[i] = inteiro.substr(0,3);
					inteiro 	= inteiro.substr(3,inteiroTam);
					
					inteiroTam 	= inteiro.length;
					i++;
				}
				
				if(inteiros[0] != ''){
					inteiros[0]	= Number(inteiros[0]);
				}
	
				for(ii=0;ii<i;ii++){
					if(inteiros[ii]!='' && inteiros[ii]!=undefined){
						if(inteiro!=''){
							inteiro = inteiro + '.';
						}
						inteiro = inteiro + inteiros[ii];
					}
				}
				
				str = inteiro + ',' + decimal;
				campo.value = str;
				break;					
		}
	}
	function mascara_cfop(campo,evento){
		var tamMax = campo.maxLength;
		var tam = campo.value.length;
		var str = campo.value;
		var pos = 0;
		
		if(evento == ''){
			pos = 1;
		}	
	
		if(str!=''){
			str = str.replace(",","");	// Tira as vírgulas
			str = str.replace(".","");	// Tira os pontos
			str = str*1;	// Converte para inteiro
			campo.value = str;
		}
		
		tam = campo.value.length;
		
		switch (tam){
			case 0:
				campo.value = "0.0000" + campo.value;
				break;	
			case 1:
				campo.value = "0.000" + campo.value;
				break;
			case 2:
				campo.value = "0.00" + campo.value;
				break;
			case 3:
				campo.value = "0.0" + campo.value;
				break;
			case 4:
				campo.value = "0." + campo.value;
				break;
			default:
				var decimal 	= campo.value.substr(tam-4-pos,4+pos);
				var inteiro 	= campo.value.substr(0,tam-4-pos);
				var inteiroTam 	= inteiro.length;
				var inteiros 	= new Array();
				var i=0;
				var ii;
					
				while((inteiroTam%5) != 0){
					inteiro		= "0"+inteiro;
					inteiroTam 	= inteiro.length;					
				}
				
				while(inteiro != ''){
					inteiros[i] = inteiro.substr(0,5);
					inteiro 	= inteiro.substr(5,inteiroTam);
					
					inteiroTam 	= inteiro.length;
					i++;
				}
				
				if(inteiros[0] != ''){
					inteiros[0]	= Number(inteiros[0]);
				}
	
				for(ii=0;ii<i;ii++){
					if(inteiros[ii]!='' && inteiros[ii]!=undefined){
						if(inteiro!=''){
							inteiro = inteiro + '.';
						}
						inteiro = inteiro + inteiros[ii];
					}
				}
				
				str = inteiro + '.' + decimal;
				campo.value = str;
				break;					
		}
	}
	function carregando(acao){
		if(acao == true){
			document.getElementById("carregando").style.display = 'block';
		}else{
			document.getElementById("carregando").style.display = 'none';
		}
		return true;
	}
	function sair(){
		parent.location.replace('../../rotinas/sair.php');
	}
	function janelas(nomeJanela,largura,altura,vtop,vleft,parametro,scro){
		if(scro == ''){
			scro = 'no';
		}
		dados 	=	"top="+vtop+",left="+vleft+",scrollbars="+scro+",status=no,toolbar=no,location=no,menu=no,width="+largura+",height="+altura;
		janela	=	window.open(nomeJanela+parametro,"_blank",dados);
		
		if(janela == null){
			alert('ERRO\nVerifique seu bloqueador de pop-up!');
		}
	}
	function busca_pais(IdPais,Erro,Local){
		if(IdPais == ''){
			IdPais = 0;
		}
		if(Local == '' || Local == undefined){
			Local	=	document.formulario.Local.value;
		}
		var nameNode, nameTextNode, url;
		
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
	   	url = "../administrativo/xml/pais.php?IdPais="+IdPais;
		xmlhttp.open("GET", url,true);
		
		xmlhttp.onreadystatechange = function(){ 
			
			// Carregando...
			carregando(true);
	
			if(xmlhttp.readyState == 4){ 
				if(xmlhttp.status == 200){
	
					if(Erro != false){
	//						document.formulario.Erro.value = 0;
	//						verificaErro();
					}
					if(xmlhttp.responseText == 'false'){
						
						document.formulario.IdPais.value 	= '';
						document.formulario.Pais.value		= '';
						
						if(Local == 'Pais'){
							
							
							addParmUrl("marPais","IdPais","");
							addParmUrl("marEstado","IdPais","");
							addParmUrl("marEstadoNovo","IdPais","");
							addParmUrl("marCidade","IdPais","");
							addParmUrl("marCidadeNovo","IdPais","");
							
							document.formulario.Acao.value	= "inserir";
							document.formulario.Pais.focus();
							verificaAcao();							
						}else{
							document.formulario.IdPais.focus();
						}
											
						// Fim de Carregando
						carregando(false);
					}else{
						nameNode = xmlhttp.responseXML.getElementsByTagName("IdPais")[0]; 
						nameTextNode = nameNode.childNodes[0];
						IdPais = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("NomePais")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var NomePais = nameTextNode.nodeValue;					
						
						document.formulario.IdPais.value	= IdPais;
						document.formulario.Pais.value 		= NomePais;
						
						if(Local == 'Pais'){
							document.formulario.Acao.value	= "alterar";
							verificaAcao();	
							addParmUrl("marPais","IdPais",IdPais);
							addParmUrl("marEstado","IdPais",IdPais);
							addParmUrl("marEstadoNovo","IdPais",IdPais);
							addParmUrl("marCidade","IdPais",IdPais);
							addParmUrl("marCidadeNovo","IdPais",IdPais);			
						}
					}
					if(document.formulario.IdEstado != undefined){
						document.formulario.IdEstado.value 	= '';
						document.formulario.Estado.value	= '';
						
						if(Local == 'Pessoa'){
							document.formulario.IdEstado.focus();							
						}
						if(Local == 'Estado'){
							document.formulario.SiglaEstado.value	= '';
							document.formulario.Acao.value	= "inserir";
							verificaAcao();	
						}
					}
					if(document.formulario.IdCidade != undefined){
						document.formulario.IdCidade.value 	= '';
						document.formulario.Cidade.value	= '';
						
						if(Local == 'Cidade'){
							document.formulario.Acao.value	= "inserir";
							verificaAcao();	
						}							
					}
					
					if(document.getElementById("quadroBuscaPais").style.display	==	"block"){
						vi_id('quadroBuscaPais', false);
					}
				}
				// Fim de Carregando
				carregando(false);
			} 
			return true;
		}
		xmlhttp.send(null);
		return true;
	}
	function busca_estado(IdPais,IdEstado,Erro,Local){
		if(Local == '' || Local == undefined){
			Local	=	document.formulario.Local.value;
		}
		if(IdEstado == ''){
			IdEstado = 0;
		}
		var nameNode, nameTextNode, url;
		
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
	    
	   	url = "../administrativo/xml/estado.php?IdPais="+IdPais+"&IdEstado="+IdEstado;
		xmlhttp.open("GET", url,true);

		xmlhttp.onreadystatechange = function(){ 
	
			// Carregando...
			carregando(true);
	
			if(xmlhttp.readyState == 4){ 
				if(xmlhttp.status == 200){
	
					if(Erro != false){
	//						document.formulario.Erro.value = 0;
	//						verificaErro();
					}
					if(xmlhttp.responseText == 'false'){
						document.formulario.IdEstado.value	 	= '';
						document.formulario.Estado.value		= '';
						
						if(IdPais == ''){
							document.formulario.IdPais.focus();
						}else{
							document.formulario.IdEstado.focus();
						}
						switch(Local){
							case 'Estado':
								addParmUrl("marCidade","IdEstado","");
								addParmUrl("marCidadeNovo","IdEstado","");
							
								document.formulario.SiglaEstado.value	=	'';
								document.formulario.Acao.value			=   'inserir';
								document.formulario.Estado.focus();
								verificaAcao();
								break;
							case 'Cidade':
								document.formulario.IdCidade.value	 	= '';
								document.formulario.Cidade.value		= '';
								document.formulario.Acao.value			=   'inserir';
								verificaAcao();
								break;
						}
						// Fim de Carregando
						carregando(false);
					}else{
						nameNode = xmlhttp.responseXML.getElementsByTagName("IdEstado")[0]; 
						nameTextNode = nameNode.childNodes[0];
						IdEstado = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("NomeEstado")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var NomeEstado = nameTextNode.nodeValue;
											
						document.formulario.IdEstado.value	= IdEstado;
						document.formulario.Estado.value 	= NomeEstado;
						
						if(document.formulario.IdCidade != undefined){
							if(Local == 'Pessoa'){
								document.formulario.IdCidade.focus();
							}
						}
						
						switch(Local){
							case 'Estado':
								nameNode = xmlhttp.responseXML.getElementsByTagName("IdPais")[0]; 
								nameTextNode = nameNode.childNodes[0];
								IdPais = nameTextNode.nodeValue;
						
								nameNode = xmlhttp.responseXML.getElementsByTagName("NomePais")[0]; 
								nameTextNode = nameNode.childNodes[0];
								var NomePais = nameTextNode.nodeValue;
								
								nameNode = xmlhttp.responseXML.getElementsByTagName("SiglaEstado")[0]; 
								nameTextNode = nameNode.childNodes[0];
								var SiglaEstado = nameTextNode.nodeValue;					
								
								addParmUrl("marPais","IdPais",IdPais);
								addParmUrl("marEstado","IdPais",IdPais);
								addParmUrl("marEstado","IdEstado",IdEstado);
								addParmUrl("marEstadoNovo","IdPais",IdPais);
								addParmUrl("marCidade","IdPais",IdPais);
								addParmUrl("marCidade","IdEstado",IdEstado);
								addParmUrl("marCidadeNovo","IdPais",IdPais);
								addParmUrl("marCidadeNovo","IdEstado",IdEstado);
								
								
								document.formulario.IdPais.value		= IdPais;
								document.formulario.Pais.value 			= NomePais;
								document.formulario.SiglaEstado.value	= SiglaEstado;	
								document.formulario.Acao.value			= 'alterar';												
								verificaAcao();
								break;
						}
					}
					if(document.formulario.IdCidade != undefined){
						document.formulario.IdCidade.value 	= '';
						document.formulario.Cidade.value	= '';							
					}
					if(document.getElementById("quadroBuscaEstado").style.display	==	"block"){
						document.getElementById("quadroBuscaEstado").style.display	=	"none";
					}
				}
				// Fim de Carregando
				carregando(false);
			} 
			return true;
		}
		xmlhttp.send(null);
		return true;
	}
	function busca_cidade(IdPais,IdEstado,IdCidade,Erro,Local,Listar){
		if(IdCidade == ''){
			IdCidade = 0;
		}
		if(Local == '' || Local == undefined){
			Local = document.formulario.Local.value;
		}
		var nameNode, nameTextNode, url;
		
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
	    
	   	url = "../administrativo/xml/cidade.php?IdPais="+IdPais+"&IdEstado="+IdEstado+"&IdCidade="+IdCidade;
	   	
	   	xmlhttp.open("GET", url,true);
		
		xmlhttp.onreadystatechange = function(){ 
	
			// Carregando...
			carregando(true);
	
			if(xmlhttp.readyState == 4){ 
				if(xmlhttp.status == 200){
	
					if(Erro != false){
	//						document.formulario.Erro.value = 0;
	//						verificaErro();
					}
					if(xmlhttp.responseText == 'false'){
						if(Local != 'Servico'){
							document.formulario.IdCidade.value 	= '';
							document.formulario.Cidade.value	= '';
		
							if(document.formulario.IdPais.value == ''){
								document.formulario.IdPais.focus();
							}else if(document.formulario.IdEstado.value == ''){
								document.formulario.IdEstado.focus();
							}else{
								switch(Local){
									case 'Cidade':
										addParmUrl("marCidade","IdCidade","");
										
										document.formulario.IdCidade.focus();
										break;
								}
							}
						}
						if(Local == 'Cidade'){
							document.formulario.Acao.value 		= 'inserir';
						}
						if(Local == 'Pessoa'){
							document.formulario.SiglaEstado.value	=	'';
						}					
						// Fim de Carregando
						carregando(false);
					}else{
						nameNode = xmlhttp.responseXML.getElementsByTagName("IdCidade")[0]; 
						nameTextNode = nameNode.childNodes[0];
						IdCidade = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("NomeCidade")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var NomeCidade = nameTextNode.nodeValue;					
							
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
						
						if(Local == 'Cidade' || Local == 'Pessoa'){
							document.formulario.IdPais.value 	= IdPais;
							document.formulario.Pais.value 		= NomePais;
							document.formulario.IdEstado.value 	= IdEstado;
							document.formulario.Estado.value 	= NomeEstado;
							
							if(Local == 'Cidade'){
								addParmUrl("marPais","IdPais",IdPais);
								addParmUrl("marEstado","IdPais",IdPais);
								addParmUrl("marEstado","IdEstado",IdEstado);
								addParmUrl("marEstadoNovo","IdPais",IdPais);
								addParmUrl("marCidade","IdPais",IdPais);
								addParmUrl("marCidade","IdEstado",IdEstado);
								addParmUrl("marCidade","IdCidade",IdCidade);
								addParmUrl("marCidadeNovo","IdPais",IdPais);
								addParmUrl("marCidadeNovo","IdEstado",IdEstado);
							
								document.formulario.Acao.value 		= 'alterar';
							}
						}
						
						if(Local == 'Pessoa'){
							document.formulario.SiglaEstado.value	=	SiglaEstado;
						}
						
						
						if(Local != 'Servico'){
							document.formulario.IdCidade.value	= IdCidade;
							document.formulario.Cidade.value 	= NomeCidade;
						}else{
							var cont = 0; ii='';
							if(document.formulario.Filtro_IdPaisEstadoCidade.value == ''){
								document.formulario.Filtro_IdPaisEstadoCidade.value = IdPais+","+IdEstado+","+IdCidade;
								ii = 0;
							}else{
								var tempFiltro	=	document.formulario.Filtro_IdPaisEstadoCidade.value.split('^');
									
								ii=0; 
								while(tempFiltro[ii] != undefined){
									if(tempFiltro[ii] != IdPais+","+IdEstado+","+IdCidade){
										cont++;		
									}
									ii++;
								}
								if(ii == cont){
									document.formulario.Filtro_IdPaisEstadoCidade.value = document.formulario.Filtro_IdPaisEstadoCidade.value + "^" + IdPais+","+IdEstado+","+IdCidade;
								}
							}
							if(ii == cont){
								var tam, linha, c0, c1, c2, c3, c4;
								
								tam 	= document.getElementById('tabelaCidade').rows.length;
								linha	= document.getElementById('tabelaCidade').insertRow(tam-1);
								
								if(tam%2 != 0){
									linha.style.backgroundColor = "#E2E7ED";
								}
								
								linha.accessKey 	= IdPais+","+IdEstado+","+IdCidade; 
								
								c0	= linha.insertCell(0);	
								c1	= linha.insertCell(1);	
								c2	= linha.insertCell(2);	
								c3	= linha.insertCell(3);
								c4	= linha.insertCell(4);
								
								var linkIni = "<a href='cadastro_cidade.php?IdPais="+IdPais+"&IdEstado="+IdEstado+"&IdCidade="+IdCidade+"'>";
								var linkFim = "</a>";
								
								c0.innerHTML = linkIni + (document.getElementById('tabelaCidade').rows.length-2) + linkFim;
								c0.style.padding =	"0 0 0 5px";
								
								c1.innerHTML = linkIni + NomePais + linkFim;
								
								c2.innerHTML = linkIni + NomeEstado + linkFim;
								
								c3.innerHTML = linkIni + NomeCidade + linkFim;
								
								if(document.formulario.IdStatus.value == 1){
									c4.innerHTML = "<img src='../../img/estrutura_sistema/ico_del.gif' alt='Excluir?' onClick=\"remover_filtro_cidade("+IdPais+","+IdEstado+","+IdCidade+")\"></tr>";
								}else{
									c4.innerHTML = "<img src='../../img/estrutura_sistema/ico_del_c.gif' alt='Excluir?'\">";
								}
								c4.style.textAlign = "center";
								c4.style.cursor = "pointer";
								
								if(Local == 'ProcessoFinanceiro'){
									if(document.formulario.IdProcessoFinanceiro.value == ''){
										document.getElementById('totaltabelaCidade').innerHTML	=	'Total: '+(ii+1);
									}else{
										if(document.formulario.Erro.value != ''){
											scrollWindow('bottom');
										}
									}
								}
								
								if(Listar != undefined){
									scrollWindow('bottom');
								}
							}
							//document.formulario.IdCidade.value	= "";
							//document.formulario.Cidade.value 	= "";
						}
					}
					if(document.getElementById("quadroBuscaCidade").style.display	==	"block"){
						vi_id('quadroBuscaCidade', false);
					}
					verificaAcao();
				}
				// Fim de Carregando
				carregando(false);
			} 
			return true;
		}
		xmlhttp.send(null);
		return true;
	}
	function keyDown(e){
		if(teclas_atalho == false){
			return true;
		}
		
		
		var e = e || event;
		var nTecla = e.keyCode || e.which;
		
		if(navigator.appName == 'Netscape'){
			var tipoCampo =  e.target.type;
			var editavel  =  e.target.readOnly;
		}else{
			var tipoCampo =  e.srcElement.type;
			var editavel  =  e.srcElement.readOnly;
		}
		
		if(e.ctrlKey == true){
			switch (nTecla){		
				case 13: // Ctrl + Entrer -> Salvar
					if(menu_form == true){
						incluir();
						if (e.preventDefault) {
            				e.preventDefault();
            				return false;
        				}
        				else {
            				e.keyCode = 0;
            				e.returnValue = false;
        				}
					}
					break;
				case 72: // Ctrl + H -> Home
					url = 'index.php';
					window.location.replace(url);
					if (e.preventDefault) {
            			e.preventDefault();
            			return false;
        			}
        			else {
            			e.keyCode = 0;
            			e.returnValue = false;
        			}
					break;
				case 78: // Ctrl + N -> Novo
					if(menu_form == true){
						cancelar();					
						if (e.preventDefault) {
            				e.preventDefault();
            				return false;
        				}
        				else {
            				e.keyCode = 0;
            				e.returnValue = false;
        				}
					}
					break;
				case 80: // Ctrl + P -> Imprimir
					imprimir();
					if (e.preventDefault) {
            			e.preventDefault();
            			return false;
        			}
        			else {
            			e.keyCode = 0;
            			e.returnValue = false;
        			}
					break;
				case 113: // Ctrl + F2 -> Cadastro de Produtos
					url = 'cadastro_produto.php';
					window.location.replace(url);
					if (e.preventDefault) {
            			e.preventDefault();
            			return false;
        			}
        			else {
            			e.keyCode = 0;
            			e.returnValue = false;
        			}
					break;
			}
		}else{
			switch (nTecla){			
				case 13: // Entrer -> Próximo campo
					if(tipoCampo != 'textarea' && tipoCampo != 'submit' && tipoCampo != 'button'){
						if(navigator.appName == 'Netscape'){
							e.which  = 9;
						}else{
							e.keyCode = 9;
						}
					}
					break;
				case 118: // F7 -> Cancelar
					if(menu_form == true){
						cancelar();
						if (e.preventDefault) {
            				e.preventDefault();
            				return false;
        				}
        				else {
            				e.keyCode = 0;
            				e.returnValue = false;
        				}
					}
					break;
				case 119: // F8 -> Listar Todos
					if(menu_form == true){
						listar_todos();
						if (e.preventDefault) {
            				e.preventDefault();
            				return false;
        				}
        				else {
            				e.keyCode = 0;
            				e.returnValue = false;
        				}
					}
					break;
				case 8:
					if(editavel == true){
						if (e.preventDefault) {
            				e.preventDefault();
            				return false;
        				}
        				else {
            				e.returnValue = false;
        				}
					}
					break;
			}		
		}
	}
	function listar(e){
		var e = e || event;
		var k = e.keyCode || e.which;
		
		if(k==13) document.filtro.submit();
	}
	
	document.onkeydown = keyDown
	
	function imprimir(){
		window.print();
	}
	
	function salvar(){
		IdGrupoParametroSistema = 13;
		switch (document.formulario.Acao.value){
			case 'inserir':
				var IdParametroSistema = 40;
				break;
			case 'alterar':			
				var IdParametroSistema = 41;
				break;
			case 'receber':			
				var IdParametroSistema = 41;
				break;
			case 'login':			
				salva_formulario();
				break;
			default:
				return false;
				break;
		}
		if(document.formulario.Acao.value != 'login'){
			var msg = '';
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
		    var Local = document.formulario.Local;
		    if(Local != undefined){
		    	if(Local.value == 'index'){
					url = "xml/codigo_interno_free.php";
				}else{	
		   			url = "../../xml/codigo_interno_free.php";
	   			}
	   		}
			url = url + "?IdGrupoParametroSistema=" + IdGrupoParametroSistema + "&IdParametroSistema=" + IdParametroSistema;
			xmlhttp.open("GET", url,true);
	   		xmlhttp.onreadystatechange = function(){ 
	   			if(xmlhttp.readyState == 4){ 
					if(xmlhttp.status == 200){
						nameNode = xmlhttp.responseXML.getElementsByTagName('ValorParametroSistema')[0]; 
						if(nameNode != null){
							nameTextNode = nameNode.childNodes[0];
							msg = nameTextNode.nodeValue;
						}else{
							msg = '';
						}
						if(confirm(msg) == true){
							salva_formulario();
						}
					}
				}
			}
			xmlhttp.send(null);
		}
	}
	function salva_formulario(){
		if(validar() == true){
			document.formulario.submit();
		}
	}
	function ativaNome(nome){
		if(window.parent.cabecalho != undefined){	
			window.parent.menu.document.formulario.codigo_barra.value = '';
			window.parent.cabecalho.document.getElementById('cp_modulo_atual').innerHTML = nome;
		}else{
			window.location = 'index.php?url=' + window.location;
		}
	}
	function ativaNomeHelpDesk(nome){
		if(window.parent.cabecalho != undefined){
			window.parent.cabecalho.document.getElementById('cp_modulo_atual').innerHTML = nome;
		}else{
			window.location = 'index.php?url=' + window.location;
		}
	}
	function verifica_dado(dado){
		if(dado == ''){
			dado = "&nbsp;";
		}
		return dado;
	}
	function janela_busca_cep(){
		janelas('../administrativo/busca_cep.php',360,200,150,600,'');
	}
	function JsMail(email){
		if(email!=''){
			parent.location.href='mailto:'+email;
		}else{
			return false;
		}
	}
	function dateFormat(date){
		if(date == ''){	return '';	}
		var year 	= date.substring(0,4);
		var month 	= date.substring(5,7);
		var day 	= date.substring(8,10);
		var end 	= date.substring(11,date.length);
		
		var date = day + "/" + month + "/" + year;
		
		if(end != ''){
			date = date + " " + end;
		}
		
		return date;
	}
	function tableMultColor(Id,Cor){
		var i,temp;
		
		if(Id == 'quadroAvisoOrdem' || Id == 'quadroAvisoOrdemUsuario'){
			temp=1;
		}else{
			temp=0;
		}
		
		for(i=1; i < document.getElementById(Id).rows.length; i++){
			if(i%2 == temp){
				document.getElementById(Id).rows[i].bgColor = Cor;
			}else{
				document.getElementById(Id).rows[i].bgColor = '#FFFFFF';			
			}		
		}		
	}
	function destacaRegistro(campo,acao){
		if(acao == true){
			campo.style.backgroundColor='#A0C4EA';
		}else{
			campo.style.backgroundColor='';
		}		
	}
	function filtro_ordenar(valor,typeDate,valor2,typeDate2){
		if(typeDate == undefined){		typeDate = 'text';		}
		if(valor2 == undefined){		valor2 = '';			}
		if(typeDate2 == undefined){		typeDate2 = 'text';		}
		
		if(document.filtro.filtro_tipoDado != undefined){
			document.filtro.filtro_tipoDado.value = typeDate;
		}
		
		if(document.filtro.filtro_ordem.value == valor){
			if(document.filtro.filtro_ordem_direcao.value == "ascending"){
				document.filtro.filtro_ordem_direcao.value = "descending";
			}else{
				document.filtro.filtro_ordem_direcao.value = "ascending";
			}
		}else{
			document.filtro.filtro_ordem.value = valor;
		}
		
		if(valor2 != ""){
			if(document.filtro.filtro_tipoDado2 != undefined){
				document.filtro.filtro_tipoDado2.value = typeDate2;
			}
			
			if(document.filtro.filtro_ordem2.value == valor2){
				if(document.filtro.filtro_ordem_direcao2.value == "ascending"){
					document.filtro.filtro_ordem_direcao2.value = "descending";
				}else{
					document.filtro.filtro_ordem_direcao2.value = "ascending";
				}
			}else{
				document.filtro.filtro_ordem2.value = valor2;
			}
		}
		
		document.filtro.submit();
	}
	function cancelar_registro(){
		return confirm("ATENCAO!\n\nVoce esta prestes a cancelar um registro.\nDeseja continuar?","SIM","NAO");
	}
	function excluir_registro(){
		return confirm("ATENCAO!\n\nVoce esta prestes a excluir um registro.\nDeseja continuar?","SIM","NAO");
	}
	function verificaAcao(){
		if(document.formulario != undefined){
			if(document.formulario.Acao.value=='inserir'){			
				document.formulario.bt_inserir.disabled 	= false;
				document.formulario.bt_alterar.disabled 	= true;
				document.formulario.bt_excluir.disabled 	= true;
			}
			if(document.formulario.Acao.value=='alterar'){			
				document.formulario.bt_inserir.disabled 	= true;
				document.formulario.bt_alterar.disabled 	= false;
				document.formulario.bt_excluir.disabled 	= false;
			}
		}	
	}
	function data(){
		var hoje = new Date();

		var dia = hoje.getDate();
		var mes = (hoje.getMonth())+1; 
		var ano = hoje.getFullYear();
		
		if(dia < 10) 	dia = "0" + dia;	
		if(mes < 10)	mes = "0" + mes;
		
		hoje = dia +"/"+mes+"/"+ano;
		
		return hoje;
	}
	function hora(){
		var hoje = new Date();

		var hours 	= hoje.getHours();
		var minutes = hoje.getMinutes();
		
		
		if(hours < 10) 		hours = "0" + hours;	
		if(minutes < 10)	minutes = "0" + minutes;
		
		hoje = hours +":"+minutes;
		
		return hoje;
	}
	function formatDate(date){
		if(date == ''){	
			return '';	
		}
		var year 	= date.substring(6,10);
		var month 	= date.substring(3,5);
		var day 	= date.substring(0,2);
		var end 	= date.substring(11,date.length);
		
		var date = year + "-" + month + "-" + day;
		
		if(end != ''){
			date = date + " " + end;
		}
		
		return date;
	}
	
	function formata_float(campo,casas){
		campo = String(campo);
		if(casas == '' || casas==undefined)	casas=2;
		var cont = campo.split('.');
		if(cont[1] != undefined){
			cont = cont[1].length;
			if(cont<casas){
				while(cont<casas){
					campo = campo + "0";
					cont++;
				}
			}
		}
		else{
			cont = 1
			while(cont<=casas){
				if(cont == 1){
					campo = campo + ".0";
				}else{
					campo = campo + "0";
				}
				cont++;
			}
		}
		return campo;
	}
	function Arredonda( valor , casas ){
		var novo = Math.round( valor * Math.pow( 10 , casas ) ) / Math.pow( 10 , casas );
   		return novo;
	}
	function addParmUrl(IdDestino,Parm,Valor,AnularTodos){
		if(document.getElementById(IdDestino) == null && Parm == "" && Valor == ""){
			return false;
		}else if(document.getElementById(IdDestino)!= null && Valor == ""){
			var url = document.getElementById(IdDestino).href;
			var ArrayTemp 	= url.split("?");
			url				= ArrayTemp[0];
			
			document.getElementById(IdDestino).href	=	url;
		}else{
			var url = document.getElementById(IdDestino).href;
			
			if(url.indexOf("?") == -1 || AnularTodos == true){
				url += "?";	
				url += Parm + "=" + Valor;
				urlTemp  = url;
			}else{
				var ArrayTemp 	= url.split("?");
				url				= ArrayTemp[0];
				ArrayTemp 		= ArrayTemp[1].split("&");
				
				var i = 0;
				while(i < ArrayTemp.length){
					ArrayTemp[i] = ArrayTemp[i].split("=");
					ArrayTemp[i][0]; // NomeParametro
					ArrayTemp[i][1]; // ValorParametro
					
					if(ArrayTemp[i][0] == Parm){
						ArrayTemp[i][0] = "";
						ArrayTemp[i][1] = "";
					}
					
					i++;
				}
				
				var i = 0, urlTemp = "";
				while(i < ArrayTemp.length){
					if(urlTemp != ""){
						urlTemp += "&"; 
					}
					if(ArrayTemp[i][0] != "" && ArrayTemp[i][1] != ""){	
						urlTemp += ArrayTemp[i][0] + "=" + ArrayTemp[i][1];
					}
					i++;
				}
				if(urlTemp != ""){
					urlTemp += "&"; 
				}
				urlTemp += Parm + "=" + Valor;
				urlTemp  = url + "?" + urlTemp;		
			}	
			document.getElementById(IdDestino).href = urlTemp;
		}
	}
	function val_Mes(valor){
		mes = (valor.substring(0,2)); 
		ano = (valor.substring(3,7)); 
		
		// verifica se o mes e valido 
		if (mes < 01 || mes > 12 ) { 
			return false;
		} 
		
		// verifica se ano é válido
		if (ano < 1000 ) { 
			return false;
		}
	}
	function addOption(objSelect,newName,newValor){
		var newOption; 
		var newDialog;
		newOption = new Option (newName,newValor,true,true);
		objSelect.options[objSelect.options.length] = newOption;
	}
	function verifica_estado(IdPais,campo){
		if(campo == "" || campo == undefined){
			campo	=	document.filtro.filtro_estado;
		}
	
		var xmlhttp = false;
		var nameNode, nameTextNode, url;	
		
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
		url = "xml/estado.php?IdPais="+IdPais;
		
		xmlhttp.open("GET", url,true);
		xmlhttp.onreadystatechange = function(){ 
		
			// Carregando...
			carregando(true);
			
			if(xmlhttp.readyState == 4){ 
				if(xmlhttp.status == 200){
					if(xmlhttp.responseText == 'false'){
						while(campo.options.length > 0){
							campo.options[0] = null;
						}
					}else{
						var IdEstado, NomeEstado;
						while(campo.options.length > 0){
							campo.options[0] = null;
						}
							
						addOption(campo,"Todos","0");
						for(var i=0; i<xmlhttp.responseXML.getElementsByTagName("IdEstado").length; i++){
							nameNode = xmlhttp.responseXML.getElementsByTagName("IdEstado")[i]; 
							nameTextNode = nameNode.childNodes[0];
							IdEstado = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("NomeEstado")[i]; 
							nameTextNode = nameNode.childNodes[0];
							NomeEstado = nameTextNode.nodeValue;
							
							addOption(campo,NomeEstado,IdEstado);
							campo.options[0].selected = true;
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
	function ignoreSpaces(string) { 
		var temp = ""; 
		string = '' + string; 
		splitstring = string.split(" "); 
		for(i = 0; i < splitstring.length; i++){ 
			temp += splitstring[i]; 
		}
		return temp;
	}
	function formata_string(campo){
		/*campo = campo.toLowerCase(); 
		 
		var estranha = new String(estranha);  
		estranha = "áéíóúàèìòùâêîôûäëïöüãõ@#$%^&*()_+=-~` ç"; 
		var correta  = "aeiouaeiouaeiouaeiouao________________c"; 
		  
		for(i=0;i<estranha.length;i++){ 
			for(j=0;j<campo.length;j++){ 
				campo = campo.replace(estranha.substr(i,1),correta.substr(i,1)); 
				campo = campo.replace("_",""); 
			} 
		}*/
		
		return campo;
	} 
	function mostraDataFim(p_dias,p_dtInicio){
		var temp=false;
		if((p_dias != "") && (p_dtInicio != "")){    
		    dia = p_dtInicio.charAt(0) + p_dtInicio.charAt(1);
		    mes = p_dtInicio.charAt(3) + p_dtInicio.charAt(4);
	    	ano = p_dtInicio.charAt(6) + p_dtInicio.charAt(7) + p_dtInicio.charAt(8) + p_dtInicio.charAt(9);
	    
	    
		    if ((parseInt(mes) == 1) || (parseInt(mes) == 3) || (parseInt(mes) == 5) || (parseInt(mes) == 7) || (parseInt(mes) == 8) || (parseInt(mes) == 10) || (parseInt(mes) == 12))
	 			ultimoDia = 31;
	    	else if((parseInt(mes) ==4 ) || (parseInt(mes) == 6) || (parseInt(mes) == 9) || (parseInt(mes) == 11) )
	 			ultimoDia = 30;
	    	else if( (parseInt(ano) % 4 == 0)  && (parseInt(ano) % 100 != 0) ||  (parseInt(ano) % 400 == 0))
	 			ultimoDia = 29;
	    	else
	 			ultimoDia = 28;
	 
	    	while(p_dias.value > 0){
	    		decr = 0;
	    		diaAnt = dia;
	    		dia = parseInt(dia) + parseInt(p_dias.value);
	 			if(dia >= ultimoDia){
	 				dia = 1;
	 				decr = 1;
	     			if (parseInt(mes) < 12){
	   					mes = parseInt(mes) + 1;
	    			}else{
					   mes = 1;
					   ano = parseInt(ano) + 1;
	     			}
				    p_dias = p_dias.value - (ultimoDia - diaAnt) - decr;
	 		   }else
			     p_dias = 0;
	 
		      if (dia < 10)
	 			dia = "0" + parseInt(dia);
	    	  if (mes < 10)
				 mes= "0" + parseInt(mes);
	    	}
	    	
			dataFinal = dia + "/" + mes + "/" + ano;    
	    	
	    	return dataFinal;
		} 
	}
	function numDiasMes(ano, mes){//retorna o numero de dias no mês.
		var numDias = 30;
		if(mes < 8 && mes % 2 != 0 || mes >=8 && mes % 2 == 0){
			numDias = 31;
		}else{
			if(mes == 2){
				//numDias = isAnoBissexto(ano) ? 29 : 28;
				if((ano % 400 == 0 || ano % 4 == 0 && ano % 100 != 0) == true){
					numDias = 29;
				}else{
					numDias = 28;
				}
			}
		}	
		return numDias;
	}
	
	// datas no formato ano/mês/dia
	function diferencaDias(data1, data2){
		var dif = Date.UTC(data1.getYear(),data1.getMonth(),data1.getDate(),0,0,0) - Date.UTC(data2.getYear(),data2.getMonth(),data2.getDate(),0,0,0);
	    return Math.abs((dif / 1000 / 60 / 60 / 24));
	}
	
	// datas no formato dia/mes/ano
	function difDias(data1,data2){
		var ano1 	= data1.substring(6,10);
		var mes1 	= data1.substring(3,5);
		var dia1	= data1.substring(0,2);
		var data_1 = new Date(ano1,mes1,dia1);
		
		var ano2 	= data2.substring(6,10);
		var mes2 	= data2.substring(3,5);
		var dia2	= data2.substring(0,2);
		var data_2 = new Date(ano2,mes2,dia2);
		
		var diferenca = data_1.getTime() - data_2.getTime();
		var diferenca = Math.floor(diferenca / (1000 * 60 * 60 * 24));
		
		return diferenca;
	}

  	function vi_id(id, visivel, mouse, width, top, aux){
		var validar	=	true;
		
		if(visivel == true){
			switch(document.formulario.Local.value){
				case 'Contrato':
					if(id == 'quadroBuscaPessoa'){
						if(document.formulario.IdContrato.value != ""){
							validar = false;
						}
					}
					if(id == 'quadroBuscaServico'){
						if(document.formulario.IdContrato.value != ""){
							validar = false;
						}else{
							if(document.formulario.IdPessoa.value == '' && document.formulario.IdPessoaF.value == ''){
								document.formulario.IdPessoa.focus();
								mensagens(104);	
								validar = false;
							}
						}
					}
					break;
				case 'ProcessoFinanceiro':
					if(id == 'quadroBuscaPessoa' || id == 'quadroBuscaPais' || id == 'quadroBuscaEstado' || id == 'quadroBuscaCidade' || id == 'quadroBuscaContrato' || id == 'quadroBuscaServico' || id == 'quadroBuscaAgente'){
						IdStatus	=	document.formulario.IdStatus.value;
						if(IdStatus == 2 || IdStatus == 3){
							validar = false;
						}
					}
					break;
				case 'OrdemServico':
					if(document.formulario.IdOrdemServico.value != ''){
						validar = false;
					}else{
						if(id == 'quadroBuscaContrato' || id == 'quadroBuscaServico'){
							if(document.formulario.IdPessoa.value == '' && document.formulario.IdPessoaF.value == ''){
								document.formulario.IdPessoa.focus();
								mensagens(104);	
								validar = false;
							}
						}
					}
					break;
				case 'HelpDesk':
					if(id == 'quadroBuscaPessoa'){
						if(document.formulario.IdTicket.value != ''){
							validar = false;
						}
					}
					break;
				case 'LoteRepasse':
					if(id == 'quadroBuscaTerceiro' || id == 'quadroBuscaServico' || id == 'quadroBuscaPessoa' || id == 'quadroBuscaAgente'){
						IdStatus	=	document.formulario.IdStatus.value;
						if(document.formulario.IdLoteRepasse.value != "" && IdStatus > 1){
							validar = false;
						}
					}
					break;
				case 'ContaEventual':
					if(id == 'quadroBuscaPessoa'){
						IdStatus	=	document.formulario.IdStatus.value;
						if(IdStatus == '2' || IdStatus == '0'){
							validar = false;
						}
					}
					break;
				case 'Pessoa':
					if(id == 'quadroBuscaEstado'){
						for(i=0;i<document.formulario.length;i++){
							if(document.formulario[i].name.substr(0,7) == 'IdPais_'){
								var temp	=	document.formulario[i].name.split('_');	
								if(temp[1] == aux){
									var IdPais	=	document.formulario[i].value;
									
									if(IdPais == ''){
										validar = false;
									}
									break;
								}
							}
						}
					}
					if(id == 'quadroBuscaCidade'){
						for(i=0;i<document.formulario.length;i++){
							if(document.formulario[i].name.substr(0,7) == 'IdPais_'){
								var temp	=	document.formulario[i].name.split('_');	
								if(temp[1] == aux){
									var IdPais		=	document.formulario[i].value;
									var IdEstado	=	document.formulario[i+2].value;
									
									if(IdPais == '' || IdEstado==''){
										validar = false;
									}
									break;
								}
							}
						}
					}
					break;			
			}
		}	
		if(validar == true){	
			if(visivel == true){
				
/*				if(width == undefined || width == '' || width == 'null'){
					width = mouse.clientX;	
				}			
				if(top == undefined || top == '' || top == 'null'){	
					top = mouse.clientY;	
				}
*/				
				// Torna o quadro flutuante
				document.getElementById(id).style.position = 'absolute';
				
				// Visualiza Quadro
				document.getElementById(id).style.display = 'block';
				
				// Posição left da janela
				var tempWidth = document.body.offsetWidth-document.getElementById(id).offsetWidth;
				width = tempWidth/2;
				
				// Posição top da janela
				var tempHeight = document.documentElement.scrollTop;
				if(tempHeight == 0 && window.pageYOffset > 0){
					tempHeight = window.pageYOffset;
				}
				top = tempHeight+77;
				
				// Posiciona o quadro
				document.getElementById(id).style.left = width + 'px';
				document.getElementById(id).style.top = top + 'px';

				switch(document.formulario.Local.value){
					case 'HelpDesk':
						if(id == 'quadroBuscaPessoa'){						
							limpa_form_pessoa(); 
							busca_pessoa_lista(); 
							document.formularioPessoa.Nome.focus();
						}
						if(id == 'quadroBuscaContrato'){
							limpa_form_contrato(); 
							busca_contrato_lista(); 
							document.formularioContrato.Nome.focus();
						}
						break;
					case 'Contrato':
						if(id == 'quadroBuscaPessoa'){
							limpa_form_pessoa(); 
							busca_pessoa_lista(); 
							document.formularioPessoa.Nome.focus();
						}
						if(id == 'quadroBuscaServico'){
							limpa_form_servico();
							busca_servico_lista(); 
							document.formularioServico.DescricaoServico.focus();
						}
						break;
					case 'ProcessoFinanceiro':
						if(id == 'quadroBuscaPessoa'){
							limpa_form_pessoa(); 
							busca_pessoa_lista(); 
							document.formularioPessoa.Nome.focus();
						}
						if(id == 'quadroBuscaContrato'){
							limpa_form_contrato(); 
							busca_contrato_lista(); 
							document.formularioContrato.Nome.focus();
						}
						if(id == 'quadroBuscaServico'){
							 limpa_form_servico();
							 busca_servico_lista(); 
							 document.formularioServico.DescricaoServico.focus();
						}
						if(id == 'quadroBuscaAgente'){
							limpa_form_agente(); 
							busca_agente_lista(); 
							document.formularioAgente.Nome.focus();
						}
						break;
					case 'OrdemServico':
						if(id == 'quadroBuscaPessoa'){
							limpa_form_pessoa(); 
							busca_pessoa_lista(); 
							document.formularioPessoa.Nome.focus();
						}
						if(id == 'quadroBuscaContrato'){
							document.formularioContrato.DescricaoServico.value	=	'';
							busca_contrato_lista(); 
							document.formularioContrato.DescricaoServico.focus();
						}
						if(id == 'quadroBuscaServico'){
							limpa_form_servico();
							busca_servico_lista(); 
							document.formularioServico.DescricaoServico.focus();
						}
						break;
					case 'Servico':
						if(id == 'quadroBuscaServico'){
							document.BuscaServico.Local.value	=	"ServicoImportar";
							limpa_form_servico();
							busca_servico_lista(); 
							document.formularioServico.DescricaoServico.focus();
						}
						if(id == 'quadroBuscaServicoAgrupador'){
							document.BuscaServicoAgrupador.Local.value	=	"ServicoAgrupador";
							document.formularioServicoAgrupador.DescricaoServico.value=''; 
							valorCampoServicoAgrupador = ''; 
							busca_servico_agrupador_lista(); 
							document.formularioServicoAgrupador.DescricaoServico.focus();
						}
						if(id == 'quadroBuscaCidade'){
							 limpa_form_cidade(); 
							 busca_cidade_lista(); 
							 document.formularioCidade.IdPais.focus();
						}
						break;
					case 'LoteRepasse':
						if(id == 'quadroBuscaTerceiro'){
							limpa_form_terceiro(); 
							busca_terceiro_lista(); 
							document.formularioTerceiro.Nome.focus();
						}
						if(id == 'quadroBuscaServico'){
							limpa_form_servico();
							busca_servico_lista(); 
							document.formularioServico.DescricaoServico.focus();
						}
						if(id == 'quadroBuscaPessoa'){
							limpa_form_pessoa(); 
							busca_pessoa_lista(); 
							document.formularioPessoa.Nome.focus();
						}
						if(id == 'quadroBuscaAgente'){
							limpa_form_agente(); 
							busca_agente_lista(); 
							document.formularioAgente.Nome.focus();
						}
						break;
					case 'NotaFiscalEntrada':				
						if(id == 'quadroBuscaProduto'){
							document.BuscaProduto.pos.value = aux; 
							limpa_form_produto(); 
							busca_produto_lista(); 
							document.formularioProduto.DescricaoProduto.focus();
						}
						break;				
					case 'ContaEventual':
						if(id == 'quadroBuscaPessoa'){
							limpa_form_pessoa(); 
							busca_pessoa_lista(); 
							document.formularioPessoa.Nome.focus();
						}
						break;
					case 'ContaReceberAtivar':
						if(id == 'quadroBuscaContaReceber'){
							limpa_form_conta_receber(); 
							busca_conta_receber_lista(); 
							document.formularioContaReceber.Nome.focus();
						}
						break;
					case 'Pessoa':
						if(id == 'quadroBuscaPais'){
							document.BuscaPais.Endereco.value 	   = aux;
							document.formularioPais.NomePais.value = '';  
							valorCampoPais = ''; 
							busca_pais_lista(); 
							document.formularioPais.NomePais.focus();
						}
						if(id == 'quadroBuscaEstado'){
							document.BuscaEstado.Endereco.value 	   = aux;
							document.formularioEstado.NomeEstado.value = ''; 
							valorCampoEstado = ''; 
							busca_estado_lista(); 
							document.formularioEstado.NomeEstado.focus();
						}
						if(id == 'quadroBuscaCidade'){
							document.BuscaCidade.Endereco.value			= aux;
							document.formularioCidade.NomeCidade.value	= ''; 
							valorCampoCidade = ''; 
							busca_cidade_lista(); 
							document.formularioCidade.NomeCidade.focus();
						}					
						break;				
					case 'Etiqueta':					
						if(id == 'quadroBuscaServico'){					
							limpa_form_servico();
							busca_servico_lista(); 
							document.formularioServico.DescricaoServico.focus();							
						}
						break;								
				}			
			}else{
				document.getElementById(id).style.display = 'none';		
			}
		}
	}
	function trim(s){
	
		while((s.substring(0,1) == ' ') || (s.substring(0,1) == '\r')){
			s = s.substring(1,s.length);
		}
		while((s.substring(s.length-1,s.length) == ' ') || (s.substring(s.length-1,s.length) == '\r')){
			s = s.substring(0,s.length-1);
		}
		return s;
	}
	function removeDuplicado(a, s){
	    var p, i, j;
	    if(s) for(i = a.length; i > 1;){
	        if(a[--i] === a[i - 1]){
	            for(p = i - 1; p-- && a[i] === a[p];);
	            	i -= a.splice(p + 1, i - p - 1).length;
	        }
	    }
	    else for(i = a.length; i;){
	        for(p = --i; p > 0;)
	            if(a[i] === a[--p]){
	                for(j = p; --p && a[i] === a[p];);
	                i -= a.splice(p + 1, j - p).length;
	            }
	    }
	    return a;
	};
	
	function consulta_rapida(acao){
		switch(acao){
			case 'top':
				document.getElementById('filtroRapido').style.display		=	'none';
			//	document.getElementById('quadroConsultaRapida').style.display		=	'none';
				break;
			case 'bottom':
				document.getElementById('filtroRapido').style.display		=	'block';
			//	document.getElementById('quadroConsultaRapida').style.display		=	'block';
				break;
				
		}
	}	
	function suporte_administrativo_cda(){
		var xmlhttp = false;
		var nameNode, nameTextNode, url;	
		
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
	    url = "xml/atendimento.php?Origem=1&IdStatus=1";
				
		xmlhttp.open("GET", url,true);
		xmlhttp.onreadystatechange = function(){ 
		
			if(xmlhttp.readyState == 4){ 
				if(xmlhttp.status == 200){					
					if(xmlhttp.responseText != 'false'){
						if(xmlhttp.responseXML.getElementsByTagName("IdAtendimento").length <= 4){
							janelas('indexCliente.php',360,400,250,100,'');
						}else{
							alert('No momento todos nossos atendentes estão ocupados.');
						}
					}else{
						alert('No momento não há atendentes on-line.');
					}
				}
			} 
			return true;
		}
		xmlhttp.send(null);
	}	
	
	//QtdDias, d, m, ano (dias e mes sem zero ex. d=5)
	function AvancaDias(lnDias, ldDia, ldMes, ldAno)
	{

		var ndiasmes="";
		var ltDia, ltMes, ltAno
		ltDia = ldDia;
		ltMes = ldMes;
		ltAno = ldAno;

		//31 dias
		if ((ldMes==1)||(ldMes==3)||(ldMes==5)||(ldMes==7)||(ldMes==8)||(ldMes==10)||(ldMes==12)){
			ndiasmes=31
		}
		else if ((ldMes==4)||(ldMes==6)||(ldMes==9)||(ldMes==11)){	//30 dias
			ndiasmes=30
		}
		else{   //fevereiro
			//Calcula ano bissexto
			if (((ldAno % 4) == 0) && ((ldAno % 100) == 0))
				ndiasmes=29
			else if ((ldAno % 400) == 0)
				ndiasmes=29
			else
				ndiasmes=28
		}

		//incrementa dias
		if ((ldDia + lnDias)<=ndiasmes){
			ltDia= ldDia + lnDias
		}
		else{
			ltDia = parseInt((ldDia+lnDias)%ndiasmes)

			if (parseInt(ldMes +((ldDia+lnDias)/ndiasmes))<=12){
				ltMes = parseInt(ldMes +((ldDia+lnDias)/ndiasmes))
			}else{
				ltMes = parseInt((ldMes +((ldDia+lnDias)/ndiasmes)) %12)
				ltAno = parseInt(ldAno + ((ldMes + ((ldDia+lnDias)/ndiasmes))/12))
			}
		}
		
		if(ltDia < 10)	ltDia	=	'0'+ltDia;
		if(ltMes < 10)	ltMes	=	'0'+ltMes;

		var data = (ltDia + "/" + ltMes + "/" + ltAno)
		
		return data;
	}
	function atualizaSessao(nome,valor){
		var xmlhttp = false;
		var nameNode, nameTextNode, url;	
		
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
	    url = "../../rotinas/muda_parametro_consulta.php?nome="+nome+"&valor="+valor;
		
		xmlhttp.open("GET", url,true);
		xmlhttp.onreadystatechange = function(){ 
		
			if(xmlhttp.readyState == 4){ 
				if(xmlhttp.status == 200){
				//	alert(xmlhttp.responseText);
				}
			} 
			return true;
		}
		xmlhttp.send(null);
	}
	function addMonth(valor)
	{
		var dataFimVigencia = new Date();
		dataFimVigencia.setMonth(dataFimVigencia.getMonth() + parseInt(valor));//lembrando que o mes é um inteiro de 0-11
		
		var dia 	= dataFimVigencia.getDate();
    	var month 	= (dataFimVigencia.getMonth() + 1);
		var year 	= dataFimVigencia.getFullYear();
		
		if(dia < 10) 	dia   = '0'+dia;
		if(month < 10)  month = '0'+month;
		    	
    	return dia+"/"+month+"/"+year;
	}
	function como_chegar(){
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

		url = "xml/loja.php";
		xmlhttp.open("GET", url,true);

		xmlhttp.onreadystatechange = function(){ 

	
			if(xmlhttp.readyState == 4){ 
				if(xmlhttp.status == 200){
					if(xmlhttp.responseText != 'false'){
						nameNode = xmlhttp.responseXML.getElementsByTagName("Endereco")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var Endereco = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("Numero")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var Numero = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("CEP")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var CEP = nameTextNode.nodeValue;	
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("Bairro")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var Bairro = nameTextNode.nodeValue;	
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("Complemento")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var Complemento = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("NomeCidade")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var NomeCidade = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("SiglaEstado")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var SiglaEstado = nameTextNode.nodeValue;
					
						var Origem 	= Endereco+", "+Numero+", "+NomeCidade+", "+SiglaEstado+", "+CEP;
						var Destino	= document.formulario.Endereco.value+", "+document.formulario.Numero.value+", "+document.formulario.Cidade.value+", "+document.formulario.SiglaEstado.value+", "+document.formulario.CEP.value;	
						
						como_chegar_direciona(Origem, Destino);
					}
				}
			}
			return true;
		}
		xmlhttp.send(null);
	}
	function como_chegar_direciona(Origem,Destino){
		window.location.replace("como_chegar.php?Origem="+Origem+"&Destino="+Destino);
	}
	function removeAcento (text){
		text = text.replace(new RegExp('[ÁÀÂÃ]','gi'), 'a');
		text = text.replace(new RegExp('[áâã]','gi'), 'a');
        text = text.replace(new RegExp('[ÉÈÊ]','gi'), 'e');
        text = text.replace(new RegExp('[éèê]','gi'), 'e');
        text = text.replace(new RegExp('[ÍÌÎ]','gi'), 'i');
        text = text.replace(new RegExp('[íìî]','gi'), 'i');
        text = text.replace(new RegExp('[ÓÒÔÕ]','gi'), 'o');
        text = text.replace(new RegExp('[óòôõ]','gi'), 'o');
        text = text.replace(new RegExp('[ÚÙÛ]','gi'), 'u');
        text = text.replace(new RegExp('[úùû]','gi'), 'u');
        text = text.replace(new RegExp('[Ç]','gi'), 'c');
        text = text.replace(new RegExp('[ç]','gi'), 'c');
        
        return text;                           
    }
    
    var extenso = new Array();

	extenso[0] = {numero:1,escrita:'um'};
	extenso[1] = {numero:2,escrita:'dois'};
	extenso[2] = {numero:3,escrita:'tres'};
	extenso[3] = {numero:4,escrita:'quatro'};
	extenso[4] = {numero:5,escrita:'cinco'};
	extenso[5] = {numero:6,escrita:'seis'};
	extenso[6] = {numero:7,escrita:'sete'};
	extenso[7] = {numero:8,escrita:'oito'};
	extenso[8] = {numero:9,escrita:'nove'};
	extenso[9] = {numero:10,escrita:'dez'};
	extenso[10]= {numero:11,escrita:'onze'};
	extenso[11]= {numero:12,escrita:'doze'};
	extenso[12]= {numero:13,escrita:'treze'};
	extenso[13]= {numero:14,escrita:'quatorze'};
	extenso[14]= {numero:15,escrita:'quinze'};
	extenso[15]= {numero:16,escrita:'dezeseis'};
	extenso[16]= {numero:17,escrita:'dezesete'};
	extenso[17]= {numero:18,escrita:'dezoito'};
	extenso[18]= {numero:19,escrita:'dezenove'};
	extenso[19]= {numero:20,escrita:'vinte'};
	extenso[20]= {numero:30,escrita:'trinta'};
	extenso[21]= {numero:40,escrita:'quarenta'};
	extenso[22]= {numero:50,escrita:'cinquenta'};
	extenso[23]= {numero:60,escrita:'secenta'};
	extenso[24]= {numero:70,escrita:'setenta'};
	extenso[25]= {numero:80,escrita:'oitenta'};
	extenso[26]= {numero:90,escrita:'noventa'};
	extenso[27]= {numero:100,escrita:'cem'};
	extenso[28]= {numero:200,escrita:'duzentos'};
	extenso[29]= {numero:300,escrita:'trezentos'};
	extenso[30]= {numero:400,escrita:'quatrocentos'};
	extenso[31]= {numero:500,escrita:'quinhentos'};
	extenso[32]= {numero:600,escrita:'seiscentos'};
	extenso[33]= {numero:700,escrita:'setecentos'};
	extenso[34]= {numero:800,escrita:'oitocentos'};
	extenso[35]= {numero:900,escrita:'novecentos'};
	
	function expoent(v){
	    var mult = 1;
	    
	    if ( v == 3 ) return mult;
	        
	    for ( v; v > 0; v-- ) mult = mult*10;
	        
	    return mult;
	}
	
	function formata(v,a){
	    var porExtenso;
	    var e = " e ";    
	    
	    switch( v.length ) {
	        case 1: porExtenso = v[0]; break;
	        case 2: if ( eval(a) < 21 ) { porExtenso = v[0]; } else { porExtenso = v[0]+e+v[1]; } break;
	        case 3: porExtenso = v[0]+e+v[1]+e+v[2]; break;
	        case 4: porExtenso = v[0]+" mil "+v[1]+e+v[2]+e+v[3]; break;
	        default:porExtenso = ""; break;        
	    }
	    
	    if ( porExtenso[porExtenso.length-1] == " " )
	        porExtenso = porExtenso.substr(0,porExtenso.length -2);
	        
	    return porExtenso;
	}
	
	function conta(valor) {
	    var valores = new Array();
	    
	    unid = valor.length - 1;
	    for( var expo = 0, unid; unid >= 0; unid--, expo++ ) {
	        if ( 1 == valor[valor.length-2] && valor == 1) {
	            valores[unid] = extenso[valor-1].escrita;
	            valores[unid+1] = "";
	        } else {
	            for ( l=0; l <= 35; l++ ) {
	                if ( extenso[l].numero == eval(valor) ) {
	                    valores[unid] = extenso[l].escrita;
	                }
	            }
	        }
	    }
	    
	    return formata(valores,valor);    
	}
	
	function dataConv(data, formatoEntrada, formatoSaida){
		switch(formatoEntrada){
			case "d/m/Y":
				dia = data.substr(0,2);
				mes = data.substr(3,2);
				ano = data.substr(6,4);
				break;
		}
		switch(formatoSaida){
			case "Ymd":
				data = ano+mes+dia;
				break;
			default:
				data = "";
				break;	
			
		}
		return data;
	}
