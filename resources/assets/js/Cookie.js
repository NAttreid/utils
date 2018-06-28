var Cookie = {
    /**
     *
     * @param {String} name
     * @param {*} value
     * @param {int} seconds
     */
    set: function (name, value, seconds) {
        var expires = '';
        if (seconds) {
            var date = new Date();
            date.setTime(date.getTime() + (seconds * 1000));
            expires = "; expires=" + date.toUTCString();
        }
        document.cookie = name + "=" + (value || "") + expires + "; path=/";
    },
    /**
     *
     * @param name
     * @returns {*}
     */
    get: function (name) {
        var nameEQ = name + '=';
        var ca = document.cookie.split(';');
        for (var i = 0; i < ca.length; i++) {
            var c = ca[i];
            while (c.charAt(0) === ' ') c = c.substring(1, c.length);
            if (c.indexOf(nameEQ) === 0) return c.substring(nameEQ.length, c.length);
        }
        return null;
    },
    /**
     *
     * @param {String} name
     */
    remove: function (name) {
        document.cookie = name + '=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/';
    }
};