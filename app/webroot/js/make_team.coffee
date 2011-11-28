TeamMaker.MakeTeam ?= (($)-> 
  
  opts = TeamMaker.Rules.view;
  
  obj =
    
    init: ->
      @views.Rules.init()
    
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
        addNew: ->
          
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
          tmpl = $(opts.numFilterValTmpl).html()
          
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
              html = @getTextFilterValueFields(index, [
                {label: "Min"},
                {label: "Max"}
              ])
              
            when 'gt', 'lt', 'gtet', 'ltet', 'is' 
              html = @getTextFilterValueFields(index, [
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
        getTextFilterValueFields: (index, fields) ->
          tmpl = $(opts.textRangeFilterValTmpl).html()
          
          retArr = []
          
          for field, count in fields
            modTmpl = tmpl
            for key, val of {i: index, j: count, label: field.label}
              modTmpl = modTmpl.replace(new RegExp('\\${'+ key + '}', 'g'), val)
              
            retArr.push(modTmpl)
          
          return retArr
  
  #returns obj
  obj
  
  )(jQuery)