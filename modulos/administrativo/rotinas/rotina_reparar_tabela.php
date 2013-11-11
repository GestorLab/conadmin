<?
	set_time_limit(0);
	
	include("../../../files/conecta.php");
	include("../../../files/funcoes.php");

	function get_ReparaTabela(){
		global $con;
		global $_GET;

		$local_NomeTabela	= $_GET["NomeTabela"];
		/*
		$sql = "repair table $local_NomeTabela USE_FRM";
		$res = @mysql_query($sql,$con);
		@mysql_fetch_array($res);
		*/
		sleep(15);
		//header("Location: ../conteudo.php");
	}

	echo get_ReparaTabela();
?>