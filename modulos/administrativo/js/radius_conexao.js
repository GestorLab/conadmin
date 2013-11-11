		function inicia(){
			if(document.filtro.filtro_mes_referencia.value!=''){
				buscaDiaReferencia(document.filtro.filtro_mes_referencia.value, document.filtro.filtro_dia_referencia_temp.value);
			}
		}
	
		function listar_radius(e){
			var e = e || event;
			var k = e.keyCode || e.which;
			if (k==13){
				if(document.filtro.filtro_servidor.value != "" &&  document.filtro.filtro_mes_referencia.value!=""){
					document.filtro.submit();
				}else{
					alert("Atencao!\nPreencha os campos do filtro.");
					return false;
				}
			} 
		}
		function validar(){
			if(document.filtro.filtro_servidor.value != "" && document.filtro.filtro_mes_referencia.value!=""){
				document.filtro.submit();
			}else{
				alert("Atencao!\nPreencha os campos do filtro.");
				return false;
			}
		}
		function atualizar_filtro_mes(campo){
			if(campo.value != ''){
				if(val_Mes(campo.value)!=false){
					buscaDiaReferencia(campo.value);
				}
			}else{
				while(document.filtro.filtro_dia_referencia.options.length > 0){
					document.filtro.filtro_dia_referencia.options[0] = null;
				}
				
				addOption(document.filtro.filtro_dia_referencia,"Todos","");
			}
		}
		function buscaDiaReferencia(mesAno,DiaReferente){
			if(mesAno == ""){
				while(document.filtro.filtro_dia_referencia.options.length > 0){
					document.filtro.filtro_dia_referencia.options[0] = null;
				}
				
				addOption(document.filtro.filtro_dia_referencia,"Todos","");
				return false;
			} else{
				var mes	=	new String(mes);
				var ano	=	new String(ano);
				
				var mes	=	mesAno.substring(0,2);
				var ano	=	mesAno.substring(3,7);
				
				mes		=	parseFloat(mes)-1;
				
				var qtdDias = 	new Array();
				
				qtdDias		=	[31,(ano%4 == 0) ? 29 : 28,31,30,31,30,31,31,30,31,30,31];	
				
				while(document.filtro.filtro_dia_referencia.options.length > 0){
					document.filtro.filtro_dia_referencia.options[0] = null;
				}
				
				addOption(document.filtro.filtro_dia_referencia,"Todos","");
				
				if(qtdDias[mes] >=1){
					var dia;
					for(i=1;i<=qtdDias[mes];i++){
						if(i<10){
							dia = "0"+i;
						} else{
							dia = i;
						}
						addOption(document.filtro.filtro_dia_referencia,i,dia);
					}	
					if(DiaReferente == "" || DiaReferente == undefined){
						document.filtro.filtro_dia_referencia[0].selected = true;
					} else{
						for(i=0;i<document.filtro.filtro_dia_referencia.options.length;i++){
							if(document.filtro.filtro_dia_referencia[i].value == DiaReferente){
								document.filtro.filtro_dia_referencia[i].selected = true;			
							}
						}
					}
				}
			}
		}

