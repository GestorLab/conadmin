<?
	$localModulo	=	1;

	include ('../../../files/conecta.php');
	include ('../../../files/funcoes.php');
	include ('../../../rotinas/verifica.php');
	
	function get_carteira(){
		
		global $con;
		global $_GET;
		
		$IdLoja					= $_SESSION["IdLoja"];
		$Limit 					= $_GET['Limit'];
		$IdCarteira				= $_GET['IdCarteira'];
		$Nome					= $_GET['Nome'];
		$IdPais					= $_GET['IdPais'];
		$IdEstado				= $_GET['IdEstado'];
		$NomeCidade				= $_GET['NomeCidade'];
		$CPF_CNPJ				= $_GET['CPF_CNPJ'];
		$IdAgenteAutorizado		= $_GET['IdAgenteAutorizado'];
		$IdStatus				= $_GET['IdStatus'];
		$where					= "";
		
		if($Limit != ''){		$Limit = "limit 0,$Limit";	}
		
		if($IdCarteira != ''){			$where .= " and Carteira.IdCarteira = ".$IdCarteira;		}
		if($Nome != ''){				$where .= " and (Pessoa.Nome like '".$Nome."%' or Pessoa.RazaoSocial like '".$Nome."%')";	}
		if($IdPais!=""){				$where .= " and Pais.IdPais=$IdPais";	}
		if($IdEstado!=""){				$where .= " and Estado.IdEstado = '$IdEstado'";	}
		if($NomeCidade!=""){			$where .= " and Cidade.NomeCidade like '$NomeCidade%'";	}
		if($CPF_CNPJ!=""){				$where .= " and CPF_CNPJ like '$CPF_CNPJ%'";	}
		if($IdAgenteAutorizado!=""){	$where .= " and Carteira.IdAgenteAutorizado = '$IdAgenteAutorizado'";	}
		if($IdStatus!=""){				$where .= " and Carteira.IdStatus = '$IdStatus'";	}
		
		
		$sql	=	"SELECT  
					      Carteira.IdCarteira,
					      Pessoa.Nome,
					      Pessoa.RazaoSocial,
					      Carteira.IdAgenteAutorizado,
					      AgenteAutorizado.RazaoSocialAgenteAutorizado,
					      AgenteAutorizado.NomeAgenteAutorizado,
					      AgenteAutorizado.TipoPessoaAgenteAutorizado,
					      Pessoa.RazaoSocial,
					      Carteira.IdStatus,
					      Carteira.Restringir,
					      Carteira.DataCriacao,
					      Carteira.LoginCriacao,
					      Carteira.DataAlteracao,
					      Carteira.LoginAlteracao
					from
					    Carteira,
					    Pessoa,
					    PessoaEndereco,
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
					    Pais,
					    Estado,
					    Cidade
					where
					     Carteira.IdLoja = $IdLoja and
					     Carteira.IdLoja = AgenteAutorizado.IdLoja and
					     Carteira.IdCarteira = Pessoa.IdPessoa and
					     Pessoa.IdPessoa = PessoaEndereco.IdPessoa and
					     PessoaEndereco.IdPessoaEndereco = Pessoa.IdEnderecoDefault and
						 Pais.IdPais = PessoaEndereco.IdPais and
					     Estado.IdEstado = PessoaEndereco.IdEstado and
					     Cidade.IdCidade = PessoaEndereco.IdCidade and
					     Pais.IdPais = Estado.IdPais and
					     Pais.IdPais = Cidade.IdPais and
					     Estado.IdEstado = Cidade.IdEstado and
						 Carteira.IdAgenteAutorizado = AgenteAutorizado.IdAgenteAutorizado $where $Limit";
		$res	=	mysql_query($sql,$con);
		if(@mysql_num_rows($res) >=1){
			header ("content-type: text/xml");
			$dados	=	"<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
			$dados	.=	"\n<reg>";
		}else{
			return "false";
		}
		while($lin	=	@mysql_fetch_array($res)){
			
			if($lin[TipoPessoaAgenteAutorizado]=='1'){
				if(getCodigoInterno(3,24) == 'RazaoSocial'){
					$lin[NomeAgenteAutorizado]	=	$lin[RazaoSocialAgenteAutorizado];	
				}
			}
			
			$dados	.=	"\n<IdCarteira>$lin[IdCarteira]</IdCarteira>";
			$dados	.=	"\n<Nome><![CDATA[$lin[Nome]]]></Nome>";
			$dados	.=	"\n<RazaoSocial><![CDATA[$lin[RazaoSocial]]]></RazaoSocial>";
			$dados	.=	"\n<IdAgenteAutorizado><![CDATA[$lin[IdAgenteAutorizado]]]></IdAgenteAutorizado>";
			$dados	.=	"\n<NomeAgenteAutorizado><![CDATA[$lin[NomeAgenteAutorizado]]]></NomeAgenteAutorizado>";
			$dados	.=	"\n<IdStatus><![CDATA[$lin[IdStatus]]]></IdStatus>";
			$dados	.=	"\n<Restringir><![CDATA[$lin[Restringir]]]></Restringir>";
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
	echo get_carteira();
?>
