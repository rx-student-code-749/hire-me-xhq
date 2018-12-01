let App = {
    Settings: {
        requestURI: "app.php",
    },
    History: {
        previousPage: "",
        currentPage: "",
        previousParams: {},
        currentParams: {},
    },
    Request(action, data = null, method = 'GET', dataType = 'json') {
        let xData = {};

        if (data !== null)
            xData = {
                action: action,
                data: JSON.stringify(data)
            };
        else
            xData = {
                action: action
            };

        let xRequest = {
            url: App.Settings.requestURI,
            method: method,
            dataType: dataType,
            data: xData,
        };

        console.log(xRequest);
        console.log($.param(xRequest.data));
        return $.ajax(xRequest);
    },
    Navigate(page, lvl = 1, fn = () => {}, _xpd = {}) {
        let resolver = (container, page) => {
            container.addClass('loading');

            App.Request('getHTML', {
                page: page,
                vars: _xpd
            }).done((data) => {
                if (data.status === 404)
                    $.notify(data.errors, 'error');
                else {
                    document.title = data.title;

                    App.History.previousPage = App.History.currentPage;
                    App.History.currentPage = page;
                    App.History.previousParams = App.History.currentParams;
                    App.History.currentParams = _xpd;

                    sessionStorage.setItem('xapp-history', JSON.stringify(App.History));

                    container.html(data.html);
                    fn(container, data);

                    if (page === "post_new_job") {
                        tinymce.remove();
                        tinymce.init({
                            selector: '#description',
                            width: 600,
                            height: 300,
                        });
                    }

                }
            });

            container.removeClass('loading');
        };

        if (lvl === 0) {
            resolver(App.Containers.main, page);
        } else if (lvl === 1) {
            resolver(App.Containers.contentArea, page);
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

    Core: {

        boot() {
            App.Navigate(
                (App.History.currentPage !== 'login' && App.History.currentPage !== 'logout')
                    ? App.History.currentPage || "dashboard"
                    : "dashboard", 2, function () {
                    App.Containers.main.addClass('xapp-running');
                    App.Actions.refreshSidebar();
                }, App.History.currentParams);
        },
        bootstrap() {
            // console.log("App bootstrap started.");
            // App.Debug();
            App.Containers.main.empty();
            // console.log(sessionStorage.getItem('xapp-history'));
            App.History = (sessionStorage.getItem('xapp-history') !== null)
                ? JSON.parse(sessionStorage.getItem('xapp-history'))
                : App.History;

            App.Request('isLoggedIn').done(function (data) {
                if (data.logged_in === false)
                    App.Navigate('login', 0, function (c, data) {
                        App.Containers.main.html(data.html);
                        App.Containers.main.addClass('login');
                    });
                else
                    App.Core.boot();
            });

            // console.log("bootstrap()::End");
        },
    },
    Actions: {
        reloadApp() {
            App.Core.bootstrap();
        },
        refreshSidebar() {
            App.Request('getHTML', {
                page: 'sidebar'
            }).done(function (data) {
                App.Containers.sidebar.html(data.html);
            });
        },


        Job: {
            new() {
                let er = false;
                let form = $('form#new-job');

                form.children().each(() => {
                    let el = $(arguments[1]);

                    if (el.attr('required'))
                        if (!el.val()) {
                            el.notify('This is a required field.', 'error');
                            er = true;
                        }


                    switch (el.attr('name')) {

                    }
                });

                let fData = form.serialize();
                fData['description'] = window.parent.tinymce.get('#description').getContent();

                console.log(fData);

                // if (!er)
                //     App.Request('addJob', fData).done((data) => {
                //         if (data.status === 200) {
                //             $.notify(data.msg, 'success');
                //             App.Navigate('job_details', 1, function () {
                //             }, {
                //                 id: data.xid
                //             });
                //         } else if (data.status === 404)
                //             $.notify(data.errors, 'error');
                //         else {
                //             data.errors.each(function (name, msg) {
                //                 console.log(arguments);
                //             });
                //         }
                //     });

            },
            apply_cancel(el, jid) {
                App.Request('applyCancelJob', {
                    id: jid
                }).done((data) => {
                    console.log(data);
                    if (data.status === 200) {
                        $.notify(data.msg, 'success');
                        el.toggleClass('appliedFor');
                        el.toggleClass('notAppliedFor');
                    } else if (data.status === 404)
                        $.notify(data.errors, 'error');
                    else {
                        // data.errors.each(function (name, msg) {
                        //     console.log(arguments);
                        // });
                    }
                });
            },
        },
        User: {
            login() {
                App.Request('login', $('form#login').serialize()).done((data) => {
                    if (data.status === 200) {
                        $.notify(data.msg, 'success');
                        App.Containers.main.removeClass('login');
                        App.Core.boot();
                    } else {
                        if (data.errors)
                            if (data.errors.username)
                                $('#username').notify(data.errors.username, 'error');
                        if (data.errors.password)
                            $('#password').notify(data.errors.password, 'error');
                    }
                });
            },
            logout() {
                App.Request('logout').done((data) => {
                    if (data.status === 200) {
                        $.notify(data.msg, 'success');
                        App.Containers.main.removeClass('xapp-running');
                        App.Core.bootstrap();
                    } else {
                        $.notify(data.errors, 'error')
                    }
                });
            },
            new() {
                let tmp;
                let error = false;
                let form = $('form#register');

                form.children().each(() => {
                    let el = $(arguments[1]);

                    if (el.attr('required'))
                        if (!el.val()) {
                            el.notify('This is a required field.', 'error');
                            error = true;
                        }

                    switch (el.attr('name')) {
                        case 'password':
                            tmp = el;
                            if (App.Plugins.passwordStrengthMeter(el.val(), true) < 2) {
                                el.notify('Password too weak!', 'error');
                                error = true;
                            }
                            break;
                        case 'cfm_password':
                            if (!(tmp.val().valueOf().trim() === el.val().valueOf().trim())) {
                                el.notify('Passwords must match!', 'error');
                                error = true;
                            }
                            break;
                    }
                });

                if (!error)
                    App.Request('addUser', form.serialize()).done((data) => {

                        if (data.status === 200) {
                            $.notify(data.msg, 'success');
                            App.Navigate('user_profile', 1, () => {
                            }, {
                                id: data.xid
                            });
                        } else if (data.status === 404)
                            $.notify(data.errors, 'error');
                        else {
                            data.errors.each((name, msg) => {
                                console.log(arguments);
                            });
                        }
                    });

            },
        },
    },
    Plugins: {
        passwordStrengthMeter(password, getStrength = false) {
            let strength = 0;
            let meter = $('#strengthMeter');

            if (password.length < 6) {
                meter.removeClass();
                meter.addClass('short');
                return 'Too short'
            }
            if (password.length > 6) strength += 1;
            if (password.match(/([a-z].*[A-Z])|([A-Z].*[a-z])/)) strength += 1;
            if (password.match(/([a-zA-Z])/) && password.match(/([0-9])/)) strength += 1;
            if (password.match(/([!,%,&,@,#,$,^,*,?,_,~])/)) strength += 1;
            if (password.match(/(.*[!,%,&,@,#,$,^,*,?,_,~].*[!,%,&,@,#,$,^,*,?,_,~])/)) strength += 1;

            if (!getStrength)
                if (strength < 2) {
                    meter.removeClass();
                    meter.addClass('weak');
                    meter.html('Weak');
                } else if (strength === 2) {
                    meter.removeClass();
                    meter.addClass('good');
                    meter.html('Good');
                } else {
                    meter.removeClass();
                    meter.addClass('strong');
                    meter.html('Strong');
                }
            else return strength;
        },
    },
};

App.Core.bootstrap();
