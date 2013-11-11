			<div id='quadroBuscaDuplicataLayout' style='width:365px;' class='quadroFlutuante'>
				<!--div class='tit'>Busca Arquivo de Retorno Tipo<div class='fecha' onClick="vi_id('quadroBuscaArquivoRetornoTipo', false);">X</div></div-->
				<table class='titMenu' cellspacing='0' cellpading='0'>
					<tr>
						<td class='tit'>Busca Duplicata Layout</td>
						<td class='fecha' onClick="vi_id('quadroBuscaDuplicataLayout', false);">X</div></td>
					</tr>
				</table>
				<div class='filtro_busca'>
					<form name='formularioDuplicataLayout' method='post'>
						<table>
							<tr>
								<td class='descCampo'>Nome Duplicata Layout</td>
							</tr>
							<tr>
								<td class='campo'>
									<input type='text' name='Nome' autocomplete="off" value='' style='width:330px' maxlength='100' onkeyup="busca_duplicata_layout_lista()" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')">
								</td>
							</tr>
						</table>
					</form>
					<div id='carregando'>carregando...</div>
					<div class='listaDados' style='height: 180px;'>
						<div id='listaDadosQuadroDuplicataLayout'>&nbsp;</div>
					</div>
					<form name='BuscaDuplicataLayout' method='post' onSubmit='return validar_busca_duplicata_layout()'>
						<input type='hidden' name='IdDuplicataLayout' value=''>
						<table class='listaDados'>
							<tr>
								<td>
									<input type='submit' value='Ok' class='botao'>
									<input type='button' value='Cancelar' onClick="vi_id('quadroBuscaDuplicataLayout', false);" class='botao'>
								</td>
							</tr>
						</table>
					</form>
					<script>
						new Draggable('quadroBuscaDuplicataLayout');
						
						var valorDuplicataLayout = '';
						function validar_busca_duplicata_layout(){
							if(valorDuplicataLayout !=''){
								busca_duplicata('',valorCampoDuplicataLayout);
							}
							return false;
						}
						function busca_duplicata_layout_lista(){
						
							var nameNode, nameTextNode, url;
						    
							url = "../administrativo/xml/duplicata_layout.php?IdDuplicataLayout=1";
							
							call_ajax(url,function (xmlhttp){
								if(xmlhttp.responseText == 'false'){
									document.getElementById('listaDadosQuadroDuplicataLayout').innerHTML = "";
									carregando(false);
								}else{
									var dados = "<table id='listaDados' style='width: 343px'>\n<tr>\n<td class='listaDados_titulo' style='width: 80px'>Duplicata</td>\n<td class='listaDados_titulo'>Descrição Duplicata Layout</td>\n</tr>";
									for(var i=0; i<xmlhttp.responseXML.getElementsByTagName("IdDuplicataLayout").length; i++){
											
										nameNode = xmlhttp.responseXML.getElementsByTagName("IdDuplicataLayout")[i]; 
										nameTextNode = nameNode.childNodes[0];
										var IdDuplicataLayout = nameTextNode.nodeValue;
										
										nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoDuplicata")[i]; 
										nameTextNode = nameNode.childNodes[0];
										var DescricaoDuplicata = nameTextNode.nodeValue;
										
										dados += "\n<tr style='background-color:#FFF' id='listaDados_td_"+IdDuplicataLayout+"' onClick='aciona_duplicata(this,"+IdDuplicataLayout+")'>";
										dados += 	"\n<td>"+IdDuplicataLayout+"</td><td>"+DescricaoDuplicata+"</td>";
										dados += "\n</tr>";
									}
									dados += "\n</table>";
									document.getElementById('listaDadosQuadroDuplicataLayout').innerHTML = dados;
								}
							});
						}
						function aciona_duplicata(campo,valor){
							if(valorDuplicataLayout!=''){
								document.getElementById('listaDados_td_'+valorDuplicataLayout).style.backgroundColor = "#FFFFFF";
							}
							if(valorDuplicataLayout == valor){
								busca_duplicata('',valor);
							}
							valorDuplicataLayout = valor;
							document.BuscaDuplicataLayout.IdDuplicataLayout.value = valorDuplicataLayout;
							campo.style.backgroundColor = '<?=getParametroSistema(15,4)?>';
						}
						enterAsTab(document.forms.formularioDuplicataLayout);	
					</script>
				</div>
			</div>
