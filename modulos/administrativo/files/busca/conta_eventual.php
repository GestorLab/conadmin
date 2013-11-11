			<div id='quadroBuscaContaEventual' style='width:778px;' class='quadroFlutuante'>
				<table class='titMenu' cellspacing='0' cellpading='0'>
					<tr>
						<td class='tit'>Busca Conta Eventual</td>
						<td class='fecha' onClick="vi_id('quadroBuscaContaEventual', false);">X</div></td>
					</tr>
				</table>
				<div class='filtro_busca'>
					<form name='formularioContaEventual' method='post'>
						<table>
							<tr>
								<td class='descCampo'>Descrição Conta Eventual</td>
								<td class='descCampo'>Forma de Cobrança</td>
								<td class='descCampo'>Status</td>
							</tr>
							<tr>
								<td class='campo'>
									<input type='text' name='DescricaoContaEventual' autocomplete="off" value='' style='width:501px' maxlength='100' onkeyup="busca_conta_eventual_lista()" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')">
								</td>
								<td class='campo'>
									<select name='FormaCobranca' style='width:120px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onChange="busca_conta_eventual_lista()">
										<option value=''>Todos</option>
										<?
											$sql = "select IdParametroSistema, ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema=50 order by ValorParametroSistema";
											$res = @mysql_query($sql,$con);
											while($lin = @mysql_fetch_array($res)){
												echo"<option value='$lin[IdParametroSistema]'>$lin[ValorParametroSistema]</option>";
											}
										?>
									</select>
								</td>
								<td class='campo'>
									<select name='IdStatus' style='width:120px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onChange="busca_conta_eventual_lista()">
										<option value=''>Todos</option>
										<?
											$sql = "select IdParametroSistema, ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema=46 order by ValorParametroSistema";
											$res = @mysql_query($sql,$con);
											while($lin = @mysql_fetch_array($res)){
												echo"<option value='$lin[IdParametroSistema]' ".compara($local_IdStatus,$lin[IdParametroSistema],"selected", "").">$lin[ValorParametroSistema]</option>";
											}
										?>
									</select>
								</td>
							</tr>
						</table>
					</form>
					<div id='carregando'>carregando...</div>
					<div class='listaDados' style='height: 245px;'>
						<div id='listaDadosQuadroContaEventual'>&nbsp;</div>
					</div>
					<form name='BuscaContaEventual' method='post' onSubmit='return validar_busca_conta_eventual()'>
						<input type='hidden' name='IdContaEventual' value=''>
						<table class='listaDados'>
							<tr>
								<td>
									<input type='submit' value='Ok' class='botao'>
									<input type='button' value='Cancelar' onClick="vi_id('quadroBuscaContaEventual',false);" class='botao'>
								</td>
							</tr>
						</table>
					</form>
					<script type='text/javascript'>
						// Para que os quadros flutuem
						new Draggable('quadroBuscaContaEventual');
						
						var valorCampoContaEventual = '';
						
						function validar_busca_conta_eventual() {
							if(valorCampoContaEventual != '') {
								busca_conta_eventual(valorCampoContaEventual,'false',document.formulario.Local.value);
							}
							
							return false;
						}
						
						function busca_conta_eventual_lista() {
							var DescricaoContaEventual = document.formularioContaEventual.DescricaoContaEventual.value;
							var FormaCobranca = document.formularioContaEventual.FormaCobranca.value;
							var IdStatus = document.formularioContaEventual.IdStatus.value;
							var Local = document.formulario.Local.value;
							var Limit = <?=getCodigoInterno(7,4)?>;
							var url = "xml/conta_eventual.php";
							
						    if(DescricaoContaEventual == '' && FormaCobranca == '' && IdStatus=='') {
						    	url += "?Limit="+Limit;
							} else {
								url += "?DescricaoContaEventual="+DescricaoContaEventual+"&FormaCobranca="+FormaCobranca+"&IdStatus="+IdStatus;
							}
							
							if(Local == 'Protocolo') {
								var IdPessoa = document.formulario.IdPessoa.value;
								
								if(IdPessoa == "") {
									IdPessoa = document.formulario.IdPessoaF.value;
								}
								
								url += "&IdPessoa="+IdPessoa+"&IdContrato="+document.formulario.IdContrato.value+"&IdContaReceber="+document.formulario.IdContaReceber.value;
							}
							
							call_ajax(url, function (xmlhttp) {
								if(xmlhttp.responseText == 'false') {
									document.getElementById('listaDadosQuadroContaEventual').innerHTML = "";
								} else {
									var nameNode, nameTextNode, dados = "<table id='listaDados' style='width:755px;'>\n<tr>\n<td class='listaDados_titulo' style='width: 90px'>Conta Eventual</td>\n<td class='listaDados_titulo'>Descrição</td>\n<td class='listaDados_titulo' style='width:100px;'>Forma Cobrança</td>\n<td class='listaDados_titulo' style='width:60px;'>Parcelas</td>\n<td class='listaDados_titulo' style='width:80px;'>Valor (<?=getParametroSistema(5,1)?>)</td>\n<td class='listaDados_titulo' style='width:90px;'>Status</td>\n</tr>";
									
									for(var i = 0; i < xmlhttp.responseXML.getElementsByTagName("IdContaEventual").length; i++) {
										nameNode = xmlhttp.responseXML.getElementsByTagName("IdContaEventual")[i]; 
										nameTextNode = nameNode.childNodes[0];
										var IdContaEventual = nameTextNode.nodeValue;
										
										nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoContaEventual")[i]; 
										nameTextNode = nameNode.childNodes[0];
										var DescricaoContaEventual = verifica_dado(nameTextNode.nodeValue);
										
										nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoFormaCobranca")[i]; 
										nameTextNode = nameNode.childNodes[0];
										var DescricaoFormaCobranca = nameTextNode.nodeValue;
										
										nameNode = xmlhttp.responseXML.getElementsByTagName("ValorTotal")[i]; 
										nameTextNode = nameNode.childNodes[0];
										var ValorTotal = verifica_dado(nameTextNode.nodeValue);
										
										nameNode = xmlhttp.responseXML.getElementsByTagName("QtdParcela")[i]; 
										nameTextNode = nameNode.childNodes[0];
										var QtdParcela = nameTextNode.nodeValue;
										
										nameNode = xmlhttp.responseXML.getElementsByTagName("Status")[i]; 
										nameTextNode = nameNode.childNodes[0];
										var Status = nameTextNode.nodeValue;
										var DescricaoContaEventual = DescricaoContaEventual.substring(0,45);
										
										if(ValorTotal == "") {
											ValorTotal = 0;
										}
										
										dados += "\n<tr style='background-color:#FFF' id='listaDados_td_"+IdContaEventual+"' onClick='aciona_busca_conta_eventual(this,"+IdContaEventual+")'>";
										dados += 	"\n<td>"+IdContaEventual+"</td><td>"+DescricaoContaEventual+"</td><td>"+DescricaoFormaCobranca+"</td><td>"+QtdParcela+"</td><td>"+formata_float(Arredonda(ValorTotal,2),2).replace(/\./i,",")+"</td><td>"+Status+"</td>";
										dados += "\n</tr>";
									}
									
									dados += "\n</table>";
									document.getElementById('listaDadosQuadroContaEventual').innerHTML = dados;
								}
							});
						}
						
						function aciona_busca_conta_eventual(campo,valor) {
							if(valorCampoContaEventual != '') {
								document.getElementById('listaDados_td_'+valorCampoContaEventual).style.backgroundColor = "#FFFFFF";
							}
							
							if(valorCampoContaEventual == valor) {
								busca_conta_eventual(valor,false,document.formulario.Local.value);
							}
							
							valorCampoContaEventual = valor;
							document.BuscaContaEventual.IdContaEventual.value = valorCampoContaEventual;
							campo.style.backgroundColor = '<?=getParametroSistema(15,4)?>';
						}
						
						function limpa_form_conta_eventual() {
							document.formularioContaEventual.DescricaoContaEventual.value = "";
							document.formularioContaEventual.FormaCobranca.value = "";
							document.formularioContaEventual.IdStatus.value = "";
							valorCampoContaEventual = "";
						}
						
						enterAsTab(document.forms.formularioContaEventual);
					</script>
				</div>
			</div>