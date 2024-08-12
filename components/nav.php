<nav class="navbar navbar-expand-lg bg-body-tertiary">
    <div class="container">
        <a class="navbar-brand" href="/enrollment/index.php">ENROLLMENT</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
          <ul class="navbar-nav me-auto mb-2 mb-lg-0">
            <li class="nav-item">
              <a class="nav-link text-dark" href="/enrollment/index.php">Home</a>
            </li>
          </ul>

            <?php
                if ($authenticated) {
            ?>
                <ul class="navbar-nav">
                    <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle text-dark" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Admin
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="/enrollment/profile.php">Profile</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="/enrollment/logout.php">Logout</a></li>
                    </ul>
                    </li>
                </ul>
            <?php
                } else {
            ?>
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a href="/enrollment/register.php" class="btn btn-outline-primary me-2">Register</a>
                    </li>
                    <li>
                        <a href="/enrollment/login.php" class="btn btn-primary">Login</a>
                    </li>
                </ul>
            <?php
                }
            ?>
            
        </div>
    </div>
    </nav>