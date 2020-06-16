class SqlQuery {
    componentClass = 'Core\\Component\\SqlQuery\\SqlQueryComponent';
    signButtonsMap = [
        {
            buttonName: 'executeQuery',
            buttonId: '#execute-query-btn',
            formId: '#queryForm',
            closeFormButtonId: null
        },
    ];
    buttons = {};

    initialize() {
        this.setupButtons()
    }

    setupButtons() {
        this.signButtonsMap.forEach(function(buttonInfo) {
            this.buttons[buttonInfo['buttonName']] = $(buttonInfo['buttonId']);
            this.setupButton(buttonInfo);
        }.bind(this));
    }

    setupButton(buttonInfo) {
        let buttonName = buttonInfo['buttonName'];
        if (!this.buttons[buttonName])
        {
            return;
        }

        let form = null;

        if (buttonInfo['formId']) {
            form = $(buttonInfo['formId'])[0];
        }

        this.buttons[buttonName].click(function (event) {
            event.preventDefault();
            Ajax.post(null, buttonName, this.componentClass, form)
                .then(function(response) {
                    $('#query-result').html(response);
                }.bind(this)) ;
        }.bind(this));
    }
}