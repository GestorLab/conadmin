<?
	$localModulo	=	1;

	include ('../../../files/conecta.php');
	include ('../../../files/funcoes.php');
	include ('../../../rotinas/verifica.php');
	
	function get_dia_cobranca(){
		
		global $con;
		global $_GET;
		
		$IdLoja 		= $_SESSION["IdLoja"];
		$IdPessoa		= $_GET['IdPessoa'];
		$Acao			= $_GET['Acao'];
		$DiaCobranca	= "";		
		
		$sql = "select 
					ValorCodigoInterno 
				from 
					(
						select 
							convert(ValorCodigoInterno,UNSIGNED) ValorCodigoInterno 
						from 
							CodigoInterno 
						where 
							IdLoja = $IdLoja and 
							IdGrupoCodigoInterno = 1
					) CodigoInterno 
				order by 
					ValorCodigoInterno";
		$res = mysql_query($sql,$con);
		
		if(@mysql_num_rows($res) > 0){
			header ("content-type: text/xml");
			$dados	=	"<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
			$dados	.=	"\n<reg>";
			
			while($lin	=	@mysql_fetch_array($res)){
				$Contrato	=	"";	
				
				if($IdPessoa!=""){
					$sql2 ="select distinct 
								count(*) QTD 
							from 
								ContratoAtivo,
								Contrato
							where 
								ContratoAtivo.IdLoja = $IdLoja and 
								ContratoAtivo.IdPessoa = $IdPessoa and 
								ContratoAtivo.DiaCobranca = $lin[ValorCodigoInterno] and
								ContratoAtivo.IdContrato = Contrato.IdContrato and
								Contrato.IdStatus > '199';";
					$res2 = mysql_query($sql2,$con);
					$lin2 = mysql_fetch_array($res2);
					
					if($Acao == 'inserir'){
						if($lin2[QTD] >= 1 ){
							$Contrato	=	" ***";
						}
					}else{
						if($lin2[QTD] > 1 ){
							$Contrato	=	" ***";
						}
					}
				}
				
				$lin[DescricaoCodigoInterno]	 =	visualizarNumber($lin[ValorCodigoInterno]);
				$lin[DescricaoCodigoInterno]	.=	$Contrato;	
				
				$dados	.=	"\n<DiaCobranca><![CDATA[$DiaCobranca]]></DiaCobranca>";
				$dados	.=	"\n<ValorCodigoInterno><![CDATA[$lin[ValorCodigoInterno]]]></ValorCodigoInterno>";
				$dados	.=	"\n<DescricaoCodigoInterno><![CDATA[$lin[DescricaoCodigoInterno]]]></DescricaoCodigoInterno>";
			}
			
			$dados	.=	"\n</reg>";
			
			return $dados;
		} else{
			return "false";
		}
	}
	
	echo get_dia_cobranca();
?>