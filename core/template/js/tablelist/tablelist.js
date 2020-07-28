class TableList {
    static instanceList = {};
    formInfoList = {
        'addForm': {
            formId: 'add-form',
            popupId: 'add-popup',
            closePopupButtonId: 'add-popup-close-btn',
            submitFormButtonId: 'submit-add-btn',
            formAction: 'addElement',
            isSetClickHandler: false
        },
        'updateForm': {
            formId: 'update-form',
            popupId: 'update-popup',
            closePopupButtonId: 'update-popup-close-btn',
            submitFormButtonId: 'submit-update-btn',
            formAction: 'updateElement',
            isSetClickHandler: false
        },
        'deleteForm': {
            formId: 'delete-form',
            popupId: 'delete-popup',
            closePopupButtonId: 'delete-popup-close-btn',
            submitFormButtonId: 'submit-delete-btn',
            formAction: 'deleteElement',
            isSetClickHandler: false
        }
    };
    multipleSelectIds = [];
    entityTableClass = null;
    componentClass = 'core\\component\\tableList\\TableListComponent';
    sort = {};
    search = '';

    static getInstance(entityTableClass = null, sortFieldName = null, sortType = null) {
        if (this.instanceList[entityTableClass] === undefined) {
            this.instanceList[entityTableClass] = new TableList(entityTableClass, sortFieldName, sortType);
        }

        return this.instanceList[entityTableClass];
    }

    constructor(entityTableClass, sortFieldName, sortType) {
        this.entityTableClass = entityTableClass;
        this.sort[sortFieldName] = sortType;
        this.initialize();
    }

    initialize() {
        $(document).on('click', '.update-btn', this.onClickUpdateButton.bind(this));
        $(document).on('click', '.add-btn', this.onClickAddButton.bind(this));
        $(document).on('click', '.delete-btn', this.onClickDeleteButton.bind(this));
        $(document).on('click', '.td-header', this.onClickHeaderElement.bind(this));
        $('#submit-search-btn').on('click', this.onClickSearchButton.bind(this));
        $('#search-input').keyup(function(event) {
            // Number 13 is the "Enter" key on the keyboard
            if (event.keyCode === 13) {
                // Cancel the default action, if needed
                event.preventDefault();
                // Trigger the button element with a click
                $('#submit-search-btn').click();
            }
        });
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
        };

        Ajax.post(elementData, 'getElementInfo', this.entityTableClass).then(
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

    onClickSearchButton(event)
    {
        event.preventDefault();
        let input = $('#search-input');
        this.search = input.val();

        this.refreshTable();
    }

    openUpdatePopup(response)
    {
        let updateFormInfo = this.formInfoList['updateForm'];
        let form = $('#' + updateFormInfo.formId);
        if (null == form) {
            return;
        }

        this.multipleSelectIds = [];
        for (let key in response) {
            let input = $('#update-' + key);
            if (!input.get(0)) {
                continue;
            }

            if (input.get(0).multiple) {
                this.multipleSelectIds[input.get(0).name] = true;
            } else {
                this.multipleSelectIds[input.get(0).name] = false;
            }

            input.val(response[key]);
        }

        if (!this.formInfoList['updateForm'].isSetClickHandler) {
            $('#' + updateFormInfo.submitFormButtonId).click(function (event) {
                event.preventDefault();
                this.submitForm(updateFormInfo);
            }.bind(this));
            this.formInfoList['updateForm'].isSetClickHandler = true;
        }

        $('#' + updateFormInfo.popupId).modal('show');
    }

    openAddPopup()
    {
        let addFormInfo = this.formInfoList['addForm'];
        let form = $('#' + addFormInfo.formId);
        if (null == form) {
            return;
        }

        this.multipleSelectIds = [];
        let selectList = form.find('select').get();
        selectList.forEach(function (item) {
            if (item.multiple) {
                this.multipleSelectIds[item.name] = true;
            } else {
                this.multipleSelectIds[item.name] = false;
            }
        }.bind(this));

        if (!this.formInfoList['addForm'].isSetClickHandler) {
            $('#' + addFormInfo.submitFormButtonId).click(function (event) {
                event.preventDefault();
                this.submitForm(addFormInfo);
            }.bind(this));
            this.formInfoList['addForm'].isSetClickHandler = true;
        }
        $('#' + addFormInfo.popupId).modal('show');
    }

    openDeletePopup(elementId)
    {
        let deleteFormInfo = this.formInfoList['deleteForm'];

        let input = $('#delete-ID');
        input.val(elementId);

        if (!this.formInfoList['deleteForm'].isSetClickHandler) {
            $('#' + deleteFormInfo.submitFormButtonId).click(function (event) {
                event.preventDefault();
                this.submitForm(deleteFormInfo);
            }.bind(this));
            this.formInfoList['deleteForm'].isSetClickHandler = true;
        }
        $('#' + deleteFormInfo.popupId).modal('show');
    }

    submitForm(formInfo)
    {
        let form = $('#' + formInfo.formId);
        let elementData = {};

        let data = form.serializeArray().reduce(
            function(obj, item) {
                if (this.multipleSelectIds[item.name]) {
                    if (obj[item.name] === undefined)  {
                        obj[item.name] = [];
                    }
                    obj[item.name].push(item.value);
                } else {
                    obj[item.name] = item.value;
                }
                return obj;
            }.bind(this), {}
        );

        for(let key in this.multipleSelectIds) {
            if (data[key] === undefined && this.multipleSelectIds[key]) {
                data[key] = [];
            }
        }

        if (data['ID']) {
            elementData['ID'] = data['ID'];
            delete data['ID'];
        }
        elementData['FIELDS'] = JSON.stringify(data);

        Ajax.post(elementData, formInfo.formAction, this.entityTableClass).then(
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
            SEARCH: this.search,
            SORT: JSON.stringify(this.sort),
        };

        Ajax.post(elementData, 'getTableOnly', this.entityTableClass).then(function(response) {
            $('#table-list').html(response);
        });
    }
}