// Generated by CoffeeScript 1.7.1
(function() {
  var Roles;

  Roles = (function() {
    function Roles() {
      this.table_selector = '#roles-table';
      this.pager_selector = '#roles-pager';
      this.loadPermissions((function(_this) {
        return function(permissions) {
          return _this.configure(permissions);
        };
      })(this));
    }

    Roles.prototype.loadPermissions = function(done) {
      return this.getAllPermissions((function(_this) {
        return function(permissions) {
          window.all_permissions = permissions;
          if (typeof done === 'function') {
            return done(permissions);
          }
        };
      })(this));
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
      var pager_selector, table_selector;
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
              dataInit: (function(_this) {
                return function(el) {
                  return $(el).css('min-height', '150px');
                };
              })(this)
            }
          }
        ]
      }).navGrid(pager_selector, {
        view: true,
        viewicon: 'icon-zoom-in grey',
        add: true,
        addicon: 'icon-plus-sign purple',
        edit: true,
        editicon: 'icon-pencil blue',
        del: true,
        delicon: 'icon-trash red',
        search: true,
        searchicon: 'icon-search orange',
        refresh: true,
        refreshicon: 'icon-refresh green',
        reloadAfterSubmit: true
      }, {
        editCaption: 'Edit Role',
        mtype: 'PUT',
        recreateForm: true,
        closeAfterEdit: true,
        url: 'configuration/roles/id',
        onclickSubmit: function(params, postdata) {
          console.log("/cms/configuration/roles/" + (encodeURIComponent(postdata['roles-table_id'])));
          return params.url = "/cms/configuration/roles/" + (encodeURIComponent(postdata['roles-table_id']));
        }
      }, {
        mtype: 'POST',
        closeAfterAdd: true,
        reloadAfterSubmit: true,
        url: 'configuration/roles'
      }, {
        mtype: 'DELETE',
        onclickSubmit: function(params, postdata) {
          return params.url = "/cms/configuration/roles/" + (encodeURIComponent(postdata));
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
      return this.loadPermissions((function(_this) {
        return function(permissions) {
          return $(_this.table_selector).jqGrid('setColProp', 'permissions', {
            editoptions: {
              value: _this.getSelectablePermissions()
            }
          }).trigger('reloadGrid');
        };
      })(this));
    };

    return Roles;

  })();

}).call(this);
