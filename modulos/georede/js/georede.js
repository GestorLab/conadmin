	var geocoder	 = new google.maps.Geocoder();
	
	//Inicia o sistema Georede
	function inicia(){
		// Titulo do bloco
		ativaNome('Geo Rede');
		
		// Oculta o menu
		if(window.parent.document.getElementById('menu') != undefined){
			window.parent.document.getElementById('menu').parentNode.removeChild(window.parent.document.getElementById('menu'));
			window.parent.document.getElementById('l2').cols = '100%';			
		}
		
	}
	
	//Adiciona Cabo de acordo com a Latitude e Longitude do Poste.
	function addCabo(event){
		var path = poly.getPath();
		path.push(event.latLng);	
		verificaPontoCabo(event.latLng);
	}
	
	//Adiciona Poste.
	function addPoste1(event){
		var latlng = event.latLng;
		markers.push(new google.maps.Marker({
			position: latlng, 
			map: map,
			draggable: false,
			title: SiglaPosteTipo+'-'+QtdPoste1,
			icon: "img/poste"+IdIconePosteTipo+".gif"
		}));
		inserir_poste(event.latLng);
		QtdPoste1++;		
		
	}
	
	//Adiciona Poste.
	function addPoste2(event){
		var latlng = event.latLng;
		markers.push(new google.maps.Marker({
			position: latlng, 
			map: map,
			draggable: false,
			title: SiglaPosteTipo+'-'+QtdPoste2,
			icon: "img/poste"+IdIconePosteTipo+".gif"
		}));
		inserir_poste(event.latLng);
		QtdPoste2++;		
		
	}

	//Gera endereco de acordo com Latitude e Longitude.
	function getEndereco(latlng){
		geocoder.geocode({
			latLng: latlng
	  	}, function(responses){
		    if (responses && responses.length > 0){
		    	return responses[0].formatted_address;
		    }else{
		    	return false;
		    }
	 	});
	}	
	
	//Insere no banco de dados o a derterminada Latitude e Longitude onde o poste deverá ficar.
	function inserir_poste(Coordenadas){
		var TipoPoste = document.formulario.TipoPoste.value;
		var nameNode, nameTextNode, url;		
		var xmlhttp   = false;
		
		if (window.XMLHttpRequest) { 
			// Mozilla, Safari,...
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
	   	url = "files/inserir/inserir_poste.php?Coordenadas="+Coordenadas+"&TipoPoste="+TipoPoste;
		call_ajax(url,function (xmlhttp){});
	}	
	
	//Inseri Cabo no banco de dados, de acordo com a Latitude e Longitude de um poste. (Ponto A a B)
	function inserir_cabo(Coordenadas){		
		var IdCabo = document.formulario.CaboAtual.value;
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
		
	   	url = "files/inserir/inserir_cabo.php?Coordenadas="+Coordenadas+"&IdCabo="+IdCabo;
		call_ajax(url,function (xmlhttp){});		
		
	}
	
	
	function verificaPontoCabo(Coordenadas){
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
		
	   	url = "xml/cabo_ponto.php?Coordenadas="+Coordenadas;
		call_ajax(url,function (xmlhttp){
			if(xmlhttp.responseText != 'false' && document.formulario.PermitirCabo.value != 2){				
				nameNode = xmlhttp.responseXML.getElementsByTagName("CaboAtual")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var CaboAtual = nameTextNode.nodeValue;	
				
				document.formulario.CaboAtual.value	 		 = CaboAtual;	
				//Fixar um Cabo Atual
				document.formulario.PermitirCabo.value	 	 = 1;			
				//Se voltar a ponta ele inseri de acordo com ela.
				inserir_cabo(Coordenadas);			
			}else{
				//Fixar um Cabo Atual
				document.formulario.PermitirCabo.value	 	 = 2;	
				//Se não for a ponta ele inseri de acordo com a nova.
				inserir_cabo(Coordenadas);	
			}
		});
	}

	//Inicia Google Maps
	google.maps.event.addDomListener(window, 'load', geoRede);
	//Verifica se o mapa não esta sendo utilizado e adiciona a Coordenada padrão.
	if(Coordenadas == ""){
		iniciaMapa();	
	}
	