<?
	include("../files/conecta_conadmin.php");

	$sql = "select
				DescricaoGrupoUsuario
			from
				GrupoUsuario";
	$res = mysql_query($sql,$conCNT);
	while($lin = mysql_fetch_array($res)){
		echo $lin[DescricaoGrupoUsuario]."<br>";
	}
?>