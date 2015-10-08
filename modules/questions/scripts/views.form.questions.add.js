$SC.module("views.form.questions.add", function(mod, app, Backbone, Marionette, $, _) {
    
    $SC.module("crud.views.add").on("start", function() {
        $SC.module("views.form.questions").start({
            module: this
        });

        this.listenTo(this.formView, "before:save", function(model) {
            model.setResponseType("redirect");
        });

    });

    $SC.module("crud.views.edit").on("start", function() {
        if (!mod._isInitialized) {
            mod.start({
                module: this
            });
        }
    });
});
