// main-themes.js

requirejs(['jquery', 'angular', 'flexslider'], function ($, angular) {

  var app = angular.module('themesApp', []);

  app.constant('baseUrl', BASEURL + 'API/');

  app.constant('ApiMap', {
    getCats: {
      url: 'getCategories.php/index'
    },
    getThemes: {
      url: 'getThemes.php/index'
    }
  });

  app.directive('imgOnLoad', function () {
    return {
      restrict: 'A',
      link: function (scope, element, attrs) {
        element.on('load', function () {
          scope.$apply(attrs.imgOnLoad);
          // usage: <img ng-src="src" img-on-load="imgLoadCallback()" />
        });
      }
    };
  });

  app.factory('themesFactory', ['$http', '$q', '$httpParamSerializer', 'baseUrl', 'ApiMap', function ($http, $q, $httpParamSerializer, baseUrl, ApiMap) {

    var factoryAPI = {
      clientRequest: function (type, dtls) {
        var deferred = $q.defer(),
          config = {
            method: ApiMap[type].method || 'post',
            url: baseUrl + ApiMap[type].url,
            data: $httpParamSerializer(dtls),
            headers: {
              'Content-Type': 'application/x-www-form-urlencoded'
            }
          };

        $http(config).then(function (data) {
          deferred.resolve(data.data);
        }, function (error) {
          deferred.reject(error);
        });

        return deferred.promise;
      }
    };

    return factoryAPI;

    }]);


  app.controller('themesController', ['$scope', '$timeout', '$location', 'themesFactory', function ($scope, $timeout, $location, themesFactory) {

    $scope.currentTheme = {};
    $scope.themesRepo = [];
    $scope.filteredThemes = [];
    
    $scope.params = $location.search();

    function hitApi(type, dtls, callback) {

      themesFactory.clientRequest(type, dtls).then(function (data) {
        callback(data);
      }, function (error) {
        console.log('Error', error);
      });
    }

    hitApi('getCats', {}, function (data) {

      $scope.filtersList = [];

      angular.forEach(data, function (cat) {
        if (cat.id === '30') {
          $scope.filtersList.push(cat);
        } else {
          angular.forEach(cat.categories, function (subcat) {
            $scope.filtersList.push(subcat);
          });
        }
      });
      
      $timeout(function () {
        if (!$scope.params.q) {
          $scope.fnUpdateFilter('featured');
        } else {
          $('.filter_themes_tabs > h4.active.openL + ul').slideDown();
        }
      });
      
    });

    hitApi('getThemes', {}, function (data) {

      if (data.response.theme_data) {
        $scope.themesRepo = data.response.theme_data;

        $timeout(function () {
          // slider for theme
          $('.flexslider').fadeIn().flexslider({
            animation: "slide",
            manualControls: ".device-list-box > .device-list > li",
            start: function () {
              $('.theme-slide-block').show();
            }
          });

          $scope.fnShowPreview($scope.filteredThemes[0]);
        });

        $scope.themesList = [];

        $scope.addMoreThemes();
      } else {
        console.log('Error', data.response);
      }

    });

    $scope.addMoreThemes = function (limit) {
      if ($scope.themesRepo && ($scope.themesList.length < $scope.themesRepo.length)) {
        var limiter = $scope.themesList.length + limit;
        for (var i = $scope.themesList.length; i < $scope.themesRepo.length && i < limiter; i++) {
          $scope.themesList.push($scope.themesRepo[i]);
        }
      }
    };

    $scope.fnShowPreview = function (theme, scroll) {
      $scope.currentTheme = theme;
      if (theme && theme.image.indexOf('Retail') > -1) {
        $scope.featuresList = shopAppFeatures.concat(proAppFeatures);
      }
      else {
        $scope.featuresList = proAppFeatures;
      }
      if (scroll) {
        $('html, body').animate({ scrollTop: $('.slider-body').offset().top - $('.logo_nav').outerHeight() - 20 }, 300);
      }
    };

    $scope.fnUpdateFilter = function (subcat, cat) {

      $scope.checkUpdate = false;

      if (typeof subcat === 'object') {
        $scope.currentCat = subcat.id;
        $scope.filStrict = true;
        $scope.filObj = function (value) {
          var list = value.parent_cat,
            cats = subcat.categories,
            i = list.length;
          while (i--) {
            if ((list[i].id === subcat.id) || (list[i].id === cat.id)) {
              return true;
            }
            if (cats) {
              var j = cats.length;
              while (j--) {
                if (cats[j] && (cats[j].id === list[i].id)) {
                  return true;
                }
              }
            }
          }
          return false;
        };
        if ($scope.searchText) {
          $scope.searchText = '';
        }
      } else if (subcat === 'search') {
        $scope.currentCat = 'search';
        $scope.filStrict = false;
        $scope.filObj = {
          $: $scope.searchText
        };
        $(".filter_themes_tabs > h4").removeClass('active openL').next('ul').slideUp();
      } else if (subcat === 'featured') {
        $scope.currentCat = 'featured';
        $scope.filStrict = true;
        $scope.filObj = {
          featured: '1'
        };
      } else {
        $scope.currentCat = '';
        $scope.filStrict = false;
        $scope.filObj = '';
      }

      $timeout(function () {
        $scope.checkUpdate = true;
        $scope.fnShowPreview($scope.filteredThemes[0]);
        $('html, body').animate({ scrollTop: $('.theme-select-block').offset().top - $('.logo_nav').outerHeight() - 20 }, 300);
      });

    };
    
    // accordion on themes filter block  
    $(document).on('click', '.filter_themes_tabs > h4', function () {
      var self = this;
      if (!$(self).hasClass('active')) {
        $(self).addClass('active');
      }
      if (!$(self).hasClass('noSubCats')) {
        $(self).toggleClass('openL').next('ul:first').slideToggle();
      }
      $(".filter_themes_tabs > h4").each(function () {
        if ($(self).index('.filter_themes_tabs > h4') !== $(this).index('.filter_themes_tabs > h4')) {
          $(this).removeClass('active');
          if ($(this).hasClass('openL')) {
            $(this).removeClass('openL').next('ul').slideUp();
          }
        }
      });
    });

    // hide loader overlay
    $('#screenoverlay').fadeOut();

  }]);


  angular.element(document).ready(function () {
    angular.bootstrap(document, ['themesApp']);
  });

});


var proAppFeatures = [{
    name: 'Unlimited Customisation',
    'image-class': 'icon-unlimitedcustomisation'
},

  {
    name: 'Push Notifications',
    'image-class': 'icon-push-notification'
},

  {
    name: 'Data Integration',
    'image-class': 'icon-data-integration'
},

  {
    name: 'Unlimited App Updates',
    'image-class': 'icon-unlimited-app-updates'
},

  {
    name: 'Signup Forms',
    'image-class': 'icon-signup-forms'
},

  {
    name: 'Marketing Tools',
    'image-class': 'icon-marketing-tools'
},

  {
    name: 'Customised Forms',
    'image-class': 'icon-customised-forms'
},


  {
    name: 'Tools & Utilities',
    'image-class': 'icon-tools-utilities'
},

  {
    name: 'Feedback Form',
    'image-class': 'icon-feedback-form'
},

  {
    name: 'Social APIs',
    'image-class': 'icon-social-apps'
},

  {
    name: 'HQ Photo Gallery',
    'image-class': 'icon-hq-photo-gallery'
},

  {
    name: 'HQ Video Gallery',
    'image-class': 'icon-hq-video-gallery'
},


  {
    name: 'Google Maps',
    'image-class': 'icon-google-maps'
},

  {
    name: 'Events Calendar',
    'image-class': 'icon-events-calendar'
},

  {
    name: 'Social Network Integration',
    'image-class': 'icon-social-network-integration'
},

  {
    name: 'Customised Widgets',
    'image-class': 'icon-customized-widgets'
},

  {
    name: 'Social Sharing',
    'image-class': 'icon-social-sharing'
},

  {
    name: 'In-App Search',
    'image-class': 'icon-in-app-search'
},

  {
    name: 'Music Integration',
    'image-class': 'icon-music-integration'
},

  {
    name: 'Video Integration',
    'image-class': 'icon-video-integration'
},

  {
    name: 'Download User Data',
    'image-class': 'icon-download-user-data'
},

  {
    name: 'Webview Screen',
    'image-class': 'icon-webview-screen'
},

  {
    name: 'Free Splash Screens',
    'image-class': 'icon-free-splash-screens'
},

  {
    name: 'Free Icons',
    'image-class': 'icon-icons'
},

  {
    name: 'Contact Us Card',
    'image-class': 'icon-contact-us-card'
},

  {
    name: 'Click-To-Call',
    'image-class': 'icon-click-to-call'
},

  {
    name: 'Secure Cloud Hosting',
    'image-class': 'icon-secure-cloud-hosting'
},

  {
    name: 'Analytics Dashboard',
    'image-class': 'icon-analytics-dashboard'
},

  {
    name: 'Google Indexing',
    'image-class': 'icon-google-indexing'
},

  {
    name: 'Native Utility',
    'image-class': 'icon-native-utility'
},

  {
    name: 'White Label Apps',
    'image-class': 'icon-white-label-apps'
},

  {
    name: 'Unlimited Downloads',
    'image-class': 'icon-unlimited-downloads'
},

  {
    name: '20+ Languages',
    'image-class': 'icon-unlimited-languages'
}
];
	
var shopAppFeatures = [
  {
    name: 'Inventory Management',
    'image-class': 'icon-inventory-management'
},

  {
    name: 'Payment Gateway',
    'image-class': 'icon-payment-gateway'
},

  {
    name: 'Seamless Web Integration',
    'image-class': 'icon-seamless-web-integration'
},

  {
    name: 'Unlimited Categories ',
    'image-class': 'icon-unlimited-categories'
},

  {
    name: 'Choose Currency',
    'image-class': 'icon-choose-currency'
}
];