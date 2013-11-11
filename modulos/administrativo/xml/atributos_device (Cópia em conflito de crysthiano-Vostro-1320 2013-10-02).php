<?php
include ('../../../files/conecta.php');
include ('../../../files/funcoes.php');
include ('../../../rotinas/verifica.php');

$local_IdLoja = $_SESSION['IdLoja'];

header ('Content-type: text/html; charset=iso-8859-1');

if(isset($_POST['dadosWhere'])){
	$sql = "SELECT DPF.IdDevicePerfil, DPF.IdAtributo, DPF.DescricaoAtributo, DPF.ValorDefault, IF(DPF.Obrigatorio = 1, 'obrig', '' ) AS Obrigatorio,
		    DPF.Observacao/*, DA.Valor*/ FROM DevicePerfilAtributo AS DPF
			LEFT JOIN DeviceAtributo AS DA ON(
			              DPF.IdDevicePerfil = DA.IdDevicePerfil AND DPF.IdAtributo = DA.IdAtributo
						)
			WHERE " . $_POST['dadosWhere'];
	//echo $sql;die;
	$res = mysql_query($sql, $con);
	$lin = @mysql_fetch_array($res, MYSQL_BOTH);
	$html = "<tr>
				<td style='vertical-align: top;'>
					<input type='hidden' id='IdDevicePerfil' name='dados[\"IdDevicePerfil\"]' value='&nbsp;'/>
					<input type='hidden' id='IdAtributo' name='dados[\"IdAtributo\"]' value='&nbsp;' />
					<p style='margin:0; padding-bottom:1px; padding-left:23px'><b style='color:#C10000;'>&nbsp;</b></p>
					<p style='padding-bottom:6px; padding-left:23px; margin:0;'>
						<input type='text' id='Valor' name='dados[\"valor\"]' value='&nbsp;' class='&nbsp;' style='width:399px;' onblur='Foco(this,\"out\");' onfocus='Foco(this,\"in\")' />
						<br />
						&nbsp;
					</p>
				</td>
				<td style='vertical-align: top;'>
					<input type='hidden' id='IdDevicePerfil' name='dados[\"IdDevicePerfil\"]' value='&nbsp;'/>
					<input type='hidden' id='IdAtributo' name='dados[\"IdAtributo\"]' value='&nbsp;' />
					<p style='margin:0; padding-bottom:1px; padding-left:23px'><b style='color:#C10000;'>&nbsp;</b></p>
					<p style='padding-bottom:6px; padding-left:23px; margin:0;'>
						<input type='text' id='Valor' name='dados[\"valor\"]' value='&nbsp;' class='&nbsp;' style='width:399px;' onblur='Foco(this,\"out\");' onfocus='Foco(this,\"in\")' />
						<br />
						&nbsp;
					</p>
				</td>
			</tr>";
	$patterns = '/&nbsp;/';
	$replace = '%s';
	$htmlAux =  $html;
	for($i = 0; $i < mysql_num_rows($res); $i++){
		
		for($j = 0; $j < mysql_num_fields($res); $j++){
			$htmlAux = preg_replace($patterns, $replace, $htmlAux, 1);
			if($j == 5){
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
	//echo $htmlPronto;die;
}

?>
<div id="cp_parametrosSistemas">
	<div id="cp_tit">Atributos</div>
	<table id="tabelaParametro" style="margin: 0" border='0'>
		<?php echo $htmlPronto;?>
	</table>
</div>