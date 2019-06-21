function format_phone_number(num)
{
    num = str_clean(num);
    if(empty(num)) return false;

    if(num.indexOf("(")!= -1 && num.indexOf(")")!= -1)
    {
        while(num.indexOf("(")!= -1 && num.indexOf(")")!= -1)
        {
            var left = num.indexOf("(");
            var right = num.indexOf(")");

            if(left!= -1)
            {
                num = num.replace("(", "");
            }

            if(right!= -1)
            {
                num = num.replace(")", "");
            }
        }
    }

    var one = "("+num.substr(0, 3)+")";
    var two = " "+num.substr(3, 3)+"-";
    var three = num.substr(6, 4);
    return one+two+three;
}