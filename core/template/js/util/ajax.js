class Ajax {
    static url = '/core/ajax/ajaxcontroller.php';
    static actionFieldName = 'action';

    static post(data, action, form = null) {
        let self = this;
        return new Promise(function(resolve,reject)
        {
            let request = new XMLHttpRequest();

            let formData = null;
            if (form) {
                formData = new FormData(form);
            } else {
                formData = new FormData();
            }

            formData.append(self.actionFieldName, action)
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