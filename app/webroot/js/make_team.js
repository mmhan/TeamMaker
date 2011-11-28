(function() {
  var _ref;

  if ((_ref = TeamMaker.MakeTeam) == null) {
    TeamMaker.MakeTeam = (function($) {
      var obj, opts;
      opts = TeamMaker.Rules.view;
      obj = {
        init: function() {
          return this.views.Rules.init();
        },
        views: {
          Rules: {
            $container: false,
            /*
                      init views
            */
            init: function() {
              this.$container = $(opts.container);
              this.$container.delegate('.skillSelect select', 'change', $.proxy(this.onSelectChange, this));
              this.$container.delegate(".numFilterType select", "change", $.proxy(this.onNumFilterTypeChange, this));
              this.$container.delegate(".textRangeFilterType select", "change", $.proxy(this.onTextRangeFilterTypeChange, this));
              return this.createRules();
            },
            /*
                      populate rules
            */
            createRules: function() {
              if (TeamMaker.Rules.data.rules != null) {} else {
                return this.addNew();
              }
            },
            /*
                      add new row
            */
            addNew: function() {
              var nextI, tmpl;
              tmpl = $(opts.ruleTmpl).html();
              nextI = this.$container.children().length;
              tmpl = tmpl.replace(/\${i}/g, nextI);
              return this.$container.append($(tmpl));
            },
            /*
                      Handler for changing select of skill
            */
            onSelectChange: function(e) {
              var index, tmpl, type, val;
              e.preventDefault();
              if ($(e.target).val()) val = $(e.target).val();
              if (!val) return false;
              type = parseInt(TeamMaker.Rules.data.skills[$(e.target).val()].type);
              index = $(e.target).closest('.rule').attr('data-index');
              tmpl = $(opts.tmpl[type]).html().replace(/\${i}/g, index);
              switch (type) {
                case opts.constants.NUMERIC_RANGE:
                  console.log("num range");
                  break;
                case opts.constants.TEXT_RANGE:
                  console.log("text range");
                  break;
                case opts.constants.TEXT:
                  console.log("text range");
              }
              return $(e.target).closest('.rule').find(".ruleConditions").html(tmpl);
            },
            /*
                      Called when number skill's filter type change
            */
            onNumFilterTypeChange: function(e) {
              var $container, $me, html, index, val;
              e.preventDefault();
              $me = $(e.target);
              if ($me.val()) val = $me.val();
              if (!val) return val;
              index = $me.closest('div.rule').attr('data-index');
              $container = $me.closest('div.rule').find('.filterValue');
              html = '';
              switch (val) {
                case 'between':
                  html = this.getNumFilterValueFields(index, [
                    {
                      label: "Min"
                    }, {
                      label: "Max"
                    }
                  ]);
                  break;
                case 'gt':
                case 'lt':
                case 'gtet':
                case 'ltet':
                case 'is':
                  html = this.getNumFilterValueFields(index, [
                    {
                      label: "Value"
                    }
                  ]);
              }
              return $container.html(html.join("\n"));
            },
            /*
                      will create number filter value input fields
            */
            getNumFilterValueFields: function(index, fields, options) {
              var count, field, key, modTmpl, retArr, tmpl, val, _len, _ref2;
              tmpl = $(opts.numFilterValTmpl).html();
              retArr = [];
              for (count = 0, _len = fields.length; count < _len; count++) {
                field = fields[count];
                modTmpl = tmpl;
                _ref2 = {
                  i: index,
                  j: count,
                  label: field.label
                };
                for (key in _ref2) {
                  val = _ref2[key];
                  modTmpl = modTmpl.replace(new RegExp('\\${' + key + '}', 'g'), val);
                }
                retArr.push(modTmpl);
              }
              return retArr;
            },
            /*
                      Will respond when filter type is changed for text range
            */
            onTextRangeFilterTypeChange: function(e) {
              var $container, $me, $select, $tmp, html, i, index, j, option, options, select, val, _len, _len2;
              e.preventDefault();
              $me = $(e.target);
              if ($me.val()) val = $me.val();
              if (!val) return val;
              index = $me.closest('div.rule').attr('data-index');
              $container = $me.closest('div.rule').find('.filterValue');
              html = '';
              options = TeamMaker.Rules.data.skills[$me.closest('div.rule').find(".skillSelect select").val()].range.split("|");
              switch (val) {
                case 'between':
                  html = this.getTextFilterValueFields(index, [
                    {
                      label: "Min"
                    }, {
                      label: "Max"
                    }
                  ]);
                  break;
                case 'gt':
                case 'lt':
                case 'gtet':
                case 'ltet':
                case 'is':
                  html = this.getTextFilterValueFields(index, [
                    {
                      label: "Value"
                    }
                  ]);
              }
              for (i = 0, _len = html.length; i < _len; i++) {
                select = html[i];
                $tmp = $("<div>").append($(select));
                $select = $tmp.find("select");
                for (j = 0, _len2 = options.length; j < _len2; j++) {
                  option = options[j];
                  $select.append($("<option value=" + j + ">" + option + "</option>"));
                }
                html[i] = $tmp.html();
              }
              return $container.html(html.join("\n"));
            },
            /*
                      Will create dropdown menus for filtering
            */
            getTextFilterValueFields: function(index, fields) {
              var count, field, key, modTmpl, retArr, tmpl, val, _len, _ref2;
              tmpl = $(opts.textRangeFilterValTmpl).html();
              retArr = [];
              for (count = 0, _len = fields.length; count < _len; count++) {
                field = fields[count];
                modTmpl = tmpl;
                _ref2 = {
                  i: index,
                  j: count,
                  label: field.label
                };
                for (key in _ref2) {
                  val = _ref2[key];
                  modTmpl = modTmpl.replace(new RegExp('\\${' + key + '}', 'g'), val);
                }
                retArr.push(modTmpl);
              }
              return retArr;
            }
          }
        }
      };
      return obj;
    })(jQuery);
  }

}).call(this);
