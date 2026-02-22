document.getElementById("toggleSidebar")?.addEventListener("click", () => {
    document.querySelector(".sidebar").classList.toggle("hide");
    document.querySelector(".main-content").classList.toggle("full");
});