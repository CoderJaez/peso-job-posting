$(document).ready(function() {

	 // Get context with jQuery - using jQuery's .get() method.
	 var monthlyCounselChartCanvas = $('#monthy_counsel').get(0).getContext('2d');
	 var sectionCounselChartCanvas = $('#section_counsel').get(0).getContext('2d');
  // This will get the first returned node in the jQuery collection.
  var monthlyCounselChart       = new Chart(monthlyCounselChartCanvas);
  var sectionCounselChart       = new Chart(sectionCounselChartCanvas);


  $.ajax({
  	url: base_url + 'Guidance_counselor/getMonthlyCounsel',
  	method: 'POST',
  	success: function(data) {
  		var salesChartData = {
  			labels  : data.labels,
  			datasets:[data.datasets]
  		};
		// Create the line chart
		monthlyCounselChart.Bar(salesChartData, data.salesChartOptions);
	}
})

  $.ajax({
  	url: base_url + 'Guidance_counselor/getSectionCounsel',
  	method: 'POST',
  	success: function(data) {
  		var salesChartData = {
  			labels  : data.labels,
  			datasets:[data.datasets]
  		};
		// Create the line chart
		sectionCounselChart.Line(salesChartData, data.salesChartOptions);
	}
})


  var pieChartCanvas = $('#list_of_students').get(0).getContext('2d');
  var pieChart       = new Chart(pieChartCanvas);

  $.ajax({
  	url: base_url + 'Guidance_counselor/getTotalPerSection',
  	method: 'POST',
  	success: function(data) {
  		var pieOptions = {
    	 // Boolean - Whether we should show a stroke on each segment
    	 segmentShowStroke    : true,
     	// String - The colour of each segment stroke
    	 segmentStrokeColor   : '#fff',
    	 // Number - The width of each segment stroke
     	segmentStrokeWidth   : 1,
    	 // Number - The percentage of the chart that we cut out of the middle
    	 percentageInnerCutout: 50, // This is 0 for Pie charts
     	// Number - Amount of animation steps
     	animationSteps       : 100,
   	 	 // String - Animation easing effect
  	   	animationEasing      : 'easeOutBounce',
   	  	// Boolean - Whether we animate the rotation of the Doughnut
  	   	animateRotate        : true,
  	   	// Boolean - Whether we animate scaling the Doughnut from the centre
  	   	animateScale         : false,
    	 // Boolean - whether to make the chart responsive to window resizing
   	 	 responsive           : true,
     	// Boolean - whether to maintain the starting aspect ratio or not when responsive, if set to false, will take up entire container
   	  maintainAspectRatio  : false,
     // String - A legend template
   	  legendTemplate       : '<ul class=\'<%=name.toLowerCase()%>-legend\'><% for (var i=0; i<segments.length; i++){%><li><span style=\'background-color:<%=segments[i].fillColor%>\'></span><%if(segments[i].label){%><%=segments[i].label%><%}%></li><%}%></ul>',
     // String - A tooltip template
   	  tooltipTemplate      : '<%=label %> - <%=value%> student/s'
 	};
   // Create pie or douhnut chart
   // You can switch between pie and douhnut using the method below.

   pieChart.Doughnut(data.PieData, pieOptions);
   $.each(data.PieData,function(index, row) {
   $('.chart-legend').append('<li><i class="fa fa-circle-o " style ="color:'+row.color+'"></i> '+row.label+'</li>')
   })
}
})


   // -----------------
   // - END PIE CHART -
   // -----------------

})
// End of document ready function