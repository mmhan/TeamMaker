/* 
 * Author: @mmhan
 */

var TeamMaker = TeamMaker || {};
TeamMaker = function () {

    var config = {
		Nav: {
			supersubs: {
				minWidth:20,
				maxWidth:30,
				extraWidth: 2
			},
			superfish: {
				delay:300,
				speed:'fast'
			}
		}
    };
	
	var priv = {
		//Top navigation.
		Nav: {
			init: function(){
				$('ul.sf-menu')
					.supersubs(config.Nav.supersubs)
					.superfish(config.Nav.superfish);
			}
		},
		//Datetime pickers.
		Datetime: {
			init: function(){
				$('.input.datetimeui :text').datetimepicker({
					dateFormat:"yy-mm-dd",
					timeFormat:"hh:mm:ss"
				});
				$('.input.timeui :text').timepicker();
			}
		}
	};
	/**
	 * MagicInit function
	 **/
	var __init__ = function () {
		if(this.beforeAutoInit && $.isFunction(this.beforeAutoInit)) this.beforeAutoInit();
		for(var i in this){
			//if i is a module and init fn exists
			if($.isPlainObject(this[i]) && $.isFunction(this[i].init)){
				if(
					//init will run autoInit property is not defined
					this[i].autoInit == undefined || 
					(
						//if autoInit is defined
						this[i].autoInit != undefined &&
						(
							//if autoInit is a function execute it and see what it says.
							($.isFunction(this[i].autoInit) && this[i].autoInit()) ||
							//if autoInit is a boolean property, check if it is true or not.
							($.type(this[i].autoInit) === 'boolean' && this[i].autoInit)
						)
					)
				){
					this[i].init();
				}
			}
		}
		if(this.afterAutoInit && $.isFunction(this.afterAutoInit)) this.afterAutoInit();
	};

    var obj = {
		'__init__': __init__,
        init: function () {
			priv.Nav.init();
			priv.Datetime.init();
			this.__init__();
        }
	};

    return obj;
}();

$(document).ready(function () {
    TeamMaker.init();
});