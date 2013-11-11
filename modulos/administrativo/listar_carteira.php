<?
	$localModulo		=	1;
	$localOperacao		=	24;
	$localSuboperacao	=	"R";
		
	include ('../../files/conecta.php');
	include ('../../files/funcoes.php');
	include ('../../rotinas/verifica.php');
	 
	
	$filtro							= $_POST['filtro'];
	$filtro_ordem					= $_POST['filtro_ordem'];
	$filtro_ordem_direcao			= $_POST['filtro_ordem_direcao'];
	$filtro_localTipoDado			= $_POST['filtro_tipoDado'];
	$filtro_nome					= $_POST['filtro_nome'];
	$filtro_nome_agente				= $_POST['filtro_nome_agente'];
	$filtro_restringir				= $_POST['filtro_restringir'];
	$filtro_status					= $_POST['filtro_status'];
	$filtro_agente					= $_GET['IdAgenteAutorizado'];
	$filtro_carteira				= $_GET['IdCarteira'];
	$filtro_limit					= $_POST['filtro_limit'];
	
	if($filtro_agente == ''){
		$filtro_agente				= $_POST['IdAgenteAutorizado'];
	}
	
	if($filtro_carteira == ''){
		$filtro_carteira			= $_POST['IdCarteira'];
	}
	
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
		
	if($filtro_nome_agente!=''){
		$filtro_url .= "&NomeAgenteAutorizado=$filtro_nome_agente";
		$filtro_sql .=	" and (AgenteAutorizado.NomeAgenteAutorizado like '%$filtro_nome_agente%' or AgenteAutorizado.RazaoSocialAgenteAutorizado like '%$filtro_nome_agente%')";
	}
	
	if($filtro_nome!=''){
		$filtro_url .= "&Nome=$filtro_nome";
		$filtro_sql .=	" and (Pessoa.Nome like '%$filtro_nome%' or Pessoa.RazaoSocial like '%$filtro_nome%')";
	}
	
	if($filtro_agente!=''){
		$filtro_url .= "&IdAgenteAutorizado=$filtro_agente";
		$filtro_sql .=	" and Carteira.IdAgenteAutorizado=$filtro_agente";
	}
	
	if($filtro_carteira!=''){
		$filtro_url .= "&IdCarteira=$filtro_carteira";
		$filtro_sql .=	" and Carteira.IdCarteira=$filtro_carteira";
	}
	
	if($filtro_restringir!=''){
		$filtro_url .= "&Restringir=$filtro_restringir";
		$filtro_sql .=	" and Carteira.Restringir=$filtro_restringir";
	}
	
	if($filtro_status!=''){
		$filtro_url .= "&IdStatus=$filtro_status";
		$filtro_sql .=	" and (Carteira.IdStatus = '$filtro_status')";
	}
		
	if($filtro_limit!="")
		$filtro_url .= "&Limit=".$filtro_limit;
		
	if($filtro_url != ""){
		$filtro_url	= "?f=t".$filtro_url;	
		$filtro_url	= url_string_xsl($filtro_url, "CONVERT", false);
	}
		
		header ("content-type: text/xml");
	
	echo "<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
	echo "<?xml-stylesheet type=\"text/xsl\" href=\"listar_carteira_xsl.php$filtro_url\"?>";
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
					Carteira.IdLoja,
					Carteira.IdCarteira, 
					Carteira.IdAgenteAutorizado,
				    AgenteAutorizado.RazaoSocialAgenteAutorizado,
				    AgenteAutorizado.NomeAgenteAutorizado,
				    AgenteAutorizado.TipoPessoaAgenteAutorizado,
					Pessoa.TipoPessoa,
					Pessoa.RazaoSocial,
					Pessoa.Nome,
					Carteira.IdStatus,
					Carteira.Restringir 
				from 
					Carteira,
					(
						select 
							AgenteAutorizado.IdLoja,
							AgenteAutorizado.IdAgenteAutorizado,
							Pessoa.TipoPessoa TipoPessoaAgenteAutorizado, 
							Pessoa.RazaoSocial RazaoSocialAgenteAutorizado,
							Pessoa.Nome NomeAgenteAutorizado 
						from 
							Pessoa,
							AgenteAutorizado 
						where 
							Pessoa.IdPessoa = AgenteAutorizado.IdAgenteAutorizado
					)AgenteAutorizado,
					Pessoa 
				where
					Carteira.IdLoja = $local_IdLoja and
					Carteira.IdLoja = AgenteAutorizado.IdLoja and
					Carteira.IdCarteira = Pessoa.IdPessoa and
					Carteira.IdAgenteAutorizado = AgenteAutorizado.IdAgenteAutorizado $filtro_sql
				order by
					Carteira.IdCarteira, AgenteAutorizado.NomeAgenteAutorizado 
				$Limit";
	$res	=	mysql_query($sql,$con);
	while($lin	=	mysql_fetch_array($res)){
		if($lin[TipoPessoa]=='1'){
			$lin[Nome]	=	$lin[trim(getCodigoInterno(3,24))];	
		}
		
		$sql2 = "select ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema=92 and IdParametroSistema = $lin[IdStatus]";
		$res2 = mysql_query($sql2,$con);
		$lin2 = mysql_fetch_array($res2);
		
		switch($lin[IdStatus]){
			case '2': 
				$Color	  =	getParametroSistema(15,2);
				break;
			case '1':
				$Color	  = getParametroSistema(15,3);		
				break;
		}
		
		if($lin[TipoPessoaAgenteAutorizado]=='1'){
			if(getCodigoInterno(3,24) == 'RazaoSocial'){
				$lin[NomeAgenteAutorizado]	=	$lin[RazaoSocialAgenteAutorizado];	
			}
		}
		
		$sql3 = "select ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema=116 and IdParametroSistema = $lin[Restringir]";
		$res3 = mysql_query($sql3,$con);
		$lin3 = mysql_fetch_array($res3);
		
		echo "<reg>";	
		echo 	"<IdLoja>$lin[IdLoja]</IdLoja>";		
		echo 	"<IdCarteira>$lin[IdCarteira]</IdCarteira>";	
		echo 	"<Nome><![CDATA[$lin[Nome]]]></Nome>";	
		echo 	"<IdAgenteAutorizado>$lin[IdAgenteAutorizado]</IdAgenteAutorizado>";	
		echo 	"<NomeAgenteAutorizado><![CDATA[$lin[NomeAgenteAutorizado]]]></NomeAgenteAutorizado>";
		echo 	"<Status><![CDATA[$lin2[ValorParametroSistema]]]></Status>";
		echo 	"<Color><![CDATA[$Color]]></Color>";			
		echo 	"<DescRestringir><![CDATA[$lin3[ValorParametroSistema]]]></DescRestringir>";			
		echo "</reg>";	
	}
	
	echo "</db>";
?>
