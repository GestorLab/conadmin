<?
	$localModulo	=	0;

	include ('../../../files/conecta.php');
	include ('../../../files/funcoes.php');
	include ('../../../rotinas/verifica.php');
	
	function get_Pessoa_Solicitacao(){
		
		global $con;
		global $_GET;
		
		$Limit 					= $_GET['Limit'];
		$IdLoja	 				= $_SESSION['IdLoja'];
		$IdPessoaSolicitacao	= $_GET['IdPessoaSolicitacao'];
		$IdPessoa 				= $_GET['IdPessoa'];
		$where					= "";
		
		if($Limit != ''){		$Limit = "limit 0,$Limit";	}
		
		if($IdPessoaSolicitacao != ''){		$where .= " and PessoaSolicitacao.IdPessoaSolicitacao=$IdPessoaSolicitacao";	}
		if($IdPessoa != ''){				$where .= " and PessoaSolicitacao.IdPessoa=$IdPessoa";	}
			
		$sql	=	"select 
					       PessoaSolicitacao.IdPessoaSolicitacao,
						   PessoaSolicitacao.IdPessoa,					
					       Pessoa.TipoPessoa,
						   PessoaSolicitacao.Nome,	
						   PessoaSolicitacao.NomeRepresentante,					
					       PessoaSolicitacao.RazaoSocial,
						   PessoaSolicitacao.DataNascimento,			
					       PessoaSolicitacao.Sexo,
						   PessoaSolicitacao.RG_IE,
						   Pessoa.CPF_CNPJ,					
					       PessoaSolicitacao.EstadoCivil,			
					       PessoaSolicitacao.InscricaoMunicipal,
						   PessoaSolicitacao.Telefone1,
						   PessoaSolicitacao.Telefone2,
						   PessoaSolicitacao.Telefone3,					
					       PessoaSolicitacao.Celular,
						   PessoaSolicitacao.Fax,	
						   PessoaSolicitacao.ComplementoTelefone,					
					       PessoaSolicitacao.Email,
						   PessoaSolicitacao.Site,						
					       PessoaSolicitacao.CampoExtra1,
					       PessoaSolicitacao.CampoExtra2,
					       PessoaSolicitacao.CampoExtra3,
					       PessoaSolicitacao.CampoExtra4,
						   PessoaSolicitacao.DataCriacao,
						   PessoaSolicitacao.LoginCriacao,
						   PessoaSolicitacao.LoginAprovacao,
						   PessoaSolicitacao.DataAprovacao,
						   PessoaSolicitacao.IdStatus,
						   LogAcessoCDA.IP,
						   PessoaSolicitacao.IdEnderecoDefault
					from 
						   PessoaSolicitacao,
						   LogAcessoCDA,
						   Pessoa
					where
					       Pessoa.IdPessoa =  PessoaSolicitacao.IdPessoa and 
						   PessoaSolicitacao.IdLogAcesso = LogAcessoCDA.IdLogAcesso $where $Limit";
		$res	=	@mysql_query($sql,$con);		
		if(@mysql_num_rows($res) >=1){
			header ("content-type: text/xml");
			$dados	=	"<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
			$dados	.=	"\n<reg>";
		}else{
			return "false";
		}
		while($lin	=	@mysql_fetch_array($res)){
			$sql2	=	"select ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema= 124 and IdParametroSistema=$lin[IdStatus]";
			$res2	=	@mysql_query($sql2,$con);
			$lin2	=	@mysql_fetch_array($res2);
			
			$sql3	=	"select count(*) QTD from PessoaSolicitacaoEndereco where IdPessoa=$lin[IdPessoa] and IdPessoaSolicitacao=$lin[IdPessoaSolicitacao]";
			$res3	=	mysql_query($sql3,$con);
			$lin3	=	mysql_fetch_array($res3);
	    	
	    	$dados	.=	"\n<IdPessoaSolicitacao>$lin[IdPessoaSolicitacao]</IdPessoaSolicitacao>";
	    	$dados	.=	"\n<IdPessoa>$lin[IdPessoa]</IdPessoa>";
			$dados	.=	"\n<TipoPessoa>$lin[TipoPessoa]</TipoPessoa>";
			$dados	.=	"\n<Nome><![CDATA[$lin[Nome]]]></Nome>";
			$dados	.=	"\n<NomeRepresentante><![CDATA[$lin[NomeRepresentante]]]></NomeRepresentante>";
			$dados	.=	"\n<RazaoSocial><![CDATA[$lin[RazaoSocial]]]></RazaoSocial>";
			$dados	.=	"\n<DataNascimento><![CDATA[$lin[DataNascimento]]]></DataNascimento>";
			$dados	.=	"\n<Sexo><![CDATA[$lin[Sexo]]]></Sexo>";
			$dados	.=	"\n<RG_IE><![CDATA[$lin[RG_IE]]]></RG_IE>";
			$dados	.=	"\n<CPF_CNPJ><![CDATA[$lin[CPF_CNPJ]]]></CPF_CNPJ>";
			$dados	.=	"\n<EstadoCivil><![CDATA[$lin[EstadoCivil]]]></EstadoCivil>";
			$dados	.=	"\n<InscricaoMunicipal><![CDATA[$lin[InscricaoMunicipal]]]></InscricaoMunicipal>";
			$dados	.=	"\n<Telefone1><![CDATA[$lin[Telefone1]]]></Telefone1>";
			$dados	.=	"\n<Telefone2><![CDATA[$lin[Telefone2]]]></Telefone2>";
			$dados	.=	"\n<Telefone3><![CDATA[$lin[Telefone3]]]></Telefone3>";
			$dados	.=	"\n<Celular><![CDATA[$lin[Celular]]]></Celular>";
			$dados	.=	"\n<Fax><![CDATA[$lin[Fax]]]></Fax>";
			$dados	.=	"\n<ComplementoTelefone><![CDATA[$lin[ComplementoTelefone]]]></ComplementoTelefone>";
			$dados	.=	"\n<Email><![CDATA[$lin[Email]]]></Email>";
			$dados	.=	"\n<Site><![CDATA[$lin[Site]]]></Site>";
			$dados	.=	"\n<CampoExtra1><![CDATA[$lin[CampoExtra1]]]></CampoExtra1>";
			$dados	.=	"\n<CampoExtra2><![CDATA[$lin[CampoExtra2]]]></CampoExtra2>";
			$dados	.=	"\n<CampoExtra3><![CDATA[$lin[CampoExtra3]]]></CampoExtra3>";
			$dados	.=	"\n<CampoExtra4><![CDATA[$lin[CampoExtra4]]]></CampoExtra4>";
			$dados	.=	"\n<DataCriacao><![CDATA[$lin[DataCriacao]]]></DataCriacao>";
			$dados	.=	"\n<LoginCriacao><![CDATA[$lin[LoginCriacao]]]></LoginCriacao>";
			$dados	.=	"\n<LoginAprovacao><![CDATA[$lin[LoginAprovacao]]]></LoginAprovacao>";
			$dados	.=	"\n<DataAprovacao><![CDATA[$lin[DataAprovacao]]]></DataAprovacao>";
			$dados	.=	"\n<Cor><![CDATA[$Color]]></Cor>";
			$dados	.=	"\n<DescricaoStatus><![CDATA[$lin2[ValorParametroSistema]]]></DescricaoStatus>";
			$dados	.=	"\n<IdStatus><![CDATA[$lin[IdStatus]]]></IdStatus>";
			$dados	.=	"\n<IP><![CDATA[$lin[IP]]]></IP>";
			$dados	.=	"\n<IdEnderecoDefault><![CDATA[$lin[IdEnderecoDefault]]]></IdEnderecoDefault>";
			$dados	.=	"\n<QtdEndereco><![CDATA[$lin3[QTD]]]></QtdEndereco>";
		}
		if(mysql_num_rows($res) >=1){
			$dados	.=	"\n</reg>";
			return $dados;
		}
	}
	echo get_Pessoa_Solicitacao();
?>
