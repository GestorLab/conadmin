<?php
	//Classe Para Gerar o Mapa
	class GeraMapa{
		public function iniciaMapa(){
			
			$local_IdLoja	= $_SESSION["IdLoja"];
			$Local			= "";
			$Coordenadas	= "";
			$zoom			= "";
			
			if($_GET['Coordenadas'] != ""){
				$Coordenadas 	= formataCoordenadas($_GET['Coordenadas'],'');		
			}else{
				$Coordenadas	= formataCoordenadas($_POST['Coordenadas'],'');
			}
			
			if($_GET['zoom'] == ""){
				$zoom 	= getCodigoInterno(67,1);
			}else{
				$zoom	= $_GET['zoom'];
			}
			
			//Inicio do JavaScript para montar o mapa.
			echo "	
			<script>
				var Coordenadas	= '$Coordenadas';
				var poly;
				var map			= '';
				var markers		= [];
				var LocalMapa
				
				function iniciaMapa(){
					local = '".retornaLocal('')."';
					geocoder.geocode( { 'address': local}, function(results, status){
				    if (status == google.maps.GeocoderStatus.OK){
						document.formulario.Coordenadas.value = results[0].geometry.location;				   		
						document.formulario.submit();											
				    }else{
				   		alert('Verifique a referência informada \"'+local+'\" :'+status+' Encontrados');
						return false;
				    }
				  });
				}
				
				function geoRede(){";
				
			if($Coordenadas != ""){		
				echo 	  "var local = new google.maps.LatLng($Coordenadas);";
				$Local	= "center: local,";
			}
			
			echo "
				var mapOptions = {
					zoom: $zoom,
					$Local
					mapTypeId: google.maps.MapTypeId.ROADMAP,
				    mapTypeControl: true,
					scaleControl: false,		   
				    panControl: true,
				    panControlOptions: {
				        position: google.maps.ControlPosition.RIGHT_BOTTOM  
				    },
				    zoomControl: true,
				    zoomControlOptions: {
				        style: google.maps.ZoomControlStyle.LARGE,
				        position: google.maps.ControlPosition.RIGHT_BOTTOM 
				    }			    
				};
				
				
				map = new google.maps.Map(document.getElementById('mapa'), mapOptions);";
		}
		
		public function fimMapa(){
			echo "
				//Default cabo ao adicionar.
				var Default = [];
				var DefaultOptions = {
					path: Default,
					strokeColor: '".getCodigoInterno(67,3)."',
					strokeOpacity: 1.0,
					strokeWeight: ".getCodigoInterno(67,2).",
					editable: false,
					zIndex: 5,
					clickable: true,
					geodesic: true
				};	
				poly = new google.maps.Polyline(DefaultOptions);
				poly.setMap(map);		     
				google.maps.event.addListener(map, 'mousedown', function(e) { Coordenadas = e.latLng;});
			}				
			</script>";				
		}
		
		//Gerar Postes.
		public function geraPoste(){
			global $con;		
	
			$Funcao			= $_GET['Funcao'];			
			$local_IdLoja	= $_SESSION["IdLoja"];
			
			$sqlIdPoste = "SELECT
								IdPoste,
								IdTipoPoste
							FROM 
								Poste";
			$resIdPoste = mysql_query($sqlIdPoste,$con);
			while($linIdPoste = mysql_fetch_array($resIdPoste)){
				$sql	=	"SELECT 
								Poste.IdTipoPoste,
								Poste.NomePoste,
								PosteTipo.IdIconePosteTipo,
								Poste.DescricaoPoste,
								Poste.Latitude,
								Poste.Longitude,
								Poste.Oculto
							FROM
								PosteTipo,
								Poste
							WHERE
								IdLoja = $local_IdLoja 
								AND Poste.IdTipoPoste = $linIdPoste[IdTipoPoste]
								AND PosteTipo.IdPosteTipo = Poste.IdTipoPoste ";
				$res	=	mysql_query($sql,$con);	
				$count	= 	mysql_num_rows($res);
				$n = 1;
				while($linPoste	=	@mysql_fetch_array($res)){						
					if($linPoste[Oculto] != 1){					
						echo "						
						var myLatLng = new google.maps.LatLng($linPoste[Latitude],$linPoste[Longitude]);
						var Marker$n = new google.maps.Marker({
							position: myLatLng,
							map: map,
							icon: '".geraIcone($linPoste[IdIconePosteTipo])."',
							draggable: false,
						});
						";
						echo " 
						
						//Gerar Titulo
						var latLngControl$n = new LatLngControl(map,'$linPoste[NomePoste]');   
						
						google.maps.event.addListener(Marker$n, 'mouseover', function(mEvent) {
				          latLngControl$n.set('visible', true);
				        });\n
						
				        google.maps.event.addListener(Marker$n, 'mouseout', function(mEvent) {
				          latLngControl$n.set('visible', false);
				        });\n
						
				        google.maps.event.addListener(Marker$n, 'mousemove', function(mEvent) {
				          latLngControl$n.updatePosition(mEvent.latLng);
				        });\n";
						
						if($Funcao == 'Cabo' && $count > 1){					
							echo "google.maps.event.addListener(Marker$n, 'click', addCabo);\n";
						}
						
						if($Funcao == ""){
							echo " google.maps.event.addListener(Marker$n, 'click', function(e) { var infoBox = new InfoBox({latlng: Marker$n.getPosition(), map: map},'$linPoste[DescricaoPoste]');});\n";
						}					
						$n++;
					}
				}	
			}	
		}
		
		//Gerar cabos.
		public function geraCabo(){
			global $con;
			
			$local_IdLoja	= $_SESSION["IdLoja"];
			
			$sql	=	"SELECT 
							Cabo.IdCabo,
							Cabo.IdTipoCabo,
							Cabo.Especificacao,
							Cabo.Cor,
							Cabo.EspessuraVisual,
							Cabo.Opacidade,
							Cabo.NomeCabo,
							Cabo.Oculto CaboOculto,
							CaboTipo.Oculto CaboTipoOculto,
							CaboTipo.SiglaCaboTipo
						FROM
							Cabo,
							CaboTipo 
						WHERE 
							Cabo.IdLoja = $local_IdLoja
							AND CaboTipo.IdLoja = Cabo.IdLoja
							AND CaboTipo.IdCaboTipo = Cabo.IdTipoCabo";
			$res	=	mysql_query($sql,$con);
			
			$i = 0;
			while($lin = mysql_fetch_array($res)){	
				$sqlCabo =	"SELECT 
								IdCabo,
								IdPontoPassagem,
								IdPoste 
							FROM
								CaboPontoPassagem 
							WHERE
								IdCabo = $lin[IdCabo]
							ORDER BY IdPontoPassagem ASC";	
				$resCabo = mysql_query($sqlCabo,$con) or die(mysql_error());
				
				$Cabo = "
				//Gerar cabo
				var CaboCoordenadas$i = [";
				while($linCabo = mysql_fetch_array($resCabo)){		
					$Cabo .= "\n		new google.maps.LatLng(".coordenadasPoste($linCabo[IdPoste])."),";	
				}			
				
				if($lin[Opacidade] == 0 || $lin[Opacidade] == 10){
					$lin[Opacidade] = '1.0';
				}else{
					$lin[Opacidade] = "0.".$lin[Opacidade];
				}
				
				if($lin[CaboOculto] == 1 || $lin[CaboTipoOculto] == 1){
					$lin[EspessuraVisual] = 0;
				}
				$Cabo .= "];
				
				var CaboOpcoes$i = {
					path: CaboCoordenadas$i,
					strokeColor: '$lin[Cor]',
					strokeOpacity: $lin[Opacidade],
					strokeWeight: $lin[EspessuraVisual],
					zIndex: 5,
					clickable: true,
					geodesic: true
				};\n
				
				poly$i = new google.maps.Polyline(CaboOpcoes$i);
				poly$i.setMap(map);\n		
				
				//Gerar um titulo para os objetos.
				var latLngControl$i = new LatLngControl(map,'$lin[NomeCabo]');   
				
				google.maps.event.addListener(poly$i, 'mouseover', function(mEvent) {
		          latLngControl$i.set('visible', true);
		        });\n
				
		        google.maps.event.addListener(poly$i, 'mouseout', function(mEvent) {
		          latLngControl$i.set('visible', false);
		        });\n
				
		        google.maps.event.addListener(poly$i, 'mousemove', function(mEvent) {
		          latLngControl$i.updatePosition(mEvent.latLng);
		        });\n
			
				";
				echo $Cabo;
				$i++;	
			}		
		}
			
	}
	
	
?>