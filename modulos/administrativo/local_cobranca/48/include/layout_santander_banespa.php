<!DOCTYPE HTML PUBLIC '-//W3C//DTD HTML 4.0 Transitional//EN'>
<HTML>
	<HEAD>
		<TITLE><?php echo $tituloBoleto;?></TITLE>
		<META http-equiv=Content-Type content=text/html charset=ISO-8859-1>
		<style type=text/css>
			body{
				FONT: 10px Arial;
			}

			<!--.cp {  font: bold 10px Arial; color: black}
			<!--.ti {  font: 9px Arial, Helvetica, sans-serif}
			<!--.ld { font: bold 15px Arial; color: #000000}
			<!--.ct { FONT: 9px "Arial Narrow"; COLOR: #000033} 
			<!--.cn { FONT: 9px Arial; COLOR: black }
			<!--.bc { font: bold 20px Arial; color: #000000 }
			<!--.ld2 { font: bold 12px Arial; color: #000000 }
			#cabecalho, #quadro{
				width:665px; 
				border-bottom: 1px #000 solid;
			}
			#cabecalho{
				height: 45px; 
				padding: 5px;
				text-align: right;
			}
			#quadro{
				height: 360px;
				text-align:left;
				margin-top: 10px;
				font-size: 11px;
			}
			#quadro table{
				font-size: 11px;
				margin: 0 0 15px 0;
				width:665px;
			}
			#quadro table tr th, #quadro table tr td{
				border-bottom: 1px #7C8286 solid;
			}
			#quadro p{
				margin: 0;
				font-size: 12px;
			}
	--></style> 
	</head>
	<BODY text=#000000 bgColor=#ffffff topMargin=0 rightMargin=0>
		<DIV ALIGN="CENTER" ><?
			include("../cabecalho_html.php");
			include("../demonstrativo_html.php");
		?>
		<div>
		<table cellspacing=0 cellpadding=0 width=666 border=0 >
			<tr>
				<td class=cp width=150> 
					<span class="campo"><IMG src="imagens/logobanespa.jpg" border=0></span>
				</td>
				<td width=3 valign=bottom><img height=22 src=imagens/3.png width=2 border=0></td>
				<td class=cpt width=58 valign=bottom>
					<div align=center><font class=bc><?php echo $dadosboleto["codigo_banco_com_dv"]?></font></div>
				</td>
				<td width=3 valign=bottom><img height=22 src=imagens/3.png width=2 border=0></td>
				<td class=ld align=right width=453 valign=bottom>
					<span class=ld> 
						<span class="campotitulo">
							<?php echo $dadosboleto["linha_digitavel"]?>
						</span>
					</span>
				</td>
			</tr>
			<tbody>
				<tr>
					<td colspan=5><img height=2 src=imagens/2.png width=666 border=0></td>
				</tr>
			</tbody>
		</table>
		<table cellspacing=0 cellpadding=0 border=0>
			<tbody>
				<tr>	
					<td class=ct valign=top width=0 height=13><img height=13 src=imagens/1.png width=1 border=0></td>
					<td class=ct valign=top width=299 height=13>Cedente</td>
					<td class=ct valign=top width=7 height=13><img height=13 src=imagens/1.png width=1 border=0></td>
					<td class=ct valign=top width=56 height=14>Ag�ncia/C�digo do Cedente</td>
					<td class=ct valign=top width=7 height=13><img height=13 src=imagens/1.png width=1 border=0></td>
					<td class=ct valign=top width=34 height=13>Esp�cie</td>
					<td class=ct valign=top width=7 height=13><img height=13 src=imagens/1.png width=1 border=0></td>
					<td class=ct valign=top width=53 height=13>Quantidade</td>
					<td class=ct valign=top width=7 height=13><img height=13 src=imagens/1.png width=1 border=0></td>
					<td class=ct valign=top width=120 height=13>Nosso n�mero</td>
				</tr>
				<tr>
					<td class=cp valign=top width=0 height=12><img height=12 src=imagens/1.png width=1 border=0></td>
					<td class=cp valign=top width=299 height=13 style='font-size:6.5pt'> 
						<span class="campo"><?php echo $dadosboleto["cedente"]; ?></span>
					</td>
					<td class=cp valign=top width=7 height=12><img height=12 src=imagens/1.png width=1 border=0></td>
					<td class=cp valign=top width=56 height=12> 
						<span class="campo">
							<? $tmp2 = $dadosboleto["codigo_cliente"];
							 $tmp2 = substr($tmp2,0,strlen($tmp2)-1).'-'.substr($tmp2,strlen($tmp2)-1,1);
							?>

							<? echo $dadosboleto["ponto_venda"]." <img src='imagens/b.png' width=10 height=1> ".$tmp2?>
						</span>
					</td>
					<td class=cp valign=top width=7 height=12><img height=12 src=imagens/1.png width=1 border=0></td>
					<td class=cp valign=top  width=34 height=12>
						<span class="campo">
							<?php echo $dadosboleto["especie"]?>
						</span> 
					</td>
					<td class=cp valign=top width=7 height=12><img height=12 src=imagens/1.png width=1 border=0></td>
					<td class=cp valign=top  width=53 height=12>
						<span class="campo">
							<?php echo $dadosboleto["quantidade"]?>
						</span> 
					</td>
					<td class=cp valign=top width=7 height=12><img height=12 src=imagens/1.png width=1 border=0></td>
					<td class=cp valign=top align=right width=120 height=12> 
						<span class="campo">
							 <? $tmp = $dadosboleto["nosso_numero"];
							 $tmp = substr($tmp,0,strlen($tmp)-1).'-'.substr($tmp,strlen($tmp)-1,1);
							 print $tmp; ?>
						</span>
					</td>
				</tr>
				<tr>
					<td valign=top width=7 height=1><img height=1 src=imagens/2.png width=7 border=0></td>
					<td valign=top width=298 height=1><img height=1 src=imagens/2.png width=298 border=0></td>
					<td valign=top width=7 height=1><img height=1 src=imagens/2.png width=7 border=0></td>
					<td valign=top width=126 height=1><img height=1 src=imagens/2.png width=126 border=0></td>
					<td valign=top width=7 height=1><img height=1 src=imagens/2.png width=7 border=0></td>
					<td valign=top width=34 height=1><img height=1 src=imagens/2.png width=34 border=0></td>
					<td valign=top width=7 height=1><img height=1 src=imagens/2.png width=7 border=0></td>
					<td valign=top width=53 height=1><img height=1 src=imagens/2.png width=53 border=0></td>
					<td valign=top width=7 height=1><img height=1 src=imagens/2.png width=7 border=0></td>
					<td valign=top width=120 height=1><img height=1 src=imagens/2.png width=120 border=0></td>
				</tr>
			</tbody>
		</table>
		<table cellspacing=0 cellpadding=0 border=0>
			<tbody>
				<tr>
					<td class=ct valign=top width=7 height=13><img height=13 src=imagens/1.png width=1 border=0></td>
					<td class=ct valign=top colspan=3 height=13>N�mero do documento</td>
					<td class=ct valign=top width=7 height=13><img height=13 src=imagens/1.png width=1 border=0></td>
					<td class=ct valign=top width=132 height=13>CPF/CNPJ</td><td class=ct valign=top width=7 height=13><img height=13 src=imagens/1.png width=1 border=0></td>
					<td class=ct valign=top width=134 height=13>Vencimento</td><td class=ct valign=top width=7 height=13><img height=13 src=imagens/1.png width=1 border=0></td>
					<td class=ct valign=top width=180 height=13>Valor documento</td>
				</tr>
				<tr>
					<td class=cp valign=top width=7 height=12><img height=12 src=imagens/1.png width=1 border=0></td>
					<td class=cp valign=top colspan=3 height=12> 
						<span class="campo">
							<?php echo $dadosboleto["numero_documento"]?>
						</span>
					</td>
					<td class=cp valign=top width=7 height=12><img height=12 src=imagens/1.png width=1 border=0></td>
					<td class=cp valign=top width=132 height=12> 
						<span class="campo">
							<?php echo $dadosboleto["cpf_cnpj"]?>
						</span>
					</td>
					<td class=cp valign=top width=7 height=12><img height=12 src=imagens/1.png width=1 border=0></td>
					<td class=cp valign=top width=134 height=12> 
						  <span class="campo">
								<?php echo $dadosboleto["data_vencimento"]?>
						  </span>
					</td>
					<td class=cp valign=top width=7 height=12><img height=12 src=imagens/1.png width=1 border=0></td>
					<td class=cp valign=top align=right width=180 height=12> 
						<span class="campo">
							<?php echo $dadosboleto["valor_boleto"]?>
						</span>
					</td>
				</tr>
				<tr>
					<td valign=top width=7 height=1><img height=1 src=imagens/2.png width=7 border=0></td>
					<td valign=top width=113 height=1><img height=1 src=imagens/2.png width=113 border=0></td>
					<td valign=top width=7 height=1><img height=1 src=imagens/2.png width=7 border=0></td>
					<td valign=top width=72 height=1><img height=1 src=imagens/2.png width=72 border=0></td>
					<td valign=top width=7 height=1><img height=1 src=imagens/2.png width=7 border=0></td>
					<td valign=top width=132 height=1><img height=1 src=imagens/2.png width=132 border=0></td>
					<td valign=top width=7 height=1><img height=1 src=imagens/2.png width=7 border=0></td>
					<td valign=top width=134 height=1><img height=1 src=imagens/2.png width=134 border=0></td>
					<td valign=top width=7 height=1><img height=1 src=imagens/2.png width=7 border=0></td>
					<td valign=top width=180 height=1><img height=1 src=imagens/2.png width=180 border=0></td>
				</tr>
			</tbody>
		</table>
		<table cellspacing=0 cellpadding=0 border=0>
			<tbody>
				<tr>
					<td class=ct valign=top width=7 height=13><img height=13 src=imagens/1.png width=1 border=0></td>
					<td class=ct valign=top width=113 height=13>(-) Desconto / Abatimentos</td>
					<td class=ct valign=top width=7 height=13><img height=13 src=imagens/1.png width=1 border=0></td>
					<td class=ct valign=top width=112 height=13>(-) Outras dedu��es</td>
					<td class=ct valign=top width=7 height=13><img height=13 src=imagens/1.png width=1 border=0></td>
					<td class=ct valign=top width=113 height=13>(+) Mora / Multa</td>
					<td class=ct valign=top width=7 height=13><img height=13 src=imagens/1.png width=1 border=0></td>
					<td class=ct valign=top width=113 height=13>(+) Outros acr�scimos</td>
					<td class=ct valign=top width=7 height=13><img height=13 src=imagens/1.png width=1 border=0></td>
					<td class=ct valign=top width=180 height=13>(=) Valor cobrado</td>
				</tr>
				<tr>
					<td class=cp valign=top width=7 height=12><img height=12 src=imagens/1.png width=1 border=0></td>
					<td class=cp valign=top align=left width=110 height=12> 
						<span class="campo">
							<?php echo $dadosboleto["valor_desconto"]?>
						</span>
					</td>
					<td class=cp valign=top width=7 height=12><img height=12 src=imagens/1.png width=1 border=0></td>
					<td class=cp valign=top align=left width=110 height=12> 
						<span class="campo">
							<?php echo $dadosboleto["valor_outras_deducoes"]?>
						</span>
					</td>
					<td class=cp valign=top width=7 height=12><img height=12 src=imagens/1.png width=1 border=0></td>
					<td class=cp valign=top align=left width=110 height=12> 
						<span class="campo">
							<?php echo $dadosboleto["valor_mora_multa"]?>
						</span>
					</td>
					<td class=cp valign=top width=7 height=12><img height=12 src=imagens/1.png width=1 border=0></td>
					<td class=cp valign=top align=left width=110 height=12> 
						<span class="campo">
							<?php echo $dadosboleto["valor_outros_acrescimos"]?>
						</span>
					</td>
					<td class=cp valign=top width=7 height=12><img height=12 src=imagens/1.png width=1 border=0></td>
					<td class=cp valign=top align=right width=110 height=12> 
						<span class="campo">
							<?php echo $dadosboleto["valor_cobrado"]?>
						</span>
					</td>
				</tr>
				<tr>
					<td valign=top width=7 height=1><img height=1 src=imagens/2.png width=7 border=0></td>
					<td valign=top width=113 height=1><img height=1 src=imagens/2.png width=113 border=0></td>
					<td valign=top width=7 height=1><img height=1 src=imagens/2.png width=7 border=0></td>
					<td valign=top width=112 height=1><img height=1 src=imagens/2.png width=112 border=0></td>
					<td valign=top width=7 height=1><img height=1 src=imagens/2.png width=7 border=0></td>
					<td valign=top width=113 height=1><img height=1 src=imagens/2.png width=113 border=0></td>
					<td valign=top width=7 height=1><img height=1 src=imagens/2.png width=7 border=0></td>
					<td valign=top width=113 height=1><img height=1 src=imagens/2.png width=113 border=0></td>
					<td valign=top width=7 height=1><img height=1 src=imagens/2.png width=7 border=0></td>
					<td valign=top width=180 height=1><img height=1 src=imagens/2.png width=180 border=0></td>
				</tr>
			</tbody>
		</table>
		<table cellspacing=0 cellpadding=0 border=0 width=666>
			<tbody>
				<tr>
					<td class=ct width=7 height=13><img height=13 src=imagens/1.png width=1 border=0></td>
					<td class=ct   width=9 height=13>Sacado:</td>
					<td width=400  style='text-align:left' class=cp  height=12>	
						<span>
							<?php echo $dadosboleto["sacado"]?>
						</span> 
					</td>
				</tr>
				<tr>
					<td class=ct width=7 height=13><img height=13 src=imagens/1.png width=1 border=0></td>
					<td class=ct width=5 height=13></td>
					<td width=200 class=cp  height=12><? echo $dadosboleto["endereco1"] ?></td>
					<td width=100> </td>
				</tr>
				<tr>
				<td class=ct width=7 height=13><img height=13 src=imagens/1.png width=1 border=0></td>
					<td class=ct width=5 height=13></td>
					<td width=200 class=cp  height=12><? echo $dadosboleto["endereco_2"]." CEP:".$dadosboleto["cep"]?></td>
					<td width=100> </td>
				</tr>
				<tr>
					<td class=ct width=7 height=13><img height=13 src=imagens/1.png width=1 border=0></td>
					<td class=ct width=60 style='text-align:left' height=13>Sacado/Avalista: </td>
					<td width=460 class=cp  height=12>	</td>
					<td width=100> </td>
				</tr>
				<tr> 
					<td colspan=5><img src='imagens/2.png' width='100%' height=1  valign='top'></td>
				</tr>
			</tbody>
		</table>
		<table cellspacing=0 cellpadding=0 border=0>
			<tbody>
				<tr>
					<td class=ct  width=4 height=5></td>
					<td class=ct  width=567 >Corte na linha pontilhada</td>
					<td class=ct  width=7 height=5></td><td class=ct  width=88 >Autentica��o mec�nica</td>
				</tr>
			</tbody>
		</table>
		<p style='margin-top:1px'></p>
		<table cellspacing=0 cellpadding=0 width=666 border=0>
			<tr>
			<td class=ct width=666></td>
			</tr>
			<tbody>
			<tr>
			<td class=ct width=666><img height=1 src=imagens/6.png width=665 border=0></td>
			</tr>
			</tbody>
			</table>
			<div style='margin-bottom:2px'></div>
			<table cellspacing=0 cellpadding=0 width=666 border=0>
				<tr>
					<td class=cp width=150> 
						<span class="campo"><IMG src="imagens/logobanespa.jpg" border=0>
						</span>
					</td>
					<td width=3 valign=bottom><img height=22 src=imagens/3.png width=2 border=0></td>
					<td class=cpt width=58 valign=bottom>
						<div align=center><font class=bc><?php echo $dadosboleto["codigo_banco_com_dv"]?></font></div>
					</td>
					<td width=3 valign=bottom><img height=22 src=imagens/3.png width=2 border=0></td>
					<td class=ld align=right width=453 valign=bottom>
						<span class=ld> 
							<span class="campotitulo">
								<?php echo $dadosboleto["linha_digitavel"]?>
							</span>
						</span>
					</td>
				</tr>
				<tbody>
					<tr>
						<td colspan=5><img height=2 src=imagens/2.png width=666 border=0></td>
					</tr>
				</tbody>
			</table>
			<table cellspacing=0 cellpadding=0 border=0>
				<tbody>
					<tr>
						<td class=ct valign=top width=7 height=13><img height=13 src=imagens/1.png width=1 border=0></td>
						<td class=ct valign=top width=472 height=13>Local de pagamento</td>
						<td class=ct valign=top width=7 height=13><img height=13 src=imagens/1.png width=1 border=0></td>
						<td class=ct valign=top width=180 height=13>Vencimento</td>
					</tr>
					<tr>
						<td class=cp valign=top width=7 height=12><img height=12 src=imagens/1.png width=1 border=0></td>
						<td class=cp valign=top width=472 height=12><?=$dadosboleto["local_pagamento"]?></td>
						<td class=cp valign=top width=7 height=12><img height=12 src=imagens/1.png width=1 border=0></td>
						<td class=cp valign=top align=right width=180 height=12> 
						  <span class="campo">
							  <?php echo $dadosboleto["data_vencimento"]?>
						  </span>
					    </td>
					</tr>
					<tr>
						<td valign=top width=7 height=1><img height=1 src=imagens/2.png width=7 border=0></td>
						<td valign=top width=472 height=1><img height=1 src=imagens/2.png width=472 border=0></td>
						<td valign=top width=7 height=1><img height=1 src=imagens/2.png width=7 border=0></td>
						<td valign=top width=180 height=1><img height=1 src=imagens/2.png width=180 border=0></td>
					</tr>
				</tbody>
			</table>
			<table cellspacing=0 cellpadding=0 border=0>
				<tbody>
					<tr>
						<td class=ct valign=top width=7 height=13><img height=13 src=imagens/1.png width=1 border=0></td>
						<td class=ct valign=top width=472 height=13>Cedente</td>
						<td class=ct valign=top width=7 height=13><img height=13 src=imagens/1.png width=1 border=0></td>
						<td class=ct valign=top width=180 height=13>Ponto Venda / Ident. cedente</td>
					</tr>
					<tr>
						<td class=cp valign=top width=7 height=12><img height=12 src=imagens/1.png width=1 border=0></td>
						<td class=cp valign=top width=472 height=12> 
						  <span class="campo">
							  <?php echo $dadosboleto["cedente"]?>
						  </span>
					    </td>
						<td class=cp valign=top width=7 height=12><img height=12 src=imagens/1.png width=1 border=0></td>
						<td class=cp valign=top align=right width=180 height=12> 
						   <span class="campo">
							  <? $tmp2 = $dadosboleto["codigo_cliente"];
								 $tmp2 = substr($tmp2,0,strlen($tmp2)-1).'-'.substr($tmp2,strlen($tmp2)-1,1);
							  ?>

							  <? echo $dadosboleto["ponto_venda"]." <img src='imagens/b.png' width=10 height=1> ".$tmp2?>
						   </span>
						</td>
					</tr>
					<tr>
						<td valign=top width=7 height=1><img height=1 src=imagens/2.png width=7 border=0></td>
						<td valign=top width=472 height=1><img height=1 src=imagens/2.png width=472 border=0></td>
						<td valign=top width=7 height=1><img height=1 src=imagens/2.png width=7 border=0></td>
						<td valign=top width=180 height=1><img height=1 src=imagens/2.png width=180 border=0></td>
					</tr>
				</tbody>
			</table>
			<table cellspacing=0 cellpadding=0 border=0>
				<tbody>
					<tr>
						<td class=ct valign=top width=7 height=13><img height=13 src=imagens/1.png width=1 border=0></td>
						<td class=ct valign=top width=113 height=13>Data do documento</td>
						<td class=ct valign=top width=7 height=13> <img height=13 src=imagens/1.png width=1 border=0></td>
						<td class=ct valign=top width=153 height=13>N<u>o</u> documento</td>
						<td class=ct valign=top width=7 height=13> <img height=13 src=imagens/1.png width=1 border=0></td>
						<td class=ct valign=top width=62 height=13>Esp�cie doc.</td>
						<td class=ct valign=top width=7 height=13> <img height=13 src=imagens/1.png width=1 border=0></td>
						<td class=ct valign=top width=34 height=13>Aceite</td>
						<td class=ct valign=top width=7 height=13><img height=13 src=imagens/1.png width=1 border=0></td>
						<td class=ct valign=top width=82 height=13>Data processamento</td>
						<td class=ct valign=top width=7 height=13> <img height=13 src=imagens/1.png width=1 border=0></td>
						<td class=ct valign=top width=180 height=13>Nosso n�mero</td>
					</tr>
					<tr>
						<td class=cp valign=top width=7 height=12><img height=12 src=imagens/1.png width=1 border=0></td>
						<td class=cp valign=top  width=113 height=12><div align=left> 
						  <span class="campo">
							  <?php echo $dadosboleto["data_documento"]?>
						  </span>
						  </div>
						  </td>
						  <td class=cp valign=top width=7 height=12><img height=12 src=imagens/1.png width=1 border=0></td>
						  <td class=cp valign=top width=153 height=12> 
							<span class="campo">
							    <?php echo $dadosboleto["numero_documento"]?>
							</span>
						  </td>
						  <td class=cp valign=top width=7 height=12><img height=12 src=imagens/1.png width=1 border=0></td>
						  <td class=cp valign=top  width=62 height=12>
							  <div align=left>
								  <span class="campo">
							          <?php echo $dadosboleto["especie_doc"]?>
						         </span> 
				             </div>
						 </td>
						 <td class=cp valign=top width=7 height=12><img height=12 src=imagens/1.png width=1 border=0></td>
						 <td class=cp valign=top  width=34 height=12>
							 <div align=left>
								  <span class="campo">
									  <?php echo $dadosboleto["aceite"]?>
								  </span> 
							 </div>
						 </td>
						 <td class=cp valign=top width=7 height=12><img height=12 src=imagens/1.png width=1 border=0></td>
						 <td class=cp valign=top  width=82 height=12>
							<div align=left> 
							   <span class="campo">
									<?php echo $dadosboleto["data_processamento"]?>
							   </span>
						    </div>
					     </td>
					     <td class=cp valign=top width=7 height=12><img height=12 src=imagens/1.png width=1 border=0></td>
					     <td class=cp valign=top align=right width=180 height=12> 
							 <span class="campo">
								  <? echo $tmp; ?>
							 </span>
						 </td>
					</tr>
					<tr>
						<td valign=top width=7 height=1><img height=1 src=imagens/2.png width=7 border=0></td>
						<td valign=top width=113 height=1><img height=1 src=imagens/2.png width=113 border=0></td>
						<td valign=top width=7 height=1><img height=1 src=imagens/2.png width=7 border=0></td>
						<td valign=top width=153 height=1><img height=1 src=imagens/2.png width=153 border=0></td>
						<td valign=top width=7 height=1><img height=1 src=imagens/2.png width=7 border=0></td>
						<td valign=top width=62 height=1><img height=1 src=imagens/2.png width=62 border=0></td>
						<td valign=top width=7 height=1><img height=1 src=imagens/2.png width=7 border=0></td>
						<td valign=top width=34 height=1><img height=1 src=imagens/2.png width=34 border=0></td>
						<td valign=top width=7 height=1><img height=1 src=imagens/2.png width=7 border=0></td>
						<td valign=top width=82 height=1><img height=1 src=imagens/2.png width=82 border=0></td>
						<td valign=top width=7 height=1><img height=1 src=imagens/2.png width=7 border=0></td>
						<td valign=top width=180 height=1><img height=1 src=imagens/2.png width=180 border=0></td>
					</tr>
				</tbody>
			</table>
			<table cellspacing=0 cellpadding=0 border=0>
				<tbody>
					<tr> 
						<td class=ct valign=top width=7 height=13> <img height=13 src=imagens/1.png width=1 border=0></td>
						<td class=ct valign=top COLSPAN="5" height=13> Carteira</td>
						<td class=ct valign=top height=13 width=7><img height=13 src=imagens/1.png width=1 border=0></td>
						<td class=ct valign=top width=53 height=13>Esp�cie</td>
						<td class=ct valign=top height=13 width=7><img height=13 src=imagens/1.png width=1 border=0></td>
						<td class=ct valign=top width=123 height=13>Quantidade</td>
						<td class=ct valign=top height=13 width=7><img height=13 src=imagens/1.png width=1 border=0></td>
						<td class=ct valign=top width=72 height=13>Valor Documento</td>
						<td class=ct valign=top width=7 height=13><img height=13 src=imagens/1.png width=1 border=0></td>
						<td class=ct valign=top width=180 height=13>(=) Valor documento</td>
					</tr>
					<tr> 
						<td class=cp valign=top width=7 height=12><img height=12 src=imagens/1.png width=1 border=0></td>
						<td valign=top class=cp height=12 COLSPAN="5">
							<div align=left></div>    
							<div align=left> 
								<span class="campo">
									<?php echo $dadosboleto["carteira"]?>
								</span>
							</div>
						</td>
						<td class=cp valign=top width=7 height=12><img height=12 src=imagens/1.png width=1 border=0></td>
						<td class=cp valign=top  width=53>
							<div align=left>
								<span class="campo">
									<?php echo $dadosboleto["especie"]?>
								</span> 
							 </div>
						 </td>
						 <td class=cp valign=top width=7 height=12><img height=12 src=imagens/1.png width=1 border=0></td>
						 <td class=cp valign=top  width=123>
							 <span class="campo">
								<?php echo $dadosboleto["quantidade"]?>
							 </span> 
						 </td>
						 <td class=cp valign=top width=7 height=12><img height=12 src=imagens/1.png width=1 border=0></td>
						 <td class=cp valign=top  width=72> 
							   <span class="campo">
									<?php echo $dadosboleto["valor_unitario"]?>
							   </span>
						  </td>
						  <td class=cp valign=top width=7 height=12> <img height=12 src=imagens/1.png width=1 border=0></td>
						  <td class=cp valign=top align=right width=180 height=12> 
							   <span class="campo">
									<?php echo $dadosboleto["valor_boleto"]?>
							   </span>
						   </td>
					</tr>
					<tr>
						 <td valign=top width=7 height=1> <img height=1 src=imagens/2.png width=7 border=0></td>
						 <td valign=top width=7 height=1><img height=1 src=imagens/2.png width=75 border=0></td>
						 <td valign=top width=7 height=1><img height=1 src=imagens/2.png width=7 border=0></td>
						 <td valign=top width=31 height=1><img height=1 src=imagens/2.png width=31 border=0></td>
						 <td valign=top width=7 height=1><img height=1 src=imagens/2.png width=7 border=0></td>
						 <td valign=top width=83 height=1><img height=1 src=imagens/2.png width=83 border=0></td>
						 <td valign=top width=7 height=1><img height=1 src=imagens/2.png width=7 border=0></td>
						 <td valign=top width=53 height=1><img height=1 src=imagens/2.png width=53 border=0></td>
						 <td valign=top width=7 height=1><img height=1 src=imagens/2.png width=7 border=0></td>
						 <td valign=top width=123 height=1><img height=1 src=imagens/2.png width=123 border=0></td>
						 <td valign=top width=7 height=1><img height=1 src=imagens/2.png width=7 border=0></td>
						 <td valign=top width=72 height=1><img height=1 src=imagens/2.png width=72 border=0></td>
						 <td valign=top width=7 height=1><img height=1 src=imagens/2.png width=7 border=0></td>
						 <td valign=top width=180 height=1><img height=1 src=imagens/2.png width=180 border=0></td>
				    </tr>
				</tbody> 
			</table>
			<table cellspacing=0 cellpadding=0 width=666 border=0>
				<tbody>
					<tr>
						<td align=right width=9>
							<table cellspacing=0 cellpadding=0 border=0 align=left>
								<tbody> 
									<tr>
									    <td class=ct valign=top width=7 height=13><img height=13 src=imagens/1.png width=1 border=0></td>
									</tr>
									<tr> 
										<td class=cp valign=top width=7 height=12><img height=12 src=imagens/1.png width=1 border=0></td>
									</tr>
									<tr> 
										<td valign=top width=7 height=1><img height=1 src=imagens/2.png width=1 border=0></td>
									</tr>
								</tbody>
							</table>
						</td>
						<td valign=top width=600 rowspan=5><font class=ct>Instru��es (Texto de responsabilidade do cedente)</font>
							<br><span class=cp> <FONT class=campo><?InstrucoesBoletoHTML($IdContaReceber);?></FONT></span>
						</td>
						<td align=right width=188>
							<table cellspacing=0 cellpadding=0 border=0>
								<tbody>
									<tr>
										<td class=ct valign=top width=7 height=13><img height=13 src=imagens/1.png width=1 border=0></td>
										<td class=ct valign=top width=180 height=13>(-) Desconto / Abatimentos</td>
									</tr>
									<tr> 
										<td class=cp valign=top width=7 height=12><img height=12 src=imagens/1.png width=1 border=0></td>
										<td class=cp valign=top align=right width=180 height=12></td>
									</tr>
									<tr> 
										<td valign=top width=7 height=1><img height=1 src=imagens/2.png width=7 border=0></td>
										<td valign=top width=180 height=1><img height=1 src=imagens/2.png width=180 border=0></td>
									</tr>
								</tbody>
							</table>
						</td>
					</tr>
					<tr>
						<td align=right width=10> 
							<table cellspacing=0 cellpadding=0 border=0 align=left>
								<tbody>
									<tr>
										<td class=ct valign=top width=7 height=13><img height=13 src=imagens/1.png width=1 border=0></td>
									</tr>
									<tr>
										<td class=cp valign=top width=7 height=12><img height=12 src=imagens/1.png width=1 border=0></td>
									</tr>
									<tr>
										<td valign=top width=7 height=1><img height=1 src=imagens/2.png width=1 border=0></td>
									</tr>
								</tbody>
							</table>
						</td>
						<td align=right width=188>
							<table cellspacing=0 cellpadding=0 border=0>
								<tbody>
									<tr>
										<td class=ct valign=top width=7 height=13><img height=13 src=imagens/1.png width=1 border=0></td>
										<td class=ct valign=top width=180 height=13>(-) Outras dedu��es</td>
									</tr>
									<tr>
										<td class=cp valign=top width=7 height=12> <img height=12 src=imagens/1.png width=1 border=0></td>
										<td class=cp valign=top align=right width=180 height=12></td>
									</tr>
									<tr>
										<td valign=top width=7 height=1><img height=1 src=imagens/2.png width=7 border=0></td>
										<td valign=top width=180 height=1><img height=1 src=imagens/2.png width=180 border=0></td>
									</tr>
								</tbody>
							</table>
						</td>
					</tr>
					<tr>
						<td align=right width=10> 
							<table cellspacing=0 cellpadding=0 border=0 align=left>
								<tbody>
									<tr>
										<td class=ct valign=top width=7 height=13><img height=13 src=imagens/1.png width=1 border=0></td>
									</tr>
									<tr>
										<td class=cp valign=top width=7 height=12><img height=12 src=imagens/1.png width=1 border=0></td>
									</tr>
									<tr>
										<td valign=top width=7 height=1><img height=1 src=imagens/2.png width=1 border=0></td>
									</tr>
								</tbody>
							</table>
						</td>
						<td align=right width=188> 
							<table cellspacing=0 cellpadding=0 border=0>
								<tbody>
									<tr>
										<td class=ct valign=top width=7 height=13><img height=13 src=imagens/1.png width=1 border=0></td>
										<td class=ct valign=top width=180 height=13>(+) Mora / Multa</td>
									</tr>
									<tr>
										<td class=cp valign=top width=7 height=12><img height=12 src=imagens/1.png width=1 border=0></td>
										<td class=cp valign=top align=right width=180 height=12></td>
									</tr>
									<tr> 
										<td valign=top width=7 height=1> <img height=1 src=imagens/2.png width=7 border=0></td>
										<td valign=top width=180 height=1> 
										<img height=1 src=imagens/2.png width=180 border=0></td>
									</tr>
								</tbody>
							</table>
						</td>
					</tr>
					<tr>
						<td align=right width=10>
							<table cellspacing=0 cellpadding=0 border=0 align=left>
								<tbody>
									<tr> 
										<td class=ct valign=top width=7 height=13><img height=13 src=imagens/1.png width=1 border=0></td>
									</tr>
									<tr>
										<td class=cp valign=top width=7 height=12><img height=12 src=imagens/1.png width=1 border=0></td>
									</tr>
									<tr>
										<td valign=top width=7 height=1><img height=1 src=imagens/2.png width=1 border=0></td>
									</tr>
								</tbody>
							</table>
						</td>
						<td align=right width=188> 
							<table cellspacing=0 cellpadding=0 border=0>
								<tbody>
									<tr> 
										<td class=ct valign=top width=7 height=13><img height=13 src=imagens/1.png width=1 border=0></td>
										<td class=ct valign=top width=180 height=13>(+) Outros acr�scimos</td>
									</tr>
									<tr>
										 <td class=cp valign=top width=7 height=12><img height=12 src=imagens/1.png width=1 border=0></td>
										<td class=cp valign=top align=right width=180 height=12></td>
									</tr>
									<tr>
										<td valign=top width=7 height=1><img height=1 src=imagens/2.png width=7 border=0></td>
										<td valign=top width=180 height=1><img height=1 src=imagens/2.png width=180 border=0></td>
									</tr>
								</tbody>
							</table>
						</td>
					</tr>
				    <tr>
						<td align=right width=10>
							<table cellspacing=0 cellpadding=0 border=0 align=left>
								<tbody>
									<tr>
										<td class=ct valign=top width=7 height=13><img height=13 src=imagens/1.png width=1 border=0></td>
									</tr>
									<tr>
										<td class=cp valign=top width=7 height=12><img height=12 src=imagens/1.png width=1 border=0></td>
									</tr>
								</tbody>
							</table>
						</td>
						<td align=right width=188>
							<table cellspacing=0 cellpadding=0 border=0>
								<tbody>
									<tr>
										<td class=ct valign=top width=7 height=13><img height=13 src=imagens/1.png width=1 border=0></td>
										<td class=ct valign=top width=180 height=13>(=) Valor cobrado</td>
									</tr>
									<tr>
										<td class=cp valign=top width=7 height=12><img height=12 src=imagens/1.png width=1 border=0></td>
										<td class=cp valign=top align=right width=180 height=12></td>
									</tr>
								</tbody> 
							</table>
						</td>
					</tr>
				</tbody>
			</table>
			<table cellspacing=0 cellpadding=0 width=666 border=0>
				<tbody>
					<tr>
						<td valign=top width=666 height=1><img height=1 src=imagens/2.png width=666 border=0></td>
					</tr>
				</tbody>
			</table>
			<table cellspacing=0 cellpadding=0 border=0 width=666>
			<tbody>
				<tr>
					<td class=ct width=7 height=13><img height=13 src=imagens/1.png width=1 border=0></td>
					<td class=ct   width=9 height=13>Sacado:</td>
					<td width=400  style='text-align:left' class=cp  height=12>	
						<span>
							<?php echo $dadosboleto["sacado"]?>
						</span> 
					</td>
				</tr>
				<tr>
					<td class=ct width=7 height=13><img height=13 src=imagens/1.png width=1 border=0></td>
					<td class=ct width=5 height=13></td>
					<td width=300 class=cp  height=12><? echo $dadosboleto["endereco1"]?></td>
					<td width=100> </td>
				</tr>
				<tr>
					<td class=ct width=7 height=13><img height=13 src=imagens/1.png width=1 border=0></td>
					<td class=ct width=5 height=13></td>
					<td width=200 class=cp  height=12><? echo $dadosboleto["endereco_2"]." CEP: ".$dadosboleto["cep"] ?></td>
					<td width=100> </td>
				</tr>
				<tr>
					<td class=ct width=7 height=13><img height=13 src=imagens/1.png width=1 border=0></td>
					<td class=ct width=60 style='text-align:left' height=13>Sacado/Avalista: </td>
					<td width=460 class=cp  height=12>	</td>
					<td width=100> </td>
				</tr>
				<tr> 
					<td colspan=5><img src='imagens/2.png' width='100%' height=0.8  valign='top'></td>
				</tr>
			</tbody>
		</table>
			<table cellspacing=0 cellpadding=0 border=0>
				<tbody>
					<tr>
						<td valign=top width=7 height=1><img height=1 src=imagens/2.png width=7 border=0></td>
						<td valign=top width=472 height=1><img height=1 src=imagens/2.png width=472 border=0></td>
						<td valign=top width=7 height=1><img height=1 src=imagens/2.png width=7 border=0></td>
						<td valign=top width=180 height=1><img height=1 src=imagens/2.png width=180 border=0></td>
					</tr>
				</tbody>
			</table>
			<div style='margin-bottom:1.2px'></div>
			<TABLE cellSpacing=0 cellPadding=0 width=666 border=0>
				<TBODY>
					<TR>
						<TD vAlign=bottom align=left height=50><?php fbarcode($dadosboleto["codigo_barras"]); ?></TD> 
						<TD style='text-align:right'><?
							if($entra["cob_outro"] == 'S'){
								echo "<img src='../../../../img/estrutura_sistema/ico_estrela.jpg'>";
							}
							?>
						</TD>
					</tr>
				</tbody>
			</table>
			</div>
		</DIV>
	</BODY>
</HTML>