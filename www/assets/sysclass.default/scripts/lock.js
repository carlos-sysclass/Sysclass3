var Lock = function () {

    return {
        //main function to initiate the module
        init: function () {

             $.backstretch([
		        "assets/sysclass.default/img/bg/1.jpg",
		        "assets/sysclass.default/img/bg/2.jpg",
		        "assets/sysclass.default/img/bg/3.jpg",
		        "assets/sysclass.default/img/bg/4.jpg"
		        ], {
		          fade: 1000,
		          duration: 8000
		      });
        }

    };

}();