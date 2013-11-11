<?
	$localModulo	=	1;

	include ('../../../files/conecta.php');
	include ('../../../files/funcoes.php');
	include ('../../../rotinas/verifica.php');
	
	function get_Aviso(){
		
		global $con;
		global $_GET;
		
		$Limit 					= $_GET['Limit'];
		$Login					= $_SESSION["Login"];
		$local_IdLoja			= $_SESSION["IdLoja"];
		$local_IdAviso			= $_GET['IdAviso'];
		$local_TituloAviso		= $_GET['TituloAviso'];
		
		$where					=	"";
		
		if($Limit != ''){		$Limit = "limit 0,$Limit";	}
		
		if($local_IdAviso != '')	{	
			$where .= " and Aviso.IdAviso = $local_IdAviso"; 	
		}
		if($local_TituloAviso!=''){			
			$where .= " and Aviso.TituloAviso like'".$local_TituloAviso."%'";
		}
		
		$sql	=	"select
						IdAviso, 
						DataExpiracao,
						TituloAviso,
						Aviso,
						ResumoAviso,
						IdAvisoForma,
						Aviso.IdGrupoPessoa,
						GrupoPessoa.DescricaoGrupoPessoa,
						Aviso.IdPessoa,
						Pessoa.Nome,
						Pessoa.RazaoSocial,
						Pessoa.TipoPessoa,
						Pessoa.CPF_CNPJ,
						Pessoa.Email,
						Aviso.IdServico,
						ParametroContrato,
						Servico.DescricaoServico,
						Servico.IdTipoServico,
						Aviso.IdGrupoUsuario,
						Aviso.Usuario,
						Aviso.DataCriacao,
						Aviso.LoginCriacao,
						Aviso.DataAlteracao,
						Aviso.LoginAlteracao
					from 
						Aviso 
							LEFT JOIN GrupoPessoa ON (Aviso.IdGrupoPessoa = GrupoPessoa.IdGrupoPessoa)
							LEFT JOIN Pessoa ON (Aviso.IdPessoa = Pessoa.IdPessoa)
							LEFT JOIN Servico ON (Aviso.IdLoja = $local_IdLoja and Aviso.IdServico = Servico.IdServico)
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
			
			$dados	.=	"\n<IdAviso>$lin[IdAviso]</IdAviso>";
			$dados	.=	"\n<DataExpiracao><![CDATA[$lin[DataExpiracao]]]></DataExpiracao>";
			$dados	.=	"\n<TituloAviso><![CDATA[$lin[TituloAviso]]]></TituloAviso>";
			$dados	.=	"\n<Aviso><![CDATA[$lin[Aviso]]]></Aviso>";
			$dados	.=  "\n<ResumoAviso><![CDATA[$lin[ResumoAviso]]]></ResumoAviso>";
			$dados	.=	"\n<IdAvisoForma><![CDATA[$lin[IdAvisoForma]]]></IdAvisoForma>";
			$dados	.=	"\n<IdGrupoPessoa><![CDATA[$lin[IdGrupoPessoa]]]></IdGrupoPessoa>";
			$dados	.=	"\n<DescricaoGrupoPessoa><![CDATA[$lin[DescricaoGrupoPessoa]]]></DescricaoGrupoPessoa>";
			$dados	.=	"\n<IdPessoa><![CDATA[$lin[IdPessoa]]]></IdPessoa>";
			$dados	.=	"\n<Nome><![CDATA[$lin[Nome]]]></Nome>";
			$dados	.=	"\n<RazaoSocial><![CDATA[$lin[RazaoSocial]]]></RazaoSocial>";
			$dados	.=	"\n<TipoPessoa><![CDATA[$lin[TipoPessoa]]]></TipoPessoa>";
			$dados	.=	"\n<Email><![CDATA[$lin[Email]]]></Email>";
			$dados	.=	"\n<CPF_CNPJ><![CDATA[$lin[CPF_CNPJ]]]></CPF_CNPJ>";
			$dados	.=	"\n<IdServico><![CDATA[$lin[IdServico]]]></IdServico>";
			$dados	.=	"\n<ParametroContrato><![CDATA[$lin[ParametroContrato]]]></ParametroContrato>";
			$dados	.=	"\n<DescricaoServico><![CDATA[$lin[DescricaoServico]]]></DescricaoServico>";
			$dados	.=	"\n<IdTipoServico><![CDATA[$lin[IdTipoServico]]]></IdTipoServico>";
			$dados	.=	"\n<IdGrupoUsuario><![CDATA[$lin[IdGrupoUsuario]]]></IdGrupoUsuario>";
			$dados	.=	"\n<Usuario><![CDATA[$lin[Usuario]]]></Usuario>";
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
	echo get_Aviso();
?>