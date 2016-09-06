/**
 * Format cisla
 * @param {int} decimals
 * @param {int} decPoint
 * @param {String} thousandSeparator
 * @returns {String}
 */
Number.prototype.format = function (decimals, decPoint, thousandSeparator) {
    var obj = this,
        dec = isNaN(decimals = Math.abs(decimals)) ? 2 : decimals,
        decPnt = decPoint === undefined ? ',' : decPoint,
        sep = thousandSeparator === undefined ? ' ' : thousandSeparator,
        s = obj < 0 ? '-' : '',
        i = parseInt(obj = Math.abs(+obj || 0).toFixed(dec)) + '',
        j = (j = i.length) > 3 ? j % 3 : 0;
    return s + (j ? i.substr(0, j) + sep : '') + i.substr(j).replace(/(\d{3})(?=\d)/g, '$1' + sep) + (dec ? decPnt + Math.abs(obj - i).toFixed(dec).slice(2) : '');
};