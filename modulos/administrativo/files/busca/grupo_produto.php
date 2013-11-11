			<div id='quadroBuscaGrupoProduto' style='width:365px;' class='quadroFlutuante'>	
				<!--div class='tit'>Busca Grupo Produto<div class='fecha' onClick="vi_id('quadroBuscaGrupoProduto', false);">X</div></div-->
				<table class='titMenu' cellspacing='0' cellpading='0'>
					<tr>
						<td class='tit'>Busca Grupo Produto</td>
						<td class='fecha' onClick="vi_id('quadroBuscaGrupoProduto', false);">X</div></td>
					</tr>
				</table>
				<div id='filtro_busca'>
					<form name='formularioGrupoProduto' method='post'>
						<table>
							<tr>
								<td class='descCampo'>Nome Grupo Produto</td>
							</tr>
							<tr>
								<td class='campo'>
									<input type='text' name='DescricaoGrupoProduto' autocomplete="off" value='' style='width:330px' maxlength='100' onkeyup="busca_grupo_produto_lista()" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='1'>
								</td>
							</tr>
						</table>
					</form>
					<div id='carregando'>carregando...</div>
					<div class='listaDados' style='height: 180px;'>
						<div id='listaDadosQuadroGrupoProduto'>&nbsp;</div>
					</div>
					<form name='BuscaGrupoProduto' method='post' onSubmit='return validar_busca_grupo_produto()'>
						<input type='hidden' name='IdGrupoProduto' value=''>
						<table>
							<tr>
								<td>
									<input type='submit' value='Ok' class='botao'>
									<input type='button' value='Cancelar' onClick="vi_id('quadroBuscaGrupoProduto', false);" class='botao'>
								</td>
							</tr>
						</table>
					</form>
					<script>
						// Para que os quadros flutuem
						new Draggable('quadroBuscaGrupoProduto');
						
						var valorCampoGrupoProduto = '';
						function validar_busca_grupo_produto(){
							if(valorCampoGrupoProduto !=''){
								busca_grupo_produto(valorCampoGrupoProduto);	
							}
							return false;
						}
						function busca_grupo_produto_lista(){
							var DescricaoGrupoProduto	= document.formularioGrupoProduto.DescricaoGrupoProduto.value;
							var Limit	  				= <?=getCodigoInterno(7,4)?>;
							var nameNode, nameTextNode, url;
							
							var xmlhttp   = false;
							if (window.XMLHttpRequest) { // Mozilla, Safari,...
						    	xmlhttp = new XMLHttpRequest();
						        if(xmlhttp.overrideMimeType){
						        	xmlhttp.overrideMimeType('text/xml');
								}
							}else if (window.ActiveXObject){ // IE
								try{
									xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
								}catch(e){
									try{
										xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
						            } catch (e) {}
						        }
						    }
						    
						    if(DescricaoGrupoProduto == ''){
						    	url = "xml/grupo_produto.php?Limit="+Limit;
							}else{
								url = "xml/grupo_produto.php?DescricaoGrupoProduto="+DescricaoGrupoProduto;
							}
							xmlhttp.open("GET", url,true);
							
							xmlhttp.onreadystatechange = function(){ 
					
								// Carregando...
								carregando(true);
					
								if(xmlhttp.readyState == 4){ 
									if(xmlhttp.status == 200){
										if(xmlhttp.responseText == 'false'){
											document.getElementById('listaDadosQuadroGrupoProduto').innerHTML = "";
											// Fim de Carregando
											carregando(false);
										}else{
											var dados = "<table id='listaDados' style='width: 343px'>\n<tr>\n<td class='listaDados_titulo' style='width: 70px'>Grupo Prod.</td>\n<td class='listaDados_titulo'>Nome Grupo Produto</td>\n</tr>";
											for(var i=0; i<xmlhttp.responseXML.getElementsByTagName("IdGrupoProduto").length; i++){
													
												nameNode = xmlhttp.responseXML.getElementsByTagName("IdGrupoProduto")[i]; 
												nameTextNode = nameNode.childNodes[0];
												var IdGrupoProduto = nameTextNode.nodeValue;
												
												nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoGrupoProduto")[i]; 
												nameTextNode = nameNode.childNodes[0];
												var DescricaoGrupoProduto = verifica_dado(nameTextNode.nodeValue);
												
												DescricaoGrupoProduto = DescricaoGrupoProduto.substr(0,30);
												
												dados += "\n<tr style='background-color:#FFF' id='listaDados_td_"+IdGrupoProduto+"' onClick='aciona_busca_grupo_produto(this,"+IdGrupoProduto+")'>";
												dados += 	"\n<td>"+IdGrupoProduto+"</td><td>"+DescricaoGrupoProduto+"</td>";
												dados += "\n</tr>";
											}
											dados += "\n</table>";
											document.getElementById('listaDadosQuadroGrupoProduto').innerHTML = dados;
										}
									}
								} 
								// Fim de Carregando
								carregando(false);
							}
							xmlhttp.send(null);
						}
						function aciona_busca_grupo_produto(campo,valor){
							if(valorCampoGrupoProduto!=''){
								document.getElementById('listaDados_td_'+valorCampoGrupoProduto).style.backgroundColor = "#FFFFFF";
							}
							if(valorCampoGrupoProduto == valor){
								busca_grupo_produto(valor,true);
							}
							valorCampoGrupoProduto = valor;
							document.BuscaGrupoProduto.IdGrupoProduto.value = valorCampoGrupoProduto;
							campo.style.backgroundColor = '<?=getParametroSistema(15,4)?>';
						}
						enterAsTab(document.forms.formularioGrupoProduto);	
					</script>
				</div>
		     </div>
