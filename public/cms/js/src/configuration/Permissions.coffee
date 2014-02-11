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
