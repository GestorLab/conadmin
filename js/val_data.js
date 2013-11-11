function isData(data) {
	dia = (data.substring(0,2)); 
	mes = (data.substring(3,5)); 
	ano = (data.substring(6,10)); 

    // verifica o dia valido para cada mes 
    if ((dia < 01)||(dia < 01 || dia > 30) && (  mes == 04 || mes == 06 || mes == 09 || mes == 11 ) || dia > 31){
		return false; 
	} 

	// verifica se o mes e valido 
	if (mes < 01 || mes > 12 ) { 
		return false;
	} 
	
	// verifica se ano é válido
	if (ano < 1000 ) { 
		return false;
	}
	
	// verifica se e ano bissexto 
	if (mes == 2 && ( dia < 01 || dia > 29 || ( dia > 28 && (parseInt(ano / 4) != ano / 4)))){
		return false;
	}
	
	return true;
}
