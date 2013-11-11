jQuery(document).ready(function(){
	
	var $ = jQuery.noConflict();
	//alert("akiii");
	url = window.location.search;
	//alert(url);
	if(url.indexOf("?") != -1){
		url = url.replace("?", "");
	}
	if(url.indexOf("=") != -1){
		url = url.replace("=", "");
	}
	   
	//alert(url);
	
	$('body').addTabindex();

	if(typeof url != 'undefined'){
		//alert(url);
		addParmUrl("marTerceiroMonitor", "", url);
		url = decodeURI(url);
		url = $.parseJSON(url);
		if(typeof url.IdDevice != 'undefined')
		{
			//alert(url.IdDevicePerfil);
			var urlPerfil = "device_monitor_" + url.IdDevicePerfil + ".php";
			$("#deviceMonitor").load("device_monitor_" + url.IdDevicePerfil + ".php #form", {dados: url});
			$("#monitor").load("xml/device_monitor.php .tableListarCad", {dados: url});
			
			$('body').on('submit', '#form', function(e){
				///alert('akiii');
				//alert($(this).find('').val());
				//teste = teste.parent('.obrig');
				//e.preventDefault();
				mensagens(0);
				$(this).find('#tableMonitor').find('#dadosMonitor').find('td').find('.obrig').each(function(index){
					//alert('akiiii');
					if($(this).is(":input")){
						if($(this).val() == ""){
							$(this).focus();
							mensagens(1);
							//cont = 1;
							e.preventDefault();
							return false;
						}
					}
				});
			});
			
			$('body').on('click', '.bt_lista #excluir_2', function(){
				//alert('akiii');
			});
			
			$('body').on('click', '.result', function(){
				data = $(this).parent('td').parent('tr').find('td:first').find(":input:first").val();
				//alert(data);
				if($('#deviceMonitor #form').is(':visible')){
					$('#deviceMonitor #form').remove();
				}
				$('#deviceMonitor').load('device_monitor_1.php #form', {IdDeviceMonitor: data});
				
			});
			$('body').addTabindex();
			
			$(document).ajaxError(function(event, jqxhr, settings){
				//alert(settings.url);
				if(settings.url.indexOf("device_monitor") != -1){
					//$("#sem_permissao").show();
					$(".escondeElemento").show();
					$("input[name=bt_voltar]").click(function(){
						//alert("akii");
						location.href = "listar_device.php";
					});
				}
			});
			
			
			if(typeof url.tipoMsg != 'undefined'){
				mensagens(url.tipoMsg);
			}
			
		}
	}
});

(function($){
	$.fn.addTabindex = function(){
		$(':input').each(function(index){
			$(this).prop('tabindex', index);
		});
	}
})(jQuery);