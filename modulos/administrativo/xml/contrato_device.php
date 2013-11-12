<?php
include ('../../../files/conecta.php');
include ('../../../files/funcoes.php');
include ('../../../rotinas/verifica.php');
header ('Content-type: text/html; charset=iso-8859-1');

if($_POST['IdServico'] != ""){
	$IdServico = $_POST['IdServico'];
	$IdLoja = $_SESSION['IdLoja'];
	$sql = "SELECT D.IdDevice, D.DescricaoDevice FROM ServicoGrupoDevice AS SGD
			INNER JOIN Device AS D ON(SGD.IdGrupoDevice = D.IdGrupoDevice)
			WHERE SGD.IdLoja = $IdLoja 
			AND SGD.IdServico = $IdServico";
	//echo $sql;die;
	$res = mysql_query($sql);
	
	while($lin = @mysql_fetch_assoc($res)){
?>
		<option value="<?php echo $lin['IdDevice'];?>"><?php echo $lin['DescricaoDevice'];?></option>
<?php 		
	}
}
?>



