<?
	include ('../../files/conecta.php');
	include ('../../files/funcoes.php');
	include ('files/funcoes.php');

	$localModulo			=	0;
	$localMenu				=	true;	
	
	$local_LinkBoleto		= $_GET['LinkBoleto'];
	$local_IdContaReceber	= $_GET['IdContaReceber'];
	$local_IdLoja			= $_GET['IdLoja'];
	$local_Tipo			 	= $_GET['Tipo'];
	$local_ContaReceber 	= $_GET['ContaReceber'];
	$dataAtual				= date("Ymd");
	
	$sql = "SELECT
				DataVencimento,
				IdStatus
			FROM
				ContaReceberDados
			WHERE
				IdLoja = '$local_IdLoja' AND
				IdContaReceber = $local_IdContaReceber";
				
	$res = mysql_query($sql,$con);
	if(mysql_num_rows($res) > 0){
		$lin = mysql_fetch_array($res);
		if($dataAtual <= str_replace('-','',$lin[DataVencimento])){
			header("Location: $local_LinkBoleto?Tipo=$local_Tipo&ContaReceber=$local_ContaReceber");
		}
	}
?>
<html>
	<head>
		<?
			include ("files/header.php");
		?>
	</head>
	<body>
		<table width="100%" height="100%" border="0" cellpadding="0" cellspacing="0">
			<tr>
		    	<td align="center">
					<table width="640" border="0" cellspacing="0" cellpadding="0">
						<tr>
							<td width="15"><img src="img/hgr1.png" width="15" height="50" /></td>
							<? 
								switch($lin[IdStatus]){
									case '0':
										echo "<td id='tit' width='387'><h1>T&#237;tulo Cancelado.</h1></td>";
										break;
									case '2':
										echo "<td id='tit' width='387'><h1>T&#237;tulo Quitado.</h1></td>";
										break;
									default:
										echo "<td id='tit' width='387'><h1>T&#237;tulo Vencido.</h1></td>";
										break;
								}
							?>
							<td align="right" width="223" id="titVoltar"><img src="img/ico_voltar.png" border="0" /> <a href="index.php?ctt=index.php">P&#225;gina Inicial</a></td>
							<td width="15"><img src="img/hgr2.png" width="15" height="50" /></td>
						</tr>
					</table>
					<table width="640" border="0" cellspacing="0" cellpadding="0">
						<?
							switch($lin[IdStatus]){
								case '0':
									echo "<tr>
											<td colspan='4' class='coluna2main' style='font-size:12px; text-align:justify;'>
												<br />
													<p style='text-align:center'>Este T&#237;tulo est&#225; Cancelado.</p>
												<br />
											</td>
										</tr>";
									break;
								case '2':
									echo "<tr>
											<td colspan='4' class='coluna2main' style='font-size:12px; text-align:justify;'>
												<br />
													<p style='text-align:center'>Este T&#237;tulo est&#225; Quitado. </br>Caso queira imprimir mesmo assim, <a style='color:red' href='$local_LinkBoleto?Tipo=$local_Tipo&ContaReceber=$local_ContaReceber'>clique aqui</a>.</p>
												<br />
											</td>
										</tr>";
									break;
								default:
									echo "<tr>
											<td colspan='4' class='coluna2main' style='font-size:12px; text-align:justify;'>
												<br />
												<p style='text-align:center'>Acesse sua central do assinante para estar atualizando seu vencimento.</p>";
													if(getCodigoInternoCDA(61,1) == 1){
														echo "<p style='text-align:center'><a href='index.php?ctt=index.php'>Acessar Central do Assinante.</a></p>";
													}else{
														echo "<p style='text-align:center'><a href='$local_LinkBoleto?Tipo=$local_Tipo&ContaReceber=$local_ContaReceber'>Imprimir Boleto Vencido.</a></p>";										
													}
											echo"<br />
											</td>
										</tr>";
									break;
							}
						?>
						<tr>
							<td colspan='4'><img src="img/coluna2_rp.png" width="640" height="15" /></td>
						</tr>
					</table>
				</td>
		  	</tr>
		  	<tr>
		    	<td height="20" align="center">
	    			<table id="rpL" width="100%" cellspacing="0" cellpadding="0">
		      			<tr>
		        			<td class="borda"><img src="img/rp1.png" /></td>
		        			<td rowspan='2' class="transparente">
								<?
									include("files/rodape.php");
								?>
							</td>
		       				<td class="borda"><img src="img/rp2.png" /></td>
		      			</tr>
						<tr>
							<td class="borda-bottom">&nbsp;</td>
							<td class="borda-bottom">&nbsp;</td>
						</tr>
		   			</table>
				</td>
		  	</tr>
		</table>
	</body>
</html>