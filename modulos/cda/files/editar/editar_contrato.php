<?
	include('../../../../files/conecta.php');
	include('../../../../files/funcoes.php');
	include('../../files/funcoes.php');	
	include('../../rotinas/verifica.php');	
	
	$local_IdLoja					= $_SESSION["IdLojaCDA"];
	$local_Login					= $_SESSION["LoginCDA"];
	$local_IdPessoa					= $_SESSION["IdPessoaCDA"];
	$local_IdContrato				= formatText($_POST['IdContrato'],NULL);
	$local_AssinaturaContrato		= $_POST['ContratoAssinado'];			
	$local_IdParametroSistemaCad	= $_GET['IdParametroSistema'];
	$AceitoTermos					= false;

	$local_Erro = '';
	$sql	=	"START TRANSACTION;";
	mysql_query($sql,$con);
		
	$tr_i = 0;
	
	$sqlAux	=	"select IdServico,Obs from Contrato where IdLoja = $local_IdLoja and IdContrato = $local_IdContrato";
	$resAux	=	mysql_query($sqlAux,$con);
	$linAux	=	mysql_fetch_array($resAux);
	
	$local_IdServico	=	$linAux[IdServico];
	
	$sql2	=	"select
					ServicoParametro.IdParametroServico,
					ServicoParametro.DescricaoParametroServico,
					ServicoParametro.Editavel,
					ServicoParametro.ValorDefault,
					ServicoParametro.IdTipoTexto,
					ServicoParametro.ExibirSenha,
					ServicoParametro.Unico
				from 
					Servico,
					ServicoParametro
				where
					Servico.IdLoja	  = $local_IdLoja and
					Servico.IdLoja = ServicoParametro.IdLoja and 
					Servico.IdServico = ServicoParametro.IdServico and
					Servico.IdServico = $local_IdServico and
					ServicoParametro.IdStatus = 1 and
					ServicoParametro.VisivelCDA = 1 and
					ServicoParametro.Editavel = 1";
	$res2	=	mysql_query($sql2,$con);
	while($lin2 = mysql_fetch_array($res2)){
		$editar	=	true;
		$sql3	=	"select IdParametroServico,Valor from ContratoParametro where IdLoja = $local_IdLoja and IdContrato = $local_IdContrato and IdServico = $local_IdServico and IdParametroServico = $lin2[IdParametroServico];";
		$res3	=	mysql_query($sql3,$con);
		$lin3	=	mysql_fetch_array($res3);

		// Carrega em uma variável os parametros para ser usado em rotinas
		$ParametroValor[$lin2[IdParametroServico]] = $_POST['Valor_'.$lin2[IdParametroServico]];
		
		if($lin2[IdTipoTexto]==2 && $_POST['Valor_'.$lin2[IdParametroServico]]==""){
			$editar = false;
		}
		
		if($editar == true){
			$local_ValorParametro = trim($_POST['Valor_'.$lin2[IdParametroServico]]);
			
			if($local_ValorParametro != ""){
				if($lin2[Unico] == 1){
					$sqlUnico = "select 
									count(*) Qtd
								from 
									Contrato,
									ContratoParametro
								where 
									Contrato.IdLoja = $local_IdLoja and 
									Contrato.IdLoja = ContratoParametro.IdLoja and 
									Contrato.IdContrato = ContratoParametro.IdContrato and 
									Contrato.IdServico = ContratoParametro.IdServico and 
									Contrato.IdStatus != 1 and
									ContratoParametro.Valor = '$local_ValorParametro';";
					$resUnico = @mysql_query($sqlUnico, $con);
					$linUnico = @mysql_fetch_array($resUnico);
					
					if($linUnico[Qtd] > 0) {
						$local_Erro = 49;
						$local_transaction[$tr_i] = false;
						$tr_i++;
					}
				}
				
				if(mysql_num_rows($res3) >= 1){	
					$sql	=	"
						UPDATE ContratoParametro SET 
							Valor					='$local_ValorParametro'
						WHERE
							IdLoja					= $local_IdLoja and
							IdContrato				= $local_IdContrato and
							IdServico				= $local_IdServico and
							IdParametroServico		= $lin2[IdParametroServico];";
					$local_transaction[$tr_i]	=	mysql_query($sql,$con);
					$tr_i++;
				}else{
					$sql	=	"
						INSERT INTO ContratoParametro SET 
							Valor					='$local_ValorParametro',
							IdLoja					= $local_IdLoja,
							IdContrato				= $local_IdContrato,
							IdServico				= $local_IdServico,
							IdParametroServico		= $lin2[IdParametroServico];";
					$local_transaction[$tr_i]	=	mysql_query($sql,$con);
					$tr_i++;	
				}
			
				if($lin3[Valor] != $_POST['Valor_'.$lin2[IdParametroServico]]){
					if($lin2[IdTipoTexto] == 2){
						if($lin2[ExibirSenha] == 1){
							if($local_Obs!="")	$local_Obs	.= "\n";
							$local_Obs	.=	date("d/m/Y H:i:s")." [".$local_Login."] - Alteração do Parâmetro - $lin2[DescricaoParametroServico] [$lin3[Valor] > ".$_POST['Valor_'.$lin2[IdParametroServico]]."]";
						}else{
							if($local_Obs!="")	$local_Obs	.= "\n";
							$local_Obs	.=	date("d/m/Y H:i:s")." [".$local_Login."] - Alteração do Parâmetro - $lin2[DescricaoParametroServico]";
						}
					}else{
						if($local_Obs!="")	$local_Obs	.= "\n";
						$local_Obs	.=	date("d/m/Y H:i:s")." [".$local_Login."] - Alteração do Parâmetro - $lin2[DescricaoParametroServico] [$lin3[Valor] > ".$_POST['Valor_'.$lin2[IdParametroServico]]."]";				
					}
				}
			}
		}
	}
	
	$sql = "select
				UrlRotinaAlteracao
			from
				Servico
			where
				IdLoja = $local_IdLoja and
				IdServico = $linAux[IdServico]";
	$res = mysql_query($sql,$con);
	$lin = mysql_fetch_array($res);

	if($lin[UrlRotinaAlteracao] != ''){
		$_SESSION["IdLoja"] = $local_IdLoja;
		include("../../../administrativo/".$lin[UrlRotinaAlteracao]);
		$_SESSION["IdLoja"] = '';
	}
	
	if($local_Obs!=""){
		if($linAux[Obs]!=""){
			if($local_Obs!="")	$local_Obs	.= "\n";
			$local_Obs	.=	$linAux[Obs];
		}
	
		$sql	=	"UPDATE Contrato SET
					Obs							= '$local_Obs',
					DataAlteracao				= (concat(curdate(),' ',curtime())),
					LoginAlteracao				= '$local_Login'
				WHERE 
					IdLoja						= $local_IdLoja and
					(IdContrato = $local_IdContrato or IdContrato in (select IdContratoAutomatico from ContratoAutomatico where IdLoja = $local_IdLoja and IdContrato = $local_IdContrato))";
		$local_transaction[$tr_i]	=	mysql_query($sql,$con);
		$tr_i++;	
	}
	
	if($local_AssinaturaContrato != ""){
		$local_Obs2 = "";
		$sql3 = "SELECT
					Obs
				FROM
					Contrato
				WHERE
					IdContrato = $local_IdContrato;";
		$res = mysql_query($sql3,$con);
		$dados = mysql_fetch_array($res);
		
		$local_Obs2 .= date("d/m/Y H:i:s")." [".$local_Login."] - Aceite via CDA [".getParametroSistema(95,36)."]\n";
		$AceitoTermos = true;
		$local_Obs2 .= $dados[Obs];
		$sqlAssinatura = "UPDATE Contrato SET
							  Obs = '$local_Obs2',
							  AssinaturaContrato = '$local_AssinaturaContrato'
						  WHERE 
							  IdLoja		     = $local_IdLoja and
							 IdContrato = $local_IdContrato;";
		$local_transaction[$tr_i]	=	mysql_query($sqlAssinatura,$con);
		$tr_i++;	
	}
	
	$local_ObsAux	=	"";
	$sqlAuto	=	"select 
						ContratoAutomatico.IdContratoAutomatico,
						Contrato.IdServico,
						Contrato.IdServico, 
						Servico.DescricaoServico,
						Contrato.IdPeriodicidade,
						Contrato.Obs
					from 
						(select	ContratoAutomatico.IdContrato,	ContratoAutomatico.IdContratoAutomatico from ContratoAutomatico where ContratoAutomatico.IdLoja = $local_IdLoja and ContratoAutomatico.IdContrato = $local_IdContrato) ContratoAutomatico, 
						Contrato,
						Servico 
					where 
						Contrato.IdLoja = $local_IdLoja and 
						Contrato.IdLoja = Servico.IdLoja and
						Contrato.IdServico = Servico.IdServico and
						Contrato.IdContrato = ContratoAutomatico.IdContratoAutomatico";
	$resAuto 	= 	@mysql_query($sqlAuto,$con);
	while($linAuto = @mysql_fetch_array($resAuto)){
		$sql2	=	"select
						ServicoParametro.IdParametroServico,
						ServicoParametro.DescricaoParametroServico,
						ServicoParametro.Editavel,
						ServicoParametro.ValorDefault,
						ServicoParametro.IdTipoTexto,
						ServicoParametro.ExibirSenha
					from 
						Loja,
						Servico,
						ServicoParametro
					where
						Servico.IdLoja	  = $local_IdLoja and
						Servico.IdLoja = Loja.IdLoja and
						Servico.IdLoja = ServicoParametro.IdLoja and
						Servico.IdServico = ServicoParametro.IdServico and
						Servico.IdServico = $linAuto[IdServico] and
						ServicoParametro.IdStatus = 1 and
						ServicoParametro.VisivelCDA = 1 and
						ServicoParametro.Editavel = 1";
		$res2	=	mysql_query($sql2,$con);
		while($lin2 = mysql_fetch_array($res2)){
			$editar	=	true;
			$sql3	=	"select IdParametroServico,Valor from ContratoParametro where IdLoja = $local_IdLoja and IdContrato = $linAuto[IdContratoAutomatico] and IdServico = $linAuto[IdServico] and IdParametroServico = $lin2[IdParametroServico];";
			$res3	=	mysql_query($sql3,$con);
			$lin3	=	@mysql_fetch_array($res3);
			
			if($lin2[IdTipoTexto] == 2 && $_POST["ValorAutomatico_".$linAuto[IdServico]."_".$lin2[IdParametroServico]]==""){
				$editar	=	false;
			}
			
			if($editar == true){
				$local_ValorParametroAutomatico = trim($_POST["ValorAutomatico_".$linAuto[IdServico]."_".$lin2[IdParametroServico]]);
				if($local_ValorParametroAutomatico != ""){
					$sql4	=	"select * from ContratoParametro where IdLoja = $local_IdLoja and IdContrato = $linAuto[IdContratoAutomatico] and IdServico = $linAuto[IdServico] and IdParametroServico = $lin2[IdParametroServico];";
					$res4	=	mysql_query($sql4,$con);
				
					if(mysql_num_rows($res4) >= 1){					
						$sql	=	"
							UPDATE ContratoParametro SET 
								Valor					='$local_ValorParametroAutomatico'
							WHERE
								IdLoja 					= $local_IdLoja and
								IdContrato				= $linAuto[IdContratoAutomatico] and
								IdServico				= $linAuto[IdServico] and
								IdParametroServico		= $lin2[IdParametroServico];";
						$local_transaction[$tr_i]	=	mysql_query($sql,$con);
						$tr_i++;
					}else{
						$sql	=	"
							INSERT INTO ContratoParametro SET 
								IdLoja 					= $local_IdLoja,
								IdContrato				= $linAuto[IdContratoAutomatico],
								IdServico				= $linAuto[IdServico],
								IdParametroServico		= $lin2[IdParametroServico],
								Valor					='$local_ValorParametroAutomatico'";
						$local_transaction[$tr_i]	=	mysql_query($sql,$con);
						$tr_i++;
					}				
					if($lin3[Valor] != $local_Valor[$ii]){
						if($lin2[IdTipoTexto] == 2){
							if($lin2[ExibirSenha]==1){
								if($local_ObsAux!="")	$local_ObsAux	.= "\n";
								$local_ObsAux	.=	date("d/m/Y H:i:s")." [".$local_Login."] - Alteração do Parâmetro Serviço Auto. ($linAuto[IdServico]) - $lin2[DescricaoParametroServico] [$lin3[Valor] > ".$local_Valor[$ii]."]";
							}else{
								if($local_ObsAux!="")	$local_ObsAux	.= "\n";
								$local_ObsAux	.=	date("d/m/Y H:i:s")." [".$local_Login."] - Alteração do Parâmetro Serviço Auto. ($linAuto[IdServico]) - $lin2[DescricaoParametroServico]";
							}
						}else{
							if($local_ObsAux!="")	$local_ObsAux	.= "\n";
							$local_ObsAux	.=	date("d/m/Y H:i:s")." [".$local_Login."] - Alteração do Parâmetro Serviço Auto. ($linAuto[IdServico]) - $lin2[DescricaoParametroServico] [$lin3[Valor] > ".$local_Valor[$ii]."]";
						}
					}
				}
			}
			
			$ii++;
		}

		$sqlAux	=	"select IdServico,Obs from Contrato where IdLoja = $local_IdLoja and IdContrato = $linAuto[IdContratoAutomatico]";
		$resAux	=	mysql_query($sqlAux,$con);
		$linAux	=	@mysql_fetch_array($resAux);		
	
		$sql = "select
					UrlRotinaAlteracao
				from
					Servico
				where
					IdLoja = $local_IdLoja and
					IdServico = $linAux[IdServico]";
		$res = mysql_query($sql,$con);
		$lin = @mysql_fetch_array($res);

		if($lin[UrlRotinaAlteracao] != ''){
			include("../../../administrativo/".$lin[UrlRotinaAlteracao]);
		}
		
		if($local_ObsAux!=""){
			if($linAux[Obs]!=""){
				if($local_ObsAux!="")	$local_Obs	.= "\n";
				$local_ObsAux	.=	$linAux[Obs];
			}
			
			$sql	=	"UPDATE Contrato SET
							Obs							= '".$local_ObsAux."',
							DataAlteracao				= (concat(curdate(),' ',curtime())),
							LoginAlteracao				= '$local_Login'
						WHERE 
							IdLoja						= $local_IdLoja and
							IdContrato					= $linAuto[IdContratoAutomatico]";
			$local_transaction[$tr_i]	=	mysql_query($sql,$con);
			$tr_i++;
		}
		
	}	

	for($i=0; $i<$tr_i; $i++){	
		if($local_transaction[$i] == false){
			$local_transaction = false;				
		}
	}
		
	if($local_transaction == true || $tr_i == 0){
		$sql = "COMMIT;";
		$local_Erro = 37;			// Mensagem de Alteração Positiva
	}else{
		$sql = "ROLLBACK;";
		if($local_Erro == ''){
			$local_Erro = 38;			// Mensagem de Alteração Negativa
		}
	}
	mysql_query($sql,$con);
	if($AceitoTermos)
		header("Location: ../../menu.php?ctt=tela_aviso.php&IdParametroSistema=$local_IdParametroSistemaCad&Erro=80&IdContrato=$local_IdContrato&AceitoTermos=true");
	else
		header("Location: ../../menu.php?ctt=tela_aviso.php&IdParametroSistema=$local_IdParametroSistemaCad&Erro=$local_Erro");
?>