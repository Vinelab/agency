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

class Roles

    constructor: ->

        @table_selector = '#roles-table'
        @pager_selector = '#roles-pager'

        @loadPermissions (permissions)=> @configure(permissions)

    loadPermissions: (done)->
        @getAllPermissions (permissions)=>
            window.all_permissions = permissions
            done(permissions) if typeof done is 'function'

    getAllPermissions: (done)->

        xhr = $.ajax 'configuration/permissions'
        xhr.done done
        xhr.error (err, thrown, status)->
            console.error err
            throw new Error thrown
            alert 'there was an error loading permissions'

    configure: (permissions)->

        table_selector = @table_selector
        pager_selector = @pager_selector

        $(table_selector).jqGrid({
            caption: "Roles"

            url: 'configuration/roles'

            datatype: 'json'
            mtype: 'GET'

            savekey: [yes, 13]

            pager: pager_selector

            height: 'auto'
            viewrecords: yes

            rowNum: 20
            rowList: [20, 40, 60]

            multiselect: yes
            multiboxonly: yes

            autowidth: yes
            shrinkToFit: yes

            recreateForm: yes

            loadError: (xhr, status, error)->
                throw new Error error
                alert 'There was an error loading roles data. Please try again later.'

            colNajems: ['ID', 'Title', 'Alias', 'Permissions']

            colModel: [
                {
                    name: 'id', index: 'id', width: 5, sorttype: 'int', editable: no, align: 'center'
                }

                {
                    name: 'title', index: 'title', width: 40, editable: yes
                    editoptions: { size: 20, maxlength: 100 }
                }

                {
                    name: 'alias', index: 'alias', width: 40, editable: yes
                    editoptions: { size: 20, maxlength: 100 }
                }

                {
                    name: 'permissions', index: 'permissions', width: 40, editable: yes
                    formatter: @permissionsView
                    edittype: 'select', editoptions:
                        value: @getSelectablePermissions()
                        size: 20
                        maxlength: 100
                        multiple: yes
                        dataInit: (el)=> $(el).css('min-height', '150px')
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
                editCaption: 'Edit Role'
                mtype: 'PUT'
                recreateForm: yes
                closeAfterEdit: yes
                url: 'configuration/roles/id'
                onclickSubmit: (params, postdata)->
                    console.log "/cms/configuration/roles/#{encodeURIComponent(postdata['roles-table_id'])}"
                    params.url = "/cms/configuration/roles/#{encodeURIComponent(postdata['roles-table_id'])}"
            }

            {
                # add
                mtype: 'POST'
                closeAfterAdd: yes
                reloadAfterSubmit: yes
                url: 'configuration/roles'
            }

            {
                # delete
                mtype: 'DELETE'
                onclickSubmit: (params, postdata)->
                    params.url = "/cms/configuration/roles/#{encodeURIComponent(postdata)}"
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

    permissionsView: (cellvalue, options, cell)->

        if typeof cellvalue is 'object'
            permissions_labels = ''

            for permission in cellvalue
                permissions_labels += "#{permission.title},"

            return permissions_labels.substring(0, permissions_labels.length - 1);

        return cellvalue

    getSelectablePermissions: ->
        console.log 'verifying permissions', window.all_permissions
        permissions = {}

        permissions[permission.id] = permission.title for permission in window.all_permissions

        return permissions

    reload: ->
        @loadPermissions (permissions)=>
            $(@table_selector).jqGrid('setColProp', 'permissions', editoptions: {
                value: @getSelectablePermissions()}).trigger('reloadGrid')

class Permissions

    constructor: ->

        @table_selector = '#permissions-table'
        @pager_selector = '#permissions-pager'

        @configure()

    configure: ->

        self = this

        table_selector = @table_selector
        pager_selector = @pager_selector

        $(table_selector).jqGrid({
            caption: "Permissions"

            url: 'configuration/permissions'

            datatype: 'json'
            mtype: 'GET'

            savekey: [yes, 13]

            pager: pager_selector

            height: 'auto'
            viewrecords: yes

            rowNum: 20
            rowList: [20, 40, 60]

            multiselect: yes
            multiboxonly: yes

            autowidth: yes
            shrinkToFit: yes

            loadError: (xhr, status, error)->
                throw new Error error
                alert 'There was an error loading permissions data. Please try again later.'

            colNajems: ['ID', 'Title', 'Alias', 'Description']

            colModel: [
                {
                    name: 'id', index: 'id', width: 5, sorttype: 'int', editable: no, align: 'center'
                }

                {
                    name: 'title', index: 'title', width: 40, editable: yes
                    editoptions: { size: 20, maxlength: 100 }
                }

                {
                    name: 'alias', index: 'alias', width: 40, editable: yes
                    editoptions: { size: 20, maxlength: 100 }
                }

                {
                    name: 'description', index: 'description', width: 50, editable: yes
                    edittype: 'textarea'
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
                editCaption: 'Edit Permission'
                mtype: 'PUT'
                recreateForm: yes
                closeAfterEdit: yes
                url: 'configuration/permissions/id'

                afterSubmit: @permissionsChanged
                onclickSubmit: (params, postdata)->
                    params.url = "/cms/configuration/permissions/#{encodeURIComponent(postdata['permissions-table_id'])}"
            }

            {
                # add
                mtype: 'POST'
                closeAfterAdd: yes
                reloadAfterSubmit: yes
                url: 'configuration/permissions'
                afterSubmit: @permissionsChanged
            }

            {
                # delete
                mtype: 'DELETE'
                afterSubmit: @permissionsChanged
                onclickSubmit: (params, postdata)->
                    params.url = "/cms/configuration/permissions/#{encodeURIComponent(postdata)}"
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

    permissionsChanged: (success, message, operation)->
        window.Configuration.Roles.reload() if success

class Applications

    constructor: -> @configure()

    configure: ->
        table_selector = '#applications-table'
        pager_selector = '#applications-pager'

        $(table_selector).jqGrid({

            url: '/cms/configuration/applications'

            datatype: 'json'
            mtype: 'GET'

            savekey: [yes, 13]

            pager : pager_selector

            height: 'auto'
            viewrecords : yes

            rowNum:10
            rowList:[10,20,30]
            caption: "Applications"

            multiselect: no
            multiboxonly: yes

            autowidth: yes
            shrinkToFit: yes

            loadError: (xhr, status, error)->
                throw new Error error
                alert 'There was an error loading data. Please try again later.'


            colNajems: ['ID','Name', 'Key', 'Secret']

            colModel: [
                {
                    name: 'id', index: 'id', width: 5, sorttype: 'int', editable: no, align: 'center'
                }

                {
                    name: 'name', index: 'name', width: 5, editable: yes, align: 'center'
                }

                {
                    name: 'key', index: 'key', width: 40, editable: no
                }

                {
                    name: 'secret', index: 'secret', width: 40, editable: no
                }

            ]

            
        }).navGrid(pager_selector, {
                view: yes, viewicon: 'icon-zoom-in grey'
                add: yes, addicon: 'icon-plus-sign purple'
                edit: no, editicon: 'icon-pencil blue'
                del: yes, delicon: 'icon-trash red'
                search: yes, searchicon: 'icon-search orange'
                refresh: yes, refreshicon: 'icon-refresh green'
                reloadAfterSubmit: yes
            }

            {
                # edit
                editCaption: 'Edit Application'
                mtype: 'PUT'
                recreateForm: yes
                closeAfterEdit: yes
                onclickSubmit: (params, postdata)->
                    params.url = "/cms/configuration/applications/#{encodeURIComponent(postdata['sections-table_id'])}"
            }

            {
                # add
                mtype: 'POST'
                closeAfterAdd: yes
                reloadAfterSubmit: yes
                url: '/cms/configuration/applications'
            }

            {
                # delete
                mtype: 'DELETE'
                onclickSubmit: (params, postdata)->
                    params.url = "/cms/configuration/applications/#{encodeURIComponent(postdata)}"
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
    





class Configuration extends Sections

    constructor: ->
        @Sections    = new Sections
        @Roles       = new Roles
        @Permissions = new Permissions
        @Applications = new Applications

$ -> window.Configuration = new Configuration
