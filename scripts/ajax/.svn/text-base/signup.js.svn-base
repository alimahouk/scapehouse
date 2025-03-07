function ajaxCheckUsername(username){

    username = $.trim(username);

    if (username){

        if (username.match(/^[a-zA-Z0-9._-]{2,20}$/)){

            $.ajax({
                type:"POST",
                url:"/logged/ajax/checkusername",
                data: "username=" + username,
                success: function(data) {

                    if(data == "true"){
                        window.validUsername = false;
                        $("#usernameFeedback").html("Username taken");
                        $("#usernameFeedback").addClass("negative");
                        $("#usernameFeedback").removeClass("positive");
                        $("#usernameErr").fadeOut();
                        return false;

                    }else{
                        window.validUsername = true;
                        $("#usernameFeedback").html("Username available");
                        $("#usernameFeedback").addClass("positive");
                        $("#usernameFeedback").removeClass("negative");
                        $("#usernameErr").fadeOut();
                        return true;
                    }

                }
            });
        
        } else {
            window.validUsername = false;
            $("#usernameFeedback").html("");
            $("#usernameFeedback").addClass("negative");
            $("#usernameFeedback").removeClass("positive");
            $("#usernameErr").fadeIn();
            return false;
        }
        

    } else {

        window.validUsername = false;
        $("#usernameFeedback").html("");
        $("#usernameFeedback").removeClass("positive");
        $("#usernameFeedback").removeClass("negative");
        $("#usernameErr").hide();
        return false;
    }

}

function ajaxCheckEmail(email){

    if (email){

        if (email.match(/^[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}$/i)){

            $.ajax({
                type:"POST",
                url:"/logged/ajax/checkemail",
                data: "email=" + email,
                success: function(data) {

                    if(data == "true"){
                        window.validEmail = false;
                        $("#existsEmailErr").fadeIn();
                        $("#emailErr").hide();
                        $("#emailFeedback").html("Email aready in use");
                        $("#emailFeedback").addClass("negative");
                        $("#emailFeedback").removeClass("positive");

                    }else{
                        window.validEmail = true;
                        $("#emailFeedback").html("");
                        $("#existsEmailErr").hide();
                        $("#emailErr").hide();
                        $("#emailFeedback").addClass("positive");
                        $("#emailFeedback").removeClass("negative");

                    }

                }
            });

        } else {
            window.validEmail = false;
            $("#emailErr").fadeIn();
            $("#existsEmailErr").hide();
            $("#emailFeedback").html("");
            $("#emailFeedback").addClass("negative");
            $("#emailFeedback").removeClass("positive");
        }


    } else {
        window.validEmail = false;
        $("#emailErr").hide();
        $("#existsEmailErr").hide();
        $("#emailFeedback").html("");
        $("#emailFeedback").removeClass("positive");
        $("#emailFeedback").removeClass("negative");
    }

}
