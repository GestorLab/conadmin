<?
	$localModulo		=	1;
	$localOperacao		=	133;
	$localSuboperacao	=	"R";
		
	include ('../../files/conecta.php');
	include ('../../files/funcoes.php');
	include ('../../rotinas/verifica.php');
	 	
	$local_IdLoja				= $_SESSION['IdLoja'];
	$local_IdPessoaLogin		= $_SESSION['IdPessoa'];
	$local_Login				= $_SESSION["Login"];
	$filtro						= $_POST['filtro'];
	$filtro_ordem				= $_POST['filtro_ordem'];
	$filtro_ordem_direcao		= $_POST['filtro_ordem_direcao'];
	$filtro_localTipoDado		= $_POST['filtro_tipoDado'];
	$filtro_ordem2				= $_POST['filtro_ordem2'];
	$filtro_ordem_direcao2		= $_POST['filtro_ordem_direcao2'];
	$filtro_localTipoDado2		= $_POST['filtro_tipoDado2'];
	$filtro_pessoa				= url_string_xsl($_POST['filtro_pessoa'],'url',false);
	$filtro_descricao_servico	= url_string_xsl($_POST['filtro_descricao_servico'],'url',false);
	$filtro_data_inicio			= $_POST['filtro_data_inicio'];
	$filtro_data_termino		= $_POST['filtro_data_termino'];
	$filtro_status				= $_POST['filtro_status'];
	$filtro_cancelado			= $_POST['filtro_contrato_cancelado'];
	$filtro_soma				= $_POST['filtro_contrato_soma'];
	$filtro_mes_fechado			= $_POST['filtro_mes_fechado'];
	$filtro_local_cobranca		= $_POST['filtro_local_cobranca'];
	$filtro_periodicidade		= $_POST['filtro_periodicidade'];	
	$filtro_data_inicio_cobranca= $_POST['filtro_data_inicio_cobranca'];	
	$filtro_data_base			= $_POST['filtro_data_base'];	
	$filtro_data_base_nula		= $_POST['filtro_data_base_nula'];									
	$filtro_IdPessoa			= $_GET['IdPessoa'];
	$filtro_IdContrato			= $_GET['IdContrato'];
	$filtro_id_servico			= $_POST['filtro_id_servico'];
	$filtro_parametro			= $_POST['filtro_parametro'];
	$filtro_valor_parametro		= url_string_xsl($_POST['filtro_valor_parametro'],'url',false);
	$filtro_limit				= $_POST['filtro_limit'];
	if($filtro_IdPessoa == ''){
		$filtro_IdPessoa		= $_POST['IdPessoa'];
	}
	
	if($filtro_IdContrato == ''){
		$filtro_IdContrato		= $_POST['IdContrato'];
	}
	
	$filtro_cancelado				= $_SESSION["filtro_contrato_cancelado"];	
	$filtro_soma					= $_SESSION["filtro_contrato_soma"];
	$filtro_QTDCaracterColunaPessoa	= $_SESSION["filtro_QTDCaracterColunaPessoa"];	
	
	$filtro_url	 = "";
	$filtro_sql  = "";
	$filtro_from = "";
	$order_by	 = "";
	
	LimitVisualizacao("listar");
	
	if($filtro != "")
		$filtro_url		.= "&Filtro=$filtro";
		
	if($filtro_ordem != "")				$filtro_url	.= "&Ordem=$filtro_ordem";
	if($filtro_ordem_direcao != "")		$filtro_url .= "&OrdemDirecao=$filtro_ordem_direcao";
	if($filtro_localTipoDado != "")		$filtro_url .= "&TipoDado=$filtro_localTipoDado";
	
	if($filtro_ordem2 != "")			$filtro_url	.= "&Ordem2=$filtro_ordem2";
	if($filtro_ordem_direcao2 != "")	$filtro_url .= "&OrdemDirecao2=$filtro_ordem_direcao2";
	if($filtro_localTipoDado2 != "")	$filtro_url .= "&TipoDado2=$filtro_localTipoDado2";
	
	if($filtro_pessoa!=''){
		$filtro_url .= "&Pessoa=".$filtro_pessoa;
		$filtro_pessoa = str_replace("'", "\'", $filtro_pessoa);
		$filtro_sql .= " and Pessoa.IdPessoa in (select IdPessoa from Pessoa where Nome like '%$filtro_pessoa%' or RazaoSocial like '%$filtro_pessoa%')";
	}
	
	if($filtro_IdPessoa!=''){
		$filtro_url .= "&IdPessoa=".$filtro_IdPessoa;
		$filtro_sql .= " and Contrato.IdPessoa = '$filtro_IdPessoa'";
	}
	
	if($filtro_IdContrato!=''){
		$filtro_url .= "&IdContrato=".$filtro_IdContrato;
		$filtro_sql .= " and Contrato.IdContrato = '$filtro_IdContrato'";
	}
	
	if($filtro_parametro!=""){
		$filtro_from .= " , ServicoParametro";
		$filtro_url .= "&DescricaoParametroServico=".$filtro_parametro;
		$filtro_sql .= " and ServicoParametro.IdServico = Servico.IdServico and ServicoParametro.DescricaoParametroServico = '$filtro_parametro'";
		$filtro_sql .= " and Contrato.IdLoja = ServicoParametro.IdLoja";
	
		$filtro_from .= " , ContratoParametro";
		$filtro_url .= "&ValorParametroServico=".$filtro_valor_parametro;
		
		if($filtro_valor_parametro != ""){
			$filtro_sql .= " and ContratoParametro.IdContrato = Contrato.IdContrato and ContratoParametro.IdParametroServico = ServicoParametro.IdParametroServico and ContratoParametro.Valor like '%$filtro_valor_parametro%'";
		}else{
			$filtro_sql .= " and ContratoParametro.IdContrato = Contrato.IdContrato and ContratoParametro.IdParametroServico = ServicoParametro.IdParametroServico and ContratoParametro.Valor = '$filtro_valor_parametro'";		
		}
	}
	
	if($filtro_id_servico!=''){
		$filtro_url .= "&IdServico=".$filtro_id_servico;
		$filtro_sql .= " and Contrato.IdServico = '$filtro_id_servico'";
	}
		
	if($filtro_descricao_servico!=""){
		$filtro_url .= "&DescricaoServico=".$filtro_descricao_servico;
		$filtro_sql .= " and Servico.DescricaoServico like '%$filtro_descricao_servico%'";
	}
		
	if($filtro_data_inicio!=""){
		$filtro_url .= "&DataInicio=".$filtro_data_inicio;
		$filtro_data_inicio = dataConv($filtro_data_inicio,'d/m/Y','Y-m-d');
		$filtro_sql .= " and ContratoVigencia.DataInicio >= '$filtro_data_inicio'";
	}
	
	if($filtro_data_termino!=""){
		$filtro_url .= "&DataTermino=".$filtro_data_termino;
		$filtro_data_termino = dataConv($filtro_data_termino,'d/m/Y','Y-m-d');
		$filtro_sql .= " and ContratoVigencia.DataTermino <= '$filtro_data_termino'";
	}
	
	if($filtro_valor!=''){
		$filtro_url .= "&Valor=".$filtro_valor;
		$filtro_sql .= " and ContratoVigencia.Valor like '%$filtro_valor%'";
	}
	
	if($filtro_local_cobranca!=''){
		$filtro_url .= "&IdLocalCobranca=".$filtro_local_cobranca;
		$filtro_sql .= " and Contrato.IdLocalCobranca = '$filtro_local_cobranca'";
	}	
	
	if($filtro_periodicidade!=''){
		$filtro_url .= "&IdPeriodicidade=".$filtro_periodicidade;
		$filtro_sql .= " and Contrato.IdPeriodicidade = '$filtro_periodicidade'";	
	}
	
	if($filtro_mes_fechado!=''){	
		$filtro_url .= "&MesFechado=".$filtro_mes_fechado;
		$filtro_sql .= " and Contrato.MesFechado = '$filtro_mes_fechado'";
	}
	
	if($filtro_data_inicio_cobranca!=''){
		$filtro_url .= "&DataInicioCobranca=".$filtro_data_inicio_cobranca;
		$filtro_data_inicio_cobranca = dataConv($filtro_data_inicio_cobranca,'d/m/Y','Y-m-d');
		$filtro_sql .= " and Contrato.DataPrimeiraCobranca = '$filtro_data_inicio_cobranca'";	
	}	
	
	if($filtro_data_base!=''){
		$filtro_data_base = dataConv($filtro_data_base,'d/m/Y','Y-m-d');
		$filtro_sql .= " and Contrato.DataBaseCalculo = '$filtro_data_base'";	
	}	
	
	if($filtro_data_base_nula!=''){	
		switch($filtro_data_base_nula){
			case 1:
				$filtro_sql .= " and Contrato.DataBaseCalculo is null";		
				break;
			case 2:
				$filtro_sql .= " and Contrato.DataBaseCalculo is not null";		
				break;
		}		
	}
	
	if($filtro_status!=''){
		$filtro_url .= "&IdStatus=".$filtro_status;
		
		$aux	=	explode("G_",$filtro_status);
		
		if($aux[1]!=""){
			switch($aux[1]){
				case '1':
					$filtro_sql .= " and (Contrato.IdStatus >= 1 and Contrato.IdStatus < 199)";
					break;
				case '2':
					$filtro_sql .= " and (Contrato.IdStatus >= 200 and Contrato.IdStatus < 300)";
					break;
				case '3':
					$filtro_sql .= " and (Contrato.IdStatus >= 300 and Contrato.IdStatus < 400)";
					break;
			}
		}else{
			$filtro_sql .= " and Contrato.IdStatus = '$filtro_status'";
		}
	}
	
	if($filtro_soma!=""){
		$filtro_url .= "&Soma=".$filtro_soma;
	}
	
	if($filtro_cancelado != ""){
		$filtro_url .= "&Cancelado=".$filtro_cancelado;
		if($filtro_status == ""){
			if($filtro_cancelado == 2){
				switch($filtro_status){
					case 'G_1':
						$filtro_sql  .= " and (Contrato.IdStatus >0 and Contrato.IdStatus <=499)";
						break;
					case 102:
						$filtro_sql  .= " and (Contrato.IdStatus >0 and Contrato.IdStatus <=499)";
						break;
					case 101:
						$filtro_sql  .= " and (Contrato.IdStatus >0 and Contrato.IdStatus <=499)";
						break;
					case 1:
						$filtro_sql  .= " and (Contrato.IdStatus >0 and Contrato.IdStatus <=499)";
						break;
					default :
						$filtro_sql  .= " and (Contrato.IdStatus >199 and Contrato.IdStatus <=499)";
						break;
					
				}
			}
		}
	}
	/*
	if($_SESSION["RestringirCarteira"] == true){
		$sqlAux		.=	",(select 
								AgenteAutorizadoPessoa.IdContrato 
						   from 
								AgenteAutorizadoPessoa,
								Carteira 
						   where 
								AgenteAutorizadoPessoa.IdLoja = $local_IdLoja and 
								AgenteAutorizadoPessoa.IdLoja = Carteira.IdLoja and 
								AgenteAutorizadoPessoa.IdCarteira = Carteira.IdCarteira and 
								Carteira.IdCarteira = $local_IdPessoaLogin and 
								Carteira.Restringir = 1 and 
								Carteira.IdStatus = 1
							) ContratoCarteira";
		$filtro_sql .=  " and  Contrato.IdContrato = ContratoCarteira.IdContrato";
	}else{
		if($_SESSION["RestringirAgenteAutorizado"] == true){
			$sqlAux		.=	",(select 
									AgenteAutorizadoPessoa.IdContrato
								from 
									AgenteAutorizadoPessoa,
									AgenteAutorizado
								where 
									AgenteAutorizadoPessoa.IdLoja = $local_IdLoja  and 
									AgenteAutorizado.IdLoja = AgenteAutorizadoPessoa.IdLoja and 
									AgenteAutorizado.IdAgenteAutorizado = AgenteAutorizadoPessoa.IdAgenteAutorizado and 
									AgenteAutorizado.IdAgenteAutorizado = $local_IdPessoaLogin and 
									AgenteAutorizado.Restringir = 1 and 
									AgenteAutorizado.IdStatus = 1
								) ContratoAgenteAutorizado";
			$filtro_sql .=  " and Contrato.IdContrato = ContratoAgenteAutorizado.IdContrato";
		}
		if($_SESSION["RestringirAgenteCarteira"] == true){
			$sqlAux		.=	",(select 
									AgenteAutorizadoPessoa.IdContrato
								from 
									AgenteAutorizadoPessoa,
									AgenteAutorizado,
									Carteira
								where 
									AgenteAutorizadoPessoa.IdLoja = $local_IdLoja  and 
									AgenteAutorizadoPessoa.IdLoja = AgenteAutorizado.IdLoja  and 
									AgenteAutorizadoPessoa.IdLoja = Carteira.IdLoja and
									AgenteAutorizado.IdAgenteAutorizado = AgenteAutorizadoPessoa.IdAgenteAutorizado and 
									AgenteAutorizado.IdAgenteAutorizado = Carteira.IdAgenteAutorizado and
									Carteira.IdCarteira = $local_IdPessoaLogin and 
									AgenteAutorizado.Restringir = 1 and 
									AgenteAutorizado.IdStatus = 1 	
								) ContratoAgenteCarteira";
			$filtro_sql .=  " and  Contrato.IdContrato = ContratoAgenteCarteira.IdContrato";
		}
	}*/
	$sqlAgente="select 
					Restringir
				from
					AgenteAutorizado 
				where 
					AgenteAutorizado.IdLoja = $local_IdLoja and
					AgenteAutorizado.IdAgenteAutorizado = $local_IdPessoaLogin and
					AgenteAutorizado.IdStatus = 1 ";
	$resAgente = mysql_query($sqlAgente,$con);
	if($linAgente = mysql_fetch_array($resAgente)){
		
		$sqlCarteira="	select 
							Restringir 
						from
							Carteira 
						where
							Carteira.IdLoja = $local_IdLoja and
							Carteira.IdCarteira = $local_IdPessoaLogin and
							Carteira.IdStatus = 1";
		$resCarteira = mysql_query($sqlCarteira,$con);
		$linCarteira = mysql_fetch_array($resCarteira);
		
		if($linAgente["Restringir"] == '1'){
			$restringirAgente = "AgenteAutorizado.Restringir = '$linAgente[Restringir]' and";
			if($linCarteira["Restringir"] == '1'){
				$restringirCarteira = "AgenteAutorizadoPessoa.IdCarteira = $local_IdPessoaLogin and";
			}else{
				$restringirCarteira = "";
			}
			$sqlAux		.=	",(select 
									AgenteAutorizadoPessoa.IdContrato
								from 
									AgenteAutorizadoPessoa,
									AgenteAutorizado,
									Carteira
								where 
									AgenteAutorizadoPessoa.IdLoja = $local_IdLoja  and 
									AgenteAutorizado.IdLoja = AgenteAutorizadoPessoa.IdLoja and 
									AgenteAutorizado.IdAgenteAutorizado = AgenteAutorizadoPessoa.IdAgenteAutorizado and 
									AgenteAutorizado.IdAgenteAutorizado = $local_IdPessoaLogin and 
									$restringirAgente
									$restringirCarteira
									AgenteAutorizado.IdAgenteAutorizado = Carteira.IdAgenteAutorizado and 
									AgenteAutorizado.IdStatus = 1
								) ContratoAgenteAutorizado";
			$filtro_sql .=  " and Contrato.IdContrato = ContratoAgenteAutorizado.IdContrato";
		}
	}
	
	if($filtro_limit!="")
		$filtro_url .= "&Limit=".$filtro_limit;
		
	if($filtro_url != ""){
		$filtro_url	= "?f=t".$filtro_url;	
		$filtro_url	= url_string_xsl($filtro_url,'convert',false);
	}
	
	header ("content-type: text/xml");
	
	echo "<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
	echo "<?xml-stylesheet type=\"text/xsl\" href=\"listar_contrato_periodicidade_xsl.php$filtro_url\"?>";
	echo "<db>";
	
	if($filtro == "s"){
		if($filtro_limit != ""){
			$Limit	= " limit $filtro_limit";
		}
	}else{
		if($filtro_limit == ""){
			$Limit 	= " limit 0,".getCodigoInterno(7,5);
		}else{
			$Limit 	= " limit 0,".$filtro_limit;
		}
	}
	
	$sql	=	"select
					distinct
				    Contrato.IdContrato,
				    Contrato.IdServico,
				    substring(Servico.DescricaoServico,1,12) DescricaoServico,
				    Servico.DescricaoServico as DescricaoServicoAlt,
				    Contrato.IdPessoa,				    
					Pessoa.TipoPessoa,
				    substring(Pessoa.Nome,1,$filtro_QTDCaracterColunaPessoa) Nome,
				    substring(Pessoa.RazaoSocial,1,$filtro_QTDCaracterColunaPessoa) RazaoSocial,
				    Contrato.DataInicio,
				    Contrato.DataBaseCalculo,
				    LocalCobranca.AbreviacaoNomeLocalCobranca,
				    ContratoVigenciaAtiva.Valor,
					ContratoVigenciaAtiva.ValorDesconto,
				    (ContratoVigenciaAtiva.Valor - ContratoVigenciaAtiva.ValorDesconto) ValorFinal,
					ContratoVigenciaAtiva.IdTipoDesconto,
				    Contrato.TipoContrato,
				    Contrato.IdStatus,
				    Contrato.VarStatus,
				    Contrato.DiaCobranca,
				    Contrato.MesFechado,
				    Contrato.DataPrimeiraCobranca,
				    Periodicidade.DescricaoPeriodicidade,
				    Contrato.QtdParcela
				from
				    Loja,
				    Contrato left join ContratoVigenciaAtiva on (
						Contrato.IdLoja = ContratoVigenciaAtiva.IdLoja and 
						Contrato.IdContrato = ContratoVigenciaAtiva.IdContrato
					), 
				    Servico,
				    Pessoa,
				    LocalCobranca,
					Periodicidade$filtro_from
					$sqlAux
				where
				    Loja.IdLoja = $local_IdLoja and
				    Contrato.IdLoja = $local_IdLoja and
				    Loja.IdLoja = Servico.IdLoja and
				    Loja.IdLoja = LocalCobranca.IdLoja and
				    Loja.IdLoja = Periodicidade.IdLoja and				    
				    Contrato.IdServico = Servico.IdServico and
				    Contrato.IdPessoa = Pessoa.IdPessoa and
				    Contrato.IdLocalCobranca = LocalCobranca.IdLocalCobranca and
					Contrato.IdPeriodicidade = Periodicidade.IdPeriodicidade and
					Periodicidade.Ativo = 1
					$filtro_sql
				order by
					Contrato.IdContrato desc
				$Limit";
	$res	=	mysql_query($sql,$con);
	while($lin	=	mysql_fetch_array($res)){
		$lin[ValorFinalTemp] = number_format($lin[ValorFinal],2,",","");
		
		if($lin[ValorFinal] == ''){
			$lin[ValorFinal] = 0;
		}
		
		$lin[ValorTemp] = number_format($lin[Valor],2,",","");
		
		if($lin[Valor] == ''){
			$lin[Valor] = 0;
		}
		
		$lin[ValorDescontoTemp] = number_format($lin[ValorDesconto],2,",","");
		
		if($lin[ValorDesconto] == ''){
			$lin[ValorDesconto]	=	0;
		}
		
		$lin[DataPrimeiraCobrancaTemp] 	= dataConv($lin[DataPrimeiraCobranca],"Y-m-d","d/m/Y");
		$lin[DataBaseCalculoTemp] 		= dataConv($lin[DataBaseCalculo],"Y-m-d","d/m/Y");
		
		$lin[DataPrimeiraCobranca] 		= dataConv($lin[DataPrimeiraCobranca],"Y-m-d","Ymd");
		$lin[DataBaseCalculo] 			= dataConv($lin[DataBaseCalculo],"Y-m-d","Ymd");
		
		$lin[DataTemporaria]	= dataConv($lin[VarStatus],"d/m/Y","Ymd");
		
		$sql2 = "select substr(ValorParametroSistema,1,3) ValorParametroSistema  from ParametroSistema where IdGrupoParametroSistema=28 and IdParametroSistema=$lin[TipoContrato]";
		$res2 = @mysql_query($sql2,$con);
		$lin2 = @mysql_fetch_array($res2);
		
		$sql3 = "select ValorParametroSistema Status  from ParametroSistema where IdGrupoParametroSistema=69 and IdParametroSistema=$lin[IdStatus]";
		$res3 = @mysql_query($sql3,$con);
		$lin3 = @mysql_fetch_array($res3);
		
		
		$sql10	=	"select IdContrato IdContratoPai from ContratoAutomatico where IdLoja = $local_IdLoja and IdContratoAutomatico = $lin[IdContrato] group by IdContrato";
		$res10 	= 	@mysql_query($sql10,$con);
		$lin10  = 	@mysql_fetch_array($res10);		
		
		if($lin10[IdContratoPai] != ""){
			$Img	  = "../../img/estrutura_sistema/ico_del_c.gif";
		}else{
			if($lin[IdStatus] == 1){
				$Img	  = "../../img/estrutura_sistema/ico_del.gif";
			}else{
				$Img	  = "../../img/estrutura_sistema/ico_del_c.gif";
			}
		}
		
		$lin3[StatusDesc]	=	$lin3[Status];
		
		if($lin[VarStatus] != ''){
			switch($lin[IdStatus]){
				case '201':
					$lin3[StatusDesc]	=	str_replace("Temporariamente","até $lin[VarStatus]",$lin3[Status]);		
					break;
			}					
		}
		
		$IdStatus	=	substr($lin[IdStatus],0,1);
		
		switch($IdStatus){
			case '1':
				$Color	  = "";
				break;
			case '2':
				$Color	  = getParametroSistema(15,3);
				break;
			case '3':
				$Color	  = getParametroSistema(15,2);
				break;
		}
		
		$DescricaoParametroServico	=	"";
		$DescricaoParametroServico = "Nome Serviço = ".$lin[DescricaoServicoAlt]." \n".$DescricaoParametroServico;
		
		$sql_ed = "select 
						PessoaEndereco.DescricaoEndereco
					from 
						Contrato,
						PessoaEndereco
					where 
						Contrato.IdLoja = '$local_IdLoja' and 
						Contrato.IdContrato = '$lin[IdContrato]' and 
						Contrato.IdPessoa = PessoaEndereco.IdPessoa and 
						Contrato.IdPessoaEndereco = PessoaEndereco.IdPessoaEndereco";
		$res_ed = mysql_query($sql_ed, $con);
		$lin_ed = mysql_fetch_array($res_ed);
		
		$DescricaoParametroServico .= "\nEndereço do Contrato/Instalação = ".$lin_ed[DescricaoEndereco]." ";
		
		$sql_ed = "select 
						PessoaEndereco.DescricaoEndereco
					from 
						Contrato,
						PessoaEndereco
					where 
						Contrato.IdLoja = '$local_IdLoja' and 
						Contrato.IdContrato = '$lin[IdContrato]' and 
						Contrato.IdPessoa = PessoaEndereco.IdPessoa and 
						Contrato.IdPessoaEnderecoCobranca = PessoaEndereco.IdPessoaEndereco";
		$res_ed = mysql_query($sql_ed, $con);
		$lin_ed = mysql_fetch_array($res_ed);
		
		$DescricaoParametroServico .= "\nEndereço de Correspondência = ".$lin_ed[DescricaoEndereco]." \n";
		
		$sql4 = "select
						ServicoParametro.IdGrupoUsuario,
						ServicoParametro.DescricaoParametroServico,
						ContratoParametro.Valor
					from 
						Loja,
						Servico,
						ServicoParametro LEFT JOIN ContratoParametro ON 
						(
							ServicoParametro.IdLoja = ContratoParametro.IdLoja and 
							ServicoParametro.IdParametroServico = ContratoParametro.IdParametroServico and
							ServicoParametro.IdServico = ContratoParametro.IdServico and
							ContratoParametro.IdContrato = $lin[IdContrato]
						)
					where
						Servico.IdLoja = $local_IdLoja and
						Servico.IdServico = ServicoParametro.IdServico and
						ServicoParametro.IdLoja = Servico.IdLoja and
						Servico.IdLoja = Loja.IdLoja and
						ServicoParametro.IdServico = $lin[IdServico] and
						ServicoParametro.Visivel = 1 and
						(
							ServicoParametro.IdTipoTexto != 3 or
							ServicoParametro.IdTipoTexto is null
						)/*and
						(
							(
								ServicoParametro.IdTipoTexto = 2 and 
								ServicoParametro.ExibirSenha != 2
							) or
							(ServicoParametro.IdTipoTexto = 1)
						)*/
					order by 
						ServicoParametro.IdParametroServico ASC";
		$res4 = @mysql_query($sql4,$con);		
		while($lin4 = @mysql_fetch_array($res4)){
			if($lin4[IdGrupoUsuario] != ''){
				$sql7	=	"select
								(COUNT(*)>0) Qtd
							from 
								UsuarioGrupoUsuario
							where 
								IdLoja = '$local_IdLoja' and 
								IdGrupoUsuario in ($lin4[IdGrupoUsuario]) and 
								Login = '$local_Login';";
				$res7	=	@mysql_query($sql7,$con);
				$lin7	=	@mysql_fetch_array($res7);
			} else {
				$lin7[Qtd] = 1;
			}
			
			if($lin7[Qtd] == 1) {
				if($DescricaoParametroServico != ""){
					$DescricaoParametroServico .= " \n";
				}
				
				$DescricaoParametroServico	.=	$lin4[DescricaoParametroServico]." = ".$lin4[Valor];
			}
		}
		
		if($lin[TipoPessoa]=='1'){
			$lin[Nome]	=	$lin[trim(getCodigoInterno(3,24))];	
		}	
		
		if($filtro_status != ''){//não todos
			if($filtro_status == 'G_1' || $filtro_status <= 199){//cancelados
				if($filtro_soma == 2){//não
					$lin[ValorSoma]			=	$lin[Valor];
					$lin[ValorDescontoSoma]	=	$lin[ValorDesconto];
					$lin[ValorFinalSoma]	=	$lin[ValorFinal];
				}else{//sim
					$lin[ValorSoma]			=	$lin[Valor];
					$lin[ValorDescontoSoma]	=	$lin[ValorDesconto];
					$lin[ValorFinalSoma]	=	$lin[ValorFinal];	
				}
			}else{
				$lin[ValorSoma]			=	$lin[Valor];
				$lin[ValorDescontoSoma]	=	$lin[ValorDesconto];
				$lin[ValorFinalSoma]	=	$lin[ValorFinal];
			}
		}else{
			if($filtro_soma == 2){//não
				if($lin[IdStatus] <= 199){
					$lin[ValorSoma]			=	0;
					$lin[ValorDescontoSoma]	=	0;
					$lin[ValorFinalSoma]	=	0;
				}else{
					$lin[ValorSoma]			=	$lin[Valor];
					$lin[ValorDescontoSoma]	=	$lin[ValorDesconto];
					$lin[ValorFinalSoma]	=	$lin[ValorFinal];	
				}
			}else{//sim
				$lin[ValorSoma]			=	$lin[Valor];
				$lin[ValorDescontoSoma]	=	$lin[ValorDesconto];
				$lin[ValorFinalSoma]	=	$lin[ValorFinal];
			}
		}
		
		$sql5 = "select
				    max(DataAlteracao) DataAlteracao
				from
				    ContratoStatus
				where
				    IdLoja = $local_IdLoja and
				    IdContrato = $lin[IdContrato]
				";
		$res5	=	mysql_query($sql5,$con);
		$lin5	=	mysql_fetch_array($res5);
		if($lin5[DataAlteracao] != ""){
			$local_StatusTempoAlteracao = ' ('.diferencaData($lin5[DataAlteracao], date("Y-m-d H:i:s")).")";
		}else{
			$local_StatusTempoAlteracao = "";
		}
		
		$lin[MesFechado] = getParametroSistema(70,$lin[MesFechado]);
		
		echo "<reg>";	
		echo 	"<IdContrato>$lin[IdContrato]</IdContrato>";	
		echo 	"<Nome><![CDATA[$lin[Nome]]]></Nome>";
		echo 	"<IdServico>$lin[IdServico]</IdServico>";	
		echo 	"<DescricaoServico><![CDATA[$lin[DescricaoServico]]]></DescricaoServico>";
		echo 	"<AbreviacaoNomeLocalCobranca><![CDATA[$lin[AbreviacaoNomeLocalCobranca]]]></AbreviacaoNomeLocalCobranca>";
		echo 	"<DiaCobranca><![CDATA[$lin[DiaCobranca]]]></DiaCobranca>";
		echo 	"<Status><![CDATA[$lin3[Status]]]></Status>";
		echo 	"<StatusDesc><![CDATA[$lin3[StatusDesc]]]></StatusDesc>";
		echo 	"<IdStatus><![CDATA[$lin[IdStatus]]]></IdStatus>";
		echo 	"<DataPrimeiraCobranca><![CDATA[$lin[DataPrimeiraCobranca]]]></DataPrimeiraCobranca>";
		echo 	"<DataPrimeiraCobrancaTemp><![CDATA[$lin[DataPrimeiraCobrancaTemp]]]></DataPrimeiraCobrancaTemp>";
		echo 	"<DataBaseCalculo><![CDATA[$lin[DataBaseCalculo]]]></DataBaseCalculo>";
		echo 	"<DataBaseCalculoTemp><![CDATA[$lin[DataBaseCalculoTemp]]]></DataBaseCalculoTemp>";
		echo 	"<DataTemporaria><![CDATA[$lin[DataTemporaria]]]></DataTemporaria>";
		echo 	"<Valor>$lin[Valor]</Valor>";
		echo 	"<ValorTemp><![CDATA[$lin[ValorTemp]]]></ValorTemp>";
		echo 	"<ValorFinal>$lin[ValorFinal]</ValorFinal>";
		echo 	"<ValorFinalTemp><![CDATA[$lin[ValorFinalTemp]]]></ValorFinalTemp>";
		echo 	"<ValorDesconto>$lin[ValorDesconto]</ValorDesconto>";
		echo 	"<ValorDescontoTemp><![CDATA[$lin[ValorDescontoTemp]]]></ValorDescontoTemp>";
		echo 	"<ValorSoma><![CDATA[$lin[ValorSoma]]]></ValorSoma>";
		echo 	"<ValorFinalSoma><![CDATA[$lin[ValorFinalSoma]]]></ValorFinalSoma>";
		echo 	"<ValorDescontoSoma><![CDATA[$lin[ValorDescontoSoma]]]></ValorDescontoSoma>";	
		echo 	"<StatusTempAlteracao><![CDATA[$local_StatusTempoAlteracao]]></StatusTempAlteracao>";
		echo 	"<TipoContrato><![CDATA[$lin2[ValorParametroSistema]]]></TipoContrato>";
		echo 	"<DescricaoParametroServico><![CDATA[$DescricaoParametroServico]]></DescricaoParametroServico>";
		echo 	"<DescricaoPeriodicidade><![CDATA[$lin[DescricaoPeriodicidade]]]></DescricaoPeriodicidade>";
		echo 	"<QtdParcela>$lin[QtdParcela]</QtdParcela>";			
		echo 	"<MesFechado><![CDATA[$lin[MesFechado]]]></MesFechado>";
		echo 	"<Color><![CDATA[$Color]]></Color>";	
		echo 	"<Img><![CDATA[$Img]]></Img>";
		echo "</reg>";	
	}
	
	echo "</db>";
?>
