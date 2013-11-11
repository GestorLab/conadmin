<?
	if(permissaoSubOperacao($localModulo,$localOperacao,"I") == false && $local != "cda"){
		$local_Erro = 2;
	}else{
		if($local != "cda"){
			$Path = "";
		}
		// Sql de Inser磯 de Contrato
		$sql3	=	"select (max(IdContrato)+1) IdContrato from Contrato where IdLoja = $local_IdLoja ";
		$res3	=	mysql_query($sql3,$con);
		$lin3	=	@mysql_fetch_array($res3);
		
		if($lin3[IdContrato]!=NULL){ 
			$local_IdContrato	=	$lin3[IdContrato];
		}else{
			$local_IdContrato	=	1;
		}
		
		if($local_Obs == "\n" || $local_Obs == "<BR>") $local_Obs = "";
		
		if($local_Obs!=""){
			$local_Obs	=	date("d/m/Y H:i:s")." [".$local_Login."] - ".$local_Obs.".";
		}
		
		if($local == "cda"){
			$local_SetaColuna 	= ", IdStatus = ".getCodigoInterno(3,223);
		}else{
			if($local_DataTermino == ""){ 
				$local_SetaColuna = "";
				#Default do status do contrato.
				if(getCodigoInterno(3,242) != ""){
					$aux 				= explode("\n",getCodigoInterno(3,242));
					$TempoNovoStatus	= date("d/m/Y",strtotime("$aux[1] days"));			
					$StatusContrado 	= ",IdStatus = ".getCodigoInterno(3,242);
				}
			}else{
				if((int) dataConv($local_DataTermino,'d/m/Y','Ymd') > (int) date("Ymd")){
					$IdStatusTemp = "205";
				} else {
					$IdStatusTemp = "1";
				}
				
				$local_SetaColuna 	= ", IdStatus = $IdStatusTemp";
				
				$sql1 = "select ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema = 69 and IdParametroSistema = $IdStatusTemp";
				$res1 = @mysql_query($sql1,$con);
				$lin1 = @mysql_fetch_array($res1);
				
				if($local_Obs != "") $local_Obs .= "\n";
				
				$local_Obs .= date("d/m/Y H:i:s")." [".$local_Login."] - Cadastrado com o status $lin1[ValorParametroSistema]";
			}
		}
		
		if($local_IdCarteiraTemp!="" && $local_IdCarteira==""){
			$local_IdCarteira	=	$local_IdCarteiraTemp;
		}
		
		if($local_IdPortador == ""){ 				$local_IdPortador = 'NULL';   } 
		if($local_IdContratoAgrupador == 0){ 		$local_IdContratoAgrupador = 'NULL';}
		if($local_AdequarLeisOrgaoPublico == 0){ 	$local_AdequarLeisOrgaoPublico = getCodigoInterno(3,28);}
		if($local_IdAgenteAutorizado == "" || $local_IdAgenteAutorizado == 0){ 		$local_IdAgenteAutorizado = 'NULL';}
		if($local_IdCarteira == "" || $local_IdCarteira == 0){ 				$local_IdCarteira = 'NULL';	}
		if($local_NotaFiscalCDA == 0){ 				$local_NotaFiscalCDA = 'NULL';	}
		if($local_NotaFiscalCDAAutomatico == 0){ 	$local_NotaFiscalCDAAutomatico = 'NULL';  }
		if($local_QtdMesesFidelidade == ""){		$local_QtdMesesFidelidade = 0; }

		$local_DataInicioVigenciaAutomatico		= $local_DataInicioVigencia;
		$local_DataTerminoVigenciaAutomatico	= $local_DataTerminoVigencia;
		$select	= "";
		
		if($local_DataTermino == ""){ 			
			$local_DataTermino = 'NULL';  		
		}else{	
			if($local_DataUltimaCobranca == ""){
				$local_DataUltimaCobranca	=	"'".dataConv($local_DataTermino,'d/m/Y','Y-m-d')."'"; 
			}
			$local_DataTermino		= 	"'".dataConv($local_DataTermino,'d/m/Y','Y-m-d')."'"; 
		}
		
		if($local_DataUltimaCobranca == ""){ 	
			$local_DataUltimaCobranca = 'NULL'; 
		}else{ 	
			$local_DataUltimaCobranca	= 	"'".dataConv($local_DataUltimaCobranca,'d/m/Y','Y-m-d')."'"; 
		}
		
		if($local_IdTerceiro == ""){ 	
			$local_IdTerceiro = 'NULL'; 
		}
		
		$local_ValorServico			=	str_replace(".", "", $local_ValorServico);	
		$local_ValorServico			= 	str_replace(",", ".", $local_ValorServico);
		
		$local_ValorRepasseTerceiro	=	str_replace(".", "", $local_ValorRepasseTerceiro);	
		$local_ValorRepasseTerceiro	= 	str_replace(",", ".", $local_ValorRepasseTerceiro);
		
		if($local_SeletorContaCartao != ""){
			$select =  "$local_SeletorContaCartao = '$local_IdContaDebitoCartao',"; 
		}
		
		$local_Erro2 = '';
		$sql	=	"START TRANSACTION;";
		mysql_query($sql,$con);
		$tr_i = 0;
		
		$local_DataInicio				=	dataConv($local_DataInicio,'d/m/Y','Y-m-d');
		$local_DataPrimeiraCobranca		=	dataConv($local_DataPrimeiraCobranca,'d/m/Y','Y-m-d');
		$StatusContrado					= "";
		/*
		#Default do status do contrato.
		if(getCodigoInterno(3,242) != ""){
			$aux 				= explode("\n",getCodigoInterno(3,242));
			$TempoNovoStatus	= date("d/m/Y",strtotime("$aux[1] days"));			
			$StatusContrado 	= ",IdStatus = ".getCodigoInterno(3,242);
		}*/
		
		if($local_CFOPServico == ''){ 
			$local_CFOPServico = 'NULL';
		}else{ 
			$local_CFOPServico = "'".$local_CFOPServico."'"; 
		}

		if($local_MultaFidelidade == ''){ 
			$local_MultaFidelidade = '0.00';
		}else{ 
			$local_MultaFidelidade = $local_MultaFidelidade; 
		}
		
		$sql = "insert into Contrato set 
					IdLoja						= $local_IdLoja,
					IdContrato					= $local_IdContrato,
					IdPessoa					= $local_IdPessoa,
					IdServico					= $local_IdServico,
					DataInicio					= '$local_DataInicio', 
					DataTermino					= $local_DataTermino,
					DataBaseCalculo				= NULL, 
					IdTerceiro					= $local_IdTerceiro, 
					DataPrimeiraCobranca		= '$local_DataPrimeiraCobranca', 
					DataUltimaCobranca			= $local_DataUltimaCobranca,
					AssinaturaContrato			= $local_AssinaturaContrato,
					IdLocalCobranca				= $local_IdLocalCobranca,
					IdAgenteAutorizado			= $local_IdAgenteAutorizado,
					IdCarteira					= $local_IdCarteira,
					IdPeriodicidade				= $local_IdPeriodicidade,
					$select
					QtdParcela					= $local_QtdParcela,
					IdContratoAgrupador			= $local_IdContratoAgrupador,
					TipoContrato				= $local_TipoContrato,
					MesFechado					= $local_MesFechado,
					Obs							= \"$local_Obs\",
					CFOP						= $local_CFOPServico,
					DiaCobranca					= '$local_DiaCobranca',
					QtdMesesFidelidade			= '$local_QtdMesesFidelidade',
					MultaFidelidade				= '$local_MultaFidelidade',
					AdequarLeisOrgaoPublico		= $local_AdequarLeisOrgaoPublico,
					NotaFiscalCDA				= $local_NotaFiscalCDA,
					IdPessoaEndereco			= '$local_IdPessoaEndereco',
					IdPessoaEnderecoCobranca	= '$local_IdPessoaEnderecoCobranca',
					DataCriacao					= (concat(curdate(),' ',curtime())),
					LoginCriacao				= '$local_Login',
					DataAlteracao				= NULL,
					LoginAlteracao				= NULL
					$StatusContrado
					$local_SetaColuna;";
		$local_transaction[$tr_i] = mysql_query($sql,$con);	
		$tr_i++;	
	
		$sql2	=	"select
						ServicoParametro.IdGrupoUsuario,
						ServicoParametro.IdParametroServico,
						ServicoParametro.Editavel,
						ServicoParametro.DescricaoParametroServico,
						ServicoParametro.ValorDefault,
						ServicoParametro.Unico
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
						ServicoParametro.IdStatus = 1";
		$res2	=	mysql_query($sql2,$con);
		while($lin2 = mysql_fetch_array($res2)){

			// Carrega em uma variᶥl os parametros para ser usado em rotinas
			//$ParametroValor[$lin2[IdParametroServico]] = $_POST['Valor_'.$lin2[IdParametroServico]];
			
			if($lin2[Editavel] == 2 && $lin2[ValorDefault]!=""){
				$_POST['Valor_'.$lin2[IdParametroServico]] =	$lin2[ValorDefault];	
			}
			
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
							ContratoParametro.Valor = '".trim($_POST['Valor_'.$lin2[IdParametroServico]])."';";
			$resUnico = @mysql_query($sqlUnico, $con);
			$linUnico = @mysql_fetch_array($resUnico);
		
			if($lin2[IdGrupoUsuario] != ''){
				$sql7	=	"select
								(COUNT(*)>0) Qtd
							from 
								UsuarioGrupoUsuario
							where 
								IdLoja = '$local_IdLoja' and 
								IdGrupoUsuario in ($lin2[IdGrupoUsuario]) and 
								Login = '$local_Login';";
				$res7	=	@mysql_query($sql7,$con);
				$lin7	=	@mysql_fetch_array($res7);
			} else {
				$lin7[Qtd] = 1;
			}
			
			if($lin2[Unico] == 1 && $linUnico[Qtd] > 0 && $lin7[Qtd] == 1 && (trim($_POST['Valor_'.$lin2[IdParametroServico]]) !="")){
				$local_Erro2 = 142;
				$local_desc_param = $lin2[DescricaoParametroServico].",Valor_".$lin2[IdParametroServico].",".trim($_POST['Valor_'.$lin2[IdParametroServico]]);

				$ValorParametroMsg = trim($_POST['Valor_'.$lin2[IdParametroServico]]);

				$local_Obs = date("d/m/Y H:i:s")." [$local_Login] - Par⭥tro $lin2[DescricaoParametroServico] \\\"$ValorParametroMsg\\\" jᠵtilizado. Digite outro novamente.\n".$local_Obs;				
				
				$sql	=	"update Contrato SET 
								Obs			= \"$local_Obs\"
							 where
								IdLoja		= '$local_IdLoja' and 
								IdContrato  = $local_IdContrato";
				$local_transaction[$tr_i]	=	mysql_query($sql,$con);
				$tr_i++;
			} else{
				$sql	=	"INSERT INTO ContratoParametro SET 
								IdLoja 					= $local_IdLoja,
								IdContrato				= $local_IdContrato,
								IdServico				= $local_IdServico,
								IdParametroServico		= $lin2[IdParametroServico],
								Valor					='".trim($_POST['Valor_'.$lin2[IdParametroServico]])."';";
				$local_transaction[$tr_i]	=	mysql_query($sql,$con);
				$tr_i++;
			}
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
					Valor								='".$_POST['LocalCobranca_Valor_'.$lin2[IdLocalCobrancaParametroContrato]]."';";
			$local_transaction[$tr_i]	=	mysql_query($sql,$con);
			$tr_i++;
		}
		
		include("inserir_contrato_mascara_vigencia.php");
	
		$sql = "select
				UrlRotinaCriacao,
				AtivacaoAutomatica
			from
				Servico
			where
				IdLoja = $local_IdLoja and
				IdServico = $local_IdServico";
		$res = mysql_query($sql,$con);
		$lin = mysql_fetch_array($res);

		if($lin[UrlRotinaCriacao] != ''){
			@include($Path.$lin[UrlRotinaCriacao]);
		}
		
		$local_IdContratoPai	=	$local_IdContrato;
		
		/// ############ Insercao de Servico Automatico #############
		if($local_ServicoAutomatico != ""){
			$temp	=	explode("#",$local_ServicoAutomatico);
			$i		=	0;
			
			while($temp[$i]!= ""){
				$local_IdServico2	=	$temp[$i];
				
				// Sql de Inser磯 de Contrato
				$sql3	=	"select (max(IdContrato)+1) IdContrato from Contrato where IdLoja = $local_IdLoja ";
				$res3	=	mysql_query($sql3,$con);
				$lin3	=	@mysql_fetch_array($res3);
					
				if($lin3[IdContrato]!=NULL){ 
					$local_IdContrato	=	$lin3[IdContrato];
				}else{
					$local_IdContrato	=	1;
				}

				
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
				
				$sql	=	"INSERT INTO Contrato SET 
								IdLoja						= $local_IdLoja,
								IdContrato					= $local_IdContrato,
								IdPessoa					= $local_IdPessoa,
								IdServico					= '$local_IdServico2',
								DataInicio					= '$local_DataInicio', 
								DataTermino					= $local_DataTermino,
								DataBaseCalculo				= NULL, 
								IdTerceiro					= $local_IdTerceiroAutomatico,
								DataPrimeiraCobranca		= '$local_DataPrimeiraCobranca', 
								DataUltimaCobranca			= $local_DataUltimaCobranca,
								AssinaturaContrato			= $local_AssinaturaContrato,
								IdLocalCobranca				= $local_IdLocalCobranca,
								$select
								IdAgenteAutorizado			= $local_IdAgenteAutorizadoAutomatico,
								IdCarteira					= $local_IdCarteiraAutomatico,
								IdPeriodicidade				= $local_IdPeriodicidade,
								QtdParcela					= $local_QtdParcela,
								IdContratoAgrupador			= $local_IdContratoPai,
								TipoContrato				= $local_TipoContrato,
								MesFechado					= $local_MesFechado,
								IdPessoaEndereco			= '$local_IdPessoaEndereco',
								IdPessoaEnderecoCobranca	= '$local_IdPessoaEnderecoCobranca',
								Obs							= \"$local_Obs\",
								CFOP						= $local_CFOPServico,
								DiaCobranca					= '$local_DiaCobranca',
								QtdMesesFidelidade			= '$local_QtdMesesFidelidade',
								MultaFidelidade				= '$local_MultaFidelidade',
								AdequarLeisOrgaoPublico		= $local_AdequarLeisOrgaoPublico,
								NotaFiscalCDA				= '$local_NotaFiscalCDAAutomatico',
								DataCriacao					= (concat(curdate(),' ',curtime())),
								LoginCriacao				= '$local_Login',
								DataAlteracao				= NULL,
								LoginAlteracao				= NULL
								$local_SetaColuna;";
				$local_transaction[$tr_i]	=	mysql_query($sql,$con);
				$tr_i++;
				
				$sql	=	"
					INSERT INTO ContratoAutomatico SET 
						IdLoja						= $local_IdLoja,
						IdContrato					= $local_IdContratoPai,
						IdContratoAutomatico		= $local_IdContrato;";
				$local_transaction[$tr_i]	=	mysql_query($sql,$con);
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
				
				$local_IdServico	= $local_IdServico2;
				$local_ValorServico	= $lin4[Valor];
				
				if($lin4[ValorRepasseTerceiro] != '' || $lin4[PercentualRepasseTerceiro] != ''){
					$local_ValorRepasseTerceiro = $lin4[ValorRepasseTerceiro];
					
					if($lin4[PercentualRepasseTerceiro] > 0) {
						$local_ValorRepasseTerceiro = (($local_ValorServico * $lin4[PercentualRepasseTerceiro]) / 100);
					}
				} else {
					$local_ValorRepasseTerceiro = 0;
				}

				$local_DataInicioVigencia	= $local_DataInicioVigenciaAutomatico;
				$local_DataTerminoVigencia	= $local_DataTerminoVigenciaAutomatico;
				
				include("inserir_contrato_mascara_vigencia.php");
				
				$ii 	= 	1;
				$sql2	=	"select
								ServicoParametro.IdGrupoUsuario,
								ServicoParametro.IdParametroServico,
								ServicoParametro.Editavel,
								ServicoParametro.ValorDefault,
								ServicoParametro.Unico
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
					$local_Valor[$ii]	=	$_POST["ValorAutomatico_".$local_IdServico2."_".$lin2[IdParametroServico]];
					if($lin2[Editavel]==2 && $lin2[ValorDefault]!=""){
						$local_Valor[$ii]	=	$lin2[ValorDefault];
					}
					
					$sqlUnico = "select 
									count(*) Qtd
								from 
									Contrato,
									ContratoParametro
								where 
									Contrato.IdLoja = ContratoParametro.IdLoja and 
									Contrato.IdContrato != $local_IdContrato and
									Contrato.IdContrato = ContratoParametro.IdContrato and 
									Contrato.IdServico = ContratoParametro.IdServico and 
									Contrato.IdStatus != 1 and
									ContratoParametro.Valor = '".trim($local_Valor[$ii])."';";
					$resUnico = @mysql_query($sqlUnico, $con);
					$linUnico = @mysql_fetch_array($resUnico);
					
					if($lin2[IdGrupoUsuario] != ''){
						$sql7 = "select
									(COUNT(*)>0) Qtd
								from 
									UsuarioGrupoUsuario
								where 
									IdLoja = '$local_IdLoja' and 
									IdGrupoUsuario in ($lin2[IdGrupoUsuario]) and 
									Login = '$local_Login';";
						$res7 = @mysql_query($sql7,$con);
						$lin7 = @mysql_fetch_array($res7);
					} else {
						$lin7[Qtd] = 1;
					}
					
					if($lin2[Unico] == 1 && $linUnico[Qtd] > 0 && $lin7[Qtd] == 1 && (trim($local_Valor[$ii])!="")){
						$local_Erro2 = 142;
						$local_desc_param = $lin2[DescricaoParametroServico].",".$local_Valor[$ii].",".trim($local_Valor[$ii]);
	
						$ValorParametroMsg = trim($local_Valor[$ii]);

						$local_Obs = date("d/m/Y H:i:s")." [$local_Login] - Par⭥tro $lin2[DescricaoParametroServico] \\\"$ValorParametroMsg\\\" jᠵtilizado. Digite outro novamente.\n".$local_Obs;				
						
						$sql	=	"update Contrato SET 
										Obs			= \"$local_Obs\"
									 where
										IdLoja		= '$local_IdLoja' and 
										IdContrato  = $local_IdContrato";
						$local_transaction[$tr_i]	=	mysql_query($sql,$con);
						$tr_i++;						
					} else{
						$sql	=	"
							INSERT INTO ContratoParametro SET 
								IdLoja 					= $local_IdLoja,
								IdContrato				= $local_IdContrato,
								IdServico				= $local_IdServico2,
								IdParametroServico		= $lin2[IdParametroServico],
								Valor					='".trim($local_Valor[$ii])."';";
						$local_transaction[$tr_i]	=	mysql_query($sql,$con);
						$tr_i++;
					}
					$ii++;
				}
				
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
					$sql	=	"
						INSERT INTO ContratoParametroLocalCobranca SET 
							IdLoja 								= $local_IdLoja,
							IdContrato							= $local_IdContrato,
							IdLocalCobranca						= $local_IdLocalCobranca,
							IdLocalCobrancaParametroContrato	= $lin2[IdLocalCobrancaParametroContrato],
							Valor								='".$_POST['LocalCobranca_Valor_'.$lin2[IdLocalCobrancaParametroContrato]]."';";
					$local_transaction[$tr_i]	=	mysql_query($sql,$con);
					$tr_i++;
				}
				
				$sql = "select
						UrlRotinaCriacao,
						AtivacaoAutomatica
					from
						Servico
					where
						IdLoja = $local_IdLoja and
						IdServico = $local_IdServico2";
				$res = mysql_query($sql,$con);
				$lin = mysql_fetch_array($res);
		
				if($lin[UrlRotinaCriacao] != ''){
					include($Path.$lin[UrlRotinaCriacao]);
				}				
				$i++;
			}
			
		}
		
		for($i=0; $i<$tr_i; $i++){
			if($local_transaction[$i] == false){
				$local_transaction = false;
				break;
			}
		}
		
		if($local_transaction == true) {
			$sql = "COMMIT;";
			@mysql_query($sql,$con);
			//Fim verifica status Tipo Mensagem
			enviarEmailAdesaoServico($local_IdLoja, $local_IdContrato);	
			// Muda a a磯 para Inserir
			$local_Acao = 'alterar';
			$local_Erro = 3;			// Mensagem de Inser磯 Positiva
		} else {
			$sql = "ROLLBACK;";
			@mysql_query($sql,$con);
			// Muda a a磯 para Inserir
			$local_Acao = 'inserir';
			$local_Erro = 8;			// Mensagem de Inser磯 Negativa
		}
	}
?>
