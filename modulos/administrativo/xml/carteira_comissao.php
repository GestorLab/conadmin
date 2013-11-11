<?
	$localModulo	=	1;

	include ('../../../files/conecta.php');
	include ('../../../files/funcoes.php');
	include ('../../../rotinas/verifica.php');
	
	function get_Agente_Comissao(){
		global $con;
		global $_GET;
		
		$Limit 				= $_GET['Limit'];
		$IdLoja				= $_SESSION["IdLoja"];
		$IdAgenteAutorizado	= $_GET['IdAgenteAutorizado'];
		$IdServico			= $_GET['IdServico'];
		$Parcela			= $_GET['Parcela'];
		$where	=	"";
		
		if($Limit != ''){
			$Limit = "limit 0,$Limit";
		}
		
		if($IdAgenteAutorizado != ''){
			$where .= " and ComissionamentoCarteira.IdAgenteAutorizado = $IdAgenteAutorizado";
		}
		
		if($IdServico != ''){
			$where .= " and ComissionamentoCarteira.IdServico=$IdServico";
		}
		
		if($Parcela != ''){
			$where .= " and ComissionamentoCarteira.Parcela = '$Parcela'";
		}
		
		$sql = "SELECT  
					ComissionamentoCarteira.IdAgenteAutorizado,
					AgenteAutorizado.NomeAgenteAutorizado,
					ComissionamentoCarteira.IdCarteira,
					Carteira.NomeCarteira,
					ComissionamentoCarteira.IdServico,
					Servico.DescricaoServico,
					ComissionamentoCarteira.Parcela,
					ComissionamentoCarteira.Percentual,
					ComissionamentoCarteira.DataCriacao,
					ComissionamentoCarteira.LoginCriacao,
					ComissionamentoCarteira.DataAlteracao,
					ComissionamentoCarteira.LoginAlteracao
				from
					ComissionamentoCarteira,
					(
						select 
							AgenteAutorizado.IdLoja,
							AgenteAutorizado.IdAgenteAutorizado,
							Pessoa.RazaoSocial 
							NomeAgenteAutorizado 
						from 
							Pessoa,
							AgenteAutorizado 
						where 
							Pessoa.IdPessoa = AgenteAutorizado.IdAgenteAutorizado
					) AgenteAutorizado,
					(
						select 
							Carteira.IdLoja,
							Carteira.IdCarteira,
							Pessoa.Nome NomeCarteira 
						from 
							Pessoa,
							Carteira 
						where 
							Pessoa.IdPessoa = Carteira.IdCarteira
					) Carteira,
					Servico
				where
					ComissionamentoCarteira.IdLoja = $IdLoja and
					ComissionamentoCarteira.IdLoja = Servico.IdLoja and
					ComissionamentoCarteira.IdLoja = AgenteAutorizado.IdLoja and
					ComissionamentoCarteira.IdLoja = Carteira.IdLoja and
					ComissionamentoCarteira.IdAgenteAutorizado = AgenteAutorizado.IdAgenteAutorizado and
					ComissionamentoCarteira.IdCarteira = Carteira.IdCarteira and
					ComissionamentoCarteira.IdServico = Servico.IdServico 
					$where 
				$Limit";
		$res = mysql_query($sql,$con);
		if(@mysql_num_rows($res) > 0){
			header("content-type: text/xml");
			$dados	=	"<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
			$dados	.=	"\n<reg>";
			
			while($lin = @mysql_fetch_array($res)){
				$dados	.=	"\n<IdAgenteAutorizado>$lin[IdAgenteAutorizado]</IdAgenteAutorizado>";
				$dados	.=	"\n<Nome><![CDATA[$lin[NomeAgenteAutorizado]]]></Nome>";
				$dados	.=	"\n<IdCarteira>$lin[IdCarteira]</IdCarteira>";
				$dados	.=	"\n<NomeCarteira><![CDATA[$lin[NomeCarteira]]]></NomeCarteira>";
				$dados	.=	"\n<IdServico><![CDATA[$lin[IdServico]]]></IdServico>";
				$dados	.=	"\n<DescricaoServico><![CDATA[$lin[DescricaoServico]]]></DescricaoServico>";
				$dados	.=	"\n<Parcela><![CDATA[$lin[Parcela]]]></Parcela>";
				$dados	.=	"\n<Percentual><![CDATA[$lin[Percentual]]]></Percentual>";
				$dados	.=	"\n<DataCriacao><![CDATA[$lin[DataCriacao]]]></DataCriacao>";
				$dados	.=	"\n<LoginCriacao><![CDATA[$lin[LoginCriacao]]]></LoginCriacao>";
				$dados	.=	"\n<DataAlteracao><![CDATA[$lin[DataAlteracao]]]></DataAlteracao>";
				$dados	.=	"\n<LoginAlteracao><![CDATA[$lin[LoginAlteracao]]]></LoginAlteracao>";
			}
			
			$dados	.=	"\n</reg>";
			return $dados;
		} else{
			return "false";
		}
	}
	
	echo get_Agente_Comissao();
?>