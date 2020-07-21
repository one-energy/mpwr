<x-app.auth :title="__('Profile')">
    <x-profile.show-profile-information/>
</x-app.auth>

<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript">
  google.charts.load("visualization", "1", {packages:["bar"]});
  google.charts.setOnLoadCallback(drawFunnelChart);

  function drawFunnelChart() {
      var data = google.visualization.arrayToDataTable([
        ["Type",   "#",  { role: "style" }],
        ["Doors",  2000, "color: #46A049"],
        ["Hours",  250,  "color: #7de8a6"],
        ["Sets",   125,  "color: #006400"],
        ["Sits",   85,   "color: #B5B5B5"],
        ["Closes", 22,   "color: #FF6E5D"]
      ]);

      var view = new google.visualization.DataView(data);
      view.setColumns([0, 1,
                       { calc: "stringify",
                         sourceColumn: 0,
                         type: "string",
                         role: "annotation" },
                       2]);

      var options = {
        bar: {groupWidth: "95%"},
        legend: { position: 'top' },
        vAxis: { gridlines: { count: 0 }, textPosition: 'none' },
        hAxis: { gridlines: { count: 0 }, textPosition: 'none', baselineColor: '#FFFFFF' },
        chartArea:{left:0, top:0, width:"100%", height:"100%"},
        isStacked: true
      };
      var chart = new google.visualization.BarChart(document.getElementById("barchart_values"));
      chart.draw(view, options);
  }

  $(window).resize(function(){
    drawFunnelChart();
});
</script>