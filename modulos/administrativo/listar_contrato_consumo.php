<?
	$localModulo		=	1;
	$localOperacao		=	2;
	$localSuboperacao	=	"R";

	include ('../../files/conecta.php');
	include ('../../files/funcoes.php');
	include ('../../rotinas/verifica.php');

	$local_IdLoja						= $_SESSION['IdLoja'];
	$local_IdPessoaLogin				= $_SESSION['IdPessoa'];
	$local_Login						= $_SESSION["Login"];
	$filtro								= $_POST['filtro'];
	$filtro_ordem						= $_POST['filtro_ordem'];
	$filtro_ordem_direcao				= $_POST['filtro_ordem_direcao'];
	$filtro_localTipoDado				= $_POST['filtro_tipoDado'];
	$filtro_ordem2						= $_POST['filtro_ordem2'];
	$filtro_ordem_direcao2				= $_POST['filtro_ordem_direcao2'];
	$filtro_localTipoDado2				= $_POST['filtro_tipoDado2'];
	$filtro_pessoa						= url_string_xsl($_POST['filtro_pessoa'],"URL",false);
	$filtro_descricao_servico			= url_string_xsl($_POST['filtro_descricao_servico'],"URL",false);
	$filtro_data_inicio					= $_POST['filtro_data_inicio'];
	$filtro_data_termino				= $_POST['filtro_data_fim'];
	$filtro_status						= $_POST['filtro_status'];
	$filtro_id_servico					= $_POST['filtro_id_servico'];
	$filtro_id_pessoa					= $_POST['filtro_id_pessoa'];
	$filtro_cancelado					= $_POST['filtro_contrato_cancelado'];
	$filtro_soma						= $_POST['filtro_contrato_soma'];
	$filtro_IdPessoa					= $_GET['IdPessoa'];
	$filtro_IdContrato					= $_GET['IdContrato'];
	$filtro_IdServico					= $_GET['IdServico'];
	$filtro_IdStatus					= $_GET['IdStatus'];
	$filtro_tipo_pessoa					= $_POST['filtro_tipo_pessoa'];
	$filtro_grupo_pessoa				= $_POST['filtro_grupo_pessoa'];
	$filtro_parametro					= $_POST['filtro_parametro'];
	$filtro_valor_parametro				= url_string_xsl($_POST['filtro_valor_parametro'],"URL",false);
	$filtro_estado						= $_POST['filtro_estado'];
	$filtro_cidade						= $_POST['filtro_cidade'];
	$filtro_bairro						= url_string_xsl($_POST['filtro_bairro'],'url',false);
	$filtro_endereco					= url_string_xsl($_POST['filtro_endereco'],'url',false);
	$filtro_campo						= $_POST['filtro_campo'];
	$filtro_limit						= $_POST['filtro_limit'];

	if($_GET['filtro_limit'] != ''){
		$filtro_limit = $_GET['filtro_limit'];
	}
	
	if($filtro_IdPessoa == ''){
		$filtro_IdPessoa	= $_POST['IdPessoa'];
	}
	
	if($filtro_IdServico == ''){
		$filtro_IdServico	= $_POST['IdServico'];
	}
	if($filtro_status == ''){
		$filtro_status		= $_GET['filtro_status'];
	}
	
	if($filtro_IdContrato == ''){
		$filtro_IdContrato	= $_POST['IdContrato'];
	}
	
	if($filtro_parametro == ''){
		$filtro_parametro = $_GET['filtro_parametro'];
	}

	if($filtro_valor_parametro == ''){
		$filtro_valor_parametro = $_GET['filtro_valor_parametro'];
	}				
	
	$filtro_cancelado				= $_SESSION["filtro_contrato_cancelado"];	
	$filtro_soma					= $_SESSION["filtro_contrato_soma"];
	$filtro_parametro_aproximidade	= $_SESSION["filtro_parametro_aproximidade"];
	$filtro_QTDCaracterColunaPessoa	= $_SESSION["filtro_QTDCaracterColunaPessoa"];
	
	if($_GET['filtro_parametro_aproximidade'] != ''){
		$filtro_parametro_aproximidade = $_GET['filtro_parametro_aproximidade'];
	}
	
	$filtro_url	 = "";
	$filtro_sql  = "";
	$filtro_from = "";
	$order_by	 = "";
	$filtro_sql_radius_data = "";
	
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

	if($filtro_parametro!=""){
		$filtro_url .= "&DescricaoParametroServico=".$filtro_parametro;
		$filtro_sql .= " and ServicoParametro.IdServico = Servico.IdServico and ServicoParametro.DescricaoParametroServico = '$filtro_parametro'";
		$filtro_sql .= " and Contrato.IdLoja = ServicoParametro.IdLoja";
		$filtro_url .= "&ValorParametroServico=".$filtro_valor_parametro;
		
		if($filtro_valor_parametro != ""){
			if($filtro_parametro_aproximidade == 2){
				$filtro_sql .= " and ContratoParametro.IdLoja = '$local_IdLoja' and ContratoParametro.IdContrato = Contrato.IdContrato and ContratoParametro.IdParametroServico = ServicoParametro.IdParametroServico and ContratoParametro.Valor = '$filtro_valor_parametro'";
			}else{
				$filtro_sql .= " and ContratoParametro.IdLoja = '$local_IdLoja' and ContratoParametro.IdContrato = Contrato.IdContrato and ContratoParametro.IdParametroServico = ServicoParametro.IdParametroServico and ContratoParametro.Valor like '%$filtro_valor_parametro%'";
			}
		} else{
			$filtro_sql .= " and ContratoParametro.IdContrato = Contrato.IdContrato and ContratoParametro.IdParametroServico = ServicoParametro.IdParametroServico and ContratoParametro.Valor = '$filtro_valor_parametro'";		
		}
	}
	if($filtro_IdContrato!=''){
		$filtro_url .= "&IdContrato=".$filtro_IdContrato;
		$filtro_sql .= " and Contrato.IdContrato = '$filtro_IdContrato'";
	}
	
	if($filtro_IdServico!=''){
		$filtro_url .= "&IdServico=".$filtro_IdServico;
		$filtro_sql .= " and Contrato.IdServico = '$filtro_IdServico'";
	}
	
	if($filtro_descricao_servico!=""){
		$filtro_url .= "&DescricaoServico=".$filtro_descricao_servico;
		$filtro_sql .= " and Servico.DescricaoServico like '%$filtro_descricao_servico%'";
	}
	
	if($filtro_parametro!=""){
		$filtro_from .= " , ServicoParametro";
		$filtro_url .= "&DescricaoParametroServico=".$filtro_parametro;
		$filtro_sql .= " and ServicoParametro.IdServico = Servico.IdServico and ServicoParametro.DescricaoParametroServico = '$filtro_parametro'";
		$filtro_sql .= " and Contrato.IdLoja = ServicoParametro.IdLoja";
	
		$filtro_from .= " , ContratoParametro";
		$filtro_url .= "&ValorParametroServico=".$filtro_valor_parametro;
		
		if($filtro_valor_parametro != ""){
			if($filtro_parametro_aproximidade == 2){
				$filtro_sql .= " and ContratoParametro.IdLoja = '$local_IdLoja' and ContratoParametro.IdContrato = Contrato.IdContrato and ContratoParametro.IdParametroServico = ServicoParametro.IdParametroServico and ContratoParametro.Valor = '$filtro_valor_parametro'";
			}else{
				$filtro_sql .= " and ContratoParametro.IdLoja = '$local_IdLoja' and ContratoParametro.IdContrato = Contrato.IdContrato and ContratoParametro.IdParametroServico = ServicoParametro.IdParametroServico and ContratoParametro.Valor like '%$filtro_valor_parametro%'";
			}
		} else{
			$filtro_sql .= " and ContratoParametro.IdContrato = Contrato.IdContrato and ContratoParametro.IdParametroServico = ServicoParametro.IdParametroServico and ContratoParametro.Valor = '$filtro_valor_parametro'";		
		}
	}	
	if($filtro_tipo_pessoa!=""){
		$filtro_url .= "&TipoPessoa=".$filtro_tipo_pessoa;
		$filtro_sql .= " and Pessoa.TipoPessoa = '$filtro_tipo_pessoa'";
	}
	
	if($filtro_grupo_pessoa!=""){
		$filtro_url .= "&IdGrupoPessoa=".$filtro_grupo_pessoa;
		$filtro_sql .= " and GrupoPessoa.IdGrupoPessoa = '$filtro_grupo_pessoa'";
	}	
	
	switch($filtro_campo){
		case 0:
			if($filtro_data_inicio!=""){
				$filtro_url .= "&DataInicio=".$filtro_data_inicio;
				$filtro_data_inicio = dataConv($filtro_data_inicio,'d/m/Y','Y-m-d');
				$filtro_sql_radius_data = "AND SUBSTRING(acctstarttime, 1, 10) >= '$filtro_data_inicio'";
			}			
			if($filtro_data_termino!=""){
				$filtro_url .= "&DataTermino=".$filtro_data_termino;
				$filtro_data_termino = dataConv($filtro_data_termino,'d/m/Y','Y-m-d');
				$filtro_sql_radius_data .= " AND SUBSTRING(acctstarttime, 1, 10) <= '$filtro_data_termino'";		
			}
			break;
		case 1:
			$filtro_url .= "&Campo=1";
			if($filtro_data_inicio!=""){
				$filtro_url .= "&DataInicio=".$filtro_data_inicio;
				$filtro_data_inicio = dataConv($filtro_data_inicio,'d/m/Y','Y-m-d');
				$filtro_sql_radius_data_dow = "AND SUBSTRING(acctstarttime, 1, 10) >= '$filtro_data_inicio'";
			}
			if($filtro_data_termino!=""){
				$filtro_url .= "&DataTermino=".$filtro_data_termino;
				$filtro_data_termino = dataConv($filtro_data_termino,'d/m/Y','Y-m-d');
				$filtro_sql_radius_data_dow .= " AND SUBSTRING(acctstarttime, 1, 10) <= '$filtro_data_termino'";		
			}			
			break;
		case 2:
			$filtro_url .= "&Campo=2";
			if($filtro_data_inicio!=""){
				$filtro_url .= "&DataInicio=".$filtro_data_inicio;
				$filtro_data_inicio = dataConv($filtro_data_inicio,'d/m/Y','Y-m-d');
				$filtro_sql_radius_data_up .= "AND SUBSTRING(acctstarttime, 1, 10) >= '$filtro_data_inicio'";
			}
			if($filtro_data_termino!=""){
				$filtro_url .= "&DataTermino=".$filtro_data_termino;
				$filtro_data_termino = dataConv($filtro_data_termino,'d/m/Y','Y-m-d');
				$filtro_sql_radius_data_up .= " AND SUBSTRING(acctstarttime, 1, 10) <= '$filtro_data_termino'";		
			}	
			break;		
	}
	if($filtro_tipo!=''){
		$filtro_url .= "&TipoContrato=".$filtro_tipo;
		$filtro_sql .= " and Contrato.TipoContrato = '$filtro_tipo'";
	}
	
	if($filtro_valor!=''){
		$filtro_url .= "&Valor=".$filtro_valor;
		$filtro_sql .= " and ContratoVigencia.Valor like '%$filtro_valor%'";
	}
	
	if($filtro_estado!=''){
		$filtro_url .= "&IdEstado=$filtro_estado";
		$filtro_sql .=	" and PessoaEndereco.IdEstado = $filtro_estado";
	}
	
	if($filtro_cidade!=''){
		$filtro_url .= "&IdCidade=$filtro_cidade";
		$filtro_sql .=	" and PessoaEndereco.IdCidade = $filtro_cidade";
		
		$sql11	="	select 
						NomeCidade
					from
						Cidade 
					where
						Cidade.IdEstado = $filtro_estado and
						Cidade.IdCidade = $filtro_cidade";
		$res11	=	mysql_query($sql11,$con);
		$lin11	=	mysql_fetch_array($res11);
		
		$filtro_url .= "&NomeCidade=$lin11[NomeCidade]";
	}
	
	if($filtro_bairro!=''){
		$filtro_url .= "&Bairro=$filtro_bairro";
		$filtro_sql .=	" and PessoaEndereco.Bairro like '%$filtro_bairro%'";
	}
	
	if($filtro_endereco!=''){
		$filtro_url .= "&Endereco=$filtro_endereco";
		$filtro_sql .=	" and PessoaEndereco.Endereco like '%$filtro_endereco%'";
	}
	
	if($filtro_IdStatus != ""){
		$filtro_sql .= " and Contrato.IdStatus in ($filtro_IdStatus)";
	} else if($filtro_status != ""){
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
	
	if($filtro_id_servico!=""){
		$filtro_url .= "&IdServico=".$filtro_id_servico;
		$filtro_sql .= " and Contrato.IdServico = '$filtro_id_servico'";
	}
	
	if($filtro_id_pessoa!=""){
		$filtro_url .= "&IdPessoa=".$filtro_id_pessoa;
		$filtro_sql .= " and Contrato.IdPessoa = '$filtro_id_pessoa'";
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
		$filtro_url	= url_string_xsl($filtro_url,'convert', false);
	}
	
	header ("content-type: text/xml");
	echo "<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
	echo "<?xml-stylesheet type=\"text/xsl\" href=\"listar_contrato_consumo_xsl.php$filtro_url\"?>";
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
	
	$sql = "select
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
				Contrato.DataTermino,
				LocalCobranca.AbreviacaoNomeLocalCobranca,
				ContratoVigenciaAtiva.Valor,
				ContratoVigenciaAtiva.ValorDesconto,
				(ContratoVigenciaAtiva.Valor - ContratoVigenciaAtiva.ValorDesconto) ValorFinal,
				ContratoVigenciaAtiva.IdTipoDesconto,
				Contrato.TipoContrato,
				Contrato.IdStatus,
				Contrato.VarStatus,
				Contrato.MesFechado,
				Contrato.DiaCobranca
			from
				Loja,
				Contrato left join ContratoVigenciaAtiva on 
				(
					Contrato.IdLoja = ContratoVigenciaAtiva.IdLoja and 
					Contrato.IdContrato = ContratoVigenciaAtiva.IdContrato
				), 
				Servico,
				Pessoa 
					LEFT JOIN (PessoaGrupoPessoa, GrupoPessoa) 
					ON (
					  Pessoa.IdPessoa = PessoaGrupoPessoa.IdPessoa 
					  AND PessoaGrupoPessoa.IdLoja = 1 
					  AND PessoaGrupoPessoa.IdLoja = GrupoPessoa.IdLoja 
					  AND PessoaGrupoPessoa.IdGrupoPessoa = GrupoPessoa.IdGrupoPessoa
					),
				PessoaEndereco,
				LocalCobranca $filtro_from $sqlAux
			where
				Loja.IdLoja = $local_IdLoja and
				Contrato.IdLoja = $local_IdLoja and
				Loja.IdLoja = Servico.IdLoja and
				Loja.IdLoja = LocalCobranca.IdLoja and
				Contrato.IdServico = Servico.IdServico and
				Contrato.IdPessoa = Pessoa.IdPessoa and
				Pessoa.IdPessoa = PessoaEndereco.IdPessoa and
				PessoaEndereco.IdPessoaEndereco = Contrato.IdPessoaEndereco and
				Contrato.IdLocalCobranca = LocalCobranca.IdLocalCobranca 
				$filtro_sql
			order by
				Contrato.IdContrato desc
			$Limit";
	$res = mysql_query($sql,$con) or die("<br><b>".mysql_error());
	while($lin = mysql_fetch_array($res)){
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
		
		$sql12 = "select DescricaoParametroServico Status  from ServicoParametro where IdLoja = $local_IdLoja";
		$res12 = @mysql_query($sql3,$con);
		$lin12 = @mysql_fetch_array($res3);
		
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
				    IdContrato = $lin[IdContrato]";
					
		$res5	=	mysql_query($sql5,$con);
		$lin5	=	mysql_fetch_array($res5);
		if($lin5[DataAlteracao] != ""){
			$local_StatusTempoAlteracao = ' ('.diferencaData($lin5[DataAlteracao], date("Y-m-d H:i:s")).")";
		}else{
			$local_StatusTempoAlteracao = "";
		}
		
		$sqlMesFechado = "select 
								IdParametroSistema, 
								ValorParametroSistema 
							from 
								ParametroSistema 
							where 
								IdGrupoParametroSistema = 70 
								and IdParametroSistema = $lin[MesFechado]";
		$resMesFechado = @mysql_query($sqlMesFechado,$con);
		$linMesFechado = @mysql_fetch_array($resMesFechado);
		
		$sqlUser = "SELECT
						Valor UserName
					FROM
						ContratoParametro
					WHERE
						IdLoja = $local_IdLoja AND
						IdContrato = $lin[IdContrato] AND
						IdParametroServico = 1";
		$resUser = mysql_query($sqlUser,$con);
		
		$sqlConRadius = "SELECT 
						ValorCodigoInterno 
					FROM 
						CodigoInterno 
					WHERE 
						IdLoja = $local_IdLoja AND 
						IdGrupoCodigoInterno = 10000 AND 
						IdCodigoInterno = 1";
		$resConRadius = mysql_query($sqlConRadius,$con);
		$linConRadius = mysql_fetch_array($resConRadius);		
	
		$aux = explode("\n",$linConRadius[ValorCodigoInterno]);
		
		#Conexão com Radius.
		$bd[server]	= trim($aux[0]); //Host
		$bd[login]	= trim($aux[1]); //Login
		$bd[senha]	= trim($aux[2]); //Senha	
		
		$conRadius	= mysql_connect($bd[server],$bd[login],$bd[senha]) or die("Não foi possível conectar ao Banco de Dados '<b>radius</b>'");	
		$linUser 	= mysql_fetch_array($resUser);
		$UserName 	= $linUser[UserName];		

		$sqlDow = "SELECT
						MesAtual.MesAtualDownload,					
						MesAtual.MesFechado					
					FROM
						(
							SELECT
								SUM(acctinputoctets) MesAtualUpload,
								SUM(acctoutputoctets) MesAtualDownload,
								SUBSTRING(acctstarttime,1,10) MesFechado 
							FROM
								(
									(
										SELECT
											acctinputoctets,
											acctoutputoctets,
											acctstarttime
										FROM
											radius.radacct
										WHERE
											username = '$UserName'
											$filtro_sql_radius_data
											$filtro_sql_radius_data_dow										
									)UNION(
										SELECT
											acctinputoctets,
											acctoutputoctets,
											acctstarttime
										FROM
											radius.radacctJornal
										WHERE
											username = '$UserName' 
											$filtro_sql_radius_data
											$filtro_sql_radius_data_dow
									)
								)Temp
						)MesAtual";
		$resDow = mysql_query($sqlDow,$conRadius) or die(mysql_error());
		
		$sqlUp = "SELECT
						MesAtual.MesAtualUpload						
					FROM
						(
							SELECT
								SUM(acctinputoctets) MesAtualUpload,
								SUM(acctoutputoctets) MesAtualDownload
							FROM
								(
									(
										SELECT
											acctinputoctets,
											acctoutputoctets
										FROM
											radius.radacct
										WHERE
											username = '$UserName'
											$filtro_sql_radius_data
											$filtro_sql_radius_data_up										
									)UNION(
										SELECT
											acctinputoctets,
											acctoutputoctets
										FROM
											radius.radacctJornal
										WHERE
											username = '$UserName' 
											$filtro_sql_radius_data
											$filtro_sql_radius_data_up
									)
								)Temp
						)MesAtual";

		$resUp = mysql_query($sqlUp,$conRadius) or die(mysql_error());

		$sqlQtdAcesso = "SELECT
						MesAtual.MesAtualUpload						
					FROM
						(
							SELECT
								acctinputoctets MesAtualUpload,
								acctoutputoctets MesAtualDownload
							FROM
								(
									(
										SELECT
											acctinputoctets,
											acctoutputoctets
										FROM
											radius.radacct
										WHERE
											username = '$UserName'									
									)UNION(
										SELECT
											acctinputoctets,
											acctoutputoctets
										FROM
											radius.radacctJornal
										WHERE
											username = '$UserName' 
									)
								)Temp
						)MesAtual";

		$resQtdAcesso = mysql_query($sqlQtdAcesso,$conRadius) or die(mysql_error());
		$QtdAcesso = mysql_num_rows($resQtdAcesso);
		
		
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
		echo 	"<Valor>$lin[Valor]</Valor>";	
		echo 	"<MesFechado>$linMesFechado[ValorParametroSistema]</MesFechado>";
		echo 	"<QtdAcesso>$QtdAcesso</QtdAcesso>";
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
		echo 	"<IdServico><![CDATA[$lin2[IdServico]]]></IdServico>";
		echo 	"<DescricaoParametroServico><![CDATA[$DescricaoParametroServico]]></DescricaoParametroServico>";
		echo 	"<Color><![CDATA[$Color]]></Color>";
		echo 	"<Img><![CDATA[$Img]]></Img>";
		if($linUp = @mysql_fetch_array($resUp)){
			$linUp[MesAtualUpload] = byte_convert($linUp[MesAtualUpload],2);
			echo "<MesAtualUpload><![CDATA[$linUp[MesAtualUpload]]]></MesAtualUpload>";
		}
		if($linDow = @mysql_fetch_array($resDow)){
			$linDow[MesAtualDownload] = byte_convert($linDow[MesAtualDownload],2);		
			echo "<MesAtualDownload><![CDATA[$linDow[MesAtualDownload]]]></MesAtualDownload>";		
		}
		echo "</reg>";	
	}	
	echo "</db>";
?>
