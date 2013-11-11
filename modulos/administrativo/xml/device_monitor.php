<?php

$localModulo	=	1;

include ('../../../files/conecta.php');
include ('../../../files/funcoes.php');
include ('../../../rotinas/verifica.php');
header ('Content-type: text/html; charset=iso-8859-1');

$local_IdLoja = $_SESSION['IdLoja'];

$operador = array(
		1 => '=',
		2 => '!=',
		3 => '>',
		4 => '<',
		5 => '>=',
		6 => '<='
);
$salvarLog = array(
		1 => 'Sim',
		2 => 'Não'
);

if(isset($_POST['dados'])){
	$IdDevice = ' AND DM.IdDevice = ' . $_POST['dados']['IdDevice'];
}else{
	$IdDevice = ' AND DM.IdDevice > 0';
}

//$sql = "SELECT * FROM DeviceMonitor WHERE IdDeviceMonitor > 0";
$sql = "SELECT DM.*, DPM.DescricaoMonitor, DA.DescricaoDeviceAlarme, D.IdTipoDevice, D.DescricaoDevice, DP.DescricaoPerfil FROM DeviceMonitor AS DM 
			INNER JOIN DevicePerfilMonitor AS DPM ON(DM.IdDevicePerfil = DPM.IdDevicePerfil AND DM.IdDevicePerfilMonitor = DPM.IdDevicePerfilMonitor)
			LEFT JOIN DeviceAlarme AS DA ON(DM.IdDeviceAlarme = DA.IdDeviceAlarme AND DA.IdLoja = $local_IdLoja)
			INNER JOIN Device as D ON(DM.IdDevice = D.IdDevice AND D.IdLoja = $local_IdLoja)
			INNER JOIN DevicePerfil AS DP ON(DM.IdDevicePerfil = DP.IdDevicePerfil)
		    WHERE DM.IdLoja = $local_IdLoja AND DM.IdDeviceMonitor > 0" . $IdDevice;
//echo $sql;die;
mysql_set_charset("utf8", $con);
$res = mysql_query($sql);
//$lin = @mysql_fetch_array($res);

if(mysql_num_rows($res) > 0){
?>

<table class="tableListarCad" cellspacing="0">
	<tr class="tableListarTitleCad">
		<td class="TableListarEspaco">Perfil Monitor</td>
		<td>Operador</td>
		<td>Valor Comparação</td>
		<td>Alarme</td>
		<td>Salvar Log</td>
		<td></td>
	</tr>
	<?php 
		$count = 0;
		while($lin = @mysql_fetch_assoc($res)):
			if($count%2 != 0){
				$styleColor = "style='cursor: pointer; background-color: rgb(226, 231, 237)'"; 
			}else{
				$styleColor = "style='cursor: pointer;'";
			}
			$count++;
	?>
			<tr <?php echo $styleColor;?>>
				<td>
					<input type="hidden" name="IdDeviceMonitor" value="<?php echo $lin['IdDeviceMonitor'];?>" />
					<a class="result"><?php echo $lin['DescricaoPerfil'];?></a>
				</td>
				<td><a class="result"><?php echo $operador[$lin['Operador']];?></a></td>
				<td><a class="result"><?php echo $lin['ValorComparacao'];?></a></td>
				<td><a class="result"><?php echo $lin['DescricaoDeviceAlarme'];?></a></td>
				<td><a class="result"><?php echo $salvarLog[$lin['SalvarLog']];?></a></td>
				<td class="bt_lista"><img id="excluir_2" class="excluir" src="../../img/estrutura_sistema/ico_del.gif" alt="Excluir?" /></td>
			</tr>
	<?php endwhile;?>
	<tr class="tableListarTitleCad">
		<td class="tableListarEspaco" colspan="6">Total: <?php echo mysql_num_rows($res);?></td>
	</tr>
</table>
<?php }?>