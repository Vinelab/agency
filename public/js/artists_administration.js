// Generated by CoffeeScript 1.6.3
(function() {
  var Administration;

  Administration = (function() {
    function Administration() {
      this.set_form_validation();
      this.set_form_deletion_confirmation();
    }

    Administration.prototype.set_form_deletion_confirmation = function(e) {
      var form;
      form = $('#admin-delete');
      return $('#delete-admin-button').on(ace.click_event, function() {
        var message;
        message = '<h1 class="alert alert-danger"><strong>This cannot be undone, press Ok to continue...</strong></h1>';
        return bootbox.confirm(message, function(confirmed) {
          if (confirmed) {
            return form.submit();
          }
        });
      });
    };

    Administration.prototype.set_form_validation = function() {
      var form;
      form = $('#admin-info');
      if (form.length > 0) {
        form.submit(function() {
          return $(this).valid();
        });
        return form.validate({
          errorElement: 'div',
          errorClass: 'help-block',
          focusInvalid: true,
          onsubmit: false,
          rules: {
            'info[name]': {
              required: true
            },
            'info[email]': {
              required: true,
              email: true
            }
          },
          messages: {
            'info[email]': {
              required: "Please provide an email.",
              email: "Please provide a valid email."
            }
          },
          highlight: function(e) {
            return $(e).closest('.form-group').removeClass('has-info').addClass('has-error');
          },
          success: function(e) {
            $(e).closest('.form-group').removeClass('has-error').addClass('has-info');
            return $(e).remove();
          },
          errorPlacement: function(error, element) {
            var controls;
            if (element.is(':checkbox') || element.is(':radio')) {
              controls = element.closest('div[class*="col-"]');
              if (controls.find(':checkbox,:radio').length > 1) {
                return controls.append(error);
              } else {
                return error.insertAfter(element.nextAll('.lbl:eq(0)').eq(0));
              }
            } else if (element.is('.select2')) {
              return error.insertAfter(element.siblings('[class*="select2-container"]:eq(0)'));
            } else if (element.is('.chosen-select')) {
              return error.insertAfter(element.siblings('[class*="chosen-container"]:eq(0)'));
            } else {
              return error.insertAfter(element.parent());
            }
          },
          submitHandler: function(form) {
            return true;
          }
        });
      }
    };

    return Administration;

  })();

  $(function() {
    var administration;
    return administration = new Administration;
  });

}).call(this);
