<?
	if(permissaoSubOperacao($localModulo,$localOperacao,"U") == false){
		$local_Erro = 2;
	}else{
		$sql	=	"START TRANSACTION;";
		mysql_query($sql,$con);
		
		$tr_i = 0;
		
		if($local_SICIAtivoDefault == 1 && $local_IdTipoServico == 1){
			if($local_ColetarSICI != ''){
				$temp_ColetarSICI = $local_ColetarSICI;
				$local_ColetarSICI = "'".$local_ColetarSICI."'";
			} else{
				$$local_ColetarSICI = "NULL";
				$temp_ColetarSICI = '';
			}
			
			if($local_IdTecnologia != ''){
				$temp_IdTecnologia = $local_IdTecnologia;
				$local_IdTecnologia = "'".$local_IdTecnologia."'";
			} else{
				$local_IdTecnologia = "NULL";
				$temp_IdTecnologia = '';
			}
			
			if($local_IdDedicado != ''){;
				$temp_IdDedicado = $local_IdDedicado;
				$local_IdDedicado = "'".$local_IdDedicado."'";
			} else{
				$local_IdDedicado = "NULL";
				$temp_IdDedicado = '';
			}
			
			if($local_FatorMega != ''){
				$local_FatorMega = str_replace ('.' , '' , $local_FatorMega);
				$local_FatorMega = str_replace (',' , '.' , $local_FatorMega);
				$temp_FatorMega = $local_FatorMega;
				$local_FatorMega = "'".$local_FatorMega."'";
			} else{
				$local_FatorMega = "NULL";
				$temp_FatorMega = '';
			}
			
			if($local_IdGrupoVelocidade != ''){;
				$temp_IdGrupoVelocidade = $local_IdGrupoVelocidade;
				$local_IdGrupoVelocidade = "'".$local_IdGrupoVelocidade."'";
			} else{
				$local_IdGrupoVelocidade = "NULL";
				$temp_IdGrupoVelocidade = '';
			}
		} else{
			$local_IdTecnologia			= "NULL";
			$local_IdDedicado			= "NULL";
			$local_FatorMega			= "NULL";
			$local_IdGrupoVelocidade	= "NULL";
			$temp_ColetarSICI			= '';
			$temp_IdTecnologia			= '';
			$temp_IdDedicado			= '';
			$temp_FatorMega				= '';
			$temp_IdGrupoVelocidade		= '';
			
			if($local_ColetarSICI == ""){
				$local_ColetarSICI = "NULL";
			}
		}
		
		$sql = "
			SELECT
				IdTipoServico,
				DescricaoServico,
				Unidade,
				DetalheServico,
				IdServicoGrupo,
				IdStatus,
				ExibirReferencia,
				IdOrdemServicoLayout,
				FaturamentoFracionado,
				IdNotaFiscalTipo,
				IdCategoriaTributaria,
				IdCentroCusto,
				IdPlanoConta,
				MsgAuxiliarCobranca,
				AtivacaoAutomatica,
				EmailCobranca,
				ExecutarRotinas,
				DiasAvisoAposVencimento,
				DiasLimiteBloqueio,
				DetalheDemonstrativoTerceiro,
				Filtro_IdTipoPessoa,
				Filtro_IdPaisEstadoCidade,
				ColetarSICI,
				Tecnologia,
				Dedicado,
				FatorMega,
				GrupoVelocidade,
				Obs
			FROM
				Servico
			WHERE
				IdLoja = '$local_IdLoja' AND
				IdServico = '$local_IdServico';";
		$res = @mysql_query($sql,$con);
		$lin = @mysql_fetch_array($res);
		
		$lf = '';
		
		if($local_IdTipoServico != $lin[IdTipoServico]){
			$sql0 = "
				SELECT
					ValorParametroSistema
				FROM
					ParametroSistema
				WHERE
					IdGrupoParametroSistema = '71' AND
					IdParametroSistema = '$lin[IdTipoServico]';";
			$res0 = @mysql_query($sql0,$con);
			$lin0 = @mysql_fetch_array($res0);
			
			$sql1 = "
				SELECT
					ValorParametroSistema
				FROM
					ParametroSistema
				WHERE
					IdGrupoParametroSistema = '71' AND
					IdParametroSistema = '$local_IdTipoServico';";
			$res1 = @mysql_query($sql1,$con);
			$lin1 = @mysql_fetch_array($res1);
			
			$ObsServico .= $lf.date("d/m/Y H:i:s")." [".$local_Login."] - Alteração de Tipo Serviço [".$lin0[ValorParametroSistema]." > ".$lin1[ValorParametroSistema]."].";
			$lf = "\n";
		}
		
		if($local_DescricaoServico != $lin[DescricaoServico]){
			$ObsServico .= $lf.date("d/m/Y H:i:s")." [".$local_Login."] - Alteração de Nome Serviço [".$lin[DescricaoServico]." > ".$local_DescricaoServico."].";
			$lf = "\n";
		}
		
		if($local_Unidade != $lin[Unidade]){
			$sql0 = "
				SELECT
					ValorParametroSistema
				FROM
					ParametroSistema
				WHERE
					IdGrupoParametroSistema = '66' AND
					IdParametroSistema = '$lin[Unidade]';";
			$res0 = @mysql_query($sql0,$con);
			$lin0 = @mysql_fetch_array($res0);
			
			$sql1 = "
				SELECT
					ValorParametroSistema
				FROM
					ParametroSistema
				WHERE
					IdGrupoParametroSistema = '66' AND
					IdParametroSistema = '$local_Unidade';";
			$res1 = @mysql_query($sql1,$con);
			$lin1 = @mysql_fetch_array($res1);
			
			$ObsServico .= $lf.date("d/m/Y H:i:s")." [".$local_Login."] - Alteração de Unidade [".$lin0[ValorParametroSistema]." > ".$lin1[ValorParametroSistema]."].";
			$lf = "\n";
		}
		
		if($local_DetalheServico != $lin[DetalheServico]){
			$ObsServico .= $lf.date("d/m/Y H:i:s")." [".$local_Login."] - Alteração de Descrição do Serviço [".$lin[DetalheServico]." > ".$local_DetalheServico."].";
			$lf = "\n";
		}
		
		if($local_IdServicoGrupo != $lin[IdServicoGrupo]){
			$sql0 = "
				SELECT
					DescricaoServicoGrupo
				FROM 
					ServicoGrupo
				WHERE
					IdLoja = '$local_IdLoja' AND
					IdServicoGrupo = '$lin[IdServicoGrupo]';";
			$res0 = @mysql_query($sql0,$con);
			$lin0 = @mysql_fetch_array($res0);
			
			$sql1 = "
				SELECT
					DescricaoServicoGrupo
				FROM 
					ServicoGrupo
				WHERE
					IdLoja = '$local_IdLoja' AND
					IdServicoGrupo = '$local_IdServicoGrupo';";
			$res1 = @mysql_query($sql1,$con);
			$lin1 = @mysql_fetch_array($res1);
			
			$ObsServico .= $lf.date("d/m/Y H:i:s")." [".$local_Login."] - Alteração de Grupo Serviço [".$lin0[DescricaoServicoGrupo]." > ".$lin1[DescricaoServicoGrupo]."].";
			$lf = "\n";
		}
		
		if($local_IdStatus != $lin[IdStatus]){
			$sql0 = "
				SELECT
					ValorParametroSistema
				FROM
					ParametroSistema
				WHERE
					IdGrupoParametroSistema = '17' AND
					IdParametroSistema = '$lin[IdStatus]';";
			$res0 = @mysql_query($sql0,$con);
			$lin0 = @mysql_fetch_array($res0);
			
			$sql1 = "
				SELECT
					ValorParametroSistema
				FROM
					ParametroSistema
				WHERE
					IdGrupoParametroSistema = '17' AND
					IdParametroSistema = '$local_IdStatus';";
			$res1 = @mysql_query($sql1,$con);
			$lin1 = @mysql_fetch_array($res1);
			
			$ObsServico .= $lf.date("d/m/Y H:i:s")." [".$local_Login."] - Alteração de Status [".$lin0[ValorParametroSistema]." > ".$lin1[ValorParametroSistema]."].";
			$lf = "\n";
		}
		
		if($local_ExibirReferencia != $lin[ExibirReferencia]){
			$sql0 = "
				SELECT
					ValorParametroSistema
				FROM
					ParametroSistema
				WHERE
					IdGrupoParametroSistema = '42' AND
					IdParametroSistema = '$lin[ExibirReferencia]';";
			$res0 = @mysql_query($sql0,$con);
			$lin0 = @mysql_fetch_array($res0);
			
			$sql1 = "
				SELECT
					ValorParametroSistema
				FROM
					ParametroSistema
				WHERE
					IdGrupoParametroSistema = '42' AND
					IdParametroSistema = '$local_ExibirReferencia';";
			$res1 = @mysql_query($sql1,$con);
			$lin1 = @mysql_fetch_array($res1);
			
			$ObsServico .= $lf.date("d/m/Y H:i:s")." [".$local_Login."] - Alteração de Exibir Referência [".$lin0[ValorParametroSistema]." > ".$lin1[ValorParametroSistema]."].";
			$lf = "\n";
		}
		
		if($local_IdOrdemServicoLayout != $lin[IdOrdemServicoLayout]){
			$sql0 = "
				SELECT 
					DescricaoOrdemServicoLayout 
				FROM 
					OrdemServicoLayout 
				WHERE 
					IdOrdemServicoLayout = '$lin[IdOrdemServicoLayout]';";
			$res0 = @mysql_query($sql0,$con);
			$lin0 = @mysql_fetch_array($res0);
			
			$sql1 = "
				SELECT 
					DescricaoOrdemServicoLayout 
				FROM 
					OrdemServicoLayout 
				WHERE 
					IdOrdemServicoLayout = '$local_IdOrdemServicoLayout';";
			$res1 = @mysql_query($sql1,$con);
			$lin1 = @mysql_fetch_array($res1);
			
			$ObsServico .= $lf.date("d/m/Y H:i:s")." [".$local_Login."] - Alteração de Layout da Ordem de Serviço [".$lin0[DescricaoOrdemServicoLayout]." > ".$lin1[DescricaoOrdemServicoLayout]."].";
			$lf = "\n";
		}
		
		if($local_IdFaturamentoFracionado != $lin[FaturamentoFracionado]){
			$sql0 = "
				SELECT
					ValorParametroSistema
				FROM
					ParametroSistema
				WHERE
					IdGrupoParametroSistema = '172' AND
					IdParametroSistema = '$lin[FaturamentoFracionado]';";
			$res0 = @mysql_query($sql0,$con);
			$lin0 = @mysql_fetch_array($res0);
			
			$sql1 = "
				SELECT
					ValorParametroSistema
				FROM
					ParametroSistema
				WHERE
					IdGrupoParametroSistema = '172' AND
					IdParametroSistema = '$local_IdFaturamentoFracionado';";
			$res1 = @mysql_query($sql1,$con);
			$lin1 = @mysql_fetch_array($res1);
			
			$ObsServico .= $lf.date("d/m/Y H:i:s")." [".$local_Login."] - Alteração de Faturamento Fracionado [".$lin0[ValorParametroSistema]." > ".$lin1[ValorParametroSistema]."].";
			$lf = "\n";
		}
		
		if($lin[IdNotaFiscalTipo] == ''){
			$lin[IdNotaFiscalTipo] = 0;
		}
		
		if($local_IdNotaFiscalTipo != $lin[IdNotaFiscalTipo]){
			$sql0 = "
				SELECT
					NotaFiscalLayout.DescricaoNotaFiscalLayout
				FROM
					NotaFiscalLayout,
					NotaFiscalTipo
				WHERE
					NotaFiscalTipo.IdLoja = $local_IdLoja AND
					NotaFiscalLayout.IdNotaFiscalLayout = NotaFiscalTipo.IdNotaFiscalLayout AND
					NotaFiscalTipo.IdStatus = 1 AND
					NotaFiscalTipo.IdNotaFiscalTipo = '$lin[IdNotaFiscalTipo]';";
			$res0 = @mysql_query($sql0,$con);
			$lin0 = @mysql_fetch_array($res0);
			
			$sql1 = "
				SELECT
					NotaFiscalLayout.DescricaoNotaFiscalLayout
				FROM
					NotaFiscalLayout,
					NotaFiscalTipo
				WHERE
					NotaFiscalTipo.IdLoja = $local_IdLoja AND
					NotaFiscalLayout.IdNotaFiscalLayout = NotaFiscalTipo.IdNotaFiscalLayout AND
					NotaFiscalTipo.IdStatus = 1 AND
					NotaFiscalTipo.IdNotaFiscalTipo = '$local_IdNotaFiscalTipo';";
			$res1 = @mysql_query($sql1,$con);
			$lin1 = @mysql_fetch_array($res1);
			
			$ObsServico .= $lf.date("d/m/Y H:i:s")." [".$local_Login."] - Alteração de Tipo de Nota Fiscal [".$lin0[DescricaoNotaFiscalLayout]." > ".$lin1[DescricaoNotaFiscalLayout]."].";
			$lf = "\n";
		}
		
		if($local_IdCategoriaTributaria != $lin[IdCategoriaTributaria]){
			$sql0 = "
				SELECT
					ValorParametroSistema
				FROM
					ParametroSistema
				WHERE
					IdGrupoParametroSistema = '159' AND
					IdParametroSistema = '$lin[IdCategoriaTributaria]';";
			$res0 = @mysql_query($sql0,$con);
			$lin0 = @mysql_fetch_array($res0);
			
			$sql1 = "
				SELECT
					ValorParametroSistema
				FROM
					ParametroSistema
				WHERE
					IdGrupoParametroSistema = '159' AND
					IdParametroSistema = '$local_IdCategoriaTributaria';";
			$res1 = @mysql_query($sql1,$con);
			$lin1 = @mysql_fetch_array($res1);
			
			$ObsServico .= $lf.date("d/m/Y H:i:s")." [".$local_Login."] - Alteração de Categoria Tributária [".$lin0[ValorParametroSistema]." > ".$lin1[ValorParametroSistema]."].";
			$lf = "\n";
		}
		
		if($local_IdCentroCusto != $lin[IdCentroCusto]){
			$sql0 = "
				SELECT
					CentroCusto.DescricaoCentroCusto
				FROM 
					CentroCusto
				WHERE
					IdLoja = '$local_IdLoja' AND
					IdCentroCusto = '$lin[IdCentroCusto]';";
			$res0 = @mysql_query($sql0,$con);
			$lin0 = @mysql_fetch_array($res0);
			
			$sql1 = "
				SELECT
					CentroCusto.DescricaoCentroCusto
				FROM 
					CentroCusto
				WHERE
					IdLoja = '$local_IdLoja' AND
					IdCentroCusto = '$local_IdCentroCusto';";
			$res1 = @mysql_query($sql1,$con);
			$lin1 = @mysql_fetch_array($res1);
			
			$ObsServico .= $lf.date("d/m/Y H:i:s")." [".$local_Login."] - Alteração de Centro de Custo [".$lin0[DescricaoCentroCusto]." > ".$lin1[DescricaoCentroCusto]."].";
			$lf = "\n";
		}
		
		if($local_IdPlanoConta != $lin[IdPlanoConta]){
			$sql0 = "
				SELECT 
					DescricaoPlanoConta
				FROM 
					PlanoConta 
				WHERE
					IdLoja = '$local_IdLoja' AND
					IdPlanoConta = '$lin[IdPlanoConta]';";
			$res0 = @mysql_query($sql0,$con);
			$lin0 = @mysql_fetch_array($res0);
			
			$sql1 = "
				SELECT
					DescricaoPlanoConta
				FROM 
					PlanoConta 
				WHERE
					IdLoja = '$local_IdLoja' AND
					IdPlanoConta = '$local_IdPlanoConta';";
			$res1 = @mysql_query($sql1,$con);
			$lin1 = @mysql_fetch_array($res1);
			
			$ObsServico .= $lf.date("d/m/Y H:i:s")." [".$local_Login."] - Alteração de Plano de Conta [".$lin0[DescricaoPlanoConta]." > ".$lin1[DescricaoPlanoConta]."].";
			$lf = "\n";
		}
		
		if($local_MsgAuxiliarCobranca != $lin[MsgAuxiliarCobranca]){
			$ObsServico .= $lf.date("d/m/Y H:i:s")." [".$local_Login."] - Alteração de Mensagem Auxiliar de Cobrança [".$lin[MsgAuxiliarCobranca]." > ".$local_MsgAuxiliarCobranca."].";
			$lf = "\n";
		}
		
		if($local_AtivacaoAutomatica != $lin[AtivacaoAutomatica] && $local_IdTipoServico == 1){
			$sql0 = "
				SELECT
					ValorParametroSistema
				FROM
					ParametroSistema
				WHERE
					IdGrupoParametroSistema = '42' AND
					IdParametroSistema = '$lin[AtivacaoAutomatica]';";
			$res0 = @mysql_query($sql0,$con);
			$lin0 = @mysql_fetch_array($res0);
			
			$sql1 = "
				SELECT
					ValorParametroSistema
				FROM
					ParametroSistema
				WHERE
					IdGrupoParametroSistema = '42' AND
					IdParametroSistema = '$local_AtivacaoAutomatica';";
			$res1 = @mysql_query($sql1,$con);
			$lin1 = @mysql_fetch_array($res1);
			
			$ObsServico .= $lf.date("d/m/Y H:i:s")." [".$local_Login."] - Alteração de Ativação Autom [".$lin0[ValorParametroSistema]." > ".$lin1[ValorParametroSistema]."].";
			$lf = "\n";
		}
		
		if($local_EmailCobranca != $lin[EmailCobranca] && $local_IdTipoServico == 1){
			$sql0 = "
				SELECT
					ValorParametroSistema
				FROM
					ParametroSistema
				WHERE
					IdGrupoParametroSistema = '86' AND
					IdParametroSistema = '$lin[EmailCobranca]';";
			$res0 = @mysql_query($sql0,$con);
			$lin0 = @mysql_fetch_array($res0);
			
			$sql1 = "
				SELECT
					ValorParametroSistema
				FROM
					ParametroSistema
				WHERE
					IdGrupoParametroSistema = '86' AND
					IdParametroSistema = '$local_EmailCobranca';";
			$res1 = @mysql_query($sql1,$con);
			$lin1 = @mysql_fetch_array($res1);
			
			$ObsServico .= $lf.date("d/m/Y H:i:s")." [".$local_Login."] - Alteração de E-mail Cobrança [".$lin0[ValorParametroSistema]." > ".$lin1[ValorParametroSistema]."].";
			$lf = "\n";
		}
		
		if($local_ExecutarRotinas != $lin[ExecutarRotinas] && $local_IdTipoServico == 1){
			$sql0 = "
				SELECT
					ValorParametroSistema
				FROM
					ParametroSistema
				WHERE
					IdGrupoParametroSistema = '87' AND
					IdParametroSistema = '$lin[ExecutarRotinas]';";
			$res0 = @mysql_query($sql0,$con);
			$lin0 = @mysql_fetch_array($res0);
			
			$sql1 = "
				SELECT
					ValorParametroSistema
				FROM
					ParametroSistema
				WHERE
					IdGrupoParametroSistema = '87' AND
					IdParametroSistema = '$local_ExecutarRotinas';";
			$res1 = @mysql_query($sql1,$con);
			$lin1 = @mysql_fetch_array($res1);
			
			$ObsServico .= $lf.date("d/m/Y H:i:s")." [".$local_Login."] - Alteração de Executar Rotinas Diárias [".$lin0[ValorParametroSistema]." > ".$lin1[ValorParametroSistema]."].";
			$lf = "\n";
		}
		
		if($local_DiasAvisoAposVencimento != $lin[DiasAvisoAposVencimento]){
			$ObsServico .= $lf.date("d/m/Y H:i:s")." [".$local_Login."] - Alteração de Dias Aviso após Vencim [".$lin[DiasAvisoAposVencimento]." > ".$local_DiasAvisoAposVencimento."].";
			$lf = "\n";
		}
		
		if($local_DiasLimiteBloqueio != $lin[DiasLimiteBloqueio]){
			$ObsServico .= $lf.date("d/m/Y H:i:s")." [".$local_Login."] - Alteração de Dias p/ Bloqueio [".$lin[DiasLimiteBloqueio]." > ".$local_DiasLimiteBloqueio."].";
			$lf = "\n";
		}
		
		$IdPeriodicidadeTemp = explode('#', $local_Periodicidade);
		$Where = '';
		
		for($i = 0; $i < count($IdPeriodicidadeTemp); $i++){
			$IdPeriodicidadeTemp[$i] = explode('_', $IdPeriodicidadeTemp[$i]);
			$Where .= " AND 
				NOT(
					ServicoPeriodicidade.IdPeriodicidade = '".$IdPeriodicidadeTemp[$i][0]."' AND
					ServicoPeriodicidade.QtdParcela = '".$IdPeriodicidadeTemp[$i][1]."' AND
					ServicoPeriodicidade.TipoContrato = '".$IdPeriodicidadeTemp[$i][2]."' AND
					ServicoPeriodicidade.IdLocalCobranca = '".$IdPeriodicidadeTemp[$i][3]."' AND
					ServicoPeriodicidade.MesFechado = '".$IdPeriodicidadeTemp[$i][4]."'
				)";
		}
		
		$sql0 = "
			SELECT
				CONCAT(
					Periodicidade.DescricaoPeriodicidade,
					' / ',
					ServicoPeriodicidade.QtdParcela,
					' / ',
					Tipo.Valor,
					' / ',
					LocalCobranca.DescricaoLocalCobranca,
					' / ',
					MesFechado.Valor,
					' / ',
					ServicoPeriodicidade.QtdMesesFidelidade
				) DadosPeriodicidade
			FROM
				Periodicidade,
				ServicoPeriodicidade,
				LocalCobranca,
				(
					SELECT
						IdParametroSistema Id,
						ValorParametroSistema Valor
					FROM
						ParametroSistema
					WHERE
						ParametroSistema.IdGrupoParametroSistema = 70
				)MesFechado,
				(
					SELECT
						IdParametroSistema Id,
						ValorParametroSistema Valor
					FROM
						ParametroSistema
					WHERE
						ParametroSistema.IdGrupoParametroSistema = 28
				)Tipo
			WHERE
				Periodicidade.IdLoja = '$local_IdLoja' AND
				Periodicidade.IdLoja = LocalCobranca.IdLoja AND
				ServicoPeriodicidade.IdServico = '$local_IdServico' AND
				ServicoPeriodicidade.MesFechado = MesFechado.Id AND
				ServicoPeriodicidade.TipoContrato = Tipo.Id AND
				ServicoPeriodicidade.IdLocalCobranca = LocalCobranca.IdLocalCobranca AND
				ServicoPeriodicidade.IdLoja = Periodicidade.IdLoja AND
				ServicoPeriodicidade.IdPeriodicidade = Periodicidade.IdPeriodicidade 
				$Where;";
		$res0 = @mysql_query($sql0,$con);
		while($lin0 = @mysql_fetch_array($res0)){
			$ObsServico .= $lf.date("d/m/Y H:i:s")." [".$local_Login."] - Periodicidade Excluída: ".$lin0[DadosPeriodicidade].'.';
			$lf = "\n";
		}
		
		$IdServicoAgrupadorTemp = explode('#', $local_ServicoAgrupador);
		$IdServicoAgrupador = 0;
		
		for($i = 0; $i < count($IdServicoAgrupadorTemp); $i++){
			$IdServicoAgrupador .= ','.$IdServicoAgrupadorTemp[$i];
		}
		
		$sql0 = "
			SELECT
				ServicoAgrupado.IdServicoAgrupador
			FROM
				Servico,
				ServicoAgrupado
			WHERE
				Servico.IdLoja = '$local_IdLoja' AND
				ServicoAgrupado.IdServico = '$local_IdServico' AND
				ServicoAgrupado.IdLoja = Servico.IdLoja AND
				ServicoAgrupado.IdServicoAgrupador = Servico.IdServico AND
				ServicoAgrupado.IdServicoAgrupador NOT IN ($IdServicoAgrupador);";
		$res0 = @mysql_query($sql0,$con);
		while($lin0 = @mysql_fetch_array($res0)){
			$ObsServico .= $lf.date("d/m/Y H:i:s")." [".$local_Login."] - Serviço Agrupado Excluído: ".$lin0[IdServicoAgrupador].'.';
			$lf = "\n";
		}
		
		if($local_DetalheDemonstrativoTerceiro != $lin[DetalheDemonstrativoTerceiro]){
			$sql0 = "
				SELECT
					ValorParametroSistema
				FROM
					ParametroSistema
				WHERE
					IdGrupoParametroSistema = '93' AND
					IdParametroSistema = '$lin[DetalheDemonstrativoTerceiro]';";
			$res0 = @mysql_query($sql0,$con);
			$lin0 = @mysql_fetch_array($res0);
			
			$sql1 = "
				SELECT
					ValorParametroSistema
				FROM
					ParametroSistema
				WHERE
					IdGrupoParametroSistema = '93' AND
					IdParametroSistema = '$local_DetalheDemonstrativoTerceiro';";
			$res1 = @mysql_query($sql1,$con);
			$lin1 = @mysql_fetch_array($res1);
			
			$ObsServico .= $lf.date("d/m/Y H:i:s")." [".$local_Login."] - Alteração de Detalhe Demonstrativo [".$lin0[ValorParametroSistema]." > ".$lin1[ValorParametroSistema]."].";
			$lf = "\n";
		}
		
		if($local_IdTipoPessoa != $lin[Filtro_IdTipoPessoa]){
			$sql0 = "
				SELECT
					ValorParametroSistema
				FROM
					ParametroSistema
				WHERE
					IdGrupoParametroSistema = '1' AND
					IdParametroSistema = '$lin[Filtro_IdTipoPessoa]';";
			$res0 = @mysql_query($sql0,$con);
			$lin0 = @mysql_fetch_array($res0);
			
			$sql1 = "
				SELECT
					ValorParametroSistema
				FROM
					ParametroSistema
				WHERE
					IdGrupoParametroSistema = '1' AND
					IdParametroSistema = '$local_IdTipoPessoa';";
			$res1 = @mysql_query($sql1,$con);
			$lin1 = @mysql_fetch_array($res1);
			
			$ObsServico .= $lf.date("d/m/Y H:i:s")." [".$local_Login."] - Alteração de Filtro Tipo Pessoa [".$lin0[ValorParametroSistema]." > ".$lin1[ValorParametroSistema]."].";
			$lf = "\n";
		}
		
		$Where = '';
		$Filtro_IdPaisEstadoCidadeTemp = explode('^', $lin[Filtro_IdPaisEstadoCidade]);
		$IdPais = 0;
		$IdEstado = 0;
		$IdCidade = 0;
		
		for($i = 0; $i < count($Filtro_IdPaisEstadoCidadeTemp); $i++){
			$Filtro_IdPaisEstadoCidadeTemp[$i] = explode(',', $Filtro_IdPaisEstadoCidadeTemp[$i]);
			$IdPais .= ','.$Filtro_IdPaisEstadoCidadeTemp[$i][0];
			$IdEstado .= ','.$Filtro_IdPaisEstadoCidadeTemp[$i][1];
			$IdCidade .= ','.$Filtro_IdPaisEstadoCidadeTemp[$i][2];
		}
		
		$Where .= " AND
			Pais.IdPais IN (".$IdPais.") AND
			Estado.IdEstado IN (".$IdEstado.") AND
			Cidade.IdCidade IN (".$IdCidade.")";
		$Filtro_IdPaisEstadoCidadeTemp = explode('^', $local_Filtro_IdPaisEstadoCidade);
		
		for($i = 0; $i < count($Filtro_IdPaisEstadoCidadeTemp); $i++){
			$Filtro_IdPaisEstadoCidadeTemp[$i] = explode(',', $Filtro_IdPaisEstadoCidadeTemp[$i]);
			$Where .= " AND
				NOT(
					Pais.IdPais = '".$Filtro_IdPaisEstadoCidadeTemp[$i][0]."' AND
					Estado.IdEstado = '".$Filtro_IdPaisEstadoCidadeTemp[$i][1]."' AND
					Cidade.IdCidade = '".$Filtro_IdPaisEstadoCidadeTemp[$i][2]."'
				)";
		}
		
		$sql0 = "
			SELECT
				CONCAT(
					Pais.NomePais,
					' / ',
					Estado.NomeEstado,
					' / ',
					Cidade.NomeCidade
				)DadosCidade
			FROM
				Pais,
				Estado,
				Cidade
			WHERE
				Pais.IdPais = Estado.IdPais AND
				Estado.IdPais = Cidade.IdPais AND
				Estado.IdEstado = Cidade.IdEstado
				$Where;";
		$res0 = @mysql_query($sql0,$con);
		while($lin0 = @mysql_fetch_array($res0)){
			$ObsServico .= $lf.date("d/m/Y H:i:s")." [".$local_Login."] - Filtro Por Cidade Excluído: ".$lin0[DadosCidade].'.';
			$lf = "\n";
		}
		
		if($temp_ColetarSICI != $lin[ColetarSICI]){
			if($local_IdTipoServico != "2" && $local_IdTipoServico != "3"){
				$sql0 = "
					SELECT
						ValorParametroSistema
					FROM
						ParametroSistema
					WHERE
						IdGrupoParametroSistema = '261' AND
						IdParametroSistema = '$lin[ColetarSICI]';";
				$res0 = @mysql_query($sql0,$con);
				$lin0 = @mysql_fetch_array($res0);
				
				$sql1 = "
					SELECT
						ValorParametroSistema
					FROM
						ParametroSistema
					WHERE
						IdGrupoParametroSistema = '261' AND
						IdParametroSistema = '$temp_ColetarSICI';";
				$res1 = @mysql_query($sql1,$con);
				$lin1 = @mysql_fetch_array($res1);
				
				$ObsServico .= $lf.date("d/m/Y H:i:s")." [".$local_Login."] - Coletar SICI [".$lin0[ValorParametroSistema]." > ".$lin1[ValorParametroSistema]."].";
				$lf = "\n";
			}
		}
		
		if($temp_IdTecnologia != $lin[Tecnologia]){
			$sql0 = "
				select 
					IdTecnologia,
					DescricaoTecnologia 
				from
					SICITecnologia
				where
					IdTecnologia = '$lin[Tecnologia]';";
			$res0 = @mysql_query($sql0,$con);
			$lin0 = @mysql_fetch_array($res0);
			
			$sql1 = "
				select 
					IdTecnologia,
					DescricaoTecnologia 
				from
					SICITecnologia
				where
					IdTecnologia = '$temp_IdTecnologia';";
			$res1 = @mysql_query($sql1,$con);
			$lin1 = @mysql_fetch_array($res1);
			
			$ObsServico .= $lf.date("d/m/Y H:i:s")." [".$local_Login."] - Alteração de Tecnologia [".$lin0[DescricaoTecnologia]." > ".$lin1[DescricaoTecnologia]."].";
			$lf = "\n";
		}
		
		if($temp_IdDedicado != $lin[Dedicado]){
			$sql0 = "
				SELECT
					ValorParametroSistema
				FROM
					ParametroSistema
				WHERE
					IdGrupoParametroSistema = '191' AND
					IdParametroSistema = '$lin[Dedicado]';";
			$res0 = @mysql_query($sql0,$con);
			$lin0 = @mysql_fetch_array($res0);
			
			$sql1 = "
				SELECT
					ValorParametroSistema
				FROM
					ParametroSistema
				WHERE
					IdGrupoParametroSistema = '191' AND
					IdParametroSistema = '$temp_IdDedicado';";
			$res1 = @mysql_query($sql1,$con);
			$lin1 = @mysql_fetch_array($res1);
			
			$ObsServico .= $lf.date("d/m/Y H:i:s")." [".$local_Login."] - Alteração de Serviço Dedicado [".$lin0[ValorParametroSistema]." > ".$lin1[ValorParametroSistema]."].";
			$lf = "\n";
		}
		
		if($temp_FatorMega != $lin[FatorMega]){
			$ObsServico .= $lf.date("d/m/Y H:i:s")." [".$local_Login."] - Alteração de Fator MB [".$lin[FatorMega]." > ".$temp_FatorMega."].";
			$lf = "\n";
		}
		
		if($temp_IdGrupoVelocidade != $lin[GrupoVelocidade]){
			$sql0 = "
				SELECT
					ValorParametroSistema
				FROM
					ParametroSistema
				WHERE
					IdGrupoParametroSistema = '190' AND
					IdParametroSistema = '$lin[GrupoVelocidade]';";
			$res0 = @mysql_query($sql0,$con);
			$lin0 = @mysql_fetch_array($res0);
			
			$sql1 = "
				SELECT
					ValorParametroSistema
				FROM
					ParametroSistema
				WHERE
					IdGrupoParametroSistema = '190' AND
					IdParametroSistema = '$temp_IdGrupoVelocidade';";
			$res1 = @mysql_query($sql1,$con);
			$lin1 = @mysql_fetch_array($res1);
			
			$ObsServico .= $lf.date("d/m/Y H:i:s")." [".$local_Login."] - Alteração de Grupo de Velocidade [".$lin0[ValorParametroSistema]." > ".$lin1[ValorParametroSistema]."].";
			$lf = "\n";
		}
				
		if($lin[Obs] != '' && $ObsServico != ''){
			$ObsServico .= "\n".$lin[Obs];
		} else{
			$ObsServico .= $lin[Obs];
		}
		
		$ObsServico = str_replace("\'", "'", $ObsServico);
		$ObsServico = str_replace("'", "\'", $ObsServico);
		
		$local_IdLoja		=	$_SESSION["IdLoja"];
		
		if($local_IdOrdemServicoLayout == ''){
			$local_IdOrdemServicoLayout = 'NULL';
		}
		
		if($local_IdCategoriaTributaria == ''){
			$local_IdCategoriaTributaria = 'NULL';
		}
		
		if($local_AtivacaoAutomaticaTemp == ""){
			$local_AtivacaoAutomaticaTemp = 'NULL';
		}
		
		if($local_DiasAvisoAposVencimento == ""){
			$local_DiasAvisoAposVencimento= 'NULL';
		}else{
			$local_DiasAvisoAposVencimento	=	"'$local_DiasAvisoAposVencimento'";
		}
		
		if($local_IdTipoPessoa == ""){
			$local_IdTipoPessoa = 'NULL';
		}	
		
		if($local_IdFaturamentoFracionado == ""){
			$local_IdFaturamentoFracionado = 'NULL';
		}
		
		if($local_DiasLimiteBloqueio == ""){
			$local_DiasLimiteBloqueio= 'NULL';
		}
		
		if($local_IdNotaFiscalTipo == 0){
			$local_IdNotaFiscalTipo= 'NULL';
		}
		
		if($local_Cor == '' || ($local_IdTipoServico != 2 && $local_IdTipoServico != 3)){
			$local_Cor = 'NULL';
		} else{
			$local_Cor = "'$local_Cor'";
		}
		
		$local_Terceiros = explode(',',$local_Terceiros);
		$local_IdTerceiros = '0';
		
		for($i = 0; $i < count($local_Terceiros); $i++) {
			list($local_IdTerceiro , $local_ValorRepasseTerceiro, $local_PercentualRepasseTerceiro, $local_PercentualRepasseTerceiroOutros) = explode('_',$local_Terceiros[$i]);
			
			if($local_IdTerceiro != '') {
				$local_IdTerceiros .= ",$local_IdTerceiro";
			
				$sql = "SELECT 
							IdPessoa 
						FROM 
							ServicoTerceiro 
						WHERE
							IdLoja = $local_IdLoja AND
							IdServico = $local_IdServico AND
							IdPessoa = $local_IdTerceiro;";
				$res = mysql_query($sql,$con);
				
				if(@mysql_num_rows($res) == 0) {
					$sql = "INSERT INTO ServicoTerceiro SET 
								IdLoja							= $local_IdLoja,
								IdServico						= $local_IdServico, 
								IdPessoa						= $local_IdTerceiro,
								ValorRepasseTerceiro			= $local_ValorRepasseTerceiro, 
								PercentualRepasseTerceiro		= $local_PercentualRepasseTerceiro, 
								PercentualRepasseTerceiroOutros	= $local_PercentualRepasseTerceiroOutros;";
					$local_transaction[$tr_i] = mysql_query($sql,$con);
					$tr_i++;
				} else{
					$sql = "UPDATE ServicoTerceiro SET 
								ValorRepasseTerceiro			= $local_ValorRepasseTerceiro, 
								PercentualRepasseTerceiro		= $local_PercentualRepasseTerceiro, 
								PercentualRepasseTerceiroOutros	= $local_PercentualRepasseTerceiroOutros
							WHERE
								IdLoja = $local_IdLoja AND
								IdServico = $local_IdServico AND 
								IdPessoa = $local_IdTerceiro;";
					$local_transaction[$tr_i] = mysql_query($sql,$con);
					$tr_i++;
				}
			}
		}
		
		$sql = "SELECT 
					IdPessoa 
				FROM 
					ServicoTerceiro 
				WHERE
					IdLoja = $local_IdLoja AND
					IdServico = $local_IdServico AND
					IdPessoa NOT IN ($local_IdTerceiros);";
		$res = mysql_query($sql,$con);
		
		while($lin = @mysql_fetch_array($res)) {
			$sql = "DELETE FROM 
						ServicoTerceiro 
					WHERE 
						IdLoja = $local_IdLoja AND 
						IdServico = $local_IdServico AND 
						IdPessoa = $lin[IdPessoa];";
			$local_transaction[$tr_i] = mysql_query($sql,$con);
			$tr_i++;
		}
		
		/*
		 * Vincula um grupo device a um servico na tabela ServicoGrupoDevice
		*/
		if($local_GrupoDevice){
			foreach($local_GrupoDevice as $value){
				$sql = "INSERT INTO ServicoGrupoDevice SET
				IdLoja = $local_IdLoja,
				IdServico = $local_IdServico,
				IdGrupoDevice = $value";
				$local_transaction[$tr_i] = mysql_query($sql,$con);
				$tr_i++;
			}
		}
		
		/*
		 * Deleta grupo device da tabela ServicoGrupoDevice referente ao servico x
		*/
		if($local_removeGrupoDevice){
			foreach($local_removeGrupoDevice as $value){
				$sql = "DELETE FROM ServicoGrupoDevice
						WHERE IdLoja = $local_IdLoja AND IdServico = $local_IdServico AND IdGrupoDevice = $value";
				$local_transaction[$tr_i] = mysql_query($sql,$con);
				$tr_i++;
			}
		}
		
		$sql	=	"UPDATE Servico SET
						IdTipoServico					= '$local_IdTipoServico',
						IdServicoGrupo					= $local_IdServicoGrupo,
						IdOrdemServicoLayout			= $local_IdOrdemServicoLayout,
						DescricaoServico				= \"$local_DescricaoServico\",
						DescricaoServicoSMS				= \"$local_DescricaoServicoSMS\",
						IdCentroCusto					= $local_IdCentroCusto,
						IdPlanoConta					= '$local_IdPlanoConta',
						ContratoViaCDA					= $local_ContratoViaCDA,
						IdStatus						= $local_IdStatus,
						ExibirReferencia				= $local_ExibirReferencia,
						DetalheDemonstrativoTerceiro	= \"$local_DetalheDemonstrativoTerceiro\",
						Unidade							= '$local_Unidade',
						Cor								= $local_Cor,
						AtivacaoAutomatica				= $local_AtivacaoAutomaticaTemp,
						DetalheServico					= \"$local_DetalheServico\",
						EmailCobranca					= '$local_EmailCobrancaTemp',
						ExecutarRotinas					= '$local_ExecutarRotinas',
						DiasAvisoAposVencimento			= $local_DiasAvisoAposVencimento,
						Filtro_IdPaisEstadoCidade		= '$local_Filtro_IdPaisEstadoCidade',
						MsgAuxiliarCobranca				= \"$local_MsgAuxiliarCobranca\",
						Filtro_IdTipoPessoa				= $local_IdTipoPessoa,
						IdNotaFiscalTipo				= $local_IdNotaFiscalTipo,
						IdCategoriaTributaria			= $local_IdCategoriaTributaria,
						DiasLimiteBloqueio				= $local_DiasLimiteBloqueio,
						FaturamentoFracionado			= $local_IdFaturamentoFracionado,
						Tecnologia						= $local_IdTecnologia,
						Dedicado						= $local_IdDedicado,
						FatorMega						= $local_FatorMega,
						ColetarSICI						= $local_ColetarSICI,
						GrupoVelocidade					= $local_IdGrupoVelocidade,
						Obs								= \"$ObsServico\",
						DataAlteracao					= (concat(curdate(),' ',curtime())),
						LoginAlteracao					= '$local_Login'
					WHERE 
						IdLoja					= $local_IdLoja and
						IdServico				= $local_IdServico";
		$local_transaction[$tr_i]	=	mysql_query($sql,$con);
		$tr_i++;
		
		$sql	=	"DELETE from ServicoPeriodicidade where IdLoja = $local_IdLoja and IdServico = $local_IdServico";
		$local_transaction[$tr_i]	=	mysql_query($sql,$con);
		$tr_i++;
		
		$sql	=	"DELETE from ServicoAgrupado where IdLoja = $local_IdLoja and IdServico = $local_IdServico";
		$local_transaction[$tr_i]	=	mysql_query($sql,$con);
		$tr_i++;
		
		switch($local_IdTipoServico){
			case '1':
				$ii		=	0;
				$temp	=	explode('#',$local_Periodicidade);
				
				while($temp[$ii] != ''){
					$temp2	=	explode('_',$temp[$ii]);
				
					$local_IdPeriodicidade		=	$temp2[0];
					$local_QtdParcela			=	$temp2[1];
					$local_TipoContrato			=	$temp2[2];
					$local_IdLocalCobranca		=	$temp2[3];
					$local_MesFechado			=	$temp2[4];
					$local_QtdMesesFidelidade	=	$temp2[5];
					
					$sql	=	"
						INSERT INTO ServicoPeriodicidade SET 
							IdLoja						= $local_IdLoja,
							IdServico					= $local_IdServico,
							IdPeriodicidade				= $local_IdPeriodicidade,
							QtdParcela					= '$local_QtdParcela',
							TipoContrato				= '$local_TipoContrato',
							IdLocalCobranca				= '$local_IdLocalCobranca',
							MesFechado					= '$local_MesFechado',
							QtdMesesFidelidade			= '$local_QtdMesesFidelidade'";
					$local_transaction[$tr_i]	=	mysql_query($sql,$con);
					$tr_i++;
					
					$ii++;
				}
				break;
			case '3':
				$ii		=	0;
				$temp	=	explode('#',$local_ServicoAgrupador);
				
				while($temp[$ii] != ''){
					$local_IdServicoAgrupador	=	$temp[$ii];
				
					$sql	=	"
						INSERT INTO ServicoAgrupado SET 
							IdLoja						= $local_IdLoja,
							IdServico					= $local_IdServico,
							IdServicoAgrupador			= $local_IdServicoAgrupador;";
					$local_transaction[$tr_i]	=	mysql_query($sql,$con);
					$tr_i++;
					
					$ii++;
				}
				break;
			case '4':
				$sql	=	"DELETE from ServicoAgrupado where IdLoja = $local_IdLoja and IdServico = $local_IdServico";
				$local_transaction[$tr_i]	=	mysql_query($sql,$con);
				$tr_i++;
				
				$ii		=	0;
				$temp	=	explode('#',$local_ServicoAgrupador);
				
				while($temp[$ii] != ''){
					$local_IdServicoAgrupador	=	$temp[$ii];
				
					$sql	=	"
						INSERT INTO ServicoAgrupado SET 
							IdLoja						= $local_IdLoja,
							IdServico					= $local_IdServico,
							IdServicoAgrupador			= $local_IdServicoAgrupador;";
					$local_transaction[$tr_i]	=	mysql_query($sql,$con);
					$tr_i++;
					
					$ii++;
				}
				break;
		}

		if($local_IdCategoriaTributaria == 1){
			$sql	=	"
					UPDATE ServicoAliquota SET
						Aliquota = NULL
					where
						IdLoja 	= $local_IdLoja and
						IdServico  = $local_IdServico and
						IdAliquotaTipo	= 1";
			$local_transaction[$tr_i]	=	mysql_query($sql,$con);			
			$tr_i++;
		}
		
		if($local_ServicoVinculado == ''){
			$local_ServicoVinculado = 0;
		}
		
		$sql = "SELECT
					IdServico,
					IdServicoAgrupador
				FROM 
					ServicoAgrupado
				WHERE 
					ServicoAgrupado.IdLoja = '$local_IdLoja' AND 
					ServicoAgrupado.IdServicoAgrupador = '$local_IdServico' AND 
					ServicoAgrupado.IdServico NOT IN ($local_ServicoVinculado);";
		$res = @mysql_query($sql, $con);
		while($lin = @mysql_fetch_array($res)){
			$sql0 = "DELETE FROM 
						ServicoAgrupado
					WHERE 
						IdLoja = '$local_IdLoja' AND 
						IdServico = '$lin[IdServico]' AND 
						IdServicoAgrupador = '$lin[IdServicoAgrupador]';";
			$local_transaction[$tr_i] = mysql_query($sql0, $con);			
			$tr_i++;
		}
		
		for($i=0; $i<$tr_i; $i++){
			if($local_transaction[$i] == false){
				$local_transaction = false;				
			}
		}
		
		if($local_transaction == true){
			$sql = "COMMIT;";
			$local_Erro = 4;			// Mensagem de Alteração Positiva
		}else{
			$sql = "ROLLBACK;";
			$local_Erro = 5;			// Mensagem de Alteração Negativa
		}
		
		mysql_query($sql,$con);
	}
?>
