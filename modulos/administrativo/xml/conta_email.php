<?
	$localModulo	= 1;

	include('../../../files/conecta.php');
	include('../../../files/funcoes.php');
	include('../../../rotinas/verifica.php');
	
	function get_ContaEmail(){
		global $con;
		global $_GET;
		
		$local_IdLoja		= $_SESSION['IdLoja'];
		$local_IdContaEmail	= $_GET['IdContaEmail'];
		$local_Login		= $_GET['Login'];
		$where				= '';
		
		if($local_Login != 'root'){
			$where .= "and ContaEmail.IdContaEmail > 0 ";
		}
		if($local_IdContaEmail != ''){
			$where .= " and ContaEmail.IdContaEmail = $local_IdContaEmail";
		}
		
		$sql = "select 
					ContaEmail.IdContaEmail, 
					ContaEmail.DescricaoContaEmail, 
					ContaEmail.NomeRemetente, 
					ContaEmail.EmailRemetente, 
					ContaEmail.NomeResposta, 
					ContaEmail.EmailResposta, 
					ContaEmail.Usuario, 
					ContaEmail.Senha, 
					ContaEmail.ServidorSMTP, 
					ContaEmail.Porta, 
					ContaEmail.RequerAutenticacao, 
					ContaEmail.SMTPSeguro, 
					ContaEmail.IntervaloEnvio, 
					ContaEmail.QtdTentativaEnvio, 
					ContaEmail.LoginCriacao, 
					ContaEmail.DataCriacao, 
					ContaEmail.LoginAlteracao, 
					ContaEmail.DataAlteracao,
					ContaEmail.LimiteEnvioDiario
				from 
					ContaEmail
				where 
					ContaEmail.IdLoja = '$local_IdLoja'
					$where;";
		$res = mysql_query($sql,$con);
		
		if(@mysql_num_rows($res) > 0){
			header("content-type: text/xml");
			
			$dados	= "<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
			$dados .= "\n<reg>";
			
			while($lin = @mysql_fetch_array($res)){
				$dados .= "\n<IdContaEmail>$lin[IdContaEmail]</IdContaEmail>";
				$dados .= "\n<DescricaoContaEmail><![CDATA[$lin[DescricaoContaEmail]]]></DescricaoContaEmail>";
				$dados .= "\n<NomeRemetente><![CDATA[$lin[NomeRemetente]]]></NomeRemetente>";
				$dados .= "\n<EmailRemetente><![CDATA[$lin[EmailRemetente]]]></EmailRemetente>";
				$dados .= "\n<NomeResposta><![CDATA[$lin[NomeResposta]]]></NomeResposta>";
				$dados .= "\n<EmailResposta><![CDATA[$lin[EmailResposta]]]></EmailResposta>";
				$dados .= "\n<Usuario><![CDATA[$lin[Usuario]]]></Usuario>";
				$dados .= "\n<Senha><![CDATA[$lin[Senha]]]></Senha>";
				$dados .= "\n<ServidorSMTP><![CDATA[$lin[ServidorSMTP]]]></ServidorSMTP>";
				$dados .= "\n<Porta><![CDATA[$lin[Porta]]]></Porta>";
				$dados .= "\n<RequerAutenticacao><![CDATA[$lin[RequerAutenticacao]]]></RequerAutenticacao>";
				$dados .= "\n<SMTPSeguro><![CDATA[$lin[SMTPSeguro]]]></SMTPSeguro>";
				$dados .= "\n<IntervaloEnvio><![CDATA[$lin[IntervaloEnvio]]]></IntervaloEnvio>";
				$dados .= "\n<QtdTentativaEnvio><![CDATA[$lin[QtdTentativaEnvio]]]></QtdTentativaEnvio>";
				$dados .= "\n<LoginCriacao><![CDATA[$lin[LoginCriacao]]]></LoginCriacao>";
				$dados .= "\n<DataCriacao><![CDATA[$lin[DataCriacao]]]></DataCriacao>";
				$dados .= "\n<LoginAlteracao><![CDATA[$lin[LoginAlteracao]]]></LoginAlteracao>";
				$dados .= "\n<DataAlteracao><![CDATA[$lin[DataAlteracao]]]></DataAlteracao>";
				$dados .= "\n<LimiteEnvioDiario><![CDATA[$lin[LimiteEnvioDiario]]]></LimiteEnvioDiario>";
			}
			
			$dados .= "\n</reg>";
			return $dados;
		} else{
			return "false";
		}
	}	
	echo get_ContaEmail();
?>