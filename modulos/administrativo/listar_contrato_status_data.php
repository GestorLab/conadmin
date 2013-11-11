<?
	$localModulo		=	1;
	$localOperacao		=	100;
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
	$filtro_descricao_servico	= url_string_xsl($_POST['filtro_descricao_servico'],'url',false);
	$filtro_data_inicio			= $_POST['filtro_data_inicio'];
	$filtro_data_termino		= $_POST['filtro_data_termino'];
	$filtro_status_antigo		= $_POST['filtro_status_antigo'];
	$filtro_status_novo			= $_POST['filtro_status_novo'];
	$filtro_status_atual		= $_POST['filtro_status_atual'];
	$filtro_id_pessoa			= $_POST['filtro_id_pessoa'];
	$filtro_id_servico			= $_POST['filtro_id_servico'];
	$filtro_id_contrato			= $_POST['filtro_id_contrato'];
	$filtro_IdPessoa			= $_GET['IdPessoa'];
	$filtro_IdContrato			= $_GET['IdContrato'];
	$filtro_IdServico			= $_GET['IdServico'];
	$filtro_limit				= $_POST['filtro_limit'];
	
	$filtro_QTDCaracterColunaPessoa	= $_SESSION["filtro_QTDCaracterColunaPessoa"];
	
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
	
	if($filtro_IdPessoa ==""){
		$filtro_IdPessoa = $_POST['IdPessoa'];
	}
	
	if($filtro_IdServico ==""){
		$filtro_IdServico = $_POST['IdServico'];
	}
	
	if($filtro_IdPessoa!=''){
		$filtro_url .= "&IdPessoa=".$filtro_IdPessoa;
		$filtro_sql .= " and Contrato.IdPessoa = '$filtro_IdPessoa'";
	}
	
	if($filtro_IdServico!=''){
		$filtro_url .= "&IdServico=".$filtro_IdServico;
		$filtro_sql .= " and Contrato.IdServico = '$filtro_IdServico'";
	}
	
	if($filtro_id_pessoa!=""){
		$filtro_url .= "&IdPessoa=".$filtro_id_pessoa;
		$filtro_sql .= " and Contrato.IdPessoa = '$filtro_id_pessoa'";
	}
	
	if($filtro_id_servico!=""){
		$filtro_url .= "&IdServico=".$filtro_id_servico;
		$filtro_sql .= " and Contrato.IdServico = '$filtro_id_servico'";
	}

	if($filtro_id_contrato!=""){
		$filtro_url .= "&IdContrato=".$filtro_id_contrato;
		$filtro_sql .= " and Contrato.IdContrato = '$filtro_id_contrato'";
	}
		
	if($filtro_descricao_servico!=""){
		$filtro_url .= "&DescricaoServico=".$filtro_descricao_servico;
		$filtro_sql .= " and Servico.DescricaoServico like '%$filtro_descricao_servico%'";
	}
	
	if($filtro_data_inicio!=""){
		$filtro_url .= "&DataInicio=".$filtro_data_inicio;
		$filtro_data_inicio = dataConv($filtro_data_inicio,'d/m/Y','Y-m-d');
		$filtro_sql .= " and substring(ContratoStatus.DataAlteracao, 1, 10) >= '$filtro_data_inicio'";
	}
	
	if($filtro_data_termino!=""){
		$filtro_url .= "&DataTermino=".$filtro_data_termino;
		$filtro_data_termino = dataConv($filtro_data_termino,'d/m/Y','Y-m-d');
		$filtro_sql .= " and substring(ContratoStatus.DataAlteracao, 1, 10) <= '$filtro_data_termino'";
	}
	
	if($filtro_status_antigo!=""){
		$filtro_url .= "&IdStatusAntigo=".$filtro_status_antigo;
		$filtro_sql .= " and ContratoStatus.IdStatusAntigo = '$filtro_status_antigo'";
	}
	
	if($filtro_status_novo!=""){
		$filtro_url .= "&IdStatusNovo=".$filtro_status_novo;
		$filtro_sql .= " and ContratoStatus.IdStatus = '$filtro_status_novo'";
	}
	
	if($filtro_status_atual!=''){
		$filtro_url .= "&IdStatusAtual=".$filtro_status_atual;
		
		$aux	=	explode("G_",$filtro_status_atual);
		
		if($aux[1]!=""){
			switch($aux[1]){
				case '1':
					$filtro_sql .= " and (Contrato.IdStatus >= 1 and Contrato.IdStatus < 100)";
					break;
				case '2':
					$filtro_sql .= " and (Contrato.IdStatus >= 200 and Contrato.IdStatus < 300)";
					break;
				case '3':
					$filtro_sql .= " and (Contrato.IdStatus >= 300 and Contrato.IdStatus < 400)";
					break;
			}
		}else{
			$filtro_sql .= " and Contrato.IdStatus = '$filtro_status_atual'";
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
	echo "<?xml-stylesheet type=\"text/xsl\" href=\"listar_contrato_status_data_xsl.php$filtro_url\"?>";
	echo "<db>";
	
	if($filtro == "s"){
		if($filtro_limit != ""){
			$Limit	= " limit $filtro_limit";
		}
	}else{
		$Limit 	= " limit 0,".getCodigoInterno(7,5);
	}
	
	$sql	=	"select
				    Contrato.IdContrato,
				    Contrato.IdServico,
				    substring(Servico.DescricaoServico,1,20) DescricaoServico,
				    Contrato.IdPessoa,				    
					Pessoa.TipoPessoa,
				    substring(Pessoa.Nome,1,$filtro_QTDCaracterColunaPessoa) Nome,
				    substring(Pessoa.RazaoSocial,1,$filtro_QTDCaracterColunaPessoa) RazaoSocial,
				    ContratoStatus.IdMudancaStatus,
				    ContratoStatus.IdStatusAntigo,
				    ContratoStatus.IdStatus,
					ContratoStatus.LoginAlteracao,
				    ContratoStatus.DataAlteracao
				from
				    Contrato, 
					ContratoStatus,
				    Servico,
				    Pessoa $filtro_from $sqlAux
				where
				    Contrato.IdLoja = $local_IdLoja and
				    Contrato.IdLoja = Servico.IdLoja and
				    Contrato.IdLoja = ContratoStatus.IdLoja and
				    Contrato.IdServico = Servico.IdServico and
				    Contrato.IdPessoa = Pessoa.IdPessoa and
					ContratoStatus.IdContrato = Contrato.IdContrato $filtro_sql	
				order by
					ContratoStatus.IdContrato desc, ContratoStatus.IdMudancaStatus $Limit";
	$res	=	mysql_query($sql,$con);
	while($lin	=	mysql_fetch_array($res)){
		$sql3 = "select ValorParametroSistema StatusAntigo  from ParametroSistema where IdGrupoParametroSistema=69 and IdParametroSistema=$lin[IdStatusAntigo]";
		$res3 = @mysql_query($sql3,$con);
		$lin3 = @mysql_fetch_array($res3);
		
		$sql2 = "select ValorParametroSistema StatusNovo  from ParametroSistema where IdGrupoParametroSistema=69 and IdParametroSistema=$lin[IdStatus]";
		$res2 = @mysql_query($sql2,$con);
		$lin2 = @mysql_fetch_array($res2);
		
		if($lin[TipoPessoa]=='1'){
			$lin[Nome]	=	$lin[trim(getCodigoInterno(3,24))];	
		}	
		
		$lin[DataAlteracaoTemp]	=	dataConv($lin[DataAlteracao],'Y-m-d H:i:s','d/m/Y H:i:s');	
		$lin[DataAlteracao]		=	dataConv($lin[DataAlteracao],'Y-m-d H:i:s','YmdHis');

		$DescricaoParametroServico	=	"";
		$DescricaoParametroServico = "Nome Serviço = ".$lin[DescricaoServico]." \n".$DescricaoParametroServico;
		
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
		
		echo "<reg>";	
		echo 	"<IdContrato>$lin[IdContrato]</IdContrato>";	
		echo 	"<IdMudancaStatus>$lin[IdMudancaStatus]</IdMudancaStatus>";	
		echo 	"<Nome><![CDATA[$lin[Nome]]]></Nome>";	
		echo 	"<DescricaoServico><![CDATA[$lin[DescricaoServico]]]></DescricaoServico>";
		echo 	"<StatusAntigo><![CDATA[$lin3[StatusAntigo]]]></StatusAntigo>";
		echo 	"<StatusNovo><![CDATA[$lin2[StatusNovo]]]></StatusNovo>";
		echo 	"<LoginAlteracao><![CDATA[$lin[LoginAlteracao]]]></LoginAlteracao>";
		echo 	"<DataAlteracaoTemp><![CDATA[$lin[DataAlteracaoTemp]]]></DataAlteracaoTemp>";
		echo 	"<DataAlteracao><![CDATA[$lin[DataAlteracao]]]></DataAlteracao>";
		echo 	"<DescricaoParametroServico><![CDATA[$DescricaoParametroServico]]></DescricaoParametroServico>";
		echo "</reg>";	
	}
	
	echo "</db>";
?>
