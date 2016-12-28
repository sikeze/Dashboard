google.charts.load('current', {packages: ['corechart', 'bar']});
google.charts.setOnLoadCallback(drawBasic);

function drawBasic() {
var data = google.visualization.arrayToDataTable([
        ['Device', 'Desktop', 'Mobile', 'Tablet', 'Others', { role: 'annotation' } ],
        ['', 10, 24, 20, 15, '']
      ]);

var options = {
        isStacked: 'percent',
        height: 300,
        legend: {position: 'top', maxLines: 3},
        hAxis: {
          minValue: 0,
          ticks: [0, .3, .6, .8, 1]
        }
      };
      
      var chart = new google.visualization.BarChart(document.getElementById('deviceschart'));

      chart.draw(data, options);
      }