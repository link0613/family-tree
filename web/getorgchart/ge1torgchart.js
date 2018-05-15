if (getOrgChart = function (t, e) {
        this.config = {
            theme: "ula",
            color: "darkred",
            editable: !0,
            zoomable: !0,
            searchable: !0,
            movable: !0,
            gridView: !1,
            printable: !1,
            scale: "auto",
            linkType: "M",
            orientation: getOrgChart.RO_TOP,
            nodeJustification: getOrgChart.NJ_TOP,
            primaryColumns: ["Name", "Title"],
            imageColumn: "Image",
            levelSeparation: 100,
            siblingSeparation: 30,
            subtreeSeparation: 40,
            topXAdjustment: 0,
            topYAdjustment: 0,
            removeEvent: "",
            updateEvent: "",
            renderBoxContentEvent: "",
            clickEvent: "",
            embededDefinitions: "",
            render: "AUTO",
            maxDepth: 50,
            dataSource: null,
            linkLabels: null,
            customize: []
        };
        var i = getOrgChart.util._4("colorScheme");
        if (i && (this.config.color = i), e)for (var a in this.config)"undefined" != typeof e[a] && (this.config[a] = e[a]);
        this._r(), this.version = "1.4", this.theme = getOrgChart.themes[this.config.theme], this.element = t, this.render = "AUTO" == this.config.render ? getOrgChart._R() : this.config.render, this._ai = [], this._ak = [], this._a1 = [], this._zz = 0, this._za = 0, this._aN = [], this._aH = [], this._zq = new getOrgChart.person(-1, null, null, 2, 2), this._y, this._areaWidth = {}, this._ze = {
            found: [],
            showIndex: 0,
            oldValue: "",
            timer: ""
        }, this._as(), this._A = new getOrgChart._A(this.element), this.theme.defs && (this.config.embededDefinitions += this.theme.defs);
        for (id in this.config.customize)this.config.customize[id].theme && (this.config.embededDefinitions += getOrgChart.themes[this.config.customize[id].theme].defs);
        this.load()
    }, getOrgChart.prototype._as = function () {
        this._zW = get._svgElement().msie ? this.element.clientWidth : window.getComputedStyle(this.element, null).width, this._zW = parseInt(this._zW), this._zW < 3 && (this._zW = 1024, this.element.style.width = "1024px"), this._zZ = get._svgElement().msie ? this.element.clientHeight : window.getComputedStyle(this.element, null).height, this._zZ = parseInt(this._zZ), this._zZ < 3 && (this._zZ = parseInt(9 * this._zW / 16), this.element.style.height = this._zZ + "px"), this._aB = this._zW, this._svgGRootElement = this._zZ - this.theme.toolbarHeight;
        var t = getOrgChart.INNER_HTML.replace("[theme]", this.config.theme).replace("[color]", this.config.color).replace(/\[height]/g, this._svgGRootElement).replace(/\[toolbar-height]/g, this.theme.toolbarHeight);
        getOrgChart._zQ && (t = t.slice(0, -6), t += getOrgChart._zQ), this.element.innerHTML = t
    }, getOrgChart.prototype.changeColorScheme = function (t) {
        this.config.color != t && (this._A._zA.className = this._A._zA.className.replace(this.config.color, t), this.config.color = t)
    }, getOrgChart.prototype._aJ = function () {
        switch (this._ai = [], this._ak = [], this._a1 = [], getOrgChart._E(this, this._zq, 0), this.config.orientation) {
            case getOrgChart.RO_TOP:
            case getOrgChart.RO_TOP_PARENT_LEFT:
            case getOrgChart.RO_LEFT:
            case getOrgChart.RO_LEFT_PARENT_TOP:
                this._za = this.config.topXAdjustment + this._zq._zX, this._zz = this.config.topYAdjustment + this._zq._zE;
                break;
            case getOrgChart.RO_BOTTOM:
            case getOrgChart.RO_BOTTOM_PARENT_LEFT:
            case getOrgChart.RO_RIGHT:
            case getOrgChart.RO_RIGHT_PARENT_TOP:
                this._za = this.config.topXAdjustment + this._zq._zX, this._zz = this.config.topYAdjustment + this._zq._zE
        }
        getOrgChart._zf(this, this._zq, 0, 0, 0)
    }, getOrgChart.prototype._searchArea = function (t, e) {
        null == this._ai[e] && (this._ai[e] = 0), this._ai[e] < t.h && (this._ai[e] = t.h)
    }, getOrgChart.prototype._zg = function (t, e) {
        null == this._ak[e] && (this._ak[e] = 0), this._ak[e] < t.w && (this._ak[e] = t.w)
    }, getOrgChart.prototype._zb = function (t, e) {
        t.leftNeighbor = this._a1[e], null != t.leftNeighbor && (t.leftNeighbor.rightNeighbor = t), this._a1[e] = t
    }, getOrgChart.prototype._1 = function (t) {
        switch (this.config.orientation) {
            case getOrgChart.RO_TOP:
            case getOrgChart.RO_TOP_PARENT_LEFT:
            case getOrgChart.RO_BOTTOM:
            case getOrgChart.RO_BOTTOM_PARENT_LEFT:
                return t.w;
            case getOrgChart.RO_RIGHT:
            case getOrgChart.RO_RIGHT_PARENT_TOP:
            case getOrgChart.RO_LEFT:
            case getOrgChart.RO_LEFT_PARENT_TOP:
                return t.h
        }
        return 0
    }, getOrgChart.prototype._K = function (t, e, i) {
        if (e >= i)return t;
        if (0 == t._T())return null;
        for (var a = t._T(), r = 0; a > r; r++) {
            var s = t._F(r), h = this._K(s, e + 1, i);
            if (null != h)return h
        }
        return null
    }, getOrgChart.prototype._graphAreaInvisible2 = function (t) {
        if (this.config.linkLabels && this.config.linkLabels.length > 0) {
            var e;
            for (e = 0; e < this.config.linkLabels.length; e++) {
                var i = this.config.linkLabels[e];
                if (t.id == i.id)return i
            }
        }
        return null
    }, getOrgChart.prototype._Q = function () {
        for (var t = [], e = null, i = 0; i < this._aN.length; i++) {
            switch (e = this._aN[i], this.render) {
                case"SVG":
                    var a = e.getImageUrl(), r = parseInt(e._zX), s = parseInt(e._zE), h = this.config.customize[e.id] && this.config.customize[e.id].theme ? getOrgChart.themes[this.config.customize[e.id].theme] : this.theme, n = this.config.customize[e.id] && this.config.customize[e.id].theme ? " get-" + this.config.customize[e.id].theme : "", o = this.config.customize[e.id] && this.config.customize[e.id].color ? " get-" + this.config.customize[e.id].color : "";
                    n && !o && (o = " get-" + this.config.color), o && !n && (n = " get-" + this.config.theme);
                    var _ = n + o, g = a ? h.textPoints : h.textPointsNoImage;
                    t.push(getOrgChart.OPEN_GROUP.replace("[x]", r).replace("[y]", s).replace("[level]", e.level).replace("[boxCssClass]", _));
                    for (themeProperty in h)switch (themeProperty) {
                        case"image":
                            a && t.push(h.image.replace("[href]", a));
                            break;
                        case"box":
                            t.push(h.box);
                            break;
                        case"text":
                            var l = 0;
                            for (k = 0; k < this.config.primaryColumns.length; k++) {
                                var c = g[l], d = this.config.primaryColumns[k];
                                c && e.data && e.data[d] && (t.push(h.text.replace("[index]", l).replace("[text]", e.data[d]).replace("[y]", c.y).replace("[x]", c.x).replace("[rotate]", c.rotate).replace("[width]", c.width)), l++)
                            }
                    }
                    this._X("renderBoxContentEvent", {
                        id: e.id,
                        parentId: e.pid,
                        data: e.data,
                        boxContentElements: t
                    }), t.push(getOrgChart.CLOSE_GROUP);
                    break;
                case"VML":
            }
            t.push(e._p(this))
        }
        return t.join("")
    }, getOrgChart.prototype._a8 = function () {
        var t = [];
        this._aJ();
        var e = this._y;
        switch (e || (e = this._6()), this.render) {
            case "SVG":
                t.push(getOrgChart.OPEN_SVG.replace("[defs]", this.config.embededDefinitions).replace("[viewBox]", e.toString())), t.push(this._Q()), t.push(getOrgChart.CLOSE_SVG);
                break;
            case "VML":
        }
        return t.join("")
    }, getOrgChart.prototype._6 = function () {
        if ("auto" === this.config.scale) {
            var t = 0, e = 0, a = 0, r = 0;
            for (i = 0; i < this._aN.length; i++)this._aN[i]._zX > t && (t = this._aN[i]._zX), this._aN[i]._zE > e && (e = this._aN[i]._zE), this._aN[i]._zX < a && (a = this._aN[i]._zX), this._aN[i]._zE < r && (r = this._aN[i]._zE);
            var s = a - this.config.siblingSeparation / 2, h = r - this.config.levelSeparation / 2, n = Math.abs(a) + Math.abs(t) + this.theme.size[0] + this.config.siblingSeparation, o = Math.abs(r) + Math.abs(e) + this.theme.size[1] + this.config.levelSeparation;
            this.initialViewBoxMatrix = [s, h, n, o]
        } else {
            var s = this.config.siblingSeparation / 2, h = this.config.levelSeparation / 2, n = this._aB / this.config.scale, o = this._svgGRootElement / this.config.scale;
            switch (this.config.orientation) {
                case getOrgChart.RO_TOP:
                case getOrgChart.RO_TOP_PARENT_LEFT:
                    this.initialViewBoxMatrix = [-s, h, n, o];
                    break;
                case getOrgChart.RO_BOTTOM:
                case getOrgChart.RO_BOTTOM_PARENT_LEFT:
                    this.initialViewBoxMatrix = [-s, -h - o, n, o];
                    break;
                case getOrgChart.RO_RIGHT:
                case getOrgChart.RO_RIGHT_PARENT_TOP:
                    this.initialViewBoxMatrix = [-n - h, -s, n, o];
                    break;
                case getOrgChart.RO_LEFT:
                case getOrgChart.RO_LEFT_PARENT_TOP:
                    this.initialViewBoxMatrix = [h, -s, n, o]
            }
        }
        return this.initialViewBoxMatrix.toString()
    }, getOrgChart.prototype.draw = function () {
        return this._A._aK(), this._A._t.innerHTML = this._a8(), this._A._aI(), this.config.searchable && (this._A._zd.style.display = "inherit", this._A._aW.style.display = "inherit", this._A._aL.style.display = "inherit"), this.config.zoomable && (this._A._zF.style.display = "inherit", this._A._zC.style.display = "inherit"), this.config.editable && (this._A._g.style.display = "inherit", this._A._zw.style.display = "inherit", this._A._a6.style.display = "inherit"), this.config.gridView && (this._A._8.style.display = "inherit"), this.config.printable && (this._A._rootNode.style.display = "inherit"), this.config.movable && (this._A._searchInput.style.display = "inherit", this._A._an.style.display = "inherit", this._A._l.style.display = "inherit", this._A._zp.style.display = "inherit"), getOrgChart._zl(this._A), getOrgChart._z(this._A._au, this.config.orientation), this._c(), this.showMainView(), this
    }, getOrgChart.prototype._aA = function (t, e) {
        switch (t) {
            case this._A._searchInput:
                this.move("right");
                break;
            case this._A._an:
                this.move("left");
                break;
            case this._A._l:
                this.move("up");
                break;
            case this._A._zp:
                this.move("down")
        }
    }, getOrgChart.prototype.move = function (t) {
        if (!this._aZ) {
            this._aZ = !0;
            var e = getOrgChart.util._5(this._A), i = e.slice(0), a = this.theme.size[0], r = this.theme.size[1];
            switch (t) {
                case"left":
                    i[0] -= a;
                    break;
                case"down":
                    i[1] -= r;
                    break;
                case"right":
                    i[0] += a;
                    break;
                case"up":
                    i[1] += r
            }
            var s = this;
            get._s(this._A._graphArea, {viewBox: e}, {viewBox: i}, 100, get._s._ar, function () {
                s._aZ = !1
            })
        }
    }, getOrgChart.prototype._c = function () {
        for (this.config.gridView && (this._a(this._A._8, "click", this._styledContainer), this._a(this._A._9, "click", this._height)), this.config.printable && this._a(this._A._rootNode, "click", this._a3), this.config.movable && (this._a(this._A._searchInput, "click", this._aA), this._a(this._A._an, "click", this._aA), this._a(this._A._l, "click", this._aA), this._a(this._A._zp, "click", this._aA)), this._a(window, "keydown", this._ay), i = 0; i < this._A._aY.length; i++)this._a(this._A._aY[i], "mouseup", this._zh);
        this._a(this._A._k, "click", this._height), this.config.editable && (this._a(this._A._g, "click", this._zh), this._a(this._A._zw, "click", this._zs), this._a(this._A._a6, "click", this._a7)), this.config.zoomable && (this._a(this._A._zC, "click", this._zR), this._a(this._A._zF, "click", this._zV), this._a(this._A._t, "DOMMouseScroll", this._zx), this._a(this._A._t, "mousewheel", this._zx), this._a(this._A._t, "mousemove", this._ap), this._a(this._A._t, "mousedown", this._al), this._a(this._A._t, "mouseup", this._aQ)), this.config.searchable && (this._a(this._A._aW, "click", this._aS), this._a(this._A._aL, "click", this._aP), this._a(this._A._zd, "keyup", this._zc), this._a(this._A._zd, "paste", this._zr))
    }, getOrgChart.prototype._a = function (t, e, i) {
        function a(t, e) {
            return function () {
                return e.apply(t, [this, arguments])
            }
        }

        function r(t) {
            var e = i.apply(this, arguments);
            return e === !1 && (t.stopPropagation(), t.preventDefault()), e
        }

        function s() {
            var e = i.call(t, window.event);
            return e === !1 && (window.event.returnValue = !1, window.event.cancelBubble = !0), e
        }

        t.getListenerList || (t.getListenerList = []), getOrgChart.util._e(t.getListenerList, e) || (i = a(this, i), t.addEventListener ? t.addEventListener(e, r, !1) : t.attachEvent("on" + e, s), t.getListenerList.push(e))
    }, getOrgChart.prototype._d = function (t, e) {
        this._Z || (this._Z = {}), this._Z[t] || (this._Z[t] = new Array), this._Z[t].push(e)
    }, getOrgChart.prototype._r = function () {
        this.config.removeEvent && this._d("removeEvent", this.config.removeEvent), this.config.updateEvent && this._d("updateEvent", this.config.updateEvent), this.config.clickEvent && this._d("clickEvent", this.config.clickEvent), this.config.renderBoxContentEvent && this._d("renderBoxContentEvent", this.config.renderBoxContentEvent)
    }, getOrgChart.prototype._X = function (t, e) {
        if (this._Z && this._Z[t]) {
            var i = !0;
            if (this._Z[t]) {
                var a;
                for (a = 0; a < this._Z[t].length; a++)this._Z[t][a](this, e) === !1 && (i = !1)
            }
            return i
        }
    }, getOrgChart._A = function (t) {
        this.element = t, this._graphAreaInvisible1
    }, getOrgChart._A.prototype._aK = function () {
        this._zA = this.element.getElementsByTagName("div")[0];
        var t = this._zA.children;
        this._zi = t[0], this._t = t[1], this._h = t[2], this._7 = t[3]
    }, getOrgChart._A.prototype._aI = function () {
        this._graphArea = this._t.getElementsByTagName("svg")[0], this._aU = this._graphArea.getElementsByTagName("g")[0], this._zk = this._zi.getElementsByTagName("div")[0];
        var t = this._zk.getElementsByTagName("div")[0], e = this._zk.getElementsByTagName("div")[1], i = this._zk.getElementsByTagName("div")[2];
        this._zd = t.getElementsByTagName("input")[0];
        var a = t.getElementsByTagName("a");
        this._aW = a[1], this._aL = a[0], this._zF = a[2], this._zC = a[3], this._searchInput = a[4], this._an = a[5], this._l = a[6], this._zp = a[7], this._g = a[8], this._8 = a[9], this._rootNode = a[10], this._u = this._h.getElementsByTagName("div")[0], this._m = this._h.getElementsByTagName("div")[1], this._aY = this._aU.getElementsByTagName("g"), a = e.getElementsByTagName("a"), this._k = a[0], this._a6 = a[1], this._zw = a[2], a = i.getElementsByTagName("a"), this._9 = a[0], this._zm = [], this._au = [];
        var s = this._graphArea.getElementsByTagName("text");
        for (r = 0; r < s.length; r++)"get-link-label" == s[r].className ? this._au.push(s[r]) : this._zm.push(s[r])
    }, getOrgChart._A.prototype._H = function () {
        return this._m.getElementsByTagName("input")[0]
    }, getOrgChart._A.prototype._B = function () {
        var t = this._m.getElementsByTagName("input"), e = {};
        for (i = 1; i < t.length; i++) {
            var a = t[i].value, r = t[i].parentNode.previousSibling.innerHTML;
            e[r] = a
        }
        return e
    }, getOrgChart._A.prototype._N = function () {
        return this._m.getElementsByTagName("input")
    }, getOrgChart._A.prototype._Y = function () {
        var t = this._m.getElementsByTagName("select");
        for (i = 0; i < t.length; i++)if ("get-oc-labels" == t[i].className)return t[i];
        return null
    }, getOrgChart._A.prototype._U = function () {
        var t = this._m.getElementsByTagName("select");
        for (i = 0; i < t.length; i++)if ("get-oc-select-parent" == t[i].className)return t[i];
        return null
    }, getOrgChart._A.prototype._J = function (t, e) {
        for (t = parseInt(t), e = parseInt(e), p = 0; p < this._aY.length; p++) {
            var i = getOrgChart.util._3(this._aY[p]), a = i[4], r = i[5];
            if (a == t && r == e)return this._aY[p]
        }
        return null
    }, getOrgChart.SCALE_FACTOR = 1.2, getOrgChart.INNER_HTML = '<div class="get-[theme] get-[color] get-org-chart"><div class="get-oc-tb"><div><div style="height:[toolbar-height]px;"><input placeholder="Search" type="text" /><a title="previous" class="get-prev get-disabled" href="javascript:void(0)">&nbsp;</a><a title="next" class="get-next get-disabled" href="javascript:void(0)">&nbsp;</a><a class="get-minus" title="zoom out" href="javascript:void(0)">&nbsp;</a><a class="get-plus" title="zoom in" href="javascript:void(0)">&nbsp;</a><a class="get-right" title="move right" href="javascript:void(0)">&nbsp;</a><a class="get-left" title="move left" href="javascript:void(0)">&nbsp;</a><a class="get-down" title="move down" href="javascript:void(0)">&nbsp;</a><a class="get-up" title="move up" href="javascript:void(0)">&nbsp;</a><a class="get-add" title="add contact" href="javascript:void(0)">&nbsp;</a><a href="javascript:void(0)" class="get-grid-view" title="grid view">&nbsp;</a><a href="javascript:void(0)" class="get-print" title="print">&nbsp;</a></div><div style="height:[toolbar-height]px;"><a title="previous page" class="get-prev-page" href="javascript:void(0)">&nbsp;</a><a title="delete" class="get-delete" href="javascript:void(0)">&nbsp;</a><a title="save" class="get-save get-disabled" href="javascript:void(0)">&nbsp;</a></div><div style="height:[toolbar-height]px;"><a title="previous page" class="get-prev-page" href="javascript:void(0)">&nbsp;</a></div></div></div><div class="get-oc-c" style="height:[height]px;"></div><div class="get-oc-v" style="height:[height]px;"><div class="get-image-pane"></div><div class="get-data-pane"></div></div><div class="get-oc-g" style="height:[height]px;"></div></div>', getOrgChart.DETAILS_VIEW_INPUT_HTML = '<div data-field-name="[label]"><div class="get-label">[label]</div><div class="get-data"><input value="[value]"/></div></div>', getOrgChart.DETAILS_VIEW_USER_LOGO = '<svg xmlns="http://www.w3.org/2003/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Layer_1" x="0px" y="0px" width="512px" height="512px" viewBox="0 0 640 640" enable-background="new 0 0 420 420" xml:space="preserve" xmlns:xml="http://www.w3.org/XML/1998/namespace" class="get-user-logo"><g><path class="get-user-logo" d="M258.744,293.214c70.895,0,128.365-57.472,128.365-128.366c0-70.896-57.473-128.367-128.365-128.367 c-70.896,0-128.368,57.472-128.368,128.367C130.377,235.742,187.848,293.214,258.744,293.214z"/><path d="M371.533,322.432H140.467c-77.577,0-140.466,62.909-140.466,140.487v12.601h512v-12.601   C512,385.341,449.112,322.432,371.533,322.432z"/></g></svg>', getOrgChart.DETAILS_VIEW_ID_INPUT = '<input value="[personId]" type="hidden">', getOrgChart.DETAILS_VIEW_ID_IMAGE = '<img src="[src]" width="420" />', getOrgChart.HIGHLIGHT_SCALE_FACTOR = 1.5, getOrgChart.MOVE_FACTOR = 2, getOrgChart.RO_TOP = 0, getOrgChart.RO_BOTTOM = 1, getOrgChart.RO_RIGHT = 2, getOrgChart.RO_LEFT = 3, getOrgChart.RO_TOP_PARENT_LEFT = 4, getOrgChart.RO_BOTTOM_PARENT_LEFT = 5, getOrgChart.RO_RIGHT_PARENT_TOP = 6, getOrgChart.RO_LEFT_PARENT_TOP = 7, getOrgChart.NJ_TOP = 0, getOrgChart.NJ_CENTER = 1, getOrgChart.NJ_BOTTOM = 2, getOrgChart.OPEN_SVG = '<svg xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="xMidYMid meet" version="1.1" viewBox="[viewBox]"><defs>[defs]</defs><g>', getOrgChart.CLOSE_SVG = "</svg>", getOrgChart.OPEN_GROUP = '<g class="get-level-[level] [boxCssClass]" title="click here to see more details" transform="matrix(1,0,0,1,[x],[y])">', getOrgChart.CLOSE_GROUP = "</g>", getOrgChart._R = function () {
        var t = "VML", e = (/msie 6\.0/i.test(navigator.userAgent), /Firefox/i.test(navigator.userAgent));
        return e && (t = "SVG"), "SVG"
    }, getOrgChart._E = function (t, e, i) {
        var a = null;
        if (e._zX = 0, e._zE = 0, e._aO = 0, e._ao = 0, e.level = i, e.leftNeighbor = null, e.rightNeighbor = null, t._searchArea(e, i), t._zg(e, i), t._zb(e, i), 0 == e._T() || i == t.config.maxDepth)a = e._O(), null != a ? e._aO = a._aO + t._1(a) + t.config.siblingSeparation : e._aO = 0; else {
            for (var r = e._T(), s = 0; r > s; s++) {
                var h = e._F(s);
                getOrgChart._E(t, h, i + 1)
            }
            var n = e._V(t);
            n -= t._1(e) / 2, a = e._O(), null != a ? (e._aO = a._aO + t._1(a) + t.config.siblingSeparation, e._ao = e._aO - n, getOrgChart._zS(t, e, i)) : t.config.orientation <= 3 ? e._aO = n : e._aO = 0
        }
    }, getOrgChart._zS = function (t, e, i) {
        for (var a = e._M(), r = a.leftNeighbor, s = 1, h = t.config.maxDepth - i; null != a && null != r && h >= s;) {
            for (var n = 0, o = 0, _ = a, g = r, l = 0; s > l; l++)_ = _._aE, g = g._aE, n += _._ao, o += g._ao;
            var c = r._aO + o + t._1(r) + t.config.subtreeSeparation - (a._aO + n);
            if (c > 0) {
                for (var d = e, p = 0; null != d && d != g; d = d._O())p++;
                if (null != d)for (var f = e, u = c / p; f != g; f = f._O())f._aO += c, f._ao += c, c -= u
            }
            s++, a = 0 == a._T() ? t._K(e, 0, s) : a._M(), null != a && (r = a.leftNeighbor)
        }
    }, getOrgChart._zf = function (t, e, i, a, r) {
        if (i <= t.config.maxDepth) {
            var s = t._za + e._aO + a, h = t._zz + r, n = 0, o = 0, _ = !1;
            switch (t.config.orientation) {
                case getOrgChart.RO_TOP:
                case getOrgChart.RO_TOP_PARENT_LEFT:
                case getOrgChart.RO_BOTTOM:
                case getOrgChart.RO_BOTTOM_PARENT_LEFT:
                    n = t._ai[i], o = e.h;
                    break;
                case getOrgChart.RO_RIGHT:
                case getOrgChart.RO_RIGHT_PARENT_TOP:
                case getOrgChart.RO_LEFT:
                case getOrgChart.RO_LEFT_PARENT_TOP:
                    n = t._ak[i], _ = !0, o = e.w
            }
            switch (t.config.nodeJustification) {
                case getOrgChart.NJ_TOP:
                    e._zX = s, e._zE = h;
                    break;
                case getOrgChart.NJ_CENTER:
                    e._zX = s, e._zE = h + (n - o) / 2;
                    break;
                case getOrgChart.NJ_BOTTOM:
                    e._zX = s, e._zE = h + n - o
            }
            if (_) {
                var g = e._zX;
                e._zX = e._zE, e._zE = g
            }
            switch (t.config.orientation) {
                case getOrgChart.RO_BOTTOM:
                case getOrgChart.RO_BOTTOM_PARENT_LEFT:
                    e._zE = -e._zE - o;
                    break;
                case getOrgChart.RO_RIGHT:
                case getOrgChart.RO_RIGHT_PARENT_TOP:
                    e._zX = -e._zX - o
            }
            0 != e._T() && getOrgChart._zf(t, e._M(), i + 1, a + e._ao, r + n + t.config.levelSeparation);
            var l = e._2();
            null != l && getOrgChart._zf(t, l, i, a, r)
        }
    }, getOrgChart._zl = function (t) {
        for (i = 0; i < t._zm.length; i++)for (var e = (t._zm[i].getAttribute("x"), t._zm[i].getAttribute("width")), a = t._zm[i].getComputedTextLength(); a > e;)t._zm[i].textContent = t._zm[i].textContent.substring(0, t._zm[i].textContent.length - 4), t._zm[i].textContent += "...", a = t._zm[i].getComputedTextLength()
    }, getOrgChart._z = function (t, e) {
        if (t && t.length > 0)for (h = 0; h < t.length; h++) {
            var i = t[h], a = parseInt(i.getAttribute("x")), r = i.getComputedTextLength();
            switch (e) {
                case getOrgChart.RO_TOP:
                case getOrgChart.RO_TOP_PARENT_LEFT:
                case getOrgChart.RO_BOTTOM:
                case getOrgChart.RO_BOTTOM_PARENT_LEFT:
                    var s = r, n = i.getAttribute("data-position").split(",")[0];
                    0 == n ? i.setAttribute("x", a - s - 6) : i.setAttribute("x", a + 6);
                    break;
                case getOrgChart.RO_RIGHT:
                case getOrgChart.RO_RIGHT_PARENT_TOP:
                    i.setAttribute("x", a + 7);
                    break;
                case getOrgChart.RO_LEFT:
                case getOrgChart.RO_LEFT_PARENT_TOP:
                    var s = r + 7;
                    i.setAttribute("x", a - s)
            }
            var o = i.getBBox(), _ = '<rect class="get-link-label-rect" width="[width]" height="[height]" x="[x]" y="[y]"  />';
            _ = _.replace("[width]", o.width + 6), _ = _.replace("[height]", o.height + 3), _ = _.replace("[x]", o.x - 3), _ = _.replace("[y]", o.y), i.insertAdjacentHTML("beforebegin", _)
        }
    }, getOrgChart.person = function (t, e, i, a, r) {
        this.id = t, this.pid = e, this.data = i, this.w = a[0], this.h = a[1], this._zX = 0, this._zE = 0, this._aO = 0, this._ao = 0, this.leftNeighbor = null, this.rightNeighbor = null, this._aE = null, this._aX = [], this.imageColumn = r
    }, getOrgChart.person.prototype.compareTo = function (t) {
        var e = this;
        return void 0 === e || void 0 === t || void 0 === e._zX || void 0 === e._zE || void 0 === t._zX || void 0 === t._zE ? !1 : e._zX == t._zX && e._zE == t._zE
    }, getOrgChart.person.prototype.getImageUrl = function () {
        return this.imageColumn && this.data[this.imageColumn] ? this.data[this.imageColumn] : null
    }, getOrgChart.person.prototype._L = function () {
        return -1 == this._aE.id ? 0 : this._aE._L() + 1
    }, getOrgChart.person.prototype._T = function () {
        return null == this._aX ? 0 : this._aX.length
    }, getOrgChart.person.prototype._O = function () {
        return null != this.leftNeighbor && this.leftNeighbor._aE == this._aE ? this.leftNeighbor : null
    }, getOrgChart.person.prototype._2 = function () {
        return null != this.rightNeighbor && this.rightNeighbor._aE == this._aE ? this.rightNeighbor : null
    }, getOrgChart.person.prototype._F = function (t) {
        return this._aX[t]
    }, getOrgChart.person.prototype._V = function (t) {
        return node = this._M(), node1 = this._I(), node._aO + (node1._aO - node._aO + t._1(node1)) / 2
    }, getOrgChart.person.prototype._M = function () {
        return this._F(0)
    }, getOrgChart.person.prototype._I = function () {
        return this._F(this._T() - 1)
    }, getOrgChart.person.prototype._p = function (t) {
        var e, i = [], a = 0, r = 0, s = 0, h = 0, n = 0, o = 0, _ = 0, g = 0, l = null;
        switch (t.config.orientation) {
            case getOrgChart.RO_TOP:
            case getOrgChart.RO_TOP_PARENT_LEFT:
                a = this._zX + this.w / 2, r = this._zE + this.h, e = -25;
                break;
            case getOrgChart.RO_BOTTOM:
            case getOrgChart.RO_BOTTOM_PARENT_LEFT:
                a = this._zX + this.w / 2, r = this._zE, e = 35;
                break;
            case getOrgChart.RO_RIGHT:
            case getOrgChart.RO_RIGHT_PARENT_TOP:
                a = this._zX, r = this._zE + this.h / 2, e = -10;
                break;
            case getOrgChart.RO_LEFT:
            case getOrgChart.RO_LEFT_PARENT_TOP:
                a = this._zX + this.w, r = this._zE + this.h / 2, e = -10
        }
        for (var c = 0; c < this._aX.length; c++) {
            switch (l = this._aX[c], t.config.orientation) {
                case getOrgChart.RO_TOP:
                case getOrgChart.RO_TOP_PARENT_LEFT:
                    switch (_ = n = l._zX + l.w / 2, g = l._zE, s = a, t.config.nodeJustification) {
                        case getOrgChart.NJ_TOP:
                            h = o = g - t.config.levelSeparation / 2;
                            break;
                        case getOrgChart.NJ_BOTTOM:
                            h = o = r + t.config.levelSeparation / 2;
                            break;
                        case getOrgChart.NJ_CENTER:
                            h = o = r + (g - r) / 2
                    }
                    break;
                case getOrgChart.RO_BOTTOM:
                case getOrgChart.RO_BOTTOM_PARENT_LEFT:
                    switch (_ = n = l._zX + l.w / 2, g = l._zE + l.h, s = a, t.config.nodeJustification) {
                        case getOrgChart.NJ_TOP:
                            h = o = g + t.config.levelSeparation / 2;
                            break;
                        case getOrgChart.NJ_BOTTOM:
                            h = o = r - t.config.levelSeparation / 2;
                            break;
                        case getOrgChart.NJ_CENTER:
                            h = o = g + (r - g) / 2
                    }
                    break;
                case getOrgChart.RO_RIGHT:
                case getOrgChart.RO_RIGHT_PARENT_TOP:
                    switch (_ = l._zX + l.w, g = o = l._zE + l.h / 2, h = r, t.config.nodeJustification) {
                        case getOrgChart.NJ_TOP:
                            s = n = _ + t.config.levelSeparation / 2;
                            break;
                        case getOrgChart.NJ_BOTTOM:
                            s = n = a - t.config.levelSeparation / 2;
                            break;
                        case getOrgChart.NJ_CENTER:
                            s = n = _ + (a - _) / 2
                    }
                    break;
                case getOrgChart.RO_LEFT:
                case getOrgChart.RO_LEFT_PARENT_TOP:
                    switch (_ = l._zX, g = o = l._zE + l.h / 2, h = r, t.config.nodeJustification) {
                        case getOrgChart.NJ_TOP:
                            s = n = _ - t.config.levelSeparation / 2;
                            break;
                        case getOrgChart.NJ_BOTTOM:
                            s = n = a + t.config.levelSeparation / 2;
                            break;
                        case getOrgChart.NJ_CENTER:
                            s = n = a + (_ - a) / 2
                    }
            }
            switch (t.render) {
                case"SVG":
                    switch (t.config.linkType) {
                        case"M":
                            i.push('<path class="link" style =" stroke: #686868;  fill: none; "  d="M' + a + "," + r + " " + s + "," + h + " " + n + "," + o + " L" + _ + "," + g + '"/>');
                            break;
                        case"B":
                            i.push('<path class="link" style =" stroke: #686868;  fill: none; "  d="M' + a + "," + r + " C" + s + "," + h + " " + n + "," + o + " " + _ + "," + g + '"/>')
                    }
                    var d = t._graphAreaInvisible2(l);
                    null != d && i.push('<text width="200" data-position="' + c + "," + this._L() + '" " class="get-link-label" x="' + _ + '" y="' + (g + e) + '">' + d.text + "</text>");
                    break;
                case"VML":
            }
        }
        return i.join("")
    }, !getOrgChart)var getOrgChart = {};
getOrgChart.themes = {

    monica: {
        size: [530, 220],
        toolbarHeight: 36,
        textPoints: [
            {x: 190, y: 50, width: 590}, //first name
            {x: 190, y: 90, width: 590}, //last name
            {x: 190, y: 190, width: 590}, //birth

        ],
        textPointsNoImage: [{x: 10, y: 200, width: 490}, {x: 10, y: 40, width: 490}, {x: 10, y: 65, width: 490}, {
            x: 10,
            y: 90,
            width: 490
        }, {x: 10, y: 115, width: 490}, {x: 10, y: 140, width: 490}],

        box: '<path class="get-box" style="fill: #A3DA7A;" d="M0 0 L530 0 L530 220 L0 220 Z"/>',
        text: '<text width="[width]" style="font-size: 30px; fill: #fff;" class="get-text get-text-[index]" x="[x]" y="[y]">[text]</text>',
        image: '<image preserveAspectRatio="xMidYMid slice" clip-path="url(#getMonicaClip)" xlink:href="[href]" x="20" y="10" height="200" width="150"/>'
    },

}, "undefined" == typeof get && (get = {}), get._s = function (t, e, a, r, s, h) {
    function n() {
        for (var n in a) {
            var d = getOrgChart.util._e(["top", "left", "right", "bottom"], n.toLowerCase()) ? "px" : "";
            switch (n.toLowerCase()) {
                case"d":
                    var p = s((l * _ - _) / r) * (a[n][0] - e[n][0]) + e[n][0], f = s((l * _ - _) / r) * (a[n][1] - e[n][1]) + e[n][1];
                    for (z = 0; z < t.length; z++)t[z].setAttribute("d", t[z].getAttribute("d") + " L" + p + " " + f);
                    break;
                case"transform":
                    if (a[n]) {
                        var u = e[n], x = a[n], O = [0, 0, 0, 0, 0, 0];
                        for (i in u)O[i] = s((l * _ - _) / r) * (x[i] - u[i]) + u[i];
                        for (z = 0; z < t.length; z++)t[z].setAttribute("transform", "matrix(" + O.toString() + ")")
                    }
                    break;
                case"viewbox":
                    if (a[n]) {
                        var u = e[n], x = a[n], O = [0, 0, 0, 0];
                        for (i in u)O[i] = s((l * _ - _) / r) * (x[i] - u[i]) + u[i];
                        for (z = 0; z < t.length; z++)t[z].setAttribute("viewBox", O.toString())
                    }
                    break;
                case"margin":
                    if (a[n]) {
                        var u = e[n], x = a[n], O = [0, 0, 0, 0];
                        for (i in u)O[i] = s((l * _ - _) / r) * (x[i] - u[i]) + u[i];
                        var y = "";
                        for (i = 0; i < O.length; i++)y += parseInt(O[i]) + "px ";
                        for (z = 0; z < t.length; z++)t[z] && t[z].style && (t[z].style[n] = v)
                    }
                    break;
                default:
                    var v = s((l * _ - _) / r) * (a[n] - e[n]) + e[n];
                    for (z = 0; z < t.length; z++)t[z] && t[z].style && (t[z].style[n] = v + d)
            }
        }
        l += g, l > c + 1 && (clearInterval(o), h && h())
    }

    var o, _ = 10, g = 1, l = 1, c = r / _ + 1;
    document.getElementsByTagName("g");
    t.length || (t = [t]), o = setInterval(n, _)
}, get._s._af = function (t) {
    var e = 2;
    return 0 > t ? 0 : t > 1 ? 1 : Math.pow(t, e)
}, get._s._aF = function (t) {
    var e = 2;
    if (0 > t)return 0;
    if (t > 1)return 1;
    var i = e % 2 == 0 ? -1 : 1;
    return i * (Math.pow(t - 1, e) + i)
}, get._s._ac = function (t) {
    var e = 2;
    if (0 > t)return 0;
    if (t > 1)return 1;
    if (t *= 2, 1 > t)return get._s._af(t, e) / 2;
    var i = e % 2 == 0 ? -1 : 1;
    return i / 2 * (Math.pow(t - 2, e) + 2 * i)
}, get._s._av = function (t) {
    return 0 > t ? 0 : t > 1 ? 1 : -Math.cos(t * (Math.PI / 2)) + 1
}, get._s._areaHeight = function (t) {
    return 0 > t ? 0 : t > 1 ? 1 : Math.sin(t * (Math.PI / 2))
}, get._s._ar = function (t) {
    return 0 > t ? 0 : t > 1 ? 1 : -.5 * (Math.cos(Math.PI * t) - 1)
}, get._s._aw = function (t) {
    return 0 > t ? 0 : t > 1 ? 1 : Math.pow(2, 10 * (t - 1))
}, get._s._aR = function (t) {
    return 0 > t ? 0 : t > 1 ? 1 : -Math.pow(2, -10 * t) + 1
}, get._s._ad = function (t) {
    return 0 > t ? 0 : t > 1 ? 1 : .5 > t ? .5 * Math.pow(2, 10 * (2 * t - 1)) : .5 * (-Math.pow(2, 10 * (-2 * t + 1)) + 2)
}, get._s._az = function (t) {
    return 0 > t ? 0 : t > 1 ? 1 : -(Math.sqrt(1 - t * t) - 1)
}, get._s._aC = function (t) {
    return 0 > t ? 0 : t > 1 ? 1 : Math.sqrt(1 - (t - 1) * (t - 1))
}, get._s._ae = function (t) {
    return 0 > t ? 0 : t > 1 ? 1 : 1 > t ? -.5 * (Math.sqrt(1 - t * t) - 1) : .5 * (Math.sqrt(1 - (2 * t - 2) * (2 * t - 2)) + 1)
}, get._s._a4 = function (t) {
    return 0 > t ? 0 : t > 1 ? 1 : 1 / 2.75 > t ? 1 - 7.5625 * t * t : 2 / 2.75 > t ? 1 - (7.5625 * (t - 1.5 / 2.75) * (t - 1.5 / 2.75) + .75) : 2.5 / 2.75 > t ? 1 - (7.5625 * (t - 2.25 / 2.75) * (t - 2.25 / 2.75) + .9375) : 1 - (7.5625 * (t - 2.625 / 2.75) * (t - 2.625 / 2.75) + .984375)
}, get._s._aa = function (t) {
    return 0 > t ? 0 : t > 1 ? 1 : t * t * (2.70158 * t - 1.70158)
}, get._s._aD = function (t) {
    return 0 > t ? 0 : t > 1 ? 1 : (t - 1) * (t - 1) * (2.70158 * (t - 1) + 1.70158) + 1
}, get._s._ax = function (t) {
    return 0 > t ? 0 : t > 1 ? 1 : .5 > t ? .5 * (4 * t * t * (7.1898 * t - 2.5949)) : .5 * ((2 * t - 2) * (2 * t - 2) * (3.5949 * (2 * t - 2) + 2.5949) + 2)
}, get._s._aq = function (t) {
    var e = 2, i = e * t;
    return i * Math.exp(1 - i)
}, get._s._W = function (t) {
    var e = 2, i = 2;
    return Math.exp(-e * Math.pow(t, i))
}, "undefined" == typeof get && (get = {}), get._svgElement = function () {
    if (getOrgChart._svgElement)return getOrgChart._svgElement;
    var t = navigator.userAgent;
    t = t.toLowerCase();
    var e = /(webkit)[ \/]([\w.]+)/, i = /(opera)(?:.*version)?[ \/]([\w.]+)/, a = /(msie) ([\w.]+)/, r = /(mozilla)(?:.*? rv:([\w.]+))?/, s = e.exec(t) || i.exec(t) || a.exec(t) || t.indexOf("compatible") < 0 && r.exec(t) || [], h = {
        browser: s[1] || "",
        version: s[2] || "0"
    };
    return getOrgChart._svgElement = {
        msie: "msie" == h.browser,
        webkit: "webkit" == h.browser,
        mozilla: "mozilla" == h.browser,
        opera: "opera" == h.browser
    }, getOrgChart._svgElement
}, getOrgChart.prototype._ay = function (t, e) {
    var i = e[0];
    switch (i.keyCode) {
        case 37:
            this.move("left");
            break;
        case 38:
            this.move("down");
            break;
        case 39:
            this.move("right");
            break;
        case 40:
            this.move("up");
            break;
        case 107:
            this.zoom(1, !0);
            break;
        case 109:
            this.zoom(-1, !0)
    }
}, getOrgChart.util = {}, getOrgChart.util._5 = function (_A) {
    var viewBox = _A._graphArea.getAttribute("viewBox");
    return viewBox = "[" + viewBox + "]", eval(viewBox.replace(/\ /g, ", "))
}, getOrgChart.util._3 = function (element) {
    var transform = element.getAttribute("transform");
    return transform = transform.replace("matrix", "").replace("(", "").replace(")", ""), transform = getOrgChart.util._zo(transform), transform = "[" + transform + "]", eval(transform.replace(/\ /g, ", "))
}, getOrgChart.util._G = function (t, e, a) {
    for (i = 0; i < a.length; i++)if (parseInt(a[i]._zX) == t && parseInt(a[i]._zE) == e)return a[i];
    return null
}, getOrgChart.util._zo = function (t) {
    return t.replace(/^\s+|\s+$/g, "")
}, getOrgChart.util._e = function (t, e) {
    for (var i = t.length; i--;)if (t[i] === e)return !0;
    return !1
}, getOrgChart.util._D = function () {
    var t = function () {
        return Math.floor(65536 * (1 + Math.random())).toString(16).substring(1)
    };
    return t() + t() + "-" + t() + "-" + t() + "-" + t() + "-" + t() + t() + t()
}, getOrgChart.util._4 = function (t) {
    for (var e, i = window.location.href.slice(window.location.href.indexOf("?") + 1).split("&"), a = 0; a < i.length; a++)if (e = i[a].split("="), e && 2 == e.length && e[0] === t) {
        for (var r, s, h = /(%[^%]{2})/; null != (encodedChar = h.exec(e[1])) && encodedChar.length > 1 && "" != encodedChar[1];)r = parseInt(encodedChar[1].substr(1), 16), s = String.fromCharCode(r), e[1] = e[1].replace(encodedChar[1], s);
        return decodeURIComponent(escape(e[1]))
    }
    return null
}, getOrgChart.util._width = function (t) {
    if (window.ActiveXObject) {
        var e = new ActiveXObject("Microsoft.XMLDOM");
        e.async = "false", e.loadXML(t)
    } else var i = new DOMParser, e = i.parseFromString(t, "text/xml");
    return e
}, getOrgChart.util._ab = function (t) {
    return null == t || "undefined" == typeof t || "" == t || -1 == t ? !0 : !1
}, getOrgChart.util._at = function (t) {
    return "undefined" != typeof t && null !== t
}, getOrgChart.prototype.showDetailsView = function (t) {
    var e;
    for (i = 0; i < this._aN.length; i++)this._aN[i].id == t && (e = this._aN[i]);
    this._zy(e)
}, getOrgChart.prototype._zh = function (t, e) {
    var i = 7;
    if (!(this._areaWidth._q > i)) {
        var a;
        if ("a" != t.nodeName.toLowerCase()) {
            var r = getOrgChart.util._3(t), s = r[4], h = r[5];
            a = getOrgChart.util._G(s, h, this._aN);
            var n = this._X("clickEvent", {id: a.id, parentId: a.pid, data: a.data});
            if (!n)return
        }
        this._zy(a)
    }
}, getOrgChart.prototype._zy = function (t) {
    var e = !1, a = "undefined" == typeof t;
    a === !1 && (e = t._aE == this._zq);
    var r = function (a, r, s) {
        for (var h = e ? 'style="display:none;"' : "", n = "<select " + h + 'class="get-oc-select-parent"><option value="' + a + '">--select parent--</option>', o = null, _ = 0; _ < r.length; _++)if (o = r[_], t != o) {
            var g = "";
            for (i = 0; i < s.length; i++) {
                var l = s[i];
                o.data && o.data[l] && (g ? g = g + ", " + o.data[l] : g += o.data[l])
            }
            n += o.id == a ? '<option selected="selected" value="' + o.id + '">' + g + "</option>" : '<option value="' + o.id + '">' + g + "</option>"
        }
        return n += "</select>"
    }, s = function (t, e) {
        var a, r = '<select class="get-oc-labels"><option value="">--other--</option>';
        for (i = 0; i < e.length; i++)getOrgChart.util._e(t, e[i]) || (a += '<option value="' + e[i] + '">' + e[i] + "</option>");
        return r += a, r += "</select>", a || (r = ""), r
    }, h = "", n = [];
    if (a === !0) {
        for (t = {}, t.data = {}, i = 0; i < this._aH.length; i++)t.data[this._aH[i]] = "";
        t.id = "", t.pid = ""
    }
    h += r(t.pid, this._aN, this.config.primaryColumns), h += getOrgChart.DETAILS_VIEW_ID_INPUT.replace("[personId]", t.id);
    for (label in t.data)h += getOrgChart.DETAILS_VIEW_INPUT_HTML.replace(/\[label]/g, label).replace("[value]", t.data[label]), n.push(label);
    h += s(n, this._aH), this._A._m.innerHTML = h;
    var o = t.getImageUrl ? t.getImageUrl() : "";
    o ? this._A._u.innerHTML = getOrgChart.DETAILS_VIEW_ID_IMAGE.replace("[src]", o) : this._A._u.innerHTML = getOrgChart.DETAILS_VIEW_USER_LOGO, this._i(), e || a ? this._A._a6.className = "get-delete get-disabled" : this._A._a6.className = "get-delete";
    var _ = this.config.customize[t.id] && this.config.customize[t.id].theme ? getOrgChart.themes[this.config.customize[t.id].theme].toolbarHeight : this.theme.toolbarHeight;
    this._A._t.style.top = "-9999px", this._A._t.style.left = "-9999px", this._A._h.style.top = _ + "px", this._A._h.style.left = "0px", this._A._7.style.top = "-9999px", this._A._7.style.left = "-9999px", this._A._7.innerHTML = "", this._A._m.style.opacity = 0, this._A._u.style.opacity = 0, get._s(this._A._u, {
        left: -100,
        opacity: 0
    }, {
        left: 20,
        opacity: 1
    }, 200, get._s._aR), get._s(this._A._zk, {top: 0}, {top: -_}, 200, get._s._areaHeight), get._s(this._A._m, {opacity: 0}, {opacity: 1}, 400, get._s._aR)
}, getOrgChart.prototype._i = function () {
    var t = this._A._N();
    for (n = 0; n < t.length; n++)this._a(t[n], "keypress", this._j), this._a(t[n], "paste", this._j);
    this._A._U() && this._a(this._A._U(), "change", this._j), this._A._Y() && this._a(this._A._Y(), "change", this._n)
}, getOrgChart.prototype._j = function (t, e) {
    this._A._zw.className = this._A._zw.className.replace("get-disabled", "")
}, getOrgChart.prototype._n = function (t, e) {
    for (var i = this._A._B(), a = this._A._Y(), r = a.value, s = 0; s < a.options.length; s++)r == a.options[s].value && (a.options[s] = null);
    if (r) {
        var h = this._A._m.innerHTML, n = getOrgChart.DETAILS_VIEW_INPUT_HTML.replace(/\[label]/g, r).replace("[value]", ""), o = h.indexOf('<select class="get-oc-labels">');
        this._A._m.innerHTML = h.substring(0, o) + n + h.substring(o, h.length);
        var _ = this._A._N(), g = 1;
        for (s in i)_[g].value = i[s], g++;
        this._i()
    }
}, getOrgChart.prototype._zs = function (t, e) {
    if (-1 == this._A._zw.className.indexOf("get-disabled")) {
        var i, a = this._A._H().value;
        this._A._U() && this._A._U().value && (i = this._A._U().value);
        var r = this._A._B();
        this.updatePerson(a, i, r), this._A._zw.className = this._A._zw.className + "get-disabled", this.showMainView()
    }
}, getOrgChart.prototype._a7 = function (t, e) {
    if (-1 == this._A._a6.className.indexOf("get-disabled")) {
        var i = this._A._H().value;
        this.removePerson(i), this.showMainView()
    }
}, getOrgChart.prototype._styledContainer = function () {
    this.showGridView()
}, getOrgChart.prototype.showGridView = function () {
    var t = '<table cellpadding="0" cellspacing="0" border="0">';
    for (t += "<tr>", t += "<th>ID</th><th>Parent ID</th>", i = 0; i < this._aH.length; i++) {
        var e = this._aH[i];
        t += "<th>" + e + "</th>"
    }
    for (t += "</tr>", i = 0; i < this._aN.length; i++) {
        var a = i % 2 == 0 ? "get-even" : "get-odd", r = this._aN[i].data;
        for (t += '<tr class="' + a + '">', t += "<td>" + this._aN[i].id + "</td>", t += "<td>" + this._aN[i].pid + "</td>", j = 0; j < this._aH.length; j++) {
            var e = this._aH[j], s = r[e];
            t += "<td>", t += s ? s : "&nbsp;", t += "</td>"
        }
        t += "</tr>"
    }
    t += "</table>", this._A._7.innerHTML = t, this._A._t.style.top = "-9999px", this._A._t.style.left = "-9999px", this._A._h.style.top = "-9999px", this._A._h.style.left = "-9999px", this._A._7.style.top = this.theme.toolbarHeight + "px", this._A._7.style.left = "0px", get._s(this._A._7, {
        left: 100,
        opacity: 0
    }, {
        left: 0,
        opacity: 1
    }, 200, get._s._aR), get._s(this._A._zk, {top: 0}, {top: 2 * -this.theme.toolbarHeight}, 200, get._s._areaHeight)
}, getOrgChart.prototype._height = function (t, e) {
    this.showMainView()
}, getOrgChart.prototype.showMainView = function () {
    this._A._t.style.top = this.theme.toolbarHeight + "px", this._A._t.style.left = "0px", this._A._h.style.top = "-9999px", this._A._h.style.left = "-9999px", this._A._7.style.top = "-9999px", this._A._7.style.left = "-9999px", this._A._7.innerHTML = "", this.config.searchable && this._A._zd.focus(), this._A._graphArea.style.opacity = 0, get._s(this._A._graphArea, {opacity: 0}, {opacity: 1}, 200, get._s._av), 0 != this._A._zk.style.top && "" != this._A._zk.style.top && get._s(this._A._zk, {top: -46}, {top: 0}, 200, get._s._areaHeight)
}, getOrgChart.prototype._a3 = function (t, e) {
    this.print()
}, getOrgChart.prototype.print = function () {
    var t, e = this, i = this._A.element, a = this._A._zi, r = [], s = i.parentNode, h = a.style.display, n = document.body, o = n.childNodes;
    if (!e._ag) {
        for (e._ag = !0, t = 0; t < o.length; t++) {
            var _ = o[t];
            1 === _.nodeType && (r[t] = _.style.display, _.style.display = "none")
        }
        a.style.display = "none", n.appendChild(i), window.focus(), window.print(), setTimeout(function () {
            for (s.appendChild(i), t = 0; t < o.length; t++) {
                var n = o[t];
                1 === n.nodeType && (n.style.display = r[t])
            }
            a.style.display = h, e._ag = !1
        }, 1e3)
    }
}, getOrgChart.prototype._zR = function () {
    this.zoom(1, !0)
}, getOrgChart.prototype._zV = function () {
    this.zoom(-1, !0)
}, getOrgChart.prototype._zx = function (t, e) {
    this._A._graphAreaInvisible1 = void 0;
    var i = e[0].wheelDelta ? e[0].wheelDelta / 40 : e[0].detail ? -e[0].detail : 0;
    return i && this.zoom(i, !1), e[0].preventDefault() && !1
}, getOrgChart.prototype._ap = function (t, e) {
    if (this._A._graphAreaInvisible1 = void 0, this._areaWidth.mouseLastX = e[0].pageX - this._A._t.offsetLeft, this._areaWidth.mouseLastY = e[0].pageY - this._A._t.offsetTop, this._areaWidth.dragged = !0, this._areaWidth.dragStart) {
        var i = Math.abs(this._areaWidth.dragStart.x - this._areaWidth.mouseLastX), a = Math.abs(this._areaWidth.dragStart.y - this._areaWidth.mouseLastY);
        this._areaWidth._q = i + a, this._A._t.style.cursor = "move";
        var r = getOrgChart.util._5(this._A), s = r[2] / this._aB, h = r[3] / this._svgGRootElement, n = s > h ? s : h;
        r[0] = -((this._areaWidth.mouseLastX - this._areaWidth.dragStart.x) * n) + this._areaWidth.dragStart.viewBoxLeft, r[1] = -((this._areaWidth.mouseLastY - this._areaWidth.dragStart.y) * n) + this._areaWidth.dragStart.viewBoxTop, r[0] = parseInt(r[0]), r[1] = parseInt(r[1]), this._A._graphArea.setAttribute("viewBox", r.toString())
    }
}, getOrgChart.prototype._al = function (t, e) {
    document.body.style.mozUserSelect = document.body.style.webkitUserSelect = document.body.style.userSelect = "none", this._areaWidth.mouseLastX = e[0].pageX - this._A._t.offsetLeft, this._areaWidth.mouseLastY = e[0].pageY - this._A._t.offsetTop;
    var i = getOrgChart.util._5(this._A);
    this._areaWidth.dragStart = {
        x: this._areaWidth.mouseLastX,
        y: this._areaWidth.mouseLastY,
        viewBoxLeft: i[0],
        viewBoxTop: i[1]
    }, this._areaWidth.dragged = !1, this._areaWidth._q = 0
}, getOrgChart.prototype._aQ = function (t, e) {
    this._areaWidth.dragStart = null, this._A._t.style.cursor = "default"
}, getOrgChart.prototype.zoom = function (t, e) {
    if (this._zD)return !1;
    this._zD = !0;
    var i = this, a = getOrgChart.util._5(this._A), r = a.slice(0), s = a[2], h = a[3];
    return t > 0 ? (a[2] = a[2] / (1.2 * getOrgChart.SCALE_FACTOR), a[3] = a[3] / (1.2 * getOrgChart.SCALE_FACTOR)) : (a[2] = a[2] * (1.2 * getOrgChart.SCALE_FACTOR), a[3] = a[3] * (1.2 * getOrgChart.SCALE_FACTOR)), a[0] = a[0] - (a[2] - s) / 2, a[1] = a[1] - (a[3] - h) / 2, e ? get._s(this._A._graphArea, {viewBox: r}, {viewBox: a}, 500, get._s._aD, function () {
        i._zD = !1
    }) : (this._A._graphArea.setAttribute("viewBox", a.toString()), this._zD = !1), !1
}, getOrgChart.prototype._aS = function (t, e) {
    if (t.className.indexOf("get-disabled") > -1)return !1;
    var i = this;
    clearTimeout(this._ze.timer), this._ze.timer = setTimeout(function () {
        i._ah("next")
    }, 100)
}, getOrgChart.prototype._aP = function (t, e) {
    if (t.className.indexOf("get-disabled") > -1)return !1;
    var i = this;
    clearTimeout(this._ze.timer), this._ze.timer = setTimeout(function () {
        i._ah("prev")
    }, 100)
}, getOrgChart.prototype._zc = function (t, e) {
    var i = this;
    clearTimeout(this._ze.timer), this._ze.timer = setTimeout(function () {
        i._ah()
    }, 500)
}, getOrgChart.prototype._zr = function (t, e) {
    var i = this;
    clearTimeout(this._ze.timer), this._ze.timer = setTimeout(function () {
        i._ah()
    }, 100)
}, getOrgChart.prototype._ah = function (t) {
    var e = this.initialViewBoxMatrix, i = getOrgChart.util._5(this._A), a = i.slice(0);
    if (t)"next" == t ? this._ze.showIndex++ : this._ze.showIndex--; else {
        if (!this._A._zd.value)return this._ze.oldValue = void 0, this._ze.found = [], this._A._graphAreaInvisible1 = void 0, get._s(this._A._graphArea, {viewBox: a}, {viewBox: e}, 200, get._s._areaHeight), void this._o();
        if (this._ze.oldValue == this._A._zd.value)return;
        this._ze.oldValue = this._A._zd.value, this._ze.found = this._S(this._A._zd.value), this._ze.showIndex = 0
    }
    if (this._o(), this._ze.found && this._ze.found.length && !this._ze.found[this._ze.showIndex].node.compareTo(this._A._graphAreaInvisible1)) {
        var r = this._aB / 2, s = this._svgGRootElement / 2, h = this.theme.size[0] / 2, n = this.theme.size[1] / 2;
        if (this._A._graphAreaInvisible1 = this._ze.found[this._ze.showIndex].node, this._ze.found.length) {
            var o = this._ze.found[this._ze.showIndex].node._zX, _ = this._ze.found[this._ze.showIndex].node._zE;
            i[0] = o - (r - h), i[1] = _ - (s - n), i[2] = this._aB, i[3] = this._svgGRootElement;
            var g = this._A._J(o, _), l = g.parentNode;
            l.removeChild(g), l.appendChild(g);
            var c = getOrgChart.util._3(g), d = c.slice(0);
            d[0] = getOrgChart.HIGHLIGHT_SCALE_FACTOR, d[3] = getOrgChart.HIGHLIGHT_SCALE_FACTOR, d[4] = d[4] - this.theme.size[0] / 2 * (getOrgChart.HIGHLIGHT_SCALE_FACTOR - 1), d[5] = d[5] - this.theme.size[1] / 2 * (getOrgChart.HIGHLIGHT_SCALE_FACTOR - 1), get._s(this._A._graphArea, {viewBox: a}, {viewBox: i}, 150, get._s._areaHeight, function () {
                get._s(g, {transform: c}, {transform: d}, 200, get._s._aC, function () {
                    get._s(g, {transform: d}, {transform: c}, 200, get._s._az)
                })
            })
        }
    }
}, getOrgChart.prototype._o = function () {
    this._ze.showIndex < this._ze.found.length - 1 && 0 != this._ze.found.length ? this._A._aW.className = this._A._aW.className.replace(" get-disabled", "") : -1 == this._A._aW.className.indexOf(" get-disabled") && (this._A._aW.className = this._A._aW.className + " get-disabled"), 0 != this._ze.showIndex && 0 != this._ze.found.length ? this._A._aL.className = this._A._aL.className.replace(" get-disabled", "") : -1 == this._A._aL.className.indexOf(" get-disabled") && (this._A._aL.className = this._A._aL.className + " get-disabled")
}, getOrgChart.prototype._S = function (t) {
    function e(t, e) {
        return t.indexOf < e.indexOf ? -1 : t.indexOf > e.indexOf ? 1 : 0
    }

    var i = [];
    for (t.toLowerCase && (t = t.toLowerCase()), n = 0; n < this._aN.length; n++)for (m in this._aN[n].data)if (m != this.config.imageColumn) {
        var a = -1;
        if (getOrgChart.util._at(this._aN[n]) && getOrgChart.util._at(this._aN[n].data[m])) {
            var r = this._aN[n].data[m].toString().toLowerCase();
            a = r.indexOf(t)
        }
        if (a > -1) {
            i.push({indexOf: a, node: this._aN[n]});
            break
        }
    }
    return i.sort(e), i
}, getOrgChart.prototype.removePerson = function (t) {
    var e = this._X("removeEvent", {id: t});
    e && (this._a5(t), this._y = getOrgChart.util._5(this._A), this.draw(), this._A._graphArea.style.opacity = 0)
}, getOrgChart.prototype.updatePerson = function (e, i, a) {
    var r = this._X("updateEvent", {id: e, parentId: i, data: a});
    if (r) {
        if ("" == e)e = getOrgChart.util._D(), this.createPerson(e, i, a); else for (t = this._aN.length - 1; t >= 0; t--)if (this._aN[t].id == e) {
            null == this._aN[t].pid || "undefined" == typeof this._aN[t].pid || "" == this._aN[t].pid ? this._aN[t].data = a : this._aN[t].pid == i ? this._aN[t].data = a : (this._a5(e), this.createPerson(e, i, a));
            break
        }
        this._y = getOrgChart.util._5(this._A), this.draw()
    }
}, getOrgChart.prototype.createPerson = function (t, e, i) {
    var a = null;
    if (getOrgChart.util._ab(e))a = this._zq; else for (var r = 0; r < this._aN.length; r++)if (this._aN[r].id == e) {
        a = this._aN[r];
        break
    }
    var s = this.config.customize[t] && this.config.customize[t].theme ? getOrgChart.themes[this.config.customize[t].theme] : this.theme, h = new getOrgChart.person(t, e, i, s.size, this.config.imageColumn);
    h._aE = a;
    var n = this._aN.length;
    this._aN[n] = h;
    var o = a._aX.length;
    a._aX[o] = h;
    for (label in h.data)getOrgChart.util._e(this._aH, label) || this._aH.push(label);
    return this
}, getOrgChart.prototype._a5 = function (t) {
    var e = this._aN.slice(0);
    for (this._aN = [], i = e.length - 1; i >= 0; i--)if (e[i].id == t) {
        var a = e[i];
        for (j = 0; j < a._aX.length; j++)a._aX[j].pid = a._aE.id;
        e.splice(i, 1);
        break
    }
    for (this._zz = 0, this._za = 0, this._ai = [], this._ak = [], this._a1 = [], this._zq = new getOrgChart.person(-1, null, null, 2, 2), i = 0; i < e.length; i++)this.createPerson(e[i].id, e[i].pid, e[i].data)
}, getOrgChart.prototype.load = function () {
    var t = this.config.dataSource;
    t && (t.constructor && t.constructor.toString().indexOf("HTML") > -1 ? this.loadFromHTMLTable(t) : "string" == typeof t ? this.loadFromXML(t) : this.loadFromJSON(t))
}, getOrgChart.prototype.loadFromJSON = function (t) {
    this._aN = [], this._zz = 0, this._za = 0, this._ai = [], this._ak = [], this._a1 = [], this._zq = new getOrgChart.person(-1, null, null, 2, 2);
    for (var e = 0; e < t.length; e++) {
        var i = t[e], a = i[Object.keys(i)[0]], r = i[Object.keys(i)[1]];
        delete i[Object.keys(i)[0]], delete i[Object.keys(i)[0]], this.createPerson(a, r, i)
    }
    this.draw()
}, getOrgChart.prototype.loadFromHTMLTable = function (t) {
    for (var e = t.rows[0], i = 1; i < t.rows.length; i++) {
        for (var a = t.rows[i], r = a.cells[0].innerHTML, s = a.cells[1].innerHTML, h = {}, n = 2; n < a.cells.length; n++) {
            var o = a.cells[n];
            h[e.cells[n].innerHTML] = o.innerHTML
        }
        this.createPerson(r, s, h)
    }
    this.draw()
}, getOrgChart.prototype.loadFromXML = function (t) {
    var e = this;
    get._w._C(t, null, function (t) {
        e._am = 0, e._aj(t, null, !0), e.draw()
    }, "xml")
}, getOrgChart.prototype.loadFromXMLDocument = function (t) {
    var e = getOrgChart.util._width(t);
    this._am = 0, this._aj(e, null, !0), this.draw()
}, getOrgChart.prototype._aj = function (t, e, i) {
    var a = this;
    if (1 == t.nodeType && t.attributes.length > 0) {
        for (var r = {}, s = 0; s < t.attributes.length; s++) {
            var h = t.attributes.item(s);
            r[h.nodeName] = h.nodeValue
        }
        a._am++, a.createPerson(a._am, e, r), i && (i = !1)
    }
    if (t.hasChildNodes()) {
        i || (e = a._am);
        for (var n = 0; n < t.childNodes.length; n++) {
            var o = t.childNodes.item(n);
            o.nodeName;
            3 != o.nodeType && this._aj(o, e, i)
        }
    }
}, "undefined" == typeof get && (get = {}), get._w = {}, get._w._zS = function () {
    return window.XMLHttpRequest ? new XMLHttpRequest : new ActiveXObject("Microsoft.XMLHTTP")
}, get._w._zv = function (t, e, i, a, r, s) {
    var h = get._w._zS();
    h.open(i, t, s), h.onreadystatechange = function () {
        if (get._svgElement().msie || "xml" != a || 4 != h.readyState)if (get._svgElement().msie && "xml" == a && 4 == h.readyState)try {
            var t = new DOMParser, i = t.parseFromString(h.responseText, "text/xml");
            e(i)
        } catch (r) {
            var i = new ActiveXObject("Microsoft.XMLDOM");
            i.loadXML(h.responseText), e(i)
        } else 4 == h.readyState && e(h.responseText); else e(h.responseXML)
    }, "POST" == i && h.setRequestHeader("Content-type", "application/x-www-form-urlencoded"), h.send(r)
}, get._w._C = function (t, e, i, a, r) {
    var s = [];
    for (var h in e)s.push(encodeURIComponent(h) + "=" + encodeURIComponent(e[h]));
    get._w._zv(t + "?" + s.join("&"), i, "GET", a, null, r)
}, get._w._aM = function (t, e, i, a, r) {
    var s = [];
    for (var h in e)s.push(encodeURIComponent(h) + "=" + encodeURIComponent(e[h]));
    get._w._zv(t, i, "POST", a, s.join("&"), r)
}, function (t) {
    t.fn.getOrgChart = function (e) {
        var i, a, r = arguments.length > 1 || 1 == arguments.length && "string" == typeof arguments[0];
        return r && (i = Array.prototype.slice.call(arguments, 1), a = arguments[0]), this.each(function () {
            var s = t(this).data("getOrgChart");
            if (s && r)s[a] && s[a].apply(s, i); else {
                var h = new getOrgChart(this, e), n = this;
                h._d("removeEvent", function (e, i) {
                    return t(n).trigger("removeEvent", [e, i]), i.returnValue === !1 ? !1 : void 0
                }), h._d("updateEvent", function (e, i) {
                    return t(n).trigger("updateEvent", [e, i]), i.returnValue === !1 ? !1 : void 0
                }), h._d("clickEvent", function (e, i) {
                    return t(n).trigger("clickEvent", [e, i]), i.returnValue === !1 ? !1 : void 0
                }), h._d("renderBoxContentEvent", function (e, i) {
                    t(n).trigger("renderBoxContentEvent", [e, i])
                }), t(this).data("getOrgChart", h)
            }
        })
    }
}(jQuery);
