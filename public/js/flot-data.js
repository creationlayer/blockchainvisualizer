//Flot Pie Chart

//Need to add hover capability for pie-charts
//however, for some reason it crashes the jquery/js
//Here is this recommended way of implementation
//    grid: {
//        hoverable: true,
//        clickable: true
//    }

//distribution of hashing power mining on network
$(function() { 

    var data = daily_tx_amount_breakdown;

    var plotObj = $.plot('#flot-node-chart', data, {
        series: {
            pie: {
                show: true,
                combine: {
                    color: '#999',
                    threshold: 0 //threshold if needed for other txs
                }
            }
        },        
        grid: {
            hoverable: true
        },
        legend: {
            show: false
        },
        tooltip: true,
        tooltipOpts: {
            content: "%s, %p.0%"
        }
    });
});

//distribution of hashing power mining on network
$(function() {

    var raw = eval('(' + pool_distribution + ')');
    var data = [];
    raw.series[0].data.forEach(function(arr) {
        data.push({
            label: arr[0],
            data: arr[1],
        });
    });

    var plotObj = $.plot('#flot-pie-chart', data, {
        series: {
            pie: {
                show: true,
                combine: {
                    color: '#999',
                    threshold: 0.04 //threshold for smaller miners
                },
            }
        },
        grid: {
            hoverable: true
        },
        legend: {
            show: false
        },
        tooltip: true,
        tooltipOpts: {
            content: "%s, %p.0%"
        }
    });
});

// Average daily TPS
$(function() { 

    var barOptions = {
        series: {
            bars: {
                show: true,
                barWidth: 43200000
            }
        },
        xaxis: {
            mode: "time",
            timeformat: "%m/%d",
            minTickSize: [1, "day"]
        },
        grid: {
            hoverable: true
        },
        legend: {
            show: false
        },
        tooltip: true,
        tooltipOpts: {
            content: "Day: %x, TPS: %y"
        }
    };
    var barData = {
        label: "bar",
        data: tps_data
    };
    $.plot($("#average_daily_tps"), [barData], barOptions);

});

// Average block size
$(function() { 

    var barOptions = {
        series: {
            bars: {
                show: true,
                barWidth: 43200000
            }
        },
        xaxis: {
            mode: "time",
            timeformat: "%m/%d",
            minTickSize: [1, "day"]
        },
        grid: {
            hoverable: true
        },
        legend: {
            show: false
        },
        tooltip: true,
        tooltipOpts: {
            content: "Day: %x, Size: %y kB"
        }
    };
    var barData = {
        label: "bar",
        data: avg_block_size_data
    };
    $.plot($("#average_block_size"), [barData], barOptions);

});