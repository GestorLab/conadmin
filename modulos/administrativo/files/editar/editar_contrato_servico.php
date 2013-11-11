<?
	$localModulo		=	1;
	$localOperacao		=	2;
	$localModulo2		=	1;
	$localOperacao2		=	17;
	$localSuboperacao	=	"V";
	$Path				=   "../../../../";
	
	include ('../../../../files/conecta.php');
	include ('../../../../files/funcoes.php');
	include ('../../../../rotinas/verifica.php');
	include ('../../../../classes/envia_mensagem/envia_mensagem.php');
	
	$local_IdLoja					=	$_SESSION["IdLoja"];
	$local_Login					= 	$_SESSION["Login"];
	$local_IdContrato				= 	$_POST['IdContratoAnterior'];
	$local_IdServico				= 	$_POST['IdServico'];
	$local_ValorServico				= 	formatText($_POST['ValorServico'],NULL);
	$local_IdPeriodicidade			=	$_POST['IdPeriodicidade'];
	$local_QtdParcela				=	$_POST['QtdParcela'];
	$local_DataBaseCalculo			=	formatText($_POST['DataBaseCalculo'],NULL);
	$local_DataCancelamento			=	formatText($_POST['DataCancelamento'],NULL);
	$local_IdLocalCobranca			=	$_POST['IdLocalCobranca'];	
	$local_TipoContrato				=	$_POST['TipoContrato'];
	$local_MesFechado				=	$_POST['MesFechado'];
	$local_QtdMesesFidelidade		=	formatText($_POST['QtdMesesFidelidade'],NULL);
	$local_MultaFidelidade			=	formatText($_POST['MultaFidelidade'],NULL);
	$local_ValorRepasseTerceiro		=	formatText($_POST['ValorRepasseTerceiro'],NULL);
	$local_ServicoAutomatico		=	formatText($_POST['ServicoAutomatico'],NULL);
	$local_ValorCredito				=	formatText($_POST['ValorCredito'],NULL);
	$local_Autorizar				=	$_POST['Autorizar'];
	$local_IdAgenteAutorizado		=   formatText($_POST['IdAgenteAutorizado'],NULL);
	$local_IdCarteira				=   formatText($_POST['IdCarteira'],NULL);
	$local_IdContratoTipoVigencia	=	formatText($_POST['IdContratoTipoVigencia'],NULL);
	$local_DiaCobranca				=	formatText($_POST['DiaCobranca'],NULL);
	$local_NotaFiscalCDA			=	formatText($_POST['NotaFiscalCDA'],NULL);
	$local_CFOPServico				=	formatText($_POST['CFOPServico'],NULL);
	$local_IdTerceiro				=	$_POST['IdTerceiro'];
	$local_IdContaDebitoCartao		= formatText($_POST['IdContaDebitoCartao'],NULL);
	$local_SeletorContaCartao		= formatText($_POST['SeletorContaCartao'],NULL);
	$include						= 	"false";
	$local_ErroEmail				= "";
	
	if(permissaoSubOperacao($localModulo,$localOperacao,"U") == false){
		$local_Erro = 2;
		header("Location: ../../cadastro_contrato.php?IdContrato=$local_IdContrato&Erro=$local_Erro");
	}else{
		// Sql de Inserção de Contrato
		$sql3	=	"select (max(IdContrato)+1) IdContrato from Contrato where IdLoja = $local_IdLoja ";
		$res3	=	mysql_query($sql3,$con);
		$lin3	=	@mysql_fetch_array($res3);
			
		if($lin3[IdContrato]!=NULL){ 
			$local_IdContratoNovo	=	$lin3[IdContrato];
		}else{
			$local_IdContratoNovo	=	1;
		}
		
		$local_ValorServico			=	str_replace(".", "", $local_ValorServico);	
		$local_ValorServico			= 	str_replace(",", ".", $local_ValorServico);
		
		$local_ValorRepasseTerceiro	=	str_replace(".", "", $local_ValorRepasseTerceiro);	
		$local_ValorRepasseTerceiro	= 	str_replace(",", ".", $local_ValorRepasseTerceiro);
		
		$local_ValorCredito			=	str_replace(".", "", $local_ValorCredito);	
		$local_ValorCredito			= 	str_replace(",", ".", $local_ValorCredito);
		
		$local_DataCancelamento		=	dataConv($local_DataCancelamento,'d/m/Y','Y-m-d');
		$local_DataBaseCalculo		=	dataConv($local_DataBaseCalculo,'d/m/Y','Y-m-d');
		
		if($local_IdCarteiraTemp!="" && $local_IdCarteira==""){
			$local_IdCarteira	=	$local_IdCarteiraTemp;
		}
		
		if($local_IdAgenteAutorizado == ""){
			$local_IdAgenteAutorizado = 'NULL';
		}
		
		if($local_IdCarteira == "" || $local_IdCarteira == "0"){
			$local_IdCarteira = 'NULL';
		}
		
		if($local_IdTerceiro == ""){
			$local_IdTerceiro = 'NULL';
		}
		
		if($local_NotaFiscalCDA == ""){
			$local_NotaFiscalCDA = 'NULL';
		} else{
			$local_NotaFiscalCDA = "'$local_NotaFiscalCDA'";
		}
		
		if($local_CFOPServico == ""){
			$local_CFOPServico = 'NULL';
		} else{
			$local_CFOPServico = "'$local_CFOPServico'";
		}
		
		$sql	=	"START TRANSACTION;";
		mysql_query($sql,$con);
		
		$tr_i = 0;
		
		$sql2	=	"select 
						Obs,
						IdServico,
						IdStatus
					from 
						Contrato 
					where 
						IdLoja = $local_IdLoja and 
						IdContrato = $local_IdContrato;";
		$res2	=	mysql_query($sql2,$con);
		$lin2	=	mysql_fetch_array($res2);
		
		if($lin2[IdStatus] == 203){
			$select .= "IdStatus  = 203,";
		}
		
		$IdServicoAntigo = $lin2[IdServico];
		$IdStatusAntigo  = $lin2[IdStatus];
		$IdContratoNovo	 = $local_IdContratoNovo;
		
		if($lin2[Obs]!=""){
			$lin2[Obs]	= "\n".trim($lin2[Obs]);
		}

		$lin2[Obs]	=	date("d/m/Y H:i:s")." [".$local_Login."] - Migrou para Contrato nº $local_IdContratoNovo."."\n".trim($lin2[Obs]);
		$lin2[Obs] = str_replace('"','\"',$lin2[Obs]);
		$lin2[Obs] = str_replace("'","\'",$lin2[Obs]);
		
		# Finalizando Antigo Contrato
		$sql = "UPDATE Contrato SET
					IdStatus					= 102,
					DataUltimaCobranca			= '$local_DataCancelamento',
					DataTermino					= '$local_DataCancelamento',
					Obs							= '$lin2[Obs]',
					DataAlteracao				= (concat(curdate(),' ',curtime())),
					LoginAlteracao				= '$local_Login'
				WHERE 
					IdLoja						= $local_IdLoja and
					IdContrato					= $local_IdContrato;";
		$local_transaction[$tr_i]	=	mysql_query($sql,$con);
		$tr_i++;

		$local_IdContratoAntigo = $local_IdContrato;
		
		$sql = "select
					UrlRotinaCancelamento
				from
					Servico
				where
					IdLoja = $local_IdLoja and
					IdServico = $IdServicoAntigo and
					UrlRotinaCancelamento is not null";
		$res = mysql_query($sql,$con);
		$lin = mysql_fetch_array($res);

		if($lin[UrlRotinaCancelamento] != ''){
			include("../../".$lin[UrlRotinaCancelamento]);
		}
		
		$local_IdContratoPai	=	$local_IdContrato;
		$local_IdContratoAntigo	=	$local_IdContrato;

		if($local_SeletorContaCartao != ""){
			$select =  "$local_SeletorContaCartao = '$local_IdContaDebitoCartao',"; 
		}
		
		$sql5	=	"select 
						ContratoAutomatico.IdContratoAutomatico IdContrato,
						Contrato.IdServico 
					from 
						(select	
							ContratoAutomatico.IdContrato,
							ContratoAutomatico.IdContratoAutomatico 
						from 
							ContratoAutomatico 
						where 
							ContratoAutomatico.IdLoja = $local_IdLoja and 
							ContratoAutomatico.IdContrato = $local_IdContratoPai) ContratoAutomatico, 
						Contrato 
					where 
						Contrato.IdLoja = $local_IdLoja and 
						Contrato.IdContrato = ContratoAutomatico.IdContratoAutomatico";
		$res5	=	mysql_query($sql5,$con);
		while($lin5 = mysql_fetch_array($res5)){
			$local_IdContrato	=	$lin5[IdContrato];
		
			# Finalizando Antigo Contrato
			$sql = "UPDATE Contrato SET
						IdStatus					= 102,
						DataUltimaCobranca			= '$local_DataCancelamento',
						DataTermino					= '$local_DataCancelamento',
						Obs							= '$lin2[Obs]',
						DataAlteracao				= (concat(curdate(),' ',curtime())),
						LoginAlteracao				= '$local_Login'
					WHERE 
						IdLoja						= $local_IdLoja and
						IdContrato					= $local_IdContrato;";
			$local_transaction[$tr_i]	=	mysql_query($sql,$con);
			$tr_i++;	
			
			$sql = "select
						UrlRotinaCancelamento
					from
						Servico
					where
						IdLoja = $local_IdLoja and
						IdServico = $lin2[IdServico]";
			$res = mysql_query($sql,$con);
			$lin = mysql_fetch_array($res);
	
			if($lin[UrlRotinaCancelamento] != ''){
				include("../../".$lin[UrlRotinaCancelamento]);
			}
		}
		
		$sql2	=	"select
						Contrato.IdPessoa,
						Contrato.DataBaseCalculo,
						Contrato.DataPrimeiraCobranca,
						Contrato.DataUltimaCobranca,
						Contrato.AssinaturaContrato,
						Contrato.IdAgenteAutorizado,
						Contrato.IdCarteira,
						Contrato.IdLocalCobranca,
						Contrato.IdContratoAgrupador,
						Contrato.AdequarLeisOrgaoPublico,
						Contrato.TipoContrato, 
						Contrato.Obs,
						Contrato.DiaCobranca,
						Contrato.IdPessoaEndereco,
						Contrato.IdPessoaEnderecoCobranca,
						Contrato.IdStatus
					from 
						Contrato
					where 
						Contrato.IdLoja = $local_IdLoja and
						Contrato.IdContrato = $local_IdContratoPai";
		$res2	=	mysql_query($sql2,$con);
		$lin2	=	mysql_fetch_array($res2);
		
		if($lin2[IdContratoAgrupador] == '')	$lin2[IdContratoAgrupador]	=	'NULL';
		if($lin2[IdAgenteAutorizado] == '')		$lin2[IdAgenteAutorizado]	=	'NULL';
		if($lin2[IdCarteira] == '')				$lin2[IdCarteira]			=	'NULL';
		
		$local_IdPessoa					=	$lin2[IdPessoa];
		$local_AssinaturaContrato		=	$lin2[AssinaturaContrato];
		#$local_IdLocalCobranca			=	$lin2[IdLocalCobranca];
		$local_IdContratoAgrupador		=	$local_IdContratoPai;
		$local_AdequarLeisOrgaoPublico	=	$lin2[AdequarLeisOrgaoPublico];
		#$local_DiaCobranca				=	$lin2[DiaCobranca];
		$local_IdPessoaEndereco			=	$lin2[IdPessoaEndereco];
		$local_IdPessoaEnderecoCobranca	=	$lin2[IdPessoaEnderecoCobranca];
		#$local_IdAgenteAutorizado		=	$lin2[IdAgenteAutorizado];
		#$local_IdCarteira				=	$lin2[IdCarteira];
		
		$local_Data		=	incrementaData($local_DataCancelamento,1);
		
		$local_IdTipoDesconto			=	getCodigoInterno(3,53);
		
		if($local_IdTipoDesconto == '')			$local_IdTipoDesconto 		  = 'NULL';
		if($local_IdContratoTipoVigencia == '')	$local_IdContratoTipoVigencia = 'NULL';
		if($local_QtdMesesFidelidade == '') $local_QtdMesesFidelidade = 0;

		$local_IdContrato	=	$local_IdContratoNovo;
		
		$Obs	=	date("d/m/Y H:i:s")." [".$local_Login."] - Migrado do Contrato nº $local_IdContratoAntigo.";
		if($local_QtdMesesFidelidade == ''){
			$local_QtdMesesFidelidade = 0;
		}
		# Inserindo novo Contrato
		$sql	=	"INSERT INTO Contrato SET 
						IdLoja						= $local_IdLoja,
						IdContrato					= $local_IdContrato,
						IdPessoa					= '$local_IdPessoa',
						IdServico					= $local_IdServico,
						IdPeriodicidade				= $local_IdPeriodicidade,
						QtdParcela					= $local_QtdParcela,
						DataInicio					= '$local_DataCancelamento', 
						DataTermino					= NULL,
						DataBaseCalculo				= NULL, 
						DataPrimeiraCobranca		= '$local_Data', 
						DataUltimaCobranca			= NULL,
						AssinaturaContrato			= '$local_AssinaturaContrato',
						IdLocalCobranca				= '$local_IdLocalCobranca',
						IdAgenteAutorizado			= $local_IdAgenteAutorizado,
						IdCarteira					= $local_IdCarteira,
						IdTerceiro					= $local_IdTerceiro,
						TipoContrato				= '$local_TipoContrato',
						IdContratoMigrado			= $local_IdContratoAntigo,
						MesFechado					= '$local_MesFechado',
						$select
						IdPessoaEndereco			= '$local_IdPessoaEndereco',
						IdPessoaEnderecoCobranca	= '$local_IdPessoaEnderecoCobranca',
						Obs							= '$Obs',
						DiaCobranca					= '$local_DiaCobranca',
						AdequarLeisOrgaoPublico		= '$local_AdequarLeisOrgaoPublico',
						DataCriacao					= (concat(curdate(),' ',curtime())),
						LoginCriacao				= '$local_Login',
						QtdMesesFidelidade			= '$local_QtdMesesFidelidade',
						MultaFidelidade				= '$local_MultaFidelidade',
						CFOP						= $local_CFOPServico,
						NotaFiscalCDA				= $local_NotaFiscalCDA,
						DataAlteracao				= NULL,
						LoginAlteracao				= NULL;";
		$local_transaction[$tr_i]	=	mysql_query($sql,$con);
		$tr_i++;	

		$sql	=	"UPDATE Contrato SET
						IdContratoAgrupador = '$local_IdContrato'
					WHERE 
						IdLoja = $local_IdLoja and
						(IdContrato = $local_IdContratoAntigo or IdContrato in (select IdContratoAutomatico from ContratoAutomatico where IdLoja = $local_IdLoja and IdContrato = $local_IdContratoAntigo)) and
						IdContrato != $local_IdContrato";
		$local_transaction[$tr_i]	=	mysql_query($sql,$con);
		$tr_i++;	
	
		$sql2	=	"select
						ServicoParametro.IdParametroServico,
						ServicoParametro.Editavel,
						ServicoParametro.ValorDefault
					from 
						Loja,
						Servico,
						ServicoParametro
					where
						Servico.IdLoja	  = $local_IdLoja and
						Servico.IdLoja = Loja.IdLoja and
						Servico.IdLoja = ServicoParametro.IdLoja and
						Servico.IdServico = ServicoParametro.IdServico and
						Servico.IdServico = $local_IdServico and
						Servico.IdStatus = 1";
		$res2	=	mysql_query($sql2,$con);
		while($lin2 = mysql_fetch_array($res2)){
			if($lin2[Editavel] == 2 && $lin2[ValorDefault]!=""){
				$_POST["Valor_".$lin2[IdParametroServico]] =	$lin2[ValorDefault];	
			}
			
			$sql	=	"INSERT INTO ContratoParametro SET 
							IdLoja 					= $local_IdLoja,
							IdContrato				= $local_IdContrato,
							IdServico				= $local_IdServico,
							IdParametroServico		= $lin2[IdParametroServico],
							Valor					='".trim($_POST["Valor_".$lin2[IdParametroServico]])."';";
			$local_transaction[$tr_i]	=	mysql_query($sql,$con);
			$tr_i++;
		}
		
		$sql2	=	"select
						LocalCobrancaParametroContrato.IdLocalCobrancaParametroContrato
					from 
						Loja,
						LocalCobranca,
						LocalCobrancaParametroContrato
					where
						LocalCobranca.IdLoja = $local_IdLoja and
						LocalCobranca.IdLoja = Loja.IdLoja and
						LocalCobranca.IdLoja = LocalCobrancaParametroContrato.IdLoja and
						LocalCobranca.IdLocalCobranca = LocalCobrancaParametroContrato.IdLocalCobranca and
						LocalCobranca.IdLocalCobranca = $local_IdLocalCobranca and
						LocalCobrancaParametroContrato.IdStatus = 1";
		$res2	=	mysql_query($sql2,$con);
		while($lin2 = mysql_fetch_array($res2)){
			$sql	=	"
				INSERT INTO ContratoParametroLocalCobranca SET 
					IdLoja 								= $local_IdLoja,
					IdContrato							= $local_IdContrato,
					IdLocalCobranca						= $local_IdLocalCobranca,
					IdLocalCobrancaParametroContrato	= $lin2[IdLocalCobrancaParametroContrato],
					Valor								='".trim($_POST['LocalCobranca_Valor_'.$lin2[IdLocalCobrancaParametroContrato]])."';";
			$local_transaction[$tr_i]	=	mysql_query($sql,$con);
			$tr_i++;
		}
		
		$local_DataPrimeiraCobranca = $local_Data;
		
		include("../inserir/inserir_contrato_mascara_vigencia.php");

		$sqlLF = "select
						IdLancamentoFinanceiro
					from
						LancamentoFinanceiro
					where
						IdLoja = $local_IdLoja and
						IdContrato = $local_IdContratoAntigo";
		$resLF = mysql_query($sqlLF,$con);
		while($linLF = mysql_fetch_array($resLF)){	
			if($_POST['Autorizar_'.$linLF[IdLancamentoFinanceiro]] == 1 && $_POST['ValorCredito_'.$linLF[IdLancamentoFinanceiro]] > 0){

				$ValorCredito = $_POST['ValorCredito_'.$linLF[IdLancamentoFinanceiro]];
				
				$sqlLancamento = "select max(IdLancamentoFinanceiro) IdLancamentoFinanceiro from LancamentoFinanceiro where IdLoja=$local_IdLoja";
				$resLancamento = mysql_query($sqlLancamento);
				$linLancamento = mysql_fetch_array($resLancamento);
				
				$IdLancamentoFinanceiro = $linLancamento[IdLancamentoFinanceiro];
				if($IdLancamentoFinanceiro == null){
					$IdLancamentoFinanceiro = 1;
				}else{
					$IdLancamentoFinanceiro++;
				}
				
				$local_ObsLancamentoFinanceiro	=	date("d/m/Y H:i:s")." [".$local_Login."] - Obs: Crédito resultado de mudança de serviço";

				$DataReferenciaInicial	= dataConv($_POST['DataInicioCredito_'.$linLF[IdLancamentoFinanceiro]],"d/m/Y","Y-m-d");
				$DataReferenciaFinal	= dataConv($_POST['DataTerminoCredito_'.$linLF[IdLancamentoFinanceiro]],"d/m/Y","Y-m-d");
	
				$sqlLancamento = "INSERT INTO LancamentoFinanceiro SET 
									IdLoja='$local_IdLoja',
									IdLancamentoFinanceiro='$IdLancamentoFinanceiro',
									IdContrato='$local_IdContratoAntigo',
									Valor='-$ValorCredito',
									DataReferenciaInicial='$DataReferenciaInicial', 
									DataReferenciaFinal='$DataReferenciaFinal',
									IdProcessoFinanceiro=NULL,
									IdStatus = 2,
									ObsLancamentoFinanceiro	= '$local_ObsLancamentoFinanceiro';"; //Aguardando Cobrança
				$local_transaction[$tr_i]	=	mysql_query($sqlLancamento,$con);
				$tr_i++; 
			 }
			 
			 $sql9	=	"select 
							ContratoAutomatico.IdContratoAutomatico,
							Contrato.IdServico 
						from 
							(select
									ContratoAutomatico.IdContrato,	
									ContratoAutomatico.IdContratoAutomatico 
								from 
									ContratoAutomatico 
								where 
									ContratoAutomatico.IdLoja = $local_IdLoja and 
									ContratoAutomatico.IdContrato = $local_IdContratoAntigo) ContratoAutomatico, 
							Contrato 
						where 
							Contrato.IdLoja = $local_IdLoja and 
							Contrato.IdContrato = ContratoAutomatico.IdContratoAutomatico";
			 $res9 	= 	@mysql_query($sql9,$con);
			 while($lin9	=	mysql_fetch_array($res9)){
				 if($_POST['Autorizar_'.$lin9[IdContratoAutomatico]] == 1 && $_POST['ValorCredito_'.$lin9[IdContratoAutomatico]] > 0){
				
					 $local_Valor   =	str_replace(".", "", $_POST['ValorCredito_'.$lin9[IdContratoAutomatico]]);
					 $local_Valor   =	str_replace(",", ".", $local_Valor);
				
					 $sqlLancamento = "select max(IdLancamentoFinanceiro) IdLancamentoFinanceiro from LancamentoFinanceiro where IdLoja=$local_IdLoja";
					 $resLancamento = mysql_query($sqlLancamento);
					 $linLancamento = mysql_fetch_array($resLancamento);
					
					 $IdLancamentoFinanceiro = $linLancamento[IdLancamentoFinanceiro];
					 if($IdLancamentoFinanceiro == null){
					 	 $IdLancamentoFinanceiro = 1;
					 }else{
						 $IdLancamentoFinanceiro++;
					 }
					
					 $local_ObsLancamentoFinanceiro	=	date("d/m/Y H:i:s")." [".$local_Login."] - Obs: Crédito resultado de mudança de serviço";

					 $DataReferenciaInicial	= dataConv($_POST['DataInicioCredito_'.$linLF[IdLancamentoFinanceiro]],"d/m/Y","Y-m-d");
					 $DataReferenciaFinal	= dataConv($_POST['DataTerminoCredito_'.$linLF[IdLancamentoFinanceiro]],"d/m/Y","Y-m-d");
		
					 $sqlLancamento = "INSERT INTO LancamentoFinanceiro SET 
										 IdLoja='$local_IdLoja',
										 IdLancamentoFinanceiro='$IdLancamentoFinanceiro',
									 	 IdContrato='$lin9[IdContratoAutomatico]',
										 Valor='-$local_Valor',
										 DataReferenciaInicial='$DataReferenciaInicial', 
										 DataReferenciaFinal='$DataReferenciaFinal',
										 IdProcessoFinanceiro=NULL,
										 IdStatus = 2,
										 ObsLancamentoFinanceiro	= '$local_ObsLancamentoFinanceiro';"; //Aguardando Cobrança
					 $local_transaction[$tr_i]	=	mysql_query($sqlLancamento,$con);
					 $tr_i++; 
				 }
			 }
		}
		
		$local_IdContratoPai	=	$local_IdContrato;
		
		/// ############ Insercao de Servico Automatico #############
		if($local_ServicoAutomatico != ""){
			$temp	= explode("#",$local_ServicoAutomatico);
			$i		= 0;
			
			while($temp[$i] != ""){
				$local_IdServico2 = $temp[$i];
				
				// Sql de Inserção de Contrato
				$sql3 = "select (max(IdContrato)+1) IdContrato from Contrato where IdLoja = $local_IdLoja ";
				$res3 = mysql_query($sql3,$con);
				$lin3 = @mysql_fetch_array($res3);
					
				if($lin3[IdContrato] != NULL){ 
					$local_IdContrato = $lin3[IdContrato];
				} else{
					$local_IdContrato = 1;
				}
				
				$local_dadosContrato[$i][IdContrato] = $local_IdContrato;
				$local_NotaFiscalCDAAutomatico = $_POST['NotaFiscalCDAAutomatico_'.$local_IdServico2];
				$local_IdAgenteAutorizadoAutomatico = $_POST['IdAgenteAutorizado_'.$local_IdServico2];
				$local_IdCarteiraAutomatico = $_POST['IdCarteira_'.$local_IdServico2];
				$local_IdTerceiroAutomatico = $_POST['IdTerceiro_'.$local_IdServico2];
				
				if($local_IdAgenteAutorizadoAutomatico == ''){
					$local_IdAgenteAutorizadoAutomatico = "NULL";
				}
				
				if($local_IdCarteiraAutomatico == ''){
					$local_IdCarteiraAutomatico = "NULL";
				}
				
				if($local_IdTerceiroAutomatico == ''){
					$local_IdTerceiroAutomatico = "NULL";
				}	
				
				$sql = "INSERT INTO Contrato SET 
							IdLoja						= $local_IdLoja,
							IdContrato					= $local_IdContrato,
							IdPessoa					= $local_IdPessoa,
							IdServico					= '$local_IdServico2',
							DataInicio					= '$local_DataCancelamento', 
							DataTermino					= NULL,
							DataBaseCalculo				= NULL, 
							DataPrimeiraCobranca		= '$local_Data', 
							DataUltimaCobranca			= NULL,
							AssinaturaContrato			= $local_AssinaturaContrato,
							IdLocalCobranca				= $local_IdLocalCobranca,
							IdAgenteAutorizado			= $local_IdAgenteAutorizadoAutomatico,
							IdCarteira					= $local_IdCarteiraAutomatico,
							IdTerceiro					= $local_IdTerceiroAutomatico,
							IdPeriodicidade				= $local_IdPeriodicidade,
							QtdParcela					= $local_QtdParcela,
							IdContratoAgrupador			= $local_IdContratoPai,
							IdPessoaEndereco			= '$local_IdPessoaEndereco',
							IdPessoaEnderecoCobranca	= '$local_IdPessoaEnderecoCobranca',
							TipoContrato				= $local_TipoContrato,
							MesFechado					= $local_MesFechado,
							Obs							= '$Obs',
							$select
							QtdMesesFidelidade			= '$local_QtdMesesFidelidade',
							MultaFidelidade				= '$local_MultaFidelidade',
							AdequarLeisOrgaoPublico		= $local_AdequarLeisOrgaoPublico,
							DiaCobranca					= '$local_DiaCobranca',
							DataCriacao					= (concat(curdate(),' ',curtime())),
							LoginCriacao				= '$local_Login',
							CFOP						= $local_CFOPServico,
							NotaFiscalCDA				= $local_NotaFiscalCDA,
							DataAlteracao				= NULL,
							LoginAlteracao				= NULL;";
				$local_transaction[$tr_i] = mysql_query($sql,$con);
				$tr_i++;
				
				$sql = "INSERT INTO ContratoAutomatico SET 
						 	IdLoja						= $local_IdLoja,
							IdContrato					= $local_IdContratoPai,
							IdContratoAutomatico		= $local_IdContrato;";
				$local_transaction[$tr_i] = mysql_query($sql,$con);
				$tr_i++;
				
				$sql4 = "select 
							ServicoValor.Valor,
							ServicoTerceiro.ValorRepasseTerceiro,
							ServicoTerceiro.PercentualRepasseTerceiro 
						from 
							ServicoValor left join ServicoTerceiro on (
								ServicoValor.IdLoja = ServicoTerceiro.IdLoja and
								ServicoValor.IdServico = ServicoTerceiro.IdServico and
								ServicoTerceiro.IdPessoa = $local_IdTerceiroAutomatico
							)
						where 
							ServicoValor.DataInicio <= CURDATE() and
							(
								ServicoValor.DataTermino IS NULL or
								ServicoValor.DataTermino >= CURDATE()
							) and 
							ServicoValor.IdLoja = $local_IdLoja and
							ServicoValor.IdServico = $local_IdServico2
						order by 
							ServicoValor.DataInicio DESC
						limit 0, 1"; 
				$res4 = mysql_query($sql4,$con);
				$lin4 = @mysql_fetch_array($res4);
				
				$local_IdServico			= $local_IdServico2;
				$local_ValorServico			= $lin4[Valor];
				$local_DataPrimeiraCobranca	= $local_Data;
				
				if($lin4[ValorRepasseTerceiro] != '' || $lin4[PercentualRepasseTerceiro] != ''){
					$local_ValorRepasseTerceiro = $lin4[ValorRepasseTerceiro];
					
					if($lin4[PercentualRepasseTerceiro] > 0){
						$local_ValorRepasseTerceiro = (($lin4[Valor] * $lin4[PercentualRepasseTerceiro]) / 100);
					}
				} else{
					$local_ValorRepasseTerceiro = 0;
				}
				
				include("../inserir/inserir_contrato_mascara_vigencia.php");
				
				$sql2	=	"select
								LocalCobrancaParametroContrato.IdLocalCobrancaParametroContrato
							from 
								Loja,
								LocalCobranca,
								LocalCobrancaParametroContrato
							where
								LocalCobranca.IdLoja	  = $local_IdLoja and
								LocalCobranca.IdLoja = Loja.IdLoja and
								LocalCobranca.IdLoja = LocalCobrancaParametroContrato.IdLoja and
								LocalCobranca.IdLocalCobranca = LocalCobrancaParametroContrato.IdLocalCobranca and
								LocalCobranca.IdLocalCobranca = $local_IdLocalCobranca and
								LocalCobrancaParametroContrato.IdStatus = 1";
				$res2	=	mysql_query($sql2,$con);
				while($lin2 = mysql_fetch_array($res2)){
					$sql = "INSERT INTO ContratoParametroLocalCobranca SET 
								IdLoja 								= $local_IdLoja,
								IdContrato							= $local_IdContrato,
								IdLocalCobranca						= $local_IdLocalCobranca,
								IdLocalCobrancaParametroContrato	= $lin2[IdLocalCobrancaParametroContrato],
								Valor								='".$_POST['LocalCobranca_Valor_'.$lin2[IdLocalCobrancaParametroContrato]]."';";
					$local_transaction[$tr_i]	=	mysql_query($sql,$con);
					
					$tr_i++;
				}
				
				$sql2	=	"select
								ServicoParametro.IdParametroServico,
								ServicoParametro.Editavel,
								ServicoParametro.ValorDefault
							from 
								Loja,
								Servico,
								ServicoParametro
							where
								Servico.IdLoja	  = $local_IdLoja and
								Servico.IdLoja = Loja.IdLoja and
								Servico.IdLoja = ServicoParametro.IdLoja and
								Servico.IdServico = ServicoParametro.IdServico and
								Servico.IdServico = $local_IdServico2 and
								ServicoParametro.IdStatus = 1";
				$res2	=	mysql_query($sql2,$con);
				while($lin2 = mysql_fetch_array($res2)){
					
					if($lin2[Editavel] == 2 && $lin2[ValorDefault]!=""){
						$_POST["ValorAutomatico_".$local_IdServico2."_".$lin2[IdParametroServico]] =	$lin2[ValorDefault];	
					}
					
					$sql = "INSERT INTO ContratoParametro SET 
								IdLoja 					= $local_IdLoja,
								IdContrato				= $local_IdContrato,
								IdServico				= $local_IdServico2,
								IdParametroServico		= $lin2[IdParametroServico],
								Valor					='".$_POST["ValorAutomatico_".$local_IdServico2."_".$lin2[IdParametroServico]]."';";
					$local_transaction[$tr_i]	=	mysql_query($sql,$con);
					$tr_i++;
					
				}
				
				$i++;
			}
			
		}
		
		$local_IdContrato = $local_IdContratoPai;		
		
		$sql = "select
					UrlRotinaCriacao,
					AtivacaoAutomatica
				from
					Contrato,
					Servico
				where
					Contrato.IdLoja = $local_IdLoja and
					Contrato.IdLoja = Servico.IdLoja and
					Contrato.IdServico = Servico.IdServico and
					Contrato.IdContrato = $local_IdContrato and
					UrlRotinaCancelamento is not null";
		$res = mysql_query($sql,$con);
		$lin = mysql_fetch_array($res);
		
		if($lin[AtivacaoAutomatica] == '1' && $lin[UrlRotinaCriacao] != ''){
			include ("../../".$lin[UrlRotinaCriacao]);
		}
		
		$temp	= explode("#",$local_ServicoAutomatico);
		$i		= 0;
		
		while($temp[$i]!= ""){
			$local_IdContrato = $local_dadosContrato[$i][IdContrato];
			$sql = "select
						UrlRotinaCriacao,
						AtivacaoAutomatica
					from
						Contrato,
						Servico
					where
						Contrato.IdLoja = $local_IdLoja and
						Contrato.IdLoja = Servico.IdLoja and
						Contrato.IdServico = Servico.IdServico and
						Contrato.IdContrato = $local_IdContrato";
			$res = mysql_query($sql,$con);
			$lin = mysql_fetch_array($res);
			
			if($lin[AtivacaoAutomatica] == '1' && $lin[UrlRotinaCriacao] != ''){
				include ("../../".$lin[UrlRotinaCriacao]);
			}

			$i++;
		}
		
		$sqlMensagem = "select
							Titulo,
							Assunto,
							Conteudo,
							IdStatus
						from
							TipoMensagem
						where
							IdLoja = $local_IdLoja and
							IdTipoMensagem = 27";
		$resMensagem = mysql_query($sqlMensagem,$con);
		$linMensagem = mysql_fetch_array($resMensagem);

		if($linMensagem[IdStatus] != 1){
			$local_ErroEmail		= getParametroSistema(13,191);
			$local_TipoEmail		= "'".delimitaAteCaracter($linMensagem[Titulo],'$')."'";
		}		
		enviarEmailAlteracaoServico($local_IdLoja, $local_IdContratoAntigo, $local_IdContratoNovo);
		
		for($i=0; $i<$tr_i; $i++){
			if($local_transaction[$i] == false){
				$local_transaction = false;				
			}
		}
		
		if($local_transaction == true){
			$sql = "COMMIT;";
			$local_Erro = 4;			// Mensagem de Inserção Positiva
			if(permissaoSubOperacao($localModulo2,$localOperacao2,"C")){
				require("editar_cancelar_conta_receber.php");
			}
			
		}else{
			$sql = "ROLLBACK;";
			$local_Erro = 5;			// Mensagem de Inserção Negativa
		}
		mysql_query($sql,$con);
		header("Location: ../../cadastro_contrato.php?IdContrato=$local_IdContratoNovo&Erro=$local_Erro&EmailErro=$local_ErroEmail&TipoEmail=$local_TipoEmail");
	}
?>
