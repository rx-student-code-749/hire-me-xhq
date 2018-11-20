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

        let reqO = {
            url: App.requestURI,
            method: method,
            dataType: dataType,
            data: tD,
        };

        return $.ajax(reqO);
    },

    Navigate (page, lvl = 1, fn = function () {}) {
        let resolver = function (container, page) {
            container.addClass('loading');

            App.Request('getHTML', {
                page: page
            }).done(function (data) {
                container.html(data.html);
                fn(container, data);
            });

            container.removeClass('loading');
        };

        if (lvl === 0) {
            resolver(App.Containers.main, page);
        } else if (lvl === 1) {

        } else if (lvl === 2) {
            App.Containers.main.empty();

            $("<div />", {
                id: 'sidebar'
            }).appendTo(App.Containers.main);
            $("<div />", {
                id: 'content-area'
            }).appendTo(App.Containers.main);

            App.Containers.sidebar = $('#sidebar');
            App.Containers.contentArea = $('#content-area');

            resolver(App.Containers.contentArea, page);

        } else throw ErrorEvent;
    },

    Containers: {
        main: $("#main-container"),
        document: $(document),
    },

    boot () {
        App.Navigate('dashboard', 2, function () {
            App.Containers.main.addClass('xapp-running');
            App.Actions.refreshSidebar();
        });
    },

    bootstrap() {
        // console.log("App bootstrap started.");

        App.Request('isLoggedIn').done(function (data) {
            if (data.logged_in === false)
                App.Navigate('login', function (c, data) {
                    App.Containers.main.html(data.html);
                    App.Containers.main.addClass('login');
                });
            else
               App.boot();
        });

        // console.log("bootstrap()::End");
    },

    Actions: {
        reloadApp() {
            App.bootstrap();
        },
        refreshSidebar () {
            App.Request('getHTML', {
                page: 'sidebar'
            }).done(function (data) {
                App.Containers.sidebar.html(data.html);
            });
        },
        login(event, el) {
            App.Request('login', $(el.closest('form')).serialize()).done(function (data) {
                console.log(data);

                if (data.status === 200) {
                    $.notify(data.msg);
                    App.Containers.main.removeClass('login');
                    App.boot();
                } else {
                    if (data.errors)
                        if (data.errors.username)
                            $('#username').notify(data.errors.username, 'error');
                        if (data.errors.password)
                            $('#password').notify(data.errors.password, 'error');
                }
            });
        },
    },
};
