<?
	$localModulo	=	1;

	include ('../../../files/conecta.php');
	include ('../../../files/funcoes.php');
	include ('../../../rotinas/verifica.php');
	
	function get_grupo_pessoa(){
		global $con;
		global $_GET;
		
		$IdLoja					= $_SESSION["IdLoja"];
		$IdPessoaLogin			= $_SESSION['IdPessoa'];
		$Limit 					= $_GET['Limit'];
		$IdGrupoPessoa			= $_GET['IdGrupoPessoa'];
		$Nome				  	= $_GET['Nome'];
		$where					= "";
		
		if($Limit != ''){
			$Limit = "limit 0,$Limit";
		}
		
		if($Nome !=''){	 				 
			$where  .= " and DescricaoGrupoPessoa like '$Nome%'";	 
		}
		
		if($IdGrupoPessoa!= ''){
			$where	.=	" and IdGrupoPessoa = ".$IdGrupoPessoa;
		}
		
		if($_SESSION["RestringirAgenteAutorizado"] == true){
			$sqlAgente	=	"select 
								AgenteAutorizado.IdGrupoPessoa 
							from 
								AgenteAutorizado
							where 
								AgenteAutorizado.IdLoja = $IdLoja  and 
								AgenteAutorizado.IdAgenteAutorizado = '$IdPessoaLogin' and 
								AgenteAutorizado.Restringir = 1 and 
								AgenteAutorizado.IdStatus = 1 and
								AgenteAutorizado.IdGrupoPessoa is not null";
			$resAgente	=	@mysql_query($sqlAgente,$con);
			while($linAgente	=	@mysql_fetch_array($resAgente)){
				$where    .=	" and IdGrupoPessoa = $linAgente[IdGrupoPessoa]"; 
			}
		}
		
		if($_SESSION["RestringirAgenteCarteira"] == true){
			$sqlAgente	=	"select 
								AgenteAutorizado.IdGrupoPessoa 
							from 
								AgenteAutorizado,
								Carteira
							where 
								AgenteAutorizado.IdLoja = $IdLoja  and 
								AgenteAutorizado.IdLoja = Carteira.IdLoja and
								AgenteAutorizado.IdAgenteAutorizado = Carteira.IdAgenteAutorizado and
								Carteira.IdCarteira = '$IdPessoaLogin' and 
								AgenteAutorizado.Restringir = 1 and 
								AgenteAutorizado.IdStatus = 1 and 
								AgenteAutorizado.IdGrupoPessoa is not null";
			$resAgente	=	@mysql_query($sqlAgente,$con);
			while($linAgente	=	@mysql_fetch_array($resAgente)){
				$where    .=	" and IdGrupoPessoa = $linAgente[IdGrupoPessoa]"; 
			}
		}
		
		$sql = "select
					IdGrupoPessoa,
					DescricaoGrupoPessoa,
					DataAlteracao,
					LoginAlteracao,
					DataCriacao,
					LoginCriacao
				from
					GrupoPessoa 
				where
					IdLoja = $IdLoja
					$where 
				$Limit";
		$res = mysql_query($sql,$con);
		if(@mysql_num_rows($res) > 0){
			header ("content-type: text/xml");
			$dados	=	"<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
			$dados	.=	"\n<reg>";
			
			while($lin = @mysql_fetch_array($res)){
				$dados	.=	"\n<IdGrupoPessoa>$lin[IdGrupoPessoa]</IdGrupoPessoa>";
				$dados	.=	"\n<DescricaoGrupoPessoa><![CDATA[$lin[DescricaoGrupoPessoa]]]></DescricaoGrupoPessoa>";
				$dados	.=	"\n<DataCriacao><![CDATA[$lin[DataCriacao]]]></DataCriacao>";
				$dados	.=	"\n<LoginCriacao><![CDATA[$lin[LoginCriacao]]]></LoginCriacao>";
				$dados	.=	"\n<DataAlteracao><![CDATA[$lin[DataAlteracao]]]></DataAlteracao>";
				$dados	.=	"\n<LoginAlteracao><![CDATA[$lin[LoginAlteracao]]]></LoginAlteracao>";
			}
			
			$dados	.=	"\n</reg>";
			return $dados;
		} else{
			return "false";
		}
	}
	echo get_grupo_pessoa();
?>