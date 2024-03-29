(function() {
  var _ref;

  if ((_ref = TeamMaker.MakeTeam) == null) {
    TeamMaker.MakeTeam = (function($) {
      var obj, opts, shuffle, tOpts;
      opts = TeamMaker.Rules.view;
      tOpts = TeamMaker.Teams.view;
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
          this.views.Log.init();
          this.model.init();
          return this.views.Teams.init();
        },
        views: {
          /*
                  To log the background statuses
          */
          Log: {
            id: "#log",
            $me: false,
            init: function() {
              return this.$me = $(this.id).empty();
            },
            scroll: function() {
              return this.$me.scrollTop(this.$me[0].scrollHeight);
            },
            text: function(text) {
              var current;
              current = this.$me.html();
              this.$me.html(current + "\n" + text);
              return this.scroll();
            },
            clear: function() {
              return this.$me.empty();
            }
          },
          /*
                  for rules
          */
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
              var rule, _i, _len, _ref2, _results;
              if (TeamMaker.Rules.data.rules != null) {
                _ref2 = TeamMaker.Rules.data.rules;
                _results = [];
                for (_i = 0, _len = _ref2.length; _i < _len; _i++) {
                  rule = _ref2[_i];
                  _results.push(this.addNewWithData(rule));
                }
                return _results;
              } else {
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
              this.$container.append($(tmpl));
              return nextI;
            },
            /*
                      To add new with data
            */
            addNewWithData: function(data) {
              var $rule, i;
              i = this.addNew();
              $rule = this.$container.children().eq(i);
              $rule.find(":input[name$='[num]']").val(data.num);
              $rule.find(":input[name$='[type]']").val(data.type).change();
              $rule.find(":input[name$='[filter_type]']").val(data.filter_type).change();
              $rule.find(":input[name$='[0]']").val(data[0]).change();
              if (data[1] != null) {
                return $rule.find(":input[name$='[1]']").val(data[1]).change();
              }
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
              'switch type\n  when opts.constants.NUMERIC_RANGE\n    console.log("num range");\n  when opts.constants.TEXT_RANGE\n    console.log("text range");\n  when opts.constants.TEXT \n    console.log("text range");';
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
            getNumOfTeams: function() {
              var $el, val;
              $el = $(".numberOfTeams input").first();
              val = parseInt($el.val());
              if (val) {
                $el.removeClass('error');
                return val;
              } else {
                $el.addClass('error');
                return false;
              }
            }
          },
          /*
                  For the teams
          */
          Teams: {
            $container: false,
            init: function() {
              this.$container = $(tOpts.container);
              this.$container.delegate(".member", "mouseenter", function(e) {
                $(this).addClass("ui-state-hover");
                e.preventDefault();
                return e.stopPropagation();
              }).delegate(".member", "mouseleave", function(e) {
                $(this).removeClass("ui-state-hover");
                e.preventDefault();
                return e.stopPropagation();
              });
              return this.renderTeams(TeamMaker.Teams.data.teams);
            },
            renderTeams: function(teams) {
              var i, team;
              this.$container.hide().empty();
              for (i in teams) {
                team = teams[i];
                this.renderTeam(team, i);
              }
              /*
                        Drag-n-drop
              */
              this.$container.find(".member").draggable({
                revert: "invalid",
                appendTo: "#teamsContainer",
                helper: "clone"
              });
              this.$container.find(".teamMembers").droppable({
                connectWithSortable: true,
                accept: function(dragged) {
                  return $(dragged).data('team') !== $(this).data('team');
                },
                drop: function(event, ui) {
                  var $dragged, $newTeamContainer, $oldTeamContainer, memberId, newTeam, oldTeam;
                  $oldTeamContainer = $(ui.draggable).closest('.team');
                  $(this).append(ui.draggable);
                  $dragged = $(ui.draggable);
                  oldTeam = $dragged.data('team');
                  memberId = $dragged.data('memberId');
                  newTeam = $(this).data('team');
                  $dragged.data("team", newTeam);
                  $newTeamContainer = $(this).closest('.team');
                  TeamMaker.MakeTeam.model.moveMember(memberId, oldTeam, newTeam);
                  $oldTeamContainer.find(".satisfyingRules").text(TeamMaker.MakeTeam.model.getSatisfyingRules(oldTeam).join(" | "));
                  return $newTeamContainer.find(".satisfyingRules").text(TeamMaker.MakeTeam.model.getSatisfyingRules(newTeam).join(" | "));
                }
              });
              return this.$container.show().parent().show();
            },
            renderTeam: function(team, i) {
              var $cloned, $membersContainer, j, member, memberId, _len;
              $cloned = $($(tOpts.teamTmpl).html());
              $cloned.find('.teamNum').text("Team #" + i);
              $membersContainer = $cloned.find(".teamMembers");
              for (j = 0, _len = team.length; j < _len; j++) {
                memberId = team[j];
                member = TeamMaker.MakeTeam.model.members[memberId];
                $membersContainer.append($("<div>").addClass('ui-state-default ui-corner-all member').append($("<div>").addClass("name").text(member.Member.name)).append($("<div>").addClass("rules").text("Rule: " + member.satisfy.join(" | "))).data('memberId', memberId).data('team', i).attr('title', "#" + memberId)).data('team', i);
              }
              $cloned.find(".satisfyingRules").text(TeamMaker.MakeTeam.model.getSatisfyingRules(i).join(" | "));
              return this.$container.append($cloned);
            }
          }
        },
        /*===================================================================
          For all the data-related operations
        */
        model: {
          rules: {},
          teams: {},
          members: false,
          numOfTeams: 0,
          currentTeamToAllocate: 0,
          rotatedFrom: false,
          inMargin: {
            NO: 0,
            YES: 1,
            FULL: 2
          },
          init: function() {
            var i, rule, _ref2;
            $("#saveRules").click($.proxy(this.saveRules, this));
            $("#generateTeam").click($.proxy(this.generateTeam, this));
            $("#saveTeams").click($.proxy(this.saveTeams, this));
            this.members = TeamMaker.Rules.data.members;
            this.teams = TeamMaker.Teams.data.teams;
            this.rules = TeamMaker.Rules.data.rules;
            _ref2 = this.rules;
            for (i in _ref2) {
              rule = _ref2[i];
              this.rules[i] = {
                num: rule.num,
                rule: this.buildRule(rule),
                skill_id: rule.type
              };
            }
            return this.populateSatisfy();
          },
          /*
                  To save the rules that was used.
          */
          saveRules: function(e) {
            var $me, rules;
            e.preventDefault();
            $me = $(e.target);
            rules = TeamMaker.MakeTeam.views.Rules.getAllRules();
            if (!rules) return alert("Please fix the errors highlighted");
            TeamMaker.MakeTeam.views.Log.text("Saving Rules.");
            return $.ajax({
              url: $me.attr('data-url'),
              data: {
                data: {
                  Project: {
                    rules: rules
                  }
                }
              },
              type: 'POST',
              dataType: 'json',
              success: function(data) {
                if (data.status) {
                  return alert("Rules saved");
                } else {
                  return alert("Rules can't be saved. Please try again");
                }
              }
            });
          },
          /*
                  To save the team
          */
          saveTeams: function(e) {
            var $me;
            e.preventDefault();
            TeamMaker.MakeTeam.views.Log.text("Saving Teams.");
            $me = $(e.target);
            return $.ajax({
              url: $me.attr('data-url'),
              data: {
                data: {
                  Teams: this.teams
                }
              },
              type: "POST",
              dataType: 'json',
              success: function(data) {
                if (data.status) {
                  return alert("Teams Saved");
                } else {
                  return alert("Teams can't be saved. Please try again.");
                }
              }
            });
          },
          /*
                  To generate a team
          */
          generateTeam: function(e) {
            var currentlyConsideredMembers, i, id, idsOfMembersLeft, j, member, membersLeft, rule, rules, _i, _j, _len, _len2, _log, _ref2, _ref3, _ref4, _ref5, _ref6;
            e.preventDefault();
            _log = TeamMaker.MakeTeam.views.Log;
            _log.clear();
            rules = TeamMaker.MakeTeam.views.Rules.getAllRules();
            if (!rules) return alert("Please fix the errors highlighted.");
            _log.text("There are rules.");
            this.numOfTeams = TeamMaker.MakeTeam.views.Rules.getNumOfTeams();
            if (!this.numOfTeams) return alert("Please provide number of teams.");
            _log.text("There should be " + this.numOfTeams + " teams.");
            for (i = 0, _ref2 = this.numOfTeams - 1; 0 <= _ref2 ? i <= _ref2 : i >= _ref2; 0 <= _ref2 ? i++ : i--) {
              this.teams[i] = [];
            }
            for (i in rules) {
              rule = rules[i];
              this.rules[i] = {
                num: rule.num,
                rule: this.buildRule(rule),
                skill_id: rule.type
              };
            }
            _log.text("Build rules.");
            _ref3 = this.members;
            for (i in _ref3) {
              member = _ref3[i];
              this.members[i].allocated = false;
              this.members[i].satisfy = [];
            }
            _ref4 = this.rules;
            for (i in _ref4) {
              rule = _ref4[i];
              _log.text("Rule #" + i);
              _log.text("==================");
              membersLeft = false;
              _ref5 = this.members;
              for (j in _ref5) {
                member = _ref5[j];
                if (member.allocated === false) {
                  membersLeft = true;
                  break;
                }
              }
              if (membersLeft) {
                _log.text("There are members still left. Will continue generating");
                currentlyConsideredMembers = [];
                _ref6 = this.members;
                for (j in _ref6) {
                  member = _ref6[j];
                  if (rule.rule(member.MembersSkill[rule.skill_id]) && !member.allocated) {
                    this.members[j].satisfy.push(i);
                    currentlyConsideredMembers.push(j);
                  }
                }
                _log.text("Members that are considered: " + currentlyConsideredMembers.join(","));
                shuffle(currentlyConsideredMembers);
                for (_i = 0, _len = currentlyConsideredMembers.length; _i < _len; _i++) {
                  id = currentlyConsideredMembers[_i];
                  this.allocate(id, i);
                }
              }
            }
            'All rules has been satisfied. Now, going to assign random members to random teams.';
            _log.text("All rules have been considered. Now going to assign the remaining members randomly.");
            _log.text("==================");
            idsOfMembersLeft = (function() {
              var _ref7, _results;
              _ref7 = this.members;
              _results = [];
              for (i in _ref7) {
                member = _ref7[i];
                if (member.allocated === false) _results.push(i);
              }
              return _results;
            }).call(this);
            shuffle(idsOfMembersLeft);
            for (_j = 0, _len2 = idsOfMembersLeft.length; _j < _len2; _j++) {
              id = idsOfMembersLeft[_j];
              this.allocate(id);
            }
            _log.text("All done.");
            return TeamMaker.MakeTeam.views.Teams.renderTeams(this.teams);
          },
          /*
                  Allocate a member to a team
          */
          allocate: function(memberId, ruleIndex) {
            var inMargin, _log;
            _log = TeamMaker.MakeTeam.views.Log;
            _log.text("Considering member#" + memberId);
            if (ruleIndex === null) {
              this.teams[this.currentTeamToAllocate].push(memberId);
              this.members[memberId].allocated = true;
              _log.text("No specific rule given allocated to team #" + this.currentTeamToAllocate);
              this.rotateTeam();
            } else {
              inMargin = this.teamIsInMargin(ruleIndex);
              switch (inMargin) {
                case this.inMargin.YES:
                  this.teams[this.currentTeamToAllocate].push(memberId);
                  this.members[memberId].allocated = true;
                  _log.text("Allocated member #" + memberId + " to team #" + this.currentTeamToAllocate);
                  this.rotateTeam();
                  break;
                case this.inMargin.NO:
                  if (this.rotateTeam(this.currentTeamToAllocate)) {
                    this.allocate(memberId, ruleIndex);
                  } else {
                    alert("Something is seriously wrong.");
                  }
                  break;
                case this.inMargin.FULL:
                  this.rotateTeam();
              }
            }
          },
          /*
                  To check whether the current team can receive member
                  The team can be determined as "within margin" for
                  - not having satisfied current rule in consideration.
                  - having min number of member that satisfy the current rule (when compared against other teams)
          */
          teamIsInMargin: function(ruleIndex) {
            '  if currentRule does not exist:\n    return true\n  if currentTeam already statisfy currentRule of minimum X member:\n    return false\n  myCandidates = num of members (that satisfy currentRule) currentTeam already have \n  minCandidates = min num of members other teams have for currentRule\n\n  return myCandidate == minCandidate';
            var i, member, memberId, minNum, numOfMembersForCurrRule, team, _i, _len, _ref2;
            if (!ruleIndex) return this.inMargin.YES;
            numOfMembersForCurrRule = {};
            minNum = false;
            _ref2 = this.teams;
            for (i in _ref2) {
              team = _ref2[i];
              numOfMembersForCurrRule[i] = 0;
              for (_i = 0, _len = team.length; _i < _len; _i++) {
                memberId = team[_i];
                member = this.members[memberId];
                if ($.inArray(ruleIndex, member.satisfy) !== -1) {
                  numOfMembersForCurrRule[i]++;
                }
              }
              if (minNum === false) {
                minNum = numOfMembersForCurrRule[i];
              } else {
                minNum = numOfMembersForCurrRule[i] < minNum ? numOfMembersForCurrRule[i] : minNum;
              }
            }
            if (numOfMembersForCurrRule[this.currentTeamToAllocate] >= this.rules[ruleIndex].num) {
              return this.inMargin.FULL;
            }
            if (minNum === numOfMembersForCurrRule[this.currentTeamToAllocate]) {
              return this.inMargin.YES;
            } else {
              return this.inMargin.NO;
            }
          },
          /*
                  To rotate the current team
          */
          rotateTeam: function(remember) {
            if ((remember != null) && this.rotatedFrom !== false && remember === this.rotatedFrom) {
              return false;
            }
            this.rotatedFrom = remember ? remember : false;
            this.currentTeamToAllocate = this.currentTeamToAllocate + 1 < this.numOfTeams ? this.currentTeamToAllocate + 1 : 0;
            return true;
          },
          /*
                  Build a function that either return true or false for a value given, 
                  using the given rule data.
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
          },
          /*
                  Find satisfying rules of a given set of array.
          */
          getSatisfyingRules: function(index) {
            var i, j, memberId, num, result, rule, team, _ref2;
            result = [];
            team = this.teams[index];
            _ref2 = this.rules;
            for (i in _ref2) {
              rule = _ref2[i];
              num = 0;
              for (j in team) {
                memberId = team[j];
                if ($.inArray(i, this.members[memberId].satisfy) !== -1) num++;
              }
              if (num >= rule.num) result.push(i);
            }
            return result;
          },
          /*
                  Move a member as a result of drag-n-drop allocation
          */
          moveMember: function(memberId, oldTeam, newTeam) {
            var i, index, team, _ref2, _results;
            index = $.inArray(memberId, this.teams[oldTeam]);
            if (index !== -1) this.teams[oldTeam].splice(index, 1);
            this.teams[newTeam].push(memberId);
            TeamMaker.MakeTeam.views.Log.text("Moved member. Printing current teams now.");
            _ref2 = this.teams;
            _results = [];
            for (i in _ref2) {
              team = _ref2[i];
              _results.push(TeamMaker.MakeTeam.views.Log.text("Team #1 : " + team.join(", ")));
            }
            return _results;
          },
          /*
                  Just do populate the satisfy
          */
          populateSatisfy: function() {
            var i, j, member, rule, _ref2, _ref3, _results;
            _ref2 = this.members;
            for (i in _ref2) {
              member = _ref2[i];
              this.members[i].allocated = false;
              this.members[i].satisfy = [];
            }
            _ref3 = this.rules;
            _results = [];
            for (i in _ref3) {
              rule = _ref3[i];
              _results.push((function() {
                var _ref4, _results2;
                _ref4 = this.members;
                _results2 = [];
                for (j in _ref4) {
                  member = _ref4[j];
                  if (rule.rule(member.MembersSkill[rule.skill_id])) {
                    _results2.push(this.members[j].satisfy.push(i));
                  } else {
                    _results2.push(void 0);
                  }
                }
                return _results2;
              }).call(this));
            }
            return _results;
          }
        }
      };
      return obj;
    })(jQuery);
  }

}).call(this);
