(function () {
    tinymce.create("tinymce.plugins.bazo_shortcode_button_plugin", {

        //url argument holds the absolute url of our plugin directory
        init: function (ed, url) {

            //add new button
            ed.addButton("bazo", {
                title: "Bazo Shortcode Insert",
                cmd: "shortcode_insert",
                image: url + '/fv-150x150.png'
            });

            //button functionality.
            ed.addCommand("shortcode_insert", function () {

                ed.windowManager.open({
                    title: 'Bazo Shortcode Builder',
                    body: [{
                        type: 'textbox',
                        name: 'name',
                        label: 'Treść widoczna w przypadku nie wykrycia firmy',
                        value: '',
                    }, {
                        type: 'checkbox',
                        name: 'lowernext',
                        label: 'Zamieniaj następną litere po firmie na małą',
                        value: false,
                    }, {
                        type: 'textbox',
                        name: 'maxlength',
                        label: 'Maksymalna długość nazwy firmy',
                        value: '',
                    }, {
                        type: 'textbox',
                        name: 'color',
                        label: 'Kolor tekstu wykrytej firmy',
                        value: '',
                    }, {
                        type: 'textbox',
                        name: 'prefix',
                        label: 'Tekst przed nazwą wykrytej firmy',
                        value: '',
                    }, {
                        type: 'textbox',
                        name: 'suffix',
                        label: 'Tekst po nazwie wykrytej firmy',
                        value: '',
                    }, {
                        type: 'checkbox',
                        name: 'removestatut',
                        label: 'Usuwaj statut firmy z jej nazwy',
                        value: false,
                    }],
                    onsubmit: function (e) {
                        let name = e.data.name;
                        (name != '') ? name = ' name="' + name + '"' : name = '';

                        let lowernext = e.data.lowernext;
                        (lowernext) ? lowernext = ' lowernext="1"' : lowernext = '';

                        let maxlength = e.data.maxlength;
                        (maxlength != '') ? maxlength = ' maxlength="' + maxlength + '"' : maxlength = '';

                        let color = e.data.color;
                        (color != '') ? color = ' color="' + color + '"' : color = '';

                        let prefix = e.data.prefix;
                        (prefix != '') ? prefix = ' prefix="' + prefix + '"' : prefix = '';

                        let suffix = e.data.suffix;
                        (suffix != '') ? suffix = ' suffix="' + suffix + '"' : suffix = '';

                        let removestatut = e.data.removestatut;
                        (removestatut) ? removestatut = ' removestatut="1"' : removestatut = '';

                        ed.insertContent(
                            '[bazo-company' + name + lowernext + maxlength + color + prefix + suffix + removestatut + ']'
                        );
                    }

                });
            });

        },


        getInfo: function () {
            return {
                longname: "Bazo Shortcode",
                author: "Bazo",
                version: "1"
            };
        }
    });

    tinymce.PluginManager.add("bazo_shortcode_button_plugin", tinymce.plugins.bazo_shortcode_button_plugin);
})();
