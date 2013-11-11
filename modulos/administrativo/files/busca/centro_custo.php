			<div id='quadroBuscaCentroCusto' style='width:365px;' class='quadroFlutuante'>	
				<!--div class='tit'>Busca Centro de Custo<div class='fecha' onClick="vi_id('quadroBuscaCentroCusto', false);">X</div></div-->
				<table class='titMenu' cellspacing='0' cellpading='0'>
					<tr>
						<td class='tit'>Busca Centro de Custo</td>
						<td class='fecha' onClick="vi_id('quadroBuscaCentroCusto', false);">X</div></td>
					</tr>
				</table>
				<div class='filtro_busca'>
					<form name='formularioCentroCusto' method='post'>
						<table>
							<tr>
								<td class='descCampo'>Nome Centro de Custo</td>
							</tr>
							<tr>
								<td class='campo'>
									<input type='text' name='DescricaoCentroCusto' autocomplete="off" value='' style='width:334px' maxlength='100' onkeyup="busca_centro_custo_lista()" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='2'>
								</td>
							</tr>
						</table>
					</form>
					<div id='carregando'>carregando...</div>
					<div class='listaDados' style='height: 180px;'>
						<div id='listaDadosQuadroCentroCusto'>&nbsp;</div>
					</div>
					<form name='BuscaCentroCusto' method='post' onSubmit='return validar_busca_centro_custo()'>
						<input type='hidden' name='IdCentroCusto' value=''>
						<input type='hidden' name='SubLocal' value=''>
						<table class='listaDados'>
							<tr>
								<td>
									<input type='submit' value='Ok' class='botao'>
									<input type='button' value='Cancelar' onClick="vi_id('quadroBuscaCentroCusto', false);" class='botao'>
								</td>
							</tr>
						</table>
					</form>	
					<script>
						// Para que os quadros flutuem
						new Draggable('quadroBuscaCentroCusto');
						
						var valorCampoCentroCusto = '';
						function validar_busca_centro_custo(){
							if(valorCampoCentroCusto !=''){
								if(document.BuscaCentroCusto.SubLocal.value == 'Conta'){
									busca_conta_pagar_centro_custo(document.formulario.IdContaPagar.value,true,document.formulario.Local.value,valorCampoCentroCusto);
								}else if(document.BuscaCentroCusto.SubLocal.value == 'Mantenedor'){
									busca_centro_custo_rateio(document.formulario.IdCentroCusto.value,true,document.formulario.Local.value,valorCampoCentroCusto);
								}else{
									busca_centro_custo(valorCampoCentroCusto,true,document.formulario.Local.value,document.BuscaCentroCusto.SubLocal.value);
								}
							}
							return false;
						}
						function busca_centro_custo_lista(){
							var DescricaoCentroCusto 	= document.formularioCentroCusto.DescricaoCentroCusto.value;
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
						    
						    if(DescricaoCentroCusto == ''){
						    	url = "xml/centro_custo.php?Limit="+Limit;
							}else{
								url = "xml/centro_custo.php?DescricaoCentroCusto="+DescricaoCentroCusto;
							}
							xmlhttp.open("GET", url,true);
							
							xmlhttp.onreadystatechange = function(){ 
					
								// Carregando...
								carregando(true);
					
								if(xmlhttp.readyState == 4){ 
									if(xmlhttp.status == 200){
										if(xmlhttp.responseText == 'false'){
											document.getElementById('listaDadosQuadroCentroCusto').innerHTML = "";
											// Fim de Carregando
											carregando(false);
										}else{
											var dados = "<table id='listaDados' style='width: 343px'>\n<tr>\n<td class='listaDados_titulo' style='width: 80px'>Cent. de Cust.</td>\n<td class='listaDados_titulo'>Nome Centro de Custo</td>\n</tr>";
											for(var i=0; i<xmlhttp.responseXML.getElementsByTagName("IdCentroCusto").length; i++){
													
												nameNode = xmlhttp.responseXML.getElementsByTagName("IdCentroCusto")[i]; 
												nameTextNode = nameNode.childNodes[0];
												var IdCentroCusto = nameTextNode.nodeValue;
												
												nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoCentroCusto")[i]; 
												nameTextNode = nameNode.childNodes[0];
												var DescricaoCentroCusto = verifica_dado(nameTextNode.nodeValue);
												
												dados += "\n<tr style='background-color:#FFF' id='listaDados_td_"+IdCentroCusto+"' onClick='aciona_busca_centro_custo(this,"+IdCentroCusto+")'>";
												dados += 	"\n<td>"+IdCentroCusto+"</td><td>"+DescricaoCentroCusto+"</td>";
												dados += "\n</tr>";
											}
											dados += "\n</table>";
											document.getElementById('listaDadosQuadroCentroCusto').innerHTML = dados;
										}
									}
								} 
								// Fim de Carregando
								carregando(false);
							}
							xmlhttp.send(null);
						}
						function aciona_busca_centro_custo(campo,valor){
							if(valorCampoCentroCusto!=''){
								document.getElementById('listaDados_td_'+valorCampoCentroCusto).style.backgroundColor = "#FFFFFF";
							}
							if(valorCampoCentroCusto == valor){
								if(document.BuscaCentroCusto.SubLocal.value == 'Conta'){
									busca_conta_pagar_centro_custo(document.formulario.IdContaPagar.value,true,document.formulario.Local.value,valor);
								}if(document.BuscaCentroCusto.SubLocal.value == 'Mantenedor'){
									busca_centro_custo_rateio(document.formulario.IdCentroCusto.value,true,document.formulario.Local.value,valor);
								}else{
									busca_centro_custo(valor,true,document.formulario.Local.value,document.BuscaCentroCusto.SubLocal.value);
								}
							}
							valorCampoCentroCusto = valor;
							document.BuscaCentroCusto.IdCentroCusto.value = valorCampoCentroCusto;
							campo.style.backgroundColor = '<?=getParametroSistema(15,4)?>';
						}
						enterAsTab(document.forms.formularioCentroCusto);	
					</script>
				</div>
			</div>
