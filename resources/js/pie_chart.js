$data_array = null;

function drawPieCharts(data_arr)
{
  data_array = data_arr;
  if (data_arr['changed_answers_chart'] == 1)
    changedAnswersChart();
  if (data_arr['confidence_chart'] == 1)
    confidenceRatingChart();
}

function changedAnswersChart()
{
  google.charts.load('current', {'packages':['corechart']});
  google.charts.setOnLoadCallback(drawChangedAnswersChart);
  // jQuery(window).resize(function() {
  //     clearTimeout(window.resizedFinishedForPieChart);
  //     window.resizedFinishedForPieChart = setTimeout(function(){
  //       drawChangedAnswersChart();
  //     }, 250);
  // });
}
function changedAnswersHandler(){
  jQuery(".parent_ul li").css("display","inline-block");
  
}

function drawChangedAnswersChart() {

  var data = google.visualization.arrayToDataTable([
    ['Type', 'Questions'],
    ['Incorrect',     data_array['incorrect']],
    ['Correct',      data_array['correct']],
  ]);

  var options = {
    title: 'Changed Answers',
    width:300,
    height:300,
    fontName: 'Open Sans',
    legend: { position: 'none'},
    pieSliceText: 'label',
    slices: [{color: '#f0784e'}, {color: '#5cb85c'}],
    chartArea:{top:50, left:0, width:'80%',height:'75%'}
  };

  var changed_answers_chart = new google.visualization.PieChart(document.getElementById('changedAnswersChart'));

  changed_answers_chart.draw(data, options);
  google.visualization.events.addListener(changed_answers_chart, 'ready', changedAnswersHandler);
}


function confidenceRatingChart()
{
  google.charts.load('current', {'packages':['corechart']});
  google.charts.setOnLoadCallback(drawConfidenceRatingChart);
  // jQuery(window).resize(function() {
  //     clearTimeout(window.resizedFinishedForPieChart2);
  //     window.resizedFinishedForPieChart2 = setTimeout(function(){
  //       drawConfidenceRatingChart();
  //     }, 250);
  // });
}

function confidenceRatingHandler(){
  jQuery(".parent_ul li").css("display","inline-block");
  jQuery(".parent_ul").css("display","inline-block");
}

function drawConfidenceRatingChart() {

  var data = google.visualization.arrayToDataTable([
    ['Type', 'Questions'],
    ['Easy',     data_array['easy']],
    ['Hard',      data_array['hard']],
    ['Guesses',  data_array['guesses']],
  ]);


  var options = {
    title: 'Confidence Rating',
    width:300,
    height:300,
    fontName: 'Open Sans',
    legend: { position: 'none'},
    pieSliceText: 'label',
    slices: [{color: '#5cb85c'}, {color: '#d9534f'}, {color: '#f0784e'}],
    chartArea:{top:50, left:0, width:'80%',height:'75%'}
  };

  var confidence_rating_chart = new google.visualization.PieChart(document.getElementById('confidenceRatingChart'));

  confidence_rating_chart.draw(data, options);

  google.visualization.events.addListener(confidence_rating_chart, 'ready', confidenceRatingHandler);
}