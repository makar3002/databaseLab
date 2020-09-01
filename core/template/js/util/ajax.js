class Ajax {
    static url = '/ajax.php';
    static actionFieldName = 'action';
    static ajaxRequestFieldName = 'AJAX_REQUEST';
    static componentClassFieldName = 'componentClass';

    static post(data, action, componentClass, form = null) {
        let self = this;
        return new Promise(function(resolve,reject)
        {
            let request = new XMLHttpRequest();

            let formData = new FormData();
            if (form) {
                formData = new FormData(form);
            }

            formData.append(self.actionFieldName, action);
            formData.append(self.ajaxRequestFieldName, true);
            formData.append(self.componentClassFieldName, componentClass);
            for (let key in data) {
                formData.append(key, data[key]);
            }

            request.open('POST', self.url, true);

            request.onreadystatechange = function () {
                if (request.readyState === 4) {
                    if(request.status === 200)
                        resolve(JSON.parse(request.responseText));
                    else
                        reject(Error(request.statusText));
                }
            };
            request.onerror = function() {
                reject(Error("network error"));
            };
            request.send(formData);
        });
    }
}