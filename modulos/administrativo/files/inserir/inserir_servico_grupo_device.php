<?php
include ('../../../../files/conecta.php');
include ('../../../../files/funcoes.php');
include ('../../../../rotinas/verifica.php');

//print_r($_POST['data']);die;
$IdLoja = $_SESSION['IdLoja'];
$IdGrupoDevice = $_POST['IdGrupoDevice'];
$IdServico = $_POST['IdServico'];

if($_POST['operacao'] == 'I'){
	if(permissaoSubOperacao($localModulo,$localOperacao,$_POST['operacao']) == false){
		$local_Erro = 2;
	}else{
		$sql = "INSERT INTO ServicoGrupoDevice SET
					IdLoja						= $IdLoja,
					IdServico					= $IdServico,
					IdGrupoDevice       		= $IdGrupoDevice";
		//echo $sql;die;
		
		if(mysql_query($sql,$con) == true){
			echo 3;
		}else{
			if(mysql_errno($con) == 1062){
				echo 60;
			}else{
				echo 8;
			}
		}
		
		
	}
}else if($_POST['operacao'] == 'D'){
	if(permissaoSubOperacao($localModulo,$localOperacao,$_POST['operacao']) == false){
		$local_Erro = 2;
	}else{
		$sql = "DELETE FROM ServicoGrupoDevice Where IdLoja = $IdLoja AND IdGrupoDevice = $IdGrupoDevice AND IdServico = $IdServico";
		//echo $sql;die;
		if(mysql_query($sql,$con) == true){
			echo 7;
		}else{
			echo 6;
		}
	}
}

	