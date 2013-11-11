<?
	if(file_exists("personalizado/index.php") || file_exists("personalizado/index.html") || file_exists("personalizado/index.htm")){
		header("Location: personalizado");
	}else{
		include("../../files/conecta.php");
		include("../../files/funcoes.php");

		// Variáveis
		$IdPessoa = $_GET['IdPessoa'];

		if($IdPessoa == ''){
			header("Location: bloqueio/index.php");
		}else{
			$UrlRedirecionamento	= $_GET[UrlRedirecionamento];

			$UrlSistema				= getParametroSistema(6,3);
			$Moeda					= getParametroSistema(5,1);
			$LayoutAvisos			= getParametroSistema(130,1);
			$Titulo					= getParametroSistema(130,2);
			$Aviso0					= getParametroSistema(131,0);
			$Aviso1					= getParametroSistema(131,1);
			$Aviso2					= getParametroSistema(131,2);
			$Aviso3					= getParametroSistema(131,3);
			$Aviso4					= getParametroSistema(131,4);

			$Aviso[geral] = false;

			if($_GET[AvisoAll] == 2){	
				$Aviso0 = 2;
				$Aviso1 = 2;
				$Aviso2 = 2;
				$Aviso3 = 2;
				$Aviso4 = 2;
			}

			if($_GET[Aviso0] != ''){	$Aviso0 = $_GET[Aviso0];	}
			if($_GET[Aviso1] != ''){	$Aviso1 = $_GET[Aviso1];	}
			if($_GET[Aviso2] != ''){	$Aviso2 = $_GET[Aviso2];	}
			if($_GET[Aviso3] != ''){	$Aviso3 = $_GET[Aviso3];	}
			if($_GET[Aviso4] != ''){	$Aviso4 = $_GET[Aviso4];	}

			if($UrlRedirecionamento	== ''){	$UrlRedirecionamento = getParametroSistema(130,0); }

			include("avisos/cabecalho.php");				#0
			include("avisos/aniversario.php");				#6
			include("avisos/bloqueio.php");					#1
			include("avisos/debito.php");					#2
			include("avisos/avisos.php");					#3
			@include("avisos/avisos_personalizado.php");	#7
			include("avisos/acessar_internet.php");			#4
			include("avisos/cda.php");						#5

			include("mostra_avisos.php");
		}
	}
?>