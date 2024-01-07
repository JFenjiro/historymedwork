const X = window.matchMedia("(max-width: 780px)");
const W = window.innerWidth;
const H = window.innerHeight;

window.addEventListener("load", (e) => {
    let btnScroll = document.querySelector("#btn-scroll");
    btnScroll.addEventListener("click", (event) => {
        scrollMenu();
    });
});

function scrollMenu() {
    if (X.matches) {
        let closeBtn = document.querySelector("#close-btn");
        closeBtn.style.display = "flex";
        document.querySelector("nav").style.display = "flex";
        document.querySelector("nav").style.width = W + "px";
        document.querySelector("nav").style.height = "500px";

        let disconnect = document.querySelector("#dropdown-disconnect");
        disconnect.style.display = "flex";
        disconnect.style.position = "relative";
        disconnect.style.left = "0";
        disconnect.style.alignItems = "center";

        closeBtn.addEventListener("click", unscrollMenu);
    } else {
        let disconnect = document.querySelector("#dropdown-disconnect");
        disconnect.style.display = "flex";

        let closeBtn2 = document.querySelector("#close-btn2");
        closeBtn2.style.display = "flex";
        closeBtn2.addEventListener("click", unscrollMenu);
    }
}

function unscrollMenu() {
    if (X.matches) {
        document.querySelector("nav").style.width = W + "px";
        document.querySelector("nav").style.height = "0";
        document.querySelector("nav").style.display = "none";
        document.querySelector("#dropdown-disconnect").style.display = "none";
    } else {
        document.querySelector("#dropdown-disconnect").style.display = "none";
    }
}