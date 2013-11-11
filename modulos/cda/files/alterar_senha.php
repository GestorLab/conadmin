<?
	include('../../../files/conecta.php');
	include('../../../files/funcoes.php');	
	include('funcoes.php');	
	include('../rotinas/verifica.php');	
	
	$IdLoja						= getParametroSistema(95,6);
	$local_TipoPessoa			= formatText($_POST['TipoPessoa'],NULL);
	$local_SenhaAtual			= trim($_POST['SenhaAtual']);
	$local_NovaSenha			= trim($_POST['NovaSenha']);
	$local_Login				= $_SESSION["LoginCDA"];
	$local_IdPessoa				= $_SESSION["IdPessoaCDA"];
	$local_IdParametroSistema	= $_POST['IdParametroSistema'];	
	
	$sql =	"select IdPessoa, Obs, Senha from Pessoa where IdPessoa = '$local_IdPessoa'and Senha='$local_SenhaAtual';";
	$res	=	mysql_query($sql,$con);
    if($lin	=	mysql_fetch_array($res)){
		if($local_SenhaAtual != $local_NovaSenha){
			$Obs = date("d/m/Y H:i:s")." [".$local_Login."] - Alteração de Senha CDA [$local_SenhaAtual > $local_NovaSenha]";
			
		}
		
		if($lin[Obs] != ""){
			if($Obs != ""){
				$Obs .= "\n";
			}
			
			$Obs .= trim($lin[Obs]);
		}
		
		$sql2	=	"UPDATE Pessoa SET 				
						Senha	    		= '$local_NovaSenha',
						Obs	    			= '$Obs',
						LoginAlteracao		= '$local_Login',
						DataAlteracao		= concat(curdate(),' ',curtime())
					WHERE
						IdPessoa			= '$local_IdPessoa';";
		if(mysql_query($sql2,$con) == true){
			$local_Erro = 31;
			header("Location: ../menu.php?ctt=cadastro_alterado.php&IdParametroSistema=$local_IdParametroSistema&Erro=$local_Erro");
		}else{
			$local_Erro = 32;
			header("Location: ../menu.php?ctt=cadastro_alterado.php&IdParametroSistema=$local_IdParametroSistema&Erro=$local_Erro");
		}
	}else{
		$local_Erro = 30;
		header("Location: ../menu.php?ctt=cadastro_alterado.php&IdParametroSistema=$local_IdParametroSistema&Erro=$local_Erro");
	}
?>
