function SecurityController() {
    var authorizeAction = function() {
        $('#loginbox').fadeOut();
    };

    this.initializeLoginForm = function() {
        $('#loginform').ajaxForm(function(response) {
            var json = JSON.parse(response);
            alert(response);
            authorizeAction();
        });
    }
}
