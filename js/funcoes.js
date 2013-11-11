	var menu_form, menu_lista, teclas_atalho;
		
	function OnOff(Local,Emissor){
		switch (Emissor){
			case 'menu':
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
			campo.style.outline = "1px solid #F1CA7F";
			campo.style.borderColor = "#C49D52";
			campo.style.outlineOffset = "0px";
			
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
			campo.style.outline = "none"; 
			campo.style.borderColor = "#A4A4A4";
			campo.style.backgroundColor = "#FFFFFF";
		}	
	}
	function mascara(campo,event,tipo,acesso,tipo_valor){
		if(campo.disabled || campo.readOnly) 
			return;
		
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
						if(event.preventDefault){ //standart browsers
							event.preventDefault();
						} else{ // internet explorer
							event.returnValue = false;
						}
					}
				}
				if(((nTecla < (48 + numMenor) && nTecla != 46) || nTecla > (48 + numMaior)) && nTecla != 9){
					if(event.preventDefault){ //standart browsers
						event.preventDefault();
					} else{ // internet explorer
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
			
			if((nTecla < (48 + numMenor) || nTecla > (48 + numMaior) || tamMax == tam) && nTecla != 9){
				if(event.preventDefault){ //standart browsers
					event.preventDefault();
				} else{ // internet explorer
					event.returnValue = false;
				}
			}else{				
				 mascara_cfop(campo,'onkeypress');
			}
		}		
		if(tipo	==	"int"){
			if(event.ctrlKey) 
				return;
			
			numMenor=0;
			numMaior=9;
			var start, end;
			/* Pega a possiÁ„o do cursor no campo */
			if(document.selection){ /*IE*/
				var bm = document.selection.createRange().getBookmark();
				var sel = campo.createTextRange();
				sel.moveToBookmark(bm);
				
				var sleft = campo.createTextRange();
				sleft.collapse(true);
				sleft.setEndPoint("EndToStart", sel);
				start = sleft.text.length
				end = sleft.text.length + sel.text.length;
			} else{
				start = campo.selectionStart;
				end = campo.selectionEnd;
			}
			
			if(tipo_valor != undefined){
				if(tipo_valor.substring(0, 3).toLowerCase() == "neg" && nTecla == 45 && parseInt(campo.value) != 0){
					campo.value = "-" + campo.value.replace(/-/g, '');
				} else if(nTecla == 48 && campo.value == "-"){
					campo.value = '';
				}
			}
			
			if (nTecla < (48 + numMenor) || nTecla > (48 + numMaior) || (nTecla == 48 && start == 0 && end == 0 && campo.value.length > 0)){
				if (event.preventDefault){ //standart browsers
					if(nTecla!=9){
						event.preventDefault();
					}
				}else{ // internet explorer
					event.returnValue = false;
				}
			} else{
				if(start != end){
					var textStart = campo.value.substring(0, start); 
					var textEnd = campo.value.substring(end, campo.value.length); 
					campo.value = textStart + textEnd;
				}
				
				var text = campo.value + String.fromCharCode(nTecla);
				
				if(text.length == 2 && ((nTecla == 48 && parseInt(campo.value) == 0) || parseInt(campo.value) == 0)){
					campo.value = '';
				}
				
				if(campo.value.length > 1){
					campo.value = Math.round(campo.value);					/* Aredondar o valor */
				}
				/* Direcionar o cursor para o local desejado */
				if(campo.createTextRange){ /*IE*/
					var range = campo.createTextRange();
					range.collapse(true);
					range.moveEnd('character', start);
					range.moveStart('character', start);
					range.select();
				} else{
					campo.setSelectionRange(start, start);
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
			
			if((nTecla < (48 + numMenor) || nTecla > (48 + numMaior) || tamMax == tam) && nTecla != 9){
				if(event.preventDefault){ //standart browsers
					event.preventDefault();
				} else{ // internet explorer
					event.returnValue = false;
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
			
			if((nTecla < (48 + numMenor) || nTecla > (48 + numMaior) || tamMax == tam) && nTecla != 9){
				if(event.preventDefault){ //standart browsers
					event.preventDefault();
				} else{ // internet explorer
					event.returnValue = false;
				}
			}else{
				if(nTecla != 9){
					mascara_double(campo,'onkeypress');
				 }
			}
		}
		if(tipo	==	"date"){
			if(campo.value.length>=10){
				return false;
			}
			numMenor=0;
			numMaior=9;
			
			if((nTecla < (48 + numMenor) || nTecla > (48 + numMaior)) && nTecla!=9){
/*				if(nTecla!=9){
					event.returnValue = false
				}
*/				if(event.preventDefault){ //standart browsers
					event.preventDefault();
				} else{ // internet explorer
					event.returnValue = false;
				}
				//48 a 57 ascII - 0 a 9
			}
			if(campo.value.length==2 || campo.value.length==5){
				campo.value = campo.value + "/";
			}
		}
		if(tipo	== "dateHora"){
			if(campo.value.length>=19){
				return false;
			}
			
			numMenor=0;
			numMaior=9;
			
			if((nTecla < (48 + numMenor) || nTecla > (48 + numMaior)) && nTecla != 9){
				if(event.preventDefault){ //standart browsers
					event.preventDefault();
				}else{ // internet explorer
					event.returnValue = false;
				}
				//48 a 57 ascII - 0 a 9
			}
			
			if(campo.value.length==2 || campo.value.length==5){
				campo.value += "/";
			}
			
			if(campo.value.length==10){
				campo.value += " ";
			}
			
			if(campo.value.length==13 || campo.value.length==16){
				campo.value += ":";
			}
		}
		if(tipo	==	"cnpj"){
			if(campo.value.length>=18){
				return false;
			}
			numMenor=0;
			numMaior=9;
			if((nTecla < (48 + numMenor) || nTecla > (48 + numMaior)) && nTecla != 9){
				if(event.preventDefault){ //standart browsers
					event.preventDefault();
				} else{ // internet explorer
					event.returnValue = false;
				}
				//48 a 57 ascII - 0 a 9
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
			if((nTecla < (48 + numMenor) || nTecla > (48 + numMaior)) && nTecla != 9){
				if(event.preventDefault){ //standart browsers
					event.preventDefault();
				} else{ // internet explorer
					event.returnValue = false;
				}
				//48 a 57 ascII - 0 a 9
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
			if((nTecla < (45 + numMenor) || nTecla > (48 + numMaior)) && nTecla != 9){
				if(event.preventDefault){ //standart browsers
					event.preventDefault();
				} else{ // internet explorer
					event.returnValue = false;
				}
				//48 a 57 ascII - 0 a 9
			}
		}
		if(tipo	==	"fone"){
			if(campo.value.replace(/[\(\)-]/g, '').substring(0, 2) == "11" && campo.value.replace(/[\(\)-]/g, '').substring(2, 3) == "9"){
				if(campo.value.length > 14){
					return false;
				}
			} else {
				if(campo.value.length > 13){
					return false;
				}
			}
			numMenor=0;
			numMaior=9;
			// 48 A 57 ASCII - 0 A 9
			if((nTecla < (48 + numMenor) || nTecla > (48 + numMaior)) && nTecla != 9){
				if(event.preventDefault){ // standart browsers
					event.preventDefault();
				} else{ // internet explorer
					event.returnValue = false;
				}
			} else {
				if((String.fromCharCode(nTecla) != "0" && campo.value == "") || (campo.value.substring(0, 1) != "0" && campo.value != "")){
					// MASCARA PARA FONE COM DDD INFERIOR A 10, FORMATO DE MONTAGEM -> {(11)9XXXX-XXXX, (XX)XXXX-XXXX}
					if(campo.value.length==0){
						campo.value = campo.value+"(";
					} else if(campo.value.length==3){
						campo.value = campo.value+")";
					}
					if(campo.value.replace(/[\(\)-]/g, '').substring(0, 2) == "11" && campo.value.replace(/[\(\)-]/g, '').substring(2, 3) == "9"){
						if(campo.value.length==9){
							campo.value = campo.value+"-";
						}
					} else {
						if(campo.value.length==8){
							campo.value = campo.value+"-";
						}
					}
				} else {
					// MASCARA PARA FONE 0800, FORMATO DE MONTAGEM -> {0XXX-XXX-XXXX}
					if(campo.value.length==4){
						campo.value = campo.value+"-";
					} else if(campo.value.length==8){
						campo.value = campo.value+"-";
					}
				}
			}
			// ADICIONAR ESPA«O AP”S O NUMERO DE FONE
			if(campo.value.replace(/[\(\)-]/g, '').substring(0, 2) == "11" && campo.value.replace(/[\(\)-]/g, '').substring(2, 3) == "9"){
				if(campo.value.length == 14){
					campo.value = campo.value+" "+String.fromCharCode(nTecla);
				}
			} else {
				if(campo.value.length == 13){
					campo.value = campo.value+" "+String.fromCharCode(nTecla);
				}
			}
		}
		if(tipo	==	"cep"){
			if(campo.value.length>=9){
				return false;
			}
			numMenor=0;
			numMaior=9;
			if((nTecla < (48 + numMenor) || nTecla > (48 + numMaior)) && nTecla != 9){
				if(event.preventDefault){ //standart browsers
					event.preventDefault();
				} else{ // internet explorer
					event.returnValue = false;
				}
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
			if((nTecla < (48 + numMenor) || nTecla > (48 + numMaior)) && nTecla != 9){
				if(event.preventDefault){ //standart browsers
					event.preventDefault();
				} else{ // internet explorer
					event.returnValue = false;
				}
				//48 a 57 ascII - 0 a 9
			}
			if(campo.value.length==2){
				campo.value = campo.value + "/";
			}
		}
		if(tipo	==	"semestral"){
			if(campo.value.length>7){
				return false;
			}
			numMenor=0;
			numMaior=9;
			if((nTecla < (48 + numMenor) || nTecla > (48 + numMaior)) && nTecla != 9){
				if(event.preventDefault){ //standart browsers
					event.preventDefault();
				} else{ // internet explorer
					event.returnValue = false;
				}
				//48 a 57 ascII - 0 a 9
			}
			if(campo.value.length==4){
				campo.value = campo.value + "/";
			}
		}
		if(tipo	==	"hora"){
			if(campo.value.length>7){
				return false;
			}
			numMenor=0;
			numMaior=9;
			if((nTecla < (48 + numMenor) || nTecla > (48 + numMaior)) && nTecla != 9){
				if(event.preventDefault){ //standart browsers
					event.preventDefault();
				} else{ // internet explorer
					event.returnValue = false;
				}
				//48 a 57 ascII - 0 a 9
			}
			if(campo.value.length==2){
				campo.value = campo.value + ":";
			}
		}
		if(tipo	==	"horaMinSeg"){
			if(campo.value.length < 8){
				numMenor=0;
				numMaior=9;
				if((nTecla < (48 + numMenor) || nTecla > (48 + numMaior)) && nTecla != 9){
					if(event.preventDefault){ //standart browsers
						event.preventDefault();
					} else{ // internet explorer
						event.returnValue = false;
					}
					//48 a 57 ascII - 0 a 9
				}
				if(campo.value.length == 2 || campo.value.length == 5){
					campo.value = campo.value + ":";
				}
			}else{
				if(event.preventDefault){ //standart browsers
					event.preventDefault();
				} else{ // internet explorer
					event.returnValue = false;
				}
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
		if(tipo == "intNegativo"){
			numMenor=0;
			numMaior=9;
			if((nTecla < (48 + numMenor) || nTecla > (48 + numMaior)) && nTecla != 9){
				if(event.preventDefault){ //standart browsers
					event.preventDefault();
				} else{ // internet explorer
					event.returnValue = false;
				}
			} else{
				if(parseInt(campo.value)){
					campo.value = parseInt(campo.value);
				}
				
				if(campo.value > 0){
					campo.value = campo.value/-1;
				}
				
				if((campo.value == "" && nTecla != 48) || (campo.value == 0 && nTecla != 48)){
					campo.value = "-";
				} else{
					if(campo.value == 0 || campo.value == "-" && nTecla==48){
						campo.value = "";
					}
				}
			}
		}
		if(tipo == "charVal"){
			var start, end;
			/* Pega a possiÁ„o do cursor no campo */
			if(document.selection){ /*IE*/
				var bm = document.selection.createRange().getBookmark();
				var sel = campo.createTextRange();
				sel.moveToBookmark(bm);
				
				var sleft = campo.createTextRange();
				sleft.collapse(true);
				sleft.setEndPoint("EndToStart", sel);
				start = sleft.text.length
				end = sleft.text.length + sel.text.length;
			} else{
				start = campo.selectionStart;
				end = campo.selectionEnd;
			}
			
			campo.value = campo.value.replace(/[<>{}]/g,'');
			
			if(start != end){
				var textStart = campo.value.substring(0, start); 
				var textEnd = campo.value.substring(end, campo.value.length); 
				campo.value = textStart + textEnd;
			}
			/* Direcionar o cursor para o local desejado */
			if(campo.createTextRange){ /*IE*/
				var range = campo.createTextRange();
				range.collapse(true);
				range.moveEnd('character', start);
				range.moveStart('character', start);
				range.select();
			} else{
				campo.setSelectionRange(start, start);
			}
			
			if(nTecla == 38 || nTecla == 60 || nTecla == 62 || nTecla == 92 || nTecla == 123 || nTecla == 125){
				if(event.preventDefault){
					event.preventDefault();
				}else{
					event.returnValue = false;
				}
			}
		}
		if(tipo == "usuario"){
			
			if(nTecla == 32 || ( (nTecla >= 65 && nTecla <= 90) || (nTecla >= 192 && nTecla <= 196) || (nTecla >= 199 && nTecla <= 207) || (nTecla >= 210 && nTecla <= 214) || (nTecla >= 217 && nTecla <= 220) )){
				if(event.preventDefault){
					event.preventDefault();
				}else{
					event.returnValue = false;
				}
			}
		}
		if(tipo == "numeroEndereco"){
			
			if(nTecla != 9 && (nTecla < 48 || nTecla > 57) && (nTecla < 65 || nTecla > 90) && (nTecla < 97 || nTecla > 122)){
				if(event.preventDefault){
					event.preventDefault();
				}else{
					event.returnValue = false;
				}
			}
		}
		if(tipo == 'filtroCaractere'){				
			var digito = String.fromCharCode(nTecla);
			var caracteresInvalidos = '·ÈÌÛ˙‡ËÏÚ˘‰ÎÔˆ¸¡…Õ”⁄¿»Ã“ŸƒÀœ÷‹\/\\^~`¥®|∞∫@<>:;=?Á«ß™{}[] ';
			var caracteresValidos = '-_.'
			if(caracteresInvalidos.indexOf(digito)>-1){
				return false;
			}
			if(caracteresValidos.indexOf(digito)>-1){
				return true;
			}
			if(nTecla>47 || nTecla == 8 || nTecla == 0 );
			else return false;
		}
		if(tipo == 'filtroCaractereEmail'){
			var digito = String.fromCharCode(nTecla);
			var caracteresInvalidos = '&#,+·ÈÌÛ˙‡ËÏÚ˘‰ÎÔˆ¸¡…Õ”⁄¿»Ã“ŸƒÀœ÷‹\/\\^~`¥®|∞∫<>()%$!"\'*:=?Á«ß™{}[] ';
			var caracteresValidos = '@-_.';
			if(caracteresInvalidos.indexOf(digito)>-1){
				return false;
			}
				if(caracteresValidos.indexOf(digito)>-1){
				return true;
			}			
		}
		if(tipo== "cor"){
			var digito = String.fromCharCode(nTecla);
			var caracteresInvalidos = '·ÈÌÛ˙‡ËÏÚ˘‰ÎÔˆ¸¡…Õ”⁄¿»Ã“ŸƒÀœ÷‹\/\\^~`¥®|∞∫@<>:;=?Á«ß™{}[] ';
			var caracteresValidos = '-_.'
			if(caracteresInvalidos.indexOf(digito)>-1){
				return false;
			}
			if(caracteresValidos.indexOf(digito)>-1){
				return true;				
			}
			if (campo.value.length == 0 || campo.value.length == 1){
	       	   	campo.value = '#'+campo.value;
	       	}
			if(nTecla>47 || nTecla == 8 || nTecla == 0 );
			else return false;
	        
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
			str = str.replace(/[\.,]/g,"");	// Tira os pontos e as vÌrgulas
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
			default:/*
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
				campo.value = str;*/
				campo.value = campo.value.substring(0, campo.value.length-1) + ',' + campo.value.substring(campo.value.length-1);
				break;					
		}
	}
	function mascara_float(campo, evento){
		if(campo.readOnly){
			return;
		}
		
		var nTecla = '';
		
		if(document.all) { // Internet Explorer
			nTecla = evento.keyCode;
		} else if(document.layers) { // Nestcape
			nTecla = evento.which;
		} else {
			nTecla = evento.which;
		}
		
		if(nTecla != 0 && nTecla != 8){
			if(evento.preventDefault){
				evento.preventDefault();
			}else{
				evento.returnValue = false;
			}
		}
		
		var tamMax = campo.maxLength;
		var tam = campo.value.length;
		var novoChar = String.fromCharCode(nTecla);
		var str = campo.value;
		var pos = 0;
		
		if((nTecla > 47 && nTecla < 58 && tam < tamMax)){
			if(novoChar > -1){
				str += novoChar;
			}
			
			if(evento == ''){
				pos = 1;
			}
			
			if(str != ''){
				str = str.replace(",","");	// Tira as vÌrgulas
				str = str.replace(".","");	// Tira os pontos
				str = str*1;	// Converte para inteiro
				campo.value = str;
			}
			
			tam = campo.value.length;
//			alert(tam);
			
			switch (tam){
				case 1:
					campo.value = "0,000" + campo.value;
					break;
				case 2:
					campo.value = "0,00" + campo.value;
					break;
				case 3:
					campo.value = "0,0" + campo.value;
					break;
				case 4:
					campo.value = "0," + campo.value;
					break;
				default:/*
					var decimal 	= campo.value.substr(tam-4-pos,4+pos);
					var inteiro 	= campo.value.substr(0,tam-4-pos);
					var inteiroTam 	= inteiro.length;
					var inteiros 	= new Array();
					var i			= 0;
					
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
					
					for(var ii=0;ii<i;ii++){
						if(inteiros[ii] != '' && inteiros[ii] != undefined){
							if(inteiro != ''){
								inteiro = inteiro + '.';
							}
							
							inteiro = inteiro + inteiros[ii];
						}
					}
					
					if(inteiro == ''){
						inteiro = 0;
					}
					
					str = inteiro + ',' + decimal;
					campo.value = str;*/
					campo.value = campo.value.substring(0, campo.value.length-4) + ',' + campo.value.substring(campo.value.length-4);
					break;					
			}
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
			str = str.replace(/[,\.]/g,"");	// Tira as vÌrgulas e pontos
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
				campo.value = campo.value.substring(0, campo.value.length-4) + '.' + campo.value.substring(campo.value.length-4);
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
						
						
						if(Local != 'Servico' && Local != "AdicionarLoteRepasse" && Local != "AdicionarMalaDireta"){
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
								
								 if(Local == "AdicionarLoteRepasse"){
									if(document.formulario.IdStatus.value == 1 || document.formulario.IdStatus.value == ''){
										c4.innerHTML = "<img src='../../img/estrutura_sistema/ico_del.gif' alt='Excluir?' onClick=\"remover_filtro_cidade("+IdPais+","+IdEstado+","+IdCidade+")\"></tr>";
									}else{
										c4.innerHTML = "<img src='../../img/estrutura_sistema/ico_del_c.gif' alt='Excluir?'\">";
									}
								} else {
									if(document.formulario.IdStatus.value == 1){
										c4.innerHTML = "<img src='../../img/estrutura_sistema/ico_del.gif' alt='Excluir?' onClick=\"remover_filtro_cidade("+IdPais+","+IdEstado+","+IdCidade+")\"></tr>";
									}else{
										c4.innerHTML = "<img src='../../img/estrutura_sistema/ico_del_c.gif' alt='Excluir?'\">";
									}
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
								
								if(Local == 'AdicionarLoteRepasse'){
									if(document.formulario.IdLoteRepasse.value == ''){
										document.getElementById('totaltabelaCidade').innerHTML	=	'Total: '+(ii+1);
									}
									//limpar os campos depois de adiciondos ‡ lista
									document.formulario.IdPais.value    = "";
									document.formulario.IdEstado.value	= "";
							        document.formulario.IdCidade.value	= "";
							        document.formulario.Pais.value 	    = "";
									document.formulario.Estado.value 	= "";
									document.formulario.Cidade.value 	= "";
								}
								
								if(Local == "AdicionarMalaDireta"){
									//limpar os campos depois de adiciondos ‡ lista
									document.formulario.IdPais.value    = "";
									document.formulario.IdEstado.value	= "";
							        document.formulario.IdCidade.value	= "";
							        document.formulario.Pais.value 	    = "";
									document.formulario.Estado.value 	= "";
									document.formulario.Cidade.value 	= "";
									
									document.getElementById('totaltabelaCidade').innerHTML = 'Total: '+(ii+1);
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
				case 13: // Entrer -> PrÛximo campo
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
	function menu_hor_marcar(){
		var element_bx = document.getElementById("menu_ar");
		
		if (element_bx != undefined && element_bx != null) { // Verificar se o barra horizontal existe
			var element_bx = element_bx.getElementsByTagName("li"); // Pegar a caixa com os menus horizontal
			
			for (var bx_i = 0; bx_i < element_bx.length; bx_i++) {
				var element_mn = element_bx[bx_i].getElementsByTagName("a"); // Pegar os menus na caixa horizontal
				var tm_mn = element_mn.length;
				
				for (var mn_i = 0; mn_i < tm_mn; mn_i++) {
					var url_fr = document.location.href.replace(/(\?[^\?]*$)/i, ""); // Limpar URI (retira as vari·veis passada)
					var url_mn = element_mn[mn_i].href;
					var val_mn = element_mn[mn_i].innerHTML;
					
					if (url_fr == url_mn && val_mn != "") { // Verificar sÈ a URI foi carregada
						element_bx[bx_i].style.backgroundColor = "#c10000";
						element_bx[bx_i].style.color = "#ffffff";
						
						for (mn_i = 0; mn_i < tm_mn; mn_i++) {
							element_mn[mn_i].style.color = "#ffffff"; // Muda a cor do texto dos link's
						}
						
						break;
					}
				}
			}
		}
	}
	function ativaNome(nome,print){
		if(window.parent.cabecalho != undefined){
			if(window.parent.menu != undefined){
				window.parent.menu.document.formulario.codigo_barra.value = '';				
			}
			
			if(window.parent.cabecalho.document.getElementById('cp_modulo_atual') != null) {
				window.parent.cabecalho.document.getElementById('cp_modulo_atual').innerHTML = nome;
			}
			switch(nome){
				case "Contrato/Datas":
					var imprimir_filtros = addFiltrosImpressao();
					break;
				default:
					imprimir_filtros = "";
					break;
			}
			if(document.getElementById("tableListar") != undefined && (print || print == undefined)) {
				document.getElementById("conteudo").innerHTML = "<h1 class='ocultar'>" + nome + "<hr /></h1>"+imprimir_filtros+document.getElementById("conteudo").innerHTML + "<table style='width:100%;' cellpadding='0' cellspacing='0'><tr><td class='find' /><td class='campo' style='padding-top:8px; padding-bottom:8px; text-align:right;'><input type='button' style='cursor: pointer' class='impress' name='bt_imprimir' value='Imprimir' onClick='imprimir_frame();' /></td><td class='find' /></tr></table>";
				
				if(document.getElementById("helpText") != undefined) {
					var element = document.getElementsByTagName("table");
					element[element.length-2].style.position  = "absolute";
				}
			}
			
			menu_hor_marcar();
		}else{
			window.location = 'index.php?url=' + window.location;
		}
	}
	function ativaNomeHelpDesk(nome){
		if(window.parent.cabecalho != undefined){
			if(window.parent.cabecalho.document.getElementById('cp_modulo_atual') != null) {
				window.parent.cabecalho.document.getElementById('cp_modulo_atual').innerHTML = nome;
			}
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
		if(date == ''){
			return '';
		}
		
		var year 	= date.substring(0,4);
		var month 	= date.substring(5,7);
		var day 	= date.substring(8,10);
		var end 	= date.substring(11,date.length);
		
		var date = (day != '' ? day + "/" + month + "/" + year : month + "/" + year);
		
		if(end != ''){
			date = date + " " + end;
		}
		
		return date;
	}
/*	function tableMultColor(Id, Cor){
		var i,temp;
		alert(Cor);
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
	}*/
	function tableMultColor(Id, Cor2){
		/* Cors branca: rgba(0, 0, 0, 0) -> rgb(255, 255, 255) -> #ffffff */
		var Cor1 = getStyle(document.getElementById(Id).rows[1], "background-color");
		var tam = document.getElementById(Id).rows.length;
		
		if(Cor1.search("rgb") == 0){
			Cor1 = "rgb(255, 255, 255)";
		} else{
			Cor1 = "#FFFFFF";
		}
		
		if(Cor2 == '' || Cor2 == undefined){
			if(tam > 3){
				Cor2 = getStyle(document.getElementById(Id).rows[2], "background-color")
				Cor2 = Cor2.replace("rgba(0, 0, 0, 0)", "rgb(255, 255, 255)");
				Cor2 = Cor2.replace("transparent", "#FFFFFF");
			} else{
				Cor2 = "#E2E7ED";
			}
		}
		
		if(Cor1.toLowerCase() == Cor2.toLowerCase()){
			if(tam > 4){
				Cor2 = getStyle(document.getElementById(Id).rows[3], "background-color");
			} else{
				Cor2 = "#E2E7ED";
			}
		}
		
		if(Id == "quadroAvisoOrdem" || Id == "quadroAvisoOrdemUsuario"){
			var temp = Cor1;
			Cor1 = Cor2;
			Cor2 = Temp;
		}
		
		for(var cont = 1; cont < (document.getElementById(Id).rows.length-1); cont++){
			if(cont%2){
				document.getElementById(Id).rows[cont].style.backgroundColor = Cor1;
			} else{
				document.getElementById(Id).rows[cont].style.backgroundColor = Cor2;
			}
		}
	}
	
	var tempCor = "#FFFFFF";
	
	function destacaRegistro(campo,acao){
		if(acao == true){
			tempCor = getStyle(campo, "background-color");
			campo.style.backgroundColor = '#A0C4EA';
		} else{
			campo.style.backgroundColor = tempCor;
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
		
		if(campo == ''){
			return "";
		}
		
		if(casas == '' || casas == undefined)
			casas=2;
		
		var cont = campo.split('.');
		
		if(cont[1] != undefined){
			cont = cont[1].length;
			
			if(cont < casas){
				while(cont < casas){
					campo = campo + "0";
					cont++;
				}
			}
		} else{
			cont = 1
			
			while(cont <= casas){
				if(cont == 1){
					campo = campo + ".0";
				} else{
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
		if(document.getElementById(IdDestino) == null || (Parm == "" && Valor == "")){
			return;
		}
		
		var url = document.getElementById(IdDestino).href;
		
		if(Valor == ""){
			if(Parm != ""){
				var ArrayTemp1	= url.split(Parm+"=")
				
				if(ArrayTemp1[1] != undefined){
					var ArrayTemp2	= ArrayTemp1[1].split("&");
					
					if(ArrayTemp2[1] != undefined){
						ArrayTemp2[1] = "&"+ArrayTemp2[1];
					}else{
						ArrayTemp2[1] = "";
					}
				
					url = ArrayTemp1[0]+Parm+'='+ArrayTemp2[1];
				}
			}else{
				var ArrayTemp 	= url.split("?");
				url				= ArrayTemp[0];
			}
			
			document.getElementById(IdDestino).href	=	url;
		}else{
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
		
		// verifica se ano È v·lido
		if (ano < 1000 ) { 
			return false;
		}
	}
	function addOption(objSelect,newName,newValor){
		var newOption; 
		var newDialog;
		newOption = new Option (newName,newValor, true, true);
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
		estranha = "·ÈÌÛ˙‡ËÏÚ˘‚ÍÓÙ˚‰ÎÔˆ¸„ı@#$%^&*()_+=-~` Á"; 
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
	function numDiasMes(ano, mes){//retorna o numero de dias no mÍs.
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
	
	// datas no formato ano/mÍs/dia
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
				case 'AgruparContaReceber':
					if(id == 'quadroBuscaPessoa'){
						if(document.formulario.IdContaReceberAgrupador.value != ""){
							validar = false;
						}
					}
					if(id == 'quadroBuscaContaReceber'){
						if(document.formulario.IdContaReceberAgrupador.value != "" || document.formulario.IdPessoa.value == ""){
							validar = false;
						}
					}
					break;
				case 'ProcessoFinanceiro':
					if(id == 'quadroBuscaPessoa' || id == 'quadroBuscaPais' || id == 'quadroBuscaEstado' || id == 'quadroBuscaCidade' || id == 'quadroBuscaContrato' || id == 'quadroBuscaServico' || id == 'quadroBuscaAgente' || id == 'quadroBuscaGrupoPessoa'){
						IdStatus	=	document.formulario.IdStatus.value;
						if(IdStatus == 2 || IdStatus == 3){
							validar = false;
						}
					}
					break;
				case 'MalaDireta':
					if(id == 'quadroBuscaPessoa' || id == 'quadroBuscaGrupoPessoa' || id == 'quadroBuscaServico' || id == 'quadroBuscaContrato' || id == 'quadroBuscaProcessoFinanceiro' || id == 'quadroBuscaMalaDiretaEnviada' || id == 'quadroBuscaPais' || id == 'quadroBuscaEstado' || id == 'quadroBuscaCidade'){
						if(Number(document.formulario.IdStatus.value) != 1){
							validar = false;
						}
					}
					break;
				case 'OrdemServico':
					if(id == "quadroBuscaAgente" && ((document.formulario.IdStatus.value > 99 && document.formulario.IdStatus.value < 500) || document.formulario.IdStatus.value > 599)){
						validar = true;
					} else{
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
					}
					break;
				case 'Protocolo':
					if(id == 'quadroBuscaContrato' || id == 'quadroBuscaContaEventual' || id == 'quadroBuscaOrdemServico' || id == 'quadroBuscaContaReceber'){
						if(document.formulario.IdPessoa.value == '' && document.formulario.IdPessoaF.value == ''){
							document.formulario.IdPessoa.focus();
							mensagens(104);	
							validar = false;
						} else if(id == 'quadroBuscaContaEventual' && document.formulario.IdContaEventual.readOnly || id == 'quadroBuscaOrdemServico' && document.formulario.IdOrdemServico.readOnly){
							validar = false;
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
				case 'OrdemServicoFatura':
					if(id == 'quadroBuscaTerceiro'){
						if(document.formulario.EditarTerceiro.value == 0 || ((document.formulario.IdStatus.value >= 500 && document.formulario.IdStatus.value <= 599) || (document.formulario.IdStatus.value >= 0 && document.formulario.IdStatus.value <= 99))){
							validar = false;
						}
					}
					break;
				case 'DeclaracaoPagamento':
					if(id == 'quadroBuscaPessoa'){
						if(document.formulario.AnoReferencia.value == ''){
							validar = false;
						}
					}
					break;
				/*case 'UrlSms':
					if(id == 'quadroBuscaPessoa'){
						if(document.formulario.IdPessoa.value == ''){
							validar = false;
						}
					}
					break;*/
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
				
				// PosiÁ„o left da janela
				var tempWidth = document.body.offsetWidth-document.getElementById(id).offsetWidth;
				width = tempWidth/2;
				
				// PosiÁ„o top da janela
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
					case 'AgruparContaReceber':
						if(id == 'quadroBuscaPessoa'){
							limpa_form_pessoa(); 
							busca_pessoa_lista(); 
							document.formularioPessoa.Nome.focus();
						}
						if(id == 'quadroBuscaContaReceber'){
							limpa_form_conta_receber(); 
							busca_conta_receber_lista(); 
							document.formularioContaReceber.Nome.focus();
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
						if(id == 'quadroBuscaGrupoPessoa'){
							//limpa_form_grupo_pessoa(); 
							busca_grupo_pessoa_lista(); 
							document.formularioGrupoPessoa.Nome.focus();
						}
						break;
					case 'MalaDireta':
						if(id == 'quadroBuscaPessoa'){
							limpa_form_pessoa(); 
							busca_pessoa_lista(); 
							document.formularioPessoa.Nome.focus();
						}
						if(id == 'quadroBuscaGrupoPessoa'){
							limpa_form_grupo_pessoa(); 
							busca_grupo_pessoa_lista(); 
							document.formularioGrupoPessoa.Nome.focus();
						}
						if(id == 'quadroBuscaServico'){
							limpa_form_servico();
							busca_servico_lista(); 
							document.formularioServico.DescricaoServico.focus();
						}
						if(id == 'quadroBuscaContrato'){
							limpa_form_contrato(); 
							busca_contrato_lista(); 
							document.formularioContrato.Nome.focus();
						}
						if(id == 'quadroBuscaProcessoFinanceiro'){
							limpa_form_processo_financeiro(); 
							busca_processo_financeiro_lista();
							document.formularioProcessoFinanceiro.MesReferencia.focus();
						}
						if(id == 'quadroBuscaMalaDiretaEnviada'){
							limpa_form_mala_direta_enviada(); 
							busca_mala_direta_enviada_lista();
							document.formularioProcessoFinanceiro.MesReferencia.focus();
						}
						if(id == 'quadroBuscaPais'){
							limpa_form_pais(); 
							busca_pais_lista();
							document.formularioPais.NomePais.focus();
						}
						if(id == 'quadroBuscaEstado'){
							limpa_form_estado(); 
							busca_estado_lista();
							document.formularioEstado.NomeEstado.focus();
						}
						if(id == 'quadroBuscaCidade'){
							limpa_form_cidade(); 
							busca_cidade_lista();
							document.formularioCidade.NomeCidade.focus();
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
						if(id == 'quadroBuscaAgente'){
							limpa_form_agente();
							busca_agente_lista();
							document.formularioAgente.Nome.focus();
						}
						break;
					case 'Protocolo':
						if(id == 'quadroBuscaPessoa'){
							limpa_form_pessoa(); 
							busca_pessoa_lista(); 
							document.formularioPessoa.Nome.focus();
						}
						if(id == 'quadroBuscaContrato'){
							document.formularioContrato.DescricaoServico.value = '';
							busca_contrato_lista(); 
							document.formularioContrato.DescricaoServico.focus();
						}
						if(id == 'quadroBuscaContaEventual'){
							limpa_form_conta_eventual();
							busca_conta_eventual_lista(); 
							document.formularioContaEventual.DescricaoContaEventual.focus();
						}
						if(id == 'quadroBuscaContaReceber'){
							limpa_form_conta_receber();
							busca_conta_receber_lista(); 
							document.formularioContaReceber.Nome.focus();
						}
						if(id == 'quadroBuscaOrdemServico'){
							limpa_form_ordem_servico();
							busca_ordem_servico_lista(); 
							document.formularioOrdemServico.DescricaoOrdemServico.focus();
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
					case 'ForcarExclusaoContratoVinculos':
						if(id == 'quadroBuscaContrato'){
							limpa_form_contrato(); 
							busca_contrato_lista(); 
							document.formularioContrato.Nome.focus();
						}
						break;	
					case 'ServicoParametro':
						if(id == 'quadroBuscaGrupoUsuario'){
							document.formularioGrupoUsuario.Nome.value = '';
							busca_grupo_usuario_lista(); 
							document.formularioGrupoUsuario.Nome.focus();
						}
						break;	
					case 'MonitorAlarme':
						if(id == 'quadroBuscaMonitor'){
							document.formularioMonitor.DescricaoMonitor.value = '';
							busca_monitor_lista(); 
							document.formularioMonitor.DescricaoMonitor.focus();
						}
						break;	
					case 'Movimentacao':
						if(id == 'quadroBuscaContaReceber'){
							limpa_form_conta_receber(); 
							busca_conta_receber_lista(); 
							document.formularioContaReceber.Nome.focus();
						}
						break;
				    case 'UrlSms':
						if(id == 'quadroBuscaPessoa'){
							limpa_form_pessoa(); 
							busca_pessoa_lista(); 
							document.formularioPessoa.Nome.focus();
						}
						break;
					case 'Device':
						if(id == 'quadroBuscaGrupoDevice'){
							limpa_form_grupo_device(); 
							busca_grupo_device_lista(); 
							document.formularioGrupoDevice.Nome.focus();
						}
						break;
					case 'TipoMensagemParametro':
						if(id == 'quadroBuscaTipoMensagem'){
							limpa_form_tipo_mensagem(); 
							busca_tipo_mensagem_lista(); 
							document.formularioTipoMensagem.Nome.focus();
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
	
	function consulta_rapida(acao, elementIMGTop){
		var img = {
			top : "../../img/estrutura_sistema/seta_cima.gif",
			bottom : "../../img/estrutura_sistema/seta_baixo.gif",
			regExp : null 
		};
		
		eval("img.regExp = new RegExp('('+img."+acao+".replace(/(\\.\\.\\/)/g, '')+')$', 'i');");
		
		switch(acao){
			case 'top':
				document.getElementById('filtroRapido').style.display = 'none';
				acao = "bottom";
				break;
				
			case 'bottom':
				document.getElementById('filtroRapido').style.display = 'block';
				acao = "top";
				break;
				
			default:
				acao = undefined;
				
		}
		
		if(acao != undefined){
			if(elementIMGTop == undefined){
				for(var i = 0; i < document.getElementsByTagName("img").length; i++){
					if(document.getElementsByTagName("img")[i].onclick != null){
						if((/(consulta_rapida)/).test(document.getElementsByTagName("img")[i].onclick.toString()) && img.regExp.test(document.getElementsByTagName("img")[i].src.toString())){
							elementIMGTop = document.getElementsByTagName("img")[i];
							break;
						}
					}
				}
			}
			
			if(elementIMGTop != undefined){
				eval("elementIMGTop.src = img."+acao+";");
				eval("elementIMGTop.onclick = "+elementIMGTop.onclick.toString().replace(/(consulta_rapida\()([^\)]*)(\)[;])/i, "consulta_rapida(acao, elementIMGTop);")+";");
			}
		}
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
		dataFimVigencia.setMonth(dataFimVigencia.getMonth() + parseInt(valor));//lembrando que o mes È um inteiro de 0-11
		
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
						
						Origem	= removeAcento(Origem);
						Destino	= removeAcento(Destino);
						
						como_chegar_direciona(Origem, Destino);
					}
				}
			}
			return true;
		}
		xmlhttp.send(null);
	}
	function como_chegar_direciona(Origem,Destino){
		window.open("como_chegar.php?Origem="+Origem+"&Destino="+Destino);
	}
	function removeAcento (text){
		text = text.replace(new RegExp('[¡¿¬√]','gi'), 'a');
		text = text.replace(new RegExp('[·‚„]','gi'), 'a');
        text = text.replace(new RegExp('[…» ]','gi'), 'e');
        text = text.replace(new RegExp('[ÈËÍ]','gi'), 'e');
        text = text.replace(new RegExp('[ÕÃŒ]','gi'), 'i');
        text = text.replace(new RegExp('[ÌÏÓ]','gi'), 'i');
        text = text.replace(new RegExp('[”“‘’]','gi'), 'o');
        text = text.replace(new RegExp('[ÛÚÙı]','gi'), 'o');
        text = text.replace(new RegExp('[⁄Ÿ€]','gi'), 'u');
        text = text.replace(new RegExp('[˙˘˚]','gi'), 'u');
        text = text.replace(new RegExp('[«]','gi'), 'c');
        text = text.replace(new RegExp('[Á]','gi'), 'c');
        
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
			case "Y-m-d":
				dia = data.substr(8,2);
				mes = data.substr(5,2);
				ano = data.substr(0,4);
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
	
	function getStyle(element, strCssRule){
		/* Busca o valor de um atributo especifico no atributo style da tag retornando o valor do atributo. */
		var strValue = null;
		
		if(document.defaultView && document.defaultView.getComputedStyle) {
			strValue = document.defaultView.getComputedStyle(element, "").getPropertyValue(strCssRule);
		} else if(element.currentStyle) {
			strCssRule = strCssRule.replace(/\-(\w)/g, function (strMatch, p1) {
				return p1.toUpperCase();
			});
			strValue = element.currentStyle[strCssRule];
		}
		
		return strValue;
	}
	
	function getAllElementsByName(tag, name) {
		/* Busca os elementos pela class dada na tag especifica retornando um array de elementos. */
		var element = document.getElementsByTagName(tag);
		var tagName = new Array();
		
		for(var cont = 0, i = 0; cont < element.length; cont++) {
			att = element[cont].getAttribute("name");
			
			if(att != null){
				att = att.toLowerCase();
			}
			
			if(att == name.toLowerCase()) {
				tagName[i] = element[cont];
				i++;
			}
		}
		
		return tagName;
	}
	
	function getPositionCursor(campo) {
		var start, end;
		/* Pega a possiÁ„o do cursor no campo */
		if(document.selection) { /*IE*/
			var bm = document.selection.createRange().getBookmark();
			var sel = campo.createTextRange();
			sel.moveToBookmark(bm);
			
			var sleft = campo.createTextRange();
			sleft.collapse(true);
			sleft.setEndPoint("EndToStart", sel);
			start = sleft.text.length
			end = sleft.text.length + sel.text.length;
		} else {
			start = campo.selectionStart;
			end = campo.selectionEnd;
		}
		
		return {
			"start": start,
			"end": end};
	}
	
	function setPositionCursor(campo, start, end) {
		/* Direcionar o cursor para o local desejado */
		if(campo.createTextRange) { /*IE*/
			var range = campo.createTextRange();
			range.collapse(true);
			range.moveStart('character', start);
			range.moveEnd('character', end-start);
			range.select();
		} else {
			campo.setSelectionRange(start, end);
		}
	}
	
	var temp_alt;
	var temp_alt_x = null;
	var temp_alt_y = null;
	
	var clear = function () {
		document.getElementById("quadro_title").style.top = "0px";
		document.getElementById("quadro_title").style.left = "0px";
		document.getElementById("quadro_title").style.display = "none";
		temp_alt_x = null;
		temp_alt_y = null;
		
		clearTimeout(temp_alt);
	};
	
	function __alt(element, title, codicao) {
		element.onmousedown = element.onmouseout = function () {
			clear();
		};
		if((/[\w ]*\(\)/).test(element.onmousemove)) { // BROWSER IE
			document.getElementsByTagName("body")[0].onmousemove = function() {
				temp_alt_x = event.clientX;
				temp_alt_y = event.clientY;
			};
			
			element.onmousemove = (function() {
				return function() {
					__posicionar_alt(event, title, codicao);
				};
			})();
		} else {
			document.getElementsByTagName("body")[0].onmousemove = function(event) {
				temp_alt_x = event.clientX;
				temp_alt_y = event.clientY;
			};
			
			element.onmousemove = (function(event) {
				return function(event) {
					__posicionar_alt(event, title, codicao);
				};
			})();
		}
	}
	function __posicionar_alt(event, title, codicao) {
		var y = event.clientY;
		var x = event.clientX;
		
		if(y != temp_alt_y || x != temp_alt_x) {
			clearTimeout(temp_alt);
			clear();
			
			if(codicao != undefined){
				codicao = eval(codicao);
			} else{
				codicao = true;
			}
			
			temp_alt = setTimeout( function() {
				if(y == temp_alt_y && x == temp_alt_x && codicao) {
					document.getElementById("quadro_title").style.display = "block";
					document.getElementById("quadro_title").innerHTML = title;
					var temp_scroll = (window.innerHeight != undefined ? window.innerHeight : document.documentElement.clientHeight);
					var tam = ((document.getElementsByTagName("body")[0].offsetWidth) + 4) - document.getElementById("quadro_title").offsetWidth;
					var temp_height = document.documentElement.scrollTop != undefined ? document.documentElement.scrollTop : 0;
					
					if(temp_height == 0 && document.getElementsByTagName("body")[0].scrollTop > 0) 
						temp_height = document.getElementsByTagName("body")[0].scrollTop; 
					
					if(temp_scroll == 0 && document.getElementsByTagName("body")[0].clientHeight > 0) 
						temp_scroll = document.getElementsByTagName("body")[0].clientHeight;
					
					if(window.pageYOffset > temp_height) 
						temp_height = window.pageYOffset;
					
					if((22 + y + document.getElementById("quadro_title").offsetHeight) > temp_scroll) {
						y += temp_height;
						y -= (document.getElementById("quadro_title").offsetHeight+26);
					} else
						y += temp_height;
					
					document.getElementById("quadro_title").style.top = (y + 22) + "px";
					document.getElementById("quadro_title").style.left = (tam < x ? tam : x) + "px";
				}
			}, 300);
		}
	}
	function quadro_alt(event, element, title, codicao) {
		if(document.getElementById("quadro_title") == null) {
			var mont = document.getElementsByTagName("body")[0];
			var new_element = document.createElement("span");
			new_element.setAttribute("id", "quadro_title");
			new_element.innerHTML = "&nbsp;";
			mont.appendChild(new_element);
		}
		
		__alt(element, title, codicao);
		__posicionar_alt(event, title, codicao);
	}
	Array.prototype.in_array = function(key) {
		for(var i in this){
			if(this[i] == key){
				return true;
			}
		}
		
		return false;
	};
	function call_ajax(dados_http, instrucao, carregando) {
		var nome_carregando = "carregando-ajax";
		
		if(carregando == undefined) {
			carregando = {
				loading: true,
				sleep: 0,
				id: null
			};
		} else {
			if(typeof(carregando) == "object") {
				if(carregando.loading == undefined) {
					carregando.loading = true;
				}
				
				if(carregando.sleep == undefined) {
					carregando.sleep = 0;
				}
				
				if(document.getElementById(carregando.id) != null) {
					nome_carregando += "-"+carregando.id;
				}
				
				carregando.id = document.getElementById(carregando.id);
				
				if(carregando.id != null) {
					if(carregando.id_y == undefined) {
						carregando.id_y = carregando.id.offsetHeight;
					}
					
					if(carregando.id_x == undefined) {
						carregando.id_x = carregando.id.offsetWidth;
					}
				}
			} else {
				carregando = {
					loading: carregando,
					sleep: 0,
					id: null
				};
			}
		}
		
		if(!document.getElementById(nome_carregando)) {
			var style = "display:block;";
			var mont = carregando.id;
			var new_element = document.createElement("div");
			new_element.setAttribute("id", nome_carregando);
			
			if(mont != null) {
				if(carregando.position_y == undefined)
					carregando.position_y = 0;
				
				if(carregando.position_x == undefined)
					carregando.position_x = 0;
				
				style += "position:relative; height:"+(carregando.id_y-4)+"px; width:"+(carregando.id_x-2)+"px; cursor:progress; margin-top:-"+(carregando.id_y+(carregando.position_y))+"px; margin-left:"+carregando.position_x+"px;";
				new_element.innerHTML = "<div style=\"height:"+(carregando.id_y-2)+"px; width:"+(carregando.id_x-2)+"px; opacity:0.6; filter:alpha(opacity = 60); background-color:#aaa; border:1px solid #666;\"></div><div style=\"position:relative; margin:auto; margin-top:-"+((carregando.id_y/2)+20)+"px; height:40px; width:40px; background:#fff no-repeat url('../../img/estrutura_sistema/carregando_min.gif') center; border:1px solid #888;\"></div>";
			} else{
				if(document.body != null) {
					mont = document.body;
				} else {
					mont = document.getElementsByTagName("body")[0];
				}
				
				new_element.innerHTML = "<div id=\"carregando-ajax-back\"></div><div id=\"carregando-ajax-img\"></div>";
			}
			
			new_element.setAttribute("style", style);
			mont.appendChild(new_element);
			document.getElementById(nome_carregando).accessKey = 0;
		} else if(document.getElementById(nome_carregando).style.display == "none") {
			document.getElementById(nome_carregando).accessKey = 0;
			document.getElementById(nome_carregando).style.display = "block";
		} else {
			document.getElementById(nome_carregando).accessKey = (parseInt(document.getElementById(nome_carregando).accessKey) + 1);
		}
		
		if(!carregando.loading) {
			document.getElementById(nome_carregando).style.display = "none";
		}
		
		var xmlhttp = false;
		
		if(window.XMLHttpRequest) { // Mozilla, Safari,...
			xmlhttp = new XMLHttpRequest();
			
			if(xmlhttp.overrideMimeType) {
				xmlhttp.overrideMimeType('text/xml');
			}
		} else if(window.ActiveXObject) { // IE
			try {
				xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
			} catch(e) {
				try {
					xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
				} catch(e) {}
			}
		}
		
		if(typeof dados_http === typeof String()) {
			dados_http = {
				method: "GET",
				url: dados_http
			};
		}
		
		if(dados_http.method.toLowerCase() == "post") {
			var temp = dados_http.url.split(/\?/);
			dados_http.url = temp[0];
			dados_http.url_var = temp[1];
		} else {
			dados_http.url_var = null;
		}
		
		xmlhttp.open(dados_http.method, dados_http.url, true);
		xmlhttp.onreadystatechange = function() {
			if(xmlhttp.readyState == 4) { 
				if(xmlhttp.status == 200) {
					if(instrucao != undefined){
						instrucao(xmlhttp);
					}
					
					if(parseInt(document.getElementById(nome_carregando).accessKey) == 0) {
						setTimeout(function (){ document.getElementById(nome_carregando).style.display = "none"; },carregando.sleep);
					} else {
						document.getElementById(nome_carregando).accessKey = (parseInt(document.getElementById(nome_carregando).accessKey) - 1);
					}
				} else if(xmlhttp.status == 404) {
					window.location.href = "./debug.php";
				}
			}
			
			return true;
		};
		xmlhttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded;");
		xmlhttp.send(dados_http.url_var);
	}
	// QUADRO DE BUSCA RAPIDA //
	var bloco_busca_aproximada_linha = 0;
	
	function get_key_code(event){
		return (document.all ? event.keyCode : document.layers ? event.which : event.which);
	}
	function busca_aproximada_nover(direcao,campo){
		if(direcao == 40){
			if(document.getElementById("bloco_busca_aproximada_linha_a"+(bloco_busca_aproximada_linha+1))){
				bloco_busca_aproximada_linha++;
				campo.value = htmlspecialchars_decode(document.getElementById("bloco_busca_aproximada_linha_a"+bloco_busca_aproximada_linha).innerHTML);
				document.getElementById("bloco_busca_aproximada_linha_a"+bloco_busca_aproximada_linha).style.background = "#a0c4ea";
				
				if(document.getElementById("bloco_busca_aproximada_linha_a"+(bloco_busca_aproximada_linha-1))){
					document.getElementById("bloco_busca_aproximada_linha_a"+(bloco_busca_aproximada_linha-1)).style.background = "#f7f7f7";
				}
			}
		} else if(direcao == 38){
			if(document.getElementById("bloco_busca_aproximada_linha_a"+(bloco_busca_aproximada_linha-1))){
				bloco_busca_aproximada_linha--;
				campo.value = htmlspecialchars_decode(document.getElementById("bloco_busca_aproximada_linha_a"+bloco_busca_aproximada_linha).innerHTML);
				document.getElementById("bloco_busca_aproximada_linha_a"+bloco_busca_aproximada_linha).style.background = "#a0c4ea";
				
				if(document.getElementById("bloco_busca_aproximada_linha_a"+(bloco_busca_aproximada_linha+1))){
					document.getElementById("bloco_busca_aproximada_linha_a"+(bloco_busca_aproximada_linha+1)).style.background = "#f7f7f7";
				}
			}
		} else if(direcao == 9 || direcao == 13 || direcao == 37 || direcao == 39){
			bloco_busca_aproximada_linha = 0;
			document.getElementById("bloco_busca_aproximada").style.display = "none";
			document.getElementById("bloco_busca_aproximada").innerHTML = '';
		} else{
			bloco_busca_aproximada_linha = 0;
		}
	}
	function busca_aproximada(formulario,campo,event,texto,top,left){
		var str = '';
		campo.autocomplete = "off";
		
		if(!document.getElementById("bloco_busca_aproximada")) {
			if(document.body != null) {
				var mont = document.body;
			} else {
				var mont = document.getElementsByTagName("body")[0];
			}
			
			var new_element = document.createElement("div");
			new_element.setAttribute("id", "bloco_busca_aproximada");
			mont.appendChild(new_element);
		}
		
		if((/[\w ]*\(\)/).test(campo.onkeydown)){ // BROWSER IE
			campo.onkeydown = function (){
				busca_aproximada_nover(get_key_code(event),campo);
			};
		} else{
			campo.onkeydown = function (event){
				busca_aproximada_nover(get_key_code(event),campo);
			};
		}
		
		var keyCode = get_key_code(event);
		
		if(keyCode == 39 && bloco_busca_aproximada_linha != 0){
			bloco_busca_aproximada_linha = 0;
			document.getElementById("bloco_busca_aproximada").style.display = "none";
			document.getElementById("bloco_busca_aproximada").innerHTML = '';
			return;
		}
		
		keyCode = (keyCode != 40 && keyCode != 38 && bloco_busca_aproximada_linha == 0);
		
		if(campo.value != '' && keyCode){
			if(top == undefined){
				top = campo.offsetTop;
			}
			
			if(left == undefined){
				left = campo.offsetLeft;
			}
			
			document.getElementById("bloco_busca_aproximada").style.top = (top+campo.offsetHeight)+"px";
			document.getElementById("bloco_busca_aproximada").style.left = left+"px";
			document.getElementById("bloco_busca_aproximada").style.minWidth = (campo.offsetWidth-2)+"px";
			
			for(var i = 0, cont = 1; i < texto.length; i++){
				if((new RegExp("^"+campo.value.toLowerCase()+"[\w\W]*")).test(texto[i].toLowerCase()) && campo.value != '' && campo.value.toLowerCase() != texto[i].toLowerCase()){
					str += "<div class='bloco_busca_aproximada_linha' id='bloco_busca_aproximada_linha_a"+cont+"' onClick=\"document."+formulario+"."+campo.name+".value = '"+texto[i]+"'; document.getElementById('bloco_busca_aproximada').style.display = 'none';\">"+texto[i]+"</div>";
					cont++;
				}
			}
		}
		
		if(str != ''){
			bloco_busca_aproximada_linha = 0;
			document.getElementById("bloco_busca_aproximada").style.display = "block";
			document.getElementById("bloco_busca_aproximada").innerHTML = str;
		} else if(keyCode){
			bloco_busca_aproximada_linha = 0;
			document.getElementById("bloco_busca_aproximada").style.display = "none";
			document.getElementById("bloco_busca_aproximada").innerHTML = '';
		}
	}
	function imprimir_frame(){
		if(document.getElementById("versaoDeImpressao") != undefined){
			document.getElementById("versaoDeImpressao").style.display = 'block';
		}
		var codeHead = document.getElementsByTagName("head")[0].innerHTML.replace(/([\w\W]*<style)/i, "<style").replace(/(<\/style>[\w\W]*)/i, "</style>");
		var codeBody = document.getElementsByTagName("body")[0].innerHTML;
		var janela = window.open("template_impressao.php");
		setTimeout(function (){
			janela.document.getElementsByTagName("body")[0].innerHTML += codeBody;
			janela.document.getElementsByTagName("head")[0].innerHTML += codeHead;
			
			setTimeout(function (){
				janela.window.print();
				janela.close();
			},333);
		},444);
		if(document.getElementById("versaoDeImpressao") != undefined){
			document.getElementById("versaoDeImpressao").style.display = 'none';
		}
	}
	function ocultarQuadro(Botao, Id, Ocultar){
		var Nome = Id.replace(/tabela/i, "filtro_");
		if(document.getElementById(Id).style.display == "none" && Ocultar !== true){
			var Valor = 1;
			Botao.src = "../../img/estrutura_sistema/ico_seta_up.gif";
			Botao.title = "Minimizar";
			Botao.alt = "Minimizar";
			document.getElementById(Id).style.display = "block";
		} else{
			var Valor = 2;
			Botao.src = "../../img/estrutura_sistema/ico_seta_down.gif";
			Botao.title = "Maximizar";
			Botao.alt = "Maximizar";
			document.getElementById(Id).style.display = "none";
		}
		
		atualizaSessao(Nome, Valor);
	}
	function getScript(src){
		document.write(
			"<"+
			"script "+
			"src=\""+src+"\" "+
			"type=\"text/javascript\" "+
			"><"+
			"/script"+
			">"
		);
	}
	function initialize_map(IdMount, instruction, foco){
		getScript("https://maps.googleapis.com/maps/api/js?sensor=false");
		
		(function (){
			function verifica(){
				Mount = document.getElementById(IdMount);
				
				if(Mount == undefined){
					setTimeout(function () { verifica(); }, 55);
				} else{
					var DivLN = document.createElement("div");
					
					do {
						var Id = "map_canvas"+Math.floor((Math.random()*10000)+1);
					} while(document.getElementById(Id) != null);
					
					DivLN.setAttribute("id", Id);
					DivLN.setAttribute("style", "height:500px; width:820px; margin:auto; border:1px solid black; background-color:#e5e3df;");
					document.getElementById(IdMount).appendChild(DivLN);
					
					if(foco != undefined) {
						foco.address = "Brazil";
						var zoom_tmp = 4;
						
						if(foco.pais != undefined) {
							foco.address = foco.pais;
							zoom_tmp = 4
						}
						
						if(foco.uf != undefined) {
							foco.address = foco.uf+", "+foco.address;
							zoom_tmp = 6
							
							if(foco.cidade != undefined) {
								foco.address = foco.cidade+" - "+foco.address;
								zoom_tmp = 12
							}
						} else if(foco.cidade != undefined) {
							foco.address = foco.cidade+", "+foco.address;
							zoom_tmp = 12
						}
						
						if(foco.zoom == undefined) {
							foco.zoom = zoom_tmp;
						}
					} else {
						foco = {
							address : "Brazil",
							zoom: 4
						};
					}
					
					var geocoder = new google.maps.Geocoder();
					
					geocoder.geocode({
							"address": foco.address
						}, function(results, status){
							var geometry = {
								location: new google.maps.LatLng(41.850033, -87.6500523),
								zoom: foco.zoom
							};
							
							if(status == google.maps.GeocoderStatus.OK){
								geometry.location = results[0].geometry.location;
							}
							
							var myOptions = {
								center: geometry.location,
								zoom: geometry.zoom,
								mapTypeId: google.maps.MapTypeId.ROADMAP
							};
							var map = new google.maps.Map(document.getElementById(Id), myOptions);
							
							instruction(map);
						}
					);
				}
			}
			
			verifica();
		})();
	}
	function map_set_marker(map, content){
		if(content.address != undefined){
			var geoCoder = new google.maps.Geocoder();
			
			geoCoder.geocode({
					"address": content.address
				}, function(results, status){
					if(status == google.maps.GeocoderStatus.OK){
						var infoWindow = new google.maps.InfoWindow({
							content: content.content
						});
						var marker = new google.maps.Marker({
							position: results[0].geometry.location,
							map: map,
							title: content.title
						});
						
						google.maps.event.addListener(marker, "click", function() {
							infoWindow.open(map, marker);
						});
					}
				}
			);
		} else if(content.lat != undefined && content.lng != undefined){
			var infoWindow = new google.maps.InfoWindow({
				content: content.content
			});
			var marker = new google.maps.Marker({
				position: new google.maps.LatLng(content.lat, content.lng),
				map: map,
				title: content.title
			});
			
			google.maps.event.addListener(marker, "click", function() {
				infoWindow.open(map, marker);
			});
		}
	}
	function order_table(id, cell, order, instrucao){
		var order_img = "../../img/estrutura_sistema/", order_new;
		
		switch(order.toLowerCase()){
			case "asc":
				order_new = "desc";
				order_img += "seta_ascending.gif";
				break;
			case "desc":
				order_new = "asc";
				order_img += "seta_descending.gif";
		}
		
		
		for(var i = 0; i < document.getElementById(id).rows[0].cells.length; i++){
			var order_temp = order;
			
			if(document.getElementById(id).rows[0].cells[i].getElementsByTagName("img")[0] != undefined){
				document.getElementById(id).rows[0].cells[i].removeChild(document.getElementById(id).rows[0].cells[i].getElementsByTagName("img")[0]);
			}
			
			if(cell == i) {
				order_temp = order_new;
			}

			eval("document.getElementById(id).rows[0].cells["+i+"].onclick = function () { order_table('"+id+"', '"+i+"', '"+order_temp+"', instrucao); };");
		}
		
		instrucao(cell, order);
		
		var img = document.createElement("img");
		img.setAttribute("src", order_img);
		img.setAttribute("style", "margin-left:4px;");
		document.getElementById(id).rows[0].cells[cell].appendChild(img);
	}
	function getSelectOptionsText(campo,form){
		var result = "";
		var opcao = eval("document."+form+"."+campo+".options;");
		var index = eval("document."+form+"."+campo+".selectedIndex;");
		
		var result = opcao[index].text;
		
		return result;
	}
	function addFiltrosImpressao(){		
		if(document.filtro.filtro_id_servico.value != ""){
			var servico = "<b>Servi&#231;o:</b> ("+document.filtro.filtro_id_servico.value+") "+document.filtro.filtro_nome_servico.value+"<br />";
		}else{servico = "";}
		if(document.filtro.filtro_contrato_cancelado.value != ""){
			var co_cancelado = "<b>Listar Contratos cancelados:</b> "+getSelectOptionsText("filtro_contrato_cancelado","filtro")+"<br />";
		}else{co_cancelado = "";}
		if(document.filtro.filtro_contrato_soma.value != ""){
			var co_soma = "<b>Somar valores dos contratos cancelados:</b> "+getSelectOptionsText("filtro_contrato_soma","filtro")+"<br />";
		}else{co_soma = "";}
		if(document.filtro.filtro_local_cobranca.value != ""){
			var local_cobranca = "<b>Local Cobran&#231;a:</b> "+getSelectOptionsText("filtro_local_cobranca","filtro")+"<br />";
		}else{local_cobranca = "";}
		if(document.filtro.filtro_usuario.value != ""){
			var usuario = "<b>Usu&#225;rio de Cadastro:</b> "+getSelectOptionsText("filtro_usuario","filtro")+"<br />";
		}else{usuario = "";}
		if(document.filtro.filtro_estado.value != ""){
			var estado = "<b>Estado:</b> "+getSelectOptionsText("filtro_estado","filtro")+"<br />";
		}else{estado = "";}
		if(document.filtro.filtro_nome_cidade.value != ""){
			var cidade = "<b>Cidade:</b> "+document.filtro.filtro_nome_cidade.value+"<br />";
		}else{cidade = "";}
		if(document.filtro.filtro_bairro.value != ""){
			var bairro = "<b>Bairro:</b> "+document.filtro.filtro_bairro.value+"<br />";
		}else{bairro = "";}
		if(document.filtro.filtro_endereco.value != ""){
			var endereco = "<b>Endere&#231;o:</b> "+document.filtro.filtro_endereco.value+"<br />";
		}else{endereco = "";}
		if(document.filtro.filtro_QTDCaracterColunaPessoa.value != ""){
			var qtd = "<b>Qtd car&#225;cter na coluna Nome Pessoa/Raz&#227;o Social:</b> "+document.filtro.filtro_QTDCaracterColunaPessoa.value+"<br />";
		}else{qtd = "";}
		
		var array = "<p class='ocultar'>"+servico+co_cancelado+co_soma+local_cobranca+usuario+estado+cidade+bairro+endereco+qtd+"</p>";
		
		return array;
	}
	function htmlspecialchars(string, quote_style, charset, double_encode) {
		var optTemp = 0,
			i = 0,
			noquotes = false;
		
		if(typeof quote_style === 'undefined' || quote_style === null) {
			quote_style = 2;
		}
		
		string = string.toString();
		
		if(double_encode !== false) { // Put this first to avoid double-encoding
			string = string.replace(/&/g, '&amp;');
		}
		
		string = string.replace(/</g, '&lt;').replace(/>/g, '&gt;');
		
		var OPTS = {
			'ENT_NOQUOTES': 0,
			'ENT_HTML_QUOTE_SINGLE': 1,
			'ENT_HTML_QUOTE_DOUBLE': 2,
			'ENT_COMPAT': 2,
			'ENT_QUOTES': 3,
			'ENT_IGNORE': 4
		};
		
		if(quote_style === 0) {
			noquotes = true;
		}
		
		if(typeof quote_style !== 'number') { // Allow for a single string or an array of string flags
			quote_style = [].concat(quote_style);
			
			for(i = 0; i < quote_style.length; i++) {
				// Resolve string input to bitwise e.g. 'ENT_IGNORE' becomes 4
				if(OPTS[quote_style[i]] === 0) {
					noquotes = true;
				} else if(OPTS[quote_style[i]]) {
					optTemp = optTemp | OPTS[quote_style[i]];
				}
			}
			
			quote_style = optTemp;
		}
		
		if(quote_style & OPTS.ENT_HTML_QUOTE_SINGLE) {
			string = string.replace(/'/g, '&#039;');
		}
		
		if(!noquotes) {
			string = string.replace(/"/g, '&quot;');
		}
		
		return string;
	}
	function htmlspecialchars_decode(string, quote_style) {
		var optTemp = 0,
			i = 0,
			noquotes = false;
		
		if(typeof quote_style === 'undefined') {
			quote_style = 2;
		}
		
		string = string.toString().replace(/&lt;/g, '<').replace(/&gt;/g, '>');
		var OPTS = {
			'ENT_NOQUOTES': 0,
			'ENT_HTML_QUOTE_SINGLE': 1,
			'ENT_HTML_QUOTE_DOUBLE': 2,
			'ENT_COMPAT': 2,
			'ENT_QUOTES': 3,
			'ENT_IGNORE': 4
		};
		
		if(quote_style === 0) {
			noquotes = true;
		}
		
		if(typeof quote_style !== 'number') { // Allow for a single string or an array of string flags
			quote_style = [].concat(quote_style);
			
			for(i = 0; i < quote_style.length; i++) {
				// Resolve string input to bitwise e.g. 'PATHINFO_EXTENSION' becomes 4
				if(OPTS[quote_style[i]] === 0) {
					noquotes = true;
				} else if(OPTS[quote_style[i]]) {
					optTemp = optTemp | OPTS[quote_style[i]];
				}
			}
			
			quote_style = optTemp;
		}
		
		if(quote_style & OPTS.ENT_HTML_QUOTE_SINGLE) {
			string = string.replace(/&#0*39;/g, "'"); // PHP doesn't currently escape if more than one 0, but it should
			// string = string.replace(/&apos;|&#x0*27;/g, "'"); // This would also be useful here, but not a part of PHP
		}
		
		if(!noquotes) {
			string = string.replace(/&quot;/g, '"');
		}
		// Put this in last place to avoid escape being double-decoded
		string = string.replace(/&amp;/g, '&');
		
		return string;
	}
	function valida_arquivo(arquivo, extensoes, acao){
		extensao = (arquivo.substring(arquivo.lastIndexOf("."))).toLowerCase();
		permitida = false;
		
		for (var i = 0; i < extensoes.length; i++) {
			if (extensoes[i] == extensao) {
				permitida = true;
				break;
			}
		}
		if (!permitida) {
			mensagens(192);
		}else{
			cadastrar(acao);
		}
	}
	function verificaSenha(Local){
		forca = 0;
		switch(Local){
			case "UsuarioAlterarSenha" :			
				senha = document.formulario.NovaSenha.value;
				break;
			case "Usuario":
				senha = document.formulario.Password.value;
				break;
		}
		nivel = document.getElementById("nivel");				
		if(senha.length >= 8){
			forca += 5;
		}
		if(senha.match(/[a-z]+/)){
			forca += 20;
		}
		if(senha.match(/[A-Z]+/)){
			forca += 25;
		}
		if(senha.match(/\d+/)){
			forca += 25;
		}
		if(senha.match(/\W+/)){			
			forca += 25;
		}
		return nivelSenha();
	}
	function nivelSenha(){
		if(senha != ""){
			document.getElementById("statusSenha").style.display = "block";
			if(senha.length < 8){
				estadoSenha.innerHTML = "<b>NÌvel da senha:</b>  Muito curta";
				nivel.style.backgroundColor = "#AA0033";
				nivel.style.width = 20+"%";
			}else{
				if(forca < 40){
					nivel.style.backgroundColor = "#AA0033";
					nivel.style.width = forca+"%";
					estadoSenha.innerHTML = "<b>NÌvel da senha:</b>  Fraca";
					document.formulario.NivelSenha.value = 1;
				}else if((forca >= 40) && (forca < 60)){
					nivel.style.backgroundColor = "#FFA500";
					nivel.style.width = forca+"%";
					estadoSenha.innerHTML = "<b>NÌvel da senha:</b>  Justa";
					document.formulario.NivelSenha.value = 2;
				}else if((forca >= 60) && (forca < 95)){
					nivel.style.backgroundColor = "#2D98F3";
					nivel.style.width = forca+"%";
					estadoSenha.innerHTML = "<b>NÌvel da senha:</b>  Bom";
					document.formulario.NivelSenha.value = 3;
				}else{
					nivel.style.backgroundColor = "#76C261";
					nivel.style.width = 100+"%";
					estadoSenha.innerHTML = "<b>NÌvel da senha:</b>  Relevante";
					document.formulario.NivelSenha.value = 4;
				}
			}
		}else{
			estadoSenha.innerHTML = "<b>NÌvel da senha:</b> ";
			nivel.style.backgroundColor = "#AA0033";
			nivel.style.width = 0+"%";
		}
	}