class Administration

    constructor: ->

        @set_form_validation()
        @set_form_deletion_confirmation()

    set_form_deletion_confirmation: (e)->

        form = $('#admin-delete')

        $('#delete-admin-button').on ace.click_event, ->
            message = '<h1 class="alert alert-danger"><strong>This cannot be undone, press Ok to continue...</strong></h1>'
            bootbox.confirm message, (confirmed)->
                form.submit() if confirmed

    set_form_validation: ->

        form = $('#admin-info')

        if form.length > 0

            # Override submission to check for validity
            form.submit -> $(@).valid()

            # Set up validation
            form.validate

                errorElement: 'div'
                errorClass: 'help-block'
                focusInvalid: yes

                onsubmit: no

                rules:
                    'info[name]':
                        required: yes
                    'info[email]':
                        required: yes
                        email:yes

                messages:
                    'info[email]':
                        required: "Please provide an email.",
                        email: "Please provide a valid email."

                highlight: (e)-> $(e).closest('.form-group').removeClass('has-info').addClass('has-error')

                success: (e)->
                    $(e).closest('.form-group').removeClass('has-error').addClass('has-info')
                    $(e).remove()

                errorPlacement: (error, element)->
                    if element.is(':checkbox') or element.is(':radio')
                        controls = element.closest('div[class*="col-"]')

                        if controls.find(':checkbox,:radio').length > 1
                            controls.append(error);
                        else error.insertAfter(element.nextAll('.lbl:eq(0)').eq(0))

                    else if element.is('.select2')
                        error.insertAfter(element.siblings('[class*="select2-container"]:eq(0)'))

                    else if element.is('.chosen-select')
                        error.insertAfter(element.siblings('[class*="chosen-container"]:eq(0)'))

                    else error.insertAfter(element.parent())

                submitHandler: (form)-> yes


$ -> administration = new Administration