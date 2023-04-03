<!DOCTYPE html>
<html>
<head>
    <title>My MQTT Client</title>
    <!-- <script src="pahomqtt.js"></script> -->
    <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/paho-mqtt/1.1.0/paho-mqtt.min.js" type="text/javascript"></script> -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/paho-mqtt/1.0.1/mqttws31.js" type="text/javascript"></script>
    
</head>
<body>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script type="text/javascript">
        // client = new Paho.MQTT.Client("broker.emqx.io", Number(8883));
        // client = new Paho.MQTT.Client("driver.cloudmqtt.com", 8883);
        const client = new Paho.MQTT.Client("broker.emqx.io" ,Number(8883))

        client.onConnectionLost = onConnectionLost;
        client.onMessageArrived = onMessageArrived;

        client.connect({
            onSuccess: onConnect,
            onFailure: onFailure,
            useSSL: true
        });

        function onConnect() {
            console.log("Connected to MQTT broker.");
            client.subscribe("ecg/analysisparsed");
        }

        function onFailure() {
            console.log("Failed to connect to MQTT broker.");
        }

        function onConnectionLost(response) {
            if (response.errorCode !== 0) {
                console.log("Connection lost: " + response.errorMessage);
            }
        }

        function onMessageArrived(message) {
            console.log("Received message: " + message.payloadString);
            const ecgAnalysisData = JSON.parse(message.toString());
            id = ecgAnalysisData.id;
            rr = ecgAnalysisData.rr;
            rr_stdev = ecgAnalysisData.rr_stdev;
            pr = ecgAnalysisData.pr;
            pr_stdev = ecgAnalysisData.pr_stdev;
            qs = ecgAnalysisData.qs;
            qs_stdev = ecgAnalysisData.qs_stdev;
            qt = ecgAnalysisData.qt;
            qt_stdev = ecgAnalysisData.qt_stdev;
            st = ecgAnalysisData.st;
            st_stdev = ecgAnalysisData.st_stdev;
            heartrate = ecgAnalysisData.heart_rate;
            classification_result = ecgAnalysisData.classification_result;
            ecg_graph = ecgAnalysisData.ecg_graph;
            
            $.post('updaterecordECGdata.php', {
            id: id,
            rr: rr,
            rr_stdev: rr_stdev,
            pr: pr,
            pr_stdev: pr_stdev,
            qs: qs,
            qs_stdev: qs_stdev,
            qt: qt,
            qt_stdev: qt_stdev,
            st: st,
            st_stdev: st_stdev,
            heartrate: heartrate,
            classification: classification_result,
            ecg_graph: ecg_graph

            }, function(response) {
            console.log('Server response: ' + response);
            });
        }
    </script>
</body>
</html>