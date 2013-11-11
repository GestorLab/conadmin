<?
	$localModulo	=	1;
	
	if($Local == 'formulario'){			
		
		$sql	=	"SELECT (max(IdCabo)+1) IdCabo FROM Cabo where IdLoja = $local_IdLoja";
		$res	=	mysql_query($sql,$con);
		$lin	=	mysql_fetch_array($res);
		
		if($lin[IdCabo] == ''){
			$lin[IdCabo] = 1;
		}
		
		$sql = "INSERT INTO
					Cabo
				SET
					IdLoja			= $local_IdLoja,
					IdCabo			= $lin[IdCabo],
					IdTipoCabo		= '$local_TipoCabo',
					Especificacao	= '$local_Especificacao',
					Cor				= '$local_Cor',
					EspessuraVisual	= '$local_EspessuraVisual',
					Opacidade		= $local_Opacidade,					
					Oculto			 = '$local_Oculto',
					QtdFibra		= '$local_QtdFibra',
					DataInstalacao	= '$local_DataInstalacao',
					LoginCriacao	= '$local_Login',
					DataCriacao		= '".date("Y/m/d h:i:s")."'";
		if(mysql_query($sql,$con) == true){						
			$local_Acao 		= 'alterar';	// Desabilita o inserir e Habilita alterar e excluir
			$local_Erro 		= 3;			// Mensagem de Inserчуo Positiva
			$local_IdCabo 		= $lin[IdCabo];
		}else{
			// Muda a aчуo para Inserir
			$local_Acao = 'inserir';
			$local_Erro = 1;			// Mensagem de Inserчуo Negativa
		}		
	}else{
		include ('../../../../files/conecta.php');
		include ('../../../../files/funcoes.php');
		include ('../funcoes.php');
		include ('../../../../rotinas/verifica.php');
		inserirCaboGeo();
	}

	
	function inserirCaboGeo(){
		global $con;
		global $_GET;
		
		$local_Login		= $_SESSION["Login"];
		$local_IdLoja		= $_SESSION["IdLoja"];	
		
		$Coordenadas		= formataCoordenadas($_GET['Coordenadas'],'');		
		$Coordenadas		= explode(",",$Coordenadas);
		$IdCabo				= $_GET['IdCabo'];
		
		//Adiciona novo cabo se nуo existir nenhum.
		$sql = "SELECT 
					IdCabo,
					IdTipoCabo 
				FROM
					Cabo
				WHERE
					IdCabo = $IdCabo";
		$res = mysql_query($sql, $con);
		$numCabo = mysql_num_rows($res);
		
		
		if($numCabo == 0){
			$sqlSiglaCabo = "SELECT
								SiglaCaboTipo
							FROM 
								CaboTipo
							WHERE IdCaboTipo = 1";
			$resSiglaCabo = mysql_query($sqlSiglaCabo,$con);
			$linSiglaCabo = mysql_fetch_array($resSiglaCabo);
				
			$sql = "INSERT INTO
						Cabo
					SET 
						IdLoja 			= $local_IdLoja,
						IdCabo			= $IdCabo,
						IdTipoCabo 		= 1,
						NomeCabo		= '$linSiglaCabo[SiglaCaboTipo]-$IdCabo',
						Especificacao 	= 'Automсtico',
						QtdFibra 		= '',
						Oculto			= 2,
						DataInstalacao	= '".date("Y-m-d")."',
						Cor				= '#000',
						EspessuraVisual = '".getCodigoInterno(67,2)."',
						LoginCriacao	= '".$local_Login."',
						DataCriacao		= '".date("Y-m-d H:i:s")."'";
			mysql_query($sql) or die("Nуo foi possэvel adicionar o cabo.\n".mysql_error());
		}		
		
		
		//Procurar informaчѕes de poste por Latitude.		
		$sqlPoste = "SELECT
						IdPoste,
						IdTipoPoste
					FROM 
						Poste
					WHERE 
						Latitude LIKE '%".$Coordenadas[0]."%'";	
		$resPoste = mysql_query($sqlPoste,$con) or die(mysql_error());
		if($linPoste = mysql_fetch_array($resPoste)){
		
			//Adiciona Cabos de acordo com o ponto de passagem.
			$sql	=	"select (max(IdPontoPassagem)+1) IdPontoPassagem from CaboPontoPassagem where IdLoja = $local_IdLoja and IdCabo = $IdCabo";
			$res	=	mysql_query($sql,$con);
			$lin	=	mysql_fetch_array($res) or die(mysql_error());
			
		
			if($lin[IdPontoPassagem] == ""){
				$lin[IdPontoPassagem] = 1;
			}
			
	
			$sql = "INSERT INTO
						CaboPontoPassagem
					SET 
						IdLoja			= $local_IdLoja,
			            IdCabo			= $IdCabo,
			            IdPontoPassagem = $lin[IdPontoPassagem],
			            IdPoste			= $linPoste[IdPoste]";
			mysql_query($sql,$con) or die(mysql_error());
			
			//Adiciona mais um para um poste temp.
			$lin[IdPontoPassagem] ++;			
			
			//Verifica se existe um ultimo poste e se nao existe ele adiciona um temp.
			$sql = "SELECT
						IdLoja,
						IdCabo,
						IdPontoPassagem,
						IdPoste
					FROM 
						CaboPontoPassagem
					WHERE
						IdLoja = $local_IdLoja 
						AND IdCabo = $IdCabo";
			$res = mysql_query($sql,$con);
			$count = mysql_num_rows($res);
		
			if($count >= 2 && $count % 2 == 0){
				$sql = "INSERT INTO
							CaboPontoPassagem
						SET 
							IdLoja			= $local_IdLoja,
				            IdCabo			= $IdCabo,
				            IdPontoPassagem = $lin[IdPontoPassagem],
				            IdPoste			= $linPoste[IdPoste]";
				mysql_query($sql,$con) or die(mysql_error());
				
			}
		}
	}

	
?>