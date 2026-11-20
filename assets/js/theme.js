/* Ayam Geprek Romeesa - perilaku antarmuka bersama. */
(function () {
    "use strict";

    /* Sidebar geser untuk layar kecil */
    var sidebar = document.getElementById("appSidebar");
    var backdrop = document.querySelector("[data-sidebar-backdrop]");
    var toggle = document.querySelector("[data-sidebar-toggle]");

    function setSidebar(open) {
        if (!sidebar || !toggle) return;
        sidebar.classList.toggle("is-open", open);
        toggle.setAttribute("aria-expanded", open ? "true" : "false");
        if (backdrop) backdrop.hidden = !open;
        document.body.style.overflow = open ? "hidden" : "";
    }

    if (toggle) {
        toggle.addEventListener("click", function () {
            setSidebar(!sidebar.classList.contains("is-open"));
        });
    }
    if (backdrop) backdrop.addEventListener("click", function () { setSidebar(false); });
    document.addEventListener("keydown", function (e) {
        if (e.key === "Escape") setSidebar(false);
    });

    /* Penyaring daftar menu: chip kategori dan kolom pencarian */
    Array.prototype.forEach.call(document.querySelectorAll("[data-filter]"), function (root) {
        var input = root.querySelector("[data-filter-search]");
        var chips = Array.prototype.slice.call(root.querySelectorAll("[data-filter-chip]"));
        var items = Array.prototype.slice.call(root.querySelectorAll("[data-filter-item]"));
        var counter = root.querySelector("[data-filter-count]");
        var empty = root.querySelector("[data-filter-empty]");
        var active = "semua";

        function apply() {
            var q = ((input && input.value) || "").toLowerCase().trim();
            var shown = 0;
            items.forEach(function (item) {
                var name = (item.getAttribute("data-name") || "").toLowerCase();
                var kategori = item.getAttribute("data-category") || "";
                var cocok = (active === "semua" || kategori === active) && (!q || name.indexOf(q) !== -1);
                item.hidden = !cocok;
                if (cocok) shown++;
            });
            if (counter) counter.textContent = shown;
            if (empty) empty.hidden = shown !== 0;
        }

        chips.forEach(function (chip) {
            chip.addEventListener("click", function () {
                chips.forEach(function (lain) {
                    lain.classList.remove("is-active");
                    lain.setAttribute("aria-pressed", "false");
                });
                chip.classList.add("is-active");
                chip.setAttribute("aria-pressed", "true");
                active = chip.getAttribute("data-filter-chip");
                apply();
            });
        });

        if (input) input.addEventListener("input", apply);
        apply();
    });
})();
