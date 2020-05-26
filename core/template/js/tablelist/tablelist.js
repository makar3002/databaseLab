class TableList {
    entityClass = null;
    formInfoList = {
        'addForm': {
            formId: 'add-form',
            popupId: 'add-popup',
            closePopupButtonId: 'add-popup-close-btn',
            submitFormButtonId: 'submit-add-btn',
            formAction: 'addElement'
        },
        'updateForm': {
            formId: 'update-form',
            popupId: 'update-popup',
            closePopupButtonId: 'update-popup-close-btn',
            submitFormButtonId: 'submit-update-btn',
            formAction: 'updateElement'
        },
        'deleteForm': {
            formId: 'delete-form',
            popupId: 'delete-popup',
            closePopupButtonId: 'delete-popup-close-btn',
            submitFormButtonId: 'submit-delete-btn',
            formAction: 'deleteElement'
        }
    };
    entityTableClass = null;
    componentClass = 'Core\\Component\\TableList\\TableListComponent';
    sort = {};

    constructor(entityClass, entityTableClass, sortFieldName, sortType) {
        this.entityClass = entityClass;
        this.entityTableClass = entityTableClass;
        this.sort[sortFieldName] = sortType;
    }

    initialize() {
        $(document).on('click', '.update-btn', this.onClickUpdateButton.bind(this));
        $(document).on('click', '.add-btn', this.onClickAddButton.bind(this));
        $(document).on('click', '.delete-btn', this.onClickDeleteButton.bind(this));
        $(document).on('click', '.td-header', this.onClickHeaderElement.bind(this));
    }

    onClickHeaderElement(event)
    {
        let element = event.target;
        if (element.tagName !== 'DIV') {
            element = element.parentElement;
        }
        let elementDataset = element.dataset;
        let sort = elementDataset.sort;
        let fieldName = elementDataset.fieldName
        if (!fieldName) {
            return;
        }
        if (!sort || sort === 'DESC') {
            sort = 'ASC'
        } else {
            sort = 'DESC'
        }

        this.sort = {};
        this.sort[fieldName] = sort;

        this.refreshTable()
    }

    onClickUpdateButton(event)
    {
        let elementData = {
            ID: event.target.dataset.elementId,
            ENTITY_CLASS: this.entityClass,
            ENTITY_TABLE_CLASS: this.entityTableClass,
        };

        Ajax.post(elementData, 'getElementInfo', this.componentClass).then(
            this.openUpdatePopup.bind(this)
        );
    }

    onClickAddButton()
    {
        this.openAddPopup();
    }

    onClickDeleteButton(event)
    {
        this.openDeletePopup(event.target.dataset.elementId);
    }

    openUpdatePopup(response)
    {
        let updateFormInfo = this.formInfoList['updateForm'];
        let form = $('#' + updateFormInfo.formId);
        if (null == form) {
            return;
        }

        for (let key in response) {
            let input = $('#update-' + key);
            if (null == input) {
                continue;
            }

            input.val(response[key]);
        }

        $('#' + updateFormInfo.submitFormButtonId).click(function (event) {
            event.preventDefault();
            this.submitForm(updateFormInfo);
        }.bind(this));
        $('#' + updateFormInfo.popupId).modal('show');
    }

    openAddPopup()
    {
        let addFormInfo = this.formInfoList['addForm'];

        $('#' + addFormInfo.submitFormButtonId).click(function (event) {
            event.preventDefault();
            this.submitForm(addFormInfo);
        }.bind(this));
        $('#' + addFormInfo.popupId).modal('show');
    }

    openDeletePopup(elementId)
    {
        let deleteFormInfo = this.formInfoList['deleteForm'];

        let input = $('#delete-ID');
        input.val(elementId);

        $('#' + deleteFormInfo.submitFormButtonId).click(function (event) {
            event.preventDefault();
            this.submitForm(deleteFormInfo);
        }.bind(this));
        $('#' + deleteFormInfo.popupId).modal('show');
    }

    submitForm(formInfo)
    {
        let form = $('#' + formInfo.formId);
        let elementData = {
            ENTITY_CLASS: this.entityClass,
            ENTITY_TABLE_CLASS: this.entityTableClass,
        };

        let data = form.serializeArray().reduce(
            function(obj, item) {
                obj[item.name] = item.value;
                return obj;
            }, {}
        );

        if (data['ID']) {
            elementData['ID'] = data['ID'];
            delete data['ID'];
        }
        elementData['FIELDS'] = JSON.stringify(data);

        Ajax.post(elementData, formInfo.formAction, this.componentClass).then(
            function (response) {
                if (response !== '') {
                    alert(response);
                } else {
                    this.refreshTable();
                    $('#' + formInfo.closePopupButtonId).click();
                }
            }.bind(this)
        );
    }

    refreshTable()
    {
        let elementData = {
            SORT: JSON.stringify(this.sort),
        };

        Ajax.post(elementData, 'getTableOnly', this.entityTableClass).then(function(response) {
            $('#table-list').html(response);
        });
    }
}