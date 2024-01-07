// Showing tabs

window.addEventListener("load", (e) => {
    let userTab = document.querySelector("#second-tab");
    userTab.addEventListener("click", (event) => {
        switchTab();
    }, false);
}, false);

function switchTab() {
    let workSection = document.querySelector("#work-space");
    let userSection = document.querySelector("#user-info");

    userSection.style.display = "flex";
    workSection.style.display = "none";

    let userTab = document.querySelector("#second-tab");
    let workTab = document.querySelector("#first-tab");

    userTab.style.opacity = "1";
    userTab.style.zIndex = "1";
    workTab.style.opacity = "0.8";
    workTab.style.zIndex = "0";
    
    if (X.matches) {
        userTab.style.width = "50%";
        workTab.style.width = "30%";
    } else {
        userTab.style.width = "20%";
        workTab.style.width = "10%";
    }
    
    workTab.addEventListener("click", returnTab);
}

function returnTab() {
    let workSection = document.querySelector("#work-space");
    let userSection = document.querySelector("#user-info");

    userSection.style.display = "none";
    workSection.style.display = "flex";

    let userTab = document.querySelector("#second-tab");
    let workTab = document.querySelector("#first-tab");

    userTab.style.opacity = "0.8";
    userTab.style.zIndex = "0";
    workTab.style.opacity = "1";
    workTab.style.zIndex = "1";

    if (X.matches) {
        userTab.style.width = "30%";
        workTab.style.width = "50%";
    } else {
        userTab.style.width = "10%";
        workTab.style.width = "20%";
    }
}

// Modales de changement d'icône
window.addEventListener("load", (e) => {
    let userChoice = document.querySelectorAll(".user-choice");

    userChoice.forEach(element => {
        element.addEventListener("click", (event) => {
            selectIcon(element.dataset.target);
        }, false);
    }, false); 
}, false);

function selectIcon($modal) {
    let choseIcon = document.querySelector($modal);
    choseIcon.style.display = "flex";

    let closeChoice = document.querySelectorAll(".close-btn3");
    closeChoice.forEach(element => {
        element.addEventListener("click", (evnt) => {
            getoutIcon(element.dataset.target);
        }, false);
    }, false);
}

function getoutIcon($modal) {
    let choseIcon = document.querySelector($modal);
    choseIcon.style.display = "none";
}