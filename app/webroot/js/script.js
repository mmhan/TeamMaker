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
		}
	};

    var obj = {
        init: function () {
			priv.Nav.init();
        }
	};

    return obj;
}();

$(document).ready(function () {
    TeamMaker.init();
});