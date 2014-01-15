$(document).ready(function(){
    $('body').click(function(evt){
        var ele = $(evt.target);
        if(!ele.is('input') && ele.attr('id') != 'login-register-popup' && ele.closest('div').attr('id') != 'login-register-popup'){
            $('#login-form-wrapper').css('display' , 'none');
        }
    })
    
    $(".dialog-box-open").click(function(evt){
        evt.preventDefault();
        var ele = $(evt.target);
        var id = '#help-' + ele.attr('id');
        $(id).dialog(
            {
                width : 500 ,
                modal : true,
                show : { duration : 500 }
            }
        );
    });
    
    // Draw graphs
    var repOrg = getUrlVar('reporting_org');
    $.ajax({
        type:"get",
        url: location.protocol + "//" + location.host + "/default/index/ajax",
        data: "reporting_org=" + repOrg + "&ele=activity_status",
        success: function(data){
            // Succesful, load visualization API and send data
            var dataset = prepareStatusData(data)
            drawBarChart('activity-status' , dataset);
        }
    });
    
    $.ajax({
        type:"get",
        url: location.protocol + "//" + location.host + "/default/index/ajax",
        data: "reporting_org=" + repOrg + "&ele=activity_dates",
        success: function(data){
            // Succesful, load visualization API and send data
            var dataset = prepareDatesData(data);
            drawLineChart('activity-dates' , dataset);
        }
    });
    
    
    $.ajax({
        type:"get",
        url: location.protocol + "//" + location.host + "/default/index/ajax",
        data: "reporting_org=" + repOrg + "&ele=recipient",
        success: function(data){
            // Succesful, load visualization API and send data
            var dataset = prepareRecipientData(data);
            drawHorizontalBarChart('recipient-info' , dataset);
        }
    });
    
});

function getUrlVar(key) {
    var result = new RegExp(key + "=([^&]*)", "i").exec(window.location.search); 
    return result && unescape(result[1]) || ""; 
}

function drawBarChart(eleId , dataset){
    nv.addGraph(function() {  
        var chart = nv.models.discreteBarChart()
            .x(function(d) { return d.label })
            .y(function(d) { return d.value })
            .staggerLabels(true)
            //.staggerLabels(historicalBarChart[0].values.length > 8)
            .tooltips(false)
            .showValues(true)
            .valueFormat(d3.format('d'))
            
        chart.yAxis
            .axisLabel('Number of Activities')
            .tickFormat(d3.format('d'));
        chart.xAxis.axisLabel('Activity Status');
      
        d3.select('#' + eleId).append('svg')
            .datum(dataset)
            .transition().duration(500)
            .call(chart);
        // adjust yaxis and xaxis label    
        d3.select('#' + eleId).select('.nv-y').selectAll('.nv-axislabel').attr('y' , '-30').style('font-size' , 15);
        d3.select('#' + eleId).select('.nv-x').selectAll('.nv-axislabel').attr('y' , '25').style('font-size' , 15);
      
        nv.utils.windowResize(chart.update);
        return chart;
      });
}

function prepareStatusData(data)
{
    obj = JSON && JSON.parse(data) || $.parseJSON(data);
    if(obj == null) return;
    var status = new Array();
    
    var completion = (obj.hasOwnProperty('completion')? obj.completion.count: 0);
    var postCompletion = (obj.hasOwnProperty('post_completion')? obj.post_completion.count: 0);
    var implementation = (obj.hasOwnProperty('implementation')? obj.implementation.count: 0);
    var pipeline = (obj.hasOwnProperty('pipeline_identification')? obj.pipeline_identification.count: 0);
    var cancelled = (obj.hasOwnProperty('cancelled')? obj.cancelled.count: 0);
    dataset = {
        'completion'  : completion ,
        'post_completion' : postCompletion,
        'implementation' : implementation,
        'pipeline' : pipeline,
        'cancelled' : cancelled
    };
    
    var outData = [ {
            key: 'Activity Dates',
            values : [
                {label :"Pipeline/identification" , value : dataset.pipeline },
                {label :"Implementation" , value : dataset.implementation},
                {label :"Completion" , value : dataset.completion},
                {label :"Post-completion" , value : dataset.post_completion},
                {label :"Cancelled" , value : dataset.cancelled}
                ]
    }];
    return outData;
}

function drawLineChart(eleId , dataset)
{
    nv.addGraph(function() {
        var chart = nv.models.lineWithFocusChart();
      
        chart.xAxis
            .axisLabel('Year')
            .tickFormat(d3.format('d'));
        chart.x2Axis
            .tickFormat(d3.format('d'));
        chart.yAxis
            .axisLabel('Number of activities')
            .tickFormat(d3.format('d'));
        chart.y2Axis
            .tickFormat(d3.format('d'));
            
        var svg = d3.select('#'+eleId).append('svg')
        
        svg.datum(dataset)
            .transition().duration(500)
            .call(chart);
                    
        chart.tooltipContent(function(key, x, y, e, graph) {
            return '<h3>' + key + '</h3>' +
           '<p>' +  y + ' activities on ' + x + '</p>';
        })
        nv.utils.windowResize(chart.update);
        // adjust yaxis label
        d3.select('.nv-lineWithFocusChart')
            .select('.nv-y')
            .select('.nv-axislabel')
            .attr('x' , '-100')
            .attr('y' , '-30')
            .style('font-size' , 15);
      
        return chart;
      });
}


function prepareDatesData(datesData) {
    obj = JSON && JSON.parse(datesData) || $.parseJSON(datesData);
    if(obj == null) return false;
    
    var startActual = new Array();
    var endActual = new Array();
    
    $.each(obj , function (datename , dateValues){
        var startActualValue = (dateValues.hasOwnProperty('start_actual')? dateValues.start_actual.count: 0);
        var endActualValue = (dateValues.hasOwnProperty('end_actual')? dateValues.end_actual.count: 0);
        
        startActual.push({x : datename , y : startActualValue});
        endActual.push({x : datename , y : endActualValue});
    });

    return [
        {
          values: startActual,
          key: "Start Actual",
          color: "#9ca04c"
        },
        {
          values: endActual,
          key: "End Actual",
          color: "#2ca02c"
        }
    ];
}

function prepareRecipientData(recipientData)
{
    var values = new Array();
    obj = JSON && JSON.parse(recipientData) || $.parseJSON(recipientData);
    if(obj == null) return false;
    
    if(obj.hasOwnProperty('region')){
        var region = obj.region;
        $.each(region , function (regionName , regionData){
            values.push({label : regionName , value : regionData.count , name : regionData.name});
        });
        values.sort(function(a, b) {
            return d3.descending(a.value, b.value);
        });
    }
    
    if(obj.hasOwnProperty('country')){
        var country = new Array();
        var countries = obj.country;
        $.each(countries , function (countryName , countryData){
            country.push({label : countryName , value : countryData.count , name : countryData.name});
        });
        country.sort(function(a , b) {
            return d3.descending(a.value , b.value);
        });
        $.merge(values , country);
    }
    return values;
}

function drawHorizontalBarChart(eleId , sortedData)
{
    var valueLabelWidth = 40; // space reserved for value labels (right)
    var barHeight = 30; // height of one bar
    var barLabelWidth = 50; // space reserved for bar labels
    var barLabelPadding = 5; // padding between bar and bar labels (left)
    var gridLabelHeight = 18; // space reserved for gridline labels
    var gridChartOffset = 3; // space between start of grid and first bar
    var maxBarWidth = 350; // width of the bar with the max value
    var offsetHeight = offsetWidth = 70;
     
    // accessor functions 
    var barLabel = function(d) { return d['label']; };
    var barValue = function(d) { return parseFloat(d['value']); };
    var barName = function(d) { return d['name']; }
    var color = d3.scale.category20();
    //var color = d3.scale.linear().domain([0,100]).range(["red","green"]);
     
    // sorting
    /*
    var sortedData = dataset.sort(function(a, b) {
        return d3.descending(barValue(a), barValue(b));
    });
    */
    // scales
    var yScale = d3.scale.ordinal().domain(d3.range(0, sortedData.length)).rangeBands([0, sortedData.length * barHeight]);
    var y = function(d, i) { return yScale(i); };
    var yText = function(d, i) { return y(d, i) + yScale.rangeBand() / 2; };
    var x = d3.scale.linear().domain([0, d3.max(sortedData, barValue)]).rangeRound([0, maxBarWidth]);
    // svg container element
    var chart = d3.select('#recipient-info').append("svg")
      .attr('width', maxBarWidth + barLabelWidth + valueLabelWidth + offsetWidth)
      .attr('height', gridLabelHeight + gridChartOffset + sortedData.length * barHeight + offsetHeight);
    // grid line labels
    var gridContainer = chart.append('g')
      .attr('transform', 'translate(' + barLabelWidth + ',' + gridLabelHeight + ')');
      
    // vertical grid lines
    gridContainer.selectAll("line").data(x.ticks(10)).enter().append("line")
      .attr("x1", x)
      .attr("x2", x)
      .attr("y1", 0)
      .attr("y2", yScale.rangeExtent()[1] + gridChartOffset)
      .style("stroke", "#ccc");
    // bar labels
    var labelsContainer = chart.append('g')
      .attr('transform', 'translate(' + (barLabelWidth - barLabelPadding) + ',' + (gridLabelHeight + gridChartOffset) + ')'); 
    labelsContainer.selectAll('text').data(sortedData).enter().append('text')
      .attr('y', yText)
      .attr('stroke', 'none')
      .attr('fill', 'black')
      .attr("dy", ".35em") // vertical-align: middle
      .attr('text-anchor', 'end')
      .text(barLabel);
    
    //define tooltip
    var div = d3.select("body").append("div")   
    .attr("class", "tooltip")               
    .style("opacity", 0)
      
    // bars
    var barsContainer = chart.append('g')
      .attr('transform', 'translate(' + barLabelWidth + ',' + (gridLabelHeight + gridChartOffset) + ')'); 
    var rect = barsContainer.selectAll("rect").data(sortedData).enter().append("rect")
        .on("mouseover", function(d , i) {
            div.transition()        
                .duration(200)      
                .style("opacity", 1);      
            div .html(barName(d))  
                .style("left", (d3.event.pageX) + "px")     
                .style("top", (d3.event.pageY) + "px");  
            }
        )
        .on("mouseout" , function(d , i){
                d3.selectAll('.tooltip').transition().duration(200).style("opacity" , 0);
            }
        )
        .attr('y', y)
        .attr('height', yScale.rangeBand())
        .attr('stroke', 'white')
        .transition()
        .duration(1000)
        .attr('width', function(d) { return x(barValue(d)); })
        .attr('fill', function(d , i) { return color(i); });
    
    
    // bar value labels
    barsContainer.selectAll("text").data(sortedData).enter().append("text")
      .attr("x", function(d) { return x(barValue(d)); })
      .attr("y", yText)
      .attr("dx", 3) // padding-left
      .attr("dy", ".35em") // vertical-align: middle
      .attr("text-anchor", "start") // text-align: right
      .attr("fill", "black")
      .attr("stroke", "none")
      .attr("fill-opacity" , 0.7)
      .text(function(d) { return d3.round(barValue(d), 2); });
      
    chart.append('g').append('text')
        .attr('x' , maxBarWidth/2)
        .attr('y' , '10')
        .text('Number of Activities')
        .style('font-size' , 15);
        
    // start line
    barsContainer.append("line")
      .attr("y1", -gridChartOffset)
      .attr("y2", yScale.rangeExtent()[1] + gridChartOffset)
      .style("stroke", "#000");
}