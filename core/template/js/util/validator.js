class Validator {
    static validateData(signType) {
        if (signType === 'signIn') {
            return this.signInDataValidating();
        } else if (signType === 'signUp') {
            return this.signUpDataValidating();
        }

        return true;
    }

    static signInDataValidating() {
        let authPassword = $('#auth-password');
        if (authPassword.val().length <= 5) {
            alert("Неправильный пароль - должно быть больше 5 символов!");
            return false;
        }
        return true;
    }

    static signUpDataValidating() {
        let regPassword = $('#reg-password');
        let anotherRegPassword = $('#another-reg-password');
        if (regPassword.val().length <= 5) {
            alert("Неправильный пароль - должно быть больше 5 символов!");
            return false;
        }

        if (regPassword.val() !== anotherRegPassword.val()) {
            alert("Введенные пароли не совпадают");
            return false;
        }
        return true;
    }

    static checkFormat(data, type) {
        switch (type) {
            case 'word':
                for (let i = 0; i < data.length; i++) {
                    if (data[i] >= '0' && data[i] <= '9') return false;
                }
                return true;
        }
        return false;
    }
}