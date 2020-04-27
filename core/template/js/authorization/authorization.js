class Authorization {
    signButtonsMap = {
        'signOut': {
            buttonId: '#sign-out-btn',
            formId: null,
            closeFormButtonId: null
        },
        'signIn': {
            buttonId: '#sign-in-btn',
            formId: '#authorizationForm',
            closeFormButtonId: '#closeAuthorizationModal'
        },
        'signUp': {
            buttonId: '#sign-up-btn',
            formId: '#registrationForm',
            closeFormButtonId: '#closeRegistrationModal'
        }
    }

    signButtons = {};

    getSignInUpOutButtonsActionName = 'getSignInUpOutButtons';

    constructor() {
        this.getSignInUpOutButtons();
    }

    initialize() {
        this.setupSignInOutUpButtons();
    }

    setupSignInOutUpButtons() {
        let context = this;

        this.signButtonsMap.forEach(function(buttonInfo, buttonName) {
            context.signButtons[buttonName] = $(buttonInfo['buttonId']);
            context.setupButton(buttonName, buttonInfo, context);
        });
    }

    getSignInUpOutButtons() {
        let setupButtonsAfterGet = function(response) {
            $('#sign-inupout-buttons').html(response);
        }

        return Ajax.post(null, this.getSignInUpOutButtonsActionName);
    }

    setupButton(buttonName, buttonInfo, context) {
        if (!context.signButtons[buttonName])
        {
            return;
        }

        let form = null;

        if (buttonInfo['formId']) {
            form = $(buttonInfo['formId'])[0];
        }

        context.signButtons[buttonName].click(function (event) {
            event.preventDefault();

            Ajax.post(null, buttonName, form)
                .then(function() {
                    if (buttonInfo['closeFormButtonId']) {
                        $(buttonInfo['closeFormButtonId']).click();
                    }

                    context.getSignInUpOutButtons().then(
                        context.setupSignInOutUpButtons()
                    );
                });
        });
    }
}

$(document).ready(function () {
    let authorization = new Authorization();
    authorization.initialize();
    // $('#auth-btn').click(function (event)
    // {
    //     event.preventDefault();
    //
    //     let formElement = $('#authorizationForm')[0];
    //     formData.append('action', 'sign_in');
    //
    //     if (signInDataValidating())
    //     {
    //         $.ajax({
    //             data: formData,
    //             processData: false,
    //             contentType: false,
    //             url: 'php/auth/authorization.php',
    //             type: 'POST',
    //             success: function () {
    //                 $('#closeAuthorizationModal').click();
    //                 setupSignInUpOutButtons();
    //             }
    //         });
    //     }
    // });
    // $('#reg-btn').click(function (event)
    // {
    //     event.preventDefault();
    //
    //     var formData = new FormData($('#registrationForm')[0]);
    //     formData.append('action', 'sign_up');
    //
    //     if (signUpDataValidating())
    //     {
    //         $.ajax({
    //             data: formData,
    //             processData: false,
    //             contentType: false,
    //             url: 'php/auth/authorization.php',
    //             type: 'POST',
    //             success: function (response)
    //             {
    //                 if (response !== ''){
    //                     alert(response);
    //                 }
    //                 else
    //                 {
    //                     $('#closeRegistrationModal').click();
    //                     setupSignInUpOutButtons();
    //                 }
    //             }
    //         });
    //     }
    // });
});