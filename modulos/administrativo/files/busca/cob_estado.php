			<div id='quadroBuscaCobEstado' style='width:365px;' class='quadroFlutuante'>
				<!--div class='tit'>Busca Estado<div class='fecha' onClick="vi_id('quadroBuscaCobEstado', false);">X</div></div-->
				<table class='titMenu' cellspacing='0' cellpading='0'>
					<tr>
						<td class='tit'>Busca Estado</td>
						<td class='fecha' onClick="vi_id('quadroBuscaCobEstado', false);">X</div></td>
					</tr>
				</table>
				<div class='filtro_busca'>
					<form name='formularioCobEstado' method='post'>
						<table>
							<tr>
								<td class='descCampo'>Nome Estado</td>
							</tr>
							<tr>
								<td class='campo'>
									<input type='text' name='NomeEstado' value='' style='width:334px' maxlength='100' onkeyup="busca_cob_estado_lista()" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='2'>
								</td>
							</tr>
						</table>
					</form>
					<div id='carregando'>carregando...</div>
					<div class='listaDados' style='height: 180px;'>
						<div id='listaDadosQuadroCobEstado'>&nbsp;</div>
					</div>
					<form name='BuscaCobEstado' method='post' onSubmit='return validar_busca_cob_estado()'>
						<input type='hidden' name='Cob_IdEstado' value=''>
						<table class='listaDados'>
							<tr>
								<td>
									<input type='submit' value='Ok' class='botao'>
									<input type='button' value='Cancelar' onClick="vi_id('quadroBuscaCobEstado', false);" class='botao'>
								</td>
							</tr>
						</table>
					</form>	
					<script language='JavaScript' type='text/javascript'>
						// Para que os quadros flutuem
						new Draggable('quadroBuscaCobEstado');
						
						var valorCampoCobEstado = '';
						function validar_busca_cob_estado(){
							if(valorCampoCobEstado !=''){
								busca_cob_estado(document.formulario.Cob_IdPais.value,valorCampoCobEstado);
							}
							return false;
						}
						function busca_cob_estado_lista(){
							var IdPais 	  	= document.formulario.Cob_IdPais.value;
							var NomeEstado	= document.formularioCobEstado.NomeEstado.value;
							var Limit	  	= <?=getCodigoInterno(7,4)?>;
							var nameNode, nameTextNode, url;
							
							if(IdPais == ''){
								window.close();
							}
							
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
						    
						    if(NomeEstado == ''){
						    	url = "xml/estado.php?IdPais="+IdPais+"&Limit="+Limit;
							}else{
								url = "xml/estado.php?IdPais="+IdPais+"&NomeEstado="+NomeEstado;
							}
							
							xmlhttp.open("GET", url,true);
							
							xmlhttp.onreadystatechange = function(){ 
					
								// Carregando...
								carregando(true);
					
								if(xmlhttp.readyState == 4){ 
									if(xmlhttp.status == 200){
										if(xmlhttp.responseText == 'false'){
											document.getElementById('listaDadosQuadroCobEstado').innerHTML = "";
											// Fim de Carregando
											carregando(false);
										}else{
											var dados = "<table id='listaDados' style='width: 343px'>\n<tr>\n<td class='listaDados_titulo' style='width: 60px'>Estado</td>\n<td class='listaDados_titulo'>Nome Estado</td>\n</tr>";
											for(var i=0; i<xmlhttp.responseXML.getElementsByTagName("IdPais").length; i++){
													
												nameNode = xmlhttp.responseXML.getElementsByTagName("IdEstado")[i]; 
												nameTextNode = nameNode.childNodes[0];
												var IdEstado = nameTextNode.nodeValue;
												
												nameNode = xmlhttp.responseXML.getElementsByTagName("NomeEstado")[i]; 
												nameTextNode = nameNode.childNodes[0];
												var NomeEstado = verifica_dado(nameTextNode.nodeValue);
												
												dados += "\n<tr style='background-color:#FFF' id='listaDados_td_"+IdEstado+"' onClick='aciona_busca_cob_estado(this,"+IdEstado+")'>";
												dados += 	"\n<td>"+IdEstado+"</td><td>"+NomeEstado+"</td>";
												dados += "\n</tr>";
											}
											dados += "\n</table>";
											document.getElementById('listaDadosQuadroCobEstado').innerHTML = dados;
										}
									}
								} 
								// Fim de Carregando
								carregando(false);
							}
							xmlhttp.send(null);
						}
						function aciona_busca_cob_estado(campo,valor){	
							var IdPais = document.formulario.Cob_IdPais.value;
							if(valorCampoCobEstado!=''){
								document.getElementById('listaDados_td_'+valorCampoCobEstado).style.backgroundColor = "#FFFFFF";
							}
							if(valorCampoCobEstado == valor){
								busca_cob_estado(IdPais,valor,true);
							}
							valorCampoCobEstado = valor;
							document.BuscaCobEstado.Cob_IdEstado.value = valorCampoCobEstado;
							campo.style.backgroundColor = '<?=getParametroSistema(15,4)?>';
						}
					</script>
				</div>
			</div>
