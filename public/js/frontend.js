$(function() {
    $('.parallax').parallax();
});
function newPos(pos, adjust, ratio){
    return ((pos - adjust) * ratio)  + "px";
}