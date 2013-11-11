<?
	$localModulo		=	1;
	$localOperacao		=	12;
	$localSuboperacao	=	"V";
	
	include ('../../../../files/conecta.php');
	include ('../../../../files/funcoes.php');
	include ('../../../../rotinas/verifica.php');
	
	$local_IdLoja = $_SESSION['IdLoja'];
	$local_Login = $_SESSION['Login'];
	$DescricaoGrupoDevice = $_POST['dados']['DescricaoGrupoDevice'];
	if(isset($_POST['dados']['IdGrupoDevice'])){
		$local_IdGrupoDevice = $_POST['dados']['IdGrupoDevice'];
	}
	if(isset($_POST['dados']['DisponivelContrato'])){
		$DisponivelContrato = $_POST['dados']['DisponivelContrato'];
	}else{
		$DisponivelContrato = 'NULL';
	}
	
	/*
	 * Cadastro de Grupo_Device
	 */
	if($_POST['bt_inserir'] == 'Cadastrar'){
		
		if(permissaoSubOperacao($localModulo,$localOperacao,"I") == false){
			$local_Erro = 2;
		}else{
			
			$sql = "SELECT (LAST_INSERT_ID(IdGrupoDevice) + 1) IdGrupoDevice FROM GrupoDevice WHERE IdLoja = $local_IdLoja ORDER BY IdGrupoDevice DESC";
			
			$res	=	mysql_query($sql,$con);
			$lin	=	@mysql_fetch_array($res);
				
			if( $lin[IdGrupoDevice] != NULL ) {
				$IdGrupoDevice	=	$lin['IdGrupoDevice'];
				
			} else {
				$IdGrupoDevice	=	1;
			}
			$sql	=	"INSERT INTO GrupoDevice SET 
						IdLoja						= $local_IdLoja,
						IdGrupoDevice				= $IdGrupoDevice, 
						DescricaoGrupoDevice		= '$DescricaoGrupoDevice',
						DisponivelContrato			=  $DisponivelContrato,
						DataCriacao					= (concat(curdate(),' ',curtime())),
						LoginCriacao				= '$local_Login',
						DataAlteracao				= NULL,
						LoginAlteracao				= NULL;";
						
			// Executa a Sql de Inserção Device
			if(mysql_query($sql,$con) == true){	
				$dataUrl['IdLoja'] = $local_IdLoja;
				$dataUrl['IdGrupoDevice'] = $IdGrupoDevice;
				$dataUrl['DisponivelContrato'] = $DisponivelContrato;
				$dataUrl['Tipo'] = 3;
				//header("Location: ../../cadastro_grupo_device.php?Tipo=3&md5(IdLoja)='".md5($local_IdLoja)."'&md5(IdGrupoDevice)='".md5($IdGrupoDevice)."'&DisponivelContrato=$DisponivelContrato");
				header("Location: ../../cadastro_grupo_device.php?" . json_encode($dataUrl));
			}else{
				$dataUrl['Tipo'] = 8;
				header("Location: ../../cadastro_grupo_device.php?" . json_encode($dataUrl));
			}
		}
	}
	
	
	/*
	 * Alteração de Grupo_Device
	 */
	elseif($_POST['bt_alterar'] == 'Alterar'){
		if(permissaoSubOperacao($localModulo, $localOperacao, "U") == false){
			$local_Erro = 2;
		}else{
			$sql = "UPDATE GrupoDevice SET
						   DescricaoGrupoDevice = '$DescricaoGrupoDevice',
						   DisponivelContrato   = $DisponivelContrato,
						   LoginAlteracao = '$local_Login',
						   DataAlteracao = CONCAT(CURDATE(), ' ', CURTIME())
					WHERE 
						   IdLoja = $local_IdLoja AND
						   IdGrupoDevice = $local_IdGrupoDevice";
			if(mysql_query($sql, $con) == true){
				$dataUrl['IdLoja'] = $local_IdLoja;
				$dataUrl['IdGrupoDevice'] = $local_IdGrupoDevice;
				$dataUrl['DisponivelContrato'] = $DisponivelContrato;
				$dataUrl['Tipo'] = 4;
				//header("Location: ../../cadastro_grupo_device.php?Tipo=3&md5(IdLoja)='".md5($local_IdLoja)."'&md5(IdGrupoDevice)='".md5($IdGrupoDevice)."'&DisponivelContrato=$DisponivelContrato");
				header("Location: ../../cadastro_grupo_device.php?" . json_encode($dataUrl));
			}else{
				$dataUrl['Tipo'] = 5;
				header("Location: ../../cadastro_grupo_device.php?" . json_encode($dataUrl));
			}
		}
	}
	
	/*
	 * Exclusão de Grupo_Device
	 */
	
	elseif($_POST['bt_excluir'] == 'Excluir'){
		//exit('akiii');
		if(permissaoSubOperacao($localModulo, $localOperacao, "D") == false){
			$local_Erro = 2;
		}else{
			$sql = "SELECT IdGrupoDevice FROM Device WHERE IdGrupoDevice = $local_IdGrupoDevice limit 1";
			//echo $sql;die;
			$res = mysql_query($sql, $con);
			$lin = @mysql_fetch_assoc($res);
			
			$sql = "DELETE FROM GrupoDevice WHERE IdLoja = $local_IdLoja AND IdGrupoDevice = $local_IdGrupoDevice";
			if($lin == null){
				if(mysql_query($sql, $con)){
					if($_POST['pageTipo'] != 'listar'){
						$dataUrl['IdLoja'] = $local_IdLoja;
						$dataUrl['IdGrupoDevice'] = $local_IdGrupoDevice;
						//$dataUrl['DisponivelContrato'] = $DisponivelContrato;
						$dataUrl['Tipo'] = 7;
						//header("Location: ../../cadastro_grupo_device.php?Tipo=3&md5(IdLoja)='".md5($local_IdLoja)."'&md5(IdGrupoDevice)='".md5($IdGrupoDevice)."'&DisponivelContrato=$DisponivelContrato");
						header("Location: ../../cadastro_grupo_device.php?" . json_encode($dataUrl));
					}else{
						echo 7;
					}
				}else{
					if($_POST['pageTipo'] != 'listar'){
						$dataUrl['Tipo'] = 6;
						header("Location: ../../cadastro_grupo_device.php?" . json_encode($dataUrl));
					}else{
						echo 6;
					}
				}
			}else{
				if($_POST['pageTipo'] != 'listar'){
					$dataUrl['Tipo'] = 33;
					$dataUrl['IdLoja'] = $local_IdLoja;
					$dataUrl['IdGrupoDevice'] = $local_IdGrupoDevice;
					$dataUrl['DisponivelContrato'] = $DisponivelContrato;
					header("Location: ../../cadastro_grupo_device.php?" . json_encode($dataUrl));
				}else{
					echo 33;
				}
			}
		}
	}
?>
