<?php
include ('../../../files/conecta.php');
include ('../../../files/funcoes.php');
include ('../../../rotinas/verifica.php');
header ('Content-type: text/html; charset=iso-8859-1');

if($_POST['IdDevice'] != ""){
	$IdDevice = $_POST['IdDevice'];
	$IdLoja = $_SESSION['IdLoja'];
	$sql = "SELECT * FROM DevicePorta
			WHERE IdLoja = $IdLoja 
	        AND IdDevice = $IdDevice
			AND Disponivel = 1";
	//echo $sql;die;
	$res = mysql_query($sql);
	
	while($lin = @mysql_fetch_assoc($res)){
?>
		<option value="<?php echo $lin['IdDevicePorta'];?>">porta <?php echo $lin['IdDevicePorta'];?> - <?php echo $lin['DescricaoDevicePorta'];?></option>
<?php 		
	}
}
?>