	function buscar_filtro(){
		var filtro = "";
		
		for(var i = 0; i < document.filtro.length; i++){
			if(document.filtro[i].name.substring(0, 7) == "filtro_"){
				filtro += "&"+document.filtro[i].name+"="+document.filtro[i].value;
			}
		}
		
		return filtro;
	}
	
	function get_lat_lng(map){
		var url = "xml/monitor_mapeamento.php?"+Math.random()+buscar_filtro();
		
		call_ajax(url,function (xmlhttp){
			if(xmlhttp.responseText != "false"){
				var reg = xmlhttp.responseXML.getElementsByTagName("reg")[0];
				
				for(var i = 0; i < reg.getElementsByTagName("IdMonitor").length; i++){
					var nameNode = reg.getElementsByTagName("IdMonitor")[i]; 
					var nameTextNode = nameNode.childNodes[0];
					var IdMonitor = nameTextNode.nodeValue;
					
					nameNode = reg.getElementsByTagName("DescricaoMonitor")[i]; 
					nameTextNode = nameNode.childNodes[0];
					var DescricaoMonitor = nameTextNode.nodeValue;
					
					nameNode = reg.getElementsByTagName("Status")[i]; 
					nameTextNode = nameNode.childNodes[0];
					var Status = nameTextNode.nodeValue;
					
					var content = {
						title: null,
						content: "<div style='margin-top:8px;'>"
					};
					content.title = DescricaoMonitor+" ("+Status+")";
					content.content += "<a href='cadastro_monitor.php?IdMonitor="+IdMonitor+"'>"+DescricaoMonitor+" ("+Status+")</a>";
					var Localizacao = reg.getElementsByTagName("Localizacao")[i];
					
					nameNode = Localizacao.getElementsByTagName("HostAddress")[0]; 
					nameTextNode = nameNode.childNodes[0];
					var HostAddress = nameTextNode.nodeValue;
					
					nameNode = Localizacao.getElementsByTagName("Porta")[0]; 
					nameTextNode = nameNode.childNodes[0];
					var Porta = nameTextNode.nodeValue;
					
					nameNode = Localizacao.getElementsByTagName("Latitude")[0]; 
					nameTextNode = nameNode.childNodes[0];
					var Latitude = nameTextNode.nodeValue;
					
					nameNode = Localizacao.getElementsByTagName("Longitude")[0]; 
					nameTextNode = nameNode.childNodes[0];
					var Longitude = nameTextNode.nodeValue;
					
					content.content += "<br /><strong>Host:</strong> "+HostAddress;
					
					if(parseInt(Porta) > 0){
						content.content += ":"+Porta;
					}
					
					content.content += "</div>";
					
					if(Latitude != "" && Longitude != ""){
						content.lat = Latitude;
						content.lng = Longitude;
						
						map_set_marker(map, content);
					}
				}
			}
		});
	}