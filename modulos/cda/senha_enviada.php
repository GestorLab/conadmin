<?
	include('../../files/conecta.php');
	include('../../files/funcoes.php');
	include('files/funcoes.php');	

	# Vars Cabeçalho **********************************
	$IdLoja					= getParametroSistema(95,6);
	# Fim Vars Cabeçalho *******************************
	
	$sair = 'disabled';
		
	if(getParametroSistema(95,2) == 1){
		$local_DescricaoEtapa	= getParametroSistema(95,10);
	}else{
		$local_DescricaoEtapa	= getParametroSistema(95,14);
	}
	$local_MSGDescricao		= getParametroSistema(95,11);
	
	$local_CPF_CNPJ			= formatText($_GET['CPF_CNPJ'],'MA');	
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
					<table width="592" border="0" cellpadding="0" cellspacing="0">
				      	<tr>
				        	<td><img src="img/marca_conadmin2.png" width="240" height="50"></td>
				        	<td width="2"><img src="img/_Espaco.gif" height="2"></td>
				        	<td>
								<table id="floatleft" width="100%" border="0" cellspacing="0" cellpadding="0">
				          			<tr>
				            			<td width="15"><img src="img/lgr1.png" width="15" height="50" /></td>
				            			<td id="titl"><h1><img src="img/ico_cadeado.png" width="24" height="33" /> <?=$local_DescricaoEtapa?></h1></td>
				            			<td width="15"><img src="img/lgr2.png" width="15" height="50" /></td>
				          			</tr>
				        		</table>
							</td>
				      	</tr>
					    <tr valign="top">
					        <td id="coluna1main" style='width:200px'>
								<div class="txtA">
					          		<!--ul id="liMais">
					            		<li>Hist&oacute;ricos Financeiro</li>
					          		</ul>
					          		<ul id="liSeta">
					          		  <li><a href="02.htm">Outros Servi&ccedil;os </a></li>
					         		</ul-->
					         		<ul id="liSeta">
					         		<?
					         			$sql	=	"select IdParametroSistema,DescricaoParametroSistema,ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema = 96 order by IdParametroSistema ASC ";
					         			$res	=	@mysql_query($sql,$con);
					         			while($lin	=	@mysql_fetch_array($res)){
					         				$sql2	=	"select ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema = 98 and IdParametroSistema = $lin[IdParametroSistema]";
										 	$res2	=	@mysql_query($sql2,$con);
										 	$lin2	=	@mysql_fetch_array($res2);
										 	
										 	if($lin2[ValorParametroSistema] == 1){
										 		echo"<li>$lin[DescricaoParametroSistema]</li>";
										 	}
										 }
									?>
									</ul>
					        	</div>
						   </td>
					       <td><img src="img/_Espaco.gif" height="2"></td>
					       <td id="colunaLmain" class="txtA" align="center">
							   <div style='width:100%; margin:0; padding:0; text-align:center'>
								   <table width="100%" border="0" cellpadding="0" cellspacing="0" id="marcaCliente" align="center">
							           <tr>
							              <td align="center" valign="middle"><img src="../../img/personalizacao/logo_cab.jpg"/></td>
							            </tr>
					          	   </table>
					          	   <BR><BR><p style='text-align:center'><?=formTexto(getParametroSistema(99,6));?></p><BR>
						           <p style='text-align:right;'><img src="img/bullet_seta_voltar.png" border="0"/> <a href="index.php">Voltar</a></p>
								</div>
							</td>
						</tr>
					    <tr>
					       <td>
						   		<table id="floatleft" width="100%" border="0" cellspacing="0" cellpadding="0">
					        	    <tr>
					            		<td width="15"><img src="img/lrp1.png" width="15" height="15" /></td>
					             		<td class="lrp"><img src="img/_Espaco.gif" /></td>
					              		<td width="15"><img src="img/lrp2.png" width="15" height="15" /></td>
					            	</tr>
					        	</table>
							</td>
					        <td><img src="img/_Espaco.gif" height="2"></td>
					        <td>
								<table id="floatleft" width="100%" border="0" cellspacing="0" cellpadding="0">
					          		<tr>
					            		<td width="15"><img src="img/lrp1.png" width="15" height="15" /></td>
					            		<td class="lrp"><img src="img/_Espaco.gif" /></td>
					            		<td width="15"><img src="img/lrp2.png" width="15" height="15" /></td>
					          		</tr>
					        	</table>
							</td>
			      		</tr>
			    	</table>
				</td>
		  	</tr>
		  	<tr>
		    	<td height="20" align="center">
	    			<table id="rpL" width="100%" border="0" cellspacing="0" cellpadding="0">
		      			<tr>
		        			<td width="20"><img src="img/rp1.png" /></td>
		        			<td class="transparente">
								<?
									include("files/rodape.php");
								?>
							</td>
		       				<td width="20"><img src="img/rp2.png" /></td>
		      			</tr>
		   			</table>
				</td>
		  	</tr>
		</table>
	</body>
</html>

