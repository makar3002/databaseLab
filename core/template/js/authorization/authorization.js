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

    }

    initialize() {
        let context = this;
        this.getSignInUpOutButtons().then(function () {
            context.setupSignInOutUpButtons()
        });
    }

    getSignInUpOutButtons() {
        let setupButtonsAfterGet = function(response) {
            $('#sign-inupout-buttons').html(response);
        }

        return Ajax.post(null, this.getSignInUpOutButtonsActionName).then(setupButtonsAfterGet);
    }

    setupSignInOutUpButtons() {
        let context = this;

        this.signButtonsMap.forEach(function(buttonInfo) {
            context.signButtons[buttonInfo['buttonName']] = $(buttonInfo['buttonId']);
            context.setupButton(buttonInfo, context);
        });
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

            if (!Validator.validateData(buttonName)) {
                return;
            }

            Ajax.post(null, buttonName, form)
                .then(function(response) {
                    if (response) {
                        alert(response);
                        return;
                    }

                    context.getSignInUpOutButtons().then(function () {
                        context.setupSignInOutUpButtons()
                    });

                    if (buttonInfo['closeFormButtonId']) {
                        $(buttonInfo['closeFormButtonId']).click();
                    }
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