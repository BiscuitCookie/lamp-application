<?php

require dirname(__DIR__, 1) . "/inc/bootstrap.php";

$uri = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);
$uri = explode( "/", $uri );

// this is ugly. why can"t we use yml like all the normal people do?
if (isset($uri[1]) && $uri[1] == "user") {
  if (isset($uri[2])) {
    switch ($uri[2]) {
      case "list":
      case "add":
        require PROJECT_ROOT_PATH . "/Controller/Api/UserController.php";
        $objFeedController = new UserController();
        $strMethodName = $uri[2] . "Action";
        $objFeedController->{$strMethodName}();
        break;
      default:
        header("HTTP/1.1 404 Not Found");
        exit();
        break;
    }
  }
}
?>

<!DOCTYPE html>
<html lang="en">
  <head>
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <title>TEST APPLICATION</title>
      <link rel="stylesheet" href="/css/bulma.min.css">
  </head>
  <body>
    <section class="hero is-medium is-info is-bold">
        <div class="hero-body">
            <div class="container has-text-centered">
                <h1 class="title">
                  TEST APPLICATION
                </h1>
            </div>
        </div>
    </section>
    <section class="section">
      <div class="columns is-gapless" style="height: 100%; margin-top: 0; margin-bottom: 0;">
        <div class="column is-5" style="min-height: 500px;">
          <h2 class="view-entries">Orders Review (IDs are hardcoded as a concept)</h2>
          <table class="table" id="overview-table">
            <tr>
              <th>Full Name</th>
              <th>Title</th>
              <th>Price</th>
            </tr>
          </table>
        </div>
        <div class="column" style="overflow: auto;">
          <h2 class="post-entries">Post New Product</h2>
          <form onsubmit="event.preventDefault(); sendData();">
            <label for="product-info">JSON data with products:</label><br>
            <textarea rows="5" cols="80" id="product-info"></textarea>
            <input type="submit" value="Submit">
          </form>
        </div>
      </div>        
    </section>
  </body>
</html>
<script>
  const requestUrl = "./user/list?uids=1,2";
  const requestJSON = async url => {
    const response = await (await fetch(url)).json();
    const result = Object.groupBy(response, ({ full_name }) => full_name);
    const table = document.getElementById("overview-table");
    for (const [key, value] of Object.entries(result)) {
      let rowspan = value.length;
      for (let [index, row] of value.entries()) {
        let fullName = row.full_name, title = row.title, price = row.price;
        let rowInsert = table.insertRow(-1);
        let c1 = rowInsert.insertCell(0), c2 = rowInsert.insertCell(1), c3 = rowInsert.insertCell(2);
        if (rowspan > 1) {
          if (index == 0) {
            c1.rowSpan = rowspan;
            c1.innerText = fullName;
            c2.innerText = title;
            c3.innerText = price;
          } else {
            c1.innerText = title;
            c2.innerText = price;
          }
        } else {
          c1.innerText = fullName;
          c2.innerText = title;
          c3.innerText = price;
        }
      };
    }
  }
  requestJSON(requestUrl);
  function sendData() {
    const xhr = new XMLHttpRequest();
    xhr.open("POST", "/user/add");
    xhr.setRequestHeader("Content-Type", "application/json; charset=UTF-8");
    const jsonString = document.getElementById("product-info").value;
    const body = jsonString;
    xhr.onload = () => {
      if (xhr.readyState == 4 && xhr.status == 201) {
        console.log(JSON.parse(xhr.responseText));
      } else {
        console.log(`Error: ${xhr.status}`);
      }
    };
    xhr.send(body);
  }
</script>