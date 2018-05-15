//debugger;
getOrgChartW = '';

/**
 * getOrgChart constructor.
 *
 * @param {Element} containerElement
 * @param {Object} config
 */
getOrgChart = function (containerElement, config) {
    // Default config values.
    this.config = {
        theme: "ula",
        color: "blue",
        enableEdit: true,
        enableZoom: true,
        enableSearch: true,
        enableMove: true,
        enableGridView: false,
        enableDetailsView: true,
        enablePrint: false,
        scale: "auto",
        linkType: "M",
        orientation: getOrgChart.RO_TOP,
        nodeJustification: getOrgChart.NJ_TOP,
        primaryFields: ["Name", "Title"],
        photoFields: ["Image"],
        idField: null,
        parentIdField: null,
        levelSeparation: 100,
        siblingSeparation: 30,
        subtreeSeparation: 40,
        removeNodeEvent: "",
        updateNodeEvent: "",
        updatedEvent: "",
        insertNodeEvent: "",
        createNodeEvent: "",
        clickNodeEvent: "",
        renderNodeEvent: "",
        embededDefinitions: "",
        maxDepth: 30,
        dataSource: null,
        dTreeDataSource: null,
        customize: [],
        expandToLevel: 3,
        flatNodes: null
    };

    var color = getOrgChart.util.parseParamFromUri("colorScheme");
    if (color) {
        this.config.color = color
    }

    // Parse input config.
    if (config) {
        for (var c in this.config) {
            if (typeof(config[c]) != "undefined") {
                this.config[c] = config[c];
            }
        }
    }

    this._initEvents();
    this.version = "2.0.9";
    this.theme = getOrgChart.themes[this.config.theme];
    this.element = containerElement;
    this.nodes = {};
    this.flatNodes = {};
    this._ab = [];
    this._ay = [];
    this._aI = [];
    this._a4 = 0;
    this._a3 = 0;
    this._aS = null;
    this._af = [];
    this._rootNode = new getOrgChart.node(-1, null, null, 2, 2);

    this._aF = {};
    this._searchResults = {
        found: [], showIndex: 0, oldValue: "", timer: ""
    };

    this._initControls();

    // Chart container
    this._p = new getOrgChart._p(this.element);

    this._l = false;
    if (this.theme.defs) {
        this.config.embededDefinitions += this.theme.defs;
    }

    for (var id in this.config.customize) {
        if (this.config.customize[id].theme) {
            this.config.embededDefinitions += getOrgChart.themes[this.config.customize[id].theme].defs
        }
    }

    this.viewBoxOriginCoords = [];

    // this.load();
    this.loadDTree();
};

/**
 * Init controls for the chart.
 *
 * @private
 */
getOrgChart.prototype._initControls = function () {
    this._width = get._userAgent().msie ? this.element.clientWidth : window.getComputedStyle(this.element, null).width;
    this._width = parseInt(this._width);
    if (this._width < 3) {
        this._width = 1024;
        this.element.style.width = "1024px"
    }
    this._height = get._userAgent().msie ? this.element.clientHeight : window.getComputedStyle(this.element, null).height;
    this._height = parseInt(this._height);
    if (this._height < 3) {
        this._height = parseInt((this._width * 9) / 16);
        this.element.style.height = this._height + "px"
    }

    this._areaWidth = this._width;
    this._areaHeight = this._height - this.theme.toolbarHeight;

    var html = getOrgChart.INNER_HTML
        .replace("[theme]", this.config.theme)
        .replace("[color]", this.config.color)
        .replace(/\[height]/g, this._areaHeight)
        .replace(/\[toolbar-height]/g, this.theme.toolbarHeight);

    if (getOrgChartW) {
        html = html.slice(0, -6);
        html += getOrgChartW;
    }

    this.element.innerHTML = html;
};

/**
 *
 * @param {HTMLElement} element
 */
getOrgChart.prototype.changeColorScheme = function (element) {
    if (this.config.color == element) {
        return;
    }

    this._p._styledContainer.className = this._p._styledContainer.className.replace(this.config.color, element);
    this.config.color = element;
};

getOrgChart.prototype._aB = function () {
    this._ab = [];
    this._ay = [];
    this._aI = [];
    getOrgChart._S(this, this._rootNode, 0);
    getOrgChart._za(this, this._rootNode, 0, 0, 0);
    getOrgChart._zv(this);
};
getOrgChart.prototype._zs = function (b, a) {
    if (this._ab[a] == null) {
        this._ab[a] = 0
    }
    if (this._ab[a] < b.h) {
        this._ab[a] = b.h
    }
};
getOrgChart.prototype._zx = function (b, a) {
    if (this._ay[a] == null) {
        this._ay[a] = 0
    }
    if (this._ay[a] < b.w) {
        this._ay[a] = b.w
    }
};
getOrgChart.prototype._ze = function (b, a) {
    b.leftNeighbor = this._aI[a];
    if (b.leftNeighbor != null) {
        b.leftNeighbor.rightNeighbor = b
    }
    this._aI[a] = b
};
getOrgChart.prototype._J = function (a) {
    switch (this.config.orientation) {
        case getOrgChart.RO_TOP:
        case getOrgChart.RO_TOP_PARENT_LEFT:
        case getOrgChart.RO_BOTTOM:
        case getOrgChart.RO_BOTTOM_PARENT_LEFT:
            return a.w;
        case getOrgChart.RO_RIGHT:
        case getOrgChart.RO_RIGHT_PARENT_TOP:
        case getOrgChart.RO_LEFT:
        case getOrgChart.RO_LEFT_PARENT_TOP:
            return a.h
    }

    return 0;
};
getOrgChart.prototype._N = function (g, d, e) {
    if (d >= e) {
        return g
    }
    if (g._R() == 0) {
        return null
    }
    var f = g._R();
    for (var a = 0; a < f; a++) {
        var b = g._D(a);
        var c = this._N(b, d + 1, e);
        if (c != null) {
            return c
        }
    }
    return null
};

/**
 * Generates and returns SVG string for HTML.
 *
 * @returns {string}
 * @private
 */
getOrgChart.prototype._generateSvg = function () {
    var e = [];
    var f;
    if (this._p._svgElement) {
        f = getOrgChart.util._getViewBoxCoords(this._p);
    } else {
        f = this._initViewBoxMatrix();
    }
    e.push(getOrgChart.OPEN_SVG.replace("[defs]", this.config.embededDefinitions).replace("[viewBox]", f.toString()));
    for (var nodeId in this.nodes) {
        var node = this.nodes[nodeId];
        if (this.isCollapsed(node)) {
            continue
        }
        var d = node.draw(this.config);
        this._nodeOnClick("renderNodeEvent", {node: node, content: d});
        e.push(d.join(""));
        var b = node._o(this.config);
        e.push(b);
    }

    e.push(getOrgChart.buttons.draw());
    e.push(getOrgChart.CLOSE_SVG);

    return e.join("");
};

getOrgChart.prototype._initViewBoxMatrix = function () {
    var m = this.config.siblingSeparation / 2;
    var n = this.config.levelSeparation / 2;
    var l;
    var c;
    var a = 0;
    var b = 0;
    var e = 0;
    var f = 0;
    var g = 0;
    var h = 0;
    for (var d in this.nodes) {
        var i = this.nodes[d];
        if (i.x > e) {
            e = i.x
        }
        if (i.y > f) {
            f = i.y
        }
        if (i.x < g) {
            g = i.x
        }
        if (i.y < h) {
            h = i.y
        }
    }

    switch (this.config.orientation) {
        case getOrgChart.RO_TOP:
        case getOrgChart.RO_TOP_PARENT_LEFT:
            l = Math.abs(g) + Math.abs(e) + this.theme.size[0];
            c = Math.abs(h) + Math.abs(f) + this.theme.size[1];
            var j = this._areaWidth / this._areaHeight;
            var k = l / c;
            if (this.config.scale === "auto") {
                if (j < k) {
                    c = l / j;
                    b = (-c) / 2 + (f - h) / 2 + this.theme.size[1] / 2
                } else {
                    l = c * j;
                    a = (-l) / 2 + (e - g) / 2 + this.theme.size[0] / 2
                }
            } else {
                l = (this._areaWidth) / this.config.scale;
                c = (this._areaHeight) / this.config.scale
            }
            this.initialViewBoxMatrix = [-m + a, n + b, l + this.config.siblingSeparation, c];
            break;
        case getOrgChart.RO_BOTTOM:
        case getOrgChart.RO_BOTTOM_PARENT_LEFT:
            l = Math.abs(g) + Math.abs(e) + this.theme.size[0];
            c = Math.abs(h) + Math.abs(f);
            var j = this._areaWidth / this._areaHeight;
            var k = l / c;
            if (this.config.scale === "auto") {
                if (j < k) {
                    c = l / j;
                    b = (-c) / 2 + (f - h) / 2
                } else {
                    l = c * j;
                    a = (-l) / 2 + (e - g) / 2 + this.theme.size[0] / 2
                }
            } else {
                l = (this._areaWidth) / this.config.scale;
                c = (this._areaHeight) / this.config.scale
            }
            this.initialViewBoxMatrix = [-m + a, -n - c - b, l + this.config.siblingSeparation, c];
            break;
        case getOrgChart.RO_RIGHT:
        case getOrgChart.RO_RIGHT_PARENT_TOP:
            l = Math.abs(g) + Math.abs(e);
            c = Math.abs(h) + Math.abs(f) + this.theme.size[1];
            var widthToHeightRation = this._areaWidth / this._areaHeight;
            var k = l / c;
            if (this.config.scale === "auto") {
                if (widthToHeightRation < k) {
                    c = l / widthToHeightRation;
                    b = (-c) / 2 + (f - h) / 2 + this.theme.size[1] / 2
                } else {
                    l = c * widthToHeightRation;
                    a = (-l) / 2 + (e - g) / 2
                }
            } else {
                l = (this._areaWidth) / this.config.scale;
                c = (this._areaHeight) / this.config.scale
            }
            this.initialViewBoxMatrix = [-l - n - a, -m + b, l, c + this.config.siblingSeparation];
            break;
        case getOrgChart.RO_LEFT:
        case getOrgChart.RO_LEFT_PARENT_TOP:
            l = Math.abs(g) + Math.abs(e) + this.theme.size[0];
            c = Math.abs(h) + Math.abs(f) + this.theme.size[1];
            var j = this._areaWidth / this._areaHeight;
            var k = l / c;
            if (this.config.scale === "auto") {
                if (j < k) {
                    c = l / j;
                    b = (-c) / 2 + (f - h) / 2 + this.theme.size[1] / 2
                } else {
                    l = c * j;
                    a = (-l) / 2 + (e - g) / 2 + this.theme.size[0] / 2
                }
            } else {
                l = (this._areaWidth) / this.config.scale;
                c = (this._areaHeight) / this.config.scale
            }
            this.initialViewBoxMatrix = [n + a, -m + b, l, c + this.config.siblingSeparation];
            break
    }

    return this.initialViewBoxMatrix.toString();
};

/*
getOrgChart.prototype._initRootSvgGElement = function () {
    this._svgElement = this._graphArea.getElementsByTagName("svg")[0];
    this._svgGRootElement = this._svgElement.getElementsByTagName("g")[0];
};
*/

/**
 * Draws the tree.
 *
 * @param {Function} callback
 * @returns {getOrgChart}
 */
getOrgChart.prototype.draw = function (callback) {
    this._p._initGraphContainerChildren();
    this._aB();
    this._p._graphArea.innerHTML = this._generateSvg();
    this._p._initSvg();

    if (this.config.enableSearch) {
        this._p._searchInput.style.display = "inherit";
        this._p._linkNextFoundNode.style.display = "inherit";
        this._p._linkPrevFoundNode.style.display = "inherit"
    }

    if (this.config.enableZoom) {
        this._p._zoomOutLink.style.display = "inherit";
        this._p._zoomInLink.style.display = "inherit"
    }
    if (this.config.enableGridView) {
        this._p._gridViewLink.style.display = "inherit"
    }
    if (this.config.enablePrint) {
        this._p._printLink.style.display = "inherit"
    }

    if (this.config.enableMove) {
        this._p._navigateArrowRight.style.display = "inherit";
        this._p._navigateArrowLeft.style.display = "inherit";
        this._p._navigateArrowDown.style.display = "inherit";
        this._p._navigateArrowUp.style.display = "inherit"
    }

    this._d();
    this._p._zy();
    this._w(callback);
    this.showMainView();

    ///////////////////////////////////////////////////
    //  Replace getOrgChart tree within dTree one.   //
    replaceSVG();
    // Reinit SVG
    this._p._initSvg();


    // Save viewbox coordinates
    this.initialViewBoxMatrix = this.viewBoxOriginCoords = getOrgChart.util._getViewBoxCoords(this._p);

    ///////////////////////////////////////////////////

    return this;
};


/**
 *
 * @param {Function|Array} a
 * @private
 */
getOrgChart.prototype._w = function (a) {
    var nodes = [];
    var node;

    for (var d in this.nodes) {
        if (this.nodes[d]._zi == null || this.nodes[d]._zk == null) {
            continue;
        }
        if (this.nodes[d]._zi == this.nodes[d].x && this.nodes[d]._zk == this.nodes[d].y) {
            continue;
        }

        node = this._p.getNodeById(d);
        if (!node) {
            continue;
        }

        nodes.push(this.nodes[d]);
    }

    for (var c = 0; c < nodes.length; c++) {
        var e = nodes[c];
        node = this._p.getNodeById(e.id);
        var b = getOrgChart.util._getElementCoordinatesAsArray(node);
        var h = b.slice(0);
        h[4] = e.x;
        h[5] = e.y;
        get._w(node, {transform: b}, {transform: h}, 200, get._w._aX, function (i) {
            if (a && nodes[nodes.length - 1].id == i[0].getAttribute("data-node-id")) {
                a();
            }
        })
    }

    if (a && nodes.length == 0) {
        a();
    }
};

/**
 * Fires when user clicks left|right|up|down arrow links on a graph.
 *
 * @param {Element} targetElement
 * @param {MouseEvent} event
 * @private
 */
getOrgChart.prototype._moveArrowsClick = function (targetElement, event) {
    this._a(targetElement, "mouseup", this._ai);
    this._a(targetElement, "mouseleave", this._ai);

    var self = this;
    var a = 100;

    targetElement.interval = setInterval(function () {
        switch (targetElement) {
            case self._p._navigateArrowRight:
                self.move("right", a);
                break;
            case self._p._navigateArrowLeft:
                self.move("left", a);
                break;
            case self._p._navigateArrowDown:
                self.move("up", a);
                break;
            case self._p._navigateArrowUp:
                self.move("down", a);
                break
        }

        if (a > 10) {
            a--;
        }
    }, 20);
};

/**
 *
 * @param {Element} clickedElement
 * @param {MouseEvent} event
 * @private
 */
getOrgChart.prototype._ai = function (clickedElement, event) {
    this._aP(clickedElement, "mouseup", this._ai);
    this._aP(clickedElement, "mouseleave", this._ai);

    clearInterval(clickedElement.interval)
};

/**
 * Search or move
 *
 * @param {Array|string} direction right|left|down|up|[] string when used navigation arrows and array when search field
 * @param {Number|null} offset Null if use search field
 * @param {Function|null} b Null if use navigation arrows
 * @returns {getOrgChart}
 */
getOrgChart.prototype.move = function (direction, offset, b) {
    var viewBoxCoords = getOrgChart.util._getViewBoxCoords(this._p);

    var coords = viewBoxCoords.slice(0);

    var c = this.theme.size[0] / offset;
    var d = this.theme.size[1] / offset;
    var g = false;

    switch (direction) {
        case"left":
            coords[0] -= c;
            break;
        case"down":
            coords[1] -= d;
            break;
        case"right":
            coords[0] += c;
            break;
        case"up":
            coords[1] += d;
            break;
        default:
            coords[0] = direction[0];
            coords[1] = direction[1];
            coords[2] = direction[2];
            coords[3] = direction[3];
            g = true;
            break
    }

    if (g) {
        get._w(this._p._svgElement, {viewBox: viewBoxCoords}, {viewBox: coords}, 300, get._w._az, function () {
            if (b) {
                b()
            }
        });
    } else {
        this._p._svgElement.setAttribute("viewBox", coords);
    }

    return this;
};

/**
 * Checks if node is collapsed.
 *
 * @param {Object} node
 * @return {boolean}
 */
getOrgChart.prototype.isCollapsed = function (node) {
    // By default all nodes are expanded
    return false;

    if ((node.parent == this._rootNode) || (node.parent == null)) {
        return false;
    }
    if (node.parent.collapsed != getOrgChart.EXPANDED) {
        return true;
    } else {
        return this.isCollapsed(node.parent);
    }

    return false;
};

getOrgChart.prototype._d = function () {
    if (this.config.enableGridView) {
        this._a(this._p._gridViewLink, "click", this._zd);
        this._a(this._p._2, "click", this._zc)
    }
    if (this.config.enablePrint) {
        this._a(this._p._printLink, "click", this._aO)
    }
    if (this.config.enableMove) {
        this._a(this._p._navigateArrowRight, "mousedown", this._moveArrowsClick);
        this._a(this._p._navigateArrowLeft, "mousedown", this._moveArrowsClick);
        this._a(this._p._navigateArrowDown, "mousedown", this._moveArrowsClick);
        this._a(this._p._navigateArrowUp, "mousedown", this._moveArrowsClick);
        this._a(this._p._graphArea, "mousemove", this._au);
        this._a(this._p._graphArea, "mousedown", this._an);
        this._a(this._p._graphArea, "mouseup", this._aj);
        this._a(this._p._graphArea, "mouseleave", this._aj)
    }
    this._a(window, "keydown", this._ac);
    for (i = 0; i < this._p._ap.length; i++) {
        this._a(this._p._ap[i], "click", this._aQ)
    }
    for (i = 0; i < this._p._aW.length; i++) {
        this._a(this._p._aW[i], "mouseover", this._aZ);
        this._a(this._p._aW[i], "click", this._aA)
    }
    this._a(this._p._j, "click", this._a5);
    if (this.config.enableZoom) {
        this._a(this._p._zoomInLink, "click", this._zoomIn);
        this._a(this._p._zoomOutLink, "click", this._zoomOut);
        this._a(this._p._graphArea, "DOMMouseScroll", this._a7);
        this._a(this._p._graphArea, "mousewheel", this._a7)
    }
    if (this.config.enableSearch) {
        this._a(this._p._linkNextFoundNode, "click", this._al);
        this._a(this._p._linkPrevFoundNode, "click", this._aM);
        this._a(this._p._searchInput, "keyup", this._search);
        this._a(this._p._searchInput, "paste", this._zq)
    }
};

/**
 *
 * @param {Element} element
 * @param {String} eventName click|mouseup
 * @param {Function} callback
 * @private
 */
getOrgChart.prototype._a = function (element, eventName, callback) {
    if (!element.getListenerList) {
        element.getListenerList = {}
    }
    if (element.getListenerList[eventName]) {
        return
    }
    function f(g, h) {
        return function () {
            if (h) {
                return h.apply(g, [this, arguments]);
            }
        }
    }

    callback = f(this, callback);
    function e(g) {
        var h = callback.apply(this, arguments);
        if (h === false) {
            g.stopPropagation();
            g.preventDefault()
        }
        return (h)
    }

    function a() {
        var g = callback.call(element, window.event);
        if (g === false) {
            window.event.returnValue = false;
            window.event.cancelBubble = true
        }
        return (g)
    }

    if (element.addEventListener) {
        element.addEventListener(eventName, e, false)
    } else {
        element.attachEvent("on" + eventName, a)
    }
    element.getListenerList[eventName] = e
};

getOrgChart.prototype._aP = function (a, b) {
    if (a.getListenerList[b]) {
        var c = a.getListenerList[b];
        a.removeEventListener(b, c, false);
        delete a.getListenerList[b]
    }
};

/**
 * Adds event handler.
 *
 * @param {String} eventName Event Name
 * @param {Function|Object} eventHandler Event handler
 * @private
 */
getOrgChart.prototype._addEventHandler = function (eventName, eventHandler) {
    if (!this._eventsQueue) {
        this._eventsQueue = {}
    }

    if (!this._eventsQueue[eventName]) {
        this._eventsQueue[eventName] = [];
    }

    this._eventsQueue[eventName].push(eventHandler);
};

/**
 * Init event handlers.
 *
 * @private
 */
getOrgChart.prototype._initEvents = function () {
    if (this.config.removeNodeEvent) {
        this._addEventHandler("removeNodeEvent", this.config.removeNodeEvent)
    }
    if (this.config.updateNodeEvent) {
        this._addEventHandler("updateNodeEvent", this.config.updateNodeEvent)
    }
    if (this.config.createNodeEvent) {
        this._addEventHandler("createNodeEvent", this.config.createNodeEvent)
    }
    if (this.config.clickNodeEvent) {
        this._addEventHandler("clickNodeEvent", this.config.clickNodeEvent)
    }
    if (this.config.renderNodeEvent) {
        this._addEventHandler("renderNodeEvent", this.config.renderNodeEvent)
    }
    if (this.config.insertNodeEvent) {
        this._addEventHandler("insertNodeEvent", this.config.insertNodeEvent)
    }
    if (this.config.updatedEvent) {
        this._addEventHandler("updatedEvent", this.config.updatedEvent)
    }
};

/**
 * @param {string} eventName
 * @param {Object} node Contains node property
 * @returns {boolean}
 * @private
 */
getOrgChart.prototype._nodeOnClick = function (eventName, node) {
    if (!this._eventsQueue || !this._eventsQueue[eventName]) {
        return true;
    }

    var d = true;
    if (this._eventsQueue[eventName]) {
        var c;
        for (c = 0; c < this._eventsQueue[eventName].length; c++) {
            if (this._eventsQueue[eventName][c](this, node) === false) {
                d = false;
            }
        }
    }

    return d;
};

getOrgChart._p = function (a) {
    this.element = a;
};

/**
 * Init search and graph areas.
 *
 * @private
 */
getOrgChart._p.prototype._initGraphContainerChildren = function () {
    this._styledContainer = this.element.getElementsByTagName("div")[0];
    var children = this._styledContainer.children;
    this._searchArea          = children[0];
    this._graphArea           = children[1];
    this._graphAreaInvisible1 = children[2];
    this._graphAreaInvisible2 = children[3];
};

getOrgChart._p.prototype._initSvg = function () {
    this._svgElement = this._graphArea.getElementsByTagName("svg")[0];
    this._svgGRootElement = this._svgElement.getElementsByTagName("g")[0];

    var transform = this._svgGRootElement.getAttribute('transform');
    if (transform) {
        transform = transform.replace("translate", "").replace("(", "").replace(")", "").split(',')[0];
        this._viewBoxTranslate = transform;
    }

    this._zg = this._searchArea.getElementsByTagName("div")[0];
    var d = this._zg.getElementsByTagName("div")[0];
    var a = this._zg.getElementsByTagName("div")[1];
    var b = this._zg.getElementsByTagName("div")[2];
    this._searchInput = d.getElementsByTagName("input")[0];
    var c = d.getElementsByTagName("a");
    this._linkNextFoundNode = c[1];
    this._linkPrevFoundNode = c[0];
    this._zoomOutLink = c[2];
    this._zoomInLink = c[3];
    this._gridViewLink = c[4];
    this._printLink = c[5];
    this._h = this._graphAreaInvisible1.getElementsByTagName("div")[0];
    this._n = this._graphAreaInvisible1.getElementsByTagName("div")[1];
    this._ap = this._svgGRootElement.querySelectorAll("[data-btn-action]");
    this._aW = this._svgGRootElement.querySelectorAll("[data-node-id]");
    c = a.getElementsByTagName("a");
    this._j = c[0];
    c = b.getElementsByTagName("a");
    this._2 = c[0];

    this._zf = [];
    var e = this._svgElement.getElementsByTagName("text");
    for (var r = 0; r < e.length; r++) {
        this._zf.push(e[r])
    }

    this._navigateArrowRight = this._styledContainer.getElementsByClassName("get-right")[0];
    this._navigateArrowLeft = this._styledContainer.getElementsByClassName("get-left")[0];
    this._navigateArrowDown = this._styledContainer.getElementsByClassName("get-down")[0];
    this._navigateArrowUp = this._styledContainer.getElementsByClassName("get-up")[0]
};

getOrgChart._p.prototype._a6 = function (a) {
    this._graphArea.style.overflow = "auto";
    this._svgElement.style.width = (a + "px")
};
getOrgChart._p.prototype._T = function () {
    return this._n.getElementsByTagName("input")[0]
};
getOrgChart._p.prototype._F = function () {
    var a = this._n.getElementsByTagName("input");
    var c = {};
    for (i = 1; i < a.length; i++) {
        var d = a[i].value;
        var b = a[i].parentNode.previousSibling.innerHTML;
        c[b] = d
    }
    return c
};
getOrgChart._p.prototype._G = function () {
    return this._n.getElementsByTagName("input")
};
getOrgChart._p.prototype._V = function () {
    var a = this._n.getElementsByTagName("select");
    for (i = 0; i < a.length; i++) {
        if (a[i].className == "get-oc-labels") {
            return a[i]
        }
    }
    return null
};
getOrgChart._p.prototype._B = function () {
    var a = this._n.getElementsByTagName("select");
    for (i = 0; i < a.length; i++) {
        if (a[i].className == "get-oc-select-parent") {
            return a[i]
        }
    }
    return null
};

/**
 * Returns node element by Id
 *
 * @param {Number} id
 * @return {Element}
 */
getOrgChart._p.prototype.getNodeById = function (id) {
    return this._svgGRootElement.querySelector("[id='" + id + "']");
};

getOrgChart._p.prototype.removeLinks = function () {
    var a = this._svgGRootElement.querySelectorAll("[data-link-id]");
    var b = a.length;
    while (b--) {
        a[b].parentNode.removeChild(a[b])
    }
};
getOrgChart._p.prototype.getButtonByType = function (a) {
    return this._svgGRootElement.querySelector("[data-btn-action='" + a + "']")
};
getOrgChart._p.prototype._zy = function (a) {
    var c;
    if (!a) {
        c = this._zf
    } else {
        c = this.getNodeById(a).getElementsByTagName("text")
    }
    for (i = 0; i < c.length; i++) {
        var e = c[i].getAttribute("x");
        var d = c[i].getAttribute("width");
        if (c[i].offsetParent === null) {
            return
        }
        var b = c[i].getComputedTextLength();
        while (b > d) {
            c[i].textContent = c[i].textContent.substring(0, c[i].textContent.length - 4);
            c[i].textContent += "...";
            b = c[i].getComputedTextLength()
        }
    }
};

getOrgChart.SCALE_FACTOR = 1.2;
getOrgChart.INNER_HTML = '<div class="get-[theme] get-[color] get-org-chart"><div class="get-oc-tb"><div><div style="height:[toolbar-height]px;"><input placeholder="Search" id="chartSearch" type="text" /><a title="previous" class="get-prev get-disabled" href="javascript:void(0)">&nbsp;</a><a title="next" class="get-next get-disabled" href="javascript:void(0)">&nbsp;</a><a class="get-minus" title="zoom out" href="javascript:void(0)">&nbsp;</a><a class="get-plus" title="zoom in" href="javascript:void(0)">&nbsp;</a><a href="javascript:void(0)" class="get-grid-view" title="grid view">&nbsp;</a><a href="javascript:void(0)" class="get-print" title="print">&nbsp;</a></div><div style="height:[toolbar-height]px;"><a title="previous page" class="get-prev-page" href="javascript:void(0)">&nbsp;</a></div><div style="height:[toolbar-height]px;"><a title="previous page" class="get-prev-page" href="javascript:void(0)">&nbsp;</a></div></div></div><div class="get-oc-c" style="height:[height]px;"></div><div class="get-oc-v" style="height:[height]px;"><div class="get-image-pane"></div><div class="get-data-pane"></div></div><div class="get-oc-g" style="height:[height]px;"></div><div class="get-left"><div class="get-left-icon"></div></div><div class="get-right"><div class="get-right-icon"></div></div><div class="get-up"><div class="get-up-icon"></div></div><div class="get-down"><div class="get-down-icon"></div></div></div>';
getOrgChart.DETAILS_VIEW_INPUT_HTML = '<div data-field-name="[label]"><div class="get-label">[label]</div><div class="get-data"><input value="[value]"/></div></div>';
getOrgChart.DETAILS_VIEW_USER_LOGO = '<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Layer_1" x="0px" y="0px" width="512px" height="512px" viewBox="0 0 640 640" enable-background="new 0 0 420 420" xml:space="preserve" xmlns:xml="http://www.w3.org/XML/1998/namespace" class="get-user-logo"><g><path class="get-user-logo" d="M258.744,293.214c70.895,0,128.365-57.472,128.365-128.366c0-70.896-57.473-128.367-128.365-128.367 c-70.896,0-128.368,57.472-128.368,128.367C130.377,235.742,187.848,293.214,258.744,293.214z"/><path d="M371.533,322.432H140.467c-77.577,0-140.466,62.909-140.466,140.487v12.601h512v-12.601   C512,385.341,449.112,322.432,371.533,322.432z"/></g></svg>';
getOrgChart.DETAILS_VIEW_ID_INPUT = '<input value="[personId]" type="hidden"></input>';
getOrgChart.DETAILS_VIEW_ID_IMAGE = '<img src="[src]" width="420" />';
getOrgChart.HIGHLIGHT_SCALE_FACTOR = 1.2;
getOrgChart.MOVE_FACTOR = 2;
getOrgChart.W = '';
getOrgChart.RO_TOP = 0;
getOrgChart.RO_BOTTOM = 1;
getOrgChart.RO_RIGHT = 2;
getOrgChart.RO_LEFT = 3;
getOrgChart.RO_TOP_PARENT_LEFT = 4;
getOrgChart.RO_BOTTOM_PARENT_LEFT = 5;
getOrgChart.RO_RIGHT_PARENT_TOP = 6;
getOrgChart.RO_LEFT_PARENT_TOP = 7;
getOrgChart.NJ_TOP = 0;
getOrgChart.NJ_CENTER = 1;
getOrgChart.NJ_BOTTOM = 2;
getOrgChart.OPEN_SVG = '<svg xmlns="http://www.w3.org/2000/svg" version="1.1" viewBox="[viewBox]"><defs>[defs]</defs><g>';
getOrgChart.CLOSE_SVG = "</svg>";
getOrgChart.OPEN_NODE = '<g data-node-id="[data-node-id]" class="get-level-[level] [nodeCssClass]" transform="matrix(1,0,0,1,[x],[y])">';
getOrgChart.CLOSE_NODE = "</g>";
getOrgChart.NOT_DEFINED = 0;
getOrgChart.COLLAPSED = 1;
getOrgChart.EXPANDED = 2;
getOrgChart._S = function (h, g, d) {
    var c = null;
    g.x = 0;
    g.y = 0;
    g._aU = 0;
    g._ah = 0;
    g.level = d;
    g.leftNeighbor = null;
    g.rightNeighbor = null;
    h._zs(g, d);
    h._zx(g, d);
    h._ze(g, d);
    if (g.collapsed == getOrgChart.NOT_DEFINED) {
        g.collapsed = (h.config.expandToLevel && h.config.expandToLevel <= d && g._R() ? getOrgChart.COLLAPSED : getOrgChart.EXPANDED)
    }
    if (g._R() == 0 || d == h.config.maxDepth) {
        c = g._U();
        if (c != null) {
            g._aU = c._aU + h._J(c) + h.config.siblingSeparation
        } else {
            g._aU = 0
        }
    } else {
        var f = g._R();
        for (var a = 0; a < f; a++) {
            var b = g._D(a);
            getOrgChart._S(h, b, d + 1)
        }
        var e = g._C(h);
        e -= h._J(g) / 2;
        c = g._U();
        if (c != null) {
            g._aU = c._aU + h._J(c) + h.config.siblingSeparation;
            g._ah = g._aU - e;
            getOrgChart._s(h, g, d)
        } else {
            if (h.config.orientation <= 3) {
                g._aU = e
            } else {
                g._aU = 0
            }
        }
    }
};
getOrgChart._s = function (t, m, g) {
    var a = m._Y();
    var b = a.leftNeighbor;
    var c = 1;
    for (var d = t.config.maxDepth - g; a != null && b != null && c <= d;) {
        var i = 0;
        var h = 0;
        var o = a;
        var f = b;
        for (var e = 0; e < c; e++) {
            o = o.parent;
            f = f.parent;
            i += o._ah;
            h += f._ah
        }
        var s = (b._aU + h + t._J(b) + t.config.subtreeSeparation) - (a._aU + i);
        if (s > 0) {
            var q = m;
            var n = 0;
            for (; q != null && q != f; q = q._U()) {
                n++
            }
            if (q != null) {
                var r = m;
                var p = s / n;
                for (; r != f; r = r._U()) {
                    r._aU += s;
                    r._ah += s;
                    s -= p
                }
            }
        }
        c++;
        if (a._R() == 0) {
            a = t._N(m, 0, c)
        } else {
            a = a._Y()
        }
        if (a != null) {
            b = a.leftNeighbor
        }
    }
};
getOrgChart._za = function (h, d, b, i, k) {
    if (b <= h.config.maxDepth) {
        var j = h._a3 + d._aU + i;
        var l = h._a4 + k;
        var c = 0;
        var e = 0;
        var a = false;
        switch (h.config.orientation) {
            case getOrgChart.RO_TOP:
            case getOrgChart.RO_TOP_PARENT_LEFT:
            case getOrgChart.RO_BOTTOM:
            case getOrgChart.RO_BOTTOM_PARENT_LEFT:
                c = h._ab[b];
                e = d.h;
                break;
            case getOrgChart.RO_RIGHT:
            case getOrgChart.RO_RIGHT_PARENT_TOP:
            case getOrgChart.RO_LEFT:
            case getOrgChart.RO_LEFT_PARENT_TOP:
                c = h._ay[b];
                a = true;
                e = d.w;
                break
        }
        switch (h.config.nodeJustification) {
            case getOrgChart.NJ_TOP:
                d.x = j;
                d.y = l;
                break;
            case getOrgChart.NJ_CENTER:
                d.x = j;
                d.y = l + (c - e) / 2;
                break;
            case getOrgChart.NJ_BOTTOM:
                d.x = j;
                d.y = (l + c) - e;
                break
        }
        if (a) {
            var g = d.x;
            d.x = d.y;
            d.y = g
        }
        switch (h.config.orientation) {
            case getOrgChart.RO_BOTTOM:
            case getOrgChart.RO_BOTTOM_PARENT_LEFT:
                d.y = -d.y - e;
                break;
            case getOrgChart.RO_RIGHT:
            case getOrgChart.RO_RIGHT_PARENT_TOP:
                d.x = -d.x - e;
                break
        }
        if (d._R() != 0) {
            getOrgChart._za(h, d._Y(), b + 1, i + d._ah, k + c + h.config.levelSeparation)
        }
        var f = d._M();
        if (f != null) {
            getOrgChart._za(h, f, b, i, k)
        }
    }
};
getOrgChart._zv = function (e) {
    e._a3 = e._rootNode.x;
    e._a4 = e._rootNode.y;
    if (e._aS) {
        var b = e.nodes[e._aS.id];
        var c = e._aS.old_x - b.x;
        var d = e._aS.old_y - b.y;
        for (var a in e.nodes) {
            if (e.nodes[a].isVisible()) {
                e.nodes[a].x += c;
                e.nodes[a].y += d
            }
        }
    }
    e._aS = null
};

/**
 * Graph node constructor.
 *
 * @param id
 * @param pid
 * @param {Object} data
 * @param {Array} dimention
 * @param {Array} photoFields
 * @param {Number} collapsed
 *
 * @example data
 *      {
 *        dead: "0",
 *        death_date: null,
 *        first_name: "Alex",
 *        middle_name: "_____________",
 *        gender: "1",
 *        email: "_____________",
 *        last_name: "Taylor",
 *        node_type: "1",
 *        b_date: "1959-01-12",
 *        image: "http://kulbeli.app/images/male.jpg",
 *        ...
 *      }
 *  @example dimention
 *      [ 500, 220 ]
 *
 *  @example photoFields
 *      ['Image']
 */
getOrgChart.node = function (id, pid, data, dimention, photoFields, collapsed) {
    this.id = id;
    this.pid = pid;
    this.data = data;
    this.w = dimention[0];
    this.h = dimention[1];
    this.parent = null;
    this.children = [];
    this.leftNeighbor = null;
    this.rightNeighbor = null;
    this.photoFields = photoFields;
    this.type = "node";
    this.collapsed = collapsed;
    this.x = 0;
    this._zi = null;
    this._zk = null;
    this.y = 0;
    this._aU = 0;
    this._ah = 0
};

getOrgChart.node.prototype.compareTo = function (b) {
    var self = this;
    if (self === undefined || b === undefined || self.x === undefined || self.y === undefined || b.x === undefined || b.y === undefined) {
        return false;
    } else {
        return (self.x == b.x && self.y == b.y);
    }
};
getOrgChart.node.prototype.getImageUrl = function () {
    if (this.photoFields && this.data[this.photoFields[0]]) {
        return this.data[this.photoFields[0]];
    }

    return null;
};
getOrgChart.node.prototype._R = function () {
    if (this.collapsed == getOrgChart.COLLAPSED) {
        return 0
    } else {
        if (this.children == null) {
            return 0
        } else {
            return this.children.length
        }
    }
};
getOrgChart.node.prototype._U = function () {
    if (this.leftNeighbor != null && this.leftNeighbor.parent == this.parent) {
        return this.leftNeighbor;
    } else {
        return null;
    }
};
getOrgChart.node.prototype.isVisible = function () {
    return (!(this.x == 0 && this.y == 0));
};

getOrgChart.node.prototype._M = function () {
    if (this.rightNeighbor != null && this.rightNeighbor.parent == this.parent) {
        return this.rightNeighbor
    } else {
        return null
    }
};
getOrgChart.node.prototype._D = function (a) {
    return this.children[a]
};
getOrgChart.node.prototype._C = function (a) {
    node = this._Y();
    node1 = this._H();
    return node._aU + ((node1._aU - node._aU) + a._J(node1)) / 2
};
getOrgChart.node.prototype._Y = function () {
    return this._D(0)
};
getOrgChart.node.prototype._H = function () {
    return this._D(this._R() - 1)
};
getOrgChart.node.prototype._o = function (b) {
    if (!this.children.length) {
        return []
    }
    var e = [];
    var f = 0, j = 0, g = 0, l = 0, h = 0, m = 0, i = 0, n = 0;
    var d = null;
    var a;
    switch (b.orientation) {
        case getOrgChart.RO_TOP:
        case getOrgChart.RO_TOP_PARENT_LEFT:
            f = this.x + (this.w / 2);
            j = this.y + this.h;
            a = -25;
            break;
        case getOrgChart.RO_BOTTOM:
        case getOrgChart.RO_BOTTOM_PARENT_LEFT:
            f = this.x + (this.w / 2);
            j = this.y;
            a = 35;
            break;
        case getOrgChart.RO_RIGHT:
        case getOrgChart.RO_RIGHT_PARENT_TOP:
            f = this.x;
            j = this.y + (this.h / 2);
            a = -10;
            break;
        case getOrgChart.RO_LEFT:
        case getOrgChart.RO_LEFT_PARENT_TOP:
            f = this.x + this.w;
            j = this.y + (this.h / 2);
            a = -10;
            break
    }
    for (var c = 0; c < this.children.length; c++) {
        d = this.children[c];
        switch (b.orientation) {
            case getOrgChart.RO_TOP:
            case getOrgChart.RO_TOP_PARENT_LEFT:
                i = h = d.x + (d.w / 2);
                n = d.y;
                g = f;
                switch (b.nodeJustification) {
                    case getOrgChart.NJ_TOP:
                        l = m = n - b.levelSeparation / 2;
                        break;
                    case getOrgChart.NJ_BOTTOM:
                        l = m = j + b.levelSeparation / 2;
                        break;
                    case getOrgChart.NJ_CENTER:
                        l = m = j + (n - j) / 2;
                        break
                }
                break;
            case getOrgChart.RO_BOTTOM:
            case getOrgChart.RO_BOTTOM_PARENT_LEFT:
                i = h = d.x + (d.w / 2);
                n = d.y + d.h;
                g = f;
                switch (b.nodeJustification) {
                    case getOrgChart.NJ_TOP:
                        l = m = n + b.levelSeparation / 2;
                        break;
                    case getOrgChart.NJ_BOTTOM:
                        l = m = j - b.levelSeparation / 2;
                        break;
                    case getOrgChart.NJ_CENTER:
                        l = m = n + (j - n) / 2;
                        break
                }
                break;
            case getOrgChart.RO_RIGHT:
            case getOrgChart.RO_RIGHT_PARENT_TOP:
                i = d.x + d.w;
                n = m = d.y + (d.h / 2);
                l = j;
                switch (b.nodeJustification) {
                    case getOrgChart.NJ_TOP:
                        g = h = i + b.levelSeparation / 2;
                        break;
                    case getOrgChart.NJ_BOTTOM:
                        g = h = f - b.levelSeparation / 2;
                        break;
                    case getOrgChart.NJ_CENTER:
                        g = h = i + (f - i) / 2;
                        break
                }
                break;
            case getOrgChart.RO_LEFT:
            case getOrgChart.RO_LEFT_PARENT_TOP:
                i = d.x;
                n = m = d.y + (d.h / 2);
                l = j;
                switch (b.nodeJustification) {
                    case getOrgChart.NJ_TOP:
                        g = h = i - b.levelSeparation / 2;
                        break;
                    case getOrgChart.NJ_BOTTOM:
                        g = h = f + b.levelSeparation / 2;
                        break;
                    case getOrgChart.NJ_CENTER:
                        g = h = f + (i - f) / 2;
                        break
                }
                break
        }
        if (this.collapsed == getOrgChart.EXPANDED) {
            switch (b.linkType) {
                case"M":
                    e.push('<path data-link-id="' + this.id + '" class="link"   d="M' + f + "," + j + " " + g + "," + l + " " + h + "," + m + " L" + i + "," + n + '"/>');
                    break;
                case"B":
                    e.push('<path data-link-id="' + this.id + '" class="link"  d="M' + f + "," + j + " C" + g + "," + l + " " + h + "," + m + " " + i + "," + n + '"/>');
                    break
            }
        }
        if (b.expandToLevel) {
            e.push(getOrgChart.buttons.expColl.replace("[display]", this.collapsed == getOrgChart.EXPANDED ? "none" : "block").replace(/\[xa]/g, f).replace(/\[ya]/g, j).replace(/\[xam13]/g, (f - 13)).replace(/\[xap13]/g, (f + 13)).replace(/\[yam13]/g, (j - 13)).replace(/\[yap13]/g, (j + 13)).replace(/\[id]/g, this.id))
        }
    }
    return e.join("")
};

/**
 *
 * @param c
 * @returns {getOrgChart.node}
 */
getOrgChart.node.prototype.getMostDeepChild = function (c) {
    var self = this;

    function a(f, g) {
        if (f.collapsed == getOrgChart.EXPANDED) {
            for (var d = 0; d < f.children.length; d++) {
                var e = g[f.children[d].id];
                if (e.level > self.level) {
                    self = e;
                }

                a(f.children[d], g);
            }
        }
    }

    a(this, c);

    return self;
};

/**
 * Draw the node
 *
 * @param a
 * @return {Array}
 */
getOrgChart.node.prototype.draw = function (a) {
    var h = [];
    var b = this.getImageUrl();
    var l = a.customize[this.id] && a.customize[this.id].theme ? getOrgChart.themes[a.customize[this.id].theme] : getOrgChart.themes[a.theme];
    var f = a.customize[this.id] && a.customize[this.id].theme ? " get-" + a.customize[this.id].theme : "";
    var e = a.customize[this.id] && a.customize[this.id].color ? " get-" + a.customize[this.id].color : "";
    if (f && !e) {
        e = " get-" + a.color
    }
    if (e && !f) {
        f = " get-" + a.theme
    }
    var d = f + e;
    var j = b ? l.textPoints : l.textPointsNoImage;
    h.push(getOrgChart.OPEN_NODE.replace("[data-node-id]", this.id).replace("[x]", this._zi == null ? this.x : this._zi).replace("[y]", this._zk == null ? this.y : this._zk).replace("[level]", this.level).replace("[nodeCssClass]", d));
    for (themeProperty in l) {
        switch (themeProperty) {
            case"image":
                if (b) {
                    h.push(l.image.replace("[href]", b))
                }
                break;
            case"text":
                var i = 0;
                for (k = 0; k < a.primaryFields.length; k++) {
                    var g = j[i];
                    var c = a.primaryFields[k];
                    if (!g || !this.data || !this.data[c]) {
                        continue
                    }
                    h.push(l.text.replace("[index]", i).replace("[text]", this.data[c]).replace("[y]", g.y).replace("[x]", g.x).replace("[rotate]", g.rotate).replace("[width]", g.width));
                    i++
                }
                break;
            default:
                if (themeProperty != "size" && themeProperty != "toolbarHeight" && themeProperty != "textPoints" && themeProperty != "textPointsNoImage") {
                    h.push(l[themeProperty])
                }
                break
        }
    }
    h.push(getOrgChart.CLOSE_NODE);
    return h;
};
if (!getOrgChart) {
    var getOrgChart = {}
}
getOrgChart.themes = {
    monica: {
        size: [500, 220],
        toolbarHeight: 46,
        textPoints: [
            {x: 10, y: 200, width: 490},
            {x: 200, y: 80, width: 300},
            {x: 200, y: 120, width: 300},
            {x: 210, y: 90, width: 290},
            {x: 200, y: 115, width: 300},
            {x: 185, y: 140, width: 315}
        ],
        textPointsNoImage: [{x: 10, y: 200, width: 490}, {x: 10, y: 40, width: 490}, {x: 10, y: 65, width: 490}, {
            x: 10,
            y: 90,
            width: 490
        }, {x: 10, y: 115, width: 490}, {x: 10, y: 140, width: 490}],
        box: '<path class="get-box" d="M0 0 L500 0 L500 220 L0 220 Z"/>',
        text: '<text width="[width]" class="get-text get-text-[index]" x="[x]" y="[y]">[text]</text>',
        image: '<clipPath id="getMonicaClip"><circle cx="105" cy="65" r="85" /></clipPath><image preserveAspectRatio="xMidYMid slice" clip-path="url(#getMonicaClip)" xlink:href="[href]" x="20" y="-20" height="170" width="170"/>'
    }
};
if (typeof(get) == "undefined") {
    get = {}
}

/**
 *
 * @param {Element} svgElement
 * @param {Object} viewBoxCoords1
 * @param {Object} viewBoxCoords2
 * @param {Number} h
 * @param {Number} j
 * @param {Function} callback
 * @private
 */
get._w = function (svgElement, viewBoxCoords1, viewBoxCoords2, h, j, callback) {
    var intervalId;
    var e = 10;
    var l = 1;
    var n = 1;
    var m = h / e + 1;
    var k = document.getElementsByTagName("g");

    if (!svgElement.length) {
        svgElement = [svgElement]
    }

    function f() {
        for (var s in viewBoxCoords2) {
            var t = getOrgChart.util._existsInArray(["top", "left", "right", "bottom"], s.toLowerCase()) ? "px" : "";
            switch (s.toLowerCase()) {
                case"d":
                    var v = j(((n * e) - e) / h) * (viewBoxCoords2[s][0] - viewBoxCoords1[s][0]) + viewBoxCoords1[s][0];
                    var w = j(((n * e) - e) / h) * (viewBoxCoords2[s][1] - viewBoxCoords1[s][1]) + viewBoxCoords1[s][1];
                    for (z = 0; z < svgElement.length; z++) {
                        svgElement[z].setAttribute("d", svgElement[z].getAttribute("d") + " L" + v + " " + w)
                    }
                    break;
                case"transform":
                    if (viewBoxCoords2[s]) {
                        var q = viewBoxCoords1[s];
                        var p = viewBoxCoords2[s];
                        var r = [0, 0, 0, 0, 0, 0];
                        for (i in q) {
                            r[i] = j(((n * e) - e) / h) * (p[i] - q[i]) + q[i]
                        }
                        for (z = 0; z < svgElement.length; z++) {
                            svgElement[z].setAttribute("transform", "matrix(" + r.toString() + ")")
                        }
                    }
                    break;
                case"viewbox":
                    if (viewBoxCoords2[s]) {
                        var q = viewBoxCoords1[s];
                        var p = viewBoxCoords2[s];
                        var r = [0, 0, 0, 0];
                        for (i in q) {
                            r[i] = j(((n * e) - e) / h) * (p[i] - q[i]) + q[i]
                        }
                        for (z = 0; z < svgElement.length; z++) {
                            svgElement[z].setAttribute("viewBox", r.toString())
                        }
                    }
                    break;
                case"margin":
                    if (viewBoxCoords2[s]) {
                        var q = viewBoxCoords1[s];
                        var p = viewBoxCoords2[s];
                        var r = [0, 0, 0, 0];
                        for (i in q) {
                            r[i] = j(((n * e) - e) / h) * (p[i] - q[i]) + q[i]
                        }
                        var g = "";
                        for (i = 0; i < r.length; i++) {
                            g += parseInt(r[i]) + "px "
                        }
                        for (z = 0; z < svgElement.length; z++) {
                            if (svgElement[z] && svgElement[z].style) {
                                svgElement[z].style[s] = u
                            }
                        }
                    }
                    break;
                default:
                    var u = j(((n * e) - e) / h) * (viewBoxCoords2[s] - viewBoxCoords1[s]) + viewBoxCoords1[s];
                    for (z = 0; z < svgElement.length; z++) {
                        if (svgElement[z] && svgElement[z].style) {
                            svgElement[z].style[s] = u + t
                        }
                    }
                    break
            }
        }
        n = n + l;
        if (n > m + 1) {
            clearInterval(intervalId);
            if (callback) {
                callback(svgElement)
            }
        }
    }

    intervalId = setInterval(f, e);
};
get._w._aw = function (b) {
    var a = 2;
    if (b < 0) {
        return 0
    }
    if (b > 1) {
        return 1
    }
    return Math.pow(b, a)
};
get._w._aC = function (c) {
    var a = 2;
    if (c < 0) {
        return 0
    }
    if (c > 1) {
        return 1
    }
    var b = a % 2 == 0 ? -1 : 1;
    return (b * (Math.pow(c - 1, a) + b))
};
get._w._aa = function (c) {
    var a = 2;
    if (c < 0) {
        return 0
    }
    if (c > 1) {
        return 1
    }
    c *= 2;
    if (c < 1) {
        return get._w._aw(c, a) / 2
    }
    var b = a % 2 == 0 ? -1 : 1;
    return (b / 2 * (Math.pow(c - 2, a) + b * 2))
};
get._w._as = function (a) {
    if (a < 0) {
        return 0
    }
    if (a > 1) {
        return 1
    }
    return -Math.cos(a * (Math.PI / 2)) + 1
};
get._w._aR = function (a) {
    if (a < 0) {
        return 0
    }
    if (a > 1) {
        return 1
    }
    return Math.sin(a * (Math.PI / 2))
};

/**
 *
 * @param a
 * @return {number}
 * @private
 */
get._w._az = function (a) {
    if (a < 0) {
        return 0
    }
    if (a > 1) {
        return 1
    }
    return -0.5 * (Math.cos(Math.PI * a) - 1)
};

get._w._7 = function (a) {
    if (a < 0) {
        return 0
    }
    if (a > 1) {
        return 1
    }
    return Math.pow(2, 10 * (a - 1))
};
get._w._aD = function (a) {
    if (a < 0) {
        return 0
    }
    if (a > 1) {
        return 1
    }
    return -Math.pow(2, -10 * a) + 1
};
get._w._aq = function (a) {
    if (a < 0) {
        return 0
    }
    if (a > 1) {
        return 1
    }
    return a < 0.5 ? 0.5 * Math.pow(2, 10 * (2 * a - 1)) : 0.5 * (-Math.pow(2, 10 * (-2 * a + 1)) + 2)
};
get._w._6 = function (a) {
    if (a < 0) {
        return 0
    }
    if (a > 1) {
        return 1
    }
    return -(Math.sqrt(1 - a * a) - 1)
};
get._w._aE = function (a) {
    if (a < 0) {
        return 0
    }
    if (a > 1) {
        return 1
    }
    return Math.sqrt(1 - (a - 1) * (a - 1))
};
get._w._0 = function (a) {
    if (a < 0) {
        return 0
    }
    if (a > 1) {
        return 1
    }
    return a < 1 ? -0.5 * (Math.sqrt(1 - a * a) - 1) : 0.5 * (Math.sqrt(1 - ((2 * a) - 2) * ((2 * a) - 2)) + 1)
};
get._w._aL = function (a) {
    if (a < 0) {
        return 0
    }
    if (a > 1) {
        return 1
    }
    if (a < (1 / 2.75)) {
        return 1 - 7.5625 * a * a
    } else {
        if (a < (2 / 2.75)) {
            return 1 - (7.5625 * (a - 1.5 / 2.75) * (a - 1.5 / 2.75) + 0.75)
        } else {
            if (a < (2.5 / 2.75)) {
                return 1 - (7.5625 * (a - 2.25 / 2.75) * (a - 2.25 / 2.75) + 0.9375)
            } else {
                return 1 - (7.5625 * (a - 2.625 / 2.75) * (a - 2.625 / 2.75) + 0.984375)
            }
        }
    }
};
get._w._5 = function (a) {
    if (a < 0) {
        return 0
    }
    if (a > 1) {
        return 1
    }
    return a * a * ((1.70158 + 1) * a - 1.70158)
};
get._w._aX = function (a) {
    if (a < 0) {
        return 0
    }
    if (a > 1) {
        return 1
    }
    return (a - 1) * (a - 1) * ((1.70158 + 1) * (a - 1) + 1.70158) + 1
};
get._w._9 = function (a) {
    if (a < 0) {
        return 0
    }
    if (a > 1) {
        return 1
    }
    return a < 0.5 ? 0.5 * (4 * a * a * ((2.5949 + 1) * 2 * a - 2.5949)) : 0.5 * ((2 * a - 2) * (2 * a - 2) * ((2.5949 + 1) * (2 * a - 2) + 2.5949) + 2)
};
get._w._4 = function (c) {
    var b = 2;
    var a = b * c;
    return a * Math.exp(1 - a)
};
get._w._A = function (c) {
    var a = 2;
    var b = 2;
    return Math.exp(-a * Math.pow(c, b))
};
if (!getOrgChart) {
    var getOrgChart = {}
}
getOrgChart.buttons = {
    add: '<g style="display:none;" class="get-btn" data-btn-id="[id]" data-btn-action="add" transform="matrix(0.14,0,0,0.14,0,0)"><rect style="opacity:0" x="0" y="0" height="300" width="300" /><path  fill="#686868" d="M149.996,0C67.157,0,0.001,67.158,0.001,149.997c0,82.837,67.156,150,149.995,150s150-67.163,150-150 C299.996,67.156,232.835,0,149.996,0z M149.996,59.147c25.031,0,45.326,20.292,45.326,45.325 c0,25.036-20.292,45.328-45.326,45.328s-45.325-20.292-45.325-45.328C104.671,79.439,124.965,59.147,149.996,59.147z M168.692,212.557h-0.001v16.41v2.028h-18.264h-0.864H83.86c0-44.674,24.302-60.571,40.245-74.843 c7.724,4.15,16.532,6.531,25.892,6.601c9.358-0.07,18.168-2.451,25.887-6.601c7.143,6.393,15.953,13.121,23.511,22.606h-7.275 v10.374v13.051h-13.054h-10.374V212.557z M218.902,228.967v23.425h-16.41v-23.425h-23.428v-16.41h23.428v-23.425H218.9v23.425 h23.423v16.41H218.902z"/></g>',
    edit: '<g style="display:none;" class="get-btn" data-btn-id="[id]" data-btn-action="edit" transform="matrix(0.14,0,0,0.14,0,0)"><rect style="opacity:0" x="0" y="0" height="300" width="300" /><path fill="#686868" d="M149.996,0C67.157,0,0.001,67.161,0.001,149.997S67.157,300,149.996,300s150.003-67.163,150.003-150.003 S232.835,0,149.996,0z M221.302,107.945l-14.247,14.247l-29.001-28.999l-11.002,11.002l29.001,29.001l-71.132,71.126 l-28.999-28.996L84.92,186.328l28.999,28.999l-7.088,7.088l-0.135-0.135c-0.786,1.294-2.064,2.238-3.582,2.575l-27.043,6.03 c-0.405,0.091-0.817,0.135-1.224,0.135c-1.476,0-2.91-0.581-3.973-1.647c-1.364-1.359-1.932-3.322-1.512-5.203l6.027-27.035 c0.34-1.517,1.286-2.798,2.578-3.582l-0.137-0.137L192.3,78.941c1.678-1.675,4.404-1.675,6.082,0.005l22.922,22.917 C222.982,103.541,222.982,106.267,221.302,107.945z"/></g>',
    del: '<g style="display:none;" class="get-btn" data-btn-id="[id]" data-btn-action="del" transform="matrix(0.14,0,0,0.14,0,0)"><rect style="opacity:0" x="0" y="0" height="300" width="300" /><path fill="#686868" d="M112.782,205.804c10.644,7.166,23.449,11.355,37.218,11.355c36.837,0,66.808-29.971,66.808-66.808 c0-13.769-4.189-26.574-11.355-37.218L112.782,205.804z"/> <path stroke="#686868" fill="#686868" d="M150,83.542c-36.839,0-66.808,29.969-66.808,66.808c0,15.595,5.384,29.946,14.374,41.326l93.758-93.758 C179.946,88.926,165.595,83.542,150,83.542z"/><path stroke="#686868" fill="#686868" d="M149.997,0C67.158,0,0.003,67.161,0.003,149.997S67.158,300,149.997,300s150-67.163,150-150.003S232.837,0,149.997,0z M150,237.907c-48.28,0-87.557-39.28-87.557-87.557c0-48.28,39.277-87.557,87.557-87.557c48.277,0,87.557,39.277,87.557,87.557 C237.557,198.627,198.277,237.907,150,237.907z"/></g>',
    details: '<g style="display:none;" class="get-btn" data-btn-id="[id]" data-btn-action="details" transform="matrix(0.14,0,0,0.14,0,0)"><rect style="opacity:0" x="0" y="0" height="300" width="300" /><path fill="#686868" d="M139.414,96.193c-22.673,0-41.056,18.389-41.056,41.062c0,22.678,18.383,41.062,41.056,41.062 c22.678,0,41.059-18.383,41.059-41.062C180.474,114.582,162.094,96.193,139.414,96.193z M159.255,146.971h-12.06v12.06 c0,4.298-3.483,7.781-7.781,7.781c-4.298,0-7.781-3.483-7.781-7.781v-12.06h-12.06c-4.298,0-7.781-3.483-7.781-7.781 c0-4.298,3.483-7.781,7.781-7.781h12.06v-12.063c0-4.298,3.483-7.781,7.781-7.781c4.298,0,7.781,3.483,7.781,7.781v12.063h12.06 c4.298,0,7.781,3.483,7.781,7.781C167.036,143.488,163.555,146.971,159.255,146.971z"/><path stroke="#686868" fill="#686868" d="M149.997,0C67.157,0,0.001,67.158,0.001,149.995s67.156,150.003,149.995,150.003s150-67.163,150-150.003 S232.836,0,149.997,0z M225.438,221.254c-2.371,2.376-5.48,3.561-8.59,3.561s-6.217-1.185-8.593-3.561l-34.145-34.147 c-9.837,6.863-21.794,10.896-34.697,10.896c-33.548,0-60.742-27.196-60.742-60.744c0-33.548,27.194-60.742,60.742-60.742 c33.548,0,60.744,27.194,60.744,60.739c0,11.855-3.408,22.909-9.28,32.256l34.56,34.562 C230.185,208.817,230.185,216.512,225.438,221.254z"/></g>',
    expColl: '<circle data-btn-id="[id]" data-btn-action="expColl" cx="[xa]" cy="[ya]" r="20" class="get-btn" /><line data-btn-id="[id]" data-btn-action="expColl" x1="[xam13]" y1="[ya]" x2="[xap13]" y2="[ya]" class="get-btn get-btn-line" /><line style="display:[display]" data-btn-id="[id]" data-btn-action="expColl" x1="[xa]" y1="[yam13]" x2="[xa]" y2="[yap13]" class="get-btn get-btn-line" />'
};
getOrgChart.buttons.draw = function () {
    var a = [];
    a.push(getOrgChart.buttons.details);
    a.push(getOrgChart.buttons.edit);
    a.push(getOrgChart.buttons.add);
    a.push(getOrgChart.buttons.del);
    return a
};
if (typeof(get) == "undefined") {
    get = {}
}

/**
 * User agent engine detector.
 *
 * @returns {{msie: boolean, webkit: boolean, mozilla: boolean, opera: boolean}|*}
 * @private
 */
get._userAgent = function () {
    if (getOrgChart._userAgent) {
        return getOrgChart._userAgent
    }
    var g = navigator.userAgent;
    g = g.toLowerCase();
    var f = /(webkit)[ \/]([\w.]+)/;
    var e = /(opera)(?:.*version)?[ \/]([\w.]+)/;
    var d = /(msie) ([\w.]+)/;
    var c = /(mozilla)(?:.*? rv:([\w.]+))?/;
    var b = f.exec(g) || e.exec(g) || d.exec(g) || g.indexOf("compatible") < 0 && c.exec(g) || [];
    var a = {browser: b[1] || "", version: b[2] || "0"};
    getOrgChart._userAgent = {
        msie: a.browser == "msie",
        webkit: a.browser == "webkit",
        mozilla: a.browser == "mozilla",
        opera: a.browser == "opera"
    };

    return getOrgChart._userAgent
};

getOrgChart.util = {};

/**
 * Parses viewbox coordinates
 *
 * @example output
 *     [-687.193396226415, -1065, 2574.38679245283, 1410]
 *
 * @param {Object} _p
 * @returns {Array}
 * @private
 */
getOrgChart.util._getViewBoxCoords = function (_p) {
    var viewBox = _p._svgElement.getAttribute("viewBox");
    viewBox = "[" + viewBox + "]";

    return eval(viewBox.replace(/\ /g, ", "));
};

/**
 * Returns element coordinates as array
 *
 * @example
 *   Element: <g data-node-id="140" class="get-level-2 " transform="matrix(1,0,0,1,0,-940)">...
 *   Returns: [1, 0, 0, 1, 0, -940]
 *
 * @param {Element} node
 * @returns {Array}
 * @private
 */
getOrgChart.util._getElementCoordinatesAsArray = function (node) {
    var transform = node.getAttribute("transform");
    transform = transform.replace("matrix", "").replace("(", "").replace(")", "");
    transform = getOrgChart.util._replaceSpaces(transform);
    transform = "[" + transform + "]";

    return eval(transform.replace(/\ /g, ", "))
};

/**
 * Replace spaces in the string.
 *
 * @example
 *     1, 0, 0, 1, 0, -470 => 1,0,0,1,0,-470
 *
 * @param {String} coordinates
 * @returns {String}
 * @private
 */
getOrgChart.util._replaceSpaces = function (coordinates) {
    return coordinates.replace(/^\s+|\s+$/g, "");
};

/**
 * Checks if string exists in array.
 *
 * @param {Array} arr
 * @param {String} string
 * @returns {boolean}
 * @private
 */
getOrgChart.util._existsInArray = function (arr, string) {
    var b = arr.length;
    while (b--) {
        if (arr[b] === string) {
            return true;
        }
    }

    return false;
};

getOrgChart.util._X = function (b) {
    var a = "1";
    while (b[a]) {
        a++
    }
    return a;
};

/**
 * Parse param from uri.
 *
 * @example theme name
 *    /tree/chart?colorScheme=red => red
 *
 * @param {String} paramName
 * @returns {String|null}
 * @private
 */
getOrgChart.util.parseParamFromUri = function (paramName) {
    var h = [], c;
    var d = window.location.href.slice(window.location.href.indexOf("?") + 1).split("&");
    for (var e = 0; e < d.length; e++) {
        c = d[e].split("=");
        if (c && c.length == 2 && c[0] === paramName) {
            var a, b;
            var g = /(%[^%]{2})/;
            while ((encodedChar = g.exec(c[1])) != null && encodedChar.length > 1 && encodedChar[1] != "") {
                a = parseInt(encodedChar[1].substr(1), 16);
                b = String.fromCharCode(a);
                c[1] = c[1].replace(encodedChar[1], b)
            }

            return decodeURIComponent(escape(c[1]))
        }
    }

    return null;
};
getOrgChart.util._zr = function (c) {
    if (window.ActiveXObject) {
        var a = new ActiveXObject("Microsoft.XMLDOM");
        a.async = "false";
        a.loadXML(c)
    } else {
        var b = new DOMParser();
        var a = b.parseFromString(c, "text/xml")
    }
    return a
};
getOrgChart.util._ad = function (a) {
    if (a == null || typeof(a) == "undefined" || a == "" || a == -1) {
        return true
    }
    return false
};
getOrgChart.util._notEmpty = function (a) {
    return (typeof a !== "undefined" && a !== null)
};
getOrgChart.prototype.showDetailsView = function (d) {
    var h = this.nodes[d];
    var f = (h.parent == this._rootNode);
    var b = function (p, n, q) {
        var l = f ? 'style="display:none;"' : "";
        var r = "<select " + l + 'class="get-oc-select-parent"><option value="' + p + '">--select parent--</option>';
        var o = null;
        for (var k in n) {
            o = n[k];
            if (h == o) {
                continue
            }
            var s = "";
            for (i = 0; i < q.length; i++) {
                var m = q[i];
                if (!o.data || !o.data[m]) {
                    continue
                }
                if (s) {
                    s = s + ", " + o.data[m]
                } else {
                    s += o.data[m]
                }
            }
            if (o.id == p) {
                r += '<option selected="selected" value="' + o.id + '">' + s + "</option>"
            } else {
                r += '<option value="' + o.id + '">' + s + "</option>"
            }
        }
        r += "</select>";
        return r
    };
    var a = function (l, k) {
        var n = '<select class="get-oc-labels"><option value="">--other--</option>';
        var m;
        for (i = 0; i < k.length; i++) {
            if (!getOrgChart.util._existsInArray(l, k[i])) {
                m += '<option value="' + k[i] + '">' + k[i] + "</option>"
            }
        }
        n += m;
        n += "</select>";
        if (!m) {
            n = ""
        }
        return n
    };
    var c = "";
    var g = [];
    c += b(h.pid, this.nodes, this.config.primaryFields);
    c += getOrgChart.DETAILS_VIEW_ID_INPUT.replace("[personId]", h.id);
    for (label in h.data) {
        c += getOrgChart.DETAILS_VIEW_INPUT_HTML.replace(/\[label]/g, label).replace("[value]", h.data[label]);
        g.push(label)
    }
    c += a(g, this._af);
    this._p._n.innerHTML = c;
    var e = h.getImageUrl ? h.getImageUrl() : "";
    if (e) {
        this._p._h.innerHTML = getOrgChart.DETAILS_VIEW_ID_IMAGE.replace("[src]", e)
    } else {
        this._p._h.innerHTML = getOrgChart.DETAILS_VIEW_USER_LOGO
    }
    this._u();
    var j = this.config.customize[h.id] && this.config.customize[h.id].theme ? getOrgChart.themes[this.config.customize[h.id].theme].toolbarHeight : this.theme.toolbarHeight;
    this._p._graphArea.style.top = "-9999px";
    this._p._graphArea.style.left = "-9999px";
    this._p._graphAreaInvisible1.style.top = j + "px";
    this._p._graphAreaInvisible1.style.left = "0px";
    this._p._graphAreaInvisible2.style.top = "-9999px";
    this._p._graphAreaInvisible2.style.left = "-9999px";
    this._p._graphAreaInvisible2.innerHTML = "";
    this._p._n.style.opacity = 0;
    this._p._h.style.opacity = 0;
    get._w(this._p._h, {left: -100, opacity: 0}, {left: 20, opacity: 1}, 200, get._w._aD);
    get._w(this._p._zg, {top: 0}, {top: -j}, 200, get._w._aR);
    get._w(this._p._n, {opacity: 0}, {opacity: 1}, 400, get._w._aD)
};
getOrgChart.prototype._u = function () {
    var a = this._p._G();
    if (this._p._V()) {
        this._a(this._p._V(), "change", this._y)
    }
};
getOrgChart.prototype._y = function (l, a) {
    var m = this._p._F();
    var k = this._p._V();
    var h = k.value;
    for (var c = 0; c < k.options.length; c++) {
        if (h == k.options[c].value) {
            k.options[c] = null
        }
    }
    if (!h) {
        return
    }
    var b = this._p._n.innerHTML;
    var e = getOrgChart.DETAILS_VIEW_INPUT_HTML.replace(/\[label]/g, h).replace("[value]", "");
    var d = b.indexOf('<select class="get-oc-labels">');
    this._p._n.innerHTML = b.substring(0, d) + e + b.substring(d, b.length);
    var f = this._p._G();
    var g = 1;
    for (c in m) {
        f[g].value = m[c];
        g++
    }
    this._u()
};
getOrgChart.prototype._a5 = function (e, a) {
    if (this._l) {
        var b = this._p._T().value;
        var d;
        if (this._p._B() && this._p._B().value) {
            d = this._p._B().value
        }
        var c = this._p._F();
        this.updateNode(b, d, c);
        this._l = false
    }
    this.showMainView()
};
getOrgChart.prototype._zd = function () {
    this.showGridView()
};
getOrgChart.prototype.showGridView = function () {
    var a = '<table cellpadding="0" cellspacing="0" border="0">';
    a += "<tr>";
    a += "<th>ID</th><th>Parent ID</th>";
    for (i = 0; i < this._af.length; i++) {
        var c = this._af[i];
        a += "<th>" + c + "</th>"
    }
    a += "</tr>";
    for (var b in this.nodes) {
        var d = this.nodes[b];
        var f = (i % 2 == 0) ? "get-even" : "get-odd";
        var e = d.data;
        a += '<tr class="' + f + '">';
        a += "<td>" + d.id + "</td>";
        a += "<td>" + d.pid + "</td>";
        for (j = 0; j < this._af.length; j++) {
            var c = this._af[j];
            var g = e[c];
            a += "<td>";
            a += g ? g : "&nbsp;";
            a += "</td>"
        }
        a += "</tr>"
    }
    a += "</table>";
    this._p._graphAreaInvisible2.innerHTML = a;
    this._p._graphArea.style.top = "-9999px";
    this._p._graphArea.style.left = "-9999px";
    this._p._graphAreaInvisible1.style.top = "-9999px";
    this._p._graphAreaInvisible1.style.left = "-9999px";
    this._p._graphAreaInvisible2.style.top = this.theme.toolbarHeight + "px";
    this._p._graphAreaInvisible2.style.left = "0px";
    get._w(this._p._graphAreaInvisible2, {left: 100, opacity: 0}, {left: 0, opacity: 1}, 200, get._w._aD);
    get._w(this._p._zg, {top: 0}, {top: -this.theme.toolbarHeight * 2}, 200, get._w._aR)
};
getOrgChart.prototype._zc = function (b, a) {
    this.showMainView()
};

/**
 * Shows main view.
 */
getOrgChart.prototype.showMainView = function () {
    this._p._graphArea.style.top = this.theme.toolbarHeight + "px";
    this._p._graphArea.style.left = "0px";
    this._p._graphAreaInvisible1.style.top = "-9999px";
    this._p._graphAreaInvisible1.style.left = "-9999px";
    this._p._graphAreaInvisible2.style.top = "-9999px";
    this._p._graphAreaInvisible2.style.left = "-9999px";
    this._p._graphAreaInvisible2.innerHTML = "";
    if (this.config.enableSearch) {
        this._p._searchInput.focus()
    }
    if (this._p._zg.style.top != 0 && this._p._zg.style.top != "" && this._p._zg.style.top != "0px") {
        get._w(this._p._zg, {top: -46}, {top: 0}, 200, get._w._aR)
    }
};

getOrgChart.prototype._aO = function (b, a) {
    this.print()
};
getOrgChart.prototype.print = function () {
    var b = this, d = this._p.element, k = this._p._searchArea, g = [], h = d.parentNode, j = k.style.display, a = document.body, c = a.childNodes, e;
    if (b._ae) {
        return
    }
    b._ae = true;
    for (e = 0; e < c.length; e++) {
        var f = c[e];
        if (f.nodeType === 1) {
            g[e] = f.style.display;
            f.style.display = "none"
        }
    }
    k.style.display = "none";
    a.appendChild(d);
    window.focus();
    window.print();
    setTimeout(function () {
        h.appendChild(d);
        for (e = 0; e < c.length; e++) {
            var i = c[e];
            if (i.nodeType === 1) {
                i.style.display = g[e]
            }
        }
        k.style.display = j;
        b._ae = false
    }, 1000)
};

/**
 * Zoom in the diagram.
 *
 * @private
 */
getOrgChart.prototype._zoomIn = function () {
    this.zoom(1, true)
};

/**
 * Zoom out the diagram.
 *
 * @private
 */
getOrgChart.prototype._zoomOut = function () {
    this.zoom(-1, true)
};
getOrgChart.prototype._a7 = function (c, b) {
    this._p._g = undefined;
    var a = b[0].wheelDelta ? b[0].wheelDelta / 40 : b[0].detail ? -b[0].detail : 0;
    if (a) {
        this.zoom(a, false)
    }
    return b[0].preventDefault() && false
};
getOrgChart.prototype._au = function (g, d) {
    this._p._g = undefined;
    this._aF.mouseLastX = (d[0].pageX - this._p._graphArea.offsetLeft);
    this._aF.mouseLastY = (d[0].pageY - this._p._graphArea.offsetTop);
    this._aF.dragged = true;
    if (this._aF.dragStart) {
        var a = Math.abs(this._aF.dragStart.x - this._aF.mouseLastX);
        var b = Math.abs(this._aF.dragStart.y - this._aF.mouseLastY);
        this._aF._q = a + b;
        this._p._graphArea.style.cursor = "move";
        var j = getOrgChart.util._getViewBoxCoords(this._p);
        var k = j[2] / this._areaWidth;
        var e = j[3] / this._areaHeight;
        var c = k > e ? k : e;
        j[0] = -((this._aF.mouseLastX - this._aF.dragStart.x) * c) + this._aF.dragStart.viewBoxLeft;
        j[1] = -((this._aF.mouseLastY - this._aF.dragStart.y) * c) + this._aF.dragStart.viewBoxTop;
        j[0] = parseInt(j[0]);
        j[1] = parseInt(j[1]);
        this._p._svgElement.setAttribute("viewBox", j.toString())
    }
    if (this.config.enableMove) {
        var i = this;
        if (this._ak) {
            clearTimeout(this._ak);
            this._ak = setTimeout(f, 4000);
            return
        }
        function h() {
            get._w(i._p._navigateArrowRight, {right: -30, opacity: 0}, {right: 1, opacity: 0.8}, 500, get._w._7);
            get._w(i._p._navigateArrowLeft, {left: -30, opacity: 0}, {left: 1, opacity: 0.8}, 500, get._w._7);
            get._w(i._p._navigateArrowUp, {top: 19, opacity: 0}, {top: 49, opacity: 0.8}, 500, get._w._7);
            get._w(i._p._navigateArrowDown, {bottom: -30, opacity: 0}, {bottom: 1, opacity: 0.8}, 500, get._w._7)
        }

        function f() {
            get._w(i._p._navigateArrowRight, {right: 1, opacity: 0.8}, {right: -30, opacity: 0}, 500, get._w._7, function () {
                i._ak = null
            });
            get._w(i._p._navigateArrowLeft, {left: 1, opacity: 0.8}, {left: -30, opacity: 0}, 500, get._w._7, function () {
                i._ak = null
            });
            get._w(i._p._navigateArrowUp, {top: 49, opacity: 0.8}, {top: 19, opacity: 0}, 500, get._w._7, function () {
                i._ak = null
            });
            get._w(i._p._navigateArrowDown, {bottom: 1, opacity: 0.8}, {bottom: -30, opacity: 0}, 500, get._w._7, function () {
                i._ak = null
            })
        }

        h();
        this._ak = setTimeout(f, 4000)
    }
};
getOrgChart.prototype._an = function (b, a) {
    document.body.style.mozUserSelect = document.body.style.webkitUserSelect = document.body.style.userSelect = "none";
    this._aF.mouseLastX = (a[0].pageX - this._p._graphArea.offsetLeft);
    this._aF.mouseLastY = (a[0].pageY - this._p._graphArea.offsetTop);
    var c = getOrgChart.util._getViewBoxCoords(this._p);
    this._aF.dragStart = {x: this._aF.mouseLastX, y: this._aF.mouseLastY, viewBoxLeft: c[0], viewBoxTop: c[1]};
    this._aF.dragged = false;
    this._aF._q = 0
};
getOrgChart.prototype._aj = function (b, a) {
    this._aF.dragStart = null;
    this._p._graphArea.style.cursor = "default"
};

/**
 *
 * @param {Number} zoomInOrOut If 1 => "zoom in", if -1 => "zoom out"
 * @param {Boolean} a
 * @returns {boolean}
 */
getOrgChart.prototype.zoom = function (zoomInOrOut, a) {
    if (this._zo) {
        return false;
    }

    this._zo = true;
    var f = this;
    var g = getOrgChart.util._getViewBoxCoords(this._p);
    var c = g.slice(0);
    var e = g[2];
    var d = g[3];

    if (zoomInOrOut > 0) {
        g[2] = g[2] / (getOrgChart.SCALE_FACTOR * 1.2);
        g[3] = g[3] / (getOrgChart.SCALE_FACTOR * 1.2)
    } else {
        g[2] = g[2] * (getOrgChart.SCALE_FACTOR * 1.2);
        g[3] = g[3] * (getOrgChart.SCALE_FACTOR * 1.2)
    }
    g[0] = g[0] - (g[2] - e) / 2;
    g[1] = g[1] - (g[3] - d) / 2;
    if (a) {
        get._w(this._p._svgElement, {viewBox: c}, {viewBox: g}, 500, get._w._aX, function () {
            f._zo = false;
        })
    } else {
        this._p._svgElement.setAttribute("viewBox", g.toString());
        this._zo = false;
    }

    return false;
};

getOrgChart.prototype._al = function (c, b) {
    if (c.className.indexOf("get-disabled") > -1) {
        return false;
    }

    var self = this;
    clearTimeout(this._searchResults.timer);
    this._searchResults.timer = setTimeout(function () {
        self._searchResults.currentIndex++;
        self._changeClassesForNavigateLinkToFoundNodes();
        self._highlightNodeIfFound()
    }, 100)
};

getOrgChart.prototype._aM = function (c, b) {
    if (c.className.indexOf("get-disabled") > -1) {
        return false;
    }

    var self = this;

    clearTimeout(this._searchResults.timer);
    this._searchResults.timer = setTimeout(function () {
        self._searchResults.currentIndex--;
        self._changeClassesForNavigateLinkToFoundNodes();
        self._highlightNodeIfFound();
    }, 100)
};

/**
 * Search node.
 *
 * @param {Element} element
 * @param {KeyboardEvent} event
 * @private
 */
getOrgChart.prototype._search = function (element, event) {
    var self = this;

    clearTimeout(this._searchResults.timer);

    this._searchResults.timer = setTimeout(function () {
        // Array of the "getOrgChart.node" objects.
        //self._searchResults.found = self._searchNodesByQuery(self._p._searchInput.value);
        self._searchResults.found = self._searchNodesByQueryDTree(self._p._searchInput.value);
        self._searchResults.currentIndex = 0;
        self._changeClassesForNavigateLinkToFoundNodes();
        self._ar();
        self._highlightNodeIfFound()
    }, 500);
};

/**
 *
 * @param c
 * @param b
 * @private
 */
getOrgChart.prototype._zq = function (c, b) {
    var self = this;

    clearTimeout(this._searchResults.timer);
    this._searchResults.timer = setTimeout(function () {
        self._searchResults.currentIndex = 0;
        self._searchResults.found = self._searchNodesByQuery(self._p._searchInput.value);
        self._ar();
        self._changeClassesForNavigateLinkToFoundNodes();
        self._highlightNodeIfFound()
    }, 100);
};

/**
 * Highlight Node If Found
 *
 * @private
 */
getOrgChart.prototype._highlightNodeIfFound = function () {
    if (this._searchResults.found.length) {
        // this.highlightNode(this._searchResults.found[this._searchResults.currentIndex].node.id)
        this.highlightDTreeNode(this._searchResults.found[this._searchResults.currentIndex].node.id)
    }
};

/**
 * Go to the node during search.
 *
 * @param {Number} nodeId
 */
getOrgChart.prototype.highlightDTreeNode = function (nodeId) {
    var self = this;

    function b() {
        var node = self.flatNodes[nodeId];
        var viewBoxCoords = getOrgChart.util._getViewBoxCoords(self._p);

        var x = node.x - self.initialViewBoxMatrix[2] / 2 + node.w / 2;
        if (self._p._viewBoxTranslate) {
            // Because of root <g> element contains transform property need to replace the value from x.
            x += self._p._viewBoxTranslate - node.w / 2;
        }

        var y = node.y - self.initialViewBoxMatrix[3] / 2 + node.h / 2;

        self.move([x, y, self.initialViewBoxMatrix[2], self.initialViewBoxMatrix[3]], null, function () {
            // Founded element animation.
            var movingNode = self._p.getNodeById(nodeId);

            if (movingNode) {
                movingNode.classList.add('foundNode');

                setTimeout(function () {
                    movingNode.classList.remove('foundNode');
                }, 1000);
            }
        })
    }

    if (this.isCollapsed(this.flatNodes[nodeId])) {
        this.expand(this.flatNodes[nodeId].parent, b);
    } else {
        b();
    }
};

/**
 * Go to the node during search.
 *
 * @param {Number} nodeId
 */
getOrgChart.prototype.highlightNode = function (nodeId) {
    var self = this;

    function b() {
        var node = self.nodes[nodeId];
        var viewBoxCoords = getOrgChart.util._getViewBoxCoords(self._p);
        var x = node.x - self.initialViewBoxMatrix[2] / 2 + node.w / 2;
        var y = node.y - self.initialViewBoxMatrix[3] / 2 + node.h / 2;
        self.move([x, y, self.initialViewBoxMatrix[2], self.initialViewBoxMatrix[3]], null, function () {
            var i = self._p.getNodeById(nodeId);
            var h = getOrgChart.util._getElementCoordinatesAsArray(i);
            var j = h.slice(0);
            j[0] = getOrgChart.HIGHLIGHT_SCALE_FACTOR;
            j[3] = getOrgChart.HIGHLIGHT_SCALE_FACTOR;
            j[4] = j[4] - ((node.w / 2) * (getOrgChart.HIGHLIGHT_SCALE_FACTOR - 1));
            j[5] = j[5] - ((node.h / 2) * (getOrgChart.HIGHLIGHT_SCALE_FACTOR - 1));
            get._w(i, {transform: h}, {transform: j}, 200, get._w._as, function (k) {
                get._w(k[0], {transform: j}, {transform: h}, 200, get._w._aR, function () {
                })
            })
        })
    }

    if (this.isCollapsed(this.nodes[nodeId])) {
        this.expand(this.nodes[nodeId].parent, b);
    } else {
        b();
    }
};

getOrgChart.prototype._ar = function (a) {
};

/**
 * Changes classes for previous and next arrow links when found some elements.
 *
 * @private
 */
getOrgChart.prototype._changeClassesForNavigateLinkToFoundNodes = function () {
    if ((this._searchResults.currentIndex < this._searchResults.found.length - 1) && (this._searchResults.found.length != 0)) {
        this._p._linkNextFoundNode.className = this._p._linkNextFoundNode.className.replace(" get-disabled", "");
    } else {
        if (this._p._linkNextFoundNode.className.indexOf(" get-disabled") == -1) {
            this._p._linkNextFoundNode.className = this._p._linkNextFoundNode.className + " get-disabled";
        }
    }
    if ((this._searchResults.currentIndex != 0) && (this._searchResults.found.length != 0)) {
        this._p._linkPrevFoundNode.className = this._p._linkPrevFoundNode.className.replace(" get-disabled", "");
    } else {
        if (this._p._linkPrevFoundNode.className.indexOf(" get-disabled") == -1) {
            this._p._linkPrevFoundNode.className = this._p._linkPrevFoundNode.className + " get-disabled";
        }
    }
};

/**
 * Search graph nodes by query string.
 *
 * @param {String} query
 * @returns {Array} of getOrgChart.node
 * @private
 */
getOrgChart.prototype._searchNodesByQueryDTree = function (query) {
    var found = [];

    if (query === '') {
        return found;
    }

    if (query.toLowerCase) {
        query = query.toLowerCase();
    }

    //for (var node in this.flatNodes) {
    for (var node in this.flatNodes) {
        var _currNode = this.flatNodes[node];

        if (_currNode.data.hidden === true
            || (_currNode.data.extra.isMarriage && _currNode.data.extra.isMarriage === true)
        ) {
            // Skip relation nodes.
            continue;
        }

        for (var m in _currNode.data) {
            if (m == this.config.photoFields[0]) {
                continue;
            }

            var foundIndex = -1;

            if (getOrgChart.util._notEmpty(_currNode) && getOrgChart.util._notEmpty(_currNode.data)) {
                var lowerCaseQuery;
                if (m === 'extra') {
                    //  birth
                    if (_currNode['data'][m]['birth'] !== null) {
                        lowerCaseQuery = _currNode['data'][m]['birth'].toString().toLowerCase();
                        foundIndex = lowerCaseQuery.indexOf(query);
                        if (foundIndex > -1) {
                            found.push({indexOf: foundIndex, node: _currNode});
                            break;
                        }
                    }

                    //  death
                    if (_currNode['data'][m]['death'] !== null) {
                        lowerCaseQuery = _currNode['data'][m]['death'].toString().toLowerCase();
                        foundIndex = lowerCaseQuery.indexOf(query);
                        if (foundIndex > -1) {
                            found.push({indexOf: foundIndex, node: _currNode});
                            break;
                        }
                    }

                    //  last_name
                    if (_currNode['data'][m]['last_name'] !== null) {
                        lowerCaseQuery = _currNode['data'][m]['last_name'].toString().toLowerCase();
                        foundIndex = lowerCaseQuery.indexOf(query);
                        if (foundIndex > -1) {
                            found.push({indexOf: foundIndex, node: _currNode});
                            break;
                        }
                    }

                    //  shortName
                    if (_currNode['data'][m]['shortName'] !== null) {
                        lowerCaseQuery = _currNode['data'][m]['shortName'].toString().toLowerCase();
                        foundIndex = lowerCaseQuery.indexOf(query);
                        if (foundIndex > -1) {
                            found.push({indexOf: foundIndex, node: _currNode});
                            break;
                        }
                    }
                }
            }
        }
    }

    function sortFunc(a, b) {
        if (a.indexOf < b.indexOf) {
            return -1;
        }

        if (a.indexOf > b.indexOf) {
            return 1;
        }

        return 0;
    }

    found.sort(sortFunc);

    return found;
};

/**
 * Search graph nodes by query string.
 *
 * @param {String} query
 * @returns {Array} of getOrgChart.node
 * @private
 */
getOrgChart.prototype._searchNodesByQuery = function (query) {
    var found = [];

    if (query == "") {
        return found;
    }

    if (query.toLowerCase) {
        query = query.toLowerCase();
    }

    for (var b in this.nodes) {

        var f = this.nodes[b];
        for (var m in f.data) {
            if (m == this.config.photoFields[0]) {
                continue;
            }

            var c = -1;
            if (getOrgChart.util._notEmpty(f) && getOrgChart.util._notEmpty(f.data[m])) {
                var d = f.data[m].toString().toLowerCase();
                c = d.indexOf(query);
            }

            if (c > -1) {
                found.push({indexOf: c, node: f});
                break;
            }
        }
    }

    function sortFunc(a, b) {
        if (a.indexOf < b.indexOf) {
            return -1;
        }

        if (a.indexOf > b.indexOf) {
            return 1;
        }

        return 0;
    }

    found.sort(sortFunc);

    return found;
};

getOrgChart.prototype._aZ = function (g, a) {
    var c = g.getAttribute("data-node-id");
    var e = this.nodes[c];
    var f = e.x + e.w - 15;
    var d = e.x - 30;
    var h = e.y - 30;
    var b = e.y + e.h - 15;
    if (this.config.enableDetailsView) {
        this._zw("details", f, h, c)
    }
    if (this.config.enableEdit) {
        this._zw("add", f, b, c);
        this._zw("edit", d, h, c);
        this._zw("del", d, b, c)
    }
};
getOrgChart.prototype._zw = function (a, d, e, c) {
    var b = this._p.getButtonByType(a);
    b.style.display = "block";
    b.setAttribute("transform", "matrix(0.14,0,0,0.14," + d + "," + e + ")");
    b.setAttribute("data-btn-id", c);
    this._a(b, "click", this._aQ)
};
getOrgChart.prototype._aA = function (d, a) {
    var b = d.getAttribute("data-node-id");
    var c = this.nodes[b];
    if (!this._nodeOnClick("clickNodeEvent", {node: c})) {
        return;
    }
};
getOrgChart.prototype._aQ = function (d, b) {
    var c = d.getAttribute("data-btn-id");
    var a = d.getAttribute("data-btn-action");
    if (a == "del") {
        this.removeNode(c)
    } else {
        if (a == "add") {
            this.insertNode(c)
        } else {
            if (a == "details") {
                this.showDetailsView(c)
            } else {
                if (a == "edit") {
                    this.showEditView(c)
                } else {
                    if (a == "expColl") {
                        this.expandOrCollapse(c)
                    }
                }
            }
        }
    }
};
getOrgChart.prototype.showEditView = function (a) {
    this._l = true;
    this.showDetailsView(a)
};
getOrgChart.prototype.expand = function (b, a) {
    b.collapsed = getOrgChart.EXPANDED;
    if ((b.parent == this._rootNode) || (b.parent == null)) {
        this.loadFromJSON(this.nodes, true, a)
    } else {
        this.expand(b.parent, a)
    }
};
getOrgChart.prototype.expandOrCollapse = function (a) {
    var c = this;
    var b = this.nodes[a];
    this._aS = {id: a, old_x: b.x, old_y: b.y};
    if (b.collapsed == getOrgChart.EXPANDED) {
        b.collapsed = getOrgChart.COLLAPSED
    } else {
        b.collapsed = getOrgChart.EXPANDED
    }
    this.loadFromJSON(this.nodes, true, function () {
        if (b.collapsed == getOrgChart.EXPANDED) {
            c.moveToMostDeepChildForNode(b)
        }
        c._nodeOnClick("updatedEvent")
    })
};
getOrgChart.prototype.moveToMostDeepChildForNode = function (b) {
    var c = getOrgChart.util._getViewBoxCoords(this._p);
    b = b.getMostDeepChild(this.nodes);
    var d = this.config.siblingSeparation / 2;
    var e = this.config.levelSeparation / 2;
    var a = c.slice(0);
    switch (this.config.orientation) {
        case getOrgChart.RO_TOP:
        case getOrgChart.RO_TOP_PARENT_LEFT:
            a[1] = ((b.y + b.h)) - c[3] + e;
            if (c[1] < a[1]) {
                this.move(a)
            }
            break;
        case getOrgChart.RO_BOTTOM:
        case getOrgChart.RO_BOTTOM_PARENT_LEFT:
            a[1] = b.y - e;
            if (c[1] > a[1]) {
                this.move(a)
            }
            break;
        case getOrgChart.RO_RIGHT:
        case getOrgChart.RO_RIGHT_PARENT_TOP:
            a[0] = b.x - d;
            if (c[0] > a[0]) {
                this.move(a)
            }
            break;
        case getOrgChart.RO_LEFT:
        case getOrgChart.RO_LEFT_PARENT_TOP:
            a[0] = ((b.x + b.w)) - c[2] + d;
            if (c[0] < a[0]) {
                this.move(a)
            }
            break
    }
};

getOrgChart.prototype.insertNode = function (d) {
    var f = this;
    var e = this.nodes[d];
    this._aS = {id: d, old_x: e.x, old_y: e.y};
    var b = getOrgChart.util._X(this.nodes);
    var a = {};
    this.config.primaryFields.forEach(function (g) {
        a[g] = g
    });
    var c = this._initNode(b, d, a, false);
    if (!this._nodeOnClick("insertNodeEvent", {node: c})) {
        this.removeNode(c.id);
        return
    }
    c.x = e.x;
    c.y = e.y;
    this.loadFromJSON(this.nodes, true, function () {
        f.moveToMostDeepChildForNode(f.nodes[c.id]);
        f._nodeOnClick("updatedEvent")
    });
    return c
};
getOrgChart.prototype.removeNode = function (b) {
    var e = this;
    if (!this._nodeOnClick("removeNodeEvent", {id: b})) {
        return
    }
    var a = this.nodes[b];
    var d = a.parent;
    for (j = 0; j < a.children.length; j++) {
        a.children[j].pid = d.id
    }
    var c = this._p.getNodeById(b);
    c.parentNode.removeChild(c);
    delete this.nodes[b];
    this.loadFromJSON(this.nodes, true, function () {
        e._nodeOnClick("updatedEvent")
    })
};
getOrgChart.prototype.updateNode = function (b, d, a) {
    var e = this;
    var c = this.nodes[b];
    c.pid = d;
    c.data = a;
    if (this._nodeOnClick("updateNodeEvent", {node: c})) {
        this.loadFromJSON(this.nodes, true, function () {
            e._nodeOnClick("updatedEvent")
        })
    }
};

/**
 *
 * @param {Number} id
 * @param {Number} parentId
 * @param {Object} data
 * @param {Number} status
 * @returns {*}
 * @private
 */
getOrgChart.prototype._initNode = function (id, parentId, data, status) {
    var f = this.config.customize[id] && this.config.customize[id].theme ? getOrgChart.themes[this.config.customize[id].theme] : this.theme;
    status = (status == undefined ? getOrgChart.NOT_DEFINED : status);
    var node = new getOrgChart.node(id, parentId, data, f.size, this.config.photoFields, status);

    if (!this._nodeOnClick("createNodeEvent", {node: node})) {
        return null;
    }

    if (this.nodes[id]) {
        node._zi = this.nodes[id].x;
        node._zk = this.nodes[id].y;
    } else {
        node._zi = null;
        node._zk = null;
    }
    this.nodes[id] = node;
    for (label in node.data) {
        if (!getOrgChart.util._existsInArray(this._af, label)) {
            this._af.push(label);
        }
    }

    return node;
};

/**
 * Loads data for the dTree
 */
getOrgChart.prototype.loadDTree = function () {
    var data = this.config.dTreeDataSource;

    if (!data) {
        return;
    }

    this.flatNodes = initDTreeSVG(data);

    this.draw();
};

/**
 * Loads data for the tree
 */
getOrgChart.prototype.load = function () {
    var data = this.config.dataSource;

    if (!data) {
        return;
    }

    if (data.constructor && (data.constructor.toString().indexOf("HTML") > -1)) {
        this.loadFromHTMLTable(data);
    } else {
        if (typeof(data) == "string") {
            this.loadFromXML(data);
        } else {
            this.loadFromJSON(data);
        }
    }
};

/**
 * Load graph data from JSON.
 *
 * @param {Array} data objects
 * @param {Boolean} m
 * @param {Function} callback
 */
getOrgChart.prototype.loadFromJSON = function (data, m, callback) {
    this._a4 = 0;
    this._a3 = 0;
    this._ab = [];
    this._ay = [];
    this._aI = [];
    this._rootNode = new getOrgChart.node(-1, null, null, 2, 2);

    if (m) {
        for (var i in data) {
            if (this.nodes[i] && !this.nodes[i].isVisible()) {
                this.nodes[i].x = this.nodes[i].parent.x;
                this.nodes[i].y = this.nodes[i].parent.y
            }

            this._initNode(i, data[i].pid, data[i].data, data[i].collapsed);
        }
    } else {
        var idField       = Object.keys(data[0])[0];
        var parentIdField = Object.keys(data[0])[1];

        if (this.config.idField) {
            idField = this.config.idField
        }

        if (this.config.parentIdField) {
            parentIdField = this.config.parentIdField
        }

        for (var c = 0; c < data.length; c++) {
            var id = data[c][idField];
            var parentId = data[c][parentIdField];
            delete data[c][idField];
            delete data[c][parentIdField];

            this._initNode(id, parentId, data[c]);
        }
    }

    for (var k in this.nodes) {
        var node = this.nodes[k];

        var parentNode = this.nodes[node.pid];
        if (!parentNode) {
            parentNode = this._rootNode;
        }
        node.parent = parentNode;
        var b = parentNode.children.length;
        parentNode.children[b] = node;
    }

    this.draw(callback);
};

getOrgChart.prototype.loadFromHTMLTable = function (c) {
    var d = c.rows[0];
    var g = [];
    for (var e = 1; e < c.rows.length; e++) {
        var h = c.rows[e];
        var b = {};
        for (var f = 0; f < h.cells.length; f++) {
            var a = h.cells[f];
            b[d.cells[f].innerHTML] = a.innerHTML
        }
        g.push(b)
    }
    this.loadFromJSON(g)
};
getOrgChart.prototype.loadFromXML = function (c) {
    var a = this;
    var b = [];
    get._z._E(c, null, function (d) {
        a._ag = 0;
        a._at(d, null, true, b);
        a.loadFromJSON(b)
    }, "xml")
};
getOrgChart.prototype.loadFromXMLDocument = function (b) {
    var a = [];
    var c = getOrgChart.util._zr(b);
    this._ag = 0;
    this._at(c, null, true, a);
    this.loadFromJSON(a)
};
getOrgChart.prototype._at = function (m, l, d, h) {
    var a = this;
    if (m.nodeType == 1) {
        if (m.attributes.length > 0) {
            var c = {};
            a._ag++;
            c.id = a._ag;
            c.pid = l;
            for (var g = 0; g < m.attributes.length; g++) {
                var b = m.attributes.item(g);
                c[b.nodeName] = b.nodeValue
            }
            h.push(c);
            if (d) {
                d = false
            }
        }
    }
    if (m.hasChildNodes()) {
        if (!d) {
            l = a._ag
        }
        for (var e = 0; e < m.childNodes.length; e++) {
            var f = m.childNodes.item(e);
            var k = f.nodeName;
            if (f.nodeType == 3) {
                continue
            }
            this._at(f, l, d, h)
        }
    }
};
if (typeof(get) == "undefined") {
    get = {}
}
get._z = {};
get._z._httpClient = function () {
    if (window.XMLHttpRequest) {
        return new XMLHttpRequest()
    } else {
        return new ActiveXObject("Microsoft.XMLHTTP")
    }
};
get._z._zz = function (f, a, d, c, b, e) {
    var g = get._z._httpClient();
    g.open(d, f, e);
    g.onreadystatechange = function () {
        if (!get._userAgent().msie && c == "xml" && g.readyState == 4) {
            a(g.responseXML)
        } else {
            if (get._userAgent().msie && c == "xml" && g.readyState == 4) {
                try {
                    var i = new DOMParser();
                    var j = i.parseFromString(g.responseText, "text/xml");
                    a(j)
                } catch (h) {
                    var j = new ActiveXObject("Microsoft.XMLDOM");
                    j.loadXML(g.responseText);
                    a(j)
                }
            } else {
                if (g.readyState == 4) {
                    a(g.responseText)
                }
            }
        }
    };
    if (d == "POST") {
        g.setRequestHeader("Content-type", "application/x-www-form-urlencoded")
    }
    g.send(b)
};
get._z._E = function (g, b, a, c, f) {
    var e = [];
    for (var d in b) {
        e.push(encodeURIComponent(d) + "=" + encodeURIComponent(b[d]))
    }
    get._z._zz(g + "?" + e.join("&"), a, "GET", c, null, f)
};
get._z._aY = function (g, b, a, c, f) {
    var e = [];
    for (var d in b) {
        e.push(encodeURIComponent(d) + "=" + encodeURIComponent(b[d]))
    }
    get._z._zz(g, a, "POST", c, e.join("&"), f)
};
