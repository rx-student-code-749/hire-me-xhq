let App = {
        FN: {
            _ni: () => {
                $.notify("Feature not implemented!", 'warn');

            },
        },
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

            // console.log(xRequest);
            let xrd = $.ajax(xRequest);
            console.log($.param(xRequest.data));
            console.log(xrd);
            return xrd;
        },
        Navigate(page, lvl = 1, fn = () => {}, _xpd = {}) {
            let resolver = (container, page) => {
                container.addClass('loading');

                App.Request('getHTML', {
                    page: page,
                    vars: _xpd
                }).done((data) => {
                    // if (data.status === 404 || data.status === 500 || data.status === 501)
                    //     $.notify(data.errors, 'error');
                    if ($.inArray(data.status, [
                            404,
                            500,
                            501
                        ]) !== -1) {
                        $.notify(data.errors.message, 'error');
                        $("#default-style").remove();
                        $("<link />", {
                            rel: "stylesheet",
                            href: "res/css/" + data.status + ".css"
                        }).appendTo(document.head);
                        App.Containers.main.load('res/html/' + data.status + ".html");
                        App.Containers.header.hide();
                        document.title = data.status;
                    }
                    else {
                        document.title = data.title;
                        $("#header-title").html(" &bull; " + data.title);

                        App.History.previousPage = App.History.currentPage;
                        App.History.currentPage = page;
                        App.History.previousParams = App.History.currentParams;
                        App.History.currentParams = _xpd;

                        sessionStorage.setItem('xapp-history', JSON.stringify(App.History));

                        container.html(data.html);
                        fn(container, data);

                        if (page === "post_new_job") {
                            $('#description').trumbowyg();
                            // tinymce.remove();
                            // tinymce.init({
                            //     selector: '#description',
                            //     width: 600,
                            //     height: 300,
                            // });
                        }

                    }
                });

                container.removeClass('loading');
            };

            if (lvl === 0) {
                resolver(App.Containers.main, page);
            }
            else if (lvl === 1) {
                resolver(App.Containers.contentArea, page);
            }
            else if (lvl === 2) {
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

            }
            else throw ErrorEvent;
        },
        Containers: {
            header: $("#main-header"),
            main: $("#main-container"),
        },

        Core: {
            boot() {
                App.Actions.refreshSidebar();
                App.Navigate(
                    (App.History.currentPage !== 'login' && App.History.currentPage !== 'logout') ?
                    App.History.currentPage || "dashboard" :
                    "dashboard", 2,
                    function() {
                        App.Containers.main.addClass('xapp-running');
                    }, App.History.currentParams);
            },
            bootstrap() {
                /** @Settings */
                $.trumbowyg.svgPath = 'res/css/vendor/trumbowg/icons.svg';

                // console.log("App bootstrap started.");
                // App.Debug();
                App.Containers.main.empty();
                // console.log(sessionStorage.getItem('xapp-history'));
                App.History = (sessionStorage.getItem('xapp-history') !== null) ?
                    JSON.parse(sessionStorage.getItem('xapp-history')) :
                    App.History;

                App.Request('isLoggedIn').done(function(data) {
                    console.log(data);
                    if (data.logged_in === false)
                        App.Navigate('login', 0, function(c, data) {
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
            async refreshSidebar() {
                App.Request('getHTML', {
                    page: 'sidebar'
                }).done(function(data) {
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

                    // let fData = form.serialize();
                    // fData['description'] = window.parent.tinymce.get('#description').getContent();

                    // console.log(fData);

                    if (!er)
                        App.Request('addJob', form.serialize()).done((data) => {
                            if (data.status === 200) {
                                $.notify(data.msg, 'success');
                                App.Navigate('job_details', 1, function() {}, {
                                    id: data.xid
                                });
                            }
                            else
                                $.notify(data.errors.message, 'error');
                        });

                },
                ac(jid) {

                    // alert(jid);
                    let el = $("#apply-link-btn");
                    // console.log($.ajax({
                    //     url: "app.php",
                    //     data: {
                    //         action: 'applyCancelJob',
                    //         data: {
                    //             id: jid
                    //         }
                    //     },
                    // }).done((d) => console.log(d)).fail((d) => console.log(d)));

                    App.Request('applyCancelJob', {
                        id: jid
                    }).done((data) => {
                        console.log(data);
                        if (data.status === 200) {
                            $.notify(data.msg, 'success');
                            el.toggleClass('appliedFor');
                            el.toggleClass('notAppliedFor');
                        } //else //if (data.status === 404)
                        // $.notify(data.errors, 'error');
                        else {
                            $.notify(data.errors.message, 'error');
                            console.log(data.errors);
                        }
                    }).fail(function() {
                        console.log(arguments);
                    });
                },
                edit: (id) => App.FN._ni(),
                delete: (id) => App.FN._ni(),

            },
            User: {
                login() {
                    App.Request('login', $('form#login').serialize()).done((data) => {
                        if (data.status === 200) {
                            $.notify(data.msg, 'success');
                            App.Containers.main.removeClass('login');
                            App.Core.boot();
                        }
                        else {
                            if (data.errors) {
                                if (data.errors.username)
                                    $('#username').notify(data.errors.username, 'error');
                            if (data.errors.password)
                                $('#password').notify(data.errors.password, 'error');
                            }
                        }
                    });
                },
                logout() {
                    App.Request('logout').done((data) => {
                        if (data.status === 200) {
                            $.notify(data.msg, 'success');
                            App.Containers.main.removeClass('xapp-running');
                            App.Core.bootstrap();
                        }
                        else 
                            $.notify(data.errors.message, 'error')
                        
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
                                    App.Navigate('user_profile', 1, () => {}, {
                                        id: data.xid
                                    });
                                }
                                else
                                    $.notify(data.errors.message, 'error');
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
                }
            else if (strength === 2) {
                meter.removeClass();
                meter.addClass('good');
                meter.html('Good');
            }
            else {
                meter.removeClass();
                meter.addClass('strong');
                meter.html('Strong');
            }
            else return strength;
        },
    },
};

App.Core.bootstrap();
