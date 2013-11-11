<?
	$localModulo		=	1;
	$localOperacao		=	31;
	$localSuboperacao	=	"R";
		
	include ('../../files/conecta.php');
	include ('../../files/funcoes.php');
	include ('../../rotinas/verifica.php');
	 
	$local_IdLoja					= $_SESSION["IdLoja"];
	$local_IdPessoaLogin			= $_SESSION["IdPessoa"];
	$filtro							= $_POST['filtro'];
	$filtro_ordem					= $_POST['filtro_ordem'];
	$filtro_ordem_direcao			= $_POST['filtro_ordem_direcao'];
	$filtro_localTipoDado			= $_POST['filtro_tipoDado'];
	$filtro_nome					= url_string_xsl($_POST['filtro_nome'],'url',false);
	$filtro_local_cobranca			= $_POST['filtro_local_cobranca'];
	$filtro_data					= url_string_xsl($_POST['filtro_data'],'url',false);
	$filtro_idstatus				= $_POST['filtro_idstatus'];
	$filtro_descricao				= url_string_xsl($_POST['filtro_descricao'],'url',false);
	$filtro_conta_receber			= $_POST['filtro_conta_receber'];
	$filtro_limit					= $_POST['filtro_limit'];
	$filtro_pessoa					= $_GET['IdPessoa'];
	$filtro_IdCarne					= $_GET['IdCarne'];
	$filtro_conta_eventual			= $_GET['IdContaEventual'];
	$filtro_IdContaReceber			= $_GET['IdContaReceber'];
	$filtro_campo					= $_POST['filtro_campo'];
	$filtro_campo_valor				= $_POST['filtro_campo_valor'];
	
	
	$filtro_url	= "";
	$filtro_sql = "";
	
	LimitVisualizacao("listar");
	
	if($filtro != "")
		$filtro_url		.= "&Filtro=$filtro";
		
	if($filtro_ordem != "")
		$filtro_url		.= "&Ordem=$filtro_ordem";
		
	if($filtro_ordem_direcao != "")
		$filtro_url .= "&OrdemDirecao=$filtro_ordem_direcao";
		
	if($filtro_localTipoDado != "")
		$filtro_url .= "&TipoDado=$filtro_localTipoDado";
		
	if($filtro_nome!=''){
		$filtro_url .= "&Nome=$filtro_nome";
		$filtro_nome = str_replace("'", "\'", $filtro_nome);
		$filtro_sql .=	" and Pessoa.IdPessoa in (select IdPessoa from Pessoa where Nome like '%$filtro_nome%' or RazaoSocial like '%$filtro_nome%')";
	}
	
	if($filtro_pessoa!=''){
		$filtro_url .= "&IdPessoa=$filtro_pessoa";
		$filtro_sql .=	" and (ContaEventual.IdPessoa = '$filtro_pessoa')";
	}
	
	if($filtro_conta_eventual!=''){
		$filtro_url .= "&IdContaEventual=$filtro_conta_eventual";
		$filtro_sql .=	" and (ContaEventual.IdContaEventual = '$filtro_conta_eventual')";
	}
	
	if($filtro_IdCarne!=''){
		$filtro_sql .= " and ContaEventual.IdContaEventual in (
			SELECT DISTINCT
				LancamentoFinanceiroDados.IdContaEventual 
			FROM 
				ContaReceberDados, 
				LancamentoFinanceiroDados
			WHERE 
				ContaReceberDados.IdLoja = $local_IdLoja AND
				ContaReceberDados.IdCarne = $filtro_IdCarne AND
				ContaReceberDados.IdLoja = LancamentoFinanceiroDados.IdLoja AND
				ContaReceberDados.IdContaReceber = LancamentoFinanceiroDados.IdContaReceber)";
	}
	
	if($filtro_descricao!=''){
		$filtro_url .= "&DescricaoContaEventual=$filtro_descricao";
		$filtro_sql .=	" and (DescricaoContaEventual like '%$filtro_descricao%')";
	}
	
	if($filtro_local_cobranca!=""){
		$filtro_url .= "&IdLocalCobranca=".$filtro_local_cobranca;
		$filtro_sql .= " and ContaEventual.IdLocalCobranca = $filtro_local_cobranca";
	}
	
	if($filtro_conta_receber!=""){
		$filtro_url  .= "&IdContaReceber=".$filtro_conta_receber;
		$filtro_sql .= " and ContaReceber.IdContaReceber = $filtro_conta_receber";
	}
	
	if($filtro_data!=""){
		$filtro_url .= "&Vencimento=".$filtro_data;
		$filtro_data2 = dataConv($filtro_data,'d/m/Y','Y-m-d');
		$filtro_sql .= " and (ContaEventualParcela.Vencimento = '$filtro_data2' or ContaEventualParcela.MesReferencia = '$filtro_data')";
	}
	
	if($filtro_idstatus!=""){
		$filtro_url .= "&IdStatus=".$filtro_idstatus;
		$filtro_sql .= " and ContaEventual.IdStatus = $filtro_idstatus";
	}
	
	if($filtro_IdContaReceber!=""){
		$filtro_sql .= " and ContaEventual.IdContaEventual in (SELECT DISTINCT IdContaEventual FROM LancamentoFinanceiroDados WHERE IdLoja = '$local_IdLoja' AND IdContaReceber = '$filtro_IdContaReceber')";
	}
	
	if($local_IdAgenteAutorizado!=""){
		$sqlAux		 =	",(select distinct IdPessoa from AgenteAutorizadoPessoa where IdLoja = $local_IdLoja and IdAgenteAutorizado in ($local_IdAgenteAutorizado) and IdCarteira = '$local_IdPessoaLogin') PessoaCarteira";
		$filtro_sql .=  " and  ContaEventual.IdPessoa = PessoaCarteira.IdPessoa";
	}
	
	if($filtro_campo!=''){
		$filtro_url .= "&Campo=$filtro_campo";
		$filtro_url .= "&Valor=".$filtro_campo_valor;
	
		switch($filtro_campo){
			case 'DataCriacao':
				$filtro_campo_valor = dataConv($filtro_campo_valor ,'d/m/Y','Y-m-d');
				$filtro_sql .= " AND SUBSTRING(ContaEventual.DataCriacao,1,10) = '$filtro_campo_valor'";
				break;
			case 'DataAlteracao': 
				$filtro_campo_valor = dataConv($filtro_campo_valor ,'d/m/Y','Y-m-d');
				$filtro_sql .= " AND SUBSTRING(ContaEventual.DataAlteracao,1,10) = '$filtro_campo_valor'";
				break;
			case 'DataConfirmacao':
				$filtro_campo_valor = dataConv($filtro_campo_valor ,'d/m/Y','Y-m-d');
				$filtro_sql .= "AND SUBSTRING(ContaEventual.DataConfirmacao,1,10) = '$filtro_campo_valor'";
				break;
			case 'MesCadastro':
				$filtro_campo_valor = dataConv($filtro_campo_valor ,'m/Y','Y-m');
				$filtro_sql .= " AND SUBSTRING(ContaEventual.DataCriacao,1,7) = '$filtro_campo_valor'";
				break;
			case'MesAlteracao':
				$filtro_campo_valor = dataConv($filtro_campo_valor ,'m/Y','Y-m');
				$filtro_sql .= "AND SUBSTRING(ContaEventual.DataAlteracao,1,7) = '$filtro_campo_valor'";
				break;
			case'MesConfirmacao':
				$filtro_campo_valor = dataConv($filtro_campo_valor ,'m/Y','Y-m');
				$filtro_sql .= "AND SUBSTRING(ContaEventual.DataConfirmacao,1,7) = '$filtro_campo_valor'";
				break;
			case'IdContaReceber':
				$sqlAux .= " ,LancamentoFinanceiroDados";
				$filtro_sql .=" AND LancamentoFinanceiroDados.IdContaEventual = ContaEventual.IdContaEventual
								AND LancamentoFinanceiroDados.IdContaReceber = '$filtro_campo_valor'";
				break;
			case'Referencia':
				$filtro_sql .= " AND ContaEventualParcela.MesReferencia ='$filtro_campo_valor'";
				break;
			case'DataPrimeiroVencimento':
				$filtro_campo_valor = dataConv($filtro_campo_valor ,'d/m/Y','Y-m-d');
				$filtro_sql .= "And ContaEventualParcela.IdContaEventualParcela = 1 AND ContaEventualParcela.Vencimento = '$filtro_campo_valor'";
				break;
		}
	}
	
	if($_SESSION["RestringirAgenteAutorizado"] == true){
		$sqlAgente	=	"select 
							AgenteAutorizado.IdGrupoPessoa 
						from 
							AgenteAutorizado
						where 
							AgenteAutorizado.IdLoja = $local_IdLoja  and 
							AgenteAutorizado.IdAgenteAutorizado = '$local_IdPessoaLogin' and 
							AgenteAutorizado.Restringir = 1 and 
							AgenteAutorizado.IdStatus = 1 and
							AgenteAutorizado.IdGrupoPessoa is not null";
		$resAgente	=	@mysql_query($sqlAgente,$con);
		while($linAgente	=	@mysql_fetch_array($resAgente)){
			$filtro_sql    .=	" and GrupoPessoa.IdGrupoPessoa = $linAgente[IdGrupoPessoa]"; 
		}
	}
	if($_SESSION["RestringirAgenteCarteira"] == true){
		$sqlAgente	=	"select 
							AgenteAutorizado.IdGrupoPessoa 
						from 
							AgenteAutorizado,
							Carteira
						where 
							AgenteAutorizado.IdLoja = $local_IdLoja  and 
							AgenteAutorizado.IdLoja = Carteira.IdLoja and
							AgenteAutorizado.IdAgenteAutorizado = Carteira.IdAgenteAutorizado and
							Carteira.IdCarteira = '$local_IdPessoaLogin' and 
							AgenteAutorizado.Restringir = 1 and 
							AgenteAutorizado.IdStatus = 1 and 
							AgenteAutorizado.IdGrupoPessoa is not null";
		$resAgente	=	@mysql_query($sqlAgente,$con);
		while($linAgente	=	@mysql_fetch_array($resAgente)){
			$filtro_sql    .=	" and GrupoPessoa.IdGrupoPessoa = $linAgente[IdGrupoPessoa]"; 
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
	echo "<?xml-stylesheet type=\"text/xsl\" href=\"listar_conta_eventual_xsl.php$filtro_url\"?>";
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
	
		 $sql = "SELECT 
				  ContaEventual.IdContaEventual,
				  SUBSTR(ContaEventual.DescricaoContaEventual, 1, 30) DescricaoContaEventual,
				  Pessoa.TipoPessoa,
				  SUBSTR(Pessoa.Nome, 1, 20) Nome,
				  SUBSTR(Pessoa.Nome, 1, 20) RazaoSocial,
				  LocalCobranca.AbreviacaoNomeLocalCobranca,
				  ContaEventualParcela.MesReferencia,
				  ContaEventual.ValorTotal,
				  ContaEventual.FormaCobranca,
				  ContaEventual.IdStatus,
				  ContaEventual.QtdParcela,
				  ContaEventualParcela.Vencimento,
				  ContaEventual.IdContrato 
			from
				ContaEventual left join (
					select 
						ContaEventualParcela.IdContaEventual, 
						ContaEventualParcela.Vencimento, 
						ContaEventualParcela.MesReferencia,
						ContaEventualParcela.IdContaEventualParcela
					from 
						ContaEventualParcela 
					where 
						ContaEventualParcela.IdLoja = $local_IdLoja 
					group by 
						ContaEventualParcela.IdContaEventual 
					order by 
						ContaEventualParcela.Vencimento Asc 
				) ContaEventualParcela ON (
					ContaEventual.IdContaEventual = ContaEventualParcela.IdContaEventual
				) left join LocalCobranca ON (
					ContaEventual.IdLoja = LocalCobranca.IdLoja and 
					ContaEventual.IdLocalCobranca = LocalCobranca.IdLocalCobranca
				), 
				Pessoa left join (
					PessoaGrupoPessoa, 
					GrupoPessoa
				) on (
					Pessoa.IdPessoa = PessoaGrupoPessoa.IdPessoa and 
					PessoaGrupoPessoa.IdLoja = '$local_IdLoja' and 
					PessoaGrupoPessoa.IdLoja = GrupoPessoa.IdLoja and 
					PessoaGrupoPessoa.IdGrupoPessoa = GrupoPessoa.IdGrupoPessoa
				) $sqlAux
			where	
				ContaEventual.IdLoja = $local_IdLoja and
				ContaEventual.IdPessoa = Pessoa.IdPessoa
				$filtro_sql
			order by
				ContaEventual.IdContaEventual desc
			$Limit";
	$res = mysql_query($sql,$con);
	while($lin = mysql_fetch_array($res)){
		if($lin[FormaCobranca] == 1){
			$lin[Vencimento]		= $lin[MesReferencia];	
			$lin[VencimentoTemp] 	= $lin[MesReferencia];	
		}else{
			$lin[VencimentoTemp] 	= dataConv($lin[Vencimento],"Y-m-d","d/m/Y");
			$lin[Vencimento] 		= dataConv($lin[Vencimento],"Y-m-d","Ymd");
		}		
		
		if($lin[TipoPessoa]=='1'){
			$lin[Nome]	=	$lin[trim(getCodigoInterno(3,24))];	
		}
		
		$sql2 = "select ValorParametroSistema  from ParametroSistema where IdGrupoParametroSistema=46 and IdParametroSistema=$lin[IdStatus]";
		$res2 = @mysql_query($sql2,$con);
		$lin2 = @mysql_fetch_array($res2);
		
		switch($lin[IdStatus]){
			case '2':
				$ImgExc	  		= "../../img/estrutura_sistema/ico_del_c.gif";
				break;
			default:
				$ImgExc	  		= "../../img/estrutura_sistema/ico_del.gif";
				break;
		}
		
		$sql3 = "select ValorParametroSistema  from ParametroSistema where IdGrupoParametroSistema=50 and IdParametroSistema=$lin[FormaCobranca]";
		$res3 = @mysql_query($sql3,$con);
		$lin3 = @mysql_fetch_array($res3);
		
	#	$lin[ValorTotal]	=	number_format($lin[ValorTotal],2,",","");
	#	$lin[ValorTotalT]
		
		echo "<reg>";	
		echo 	"<IdContaEventual>$lin[IdContaEventual]</IdContaEventual>";	
		echo 	"<DescricaoContaEventual><![CDATA[$lin[DescricaoContaEventual]]]></DescricaoContaEventual>";
		echo 	"<Nome><![CDATA[$lin[Nome]]]></Nome>";
		echo 	"<AbreviacaoNomeLocalCobranca><![CDATA[$lin[AbreviacaoNomeLocalCobranca]]]></AbreviacaoNomeLocalCobranca>";
		echo 	"<ValorTotal><![CDATA[$lin[ValorTotal]]]></ValorTotal>";
		echo 	"<QtdParcela><![CDATA[$lin[QtdParcela]]]></QtdParcela>";
		echo 	"<IdStatus><![CDATA[$lin[IdStatus]]]></IdStatus>";
		echo 	"<Vencimento><![CDATA[$lin[Vencimento]]]></Vencimento>";
		echo 	"<VencimentoTemp><![CDATA[$lin[VencimentoTemp]]]></VencimentoTemp>";	
		echo 	"<Status><![CDATA[$lin2[ValorParametroSistema]]]></Status>";	
		echo 	"<FormaCobranca><![CDATA[$lin3[ValorParametroSistema]]]></FormaCobranca>";	
		echo 	"<ImgExc><![CDATA[$ImgExc]]></ImgExc>";
		echo "</reg>";	
	}
	
	echo "</db>";
?>
