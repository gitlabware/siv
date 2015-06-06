
/*
 * This script is dedicated to building and refreshing the demo chart
 * Remove if not needed
 */

// Demo chart
var chartInit = false,
        drawVisitorsChart = function ()
        {

            // Create our data table.
            var data = new google.visualization.DataTable();
            var raw_data = datos_est;

            var months = meses;

            data.addColumn('string', 'Month');
            for (var i = 0; i < raw_data.length; ++i)
            {
                data.addColumn('number', raw_data[i][0]);
            }

            data.addRows(months.length);

            for (var j = 0; j < months.length; ++j)
            {
                data.setValue(j, 0, months[j]);
            }
            for (var i = 0; i < raw_data.length; ++i)
            {
                for (var j = 1; j < raw_data[i].length; ++j)
                {
                    data.setValue(j - 1, i + 1, raw_data[i][j]);
                }
            }

            // Create and draw the visualization.
            // Learn more on configuration for the LineChart: http://code.google.com/apis/chart/interactive/docs/gallery/linechart.html
            var div = $('#demo-chart'),
                    divWidth = div.width();
            new google.visualization.LineChart(div.get(0)).draw(data, {
                title: 'Ventas mensuales de productos',
                width: divWidth,
                height: $.template.mediaQuery.is('mobile') ? 180 : 265,
                legend: 'right',
                yAxis: {title: '(thousands)'},
                backgroundColor: ($.template.ie7 || $.template.ie8) ? '#494C50' : 'transparent', // IE8 and lower do not support transparency
                legendTextStyle: {color: 'white'},
                titleTextStyle: {color: 'white'},
                hAxis: {
                    textStyle: {color: 'white'}
                },
                vAxis: {
                    textStyle: {color: 'white'},
                    baselineColor: '#666666'
                },
                chartArea: {
                    top: 35,
                    left: 30,
                    width: divWidth - 40
                },
                legend: 'bottom'
            });

            // Message only when resizing
            if (chartInit)
            {
                notify('Chart resized', 'The width change event has been triggered.', {
                    icon: 'img/demo/icon.png'
                });
            }
            // Ready
            chartInit = true;
        };
// Load the Visualization API and the piechart package.
google.load('visualization', '1', {
    'packages': ['corechart']
});
// Set a callback to run when the Google Visualization API is loaded.
google.setOnLoadCallback(drawVisitorsChart);
// Watch for block resizing
$('#demo-chart').widthchange(drawVisitorsChart);

// Respond.js hook (media query polyfill)
$(document).on('respond-ready', drawVisitorsChart);
