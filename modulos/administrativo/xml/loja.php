<?
	$localModulo	=	0;

	include ('../../../files/conecta.php');
	include ('../../../files/funcoes.php');
	include ('../../../rotinas/verifica.php');
	
	function get_Loja(){
		
		global $con;
		global $_GET;
		
		$Limit 			= $_GET['Limit'];
		$IdLoja	 		= $_SESSION['IdLoja'];
		$where			= "";
		
		if($Limit != ''){		$Limit = "limit 0,$Limit";	}
		
		if($IdLoja != ''){				$where .= " and Loja.IdLoja=$IdLoja";	}
			
		$sql	=	"select 
					       Loja.IdLoja,
					       Loja.DescricaoLoja,
						   Pessoa.IdPessoa,
						   Pessoa.IdEnderecoDefault					
					 from 
						   Loja 
						   		LEFT JOIN Pessoa ON (Loja.IdPessoa = Pessoa.IdPessoa) 
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
			
			$sql3	=	"select 
							IdPessoaEndereco,
							Endereco,
							CEP,
							Numero,
							Bairro,
							PessoaEndereco.IdPais,
							Pais.NomePais,
							PessoaEndereco.IdEstado,
							Estado.NomeEstado,
							Estado.SiglaEstado,
							PessoaEndereco.IdCidade,
							Cidade.NomeCidade,
							Complemento,
							Telefone,
							Celular,
							EmailEndereco,
							NomeResponsavelEndereco
						from
							PessoaEndereco LEFT JOIN Pais ON (PessoaEndereco.IdPais = Pais.IdPais) LEFT JOIN Estado ON (PessoaEndereco.IdPais = Pais.IdPais and PessoaEndereco.IdEstado = Estado.IdEstado) LEFT JOIN Cidade ON (PessoaEndereco.IdPais = Pais.IdPais and PessoaEndereco.IdEstado = Estado.IdEstado and PessoaEndereco.IdCidade = Cidade.IdCidade and Pais.IdPais = Estado.IdPais and Estado.IdEstado = Cidade.IdEstado)
						where 
							IdPessoa = $lin[IdPessoa] and
							IdPessoaEndereco = $lin[IdEnderecoDefault]";
			$res3	=	mysql_query($sql3,$con);
			$lin3	=	mysql_fetch_array($res3);
			
			
			$dados	.=	"\n<IdLoja>$lin[IdLoja]</IdLoja>";
			$dados	.=	"\n<DescricaoLoja><![CDATA[$lin[DescricaoLoja]]]></DescricaoLoja>";
			$dados	.=	"\n<IdPessoa><![CDATA[$lin3[IdPessoa]]]></IdPessoa>";
			$dados	.=	"\n<NomePais><![CDATA[$lin3[NomePais]]]></NomePais>";
			$dados	.=	"\n<NomeEstado><![CDATA[$lin3[NomeEstado]]]></NomeEstado>";
			$dados	.=	"\n<SiglaEstado><![CDATA[$lin3[SiglaEstado]]]></SiglaEstado>";
			$dados	.=	"\n<NomeCidade><![CDATA[$lin3[NomeCidade]]]></NomeCidade>";
			$dados	.=	"\n<CEP><![CDATA[$lin3[CEP]]]></CEP>";
			$dados	.=	"\n<Endereco><![CDATA[$lin3[Endereco]]]></Endereco>";
			$dados	.=	"\n<Complemento><![CDATA[$lin3[Complemento]]]></Complemento>";
			$dados	.=	"\n<Numero><![CDATA[$lin3[Numero]]]></Numero>";
			$dados	.=	"\n<Bairro><![CDATA[$lin3[Bairro]]]></Bairro>";
		}
		if(mysql_num_rows($res) >=1){
			$dados	.=	"\n</reg>";
			return $dados;
		}
	}
	echo get_Loja();
?>
