class Profile {
    componentClass = null;
    signButtonsMap = [
        {
            buttonName: 'saveProfile',
            buttonId: '#profile-save-btn',
            formId: '#profileForm',
            closeFormButtonId: null
        },
    ];
    buttons = {};

    getProfileFormActionName = 'getProfileForm';

    constructor(componentClass) {
        this.componentClass = componentClass;
    }

    initialize() {
        let context = this;
        this.getProfileForm().then(function () {
            context.setupProfileButtons()
        });

        document.addEventListener('Authorization::Success', function () {
            context.initialize();
        });
    }

    getProfileForm() {
        let setupFormAfterGet = function(response) {
            $('#main').html(response);
        }

        return Ajax.post(null, this.getProfileFormActionName, this.componentClass).then(setupFormAfterGet);
    }

    setupProfileButtons() {
        let context = this;

        this.signButtonsMap.forEach(function(buttonInfo) {
            context.buttons[buttonInfo['buttonName']] = $(buttonInfo['buttonId']);
            context.setupButton(buttonInfo, context);
        });
    }

    setupButton(buttonInfo, context) {
        let buttonName = buttonInfo['buttonName'];
        if (!context.buttons[buttonName])
        {
            return;
        }

        let form = null;

        if (buttonInfo['formId']) {
            form = $(buttonInfo['formId'])[0];
        }

        context.buttons[buttonName].click(function (event) {
            event.preventDefault();

            if (!Validator.validateFormData(form)) {
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