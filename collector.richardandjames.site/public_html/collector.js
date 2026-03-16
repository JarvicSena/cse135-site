(() => {
  const ENDPOINT = "https://collector.richardandjames.site/log/";
  const SESSION_KEY = "cse135_session_id";
  const IDLE_MS = 2000;
  const FLUSH_EVERY_MS = 3000;

  //session id
  function uuid() {
    return ([1e7]+-1e3+-4e3+-8e3+-1e11).replace(/[018]/g, c =>
      (c ^ (crypto.getRandomValues(new Uint8Array(1))[0] & (15 >> (c / 4)))).toString(16)
    );
  }

  function getSessionId() {
    let id = localStorage.getItem(SESSION_KEY);
    if (!id) {
      id = uuid();
      localStorage.setItem(SESSION_KEY, id);
    }
    return id;
  }

  const sessionId = getSessionId();

  //event queue
  const queue = [];
  function nowISO() { return new Date().toISOString(); }

  function basePayload(type) {
    return {
      type,
      sessionId,
      page: location.href,
      ts: nowISO(),
    };
  }

  //prefered sendBeacon when page is leaving/hidden
  function send(payload, preferBeacon = false) {
    const body = JSON.stringify(payload);

    if (preferBeacon && navigator.sendBeacon) {
      const ok = navigator.sendBeacon(ENDPOINT, new Blob([body], { type: "application/json" }));
      return ok;
    }

    //fetch is fine for normal sends
    fetch(ENDPOINT, {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body,
      keepalive: true, //helps during unload-like situations
    }).catch(() => {});
    return true;
  }

  function flush(preferBeacon = false) {
    if (queue.length === 0) return;
    const batch = queue.splice(0, queue.length);
    send({ ...basePayload("batch"), events: batch }, preferBeacon);
  }

  setInterval(() => flush(false), FLUSH_EVERY_MS);

  //static data
  function detectImagesEnabled() {
    return new Promise((resolve) => {
      const img = new Image();
      img.onload = () => resolve(true);
      img.onerror = () => resolve(false);
      //any image on test site:
      img.src = location.origin + "/favicon.ico?imgtest=" + Date.now();
    });
  }

  function detectCssEnabled() {
    //if computed style differs from default, CSS is probably enabled
    const el = document.createElement("div");
    el.style.display = "none";
    el.className = "css-test";
    document.body.appendChild(el);
    const computed = getComputedStyle(el);
    document.body.removeChild(el);
    return !!computed; //if getComputedStyle works, CSS engine is running
  }

  async function collectStatic() {
    const imagesEnabled = await detectImagesEnabled();
    const cssEnabled = detectCssEnabled();

    const staticData = {
      ...basePayload("static"),
      userAgent: navigator.userAgent,
      language: navigator.language,
      cookiesEnabled: navigator.cookieEnabled,
      jsEnabled: true, //if this ran, JS is enabled
      imagesEnabled,
      cssEnabled,
      screen: { width: screen.width, height: screen.height },
      window: { width: innerWidth, height: innerHeight },
      connection: navigator.connection?.effectiveType || null,
    };

    send(staticData, false);
  }

  //performance data
  function collectPerformance() {
    const nav = performance.getEntriesByType("navigation")[0];
    //fallback: older timing object
    const timing = performance.timing ? { ...performance.timing } : null;

    let start = null, end = null, totalMs = null;

    if (nav) {
      start = nav.startTime; //ms relative to page start
      end = nav.loadEventEnd; //ms relative to page start
      totalMs = Math.max(0, end - start);
    } else if (timing) {
      start = timing.navigationStart;
      end = timing.loadEventEnd;
      totalMs = Math.max(0, end - start);
    }

    const perfData = {
      ...basePayload("performance"),
      pageStart: start,
      pageEnd: end,
      totalLoadTimeMs: totalMs,
      timingObject: nav ? nav.toJSON() : timing,
    };

    send(perfData, false);
  }

  //activity tracking
  let lastActivity = Date.now();
  let idleStart = null;

  function noteActivity() {
    const t = Date.now();

    //if we were idle, close it out
    if (idleStart !== null) {
      const idleEnd = t;
      queue.push({
        ...basePayload("idle_end"),
        idleStart,
        idleEnd,
        idleDurationMs: idleEnd - idleStart,
      });
      idleStart = null;
    }

    lastActivity = t;
  }

  setInterval(() => {
    const t = Date.now();
    if (idleStart === null && t - lastActivity >= IDLE_MS) {
      idleStart = t;
      queue.push({ ...basePayload("idle_start"), idleStart });
    }
  }, 250);

  //throttle mousemove because it fires a lot
  function throttle(fn, wait) {
    let last = 0;
    return (...args) => {
      const t = Date.now();
      if (t - last >= wait) {
        last = t;
        fn(...args);
      }
    };
  }

  window.addEventListener("mousemove", throttle((e) => {
    noteActivity();
    queue.push({
      ...basePayload("mousemove"),
      x: e.clientX,
      y: e.clientY,
    });
  }, 200));

  window.addEventListener("click", (e) => {
    noteActivity();
    queue.push({
      ...basePayload("click"),
      button: e.button, //0 left, 1 middle, 2 right
      x: e.clientX,
      y: e.clientY,
    });
  });

  window.addEventListener("scroll", throttle(() => {
    noteActivity();
    queue.push({
      ...basePayload("scroll"),
      scrollX: window.scrollX,
      scrollY: window.scrollY,
    });
  }, 250));

  //keyboard activity
  window.addEventListener("keydown", (e) => {
    noteActivity();
    queue.push({
      ...basePayload("keydown"),
      key: e.key,
      code: e.code,
    });
  });

  window.addEventListener("keyup", (e) => {
    noteActivity();
    queue.push({
      ...basePayload("keyup"),
      key: e.key,
      code: e.code,
    });
  });

  //errors
  window.addEventListener("error", (e) => {
    queue.push({
      ...basePayload("error"),
      message: e.message || "error",
      filename: e.filename || null,
      lineno: e.lineno || null,
      colno: e.colno || null,
    });
  });

  //page enter
  queue.push({ ...basePayload("page_enter") });

  //page leave: use visibilitychange + sendBeacon(suggested)
  document.addEventListener("visibilitychange", () => {
    if (document.visibilityState === "hidden") {
      queue.push({ ...basePayload("page_leave") });
      flush(true);
    }
  });

  //run collectors after load
  window.addEventListener("load", async () => {
    await collectStatic();
    collectPerformance();
  });
})();
