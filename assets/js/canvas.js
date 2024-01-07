// Canvas frise chronologique

window.addEventListener("load", (e) => {
    const canvas = document.getElementById("canvas1");
    const ctx = canvas.getContext("2d");
    ctx.fillStyle = "#111111";
    ctx.fillRect(0,0,3000,1000);
    ctx.moveTo(0, canvas.clientHeight / 2);
    ctx.lineTo(canvas.clientWidth, canvas.clientHeight / 2);
    ctx.strokeStyle = "#ffffff";
    ctx.stroke();
    ctx.fillStyle = "#ffffff";
    ctx.font = "10px Arial";
    friseChrono();
}, false);

function friseChrono() {

    zoomEnabled = true;

    const axe_y_1 = 490, axe_x_1 = 510;
    const axe_y_2 = 492, axe_x_2 = 508;
    const axe_y_3 = 495, axe_x_3 = 505;

    for (let i = 0; i <= 100; i++) {
        if (i % 10 == 0) {
            // multiple de 10
            ctx.moveTo(0, axe_y_1);
            ctx.lineTo(0, axe_x_1);
            ctx.stroke();
            console.log(i + " grand trait");
        } else if (i % 5 == 0) {
            ctx.moveTo(0, axe_y_2);
            ctx.lineTo(0, axe_x_2);
            ctx.stroke();
            console.log(i + " moyen trait");
        } else {
            ctx.moveTo(0, axe_y_3);
            ctx.lineTo(0, axe_x_3);
            ctx.stroke();
            console.log(i + " petit trait");
        }
    }  
}




// else if (i % 5 == 0) {
//     // multiple de 5
//     // console.log(i + " moyen trait");
//     ctx.moveTo(100,axe_y_2);
//     ctx.lineTo(100,axe_x_2);
//     ctx.stroke();
//     xccordinate = xcoordinate + 5;
// } else {
//     //les autres cas
//     // console.log(i + "petit trait");
//     ctx.moveTo(100,axe_y_3);
//     ctx.lineTo(100,axe_x_3);
//     ctx.stroke();
//     xccordinate = xcoordinate + 1;
// }


// let axe_y_1 = 490;
// let axe_x_1 = 510;
// let axe_y_2 = 492;
// let axe_x_2 = 508
// let axe_y_3 = 495;
// let axe_x_3 = 505;

// window.onload = draw;

// function draw() {
//     var canvas = document.getElementById('sheetmusic');
//     var c = canvas.getContext('2d');
//     var whitespace = 0;
//     var ycoordinate = 10;

//     //draw the staves 'x' number of times requested
//     for (var x = 1; x <= 10; x++) {

//         // draw the staff
//         for (var i = 1; i <= 5; i++){
//             c.strokeStyle = 'black';
//             c.moveTo(0,ycoordinate);
//             c.lineTo(canvas.width,ycoordinate);
//             c.stroke();
//             ycoordinate = ycoordinate + 10;
//         }

//         //draw white space beneath each staff
//         c.fillStyle = 'white';
//         c.fillRect(0,whitespace + 52,canvas.width,52);
//         whitespace = whitespace + 100;
//         ycoordinate = ycoordinate + 30;

//     }
// }