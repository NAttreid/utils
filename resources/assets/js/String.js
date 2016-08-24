/**
 * Odstrani diakritiku
 * @returns {String.prototype@call;replace}
 */
String.prototype.removeDiacritic = function () {
    return this.replace(/[^\u0000-\u007E]/g, function (a) {
        return RemoveDiacritic.map[a] || a;
    });
};

/**
 * Vlozi tag do hledaneho retezce (ignoruje diakritiku pri hledani)
 * @param {String} search
 * @param {String} tag
 * @returns {String|String@call;removeDiacritic@call;toLowerCase|String.prototype.injectTag.text}
 */
String.prototype.injectTag = function (search, tag) {
    var text = this.removeDiacritic().toLowerCase();
    var needle = search.removeDiacritic().toLowerCase();
    var indexes = [], i = -1;

    while ((i = text.indexOf(needle, i + 1)) !== -1) {
        indexes.push(i);
    }

    text = '';
    var start = 0;
    for (var i = 0; i < indexes.length; i++) {
        var match = this.substr(indexes[i], needle.length);
        text += this.substr(start, indexes[i] - start) + '<' + tag + '>' + match + '</' + tag + '>';
        start = indexes[i] + needle.length;
    }
    text += this.substr(start, this.length - start);

    return text;
};

/**
 * Hash MD5
 * @returns {String|String@call;removeDiacritic@call;toLowerCase|String.prototype.injectTag.text}
 */
String.prototype.hash = function () {
    return MD5Hasher.hash(this);
}