class Validator {
    static validateFormData(form) {
        if (form === null) {
            return true;
        }

        let formData = new FormData(form);
        let formId = form.id;

        if (formId === 'authorizationForm') {
            return this.signInDataValidating(formData);
        } else if (formId === 'registrationForm') {
            return this.signUpDataValidating(formData);
        } else if (formId === 'profileForm') {
            return this.profileDataValidating(formData);
        }

        return true;
    }

    static signInDataValidating(formData) {
        let authPassword = formData.get('password');
        if (!this.checkFormat(authPassword, 'password')) {
            alert("Неправильный пароль - должно быть больше 5 символов!");
            return false;
        }
        return true;
    }

    static signUpDataValidating(formData) {
        let regPassword = formData.get('password');
        let anotherRegPassword = formData.get('anotherPassword');
        if (!this.checkFormat(regPassword, 'password')) {
            alert("Неправильный пароль - должно быть больше 5 символов!");
            return false;
        }

        if (regPassword !== anotherRegPassword) {
            alert("Введенные пароли не совпадают");
            return false;
        }
        return true;
    }

    static profileDataValidating(formData) {
        let name = formData.get('name');
        let lastName = formData.get('last_name');
        let secondName = formData.get('second_name');
        let password = formData.get('password');
        if (
            !this.checkFormat(name, 'word') ||
            !this.checkFormat(lastName, 'word') ||
            !this.checkFormat(secondName, 'word')
        ) {
            alert("Неправильное ФИО - можно использовать только буквы!");
            return false;
        }

        if (password.length > 0 && !this.checkFormat(password, 'password')) {
            alert("Неправильный пароль - должно быть больше 5 символов!");
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
            case 'password':
                if (data.length <= 5) {
                    alert("Неправильный пароль - должно быть больше 5 символов!");
                    return false;
                }
                return true;
        }
        return false;
    }
}