<!DOCTYPE HTML>
<html>
  <head>
    <title>ECG Databases</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">
    <link rel="icon" href="data:,">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet"/>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css"/>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.min.js"></script>
    <!-- <script src="https://cdn.jsdelivr.net/npm/chart.js"></script> -->
    <style>
      html {font-family: Arial; display: inline-block; text-align: center;}
      p {font-size: 1.2rem;}
      h4 {font-size: 0.8rem;}
      body {margin: 0;}
      /* ----------------------------------- TOPNAV STYLE */
      .topnav {overflow: hidden; background-color: #0c6980; color: white; font-size: 1.2rem; padding: 15px;}
      /* ----------------------------------- */
      .jumbotron {
        flex: 0 0 100%;
        max-width: 100%;
        margin-bottom: 20px;
        justify-content: center;
        display: flex;
      }

      canvas {
        max-width: auto;
        justify-content: center;
        column-fill: auto;
      }

      footer {
        margin-top: 30px;
        padding: 20px;
        color: white;
        background-color: #0c6980;
        text-align: center;
        font-weight: bold;
      }
    </style>
  </head>
    
  <body>
    <div class="topnav">
      <h3>ECG SIGNAL MONITORING</h3>
    </div>
    
    <br>
    
    <h3 style="color: #0c6980;">LIVE ECG GRAPH</h3>

    <br>

    <div class="jumbotron">
        <canvas
            class="p-2 mx-1 shadow-lg rounded-3 d-flex justify-content-center no-chart"
            id="myChart"
            width="1200"
            height="500"
        >
        </canvas>
    </div>
    <footer class="contens" id="footer">
      <div class="cards p-4 pb-0">
        <section class="mb-4">
          <a
            class="btn btn-outline-light btn-floating m-1"
            href="https://mail.google.com/mail/u/0/?view=cm&tf=1&fs=1&to=emailanda@gmail.com!"
            role="button"
            ><i class="bi bi-envelope"></i
          ></a>
          <a
            class="btn btn-outline-light btn-floating m-1"
            href="https://www.linkedin.com/in/seanjuliuslase/"
            role="button"
            ><i class="bi bi-linkedin"></i
          ></a>
          <a
            class="btn btn-outline-light btn-floating m-1"
            href="https://github.com/anjuanse07"
            role="button"
            ><i class="bi bi-github"></i
          ></a>
          <a
            class="btn btn-outline-light btn-floating m-1"
            href="https://www.instagram.com/seanjuu_/"
            role="button"
            ><i class="bi bi-instagram"></i
          ></a>
        </section>
      </div>

      <div class="text-center p-3">
        2023 &#169;: Monitoring EKG menggunakan proktol MQTT
      </div>
      <!-- Copyright -->
    </footer>
    <?php 
      include 'database.php';
      $pdo = Database::connect();
      $max_ecg_id_query = 'SELECT MAX(ecg_id) AS max_ecg_id FROM ecg_raw_test_2';
      $max_ecg_id_result = $pdo->query($max_ecg_id_query);
      $max_ecg_id = $max_ecg_id_result->fetch(PDO::FETCH_ASSOC)['max_ecg_id'];

      $sql = 'SELECT data_val FROM ecg_raw_test_2 WHERE ecg_id = :ecg_id';
      $q = $pdo->prepare($sql);
      $q->execute(array(':ecg_id' => $max_ecg_id));

      $graphData = array();
      while ($row = $q->fetch(PDO::FETCH_ASSOC)) {
          $graphData[] = $row['data_val'];
      }
      $labels = range(1, count($graphData));

      Database::disconnect();

      $graphData = json_encode($graphData);
      $labels = json_encode($labels);
    ?>
    <script>
      const labels = <?php echo $labels; ?>;
      const graphData = {
          labels: labels,
          datasets: [
              {
              label: "ECG Live Graph",
              backgroundColor: "rgb(255, 0, 0)",
              borderColor: "rgb(255, 0, 0)",
              data: <?php echo $graphData; ?>,
              pointRadius: 0,
              fill: false,
              },
          ],
          options: {
              animation: {
              onComplete: function () {
                  console.log(myChart.toBase64Image());
              },
              },
          },
      };

      const config = {
          type: "line",
          data: graphData,
          options: {
              scales: {
              y: {
                  title: {
                  display: true,
                  text: "Amplitudo (V)",
                  },
              },
              x: {
                  ticks: {
                  autoskip: false,
                  },
                  title: {
                  display: true,
                  text: "n_data",
                  },
              },
              },
              spanGaps: true,
              responsive: true,
              maintainAspectRatio: true,
          },
      };

      const myChart = new Chart(document.getElementById('myChart'), config);
    </script>
    
  </body>
</html>