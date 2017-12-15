content_sections_data_array_ = null;
systems_data_array = null;


function drawMissedContentSectionsChart(data_array)
{
  content_sections_data_array_ = data_array;
  // alert(JSON.stringify(data_array));
  google.charts.load('current', {packages: ['corechart', 'bar']});
  google.charts.setOnLoadCallback(drawMissedContentSectionsStacked);
  jQuery(window).resize(function() {
      clearTimeout(window.resizedFinished2);
      window.resizedFinished2 = setTimeout(function(){
        drawMissedContentSectionsStacked();
      }, 250);
  });
}


function myReadyHandler2(){
  jQuery("#contentsections_chart_div > div > div:nth-child(1) > div > svg > g:nth-child(3) > text").css("font-size",parseInt(jQuery("#contentsections_chart_div > div > div:nth-child(1) > div > svg > g:nth-child(3) > text").css("font-size"))+4);
  jQuery("#contentsections_chart_div > div > div:nth-child(1) > div > svg > g:nth-child(4) > g:nth-child(5) > g > g > text").css("font-size",parseInt(jQuery("#contentsections_chart_div > div > div:nth-child(1) > div > svg > g:nth-child(4) > g:nth-child(5) > g > g > text").css("font-size"))+2);
}

function drawMissedContentSectionsStacked() {
      var data = google.visualization.arrayToDataTable(content_sections_data_array_);
      
      var options = {
        
        title: "Top 3 Content Sections Missed",
        fontName: 'Open Sans',
        isStacked: true,
        hAxis: {
          title: 'Number of Questions',
          viewWindowMode: 'maximized',
          titleTextStyle: {
            color: '#2B3E51'
          }
        },
        legend: {
          position: "none",
          titleTextStyle: {
            color: '#2B3E51'
          }
        },
        chartArea: {top:50, left:110, height: '100%', width: '90%'},
      };
      var content_sections_chart = new google.visualization.BarChart(document.getElementById("contentsections_chart_div"));
      content_sections_chart.draw(data, options);
      google.visualization.events.addListener(content_sections_chart, 'ready', myReadyHandler2);
    }

function myReadyHandler(){
  jQuery("#systems_chart_div > div > div:nth-child(1) > div > svg > g:nth-child(3) > text").css("font-size",parseInt(jQuery("#systems_chart_div > div > div:nth-child(1) > div > svg > g:nth-child(3) > text").css("font-size"))+4);
  jQuery("#systems_chart_div > div > div:nth-child(1) > div > svg > g:nth-child(4) > g:nth-child(5) > g > g > text").css("font-size",parseInt(jQuery("#systems_chart_div > div > div:nth-child(1) > div > svg > g:nth-child(4) > g:nth-child(5) > g > g > text").css("font-size"))+2);
}
function drawMissedSystemsChart(data_array)
{
  systems_data_array = data_array;
  // alert(JSON.stringify(data_array));
  google.charts.load('current', {packages: ['corechart', 'bar']});
  
  google.charts.setOnLoadCallback(function(){
    drawMissedSystemsStacked();
    
  });
  jQuery(window).resize(function() {
      clearTimeout(window.resizedFinished3);
      window.resizedFinished3 = setTimeout(function(){
        drawMissedSystemsStacked();
      }, 250);
  });
}




function drawMissedSystemsStacked() {
      var data = google.visualization.arrayToDataTable(systems_data_array);
      
      var options = {
        
        title: "Top 3 Systems Missed",
        chartArea: {width: '60%'},
        isStacked: true,
        fontName: 'Open Sans',
        hAxis: {
          title: 'Number of Questions',
          viewWindowMode: 'maximized',
          titleTextStyle: {
            color: '#2B3E51'
          }
        },
        legend: {
          position: "none",
          titleTextStyle: {
            color: '#2B3E51'
          }
        },
        chartArea: {top:50, left:130, height: '100%', width: '90%'},
      };
      var systems_chart = new google.visualization.BarChart(document.getElementById("systems_chart_div"));
      systems_chart.draw(data, options);
      google.visualization.events.addListener(systems_chart, 'ready', myReadyHandler);
      
    }