<?
	$localModulo	=	1;

	include ('../../../../files/conecta.php');
	include ('../../../../files/funcoes.php');
	include ('../../files/funcoes.php');
	include ('../../../../rotinas/verifica.php');


	$local_Login		= $_SESSION["Login"];
	$local_IdLoja		= $_SESSION["IdLoja"];	
	
	$Coordenadas		= $_GET['Coordenadas'];		
	$TipoPoste			= $_GET['TipoPoste'];		
	
	$Latitude 	= "";
	$Longitude 	= "";
	
	$sqlTipoPoste = "SELECT
						IdPosteTipo,
						DescricaoPosteTipo,
						SiglaPosteTipo,
						IdIconePosteTipo,
						LoginAlteracao,
						DataAlteracao
					FROM
						PosteTipo
					WHERE
						IdPosteTipo = $TipoPoste";
	$resTipoPoste 	= mysql_query($sqlTipoPoste,$con);
	$linTipoPoste	= mysql_fetch_array($resTipoPoste);
	$NomePoste		=  $linTipoPoste[SiglaPosteTipo];
	
	
	$Coordenadas = str_replace("(","",$Coordenadas);
	$Coordenadas = str_replace(")","",$Coordenadas);
	$Coordenadas = explode(",",$Coordenadas);
	
	if($Coordenadas != ""){
		$Latitude 	=  $Coordenadas[0];
		$Longitude 	=  $Coordenadas[1];			
	}
	
	$sql	=	"select (max(IdPoste)+1) IdPoste from Poste where IdLoja = $local_IdLoja";
	$res	=	mysql_query($sql,$con);
	$lin	=	mysql_fetch_array($res);

	if($lin[IdPoste] == ""){
		$lin[IdPoste] = 1;
	}
	
	$sqlIdPoste = "SELECT 
						IdPoste 
					FROM
						Poste
					WHERE
						IdLoja = $local_IdLoja 
						AND IdTipoPoste = $TipoPoste";
	$resIdPoste = mysql_query($sqlIdPoste,$con);
	$IdNomePoste = mysql_num_rows($resIdPoste)+1;
		
	
	switch($Local){
		case 'formulario':
			$sql = "INSERT INTO
					Poste
				SET 
					IdLoja			= $local_IdLoja,
					IdPoste 		= $lin[IdPoste],
					IdTipoPoste 	= $TipoPoste,
					NomePoste 		= '$NomePoste-$IdNomePoste',
					DescricaoPoste 	= '',
					IdPais 			= ".retornaLocal('Pais').",
					IdEstado 		= ".retornaLocal('Estado').",
					IdCidade 		= ".retornaLocal('Cidade').",
					Endereco		= '',
					Numero			= '',
					Bairro			= '$local_Bairro',
					Complemento		= '',
					Cep				= '',
					Latitude		= '$Latitude',
					Longitude		= '$Longitude',
					Oculto			= '2',
					LoginCriacao	= '$local_Login',
					DataCriacao		= '".date('Y-m-d h:i:s')."'";
			mysql_query($sql,$con) or die("Inserir Poste Formulario: ".mysql_error());
			break;
		default:
			$sql = "INSERT INTO
						Poste
					SET 
						IdLoja			= $local_IdLoja,
						IdPoste 		= $lin[IdPoste],
						IdTipoPoste 	= $TipoPoste,
						NomePoste 		= '$NomePoste-$IdNomePoste',
						DescricaoPoste 	= '',
						IdPais 			= ".retornaLocal('IdPais').",
						IdEstado 		= ".retornaLocal('IdEstado').",
						IdCidade 		= ".retornaLocal('IdCidade').",
						Endereco		= '',
						Numero			= '',
						Complemento		= '',
						Cep				= '',
						Latitude		= '$Latitude',
						Longitude		= '$Longitude',
						LoginCriacao	= '$local_Login',
						DataCriacao		= '".date('Y-m-d h:i:s')."'";
			mysql_query($sql,$con) or die("Inserir Poste: ".mysql_error());
			break;
	}

?>