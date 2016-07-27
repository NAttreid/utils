/**
 * Format cisla
 * @param {int} decimals
 * @param {int} decPoint
 * @param {String} thousandSeparator
 * @returns {String}
 */
Number.prototype.format = function (decimals, decPoint, thousandSeparator) {
    var n = this,
            decimals = isNaN(decimals = Math.abs(decimals)) ? 2 : decimals,
            decPoint = decPoint === undefined ? ',' : decPoint,
            thousandSeparator = thousandSeparator === undefined ? ' ' : thousandSeparator,
            s = n < 0 ? '-' : '',
            i = parseInt(n = Math.abs(+n || 0).toFixed(decimals)) + '',
            j = (j = i.length) > 3 ? j % 3 : 0;
    return s + (j ? i.substr(0, j) + thousandSeparator : '') + i.substr(j).replace(/(\d{3})(?=\d)/g, '$1' + thousandSeparator) + (decimals ? decPoint + Math.abs(n - i).toFixed(decimals).slice(2) : '');
};