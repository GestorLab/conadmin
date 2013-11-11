<?php
	include ("../files/conecta.php");
	include ("../files/funcoes.php");
	
	$formDados[Login]			= trim(strtolower($_POST['Login']));
	$formDados[SenhaAtual]		= $_POST['SenhaAtual'];
	$formDados[NovaSenha]		= $_POST['NovaSenha'];
	$formDados[ConfirmaSenha]	= $_POST['ConfirmaSenha'];
	$dataAtual					= date('Y-m-d');
	
	if($formDados[Login]!='' && $formDados[SenhaAtual]!='' && $formDados[NovaSenha]!='' && $formDados[ConfirmaSenha]!=''){
		$lin = validaAutenticacaoLogin($formDados[Login], $formDados[SenhaAtual]);
		
		if($lin != false && $formDados[NovaSenha] == $formDados[ConfirmaSenha]){
			$local_NovaSenha	= $formDados[NovaSenha];
			$local_Login		= $formDados[Login];
			
			include ("../modulos/administrativo/files/editar/editar_usuario_alterar_senha_radius.php");
			include ("../files/conecta.php");
			
			$formDados[NovaSenha]= md5($formDados[NovaSenha]);
			
			$sqlSolicitaAlteraSenha = "SELECT 
											SolicitacaoAlteracaoSenha 
										FROM
											Usuario 
										WHERE
											Login = '$formDados[Login]'";
			$resSolicitaAlteraSenha = mysql_query($sqlSolicitaAlteraSenha, $con);
			$linSolicitaAlteraSenha = mysql_fetch_array($resSolicitaAlteraSenha);
			if($dataAtual == $linSolicitaAlteraSenha[SolicitacaoAlteracaoSenha]){
				$SolicitaAlteraSenha = ",SolicitacaoAlteracaoSenha	= null";
			}
		
			$sql = "UPDATE Usuario SET 				
						Password    				= '$formDados[NovaSenha]',
						LoginAlteracao				= '$formDados[Login]',
						ForcarAlteracaoSenha		= 2,
						DataAlteracao				= concat(curdate(),' ',curtime())
						$SolicitaAlteraSenha 
					WHERE
						Login					= '$formDados[Login]';";
			if(mysql_query($sql, $con)){
				header("Location: ../alterar_senha.php?Erro=4");
			}else{
				header("Location: ../alterar_senha.php?Erro=5");
			}
		}else{
			header("Location: ../alterar_senha.php?Erro=196");
		}
	}else{
		header("Location: ../alterar_senha.php?Erro=196");
	}
?>
