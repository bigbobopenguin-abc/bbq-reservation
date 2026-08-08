$(document).ready(function () {

    $("#contactForm").on("submit", function (e) {
        let isValid = true;
        $(".field-error").text("");

        const name = $("#name").val().trim();
        if (name === "") {
            $("#err-name").text("Please enter your name.");
            isValid = false;
        }

        const email = $("#email").val().trim();
        const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailPattern.test(email)) {
            $("#err-email").text("Please enter a valid email address.");
            isValid = false;
        }

        const subject = $("#subject").val();
        if (!subject) {
            $("#err-subject").text("Please select an enquiry type.");
            isValid = false;
        }

        const message = $("#message").val().trim();
        if (message === "") {
            $("#err-message").text("Please enter a message.");
            isValid = false;
        }

        if (!isValid) {
            e.preventDefault();
        }
    });

});