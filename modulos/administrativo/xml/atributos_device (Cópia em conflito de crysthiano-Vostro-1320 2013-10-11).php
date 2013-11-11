<?php
include ('../../../files/conecta.php');
include ('../../../files/funcoes.php');
include ('../../../rotinas/verifica.php');

$local_IdLoja = $_SESSION['IdLoja'];

header ('Content-type: text/html; charset=iso-8859-1');
//echo $_POST['dadosWhere'];die;
if(!empty($_POST['dadosWhere'])){
	
	if(is_array($_POST['dadosWhere'])){
		$dadosWhere = " DPF.IdDevicePerfil = " . $_POST['dadosWhere']['IdDevicePerfil'] . " AND DA.IdDevice = " . $_POST['dadosWhere']['IdDevice'];
		$sql = "SELECT DPF.IdDevicePerfil, DPF.IdAtributo, IF(DPF.Obrigatorio = 1, 'color:#C10000;', 'color:#000000;') AS COLOR, DPF.DescricaoAtributo,
			IF(DPF.ValorDefault = NULL, DA.Valor, IF(DA.Valor = NULL, DPF.ValorDefault, DA.Valor)) AS Valor,
			IF(DPF.Obrigatorio = 1, 'obrig', '' ) AS Obrigatorio, IFNULL(DPF.Observacao, '<br />') AS Observacao
		    FROM DevicePerfilAtributo AS DPF
			LEFT JOIN DeviceAtributo AS DA ON(
			              DPF.IdDevicePerfil = DA.IdDevicePerfil AND DPF.IdAtributo = DA.IdAtributo
						)
			WHERE " . $dadosWhere;
		//echo $sql;die;
		
	}else{
		$sql = "SELECT IdDevicePerfil, IdAtributo, IF(Obrigatorio = 1, 'color:#C10000;', 'color:#000000;') AS COLOR, DescricaoAtributo,
			ValorDefault,
			IF(Obrigatorio = 1, 'obrig', '' ) AS Obrigatorio, IFNULL(Observacao, '<br />') AS Observacao
		    FROM DevicePerfilAtributo WHERE IdDevicePerfil = " . $_POST['dadosWhere'];
		//echo $sql;die;
	}
	//echo "akiii";
	$res = mysql_query($sql, $con);
	$lin = @mysql_fetch_array($res, MYSQL_BOTH);
	$html = "<tr>
				<td>
					<input type='hidden' name='atributos[_x][IdDevicePerfil]' value='&nbsp;'/>
					<input type='hidden' name='atributos[_x][IdAtributo]' value='&nbsp;' />
					<p style='margin:0; padding-bottom:1px; padding-left:23px'><b style='&nbsp;'>&nbsp;</b></p>
					<p style='padding-bottom:6px; padding-left:23px; margin:0;'>
						<input type='text' name='atributos[_x][Valor]' value='&nbsp;' class='&nbsp;' style='width: 392px;' onblur='Foco(this,\"out\");' onfocus='Foco(this,\"in\");' />
						<br />
						&nbsp;
					</p>
				</td>
				<td>
					<input type='hidden' name='atributos[_x][IdDevicePerfil]' value='&nbsp;'/>
					<input type='hidden' name='atributos[_x][IdAtributo]' value='&nbsp;' />
					<p style='margin:0; padding-bottom:1px; padding-left:23px'><b style='&nbsp;'>&nbsp;</b></p>
					<p style='padding-bottom:6px; padding-left:23px; margin:0;'>
						<input type='text' name='atributos[_x][Valor]' value='&nbsp;' class='&nbsp;' style='width: 392px;' onblur='Foco(this,\"out\");' onfocus='Foco(this,\"in\");' />
						<br />
						&nbsp;
					</p>
				</td>
			</tr>";
	$patterns[0] = '/&nbsp;/';
	$patterns[1] = '/_x/';
	$replace[0] = '%s';
	
	$htmlAux =  $html;
	$cont = 0;
	$replace[1] = $cont;
	for($i = 0; $i < mysql_num_rows($res); $i++){
		for($j = 0; $j < mysql_num_fields($res); $j++){
			
			$htmlAux = preg_replace($patterns, $replace, $htmlAux, 1);
			if($j == 2){
				$replace[1]++;
			}
			if($j == 6){
				if($i%2 != 0){
					$htmlPronto = sprintf($htmlAux, $lin[$j]);
					$htmlAux =  $html;
				}
				else{
					$htmlPronto .= sprintf($htmlAux, $lin[$j]);
					$htmlAux =  $htmlPronto;
				}
					
			}else{
				$htmlAux = sprintf($htmlAux, $lin[$j]);
			}
		}
		$lin = @mysql_fetch_array($res, MYSQL_BOTH);
	}
?>
	<div id="cp_parametrosSistemas">
		<div id="cp_tit">Atributos</div>
		<table id="tabelaParametro" style="margin: 0">
			<?php echo $htmlPronto?>
		</table>
	</div>
<?php 
}
?>
