<?
	$localModulo	=	1;
	
	include ('../../../files/conecta.php');
	include ('../../../files/funcoes.php');
	include ('../../../rotinas/verifica.php');
	
	function get_reenvio_mensagem_status(){
		global $con;
		global $_SESSION;
		global $_GET;
		
		$IdLoja			= $_SESSION["IdLoja"];
		$IdHistoricoMensagem	= $_GET['IdHistoricoMensagem'];
		$where			= "";
		/*
		if($IdHistoricoMensagem != ''){
			$where .= " and HistoricoMensagem.IdHistoricoMensagem = $IdHistoricoMensagem";
		}*/
		$sql2 = "select 
					HistoricoMensagem.IdTipoMensagem, 
					HistoricoMensagem.IdPessoa 
				from
					HistoricoMensagem,
					MalaDireta 
				where
					HistoricoMensagem.IdLoja = $IdLoja and
					HistoricoMensagem.IdLoja = MalaDireta.IdLoja and
					HistoricoMensagem.IdHistoricoMensagem = '$IdHistoricoMensagem'
				group by
					IdHistoricoMensagem";
		$res2 =	@mysql_query($sql2,$con);
		$lin2 =	@mysql_fetch_array($res2);
		if($lin2[IdTipoMensagem] >= 1000000){
			$from ="left join MalaDireta 
					on (
					  TipoMensagem.IdTipoMensagem = MalaDireta.IdTipoMensagem and
					  MalaDireta.IdLoja = '$IdLoja' and
					  TipoMensagem.IdLoja = MalaDireta.IdLoja 
					)";
			
			if($lin2[IdPessoa] != ''){
				$filtro_sql_id_pessoa = " and HistoricoMensagem.IdPessoa = Pessoa.IdPessoa";
			}else{
				$filtro_sql_id_pessoa = "";
			}
			$where				 .=	" and HistoricoMensagem.IdHistoricoMensagem = $IdHistoricoMensagem";
			$order_group		 =	" group by HistoricoMensagem.IdHistoricoMensagem";
		}else{
			$filtro_sql_id_pessoa = " and HistoricoMensagem.IdPessoa = Pessoa.IdPessoa";
			$where				 .=	" and HistoricoMensagem.IdHistoricoMensagem = $IdHistoricoMensagem";
			$order_group		 = 	"";
		}
		
		header ("content-type: text/xml");
		$dados	=	"<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
		$dados	.=	"\n<reg>";
		
		$sql = "select
    				HistoricoMensagem.IdStatus
    			from
    				HistoricoMensagem,
    				Pessoa left join (PessoaGrupoPessoa, GrupoPessoa) on (
    					Pessoa.IdPessoa = PessoaGrupoPessoa.IdPessoa and 
    					PessoaGrupoPessoa.IdLoja = '$IdLoja' and 
    					PessoaGrupoPessoa.IdLoja = GrupoPessoa.IdLoja and 
    					PessoaGrupoPessoa.IdGrupoPessoa = GrupoPessoa.IdGrupoPessoa
    				) ,
    				TipoMensagem
    			where
    				HistoricoMensagem.IdLoja = $IdLoja and
    				HistoricoMensagem.IdLoja = TipoMensagem.IdLoja and	
    				HistoricoMensagem.IdTipoMensagem = TipoMensagem.IdTipoMensagem
					$filtro_sql_id_pessoa
    				$where
					$order_group";
		$res = @mysql_query($sql,$con);
		if($lin = @mysql_fetch_array($res)){
            
            switch($lin[IdStatus]){
    			case 1:
    				$IdStatusTemp = 1;
    				break;
    			case 2:
    				$IdStatusTemp = 2;
    				break;
    			case 3:
    				$IdStatusTemp = 3;
    				break;
    			case 4:
    				$IdStatusTemp = 4;
    				break;
    			case 5:
    				$IdStatusTemp = 5;
    				break;
    			case 6:
    				$IdStatusTemp = 6;
    				break;
            }
		}
		
		list($Cor) = explode("\r\n",getCodigoInterno(54, $IdStatusTemp));
		$ValorParametroSistema = getParametroSistema(193, $IdStatusTemp);
		
		$dados	.=	"\n<ValorParametroSistema><![CDATA[$ValorParametroSistema]]></ValorParametroSistema>";
		$dados	.=	"\n<Cor><![CDATA[$Cor]]></Cor>";		
		$dados	.=	"\n</reg>";
		
		return $dados;
	}
	echo get_reenvio_mensagem_status();
?>