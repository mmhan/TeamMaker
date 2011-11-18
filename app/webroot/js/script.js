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
			//checks whether or not to init module.
			formId: '#ProjectAdminAddMembersForm',
			$form: false,
			autoInit: function(){
				this.$form = $(this.formId);
				return this.$form.length;
			},
			//initialize the module.
			init: function(){
				$('tr.importFields')
					.delegate('select.memberImportActions', 'change', $.proxy(this.actionChange, this));
				this.$form.submit($.proxy(this.formSubmit, this));
			},
			//action listener to change event of select dropdowns
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
			//will show a list of importable fields from user table.
			mapFieldModifySelect: function($container, prefix){
				var $select = $("<select>").attr('name', prefix + "[maps_to]");
				$.each(TeamMaker.UserFields, function(i, val){
					$select.append(
						$("<option>").attr('value', i).text(val)
					);
				});
				$container.empty().append($select);
			},
			//will show a list of importable skills
			isSkillModifySelect: function($container, prefix){
				$container.empty();
			},
			//will not show anything as option.
			discardModifySelect: function($container){
				$container.empty();
			},
			//will submit the form using ajax while checking for updates.
			formSubmit: function(e){
				//hides form and show status
				$('div.importForm').slideUp('medium', function(){
					$('div.importStatus').slideDown();
				});
				//submit with ajax
				$.ajax({
					url: this.$form.attr('action'),
					type: 'POST',
					dataType: 'html',
					data: this.$form.serialize(),
					timeout: 5 * 60 * 1000, //5 minutes
					context: this,
					success: this.formSubmitSuccess
				});
				//periodically, check for status with ajax
				this.timeoutID = window.setInterval($.proxy(this.formSubmitStatusCheck, this), 2000);
				e.preventDefault();
				return false;
			},
			//will execute when the form has been successfully submitted.
			formSubmitSuccess: function(data){
				var $statusDiv = $('#status').hide().html(data);
				$("#progressIndicator").slideUp('medium', function(){
					$statusDiv.slideDown();
				});
			},
			//will check for status of form submission.
			formSubmitStatusCheck: function(){
				$.ajax({
					url: this.$form.attr('data-status'),
					type: 'POST',
					dataType: 'json',
					context: this,
					success: this.formSubmitStatusSuccess
				});
			},
			//will check for success of form submission.
			formSubmitStatusSuccess: function(data){
				var $bar = $('#progressIndicator');
				var progress = data.progress / data.total * 100;
				$bar.find(".progress").css('width', progress + "%");
				$bar.find("#progressTxt").text(data.progress + "/" + data.total);
				
				if(data.progress >= data.total){
					window.clearTimeout(this.timeoutID);
				}
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