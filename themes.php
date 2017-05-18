  <!-- start header -->
  <?php require_once('website_header_new.php');?>
    <?php
              unset($_SESSION['catid']);
              unset($_SESSION['themeid']);
              unset($_SESSION['currentpagevisit']);
            ?>
  <!-- end header -->
      
<link rel="stylesheet" href="css/theme-page.css" />

<!-- start main wrap -->
<section id="main-wrap" ng-controller="themesController" ng-cloak>

      <!-- start theme wrap -->
      <section id="theme-wrap">
        <div class="box-row theme-width">

          <!-- start theme slide block -->
          <article class="theme-slide-block">

            <!-- start features box -->
            <div class="features-box">
              <div class="features-heading">
                <h3>Features</h3>
              </div>
              <div class="features-list-area">
                <ul class="features-list">
                  <li ng-repeat="feature in featuresList">
                    <a href="" title="{{ feature.name }}">
                      <span class="cat-list-icon" ng-class="feature['image-class']"></span>
                      <span class="cat-list-name" ng-bind="feature.name"></span>
                    </a>
                  </li>
                </ul>
              </div>
            </div>
            <!-- end features box -->

            <!-- start slider box -->
            <div class="slider-box">
              <div class="slider-header">
                <h2>Choose Your Theme</h2>
                <p>Professional features, custom builds and expert assistance.</p>
              </div>
              <div class="slider-body">
                <div class="flexslider" style="display: none;">
                  <ul class="slides">
                    <li>
                      <div class="device-frame" ng-class="{ 'animated fadeIn': currentTheme.loaded }">
                        <img ng-hide="currentTheme.loaded" class="img-loader" src="images/ajax-loader_new.gif" />
                        <div class="tablet" ng-show="currentTheme.loaded"> <img alt="" src="images-new/iPad-frame.png">
                          <div class="change-theme-tab">
                            <img alt="" ng-src="{{ currentTheme.image2x || currentTheme.image_new || currentTheme.image }}" img-on-load="currentTheme.loaded = true;" img-loading="currentTheme.loaded = false;">
                          </div>
                        </div>
                        <div class="mobile" ng-show="currentTheme.loaded"> <img alt="" src="images-new/iPhone-frame.png">
                          <div class="change-theme-mobile"><img alt="" ng-src="{{ currentTheme.image_new || currentTheme.image }}"></div>
                        </div>
                      </div>
                    </li>
                    <li>
                      <div class="device-frame android" ng-class="{ 'animated fadeIn': currentTheme.loaded }">
                        <img ng-hide="currentTheme.loaded" class="img-loader" src="images/ajax-loader_new.gif" />
                        <div class="tablet" ng-show="currentTheme.loaded"> <img alt="" src="images-new/tablet-frame.png">
                          <div class="change-theme-tab">
                            <img alt="" ng-src="{{ currentTheme.image2x || currentTheme.image_new || currentTheme.image }}" img-on-load="currentTheme.loaded = true;" img-loading="currentTheme.loaded = false;">
                          </div>
                        </div>
                        <div class="mobile" ng-show="currentTheme.loaded"> <img alt="" src="images-new/theme-mobile.png">
                          <div class="change-theme-mobile"><img alt="" ng-src="{{ currentTheme.image_new || currentTheme.image }}"></div>
                        </div>
                      </div>
                    </li>
                  </ul>
                </div>
                <div class="device-list-box">
                  <ul class="device-list">
                    <li class="active clearfix"><a href="">iOS</a></li>
                    <li><a href="">Android</a></li>
                  </ul>
                </div>
              </div>
              <div class="slider-footer">
                <div class="app-type-name">
                  <p>App Type: <span ng-bind="currentTheme.name"></span></p>
                </div>
                <div class="create-app-button"><a href="{{ ((currentTheme.top_cat.id === '30') ? 'catalogue-app' : 'panel') + '.php?themeid=' + currentTheme.id + '&catid=' + currentTheme.parent_cat[0].id + '&app=create' }}">Create App <i class="fa fa-sign-in fa-lg"></i></a></div>
              </div>
            </div>
            <!-- end slider box -->

          </article>
          <!-- end theme slide block -->

          <!-- start theme select block -->
          <article class="theme-select-block">
            <div class="theme-header">
              <h2>All Themes</h2>
              <div class="theme-search">
                <input type="text" placeholder="Search Themes..." ng-model="searchText" ng-keyup="fnUpdateFilter('search')" />
              </div>
            </div>
            <div class="loader" ng-hide="filtersList && themesRepo">
              <img src="images/ajax-loader_new.gif" />
            </div>
            <div class="theme-left" ng-show="filtersList && themesRepo">
              <div class="filter_themes">
                <div class="filter_themes_tabs">
                  <h4 class="noSubCats" ng-class="{ 'active': !params.q }" ng-click="fnUpdateFilter('featured')">Featured</h4>
                </div>
                <div class="filter_themes_tabs">
                  <h4 class="noSubCats" ng-click="fnUpdateFilter()">All</h4>
                </div>
                <div class="filter_themes_tabs" ng-repeat="cat in filtersList" ng-if="cat.name">
                  <h4 ng-bind="cat.name" ng-class="{ 'active openL': (params.q && (params.q === cat.id)) }" ng-click="fnUpdateFilter(cat, cat)" ng-init="(params.q && (params.q === cat.id)) ? fnUpdateFilter(cat, cat) : ''"></h4>
                  <ul class="filter_themes-list">
                    <li ng-repeat="subcat in cat.categories" ng-if="subcat.name" ng-class="{ 'active': (subcat.id === currentCat) }">
                      <a href="" ng-click="fnUpdateFilter(subcat, cat)" ng-bind="subcat.name"></a>
                    </li>
                  </ul>
                </div>
              </div>
            </div>
            <div class="theme-right">
              <div class="theme-collection">
                <div ng-if="!filteredThemes.length">
                  <h3 class="noThemes">No Themes Found.</h3>
                </div>
                <ul class="theme-collection-list clearfix" ng-show="filtersList && themesRepo">
                  <li ng-repeat="theme in filteredThemes = (themesRepo | filter: filObj : filStrict | orderBy: '-featured')" ng-class="{ 'active': (currentTheme.id === theme.id), 'animated fadeIn': checkUpdate }">
                    <a href="" ng-click="fnShowPreview(theme, true)">
                      <img ng-hide="theme.loaded" class="img-loader" src="images/ajax-loader_new.gif" />
                      <img ng-src="{{ theme.image_new || theme.image }}" ng-show="theme.loaded" ng-class="{ 'animated fadeIn': theme.loaded }" img-on-load="theme.loaded = true;" />
                      <span ng-bind="theme.name"></span>
                    </a>
                  </li>
                </ul>
              </div>
            </div>
            <div class="clearfix"></div>
          </article>
          <!-- end theme select block -->

        </div>
      </section>
      <!-- end content wrap -->

</section>
<!-- end main wrap -->

<!-- start footer -->
<?php require_once('website_footer_new.php');?>
<!-- end footer -->

<!--Always load script files after the footer-->
<script src="js/main-themes.js"></script>