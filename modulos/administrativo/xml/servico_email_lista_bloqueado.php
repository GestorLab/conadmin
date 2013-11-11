<?
	$localModulo	=	1;

	include('../../../files/conecta.php');
	include('../../../files/funcoes.php');
	include('../../../rotinas/verifica.php');
	
	function get_email_lista_bloqueado(){
		global $con;
		global $_GET;
		
		$IdLoja				= $_SESSION["IdLoja"];
		$IdServico			= $_GET['IdServico'];
		$Where				= "";
		
		if($IdServico != '') {
			$Where .= " and ServicoMonitor.IdServico = $IdServico";	
		}
		
		$sql = "select 
					Servico.IdServico,
					Servico.EmailListaBloqueados 
				from
					Servico 
				where 
					Servico.IdLoja = $IdLoja and
					Servico.IdServico = $IdServico";
		$res = mysql_query($sql,$con);
		if(@mysql_num_rows($res) > 0) {
			header("content-type: text/xml");
			$dados	=	"<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
			$dados	.=	"\n<reg>";
			
			while($lin = @mysql_fetch_array($res)) {
				$dados	.=	"\n<IdServico>$lin[IdServico]</IdServico>";
				$dados	.=	"\n<EmailListaBloqueados><![CDATA[$lin[EmailListaBloqueados]]]></EmailListaBloqueados>";
			}
			
			$dados	.=	"\n</reg>";
			return $dados;
		} else {
			return "false";
		}
	}
	
	echo get_email_lista_bloqueado();
?>