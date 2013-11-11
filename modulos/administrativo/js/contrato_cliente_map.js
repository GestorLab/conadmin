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
		var url = "xml/contrato_cliente_map.php?"+Math.random()+buscar_filtro();
		
		call_ajax(url,function (xmlhttp){
			if(xmlhttp.responseText != "false"){
				var reg = xmlhttp.responseXML.getElementsByTagName("reg")[0];
				var nameNode = reg.getElementsByTagName("Foco")[0];
				var nameTextNode = nameNode.childNodes[0];
				var Foco = nameTextNode.nodeValue;
				var geocoder = new google.maps.Geocoder();
				
				geocoder.geocode({
						"address": Foco
					}, function(results, status){
						if(status == google.maps.GeocoderStatus.OK){
							map.setCenter(results[0].geometry.location);
							map.setZoom(6);
						}
					}
				);
				
				for(var i = 0; i < reg.getElementsByTagName("Localizacao").length; i++){
					var nameNode = reg.getElementsByTagName("Localizacao")[i].getElementsByTagName("Latitude")[0]; 
					var nameTextNode = nameNode.childNodes[0];
					var Latitude = nameTextNode.nodeValue;
					
					var nameNode = reg.getElementsByTagName("Localizacao")[i].getElementsByTagName("Longitude")[0]; 
					var nameTextNode = nameNode.childNodes[0];
					var Longitude = nameTextNode.nodeValue;
					
					var pes = reg.getElementsByTagName("Localizacao")[i].getElementsByTagName("Pessoa")[0];
					var content = {
						title: null,
						content: ""
					};
					
					for(var ii = 0; ii < pes.getElementsByTagName("IdPessoa").length; ii++){
						var nameNode = pes.getElementsByTagName("IdPessoa")[ii]; 
						var nameTextNode = nameNode.childNodes[0];
						var IdPessoa = nameTextNode.nodeValue;
						
						var nameNode = pes.getElementsByTagName("Nome")[ii]; 
						var nameTextNode = nameNode.childNodes[0];
						var Nome = nameTextNode.nodeValue;
						
						var nameNode = pes.getElementsByTagName("RazaoSocial")[ii]; 
						var nameTextNode = nameNode.childNodes[0];
						var RazaoSocial = nameTextNode.nodeValue;
						
						var nameNode = pes.getElementsByTagName("Telefone1")[ii]; 
						var nameTextNode = nameNode.childNodes[0];
						var Telefone1 = nameTextNode.nodeValue;
						
						var nameNode = pes.getElementsByTagName("Telefone2")[ii]; 
						var nameTextNode = nameNode.childNodes[0];
						var Telefone2 = nameTextNode.nodeValue;
						
						var nameNode = pes.getElementsByTagName("Telefone3")[ii]; 
						var nameTextNode = nameNode.childNodes[0];
						var Telefone3 = nameTextNode.nodeValue;
						
						var nameNode = pes.getElementsByTagName("Celular")[ii]; 
						var nameTextNode = nameNode.childNodes[0];
						var Celular = nameTextNode.nodeValue;
						
						var nameNode = pes.getElementsByTagName("Email")[ii]; 
						var nameTextNode = nameNode.childNodes[0];
						var Email = nameTextNode.nodeValue;
						
						var nameNode = pes.getElementsByTagName("Endereco")[ii]; 
						var nameTextNode = nameNode.childNodes[0];
						var Endereco = nameTextNode.nodeValue;
						
						var nameNode = pes.getElementsByTagName("Address")[ii]; 
						var nameTextNode = nameNode.childNodes[0];
						var Address = nameTextNode.nodeValue;
						
						content.content += "<div style='text-align:left;'>";
						
						if(RazaoSocial != ''){
							content.content += "Razão Social: ("+IdPessoa+") "+RazaoSocial+"<br />";
						} else{
							content.content += "Nome Pessoa: ("+IdPessoa+") "+Nome+"<br />";
						}
						
						if(Telefone1 !=  ''){
							content.content += "Fone Residencial: "+Telefone1+"<br />";
						}
						
						if(Telefone2 !=  ''){
							content.content += "Fone Comercial (1): "+Telefone2+"<br />";
						}
						
						if(Telefone3 !=  ''){
							content.content += "Fone Comercial (2): "+Telefone3+"<br />";
						}
						
						if(Celular !=  ''){
							content.content += "Celular: "+Celular+"<br />";
						}
						
						if(Email != ''){
							content.content += "E-mail: <u><a href='mailto:"+Email+"'>"+Email+"</a></u><br />";
						}
						
						content.content += "Endereco: "+Address+"<br />";
						content.content += "</div>";
						content.content += "<hr style='margin:12px 0px;' />";
					}
					
					content.content = content.content.replace(/(<hr style='margin:12px 0px;' \/>)$/i, "");
					
					if(Latitude != "" && Longitude != ""){
						content.lat = Latitude;
						content.lng = Longitude;
					} else if(Address != undefined) {
						content.address = Address;
					}
					
					map_set_marker(map, content);
				}
			}
		});
	}
	
	function filtro_buscar_servico(IdServico){
		if(IdServico == "" || IdServico == undefined){
			IdServico = 0;
		}
		
		var url = "xml/servico.php?IdServico="+IdServico+"&IdTipoServico=1";
		
		call_ajax(url, function (xmlhttp) {
			if(xmlhttp.responseText == "false"){
				document.filtro.filtro_id_servico.value				= "";
				document.filtro.filtro_descricao_id_servico.value	= "";
			} else {
				var nameNode = xmlhttp.responseXML.getElementsByTagName("IdServico")[0]; 
				var nameTextNode = nameNode.childNodes[0];
				var IdServico = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoServico")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var DescricaoServico = nameTextNode.nodeValue;
				
				document.filtro.filtro_id_servico.value				= IdServico;
				document.filtro.filtro_descricao_id_servico.value	= DescricaoServico;
				
				if(document.filtro.IdServico != undefined) {
					document.filtro.IdServico.value = "";
				}
			}
		});
	}