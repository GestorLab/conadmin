<?
	session_start("ConAdmin_session");
	$localRetornoPrincipal	=	"../index.php";
	$localRetornoIndex		=	"../../rotinas/sair.php";
	$bloqueio 				=	true;

	// Carrega as variáveis do config
	$Vars = Vars();
	@$VarsKeys = array_keys($Vars);
	/*for($i=0; $i<count($VarsKeys); $i++){
		$_SESSION[$VarsKeys[$i]] = $Vars[$VarsKeys[$i]];
	}*/
	// Fim - Carrega as variáveis do config

	############ Verifica Licença
 	$Vars[DataLicenca] = dataConv($Vars[DataLicenca], 'Ymd', 'Y-m-d');
	$Vars[DataHoje] = date("Y-m-d");
	$Vars[DataLicenca] = date("Y-m-d");

	if($Vars[DataLicenca] != $Vars[DataHoje]){
		$nDiasIntervalo = nDiasIntervalo($Vars[DataLicenca],$Vars[DataHoje]);
		$nDiasIntervalo--;
		if($nDiasIntervalo < 0){
			$nDiasIntervalo = $nDiasIntervalo * (-1);
		}
	 	$Vars[DiasLicenca] -= $nDiasIntervalo;
		AtualizaConfig($Vars[IdLicenca], $Vars[TipoLicenca], $Vars[DiasLicenca]);

		if($Vars[DiasLicenca] <= 7){
			$KeyCode	= KeyCode($Vars[IdLicenca],1);

			$File		= @file("http://intranet.cntsistemas.com.br/licenca/licenca.php?KeyCode=$KeyCode");
			$KeyLicenca = end($File);
			KeyProcess($KeyCode, $KeyLicenca);
		}
	}
	############ Verifica Licença
	
	if($localModulo !=''){
	
		$local_login 	= $_SESSION["Login"];
		$local_IdLoja	= $_SESSION["IdLoja"];
		
		$sql = "select
				    count(*) quant
				from 
				    UsuarioSubOperacao
				where
					Login=\"$local_login\" and
				    IdLoja=$local_IdLoja and
				    IdModulo=$localModulo";
		$res = mysql_query($sql,$con);
		$lin = @mysql_fetch_array($res);
		if($lin[quant] > 0 || $local_login == 'root'){
			$bloqueio = false;	
		}
		
		$sql = "select
					count(*) quant
				from
				    UsuarioGrupoPermissao,
				    GrupoPermissaoSubOperacao
				where
				    UsuarioGrupoPermissao.Login=\"$local_login\" and
				    UsuarioGrupoPermissao.IdGrupoPermissao = GrupoPermissaoSubOperacao.IdGrupoPermissao and
			    	GrupoPermissaoSubOperacao.IdLoja = $local_IdLoja and
				    GrupoPermissaoSubOperacao.IdModulo = $localModulo";
		$res = mysql_query($sql,$con);
		$lin = @mysql_fetch_array($res);
		
		if($lin[quant] > 0){	
			$bloqueio = false;	
		}
		
		if($bloqueio == true){
			header("Location: $localRetornoPrincipal");
		}

		if(($localOperacao != '' || $localOperacao == 0) && $localSuboperacao !=''){
			if(permissaoSubOperacao($localModulo, $localOperacao, $localSuboperacao) == false){
				header("Location: sem_permissao.php");
			}
		}
	}else{
		if(!isset($_SESSION["Login"]) || !isset($_SESSION["IdLoja"])){
			session_destroy();
			header("Location: $localRetornoIndex");
		}
	}
?>