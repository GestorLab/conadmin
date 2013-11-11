<?
	$localModulo	=	1;

	include ('../../../files/conecta.php');
	include ('../../../files/funcoes.php');
	include ('../../../rotinas/verifica.php');
	
	function get_simular_acesso_usuario(){
		
		global $con;
		global $_GET;
		
		$Limit 							= $_GET['Limit'];
		$Login							= $_GET['Login'];
		$NomeUsuario				  	= $_GET['NomeUsuario'];
		$Busca						  	= $_GET['Busca'];
		$where						  	= "";
		
		if($Limit != ''){		$Limit = "limit 0,$Limit";	}
		
		if($Login != ''){						 
			if($Busca == "Busca"){
				$where .= " and Login like '$Login%'";	
			}else{
				$where .= " and Usuario.Login = '$Login'";	
			}
		}
		
		if($NomeUsuario !=''){	 				 
			$where  .= " and Pessoa.Nome like '$NomeUsuario%'";	 
		}
		
		$sql	=	"select
				      Usuario.Login,
				      Pessoa.Nome,
				      Pessoa.Email,
				      Usuario.LimiteVisualizacao,
				      Usuario.IdPessoa,
				      Usuario.IpAcesso,
				      Usuario.IdStatus,
				      Usuario.DataExpiraSenha,
				      Usuario.LoginCriacao,
				      Usuario.DataCriacao,
				      Usuario.LoginAlteracao,
				      Usuario.DataAlteracao
				from
				      Usuario LEFT JOIN Pessoa ON (Usuario.IdPessoa = Pessoa.IdPessoa)
				where
					  1 $where $Limit";
		$res	=	mysql_query($sql,$con);
		if(@mysql_num_rows($res) >=1){
			header ("content-type: text/xml");
			$dados	=	"<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
			$dados	.=	"\n<reg>";
		}else{
			return "false";
		}
		while($lin	=	@mysql_fetch_array($res)){
			$dados	.=	"\n<Login><![CDATA[$lin[Login]]]></Login>";
			$dados	.=	"\n<IdPessoa><![CDATA[$lin[IdPessoa]]]></IdPessoa>";
			$dados	.=	"\n<NomeUsuario><![CDATA[$lin[Nome]]]></NomeUsuario>";
			$dados	.=	"\n<Email><![CDATA[$lin[Email]]]></Email>";
			$dados	.=	"\n<LimiteVisualizacao><![CDATA[$lin[LimiteVisualizacao]]]></LimiteVisualizacao>";
			$dados	.=	"\n<IpAcesso><![CDATA[$lin[IpAcesso]]]></IpAcesso>";
			$dados	.=	"\n<DataExpiraSenha><![CDATA[$lin[DataExpiraSenha]]]></DataExpiraSenha>";
			$dados	.=	"\n<IdStatus><![CDATA[$lin[IdStatus]]]></IdStatus>";
			$dados	.=	"\n<DataCriacao><![CDATA[$lin[DataCriacao]]]></DataCriacao>";
			$dados	.=	"\n<LoginCriacao><![CDATA[$lin[LoginCriacao]]]></LoginCriacao>";
			$dados	.=	"\n<DataAlteracao><![CDATA[$lin[DataAlteracao]]]></DataAlteracao>";
			$dados	.=	"\n<LoginAlteracao><![CDATA[$lin[LoginAlteracao]]]></LoginAlteracao>";
		}
		if(mysql_num_rows($res) >=1){
			$dados	.=	"\n</reg>";
			return $dados;
		}
	}
	echo get_simular_acesso_usuario();
?>