<!-- <nav class="navbar bg-primary" data-bs-theme="dark">
  <div class="container-fluid"> -->
  <nav class="navbar navbar-expand-lg bg-primary" data-bs-theme="dark">
  <div class="container-fluid d-flex justify-content-between">
    <div>
  <a class="navbar-brand" href="#">
      
      SMKN 43 Jakarta
    </a>
    </div>
    <div class="d-flex justify-content-end">
   

    <div class="collapse navbar-collapse" id="navbarTogglerDemo02">
    <div>
    <ul class="nav nav-underline me-4">

  <li class="nav-item me-2">
    <a class="nav-link <?php if ($_SESSION["activePage"] == "main") { echo "active"; }?>" aria-current="page" href="main.php">Home</a>
  </li>

  <li class="nav-item me-2">
    <a class="nav-link <?php if ($_SESSION["activePage"] == "datapeserta") { echo "active"; }?>" href="datapeserta.php">Data Peserta</a>
  </li>

  <li class="nav-item me-2">
    <a class="nav-link <?php if ($_SESSION["activePage"] == "datakriteria") { echo "active"; }?>" href="datakriteria.php">Kriteria</a>
  </li>

  <li class="nav-item me-2">
    <a class="nav-link <?php if ($_SESSION["activePage"] == "perankingan") { echo "active"; }?>" aria-disabled="true" href="perankingan.php">Perankingan</a>
  </li>

  <?php if ($_SESSION["role"] == "admin") :?>
  <li class="nav-item me-2">
    <a class="nav-link <?php if ($_SESSION["activePage"] == "user") { echo "active"; }?>" aria-disabled="true" href="user.php">User</a>
  </li>
  <?php endif; ?>

  <li class="nav-item me-2">
    <a class="nav-link text-danger-emphasis"  href="logout.php">Log Out</a>
  </li>


</ul>
  </div>
  </div>
  <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarTogglerDemo02" aria-controls="navbarTogglerDemo02" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

</nav>