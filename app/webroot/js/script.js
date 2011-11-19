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
				$('#status').hide().html(data);
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
					$("div.importStatus").slideUp('medium', function(){
						$('#status').slideDown();
					});
				}
			}
		},
		/**
		 * Module for creation of skills.
		 */
		skillsForm: {
			$me: false,
			$tmpl: false,
			$container: false,
			/**
			 * Whether or not to init the module.
			 */
			autoInit: function(){
				this.$me = $("#skillsForm");
				return $("#skillsForm").length;
			},
			/**
			 * Init the module.
			 */
			init: function(){
				this.$tmpl = $('#skillTemplate');
				this.$container = this.$me.find('div.skills');
				this.$me.delegate('a.add', 'click', $.proxy(this.addNew, this));
				this.dataInit();
			},
			/**
			 * This function will check if data exists, if it does, will populate data, if not, will show you a blank one.
			 */
			dataInit: function(){
				if(TeamMaker.skillData){
					
				}else{
					this.addNew();
				}
				this.$container.delegate('div.skill select', 'change', $.proxy(this.onSelectChange, this));
				this.$container.delegate('div.numericRange input', 'change', $.proxy(this.onNumericRangeChange, this));
				this.$container.delegate('div.textRange input', 'change', $.proxy(this.onTextRangeChange, this));
				this.$container.delegate('div.text input', 'change', $.proxy(this.onTextChange, this));
			},
			/**
			 * This function will creat
			 */
			addNew: function(e){
				if(e) e.preventDefault();
				//clone
				var $clone = this.$tmpl.children().first().clone();
				//get index
				var myIndex = this.$container.children().length;
				//get the item to change stuffs.
				var $select = $clone
					.attr('data-index', myIndex)
					.find('select');
				
				//change some val
				$.each(['name', 'id'], function(i, val){
					$select.attr(val, $select.attr(val).replace('${i}', myIndex));
				});
				//apend
				this.$container.append($clone);
			},
			/**
			 * Will execute on changing a skill value type drop-down menu
			 */
			onSelectChange: function(e){
				var $me = $(e.target);
				var myIndex = $me.closest('div.skill').attr('data-index');
				var $container = $me.closest('div.skill').find('div.skillOption');
				var choice = parseInt($me.val());
				
				var cloneStr = '';
				//ref to my pub obj
				var pub = TeamMaker.skillsForm;
				
				//use it as string instead.
				cloneStr = $(pub.tmpl[choice]).html();
				//substitute placeholders
				$.each(
					[{pattern: /\${i}/g, val: myIndex}], 
					function(i, o){
						cloneStr = cloneStr.replace(o.pattern, o.val);
					}
				);
				switch(choice){
					case pub.constants.NUMERIC_RANGE:
						break;
					case pub.constants.TEXT_RANGE:
						break;
					case pub.constants.TEXT:
						break; 
				}
				
				//populate container
				$container.html(cloneStr);
			},
			/**
			 * Validate the numeric range input data.
			 */
			onNumericRangeChange: function(e){
				var $parent = $(e.target).closest('div.colContainer');
				//query fields in question
				var fields = {
					$min : $parent.find('input.min').first().removeData('error'),
					$max : $parent.find('input.max').first().removeData('error'),
					$range : $parent.find('input.range').first()
				};
				var minVal = priv.Validate.isNumber(fields.$min.val());
				var maxVal = priv.Validate.isNumber(fields.$max.val());
				
				//validate min or max value.
				if(minVal === false){
					fields.$min.data('error', true);
				}
				if(maxVal === false){
					fields.$max.data('error', true);
				}
				
				if(minVal >= maxVal){//min is larger or equal to max
					fields.$max.data('error', true);
				}else{
					fields.$max.data('error', fields.$max.data('error') || false);
				}
				
				$.each(fields, function(i, $el){
					if($el.data('error')){
						$el.addClass('error');
					}else{
						$el.removeClass('error');
					}
				});
				
				//doesn't matter if any of 'em got an error or not, just update the value.
				fields.$range.val(fields.$min.val() + "-" + fields.$max.val());
			},
			/**
			 * Validate the text range input data
			 */
			onTextRangeChange: function(e){
				var $me = $(e.target).removeData('error');
				var val = $me.val();
				if(val.split('|').length <= 1){
					$me.addClass('error').data('error', true);
				}else{
					$me.removeClass('error').data('error', false);
				}
			},
			/**
			 * Validate the text input data.
			 */
			onTextChange: function(e){
				var $me = $(e.target).removeData('error');
				var val = priv.Validate.isInt($me.val());
				if(val !== false){
					$me.removeClass("error").data('error', false);
				}else{
					$me.addClass('error').data('error', true);
				}
			}
		},
		Validate: {
			isEmpty: function(val){
				return val.length == 0;
			},
			isInt: function(val){
				if(this.isEmpty(val)) return false;
				
				if(val.match(/^[\d]*$/)){
					var num = parseInt(val);
					if(!isNaN(num)){
						return num;
					}
				}
				return false;
			},
			isNumber: function(val){
				if(this.isEmpty(val)) return false;
				
				//do a check of float/int.
				if(this.isInt(val) !== false){
					return this.isInt(val);
				}else{
					var flt = parseFloat(val);
					if(!isNaN(flt)){
						return flt;
					}			
				}
								
				return false;
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