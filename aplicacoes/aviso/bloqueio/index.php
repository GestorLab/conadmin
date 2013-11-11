<?
	include("../../../files/conecta.php");
	include("../../../files/funcoes.php");

	$UrlSistema		= getParametroSistema(6,3);
	$LayoutAvisos	= getParametroSistema(130,1);
	$Titulo			= getParametroSistema(130,2);
	$Aviso0			= getParametroSistema(131,0);
	$Aviso1			= getParametroSistema(131,1);
	$Aviso3			= getParametroSistema(131,3);
	$UrlImagem		= $UrlSistema."/img/personalizacao/logo_cab.gif";

	$Aviso[geral] = false;

	if($_GET[AvisoAll] == 2){	
		$Aviso0 = 2;
		$Aviso1 = 2;
	}

	if($UrlRedirecionamento	== ''){	$UrlRedirecionamento = getParametroSistema(130,0); }

	include("../avisos/cabecalho_simples.php");	#0
	include("../avisos/bloqueio_simples.php");	#1
	include("../avisos/avisos.php");			#3
	include("../avisos/cda.php");				#5

	$AvisoPos = array_keys($Aviso);

	for($i=0; $i<count($AvisoPos); $i++){

		$Mensagem = "";

		if($Aviso[$AvisoPos[$i]] == true){

			$Mensagem = $Msg[$AvisoPos[$i]];
			
			include("../layout/$LayoutAvisos/modelo.php");
	
			$Conteudo .= "\n".$Modelo;
		}
	}

	include("../layout/$LayoutAvisos/layout.php");
?>