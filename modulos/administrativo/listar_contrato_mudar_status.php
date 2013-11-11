<?
	$localModulo		=	1;
	$localOperacao		=	58;
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
	$filtro_pessoa				= url_string_xsl($_POST['filtro_pessoa'],'url',false);
	$filtro_parametro			= $_POST['filtro_parametro'];
	$filtro_descricao_servico	= url_string_xsl($_POST['filtro_descricao_servico'],'url',false);
	$filtro_data_inicio			= $_POST['filtro_data_inicio'];
	$filtro_data_termino		= $_POST['filtro_data_termino'];
	$filtro_valor_parametro		= url_string_xsl($_POST['filtro_valor_parametro'],'url',false);
	$filtro_status				= $_POST['filtro_status'];
	$filtro_IdPessoa			= $_GET['IdPessoa'];
	$filtro_IdContrato			= $_GET['IdContrato'];
	$filtro_IdServico			= $_GET['IdServico'];
	$filtro_contratos			= $_GET['Contratos'];
	$filtro_limit				= $_POST['filtro_limit'];
	
	$filtro_url	 = "";
	$filtro_sql  = "";
	$filtro_from = "";
	
	LimitVisualizacao("listar");
	
	if($filtro != "")
		$filtro_url		.= "&Filtro=$filtro";
		
	if($filtro_ordem != "")
		$filtro_url		.= "&Ordem=$filtro_ordem";
		
	if($filtro_ordem_direcao != "")
		$filtro_url .= "&OrdemDirecao=$filtro_ordem_direcao";
		
	if($filtro_localTipoDado != "")
		$filtro_url .= "&TipoDado=$filtro_localTipoDado";
	
	if($filtro_pessoa!=''){
		$filtro_url .= "&Pessoa=".$filtro_pessoa;
		$filtro_pessoa = str_replace("'", "\'", $filtro_pessoa);
		$filtro_sql .= " and Pessoa.IdPessoa in (select IdPessoa from Pessoa where Nome like '%$filtro_pessoa%' or RazaoSocial like '%$filtro_pessoa%')";
	}
	
	if($filtro_IdPessoa!=''){
		$filtro_url .= "&IdPessoa=".$filtro_IdPessoa;
		$filtro_sql .= " and Contrato.IdPessoa = '$filtro_IdPessoa'";
	}
	
	if($filtro_contratos!=''){
		$filtro_url .= "&Contratos=".$filtro_contratos;
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
	
	if($filtro_parametro!=""){
		$filtro_from .= " , ServicoParametro";
		$filtro_url .= "&DescricaoParametroServico=".$filtro_parametro;
		$filtro_sql .= " and ServicoParametro.IdServico = Servico.IdServico and ServicoParametro.DescricaoParametroServico = '$filtro_parametro'";
		$filtro_sql .= " and Contrato.IdLoja = ServicoParametro.IdLoja";
	}
	
	if($filtro_valor_parametro!=""){
		$filtro_from .= " , ContratoParametro";
		$filtro_url .= "&ValorParametroServico=".$filtro_valor_parametro;
		$filtro_sql .= " and ContratoParametro.IdContrato = Contrato.IdContrato and ContratoParametro.IdParametroServico = ServicoParametro.IdParametroServico and ContratoParametro.Valor like '%$filtro_valor_parametro%'";
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
	echo "<?xml-stylesheet type=\"text/xsl\" href=\"listar_contrato_mudar_status_xsl.php$filtro_url\"?>";
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
				Contrato.IdContrato,
				Contrato.IdServico,
				substring(Servico.DescricaoServico,1,25) DescricaoServico,
				Contrato.IdPessoa,				    
				Pessoa.TipoPessoa,
				substring(Pessoa.Nome,1,28) Nome,
				substring(Pessoa.RazaoSocial,1,28) RazaoSocial,
				Contrato.DataInicio,
				Contrato.DataTermino,
				LocalCobranca.AbreviacaoNomeLocalCobranca,
				ContratoVigenciaAtiva.Valor,
				ContratoVigenciaAtiva.ValorDesconto,
				(ContratoVigenciaAtiva.Valor - ContratoVigenciaAtiva.ValorDesconto) ValorFinal,
				ContratoVigenciaAtiva.IdTipoDesconto,
				Contrato.TipoContrato,
				Contrato.IdStatus,
				Contrato.VarStatus
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
				Pessoa,
				LocalCobranca 
				$filtro_from 
				$sqlAux
			where
				Loja.IdLoja = $local_IdLoja and
				Contrato.IdLoja = Loja.IdLoja and
				Loja.IdLoja = Servico.IdLoja and
				Loja.IdLoja = LocalCobranca.IdLoja and
				Contrato.IdServico = Servico.IdServico and
				Contrato.IdPessoa = Pessoa.IdPessoa and
				Contrato.IdLocalCobranca = LocalCobranca.IdLocalCobranca
				$filtro_sql
			group by
				Contrato.IdContrato desc
			$Limit";
	$res = mysql_query($sql,$con);
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
		
		$sql2 = "select substr(ValorParametroSistema,1,3) ValorParametroSistema  from ParametroSistema where IdGrupoParametroSistema=28 and IdParametroSistema=$lin[TipoContrato]";
		$res2 = @mysql_query($sql2,$con);
		$lin2 = @mysql_fetch_array($res2);
		
		$sql3 = "select ValorParametroSistema Status  from ParametroSistema where IdGrupoParametroSistema=69 and IdParametroSistema=$lin[IdStatus]";
		$res3 = @mysql_query($sql3,$con);
		$lin3 = @mysql_fetch_array($res3);
		
		
		if($lin[VarStatus] != ''){
			switch($lin[IdStatus]){
				case '201':
					$lin3[Status]	=	str_replace("Temporariamente","até $lin[VarStatus]",$lin3[Status]);	
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
		
		$sql4 = "select DescricaoParametroServico from Contrato,Servico,ServicoParametro where Contrato.IdLoja=$local_IdLoja and Contrato.IdLoja = Servico.IdLoja and Contrato.IdLoja = ServicoParametro.IdLoja and Contrato.IdServico = Servico.IdServico and Servico.IdServico = ServicoParametro.IdServico and Contrato.IdContrato=$lin[IdContrato] and ServicoParametro.ParametroDemonstrativo = 1";
		$res4 = @mysql_query($sql4,$con);
		$lin4 = @mysql_fetch_array($res4);
		
		if($lin[TipoPessoa]=='1'){
			$lin[Nome]	=	$lin[trim(getCodigoInterno(3,24))];	
		}							
		
		echo "<reg>";	
		echo 	"<IdContrato>$lin[IdContrato]</IdContrato>";	
		echo 	"<Nome><![CDATA[$lin[Nome]]]></Nome>";
		echo 	"<IdServico>$lin[IdServico]</IdServico>";	
		echo 	"<DescricaoServico><![CDATA[$lin[DescricaoServico]]]></DescricaoServico>";
		echo 	"<AbreviacaoNomeLocalCobranca><![CDATA[$lin[AbreviacaoNomeLocalCobranca]]]></AbreviacaoNomeLocalCobranca>";
		echo 	"<Status><![CDATA[$lin3[Status]]]></Status>";
		
		echo 	"<DataInicio><![CDATA[$lin[DataInicio]]]></DataInicio>";
		echo 	"<DataInicioTemp><![CDATA[$lin[DataInicioTemp]]]></DataInicioTemp>";
		
		echo 	"<DataTermino><![CDATA[$lin[DataTermino]]]></DataTermino>";
		echo 	"<DataTerminoTemp><![CDATA[$lin[DataTerminoTemp]]]></DataTerminoTemp>";
		
		echo 	"<Valor>$lin[Valor]</Valor>";
		echo 	"<ValorTemp><![CDATA[$lin[ValorTemp]]]></ValorTemp>";
		echo 	"<ValorFinal>$lin[ValorFinal]</ValorFinal>";
		echo 	"<ValorFinalTemp><![CDATA[$lin[ValorFinalTemp]]]></ValorFinalTemp>";
		echo 	"<ValorDesconto>$lin[ValorDesconto]</ValorDesconto>";
		echo 	"<ValorDescontoTemp><![CDATA[$lin[ValorDescontoTemp]]]></ValorDescontoTemp>";
		
		echo 	"<TipoContrato><![CDATA[$lin2[ValorParametroSistema]]]></TipoContrato>";
		echo 	"<DescricaoParametroServico><![CDATA[$lin4[DescricaoParametroServico]]]></DescricaoParametroServico>";
		echo 	"<Color><![CDATA[$Color]]></Color>";
		echo "</reg>";	
	}
	
	echo "</db>";
?>