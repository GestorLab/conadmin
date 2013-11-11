<?php
include ('../../../files/conecta.php');
include ('../../../files/funcoes.php');
include ('../../../rotinas/verifica.php');

$local_IdLoja = $_SESSION['IdLoja'];

header ('Content-type: text/html; charset=iso-8859-1');
if(!empty($_POST['dadosWhere'])){
	
	if(is_array($_POST['dadosWhere'])){
		$dadosWhere = " DPF.IdDevicePerfil = " . $_POST['dadosWhere']['IdDevicePerfil'] . " AND DA.IdDevice = " . $_POST['dadosWhere']['IdDevice'];
		$sql = "SELECT DPF.DescricaoAtributo AS NovaDesc, DPF.IdDevicePerfil, DPF.IdAtributo, IF(DPF.Obrigatorio = 1, 'color:#C10000;', IF(DPF.Obrigatorio > 1, 'color:#000000;', '&nbsp;')) AS COLOR, DPF.DescricaoAtributo,
			IF(DPF.ValorDefault = NULL, DA.Valor, IF(DA.Valor = NULL, DPF.ValorDefault, DA.Valor)) AS Valor,
			IF(DPF.Obrigatorio = 1, 'obrig', '' ) AS Obrigatorio, IF(DPF.Observacao = '', '<br />', DPF.Observacao) AS Observacao
		    FROM DevicePerfilAtributo AS DPF
			LEFT JOIN DeviceAtributo AS DA ON(
			              DPF.IdDevicePerfil = DA.IdDevicePerfil AND DPF.IdAtributo = DA.IdAtributo
						)
			WHERE " . $dadosWhere;
		//echo $sql;die;
		
	}else{
		$sql = "SELECT DescricaoAtributo AS NovaDesc, IdDevicePerfil, IdAtributo, IF(Obrigatorio = 1, 'color:#C10000;', IF(Obrigatorio > 1, 'color:#000000;', '&nbsp;')) AS COLOR, DescricaoAtributo,
			ValorDefault,
			IF(Obrigatorio = 1, 'obrig', '' ) AS Obrigatorio, IF(Observacao = '', '<br />', Observacao) AS Observacao
		    FROM DevicePerfilAtributo WHERE IdDevicePerfil = " . $_POST['dadosWhere'];
		//echo $sql;die;
	}
	mysql_set_charset("utf8", $con);
	$res = mysql_query($sql, $con);
	
	$linhasTabela = '<tr><td>%1$s</td><td>%2$s</td></tr>';
	
	$html = "<input type='hidden' name='atributos[_x][DescricaoAtributo]' value='%1\$s' />
					<input type='hidden' name='atributos[_x][IdDevicePerfil]' value='%2\$s'/>
					<input type='hidden' name='atributos[_x][IdAtributo]' value='%3\$s' />
					<p style='margin:0; padding-bottom:1px; padding-left:23px'><b style='%4\$s'>%5\$s</b></p>
					<p style='padding-bottom:6px; padding-left:23px; margin:0;'>
						<input type='text' name='atributos[_x][Valor]' value='%6\$s' class='%7\$s' style='width: 392px;' onblur='Foco(this,\"out\");' onfocus='Foco(this,\"in\");' />
						<br />
						%8\$s
					</p>";
	$htmlAux = $linhasTabela;
	$j = 1;
	$linhaHtml = array();
	$patterns = array();
	for($i = 0; $i <= mysql_num_rows($res); $i++){
		$lin = @mysql_fetch_assoc($res);
		if(strstr($htmlAux, '$s') == false || $i == mysql_num_rows($res)){
			$array[] = $htmlAux;
			$htmlAux = $linhasTabela;
			$j = 1;
		}
		ob_start();
		vprintf($html, $lin);
		$linhaHtml = ob_get_contents();
		$linhaHtml = str_replace("_x", $i, $linhaHtml);
		ob_end_clean();
		$patterns = '%'.$j.'$s';
		$htmlAux = str_replace($patterns, $linhaHtml, $htmlAux);
		$j++;
	}
	
	$linhaCel = join("", $array);
	if(strstr($linhaCel, '%2$s')){
		$linhaCel = strstr($linhaCel, '%2$s', true);
	}
?>
	<div id="cp_parametrosSistemas">
		<div id="cp_tit">Atributos</div>
		<table id="tabelaParametro" style="margin: 0">
			<?php echo $linhaCel;?>
		</table>
	</div>
<?php 
}
?>
