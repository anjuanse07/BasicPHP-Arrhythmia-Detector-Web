<!DOCTYPE HTML>
<html>
  <head>
    <title>ECG Databases</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css"/>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet"/>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
      html {font-family: Arial; display: inline-block; text-align: center;}
      p {font-size: 1.2rem;}
      h4 {font-size: 0.8rem;}
      body {margin: 0;}
      /* ----------------------------------- TOPNAV STYLE */
      .topnav {overflow: hidden; background-color: royalblue; color: white; font-size: 1.2rem; padding: 15px;}
      /* ----------------------------------- */
      
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
        background-color: royalblue;
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
      /* ----------------------------------- */
      
      /* ----------------------------------- BUTTON STYLE */
      .btn-group .button {
        background-color: royalblue; 
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
        background-color: royalblue;
        transform: translateY(1px);
      }

      .btn-group .button:disabled,
      .button.disabled{
        color:#fff;
        background-color: #a0a0a0; 
        cursor: not-allowed;
        pointer-events:none;
      }
      /* ----------------------------------- */

      .forms{
        margin-top: 20px;
        background-color: royalblue; 
        border: 1px solid #e3e3e3;
        color: white;
        padding: 5px 8px;
        text-align: center;
        text-decoration: none;
        display: inline-block;
        font-size: 14px;
        cursor: pointer;
        /* padding-top: 20px; */
      }

      #table_id2 {
        display: none;
      }

      /* ----------------------------------- */
      footer {
        margin-top: 30px;
        padding: 20px;
        color: white;
        background-color: royalblue;
        text-align: center;
        font-weight: bold;
      }
    </style>
  </head>
  
  <body>
    <div class="topnav">
      <h3>ECG Data Segments & Parameter</h3>
    </div>
    
    <br>
    
    <h3 style="color: royalblue; font-size: 0.8 rem;">RECORDED DATA TABLE</h3>
    
    <table class="styled-table" id= "table_id">
      <thead>
        <tr>
          <!-- <th>NO</th> -->
          <th>ID</th>
          <th>R-R Avg</th>
          <th>P-R Avg</th>
          <th>Q-S Avg</th>
          <th>Q-T Avg</th>
          <th>S-T Avg</th>
          <th>Heart Rate</th>
          <th>Classification</th>
          <th>TIME</th>
          <th>DATE (dd-mm-yyyy)</th>
        </tr>
      </thead>
      <tbody id="tbody_table_record">
        <?php
          include 'database.php';
          // $num = 0;
          
          $pdo = Database::connect();
          $sql = 'SELECT * FROM ecg_table_record ORDER BY date, time';
          foreach ($pdo->query($sql) as $row) {
            $date = date_create($row['date']);
            $dateFormat = date_format($date,"d-m-Y");
            // $num++;
            echo '<tr>';
            // echo '<td>'. $num . '</td>';
            echo '<td class="bdr">'. $row['id'] . '</td>';
            echo '<td class="bdr">'. $row['rr'] . '</td>';
            echo '<td class="bdr">'. $row['pr'] . '</td>';
            echo '<td class="bdr">'. $row['qs'] . '</td>';
            echo '<td class="bdr">'. $row['qt'] . '</td>';
            echo '<td class="bdr">'. $row['st'] . '</td>';
            echo '<td class="bdr">'. $row['heartrate'] . '</td>';
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

    <h3 style="color: royalblue; font-size: 0.8 rem; margin-top : 20px;">RECORDED DATA TABLE : FILTER</h3>

    <form class="forms" id="filter-form">
      <label for="start-date">Start Date :</label>
      <input type="date" id="start-date" name="start_date">

      <label for="end-date">End Date :</label>
      <input type="date" id="end-date" name="end_date">

      <label for="start-time">Start Time :</label>
      <input type="time" id="start-time" name="start_time">

      <label for="end-time">End Time :</label>
      <input type="time" id="end-time" name="end_time">

      <button class="button" type="submit">Filter</button>
    </form>

    <table class="styled-table" id= "table_id2" style="margin-top:20px;">
      <thead>
        <tr>
          <th>ID</th>
          <th>R-R Avg</th>
          <th>P-R Avg</th>
          <th>Q-S Avg</th>
          <th>Q-T Avg</th>
          <th>S-T Avg</th>
          <th>Heart Rate</th>
          <th>Classification</th>
          <th>TIME</th>
          <th>DATE (dd-mm-yyyy)</th>
        </tr>
      </thead>
      <tbody id="tbody_table_recorded">

      </tbody>
    </table>
    
    <br>

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
      $('#filter-form').submit(function(event) {
      event.preventDefault();

      const form_data = $(this).serialize();
      // console.log(form_data)

      $.ajax({
        url: "load_filtered_data.php",
        type: "POST",
        data: form_data,
        dataType: "html",
        success: function(data) {
          document.getElementById('table_id2').style.display = 'table';
  
          // Clear the current table rows
          var tableRows = document.getElementById("tbody_table_recorded");
          tableRows.innerHTML = '';
          // var tableRows = '';
          var parsedData = JSON.parse(data);
          parsedData.forEach(function(row) {
            var date = new Date(row.date);
            var dateFormat = date.getDate() + '-' + (date.getMonth()+1) + '-' + date.getFullYear();
            tableRows += '<tr>';
            tableRows += '<td class="bdr">' + row.id + '</td>';
            tableRows += '<td class="bdr">' + row.rr + '</td>';
            tableRows += '<td class="bdr">' + row.pr + '</td>';
            tableRows += '<td class="bdr">' + row.qs + '</td>';
            tableRows += '<td class="bdr">' + row.qt + '</td>';
            tableRows += '<td class="bdr">' + row.st + '</td>';
            tableRows += '<td class="bdr">' + row.heartrate + '</td>';
            tableRows += '<td class="bdr">' + row.classification + '</td>';
            tableRows += '<td class="bdr">' + row.time + '</td>';
            tableRows += '<td>' + dateFormat + '</td>';
            tableRows += '</tr>';
          });
          $('#tbody_table_recorded').html(tableRows);
        },
        error: function() {
          alert('Error getting filtered data');
        }
      });
    });


    </script>
  </body>
</html>