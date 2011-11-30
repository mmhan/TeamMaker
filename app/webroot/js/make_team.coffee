TeamMaker.MakeTeam ?= (($)-> 
  
  opts = TeamMaker.Rules.view;
  
  obj =
    
    init: ->
      @views.Rules.init()
      @model.init()
    
    views: 
      #for rules
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
            #populate the rules
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
          
          
          switch type
            when opts.constants.NUMERIC_RANGE
              console.log("num range");
            when opts.constants.TEXT_RANGE
              console.log("text range");
            when opts.constants.TEXT 
              console.log("text range");
          
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
        getNumForEachTeams: ->
          $el = $(".numberOfMembers input").first()
          val = $el.val() 
          if val
            $el.removeClass('error')
            return val
          else
            $el.addClass('error')
            return false
          
    ###===================================================================
      For all the data-related operations
      
    ###
    model:
      
      rules: {}
      members: false
      
      init: ->
        $("#generateTeam").click($.proxy(@generateTeam, this))
        
        @members = TeamMaker.Rules.data.members
       
      ###
        To generate a team
      ###
      generateTeam: (e) ->
        e.preventDefault()
        #get all rules
        rules = TeamMaker.MakeTeam.views.Rules.getAllRules()
        return alert("Please fix the errors highlighted.") if !rules
        
        numOfTeams = TeamMaker.MakeTeam.views.Rules.getNumForEachTeams()
        return alert("Please provide number of teams.") if !numOfTeams
        
        #build rules
        for i, rule of rules
          @rules[i] = {
            rule: @buildRule(rule)
          } 
        
        
        #generate now
        # for each rule
        for i, rule of @rules
          membersLeft = false
          for member of @members
            membersLeft = true if member.allocated? and !member.allocated
          
          # Suppose X[] is a set of unallocated members who satisfy currentRule
          # X = []
          # for member of @members
            # if member.
        
        
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