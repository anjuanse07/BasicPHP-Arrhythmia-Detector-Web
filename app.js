const express = require('express')
const app = express()

const mqtt = require("mqtt");
const axios = require('axios');

// let mqttClient = mqtt.connect('mqtt://broker.emqx.io:1883');
let mqttClient = mqtt.connect('mqtt://localhost:1883');

mqttClient.on('connect', function () {
  console.log('MQTT client connected');
});

mqttClient.on("connect",() => {
    mqttClient.subscribe("ecglive", function(err){
        if (!err) console.log("Success subscribing to 'ecglive'");
    });
    mqttClient.subscribe("ecg/analysisparsed", function(err){
        if (!err) console.log("Success subscribing to 'ecg/analysisparsed'");
    });
});

// var today = new Date();
// var date = today.getFullYear()+'-'+(today.getMonth()+1)+'-'+today.getDate();
// var time = today.getHours() + ":" + today.getMinutes() + ":" + today.getSeconds(); 
// var dateTime = date+' '+time;
// console.log(dateTime)
// console.time("for_debug");

// console.timeEnd("for_debug");
// console.log(ecgAnalysisData);

mqttClient.on('message', (topic, message) => {
  if (topic == 'ecg/live') {
    console.log("successs");
  } else if (topic == 'ecg/analysisparsed') {
    console.log('Submitting to client...');
    // const ecgAnalysisData = message.toString();
    console.time("for_debug");
    let ecgAnalysisData = JSON.parse(message.toString());
    if (ecgAnalysisData.classification === "Normal") {
      Object.keys(ecgAnalysisData).forEach(key => {
        if (key !== "ecg_graph") {
          ecgAnalysisData[key] = 0;
          // delete ecgAnalysisData[key];
        }
      });
    }
    // if (ecgAnalysisData.classification === "Normal") return;
    ecgAnalysisData = JSON.stringify(ecgAnalysisData);
    console.timeEnd("for_debug");
    axios.post('http://localhost/COBA_2/updaterecordECGdata.php', ecgAnalysisData)
        .then(function (response) {
      console.log("Data Transfered..");
    })
    .catch(function (error) {
      console.log("error");
    });
    // axios.post('http://localhost/COBA_2/home.php', ecgAnalysisData)
    // .then(function (response) {
    //   console.log("Data Transfered..");
    // })
    // .catch(function (error) {
    //   console.log("error");
    // });
  };
});