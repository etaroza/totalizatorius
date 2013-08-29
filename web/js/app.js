var App = App || {};

App.Util = {
    init: function() {

    },
    
    validateForm: function(form) {
        var errors = 0;

        if (typeof form == 'string') {
            form = $(form);
        }
        
        form.find(':input:not(button)').each(function(){
            errors += App.Util.validateField($(this));
        });

        if (errors > 0) {
            if (form.find('.response').length == 1) {
                form.find('.response').html('Please fill all required fields.');
            }
            return false;
        } else {
            return true;
        }
    },

    validateField: function(field, type) {
        var errors = 0;
        var container = field.parents('.control-group');

        field.parents('.input-prepend:first').removeClass('error');
        field.removeClass('error');
        container.removeClass('error');

        if ((field.val() === '' || field.val() == field.attr('placeholder')) && field.attr('required') == 'required' && !field.hasClass('placeholder')){
            errors++;
        }else{
            if (field.attr('required') == 'required' && field.attr('type') == 'checkbox' &&  field.attr('checked') != 'checked'){
                errors++;
                field.parent().children('.checkbox').addClass('error');
            }
            if (field.attr('type') == 'email' && !App.Util.isValidEmail(field.val())){
                errors++;
            }
        }

        if (errors > 0) {
            field.addClass('error');
            container.addClass('error');
            field.parents('.input-prepend:first').addClass('error');
        }

        return errors;
    },

    //returns true if pattern is a valid email address
    isValidEmail: function(emailAddress) {
        var emailPattern = /^[a-zA-Z0-9\+._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/;
        return emailPattern.test(emailAddress);
    }
};

$(document).ready(function() {
    App.Util.init();
});