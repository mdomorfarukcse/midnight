(function(a, b) {
    if (typeof define === "function" && define.amd) {
        define(["jquery"], function(c) {
            return (b(c))
        })
    } else {
        if (typeof module === "object" && module.exports) {
            module.exports = b(require("jquery"))
        } else {
            b(a.jQuery)
        }
    }
}(this, function(a) {
    (function(g) {
        if (!String.prototype.includes) {
            (function() {
                var q = {}.toString;
                var n = (function() {
                    try {
                        var u = {};
                        var t = Object.defineProperty;
                        var r = t(u, u, u) && t
                    } catch (s) {}
                    return r
                }());
                var p = "".indexOf;
                var o = function(w) {
                    if (this == null) {
                        throw new TypeError()
                    }
                    var u = String(this);
                    if (w && q.call(w) == "[object RegExp]") {
                        throw new TypeError()
                    }
                    var s = u.length;
                    var t = String(w);
                    var v = t.length;
                    var r = arguments.length > 1 ? arguments[1] : undefined;
                    var y = r ? Number(r) : 0;
                    if (y != y) {
                        y = 0
                    }
                    var x = Math.min(Math.max(y, 0), s);
                    if (v + x > s) {
                        return false
                    }
                    return p.call(u, t, y) != -1
                };
                if (n) {
                    n(String.prototype, "includes", {
                        value: o,
                        configurable: true,
                        writable: true,
                    })
                } else {
                    String.prototype.includes = o
                }
            }())
        }
        if (!String.prototype.startsWith) {
            (function() {
                var n = (function() {
                    try {
                        var t = {};
                        var s = Object.defineProperty;
                        var q = s(t, t, t) && s
                    } catch (r) {}
                    return q
                }());
                var p = {}.toString;
                var o = function(x) {
                    if (this == null) {
                        throw new TypeError()
                    }
                    var u = String(this);
                    if (x && p.call(x) == "[object RegExp]") {
                        throw new TypeError()
                    }
                    var q = u.length;
                    var y = String(x);
                    var s = y.length;
                    var t = arguments.length > 1 ? arguments[1] : undefined;
                    var w = t ? Number(t) : 0;
                    if (w != w) {
                        w = 0
                    }
                    var r = Math.min(Math.max(w, 0), q);
                    if (s + r > q) {
                        return false
                    }
                    var v = -1;
                    while (++v < s) {
                        if (u.charCodeAt(r + v) != y.charCodeAt(v)) {
                            return false
                        }
                    }
                    return true
                };
                if (n) {
                    n(String.prototype, "startsWith", {
                        value: o,
                        configurable: true,
                        writable: true,
                    })
                } else {
                    String.prototype.startsWith = o
                }
            }())
        }
        if (!Object.keys) {
            Object.keys = function(q, n, p) {
                p = [];
                for (n in q) {
                    p.hasOwnProperty.call(q, n) && p.push(n)
                }
                return p
            }
        }
        var e = {
            useDefault: false,
            _set: g.valHooks.select.set,
        };
        g.valHooks.select.set = function(n, o) {
            if (o && !e.useDefault) {
                g(n).data("selected", true)
            }
            return e._set.apply(this, arguments)
        };
        var m = null;
        g.fn.triggerNative = function(n) {
            var o = this[0],
                p;
            if (o.dispatchEvent) {
                if (typeof Event === "function") {
                    p = new Event(n, {
                        bubbles: true,
                    })
                } else {
                    p = document.createEvent("Event");
                    p.initEvent(n, true, false)
                }
                o.dispatchEvent(p)
            } else {
                if (o.fireEvent) {
                    p = document.createEventObject();
                    p.eventType = n;
                    o.fireEvent("on" + n, p)
                } else {
                    this.trigger(n)
                }
            }
        };
        g.expr.pseudos.icontains = function(q, n, p) {
            var r = g(q).find("span.dropdown-item-inner");
            var o = (r.data("tokens") || r.text()).toString().toUpperCase();
            return o.includes(p[3].toUpperCase())
        };
        g.expr.pseudos.ibegins = function(q, n, p) {
            var r = g(q).find("span.dropdown-item-inner");
            var o = (r.data("tokens") || r.text()).toString().toUpperCase();
            return o.startsWith(p[3].toUpperCase())
        };
        g.expr.pseudos.aicontains = function(q, n, p) {
            var r = g(q).find("span.dropdown-item-inner");
            var o = (r.data("tokens") || r.data("normalizedText") || r.text()).toString().toUpperCase();
            return o.includes(p[3].toUpperCase())
        };
        g.expr.pseudos.aibegins = function(q, n, p) {
            var r = g(q).find("span.dropdown-item-inner");
            var o = (r.data("tokens") || r.data("normalizedText") || r.text()).toString().toUpperCase();
            return o.startsWith(p[3].toUpperCase())
        };

        function c(o) {
            var n = [{
                re: /[\xC0-\xC6]/g,
                ch: "A"
            }, {
                re: /[\xE0-\xE6]/g,
                ch: "a"
            }, {
                re: /[\xC8-\xCB]/g,
                ch: "E"
            }, {
                re: /[\xE8-\xEB]/g,
                ch: "e"
            }, {
                re: /[\xCC-\xCF]/g,
                ch: "I"
            }, {
                re: /[\xEC-\xEF]/g,
                ch: "i"
            }, {
                re: /[\xD2-\xD6]/g,
                ch: "O"
            }, {
                re: /[\xF2-\xF6]/g,
                ch: "o"
            }, {
                re: /[\xD9-\xDC]/g,
                ch: "U"
            }, {
                re: /[\xF9-\xFC]/g,
                ch: "u"
            }, {
                re: /[\xC7-\xE7]/g,
                ch: "c"
            }, {
                re: /[\xD1]/g,
                ch: "N"
            }, {
                re: /[\xF1]/g,
                ch: "n"
            }, ];
            g.each(n, function() {
                o = o ? o.replace(this.re, this.ch) : ""
            });
            return o
        }
        var h = {
            "&": "&amp;",
            "<": "&lt;",
            ">": "&gt;",
            '"': "&quot;",
            "'": "&#x27;",
            "`": "&#x60;",
        };
        var b = {
            "&amp;": "&",
            "&lt;": "<",
            "&gt;": ">",
            "&quot;": '"',
            "&#x27;": "'",
            "&#x60;": "`",
        };
        var f = function(r) {
            var o = function(s) {
                return r[s]
            };
            var q = "(?:" + Object.keys(r).join("|") + ")";
            var p = RegExp(q);
            var n = RegExp(q, "g");
            return function(s) {
                s = s == null ? "" : "" + s;
                return p.test(s) ? s.replace(n, o) : s
            }
        };
        var l = f(h);
        var i = f(b);
        var k = function(p, o) {
            if (!e.useDefault) {
                g.valHooks.select.set = e._set;
                e.useDefault = true
            }
            this.$element = g(p);
            this.$newElement = null;
            this.$button = null;
            this.$menu = null;
            this.$lis = null;
            this.options = o;
            if (this.options.title === null) {
                this.options.title = this.$element.attr("title")
            }
            var n = this.options.windowPadding;
            if (typeof n === "number") {
                this.options.windowPadding = [n, n, n, n]
            }
            this.val = k.prototype.val;
            this.render = k.prototype.render;
            this.refresh = k.prototype.refresh;
            this.setStyle = k.prototype.setStyle;
            this.selectAll = k.prototype.selectAll;
            this.deselectAll = k.prototype.deselectAll;
            this.destroy = k.prototype.destroy;
            this.remove = k.prototype.remove;
            this.show = k.prototype.show;
            this.hide = k.prototype.hide;
            this.init()
        };
        k.VERSION = "1.12.2";
        k.DEFAULTS = {
            noneSelectedText: "Nothing selected",
            noneResultsText: "No results matched {0}",
            countSelectedText: function(o, n) {
                return (o == 1) ? "{0} item selected" : "{0} items selected"
            },
            maxOptionsText: function(n, o) {
                return [(n == 1) ? "Limit reached ({n} item max)" : "Limit reached ({n} items max)", (o == 1) ? "Group limit reached ({n} item max)" : "Group limit reached ({n} items max)", ]
            },
            selectAllText: "Select All",
            deselectAllText: "Deselect All",
            doneButton: false,
            doneButtonText: "Close",
            multipleSeparator: ", ",
            styleBase: "btn",
            style: "btn mb-2",
            size: "auto",
            title: null,
            selectedTextFormat: "values",
            width: false,
            container: false,
            hideDisabled: false,
            showSubtext: false,
            showIcon: true,
            showContent: true,
            dropupAuto: true,
            header: false,
            liveSearch: false,
            liveSearchPlaceholder: null,
            liveSearchNormalize: false,
            liveSearchStyle: "contains",
            actionsBox: false,
            iconBase: "",
            tickIcon: "",
            showTick: false,
            template: {
                caret: '<span class="caret"></span>',
            },
            maxOptions: false,
            mobile: false,
            selectOnTab: false,
            dropdownAlignRight: false,
            windowPadding: 0,
        };
        k.prototype = {
            constructor: k,
            init: function() {
                var n = this,
                    o = this.$element.attr("id");
                this.$element.addClass("bs-select-hidden");
                this.liObj = {};
                this.multiple = this.$element.prop("multiple");
                this.autofocus = this.$element.prop("autofocus");
                this.$newElement = this.createView();
                this.$element.after(this.$newElement).appendTo(this.$newElement);
                this.$button = this.$newElement.children("button");
                this.$menu = this.$newElement.children(".dropdown-menu");
                this.$menuInner = this.$menu.children(".inner");
                this.$searchbox = this.$menu.find("input");
                this.$element.removeClass("bs-select-hidden");
                if (this.options.dropdownAlignRight === true) {
                    this.$menu.addClass("dropdown-menu-right")
                }
                if (typeof o !== "undefined") {
                    this.$button.attr("data-id", o);
                    g('label[for="' + o + '"]').click(function(p) {
                        p.preventDefault();
                        n.$button.focus()
                    })
                }
                this.checkDisabled();
                this.clickListener();
                if (this.options.liveSearch) {
                    this.liveSearchListener()
                }
                this.render();
                this.setStyle();
                this.setWidth();
                if (this.options.container) {
                    this.selectPosition()
                }
                this.$menu.data("this", this);
                this.$newElement.data("this", this);
                if (this.options.mobile) {
                    this.mobile()
                }
                this.$newElement.on({
                    "hide.bs.dropdown": function(p) {
                        n.$menuInner.attr("aria-expanded", false);
                        n.$element.trigger("hide.bs.select", p)
                    },
                    "hidden.bs.dropdown": function(p) {
                        n.$element.trigger("hidden.bs.select", p)
                    },
                    "show.bs.dropdown": function(p) {
                        n.$menuInner.attr("aria-expanded", true);
                        n.$element.trigger("show.bs.select", p)
                    },
                    "shown.bs.dropdown": function(p) {
                        n.$element.trigger("shown.bs.select", p)
                    },
                });
                if (n.$element[0].hasAttribute("required")) {
                    this.$element.on("invalid", function() {
                        n.$button.addClass("bs-invalid").focus();
                        n.$element.on({
                            "focus.bs.select": function() {
                                n.$button.focus();
                                n.$element.off("focus.bs.select")
                            },
                            "shown.bs.select": function() {
                                n.$element.val(n.$element.val()).off("shown.bs.select")
                            },
                            "rendered.bs.select": function() {
                                if (this.validity.valid) {
                                    n.$button.removeClass("bs-invalid")
                                }
                                n.$element.off("rendered.bs.select")
                            },
                        })
                    })
                }
                setTimeout(function() {
                    n.$element.trigger("loaded.bs.select")
                })
            },
            createDropdown: function() {
                var s = (this.multiple || this.options.showTick) ? " show-tick" : "",
                    o = this.$element.parent().hasClass("input-group") ? " input-group-btn" : "",
                    u = this.autofocus ? " autofocus" : "";
                var t = this.options.header ? '<div class="popover-title"><button type="button" class="close" aria-hidden="true">&times;</button>' + this.options.header + "</div>" : "";
                var r = this.options.liveSearch ? '<div class="bs-searchbox"><input type="text" class="form-control" autocomplete="off"' + (null === this.options.liveSearchPlaceholder ? "" : ' placeholder="' + l(this.options.liveSearchPlaceholder) + '"') + ' role="textbox" aria-label="Search"></div>' : "";
                var q = this.multiple && this.options.actionsBox ? '<div class="bs-actionsbox"><div class="btn-group btn-group-sm btn-block"><button type="button" class="actions-btn bs-select-all btn btn-default btn-light">' + this.options.selectAllText + '</button><button type="button" class="actions-btn bs-deselect-all btn btn-default btn-light">' + this.options.deselectAllText + "</button></div></div>" : "";
                var n = this.multiple && this.options.doneButton ? '<div class="bs-donebutton"><div class="btn-group btn-block"><button type="button" class="btn btn-sm btn-default btn-light">' + this.options.doneButtonText + "</button></div></div>" : "";
                var p = '<div class="btn-group bootstrap-select' + s + o + '"><button type="button" class="' + this.options.styleBase + ' dropdown-toggle" data-toggle="dropdown"' + u + ' role="button"><span class="filter-option float-left"></span>&nbsp;<span class="bs-caret">' + this.options.template.caret + '</span></button><div class="dropdown-menu select-dropdown open" role="combobox">' + t + r + q + '<div class="dropdown-menu inner" role="listbox" aria-expanded="false"></div>' + n + "</div></div>";
                return g(p)
            },
            createView: function() {
                var o = this.createDropdown(),
                    n = this.createLi();
                o.find("div.inner")[0].innerHTML = n;
                return o
            },
            reloadLi: function() {
                var n = this.createLi();
                this.$menuInner[0].innerHTML = n
            },
            createLi: function() {
                var u = this,
                    t = [],
                    x = 0,
                    n = document.createElement("option"),
                    q = -1;
                var p = function(B, z, A, y) {
                    A = "dropdown-item " + (A || "");
                    return '<a tabindex="0"' + ((typeof A !== "undefined" && "" !== A) ? ' class="' + A + '"' : "") + ((typeof z !== "undefined" && null !== z) ? ' data-original-index="' + z + '"' : "") + ((typeof y !== "undefined" && null !== y) ? 'data-optgroup="' + y + '"' : "") + ">" + B + "</a>"
                };
                var r = function(A, z, y) {
                    return "<div" + ((typeof z !== "undefined" && "" !== z) ? ' class="' + z + '"' : "") + ((typeof y !== "undefined" && null !== y) ? ' data-optgroup="' + y + '"' : "") + ">" + A + "</div>"
                };
                var v = function(B, y, A, z) {
                    y = "dropdown-item-inner " + (y || "");
                    return "<span" + (typeof y !== "undefined" ? ' class="' + y + '"' : "") + (A ? ' style="' + A + '"' : "") + (u.options.liveSearchNormalize ? ' data-normalized-text="' + c(l(g(B).html())) + '"' : "") + (typeof z !== "undefined" || z !== null ? ' data-tokens="' + z + '"' : "") + ' role="option">' + B + '<span class="' + u.options.iconBase + " " + u.options.tickIcon + ' check-mark"></span></span>'
                };
                if (this.options.title && !this.multiple) {
                    q--;
                    if (!this.$element.find(".bs-title-option").length) {
                        var s = this.$element[0];
                        n.className = "bs-title-option";
                        n.innerHTML = this.options.title;
                        n.value = "";
                        s.insertBefore(n, s.firstChild);
                        var w = g(s.options[s.selectedIndex]);
                        if (w.attr("selected") === undefined && this.$element.data("selected") === undefined) {
                            n.selected = true
                        }
                    }
                }
                var o = this.$element.find("option");
                o.each(function(C) {
                    var B = g(this);
                    q++;
                    if (B.hasClass("bs-title-option")) {
                        return
                    }
                    var E = this.className || "",
                        A = l(this.style.cssText),
                        F = B.data("content") ? B.data("content") : B.html(),
                        H = B.data("tokens") ? B.data("tokens") : null,
                        R = typeof B.data("subtext") !== "undefined" ? '<small class="text-muted">' + B.data("subtext") + "</small>" : "",
                        O = typeof B.data("icon") !== "undefined" ? '<span class="' + u.options.iconBase + " " + B.data("icon") + '"></span> ' : "",
                        J = B.parent(),
                        z = J[0].tagName === "OPTGROUP",
                        M = z && J[0].disabled,
                        G = this.disabled || M,
                        y;
                    if (O !== "" && G) {
                        O = "<span>" + O + "</span>"
                    }
                    if (u.options.hideDisabled && (G && !z || M)) {
                        y = B.data("prevHiddenIndex");
                        B.next().data("prevHiddenIndex", (y !== undefined ? y : C));
                        q--;
                        return
                    }
                    if (!B.data("content")) {
                        F = O + '<span class="text">' + F + R + "</span>"
                    }
                    if (z && B.data("divider") !== true) {
                        if (u.options.hideDisabled && G) {
                            if (J.data("allOptionsDisabled") === undefined) {
                                var N = J.children();
                                J.data("allOptionsDisabled", N.filter(":disabled").length === N.length)
                            }
                            if (J.data("allOptionsDisabled")) {
                                q--;
                                return
                            }
                        }
                        var P = " " + J[0].className || "";
                        if (B.index() === 0) {
                            x += 1;
                            var D = J[0].label,
                                Q = typeof J.data("subtext") !== "undefined" ? '<small class="text-muted">' + J.data("subtext") + "</small>" : "",
                                K = J.data("icon") ? '<span class="' + u.options.iconBase + " " + J.data("icon") + '"></span> ' : "";
                            D = K + '<span class="text">' + l(D) + Q + "</span>";
                            if (C !== 0 && t.length > 0) {
                                q++;
                                t.push(r("", "dropdown-divider", x + "div"))
                            }
                            q++;
                            t.push(r(D, "dropdown-header" + P, x))
                        }
                        if (u.options.hideDisabled && G) {
                            q--;
                            return
                        }
                        t.push(p(v(F, "opt " + E + P, A, H), C, "", x))
                    } else {
                        if (B.data("divider") === true) {
                            t.push(r("", "dropdown-divider", x + "div"))
                        } else {
                            if (B.data("hidden") === true) {
                                y = B.data("prevHiddenIndex");
                                B.next().data("prevHiddenIndex", (y !== undefined ? y : C));
                                t.push(p(v(F, E, A, H), C, "hidden is-hidden"))
                            } else {
                                var I = this.previousElementSibling && this.previousElementSibling.tagName === "OPTGROUP";
                                if (!I && u.options.hideDisabled) {
                                    y = B.data("prevHiddenIndex");
                                    if (y !== undefined) {
                                        var L = o.eq(y)[0].previousElementSibling;
                                        if (L && L.tagName === "OPTGROUP" && !L.disabled) {
                                            I = true
                                        }
                                    }
                                }
                                if (I) {
                                    q++;
                                    t.push(r("", "dropdown-divider", x + "div"))
                                }
                                t.push(p(v(F, E, A, H), C))
                            }
                        }
                    }
                    u.liObj[C] = q
                });
                if (!this.multiple && this.$element.find("option:selected").length === 0 && !this.options.title) {
                    this.$element.find("option").eq(0).prop("selected", true).attr("selected", "selected")
                }
                return t.join("")
            },
            findLis: function() {
                if (this.$lis == null) {
                    this.$lis = this.$menu.find("a, .dropdown-header, .dropdown-divider")
                }
                return this.$lis
            },
            render: function(r) {
                var q = this,
                    n, o = this.$element.find("option");
                if (r !== false) {
                    o.each(function(w) {
                        var x = q.findLis().eq(q.liObj[w]);
                        q.setDisabled(w, this.disabled || this.parentNode.tagName === "OPTGROUP" && this.parentNode.disabled, x);
                        q.setSelected(w, this.selected, x)
                    })
                }
                this.togglePlaceholder();
                this.tabIndex();
                var p = o.map(function() {
                    if (this.selected) {
                        if (q.options.hideDisabled && (this.disabled || this.parentNode.tagName === "OPTGROUP" && this.parentNode.disabled)) {
                            return
                        }
                        var y = g(this),
                            x = y.data("icon") && q.options.showIcon ? '<i class="' + q.options.iconBase + " " + y.data("icon") + '"></i> ' : "",
                            w;
                        if (q.options.showSubtext && y.data("subtext") && !q.multiple) {
                            w = ' <small class="text-muted">' + y.data("subtext") + "</small>"
                        } else {
                            w = ""
                        }
                        if (typeof y.attr("title") !== "undefined") {
                            return y.attr("title")
                        } else {
                            if (y.data("content") && q.options.showContent) {
                                return y.data("content").toString()
                            } else {
                                return x + y.html() + w
                            }
                        }
                    }
                }).toArray();
                var t = !this.multiple ? p[0] : p.join(this.options.multipleSeparator);
                if (this.multiple && this.options.selectedTextFormat.indexOf("count") > -1) {
                    var s = this.options.selectedTextFormat.split(">");
                    if ((s.length > 1 && p.length > s[1]) || (s.length == 1 && p.length >= 2)) {
                        n = this.options.hideDisabled ? ", [disabled]" : "";
                        var u = o.not('[data-divider="true"], [data-hidden="true"]' + n).length,
                            v = (typeof this.options.countSelectedText === "function") ? this.options.countSelectedText(p.length, u) : this.options.countSelectedText;
                        t = v.replace("{0}", p.length.toString()).replace("{1}", u.toString())
                    }
                }
                if (this.options.title == undefined) {
                    this.options.title = this.$element.attr("title")
                }
                if (this.options.selectedTextFormat == "static") {
                    t = this.options.title
                }
                if (!t) {
                    t = typeof this.options.title !== "undefined" ? this.options.title : this.options.noneSelectedText
                }
                this.$button.attr("title", i(g.trim(t.replace(/<[^>]*>?/g, ""))));
                this.$button.children(".filter-option").html(t);
                this.$element.trigger("rendered.bs.select")
            },
            setStyle: function(p, o) {
                if (this.$element.attr("class")) {
                    this.$newElement.addClass(this.$element.attr("class").replace(/selectpicker|mobile-device|bs-select-hidden|validate\[.*\]/gi, ""))
                }
                var n = p ? p : this.options.style;
                if (o == "add") {
                    this.$button.addClass(n)
                } else {
                    if (o == "remove") {
                        this.$button.removeClass(n)
                    } else {
                        this.$button.removeClass(this.options.style);
                        this.$button.addClass(n)
                    }
                }
            },
            updatePosition: function() {
                var n = this.$menu.get(0).ownerDocument.createEvent("HTMLEvents");
                n.initEvent("resize", true, false);
                this.$menu.get(0).ownerDocument.dispatchEvent(n)
            },
            liHeight: function(t) {
                if (!t && (this.options.size === false || this.sizeInfo)) {
                    return
                }
                var r = document.createElement("div"),
                    n = document.createElement("div"),
                    x = document.createElement("ul"),
                    F = document.createElement("a"),
                    E = document.createElement("a"),
                    J = document.createElement("span"),
                    D = document.createElement("span"),
                    I = this.options.header && this.$menu.find(".popover-title").length > 0 ? this.$menu.find(".popover-title")[0].cloneNode(true) : null,
                    y = this.options.liveSearch ? document.createElement("div") : null,
                    z = this.options.actionsBox && this.multiple && this.$menu.find(".bs-actionsbox").length > 0 ? this.$menu.find(".bs-actionsbox")[0].cloneNode(true) : null,
                    q = this.options.doneButton && this.multiple && this.$menu.find(".bs-donebutton").length > 0 ? this.$menu.find(".bs-donebutton")[0].cloneNode(true) : null;
                D.className = "text";
                r.className = this.$menu[0].parentNode.className + " show open";
                n.className = "dropdown-menu open show";
                x.className = "dropdown-menu inner";
                F.className = "dropdown-divider";
                J.className = "dropdown-item-inner";
                D.appendChild(document.createTextNode("Inner text"));
                J.appendChild(D);
                E.appendChild(J);
                x.appendChild(E);
                x.appendChild(F);
                if (I) {
                    n.appendChild(I)
                }
                if (y) {
                    var B = document.createElement("input");
                    y.className = "bs-searchbox";
                    B.className = "form-control";
                    y.appendChild(B);
                    n.appendChild(y)
                }
                if (z) {
                    n.appendChild(z)
                }
                n.appendChild(x);
                if (q) {
                    n.appendChild(q)
                }
                r.appendChild(n);
                document.body.appendChild(r);
                var p = J.offsetHeight,
                    H = I ? I.offsetHeight : 0,
                    A = y ? y.offsetHeight : 0,
                    w = z ? z.offsetHeight : 0,
                    s = q ? q.offsetHeight : 0,
                    C = g(F).outerHeight(true),
                    o = typeof getComputedStyle === "function" ? getComputedStyle(n) : false,
                    u = o ? null : g(n),
                    G = {
                        vert: parseInt(o ? o.paddingTop : u.css("paddingTop")) + parseInt(o ? o.paddingBottom : u.css("paddingBottom")) + parseInt(o ? o.borderTopWidth : u.css("borderTopWidth")) + parseInt(o ? o.borderBottomWidth : u.css("borderBottomWidth")),
                        horiz: parseInt(o ? o.paddingLeft : u.css("paddingLeft")) + parseInt(o ? o.paddingRight : u.css("paddingRight")) + parseInt(o ? o.borderLeftWidth : u.css("borderLeftWidth")) + parseInt(o ? o.borderRightWidth : u.css("borderRightWidth")),
                    },
                    v = {
                        vert: G.vert + parseInt(o ? o.marginTop : u.css("marginTop")) + parseInt(o ? o.marginBottom : u.css("marginBottom")) + 2,
                        horiz: G.horiz + parseInt(o ? o.marginLeft : u.css("marginLeft")) + parseInt(o ? o.marginRight : u.css("marginRight")) + 2,
                    };
                document.body.removeChild(r);
                this.sizeInfo = {
                    liHeight: p,
                    headerHeight: H,
                    searchHeight: A,
                    actionsHeight: w,
                    doneButtonHeight: s,
                    dividerHeight: C,
                    menuPadding: G,
                    menuExtras: v,
                }
            },
            setSize: function() {
                this.findLis();
                this.liHeight();
                if (this.options.header) {
                    this.$menu.css("padding-top", 0)
                }
                if (this.options.size === false) {
                    return
                }
                var y = this,
                    v = this.$menu,
                    r = this.$menuInner,
                    D = g(window),
                    L = this.$newElement[0].offsetHeight,
                    N = this.$newElement[0].offsetWidth,
                    s = this.sizeInfo.liHeight,
                    H = this.sizeInfo.headerHeight,
                    A = this.sizeInfo.searchHeight,
                    x = this.sizeInfo.actionsHeight,
                    t = this.sizeInfo.doneButtonHeight,
                    z = this.sizeInfo.dividerHeight,
                    G = this.sizeInfo.menuPadding,
                    w = this.sizeInfo.menuExtras,
                    E = this.options.hideDisabled ? ".disabled" : "",
                    F, n, B, p, M, K, u, J, I = function() {
                        var R = y.$newElement.offset(),
                            P = g(y.options.container),
                            Q;
                        if (y.options.container && !P.is("body")) {
                            Q = P.offset();
                            Q.top += parseInt(P.css("borderTopWidth"));
                            Q.left += parseInt(P.css("borderLeftWidth"))
                        } else {
                            Q = {
                                top: 0,
                                left: 0
                            }
                        }
                        var O = y.options.windowPadding;
                        M = R.top - Q.top - D.scrollTop();
                        K = D.height() - M - L - Q.top - O[2];
                        u = R.left - Q.left - D.scrollLeft();
                        J = D.width() - u - N - Q.left - O[1];
                        M -= O[0];
                        u -= O[3]
                    };
                I();
                if (this.options.size === "auto") {
                    var q = function() {
                        var R, Q = function(U, T) {
                                return function(V) {
                                    if (T) {
                                        return (V.classList ? V.classList.contains(U) : g(V).hasClass(U))
                                    } else {
                                        return !(V.classList ? V.classList.contains(U) : g(V).hasClass(U))
                                    }
                                }
                            },
                            P = y.$menuInner[0].getElementsByTagName("a"),
                            O = Array.prototype.filter ? Array.prototype.filter.call(P, Q("d-none", false)) : y.$lis.not(".d-none"),
                            S = Array.prototype.filter ? Array.prototype.filter.call(O, Q("dropdown-header", true)) : O.filter(".dropdown-header");
                        I();
                        F = K - w.vert;
                        n = J - w.horiz;
                        if (y.options.container) {
                            if (!v.data("height")) {
                                v.data("height", v.height())
                            }
                            B = v.data("height");
                            if (!v.data("width")) {
                                v.data("width", v.width())
                            }
                            p = v.data("width")
                        } else {
                            B = v.height();
                            p = v.width()
                        }
                        if (y.options.dropupAuto) {
                            y.$newElement.toggleClass("dropup", M > K && (F - w.vert) < B)
                        }
                        if (y.$newElement.hasClass("dropup")) {
                            F = M - w.vert
                        }
                        if (y.options.dropdownAlignRight === "auto") {
                            v.toggleClass("dropdown-menu-right", u > J && (n - w.horiz) < (p - N))
                        }
                        if ((O.length + S.length) > 3) {
                            R = s * 3 + w.vert - 2
                        } else {
                            R = 0
                        }
                        v.css({
                            "max-height": F + "px",
                            overflow: "hidden",
                            "min-height": R + H + A + x + t + "px",
                        });
                        r.css({
                            "max-height": F - H - A - x - t - G.vert + "px",
                            "overflow-y": "auto",
                            "min-height": Math.max(R - G.vert, 0) + "px",
                        })
                    };
                    q();
                    this.$searchbox.off("input.getSize propertychange.getSize").on("input.getSize propertychange.getSize", q);
                    D.off("resize.getSize scroll.getSize").on("resize.getSize scroll.getSize", q)
                } else {
                    if (this.options.size && this.options.size != "auto" && this.$lis.not(E).length > this.options.size) {
                        var C = this.$lis.not(".dropdown-divider").not(E).children().slice(0, this.options.size).last().parent().index(),
                            o = this.$lis.slice(0, C + 1).filter(".dropdown-divider").length;
                        F = s * this.options.size + o * z + G.vert;
                        if (y.options.container) {
                            if (!v.data("height")) {
                                v.data("height", v.height())
                            }
                            B = v.data("height")
                        } else {
                            B = v.height()
                        }
                        if (y.options.dropupAuto) {
                            this.$newElement.toggleClass("dropup", M > K && (F - w.vert) < B)
                        }
                        v.css({
                            "max-height": F + H + A + x + t + "px",
                            overflow: "hidden",
                            "min-height": "",
                        });
                        r.css({
                            "max-height": F - G.vert + "px",
                            "overflow-y": "auto",
                            "min-height": "",
                        })
                    }
                }
            },
            setWidth: function() {
                if (this.options.width === "auto") {
                    this.$menu.css("min-width", "0");
                    var o = this.$menu.parent().clone().appendTo("body"),
                        n = this.options.container ? this.$newElement.clone().appendTo("body") : o,
                        p = o.children(".dropdown-menu").outerWidth(),
                        q = n.css("width", "auto").children("button").outerWidth();
                    o.remove();
                    n.remove();
                    this.$newElement.css("width", Math.max(p, q) + "px")
                } else {
                    if (this.options.width === "fit") {
                        this.$menu.css("min-width", "");
                        this.$newElement.css("width", "").addClass("fit-width")
                    } else {
                        if (this.options.width) {
                            this.$menu.css("min-width", "");
                            this.$newElement.css("width", this.options.width)
                        } else {
                            this.$menu.css("min-width", "");
                            this.$newElement.css("width", "")
                        }
                    }
                }
                if (this.$newElement.hasClass("fit-width") && this.options.width !== "fit") {
                    this.$newElement.removeClass("fit-width")
                }
            },
            selectPosition: function() {
                this.$bsContainer = g('<div class="bs-container" />');
                var o = this,
                    p = g(this.options.container),
                    s, r, q, n = function(t) {
                        o.$bsContainer.addClass(t.attr("class").replace(/form-control|fit-width/gi, "")).toggleClass("dropup", t.hasClass("dropup"));
                        s = t.offset();
                        if (!p.is("body")) {
                            r = p.offset();
                            r.top += parseInt(p.css("borderTopWidth")) - p.scrollTop();
                            r.left += parseInt(p.css("borderLeftWidth")) - p.scrollLeft()
                        } else {
                            r = {
                                top: 0,
                                left: 0
                            }
                        }
                        q = t.hasClass("dropup") ? 0 : t[0].offsetHeight;
                        o.$bsContainer.css({
                            top: s.top - r.top + q,
                            left: s.left - r.left,
                            width: t[0].offsetWidth,
                        })
                    };
                this.$button.on("click", function() {
                    var t = g(this);
                    if (o.isDisabled()) {
                        return
                    }
                    n(o.$newElement);
                    o.$bsContainer.appendTo(o.options.container).toggleClass("open", !t.hasClass("open")).append(o.$menu)
                });
                g(window).on("resize scroll", function() {
                    n(o.$newElement)
                });
                this.$element.on("hide.bs.select", function() {
                    o.$menu.data("height", o.$menu.height());
                    o.$bsContainer.detach()
                })
            },
            setSelected: function(n, p, o) {
                if (!o) {
                    this.togglePlaceholder();
                    o = this.findLis().eq(this.liObj[n])
                }
                o.toggleClass("selected", p).find("span.dropdown-item-inner").attr("aria-selected", p)
            },
            setDisabled: function(n, p, o) {
                if (!o) {
                    o = this.findLis().eq(this.liObj[n])
                }
                if (p) {
                    o.addClass("disabled").children("span.dropdown-item-inner").attr("href", "#").attr("tabindex", -1).attr("aria-disabled", true)
                } else {
                    o.removeClass("disabled").children("span.dropdown-item-inner").removeAttr("href").attr("tabindex", 0).attr("aria-disabled", false)
                }
            },
            isDisabled: function() {
                return this.$element[0].disabled
            },
            checkDisabled: function() {
                var n = this;
                if (this.isDisabled()) {
                    this.$newElement.addClass("disabled");
                    this.$button.addClass("disabled").attr("tabindex", -1).attr("aria-disabled", true)
                } else {
                    if (this.$button.hasClass("disabled")) {
                        this.$newElement.removeClass("disabled");
                        this.$button.removeClass("disabled").attr("aria-disabled", false)
                    }
                    if (this.$button.attr("tabindex") == -1 && !this.$element.data("tabindex")) {
                        this.$button.removeAttr("tabindex")
                    }
                }
                this.$button.click(function() {
                    return !n.isDisabled()
                })
            },
            togglePlaceholder: function() {
                var n = this.$element.val();
                this.$button.toggleClass("bs-placeholder", n === null || n === "" || (n.constructor === Array && n.length === 0))
            },
            tabIndex: function() {
                if (this.$element.data("tabindex") !== this.$element.attr("tabindex") && (this.$element.attr("tabindex") !== -98 && this.$element.attr("tabindex") !== "-98")) {
                    this.$element.data("tabindex", this.$element.attr("tabindex"));
                    this.$button.attr("tabindex", this.$element.data("tabindex"))
                }
                this.$element.attr("tabindex", -98)
            },
            clickListener: function() {
                var n = this,
                    o = g(document);
                o.data("spaceSelect", false);
                this.$button.on("keyup", function(p) {
                    if (/(32)/.test(p.keyCode.toString(10)) && o.data("spaceSelect")) {
                        p.preventDefault();
                        o.data("spaceSelect", false)
                    }
                });
                this.$button.on("click", function() {
                    n.setSize()
                });
                this.$element.on("shown.bs.select", function() {
                    if (!n.options.liveSearch && !n.multiple) {
                        n.$menuInner.find("a.selected").focus()
                    } else {
                        if (!n.multiple) {
                            var p = n.liObj[n.$element[0].selectedIndex];
                            if (typeof p !== "number" || n.options.size === false) {
                                return
                            }
                            var q = n.$lis.eq(p)[0].offsetTop - n.$menuInner[0].offsetTop;
                            q = q - n.$menuInner[0].offsetHeight / 2 + n.sizeInfo.liHeight / 2;
                            n.$menuInner[0].scrollTop = q
                        }
                    }
                });
                this.$menuInner.on("click", "a", function(G) {
                    var v = g(this).find("span.dropdown-item-inner"),
                        q = v.parent().data("originalIndex"),
                        E = n.$element.val(),
                        x = n.$element.prop("selectedIndex"),
                        y = true;
                    if (n.multiple && n.options.maxOptions !== 1) {
                        G.stopPropagation()
                    }
                    G.preventDefault();
                    if (!n.isDisabled() && !v.parent().hasClass("disabled")) {
                        var C = n.$element.find("option"),
                            D = C.eq(q),
                            s = D.prop("selected"),
                            B = D.parent("optgroup"),
                            I = n.options.maxOptions,
                            z = B.data("maxOptions") || false;
                        if (!n.multiple) {
                            C.prop("selected", false);
                            D.prop("selected", true);
                            n.$menuInner.find(".selected").removeClass("selected").find("span.dropdown-item-inner").attr("aria-selected", false);
                            n.setSelected(q, true)
                        } else {
                            D.prop("selected", !s);
                            n.setSelected(q, !s);
                            v.blur();
                            if (I !== false || z !== false) {
                                var r = I < C.filter(":selected").length,
                                    u = z < B.find("option:selected").length;
                                if ((I && r) || (z && u)) {
                                    if (I && I == 1) {
                                        C.prop("selected", false);
                                        D.prop("selected", true);
                                        n.$menuInner.find(".selected").removeClass("selected");
                                        n.setSelected(q, true)
                                    } else {
                                        if (z && z == 1) {
                                            B.find("option:selected").prop("selected", false);
                                            D.prop("selected", true);
                                            var F = v.parent().data("optgroup");
                                            n.$menuInner.find('[data-optgroup="' + F + '"]').removeClass("selected");
                                            n.setSelected(q, true)
                                        } else {
                                            var p = typeof n.options.maxOptionsText === "string" ? [n.options.maxOptionsText, n.options.maxOptionsText] : n.options.maxOptionsText,
                                                t = typeof p === "function" ? p(I, z) : p,
                                                H = t[0].replace("{n}", I),
                                                w = t[1].replace("{n}", z),
                                                A = g('<div class="notify"></div>');
                                            if (t[2]) {
                                                H = H.replace("{var}", t[2][I > 1 ? 0 : 1]);
                                                w = w.replace("{var}", t[2][z > 1 ? 0 : 1])
                                            }
                                            D.prop("selected", false);
                                            n.$menu.append(A);
                                            if (I && r) {
                                                A.append(g("<div>" + H + "</div>"));
                                                y = false;
                                                n.$element.trigger("maxReached.bs.select")
                                            }
                                            if (z && u) {
                                                A.append(g("<div>" + w + "</div>"));
                                                y = false;
                                                n.$element.trigger("maxReachedGrp.bs.select")
                                            }
                                            setTimeout(function() {
                                                n.setSelected(q, false)
                                            }, 10);
                                            A.delay(750).fadeOut(300, function() {
                                                g(this).remove()
                                            })
                                        }
                                    }
                                }
                            }
                        }
                        if (!n.multiple || (n.multiple && n.options.maxOptions === 1)) {
                            n.$button.focus()
                        } else {
                            if (n.options.liveSearch) {
                                n.$searchbox.focus()
                            }
                        }
                        if (y) {
                            if ((E != n.$element.val() && n.multiple) || (x != n.$element.prop("selectedIndex") && !n.multiple)) {
                                m = [q, D.prop("selected"), s];
                                n.$element.triggerNative("change")
                            }
                        }
                    }
                });
                this.$menu.on("click", "a.disabled span.dropdown-item-inner , .popover-title, .popover-title :not(.close)", function(p) {
                    if (p.currentTarget == this) {
                        p.preventDefault();
                        p.stopPropagation();
                        if (n.options.liveSearch && !g(p.target).hasClass("close")) {
                            n.$searchbox.focus()
                        } else {
                            n.$button.focus()
                        }
                    }
                });
                this.$menuInner.on("click", ".dropdown-divider, .dropdown-header", function(p) {
                    p.preventDefault();
                    p.stopPropagation();
                    if (n.options.liveSearch) {
                        n.$searchbox.focus()
                    } else {
                        n.$button.focus()
                    }
                });
                this.$menu.on("click", ".popover-title .close", function() {
                    n.$button.click()
                });
                this.$searchbox.on("click", function(p) {
                    p.stopPropagation()
                });
                this.$menu.on("click", ".actions-btn", function(p) {
                    if (n.options.liveSearch) {
                        n.$searchbox.focus()
                    } else {
                        n.$button.focus()
                    }
                    p.preventDefault();
                    p.stopPropagation();
                    if (g(this).hasClass("bs-select-all")) {
                        n.selectAll()
                    } else {
                        n.deselectAll()
                    }
                });
                this.$element.change(function() {
                    n.render(false);
                    n.$element.trigger("changed.bs.select", m);
                    m = null
                })
            },
            liveSearchListener: function() {
                var o = this,
                    n = g('<li class="no-results"></li>');
                this.$button.on("click.dropdown.data-api", function() {
                    o.$menuInner.find(".active").removeClass("active");
                    if (!!o.$searchbox.val()) {
                        o.$searchbox.val("");
                        o.$lis.not(".is-hidden").removeClass("d-none");
                        if (!!n.parent().length) {
                            n.remove()
                        }
                    }
                    if (!o.multiple) {
                        o.$menuInner.find(".selected").addClass("active")
                    }
                    setTimeout(function() {
                        o.$searchbox.focus()
                    }, 10)
                });
                this.$searchbox.on("click.dropdown.data-api focus.dropdown.data-api touchend.dropdown.data-api", function(p) {
                    p.stopPropagation()
                });
                this.$searchbox.on("input propertychange", function() {
                    o.$lis.not(".is-hidden").removeClass("d-none");
                    o.$lis.filter(".active").removeClass("active");
                    n.remove();
                    if (o.$searchbox.val()) {
                        var q = o.$lis.not(".is-hidden, .dropdown-divider, .dropdown-header"),
                            p;
                        if (o.options.liveSearchNormalize) {
                            p = q.not(":a" + o._searchStyle() + '("' + c(o.$searchbox.val()) + '")')
                        } else {
                            p = q.not(":" + o._searchStyle() + '("' + o.$searchbox.val() + '")')
                        }
                        if (p.length === q.length) {
                            n.html(o.options.noneResultsText.replace("{0}", '"' + l(o.$searchbox.val()) + '"'));
                            o.$menuInner.append(n);
                            o.$lis.addClass("d-none")
                        } else {
                            p.addClass("d-none");
                            var s = o.$lis.not(".d-none"),
                                r;
                            s.each(function(t) {
                                var u = g(this);
                                if (u.hasClass("dropdown-divider")) {
                                    if (r === undefined) {
                                        u.addClass("d-none")
                                    } else {
                                        if (r) {
                                            r.addClass("d-none")
                                        }
                                        r = u
                                    }
                                } else {
                                    if (u.hasClass("dropdown-header") && s.eq(t + 1).data("optgroup") !== u.data("optgroup")) {
                                        u.addClass("d-none")
                                    } else {
                                        r = null
                                    }
                                }
                            });
                            if (r) {
                                r.addClass("d-none")
                            }
                            q.not(".d-none").first().addClass("active");
                            o.$menuInner.scrollTop(0)
                        }
                        o.updatePosition()
                    }
                })
            },
            _searchStyle: function() {
                var n = {
                    begins: "ibegins",
                    startsWith: "ibegins",
                };
                return n[this.options.liveSearchStyle] || "icontains"
            },
            val: function(n) {
                if (typeof n !== "undefined") {
                    this.$element.val(n);
                    this.render();
                    return this.$element
                } else {
                    return this.$element.val()
                }
            },
            changeAll: function(o) {
                if (!this.multiple) {
                    return
                }
                if (typeof o === "undefined") {
                    o = true
                }
                this.findLis();
                var n = this.$element.find("option"),
                    t = this.$lis.not(".dropdown-divider, .dropdown-header, .disabled, .d-none"),
                    p = t.length,
                    s = [];
                if (o) {
                    if (t.filter(".selected").length === t.length) {
                        return
                    }
                } else {
                    if (t.filter(".selected").length === 0) {
                        return
                    }
                }
                t.toggleClass("selected", o);
                for (var q = 0; q < p; q++) {
                    var r = t[q].getAttribute("data-original-index");
                    s[s.length] = n.eq(r)[0]
                }
                g(s).prop("selected", o);
                this.render(false);
                this.togglePlaceholder();
                this.$element.triggerNative("change")
            },
            selectAll: function() {
                return this.changeAll(true)
            },
            deselectAll: function() {
                return this.changeAll(false)
            },
            toggle: function(n) {
                n = n || window.event;
                if (n) {
                    n.stopPropagation()
                }
                this.$button.trigger("click")
            },
            keydown: function(x) {
                var y = g(this),
                    s = y.is("input") ? y.parent().parent() : y.parent(),
                    w, u = s.data("this"),
                    v, q, z, p = ":not(.disabled, .d-none, .dropdown-header, .dropdown-divider)",
                    r = {
                        32: " ",
                        48: "0",
                        49: "1",
                        50: "2",
                        51: "3",
                        52: "4",
                        53: "5",
                        54: "6",
                        55: "7",
                        56: "8",
                        57: "9",
                        59: ";",
                        65: "a",
                        66: "b",
                        67: "c",
                        68: "d",
                        69: "e",
                        70: "f",
                        71: "g",
                        72: "h",
                        73: "i",
                        74: "j",
                        75: "k",
                        76: "l",
                        77: "m",
                        78: "n",
                        79: "o",
                        80: "p",
                        81: "q",
                        82: "r",
                        83: "s",
                        84: "t",
                        85: "u",
                        86: "v",
                        87: "w",
                        88: "x",
                        89: "y",
                        90: "z",
                        96: "0",
                        97: "1",
                        98: "2",
                        99: "3",
                        100: "4",
                        101: "5",
                        102: "6",
                        103: "7",
                        104: "8",
                        105: "9",
                    };
                z = u.$newElement.hasClass("open") || u.$newElement.hasClass("show");
                if (!z && (x.keyCode >= 48 && x.keyCode <= 57 || x.keyCode >= 96 && x.keyCode <= 105 || x.keyCode >= 65 && x.keyCode <= 90)) {
                    if (!u.options.container) {
                        u.setSize();
                        u.$menu.parent().addClass("open show");
                        z = true
                    } else {
                        u.$button.trigger("click")
                    }
                    u.$searchbox.focus();
                    return
                }
                if (u.options.liveSearch) {
                    if (/(^9$|27)/.test(x.keyCode.toString(10)) && z) {
                        x.preventDefault();
                        x.stopPropagation();
                        u.$menuInner.click();
                        u.$button.focus()
                    }
                }
                if (/(38|40)/.test(x.keyCode.toString(10))) {
                    w = u.$lis.filter(p);
                    if (!w.length) {
                        return
                    }
                    if (!u.options.liveSearch) {
                        v = w.index(w.filter(":focus"))
                    } else {
                        v = w.index(w.filter(".active"))
                    }
                    q = u.$menuInner.data("prevIndex");
                    if (x.keyCode == 38) {
                        if ((u.options.liveSearch || v == q) && v != -1) {
                            v--
                        }
                        if (v < 0) {
                            v += w.length
                        }
                    } else {
                        if (x.keyCode == 40) {
                            if (u.options.liveSearch || v == q) {
                                v++
                            }
                            v = v % w.length
                        }
                    }
                    u.$menuInner.data("prevIndex", v);
                    if (!u.options.liveSearch) {
                        w.eq(v).focus()
                    } else {
                        x.preventDefault();
                        if (!y.hasClass("dropdown-toggle")) {
                            w.removeClass("active").eq(v).addClass("active").children("span.dropdown-item-inner").focus();
                            y.focus()
                        }
                    }
                } else {
                    if (!y.is("input")) {
                        var n = [],
                            t, A;
                        w = u.$lis.filter(p);
                        w.each(function(B) {
                            if (g.trim(g(this).children("span.dropdown-item-inner").text().toLowerCase()).substring(0, 1) == r[x.keyCode]) {
                                n.push(B)
                            }
                        });
                        t = g(document).data("keycount");
                        t++;
                        g(document).data("keycount", t);
                        A = g.trim(g(":focus").text().toLowerCase()).substring(0, 1);
                        if (A != r[x.keyCode]) {
                            t = 1;
                            g(document).data("keycount", t)
                        } else {
                            if (t >= n.length) {
                                g(document).data("keycount", 0);
                                if (t > n.length) {
                                    t = 1
                                }
                            }
                        }
                        w.eq(n[t - 1]).children("span.dropdown-item-inner").focus()
                    }
                }
                if ((/(13|32)/.test(x.keyCode.toString(10)) || (/(^9$)/.test(x.keyCode.toString(10)) && u.options.selectOnTab)) && z) {
                    if (!/(32)/.test(x.keyCode.toString(10))) {
                        x.preventDefault()
                    }
                    if (!u.options.liveSearch) {
                        var o = g(":focus");
                        o.click();
                        o.focus();
                        x.preventDefault();
                        g(document).data("spaceSelect", true)
                    } else {
                        if (!/(32)/.test(x.keyCode.toString(10))) {
                            u.$menuInner.find("a.active").click();
                            y.focus()
                        }
                    }
                    g(document).data("keycount", 0)
                }
                if ((/(^9$|27)/.test(x.keyCode.toString(10)) && z && (u.multiple || u.options.liveSearch)) || (/(27)/.test(x.keyCode.toString(10)) && !z)) {
                    u.$menu.parent().removeClass("open");
                    if (u.options.container) {
                        u.$newElement.removeClass("open")
                    }
                    u.$button.focus()
                }
            },
            mobile: function() {
                this.$element.addClass("mobile-device")
            },
            refresh: function() {
                this.$lis = null;
                this.liObj = {};
                this.reloadLi();
                this.render();
                this.checkDisabled();
                this.liHeight(true);
                this.setStyle();
                this.setWidth();
                if (this.$lis) {
                    this.$searchbox.trigger("propertychange")
                }
                this.$element.trigger("refreshed.bs.select")
            },
            hide: function() {
                this.$newElement.hide()
            },
            show: function() {
                this.$newElement.show()
            },
            remove: function() {
                this.$newElement.remove();
                this.$element.remove()
            },
            destroy: function() {
                this.$newElement.before(this.$element).remove();
                if (this.$bsContainer) {
                    this.$bsContainer.remove()
                } else {
                    this.$menu.remove()
                }
                this.$element.off(".bs.select").removeData("selectpicker").removeClass("bs-select-hidden selectpicker")
            },
        };

        function j(p) {
            var n = arguments;
            var r = p;
            [].shift.apply(n);
            var q;
            var o = this.each(function() {
                var w = g(this);
                if (w.is("select")) {
                    var v = w.data("selectpicker"),
                        t = typeof r == "object" && r;
                    if (!v) {
                        var s = g.extend({}, k.DEFAULTS, g.fn.selectpicker.defaults || {}, w.data(), t);
                        s.template = g.extend({}, k.DEFAULTS.template, (g.fn.selectpicker.defaults ? g.fn.selectpicker.defaults.template : {}), w.data().template, t.template);
                        w.data("selectpicker", (v = new k(this, s)))
                    } else {
                        if (t) {
                            for (var u in t) {
                                if (t.hasOwnProperty(u)) {
                                    v.options[u] = t[u]
                                }
                            }
                        }
                    }
                    if (typeof r == "string") {
                        if (v[r] instanceof Function) {
                            q = v[r].apply(v, n)
                        } else {
                            q = v.options[r]
                        }
                    }
                }
            });
            if (typeof q !== "undefined") {
                return q
            } else {
                return o
            }
        }
        var d = g.fn.selectpicker;
        g.fn.selectpicker = j;
        g.fn.selectpicker.Constructor = k;
        g.fn.selectpicker.noConflict = function() {
            g.fn.selectpicker = d;
            return this
        };
        g(document).data("keycount", 0).on("keydown.bs.select", '.bootstrap-select [data-toggle=dropdown], .bootstrap-select [role="listbox"], .bs-searchbox input', k.prototype.keydown).on("focusin.modal", '.bootstrap-select [data-toggle=dropdown], .bootstrap-select [role="listbox"], .bs-searchbox input', function(n) {
            n.stopPropagation()
        });
        g(window).on("load.bs.select.data-api", function() {
            g(".selectpicker").each(function() {
                var n = g(this);
                j.call(n, n.data())
            })
        })
    })(a)
}));
