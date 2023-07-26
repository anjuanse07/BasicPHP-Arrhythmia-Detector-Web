<?php
include 'database.php';
$pdo = Database::connect();
$currentYear = date('Y');
$currentMonth = date('m');

if (isset($_GET['year']) && isset($_GET['month'])) {
    $selectedYear = $_GET['year'];
    $selectedMonth = $_GET['month'];
} else {
    $selectedYear = $currentYear;
    $selectedMonth = $currentMonth;
}

if (isset($_GET['day'])) {
  $selectedDay = $_GET['day'];
} else {
  $selectedDay = null;
}

$daysInMonth = cal_days_in_month(CAL_GREGORIAN, $selectedMonth, $selectedYear);

?>

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
      .card {background-color: white; box-shadow: 0px 0px 10px 1px rgba(140,140,140,.5); border: 1px solid royalblue; border-radius: 15px;}
      .card.header {background-color: royalblue; color: white; border-bottom-right-radius: 0px; border-bottom-left-radius: 0px; border-top-right-radius: 12px; border-top-left-radius: 12px; padding: 10px;}
      /* ----------------------------------- TOPNAV STYLE */
      .topnav {overflow: hidden; background-color: royalblue; color: white; font-size: 1.2rem; padding: 15px;}
      /* ----------------------------------- */
      /* Calendar container */
      #calendar {
          display: grid;
          grid-template-columns: repeat(7, 1fr);
          grid-gap: 10px;
          width: 600px;
          margin: 0 auto;
      }

      /* Calendar cell */
      .calendar-cell {
          display: flex;
          flex-direction: column;
          align-items: center;
          justify-content: center;
          border: 1px solid #ccc;
          padding: 10px;
      }

      /* Calendar date */
      .calendar-date {
          font-size: 18px;
          font-weight: bold;
      }

      /* Record count */
      .record-count {
          margin-top: 5px;
          font-size: 14px;
          color: #666;
      }
      #calendar-heading {
          text-align: center;
          margin-bottom: 20px;
      }
      #calendar-nav {
          display: flex;
          justify-content: center;
          margin-bottom: 10px;
      }
      #prev-button,
      #next-button {
          margin-right: 10px;
      }
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
      .forms{
        margin-bottom: 20px;
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
      <h3>ECG SIGNAL CALENDAR</h3>
    </div>
    
    <br>
    
    <div id="calendar-heading">
      <h2><?php echo date('F Y', strtotime("$selectedYear-$selectedMonth-01")); ?></h2>
    </div>

    <div class="forms">
        <form id="filter-form">
            <label for="year">Select Year:</label>
            <select id="year" name="year">
                <?php
                for ($year = 2020; $year <= date('Y'); $year++) {
                    $selected = ($year == $selectedYear) ? 'selected' : '';
                    echo "<option value=\"$year\" $selected>$year</option>";
                }
                ?>
            </select>

            <label for="month">Select Month:</label>
            <select id="month" name="month">
                <?php
                for ($month = 1; $month <= 12; $month++) {
                    $monthName = date('F', strtotime("2022-$month-01"));
                    $selected = ($month == $selectedMonth) ? 'selected' : '';
                    echo "<option value=\"$month\" $selected>$monthName</option>";
                }
                ?>
            </select>

            <button class="button" type="submit">Filter</button>
        </form>
    </div>
    
    <br>

    <div id="calendar">
        <?php
        $startDate = date('Y-m-01', strtotime("$selectedYear-$selectedMonth-01"));
        $endDate = date('Y-m-t', strtotime("$selectedYear-$selectedMonth-01"));
        $query = "SELECT DATE(created_at) AS date, COUNT(*) AS count FROM ecg_table_record 
                  WHERE created_at BETWEEN :startDateTime AND :endDateTime 
                  GROUP BY DATE(created_at)";
        $startDateTime = "$startDate 00:00";
        $endDateTime = "$endDate 23:59";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':startDateTime', $startDateTime);
        $stmt->bindParam(':endDateTime', $endDateTime);
        $stmt->execute();
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        for ($day = 1; $day <= $daysInMonth; $day++) {
            $date = date('Y-m-d', strtotime("$selectedYear-$selectedMonth-$day"));
            $recordCount = 0;
            
            foreach ($results as $result) {
                if ($result['date'] === $date) {
                    $recordCount = $result['count'];
                    break;
                }
            }

            $selectedClass = ($selectedDay == $day) ? 'selected-day' : '';

            echo "<div class='calendar-cell'>"
                    . "<div class='calendar-date $selectedClass'>$day</div>"
                    . "<div class='record-count'>$recordCount</div>"
                    . "<div class='show-data-link'>";
            if ($recordCount > 0) {
                echo "<a href='data_list.php?date=$date'>Show Data</a>";
            } else {
                echo "No Data";
            }
            echo "</div></div>";
        }
        ?>
    </div>

    <br>

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
        2023 &#169;: Monitoring EKG menggunakan protokol MQTT
      </div>
    </footer>

  </body>
</html>