class Authorization {
    signButtonsMap = [
        {
            buttonName: 'signOut',
            buttonId: '#sign-out-btn',
            formId: null,
            closeFormButtonId: null
        },
        {
            buttonName: 'signIn',
            buttonId: '#sign-in',
            formId: '#authorizationForm',
            closeFormButtonId: '#closeAuthorizationModal'
        },
        {
            buttonName: 'signUp',
            buttonId: '#sign-up',
            formId: '#registrationForm',
            closeFormButtonId: '#closeRegistrationModal'
        }
    ]

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

        this.signButtonsMap.forEach(function(buttonInfo) {
            context.signButtons[buttonInfo['buttonName']] = $(buttonInfo['buttonId']);
            context.setupButton(buttonInfo, context);
        });
    }

    getSignInUpOutButtons() {
        let setupButtonsAfterGet = function(response) {
            $('#sign-inupout-buttons').html(response);
        }

        return Ajax.post(null, this.getSignInUpOutButtonsActionName).then(setupButtonsAfterGet);
    }

    setupButton(buttonInfo, context) {
        let buttonName = buttonInfo['buttonName'];
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