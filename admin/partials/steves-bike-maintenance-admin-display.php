<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       http://www.offthekitchen.com
 * @since      1.0.0
 *
 * @package    Steves_Bike_Maintenance
 * @subpackage Steves_Bike_Maintenance/admin/partials
 */
?>
<!-- Can LIs be embedded a better way -->
 <link
    href="https://fonts.googleapis.com/css2?family=Raleway:wght@400;500;700&display=swap"
    rel="stylesheet"
  />
<div class="main-container">
      <header>
        <h1>MY BIKES</h1>
      </header>
      <main class="main-content">
        <section id="bike-admin-tools" class="bike-admin-tools">
          <div class="cards bike-admin-cards">
            <div class="card-container manage-bikes-card-container">
              <a href="#" class="card-link">
                <article class="card">
                  <h3 class="card-title">Bikes</h3>
                  <div class="card-subtitle">
                    5 bikes
                  </div>
                  <img
                    src="<?php echo plugin_dir_url( __DIR__ ).'img/manage-bikes-thumbnail.jpg'; ?>"
                    alt="Image of several bikes on a rack"
                    class="admin-thumbnail"
                  />
                  <div class="card-content">  
                    <img 
                    src="<?php echo plugin_dir_url( __DIR__ ).'img/admin-icon-bikes.png'; ?>"
                    alt="bike icon"
                    class="admin-icon">               
                    <div class="card-txt">
                      Add new bikes and maintain existing ones
                    </div>
                  </div>
                </article>
              </a>
            </div>
            <div class="card-container manage-specs-card-container">
              <a href="#" class="card-link">
                <article class="card">
                  <h3 class="card-title">Specs</h3>
                  <img
                    src="<?php echo plugin_dir_url( __DIR__ ).'img/manage-specs-thumbnail.jpg'; ?>"
                    alt="Image of bike tools hanging in a workshop"
                    class="admin-thumbnail"
                  />
                  <div class="card-content">
                    <img 
                    src="<?php echo plugin_dir_url( __DIR__ ).'img/admin-icon-specs.png'; ?>"
                    alt="specs icon"
                    class="admin-icon">               
                    <div class="card-txt">
                      Maintain bike specifications
                    </div>
                  </div>
                </article>
              </a>
            </div>
            <div class="card-container manage-maint-card-container">
              <a href="#" class="card-link">
                <article class="card">
                  <h3 class="card-title">Matinenance Records</h3>
                  <img
                    src="<?php echo plugin_dir_url( __DIR__ ).'img/manage-maint-thumbnail.jpg'; ?>"
                    alt="Image of bike manuals"
                    class="admin-thumbnail"
                  />
                  <div class="card-content">
                  <img 
                    src="<?php echo plugin_dir_url( __DIR__ ).'img/admin-icon-maint.png'; ?>"
                    alt="wrench icon"
                    class="admin-icon">   
                    <div class="card-txt">      
                        Maintain bike maintenance records
                      </div>
                  </div>
                </article>
              </a>
            </div>
            <div class="card-container manage-statuses-card-container">
              <a href="#" class="card-link">
                <article class="card">
                  <h3 class="card-title">Supporting Data</h3>
                  <img
                    src="<?php echo plugin_dir_url( __DIR__ ).'img/manage-data-thumbnail.jpg'; ?>"
                    alt="Image of a bike on a work rack"
                    class="admin-thumbnail"
                  />
                  <div class="card-content">
                  <img 
                    src="<?php echo plugin_dir_url( __DIR__ ).'img/admin-icon-data.png'; ?>"
                    alt="gear icon"
                    class="admin-icon">   
                      <div class="card-txt">
                        Maintain supporting bike data (e.g. statuses)
                      </div>

                  </div>
                </article>
              </a>
            </div>
          </div>
      </main>
      <footer class="admin-footer">
        <div class="footer-section">
          <h3 class="footer-header">About</h3>
          <ul class="footer-list">
            <li class="footer-item">
              <a href="#">Plugin Installation</a>
            </li>
            <li class="footer-item">
              <a href="#">Terms and Conditions</a>
            </li>
            <li class="footer-item">
              <a href="#">Data and Privacy</a>
            </li>
          </ul>
        </div>
        <div class="footer-section">
          <h3 class="footer-header">Footer 2</h3>
          <ul class="footer-list">
            <li class="footer-item">
              <a href="#">Footer 2A</a>
            </li>
            <li class="footer-item">
              <a href="#">Footer 2B</a>
            </li>
          </ul>
        </div>
        <div class="footer-section">
          <h3 class="footer-header">Contact</h3>
          <ul class="footer-list">
            <li class="footer-item">
              <a href="#">Email</a>
            </li>
            <li class="footer-item">
              <a href="#">Website</a>
            </li>
          </ul>
        </div>
      </footer>
    </div>
  </body>
