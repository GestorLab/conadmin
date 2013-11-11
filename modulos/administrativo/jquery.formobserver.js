/**
 *  jquery.popupt
 *  (c) 2008 Semooh (http://semooh.jp/)
 *
 *  Dual licensed under the MIT (MIT-LICENSE.txt)
 *  and GPL (GPL-LICENSE.txt) licenses.
 *
 **/
(function($){
  $.fn.extend({
	  FormObserve: function(){
		  fs = $(this);
		  fs.each(function(){
			  node = $(this);
			  v = node.find(":input");
			  node.FormObserve_Save();
			  
			  setInterval(function(){
				  v.each(function(){
					  campo = $(this);
					  //alert(this[campo.attr("id")]);
					  if(campo.val() != this[campo.attr("id")] && campo.attr("class") == "observe")
						  campo.attr("name", "dados["+campo.attr("id")+"]");
					  else if(campo.val() == this[campo.attr("id")] && campo.attr("class") == "observe")
						  campo.removeAttr("name");
				  });
			  },1);
		  });
	  },
	  FormObserve_Save: function(){
		 node = $(this);
		 if(node.is("form")){
			 node.find(":input").each(function(){
				 //alert($(this).attr("id"));
				 //$(this).FormObserve_Save();
				 if($(this).attr("id") != null){
					 this[$(this).attr("id")] = $(this).val();
				 }
			 });
		 }/*else if(node.is(":input")){
			 //v = node.get(0);
			 //alert(v.val());
		 }*/
		  /*type = $(this);
		  node = type.find(":input");
		  v = node.get(0)
		  this.prototype[v.attr("id")] = v.val();*/
	  }
    /*FormObserve: function(opt){
      opt = $.extend({
       changeClass: "changed",
       //changeClass: "dados[]",
        filterExp: "",
        msg: "Unsaved changes will be lost.\nReally continue?"
      }, opt || {});

      var fs = $(this);
      fs.each(function(){
        //this.reset();
        var f = $(this);
        var is = f.find(':input');
        f.FormObserve_save();
        setInterval(function(){
          is.each(function(){
            var node = $(this);
            var def = $.data(node.get(0), 'FormObserve_Def');
            alert(node.attr('id'));
            if(node.FormObserve_ifVal() == def){
              //if(opt.changeClass) node.removeClass(opt.changeClass);
            	if(opt.changeClass){
            		node.removeClass(opt.changeClass);
            		node.removeAttr("name");
            	} 
            }else{
              //if(opt.changeClass) node.addClass(opt.changeClass);
            	if(opt.changeClass){
            		node.addClass(opt.changeClass);
            		node.attr("name", "dados["+node.attr("id")+"]");
            	} 
            }
          });
        }, 1);
      });

      function beforeunload(e){
        var changed = false;
        fs.each(function(){
          if($(this).find(':input').FormObserve_isChanged()){
            changed = true;
            return false;
          }
        });
        if(changed){
          e = e || window.event;
          e.returnValue = opt.msg;
        }
      }
      if(window.attachEvent){
          window.attachEvent('onbeforeunload', beforeunload);
      }else if(window.addEventListener){
          window.addEventListener('beforeunload', beforeunload, true);
      }
    },
    FormObserve_save: function(){
      var node = $(this);
      if(node.is('form')){
        node.find(':input').each(function(){
          $(this).FormObserve_save();
        });
      } else if(node.is(':input')){
        $.data(node.get(0), 'FormObserve_Def', node.FormObserve_ifVal());
      }
    },
    FormObserve_isChanged: function(){
      var changed = false;
      this.each(function() {
        var node = $(this);
        if(node.eq(':input')){
          var def = $.data(node.get(0), 'FormObserve_Def');
          if(typeof def != 'undefined' && def != node.FormObserve_ifVal()){
            changed = true;
            return false;
          }
        }
      });
      return changed;
    },
    FormObserve_ifVal: function(){
      var node = $(this.get(0));
      if(node.is(':radio,:checkbox')){
        var r = node.attr('checked');
      }else if(node.is(':input')){
        var r = node.val();
      }
      return r;
    }*/
  });
})(jQuery);
