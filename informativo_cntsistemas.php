<?
	$localModulo	=	1;
	$localMenu		=	true;

	include ('files/conecta.php');
	include ('files/funcoes.php');
	include ('rotinas/verifica.php');
	
	$local_login 		= $_SESSION["Login"];
	$local_IdLogAcesso	= $_SESSION["IdLogAcesso"];

	$IdAviso	= $_POST[IdAviso];

	if($IdAviso != ''){
		$sql = "insert into AvisoLeitura  set
					LocalAviso = 0,	
					IdAviso = $IdAviso,
					Login = '$local_login',
					IdLogAcesso = $local_IdLogAcesso,
					DataConfirmacao = concat(curdate(),' ',curtime())";
		mysql_query($sql,$con);
	}

	$noAviso = '';

	$sql = "select
				IdAviso
			from
				AvisoLeitura
			where
				Login = '$local_login' and
				LocalAviso = 0";
	$res = mysql_query($sql,$con);
	while($lin = mysql_fetch_array($res)){
		$noAviso .= $lin[IdAviso].",";
	}
	$noAviso .= "0";

	include ('files/conecta_cntsistemas.php');

	// Sem conexão
	if(!$conCNT){
		header("Location: modulos/administrativo/index.php");
	}

	// Não remova a data... É fixa. Att. Douglas 10/05/2012
	$sql = "SELECT
				IdAviso,
				TituloAviso,
				DataCriacao,
				Aviso,
				ResumoAviso
			FROM
				Aviso
			WHERE
				IdLoja = 1 and
				(DataExpiracao >= CONCAT(CURDATE(),' ',CURTIME()) 
				or DataExpiracao is NULL) AND
				IdAviso NOT IN ($noAviso) and
				DataCriacao >= '2012-05-01'
			ORDER BY
				IdAviso
			LIMIT 0,1";
	$res = mysql_query($sql,$conCNT);
	$lin = mysql_fetch_array($res);

	if(mysql_num_rows($res) < 1){
		header("Location: informativo.php");
	}

	$lin[Aviso] = str_replace("\n","<BR><BR>",$lin[Aviso]);

	include("informativo_cntsistemas_layout.php");
?>