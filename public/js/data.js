var timeSinceLastBlock = null;
var connected = false;

// Helper functions
function addCommas(nStr)
{
    nStr += '';
    var x = nStr.split('.');
    var x1 = x[0];
    var x2 = x.length > 1 ? '.' + x[1] : '';
    var rgx = /(\d+)(\d{3})/;
    while (rgx.test(x1)) {
        x1 = x1.replace(rgx, '$1' + ',' + '$2');
    }
    return x1 + x2;
}

function pad (str, max) {
    str = str.toString();
    return str.length < max ? pad("0" + str, max) : str;
}

// Logic
function refreshTimeSinceLastBlock() {
    var diff = moment().diff(timeSinceLastBlock, 'seconds');
    diff = moment.duration(diff, 'seconds');
    $("#current_block_time").html(pad(diff.hours(), 2) + ":" + pad(diff.minutes(), 2) + ":" + pad(diff.seconds(), 2));
}

function setLastBlockTime(moment) {
    timeSinceLastBlock = moment;
}

function refreshBlocksList() {
    var result = "";
    blocks.forEach(function(b, i) {
        var minutesOffset = new Date().getTimezoneOffset();
        var time = new moment(b[2]).add(-minutesOffset, 'minutes');
        if(i == 0) {
            setLastBlockTime(time);
        }

        result += "<tr>";
        result += "<td><a href=\"https://www.blocktrail.com/BTC/block/" + b[0] + "\">" + b[0] + "</a></td>";
        result += "<td>" + b[1] + "</td>";
        result += "<td>" + time.fromNow() + " (" + time.calendar() + ")</td>";
        result += "<td>" + Math.round(b[3] / 1024) + " kB</td>";
        result += "<td>" + addCommas(b[4].toString()) + "</td>";
        result += "<td>" + addCommas(Math.round(b[5] / 100000000).toString()) + " BTC</td>";
        result += "</tr>";
    });
    $('#block_list').html(result);
}

refreshBlocksList();
setInterval(refreshTimeSinceLastBlock, 1000);
setInterval(initSocket, 1000);
setInterval(refreshBlocksList, 30000);

// Modal
$("#alert_tx_id").keyup(function() {
    if($("#alert_tx_id").val() != "") {
        if($("#alert_tx_id").val().match(/^[a-fA-F0-9]{64}$/) != null) {
            $("#modal_ready").show();
            $("#modal_error").hide();
        }
        else {
            $("#modal_ready").hide();
            $("#modal_error").show();
        }
    }
    else {
        $("#modal_ready").hide();
        $("#modal_error").hide();
    }
});

// Socket Handling
var socket = null;
function initSocket() {
    if(connected == false) {
        connected = true;
        socket = new WebSocket("ws://188.166.27.94:4024/");

        socket.onmessage = function(msg) {
            var data = JSON.parse(msg.data);
            if(data.pending !== undefined) {
                $('#data_pending_count').html(addCommas(data.pending.count.toString()));
                $('#data_pending_size').html(data.pending.size);
            }
            else if(data.block !== undefined) {
                var tracking = $("#alert_tx_id").val();
                if(tracking != "") {
                    $.get("http://188.166.27.94/transaction-confirmed/"+tracking, function(data) {
                        if(data == "true") {
                            $("#modal_ready").hide();
                            $("#modal_confirmed").show();
                            $("#txAlertAudio")[0].play();
                            $("#alert_tx_id").val("");
                        }
                    });
                }
                if($("#alert_on_new_block").is(':checked')) {
                    $("#newBlockAudio")[0].play();
                }
                $('#data_24hr_volume').html(addCommas(data.volume.today.toString()));
                $('#data_25block_size_avg').html(data.avg_block_size);
                blocks.pop();
                blocks.unshift([data.block.hash, data.block.height, data.block.created, data.block.size, data.block.tx_amount, data.block.total_sent]);
                refreshBlocksList();
            }
            else {
                console.log(data);
            }
        };

        socket.onclose = function(msg) {
            connected = false;
            console.log(msg);
        };

        socket.onerror = function(msg) {
            connected = false;
            console.log(msg);
        };
    }
}
