<?php 
include ('../../files/conecta.php');
include ('../../files/funcoes.php');
include ('../../rotinas/verifica.php');

header('Content-type: text/html; charset=iso-8859-1');
//$teste = json_decode($_POST['dados']);
//print_r($_POST['dados']['IdDevicePerfil']);die;
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
if($_POST['IdDeviceMonitor']){
	$local_IdLoja = $_SESSION['IdLoja'];
	$sql = "SELECT DM.*, DPM.DescricaoMonitor, DA.DescricaoDeviceAlarme, D.IdTipoDevice, D.DescricaoDevice, DP.DescricaoPerfil FROM DeviceMonitor AS DM 
			INNER JOIN DevicePerfilMonitor AS DPM ON(DM.IdDevicePerfil = DPM.IdDevicePerfil AND DM.IdDevicePerfilMonitor = DPM.IdDevicePerfilMonitor)
			LEFT JOIN DeviceAlarme AS DA ON(DM.IdDeviceAlarme = DA.IdDeviceAlarme AND DA.IdLoja = $local_IdLoja)
			INNER JOIN Device as D ON(DM.IdDevice = D.IdDevice AND D.IdLoja = $local_IdLoja)
			INNER JOIN DevicePerfil AS DP ON(DM.IdDevicePerfil = DP.IdDevicePerfil)
		    WHERE DM.IdLoja = $local_IdLoja AND DM.IdDeviceMonitor = " . $_POST['IdDeviceMonitor'];
	$res = mysql_query($sql);
	//echo $sql;die;
	$lin2 = @mysql_fetch_assoc($res);
}
if(isset($_POST['dados'])){
	$IdDevice = $_POST['dados']['IdDevice'];
	$DescricaoDevice = $_POST['dados']['DescricaoDevice'];
}else{
	$IdDevice = $lin2['IdDevice'];
	$DescricaoDevice = $lin2['DescricaoDevice'];
	$IdDeviceMonitor = $lin2['IdDeviceMonitor'];
}
?>
<form id="form" action="files/inserir/inserir_device_monitor.php" method="post">
<!--<p><?php echo $sql;?></p>-->
	<table>
		<tr>
			<td class="find">&nbsp;</td>
			<td class="descCampo">Device</td>
			<td class="separador">&nbsp;</td>
			<td class="descCampo">Tipo</td>
			<td class="separador">&nbsp;</td>
			<td class="descCampo">Perfil</td>
			<td class="separador">&nbsp;</td>
			<td class="descCampo">Descrição</td>
		</tr>
		<tr>
			<td class="find">&nbsp;</td>
			<td class="campo">
				<input type="text" id="IdDevice" name="data[IdDevice]" value="<?php echo $IdDevice;?>" autocomplete="off" style='width:70px' maxlength='11' onkeypress="mascara(this,event,'int')" onFocus="Foco(this,'in','auto')"  onBlur="Foco(this,'out')" tabindex="4" />
			</td>
			<td class="separador">&nbsp;</td>
			<td class="campo">
				<select id="IdTipoDevice" name="IdTipoDevice" style="width: 100px;" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex="5">
					<option value=""></option>
					<?php 
						if(isset($_POST['dados'])){
							$IdTipoDevice = $_POST['dados']['IdTipoDevice'];
						
						}else{
							$IdTipoDevice = $lin2['IdTipoDevice'];
						} 
					?>
					<option value="<?php echo $IdTipoDevice;?>" selected="selected"><?=getParametroSistema(276,$IdTipoDevice);?></option>
				</select>
			</td>
			<td class="separador">&nbsp;</td>
			<td class="descCampo">
				<select id="IdDevicePerfil" name="data[IdDevicePerfil]" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex="6">
					<option value=""></option>
					<?php
					    if($lin2 != null){
					    	$IdDevicePerfil = $lin2['IdDevicePerfil'];
					    	$DescricaoPerfil = $lin2['DescricaoPerfil'];
					    }else{
					    	$sql = "SELECT IdDevicePerfil, DescricaoPerfil FROM DevicePerfil WHERE IdDevicePerfil = " . $_POST['dados']['IdDevicePerfil'];
							$res = mysql_query($sql, $con);
							$lin = @mysql_fetch_assoc($res);
							
							$IdDevicePerfil = $lin['IdDevicePerfil'];
							$DescricaoPerfil = $lin['DescricaoPerfil'];
					    }
						
					?>
					<option value="<?php echo $IdDevicePerfil;?>" selected="selected"><?php echo $DescricaoPerfil;?></option>
				</select>
			</td>
			<td class="separador">&nbsp;</td>
			<td class="descCampo">
				<input type="text" id="DescricaoDevice" name="DescricaoDevice" value="<?php echo $DescricaoDevice;?>" style='width:337px' maxlength='255' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex="7" />
			</td>
		</tr>
	</table>
	<div id="cp_tit">Monitor</div>
	<table id="tableMonitor">
		<tr>
			<td class="find">&nbsp;</td>
			<td class="descCampo"><b>Perfil Monitor</b></td>
			<td class="separador">&nbsp;</td>
			<td class="descCampo">&nbsp;</td>
			<td class="separador">&nbsp;</td>
			<td class="descCampo"><b>Operador</b></td>
			<td class="separador">&nbsp;</td>
			<td class="descCampo">&nbsp;</td>
			<td class="separador">&nbsp;</td>
			<td class="descCampo"><b>Valor Comparação</b></td>
			<td class="separador">&nbsp;</td>
			<td class="descCampo">&nbsp;</td>
			<td class="separador">&nbsp;</td>
			<td class="descCampo">Alarme</td>
			<td class="separador">&nbsp;</td>
			<td class="desCampo">&nbsp;</td>
			<td class="separador">&nbsp;</td>
			<td class="descCampo"><b>Salvar Log</b></td>
		</tr>
		<tr id="dadosMonitor">
			<td class="find">&nbsp;</td>
			<td class="campo">
				<input type="hidden" id="IdDeviceMonitor" name="data[IdDeviceMonitor]" value="<?php echo $IdDeviceMonitor;?>" />
				<select id="IdDevicePerfilMonitor" name="data[IdDevicePerfilMonitor]" class="obrig" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex="8">
					<option value=""></option>
					<?php 
						$sql = "SELECT IdDevicePerfilMonitor, DescricaoMonitor FROM DevicePerfilMonitor WHERE IdDevicePerfilMonitor > 0";
						$res = mysql_query($sql, $con);
						//$lin = @mysql_fetch_assoc($res);
						while($lin = @mysql_fetch_assoc($res)):
							if($lin['IdDevicePerfilMonitor'] == $lin2['IdDevicePerfilMonitor']){
					?>
								<option value="<?php echo $lin2['IdDevicePerfilMonitor'];?>" selected="selected"><?php echo $lin2['DescricaoMonitor'];?></option>
					<?php 
							}else{
					?>
								<option value="<?php echo $lin['IdDevicePerfilMonitor'];?>"><?php echo $lin['DescricaoMonitor'];?></option>
					<?php 			
							}
						endwhile;
					?>
				</select>
			</td>
			<td class="separador">&nbsp;</td>
			<td class="campo">
				<img style="width: 10px;" src="../../img/estrutura_sistema/setaMonitorDevice.png" />
			</td>
			<td class="separador">&nbsp;</td>
			<td class="campo">
				<select id="Operador" name="data[Operador]" class="obrig" style='width:55px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex="9">
					<option value=""></option>
					<?php 
						foreach($operador as $key => $value):
							if($key == $lin2['Operador']){
					?>
								<option value="<?php echo $key;?>" selected="selected"><?php echo $value;?></option>
					<?php
							}else{
					?>
								<option value="<?php echo $key;?>"><?php echo $value;?></option>
					<?php 
							} 
						endforeach;
					?>
				</select>
			</td>
			<td class="separador">&nbsp;</td>
			<td class="campo">
				<img style="width: 10px;" src="../../img/estrutura_sistema/setaMonitorDevice.png" />
				
			</td>
			<td class="separador">&nbsp;</td>
			<td class="campo">
				<input type="text" id="valorComparacao" name="data[ValorComparacao]" class="obrig" value="<?php echo $lin2['ValorComparacao'];?>" style='width:130px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex="10" />
			</td>
			<td class="separador">&nbsp;</td>
			<td class="campo">
				<img style="width: 10px;" src="../../img/estrutura_sistema/setaMonitorDevice.png" />
			</td>
			<td class="separador">&nbsp;</td>
			<td class="campo">
				<select id="IdDeviceAlarme" name="data[IdDeviceAlarme]" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex="11">
					<option value=""></option>
					<?php 
						$sql = "SELECT IdDeviceAlarme, DescricaoDeviceAlarme FROM DeviceAlarme WHERE IdDeviceAlarme > 0";
						$res = mysql_query($sql, $con);
						while($lin = @mysql_fetch_assoc($res)):
							if($lin['IdDeviceAlarme'] == $lin2['IdDeviceAlarme']){
					?>
								<option value="<?php echo $lin2['IdDeviceAlarme'];?>" selected="selected"><?php echo $lin2['DescricaoDeviceAlarme'];?></option>
					<?php 
							}else{
					?>
								<option value="<?php echo $lin['IdDeviceAlarme'];?>"><?php echo $lin['DescricaoDeviceAlarme'];?></option>
					<?php 							
							}
						endwhile;
					?>
				</select>
			</td>
			<td class="separador">&nbsp;</td>
			<td class="campo">
				<img style="width: 10px;" src="../../img/estrutura_sistema/setaMonitorDevice.png" />
			</td>
			<td class="separador">&nbsp;</td>
			<td class="campo">
				<select id="SalvarLog" name="data[SalvarLog]" class="obrig" style='width:57px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex="12">
					<option value=""></option>
					<?php 
						if($lin2 != null){
							foreach($salvarLog as $key => $value){
								if($lin2['SalvarLog'] == $key){
					?>
									<option value="<?php echo $key;?>" selected="selected"><?php echo $value;?></option>		
					<?php
								}else{
					?>
									<option value="<?php echo $key;?>"><?php echo $value;?></option>
					<?php 
								}
							}
						}else{
					?>
							<option value="1">Sim</option>
							<option value="2" selected="selected">Não</option>	
					<?php 
						}
					?>
				</select>
			</td>
			<td class="separador">&nbsp;</td>
			<td class="campo">
				<input type="submit" id="action_insert" name="action[action_insert]" class="botao" value="Adicionar" tabindex="13" />
				<!--<input type="reset" id="resetForm" class="botao" value="reset" />-->
			</td>
		</tr>
	</table>
</form>

<!--<div id="monitor"></div>-->