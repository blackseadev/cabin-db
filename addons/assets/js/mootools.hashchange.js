Element.Events.hashchange = {
    onAdd: function () {
        var hash = location.hash;

        var hashchange = function () {
            if (hash == location.hash) return;
            else hash = location.hash;

            var value = (hash.indexOf('#') == 0 ? hash.substr(1) : hash);
            window.fireEvent('hashchange', value);
            document.fireEvent('hashchange', value);
        };

        if (("onhashchange" in window) && ((document.documentMode != 5) && (document.documentMode != 7))) {
            window.onhashchange = hashchange;
        }
        else {
            hashchange.periodical(50);
        }
    }
};