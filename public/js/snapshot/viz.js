google.load('visualization', '1', {
    packages: ['linechart']
});
//google.load('visualization', '1', {packages: ['columnchart']});
//google.load('visualization', '1', {packages: ['annotatedtimeline']});
$(document).ready(function () {
    var repOrg = $('#reporting-org-select>select').val();
    repOrg = encodeURIComponent(repOrg);
    $.ajax({
        type: "get",
        url: "ajax.php",
        data: "reporting_org=" + repOrg + "&ele=activity_status",
        success: function (data) {
            obj = JSON && JSON.parse(data) || $.parseJSON(data);
            if (obj == null) return;
            var status = new Array();
            /*
            var nameArray = ['Status'];
            var countArray = ['Status'];
            $.each(obj, function (name , values) {
                nameArray.push(name);
                countArray.push(values.count);
            });
            status.push(nameArray);
            status.push(countArray)
            */
            status.push(['', 'Completion', 'Post-completion', 'Implementation', 'Pipeline/identification', 'Cancelled']);
            var completion = (obj.hasOwnProperty('completion') ? obj.completion.count : 0);
            var postCompletion = (obj.hasOwnProperty('post_completion') ? obj.post_completion.count : 0);
            var implementation = (obj.hasOwnProperty('implementation') ? obj.implementation.count : 0);
            var pipeline = (obj.hasOwnProperty('pipeline_identification') ? obj.pipeline_identification.count : 0);
            var cancelled = (obj.hasOwnProperty('cancelled') ? obj.cancelled.count : 0);
            status.push(['Status', completion, postCompletion, implementation, pipeline, cancelled]);
            // Succesful, load visualization API and send data      
            google.setOnLoadCallback(drawActivityStatus(status));
        }
    });

    $.ajax({
        type: "get",
        url: "ajax.php",
        data: "reporting_org=" + repOrg + "&ele=activity_dates",
        success: function (data) {
            // Succesful, load visualization API and send data      
            google.setOnLoadCallback(drawActivityDates(data));
        }
    });

    function drawActivityStatus(status) {
        // Create and populate the data table.
        var data = google.visualization.arrayToDataTable(status);

        // Create and draw the visualization.
        new google.visualization.ColumnChart(document.getElementById('activity-status')).draw(data, {
            legend: {
                position: 'right'
            },
            bar: {
                groupWidth: '100%'
            },
            tooltip: {
                trigger: 'focus'
            }
        });
    }

    function drawActivityDates(datesData) {
        obj = JSON && JSON.parse(datesData) || $.parseJSON(datesData);
        if (obj == null) return;

        var dataset = new Array();
        dataset.push(['Year', 'Start Actual', 'End Actual']);
        $.each(obj, function (datename, dateValues) {
            var startPlanned = (dateValues.hasOwnProperty('start_planned') ? dateValues.start_planned.count : 0);
            var startActual = (dateValues.hasOwnProperty('start_actual') ? dateValues.start_actual.count : 0);
            var endActual = (dateValues.hasOwnProperty('end_actual') ? dateValues.end_actual.count : 0);
            var endPlanned = (dateValues.hasOwnProperty('end_planned') ? dateValues.end_planned.count : 0);
            var row = [datename, startActual, endActual];
            dataset.push(row);
        });
        var data = new google.visualization.arrayToDataTable(dataset);
        // */
        /*
        var data = new google.visualization.DataTable();
        data.addColumn('date', 'Year');
        //data.addColumn('number', 'Start Plan');
        data.addColumn('number', 'Start Act');
        //data.addColumn('number' ,'End Plan');
        data.addColumn('number' , 'End Act');
        var rows = new Array();
        console.log(obj);
        $.each(obj, function (datename , dateValues) {
            //var startPlanned = (dateValues.hasOwnProperty('start_planned')? dateValues.start_planned.count: 0);
            var startActual = (dateValues.hasOwnProperty('start_actual')? dateValues.start_actual.count: 0);
            //var endPlanned = (dateValues.hasOwnProperty('end_planned')? dateValues.end_planned.count: 0);
            var endActual = (dateValues.hasOwnProperty('end_actual')? dateValues.end_actual.count: 0);
            var row = [new Date(datename) , startActual  , endActual];
            rows.push(row);
        });
        
        data.addRows(rows);
        // */
        var options = {
            title: 'Activity Dates'
        };

        new google.visualization.LineChart(document.getElementById('activity-dates')).
        draw(data, {
            curveType: "function",
            vAxis: {
                maxValue: 10
            },
            tooltip: {
                trigger: 'focus'
            }
        });
        /*
        var annotatedtimeline = new google.visualization.AnnotatedTimeLine(document.getElementById('activity-dates'));
        var options = 
        annotatedtimeline.draw(data, {
            'displayAnnotations': false ,
            'thickness' : 2 ,
            'displayZoomButtons' : false
            }
        );
        */
    }
});