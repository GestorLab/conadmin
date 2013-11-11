	function listar_radius(e){
		var e = e || event;
		var k = e.keyCode || e.which;
		if (k==13){
			if(document.filtro.filtro_servidor.value != "" &&  document.filtro.filtro_mes_referencia.value!=""){
				document.filtro.submit();
			}else{
				alert("Atencao!\nPreencha os campos do filtro.");
				return false;
			}
		} 
	}
	function validar(){
		if(document.filtro.filtro_servidor.value != "" && document.filtro.filtro_mes_referencia.value!=""){
			document.filtro.submit();
		}else{
			alert("Atencao!\nPreencha os campos do filtro.");
			return false;
		}
	}