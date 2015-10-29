(function(a) {
    "use strict";
    function b(a) {
        return (a || "").toLowerCase();
    }
    var c = "2.1.1";
    a.fn.cycle = function(c) {
        var d;
        return 0 !== this.length || a.isReady ? this.each(function() {
            var d, e, f, g, h = a(this), i = a.fn.cycle.log;
            if (!h.data("cycle.opts")) {
                (h.data("cycle-log")===!1 || c && c.log===!1 || e && e.log===!1) && (i = a.noop), i("--c2 init--"), d = h.data();
                for (var j in d)
                    d.hasOwnProperty(j) && /^cycle[A-Z]+/.test(j) && (g = d[j], f = j.match(/^cycle(.*)/)[1].replace(/^[A-Z]/, b), i(f + ":", g, "(" + typeof g + ")"), d[f] = g);
                e = a.extend({}, a.fn.cycle.defaults, d, c || {}), e.timeoutId = 0, e.paused = e.paused ||!1, e.container = h, e._maxZ = e.maxZ, e.API = a.extend({
                    _container: h
                }, a.fn.cycle.API), e.API.log = i, e.API.trigger = function(a, b) {
                    return e.container.trigger(a, b), e.API;
                }, h.data("cycle.opts", e), h.data("cycle.API", e.API), e.API.trigger("cycle-bootstrap", [e, e.API]), e.API.addInitialSlides(), e.API.preInitSlideshow(), e.slides.length && e.API.initSlideshow();
            }
        }) : (d = {
            s: this.selector,
            c: this.context
        }, a.fn.cycle.log("requeuing slideshow (dom not ready)"), a(function() {
            a(d.s, d.c).cycle(c);
        }), this);
    }, a.fn.cycle.API = {
        opts: function() {
            return this._container.data("cycle.opts");
        },
        addInitialSlides: function() {
            var b = this.opts(), c = b.slides;
            b.slideCount = 0, b.slides = a(), c = c.jquery ? c : b.container.find(c), b.random && c.sort(function() {
                return Math.random() - .5;
            }), b.API.add(c);
        },
        preInitSlideshow: function() {
            var b = this.opts();
            b.API.trigger("cycle-pre-initialize", [b]);
            var c = a.fn.cycle.transitions[b.fx];
            c && a.isFunction(c.preInit) && c.preInit(b), b._preInitialized=!0;
        },
        postInitSlideshow: function() {
            var b = this.opts();
            b.API.trigger("cycle-post-initialize", [b]);
            var c = a.fn.cycle.transitions[b.fx];
            c && a.isFunction(c.postInit) && c.postInit(b);
        },
        initSlideshow: function() {
            var b, c = this.opts(), d = c.container;
            c.API.calcFirstSlide(), "static" == c.container.css("position") && c.container.css("position", "relative"), a(c.slides[c.currSlide]).css({
                opacity: 1,
                display: "block",
                visibility: "visible"
            }), c.API.stackSlides(c.slides[c.currSlide], c.slides[c.nextSlide], !c.reverse), c.pauseOnHover && (c.pauseOnHover!==!0 && (d = a(c.pauseOnHover)), d.hover(function() {
                c.API.pause(!0);
            }, function() {
                c.API.resume(!0);
            })), c.timeout && (b = c.API.getSlideOpts(c.currSlide), c.API.queueTransition(b, b.timeout + c.delay)), c._initialized=!0, c.API.updateView(!0), c.API.trigger("cycle-initialized", [c]), c.API.postInitSlideshow();
        },
        pause: function(b) {
            var c = this.opts(), d = c.API.getSlideOpts(), e = c.hoverPaused || c.paused;
            b ? c.hoverPaused=!0 : c.paused=!0, e || (c.container.addClass("cycle-paused"), c.API.trigger("cycle-paused", [c]).log("cycle-paused"), d.timeout && (clearTimeout(c.timeoutId), c.timeoutId = 0, c._remainingTimeout -= a.now() - c._lastQueue, (0 > c._remainingTimeout || isNaN(c._remainingTimeout)) && (c._remainingTimeout = void 0)));
        },
        resume: function(a) {
            var b = this.opts(), c=!b.hoverPaused&&!b.paused;
            a ? b.hoverPaused=!1 : b.paused=!1, c || (b.container.removeClass("cycle-paused"), 0 === b.slides.filter(":animated").length && b.API.queueTransition(b.API.getSlideOpts(), b._remainingTimeout), b.API.trigger("cycle-resumed", [b, b._remainingTimeout]).log("cycle-resumed"));
        },
        add: function(b, c) {
            var d, e = this.opts(), f = e.slideCount, g=!1;
            "string" == a.type(b) && (b = a.trim(b)), a(b).each(function() {
                var b, d = a(this);
                c ? e.container.prepend(d) : e.container.append(d), e.slideCount++, b = e.API.buildSlideOpts(d), e.slides = c ? a(d).add(e.slides) : e.slides.add(d), e.API.initSlide(b, d, --e._maxZ), d.data("cycle.opts", b), e.API.trigger("cycle-slide-added", [e, b, d]);
            }), e.API.updateView(!0), g = e._preInitialized && 2 > f && e.slideCount >= 1, g && (e._initialized ? e.timeout && (d = e.slides.length, e.nextSlide = e.reverse ? d - 1 : 1, e.timeoutId || e.API.queueTransition(e)) : e.API.initSlideshow());
        },
        calcFirstSlide: function() {
            var a, b = this.opts();
            a = parseInt(b.startingSlide || 0, 10), (a >= b.slides.length || 0 > a) && (a = 0), b.currSlide = a, b.reverse ? (b.nextSlide = a - 1, 0 > b.nextSlide && (b.nextSlide = b.slides.length - 1)) : (b.nextSlide = a + 1, b.nextSlide == b.slides.length && (b.nextSlide = 0));
        },
        calcNextSlide: function() {
            var a, b = this.opts();
            b.reverse ? (a = 0 > b.nextSlide - 1, b.nextSlide = a ? b.slideCount - 1 : b.nextSlide - 1, b.currSlide = a ? 0 : b.nextSlide + 1) : (a = b.nextSlide + 1 == b.slides.length, b.nextSlide = a ? 0 : b.nextSlide + 1, b.currSlide = a ? b.slides.length - 1 : b.nextSlide - 1);
        },
        calcTx: function(b, c) {
            var d, e = b;
            return c && e.manualFx && (d = a.fn.cycle.transitions[e.manualFx]), d || (d = a.fn.cycle.transitions[e.fx]), d || (d = a.fn.cycle.transitions.fade, e.API.log('Transition "' + e.fx + '" not found.  Using fade.')), d;
        },
        prepareTx: function(a, b) {
            var c, d, e, f, g, h = this.opts();
            return 2 > h.slideCount ? (h.timeoutId = 0, void 0) : (!a || h.busy&&!h.manualTrump || (h.API.stopTransition(), h.busy=!1, clearTimeout(h.timeoutId), h.timeoutId = 0), h.busy || (0 !== h.timeoutId || a) && (d = h.slides[h.currSlide], e = h.slides[h.nextSlide], f = h.API.getSlideOpts(h.nextSlide), g = h.API.calcTx(f, a), h._tx = g, a && void 0 !== f.manualSpeed && (f.speed = f.manualSpeed), h.nextSlide != h.currSlide && (a ||!h.paused&&!h.hoverPaused && h.timeout) ? (h.API.trigger("cycle-before", [f, d, e, b]), g.before && g.before(f, d, e, b), c = function() {
                h.busy=!1, h.container.data("cycle.opts") && (g.after && g.after(f, d, e, b), h.API.trigger("cycle-after", [f, d, e, b]), h.API.queueTransition(f), h.API.updateView(!0));
            }, h.busy=!0, g.transition ? g.transition(f, d, e, b, c) : h.API.doTransition(f, d, e, b, c), h.API.calcNextSlide(), h.API.updateView()) : h.API.queueTransition(f)), void 0);
        },
        doTransition: function(b, c, d, e, f) {
            var g = b, h = a(c), i = a(d), j = function() {
                i.animate(g.animIn || {
                    opacity: 1
                }, g.speed, g.easeIn || g.easing, f);
            };
            i.css(g.cssBefore || {}), h.animate(g.animOut || {}, g.speed, g.easeOut || g.easing, function() {
                h.css(g.cssAfter || {}), g.sync || j();
            }), g.sync && j();
        },
        queueTransition: function(b, c) {
            var d = this.opts(), e = void 0 !== c ? c : b.timeout;
            return 0 === d.nextSlide && 0===--d.loop ? (d.API.log("terminating; loop=0"), d.timeout = 0, e ? setTimeout(function() {
                d.API.trigger("cycle-finished", [d]);
            }, e) : d.API.trigger("cycle-finished", [d]), d.nextSlide = d.currSlide, void 0) : (e && (d._lastQueue = a.now(), void 0 === c && (d._remainingTimeout = b.timeout), d.paused || d.hoverPaused || (d.timeoutId = setTimeout(function() {
                d.API.prepareTx(!1, !d.reverse);
            }, e))), void 0);
        },
        stopTransition: function() {
            var a = this.opts();
            a.slides.filter(":animated").length && (a.slides.stop(!1, !0), a.API.trigger("cycle-transition-stopped", [a])), a._tx && a._tx.stopTransition && a._tx.stopTransition(a);
        },
        advanceSlide: function(a) {
            var b = this.opts();
            return clearTimeout(b.timeoutId), b.timeoutId = 0, b.nextSlide = b.currSlide + a, 0 > b.nextSlide ? b.nextSlide = b.slides.length - 1 : b.nextSlide >= b.slides.length && (b.nextSlide = 0), b.API.prepareTx(!0, a >= 0), !1;
        },
        buildSlideOpts: function(c) {
            var d, e, f = this.opts(), g = c.data() || {};
            for (var h in g)
                g.hasOwnProperty(h) && /^cycle[A-Z]+/.test(h) && (d = g[h], e = h.match(/^cycle(.*)/)[1].replace(/^[A-Z]/, b), f.API.log("[" + (f.slideCount - 1) + "]", e + ":", d, "(" + typeof d + ")"), g[e] = d);
            g = a.extend({}, a.fn.cycle.defaults, f, g), g.slideNum = f.slideCount;
            try {
                delete g.API, delete g.slideCount, delete g.currSlide, delete g.nextSlide, delete g.slides;
            } catch (i) {}
            return g;
        },
        getSlideOpts: function(b) {
            var c = this.opts();
            void 0 === b && (b = c.currSlide);
            var d = c.slides[b], e = a(d).data("cycle.opts");
            return a.extend({}, c, e);
        },
        initSlide: function(b, c, d) {
            var e = this.opts();
            c.css(b.slideCss || {}), d > 0 && c.css("zIndex", d), isNaN(b.speed) && (b.speed = a.fx.speeds[b.speed] || a.fx.speeds._default), b.sync || (b.speed = b.speed / 2), c.addClass(e.slideClass);
        },
        updateView: function(a, b) {
            var c = this.opts();
            if (c._initialized) {
                var d = c.API.getSlideOpts(), e = c.slides[c.currSlide];
                !a && b!==!0 && (c.API.trigger("cycle-update-view-before", [c, d, e]), 0 > c.updateView) || (c.slideActiveClass && c.slides.removeClass(c.slideActiveClass).eq(c.currSlide).addClass(c.slideActiveClass), a && c.hideNonActive && c.slides.filter(":not(." + c.slideActiveClass + ")").css("visibility", "hidden"), 0 === c.updateView && setTimeout(function() {
                    c.API.trigger("cycle-update-view", [c, d, e, a]);
                }, d.speed / (c.sync ? 2 : 1)), 0 !== c.updateView && c.API.trigger("cycle-update-view", [c, d, e, a]), a && c.API.trigger("cycle-update-view-after", [c, d, e]));
            }
        },
        getComponent: function(b) {
            var c = this.opts(), d = c[b];
            return "string" == typeof d ? /^\s*[\>|\+|~]/.test(d) ? c.container.find(d) : a(d) : d.jquery ? d : a(d);
        },
        stackSlides: function(b, c, d) {
            var e = this.opts();
            b || (b = e.slides[e.currSlide], c = e.slides[e.nextSlide], d=!e.reverse), a(b).css("zIndex", e.maxZ);
            var f, g = e.maxZ - 2, h = e.slideCount;
            if (d) {
                for (f = e.currSlide + 1; h > f; f++)
                    a(e.slides[f]).css("zIndex", g--);
                for (f = 0; e.currSlide > f; f++)
                    a(e.slides[f]).css("zIndex", g--);
            } else {
                for (f = e.currSlide - 1; f >= 0; f--)
                    a(e.slides[f]).css("zIndex", g--);
                for (f = h - 1; f > e.currSlide; f--)
                    a(e.slides[f]).css("zIndex", g--);
            }
            a(c).css("zIndex", e.maxZ - 1);
        },
        getSlideIndex: function(a) {
            return this.opts().slides.index(a);
        }
    }, a.fn.cycle.log = function() {
        window.console && console.log && console.log("[cycle2] " + Array.prototype.join.call(arguments, " "));
    }, a.fn.cycle.version = function() {
        return "Cycle2: " + c;
    }, a.fn.cycle.transitions = {
        custom: {},
        none: {
            before: function(a, b, c, d) {
                a.API.stackSlides(c, b, d), a.cssBefore = {
                    opacity: 1,
                    visibility: "visible",
                    display: "block"
                };
            }
        },
        fade: {
            before: function(b, c, d, e) {
                var f = b.API.getSlideOpts(b.nextSlide).slideCss || {};
                b.API.stackSlides(c, d, e), b.cssBefore = a.extend(f, {
                    opacity: 0,
                    visibility: "visible",
                    display: "block"
                }), b.animIn = {
                    opacity: 1
                }, b.animOut = {
                    opacity: 0
                };
            }
        },
        fadeout: {
            before: function(b, c, d, e) {
                var f = b.API.getSlideOpts(b.nextSlide).slideCss || {};
                b.API.stackSlides(c, d, e), b.cssBefore = a.extend(f, {
                    opacity: 1,
                    visibility: "visible",
                    display: "block"
                }), b.animOut = {
                    opacity: 0
                };
            }
        },
        scrollHorz: {
            before: function(a, b, c, d) {
                a.API.stackSlides(b, c, d);
                var e = a.container.css("overflow", "hidden").width();
                a.cssBefore = {
                    left: d ? e: - e,
                    top: 0,
                    opacity: 1,
                    visibility: "visible",
                    display: "block"
                }, a.cssAfter = {
                    zIndex: a._maxZ - 2,
                    left: 0
                }, a.animIn = {
                    left: 0
                }, a.animOut = {
                    left: d?-e: e
                };
            }
        }
    }, a.fn.cycle.defaults = {
        allowWrap: !0,
        autoSelector: ".cycle-slideshow[data-cycle-auto-init!=false]",
        delay: 0,
        easing: null,
        fx: "fade",
        hideNonActive: !0,
        loop: 0,
        manualFx: void 0,
        manualSpeed: void 0,
        manualTrump: !0,
        maxZ: 100,
        pauseOnHover: !1,
        reverse: !1,
        slideActiveClass: "cycle-slide-active",
        slideClass: "cycle-slide",
        slideCss: {
            position: "absolute",
            top: 0,
            left: 0
        }, slides : "> img", speed : 500, startingSlide : 0, sync : !0, timeout : 4e3, updateView : 0
    }, a(document).ready(function() {
        a(a.fn.cycle.defaults.autoSelector).cycle();
    });
})(jQuery), function(a) {
    "use strict";
    function b(b, d) {
        var e, f, g, h = d.autoHeight;
        if ("container" == h)
            f = a(d.slides[d.currSlide]).outerHeight(), d.container.height(f);
        else if (d._autoHeightRatio)
            d.container.height(d.container.width() / d._autoHeightRatio);
        else if ("calc" === h || "number" == a.type(h) && h >= 0) {
            if (g = "calc" === h ? c(b, d) : h >= d.slides.length ? 0 : h, g == d._sentinelIndex)
                return;
            d._sentinelIndex = g, d._sentinel && d._sentinel.remove(), e = a(d.slides[g].cloneNode(!0)), e.removeAttr("id name rel").find("[id],[name],[rel]").removeAttr("id name rel"), e.css({
                position: "static",
                visibility: "hidden",
                display: "block"
            }).prependTo(d.container).addClass("cycle-sentinel cycle-slide").removeClass("cycle-slide-active"), e.find("*").css("visibility", "hidden"), d._sentinel = e;
        }
    }
    function c(b, c) {
        var d = 0, e =- 1;
        return c.slides.each(function(b) {
            var c = a(this).height();
            c > e && (e = c, d = b);
        }), d;
    }
    function d(b, c, d, e) {
        var f = a(e).outerHeight();
        c.container.animate({
            height: f
        }, c.autoHeightSpeed, c.autoHeightEasing);
    }
    function e(c, f) {
        f._autoHeightOnResize && (a(window).off("resize orientationchange", f._autoHeightOnResize), f._autoHeightOnResize = null), f.container.off("cycle-slide-added cycle-slide-removed", b), f.container.off("cycle-destroyed", e), f.container.off("cycle-before", d), f._sentinel && (f._sentinel.remove(), f._sentinel = null);
    }
    a.extend(a.fn.cycle.defaults, {
        autoHeight: 0,
        autoHeightSpeed: 250,
        autoHeightEasing: null
    }), a(document).on("cycle-initialized", function(c, f) {
        function g() {
            b(c, f);
        }
        var h, i = f.autoHeight, j = a.type(i), k = null;
        ("string" === j || "number" === j) && (f.container.on("cycle-slide-added cycle-slide-removed", b), f.container.on("cycle-destroyed", e), "container" == i ? f.container.on("cycle-before", d) : "string" === j && /\d+\:\d+/.test(i) && (h = i.match(/(\d+)\:(\d+)/), h = h[1] / h[2], f._autoHeightRatio = h), "number" !== j && (f._autoHeightOnResize = function() {
            clearTimeout(k), k = setTimeout(g, 50);
        }, a(window).on("resize orientationchange", f._autoHeightOnResize)), setTimeout(g, 30));
    });
}(jQuery), function(a) {
    "use strict";
    a.extend(a.fn.cycle.defaults, {
        caption: "> .cycle-caption",
        captionTemplate: "{{slideNum}} / {{slideCount}}",
        overlay: "> .cycle-overlay",
        overlayTemplate: "<div>{{title}}</div><div>{{desc}}</div>",
        captionModule: "caption"
    }), a(document).on("cycle-update-view", function(b, c, d, e) {
        "caption" === c.captionModule && a.each(["caption", "overlay"], function() {
            var a = this, b = d[a + "Template"], f = c.API.getComponent(a);
            f.length && b ? (f.html(c.API.tmpl(b, d, c, e)), f.show()) : f.hide();
        });
    }), a(document).on("cycle-destroyed", function(b, c) {
        var d;
        a.each(["caption", "overlay"], function() {
            var a = this, b = c[a + "Template"];
            c[a] && b && (d = c.API.getComponent("caption"), d.empty());
        });
    });
}(jQuery), function(a) {
    "use strict";
    var b = a.fn.cycle;
    a.fn.cycle = function(c) {
        var d, e, f, g = a.makeArray(arguments);
        return "number" == a.type(c) ? this.cycle("goto", c) : "string" == a.type(c) ? this.each(function() {
            var h;
            return d = c, f = a(this).data("cycle.opts"), void 0 === f ? (b.log('slideshow must be initialized before sending commands; "' + d + '" ignored'), void 0) : (d = "goto" == d ? "jump" : d, e = f.API[d], a.isFunction(e) ? (h = a.makeArray(g), h.shift(), e.apply(f.API, h)) : (b.log("unknown command: ", d), void 0));
        }) : b.apply(this, arguments);
    }, a.extend(a.fn.cycle, b), a.extend(b.API, {
        next: function() {
            var a = this.opts();
            if (!a.busy || a.manualTrump) {
                var b = a.reverse?-1 : 1;
                a.allowWrap===!1 && a.currSlide + b >= a.slideCount || (a.API.advanceSlide(b), a.API.trigger("cycle-next", [a]).log("cycle-next"));
            }
        },
        prev: function() {
            var a = this.opts();
            if (!a.busy || a.manualTrump) {
                var b = a.reverse ? 1: - 1;
                a.allowWrap===!1 && 0 > a.currSlide + b || (a.API.advanceSlide(b), a.API.trigger("cycle-prev", [a]).log("cycle-prev"));
            }
        },
        destroy: function() {
            this.stop();
            var b = this.opts(), c = a.isFunction(a._data) ? a._data: a.noop;
            clearTimeout(b.timeoutId), b.timeoutId = 0, b.API.stop(), b.API.trigger("cycle-destroyed", [b]).log("cycle-destroyed"), b.container.removeData(), c(b.container[0], "parsedAttrs", !1), b.retainStylesOnDestroy || (b.container.removeAttr("style"), b.slides.removeAttr("style"), b.slides.removeClass(b.slideActiveClass)), b.slides.each(function() {
                a(this).removeData(), c(this, "parsedAttrs", !1);
            });
        },
        jump: function(a) {
            var b, c = this.opts();
            if (!c.busy || c.manualTrump) {
                var d = parseInt(a, 10);
                if (isNaN(d) || 0 > d || d >= c.slides.length)
                    return c.API.log("goto: invalid slide index: " + d), void 0;
                if (d == c.currSlide)
                    return c.API.log("goto: skipping, already on slide", d), void 0;
                c.nextSlide = d, clearTimeout(c.timeoutId), c.timeoutId = 0, c.API.log("goto: ", d, " (zero-index)"), b = c.currSlide < c.nextSlide, c.API.prepareTx(!0, b);
            }
        },
        stop: function() {
            var b = this.opts(), c = b.container;
            clearTimeout(b.timeoutId), b.timeoutId = 0, b.API.stopTransition(), b.pauseOnHover && (b.pauseOnHover!==!0 && (c = a(b.pauseOnHover)), c.off("mouseenter mouseleave")), b.API.trigger("cycle-stopped", [b]).log("cycle-stopped");
        },
        reinit: function() {
            var a = this.opts();
            a.API.destroy(), a.container.cycle();
        },
        remove: function(b) {
            for (var c, d, e = this.opts(), f = [], g = 1, h = 0; e.slides.length > h; h++)
                c = e.slides[h], h == b ? d = c : (f.push(c), a(c).data("cycle.opts").slideNum = g, g++);
            d && (e.slides = a(f), e.slideCount--, a(d).remove(), b == e.currSlide ? e.API.advanceSlide(1) : e.currSlide > b ? e.currSlide-- : e.currSlide++, e.API.trigger("cycle-slide-removed", [e, b, d]).log("cycle-slide-removed"), e.API.updateView());
        }
    }), a(document).on("click.cycle", "[data-cycle-cmd]", function(b) {
        b.preventDefault();
        var c = a(this), d = c.data("cycle-cmd"), e = c.data("cycle-context") || ".cycle-slideshow";
        a(e).cycle(d, c.data("cycle-arg"));
    });
}(jQuery), function(a) {
    "use strict";
    function b(b, c) {
        var d;
        return b._hashFence ? (b._hashFence=!1, void 0) : (d = window.location.hash.substring(1), b.slides.each(function(e) {
            if (a(this).data("cycle-hash") == d) {
                if (c===!0)
                    b.startingSlide = e;
                else {
                    var f = e > b.currSlide;
                    b.nextSlide = e, b.API.prepareTx(!0, f);
                }
                return !1;
            }
        }), void 0);
    }
    a(document).on("cycle-pre-initialize", function(c, d) {
        b(d, !0), d._onHashChange = function() {
            b(d, !1);
        }, a(window).on("hashchange", d._onHashChange);
    }), a(document).on("cycle-update-view", function(a, b, c) {
        c.hash && "#" + c.hash != window.location.hash && (b._hashFence=!0, window.location.hash = c.hash);
    }), a(document).on("cycle-destroyed", function(b, c) {
        c._onHashChange && a(window).off("hashchange", c._onHashChange);
    });
}(jQuery), function(a) {
    "use strict";
    a.extend(a.fn.cycle.defaults, {
        loader: !1
    }), a(document).on("cycle-bootstrap", function(b, c) {
        function d(b, d) {
            function f(b) {
                var f;
                "wait" == c.loader ? (h.push(b), 0 === j && (h.sort(g), e.apply(c.API, [h, d]), c.container.removeClass("cycle-loading"))) : (f = a(c.slides[c.currSlide]), e.apply(c.API, [b, d]), f.show(), c.container.removeClass("cycle-loading"));
            }
            function g(a, b) {
                return a.data("index") - b.data("index");
            }
            var h = [];
            if ("string" == a.type(b))
                b = a.trim(b);
            else if ("array" === a.type(b))
                for (var i = 0; b.length > i; i++)
                    b[i] = a(b[i])[0];
            b = a(b);
            var j = b.length;
            j && (b.css("visibility", "hidden").appendTo("body").each(function(b) {
                function g() {
                    0===--i && (--j, f(k));
                }
                var i = 0, k = a(this), l = k.is("img") ? k: k.find("img");
                return k.data("index", b), l = l.filter(":not(.cycle-loader-ignore)").filter(':not([src=""])'), l.length ? (i = l.length, l.each(function() {
                    this.complete ? g() : a(this).load(function() {
                        g();
                    }).on("error", function() {
                        0===--i && (c.API.log("slide skipped; img not loaded:", this.src), 0===--j && "wait" == c.loader && e.apply(c.API, [h, d]));
                    });
                }), void 0) : (--j, h.push(k), void 0);
            }), j && c.container.addClass("cycle-loading"));
        }
        var e;
        c.loader && (e = c.API.add, c.API.add = d);
    });
}(jQuery), function(a) {
    "use strict";
    function b(b, c, d) {
        var e, f = b.API.getComponent("pager");
        f.each(function() {
            var f = a(this);
            if (c.pagerTemplate) {
                var g = b.API.tmpl(c.pagerTemplate, c, b, d[0]);
                e = a(g).appendTo(f);
            } else
                e = f.children().eq(b.slideCount - 1);
            e.on(b.pagerEvent, function(a) {
                a.preventDefault(), b.API.page(f, a.currentTarget);
            });
        });
    }
    function c(a, b) {
        var c = this.opts();
        if (!c.busy || c.manualTrump) {
            var d = a.children().index(b), e = d, f = e > c.currSlide;
            c.currSlide != e && (c.nextSlide = e, c.API.prepareTx(!0, f), c.API.trigger("cycle-pager-activated", [c, a, b]));
        }
    }
    a.extend(a.fn.cycle.defaults, {
        pager: "> .cycle-pager",
        pagerActiveClass: "cycle-pager-active",
        pagerEvent: "click.cycle",
        pagerTemplate: "<span>&bull;</span>"
    }), a(document).on("cycle-bootstrap", function(a, c, d) {
        d.buildPagerLink = b;
    }), a(document).on("cycle-slide-added", function(a, b, d, e) {
        b.pager && (b.API.buildPagerLink(b, d, e), b.API.page = c);
    }), a(document).on("cycle-slide-removed", function(b, c, d) {
        if (c.pager) {
            var e = c.API.getComponent("pager");
            e.each(function() {
                var b = a(this);
                a(b.children()[d]).remove();
            });
        }
    }), a(document).on("cycle-update-view", function(b, c) {
        var d;
        c.pager && (d = c.API.getComponent("pager"), d.each(function() {
            a(this).children().removeClass(c.pagerActiveClass).eq(c.currSlide).addClass(c.pagerActiveClass);
        }));
    }), a(document).on("cycle-destroyed", function(a, b) {
        var c = b.API.getComponent("pager");
        c && (c.children().off(b.pagerEvent), b.pagerTemplate && c.empty());
    });
}(jQuery), function(a) {
    "use strict";
    a.extend(a.fn.cycle.defaults, {
        next: "> .cycle-next",
        nextEvent: "click.cycle",
        disabledClass: "disabled",
        prev: "> .cycle-prev",
        prevEvent: "click.cycle",
        swipe: !1
    }), a(document).on("cycle-initialized", function(a, b) {
        if (b.API.getComponent("next").on(b.nextEvent, function(a) {
                a.preventDefault(), b.API.next();
            }), b.API.getComponent("prev").on(b.prevEvent, function(a) {
                a.preventDefault(), b.API.prev();
            }), b.swipe) {
            var c = b.swipeVert ? "swipeUp.cycle": "swipeLeft.cycle swipeleft.cycle", d = b.swipeVert ? "swipeDown.cycle": "swipeRight.cycle swiperight.cycle";
            b.container.on(c, function() {
                b.API.next();
            }), b.container.on(d, function() {
                b.API.prev();
            });
        }
    }), a(document).on("cycle-update-view", function(a, b) {
        if (!b.allowWrap) {
            var c = b.disabledClass, d = b.API.getComponent("next"), e = b.API.getComponent("prev"), f = b._prevBoundry || 0, g = void 0 !== b._nextBoundry ? b._nextBoundry : b.slideCount - 1;
            b.currSlide == g ? d.addClass(c).prop("disabled", !0) : d.removeClass(c).prop("disabled", !1), b.currSlide === f ? e.addClass(c).prop("disabled", !0) : e.removeClass(c).prop("disabled", !1);
        }
    }), a(document).on("cycle-destroyed", function(a, b) {
        b.API.getComponent("prev").off(b.nextEvent), b.API.getComponent("next").off(b.prevEvent), b.container.off("swipeleft.cycle swiperight.cycle swipeLeft.cycle swipeRight.cycle swipeUp.cycle swipeDown.cycle");
    });
}(jQuery), function(a) {
    "use strict";
    a.extend(a.fn.cycle.defaults, {
        progressive: !1
    }), a(document).on("cycle-pre-initialize", function(b, c) {
        if (c.progressive) {
            var d, e, f = c.API, g = f.next, h = f.prev, i = f.prepareTx, j = a.type(c.progressive);
            if ("array" == j)
                d = c.progressive;
            else if (a.isFunction(c.progressive))
                d = c.progressive(c);
            else if ("string" == j) {
                if (e = a(c.progressive), d = a.trim(e.html()), !d)
                    return;
                if (/^(\[)/.test(d))
                    try {
                        d = a.parseJSON(d);
                    } catch (k) {
                        return f.log("error parsing progressive slides", k), void 0;
                    } else
                    d = d.split(RegExp(e.data("cycle-split") || "\n")), d[d.length - 1] || d.pop();
            }
            i && (f.prepareTx = function(a, b) {
                var e, f;
                return a || 0 === d.length ? (i.apply(c.API, [a, b]), void 0) : (b && c.currSlide == c.slideCount - 1 ? (f = d[0], d = d.slice(1), c.container.one("cycle-slide-added", function(a, b) {
                    setTimeout(function() {
                        b.API.advanceSlide(1);
                    }, 50);
                }), c.API.add(f)) : b || 0 !== c.currSlide ? i.apply(c.API, [a, b]) : (e = d.length - 1, f = d[e], d = d.slice(0, e), c.container.one("cycle-slide-added", function(a, b) {
                    setTimeout(function() {
                        b.currSlide = 1, b.API.advanceSlide( - 1);
                    }, 50);
                }), c.API.add(f, !0)), void 0);
            }), g && (f.next = function() {
                var a = this.opts();
                if (d.length && a.currSlide == a.slideCount - 1) {
                    var b = d[0];
                    d = d.slice(1), a.container.one("cycle-slide-added", function(a, b) {
                        g.apply(b.API), b.container.removeClass("cycle-loading");
                    }), a.container.addClass("cycle-loading"), a.API.add(b);
                } else
                    g.apply(a.API);
            }), h && (f.prev = function() {
                var a = this.opts();
                if (d.length && 0 === a.currSlide) {
                    var b = d.length - 1, c = d[b];
                    d = d.slice(0, b), a.container.one("cycle-slide-added", function(a, b) {
                        b.currSlide = 1, b.API.advanceSlide( - 1), b.container.removeClass("cycle-loading");
                    }), a.container.addClass("cycle-loading"), a.API.add(c, !0);
                } else
                    h.apply(a.API);
            });
        }
    });
}(jQuery), function(a) {
    "use strict";
    a.extend(a.fn.cycle.defaults, {
        tmplRegex: "{{((.)?.*?)}}"
    }), a.extend(a.fn.cycle.API, {
        tmpl: function(b, c) {
            var d = RegExp(c.tmplRegex || a.fn.cycle.defaults.tmplRegex, "g"), e = a.makeArray(arguments);
            return e.shift(), b.replace(d, function(b, c) {
                var d, f, g, h, i = c.split(".");
                for (d = 0; e.length > d; d++)
                    if (g = e[d]) {
                        if (i.length > 1)
                            for (h = g, f = 0; i.length > f; f++)
                                g = h, h = h[i[f]] || c;
                        else
                            h = g[c];
                        if (a.isFunction(h))
                            return h.apply(g, e);
                        if (void 0 !== h && null !== h && h != c)
                            return h;
                    }
                return c;
            });
        }
    });
}(jQuery);
(function(a) {
    "use strict";
    a.extend(a.fn.cycle.defaults, {
        centerHorz: !1,
        centerVert: !1
    }), a(document).on("cycle-pre-initialize", function(b, c) {
        function d() {
            clearTimeout(i), i = setTimeout(g, 50);
        }
        function e() {
            clearTimeout(i), clearTimeout(j), a(window).off("resize orientationchange", d);
        }
        function f() {
            c.slides.each(h);
        }
        function g() {
            h.apply(c.container.find("." + c.slideActiveClass)), clearTimeout(j), j = setTimeout(f, 50);
        }
        function h() {
            var b = a(this), d = c.container.width(), e = c.container.height(), f = b.outerWidth(), g = b.outerHeight();
            f && (c.centerHorz && d >= f && b.css("marginLeft", (d - f) / 2), c.centerVert && e >= g && b.css("marginTop", (e - g) / 2));
        }
        if (c.centerHorz || c.centerVert) {
            var i, j;
            a(window).on("resize orientationchange load", d), c.container.on("cycle-destroyed", e), c.container.on("cycle-initialized cycle-slide-added cycle-slide-removed", function() {
                d();
            }), g();
        }
    });
})(jQuery);
(function(a) {
    "use strict";
    a.event.special.swipe = a.event.special.swipe || {
        scrollSupressionThreshold: 10,
        durationThreshold: 1e3,
        horizontalDistanceThreshold: 30,
        verticalDistanceThreshold: 75,
        setup: function() {
            var b = a(this);
            b.bind("touchstart", function(c) {
                function d(b) {
                    if (g) {
                        var c = b.originalEvent.touches ? b.originalEvent.touches[0]: b;
                        e = {
                            time: new Date().getTime(),
                            coords: [c.pageX, c.pageY]
                        }, Math.abs(g.coords[0] - e.coords[0]) > a.event.special.swipe.scrollSupressionThreshold && b.preventDefault();
                    }
                }
                var e, f = c.originalEvent.touches ? c.originalEvent.touches[0]: c, g = {
                    time: new Date().getTime(),
                    coords: [f.pageX, f.pageY],
                    origin: a(c.target)
                };
                b.bind("touchmove", d).one("touchend", function() {
                    b.unbind("touchmove", d), g && e && e.time - g.time < a.event.special.swipe.durationThreshold && Math.abs(g.coords[0] - e.coords[0]) > a.event.special.swipe.horizontalDistanceThreshold && Math.abs(g.coords[1] - e.coords[1]) < a.event.special.swipe.verticalDistanceThreshold && g.origin.trigger("swipe").trigger(g.coords[0] > e.coords[0] ? "swipeleft" : "swiperight"), g = e = void 0;
                });
            });
        }
    }, a.event.special.swipeleft = a.event.special.swipeleft || {
        setup: function() {
            a(this).bind("swipe", a.noop);
        }
    }, a.event.special.swiperight = a.event.special.swiperight || a.event.special.swipeleft;
})(jQuery);
var VIMEO_THUMB_API_URL = 'http://vimeo.com/api/v2/video/%id.json', YOUTUBE_THUMB_API_URL = 'http://img.youtube.com/vi/%id/hqdefault.jpg';
$(window).load(function() {
    $('.gallery-slideshow').cycle();
    $(".gallery-plugin").show();
    $('.gallery-slideshow').on('cycle-before', function(a, b, c, d) {
        $(".caption.slideshow-caption p").fadeOut();
    });
    $('.gallery-slideshow').on('cycle-after', function(a, b, c, d) {
        $(".caption.slideshow-caption p").stop().fadeIn();
    });
});
!function(a) {
    "use strict";
    a(function() {
        a.support.transition = function() {
            var a = function() {
                var a = document.createElement("bootstrap"), b = {
                    WebkitTransition: "webkitTransitionEnd",
                    MozTransition: "transitionend",
                    OTransition: "oTransitionEnd otransitionend",
                    transition: "transitionend"
                }, c;
                for (c in b)
                    if (a.style[c] !== undefined)
                        return b[c];
            }();
            return a && {
                    end: a
                };
        }();
    });
}(window.jQuery), !function(a) {
    "use strict";
    var b = '[data-dismiss="alert"]', c = function(c) {
        a(c).on("click", b, this.close);
    };
    c.prototype.close = function(b) {
        function c() {
            f.trigger("closed").remove();
        }
        var d = a(this), e = d.attr("data-target"), f;
        e || (e = d.attr("href"), e = e && e.replace(/.*(?=#[^\s]*$)/, "")), f = a(e), b && b.preventDefault(), f.length || (f = d.hasClass("alert") ? d : d.parent()), f.trigger(b = a.Event("close"));
        if (b.isDefaultPrevented())
            return;
        f.removeClass("in"), a.support.transition && f.hasClass("fade") ? f.on(a.support.transition.end, c) : c();
    }, a.fn.alert = function(b) {
        return this.each(function() {
            var d = a(this), e = d.data("alert");
            e || d.data("alert", e = new c(this)), typeof b == "string" && e[b].call(d);
        });
    }, a.fn.alert.Constructor = c, a(document).on("click.alert.data-api", b, c.prototype.close);
}(window.jQuery), !function(a) {
    "use strict";
    var b = function(b, c) {
        this.$element = a(b), this.options = a.extend({}, a.fn.button.defaults, c);
    };
    b.prototype.setState = function(a) {
        var b = "disabled", c = this.$element, d = c.data(), e = c.is("input") ? "val": "html";
        a += "Text", d.resetText || c.data("resetText", c[e]()), c[e](d[a] || this.options[a]), setTimeout(function() {
            a == "loadingText" ? c.addClass(b).attr(b, b) : c.removeClass(b).removeAttr(b);
        }, 0);
    }, b.prototype.toggle = function() {
        var a = this.$element.closest('[data-toggle="buttons-radio"]');
        a && a.find(".active").removeClass("active"), this.$element.toggleClass("active");
    }, a.fn.button = function(c) {
        return this.each(function() {
            var d = a(this), e = d.data("button"), f = typeof c == "object" && c;
            e || d.data("button", e = new b(this, f)), c == "toggle" ? e.toggle() : c && e.setState(c);
        });
    }, a.fn.button.defaults = {
        loadingText: "loading..."
    }, a.fn.button.Constructor = b, a(document).on("click.button.data-api", "[data-toggle^=button]", function(b) {
        var c = a(b.target);
        c.hasClass("btn") || (c = c.closest(".btn")), c.button("toggle");
    });
}(window.jQuery), !function(a) {
    "use strict";
    var b = function(b, c) {
        this.$element = a(b), this.options = c, this.options.slide && this.slide(this.options.slide), this.options.pause == "hover" && this.$element.on("mouseenter", a.proxy(this.pause, this)).on("mouseleave", a.proxy(this.cycle, this));
    };
    b.prototype = {
        cycle: function(b) {
            return b || (this.paused=!1), this.options.interval&&!this.paused && (this.interval = setInterval(a.proxy(this.next, this), this.options.interval)), this;
        },
        to: function(b) {
            var c = this.$element.find(".item.active"), d = c.parent().children(), e = d.index(c), f = this;
            if (b > d.length - 1 || b < 0)
                return;
            return this.sliding ? this.$element.one("slid", function() {
                f.to(b);
            }) : e == b ? this.pause().cycle() : this.slide(b > e ? "next" : "prev", a(d[b]));
        },
        pause: function(b) {
            return b || (this.paused=!0), this.$element.find(".next, .prev").length && a.support.transition.end && (this.$element.trigger(a.support.transition.end), this.cycle()), clearInterval(this.interval), this.interval = null, this;
        },
        next: function() {
            if (this.sliding)
                return;
            return this.slide("next");
        },
        prev: function() {
            if (this.sliding)
                return;
            return this.slide("prev");
        },
        slide: function(b, c) {
            var d = this.$element.find(".item.active"), e = c || d[b](), f = this.interval, g = b == "next" ? "left": "right", h = b == "next" ? "first": "last", i = this, j;
            this.sliding=!0, f && this.pause(), e = e.length ? e : this.$element.find(".item")[h](), j = a.Event("slide", {
                relatedTarget: e[0]
            });
            if (e.hasClass("active"))
                return;
            if (a.support.transition && this.$element.hasClass("slide")) {
                this.$element.trigger(j);
                if (j.isDefaultPrevented())
                    return;
                e.addClass(b), e[0].offsetWidth, d.addClass(g), e.addClass(g), this.$element.one(a.support.transition.end, function() {
                    e.removeClass([b, g].join(" ")).addClass("active"), d.removeClass(["active", g].join(" ")), i.sliding=!1, setTimeout(function() {
                        i.$element.trigger("slid");
                    }, 0);
                });
            } else {
                this.$element.trigger(j);
                if (j.isDefaultPrevented())
                    return;
                d.removeClass("active"), e.addClass("active"), this.sliding=!1, this.$element.trigger("slid");
            }
            return f && this.cycle(), this;
        }
    }, a.fn.carousel = function(c) {
        return this.each(function() {
            var d = a(this), e = d.data("carousel"), f = a.extend({}, a.fn.carousel.defaults, typeof c == "object" && c), g = typeof c == "string" ? c: f.slide;
            e || d.data("carousel", e = new b(this, f)), typeof c == "number" ? e.to(c) : g ? e[g]() : f.interval && e.cycle();
        });
    }, a.fn.carousel.defaults = {
        interval: 5e3,
        pause: "hover"
    }, a.fn.carousel.Constructor = b, a(document).on("click.carousel.data-api", "[data-slide]", function(b) {
        var c = a(this), d, e = a(c.attr("data-target") || (d = c.attr("href")) && d.replace(/.*(?=#[^\s]+$)/, "")), f = a.extend({}, e.data(), c.data());
        e.carousel(f), b.preventDefault();
    });
}(window.jQuery),























 function(a) {
    "use strict";
    a(function() {
        a.support.transition = function() {
            var a = function() {
                var a = document.createElement("bootstrap"),
                    b = {
                        WebkitTransition: "webkitTransitionEnd",
                        MozTransition: "transitionend",
                        OTransition: "oTransitionEnd otransitionend",
                        transition: "transitionend"
                    }, c;
                for (c in b)
                    if (a.style[c] !== undefined) return b[c];
            }();
            return a && {
                    end: a
                };
        }();
    });
}(window.jQuery), ! function(a) {
    "use strict";
    var b = '[data-dismiss="alert"]',
        c = function(c) {
            a(c).on("click", b, this.close);
        };
    c.prototype.close = function(b) {
        function c() {
            f.trigger("closed").remove();
        }
        var d = a(this),
            e = d.attr("data-target"),
            f;
        e || (e = d.attr("href"), e = e && e.replace(/.*(?=#[^\s]*$)/, "")), f = a(e), b && b.preventDefault(), f.length || (f = d.hasClass("alert") ? d : d.parent()), f.trigger(b = a.Event("close"));
        if (b.isDefaultPrevented()) return;
        f.removeClass("in"), a.support.transition && f.hasClass("fade") ? f.on(a.support.transition.end, c) : c();
    }, a.fn.alert = function(b) {
        return this.each(function() {
            var d = a(this),
                e = d.data("alert");
            e || d.data("alert", e = new c(this)), typeof b == "string" && e[b].call(d);
        });
    }, a.fn.alert.Constructor = c, a(document).on("click.alert.data-api", b, c.prototype.close);
}(window.jQuery), ! function(a) {
    "use strict";
    var b = function(b, c) {
        this.$element = a(b), this.options = a.extend({}, a.fn.button.defaults, c);
    };
    b.prototype.setState = function(a) {
        var b = "disabled",
            c = this.$element,
            d = c.data(),
            e = c.is("input") ? "val" : "html";
        a += "Text", d.resetText || c.data("resetText", c[e]()), c[e](d[a] || this.options[a]), setTimeout(function() {
            a == "loadingText" ? c.addClass(b).attr(b, b) : c.removeClass(b).removeAttr(b);
        }, 0);
    }, b.prototype.toggle = function() {
        var a = this.$element.closest('[data-toggle="buttons-radio"]');
        a && a.find(".active").removeClass("active"), this.$element.toggleClass("active");
    }, a.fn.button = function(c) {
        return this.each(function() {
            var d = a(this),
                e = d.data("button"),
                f = typeof c == "object" && c;
            e || d.data("button", e = new b(this, f)), c == "toggle" ? e.toggle() : c && e.setState(c);
        });
    }, a.fn.button.defaults = {
        loadingText: "loading..."
    }, a.fn.button.Constructor = b, a(document).on("click.button.data-api", "[data-toggle^=button]", function(b) {
        var c = a(b.target);
        c.hasClass("btn") || (c = c.closest(".btn")), c.button("toggle");
    });
}(window.jQuery), ! function(a) {
    "use strict";
    var b = function(b, c) {
        this.$element = a(b), this.options = c, this.options.slide && this.slide(this.options.slide), this.options.pause == "hover" && this.$element.on("mouseenter", a.proxy(this.pause, this)).on("mouseleave", a.proxy(this.cycle, this));
    };
    b.prototype = {
        cycle: function(b) {
            return b || (this.paused = !1), this.options.interval && !this.paused && (this.interval = setInterval(a.proxy(this.next, this), this.options.interval)), this;
        },
        to: function(b) {
            var c = this.$element.find(".item.active"),
                d = c.parent().children(),
                e = d.index(c),
                f = this;
            if (b > d.length - 1 || b < 0) return;
            return this.sliding ? this.$element.one("slid", function() {
                f.to(b);
            }) : e == b ? this.pause().cycle() : this.slide(b > e ? "next" : "prev", a(d[b]));
        },
        pause: function(b) {
            return b || (this.paused = !0), this.$element.find(".next, .prev").length && a.support.transition.end && (this.$element.trigger(a.support.transition.end), this.cycle()), clearInterval(this.interval), this.interval = null, this;
        },
        next: function() {
            if (this.sliding) return;
            return this.slide("next");
        },
        prev: function() {
            if (this.sliding) return;
            return this.slide("prev");
        },
        slide: function(b, c) {
            var d = this.$element.find(".item.active"),
                e = c || d[b](),
                f = this.interval,
                g = b == "next" ? "left" : "right",
                h = b == "next" ? "first" : "last",
                i = this,
                j;
            this.sliding = !0, f && this.pause(), e = e.length ? e : this.$element.find(".item")[h](), j = a.Event("slide", {
                relatedTarget: e[0]
            });
            if (e.hasClass("active")) return;
            if (a.support.transition && this.$element.hasClass("slide")) {
                this.$element.trigger(j);
                if (j.isDefaultPrevented()) return;
                e.addClass(b), e[0].offsetWidth, d.addClass(g), e.addClass(g), this.$element.one(a.support.transition.end, function() {
                    e.removeClass([b, g].join(" ")).addClass("active"), d.removeClass(["active", g].join(" ")), i.sliding = !1, setTimeout(function() {
                        i.$element.trigger("slid");
                    }, 0);
                });
            } else {
                this.$element.trigger(j);
                if (j.isDefaultPrevented()) return;
                d.removeClass("active"), e.addClass("active"), this.sliding = !1, this.$element.trigger("slid");
            }
            return f && this.cycle(), this;
        }
    }, a.fn.carousel = function(c) {
        return this.each(function() {
            var d = a(this),
                e = d.data("carousel"),
                f = a.extend({}, a.fn.carousel.defaults, typeof c == "object" && c),
                g = typeof c == "string" ? c : f.slide;
            e || d.data("carousel", e = new b(this, f)), typeof c == "number" ? e.to(c) : g ? e[g]() : f.interval && e.cycle();
        });
    }, a.fn.carousel.defaults = {
        interval: 5e3,
        pause: "hover"
    }, a.fn.carousel.Constructor = b, a(document).on("click.carousel.data-api", "[data-slide]", function(b) {
        var c = a(this),
            d, e = a(c.attr("data-target") || (d = c.attr("href")) && d.replace(/.*(?=#[^\s]+$)/, "")),
            f = a.extend({}, e.data(), c.data());
        e.carousel(f), b.preventDefault();
    });
}(window.jQuery), ! function(a) {
    "use strict";
    var b = function(b, c) {
        this.$element = a(b), this.options = a.extend({}, a.fn.collapse.defaults, c), this.options.parent && (this.$parent = a(this.options.parent)), this.options.toggle && this.toggle();
    };
    b.prototype = {
        constructor: b,
        dimension: function() {
            var a = this.$element.hasClass("width");
            return a ? "width" : "height";
        },
        show: function() {
            var b, c, d, e;
            if (this.transitioning) return;
            b = this.dimension(), c = a.camelCase(["scroll", b].join("-")), d = this.$parent && this.$parent.find("> .accordion-group > .in");
            if (d && d.length) {
                e = d.data("collapse");
                if (e && e.transitioning) return;
                d.collapse("hide"), e || d.data("collapse", null);
            }
            this.$element[b](0), this.transition("addClass", a.Event("show"), "shown"), a.support.transition && this.$element[b](this.$element[0][c]);
        },
        hide: function() {
            var b;
            if (this.transitioning) return;
            b = this.dimension(), this.reset(this.$element[b]()), this.transition("removeClass", a.Event("hide"), "hidden"), this.$element[b](0);
        },
        reset: function(a) {
            var b = this.dimension();
            return this.$element.removeClass("collapse")[b](a || "auto")[0].offsetWidth, this.$element[a !== null ? "addClass" : "removeClass"]("collapse"), this;
        },
        transition: function(b, c, d) {
            var e = this,
                f = function() {
                    c.type == "show" && e.reset(), e.transitioning = 0, e.$element.trigger(d);
                };
            this.$element.trigger(c);
            if (c.isDefaultPrevented()) return;
            this.transitioning = 1, this.$element[b]("in"), a.support.transition && this.$element.hasClass("collapse") ? this.$element.one(a.support.transition.end, f) : f();
        },
        toggle: function() {
            this[this.$element.hasClass("in") ? "hide" : "show"]();
        }
    }, a.fn.collapse = function(c) {
        return this.each(function() {
            var d = a(this),
                e = d.data("collapse"),
                f = typeof c == "object" && c;
            e || d.data("collapse", e = new b(this, f)), typeof c == "string" && e[c]();
        });
    }, a.fn.collapse.defaults = {
        toggle: !0
    }, a.fn.collapse.Constructor = b, a(document).on("click.collapse.data-api", "[data-toggle=collapse]", function(b) {
        var c = a(this),
            d, e = c.attr("data-target") || b.preventDefault() || (d = c.attr("href")) && d.replace(/.*(?=#[^\s]+$)/, ""),
            f = a(e).data("collapse") ? "toggle" : c.data();
        c[a(e).hasClass("in") ? "addClass" : "removeClass"]("collapsed"), a(e).collapse(f);
    });
}(window.jQuery), ! function(a) {
    "use strict";

    function b() {
        a(d).each(function() {
            c(a(this)).removeClass("open");
        });
    }

    function c(b) {
        var c = b.attr("data-target"),
            d;
        return c || (c = b.attr("href"), c = c && /#/.test(c) && c.replace(/.*(?=#[^\s]*$)/, "")), d = a(c), d.length || (d = b.parent()), d;
    }
    var d = "[data-toggle=dropdown]",
        e = function(b) {
            var c = a(b).on("click.dropdown.data-api", this.toggle);
            a("html").on("click.dropdown.data-api", function() {
                c.parent().removeClass("open");
            });
        };
    e.prototype = {
        constructor: e,
        toggle: function(d) {
            var e = a(this),
                f, g;
            if (e.is(".disabled, :disabled")) return;
            return f = c(e), g = f.hasClass("open"), b(), g || (f.toggleClass("open"), e.focus()), !1;
        },
        keydown: function(b) {
            var d, e, f, g, h, i;
            if (!/(38|40|27)/.test(b.keyCode)) return;
            d = a(this), b.preventDefault(), b.stopPropagation();
            if (d.is(".disabled, :disabled")) return;
            g = c(d), h = g.hasClass("open");
            if (!h || h && b.keyCode == 27) return d.click();
            e = a("[role=menu] li:not(.divider) a", g);
            if (!e.length) return;
            i = e.index(e.filter(":focus")), b.keyCode == 38 && i > 0 && i--, b.keyCode == 40 && i < e.length - 1 && i++, ~i || (i = 0), e.eq(i).focus();
        }
    }, a.fn.dropdown = function(b) {
        return this.each(function() {
            var c = a(this),
                d = c.data("dropdown");
            d || c.data("dropdown", d = new e(this)), typeof b == "string" && d[b].call(c);
        });
    }, a.fn.dropdown.Constructor = e, a(document).on("click.dropdown.data-api touchstart.dropdown.data-api", b).on("click.dropdown touchstart.dropdown.data-api", ".dropdown form", function(a) {
        a.stopPropagation();
    }).on("click.dropdown.data-api touchstart.dropdown.data-api", d, e.prototype.toggle).on("keydown.dropdown.data-api touchstart.dropdown.data-api", d + ", [role=menu]", e.prototype.keydown);
}(window.jQuery), ! function(a) {
    "use strict";
    var b = function(b, c) {
        this.options = c, this.$element = a(b).delegate('[data-dismiss="modal"]', "click.dismiss.modal", a.proxy(this.hide, this)), this.options.remote && this.$element.find(".modal-body").load(this.options.remote);
    };
    b.prototype = {
        constructor: b,
        toggle: function() {
            return this[this.isShown ? "hide" : "show"]();
        },
        show: function() {
            var b = this,
                c = a.Event("show");
            this.$element.trigger(c);
            if (this.isShown || c.isDefaultPrevented()) return;
            this.isShown = !0, this.escape(), this.backdrop(function() {
                var c = a.support.transition && b.$element.hasClass("fade");
                b.$element.parent().length || b.$element.appendTo(document.body), b.$element.show(), c && b.$element[0].offsetWidth, b.$element.addClass("in").attr("aria-hidden", !1), b.enforceFocus(), c ? b.$element.one(a.support.transition.end, function() {
                    b.$element.focus().trigger("shown");
                }) : b.$element.focus().trigger("shown");
            });
        },
        hide: function(b) {
            b && b.preventDefault();
            var c = this;
            b = a.Event("hide"), this.$element.trigger(b);
            if (!this.isShown || b.isDefaultPrevented()) return;
            this.isShown = !1, this.escape(), a(document).off("focusin.modal"), this.$element.removeClass("in").attr("aria-hidden", !0), a.support.transition && this.$element.hasClass("fade") ? this.hideWithTransition() : this.hideModal();
        },
        enforceFocus: function() {
            var b = this;
            a(document).on("focusin.modal", function(a) {
                b.$element[0] !== a.target && !b.$element.has(a.target).length && b.$element.focus();
            });
        },
        escape: function() {
            var a = this;
            this.isShown && this.options.keyboard ? this.$element.on("keyup.dismiss.modal", function(b) {
                b.which == 27 && a.hide();
            }) : this.isShown || this.$element.off("keyup.dismiss.modal");
        },
        hideWithTransition: function() {
            var b = this,
                c = setTimeout(function() {
                    b.$element.off(a.support.transition.end), b.hideModal();
                }, 500);
            this.$element.one(a.support.transition.end, function() {
                clearTimeout(c), b.hideModal();
            });
        },
        hideModal: function(a) {
            this.$element.hide().trigger("hidden"), this.backdrop();
        },
        removeBackdrop: function() {
            this.$backdrop.remove(), this.$backdrop = null;
        },
        backdrop: function(b) {
            var c = this,
                d = this.$element.hasClass("fade") ? "fade" : "";
            if (this.isShown && this.options.backdrop) {
                var e = a.support.transition && d;
                this.$backdrop = a('<div class="modal-backdrop ' + d + '" />').appendTo(document.body), this.$backdrop.click(this.options.backdrop == "static" ? a.proxy(this.$element[0].focus, this.$element[0]) : a.proxy(this.hide, this)), e && this.$backdrop[0].offsetWidth, this.$backdrop.addClass("in"), e ? this.$backdrop.one(a.support.transition.end, b) : b();
            } else !this.isShown && this.$backdrop ? (this.$backdrop.removeClass("in"), a.support.transition && this.$element.hasClass("fade") ? this.$backdrop.one(a.support.transition.end, a.proxy(this.removeBackdrop, this)) : this.removeBackdrop()) : b && b();
        }
    }, a.fn.modal = function(c) {
        return this.each(function() {
            var d = a(this),
                e = d.data("modal"),
                f = a.extend({}, a.fn.modal.defaults, d.data(), typeof c == "object" && c);
            e || d.data("modal", e = new b(this, f)), typeof c == "string" ? e[c]() : f.show && e.show();
        });
    }, a.fn.modal.defaults = {
        backdrop: !0,
        keyboard: !0,
        show: !0
    }, a.fn.modal.Constructor = b, a(document).on("click.modal.data-api", '[data-toggle="modal"]', function(b) {
        var c = a(this),
            d = c.attr("href"),
            e = a(c.attr("data-target") || d && d.replace(/.*(?=#[^\s]+$)/, "")),
            f = e.data("modal") ? "toggle" : a.extend({
                remote: !/#/.test(d) && d
            }, e.data(), c.data());
        b.preventDefault(), e.modal(f).one("hide", function() {
            c.focus();
        });
    });
}(window.jQuery), ! function(a) {
    "use strict";
    var b = function(a, b) {
        this.init("tooltip", a, b);
    };
    b.prototype = {
        constructor: b,
        init: function(b, c, d) {
            var e, f;
            this.type = b, this.$element = a(c), this.options = this.getOptions(d), this.enabled = !0, this.options.trigger == "click" ? this.$element.on("click." + this.type, this.options.selector, a.proxy(this.toggle, this)) : this.options.trigger != "manual" && (e = this.options.trigger == "hover" ? "mouseenter" : "focus", f = this.options.trigger == "hover" ? "mouseleave" : "blur", this.$element.on(e + "." + this.type, this.options.selector, a.proxy(this.enter, this)), this.$element.on(f + "." + this.type, this.options.selector, a.proxy(this.leave, this))), this.options.selector ? this._options = a.extend({}, this.options, {
                trigger: "manual",
                selector: ""
            }) : this.fixTitle();
        },
        getOptions: function(b) {
            return b = a.extend({}, a.fn[this.type].defaults, b, this.$element.data()), b.delay && typeof b.delay == "number" && (b.delay = {
                show: b.delay,
                hide: b.delay
            }), b;
        },
        enter: function(b) {
            var c = a(b.currentTarget)[this.type](this._options).data(this.type);
            if (!c.options.delay || !c.options.delay.show) return c.show();
            clearTimeout(this.timeout), c.hoverState = "in", this.timeout = setTimeout(function() {
                c.hoverState == "in" && c.show();
            }, c.options.delay.show);
        },
        leave: function(b) {
            var c = a(b.currentTarget)[this.type](this._options).data(this.type);
            this.timeout && clearTimeout(this.timeout);
            if (!c.options.delay || !c.options.delay.hide) return c.hide();
            c.hoverState = "out", this.timeout = setTimeout(function() {
                c.hoverState == "out" && c.hide();
            }, c.options.delay.hide);
        },
        show: function() {
            var a, b, c, d, e, f, g;
            if (this.hasContent() && this.enabled) {
                a = this.tip(), this.setContent(), this.options.animation && a.addClass("fade"), f = typeof this.options.placement == "function" ? this.options.placement.call(this, a[0], this.$element[0]) : this.options.placement, b = /in/.test(f), a.detach().css({
                    top: 0,
                    left: 0,
                    display: "block"
                }).insertAfter(this.$element), c = this.getPosition(b), d = a[0].offsetWidth, e = a[0].offsetHeight;
                switch (b ? f.split(" ")[1] : f) {
                    case "bottom":
                        g = {
                            top: c.top + c.height,
                            left: c.left + c.width / 2 - d / 2
                        };
                        break;
                    case "top":
                        g = {
                            top: c.top - e,
                            left: c.left + c.width / 2 - d / 2
                        };
                        break;
                    case "left":
                        g = {
                            top: c.top + c.height / 2 - e / 2,
                            left: c.left - d
                        };
                        break;
                    case "right":
                        g = {
                            top: c.top + c.height / 2 - e / 2,
                            left: c.left + c.width
                        };
                }
                a.offset(g).addClass(f).addClass("in");
            }
        },
        setContent: function() {
            var a = this.tip(),
                b = this.getTitle();
            a.find(".tooltip-inner")[this.options.html ? "html" : "text"](b), a.removeClass("fade in top bottom left right");
        },
        hide: function() {
            function b() {
                var b = setTimeout(function() {
                    d.off(a.support.transition.end).detach();
                }, 500);
                d.one(a.support.transition.end, function() {
                    clearTimeout(b), d.detach();
                });
            }
            var c = this,
                d = this.tip();
            return d.removeClass("in"), a.support.transition && this.$tip.hasClass("fade") ? b() : d.detach(), this;
        },
        fixTitle: function() {
            var a = this.$element;
            (a.attr("title") || typeof a.attr("data-original-title") != "string") && a.attr("data-original-title", a.attr("title") || "").removeAttr("title");
        },
        hasContent: function() {
            return this.getTitle();
        },
        getPosition: function(b) {
            return a.extend({}, b ? {
                top: 0,
                left: 0
            } : this.$element.offset(), {
                width: this.$element[0].offsetWidth,
                height: this.$element[0].offsetHeight
            });
        },
        getTitle: function() {
            var a, b = this.$element,
                c = this.options;
            return a = b.attr("data-original-title") || (typeof c.title == "function" ? c.title.call(b[0]) : c.title), a;
        },
        tip: function() {
            return this.$tip = this.$tip || a(this.options.template);
        },
        validate: function() {
            this.$element[0].parentNode || (this.hide(), this.$element = null, this.options = null);
        },
        enable: function() {
            this.enabled = !0;
        },
        disable: function() {
            this.enabled = !1;
        },
        toggleEnabled: function() {
            this.enabled = !this.enabled;
        },
        toggle: function(b) {
            var c = a(b.currentTarget)[this.type](this._options).data(this.type);
            c[c.tip().hasClass("in") ? "hide" : "show"]();
        },
        destroy: function() {
            this.hide().$element.off("." + this.type).removeData(this.type);
        }
    }, a.fn.tooltip = function(c) {
        return this.each(function() {
            var d = a(this),
                e = d.data("tooltip"),
                f = typeof c == "object" && c;
            e || d.data("tooltip", e = new b(this, f)), typeof c == "string" && e[c]();
        });
    }, a.fn.tooltip.Constructor = b, a.fn.tooltip.defaults = {
        animation: !0,
        placement: "top",
        selector: !1,
        template: '<div class="tooltip"><div class="tooltip-arrow"></div><div class="tooltip-inner"></div></div>',
        trigger: "hover",
        title: "",
        delay: 0,
        html: !1
    };
}(window.jQuery), ! function(a) {
    "use strict";
    var b = function(a, b) {
        this.init("popover", a, b);
    };
    b.prototype = a.extend({}, a.fn.tooltip.Constructor.prototype, {
        constructor: b,
        setContent: function() {
            var a = this.tip(),
                b = this.getTitle(),
                c = this.getContent();
            a.find(".popover-title")[this.options.html ? "html" : "text"](b), a.find(".popover-content > *")[this.options.html ? "html" : "text"](c), a.removeClass("fade top bottom left right in");
        },
        hasContent: function() {
            return this.getTitle() || this.getContent();
        },
        getContent: function() {
            var a, b = this.$element,
                c = this.options;
            return a = b.attr("data-content") || (typeof c.content == "function" ? c.content.call(b[0]) : c.content), a;
        },
        tip: function() {
            return this.$tip || (this.$tip = a(this.options.template)), this.$tip;
        },
        destroy: function() {
            this.hide().$element.off("." + this.type).removeData(this.type);
        }
    }), a.fn.popover = function(c) {
        return this.each(function() {
            var d = a(this),
                e = d.data("popover"),
                f = typeof c == "object" && c;
            e || d.data("popover", e = new b(this, f)), typeof c == "string" && e[c]();
        });
    }, a.fn.popover.Constructor = b, a.fn.popover.defaults = a.extend({}, a.fn.tooltip.defaults, {
        placement: "right",
        trigger: "click",
        content: "",
        template: '<div class="popover"><div class="arrow"></div><div class="popover-inner"><h3 class="popover-title"></h3><div class="popover-content"><p></p></div></div></div>'
    });
}(window.jQuery), ! function(a) {
    "use strict";

    function b(b, c) {
        var d = a.proxy(this.process, this),
            e = a(b).is("body") ? a(window) : a(b),
            f;
        this.options = a.extend({}, a.fn.scrollspy.defaults, c), this.$scrollElement = e.on("scroll.scroll-spy.data-api", d), this.selector = (this.options.target || (f = a(b).attr("href")) && f.replace(/.*(?=#[^\s]+$)/, "") || "") + " .nav li > a", this.$body = a("body"), this.refresh(), this.process();
    }
    b.prototype = {
        constructor: b,
        refresh: function() {
            var b = this,
                c;
            this.offsets = a([]), this.targets = a([]), c = this.$body.find(this.selector).map(function() {
                var b = a(this),
                    c = b.data("target") || b.attr("href"),
                    d = /^#\w/.test(c) && a(c);
                return d && d.length && [
                        [d.position().top, c]
                    ] || null;
            }).sort(function(a, b) {
                return a[0] - b[0];
            }).each(function() {
                b.offsets.push(this[0]), b.targets.push(this[1]);
            });
        },
        process: function() {
            var a = this.$scrollElement.scrollTop() + this.options.offset,
                b = this.$scrollElement[0].scrollHeight || this.$body[0].scrollHeight,
                c = b - this.$scrollElement.height(),
                d = this.offsets,
                e = this.targets,
                f = this.activeTarget,
                g;
            if (a >= c) return f != (g = e.last()[0]) && this.activate(g);
            for (g = d.length; g--;) f != e[g] && a >= d[g] && (!d[g + 1] || a <= d[g + 1]) && this.activate(e[g]);
        },
        activate: function(b) {
            var c, d;
            this.activeTarget = b, a(this.selector).parent(".active").removeClass("active"), d = this.selector + '[data-target="' + b + '"],' + this.selector + '[href="' + b + '"]', c = a(d).parent("li").addClass("active"), c.parent(".dropdown-menu").length && (c = c.closest("li.dropdown").addClass("active")), c.trigger("activate");
        }
    }, a.fn.scrollspy = function(c) {
        return this.each(function() {
            var d = a(this),
                e = d.data("scrollspy"),
                f = typeof c == "object" && c;
            e || d.data("scrollspy", e = new b(this, f)), typeof c == "string" && e[c]();
        });
    }, a.fn.scrollspy.Constructor = b, a.fn.scrollspy.defaults = {
        offset: 10
    }, a(window).on("load", function() {
        a('[data-spy="scroll"]').each(function() {
            var b = a(this);
            b.scrollspy(b.data());
        });
    });
}(window.jQuery), ! function(a) {
    "use strict";
    var b = function(b) {
        this.element = a(b);
    };
    b.prototype = {
        constructor: b,
        show: function() {
            var b = this.element,
                c = b.closest("ul:not(.dropdown-menu)"),
                d = b.attr("data-target"),
                e, f, g;
            d || (d = b.attr("href"), d = d && d.replace(/.*(?=#[^\s]*$)/, ""));
            if (b.parent("li").hasClass("active")) return;
            e = c.find(".active:last a")[0], g = a.Event("show", {
                relatedTarget: e
            }), b.trigger(g);
            if (g.isDefaultPrevented()) return;
            f = a(d), this.activate(b.parent("li"), c), this.activate(f, f.parent(), function() {
                b.trigger({
                    type: "shown",
                    relatedTarget: e
                });
            });
        },
        activate: function(b, c, d) {
            function e() {
                f.removeClass("active").find("> .dropdown-menu > .active").removeClass("active"), b.addClass("active"), g ? (b[0].offsetWidth, b.addClass("in")) : b.removeClass("fade"), b.parent(".dropdown-menu") && b.closest("li.dropdown").addClass("active"), d && d();
            }
            var f = c.find("> .active"),
                g = d && a.support.transition && f.hasClass("fade");
            g ? f.one(a.support.transition.end, e) : e(), f.removeClass("in");
        }
    }, a.fn.tab = function(c) {
        return this.each(function() {
            var d = a(this),
                e = d.data("tab");
            e || d.data("tab", e = new b(this)), typeof c == "string" && e[c]();
        });
    }, a.fn.tab.Constructor = b, a(document).on("click.tab.data-api", '[data-toggle="tab"], [data-toggle="pill"]', function(b) {
        b.preventDefault(), a(this).tab("show");
    });
}(window.jQuery), ! function(a) {
    "use strict";
    var b = function(b, c) {
        this.$element = a(b), this.options = a.extend({}, a.fn.typeahead.defaults, c), this.matcher = this.options.matcher || this.matcher, this.sorter = this.options.sorter || this.sorter, this.highlighter = this.options.highlighter || this.highlighter, this.updater = this.options.updater || this.updater, this.$menu = a(this.options.menu).appendTo("body"), this.source = this.options.source, this.shown = !1, this.listen();
    };
    b.prototype = {
        constructor: b,
        select: function() {
            var a = this.$menu.find(".active").attr("data-value");
            return this.$element.val(this.updater(a)).change(), this.hide();
        },
        updater: function(a) {
            return a;
        },
        show: function() {
            var b = a.extend({}, this.$element.offset(), {
                height: this.$element[0].offsetHeight
            });
            return this.$menu.css({
                top: b.top + b.height,
                left: b.left
            }), this.$menu.show(), this.shown = !0, this;
        },
        hide: function() {
            return this.$menu.hide(), this.shown = !1, this;
        },
        lookup: function(b) {
            var c;
            return this.query = this.$element.val(), !this.query || this.query.length < this.options.minLength ? this.shown ? this.hide() : this : (c = a.isFunction(this.source) ? this.source(this.query, a.proxy(this.process, this)) : this.source, c ? this.process(c) : this);
        },
        process: function(b) {
            var c = this;
            return b = a.grep(b, function(a) {
                return c.matcher(a);
            }), b = this.sorter(b), b.length ? this.render(b.slice(0, this.options.items)).show() : this.shown ? this.hide() : this;
        },
        matcher: function(a) {
            return~ a.toLowerCase().indexOf(this.query.toLowerCase());
        },
        sorter: function(a) {
            var b = [],
                c = [],
                d = [],
                e;
            while (e = a.shift()) e.toLowerCase().indexOf(this.query.toLowerCase()) ? ~e.indexOf(this.query) ? c.push(e) : d.push(e) : b.push(e);
            return b.concat(c, d);
        },
        highlighter: function(a) {
            var b = this.query.replace(/[\-\[\]{}()*+?.,\\\^$|#\s]/g, "\\$&");
            return a.replace(new RegExp("(" + b + ")", "ig"), function(a, b) {
                return "<strong>" + b + "</strong>";
            });
        },
        render: function(b) {
            var c = this;
            return b = a(b).map(function(b, d) {
                return b = a(c.options.item).attr("data-value", d), b.find("a").html(c.highlighter(d)), b[0];
            }), b.first().addClass("active"), this.$menu.html(b), this;
        },
        next: function(b) {
            var c = this.$menu.find(".active").removeClass("active"),
                d = c.next();
            d.length || (d = a(this.$menu.find("li")[0])), d.addClass("active");
        },
        prev: function(a) {
            var b = this.$menu.find(".active").removeClass("active"),
                c = b.prev();
            c.length || (c = this.$menu.find("li").last()), c.addClass("active");
        },
        listen: function() {
            this.$element.on("blur", a.proxy(this.blur, this)).on("keypress", a.proxy(this.keypress, this)).on("keyup", a.proxy(this.keyup, this)), this.eventSupported("keydown") && this.$element.on("keydown", a.proxy(this.keydown, this)), this.$menu.on("click", a.proxy(this.click, this)).on("mouseenter", "li", a.proxy(this.mouseenter, this));
        },
        eventSupported: function(a) {
            var b = a in this.$element;
            return b || (this.$element.setAttribute(a, "return;"), b = typeof this.$element[a] == "function"), b;
        },
        move: function(a) {
            if (!this.shown) return;
            switch (a.keyCode) {
                case 9:
                case 13:
                case 27:
                    a.preventDefault();
                    break;
                case 38:
                    a.preventDefault(), this.prev();
                    break;
                case 40:
                    a.preventDefault(), this.next();
            }
            a.stopPropagation();
        },
        keydown: function(b) {
            this.suppressKeyPressRepeat = !~a.inArray(b.keyCode, [40, 38, 9, 13, 27]), this.move(b);
        },
        keypress: function(a) {
            if (this.suppressKeyPressRepeat) return;
            this.move(a);
        },
        keyup: function(a) {
            switch (a.keyCode) {
                case 40:
                case 38:
                case 16:
                case 17:
                case 18:
                    break;
                case 9:
                case 13:
                    if (!this.shown) return;
                    this.select();
                    break;
                case 27:
                    if (!this.shown) return;
                    this.hide();
                    break;
                default:
                    this.lookup();
            }
            a.stopPropagation(), a.preventDefault();
        },
        blur: function(a) {
            var b = this;
            setTimeout(function() {
                b.hide();
            }, 150);
        },
        click: function(a) {
            a.stopPropagation(), a.preventDefault(), this.select();
        },
        mouseenter: function(b) {
            this.$menu.find(".active").removeClass("active"), a(b.currentTarget).addClass("active");
        }
    }, a.fn.typeahead = function(c) {
        return this.each(function() {
            var d = a(this),
                e = d.data("typeahead"),
                f = typeof c == "object" && c;
            e || d.data("typeahead", e = new b(this, f)), typeof c == "string" && e[c]();
        });
    }, a.fn.typeahead.defaults = {
        source: [],
        items: 8,
        menu: '<ul class="typeahead dropdown-menu"></ul>',
        item: '<li><a href="#"></a></li>',
        minLength: 1
    }, a.fn.typeahead.Constructor = b, a(document).on("focus.typeahead.data-api", '[data-provide="typeahead"]', function(b) {
        var c = a(this);
        if (c.data("typeahead")) return;
        b.preventDefault(), c.typeahead(c.data());
    });
}(window.jQuery), ! function(a) {
    "use strict";
    var b = function(b, c) {
        this.options = a.extend({}, a.fn.affix.defaults, c), this.$window = a(window).on("scroll.affix.data-api", a.proxy(this.checkPosition, this)).on("click.affix.data-api", a.proxy(function() {
            setTimeout(a.proxy(this.checkPosition, this), 1);
        }, this)), this.$element = a(b), this.checkPosition();
    };
    b.prototype.checkPosition = function() {
        if (!this.$element.is(":visible")) return;
        var b = a(document).height(),
            c = this.$window.scrollTop(),
            d = this.$element.offset(),
            e = this.options.offset,
            f = e.bottom,
            g = e.top,
            h = "affix affix-top affix-bottom",
            i;
        typeof e != "object" && (f = g = e), typeof g == "function" && (g = e.top()), typeof f == "function" && (f = e.bottom()), i = this.unpin != null && c + this.unpin <= d.top ? !1 : f != null && d.top + this.$element.height() >= b - f ? "bottom" : g != null && c <= g ? "top" : !1;
        if (this.affixed === i) return;
        this.affixed = i, this.unpin = i == "bottom" ? d.top - c : null, this.$element.removeClass(h).addClass("affix" + (i ? "-" + i : ""));
    }, a.fn.affix = function(c) {
        return this.each(function() {
            var d = a(this),
                e = d.data("affix"),
                f = typeof c == "object" && c;
            e || d.data("affix", e = new b(this, f)), typeof c == "string" && e[c]();
        });
    }, a.fn.affix.Constructor = b, a.fn.affix.defaults = {
        offset: 0
    }, a(window).on("load", function() {
        a('[data-spy="affix"]').each(function() {
            var b = a(this),
                c = b.data();
            c.offset = c.offset || {}, c.offsetBottom && (c.offset.bottom = c.offsetBottom), c.offsetTop && (c.offset.top = c.offsetTop), b.affix(c);
        });
    });
}(window.jQuery);
jQuery.easing.jswing = jQuery.easing.swing;
jQuery.extend(jQuery.easing, {
    def: 'easeOutQuad',
    swing: function(a, b, c, d, e) {
        return jQuery.easing[jQuery.easing.def](a, b, c, d, e);
    },
    easeInQuad: function(a, b, c, d, e) {
        return d * (b /= e) * b + c;
    },
    easeOutQuad: function(a, b, c, d, e) {
        return -d * (b /= e) * (b - 2) + c;
    },
    easeInOutQuad: function(a, b, c, d, e) {
        if ((b /= e / 2) < 1) return d / 2 * b * b + c;
        return -d / 2 * ((--b) * (b - 2) - 1) + c;
    },
    easeInCubic: function(a, b, c, d, e) {
        return d * (b /= e) * b * b + c;
    },
    easeOutCubic: function(a, b, c, d, e) {
        return d * ((b = b / e - 1) * b * b + 1) + c;
    },
    easeInOutCubic: function(a, b, c, d, e) {
        if ((b /= e / 2) < 1) return d / 2 * b * b * b + c;
        return d / 2 * ((b -= 2) * b * b + 2) + c;
    },
    easeInQuart: function(a, b, c, d, e) {
        return d * (b /= e) * b * b * b + c;
    },
    easeOutQuart: function(a, b, c, d, e) {
        return -d * ((b = b / e - 1) * b * b * b - 1) + c;
    },
    easeInOutQuart: function(a, b, c, d, e) {
        if ((b /= e / 2) < 1) return d / 2 * b * b * b * b + c;
        return -d / 2 * ((b -= 2) * b * b * b - 2) + c;
    },
    easeInQuint: function(a, b, c, d, e) {
        return d * (b /= e) * b * b * b * b + c;
    },
    easeOutQuint: function(a, b, c, d, e) {
        return d * ((b = b / e - 1) * b * b * b * b + 1) + c;
    },
    easeInOutQuint: function(a, b, c, d, e) {
        if ((b /= e / 2) < 1) return d / 2 * b * b * b * b * b + c;
        return d / 2 * ((b -= 2) * b * b * b * b + 2) + c;
    },
    easeInSine: function(a, b, c, d, e) {
        return -d * Math.cos(b / e * (Math.PI / 2)) + d + c;
    },
    easeOutSine: function(a, b, c, d, e) {
        return d * Math.sin(b / e * (Math.PI / 2)) + c;
    },
    easeInOutSine: function(a, b, c, d, e) {
        return -d / 2 * (Math.cos(Math.PI * b / e) - 1) + c;
    },
    easeInExpo: function(a, b, c, d, e) {
        return (b == 0) ? c : d * Math.pow(2, 10 * (b / e - 1)) + c;
    },
    easeOutExpo: function(a, b, c, d, e) {
        return (b == e) ? c + d : d * (-Math.pow(2, -10 * b / e) + 1) + c;
    },
    easeInOutExpo: function(a, b, c, d, e) {
        if (b == 0) return c;
        if (b == e) return c + d;
        if ((b /= e / 2) < 1) return d / 2 * Math.pow(2, 10 * (b - 1)) + c;
        return d / 2 * (-Math.pow(2, -10 * --b) + 2) + c;
    },
    easeInCirc: function(a, b, c, d, e) {
        return -d * (Math.sqrt(1 - (b /= e) * b) - 1) + c;
    },
    easeOutCirc: function(a, b, c, d, e) {
        return d * Math.sqrt(1 - (b = b / e - 1) * b) + c;
    },
    easeInOutCirc: function(a, b, c, d, e) {
        if ((b /= e / 2) < 1) return -d / 2 * (Math.sqrt(1 - b * b) - 1) + c;
        return d / 2 * (Math.sqrt(1 - (b -= 2) * b) + 1) + c;
    },
    easeInElastic: function(a, b, c, d, e) {
        var f = 1.70158;
        var g = 0;
        var h = d;
        if (b == 0) return c;
        if ((b /= e) == 1) return c + d;
        if (!g) g = e * .3;
        if (h < Math.abs(d)) {
            h = d;
            var f = g / 4;
        } else var f = g / (2 * Math.PI) * Math.asin(d / h);
        return -(h * Math.pow(2, 10 * (b -= 1)) * Math.sin((b * e - f) * (2 * Math.PI) / g)) + c;
    },
    easeOutElastic: function(a, b, c, d, e) {
        var f = 1.70158;
        var g = 0;
        var h = d;
        if (b == 0) return c;
        if ((b /= e) == 1) return c + d;
        if (!g) g = e * .3;
        if (h < Math.abs(d)) {
            h = d;
            var f = g / 4;
        } else var f = g / (2 * Math.PI) * Math.asin(d / h);
        return h * Math.pow(2, -10 * b) * Math.sin((b * e - f) * (2 * Math.PI) / g) + d + c;
    },
    easeInOutElastic: function(a, b, c, d, e) {
        var f = 1.70158;
        var g = 0;
        var h = d;
        if (b == 0) return c;
        if ((b /= e / 2) == 2) return c + d;
        if (!g) g = e * (.3 * 1.5);
        if (h < Math.abs(d)) {
            h = d;
            var f = g / 4;
        } else var f = g / (2 * Math.PI) * Math.asin(d / h); if (b < 1) return -.5 * (h * Math.pow(2, 10 * (b -= 1)) * Math.sin((b * e - f) * (2 * Math.PI) / g)) + c;
        return h * Math.pow(2, -10 * (b -= 1)) * Math.sin((b * e - f) * (2 * Math.PI) / g) * .5 + d + c;
    },
    easeInBack: function(a, b, c, d, e, f) {
        if (f == undefined) f = 1.70158;
        return d * (b /= e) * b * ((f + 1) * b - f) + c;
    },
    easeOutBack: function(a, b, c, d, e, f) {
        if (f == undefined) f = 1.70158;
        return d * ((b = b / e - 1) * b * ((f + 1) * b + f) + 1) + c;
    },
    easeInOutBack: function(a, b, c, d, e, f) {
        if (f == undefined) f = 1.70158;
        if ((b /= e / 2) < 1) return d / 2 * (b * b * (((f *= 1.525) + 1) * b - f)) + c;
        return d / 2 * ((b -= 2) * b * (((f *= 1.525) + 1) * b + f) + 2) + c;
    },
    easeInBounce: function(a, b, c, d, e) {
        return d - jQuery.easing.easeOutBounce(a, e - b, 0, d, e) + c;
    },
    easeOutBounce: function(a, b, c, d, e) {
        if ((b /= e) < (1 / 2.75)) return d * (7.5625 * b * b) + c;
        else if (b < (2 / 2.75)) return d * (7.5625 * (b -= (1.5 / 2.75)) * b + .75) + c;
        else if (b < (2.5 / 2.75)) return d * (7.5625 * (b -= (2.25 / 2.75)) * b + .9375) + c;
        else return d * (7.5625 * (b -= (2.625 / 2.75)) * b + .984375) + c;
    },
    easeInOutBounce: function(a, b, c, d, e) {
        if (b < e / 2) return jQuery.easing.easeInBounce(a, b * 2, 0, d, e) * .5 + c;
        return jQuery.easing.easeOutBounce(a, b * 2 - e, 0, d, e) * .5 + d * .5 + c;
    }
});
$(document).ready(function() {
    $(".three-columns-container .title.mobile").on("click", function(a) {
        $(this).next().stop().slideToggle();
        $(this).next().toggleClass("active");
        if ($(this).next().hasClass("active")) {
            $(this).find(".toggle-open").hide();
            $(this).find(".toggle-close").show();
        } else {
            $(this).find(".toggle-open").show();
            $(this).find(".toggle-close").hide();
        }
    });
    $("#navbar .menu-toggle a").on("click", function(a) {
        $(".mobile-menu-container").slideToggle();
        $(".mobile-menu-container").toggleClass("active");
        $(this).toggleClass("active");
    });
    //try {
    //    var a = window.location.hash;
    //    if (a) $(a).click();
    //    $('a[href*="#"]').click(function(a) {
    //        a.preventDefault();
    //        var b = $(this).attr('href').replace('/', '');
    //        $(b).click();
    //    });
    //} catch (b) {}
});



var IS_MOBILE = $(".mobile-check").is(":visible");
$(window).load(function() {
    $(".diagram-container").each(function() {
        $this = $(this);
        if ($this.hasClass("diagram-vertical")) findSize('vertical', $this);;
        if ($this.hasClass("diagram-horizontal"))
            if (IS_MOBILE) findSize('vertical', $this);
            else findSize('horizontal', $this);;
    });
});
$(window).resize(function() {
    IS_MOBILE = $(".mobile-check").is(":visible");
    $(".diagram-container").each(function() {
        $this = $(this);
        if ($this.hasClass("diagram-vertical")) findSize('vertical', $this);;
        if ($this.hasClass("diagram-horizontal"))
            if (IS_MOBILE) findSize('vertical', $this);
            else findSize('horizontal', $this);;
    });
});

function findSize(a, b) {
    if (a == 'vertical') {
        $(".horizontal-border").css("visibility", "hidden");
        $firstLine = b.find(".first .vertical-border").first();
        $lastLine = b.find(".last .vertical-border").first();
        var c = $lastLine.parents(".diagram-item").position().top - $firstLine.parents(".diagram-item").position().top;
        $firstLine.css({
            top: $firstLine.parent().outerHeight() / 2,
            left: $firstLine.parent().outerWidth() / 2,
            height: c,
            visibility: "visible"
        });
    } else if (a == 'horizontal') {
        $(".vertical-border").css("visibility", "hidden");
        $firstLine = b.find(".first .horizontal-border").first();
        $lastLine = b.find(".last .horizontal-border").first();
        var d = $lastLine.parent().offset().left - $firstLine.parent().offset().left;
        $firstLine.css({
            top: $firstLine.parent().outerHeight() / 2,
            left: $firstLine.parent().outerWidth() / 2,
            width: d,
            visibility: "visible"
        });
    } else console.log("No axis determined");;
}! function(a) {
    "use strict";
    a(function() {
        a.support.transition = function() {
            var a = function() {
                var a = document.createElement("bootstrap"),
                    b = {
                        WebkitTransition: "webkitTransitionEnd",
                        MozTransition: "transitionend",
                        OTransition: "oTransitionEnd otransitionend",
                        transition: "transitionend"
                    },
                    c;
                for (c in b)
                    if (a.style[c] !== undefined) return b[c];
            }();
            return a && {
                    end: a
                };
        }();
    });
}(window.jQuery), ! function(a) {
    "use strict";
    var b = '[data-dismiss="alert"]',
        c = function(c) {
            a(c).on("click", b, this.close);
        };
    c.prototype.close = function(b) {
        function c() {
            f.trigger("closed").remove();
        }
        var d = a(this),
            e = d.attr("data-target"),
            f;
        e || (e = d.attr("href"), e = e && e.replace(/.*(?=#[^\s]*$)/, "")), f = a(e), b && b.preventDefault(), f.length || (f = d.hasClass("alert") ? d : d.parent()), f.trigger(b = a.Event("close"));
        if (b.isDefaultPrevented()) return;
        f.removeClass("in"), a.support.transition && f.hasClass("fade") ? f.on(a.support.transition.end, c) : c();
    }, a.fn.alert = function(b) {
        return this.each(function() {
            var d = a(this),
                e = d.data("alert");
            e || d.data("alert", e = new c(this)), typeof b == "string" && e[b].call(d);
        });
    }, a.fn.alert.Constructor = c, a(document).on("click.alert.data-api", b, c.prototype.close);
}(window.jQuery), ! function(a) {
    "use strict";
    var b = function(b, c) {
        this.$element = a(b), this.options = a.extend({}, a.fn.button.defaults, c);
    };
    b.prototype.setState = function(a) {
        var b = "disabled",
            c = this.$element,
            d = c.data(),
            e = c.is("input") ? "val" : "html";
        a += "Text", d.resetText || c.data("resetText", c[e]()), c[e](d[a] || this.options[a]), setTimeout(function() {
            a == "loadingText" ? c.addClass(b).attr(b, b) : c.removeClass(b).removeAttr(b);
        }, 0);
    }, b.prototype.toggle = function() {
        var a = this.$element.closest('[data-toggle="buttons-radio"]');
        a && a.find(".active").removeClass("active"), this.$element.toggleClass("active");
    }, a.fn.button = function(c) {
        return this.each(function() {
            var d = a(this),
                e = d.data("button"),
                f = typeof c == "object" && c;
            e || d.data("button", e = new b(this, f)), c == "toggle" ? e.toggle() : c && e.setState(c);
        });
    }, a.fn.button.defaults = {
        loadingText: "loading..."
    }, a.fn.button.Constructor = b, a(document).on("click.button.data-api", "[data-toggle^=button]", function(b) {
        var c = a(b.target);
        c.hasClass("btn") || (c = c.closest(".btn")), c.button("toggle");
    });
}(window.jQuery), ! function(a) {
    "use strict";
    var b = function (b, c) {
        this.$element = a(b), this.options = c, this.options.slide && this.slide(this.options.slide), this.options.pause == "hover" && this.$element.on("mouseenter", a.proxy(this.pause, this)).on("mouseleave", a.proxy(this.cycle, this));
    };
    b.prototype = {
        cycle: function (b) {
            return b || (this.paused = !1), this.options.interval && !this.paused && (this.interval = setInterval(a.proxy(this.next, this), this.options.interval)), this;
        },
        to: function (b) {
            var c = this.$element.find(".item.active"),
                d = c.parent().children(),
                e = d.index(c),
                f = this;
            if (b > d.length - 1 || b < 0) return;
            return this.sliding ? this.$element.one("slid", function () {
                f.to(b);
            }) : e == b ? this.pause().cycle() : this.slide(b > e ? "next" : "prev", a(d[b]));
        },
        pause: function (b) {
            return b || (this.paused = !0), this.$element.find(".next, .prev").length && a.support.transition.end && (this.$element.trigger(a.support.transition.end), this.cycle()), clearInterval(this.interval), this.interval = null, this;
        },
        next: function () {
            if (this.sliding) return;
            return this.slide("next");
        },
        prev: function () {
            if (this.sliding) return;
            return this.slide("prev");
        },
        slide: function (b, c) {
            var d = this.$element.find(".item.active"),
                e = c || d[b](),
                f = this.interval,
                g = b == "next" ? "left" : "right",
                h = b == "next" ? "first" : "last",
                i = this,
                j;
            this.sliding = !0, f && this.pause(), e = e.length ? e : this.$element.find(".item")[h](), j = a.Event("slide", {
                relatedTarget: e[0]
            });
            if (e.hasClass("active")) return;
            if (a.support.transition && this.$element.hasClass("slide")) {
                this.$element.trigger(j);
                if (j.isDefaultPrevented()) return;
                e.addClass(b), e[0].offsetWidth, d.addClass(g), e.addClass(g), this.$element.one(a.support.transition.end, function () {
                    e.removeClass([b, g].join(" ")).addClass("active"), d.removeClass(["active", g].join(" ")), i.sliding = !1, setTimeout(function () {
                        i.$element.trigger("slid");
                    }, 0);
                });
            } else {
                this.$element.trigger(j);
                if (j.isDefaultPrevented()) return;
                d.removeClass("active"), e.addClass("active"), this.sliding = !1, this.$element.trigger("slid");
            }
            return f && this.cycle(), this;
        }
    }, a.fn.carousel = function (c) {
        return this.each(function () {
            var d = a(this),
                e = d.data("carousel"),
                f = a.extend({}, a.fn.carousel.defaults, typeof c == "object" && c),
                g = typeof c == "string" ? c : f.slide;
            e || d.data("carousel", e = new b(this, f)), typeof c == "number" ? e.to(c) : g ? e[g]() : f.interval && e.cycle();
        });
    }, a.fn.carousel.defaults = {
        interval: 5e3,
        pause: "hover"
    }, a.fn.carousel.Constructor = b, a(document).on("click.carousel.data-api", "[data-slide]", function (b) {
        var c = a(this),
            d, e = a(c.attr("data-target") || (d = c.attr("href")) && d.replace(/.*(?=#[^\s]+$)/, "")),
            f = a.extend({}, e.data(), c.data());
        e.carousel(f), b.preventDefault();
    });
}(window.jQuery), ! function(a) {
    "use strict";

    function b() {
        a(d).each(function() {
            c(a(this)).removeClass("open");
        });
    }

    function c(b) {
        var c = b.attr("data-target"),
            d;
        return c || (c = b.attr("href"), c = c && /#/.test(c) && c.replace(/.*(?=#[^\s]*$)/, "")), d = a(c), d.length || (d = b.parent()), d;
    }
    var d = "[data-toggle=dropdown]",
        e = function(b) {
            var c = a(b).on("click.dropdown.data-api", this.toggle);
            a("html").on("click.dropdown.data-api", function() {
                c.parent().removeClass("open");
            });
        };
    e.prototype = {
        constructor: e,
        toggle: function(d) {
            var e = a(this),
                f, g;
            if (e.is(".disabled, :disabled")) return;
            return f = c(e), g = f.hasClass("open"), b(), g || (f.toggleClass("open"), e.focus()), !1;
        },
        keydown: function(b) {
            var d, e, f, g, h, i;
            if (!/(38|40|27)/.test(b.keyCode)) return;
            d = a(this), b.preventDefault(), b.stopPropagation();
            if (d.is(".disabled, :disabled")) return;
            g = c(d), h = g.hasClass("open");
            if (!h || h && b.keyCode == 27) return d.click();
            e = a("[role=menu] li:not(.divider) a", g);
            if (!e.length) return;
            i = e.index(e.filter(":focus")), b.keyCode == 38 && i > 0 && i--, b.keyCode == 40 && i < e.length - 1 && i++, ~i || (i = 0), e.eq(i).focus();
        }
    }, a.fn.dropdown = function(b) {
        return this.each(function() {
            var c = a(this),
                d = c.data("dropdown");
            d || c.data("dropdown", d = new e(this)), typeof b == "string" && d[b].call(c);
        });
    }, a.fn.dropdown.Constructor = e, a(document).on("click.dropdown.data-api touchstart.dropdown.data-api", b).on("click.dropdown touchstart.dropdown.data-api", ".dropdown form", function(a) {
        a.stopPropagation();
    }).on("click.dropdown.data-api touchstart.dropdown.data-api", d, e.prototype.toggle).on("keydown.dropdown.data-api touchstart.dropdown.data-api", d + ", [role=menu]", e.prototype.keydown);
}(window.jQuery), ! function(a) {
    "use strict";
    var b = function(a, b) {
        this.init("tooltip", a, b);
    };
    b.prototype = {
        constructor: b,
        init: function(b, c, d) {
            var e, f;
            this.type = b, this.$element = a(c), this.options = this.getOptions(d), this.enabled = !0, this.options.trigger == "click" ? this.$element.on("click." + this.type, this.options.selector, a.proxy(this.toggle, this)) : this.options.trigger != "manual" && (e = this.options.trigger == "hover" ? "mouseenter" : "focus", f = this.options.trigger == "hover" ? "mouseleave" : "blur", this.$element.on(e + "." + this.type, this.options.selector, a.proxy(this.enter, this)), this.$element.on(f + "." + this.type, this.options.selector, a.proxy(this.leave, this))), this.options.selector ? this._options = a.extend({}, this.options, {
                trigger: "manual",
                selector: ""
            }) : this.fixTitle();
        },
        getOptions: function(b) {
            return b = a.extend({}, a.fn[this.type].defaults, b, this.$element.data()), b.delay && typeof b.delay == "number" && (b.delay = {
                show: b.delay,
                hide: b.delay
            }), b;
        },
        enter: function(b) {
            var c = a(b.currentTarget)[this.type](this._options).data(this.type);
            if (!c.options.delay || !c.options.delay.show) return c.show();
            clearTimeout(this.timeout), c.hoverState = "in", this.timeout = setTimeout(function() {
                c.hoverState == "in" && c.show();
            }, c.options.delay.show);
        },
        leave: function(b) {
            var c = a(b.currentTarget)[this.type](this._options).data(this.type);
            this.timeout && clearTimeout(this.timeout);
            if (!c.options.delay || !c.options.delay.hide) return c.hide();
            c.hoverState = "out", this.timeout = setTimeout(function() {
                c.hoverState == "out" && c.hide();
            }, c.options.delay.hide);
        },
        show: function() {
            var a, b, c, d, e, f, g;
            if (this.hasContent() && this.enabled) {
                a = this.tip(), this.setContent(), this.options.animation && a.addClass("fade"), f = typeof this.options.placement == "function" ? this.options.placement.call(this, a[0], this.$element[0]) : this.options.placement, b = /in/.test(f), a.detach().css({
                    top: 0,
                    left: 0,
                    display: "block"
                }).insertAfter(this.$element), c = this.getPosition(b), d = a[0].offsetWidth, e = a[0].offsetHeight;
                switch (b ? f.split(" ")[1] : f) {
                    case "bottom":
                        g = {
                            top: c.top + c.height,
                            left: c.left + c.width / 2 - d / 2
                        };
                        break;
                    case "top":
                        g = {
                            top: c.top - e,
                            left: c.left + c.width / 2 - d / 2
                        };
                        break;
                    case "left":
                        g = {
                            top: c.top + c.height / 2 - e / 2,
                            left: c.left - d
                        };
                        break;
                    case "right":
                        g = {
                            top: c.top + c.height / 2 - e / 2,
                            left: c.left + c.width
                        };
                }
                a.offset(g).addClass(f).addClass("in");
            }
        },
        setContent: function() {
            var a = this.tip(),
                b = this.getTitle();
            a.find(".tooltip-inner")[this.options.html ? "html" : "text"](b), a.removeClass("fade in top bottom left right");
        },
        hide: function() {
            function b() {
                var b = setTimeout(function() {
                    d.off(a.support.transition.end).detach();
                }, 500);
                d.one(a.support.transition.end, function() {
                    clearTimeout(b), d.detach();
                });
            }
            var c = this,
                d = this.tip();
            return d.removeClass("in"), a.support.transition && this.$tip.hasClass("fade") ? b() : d.detach(), this;
        },
        fixTitle: function() {
            var a = this.$element;
            (a.attr("title") || typeof a.attr("data-original-title") != "string") && a.attr("data-original-title", a.attr("title") || "").removeAttr("title");
        },
        hasContent: function() {
            return this.getTitle();
        },
        getPosition: function(b) {
            return a.extend({}, b ? {
                top: 0,
                left: 0
            } : this.$element.offset(), {
                width: this.$element[0].offsetWidth,
                height: this.$element[0].offsetHeight
            });
        },
        getTitle: function() {
            var a, b = this.$element,
                c = this.options;
            return a = b.attr("data-original-title") || (typeof c.title == "function" ? c.title.call(b[0]) : c.title), a;
        },
        tip: function() {
            return this.$tip = this.$tip || a(this.options.template);
        },
        validate: function() {
            this.$element[0].parentNode || (this.hide(), this.$element = null, this.options = null);
        },
        enable: function() {
            this.enabled = !0;
        },
        disable: function() {
            this.enabled = !1;
        },
        toggleEnabled: function() {
            this.enabled = !this.enabled;
        },
        toggle: function(b) {
            var c = a(b.currentTarget)[this.type](this._options).data(this.type);
            c[c.tip().hasClass("in") ? "hide" : "show"]();
        },
        destroy: function() {
            this.hide().$element.off("." + this.type).removeData(this.type);
        }
    }, a.fn.tooltip = function(c) {
        return this.each(function() {
            var d = a(this),
                e = d.data("tooltip"),
                f = typeof c == "object" && c;
            e || d.data("tooltip", e = new b(this, f)), typeof c == "string" && e[c]();
        });
    }, a.fn.tooltip.Constructor = b, a.fn.tooltip.defaults = {
        animation: !0,
        placement: "top",
        selector: !1,
        template: '<div class="tooltip"><div class="tooltip-arrow"></div><div class="tooltip-inner"></div></div>',
        trigger: "hover",
        title: "",
        delay: 0,
        html: !1
    };
}(window.jQuery), ! function(a) {
    "use strict";
    var b = function(a, b) {
        this.init("popover", a, b);
    };
    b.prototype = a.extend({}, a.fn.tooltip.Constructor.prototype, {
        constructor: b,
        setContent: function() {
            var a = this.tip(),
                b = this.getTitle(),
                c = this.getContent();
            a.find(".popover-title")[this.options.html ? "html" : "text"](b), a.find(".popover-content > *")[this.options.html ? "html" : "text"](c), a.removeClass("fade top bottom left right in");
        },
        hasContent: function() {
            return this.getTitle() || this.getContent();
        },
        getContent: function() {
            var a, b = this.$element,
                c = this.options;
            return a = b.attr("data-content") || (typeof c.content == "function" ? c.content.call(b[0]) : c.content), a;
        },
        tip: function() {
            return this.$tip || (this.$tip = a(this.options.template)), this.$tip;
        },
        destroy: function() {
            this.hide().$element.off("." + this.type).removeData(this.type);
        }
    }), a.fn.popover = function(c) {
        return this.each(function() {
            var d = a(this),
                e = d.data("popover"),
                f = typeof c == "object" && c;
            e || d.data("popover", e = new b(this, f)), typeof c == "string" && e[c]();
        });
    }, a.fn.popover.Constructor = b, a.fn.popover.defaults = a.extend({}, a.fn.tooltip.defaults, {
        placement: "right",
        trigger: "click",
        content: "",
        template: '<div class="popover"><div class="arrow"></div><div class="popover-inner"><h3 class="popover-title"></h3><div class="popover-content"><p></p></div></div></div>'
    });
}(window.jQuery), ! function(a) {
    "use strict";

    function b(b, c) {
        var d = a.proxy(this.process, this),
            e = a(b).is("body") ? a(window) : a(b),
            f;
        this.options = a.extend({}, a.fn.scrollspy.defaults, c), this.$scrollElement = e.on("scroll.scroll-spy.data-api", d), this.selector = (this.options.target || (f = a(b).attr("href")) && f.replace(/.*(?=#[^\s]+$)/, "") || "") + " .nav li > a", this.$body = a("body"), this.refresh(), this.process();
    }
    b.prototype = {
        constructor: b,
        refresh: function() {
            var b = this,
                c;
            this.offsets = a([]), this.targets = a([]), c = this.$body.find(this.selector).map(function() {
                var b = a(this),
                    c = b.data("target") || b.attr("href"),
                    d = /^#\w/.test(c) && a(c);
                return d && d.length && [
                        [d.position().top, c]
                    ] || null;
            }).sort(function(a, b) {
                return a[0] - b[0];
            }).each(function() {
                b.offsets.push(this[0]), b.targets.push(this[1]);
            });
        },
        process: function() {
            var a = this.$scrollElement.scrollTop() + this.options.offset,
                b = this.$scrollElement[0].scrollHeight || this.$body[0].scrollHeight,
                c = b - this.$scrollElement.height(),
                d = this.offsets,
                e = this.targets,
                f = this.activeTarget,
                g;
            if (a >= c) return f != (g = e.last()[0]) && this.activate(g);
            for (g = d.length; g--;) f != e[g] && a >= d[g] && (!d[g + 1] || a <= d[g + 1]) && this.activate(e[g]);
        },
        activate: function(b) {
            var c, d;
            this.activeTarget = b, a(this.selector).parent(".active").removeClass("active"), d = this.selector + '[data-target="' + b + '"],' + this.selector + '[href="' + b + '"]', c = a(d).parent("li").addClass("active"), c.parent(".dropdown-menu").length && (c = c.closest("li.dropdown").addClass("active")), c.trigger("activate");
        }
    }, a.fn.scrollspy = function(c) {
        return this.each(function() {
            var d = a(this),
                e = d.data("scrollspy"),
                f = typeof c == "object" && c;
            e || d.data("scrollspy", e = new b(this, f)), typeof c == "string" && e[c]();
        });
    }, a.fn.scrollspy.Constructor = b, a.fn.scrollspy.defaults = {
        offset: 10
    }, a(window).on("load", function() {
        a('[data-spy="scroll"]').each(function() {
            var b = a(this);
            b.scrollspy(b.data());
        });
    });
}(window.jQuery), ! function(a) {
    "use strict";
    var b = function(b) {
        this.element = a(b);
    };
    b.prototype = {
        constructor: b,
        show: function() {
            var b = this.element,
                c = b.closest("ul:not(.dropdown-menu)"),
                d = b.attr("data-target"),
                e, f, g;
            d || (d = b.attr("href"), d = d && d.replace(/.*(?=#[^\s]*$)/, ""));
            if (b.parent("li").hasClass("active")) return;
            e = c.find(".active:last a")[0], g = a.Event("show", {
                relatedTarget: e
            }), b.trigger(g);
            if (g.isDefaultPrevented()) return;
            f = a(d), this.activate(b.parent("li"), c), this.activate(f, f.parent(), function() {
                b.trigger({
                    type: "shown",
                    relatedTarget: e
                });
            });
        },
        activate: function(b, c, d) {
            function e() {
                f.removeClass("active").find("> .dropdown-menu > .active").removeClass("active"), b.addClass("active"), g ? (b[0].offsetWidth, b.addClass("in")) : b.removeClass("fade"), b.parent(".dropdown-menu") && b.closest("li.dropdown").addClass("active"), d && d();
            }
            var f = c.find("> .active"),
                g = d && a.support.transition && f.hasClass("fade");
            g ? f.one(a.support.transition.end, e) : e(), f.removeClass("in");
        }
    }, a.fn.tab = function(c) {
        return this.each(function() {
            var d = a(this),
                e = d.data("tab");
            e || d.data("tab", e = new b(this)), typeof c == "string" && e[c]();
        });
    }, a.fn.tab.Constructor = b, a(document).on("click.tab.data-api", '[data-toggle="tab"], [data-toggle="pill"]', function(b) {
        b.preventDefault(), a(this).tab("show");
    });
}(window.jQuery), ! function(a) {
    "use strict";
    var b = function(b, c) {
        this.$element = a(b), this.options = a.extend({}, a.fn.typeahead.defaults, c), this.matcher = this.options.matcher || this.matcher, this.sorter = this.options.sorter || this.sorter, this.highlighter = this.options.highlighter || this.highlighter, this.updater = this.options.updater || this.updater, this.$menu = a(this.options.menu).appendTo("body"), this.source = this.options.source, this.shown = !1, this.listen();
    };
    b.prototype = {
        constructor: b,
        select: function() {
            var a = this.$menu.find(".active").attr("data-value");
            return this.$element.val(this.updater(a)).change(), this.hide();
        },
        updater: function(a) {
            return a;
        },
        show: function() {
            var b = a.extend({}, this.$element.offset(), {
                height: this.$element[0].offsetHeight
            });
            return this.$menu.css({
                top: b.top + b.height,
                left: b.left
            }), this.$menu.show(), this.shown = !0, this;
        },
        hide: function() {
            return this.$menu.hide(), this.shown = !1, this;
        },
        lookup: function(b) {
            var c;
            return this.query = this.$element.val(), !this.query || this.query.length < this.options.minLength ? this.shown ? this.hide() : this : (c = a.isFunction(this.source) ? this.source(this.query, a.proxy(this.process, this)) : this.source, c ? this.process(c) : this);
        },
        process: function(b) {
            var c = this;
            return b = a.grep(b, function(a) {
                return c.matcher(a);
            }), b = this.sorter(b), b.length ? this.render(b.slice(0, this.options.items)).show() : this.shown ? this.hide() : this;
        },
        matcher: function(a) {
            return ~a.toLowerCase().indexOf(this.query.toLowerCase());
        },
        sorter: function(a) {
            var b = [],
                c = [],
                d = [],
                e;
            while (e = a.shift()) e.toLowerCase().indexOf(this.query.toLowerCase()) ? ~e.indexOf(this.query) ? c.push(e) : d.push(e) : b.push(e);
            return b.concat(c, d);
        },
        highlighter: function(a) {
            var b = this.query.replace(/[\-\[\]{}()*+?.,\\\^$|#\s]/g, "\\$&");
            return a.replace(new RegExp("(" + b + ")", "ig"), function(a, b) {
                return "<strong>" + b + "</strong>";
            });
        },
        render: function(b) {
            var c = this;
            return b = a(b).map(function(b, d) {
                return b = a(c.options.item).attr("data-value", d), b.find("a").html(c.highlighter(d)), b[0];
            }), b.first().addClass("active"), this.$menu.html(b), this;
        },
        next: function(b) {
            var c = this.$menu.find(".active").removeClass("active"),
                d = c.next();
            d.length || (d = a(this.$menu.find("li")[0])), d.addClass("active");
        },
        prev: function(a) {
            var b = this.$menu.find(".active").removeClass("active"),
                c = b.prev();
            c.length || (c = this.$menu.find("li").last()), c.addClass("active");
        },
        listen: function() {
            this.$element.on("blur", a.proxy(this.blur, this)).on("keypress", a.proxy(this.keypress, this)).on("keyup", a.proxy(this.keyup, this)), this.eventSupported("keydown") && this.$element.on("keydown", a.proxy(this.keydown, this)), this.$menu.on("click", a.proxy(this.click, this)).on("mouseenter", "li", a.proxy(this.mouseenter, this));
        },
        eventSupported: function(a) {
            var b = a in this.$element;
            return b || (this.$element.setAttribute(a, "return;"), b = typeof this.$element[a] == "function"), b;
        },
        move: function(a) {
            if (!this.shown) return;
            switch (a.keyCode) {
                case 9:
                case 13:
                case 27:
                    a.preventDefault();
                    break;
                case 38:
                    a.preventDefault(), this.prev();
                    break;
                case 40:
                    a.preventDefault(), this.next();
            }
            a.stopPropagation();
        },
        keydown: function(b) {
            this.suppressKeyPressRepeat = !~a.inArray(b.keyCode, [40, 38, 9, 13, 27]), this.move(b);
        },
        keypress: function(a) {
            if (this.suppressKeyPressRepeat) return;
            this.move(a);
        },
        keyup: function(a) {
            switch (a.keyCode) {
                case 40:
                case 38:
                case 16:
                case 17:
                case 18:
                    break;
                case 9:
                case 13:
                    if (!this.shown) return;
                    this.select();
                    break;
                case 27:
                    if (!this.shown) return;
                    this.hide();
                    break;
                default:
                    this.lookup();
            }
            a.stopPropagation(), a.preventDefault();
        },
        blur: function(a) {
            var b = this;
            setTimeout(function() {
                b.hide();
            }, 150);
        },
        click: function(a) {
            a.stopPropagation(), a.preventDefault(), this.select();
        },
        mouseenter: function(b) {
            this.$menu.find(".active").removeClass("active"), a(b.currentTarget).addClass("active");
        }
    }, a.fn.typeahead = function(c) {
        return this.each(function() {
            var d = a(this),
                e = d.data("typeahead"),
                f = typeof c == "object" && c;
            e || d.data("typeahead", e = new b(this, f)), typeof c == "string" && e[c]();
        });
    }, a.fn.typeahead.defaults = {
        source: [],
        items: 8,
        menu: '<ul class="typeahead dropdown-menu"></ul>',
        item: '<li><a href="#"></a></li>',
        minLength: 1
    }, a.fn.typeahead.Constructor = b, a(document).on("focus.typeahead.data-api", '[data-provide="typeahead"]', function(b) {
        var c = a(this);
        if (c.data("typeahead")) return;
        b.preventDefault(), c.typeahead(c.data());
    });
}(window.jQuery), ! function(a) {
    "use strict";
    var b = function(b, c) {
        this.options = a.extend({}, a.fn.affix.defaults, c), this.$window = a(window).on("scroll.affix.data-api", a.proxy(this.checkPosition, this)).on("click.affix.data-api", a.proxy(function() {
            setTimeout(a.proxy(this.checkPosition, this), 1);
        }, this)), this.$element = a(b), this.checkPosition();
    };
    b.prototype.checkPosition = function() {
        if (!this.$element.is(":visible")) return;
        var b = a(document).height(),
            c = this.$window.scrollTop(),
            d = this.$element.offset(),
            e = this.options.offset,
            f = e.bottom,
            g = e.top,
            h = "affix affix-top affix-bottom",
            i;
        typeof e != "object" && (f = g = e), typeof g == "function" && (g = e.top()), typeof f == "function" && (f = e.bottom()), i = this.unpin != null && c + this.unpin <= d.top ? !1 : f != null && d.top + this.$element.height() >= b - f ? "bottom" : g != null && c <= g ? "top" : !1;
        if (this.affixed === i) return;
        this.affixed = i, this.unpin = i == "bottom" ? d.top - c : null, this.$element.removeClass(h).addClass("affix" + (i ? "-" + i : ""));
    }, a.fn.affix = function(c) {
        return this.each(function() {
            var d = a(this),
                e = d.data("affix"),
                f = typeof c == "object" && c;
            e || d.data("affix", e = new b(this, f)), typeof c == "string" && e[c]();
        });
    }, a.fn.affix.Constructor = b, a.fn.affix.defaults = {
        offset: 0
    }, a(window).on("load", function() {
        a('[data-spy="affix"]').each(function() {
            var b = a(this),
                c = b.data();
            c.offset = c.offset || {}, c.offsetBottom && (c.offset.bottom = c.offsetBottom), c.offsetTop && (c.offset.top = c.offsetTop), b.affix(c);
        });
    });
}(window.jQuery);
jQuery.easing.jswing = jQuery.easing.swing;
jQuery.extend(jQuery.easing, {
    def: 'easeOutQuad',
    swing: function(a, b, c, d, e) {
        return jQuery.easing[jQuery.easing.def](a, b, c, d, e);
    },
    easeInQuad: function(a, b, c, d, e) {
        return d * (b /= e) * b + c;
    },
    easeOutQuad: function(a, b, c, d, e) {
        return -d * (b /= e) * (b - 2) + c;
    },
    easeInOutQuad: function(a, b, c, d, e) {
        if ((b /= e / 2) < 1) return d / 2 * b * b + c;
        return -d / 2 * ((--b) * (b - 2) - 1) + c;
    },
    easeInCubic: function(a, b, c, d, e) {
        return d * (b /= e) * b * b + c;
    },
    easeOutCubic: function(a, b, c, d, e) {
        return d * ((b = b / e - 1) * b * b + 1) + c;
    },
    easeInOutCubic: function(a, b, c, d, e) {
        if ((b /= e / 2) < 1) return d / 2 * b * b * b + c;
        return d / 2 * ((b -= 2) * b * b + 2) + c;
    },
    easeInQuart: function(a, b, c, d, e) {
        return d * (b /= e) * b * b * b + c;
    },
    easeOutQuart: function(a, b, c, d, e) {
        return -d * ((b = b / e - 1) * b * b * b - 1) + c;
    },
    easeInOutQuart: function(a, b, c, d, e) {
        if ((b /= e / 2) < 1) return d / 2 * b * b * b * b + c;
        return -d / 2 * ((b -= 2) * b * b * b - 2) + c;
    },
    easeInQuint: function(a, b, c, d, e) {
        return d * (b /= e) * b * b * b * b + c;
    },
    easeOutQuint: function(a, b, c, d, e) {
        return d * ((b = b / e - 1) * b * b * b * b + 1) + c;
    },
    easeInOutQuint: function(a, b, c, d, e) {
        if ((b /= e / 2) < 1) return d / 2 * b * b * b * b * b + c;
        return d / 2 * ((b -= 2) * b * b * b * b + 2) + c;
    },
    easeInSine: function(a, b, c, d, e) {
        return -d * Math.cos(b / e * (Math.PI / 2)) + d + c;
    },
    easeOutSine: function(a, b, c, d, e) {
        return d * Math.sin(b / e * (Math.PI / 2)) + c;
    },
    easeInOutSine: function(a, b, c, d, e) {
        return -d / 2 * (Math.cos(Math.PI * b / e) - 1) + c;
    },
    easeInExpo: function(a, b, c, d, e) {
        return (b == 0) ? c : d * Math.pow(2, 10 * (b / e - 1)) + c;
    },
    easeOutExpo: function(a, b, c, d, e) {
        return (b == e) ? c + d : d * (-Math.pow(2, -10 * b / e) + 1) + c;
    },
    easeInOutExpo: function(a, b, c, d, e) {
        if (b == 0) return c;
        if (b == e) return c + d;
        if ((b /= e / 2) < 1) return d / 2 * Math.pow(2, 10 * (b - 1)) + c;
        return d / 2 * (-Math.pow(2, -10 * --b) + 2) + c;
    },
    easeInCirc: function(a, b, c, d, e) {
        return -d * (Math.sqrt(1 - (b /= e) * b) - 1) + c;
    },
    easeOutCirc: function(a, b, c, d, e) {
        return d * Math.sqrt(1 - (b = b / e - 1) * b) + c;
    },
    easeInOutCirc: function(a, b, c, d, e) {
        if ((b /= e / 2) < 1) return -d / 2 * (Math.sqrt(1 - b * b) - 1) + c;
        return d / 2 * (Math.sqrt(1 - (b -= 2) * b) + 1) + c;
    },
    easeInElastic: function(a, b, c, d, e) {
        var f = 1.70158;
        var g = 0;
        var h = d;
        if (b == 0) return c;
        if ((b /= e) == 1) return c + d;
        if (!g) g = e * .3;
        if (h < Math.abs(d)) {
            h = d;
            var f = g / 4;
        } else var f = g / (2 * Math.PI) * Math.asin(d / h);
        return -(h * Math.pow(2, 10 * (b -= 1)) * Math.sin((b * e - f) * (2 * Math.PI) / g)) + c;
    },
    easeOutElastic: function(a, b, c, d, e) {
        var f = 1.70158;
        var g = 0;
        var h = d;
        if (b == 0) return c;
        if ((b /= e) == 1) return c + d;
        if (!g) g = e * .3;
        if (h < Math.abs(d)) {
            h = d;
            var f = g / 4;
        } else var f = g / (2 * Math.PI) * Math.asin(d / h);
        return h * Math.pow(2, -10 * b) * Math.sin((b * e - f) * (2 * Math.PI) / g) + d + c;
    },
    easeInOutElastic: function(a, b, c, d, e) {
        var f = 1.70158;
        var g = 0;
        var h = d;
        if (b == 0) return c;
        if ((b /= e / 2) == 2) return c + d;
        if (!g) g = e * (.3 * 1.5);
        if (h < Math.abs(d)) {
            h = d;
            var f = g / 4;
        } else var f = g / (2 * Math.PI) * Math.asin(d / h);
        if (b < 1) return -.5 * (h * Math.pow(2, 10 * (b -= 1)) * Math.sin((b * e - f) * (2 * Math.PI) / g)) + c;
        return h * Math.pow(2, -10 * (b -= 1)) * Math.sin((b * e - f) * (2 * Math.PI) / g) * .5 + d + c;
    },
    easeInBack: function(a, b, c, d, e, f) {
        if (f == undefined) f = 1.70158;
        return d * (b /= e) * b * ((f + 1) * b - f) + c;
    },
    easeOutBack: function(a, b, c, d, e, f) {
        if (f == undefined) f = 1.70158;
        return d * ((b = b / e - 1) * b * ((f + 1) * b + f) + 1) + c;
    },
    easeInOutBack: function(a, b, c, d, e, f) {
        if (f == undefined) f = 1.70158;
        if ((b /= e / 2) < 1) return d / 2 * (b * b * (((f *= 1.525) + 1) * b - f)) + c;
        return d / 2 * ((b -= 2) * b * (((f *= 1.525) + 1) * b + f) + 2) + c;
    },
    easeInBounce: function(a, b, c, d, e) {
        return d - jQuery.easing.easeOutBounce(a, e - b, 0, d, e) + c;
    },
    easeOutBounce: function(a, b, c, d, e) {
        if ((b /= e) < (1 / 2.75)) return d * (7.5625 * b * b) + c;
        else if (b < (2 / 2.75)) return d * (7.5625 * (b -= (1.5 / 2.75)) * b + .75) + c;
        else if (b < (2.5 / 2.75)) return d * (7.5625 * (b -= (2.25 / 2.75)) * b + .9375) + c;
        else return d * (7.5625 * (b -= (2.625 / 2.75)) * b + .984375) + c;
    },
    easeInOutBounce: function(a, b, c, d, e) {
        if (b < e / 2) return jQuery.easing.easeInBounce(a, b * 2, 0, d, e) * .5 + c;
        return jQuery.easing.easeOutBounce(a, b * 2 - e, 0, d, e) * .5 + d * .5 + c;
    }
});
