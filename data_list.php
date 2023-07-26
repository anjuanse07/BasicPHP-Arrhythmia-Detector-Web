<?php
// data_list.php
include 'database.php';
$pdo = Database::connect();
// Check if the date parameter is provided
if (isset($_GET['date'])) {
    $selectedDate = $_GET['date'];
} else {
    // If no date is provided, redirect back to the calendar page
    header("Location: calendar.php");
    exit();
}

// Query the database to get the data for the selected day
$query = "SELECT * FROM ecg_table_record WHERE DATE(created_at) = :selectedDate AND classification = 'Abnormal'";
$stmt = $pdo->prepare($query);
$stmt->bindParam(':selectedDate', $selectedDate);
$stmt->execute();
$dataList = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Data List for <?php echo $selectedDate; ?></title>
    <style>
      html {font-family: Arial; display: inline-block; text-align: center;}
      body {margin: 0;}
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
    </style>
</head>
<body>

<h3 style="color: royalblue; font-size: 0.8 rem;">RECORDED DATA TABLE FOR <?php echo $selectedDate; ?></h3>

<table class="styled-table" id="table_id">
    <thead>
        <tr>
            <!-- <th>ID</th> -->
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
        foreach ($dataList as $row) {
            $date = date_create($row['created_at']);
            $dateFormat = date_format($date, "d-m-Y");
            echo '<tr>';
            // echo '<td class="bdr">' . $row['id'] . '</td>';
            echo '<td class="bdr">' . $row['rr'] . '</td>';
            echo '<td class="bdr">' . $row['pr'] . '</td>';
            echo '<td class="bdr">' . $row['qs'] . '</td>';
            echo '<td class="bdr">' . $row['qt'] . '</td>';
            echo '<td class="bdr">' . $row['st'] . '</td>';
            echo '<td class="bdr">' . $row['heartrate'] . '</td>';
            echo '<td class="bdr">' . $row['classification'] . '</td>';
            echo '<td class="bdr">' . $row['time'] . '</td>';
            echo '<td>' . $dateFormat . '</td>';
            echo '</tr>';
        }
        ?>
    </tbody>
</table>

    <script>
    </script>
</body>
</html>
