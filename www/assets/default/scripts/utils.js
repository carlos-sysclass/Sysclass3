$SC.module("utils", function(mod, app, Backbone, Marionette, $, _){

    this.truncate = function (str, limit) {
        var bits, i;
        if ('string' !== typeof str) {
            return '';
        }
        bits = str.split('');
        if (bits.length > limit) {
            for (i = bits.length - 1; i > -1; --i) {
                if (i > limit) {
                    bits.length = i;
                }
                else if (' ' === bits[i]) {
                    bits.length = i;
                    break;
                }
            }
            bits.push('...');
        }
        return bits.join('');
    };
    this.toggleAt = function (str, limit, size) {
        var bits, i;
        var chars = [];
        if ('string' !== typeof str) {
            return '';
        }
        bits = str.split('');
        if (bits.length > limit) {
            //chars.push(bits[bits.length - 1]);
            for (i = bits.length - 1; i > -1; --i) {
                if (i > limit) {
                    chars.push(bits[i]);
                    bits.length = i;
                }
                else if (' ' === bits[i]) {
                    //chars.push(bits[i]);
//                    bits.length = i;
                    //break;
                }
            }
            chars = chars.reverse();
            var suffix = '<span class="visible-' + size + ' inline">' + chars.join('') + '</span>' + '<span class="hidden-' + size + ' inline">...</span>';
            bits.push(suffix);
        }
       
        return bits.join('');
    };

//var string = 'Pre-register now for the Science and Engineering Career Fair on Tuesday, Feb. 4';
//$SC.module("utils").toggleAt(string, 50, "visible-lg");
});
