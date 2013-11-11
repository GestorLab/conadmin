<?
	$localModulo	=	1;

	include ('../../../files/conecta.php');
	include ('../../../files/funcoes.php');
	include ('../../../rotinas/verifica.php');
	
	function get_fornecedor(){
		
		global $con;
		global $_GET;
		
		$Limit 					= $_GET['Limit'];
		$IdLoja					= $_SESSION["IdLoja"];
		$IdPessoa				= $_GET['IdPessoa'];
		$Nome					= $_GET['Nome'];
		$IdPais					= $_GET['IdPais'];
		$IdEstado				= $_GET['IdEstado'];
		$NomeCidade				= $_GET['NomeCidade'];
		$CPF_CNPJ				= $_GET['CPF_CNPJ'];
		$where					=	"";
		
		
		if($IdPessoa=="" && $_POST['IdPessoa']!=""){
			$IdPessoa	= $_POST['IdPessoa'];
		}
		
		if($Limit != ''){		$Limit = "limit 0,$Limit";	}
		
		if($IdPessoa != ''){		$where .= " and Fornecedor.IdFornecedor = ".$IdPessoa;		}
		if($Nome != ''){			$where .= " and (Pessoa.Nome like '$Nome' or Pessoa.RazaoSocial like '$Nome')";		}
		if($IdPais!=''){			$where .= " and Pais.IdPais=$IdPais";	}
		if($IdEstado!=''){			$where .= " and Estado.IdEstado = '$IdEstado'";	}
		if($NomeCidade!=''){		$where .= " and Cidade.NomeCidade like '$NomeCidade%'";	}
		if($CPF_CNPJ!=''){			$where .= " and Pessoa.CPF_CNPJ like '$CPF_CNPJ%'";	}
		
		$sql	=	"SELECT  
					    Fornecedor.IdFornecedor,
					    Pessoa.Nome,
					    Pessoa.RazaoSocial,
					    Pessoa.CPF_CNPJ,
					    Pessoa.TipoPessoa,
					    Fornecedor.DataCriacao,
					    Fornecedor.LoginCriacao,
					    Fornecedor.DataAlteracao,
					    Fornecedor.LoginAlteracao
					from
					    Fornecedor,
					    Pessoa,
					    PessoaEndereco,
					    Pais,
					    Estado,
					    Cidade
					where
					    Fornecedor.IdLoja = $IdLoja and
					    Fornecedor.IdFornecedor = Pessoa.IdPessoa and
					    Pessoa.IdPessoa = PessoaEndereco.IdPessoa and
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
			$lin[Nome] 			= $lin[getCodigoInterno(3,24)];
			
			$dados	.=	"\n<IdPessoa>$lin[IdFornecedor]</IdPessoa>";
			$dados	.=	"\n<Nome><![CDATA[$lin[Nome]]]></Nome>";
			$dados	.=	"\n<RazaoSocial><![CDATA[$lin[RazaoSocial]]]></RazaoSocial>";
			$dados	.=	"\n<CPF_CNPJ><![CDATA[$lin[CPF_CNPJ]]]></CPF_CNPJ>";
			$dados	.=	"\n<TipoPessoa><![CDATA[$lin[TipoPessoa]]]></TipoPessoa>";
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
	echo get_fornecedor();
?>
