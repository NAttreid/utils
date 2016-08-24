var MD5Hasher = {
    hex_chr: '0123456789abcdef'.split(''),
    md5cycle: function (x, k) {
        var a = x[0], b = x[1], c = x[2], d = x[3];

        a = this.ff(a, b, c, d, k[0], 7, -680876936);
        d = this.ff(d, a, b, c, k[1], 12, -389564586);
        c = this.ff(c, d, a, b, k[2], 17, 606105819);
        b = this.ff(b, c, d, a, k[3], 22, -1044525330);
        a = this.ff(a, b, c, d, k[4], 7, -176418897);
        d = this.ff(d, a, b, c, k[5], 12, 1200080426);
        c = this.ff(c, d, a, b, k[6], 17, -1473231341);
        b = this.ff(b, c, d, a, k[7], 22, -45705983);
        a = this.ff(a, b, c, d, k[8], 7, 1770035416);
        d = this.ff(d, a, b, c, k[9], 12, -1958414417);
        c = this.ff(c, d, a, b, k[10], 17, -42063);
        b = this.ff(b, c, d, a, k[11], 22, -1990404162);
        a = this.ff(a, b, c, d, k[12], 7, 1804603682);
        d = this.ff(d, a, b, c, k[13], 12, -40341101);
        c = this.ff(c, d, a, b, k[14], 17, -1502002290);
        b = this.ff(b, c, d, a, k[15], 22, 1236535329);

        a = this.gg(a, b, c, d, k[1], 5, -165796510);
        d = this.gg(d, a, b, c, k[6], 9, -1069501632);
        c = this.gg(c, d, a, b, k[11], 14, 643717713);
        b = this.gg(b, c, d, a, k[0], 20, -373897302);
        a = this.gg(a, b, c, d, k[5], 5, -701558691);
        d = this.gg(d, a, b, c, k[10], 9, 38016083);
        c = this.gg(c, d, a, b, k[15], 14, -660478335);
        b = this.gg(b, c, d, a, k[4], 20, -405537848);
        a = this.gg(a, b, c, d, k[9], 5, 568446438);
        d = this.gg(d, a, b, c, k[14], 9, -1019803690);
        c = this.gg(c, d, a, b, k[3], 14, -187363961);
        b = this.gg(b, c, d, a, k[8], 20, 1163531501);
        a = this.gg(a, b, c, d, k[13], 5, -1444681467);
        d = this.gg(d, a, b, c, k[2], 9, -51403784);
        c = this.gg(c, d, a, b, k[7], 14, 1735328473);
        b = this.gg(b, c, d, a, k[12], 20, -1926607734);

        a = this.hh(a, b, c, d, k[5], 4, -378558);
        d = this.hh(d, a, b, c, k[8], 11, -2022574463);
        c = this.hh(c, d, a, b, k[11], 16, 1839030562);
        b = this.hh(b, c, d, a, k[14], 23, -35309556);
        a = this.hh(a, b, c, d, k[1], 4, -1530992060);
        d = this.hh(d, a, b, c, k[4], 11, 1272893353);
        c = this.hh(c, d, a, b, k[7], 16, -155497632);
        b = this.hh(b, c, d, a, k[10], 23, -1094730640);
        a = this.hh(a, b, c, d, k[13], 4, 681279174);
        d = this.hh(d, a, b, c, k[0], 11, -358537222);
        c = this.hh(c, d, a, b, k[3], 16, -722521979);
        b = this.hh(b, c, d, a, k[6], 23, 76029189);
        a = this.hh(a, b, c, d, k[9], 4, -640364487);
        d = this.hh(d, a, b, c, k[12], 11, -421815835);
        c = this.hh(c, d, a, b, k[15], 16, 530742520);
        b = this.hh(b, c, d, a, k[2], 23, -995338651);

        a = this.ii(a, b, c, d, k[0], 6, -198630844);
        d = this.ii(d, a, b, c, k[7], 10, 1126891415);
        c = this.ii(c, d, a, b, k[14], 15, -1416354905);
        b = this.ii(b, c, d, a, k[5], 21, -57434055);
        a = this.ii(a, b, c, d, k[12], 6, 1700485571);
        d = this.ii(d, a, b, c, k[3], 10, -1894986606);
        c = this.ii(c, d, a, b, k[10], 15, -1051523);
        b = this.ii(b, c, d, a, k[1], 21, -2054922799);
        a = this.ii(a, b, c, d, k[8], 6, 1873313359);
        d = this.ii(d, a, b, c, k[15], 10, -30611744);
        c = this.ii(c, d, a, b, k[6], 15, -1560198380);
        b = this.ii(b, c, d, a, k[13], 21, 1309151649);
        a = this.ii(a, b, c, d, k[4], 6, -145523070);
        d = this.ii(d, a, b, c, k[11], 10, -1120210379);
        c = this.ii(c, d, a, b, k[2], 15, 718787259);
        b = this.ii(b, c, d, a, k[9], 21, -343485551);

        x[0] = this.add32(a, x[0]);
        x[1] = this.add32(b, x[1]);
        x[2] = this.add32(c, x[2]);
        x[3] = this.add32(d, x[3]);

    },
    cmn: function (q, a, b, x, s, t) {
        a = this.add32(this.add32(a, q), this.add32(x, t));
        return this.add32((a << s) | (a >>> (32 - s)), b);
    },
    ff: function (a, b, c, d, x, s, t) {
        return this.cmn((b & c) | ((~b) & d), a, b, x, s, t);
    },
    gg: function (a, b, c, d, x, s, t) {
        return this.cmn((b & d) | (c & (~d)), a, b, x, s, t);
    },
    hh: function (a, b, c, d, x, s, t) {
        return this.cmn(b ^ c ^ d, a, b, x, s, t);
    },
    ii: function (a, b, c, d, x, s, t) {
        return this.cmn(c ^ (b | (~d)), a, b, x, s, t);
    },
    md51: function (s) {
        var n = s.length,
                state = [1732584193, -271733879, -1732584194, 271733878], i;
        for (i = 64; i <= s.length; i += 64) {
            this.md5cycle(state, this.md5blk(s.substring(i - 64, i)));
        }
        s = s.substring(i - 64);
        var tail = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
        for (i = 0; i < s.length; i++)
            tail[i >> 2] |= s.charCodeAt(i) << ((i % 4) << 3);
        tail[i >> 2] |= 0x80 << ((i % 4) << 3);
        if (i > 55) {
            md5cycle(state, tail);
            for (i = 0; i < 16; i++)
                tail[i] = 0;
        }
        tail[14] = n * 8;
        this.md5cycle(state, tail);
        return state;
    },
    md5blk: function (s) {
        var md5blks = [], i;
        for (i = 0; i < 64; i += 4) {
            md5blks[i >> 2] = s.charCodeAt(i)
                    + (s.charCodeAt(i + 1) << 8)
                    + (s.charCodeAt(i + 2) << 16)
                    + (s.charCodeAt(i + 3) << 24);
        }
        return md5blks;
    },
    rhex: function (n) {
        var s = '', j = 0;
        for (; j < 4; j++)
            s += this.hex_chr[(n >> (j * 8 + 4)) & 0x0F]
                    + this.hex_chr[(n >> (j * 8)) & 0x0F];
        return s;
    },
    hex: function (x) {
        for (var i = 0; i < x.length; i++)
            x[i] = this.rhex(x[i]);
        return x.join('');
    },
    hash: function (s) {
        return this.hex(this.md51(s));
    },
    add32: function (a, b) {
        return (a + b) & 0xFFFFFFFF;
    },
    init: function () {
        if (this.hash('hello') !== '5d41402abc4b2a76b9719d911017c592') {
            this.add32 = function (x, y) {
                var lsw = (x & 0xFFFF) + (y & 0xFFFF),
                        msw = (x >> 16) + (y >> 16) + (lsw >> 16);
                return (msw << 16) | (lsw & 0xFFFF);
            };
        }
    }
};
MD5Hasher.init();
var RemoveDiacritic = {
    map: {},
    init: function () {
        var defaultMap = [
            {'base': 'A', 'letters': '\u0041\u24B6\uFF21\u00C0\u00C1\u00C2\u1EA6\u1EA4\u1EAA\u1EA8\u00C3\u0100\u0102\u1EB0\u1EAE\u1EB4\u1EB2\u0226\u01E0\u00C4\u01DE\u1EA2\u00C5\u01FA\u01CD\u0200\u0202\u1EA0\u1EAC\u1EB6\u1E00\u0104\u023A\u2C6F'},
            {'base': 'AA', 'letters': '\uA732'},
            {'base': 'AE', 'letters': '\u00C6\u01FC\u01E2'},
            {'base': 'AO', 'letters': '\uA734'},
            {'base': 'AU', 'letters': '\uA736'},
            {'base': 'AV', 'letters': '\uA738\uA73A'},
            {'base': 'AY', 'letters': '\uA73C'},
            {'base': 'B', 'letters': '\u0042\u24B7\uFF22\u1E02\u1E04\u1E06\u0243\u0182\u0181'},
            {'base': 'C', 'letters': '\u0043\u24B8\uFF23\u0106\u0108\u010A\u010C\u00C7\u1E08\u0187\u023B\uA73E'},
            {'base': 'D', 'letters': '\u0044\u24B9\uFF24\u1E0A\u010E\u1E0C\u1E10\u1E12\u1E0E\u0110\u018B\u018A\u0189\uA779'},
            {'base': 'DZ', 'letters': '\u01F1\u01C4'},
            {'base': 'Dz', 'letters': '\u01F2\u01C5'},
            {'base': 'E', 'letters': '\u0045\u24BA\uFF25\u00C8\u00C9\u00CA\u1EC0\u1EBE\u1EC4\u1EC2\u1EBC\u0112\u1E14\u1E16\u0114\u0116\u00CB\u1EBA\u011A\u0204\u0206\u1EB8\u1EC6\u0228\u1E1C\u0118\u1E18\u1E1A\u0190\u018E'},
            {'base': 'F', 'letters': '\u0046\u24BB\uFF26\u1E1E\u0191\uA77B'},
            {'base': 'G', 'letters': '\u0047\u24BC\uFF27\u01F4\u011C\u1E20\u011E\u0120\u01E6\u0122\u01E4\u0193\uA7A0\uA77D\uA77E'},
            {'base': 'H', 'letters': '\u0048\u24BD\uFF28\u0124\u1E22\u1E26\u021E\u1E24\u1E28\u1E2A\u0126\u2C67\u2C75\uA78D'},
            {'base': 'I', 'letters': '\u0049\u24BE\uFF29\u00CC\u00CD\u00CE\u0128\u012A\u012C\u0130\u00CF\u1E2E\u1EC8\u01CF\u0208\u020A\u1ECA\u012E\u1E2C\u0197'},
            {'base': 'J', 'letters': '\u004A\u24BF\uFF2A\u0134\u0248'},
            {'base': 'K', 'letters': '\u004B\u24C0\uFF2B\u1E30\u01E8\u1E32\u0136\u1E34\u0198\u2C69\uA740\uA742\uA744\uA7A2'},
            {'base': 'L', 'letters': '\u004C\u24C1\uFF2C\u013F\u0139\u013D\u1E36\u1E38\u013B\u1E3C\u1E3A\u0141\u023D\u2C62\u2C60\uA748\uA746\uA780'},
            {'base': 'LJ', 'letters': '\u01C7'},
            {'base': 'Lj', 'letters': '\u01C8'},
            {'base': 'M', 'letters': '\u004D\u24C2\uFF2D\u1E3E\u1E40\u1E42\u2C6E\u019C'},
            {'base': 'N', 'letters': '\u004E\u24C3\uFF2E\u01F8\u0143\u00D1\u1E44\u0147\u1E46\u0145\u1E4A\u1E48\u0220\u019D\uA790\uA7A4'},
            {'base': 'NJ', 'letters': '\u01CA'},
            {'base': 'Nj', 'letters': '\u01CB'},
            {'base': 'O', 'letters': '\u004F\u24C4\uFF2F\u00D2\u00D3\u00D4\u1ED2\u1ED0\u1ED6\u1ED4\u00D5\u1E4C\u022C\u1E4E\u014C\u1E50\u1E52\u014E\u022E\u0230\u00D6\u022A\u1ECE\u0150\u01D1\u020C\u020E\u01A0\u1EDC\u1EDA\u1EE0\u1EDE\u1EE2\u1ECC\u1ED8\u01EA\u01EC\u00D8\u01FE\u0186\u019F\uA74A\uA74C'},
            {'base': 'OI', 'letters': '\u01A2'},
            {'base': 'OO', 'letters': '\uA74E'},
            {'base': 'OU', 'letters': '\u0222'},
            {'base': 'OE', 'letters': '\u008C\u0152'},
            {'base': 'oe', 'letters': '\u009C\u0153'},
            {'base': 'P', 'letters': '\u0050\u24C5\uFF30\u1E54\u1E56\u01A4\u2C63\uA750\uA752\uA754'},
            {'base': 'Q', 'letters': '\u0051\u24C6\uFF31\uA756\uA758\u024A'},
            {'base': 'R', 'letters': '\u0052\u24C7\uFF32\u0154\u1E58\u0158\u0210\u0212\u1E5A\u1E5C\u0156\u1E5E\u024C\u2C64\uA75A\uA7A6\uA782'},
            {'base': 'S', 'letters': '\u0053\u24C8\uFF33\u1E9E\u015A\u1E64\u015C\u1E60\u0160\u1E66\u1E62\u1E68\u0218\u015E\u2C7E\uA7A8\uA784'},
            {'base': 'T', 'letters': '\u0054\u24C9\uFF34\u1E6A\u0164\u1E6C\u021A\u0162\u1E70\u1E6E\u0166\u01AC\u01AE\u023E\uA786'},
            {'base': 'TZ', 'letters': '\uA728'},
            {'base': 'U', 'letters': '\u0055\u24CA\uFF35\u00D9\u00DA\u00DB\u0168\u1E78\u016A\u1E7A\u016C\u00DC\u01DB\u01D7\u01D5\u01D9\u1EE6\u016E\u0170\u01D3\u0214\u0216\u01AF\u1EEA\u1EE8\u1EEE\u1EEC\u1EF0\u1EE4\u1E72\u0172\u1E76\u1E74\u0244'},
            {'base': 'V', 'letters': '\u0056\u24CB\uFF36\u1E7C\u1E7E\u01B2\uA75E\u0245'},
            {'base': 'VY', 'letters': '\uA760'},
            {'base': 'W', 'letters': '\u0057\u24CC\uFF37\u1E80\u1E82\u0174\u1E86\u1E84\u1E88\u2C72'},
            {'base': 'X', 'letters': '\u0058\u24CD\uFF38\u1E8A\u1E8C'},
            {'base': 'Y', 'letters': '\u0059\u24CE\uFF39\u1EF2\u00DD\u0176\u1EF8\u0232\u1E8E\u0178\u1EF6\u1EF4\u01B3\u024E\u1EFE'},
            {'base': 'Z', 'letters': '\u005A\u24CF\uFF3A\u0179\u1E90\u017B\u017D\u1E92\u1E94\u01B5\u0224\u2C7F\u2C6B\uA762'},
            {'base': 'a', 'letters': '\u0061\u24D0\uFF41\u1E9A\u00E0\u00E1\u00E2\u1EA7\u1EA5\u1EAB\u1EA9\u00E3\u0101\u0103\u1EB1\u1EAF\u1EB5\u1EB3\u0227\u01E1\u00E4\u01DF\u1EA3\u00E5\u01FB\u01CE\u0201\u0203\u1EA1\u1EAD\u1EB7\u1E01\u0105\u2C65\u0250'},
            {'base': 'aa', 'letters': '\uA733'},
            {'base': 'ae', 'letters': '\u00E6\u01FD\u01E3'},
            {'base': 'ao', 'letters': '\uA735'},
            {'base': 'au', 'letters': '\uA737'},
            {'base': 'av', 'letters': '\uA739\uA73B'},
            {'base': 'ay', 'letters': '\uA73D'},
            {'base': 'b', 'letters': '\u0062\u24D1\uFF42\u1E03\u1E05\u1E07\u0180\u0183\u0253'},
            {'base': 'c', 'letters': '\u0063\u24D2\uFF43\u0107\u0109\u010B\u010D\u00E7\u1E09\u0188\u023C\uA73F\u2184'},
            {'base': 'd', 'letters': '\u0064\u24D3\uFF44\u1E0B\u010F\u1E0D\u1E11\u1E13\u1E0F\u0111\u018C\u0256\u0257\uA77A'},
            {'base': 'dz', 'letters': '\u01F3\u01C6'},
            {'base': 'e', 'letters': '\u0065\u24D4\uFF45\u00E8\u00E9\u00EA\u1EC1\u1EBF\u1EC5\u1EC3\u1EBD\u0113\u1E15\u1E17\u0115\u0117\u00EB\u1EBB\u011B\u0205\u0207\u1EB9\u1EC7\u0229\u1E1D\u0119\u1E19\u1E1B\u0247\u025B\u01DD'},
            {'base': 'f', 'letters': '\u0066\u24D5\uFF46\u1E1F\u0192\uA77C'},
            {'base': 'g', 'letters': '\u0067\u24D6\uFF47\u01F5\u011D\u1E21\u011F\u0121\u01E7\u0123\u01E5\u0260\uA7A1\u1D79\uA77F'},
            {'base': 'h', 'letters': '\u0068\u24D7\uFF48\u0125\u1E23\u1E27\u021F\u1E25\u1E29\u1E2B\u1E96\u0127\u2C68\u2C76\u0265'},
            {'base': 'hv', 'letters': '\u0195'},
            {'base': 'i', 'letters': '\u0069\u24D8\uFF49\u00EC\u00ED\u00EE\u0129\u012B\u012D\u00EF\u1E2F\u1EC9\u01D0\u0209\u020B\u1ECB\u012F\u1E2D\u0268\u0131'},
            {'base': 'j', 'letters': '\u006A\u24D9\uFF4A\u0135\u01F0\u0249'},
            {'base': 'k', 'letters': '\u006B\u24DA\uFF4B\u1E31\u01E9\u1E33\u0137\u1E35\u0199\u2C6A\uA741\uA743\uA745\uA7A3'},
            {'base': 'l', 'letters': '\u006C\u24DB\uFF4C\u0140\u013A\u013E\u1E37\u1E39\u013C\u1E3D\u1E3B\u017F\u0142\u019A\u026B\u2C61\uA749\uA781\uA747'},
            {'base': 'lj', 'letters': '\u01C9'},
            {'base': 'm', 'letters': '\u006D\u24DC\uFF4D\u1E3F\u1E41\u1E43\u0271\u026F'},
            {'base': 'n', 'letters': '\u006E\u24DD\uFF4E\u01F9\u0144\u00F1\u1E45\u0148\u1E47\u0146\u1E4B\u1E49\u019E\u0272\u0149\uA791\uA7A5'},
            {'base': 'nj', 'letters': '\u01CC'},
            {'base': 'o', 'letters': '\u006F\u24DE\uFF4F\u00F2\u00F3\u00F4\u1ED3\u1ED1\u1ED7\u1ED5\u00F5\u1E4D\u022D\u1E4F\u014D\u1E51\u1E53\u014F\u022F\u0231\u00F6\u022B\u1ECF\u0151\u01D2\u020D\u020F\u01A1\u1EDD\u1EDB\u1EE1\u1EDF\u1EE3\u1ECD\u1ED9\u01EB\u01ED\u00F8\u01FF\u0254\uA74B\uA74D\u0275'},
            {'base': 'oi', 'letters': '\u01A3'},
            {'base': 'ou', 'letters': '\u0223'},
            {'base': 'oo', 'letters': '\uA74F'},
            {'base': 'p', 'letters': '\u0070\u24DF\uFF50\u1E55\u1E57\u01A5\u1D7D\uA751\uA753\uA755'},
            {'base': 'q', 'letters': '\u0071\u24E0\uFF51\u024B\uA757\uA759'},
            {'base': 'r', 'letters': '\u0072\u24E1\uFF52\u0155\u1E59\u0159\u0211\u0213\u1E5B\u1E5D\u0157\u1E5F\u024D\u027D\uA75B\uA7A7\uA783'},
            {'base': 's', 'letters': '\u0073\u24E2\uFF53\u00DF\u015B\u1E65\u015D\u1E61\u0161\u1E67\u1E63\u1E69\u0219\u015F\u023F\uA7A9\uA785\u1E9B'},
            {'base': 't', 'letters': '\u0074\u24E3\uFF54\u1E6B\u1E97\u0165\u1E6D\u021B\u0163\u1E71\u1E6F\u0167\u01AD\u0288\u2C66\uA787'},
            {'base': 'tz', 'letters': '\uA729'},
            {'base': 'u', 'letters': '\u0075\u24E4\uFF55\u00F9\u00FA\u00FB\u0169\u1E79\u016B\u1E7B\u016D\u00FC\u01DC\u01D8\u01D6\u01DA\u1EE7\u016F\u0171\u01D4\u0215\u0217\u01B0\u1EEB\u1EE9\u1EEF\u1EED\u1EF1\u1EE5\u1E73\u0173\u1E77\u1E75\u0289'},
            {'base': 'v', 'letters': '\u0076\u24E5\uFF56\u1E7D\u1E7F\u028B\uA75F\u028C'},
            {'base': 'vy', 'letters': '\uA761'},
            {'base': 'w', 'letters': '\u0077\u24E6\uFF57\u1E81\u1E83\u0175\u1E87\u1E85\u1E98\u1E89\u2C73'},
            {'base': 'x', 'letters': '\u0078\u24E7\uFF58\u1E8B\u1E8D'},
            {'base': 'y', 'letters': '\u0079\u24E8\uFF59\u1EF3\u00FD\u0177\u1EF9\u0233\u1E8F\u00FF\u1EF7\u1E99\u1EF5\u01B4\u024F\u1EFF'},
            {'base': 'z', 'letters': '\u007A\u24E9\uFF5A\u017A\u1E91\u017C\u017E\u1E93\u1E95\u01B6\u0225\u0240\u2C6C\uA763'}
        ];

        for (var i = 0; i < defaultMap.length; i++) {
            var letters = defaultMap [i].letters;
            for (var j = 0; j < letters.length; j++) {
                this.map[letters[j]] = defaultMap[i].base;
            }
        }
    }
};
RemoveDiacritic.init();
(function ($, window) {
    if (window.jQuery === undefined) {
        console.error('Plugin "jQuery" required by "utils/jQuery.js" is missing!');
        return;
    }

    /**
     * Metoda pro scrollovani objektu na strance
     * @param {object} opt
     * @returns {jQuery.fn}
     */
    $.fn.fixedPosition = function (opt) {

        function getTopOffset() {
            var topOffset = element.offset().top;
            if (options.from !== null) {
                topOffset = $(options.from).offset().top;
            }
            return topOffset;
        }

        function scroll() {
            if ((options.to !== null && $(options.to).length > 0) && ($(window).scrollTop() + element.height() >= $(options.to).offset().top)) {
                var top = $(options.to).offset().top - element.height() + options.bottom;
                if (topOffset <= top) {
                    element
                            .removeAttr('style')
                            .css('position', 'absolute')
                            .css('top', top + 'px')
                            .addClass('fixed')
                            .removeClass('moving');
                } else {
                    clear();
                }
            } else if (($(window).scrollTop() > topOffset - options.top)) {
                element
                        .removeAttr('style')
                        .css('position', 'fixed')
                        .css('top', 0)
                        .addClass('moving')
                        .removeClass('fixed');
                if (options.width !== null) {
                    element.css('width', getWidth());
                }
            } else {
                clear();
            }
        }

        function clear() {
            element
                    .removeAttr('style')
                    .removeClass('fixed')
                    .removeClass('moving');
        }

        function getWidth() {
            return options.width instanceof jQuery ? options.width.width() : options.width;
        }

        var options = {
            top: 0,
            bottom: 0,
            from: null,
            to: null,
            width: null
        };

        if (options !== null) {
            for (var property in opt) {
                if (opt[property] !== null) {
                    options[property] = opt[property];
                }
            }
        }

        var element = $(this);

        if (element.length > 0) {

            var topOffset = getTopOffset();

            $(window).on('resize', function () {
                if (element.hasClass('moving') && options.width !== null) {
                    element.css('width', getWidth());
                }
                topOffset = getTopOffset();
            });

            $(document).on('scroll', scroll);
            scroll();
        }
        return this;
    };

    /**
     * Metoda pro vycentrovani na obrazovce (zustane na aktualni pozici)
     * @returns {jQuery.fn}
     */
    $.fn.center = function () {
        this.css('position', 'absolute');
        this.css({visibility: 'hidden', display: 'block'});
        this.css('top', Math.max(0, (($(window).height() - this.outerHeight()) / 2) + $(window).scrollTop()) + 'px');
        this.css('left', Math.max(0, (($(window).width() - this.outerWidth()) / 2) + $(window).scrollLeft()) + 'px');
        this.css({visibility: '', display: ''});
        return this;
    };

    /**
     * Metoda pro vycentrovani na obrazovce (posouva se pri scrollu)
     * @returns {jQuery.fn}
     */
    $.fn.centerFixed = function () {
        this.css('position', 'fixed');
        this.css({visibility: 'hidden', display: 'block'});
        this.css('top', Math.max(0, (($(window).height() - this.outerHeight()) / 2)) + 'px');
        this.css('left', Math.max(0, (($(window).width() - this.outerWidth()) / 2)) + 'px');
        this.css({visibility: '', display: ''});
        return this;
    };

    /**
     * Metoda spousti callback pri kliku mimo dany objekt
     * @param {callback} callback
     * @returns {jQuery.fn}
     */
    $.fn.clickOut = function (callback) {
        var container = this;
        var id = container.clickOff();
        $(document).on('click.clickOutEvent' + id, function (e) {
            var enableClick = true;

            if (!container.is(e.target) && container.has(e.target).length === 0) {
                enableClick = false;
                if (typeof (callback) === 'function') {
                    var disable = callback(container, e);
                } else {
                    window.console.error('Neni volan callback v funkci $.clickOut');
                }
            }

            if (disable === true) {
                container.clickOff();
            }

            if (enableClick) {
                return true;
            } else {
                e.stopPropagation();
                e.preventDefault();
                return false;
            }
        });
        return this;
    };

    /**
     * Vypnuti clickOut
     * @returns {String}
     */
    $.fn.clickOff = function () {
        var container = this;
        container.uniqueId();
        var id = container.attr('id');
        $(document).off('click.clickOutEvent' + id);
        return id;
    };

    /**
     * Metoda spousti callback pri najeti okna k danemu elementu
     * @param {callback} callback
     * @returns {jQuery.fn}
     */
    $.fn.onScrollTo = function (callback) {
        var element = $(this);

        if (element.length > 0) {

            element.uniqueId();
            var id = element.attr('id');

            function bindEvent() {
                $(window).bind('scroll.scrollToEvent' + id, function () {
                    if ($(window).scrollTop() + $(window).height() >= element.offset().top) {
                        $(window).unbind('scroll.scrollToEvent' + id);
                        var disable = callback(element);
                        if (!disable === true) {
                            bindEvent();
                        }
                    }
                });
            }
            bindEvent();
        }
        return this;
    };

    /**
     * Zkopiruje obsah objeku do schranky
     * @returns {Boolean}
     */
    $.fn.copyToClipboard = function () {
        var elem = this[0];

        // create hidden text element, if it doesn't already exist
        var targetId = "_hiddenCopyText_";
        var isInput = elem.tagName === "INPUT" || elem.tagName === "TEXTAREA";
        var origSelectionStart, origSelectionEnd;
        if (isInput) {
            // can just use the original source element for the selection and copy
            target = elem;
            origSelectionStart = elem.selectionStart;
            origSelectionEnd = elem.selectionEnd;
        } else {
            // must use a temporary form element for the selection and copy
            target = document.getElementById(targetId);
            if (!target) {
                var target = document.createElement("textarea");
                target.style.position = "absolute";
                target.style.left = "-9999px";
                target.style.top = "0";
                target.id = targetId;
                document.body.appendChild(target);
            }
            target.textContent = elem.textContent;
        }
        // select the content
        var currentFocus = document.activeElement;
        target.focus();
        target.setSelectionRange(0, target.value.length);

        // copy the selection
        var succeed;
        try {
            succeed = document.execCommand("copy");
        } catch (e) {
            succeed = false;
        }
        // restore original focus
        if (currentFocus && typeof currentFocus.focus === "function") {
            currentFocus.focus();
        }

        if (isInput) {
            // restore prior selection
            elem.setSelectionRange(origSelectionStart, origSelectionEnd);
        } else {
            // clear temporary content
            target.textContent = "";
        }
        return succeed;
    };

    /**
     * Umisti objekt podle pozice mysi. Vraci objekt s pozici pro zobrazeni objektu
     * @param {Event} event
     * @param {int} x
     * @param {int} y
     * @returns {jQuery.fn.onPosition.position}
     */
    $.fn.onPosition = function (event, x, y) {
        var obj = $(this);
        x = typeof (x) === 'undefined' ? 0 : x;
        y = typeof (y) === 'undefined' ? 0 : y;

        var position = {
            left: $(window).width() < event.pageX + obj.outerWidth(true) ? event.pageX - obj.outerWidth(true) - x : event.pageX + x,
            top: $(window).height() < event.pageY + obj.outerHeight(true) ? event.pageY - obj.outerHeight(true) - y : event.pageY + y
        };
        obj.css({
            position: 'absolute',
            left: position.left,
            top: position.top
        });
        return position;
    };

    /**
     * Nacteni skriptu
     * @param {string} url
     * @param {object} options
     * @returns {jqXHR}
     */
    $.cachedScript = function (url, options) {
        options = $.extend(options || {}, {
            dataType: "script",
            cache: true,
            url: url
        });
        return jQuery.ajax(options);
    };

})(jQuery, window);
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