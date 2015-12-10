<h1><?=node($node, null, 'name')?></h1>
    <div style="float:left">
      <div id="placeholder" style="width:500px;height:300px"></div>
    </div>
    
    <div id="miniature" style="float:left;margin-left:20px;margin-top:50px">
      <div id="overview" style="width:166px;height:100px"></div>

      <p id="overviewLegend" style="margin-left:10px"></p>
    </div>

    <p style="clear:left"> The selection support makes 
      pretty advanced zooming schemes possible. With a few lines of code,
      the small overview plot to the right has been connected to the large
      plot. Try selecting a rectangle on either of them.</p>

<script id="source" language="javascript" type="text/javascript">
$(function () {
    // setup plot
    function getData(x1, x2) {
        var d = [];
        for (var i = x1; i < x2; i += (x2 - x1) / 100)
            d.push([i, Math.sin(i * Math.sin(i))]);

        return [
            { label: "sin(x sin(x))", data: d }
        ];
    }

    var options = {
        legend: { show: false },
        lines: { show: true },
        points: { show: true },
        yaxis: { ticks: 10 },
        selection: { mode: "xy" }
    };

    var startData = getData(0, 3 * Math.PI);
    
    var plot = $.plot($("#placeholder"), startData, options);

    // setup overview
    var overview = $.plot($("#overview"), startData, {
        legend: { show: true, container: $("#overviewLegend") },
        lines: { show: true, lineWidth: 1 },
        shadowSize: 0,
        xaxis: { ticks: 4 },
        yaxis: { ticks: 3, min: -2, max: 2 },
        grid: { color: "#999" },
        selection: { mode: "xy" }
    });

    // now connect the two
    
    $("#placeholder").bind("plotselected", function (event, ranges) {
        // clamp the zooming to prevent eternal zoom
        if (ranges.xaxis.to - ranges.xaxis.from < 0.00001)
            ranges.xaxis.to = ranges.xaxis.from + 0.00001;
        if (ranges.yaxis.to - ranges.yaxis.from < 0.00001)
            ranges.yaxis.to = ranges.yaxis.from + 0.00001;
        
        // do the zooming
        plot = $.plot($("#placeholder"), getData(ranges.xaxis.from, ranges.xaxis.to),
                      $.extend(true, {}, options, {
                          xaxis: { min: ranges.xaxis.from, max: ranges.xaxis.to },
                          yaxis: { min: ranges.yaxis.from, max: ranges.yaxis.to }
                      }));
        
        // don't fire event on the overview to prevent eternal loop
        overview.setSelection(ranges, true);
    });
    $("#overview").bind("plotselected", function (event, ranges) {
        plot.setSelection(ranges);
    });
});
</script>

