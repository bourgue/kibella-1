  define(function (require) {
  var _ = require('lodash');
  var $ = require('jquery');
  var angular = require('angular');
  var ConfigTemplate = require('utils/config_template');

  require('directives/config');
  require('components/courier/courier');
  require('components/config/config');
  require('components/notify/notify');
  require('components/typeahead/typeahead');

  require('plugins/dashboard/directives/grid');
  require('plugins/dashboard/components/panel/panel');
  require('plugins/dashboard/services/saved_dashboards');
  require('css!plugins/dashboard/styles/main.css');

  var rison = require('utils/rison');

  // require('plugins/dashboard/directives/share');

  var app = require('ui/modules').get('app/dashboard', [
    'elasticsearch',
    'ngRoute',
    'kibana/courier',
    'kibana/config',
    'kibana/notify',
    'kibana/typeahead'
  ]);

  require('routes')
  .when('/dashboard', {
    template: require('text!plugins/dashboard/index.html'),
    resolve: {
      dash: function (savedDashboards) {
        return savedDashboards.get();
      }
    }
  })
  .when('/dashboard/:id', {
    template: require('text!plugins/dashboard/index.html'),
    resolve: {
      dash: function (savedDashboards, Notifier, $route, $location, courier) {
        return savedDashboards.get($route.current.params.id)
        .catch(courier.redirectWhenMissing({
          'dashboard' : '/dashboard'
        }));
      }
    }
  });

  app.directive('dashboardApp', function (Notifier, courier, AppState, timefilter, kbnUrl) {
    return {
      controller: function ($scope, $rootScope, $route, $routeParams, $location, $http, kbnPath, configFile, Private, getAppState) {
        var queryFilter = Private(require('components/filter_bar/query_filter'));

        var notify = new Notifier({
          location: 'Dashboard'
        });

        var dash = $scope.dash = $route.current.locals.dash;

        if (dash.timeRestore && dash.timeTo && dash.timeFrom && !getAppState.previouslyStored()) {
          timefilter.time.to = dash.timeTo;
          timefilter.time.from = dash.timeFrom;
        }

        if(dash.shared === 1 || dash.shared === "1")
          $scope.shared = true
        else if(dash.shared === 0 || dash.shared === "0")
          $scope.shared = false
        else
          console.error("Invalid value: shared must be 1 or 0 (Number or String)")

        $scope.$on('$destroy', dash.destroy);

        var matchQueryFilter = function (filter) {
          return filter.query && filter.query.query_string && !filter.meta;
        };

        var extractQueryFromFilters = function (filters) {
          var filter = _.find(filters, matchQueryFilter);
          if (filter) return filter.query;
        };

        var stateDefaults = {
          title: dash.title,
          panels: dash.panelsJSON ? JSON.parse(dash.panelsJSON) : [],
          query: extractQueryFromFilters(dash.searchSource.getOwn('filter')) || {query_string: {query: '*'}},
          filters: _.reject(dash.searchSource.getOwn('filter'), matchQueryFilter),
          theme: $rootScope.theme
        };

        var $state = $scope.state = new AppState(stateDefaults);

        $scope.configTemplate = new ConfigTemplate({
          save: require('text!plugins/dashboard/partials/save_dashboard.html'),
          load: require('text!plugins/dashboard/partials/load_dashboard.html'),
          share: require('text!plugins/dashboard/partials/share.html'),
          pickVis: require('text!plugins/dashboard/partials/pick_visualization.html'),
          settings: require('text!plugins/dashboard/partials/settings.html')
        });

        $scope.refresh = _.bindKey(courier, 'fetch');

        timefilter.enabled = true;
        $scope.timefilter = timefilter;
        $scope.$listen(timefilter, 'fetch', $scope.refresh);

        courier.setRootSearchSource(dash.searchSource);

        function init() {
          updateQueryOnRootSource();

          var docTitle = Private(require('components/doc_title/doc_title'));
          if (dash.id) {
            docTitle.change(dash.title);
          }

          $scope.$emit('application.load');
        }


        function updateQueryOnRootSource() {
          var filters = queryFilter.getFilters();
          if ($state.query) {

              if( $state.query.query_string !== undefined       &&
                  $state.query.query_string.query !== undefined &&
                  $state.query.query_string.query !== "*"       ){

                $state.query.query_string.query.split(/( AND | OR | and | or )/)
                  .map(function(str,i) {
                    if(!str.includes(" AND ") && !str.includes(" OR ") &&
                      !str.includes(" and ") && !str.includes(" or ")) {
                      return str.split("=").map(function(str,i) {
                        if(i % 2 !== 0)
                          return str.replace('"', "'").replace('"', "'")
                        return str;
                      }).join('=')
                    }
                    return str;
                  }).join(' ')
              }

            dash.searchSource.set('filter', _.union(filters, [{
              query: $state.query
            }]));
          } else {
            dash.searchSource.set('filter', filters);
          }
        }


        // update root source when filters update
        $scope.$listen(queryFilter, 'update', function () {
          updateQueryOnRootSource();
          $state.save();
        });

        // update data when filters fire fetch event
        $scope.$listen(queryFilter, 'fetch',  $scope.refresh);


        $scope.newDashboard = function () {
          kbnUrl.change('/dashboard', {});
        };

        $scope.filterResults = function () {
          updateQueryOnRootSource();
          $state.save();
          $scope.refresh();
        };

        $scope.save = function () {
          $state.title = dash.id = dash.title;
          $state["theme"] = $rootScope.theme;
          $state.save();
          dash.panelsJSON = angular.toJson($state.panels);
          dash.timeFrom = dash.timeRestore ? timefilter.time.from : undefined;
          dash.timeTo = dash.timeRestore ? timefilter.time.to : undefined;

          dash["theme"] = $rootScope.theme;
          dash.save()
          .then(function (id) {
            $scope.configTemplate.close('save');
            if (id) {
              notify.info('Saved Dashboard as "' + dash.title + '"');
              if (dash.id !== $routeParams.id) {
                kbnUrl.change('/dashboard/{{id}}', {id: dash.id});
              }
            }
          })
          .catch(notify.fatal);
        };

        var pendingVis = _.size($state.panels);
        $scope.$on('ready:vis', function () {
          if (pendingVis) pendingVis--;
          if (pendingVis === 0) {
            $state.save();
            $scope.refresh();
          }
        });

        // listen for notifications from the grid component that changes have
        // been made, rather than watching the panels deeply
        $scope.$on('change:vis', function () {
          $state.save();
        });



        // called by the saved-object-finder when a user clicks a vis
        $scope.addVis = function (hit) {
          $scope.configTemplate.close('pickVis');
          pendingVis++;
          $state.panels.push({ id: hit.id, type: 'visualization', isNew: true });
        };

        $scope.addSearch = function (hit) {
          pendingVis++;
          $state.panels.push({ id: hit.id, type: 'search' });
        };


        
        $rootScope.theme = dash.theme
        $state["theme"] = dash.theme

        
        // Setup configurable values for config directive, after objects are initialized
        $scope.opts = {
          dashboard: dash,
          save: $scope.save,
          addVis: $scope.addVis,
          addSearch: $scope.addSearch,
          isShared: $scope.shared,
          themes: ["bright", "dark"],
          selectedTheme: $rootScope.theme || $rootScope.defaultTheme,
          updateTheme: function(e) {
            $rootScope.theme = $scope.opts.selectedTheme;
            $state["theme"] = $scope.opts.selectedTheme;

            var s = $location.search()

            if(s["_a"] === undefined) return;

            var e = rison.decode(s["_a"]);
            
            if(e.theme === undefined) return;
            
            e.theme = $scope.opts.selectedTheme
            s["_a"] = rison.encode(e);
            $location.search(s).replace();
          
            
           // $scope.refresh();
          },
          changeShared: function() {
            $http.post(kbnPath + '/JSON_SQL_Bridge/dashboard/actions/changeShared.php', { id: $route.current.params.id, sharedValue: $scope.opts.isShared });
          },
          shareData: function () {
            return {
              link: $location.absUrl(),
              rolink: $location.absUrl().replace('?', '?embed&'),
              embed: '<iframe src="' + $location.absUrl().replace('?', '?embed&') +
                '" height="600" width="800"></iframe>'
            };
          }
        };

        init();
      }
    };
  });

  var apps = require('ui/registry/apps');
  apps.register(function DashboardAppModule() {
    return {
      id: 'dashboard',
      name: 'Dashboard',
      icon: '<span class="fa fa-cubes"></span>',
      order: 2
    };
  });
});
