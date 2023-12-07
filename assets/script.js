document.addEventListener("DOMContentLoaded", function() {
    const tabLinks = document.querySelectorAll(".zesthours-help-tab-links a");
    const tabContents = document.querySelectorAll(".zesthours-help-tab-content .zesthours-help-tab");

    tabLinks.forEach((link) => {
        link.addEventListener("click", function (e) {
            e.preventDefault();
            tabLinks.forEach((l) => l.parentElement.classList.remove("zesthours-help-tab-active"));
            this.parentElement.classList.add("zesthours-help-tab-active");

            const targetTab = document.querySelector(this.getAttribute("href"));
            tabContents.forEach((tab) => tab.classList.remove("zesthours-help-tab-active"));
            targetTab.classList.add("zesthours-help-tab-active");
        });
    });
});