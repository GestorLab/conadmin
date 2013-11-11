	function agrid(largura,destaque){
		if(document.getElementById('grid') == undefined){				
			var novo 			= document.getElementById('ctt_l1').insertCell(1);
			novo.id				= 'grid';
			novo.style.width	= largura;
			novo.vAlign			= 'top';			
		}else{
			var novo 			= document.getElementById('ctt_l1').deleteCell(1);
		}
		
		switch(destaque){
			case 'poste1':
				novo.innerHTML = '<table id="poste" class="tableListar" heigth="20" border="0" cellpadding="0" cellspacing="0" width="100%"></table>';
				listar_poste(1);
				break;
			case 'poste2':
				novo.innerHTML = '<table id="poste" class="tableListar" heigth="20" border="0" cellpadding="0" cellspacing="0" width="100%"></table>';
				listar_poste(2);
				break;
		}
	}
	
	function menuDestaqueIcone(campo,modo){
		if(modo == 1){
			document.getElementById(campo).style.borderColor = "#78AEE5";
			document.getElementById(campo).style.backgroundColor = "#D4E5F5";
		}else{
			document.getElementById(campo).style.borderColor = "#D4D4D0";
			document.getElementById(campo).style.backgroundColor = null;
		}
	}

	
	function repassaMetodo(Funcao){
		switch(Funcao){
			case 'Selecao':
				//carregando(true);
				location.replace('index.php?zoom='+map.getZoom()+'&Coordenadas='+Coordenadas);
				break;
			case 'Poste1':
				google.maps.event.addDomListener(map, 'click', addPoste1);
				break;
			case 'Poste2':
				google.maps.event.addDomListener(map, 'click', addPoste2);
				break;
			case 'Cabo':
				//carregando(true);
				location.replace('index.php?Funcao=Cabo&zoom='+map.getZoom()+'&Coordenadas='+Coordenadas);
				break;
		}		
	}
	
	function validar_Data(valor,campo){
		var color = "#000";
		
		if(!isData(valor) && valor != '') {
			document.getElementById(campo).style.backgroundColor = '#C10000';
			document.getElementById(campo).style.color = '#FFF';
			mensagens(27);
			return false;
		} else {
			document.getElementById(campo).style.backgroundColor='#FFFFFF';
			document.getElementById(campo).style.color = color;
			mensagens(0);
			return true;
		}	
	}
	
	function carregando(caso){
		if(caso == true){
			document.getElementById('carregando').style.display = 'block';
		}else{
			document.getElementById('carregando').style.display = 'none';
		}
	}