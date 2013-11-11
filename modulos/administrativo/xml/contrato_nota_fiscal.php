<?
	$localModulo	=	1;

	include('../../../files/conecta.php');
	include('../../../files/funcoes.php');
	include('../../../rotinas/verifica.php');
	
	function get_contrato_nota_fiscal(){
		global $con;
		global $_GET;
		
		$IdLoja		= $_SESSION["IdLoja"];
		$IdContrato	= $_GET['IdContrato'];
		$where		= "";
		
		if($IdContrato != ''){
			$where .= " and Contrato.IdContrato = $IdContrato";
		}
		
		$sql = "SELECT
					Contrato.IdContrato,
					Servico.IdServico,
					NotaFiscalTipo.IdNotaFiscalTipo,
					NotaFiscalLayout.DescricaoNotaFiscalLayout
				FROM 
					Contrato,
					LocalCobranca,
					Servico,
					NotaFiscalTipo,
					NotaFiscalLayout
				WHERE 
					Contrato.IdLoja = $IdLoja AND 
					Contrato.NotaFiscalCDA = 1 AND 
					Contrato.IdLoja = LocalCobranca.IdLoja AND 
					Contrato.IdLocalCobranca = LocalCobranca.IdLocalCobranca AND 
					Contrato.IdServico = Servico.IdServico AND 
					NotaFiscalTipo.IdNotaFiscalTipo = CASE 
						WHEN Servico.IdNotaFiscalTipo IS NULL THEN 
							LocalCobranca.IdNotaFiscalTipo 
						ELSE 
							Servico.IdNotaFiscalTipo 
						END AND 
					NotaFiscalTipo.IdNotaFiscalLayout = NotaFiscalLayout.IdNotaFiscalLayout
					$where;";
		$res = @mysql_query($sql, $con);
		if(@mysql_num_rows($res) >= 1){
			header("content-type: text/xml");
			
			$dados	= "<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
			$dados .= "\n<reg>";
		} else{
			return "false";
		}
		
		while($lin = @mysql_fetch_array($res)){
			$dados .= "\n<IdContrato>$lin[IdContrato]</IdContrato>";
			$dados .= "\n<IdServico>$lin[IdServico]</IdServico>";
			$dados .= "\n<IdNotaFiscalTipo>$lin[IdNotaFiscalTipo]</IdNotaFiscalTipo>";
			$dados .= "\n<DescricaoNotaFiscalLayout><![CDATA[$lin[DescricaoNotaFiscalLayout]]]></DescricaoNotaFiscalLayout>";
		}
		
		if(@mysql_num_rows($res) >= 1){
			$dados .= "\n</reg>";
			
			return $dados;
		}
	}
	
	echo get_contrato_nota_fiscal();
?>