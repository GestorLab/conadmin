function JsHttp(urladress){
	var is_protocol_ok=urladress.indexOf('http://');
	var is_dot_ok=urladress.indexOf('.');
	if(urladress==''){
		return false;
	}
	if(is_protocol_ok == -1){
		is_protocol_ok=urladress.indexOf('HTTP://');
	}
	if ((is_protocol_ok==-1) || (is_dot_ok==-1)) { 
	  alert('A URL deve começar com o http://');
	}else
		window.open(''+urladress+'');
}
