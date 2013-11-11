<?
	$localModulo = 0;
	
	include('../../../files/conecta.php');
	
	function get_Pessoa(){
		global $con;
		global $_GET;
		
		$IdLoja	 	= $_SESSION['IdLoja'];
		$IdPessoa 	= $_GET['IdPessoa'];
		$CPF_CNPJ	= $_GET['CPF_CNPJ'];
		$Limit 		= $_GET['Limit'];
		$where		= "";
		
		if($Limit != ''){
			$Limit = "limit 0,$Limit";
		}
		
		if($IdPessoa != ''){
			$where .= " and Pessoa.IdPessoa=$IdPessoa";	
		}
		
		if($CPF_CNPJ != ''){
			$where .= " and Pessoa.CPF_CNPJ like '$CPF_CNPJ%'";	
		}
		
		$sql = "select
					Pessoa.IdPessoa,					
					Pessoa.TipoPessoa,
					Pessoa.Nome
				from 
					Pessoa
				where
					1
					$where
				$Limit;";
		$res = @mysql_query($sql,$con);
		
		if(@mysql_num_rows($res) > 0){
			header("content-type: text/xml");
			$dados	=	"<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
			$dados	.=	"\n<reg>";
			
			while($lin = @mysql_fetch_array($res)){
				$dados	.=	"\n<IdPessoa><![CDATA[$lin[IdPessoa]]]></IdPessoa>";
				$dados	.=	"\n<TipoPessoa><![CDATA[$lin[TipoPessoa]]]></TipoPessoa>";
				$dados	.=	"\n<Nome><![CDATA[$lin[Nome]]]></Nome>";
			}
			
			$dados	.=	"\n</reg>";
			return $dados;
		} else{
			return "false";
		}
	}
	
	echo get_Pessoa();
?>