// require("dotenv").config();

// const io = require("socket.io")(process.env.SOCKET_IO_PORT,{
//     cors: {
//         origin: "*",
//         methods: ["GET","POST"],
//     },
// });

// // const redisAdapter = require("socket.io-redis");
// // io.adapter(
// //     redisAdapter({
// //         host: process.env.REDIS_HOST,
// //         port: process.env.REDIS_PORT,
// //         user: process.env.REDIS_USERNAME,
// //         password: process.env.REDIS_PASSWORD,
// //     })
// // );

// // const {Sequelize} = require("sequelize");
// // const sequelize = new Sequelize(
// //     process.env.DB_NAME,
// //     process.env.DB_USERNAME,
// //     process.env.DB_PASSWORD,
// //     {
// //         host: process.env.DB_HOST,
// //         dialect: "mariadb",
// //     }
// // );

// const mqtt = require("mqtt");

// // let mqttClient = mqtt.connect(
// //     'mqtt://&{process.env.MQTT_HOST}:${process.env.MQTT_PORT}',
// //     {
// //         username: process.env.MQTT_USERNAME,
// //         password: process.env.MQTT_PASSWORD,
// //     }
// // );

// let mqttClient = mqtt.connect('mqtt://broker.emqx.io:8883');

// mqttClient.on("connect",() => {
//     mqttClient.subscribe("ecg/live", function(err){
//         if (!err) console.log("Success subscribing to 'ecg/live'");
//     });
//     mqttClient.subscribe("ecg/analysisparsed", function(err){
//         if (!err) console.log("Success subscribing to 'ecg/analysisparsed'");
//     });
// });

// mqttClient.on("message", (topic, message) => {
//     if (topic == "ecg/live") {
//         io.emit("ceksocket.io", message.toString());
//         console.log(message.toString().substring(0,20));
//     } else if (topic == "ecg/analysisparsed") {
//         console.log("Submitting to client...");
//         io.emit("ecganalysis", message.toString());

//         const ecgAnalysisData = (message.toString());
//         $.ajax({
//             url: "updaterecordECGdata.php",
//             type: "POST",
//             data: { data: ecgAnalysisData },
//             success: function(response) {
//                 console.log(response);
//             },
//             error: function(xhr) {
//                 console.log(xhr);
//             }
//         });

//         // if(ecgAnalysisData.classification_result == "Normal") return;

//         // Classified.create({
//         //     user_id: "1", 
//         //     rr : ecgAnalysisData.rr_avg,
//         //     rr_stdev : ecgAnalysisData.rr_dev,
//         //     pr : ecgAnalysisData.pr_avg,
//         //     pr_stdev : ecgAnalysisData.pr_dev,
//         //     qs : ecgAnalysisData.qs_avg,
//         //     qs_stdev : ecgAnalysisData.qs_dev,
//         //     qt : ecgAnalysisData.qt_avg,
//         //     qt_stdev : ecgAnalysisData.qt_dev,
//         //     st : ecgAnalysisData.st_avg,
//         //     st_stdev : ecgAnalysisData.st_dev,
//         //     heartrate : ecgAnalysisData.heart_rate,
//         //     classification_result : ecgAnalysisData.classification_result,
//         // })
//         // .then((classified) => {
//         //     const packedBulk = ecgAnalysisData.ecg_graph.map((e) => {
//         //         return {
//         //             classified_id: classified.id,
//         //             data: e,
//         //         };
//         //     });

//         //     raw.bulkCreate(packedBulk).catch((error) => {
//         //         console.log(error);
//         //     });
//         // })
//     };
// });




//===================================================================

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