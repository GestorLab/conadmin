			<div id='quadroBuscaFabricante' style='width:365px;' class='quadroFlutuante'>	
				<!--div class='tit'>Busca Fabricante<div class='fecha' onClick="vi_id('quadroBuscaFabricante', false);">X</div></div-->
				<table class='titMenu' cellspacing='0' cellpading='0'>
					<tr>
						<td class='tit'>Busca Fabricante</td>
						<td class='fecha' onClick="vi_id('quadroBuscaFabricante', false);">X</div></td>
					</tr>
				</table>
				<div class='filtro_busca'>
					<form name='formularioFabricante' method='post'>
						<table>
							<tr>
								<td class='descCampo'>Nome Fabricante</td>
							</tr>
							<tr>
								<td class='campo'>
									<input type='text' name='Nome' autocomplete="off" value='' style='width:334px' maxlength='100' onkeyup="busca_fabricante_lista()" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='2'>
								</td>
							</tr>
						</table>
					</form>
					<div id='carregando'>carregando...</div>
					<div class='listaDados' style='height: 180px;'>
						<div id='listaDadosQuadroFabricante'>&nbsp;</div>
					</div>
					<form name='BuscaFabricante' method='post' onSubmit='return validar_busca_fabricante()'>
						<input type='hidden' name='IdFabricante' value=''>
						<table class='listaDados'>
							<tr>
								<td>
									<input type='submit' value='Ok' class='botao'>
									<input type='button' value='Cancelar' onClick="vi_id('quadroBuscaFabricante', false);" class='botao'>
								</td>
							</tr>
						</table>
					</form>
					<script>
						// Para que os quadros flutuem
						new Draggable('quadroBuscaFabricante');
						
						var valorCampoFabricante = '';
						function validar_busca_fabricante(){
							if(valorCampoFabricante !=''){
								busca_fabricante(valorCampoFabricante);
							}
							return false;
						}
						function busca_fabricante_lista(){
							var Nome 		= document.formularioFabricante.Nome.value;
							var Limit	  	= <?=getCodigoInterno(7,4)?>;
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
						    
						    if(Nome == ''){
						    	url = "../administrativo/xml/fabricante.php?Limit="+Limit;
							}else{
								url = "../administrativo/xml/fabricante.php?DescricaoFabricante="+Nome;
							}
							xmlhttp.open("GET", url,true);
							
							xmlhttp.onreadystatechange = function(){ 
					
								// Carregando...
								carregando(true);
					
								if(xmlhttp.readyState == 4){ 
									if(xmlhttp.status == 200){
										if(xmlhttp.responseText == 'false'){
											document.getElementById('listaDadosQuadroFabricante').innerHTML = "";
											// Fim de Carregando
											carregando(false);
										}else{
											var dados = "<table id='listaDados' style='width: 343px'>\n<tr>\n<td class='listaDados_titulo' style='width: 70px'>Fabricante</td>\n<td class='listaDados_titulo'>Nome Fabricante</td>\n</tr>";
											for(var i=0; i<xmlhttp.responseXML.getElementsByTagName("IdFabricante").length; i++){
													
												nameNode = xmlhttp.responseXML.getElementsByTagName("IdFabricante")[i]; 
												nameTextNode = nameNode.childNodes[0];
												var IdFabricante = nameTextNode.nodeValue;
												
												nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoFabricante")[i]; 
												nameTextNode = nameNode.childNodes[0];
												var DescricaoFabricante = nameTextNode.nodeValue;
												
												DescricaoFabricante = DescricaoFabricante.substr(0,40);
												
												dados += "\n<tr style='background-color:#FFF' id='listaDados_td_"+IdFabricante+"' onClick='aciona_busca_fabricante(this,"+IdFabricante+")'>";
												dados += 	"\n<td>"+IdFabricante+"</td><td>"+DescricaoFabricante+"</td>";
												dados += "\n</tr>";
											}
											dados += "\n</table>";
											document.getElementById('listaDadosQuadroFabricante').innerHTML = dados;
										}
									}
								} 
								// Fim de Carregando
								carregando(false);
							}
							xmlhttp.send(null);
						}
						function aciona_busca_fabricante(campo,valor){
							if(valorCampoFabricante!=''){
								document.getElementById('listaDados_td_'+valorCampoFabricante).style.backgroundColor = "#FFFFFF";
							}
							if(valorCampoFabricante == valor){
								busca_fabricante(valor);
							}
							valorCampoFabricante = valor;
							document.BuscaFabricante.IdFabricante.value = valorCampoFabricante;
							campo.style.backgroundColor = '<?=getParametroSistema(15,4)?>';
						}
						enterAsTab(document.forms.formularioFabricante);
					</script>
				</div>
			</div>
