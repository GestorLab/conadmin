<?
	function tipo_pessoa($campo){
		$campo	=	str_replace(".", "", $campo);
		$campo	=	str_replace("-", "", $campo);
		$campo	=	str_replace("/", "", $campo);
		
		$tam	=	strlen($campo);
		
		switch($tam){
			case '11':	//Fisica
				$tipo	=	2;
				break;
			case '14':	//Juridica
				$tipo	=	1;
				break;
		}
		return $tipo;
	}
	function getCodigoInternoCDA($IdGrupoCodigoInterno,$IdCodigoInterno){
		global $con;
		
		$IdLoja = $_SESSION["IdLojaCDA"];
		
		if($IdLoja == ""){
			$IdLoja		= getParametroSistema(95,6);
		}
		
		$sql	= "select 
						ValorCodigoInterno 
				   from 
				   		CodigoInterno 
				   where 
				   		IdGrupoCodigoInterno=$IdGrupoCodigoInterno and 
				   		IdLoja = $IdLoja and
						IdCodigoInterno=$IdCodigoInterno;";
		$res	= mysql_query($sql,$con);
		if($lin	= @mysql_fetch_array($res)){
			return $lin[ValorCodigoInterno];
		}else{
			echo "Erro - Codigo Interno $IdGrupoCodigoInterno,$IdCodigoInterno não foi encontrado.";
		}
	}
?>
