(function() {
  var _ref;

  if ((_ref = TeamMaker.MakeTeam) == null) {
    TeamMaker.MakeTeam = (function($) {
      var obj, opts, shuffle;
      opts = TeamMaker.Rules.view;
      shuffle = function(array) {
        var i, len, p, t;
        len = array.length;
        i = len;
        while (i--) {
          p = parseInt(Math.random() * len);
          t = array[i];
          array[i] = array[p];
          array[p] = t;
        }
        return array;
      };
      obj = {
        init: function() {
          this.views.Rules.init();
          return this.model.init();
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
              this.$container.delegate(".textFilterType select", "change", $.proxy(this.onTextFilterTypeChange, this));
              this.$container.delegate(".rearrangeBtns li", "mouseenter", function(e) {
                $(this).addClass("ui-state-hover");
                e.preventDefault();
                return e.stopPropagation();
              }).delegate(".rearrangeBtns li", "mouseleave", function(e) {
                $(this).removeClass("ui-state-hover");
                e.preventDefault();
                return e.stopPropagation();
              }).delegate(".moveDown", 'click', $.proxy(this.moveDown, this)).delegate(".moveUp", "click", $.proxy(this.moveUp, this)).delegate(".remove", 'click', $.proxy(this.remove, this));
              $("#addMoreRule").click($.proxy(this.addNew, this)).parent().parent().find('a').hover(function(e) {
                return $(this).addClass('ui-state-hover');
              }, function(e) {
                return $(this).removeClass('ui-state-hover');
              });
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
            addNew: function(e) {
              var nextI, tmpl;
              if (e != null) e.preventDefault();
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
              tmpl = $(opts.filterValTmpl).html();
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
                  html = this.getTextRangeFilterValueFields(index, [
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
                  html = this.getTextRangeFilterValueFields(index, [
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
            getTextRangeFilterValueFields: function(index, fields, after) {
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
              retArr.push(after);
              return retArr;
            },
            /*
                      To respond to drop-down menu changes for text filter type
            */
            onTextFilterTypeChange: function(e) {
              var $container, $me, html, index, options, val;
              e.preventDefault();
              $me = $(e.target);
              if ($me.val()) val = $me.val();
              if (!val) return val;
              index = $me.closest('div.rule').attr('data-index');
              $container = $me.closest('div.rule').find('.filterValue');
              html = '';
              options = TeamMaker.Rules.data.skills[$me.closest('div.rule').find(".skillSelect select").val()].range.split("|");
              switch (val) {
                case 'is':
                case '!is':
                case 'contains':
                case '!contains':
                  html = this.getTextFilterValueFields(index, [
                    {
                      label: "Term"
                    }
                  ], "Case-insensitive");
                  break;
                case 'matches':
                  html = this.getTextFilterValueFields(index, [
                    {
                      label: "Pattern"
                    }, {
                      label: "Modifier"
                    }
                  ], "<a href='http://www.w3schools.com/jsref/jsref_obj_regexp.asp' target='_blank'>?</a>");
              }
              html[0] = $("<div>").html(html[0]).children().first().addClass("term").parent().html();
              return $container.html(html.join("\n"));
            },
            /*
                      Will create dropdown menus for filtering
            */
            getTextFilterValueFields: function(index, fields, after) {
              var count, field, key, modTmpl, retArr, tmpl, val, _len, _ref2;
              tmpl = $(opts.filterValTmpl).html();
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
              retArr.push(after);
              return retArr;
            },
            /*
                      Get rule
            */
            getRule: function(index) {
              var $me, el, hasErr, i, name, retVals, val, _len, _ref2;
              retVals = {};
              hasErr = false;
              _ref2 = this.$container.find("div.rule[data-index='" + index + "'] :input");
              for (i = 0, _len = _ref2.length; i < _len; i++) {
                el = _ref2[i];
                $me = $(el);
                name = $me.attr('name');
                retVals[name.slice(name.lastIndexOf("[") + 1, name.lastIndexOf("]"))] = val = $me.val();
                if (val) {
                  $me.removeClass('error');
                } else {
                  hasErr = true;
                  $me.addClass('error');
                }
              }
              if (hasErr) {
                return false;
              } else {
                return retVals;
              }
            },
            /*
                      Get all rules in an array.
            */
            getAllRules: function() {
              var hasErr, i, retVals, rule, _ref2;
              retVals = {};
              hasErr = false;
              for (i = 0, _ref2 = this.$container.find('div.rule').length - 1; 0 <= _ref2 ? i <= _ref2 : i >= _ref2; 0 <= _ref2 ? i++ : i--) {
                rule = this.getRule(i);
                if (!rule) hasErr = true;
                retVals[i] = rule ? rule : false;
              }
              if (hasErr) {
                return false;
              } else {
                return retVals;
              }
            },
            /*
                      To move down the rule
            */
            moveDown: function(e) {
              var $rule;
              e.preventDefault();
              $rule = $(e.target).closest('div.rule');
              return $rule.next().after($rule);
            },
            /*
                      To move up the rule
            */
            moveUp: function(e) {
              var $rule;
              e.preventDefault();
              $rule = $(e.target).closest('div.rule');
              return $rule.prev().before($rule);
            },
            /*
                      To remove a rule
            */
            remove: function(e) {
              e.preventDefault();
              return $(e.target).closest('div.rule').remove();
            },
            /*
                      To return number of teams
            */
            getNumForEachTeams: function() {
              var $el, val;
              $el = $(".numberOfMembers input").first();
              val = parseInt($el.val());
              if (val) {
                $el.removeClass('error');
                return val;
              } else {
                $el.addClass('error');
                return false;
              }
            }
          }
        },
        /*===================================================================
          For all the data-related operations
        */
        model: {
          rules: {},
          members: false,
          numForEachTeam: 0,
          init: function() {
            $("#generateTeam").click($.proxy(this.generateTeam, this));
            return this.members = TeamMaker.Rules.data.members;
          },
          /*
                  To generate a team
          */
          generateTeam: function(e) {
            var currentlyConsideredMembers, i, id, j, member, membersLeft, rule, rules, _ref2, _ref3, _ref4, _results;
            e.preventDefault();
            rules = TeamMaker.MakeTeam.views.Rules.getAllRules();
            if (!rules) return alert("Please fix the errors highlighted.");
            this.numForEachTeam = TeamMaker.MakeTeam.views.Rules.getNumForEachTeams();
            if (!this.numForEachTeam) {
              return alert("Please provide number of teams.");
            }
            for (i in rules) {
              rule = rules[i];
              this.rules[i] = {
                rule: this.buildRule(rule),
                skill_id: rule.type
              };
            }
            _ref2 = this.rules;
            _results = [];
            for (i in _ref2) {
              rule = _ref2[i];
              membersLeft = true;
              _ref3 = this.members;
              for (i in _ref3) {
                member = _ref3[i];
                if (member.allocated != null) {
                  membersLeft = false;
                  break;
                }
              }
              if (membersLeft) {
                currentlyConsideredMembers = [];
                _ref4 = this.members;
                for (j in _ref4) {
                  member = _ref4[j];
                  if (rule.rule(member.MembersSkill[rule.skill_id])) {
                    if (member.satisfy != null) {
                      member.satisfy.push(i);
                      currentlyConsideredMembers.push(j);
                    } else {
                      member.satisfy = [i];
                    }
                  }
                }
                shuffle(currentlyConsideredMembers);
                _results.push((function() {
                  var _i, _len, _results2;
                  _results2 = [];
                  for (_i = 0, _len = currentlyConsideredMembers.length; _i < _len; _i++) {
                    id = currentlyConsideredMembers[_i];
                    console.log(this.getAllocationFor(this.members[id]));
                    _results2.push('something');
                  }
                  return _results2;
                }).call(this));
              } else {
                _results.push(void 0);
              }
            }
            return _results;
          },
          /*
                  Allocate a member to a team
          */
          getAllocationFor: function(member) {
            return parseInt(Math.random() * this.numForEachTeam);
          },
          /*
                  Build a function that either return true or false for a value given, 
                  using the given data.
          */
          buildRule: function(rule) {
            var arg1, arg2, consts, dataType;
            consts = TeamMaker.Rules.view.constants;
            dataType = parseInt(TeamMaker.Rules.data.skills[rule.type].type);
            switch (dataType) {
              case consts.NUMERIC_RANGE:
              case consts.TEXT_RANGE:
                arg1 = parseInt(rule[0]);
                if (rule[1] != null) arg2 = parseInt(rule[1]);
                switch (rule.filter_type) {
                  case "gt":
                    return function(v) {
                      return v > arg1;
                    };
                  case "gtet":
                    return function(v) {
                      return v >= arg1;
                    };
                  case "lt":
                    return function(v) {
                      return v < arg1;
                    };
                  case "ltet":
                    return function(v) {
                      return v <= arg1;
                    };
                  case "is":
                    return function(v) {
                      return v === arg1;
                    };
                  case "between":
                    return function(v) {
                      return (arg1 <= v && v <= arg2);
                    };
                }
                break;
              case consts.TEXT:
                arg1 = rule[0];
                if (rule[1] != null) arg2 = rule[1];
                switch (rule.filter_type) {
                  case "is":
                    return function(v) {
                      return v.lower() === rule[0].lower();
                    };
                  case "!is":
                    return function(v) {
                      return v.lower() !== rule[0].lower();
                    };
                  case "contains":
                    return function(v) {
                      if (v.match(new RegExp(arg1, 'gi'))) {
                        return true;
                      } else {
                        return false;
                      }
                    };
                  case "!contains":
                    return function(v) {
                      if (v.match(new RegExp(arg1, 'gi'))) {
                        return false;
                      } else {
                        return true;
                      }
                    };
                  case "matches":
                    return function(v) {
                      if (v.match(new RegExp(arg1, arg2))) {
                        return true;
                      } else {
                        return false;
                      }
                    };
                }
            }
          }
        }
      };
      return obj;
    })(jQuery);
  }

}).call(this);
