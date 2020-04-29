class Authorization {
    componentClass = null;
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
    ];
    signButtons = {};

    getSignInUpOutButtonsActionName = 'getSignInUpOutButtons';

    constructor(componentClass) {
        this.componentClass = componentClass;
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

        return Ajax.post(null, this.getSignInUpOutButtonsActionName, this.componentClass).then(setupButtonsAfterGet);
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

            Ajax.post(null, buttonName, context.componentClass, form)
                .then(function(response) {
                    if (response) {
                        alert(response);
                        return;
                    }
                    if (buttonInfo['closeFormButtonId']) {
                        $(buttonInfo['closeFormButtonId']).click();
                    }

                    setTimeout(function () {
                        context.initialize();
                    }, buttonName === 'signOut' ? 0 : 150);
                }) ;
        });
    }
}