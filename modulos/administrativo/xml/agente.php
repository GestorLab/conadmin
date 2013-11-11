<?
	$localModulo	=	1;

	include ('../../../files/conecta.php');
	include ('../../../files/funcoes.php');
	include ('../../../rotinas/verifica.php');
	
	function get_Agente(){
		global $con;
		global $_GET;
		
		$Limit 						= $_GET['Limit'];
		$IdAgenteAutorizadoLogin	= $_SESSION['IdAgenteAutorizado'];
		$IdLoja						= $_SESSION["IdLoja"];
		$where						= "";
		$where0						= "";
		
		if($Limit != ''){		$Limit = "limit 0,$Limit";	}
		
		if($_GET['IdAgenteAutorizado'] != ''){		$IdAgenteAutorizado = $_GET['IdAgenteAutorizado'];		}
		if($_POST['IdAgenteAutorizado'] != ''){	$IdAgenteAutorizado = $_POST['IdAgenteAutorizado'];	}	
		
		$IdPais			= $_GET['IdPais'];
		$IdEstado		= $_GET['IdEstado'];
		$NomeCidade		= $_GET['NomeCidade'];
		$CPF_CNPJ		= $_GET['CPF_CNPJ'];
		$Nome			= $_GET['Nome'];	
		$IdStatus		= $_GET['IdStatus'];	
		$IdCarteira		= $_GET['IdCarteira'];
		$GroupBy		= $_GET['GroupBy'];
		
		if($Nome!=''){							$where .= " and (Pessoa.Nome like '".$_GET['Nome']."%' or Pessoa.RazaoSocial like '".$_GET['Nome']."%')";	}
		if($IdPais){							$where .= " and Pais.IdPais=$IdPais";	}
		if($NomeEstado){						$where .= " and Estado.IdEstado = '$IdEstado'";	}
		if($NomeCidade){						$where .= " and Cidade.NomeCidade like '$NomeCidade%'";	}
		if($CPF_CNPJ){							$where .= " and CPF_CNPJ like '$CPF_CNPJ%'";	}
		if($IdAgenteAutorizado	!= '')	{		$where .= " and AgenteAutorizado.IdAgenteAutorizado = $IdAgenteAutorizado"; 	}
		if($IdStatus	!= '')	{				$where .= " and AgenteAutorizado.IdStatus = $IdStatus"; 	}
		if($IdAgenteAutorizadoLogin	!= '')	{	$where .= " and AgenteAutorizado.IdAgenteAutorizado in ($IdAgenteAutorizadoLogin)"; 	}
		
		if($GroupBy != ''){
			$GroupBy = " group by ".$GroupBy; 
		}
		
		if($IdCarteira != '') {
			$where0 .= " and IdCarteira = $IdCarteira";
		}
		
		$sql = "SELECT 
					AgenteAutorizado.IdAgenteAutorizado,
					Pessoa.RazaoSocial,
					Pessoa.Nome,
					Pessoa.TipoPessoa,
					Pessoa.CPF_CNPJ,
					AgenteAutorizado.IdGrupoPessoa,
					GrupoPessoa.DescricaoGrupoPessoa,
					AgenteAutorizado.IdLocalCobranca,
					AgenteAutorizado.Restringir,
					AgenteAutorizado.IdStatus,
					AgenteAutorizado.DataCriacao,
					AgenteAutorizado.LoginCriacao,
					AgenteAutorizado.DataAlteracao,
					AgenteAutorizado.LoginAlteracao,
					Estado.SiglaEstado,
					Cidade.NomeCidade
				from
					AgenteAutorizado LEFT JOIN GrupoPessoa ON (
						AgenteAutorizado.IdGrupoPessoa = GrupoPessoa.IdGrupoPessoa
					),
					Pessoa,
					PessoaEndereco,
					Pais,
					Estado,
					Cidade
				where
					AgenteAutorizado.IdLoja = $IdLoja and
					AgenteAutorizado.IdAgenteAutorizado = Pessoa.IdPessoa and
					PessoaEndereco.IdPessoa = Pessoa.IdPessoa and
					PessoaEndereco.IdPessoaEndereco = Pessoa.IdEnderecoDefault and
					Pais.IdPais = PessoaEndereco.IdPais and
					Estado.IdEstado = PessoaEndereco.IdEstado and
					Cidade.IdCidade = PessoaEndereco.IdCidade and
					Pais.IdPais = Estado.IdPais and
					Pais.IdPais = Cidade.IdPais and
					Estado.IdEstado = Cidade.IdEstado 
					$where 
				$GroupBy
				$Limit";
		$res = mysql_query($sql,$con);
		
		if(@mysql_num_rows($res) > 0){
			header ("content-type: text/xml");
			$dados	=	"<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
			$dados	.=	"\n<reg>";
			
			while($lin = @mysql_fetch_array($res)){
				if($lin[TipoPessoa]=='1'){
					$lin[Nome]	=	$lin[trim(getCodigoInterno(3,24))];	
				}
				
				$sql0 = "select 
							ValorParametroSistema 
						from 
							ParametroSistema 
						where 
							IdGrupoParametroSistema = 91 and 
							IdParametroSistema = $lin[IdStatus]";
				$res0 = @mysql_query($sql0,$con);
				$lin0 = @mysql_fetch_array($res0);
				
				$dados	.=	"\n<IdAgenteAutorizado>$lin[IdAgenteAutorizado]</IdAgenteAutorizado>";
				$dados	.=	"\n<RazaoSocial><![CDATA[$lin[RazaoSocial]]]></RazaoSocial>";
				$dados	.=	"\n<CPF_CNPJ><![CDATA[$lin[CPF_CNPJ]]]></CPF_CNPJ>";
				$dados	.=	"\n<Nome><![CDATA[$lin[Nome]]]></Nome>";
				$dados	.=	"\n<IdStatus><![CDATA[$lin[IdStatus]]]></IdStatus>";			
				$dados	.=	"\n<Status><![CDATA[$lin0[ValorParametroSistema]]]></Status>";			
				$dados	.=	"\n<IdGrupoPessoa><![CDATA[$lin[IdGrupoPessoa]]]></IdGrupoPessoa>";
				$dados	.=	"\n<DescricaoGrupoPessoa><![CDATA[$lin[DescricaoGrupoPessoa]]]></DescricaoGrupoPessoa>";
				$dados	.=	"\n<Restringir><![CDATA[$lin[Restringir]]]></Restringir>";
				$dados	.=	"\n<IdLocalCobranca><![CDATA[$lin[IdLocalCobranca]]]></IdLocalCobranca>";
				$dados	.=	"\n<DataCriacao><![CDATA[$lin[DataCriacao]]]></DataCriacao>";
				$dados	.=	"\n<LoginCriacao><![CDATA[$lin[LoginCriacao]]]></LoginCriacao>";
				$dados	.=	"\n<DataAlteracao><![CDATA[$lin[DataAlteracao]]]></DataAlteracao>";
				$dados	.=	"\n<LoginAlteracao><![CDATA[$lin[LoginAlteracao]]]></LoginAlteracao>";
				$dados	.=	"\n<SiglaEstado><![CDATA[$lin[SiglaEstado]]]></SiglaEstado>";
				$dados	.=	"\n<NomeCidade><![CDATA[$lin[NomeCidade]]]></NomeCidade>";
				$dados	.=	"\n<Carteira>";
				$sql0 = "select
							Carteira.IdCarteira,
							Pessoa.Nome
						from
							Carteira,
							Pessoa
						where
							Carteira.IdLoja = $IdLoja and
							Carteira.IdAgenteAutorizado = $lin[IdAgenteAutorizado] and
							Carteira.IdStatus = 1 and
							Carteira.IdCarteira = Pessoa.IdPessoa 
							$where0;";
				$res0 = mysql_query($sql0,$con);
				if(@mysql_num_rows($res0) < 1) {
						$dados	.=	"\n<IdCarteira><![CDATA[]]></IdCarteira>";
						$dados	.=	"\n<NomeCarteira><![CDATA[]]></NomeCarteira>";
				} else {
					while($lin0 = @mysql_fetch_array($res0)) {
						$dados	.=	"\n<IdCarteira><![CDATA[$lin0[IdCarteira]]]></IdCarteira>";
						$dados	.=	"\n<NomeCarteira><![CDATA[$lin0[Nome]]]></NomeCarteira>";
					}
				}
				
				$dados	.=	"\n</Carteira>";
			}
			
			$dados	.=	"\n</reg>";
			return $dados;
		} else {
			return "false";
		}
	}
	
	echo get_Agente();
?>