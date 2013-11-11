<?
	$localModulo	= 1;

	include('../../../files/conecta.php');
	include('../../../files/funcoes.php');
	include('../../../rotinas/verifica.php');
	
	function get_TipoMensagem(){
		global $con;
		global $_GET;
		
		$local_IdLoja			= $_SESSION['IdLoja'];
		$Limit					= $_GET['Limit'];
		$local_IdTipoMensagem	= $_GET['IdTipoMensagem'];
		$local_Titulo			= $_GET['Titulo'];
		$where					= '';
		
		if($local_IdTipoMensagem != ''){
			$where .= " and TipoMensagem.IdTipoMensagem = $local_IdTipoMensagem";
		}

		if($local_Titulo != ''){
			$where .= " and TipoMensagem.Titulo like '$local_Titulo%'";
		}

		if($Limit != ''){		$Limit = "limit 0,$Limit";	}
		
		$sql = "select 
					TipoMensagem.IdTipoMensagem,
					TipoMensagem.IdTemplate,
					TipoMensagem.LimiteEnvioDiario,
					TipoMensagem.DelayDisparo,
					TipoMensagem.IdContaEmail,
					TipoMensagem.IdContaSMS,
					TipoMensagem.Titulo,
					TipoMensagem.Assunto,
					TipoMensagem.Conteudo,
					TipoMensagem.Assinatura,
					TipoMensagem.IdStatus,
					TipoMensagem.DataAlteracao,
					TipoMensagem.LoginAlteracao
				from 
					TipoMensagem
				where 
					TipoMensagem.IdLoja = '$local_IdLoja'
					$where
					$Limit";
		$res = mysql_query($sql,$con);

		if(@mysql_num_rows($res) > 0){
			header("content-type: text/xml");
			
			$dados	= "<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
			$dados .= "\n<reg>";

			while($lin = @mysql_fetch_array($res)){
				$dados .= "\n<IdTipoMensagem>$lin[IdTipoMensagem]</IdTipoMensagem>";
				$dados .= "\n<IdTemplate><![CDATA[$lin[IdTemplate]]]></IdTemplate>";
				$dados .= "\n<LimiteEnvioDiario><![CDATA[$lin[LimiteEnvioDiario]]]></LimiteEnvioDiario>";
				$dados .= "\n<DelayDisparo><![CDATA[$lin[DelayDisparo]]]></DelayDisparo>";
				$dados .= "\n<IdContaEmail><![CDATA[$lin[IdContaEmail]]]></IdContaEmail>";
				$dados .= "\n<IdContaSMS><![CDATA[$lin[IdContaSMS]]]></IdContaSMS>";
				$dados .= "\n<Titulo><![CDATA[$lin[Titulo]]]></Titulo>";
				$dados .= "\n<Assunto><![CDATA[$lin[Assunto]]]></Assunto>";
				$dados .= "\n<Conteudo><![CDATA[$lin[Conteudo]]]></Conteudo>";
				$dados .= "\n<Assinatura><![CDATA[$lin[Assinatura]]]></Assinatura>";
				$dados .= "\n<IdStatus><![CDATA[$lin[IdStatus]]]></IdStatus>";
				$dados .= "\n<DataAlteracao><![CDATA[$lin[DataAlteracao]]]></DataAlteracao>";
				$dados .= "\n<LoginAlteracao><![CDATA[$lin[LoginAlteracao]]]></LoginAlteracao>";
			}

			$dados .= "\n</reg>";
			return $dados;
		} else{
			return "false";
		}
	}
	
	echo get_TipoMensagem();
?>