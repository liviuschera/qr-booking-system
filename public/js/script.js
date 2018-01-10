// https://github.com/schmich/instascan <--- scan and decode qr code
// https://davidshimjs.github.io/qrcodejs/ <-- encodes a string into qr code

document.addEventListener('DOMContentLoaded', function() {

    // References to all the element we will need.
    const video = document.querySelector('#camera-stream');
    const image = document.querySelector('#snap');
    const message = document.querySelector('#message');
    const app = document.querySelector('.app');
    const scanner = new Instascan.Scanner({video: video});

    // video.classList.add("visible");
    scanner.addListener('scan', function(content) {

        let d = new Date();
        let splitContent = content.split('|');

        let [qrYear, qrMonth, qrDay] = splitContent[4].split('-');
        let [qrHour, qrMin] = splitContent[5].split(':');
        let [year, month, day, hour, min] = [d.getFullYear(), d.getMonth(), d.getDate(), d.getHours(), d.getMinutes()];

        message.classList.add("visible");
        message.innerHTML = `<h1>Hi ${splitContent[0]}</h1>`;

        if (qrYear >= year && qrMonth >= month && qrDay >= day) {
            if (qrDay > day) {
                if (message.classList.contains("red")) {
                    message.classList.remove("red")
                };
                message.classList.add("aqua");
                if (qrDay - day === 1) {
                    message.innerHTML += `<p>This ${splitContent[2]} class is scheduled for tomorrow at ${qrHour}:${qrMin}</p>`;
                } else {
                    message.innerHTML += `<p>Please contact the reception for more information.</p>`;
                }
            }
            if (qrDay == day) {
                if (qrHour >= hour) {
                    if (message.classList.contains("red")) {
                        message.classList.remove("red")
                    };
                    if (message.classList.contains("aqua")) {
                        message.classList.remove("aqua")
                    };
                    let calculateMin = qrMin - min >= 0
                        ? qrMin - min
                        : 60 - min;
                    let plural = qrHour - hour === 1
                        ? "hour"
                        : "hours";
                    let timeToClassStart = qrHour - hour >= 0
                        ? `${calculateMin} mins`
                        : `${qrHour - hour} ${plural} and ${calculateMin} mins`;

                    message.innerHTML += `<p> Your <strong>${splitContent[2]}</strong> class will start in ${timeToClassStart}.</p>`;
                    message.innerHTML += `Enjoy!`
                } else {
                    if (message.classList.contains("aqua")) {
                        message.classList.remove("aqua")
                    };
                    message.classList.add("red");
                    message.innerHTML += `<p>This ticket is no longer valid. Please ask reception staff for more details.</p>`;
                }
            }

        }
        console.log(year, month, day, hour, min);
        console.log(content);
    });
    Instascan.Camera.getCameras().then(function(cameras) {
        if (cameras.length > 0) {
            scanner.start(cameras[0]);
        } else {
            console.error('No cameras found.');
            message.classList.add("visible");
            message.classList.add("red");
            message.innerText = 'No camera found.';

        }
    }).catch(function(e) {
        console.error("Ups: ", e);
    });

});
