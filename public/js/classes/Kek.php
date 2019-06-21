<script>
    /*
    Kek represents the kekPHP Application currently running. It can be accessed via the Javascript Global Namespace.

    Kek is used to read/manage:
    * Application Configuration
    * CSS Theme Configuration
    * Logging
    * PHP Global Constants
    * Session Data
    * User Data

    Try: console.log(kek)

    Example Kek Object (@see: Browser Console):
    {
        "COMPANY_NAME": "Refracted Consulting, LLC",
        "CSS_THEME": "kek_light",
        "CURRENT_PAGE": "home",
        "CURRENT_ROUTE": "/admin/home",
        "SESSION":
        {
            "history":
            [
                "http://refracted.consulting",
            ]
        },
        "SITE_NAME": "Refracted Studios",
        "USER":
        {
            "id":"1471247124_2006-PRJuTJnOLrTjPRzE",
            "display_name":"Robert Schwindaman",
            "settings":
            {
                "kek_menu_visible": true
            }
        },
        "VERSION": "1.0.0"
    }
    */
    'use strict';
    var __logs = [];

    class __Object {
        constructor(args)
        {
            this.args = args;
            for(var key in args)
            {
                if(!empty(this[key])) console.log(this[key]);
                this[key] = args[key];
            }
        }
    }

    class __Session extends __Object {
        init()
        {
            if(empty(this.memory_id)) this.memory_id = "kek_session";
            if(empty(this.memory_obj)) this.memory_obj = sessionStorage;
            if(empty(this.memory)) this.memory = this.memory_obj[this.memory_id];

            this.get();
            this.get_history();
            this.add_history(location.href);
        }

        constructor(args)
        {
            super(args);
            this.init();
        }

        add_history(path)
        {
            if(this.history[this.history.length-1]===path) return false;
            if(path.indexOf('/logout')!== -1) return false;
            this.history.push(path);
            this.set_history();
        }

        forget(key)
        {
            if(empty(key)) this.reset();
            else delete this.data[key];
        }

        get(key)
        {
            this.memory = this.memory_obj[this.memory_id];
            if(empty(this.memory)) return {};

            if(!is_json(this.memory)) return {};
            this.data = JSON.parse(this.memory);

            if(!empty(key)) return this.data[key];
            return this.data;
        }

        get_history()
        {
            if(empty(this.history) && empty(sessionStorage.history)) this.reset_history();
            this.history = JSON.parse(sessionStorage.history);
        }

        go_back()
        {
            if(empty(this.history)) location.href = '/';
            this.history.pop();
            var last = this.history[this.history.length-1];
            this.set_history();
            return location.href = empty(last)?'/':last;
        }

        reset()
        {
            this.set({});
            this.reset_history();
        }

        reset_history()
        {
            this.history = [];
            this.set_history();
        }

        save()
        {
            this.memory_obj[this.memory_id] = JSON.stringify(this.data);
            this.get();
        }

        set(val)
        {
            this.data = val;
            this.save();
        }

        set_history()
        {
            sessionStorage.history = JSON.stringify(this.history);
        }

        val(key, val)
        {
            if(empty(this.data)) this.data = {};
            this.data[key] = val;
            this.save();
        }
    }

    class __User extends __Object {
        constructor(args)
        {
            if(empty(args)) args = {};

            super(args);

            if(empty(args.user_id)) args.user_id = 'anon';

            this.id = `${window.USER_ID || 'n/a'}`;
            this.settings = window.USER_SETTINGS || {};
            this.display_name = window.USER_DISPLAY_NAME || 'n/a';
        }
    }

    class Kek extends __Object {
        constructor(args)
        {
            super(args);

            this.COMPANY_NAME = `${window.COMPANY_NAME_LONG || 'n/a'}`;
            this.CSS_THEME = `${window.CSS_THEME || 'dark'}`;
            this.CURRENT_PAGE = `${window.CURRENT_PAGE || 'n/a'}`;
            this.CURRENT_ROUTE = `${window.CURRENT_ROUTE || 'n/a'}`;
            this.SESSION = new __Session();
            this.SITE_NAME = `${window.SITE_NAME || 'n/a'}`;
            this.USER = new __User();
            this.VERSION = `${window.KEK_VERSION || 'n/a'}`;
            console.log("kekPHP|> Initialized", this);
        }

        error(message, data)
        {
            data = empty(data)?{}:data;
            data.log_type = "error";
            this.log(message, data);
        }

        log(message, data)
        {
            data = empty(data)?{}:data;
            if(empty(data)) console.log(message);
            else
            {
                try
                {
                    if(is_json(data)) data = JSON.parse(data);
                }
                catch(e)
                {
                    console.error("kekPHP|> JSON Parse | Kek.log(): "+e, data);
                    return false;
                }

                var level = empty(data['log_type'])?'log':data['log_type'];
                if(level==='error') return console.error("kekPHP(error)|> "+message, data);
                if(level==='warn') return console.warn("kekPHP(warn)|> "+message, data);
                if(level==='info') return console.info("kekPHP|> "+message, data);
                return console.log("kekPHP|> "+message, data);
            }
        }

        warn(message, data)
        {
            data = empty(data)?{}:data;
            data.log_type = "warn";
            this.log(message, data);
        }
    }

    /* Initialize kek */
    var kek = new Kek();
</script>