	var IdIconePosteTipo = "";
	var SiglaPosteTipo	 = "";
	var QtdPoste1		 = "";
	var QtdPoste2		 = "";
	
	//Retorna informações do Poste 1;
	function info_poste1(){
		var nameNode, nameTextNode, url;		
		var xmlhttp   = false;
		
		document.formulario.TipoPoste.value = 1;
		
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
	   	url = "xml/info_poste.php?TipoPoste=1";
		call_ajax(url,function (xmlhttp){
			if(xmlhttp.responseText != 'false'){		
				nameNode = xmlhttp.responseXML.getElementsByTagName("SiglaPosteTipo")[0]; 
				nameTextNode = nameNode.childNodes[0];
				SiglaPosteTipo = nameTextNode.nodeValue;	
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("IdIconePosteTipo")[0]; 
				nameTextNode = nameNode.childNodes[0];
				IdIconePosteTipo = nameTextNode.nodeValue;				
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("QtdPoste")[0]; 
				nameTextNode = nameNode.childNodes[0];
				QtdPoste1 = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("VerificaIcone")[0]; 
				nameTextNode = nameNode.childNodes[0];
				VerificaIcone = nameTextNode.nodeValue;		
				
				if(VerificaIcone == 'false'){
					alert('O icone "poste'+IdIconePosteTipo+'.gif" não existe!');
				}
			}
		})
	}
	
	//Retorna informações do Poste 2;
	function info_poste2(){
		var nameNode, nameTextNode, url;		
		var xmlhttp   = false;
		
		document.formulario.TipoPoste.value = 2;
		
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
	    url = "xml/info_poste.php?TipoPoste=2";
		call_ajax(url,function (xmlhttp){
			if(xmlhttp.responseText != 'false'){		
				nameNode = xmlhttp.responseXML.getElementsByTagName("SiglaPosteTipo")[0]; 
				nameTextNode = nameNode.childNodes[0];
				SiglaPosteTipo = nameTextNode.nodeValue;	
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("IdIconePosteTipo")[0]; 
				nameTextNode = nameNode.childNodes[0];
				IdIconePosteTipo = nameTextNode.nodeValue;				
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("QtdPoste")[0]; 
				nameTextNode = nameNode.childNodes[0];
				QtdPoste2 = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("VerificaIcone")[0]; 
				nameTextNode = nameNode.childNodes[0];
				VerificaIcone = nameTextNode.nodeValue;		
				
				if(VerificaIcone == 'false'){
					alert('O icone "poste'+IdIconePosteTipo+'.gif" não existe!');
				}
			}
		})
	}
	
	function listar_poste(IdTipoPoste){
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
		
	   	url = "xml/georede.php?IdTipoPoste="+IdTipoPoste;
		call_ajax(url,function (xmlhttp){
			if(xmlhttp.responseText != 'false'){
				var c0,c1,c2,c3,c4,c5;
				var tam,linha,i;				
		
				linha	= document.getElementById('poste').insertRow(0);

				c0	= linha.insertCell(0);
				c1	= linha.insertCell(1);
				c2	= linha.insertCell(2);
				
				c0.innerHTML = "Id";
				c1.innerHTML = "Nome Poste";
			
			
				c0.style.color = "#FFF";
				c1.style.color = "#FFF";
				
				c0.style.fontWeight = "bold";
				c1.style.fontWeight = "bold";
				
				c0.style.background = "#004492";				
				c1.style.background = "#004492";
				
				
				for(i=0;i< xmlhttp.responseXML.getElementsByTagName("IdPoste").length; i++){
					var nameNode = xmlhttp.responseXML.getElementsByTagName("IdPoste")[i]; 
					var nameTextNode = nameNode.childNodes[0];
					IdPoste = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("NomePoste")[i]; 
					nameTextNode = nameNode.childNodes[0];
					var NomePoste = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoPoste")[i]; 
					nameTextNode = nameNode.childNodes[0];
					var DescricaoPoste = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("Latitude")[i]; 
					nameTextNode = nameNode.childNodes[0];
					var Latitude = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("Longitude")[i]; 
					nameTextNode = nameNode.childNodes[0];
					var Longitude = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("Total")[i]; 
					nameTextNode = nameNode.childNodes[0];
					var Total = nameTextNode.nodeValue;
					
					tam 	= document.getElementById('poste').rows.length;
					linha	= document.getElementById('poste').insertRow(tam);
					linha.onmouseover = function(){destacaRegistro(this,true)};
					linha.onmouseout = function(){destacaRegistro(this,false)};
					
					c0	= linha.insertCell(0);	
					c1	= linha.insertCell(1);		
					
					
					c0.innerHTML = '<a href="index.php?Coordenadas='+Latitude+','+Longitude+'&zoom=21">'+IdPoste+'</a>';
					c0.className 	 = "tableListarDados";
					
					c1.innerHTML = '<a href="index.php?Coordenadas='+Latitude+','+Longitude+'&zoom=21">'+NomePoste+'</a>';
					c1.className  = "tableListarDados";
					
				}				
				linha	= document.getElementById('poste').insertRow(tam+1);	
			
				c0	= linha.insertCell(0);
				c1	= linha.insertCell(1);
				
				c0.innerHTML = "<b>Total: "+Total+"</b>";
				
				c0.style.background = "#004492";
				c1.style.background = "#004492";
				
				
				c0.style.color = "#FFF";
			}
			
			
		});
	}