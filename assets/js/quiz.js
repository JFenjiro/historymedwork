let counter = 0;

// let currentTab = 0;
// showTab(currentTab);

// function showTab(n) {
//     // This function will display the specified tab of the form
//     // and fix the Previous/Next buttons:
//     let x = document.getElementsByClassName("quiz-4");
//     x[n].style.display = "block";

//     if (n == 0) {
//         document.getElementById("prevBtn").style.display = "none";
//     } else {
//         document.getElementById("prevBtn").style.display = "inline";
//     }

//     if (n == (x.length - 1)) {
//         let submitBtn = document.getElementById("nextBtn");
//         submitBtn.style.display = "none";
//         let seeBtn = document.querySelector("#see-score");
//         seeBtn.style.display = "inline";
//     } else {
//         document.getElementById("nextBtn").innerHTML = "Question suivante";
//     }

//     fixStepIndicator(n);
// }

// function nextPrev(n) {
//     counter++;
//     // This function will figure out which tab to display
//     let x = document.getElementsByClassName("quiz-4");

//     if (n == 1 && !validateForm()) return false;
//     x[currentTab].style.display = "none";
//     currentTab = currentTab + n;

//     if (currentTab >= x.length) {
//         document.getElementById("regForm" + counter).submit();
//         return false;
//     }

//     showTab(currentTab);
// }

// function validateForm() {
//     // This function deals with validation of the form fields
//     let x, y, z, i, valid = true;
//     x = document.getElementsByClassName("quiz-4");
//     y = x[currentTab].querySelector("input:checked");

//     for(i = 0; i == y.checked; i++) {
//         if (y[i].checked == false) {
//             y[i].className += " invalid";
//             valid = false;
//         }
//     }
    
//     if (valid) {
//         document.getElementsByClassName("step1")[currentTab].className += " finish";
//     }

//     return valid;
// }

// function fixStepIndicator(n) {
//     // This function removes the "active" class of all steps
//     // and adds the "active" class to the current step
//     let i, x = document.getElementsByClassName("step1");

//     for (i = 0; i < x.length; i++) {
//         x[i].className.replace(" active", "");
//     }
//     x[n].className += " active";
// }

// window.addEventListener("load", (e) => {
    
// }, flase);