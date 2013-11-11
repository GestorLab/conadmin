<?php
include ('../../../files/conecta.php');
include ('../../../files/funcoes.php');
include ('../../../rotinas/verifica.php');
header ('Content-type: text/html; charset=iso-8859-1');

$IdLoja = $_SESSION['IdLoja'];

if(isset($_POST['IdGrupoDevice'])){
	$IdGrupoDevice = $_POST['IdGrupoDevice'];
	$sql = "SELECT COUNT(GD.IdGrupoDevice) AS QuantidadeDeviceGrupo, GD.IdGrupoDevice, GD.DescricaoGrupoDevice FROM GrupoDevice AS GD
			INNER JOIN Device AS D ON(D.IdGrupoDevice = GD.IdGrupoDevice)
		    WHERE GD.IdLoja = $IdLoja AND GD.DisponivelContrato = 1 AND GD.IdGrupoDevice = $IdGrupoDevice GROUP BY GD.IdGrupoDevice";
	$res = mysql_query($sql);
}elseif($_POST['IdServico']){
	$IdServico = $_POST['IdServico'];
	$sql = "SELECT COUNT(GD.IdGrupoDevice) AS QuantidadeDeviceGrupo, GD.IdGrupoDevice, GD.DescricaoGrupoDevice FROM ServicoGrupoDevice AS SGD
	INNER JOIN GrupoDevice AS GD ON(SGD.IdGrupoDevice = GD.IdGrupoDevice)
	INNER JOIN Device AS D ON(D.IdGrupoDevice = GD.IdGrupoDevice)
	WHERE SGD.IdLoja = $IdLoja AND GD.DisponivelContrato = 1 AND SGD.IdServico = $IdServico GROUP BY GD.IdGrupoDevice";
	//echo $sql;die;
	$res = mysql_query($sql);
}

if(mysql_num_rows($res) > 0){
?>
	<?php 
		while($lin = @mysql_fetch_assoc($res)):
		if(isset($_POST['IdGrupoDevice'])){
			$name = "name='device[{$lin[IdGrupoDevice]}]'";
		}
	?>
			<tr class="dadosDevice">
				<td style="padding-left: 5px;" class="ServicoGrupoDevice">
					<input type="hidden" id="Id_GrupoDevice_<?php echo $lin['IdGrupoDevice'];?>" <?php echo $name;?> value="<?php echo $lin['IdGrupoDevice'];?>" />
					<?php echo $lin['IdGrupoDevice'];?>
				</td>
				<td><?php echo $lin['DescricaoGrupoDevice'];?></td>
				<td><?php echo $lin['QuantidadeDeviceGrupo'];?></td>
				<td class="bt_lista" style="cursor: pointer;">
					<img id="excluir_<?php echo $lin['IdGrupoDevice'];?>" class="excluir" src="../../img/estrutura_sistema/ico_del.gif" alt="Excluir?" />
				</td>
			</tr>
	<?php endwhile;?>
<?php 
}
?>