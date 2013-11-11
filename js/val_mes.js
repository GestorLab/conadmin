function isMes(data) {
	mes = (data.substring(0,2)); 
	ano = (data.substring(3,7)); 
	
    // verifica se o mes e valido 
	if (mes < 01 || mes > 12 ) { 
		return false;
	} 
	
	// verifica se ano é válido
	if (ano < 1000 ) { 
		return false;
	}
	
	return true;
}
