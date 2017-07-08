<link rel="stylesheet" href="/assets/css/nav.css">
<header id="vita-nav">
  <nav class="closed">
    <a href="/">VITA</a> <!-- VITA LOGO -->
    <a href="/signup">Make an Appointment</a>
    <?php
    // if not logged in
    echo '<a href="/login">Log in</a>';
    // else (the user is logged in)
    // <a href="/">Sign out</a>
     ?>
  </nav>
  <!-- For small screens. -->
  <div id="vita-menu-toggle">
    <!-- For modern browsers. -->
    <i class="material-icons">menu</i>
    <!-- For IE9 or below. -->
    <i class="material-icons">&#xE5D2;</i>
  </div>
</header>
<script src="/assets/js/nav.js"></script>
