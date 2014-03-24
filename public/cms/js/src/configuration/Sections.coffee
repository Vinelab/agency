class Sections

    constructor: -> @configure()

    configure: ->
        table_selector = '#sections-table'
        pager_selector = '#sections-pager'

        $(table_selector).jqGrid({

            url: 'configuration/sections'

            datatype: 'json'
            mtype: 'GET'

            savekey: [yes, 13]

            pager : pager_selector

            height: 'auto'
            viewrecords : yes

            rowNum:10
            rowList:[10,20,30]
            caption: "Sections"

            multiselect: yes
            multiboxonly: yes

            autowidth: yes
            shrinkToFit: yes

            loadError: (xhr, status, error)->
                throw new Error error
                alert 'There was an error loading data. Please try again later.'


            colNames: [
                'ID'
                # ''
                'Title'
                'Alias (URI)'
                'Icon'
                'Parent'
                'Fertile'
                'Can Have Roles'
                'Created'
                'Last Updated'
            ]
            colModel: [

                {
                    name:'id',index:'id', width:5, sorttype:"int", editable: false, align: 'center'
                }

                # {
                #     name:' '
                #     index:''
                #     width:80
                #     fixed:true
                #     sortable:false
                #     resize:false
                #     formatter:'actions'
                #     formatoptions:
                #         keys:true
                #         delOptions:{
                #             recreateForm: true
                #             # beforeShowForm:beforeDeleteCallback
                #         }
                #         # editformbutton:true, editOptions:{recreateForm: true, beforeShowForm:beforeEditCallback}
                # }

                {
                    name:'title', index:'title', width:40, editable: true,
                    editoptions:{size:"20",maxlength:"100"}
                }

                {
                    name:'alias',index:'alias', width:40, editable: true
                    editoptions:{size:"20",maxlength:"100"}
                }

                {
                    name:'icon',index:'icon', width:30, editable: true, formatter: @iconView
                    unformat: @iconEdit, editoptions:{size:"20",maxlength:"100"}
                }

                {
                    name:'parent_id',index:'parent_id', editable: yes, width:25
                }

                {
                    name:'is_fertile',index:'is_fertile', width:20, editable: true
                    align: 'center', formatter: 'checkbox', edittype: 'checkbox', editoptions: {value:"Yes:No"}
                }

                {
                    name: 'is_roleable', index: 'is_roleable', width:20, editable: true
                    align: 'center', formatter: 'checkbox', edittype:'checkbox', editoptions:{value:'Yes:No'}
                }

                {
                    name:'created_at',index:'created_at', width:35, editable: false
                }

                {
                    name:'updated_at',index:'updated_at', width:35, editable: false
                }
            ]
        }).navGrid(pager_selector, {
                view: yes, viewicon: 'icon-zoom-in grey'
                add: yes, addicon: 'icon-plus-sign purple'
                edit: yes, editicon: 'icon-pencil blue'
                del: yes, delicon: 'icon-trash red'
                search: yes, searchicon: 'icon-search orange'
                refresh: yes, refreshicon: 'icon-refresh green'
                reloadAfterSubmit: yes
            }

            {
                # edit
                editCaption: 'Edit Section'
                mtype: 'PUT'
                recreateForm: yes
                closeAfterEdit: yes
                onclickSubmit: (params, postdata)->
                    params.url = "/cms/configuration/sections/#{encodeURIComponent(postdata['sections-table_id'])}"
            }

            {
                # add
                mtype: 'POST'
                closeAfterAdd: yes
                reloadAfterSubmit: yes
                url: 'configuration/sections'
            }

            {
                # delete
                mtype: 'DELETE'
                onclickSubmit: (params, postdata)->
                    params.url = "/cms/configuration/sections/#{encodeURIComponent(postdata)}"
            }

            {
                # search
                mtype: 'GET'
            }

            {
                # view
                mtype: 'GET'
            }
        )

        $(window).on 'resize', ->

            # Get width of parent container
            width = $(window).width()

            width = width - 2 # Fudge factor to prevent horizontal scrollbars

            # Only resize if new width exceeds a minimal threshold
            # Fixes IE issue with in-place resizing when mousing-over frame bars
            if width > 0 and Math.abs(width - $(table_selector).width()) > 5
                $(table_selector).setGridWidth(width)

    iconView: (cellvalue, options, cell)-> "<span class=\"icon-#{cellvalue} bigger-110\"> #{cellvalue}</span>"
    iconEdit: (cellvalue, options, cell)-> $('span', cell).text().trim()