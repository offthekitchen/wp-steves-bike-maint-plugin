<?php
/**
 * Bike Admin Page
 */
?>
<!-- Can links be embedded a better way -->
<link href="https://fonts.googleapis.com/css2?family=Raleway:wght@400;500;700&display=swap" rel="stylesheet" />

<body>
  <div class="main-container">
    
    <?php 
    include(plugin_dir_path(__FILE__) . 'bike-admin-header.php') 
    ?>
    <h1>MY BIKES</h1>
    <main class="main-content">
      <section id="bike-admin-tools" class="bike-admin-tools">
        <div class="cards bike-admin-cards">

          <article class="card manage-bikes-card">
            <div class="card__info-hover">
              <img src="<?php echo plugin_dir_url(__DIR__) . 'img/admin-icon-bikes.png'; ?>" alt="bike icon"
                class="admin-icon">
            </div>
            <div class="card__img"></div>
            <a href="admin.php?page=bikes-admin" class="card_link">
              <div class="card__img--hover"></div>
            </a>
            <div class="card__info">
              <span class="card__subcategory">5 bikes</span>
              <h3 class="card__title">Manage Bikes</h3>
              <span class="card__desc">Add new bikes and maintain existing ones</span>
            </div>
          </article>

          <article class="card manage-specs-card">
            <div class="card__info-hover">
              <img src="<?php echo plugin_dir_url(__DIR__) . 'img/admin-icon-specs.png'; ?>" alt="specs icon"
                class="admin-icon">
            </div>
            <div class="card__img"></div>
            <a href="admin.php?page=specs-admin" class="card_link">
              <div class="card__img--hover"></div>
            </a>
            <div class="card__info">
              <span class="card__subcategory">Subtitle</span>
              <h3 class="card__title">Manage Specs</h3>
              <span class="card__desc">Maintain bike specifications</span>
            </div>
          </article>

          <article class="card manage-maint-card">
            <div class="card__info-hover">
              <img src="<?php echo plugin_dir_url(__DIR__) . 'img/admin-icon-maint.png'; ?>" alt="maint icon"
                class="admin-icon">
            </div>
            <div class="card__img"></div>
            <a href="admin.php?page=maint-admin" class="card_link">
              <div class="card__img--hover"></div>
            </a>
            <div class="card__info">
              <span class="card__subcategory">Last Maintenance: March 1, 2025</span>
              <h3 class="card__title">Manage Maintenance Records</h3>
              <span class="card__desc">Maintain bike maintenance records</span>
            </div>
          </article>

          <article class="card manage-data-card">
            <div class="card__info-hover">
              <img src="<?php echo plugin_dir_url(__DIR__) . 'img/admin-icon-data.png'; ?>" alt="data icon"
                class="admin-icon">
            </div>
            <div class="card__img"></div>
            <a href="admin.php?page=data-admin" class="card_link">
              <div class="card__img--hover"></div>
            </a>
            <div class="card__info">
              <span class="card__subcategory">Subtitle</span>
              <h3 class="card__title">Manage Supporting Data</h3>
              <span class="card__desc">Maintain supporting bike data (e.g. statuses)</span>
            </div>
          </article>

      </section>
    </main>
    <?php include(plugin_dir_path(__FILE__) . 'bike-admin-footer.php'); ?>
  </div>
</body>