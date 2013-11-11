<?
	$localModulo		=	1;
	$localOperacao		=	125;
	$localSuboperacao	=	"R";
		
	include ('../../files/conecta.php');
	include ('../../files/funcoes.php');
	include ('../../rotinas/verifica.php');
	 	
	
	$local_IdLoja				= $_SESSION['IdLoja'];
	$local_IdPessoaLogin		= $_SESSION['IdPessoa'];
	$filtro						= $_POST['filtro'];
	$filtro_ordem				= $_POST['filtro_ordem'];
	$filtro_ordem_direcao		= $_POST['filtro_ordem_direcao'];
	$filtro_localTipoDado		= $_POST['filtro_tipoDado'];
	$filtro_ordem2				= $_POST['filtro_ordem2'];
	$filtro_ordem_direcao2		= $_POST['filtro_ordem_direcao2'];
	$filtro_localTipoDado2		= $_POST['filtro_tipoDado2'];
	$filtro_pessoa				= url_string_xsl($_POST['filtro_pessoa'],'url',false);
	$filtro_descricao_servico	= url_string_xsl($_POST['filtro_descricao_servico'],'url',false);
	$filtro_tipo_pessoa			= $_POST['filtro_tipo_pessoa'];
	$filtro_grupo_pessoa		= $_POST['filtro_grupo_pessoa'];
	$filtro_id_status			= $_POST['filtro_id_status'];
	$filtro_IdPessoa			= $_GET['IdPessoa'];
	$filtro_IdContrato			= $_GET['IdContrato'];
	$filtro_IdServico			= $_GET['IdServico'];
	$filtro_local_cobranca		= $_POST['filtro_local_cobranca'];
	$filtro_usuario				= $_POST['filtro_usuario'];
	$filtro_estado				= $_POST['filtro_estado'];
	$filtro_cidade				= $_POST['filtro_cidade'];
	$filtro_bairro				= url_string_xsl($_POST['filtro_bairro'],'url',false);
	$filtro_endereco			= url_string_xsl($_POST['filtro_endereco'],'url',false);
	$filtro_limit				= $_POST['filtro_limit'];
	$filtro_id_servico			= $_POST['filtro_id_servico'];
	
	$filtro_url	 = "";
	$filtro_sql  = "";
	$filtro_from = "";
	
	LimitVisualizacao("listar");
	if($filtro_IdServico == ''){
		$filtro_IdServico	= $_POST['IdServico'];
	}
	
	$filtro_cancelado				=	$_SESSION["filtro_contrato_cancelado"];
	$filtro_soma					=	$_SESSION["filtro_contrato_soma"];
	$filtro_QTDCaracterColunaPessoa	= $_SESSION["filtro_QTDCaracterColunaPessoa"];
	
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
	
	if($filtro_IdServico!=''){
		$filtro_url .= "&IdContrato=".$filtro_IdServico;
		$filtro_sql .= " and Contrato.IdServico = '$filtro_IdServico'";
	}
		
	if($filtro_descricao_servico!=""){
		$filtro_url .= "&DescricaoServico=".$filtro_descricao_servico;
		$filtro_sql .= " and Servico.DescricaoServico like '%$filtro_descricao_servico%'";
	}
	
	if($filtro_usuario!=''){
		$filtro_url .= "&Usuario=".$filtro_usuario;
		$filtro_sql .= " and Contrato.LoginCriacao like '%$filtro_usuario%'";
	}
	
	if($filtro_estado!=''){
		$filtro_url .= "&IdEstado=$filtro_estado";
		$filtro_sql .=	" and PessoaEndereco.IdEstado = $filtro_estado";
	}
	
	if($filtro_cidade!=''){
		$filtro_url .= "&IdCidade=$filtro_cidade";
		$filtro_sql .=	" and PessoaEndereco.IdCidade = $filtro_cidade";
	}
	
	if($filtro_bairro!=''){
		$filtro_url .= "&Bairro=$filtro_bairro";
		$filtro_sql .=	" and PessoaEndereco.Bairro like '%$filtro_bairro%'";
	}
	
	if($filtro_endereco!=''){
		$filtro_url .= "&Endereco=$filtro_endereco";
		$filtro_sql .=	" and PessoaEndereco.Endereco like '%$filtro_endereco%'";
	}
	
	if($filtro_tipo_pessoa!=''){
		$filtro_url .= "&TipoPessoa=".$filtro_tipo_pessoa;
		$filtro_sql .= " and Pessoa.TipoPessoa = '$filtro_tipo_pessoa'";
	}
	
	if($filtro_grupo_pessoa!=""){
		$filtro_url .= "&IdGrupoPessoa=".$filtro_grupo_pessoa;
		$filtro_sql .= " and GrupoPessoa.IdGrupoPessoa = '$filtro_grupo_pessoa'";
	}
	
	if($filtro_local_cobranca!=''){
		$filtro_url .= "&IdLocalCobranca=".$filtro_local_cobranca;
		$filtro_sql .= " and (Contrato.IdLocalCobranca = '$filtro_local_cobranca')";
	}
	
	if($filtro_id_status!=''){
		$filtro_url .= "&IdStatus=".$filtro_id_status;
		
		$aux	=	explode("G_",$filtro_id_status);
		
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
			$filtro_sql .= " and Contrato.IdStatus = '$filtro_id_status'";
		}
	}
	
	if($filtro_soma!=""){
		$filtro_url .= "&Soma=".$filtro_soma;
	}
	
	if($filtro_cancelado != ""){
		$filtro_url .= "&Cancelado=".$filtro_cancelado;
		if($filtro_id_status == ""){
			if($filtro_cancelado == 2){
				switch($filtro_id_status){
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
	if($filtro_id_servico!=""){
		$filtro_url .= "&IdServico=".$filtro_id_servico;
		$filtro_sql .= " and Contrato.IdServico = '$filtro_id_servico'";
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
	echo "<?xml-stylesheet type=\"text/xsl\" href=\"listar_contrato_tipo_pessoa_xsl.php$filtro_url\"?>";
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
				    Contrato.IdContrato,
				    Contrato.IdServico,
				    substring(Servico.DescricaoServico,1,15) DescricaoServico,
				    Contrato.IdPessoa,				    
					Pessoa.TipoPessoa,
					GrupoPessoa.IdGrupoPessoa,
				    substring(Pessoa.Nome,1,$filtro_QTDCaracterColunaPessoa) Nome,
				    substring(Pessoa.RazaoSocial,1,$filtro_QTDCaracterColunaPessoa) RazaoSocial,
				    Contrato.DataInicio,
				    Contrato.DataTermino,
				    LocalCobranca.AbreviacaoNomeLocalCobranca,
				    ContratoVigenciaAtiva.Valor,
					ContratoVigenciaAtiva.ValorDesconto,
				    (ContratoVigenciaAtiva.Valor - ContratoVigenciaAtiva.ValorDesconto) ValorFinal,
					ContratoVigenciaAtiva.IdTipoDesconto,
				    Contrato.TipoContrato,
				    Contrato.IdStatus,
				    Contrato.VarStatus,
				    Contrato.DiaCobranca,
				    subString(GrupoPessoa.DescricaoGrupoPessoa,1,12) GrupoPessoa
				from
				    Loja,
				    Contrato 
					left join (
						select 
							ContratoVigenciaAtiva.IdContrato, 
							ContratoVigenciaAtiva.DataInicio, 
							ContratoVigenciaAtiva.DataTermino, 
							ContratoVigenciaAtiva.Valor, 
							ContratoVigenciaAtiva.ValorDesconto, 
							ContratoVigenciaAtiva.IdTipoDesconto 
						from 
							Loja, 
							ContratoVigenciaAtiva, 
							Contrato 
						where 
							Loja.IdLoja = $local_IdLoja and 
							ContratoVigenciaAtiva.IdLoja = Loja.IdLoja and 
							Contrato.IdLoja = Loja.IdLoja and 
							ContratoVigenciaAtiva.IdContrato = Contrato.IdContrato) 
					ContratoVigenciaAtiva on Contrato.IdContrato = ContratoVigenciaAtiva.IdContrato,
				    Servico,
				    Pessoa left join (
						PessoaGrupoPessoa, 
						GrupoPessoa
					) on (
						Pessoa.IdPessoa = PessoaGrupoPessoa.IdPessoa and 
						PessoaGrupoPessoa.IdLoja = '$local_IdLoja' and 
						PessoaGrupoPessoa.IdLoja = GrupoPessoa.IdLoja and 
						PessoaGrupoPessoa.IdGrupoPessoa = GrupoPessoa.IdGrupoPessoa
					),
					PessoaEndereco,
				    LocalCobranca $sqlAux
				where
				    Loja.IdLoja = $local_IdLoja and
				    Contrato.IdLoja = Loja.IdLoja and
				    Loja.IdLoja = Servico.IdLoja and
				    Loja.IdLoja = LocalCobranca.IdLoja and
				    Contrato.IdServico = Servico.IdServico and
				    Contrato.IdPessoa = Pessoa.IdPessoa and
				    Contrato.IdLocalCobranca = LocalCobranca.IdLocalCobranca and
					Pessoa.IdPessoa = PessoaEndereco.IdPessoa and
					Pessoa.IdEnderecoDefault = PessoaEndereco.IdPessoaEndereco
					$filtro_sql
				group by
					Contrato.IdContrato
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
		
		$lin[DataInicioTemp] 	= dataConv($lin[DataInicio],"Y-m-d","d/m/Y");
		$lin[DataTerminoTemp] 	= dataConv($lin[DataTermino],"Y-m-d","d/m/Y");
		
		$lin[DataInicio] 		= dataConv($lin[DataInicio],"Y-m-d","Ymd");
		$lin[DataTermino] 		= dataConv($lin[DataTermino],"Y-m-d","Ymd");
		
		$lin[DataTemporaria]	= dataConv($lin[VarStatus],"d/m/Y","Ymd");
		
		$sql2 = "select substr(ValorParametroSistema,1,3) ValorParametroSistema  from ParametroSistema where IdGrupoParametroSistema=28 and IdParametroSistema=$lin[TipoContrato]";
		$res2 = @mysql_query($sql2,$con);
		$lin2 = @mysql_fetch_array($res2);
		
		$sql3 = "select ValorParametroSistema Status  from ParametroSistema where IdGrupoParametroSistema=69 and IdParametroSistema=$lin[IdStatus]";
		$res3 = @mysql_query($sql3,$con);
		$lin3 = @mysql_fetch_array($res3);
		
		$sql5 = "select ValorParametroSistema TipoPessoa from ParametroSistema where IdGrupoParametroSistema=1 and IdParametroSistema=$lin[TipoPessoa]";
		$res5 = @mysql_query($sql5,$con);
		$lin5 = @mysql_fetch_array($res5);
		
		
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
		$DescricaoParametroServico = "Nome Serviço=".$lin[DescricaoServicoAlt]."\n".$DescricaoParametroServico;
		
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
						ServicoParametro.DescricaoParametroServico,
						ContratoParametro.Valor
					from 
						Loja,
						Servico,
						ServicoParametro LEFT JOIN 
								ContratoParametro ON (
									ServicoParametro.IdLoja = ContratoParametro.IdLoja and 
									ServicoParametro.IdParametroServico = ContratoParametro.IdParametroServico and
									ServicoParametro.IdServico = ContratoParametro.IdServico and
									ContratoParametro.IdContrato = $lin[IdContrato])
					where
						Servico.IdLoja = $local_IdLoja and
						Servico.IdServico = ServicoParametro.IdServico and
						ServicoParametro.IdLoja = Servico.IdLoja and
						Servico.IdLoja = Loja.IdLoja and
						ServicoParametro.IdServico = $lin[IdServico] and
						ServicoParametro.Visivel = 1
					order by 
						ServicoParametro.IdParametroServico ASC";
		$res4 = @mysql_query($sql4,$con);
		while($lin4 = @mysql_fetch_array($res4)){
			if($DescricaoParametroServico != "") $DescricaoParametroServico = "\n";
			$DescricaoParametroServico	.=	$lin4[DescricaoParametroServico]."=".$lin4[Valor];
		}
		
		if($lin[TipoPessoa]=='1'){
			$lin[Nome]	=	$lin[trim(getCodigoInterno(3,24))];	
		}	
		/*
		if($filtro_soma == 2){
			if($lin[IdStatus] == 1){
				$lin[ValorFinalSoma]	=	0;
			}else{
				$lin[ValorFinalSoma]	=	$lin[ValorFinal];
				
			}
		}else{
			$lin[ValorFinalSoma]	=	$lin[ValorFinal];
		}						
		*/
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
		echo 	"<DataInicio><![CDATA[$lin[DataInicio]]]></DataInicio>";
		echo 	"<DataInicioTemp><![CDATA[$lin[DataInicioTemp]]]></DataInicioTemp>";
		echo 	"<DataTermino><![CDATA[$lin[DataTermino]]]></DataTermino>";
		echo 	"<DataTerminoTemp><![CDATA[$lin[DataTerminoTemp]]]></DataTerminoTemp>";
		echo 	"<DataTemporaria><![CDATA[$lin[DataTemporaria]]]></DataTemporaria>";
		echo 	"<ValorFinal>$lin[ValorFinal]</ValorFinal>";
		echo 	"<ValorFinalTemp><![CDATA[$lin[ValorFinalTemp]]]></ValorFinalTemp>";
		echo 	"<ValorFinalSoma><![CDATA[$lin[ValorFinalSoma]]]></ValorFinalSoma>";
		echo 	"<TipoContrato><![CDATA[$lin2[ValorParametroSistema]]]></TipoContrato>";
		echo 	"<TipoPessoa><![CDATA[$lin5[TipoPessoa]]]></TipoPessoa>";
		echo 	"<GrupoPessoa><![CDATA[$lin[GrupoPessoa]]]></GrupoPessoa>";
		echo 	"<Color><![CDATA[$Color]]></Color>";
		echo 	"<Img><![CDATA[$Img]]></Img>";
		echo "</reg>";	
	}
	
	echo "</db>";
?>
