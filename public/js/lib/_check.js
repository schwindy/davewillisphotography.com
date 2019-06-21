function checkEmailAddress(input)
{
    var resultArr = new Array();
    var result = false;
    var reason = "Invalid Email Address\n";

    if(input.indexOf('@')!== -1 && input.indexOf('.')!== -1)
    {
        result = true;
        reason = "success";
    }

    resultArr[0] = result;
    resultArr[1] = reason;
    return resultArr;
}

function checkPassword4(input, op)
{
    op = typeof op!=='undefined'?op:"hue";
    var resultArr = new Array();
    var result = false;
    var reason = "Invalid Password\n";

    // @CONFIG: Minimum Length of Password Set
    var minLength = 4;

    if(input.length >= minLength)
    {
        if(op==="hue")
        {
            result = true;
            reason = "success";

        }
        else if(op==="strong") // "strong" op is defined above
        {
            var upper = false;
            var lower = false;
            var number = false;

            for(var i = 0; i < input.length; i++)
            {
                var c = input.charAt(i);

                if(c >= "0" && c <= "9")
                {
                    number = true;
                }
                else if(c >= "a" && c <= "z")
                {
                    lower = true;
                }
                else if(c >= "A" && c <= "Z")
                {
                    upper = true;
                }
            }

            if(upper===true && lower===true && number===true)
            {
                result = true;
                reason = "success";

            }
            else
            {
                result = false;
                reason = "Your password must contain at least one uppercase, one lowercase and one numeric character\n";
            }
        }
        else
        {
            // Default to normal check recursively
            resultArr = checkPassword4(input);
            return resultArr;
        }
    }
    else
    {
        result = false;
        reason = "Your password is not long enough. It Must be at least "+minLength+" characters\n";

        $("#error3").addClass('display');
        $('#error-txt-3').css('display', 'block');
        $("#cd-password").addClass('error');
        $("#cd-confirm-password").addClass('error');

    }

    resultArr[0] = result;
    resultArr[1] = reason;
    return resultArr;
}

function checkPassword6(input, op)
{
    op = typeof op!=='undefined'?op:"hue";
    var resultArr = new Array();
    var result = false;
    var reason = "Invalid Password\n";

    // @CONFIG: Minimum Length of Password Set
    var minLength = 6;

    if(input.length >= minLength)
    {
        if(op==="hue")
        {
            result = true;
            reason = "success";
        }
        else if(op==="strong") // "strong" op is defined above
        {
            var upper = false;
            var lower = false;
            var number = false;

            for(var i = 0; i < input.length; i++)
            {
                var c = input.charAt(i);

                if(c >= "0" && c <= "9")
                {
                    number = true;
                }
                else if(c >= "a" && c <= "z")
                {
                    lower = true;
                }
                else if(c >= "A" && c <= "Z")
                {
                    upper = true;
                }
            }

            if(upper===true && lower===true && number===true)
            {
                result = true;
                reason = "success";
            }
            else
            {
                result = false;
                reason = "Your password must contain at least one uppercase, one lowercase and one numeric character\n";
            }
        }
        else
        {
            // Default to normal check recursively
            resultArr = checkPassword6(input);
            return resultArr;
        }
    }
    else
    {
        result = false;
        reason = "Your password is not long enough. It Must be at least "+minLength+" characters\n";
    }

    resultArr[0] = result;
    resultArr[1] = reason;
    return resultArr;
}

function checkPhoneNumber(input)
{
    input = input.trim();
    var resultArr = new Array();
    var result = false;
    var reason = "Invalid Phone Number (xxx-xxx-xxxx)\n";

    if(input.length===10 || input.length===11)
    {
        var found = false;

        for(var i = 0; i < input.length; i++)
        {
            var c = input.charAt(i);
            if(c >= "0" && c <= "9")
            {
                // do-nothing.jpg
            }
            else if(c==="-" || c===".")
            {
                // do-nothing.jpg
            }
            else
            {
                found = true;
            }
        }

        if(found===false)
        {
            result = true;
            reason = "success";
        }
        else
        {
            result = false;
            reason = "Invalid Phone Number (xxx-xxx-xxxx)\n";
        }
    }

    resultArr[0] = result;
    resultArr[1] = reason;
    return resultArr;
}

function checkZipUS(input)
{
    var resultArr = new Array();
    var result = false;
    var reason = "Invalid US Zip Code\n";

    if(input.length <= 5)
    {
        var found = false;

        for(var i = 0; i < input.length; i++)
        {
            var c = input.charAt(i);
            if(c >= "0" && c <= "9")
            {
                // do-nothing.jpg
            }
            else
            {
                found = true;
            }
        }

        if(found===false)
        {
            result = true;
            reason = "success";
        }
        else
        {
            result = false;
            reason = "ZIP Code contains non-numeric characters\n";
        }
    }
    else
    {
        reason = "ZIP Code is too long\n";
    }

    resultArr[0] = result;
    resultArr[1] = reason;
    return resultArr;
}

function checkState(input)
{
    var resultArr = new Array();
    var result = false;
    var reason = "Invalid US State\n";

    if(input!=="hue" && input!=="")
    {
        result = true;
        reason = "success";
    }

    resultArr[0] = result;
    resultArr[1] = reason;
    return resultArr;
}

function checkNotEmpty(input, name)
{
    var resultArr = new Array();
    var result = false;
    var reason = name+" is Empty\n";

    input = input.trim();

    if(input.length > 0)
    {
        result = true;
        reason = "success";
    }

    resultArr[0] = result;
    resultArr[1] = reason;
    return resultArr;
}