<?
	set_time_limit(0);

	$Path = "../";

	include ("../files/conecta.php");
	include ("../files/funcoes.php");
	include ("../files/envia_email.php");

	$url_sistema = getParametroSistema(6,3);
	$IdTipoEmail = 6;
	$local_Login = 'automatico';

	$sql = "select
				ContaReceberBaseVencimento.IdLoja,
				Contrato.IdContrato,
				min(ContaReceber.IdContaReceber) IdContaReceber,
				Pessoa.IdPessoa,
				Pessoa.Nome,
				Pessoa.NomeRepresentante,
				Pessoa.TipoPessoa,
				Pessoa.Email,
				ContaReceber.NumeroDocumento,
				ContaReceber.DataVencimento,
				(ContaReceber.ValorLancamento - ContaReceber.ValorDesconto + ContaReceber.ValorDespesas) ValorTotal,
				LocalCobranca.IdLocalCobrancaLayout
			from
				ContaReceberBaseVencimento,
				LancamentoFinanceiroContaReceber,
				LancamentoFinanceiro,
				Contrato,
				Pessoa,
				ContaReceber,
				LocalCobranca
			where
				ContaReceberBaseVencimento.BaseVencimento >= 31 and
				ContaReceberBaseVencimento.IdStatus = 1 and
				ContaReceberBaseVencimento.IdLoja = LancamentoFinanceiroContaReceber.IdLoja and
				ContaReceberBaseVencimento.IdContaReceber = LancamentoFinanceiroContaReceber.IdContaReceber and
				ContaReceberBaseVencimento.IdContaReceber = ContaReceber.IdContaReceber and
				ContaReceberBaseVencimento.IdLoja = ContaReceber.IdLoja and
				LancamentoFinanceiroContaReceber.IdLoja = LancamentoFinanceiro.IdLoja and
				ContaReceberBaseVencimento.IdLoja = LocalCobranca.IdLoja and
				ContaReceber.IdLocalCobranca = LocalCobranca.IdLocalCobranca and
				LancamentoFinanceiroContaReceber.IdLancamentoFinanceiro = LancamentoFinanceiro.IdLancamentoFinanceiro and
				LancamentoFinanceiro.IdLoja = Contrato.IdLoja and
				LancamentoFinanceiro.IdContrato = Contrato.IdContrato and
				Contrato.IdStatus = 200 and
				Contrato.IdPessoa = Pessoa.IdPessoa and
				Pessoa.Email != ''
			group by
				Contrato.IdLoja,
				Contrato.IdContrato";
	$res = mysql_query($sql,$con);
	while($lin = mysql_fetch_array($res)){

		if($lin[TipoPessoa] == 1){
			$nome_responsavel = $lin[NomeRepresentante]." (".$lin[Nome].")";
		}else{
			$nome_responsavel = $lin[Nome];
		}

		$IdLoja					= $lin[IdLoja];
		$NumeroDocumento		= $lin[NumeroDocumento];
		$DataVencimento			= dataConv($lin[DataVencimento],'Y-m-d','d/m/Y');
		$ValorTotal				= getParametroSistema(5,1)." ".$lin[ValorTotal];
		$Link					= getParametroSistema(6,3)."/modulos/administrativo/local_cobranca/".$lin[IdLocalCobrancaLayout]."/index.php?IdLoja=".$lin[IdLoja]."&IdContaReceber=".$lin[IdContaReceber];
		$DataCorte				= date('Y-m-d');
		$assinatura				= getCodigoInterno(2,23);
		
		$sqlDiaCorte = "select IdCodigoInterno DiaSemana from CodigoInterno where IdLoja = $lin[IdLoja] and IdGrupoCodigoInterno = 18 and ValorCodigoInterno = 1";
		$resDiaCorte = mysql_query($sqlDiaCorte,$con);
		while($linDiaCorte = mysql_fetch_array($resDiaCorte)){
			$Cortar[$linDiaCorte[DiaSemana]] = 'S';
		}

		$Incrementa = '';

		while($Incrementa != 'N'){

			$DataCorteAno	= substr($DataCorte,0,4);
			$DataCorteMes	= substr($DataCorte,5,2);
			$DataCorteDia	= substr($DataCorte,8,2);

			$DataCorteTemp = mktime(0,0,0,$DataCorteMes,$DataCorteDia,$DataCorteAno); 
			$DataCorteWeek = date("w",$DataCorteTemp);

			if($Cortar[$DataCorteWeek] == 'S'){
				$Incrementa = 'N';
			}else{
				$DataCorte = incrementaData($DataCorte,1);
				$Incrementa = 'S';
			}
		}

		$DataCorte = dataConv($DataCorte,'Y-m-d','d/m/Y');

		$sqlEmail = "select 
						DescricaoTipoEmail, 
						EstruturaEmail, 
						AssuntoEmail 
					from 
						TipoEmail 
					where 
						IdLoja = $lin[IdLoja] and 
						IdTipoEmail = $IdTipoEmail";
		$resEmail = mysql_query($sqlEmail,$con);
		if($TipoEmail = mysql_fetch_array($resEmail)){

			$TipoEmail[EstruturaEmail]	= str_replace('$nome_responsavel',$nome_responsavel,$TipoEmail[EstruturaEmail]);
			$TipoEmail[EstruturaEmail]	= str_replace('$NumeroDocumento',$NumeroDocumento,$TipoEmail[EstruturaEmail]);
			$TipoEmail[EstruturaEmail]	= str_replace('$DataVencimento',$DataVencimento,$TipoEmail[EstruturaEmail]);
			$TipoEmail[EstruturaEmail]	= str_replace('$ValorTotal',$ValorTotal,$TipoEmail[EstruturaEmail]);
			$TipoEmail[EstruturaEmail]	= str_replace('$Link',$Link,$TipoEmail[EstruturaEmail]);
			$TipoEmail[EstruturaEmail]	= str_replace('$link_boleto',$Link,$TipoEmail[EstruturaEmail]);
			$TipoEmail[EstruturaEmail]	= str_replace('$DataCorte',$DataCorte,$TipoEmail[EstruturaEmail]);
			$TipoEmail[EstruturaEmail]	= str_replace('$url_sistema',$url_sistema,$TipoEmail[EstruturaEmail]);
			$TipoEmail[EstruturaEmail]	= str_replace('$descricao_tipo_email',$TipoEmail[DescricaoTipoEmail],$TipoEmail[EstruturaEmail]);
			$TipoEmail[EstruturaEmail]	= str_replace('$assinatura',$assinatura,$TipoEmail[EstruturaEmail]);
			$TipoEmail[EstruturaEmail]	= str_replace('\'','"',$TipoEmail[EstruturaEmail]);

			$sqlIdEmail = "select
						max(IdEmail) IdEmail
					from
						HistoricoEmail
					where
						IdLoja = $lin[IdLoja]";
			$resIdEmail = mysql_query($sqlIdEmail,$con);
			$linIdEmail = mysql_fetch_array($resIdEmail);
	
			$linIdEmail[IdEmail]++;

#			$lin[Email]	= 'douglas@cntsistemas.com.br';
	
			$sqlHistorico = "insert into HistoricoEmail(IdLoja, IdEmail, IdTipoEmail, ConteudoEmail, Email, IdPessoa, IdContaReceber, IdStatus, LoginCriacao ) values ($IdLoja,  $linIdEmail[IdEmail],  $IdTipoEmail,  '$TipoEmail[EstruturaEmail]',  '$lin[Email]',  $lin[IdPessoa],  $lin[IdContaReceber],  1,  '$local_Login' )";
			if(mysql_query($sqlHistorico,$con) == true){
				EnviaEmail($lin[IdLoja], $linIdEmail[IdEmail]);
				sleep(2);
			}
		}
	}
?>