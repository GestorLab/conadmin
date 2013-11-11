			<div id='quadroBuscaCobPais' style='width:365px;' class='quadroFlutuante'>
				<!--div class='tit'>Busca Pa�s<div class='fecha' onClick="vi_id('quadroBuscaCobPais', false);">X</div></div-->
				<table class='titMenu' cellspacing='0' cellpading='0'>
					<tr>
						<td class='tit'>Busca Pa�s</td>
						<td class='fecha' onClick="vi_id('quadroBuscaCobPais', false);">X</div></td>
					</tr>
				</table>
				<div class='filtro_busca'>
					<form name='formularioCobPais' method='post'>
						<table>
							<tr>
								<td class='descCampo'>Nome Pa�s</td>
							</tr>
							<tr>
								<td class='campo'>
									<input type='text' name='NomePais' autocomplete="off" value='' style='width:334px' maxlength='100' onkeyup="busca_cob_pais_lista()" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='1'>
								</td>
							</tr>
						</table>
					</form>
					<div id='carregando'>carregando...</div>
					<div class='listaDados' style='height: 180px;'>
						<div id='listaDadosQuadroCobPais'>&nbsp;</div>
					</div>
					<form name='BuscaCobPais' method='post' onSubmit='return validar_busca_cob_pais()'>
						<input type='hidden' name='Cob_IdPais' value=''>
						<table class='listaDados'>
							<tr>
								<td>
									<input type='submit' value='Ok' class='botao'>
									<input type='button' value='Cancelar' onClick="vi_id('quadroBuscaCobPais', false);" class='botao'>
								</td>
							</tr>
						</table>
					</form>
					<script language='JavaScript' type='text/javascript'>
						// Para que os quadros flutuem
						new Draggable('quadroBuscaCobPais');						
						
						var valorCampoCobPais = '';
						function validar_busca_cob_pais(){
							if(valorCampoCobPais !=''){
								busca_cob_pais(valorCampoCobPais);	
							}
							return false;
						}
						function busca_cob_pais_lista(){
							var NomePais	= document.formularioCobPais.NomePais.value;
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
						    
						    if(NomePais == ''){
						    	url = "xml/pais.php?Limit="+Limit;
							}else{
								url = "xml/pais.php?NomePais="+NomePais;
							}
							xmlhttp.open("GET", url,true);
							
							xmlhttp.onreadystatechange = function(){ 
					
								// Carregando...
								carregando(true);
					
								if(xmlhttp.readyState == 4){ 
									if(xmlhttp.status == 200){
										if(xmlhttp.responseText == 'false'){
											document.getElementById('listaDadosQuadroCobPais').innerHTML = "";
											// Fim de Carregando
											carregando(false);
										}else{
											var dados = "<table id='listaDados' style='width: 343px;'>\n<tr>\n<td class='listaDados_titulo' style='width: 60px'>Pa�s</td>\n<td class='listaDados_titulo'>Nome Pa�s</td>\n</tr>";
											for(var i=0; i<xmlhttp.responseXML.getElementsByTagName("IdPais").length; i++){
													
												nameNode = xmlhttp.responseXML.getElementsByTagName("IdPais")[i]; 
												nameTextNode = nameNode.childNodes[0];
												var IdPais = nameTextNode.nodeValue;
												
												nameNode = xmlhttp.responseXML.getElementsByTagName("NomePais")[i]; 
												nameTextNode = nameNode.childNodes[0];
												var NomePais = verifica_dado(nameTextNode.nodeValue);
												
												dados += "\n<tr style='background-color:#FFF' id='listaDados_td_"+IdPais+"' onClick='aciona_busca_cob_pais(this,"+IdPais+")'>";
												dados += 	"\n<td>"+IdPais+"</td><td>"+NomePais+"</td>";
												dados += "\n</tr>";
											}
											dados += "\n</table>";
											document.getElementById('listaDadosQuadroCobPais').innerHTML = dados;
										}
									}
								} 
								// Fim de Carregando
								carregando(false);
							}
							xmlhttp.send(null);
						}
						function aciona_busca_cob_pais(campo,valor){
							if(valorCampoCobPais!=''){
								document.getElementById('listaDados_td_'+valorCampoCobPais).style.backgroundColor = "#FFFFFF";
							}
							if(valorCampoCobPais == valor){
								busca_cob_pais(valor,true);
							}
							valorCampoCobPais = valor;
							document.BuscaCobPais.Cob_IdPais.value = valorCampoCobPais;
							campo.style.backgroundColor = '<?=getParametroSistema(15,4)?>';
						}			
					</script>
				</div>
			</div>

