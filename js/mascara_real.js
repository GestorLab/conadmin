documentall = document.all;
	
function formatamoney(c) {
	if(c == undefined) 
		c = 2;
	
	var temp = this.replace(/[-\.,]/g, '');
	
	return temp.substring(0, temp.length-c) + ',' + temp.substring(temp.length-c);
/*	var t = this; if(c == undefined) c = 2;		
    var p, d = (t=t.split("."))[1].substr(0, c);
    for(p = (t=t[0]).length; (p-=3) >= 1;) {
	        t = t.substr(0,p) + "." + t.substr(p);
    }
    return t+","+d+Array(c+1-d.length).join(0);*/
}

String.prototype.formatCurrency = formatamoney;

function demaskvalue(valor, currency, qtd){
	/* Se currency é false, retorna o valor sem apenas com os números. Se é true, os últimos caracteres são considerados as casas decimais	*/
	var val2 = '', val3='';
	var strCheck = '0123456789';
	var len = valor.length;
	if (len==0){
		val3 = '0.';
		
		for(i=0; i<qtd;i++){
			val3	+= '0';
		}
		
		val3	=	parseFloat(val3);
		return val3;
	}

	if (currency ==true){	
		/* Elimina os zeros à esquerda a variável <i> passa a ser a localização do primeiro caractere após os zeros e */
		/* val2 contém os caracteres (descontando os zeros à esquerda) */
		
		for(var i = 0; i < len; i++)
			if ((valor.charAt(i) != '0') && (valor.charAt(i) != ',')) break;
		
		for(; i < len; i++){
			if (strCheck.indexOf(valor.charAt(i))!=-1) val2+= valor.charAt(i);
		}

		if(val2.length<=qtd){
			val3 = '0.';
			for(i=val2.length;i<qtd;i++){
				val3	+= '0';
			}
			return val3 + val2;
		}else{
			var parte1 = val2.substring(0,val2.length-qtd);
			var parte2 = val2.substring(val2.length-qtd);
			var returnvalue = parte1 + "." + parte2;
		}
		return returnvalue;
		
	}
	else{
		/* currency é false: retornamos os valores COM os zeros à esquerda, sem considerar os últimos algarismos como casas decimais */
		val3 ="";
		
		for(var k=0; k < len; k++){
			if (strCheck.indexOf(valor.charAt(k))!=-1) val3+= valor.charAt(k);
		}			
		return val3;
	}
}

//qtd = qtd casas decimais
function reais(obj,event,qtd,type_val) {
	if(qtd == undefined || qtd == null) {
		qtd = 2;
	}
	
	var whichCode;
	
	if(document.all) { // Internet Explorer
		whichCode = event.keyCode;
	} else if(document.layers) { // Nestcape
		whichCode = event.which;
	} else {
		whichCode = event.which;
	}
	
	if(obj.readOnly == false){
		if(whichCode != 0 && whichCode != 8 && obj.maxLength > (Number((obj.value.replace(/[-\.]|,[\d]*/g, '').length/3).toString().replace(/[\d]*\./, '').substring(0, 1)) == 3 ? obj.value.length+1 : obj.value.length)) {
			if(type_val != undefined) {
				type_val = type_val.substring(0,3).toLowerCase() == "neg";
			} else {
				type_val = false;
			}
			
			if((/-/).test(obj.value) && whichCode != 45 && type_val && Number(obj.value.replace(/[\.,-]/g, '')) != 0) {
				var sinal = '-';
			} else {
				var sinal = '';
			}
			
			obj.value = obj.value.replace(/[\.-]/g, '');
			var temp = obj.value.split(',');
			
			if(temp[1] != undefined) {
				temp[0] = Number(temp[0]);
				
				if(temp[0] == 0) {
					temp[0] = '';
				}
				
				obj.value = temp[0]+temp[1];
			}
			
			obj.value = obj.value.replace(/,/i, '');
			var tam = obj.value.length, i;
			
			if((whichCode < 48 || whichCode > 57) && (((whichCode != 45 && type_val) || Number(obj.value) == 0) || !type_val)) {
				if(event.preventDefault) { //standart browsers
					if(whichCode != 9) {
						event.preventDefault();
					}
				} else { // internet explorer
					event.returnValue = false;
				}
				
				if(obj.value.length == qtd) {
					obj.value = '0' + obj.value;
					tam ++;
				}
			} else if(whichCode == 45 && type_val) {
				if(obj.value.length == qtd) {
					obj.value = '0' + obj.value;
					tam ++;
				}
			} else {
				tam ++;
			}
			
			if(tam > qtd) {
				obj.value = obj.value.substring(0, tam-qtd) + ',' + obj.value.substring(tam-qtd);
			} else if(tam < (qtd+1)) {
				obj.value = "0,";
				
				for(i = 1; i < qtd; i++) {
					obj.value += "0";
				}
			}
			/*
			for(tam -= (3+qtd); tam > 0; tam -= 3) {
				if(tam > 0) {
					obj.value = obj.value.substring(0, tam) + '.' + obj.value.substring(tam);
				}
			}
			*/
			obj.value = sinal + obj.value;
			
			if(!(/\d/).test(String.fromCharCode(whichCode)) && Number(obj.value.replace(/[-\.,]/g, '')) == 0) {
				obj.value = "0,";
				
				for(i = 0; i < qtd; i++) {
					obj.value += "0";
				}
			}
			
			if(whichCode == 45 && type_val)
				setPositionCursor(obj, 0, 0);
			
		} else if(obj.maxLength == (Number((obj.value.replace(/[-\.]|,[\d]*/g, '').length/3).toString().replace(/[\d]*\./, '').substring(0, 1)) == 3 ? obj.value.length+1 : obj.value.length) && (/[-\d]/).test(String.fromCharCode(whichCode))) {
			if(event.preventDefault) { //standart browsers
				if(whichCode != 9) {
					event.preventDefault();
				}
			} else { // internet explorer
				event.returnValue = false;
			}
			
			if(obj.value.length == qtd) {
				obj.value = '0' + obj.value;
			}
			
			if(!(/\d/).test(String.fromCharCode(whichCode)) && Number(obj.value.replace(/[-\.,]/g, '')) == 0) {
				obj.value = "0,";
				
				for(var i = 0; i < qtd; i++) {
					obj.value += "0";
				}
			}
		} else if(whichCode != 8 && whichCode != 0) {
			if(event.preventDefault) { //standart browsers
				if(whichCode != 9) {
					event.preventDefault();
				}
			} else { // internet explorer
				event.returnValue = false;
			}
		}
	}
} // end reais

function backspace(obj,event,qtd) {
	if(qtd == undefined) 
		qtd = 2;
	
	if(document.all) { // Internet Explorer
	   var whichCode = event.keyCode;
	} else if(document.layers) { // Nestcape
		var whichCode = event.which;
	} else {
		var whichCode = event.which;
	}
	
	if(obj.readOnly == false) {	
		/*
		*  Essa função basicamente altera o  backspace nos input com máscara reais para os navegadores IE e opera.
		*  O IE não detecta o keycode 8 no evento keypress, por isso, tratamos no keydown.
		*  Como o opera suporta o infame document.all, tratamos dele na mesma parte do código.
		**/
		
		if(whichCode == 8) { // 8 -> backspace | 46 -> delete
			if(Number(obj.value.replace(/[-\.,]/g, '')) > 0) {
				if((/-/).test(obj.value) && Number(obj.value.replace(/[-,\.]/g, '').substring(0, obj.value.replace(/[-,\.]/g, '').length-1)) > 0) {
					var sinal = '-';
				} else {
					var sinal = '';
				}
				
				var x = obj.value.substring(obj.value.length-1, obj.value.length);
				obj.value = sinal + (demaskvalue(obj.value.substring(0,obj.value.length-1),true,qtd).formatCurrency(qtd)) + x;
			} else {
				obj.value = obj.value.replace(/[-\.,]/g, '');
			}
			
			return false;
		} else if(whichCode == 46) {
			var position = getPositionCursor(obj);
			
			if(Number(obj.value.replace(/[-\.,]/g, '')) > 0) {
				var x = obj.value.substring(0, position["start"]) + obj.value.substring(position["end"]+1);
				var y = obj.value.substring(position["end"], position["end"]+1);
				
				if((/[\.,]/).test(y) && position["start"] == position["end"]) {
					if(event.preventDefault) { //standart browsers
						event.preventDefault();
					} else { // internet explorer
						event.returnValue = false;
					}
					
					return;
				}
				
				if((/-/).test(obj.value) && y != '-' && Number(obj.value.replace(y, '').replace(/[-\.,]/g, '')) > 0) {
					var sinal = '-';
				} else {
					var sinal = '';
				}
				
				if(position["start"] == position["end"]) {
//					if(Number((obj.value.replace(/[-\.]|,[\d]*/g, '').length/3).toString().replace(/[\d]*./, '').substring(0, 1)) == 3 && position["start"] > ((/-/).test(obj.value) ? 1 : 0) && obj.value.length > (3+qtd)) {
/*						position["start"]--;
						position["end"]--;
					}
					*/
					x = sinal + demaskvalue(x,true,qtd).formatCurrency(qtd);
					obj.value = x.substring(0, position["start"]) + y + x.substring(position["end"]);
				} else {
					x = obj.value.substring(0, position["start"]) + obj.value.substring(position["end"]);
					x = sinal + demaskvalue(x,true,qtd).formatCurrency(qtd);
					y = obj.value.substring(position["start"], position["end"]);
					obj.value = x.substring(0, position["start"]) + y + x.substring((position["end"]-y.length));
				}
				
				setPositionCursor(obj, position["start"], position["end"]);
			} else {
				if(((/-/).test(obj.value) ? position["start"]-1 : position["start"]) < (2+qtd) && obj.value.replace(/-/, '').length == (2+qtd)) {
					if(position["start"] == 1) {
						if(event.preventDefault) { //standart browsers
							event.preventDefault();
						} else { // internet explorer
							event.returnValue = false;
						}
						
						return;
					}
					
					if((/-/).test(obj.value)) {
						position["start"] -= 2;
						position["end"] -= 2;
					} else {
						position["start"]--;
						position["end"]--;
					}
					
					obj.value = obj.value.replace(/[-\.,]/g, '');
				}
				
				setPositionCursor(obj, position["start"], position["end"]);
			}
			
			return false;
		}// end else if	
	} else {
		return false;
	}	
}// end backspace

//fld = campo
function FormataReais(fld, milSep, decSep, event, qtd) {
	var sep = 0;
	var key = '';
	var i = j = 0;
	var len = len2 = 0;
	var strCheck = '0123456789';
	var aux = aux2 = '';
	//var whichCode = (window.Event) ? e.which : e.keyCode;
	
	if(qtd == undefined) qtd = 2;	
	
	if(document.all) { // Internet Explorer
	    whichCode = event.keyCode;
	} else if(document.layers) { // Nestcape
	    whichCode = event.which;
	} else {
	    whichCode = event.which;
	}
	
	//if (whichCode == 8 ) return true; //backspace - estamos tratando disso em outra função no keydown
	if (whichCode == 0  ) return true;
	if (whichCode == 9  ) return true; //tecla tab
	if (whichCode == 13 ) return true; //tecla enter
	if (whichCode == 16 ) return true; //shift internet explorer
	if (whichCode == 17 ) return true; //control no internet explorer
	if (whichCode == 27 ) return true; //tecla esc
	if (whichCode == 34 ) return true; //tecla end
	if (whichCode == 35 ) return true; //tecla end
	if (whichCode == 36 ) return true; //tecla home
	
	/*	O trecho abaixo previne a ação padrão nos navegadores. Não estamos inserindo o caractere normalmente, mas via script 	*/
	if (event.preventDefault){ //standart browsers
		event.preventDefault()
	}else{ // internet explorer
		event.returnValue = false
	}
	
	var key = String.fromCharCode(whichCode);  // Valor para o código da Chave
	if (strCheck.indexOf(key) == -1) return false;  // Chave inválida
	
	/*	Concatenamos ao value o keycode de key, se esse for um número	*/
	fld.value += key;
	
	var len = fld.value.length;
	var bodeaux = demaskvalue(fld.value,true, qtd).formatCurrency(qtd);
	fld.value=bodeaux;
	
	/* 	Essa parte da função tão somente move o cursor para o final no opera. Atualmente não existe como movê-lo no konqueror.	*/
	if (fld.createTextRange) {
	    var range = fld.createTextRange();
	    range.collapse(false);
	    range.select();
	}
	else if (fld.setSelectionRange) {
	    fld.focus();
	    var length = fld.value.length;
	    fld.setSelectionRange(length, length);
	}
	return false;

}
