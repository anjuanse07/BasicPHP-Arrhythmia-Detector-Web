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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
      html {font-family: Arial; display: inline-block; text-align: center;}
      p {font-size: 1.2rem;}
      h4 {font-size: 0.8rem;}
      body {margin: 0;}
      .card {background-color: white; box-shadow: 0px 0px 10px 1px rgba(140,140,140,.5); border: 1px solid #0c6980; border-radius: 15px;}
      .card.header {background-color: #0c6980; color: white; border-bottom-right-radius: 0px; border-bottom-left-radius: 0px; border-top-right-radius: 12px; border-top-left-radius: 12px; padding: 10px;}
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
        /* ----------------------------------- TABLE STYLE */
      .styled-table {
        border-collapse: collapse;
        margin-left: auto; 
        margin-right: auto;
        font-size: 0.9em;
        font-family: sans-serif;
        min-width: 400px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.15);
        border-radius: 0.5em;
        overflow: hidden;
        width: 90%;
      }

      .styled-table thead tr {
        background-color: #0c6980;
        color: #ffffff;
        text-align: left;
      }

      .styled-table th {
        padding: 12px 15px;
        text-align: center;
      }

      .styled-table td {
        padding: 12px 15px;
        text-align: center;
      }

      .styled-table tbody tr:nth-of-type(even) {
        background-color: #f3f3f3;
      }

      .styled-table tbody tr.active-row {
        font-weight: bold;
        color: #009879;
      }

      .bdr {
        border-right: 1px solid #e3e3e3;
        border-left: 1px solid #e3e3e3;
      }
      
      td:hover {background-color: rgba(12, 105, 128, 0.21);}
      tr:hover {background-color: rgba(12, 105, 128, 0.15);}
      .styled-table tbody tr:nth-of-type(even):hover {background-color: rgba(12, 105, 128, 0.15);}
      /* ----------------------------------- BUTTON STYLE */
      .btn-group .button {
        background-color: #0c6980; 
        border: 1px solid #e3e3e3;
        color: white;
        padding: 5px 8px;
        text-align: center;
        text-decoration: none;
        display: inline-block;
        font-size: 14px;
        cursor: pointer;
      }

      .btn-group .button:not(:last-child) {
        border-right: none; 
      }

      .btn-group .button:hover {
        background-color: #094c5d;
      }

      .btn-group .button:active {
        background-color: #0c6980;
        transform: translateY(1px);
      }

      .btn-group .button:disabled,
      .button.disabled{
        color:#fff;
        background-color: #a0a0a0; 
        cursor: not-allowed;
        pointer-events:none;
      }

      footer {
        margin-top: 30px;
        padding: 20px;
        color: white;
        background-color: #0c6980;
        text-align: center;
        font-weight: bold;
      }
      /* ----------------------------------- BUTTON ID STYLE */
      .table-row-button {
        cursor: pointer;
      }
      .table-row-button:hover {
        border-color: #007bff;
        background-color: orange;
      }
    </style>
  </head>
    
  <body>
    <div class="topnav">
      <h3>ECG SIGNAL MONITORING</h3>
    </div>
    
    <br>
    
    <h3 style="color: #0c6980;">Electrocardiogram Recorded Data</h3>

    <br>
    <!-- ----------------------------------- CANVAS ---------------------------------------- -->
    <div class="jumbotron">
        <canvas
            class="p-2 mx-1 shadow-lg rounded-3 d-flex justify-content-center no-chart"
            id="myChart"
            width="1200"
            height="500"
        >
        </canvas>
    </div>

    <br>
    <!-- ----------------------------------- TABEL 1 ---------------------------------------- -->

    <table class="styled-table" id= "table_id">
      <thead>
        <tr>
          <th>ID</th>
          <th>Classification</th>
          <th>TIME</th>
          <th>DATE (dd-mm-yyyy)</th>
        </tr>
      </thead>
      <tbody id="tbody_table_record">
        <?php
            include 'database.php';
            
            $pdo = Database::connect();
            $sql = 'SELECT * FROM ecg_table_record ORDER BY date, time';
            foreach ($pdo->query($sql) as $row) {
                $date = date_create($row['date']);
                $dateFormat = date_format($date,"d-m-Y");
                echo '<tr  onclick="loadData('.$row['id'].')">';
                echo '<td class="bdr table-row-button">'. $row['id'] . '</td>';
                echo '<td class="bdr">'. $row['classification'] . '</td>';
                echo '<td class="bdr">'. $row['time'] . '</td>';
                echo '<td>'. $dateFormat . '</td>';
                echo '</tr>';
            }
            Database::disconnect();
          
        ?>
      </tbody>
    </table>

    <br>
    <!-- ----------------------------------- Button ---------------------------------------- -->

    <div class="btn-group">
      <button class="button" id="btn_prev" onclick="prevPage()">Prev</button>
      <button class="button" id="btn_next" onclick="nextPage()">Next</button>
      <div style="display: inline-block; position:relative; border: 0px solid #e3e3e3; margin: 4px; justify-content: center; text-align:center;">
        <p style="position:relative; font-size: 14px; text-align:center; margin:auto; padding: 0px 10px"> Table : <span id="page"></span></p>
      </div>
      <select name="number_of_rows" id="number_of_rows">
        <option value="10">10</option>
        <option value="25">25</option>
        <option value="50">50</option>
        <option value="100">100</option>
      </select>
      <button class="button" id="btn_apply" onclick="apply_Number_of_Rows()">Apply</button>
    </div>

    <br>
<!-- ----------------------------------- Tabel 2 ---------------------------------------- -->
    <div class="container">
      <h3 class="jumbotron-fluid" style="padding-top: 15px; padding-bottom: 15px; color: #0c6980;">ECG Parameters</h3>
      <p class="card header">Table Classes for PQRST Segments and Classification</p>            
      <table class="table table-hover">
        <thead>
          <tr>
            <th>Segments</th>
            <th>Interval</th>
            <th>Stdev</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>RR</td>
            <td id="rr_val"></td>
            <td id="rr_stdev"></td>
          </tr>
          <tr>
            <td>PR</td>
            <td id="pr_val"></td>
            <td id="pr_stdev"></td>
          </tr>
          <tr>
            <td>QS</td>
            <td id="qs_val"></td>
            <td id="qs_stdev"></td>
          </tr>
          <tr>
            <td>QT</td>
            <td id="qt_val"></td>
            <td id="qt_stdev"></td>
          </tr>
          <tr>
            <td>ST</td>
            <td id="st_val"></td>
            <td id="st_stdev"></td>
          </tr>
        </tbody>
      </table>
    </div>
<!-- ----------------------------------- tabel 3 ---------------------------------------- -->
    <div class="container">            
      <table class="table table-hover">
        <thead>
          <tr>
            <th>Parameters</th>
            <th>Results</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>Heart Rate (bpm)</td>
            <td id="heart_rate"></td>
          </tr>
          <tr>
            <td>Classification Result</td>
            <td id="classification_result"></td>
          </tr>
        </tbody>
      </table>
    </div>

<!-- ----------------------------------- FOOTER ---------------------------------------- -->
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
    </footer>
    <script>
      document.getElementById("rr_val").innerHTML = "0"; 
      document.getElementById("pr_val").innerHTML = "0";
      document.getElementById("qs_val").innerHTML = "0"; 
      document.getElementById("qt_val").innerHTML = "0"; 
      document.getElementById("st_val").innerHTML = "0";
      document.getElementById("rr_stdev").innerHTML = "0"; 
      document.getElementById("pr_stdev").innerHTML = "0";
      document.getElementById("qs_stdev").innerHTML = "0"; 
      document.getElementById("qt_stdev").innerHTML = "0"; 
      document.getElementById("st_stdev").innerHTML = "0"; 
      document.getElementById("heart_rate").innerHTML = "0"; 
      document.getElementById("classification_result").innerHTML = "unknown";
    </script>
<!-- ----------------------------------- SCRIPT GRAPH ---------------------------------------- -->
    <script>
      const labels = [];
      const graphData = {
          labels: labels,
          datasets: [
              {
              label: "ECG Recorded Graph",
              backgroundColor: "rgb(255, 0, 0)",
              borderColor: "rgb(255, 0, 0)",
              data: [],
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
<!-- ----------------------------------- SCRIPT BUTTON-Tabel1 ---------------------------------------- -->
    <script>
      //------------------------------------------------------------
      var current_page = 1;
      var records_per_page = 10;
      var l = document.getElementById("table_id").rows.length
      //------------------------------------------------------------
      
      //------------------------------------------------------------
      function apply_Number_of_Rows() {
        var x = document.getElementById("number_of_rows").value;
        records_per_page = x;
        changePage(current_page);
      }
      //------------------------------------------------------------
      
      //------------------------------------------------------------
      function prevPage() {
        if (current_page > 1) {
            current_page--;
            changePage(current_page);
        }
      }
      //------------------------------------------------------------
      
      //------------------------------------------------------------
      function nextPage() {
        if (current_page < numPages()) {
            current_page++;
            changePage(current_page);
        }
      }
      //------------------------------------------------------------
      
      //------------------------------------------------------------
      function changePage(page) {
        var btn_next = document.getElementById("btn_next");
        var btn_prev = document.getElementById("btn_prev");
        var listing_table = document.getElementById("table_id");
        var page_span = document.getElementById("page");
       
        // Validate page
        if (page < 1) page = 1;
        if (page > numPages()) page = numPages();

        [...listing_table.getElementsByTagName('tr')].forEach((tr)=>{
            tr.style.display='none'; // reset all to not display
        });
        listing_table.rows[0].style.display = ""; // display the title row

        for (var i = (page-1) * records_per_page + 1; i < (page * records_per_page) + 1; i++) {
          if (listing_table.rows[i]) {
            listing_table.rows[i].style.display = ""
          } else {
            continue;
          }
        }
          
        page_span.innerHTML = page + "/" + numPages() + " (Total Number of Rows = " + (l-1) + ") | Number of Rows : ";
        
        if (page == 0 && numPages() == 0) {
          btn_prev.disabled = true;
          btn_next.disabled = true;
          return;
        }

        if (page == 1) {
          btn_prev.disabled = true;
        } else {
          btn_prev.disabled = false;
        }

        if (page == numPages()) {
          btn_next.disabled = true;
        } else {
          btn_next.disabled = false;
        }
      }
      //------------------------------------------------------------
      
      //------------------------------------------------------------
      function numPages() {
        return Math.ceil((l - 1) / records_per_page);
      }
      //------------------------------------------------------------
      
      //------------------------------------------------------------
      window.onload = function() {
        var x = document.getElementById("number_of_rows").value;
        records_per_page = x;
        changePage(current_page);
      };
      //------------------------------------------------------------
    </script>
    <script>
      function loadData(id) {
      // Send AJAX request to PHP script with ID parameter
      $.ajax({
          url: "load_history_data.php",
          type: "POST",
          data: {id: id},
          dataType: "json",
          success: function(data) {
              // Update table with retrieved data
              $("#rr_val").html(data.rr);
              $("#rr_stdev").html(data.rr_stdev);
              $("#pr_val").html(data.pr);
              $("#pr_stdev").html(data.pr_stdev);
              $("#qs_val").html(data.qs);
              $("#qs_stdev").html(data.qs_stdev);
              $("#qt_val").html(data.qt);
              $("#qt_stdev").html(data.qt_stdev);
              $("#st_val").html(data.st);
              $("#st_stdev").html(data.st_stdev);
              $("#heart_rate").html(data.heartrate);
              $("#classification_result").html(data.classification);
              $(".header").text("Table Classes for PQRST Segments and Classification, Table ID : " + id);

              $.ajax({
              url: "load_graph_data.php",
              type: "POST",
              data: {id: id},
              dataType: "json",
              success: function(graphData) {
                // console.log(graphData);
                // graphData = JSON.parse(graphData);
                // console.log("labels:", graphData.labels);
                // console.log("graphData:", graphData.graphData);
                // Update graph with retrieved data
                const labels = graphData.labels;
                const data = graphData.graphData.map(Number);
                const config = {
                  type: "line",
                  data: {
                    labels: labels,
                    datasets: [
                      {
                        label: "ECG Recorded Graph",
                        backgroundColor: "rgb(255, 0, 0)",
                        borderColor: "rgb(255, 0, 0)",
                        data: data,
                        pointRadius: 0,
                        fill: false,
                      },
                    ],
                  },
                  options: {
                    animation: {
                      onComplete: function () {
                        console.log(myChart.toBase64Image());
                      },
                    },
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

                const ctx = document.getElementById("myChart").getContext("2d");
                myChart = new Chart(ctx, config);
              },
            });

          }
        });
      }
  
    </script>
  </body>
</html>