console.log("BEGIN OF TEST SCRIPT");
console.log("requiring pvcloud api module...");
var pvCloud = require("./pvcloud_api.js");
var counter = 0;
var capturedDatetime = '2015-02-20+09:22';

console.log("First ADD call");

pvCloud.API.Add("INIT TEST SCRIPT", "1", "BOOL", function (error, response, body) {
    console.log("Callback for First ADD call");
    console.log(body);
});

timedADDCall();

console.log("END OF TEST SCRIPT... Timeouts and callbacks still active");

function timedADDCall() {
    counter++;
    console.log("Timed ADD Call #" + counter);
    pvCloud.API.Add("TEST SCRIPT - COUNTER", counter, "INT", function (error, response, body) {
        console.log("Callback for Timed ADD call");
        console.log(body);
        setTimeout(timedADDCall, 10000);
    });
}