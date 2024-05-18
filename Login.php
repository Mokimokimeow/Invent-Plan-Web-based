<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login</title>
  <link rel="stylesheet" type="text/css" href="../asset/css/login.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
  <div class="wrapper">
    <div class="container main">
      <div class="row">
        <div class="col-md-6 side-img">
          <img src="../asset/img/Logo.png" alt="" width="400">
        </div>
        <div class="col-md-6 right">
          <div class="input-box">
            <header>Log In</header>

            <form action="Login-Func.php" id="login-form" method="POST">
              <div class="input-field">
                <input type="text" class="input" name="username" id="username" placeholder="Username" required autocomplete="off">
                <label>Username</label>
              </div>

              <div class="input-field">
                <input type="password" class="input" name="password" id="password" placeholder="Password" required>
                <label>Password</label>
              </div>

              <div class="input-field">
                <input type="submit" id="submit" class="submit" value="Log In">
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script>
    document.getElementById("login-form").addEventListener("submit", function(event) {
      event.preventDefault();
      var form = this;
      var formData = new FormData(form);

      fetch("Login-Func.php", {
        method: "POST",
        body: formData
      })
     .then(response => response.json())
     .then(data => {
        if (data.success) {
          Swal.fire({
            icon: 'success',
            title: 'Login Successful',
            showConfirmButton: false,
            timer: 1500
          }).then(function() {
            window.location.href = 'dashboard.php';
          });
        } else {
          Swal.fire({
            icon: 'error',
            title: 'Invalid username or password',
            showConfirmButton: false,
            timer: 1500
          });
        }
      })
    });
  </script>
</body>
</html>