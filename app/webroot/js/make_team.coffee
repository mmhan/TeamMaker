TeamMaker.MakeTeam ?= (($)-> 
  
  opts = TeamMaker.Rules.view;
  tOpts = TeamMaker.Teams.view;
  
  shuffle = (array)->
    # http://www.hardcode.nl/subcategory_1/article_317-array-shuffle-function
    # var len = this.length;
    # var i = len;
     # while (i--) {
      # var p = parseInt(Math.random()*len);
      # var t = this[i];
      # this[i] = this[p];
      # this[p] = t;
    # }
    len = array.length
    i = len
    while i--
      p = parseInt(Math.random() * len)
      t = array[i]
      array[i] = array[p]
      array[p] = t
    array
  
  obj =
    
    init: ->
      @views.Rules.init()
      @views.Log.init()
      @model.init()
    
    views: 
      ###
        To log the background statuses
      ###
      Log: 
        id : "#log"
        $me : false
        
        init: () ->
          @$me = $(@id).empty()
        
        scroll: () ->
          @$me.scrollTop(@$me[0].scrollHeight)
        
        text: (text) ->
          current = @$me.html()
          @$me.html(current + "\n" + text)
          @scroll()
          
        clear: ->
          @$me.empty()
      ###
        for rules
      ###
      Rules:
        #container, templates
        $container: false
        
        ###
          init views
        ###
        init: ->
          @$container = $(opts.container)
          
          @$container.delegate('.skillSelect select', 'change', $.proxy(@onSelectChange, this))
          
          
          @$container.delegate(".numFilterType select", "change", $.proxy(@onNumFilterTypeChange, this))
          @$container.delegate(".textRangeFilterType select", "change", $.proxy(@onTextRangeFilterTypeChange, this))
          @$container.delegate(".textFilterType select", "change", $.proxy(@onTextFilterTypeChange, this))
          
          #hover for the up-down buttons
          @$container
            .delegate(
              ".rearrangeBtns li", 
              "mouseenter",
              (e)-> 
                $(this).addClass("ui-state-hover")
                e.preventDefault();
                e.stopPropagation();
            )
            .delegate(
              ".rearrangeBtns li", 
              "mouseleave",
              (e)-> 
                $(this).removeClass("ui-state-hover")
                e.preventDefault();
                e.stopPropagation();
            )
            .delegate(".moveDown", 'click', $.proxy(@moveDown, this))
            .delegate(".moveUp", "click", $.proxy(@moveUp, this))
            .delegate(".remove", 'click', $.proxy(@remove, this))
          
          #add-more button
          $("#addMoreRule")
            .click($.proxy(@addNew, this))
            .parent().parent().find('a') #select both links under this tree
            .hover(
              (e) -> $(this).addClass('ui-state-hover')
              (e) -> $(this).removeClass('ui-state-hover')
            )
          
            
          @createRules()
          
        ###
          populate rules
        ###
        createRules: ->
          if TeamMaker.Rules.data.rules?
            for rule in TeamMaker.Rules.data.rules
              @addNewWithData(rule)
          else
            #populate first row
            @addNew()
        
        ###
          add new row
        ###
        addNew: (e)->
          e.preventDefault() if e?
          #first get the string
          tmpl = $(opts.ruleTmpl).html()
          
          #then the index
          nextI = @$container.children().length
          
          #then do the replacement
          tmpl = tmpl.replace(/\${i}/g, nextI)
          
          #then attach template to container
          @$container.append($(tmpl))
          
          return nextI
        
        ###
          To add new with data
        ###
        addNewWithData: (data) ->
          i = @addNew()
          $rule = @$container.children().eq(i)
          
          $rule.find(":input[name$='[num]']").val(data.num)
          $rule.find(":input[name$='[type]']").val(data.type).change()
          $rule.find(":input[name$='[filter_type]']").val(data.filter_type).change()
          $rule.find(":input[name$='[0]']").val(data[0]).change()
          $rule.find(":input[name$='[1]']").val(data[1]).change() if data[1]?
        
        ###
          Handler for changing select of skill
        ###
        onSelectChange: (e) ->
          e.preventDefault()
          
          val = $(e.target).val() if $(e.target).val()
          return false if not val
          
          #get skill's type
          type = (parseInt) TeamMaker.Rules.data.skills[$(e.target).val()].type
          
          #get index 
          index = $(e.target).closest('.rule').attr('data-index')
          
          #get the replaced string
          tmpl = $(opts.tmpl[type]).html().replace(/\${i}/g, index)
          
          '''
          switch type
            when opts.constants.NUMERIC_RANGE
              console.log("num range");
            when opts.constants.TEXT_RANGE
              console.log("text range");
            when opts.constants.TEXT 
              console.log("text range");
          '''
          
          #put it in 
          $(e.target).closest('.rule').find(".ruleConditions").html(tmpl)
          
        ###
          Called when number skill's filter type change
        ###
        onNumFilterTypeChange: (e) ->
          e.preventDefault()
          $me = $(e.target);
          val = $me.val() if $me.val()
          return val if not val
          
          index = $me.closest('div.rule').attr('data-index')
          
          $container = $me.closest('div.rule').find('.filterValue');
          
          html = ''
          
          switch val
            when 'between' 
              html = @getNumFilterValueFields(index, [
                {label: "Min"},
                {label: "Max"}
              ])
              
            when 'gt', 'lt', 'gtet', 'ltet', 'is' 
              html = @getNumFilterValueFields(index, [
                {label: "Value"}
              ])
          
          $container.html(html.join("\n"))
          
        ###
          will create number filter value input fields
        ###
        getNumFilterValueFields: (index, fields, options) ->
          tmpl = $(opts.filterValTmpl).html()
          
          retArr = []
          
          for field, count in fields
            modTmpl = tmpl
            for key, val of {i: index, j: count, label: field.label}
              modTmpl = modTmpl.replace(new RegExp('\\${'+ key + '}', 'g'), val)
            retArr.push(modTmpl)
          
          return retArr
          
          
        ###
          Will respond when filter type is changed for text range
        ###
        onTextRangeFilterTypeChange: (e)->
          e.preventDefault()
          
          $me = $(e.target);
          val = $me.val() if $me.val()
          return val if not val
          
          index = $me.closest('div.rule').attr('data-index')
          
          $container = $me.closest('div.rule').find('.filterValue');
          
          html = ''
          
          ## get all the options as array to pass to getTextFilterValueFields
          options = TeamMaker.Rules.data.skills[$me.closest('div.rule').find(".skillSelect select").val()].range.split("|")
          
          ## get the select tag
          switch val
            when 'between' 
              html = @getTextRangeFilterValueFields(index, [
                {label: "Min"},
                {label: "Max"}
              ])
              
            when 'gt', 'lt', 'gtet', 'ltet', 'is' 
              html = @getTextRangeFilterValueFields(index, [
                {label: "Value"}
              ])
          
          ## add the options.
          for select, i in html
            $tmp = $("<div>").append($(select))
            $select = $tmp.find("select")
            for option, j in options
               $select.append($("<option value=#{j}>#{option}</option>"))
            html[i] = $tmp.html()
            
          $container.html(html.join("\n"))
        
        ###
          Will create dropdown menus for filtering
        ###
        getTextRangeFilterValueFields: (index, fields, after) ->
          tmpl = $(opts.textRangeFilterValTmpl).html()
          
          retArr = []
          
          for field, count in fields
            modTmpl = tmpl
            for key, val of {i: index, j: count, label: field.label}
              modTmpl = modTmpl.replace(new RegExp('\\${'+ key + '}', 'g'), val)
              
            retArr.push(modTmpl)
          
          retArr.push(after)
          
          return retArr
        
        ###
          To respond to drop-down menu changes for text filter type
        ###
        onTextFilterTypeChange: (e) ->
          e.preventDefault()
          
          $me = $(e.target);
          val = $me.val() if $me.val()
          return val if not val
          
          index = $me.closest('div.rule').attr('data-index')
          
          $container = $me.closest('div.rule').find('.filterValue');
          
          html = ''
          
          ## get all the options as array to pass to getTextFilterValueFields
          options = TeamMaker.Rules.data.skills[$me.closest('div.rule').find(".skillSelect select").val()].range.split("|")
          
          ## get the select tag
          switch val
            when 'is', '!is', 'contains', '!contains'
              html = @getTextFilterValueFields(index, [
                {label: "Term"}
              ], "Case-insensitive")
              
            when 'matches' 
              html = @getTextFilterValueFields(index, [
                {label: "Pattern"},
                {label: "Modifier"}
              ], "<a href='http://www.w3schools.com/jsref/jsref_obj_regexp.asp' target='_blank'>?</a>")
          
          html[0] = $("<div>")
            .html(html[0])
            .children().first().addClass("term")
            .parent().html()
          
          $container.html(html.join("\n"))
        
        ###
          Will create dropdown menus for filtering
        ###
        getTextFilterValueFields: (index, fields, after) ->
          tmpl = $(opts.filterValTmpl).html()
          
          retArr = []
          
          for field, count in fields
            modTmpl = tmpl
            for key, val of {i: index, j: count, label: field.label}
              modTmpl = modTmpl.replace(new RegExp('\\${'+ key + '}', 'g'), val)
              
            retArr.push(modTmpl)
          
          retArr.push(after)
          
          return retArr
          
        ###
          Get rule
        ###
        getRule: (index) ->
          retVals = {}
          hasErr = false
          for el, i in @$container.find("div.rule[data-index='" + index + "'] :input")
            $me = $(el)
            name = $me.attr('name')
            retVals[name.slice(name.lastIndexOf("[") + 1, name.lastIndexOf("]"))] = val = $me.val() 
            if val
              $me.removeClass('error')
            else
              hasErr = true
              $me.addClass('error')
              
          return if hasErr then false else retVals
        
        ###
          Get all rules in an array.
        ###  
        getAllRules: () ->
          retVals = {}
          hasErr = false
          for i in [0..@$container.find('div.rule').length-1]
            rule = @getRule(i)
            hasErr = true if !rule 
            retVals[i] = if rule then rule else false
            
          return if hasErr then false else retVals
        
        ###
          To move down the rule
        ###
        moveDown: (e) ->
          e.preventDefault()
          $rule = $(e.target).closest('div.rule')
          $rule.next().after($rule)
          
        ###
          To move up the rule
        ###
        moveUp: (e) ->
          e.preventDefault()
          $rule = $(e.target).closest('div.rule')
          $rule.prev().before($rule)
          
        ###
          To remove a rule
        ###
        remove: (e) ->
          e.preventDefault()
          $(e.target).closest('div.rule').remove()
          
        ###
          To return number of teams
        ###
        getNumOfTeams: ->
          $el = $(".numberOfTeams input").first()
          val = parseInt($el.val()) 
          if val
            $el.removeClass('error')
            return val
          else
            $el.addClass('error')
            return false
      
      ###
        For the teams
      ###
      Teams:
        # container
        $container: false
        init: () ->
          @$container: $(opts.container)
          
          
    ###===================================================================
      For all the data-related operations
      
    ###
    model:
      
      rules: {}
      teams: {}
      members: false
      
      numOfTeams: 0
      currentTeamToAllocate: 0
      rotatedFrom: false
      
      inMargin: {
        NO: 0
        YES: 1
        FULL: 2
      }
      
      init: ->
        $("#saveRules").click($.proxy(@saveRules, this))
        $("#generateTeam").click($.proxy(@generateTeam, this))
        @members = TeamMaker.Rules.data.members
       
      ###
        To save the rules that was used.
      ###
      saveRules: (e) ->
        e.preventDefault()
        
        $me = $(e.target)
        rules = TeamMaker.MakeTeam.views.Rules.getAllRules()
        return alert("Please fix the errors highlighted") if !rules
        
        TeamMaker.MakeTeam.views.Log.text("Saving Rules.");
        
        $.ajax({
          url: $me.attr('data-url')
          data: {data: {Project: {rules: rules}}}
          type: 'POST'
          dataType: 'json'
          success: (data) ->
            if(data.status)
              alert("Rules saved")
            else
              alert("Rules can't be save. Please try again")
        })
        
      ###
        To generate a team
      ###
      generateTeam: (e) ->
        e.preventDefault()
        
        _log = TeamMaker.MakeTeam.views.Log
        _log.clear()
        
        #get all rules
        rules = TeamMaker.MakeTeam.views.Rules.getAllRules()
        return alert("Please fix the errors highlighted.") if !rules
        _log.text("There are rules.")
        
        @numOfTeams = TeamMaker.MakeTeam.views.Rules.getNumOfTeams()
        return alert("Please provide number of teams.") if !@numOfTeams
        _log.text("There should be " + @numOfTeams + " teams.")
        
        #generate blank teams
        for i in [0..@numOfTeams - 1]
          @teams[i] = []
        
        #build rules
        for i, rule of rules
          @rules[i] = {
            num: rule.num
            rule: @buildRule(rule)
            skill_id: rule.type
          } 
        _log.text("Build rules.")
        
        #reset all members
        for i, member of @members
          @members[i].allocated = false 
        
        #generate now
        # for each rule
        for i, rule of @rules
          _log.text("Rule #" + i)
          _log.text("==================")
          membersLeft = false
          for j, member of @members
            if member.allocated == false
              membersLeft = true
              break
          
          if membersLeft
            _log.text("There are members still left. Will continue generating")
            
            # Suppose X[] is a set of unallocated members who satisfy currentRule
            # figuring out who satisfy this rule
            currentlyConsideredMembers = []
            for j, member of @members
               if rule.rule(member.MembersSkill[rule.skill_id]) and !member.allocated
                 if @members[j].satisfy?
                   @members[j].satisfy.push(i)
                 else
                   @members[j].satisfy = [i]
                   
                 currentlyConsideredMembers.push(j)
                 
            #shuffle x[]
            _log.text("Members that are considered: " + currentlyConsideredMembers.join(","))
            shuffle currentlyConsideredMembers
            
            for id in currentlyConsideredMembers
              @allocate(id, i)
        
        '''
          All rules has been satisfied. Now, going to assign random members to random teams.
        '''
        _log.text("All rules have been considered. Now going to assign the remaining members randomly.")
        _log.text("==================")
        idsOfMembersLeft = (i for i, member of @members when member.allocated == false)
        shuffle idsOfMembersLeft
        for id in idsOfMembersLeft
          @allocate(id)
        
        _log.text("All done.")
        for i, team of @teams
          _log.text("Team #" + i + ": " + team.join(", "))
        
      ###
        Allocate a member to a team
      ###      
      allocate: (memberId, ruleIndex) ->
        _log = TeamMaker.MakeTeam.views.Log
        _log.text("Considering member#"+memberId)
        
        if ruleIndex is null
          @teams[@currentTeamToAllocate].push(memberId)
          @members[memberId].allocated = true
          _log.text("No specific rule given allocated to team #" + @currentTeamToAllocate)
          @rotateTeam()
        else
          inMargin = @teamIsInMargin(ruleIndex)
          switch inMargin
            when @inMargin.YES 
              @teams[@currentTeamToAllocate].push(memberId)
              @members[memberId].allocated = true
              _log.text("Allocated member #" + memberId + " to team #" + @currentTeamToAllocate)
              @rotateTeam()
            when @inMargin.NO
              if @rotateTeam(@currentTeamToAllocate)
                @allocate(memberId, ruleIndex)
              else
                alert("Something is seriously wrong.")
            when @inMargin.FULL
              @rotateTeam()
        "something"
          
        
      ###
        To check whether the current team can receive member
        The team can be determined as "within margin" for
        - not having satisfied current rule in consideration.
        - having min number of member that satisfy the current rule (when compared against other teams)
      ###
      teamIsInMargin: (ruleIndex) ->
        '''
        if currentRule does not exist:
          return true
        if currentTeam already statisfy currentRule of minimum X member:
          return false
        myCandidates = num of members (that satisfy currentRule) currentTeam already have 
        minCandidates = min num of members other teams have for currentRule
      
        return myCandidate == minCandidate
        '''
        return @inMargin.YES if !ruleIndex
        
        #check for num of members for current rule in all team
        numOfMembersForCurrRule = {}
        minNum = false
        for i, team of @teams
          numOfMembersForCurrRule[i] = 0
          for memberId in team
            member = @members[memberId]
            if $.inArray(ruleIndex, member.satisfy) != -1
              numOfMembersForCurrRule[i]++
          if minNum == false
            minNum = numOfMembersForCurrRule[i] 
          else
            minNum = if numOfMembersForCurrRule[i] < minNum then numOfMembersForCurrRule[i] else minNum
            
        return @inMargin.FULL if numOfMembersForCurrRule[@currentTeamToAllocate] >= @rules[ruleIndex].num 
        if minNum == numOfMembersForCurrRule[@currentTeamToAllocate]
          return @inMargin.YES
        else
          return @inMargin.NO
        
        
      ###
        To rotate the current team 
      ###
      rotateTeam: (remember) ->
        return false if remember? and @rotatedFrom != false and remember == @rotatedFrom
        @rotatedFrom = if remember then remember else false
        @currentTeamToAllocate = if @currentTeamToAllocate + 1 < @numOfTeams then @currentTeamToAllocate + 1 else 0
        return true
        
      ###
        Build a function that either return true or false for a value given, 
        using the given data.
      ###
      buildRule: (rule) ->
        consts = TeamMaker.Rules.view.constants
        dataType = parseInt TeamMaker.Rules.data.skills[rule.type].type
        switch(dataType)
          when consts.NUMERIC_RANGE, consts.TEXT_RANGE
            arg1 = parseInt rule[0]
            arg2 = parseInt rule[1] if rule[1]?
            switch(rule.filter_type)
              when "gt"
                return (v) -> v >  arg1
              when "gtet"
                return (v) -> v >= arg1
              when "lt"
                return (v) -> v <  arg1
              when "ltet"
                return (v) -> v <= arg1
              when "is"
                return (v) -> v == arg1
              when "between"
                return (v) -> arg1 <= v <= arg2 
          
          when consts.TEXT
            arg1 = rule[0]
            arg2 = rule[1] if rule[1]?
            switch(rule.filter_type)
              when "is"
                return (v) -> v.lower() == rule[0].lower()
              when "!is"
                return (v) -> v.lower() != rule[0].lower()
              when "contains"
                return (v) -> if v.match(new RegExp(arg1, 'gi')) then true else false
              when "!contains"
                return (v) -> if v.match(new RegExp(arg1, 'gi')) then false else true
              when "matches"
                return (v) -> if v.match(new RegExp(arg1, arg2)) then true else false
      
  #returns obj
  obj
  
  )(jQuery)