/**
 * CREATE THE TYPED FIELDS, BASED ON [SC-280] ISSUE
 * ALL "TYPED FIELDS" MUST BE MOVED TO HERE 
 */

$SC.module("ui.field.date", function(mod, app, Backbone, Marionette, $, _){
    /**
     * @todo Create date-field implementation, based on ui.js handleDatepickers method
     */
    /*
    this._Masks = {
        'default' : "US",
        'US' : "(000) 000-0000",
        'BR' : function(value) {
            // Brazilian 9-digit logic
            return "(00) 0000-0000";
        }
    };

    this._createValidationMethods = function() {
        $.validator.addMethod("phoneUS", function(phone_number, element) {
            phone_number = phone_number.replace(/\s+/g, "");
            return this.optional(element) || phone_number.length > 9 &&
                phone_number.match(/^(\+?1-?)?(\([2-9]\d{2}\)|[2-9]\d{2})-?[2-9]\d{2}-?\d{4}$/);
        }, "Please, specify a valid US phone number");

        $.validator.addMethod("phoneBR", function(phone_number, element) {

            return true;
            phone_number = phone_number.replace(/\s+/g, "");
            return this.optional(element) || phone_number.length > 9 &&
                phone_number.match(/^(\+?1-?)?(\([2-9]\d{2}\)|[2-9]\d{2})-?[2-9]\d{2}-?\d{4}$/);
        }, "Please, specify a valid US phone number");

        $.validator.addMethod("countryphone", function(phone_number, element) {

            if (this.optional(element)) {
                return true;
            }
            var sourceValue = mod._getCountryFromInput(element);

            var methodName = "phone" + sourceValue;

            if (_.has(jQuery.validator.methods, methodName)) {
                return jQuery.validator.methods["phone" + sourceValue].call(this, phone_number, element);
            }
            return false;

        }, "Please, specify a valid phone number");
    };

    this._createMasksMethods = function() {

    };
    
    this._getCountryFromInput = function(element) {
        var sourceSelector = $(element).data("country-selector");
        if ($(sourceSelector).size() > 0) {
            return $(sourceSelector).val();
        }
        return null;
    };

    this.refresh = function(context) {
        if ($("[data-type-field='phone']", context).size() > 0) {
            var self = this;

            $("[data-type-field='phone']", context).each(function() {
                $(this).attr({
                    'data-rule-countryphone' : "true", // VALIDATION RULE
                    'data-mask-countryphone' : "true" // VALIDATION RULE
                });

                if ($.fn.mask) {
                    var options =  {
                        onKeyPress: function(cep, e, field, options) {
                            var country = mod._getCountryFromInput(field);

                            if (_.has(mod._Masks, country)) {
                                var mask_value = mod._Masks[country];
                                if (_.isFunction(mod._Masks[country])) {
                                    mask_value = mod._Masks[country];
                                }

                                $(field).mask(mask_value, options);
                            } else {

                            }
                        }
                    };
                    
                    $(this)
                        //.not("[readonly]")
                        .mask(mod._Masks['default'], options);
                }
            });
        }
    };

    this.on("start", function(opt) {
        if ($.fn.validate) {
            this._createValidationMethods();
        }
        if ($.fn.mask) {
            this._createMasksMethods();
        }
    });
    */
});
