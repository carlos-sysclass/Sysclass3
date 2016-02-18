$SC.module("fields", function(mod, app, Backbone, Marionette, $, _) {
    var handleFunctions = {
        email : function(el) {
            var me = $(el);
            me.attr("data-rule-email", "true");
        },
        cnpj : function(el) {
            var me = $(el);
            me.attr("data-rule-cnpj", "true");
            me.attr("data-mask", "00.000.000/0000-00");
        },
        phone_br : function(el) {
            var me = $(el);
            me.attr("data-rule-phone_br", "true");

            var phoneBrMaskBehavior = function (val) {
                var ddd = val.replace(/\D/g, '').substr(0, 2);
                var firstDigit = val.replace(/\D/g, '').substr(2, 1);

                if (_.contains([9,8,7,6], parseInt(firstDigit))) {

                    var ddd9digitos = [
                        // Em 25 de Agosto de 2013 alterados os números móveis dos DDDs 12, 13, 14, 15, 16, 17, 18 e 19; já implementado.
                        11, 12, 13, 14, 15, 16, 17, 18, 19, 
                        // Em 27 de Outubro de 2013 alterados os números dos DDDs 21, 22, 24, 27 e 28; já implementado.
                        21, 22, 24, 27, 28,
                        // Em 02 de Novembro de 2014 para os Códigos Nacionais 91, 92, 93, 94, 95, 96, 97, 98 e 99; já implementado.
                        91, 92, 93, 94, 95, 96, 97, 98, 99,
                        // Em 31 de Maio de 2015 para os Códigos Nacionais 81, 82, 83, 84, 85, 86, 87, 88 e 89; já implementado.
                        81, 82, 83, 84, 85, 86, 87, 88, 89,
                        // Até 31 de dezembro de 2015 Cód'igos Nacionais 31, 32, 33, 34, 35, 37, 38, 71, 73, 74, 75, 77 e 79 em 11/10/2015.
                        31, 32, 33, 34, 35, 37, 38, 71, 73, 74, 75, 77, 79,
                        // Até 31 de dezembro de 2016 para os Códigos Nacionais 41, 42, 43, 44, 45, 46, 47, 48, 49, 51, 53, 54, 55, 61,62, 63, 64, 65, 66, 67, 68 e 69.
                        //41, 42, 43, 44, 45, 46, 47, 48, 49, 51, 53, 54, 55, 61, 62, 63, 64, 65, 66, 67, 68, 69
                    ];
                    console.warn(_.contains(ddd9digitos, parseInt(ddd)));
                    if (_.contains(ddd9digitos, parseInt(ddd))) {
                        return '(00) 00000-0000';
                    } else {
                        return '(00) 0000-0000';
                    }
                } else {

                    console.warn('(00) 0000-0000');
                    return '(00) 0000-0000';
                }
            },
            spOptions = {
                onKeyPress: function(val, e, field, options) {
                    field.mask(phoneBrMaskBehavior.apply({}, arguments), options);
                }
            };

            console.warn(phoneBrMaskBehavior, spOptions);

            $(el).mask(phoneBrMaskBehavior, spOptions);

            //me.attr("data-mask", "00.000.000/0000-00");
            //me.attr("data-mask-reverse", "true");
        },
        zipcode_br : function(el) {
            var me = $(el);
            me.attr("data-rule-zipcode_br", "true");
            me.attr("data-mask", "00000-000");
        },
        integer : function(el) {
            var me = $(el);
            me.attr("data-rule-digits", "true");
            me.attr("data-mask", "09#");
        },
        float : function(el) {
            var me = $(el);
            me.attr("data-rule-number", "true");
            me.attr("data-mask", "#90.00");
            me.attr("data-mask-reverse", "true");
        }
    };

    this.handle = function(name, el) {
        console.warn(name, el);
        if (_.has(handleFunctions, name)) {
            return handleFunctions[name](el);
        }
        return false;
    };
    this.getRawValue = function(name, el) {
        if ($(el).is(':input')) {
            if ($(el).is(':input[data-mask]')) {
                return $(el).cleanVal();
            }
            return $(el).val();
        }
        return $(el).html();
   };
   /*
   this.on("start", function() {
        $('[data-helper]').each(function(i, el) {
            if (_.isEmpty($(el).data('helper'))) {
                return;
            }
            var helper = $(el).data('helper');

            mod.handle(helper, el);

        }.bind(this));
   });
   */
});