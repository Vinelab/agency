// Generated by CoffeeScript 1.6.3
(function() {
    var Configuration, Permissions, Roles, Sections,
        __hasProp = {}.hasOwnProperty,
        __extends = function(child, parent) { for (var key in parent) { if (__hasProp.call(parent, key)) child[key] = parent[key]; } function ctor() { this.constructor = child; } ctor.prototype = parent.prototype; child.prototype = new ctor(); child.__super__ = parent.prototype; return child; };

    Sections = (function() {
        function Sections() {
            this.configure();
        }

        Sections.prototype.configure = function() {
            var pager_selector, table_selector;
            table_selector = '#sections-table';
            pager_selector = '#sections-pager';
            $(table_selector).jqGrid({
                url: 'configuration/sections',
                datatype: 'json',
                mtype: 'GET',
                savekey: [true, 13],
                pager: pager_selector,
                height: 'auto',
                viewrecords: true,
                rowNum: 10,
                rowList: [10, 20, 30],
                caption: "Sections",
                multiselect: true,
                multiboxonly: true,
                autowidth: true,
                shrinkToFit: true,
                loadError: function(xhr, status, error) {
                    throw new Error(error);
                    return alert('There was an error loading data. Please try again later.');
                },
                loadComplete : function() {
                    var table = this;
                    setTimeout(function(){
                        updatePagerIcons(table);
                    }, 0);
                },
                colNames: ['ID', 'Title', 'Alias (URI)', 'Icon', 'Thumbnail', 'Parent', 'Fertile', 'Can Have Roles', 'Created', 'Last Updated'],
                colModel: [
                    {
                        name: 'id',
                        index: 'id',
                        width: 5,
                        sorttype: "int",
                        editable: false,
                        align: 'center'
                    }, {
                        name: 'title',
                        index: 'title',
                        width: 40,
                        editable: true,
                        editoptions: {
                            size: "20",
                            maxlength: "100"
                        }
                    }, {
                        name: 'alias',
                        index: 'alias',
                        width: 40,
                        editable: true,
                        editoptions: {
                            size: "20",
                            maxlength: "100"
                        }
                    }, {
                        name: 'icon',
                        index: 'icon',
                        width: 30,
                        editable: true,
                        formatter: this.iconView,
                        unformat: this.iconEdit,
                        editoptions: {
                            size: "20",
                            maxlength: "100"
                        }
                    }, {
                        name: 'thumbnail',
                        index: 'thumbnail',
                        width: 30,
                        editable: true,
                        formatter: this.iconView,
                        unformat: this.iconEdit,
                        editoptions: {
                            size: "20",
                            maxlength: "100"
                        }
                    }, {
                        name: 'parent_id',
                        index: 'parent_id',
                        editable: true,
                        width: 25
                    }, {
                        name: 'is_fertile',
                        index: 'is_fertile',
                        width: 20,
                        editable: true,
                        align: 'center',
                        formatter: 'checkbox',
                        edittype: 'checkbox',
                        editoptions: {
                            value: "Yes:No"
                        }
                    }, {
                        name: 'is_roleable',
                        index: 'is_roleable',
                        width: 20,
                        editable: true,
                        align: 'center',
                        formatter: 'checkbox',
                        edittype: 'checkbox',
                        editoptions: {
                            value: 'Yes:No'
                        }
                    }, {
                        name: 'created_at',
                        index: 'created_at',
                        width: 35,
                        editable: false
                    }, {
                        name: 'updated_at',
                        index: 'updated_at',
                        width: 35,
                        editable: false
                    }
                ]
            }).navGrid(pager_selector, {
                view: true,
                viewicon: 'ace-icon fa fa-search-plus grey',
                add: true,
                addicon: 'ace-icon fa fa-plus-circle purple',
                edit: true,
                editicon: 'ace-icon fa fa-pencil blue',
                del: true,
                delicon: 'ace-icon fa fa-trash-o red',
                search: true,
                searchicon: 'ace-icon fa fa-search orange',
                refresh: true,
                refreshicon: 'ace-icon fa fa-refresh green',
                reloadAfterSubmit: true
            }, {
                editCaption: 'Edit Section',
                mtype: 'PUT',
                recreateForm: true,
                closeAfterEdit: true,
                onclickSubmit: function(params, postdata) {
                    return params.url = "/configuration/sections/" + (encodeURIComponent(postdata['sections-table_id']));
                }
            }, {
                mtype: 'POST',
                closeAfterAdd: true,
                reloadAfterSubmit: true,
                url: 'configuration/sections'
            }, {
                mtype: 'DELETE',
                onclickSubmit: function(params, postdata) {
                    return params.url = "/configuration/sections/" + (encodeURIComponent(postdata));
                }
            }, {
                mtype: 'GET'
            }, {
                mtype: 'GET'
            });
            return $(window).on('resize', function() {
                var width;
                width = $(window).width();
                width = width - 2;
                if (width > 0 && Math.abs(width - $(table_selector).width()) > 5) {
                    return $(table_selector).setGridWidth(width);
                }
            });
        };

        Sections.prototype.iconView = function(cellvalue, options, cell) {
            return "<span class=\"icon-" + cellvalue + " bigger-110\"> " + cellvalue + "</span>";
        };

        Sections.prototype.iconEdit = function(cellvalue, options, cell) {
            return $('span', cell).text().trim();
        };

        return Sections;

    })();

    Roles = (function() {
        function Roles() {
            var _this = this;
            this.table_selector = '#roles-table';
            this.pager_selector = '#roles-pager';
            this.loadPermissions(function(permissions) {
                return _this.configure(permissions);
            });
        }

        Roles.prototype.loadPermissions = function(done) {
            var _this = this;
            return this.getAllPermissions(function(permissions) {
                window.all_permissions = permissions;
                if (typeof done === 'function') {
                    return done(permissions);
                }
            });
        };

        Roles.prototype.getAllPermissions = function(done) {
            var xhr;
            xhr = $.ajax('configuration/permissions');
            xhr.done(done);
            return xhr.error(function(err, thrown, status) {
                console.error(err);
                throw new Error(thrown);
                return alert('there was an error loading permissions');
            });
        };

        Roles.prototype.configure = function(permissions) {
            var pager_selector, table_selector,
                _this = this;
            table_selector = this.table_selector;
            pager_selector = this.pager_selector;
            $(table_selector).jqGrid({
                caption: "Roles",
                url: 'configuration/roles',
                datatype: 'json',
                mtype: 'GET',
                savekey: [true, 13],
                pager: pager_selector,
                height: 'auto',
                viewrecords: true,
                rowNum: 20,
                rowList: [20, 40, 60],
                multiselect: true,
                multiboxonly: true,
                autowidth: true,
                shrinkToFit: true,
                recreateForm: true,
                loadError: function(xhr, status, error) {
                    throw new Error(error);
                    return alert('There was an error loading roles data. Please try again later.');
                },
                loadComplete : function() {
                    var table = this;
                    setTimeout(function(){
                        updatePagerIcons(table);
                    }, 0);
                },
                colNajems: ['ID', 'Title', 'Alias', 'Permissions'],
                colModel: [
                    {
                        name: 'id',
                        index: 'id',
                        width: 5,
                        sorttype: 'int',
                        editable: false,
                        align: 'center'
                    }, {
                        name: 'title',
                        index: 'title',
                        width: 40,
                        editable: true,
                        editoptions: {
                            size: 20,
                            maxlength: 100
                        }
                    }, {
                        name: 'alias',
                        index: 'alias',
                        width: 40,
                        editable: true,
                        editoptions: {
                            size: 20,
                            maxlength: 100
                        }
                    }, {
                        name: 'permissions',
                        index: 'permissions',
                        width: 40,
                        editable: true,
                        formatter: this.permissionsView,
                        edittype: 'select',
                        editoptions: {
                            value: this.getSelectablePermissions(),
                            size: 20,
                            maxlength: 100,
                            multiple: true,
                            dataInit: function(el) {
                                return $(el).css('min-height', '150px');
                            }
                        }
                    }
                ]
            }).navGrid(pager_selector, {
                view: true,
                viewicon: 'ace-icon fa fa-search-plus grey',
                add: true,
                addicon: 'ace-icon fa fa-plus-circle purple',
                edit: true,
                editicon: 'ace-icon fa fa-pencil blue',
                del: true,
                delicon: 'ace-icon fa fa-trash-o red',
                search: true,
                searchicon: 'ace-icon fa fa-search orange',
                refresh: true,
                refreshicon: 'ace-icon fa fa-refresh green',
                reloadAfterSubmit: true
            }, {
                editCaption: 'Edit Role',
                mtype: 'PUT',
                recreateForm: true,
                closeAfterEdit: true,
                url: 'configuration/roles/id',
                onclickSubmit: function(params, postdata) {
                    console.log("/configuration/roles/" + (encodeURIComponent(postdata['roles-table_id'])));
                    return params.url = "/configuration/roles/" + (encodeURIComponent(postdata['roles-table_id']));
                }
            }, {
                mtype: 'POST',
                closeAfterAdd: true,
                reloadAfterSubmit: true,
                url: 'configuration/roles'
            }, {
                mtype: 'DELETE',
                onclickSubmit: function(params, postdata) {
                    return params.url = "/configuration/roles/" + (encodeURIComponent(postdata));
                }
            }, {
                mtype: 'GET'
            }, {
                mtype: 'GET'
            });
            return $(window).on('resize', function() {
                var width;
                width = $(window).width();
                width = width - 2;
                if (width > 0 && Math.abs(width - $(table_selector).width()) > 5) {
                    return $(table_selector).setGridWidth(width);
                }
            });
        };

        Roles.prototype.permissionsView = function(cellvalue, options, cell) {
            var permission, permissions_labels, _i, _len;
            if (typeof cellvalue === 'object') {
                permissions_labels = '';
                for (_i = 0, _len = cellvalue.length; _i < _len; _i++) {
                    permission = cellvalue[_i];
                    permissions_labels += "" + permission.title + ",";
                }
                return permissions_labels.substring(0, permissions_labels.length - 1);
            }
            return cellvalue;
        };

        Roles.prototype.getSelectablePermissions = function() {
            var permission, permissions, _i, _len, _ref;
            console.log('verifying permissions', window.all_permissions);
            permissions = {};
            _ref = window.all_permissions;
            for (_i = 0, _len = _ref.length; _i < _len; _i++) {
                permission = _ref[_i];
                permissions[permission.id] = permission.title;
            }
            return permissions;
        };

        Roles.prototype.reload = function() {
            var _this = this;
            return this.loadPermissions(function(permissions) {
                return $(_this.table_selector).jqGrid('setColProp', 'permissions', {
                    editoptions: {
                        value: _this.getSelectablePermissions()
                    }
                }).trigger('reloadGrid');
            });
        };

        return Roles;

    })();

    Permissions = (function() {
        function Permissions() {
            this.table_selector = '#permissions-table';
            this.pager_selector = '#permissions-pager';
            this.configure();
        }

        Permissions.prototype.configure = function() {
            var pager_selector, self, table_selector;
            self = this;
            table_selector = this.table_selector;
            pager_selector = this.pager_selector;
            $(table_selector).jqGrid({
                caption: "Permissions",
                url: 'configuration/permissions',
                datatype: 'json',
                mtype: 'GET',
                savekey: [true, 13],
                pager: pager_selector,
                height: 'auto',
                viewrecords: true,
                rowNum: 20,
                rowList: [20, 40, 60],
                multiselect: true,
                multiboxonly: true,
                autowidth: true,
                shrinkToFit: true,
                loadError: function(xhr, status, error) {
                    throw new Error(error);
                    return alert('There was an error loading permissions data. Please try again later.');
                },
                loadComplete : function() {
                    var table = this;
                    setTimeout(function(){
                        updatePagerIcons(table);
                    }, 0);
                },
                colNajems: ['ID', 'Title', 'Alias', 'Description'],
                colModel: [
                    {
                        name: 'id',
                        index: 'id',
                        width: 5,
                        sorttype: 'int',
                        editable: false,
                        align: 'center'
                    }, {
                        name: 'title',
                        index: 'title',
                        width: 40,
                        editable: true,
                        editoptions: {
                            size: 20,
                            maxlength: 100
                        }
                    }, {
                        name: 'alias',
                        index: 'alias',
                        width: 40,
                        editable: true,
                        editoptions: {
                            size: 20,
                            maxlength: 100
                        }
                    }, {
                        name: 'description',
                        index: 'description',
                        width: 50,
                        editable: true,
                        edittype: 'textarea'
                    }
                ]
            }).navGrid(pager_selector, {
                view: true,
                viewicon: 'ace-icon fa fa-search-plus grey',
                add: true,
                addicon: 'ace-icon fa fa-plus-circle purple',
                edit: true,
                editicon: 'ace-icon fa fa-pencil blue',
                del: true,
                delicon: 'ace-icon fa fa-trash-o red',
                search: true,
                searchicon: 'ace-icon fa fa-search orange',
                refresh: true,
                refreshicon: 'ace-icon fa fa-refresh green',
                reloadAfterSubmit: true
            }, {
                editCaption: 'Edit Permission',
                mtype: 'PUT',
                recreateForm: true,
                closeAfterEdit: true,
                url: 'configuration/permissions/id',
                afterSubmit: this.permissionsChanged,
                onclickSubmit: function(params, postdata) {
                    return params.url = "/configuration/permissions/" + (encodeURIComponent(postdata['permissions-table_id']));
                }
            }, {
                mtype: 'POST',
                closeAfterAdd: true,
                reloadAfterSubmit: true,
                url: 'configuration/permissions',
                afterSubmit: this.permissionsChanged
            }, {
                mtype: 'DELETE',
                afterSubmit: this.permissionsChanged,
                onclickSubmit: function(params, postdata) {
                    return params.url = "/configuration/permissions/" + (encodeURIComponent(postdata));
                }
            }, {
                mtype: 'GET'
            }, {
                mtype: 'GET'
            });
            return $(window).on('resize', function() {
                var width;
                width = $(window).width();
                width = width - 2;
                if (width > 0 && Math.abs(width - $(table_selector).width()) > 5) {
                    return $(table_selector).setGridWidth(width);
                }
            });
        };

        Permissions.prototype.permissionsChanged = function(success, message, operation) {
            if (success) {
                return window.Configuration.Roles.reload();
            }
        };

        return Permissions;

    })();

    Configuration = (function(_super) {
        __extends(Configuration, _super);

        function Configuration() {
            this.Sections = new Sections;
            this.Roles = new Roles;
            this.Permissions = new Permissions;
        }

        return Configuration;

    })(Sections);

    $(function() {
        return window.Configuration = new Configuration;
    });

}).call(this);


// replace icons with FontAwesome icons in the js grid table
function updatePagerIcons(table) {
    var replacement =
    {
        'ui-icon-seek-first' : 'ace-icon fa fa-angle-double-left bigger-140',
        'ui-icon-seek-prev' : 'ace-icon fa fa-angle-left bigger-140',
        'ui-icon-seek-next' : 'ace-icon fa fa-angle-right bigger-140',
        'ui-icon-seek-end' : 'ace-icon fa fa-angle-double-right bigger-140'
    };
    $('.ui-pg-table:not(.navtable) > tbody > tr > .ui-pg-button > .ui-icon').each(function(){
        var icon = $(this);
        var $class = $.trim(icon.attr('class').replace('ui-icon', ''));

        if($class in replacement) icon.attr('class', 'ui-icon '+replacement[$class]);
    })
}
