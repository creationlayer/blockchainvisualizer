@extends('default')

@section('content')
    <div id="page-wrapper">
        <div class="alert alert-dismissible alert-danger" style="display:none;" id="modal_error">
            <button type="button" class="close" data-dismiss="alert">×</button>
            <strong>Wrong format</strong> Please check if you entered a valid transaction id.</a>.
        </div>

        <div class="alert alert-dismissible alert-warning" style="display:none;" id="modal_ready">
            <button type="button" class="close" data-dismiss="alert">×</button>
            <strong>Accepted</strong> You will receive a sound notification when transaction receives first confirmation.</a>.
        </div>

        <div class="alert alert-dismissible alert-success" style="display:none;" id="modal_confirmed">
            <button type="button" class="close" data-dismiss="alert">×</button>
            <strong>Success</strong> Your transaction has recieved 1 confirmation.</a>.
        </div>

        <!-- ./row top stats boxes -->
        <div class="row">
            <div class="col-lg-2 col-md-2">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <i class="fa fa-clock-o fa-fw"></i>Current Block Time
                    </div>
                    <div class="panel-body">
                        <span class="textdisplay" id="current_block_time"></span>
                    </div>
                </div>
            </div>
            <div class="col-lg-2 col-md-2">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <i class="fa fa-download fa-fw"></i> Pending Size
                    </div>
                    <div class="panel-body">
                        <span class="textdisplay"><span id="data_pending_size">{{ $pending['size'] }}</span> MB</span>
                    </div>
                </div>
            </div>
            <div class="col-lg-2 col-md-2">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <i class="glyphicon glyphicon-transfer"></i> Pending Transactions
                    </div>
                    <div class="panel-body">
                        <span class="textdisplay"><span id="data_pending_count">{{ number_format($pending['count']) }}</span></span>
                    </div>
                </div>
            </div>
            <div class="col-lg-2 col-md-2">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <i class="fa fa-bitcoin fa-fw"></i> Volume 24hrs <span class="text-{{ $volume['diffPercentage'][0] == "+" ? "green" : "red" }}">({{ $volume['diffPercentage'] }} %)</span>
                    </div>
                    <div class="panel-body">
                        <span class="textdisplay-small"><span id="data_24hr_volume">{{ number_format($volume['today']) }}</span> BTC</span>
                    </div>
                </div>
            </div>
            <div class="col-lg-2 col-md-2">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <i class="fa fa-cube fa-fw"></i> Average last 25 Block Size
                    </div>
                    <div class="panel-body">
                        <span class="textdisplay"><span id="data_25block_size_avg">{{ $avg_block_size }}</span> MB</span>
                    </div>
                </div>
            </div>
            <div class="col-lg-2 col-md-2">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <i class="fa fa-bar-chart-o fa-fw"></i> Hash Rate <span class="text-{{ $hashrate['dayHashrateDiffPercent'][0] == "+" ? "green" : "red" }}">({{ $hashrate['dayHashrateDiffPercent'] }} %)
                    </div>
                    <div class="panel-body">
                        <span class="textdisplay">{{ $hashrate['hashrate'] }} ph/s</span>
                    </div>
                </div>
            </div>
            <!-- row charts -->
            <div class="row">
                <div class="col-lg-3 col-md-2">
                    <div class="panel-heading">
                        <i class="fa fa-bitcoin fa-fw"></i> Size of Transactions Daily
                    </div>
                    <div class="flot-chart">
                        <div class="flot-chart-content" id="flot-node-chart"></div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-2">
                    <div class="panel-heading">
                        <i class="fa fa-cube fa-fw"></i> Average Daily Block Size
                    </div>
                    <div class="flot-chart">
                        <div class="flot-chart-content" id="average_block_size"></div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-2">
                    <div class="panel-heading">
                        <i class="glyphicon glyphicon-transfer"></i> Average Daily Transactions Per Second
                    </div>
                    <div class="flot-chart">
                        <div class="flot-chart-content" id="average_daily_tps"></div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-2">
                    <div class="panel-heading">
                        <i class="glyphicon glyphicon-refresh"></i> Mining Distribution
                    </div>
                    <div class="flot-chart">
                        <div class="flot-chart-content" id="flot-pie-chart"></div>
                    </div>
                </div>
            </div>
            <!-- ./row block table -->
            <div class="row">
                <div class="col-lg-12" style="margin-top:10px">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <i class="fa fa-table fa-fw"></i> Previous Blocks
                        </div>
                    </div>
                    <div class="dataTable_wrapper">
                        <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                            <thead>
                            <tr>
                                <th>Hash</th>
                                <th>Block Height</th>
                                <th>Age</th>
                                <th>Size</th>
                                <th>Txs</th>
                                <th>Coin Volume</th>
                            </tr>
                            </thead>
                            <tbody id="block_list">
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>

    <audio id="newBlockAudio" src="{{ asset('sounds/notif.mp3') }}" type="audio/mpeg"></audio>
    <audio id="txAlertAudio" src="{{ asset('sounds/tx_notif.mp3') }}" type="audio/mpeg"></audio>
    <script>
        var tps_data = [
            @foreach($dailyTPS as $t)
                [{{ $t[0] }}, {{ $t[1] }}],
            @endforeach
        ];
        var avg_block_size_data = [
            @foreach($dailyAvgBlockSize as $t)
                [{{ $t[0] }}, {{ $t[1] }}],
            @endforeach
        ];
        var daily_tx_amount_breakdown = [
            @foreach($dailyAmountBreakdown as $label => $a)
                {
                    label: "{!! $label !!}",
                    data: {{ $a }}
                },
            @endforeach
        ];
        var blocks = [
            @foreach($blocks as $block)
                ["{{ $block['hash'] }}", {{ $block['height'] }}, "{{ $block['created'] }}", {{ $block['size'] }}, {{ $block['tx_amount'] }}, {{ $block['total_sent'] }}],
            @endforeach
        ];
        var pool_distribution = "{!! $poolDistribution !!}";
    </script>

@endsection

@section('scripts')
    <script src="{{ asset('js/data.js') }}"></script>
@endsection