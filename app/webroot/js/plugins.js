
// remap jQuery to $
(function($){

 





 



})(window.jQuery);



// usage: log('inside coolFunc',this,arguments);
// paulirish.com/2009/log-a-lightweight-wrapper-for-consolelog/
window.log = function(){
  log.history = log.history || [];   // store logs to an array for reference
  log.history.push(arguments);
  if(this.console){
    console.log( Array.prototype.slice.call(arguments) );
  }
};



// catch all document.write() calls
(function(doc){
  var write = doc.write;
  doc.write = function(q){ 
    log('document.write(): ',arguments); 
    if (/docwriteregexwhitelist/.test(q)) write.apply(doc,arguments);  
  };
})(document);

/**
* hoverIntent r5 // 2007.03.27 // jQuery 1.1.2
* <http://cherne.net/brian/resources/jquery.hoverIntent.html>
* 
* @param  f  onMouseOver function || An object with configuration options
* @param  g  onMouseOut function  || Nothing (use configuration options object)
* @return    The object (aka "this") that called hoverIntent, and the event object
* @author    Brian Cherne <brian@cherne.net>
*/
;(function($){$.fn.hoverIntent=function(f,g){var cfg={sensitivity:7,interval:100,timeout:0};cfg=$.extend(cfg,g?{over:f,out:g}:f);var cX,cY,pX,pY;var track=function(ev){cX=ev.pageX;cY=ev.pageY;};var compare=function(ev,ob){ob.hoverIntent_t=clearTimeout(ob.hoverIntent_t);if((Math.abs(pX-cX)+Math.abs(pY-cY))<cfg.sensitivity){$(ob).unbind("mousemove",track);ob.hoverIntent_s=1;return cfg.over.apply(ob,[ev]);}else{pX=cX;pY=cY;ob.hoverIntent_t=setTimeout(function(){compare(ev,ob);},cfg.interval);}};var delay=function(ev,ob){ob.hoverIntent_t=clearTimeout(ob.hoverIntent_t);ob.hoverIntent_s=0;return cfg.out.apply(ob,[ev]);};var handleHover=function(e){var p=(e.type=="mouseover"?e.fromElement:e.toElement)||e.relatedTarget;while(p&&p!=this){try{p=p.parentNode;}catch(e){p=this;}}if(p==this){return false;}var ev=jQuery.extend({},e);var ob=this;if(ob.hoverIntent_t){ob.hoverIntent_t=clearTimeout(ob.hoverIntent_t);}if(e.type=="mouseover"){pX=ev.pageX;pY=ev.pageY;$(ob).bind("mousemove",track);if(ob.hoverIntent_s!=1){ob.hoverIntent_t=setTimeout(function(){compare(ev,ob);},cfg.interval);}}else{$(ob).unbind("mousemove",track);if(ob.hoverIntent_s==1){ob.hoverIntent_t=setTimeout(function(){delay(ev,ob);},cfg.timeout);}}};return this.mouseover(handleHover).mouseout(handleHover);};})(jQuery);



/*
 * Superfish v1.4.8 - jQuery menu widget
 * Copyright (c) 2008 Joel Birch
 *
 * Dual licensed under the MIT and GPL licenses:
 * 	http://www.opensource.org/licenses/mit-license.php
 * 	http://www.gnu.org/licenses/gpl.html
 *
 * CHANGELOG: http://users.tpg.com.au/j_birch/plugins/superfish/changelog.txt
 */

;(function($){
	$.fn.superfish = function(op){

		var sf = $.fn.superfish,
			c = sf.c,
			$arrow = $(['<span class="',c.arrowClass,'"> &#187;</span>'].join('')),
			over = function(){
				var $$ = $(this), menu = getMenu($$);
				clearTimeout(menu.sfTimer);
				$$.showSuperfishUl().siblings().hideSuperfishUl();
			},
			out = function(){
				var $$ = $(this), menu = getMenu($$), o = sf.op;
				clearTimeout(menu.sfTimer);
				menu.sfTimer=setTimeout(function(){
					o.retainPath=($.inArray($$[0],o.$path)>-1);
					$$.hideSuperfishUl();
					if (o.$path.length && $$.parents(['li.',o.hoverClass].join('')).length<1){over.call(o.$path);}
				},o.delay);	
			},
			getMenu = function($menu){
				var menu = $menu.parents(['ul.',c.menuClass,':first'].join(''))[0];
				sf.op = sf.o[menu.serial];
				return menu;
			},
			addArrow = function($a){ $a.addClass(c.anchorClass).append($arrow.clone()); };
			
		return this.each(function() {
			var s = this.serial = sf.o.length;
			var o = $.extend({},sf.defaults,op);
			o.$path = $('li.'+o.pathClass,this).slice(0,o.pathLevels).each(function(){
				$(this).addClass([o.hoverClass,c.bcClass].join(' '))
					.filter('li:has(ul)').removeClass(o.pathClass);
			});
			sf.o[s] = sf.op = o;
			
			$('li:has(ul)',this)[($.fn.hoverIntent && !o.disableHI) ? 'hoverIntent' : 'hover'](over,out).each(function() {
				if (o.autoArrows) addArrow( $('>a:first-child',this) );
			})
			.not('.'+c.bcClass)
				.hideSuperfishUl();
			
			var $a = $('a',this);
			$a.each(function(i){
				var $li = $a.eq(i).parents('li');
				$a.eq(i).focus(function(){over.call($li);}).blur(function(){out.call($li);});
			});
			o.onInit.call(this);
			
		}).each(function() {
			var menuClasses = [c.menuClass];
			if (sf.op.dropShadows  && !($.browser.msie && $.browser.version < 7)) menuClasses.push(c.shadowClass);
			$(this).addClass(menuClasses.join(' '));
		});
	};

	var sf = $.fn.superfish;
	sf.o = [];
	sf.op = {};
	sf.IE7fix = function(){
		var o = sf.op;
		if ($.browser.msie && $.browser.version > 6 && o.dropShadows && o.animation.opacity!=undefined)
			this.toggleClass(sf.c.shadowClass+'-off');
		};
	sf.c = {
		bcClass     : 'sf-breadcrumb',
		menuClass   : 'sf-js-enabled',
		anchorClass : 'sf-with-ul',
		arrowClass  : 'sf-sub-indicator',
		shadowClass : 'sf-shadow'
	};
	sf.defaults = {
		hoverClass	: 'sfHover',
		pathClass	: 'overideThisToUse',
		pathLevels	: 1,
		delay		: 800,
		animation	: {opacity:'show'},
		speed		: 'normal',
		autoArrows	: true,
		dropShadows : true,
		disableHI	: false,		// true disables hoverIntent detection
		onInit		: function(){}, // callback functions
		onBeforeShow: function(){},
		onShow		: function(){},
		onHide		: function(){}
	};
	$.fn.extend({
		hideSuperfishUl : function(){
			var o = sf.op,
				not = (o.retainPath===true) ? o.$path : '';
			o.retainPath = false;
			var $ul = $(['li.',o.hoverClass].join(''),this).add(this).not(not).removeClass(o.hoverClass)
					.find('>ul').hide().css('visibility','hidden');
			o.onHide.call($ul);
			return this;
		},
		showSuperfishUl : function(){
			var o = sf.op,
				sh = sf.c.shadowClass+'-off',
				$ul = this.addClass(o.hoverClass)
					.find('>ul:hidden').css('visibility','visible');
			sf.IE7fix.call($ul);
			o.onBeforeShow.call($ul);
			$ul.animate(o.animation,o.speed,function(){ sf.IE7fix.call($ul); o.onShow.call($ul); });
			return this;
		}
	});

})(jQuery);


/*
 * Supersubs v0.2b - jQuery plugin
 * Copyright (c) 2008 Joel Birch
 *
 * Dual licensed under the MIT and GPL licenses:
 * 	http://www.opensource.org/licenses/mit-license.php
 * 	http://www.gnu.org/licenses/gpl.html
 *
 *
 * This plugin automatically adjusts submenu widths of suckerfish-style menus to that of
 * their longest list item children. If you use this, please expect bugs and report them
 * to the jQuery Google Group with the word 'Superfish' in the subject line.
 *
 */

;(function(a){a.fn.superfish=function(b){var c=a.fn.superfish,d=c.c,e=a(['<span class="',d.arrowClass,'"> &#187;</span>'].join("")),f=function(){var b=a(this),c=h(b);clearTimeout(c.sfTimer);b.showSuperfishUl().siblings().hideSuperfishUl()},g=function(){var b=a(this),d=h(b),e=c.op;clearTimeout(d.sfTimer);d.sfTimer=setTimeout(function(){e.retainPath=a.inArray(b[0],e.$path)>-1;b.hideSuperfishUl();if(e.$path.length&&b.parents(["li.",e.hoverClass].join("")).length<1){f.call(e.$path)}},e.delay)},h=function(a){var b=a.parents(["ul.",d.menuClass,":first"].join(""))[0];c.op=c.o[b.serial];return b},i=function(a){a.addClass(d.anchorClass).append(e.clone())};return this.each(function(){var e=this.serial=c.o.length;var h=a.extend({},c.defaults,b);h.$path=a("li."+h.pathClass,this).slice(0,h.pathLevels).each(function(){a(this).addClass([h.hoverClass,d.bcClass].join(" ")).filter("li:has(ul)").removeClass(h.pathClass)});c.o[e]=c.op=h;a("li:has(ul)",this)[a.fn.hoverIntent&&!h.disableHI?"hoverIntent":"hover"](f,g).each(function(){if(h.autoArrows)i(a(">a:first-child",this))}).not("."+d.bcClass).hideSuperfishUl();var j=a("a",this);j.each(function(a){var b=j.eq(a).parents("li");j.eq(a).focus(function(){f.call(b)}).blur(function(){g.call(b)})});h.onInit.call(this)}).each(function(){var b=[d.menuClass];if(c.op.dropShadows&&!(a.browser.msie&&a.browser.version<7))b.push(d.shadowClass);a(this).addClass(b.join(" "))})};var b=a.fn.superfish;b.o=[];b.op={};b.IE7fix=function(){var c=b.op;if(a.browser.msie&&a.browser.version>6&&c.dropShadows&&c.animation.opacity!=undefined)this.toggleClass(b.c.shadowClass+"-off")};b.c={bcClass:"sf-breadcrumb",menuClass:"sf-js-enabled",anchorClass:"sf-with-ul",arrowClass:"sf-sub-indicator",shadowClass:"sf-shadow"};b.defaults={hoverClass:"sfHover",pathClass:"overideThisToUse",pathLevels:1,delay:800,animation:{opacity:"show"},speed:"normal",autoArrows:true,dropShadows:true,disableHI:false,onInit:function(){},onBeforeShow:function(){},onShow:function(){},onHide:function(){}};a.fn.extend({hideSuperfishUl:function(){var c=b.op,d=c.retainPath===true?c.$path:"";c.retainPath=false;var e=a(["li.",c.hoverClass].join(""),this).add(this).not(d).removeClass(c.hoverClass).find(">ul").hide().css("visibility","hidden");c.onHide.call(e);return this},showSuperfishUl:function(){var a=b.op,c=b.c.shadowClass+"-off",d=this.addClass(a.hoverClass).find(">ul:hidden").css("visibility","visible");b.IE7fix.call(d);a.onBeforeShow.call(d);d.animate(a.animation,a.speed,function(){b.IE7fix.call(d);a.onShow.call(d)});return this}})})(jQuery);(function(a){a.fn.supersubs=function(b){var c=a.extend({},a.fn.supersubs.defaults,b);return this.each(function(){var b=a(this);var d=a.meta?a.extend({},c,b.data()):c;var e=a('<li id="menu-fontsize">&#8212;</li>').css({padding:0,position:"absolute",top:"-999em",width:"auto"}).appendTo(b).width();a("#menu-fontsize").remove();$ULs=b.find("ul");$ULs.each(function(b){var c=$ULs.eq(b);var f=c.children();var g=f.children("a");var h=f.css("white-space","nowrap").css("float");var i=c.add(f).add(g).css({"float":"none",width:"auto"}).end().end()[0].clientWidth/e;i+=d.extraWidth;if(i>d.maxWidth){i=d.maxWidth}else if(i<d.minWidth){i=d.minWidth}i+="em";c.css("width",i);f.css({"float":h,width:"100%","white-space":"normal"}).each(function(){var b=a(">ul",this);var c=b.css("left")!==undefined?"left":"right";b.css(c,i)})})})};a.fn.supersubs.defaults={minWidth:9,maxWidth:25,extraWidth:0}})(jQuery);// plugin code ends


/*
* jQuery timepicker addon
* By: Trent Richardson [http://trentrichardson.com]
* Version 0.9.7
* Last Modified: 10/02/2011
* 
* Copyright 2011 Trent Richardson
* Dual licensed under the MIT and GPL licenses.
* http://trentrichardson.com/Impromptu/GPL-LICENSE.txt
* http://trentrichardson.com/Impromptu/MIT-LICENSE.txt
* 
* HERES THE CSS:
* .ui-timepicker-div .ui-widget-header { margin-bottom: 8px; }
* .ui-timepicker-div dl { text-align: left; }
* .ui-timepicker-div dl dt { height: 25px; }
* .ui-timepicker-div dl dd { margin: -25px 10px 10px 65px; }
* .ui-timepicker-div td { font-size: 90%; }
* .ui-tpicker-grid-label { background: none; border: none; margin: 0; padding: 0; }
*/

;(function($){function extendRemove(a,b){$.extend(a,b);for(var c in b)if(b[c]===null||b[c]===undefined)a[c]=b[c];return a}function Timepicker(){this.regional=[];this.regional[""]={currentText:"Now",closeText:"Done",ampm:false,amNames:["AM","A"],pmNames:["PM","P"],timeFormat:"hh:mm tt",timeSuffix:"",timeOnlyTitle:"Choose Time",timeText:"Time",hourText:"Hour",minuteText:"Minute",secondText:"Second",millisecText:"Millisecond",timezoneText:"Time Zone"};this._defaults={showButtonPanel:true,timeOnly:false,showHour:true,showMinute:true,showSecond:false,showMillisec:false,showTimezone:false,showTime:true,stepHour:.05,stepMinute:.05,stepSecond:.05,stepMillisec:.5,hour:0,minute:0,second:0,millisec:0,timezone:"+0000",hourMin:0,minuteMin:0,secondMin:0,millisecMin:0,hourMax:23,minuteMax:59,secondMax:59,millisecMax:999,minDateTime:null,maxDateTime:null,onSelect:null,hourGrid:0,minuteGrid:0,secondGrid:0,millisecGrid:0,alwaysSetTime:true,separator:" ",altFieldTimeOnly:true,showTimepicker:true,timezoneIso8609:false,timezoneList:null};$.extend(this._defaults,this.regional[""])}$.extend($.ui,{timepicker:{version:"0.9.7"}});$.extend(Timepicker.prototype,{$input:null,$altInput:null,$timeObj:null,inst:null,hour_slider:null,minute_slider:null,second_slider:null,millisec_slider:null,timezone_select:null,hour:0,minute:0,second:0,millisec:0,timezone:"+0000",hourMinOriginal:null,minuteMinOriginal:null,secondMinOriginal:null,millisecMinOriginal:null,hourMaxOriginal:null,minuteMaxOriginal:null,secondMaxOriginal:null,millisecMaxOriginal:null,ampm:"",formattedDate:"",formattedTime:"",formattedDateTime:"",timezoneList:null,setDefaults:function(a){extendRemove(this._defaults,a||{});return this},_newInst:function($input,o){var tp_inst=new Timepicker,inlineSettings={};for(var attrName in this._defaults){var attrValue=$input.attr("time:"+attrName);if(attrValue){try{inlineSettings[attrName]=eval(attrValue)}catch(err){inlineSettings[attrName]=attrValue}}}tp_inst._defaults=$.extend({},this._defaults,inlineSettings,o,{beforeShow:function(a,b){if($.isFunction(o.beforeShow))o.beforeShow(a,b,tp_inst)},onChangeMonthYear:function(a,b,c){tp_inst._updateDateTime(c);if($.isFunction(o.onChangeMonthYear))o.onChangeMonthYear.call($input[0],a,b,c,tp_inst)},onClose:function(a,b){if(tp_inst.timeDefined===true&&$input.val()!="")tp_inst._updateDateTime(b);if($.isFunction(o.onClose))o.onClose.call($input[0],a,b,tp_inst)},timepicker:tp_inst});tp_inst.amNames=$.map(tp_inst._defaults.amNames,function(a){return a.toUpperCase()});tp_inst.pmNames=$.map(tp_inst._defaults.pmNames,function(a){return a.toUpperCase()});if(tp_inst._defaults.timezoneList===null){var timezoneList=[];for(var i=-11;i<=12;i++)timezoneList.push((i>=0?"+":"-")+("0"+Math.abs(i).toString()).slice(-2)+"00");if(tp_inst._defaults.timezoneIso8609)timezoneList=$.map(timezoneList,function(a){return a=="+0000"?"Z":a.substring(0,3)+":"+a.substring(3)});tp_inst._defaults.timezoneList=timezoneList}tp_inst.hour=tp_inst._defaults.hour;tp_inst.minute=tp_inst._defaults.minute;tp_inst.second=tp_inst._defaults.second;tp_inst.millisec=tp_inst._defaults.millisec;tp_inst.ampm="";tp_inst.$input=$input;if(o.altField)tp_inst.$altInput=$(o.altField).css({cursor:"pointer"}).focus(function(){$input.trigger("focus")});if(tp_inst._defaults.minDate==0||tp_inst._defaults.minDateTime==0){tp_inst._defaults.minDate=new Date}if(tp_inst._defaults.maxDate==0||tp_inst._defaults.maxDateTime==0){tp_inst._defaults.maxDate=new Date}if(tp_inst._defaults.minDate!==undefined&&tp_inst._defaults.minDate instanceof Date)tp_inst._defaults.minDateTime=new Date(tp_inst._defaults.minDate.getTime());if(tp_inst._defaults.minDateTime!==undefined&&tp_inst._defaults.minDateTime instanceof Date)tp_inst._defaults.minDate=new Date(tp_inst._defaults.minDateTime.getTime());if(tp_inst._defaults.maxDate!==undefined&&tp_inst._defaults.maxDate instanceof Date)tp_inst._defaults.maxDateTime=new Date(tp_inst._defaults.maxDate.getTime());if(tp_inst._defaults.maxDateTime!==undefined&&tp_inst._defaults.maxDateTime instanceof Date)tp_inst._defaults.maxDate=new Date(tp_inst._defaults.maxDateTime.getTime());return tp_inst},_addTimePicker:function(a){var b=this.$altInput&&this._defaults.altFieldTimeOnly?this.$input.val()+" "+this.$altInput.val():this.$input.val();this.timeDefined=this._parseTime(b);this._limitMinMaxDateTime(a,false);this._injectTimePicker()},_parseTime:function(a,b){var c=this._defaults.timeFormat.toString().replace(/h{1,2}/ig,"(\\d?\\d)").replace(/m{1,2}/ig,"(\\d?\\d)").replace(/s{1,2}/ig,"(\\d?\\d)").replace(/l{1}/ig,"(\\d?\\d?\\d)").replace(/t{1,2}/ig,this._getPatternAmpm()).replace(/z{1}/ig,"(z|[-+]\\d\\d:?\\d\\d)?").replace(/\s/g,"\\s?")+this._defaults.timeSuffix+"$",d=this._getFormatPositions(),e="",f;if(!this.inst)this.inst=$.datepicker._getInst(this.$input[0]);if(b||!this._defaults.timeOnly){var g=$.datepicker._get(this.inst,"dateFormat");var h=new RegExp("[.*+?|()\\[\\]{}\\\\]","g");c=".{"+g.length+",}"+this._defaults.separator.replace(h,"\\$&")+c}f=a.match(new RegExp(c,"i"));if(f){if(d.t!==-1){if(f[d.t]===undefined||f[d.t].length===0){e="";this.ampm=""}else{e=$.inArray(f[d.t].toUpperCase(),this.amNames)!==-1?"AM":"PM";this.ampm=this._defaults[e=="AM"?"amNames":"pmNames"][0]}}if(d.h!==-1){if(e=="AM"&&f[d.h]=="12")this.hour=0;else if(e=="PM"&&f[d.h]!="12")this.hour=(parseFloat(f[d.h])+12).toFixed(0);else this.hour=Number(f[d.h])}if(d.m!==-1)this.minute=Number(f[d.m]);if(d.s!==-1)this.second=Number(f[d.s]);if(d.l!==-1)this.millisec=Number(f[d.l]);if(d.z!==-1&&f[d.z]!==undefined){var i=f[d.z].toUpperCase();switch(i.length){case 1:i=this._defaults.timezoneIso8609?"Z":"+0000";break;case 5:if(this._defaults.timezoneIso8609)i=i.substring(1)=="0000"?"Z":i.substring(0,3)+":"+i.substring(3);break;case 6:if(!this._defaults.timezoneIso8609)i=i=="Z"||i.substring(1)=="00:00"?"+0000":i.replace(/:/,"");else if(i.substring(1)=="00:00")i="Z";break}this.timezone=i}return true}return false},_getPatternAmpm:function(){var a=[];o=this._defaults;if(o.amNames)$.merge(a,o.amNames);if(o.pmNames)$.merge(a,o.pmNames);a=$.map(a,function(a){return a.replace(/[.*+?|()\[\]{}\\]/g,"\\$&")});return"("+a.join("|")+")?"},_getFormatPositions:function(){var a=this._defaults.timeFormat.toLowerCase().match(/(h{1,2}|m{1,2}|s{1,2}|l{1}|t{1,2}|z)/g),b={h:-1,m:-1,s:-1,l:-1,t:-1,z:-1};if(a)for(var c=0;c<a.length;c++)if(b[a[c].toString().charAt(0)]==-1)b[a[c].toString().charAt(0)]=c+1;return b},_injectTimePicker:function(){var a=this.inst.dpDiv,b=this._defaults,c=this,d=(b.hourMax-(b.hourMax-b.hourMin)%b.stepHour).toFixed(0),e=(b.minuteMax-(b.minuteMax-b.minuteMin)%b.stepMinute).toFixed(0),f=(b.secondMax-(b.secondMax-b.secondMin)%b.stepSecond).toFixed(0),g=(b.millisecMax-(b.millisecMax-b.millisecMin)%b.stepMillisec).toFixed(0),h=this.inst.id.toString().replace(/([^A-Za-z0-9_])/g,"");if(a.find("div#ui-timepicker-div-"+h).length===0&&b.showTimepicker){var i=' style="display:none;"',j='<div class="ui-timepicker-div" id="ui-timepicker-div-'+h+'"><dl>'+'<dt class="ui_tpicker_time_label" id="ui_tpicker_time_label_'+h+'"'+(b.showTime?"":i)+">"+b.timeText+"</dt>"+'<dd class="ui_tpicker_time" id="ui_tpicker_time_'+h+'"'+(b.showTime?"":i)+"></dd>"+'<dt class="ui_tpicker_hour_label" id="ui_tpicker_hour_label_'+h+'"'+(b.showHour?"":i)+">"+b.hourText+"</dt>",k=0,l=0,m=0,n=0,o;if(b.showHour&&b.hourGrid>0){j+='<dd class="ui_tpicker_hour">'+'<div id="ui_tpicker_hour_'+h+'"'+(b.showHour?"":i)+"></div>"+'<div style="padding-left: 1px"><table class="ui-tpicker-grid-label"><tr>';for(var p=b.hourMin;p<=d;p+=parseInt(b.hourGrid,10)){k++;var q=b.ampm&&p>12?p-12:p;if(q<10)q="0"+q;if(b.ampm){if(p==0)q=12+"a";else if(p<12)q+="a";else q+="p"}j+="<td>"+q+"</td>"}j+="</tr></table></div>"+"</dd>"}else j+='<dd class="ui_tpicker_hour" id="ui_tpicker_hour_'+h+'"'+(b.showHour?"":i)+"></dd>";j+='<dt class="ui_tpicker_minute_label" id="ui_tpicker_minute_label_'+h+'"'+(b.showMinute?"":i)+">"+b.minuteText+"</dt>";if(b.showMinute&&b.minuteGrid>0){j+='<dd class="ui_tpicker_minute ui_tpicker_minute_'+b.minuteGrid+'">'+'<div id="ui_tpicker_minute_'+h+'"'+(b.showMinute?"":i)+"></div>"+'<div style="padding-left: 1px"><table class="ui-tpicker-grid-label"><tr>';for(var r=b.minuteMin;r<=e;r+=parseInt(b.minuteGrid,10)){l++;j+="<td>"+(r<10?"0":"")+r+"</td>"}j+="</tr></table></div>"+"</dd>"}else j+='<dd class="ui_tpicker_minute" id="ui_tpicker_minute_'+h+'"'+(b.showMinute?"":i)+"></dd>";j+='<dt class="ui_tpicker_second_label" id="ui_tpicker_second_label_'+h+'"'+(b.showSecond?"":i)+">"+b.secondText+"</dt>";if(b.showSecond&&b.secondGrid>0){j+='<dd class="ui_tpicker_second ui_tpicker_second_'+b.secondGrid+'">'+'<div id="ui_tpicker_second_'+h+'"'+(b.showSecond?"":i)+"></div>"+'<div style="padding-left: 1px"><table><tr>';for(var s=b.secondMin;s<=f;s+=parseInt(b.secondGrid,10)){m++;j+="<td>"+(s<10?"0":"")+s+"</td>"}j+="</tr></table></div>"+"</dd>"}else j+='<dd class="ui_tpicker_second" id="ui_tpicker_second_'+h+'"'+(b.showSecond?"":i)+"></dd>";j+='<dt class="ui_tpicker_millisec_label" id="ui_tpicker_millisec_label_'+h+'"'+(b.showMillisec?"":i)+">"+b.millisecText+"</dt>";if(b.showMillisec&&b.millisecGrid>0){j+='<dd class="ui_tpicker_millisec ui_tpicker_millisec_'+b.millisecGrid+'">'+'<div id="ui_tpicker_millisec_'+h+'"'+(b.showMillisec?"":i)+"></div>"+'<div style="padding-left: 1px"><table><tr>';for(var t=b.millisecMin;t<=g;t+=parseInt(b.millisecGrid,10)){n++;j+="<td>"+(t<10?"0":"")+s+"</td>"}j+="</tr></table></div>"+"</dd>"}else j+='<dd class="ui_tpicker_millisec" id="ui_tpicker_millisec_'+h+'"'+(b.showMillisec?"":i)+"></dd>";j+='<dt class="ui_tpicker_timezone_label" id="ui_tpicker_timezone_label_'+h+'"'+(b.showTimezone?"":i)+">"+b.timezoneText+"</dt>";j+='<dd class="ui_tpicker_timezone" id="ui_tpicker_timezone_'+h+'"'+(b.showTimezone?"":i)+"></dd>";j+="</dl></div>";$tp=$(j);if(b.timeOnly===true){$tp.prepend('<div class="ui-widget-header ui-helper-clearfix ui-corner-all">'+'<div class="ui-datepicker-title">'+b.timeOnlyTitle+"</div>"+"</div>");a.find(".ui-datepicker-header, .ui-datepicker-calendar").hide()}this.hour_slider=$tp.find("#ui_tpicker_hour_"+h).slider({orientation:"horizontal",value:this.hour,min:b.hourMin,max:d,step:b.stepHour,slide:function(a,b){c.hour_slider.slider("option","value",b.value);c._onTimeChange()}});this.minute_slider=$tp.find("#ui_tpicker_minute_"+h).slider({orientation:"horizontal",value:this.minute,min:b.minuteMin,max:e,step:b.stepMinute,slide:function(a,b){c.minute_slider.slider("option","value",b.value);c._onTimeChange()}});this.second_slider=$tp.find("#ui_tpicker_second_"+h).slider({orientation:"horizontal",value:this.second,min:b.secondMin,max:f,step:b.stepSecond,slide:function(a,b){c.second_slider.slider("option","value",b.value);c._onTimeChange()}});this.millisec_slider=$tp.find("#ui_tpicker_millisec_"+h).slider({orientation:"horizontal",value:this.millisec,min:b.millisecMin,max:g,step:b.stepMillisec,slide:function(a,b){c.millisec_slider.slider("option","value",b.value);c._onTimeChange()}});this.timezone_select=$tp.find("#ui_tpicker_timezone_"+h).append("<select></select>").find("select");$.fn.append.apply(this.timezone_select,$.map(b.timezoneList,function(a,b){return $("<option />").val(typeof a=="object"?a.value:a).text(typeof a=="object"?a.label:a)}));this.timezone_select.val(typeof this.timezone!="undefined"&&this.timezone!=null&&this.timezone!=""?this.timezone:b.timezone);this.timezone_select.change(function(){c._onTimeChange()});if(b.showHour&&b.hourGrid>0){o=100*k*b.hourGrid/(d-b.hourMin);$tp.find(".ui_tpicker_hour table").css({width:o+"%",marginLeft:o/(-2*k)+"%",borderCollapse:"collapse"}).find("td").each(function(a){$(this).click(function(){var a=$(this).html();if(b.ampm){var d=a.substring(2).toLowerCase(),e=parseInt(a.substring(0,2),10);if(d=="a"){if(e==12)a=0;else a=e}else if(e==12)a=12;else a=e+12}c.hour_slider.slider("option","value",a);c._onTimeChange();c._onSelectHandler()}).css({cursor:"pointer",width:100/k+"%",textAlign:"center",overflow:"hidden"})})}if(b.showMinute&&b.minuteGrid>0){o=100*l*b.minuteGrid/(e-b.minuteMin);$tp.find(".ui_tpicker_minute table").css({width:o+"%",marginLeft:o/(-2*l)+"%",borderCollapse:"collapse"}).find("td").each(function(a){$(this).click(function(){c.minute_slider.slider("option","value",$(this).html());c._onTimeChange();c._onSelectHandler()}).css({cursor:"pointer",width:100/l+"%",textAlign:"center",overflow:"hidden"})})}if(b.showSecond&&b.secondGrid>0){$tp.find(".ui_tpicker_second table").css({width:o+"%",marginLeft:o/(-2*m)+"%",borderCollapse:"collapse"}).find("td").each(function(a){$(this).click(function(){c.second_slider.slider("option","value",$(this).html());c._onTimeChange();c._onSelectHandler()}).css({cursor:"pointer",width:100/m+"%",textAlign:"center",overflow:"hidden"})})}if(b.showMillisec&&b.millisecGrid>0){$tp.find(".ui_tpicker_millisec table").css({width:o+"%",marginLeft:o/(-2*n)+"%",borderCollapse:"collapse"}).find("td").each(function(a){$(this).click(function(){c.millisec_slider.slider("option","value",$(this).html());c._onTimeChange();c._onSelectHandler()}).css({cursor:"pointer",width:100/n+"%",textAlign:"center",overflow:"hidden"})})}var u=a.find(".ui-datepicker-buttonpane");if(u.length)u.before($tp);else a.append($tp);this.$timeObj=$tp.find("#ui_tpicker_time_"+h);if(this.inst!==null){var v=this.timeDefined;this._onTimeChange();this.timeDefined=v}var w=function(){c._onSelectHandler()};this.hour_slider.bind("slidestop",w);this.minute_slider.bind("slidestop",w);this.second_slider.bind("slidestop",w);this.millisec_slider.bind("slidestop",w)}},_limitMinMaxDateTime:function(a,b){var c=this._defaults,d=new Date(a.selectedYear,a.selectedMonth,a.selectedDay);if(!this._defaults.showTimepicker)return;if($.datepicker._get(a,"minDateTime")!==null&&$.datepicker._get(a,"minDateTime")!==undefined&&d){var e=$.datepicker._get(a,"minDateTime"),f=new Date(e.getFullYear(),e.getMonth(),e.getDate(),0,0,0,0);if(this.hourMinOriginal===null||this.minuteMinOriginal===null||this.secondMinOriginal===null||this.millisecMinOriginal===null){this.hourMinOriginal=c.hourMin;this.minuteMinOriginal=c.minuteMin;this.secondMinOriginal=c.secondMin;this.millisecMinOriginal=c.millisecMin}if(a.settings.timeOnly||f.getTime()==d.getTime()){this._defaults.hourMin=e.getHours();if(this.hour<=this._defaults.hourMin){this.hour=this._defaults.hourMin;this._defaults.minuteMin=e.getMinutes();if(this.minute<=this._defaults.minuteMin){this.minute=this._defaults.minuteMin;this._defaults.secondMin=e.getSeconds()}else if(this.second<=this._defaults.secondMin){this.second=this._defaults.secondMin;this._defaults.millisecMin=e.getMilliseconds()}else{if(this.millisec<this._defaults.millisecMin)this.millisec=this._defaults.millisecMin;this._defaults.millisecMin=this.millisecMinOriginal}}else{this._defaults.minuteMin=this.minuteMinOriginal;this._defaults.secondMin=this.secondMinOriginal;this._defaults.millisecMin=this.millisecMinOriginal}}else{this._defaults.hourMin=this.hourMinOriginal;this._defaults.minuteMin=this.minuteMinOriginal;this._defaults.secondMin=this.secondMinOriginal;this._defaults.millisecMin=this.millisecMinOriginal}}if($.datepicker._get(a,"maxDateTime")!==null&&$.datepicker._get(a,"maxDateTime")!==undefined&&d){var g=$.datepicker._get(a,"maxDateTime"),h=new Date(g.getFullYear(),g.getMonth(),g.getDate(),0,0,0,0);if(this.hourMaxOriginal===null||this.minuteMaxOriginal===null||this.secondMaxOriginal===null){this.hourMaxOriginal=c.hourMax;this.minuteMaxOriginal=c.minuteMax;this.secondMaxOriginal=c.secondMax;this.millisecMaxOriginal=c.millisecMax}if(a.settings.timeOnly||h.getTime()==d.getTime()){this._defaults.hourMax=g.getHours();if(this.hour>=this._defaults.hourMax){this.hour=this._defaults.hourMax;this._defaults.minuteMax=g.getMinutes();if(this.minute>=this._defaults.minuteMax){this.minute=this._defaults.minuteMax;this._defaults.secondMax=g.getSeconds()}else if(this.second>=this._defaults.secondMax){this.second=this._defaults.secondMax;this._defaults.millisecMax=g.getMilliseconds()}else{if(this.millisec>this._defaults.millisecMax)this.millisec=this._defaults.millisecMax;this._defaults.millisecMax=this.millisecMaxOriginal}}else{this._defaults.minuteMax=this.minuteMaxOriginal;this._defaults.secondMax=this.secondMaxOriginal;this._defaults.millisecMax=this.millisecMaxOriginal}}else{this._defaults.hourMax=this.hourMaxOriginal;this._defaults.minuteMax=this.minuteMaxOriginal;this._defaults.secondMax=this.secondMaxOriginal;this._defaults.millisecMax=this.millisecMaxOriginal}}if(b!==undefined&&b===true){var i=(this._defaults.hourMax-(this._defaults.hourMax-this._defaults.hourMin)%this._defaults.stepHour).toFixed(0),j=(this._defaults.minuteMax-(this._defaults.minuteMax-this._defaults.minuteMin)%this._defaults.stepMinute).toFixed(0),k=(this._defaults.secondMax-(this._defaults.secondMax-this._defaults.secondMin)%this._defaults.stepSecond).toFixed(0),l=(this._defaults.millisecMax-(this._defaults.millisecMax-this._defaults.millisecMin)%this._defaults.stepMillisec).toFixed(0);if(this.hour_slider)this.hour_slider.slider("option",{min:this._defaults.hourMin,max:i}).slider("value",this.hour);if(this.minute_slider)this.minute_slider.slider("option",{min:this._defaults.minuteMin,max:j}).slider("value",this.minute);if(this.second_slider)this.second_slider.slider("option",{min:this._defaults.secondMin,max:k}).slider("value",this.second);if(this.millisec_slider)this.millisec_slider.slider("option",{min:this._defaults.millisecMin,max:l}).slider("value",this.millisec)}},_onTimeChange:function(){var a=this.hour_slider?this.hour_slider.slider("value"):false,b=this.minute_slider?this.minute_slider.slider("value"):false,c=this.second_slider?this.second_slider.slider("value"):false,d=this.millisec_slider?this.millisec_slider.slider("value"):false,e=this.timezone_select?this.timezone_select.val():false,f=this._defaults;if(typeof a=="object")a=false;if(typeof b=="object")b=false;if(typeof c=="object")c=false;if(typeof d=="object")d=false;if(typeof e=="object")e=false;if(a!==false)a=parseInt(a,10);if(b!==false)b=parseInt(b,10);if(c!==false)c=parseInt(c,10);if(d!==false)d=parseInt(d,10);var g=f[a<12?"amNames":"pmNames"][0];var h=a!=this.hour||b!=this.minute||c!=this.second||d!=this.millisec||this.ampm.length>0&&a<12!=($.inArray(this.ampm.toUpperCase(),this.amNames)!==-1)||e!=this.timezone;if(h){if(a!==false)this.hour=a;if(b!==false)this.minute=b;if(c!==false)this.second=c;if(d!==false)this.millisec=d;if(e!==false)this.timezone=e;if(!this.inst)this.inst=$.datepicker._getInst(this.$input[0]);this._limitMinMaxDateTime(this.inst,true)}if(f.ampm)this.ampm=g;this._formatTime();if(this.$timeObj)this.$timeObj.text(this.formattedTime+f.timeSuffix);this.timeDefined=true;if(h)this._updateDateTime()},_onSelectHandler:function(){var a=this._defaults.onSelect;var b=this.$input?this.$input[0]:null;if(a&&b){a.apply(b,[this.formattedDateTime,this])}},_formatTime:function(a,b,c){if(c==undefined)c=this._defaults.ampm;a=a||{hour:this.hour,minute:this.minute,second:this.second,millisec:this.millisec,ampm:this.ampm,timezone:this.timezone};var d=(b||this._defaults.timeFormat).toString();var e=parseInt(a.hour,10);if(c){if(!$.inArray(a.ampm.toUpperCase(),this.amNames)!==-1)e=e%12;if(e===0)e=12}d=d.replace(/(?:hh?|mm?|ss?|[tT]{1,2}|[lz])/g,function(b){switch(b.toLowerCase()){case"hh":return("0"+e).slice(-2);case"h":return e;case"mm":return("0"+a.minute).slice(-2);case"m":return a.minute;case"ss":return("0"+a.second).slice(-2);case"s":return a.second;case"l":return("00"+a.millisec).slice(-3);case"z":return a.timezone;case"t":case"tt":if(c){var d=a.ampm;if(b.length==1)d=d.charAt(0);return b.charAt(0)=="T"?d.toUpperCase():d.toLowerCase()}return""}});if(arguments.length)return d;else this.formattedTime=d},_updateDateTime:function(a){a=this.inst||a,dt=new Date(a.selectedYear,a.selectedMonth,a.selectedDay),dateFmt=$.datepicker._get(a,"dateFormat"),formatCfg=$.datepicker._getFormatConfig(a),timeAvailable=dt!==null&&this.timeDefined;this.formattedDate=$.datepicker.formatDate(dateFmt,dt===null?new Date:dt,formatCfg);var b=this.formattedDate;if(a.lastVal!==undefined&&a.lastVal.length>0&&this.$input.val().length===0)return;if(this._defaults.timeOnly===true){b=this.formattedTime}else if(this._defaults.timeOnly!==true&&(this._defaults.alwaysSetTime||timeAvailable)){b+=this._defaults.separator+this.formattedTime+this._defaults.timeSuffix}this.formattedDateTime=b;if(!this._defaults.showTimepicker){this.$input.val(this.formattedDate)}else if(this.$altInput&&this._defaults.altFieldTimeOnly===true){this.$altInput.val(this.formattedTime);this.$input.val(this.formattedDate)}else if(this.$altInput){this.$altInput.val(b);this.$input.val(b)}else{this.$input.val(b)}this.$input.trigger("change")}});$.fn.extend({timepicker:function(a){a=a||{};var b=arguments;if(typeof a=="object")b[0]=$.extend(a,{timeOnly:true});return $(this).each(function(){$.fn.datetimepicker.apply($(this),b)})},datetimepicker:function(a){a=a||{};var b=this,c=arguments;if(typeof a=="string"){if(a=="getDate")return $.fn.datepicker.apply($(this[0]),c);else return this.each(function(){var a=$(this);a.datepicker.apply(a,c)})}else return this.each(function(){var b=$(this);b.datepicker($.timepicker._newInst(b,a)._defaults)})}});$.datepicker._base_selectDate=$.datepicker._selectDate;$.datepicker._selectDate=function(a,b){var c=this._getInst($(a)[0]),d=this._get(c,"timepicker");if(d){d._limitMinMaxDateTime(c,true);c.inline=c.stay_open=true;this._base_selectDate(a,b);c.inline=c.stay_open=false;this._notifyChange(c);this._updateDatepicker(c)}else this._base_selectDate(a,b)};$.datepicker._base_updateDatepicker=$.datepicker._updateDatepicker;$.datepicker._updateDatepicker=function(a){var b=a.input[0];if($.datepicker._curInst&&$.datepicker._curInst!=a&&$.datepicker._datepickerShowing&&$.datepicker._lastInput!=b){return}if(typeof a.stay_open!=="boolean"||a.stay_open===false){this._base_updateDatepicker(a);var c=this._get(a,"timepicker");if(c)c._addTimePicker(a)}};$.datepicker._base_doKeyPress=$.datepicker._doKeyPress;$.datepicker._doKeyPress=function(a){var b=$.datepicker._getInst(a.target),c=$.datepicker._get(b,"timepicker");if(c){if($.datepicker._get(b,"constrainInput")){var d=c._defaults.ampm,e=$.datepicker._possibleChars($.datepicker._get(b,"dateFormat")),f=c._defaults.timeFormat.toString().replace(/[hms]/g,"").replace(/TT/g,d?"APM":"").replace(/Tt/g,d?"AaPpMm":"").replace(/tT/g,d?"AaPpMm":"").replace(/T/g,d?"AP":"").replace(/tt/g,d?"apm":"").replace(/t/g,d?"ap":"")+" "+c._defaults.separator+c._defaults.timeSuffix+(c._defaults.showTimezone?c._defaults.timezoneList.join(""):"")+c._defaults.amNames.join("")+c._defaults.pmNames.join("")+e,g=String.fromCharCode(a.charCode===undefined?a.keyCode:a.charCode);return a.ctrlKey||g<" "||!e||f.indexOf(g)>-1}}return $.datepicker._base_doKeyPress(a)};$.datepicker._base_doKeyUp=$.datepicker._doKeyUp;$.datepicker._doKeyUp=function(a){var b=$.datepicker._getInst(a.target),c=$.datepicker._get(b,"timepicker");if(c){if(c._defaults.timeOnly&&b.input.val()!=b.lastVal){try{$.datepicker._updateDatepicker(b)}catch(d){$.datepicker.log(d)}}}return $.datepicker._base_doKeyUp(a)};$.datepicker._base_gotoToday=$.datepicker._gotoToday;$.datepicker._gotoToday=function(a){var b=this._getInst($(a)[0]),c=b.dpDiv;this._base_gotoToday(a);var d=new Date;var e=this._get(b,"timepicker");if(e._defaults.showTimezone&&e.timezone_select){var f=d.getTimezoneOffset();var g=f>0?"-":"+";f=Math.abs(f);var h=f%60;f=g+("0"+(f-h)/60).slice(-2)+("0"+h).slice(-2);if(e._defaults.timezoneIso8609)f=f.substring(0,3)+":"+f.substring(3);e.timezone_select.val(f)}this._setTime(b,d);$(".ui-datepicker-today",c).click()};$.datepicker._disableTimepickerDatepicker=function(a,b,c){var d=this._getInst(a),e=this._get(d,"timepicker");$(a).datepicker("getDate");if(e){e._defaults.showTimepicker=false;e._updateDateTime(d)}};$.datepicker._enableTimepickerDatepicker=function(a,b,c){var d=this._getInst(a),e=this._get(d,"timepicker");$(a).datepicker("getDate");if(e){e._defaults.showTimepicker=true;e._addTimePicker(d);e._updateDateTime(d)}};$.datepicker._setTime=function(a,b){var c=this._get(a,"timepicker");if(c){var d=c._defaults,e=b?b.getHours():d.hour,f=b?b.getMinutes():d.minute,g=b?b.getSeconds():d.second,h=b?b.getMilliseconds():d.millisec;if(e<d.hourMin||e>d.hourMax||f<d.minuteMin||f>d.minuteMax||g<d.secondMin||g>d.secondMax||h<d.millisecMin||h>d.millisecMax){e=d.hourMin;f=d.minuteMin;g=d.secondMin;h=d.millisecMin}c.hour=e;c.minute=f;c.second=g;c.millisec=h;if(c.hour_slider)c.hour_slider.slider("value",e);if(c.minute_slider)c.minute_slider.slider("value",f);if(c.second_slider)c.second_slider.slider("value",g);if(c.millisec_slider)c.millisec_slider.slider("value",h);c._onTimeChange();c._updateDateTime(a)}};$.datepicker._setTimeDatepicker=function(a,b,c){var d=this._getInst(a),e=this._get(d,"timepicker");if(e){this._setDateFromField(d);var f;if(b){if(typeof b=="string"){e._parseTime(b,c);f=new Date;f.setHours(e.hour,e.minute,e.second,e.millisec)}else f=new Date(b.getTime());if(f.toString()=="Invalid Date")f=undefined;this._setTime(d,f)}}};$.datepicker._base_setDateDatepicker=$.datepicker._setDateDatepicker;$.datepicker._setDateDatepicker=function(a,b){var c=this._getInst(a),d=b instanceof Date?new Date(b.getTime()):b;this._updateDatepicker(c);this._base_setDateDatepicker.apply(this,arguments);this._setTimeDatepicker(a,d,true)};$.datepicker._base_getDateDatepicker=$.datepicker._getDateDatepicker;$.datepicker._getDateDatepicker=function(a,b){var c=this._getInst(a),d=this._get(c,"timepicker");if(d){this._setDateFromField(c,b);var e=this._getDate(c);if(e&&d._parseTime($(a).val(),d.timeOnly))e.setHours(d.hour,d.minute,d.second,d.millisec);return e}return this._base_getDateDatepicker(a,b)};$.datepicker._base_parseDate=$.datepicker.parseDate;$.datepicker.parseDate=function(a,b,c){var d;try{d=this._base_parseDate(a,b,c)}catch(e){d=this._base_parseDate(a,b.substring(0,b.length-(e.length-e.indexOf(":")-2)),c)}return d};$.datepicker._base_formatDate=$.datepicker._formatDate;$.datepicker._formatDate=function(a,b,c,d){var e=this._get(a,"timepicker");if(e){if(b)var f=this._base_formatDate(a,b,c,d);e._updateDateTime();return e.$input.val()}return this._base_formatDate(a)};$.datepicker._base_optionDatepicker=$.datepicker._optionDatepicker;$.datepicker._optionDatepicker=function(a,b,c){var d=this._getInst(a),e=this._get(d,"timepicker");if(e){var f,g,h;if(typeof b=="string"){if(b==="minDate"||b==="minDateTime")f=c;else if(b==="maxDate"||b==="maxDateTime")g=c;else if(b==="onSelect")h=c}else if(typeof b=="object"){if(b.minDate)f=b.minDate;else if(b.minDateTime)f=b.minDateTime;else if(b.maxDate)g=b.maxDate;else if(b.maxDateTime)g=b.maxDateTime}if(f){if(f==0)f=new Date;else f=new Date(f);e._defaults.minDate=f;e._defaults.minDateTime=f}else if(g){if(g==0)g=new Date;else g=new Date(g);e._defaults.maxDate=g;e._defaults.maxDateTime=g}else if(h)e._defaults.onSelect=h}this._base_optionDatepicker(a,b,c)};$.timepicker=new Timepicker;$.timepicker.version="0.9.7"})(jQuery)