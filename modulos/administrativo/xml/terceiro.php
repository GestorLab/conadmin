<?
	$localModulo	=	1;

	include ('../../../files/conecta.php');
	include ('../../../files/funcoes.php');
	include ('../../../rotinas/verifica.php');
	
	function get_terceiro(){
		
		global $con;
		global $_GET;
		
		$Limit 					= $_GET['Limit'];
		$IdLoja					= $_SESSION["IdLoja"];
		$where					=	"";
		
		if($Limit != ''){		$Limit = "limit 0,$Limit";	}
		
		if($_GET['IdPessoa'] != ''){		
			$where .= " and Terceiro.IdPessoa = ".$_GET['IdPessoa'];
		}
		if($_POST['IdPessoa'] != ''){		
			$where .= " and Terceiro.IdPessoa = ".$_POST['IdPessoa'];
		}
		if($_GET['Nome'] != ''){		
			$where .= " and (Pessoa.Nome like '".$_GET['Nome']."%' or Pessoa.RazaoSocial like '".$_GET['Nome']."%')";
		}
		
		$IdPais			= $_GET['IdPais'];
		$IdEstado		= $_GET['IdEstado'];
		$NomeCidade		= $_GET['NomeCidade'];
		$CPF_CNPJ		= $_GET['CPF_CNPJ'];
		
		if($IdPais){			$where .= " and Pais.IdPais=$IdPais";	}
		if($IdEstado){			$where .= " and Estado.IdEstado = '$IdEstado'";	}
		if($NomeCidade){		$where .= " and Cidade.NomeCidade like '$NomeCidade%'";	}
		if($CPF_CNPJ){			$where .= " and CPF_CNPJ like '$CPF_CNPJ%'";	}
		
		$sql	=	"SELECT  
				        Terceiro.IdPessoa,
				        Pessoa.Nome,
				        Pessoa.RazaoSocial,
				        Terceiro.DataCriacao,
				        Terceiro.LoginCriacao,
				        Terceiro.DataAlteracao,
				        Terceiro.LoginAlteracao
					from
					    Terceiro,
					    Pessoa,
					    PessoaEndereco,
					    Pais,
					    Estado,
					    Cidade
					where
					    Terceiro.IdLoja = $IdLoja and
					    Terceiro.IdPessoa = PessoaEndereco.IdPessoa and
					    PessoaEndereco.IdPessoa = Pessoa.IdPessoa and
					    PessoaEndereco.IdPessoaEndereco = Pessoa.IdEnderecoDefault and
						Pais.IdPais = PessoaEndereco.IdPais and
					    Estado.IdEstado = PessoaEndereco.IdEstado and
					    Cidade.IdCidade = PessoaEndereco.IdCidade and
					    Pais.IdPais = Estado.IdPais and
					    Pais.IdPais = Cidade.IdPais and
					    Estado.IdEstado = Cidade.IdEstado $where $Limit";
		$res	=	mysql_query($sql,$con);
		if(@mysql_num_rows($res) >=1){
			header ("content-type: text/xml");
			$dados	=	"<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
			$dados	.=	"\n<reg>";
		}else{
			return "false";
		}
		while($lin	=	@mysql_fetch_array($res)){
			#$lin[Nome] 			= $lin[getCodigoInterno(3,24)];
			
			$dados	.=	"\n<IdPessoa>$lin[IdPessoa]</IdPessoa>";
			$dados	.=	"\n<Nome><![CDATA[$lin[Nome]]]></Nome>";
			$dados	.=	"\n<RazaoSocial><![CDATA[$lin[RazaoSocial]]]></RazaoSocial>";
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
	echo get_terceiro();
?>
