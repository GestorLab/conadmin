<?
	$localModulo	=	1;

	include ('../../../files/conecta.php');
	include ('../../../files/funcoes.php');
	include ('../../../rotinas/verifica.php');
	
	function get_Nota_Fiscal_Conta_Receber(){
		
		global $con;
		global $_GET;
		
		$IdLoja		 					= $_SESSION["IdLoja"];		
		$IdContaReceber					= $_GET['IdContaReceber'];
		$CancelarNotaFiscal				= $_GET['CancelarNotaFiscal'];
		
		if($CancelarNotaFiscal == 1){
			$sqlCancelarNotaFiscal = "UPDATE 
										NotaFiscal 
									SET
										IdStatus = 0 
									WHERE 
										IdLoja = $IdLoja 
										AND IdContaReceber = $IdContaReceber";
			if(mysql_query($sqlCancelarNotaFiscal,$con)){
				return "Cancelado";
			}
		}
		$cont	=	0;	
		$sql	=	"SELECT 
						IdContaReceber,
						IdStatus
					FROM
						NotaFiscal 
					WHERE 
						IdLoja = $IdLoja 
						AND	IdContaReceber = $IdContaReceber
						AND IdStatus = 1";
		$res	=	@mysql_query($sql,$con);
		if(@mysql_num_rows($res) >=1){
			header ("content-type: text/xml");
			$dados	=	"<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
			$dados	.=	"\n<reg>";
		}else{
			return "false";
		}
		while($lin	=	@mysql_fetch_array($res)){			
			$dados	.=	"\n<IdContaReceber>$lin[IdContaReceber]</IdContaReceber>";
			$dados	.=	"\n<IdStatus>$lin[IdStatus]</IdStatus>";
			$dados	.=	"\n<Alerta>Conta a Receber já possui Nota Fiscal.\n\nDeseja cancelar a atual e gerar uma nova?</Alerta>";
			$dados	.=	"\n</reg>";
			return $dados;
		
		}
	}
	echo get_Nota_Fiscal_Conta_Receber();
?>
