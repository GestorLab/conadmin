<?
	$localModulo	= 1;

	include('../../../files/conecta.php');
	include('../../../files/funcoes.php');
	include('../../../rotinas/verifica.php');
	
	function get_MalaDiretaEmail(){
		global $con;
		global $_GET;
		
		$local_IdLoja		= $_SESSION['IdLoja'];
		$local_IdMalaDireta	= $_GET['IdMalaDireta'];
		
		if($local_IdMalaDireta != ''){
			$where .= " and MalaDireta.IdMalaDireta = $local_IdMalaDireta";
		}
		
		$sql = "select 
					MalaDireta.IdMalaDireta, 
					MalaDireta.IdTipoMensagem, 
					MalaDireta.IdTipoConteudo,
					MalaDiretaEmail.Email,
					Pessoa.IdPessoa,
					Pessoa.Nome,
					Pessoa.RazaoSocial
				from 
					MalaDireta,
					MalaDiretaEmail left join Pessoa on (
						MalaDiretaEmail.IdPessoa = Pessoa.IdPessoa 
					)
				where 
					MalaDireta.IdLoja = '$local_IdLoja' and
					MalaDireta.IdLoja = MalaDiretaEmail.IdLoja and
					MalaDireta.IdMalaDireta = MalaDiretaEmail.IdMalaDireta 
					$where
				order by
					Pessoa.Nome,
					Pessoa.RazaoSocial,
					MalaDiretaEmail.Email;";
		$res = mysql_query($sql,$con);
		
		if(@mysql_num_rows($res) > 0){
			header("content-type: text/xml");
			
			$dados	= "<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
			$dados .= "\n<reg>";
			
			while($lin = @mysql_fetch_array($res)){
				$dados .= "\n<IdMalaDireta>$lin[IdMalaDireta]</IdMalaDireta>";
				$dados .= "\n<IdTipoMensagem><![CDATA[$lin[IdTipoMensagem]]]></IdTipoMensagem>";
				$dados .= "\n<IdTipoConteudo><![CDATA[$lin[IdTipoConteudo]]]></IdTipoConteudo>";
				$dados .= "\n<IdPessoa><![CDATA[$lin[IdPessoa]]]></IdPessoa>";
				$dados .= "\n<Nome><![CDATA[$lin[Nome]]]></Nome>";
				$dados .= "\n<RazaoSocial><![CDATA[$lin[RazaoSocial]]]></RazaoSocial>";
				$dados .= "\n<Email><![CDATA[$lin[Email]]]></Email>";
			}
			
			$dados .= "\n</reg>";
			return $dados;
		} else{
			return "false";
		}
	}
	
	echo get_MalaDiretaEmail();
?>