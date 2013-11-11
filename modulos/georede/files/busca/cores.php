			<div id='quadroBuscaCor' style='width:90px;' class='quadroFlutuante' style="margin-right: 92%; position: ">
				<table class='titMenu' cellspacing='0' cellpading='0'>
					<tr>
						<td class='tit'>Cores</td>
						<td class='fecha' onClick="vi_id('quadroBuscaCor', false);">X</td>
					</tr>
				</table>
				<form name='formularioCor' method='post'>
					<input type='hidden' name='Cores' value='<?=getCodigoInterno(3,83)?>'>
				</form>
				<div class='filtro_busca' >
					<script language='JavaScript' type='text/javascript'>
						// Para que os quadros flutuem
						new Draggable('quadroBuscaCor');
						
						var cor = new Array(36);
						var tam = 0;					
						
						function muda_cor(triplet) {
							document.formulario.Cor.value = triplet
							
							vi_id('quadroBuscaCor', false);
						}
						
						function drawTable(){
							var aux	= document.formularioCor.Cores.value.split('\n');
							var pos = 0;  
							var tam = aux.length;
							var cor	= "#FFFFFF";
							document.write('<TABLE CELLPADDING=0 CELLSPACING=0 BORDER=0>');
							for (var i = 0; i < 6; ++i) {
								document.write('<TR>');
								for (var j = 0; j < 6; ++j) {
									if(pos < tam){
										cor	=	trim(aux[pos]);
									}
									
									document.write("<TD STYLE='CURSOR:POINTER; border:1px #A4A4A4 solid' BGCOLOR='"+cor+"' HEIGHT=14 WIDTH=14 onClick=\"javascript:muda_cor('"+cor+"')\">&nbsp;</TD>");
									
									pos++;
								}
								document.write('</TR>');
							}	
							document.write('</TABLE>');	
						}
						
						drawTable();
					</script>
				</div>
			</div>
