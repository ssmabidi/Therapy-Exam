data_arr_ = null;
color = null;
target_color = "green"
function drawTimesBySectionChart(data_arr)
{
  data_arr_ = data_arr;

  google.charts.load('current', {packages: ['corechart', 'line']});
  google.charts.setOnLoadCallback(drawTrendlines);
  jQuery(window).resize(function() {
      clearTimeout(window.resizedFinished4);
      window.resizedFinished4 = setTimeout(function(){
        drawTrendlines();
      }, 250);
  });
}

function myReadyHandler3(){
  jQuery("#line_chart_div > div > div:nth-child(1) > div > svg > g:nth-child(3) > text").css("font-size",parseInt(jQuery("#line_chart_div > div > div:nth-child(1) > div > svg > g:nth-child(3) > text").css("font-size"))+4);
}

function drawTrendlines() {
      if (data_arr_['color'] == 'green')
        target_color = 'blue';
      var data = new google.visualization.DataTable();
      data.addColumn('number', 'Section');
      data.addColumn('number', 'Your Time');
      data.addColumn('number', 'Target');
      data.addRows([
        [1, data_arr_[0], data_arr_['target']], [2, data_arr_[1], data_arr_['target']], [3, data_arr_[2], data_arr_['target']], [4, data_arr_[3], data_arr_['target']], [5, data_arr_[4], data_arr_['target']]]);
     
      var options = {
        title: 'Average Time Spent per Question',
        fontName: 'Open Sans',
      
        hAxis: {
          title: 'Section',

          titleTextStyle: {
            color: '#2B3E51'
          }
        },
        vAxis: {
          title: 'Seconds',
          titleTextStyle: {
            color: '#2B3E51'
          }
        },
        colors: [data_arr_['color'], target_color],
        legend: {
          position: "top",
          titleTextStyle: {
            color: '#2B3E51'
          }
        },  
        annotation: {
          titleTextStyle: {
            color: 'white !important'
          }
        },
        chartArea: {top:50, left:80, height: '50%', width: '80%'},        
      };

      var line_chart = new google.visualization.LineChart(document.getElementById('line_chart_div'));
      line_chart.draw(data, options);
      google.visualization.events.addListener(line_chart, 'ready', myReadyHandler3);
    }