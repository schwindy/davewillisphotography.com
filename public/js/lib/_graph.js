function create_graph(args)
{
    if(empty(args))
    {
        return false;
    }

    args.run = empty(args.run)?'create_job':args.run;
    args.request = empty(args.request)?'get_json':args.request;

    var url = PATH_WORLD+
        '?run='+args.run+
        '&method=Graph::'+args.request+
        '&args='+JSON.stringify(args.args)+
        '&handle=true';

    ajax(url, {}, function(response){
        if(empty(response.data) || empty(response.data[0]) || empty(response.data[0][1])) response.data = [[0, 0]];
        var precision = response.data[0][1].toString().substr(0, 2)==="0."?6:2;
        Highcharts.stockChart
        (
            args.selector.substr(1),
            {
                rangeSelector:
                    {
                        selected:1
                    },
                title:
                    {
                        text:args.title
                    },
                series:
                    [
                        {
                            name:args.display_name,
                            data:response.data,
                            tooltip:
                                {
                                    valueDecimals:precision
                                }
                        }
                    ]
            }
        );

        $('.highcharts-container').each(function(){
            $(this).children('svg').children('text').each(function(){
                if($(this).html().indexOf('charts.com')!== -1) $(this).remove();
            })
        })
    })
}