function isTime(hora) {
	hr  = parseInt(hora.substring(0,2)); 
	min = parseInt(hora.substring(3,5)); 

    if (hr < 0 || hr > 23){
		return false; 
	} 

	if (min < 0 || min > 59){
		return false; 
	} 
	
	return true;
}
