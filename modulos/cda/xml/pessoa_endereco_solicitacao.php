<?
	$localModulo	=	0;

	include ('../../../files/conecta.php');
	
	function get_Pessoa_Endereco(){
		
		global $con;
		global $_GET;
		
		$Limit 					= $_GET['Limit'];
		$IdLoja	 				= $_SESSION['IdLoja'];
		$IdPessoa 				= $_GET['IdPessoa'];
		$IdPessoaEndereco 		= $_GET['IdPessoaEndereco'];
		$IdPessoaSolicitacao 	= $_GET['IdPessoaSolicitacao'];
		$where					= "";
		
		if($Limit != ''){		$Limit = "limit 0,$Limit";	}
		
		if($IdPessoa != ''){				$where .= " and PessoaSolicitacaoEndereco.IdPessoa=$IdPessoa";	}
		if($IdPessoaSolicitacao != ''){		$where .= " and PessoaSolicitacaoEndereco.IdPessoaSolicitacao=$IdPessoaSolicitacao";	}
		if($IdPessoaEndereco !=''){			$where .= " and PessoaSolicitacaoEndereco.IdPessoaEndereco=$IdPessoaEndereco";	}
		
			
		$sql	=	"select 
					       PessoaSolicitacaoEndereco.IdPessoa,					
					       PessoaSolicitacaoEndereco.IdPessoaEndereco,
					       Telefone,					
					       Celular,
						   Fax,	
						   ComplementoTelefone,					
					       EmailEndereco,
					       NomeResponsavelEndereco,
					       Endereco,
					       CEP,
					       Bairro,
					       Complemento,
					       Numero,
					       PessoaSolicitacaoEndereco.IdPais,
						   Pais.NomePais,
						   PessoaSolicitacaoEndereco.IdEstado,
						   Estado.NomeEstado,
						   Estado.SiglaEstado,
						   PessoaSolicitacaoEndereco.IdCidade,
						   Cidade.NomeCidade,
						   DescricaoEndereco  
					from 
						   PessoaSolicitacaoEndereco LEFT JOIN Pais ON (PessoaSolicitacaoEndereco.IdPais = Pais.IdPais) LEFT JOIN Estado ON (PessoaSolicitacaoEndereco.IdEstado = Estado.IdEstado and Estado.IdPais = Pais.IdPais) LEFT JOIN Cidade ON (PessoaSolicitacaoEndereco.IdCidade = Cidade.IdCidade and Cidade.IdPais = Pais.IdPais and Cidade.IdEstado = Estado.IdEstado)
					where
					       1 $where $Limit";
		$res	=	@mysql_query($sql,$con);

		
		if(@mysql_num_rows($res) >=1){
			header ("content-type: text/xml");
			$dados	=	"<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
			$dados	.=	"\n<reg>";
		}else{
			return "false";
		}
		while($lin	=	@mysql_fetch_array($res)){
			$dados	.=	"\n<IdPessoa><![CDATA[$lin[IdPessoa]]]></IdPessoa>";
			$dados	.=	"\n<IdPessoaEndereco><![CDATA[$lin[IdPessoaEndereco]]]></IdPessoaEndereco>";
			$dados	.=	"\n<IdPais><![CDATA[$lin[IdPais]]]></IdPais>";
			$dados	.=	"\n<NomePais><![CDATA[$lin[NomePais]]]></NomePais>";
			$dados	.=	"\n<IdEstado><![CDATA[$lin[IdEstado]]]></IdEstado>";
			$dados	.=	"\n<NomeEstado><![CDATA[$lin[NomeEstado]]]></NomeEstado>";
			$dados	.=	"\n<SiglaEstado><![CDATA[$lin[SiglaEstado]]]></SiglaEstado>";
			$dados	.=	"\n<IdCidade><![CDATA[$lin[IdCidade]]]></IdCidade>";
			$dados	.=	"\n<NomeCidade><![CDATA[$lin[NomeCidade]]]></NomeCidade>";
			$dados	.=	"\n<CEP><![CDATA[$lin[CEP]]]></CEP>";
			$dados	.=	"\n<Endereco><![CDATA[$lin[Endereco]]]></Endereco>";
			$dados	.=	"\n<Complemento><![CDATA[$lin[Complemento]]]></Complemento>";
			$dados	.=	"\n<Numero><![CDATA[$lin[Numero]]]></Numero>";
			$dados	.=	"\n<Bairro><![CDATA[$lin[Bairro]]]></Bairro>";
			$dados	.=	"\n<Telefone><![CDATA[$lin[Telefone]]]></Telefone>";
			$dados	.=	"\n<Celular><![CDATA[$lin[Celular]]]></Celular>";
			$dados	.=	"\n<Fax><![CDATA[$lin[Fax]]]></Fax>";
			$dados	.=	"\n<ComplementoTelefone><![CDATA[$lin[ComplementoTelefone]]]></ComplementoTelefone>";
			$dados	.=	"\n<NomeResponsavelEndereco><![CDATA[$lin[NomeResponsavelEndereco]]]></NomeResponsavelEndereco>";
			$dados	.=	"\n<DescricaoEndereco><![CDATA[$lin[DescricaoEndereco]]]></DescricaoEndereco>";
			$dados	.=	"\n<EmailEndereco><![CDATA[$lin[EmailEndereco]]]></EmailEndereco>";
		}
		if(mysql_num_rows($res) >=1){
			$dados	.=	"\n</reg>";
			return $dados;
		}
	}
	echo get_Pessoa_Endereco();
?>
