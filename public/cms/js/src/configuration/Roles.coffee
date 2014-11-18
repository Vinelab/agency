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
                    console.log "/configuration/roles/#{encodeURIComponent(postdata['roles-table_id'])}"
                    params.url = "/configuration/roles/#{encodeURIComponent(postdata['roles-table_id'])}"
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
                    params.url = "/configuration/roles/#{encodeURIComponent(postdata)}"
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
