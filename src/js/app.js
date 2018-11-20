let App = {
    requestURI: "app.php",

    Request(action, data = null, method = 'GET', dataType = 'json') {
        let tD = {};

        if (data !== null)
            tD = {
                action: action,
                data: JSON.stringify(data)
            };
        else
            tD = {
                action: action
            };

        return $.ajax({
            url: App.requestURI,
            method: method,
            dataType: dataType,
            data: tD,
        });
    },

    Navigate (page, lvl = 1, fn = function () {}) {
        let resolver = function (container, page) {
            container.addClass('loading');

            App.Request('getHTML', {
                page: page
            }).done(function (data) {
                container.html(data.html);
                fn(container);
            });

            container.removeClass('loading');
        };

        if (lvl === 0) {
            resolver(App.Containers.main, page);
        } else if (lvl === 1) {

        } else throw ErrorEvent;
    },

    Containers: {
        main: $("#main-container"),
        document: $(document),
    },

    bootstrap() {
        // console.log("App bootstrap started.");

        App.Request('isLoggedIn').done(function (data) {
            if (data.logged_in === false)
                App.Navigate('login', 0, function (c) {
                    c.addClass('login');
                });
        });

        // console.log("bootstrap()::End");
    },

    Actions: {
        reloadPage() {
            App.bootstrap();
            // window.location.reload();
        },
        login(event, el) {
            App.Request('login', $(el.closest('form')).serialize()).done(function (data) {
                console.log(data);

            });
        },
    },
};
