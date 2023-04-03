const express = require('express')
const app = express()

const mqtt = require("mqtt");
const $ = require("jquery");

let mqttClient = mqtt.connect('mqtt://broker.emqx.io:8883');

mqttClient.on("connect",() => {
    mqttClient.subscribe("ecg/live", function(err){
        if (!err) console.log("Success subscribing to 'ecg/live'");
    });
    mqttClient.subscribe("ecg/analysisparsed", function(err){
        if (!err) console.log("Success subscribing to 'ecg/analysisparsed'");
    });
});

mqttClient.on("message", (topic, message) => {
    if (topic == "ecg/live") {
        console.log(message.toString().substring(0,20));
    } else if (topic == "ecg/analysisparsed") {
        console.log("Submitting to client...");
        const ecgAnalysisData = (message.toString());
        $.ajax({
            url: "updaterecordECGdata.php",
            type: "POST",
            data: { data: ecgAnalysisData },
            success: function(response) {
                console.log(response);
            }
        });
    };
});

// app.get('/', (req, res) => res.send('Hello World!'))

// app.listen(3000,() => console.log('Example app listening!'))