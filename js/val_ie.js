	//Funções auxiliares para verifica磯 de Inscri磯 Estadual -> In���o.	
	function randomiza(n){
        var ranNum = Math.round(Math.random()*n);
        return ranNum;
    }

    function mod(dividendo,divisor){
    	return Math.round(dividendo - (Math.floor(dividendo/divisor)*divisor));
    }

	function IntToChar(intt){
		var OrdZero = '0'.charCodeAt(0);
	    return String.fromCharCode(intt + OrdZero);
	}

	function removeCaracteres(text){
		var descartar = "/\. -/\. -/\. -";
		for (var i=0;i<=descartar.length;i++){
			text = text.replace(descartar.charAt(i),'');
		}
		return text; 
	}

	function isCurrentBrowser(browserName){
		if(navigator.userAgent.search(browserName) != -1)
			return true;
		else
			return false;
	}	
	//Funções auxiliares para verifica磯 de Inscri磯 Estadual -> Fim. 		
	
	//Inscrição Estadual de todos os estados 
	
	function verificaIE_AC(ie){
		ie = removeCaracteres(ie);
	    if (ie.length != 13)
	        return false;
	 
	    var b = 4, soma = 0;
	    for (var i = 0; i <= 10; i++){
	        soma += Number(ie.charAt(i)) * b;
	        --b;
	        if(b == 1){
				b = 9;
			}
	    }
	    dig = 11 - (soma % 11);
	    if(dig >= 10){
			dig = 0;
		}
	    resultado = (IntToChar(dig) == ie.charAt(11));
	    if (!resultado){
			return false;
		}
	 
	    b = 5;
	    soma = 0;
	    for(var i = 0; i <= 11; i++){
	        soma += Number(ie.charAt(i)) * b;
	        --b;
	        if (b == 1) { b = 9; }
	    }
	    dig = 11 - (soma % 11);
	    if(dig >= 10){
			dig = 0;
		}
	    if(IntToChar(dig) == ie.charAt(12)){
			return true;
		}else{
			return false;
		}
	} //AC

	function verificaIE_AL(ie){
		ie = removeCaracteres(ie);
	    if (ie.length != 9)
	        return false;	 
	    teste = (Number(ie.charAt(0))*10)+ Number(ie.charAt(1))
	    if (teste != 24)
	        return false;
	    var b = 9, soma = 0;
	    for(var i = 0; i <= 7; i++){
	        soma += Number(ie.charAt(i)) * b;
	        --b;
	    }
	    soma *= 10;
	    dig = soma - Math.floor(soma / 11) * 11;
	    if(dig == 10){
			dig = 0;
		}
	    return (IntToChar(dig) == ie.charAt(8));
	} //AL

	function verificaIE_AM(ie){
		ie = removeCaracteres(ie);
	    if (ie.length != 9)
	        return false;
	 
	    var b = 9, soma = 0;
	    for (var i = 0; i <= 7; i++) {
	        soma += Number(ie.charAt(i)) * b;
	        b--;
	    }
	    if (soma < 11){
			dig = 11 - soma;
		}else{ 
	        i = soma % 11;
	        if (i <= 1) { dig = 0; } else { dig = 11 - i; }
	    }
	    return (IntToChar(dig) == ie.charAt(8));
	} //AM

	function verificaIE_AP(ie){
		ie = removeCaracteres(ie);
	    if (ie.length != 9)
	        return false;
	 
	    teste = (Number(ie.charAt(0))*10)+ Number(ie.charAt(1))
	    if (teste != 03)
	        return false;  
	    var p = 0, d = 0, i = ie.substring(1, 8);
	    if ((i >= 3000001) && (i <= 3017000)) {
	        p =5;
	        d = 0;
	    }else if ((i >= 3017001) && (i <= 3019022)) {
	        p = 9;
	        d = 1;
	    }
	    b = 9;
	    soma = p;
	    for (var i = 0; i <= 7; i++) {
	        soma += Number(ie.charAt(i)) * b;
	        b--;
	    }
	    dig = 11 - (soma % 11);
	    if (dig == 10) {
	        dig = 0;
	    }else if (dig == 11) {
	        dig = d;
	    }
	    return (IntToChar(dig) == ie.charAt(8));
	} //AP

	function verificaIE_BA(ie){
		ie = removeCaracteres(ie);
	    if (ie.length < 8 || ie.length > 9)
	        return false;
		
		if (ie.length == 8){
		    die = ie.substring(0, 8);
		    var nro = new Array(8);
		    var dig = -1;
		    for (var i = 0; i <= 7; i++) {
		        nro[i] = Number(die.charAt(i));
		    }
			
		    var NumMod = 0;
		    if (String(nro[0]).match(/[0123458]/))
		        NumMod = 10;
		    else
		        NumMod = 11;
		    b = 7;
		    soma = 0;
			
		    for (i = 0; i <= 5; i++){
		        soma += nro[i] * b;
		        b--;
		    }
		    i = soma % NumMod;
		    if (NumMod == 10){
		        if(i == 0){
					dig = 0;
				}else{
					dig = NumMod - i;
				}
		    }else{
		        if (i <= 1){
					dig = 0;
				}else{
					dig = NumMod - i;
				}
		    }
		    resultado = (dig == nro[7]);
		    if (!resultado){
				return false;
			}
		    b = 8;
		    soma = 0;
		    for (i = 0; i <= 5; i++){
		        soma += nro[i] * b;
		        b--;
		    }
		    soma += nro[7] * 2;
		    i = soma % NumMod;
		    if (NumMod == 10){
		        if(i == 0){
					dig = 0;
				}else{
					dig = NumMod - i;
				}
		    }else{
		        if (i <= 1) { dig = 0; } else { dig = NumMod - i; }
		    }
		    return (dig == nro[6]);	
		}else{
			
			die = ie.substring(0, 9);
		    var nro = new Array(9);
		    var dig = -1;
		    for (var i = 0; i <= 8; i++) {
		        nro[i] = Number(die.charAt(i));
		    }
		    var NumMod = 0;
		    if (String(nro[0]).match(/[0123458]/))
		        NumMod = 10;
		    else
		        NumMod = 11;
		    b = 8;
		    soma = 0;
		    for (i = 0; i <= 6; i++) {
		        soma += nro[i] * b;
		        b--;
		    }
		    i = soma % NumMod;
			
			if (NumMod == 10) {
		        if (i == 0) { dig = 0; } else { dig = NumMod - i; }
			}
		    else {
		        if (i <= 1) { dig = 0; } else { dig = NumMod - i; }
		    }
		    resultado = (dig == nro[8]);
		    if (!resultado) { return false; }
		    b = 9;
		    soma = 0;
		    for (i = 0; i <= 6; i++) {
		        soma += nro[i] * b;
		        b--;
		    }
		    soma += nro[8] * 2;
			i = soma % NumMod;
			if (NumMod == 10) {
		        if (i == 0) { dig = 0; } else { dig = NumMod - i; }
		    }
		    else {
		        if (i <= 1) { dig = 0; } else { dig = NumMod - i; }
		    }
			//alert("dig: "+dig);
		    return (dig == nro[7]);	
		}
	} //BA

	function verificaIE_CE(ie){
		ie = removeCaracteres(ie);
	    if (ie.length != 9)
	        return false;
	 
	    var nro = new Array(9);
	    for (var i = 0; i <= 9; i++)
	        nro[i] = Number(ie.charAt(i));
	    b = 9;
	    soma = 0;
	    for(i = 0; i <= 7; i++){
	        soma += nro[i] * b;
	        b--;
	    }
	    resto = soma % 11;
	    dig = 11 - (resto);
	    if(resto <= 1)
	        dig = 0;
	    return (dig == nro[8]);
	} //CE

	function verificaIE_DF(ie){
		ie = removeCaracteres(ie);
	    if (ie.length != 13)
	        return false;
	 
	    var nro = new Array(13);
	    for (var i = 0; i <= 12; i++)
	        nro[i] = Number(ie.charAt(i));
	    b = 4;
	    soma = 0;
	    for (i = 0; i <= 10; i++){
	        soma += nro[i] * b;
	        b--;
	        if (b == 1)
	            b = 9;
	    }
	    dig = 11 - (soma % 11);
	    if (dig >= 10)
	        dig = 0;
	    resultado = (dig == nro[11]);
	    if (!resultado)
	        return false;  
	    b = 5;
	    soma = 0;
	    for (i = 0; i <= 11; i++) {
	        soma += nro[i] * b;
	        b--;
	        if (b == 1)
	            b = 9;
	    }
	    dig = 11 - (soma % 11);
	    if (dig >= 10)
	        dig = 0;
	    return (dig == nro[12]);
	} //DF

	function verificaIE_ES(ie){
		ie = removeCaracteres(ie);
	    if (ie.length != 9)
	        return false;
	 
	    var nro = new Array(9);
	    for (var i = 0; i <= 8; i++)
	        nro[i] = Number(ie.charAt(i)); 
	    b = 9;
	    soma = 0;
	    for (i = 0; i <= 7; i++) {
	        soma += nro[i] * b;
	        b--;
	    }
	    i = soma % 11;
	    if (i < 2)
	        dig = 0;
	    else
	        dig = 11 - i;
	    return (dig == nro[8]);
	} //ES

	function verificaIE_GO(ie){
		ie = removeCaracteres(ie);
	    if (ie.length != 9)
	        return false;

	    s = ie.substring(0, 2);
	    
	    if ((s != '10') && (s != '11') && (s != '15'))
	      return false;
	      
	    var nro = new Array(9);
	    for (var i = 0; i <= 8; i++)
	        nro[i] = Number(ie.charAt(i));
	        
	    n = Math.floor(parseInt(ie) / 10);
	    if (n == 11094402){
	        if ((nro[8] == 0) || (nro[8] == 1))
	            return true;
	    }
	    
	    b = 9;
	    soma = 0;
	    for (i = 0; i <= 7; i++) {
	        soma += nro[i] * b;
	        b--;
	    }
			    
	    i = soma % 11;
	    if (i == 0)
	        dig = 0;
	    else if (i == 1)
	      dig = ((n >= 10103105) && (n <= 10119997)) ? 1 : 0;
	    else
	        dig = 11 - i;
	    return (dig == nro[8]);
	} //GO

	function verificaIE_MA(ie){
		ie = removeCaracteres(ie);
	    if (ie.length != 9)
	      return false;
	 
	    var nro = new Array(9); 
	    for (var i = 0; i <= 8; i++)
	        nro[i] = Number(ie.charAt(i));
	    b = 9;
	    soma = 0;
	    for (i = 0; i <= 7; i++) {
	        soma += nro[i] * b;
	        b--;
	    }
	    i = soma % 11;
	    if (i <= 1)
	        dig = 0;
	    else
	        dig = 11 - i;
	    return (dig == nro[8]);
	} //MA

	function verificaIE_MG(ie){
		ie = removeCaracteres(ie);
	 
	    if (ie.length != 13)
	        return false;
	 
	    var dig1 = ie.substring(11, 12);
	    var dig2 = ie.substring(12, 13);
	    var inscC = ie.substring(0, 3) + '0' + ie.substring(3, 11);
	    var insc = inscC.split('');
	    var npos = 11;
	    var i = 1;
	    var ptotal = 0;
	    var psoma = 0;
	    while (npos >= 0) {
	        i++;
	        psoma = Number(insc[npos]) * i;  
	        if (psoma >= 10)
	            psoma -= 9;
	        ptotal += psoma;
	        if (i == 2)
	            i = 0;
	        npos--;
	    }
	    nresto = ptotal % 10;
	    if (nresto == 0)
	        nresto = 10;
	    nresto = 10 - nresto;
	    if (nresto != Number(dig1))
	        return false;
	    npos = 11;
	    i = 1;
	    ptotal = 0;
	    is=ie.split('');
	    while (npos >= 0) {
	        i++;
	        if (i == 12)
	            i = 2;
	        ptotal += Number(is[npos]) * i;
	        npos--;
	    }
	    nresto = ptotal % 11;
	    if ((nresto == 0) || (nresto == 1))
	        nresto = 11;
	    nresto = 11 - nresto;  
	    return (nresto == Number(dig2));
	} //MG

	function verificaIE_MT(ie){
		ie = removeCaracteres(ie);
	    if (ie.length > 11)
	      return false;
	 
	  	var nro = new Array(11);
	    for (var i = 0; i <= 10 - ie.length ; i++)
	        nro[i] = 0;
	    var j = 0;
	    for (i = 11 - ie.length ; i <= 10; i++)
	    	nro[i] = Number(ie.charAt(j++));
		b = 3;
		soma = 0;
		for (i = 0; i <= 9; i++) {
	    	soma += nro[i] * b;
	    	b--;
	    	if (b == 1)
	    		b = 9;
	  	}
		resto = soma % 11;
		dig = 11 - (resto);
		if (resto <= 1)
			dig = 0;
	  	return (dig == nro[10]);
	} //MT

	function verificaIE_MS(ie){
		ie = removeCaracteres(ie);
	    if (ie.length != 9)
	        return false;

	    if (ie.substring(0,2) != '28')
	        return false;
	    var nro = new Array(9);
	    for (var i = 0; i <= 8; i++)
	        nro[i] = Number(ie.charAt(i));
	    b = 9;
	    soma = 0;
	    for (i = 0; i <= 7; i++){
	        soma += nro[i] * b;
	        b--;
	    }
	    i = soma % 11;
	    if (i <= 1){
	        dig = 0;
	    }else{		
	        dig = 11 - i;
		}
	    return (dig == nro[8]);
	} //MS

	function verificaIE_PA(ie){
		ie = removeCaracteres(ie);
	    if (ie.length != 9)
	        return false;
	 
	    if (ie.substring(0, 2) != '15')
	        return false;
	    var nro = new Array(9);
	    for (var i = 0; i <= 8; i++)
	        nro[i] = Number(ie.charAt(i));
	    b = 9;
	    soma = 0;
	    for (i = 0; i <= 7; i++) {
	        soma += nro[i] * b;
	        b--;
	    }
	    i = soma % 11;
	    if (i <= 1)
	        dig = 0;
	    else
	        dig = 11 - i;
	    return (dig == nro[8]);
	} //PA

	function verificaIE_PB(ie){
		ie = removeCaracteres(ie);
	    if (ie.length != 9)
	        return false;
	 
	    var nro = new Array(9);
	    for (var i = 0; i <= 8; i++)
	        nro[i] = Number(ie.charAt(i));
	    b = 9;
	    soma = 0;
	    for (i = 0; i <= 7; i++) {
	        soma += nro[i] * b;
	        b--;  
	    }
	    i = soma % 11;
	    if (i <= 1)
	        dig = 0;
	    else
	        dig = 11 - i;
	    return (dig == nro[8]);
	} //PB

	function verificaIE_PR(ie){
		ie = removeCaracteres(ie);
	    if (ie.length != 10)
	        return false;
	 
	    var nro = new Array(10);
	    for (var i = 0; i <= 9; i++)
	        nro[i] = Number(ie.charAt(i));
	    b = 3;
	    soma = 0;
	    for (i = 0; i <= 7; i++) {
	        soma += nro[i] * b;
	        b--;
	        if (b == 1)
	            b = 7;
	    }
	    i = soma % 11;
	    if (i <= 1)
	        dig = 0;
	    else
	        dig = 11 - i;
	    resultado = (dig == nro[8]);
	    if (!resultado)
	        return false;
	    b = 4;
	    soma = 0;
	    for (i = 0; i <= 8; i++) {
	        soma += nro[i] * b;
	        b--;
	        if (b == 1)
	            b = 7;
	    }
	    i = soma % 11;
	    if (i <= 1)
	        dig = 0;
	    else
	        dig = 11 - i;
	    return (dig == nro[9]);
	} //PR

	function verificaIE_PE(ie){
		ie = removeCaracteres(ie);
	    if (ie.length == 14){
	    	var nro = new Array(14);	 
		    for (var i = 0; i <= 13; i++)
		        nro[i] = Number(ie.charAt(i));
		    b = 5;
		    soma = 0;
		    for (i = 0; i <= 12; i++) {
		        soma += nro[i] * b;
		        b--;
		        if (b == 0)
		            b = 9;
		    }
		    dig = 11 - (soma % 11);
		    if (dig > 9)
		        dig = dig - 10;
		    return (dig == nro[13]);
	    }
	    //Ro nova - IE com 9 digitos, sendo os dois �ltimos, os de verifica磯
	    else if (ie.length == 9){
			var nro = new Array(9);
			var dig;
	            
	        for (i= 0; i < 7; i++){  
            	nro[i]= Number(ie.charAt(i));                                  
            	// primeiro digito verificador da Inscricao Estadual 
            	soma1= 0;               
            	for (j = 0; j < 7; j++) {
                    soma1+= nro[j] * (8 - j);
           		}
	          	resto1= soma1 % 11;                 
	         	if (resto1== 0||resto1== 1)
	                nro[7]= 0;
	        	else
	                nro[7]= 11 - resto1;
	          
	              // segundo digito verificador da Inscricao Estadual 
	            soma2= (nro[7] * 2);                
	            for (k = 0; k < 7; k++) {
	                soma2+= nro[k] * (9 - k);
	            }
	              
	            resto2= soma2 % 11;                 
	           	if (resto2== 0||resto2== 1)
	                nro[8]= 0;
	            else
	                nro[8]= 11 - resto2;
	            dig = ""+nro[7]+nro[8];
	        }
	        return dig == ie.substring(7, 9);
	    }else
	        return false;
	} //PE

	function verificaIE_PI(ie){
		ie = removeCaracteres(ie);
	    if (ie.length != 9)
	        return false;
	 
	    var nro = new Array(9);
	    for (var i = 0; i <= 8; i++)
	        nro[i] = Number(ie.charAt(i));
	    b = 9;
	    soma = 0;
	    for (i = 0; i <= 7; i++){
	        soma += nro[i] * b;
	        b--;
	    }
	    i = soma % 11;
	    if (i <= 1)
	        dig = 0;
	    else
	        dig = 11 - i;
	    return (dig == nro[8]);
	} //PI

	function verificaIE_RJ(ie){
		ie = removeCaracteres(ie);
	    if (ie.length != 8)
	        return false;
	 
	    var nro = new Array(8);
	    for (var i = 0; i <= 7; i++)
	        nro[i] = Number(ie.charAt(i));
	    b = 2;
	    soma = 0;
	    for (i = 0; i <= 6; i++) {
	        soma += nro[i] * b;
	        b--;
	        if (b == 1)
	            b = 7;
	    }
	    i = soma % 11;
	    if (i <= 1)
	        dig = 0;
	    else
	        dig = 11 - i;
	    return (dig == nro[7]);
	} //RJ

	function verificaIE_RN(ie){
		ie = removeCaracteres(ie);
	    if (ie.length != 9 && ie.length != 10)
	        return false;	    
	    var nro = new Array(9);
	    for(var i = 0; i <= ie.length -1; i++)
	        nro[i] = Number(ie.charAt(i));
	    b = ie.length;
	    soma = 0;
	    for(i = 0; i < ie.length -1; i++){
	        soma += nro[i] * b;
	        b--;
	    }
	    soma *= 10;
	    dig = soma % 11;
	    if(dig == 10)
	        dig = 0;
	    return (dig == nro[ie.length -1]);
	} //RN

	function verificaIE_RS(ie){
		ie = removeCaracteres(ie);
	    if (ie.length != 10)
	    	return false;	 
	    	var nro = new Array(10);
	        for (var i = 0; i <= 9; i++)
	            nro[i] = Number(ie.charAt(i));
	        b = 2;
	        soma = 0;
	        for (i = 0; i <= 8; i++) {
	            soma += nro[i] * b;
	            b--;
	            if (b == 1)
	                b = 9;
	        }
	        dig = 11 - (soma % 11);
	        if (dig >= 10)
	            dig = 0;
	        return (dig == nro[9]);
	} //RS

	function verificaIE_RO(ie){
		ie = removeCaracteres(ie);
	    // ro-antiga
	    if(ie.length == 9){
			var nro = new Array(9);
			b=6;
			soma =0;	  
			for(var i = 3; i <= 8; i++){
				nro[i] = Number(ie.charAt(i));
				if( i != 8 ){
					soma = soma + ( nro[i] * b );
					b--;
				}
	     	}	  
	    	dig = 11 - (soma % 11);
	    	if (dig >= 10)
	        	dig = dig - 10;
	    	return (dig == nro[8]);
    	} //RO nova
	    else if (ie.length == 14) {
	      var nro = new Array(14);
	      b=6;
	      soma=0;
	      for(var i=0; i <= 4; i++) {
	          nro[i] = Number(ie.charAt(i));
	          soma = soma + ( nro[i] * b );
	          b--;
	      }
	  
	      b=9;
	      for(var i=5; i <= 13; i++) {
	          nro[i] = Number(ie.charAt(i));
	          if ( i != 13 ) {        
	              soma = soma + ( nro[i] * b );
	              b--;
	          }
	      }
	  
	      dig = 11 - ( soma % 11);
	      if (dig >= 10)
	          dig = dig - 10;
	      return(dig == nro[13]);
	    }
	    else
	      return false;
	} //RO
	
	function verificaIE_RR(ie){
		ie = removeCaracteres(ie);
	    if (ie.length != 9)
	        return false;
	    if (ie.substring(0,2) != '24')
	        return false;
	    var nro = new Array(9);
	    for (var i = 0; i <= 8; i++)
	        nro[i] = Number(ie.charAt(i));
	    var soma = 0;
	    var n = 0;
	    for (i = 0; i <= 7; i++)
	        soma += nro[i] * ++n;
	    dig = soma % 9;
	    return (dig == nro[8]);
	} //RR
	
	function verificaIE_SC(ie){
		ie = removeCaracteres(ie);
	    if (ie.length != 9)
	        return false;
 
	    var nro = new Array(9);
	    for (var i = 0; i <= 8; i++) nro[i] = Number(ie.charAt(i));
	    b = 9;
	    soma = 0;
	    for(i = 0; i <= 7; i++){
	        soma += nro[i] * b;
	        b--;
	    }
	    i = soma % 11;
	    if (i <= 1)
	        dig = 0;
	    else
	        dig = 11 - i;
	    return (dig == nro[8]);
	} //SC

	function verificaIE_SP(ie){
		ie = removeCaracteres(ie);
	    if (ie.length == 13 && ie.substring(0,1).toUpperCase() == 'P'){
	        s = ie.substring(1, 10);
	        var nro = new Array(12);
			
	        for (var i = 0; i < 9; i++){
	            nro[i] = Number(s.charAt(i));
			}
	        soma = (nro[0] * 1) + (nro[1] * 3) + (nro[2] * 4) + (nro[3] * 5) +
	        (nro[4] * 6) + (nro[5] * 7) + (nro[6] * 8) + (nro[7] * 10);
	       
		   	dig = soma % 11;			
	        if (dig >= 10)
	            dig = 0;
	        return (dig == nro[8]);
			
	    }else if(ie.length == 12){
	        var nro = new Array(12);
	        for (var i = 0; i <= 11; i++){
				nro[i] = Number(ie.charAt(i));
			}
		
	        soma = (nro[0] * 1) + (nro[1] * 3) + (nro[2] * 4) + (nro[3] * 5) +
	        (nro[4] * 6) + (nro[5] * 7) + (nro[6] * 8) + (nro[7] * 10);
	        
			dig = soma % 11;
			
	        if (dig >= 10)
	            dig = 0;
	        resultado = (dig == nro[8]);
	        if (!resultado)
	            return false;
	        soma = (nro[0] * 3) + (nro[1] * 2) + (nro[2] * 10) + (nro[3] * 9) +
	        (nro[4] * 8) + (nro[5] * 7) + (nro[6] * 6)  + (nro[7] * 5) +
	        (nro[8] * 4) + (nro[9] * 3) + (nro[10] * 2);
	        dig = soma % 11;
	        if (dig >= 10)
	            dig = 0;
	        return (dig == nro[11]);
   		}else
       		return false;
	} //SP

	function verificaIE_SE(ie){
		ie = removeCaracteres(ie);
	    if (ie.length != 9)
	        return false;
	 
	    var nro = new Array(9);
	    for (var i = 0; i <= 8; i++)
	        nro[i] = Number(ie.charAt(i));
	    b = 9;
	    soma = 0;
	    for (i = 0; i <= 7; i++) {
	        soma += nro[i] * b;
	        b--;
	    }
	    dig = 11 - (soma % 11);
	    if (dig >= 10)
	        dig = 0;
	    return (dig == nro[8]);
	} //SE

	function verificaIE_TO(ie){
	    // Valida磯 p/ o estado de Tocantins.
	    // - Caso possua 11 digitos, desconsidera o 3o e
	    //  4o digitos e trata como 9 digitos
	    // Tratamento para IEs com 11 digitos        
		ie = removeCaracteres(ie);
	    if (ie.length == 11)
	        ie = ie.substr(0, 2) + ie.substring(4, 11);
	    else if (ie.length != 9)
	        return false;
	 
	    var nro = new Array(9);
	    b=9;
	    soma=0;
	 
	    for (var i=0; i <= 8; i++ ){
	        nro[i] = Number(ie.charAt(i));
	        if(i != 8) {
	            soma = soma + ( nro[i] * b );
	            b--;
	        }
		}
	 
	    ver = soma % 11;
	    if(ver < 2)
	        dig=0;
	    else if ( ver >= 2 )
	        dig = 11 - ver;
	 
	    return(dig == nro[8]);
	} //TO

	function isIE(IE,Estado){
		IE = IE.toUpperCase();
		switch (Estado){
			case 'AC':			
				if (verificaIE_AC(IE) || (IE=="ISENTO" || IE=="ISENTA")){
					return true;
				}else{ 
					return false;
				}				
				break;
			case 'AL':			
				if (verificaIE_AL(IE) || (IE=="ISENTO" || IE=="ISENTA")){
					return true;
				}else{ 
					return false;
				}				
				break;
			case 'AP':			
				if (verificaIE_AP(IE) || (IE=="ISENTO" || IE=="ISENTA")){
						return true;
				}else{ 
					return false;
				}					
				break;
			case 'AM':			
				if (verificaIE_AM(IE) || (IE=="ISENTO" || IE=="ISENTA")){ 	
					return true;
				}else{ 
					return false;
				}				
				break;
			case 'BA':				
				if (verificaIE_BA(IE) || (IE=="ISENTO" || IE=="ISENTA")){ 	
					return true;
				}else{ 
					return false;
				}
				break;
			case 'CE':			
				if (verificaIE_CE(IE) || (IE=="ISENTO" || IE=="ISENTA")){ 	
					return true;
				}else{ 
					return false;
				}				
				break;
			case 'DF':				
				if (verificaIE_DF(IE) || (IE=="ISENTO" || IE=="ISENTA")){ 	
					return true;
				}else{ 
					return false;
				}				
				break;
			case 'ES':				
				if (verificaIE_ES(IE) || (IE=="ISENTO" || IE=="ISENTA")){ 	
					return true;
				}else{ 
					return false;
				}			
				break;
			case 'GO':			
				if(verificaIE_GO(IE) || (IE=="ISENTO" || IE=="ISENTA")){
					return true;
				}else{ 
					return false;
				}				
				break;
			case 'MA':				
				if (verificaIE_MA(IE) || (IE=="ISENTO" || IE=="ISENTA")){	
					return true;
				}else{
					return false;
				}			
				break;
			case 'MT':			
				if (verificaIE_MT(IE) || (IE=="ISENTO" || IE=="ISENTA")){ 	
					return true;
				}else{ 
					return false;
				}			
				break;
			case 'MS':			
				if (verificaIE_MS(IE) || (IE=="ISENTO" || IE=="ISENTA")){ 	
					return true;
				}else{
					return false;
				}				
				break;
			case 'MG':				
				if (verificaIE_MG(IE) || (IE=="ISENTO" || IE=="ISENTA")){
					return true;
				}else{
					return false;
				}							
				break;
			case 'PA':				
				if (verificaIE_PA(IE) || (IE=="ISENTO" || IE=="ISENTA")){
					return true;
				}else{
					return false;
				}				
				break;
			case 'PB':				
				if (verificaIE_PB(IE) || (IE=="ISENTO" || IE=="ISENTA")){
					return true;
				}else{
					return false;
				}				
				break;
			case 'PR':				
				if (verificaIE_PR(IE) || (IE=="ISENTO" || IE=="ISENTA")){
					return true;
				}else{
					return false;
				}				
				break;
			case 'PE':				
				if (verificaIE_PE(IE) || (IE=="ISENTO" || IE=="ISENTA")){ 	
					return true;
				}else{ 
					return false;
				}				
				break;
			case 'PI':				
				if (verificaIE_PI(IE) || (IE=="ISENTO" || IE=="ISENTA")){ 	
					return true;
				}else{ 
					return false;
				}				
				break;
			case 'RJ':			
				if (verificaIE_RJ(IE) || (IE=="ISENTO" || IE=="ISENTA")){ 	
					return true;
				}else{ 
					return false;
				}				
				break;
			case 'RN':				
				if (verificaIE_RN(IE) || (IE=="ISENTO" || IE=="ISENTA")){ 	
					return true;
				}else{ 
					return false;
				}			
				break;
			case 'RS':				
				if (verificaIE_RS(IE) || (IE=="ISENTO" || IE=="ISENTA")){ 	
					return true;
				}else{ 
					return false;
				}				
				break;
			case 'RO':				
				if (verificaIE_RO(IE) || (IE=="ISENTO" || IE=="ISENTA")){ 	
					return true;
				}else{ 
					return false;
				}			
				break;
			case 'RR':			
				if (verificaIE_RR(IE) || (IE=="ISENTO" || IE=="ISENTA")){
					return true;
				}else{ 
					return false;
				}				
				break;
			case 'SC':				
				if (verificaIE_SC(IE) || (IE=="ISENTO" || IE=="ISENTA")){
					return true;
				}else{ 
					return false;
				}	
				break;
			case 'SP':
				if (verificaIE_SP(IE) || (IE=="ISENTO" || IE=="ISENTA")){
					return true;
				}else{
					return false;
				}						
				break;
			case 'SE':				
				if (verificaIE_SE(IE) || (IE=="ISENTO" || IE=="ISENTA")){
					return true;
				}else{ 
					return false;
				}	
				break;
			case 'TO':				
				if (verificaIE_TO(IE) || (IE=="ISENTO" || IE=="ISENTA")){ 	
					return true;
				}else{
					return false;
				}
				break;
		}
	} //Funçãp de intermédio para executar as funções de determinados estados.