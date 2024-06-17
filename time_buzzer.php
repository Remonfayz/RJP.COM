<?php
session_start();
if (!isset($_SESSION['Admin-name'])) {
  header("location: login.php");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Determine the lecture time</title>
    
    <style>
        body {
            /* color: #511616; */
            font-family: Arial, sans-serif;
            background: linear-gradient(to right, #531c1c, #181833);
            margin: 0;
            padding: 0;
            text-align: center;
            height: 100vh; /* Ensure full viewport height */
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        h1 {
            color: whitesmoke;
        }
        form {
            width: 50%;
            margin: 0 auto;
            text-align: center;
            padding-top: 50px;
        }
        label {
            display: block;
            margin-bottom: 10px;
            color: white;
            size: 40;
        }
        input[type="time"] {
            padding: 5px;
            width: 200px;
            font-size: 16px;
        }
        input[type="button"] {
            padding: 10px 20px;
            background-color: #511616;
            color: white;
            border: none;
            cursor: pointer;
            font-size: 18px;
        }
        #buzzerTime {
            margin-top: 20px;
            text-align: center;
            font-size: 18px;
        }
        .logo-img {
            text-align: center;
            margin-top: 20px;
        }
        .logo-img img {
            width: 200px;
            height: 200px;
            border-radius: 50%;
            object-fit: cover;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0);
        }
    </style>
    <script>
        function setBuzzerTime() {
            var time = document.getElementById("time").value;

            // تحديد الوقت المحدد
            var now = new Date();
            var buzzerTime = new Date(now.getFullYear(), now.getMonth(), now.getDate(), now.getHours(), now.getMinutes(), now.getSeconds());
            var parts = time.split(":");
            buzzerTime.setHours(parseInt(parts[0]));
            buzzerTime.setMinutes(parseInt(parts[1]));

            // عرض الوقت المحدد
            document.getElementById("buzzerTime").innerHTML = "The specified time: " + buzzerTime.toLocaleTimeString();

            // تحديد مؤقت لتشغيل البازر بعد الوقت المحدد
            setTimeout(function() {
                var xhttp = new XMLHttpRequest();
                xhttp.onreadystatechange = function() {
                    if (this.readyState == 4 && this.status == 200) {
                        console.log(this.responseText);
                    }
                };
                xhttp.open("POST", "<?php echo $_SERVER['PHP_SELF']; ?>", true);
                xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                xhttp.send("time=" + time);
                document.getElementById("buzzerTime").innerHTML = "The bell was rang.";
            }, buzzerTime - now);
        }
    </script>
</head>
<body>
<?php include'header.php'; ?> 

    <div class="logo-img">
        <img src="WhatsApp Image 2024-06-15 at 00.16.33_c02fbb28.jpg" alt="Logo">
    </div>

    <h1 align='center'>Determine the lecture time</h1>
    <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
        <label for="time">Choose time:</label>
        <input type="time" id="time" name="time" required><br><br>
        <input type="button" value="Determine the time" onclick="setBuzzerTime()">
    </form>

    <div id="buzzerTime"></div>

    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["time"])) {
        $node_mcu_url = 'http://192.168.103.170/control_buzzer'; // Replace with the actual IP address of your NodeMCU device

        $ch = curl_init($node_mcu_url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);

        if ($response === false) {
            echo '<p>There was an error connecting to the NodeMCU device.</p>';
        } else {
            echo '<p>The buzzer was successfully activated.</p>';
        }
    }
    ?>
</body>
</html>
