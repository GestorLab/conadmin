<?
	include ('../../../files/conecta.php');
	include ('../../../files/funcoes.php');
	include ('../../../rotinas/verifica.php');
	

	$IdTipoPoste = $_GET['TipoPoste'];	

	//Info Tipo de Poste
	$sqlTipoPoste = "SELECT
							IdPosteTipo,
							DescricaoPosteTipo,
							SiglaPosteTipo,
							IdIconePosteTipo,
							LoginAlteracao,
							DataAlteracao
						FROM
							PosteTipo
						WHERE
							IdPosteTipo = $IdTipoPoste";
	$resTipoPoste 	= mysql_query($sqlTipoPoste,$con);		
	$linTipoPoste	= mysql_fetch_array($resTipoPoste);
	
	//Quantidade de Poste
	$sqlQtd 	= "SELECT 
						* 
					FROM
						Poste 
					WHERE 
						IdTipoPoste = $IdTipoPoste";
	$resQtd 	= mysql_query($sqlQtd,$con);
	$QtdPoste	= mysql_num_rows($resQtd);	
	$QtdPoste	= $QtdPoste+1;
	

	if(is_file('../img/poste'.$linTipoPoste[IdIconePosteTipo].'.gif') == false){		
		$VerificaIcone = 'false';
	}else{
		$VerificaIcone = 'true';
	}
	
	//Montar XML
	if(mysql_num_rows($resTipoPoste) > 0){	
		header ("content-type: text/xml");
		
		$dados	=	"<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
		$dados	.=	"\n<reg>";					
		$dados	.=	"\n<IdPosteTipo><![CDATA[$linTipoPoste[IdPosteTipo]]]></IdPosteTipo>";				
		$dados	.=	"\n<DescricaoPosteTipo><![CDATA[$linTipoPoste[DescricaoPosteTipo]]]></DescricaoPosteTipo>";				
		$dados	.=	"\n<SiglaPosteTipo><![CDATA[$linTipoPoste[SiglaPosteTipo]]]></SiglaPosteTipo>";		
		$dados	.=	"\n<IdIconePosteTipo><![CDATA[$linTipoPoste[IdIconePosteTipo]]]></IdIconePosteTipo>";					
		$dados	.=	"\n<VerificaIcone><![CDATA[$VerificaIcone]]></VerificaIcone>";					
		$dados	.=	"\n<QtdPoste>$QtdPoste</QtdPoste>";				
		
		if(mysql_num_rows($resTipoPoste) >=1){
			$dados	.=	"\n</reg>";					
		}
	}else{
		$dados = "false";
	}

	echo $dados;


?>