<?php
	//Formata a coordenada (-00000,00000) -> -00000,00000.	
	function formataCoordenadas($Coordenadas,$LatLng){
		$Coordenadas = str_replace(")","",$Coordenadas);
		$Coordenadas = str_replace("(","",$Coordenadas);
		switch($LatLng){
			case 'Lat':
				$Coordenadas = explode(",",$Coordenadas);
				return $Coordenadas[0];
				break;
			case 'Lng':
				$Coordenadas = explode(",",$Coordenadas);
				return $Coordenadas[1];
				break;			
			default:
				return $Coordenadas;
				break;
		}		
	}
	
	//Gerar ícone do poste.
	function geraIcone($IdPosteTipo){	
		global $con;
		
		$sqlIcone = "SELECT 
					IdPosteTipo,
					DescricaoPosteTipo,
					SiglaPosteTipo,
					IdIconePosteTipo 
				FROM
 					PosteTipo 
				WHERE 
					IdPosteTipo = $IdPosteTipo";
		$resIcone = mysql_query($sqlIcone,$con);
		$linIcone = mysql_fetch_array($resIcone);
		
		return $Icone = "img/poste$linIcone[IdIconePosteTipo].gif";
		if(is_file($Icone) == false){		
			echo "alert('O icone \"poste$linIcone[IdIconePosteTipo].gif\" não se existe!');";
		}
	}
	
	//Retorna coordenadas de poste.
	function coordenadasPoste($IdPoste){
		global $con;
		$local_IdLoja	= $_SESSION["IdLoja"];
		
		$sql = "SELECT
					IdLoja,
					IdPoste,
					Latitude,
					Longitude
				FROM 
					Poste
				WHERE
					IdLoja	= $local_IdLoja
					AND IdPoste = $IdPoste";
		$res = mysql_query($sql,$con);
		$lin = mysql_fetch_array($res);
		$LatLng = $lin[Latitude].",".$lin[Longitude];
		return $LatLng;
	}
	
	
	//Elimina Pontos de Passagem desnecessários.
	function eliminaPontoPassagemTemp(){	
		global $con;
		$IdLoja		= $_SESSION["IdLoja"];
		
		$sql = "SELECT 
					IdCabo 
				FROM
					Cabo
				WHERE
					IdLoja = $IdLoja";
		$res = mysql_query($sql,$con);
		while($lin = mysql_fetch_array($res)){		
			$sql1 = "SELECT 
						IdCabo,
						IdPontoPassagem,
						IdPoste 
					FROM
						CaboPontoPassagem
					WHERE
						IdCabo = $lin[IdCabo]
						AND IdLoja = $IdLoja";		
			$res1 = mysql_query($sql1, $con);
			$ultimoCabo = mysql_num_rows($res1);	
			
			$sql2 = "SELECT 
						IdLoja,
						MAX(IdCabo) IdCabo,
						MAX(IdPontoPassagem) IdPontoPassagem,
						IdPoste 
					FROM
						CaboPontoPassagem 
					WHERE
						IdCabo = $lin[IdCabo]
						AND IdLoja = $IdLoja";
			$res2 = mysql_query($sql2,$con);
			$lin2 = mysql_fetch_array($res2);		
			
			if($ultimoCabo % 2 != 0){	
				$sql3 = "DELETE
						FROM 
							CaboPontoPassagem
						WHERE 
							IdLoja = $IdLoja
							AND IdCabo = $lin[IdCabo]
							AND IdPontoPassagem = $lin2[IdPontoPassagem]";
				mysql_query($sql3,$con);
			}
			
		}
	}
	
	//Elimina Cabos desnecessários.
	function eliminaCabosTemp(){
		global $con;
		
		$IdLoja		= $_SESSION["IdLoja"];
		
		function temp($IdLoja,$IdCabo){
			global $con;
			
			$sql = "SELECT
						IdCabo,
						IdPontoPassagem,
						IdPoste
					FROM 
						CaboPontoPassagem
					WHERE
						IdLoja = $IdLoja
						AND IdCabo = $IdCabo";
			$res = mysql_query($sql, $con);
			$numCabo = mysql_num_rows($res);
		
			if($numCabo == 0){
				$sql = "DELETE 
						FROM  
							Cabo
						WHERE 
							IdLoja = $IdLoja
							AND IdCabo = $IdCabo";
				mysql_query($sql, $con);
			}
			
			if($numCabo == 1){				
				$sql = "DELETE 
						FROM
							CaboPontoPassagem 
						WHERE 
							IdLoja = $IdLoja 
							AND IdCabo = $IdCabo;";
				if(mysql_query($sql, $con)){
					$sql = "DELETE 
							FROM  
								Cabo
							WHERE 
								IdLoja = $IdLoja
								AND IdCabo = $IdCabo";
					mysql_query($sql, $con);
				}	
				
			}
		}
			
		$sql = "SELECT 
				IdCabo,
				IdTipoCabo 
			FROM
				Cabo
			WHERE
				IdLoja = $IdLoja";
		$res = mysql_query($sql, $con);	
		
		while($lin = mysql_fetch_array($res)){
			temp($IdLoja,$lin[IdCabo]);
		}
	
	}
	
	//Retorna o cabo de Id maior.
	function caboAtual(){
		global $con;
		$IdLoja		= $_SESSION["IdLoja"];
		
		$sql = "SELECT
					(MAX(IdCabo)+1) IdCabo,
					IdTipoCabo
				FROM
					Cabo
				WHERE
					IdLoja = $IdLoja";
		$res = mysql_query($sql,$con);
		$lin = mysql_fetch_array($res);
		
		if($lin[IdCabo] == ""){
			$lin[IdCabo] = 1;
		}
		
		return $lin[IdCabo];
	}
	
	//Verifica se poste é um ponto Inicial/Final.
	function verificaPontoCabo($IdPoste){
		global $con;
		$local_IdLoja		= $_SESSION["IdLoja"];
		
		$sql = "SELECT
					IdLoja,
					IdCabo,
					IdTipoCabo
				FROM 
					Cabo
				WHERE
					IdLoja = $local_IdLoja";
		$res = mysql_query($sql, $con);
		while($lin = mysql_fetch_array($res)){
			
			$sqlInicioPontoPassagem = "SELECT
										IdCabo,
										IdPontoPassagem,
										IdPoste
									FROM 
										CaboPontoPassagem
									WHERE
										IdLoja = $local_IdLoja
										AND IdCabo = $lin[IdCabo]
										AND IdPoste = $IdPoste";
			$resInicioPontoPassagem = mysql_query($sqlInicioPontoPassagem,$con);
			$linInicioPontoPassagem = mysql_fetch_array($resInicioPontoPassagem);
			
			$sqlFimPontoPassagem = "SELECT
									IdLoja,
									IdCabo,
									MAX(IdPontoPassagem) IdPontoPassagem,
									IdPoste
								FROM 
									CaboPontoPassagem
								WHERE
									IdCabo = $lin[IdCabo]
									AND IdPoste = $IdPoste";
			$resFimPontoPassagem = mysql_query($sqlFimPontoPassagem,$con) or die(mysql_error());
			$linFimPontoPassagem = mysql_fetch_array($resFimPontoPassagem);
		
			if($linInicioPontoPassagem[IdCabo] == $lin[IdCabo] && $linInicioPontoPassagem[IdPontoPassagem] == 1){
				return $linInicioPontoPassagem[IdCabo];	
				break;			
			}
			
			if($linInicioPontoPassagem[IdCabo] == $lin[IdCabo] && $linInicioPontoPassagem[IdPontoPassagem] == $linFimPontoPassagem[IdPontoPassagem] ){
				return $linInicioPontoPassagem[IdCabo];
				break;
			}
		}
	}
	
	//Retorna descrição do Tipo Poste.
	function retornaTipoPoste($IdTipoPoste){
		global $con;
		
		$sql = "SELECT
					IdPosteTipo,
					DescricaoPosteTipo
					FROM PosteTipo
					WHERE IdPosteTipo = $IdTipoPoste";
		$res = mysql_query($sql, $con);
		$lin = mysql_fetch_array($res);		
		return $lin[DescricaoPosteTipo];
	}
	
	//Retorna descrição do Tipo Cabo.
	function retornaTipoCabo($IdCaboTipo){
		global $con;
		$IdLoja		= $_SESSION["IdLoja"];
		
		$sql = "SELECT
					IdCaboTipo,
					DescricaoCaboTipo
				FROM 
					CaboTipo
				WHERE
					IdLoja = $IdLoja
					AND IdCaboTipo = $IdCaboTipo";
		$res = mysql_query($sql, $con);
		$lin = mysql_fetch_array($res);		
		
		return $lin[DescricaoCaboTipo];
		
	}
	
	function retornaLocal($retorno){
		global $con;
		
		$Local = getCodigoInterno(67,4);
		$Local = explode(",",$Local);
		
		$Pais 	= $Local[0];
		$Estado = $Local[1];
		$Cidade	= $Local[2];
		
		if($Pais && $Estado && $Cidade){		
			$sql = "SELECT 
						Pais.NomePais,
						Pais.IdPais,
						Estado.NomeEstado,
						Estado.IdEstado,
						Cidade.NomeCidade,
						Cidade.IdCidade
					FROM
						Pais,
						Estado,
						Cidade
					WHERE Pais.IdPais = $Pais 
						AND Estado.IdPais = Pais.IdPais 
						AND Estado.IdEstado = $Estado 
						AND Cidade.IdPais = Estado.IdPais 
						AND Cidade.IdEstado = Estado.IdEstado 
						AND Cidade.IdCidade = $Cidade";
			$res = mysql_query($sql, $con);
			$lin = mysql_fetch_array($res);	
			
			$Pais 	= $lin[NomePais];
			$Estado = $lin[NomeEstado];
			$Cidade	= $lin[NomeCidade];
			
			switch($retorno){
				case 'Pais':
					return $Pais;
					break;
				case 'IdPais':
					return $lin[IdPais];
					break;
				case 'Estado':
					return $Estado;
					break;
				case 'IdEstado':
					return $lin[IdEstado];
					break;
				case 'Cidade':
					return $Cidade;
					break;
				case 'IdCidade':
					return $lin[IdCidade];
				default:	
					return $Cidade.",".$Estado.",".$Pais;
					break;
			}			
		}else{
			echo "Erro - Codigo Interno 67,4: <b>Informe  'Pais,Estado,Cidade'</b>";
		}
		
	}		

	
?>