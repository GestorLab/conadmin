			<div id='quadroBuscaPlanoConta' style='width:533px;' class='quadroFlutuante'>		
				<!--div class='tit'>Busca Plano de Conta<div class='fecha' onClick="vi_id('quadroBuscaPlanoConta', false);">X</div></div-->
				<table class='titMenu' cellspacing='0' cellpading='0'>
					<tr>
						<td class='tit'>Busca Plano de Conta</td>
						<td class='fecha' onClick="vi_id('quadroBuscaPlanoConta', false);">X</div></td>
					</tr>
				</table>
				<div class='filtro_busca'>
					<form name='formularioPlanoConta' method='post'>
						<table>
							<tr>
								<td class='descCampo'>Plano de Conta</td>
								<td class='descCampo'>Nome Plano de Conta</td>
								<td class='descCampo'>Ac. Rápido</td>
							</tr>
							<tr>
								<td class='campo'>
									<input type='text' name='IdPlanoConta' value='' size='9' style='width:110px' onkeyup="busca_plano_conta_lista()" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onkeypress="mascara(this,event,'PlanoConta','N')" tabindex='1'> 
								</td>
								<td class='campo'>
									<input type='text' name='DescricaoPlanoConta' value='' autocomplete="off" style='width:225px' maxlength='100' onkeyup="busca_plano_conta_lista()" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='2'>
								</td>
								<td class='campo'>
									<input type='text' name='IdAcessoRapido' value='' autocomplete="off" style='width:150px' maxlength='30' onkeyup="busca_plano_conta_lista()" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='3'>
								</td>
							</tr>
						</table>
					</form>
					<div id='carregando'>carregando...</div>
					<div class='listaDados' style='height: 200px;'>
						<div id='listaDadosQuadroPlanoConta'>&nbsp;</div>
					</div>
					<form name='BuscaPlanoConta' method='post' action='busca_plano_conta.php' onSubmit='return validar_busca_plano_conta()'>
						<input type='hidden' name='IdPlanoConta' value=''>
						<input type='hidden' name='Tipo' value=''>
						<input type='hidden' name='AcessoRapido' value=''>
						<table>
							<tr>
								<td>
									<input type='submit' value='Ok' class='botao'>
									<input type='button' value='Cancelar' onClick="vi_id('quadroBuscaPlanoConta', false);" class='botao'>
								</td>
							</tr>
						</table>
					</form>	
					<script>
						// Para que os quadros flutuem
						new Draggable('quadroBuscaPlanoConta');
						
						var valorCampoPlanoConta = '';
						function validar_busca_plano_conta(){
							if(valorCampoPlanoConta !=''){
								busca_plano_conta(valorCampoPlanoConta,document.BuscaPlanoConta.Tipo.value,true,document.formulario.Local.value,document.BuscaPlanoConta.AcessoRapido.value,'');
							}
							return false;
						}
						function busca_plano_conta_lista(){
							var IdPlanoConta 			= document.formularioPlanoConta.IdPlanoConta.value;
							var DescricaoPlanoConta	 	= document.formularioPlanoConta.DescricaoPlanoConta.value;
							var IdAcessoRapido		 	= document.formularioPlanoConta.IdAcessoRapido.value;
							var Limit	  	 			= '<?=getCodigoInterno(7,4)?>';
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
						    
						    if(IdPlanoConta == '' && DescricaoPlanoConta == '' && IdAcessoRapido == ''){
						    	url = "xml/plano_conta.php?Limit="+Limit;
							}else{
								url = "xml/plano_conta.php?IdPlanoConta="+IdPlanoConta+"&DescricaoPlanoConta="+DescricaoPlanoConta+"&IdAcessoRapido="+IdAcessoRapido;
							}
							xmlhttp.open("GET", url,true);
							
							xmlhttp.onreadystatechange = function(){ 
					
								// Carregando...
								carregando(true);
					
								if(xmlhttp.readyState == 4){ 
									if(xmlhttp.status == 200){
										if(xmlhttp.responseText == 'false'){
											document.getElementById('listaDadosQuadroPlanoConta').innerHTML = "";
											// Fim de Carregando
											carregando(false);
										}else{
											var dados = "<table id='listaDados' style='width: 511px; font-size:10px'>\n<tr>\n<td class='listaDados_titulo' style='width: 100px'>Plano de Conta</td>\n<td class='listaDados_titulo'>Nome</td>\n<td class='listaDados_titulo' style='width:55px'>Tipo</td>\n<td class='listaDados_titulo'>Ac. Rápido</td>\n</tr>";
											
											for(var i=0; i<xmlhttp.responseXML.getElementsByTagName("IdPlanoConta").length; i++){
												
												nameNode = xmlhttp.responseXML.getElementsByTagName("IdPlanoConta")[i]; 
												nameTextNode = nameNode.childNodes[0];
												var IdPlanoConta = nameTextNode.nodeValue;
												
												nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoPlanoConta")[i]; 
												nameTextNode = nameNode.childNodes[0];
												var DescricaoPlanoConta = verifica_dado(nameTextNode.nodeValue);
						
												nameNode = xmlhttp.responseXML.getElementsByTagName("IdAcessoRapido")[i]; 
												nameTextNode = nameNode.childNodes[0];
												var IdAcessoRapido = verifica_dado(nameTextNode.nodeValue);
												
												nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoTipo")[i]; 
												nameTextNode = nameNode.childNodes[0];
												var Tipo = verifica_dado(nameTextNode.nodeValue);
												
												dados += "\n<tr style='background-color:#FFF' id='listaDados_td_"+IdPlanoConta+"' onClick=\"aciona_busca_plano_conta(this,'"+IdPlanoConta+"','"+Tipo+"')\">";
												dados += "\n<td>"+IdPlanoConta+"</td>\n<td>"+DescricaoPlanoConta+"</td>\n<td>"+Tipo+"</td>\n<td>"+IdAcessoRapido+"</td>";
												dados += "\n</tr>";
											}
											dados += "\n</table>";
											document.getElementById('listaDadosQuadroPlanoConta').innerHTML = dados;
										}
									}
								} 
								// Fim de Carregando
								carregando(false);
							}
							xmlhttp.send(null);
						}
						function aciona_busca_plano_conta(campo,valor,Tipo){
							if(valorCampoPlanoConta!=''){
								document.getElementById('listaDados_td_'+valorCampoPlanoConta).style.backgroundColor = "#FFFFFF";
							}
							var Local = document.formulario.Local.value;
							if(Local == 'ContaPagar'){
								if(Tipo == 'Analítico'){
									if(valorCampoPlanoConta == valor){
										busca_plano_conta(valor,document.BuscaPlanoConta.Tipo.value,true,document.formulario.Local.value,document.BuscaPlanoConta.AcessoRapido.value,'');
									}
									valorCampoPlanoConta = valor;
									document.BuscaPlanoConta.IdPlanoConta.value = valorCampoPlanoConta;
									campo.style.backgroundColor = '<?=getParametroSistema(15,4)?>';	
								}
							}else{
								if(valorCampoPlanoConta == valor){
									busca_plano_conta(valor,document.BuscaPlanoConta.Tipo.value,true,document.formulario.Local.value,document.BuscaPlanoConta.AcessoRapido.value,'');
								}
								valorCampoPlanoConta = valor;
								document.BuscaPlanoConta.IdPlanoConta.value = valorCampoPlanoConta;
								campo.style.backgroundColor = '<?=getParametroSistema(15,4)?>';
							}
						}
						function limpa_form_plano_conta(){
							document.formularioPlanoConta.IdPlanoConta.value		=	"";
							document.formularioPlanoConta.DescricaoPlanoConta.value	=	"";
							document.formularioPlanoConta.IdAcessoRapido.value		=	"";
							
							valorCampoPlanoConta	=	"";
						}
						enterAsTab(document.forms.formularioPlanoConta);
					</script>
				</div>
			</div>
