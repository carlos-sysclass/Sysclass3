$SC.module("views.form.questions.edit", function(mod, app, Backbone, Marionette, $, _) {

    $SC.module("crud.views.edit").on("start", function() {
        $SC.module("views.form.questions").start({
            module: this
        });
    });
});
