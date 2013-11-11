<?
	include ('../files/conecta.php');
	include ('../files/funcoes.php');
	
	function get_Pessoa(){
		
		global $con;
		global $HTTP_GET_VARS;
		
		$CPF_CNPJ 	= $_GET['CPF_CNPJ'];
		
		$sql	=	"select 
					       Pessoa.IdPessoa,					
					       Email,
						   Cob_Email,
						   CPF_CNPJ
					from 
						   Pessoa
					where
					       CPF_CNPJ = '$CPF_CNPJ'";
		$res	=	@mysql_query($sql,$con);

		
		if(@mysql_num_rows($res) >=1){
			header ("content-type: text/xml");
			$dados	=	"<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
			$dados	.=	"\n<reg>";
		}else{
			return "false";
		}
		while($lin	=	@mysql_fetch_array($res)){
			if($lin[Cob_Email] != ''){
				$lin[Email] .= ";".$lin[Cob_Email];
			}
			
			$dados	.=	"\n<IdPessoa>$lin[IdPessoa]</IdPessoa>";
			$dados	.=	"\n<Email><![CDATA[$lin[Email]]]></Email>";
			$dados	.=	"\n<CPF_CNPJ><![CDATA[$lin[CPF_CNPJ]]]></CPF_CNPJ>";
		}
		if(mysql_num_rows($res) >=1){
			$dados	.=	"\n</reg>";
			return $dados;
		}
	}
	echo get_Pessoa();
?>
