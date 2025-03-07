$(document).ready(function(){

    function validateFullname(){

        function validate(){

            fullname = $.trim($("#fullname").val());

            if (fullname.match(/^[a-zA-Z\s]{2,45}$/)){

                $("#fullnameErr").fadeOut();
                $("#fullnameFeedback").removeClass("negative");
                $("#fullnameFeedback").addClass("positive");

                window.validName = true;
                return true;

            } else {

                if(!fullname){

                    window.validName = false;
                    $("#fullnameErr").hide();
                    $("#fullnameFeedback").removeClass("positive");
                    $("#fullnameFeedback").removeClass("negative");

                    return false;
                }else{

                    window.validName = false;
                    $("#fullnameErr").fadeIn();
                    $("#fullnameFeedback").addClass("negative");
                    $("#fullnameFeedback").removeClass("positive");

                    return false;
                }

            }

        }

        return validate();

    }

    function validatePass(){


        if ($("#pass").val().match(/^.{6,}$/)){

            $("#passwordErr").fadeOut();
            $("#passFeedback").removeClass("negative");
            $("#passFeedback").addClass("positive");

            window.validPass = true;
            return true;

        } else {

            if(!$("#pass").val()){
                window.validPass = false;
                $("#passwordErr").hide();
                $("#passFeedback").removeClass("positive");
                $("#passFeedback").removeClass("negative");

                return false;

            }else{
                window.validPass = false;
                $("#passwordErr").fadeIn();
                $("#passFeedback").addClass("negative");
                $("#passFeedback").removeClass("positive");

                return false;
            }

        }
    }

    function validatePassConfirm(){

        if ($("#passConfirm").val() == $("#pass").val() && $("#passConfirm").val() != ""){

            $("#passwordMatchErr").fadeOut();
            $("#passConfirmFeedback").addClass("positive");
            $("#passConfirmFeedback").removeClass("negative");

            window.validPassConfirm = true;
            return true;
        } else {

            if(!$("#passConfirm").val()){
                window.validPassConfirm = false;
                $("#passwordMatchErr").hide();
                $("#passConfirmFeedback").removeClass("positive");
                $("#passConfirmFeedback").removeClass("negative");

                return false;
            } else {
                window.validPassConfirm = false;
                $("#passwordMatchErr").fadeIn();
                $("#passConfirmFeedback").removeClass("positive");
                $("#passConfirmFeedback").addClass("negative");

                return false;
            }

        }

    }

    function submitForm(){

        responseField = $("input#recaptcha_response_field").val();

        if(window.validUsername && window.validEmail && window.validName && window.validPass && window.validPassConfirm && responseField){
            // alert("sdasda");
            $("form:first").submit();
        }else{
            $("#fieldBlank").fadeIn();
        }
    }

$("#fullname").bind("keyup", logKeyPress);
$("#email").bind("keyup", logKeyPress);
$("#username").bind("keyup", logKeyPress);
$("#pass").bind("keyup", logKeyPress);
$("#passConfirm").bind("keyup", logKeyPress);

    var interval = 1000;
    var filterValue = "";

    function logKeyPress() {
        var now = new Date().getTime();
        var lastTime = this._keyPressedAt || now;
        this._keyPressedAt = now;
        if (!this._monitoringSearch) {
            this._monitoringSearch = true;
            var input = this;
            window.setTimeout(
                function() {
                    search(input);
                }, 0);
        }
    }
    function search(input) {
        var now = new Date().getTime();
        var lastTime = input._keyPressedAt;
        if ((now - lastTime) > interval) {

            // Validation Functions ----


            switch ($(input).attr("id")){
                case "fullname":
                    validateFullname();
                    break;
                case "email":
                    ajaxCheckEmail($('#email').val());
                    break;
                case "username":
                    ajaxCheckUsername($('#username').val());
                    break;
                case "pass":
                    validatePass();
                    validatePassConfirm();
                    break;
                case "passConfirm":
                    validatePass();
                    validatePassConfirm();
                    break;

            }

            input._monitoringSearch = false;
        }
        else {
            window.setTimeout(
                function() {
                    search(input);
                }, 0);
        }
    }
});

