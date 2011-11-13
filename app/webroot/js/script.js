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
	/**
	 * Private modules that doesn't need to be exposed
	 **/
	var priv = {
		init: __init__,
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
					timeFormat:"hh:mm:ss",
					stepMinute: 10
				});
				$('.input.timeui :text').timepicker();
			}
		},
		/* HABTM select using chosen */
		HabtmSelector:{
			init: function(){
				$('.input.habtmSelector select').chosen();
			}
		},
		/** scripts to handle the actions of importing fields **/
		Import:{
			autoInit: function(){
				return $('#ProjectAdminAddMembersForm').length;
			},
			init: function(){
				$('tr.importFields')
					.delegate('select.memberImportActions', 'change', $.proxy(this.actionChange, this));
			},
			actionChange: function(e){
				var $me = $(e.target);
				var $myTr = $(e.target).closest('tr');
				
				var choice = $me.val();
				var index = $myTr.attr('data-index');
				this[choice + 'ModifySelect'].call(this, 
					$myTr.find('.actionOptionsContainer'), 
					'data[Import][' + index + "]"
				);
			},
			mapFieldModifySelect: function($container, prefix){
				var $select = $("<select>").attr('name', prefix + "[maps_to]");
				$.each(TeamMaker.UserFields, function(i, val){
					$select.append(
						$("<option>").attr('value', i).text(val)
					);
				});
				$container.empty().append($select);
			},
			isSkillModifySelect: function($container, prefix){
				$container.empty();
			},
			discardModifySelect: function($container){
				$container.empty();
			}
		}
	};
	

    var obj = {
		'__init__' : __init__,
		init: function(){
			priv.init();
			this.__init__();
		}
	};
	$.extend(obj, TeamMaker);
    return obj;
}();

$(document).ready(function () {
    TeamMaker.init();
});