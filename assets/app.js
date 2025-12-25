const canvas = document.getElementById("signature");
const ctx = canvas.getContext("2d");

let drawing = false;

// Resize canvas to match container
function resizeCanvas() {
    canvas.width = canvas.offsetWidth;
    canvas.height = canvas.offsetHeight;
}
window.addEventListener("resize", resizeCanvas);
resizeCanvas();

// Helpers to get positions
function getMousePos(e) {
    const rect = canvas.getBoundingClientRect();
    return { x: e.clientX - rect.left, y: e.clientY - rect.top };
}
function getTouchPos(e) {
    const rect = canvas.getBoundingClientRect();
    return { x: e.touches[0].clientX - rect.left, y: e.touches[0].clientY - rect.top };
}

// Mouse events
canvas.addEventListener("mousedown", e => {
    drawing = true;
    const pos = getMousePos(e);
    ctx.beginPath();
    ctx.moveTo(pos.x, pos.y);
});
canvas.addEventListener("mousemove", e => {
    if (!drawing) return;
    const pos = getMousePos(e);
    ctx.lineTo(pos.x, pos.y);
    ctx.strokeStyle = "black"; // black ink
    ctx.lineWidth = 2;
    ctx.lineCap = "round";
    ctx.stroke();
    ctx.beginPath();
    ctx.moveTo(pos.x, pos.y);
});
canvas.addEventListener("mouseup", () => { drawing = false; });
canvas.addEventListener("mouseout", () => { drawing = false; });

// Touch events
canvas.addEventListener("touchstart", e => {
    drawing = true;
    const pos = getTouchPos(e);
    ctx.beginPath();
    ctx.moveTo(pos.x, pos.y);
});
canvas.addEventListener("touchmove", e => {
    if (!drawing) return;
    const pos = getTouchPos(e);
    ctx.lineTo(pos.x, pos.y);
    ctx.strokeStyle = "black"; // black ink
    ctx.lineWidth = 2;
    ctx.lineCap = "round";
    ctx.stroke();
    ctx.beginPath();
    ctx.moveTo(pos.x, pos.y);
    e.preventDefault();
});
canvas.addEventListener("touchend", () => { drawing = false; });

// Copy canvas to hidden input before submit
document.getElementById("declarationForm").addEventListener("submit", function(e) {
    const dataURL = canvas.toDataURL("image/png");
    document.getElementById("signature_data").value = dataURL;
});
