class Schedule {
    componentClass = 'Core\\Component\\Schedule\\ScheduleComponent';
    directionId;

    initialize() {
        $('#direction-id-select').on('change', this.onClickSearchButton.bind(this));
    }

    onClickSearchButton(event)
    {
        event.preventDefault();
        this.directionId = event.target.value;

        this.refreshTable();
    }

    refreshTable()
    {
        let elementData = {
            DIRECTION_ID: this.directionId,
        };

        Ajax.post(elementData, 'refreshTable', this.componentClass).then(function(response) {
            $('#table-list').html(response);
        });
    }
}