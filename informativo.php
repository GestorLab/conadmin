<?
	$localModulo	=	1;
	$localMenu		=	true;

	include ('files/conecta.php');
	include ('files/funcoes.php');
	include ('rotinas/verifica.php');
	
	$local_IdLoja 		= $_SESSION["IdLoja"];
	$local_login 		= $_SESSION["Login"];
	$local_IdLogAcesso	= $_SESSION["IdLogAcesso"];

	$IdAviso			= $_POST[IdAviso];
	$Permicao			= 0;
	$where				= '';

	if($IdAviso != ''){
		$sql = "insert into AvisoLeitura  set
					LocalAviso = 1,
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
				LocalAviso = 1";
	$res = mysql_query($sql,$con);
	while($lin = mysql_fetch_array($res)){
		$noAviso .= $lin[IdAviso].",";
	}
	$noAviso .= "0";
/*
	include ('files/conecta_cntsistemas.php');

	// Sem conexão
	if(!$con){
		header("Location: modulos/administrativo/index.php");
	}
*/
	$sql = "select 
				IdGrupoUsuario
			from 
				UsuarioGrupoUsuario 			
			where 
				IdLoja = $local_IdLoja and 
				Login = '$local_login'";
	$res = mysql_query($sql,$con);
	while($lin = mysql_fetch_array($res)){
		if($IdGrupoUsuario == ''){
			$IdGrupoUsuario = $lin[IdGrupoUsuario];
		}else{
			$IdGrupoUsuario .= ",".$lin[IdGrupoUsuario];
		}
	}
	// Não remova a data... É fixa. Att. Douglas 10/05/2012
	$sql = "SELECT
				Aviso.IdAviso,
				Aviso.TituloAviso,
				Aviso.DataCriacao,
				Aviso.Aviso,
				Aviso.ResumoAviso,
				Aviso.Usuario,
				Aviso.IdGrupoUsuario
			FROM
				Aviso
			WHERE
				Aviso.IdLoja = $local_IdLoja and				
				Aviso.IdAvisoForma = 3 and
				(Aviso.DataExpiracao >= CONCAT(CURDATE(),' ',CURTIME()) or Aviso.DataExpiracao is NULL) and
				((Aviso.IdGrupoUsuario in ('$IdGrupoUsuario') or Aviso.IdGrupoUsuario = '' or Aviso.IdGrupoUsuario is null) and (Aviso.Usuario = '$local_login' or Aviso.Usuario = '' or Aviso.Usuario is null)) and
				Aviso.IdAviso NOT IN ($noAviso) and
				Aviso.DataCriacao >= '2012-05-01'
			ORDER BY
				IdAviso
			LIMIT 0,1";
	$res = mysql_query($sql,$con);
	$lin = mysql_fetch_array($res);
		
	if($lin[IdGrupoUsuario] != ''){
		if($lin[Usuario] != ''){
			$where = " and Usuario.Login = '$lin[Usuario]'";
		}

		$sql = "select 
					count(*) Qtd
				from 
					UsuarioGrupoUsuario, 
					GrupoUsuario, 
					Usuario
				where 
					UsuarioGrupoUsuario.IdLoja = $local_IdLoja and 
					UsuarioGrupoUsuario.IdLoja = GrupoUsuario.IdLoja and 
					UsuarioGrupoUsuario.Login = Usuario.Login and 
					UsuarioGrupoUsuario.IdGrupoUsuario = GrupoUsuario.IdGrupoUsuario and						
					GrupoUsuario.IdGrupoUsuario = $lin[IdGrupoUsuario] and
					Usuario.Login = '$local_login' and
					Usuario.IdStatus = 1
					$where";
		$res2 = mysql_query($sql,$con);
		$lin2 = mysql_fetch_array($res2);

		if($lin2[Qtd] == 0) $Permicao = 1;
	}else{
		$Permicao = 1;
	}

	if(mysql_num_rows($res) < 1 && $Permicao == 1){
		header("Location: modulos/administrativo/index.php");
	}

	$lin[Aviso] = str_replace("\n","<BR><BR>",$lin[Aviso]);

	include("informativo_layout.php");
?>